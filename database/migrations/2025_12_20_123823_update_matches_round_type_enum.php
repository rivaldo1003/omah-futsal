<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom round_type dari ENUM ke VARCHAR
        Schema::table('matches', function (Blueprint $table) {
            // Ubah tipe data menjadi string dengan length yang cukup
            $table->string('round_type', 50)->default('group')->change();
        });
    }

    public function down(): void
    {
        // Kembalikan ke ENUM yang lama
        // Note: MySQL tidak mendukung perubahan langsung dari VARCHAR ke ENUM
        // Kita perlu membuat kolom baru atau menggunakan raw SQL
        DB::statement("ALTER TABLE matches MODIFY round_type ENUM('group', 'semifinal', 'final', 'third_place') DEFAULT 'group'");
    }
};