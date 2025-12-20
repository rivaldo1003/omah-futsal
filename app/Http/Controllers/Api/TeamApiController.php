<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamApiController extends Controller
{
    /**
     * Display a listing of the teams
     */
    public function index(Request $request)
    {
        $query = Team::withCount(['players', 'tournaments']);
        
        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by tournament if provided
        if ($request->has('tournament_id')) {
            $query->whereHas('tournaments', function ($q) use ($request) {
                $q->where('tournament_id', $request->tournament_id);
            });
        }
        
        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Pagination
        $perPage = $request->get('per_page', 15);
        $teams = $query->orderBy('name')->paginate($perPage);
        
        // Transform teams for API response
        $transformedTeams = $teams->map(function ($team) {
            return $this->transformTeam($team);
        });
        
        return response()->json([
            'success' => true,
            'data' => $transformedTeams,
            'meta' => [
                'current_page' => $teams->currentPage(),
                'last_page' => $teams->lastPage(),
                'per_page' => $teams->perPage(),
                'total' => $teams->total(),
            ]
        ]);
    }

    /**
     * Display the specified team
     */
    public function show(Team $team)
    {
        $team->load(['players', 'tournaments', 'homeMatches', 'awayMatches']);
        
        // Calculate team statistics
        $stats = $this->calculateTeamStats($team);
        
        return response()->json([
            'success' => true,
            'data' => [
                'team' => $this->transformTeam($team),
                'statistics' => $stats,
                'players_count' => $team->players->count(),
                'tournaments_count' => $team->tournaments->count(),
            ]
        ]);
    }

    /**
     * Get team players with pagination - METHOD INI YANG DIPERLUKAN
     */
    // public function players(Team $team, Request $request)
    // {
    //     try {
    //         $page = $request->get('page', 1);
    //         $perPage = 12;
            
    //         $players = $team->players()
    //             ->orderBy('position')
    //             ->orderBy('jersey_number')
    //             ->paginate($perPage, ['*'], 'page', $page);
            
    //         $playersHtml = '';
    //         foreach ($players as $player) {
    //             $playersHtml .= $this->generatePlayerCardHtml($player);
    //         }
            
    //         return response()->json([
    //             'success' => true,
    //             'team' => [
    //                 'name' => $team->name,
    //                 'coach_name' => $team->coach_name,
    //                 'logo_html' => $this->generateLogoHtml($team),
    //             ],
    //             'players_html' => $playersHtml,
    //             'total_players' => $team->players()->count(),
    //             'all_loaded' => !$players->hasMorePages(),
    //             'current_page' => $page,
    //             'has_more' => $players->hasMorePages(),
    //         ]);
            
    //     } catch (\Exception $e) {
    //         \Log::error('Team API Players Error: ' . $e->getMessage(), [
    //             'team_id' => $team->id,
    //             'exception' => $e
    //         ]);
            
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to load players',
    //             'error' => config('app.debug') ? $e->getMessage() : null,
    //         ], 500);
    //     }
    // }

    /**
     * Get team matches
     */
    public function matches(Team $team, Request $request)
    {
        $query = $team->homeMatches()
            ->orWhere('team_away_id', $team->id)
            ->with(['homeTeam', 'awayTeam', 'tournament'])
            ->orderBy('match_date', 'desc')
            ->orderBy('time_start', 'desc');
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by tournament
        if ($request->has('tournament_id')) {
            $query->where('tournament_id', $request->tournament_id);
        }
        
        // Pagination
        $perPage = $request->get('per_page', 10);
        $matches = $query->paginate($perPage);
        
        $transformedMatches = $matches->map(function ($match) use ($team) {
            return $this->transformMatch($match, $team);
        });
        
        return response()->json([
            'success' => true,
            'data' => $transformedMatches,
            'meta' => [
                'current_page' => $matches->currentPage(),
                'last_page' => $matches->lastPage(),
                'per_page' => $matches->perPage(),
                'total' => $matches->total(),
            ]
        ]);
    }

    /**
     * Transform team data for API response
     */
    private function transformTeam(Team $team)
    {
        $logoUrl = null;
        if ($team->logo) {
            if (filter_var($team->logo, FILTER_VALIDATE_URL)) {
                $logoUrl = $team->logo;
            } elseif (Storage::disk('public')->exists($team->logo)) {
                $logoUrl = asset('storage/' . $team->logo);
            }
        }
        
        return [
            'id' => $team->id,
            'name' => $team->name,
            'short_name' => $team->short_name,
            'description' => $team->description,
            'coach_name' => $team->coach_name,
            'city' => $team->city,
            'group_name' => $team->group_name,
            'contact_email' => $team->contact_email,
            'contact_phone' => $team->contact_phone,
            'status' => $team->status,
            'logo' => $logoUrl,
            'players_count' => $team->players_count ?? $team->players()->count(),
            'tournaments_count' => $team->tournaments_count ?? $team->tournaments()->count(),
            'created_at' => $team->created_at->toDateTimeString(),
            'updated_at' => $team->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Calculate team statistics
     */
    private function calculateTeamStats(Team $team)
    {
        $stats = [
            'total_matches' => 0,
            'wins' => 0,
            'draws' => 0,
            'losses' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
        ];

        // Calculate from home matches
        foreach ($team->homeMatches->where('status', 'completed') as $match) {
            $stats['total_matches']++;
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
        foreach ($team->awayMatches->where('status', 'completed') as $match) {
            $stats['total_matches']++;
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
        
        // Calculate win percentage
        $stats['win_percentage'] = $stats['total_matches'] > 0 
            ? round(($stats['wins'] / $stats['total_matches']) * 100, 1)
            : 0;

        return $stats;
    }

    /**
     * Generate HTML for player card
     */
    private function generatePlayerCardHtml(Player $player)
    {
        $photoHtml = '';
        if ($player->hasPhoto()) {
            $photoUrl = $player->photo_url;
            $photoHtml = '<img src="' . e($photoUrl) . '" alt="' . e($player->name) . '" class="player-photo">';
        } else {
            $initial = strtoupper(substr($player->name, 0, 1));
            $photoHtml = '<div class="player-initial">' . e($initial) . '</div>';
        }
        
        $jerseyHtml = $player->jersey_number 
            ? '<div class="player-jersey">#' . e($player->jersey_number) . '</div>' 
            : '';
        
        $positionHtml = $player->position 
            ? '<div class="player-position">' . e($player->position) . '</div>' 
            : '';
        
        // Stats badges if available
        $statsHtml = '';
        if ($player->goals > 0 || $player->assists > 0) {
            $statsHtml = '<div class="player-stats mt-2">';
            if ($player->goals > 0) {
                $statsHtml .= '<span class="badge bg-success me-1"><i class="bi bi-soccer"></i> ' . $player->goals . '</span>';
            }
            if ($player->assists > 0) {
                $statsHtml .= '<span class="badge bg-info"><i class="bi bi-share"></i> ' . $player->assists . '</span>';
            }
            $statsHtml .= '</div>';
        }
        
        return '
        <div class="player-card-modal">
            <div class="player-photo-container">
                ' . $photoHtml . '
            </div>
            <div class="player-info-modal">
                <h6 class="mb-1">' . e($player->name) . '</h6>
                ' . $jerseyHtml . '
                ' . $positionHtml . '
                ' . $statsHtml . '
            </div>
        </div>';
    }

    /**
     * Generate HTML for team logo
     */
    private function generateLogoHtml(Team $team)
    {
        if ($team->logo) {
            // Check if logo is URL or path
            if (filter_var($team->logo, FILTER_VALIDATE_URL)) {
                $logoUrl = $team->logo;
            } elseif (Storage::disk('public')->exists($team->logo)) {
                $logoUrl = asset('storage/' . $team->logo);
            } else {
                $logoUrl = null;
            }
            
            if ($logoUrl) {
                return '<img src="' . e($logoUrl) . '" alt="' . e($team->name) . '" style="width: 100%; height: 100%; object-fit: cover;">';
            }
        }
        
        // Fallback to initials
        $initials = strtoupper(substr($team->name, 0, 2));
        return '<div class="d-flex align-items-center justify-content-center h-100 bg-light">
                    <span class="fw-bold text-primary">' . e($initials) . '</span>
                </div>';
    }

    /**
     * Transform match data for API response
     */
    private function transformMatch($match, Team $team)
    {
        $isHome = $match->team_home_id == $team->id;
        $opponent = $isHome ? $match->awayTeam : $match->homeTeam;
        $teamScore = $isHome ? $match->home_score : $match->away_score;
        $opponentScore = $isHome ? $match->away_score : $match->home_score;
        
        $result = 'draw';
        if ($teamScore > $opponentScore) {
            $result = 'win';
        } elseif ($teamScore < $opponentScore) {
            $result = 'loss';
        }
        
        return [
            'id' => $match->id,
            'date' => $match->match_date->format('Y-m-d'),
            'time' => $match->time_start,
            'venue' => $match->venue,
            'status' => $match->status,
            'round_type' => $match->round_type,
            'group_name' => $match->group_name,
            'team_score' => $teamScore,
            'opponent_score' => $opponentScore,
            'result' => $result,
            'is_home' => $isHome,
            'opponent' => $opponent ? [
                'id' => $opponent->id,
                'name' => $opponent->name,
                'short_name' => $opponent->short_name,
            ] : null,
            'tournament' => $match->tournament ? [
                'id' => $match->tournament->id,
                'name' => $match->tournament->name,
            ] : null,
        ];
    }
}