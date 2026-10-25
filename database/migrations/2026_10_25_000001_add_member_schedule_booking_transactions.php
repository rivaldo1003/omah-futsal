<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('court_bookings', 'court_member_schedule_id')) {
            Schema::table('court_bookings', function (Blueprint $table): void {
                $table->foreignId('court_member_schedule_id')
                    ->nullable()
                    ->after('court_id')
                    ->constrained('court_member_schedules')
                    ->nullOnDelete();
                $table->index(
                    ['court_member_schedule_id', 'booking_date'],
                    'court_bookings_member_occurrence_idx',
                );
            });
        }

        if (Schema::hasColumn('court_bookings', 'customer_phone')) {
            Schema::table('court_bookings', function (Blueprint $table): void {
                $table->string('customer_phone', 30)->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('court_bookings', 'court_member_schedule_id')) {
            Schema::table('court_bookings', function (Blueprint $table): void {
                $table->dropIndex('court_bookings_member_occurrence_idx');
                $table->dropConstrainedForeignId('court_member_schedule_id');
            });
        }

        if (Schema::hasColumn('court_bookings', 'customer_phone')) {
            DB::table('court_bookings')->whereNull('customer_phone')->update(['customer_phone' => '']);
            Schema::table('court_bookings', function (Blueprint $table): void {
                $table->string('customer_phone', 30)->nullable(false)->change();
            });
        }
    }
};
