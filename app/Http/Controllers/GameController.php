<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\MatchEvent;
use App\Models\Standing;
use App\Models\Team;
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
            $groupOptions = [];
            if ($tournament && $tournament->groups_count) {
                $groupOptions = array_map(function ($i) {
                    return chr(64 + $i);
                }, range(1, $tournament->groups_count));
            } else {
                $groupOptions = ['A', 'B'];
            }
        } else {
            $teams = collect();
            $groupOptions = ['A', 'B'];
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

        $tournament = Tournament::find($request->tournament_id);

        $homeTeamInTournament = $tournament->teams()->where('team_id', $request->team_home_id)->exists();
        $awayTeamInTournament = $tournament->teams()->where('team_id', $request->team_away_id)->exists();

        if (! $homeTeamInTournament) {
            return redirect()->back()
                ->with('error', 'Home team is not registered in selected tournament.')
                ->withInput();
        }

        if (! $awayTeamInTournament) {
            return redirect()->back()
                ->with('error', 'Away team is not registered in selected tournament.')
                ->withInput();
        }

        if ($request->round_type === 'group') {
            $homeTeamGroup = $tournament->teams()->where('team_id', $request->team_home_id)->first()->pivot->group_name;
            $awayTeamGroup = $tournament->teams()->where('team_id', $request->team_away_id)->first()->pivot->group_name;

            if ($homeTeamGroup !== $awayTeamGroup) {
                return redirect()->back()
                    ->with('error', 'For group stage matches, teams must be in the same group.')
                    ->withInput();
            }

            $request->merge(['group_name' => $homeTeamGroup]);
        }

        try {
            DB::transaction(function () use ($request) {
                $match = Game::create($request->all());

                if ($request->status === 'completed' && $request->round_type === 'group') {
                    $this->updateStandings($match);
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

                $match->update($request->all());

                if ($oldRoundType === 'group' && $oldStatus === 'completed') {
                    $this->revertStandings($match, $oldHomeScore, $oldAwayScore);
                }

                if ($request->round_type === 'group' && $request->status === 'completed') {
                    $this->updateStandings($match);
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
                if ($match->status === 'completed' && $match->round_type === 'group') {
                    $this->revertStandings($match);
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

                if ($match->round_type === 'group') {
                    if ($oldStatus === 'completed') {
                        $this->revertStandings($match, $oldHomeScore, $oldAwayScore);
                    }

                    $this->updateStandings($match);
                }
            });

            return redirect()->back()
                ->with('success', 'Score updated successfully! Standings have been recalculated.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating score: '.$e->getMessage());
        }
    }

    // ==================== YOUTUBE HIGHLIGHTS ====================

    /**
     * Add YouTube highlight to match
     */
    public function highlights()
    {
        // PERBAIKAN: Gunakan youtube_id, bukan highlight_video
        $highlights = Game::whereNotNull('youtube_id')
            ->where('status', 'completed')
            ->with(['homeTeam', 'awayTeam', 'tournament'])
            ->orderBy('youtube_uploaded_at', 'desc')
            ->paginate(9);

        // Debug info
        \Log::info('Highlights count: '.$highlights->count());
        \Log::info('SQL Query: '.\App\Models\Game::whereNotNull('youtube_id')->toSql());

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
            // Optional: Get video duration from YouTube API if you have API key
            $duration = $this->getYoutubeDuration($youtubeId);

            $match->update([
                'youtube_id' => $youtubeId,
                'youtube_thumbnail' => 'youtube', // Flag untuk menunjukkan ini YouTube
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

    /**
     * Get YouTube duration (optional - requires YouTube Data API v3 key)
     */
    private function getYoutubeDuration($videoId)
    {
        // Jika Anda punya YouTube Data API v3 key, bisa diimplementasi
        // Untuk sekarang, return null saja
        return null;

        /*
        $apiKey = config('services.youtube.api_key');
        if (!$apiKey) return null;

        try {
            $url = "https://www.googleapis.com/youtube/v3/videos?id={$videoId}&part=contentDetails&key={$apiKey}";
            $response = @file_get_contents($url);

            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['items'][0]['contentDetails']['duration'])) {
                    $duration = $data['items'][0]['contentDetails']['duration'];
                    $interval = new \DateInterval($duration);
                    return $interval->h * 3600 + $interval->i * 60 + $interval->s;
                }
            }
        } catch (\Exception $e) {
            // Ignore error
        }

        return null;
        */
    }

    // ==================== HELPER METHODS ====================

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

        if ($match->home_score > $match->away_score) {
            $homeStanding->increment('wins');
            $homeStanding->increment('points', 3);
            $awayStanding->increment('losses');
        } elseif ($match->home_score < $match->away_score) {
            $awayStanding->increment('wins');
            $awayStanding->increment('points', 3);
            $homeStanding->increment('losses');
        } else {
            $homeStanding->increment('draws');
            $homeStanding->increment('points', 1);
            $awayStanding->increment('draws');
            $awayStanding->increment('points', 1);
        }

        $homeStanding->update([
            'goal_difference' => $homeStanding->goals_for - $homeStanding->goals_against,
        ]);

        $awayStanding->update([
            'goal_difference' => $awayStanding->goals_for - $awayStanding->goals_against,
        ]);
    }

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

        if ($homeScore > $awayScore) {
            $homeStanding->decrement('wins');
            $homeStanding->decrement('points', 3);
            $awayStanding->decrement('losses');
        } elseif ($homeScore < $awayScore) {
            $awayStanding->decrement('wins');
            $awayStanding->decrement('points', 3);
            $homeStanding->decrement('losses');
        } else {
            $homeStanding->decrement('draws');
            $homeStanding->decrement('points', 1);
            $awayStanding->decrement('draws');
            $awayStanding->decrement('points', 1);
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
}
