<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtBookingFundSettlement extends Model
{
    public const TYPE_REFUND = 'refund';
    public const TYPE_RETAINED = 'retained';

    protected $fillable = [
        'court_booking_id',
        'request_key',
        'type',
        'amount',
        'payment_method',
        'reference',
        'reason',
        'recorded_by',
        'recorded_by_name_snapshot',
        'recorded_at',
        'voided_by',
        'voided_by_name_snapshot',
        'voided_at',
        'void_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'recorded_at' => 'datetime',
        'voided_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(CourtBooking::class, 'court_booking_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function voidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_REFUND => 'Pengembalian dana',
            self::TYPE_RETAINED => 'Dana ditahan',
            default => 'Tidak diketahui',
        };
    }

    public function getPaymentMethodLabelAttribute(): ?string
    {
        return match ($this->payment_method) {
            'cash' => 'Tunai',
            'bank_transfer' => 'Transfer bank',
            'qris' => 'QRIS',
            default => null,
        };
    }
}
