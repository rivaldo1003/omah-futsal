<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('match_events', function (Blueprint $table) {
            $table->unsignedTinyInteger('extra_minute')->nullable()->after('minute');
        });

        // Extend available event types for goalkeeper events.
        DB::statement("
            ALTER TABLE match_events
            MODIFY event_type ENUM(
                'goal',
                'yellow_card',
                'red_card',
                'substitution',
                'penalty',
                'foul',
                'injury',
                'assist',
                'save',
                'clean_sheet'
            ) NOT NULL
        ");

        Schema::table('players', function (Blueprint $table) {
            $table->unsignedInteger('saves')->default(0)->after('red_cards');
            $table->unsignedInteger('clean_sheets')->default(0)->after('saves');
            $table->unsignedInteger('penalty_goals')->default(0)->after('clean_sheets');
            $table->unsignedInteger('penalty_missed')->default(0)->after('penalty_goals');
        });
    }

    public function down(): void
    {
        Schema::table('match_events', function (Blueprint $table) {
            $table->dropColumn('extra_minute');
        });

        DB::statement("
            ALTER TABLE match_events
            MODIFY event_type ENUM(
                'goal',
                'yellow_card',
                'red_card',
                'substitution',
                'penalty',
                'foul',
                'injury',
                'assist'
            ) NOT NULL
        ");

        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['saves', 'clean_sheets', 'penalty_goals', 'penalty_missed']);
        });
    }
};
