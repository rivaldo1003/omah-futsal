<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Standing;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TournamentController extends Controller
{
    // Create tournament - redirect to step 1
    public function create()
    {
        return redirect()->route('admin.tournaments.create.step', ['step' => 1]);
    }

    public function standings(Tournament $tournament)
    {
        return $this->showStandings($tournament->id);
    }

    public function showStandings($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        // Debug 1: Cek tournament
        \Log::info('Tournament ID: '.$tournament->id.', Name: '.$tournament->name);

        // Ambil standings yang sudah ada (tim yang sudah bertanding)
        $standings = Standing::where('tournament_id', $tournamentId)
            ->with('team')
            ->get();

        // Debug 2: Cek standings
        \Log::info('Standings count: '.$standings->count());

        // AMBIL SEMUA TIM YANG TERDAFTAR DI TOURNAMENT
        $allRegisteredTeams = DB::table('team_tournament')
            ->where('tournament_id', $tournamentId)
            ->join('teams', 'team_tournament.team_id', '=', 'teams.id')
            ->select(
                'teams.id',
                'teams.name',
                'teams.coach',
                'teams.primary_color',
                'team_tournament.group_name',
                'team_tournament.seed'
            )
            ->get();

        // Debug 3: Cek data dari team_tournament
        \Log::info('Registered teams query result:', [
            'count' => $allRegisteredTeams->count(),
            'data' => $allRegisteredTeams->toArray(),
        ]);

        // Tambahkan ini untuk langsung lihat di browser:
        if ($allRegisteredTeams->isEmpty()) {
            dd(
                'NO TEAMS FOUND in team_tournament for tournament ID: '.$tournamentId,
                'Tournament: ',
                $tournament
            );
        }

        $groupedTeams = $allRegisteredTeams->groupBy('group_name');

        // Debug 4: Cek grouped data
        \Log::info('Grouped teams:', $groupedTeams->toArray());

        $allTeamsByGroup = [];

        foreach ($groupedTeams as $groupName => $teams) {
            foreach ($teams as $team) {
                // Cari apakah tim ini sudah memiliki standings
                $standing = $standings->firstWhere('team_id', $team->id);

                $allTeamsByGroup[$groupName][] = (object) [
                    'team' => (object) [
                        'id' => $team->id,
                        'name' => $team->name,
                        'coach' => $team->coach,
                        'primary_color' => $team->primary_color,
                    ],
                    'played' => $standing->played ?? 0,
                    'won' => $standing->won ?? 0,
                    'drawn' => $standing->drawn ?? 0,
                    'lost' => $standing->lost ?? 0,
                    'goals_for' => $standing->goals_for ?? 0,
                    'goals_against' => $standing->goals_against ?? 0,
                    'goal_difference' => $standing->goal_difference ?? 0,
                    'points' => $standing->points ?? 0,
                    'group' => $groupName,
                    'seed' => $team->seed,
                ];
            }
        }

        // Debug 5: Cek final data
        \Log::info('Final allTeamsByGroup:', $allTeamsByGroup);

        // Tambahkan ini untuk lihat di browser:
        dd('Final data for view:', [
            'tournament' => $tournament,
            'allTeamsByGroup' => $allTeamsByGroup,
            'groupedTeams' => $groupedTeams->toArray(),
        ]);

        return view('standings.index', [
            'selectedTournament' => $tournament,
            'allTeamsInGroupByGroup' => $allTeamsByGroup,
        ]);
    }

    // Create tournament step by step
    public function createStep(Request $request, $step = 1)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }

        // Get all teams
        $teams = Team::orderBy('name')->get(['id', 'name', 'logo', 'coach_name']);

        // Get tournament data from session
        $tournamentData = session()->get('tournament_data', []);

        // Jika step tidak valid, redirect ke step 1
        if ($step < 1 || $step > 5) {
            return redirect()->route('admin.tournaments.create.step', ['step' => 1]);
        }

        // Pastikan $step adalah integer
        $currentStep = (int) $step;

        // Untuk debugging, tambahkan log
        \Log::info('Tournament create step:', [
            'step' => $step,
            'currentStep' => $currentStep,
            'session_data' => ! empty($tournamentData),
        ]);

        // Kirim data ke view dengan nama variabel yang konsisten
        return view('admin.tournaments.create', [
            'teams' => $teams,
            'currentStep' => $currentStep,
            'step' => $currentStep,
            'tournamentData' => $tournamentData,
        ]);
    }

    // Store tournament step by step
    public function storeStep(Request $request, $step)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }

        // Validasi step
        $step = (int) $step;
        if ($step < 1 || $step > 5) {
            return redirect()->route('admin.tournaments.create.step', ['step' => 1])
                ->with('error', 'Invalid step.');
        }

        // Get existing data from session
        $tournamentData = session()->get('tournament_data', []);

        // Log untuk debugging
        \Log::info('Store step data:', [
            'step' => $step,
            'has_session_data' => ! empty($tournamentData),
            'request_data' => $request->except(['_token']),
        ]);

        switch ($step) {
            case 1:
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'slug' => 'nullable|string|max:255|unique:tournaments,slug',
                    'description' => 'nullable|string',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'location' => 'nullable|string|max:255',
                    'organizer' => 'nullable|string|max:255',
                    'type' => 'required|in:league,knockout,group_knockout',
                    'status' => 'required|in:upcoming,ongoing,completed,cancelled',
                    'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                    // Hanya untuk group_knockout
                    'groups_count' => 'nullable|integer|min:1|max:8',
                    'teams_per_group' => 'nullable|integer|min:2|max:10',
                    'qualify_per_group' => 'nullable|integer|min:1|max:4',
                    // Hanya untuk league
                    'league_rounds' => 'nullable|integer|min:1|max:2',
                    'league_standings_type' => 'nullable|in:total_points,head_to_head,goal_difference',
                    'league_allow_draw' => 'nullable|boolean',
                    // Hanya untuk knockout
                    'knockout_format' => 'nullable|in:single_elimination,double_elimination',
                    'knockout_teams' => 'nullable|integer|min:2|max:32',
                    'knockout_seeding' => 'nullable|in:random,ranked,manual',
                    'knockout_byes' => 'nullable|integer|min:0|max:16',
                    'knockout_third_place' => 'nullable|boolean',
                ]);

                // **PERBAIKAN: Validasi berdasarkan tipe turnamen yang benar**
                if ($validated['type'] === 'group_knockout') {
                    $request->validate([
                        'groups_count' => 'required|integer|min:1|max:8',
                        'teams_per_group' => 'required|integer|min:2|max:10',
                        'qualify_per_group' => 'required|integer|min:1|max:4',
                    ], [
                        'groups_count.required' => 'Number of groups is required for Group + Knockout tournament',
                        'teams_per_group.required' => 'Teams per group is required for Group + Knockout tournament',
                        'qualify_per_group.required' => 'Qualify per group is required for Group + Knockout tournament',
                    ]);
                }

                if ($validated['type'] === 'league') {
                    // **LEAGUE TIDAK PERNAH MEMILIKI GRUP**
                    // League hanya single round-robin, tidak ada groups_count
                    // Hapus groups_count dari data jika ada
                    unset($validated['groups_count']);
                    unset($validated['teams_per_group']);
                    unset($validated['qualify_per_group']);
                }

                if ($validated['type'] === 'knockout') {
                    $request->validate([
                        'knockout_teams' => 'required|integer|min:2|max:32',
                    ], [
                        'knockout_teams.required' => 'Number of knockout teams is required',
                    ]);

                    // Hapus field grup dari knockout
                    unset($validated['groups_count']);
                    unset($validated['teams_per_group']);
                    unset($validated['qualify_per_group']);
                }

                // Handle file upload untuk logo
                if ($request->hasFile('logo')) {
                    $logoFile = $request->file('logo');
                    $logoName = 'logo_'.Str::random(10).'_'.time().'.'.$logoFile->getClientOriginalExtension();
                    $logoPath = $logoFile->storeAs('tournament-logos', $logoName, 'public');
                    $validated['logo'] = $logoPath;
                }

                // Handle file upload untuk banner
                if ($request->hasFile('banner')) {
                    $bannerFile = $request->file('banner');
                    $bannerName = 'banner_'.Str::random(10).'_'.time().'.'.$bannerFile->getClientOriginalExtension();
                    $bannerPath = $bannerFile->storeAs('tournament-banners', $bannerName, 'public');
                    $validated['banner'] = $bannerPath;
                }

                // Merge validated data
                $tournamentData = array_merge($tournamentData, $validated);
                session()->put('tournament_data', $tournamentData);

                return redirect()->route('admin.tournaments.create.step', ['step' => 2])
                    ->with('success', 'Basic information saved. Please select teams.');

            case 2:
                $validated = $request->validate([
                    'teams' => 'required|array|min:2',
                    'teams.*' => 'exists:teams,id',
                ]);

                $selectedTeamCount = count($validated['teams']);
                $tournamentType = $tournamentData['type'] ?? 'group_knockout';

                // **PERBAIKAN: Validasi jumlah tim berdasarkan tipe turnamen yang benar**
                if ($tournamentType === 'knockout') {
                    // Untuk knockout, jumlah tim harus sesuai dengan knockout_teams
                    $knockoutTeams = $tournamentData['knockout_teams'] ?? 8;

                    if ($selectedTeamCount > $knockoutTeams) {
                        return redirect()->back()
                            ->with('error', "For Knockout tournament, maximum teams is {$knockoutTeams}. You selected {$selectedTeamCount} teams.")
                            ->withInput();
                    }

                    // Warning jika bukan power of 2
                    if (! ($selectedTeamCount >= 2 && ($selectedTeamCount & ($selectedTeamCount - 1)) == 0)) {
                        session()->flash('warning',
                            'Knockout tournament works best with power of 2 teams (2, 4, 8, 16, 32). '.
                            'Byes will be added for missing teams.'
                        );
                    }
                } elseif ($tournamentType === 'group_knockout') {
                    $groupsCount = $tournamentData['groups_count'] ?? 2;
                    $teamsPerGroup = $tournamentData['teams_per_group'] ?? 4;

                    $minTeams = $groupsCount * 2; // Minimal 2 tim per grup
                    $maxTeams = $groupsCount * $teamsPerGroup;

                    if ($selectedTeamCount < $minTeams) {
                        return redirect()->back()
                            ->with('error', "For {$groupsCount} groups, you need at least {$minTeams} teams (2 teams per group)")
                            ->withInput();
                    }

                    if ($selectedTeamCount > $maxTeams) {
                        return redirect()->back()
                            ->with('error', "Maximum teams for {$groupsCount} groups with {$teamsPerGroup} teams per group is {$maxTeams}")
                            ->withInput();
                    }
                } elseif ($tournamentType === 'league') {
                    // **LEAGUE: Minimal 2 tim, tidak ada batasan maksimal grup**
                    if ($selectedTeamCount < 2) {
                        return redirect()->back()
                            ->with('error', 'League tournament needs at least 2 teams')
                            ->withInput();
                    }
                }

                // Merge validated data
                $tournamentData = array_merge($tournamentData, $validated);
                session()->put('tournament_data', $tournamentData);

                // **PERBAIKAN: Tentukan step berikutnya berdasarkan tipe**
                $nextStep = 3; // Default

                if ($tournamentType === 'knockout' || $tournamentType === 'league') {
                    // Knockout dan League skip step 3 (group assignment)
                    $nextStep = 4;
                }

                return redirect()->route('admin.tournaments.create.step', ['step' => $nextStep])
                    ->with('success', 'Teams selected. '.
                        ($nextStep == 3 ? 'Please assign teams to groups.' : 'Please configure match rules.'));

            case 3:
                $tournamentType = $tournamentData['type'] ?? 'group_knockout';

                // **PERBAIKAN: Skip step 3 untuk knockout dan league**
                if ($tournamentType === 'knockout' || $tournamentType === 'league') {
                    // Redirect ke step 4
                    return redirect()->route('admin.tournaments.create.step', ['step' => 4])
                        ->with('success', ucfirst($tournamentType).' tournament setup. Please configure match rules.');
                }

                // **Hanya Group + Knockout yang memerlukan group assignment**
                if ($tournamentType === 'group_knockout') {
                    $groupAssignments = $request->input('group_assignments', '[]');
                    $groupAssignments = json_decode($groupAssignments, true);

                    if (! is_array($groupAssignments)) {
                        return redirect()->back()
                            ->with('error', 'Invalid group assignments data.')
                            ->withInput();
                    }

                    // Validasi apakah semua tim sudah diassign
                    $selectedTeams = $tournamentData['teams'] ?? [];
                    $assignedTeamIds = array_column($groupAssignments, 'team_id');
                    $unassignedTeams = array_diff($selectedTeams, $assignedTeamIds);

                    if (! empty($unassignedTeams)) {
                        return redirect()->back()
                            ->with('error', 'Please assign all teams to groups. There are still unassigned teams.')
                            ->withInput();
                    }

                    $tournamentData['group_assignments'] = $groupAssignments;
                    session()->put('tournament_data', $tournamentData);

                    return redirect()->route('admin.tournaments.create.step', ['step' => 4])
                        ->with('success', 'Groups assigned. Please configure match rules.');
                }

                // Fallback untuk tipe lain
                $tournamentData['group_assignments'] = [];
                session()->put('tournament_data', $tournamentData);

                return redirect()->route('admin.tournaments.create.step', ['step' => 4])
                    ->with('success', 'Proceeding to match rules.');

            case 4:
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

                // Merge validated data
                $tournamentData = array_merge($tournamentData, $validated);
                session()->put('tournament_data', $tournamentData);

                return redirect()->route('admin.tournaments.create.step', ['step' => 5])
                    ->with('success', 'Match rules configured. Please review and confirm.');

            case 5:
                // Final validation
                $request->validate([
                    'confirmTournament' => 'required|accepted',
                ]);

                $tournamentData = session()->get('tournament_data', []);
                $tournamentType = $tournamentData['type'];

                // Check if we have all required data
                $requiredFields = ['name', 'start_date', 'end_date', 'type', 'teams'];
                $missingFields = [];

                foreach ($requiredFields as $field) {
                    if (empty($tournamentData[$field])) {
                        $missingFields[] = $field;
                    }
                }

                // **PERBAIKAN: Validasi berdasarkan tipe yang benar**
                if ($tournamentType === 'group_knockout') {
                    if (empty($tournamentData['groups_count'])) {
                        $missingFields[] = 'groups_count';
                    }
                    if (empty($tournamentData['teams_per_group'])) {
                        $missingFields[] = 'teams_per_group';
                    }
                    if (empty($tournamentData['qualify_per_group'])) {
                        $missingFields[] = 'qualify_per_group';
                    }
                    if (empty($tournamentData['group_assignments'])) {
                        $missingFields[] = 'group_assignments';
                    }
                }

                if ($tournamentType === 'knockout') {
                    if (empty($tournamentData['knockout_teams'])) {
                        $missingFields[] = 'knockout_teams';
                    }
                }

                if (! empty($missingFields)) {
                    return redirect()->route('admin.tournaments.create.step', ['step' => 1])
                        ->with('error', 'Missing required fields: '.implode(', ', $missingFields));
                }

                try {
                    DB::beginTransaction();

                    // Auto-generate slug jika kosong
                    if (empty($tournamentData['slug'])) {
                        $tournamentData['slug'] = Str::slug($tournamentData['name']).'-'.time();
                    }

                    // Prepare settings as JSON
                    $settings = [
                        'match_duration' => $tournamentData['match_duration'] ?? 40,
                        'half_time' => $tournamentData['half_time'] ?? 10,
                        'extra_time' => $tournamentData['extra_time'] ?? 10,
                        'points_win' => $tournamentData['points_win'] ?? 3,
                        'points_draw' => $tournamentData['points_draw'] ?? 1,
                        'points_loss' => $tournamentData['points_loss'] ?? 0,
                        'points_no_show' => $tournamentData['points_no_show'] ?? -1,
                        'max_substitutes' => $tournamentData['max_substitutes'] ?? 5,
                        'yellow_card_suspension' => $tournamentData['yellow_card_suspension'] ?? 3,
                        'matches_per_day' => $tournamentData['matches_per_day'] ?? 4,
                        'match_interval' => $tournamentData['match_interval'] ?? 30,
                        'match_time_slots' => $tournamentData['match_time_slots'] ?? '14:00,16:00,18:00,20:00',
                        'allow_draw' => (bool) ($tournamentData['allow_draw'] ?? true),
                        'extra_time_enabled' => (bool) ($tournamentData['extra_time_enabled'] ?? true),
                        'penalty_shootout' => (bool) ($tournamentData['penalty_shootout'] ?? true),
                        'var_enabled' => (bool) ($tournamentData['var_enabled'] ?? false),
                    ];

                    // **PERBAIKAN: Tambah setting khusus berdasarkan tipe yang benar**
                    if ($tournamentType === 'league') {
                        $settings['league_rounds'] = $tournamentData['league_rounds'] ?? 1;
                        $settings['league_standings_type'] = $tournamentData['league_standings_type'] ?? 'total_points';
                        $settings['league_allow_draw'] = (bool) ($tournamentData['league_allow_draw'] ?? true);
                        // League tidak punya groups_count di settings
                    }

                    if ($tournamentType === 'knockout') {
                        $settings['knockout_format'] = $tournamentData['knockout_format'] ?? 'single_elimination';
                        $settings['knockout_teams'] = $tournamentData['knockout_teams'] ?? 8;
                        $settings['knockout_seeding'] = $tournamentData['knockout_seeding'] ?? 'random';
                        $settings['knockout_byes'] = $tournamentData['knockout_byes'] ?? 0;
                        $settings['knockout_third_place'] = (bool) ($tournamentData['knockout_third_place'] ?? false);
                    }

                    // Create tournament
                    $tournament = Tournament::create([
                        'name' => $tournamentData['name'],
                        'slug' => $tournamentData['slug'],
                        'description' => $tournamentData['description'] ?? null,
                        'start_date' => $tournamentData['start_date'],
                        'end_date' => $tournamentData['end_date'],
                        'location' => $tournamentData['location'] ?? null,
                        'organizer' => $tournamentData['organizer'] ?? null,
                        'logo' => $tournamentData['logo'] ?? null,
                        'banner' => $tournamentData['banner'] ?? null,
                        'type' => $tournamentData['type'],
                        'status' => $tournamentData['status'] ?? 'upcoming',
                        // **PERBAIKAN: Hanya group_knockout yang punya groups_count**
                        'groups_count' => $tournamentType === 'group_knockout' ? $tournamentData['groups_count'] : null,
                        'teams_per_group' => $tournamentType === 'group_knockout' ? $tournamentData['teams_per_group'] : null,
                        'qualify_per_group' => $tournamentType === 'group_knockout' ? $tournamentData['qualify_per_group'] : null,
                        'settings' => json_encode($settings),
                        'created_by' => auth()->id(),
                    ]);

                    // Get selected teams
                    $selectedTeams = $tournamentData['teams'];

                    // **PERBAIKAN: Handle team attachment berdasarkan tipe turnamen yang benar**
                    if ($tournamentType === 'knockout') {
                        // Untuk knockout, attach tim dengan seed berdasarkan urutan
                        foreach ($selectedTeams as $index => $teamId) {
                            $tournament->teams()->attach($teamId, [
                                'group_name' => null, // Knockout tidak perlu group
                                'seed' => $index + 1,
                            ]);
                        }
                    } elseif ($tournamentType === 'league') {
                        // **LEAGUE: Tanpa grup, hanya single round-robin**
                        shuffle($selectedTeams);

                        foreach ($selectedTeams as $index => $teamId) {
                            $tournament->teams()->attach($teamId, [
                                'group_name' => null, // League TANPA grup
                                'seed' => $index + 1,
                            ]);
                        }
                    } elseif ($tournamentType === 'group_knockout') {
                        // Untuk group+knockout, wajib ada assignments
                        if (empty($tournamentData['group_assignments'])) {
                            throw new \Exception('Group assignments required for Group + Knockout tournament');
                        }

                        foreach ($tournamentData['group_assignments'] as $assignment) {
                            if (isset($assignment['team_id'], $assignment['group'], $assignment['seed'])) {
                                $tournament->teams()->attach($assignment['team_id'], [
                                    'group_name' => $assignment['group'],
                                    'seed' => $assignment['seed'],
                                ]);
                            }
                        }
                    } else {
                        // Fallback untuk tipe lain
                        foreach ($selectedTeams as $index => $teamId) {
                            $tournament->teams()->attach($teamId, [
                                'group_name' => null,
                                'seed' => $index + 1,
                            ]);
                        }
                    }

                    DB::commit();

                    // Clear session data
                    session()->forget('tournament_data');

                    // Redirect dengan pesan yang sesuai
                    $redirectMessage = 'Tournament "'.$tournament->name.'" created successfully!';

                    if ($tournamentType === 'knockout') {
                        $redirectMessage .= ' You can now generate knockout bracket.';
                    } elseif ($tournamentType === 'league') {
                        $redirectMessage .= ' You can now generate league schedule.';
                    } else {
                        $redirectMessage .= ' You can now generate group matches.';
                    }

                    return redirect()->route('admin.tournaments.index')
                        ->with('success', $redirectMessage)
                        ->with('tournament_id', $tournament->id);
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error('Tournament creation failed: '.$e->getMessage(), [
                        'exception' => $e,
                        'tournament_data' => $tournamentData,
                    ]);

                    return redirect()->route('admin.tournaments.create.step', ['step' => 1])
                        ->with('error', 'Error creating tournament: '.$e->getMessage());
                }
        }
    }

    // Display all tournaments
    public function index(Request $request)
    {
        $query = Tournament::query()->withCount(['teams', 'matches']);

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('organizer', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Type filter
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('end_date', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSort = ['name', 'start_date', 'end_date', 'created_at', 'teams_count'];
        if (in_array($sortBy, $allowedSort)) {
            if ($sortBy === 'teams_count') {
                $query->withCount('teams')->orderBy('teams_count', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->latest();
        }

        $tournaments = $query->paginate(10)->withQueryString();

        // Stats for dashboard
        $stats = [
            'total' => Tournament::count(),
            'upcoming' => Tournament::where('status', 'upcoming')->count(),
            'ongoing' => Tournament::where('status', 'ongoing')->count(),
            'completed' => Tournament::where('status', 'completed')->count(),
            'today' => Tournament::whereDate('created_at', today())->count(),
        ];

        return view('admin.tournaments.index', compact('tournaments', 'stats'));
    }

    // Show tournament details
    public function show(Tournament $tournament)
    {
        $tournament->load(['teams', 'matches.homeTeam', 'matches.awayTeam']);

        return view('admin.tournaments.show', compact('tournament'));
    }

    // Edit tournament
    public function edit(Tournament $tournament)
    {
        $teams = Team::orderBy('name')->get(['id', 'name']);
        $selectedTeams = $tournament->teams()->pluck('teams.id')->toArray();

        // Decode settings
        $settings = json_decode($tournament->settings, true) ?? [];

        return view('admin.tournaments.edit', compact('tournament', 'teams', 'selectedTeams', 'settings'));
    }

    // Update tournament
    public function update(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tournaments,slug,'.$tournament->id,
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'type' => 'required|in:league,knockout,group_knockout',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'teams' => 'required|array|min:2',
            'teams.*' => 'exists:teams,id',
            'groups_count' => 'nullable|required_if:type,group_knockout|integer|min:1|max:8',
            'match_duration' => 'required|integer|min:10|max:120',
            'half_time' => 'required|integer|min:5|max:30',
            'points_win' => 'required|integer|min:0|max:10',
            'points_draw' => 'required|integer|min:0|max:5',
        ]);

        try {
            DB::beginTransaction();

            // Handle file upload untuk logo jika ada
            if ($request->hasFile('logo')) {
                // Hapus logo lama jika ada
                if ($tournament->logo && Storage::disk('public')->exists($tournament->logo)) {
                    Storage::disk('public')->delete($tournament->logo);
                }

                $logoFile = $request->file('logo');
                $logoName = 'logo_'.Str::random(10).'_'.time().'.'.$logoFile->getClientOriginalExtension();
                $logoPath = $logoFile->storeAs('tournament-logos', $logoName, 'public');
                $validated['logo'] = $logoPath;
            } else {
                // Jika tidak upload logo baru, pertahankan logo lama
                unset($validated['logo']);
            }

            // Handle file upload untuk banner jika ada
            if ($request->hasFile('banner')) {
                // Hapus banner lama jika ada
                if ($tournament->banner && Storage::disk('public')->exists($tournament->banner)) {
                    Storage::disk('public')->delete($tournament->banner);
                }

                $bannerFile = $request->file('banner');
                $bannerName = 'banner_'.Str::random(10).'_'.time().'.'.$bannerFile->getClientOriginalExtension();
                $bannerPath = $bannerFile->storeAs('tournament-banners', $bannerName, 'public');
                $validated['banner'] = $bannerPath;
            } else {
                // Jika tidak upload banner baru, pertahankan banner lama
                unset($validated['banner']);
            }

            // Prepare settings
            $settings = json_decode($tournament->settings, true) ?? [];
            $settings = array_merge($settings, [
                'match_duration' => $validated['match_duration'],
                'half_time' => $validated['half_time'],
                'points_win' => $validated['points_win'],
                'points_draw' => $validated['points_draw'],
            ]);

            // Update tournament
            $tournament->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'] ?? Str::slug($validated['name']).'-'.time(),
                'description' => $validated['description'] ?? null,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'location' => $validated['location'] ?? null,
                'organizer' => $validated['organizer'] ?? null,
                'logo' => $validated['logo'] ?? $tournament->logo,
                'banner' => $validated['banner'] ?? $tournament->banner,
                'type' => $validated['type'],
                'status' => $validated['status'],
                // **PERBAIKAN: Hanya group_knockout yang punya groups_count**
                'groups_count' => $validated['type'] === 'group_knockout' ? $validated['groups_count'] : null,
                'settings' => json_encode($settings),
            ]);

            // Sync teams (preserve existing group assignments if possible)
            $currentTeams = $tournament->teams()->pluck('teams.id')->toArray();
            $newTeams = $validated['teams'];

            // Remove teams that are no longer selected
            $teamsToRemove = array_diff($currentTeams, $newTeams);
            if (! empty($teamsToRemove)) {
                DB::table('team_tournament')
                    ->where('tournament_id', $tournament->id)
                    ->whereIn('team_id', $teamsToRemove)
                    ->delete();
            }

            // Add new teams
            $teamsToAdd = array_diff($newTeams, $currentTeams);
            if (! empty($teamsToAdd)) {
                foreach ($teamsToAdd as $teamId) {
                    // **PERBAIKAN: Assign group hanya untuk group_knockout**
                    $groupName = null;
                    if ($tournament->type === 'group_knockout') {
                        // Simple group assignment
                        $groups = range('A', 'Z');
                        $groupCount = min($tournament->groups_count ?? 1, count($groups));
                        $groupIndex = array_search($teamId, $newTeams) % $groupCount;
                        $groupName = $groups[$groupIndex];
                    }
                    // League dan Knockout: group_name = null

                    $tournament->teams()->attach($teamId, [
                        'group_name' => $groupName,
                        'seed' => 1, // Default seed
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.tournaments.index')
                ->with('success', 'Tournament "'.$tournament->name.'" updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Tournament update failed: '.$e->getMessage(), [
                'exception' => $e,
                'tournament_id' => $tournament->id,
            ]);

            return redirect()->back()
                ->with('error', 'Error updating tournament: '.$e->getMessage())
                ->withInput();
        }
    }

    // Delete tournament
    public function destroy(Tournament $tournament)
    {
        try {
            // Hapus file logo dan banner jika ada
            if ($tournament->logo && Storage::disk('public')->exists($tournament->logo)) {
                Storage::disk('public')->delete($tournament->logo);
            }

            if ($tournament->banner && Storage::disk('public')->exists($tournament->banner)) {
                Storage::disk('public')->delete($tournament->banner);
            }

            $tournament->delete();

            return redirect()->route('admin.tournaments.index')
                ->with('success', 'Tournament deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting tournament: '.$e->getMessage());
        }
    }

    // Generate schedule for tournament
    public function generateSchedule(Tournament $tournament)
    {
        try {
            DB::beginTransaction();

            // Hapus match yang ada
            $tournament->matches()->delete();

            $teams = $tournament->teams;

            if ($tournament->type === 'league') {
                $matches = $this->generateRoundRobin($tournament, $teams);
            } elseif ($tournament->type === 'knockout') {
                $matches = $this->generateKnockoutBracket($tournament, $teams);
            } elseif ($tournament->type === 'group_knockout') {
                $matches = $this->generateGroupStage($tournament, $teams);
            } else {
                throw new \Exception('Unsupported tournament type');
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Schedule generated successfully! '.count($matches).' matches created.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error generating schedule: '.$e->getMessage());
        }
    }

    private function generateRoundRobin(Tournament $tournament, $teams)
    {
        $matches = [];
        $teamsArray = $teams->toArray();

        // **PERBAIKAN: League tidak punya grup, semua tim dalam satu pool**
        $groups = ['single' => $teams];

        $matchDate = $tournament->start_date;
        $matchesPerDay = $tournament->matches_per_day ?? 4;
        $matchCounter = 0;

        foreach ($groups as $groupName => $groupTeams) {
            $groupTeamsArray = $groupTeams;
            $totalTeams = count($groupTeamsArray);

            if ($totalTeams < 2) {
                continue;
            }

            if ($totalTeams % 2 != 0) {
                array_push($groupTeamsArray, null);
                $totalTeams++;
            }

            $totalRounds = $totalTeams - 1;
            $matchesPerRound = $totalTeams / 2;

            for ($round = 0; $round < $totalRounds; $round++) {
                // Calculate match date based on matches per day
                if ($matchCounter % $matchesPerDay == 0 && $matchCounter > 0) {
                    $matchDate = $matchDate->copy()->addDay();
                }

                for ($match = 0; $match < $matchesPerRound; $match++) {
                    $home = ($round + $match) % ($totalTeams - 1);
                    $away = ($totalTeams - 1 - $match + $round) % ($totalTeams - 1);

                    if ($match == 0) {
                        $away = $totalTeams - 1;
                    }

                    // Skip dummy team
                    if ($groupTeamsArray[$home] && $groupTeamsArray[$away]) {
                        $matchTime = $this->calculateMatchTime($matchCounter % $matchesPerDay);

                        $matches[] = Game::create([
                            'tournament_id' => $tournament->id,
                            'home_team_id' => $groupTeamsArray[$home]['id'],
                            'away_team_id' => $groupTeamsArray[$away]['id'],
                            'round' => $round + 1,
                            'group_name' => null, // **PERBAIKAN: League group_name = null**
                            'match_date' => $matchDate,
                            'match_time' => $matchTime,
                            'status' => 'scheduled',
                            'stage' => 'league', // **PERBAIKAN: Stage = league**
                        ]);

                        $matchCounter++;
                    }
                }
            }
        }

        return $matches;
    }

    private function generateKnockoutBracket(Tournament $tournament, $teams)
    {
        $matches = [];
        $teamsArray = $teams->sortBy('pivot.seed')->values()->toArray();
        $totalTeams = count($teamsArray);

        if (! ($totalTeams > 0 && ($totalTeams & ($totalTeams - 1)) == 0)) {
            throw new \Exception('Knockout tournament requires power of 2 teams (2, 4, 8, 16, 32, 64)');
        }

        $totalRounds = log($totalTeams, 2);
        $matchDate = $tournament->start_date;
        $matchesPerDay = $tournament->matches_per_day ?? 4;
        $matchCounter = 0;

        // Create matches for each round
        for ($round = 1; $round <= $totalRounds; $round++) {
            $matchesInRound = $totalTeams / pow(2, $round);

            // Calculate match date based on round
            if ($round > 1) {
                $matchDate = $matchDate->copy()->addDays(ceil(($round - 1) * 2 / $matchesPerDay));
            }

            for ($match = 0; $match < $matchesInRound; $match++) {
                $matchTime = $this->calculateMatchTime($matchCounter % $matchesPerDay);

                $matchData = [
                    'tournament_id' => $tournament->id,
                    'round' => $round,
                    'match_date' => $matchDate,
                    'match_time' => $matchTime,
                    'status' => 'scheduled',
                    'is_knockout' => true,
                    'knockout_round' => $round,
                    'stage' => 'knockout',
                ];

                // For first round, assign teams
                if ($round == 1) {
                    $team1Index = $match * 2;
                    $team2Index = $team1Index + 1;

                    $matchData['home_team_id'] = $teamsArray[$team1Index]['id'] ?? null;
                    $matchData['away_team_id'] = $teamsArray[$team2Index]['id'] ?? null;
                }

                $matches[] = Game::create($matchData);
                $matchCounter++;
            }
        }

        return $matches;
    }

    private function generateGroupStage(Tournament $tournament, $teams)
    {
        $matches = [];
        $groups = $teams->groupBy('pivot.group_name');

        $matchDate = $tournament->start_date;
        $matchesPerDay = $tournament->matches_per_day ?? 4;
        $matchCounter = 0;

        foreach ($groups as $groupName => $groupTeams) {
            $groupTeamsArray = $groupTeams->toArray();
            $totalTeams = count($groupTeamsArray);

            if ($totalTeams < 2) {
                continue;
            }

            // Round robin within group
            if ($totalTeams % 2 != 0) {
                array_push($groupTeamsArray, null);
                $totalTeams++;
            }

            $totalRounds = $totalTeams - 1;
            $matchesPerRound = $totalTeams / 2;

            for ($round = 0; $round < $totalRounds; $round++) {
                // Calculate match date based on matches per day
                if ($matchCounter % $matchesPerDay == 0 && $matchCounter > 0) {
                    $matchDate = $matchDate->copy()->addDay();
                }

                for ($match = 0; $match < $matchesPerRound; $match++) {
                    $home = ($round + $match) % ($totalTeams - 1);
                    $away = ($totalTeams - 1 - $match + $round) % ($totalTeams - 1);

                    if ($match == 0) {
                        $away = $totalTeams - 1;
                    }

                    if ($groupTeamsArray[$home] && $groupTeamsArray[$away]) {
                        $matchTime = $this->calculateMatchTime($matchCounter % $matchesPerDay);

                        $matches[] = Game::create([
                            'tournament_id' => $tournament->id,
                            'home_team_id' => $groupTeamsArray[$home]['id'],
                            'away_team_id' => $groupTeamsArray[$away]['id'],
                            'round' => $round + 1,
                            'group_name' => $groupName,
                            'match_date' => $matchDate,
                            'match_time' => $matchTime,
                            'status' => 'scheduled',
                            'stage' => 'group',
                        ]);

                        $matchCounter++;
                    }
                }
            }
        }

        return $matches;
    }

    private function calculateMatchTime($matchIndex)
    {
        $timeSlots = ['14:00', '16:00', '18:00', '20:00'];
        $timeSlotIndex = $matchIndex % count($timeSlots);

        return $timeSlots[$timeSlotIndex];
    }

    // Clear session data (for debugging)
    public function clearSession()
    {
        session()->forget('tournament_data');

        return redirect()->route('admin.tournaments.create.step', ['step' => 1])
            ->with('success', 'Session data cleared.');
    }

    // Debug session data
    public function debugSession()
    {
        $tournamentData = session()->get('tournament_data', []);

        return response()->json([
            'session_data' => $tournamentData,
            'session_id' => session()->getId(),
        ]);
    }
}
