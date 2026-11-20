<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('court_booking_tournament')) {
            Schema::create('court_booking_tournament', function (Blueprint $table): void {
                $table->foreignId('court_booking_id')->constrained('court_bookings')->cascadeOnDelete();
                $table->foreignId('tournament_id')->constrained('tournaments')->cascadeOnDelete();
                $table->foreignId('linked_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->primary(['court_booking_id', 'tournament_id'], 'court_booking_tournament_primary');
                $table->index(['tournament_id', 'court_booking_id'], 'court_booking_tournament_reverse_idx');
            });
        }

        if (Schema::hasColumn('court_bookings', 'tournament_id')) {
            $legacyLinks = DB::table('court_bookings')
                ->whereNotNull('tournament_id')
                ->get(['id', 'tournament_id', 'created_at', 'updated_at']);

            foreach ($legacyLinks->chunk(500) as $chunk) {
                DB::table('court_booking_tournament')->insertOrIgnore($chunk->map(fn ($booking): array => [
                    'court_booking_id' => $booking->id,
                    'tournament_id' => $booking->tournament_id,
                    'linked_by' => null,
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->updated_at,
                ])->all());
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('court_booking_tournament');
    }
};
