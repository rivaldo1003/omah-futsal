<?php

use App\Http\Controllers\Api\MatchApiController;
use App\Http\Controllers\Api\PlayerApiController;
use App\Http\Controllers\Api\StandingApiController;
use App\Http\Controllers\Api\TeamApiController;
use App\Http\Controllers\Api\TournamentApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API Routes (No Authentication Required)
Route::prefix('v1')->group(function () {
    // Tournaments
    Route::get('tournaments', [TournamentApiController::class, 'index']);
    Route::get('tournaments/active', [TournamentApiController::class, 'active']);
    Route::get('tournaments/{id}', [TournamentApiController::class, 'show']);
    Route::get('tournaments/{id}/standings', [TournamentApiController::class, 'standings']);
    Route::get('tournaments/{id}/matches', [TournamentApiController::class, 'matches']);

    // Matches
    Route::get('matches', [MatchApiController::class, 'index']);
    Route::get('matches/upcoming', [MatchApiController::class, 'upcoming']);
    Route::get('matches/live', [MatchApiController::class, 'live']);
    Route::get('matches/{id}', [MatchApiController::class, 'show']);
    Route::get('matches/{id}/events', [MatchApiController::class, 'events']);

    // Teams
    Route::get('teams', [TeamApiController::class, 'index']);
    Route::get('teams/{id}', [TeamApiController::class, 'show']);
    Route::get('teams/{id}/players', [TeamApiController::class, 'players']); // <-- HAPUS SALAH SATU
    Route::get('teams/{id}/matches', [TeamApiController::class, 'matches']);

    // Players
    Route::get('players', [PlayerApiController::class, 'index']);
    Route::get('players/top-scorers', [PlayerApiController::class, 'topScorers']);
    Route::get('players/{id}', [PlayerApiController::class, 'show']);

    // Standings
    Route::get('standings', [StandingApiController::class, 'index']);
    Route::get('standings/group/{group}', [StandingApiController::class, 'byGroup']);

    // Statistics
    Route::get('statistics', function () {
        return response()->json([
            'total_matches' => \App\Models\Game::count(),
            'total_teams' => \App\Models\Team::count(),
            'total_players' => \App\Models\Player::count(),
            'total_goals' => \App\Models\Player::sum('goals'),
        ]);
    });
    // HAPUS BARIS INI: Route::get('teams/{id}/players', [TeamApiController::class, 'players']);
});

// Protected API Routes (Authentication Required)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Admin endpoints
    Route::apiResource('admin/tournaments', TournamentApiController::class)->except(['index', 'show']);
    Route::apiResource('admin/matches', MatchApiController::class)->except(['index', 'show']);
    Route::apiResource('admin/teams', TeamApiController::class)->except(['index', 'show']);
    Route::apiResource('admin/players', PlayerApiController::class)->except(['index', 'show']);

    // Match actions
    Route::post('matches/{id}/score', [MatchApiController::class, 'updateScore']);
    Route::post('matches/{id}/events', [MatchApiController::class, 'addEvent']);
});