<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display schedule page for public
     */
    public function schedule(Request $request)
    {
        // Get active tournament (ongoing) as default
        $activeTournament = Tournament::where('status', 'ongoing')->first();

        // Get all tournaments for filter dropdown
        $allTournaments = Tournament::whereIn('status', ['upcoming', 'ongoing', 'completed'])
            ->orderByRaw("
            CASE 
                WHEN status = 'ongoing' THEN 1
                WHEN status = 'upcoming' THEN 2
                WHEN status = 'completed' THEN 3
                ELSE 4
            END
        ")
            ->orderBy('start_date', 'desc')
            ->get();

        // Get unique groups from matches for filter
        $groups = Game::select('group_name')
            ->whereNotNull('group_name')
            ->distinct()
            ->orderBy('group_name')
            ->pluck('group_name')
            ->filter()
            ->values();

        // Initialize query
        $query = Game::with(['homeTeam', 'awayTeam', 'tournament']);

        // Apply tournament filter
        $selectedTournament = null;
        if ($request->filled('tournament') && $request->tournament !== 'all') {
            $selectedTournament = Tournament::find($request->tournament);
            $query->where('tournament_id', $request->tournament);
        }
        // Default to active tournament if no filter and active tournament exists
        elseif ($activeTournament) {
            $selectedTournament = $activeTournament;
            $query->where('tournament_id', $activeTournament->id);
        }
        // If no tournament selected and no active tournament, show all matches

        // Apply other filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('homeTeam', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('awayTeam', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('group') && $request->group !== 'all') {
            $query->where('group_name', $request->group);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        } else {
            // Default: show upcoming and ongoing first
            $query->orderByRaw("
            CASE 
                WHEN status = 'ongoing' THEN 1
                WHEN status = 'upcoming' THEN 2
                WHEN status = 'completed' THEN 3
                ELSE 4
            END
        ");
        }

        if ($request->filled('date')) {
            $query->whereDate('match_date', $request->date);
        }

        // Order matches
        $query->orderBy('match_date')
            ->orderBy('time_start');

        // Paginate results
        $perPage = $request->get('per_page', 20);
        $matches = $query->paginate($perPage)->withQueryString();

        // Group matches by date for display
        $groupedMatches = $matches->groupBy(function ($match) {
            return \Carbon\Carbon::parse($match->match_date)->format('Y-m-d');
        });

        // Get statistics for current filter
        $totalMatches = $matches->total();
        $completedMatches = $matches->where('status', 'completed')->count();
        $upcomingMatches = $matches->where('status', 'upcoming')->count();
        $ongoingMatches = $matches->where('status', 'ongoing')->count();

        return view('games.schedule', compact(
            'matches',
            'groupedMatches',
            'activeTournament',
            'allTournaments',
            'selectedTournament',
            'groups',
            'totalMatches',
            'completedMatches',
            'upcomingMatches',
            'ongoingMatches'
        ));
    }
}