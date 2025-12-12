// database/migrations/xxxx_create_hero_settings_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('OFS Champions League 2025');
            $table->text('subtitle')->default('The ultimate futsal championship featuring elite teams competing for glory');
            $table->boolean('is_active')->default(true);
            $table->string('background_type')->default('gradient'); // gradient, image, color
            $table->string('background_color')->nullable();
            $table->string('background_image')->nullable();
            $table->string('text_color')->default('#ffffff');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_settings');
    }
};
