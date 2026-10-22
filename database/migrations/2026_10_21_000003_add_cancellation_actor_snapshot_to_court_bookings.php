<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('court_bookings', 'cancelled_by_name_snapshot')) {
            Schema::table('court_bookings', function (Blueprint $table): void {
                $table->string('cancelled_by_name_snapshot', 160)->nullable()->after('cancelled_by');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('court_bookings', 'cancelled_by_name_snapshot')) {
            Schema::table('court_bookings', function (Blueprint $table): void {
                $table->dropColumn('cancelled_by_name_snapshot');
            });
        }
    }
};
