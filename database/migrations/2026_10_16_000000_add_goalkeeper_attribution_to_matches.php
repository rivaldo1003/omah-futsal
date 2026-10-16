<?php

use App\Services\GoalkeeperCleanSheetService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('match_player', 'position_snapshot')) {
            Schema::table('match_player', function (Blueprint $table) {
                $table->string('position_snapshot', 50)->nullable()->after('player_id');
            });
        }

        if (! Schema::hasColumn('match_events', 'conceded_by_goalkeeper_id')) {
            Schema::table('match_events', function (Blueprint $table) {
                $table->foreignId('conceded_by_goalkeeper_id')
                    ->nullable()
                    ->after('related_player_id')
                    ->constrained('players')
                    ->nullOnDelete();
            });
        }

        DB::table('match_player')
            ->whereNull('position_snapshot')
            ->orderBy('id')
            ->chunkById(500, function ($appearances) {
                $positions = DB::table('players')
                    ->whereIn('id', $appearances->pluck('player_id')->unique())
                    ->pluck('position', 'id');

                foreach ($appearances as $appearance) {
                    DB::table('match_player')
                        ->where('id', $appearance->id)
                        ->update([
                            'position_snapshot' => $positions->get($appearance->player_id),
                        ]);
                }
            });

        app(GoalkeeperCleanSheetService::class)->recalculateAll();
    }

    public function down(): void
    {
        if (Schema::hasColumn('match_events', 'conceded_by_goalkeeper_id')) {
            Schema::table('match_events', function (Blueprint $table) {
                $table->dropConstrainedForeignId('conceded_by_goalkeeper_id');
            });
        }

        if (Schema::hasColumn('match_player', 'position_snapshot')) {
            Schema::table('match_player', function (Blueprint $table) {
                $table->dropColumn('position_snapshot');
            });
        }
    }
};
