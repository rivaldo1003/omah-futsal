<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtBookingRentalAdditionLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'court_booking_rental_addition_id',
        'court_rental_item_id',
        'item_name_snapshot',
        'quantity',
        'unit_price',
        'line_total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function addition(): BelongsTo
    {
        return $this->belongsTo(CourtBookingRentalAddition::class, 'court_booking_rental_addition_id');
    }

    public function rentalItem(): BelongsTo
    {
        return $this->belongsTo(CourtRentalItem::class, 'court_rental_item_id');
    }
}
