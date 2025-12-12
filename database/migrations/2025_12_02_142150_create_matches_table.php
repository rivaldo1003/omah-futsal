<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->date('match_date');
            $table->time('time_start');
            $table->time('time_end');
            $table->foreignId('team_home_id')->constrained('teams');
            $table->foreignId('team_away_id')->constrained('teams');
            $table->integer('home_score')->default(0);
            $table->integer('away_score')->default(0);
            $table->string('venue')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'postponed'])->default('upcoming');
            $table->enum('round_type', ['group', 'semifinal', 'final', 'third_place'])->default('group');
            $table->char('group_name', 1)->nullable(); // NULL untuk babak knockout
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indeks untuk query yang sering digunakan
            $table->index(['match_date']);
            $table->index(['status']);
            $table->index(['round_type', 'group_name']);
            $table->index(['team_home_id', 'team_away_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
