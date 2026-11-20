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
            $table->date('match_date')->nullable()->change();
            $table->time('time_start')->nullable()->change();
            $table->time('time_end')->nullable()->change();
        });
    }

    public function down(): void
    {
        $hasUnscheduledMatches = DB::table('matches')
            ->whereNull('match_date')
            ->orWhereNull('time_start')
            ->orWhereNull('time_end')
            ->exists();

        if ($hasUnscheduledMatches) {
            throw new RuntimeException(
                'Cannot make match schedule fields required while unscheduled fixtures still exist. Schedule or remove them first.'
            );
        }

        Schema::table('matches', function (Blueprint $table) {
            $table->date('match_date')->nullable(false)->change();
            $table->time('time_start')->nullable(false)->change();
            $table->time('time_end')->nullable(false)->change();
        });
    }
};
