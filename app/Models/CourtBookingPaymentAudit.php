<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtBookingPaymentAudit extends Model
{
    protected $fillable = [
        'court_booking_id', 'court_booking_payment_id', 'admin_id', 'admin_name_snapshot', 'action', 'reason', 'before_data', 'after_data',
    ];

    protected $casts = ['before_data' => 'array', 'after_data' => 'array'];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
