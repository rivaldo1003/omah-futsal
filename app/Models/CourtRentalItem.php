<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourtRentalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit_price',
        'stock_quantity',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
    ];

    public function bookingLines(): HasMany
    {
        return $this->hasMany(CourtBookingRentalLine::class);
    }
}
