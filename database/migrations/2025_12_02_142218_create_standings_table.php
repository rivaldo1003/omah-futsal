<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->char('group_name', 1);
            $table->integer('matches_played')->default(0);
            $table->integer('wins')->default(0);
            $table->integer('draws')->default(0);
            $table->integer('losses')->default(0);
            $table->integer('goals_for')->default(0);
            $table->integer('goals_against')->default(0);
            $table->integer('goal_difference')->default(0);
            $table->integer('points')->default(0);
            $table->timestamps();

            // Indeks untuk sorting klasemen
            $table->unique(['team_id', 'group_name']);
            $table->index(['group_name', 'points', 'goal_difference', 'goals_for']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standings');
    }
};
