<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'weekend_day_hourly_rate' => fn (Blueprint $table) => $table->decimal('weekend_day_hourly_rate', 12, 2)->nullable(),
            'weekend_night_hourly_rate' => fn (Blueprint $table) => $table->decimal('weekend_night_hourly_rate', 12, 2)->nullable(),
        ] as $column => $definition) {
            if (Schema::hasTable('courts') && !Schema::hasColumn('courts', $column)) {
                Schema::table('courts', $definition);
            }
        }

        if (Schema::hasTable('courts')) {
            if (Schema::hasColumn('courts', 'slot_duration_minutes')) {
                DB::table('courts')->update(['slot_duration_minutes' => 30]);
            }
            $dayRateColumn = Schema::hasColumn('courts', 'day_hourly_rate') ? 'day_hourly_rate' : 'hourly_rate';
            $nightRateColumn = Schema::hasColumn('courts', 'night_hourly_rate') ? 'night_hourly_rate' : 'hourly_rate';
            DB::table('courts')->whereNull('weekend_day_hourly_rate')->update([
                'weekend_day_hourly_rate' => DB::raw($dayRateColumn),
            ]);
            DB::table('courts')->whereNull('weekend_night_hourly_rate')->update([
                'weekend_night_hourly_rate' => DB::raw($nightRateColumn),
            ]);
        }

        if (!Schema::hasTable('court_member_schedules')) {
            Schema::create('court_member_schedules', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('court_id')->constrained()->cascadeOnDelete();
                $table->string('member_name', 120);
                $table->unsignedTinyInteger('day_of_week');
                $table->time('time_start');
                $table->time('time_end');
                $table->date('starts_on')->nullable();
                $table->date('ends_on')->nullable();
                $table->boolean('is_active')->default(true);
                $table->string('notes', 500)->nullable();
                $table->timestamps();
                $table->index(['court_id', 'day_of_week', 'is_active'], 'court_member_schedules_lookup_idx');
            });
        }

        if (!Schema::hasTable('court_member_schedule_days_off')) {
            Schema::create('court_member_schedule_days_off', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('court_member_schedule_id')->constrained('court_member_schedules')->cascadeOnDelete();
                $table->date('off_date');
                $table->string('note', 255)->nullable();
                $table->timestamps();
                $table->unique(['court_member_schedule_id', 'off_date'], 'court_member_day_off_unique');
            });
        }

        if (!Schema::hasTable('court_daily_revenue_entries')) {
            Schema::create('court_daily_revenue_entries', function (Blueprint $table): void {
                $table->id();
                $table->date('operating_date');
                $table->string('title', 120);
                $table->decimal('amount', 12, 2);
                $table->string('note', 500)->nullable();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
                $table->index(['operating_date', 'id'], 'court_daily_revenue_entries_date_idx');
            });
        }

        if (!Schema::hasTable('court_booking_payment_audits')) {
            Schema::create('court_booking_payment_audits', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('court_booking_id')->constrained('court_bookings')->cascadeOnDelete();
                $table->foreignId('court_booking_payment_id')->nullable()->constrained('court_booking_payments')->nullOnDelete();
                $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('action', 40);
                $table->string('reason', 500);
                $table->json('before_data')->nullable();
                $table->json('after_data')->nullable();
                $table->timestamps();
                $table->index(['court_booking_id', 'created_at'], 'court_booking_payment_audits_booking_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('court_booking_payment_audits');
        Schema::dropIfExists('court_daily_revenue_entries');
        Schema::dropIfExists('court_member_schedule_days_off');
        Schema::dropIfExists('court_member_schedules');

        if (Schema::hasTable('courts')) {
            if (Schema::hasColumn('courts', 'slot_duration_minutes')) {
                DB::table('courts')->update(['slot_duration_minutes' => 60]);
            }
            foreach (['weekend_day_hourly_rate', 'weekend_night_hourly_rate'] as $column) {
                if (Schema::hasColumn('courts', $column)) {
                    Schema::table('courts', fn (Blueprint $table) => $table->dropColumn($column));
                }
            }
        }
    }
};
