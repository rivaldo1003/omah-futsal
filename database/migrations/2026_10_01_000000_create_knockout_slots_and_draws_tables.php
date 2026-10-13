<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bracket = struktur pertandingan (knockout_slots).
     * Draw    = riwayat penentuan posisi tim pada bracket (tournament_draws).
     *
     * Dipisah agar bracket bisa dibuat lebih dulu (slot kosong), undian
     * dijalankan transparan, lalu pertandingan mengisi slot pemenang.
     */
    public function up(): void
    {
        // ── Slot bagan: satu baris untuk tiap sisi pertandingan (home/away) ──
        Schema::create('knockout_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->cascadeOnDelete();

            $table->unsignedInteger('round_number');   // 1 = babak pertama
            $table->unsignedInteger('match_index');    // 0-based dalam satu babak
            $table->string('side', 5);                 // home | away

            // Nomor slot undian (hanya untuk slot daun / babak 1). 1..bracketSize
            $table->unsignedInteger('slot_number')->nullable();

            $table->unsignedBigInteger('match_id')->nullable();   // pertandingan pemilik slot
            $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();

            $table->string('source', 12)->default('empty'); // empty|draw|winner|bye
            $table->unsignedBigInteger('source_match_id')->nullable(); // match yang mengirim pemenang

            $table->string('status', 10)->default('empty'); // empty|filled
            $table->string('result', 12)->default('pending'); // pending|advanced|eliminated|bye

            $table->timestamps();

            $table->foreign('match_id')->references('id')->on('matches')->nullOnDelete();
            $table->foreign('source_match_id')->references('id')->on('matches')->nullOnDelete();

            $table->unique(['tournament_id', 'round_number', 'match_index', 'side'], 'knockout_slots_pos_unique');
            $table->unique(['tournament_id', 'slot_number'], 'knockout_slots_number_unique');
            $table->index(['tournament_id', 'round_number']);
        });

        // ── Riwayat undian (audit) ──
        Schema::create('tournament_draws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->cascadeOnDelete();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();

            $table->unsignedInteger('slot_number');    // nomor yang dicabut
            $table->unsignedBigInteger('slot_id')->nullable();

            $table->foreignId('drawn_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('drawn_at')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->string('note')->nullable();

            $table->timestamps();

            $table->foreign('slot_id')->references('id')->on('knockout_slots')->nullOnDelete();

            // Satu tim hanya boleh satu slot; satu slot hanya boleh satu tim.
            $table->unique(['tournament_id', 'team_id'], 'draw_team_unique');
            $table->unique(['tournament_id', 'slot_number'], 'draw_slot_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_draws');
        Schema::dropIfExists('knockout_slots');
    }
};