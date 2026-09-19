<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('match_events', function (Blueprint $table) {
            if (! Schema::hasColumn('match_events', 'extra_minute')) {
                $table->unsignedTinyInteger('extra_minute')->nullable()->after('minute');
            }
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
            if (! Schema::hasColumn('players', 'saves')) {
                $table->unsignedInteger('saves')->default(0)->after('red_cards');
            }
            if (! Schema::hasColumn('players', 'clean_sheets')) {
                $table->unsignedInteger('clean_sheets')->default(0)->after('saves');
            }
            if (! Schema::hasColumn('players', 'penalty_goals')) {
                $table->unsignedInteger('penalty_goals')->default(0)->after('clean_sheets');
            }
            if (! Schema::hasColumn('players', 'penalty_missed')) {
                $table->unsignedInteger('penalty_missed')->default(0)->after('penalty_goals');
            }
        });
    }

    public function down(): void
    {
        Schema::table('match_events', function (Blueprint $table) {
            if (Schema::hasColumn('match_events', 'extra_minute')) {
                $table->dropColumn('extra_minute');
            }
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
            $droppable = array_filter(
                ['saves', 'clean_sheets', 'penalty_goals', 'penalty_missed'],
                fn ($c) => Schema::hasColumn('players', $c)
            );
            if (! empty($droppable)) {
                $table->dropColumn($droppable);
            }
        });
    }
};
