<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixStandingsUniqueConstraintSafely extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add tournament_id column if it doesn't exist
        Schema::table('standings', function (Blueprint $table) {
            if (!Schema::hasColumn('standings', 'tournament_id')) {
                $table->foreignId('tournament_id')->nullable()->after('team_id');
            }
        });

        // Step 2: Drop index lama
        Schema::table('standings', function (Blueprint $table) {
            $table->dropUnique(['team_id', 'group_name']);
        });

        // Step 3: Add index baru dengan tournament_id
        Schema::table('standings', function (Blueprint $table) {
            $table->unique(['tournament_id', 'team_id', 'group_name'], 'standings_tournament_team_group_unique');
        });

        // Step 4: Add foreign key constraints
        Schema::table('standings', function (Blueprint $table) {
            // Add foreign key to teams
            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
                ->onDelete('cascade');

            // Add foreign key to tournaments
            $table->foreign('tournament_id')
                ->references('id')
                ->on('tournaments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Drop foreign keys
        Schema::table('standings', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['tournament_id']);
        });

        // Step 2: Drop new index
        Schema::table('standings', function (Blueprint $table) {
            $table->dropUnique(['tournament_id', 'team_id', 'group_name']);
        });

        // Step 3: Add old index
        Schema::table('standings', function (Blueprint $table) {
            $table->unique(['team_id', 'group_name'], 'standings_team_id_group_name_unique');
        });

        // Step 4: Drop tournament_id column
        Schema::table('standings', function (Blueprint $table) {
            $table->dropColumn('tournament_id');
        });
    }
}