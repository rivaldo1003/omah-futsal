<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'team_id',
        'group_name',
        'matches_played',
        'wins',
        'draws',
        'losses',
        'goals_for',
        'goals_against',
        'goal_difference',
        'points',
        'form',
    ];

    /**
     * Get the tournament that owns the standing
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get the team that owns the standing
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Scope a query to only include standings for a specific tournament
     */
    public function scopeForTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    /**
     * Scope a query to only include standings for a specific group
     */
    public function scopeForGroup($query, $groupName)
    {
        return $query->where('group_name', $groupName);
    }

    /**
     * Update form string (recent matches)
     */
    public function updateForm($result)
    {
        $form = $this->form ?? '';
        $form = substr($form.$result, -5); // Keep only last 5 matches
        $this->update(['form' => $form]);
    }

    /**
     * Get recent form as array
     */
    public function getFormArrayAttribute()
    {
        $form = $this->form ?? '';

        return str_split(str_pad($form, 5, '-'));
    }

    /**
     * Calculate goal difference
     */
    public function getGoalDifferenceAttribute()
    {
        return $this->goals_for - $this->goals_against;
    }

    /**
     * Calculate points per match
     */
    public function getPointsPerMatchAttribute()
    {
        return $this->matches_played > 0
            ? round($this->points / $this->matches_played, 2)
            : 0;
    }

    /**
     * Calculate goals per match
     */
    public function getGoalsPerMatchAttribute()
    {
        return $this->matches_played > 0
            ? round($this->goals_for / $this->matches_played, 2)
            : 0;
    }
}
