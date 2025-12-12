<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'start_date',
        'end_date',
        'location',
        'organizer',
        'type',
        'status',
        'groups_count',
        'teams_per_group',
        'qualify_per_group',
        'settings',
        'logo',
        'banner',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'settings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'duration',
        'formatted_dates',
        'teams_count',
        'matches_count',
        'match_duration',
        'half_time',
        'points_win',
        'points_draw',
        'points_loss',
        'max_substitutes',
    ];

    // Relasi dengan teams (many-to-many)
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_tournament')
            ->withPivot('group_name', 'seed')
            ->withTimestamps();
    }

    // Relasi dengan matches (one-to-many)
    public function matches(): HasMany
    {
        return $this->hasMany(Game::class, 'tournament_id');
    }

    // Accessors untuk match settings
    public function getMatchDurationAttribute()
    {
        return $this->settings['match_duration'] ?? 40;
    }

    public function getHalfTimeAttribute()
    {
        return $this->settings['half_time'] ?? 10;
    }

    public function getExtraTimeAttribute()
    {
        return $this->settings['extra_time'] ?? 10;
    }

    public function getPointsWinAttribute()
    {
        return $this->settings['points_win'] ?? 3;
    }

    public function getPointsDrawAttribute()
    {
        return $this->settings['points_draw'] ?? 1;
    }

    public function getPointsLossAttribute()
    {
        return $this->settings['points_loss'] ?? 0;
    }

    public function getMaxSubstitutesAttribute()
    {
        return $this->settings['max_substitutes'] ?? 5;
    }

    public function getMatchesPerDayAttribute()
    {
        return $this->settings['matches_per_day'] ?? 4;
    }

    public function getMatchIntervalAttribute()
    {
        return $this->settings['match_interval'] ?? 30;
    }

    public function getAllowDrawAttribute()
    {
        return $this->settings['allow_draw'] ?? true;
    }

    // Helper methods
    public function getTeamsCountAttribute(): int
    {
        return $this->teams()->count();
    }

    public function getMatchesCountAttribute(): int
    {
        return $this->matches()->count();
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
            ->where('start_date', '>=', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors
    public function getDurationAttribute(): int
    {
        if ($this->start_date && $this->end_date) {
            return now()->parse($this->start_date)->diffInDays($this->end_date) + 1;
        }

        return 0;
    }

    public function getFormattedDatesAttribute(): string
    {
        if ($this->start_date && $this->end_date) {
            $start = now()->parse($this->start_date)->format('d M Y');
            $end = now()->parse($this->end_date)->format('d M Y');

            return "{$start} - {$end}";
        }

        return 'Not scheduled';
    }

    // Get groups list
    public function getGroupsAttribute(): array
    {
        if ($this->type === 'group_knockout' && $this->groups_count) {
            return range('A', chr(ord('A') + $this->groups_count - 1));
        }

        return [];
    }

    // Get teams by group
    public function getTeamsByGroup(string $group)
    {
        return $this->teams()->wherePivot('group_name', $group)->get();
    }

    // In Team model
    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'team_tournament')
            ->withPivot('group_name', 'seed')
            ->withTimestamps();
    }
}
