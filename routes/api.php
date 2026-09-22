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

// Deployment Webhook for Automated Migration & Cache Optimization (Used by CI/CD v2.1)
Route::post('/deploy/execute/{token}', function ($token) {
    $expectedToken = config('app.deploy_token') ?: env('DEPLOY_TOKEN');

    if (empty($expectedToken) || !hash_equals((string) $expectedToken, (string) $token)) {
        return response()->json(['message' => 'Unauthorized token'], 403);
    }

    try {
        // Reset OPcache if available
        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }

        // 1. Remove orphaned migration files on the server that might have been deleted/renamed in git
        // (FTP Deploy Action does not delete remote files when renamed/removed locally).
        $orphanedMigrationFiles = [
            database_path('migrations/2025_12_12_031948_create_news_articles_table.php'),
        ];
        foreach ($orphanedMigrationFiles as $filePath) {
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        // 2. Pre-migration fix: For every `create_X_table` migration file, if table X already
        // exists in the database but the migration is not recorded in the `migrations` table,
        // record it so artisan migrate won't attempt to re-create the existing table.
        $maxBatch = (int) (\Illuminate\Support\Facades\DB::table('migrations')->max('batch') ?? 0);
        $batch = $maxBatch + 1;

        foreach (glob(database_path('migrations/*.php')) as $migrationFile) {
            $migrationName = basename($migrationFile, '.php');

            // Only handle migrations that create a table: *_create_<table>_table
            if (!preg_match('/^\d{4}_\d{2}_\d{2}_\d{6}_create_(.+)_table$/', $migrationName, $m)) {
                continue;
            }

            $table = $m[1];

            if (!\Illuminate\Support\Facades\Schema::hasTable($table)) {
                continue;
            }

            $alreadyRan = \Illuminate\Support\Facades\DB::table('migrations')
                ->where('migration', $migrationName)
                ->exists();

            if (!$alreadyRan) {
                \Illuminate\Support\Facades\DB::table('migrations')->insert([
                    'migration' => $migrationName,
                    'batch'     => $batch,
                ]);
            }
        }

        // 3. Run database migrations
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        // 4. Ensure the public/storage symlink exists so team/player photos are accessible
        //    (FTP deploy excludes public/storage, so the symlink must be recreated on the server).
        if (!file_exists(public_path('storage'))) {
            try {
                \Illuminate\Support\Facades\Artisan::call('storage:link');
            } catch (\Throwable $e) {
                // Fallback for shared hosting that disallows symlinks:
                // copy the contents of storage/app/public into public/storage.
                $src = storage_path('app/public');
                $dst = public_path('storage');
                if (is_dir($src)) {
                    @mkdir($dst, 0755, true);
                    $iterator = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($src, \FilesystemIterator::SKIP_DOTS),
                        \RecursiveIteratorIterator::SELF_FIRST
                    );
                    foreach ($iterator as $item) {
                        $target = $dst . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
                        if ($item->isDir()) {
                            @mkdir($target, 0755, true);
                        } else {
                            @copy($item->getPathname(), $target);
                        }
                    }
                }
            }
        }

        // 5. Clear and recache config/routes/views for production performance
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        \Illuminate\Support\Facades\Artisan::call('optimize');

        return response()->json([
            'status' => 'success',
            'message' => 'Deployment actions executed successfully.',
            'migrate_output' => trim($migrateOutput),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
});
