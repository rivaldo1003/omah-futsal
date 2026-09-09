<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Menggunakan Schema::table karena tabel sudah ada
        Schema::table('match_events', function (Blueprint $table) {

            // Ubah kolom event_type (Hanya jika perlu menambahkan nilai baru seperti 'substitution')
            // Catatan: Jika Anda belum menginstal doctrine/dbal, Anda harus menjalankannya dulu: composer require doctrine/dbal
            $table->enum('event_type', [
                'goal',
                'yellow_card',
                'red_card',
                'substitution',
                'penalty',
                'foul',
                'injury',
                'assist',
                'save',
                'clean_sheet'
            ])->change();

            // Opsional: Drop index lama yang mungkin sudah ada di event_type sebelum diubah
            // $table->dropIndex(['match_id', 'event_type']);
            // $table->index(['match_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::table('match_events', function (Blueprint $table) {
            // Kembalikan enum (Hapus 'substitution')
            $table->enum('event_type', [
                'goal',
                'yellow_card',
                'red_card',
                'substitution',
                'assist',
                'penalty',
                'save',
                'clean_sheet',
            ])->change();
        });
    }
};
