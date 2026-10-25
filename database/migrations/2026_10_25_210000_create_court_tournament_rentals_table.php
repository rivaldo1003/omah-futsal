<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('court_tournament_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_id')->constrained()->restrictOnDelete();
            $table->foreignId('tournament_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tournament_name_snapshot', 150);
            $table->date('rental_date');
            $table->time('time_start');
            $table->time('time_end');
            $table->unsignedSmallInteger('duration_minutes');
            $table->string('status', 20)->default('scheduled');
            $table->text('admin_note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            $table->index(['court_id', 'rental_date', 'status']);
            $table->index(['tournament_id', 'rental_date']);
            $table->index(['rental_date', 'time_start', 'time_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('court_tournament_rentals');
    }
};
