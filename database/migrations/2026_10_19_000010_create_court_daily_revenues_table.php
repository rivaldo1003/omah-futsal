<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('court_daily_revenues')) {
            Schema::create('court_daily_revenues', function (Blueprint $table): void {
                $table->id();
                $table->date('operating_date')->unique('court_daily_revenues_date_unique');
                $table->decimal('parking_income', 12, 2)->default(0);
                $table->decimal('shop_income', 12, 2)->default(0);
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('updated_by', 'court_daily_revenues_updated_by_fk')
                    ->references('id')->on('users')->nullOnDelete();
            });
        }

        // Preserve amounts already recorded against bookings by moving their daily
        // totals into a date-based table before removing the booking-level columns.
        if (Schema::hasTable('court_bookings')
            && Schema::hasColumn('court_bookings', 'parking_income')
            && Schema::hasColumn('court_bookings', 'shop_income')) {
            $now = now();
            $legacyDailyTotals = DB::table('court_bookings')
                ->select('booking_date as operating_date')
                ->selectRaw('SUM(parking_income) as parking_income, SUM(shop_income) as shop_income')
                ->where(function ($query): void {
                    $query->where('parking_income', '>', 0)
                        ->orWhere('shop_income', '>', 0);
                })
                ->groupBy('booking_date')
                ->get()
                ->map(fn ($row): array => [
                    'operating_date' => $row->operating_date,
                    'parking_income' => $row->parking_income,
                    'shop_income' => $row->shop_income,
                    'updated_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
                ->all();

            foreach (array_chunk($legacyDailyTotals, 300) as $dailyTotalsChunk) {
                DB::table('court_daily_revenues')->insertOrIgnore($dailyTotalsChunk);
            }
        }

        $columnsToDrop = array_values(array_filter(
            ['parking_income', 'shop_income'],
            fn (string $column): bool => Schema::hasColumn('court_bookings', $column),
        ));

        if ($columnsToDrop !== []) {
            Schema::table('court_bookings', function (Blueprint $table) use ($columnsToDrop): void {
                $table->dropColumn($columnsToDrop);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('court_daily_revenues');

        if (Schema::hasTable('court_bookings')) {
            $columnsToRestore = array_values(array_filter(
                ['parking_income', 'shop_income'],
                fn (string $column): bool => !Schema::hasColumn('court_bookings', $column),
            ));

            if ($columnsToRestore !== []) {
                Schema::table('court_bookings', function (Blueprint $table) use ($columnsToRestore): void {
                    if (in_array('parking_income', $columnsToRestore, true)) {
                        $table->decimal('parking_income', 12, 2)->default(0);
                    }
                    if (in_array('shop_income', $columnsToRestore, true)) {
                        $table->decimal('shop_income', 12, 2)->default(0);
                    }
                });
            }
        }

    }
};
