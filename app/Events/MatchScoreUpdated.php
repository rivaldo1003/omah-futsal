<?php

namespace App\Events;

use App\Models\Game;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchScoreUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $match;

    /**
     * Create a new event instance.
     */
    public function __construct(Game $match)
    {
        $this->match = $match;
    }
}