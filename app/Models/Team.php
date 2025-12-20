<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'coach_name',
        'contact_phone',
        'contact_email',
        'description',
        'short_name',
        'status',
    ];

    // Relasi many-to-many dengan tournaments
    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'team_tournament')
            ->withPivot(['group_name', 'seed'])
            ->withTimestamps();
    }

    // Scope untuk teams dalam tournament tertentu
    public function scopeInTournament($query, $tournamentId)
    {
        return $query->whereHas('tournaments', function ($q) use ($tournamentId) {
            $q->where('tournament_id', $tournamentId);
        });
    }

    // Relasi dengan matches sebagai home team
    public function homeMatches()
    {
        return $this->hasMany(Game::class, 'team_home_id');
    }

    // Relasi dengan matches sebagai away team
    public function awayMatches()
    {
        return $this->hasMany(Game::class, 'team_away_id');
    }

    // Relasi dengan players
    public function players()
    {
        return $this->hasMany(Player::class);
    }
}