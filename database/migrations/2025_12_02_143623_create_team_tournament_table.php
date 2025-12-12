<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_tournament', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->char('group_name', 1); // A, B, C, etc
            $table->integer('seed')->nullable(); // Urutan seed dalam grup
            $table->timestamps();

            $table->unique(['team_id', 'tournament_id']);
            $table->index(['tournament_id', 'group_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_tournament');
    }
};
