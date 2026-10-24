<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('court_booking_schedule_audits')) {
            return;
        }

        Schema::create('court_booking_schedule_audits', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('court_booking_id')->constrained('court_bookings')->restrictOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('admin_name_snapshot', 160)->nullable();
            $table->string('reason', 500);
            $table->json('before_data');
            $table->json('after_data');
            $table->timestamps();

            $table->index(['court_booking_id', 'created_at'], 'court_booking_schedule_audits_booking_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('court_booking_schedule_audits');
    }
};
