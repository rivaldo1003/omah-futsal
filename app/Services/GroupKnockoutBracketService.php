<?php

namespace App\Services;

use App\Models\Game;
use App\Models\KnockoutSlot;
use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/** Builds and protects the knockout phase of a group + knockout tournament. */
class GroupKnockoutBracketService
{
    public const READY_KEY = 'group_knockout_bracket_ready';
    public const QUALIFIERS_KEY = 'group_knockout_qualifier_ids';
    public const SNAPSHOT_KEY = 'group_knockout_bracket_snapshot';
    public const QUALIFIER_OVERRIDES_KEY = 'group_knockout_qualifier_overrides';
    public const QUALIFIER_HISTORY_KEY = 'group_knockout_qualifier_history';
    public const FIRST_ROUND_ORDER_KEY = 'group_knockout_first_round_order';

    public function firstRoundEditor(Tournament $tournament): ?array
    {
        $sources = $this->configuredQualifierSources($tournament);
        if ($sources === []) {
            return null;
        }

        $stage = $this->completedGroupStage($tournament);
        $qualifiers = $stage[0] ?? $sources;
        $plan = $this->firstRoundPlan($tournament, $qualifiers);
        $fixtures = KnockoutSlot::query()
            ->where('tournament_id', $tournament->id)
            ->where('round_number', 1)
            ->whereNotNull('match_id')
            ->with('match:id,match_date,time_start,time_end,venue')
            ->get()
            ->keyBy('match_index')
            ->map->match;

        return [
            'sources' => array_column($qualifiers, null, 'source'),
            'matches' => $plan['slots'],
            'manual' => $plan['manual'],
            'fixtures' => $fixtures,
            'configured' => ($tournament->settings[self::READY_KEY] ?? false) && $fixtures->isNotEmpty(),
        ];
    }

    public function saveFirstRoundOrder(Tournament $tournament, ?array $order): bool
    {
        return DB::transaction(function () use ($tournament, $order): bool {
            $locked = Tournament::query()->whereKey($tournament->id)->lockForUpdate()->firstOrFail();
            $sources = $this->configuredQualifierSources($locked);
            if ($sources === []) {
                throw new \RuntimeException('Lengkapi pengaturan grup dan jumlah tim yang lolos sebelum menyusun bagan.');
            }

            $draw = app(KnockoutDrawService::class);
            if ($locked->status === 'completed' || $draw->hasStartedKnockoutMatches($locked, lockMatches: true)) {
                throw new \RuntimeException('Susunan bagan terkunci karena laga knockout sudah dimulai, memiliki hasil, atau turnamen sudah selesai.');
            }

            if ($order !== null && !$this->usesAllSourcesOnce($order, $sources)) {
                throw new \RuntimeException('Isi setiap posisi dengan asal tim lolos yang berbeda. Setiap asal tim harus dipakai tepat sekali.');
            }

            $hasBracket = $draw->hasBracket($locked);
            $settings = $locked->settings ?? [];
            $groupStageReady = $this->hasCompletedGroupStage($locked);
            if ($hasBracket && !($settings[self::READY_KEY] ?? false)) {
                throw new \RuntimeException('Susunan hanya bisa diubah pada bagan otomatis dari fase grup.');
            }

            if (!$hasBracket && $locked->matches()->whereNull('group_name')
                ->whereNotIn('round_type', ['group', 'league', 'friendly'])->exists()) {
                throw new \RuntimeException('Ada pertandingan knockout lama yang belum terhubung dengan bagan. Periksa jadwal yang sudah ada sebelum menyusun bagan.');
            }

            if ($hasBracket && !$groupStageReady) {
                throw new \RuntimeException('Selesaikan semua pertandingan grup sebelum mengubah bagan yang sudah terbentuk.');
            }

            if ($order === null) {
                unset($settings[self::FIRST_ROUND_ORDER_KEY]);
            } else {
                $settings[self::FIRST_ROUND_ORDER_KEY] = $order;
            }

            $locked->settings = $settings;
            $locked->save();

            if (!$groupStageReady) {
                return false;
            }

            if (!$this->sync($locked, true)) {
                throw new \RuntimeException('Bagan belum dapat diperbarui. Periksa jumlah tim lolos dan hasil pertandingan grup.');
            }

            return true;
        }, 3);
    }

    private function configuredQualifierSources(Tournament $tournament): array
    {
        $groups = $tournament->groups;
        $quota = (int) ($tournament->qualify_per_group ?? 2);
        $teamCount = count($groups) * $quota;
        if ($tournament->type !== 'group_knockout'
            || count($groups) > 8
            || $quota < 1
            || $quota > (int) ($tournament->teams_per_group ?? 4)
            || $teamCount < 2
            || $teamCount > 64) {
            return [];
        }

        $sources = [];
        for ($position = 1; $position <= $quota; $position++) {
            foreach ($groups as $group) {
                $sources[] = [
                    'source' => $group.':'.$position,
                    'group_name' => $group,
                    'group_position' => $position,
                    'team_id' => null,
                ];
            }
        }

        return $sources;
    }

    private function usesAllSourcesOnce(array $order, array $qualifiers): bool
    {
        if (!array_is_list($order)
            || count($order) !== count($qualifiers)
            || array_filter($order, 'is_string') !== $order) {
            return false;
        }

        $expected = array_column($qualifiers, 'source');
        sort($expected, SORT_STRING);
        sort($order, SORT_STRING);

        return $order === $expected;
    }

    public function hasCompletedGroupStage(Tournament $tournament): bool
    {
        return $tournament->type === 'group_knockout'
            && $this->completedGroupStage($tournament, false) !== null;
    }

    public function hasCompletedGroup(Tournament $tournament, string $groupName): bool
    {
        if ($tournament->type !== 'group_knockout') {
            return false;
        }

        $groupName = strtoupper(trim($groupName));
        $teamIds = $tournament->teams()
            ->withPivot('group_name')
            ->get(['teams.id'])
            ->filter(fn ($team) => strtoupper(trim((string) $team->pivot->group_name)) === $groupName)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        if (count($teamIds) < 2) {
            return false;
        }

        $expectedFixtures = [];
        for ($i = 0; $i < count($teamIds) - 1; $i++) {
            for ($j = $i + 1; $j < count($teamIds); $j++) {
                $expectedFixtures[$this->pairKey($teamIds[$i], $teamIds[$j])] = true;
            }
        }

        $matches = Game::query()
            ->where('tournament_id', $tournament->id)
            ->where('round_type', 'group')
            ->whereRaw('UPPER(TRIM(group_name)) = ?', [$groupName])
            ->get();

        if (count($expectedFixtures) === 0 || $matches->count() !== count($expectedFixtures)) {
            return false;
        }

        $seenFixtures = [];
        foreach ($matches as $match) {
            $homeId = (int) $match->team_home_id;
            $awayId = (int) $match->team_away_id;
            $fixtureKey = $this->pairKey($homeId, $awayId);

            if ($match->is_cancelled
                || $match->status !== 'completed'
                || $match->home_score === null
                || $match->away_score === null
                || $homeId === $awayId
                || !isset($expectedFixtures[$fixtureKey])
                || isset($seenFixtures[$fixtureKey])) {
                return false;
            }

            $seenFixtures[$fixtureKey] = true;
        }

        return count($seenFixtures) === count($expectedFixtures);
    }

    public function sync(Tournament $tournament, bool $strict = false): bool
    {
        if ($tournament->type !== 'group_knockout') {
            return false;
        }

        $synchronize = function () use ($tournament, $strict): bool {
            $lockedTournament = Tournament::query()
                ->whereKey($tournament->id)
                ->lockForUpdate()
                ->firstOrFail();
            $draw = app(KnockoutDrawService::class);
            $hasBracket = $draw->hasBracket($lockedTournament);
            $stage = $this->completedGroupStage($lockedTournament);

            if (!$stage) {
                if (!empty($lockedTournament->settings[self::QUALIFIER_OVERRIDES_KEY])
                    && $this->hasCompletedGroupStage($lockedTournament)) {
                    if ($strict) {
                        throw new \RuntimeException('Bagan knockout belum dapat diperbarui. Periksa jumlah tim yang lolos dan pilihan di setiap grup.');
                    }

                    return $hasBracket;
                }

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
            $plan = $this->firstRoundPlan($lockedTournament, $qualifiers);
            if ($plan['manual']) {
                $fingerprint = hash('sha256', $fingerprint.'|'.implode('|', $settings[self::FIRST_ROUND_ORDER_KEY]));
            }
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

            if ($hasBracket) {
                if (!($settings[self::READY_KEY] ?? false)) {
                    if ($strict) {
                        throw new \RuntimeException('Bracket yang sudah ada tidak dikenali sebagai bracket otomatis fase grup. Jadwal tidak diubah.');
                    }

                    return false;
                }

                if (!$this->replaceGeneratedBracketSlots($lockedTournament, $plan)) {
                    throw new \RuntimeException('Struktur bracket tersimpan tidak cocok dengan jumlah tim lolos. Jadwal tidak diubah; hubungi admin sistem untuk pemeriksaan.');
                }
            } else {
                $this->createSeededSlots($lockedTournament, $plan);
            }

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
            $settings['draw_created_at'] ??= now()->toIso8601String();
            $settings['draw_completed_at'] = now()->toIso8601String();
            $lockedTournament->settings = $settings;
            $lockedTournament->save();

            $draw->generateAllMatches($lockedTournament);

            return true;
        };

        try {
            return DB::transaction($synchronize);
        } catch (ValidationException $exception) {
            if ($strict) {
                throw $exception;
            }

            return app(KnockoutDrawService::class)->hasBracket($tournament);
        }
    }

    /** Return qualifiers and a stable fingerprint only when the entire round robin is complete. */
    private function completedGroupStage(Tournament $tournament, bool $applyOverrides = true): ?array
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
        $overrides = $applyOverrides
            ? ($tournament->settings[self::QUALIFIER_OVERRIDES_KEY] ?? [])
            : [];
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

            $selectedTeamIds = $overrides[$groupName] ?? null;
            if ($selectedTeamIds !== null) {
                $selectedTeamIds = array_values(array_unique(array_map('intval', (array) $selectedTeamIds)));
                $groupTeamIds = array_column($rows, 'team_id');
                if (count($selectedTeamIds) !== $qualifyPerGroup
                    || count(array_intersect($selectedTeamIds, $groupTeamIds)) !== $qualifyPerGroup) {
                    return null;
                }

                $selectedSet = array_fill_keys($selectedTeamIds, true);
                $selectedRows = array_values(array_filter($rows, fn (array $row) => isset($selectedSet[$row['team_id']])));
            } else {
                $selectedRows = array_slice($rows, 0, $qualifyPerGroup);
            }

            foreach ($selectedRows as $position => $standing) {
                $qualifiers[] = $standing + [
                    'source' => $groupName.':'.($position + 1),
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

    private function createSeededSlots(Tournament $tournament, array $plan): void
    {
        $size = $plan['size'];
        foreach ($plan['slots'] as $matchIndex => $sides) {
            foreach ($sides as $side => $slot) {
                $this->createFirstRoundSlot($tournament, $matchIndex, $side, $slot['team_id'] ?? null, $slot === null);
            }
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

    /** Keep fixture rows and their scheduled dates while replacing qualifier participants. */
    private function replaceGeneratedBracketSlots(Tournament $tournament, array $plan): bool
    {
        $firstRound = KnockoutSlot::query()
            ->where('tournament_id', $tournament->id)
            ->where('round_number', 1)
            ->get()
            ->keyBy(fn (KnockoutSlot $slot) => $slot->match_index.'-'.$slot->side);

        if ($firstRound->count() !== $plan['size']) {
            return false;
        }

        foreach ($plan['slots'] as $matchIndex => $sides) {
            foreach ($sides as $side => $slot) {
                if (!$firstRound->has($matchIndex.'-'.$side)) {
                    return false;
                }
            }
        }

        foreach ($plan['slots'] as $matchIndex => $sides) {
            foreach ($sides as $side => $slot) {
                $record = $firstRound->get($matchIndex.'-'.$side);
                $record->update([
                    'slot_number' => ($matchIndex * 2) + ($side === 'home' ? 1 : 2),
                    'team_id' => $slot['team_id'] ?? null,
                    'source' => $slot === null ? KnockoutSlot::SOURCE_BYE : KnockoutSlot::SOURCE_DRAW,
                    'source_match_id' => null,
                    'status' => $slot === null ? KnockoutSlot::STATUS_EMPTY : KnockoutSlot::STATUS_FILLED,
                    'result' => $slot === null ? KnockoutSlot::RESULT_BYE : KnockoutSlot::RESULT_PENDING,
                ]);
            }
        }

        KnockoutSlot::query()
            ->where('tournament_id', $tournament->id)
            ->where('round_number', '>', 1)
            ->update([
                'team_id' => null,
                'source' => KnockoutSlot::SOURCE_WINNER,
                'source_match_id' => null,
                'status' => KnockoutSlot::STATUS_EMPTY,
                'result' => KnockoutSlot::RESULT_PENDING,
            ]);

        return true;
    }

    private function firstRoundPlan(Tournament $tournament, array $qualifiers): array
    {
        $plan = $this->seededFirstRoundPlan($qualifiers);
        $order = $tournament->settings[self::FIRST_ROUND_ORDER_KEY] ?? null;
        $plan['manual'] = is_array($order) && $this->usesAllSourcesOnce($order, $qualifiers);
        if (!$plan['manual']) {
            return $plan;
        }

        $bySource = array_column($qualifiers, null, 'source');
        $position = 0;
        foreach ($plan['slots'] as $matchIndex => $sides) {
            foreach ($sides as $side => $qualifier) {
                if ($qualifier !== null) {
                    $plan['slots'][$matchIndex][$side] = $bySource[$order[$position++]];
                }
            }
        }

        return $plan;
    }

    /** A null side is a BYE. Its position stays fixed when qualifiers are rearranged. */
    private function seededFirstRoundPlan(array $qualifiers): array
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

        $slots = [];
        $nextTeamIndex = 0;
        $openMatches = [];
        for ($matchIndex = 0; $matchIndex < $matchCount; $matchIndex++) {
            if (in_array($matchIndex, $byeMatchIndexes, true)) {
                $slots[$matchIndex] = [
                    'home' => $qualifiers[$nextTeamIndex++],
                    'away' => null,
                ];
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
            $slots[$matchIndex] = [
                'home' => $home,
                'away' => $away,
            ];
        }

        if ($remaining !== []) {
            throw new \RuntimeException('Sebagian peserta lolos tidak mendapat slot bracket.');
        }

        ksort($slots);

        return ['size' => $size, 'slots' => $slots];
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
