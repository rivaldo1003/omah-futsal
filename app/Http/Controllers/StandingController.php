<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Standing;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StandingController extends Controller
{
    /**
     * Display a listing of the resource (public view).
     */
    public function publicIndex(Request $request)
    {
        // Ambil semua tournaments untuk dropdown
        $tournaments = Tournament::orderBy('created_at', 'desc')->get();

        // Ambil tournament yang dipilih dari request atau default
        $tournamentId = $request->get('tournament_id');

        if ($tournamentId) {
            $selectedTournament = Tournament::find($tournamentId);
        } else {
            // Default ke tournament ongoing atau terbaru
            $selectedTournament = Tournament::where('status', 'ongoing')->first();
            if (!$selectedTournament) {
                $selectedTournament = Tournament::where('status', 'upcoming')->first();
            }
            if (!$selectedTournament) {
                $selectedTournament = Tournament::latest()->first();
            }
        }

        // Inisialisasi variabel
        $tournamentType = $selectedTournament ? $selectedTournament->type : null;
        $groupedStandingsWithPosition = collect();
        $flatStandings = collect();
        $knockoutBracket = null;
        $tournamentStats = [];

        // Jika ada tournament yang dipilih
        if ($selectedTournament) {
            // Cek tipe tournament
            if (in_array($tournamentType, ['league', 'group_knockout'])) {
                // Untuk league dan group_knockout: tampilkan standings grup
                $standings = $this->getCompleteStandingsWithAllTeams($selectedTournament->id);

                // Group standings by group
                $groupedStandings = $standings->groupBy('group_name');

                // Hitung position per group
                foreach ($groupedStandings as $group => $groupStandings) {
                    $position = 1;
                    foreach ($groupStandings as $standing) {
                        // Tambahkan calculated fields
                        $standing->played = $standing->matches_played;
                        $standing->won = $standing->wins;
                        $standing->drawn = $standing->draws;
                        $standing->lost = $standing->losses;
                        $standing->position = $position;
                        $position++;
                    }
                    $groupedStandingsWithPosition->put($group, $groupStandings);
                }

                // Untuk flat standings dengan position
                $flatStandings = $standings->map(function ($standing) {
                    $standing->played = $standing->matches_played;
                    $standing->won = $standing->wins;
                    $standing->drawn = $standing->draws;
                    $standing->lost = $standing->losses;

                    return $standing;
                });

            } elseif ($tournamentType === 'knockout') {
                // Untuk knockout: tampilkan bracket
                $knockoutBracket = $this->getKnockoutBracketData($selectedTournament->id);
            }

            // Hitung statistik tournament
            $tournamentStats = $this->calculateTournamentStats($selectedTournament, $flatStandings);
        }

        return view('standings.index', compact(
            'groupedStandingsWithPosition',
            'flatStandings',
            'selectedTournament',
            'tournaments',
            'tournamentStats',
            'tournamentType',
            'knockoutBracket'
        ));
    }

    /**
     * Get complete standings including teams that haven't played yet
     */
    private function getCompleteStandingsWithAllTeams($tournamentId)
    {
        // 1. Ambil semua tim yang terdaftar di tournament ini dari team_tournament
        $teamsInTournament = DB::table('team_tournament')
            ->join('teams', 'team_tournament.team_id', '=', 'teams.id')
            ->where('team_tournament.tournament_id', $tournamentId)
            ->select(
                'teams.id',
                'teams.name',
                'teams.logo',
                'team_tournament.group_name',
                'team_tournament.seed'
            )
            ->get();

        if ($teamsInTournament->isEmpty()) {
            return collect();
        }

        // 2. Ambil standings yang sudah ada dan load team relationship
        $existingStandings = Standing::with('team')
            ->where('tournament_id', $tournamentId)
            ->get()
            ->keyBy('team_id');

        // 3. Gabungkan semua tim dengan data standings
        $allStandings = collect();

        foreach ($teamsInTournament as $teamTournament) {
            // Cek apakah tim sudah punya standings
            if ($existingStandings->has($teamTournament->id)) {
                $standing = $existingStandings[$teamTournament->id];

                // Pastikan team relation sudah loaded
                if (!$standing->relationLoaded('team')) {
                    $standing->load('team');
                }

                // Tambahkan flag
                $standing->is_default = false;
            } else {
                // Buat object custom untuk tim yang belum bermain
                // JANGAN gunakan model Standing langsung
                $standing = (object) [
                    'id' => null,
                    'team_id' => $teamTournament->id,
                    'tournament_id' => $tournamentId,
                    'group_name' => $teamTournament->group_name,
                    'matches_played' => 0,
                    'wins' => 0,
                    'draws' => 0,
                    'losses' => 0,
                    'goals_for' => 0,
                    'goals_against' => 0,
                    'goal_difference' => 0,
                    'points' => 0,
                    'form' => null,
                    'created_at' => null,
                    'updated_at' => null,
                    'team' => (object) [  // Object biasa, bukan Eloquent relation
                        'id' => $teamTournament->id,
                        'name' => $teamTournament->name,
                        'logo' => $teamTournament->logo,
                    ],
                    'is_default' => true,
                ];
            }

            $allStandings->push($standing);
        }

        // 4. Sortir standings per group
        $grouped = $allStandings->groupBy('group_name');

        $sortedStandings = collect();

        foreach ($grouped as $group => $groupStandings) {
            // Sort by: points > GD > GF > wins
            $sortedGroup = $groupStandings->sortByDesc(function ($standing) {
                // Jika ini object custom
                if (isset($standing->is_default) && $standing->is_default) {
                    return [
                        $standing->points,
                        $standing->goal_difference,
                        $standing->goals_for,
                        $standing->wins,
                    ];
                }

                // Jika ini model Standing
                return [
                    $standing->points,
                    $standing->goal_difference,
                    $standing->goals_for,
                    $standing->wins,
                ];
            });

            $sortedStandings = $sortedStandings->merge($sortedGroup);
        }

        return $sortedStandings;
    }

    /**
     * Get knockout bracket data
     */
    private function getKnockoutBracketData($tournamentId)
    {
        try {
            // Ambil semua match knockout
            $matches = Game::with(['homeTeam', 'awayTeam'])
                ->where('tournament_id', $tournamentId)
                ->whereIn('round_type', [
                    'round_of_32',
                    'round_of_16',
                    'quarterfinal',
                    'semifinal',
                    'final',
                    'third_place'
                ])
                ->orderByRaw("
                    CASE 
                        WHEN round_type = 'round_of_32' THEN 1
                        WHEN round_type = 'round_of_16' THEN 2
                        WHEN round_type = 'quarterfinal' THEN 3
                        WHEN round_type = 'semifinal' THEN 4
                        WHEN round_type = 'third_place' THEN 5
                        WHEN round_type = 'final' THEN 6
                        ELSE 7
                    END
                ")
                ->orderBy('match_date')
                ->orderBy('time_start')
                ->get();

            // Organize matches by round
            $bracket = [
                'round_of_32' => [],
                'round_of_16' => [],
                'quarterfinal' => [],
                'semifinal' => [],
                'third_place' => [],
                'final' => []
            ];

            foreach ($matches as $match) {
                $round = $match->round_type;
                if (isset($bracket[$round])) {
                    $bracket[$round][] = [
                        'id' => $match->id,
                        'home_team' => $match->homeTeam ? [
                            'id' => $match->homeTeam->id,
                            'name' => $match->homeTeam->name,
                            'logo' => $match->homeTeam->logo,
                            'score' => $match->home_score
                        ] : null,
                        'away_team' => $match->awayTeam ? [
                            'id' => $match->awayTeam->id,
                            'name' => $match->awayTeam->name,
                            'logo' => $match->awayTeam->logo,
                            'score' => $match->away_score
                        ] : null,
                        'status' => $match->status,
                        'date' => $match->match_date,
                        'time' => $match->time_start,
                        'winner' => $this->getMatchWinner($match)
                    ];
                }
            }

            // Filter out empty rounds
            $bracket = array_filter($bracket, function ($matches) {
                return !empty($matches);
            });

            return $bracket;

        } catch (\Exception $e) {
            \Log::error('Error getting knockout bracket: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Determine match winner
     */
    private function getMatchWinner($match)
    {
        if ($match->status !== 'completed') {
            return null;
        }

        if ($match->home_score > $match->away_score) {
            return 'home';
        } elseif ($match->away_score > $match->home_score) {
            return 'away';
        } else {
            return 'draw';
        }
    }

    /**
     * Calculate tournament statistics
     */
    private function calculateTournamentStats($tournament, $standings)
    {
        if (!$tournament) {
            return [
                'total_matches' => 0,
                'total_goals' => 0,
                'avg_goals_per_match' => 0,
                'top_team' => null,
                'most_goals_for' => null,
                'least_goals_against' => null,
            ];
        }

        // Hitung total pertandingan berdasarkan tournament
        $totalMatches = Game::where('tournament_id', $tournament->id)
            ->where('status', 'completed')
            ->count();

        // Hitung total gol dari matches yang completed
        $homeGoals = Game::where('tournament_id', $tournament->id)
            ->where('status', 'completed')
            ->sum('home_score');

        $awayGoals = Game::where('tournament_id', $tournament->id)
            ->where('status', 'completed')
            ->sum('away_score');

        $totalGoals = $homeGoals + $awayGoals;

        // Team dengan posisi teratas (pertama di standings)
        $topTeam = $standings->first();

        // Team dengan gol terbanyak (hanya yang sudah bermain)
        $mostGoalsFor = $standings->where('goals_for', '>', 0)
            ->sortByDesc('goals_for')
            ->first();

        // Team dengan gol kemasukan paling sedikit (hanya yang sudah bermain)
        $leastGoalsAgainst = $standings->where('matches_played', '>', 0)
            ->sortBy('goals_against')
            ->first();

        return [
            'total_matches' => $totalMatches,
            'total_goals' => $totalGoals,
            'avg_goals_per_match' => $totalMatches > 0 ? round($totalGoals / $totalMatches, 2) : 0,
            'top_team' => $topTeam,
            'most_goals_for' => $mostGoalsFor,
            'least_goals_against' => $leastGoalsAgainst,
        ];
    }

    /**
     * Display standings for admin
     */
    public function adminIndex(Request $request)
    {
        // Ambil semua tournaments untuk dropdown
        $tournaments = Tournament::orderBy('created_at', 'desc')->get();

        // Ambil tournament yang dipilih dari request atau default
        $tournamentId = $request->get('tournament_id');

        if ($tournamentId) {
            $selectedTournament = Tournament::find($tournamentId);
        } else {
            // Default ke tournament ongoing atau terbaru
            $selectedTournament = Tournament::where('status', 'ongoing')->first();
            if (!$selectedTournament) {
                $selectedTournament = Tournament::where('status', 'upcoming')->first();
            }
            if (!$selectedTournament) {
                $selectedTournament = Tournament::latest()->first();
            }
        }

        $tournamentType = $selectedTournament ? $selectedTournament->type : null;
        $groupedStandingsWithPosition = collect();
        $knockoutBracket = null;

        // Jika ada tournament yang dipilih
        if ($selectedTournament) {
            // Cek tipe tournament
            if (in_array($tournamentType, ['league', 'group_knockout'])) {
                // Untuk league dan group_knockout: ambil standings dengan team relation
                $standings = Standing::with('team')
                    ->where('tournament_id', $selectedTournament->id)
                    ->orderBy('group_name')
                    ->orderByDesc('points')
                    ->orderByDesc('goal_difference')
                    ->orderByDesc('goals_for')
                    ->orderByDesc('wins')
                    ->get();

                // Ambil semua tim di tournament untuk memastikan semua tim ditampilkan
                $teamsInTournament = DB::table('team_tournament')
                    ->join('teams', 'team_tournament.team_id', '=', 'teams.id')
                    ->where('team_tournament.tournament_id', $selectedTournament->id)
                    ->select(
                        'teams.id',
                        'teams.name',
                        'teams.logo',
                        'team_tournament.group_name',
                        'team_tournament.seed'
                    )
                    ->get();

                // Gabungkan dengan tim yang belum ada di standings
                $allTeamsWithStandings = collect();

                foreach ($teamsInTournament as $team) {
                    $existingStanding = $standings->where('team_id', $team->id)->first();

                    if ($existingStanding) {
                        $allTeamsWithStandings->push($existingStanding);
                    } else {
                        // Buat object custom, bukan model Standing
                        $customStanding = (object) [
                            'id' => null,
                            'tournament_id' => $selectedTournament->id,
                            'team_id' => $team->id,
                            'group_name' => $team->group_name,
                            'matches_played' => 0,
                            'wins' => 0,
                            'draws' => 0,
                            'losses' => 0,
                            'goals_for' => 0,
                            'goals_against' => 0,
                            'goal_difference' => 0,
                            'points' => 0,
                            'form' => null,
                            'team' => (object) [
                                'id' => $team->id,
                                'name' => $team->name,
                                'logo' => $team->logo,
                            ],
                            'is_default' => true,
                        ];

                        $allTeamsWithStandings->push($customStanding);
                    }
                }

                // Group standings by group
                $groupedStandings = $allTeamsWithStandings->groupBy('group_name');

                // Add position to each group
                foreach ($groupedStandings as $group => $groupStandings) {
                    $position = 1;
                    $sortedGroup = $groupStandings->sortByDesc(function ($standing) {
                        return [
                            $standing->points,
                            $standing->goal_difference,
                            $standing->goals_for,
                            $standing->wins,
                        ];
                    });

                    foreach ($sortedGroup as $standing) {
                        $standing->position = $position;
                        $position++;
                    }

                    $groupedStandingsWithPosition[$group] = $sortedGroup;
                }

            } elseif ($tournamentType === 'knockout') {
                // Untuk knockout: ambil bracket data untuk admin
                $knockoutBracket = $this->getKnockoutBracketData($selectedTournament->id);
            }
        }

        return view('admin.standings.index', compact(
            'tournaments',
            'selectedTournament',
            'tournamentType',
            'groupedStandingsWithPosition',
            'knockoutBracket'
        ));
    }

    /**
     * Recalculate standings for a specific tournament
     */
    public function recalculate(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
        ]);

        try {
            DB::beginTransaction();

            $tournamentId = $request->tournament_id;

            // 1. Hapus semua standings untuk tournament ini
            Standing::where('tournament_id', $tournamentId)->delete();

            // 2. Ambil semua completed group matches untuk tournament ini
            $completedMatches = Game::where('tournament_id', $tournamentId)
                ->where('round_type', 'group')
                ->where('status', 'completed')
                ->get();

            // 3. Hitung ulang standings dari awal
            foreach ($completedMatches as $match) {
                $this->processMatchForStandings($match);
            }

            // 4. Ambil semua tim di tournament untuk memastikan semua tim punya record
            $teamsInTournament = DB::table('team_tournament')
                ->where('tournament_id', $tournamentId)
                ->pluck('team_id');

            foreach ($teamsInTournament as $teamId) {
                Standing::firstOrCreate(
                    [
                        'tournament_id' => $tournamentId,
                        'team_id' => $teamId,
                    ],
                    [
                        'group_name' => DB::table('team_tournament')
                            ->where('tournament_id', $tournamentId)
                            ->where('team_id', $teamId)
                            ->value('group_name'),
                        'matches_played' => 0,
                        'wins' => 0,
                        'draws' => 0,
                        'losses' => 0,
                        'goals_for' => 0,
                        'goals_against' => 0,
                        'goal_difference' => 0,
                        'points' => 0,
                        'form' => null,
                    ]
                );
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Standings recalculated successfully for tournament!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error recalculating standings: ' . $e->getMessage());
        }
    }

    /**
     * Process a single match for standings calculation
     */
    /**
     * Process a single match for standings calculation
     */
    private function processMatchForStandings(Game $match)
    {
        if ($match->status !== 'completed' || $match->round_type !== 'group') {
            return;
        }

        $tournamentId = $match->tournament_id;
        $homeTeamId = $match->team_home_id;
        $awayTeamId = $match->team_away_id;
        $groupName = $match->group_name;

        // Cari atau buat standings untuk home team
        $homeStanding = Standing::firstOrCreate(
            [
                'tournament_id' => $tournamentId,
                'team_id' => $homeTeamId,
            ],
            [
                'group_name' => $groupName,
                'matches_played' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'points' => 0,
                'form' => null,
            ]
        );

        // Cari atau buat standings untuk away team
        $awayStanding = Standing::firstOrCreate(
            [
                'tournament_id' => $tournamentId,
                'team_id' => $awayTeamId,
            ],
            [
                'group_name' => $groupName,
                'matches_played' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'points' => 0,
                'form' => null,
            ]
        );

        // Update group name jika berbeda
        $homeStanding->group_name = $groupName;
        $awayStanding->group_name = $groupName;

        // Update matches played
        $homeStanding->matches_played += 1;
        $awayStanding->matches_played += 1;

        // Update goals
        $homeStanding->goals_for += $match->home_score;
        $homeStanding->goals_against += $match->away_score;
        $awayStanding->goals_for += $match->away_score;
        $awayStanding->goals_against += $match->home_score;

        // Update win/draw/loss
        if ($match->home_score > $match->away_score) {
            // Home team wins
            $homeStanding->wins += 1;
            $homeStanding->points += 3;
            $homeStanding->updateForm('W');
            $awayStanding->losses += 1;
            $awayStanding->updateForm('L');
        } elseif ($match->home_score < $match->away_score) {
            // Away team wins
            $awayStanding->wins += 1;
            $awayStanding->points += 3;
            $awayStanding->updateForm('W');
            $homeStanding->losses += 1;
            $homeStanding->updateForm('L');
        } else {
            // Draw
            $homeStanding->draws += 1;
            $homeStanding->points += 1;
            $homeStanding->updateForm('D');
            $awayStanding->draws += 1;
            $awayStanding->points += 1;
            $awayStanding->updateForm('D');
        }

        // HAPUS baris ini - goal_difference akan dihitung otomatis:
        // $homeStanding->goal_difference = $homeStanding->goals_for - $homeStanding->goals_against;
        // $awayStanding->goal_difference = $awayStanding->goals_for - $awayStanding->goals_against;

        // Simpan
        $homeStanding->save();
        $awayStanding->save();
    }

    // ... (method-method lainnya tetap sama seperti controller Anda)
}