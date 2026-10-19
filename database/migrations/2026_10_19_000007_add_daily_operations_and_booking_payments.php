<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('court_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('court_bookings', 'team_name')) {
                $table->string('team_name', 120)->nullable();
            }
            if (!Schema::hasColumn('court_bookings', 'parking_income')) {
                $table->decimal('parking_income', 12, 2)->default(0);
            }
            if (!Schema::hasColumn('court_bookings', 'shop_income')) {
                $table->decimal('shop_income', 12, 2)->default(0);
            }
        });

        if (!Schema::hasTable('court_booking_payments')) {
            Schema::create('court_booking_payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('court_booking_id');
                $table->decimal('amount', 12, 2);
                $table->string('stage', 20);
                $table->string('payment_method', 30);
                $table->string('payment_reference', 100)->nullable();
                $table->string('payment_proof_path')->nullable();
                $table->string('payment_proof_original_name')->nullable();
                $table->string('status', 30)->default('pending_review');
                $table->text('note')->nullable();
                $table->unsignedBigInteger('recorded_by')->nullable();
                $table->unsignedBigInteger('verified_by')->nullable();
                $table->timestamp('recorded_at')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->timestamps();

                $table->index(['court_booking_id', 'status'], 'cbp_booking_status_idx');
                $table->index(['status', 'verified_at'], 'cbp_status_verified_idx');
                $table->foreign('court_booking_id', 'cbp_booking_fk')
                    ->references('id')->on('court_bookings')->cascadeOnDelete();
                $table->foreign('recorded_by', 'cbp_recorder_fk')
                    ->references('id')->on('users')->nullOnDelete();
                $table->foreign('verified_by', 'cbp_verifier_fk')
                    ->references('id')->on('users')->nullOnDelete();
            });
        }

        if (Schema::hasColumn('court_bookings', 'status') && Schema::hasTable('court_booking_payments')) {
            DB::table('court_bookings')
                ->whereIn('status', ['confirmed', 'pending_review'])
                ->whereNotExists(function ($query): void {
                    $query->select(DB::raw(1))
                        ->from('court_booking_payments')
                        ->whereColumn('court_booking_payments.court_booking_id', 'court_bookings.id');
                })
                ->orderBy('id')
                ->chunkById(300, function ($bookings): void {
                    $payments = $bookings->map(function ($booking): array {
                        $isConfirmed = $booking->status === 'confirmed';

                        return [
                            'court_booking_id' => $booking->id,
                            'amount' => $booking->amount,
                            'stage' => 'full',
                            'payment_method' => $booking->payment_method,
                            'payment_reference' => $booking->payment_reference,
                            'payment_proof_path' => $booking->payment_proof_path,
                            'payment_proof_original_name' => $booking->payment_proof_original_name,
                            'status' => $isConfirmed ? 'confirmed' : 'pending_review',
                            'note' => null,
                            'recorded_by' => $booking->created_by,
                            'verified_by' => $isConfirmed ? $booking->verified_by : null,
                            'recorded_at' => $isConfirmed ? ($booking->confirmed_at ?? $booking->created_at) : $booking->created_at,
                            'verified_at' => $isConfirmed ? ($booking->verified_at ?? $booking->confirmed_at) : null,
                            'created_at' => $booking->created_at,
                            'updated_at' => $booking->updated_at,
                        ];
                    })->all();

                    if ($payments !== []) {
                        DB::table('court_booking_payments')->insert($payments);
                    }
                });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('court_booking_payments');

        Schema::table('court_bookings', function (Blueprint $table) {
            foreach (['team_name', 'parking_income', 'shop_income'] as $column) {
                if (Schema::hasColumn('court_bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
