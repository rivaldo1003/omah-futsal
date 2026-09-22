<?php

namespace App\Providers;

use App\Models\Player;
use App\Observers\PlayerObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Auto-generate transparent cutout when a player photo is uploaded/changed
        Player::observe(PlayerObserver::class);
    }
}
