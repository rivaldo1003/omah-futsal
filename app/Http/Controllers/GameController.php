<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\MatchEvent;
use App\Models\Standing;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            ->orderByRaw("
            CASE 
                WHEN status = 'ongoing' THEN 1
                WHEN status = 'upcoming' THEN 2
                WHEN status = 'completed' THEN 3
                ELSE 4
            END
        ")
            ->orderByRaw("
            CASE 
                WHEN status = 'upcoming' THEN match_date
                ELSE '9999-12-31'
            END ASC
        ")
            ->orderBy('time_start');

        if ($tournamentId && $tournamentId !== 'all') {
            $query->where('tournament_id', $tournamentId);
        }

        if ($group && $group !== 'all') {
            $query->where('group_name', $group);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($date) {
            $query->whereDate('match_date', $date);
        }

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
            'events.player.team',
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

        if ($tournamentId && $tournamentId !== 'all') {
            $query->where('tournament_id', $tournamentId);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($group && $group !== 'all') {
            $query->where('group_name', $group);
        }

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
        $tournaments = Tournament::whereIn('status', ['upcoming', 'ongoing'])
            ->orderBy('name')
            ->get();

        $selectedTournament = $tournaments->first();
        $tournamentId = $request->input('tournament_id', $selectedTournament?->id);

        if ($tournamentId) {
            $teams = Team::whereHas('tournaments', function ($query) use ($tournamentId) {
                $query->where('tournament_id', $tournamentId);
            })->orderBy('name')->get();

            $tournament = Tournament::find($tournamentId);
            $tournamentType = $tournament->type;
            $groupOptions = [];
            
            // Hanya tampilkan group untuk group_knockout
            if ($tournamentType === 'group_knockout' && $tournament->groups_count) {
                $groupOptions = array_map(function ($i) {
                    return chr(64 + $i);
                }, range(1, $tournament->groups_count));
            } else {
                $groupOptions = []; // League dan knockout TIDAK punya group options
            }
            
            $tournamentSettings = json_decode($tournament->settings, true) ?? [];
        } else {
            $teams = collect();
            $groupOptions = [];
            $tournamentType = null;
            $tournamentSettings = [];
        }

        $roundTypes = ['group', 'quarterfinal', 'semifinal', 'final', 'third_place', 'league', 'round_of_16', 'round_of_32'];
        $statusOptions = ['upcoming', 'ongoing', 'completed', 'postponed'];

        return view('admin.matches.create', compact(
            'tournaments',
            'teams',
            'groupOptions',
            'roundTypes',
            'statusOptions',
            'tournamentId',
            'tournamentType',
            'tournamentSettings'
        ));
    }

    /**
     * Store a newly created match - PERBAIKAN UTAMA
     */
    public function store(Request $request)
    {
        // Cari tournament terlebih dahulu
        $tournament = Tournament::find($request->tournament_id);
        
        if (!$tournament) {
            return redirect()->back()
                ->with('error', 'Tournament not found.')
                ->withInput();
        }
        
        $tournamentType = $tournament->type;
        
        // ===== PERBAIKAN UTAMA: Validasi dinamis berdasarkan tournament type =====
        $rules = [
            'tournament_id' => 'required|exists:tournaments,id',
            'match_date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'required',
            'team_home_id' => 'required|exists:teams,id',
            'team_away_id' => 'required|exists:teams,id|different:team_home_id',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:upcoming,ongoing,completed,postponed',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'round' => 'nullable|integer|min:1',
            'stage' => 'nullable|in:group,knockout,league,qualification',
        ];
        
        // PERBAIKAN: Tentukan validasi round_type berdasarkan tournament type
        if ($tournamentType === 'league') {
            // Untuk league: round_type harus 'league' dan group_name tidak perlu
            $rules['round_type'] = 'required|in:league';
            $rules['group_name'] = 'nullable|string|max:1';
            
            // Force set round_type ke 'league'
            $request->merge(['round_type' => 'league']);
        } elseif ($tournamentType === 'knockout') {
            // Untuk knockout: round_type sesuai dengan format knockout
            $rules['round_type'] = 'required|in:quarterfinal,semifinal,final,third_place,round_of_16,round_of_32';
            $rules['group_name'] = 'nullable|string|max:1';
        } elseif ($tournamentType === 'group_knockout') {
            // Untuk group_knockout: round_type bisa group atau knockout
            $rules['round_type'] = 'required|in:group,quarterfinal,semifinal,final,third_place';
            
            // PERBAIKAN: Group name hanya required untuk round_type group
            if ($request->round_type === 'group') {
                $rules['group_name'] = 'required|string|max:1';
            } else {
                $rules['group_name'] = 'nullable|string|max:1';
            }
        }
        
        $request->validate($rules);

        // ===== PERBAIKAN: Auto-set values berdasarkan tournament type =====
        if ($tournamentType === 'league') {
            // Untuk league: group_name harus null dan stage harus 'league'
            $request->merge([
                'group_name' => null,
                'stage' => 'league'
            ]);
        } elseif ($tournamentType === 'knockout') {
            // Untuk knockout: group_name harus null
            $request->merge(['group_name' => null]);
        }

        // ===== PERBAIKAN: Skip validasi compatibility untuk league =====
        // Karena sudah kita force set round_type ke 'league'
        if ($tournamentType !== 'league') {
            $typeValidation = $this->validateTournamentTypeCompatibility($tournamentType, $request->round_type);
            if (!$typeValidation['valid']) {
                return redirect()->back()
                    ->with('error', $typeValidation['message'])
                    ->withInput();
            }
        }

        // ===== Validasi tim di tournament =====
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

        // ===== Validasi group stage HANYA untuk group_knockout =====
        if ($request->round_type === 'group' && $tournamentType === 'group_knockout') {
            $homeTeamGroup = $tournament->teams()->where('team_id', $request->team_home_id)->first()->pivot->group_name ?? null;
            $awayTeamGroup = $tournament->teams()->where('team_id', $request->team_away_id)->first()->pivot->group_name ?? null;

            if ($homeTeamGroup && $awayTeamGroup && $homeTeamGroup !== $awayTeamGroup) {
                return redirect()->back()
                    ->with('error', 'For group stage matches, teams must be in the same group.')
                    ->withInput();
            }

            // Auto-set group_name dari tim
            if ($homeTeamGroup) {
                $request->merge(['group_name' => $homeTeamGroup]);
            }
        }

        try {
            DB::transaction(function () use ($request, $tournament) {
                $match = Game::create($request->all());

                if ($request->status === 'completed') {
                    $this->updateMatchResults($match, $tournament);
                }
            });

            return redirect()->route('admin.matches.index')
                ->with('success', 'Match created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating match: '.$e->getMessage())
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
        
        // PERBAIKAN: Hanya tampilkan group options untuk group_knockout
        if ($tournament && $tournament->type === 'group_knockout' && $tournament->groups_count) {
            $groupOptions = array_map(function ($i) {
                return chr(64 + $i);
            }, range(1, $tournament->groups_count));
        } else {
            $groupOptions = [];
        }

        $roundTypes = ['league', 'group', 'quarterfinal', 'semifinal', 'final', 'third_place', 'round_of_16', 'round_of_32'];
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
        // Cari tournament terlebih dahulu
        $tournament = Tournament::find($request->tournament_id);
        
        if (!$tournament) {
            return redirect()->back()
                ->with('error', 'Tournament not found.')
                ->withInput();
        }
        
        $tournamentType = $tournament->type;
        
        // ===== PERBAIKAN: Validasi dinamis berdasarkan tournament type =====
        $rules = [
            'tournament_id' => 'required|exists:tournaments,id',
            'match_date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'required',
            'team_home_id' => 'required|exists:teams,id',
            'team_away_id' => 'required|exists:teams,id|different:team_home_id',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:upcoming,ongoing,completed,postponed',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ];
        
        // PERBAIKAN: Tentukan validasi round_type dan group_name berdasarkan tournament type
        if ($tournamentType === 'league') {
            $rules['round_type'] = 'required|in:league';
            $rules['group_name'] = 'nullable|string|max:1';
        } elseif ($tournamentType === 'knockout') {
            $rules['round_type'] = 'required|in:quarterfinal,semifinal,final,third_place,round_of_16,round_of_32';
            $rules['group_name'] = 'nullable|string|max:1';
        } elseif ($tournamentType === 'group_knockout') {
            $rules['round_type'] = 'required|in:group,quarterfinal,semifinal,final,third_place';
            
            // Group name hanya required untuk round_type group
            if ($request->round_type === 'group') {
                $rules['group_name'] = 'required|string|max:1';
            } else {
                $rules['group_name'] = 'nullable|string|max:1';
            }
        }

        $request->validate($rules);

        // ===== PERBAIKAN: Auto-set values berdasarkan tournament type =====
        if ($tournamentType === 'league') {
            $request->merge(['group_name' => null]);
        } elseif ($tournamentType === 'knockout') {
            $request->merge(['group_name' => null]);
        }

        // Validasi tournament type dan round type compatibility
        $typeValidation = $this->validateTournamentTypeCompatibility($tournament->type, $request->round_type);
        if (!$typeValidation['valid']) {
            return redirect()->back()
                ->with('error', $typeValidation['message'])
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $match, $tournament) {
                $oldStatus = $match->status;
                $oldHomeScore = $match->home_score;
                $oldAwayScore = $match->away_score;

                $match->update($request->all());

                if ($oldStatus === 'completed') {
                    $this->revertMatchResults($match, $oldHomeScore, $oldAwayScore, $tournament);
                }

                if ($request->status === 'completed') {
                    $this->updateMatchResults($match, $tournament);
                }
            });

            return redirect()->route('admin.matches.index')
                ->with('success', 'Match updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating match: '.$e->getMessage())
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
                if ($match->status === 'completed') {
                    $this->revertMatchResults($match, $match->home_score, $match->away_score, $match->tournament);
                }

                $match->delete();
            });

            return redirect()->route('admin.matches.index')
                ->with('success', 'Match deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting match: '.$e->getMessage());
        }
    }

    /**
     * Update match score
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

                $match->update([
                    'home_score' => $request->home_score,
                    'away_score' => $request->away_score,
                    'status' => 'completed',
                ]);

                if ($oldStatus === 'completed') {
                    $this->revertMatchResults($match, $oldHomeScore, $oldAwayScore, $match->tournament);
                }

                $this->updateMatchResults($match, $match->tournament);
            });

            return redirect()->back()
                ->with('success', 'Score updated successfully! Standings have been recalculated.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating score: '.$e->getMessage());
        }
    }

    /**
     * Generate matches automatically based on tournament type
     */
    public function generateMatches(Request $request, Tournament $tournament)
    {
        // Validasi hanya untuk turnamen yang belum memiliki match
        $existingMatches = Game::where('tournament_id', $tournament->id)->count();
        if ($existingMatches > 0) {
            return redirect()->back()
                ->with('error', 'Matches already exist for this tournament. Please delete existing matches first.');
        }

        // Validasi tim
        $teams = $tournament->teams()->get();
        if ($teams->count() < 2) {
            return redirect()->back()
                ->with('error', 'Tournament must have at least 2 teams to generate matches.');
        }

        try {
            DB::transaction(function () use ($tournament, $teams) {
                switch ($tournament->type) {
                    case 'league':
                        $this->generateLeagueMatches($tournament, $teams);
                        break;
                        
                    case 'knockout':
                        $this->generateKnockoutMatches($tournament, $teams);
                        break;
                        
                    case 'group_knockout':
                        $this->generateGroupKnockoutMatches($tournament, $teams);
                        break;
                        
                    default:
                        throw new \Exception('Unsupported tournament type');
                }
            });

            return redirect()->route('admin.matches.index', ['tournament_id' => $tournament->id])
                ->with('success', 'Matches generated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error generating matches: ' . $e->getMessage());
        }
    }

    /**
     * Delete all matches for a tournament
     */
    public function deleteTournamentMatches(Tournament $tournament)
    {
        try {
            $matchCount = Game::where('tournament_id', $tournament->id)->count();
            
            DB::transaction(function () use ($tournament) {
                // Delete all matches for this tournament
                Game::where('tournament_id', $tournament->id)->delete();
                
                // Reset standings if any
                Standing::where('tournament_id', $tournament->id)->delete();
            });

            return redirect()->back()
                ->with('success', "{$matchCount} matches deleted successfully. You can now generate new matches.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting matches: ' . $e->getMessage());
        }
    }

    // ==================== YOUTUBE HIGHLIGHTS ====================

    /**
     * Add YouTube highlight to match
     */
    public function highlights()
    {
        $highlights = Game::whereNotNull('youtube_id')
            ->where('status', 'completed')
            ->with(['homeTeam', 'awayTeam', 'tournament'])
            ->orderBy('youtube_uploaded_at', 'desc')
            ->paginate(9);

        return view('highlights.index', compact('highlights'));
    }

    public function addYoutubeHighlight(Request $request, $matchId)
    {
        $match = Game::find($matchId);

        if (! $match) {
            return response()->json([
                'success' => false,
                'message' => 'Match not found.',
            ], 404);
        }

        // Validate match status
        if ($match->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Highlight can only be added to completed matches.',
            ], 400);
        }

        $request->validate([
            'youtube_url' => 'required|url|max:500',
        ]);

        $youtubeUrl = $request->input('youtube_url');

        // Validate YouTube URL format
        if (! Game::isValidYoutubeUrl($youtubeUrl)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid YouTube URL format. Please provide a valid YouTube link.',
            ], 400);
        }

        $youtubeId = Game::parseYoutubeId($youtubeUrl);

        if (! $youtubeId) {
            return response()->json([
                'success' => false,
                'message' => 'Could not extract YouTube video ID. Please check the URL format.',
            ], 400);
        }

        try {
            $duration = $this->getYoutubeDuration($youtubeId);

            $match->update([
                'youtube_id' => $youtubeId,
                'youtube_thumbnail' => 'youtube',
                'youtube_duration' => $duration,
                'youtube_uploaded_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'YouTube highlight added successfully!',
                'data' => [
                    'youtube_id' => $youtubeId,
                    'embed_url' => $match->youtube_embed_url,
                    'watch_url' => $match->youtube_watch_url,
                    'thumbnail_url' => $match->youtube_thumbnail_url,
                    'duration' => $match->youtube_duration_formatted,
                    'uploaded_at' => $match->youtube_uploaded_at->format('Y-m-d H:i:s'),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding YouTube highlight: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update YouTube highlight
     */
    public function updateYoutubeHighlight(Request $request, $matchId)
    {
        $match = Game::find($matchId);

        if (! $match) {
            return response()->json([
                'success' => false,
                'message' => 'Match not found.',
            ], 404);
        }

        $request->validate([
            'youtube_url' => 'required|url|max:500',
        ]);

        $youtubeUrl = $request->input('youtube_url');

        if (! Game::isValidYoutubeUrl($youtubeUrl)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid YouTube URL format.',
            ], 400);
        }

        $youtubeId = Game::parseYoutubeId($youtubeUrl);

        if (! $youtubeId) {
            return response()->json([
                'success' => false,
                'message' => 'Could not extract YouTube video ID.',
            ], 400);
        }

        try {
            $duration = $this->getYoutubeDuration($youtubeId);

            $match->update([
                'youtube_id' => $youtubeId,
                'youtube_thumbnail' => 'youtube',
                'youtube_duration' => $duration,
                'youtube_uploaded_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'YouTube highlight updated successfully!',
                'data' => [
                    'youtube_id' => $youtubeId,
                    'embed_url' => $match->youtube_embed_url,
                    'thumbnail_url' => $match->youtube_thumbnail_url,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating YouTube highlight: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove YouTube highlight
     */
    public function removeYoutubeHighlight($matchId)
    {
        $match = Game::find($matchId);

        if (! $match) {
            return response()->json([
                'success' => false,
                'message' => 'Match not found.',
            ], 404);
        }

        try {
            $match->update([
                'youtube_id' => null,
                'youtube_thumbnail' => null,
                'youtube_duration' => null,
                'youtube_uploaded_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'YouTube highlight removed successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing highlight: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get YouTube highlight info
     */
    public function getYoutubeHighlightInfo($matchId)
    {
        $match = Game::find($matchId);

        if (! $match) {
            return response()->json([
                'success' => false,
                'message' => 'Match not found.',
            ], 404);
        }

        $hasHighlight = ! empty($match->youtube_id);

        return response()->json([
            'success' => true,
            'data' => [
                'has_highlight' => $hasHighlight,
                'youtube_id' => $match->youtube_id,
                'embed_url' => $match->youtube_embed_url,
                'watch_url' => $match->youtube_watch_url,
                'thumbnail_url' => $match->youtube_thumbnail_url,
                'duration' => $match->youtube_duration_formatted,
                'uploaded_at' => $match->youtube_uploaded_at ? $match->youtube_uploaded_at->format('Y-m-d H:i:s') : null,
                'uploaded_relative' => $match->youtube_uploaded_at ? $match->youtube_uploaded_at->diffForHumans() : null,
                'match_info' => [
                    'id' => $match->id,
                    'teams' => ($match->homeTeam->name ?? 'Home').' vs '.($match->awayTeam->name ?? 'Away'),
                    'date' => $match->match_date->format('Y-m-d'),
                    'status' => $match->status,
                    'score' => $match->home_score !== null ? $match->home_score.' - '.$match->away_score : null,
                ],
            ],
        ]);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Validate tournament type and round type compatibility
     */
    private function validateTournamentTypeCompatibility($tournamentType, $roundType)
    {
        $compatibility = [
            'league' => ['league'], // HANYA 'league'
            'knockout' => ['quarterfinal', 'semifinal', 'final', 'third_place', 'round_of_16', 'round_of_32'],
            'group_knockout' => ['group', 'quarterfinal', 'semifinal', 'final', 'third_place'],
        ];

        if (!isset($compatibility[$tournamentType])) {
            return ['valid' => false, 'message' => 'Invalid tournament type.'];
        }

        if (!in_array($roundType, $compatibility[$tournamentType])) {
            $allowedTypes = implode(', ', $compatibility[$tournamentType]);
            return [
                'valid' => false, 
                'message' => "For {$tournamentType} tournament, round type must be one of: {$allowedTypes}"
            ];
        }

        return ['valid' => true, 'message' => ''];
    }

    /**
     * Generate league matches (round robin)
     */
    private function generateLeagueMatches(Tournament $tournament, $teams)
    {
        $settings = json_decode($tournament->settings, true) ?? [];
        $matchesPerDay = $settings['matches_per_day'] ?? 4;
        $matchDuration = $settings['match_duration'] ?? 40;
        $timeSlots = explode(',', $settings['match_time_slots'] ?? '14:00,16:00,18:00,20:00');
        $timeSlots = array_map('trim', $timeSlots);
        $rounds = $settings['league_rounds'] ?? 1;
        
        // Group teams jika league memiliki groups
        $groups = [];
        if ($tournament->groups_count > 1) {
            foreach ($teams as $team) {
                $groupName = $team->pivot->group_name ?? 'A';
                if (!isset($groups[$groupName])) {
                    $groups[$groupName] = [];
                }
                $groups[$groupName][] = $team->id;
            }
        } else {
            // League tanpa grup
            $groups['single'] = $teams->pluck('id')->toArray();
        }
        
        $currentDate = Carbon::parse($tournament->start_date);
        $endDate = Carbon::parse($tournament->end_date);
        $matchCount = 0;
        $timeSlotIndex = 0;
        
        foreach ($groups as $groupName => $teamIds) {
            $teamCount = count($teamIds);
            
            if ($teamCount < 2) continue;
            
            // Generate all possible pairings
            $pairings = [];
            
            for ($i = 0; $i < $teamCount - 1; $i++) {
                for ($j = $i + 1; $j < $teamCount; $j++) {
                    $pairings[] = [
                        'home' => $teamIds[$i],
                        'away' => $teamIds[$j]
                    ];
                    
                    // Add return match if double round
                    if ($rounds == 2) {
                        $pairings[] = [
                            'home' => $teamIds[$j],
                            'away' => $teamIds[$i]
                        ];
                    }
                }
            }
            
            // Shuffle pairings for random scheduling
            shuffle($pairings);
            
            foreach ($pairings as $pairing) {
                if ($matchCount % $matchesPerDay == 0 && $matchCount > 0) {
                    $currentDate->addDay();
                    
                    // Check if tournament end date is reached
                    if ($currentDate > $endDate) {
                        throw new \Exception('Tournament duration too short for all matches. Please extend the end date.');
                    }
                }
                
                $timeStart = $timeSlots[$timeSlotIndex % count($timeSlots)];
                $timeEnd = Carbon::parse($timeStart)->addMinutes($matchDuration)->format('H:i');
                
                Game::create([
                    'tournament_id' => $tournament->id,
                    'match_date' => $currentDate->format('Y-m-d'),
                    'time_start' => $timeStart,
                    'time_end' => $timeEnd,
                    'team_home_id' => $pairing['home'],
                    'team_away_id' => $pairing['away'],
                    'venue' => $tournament->location ?? 'Main Field',
                    'status' => 'upcoming',
                    'round_type' => 'league',
                    'group_name' => $tournament->groups_count > 1 ? $groupName : null,
                ]);
                
                $matchCount++;
                $timeSlotIndex++;
            }
        }
        
        return $matchCount;
    }

    /**
     * Generate knockout matches
     */
    private function generateKnockoutMatches(Tournament $tournament, $teams)
    {
        $teamIds = $teams->pluck('id')->toArray();
        $teamsCount = count($teamIds);
        $bracketSize = $tournament->knockout_teams ?? 8;
        
        // Validasi jumlah tim
        if ($teamsCount > $bracketSize) {
            throw new \Exception("Knockout tournament can only have {$bracketSize} teams maximum. You have {$teamsCount} teams.");
        }
        
        if ($teamsCount < 2) {
            throw new \Exception("Knockout tournament must have at least 2 teams.");
        }
        
        // Shuffle teams
        shuffle($teamIds);
        
        // Generate bracket
        $matches = [];
        $matchDates = $this->generateMatchDates($tournament->start_date, $tournament->end_date, $bracketSize);
        
        // Round 1 matches
        $roundNumber = 1;
        $totalRounds = log($bracketSize, 2);
        
        for ($i = 0; $i < $bracketSize; $i += 2) {
            $homeTeam = isset($teamIds[$i]) ? $teamIds[$i] : null;
            $awayTeam = isset($teamIds[$i + 1]) ? $teamIds[$i + 1] : null;
            
            $matchData = [
                'tournament_id' => $tournament->id,
                'match_date' => $matchDates[0],
                'time_start' => $this->getTimeSlot($i/2, $tournament),
                'time_end' => $this->getTimeEnd($this->getTimeSlot($i/2, $tournament), $tournament),
                'team_home_id' => $homeTeam,
                'team_away_id' => $awayTeam,
                'venue' => $tournament->location ?? 'Main Field',
                'status' => 'upcoming',
                'round_type' => $this->getRoundType($bracketSize, $roundNumber),
                'group_name' => null,
            ];
            
            // Jika ada bye (tim kosong), set status completed
            if (is_null($homeTeam) || is_null($awayTeam)) {
                $matchData['status'] = 'completed';
                if (is_null($homeTeam)) {
                    $matchData['home_score'] = 0;
                    $matchData['away_score'] = 3; // Forfeit win
                } elseif (is_null($awayTeam)) {
                    $matchData['home_score'] = 3;
                    $matchData['away_score'] = 0;
                }
            }
            
            $matches[] = $matchData;
        }
        
        // Subsequent rounds (quarter, semi, final)
        $roundMatchCount = $bracketSize / 2;
        $roundNumber = 2;
        
        while ($roundMatchCount > 1) {
            $nextRoundMatches = $roundMatchCount / 2;
            
            for ($i = 0; $i < $nextRoundMatches; $i++) {
                $matches[] = [
                    'tournament_id' => $tournament->id,
                    'match_date' => $matchDates[$roundNumber - 1] ?? end($matchDates),
                    'time_start' => $this->getTimeSlot($i, $tournament),
                    'time_end' => $this->getTimeEnd($this->getTimeSlot($i, $tournament), $tournament),
                    'team_home_id' => null, // Will be filled by winners
                    'team_away_id' => null,
                    'venue' => $tournament->location ?? 'Main Field',
                    'status' => 'upcoming',
                    'round_type' => $this->getRoundType($bracketSize, $roundNumber),
                    'group_name' => null,
                ];
            }
            
            $roundMatchCount = $nextRoundMatches;
            $roundNumber++;
        }
        
        // Third place match jika diperlukan
        if ($tournament->knockout_third_place) {
            $matches[] = [
                'tournament_id' => $tournament->id,
                'match_date' => end($matchDates),
                'time_start' => $this->getTimeSlot(0, $tournament),
                'time_end' => $this->getTimeEnd($this->getTimeSlot(0, $tournament), $tournament),
                'team_home_id' => null,
                'team_away_id' => null,
                'venue' => $tournament->location ?? 'Main Field',
                'status' => 'upcoming',
                'round_type' => 'third_place',
                'group_name' => null,
            ];
        }
        
        // Create all matches
        foreach ($matches as $matchData) {
            Game::create($matchData);
        }
    }

    /**
     * Generate group stage + knockout matches
     */
    private function generateGroupKnockoutMatches(Tournament $tournament, $teams)
    {
        $groupsCount = $tournament->groups_count ?? 2;
        $teamsPerGroup = $tournament->teams_per_group ?? 4;
        $totalTeams = $teams->count();
        
        // Validasi jumlah tim
        $expectedTeams = $groupsCount * $teamsPerGroup;
        if ($totalTeams != $expectedTeams) {
            throw new \Exception("Expected {$expectedTeams} teams for {$groupsCount} groups with {$teamsPerGroup} teams each, but got {$totalTeams}");
        }
        
        // Assign teams to groups dari database
        $groupAssignments = [];
        foreach ($teams as $team) {
            $groupName = $team->tournaments()
                ->where('tournament_id', $tournament->id)
                ->first()
                ->pivot
                ->group_name ?? null;
            
            if (!$groupName) {
                // Fallback: assign groups alphabetically
                $teamsArray = $teams->toArray();
                shuffle($teamsArray);
                for ($i = 0; $i < $totalTeams; $i++) {
                    $groupLetter = chr(65 + ($i % $groupsCount));
                    $groupAssignments[$groupLetter][] = $teamsArray[$i]['id'];
                }
                break;
            }
            
            $groupAssignments[$groupName][] = $team->id;
        }
        
        // Generate group stage matches
        $groupStageMatches = [];
        $currentDate = Carbon::parse($tournament->start_date);
        
        foreach ($groupAssignments as $groupLetter => $teamIds) {
            // Generate all pairings within group
            $pairings = [];
            $teamCount = count($teamIds);
            
            for ($i = 0; $i < $teamCount - 1; $i++) {
                for ($j = $i + 1; $j < $teamCount; $j++) {
                    $pairings[] = [
                        'home' => $teamIds[$i],
                        'away' => $teamIds[$j]
                    ];
                }
            }
            
            // Shuffle and schedule
            shuffle($pairings);
            $matchInGroup = 0;
            
            foreach ($pairings as $pairing) {
                $timeSlot = $matchInGroup % 4; // 4 match slots per day
                $groupStageMatches[] = [
                    'tournament_id' => $tournament->id,
                    'match_date' => $currentDate->format('Y-m-d'),
                    'time_start' => $this->getTimeSlot($timeSlot, $tournament),
                    'time_end' => $this->getTimeEnd($this->getTimeSlot($timeSlot, $tournament), $tournament),
                    'team_home_id' => $pairing['home'],
                    'team_away_id' => $pairing['away'],
                    'venue' => $tournament->location ?? 'Main Field',
                    'status' => 'upcoming',
                    'round_type' => 'group',
                    'group_name' => $groupLetter,
                ];
                
                $matchInGroup++;
                if ($matchInGroup % 4 == 0) {
                    $currentDate->addDay();
                }
            }
            
            // Add day if not all matches scheduled for the day
            if ($matchInGroup % 4 != 0) {
                $currentDate->addDay();
            }
        }
        
        // Create group stage matches
        foreach ($groupStageMatches as $matchData) {
            Game::create($matchData);
        }
        
        // Generate knockout stage matches
        $this->scheduleKnockoutStage($tournament, $currentDate, $groupsCount);
    }

    /**
     * Schedule knockout stage for group_knockout tournament
     */
    private function scheduleKnockoutStage(Tournament $tournament, Carbon $startDate, $groupsCount)
    {
        $qualifyPerGroup = $tournament->qualify_per_group ?? 2;
        $knockoutTeams = $groupsCount * $qualifyPerGroup;
        
        if ($knockoutTeams < 2) {
            return; // Tidak ada knockout stage jika kurang dari 2 tim
        }
        
        // Determine round types based on number of teams
        $roundTypes = [];
        $teamCount = $knockoutTeams;
        
        while ($teamCount > 1) {
            if ($teamCount >= 8) {
                $roundTypes[] = 'quarterfinal';
                $teamCount = $teamCount / 2;
            } elseif ($teamCount >= 4) {
                $roundTypes[] = 'semifinal';
                $teamCount = $teamCount / 2;
            } elseif ($teamCount == 2) {
                $roundTypes[] = 'final';
                $teamCount = $teamCount / 2;
            }
        }
        
        // Add third place match if needed
        if ($tournament->knockout_third_place) {
            $roundTypes[] = 'third_place';
        }
        
        // Create placeholder matches for knockout stage
        $matchDate = $startDate->copy()->addDays(2); // 2 days break after group stage
        
        foreach ($roundTypes as $roundType) {
            $matchesInRound = $this->getMatchesInRound($roundType, $knockoutTeams);
            
            for ($i = 0; $i < $matchesInRound; $i++) {
                Game::create([
                    'tournament_id' => $tournament->id,
                    'match_date' => $matchDate->format('Y-m-d'),
                    'time_start' => $this->getTimeSlot($i, $tournament),
                    'time_end' => $this->getTimeEnd($this->getTimeSlot($i, $tournament), $tournament),
                    'team_home_id' => null, // Will be filled by group stage winners
                    'team_away_id' => null,
                    'venue' => $tournament->location ?? 'Main Field',
                    'status' => 'upcoming',
                    'round_type' => $roundType,
                    'group_name' => null,
                ]);
            }
            
            $matchDate->addDays(2); // 2 days between rounds
        }
    }

    /**
     * Update match results (standings and tournament progression)
     */
    private function updateMatchResults(Game $match, Tournament $tournament)
    {
        if ($match->status !== 'completed') {
            return;
        }

        // Update standings for group matches
        if ($match->round_type === 'group') {
            $this->updateStandings($match);
        }

        // Update knockout bracket progression
        if (in_array($match->round_type, ['quarterfinal', 'semifinal', 'final', 'third_place', 'round_of_16', 'round_of_32'])) {
            $this->updateKnockoutProgression($match, $tournament);
        }
    }

    /**
     * Revert match results
     */
    private function revertMatchResults(Game $match, $oldHomeScore, $oldAwayScore, Tournament $tournament)
    {
        if ($match->round_type === 'group') {
            $this->revertStandings($match, $oldHomeScore, $oldAwayScore);
        }

        // Revert knockout progression
        if (in_array($match->round_type, ['quarterfinal', 'semifinal', 'final', 'third_place', 'round_of_16', 'round_of_32'])) {
            $this->revertKnockoutProgression($match, $tournament);
        }
    }

    /**
     * Update standings for group matches
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

        $homeStanding = Standing::firstOrCreate(
            [
                'tournament_id' => $tournamentId,
                'team_id' => $homeTeamId,
                'group_name' => $groupName,
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
                'group_name' => $groupName,
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

        $homeStanding->increment('matches_played');
        $awayStanding->increment('matches_played');

        $homeStanding->increment('goals_for', $match->home_score);
        $homeStanding->increment('goals_against', $match->away_score);

        $awayStanding->increment('goals_for', $match->away_score);
        $awayStanding->increment('goals_against', $match->home_score);

        $tournament = Tournament::find($tournamentId);
        $pointsWin = $tournament->points_win ?? 3;
        $pointsDraw = $tournament->points_draw ?? 1;
        $pointsLoss = $tournament->points_loss ?? 0;

        if ($match->home_score > $match->away_score) {
            $homeStanding->increment('wins');
            $homeStanding->increment('points', $pointsWin);
            $awayStanding->increment('losses');
            $awayStanding->increment('points', $pointsLoss);
        } elseif ($match->home_score < $match->away_score) {
            $awayStanding->increment('wins');
            $awayStanding->increment('points', $pointsWin);
            $homeStanding->increment('losses');
            $homeStanding->increment('points', $pointsLoss);
        } else {
            $homeStanding->increment('draws');
            $homeStanding->increment('points', $pointsDraw);
            $awayStanding->increment('draws');
            $awayStanding->increment('points', $pointsDraw);
        }

        $homeStanding->update([
            'goal_difference' => $homeStanding->goals_for - $homeStanding->goals_against,
        ]);

        $awayStanding->update([
            'goal_difference' => $awayStanding->goals_for - $awayStanding->goals_against,
        ]);
    }

    /**
     * Revert standings
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

        $homeScore = $oldHomeScore ?? $match->home_score;
        $awayScore = $oldAwayScore ?? $match->away_score;

        $homeStanding = Standing::where('tournament_id', $tournamentId)
            ->where('team_id', $homeTeamId)
            ->where('group_name', $groupName)
            ->first();

        $awayStanding = Standing::where('tournament_id', $tournamentId)
            ->where('team_id', $awayTeamId)
            ->where('group_name', $groupName)
            ->first();

        if (! $homeStanding || ! $awayStanding) {
            return;
        }

        $homeStanding->decrement('matches_played');
        $awayStanding->decrement('matches_played');

        $homeStanding->decrement('goals_for', $homeScore);
        $homeStanding->decrement('goals_against', $awayScore);

        $awayStanding->decrement('goals_for', $awayScore);
        $awayStanding->decrement('goals_against', $homeScore);

        $tournament = Tournament::find($tournamentId);
        $pointsWin = $tournament->points_win ?? 3;
        $pointsDraw = $tournament->points_draw ?? 1;
        $pointsLoss = $tournament->points_loss ?? 0;

        if ($homeScore > $awayScore) {
            $homeStanding->decrement('wins');
            $homeStanding->decrement('points', $pointsWin);
            $awayStanding->decrement('losses');
            $awayStanding->decrement('points', $pointsLoss);
        } elseif ($homeScore < $awayScore) {
            $awayStanding->decrement('wins');
            $awayStanding->decrement('points', $pointsWin);
            $homeStanding->decrement('losses');
            $homeStanding->decrement('points', $pointsLoss);
        } else {
            $homeStanding->decrement('draws');
            $homeStanding->decrement('points', $pointsDraw);
            $awayStanding->decrement('draws');
            $awayStanding->decrement('points', $pointsDraw);
        }

        $homeStanding->update([
            'goal_difference' => $homeStanding->goals_for - $homeStanding->goals_against,
        ]);

        $awayStanding->update([
            'goal_difference' => $awayStanding->goals_for - $awayStanding->goals_against,
        ]);

        if ($homeStanding->matches_played <= 0) {
            $homeStanding->delete();
        }
        if ($awayStanding->matches_played <= 0) {
            $awayStanding->delete();
        }
    }

    /**
     * Update knockout bracket progression
     */
    private function updateKnockoutProgression(Game $match, Tournament $tournament)
    {
        if ($match->status !== 'completed' || !in_array($match->round_type, ['quarterfinal', 'semifinal', 'final', 'third_place', 'round_of_16', 'round_of_32'])) {
            return;
        }

        // Determine next round based on current round
        $nextRound = $this->getNextRoundType($match->round_type);
        
        if (!$nextRound) {
            return; // Final match, no next round
        }

        // Find the next match in the bracket
        $nextMatch = Game::where('tournament_id', $tournament->id)
            ->where('round_type', $nextRound)
            ->where(function ($query) {
                $query->whereNull('team_home_id')
                      ->orWhereNull('team_away_id');
            })
            ->orderBy('match_date')
            ->orderBy('time_start')
            ->first();

        if (!$nextMatch) {
            return; // No next match found
        }

        // Determine winner
        $winnerId = null;
        if ($match->home_score > $match->away_score) {
            $winnerId = $match->team_home_id;
        } elseif ($match->away_score > $match->home_score) {
            $winnerId = $match->team_away_id;
        }

        if ($winnerId) {
            // Fill the next match slot
            if (!$nextMatch->team_home_id) {
                $nextMatch->team_home_id = $winnerId;
            } elseif (!$nextMatch->team_away_id) {
                $nextMatch->team_away_id = $winnerId;
            }
            $nextMatch->save();
        }

        // For third place match
        if ($match->round_type === 'semifinal' && $tournament->knockout_third_place) {
            $loserId = ($match->home_score < $match->away_score) ? $match->team_home_id : $match->team_away_id;
            
            $thirdPlaceMatch = Game::where('tournament_id', $tournament->id)
                ->where('round_type', 'third_place')
                ->first();

            if ($thirdPlaceMatch) {
                if (!$thirdPlaceMatch->team_home_id) {
                    $thirdPlaceMatch->team_home_id = $loserId;
                } elseif (!$thirdPlaceMatch->team_away_id) {
                    $thirdPlaceMatch->team_away_id = $loserId;
                }
                $thirdPlaceMatch->save();
            }
        }
    }

    /**
     * Revert knockout progression
     */
    private function revertKnockoutProgression(Game $match, Tournament $tournament)
    {
        // Clear the next match slots
        $nextRound = $this->getNextRoundType($match->round_type);
        
        if ($nextRound) {
            $nextMatches = Game::where('tournament_id', $tournament->id)
                ->where('round_type', $nextRound)
                ->get();

            foreach ($nextMatches as $nextMatch) {
                if ($nextMatch->team_home_id == $match->team_home_id || $nextMatch->team_home_id == $match->team_away_id) {
                    $nextMatch->team_home_id = null;
                }
                if ($nextMatch->team_away_id == $match->team_home_id || $nextMatch->team_away_id == $match->team_away_id) {
                    $nextMatch->team_away_id = null;
                }
                $nextMatch->save();
            }
        }

        // Revert third place match
        if ($match->round_type === 'semifinal') {
            $thirdPlaceMatch = Game::where('tournament_id', $tournament->id)
                ->where('round_type', 'third_place')
                ->first();

            if ($thirdPlaceMatch) {
                if ($thirdPlaceMatch->team_home_id == $match->team_home_id || $thirdPlaceMatch->team_home_id == $match->team_away_id) {
                    $thirdPlaceMatch->team_home_id = null;
                }
                if ($thirdPlaceMatch->team_away_id == $match->team_home_id || $thirdPlaceMatch->team_away_id == $match->team_away_id) {
                    $thirdPlaceMatch->team_away_id = null;
                }
                $thirdPlaceMatch->save();
            }
        }
    }

    // ==================== UTILITY METHODS ====================

    /**
     * Get YouTube duration (optional - requires YouTube Data API v3 key)
     */
    private function getYoutubeDuration($videoId)
    {
        return null;
    }

    public function recalculateStandings($tournamentId)
    {
        try {
            DB::transaction(function () use ($tournamentId) {
                Standing::where('tournament_id', $tournamentId)->delete();

                $completedMatches = Game::where('tournament_id', $tournamentId)
                    ->where('round_type', 'group')
                    ->where('status', 'completed')
                    ->get();

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
     * Add event to match
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
                $event = MatchEvent::create([
                    'match_id' => $match->id,
                    'player_id' => $request->player_id,
                    'event_type' => $request->event_type,
                    'minute' => $request->minute,
                    'description' => $request->description,
                    'is_own_goal' => $request->boolean('is_own_goal'),
                    'is_penalty' => $request->boolean('is_penalty'),
                ]);

                if ($request->event_type === 'goal') {
                    $player = $event->player;
                    $player->increment('goals');
                }

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
                ->with('error', 'Error adding event: '.$e->getMessage());
        }
    }

    /**
     * Delete event from match
     */
    public function deleteEvent(MatchEvent $event)
    {
        try {
            DB::transaction(function () use ($event) {
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
                ->with('error', 'Error deleting event: '.$e->getMessage());
        }
    }

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
                $homeTeamInTournament = $tournament->teams()
                    ->where('team_id', $matchData['team_home_id'])
                    ->exists();
                $awayTeamInTournament = $tournament->teams()
                    ->where('team_id', $matchData['team_away_id'])
                    ->exists();

                if (! $homeTeamInTournament || ! $awayTeamInTournament) {
                    throw new \Exception('One or both teams are not in this tournament');
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
                ->with('error', 'Error creating matches: '.$e->getMessage())
                ->withInput();
        }
    }

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

    public function toggleStatus(Game $match, $status)
    {
        try {
            DB::transaction(function () use ($match, $status) {
                $oldStatus = $match->status;

                $match->update(['status' => $status]);

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
                ->with('error', 'Error updating match status: '.$e->getMessage());
        }
    }

    // ==================== MATCH GENERATION HELPER METHODS ====================

    /**
     * Generate match dates for knockout bracket
     */
    private function generateMatchDates($startDate, $endDate, $bracketSize)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $interval = $start->diffInDays($end);
        
        $dates = [];
        $rounds = log($bracketSize, 2);
        
        for ($i = 0; $i < $rounds; $i++) {
            $date = $start->copy()->addDays($i * 2); // 2 days between rounds
            if ($date <= $end) {
                $dates[] = $date->format('Y-m-d');
            }
        }
        
        return $dates;
    }

    /**
     * Get time slot for match
     */
    private function getTimeSlot($index, Tournament $tournament)
    {
        $timeSlots = explode(',', $tournament->match_time_slots ?? '14:00,16:00,18:00,20:00');
        $timeSlots = array_map('trim', $timeSlots);
        return $timeSlots[$index % count($timeSlots)] ?? '14:00';
    }

    /**
     * Get end time for match
     */
    private function getTimeEnd($timeStart, Tournament $tournament)
    {
        $duration = $tournament->match_duration ?? 40;
        return Carbon::parse($timeStart)->addMinutes($duration)->format('H:i');
    }

    /**
     * Get round type based on bracket size and round number
     */
    private function getRoundType($bracketSize, $roundNumber)
    {
        if ($bracketSize == 2) {
            return 'final';
        }
        
        $totalRounds = log($bracketSize, 2);
        
        if ($roundNumber == 1) {
            if ($bracketSize >= 32) return 'round_of_32';
            if ($bracketSize >= 16) return 'round_of_16';
            if ($bracketSize >= 8) return 'quarterfinal';
            if ($bracketSize >= 4) return 'semifinal';
        }
        
        if ($roundNumber == $totalRounds) {
            return 'final';
        }
        
        if ($roundNumber == $totalRounds - 1) {
            return 'semifinal';
        }
        
        return 'quarterfinal';
    }

    /**
     * Get next round type
     */
    private function getNextRoundType($currentRound)
    {
        $progression = [
            'round_of_32' => 'round_of_16',
            'round_of_16' => 'quarterfinal',
            'quarterfinal' => 'semifinal',
            'semifinal' => 'final',
            'final' => null,
            'third_place' => null,
        ];
        
        return $progression[$currentRound] ?? null;
    }

    /**
     * Get matches in round
     */
    private function getMatchesInRound($roundType, $totalTeams)
    {
        switch ($roundType) {
            case 'quarterfinal': return ceil($totalTeams / 8);
            case 'semifinal': return ceil($totalTeams / 4);
            case 'final': return 1;
            case 'third_place': return 1;
            case 'round_of_16': return ceil($totalTeams / 16);
            case 'round_of_32': return ceil($totalTeams / 32);
            default: return 0;
        }
    }

    /**
     * Calculate total matches for tournament
     */
    public function calculateTotalMatches(Tournament $tournament)
    {
        $teamCount = $tournament->teams()->count();
        
        switch ($tournament->type) {
            case 'league':
                $rounds = $tournament->league_rounds ?? 1;
                return ($teamCount * ($teamCount - 1) / 2) * $rounds;
                
            case 'knockout':
                $bracketSize = $tournament->knockout_teams ?? 8;
                $total = $bracketSize - 1;
                if ($tournament->knockout_third_place) {
                    $total += 1;
                }
                return $total;
                
            case 'group_knockout':
                $groupsCount = $tournament->groups_count ?? 2;
                $teamsPerGroup = $tournament->teams_per_group ?? 4;
                $qualifyPerGroup = $tournament->qualify_per_group ?? 2;
                
                // Group stage matches
                $groupMatches = $groupsCount * ($teamsPerGroup * ($teamsPerGroup - 1) / 2);
                
                // Knockout matches
                $knockoutTeams = $groupsCount * $qualifyPerGroup;
                $knockoutMatches = $knockoutTeams - 1;
                if ($tournament->knockout_third_place) {
                    $knockoutMatches += 1;
                }
                
                return $groupMatches + $knockoutMatches;
                
            default:
                return 0;
        }
    }
}