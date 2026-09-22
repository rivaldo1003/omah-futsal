<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_settings', function (Blueprint $table) {
            $table->string('title', 255)->nullable()->change();
            $table->string('subtitle', 500)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('hero_settings', function (Blueprint $table) {
            $table->string('title', 255)->nullable(false)->change();
            $table->string('subtitle', 500)->nullable(false)->change();
        });
    }
};
