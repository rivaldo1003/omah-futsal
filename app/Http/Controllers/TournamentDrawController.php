<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentDraw;
use App\Services\KnockoutDrawService;
use Illuminate\Http\Request;

class TournamentDrawController extends Controller
{
    protected KnockoutDrawService $draw;

    public function __construct(KnockoutDrawService $draw)
    {
        $this->draw = $draw;
    }

    /**
     * Halaman Tournament Draw: daftar tim, bracket, tombol undian.
     */
    public function show(Tournament $tournament)
    {
        abort_unless($tournament->type === 'knockout', 404, 'Undian hanya untuk turnamen Knockout.');

        $hasBracket = $this->draw->hasBracket($tournament);
        $status = $this->draw->drawStatus($tournament);
        $tree = $hasBracket ? $this->draw->bracketTree($tournament) : [];
        $hasKnockoutFixtures = Game::where('tournament_id', $tournament->id)
            ->whereNull('group_name')
            ->whereIn('round_type', [
                'round_of_64', 'round_of_32', 'round_of_16', 'quarterfinal',
                'semifinal', 'final', 'third_place', 'knockout',
            ])
            ->exists();
        $hasDrawHistory = TournamentDraw::where('tournament_id', $tournament->id)->exists();
        $canCancelPlacement = $hasBracket && !$this->draw->hasStartedKnockoutMatches($tournament);

        $remainingTeams = $this->draw->remainingTeams($tournament);
        $availableSlots = $this->draw->availableSlotNumbers($tournament);

        $drawnTeams = TournamentDraw::with(['team', 'slot', 'drawnBy'])
            ->where('tournament_id', $tournament->id)
            ->orderBy('slot_number')
            ->get();

        $bracketSize = $this->draw->bracketSize($tournament);
        $bracketCapacity = $this->draw->configuredBracketCapacity($tournament);
        $teamCount = $tournament->teams()->count();
        $byeCount = max(0, $bracketSize - $teamCount);

        return view('admin.tournaments.draw', compact(
            'tournament',
            'hasBracket',
            'hasKnockoutFixtures',
            'hasDrawHistory',
            'canCancelPlacement',
            'status',
            'tree',
            'remainingTeams',
            'availableSlots',
            'drawnTeams',
            'bracketSize',
            'bracketCapacity',
            'teamCount',
            'byeCount'
        ));
    }

    /**
     * Bangun bracket (struktur) tanpa tim.
     */
    public function build(Tournament $tournament)
    {
        abort_unless($tournament->type === 'knockout', 404);

        try {
            $this->draw->buildBracket($tournament);

            $bracketSize = $this->draw->bracketSize($tournament);
            $capacity = $this->draw->configuredBracketCapacity($tournament);
            $teams = $tournament->teams()->count();
            $byeCount = max(0, $bracketSize - $teams);
            $summary = "Bracket {$bracketSize} slot untuk {$teams} tim berhasil dibuat";
            if ($byeCount > 0) {
                $summary .= ", dengan {$byeCount} BYE otomatis";
            }
            if ($bracketSize < $capacity) {
                $summary .= " (kapasitas terpilih {$capacity} slot)";
            }

            return redirect()
                ->route('admin.tournaments.draw.show', $tournament)
                ->with('success', $summary . '. Slot siap untuk undian.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat bracket: ' . $e->getMessage());
        }
    }

    /**
     * Cabut nomor untuk satu tim (AJAX-friendly JSON).
     */
    public function draw(Request $request, Tournament $tournament)
    {
        abort_unless($tournament->type === 'knockout', 404);

        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'slot_number' => 'nullable|integer|min:1|max:64',
        ]);

        try {
            // Pastikan bracket sudah dibangun. Keep this inside try so a
            // registration/capacity issue is returned as a normal draw error.
            if (!$this->draw->hasBracket($tournament)) {
                $this->draw->buildBracket($tournament);
            }

            $team = Team::findOrFail($validated['team_id']);
            $slotNumber = $this->draw->drawForTeam(
                $tournament,
                $team,
                isset($validated['slot_number']) ? (int) $validated['slot_number'] : null
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => true,
                    'team' => ['id' => $team->id, 'name' => $team->name],
                    'slot_number' => $slotNumber,
                    'remaining' => $this->draw->remainingTeams($tournament)->count(),
                    'status' => $this->draw->drawStatus($tournament),
                ]);
            }

            return redirect()
                ->route('admin.tournaments.draw.show', $tournament)
                ->with('success', $team->name . ' mendapatkan slot nomor ' . $slotNumber . '.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /** Return one incorrectly placed team to the undrawn pool. */
    public function cancelPlacement(Tournament $tournament, Team $team)
    {
        abort_unless($tournament->type === 'knockout', 404);

        try {
            $slotNumber = $this->draw->cancelTeamPlacement($tournament, $team);

            return redirect()
                ->route('admin.tournaments.draw.show', $tournament)
                ->with('success', "Penempatan {$team->name} dari slot #{$slotNumber} dibatalkan. Tim dikembalikan ke daftar undian.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan penempatan: ' . $e->getMessage());
        }
    }

    /**
     * Reset undian. Bracket dibangun ulang (slot kosong).
     */
    public function reset(Request $request, Tournament $tournament)
    {
        abort_unless($tournament->type === 'knockout', 404);

        try {
            $rebuild = $request->boolean('rebuild', true);
            $this->draw->resetDraw($tournament, $rebuild);

            $message = $rebuild
                ? 'Undian direset dan bracket dibangun ulang.'
                : 'Undian dan struktur bracket dihapus. Bangun bracket baru sebelum mengundi lagi.';

            return redirect()
                ->route('admin.tournaments.draw.show', $tournament)
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal reset: ' . $e->getMessage());
        }
    }

    /**
     * Simpan seluruh sisa undian sekaligus (opsi cepat / auto-draw).
     */
    public function drawAll(Request $request, Tournament $tournament)
    {
        abort_unless($tournament->type === 'knockout', 404);

        try {
            if (!$this->draw->hasBracket($tournament)) {
                $this->draw->buildBracket($tournament);
            }

            $teams = $this->draw->remainingTeams($tournament);
            foreach ($teams as $team) {
                $this->draw->drawForTeam($tournament, $team);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => true,
                    'status' => $this->draw->drawStatus($tournament),
                    'remaining' => $this->draw->remainingTeams($tournament)->count(),
                ]);
            }

            return redirect()
                ->route('admin.tournaments.draw.show', $tournament)
                ->with('success', 'Semua tim berhasil diundi. Bracket resmi terbentuk.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
