<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtDailyRevenue extends Model
{
    protected $fillable = [
        'operating_date',
        'parking_income',
        'shop_income',
        'updated_by',
    ];

    protected $casts = [
        'operating_date' => 'date:Y-m-d',
        'parking_income' => 'decimal:2',
        'shop_income' => 'decimal:2',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
