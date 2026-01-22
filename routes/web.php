<?php

use App\Http\Controllers\Admin\HeroSettingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchEventController;
use App\Http\Controllers\NewsArticleController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StandingController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TopScorerController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

// ==================== PUBLIC ROUTES ====================

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// / News
Route::get('/news', [NewsArticleController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsArticleController::class, 'show'])->name('news.show');

// Tournaments
Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');
Route::get('/tournaments/active', [TournamentController::class, 'active'])->name('tournaments.active');
Route::get('/tournaments/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');
// Route::get('/tournaments/{tournament}/standings', [TournamentController::class, 'standings'])->name('tournaments.standings');
Route::get('/tournaments/{tournament}/schedule', [TournamentController::class, 'schedule'])->name('tournaments.schedule');
Route::get('/tournaments/{tournament}/teams', [TournamentController::class, 'teams'])->name('tournaments.teams');

// Public Matches/Schedule
Route::get('/schedule', [GameController::class, 'schedule'])->name('schedule');
Route::get('/matches/{game}', [GameController::class, 'show'])->name('matches.show');

// Public Standings
Route::get('/standings', [StandingController::class, 'publicIndex'])->name('standings');
// Route::get('/top-scorers', [PlayerController::class, 'topScorers'])->name('top-scorers');
// Menjadi ini:
// Route::get('/top-scorers', [TopScorerController::class, 'index'])->name('top-scorers');
// Route untuk top scorers
Route::get('/top-scorers', [TopScorerController::class, 'index'])->name('top-scorers');

// Public routes for highlight
Route::get('/matches/{match}/highlight', [GameController::class, 'getHighlightInfo'])
    ->name('matches.highlight');

// routes/web.php
Route::get('/highlights', [GameController::class, 'highlights'])->name('highlights.index');

Route::get('/matches/{match}/youtube-highlight', [GameController::class, 'getYoutubeHighlightInfo'])
    ->name('matches.youtube-highlight.info');

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/teams/{team}/details', [HomeController::class, 'teamDetails'])->name('teams.details');

// ==================== AUTHENTICATION ROUTES ====================

// Authentication Routes
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Password Reset Routes
Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
Route::resource('teams', TeamController::class);

// ==================== USER DASHBOARD ROUTES ====================

Route::middleware(['auth'])->group(function () {
    // User Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    // User Dashboard
    Route::get('/dashboard', [HomeController::class, 'userDashboard'])->name('dashboard');
    Route::get('/my-teams', [TeamController::class, 'myTeams'])->name('my-teams');
    Route::get('/my-players', [PlayerController::class, 'myPlayers'])->name('my-players');
});

// ==================== ADMIN ROUTES ====================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // ========== ADMIN DASHBOARD ==========
    Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/overview', [AdminController::class, 'overview'])->name('overview');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');

    Route::get('/tournaments/debug/clear-session', [TournamentController::class, 'clearSessionDebug']);

    // / NEWS
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [NewsArticleController::class, 'adminIndex'])->name('index');  // adminIndex
        Route::get('/create', [NewsArticleController::class, 'create'])->name('create');
        Route::post('/', [NewsArticleController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [NewsArticleController::class, 'edit'])->name('edit');  // {id} bukan {article}
        Route::put('/{id}', [NewsArticleController::class, 'update'])->name('update');  // {id} bukan {article}
        Route::delete('/{id}', [NewsArticleController::class, 'destroy'])->name('destroy');  // {id} bukan {article}
    });

    // Highlight routes
    Route::get('/matches/{match}/edit-highlight', [GameController::class, 'editHighlight'])
        ->name('admin.matches.edit-highlight');
    Route::post('/matches/{match}/upload-highlight', [GameController::class, 'uploadHighlight'])
        ->name('admin.matches.upload-highlight');
    Route::delete('/matches/{match}/delete-highlight', [GameController::class, 'deleteHighlight'])
        ->name('admin.matches.delete-highlight');
    Route::get('/matches/{match}/highlight-info', [GameController::class, 'getHighlightInfo'])
        ->name('admin.matches.highlight-info');

    // YouTube highlights
    Route::post('/matches/{match}/youtube-highlight', [GameController::class, 'addYoutubeHighlight'])
        ->name('matches.youtube-highlight.add');
    Route::put('/matches/{match}/youtube-highlight', [GameController::class, 'updateYoutubeHighlight'])
        ->name('matches.youtube-highlight.update');
    Route::delete('/matches/{match}/youtube-highlight', [GameController::class, 'removeYoutubeHighlight'])
        ->name('matches.youtube-highlight.remove');

    // / NEWS

    Route::post('/news/{id}/increment-views', [NewsArticleController::class, 'incrementViews'])
        ->name('news.increment-views');

    // Untuk admin (jika ada)
    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
        Route::resource('news', AdminNewsController::class);
    });

    // HERO SETTINGS
    Route::get('/hero-settings', [HeroSettingController::class, 'edit'])->name('hero-settings.index');
    Route::put('/hero-settings', [HeroSettingController::class, 'update'])->name('hero-settings.update');

    // ========== TOURNAMENT MANAGEMENT ==========
    // Multi-step tournament creation - URUTAN PENTING!
    Route::get('/tournaments/create', [TournamentController::class, 'create'])->name('tournaments.create');
    Route::get('/tournaments/create/step/{step}', [TournamentController::class, 'createStep'])->name('tournaments.create.step');
    Route::post('/tournaments/store/step/{step}', [TournamentController::class, 'storeStep'])->name('tournaments.store.step');

    // Debug routes untuk tournament creation
    Route::get('/tournaments/debug-session', [TournamentController::class, 'debugSession'])->name('tournaments.debug.session');
    Route::get('/tournaments/clear-session', [TournamentController::class, 'clearSession'])->name('tournaments.clear.session');
    Route::post('/tournaments/create/final', [TournamentController::class, 'createFinal'])->name('tournaments.create.final');

    // Standard CRUD routes - EXCLUDE create dan store karena sudah ada di atas
    Route::resource('tournaments', TournamentController::class)->except(['create', 'store']);

    // Tournament Schedule Routes
    Route::get('/tournaments/{tournament}/schedule', [ScheduleController::class, 'index'])->name('tournaments.schedule');
    Route::post('/tournaments/{tournament}/schedule/generate', [ScheduleController::class, 'generateSchedule'])->name('tournaments.schedule.generate');

    // Tournament Actions
    Route::get('tournaments/{tournament}/teams', [TournamentController::class, 'addTeams'])->name('tournaments.teams');
    Route::post('tournaments/{tournament}/teams', [TournamentController::class, 'storeTeams'])->name('tournaments.store-teams');
    Route::post('tournaments/{tournament}/start', [TournamentController::class, 'start'])->name('tournaments.start');
    Route::post('tournaments/{tournament}/end', [TournamentController::class, 'end'])->name('tournaments.end');
    Route::post('tournaments/{tournament}/activate', [TournamentController::class, 'activate'])->name('tournaments.activate');
    Route::get('tournaments/{tournament}/export', [TournamentController::class, 'export'])->name('tournaments.export');
    Route::get('tournaments/{tournament}/settings', [TournamentController::class, 'settings'])->name('tournaments.settings');
    Route::put('tournaments/{tournament}/settings', [TournamentController::class, 'updateSettings'])->name('tournaments.update-settings');

    // ========== MATCHES MANAGEMENT ==========
    Route::resource('matches', GameController::class);

    // Match Actions
    Route::post('matches/{match}/update-score', [GameController::class, 'updateScore'])->name('matches.update-score');
    Route::post('matches/{match}/add-event', [GameController::class, 'addEvent'])->name('matches.add-event');
    Route::delete('events/{event}', [GameController::class, 'deleteEvent'])->name('matches.delete-event');
    Route::post('matches/batch-create', [GameController::class, 'createBatch'])->name('matches.batch-create');
    Route::post('matches/{match}/update-status', [GameController::class, 'updateStatus'])->name('matches.update-status');
    Route::get('matches/{match}/lineup', [GameController::class, 'lineup'])->name('matches.lineup');
    Route::post('matches/{match}/save-lineup', [GameController::class, 'saveLineup'])->name('matches.save-lineup');
    Route::get('matches/{match}/stats', [GameController::class, 'stats'])->name('matches.stats');
    Route::post('matches/{match}/stats', [GameController::class, 'updateStats'])->name('matches.update-stats');

    // ========== MATCH EVENTS MANAGEMENT ==========
    // Route khusus untuk MatchEventController
    Route::get('matches/{match}/events', [MatchEventController::class, 'index'])->name('matches.events.index');
    Route::get('matches/{match}/events/create', [MatchEventController::class, 'create'])->name('matches.events.create');
    Route::post('matches/{match}/events', [MatchEventController::class, 'store'])->name('matches.events.store');
    Route::get('matches/{match}/events/{event}/edit', [MatchEventController::class, 'edit'])->name('matches.events.edit');
    Route::put('matches/{match}/events/{event}', [MatchEventController::class, 'update'])->name('matches.events.update');
    Route::delete('matches/{match}/events/{event}', [MatchEventController::class, 'destroy'])->name('matches.events.destroy');

    // Route untuk generate matches
    Route::post('/tournaments/{tournament}/generate-matches', [GameController::class, 'generateMatches'])
        ->name('admin.tournaments.generate-matches');

    // Route untuk delete all matches
    Route::delete('/tournaments/{tournament}/matches', [GameController::class, 'deleteTournamentMatches'])
        ->name('admin.tournaments.delete-matches');

    // Quick actions untuk API/AJAX
    Route::post('matches/{match}/events/quick-goal', [MatchEventController::class, 'quickAddGoal'])->name('matches.events.quick-goal');
    Route::post('matches/{match}/events/quick-card', [MatchEventController::class, 'quickAddCard'])->name('matches.events.quick-card');
    Route::get('matches/{match}/events/timeline', [MatchEventController::class, 'timeline'])->name('matches.events.timeline');

    // Batch Match Operations
    Route::get('matches/create/batch', [GameController::class, 'createBatchForm'])->name('matches.create-batch');
    Route::post('matches/generate-group-stage', [GameController::class, 'generateGroupStage'])->name('matches.generate-group-stage');
    Route::post('matches/generate-knockout', [GameController::class, 'generateKnockout'])->name('matches.generate-knockout');
    Route::post('matches/update-batch-status', [GameController::class, 'updateBatchStatus'])->name('matches.update-batch-status');

    // ========== TEAMS MANAGEMENT ==========
    Route::resource('teams', TeamController::class);

    // Tambahkan route untuk update status
    Route::put('teams/{team}/status', [TeamController::class, 'updateStatus'])->name('teams.update-status');
    Route::post('teams/{team}/assign-tournament', [TeamController::class, 'assignTournament'])->name('teams.assign-tournament');

    // Team Actions
    Route::get('teams/trashed', [TeamController::class, 'trashed'])->name('teams.trashed');
    Route::post('teams/{id}/restore', [TeamController::class, 'restore'])->name('teams.restore');
    Route::delete('teams/{id}/force-delete', [TeamController::class, 'forceDelete'])->name('teams.force-delete');
    Route::post('teams/import', [TeamController::class, 'import'])->name('teams.import');
    Route::get('teams/export', [TeamController::class, 'export'])->name('teams.export');
    Route::get('teams/{team}/players/create', [TeamController::class, 'createPlayer'])->name('teams.players.create');
    Route::post('teams/{team}/players', [TeamController::class, 'storePlayer'])->name('teams.players.store');

    // ========== PLAYERS MANAGEMENT ==========
    Route::resource('players', PlayerController::class);

    // Player Actions
    Route::get('teams/{team}/players', [PlayerController::class, 'teamPlayers'])->name('team.players');
    Route::get('players/trashed', [PlayerController::class, 'trashed'])->name('players.trashed');
    Route::post('players/{id}/restore', [PlayerController::class, 'restore'])->name('players.restore');
    Route::delete('players/{id}/force-delete', [PlayerController::class, 'forceDelete'])->name('players.force-delete');
    Route::post('players/import', [PlayerController::class, 'import'])->name('players.import');
    Route::get('players/export', [PlayerController::class, 'export'])->name('players.export');
    Route::post('players/{player}/photo', [PlayerController::class, 'updatePhoto'])->name('players.photo');
    Route::get('players/{player}/stats', [PlayerController::class, 'stats'])->name('players.stats');

    // ========== STANDINGS MANAGEMENT ==========
    Route::resource('standings', StandingController::class)->except(['create', 'store', 'destroy']);

    // Route yang benar:
    Route::get('/standings', [StandingController::class, 'publicIndex'])->name('standings');

    // Atau jika ingin menggunakan 'standings.index':
    Route::get('/standings', [StandingController::class, 'publicIndex'])->name('standings.index');

    Route::post('/admin/standings/fix/{match}', [GameController::class, 'fixStandings'])
        ->name('admin.standings.fix');

    Route::get('/tournaments/{tournament}/standings', [TournamentController::class, 'showStandings'])
        ->name('tournaments.standings');

    // Standing Actions
    Route::post('standings/recalculate', [StandingController::class, 'recalculate'])->name('standings.recalculate');
    Route::post('standings/reset', [StandingController::class, 'reset'])->name('standings.reset');
    Route::post('standings/update', [StandingController::class, 'update'])->name('standings.update');
    Route::get('standings/export', [StandingController::class, 'export'])->name('standings.export');

    // ========== MATCH EVENTS MANAGEMENT ==========
    // Route::resource('events', MatchEventController::class)->except(['create', 'store', 'show']);
    // Route::get('matches/{match}/events', [MatchEventController::class, 'matchEvents'])->name('matches.events');
    // Route::post('matches/{match}/events/batch', [MatchEventController::class, 'storeBatch'])->name('events.store-batch');
    // Route::put('events/{event}/minute', [MatchEventController::class, 'updateMinute'])->name('events.update-minute');

    // ========== STATISTICS & REPORTS ==========
    Route::prefix('statistics')->name('statistics.')->group(function () {
        Route::get('/', [AdminController::class, 'statistics'])->name('index');
        Route::get('dashboard', [AdminController::class, 'statisticsDashboard'])->name('dashboard');
        Route::get('goals', [AdminController::class, 'goalStatistics'])->name('goals');
        Route::get('cards', [AdminController::class, 'cardStatistics'])->name('cards');
        Route::get('players', [AdminController::class, 'playerStatistics'])->name('players');
        Route::get('teams', [AdminController::class, 'teamStatistics'])->name('teams');
        Route::get('matches', [AdminController::class, 'matchStatistics'])->name('matches');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('matches', [AdminController::class, 'matchReport'])->name('matches');
        Route::get('players', [AdminController::class, 'playerReport'])->name('players');
        Route::get('teams', [AdminController::class, 'teamReport'])->name('teams');
        Route::get('financial', [AdminController::class, 'financialReport'])->name('financial');
        Route::get('attendance', [AdminController::class, 'attendanceReport'])->name('attendance');
        Route::get('export/{type}', [AdminController::class, 'exportReport'])->name('export');
        Route::get('generate/{report}', [AdminController::class, 'generateReport'])->name('generate');
    });

    // ========== SETTINGS MANAGEMENT ==========
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminController::class, 'settings'])->name('index');
        Route::get('general', [AdminController::class, 'generalSettings'])->name('general');
        Route::post('general', [AdminController::class, 'updateGeneralSettings'])->name('general.update');
        Route::get('tournament', [AdminController::class, 'tournamentSettings'])->name('tournament');
        Route::post('tournament', [AdminController::class, 'updateTournamentSettings'])->name('tournament.update');
        Route::get('system', [AdminController::class, 'systemSettings'])->name('system');
        Route::post('system', [AdminController::class, 'updateSystemSettings'])->name('system.update');
        Route::get('email', [AdminController::class, 'emailSettings'])->name('email');
        Route::post('email', [AdminController::class, 'updateEmailSettings'])->name('email.update');
        Route::get('notifications', [AdminController::class, 'notificationSettings'])->name('notifications');
        Route::post('notifications', [AdminController::class, 'updateNotificationSettings'])->name('notifications.update');
        Route::get('backup', [AdminController::class, 'backupSettings'])->name('backup');
        Route::post('backup', [AdminController::class, 'createBackup'])->name('backup.create');
    });

    // ========== USER MANAGEMENT ==========
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('index');
        Route::get('create', [AdminController::class, 'createUser'])->name('create');
        Route::post('store', [AdminController::class, 'storeUser'])->name('store');
        Route::get('{user}/edit', [AdminController::class, 'editUser'])->name('edit');
        Route::put('{user}/update', [AdminController::class, 'updateUser'])->name('update');
        Route::delete('{user}/delete', [AdminController::class, 'deleteUser'])->name('delete');
        Route::post('{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('toggle-status');
        Route::post('{user}/change-role', [AdminController::class, 'changeUserRole'])->name('change-role');
        Route::post('{user}/reset-password', [AdminController::class, 'resetUserPassword'])->name('reset-password');
        Route::get('{user}/activity', [AdminController::class, 'userActivity'])->name('activity');
        Route::get('roles', [AdminController::class, 'roles'])->name('roles');
        Route::post('roles', [AdminController::class, 'storeRole'])->name('roles.store');
    });

    // ========== GALLERY & MEDIA MANAGEMENT ==========
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [AdminController::class, 'media'])->name('index');
        Route::post('upload', [AdminController::class, 'uploadMedia'])->name('upload');
        Route::delete('{media}/delete', [AdminController::class, 'deleteMedia'])->name('delete');
        Route::get('gallery', [AdminController::class, 'gallery'])->name('gallery');
        Route::post('gallery', [AdminController::class, 'storeGallery'])->name('gallery.store');
    });

    // // ========== NEWS & ANNOUNCEMENTS ==========
    // Route::resource('news', \App\Http\Controllers\NewsController::class);
    // Route::post('news/{news}/publish', [\App\Http\Controllers\NewsController::class, 'publish'])->name('news.publish');
    // Route::post('news/{news}/unpublish', [\App\Http\Controllers\NewsController::class, 'unpublish'])->name('news.unpublish');
});

// ==================== API ROUTES (Public) ====================

Route::prefix('api')->name('api.')->group(function () {
    // Public API
    Route::get('schedule', [GameController::class, 'getTodayMatches'])->name('schedule');
    Route::get('standings', [StandingController::class, 'apiIndex'])->name('standings');
    Route::get('top-scorers', [PlayerController::class, 'apiTopScorers'])->name('top-scorers');
    Route::get('teams', [TeamController::class, 'apiIndex'])->name('teams');
    Route::get('matches/upcoming', [GameController::class, 'apiUpcoming'])->name('matches.upcoming');
    Route::get('matches/{id}', [GameController::class, 'apiShow'])->name('matches.show');
    Route::get('tournaments/active', [TournamentController::class, 'apiActive'])->name('tournaments.active');
    Route::get('tournaments/{id}', [TournamentController::class, 'apiShow'])->name('tournaments.show');
    Route::get('matches/by-date-range', [GameController::class, 'getByDateRange'])->name('matches.by-date-range');

    // ADD THESE NEW ROUTES - IMPORTANT!
    Route::prefix('v1')->group(function () {
        Route::get('teams/{team}/players', [App\Http\Controllers\Api\TeamApiController::class, 'players'])
            ->where('team', '[0-9]+')
            ->name('teams.players');
        Route::get('teams/{team}', [App\Http\Controllers\Api\TeamApiController::class, 'show'])
            ->where('team', '[0-9]+')
            ->name('teams.show');
    });

});

// ==================== AJAX/JSON ROUTES ====================

Route::prefix('ajax')->name('ajax.')->group(function () {
    // Live scores
    Route::get('live-scores', [GameController::class, 'liveScores'])->name('live-scores');

    // Search
    Route::get('search/players', [PlayerController::class, 'ajaxSearch'])->name('search.players');
    Route::get('search/teams', [TeamController::class, 'ajaxSearch'])->name('search.teams');
    Route::get('search/matches', [GameController::class, 'ajaxSearch'])->name('search.matches');

    // Standings updates
    Route::get('standings/refresh', [StandingController::class, 'ajaxRefresh'])->name('standings.refresh');
});

// ==================== PDF/EXPORT ROUTES ====================

Route::prefix('export')->name('export.')->group(function () {
    Route::get('schedule/pdf', [GameController::class, 'exportSchedulePDF'])->name('schedule.pdf');
    Route::get('standings/pdf', [StandingController::class, 'exportStandingsPDF'])->name('standings.pdf');
    Route::get('teams/pdf', [TeamController::class, 'exportTeamsPDF'])->name('teams.pdf');
    Route::get('players/pdf', [PlayerController::class, 'exportPlayersPDF'])->name('players.pdf');
    Route::get('match/{id}/pdf', [GameController::class, 'exportMatchPDF'])->name('match.pdf');
});

// ==================== WEBSOCKET/EVENT STREAM ROUTES ====================

Route::get('/live/stream', [GameController::class, 'liveStream'])->name('live.stream');
Route::get('/live/events/{match}', [GameController::class, 'eventStream'])->name('live.events');
Route::get('/live/standings', [StandingController::class, 'liveStandings'])->name('live.standings');

// ==================== CUSTOM ERROR PAGES ====================

// Redirect setelah login
Route::get('/home', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect('/');
})->name('home');

Route::fallback(function () {
    return view('errors.404');
});

// ==================== TESTING ROUTES (Only for development) ====================

if (app()->environment('local')) {
    Route::prefix('test')->group(function () {
        Route::get('/database', function () {
            return response()->json([
                'teams' => \App\Models\Team::count(),
                'players' => \App\Models\Player::count(),
                'matches' => \App\Models\Game::count(),
                'tournaments' => \App\Models\Tournament::count(),
            ]);
        });

        Route::get('/seed-test-data', function () {
            Artisan::call('db:seed', ['--class' => 'TournamentDataSeeder']);

            return 'Test data seeded!';
        });

        Route::get('/clear-cache', function () {
            Artisan::call('optimize:clear');

            return 'Cache cleared!';
        });
    });
}

// ==================== MAINTENANCE MODE ====================

Route::get('/maintenance', function () {
    if (! app()->isDownForMaintenance()) {
        return redirect('/');
    }

    return view('maintenance');
})->name('maintenance');
