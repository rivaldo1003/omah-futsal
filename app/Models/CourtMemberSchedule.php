<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourtMemberSchedule extends Model
{
    protected $fillable = [
        'court_id', 'member_name', 'day_of_week', 'time_start', 'time_end',
        'starts_on', 'ends_on', 'is_active', 'notes',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'starts_on' => 'date:Y-m-d',
        'ends_on' => 'date:Y-m-d',
        'is_active' => 'boolean',
    ];

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function daysOff(): HasMany
    {
        return $this->hasMany(CourtMemberScheduleDayOff::class);
    }
}
