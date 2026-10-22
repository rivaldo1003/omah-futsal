<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtBookingPayment extends Model
{
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_REJECTED = 'rejected';

    public const STAGE_DEPOSIT = 'dp';
    public const STAGE_FULL = 'full';
    public const STAGE_SETTLEMENT = 'settlement';
    public const STAGE_UNVERIFIED = 'unverified';

    protected $fillable = [
        'court_booking_id',
        'amount',
        'claimed_amount',
        'verified_amount',
        'stage',
        'payment_method',
        'payment_reference',
        'payment_proof_path',
        'payment_proof_original_name',
        'status',
        'note',
        'recorded_by',
        'verified_by',
        'recorded_at',
        'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'claimed_amount' => 'decimal:2',
        'verified_amount' => 'decimal:2',
        'recorded_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(CourtBooking::class, 'court_booking_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getStageLabelAttribute(): string
    {
        return match ($this->stage) {
            self::STAGE_UNVERIFIED => 'Menunggu verifikasi',
            self::STAGE_DEPOSIT => 'DP',
            self::STAGE_SETTLEMENT => 'Pelunasan',
            default => 'Lunas',
        };
    }

    public function getRecognizedAmountAttribute(): float
    {
        return (float) ($this->status === self::STATUS_CONFIRMED
            ? ($this->verified_amount ?? $this->amount)
            : ($this->claimed_amount ?? $this->amount));
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING_REVIEW => 'Menunggu verifikasi',
            self::STATUS_CONFIRMED => 'Terkonfirmasi',
            self::STATUS_REJECTED => 'Ditolak',
            default => 'Tidak diketahui',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash' => 'Tunai',
            'qris' => 'QRIS',
            default => 'Transfer bank',
        };
    }

}
