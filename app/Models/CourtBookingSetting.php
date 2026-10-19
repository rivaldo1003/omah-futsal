<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtBookingSetting extends Model
{
    protected $fillable = [
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'qris_image_path',
        'payment_instructions',
        'booking_policy',
        'payment_hold_minutes',
        'updated_by',
    ];

    public function hasBankTransfer(): bool
    {
        return filled($this->bank_name)
            && filled($this->bank_account_number)
            && filled($this->bank_account_name);
    }

    public function hasQris(): bool
    {
        return filled($this->qris_image_path);
    }
}
