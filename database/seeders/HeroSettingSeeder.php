<?php

namespace Database\Seeders;

use App\Models\HeroSetting;
use Illuminate\Database\Seeder;

class HeroSettingSeeder extends Seeder
{
    public function run(): void
    {
        HeroSetting::create([
            'title' => 'OFS Champions League 2024',
            'subtitle' => 'The ultimate futsal championship featuring elite teams competing for glory',
            'is_active' => true,
            'background_type' => 'gradient',
            'text_color' => '#ffffff',
        ]);
    }
}
