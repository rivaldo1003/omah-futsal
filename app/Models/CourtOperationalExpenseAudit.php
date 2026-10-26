<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtOperationalExpenseAudit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'expense_id', 'actor_id', 'actor_name_snapshot', 'action', 'reason', 'before_data', 'after_data', 'created_at',
    ];

    protected $casts = [
        'before_data' => 'array',
        'after_data' => 'array',
        'created_at' => 'datetime',
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(CourtOperationalExpense::class, 'expense_id')->withTrashed();
    }
}
