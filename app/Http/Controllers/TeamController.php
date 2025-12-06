<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; // Tambahkan ini

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::with(['players', 'tournaments'])
            ->orderBy('name')
            ->paginate(15);

        return view('admin.teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tournaments = Tournament::where('status', 'active')->get();
        $players = Player::where('team_id', null)->get();

        // Get statistics for the dashboard
        $totalTeams = Team::count();
        $activeTeams = Team::where('status', 'active')->count();
        $pendingTeams = Team::where('status', 'pending')->count();
        $inactiveTeams = Team::where('status', 'inactive')->count();
        $totalPlayers = Player::count();
        $freePlayers = Player::where('team_id', null)->count();
        $assignedPlayers = Player::whereNotNull('team_id')->count();

        return view('admin.teams.create', compact(
            'tournaments',
            'players',
            'totalTeams',
            'activeTeams',
            'pendingTeams',
            'inactiveTeams',
            'totalPlayers',
            'freePlayers',
            'assignedPlayers'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([ // Ubah ke $request->validate() yang lebih sederhana
            'name' => 'required|string|max:255|unique:teams',
            'short_name' => 'nullable|string|max:4',
            'description' => 'nullable|string',
            'coach_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,pending,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'player_ids' => 'nullable|array',
            'player_ids.*' => 'exists:players,id',
            'tournament_ids' => 'nullable|array',
            'tournament_ids.*' => 'exists:tournaments,id',
            'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        DB::beginTransaction();

        try {
            $teamData = $validated;

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                $logoPath = $logoFile->store('teams/logos', 'public');
                $teamData['logo'] = $logoPath;
            }

            // Create team
            $team = Team::create($teamData);

            // Attach players if selected
            if ($request->filled('player_ids')) {
                Player::whereIn('id', $request->player_ids)->update(['team_id' => $team->id]);
            }

            // Attach tournaments if selected
            if ($request->filled('tournament_ids')) {
                $team->tournaments()->attach($request->tournament_ids);
            }

            DB::commit();

            return redirect()->route('admin.teams.index')
                ->with('success', 'Team created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error creating team: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        $team->load(['players', 'tournaments', 'homeMatches', 'awayMatches']);

        // Get team statistics
        $stats = [
            'total_matches' => $team->homeMatches->count() + $team->awayMatches->count(),
            'wins' => 0,
            'draws' => 0,
            'losses' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
        ];

        // Calculate wins/draws/losses
        foreach ($team->homeMatches->where('status', 'completed') as $match) {
            if ($match->home_score > $match->away_score)
                $stats['wins']++;
            elseif ($match->home_score < $match->away_score)
                $stats['losses']++;
            else
                $stats['draws']++;

            $stats['goals_for'] += $match->home_score;
            $stats['goals_against'] += $match->away_score;
        }

        foreach ($team->awayMatches->where('status', 'completed') as $match) {
            if ($match->away_score > $match->home_score)
                $stats['wins']++;
            elseif ($match->away_score < $match->home_score)
                $stats['losses']++;
            else
                $stats['draws']++;

            $stats['goals_for'] += $match->away_score;
            $stats['goals_against'] += $match->home_score;
        }

        $stats['goal_difference'] = $stats['goals_for'] - $stats['goals_against'];
        $stats['points'] = ($stats['wins'] * 3) + $stats['draws'];

        // Get recent matches
        $recentMatches = $team->homeMatches->merge($team->awayMatches)
            ->sortByDesc('match_date')
            ->take(5);

        // Get top scorers
        $topScorers = $team->players()
            ->orderByDesc('goals')
            ->take(5)
            ->get();

        return view('admin.teams.show', compact('team', 'stats', 'recentMatches', 'topScorers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        $tournaments = Tournament::where('status', 'active')->get();
        $players = Player::where('team_id', null)
            ->orWhere('team_id', $team->id)
            ->get();

        $currentPlayers = $team->players->pluck('id')->toArray();
        $currentTournaments = $team->tournaments->pluck('id')->toArray(); // Tambahkan ini

        return view('admin.teams.edit', compact('team', 'tournaments', 'players', 'currentPlayers', 'currentTournaments'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
            'short_name' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'coach_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'group_name' => 'nullable|string|max:50', // TAMBAHKAN INI
            'contact_email' => 'nullable|email|max:255', // TAMBAHKAN INI
            'contact_phone' => 'nullable|string|max:20', // TAMBAHKAN INI
            'status' => 'required|in:active,inactive,pending',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'logo_url' => 'nullable|url|max:255', // TAMBAHKAN INI untuk handle URL
            'remove_logo' => 'nullable|boolean', // TAMBAHKAN INI
        ]);

        DB::beginTransaction();

        try {
            // Handle logo removal
            if ($request->has('remove_logo') && $request->remove_logo == '1') {
                if ($team->logo && Storage::disk('public')->exists($team->logo)) {
                    Storage::disk('public')->delete($team->logo);
                }
                $validated['logo'] = null;
            } else {
                // Handle logo file upload
                if ($request->hasFile('logo')) {
                    // Delete old logo if exists
                    if ($team->logo && Storage::disk('public')->exists($team->logo)) {
                        Storage::disk('public')->delete($team->logo);
                    }

                    $logoPath = $request->file('logo')->store('teams/logos', 'public');
                    $validated['logo'] = $logoPath;
                }
                // Handle logo URL
                elseif ($request->filled('logo_url')) {
                    $validated['logo'] = $request->logo_url;
                }
            }

            // Clean up data - remove fields that aren't in database
            $teamData = array_intersect_key($validated, array_flip([
                'name',
                'short_name',
                'description',
                'coach_name',
                'city',
                'group_name',
                'contact_email',
                'contact_phone',
                'status',
                'logo'
            ]));

            // Update team
            $team->update($teamData);

            DB::commit();

            return redirect()->route('admin.teams.show', $team)
                ->with('success', 'Team updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating team: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        try {
            // Check if team has matches
            if ($team->homeMatches()->exists() || $team->awayMatches()->exists()) {
                return redirect()->back()
                    ->with('error', 'Cannot delete team that has matches. Delete matches first.');
            }

            // Delete logo if exists
            if ($team->logo && Storage::disk('public')->exists($team->logo)) {
                Storage::disk('public')->delete($team->logo);
            }

            // Remove players from team
            $team->players()->update(['team_id' => null]);

            // Detach tournaments
            $team->tournaments()->detach();

            // Delete team
            $team->delete();

            return redirect()->route('admin.teams.index')
                ->with('success', 'Team deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting team: ' . $e->getMessage());
        }
    }

    /**
     * Remove logo from team
     */
    public function removeLogo(Team $team)
    {
        try {
            if ($team->logo && Storage::disk('public')->exists($team->logo)) {
                Storage::disk('public')->delete($team->logo);
                $team->update(['logo' => null]);

                return response()->json(['success' => true, 'message' => 'Logo removed successfully']);
            }

            return response()->json(['success' => false, 'message' => 'Logo not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error removing logo'], 500);
        }
    }

    /**
     * Show trashed teams
     */
    public function trashed()
    {
        $teams = Team::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('admin.teams.trashed', compact('teams'));
    }

    /**
     * Restore trashed team
     */
    public function restore($id)
    {
        $team = Team::onlyTrashed()->findOrFail($id);
        $team->restore();

        return redirect()->route('admin.teams.trashed')
            ->with('success', 'Team restored successfully!');
    }

    /**
     * Force delete team
     */
    public function forceDelete($id)
    {
        $team = Team::onlyTrashed()->findOrFail($id);

        // Delete logo if exists
        if ($team->logo && Storage::disk('public')->exists($team->logo)) {
            Storage::disk('public')->delete($team->logo);
        }

        $team->forceDelete();

        return redirect()->route('admin.teams.trashed')
            ->with('success', 'Team permanently deleted!');
    }

    /**
     * Import teams from CSV/Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls',
        ]);

        // Implement import logic here
        // You can use Laravel Excel package or manual CSV parsing

        return redirect()->route('admin.teams.index')
            ->with('success', 'Teams imported successfully!');
    }

    /**
     * Export teams
     */
    public function export()
    {
        // Implement export logic here
        // You can use Laravel Excel package

        return response()->download('path/to/exported/file.csv');
    }

    /**
     * Create player for team
     */
    public function createPlayer(Team $team)
    {
        return view('admin.teams.create-player', compact('team'));
    }

    /**
     * Store player for team
     */
    public function storePlayer(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:50',
            'jersey_number' => 'required|integer|min:1|max:99',
            'date_of_birth' => 'nullable|date',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:30|max:150',
            'nationality' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['team_id'] = $team->id;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('players/photos', 'public');
            $validated['photo'] = $photoPath;
        }

        Player::create($validated);

        return redirect()->route('admin.teams.show', $team)
            ->with('success', 'Player added to team successfully!');
    }

    /**
     * Get teams for select dropdown (API)
     */
    public function selectOptions(Request $request)
    {
        $teams = Team::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'short_name', 'logo']);

        return response()->json($teams);
    }

    /**
     * Get teams by tournament (API)
     */
    public function byTournament(Tournament $tournament)
    {
        $teams = $tournament->teams()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return response()->json($teams);
    }
}