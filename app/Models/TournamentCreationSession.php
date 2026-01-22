<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentCreationSession extends Model
{
    protected $fillable = [
        'user_id',
        'session_token',
        'data',
        'current_step',
        'expires_at',
    ];

    protected $casts = [
        'data' => 'array',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk session yang belum expired
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
