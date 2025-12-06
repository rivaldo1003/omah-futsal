<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\MatchEvent;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchEventController extends Controller
{
    /**
     * Display events for a specific match
     */
    public function index(Game $match)
    {
        // Ambil events - HANYA orderBy minute saja
        $events = MatchEvent::where('match_id', $match->id)
            ->orderBy('minute')
            ->with(['team', 'player', 'relatedPlayer'])
            ->get();

        // Ambil players dari kedua tim
        $homeTeamPlayers = Player::where('team_id', $match->team_home_id)
            ->orderBy('name')
            ->get();

        $awayTeamPlayers = Player::where('team_id', $match->team_away_id)
            ->orderBy('name')
            ->get();

        $players = $homeTeamPlayers->merge($awayTeamPlayers)->sortBy('name');

        // Event statistics
        $eventStats = [
            'home' => [
                'goals' => $events->where('event_type', 'goal')
                    ->filter(function ($event) use ($match) {
                        return $event->team_id == $match->team_home_id && !$event->is_own_goal;
                    })->count(),
                'yellow_cards' => $events->where('event_type', 'yellow_card')
                    ->where('team_id', $match->team_home_id)->count(),
                'red_cards' => $events->where('event_type', 'red_card')
                    ->where('team_id', $match->team_home_id)->count(),
                'substitutions' => $events->where('event_type', 'substitution')
                    ->where('team_id', $match->team_home_id)->count(),
            ],
            'away' => [
                'goals' => $events->where('event_type', 'goal')
                    ->filter(function ($event) use ($match) {
                        return $event->team_id == $match->team_away_id && !$event->is_own_goal;
                    })->count(),
                'yellow_cards' => $events->where('event_type', 'yellow_card')
                    ->where('team_id', $match->team_away_id)->count(),
                'red_cards' => $events->where('event_type', 'red_card')
                    ->where('team_id', $match->team_away_id)->count(),
                'substitutions' => $events->where('event_type', 'substitution')
                    ->where('team_id', $match->team_away_id)->count(),
            ]
        ];

        // Load match data
        $match->load(['homeTeam', 'awayTeam']);

        return view('admin.matches.events.index', compact('match', 'events', 'players', 'eventStats'));
    }

    /**
     * Show form to create new event
     */
    public function create(Game $match)
    {
        $teams = Team::whereIn('id', [$match->team_home_id, $match->awayTeam->id])->get();

        // Ambil players dari kedua tim
        $homeTeamPlayers = Player::where('team_id', $match->team_home_id)
            ->orderBy('name')
            ->get();

        $awayTeamPlayers = Player::where('team_id', $match->team_away_id)
            ->orderBy('name')
            ->get();

        $players = $homeTeamPlayers->merge($awayTeamPlayers)->sortBy('name');

        $eventTypes = [
            'goal' => 'Goal',
            'yellow_card' => 'Yellow Card',
            'red_card' => 'Red Card',
            'substitution' => 'Substitution',
            'penalty' => 'Penalty',
            'foul' => 'Foul',
            'injury' => 'Injury',
        ];

        return view('admin.matches.events.create', compact('match', 'teams', 'players', 'eventTypes'));
    }

    /**
     * Store a new match event
     */
    public function store(Request $request, Game $match)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id|in:' . $match->team_home_id . ',' . $match->team_away_id,
            'player_id' => 'required|exists:players,id',
            'related_player_id' => 'nullable|exists:players,id|different:player_id',
            'event_type' => 'required|in:goal,yellow_card,red_card,substitution,penalty,foul,injury',
            'minute' => 'required|integer|min:1|max:120',
            'description' => 'nullable|string|max:500',
            'is_own_goal' => 'nullable|boolean',
            'is_penalty' => 'nullable|boolean',
        ]);

        $validated['match_id'] = $match->id;
        $validated['is_own_goal'] = $request->boolean('is_own_goal');
        $validated['is_penalty'] = $request->boolean('is_penalty');

        try {
            DB::beginTransaction();

            // Create the event
            $event = MatchEvent::create($validated);

            // Update match score if it's a goal
            if ($validated['event_type'] === 'goal') {
                $this->updateMatchScore($match, $validated['team_id'], $validated['is_own_goal']);
            }

            // Update player statistics
            $this->updatePlayerStats($event);

            DB::commit();

            return redirect()->route('admin.matches.events.index', $match)
                ->with('success', 'Event berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan event: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form to edit event
     */
    public function edit(Game $match, MatchEvent $event)
    {
        $teams = Team::whereIn('id', [$match->team_home_id, $match->team_away_id])->get();

        // Ambil players dari kedua tim
        $homeTeamPlayers = Player::where('team_id', $match->team_home_id)
            ->orderBy('name')
            ->get();

        $awayTeamPlayers = Player::where('team_id', $match->team_away_id)
            ->orderBy('name')
            ->get();

        $players = $homeTeamPlayers->merge($awayTeamPlayers)->sortBy('name');

        $eventTypes = [
            'goal' => 'Goal',
            'yellow_card' => 'Yellow Card',
            'red_card' => 'Red Card',
            'substitution' => 'Substitution',
            'penalty' => 'Penalty',
            'foul' => 'Foul',
            'injury' => 'Injury',
        ];

        return view('admin.matches.events.edit', compact('match', 'event', 'teams', 'players', 'eventTypes'));
    }

    /**
     * Update an existing event
     */
    public function update(Request $request, Game $match, MatchEvent $event)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id|in:' . $match->team_home_id . ',' . $match->team_away_id,
            'player_id' => 'required|exists:players,id',
            'related_player_id' => 'nullable|exists:players,id|different:player_id',
            'event_type' => 'required|in:goal,yellow_card,red_card,substitution,penalty,foul,injury',
            'minute' => 'required|integer|min:1|max:120',
            'description' => 'nullable|string|max:500',
            'is_own_goal' => 'nullable|boolean',
            'is_penalty' => 'nullable|boolean',
        ]);

        $oldEventType = $event->event_type;
        $oldTeamId = $event->team_id;
        $oldIsOwnGoal = $event->is_own_goal;

        $validated['is_own_goal'] = $request->boolean('is_own_goal');
        $validated['is_penalty'] = $request->boolean('is_penalty');

        try {
            DB::beginTransaction();

            // Revert old effects
            if ($oldEventType === 'goal') {
                $this->revertMatchScore($match, $oldTeamId, $oldIsOwnGoal);
                $this->revertPlayerStats($event);
            }

            // Update the event
            $event->update($validated);

            // Apply new effects
            if ($validated['event_type'] === 'goal') {
                $this->updateMatchScore($match, $validated['team_id'], $validated['is_own_goal']);
                $this->updatePlayerStats($event);
            }

            DB::commit();

            return redirect()->route('admin.matches.events.index', $match)
                ->with('success', 'Event berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui event: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete an event
     */
    public function destroy(Game $match, MatchEvent $event)
    {
        try {
            DB::transaction(function () use ($match, $event) {
                // Revert effects before deleting
                if ($event->event_type === 'goal') {
                    $this->revertMatchScore($match, $event->team_id, $event->is_own_goal);
                }

                $this->revertPlayerStats($event);
                $event->delete();
            });

            return redirect()->route('admin.matches.events.index', $match)
                ->with('success', 'Event berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus event: ' . $e->getMessage());
        }
    }

    /**
     * Helper to update match score
     */
    private function updateMatchScore(Game $match, $teamId, $isOwnGoal)
    {
        $match->refresh();

        if ($isOwnGoal) {
            // Own goal: opposite team gets the goal
            $scoringTeamId = ($teamId == $match->team_home_id)
                ? $match->team_away_id
                : $match->team_home_id;
        } else {
            $scoringTeamId = $teamId;
        }

        if ($scoringTeamId == $match->team_home_id) {
            $match->home_score += 1;
        } else {
            $match->away_score += 1;
        }

        // Update match status if needed
        if ($match->status === 'upcoming') {
            $match->status = 'ongoing';
        }

        $match->save();
    }

    /**
     * Helper to revert match score
     */
    private function revertMatchScore(Game $match, $teamId, $isOwnGoal)
    {
        $match->refresh();

        if ($isOwnGoal) {
            $scoringTeamId = ($teamId == $match->team_home_id)
                ? $match->team_away_id
                : $match->team_home_id;
        } else {
            $scoringTeamId = $teamId;
        }

        if ($scoringTeamId == $match->team_home_id) {
            $match->home_score = max(0, $match->home_score - 1);
        } else {
            $match->away_score = max(0, $match->away_score - 1);
        }

        $match->save();
    }

    /**
     * Update player statistics based on event
     */
    private function updatePlayerStats(MatchEvent $event)
    {
        $player = $event->player;

        switch ($event->event_type) {
            case 'goal':
                $player->increment('goals');
                if ($event->is_penalty) {
                    $player->increment('penalty_goals');
                }
                break;

            case 'yellow_card':
                $player->increment('yellow_cards');
                break;

            case 'red_card':
                $player->increment('red_cards');
                break;

            case 'penalty':
                $player->increment('penalty_missed');
                break;
        }

        // Update related player stats if applicable
        if ($event->related_player_id && $event->event_type === 'goal') {
            $relatedPlayer = Player::find($event->related_player_id);
            if ($relatedPlayer) {
                $relatedPlayer->increment('assists');
            }
        }
    }

    /**
     * Revert player statistics
     */
    private function revertPlayerStats(MatchEvent $event)
    {
        $player = $event->player;

        switch ($event->event_type) {
            case 'goal':
                $player->decrement('goals');
                if ($event->is_penalty) {
                    $player->decrement('penalty_goals');
                }
                break;

            case 'yellow_card':
                $player->decrement('yellow_cards');
                break;

            case 'red_card':
                $player->decrement('red_cards');
                break;

            case 'penalty':
                $player->decrement('penalty_missed');
                break;
        }

        // Revert related player stats
        if ($event->related_player_id && $event->event_type === 'goal') {
            $relatedPlayer = Player::find($event->related_player_id);
            if ($relatedPlayer) {
                $relatedPlayer->decrement('assists');
            }
        }
    }

    /**
     * Get timeline of events for a match (API)
     */
    public function timeline(Game $match)
    {
        $events = $match->events()
            ->with(['player', 'relatedPlayer', 'team'])
            ->orderBy('minute')
            ->get()
            ->groupBy(function ($event) {
                if ($event->minute <= 45)
                    return 'first_half';
                if ($event->minute <= 90)
                    return 'second_half';
                return 'extra_time';
            });

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Quick add goal
     */
    public function quickAddGoal(Request $request, Game $match)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'player_id' => 'required|exists:players,id',
            'minute' => 'required|integer|min:1|max:120',
            'is_penalty' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $event = MatchEvent::create([
                'match_id' => $match->id,
                'team_id' => $request->team_id,
                'player_id' => $request->player_id,
                'event_type' => 'goal',
                'minute' => $request->minute,
                'is_penalty' => $request->boolean('is_penalty'),
                'is_own_goal' => false,
            ]);

            $this->updateMatchScore($match, $request->team_id, false);
            $this->updatePlayerStats($event);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Goal berhasil ditambahkan!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick add card
     */
    public function quickAddCard(Request $request, Game $match)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'player_id' => 'required|exists:players,id',
            'card_type' => 'required|in:yellow_card,red_card',
            'minute' => 'required|integer|min:1|max:120',
        ]);

        try {
            $event = MatchEvent::create([
                'match_id' => $match->id,
                'team_id' => $request->team_id,
                'player_id' => $request->player_id,
                'event_type' => $request->card_type,
                'minute' => $request->minute,
            ]);

            $this->updatePlayerStats($event);

            return response()->json([
                'success' => true,
                'message' => 'Kartu berhasil ditambahkan!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}