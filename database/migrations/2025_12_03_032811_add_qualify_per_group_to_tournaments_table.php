<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_qualify_per_group_to_tournaments_table.php
    public function up()
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->integer('qualify_per_group')->nullable()->after('teams_per_group');
        });
    }

    public function down()
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('qualify_per_group');
        });
    }
};
