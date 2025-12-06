<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'name',
        'jersey_number',
        'position',
        'photo',
        'goals',
        'assists',
        'yellow_cards',
        'red_cards'
    ];

    // Relasi dengan Team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Relasi dengan MatchEvent
    public function matchEvents()
    {
        return $this->hasMany(MatchEvent::class);
    }

    // Scope untuk top scorer
    public function scopeTopScorers($query, $limit = 10)
    {
        return $query->orderBy('goals', 'desc')
            ->orderBy('assists', 'desc')
            ->limit($limit);
    }

    // Getter untuk nama dengan nomor
    public function getFullNameAttribute()
    {
        return "#{$this->jersey_number} {$this->name}";
    }

    // app/Models/Player.php

    // Tambahkan method ini jika belum ada
    public function tournamentGoals($tournamentId)
    {
        return $this->goals()
            ->whereHas('match', function ($query) use ($tournamentId) {
                $query->where('tournament_id', $tournamentId);
            })
            ->count();
    }

    public function tournamentYellowCards($tournamentId)
    {
        return DB::table('match_events')
            ->join('matches', 'match_events.match_id', '=', 'matches.id')
            ->where('match_events.player_id', $this->id)
            ->where('match_events.event_type', 'yellow_card')
            ->where('matches.tournament_id', $tournamentId)
            ->count();
    }

    public function tournamentRedCards($tournamentId)
    {
        return DB::table('match_events')
            ->join('matches', 'match_events.match_id', '=', 'matches.id')
            ->where('match_events.player_id', $this->id)
            ->where('match_events.event_type', 'red_card')
            ->where('matches.tournament_id', $tournamentId)
            ->count();
    }
}