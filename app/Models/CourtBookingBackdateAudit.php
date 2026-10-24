<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtBookingBackdateAudit extends Model
{
    protected $fillable = [
        'court_booking_id',
        'admin_id',
        'admin_name_snapshot',
        'reason',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(CourtBooking::class, 'court_booking_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
