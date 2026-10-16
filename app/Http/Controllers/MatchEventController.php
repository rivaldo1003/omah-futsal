<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\MatchEvent;
use App\Models\Player;
use App\Models\Team;
use App\Services\GoalkeeperCleanSheetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchEventController extends Controller
{
    /**
     * Display events for a specific match
     */
    public function index(Game $match)
    {
        if ($match->is_cancelled) {
            return redirect()->route('admin.matches.index')
                ->with('error', 'Fixture dinonaktifkan. Event pertandingan tidak dapat diubah.');
        }

        // Ambil events - HANYA orderBy minute saja
        $events = MatchEvent::where('match_id', $match->id)
            ->where('event_type', '!=', 'clean_sheet')
            ->orderBy('minute')
            ->with(['team', 'player', 'relatedPlayer', 'concededByGoalkeeper'])
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
            ],
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
        if ($match->is_cancelled) {
            return redirect()->route('admin.matches.index')
                ->with('error', 'Fixture dinonaktifkan. Event pertandingan tidak dapat diubah.');
        }

        // Gunakan team_away_id secara langsung untuk menghindari error jika relasi belum dimuat
        $teams = Team::whereIn('id', [$match->team_home_id, $match->team_away_id])->get();

        // Ambil players dari kedua tim
        $homeTeamPlayers = Player::where('team_id', $match->team_home_id)
            ->orderBy('name')
            ->get();

        $awayTeamPlayers = Player::where('team_id', $match->team_away_id)
            ->orderBy('name')
            ->get();

        $players = $homeTeamPlayers->merge($awayTeamPlayers)->sortBy('name');
        $goalkeepers = $this->goalkeepersInMatchLineup($match);

        $eventTypes = [
            'goal' => 'Goal',
            'yellow_card' => 'Yellow Card',
            'red_card' => 'Red Card',
            'substitution' => 'Substitution',
            'penalty' => 'Penalty',
            'foul' => 'Foul',
            'injury' => 'Injury',
            'save' => 'Save (Goalkeeper)',
        ];

        return view('admin.matches.events.create', compact('match', 'teams', 'players', 'goalkeepers', 'eventTypes'));
    }

    /**
     * Store a new match event
     */
    public function store(Request $request, Game $match)
    {
        if ($match->is_cancelled) {
            return redirect()->route('admin.matches.index')
                ->with('error', 'Fixture dinonaktifkan. Event pertandingan tidak dapat diubah.');
        }

        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'player_id' => 'required|exists:players,id',
            'related_player_id' => 'nullable|exists:players,id|different:player_id',
            'conceded_by_goalkeeper_id' => 'required_if:event_type,goal|nullable|integer|exists:players,id',
            'event_type' => 'required|in:goal,yellow_card,red_card,substitution,penalty,foul,injury,assist,save',
            'minute' => 'required|integer|min:1|max:120',
            'description' => 'nullable|string|max:500',
            'is_own_goal' => 'nullable|boolean',
            'is_penalty' => 'nullable|boolean',
        ]);

        // Validasi tambahan: pastikan tim yang dipilih adalah salah satu yang bertanding
        if (!in_array((int) $validated['team_id'], [$match->team_home_id, $match->team_away_id])) {
            return redirect()->back()
                ->with('error', 'Tim yang dipilih tidak terdaftar di pertandingan ini.')
                ->withInput();
        }

        $validated['match_id'] = $match->id;
        // Pastikan nilai boolean terkonversi dengan benar untuk SQL
        $validated['is_own_goal'] = $request->has('is_own_goal') ? 1 : 0;
        $validated['is_penalty'] = $request->has('is_penalty') ? 1 : 0;

        if (!$this->isGoalkeeperEventAllowed($validated['event_type'], (int) $validated['player_id'])) {
            return redirect()->back()
                ->with('error', 'Event save hanya untuk pemain dengan posisi kiper.')
                ->withInput();
        }

        if ($validated['event_type'] === 'goal' && !$this->isValidConcededGoalkeeper(
            $match,
            (int) $validated['team_id'],
            (bool) ($validated['is_own_goal'] ?? false),
            (int) $validated['conceded_by_goalkeeper_id']
        )) {
            return redirect()->back()
                ->withErrors(['conceded_by_goalkeeper_id' => 'Pilih kiper dari lineup tim yang kebobolan. Pastikan lineup pertandingan sudah disimpan.'])
                ->withInput();
        }

        if ($validated['event_type'] !== 'goal') {
            $validated['conceded_by_goalkeeper_id'] = null;
        }

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
        if ($match->is_cancelled) {
            return redirect()->route('admin.matches.index')
                ->with('error', 'Fixture dinonaktifkan. Event pertandingan tidak dapat diubah.');
        }

        if ($event->event_type === 'clean_sheet') {
            return redirect()->route('admin.matches.events.index', $match)
                ->with('error', 'Clean Sheet sekarang dihitung dari lineup dan kiper yang tercatat kebobolan pada setiap gol.');
        }

        $teams = Team::whereIn('id', [$match->team_home_id, $match->team_away_id])->get();

        // Ambil players dari kedua tim
        $homeTeamPlayers = Player::where('team_id', $match->team_home_id)
            ->orderBy('name')
            ->get();

        $awayTeamPlayers = Player::where('team_id', $match->team_away_id)
            ->orderBy('name')
            ->get();

        $players = $homeTeamPlayers->merge($awayTeamPlayers)->sortBy('name');
        $goalkeepers = $this->goalkeepersInMatchLineup($match);

        $eventTypes = [
            'goal' => 'Goal',
            'yellow_card' => 'Yellow Card',
            'red_card' => 'Red Card',
            'substitution' => 'Substitution',
            'penalty' => 'Penalty',
            'foul' => 'Foul',
            'injury' => 'Injury',
            'save' => 'Save (Goalkeeper)',
        ];

        return view('admin.matches.events.edit', compact('match', 'event', 'teams', 'players', 'goalkeepers', 'eventTypes'));
    }

    /**
     * Update an existing event
     */
    public function update(Request $request, Game $match, MatchEvent $event)
    {
        if ($match->is_cancelled) {
            return redirect()->route('admin.matches.index')
                ->with('error', 'Fixture dinonaktifkan. Event pertandingan tidak dapat diubah.');
        }

        if ($event->event_type === 'clean_sheet') {
            return redirect()->route('admin.matches.events.index', $match)
                ->with('error', 'Clean Sheet sekarang dihitung dari lineup dan kiper yang tercatat kebobolan pada setiap gol.');
        }

        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id|in:' . $match->team_home_id . ',' . $match->team_away_id,
            'player_id' => 'required|exists:players,id',
            'related_player_id' => 'nullable|exists:players,id|different:player_id',
            'conceded_by_goalkeeper_id' => 'required_if:event_type,goal|nullable|integer|exists:players,id',
            'event_type' => 'required|in:goal,yellow_card,red_card,substitution,penalty,foul,injury,assist,save',
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

        if ($validated['event_type'] === 'goal' && !$this->isValidConcededGoalkeeper(
            $match,
            (int) $validated['team_id'],
            $validated['is_own_goal'],
            (int) $validated['conceded_by_goalkeeper_id']
        )) {
            return redirect()->back()
                ->withErrors(['conceded_by_goalkeeper_id' => 'Pilih kiper dari lineup tim yang kebobolan. Pastikan lineup pertandingan sudah disimpan.'])
                ->withInput();
        }

        if ($validated['event_type'] !== 'goal') {
            $validated['conceded_by_goalkeeper_id'] = null;
        }

        if (!$this->isGoalkeeperEventAllowed($validated['event_type'], (int) $validated['player_id'])) {
            return redirect()->back()
                ->with('error', 'Event save hanya untuk pemain dengan posisi kiper.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Revert old effects
            if ($oldEventType === 'goal') {
                $this->revertMatchScore($match, $oldTeamId, $oldIsOwnGoal);
            }
            $this->revertPlayerStats($event);

            // Update the event
            $event->update($validated);

            // Apply new effects
            if ($validated['event_type'] === 'goal') {
                $this->updateMatchScore($match, $validated['team_id'], $validated['is_own_goal']);
            }
            $this->updatePlayerStats($event);

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
        if ($match->is_cancelled) {
            return redirect()->route('admin.matches.index')
                ->with('error', 'Fixture dinonaktifkan. Event pertandingan tidak dapat diubah.');
        }

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

            case 'assist':
                $player->increment('assists');
                break;

            case 'yellow_card':
                // Cari kartu kuning lain milik pemain ini di pertandingan yang sama (selain event ini)
                $otherYellow = MatchEvent::where('match_id', $event->match_id)
                    ->where('player_id', $player->id)
                    ->where('event_type', 'yellow_card')
                    ->where('id', '!=', $event->id)
                    ->first();

                if ($otherYellow) {
                    // Jika ini kartu kuning kedua:
                    // 1. Buat event kartu merah otomatis
                    $automaticRedCard = MatchEvent::updateOrCreate(
                        [
                            'match_id' => $event->match_id,
                            'player_id' => $player->id,
                            'event_type' => 'red_card',
                            'description' => 'Kartu Kuning Kedua (Indirect Red)'
                        ],
                        [
                            'team_id' => $event->team_id,
                            'minute' => $event->minute,
                        ]
                    );

                    // 2. Update Statistik: Merah +1, Kuning -1 (membatalkan kuning pertama)
                    if ($automaticRedCard->wasRecentlyCreated) {
                        $player->increment('red_cards');
                    }
                    $player->yellow_cards = max(0, $player->yellow_cards - 1);
                    $player->save();
                } else {
                    $player->increment('yellow_cards');
                }
                break;

            case 'red_card':
                $player->increment('red_cards');
                break;

            case 'penalty':
                $player->increment('penalty_missed');
                break;
            case 'save':
                $player->increment('saves');
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

            case 'assist':
                $player->decrement('assists');
                break;

            case 'yellow_card':
                // Jika menghapus kartu kuning, cek apakah dia punya kartu merah "Indirect Red"
                $autoRed = MatchEvent::where('match_id', $event->match_id)
                    ->where('player_id', $event->player_id)
                    ->where('event_type', 'red_card')
                    ->where('description', 'Kartu Kuning Kedua (Indirect Red)')
                    ->first();

                if ($autoRed) {
                    // Jika yang dihapus adalah kartu kuning yang memicu merah:
                    // 1. Kembalikan status kartu kuning pertama ke statistik
                    $player->yellow_cards += 1;
                    // 2. Kurangi kartu merah dari statistik
                    $player->red_cards = max(0, $player->red_cards - 1);
                    $player->save();
                    // 3. Hapus event merah otomatisnya
                    $autoRed->delete();
                } else {
                    $player->yellow_cards = max(0, $player->yellow_cards - 1);
                    $player->save();
                }
                break;

            case 'red_card':
                $player->decrement('red_cards');
                break;

            case 'penalty':
                $player->decrement('penalty_missed');
                break;
            case 'save':
                $player->decrement('saves');
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
        abort_if($match->is_cancelled, 404);

        $events = $match->events()
            ->with(['player', 'relatedPlayer', 'team'])
            ->orderBy('minute')
            ->get()
            ->groupBy(function ($event) {
                if ($event->minute <= 45) {
                    return 'first_half';
                }
                if ($event->minute <= 90) {
                    return 'second_half';
                }

                return 'extra_time';
            });

        return response()->json([
            'success' => true,
            'data' => $events,
        ]);
    }

    /**
     * Quick add goal
     */
    public function quickAddGoal(Request $request, Game $match)
    {
        if ($match->is_cancelled) {
            return response()->json([
                'success' => false,
                'message' => 'Fixture dinonaktifkan. Event pertandingan tidak dapat diubah.',
            ], 422);
        }

        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'player_id' => 'required|exists:players,id',
            'conceded_by_goalkeeper_id' => 'required|integer|exists:players,id',
            'minute' => 'required|integer|min:1|max:120',
            'is_penalty' => 'boolean',
        ]);

        if (!$this->isValidConcededGoalkeeper(
            $match,
            (int) $request->team_id,
            false,
            (int) $request->conceded_by_goalkeeper_id
        )) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih kiper dari lineup tim yang kebobolan. Pastikan lineup pertandingan sudah disimpan.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $event = MatchEvent::create([
                'match_id' => $match->id,
                'team_id' => $request->team_id,
                'player_id' => $request->player_id,
                'conceded_by_goalkeeper_id' => $request->conceded_by_goalkeeper_id,
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
                'message' => 'Goal berhasil ditambahkan!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Quick add card
     */
    public function quickAddCard(Request $request, Game $match)
    {
        if ($match->is_cancelled) {
            return response()->json([
                'success' => false,
                'message' => 'Fixture dinonaktifkan. Event pertandingan tidak dapat diubah.',
            ], 422);
        }

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
                'message' => 'Kartu berhasil ditambahkan!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Recalculate all player statistics from match_events
     * (perbaikan data yang tidak sinkron antara kolom statistik player dan event)
     *
     * Model agregasi (konsisten dengan updatePlayerStats()):
     * - goals         = jumlah event 'goal' per player
     * - penalty_goals = jumlah event 'goal' dengan is_penalty = 1
     * - assists       = assist eksplisit dan pemain terkait pada event goal
     * - yellow_cards  = satu kartu bila hanya ada satu event kuning dalam match;
     *                   dua atau lebih menghasilkan kartu merah dan nol kartu kuning
     * - red_cards     = jumlah event 'red_card'
     * - event tetap menjadi sumber statistik selama fixture belum dihapus;
     *   fixture juara 3 yang dinonaktifkan seharusnya tidak memiliki event
     */
    public function recalculateStats()
    {
        try {
            DB::beginTransaction();

            // 1. Goals & penalty_goals dari agregasi event
            $goalStats = MatchEvent::selectRaw('player_id,
                    COUNT(*) as goals,
                    SUM(CASE WHEN is_penalty = 1 THEN 1 ELSE 0 END) as penalty_goals')
                ->where('event_type', 'goal')
                ->groupBy('player_id')
                ->get()
                ->keyBy('player_id');

            // 2. Assists from goal events and explicitly recorded assist events.
            $goalAssistStats = MatchEvent::selectRaw('related_player_id as player_id, COUNT(*) as assists')
                ->where('event_type', 'goal')
                ->whereNotNull('related_player_id')
                ->groupBy('related_player_id')
                ->get()
                ->keyBy('player_id');

            $explicitAssistStats = MatchEvent::selectRaw('player_id, COUNT(*) as total')
                ->where('event_type', 'assist')
                ->groupBy('player_id')
                ->get()
                ->keyBy('player_id');

            // 3. Yellow-card contribution per player per match follows the
            // event-entry logic: a second caution is converted into a red.
            $yellowByMatch = MatchEvent::selectRaw('player_id, match_id, COUNT(*) as total')
                ->where('event_type', 'yellow_card')
                ->groupBy('player_id', 'match_id')
                ->get()
                ->groupBy('player_id')
                ->map(fn ($matches) => $matches->where('total', 1)->count());

            // 4. Red cards dari event
            $redStats = MatchEvent::selectRaw('player_id, COUNT(*) as total')
                ->where('event_type', 'red_card')
                ->groupBy('player_id')
                ->get()
                ->keyBy('player_id');

            $penaltyStats = MatchEvent::selectRaw('player_id, COUNT(*) as total')
                ->where('event_type', 'penalty')
                ->groupBy('player_id')
                ->get()
                ->keyBy('player_id');

            $saveStats = MatchEvent::selectRaw('player_id, COUNT(*) as total')
                ->where('event_type', 'save')
                ->groupBy('player_id')
                ->get()
                ->keyBy('player_id');

            // 5. Terapkan ke semua player yang punya event ATAU yang stat-nya
            //    non-zero (supaya player yang event-nya habis/dipindah ikut di-reset ke 0)
            $playerIds = $goalStats->keys()
                ->merge($goalAssistStats->keys())
                ->merge($explicitAssistStats->keys())
                ->merge($yellowByMatch->keys())
                ->merge($redStats->keys())
                ->merge($penaltyStats->keys())
                ->merge($saveStats->keys())
                ->merge(
                    Player::where(function ($q) {
                        $q->where('goals', '>', 0)
                            ->orWhere('penalty_goals', '>', 0)
                            ->orWhere('assists', '>', 0)
                            ->orWhere('yellow_cards', '>', 0)
                            ->orWhere('red_cards', '>', 0)
                            ->orWhere('penalty_missed', '>', 0)
                            ->orWhere('saves', '>', 0)
                            ->orWhere('clean_sheets', '>', 0);
                    })->pluck('id')
                )
                ->unique();

            $updated = 0;
            foreach ($playerIds as $playerId) {
                $newStats = [
                    'goals' => $goalStats->has($playerId) ? (int) $goalStats[$playerId]->goals : 0,
                    'penalty_goals' => $goalStats->has($playerId) ? (int) $goalStats[$playerId]->penalty_goals : 0,
                    'assists' => ($goalAssistStats->has($playerId) ? (int) $goalAssistStats[$playerId]->assists : 0)
                        + ($explicitAssistStats->has($playerId) ? (int) $explicitAssistStats[$playerId]->total : 0),
                    'yellow_cards' => $yellowByMatch->has($playerId) ? (int) $yellowByMatch[$playerId] : 0,
                    'red_cards' => $redStats->has($playerId) ? (int) $redStats[$playerId]->total : 0,
                    'penalty_missed' => $penaltyStats->has($playerId) ? (int) $penaltyStats[$playerId]->total : 0,
                    'saves' => $saveStats->has($playerId) ? (int) $saveStats[$playerId]->total : 0,
                ];

                $player = Player::find($playerId);
                if (! $player) {
                    continue;
                }

                $player->forceFill($newStats);
                if ($player->isDirty()) {
                    $player->save();
                    $updated++;
                }
            }

            app(GoalkeeperCleanSheetService::class)->recalculateAll();

            DB::commit();

            return redirect()->back()
                ->with('success', "Statistik {$updated} player berhasil dihitung ulang dari event dan hasil pertandingan selesai!");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal menghitung ulang statistik: ' . $e->getMessage());
        }
    }

    private function isGoalkeeperEventAllowed(string $eventType, int $playerId): bool
    {
        if ($eventType !== 'save') {
            return true;
        }

        $player = Player::find($playerId);
        return $player
            && GoalkeeperCleanSheetService::isGoalkeeperPosition($player->position);
    }

    private function goalkeepersInMatchLineup(Game $match)
    {
        return DB::table('match_player as mp')
            ->join('players as p', 'p.id', '=', 'mp.player_id')
            ->join('teams as t', 't.id', '=', 'mp.team_id')
            ->where('mp.match_id', $match->id)
            ->whereIn('mp.team_id', [$match->team_home_id, $match->team_away_id])
            ->select([
                'p.id',
                'p.name',
                'mp.team_id as match_team_id',
                't.name as match_team_name',
            ])
            ->selectRaw("COALESCE(NULLIF(mp.position_snapshot, ''), p.position) as position")
            ->get()
            ->filter(fn ($player) => GoalkeeperCleanSheetService::isGoalkeeperPosition($player->position))
            ->values();
    }

    private function isValidConcededGoalkeeper(Game $match, int $eventTeamId, bool $isOwnGoal, int $goalkeeperId): bool
    {
        if (!in_array($eventTeamId, [(int) $match->team_home_id, (int) $match->team_away_id], true)) {
            return false;
        }

        $scoringTeamId = $isOwnGoal
            ? ((int) $eventTeamId === (int) $match->team_home_id ? (int) $match->team_away_id : (int) $match->team_home_id)
            : $eventTeamId;
        $concedingTeamId = $scoringTeamId === (int) $match->team_home_id
            ? (int) $match->team_away_id
            : (int) $match->team_home_id;

        return GoalkeeperCleanSheetService::isGoalkeeperInMatchLineup(
            (int) $match->id,
            $concedingTeamId,
            $goalkeeperId
        );
    }
}
