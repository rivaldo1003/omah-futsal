<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('court_booking_payments', 'verified_amount')) {
            Schema::table('court_booking_payments', function (Blueprint $table): void {
                $table->decimal('verified_amount', 12, 2)->nullable()->after('amount');
            });
        }

        DB::table('court_booking_payments')
            ->where('status', 'confirmed')
            ->whereNull('verified_amount')
            ->update(['verified_amount' => DB::raw('amount')]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('court_booking_payments', 'verified_amount')) {
            Schema::table('court_booking_payments', function (Blueprint $table): void {
                $table->dropColumn('verified_amount');
            });
        }
    }
};
