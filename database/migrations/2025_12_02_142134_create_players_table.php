<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->integer('jersey_number')->nullable();
            $table->string('position', 50)->nullable();
            $table->string('photo')->nullable();
            $table->integer('goals')->default(0);
            $table->integer('assists')->default(0);
            $table->integer('yellow_cards')->default(0);
            $table->integer('red_cards')->default(0);
            $table->timestamps();

            // Indeks untuk pencarian
            $table->index(['team_id']);
            $table->index(['goals']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
