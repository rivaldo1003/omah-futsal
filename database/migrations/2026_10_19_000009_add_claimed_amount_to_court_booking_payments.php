<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('court_booking_payments', 'claimed_amount')) {
            Schema::table('court_booking_payments', function (Blueprint $table): void {
                $table->decimal('claimed_amount', 12, 2)->nullable()->after('amount');
            });
        }

        // Preserve amounts entered by customers before the proof-only flow.
        DB::table('court_booking_payments')
            ->whereNotNull('payment_proof_path')
            ->whereNull('claimed_amount')
            ->update(['claimed_amount' => DB::raw('amount')]);

        // Old pending uploads had a guessed DP/full label based on the customer input.
        DB::table('court_booking_payments')
            ->where('status', 'pending_review')
            ->whereNotNull('payment_proof_path')
            ->update(['stage' => 'unverified']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('court_booking_payments', 'claimed_amount')) {
            Schema::table('court_booking_payments', function (Blueprint $table): void {
                $table->dropColumn('claimed_amount');
            });
        }
    }
};
