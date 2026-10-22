<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->decimal('hourly_rate', 12, 2);
            $table->time('opening_time')->default('08:00:00');
            $table->time('closing_time')->default('23:00:00');
            $table->unsignedSmallInteger('slot_duration_minutes')->default(30);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('court_bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_token')->unique();
            $table->foreignId('court_id')->constrained()->restrictOnDelete();
            $table->date('booking_date');
            $table->time('time_start');
            $table->time('time_end');
            $table->unsignedSmallInteger('duration_minutes');
            $table->decimal('amount', 12, 2);
            $table->string('customer_name', 120);
            $table->string('customer_phone', 30);
            $table->string('customer_email')->nullable();
            $table->string('payment_method', 30);
            $table->string('payment_reference', 100)->nullable();
            $table->string('payment_proof_path')->nullable();
            $table->string('payment_proof_original_name')->nullable();
            $table->string('payment_bank_name', 100)->nullable();
            $table->string('payment_bank_account_number', 100)->nullable();
            $table->string('payment_bank_account_name', 120)->nullable();
            $table->string('payment_qris_image_path')->nullable();
            $table->text('payment_instructions_snapshot')->nullable();
            $table->text('booking_policy_snapshot')->nullable();
            $table->string('status', 30)->default('awaiting_payment');
            $table->text('admin_note')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['court_id', 'booking_date', 'status']);
            $table->index(['booking_date', 'status']);
            $table->index(['status', 'expires_at']);
        });

        Schema::create('court_booking_settings', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_number', 100)->nullable();
            $table->string('bank_account_name', 120)->nullable();
            $table->string('qris_image_path')->nullable();
            $table->text('payment_instructions')->nullable();
            $table->text('booking_policy')->nullable();
            $table->unsignedSmallInteger('payment_hold_minutes')->default(60);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('court_booking_settings');
        Schema::dropIfExists('court_bookings');
        Schema::dropIfExists('courts');
    }
};
