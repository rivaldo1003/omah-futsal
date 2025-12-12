<?php

namespace App\Http\Controllers;

use App\Models\Game;

class HighlightController extends Controller
{
    /**
     * Display highlights page
     */
    public function index()
    {
        // Debug: Cek data di database dulu
        \Log::info('HighlightController@index called');

        $youtubeMatchesCount = Game::whereNotNull('youtube_id')->count();
        \Log::info('Matches with youtube_id: '.$youtubeMatchesCount);

        // Hanya ambil match yang memiliki youtube_id
        $highlights = Game::whereNotNull('youtube_id')
            ->with(['homeTeam', 'awayTeam', 'tournament'])
            ->orderBy('youtube_uploaded_at', 'desc')
            ->paginate(9); // 3x3 grid

        \Log::info('Highlights fetched: '.$highlights->count());

        // Debug info untuk developer
        if (config('app.debug')) {
            foreach ($highlights as $highlight) {
                \Log::info("Highlight ID: {$highlight->id}, YouTube ID: {$highlight->youtube_id}");
            }
        }

        return view('highlights.index', compact('highlights'));
    }

    /**
     * Debug method untuk menampilkan semua match dengan youtube_id
     */
    public function debug()
    {
        $matches = Game::whereNotNull('youtube_id')
            ->with(['homeTeam', 'awayTeam'])
            ->orderBy('youtube_uploaded_at', 'desc')
            ->get();

        return response()->json([
            'total' => $matches->count(),
            'matches' => $matches->map(function ($match) {
                return [
                    'id' => $match->id,
                    'youtube_id' => $match->youtube_id,
                    'youtube_uploaded_at' => $match->youtube_uploaded_at,
                    'teams' => ($match->homeTeam->name ?? 'Home').' vs '.($match->awayTeam->name ?? 'Away'),
                    'score' => ($match->home_score ?? 0).' - '.($match->away_score ?? 0),
                    'thumbnail_url' => $match->display_thumbnail_url,
                ];
            }),
        ]);
    }
}
