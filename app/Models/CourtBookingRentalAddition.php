<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourtBookingRentalAddition extends Model
{
    use HasFactory;

    public const STATUS_AWAITING_PAYMENT = 'awaiting_payment';
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'court_booking_id',
        'idempotency_key',
        'request_fingerprint',
        'amount',
        'payment_method',
        'payment_reference',
        'payment_proof_path',
        'payment_proof_original_name',
        'payment_bank_name',
        'payment_bank_account_number',
        'payment_bank_account_name',
        'payment_qris_image_path',
        'payment_instructions_snapshot',
        'status',
        'admin_note',
        'expires_at',
        'created_by',
        'verified_by',
        'verified_at',
        'confirmed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(CourtBooking::class, 'court_booking_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(CourtBookingRentalAdditionLine::class, 'court_booking_rental_addition_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_AWAITING_PAYMENT => 'Menunggu pembayaran',
            self::STATUS_PENDING_REVIEW => 'Menunggu verifikasi',
            self::STATUS_CONFIRMED => 'Terkonfirmasi',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_EXPIRED => 'Kedaluwarsa',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => 'Tidak diketahui',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash' => 'Tunai',
            'qris' => 'QRIS',
            'bank_transfer' => 'Transfer bank',
            default => '—',
        };
    }
}
