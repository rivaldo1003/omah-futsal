<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourtDailyRevenueEntry extends Model
{
    use SoftDeletes;

    public const PAYMENT_METHOD_CASH = 'cash';
    public const PAYMENT_METHOD_TRANSFER = 'bank_transfer';
    public const PAYMENT_METHOD_QRIS = 'qris';
    public const PAYMENT_METHOD_UNCLASSIFIED = 'unclassified';

    protected $fillable = [
        'operating_date',
        'title',
        'amount',
        'payment_method',
        'tournament_id',
        'court_id',
        'note',
        'updated_by',
    ];

    protected $casts = [
        'operating_date' => 'date:Y-m-d',
        'amount' => 'decimal:2',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            self::PAYMENT_METHOD_CASH => 'Tunai',
            self::PAYMENT_METHOD_TRANSFER => 'Transfer',
            self::PAYMENT_METHOD_QRIS => 'QRIS',
            default => 'Belum dirinci',
        };
    }
}
