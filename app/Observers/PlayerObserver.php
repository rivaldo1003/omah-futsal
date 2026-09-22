<?php

namespace App\Observers;

use App\Models\Player;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Automatically generates a transparent-background cutout (photo_cutout)
 * whenever a player's photo is created or changed.
 *
 * Engine priority:
 *   1. remove.bg API (REMOVE_BG_API_KEY in .env)
 *   2. local `rembg` CLI (pip install rembg)
 *
 * If neither engine is available, the cutout is simply skipped -
 * the site falls back to the original photo. Never breaks.
 */
class PlayerObserver
{
    public function saved(Player $player): void
    {
        if (!$player->wasChanged('photo') || empty($player->photo)) {
            return;
        }

        try {
            $source = $this->resolveSource($player);
            if (!$source) {
                return;
            }

            $png = env('REMOVE_BG_API_KEY')
                ? $this->cutWithRemoveBg($source)
                : $this->cutWithRembg($source);

            if (!$png) {
                return;
            }

            $outPath = 'players/cutouts/player-' . $player->id . '.png';
            Storage::disk('public')->makeDirectory('players/cutouts');
            Storage::disk('public')->put($outPath, $png);

            Player::withoutEvents(fn () => $player->update(['photo_cutout' => $outPath]));
        } catch (\Throwable $e) {
            Log::warning('Player cutout generation failed: ' . $e->getMessage());
        }
    }

    private function resolveSource(Player $player): ?string
    {
        $photo = $player->photo;

        if (filter_var($photo, FILTER_VALIDATE_URL)) {
            $content = @file_get_contents($photo);
            if ($content === false) {
                return null;
            }
            $tmp = tempnam(sys_get_temp_dir(), 'pimg');
            file_put_contents($tmp, $content);
            return $tmp;
        }

        foreach ([$photo, 'players/photos/' . $photo, 'players/' . $photo] as $c) {
            $clean = ltrim($c, '/\\');
            if (Storage::disk('public')->exists($clean)) {
                $tmp = tempnam(sys_get_temp_dir(), 'pimg');
                file_put_contents($tmp, Storage::disk('public')->get($clean));
                return $tmp;
            }
        }

        return null;
    }

    private function cutWithRemoveBg(string $path): ?string
    {
        $response = Http::asMultipart()
            ->withToken(env('REMOVE_BG_API_KEY'))
            ->attach('image_file', fopen($path, 'r'), 'photo.jpg')
            ->post('https://api.remove.bg/v1.0/removebg', [
                'size'   => 'regular',
                'format' => 'png',
                'type'   => 'person',
            ]);

        return $response->successful() ? $response->body() : null;
    }

    private function cutWithRembg(string $path): ?string
    {
        exec('command -v rembg 2>/dev/null', $lines, $code);
        if ($code !== 0) {
            return null;
        }

        $out = $path . '.cutout.png';
        exec('rembg i -m u2net ' . escapeshellarg($path) . ' ' . escapeshellarg($out) . ' 2>/dev/null', $lines, $code);
        if ($code !== 0 || !file_exists($out)) {
            return null;
        }

        $png = file_get_contents($out);
        @unlink($out);
        return $png ?: null;
    }
}
