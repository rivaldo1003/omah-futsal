<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Tambahkan ini

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
    /**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:100|unique:teams',
        'short_name' => 'nullable|string|max:10',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        'coach_name' => 'nullable|string|max:255',
        'coach_phone' => 'nullable|string|max:20',
        'coach_email' => 'nullable|email|max:255',
        'head_coach' => 'nullable|string|max:255',
        'assistant_coach' => 'nullable|string|max:255',
        'goalkeeper_coach' => 'nullable|string|max:255',
        'kitman' => 'nullable|string|max:255',
        'primary_color' => 'nullable|string|max:7|regex:/^#[0-9A-F]{6}$/i',
        'secondary_color' => 'nullable|string|max:7|regex:/^#[0-9A-F]{6}$/i',
        'status' => 'required|in:active,inactive,pending',
        'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        'home_venue' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'website' => 'nullable|url|max:255',
        'address' => 'nullable|string',
        'player_ids' => 'nullable|array',
        'player_ids.*' => 'exists:players,id',
        'tournament_ids' => 'nullable|array',
        'tournament_ids.*' => 'exists:tournaments,id',
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

        // Remove player_ids and tournament_ids from team creation data
        unset($teamData['player_ids']);
        unset($teamData['tournament_ids']);

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
            if ($match->home_score > $match->away_score) {
                $stats['wins']++;
            } elseif ($match->home_score < $match->away_score) {
                $stats['losses']++;
            } else {
                $stats['draws']++;
            }

            $stats['goals_for'] += $match->home_score;
            $stats['goals_against'] += $match->away_score;
        }

        foreach ($team->awayMatches->where('status', 'completed') as $match) {
            if ($match->away_score > $match->home_score) {
                $stats['wins']++;
            } elseif ($match->away_score < $match->home_score) {
                $stats['losses']++;
            } else {
                $stats['draws']++;
            }

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
    $currentTournaments = $team->tournaments->pluck('id')->toArray();

    // Get statistics for the dashboard
    $totalTeams = Team::count();
    $activeTeams = Team::where('status', 'active')->count();
    $pendingTeams = Team::where('status', 'pending')->count();
    $inactiveTeams = Team::where('status', 'inactive')->count();
    $totalPlayers = Player::count();
    $freePlayers = Player::where('team_id', null)->count();
    $assignedPlayers = Player::whereNotNull('team_id')->count();

    return view('admin.teams.edit', compact(
        'team',
        'tournaments',
        'players',
        'currentPlayers',
        'currentTournaments',
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
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    /**
 * Update the specified resource in storage.
 */
/**
 * Update the specified resource in storage.
 */
/**
 * Update the specified resource in storage.
 */
public function update(Request $request, Team $team)
{
    \Log::info('=== UPDATE WITH PLAYERS START ===');
    
    $validated = $request->validate([
        // Basic info (19 fields)
        'name' => 'required|string|max:100|unique:teams,name,' . $team->id,
        'short_name' => 'nullable|string|max:10',
        'description' => 'nullable|string',
        'status' => 'required|in:active,inactive,pending',
        'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        'coach_name' => 'nullable|string|max:255',
        'coach_phone' => 'nullable|string|max:20',
        'coach_email' => 'nullable|email|max:255',
        'head_coach' => 'nullable|string|max:255',
        'assistant_coach' => 'nullable|string|max:255',
        'goalkeeper_coach' => 'nullable|string|max:255',
        'kitman' => 'nullable|string|max:255',
        'primary_color' => 'nullable|string|max:7|regex:/^#[0-9A-F]{6}$/i',
        'secondary_color' => 'nullable|string|max:7|regex:/^#[0-9A-F]{6}$/i',
        'home_venue' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'website' => 'nullable|url|max:255',
        'address' => 'nullable|string',
        
        // Players
        'player_ids' => 'nullable|array',
        'player_ids.*' => 'exists:players,id',
        
        // Tournaments
        'tournament_ids' => 'nullable|array',
        'tournament_ids.*' => 'exists:tournaments,id',
    ]);
    
    \Log::info('Player IDs count: ' . count($validated['player_ids'] ?? []));
    \Log::info('Tournament IDs count: ' . count($validated['tournament_ids'] ?? []));
    
    DB::beginTransaction();

    try {
        // Handle logo upload jika ada
        if ($request->hasFile('logo')) {
            if ($team->logo && Storage::disk('public')->exists($team->logo)) {
                Storage::disk('public')->delete($team->logo);
            }
            $logoPath = $request->file('logo')->store('teams/logos', 'public');
            $validated['logo'] = $logoPath;
        }
        
        // Simpan array untuk relations
        $player_ids = $validated['player_ids'] ?? [];
        $tournament_ids = $validated['tournament_ids'] ?? [];
        
        // Hapus dari array sebelum update team
        unset($validated['player_ids']);
        unset($validated['tournament_ids']);
        
        // Update team basic info
        $team->update($validated);
        \Log::info('Team basic info updated');
        
        // === LOGIC UPDATE PLAYERS YANG AMAN ===
        // (Tidak pernah set team_id = null karena NOT NULL)
        
        if (is_array($player_ids)) {
            // 1. Player yang SEKARANG ada di tim ini
            $currentPlayerIds = $team->players()->pluck('players.id')->toArray();
            
            // 2. Player yang akan DIPINDAHKAN keluar dari tim ini
            $playersToRemove = array_diff($currentPlayerIds, $player_ids);
            
            if (!empty($playersToRemove)) {
                // Karena team_id NOT NULL, kita perlu pindahkan ke tim lain
                // OPTION 1: Pindahkan ke tim khusus "Free Agents" atau "Default Team"
                // Atau OPTION 2: Biarkan administrator memilih tim tujuan
                // Untuk sekarang, kita lewati dulu - biarkan administrator handle manual
                \Log::warning('Cannot remove players automatically. Team ID cannot be null. Players to remove: ' . implode(',', $playersToRemove));
                
                // Tampilkan warning ke user
                session()->flash('warning', count($playersToRemove) . ' players cannot be removed automatically because every player must have a team. Please move them to another team first.');
            }
            
            // 3. Player yang akan DITAMBAHKAN ke tim ini
            $playersToAdd = array_diff($player_ids, $currentPlayerIds);
            
            if (!empty($playersToAdd)) {
                // Cek dulu apakah player sudah punya tim lain
                $playersWithOtherTeams = Player::whereIn('id', $playersToAdd)
                    ->whereNotNull('team_id')
                    ->where('team_id', '!=', $team->id)
                    ->get();
                
                if ($playersWithOtherTeams->isNotEmpty()) {
                    // Player ini sudah punya tim lain, perlu warning
                    $conflictPlayers = $playersWithOtherTeams->pluck('name')->implode(', ');
                    \Log::warning('Some players already belong to other teams: ' . $conflictPlayers);
                    session()->flash('warning', 'Some players already belong to other teams: ' . $conflictPlayers);
                }
                
                // Update hanya player yang bisa ditambahkan (team_id = null atau team_id = tim ini)
                $playersToActuallyAdd = Player::whereIn('id', $playersToAdd)
                    ->where(function($query) use ($team) {
                        $query->whereNull('team_id')
                              ->orWhere('team_id', $team->id);
                    })
                    ->pluck('id')
                    ->toArray();
                
                if (!empty($playersToActuallyAdd)) {
                    Player::whereIn('id', $playersToActuallyAdd)->update(['team_id' => $team->id]);
                    \Log::info('Players added to team: ' . implode(',', $playersToActuallyAdd));
                }
            }
        }
        
        // === HANDLE TOURNAMENTS ===
        if (is_array($tournament_ids)) {
            $team->tournaments()->sync($tournament_ids);
            \Log::info('Tournaments updated: ' . implode(',', $tournament_ids));
        }
        
        DB::commit();
        \Log::info('=== UPDATE WITH PLAYERS SUCCESS ===');

        return redirect()->route('admin.teams.show', $team)
            ->with('success', 'Team updated successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('UPDATE ERROR: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
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
                ->with('error', 'Error deleting team: '.$e->getMessage());
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
