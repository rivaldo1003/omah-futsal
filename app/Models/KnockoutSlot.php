<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnockoutSlot extends Model
{
    // Slot status
    public const STATUS_EMPTY = 'empty';
    public const STATUS_FILLED = 'filled';

    // Slot result (post-match)
    public const RESULT_PENDING = 'pending';
    public const RESULT_ADVANCED = 'advanced';
    public const RESULT_ELIMINATED = 'eliminated';
    public const RESULT_BYE = 'bye';

    // Slot source
    public const SOURCE_EMPTY = 'empty';
    public const SOURCE_DRAW = 'draw';
    public const SOURCE_WINNER = 'winner';
    public const SOURCE_BYE = 'bye';

    protected $fillable = [
        'tournament_id',
        'round_number',
        'match_index',
        'side',
        'slot_number',
        'match_id',
        'team_id',
        'source',
        'source_match_id',
        'status',
        'result',
    ];

    protected $casts = [
        'round_number' => 'integer',
        'match_index' => 'integer',
        'slot_number' => 'integer',
    ];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'match_id');
    }

    public function isFilled(): bool
    {
        return $this->status === self::STATUS_FILLED && $this->team_id !== null;
    }

    public function isBye(): bool
    {
        return $this->source === self::SOURCE_BYE;
    }

    /**
     * The sibling slot of the same match (the other side).
     */
    public function sibling()
    {
        return self::where('tournament_id', $this->tournament_id)
            ->where('round_number', $this->round_number)
            ->where('match_index', $this->match_index)
            ->where('side', $this->side === 'home' ? 'away' : 'home')
            ->first();
    }
}