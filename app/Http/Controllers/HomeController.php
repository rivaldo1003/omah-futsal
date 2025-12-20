<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\HeroSetting;
use App\Models\Player;
use App\Models\Standing;
use App\Models\Team;
use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    private function getFallbackTopScorers()
    {
        return Player::with('team')
            ->orderBy('goals', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($player) {
                $player->goals = $player->goals ?? 0;
                $player->yellow_cards = $player->yellow_cards ?? 0;
                $player->red_cards = $player->red_cards ?? 0;

                return $player;
            });
    }

    public function index()
    {
        // ==============================
        // GET HERO SETTINGS
        // ==============================
        $heroSetting = HeroSetting::first();

        // Jika tidak ada setting, buat default
        if (!$heroSetting) {
            $heroSetting = (object) [
                'title' => 'OFS Champions League 2025',
                'subtitle' => 'The ultimate futsal championship featuring elite teams competing for glory',
                'is_active' => true,
                'background_type' => 'gradient',
                'background_color' => null,
                'background_image' => null,
                'text_color' => '#ffffff',
            ];
        }

        // DEBUG: Cek semua tournaments
        $allTournaments = Tournament::all();

        // Get active tournament - coba ambil tournament apapun yang ada
        $activeTournament = Tournament::where('status', 'ongoing')->first();

        // Jika tidak ada tournament ongoing, ambil yang upcoming
        if (!$activeTournament) {
            $activeTournament = Tournament::where('status', 'upcoming')->first();
        }

        // Jika masih tidak ada, ambil tournament apapun
        if (!$activeTournament) {
            $activeTournament = Tournament::first();
        }

        // ==============================
        // GET TEAMS DATA
        // ==============================
        $teams = Team::withCount(['players', 'tournaments'])
        ->with(['players' => function($query) {
            // Ambil SEMUA players, jangan dibatasi di sini
            $query->orderBy('goals', 'desc')
                  ->orderBy('position')
                  ->orderBy('name');
        }])
        ->where('status', 'active')
        ->orderBy('name')
        ->limit(value: 12)
        ->get();

        // Get today's matches
        $todayMatches = Game::with(['homeTeam', 'awayTeam'])
            ->whereDate('match_date', Carbon::today())
            ->orderBy('time_start')
            ->get();

        // Get upcoming matches (next 5)
        $upcomingMatches = Game::with(['homeTeam', 'awayTeam'])
            ->where('status', 'upcoming')
            ->whereDate('match_date', '>=', Carbon::today())
            ->orderBy('match_date')
            ->orderBy('time_start')
            ->limit(5)
            ->get();

        // Get recent results (last 5 completed matches) - Filter per tournament
        if ($activeTournament) {
            $recentResults = Game::with(['homeTeam', 'awayTeam', 'events.player'])
                ->where('tournament_id', $activeTournament->id)
                ->where('status', 'completed')
                ->orderBy('match_date', 'desc')
                ->orderBy('time_start', 'desc')
                ->limit(5)
                ->get();
        } else {
            $recentResults = Game::with(['homeTeam', 'awayTeam', 'events.player'])
                ->where('status', 'completed')
                ->orderBy('match_date', 'desc')
                ->orderBy('time_start', 'desc')
                ->limit(5)
                ->get();
        }

        // ==============================
        // GET TOP SCORERS PER TOURNAMENT - DENGAN PERINGKAT YANG SAMA
        // ==============================
        $topScorers = collect();

        if ($activeTournament) {
            try {
                $tournamentId = $activeTournament->id;

                // Hitung gol, assist, dan kartu per pemain dalam tournament
                $playerStats = DB::table('players')
                    ->select([
                        'players.id',
                        'players.name',
                        'players.jersey_number',
                        'players.position',
                        'players.team_id',
                        'teams.name as team_name',
                        DB::raw('COUNT(DISTINCT CASE WHEN me.event_type = "goal" AND me.is_own_goal = 0 THEN me.id END) as goals'),
                        DB::raw('COUNT(DISTINCT CASE WHEN me.event_type = "assist" THEN me.id END) as assists'),
                        DB::raw('COUNT(DISTINCT CASE WHEN me.event_type = "yellow_card" THEN me.id END) as yellow_cards'),
                        DB::raw('COUNT(DISTINCT CASE WHEN me.event_type = "red_card" THEN me.id END) as red_cards'),
                    ])
                    ->join('teams', 'players.team_id', '=', 'teams.id')
                    ->join('team_tournament', 'teams.id', '=', 'team_tournament.team_id')
                    ->leftJoin('match_events as me', 'players.id', '=', 'me.player_id')
                    ->leftJoin('games', function ($join) use ($tournamentId) {
                        $join->on('me.game_id', '=', 'games.id')
                            ->where('games.tournament_id', '=', $tournamentId);
                    })
                    ->where('team_tournament.tournament_id', $tournamentId)
                    ->groupBy(
                        'players.id',
                        'players.name',
                        'players.jersey_number',
                        'players.position',
                        'players.team_id',
                        'teams.name'
                    )
                    ->having('goals', '>', 0) // Hanya yang punya gol
                    ->orderBy('goals', 'DESC')
                    ->orderBy('assists', 'DESC')
                    ->orderBy('yellow_cards', 'ASC') // Kartu lebih sedikit lebih baik
                    ->orderBy('red_cards', 'ASC')    // Kartu merah lebih sedikit lebih baik
                    ->orderBy('players.name', 'ASC') // Jika semua sama, urutkan nama
                    ->limit(10)
                    ->get();

                // Tambahkan peringkat dengan tie-handling
                $rank = 1;
                $previousGoals = null;
                $skipRank = 0;

                $topScorers = $playerStats->map(function ($player, $index) use (&$rank, &$previousGoals, &$skipRank) {
                    // Jika gol sama dengan sebelumnya, gunakan peringkat yang sama
                    if ($previousGoals !== null && $player->goals == $previousGoals) {
                        $skipRank++; // Tidak naikkan peringkat
                        $player->rank = $rank - 1;
                    } else {
                        $rank += $skipRank; // Naikkan peringkat dengan skip
                        $skipRank = 1; // Reset skip
                        $player->rank = $rank;
                        $rank++; // Siap untuk pemain berikutnya
                    }

                    $previousGoals = $player->goals;

                    // Konversi ke object yang bisa digunakan di view
                    return (object) [
                        'id' => $player->id,
                        'name' => $player->name,
                        'jersey_number' => $player->jersey_number,
                        'position' => $player->position,
                        'team_id' => $player->team_id,
                        'team_name' => $player->team_name,
                        'goals' => $player->goals,
                        'assists' => $player->assists,
                        'yellow_cards' => $player->yellow_cards,
                        'red_cards' => $player->red_cards,
                        'rank' => $player->rank, // Peringkat setelah tie-handling
                        'display_rank' => $player->rank, // Untuk ditampilkan
                    ];
                })->take(5); // Ambil 5 teratas

                \Log::info('Top Scorers with Ranks:', [
                    'players' => $topScorers->map(function ($p) {
                        return $p->name . ' - ' . $p->goals . ' gol (Rank: ' . $p->rank . ')';
                    })->toArray(),
                ]);

            } catch (\Exception $e) {
                \Log::error('Error getting tournament top scorers: ' . $e->getMessage());
                $topScorers = $this->getFallbackTopScorersWithRank();
            }
        } else {
            $topScorers = $this->getFallbackTopScorersWithRank();
        }

        // Pastikan diurutkan benar sebelum dikirim ke view
        $topScorers = $topScorers->sortByDesc('goals')->values();

        // GET STANDINGS dengan semua tim termasuk yang belum bermain
        $standings = $this->getAllGroupStandingsFixed($activeTournament);

        // Statistics for hero section
        $totalTeams = Team::count();
        $matchesCount = Game::count();
        $totalGoals = Player::sum('goals');

        // Calculate days left for active tournament
        $daysLeft = 0;
        if ($activeTournament) {
            $endDate = Carbon::parse($activeTournament->end_date);
            $daysLeft = max(0, Carbon::now()->diffInDays($endDate, false));
        }

        // DEBUG: Kirim data debug ke view
        $debugInfo = [
            'has_active_tournament' => $activeTournament ? 'YES' : 'NO',
            'tournament_name' => $activeTournament ? $activeTournament->name : 'No Tournament',
            'tournament_status' => $activeTournament ? $activeTournament->status : 'N/A',
            'total_tournaments' => $allTournaments->count(),
            'standings_count' => is_array($standings) ? count($standings) : 0,
            'teams_in_tournament' => $activeTournament ?
                DB::table('team_tournament')->where('tournament_id', $activeTournament->id)->count() : 0,
            'top_scorers_count' => $topScorers->count(),
            'top_scorers_has_tournament_data' => $topScorers->first() && isset($topScorers->first()->tournament_goals) ? 'YES' : 'NO',
            'top_scorer_goals' => $topScorers->first() ? ($topScorers->first()->tournament_goals ?? 'N/A') : 'N/A',
            'teams_count' => $teams->count(),
        ];

        $recentHighlights = Game::whereNotNull('youtube_id')
            ->with(['homeTeam', 'awayTeam'])
            ->orderBy('youtube_uploaded_at', 'desc')
            ->limit(3)
            ->get();

        return view('home', compact(
            'heroSetting',
            'activeTournament',
            'todayMatches',
            'upcomingMatches',
            'recentResults',
            'topScorers',
            'standings',
            'teams', // Data teams yang baru
            'totalTeams', // Ubah dari $teams ke $totalTeams untuk statistik
            'matchesCount',
            'totalGoals',
            'daysLeft',
            'debugInfo',
            'recentHighlights'
        ));
    }

    private function getFallbackTopScorersWithRank()
    {
        $players = Player::with('team')
            ->orderBy('goals', 'desc')
            ->orderBy('assists', 'desc')
            ->orderBy('yellow_cards', 'asc')
            ->orderBy('red_cards', 'asc')
            ->orderBy('name', 'asc')
            ->limit(5)
            ->get();

        // Tambahkan ranking dengan tie-handling
        $rank = 1;
        $previousGoals = null;
        $skipRank = 0;

        return $players->map(function ($player, $index) use (&$rank, &$previousGoals, &$skipRank) {
            $playerGoals = $player->goals ?? 0;

            if ($previousGoals !== null && $playerGoals == $previousGoals) {
                $skipRank++;
                $player->rank = $rank - 1;
            } else {
                $rank += $skipRank;
                $skipRank = 1;
                $player->rank = $rank;
                $rank++;
            }

            $previousGoals = $playerGoals;

            // Set default values
            $player->goals = $playerGoals;
            $player->assists = $player->assists ?? 0;
            $player->yellow_cards = $player->yellow_cards ?? 0;
            $player->red_cards = $player->red_cards ?? 0;
            $player->team_name = $player->team->name ?? 'No Team';
            $player->display_rank = $player->rank;

            return $player;
        });
    }

    /**
     * FIXED: Get all group standings including teams that haven't played yet
     */
    private function getAllGroupStandingsFixed($activeTournament = null)
    {
        if (! $activeTournament) {
            // DEBUG: Log jika tidak ada tournament
            \Log::info('No active tournament found in getAllGroupStandingsFixed');

            return [];
        }

        $tournamentId = $activeTournament->id;

        try {
            // DEBUG: Cek apakah ada data di team_tournament
            $teamTournamentCount = DB::table('team_tournament')
                ->where('tournament_id', $tournamentId)
                ->count();

            \Log::info("Team tournament count for tournament {$tournamentId}: {$teamTournamentCount}");

            if ($teamTournamentCount === 0) {
                // Coba ambil data dari standings langsung
                return $this->getStandingsDirectly($tournamentId);
            }

            // 1. Get all teams in this tournament from team_tournament table
            $teamsInTournament = DB::table('team_tournament')
                ->join('teams', 'team_tournament.team_id', '=', 'teams.id')
                ->where('team_tournament.tournament_id', $tournamentId)
                ->select(
                    'teams.id as team_id',
                    'teams.name as team_name',
                    'teams.logo as team_logo',
                    'team_tournament.group_name',
                    'team_tournament.seed'
                )
                ->get();

            // 2. Get existing standings
            $existingStandings = Standing::with('team')
                ->where('tournament_id', $tournamentId)
                ->get()
                ->keyBy('team_id');

            // 3. Group teams by group
            $groupedTeams = [];

            foreach ($teamsInTournament as $teamTournament) {
                $group = $teamTournament->group_name;

                if (empty($group)) {
                    $group = 'Ungrouped';
                }

                if (! isset($groupedTeams[$group])) {
                    $groupedTeams[$group] = [];
                }

                // Check if team has existing standings
                if ($existingStandings->has($teamTournament->team_id)) {
                    $standing = $existingStandings[$teamTournament->team_id];
                    // Tambahkan properti tambahan jika belum ada
                    $standing->team_name = $teamTournament->team_name;
                    $standing->team_logo = $teamTournament->team_logo;
                    $standing->is_default = false;
                } else {
                    // Create default standing
                    $standing = (object) [
                        'team_id' => $teamTournament->team_id,
                        'team_name' => $teamTournament->team_name,
                        'team_logo' => $teamTournament->team_logo,
                        'tournament_id' => $tournamentId,
                        'group_name' => $group,
                        'matches_played' => 0,
                        'wins' => 0,
                        'draws' => 0,
                        'losses' => 0,
                        'goals_for' => 0,
                        'goals_against' => 0,
                        'goal_difference' => 0,
                        'points' => 0,
                        'is_default' => true,
                        'team' => (object) [
                            'id' => $teamTournament->team_id,
                            'name' => $teamTournament->team_name,
                            'logo' => $teamTournament->team_logo,
                        ],
                    ];
                }

                $groupedTeams[$group][] = $standing;
            }

            // 4. Sort each group
            foreach ($groupedTeams as $group => $standings) {
                usort($groupedTeams[$group], function ($a, $b) {
                    // Sort by: points > GD > GF > wins
                    if ($b->points != $a->points) {
                        return $b->points - $a->points;
                    }
                    if ($b->goal_difference != $a->goal_difference) {
                        return $b->goal_difference - $a->goal_difference;
                    }
                    if ($b->goals_for != $a->goals_for) {
                        return $b->goals_for - $a->goals_for;
                    }

                    return $b->wins - $a->wins;
                });
            }

            // 5. Sort groups alphabetically, tapi keluarkan 'Ungrouped' terakhir
            uksort($groupedTeams, function ($a, $b) {
                if ($a === 'Ungrouped') {
                    return 1;
                }
                if ($b === 'Ungrouped') {
                    return -1;
                }

                return strcmp($a, $b);
            });

            \Log::info('Grouped standings created with '.count($groupedTeams).' groups');

            return $groupedTeams;

        } catch (\Exception $e) {
            \Log::error('Error in getAllGroupStandingsFixed: '.$e->getMessage());

            // Fallback ke method simple
            return $this->getStandingsDirectly($tournamentId);
        }
    }

    /**
     * Get standings directly without team_tournament
     */
    private function getStandingsDirectly($tournamentId)
    {
        try {
            $standings = Standing::with([
                'team' => function ($query) {
                    $query->select('id', 'name', 'logo');
                },
            ])
                ->where('tournament_id', $tournamentId)
                ->orderBy('group_name')
                ->orderBy('points', 'desc')
                ->orderBy('goal_difference', 'desc')
                ->orderBy('goals_for', 'desc')
                ->get();

            if ($standings->isEmpty()) {
                \Log::info("No standings found for tournament {$tournamentId}");

                return [];
            }

            $grouped = $standings->groupBy('group_name')->toArray();
            \Log::info('Direct standings found: '.count($grouped).' groups');

            return $grouped;

        } catch (\Exception $e) {
            \Log::error('Error in getStandingsDirectly: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Method paling sederhana untuk testing
     */
    private function getStandingsSimple()
    {
        try {
            // Coba ambil data standings apapun yang ada
            $standings = Standing::with('team')
                ->orderBy('group_name')
                ->orderBy('points', 'desc')
                ->limit(20)
                ->get();

            if ($standings->isEmpty()) {
                // Coba create dummy data untuk testing
                $teams = Team::limit(8)->get();
                $dummyStandings = [];
                $groups = ['A', 'B'];

                foreach ($teams as $index => $team) {
                    $group = $groups[$index % 2];
                    $dummyStandings[] = (object) [
                        'team_id' => $team->id,
                        'team_name' => $team->name,
                        'team' => $team,
                        'tournament_id' => 1,
                        'group_name' => $group,
                        'matches_played' => rand(0, 3),
                        'wins' => rand(0, 2),
                        'draws' => rand(0, 1),
                        'losses' => rand(0, 2),
                        'goals_for' => rand(0, 10),
                        'goals_against' => rand(0, 10),
                        'goal_difference' => rand(-5, 5),
                        'points' => rand(0, 9),
                        'is_default' => false,
                    ];
                }

                // Group dummy data
                $grouped = collect($dummyStandings)->groupBy('group_name')->toArray();

                return $grouped;
            }

            return $standings->groupBy('group_name')->toArray();

        } catch (\Exception $e) {
            \Log::error('Error in getStandingsSimple: '.$e->getMessage());

            return [];
        }
    }

    public function adminDashboard()
    {
        $totalTeams = Team::count();
        $totalPlayers = Player::count();
        $totalMatches = Game::count();
        $completedMatches = Game::where('status', 'completed')->count();

        $recentMatches = Game::with(['homeTeam', 'awayTeam'])
            ->orderBy('match_date', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTeams',
            'totalPlayers',
            'totalMatches',
            'completedMatches',
            'recentMatches'
        ));
    }

    // HomeController.php
    public function highlights()
    {
        $highlights = Game::with(['homeTeam', 'awayTeam', 'tournament'])
            ->where('status', 'completed')
            ->whereNotNull('highlight_video')
            ->orderBy('match_date', 'desc')
            ->paginate(12);

        return view('highlights.index', compact('highlights'));
    }

    /**
 * Get team details for AJAX modal
 */
    public function teamDetails($id)
    {
        try {
            $team = Team::withCount(['players', 'tournaments', 'homeMatches', 'awayMatches'])
                ->with([
                    'players' => function($query) {
                        $query->orderBy('goals', 'desc')
                            ->orderBy('position')
                            ->orderBy('jersey_number');
                    },
                    'tournaments' => function($query) {
                        $query->where('status', 'active')
                            ->orderBy('start_date', 'desc');
                    },
                    'homeMatches' => function($query) {
                        $query->where('status', 'completed')
                            ->orderBy('match_date', 'desc')
                            ->limit(5)
                            ->with('awayTeam');
                    },
                    'awayMatches' => function($query) {
                        $query->where('status', 'completed')
                            ->orderBy('match_date', 'desc')
                            ->limit(5)
                            ->with('homeTeam');
                    }
                ])
                ->findOrFail($id);

            // Calculate team statistics
            $stats = [
                'total_matches' => $team->home_matches_count + $team->away_matches_count,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
            ];

            // Calculate from home matches
            foreach ($team->homeMatches as $match) {
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

            // Calculate from away matches
            foreach ($team->awayMatches as $match) {
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

            // Get top scorer
            $topScorer = $team->players->sortByDesc('goals')->first();

            // Render HTML content
            $html = view('partials.team-details-modal', compact('team', 'stats', 'topScorer'))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load team details: ' . $e->getMessage()
            ], 500);
        }
    }

    // ... method lainnya tetap sama ...
}
