<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentDraw extends Model
{
    protected $fillable = [
        'tournament_id',
        'team_id',
        'slot_number',
        'slot_id',
        'drawn_by',
        'drawn_at',
        'ip',
        'user_agent',
        'note',
    ];

    protected $casts = [
        'slot_number' => 'integer',
        'drawn_at' => 'datetime',
    ];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(KnockoutSlot::class, 'slot_id');
    }

    public function drawnBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'drawn_by');
    }
}