<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courts', function (Blueprint $table) {
            $table->decimal('day_hourly_rate', 12, 2)->nullable()->after('hourly_rate');
            $table->decimal('night_hourly_rate', 12, 2)->nullable()->after('day_hourly_rate');
            $table->time('night_rate_starts_at')->default('18:00:00')->after('night_hourly_rate');
        });

        DB::table('courts')->update([
            'day_hourly_rate' => DB::raw('hourly_rate'),
            'night_hourly_rate' => DB::raw('hourly_rate'),
        ]);
    }

    public function down(): void
    {
        Schema::table('courts', function (Blueprint $table) {
            $table->dropColumn(['day_hourly_rate', 'night_hourly_rate', 'night_rate_starts_at']);
        });
    }
};
