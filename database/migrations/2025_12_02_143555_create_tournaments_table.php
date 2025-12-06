<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Ofs Champions League 2025"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location')->nullable();
            $table->string('organizer')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->enum('type', ['league', 'knockout', 'group_knockout'])->default('group_knockout');
            $table->integer('teams_per_group')->default(5);
            $table->integer('groups_count')->default(2);
            $table->json('settings')->nullable(); // Untuk pengaturan khusus
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};