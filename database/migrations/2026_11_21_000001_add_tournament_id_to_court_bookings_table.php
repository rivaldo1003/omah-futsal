<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('court_bookings', 'tournament_id')) {
            Schema::table('court_bookings', function (Blueprint $table): void {
                $table->foreignId('tournament_id')
                    ->nullable()
                    ->after('court_id')
                    ->constrained('tournaments')
                    ->nullOnDelete();
                $table->index(['tournament_id', 'booking_date', 'status'], 'court_bookings_tournament_schedule_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('court_bookings', 'tournament_id')) {
            Schema::table('court_bookings', function (Blueprint $table): void {
                $table->dropForeign(['tournament_id']);
                $table->dropIndex('court_bookings_tournament_schedule_idx');
                $table->dropColumn('tournament_id');
            });
        }
    }
};
