<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom team_home_id / team_away_id harus nullable agar bracket
     * knockout bisa dibuat sebagai placeholder (tim diisi pemenang nanti).
     */
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->foreignId('team_home_id')->nullable()->change();
            $table->foreignId('team_away_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->foreignId('team_home_id')->nullable(false)->change();
            $table->foreignId('team_away_id')->nullable(false)->change();
        });
    }
};