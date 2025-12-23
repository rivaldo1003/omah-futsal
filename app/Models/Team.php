<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'description',
        'logo',
        'coach_name',
        'coach_phone',
        'coach_email',
        'head_coach',
        'assistant_coach',
        'goalkeeper_coach',
        'kitman',
        'primary_color',
        'secondary_color',
        'status',
        'founded_year',
        'home_venue',
        'phone',
        'email',
        'website',
        'address',
        'tournament_id',
        // HAPUS field yang tidak ada di database:
        // 'contact_phone',  // TIDAK ADA di database
        // 'contact_email',  // TIDAK ADA di database
        // 'city',          // TIDAK ADA di database
        // 'country',       // TIDAK ADA di database
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