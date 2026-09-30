<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom untuk memilih turnamen unggulan yang tampil di Home,
     * sekaligus mendukung banyak turnamen berjalan (ongoing/upcoming).
     */
    public function up(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            if (!Schema::hasColumn('tournaments', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
            if (!Schema::hasColumn('tournaments', 'home_order')) {
                $table->integer('home_order')->default(0)->after('is_featured');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            if (Schema::hasColumn('tournaments', 'home_order')) {
                $table->dropColumn('home_order');
            }
            if (Schema::hasColumn('tournaments', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });
    }
};