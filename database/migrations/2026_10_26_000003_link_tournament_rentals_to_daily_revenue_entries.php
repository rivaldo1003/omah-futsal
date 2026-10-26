<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('court_daily_revenue_entries')
            && ! Schema::hasColumn('court_daily_revenue_entries', 'court_tournament_rental_id')) {
            Schema::table('court_daily_revenue_entries', function (Blueprint $table): void {
                $table->foreignId('court_tournament_rental_id')
                    ->nullable()
                    ->after('court_id')
                    ->constrained('court_tournament_rentals')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('court_daily_revenue_entries')
            && Schema::hasColumn('court_daily_revenue_entries', 'court_tournament_rental_id')) {
            Schema::table('court_daily_revenue_entries', function (Blueprint $table): void {
                $table->dropForeign(['court_tournament_rental_id']);
                $table->dropColumn('court_tournament_rental_id');
            });
        }
    }
};
