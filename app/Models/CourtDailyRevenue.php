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
        'parking_cash_income',
        'parking_transfer_income',
        'parking_qris_income',
        'parking_unclassified_income',
        'shop_cash_income',
        'shop_transfer_income',
        'shop_qris_income',
        'shop_unclassified_income',
        'updated_by',
    ];

    protected $casts = [
        'operating_date' => 'date:Y-m-d',
        'parking_income' => 'decimal:2',
        'shop_income' => 'decimal:2',
        'parking_cash_income' => 'decimal:2',
        'parking_transfer_income' => 'decimal:2',
        'parking_qris_income' => 'decimal:2',
        'parking_unclassified_income' => 'decimal:2',
        'shop_cash_income' => 'decimal:2',
        'shop_transfer_income' => 'decimal:2',
        'shop_qris_income' => 'decimal:2',
        'shop_unclassified_income' => 'decimal:2',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
