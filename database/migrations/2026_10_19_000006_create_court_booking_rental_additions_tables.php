<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('court_booking_rental_additions')) {
            Schema::create('court_booking_rental_additions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('court_booking_id')->constrained('court_bookings')->cascadeOnDelete();
                $table->uuid('idempotency_key')->unique();
                $table->char('request_fingerprint', 64);
                $table->decimal('amount', 12, 2);
                $table->string('payment_method', 30);
                $table->string('payment_reference', 100)->nullable();
                $table->string('payment_proof_path')->nullable();
                $table->string('payment_proof_original_name')->nullable();
                $table->string('payment_bank_name', 100)->nullable();
                $table->string('payment_bank_account_number', 100)->nullable();
                $table->string('payment_bank_account_name', 120)->nullable();
                $table->string('payment_qris_image_path')->nullable();
                $table->text('payment_instructions_snapshot')->nullable();
                $table->string('status', 30)->default('awaiting_payment');
                $table->text('admin_note')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('verified_at')->nullable();
                $table->timestamp('confirmed_at')->nullable();
                $table->timestamps();

                $table->index(['court_booking_id', 'status']);
                $table->index(['status', 'expires_at']);
            });
        }

        if (!Schema::hasTable('court_booking_rental_addition_lines')) {
            Schema::create('court_booking_rental_addition_lines', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('court_booking_rental_addition_id');
                $table->unsignedBigInteger('court_rental_item_id')->nullable();
                $table->string('item_name_snapshot', 100);
                $table->unsignedSmallInteger('quantity');
                $table->decimal('unit_price', 12, 2);
                $table->decimal('line_total', 12, 2);
                $table->timestamps();
            });
        }

        $lineTable = 'court_booking_rental_addition_lines';
        $existingIndexes = collect(DB::select(
            'SELECT DISTINCT INDEX_NAME FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
            [$lineTable],
        ))->pluck('INDEX_NAME')->all();

        Schema::table($lineTable, function (Blueprint $table) use ($existingIndexes) {
            if (!in_array('rental_addition_item_index', $existingIndexes, true)) {
                $table->index(['court_booking_rental_addition_id', 'court_rental_item_id'], 'rental_addition_item_index');
            }
            if (!in_array('cbral_item_idx', $existingIndexes, true)) {
                $table->index('court_rental_item_id', 'cbral_item_idx');
            }
        });

        $existingForeignColumns = collect(DB::select(
            'SELECT COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL',
            [$lineTable],
        ))->pluck('COLUMN_NAME')->all();

        Schema::table($lineTable, function (Blueprint $table) use ($existingForeignColumns) {
            if (!in_array('court_booking_rental_addition_id', $existingForeignColumns, true)) {
                $table->foreign('court_booking_rental_addition_id', 'cbral_addition_fk')
                    ->references('id')->on('court_booking_rental_additions')->cascadeOnDelete();
            }
            if (!in_array('court_rental_item_id', $existingForeignColumns, true)) {
                $table->foreign('court_rental_item_id', 'cbral_item_fk')
                    ->references('id')->on('court_rental_items')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('court_booking_rental_addition_lines');
        Schema::dropIfExists('court_booking_rental_additions');
    }
};
