<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->foreignId('tournament_id')->nullable()->constrained()->onDelete('cascade');
            // Group_name bisa nullable karena ada di matches juga
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropColumn('tournament_id');
        });
    }
};