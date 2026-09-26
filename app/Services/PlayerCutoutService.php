<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Generates transparent-background player cutouts.
 *
 * Engine priority:
 *   1. remove.bg API  (REMOVE_BG_API_KEY)
 *   2. local `rembg` CLI (pip install rembg)
 *
 * Shared by PlayerObserver and the players:generate-cutouts command so both
 * behave identically. Failures are logged (previously they were silent, which
 * made it impossible to tell why cutouts were not generated in production).
 */
class PlayerCutoutService
{
    /**
     * remove.bg API key from config (survives config:cache), falling back to env().
     */
    public function removeBgKey(): ?string
    {
        return config('services.remove_bg.key') ?: env('REMOVE_BG_API_KEY');
    }

    /**
     * Detect which engine is available. Returns 'removebg', 'rembg' or null.
     */
    public function detectEngine(): ?string
    {
        if ($this->removeBgKey()) {
            return 'removebg';
        }

        if ($this->rembgBinary()) {
            return 'rembg';
        }

        return null;
    }

    /**
     * Locate the rembg executable robustly.
     *
     * The web/PHP-FPM process usually has a minimal PATH (e.g. /usr/bin:/bin),
     * so `command -v rembg` fails even when rembg is installed. We therefore
     * also probe common install locations and any path set via config/env.
     */
    public function rembgBinary(): ?string
    {
        // 1. Explicit override.
        $configured = config('services.rembg.path') ?: env('REMBG_PATH');
        if ($configured && @is_executable($configured)) {
            return $configured;
        }

        // 2. Current PATH.
        $which = $this->which('rembg');
        if ($which) {
            return $which;
        }

        // 3. Common install locations.
        $home = getenv('HOME') ?: '';
        $candidates = [
            '/usr/local/bin/rembg',
            '/opt/homebrew/bin/rembg',
            '/usr/bin/rembg',
            '/opt/local/bin/rembg',
            $home . '/.local/bin/rembg',
        ];

        foreach (glob('/Library/Frameworks/Python.framework/Versions/*/bin/rembg') ?: [] as $g) {
            $candidates[] = $g;
        }
        foreach (glob($home . '/.pyenv/versions/*/bin/rembg') ?: [] as $g) {
            $candidates[] = $g;
        }
        foreach (glob('/usr/local/python/*/bin/rembg') ?: [] as $g) {
            $candidates[] = $g;
        }

        foreach ($candidates as $c) {
            if ($c && @is_executable($c)) {
                return $c;
            }
        }

        return null;
    }

    /**
     * Generate a transparent PNG from a local source file.
     * Returns raw PNG bytes, or null if no engine is available / it failed.
     */
    public function generate(string $sourcePath): ?string
    {
        if (!file_exists($sourcePath)) {
            Log::warning('Player cutout skipped: source file not found at ' . $sourcePath);
            return null;
        }

        $engine = $this->detectEngine();
        if (!$engine) {
            Log::warning('Player cutout skipped: no engine available. Set REMOVE_BG_API_KEY, or install rembg (pip install rembg) and/or set REMBG_PATH.');
            return null;
        }

        return $engine === 'removebg'
            ? $this->cutWithRemoveBg($sourcePath)
            : $this->cutWithRembg($sourcePath);
    }

    private function which(string $bin): ?string
    {
        $cmd = PHP_OS_FAMILY === 'Windows' ? 'where ' : 'command -v ';
        exec($cmd . escapeshellarg($bin) . ' 2>/dev/null', $lines, $code);
        if ($code === 0 && !empty($lines[0])) {
            $path = trim($lines[0]);
            if (@is_executable($path)) {
                return $path;
            }
        }
        return null;
    }

    private function cutWithRemoveBg(string $path): ?string
    {
        try {
            $response = Http::asMultipart()
                ->withToken($this->removeBgKey())
                ->attach('image_file', fopen($path, 'r'), 'photo.jpg')
                ->post('https://api.remove.bg/v1.0/removebg', [
                    'size'   => 'regular',
                    'format' => 'png',
                    'type'   => 'person',
                ]);

            if (!$response->successful()) {
                Log::warning('remove.bg cutout failed: HTTP ' . $response->status() . ' ' . substr($response->body(), 0, 200));
                return null;
            }

            return $response->body();
        } catch (\Throwable $e) {
            Log::warning('remove.bg cutout error: ' . $e->getMessage());
            return null;
        }
    }

    private function cutWithRembg(string $sourcePath): ?string
    {
        $bin = $this->rembgBinary();
        if (!$bin) {
            Log::warning('rembg cutout failed: binary not found.');
            return null;
        }

        $out = $sourcePath . '.cutout.png';
        $cmd = escapeshellarg($bin) . ' i -m u2net '
            . escapeshellarg($sourcePath) . ' ' . escapeshellarg($out) . ' 2>&1';
        exec($cmd, $lines, $code);

        if ($code !== 0 || !file_exists($out)) {
            Log::warning('rembg cutout failed (exit ' . $code . '): ' . implode(' | ', array_slice($lines, -3)));
            return null;
        }

        $png = file_get_contents($out);
        @unlink($out);

        return $png ?: null;
    }
}