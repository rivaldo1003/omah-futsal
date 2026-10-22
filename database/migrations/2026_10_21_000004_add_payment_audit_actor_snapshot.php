<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('court_booking_payment_audits')
            && !Schema::hasColumn('court_booking_payment_audits', 'admin_name_snapshot')) {
            Schema::table('court_booking_payment_audits', function (Blueprint $table): void {
                $table->string('admin_name_snapshot', 160)->nullable()->after('admin_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('court_booking_payment_audits')
            && Schema::hasColumn('court_booking_payment_audits', 'admin_name_snapshot')) {
            Schema::table('court_booking_payment_audits', function (Blueprint $table): void {
                $table->dropColumn('admin_name_snapshot');
            });
        }
    }
};
