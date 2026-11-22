<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournaments') || ! Schema::hasTable('matches') || ! Schema::hasTable('teams')) {
            return;
        }

        DB::transaction(function (): void {
            $tournaments = DB::table('tournaments')
                ->whereRaw('UPPER(TRIM(name)) = ?', ['OFS RAMADHAN CUP'])
                ->lockForUpdate()
                ->get();

            if ($tournaments->isEmpty()) {
                $this->command?->warn('OFS RAMADHAN CUP was not found; historical tournament correction was skipped.');

                return;
            }

            if ($tournaments->count() !== 1) {
                throw new RuntimeException('Historical correction stopped: more than one tournament is named OFS RAMADHAN CUP.');
            }

            $tournament = $tournaments->first();

            $rawSettings = (string) $tournament->settings;
            $settings = json_decode($rawSettings, true);
            if (is_string($settings)) {
                $settings = json_decode($settings, true);
            }
            if ($rawSettings !== '' && ! is_array($settings)) {
                throw new RuntimeException('Historical correction stopped: OFS RAMADHAN CUP settings are not valid JSON.');
            }
            if (! is_array($settings)) {
                $settings = [];
            }
            $settings['knockout_third_place'] = true;

            $targetMatches = DB::table('matches as m')
                ->join('teams as home_team', 'home_team.id', '=', 'm.team_home_id')
                ->join('teams as away_team', 'away_team.id', '=', 'm.team_away_id')
                ->where('m.tournament_id', $tournament->id)
                ->whereIn('m.round_type', ['final', 'third_place'])
                ->where('m.status', 'completed')
                ->where('m.home_score', 1)
                ->where('m.away_score', 2)
                ->whereRaw('UPPER(TRIM(home_team.name)) = ?', ['UPJB'])
                ->whereRaw('UPPER(TRIM(away_team.name)) = ?', ['CASPER']);

            if (Schema::hasColumn('matches', 'is_cancelled')) {
                $targetMatches->where('m.is_cancelled', false);
            }

            $matches = $targetMatches->lockForUpdate()->get(['m.id']);

            if ($matches->count() !== 1) {
                throw new RuntimeException(sprintf(
                    'Historical correction stopped: expected one completed UPJB vs CASPER match in OFS RAMADHAN CUP, found %d.',
                    $matches->count(),
                ));
            }

            DB::table('tournaments')
                ->where('id', $tournament->id)
                ->update([
                    'type' => 'group_knockout',
                    'groups_count' => 8,
                    'teams_per_group' => 3,
                    'qualify_per_group' => 1,
                    'settings' => json_encode($settings, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'updated_at' => now(),
                ]);

            DB::table('matches')
                ->where('id', $matches->first()->id)
                ->update([
                    'round_type' => 'third_place',
                    'updated_at' => now(),
                ]);
        });
    }

    public function down(): void
    {
        // This repairs historical records; reverting it could reintroduce the wrong champion.
    }
};
