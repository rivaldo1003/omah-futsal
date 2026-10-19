<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // These rows were written as Jakarta wall time while Eloquent treats
        // TIMESTAMP values as UTC. Normalize the legacy rows once for display.
        DB::table('court_daily_revenues')
            ->whereNotNull('updated_by')
            ->update([
                'created_at' => DB::raw('DATE_SUB(`created_at`, INTERVAL 7 HOUR)'),
                'updated_at' => DB::raw('DATE_SUB(`updated_at`, INTERVAL 7 HOUR)'),
            ]);
    }

    public function down(): void
    {
        // Reversing this data correction could corrupt timestamps saved later.
    }
};
