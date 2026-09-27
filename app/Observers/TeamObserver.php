<?php

namespace App\Observers;

use App\Models\Team;
use App\Services\TeamLogoService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Strips the white background from a team logo whenever it is uploaded/changed,
 * so only the logo shows (transparent PNG). Uses TeamLogoService (pure PHP GD).
 */
class TeamObserver
{
    public function saved(Team $team): void
    {
        if (!$team->wasChanged('logo') || empty($team->logo)) {
            return;
        }

        if (str_starts_with($team->logo, 'teams/logos/logo-')) {
            return;
        }

        try {
            $source = $this->resolveSource($team);
            if (!$source) {
                return;
            }

            $png = (new TeamLogoService())->removeBackground($source);
            @unlink($source);

            if (!$png) {
                return;
            }

            $outPath = 'teams/logos/logo-' . $team->id . '.png';
            Storage::disk('public')->makeDirectory('teams/logos');
            Storage::disk('public')->put($outPath, $png);

            if ($team->logo !== $outPath && Storage::disk('public')->exists($team->logo)) {
                Storage::disk('public')->delete($team->logo);
            }

            Team::withoutEvents(fn () => $team->update(['logo' => $outPath]));
        } catch (\Throwable $e) {
            Log::warning('Team logo processing failed: ' . $e->getMessage());
        }
    }

    private function resolveSource(Team $team): ?string
    {
        $logo = $team->logo;

        if (filter_var($logo, FILTER_VALIDATE_URL)) {
            $content = @file_get_contents($logo);
            if ($content === false) {
                return null;
            }
            $tmp = tempnam(sys_get_temp_dir(), 'tlogo');
            file_put_contents($tmp, $content);
            return $tmp;
        }

        foreach ([$logo, 'teams/logos/' . $logo] as $c) {
            $clean = ltrim($c, '/\\');
            if ($clean && Storage::disk('public')->exists($clean)) {
                $tmp = tempnam(sys_get_temp_dir(), 'tlogo');
                file_put_contents($tmp, Storage::disk('public')->get($clean));
                return $tmp;
            }
        }

        return null;
    }
}