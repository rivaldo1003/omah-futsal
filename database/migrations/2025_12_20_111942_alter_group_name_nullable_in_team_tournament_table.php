<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_tournament', function (Blueprint $table) {
            // Ubah kolom group_name menjadi nullable
            $table->char('group_name', 1)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('team_tournament', function (Blueprint $table) {
            // Kembalikan ke not nullable jika rollback
            $table->char('group_name', 1)->nullable(false)->change();
        });
    }
};