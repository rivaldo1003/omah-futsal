<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use App\Models\MatchEvent;
use App\Models\Standing;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    // ==================== PUBLIC ROUTES ====================

    /**
     * Display schedule page for public
     */
    public function schedule(Request $request)
    {
        $perPage = 20;
        $search = $request->input('search');
        $group = $request->input('group');
        $status = $request->input('status');
        $date = $request->input('date');
        $tournamentId = $request->input('tournament_id');

        $query = Game::with(['homeTeam', 'awayTeam', 'tournament'])
            // Urutan khusus: ongoing dulu, lalu upcoming terdekat, baru completed
            ->orderByRaw("
            CASE 
                WHEN status = 'ongoing' THEN 1
                WHEN status = 'upcoming' THEN 2
                WHEN status = 'completed' THEN 3
                ELSE 4
            END
        ")
            // Untuk upcoming: tanggal terdekat dulu (asc)
            // Untuk completed: tanggal terbaru dulu (desc)
            ->orderByRaw("
            CASE 
                WHEN status = 'upcoming' THEN match_date
                ELSE '9999-12-31' -- Untuk non-upcoming, tetap urut berdasarkan status
            END ASC
        ")
            ->orderBy('time_start');

        // Filter by tournament
        if ($tournamentId && $tournamentId !== 'all') {
            $query->where('tournament_id', $tournamentId);
        }

        // Filter by group
        if ($group && $group !== 'all') {
            $query->where('group_name', $group);
        }

        // Filter by status
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by date
        if ($date) {
            $query->whereDate('match_date', $date);
        }

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('homeTeam', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('awayTeam', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        $matches = $query->paginate($perPage);
        $teams = Team::all();
        $tournaments = Tournament::whereIn('status', ['upcoming', 'ongoing'])->get();

        // Group matches by date for better display
        $groupedMatches = $matches->groupBy(function ($item) {
            return $item->match_date->format('Y-m-d');
        });

        return view('games.schedule', compact('matches', 'groupedMatches', 'teams', 'tournaments'));
    }

    /**
     * Display match details for public
     */
    public function show(Game $game)
    {
        $game->load([
            'homeTeam',
            'awayTeam',
            'tournament',
            'events' => function ($query) {
                $query->orderBy('minute')->with('player');
            },
            'events.player.team'
        ]);

        // Get match statistics
        $homeTeamStats = [
            'goals' => $game->home_score,
            'yellow_cards' => $game->events->where('event_type', 'yellow_card')
                ->filter(function ($event) use ($game) {
                    return $event->player->team_id == $game->team_home_id;
                })->count(),
            'red_cards' => $game->events->where('event_type', 'red_card')
                ->filter(function ($event) use ($game) {
                    return $event->player->team_id == $game->team_home_id;
                })->count(),
        ];

        $awayTeamStats = [
            'goals' => $game->away_score,
            'yellow_cards' => $game->events->where('event_type', 'yellow_card')
                ->filter(function ($event) use ($game) {
                    return $event->player->team_id == $game->team_away_id;
                })->count(),
            'red_cards' => $game->events->where('event_type', 'red_card')
                ->filter(function ($event) use ($game) {
                    return $event->player->team_id == $game->team_away_id;
                })->count(),
        ];

        // Group events by type and team
        $homeTeamEvents = $game->events->filter(function ($event) use ($game) {
            return $event->player->team_id == $game->team_home_id;
        })->groupBy('event_type');

        $awayTeamEvents = $game->events->filter(function ($event) use ($game) {
            return $event->player->team_id == $game->team_away_id;
        })->groupBy('event_type');

        return view('games.show', compact(
            'game',
            'homeTeamStats',
            'awayTeamStats',
            'homeTeamEvents',
            'awayTeamEvents'
        ));
    }

    // ==================== ADMIN ROUTES ====================

    /**
     * Display matches management for admin
     */
    public function index(Request $request)
    {
        $perPage = 20;
        $search = $request->input('search');
        $tournamentId = $request->input('tournament_id');
        $status = $request->input('status');
        $group = $request->input('group');

        $query = Game::with(['homeTeam', 'awayTeam', 'tournament'])
            ->orderBy('match_date', 'desc')
            ->orderBy('time_start');

        // Filter by tournament
        if ($tournamentId && $tournamentId !== 'all') {
            $query->where('tournament_id', $tournamentId);
        }

        // Filter by status
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by group
        if ($group && $group !== 'all') {
            $query->where('group_name', $group);
        }

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('homeTeam', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('awayTeam', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        $matches = $query->paginate($perPage);
        $teams = Team::all();
        $tournaments = Tournament::orderBy('name')->get();
        $statuses = ['upcoming', 'ongoing', 'completed', 'postponed'];
        $groups = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        return view('admin.matches.index', compact(
            'matches',
            'teams',
            'tournaments',
            'statuses',
            'groups'
        ));
    }

    /**
     * Show form to create new match
     */
    public function create(Request $request)
    {
        // Ambil tournament aktif (upcoming atau ongoing)
        $tournaments = Tournament::whereIn('status', ['upcoming', 'ongoing'])
            ->orderBy('name')
            ->get();

        // Default pilih tournament pertama atau null
        $selectedTournament = $tournaments->first();
        $tournamentId = $request->input('tournament_id', $selectedTournament?->id);

        if ($tournamentId) {
            // Ambil teams yang terdaftar di tournament ini
            $teams = Team::whereHas('tournaments', function ($query) use ($tournamentId) {
                $query->where('tournament_id', $tournamentId);
            })->orderBy('name')->get();

            // Ambil group yang ada di tournament ini
            $tournament = Tournament::find($tournamentId);
            $groupOptions = [];
            if ($tournament && $tournament->groups_count) {
                // Generate group options berdasarkan groups_count
                $groupOptions = array_map(function ($i) {
                    return chr(64 + $i); // A, B, C, dst
                }, range(1, $tournament->groups_count));
            } else {
                $groupOptions = ['A', 'B']; // Default
            }
        } else {
            $teams = collect();
            $groupOptions = ['A', 'B']; // Default
        }

        $roundTypes = ['group', 'quarterfinal', 'semifinal', 'final', 'third_place'];
        $statusOptions = ['upcoming', 'ongoing', 'completed', 'postponed'];

        return view('admin.matches.create', compact(
            'tournaments',
            'teams',
            'groupOptions',
            'roundTypes',
            'statusOptions',
            'tournamentId'
        ));
    }

    /**
     * Store a newly created match
     */
    public function store(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'match_date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'required',
            'team_home_id' => 'required|exists:teams,id',
            'team_away_id' => 'required|exists:teams,id|different:team_home_id',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:upcoming,ongoing,completed,postponed',
            'round_type' => 'required|in:group,quarterfinal,semifinal,final,third_place',
            'group_name' => 'required_if:round_type,group',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        // Validasi: Cek apakah teams terdaftar di tournament yang dipilih
        $tournament = Tournament::find($request->tournament_id);

        $homeTeamInTournament = $tournament->teams()->where('team_id', $request->team_home_id)->exists();
        $awayTeamInTournament = $tournament->teams()->where('team_id', $request->team_away_id)->exists();

        if (!$homeTeamInTournament) {
            return redirect()->back()
                ->with('error', 'Home team is not registered in selected tournament.')
                ->withInput();
        }

        if (!$awayTeamInTournament) {
            return redirect()->back()
                ->with('error', 'Away team is not registered in selected tournament.')
                ->withInput();
        }

        // Untuk group stage: cek apakah teams dalam group yang sama
        if ($request->round_type === 'group') {
            $homeTeamGroup = $tournament->teams()->where('team_id', $request->team_home_id)->first()->pivot->group_name;
            $awayTeamGroup = $tournament->teams()->where('team_id', $request->team_away_id)->first()->pivot->group_name;

            if ($homeTeamGroup !== $awayTeamGroup) {
                return redirect()->back()
                    ->with('error', 'For group stage matches, teams must be in the same group.')
                    ->withInput();
            }

            // Set group_name otomatis dari group tim
            $request->merge(['group_name' => $homeTeamGroup]);
        }

        try {
            DB::transaction(function () use ($request) {
                $match = Game::create($request->all());

                // Jika match dibuat sebagai completed, update standings
                if ($request->status === 'completed' && $request->round_type === 'group') {
                    $this->updateStandings($match);
                }
            });

            return redirect()->route('admin.matches.index')
                ->with('success', 'Match created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating match: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form to edit match
     */
    public function edit(Game $match)
    {
        $tournaments = Tournament::whereIn('status', ['upcoming', 'ongoing'])->get();
        $teams = Team::whereHas('tournaments', function ($query) use ($match) {
            $query->where('tournament_id', $match->tournament_id);
        })->orderBy('name')->get();

        $groupOptions = [];
        $tournament = $match->tournament;
        if ($tournament && $tournament->groups_count) {
            $groupOptions = array_map(function ($i) {
                return chr(64 + $i);
            }, range(1, $tournament->groups_count));
        } else {
            $groupOptions = ['A', 'B'];
        }

        $roundTypes = ['group', 'quarterfinal', 'semifinal', 'final', 'third_place'];
        $statusOptions = ['upcoming', 'ongoing', 'completed', 'postponed'];

        return view('admin.matches.edit', compact(
            'match',
            'tournaments',
            'teams',
            'groupOptions',
            'roundTypes',
            'statusOptions'
        ));
    }

    /**
     * Update match
     */
    public function update(Request $request, Game $match)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'match_date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'required',
            'team_home_id' => 'required|exists:teams,id',
            'team_away_id' => 'required|exists:teams,id|different:team_home_id',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:upcoming,ongoing,completed,postponed',
            'round_type' => 'required|in:group,quarterfinal,semifinal,final,third_place',
            'group_name' => 'required_if:round_type,group',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request, $match) {
                $oldStatus = $match->status;
                $oldHomeScore = $match->home_score;
                $oldAwayScore = $match->away_score;
                $oldRoundType = $match->round_type;

                // Update match
                $match->update($request->all());

                // Handle standings updates
                if ($oldRoundType === 'group' && $oldStatus === 'completed') {
                    // Revert old standings first
                    $this->revertStandings($match, $oldHomeScore, $oldAwayScore);
                }

                if ($request->round_type === 'group' && $request->status === 'completed') {
                    // Update new standings
                    $this->updateStandings($match);
                }
            });

            return redirect()->route('admin.matches.index')
                ->with('success', 'Match updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating match: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete match
     */
    public function destroy(Game $match)
    {
        try {
            DB::transaction(function () use ($match) {
                // If match was completed, revert standings before deleting
                if ($match->status === 'completed' && $match->round_type === 'group') {
                    $this->revertStandings($match);
                }

                $match->delete();
            });

            return redirect()->route('admin.matches.index')
                ->with('success', 'Match deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting match: ' . $e->getMessage());
        }
    }

    /**
     * Update match score (admin only)
     */
    public function updateScore(Request $request, Game $match)
    {
        $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $match) {
                $oldStatus = $match->status;
                $oldHomeScore = $match->home_score;
                $oldAwayScore = $match->away_score;

                // Update match
                $match->update([
                    'home_score' => $request->home_score,
                    'away_score' => $request->away_score,
                    'status' => 'completed'
                ]);

                // Handle standings
                if ($match->round_type === 'group') {
                    if ($oldStatus === 'completed') {
                        // Revert old standings first
                        $this->revertStandings($match, $oldHomeScore, $oldAwayScore);
                    }

                    // Update standings with new scores
                    $this->updateStandings($match);
                }
            });

            return redirect()->back()
                ->with('success', 'Score updated successfully! Standings have been recalculated.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating score: ' . $e->getMessage());
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Update standings based on match result
     */
    private function updateStandings(Game $match)
    {
        if ($match->round_type !== 'group' || $match->status !== 'completed') {
            return;
        }

        $homeTeamId = $match->team_home_id;
        $awayTeamId = $match->team_away_id;
        $groupName = $match->group_name;
        $tournamentId = $match->tournament_id;

        // Get or create standings for both teams
        $homeStanding = Standing::firstOrCreate(
            [
                'tournament_id' => $tournamentId,
                'team_id' => $homeTeamId,
                'group_name' => $groupName
            ],
            [
                'matches_played' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'points' => 0,
            ]
        );

        $awayStanding = Standing::firstOrCreate(
            [
                'tournament_id' => $tournamentId,
                'team_id' => $awayTeamId,
                'group_name' => $groupName
            ],
            [
                'matches_played' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'points' => 0,
            ]
        );

        // Update matches played
        $homeStanding->increment('matches_played');
        $awayStanding->increment('matches_played');

        // Update goals
        $homeStanding->increment('goals_for', $match->home_score);
        $homeStanding->increment('goals_against', $match->away_score);

        $awayStanding->increment('goals_for', $match->away_score);
        $awayStanding->increment('goals_against', $match->home_score);

        // Update win/draw/loss and points
        if ($match->home_score > $match->away_score) {
            // Home team wins
            $homeStanding->increment('wins');
            $homeStanding->increment('points', 3);
            $awayStanding->increment('losses');
        } elseif ($match->home_score < $match->away_score) {
            // Away team wins
            $awayStanding->increment('wins');
            $awayStanding->increment('points', 3);
            $homeStanding->increment('losses');
        } else {
            // Draw
            $homeStanding->increment('draws');
            $homeStanding->increment('points', 1);
            $awayStanding->increment('draws');
            $awayStanding->increment('points', 1);
        }

        // Update goal difference
        $homeStanding->update([
            'goal_difference' => $homeStanding->goals_for - $homeStanding->goals_against
        ]);

        $awayStanding->update([
            'goal_difference' => $awayStanding->goals_for - $awayStanding->goals_against
        ]);
    }

    /**
     * Revert standings when match is changed or deleted
     */
    private function revertStandings(Game $match, $oldHomeScore = null, $oldAwayScore = null)
    {
        if ($match->round_type !== 'group') {
            return;
        }

        $homeTeamId = $match->team_home_id;
        $awayTeamId = $match->team_away_id;
        $groupName = $match->group_name;
        $tournamentId = $match->tournament_id;

        // Use provided scores or match scores
        $homeScore = $oldHomeScore ?? $match->home_score;
        $awayScore = $oldAwayScore ?? $match->away_score;

        // Cari standings
        $homeStanding = Standing::where('tournament_id', $tournamentId)
            ->where('team_id', $homeTeamId)
            ->where('group_name', $groupName)
            ->first();

        $awayStanding = Standing::where('tournament_id', $tournamentId)
            ->where('team_id', $awayTeamId)
            ->where('group_name', $groupName)
            ->first();

        if (!$homeStanding || !$awayStanding) {
            return;
        }

        // Decrement matches played
        $homeStanding->decrement('matches_played');
        $awayStanding->decrement('matches_played');

        // Revert goals
        $homeStanding->decrement('goals_for', $homeScore);
        $homeStanding->decrement('goals_against', $awayScore);

        $awayStanding->decrement('goals_for', $awayScore);
        $awayStanding->decrement('goals_against', $homeScore);

        // Revert win/draw/loss and points
        if ($homeScore > $awayScore) {
            // Home team win reverted
            $homeStanding->decrement('wins');
            $homeStanding->decrement('points', 3);
            $awayStanding->decrement('losses');
        } elseif ($homeScore < $awayScore) {
            // Away team win reverted
            $awayStanding->decrement('wins');
            $awayStanding->decrement('points', 3);
            $homeStanding->decrement('losses');
        } else {
            // Draw reverted
            $homeStanding->decrement('draws');
            $homeStanding->decrement('points', 1);
            $awayStanding->decrement('draws');
            $awayStanding->decrement('points', 1);
        }

        // Update goal difference
        $homeStanding->update([
            'goal_difference' => $homeStanding->goals_for - $homeStanding->goals_against
        ]);

        $awayStanding->update([
            'goal_difference' => $awayStanding->goals_for - $awayStanding->goals_against
        ]);

        // Delete standings if no matches played
        if ($homeStanding->matches_played <= 0) {
            $homeStanding->delete();
        }
        if ($awayStanding->matches_played <= 0) {
            $awayStanding->delete();
        }
    }

    /**
     * Recalculate all standings for a tournament
     */
    public function recalculateStandings($tournamentId)
    {
        try {
            DB::transaction(function () use ($tournamentId) {
                // Delete all standings for this tournament
                Standing::where('tournament_id', $tournamentId)->delete();

                // Get all completed group matches for this tournament
                $completedMatches = Game::where('tournament_id', $tournamentId)
                    ->where('round_type', 'group')
                    ->where('status', 'completed')
                    ->get();

                // Recalculate standings from scratch
                foreach ($completedMatches as $match) {
                    $this->updateStandings($match);
                }
            });

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Add event (goal, card) to match
     */
    public function addEvent(Request $request, Game $match)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'event_type' => 'required|in:goal,yellow_card,red_card,assist,substitution,penalty',
            'minute' => 'required|integer|min:1|max:120',
            'description' => 'nullable|string',
            'is_own_goal' => 'boolean',
            'is_penalty' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($request, $match) {
                // Create event
                $event = MatchEvent::create([
                    'match_id' => $match->id,
                    'player_id' => $request->player_id,
                    'event_type' => $request->event_type,
                    'minute' => $request->minute,
                    'description' => $request->description,
                    'is_own_goal' => $request->boolean('is_own_goal'),
                    'is_penalty' => $request->boolean('is_penalty'),
                ]);

                // If event is a goal, update player's goal count
                if ($request->event_type === 'goal') {
                    $player = $event->player;
                    $player->increment('goals');

                    // Update match score if needed
                    // Note: This might need adjustment based on your logic
                }

                // If event is yellow/red card, update player's card count
                if ($request->event_type === 'yellow_card') {
                    $event->player->increment('yellow_cards');
                }
                if ($request->event_type === 'red_card') {
                    $event->player->increment('red_cards');
                }
            });

            return redirect()->back()
                ->with('success', 'Event added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error adding event: ' . $e->getMessage());
        }
    }

    /**
     * Delete event from match
     */
    public function deleteEvent(MatchEvent $event)
    {
        try {
            DB::transaction(function () use ($event) {
                // Decrement player stats before deleting
                if ($event->event_type === 'goal') {
                    $event->player->decrement('goals');
                }
                if ($event->event_type === 'yellow_card') {
                    $event->player->decrement('yellow_cards');
                }
                if ($event->event_type === 'red_card') {
                    $event->player->decrement('red_cards');
                }

                $event->delete();
            });

            return redirect()->back()
                ->with('success', 'Event deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting event: ' . $e->getMessage());
        }
    }

    /**
     * Quick match creation from schedule (batch create)
     */
    public function createBatch(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'match_date' => 'required|date',
            'matches' => 'required|array',
            'matches.*.time_start' => 'required',
            'matches.*.time_end' => 'required',
            'matches.*.team_home_id' => 'required|exists:teams,id',
            'matches.*.team_away_id' => 'required|exists:teams,id',
            'matches.*.group_name' => 'required',
            'matches.*.venue' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $tournament = Tournament::find($request->tournament_id);

            foreach ($request->matches as $matchData) {
                // Validate teams are in tournament
                $homeTeamInTournament = $tournament->teams()
                    ->where('team_id', $matchData['team_home_id'])
                    ->exists();
                $awayTeamInTournament = $tournament->teams()
                    ->where('team_id', $matchData['team_away_id'])
                    ->exists();

                if (!$homeTeamInTournament || !$awayTeamInTournament) {
                    throw new \Exception("One or both teams are not in this tournament");
                }

                Game::create([
                    'tournament_id' => $request->tournament_id,
                    'match_date' => $request->match_date,
                    'time_start' => $matchData['time_start'],
                    'time_end' => $matchData['time_end'],
                    'team_home_id' => $matchData['team_home_id'],
                    'team_away_id' => $matchData['team_away_id'],
                    'venue' => $matchData['venue'] ?? 'Main Field',
                    'status' => 'upcoming',
                    'round_type' => 'group',
                    'group_name' => $matchData['group_name'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.matches.index')
                ->with('success', 'Batch matches created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating matches: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get matches by date range (API endpoint)
     */
    public function getByDateRange(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'tournament_id' => 'nullable|exists:tournaments,id',
        ]);

        $query = Game::with(['homeTeam', 'awayTeam', 'tournament'])
            ->whereBetween('match_date', [$request->start_date, $request->end_date])
            ->orderBy('match_date')
            ->orderBy('time_start');

        if ($request->tournament_id) {
            $query->where('tournament_id', $request->tournament_id);
        }

        $matches = $query->get();

        return response()->json($matches);
    }

    /**
     * Get today's matches (API endpoint)
     */
    public function getTodayMatches(Request $request)
    {
        $query = Game::with(['homeTeam', 'awayTeam', 'tournament'])
            ->whereDate('match_date', today())
            ->orderBy('time_start');

        if ($request->tournament_id) {
            $query->where('tournament_id', $request->tournament_id);
        }

        $matches = $query->get();

        return response()->json($matches);
    }

    /**
     * Toggle match status
     */
    public function toggleStatus(Game $match, $status)
    {
        try {
            DB::transaction(function () use ($match, $status) {
                $oldStatus = $match->status;

                $match->update(['status' => $status]);

                // Handle standings for group matches
                if ($match->round_type === 'group') {
                    if ($oldStatus === 'completed' && $status !== 'completed') {
                        $this->revertStandings($match);
                    } elseif ($oldStatus !== 'completed' && $status === 'completed') {
                        $this->updateStandings($match);
                    }
                }
            });

            return redirect()->back()
                ->with('success', 'Match status updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating match status: ' . $e->getMessage());
        }
    }
}