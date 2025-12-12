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
            if (! $selectedTournament) {
                $selectedTournament = Tournament::where('status', 'upcoming')->first();
            }
            if (! $selectedTournament) {
                $selectedTournament = Tournament::latest()->first();
            }
        }

        // Jika ada tournament yang dipilih, ambil standings dengan SEMUA tim
        if ($selectedTournament) {
            $standings = $this->getCompleteStandingsWithAllTeams($selectedTournament->id);
        } else {
            $standings = collect();
        }

        // Group standings by group
        $groupedStandings = $standings->groupBy('group_name');

        // Hitung position per group
        $groupedStandingsWithPosition = collect();
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

        // Hitung statistik tournament
        $tournamentStats = $this->calculateTournamentStats($selectedTournament, $flatStandings);

        return view('standings.index', compact(
            'groupedStandingsWithPosition',
            'flatStandings',
            'selectedTournament',
            'tournaments',
            'tournamentStats'
        ));
    }

    /**
     * Get complete standings including teams that haven't played yet
     */
    private function getCompleteStandingsWithAllTeams($tournamentId)
    {
        // 1. Ambil semua tim yang terdaftar di tournament ini dari team_tournament
        // SELECT teams.* untuk dapatkan semua kolom termasuk logo
        $teamsInTournament = DB::table('team_tournament')
            ->join('teams', 'team_tournament.team_id', '=', 'teams.id')
            ->where('team_tournament.tournament_id', $tournamentId)
            ->select(
                'teams.*', // Ambil SEMUA kolom dari teams termasuk logo
                'team_tournament.group_name',
                'team_tournament.seed'
            )
            ->get();

        if ($teamsInTournament->isEmpty()) {
            return collect();
        }

        // 2. Ambil standings yang sudah ada
        $existingStandings = Standing::where('tournament_id', $tournamentId)
            ->with('team') // Load team dengan logo
            ->get()
            ->keyBy('team_id');

        // 3. Gabungkan semua tim dengan data standings
        $allStandings = collect();

        foreach ($teamsInTournament as $teamTournament) {
            // Cek apakah tim sudah punya standings
            if ($existingStandings->has($teamTournament->id)) {
                $standing = $existingStandings[$teamTournament->id];
                $standing->is_default = false;
            } else {
                // Buat default standing untuk tim yang belum bermain
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
                    'created_at' => null,
                    'updated_at' => null,
                    'team' => (object) [
                        'id' => $teamTournament->id,
                        'name' => $teamTournament->name,
                        'logo' => $teamTournament->logo, // TAMBAHKAN LOGO
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
     * Calculate tournament statistics
     */
    private function calculateTournamentStats($tournament, $standings)
    {
        if (! $tournament) {
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
            if (! $selectedTournament) {
                $selectedTournament = Tournament::where('status', 'upcoming')->first();
            }
            if (! $selectedTournament) {
                $selectedTournament = Tournament::latest()->first();
            }
        }

        // Jika ada tournament yang dipilih, ambil standings
        if ($selectedTournament) {
            // Ambil standings yang sudah ada di database
            $standings = Standing::with([
                'team' => function ($query) {
                    $query->select('id', 'name', 'logo'); // TAMBAHKAN LOGO
                },
            ])
                ->where('tournament_id', $selectedTournament->id)
                ->orderBy('group_name')
                ->orderByDesc('points')
                ->orderByDesc('goal_difference')
                ->orderByDesc('goals_for')
                ->orderByDesc('wins')
                ->get();

            // Ambil semua tim di tournament untuk memastikan semua tim ditampilkan
            // SELECT teams.* untuk dapatkan semua kolom termasuk logo
            $teamsInTournament = DB::table('team_tournament')
                ->join('teams', 'team_tournament.team_id', '=', 'teams.id')
                ->where('team_tournament.tournament_id', $selectedTournament->id)
                ->select(
                    'teams.*', // Ambil SEMUA kolom dari teams
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
                    // Buat default standing untuk tim yang belum bermain
                    $defaultStanding = new Standing([
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
                    ]);
                    $defaultStanding->team = (object) [
                        'id' => $team->id,
                        'name' => $team->name,
                        'logo' => $team->logo, // TAMBAHKAN LOGO
                    ];
                    $defaultStanding->is_default = true;

                    $allTeamsWithStandings->push($defaultStanding);
                }
            }

            // Group standings by group
            $groupedStandings = $allTeamsWithStandings->groupBy('group_name');

            // Add position to each group
            $groupedStandingsWithPosition = collect();
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

        } else {
            $standings = collect();
            $groupedStandingsWithPosition = collect();
            $allTeamsWithStandings = collect();
        }

        return view('admin.standings.index', compact(
            'tournaments',
            'selectedTournament',
            'standings',
            'groupedStandingsWithPosition',
            'allTeamsWithStandings'
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
                    ]
                );
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Standings recalculated successfully for tournament!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error recalculating standings: '.$e->getMessage());
        }
    }

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
                'goal_difference' => 0,
                'points' => 0,
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
                'goal_difference' => 0,
                'points' => 0,
            ]
        );

        // Update group name jika berbeda
        $homeStanding->group_name = $groupName;
        $awayStanding->group_name = $groupName;

        // Reset matches played (kita akan hitung ulang)
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
            $awayStanding->losses += 1;
        } elseif ($match->home_score < $match->away_score) {
            // Away team wins
            $awayStanding->wins += 1;
            $awayStanding->points += 3;
            $homeStanding->losses += 1;
        } else {
            // Draw
            $homeStanding->draws += 1;
            $homeStanding->points += 1;
            $awayStanding->draws += 1;
            $awayStanding->points += 1;
        }

        // Update goal difference
        $homeStanding->goal_difference = $homeStanding->goals_for - $homeStanding->goals_against;
        $awayStanding->goal_difference = $awayStanding->goals_for - $awayStanding->goals_against;

        // Simpan
        $homeStanding->save();
        $awayStanding->save();
    }

    /**
     * Reset standings for a tournament (delete all)
     */
    public function reset(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
        ]);

        try {
            Standing::where('tournament_id', $request->tournament_id)->delete();

            return redirect()->back()
                ->with('success', 'Standings reset successfully! All standings data has been deleted.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error resetting standings: '.$e->getMessage());
        }
    }

    /**
     * Manual update of standings (for admin)
     */
    public function manualUpdate(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'team_id' => 'required|exists:teams,id',
            'matches_played' => 'required|integer|min:0',
            'wins' => 'required|integer|min:0',
            'draws' => 'required|integer|min:0',
            'losses' => 'required|integer|min:0',
            'goals_for' => 'required|integer|min:0',
            'goals_against' => 'required|integer|min:0',
            'points' => 'required|integer|min:0',
        ]);

        try {
            // Validasi konsistensi data
            $totalMatches = $request->wins + $request->draws + $request->losses;

            if ($totalMatches != $request->matches_played) {
                return redirect()->back()
                    ->with('error', 'Total matches (wins + draws + losses) must equal matches played.');
            }

            // Hitung goal difference
            $goalDifference = $request->goals_for - $request->goals_against;

            // Update atau create standing
            $standing = Standing::updateOrCreate(
                [
                    'tournament_id' => $request->tournament_id,
                    'team_id' => $request->team_id,
                ],
                [
                    'group_name' => DB::table('team_tournament')
                        ->where('tournament_id', $request->tournament_id)
                        ->where('team_id', $request->team_id)
                        ->value('group_name') ?? 'A',
                    'matches_played' => $request->matches_played,
                    'wins' => $request->wins,
                    'draws' => $request->draws,
                    'losses' => $request->losses,
                    'goals_for' => $request->goals_for,
                    'goals_against' => $request->goals_against,
                    'goal_difference' => $goalDifference,
                    'points' => $request->points,
                ]
            );

            return redirect()->back()
                ->with('success', 'Standing updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating standing: '.$e->getMessage());
        }
    }

    /**
     * Verify standings consistency
     */
    public function verify(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
        ]);

        try {
            $tournamentId = $request->tournament_id;
            $inconsistencies = [];

            // 1. Check if standings exist for all teams in tournament
            $teamsInTournament = DB::table('team_tournament')
                ->where('tournament_id', $tournamentId)
                ->pluck('team_id');

            $teamsWithStandings = Standing::where('tournament_id', $tournamentId)
                ->pluck('team_id');

            $missingTeams = $teamsInTournament->diff($teamsWithStandings);

            if ($missingTeams->isNotEmpty()) {
                $inconsistencies[] = [
                    'type' => 'missing_standings',
                    'message' => 'Some teams are missing standings records',
                    'details' => $missingTeams->map(function ($teamId) {
                        $team = Team::find($teamId);

                        return $team ? $team->name : "Team ID: $teamId";
                    })->toArray(),
                ];
            }

            // 2. Check standings data consistency
            $standings = Standing::where('tournament_id', $tournamentId)->get();

            foreach ($standings as $standing) {
                // Check if matches_played equals wins + draws + losses
                $calculatedMatches = $standing->wins + $standing->draws + $standing->losses;

                if ($standing->matches_played != $calculatedMatches) {
                    $inconsistencies[] = [
                        'type' => 'matches_mismatch',
                        'message' => "Matches played mismatch for {$standing->team->name}",
                        'details' => "Matches played: {$standing->matches_played}, Calculated: {$calculatedMatches}",
                    ];
                }

                // Check if goal difference is correct
                $calculatedGD = $standing->goals_for - $standing->goals_against;

                if ($standing->goal_difference != $calculatedGD) {
                    $inconsistencies[] = [
                        'type' => 'goal_difference_mismatch',
                        'message' => "Goal difference mismatch for {$standing->team->name}",
                        'details' => "GD: {$standing->goal_difference}, Calculated: {$calculatedGD}",
                    ];
                }

                // Check if points calculation is correct
                $calculatedPoints = ($standing->wins * 3) + ($standing->draws * 1);

                if ($standing->points != $calculatedPoints) {
                    $inconsistencies[] = [
                        'type' => 'points_mismatch',
                        'message' => "Points mismatch for {$standing->team->name}",
                        'details' => "Points: {$standing->points}, Calculated: {$calculatedPoints}",
                    ];
                }
            }

            // 3. Check matches vs standings consistency
            $completedMatches = Game::where('tournament_id', $tournamentId)
                ->where('round_type', 'group')
                ->where('status', 'completed')
                ->get();

            foreach ($completedMatches as $match) {
                $homeStanding = $standings->where('team_id', $match->team_home_id)->first();
                $awayStanding = $standings->where('team_id', $match->team_away_id)->first();

                if (! $homeStanding || ! $awayStanding) {
                    $inconsistencies[] = [
                        'type' => 'match_no_standing',
                        'message' => 'Match has no corresponding standing records',
                        'details' => "Match ID: {$match->id}",
                    ];
                }
            }

            // 4. Check group consistency
            foreach ($standings as $standing) {
                $teamGroup = DB::table('team_tournament')
                    ->where('tournament_id', $tournamentId)
                    ->where('team_id', $standing->team_id)
                    ->value('group_name');

                if ($teamGroup != $standing->group_name) {
                    $inconsistencies[] = [
                        'type' => 'group_mismatch',
                        'message' => "Group mismatch for {$standing->team->name}",
                        'details' => "Standing Group: {$standing->group_name}, Team-Tournament Group: {$teamGroup}",
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'inconsistencies' => $inconsistencies,
                'total_issues' => count($inconsistencies),
                'tournament_id' => $tournamentId,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error verifying standings: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Fix standings inconsistencies automatically
     */
    public function fixInconsistencies(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
        ]);

        try {
            DB::beginTransaction();

            $tournamentId = $request->tournament_id;
            $fixedIssues = [];

            // 1. Ensure all teams have standings records
            $teamsInTournament = DB::table('team_tournament')
                ->where('tournament_id', $tournamentId)
                ->get();

            foreach ($teamsInTournament as $teamTournament) {
                $standing = Standing::firstOrCreate(
                    [
                        'tournament_id' => $tournamentId,
                        'team_id' => $teamTournament->team_id,
                    ],
                    [
                        'group_name' => $teamTournament->group_name,
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

                // Update group name if it's wrong
                if ($standing->group_name != $teamTournament->group_name) {
                    $standing->group_name = $teamTournament->group_name;
                    $standing->save();
                    $fixedIssues[] = "Updated group for team ID: {$teamTournament->team_id}";
                }
            }

            // 2. Recalculate all standings from matches
            $completedMatches = Game::where('tournament_id', $tournamentId)
                ->where('round_type', 'group')
                ->where('status', 'completed')
                ->get();

            // Reset all standings to zero
            Standing::where('tournament_id', $tournamentId)->update([
                'matches_played' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'points' => 0,
            ]);

            // Recalculate from matches
            foreach ($completedMatches as $match) {
                $this->processMatchForStandings($match);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Standings inconsistencies fixed successfully!',
                'fixed_issues' => $fixedIssues,
                'total_fixed' => count($fixedIssues),
                'recalculated_matches' => $completedMatches->count(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error fixing inconsistencies: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export standings to CSV
     */
    public function export(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
        ]);

        try {
            $tournament = Tournament::find($request->tournament_id);
            $standings = Standing::with('team')
                ->where('tournament_id', $request->tournament_id)
                ->orderBy('group_name')
                ->orderByDesc('points')
                ->orderByDesc('goal_difference')
                ->orderByDesc('goals_for')
                ->orderByDesc('wins')
                ->get();

            $filename = "standings_{$tournament->slug}_".date('Y-m-d').'.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function () use ($standings, $tournament) {
                $file = fopen('php://output', 'w');

                // Add BOM for UTF-8
                fwrite($file, "\xEF\xBB\xBF");

                // Header
                fputcsv($file, ["Tournament Standings: {$tournament->name}"]);
                fputcsv($file, ['Generated: '.date('Y-m-d H:i:s')]);
                fputcsv($file, []); // Empty line

                // Column headers
                fputcsv($file, ['Group', 'Position', 'Team', 'MP', 'W', 'D', 'L', 'GF', 'GA', 'GD', 'PTS']);

                // Data
                $currentGroup = null;
                $position = 1;

                foreach ($standings as $standing) {
                    if ($currentGroup !== $standing->group_name) {
                        $currentGroup = $standing->group_name;
                        $position = 1;
                        fputcsv($file, []); // Empty line between groups
                        fputcsv($file, ["GROUP {$currentGroup}"]);
                    }

                    fputcsv($file, [
                        $standing->group_name,
                        $position,
                        $standing->team->name,
                        $standing->matches_played,
                        $standing->wins,
                        $standing->draws,
                        $standing->losses,
                        $standing->goals_for,
                        $standing->goals_against,
                        $standing->goal_difference,
                        $standing->points,
                    ]);

                    $position++;
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error exporting standings: '.$e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Standing $standing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Standing $standing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Standing $standing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Standing $standing)
    {
        //
    }
}
