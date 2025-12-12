<?php

// app/Models/MatchEvent.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan untuk tipe-hinting yang lebih baik

class MatchEvent extends Model
{
    // Pastikan semua kolom yang baru (team_id dan related_player_id)
    // serta kolom lama yang relevan ada di fillable.
    protected $fillable = [
        'match_id',
        'team_id',           // Kolom baru
        'player_id',
        'related_player_id', // Kolom baru
        'event_type',
        'minute',
        'description',
        'is_own_goal',
        'is_penalty',
    ];

    // Relasi ke Pertandingan
    // Model yang digunakan adalah Game (karena tabelnya adalah 'matches')
    public function match(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'match_id'); // Secara eksplisit tentukan Foreign Key
    }

    // Relasi ke Tim
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    // Relasi ke Pemain Utama (Pencetak Gol, Penerima Kartu, Pemain Keluar/Masuk)
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    // Relasi ke Pemain Terkait (Assister atau Pasangan Substitusi)
    public function relatedPlayer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'related_player_id');
    }
}
