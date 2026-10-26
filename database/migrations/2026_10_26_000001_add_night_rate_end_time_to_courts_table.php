<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('courts', 'night_rate_ends_at')) {
            Schema::table('courts', function (Blueprint $table): void {
                $table->time('night_rate_ends_at')->default('04:00:00')->after('night_rate_starts_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('courts', 'night_rate_ends_at')) {
            Schema::table('courts', function (Blueprint $table): void {
                $table->dropColumn('night_rate_ends_at');
            });
        }
    }
};
