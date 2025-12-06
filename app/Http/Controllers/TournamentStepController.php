<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TournamentStepController extends Controller
{
    public function showStep(Request $request, $step = 1)
    {
        $currentStep = $step;
        $teams = Team::all();

        // Ambil data dari session jika ada
        $tournamentData = Session::get('tournament_data', []);

        return view('admin.tournaments.create-step', compact(
            'currentStep',
            'teams',
            'tournamentData'
        ));
    }

    public function storeStep(Request $request, $step)
    {
        switch ($step) {
            case 1:
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'slug' => 'nullable|string|max:255',
                    'description' => 'nullable|string',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'location' => 'nullable|string|max:255',
                    'organizer' => 'nullable|string|max:255',
                    'type' => 'required|in:league,knockout,group_knockout',
                    'status' => 'required|in:upcoming,ongoing,completed,cancelled',
                ]);

                // Simpan ke session
                $tournamentData = Session::get('tournament_data', []);
                $tournamentData = array_merge($tournamentData, $validated);
                Session::put('tournament_data', $tournamentData);

                return redirect()->route('tournament.create.step', ['step' => 2]);

            case 2:
                $validated = $request->validate([
                    'teams' => 'required|array|min:2',
                    'teams.*' => 'exists:teams,id',
                ]);

                // Simpan ke session
                $tournamentData = Session::get('tournament_data', []);
                $tournamentData = array_merge($tournamentData, $validated);
                Session::put('tournament_data', $tournamentData);

                return redirect()->route('tournament.create.step', ['step' => 3]);

            case 3:
                $validated = $request->validate([
                    'match_duration' => 'required|integer|min:10|max:120',
                    'half_time' => 'required|integer|min:5|max:30',
                    'extra_time' => 'nullable|integer|min:0|max:30',
                    'points_win' => 'required|integer|min:0|max:10',
                    'points_draw' => 'required|integer|min:0|max:5',
                    'points_loss' => 'required|integer|min:0|max:5',
                    'points_no_show' => 'nullable|integer|min:-10|max:0',
                    'max_substitutes' => 'required|integer|min:0|max:20',
                    'yellow_card_suspension' => 'nullable|integer|min:1|max:10',
                    'matches_per_day' => 'required|integer|min:1|max:20',
                    'match_interval' => 'required|integer|min:15|max:120',
                    'match_time_slots' => 'nullable|string',
                    'allow_draw' => 'nullable|boolean',
                    'extra_time_enabled' => 'nullable|boolean',
                    'penalty_shootout' => 'nullable|boolean',
                    'var_enabled' => 'nullable|boolean',
                ]);

                // Simpan ke session
                $tournamentData = Session::get('tournament_data', []);
                $tournamentData = array_merge($tournamentData, $validated);
                Session::put('tournament_data', $tournamentData);

                return redirect()->route('tournament.create.step', ['step' => 4]);

            case 4:
                // Validasi final dan simpan ke database
                $tournamentData = Session::get('tournament_data', []);

                // Validasi semua data
                $request->validate([
                    'confirmTournament' => 'required|accepted',
                ]);

                try {
                    // Buat tournament
                    $tournament = Tournament::create([
                        'name' => $tournamentData['name'],
                        'slug' => $tournamentData['slug'] ?? \Illuminate\Support\Str::slug($tournamentData['name']),
                        'description' => $tournamentData['description'] ?? null,
                        'start_date' => $tournamentData['start_date'],
                        'end_date' => $tournamentData['end_date'],
                        'location' => $tournamentData['location'] ?? null,
                        'organizer' => $tournamentData['organizer'] ?? null,
                        'type' => $tournamentData['type'],
                        'status' => $tournamentData['status'],
                        'settings' => json_encode([
                            'match_duration' => $tournamentData['match_duration'],
                            'half_time' => $tournamentData['half_time'],
                            'extra_time' => $tournamentData['extra_time'] ?? 10,
                            'points_win' => $tournamentData['points_win'],
                            'points_draw' => $tournamentData['points_draw'],
                            'points_loss' => $tournamentData['points_loss'],
                            'points_no_show' => $tournamentData['points_no_show'] ?? -1,
                            'max_substitutes' => $tournamentData['max_substitutes'],
                            'yellow_card_suspension' => $tournamentData['yellow_card_suspension'] ?? 3,
                            'matches_per_day' => $tournamentData['matches_per_day'],
                            'match_interval' => $tournamentData['match_interval'],
                            'match_time_slots' => $tournamentData['match_time_slots'] ?? '14:00,16:00,18:00,20:00',
                            'allow_draw' => (bool) ($tournamentData['allow_draw'] ?? true),
                            'extra_time_enabled' => (bool) ($tournamentData['extra_time_enabled'] ?? true),
                            'penalty_shootout' => (bool) ($tournamentData['penalty_shootout'] ?? true),
                            'var_enabled' => (bool) ($tournamentData['var_enabled'] ?? false),
                        ]),
                    ]);

                    // Attach teams
                    if (isset($tournamentData['teams'])) {
                        $tournament->teams()->attach($tournamentData['teams']);
                    }

                    // Clear session
                    Session::forget('tournament_data');

                    return redirect()->route('admin.tournaments.index')
                        ->with('success', 'Tournament created successfully!');

                } catch (\Exception $e) {
                    return redirect()->back()
                        ->with('error', 'Error creating tournament: ' . $e->getMessage());
                }
        }
    }
}