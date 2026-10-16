<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GoalkeeperCleanSheetService
{
    public static function isGoalkeeperPosition(?string $position): bool
    {
        $position = mb_strtolower(trim((string) $position));

        return str_contains($position, 'goalkeeper')
            || str_contains($position, 'kiper')
            || str_contains($position, 'keeper')
            || str_contains($position, 'gk')
            || $position === 'g';
    }

    public static function isGoalkeeperInMatchLineup(int $matchId, int $teamId, int $playerId): bool
    {
        return DB::table('match_player as mp')
            ->join('players as p', 'p.id', '=', 'mp.player_id')
            ->where('mp.match_id', $matchId)
            ->where('mp.team_id', $teamId)
            ->where('mp.player_id', $playerId)
            ->where(function ($query) {
                $position = "LOWER(COALESCE(NULLIF(mp.position_snapshot, ''), p.position, ''))";
                $query->whereRaw("{$position} LIKE ?", ['%goalkeeper%'])
                    ->orWhereRaw("{$position} LIKE ?", ['%kiper%'])
                    ->orWhereRaw("{$position} LIKE ?", ['%keeper%'])
                    ->orWhereRaw("{$position} LIKE ?", ['%gk%'])
                    ->orWhereRaw("LOWER(TRIM(COALESCE(NULLIF(mp.position_snapshot, ''), p.position, ''))) = ?", ['g']);
            })
            ->exists();
    }

    public function recalculateForMatch(Game|int $match): void
    {
        $matchId = $match instanceof Game ? (int) $match->getKey() : (int) $match;

        $playerIds = DB::table('match_player')
            ->where('match_id', $matchId)
            ->pluck('player_id')
            ->merge(
                DB::table('match_events')
                    ->where('match_id', $matchId)
                    ->where(function ($query) {
                        $query->where('event_type', 'clean_sheet')
                            ->orWhereNotNull('conceded_by_goalkeeper_id');
                    })
                    ->get(['player_id', 'conceded_by_goalkeeper_id'])
                    ->flatMap(fn ($event) => [$event->player_id, $event->conceded_by_goalkeeper_id])
            );

        $this->recalculateForPlayers($playerIds);
    }

    public function recalculateForPlayers(iterable $playerIds): void
    {
        $playerIds = collect($playerIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values();

        if ($playerIds->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($playerIds) {
            $appearances = $this->goalkeeperAppearancesQuery()
                ->whereIn('mp.player_id', $playerIds)
                ->get([
                    'mp.player_id',
                    'm.id as match_id',
                    'mp.team_id',
                    'm.team_home_id',
                    'm.team_away_id',
                    'm.home_score',
                    'm.away_score',
                    'm.et_score',
                ]);

            $matchIds = $appearances->pluck('match_id')->unique()->values();
            $allAppearances = $matchIds->isEmpty()
                ? collect()
                : $this->goalkeeperAppearancesQuery()
                    ->whereIn('m.id', $matchIds)
                    ->get(['mp.player_id', 'm.id as match_id', 'mp.team_id']);
            $goalEvents = $matchIds->isEmpty()
                ? collect()
                : DB::table('match_events')
                    ->whereIn('match_id', $matchIds)
                    ->where('event_type', 'goal')
                    ->get([
                        'match_id',
                        'team_id',
                        'is_own_goal',
                        'conceded_by_goalkeeper_id',
                    ])
                    ->groupBy('match_id');

            $counts = $appearances
                ->groupBy('player_id')
                ->map(function (Collection $playerAppearances) use ($allAppearances, $goalEvents) {
                    return $playerAppearances
                        ->filter(fn ($appearance) => $this->earnedCleanSheet(
                            $appearance,
                            $allAppearances,
                            $goalEvents->get($appearance->match_id, collect())
                        ))
                        ->pluck('match_id')
                        ->unique()
                        ->count();
                });

            Player::query()
                ->whereIn('id', $playerIds)
                ->get(['id', 'clean_sheets'])
                ->each(function (Player $player) use ($counts) {
                    $cleanSheets = (int) ($counts->get($player->id) ?? 0);

                    if ((int) ($player->clean_sheets ?? 0) !== $cleanSheets) {
                        $player->clean_sheets = $cleanSheets;
                        $player->save();
                    }
                });
        });
    }

    public function recalculateAll(): void
    {
        Player::query()
            ->select('id')
            ->chunkById(500, function ($players) {
                $this->recalculateForPlayers($players->pluck('id'));
            });
    }

    private function goalkeeperAppearancesQuery()
    {
        return DB::table('match_player as mp')
            ->join('matches as m', 'm.id', '=', 'mp.match_id')
            ->join('players as p', 'p.id', '=', 'mp.player_id')
            ->where('m.status', 'completed')
            ->where('m.is_cancelled', false)
            ->where(function ($query) {
                $position = "LOWER(COALESCE(NULLIF(mp.position_snapshot, ''), p.position, ''))";
                $query->whereRaw("{$position} LIKE ?", ['%goalkeeper%'])
                    ->orWhereRaw("{$position} LIKE ?", ['%kiper%'])
                    ->orWhereRaw("{$position} LIKE ?", ['%keeper%'])
                    ->orWhereRaw("{$position} LIKE ?", ['%gk%'])
                    ->orWhereRaw("LOWER(TRIM(COALESCE(NULLIF(mp.position_snapshot, ''), p.position, ''))) = ?", ['g']);
            });
    }

    private function earnedCleanSheet(object $appearance, Collection $allAppearances, Collection $goalEvents): bool
    {
        $teamId = (int) $appearance->team_id;
        $homeTeamId = (int) $appearance->team_home_id;
        $awayTeamId = (int) $appearance->team_away_id;
        $extraTimeScore = $this->parseExtraTimeScore($appearance->et_score);

        if ($extraTimeScore === null) {
            return false;
        }

        [$extraHomeGoals, $extraAwayGoals] = $extraTimeScore;

        if ($teamId === $homeTeamId) {
            $opponentTeamId = $awayTeamId;
            $opponentGoals = (int) $appearance->away_score + $extraAwayGoals;
        } elseif ($teamId === $awayTeamId) {
            $opponentTeamId = $homeTeamId;
            $opponentGoals = (int) $appearance->home_score + $extraHomeGoals;
        } else {
            return false;
        }

        if ($opponentGoals === 0) {
            return !$goalEvents->contains(
                fn ($event) => $this->scoringTeamId($event, $homeTeamId, $awayTeamId) === $opponentTeamId
            );
        }

        $goalsAgainstThisTeam = $goalEvents
            ->filter(fn ($event) => $this->scoringTeamId($event, $homeTeamId, $awayTeamId) === $opponentTeamId);

        if ($goalsAgainstThisTeam->count() !== $opponentGoals) {
            return false;
        }

        $goalkeeperIdsForTeam = $allAppearances
            ->where('match_id', $appearance->match_id)
            ->where('team_id', $teamId)
            ->pluck('player_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        // If even one conceded goal lacks a valid goalkeeper assignment, we do
        // not guess which keeper was playing at that moment.
        foreach ($goalsAgainstThisTeam as $goalEvent) {
            $concededByGoalkeeperId = (int) ($goalEvent->conceded_by_goalkeeper_id ?? 0);

            if ($concededByGoalkeeperId === 0 || !in_array($concededByGoalkeeperId, $goalkeeperIdsForTeam, true)) {
                return false;
            }
        }

        return !$goalsAgainstThisTeam->contains(
            fn ($goalEvent) => (int) $goalEvent->conceded_by_goalkeeper_id === (int) $appearance->player_id
        );
    }

    private function scoringTeamId(object $goalEvent, int $homeTeamId, int $awayTeamId): int
    {
        $eventTeamId = (int) $goalEvent->team_id;

        if (!$goalEvent->is_own_goal) {
            return $eventTeamId;
        }

        return $eventTeamId === $homeTeamId ? $awayTeamId : $homeTeamId;
    }

    private function parseExtraTimeScore(?string $score): ?array
    {
        if (!$score) {
            return [0, 0];
        }

        if (!preg_match('/^(\d+)-(\d+)$/', $score, $matches)) {
            return null;
        }

        return [(int) $matches[1], (int) $matches[2]];
    }
}
