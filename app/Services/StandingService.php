<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Standing;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class StandingService
{
    public static function updateStandings(Game $match)
    {
        // Pastikan match sudah completed
        if ($match->status !== 'completed') {
            return;
        }

        $tournamentId = $match->tournament_id;
        $groupName = $match->group_name;

        // For league matches (group_name = null), recalculate all standings in tournament
        // For group matches, recalculate only that group
        if (empty($groupName)) {
            self::recalculateAllStandings($tournamentId);
        } else {
            self::recalculateGroupStandings($groupName, $tournamentId);
        }
    }

    private static function recalculateGroupStandings($groupName, $tournamentId)
    {
        // Ambil semua matches yang sudah completed untuk grup ini
        $completedMatches = Game::where('tournament_id', $tournamentId)
            ->where('group_name', $groupName)
            ->where('status', 'completed')
            ->get();

        // Reset seluruh peserta grup, termasuk tim yang belum memainkan laga.
        // Ini juga menghapus angka sisa jika fixture selesai diedit ke pasangan lain.
        $registeredTeamIds = DB::table('team_tournament')
            ->where('tournament_id', $tournamentId)
            ->where('group_name', $groupName)
            ->pluck('team_id');
        $allTeamIdsInGroup = $registeredTeamIds
            ->merge($completedMatches->flatMap(fn ($match) => [$match->team_home_id, $match->team_away_id]))
            ->unique()
            ->filter()
            ->values();

        // Reset stats untuk SEMUA tim di grup
        foreach ($allTeamIdsInGroup as $teamId) {
            $standing = Standing::firstOrNew([
                'tournament_id' => $tournamentId,
                'team_id' => $teamId,
                'group_name' => $groupName,
            ]);

            // Reset ke default
            $standing->matches_played = 0;
            $standing->wins = 0;
            $standing->draws = 0;
            $standing->losses = 0;
            $standing->goals_for = 0;
            $standing->goals_against = 0;
            $standing->goal_difference = 0;
            $standing->points = 0;

            $standing->save();
        }

        // Hitung ulang dari SEMUA matches
        foreach ($completedMatches as $match) {
            self::processMatchResult($match);
        }
    }

    private static function processMatchResult(Game $match)
    {
        // Matches in a league have no group_name, while standings.group_name
        // is required by the database. Keep the same sentinel used by the
        // standings controller for ungrouped league matches.
        $groupName = $match->group_name ?: 'A';

        // Use firstOrCreate to get existing standing without resetting values
        // The reset has already been done in recalculateGroupStandings/recalculateAllStandings
        $homeStanding = Standing::firstOrCreate([
            'team_id' => $match->team_home_id,
            'group_name' => $groupName,
            'tournament_id' => $match->tournament_id,
        ]);

        $awayStanding = Standing::firstOrCreate([
            'team_id' => $match->team_away_id,
            'group_name' => $groupName,
            'tournament_id' => $match->tournament_id,
        ]);

        $tournament = $match->tournament;
        $pointsWin = (int) ($tournament?->points_win ?? 3);
        $pointsDraw = (int) ($tournament?->points_draw ?? 1);
        $pointsLoss = (int) ($tournament?->points_loss ?? 0);

        // Update stats untuk kedua tim (accumulate, don't reset)
        self::updateTeamStats($homeStanding, $match->home_score, $match->away_score, true, $pointsWin, $pointsDraw, $pointsLoss);
        self::updateTeamStats($awayStanding, $match->away_score, $match->home_score, false, $pointsWin, $pointsDraw, $pointsLoss);

        $homeStanding->save();
        $awayStanding->save();
    }

    private static function updateTeamStats(Standing $standing, $goalsFor, $goalsAgainst, $isHome, int $pointsWin, int $pointsDraw, int $pointsLoss)
    {
        $standing->matches_played += 1;
        $standing->goals_for += $goalsFor;
        $standing->goals_against += $goalsAgainst;
        $standing->goal_difference = $standing->goals_for - $standing->goals_against;

        if ($goalsFor > $goalsAgainst) {
            $standing->wins += 1;
            $standing->points += $pointsWin;
        } elseif ($goalsFor < $goalsAgainst) {
            $standing->losses += 1;
            $standing->points += $pointsLoss;
        } else {
            $standing->draws += 1;
            $standing->points += $pointsDraw;
        }
    }

    public static function recalculateAllStandings($tournamentId = null)
    {
        // Reset semua standings
        $query = Standing::query();
        if ($tournamentId) {
            $query->where('tournament_id', $tournamentId);
        }
        $standings = $query->get();

        foreach ($standings as $standing) {
            $standing->matches_played = 0;
            $standing->wins = 0;
            $standing->draws = 0;
            $standing->losses = 0;
            $standing->goals_for = 0;
            $standing->goals_against = 0;
            $standing->goal_difference = 0;
            $standing->points = 0;
            $standing->save();
        }

        // Ambil semua completed matches
        $matchesQuery = Game::where('status', 'completed')
            ->whereIn('round_type', ['group', 'league']);
        if ($tournamentId) {
            $matchesQuery->where('tournament_id', $tournamentId);
        }
        $completedMatches = $matchesQuery->get();

        // Proses semua matches
        foreach ($completedMatches as $match) {
            self::processMatchResult($match);
        }
    }
}
