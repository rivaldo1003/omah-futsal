<?php

namespace App\Services;

use App\Models\Game;
use App\Models\KnockoutSlot;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentDraw;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Knockout Draw Engine.
 *
 * Tiga konsep yang sengaja dipisah:
 *   - Bracket (KnockoutSlot): struktur pertandingan, dibuat lebih dulu.
 *   - Draw    (TournamentDraw): penentuan posisi tim pada bracket.
 *   - Result  (Game): penentu siapa yang maju ke node berikutnya.
 */
class KnockoutDrawService
{
    public const DRAW_DRAFT = 'DRAFT';
    public const DRAW_DRAWING = 'DRAWING';
    public const DRAW_COMPLETED = 'DRAW_COMPLETED';
    public const DRAW_IN_PROGRESS = 'IN_PROGRESS';
    public const DRAW_FINISHED = 'COMPLETED';

    /** Kapasitas maksimum yang dipilih saat turnamen dibuat. */
    public function configuredBracketCapacity(Tournament $tournament): int
    {
        $configured = (int) ($tournament->settings['knockout_teams'] ?? $tournament->knockout_teams ?? 8);
        $configured = max(2, min(64, $configured));

        return $this->normalizeBracketSize($configured);
    }

    /**
     * Ukuran bracket aktual. Kapasitas adalah batas pendaftaran; ronde aktual
     * memakai pangkat dua terkecil yang cukup untuk jumlah tim.
     * Bracket yang sudah dibangun tetap memakai struktur tersimpannya.
     */
    public function bracketSize(Tournament $tournament): int
    {
        $builtSize = KnockoutSlot::where('tournament_id', $tournament->id)
            ->where('round_number', 1)
            ->count();

        if ($builtSize >= 2) {
            return min(64, $this->normalizeBracketSize($builtSize));
        }

        return $this->requiredBracketSize($tournament->teams()->count());
    }

    /** Ronde yang benar-benar ada pada bracket aktual turnamen. */
    public function availableRoundTypes(Tournament $tournament): array
    {
        $size = $this->bracketSize($tournament);
        $rounds = (int) round(log(max($size, 2), 2));
        $types = [];

        for ($roundNumber = 1; $roundNumber <= $rounds; $roundNumber++) {
            $types[] = $this->roundType($size, $roundNumber);
        }

        if ($tournament->knockout_third_place && $size >= 4) {
            $types[] = 'third_place';
        }

        return array_values(array_unique($types));
    }

    private function requiredBracketSize(int $teamCount): int
    {
        if ($teamCount > 64) {
            throw new \RuntimeException('Turnamen knockout mendukung maksimal 64 tim.');
        }

        return $this->normalizeBracketSize(max(2, $teamCount));
    }

    private function normalizeBracketSize(int $size): int
    {
        $powerOfTwo = 1;
        while ($powerOfTwo < max(2, $size)) {
            $powerOfTwo *= 2;
        }

        if ($powerOfTwo > 64) {
            throw new \RuntimeException('Bracket knockout mendukung maksimal 64 slot.');
        }

        return $powerOfTwo;
    }

    public function drawStatus(Tournament $tournament): string
    {
        // A persisted status can get out of sync after a partial reset or data
        // cleanup. The actual slot structure is the source of truth.
        if (!$this->hasBracket($tournament)) {
            return self::DRAW_DRAFT;
        }

        $settings = $tournament->settings ?? [];
        $status = $settings['draw_status'] ?? self::DRAW_DRAFT;

        if (!$this->isDrawComplete($tournament)) {
            return self::DRAW_DRAWING;
        }

        return in_array($status, [self::DRAW_COMPLETED, self::DRAW_IN_PROGRESS, self::DRAW_FINISHED], true)
            ? $status
            : self::DRAW_COMPLETED;
    }

    /** Slot/bracket sudah dibuat? */
    public function hasBracket(Tournament $tournament): bool
    {
        return KnockoutSlot::where('tournament_id', $tournament->id)->exists();
    }

    /**
     * Bangun struktur bracket. Slot kosong dibuat lebih dulu, tanpa tim.
     * Hanya bracket kosong yang dapat dibangun ulang tanpa melalui Reset.
     */
    public function buildBracket(Tournament $tournament): void
    {
        DB::transaction(function () use ($tournament) {
            $teamCount = $tournament->teams()->count();
            if ($teamCount < 2) {
                throw new \RuntimeException('Bracket knockout membutuhkan minimal 2 tim.');
            }

            $capacity = $this->configuredBracketCapacity($tournament);
            if ($teamCount > $capacity) {
                throw new \RuntimeException("Kapasitas bracket {$capacity} tim tidak cukup untuk {$teamCount} tim.");
            }

            $size = $this->requiredBracketSize($teamCount);
            $rounds = (int) round(log($size, 2));
            $byeCount = max(0, $size - $teamCount);

            if ($this->drawnCount($tournament) > 0) {
                throw new \RuntimeException('Undian sudah berisi tim. Gunakan Reset sebelum membangun bracket ulang.');
            }

            if ($this->hasStartedKnockoutMatches($tournament)) {
                throw new \RuntimeException('Bracket memiliki pertandingan yang sudah dimulai atau selesai. Hasil pertandingan harus diselesaikan sebelum bracket dibangun ulang.');
            }

            $hasKnockoutFixtures = Game::where('tournament_id', $tournament->id)
                ->whereNull('group_name')
                ->whereIn('round_type', [
                    'round_of_64', 'round_of_32', 'round_of_16', 'quarterfinal',
                    'semifinal', 'final', 'third_place', 'knockout',
                ])
                ->exists();

            if ($hasKnockoutFixtures) {
                throw new \RuntimeException('Fixture knockout sudah ada. Reset fixture terlebih dahulu sebelum membangun bracket baru.');
            }

            // Hapus bracket & undian lama (reset bersih)
            $this->purgeBracket($tournament);

            // Nomor slot BYE: pilih satu per pertandingan dari belakang agar tidak
            // ada dua BYE di pertandingan yang sama (byeCount < size/2 dijamin).
            $byeSlotNumbers = [];
            for ($i = 0; $i < $byeCount; $i++) {
                // away slot pertandingan ke-i
                $byeSlotNumbers[] = ($i * 2) + 2;
            }

            // Buat slot leaf (babak 1)
            for ($m = 0; $m < intdiv($size, 2); $m++) {
                foreach (['home', 'away'] as $side) {
                    $slotNumber = ($m * 2) + ($side === 'home' ? 1 : 2);
                    $isBye = in_array($slotNumber, $byeSlotNumbers, true);

                    KnockoutSlot::create([
                        'tournament_id' => $tournament->id,
                        'round_number' => 1,
                        'match_index' => $m,
                        'side' => $side,
                        'slot_number' => $slotNumber,
                        'source' => $isBye ? KnockoutSlot::SOURCE_BYE : KnockoutSlot::SOURCE_EMPTY,
                        'status' => KnockoutSlot::STATUS_EMPTY,
                        'result' => $isBye ? KnockoutSlot::RESULT_BYE : KnockoutSlot::RESULT_PENDING,
                    ]);
                }
            }

            // Buat slot untuk ronde berikutnya (diisi pemenang)
            for ($r = 2; $r <= $rounds; $r++) {
                $matchesInRound = intdiv($size, (int) pow(2, $r));
                for ($m = 0; $m < $matchesInRound; $m++) {
                    foreach (['home', 'away'] as $side) {
                        KnockoutSlot::create([
                            'tournament_id' => $tournament->id,
                            'round_number' => $r,
                            'match_index' => $m,
                            'side' => $side,
                            'slot_number' => null,
                            'source' => KnockoutSlot::SOURCE_WINNER,
                            'status' => KnockoutSlot::STATUS_EMPTY,
                            'result' => KnockoutSlot::RESULT_PENDING,
                        ]);
                    }
                }
            }

            // Tandai status turnamen = DRAWING
            $settings = $tournament->settings ?? [];
            $settings['draw_status'] = self::DRAW_DRAWING;
            $settings['draw_bracket_size'] = $size;
            $settings['draw_bracket_capacity'] = $this->configuredBracketCapacity($tournament);
            $settings['draw_created_at'] = now()->toIso8601String();
            unset($settings['draw_completed_at']);
            $tournament->settings = $settings;
            $tournament->save();
        });
    }

    /** Nomor slot yang masih tersedia untuk diundi (bukan BYE, belum terisi). */
    public function availableSlotNumbers(Tournament $tournament): array
    {
        return KnockoutSlot::where('tournament_id', $tournament->id)
            ->where('round_number', 1)
            ->where('source', '!=', KnockoutSlot::SOURCE_BYE)
            ->whereNull('team_id')
            ->orderBy('slot_number')
            ->pluck('slot_number')
            ->map(fn ($slotNumber) => (int) $slotNumber)
            ->all();
    }

    /** Tim yang belum mendapatkan slot. */
    public function remainingTeams(Tournament $tournament)
    {
        $drawn = TournamentDraw::where('tournament_id', $tournament->id)->pluck('team_id');

        return $tournament->teams()
            ->whereNotIn('teams.id', $drawn)
            ->orderBy('teams.name')
            ->get();
    }

    public function drawnCount(Tournament $tournament): int
    {
        return TournamentDraw::where('tournament_id', $tournament->id)->count();
    }

    /**
     * Cabut nomor untuk satu tim. Mengembalikan nomor slot yang didapat.
     * Aturan: satu tim satu slot; satu slot satu tim; slot terpakai hilang.
     */
    public function drawForTeam(Tournament $tournament, Team $team, ?int $preferredSlotNumber = null): int
    {
        return DB::transaction(function () use ($tournament, $team, $preferredSlotNumber) {
            // Serialize placement changes with per-team cancellation requests.
            Tournament::query()->whereKey($tournament->id)->lockForUpdate()->firstOrFail();

            // Idempoten untuk tim yang sama
            $existing = TournamentDraw::where('tournament_id', $tournament->id)
                ->where('team_id', $team->id)
                ->first();
            if ($existing) {
                return (int) $existing->slot_number;
            }

            if (!$tournament->teams()->where('teams.id', $team->id)->exists()) {
                throw new \RuntimeException('Tim ini tidak terdaftar pada turnamen.');
            }

            if (in_array($this->drawStatus($tournament), [self::DRAW_COMPLETED, self::DRAW_IN_PROGRESS, self::DRAW_FINISHED], true)) {
                throw new \RuntimeException('Undian sudah selesai dan tidak dapat ditambah. Reset bracket terlebih dahulu.');
            }

            $available = $this->availableSlotNumbers($tournament);
            if (empty($available)) {
                throw new \RuntimeException('Semua slot sudah terisi.');
            }

            if ($preferredSlotNumber !== null && !in_array($preferredSlotNumber, $available, true)) {
                throw new \RuntimeException('Slot yang dipilih tidak tersedia untuk undian.');
            }

            // Cabut acak, kecuali pemanggil internal meminta posisi seeding.
            $slotNumber = $preferredSlotNumber ?? $available[array_rand($available)];

            $slot = KnockoutSlot::where('tournament_id', $tournament->id)
                ->where('round_number', 1)
                ->where('slot_number', $slotNumber)
                ->lockForUpdate()
                ->first();

            // Recheck after acquiring the row lock so simultaneous manual
            // placements cannot overwrite a slot selected by another request.
            if (!$slot || $slot->source === KnockoutSlot::SOURCE_BYE || $slot->team_id !== null) {
                throw new \RuntimeException('Slot yang dipilih sudah tidak tersedia. Pilih slot kosong lainnya.');
            }

            $slot->team_id = $team->id;
            $slot->source = KnockoutSlot::SOURCE_DRAW;
            $slot->status = KnockoutSlot::STATUS_FILLED;
            $slot->save();

            $request = request();

            TournamentDraw::create([
                'tournament_id' => $tournament->id,
                'team_id' => $team->id,
                'slot_number' => $slotNumber,
                'slot_id' => $slot->id,
                'drawn_by' => optional($request->user())->id,
                'drawn_at' => now(),
                'ip' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 255, ''),
            ]);

            // Setelah tiap undian, sinkronkan pertandingan (dan tangani BYE)
            $this->resolveFirstRound($tournament);

            if ($this->drawnCount($tournament) >= $this->drawPoolSize($tournament)) {
                $this->markDrawCompleted($tournament);
            }

            return $slotNumber;
        });
    }

    /** Remove one placement while preserving every other drawn slot. */
    public function cancelTeamPlacement(Tournament $tournament, Team $team): int
    {
        return DB::transaction(function () use ($tournament, $team) {
            $lockedTournament = Tournament::query()
                ->whereKey($tournament->id)
                ->lockForUpdate()
                ->firstOrFail();

            $matches = Game::query()
                ->where('tournament_id', $lockedTournament->id)
                ->whereNull('group_name')
                ->whereIn('round_type', [
                    'round_of_64', 'round_of_32', 'round_of_16', 'quarterfinal',
                    'semifinal', 'final', 'third_place', 'knockout',
                ])
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $hasStartedMatch = $matches->contains(fn (Game $match) =>
                in_array($match->status, ['ongoing', 'completed'], true)
                || (int) ($match->home_score ?? 0) !== 0
                || (int) ($match->away_score ?? 0) !== 0
                || $match->et_score !== null
                || $match->penalty_score !== null
                || $match->events()->exists()
            );

            if ($hasStartedMatch) {
                throw new \RuntimeException('Penempatan tidak dapat dibatalkan karena pertandingan knockout sudah dimulai atau memiliki hasil/event.');
            }

            $draw = TournamentDraw::query()
                ->where('tournament_id', $lockedTournament->id)
                ->where('team_id', $team->id)
                ->lockForUpdate()
                ->first();

            if (!$draw) {
                throw new \RuntimeException('Tim ini belum memiliki penempatan yang dapat dibatalkan.');
            }

            $slot = KnockoutSlot::query()
                ->where('tournament_id', $lockedTournament->id)
                ->where('round_number', 1)
                ->where('slot_number', $draw->slot_number)
                ->lockForUpdate()
                ->first();

            if (!$slot
                || (int) $slot->team_id !== (int) $team->id
                || $slot->source !== KnockoutSlot::SOURCE_DRAW) {
                throw new \RuntimeException('Penempatan tim dan slot bracket tidak sinkron. Tidak ada data yang diubah.');
            }

            $slotNumber = (int) $slot->slot_number;
            $firstRoundMatchId = $slot->match_id;
            $futureSlots = KnockoutSlot::query()
                ->where('tournament_id', $lockedTournament->id)
                ->where('round_number', '>', 1)
                ->lockForUpdate()
                ->get();
            $futureMatchIds = $futureSlots->pluck('match_id')->filter()->unique();

            KnockoutSlot::query()
                ->where('tournament_id', $lockedTournament->id)
                ->where('round_number', '>', 1)
                ->update([
                    'team_id' => null,
                    'source' => KnockoutSlot::SOURCE_WINNER,
                    'source_match_id' => null,
                    'status' => KnockoutSlot::STATUS_EMPTY,
                    'result' => KnockoutSlot::RESULT_PENDING,
                ]);

            foreach ($futureMatchIds as $matchId) {
                $match = $matches->get($matchId);
                if ($match) {
                    $this->syncMatchParticipants($match, null, null);
                }
            }

            $slot->team_id = null;
            $slot->source = KnockoutSlot::SOURCE_EMPTY;
            $slot->status = KnockoutSlot::STATUS_EMPTY;
            $slot->result = KnockoutSlot::RESULT_PENDING;
            $slot->save();
            $draw->delete();

            if ($firstRoundMatchId && ($firstRoundMatch = $matches->get($firstRoundMatchId))) {
                $home = $this->leafSlot($lockedTournament, (int) $slot->match_index, 'home');
                $away = $this->leafSlot($lockedTournament, (int) $slot->match_index, 'away');
                $this->syncMatchParticipants($firstRoundMatch, $home?->team_id, $away?->team_id);
            }

            $settings = $lockedTournament->settings ?? [];
            $settings['draw_status'] = self::DRAW_DRAWING;
            unset($settings['draw_completed_at']);
            $lockedTournament->settings = $settings;
            $lockedTournament->save();

            // Re-advance any first-round BYE teams whose placements remain valid.
            $this->resolveFirstRound($lockedTournament);

            return $slotNumber;
        });
    }

    /** Jumlah slot yang harus terisi = bracketSize - byeCount = jumlah tim. */
    public function drawPoolSize(Tournament $tournament): int
    {
        return $tournament->teams()->count();
    }

    private function markDrawCompleted(Tournament $tournament): void
    {
        $settings = $tournament->settings ?? [];
        $settings['draw_status'] = self::DRAW_COMPLETED;
        $settings['draw_completed_at'] = now()->toIso8601String();
        $tournament->settings = $settings;
        $tournament->save();

        $this->resolveFirstRound($tournament);
        $this->resolveAutomaticByes($tournament);
    }

    /**
     * Sinkronkan babak 1: buat pertandingan bila 2 tim sudah ada,
     * atau majukan tim yang menghadapi BYE ke babak berikutnya.
     */
    public function resolveFirstRound(Tournament $tournament): void
    {
        $size = (int) (($tournament->settings['draw_bracket_size'] ?? null) ?: $this->bracketSize($tournament));
        $firstRoundMatches = intdiv($size, 2);

        for ($m = 0; $m < $firstRoundMatches; $m++) {
            $home = $this->leafSlot($tournament, $m, 'home');
            $away = $this->leafSlot($tournament, $m, 'away');

            if (!$home || !$away) {
                continue;
            }

            $homeTeam = $home->team_id;
            $awayTeam = $away->team_id;
            $homeBye = $home->source === KnockoutSlot::SOURCE_BYE;
            $awayBye = $away->source === KnockoutSlot::SOURCE_BYE;

            // Belum lengkap dan bukan BYE -> tunggu undian berikutnya
            if (!$homeTeam && !$homeBye) {
                continue;
            }
            if (!$awayTeam && !$awayBye) {
                continue;
            }

            // Dua BYE (seharusnya tidak terjadi) -> lewati
            if ($homeBye && $awayBye) {
                continue;
            }

            // Satu tim + BYE -> tim otomatis maju ke babak 2
            if ($homeBye xor $awayBye) {
                $teamId = $homeBye ? $awayTeam : $homeTeam;
                if ($teamId) {
                    $this->advanceToNextRound($tournament, $m, (int) $teamId, $homeBye ? $home : $away);
                }
                continue;
            }

            // Dua tim nyata -> buat pertandingan bila belum ada
            if ($homeTeam && $awayTeam) {
                $this->ensureFirstRoundMatch($tournament, $m, (int) $homeTeam, (int) $awayTeam, $home, $away);
            }
        }
    }

    /**
     * Tutup jalur bracket yang sudah pasti kosong dan teruskan tim yang
     * mendapat bye pada ronde mana pun. Tidak menyentuh jalur yang masih
     * menunggu hasil pertandingan nyata.
     */
    private function resolveAutomaticByes(Tournament $tournament, bool $syncFixtures = true): void
    {
        if (!$this->isDrawComplete($tournament)) {
            return;
        }

        $size = (int) ($tournament->settings['draw_bracket_size'] ?? $this->bracketSize($tournament));
        $rounds = (int) round(log(max($size, 2), 2));

        for ($roundNumber = 1; $roundNumber < $rounds; $roundNumber++) {
            $matchesInRound = intdiv($size, (int) pow(2, $roundNumber));

            for ($matchIndex = 0; $matchIndex < $matchesInRound; $matchIndex++) {
                $home = $roundNumber === 1
                    ? $this->leafSlot($tournament, $matchIndex, 'home')
                    : $this->matchSlot($tournament, $roundNumber, $matchIndex, 'home');
                $away = $roundNumber === 1
                    ? $this->leafSlot($tournament, $matchIndex, 'away')
                    : $this->matchSlot($tournament, $roundNumber, $matchIndex, 'away');

                if (!$home || !$away) {
                    continue;
                }

                $homeTeamId = $home->team_id ? (int) $home->team_id : null;
                $awayTeamId = $away->team_id ? (int) $away->team_id : null;
                $hasOneTeam = (((bool) $homeTeamId) xor ((bool) $awayTeamId));
                $hasNoTeams = !$homeTeamId && !$awayTeamId;

                $emptySide = null;
                $advancingSide = null;

                if ($hasOneTeam) {
                    $advancingSide = $homeTeamId ? $home : $away;
                    $emptySide = $homeTeamId ? $away : $home;
                    $emptyIsResolved = $roundNumber === 1
                        || ($emptySide->source === KnockoutSlot::SOURCE_BYE && !$emptySide->team_id);

                    if (!$emptyIsResolved) {
                        continue;
                    }

                    if ($roundNumber > 1) {
                        $this->removeUnplayedByeFixture($home, $away);
                    }

                    $emptySide->source = KnockoutSlot::SOURCE_BYE;
                    $emptySide->status = KnockoutSlot::STATUS_EMPTY;
                    $emptySide->result = KnockoutSlot::RESULT_BYE;
                    $emptySide->save();

                    $advancingSide->result = KnockoutSlot::RESULT_ADVANCED;
                    $advancingSide->save();
                } elseif ($hasNoTeams) {
                    $bothResolvedEmpty = $roundNumber === 1
                        || ($home->source === KnockoutSlot::SOURCE_BYE && $away->source === KnockoutSlot::SOURCE_BYE);

                    if (!$bothResolvedEmpty) {
                        continue;
                    }

                    if ($roundNumber > 1) {
                        $this->removeUnplayedByeFixture($home, $away);
                    }

                    foreach ([$home, $away] as $emptySlot) {
                        $emptySlot->source = KnockoutSlot::SOURCE_BYE;
                        $emptySlot->status = KnockoutSlot::STATUS_EMPTY;
                        $emptySlot->result = KnockoutSlot::RESULT_BYE;
                        $emptySlot->save();
                    }
                } else {
                    continue;
                }

                $nextMatchIndex = intdiv($matchIndex, 2);
                $nextSide = $matchIndex % 2 === 0 ? 'home' : 'away';
                $target = $this->matchSlot($tournament, $roundNumber + 1, $nextMatchIndex, $nextSide);

                if (!$target) {
                    continue;
                }

                if ($advancingSide) {
                    $teamId = (int) $advancingSide->team_id;
                    if ((int) $target->team_id !== $teamId || $target->source !== KnockoutSlot::SOURCE_BYE) {
                        $target->team_id = $teamId;
                        $target->source = KnockoutSlot::SOURCE_BYE;
                        $target->source_match_id = null;
                        $target->status = KnockoutSlot::STATUS_FILLED;
                        $target->result = KnockoutSlot::RESULT_PENDING;
                        $target->save();
                    }

                    if ($syncFixtures) {
                        $this->resolveMatchSlots($tournament, $roundNumber + 1, $nextMatchIndex);
                    }
                } elseif ($target->team_id === null) {
                    $target->source = KnockoutSlot::SOURCE_BYE;
                    $target->source_match_id = null;
                    $target->status = KnockoutSlot::STATUS_EMPTY;
                    $target->result = KnockoutSlot::RESULT_BYE;
                    $target->save();
                }
            }
        }
    }

    private function removeUnplayedByeFixture(KnockoutSlot $home, KnockoutSlot $away): void
    {
        $matchIds = collect([$home->match_id, $away->match_id])
            ->filter()
            ->unique()
            ->values();

        if ($matchIds->count() !== 1) {
            return;
        }

        $match = Game::find($matchIds->first());
        if (!$match
            || !in_array($match->status, ['upcoming', 'postponed'], true)
            || (int) ($match->home_score ?? 0) !== 0
            || (int) ($match->away_score ?? 0) !== 0
            || $match->et_score !== null
            || $match->penalty_score !== null
            || $match->events()->exists()) {
            return;
        }

        KnockoutSlot::where('tournament_id', $match->tournament_id)
            ->where('match_id', $match->id)
            ->update(['match_id' => null]);

        $match->delete();
    }

    private function leafSlot(Tournament $tournament, int $matchIndex, string $side): ?KnockoutSlot
    {
        return KnockoutSlot::where('tournament_id', $tournament->id)
            ->where('round_number', 1)
            ->where('match_index', $matchIndex)
            ->where('side', $side)
            ->first();
    }

    private function matchSlot(Tournament $tournament, int $roundNumber, int $matchIndex, string $side): ?KnockoutSlot
    {
        return KnockoutSlot::where('tournament_id', $tournament->id)
            ->where('round_number', $roundNumber)
            ->where('match_index', $matchIndex)
            ->where('side', $side)
            ->lockForUpdate()
            ->first();
    }

    private function ensureFirstRoundMatch(Tournament $tournament, int $matchIndex, int $homeTeamId, int $awayTeamId, KnockoutSlot $home, KnockoutSlot $away): void
    {
        $matchId = $home->match_id ?: $away->match_id;

        $match = $matchId ? Game::find($matchId) : null;

        if (!$match) {
            $match = Game::create([
                'tournament_id' => $tournament->id,
                'match_date' => $this->matchDate($tournament, 1),
                'time_start' => $this->timeSlot($matchIndex, $tournament),
                'time_end' => $this->timeEnd($this->timeSlot($matchIndex, $tournament), $tournament),
                'team_home_id' => $homeTeamId,
                'team_away_id' => $awayTeamId,
                'venue' => $tournament->location ?? 'Main Field',
                'status' => 'upcoming',
                'round_type' => $this->roundType((int) ($tournament->settings['draw_bracket_size'] ?? $this->bracketSize($tournament)), 1),
                'group_name' => null,
            ]);
        } else {
            $this->syncMatchParticipants($match, $homeTeamId, $awayTeamId);
        }

        // Tautkan kedua slot leaf ke pertandingan ini
        $home->match_id = $match->id;
        $home->save();
        $away->match_id = $match->id;
        $away->save();

        // Record eliminasi/pending dipertahankan; status tetap filled.
    }

    /**
     * Pemenang (atau tim BYE) maju ke slot ronde berikutnya.
     */
    public function advanceToNextRound(Tournament $tournament, int $fromMatchIndex, int $teamId, ?KnockoutSlot $originSlot = null): void
    {
        $nextMatchIndex = intdiv($fromMatchIndex, 2);
        $nextSide = ($fromMatchIndex % 2 === 0) ? 'home' : 'away';

        $target = KnockoutSlot::where('tournament_id', $tournament->id)
            ->where('round_number', 2)
            ->where('match_index', $nextMatchIndex)
            ->where('side', $nextSide)
            ->first();

        if (!$target) {
            return;
        }

        $target->team_id = $teamId;
        $target->source = $originSlot && $originSlot->source === KnockoutSlot::SOURCE_BYE
            ? KnockoutSlot::SOURCE_BYE
            : KnockoutSlot::SOURCE_WINNER;
        $target->status = KnockoutSlot::STATUS_FILLED;
        if ($originSlot) {
            $target->source_match_id = $originSlot->match_id;
        }
        $target->save();

        // Isi pertandingan ronde 2 bila kedua sisinya sudah siap
        $this->resolveMatchSlots($tournament, 2, $nextMatchIndex);
    }

    /**
     * Bila kedua slot sebuah pertandingan (ronde >=2) sudah terisi tim,
     * buat / perbarui Game-nya.
     */
    public function resolveMatchSlots(Tournament $tournament, int $roundNumber, int $matchIndex): void
    {
        $home = KnockoutSlot::where('tournament_id', $tournament->id)
            ->where('round_number', $roundNumber)
            ->where('match_index', $matchIndex)
            ->where('side', 'home')->first();
        $away = KnockoutSlot::where('tournament_id', $tournament->id)
            ->where('round_number', $roundNumber)
            ->where('match_index', $matchIndex)
            ->where('side', 'away')->first();

        if (!$home || !$away) {
            return;
        }

        $size = (int) ($tournament->settings['draw_bracket_size'] ?? $this->bracketSize($tournament));
        $roundType = $this->roundType($size, $roundNumber);

        $linkedMatchIds = collect([$home->match_id, $away->match_id])
            ->filter()
            ->unique()
            ->values();

        if ($linkedMatchIds->count() > 1) {
            throw new \RuntimeException('Slot bracket terhubung ke match yang berbeda.');
        }

        $match = $linkedMatchIds->isNotEmpty()
            ? Game::find($linkedMatchIds->first())
            : null;

        // Legacy fixtures yang belum terhubung masih dapat dipetakan dari
        // urutan ID; setelah ditautkan, slot bracket menjadi sumber utamanya.
        if (!$match) {
            $match = Game::where('tournament_id', $tournament->id)
                ->where('round_type', $roundType)
                ->orderBy('id')
                ->skip($matchIndex)
                ->first();
        }

        // Match yang sudah dibuat sejak awal tetap menampilkan sisi yang sudah
        // diketahui, meski pemenang dari pertandingan pasangannya belum ada.
        if (!$home->team_id || !$away->team_id) {
            if ($match) {
                $this->syncMatchParticipants($match, $home->team_id, $away->team_id);
                $this->linkMatchSlots($home, $away, $match);
            }

            return;
        }

        if (!$match) {
            $match = Game::create([
                'tournament_id' => $tournament->id,
                'match_date' => $this->matchDate($tournament, $roundNumber),
                'time_start' => $this->timeSlot($matchIndex, $tournament),
                'time_end' => $this->timeEnd($this->timeSlot($matchIndex, $tournament), $tournament),
                'team_home_id' => $home->team_id,
                'team_away_id' => $away->team_id,
                'venue' => $tournament->location ?? 'Main Field',
                'status' => 'upcoming',
                'round_type' => $roundType,
                'group_name' => null,
            ]);
        } else {
            $this->syncMatchParticipants($match, $home->team_id, $away->team_id);
        }

        $this->linkMatchSlots($home, $away, $match);
    }

    private function linkMatchSlots(KnockoutSlot $home, KnockoutSlot $away, Game $match): void
    {
        foreach ([$home, $away] as $slot) {
            if ((int) $slot->match_id !== (int) $match->id) {
                $slot->match_id = $match->id;
                $slot->save();
            }
        }
    }

    /**
     * Panggil ketika sebuah pertandingan selesai: isi slot ronde berikutnya.
     */
    public function advanceWinner(Game $match, bool $syncFixtures = true): bool
    {
        if (!$match->tournament_id) {
            return false;
        }

        $tournament = Tournament::find($match->tournament_id);
        if (!$tournament) {
            return false;
        }

        $size = (int) ($tournament->settings['draw_bracket_size'] ?? $this->bracketSize($tournament));
        $roundNumber = $this->roundNumberForMatch($match, $size);

        // Final tidak memiliki slot berikutnya.
        if (!$roundNumber || $roundNumber >= (int) round(log(max($size, 2), 2))) {
            return true;
        }

        // Tentukan tujuan dari pasangan peserta/indeks bracket. Tautan sumber
        // lama bisa keliru, jadi hanya dipakai jika posisi tidak bisa ditentukan.
        $sourceMatchIndex = $this->sourceMatchIndex($match, $roundNumber);
        $target = null;
        if ($sourceMatchIndex !== null) {
            $sourceTeamIds = KnockoutSlot::where('tournament_id', $match->tournament_id)
                ->where('round_number', $roundNumber)
                ->where('match_index', $sourceMatchIndex)
                ->whereNotNull('team_id')
                ->pluck('team_id')
                ->map(fn ($teamId) => (int) $teamId)
                ->sort()
                ->values()
                ->all();
            $matchTeamIds = collect([$match->team_home_id, $match->team_away_id])
                ->filter()
                ->map(fn ($teamId) => (int) $teamId)
                ->sort()
                ->values()
                ->all();

            if (count($sourceTeamIds) !== 2 || $sourceTeamIds !== $matchTeamIds) {
                throw new \RuntimeException("Peserta match #{$match->id} tidak cocok dengan slot babak bracket-nya. Periksa fixture sebelum melanjutkan hasil.");
            }

            $target = KnockoutSlot::where('tournament_id', $match->tournament_id)
                ->where('round_number', $roundNumber + 1)
                ->where('match_index', intdiv($sourceMatchIndex, 2))
                ->where('side', $sourceMatchIndex % 2 === 0 ? 'home' : 'away')
                ->first();
        } else {
            $target = KnockoutSlot::where('tournament_id', $match->tournament_id)
                ->where('source_match_id', $match->id)
                ->first();
        }

        if (!$target) {
            return false;
        }

        $winnerId = $match->getWinnerId();
        $loserId = $match->getLoserId();

        if ($winnerId) {
            $target->team_id = $winnerId;
            $target->source = KnockoutSlot::SOURCE_WINNER;
            $target->source_match_id = $match->id;
            $target->status = KnockoutSlot::STATUS_FILLED;
            $target->save();
        }

        // Tandai hasil slot sumber, termasuk slot lama yang belum terhubung ke match.
        $sourceSlots = KnockoutSlot::where('tournament_id', $match->tournament_id)
            ->where('round_number', $roundNumber)
            ->where('match_index', $sourceMatchIndex ?? -1)
            ->get();

        foreach ($sourceSlots as $sourceSlot) {
            if ($winnerId && (int) $sourceSlot->team_id === (int) $winnerId) {
                $sourceSlot->result = KnockoutSlot::RESULT_ADVANCED;
            } elseif ($loserId && (int) $sourceSlot->team_id === (int) $loserId) {
                $sourceSlot->result = KnockoutSlot::RESULT_ELIMINATED;
            }
            $sourceSlot->save();
        }

        // Saat replay progres, seluruh slot diisi dahulu. Fixture disinkronkan
        // setelahnya agar final yang sedang disimpan tidak terlihat setengah
        // kosong selama proses rekonstruksi bracket.
        if ($syncFixtures) {
            $this->resolveMatchSlots($tournament, $target->round_number, $target->match_index);
            $this->resolveAutomaticByes($tournament);
        }

        if ($syncFixtures && $match->round_type === 'semifinal' && $tournament->knockout_third_place && $loserId) {
            $this->assignThirdPlaceLoser($match, $tournament, (int) $loserId);
        }

        return true;
    }

    /** Re-apply completed results in round order to repair older bracket links. */
    public function syncCompletedResults(Tournament $tournament): void
    {
        DB::transaction(function () use ($tournament) {
            $size = (int) ($tournament->settings['draw_bracket_size'] ?? $this->bracketSize($tournament));
            $rounds = (int) round(log(max($size, 2), 2));

            $this->resolveFirstRound($tournament);
            $this->linkFirstRoundFixtures($tournament, $size);

            // Later-round entrants are derived data. Rebuild them from first-round
            // draw slots and completed results so an old, incorrectly advanced
            // winner cannot remain duplicated in a different branch.
            KnockoutSlot::where('tournament_id', $tournament->id)
                ->where('round_number', 1)
                ->where('source', KnockoutSlot::SOURCE_DRAW)
                ->update(['result' => KnockoutSlot::RESULT_PENDING]);

            KnockoutSlot::where('tournament_id', $tournament->id)
                ->where('round_number', '>', 1)
                ->update([
                    'team_id' => null,
                    'source' => KnockoutSlot::SOURCE_WINNER,
                    'source_match_id' => null,
                    'status' => KnockoutSlot::STATUS_EMPTY,
                    'result' => KnockoutSlot::RESULT_PENDING,
                ]);

            // Rebuild slot state without writing intermediate, partially
            // reconstructed participants to fixtures that may already have a
            // result (for example, the final currently being updated).
            $this->resolveAutomaticByes($tournament, false);

            $matches = Game::where('tournament_id', $tournament->id)
                ->where('status', 'completed')
                ->whereIn('round_type', [
                    'round_of_64', 'round_of_32', 'round_of_16', 'quarterfinal',
                    'semifinal', 'third_place', 'final',
                ])
                ->orderByRaw("CASE
                    WHEN round_type = 'round_of_64' THEN 1
                    WHEN round_type = 'round_of_32' THEN 2
                    WHEN round_type = 'round_of_16' THEN 3
                    WHEN round_type = 'quarterfinal' THEN 4
                    WHEN round_type = 'semifinal' THEN 5
                    WHEN round_type = 'third_place' THEN 6
                    WHEN round_type = 'final' THEN 7
                    ELSE 7
                END")
                ->orderBy('id')
                ->get();

            foreach ($matches as $match) {
                $match->refresh();
                if ($match->status === 'completed') {
                    $this->advanceWinner($match, false);
                }
            }

            // Apply the fully rebuilt participant slots to each later fixture.
            for ($roundNumber = 2; $roundNumber <= $rounds; $roundNumber++) {
                $matchesInRound = intdiv($size, (int) pow(2, $roundNumber));
                for ($matchIndex = 0; $matchIndex < $matchesInRound; $matchIndex++) {
                    $this->resolveMatchSlots($tournament, $roundNumber, $matchIndex);
                }
            }

            if ($tournament->knockout_third_place) {
                $this->ensureThirdPlaceFixture($tournament);
            }

            $this->resolveAutomaticByes($tournament);
            $this->assertNoDuplicateTeamsInBracketRounds($tournament, $rounds);
        });
    }

    /** Keep visual fixture order aligned with each bracket slot index. */
    public function orderMatchesForBracket(Tournament $tournament, $matches)
    {
        $matches = $matches->reject(function (Game $match) use ($tournament) {
            return $match->round_type === 'third_place'
                && (!$tournament->knockout_third_place || $match->is_cancelled);
        })->values();

        $size = (int) ($tournament->settings['draw_bracket_size'] ?? $this->bracketSize($tournament));
        $rounds = (int) round(log(max($size, 2), 2));
        $positions = KnockoutSlot::where('tournament_id', $tournament->id)
            ->whereNotNull('match_id')
            ->get()
            ->groupBy('match_id')
            ->map(fn ($slots) => $slots->first());

        $fallbackIndexes = [];
        foreach ($matches->sortBy('id')->values() as $match) {
            $slot = $positions->get($match->id);
            if ($slot) {
                $match->setAttribute('bracket_match_index', (int) $slot->match_index);
                continue;
            }

            $roundNumber = $this->roundNumberForMatch($match, $size);
            $participantPosition = $roundNumber
                ? $this->sourceMatchIndex($match, $roundNumber)
                : null;
            if ($participantPosition !== null) {
                $match->setAttribute('bracket_match_index', $participantPosition);
                continue;
            }

            $roundType = $match->round_type;
            $match->setAttribute('bracket_match_index', $fallbackIndexes[$roundType] ?? 0);
            $fallbackIndexes[$roundType] = ($fallbackIndexes[$roundType] ?? 0) + 1;
        }

        // A BYE is still a real bracket position. Include it as a virtual card
        // so hidden BYEs do not renumber matches or collapse the bracket layout.
        $firstRoundType = $this->roundType($size, 1);
        for ($matchIndex = 0; $matchIndex < intdiv($size, 2); $matchIndex++) {
            $alreadyRendered = $matches->contains(fn (Game $match) =>
                $match->round_type === $firstRoundType
                && (int) $match->getAttribute('bracket_match_index') === $matchIndex
            );

            if ($alreadyRendered) {
                continue;
            }

            $home = $this->leafSlot($tournament, $matchIndex, 'home');
            $away = $this->leafSlot($tournament, $matchIndex, 'away');
            if (!$home || !$away || (!$home->isBye() && !$away->isBye()) || (!$home->team_id && !$away->team_id)) {
                continue;
            }

            $byeMatch = new Game([
                'team_home_id' => $home->team_id,
                'team_away_id' => $away->team_id,
                'round_type' => $firstRoundType,
                'status' => 'bye',
            ]);
            $byeMatch->setRelation('homeTeam', $home->team);
            $byeMatch->setRelation('awayTeam', $away->team);
            $byeMatch->setAttribute('bracket_match_index', $matchIndex);
            $byeMatch->setAttribute('bracket_bye', true);
            $byeMatch->setAttribute('bracket_bye_side', $home->isBye() ? 'home' : 'away');
            $matches->push($byeMatch);
        }

        return $matches->sort(function (Game $left, Game $right) use ($positions, $size, $rounds) {
            $position = function (Game $match) use ($positions, $size, $rounds) {
                $slot = $positions->get($match->id);
                $roundNumber = $slot?->round_number
                    ?? ($match->round_type === 'third_place'
                        ? $rounds - 0.5
                        : ($this->roundNumberForMatch($match, $size) ?? ($rounds + 1)));
                $matchIndex = $slot?->match_index ?? $match->getAttribute('bracket_match_index') ?? $match->id;

                return [$roundNumber, $matchIndex, $match->id ?? 0];
            };

            return $position($left) <=> $position($right);
        })->values();
    }

    /** Create/update the optional playoff fixture when the setting is enabled. */
    public function ensureThirdPlaceFixture(Tournament $tournament): ?Game
    {
        if (!$tournament->knockout_third_place || $tournament->teams()->count() < 4) {
            return null;
        }

        if ($tournament->type === 'group_knockout' && !$this->hasBracket($tournament)) {
            return null;
        }

        $semifinals = Game::where('tournament_id', $tournament->id)
            ->where('round_type', 'semifinal')
            ->orderBy('id')
            ->get();

        $fixture = Game::where('tournament_id', $tournament->id)
            ->where('round_type', 'third_place')
            ->first();

        if ($semifinals->count() < 2) {
            if ($fixture?->is_cancelled) {
                $fixture->update(['is_cancelled' => false]);
            }

            return $fixture?->fresh();
        }

        if (!$fixture) {
            $size = (int) ($tournament->settings['draw_bracket_size'] ?? $this->bracketSize($tournament));
            $rounds = (int) round(log(max($size, 2), 2));
            $timeStart = $this->timeSlot(0, $tournament);

            $fixture = Game::create([
                'tournament_id' => $tournament->id,
                'match_date' => $this->matchDate($tournament, $rounds),
                'time_start' => $timeStart,
                'time_end' => $this->timeEnd($timeStart, $tournament),
                'team_home_id' => null,
                'team_away_id' => null,
                'venue' => $tournament->location ?? 'Main Field',
                'status' => 'upcoming',
                'round_type' => 'third_place',
                'group_name' => null,
            ]);
        }

        if ($fixture->is_cancelled) {
            $fixture->update(['is_cancelled' => false]);
        }

        $participants = [
            'team_home_id' => null,
            'team_away_id' => null,
        ];
        $size = (int) ($tournament->settings['draw_bracket_size'] ?? $this->bracketSize($tournament));
        $semifinalRound = (int) round(log(max($size, 2), 2)) - 1;

        foreach ($semifinals as $index => $semifinal) {
            if ($semifinal->status !== 'completed' || !$semifinal->getLoserId()) {
                continue;
            }

            $slot = KnockoutSlot::where('tournament_id', $tournament->id)
                ->where('match_id', $semifinal->id)
                ->where('round_number', $semifinalRound)
                ->first();
            $matchIndex = $slot ? (int) $slot->match_index : $index;
            $side = $matchIndex % 2 === 0 ? 'team_home_id' : 'team_away_id';
            $participants[$side] = $semifinal->getLoserId();
        }

        if ($participants['team_home_id'] !== $fixture->team_home_id
            || $participants['team_away_id'] !== $fixture->team_away_id) {
            $this->syncMatchParticipants(
                $fixture,
                $participants['team_home_id'] ? (int) $participants['team_home_id'] : null,
                $participants['team_away_id'] ? (int) $participants['team_away_id'] : null,
            );
        }

        return $fixture->fresh();
    }

    private function linkFirstRoundFixtures(Tournament $tournament, int $size): void
    {
        $firstRoundType = $this->roundType($size, 1);
        $matches = Game::where('tournament_id', $tournament->id)
            ->where('round_type', $firstRoundType)
            ->orderBy('id')
            ->get();

        foreach ($matches as $match) {
            $matchIndex = $this->sourceMatchIndex($match, 1);
            if ($matchIndex === null) {
                continue;
            }

            $home = $this->leafSlot($tournament, $matchIndex, 'home');
            $away = $this->leafSlot($tournament, $matchIndex, 'away');
            if ($home && $away) {
                $this->linkMatchSlots($home, $away, $match);
            }
        }
    }

    private function roundNumberForMatch(Game $match, int $size): ?int
    {
        $rounds = (int) round(log(max($size, 2), 2));
        for ($roundNumber = 1; $roundNumber <= $rounds; $roundNumber++) {
            if ($this->roundType($size, $roundNumber) === $match->round_type) {
                return $roundNumber;
            }
        }

        return null;
    }

    private function sourceMatchIndex(Game $match, int $roundNumber): ?int
    {
        if ($match->team_home_id && $match->team_away_id) {
            $teamIds = [(int) $match->team_home_id, (int) $match->team_away_id];
            $candidateSlots = KnockoutSlot::where('tournament_id', $match->tournament_id)
                ->where('round_number', $roundNumber)
                ->whereIn('team_id', $teamIds)
                ->get()
                ->groupBy('match_index');

            foreach ($candidateSlots as $matchIndex => $matchSlots) {
                if ($matchSlots->pluck('team_id')->map(fn ($id) => (int) $id)->unique()->count() === 2) {
                    return (int) $matchIndex;
                }
            }
        }

        $slots = KnockoutSlot::where('tournament_id', $match->tournament_id)
            ->where('round_number', $roundNumber)
            ->where('match_id', $match->id)
            ->first();

        if ($slots) {
            return (int) $slots->match_index;
        }

        $roundMatches = Game::where('tournament_id', $match->tournament_id)
            ->where('round_type', $match->round_type)
            ->orderBy('id')
            ->pluck('id');
        $position = $roundMatches->search($match->id);

        return $position === false ? null : (int) $position;
    }

    private function assertNoDuplicateTeamsInBracketRounds(Tournament $tournament, int $rounds): void
    {
        for ($roundNumber = 1; $roundNumber <= $rounds; $roundNumber++) {
            $duplicate = KnockoutSlot::where('tournament_id', $tournament->id)
                ->where('round_number', $roundNumber)
                ->whereNotNull('team_id')
                ->select('team_id')
                ->selectRaw('COUNT(*) AS slot_count')
                ->groupBy('team_id')
                ->havingRaw('COUNT(*) > 1')
                ->first();

            if ($duplicate) {
                throw new \RuntimeException("Tim #{$duplicate->team_id} muncul lebih dari sekali pada babak {$roundNumber}. Hasil bracket tidak disimpan agar jalurnya tidak makin keliru.");
            }
        }
    }

    private function assignThirdPlaceLoser(Game $match, Tournament $tournament, int $loserId): void
    {
        $size = (int) ($tournament->settings['draw_bracket_size'] ?? $this->bracketSize($tournament));
        $semifinalRound = (int) round(log(max($size, 2), 2)) - 1;
        $semifinalSlot = KnockoutSlot::where('tournament_id', $tournament->id)
            ->where('match_id', $match->id)
            ->where('round_number', $semifinalRound)
            ->first();

        if (!$semifinalSlot) {
            return;
        }

        $thirdPlaceMatch = Game::where('tournament_id', $tournament->id)
            ->where('round_type', 'third_place')
            ->first();

        if (!$thirdPlaceMatch) {
            return;
        }

        $field = $semifinalSlot->match_index === 0 ? 'team_home_id' : 'team_away_id';
        $homeTeamId = $field === 'team_home_id' ? $loserId : $thirdPlaceMatch->team_home_id;
        $awayTeamId = $field === 'team_away_id' ? $loserId : $thirdPlaceMatch->team_away_id;
        $this->syncMatchParticipants($thirdPlaceMatch, $homeTeamId, $awayTeamId);
    }

    private function syncMatchParticipants(Game $match, ?int $homeTeamId, ?int $awayTeamId): void
    {
        $participantsChanged = (int) $match->team_home_id !== (int) $homeTeamId
            || (int) $match->team_away_id !== (int) $awayTeamId;

        if ($participantsChanged) {
            $hasRecordedResult = in_array($match->status, ['ongoing', 'completed'], true)
                || (int) ($match->home_score ?? 0) !== 0
                || (int) ($match->away_score ?? 0) !== 0
                || $match->et_score !== null
                || $match->penalty_score !== null
                || $match->events()->exists();

            if ($hasRecordedResult) {
                throw new \RuntimeException('Peserta fixture tidak dapat diganti karena pertandingan sudah dimulai atau memiliki hasil/event.');
            }
        }

        $attributes = [
            'team_home_id' => $homeTeamId,
            'team_away_id' => $awayTeamId,
        ];

        if ($participantsChanged && $match->status !== 'upcoming') {
            $attributes['status'] = 'upcoming';
        }

        $match->update($attributes);
    }

    /** Roster undian lengkap? */
    public function isDrawComplete(Tournament $tournament): bool
    {
        if ($tournament->type === 'group_knockout'
            && ($tournament->settings[GroupKnockoutBracketService::READY_KEY] ?? false)) {
            $expectedTeamIds = collect($tournament->settings[GroupKnockoutBracketService::QUALIFIERS_KEY] ?? [])
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->sort()
                ->values();
            $slotTeamIds = KnockoutSlot::where('tournament_id', $tournament->id)
                ->where('round_number', 1)
                ->where('source', KnockoutSlot::SOURCE_DRAW)
                ->whereNotNull('team_id')
                ->pluck('team_id')
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->sort()
                ->values();

            return $expectedTeamIds->count() >= 2
                && $expectedTeamIds->all() === $slotTeamIds->all();
        }

        $teams = $tournament->teams()->count();
        $drawn = $this->drawnCount($tournament);

        return $teams > 0 && $drawn >= $teams;
    }

    /**
     * Create every fixture in a completed bracket. Future-round fixtures keep
     * empty team sides until earlier matches determine their participants.
     */
    public function generateAllMatches(Tournament $tournament): int
    {
        if (!$this->hasBracket($tournament)) {
            throw new \RuntimeException('Bracket belum dibuat.');
        }

        if (!$this->isDrawComplete($tournament)) {
            throw new \RuntimeException('Selesaikan undian semua tim sebelum membuat match.');
        }

        return DB::transaction(function () use ($tournament) {
            $size = (int) ($tournament->settings['draw_bracket_size'] ?? $this->bracketSize($tournament));
            $rounds = (int) round(log(max($size, 2), 2));
            $created = 0;

            $this->resolveFirstRound($tournament);
            $this->resolveAutomaticByes($tournament);

            for ($roundNumber = 1; $roundNumber <= $rounds; $roundNumber++) {
                $matchesInRound = intdiv($size, (int) pow(2, $roundNumber));

                for ($matchIndex = 0; $matchIndex < $matchesInRound; $matchIndex++) {
                    $home = $this->matchSlot($tournament, $roundNumber, $matchIndex, 'home');
                    $away = $this->matchSlot($tournament, $roundNumber, $matchIndex, 'away');

                    if (!$home || !$away) {
                        throw new \RuntimeException('Struktur bracket tidak lengkap. Bangun ulang bracket sebelum membuat match.');
                    }

                    // Tim yang berhadapan dengan BYE maju otomatis dan tidak membutuhkan fixture.
                    if ($roundNumber === 1 && ($home->isBye() || $away->isBye())) {
                        continue;
                    }

                    if ($roundNumber > 1 && (($home->isBye() && !$home->team_id) || ($away->isBye() && !$away->team_id))) {
                        continue;
                    }

                    $linkedMatchIds = collect([$home->match_id, $away->match_id])
                        ->filter()
                        ->unique()
                        ->values();

                    if ($linkedMatchIds->count() > 1) {
                        throw new \RuntimeException('Slot bracket terhubung ke match yang berbeda.');
                    }

                    $match = $linkedMatchIds->isNotEmpty()
                        ? Game::find($linkedMatchIds->first())
                        : null;

                    if ($match && (int) $match->tournament_id !== (int) $tournament->id) {
                        throw new \RuntimeException('Match bracket terhubung ke turnamen lain.');
                    }

                    if (!$match) {
                        $timeStart = $this->timeSlot($matchIndex, $tournament);
                        $match = Game::create([
                            'tournament_id' => $tournament->id,
                            'match_date' => $this->matchDate($tournament, $roundNumber),
                            'time_start' => $timeStart,
                            'time_end' => $this->timeEnd($timeStart, $tournament),
                            'team_home_id' => $home->team_id,
                            'team_away_id' => $away->team_id,
                            'venue' => $tournament->location ?? 'Main Field',
                            'status' => 'upcoming',
                            'round_type' => $this->roundType($size, $roundNumber),
                            'group_name' => null,
                        ]);
                        $created++;
                    } else {
                        $this->syncMatchParticipants($match, $home->team_id, $away->team_id);
                    }

                    foreach ([$home, $away] as $slot) {
                        if ((int) $slot->match_id !== (int) $match->id) {
                            $slot->match_id = $match->id;
                            $slot->save();
                        }
                    }
                }
            }

            if ($tournament->knockout_third_place && $size >= 4) {
                $thirdPlaceFixture = Game::where('tournament_id', $tournament->id)
                    ->where('round_type', 'third_place')
                    ->first();

                if (!$thirdPlaceFixture) {
                    $timeStart = $this->timeSlot(0, $tournament);
                    $thirdPlaceFixture = Game::create([
                        'tournament_id' => $tournament->id,
                        'match_date' => $this->matchDate($tournament, $rounds),
                        'time_start' => $timeStart,
                        'time_end' => $this->timeEnd($timeStart, $tournament),
                        'team_home_id' => null,
                        'team_away_id' => null,
                        'venue' => $tournament->location ?? 'Main Field',
                        'status' => 'upcoming',
                        'round_type' => 'third_place',
                        'group_name' => null,
                    ]);
                    $created++;
                } elseif ($thirdPlaceFixture->is_cancelled) {
                    $thirdPlaceFixture->update(['is_cancelled' => false]);
                }
            }

            return $created;
        });
    }

    /**
     * Struktur bracket untuk ditampilkan di view.
     */
    public function bracketTree(Tournament $tournament): array
    {
        if (!$this->hasBracket($tournament)) {
            return [];
        }

        $size = $this->bracketSize($tournament);
        $rounds = (int) round(log(max($size, 2), 2));

        $slots = KnockoutSlot::where('tournament_id', $tournament->id)
            ->with(['team', 'match'])
            ->get()
            ->groupBy(fn ($s) => $s->round_number.'-'.$s->match_index.'-'.$s->side);

        $tree = [];
        for ($r = 1; $r <= $rounds; $r++) {
            $matchesInRound = intdiv($size, (int) pow(2, $r));
            $roundMatches = [];
            for ($m = 0; $m < $matchesInRound; $m++) {
                $home = $slots->get($r.'-'.$m.'-home')?->first();
                $away = $slots->get($r.'-'.$m.'-away')?->first();

                $roundMatches[] = [
                    'match_index' => $m,
                    'round_number' => $r,
                    'home' => $home,
                    'away' => $away,
                    'match' => $home?->match ?? $away?->match,
                    'is_final' => $r === $rounds,
                ];
            }
            $tree[$r] = [
                'round_number' => $r,
                'label' => $this->roundLabel($size, $r),
                'matches' => $roundMatches,
            ];
        }

        return $tree;
    }

    /** Hapus bracket + undian + pertandingan knockout. */
    public function purgeBracket(Tournament $tournament): void
    {
        KnockoutSlot::where('tournament_id', $tournament->id)->delete();
        TournamentDraw::where('tournament_id', $tournament->id)->delete();
        // Hapus hanya pertandingan knockout milik undian (bukan friendly/league)
        $matches = Game::where('tournament_id', $tournament->id)
            ->whereNull('group_name')
            ->whereIn('round_type', [
                'quarterfinal', 'semifinal', 'final', 'third_place',
                'round_of_16', 'round_of_32', 'round_of_64', 'knockout',
            ])
            ->get();

        // Delete each model instance so Game's deleting hook can reverse the
        // player statistics attached to its match events.
        foreach ($matches as $match) {
            $match->delete();
        }
    }

    /** Reset undian dan fixture, lalu bangun ulang struktur jika diminta. */
    public function resetDraw(Tournament $tournament, bool $rebuild = true): void
    {
        DB::transaction(function () use ($tournament, $rebuild) {
            if ($this->hasStartedKnockoutMatches($tournament)) {
                throw new \RuntimeException('Bracket tidak dapat direset karena pertandingan knockout sudah dimulai, selesai, atau memiliki event.');
            }

            $this->purgeBracket($tournament);

            $settings = $tournament->settings ?? [];
            $settings['draw_status'] = self::DRAW_DRAFT;
            unset($settings['draw_completed_at']);
            $tournament->settings = $settings;
            $tournament->save();

            if ($rebuild) {
                $this->buildBracket($tournament);
            }
        });
    }

    public function hasStartedKnockoutMatches(Tournament $tournament): bool
    {
        $matches = Game::where('tournament_id', $tournament->id)
            ->whereNull('group_name')
            ->whereIn('round_type', [
                'round_of_64', 'round_of_32', 'round_of_16', 'quarterfinal',
                'semifinal', 'final', 'third_place', 'knockout',
            ])
            ->get();

        return $matches->contains(fn (Game $match) => in_array($match->status, ['ongoing', 'completed'], true)
            || (int) ($match->home_score ?? 0) !== 0
            || (int) ($match->away_score ?? 0) !== 0
            || $match->et_score !== null
            || $match->penalty_score !== null
            || $match->events()->exists());
    }

    // ── Helpers (round/slot/time) ──

    private function roundType(int $size, int $roundNumber): string
    {
        if ($size <= 2 || $roundNumber >= (int) round(log(max($size, 2), 2))) {
            return 'final';
        }

        $teamsInRound = intdiv($size, (int) pow(2, $roundNumber - 1));

        return match (true) {
            $teamsInRound >= 64 => 'round_of_64',
            $teamsInRound >= 32 => 'round_of_32',
            $teamsInRound >= 16 => 'round_of_16',
            $teamsInRound >= 8 => 'quarterfinal',
            $teamsInRound >= 4 => 'semifinal',
            default => 'final',
        };
    }

    private function roundLabel(int $size, int $roundNumber): string
    {
        $type = $this->roundType($size, $roundNumber);

        return [
            'final' => 'Final',
            'semifinal' => 'Semifinal',
            'quarterfinal' => 'Quarter Final',
            'round_of_16' => 'Round of 16',
            'round_of_32' => 'Round of 32',
            'round_of_64' => 'Round of 64',
        ][$type] ?? ('Round '.$roundNumber);
    }

    private function timeSlot(int $index, Tournament $tournament): string
    {
        $slots = $tournament->time_slots ?? ['14:00', '16:00', '18:00', '20:00'];
        if (empty($slots)) {
            $slots = ['14:00'];
        }

        return $slots[$index % count($slots)] ?? '14:00';
    }

    private function matchDate(Tournament $tournament, int $roundNumber): string
    {
        if ($tournament->type === 'group_knockout'
            && ($tournament->settings[GroupKnockoutBracketService::READY_KEY] ?? false)) {
            $lastGroupDate = Game::where('tournament_id', $tournament->id)
                ->where('round_type', 'group')
                ->max('match_date');
            $date = $lastGroupDate
                ? Carbon::parse($lastGroupDate)->addDay()
                : ($tournament->start_date ? Carbon::parse($tournament->start_date) : now());
        } else {
            $date = $tournament->start_date
                ? Carbon::parse($tournament->start_date)
                : now();
        }
        $date->addDays(max(0, $roundNumber - 1) * 2);

        if ($tournament->end_date) {
            $endDate = Carbon::parse($tournament->end_date);
            if ($date->greaterThan($endDate)) {
                $date = $endDate;
            }
        }

        return $date->toDateString();
    }

    private function timeEnd(string $start, Tournament $tournament): string
    {
        $duration = (int) ($tournament->settings['match_duration'] ?? 40);

        return date('H:i', strtotime($start) + ($duration * 60));
    }
}
