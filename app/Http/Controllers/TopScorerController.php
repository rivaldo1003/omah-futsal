<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Player;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TopScorerController extends Controller
{
    public function index(Request $request)
    {
        // Get all tournaments for dropdown
        $tournaments = Tournament::orderBy('created_at', 'desc')->get();

        // Get active tournament from request or use default
        $tournamentId = $request->get('tournament');

        if ($tournamentId) {
            $activeTournament = Tournament::find($tournamentId);
        } else {
            $activeTournament = Tournament::where('status', 'ongoing')->first();
        }

        // Query untuk top scorers
        $topScorers = collect();

        if ($activeTournament) {
            try {
                // Query untuk mendapatkan top scorers dalam tournament tertentu
                $tournamentId = $activeTournament->id;

                // Coba query langsung dari match_events
                $goalEvents = DB::table('match_events')
                    ->join('matches', 'match_events.match_id', '=', 'matches.id')
                    ->join('players', 'match_events.player_id', '=', 'players.id')
                    ->leftJoin('teams', 'players.team_id', '=', 'teams.id')
                    ->where('matches.tournament_id', $tournamentId)
                    ->where('match_events.event_type', 'goal')
                    ->where('match_events.is_own_goal', false)
                    ->select(
                        'players.id',
                        'players.name',
                        'players.photo',
                        'players.jersey_number',
                        'players.position',
                        'teams.id as team_id',
                        'teams.name as team_name',
                        'teams.logo as team_logo',
                        DB::raw('COUNT(match_events.id) as goals')
                    )
                    ->groupBy(
                        'players.id',
                        'players.name',
                        'players.photo',
                        'players.jersey_number',
                        'players.position',
                        'teams.id',
                        'teams.name',
                        'teams.logo'
                    )
                    ->orderBy('goals', 'desc')
                    ->get();

                // Get assists, yellow cards, red cards
                foreach ($goalEvents as $player) {
                    // Assists
                    $assists = DB::table('match_events')
                        ->join('matches', 'match_events.match_id', '=', 'matches.id')
                        ->where('matches.tournament_id', $tournamentId)
                        ->where('match_events.event_type', 'goal')
                        ->where('match_events.assist_player_id', $player->id)
                        ->count();

                    // Yellow cards
                    $yellowCards = DB::table('match_events')
                        ->join('matches', 'match_events.match_id', '=', 'matches.id')
                        ->where('matches.tournament_id', $tournamentId)
                        ->where('match_events.event_type', 'yellow_card')
                        ->where('match_events.player_id', $player->id)
                        ->count();

                    // Red cards
                    $redCards = DB::table('match_events')
                        ->join('matches', 'match_events.match_id', '=', 'matches.id')
                        ->where('matches.tournament_id', $tournamentId)
                        ->where('match_events.event_type', 'red_card')
                        ->where('match_events.player_id', $player->id)
                        ->count();

                    $player->assists = $assists;
                    $player->yellow_cards = $yellowCards;
                    $player->red_cards = $redCards;

                    // Add team object for compatibility
                    $player->team = (object) [
                        'id' => $player->team_id,
                        'name' => $player->team_name,
                        'logo' => $player->team_logo
                    ];
                }

                $topScorers = $goalEvents;

            } catch (\Exception $e) {
                \Log::error('Error in TopScorerController: ' . $e->getMessage());

                // Fallback: jika error, gunakan data overall
                $topScorers = Player::with('team')
                    ->select('players.*')
                    ->orderBy('goals', 'desc')
                    ->orderBy('assists', 'desc')
                    ->get();
            }
        } else {
            // Jika tidak ada tournament yang dipilih, tampilkan semua pemain
            $topScorers = Player::with('team')
                ->orderBy('goals', 'desc')
                ->orderBy('assists', 'desc')
                ->get();
        }

        // Controller:
        return view('top-scorers.top-scorers', compact('tournaments', 'activeTournament', 'topScorers'));
    }
}