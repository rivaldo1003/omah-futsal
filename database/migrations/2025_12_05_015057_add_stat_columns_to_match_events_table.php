<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Menggunakan Schema::table karena tabel sudah ada
        Schema::table('match_events', function (Blueprint $table) {

            // 1. Tambahkan kolom team_id
            // Gunakan after('match_id') agar urutannya bagus
            $table->foreignId('team_id')
                ->after('match_id')
                ->constrained('teams')
                ->onDelete('cascade');

            // 2. Tambahkan kolom related_player_id untuk assist/substitusi
            $table->foreignId('related_player_id')
                ->nullable()
                ->after('player_id')
                ->constrained('players')
                ->onDelete('cascade');

            // 3. Ubah kolom event_type (Hanya jika perlu menambahkan nilai baru seperti 'substitution')
            // Catatan: Jika Anda belum menginstal doctrine/dbal, Anda harus menjalankannya dulu: composer require doctrine/dbal
            $table->enum('event_type', [
                'goal',
                'yellow_card',
                'red_card',
                'substitution', // Nilai baru yang ditambahkan
                // Tambahkan lagi event type lama (misal 'assist', jika ada di versi lama)
            ])->change();

            // Opsional: Drop index lama yang mungkin sudah ada di event_type sebelum diubah
            // $table->dropIndex(['match_id', 'event_type']);
            // $table->index(['match_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::table('match_events', function (Blueprint $table) {
            // Urutan drop harus: drop index (jika dibuat), drop foreign key, drop column

            // Hapus foreign key dan kolom related_player_id
            $table->dropForeign(['related_player_id']);
            $table->dropColumn('related_player_id');

            // Hapus foreign key dan kolom team_id
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');

            // Kembalikan enum (Hapus 'substitution')
            $table->enum('event_type', [
                'goal',
                'yellow_card',
                'red_card',
                // masukkan kembali nilai enum yang ada sebelum penambahan 'substitution'
            ])->change();
        });
    }
};
