<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            // Tambah field yang missing
            if (!Schema::hasColumn('tournaments', 'qualify_per_group')) {
                $table->integer('qualify_per_group')->nullable()->default(2)->after('teams_per_group');
            }
            
            if (!Schema::hasColumn('tournaments', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('settings');
            }
            
            // Update tipe data jika perlu
            if (Schema::hasColumn('tournaments', 'teams_per_group')) {
                $table->integer('teams_per_group')->nullable()->default(4)->change();
            }
            
            if (Schema::hasColumn('tournaments', 'groups_count')) {
                $table->integer('groups_count')->nullable()->default(2)->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            if (Schema::hasColumn('tournaments', 'qualify_per_group')) {
                $table->dropColumn('qualify_per_group');
            }
            
            if (Schema::hasColumn('tournaments', 'created_by')) {
                $table->dropColumn('created_by');
            }
        });
    }
};