<?php

// database/migrations/*_create_group_team_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan tabel "group_team" menggunakan nama singular, sesuai konvensi Laravel pivot
        Schema::create('group_team', function (Blueprint $table) {
            // Foreign Key ke tabel groups
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');

            // Foreign Key ke tabel teams
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');

            // Menetapkan primary key gabungan (compound key)
            $table->primary(['group_id', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_team');
    }
};
