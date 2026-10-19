<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtRentalOrderLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'court_rental_order_id', 'court_rental_item_id', 'item_name_snapshot', 'quantity', 'unit_price', 'line_total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(CourtRentalOrder::class, 'court_rental_order_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(CourtRentalItem::class, 'court_rental_item_id');
    }
}
