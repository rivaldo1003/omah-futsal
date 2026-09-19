<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Rekonstruksi migrasi yang hilang dari repo.
 * Menambahkan kolom info kelahiran ke tabel players.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            if (! Schema::hasColumn('players', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('position');
            }
            if (! Schema::hasColumn('players', 'birth_place')) {
                $table->string('birth_place', 100)->nullable()->after('birth_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            if (Schema::hasColumn('players', 'birth_place')) {
                $table->dropColumn('birth_place');
            }
            if (Schema::hasColumn('players', 'birth_date')) {
                $table->dropColumn('birth_date');
            }
        });
    }
};
