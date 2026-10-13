<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->boolean('is_cancelled')->default(false)->after('status');
        });

        $thirdPlaceMatches = DB::table('matches')
            ->join('tournaments', 'tournaments.id', '=', 'matches.tournament_id')
            ->where('matches.round_type', 'third_place')
            ->whereIn('matches.status', ['upcoming', 'postponed'])
            ->select('matches.id', 'tournaments.settings')
            ->get();

        foreach ($thirdPlaceMatches as $match) {
            $settings = json_decode($match->settings ?? '', true);
            if (is_string($settings)) {
                $settings = json_decode($settings, true);
            }

            if (!filter_var($settings['knockout_third_place'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                DB::table('matches')->where('id', $match->id)->update(['is_cancelled' => true]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('is_cancelled');
        });
    }
};
