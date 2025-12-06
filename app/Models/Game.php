<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'tournament_id',
        'match_date',
        'time_start',
        'time_end',
        'team_home_id',
        'team_away_id',
        'home_score',
        'away_score',
        'venue',
        'status',
        'round_type',
        'group_name',
        'notes',
    ];

    protected $dates = ['match_date'];
    protected $casts = [
        'match_date' => 'date:Y-m-d',
    ];

    // Relasi dengan Tournament
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    // Relasi dengan Team Home
    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'team_home_id');
    }

    // Relasi dengan Team Away
    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'team_away_id');
    }

    // Relasi dengan MatchEvent - FIX: Tambahkan foreign key 'match_id'
    public function events()
    {
        return $this->hasMany(MatchEvent::class, 'match_id');
    }

    // Scope untuk match berdasarkan tournament
    public function scopeByTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    // Helper untuk format waktu
    public function getTimeRangeAttribute()
    {
        return date('H:i', strtotime($this->time_start)) . ' - ' .
            date('H:i', strtotime($this->time_end));
    }

    // Helper untuk hasil pertandingan
    public function getResultAttribute()
    {
        if ($this->status !== 'completed') {
            return 'VS';
        }
        return "{$this->home_score} - {$this->away_score}";
    }

    // Scope untuk match yang akan datang
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
            ->orderBy('match_date')
            ->orderBy('time_start');
    }

    // Scope untuk match yang selesai
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
            ->orderBy('match_date', 'desc');
    }

    // Scope untuk match berdasarkan grup
    public function scopeByGroup($query, $group)
    {
        return $query->where('group_name', $group);
    }
}