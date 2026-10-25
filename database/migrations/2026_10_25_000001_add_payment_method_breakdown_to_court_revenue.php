<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('court_daily_revenues')) {
            $legacyColumns = [];
            Schema::table('court_daily_revenues', function (Blueprint $table) use (&$legacyColumns): void {
                foreach (['parking', 'shop'] as $source) {
                    foreach (['cash', 'transfer', 'qris', 'unclassified'] as $method) {
                        $column = "{$source}_{$method}_income";
                        if (! Schema::hasColumn('court_daily_revenues', $column)) {
                            $table->decimal($column, 12, 2)->default(0);
                            if ($method === 'unclassified') {
                                $legacyColumns[] = [$source, $column];
                            }
                        }
                    }
                }
            });

            foreach ($legacyColumns as [$source, $column]) {
                DB::table('court_daily_revenues')->update([
                    $column => DB::raw("{$source}_income"),
                ]);
            }
        }

        if (Schema::hasTable('court_daily_revenue_entries')) {
            if (! Schema::hasColumn('court_daily_revenue_entries', 'payment_method')) {
                Schema::table('court_daily_revenue_entries', function (Blueprint $table): void {
                    $table->string('payment_method', 30)->default('unclassified');
                });
            }

            if (! Schema::hasColumn('court_daily_revenue_entries', 'tournament_id')) {
                Schema::table('court_daily_revenue_entries', function (Blueprint $table): void {
                    $table->foreignId('tournament_id')->nullable()->constrained()->nullOnDelete();
                });
            }

            if (! Schema::hasColumn('court_daily_revenue_entries', 'court_id')) {
                Schema::table('court_daily_revenue_entries', function (Blueprint $table): void {
                    $table->foreignId('court_id')->nullable()->constrained()->nullOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('court_daily_revenue_entries')) {
            foreach (['court_id', 'tournament_id', 'payment_method'] as $column) {
                if (Schema::hasColumn('court_daily_revenue_entries', $column)) {
                    Schema::table('court_daily_revenue_entries', function (Blueprint $table) use ($column): void {
                        if (in_array($column, ['court_id', 'tournament_id'], true)) {
                            $table->dropForeign([$column]);
                        }
                        $table->dropColumn($column);
                    });
                }
            }
        }

        if (Schema::hasTable('court_daily_revenues')) {
            $columns = [];
            foreach (['parking', 'shop'] as $source) {
                foreach (['cash', 'transfer', 'qris', 'unclassified'] as $method) {
                    $column = "{$source}_{$method}_income";
                    if (Schema::hasColumn('court_daily_revenues', $column)) {
                        $columns[] = $column;
                    }
                }
            }

            if ($columns !== []) {
                Schema::table('court_daily_revenues', fn (Blueprint $table) => $table->dropColumn($columns));
            }
        }
    }
};
