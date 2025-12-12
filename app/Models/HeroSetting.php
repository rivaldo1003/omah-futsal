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
        'text_color',
        'cta_button_text',
        'cta_button_link',
        'gradient_start',
        'gradient_end',
        'overlay_opacity',
        'button_color',
        'button_text_color',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'overlay_opacity' => 'integer',
    ];

    protected $attributes = [
        'overlay_opacity' => 0,
        'button_color' => '#3b82f6',
        'button_text_color' => '#ffffff',
        'gradient_start' => '#0f172a',
        'gradient_end' => '#1e293b',
    ];
}
