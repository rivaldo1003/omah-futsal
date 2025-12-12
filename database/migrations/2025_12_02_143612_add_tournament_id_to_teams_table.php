<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->foreignId('tournament_id')->nullable()->constrained()->onDelete('cascade');
            // Hapus group_name dari teams karena akan di table pivot
            $table->dropColumn('group_name');
        });
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropColumn('tournament_id');
            $table->char('group_name', 1)->nullable();
        });
    }
};
