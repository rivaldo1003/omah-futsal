<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixStandingsUniqueConstraintSafely extends Migration
{
    /**
     * Get all index names on the standings table.
     */
    private function existingIndexes(): array
    {
        return collect(DB::select('SHOW INDEX FROM standings'))
            ->pluck('Key_name')
            ->unique()
            ->toArray();
    }

    /**
     * Get foreign key constraint names on the standings table.
     */
    private function existingForeignKeys(): array
    {
        return collect(DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = 'standings'
               AND REFERENCED_TABLE_NAME IS NOT NULL"
        ))->pluck('CONSTRAINT_NAME')->toArray();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add tournament_id column if it doesn't exist
        if (!Schema::hasColumn('standings', 'tournament_id')) {
            Schema::table('standings', function (Blueprint $table) {
                $table->foreignId('tournament_id')->nullable()->after('team_id');
            });
        }

        $indexes = $this->existingIndexes();

        // Step 2: Drop old index (only if it exists — avoids errors on servers
        // where the index was already dropped or has a different name)
        if (in_array('standings_team_id_group_name_unique', $indexes)) {
            Schema::table('standings', function (Blueprint $table) {
                $table->dropUnique('standings_team_id_group_name_unique');
            });
        }

        $indexes = $this->existingIndexes();

        // Step 3: Add new unique index with tournament_id (only if missing)
        if (!in_array('standings_tournament_team_group_unique', $indexes)) {
            Schema::table('standings', function (Blueprint $table) {
                $table->unique(['tournament_id', 'team_id', 'group_name'], 'standings_tournament_team_group_unique');
            });
        }

        // Step 4: Add foreign key constraints (only if missing)
        $foreignKeys = $this->existingForeignKeys();

        Schema::table('standings', function (Blueprint $table) use ($foreignKeys) {
            if (!in_array('standings_team_id_foreign', $foreignKeys)) {
                $table->foreign('team_id')
                    ->references('id')
                    ->on('teams')
                    ->onDelete('cascade');
            }

            if (!in_array('standings_tournament_id_foreign', $foreignKeys)) {
                $table->foreign('tournament_id')
                    ->references('id')
                    ->on('tournaments')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $foreignKeys = $this->existingForeignKeys();

        Schema::table('standings', function (Blueprint $table) use ($foreignKeys) {
            if (in_array('standings_team_id_foreign', $foreignKeys)) {
                $table->dropForeign(['team_id']);
            }
            if (in_array('standings_tournament_id_foreign', $foreignKeys)) {
                $table->dropForeign(['tournament_id']);
            }
        });

        $indexes = $this->existingIndexes();

        if (in_array('standings_tournament_team_group_unique', $indexes)) {
            Schema::table('standings', function (Blueprint $table) {
                $table->dropUnique('standings_tournament_team_group_unique');
            });
        }

        $indexes = $this->existingIndexes();

        if (!in_array('standings_team_id_group_name_unique', $indexes)) {
            Schema::table('standings', function (Blueprint $table) {
                $table->unique(['team_id', 'group_name'], 'standings_team_id_group_name_unique');
            });
        }

        if (Schema::hasColumn('standings', 'tournament_id')) {
            Schema::table('standings', function (Blueprint $table) {
                $table->dropColumn('tournament_id');
            });
        }
    }
}