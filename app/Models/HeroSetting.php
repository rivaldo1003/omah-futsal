<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSetting extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'is_active',
        'background_type',
        'background_color',
        'background_image',
        'text_color'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}