<?php

namespace App\Listeners;

use App\Events\MatchScoreUpdated;
use App\Services\StandingService;

class UpdateStandingsOnMatchCompleted
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MatchScoreUpdated $event): void
    {
        // Panggil service untuk update standings
        StandingService::updateStandings($event->match);
    }
}