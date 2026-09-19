<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Rekonstruksi migrasi yang hilang dari repo.
 * Menambahkan kolom tambahan hero settings: CTA button, gradient, overlay, warna tombol.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('hero_settings', 'cta_button_text')) {
                $table->string('cta_button_text')->nullable()->after('subtitle');
            }
            if (! Schema::hasColumn('hero_settings', 'cta_button_link')) {
                $table->string('cta_button_link')->nullable()->after('cta_button_text');
            }
            if (! Schema::hasColumn('hero_settings', 'gradient_start')) {
                $table->string('gradient_start')->nullable()->after('background_color');
            }
            if (! Schema::hasColumn('hero_settings', 'gradient_end')) {
                $table->string('gradient_end')->nullable()->after('gradient_start');
            }
            if (! Schema::hasColumn('hero_settings', 'overlay_opacity')) {
                $table->integer('overlay_opacity')->default(50)->after('background_image');
            }
            if (! Schema::hasColumn('hero_settings', 'button_color')) {
                $table->string('button_color')->nullable()->after('text_color');
            }
            if (! Schema::hasColumn('hero_settings', 'button_text_color')) {
                $table->string('button_text_color')->nullable()->after('button_color');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hero_settings', function (Blueprint $table) {
            foreach (['cta_button_text', 'cta_button_link', 'gradient_start', 'gradient_end', 'overlay_opacity', 'button_color', 'button_text_color'] as $column) {
                if (Schema::hasColumn('hero_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
