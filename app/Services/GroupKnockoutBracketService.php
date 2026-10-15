<?php

namespace App\Services;

use App\Models\Game;
use App\Models\KnockoutSlot;
use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/** Builds and protects the knockout phase of a group + knockout tournament. */
class GroupKnockoutBracketService
{
    public const READY_KEY = 'group_knockout_bracket_ready';
    public const QUALIFIERS_KEY = 'group_knockout_qualifier_ids';
    public const SNAPSHOT_KEY = 'group_knockout_bracket_snapshot';

    public function sync(Tournament $tournament, bool $strict = false): bool
    {
        if ($tournament->type !== 'group_knockout') {
            return false;
        }

        return DB::transaction(function () use ($tournament, $strict) {
            $lockedTournament = Tournament::query()
                ->whereKey($tournament->id)
                ->lockForUpdate()
                ->firstOrFail();
            $draw = app(KnockoutDrawService::class);
            $hasBracket = $draw->hasBracket($lockedTournament);
            $stage = $this->completedGroupStage($lockedTournament);

            if (!$stage) {
                if (!$hasBracket) {
                    $this->removeSafeLegacyPlaceholders($lockedTournament, $draw);
                    return false;
                }

                if ($draw->hasStartedKnockoutMatches($lockedTournament)) {
                    if ($strict) {
                        throw new \RuntimeException('Fase grup tidak dapat diubah setelah pertandingan knockout dimulai. Selesaikan atau koreksi fase knockout terlebih dahulu.');
                    }

                    return true;
                }

                $this->purgeGeneratedBracket($lockedTournament, $draw);
                return false;
            }

            [$qualifiers, $fingerprint] = $stage;
            $settings = $lockedTournament->settings ?? [];
            $sameSnapshot = ($settings[self::SNAPSHOT_KEY] ?? null) === $fingerprint;

            if ($hasBracket && $sameSnapshot && $draw->isDrawComplete($lockedTournament)) {
                return true;
            }

            if (!$hasBracket && $this->hasUnsafeUnlinkedKnockoutFixtures($lockedTournament)) {
                if ($strict) {
                    throw new \RuntimeException('Fixture knockout lama belum terhubung ke bracket otomatis. Hapus atau selesaikan fixture lama sebelum menyimpan hasil grup terakhir.');
                }

                return false;
            }

            if ($hasBracket && $draw->hasStartedKnockoutMatches($lockedTournament)) {
                if ($strict) {
                    throw new \RuntimeException('Hasil atau susunan fase grup tidak dapat diubah setelah pertandingan knockout dimulai.');
                }

                return true;
            }

            if (count($qualifiers) < 2) {
                if ($hasBracket) {
                    $this->purgeGeneratedBracket($lockedTournament, $draw);
                }

                return false;
            }

            if (!$this->knockoutFitsTournamentDates($lockedTournament, count($qualifiers))) {
                if ($strict) {
                    throw new \RuntimeException('Rentang tanggal turnamen tidak cukup untuk fase knockout. Perpanjang tanggal akhir turnamen sebelum menyelesaikan hasil grup terakhir.');
                }

                return false;
            }

            if (!$hasBracket) {
                StandingService::recalculateAllStandings($lockedTournament->id);
            }

            $this->purgeGeneratedBracket($lockedTournament, $draw);
            $this->createSeededSlots($lockedTournament, $qualifiers);

            $settings = $lockedTournament->settings ?? [];
            $size = count(KnockoutSlot::where('tournament_id', $lockedTournament->id)
                ->where('round_number', 1)
                ->get());
            $settings[self::READY_KEY] = true;
            $settings[self::QUALIFIERS_KEY] = array_column($qualifiers, 'team_id');
            $settings[self::SNAPSHOT_KEY] = $fingerprint;
            $settings['draw_status'] = KnockoutDrawService::DRAW_COMPLETED;
            $settings['draw_bracket_size'] = $size;
            $settings['draw_bracket_capacity'] = $size;
            $settings['draw_created_at'] = now()->toDateTimeString();
            $settings['draw_completed_at'] = now()->toDateTimeString();
            $lockedTournament->settings = $settings;
            $lockedTournament->save();

            $draw->generateAllMatches($lockedTournament);

            return true;
        });
    }

    /** Return qualifiers and a stable fingerprint only when the entire round robin is complete. */
    private function completedGroupStage(Tournament $tournament): ?array
    {
        $teams = $tournament->teams()
            ->withPivot('group_name')
            ->orderBy('teams.name')
            ->get(['teams.id', 'teams.name']);

        $groupedTeams = $teams->groupBy(fn ($team) => strtoupper(trim((string) $team->pivot->group_name)));
        $groupedTeams = $groupedTeams->filter(fn ($group, $name) => $name !== '');
        $qualifyPerGroup = (int) ($tournament->qualify_per_group ?? 2);
        $groupsCount = (int) ($tournament->groups_count ?? 0);
        $teamsPerGroup = (int) ($tournament->teams_per_group ?? 4);
        $expectedGroupNames = collect(range(0, max(0, $groupsCount - 1)))
            ->map(fn ($index) => chr(65 + $index))
            ->all();
        $actualGroupNames = $groupedTeams->keys()
            ->map(fn ($name) => strtoupper((string) $name))
            ->sort()
            ->values()
            ->all();

        if ($groupsCount < 1
            || $groupsCount > 8
            || $groupedTeams->count() !== $groupsCount
            || $actualGroupNames !== $expectedGroupNames
            || $qualifyPerGroup < 1
            || $qualifyPerGroup > $teamsPerGroup) {
            return null;
        }

        foreach ($groupedTeams as $groupTeams) {
            if ($groupTeams->count() < max(2, $qualifyPerGroup)
                || $groupTeams->count() > $teamsPerGroup) {
                return null;
            }
        }

        $groupSizes = $groupedTeams->map->count()->values();
        if ($groupSizes->max() - $groupSizes->min() > 1) {
            return null;
        }

        $teamGroups = [];
        $stats = [];
        $expectedFixtures = [];
        foreach ($groupedTeams as $groupName => $groupTeams) {
            $groupTeamIds = $groupTeams->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
            foreach ($groupTeams as $team) {
                $teamId = (int) $team->id;
                $teamGroups[$teamId] = (string) $groupName;
                $stats[$groupName][$teamId] = [
                    'team_id' => $teamId,
                    'team_name' => (string) $team->name,
                    'group_name' => (string) $groupName,
                    'played' => 0,
                    'wins' => 0,
                    'draws' => 0,
                    'losses' => 0,
                    'goals_for' => 0,
                    'goals_against' => 0,
                    'points' => 0,
                ];
            }

            for ($i = 0; $i < count($groupTeamIds) - 1; $i++) {
                for ($j = $i + 1; $j < count($groupTeamIds); $j++) {
                    $expectedFixtures[$groupName][$this->pairKey($groupTeamIds[$i], $groupTeamIds[$j])] = true;
                }
            }
        }

        $groupMatches = Game::query()
            ->where('tournament_id', $tournament->id)
            ->where('round_type', 'group')
            ->orderBy('id')
            ->get();

        $expectedCount = array_sum(array_map('count', $expectedFixtures));
        if ($expectedCount < 1 || $groupMatches->count() !== $expectedCount) {
            return null;
        }

        $seenFixtures = [];
        $snapshotMatches = [];
        foreach ($groupMatches as $match) {
            $groupName = strtoupper(trim((string) $match->group_name));
            $homeId = (int) $match->team_home_id;
            $awayId = (int) $match->team_away_id;
            $pairKey = $this->pairKey($homeId, $awayId);

            if ($match->is_cancelled
                || $match->status !== 'completed'
                || $match->home_score === null
                || $match->away_score === null
                || $homeId === $awayId
                || ($teamGroups[$homeId] ?? null) !== $groupName
                || ($teamGroups[$awayId] ?? null) !== $groupName
                || !isset($expectedFixtures[$groupName][$pairKey])
                || isset($seenFixtures[$groupName][$pairKey])) {
                return null;
            }

            $seenFixtures[$groupName][$pairKey] = true;
            $homeScore = (int) $match->home_score;
            $awayScore = (int) $match->away_score;
            $home = &$stats[$groupName][$homeId];
            $away = &$stats[$groupName][$awayId];
            $home['played']++;
            $away['played']++;
            $home['goals_for'] += $homeScore;
            $home['goals_against'] += $awayScore;
            $away['goals_for'] += $awayScore;
            $away['goals_against'] += $homeScore;

            if ($homeScore > $awayScore) {
                $home['wins']++;
                $away['losses']++;
                $home['points'] += (int) $tournament->points_win;
                $away['points'] += (int) $tournament->points_loss;
            } elseif ($homeScore < $awayScore) {
                $away['wins']++;
                $home['losses']++;
                $away['points'] += (int) $tournament->points_win;
                $home['points'] += (int) $tournament->points_loss;
            } else {
                $home['draws']++;
                $away['draws']++;
                $home['points'] += (int) $tournament->points_draw;
                $away['points'] += (int) $tournament->points_draw;
            }
            unset($home, $away);

            $snapshotMatches[] = [
                (int) $match->id,
                $groupName,
                $homeId,
                $awayId,
                $homeScore,
                $awayScore,
                $match->et_score,
                $match->penalty_score,
                (bool) $match->is_penalty,
            ];
        }

        if (count($seenFixtures) !== count($expectedFixtures)) {
            return null;
        }
        foreach ($expectedFixtures as $groupName => $pairs) {
            if (count($seenFixtures[$groupName] ?? []) !== count($pairs)) {
                return null;
            }
        }

        $qualifiers = [];
        foreach ($stats as $groupName => $groupStats) {
            $rows = array_values($groupStats);
            usort($rows, function (array $a, array $b) use ($groupMatches, $tournament) {
                if ($a['points'] !== $b['points']) {
                    return $b['points'] <=> $a['points'];
                }

                $goalDifference = ($b['goals_for'] - $b['goals_against']) <=> ($a['goals_for'] - $a['goals_against']);
                if ($goalDifference !== 0) {
                    return $goalDifference;
                }

                if ($a['goals_for'] !== $b['goals_for']) {
                    return $b['goals_for'] <=> $a['goals_for'];
                }

                $headToHead = $this->compareHeadToHead($a['team_id'], $b['team_id'], $groupMatches, $tournament);
                if ($headToHead !== 0) {
                    return $headToHead;
                }

                if ($a['wins'] !== $b['wins']) {
                    return $b['wins'] <=> $a['wins'];
                }

                $nameCompare = strcasecmp($a['team_name'], $b['team_name']);
                return $nameCompare !== 0 ? $nameCompare : ($a['team_id'] <=> $b['team_id']);
            });

            foreach (array_slice($rows, 0, $qualifyPerGroup) as $position => $standing) {
                $qualifiers[] = $standing + [
                    'group_position' => $position + 1,
                    'group_name' => (string) $groupName,
                ];
            }
        }

        usort($qualifiers, function (array $a, array $b) {
            return ($a['group_position'] <=> $b['group_position'])
                ?: strnatcasecmp($a['group_name'], $b['group_name'])
                ?: ($b['points'] <=> $a['points'])
                ?: (($b['goals_for'] - $b['goals_against']) <=> ($a['goals_for'] - $a['goals_against']))
                ?: ($b['goals_for'] <=> $a['goals_for'])
                ?: strcasecmp($a['team_name'], $b['team_name'])
                ?: ($a['team_id'] <=> $b['team_id']);
        });

        $fingerprint = hash('sha256', json_encode([
            'matches' => $snapshotMatches,
            'seed_order' => array_column($qualifiers, 'team_id'),
            'qualify_per_group' => $qualifyPerGroup,
            'third_place' => (bool) $tournament->knockout_third_place,
        ], JSON_THROW_ON_ERROR));

        return [$qualifiers, $fingerprint];
    }

    private function knockoutFitsTournamentDates(Tournament $tournament, int $qualifierCount): bool
    {
        if (!$tournament->end_date) {
            return true;
        }

        $lastGroupDate = Game::where('tournament_id', $tournament->id)
            ->where('round_type', 'group')
            ->max('match_date');
        if (!$lastGroupDate) {
            return false;
        }

        $size = 2;
        while ($size < $qualifierCount) {
            $size *= 2;
        }
        $rounds = (int) round(log($size, 2));
        $finalDate = Carbon::parse($lastGroupDate)
            ->addDay()
            ->addDays(max(0, $rounds - 1) * 2);

        return !$finalDate->greaterThan(Carbon::parse($tournament->end_date));
    }

    private function compareHeadToHead(int $teamAId, int $teamBId, $matches, Tournament $tournament): int
    {
        $points = [$teamAId => 0, $teamBId => 0];
        foreach ($matches as $match) {
            if (!in_array((int) $match->team_home_id, [$teamAId, $teamBId], true)
                || !in_array((int) $match->team_away_id, [$teamAId, $teamBId], true)) {
                continue;
            }

            if ((int) $match->home_score === (int) $match->away_score) {
                $points[$teamAId] += (int) $tournament->points_draw;
                $points[$teamBId] += (int) $tournament->points_draw;
            } else {
                $winnerId = (int) $match->home_score > (int) $match->away_score
                    ? (int) $match->team_home_id
                    : (int) $match->team_away_id;
                $loserId = $winnerId === (int) $match->team_home_id
                    ? (int) $match->team_away_id
                    : (int) $match->team_home_id;
                $points[$winnerId] += (int) $tournament->points_win;
                $points[$loserId] += (int) $tournament->points_loss;
            }
        }

        return $points[$teamBId] <=> $points[$teamAId];
    }

    private function createSeededSlots(Tournament $tournament, array $qualifiers): void
    {
        $teamCount = count($qualifiers);
        $size = 2;
        while ($size < $teamCount) {
            $size *= 2;
        }
        if ($size > 64) {
            throw new \RuntimeException('Bracket group + knockout mendukung maksimal 64 slot.');
        }

        $matchCount = intdiv($size, 2);
        $byeCount = $size - $teamCount;
        $byeMatchIndexes = [];
        for ($i = 0; $i < $byeCount; $i++) {
            $byeMatchIndexes[] = (int) floor(($i * $matchCount) / max(1, $byeCount));
        }

        $nextTeamIndex = 0;
        $openMatches = [];
        for ($matchIndex = 0; $matchIndex < $matchCount; $matchIndex++) {
            if (in_array($matchIndex, $byeMatchIndexes, true)) {
                $this->createFirstRoundSlot($tournament, $matchIndex, 'home', $qualifiers[$nextTeamIndex++]['team_id'], false);
                $this->createFirstRoundSlot($tournament, $matchIndex, 'away', null, true);
            } else {
                $openMatches[] = $matchIndex;
            }
        }

        $remaining = array_slice($qualifiers, $nextTeamIndex);
        foreach ($openMatches as $matchIndex) {
            if (count($remaining) < 2) {
                throw new \RuntimeException('Peserta lolos tidak dapat dipasangkan dengan benar pada bracket.');
            }

            $home = array_shift($remaining);
            $awayIndex = count($remaining) - 1;
            foreach ($remaining as $index => $candidate) {
                if ($candidate['group_name'] !== $home['group_name']) {
                    $awayIndex = $index;
                }
            }
            $away = array_splice($remaining, $awayIndex, 1)[0];

            $this->createFirstRoundSlot($tournament, $matchIndex, 'home', $home['team_id'], false);
            $this->createFirstRoundSlot($tournament, $matchIndex, 'away', $away['team_id'], false);
        }

        if ($remaining !== []) {
            throw new \RuntimeException('Sebagian peserta lolos tidak mendapat slot bracket.');
        }

        $rounds = (int) round(log($size, 2));
        for ($round = 2; $round <= $rounds; $round++) {
            $matchesInRound = intdiv($size, (int) pow(2, $round));
            for ($matchIndex = 0; $matchIndex < $matchesInRound; $matchIndex++) {
                foreach (['home', 'away'] as $side) {
                    KnockoutSlot::create([
                        'tournament_id' => $tournament->id,
                        'round_number' => $round,
                        'match_index' => $matchIndex,
                        'side' => $side,
                        'slot_number' => null,
                        'source' => KnockoutSlot::SOURCE_WINNER,
                        'status' => KnockoutSlot::STATUS_EMPTY,
                        'result' => KnockoutSlot::RESULT_PENDING,
                    ]);
                }
            }
        }
    }

    private function createFirstRoundSlot(Tournament $tournament, int $matchIndex, string $side, ?int $teamId, bool $bye): void
    {
        $slotNumber = ($matchIndex * 2) + ($side === 'home' ? 1 : 2);
        KnockoutSlot::create([
            'tournament_id' => $tournament->id,
            'round_number' => 1,
            'match_index' => $matchIndex,
            'side' => $side,
            'slot_number' => $slotNumber,
            'team_id' => $teamId,
            'source' => $bye ? KnockoutSlot::SOURCE_BYE : KnockoutSlot::SOURCE_DRAW,
            'status' => $teamId ? KnockoutSlot::STATUS_FILLED : KnockoutSlot::STATUS_EMPTY,
            'result' => $bye ? KnockoutSlot::RESULT_BYE : KnockoutSlot::RESULT_PENDING,
        ]);
    }

    private function purgeGeneratedBracket(Tournament $tournament, KnockoutDrawService $draw): void
    {
        $draw->purgeBracket($tournament);
        $settings = $tournament->settings ?? [];
        unset(
            $settings[self::READY_KEY],
            $settings[self::QUALIFIERS_KEY],
            $settings[self::SNAPSHOT_KEY],
            $settings['draw_bracket_size'],
            $settings['draw_bracket_capacity'],
            $settings['draw_completed_at'],
            $settings['draw_created_at']
        );
        $settings['draw_status'] = KnockoutDrawService::DRAW_DRAFT;
        $tournament->settings = $settings;
        $tournament->save();
    }

    private function removeSafeLegacyPlaceholders(Tournament $tournament, KnockoutDrawService $draw): void
    {
        if ($draw->hasBracket($tournament)) {
            return;
        }

        $fixtures = Game::where('tournament_id', $tournament->id)
            ->whereNull('group_name')
            ->whereIn('round_type', ['round_of_64', 'round_of_32', 'round_of_16', 'quarterfinal', 'semifinal', 'final', 'third_place', 'knockout'])
            ->get();

        if ($fixtures->isEmpty()) {
            return;
        }

        $onlySafePlaceholders = $fixtures->every(fn (Game $match) =>
            $match->team_home_id === null
            && $match->team_away_id === null
            && in_array($match->status, ['upcoming', 'postponed'], true)
            && (int) ($match->home_score ?? 0) === 0
            && (int) ($match->away_score ?? 0) === 0
            && !$match->events()->exists()
        );

        if ($onlySafePlaceholders) {
            $draw->purgeBracket($tournament);
        }
    }

    private function hasUnsafeUnlinkedKnockoutFixtures(Tournament $tournament): bool
    {
        $fixtures = Game::where('tournament_id', $tournament->id)
            ->whereNull('group_name')
            ->whereIn('round_type', ['round_of_64', 'round_of_32', 'round_of_16', 'quarterfinal', 'semifinal', 'final', 'third_place', 'knockout'])
            ->get();

        return $fixtures->contains(fn (Game $match) =>
            $match->team_home_id !== null
            || $match->team_away_id !== null
            || !in_array($match->status, ['upcoming', 'postponed'], true)
            || (int) ($match->home_score ?? 0) !== 0
            || (int) ($match->away_score ?? 0) !== 0
            || $match->events()->exists()
        );
    }

    private function pairKey(int $first, int $second): string
    {
        return min($first, $second).'-'.max($first, $second);
    }
}
