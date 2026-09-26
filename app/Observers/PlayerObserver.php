<?php

namespace App\Observers;

use App\Models\Player;
use App\Services\PlayerCutoutService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Automatically generates a transparent-background cutout (photo_cutout)
 * whenever a player's photo is created or changed.
 *
 * Delegates generation to PlayerCutoutService, which supports:
 *   1. remove.bg API  (REMOVE_BG_API_KEY)
 *   2. local `rembg` CLI (pip install rembg)
 *
 * If no engine is available, the cutout is skipped (and logged) so the site
 * simply falls back to the original photo. Never breaks.
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
                Log::warning("Player cutout skipped for #{$player->id}: source photo not resolvable ({$player->photo}).");
                return;
            }

            $png = (new PlayerCutoutService())->generate($source);
            @unlink($source);

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

        // Remote URL: download to a temp file.
        if (filter_var($photo, FILTER_VALIDATE_URL)) {
            $content = @file_get_contents($photo);
            if ($content === false) {
                return null;
            }
            $tmp = tempnam(sys_get_temp_dir(), 'pimg');
            file_put_contents($tmp, $content);
            return $tmp;
        }

        // Local storage path (try a few common prefixes).
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