<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Standing;
use App\Models\Team;

class StandingService
{
    public static function updateStandings(Game $match)
    {
        // Pastikan match sudah completed
        if ($match->status !== 'completed') {
            return;
        }

        $homeTeamId = $match->team_home_id;
        $awayTeamId = $match->team_away_id;
        $homeScore = $match->home_score;
        $awayScore = $match->away_score;
        $groupName = $match->group_name;
        $tournamentId = $match->tournament_id;

        // Update standing untuk home team
        $homeStanding = Standing::firstOrNew([
            'tournament_id' => $tournamentId,
            'team_id' => $homeTeamId,
            'group_name' => $groupName
        ]);

        // Update standing untuk away team
        $awayStanding = Standing::firstOrNew([
            'tournament_id' => $tournamentId,
            'team_id' => $awayTeamId,
            'group_name' => $groupName
        ]);

        // Reset stats sebelum dihitung ulang
        self::recalculateStandings($homeTeamId, $awayTeamId, $groupName, $tournamentId);
    }

    private static function recalculateStandings($homeTeamId, $awayTeamId, $groupName, $tournamentId)
    {
        // Ambil semua matches yang sudah completed untuk grup ini
        $completedMatches = Game::where('tournament_id', $tournamentId)
            ->where('group_name', $groupName)
            ->where('status', 'completed')
            ->get();

        // Reset semua standings untuk grup ini
        $teamsInGroup = collect([$homeTeamId, $awayTeamId]);
        $allMatches = $completedMatches->whereIn('team_home_id', $teamsInGroup)
            ->orWhereIn('team_away_id', $teamsInGroup);

        // Reset stats untuk kedua tim
        $homeStanding = Standing::firstOrNew([
            'tournament_id' => $tournamentId,
            'team_id' => $homeTeamId,
            'group_name' => $groupName
        ]);

        $awayStanding = Standing::firstOrNew([
            'tournament_id' => $tournamentId,
            'team_id' => $awayTeamId,
            'group_name' => $groupName
        ]);

        // Reset ke default
        $homeStanding->matches_played = 0;
        $homeStanding->wins = 0;
        $homeStanding->draws = 0;
        $homeStanding->losses = 0;
        $homeStanding->goals_for = 0;
        $homeStanding->goals_against = 0;
        $homeStanding->goal_difference = 0;
        $homeStanding->points = 0;

        $awayStanding->matches_played = 0;
        $awayStanding->wins = 0;
        $awayStanding->draws = 0;
        $awayStanding->losses = 0;
        $awayStanding->goals_for = 0;
        $awayStanding->goals_against = 0;
        $awayStanding->goal_difference = 0;
        $awayStanding->points = 0;

        // Hitung ulang dari semua matches
        foreach ($completedMatches as $match) {
            self::processMatchResult($match);
        }

        $homeStanding->save();
        $awayStanding->save();
    }

    private static function processMatchResult(Game $match)
    {
        $homeStanding = Standing::where('team_id', $match->team_home_id)
            ->where('group_name', $match->group_name)
            ->where('tournament_id', $match->tournament_id)
            ->first();

        $awayStanding = Standing::where('team_id', $match->team_away_id)
            ->where('group_name', $match->group_name)
            ->where('tournament_id', $match->tournament_id)
            ->first();

        if (!$homeStanding || !$awayStanding) {
            return;
        }

        // Update stats untuk kedua tim
        self::updateTeamStats($homeStanding, $match->home_score, $match->away_score, true);
        self::updateTeamStats($awayStanding, $match->away_score, $match->home_score, false);

        $homeStanding->save();
        $awayStanding->save();
    }

    private static function updateTeamStats(Standing $standing, $goalsFor, $goalsAgainst, $isHome)
    {
        $standing->matches_played += 1;
        $standing->goals_for += $goalsFor;
        $standing->goals_against += $goalsAgainst;
        $standing->goal_difference = $standing->goals_for - $standing->goals_against;

        if ($goalsFor > $goalsAgainst) {
            $standing->wins += 1;
            $standing->points += 3;
        } elseif ($goalsFor < $goalsAgainst) {
            $standing->losses += 1;
        } else {
            $standing->draws += 1;
            $standing->points += 1;
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
        $matchesQuery = Game::where('status', 'completed');
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