<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_events', function (Blueprint $table) {
            $table->id();

            // Relasi Utama
            $table->foreignId('match_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');

            // Pemain yang Terlibat
            // Pemain Utama: Pencetak Gol, Penerima Kartu, Pemain Keluar (Substitusi)
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            // Pemain Terkait: Assister, Pemain Masuk (Substitusi)
            $table->foreignId('related_player_id')->nullable()->constrained('players')->onDelete('cascade');

            // Tipe Event
            $table->enum('event_type', [
                'goal',
                'yellow_card',
                'red_card',
                'substitution', // Menggabungkan in/out, lihat catatan di bawah
            ]);

            // Detail Event
            $table->integer('minute');
            $table->boolean('is_own_goal')->default(false); // Apakah gol bunuh diri
            $table->boolean('is_penalty')->default(false); // Apakah gol dari penalti

            $table->string('description')->nullable();
            $table->timestamps();

            $table->index(['match_id', 'event_type']);
            $table->index(['player_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_events');
    }
};
