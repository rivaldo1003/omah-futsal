<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Standing;
use App\Models\Tournament;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TournamentStandingsService
{
    /**
     * Return one canonical, ordered standings table for the home page and the
     * complete standings page. Tournament registration is authoritative for
     * group membership; stored standings supply the match statistics.
     */
    public function grouped(Tournament $tournament): Collection
    {
        $registeredTeams = DB::table('team_tournament')
            ->join('teams', 'team_tournament.team_id', '=', 'teams.id')
            ->where('team_tournament.tournament_id', $tournament->id)
            ->select(
                'teams.id as team_id',
                'teams.name as team_name',
                'teams.logo as team_logo',
                'team_tournament.group_name',
                'team_tournament.seed'
            )
            ->orderBy('team_tournament.group_name')
            ->orderBy('team_tournament.seed')
            ->orderBy('teams.name')
            ->orderBy('teams.id')
            ->get();

        if ($registeredTeams->isEmpty()) {
            return collect();
        }

        $storedStandings = Standing::query()
            ->where('tournament_id', $tournament->id)
            ->get()
            ->keyBy('team_id');

        $groups = collect();

        foreach ($registeredTeams as $registeredTeam) {
            $groupName = $tournament->type === 'league'
                ? 'Klasemen'
                : (filled($registeredTeam->group_name) ? (string) $registeredTeam->group_name : 'Ungrouped');
            $stored = $storedStandings->get($registeredTeam->team_id);

            $goalsFor = (int) ($stored?->goals_for ?? 0);
            $goalsAgainst = (int) ($stored?->goals_against ?? 0);
            $form = (string) ($stored?->form ?? '');

            $row = (object) [
                'id' => $stored?->id,
                'team_id' => (int) $registeredTeam->team_id,
                'tournament_id' => (int) $tournament->id,
                'group_name' => $groupName,
                'team_name' => $registeredTeam->team_name,
                'team_logo' => $registeredTeam->team_logo,
                'matches_played' => (int) ($stored?->matches_played ?? 0),
                'wins' => (int) ($stored?->wins ?? 0),
                'draws' => (int) ($stored?->draws ?? 0),
                'losses' => (int) ($stored?->losses ?? 0),
                'goals_for' => $goalsFor,
                'goals_against' => $goalsAgainst,
                'goal_difference' => $goalsFor - $goalsAgainst,
                'points' => (int) ($stored?->points ?? 0),
                'form' => $form,
                'recent_form' => $form === '' ? [] : array_slice(str_split($form), -5),
                'is_default' => $stored === null,
                'team' => (object) [
                    'id' => (int) $registeredTeam->team_id,
                    'name' => $registeredTeam->team_name,
                    'logo' => $registeredTeam->team_logo,
                ],
            ];

            $groups->put($groupName, $groups->get($groupName, collect())->push($row));
        }

        $matchesByGroup = Game::query()
            ->where('tournament_id', $tournament->id)
            ->where('status', 'completed')
            ->get()
            ->groupBy(fn (Game $match) => filled($match->group_name)
                ? (string) $match->group_name
                : '');

        $orderedGroups = collect();
        $groupNames = $groups->keys()->all();
        usort($groupNames, static function ($left, $right): int {
            $left = (string) $left;
            $right = (string) $right;

            if ($left === 'Ungrouped') {
                return $right === 'Ungrouped' ? 0 : 1;
            }
            if ($right === 'Ungrouped') {
                return -1;
            }

            return strnatcasecmp($left, $right);
        });

        foreach ($groupNames as $groupName) {
            $groupMatches = $matchesByGroup->get($groupName, collect());
            $rows = $groups->get($groupName)->all();

            usort($rows, fn (object $left, object $right): int => $this->compareRows(
                $left,
                $right,
                $groupMatches,
                $tournament
            ));

            $orderedGroups->put($groupName, collect($rows));
        }

        return $orderedGroups;
    }

    private function compareRows(object $left, object $right, Collection $matches, Tournament $tournament): int
    {
        foreach ([
            [(int) $left->points, (int) $right->points],
            [(int) $left->goal_difference, (int) $right->goal_difference],
            [(int) $left->goals_for, (int) $right->goals_for],
        ] as [$leftValue, $rightValue]) {
            if ($leftValue !== $rightValue) {
                return $rightValue <=> $leftValue;
            }
        }

        $headToHead = $this->compareHeadToHead($left, $right, $matches, $tournament);
        if ($headToHead !== 0) {
            return $headToHead;
        }

        if ((int) $left->wins !== (int) $right->wins) {
            return (int) $right->wins <=> (int) $left->wins;
        }

        $nameOrder = strcmp((string) $left->team_name, (string) $right->team_name);

        return $nameOrder !== 0 ? $nameOrder : ((int) $left->team_id <=> (int) $right->team_id);
    }

    /** Positive means the right row should be placed before the left row. */
    private function compareHeadToHead(object $left, object $right, Collection $matches, Tournament $tournament): int
    {
        $leftPoints = 0;
        $rightPoints = 0;
        $pointsWin = (int) $tournament->points_win;
        $pointsDraw = (int) $tournament->points_draw;
        $pointsLoss = (int) $tournament->points_loss;

        foreach ($matches as $match) {
            $leftIsHome = (int) $match->team_home_id === (int) $left->team_id
                && (int) $match->team_away_id === (int) $right->team_id;
            $leftIsAway = (int) $match->team_away_id === (int) $left->team_id
                && (int) $match->team_home_id === (int) $right->team_id;

            if (!$leftIsHome && !$leftIsAway) {
                continue;
            }

            $homeScore = (int) $match->home_score;
            $awayScore = (int) $match->away_score;

            if ($homeScore === $awayScore) {
                $leftPoints += $pointsDraw;
                $rightPoints += $pointsDraw;
            } elseif (($leftIsHome && $homeScore > $awayScore) || ($leftIsAway && $awayScore > $homeScore)) {
                $leftPoints += $pointsWin;
                $rightPoints += $pointsLoss;
            } else {
                $rightPoints += $pointsWin;
                $leftPoints += $pointsLoss;
            }
        }

        return $rightPoints <=> $leftPoints;
    }
}
