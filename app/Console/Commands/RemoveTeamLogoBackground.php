<?php

namespace App\Console\Commands;

use App\Models\Team;
use App\Services\TeamLogoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Strips the white background from team logo images (transparent PNG),
 * pure PHP GD — no external API or binary required.
 *
 * Usage:
 *   php artisan teams:remove-logo-background              # all teams with a logo
 *   php artisan teams:remove-logo-background --id=5       # single team
 *   php artisan teams:remove-logo-background --force      # reprocess output
 *   php artisan teams:remove-logo-background --limit=20
 *
 * Output: storage/app/public/teams/logos/logo-{id}.png  (teams.logo updated)
 */
class RemoveTeamLogoBackground extends Command
{
    protected $signature   = 'teams:remove-logo-background {--id=} {--force} {--limit=}';
    protected $description = 'Strip the white background from team logos (transparent PNG)';

    public function handle(TeamLogoService $logos): int
    {
        $query = Team::query()->whereNotNull('logo')->where('logo', '!=', '');

        if ($id = $this->option('id')) {
            $query->where('id', $id);
        }

        if (!$this->option('force')) {
            $query->where('logo', 'not like', 'teams/logos/logo-%');
        }

        if ($limit = (int) $this->option('limit')) {
            $query->limit($limit);
        }

        $teams = $query->get();
        if ($teams->isEmpty()) {
            $this->info('No team logos need processing.');
            return self::SUCCESS;
        }

        Storage::disk('public')->makeDirectory('teams/logos');

        foreach ($teams as $team) {
            $source = $this->resolveSourcePath($team);
            if (!$source) {
                $this->warn("[{$team->id}] {$team->name}: logo not found, skipped.");
                continue;
            }

            try {
                $png = $logos->removeBackground($source);
                @unlink($source);

                if (!$png) {
                    $this->warn("[{$team->id}] {$team->name}: processing failed, skipped.");
                    continue;
                }

                $outPath = "teams/logos/logo-{$team->id}.png";
                Storage::disk('public')->put($outPath, $png);

                if ($team->logo !== $outPath && Storage::disk('public')->exists($team->logo)) {
                    Storage::disk('public')->delete($team->logo);
                }

                $team->update(['logo' => $outPath]);
                $this->info("[{$team->id}] {$team->name}: OK {$outPath}");
            } catch (\Throwable $e) {
                $this->error("[{$team->id}] {$team->name}: " . $e->getMessage());
            }
        }

        return self::SUCCESS;
    }

    private function resolveSourcePath(Team $team): ?string
    {
        $logo = $team->logo;

        if (filter_var($logo, FILTER_VALIDATE_URL)) {
            $tmp = tempnam(sys_get_temp_dir(), 'tlogo');
            $content = @file_get_contents($logo);
            if ($content === false) {
                return null;
            }
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