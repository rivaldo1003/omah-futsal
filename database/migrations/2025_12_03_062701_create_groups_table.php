<?php

// database/migrations/*_create_groups_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
            $table->string('name', 50); // Contoh: "Group A", "Group B"
            $table->timestamps();

            // Tambahkan index unik untuk mencegah duplikasi grup dalam 1 turnamen
            $table->unique(['tournament_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
