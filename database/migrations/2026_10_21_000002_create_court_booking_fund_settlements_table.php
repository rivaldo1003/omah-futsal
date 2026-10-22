<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('court_booking_fund_settlements')) {
            return;
        }

        Schema::create('court_booking_fund_settlements', function (Blueprint $table): void {
            $table->id();
            // Preserve financial audit rows; a booking with settlement history must not be hard-deleted.
            $table->foreignId('court_booking_id')->constrained('court_bookings')->restrictOnDelete();
            $table->uuid('request_key')->unique();
            $table->string('type', 20); // refund or retained
            $table->decimal('amount', 12, 2);
            $table->string('payment_method', 30)->nullable();
            $table->string('reference', 100)->nullable();
            $table->string('reason', 500);
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('recorded_by_name_snapshot', 160)->nullable();
            $table->timestamp('recorded_at');
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('voided_by_name_snapshot', 160)->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->string('void_reason', 500)->nullable();
            $table->timestamps();

            $table->index(['court_booking_id', 'type', 'voided_at'], 'cbfs_booking_type_voided_idx');
            $table->index(['recorded_at', 'type'], 'cbfs_recorded_type_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('court_booking_fund_settlements');
    }
};
