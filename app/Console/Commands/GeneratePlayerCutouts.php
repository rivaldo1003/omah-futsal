<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Services\PlayerCutoutService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Generate transparent-background cutouts for player photos.
 *
 * Two engines (auto-detected, in order):
 *   1. remove.bg API  - set REMOVE_BG_API_KEY in .env (free tier: 50/mo)
 *   2. local `rembg` CLI - pip install rembg (free, unlimited, runs locally)
 *
 * Usage:
 *   php artisan players:generate-cutouts            # all players missing a cutout
 *   php artisan players:generate-cutouts --id=5     # single player
 *   php artisan players:generate-cutouts --force    # regenerate existing cutouts
 *   php artisan players:generate-cutouts --limit=20 # process at most N players
 *
 * Output: storage/app/public/players/cutouts/player-{id}.png
 * The website automatically prefers the cutout on the showcase stage.
 */
class GeneratePlayerCutouts extends Command
{
    protected $signature   = 'players:generate-cutouts {--id=} {--force} {--limit=}';
    protected $description = 'Generate transparent-background player cutouts (remove.bg API or local rembg)';

    public function handle(PlayerCutoutService $cutouts): int
    {
        $query = Player::query()->whereNotNull('photo')->where('photo', '!=', '');

        if ($id = $this->option('id')) {
            $query->where('id', $id);
        }

        if (!$this->option('force')) {
            $query->where(function ($q) {
                $q->whereNull('photo_cutout')->orWhere('photo_cutout', '');
            });
        }

        if ($limit = (int) $this->option('limit')) {
            $query->limit($limit);
        }

        $players = $query->get();
        if ($players->isEmpty()) {
            $this->info('No players need cutouts.');
            return self::SUCCESS;
        }

        $engine = $cutouts->detectEngine();
        if (!$engine) {
            $this->error('No engine available. Set REMOVE_BG_API_KEY in .env, or install rembg (pip install rembg) and/or set REMBG_PATH.');
            return self::FAILURE;
        }
        $this->info("Engine: {$engine}");

        Storage::disk('public')->makeDirectory('players/cutouts');

        foreach ($players as $player) {
            $source = $this->resolveSourcePath($player);
            if (!$source) {
                $this->warn("[{$player->id}] {$player->name}: photo not found, skipped.");
                continue;
            }

            $outPath = "players/cutouts/player-{$player->id}.png";

            try {
                $png = $cutouts->generate($source);
                @unlink($source);

                if (!$png) {
                    $this->warn("[{$player->id}] {$player->name}: cutout failed, skipped.");
                    continue;
                }

                Storage::disk('public')->put($outPath, $png);
                $player->update(['photo_cutout' => $outPath]);
                $this->info("[{$player->id}] {$player->name}: OK {$outPath}");
            } catch (\Throwable $e) {
                $this->error("[{$player->id}] {$player->name}: " . $e->getMessage());
            }
        }

        return self::SUCCESS;
    }

    private function resolveSourcePath(Player $player): ?string
    {
        $photo = $player->photo;

        if (filter_var($photo, FILTER_VALIDATE_URL)) {
            $tmp = tempnam(sys_get_temp_dir(), 'pimg');
            $content = @file_get_contents($photo);
            if ($content === false) {
                return null;
            }
            file_put_contents($tmp, $content);
            return $tmp;
        }

        $candidates = [$photo, 'players/' . $photo, 'players/photos/' . $photo];
        foreach ($candidates as $c) {
            $clean = ltrim($c, '/\\');
            if ($clean && Storage::disk('public')->exists($clean)) {
                $tmp = tempnam(sys_get_temp_dir(), 'pimg');
                file_put_contents($tmp, Storage::disk('public')->get($clean));
                return $tmp;
            }
        }

        return null;
    }
}