<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourtDailyRevenueEntry extends Model
{
    use SoftDeletes;

    protected $fillable = ['operating_date', 'title', 'amount', 'note', 'updated_by'];

    protected $casts = [
        'operating_date' => 'date:Y-m-d',
        'amount' => 'decimal:2',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
