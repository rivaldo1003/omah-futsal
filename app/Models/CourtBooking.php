<?php

namespace App\Models;

use App\Support\BusinessTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourtBooking extends Model
{
    use HasFactory;

    public const TIMEZONE = BusinessTime::TIMEZONE;

    public const STATUS_AWAITING_PAYMENT = 'awaiting_payment';
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'public_token',
        'idempotency_key',
        'request_fingerprint',
        'court_id',
        'booking_date',
        'time_start',
        'time_end',
        'duration_minutes',
        'amount',
        'team_name',
        'customer_name',
        'customer_phone',
        'customer_email',
        'payment_method',
        'source',
        'created_by',
        'payment_reference',
        'payment_proof_path',
        'payment_proof_original_name',
        'payment_bank_name',
        'payment_bank_account_number',
        'payment_bank_account_name',
        'payment_qris_image_path',
        'payment_instructions_snapshot',
        'booking_policy_snapshot',
        'status',
        'admin_note',
        'expires_at',
        'verified_by',
        'cancelled_by',
        'cancelled_by_name_snapshot',
        'verified_at',
        'confirmed_at',
        'cancelled_at',
        'rental_picked_up_at',
        'rental_returned_at',
    ];

    protected $casts = [
        'booking_date' => 'date:Y-m-d',
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'rental_picked_up_at' => 'datetime',
        'rental_returned_at' => 'datetime',
    ];

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function rentalLines(): HasMany
    {
        return $this->hasMany(CourtBookingRentalLine::class);
    }

    public function rentalAdditions(): HasMany
    {
        return $this->hasMany(CourtBookingRentalAddition::class, 'court_booking_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(CourtBookingPayment::class, 'court_booking_id')->orderBy('recorded_at')->orderBy('id');
    }

    public function fundSettlements(): HasMany
    {
        return $this->hasMany(CourtBookingFundSettlement::class, 'court_booking_id')->orderBy('recorded_at')->orderBy('id');
    }

    public function paymentAudits(): HasMany
    {
        return $this->hasMany(CourtBookingPaymentAudit::class, 'court_booking_id')->orderBy('created_at')->orderBy('id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_AWAITING_PAYMENT => 'Menunggu pembayaran',
            self::STATUS_PENDING_REVIEW => 'Menunggu verifikasi',
            self::STATUS_CONFIRMED => 'Terkonfirmasi',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_EXPIRED => 'Kedaluwarsa',
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

    public function getDurationLabelAttribute(): string
    {
        $hours = intdiv((int) $this->duration_minutes, 60);
        $minutes = (int) $this->duration_minutes % 60;

        if ($hours === 0) {
            return $minutes.' menit';
        }

        return $minutes === 0 ? $hours.' jam' : $hours.' jam '.$minutes.' menit';
    }
}
