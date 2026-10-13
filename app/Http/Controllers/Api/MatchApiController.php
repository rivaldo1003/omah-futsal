<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Services\KnockoutDrawService;
use Illuminate\Http\Request;

class MatchApiController extends Controller
{
    public function generateFromDraw(Request $request, Tournament $tournament, KnockoutDrawService $draw)
    {
        abort_unless($tournament->type === 'knockout', 404);

        try {
            $created = $draw->generateAllMatches($tournament);
            $message = $created > 0
                ? "{$created} match baru berhasil dibuat dari bracket."
                : 'Semua match bracket sudah tersedia.';

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => true,
                    'created' => $created,
                    'message' => $message,
                ]);
            }

            return redirect()
                ->route('admin.matches.index', ['tournament_id' => $tournament->id])
                ->with('success', $message);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
