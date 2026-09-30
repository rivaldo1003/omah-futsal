<?php

namespace App\Services;

use App\Models\Game;
use App\Models\KnockoutSlot;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentDraw;
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

    /**
     * Ukuran bracket dipaksa ke pangkat 2 (2,4,8,16,32) yang >= jumlah tim.
     */
    public function bracketSize(Tournament $tournament): int
    {
        $configured = (int) ($tournament->knockout_teams ?? 0);
        $teamCount = $tournament->teams()->count();

        $size = max($configured, $teamCount, 2);
        $pow = 1;
        while ($pow < $size) {
            $pow *= 2;
        }

        return $pow;
    }

    public function drawStatus(Tournament $tournament): string
    {
        $settings = $tournament->settings ?? [];

        return $settings['draw_status'] ?? self::DRAW_DRAFT;
    }

    /** Slot/bracket sudah dibuat? */
    public function hasBracket(Tournament $tournament): bool
    {
        return KnockoutSlot::where('tournament_id', $tournament->id)->exists();
    }

    /**
     * Bangun struktur bracket. Slot kosong dibuat lebih dulu, tanpa tim.
     * Idempoten: memanggil ulang akan mereset roster undian.
     */
    public function buildBracket(Tournament $tournament): void
    {
        DB::transaction(function () use ($tournament) {
            $size = $this->bracketSize($tournament);
            $rounds = (int) round(log($size, 2));
            $teamCount = $tournament->teams()->count();
            $byeCount = max(0, $size - $teamCount);

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
            $settings['draw_created_at'] = now()->toDateTimeString();
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
    public function drawForTeam(Tournament $tournament, Team $team): int
    {
        return DB::transaction(function () use ($tournament, $team) {
            // Idempoten untuk tim yang sama
            $existing = TournamentDraw::where('tournament_id', $tournament->id)
                ->where('team_id', $team->id)
                ->first();
            if ($existing) {
                return (int) $existing->slot_number;
            }

            $available = $this->availableSlotNumbers($tournament);
            if (empty($available)) {
                throw new \RuntimeException('Semua slot sudah terisi.');
            }

            // Cabut acak
            $slotNumber = $available[array_rand($available)];

            $slot = KnockoutSlot::where('tournament_id', $tournament->id)
                ->where('round_number', 1)
                ->where('slot_number', $slotNumber)
                ->lockForUpdate()
                ->firstOrFail();

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

    /** Jumlah slot yang harus terisi = bracketSize - byeCount = jumlah tim. */
    public function drawPoolSize(Tournament $tournament): int
    {
        return $tournament->teams()->count();
    }

    private function markDrawCompleted(Tournament $tournament): void
    {
        $settings = $tournament->settings ?? [];
        $settings['draw_status'] = self::DRAW_COMPLETED;
        $settings['draw_completed_at'] = now()->toDateTimeString();
        $tournament->settings = $settings;
        $tournament->save();

        $this->resolveFirstRound($tournament);
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

    private function leafSlot(Tournament $tournament, int $matchIndex, string $side): ?KnockoutSlot
    {
        return KnockoutSlot::where('tournament_id', $tournament->id)
            ->where('round_number', 1)
            ->where('match_index', $matchIndex)
            ->where('side', $side)
            ->first();
    }

    private function ensureFirstRoundMatch(Tournament $tournament, int $matchIndex, int $homeTeamId, int $awayTeamId, KnockoutSlot $home, KnockoutSlot $away): void
    {
        $matchId = $home->match_id ?: $away->match_id;

        $match = $matchId ? Game::find($matchId) : null;

        if (!$match) {
            $match = Game::create([
                'tournament_id' => $tournament->id,
                'match_date' => $tournament->start_date,
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
            $match->update([
                'team_home_id' => $homeTeamId,
                'team_away_id' => $awayTeamId,
            ]);
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

        if (!$home || !$away || !$home->team_id || !$away->team_id) {
            return;
        }

        $size = (int) ($tournament->settings['draw_bracket_size'] ?? $this->bracketSize($tournament));

        $matchId = $home->match_id ?: $away->match_id;
        $match = $matchId ? Game::find($matchId) : null;

        if (!$match) {
            $match = Game::create([
                'tournament_id' => $tournament->id,
                'match_date' => $tournament->start_date,
                'time_start' => $this->timeSlot($matchIndex, $tournament),
                'time_end' => $this->timeEnd($this->timeSlot($matchIndex, $tournament), $tournament),
                'team_home_id' => $home->team_id,
                'team_away_id' => $away->team_id,
                'venue' => $tournament->location ?? 'Main Field',
                'status' => 'upcoming',
                'round_type' => $this->roundType($size, $roundNumber),
                'group_name' => null,
            ]);
        } else {
            $match->update([
                'team_home_id' => $home->team_id,
                'team_away_id' => $away->team_id,
            ]);
        }

        $home->match_id = $match->id;
        $home->save();
        $away->match_id = $match->id;
        $away->save();
    }

    /**
     * Panggil ketika sebuah pertandingan selesai: isi slot ronde berikutnya.
     */
    public function advanceWinner(Game $match): void
    {
        if (!$match->tournament_id) {
            return;
        }

        // Cari slot target (ronde berikutnya) yang menunjuk match ini sebagai sumber.
        $target = KnockoutSlot::where('tournament_id', $match->tournament_id)
            ->where('source_match_id', $match->id)
            ->first();

        // Fallback: cari berdasarkan posisi pertandingan round-1 leaf
        if (!$target) {
            $leaf = KnockoutSlot::where('tournament_id', $match->tournament_id)
                ->where('match_id', $match->id)
                ->whereNotNull('slot_number')
                ->first();

            if ($leaf) {
                $tournament = Tournament::find($match->tournament_id);
                $fromMatchIndex = intdiv(((int) $leaf->slot_number - 1), 2);
                $target = KnockoutSlot::where('tournament_id', $match->tournament_id)
                    ->where('round_number', 2)
                    ->where('match_index', intdiv($fromMatchIndex, 2))
                    ->where('side', $fromMatchIndex % 2 === 0 ? 'home' : 'away')
                    ->first();
            }
        }

        if (!$target) {
            return;
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

        // Tandai hasil pada slot match ini
        KnockoutSlot::where('tournament_id', $match->tournament_id)
            ->where('match_id', $match->id)
            ->where('team_id', $winnerId)
            ->update(['result' => KnockoutSlot::RESULT_ADVANCED]);

        if ($loserId) {
            KnockoutSlot::where('tournament_id', $match->tournament_id)
                ->where('match_id', $match->id)
                ->where('team_id', $loserId)
                ->update(['result' => KnockoutSlot::RESULT_ELIMINATED]);
        }

        // Sinkronkan pertandingan ronde berikutnya bila kedua sisinya sudah terisi
        $tournament = Tournament::find($match->tournament_id);
        if ($tournament) {
            $this->resolveMatchSlots($tournament, $target->round_number, $target->match_index);
        }
    }

    /** Roster undian lengkap? */
    public function isDrawComplete(Tournament $tournament): bool
    {
        $teams = $tournament->teams()->count();
        $drawn = $this->drawnCount($tournament);

        return $teams > 0 && $drawn >= $teams;
    }

    /**
     * Struktur bracket untuk ditampilkan di view.
     */
    public function bracketTree(Tournament $tournament): array
    {
        $size = (int) ($tournament->settings['draw_bracket_size'] ?? ($this->hasBracket($tournament) ? $this->bracketSize($tournament) : $this->bracketSize($tournament)));
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
        Game::where('tournament_id', $tournament->id)
            ->whereNull('group_name')
            ->whereIn('round_type', [
                'quarterfinal', 'semifinal', 'final', 'third_place',
                'round_of_16', 'round_of_32', 'knockout',
            ])
            ->delete();
    }

    /** Reset undian penuh, bracket tetap / dibuat ulang. */
    public function resetDraw(Tournament $tournament, bool $rebuild = true): void
    {
        DB::transaction(function () use ($tournament, $rebuild) {
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

    // ── Helpers (round/slot/time) ──

    private function roundType(int $size, int $roundNumber): string
    {
        if ($size <= 2) {
            return 'final';
        }

        $totalRounds = (int) round(log($size, 2));
        if ($roundNumber === $totalRounds) {
            return 'final';
        }
        if ($roundNumber === $totalRounds - 1) {
            return 'semifinal';
        }
        if ($roundNumber === $totalRounds - 2) {
            return 'quarterfinal';
        }

        return $size >= 32 ? 'round_of_32' : 'round_of_16';
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
        ][$type] ?? ('Round '.$roundNumber);
    }

    private function timeSlot(int $index, Tournament $tournament): string
    {
        $slots = $tournament->time_slots ?? ['14:00', '16:00', '18:00', '20:00'];

        return $slots[$index % count($slots)] ?? '14:00';
    }

    private function timeEnd(string $start, Tournament $tournament): string
    {
        $duration = (int) ($tournament->settings['match_duration'] ?? 40);

        return date('H:i', strtotime($start) + ($duration * 60));
    }
}