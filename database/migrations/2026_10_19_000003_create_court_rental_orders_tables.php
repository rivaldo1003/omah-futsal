<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('court_rental_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_token')->unique();
            $table->uuid('idempotency_key')->unique();
            $table->string('request_fingerprint', 64);
            $table->date('rental_date');
            $table->time('time_start');
            $table->time('time_end');
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

            $table->index(['rental_date', 'status']);
            $table->index(['status', 'expires_at']);
        });

        Schema::create('court_rental_order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_rental_order_id')->constrained('court_rental_orders')->cascadeOnDelete();
            $table->foreignId('court_rental_item_id')->nullable()->constrained('court_rental_items')->nullOnDelete();
            $table->string('item_name_snapshot', 100);
            $table->unsignedSmallInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('line_total', 12, 2);
            $table->timestamps();
            $table->unique(['court_rental_order_id', 'court_rental_item_id'], 'court_rental_order_item_unique');
            $table->index('court_rental_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('court_rental_order_lines');
        Schema::dropIfExists('court_rental_orders');
    }
};
