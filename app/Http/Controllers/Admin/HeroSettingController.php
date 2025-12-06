<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSettingController extends Controller
{
    public function edit()
    {
        $heroSetting = HeroSetting::first();

        if (!$heroSetting) {
            $heroSetting = HeroSetting::create([
                'title' => 'OFS Champions League 2025',
                'subtitle' => 'The ultimate futsal championship featuring elite teams competing for glory',
                'is_active' => true,
                'background_type' => 'gradient',
                'text_color' => '#ffffff'
            ]);
        }

        return view('admin.hero-settings.edit', compact('heroSetting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:500',
            'is_active' => 'boolean',
            'background_type' => 'required|in:gradient,image,color',
            'background_color' => 'nullable|string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'text_color' => 'required|string'
        ]);

        $heroSetting = HeroSetting::firstOrNew();

        $data = $request->only([
            'title',
            'subtitle',
            'is_active',
            'background_type',
            'background_color',
            'text_color'
        ]);

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            if ($heroSetting->background_image) {
                Storage::disk('public')->delete($heroSetting->background_image);
            }

            $path = $request->file('background_image')->store('hero-backgrounds', 'public');
            $data['background_image'] = $path;
        }

        $heroSetting->fill($data);
        $heroSetting->save();

        return redirect()->route('admin.hero-settings.update')
            ->with('success', 'Hero section updated successfully!');
    }
}