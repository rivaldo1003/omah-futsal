<?php

namespace App\Console\Commands;

use App\Models\Standing;
use App\Models\Tournament;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InitializeTournamentStandings extends Command
{
    protected $signature = 'standings:init-tournament {tournament?}';

    protected $description = 'Initialize standings for tournament teams';

    public function handle()
    {
        $tournamentId = $this->argument('tournament');

        if ($tournamentId) {
            $tournament = Tournament::find($tournamentId);
            if (! $tournament) {
                $this->error('Tournament not found!');

                return;
            }
            $tournaments = [$tournament];
        } else {
            $tournaments = Tournament::whereIn('status', ['ongoing', 'upcoming'])->get();
        }

        foreach ($tournaments as $tournament) {
            $this->info("Processing tournament: {$tournament->name}");

            // Get all teams in this tournament from team_tournament
            $teams = DB::table('team_tournament')
                ->where('tournament_id', $tournament->id)
                ->get();

            $count = 0;
            foreach ($teams as $teamTournament) {
                // Check if standing already exists
                $existing = Standing::where('team_id', $teamTournament->team_id)
                    ->where('tournament_id', $tournament->id)
                    ->where('group_name', $teamTournament->group_name)
                    ->first();

                if (! $existing) {
                    Standing::create([
                        'team_id' => $teamTournament->team_id,
                        'tournament_id' => $tournament->id,
                        'group_name' => $teamTournament->group_name,
                        'matches_played' => 0,
                        'wins' => 0,
                        'draws' => 0,
                        'losses' => 0,
                        'goals_for' => 0,
                        'goals_against' => 0,
                        'goal_difference' => 0,
                        'points' => 0,
                    ]);
                    $count++;
                }
            }

            $this->info("Created {$count} standings for {$tournament->name}");
        }

        $this->info('All standings initialized!');
    }
}
