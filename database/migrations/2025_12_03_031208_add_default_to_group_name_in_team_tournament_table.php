<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('team_tournament', function (Blueprint $table) {
            // Ubah kolom group_name menjadi nullable atau beri default value
            $table->string('group_name')->nullable()->change();
            // Atau:
            // $table->string('group_name')->default('Group A')->change();
        });
    }

    public function down()
    {
        Schema::table('team_tournament', function (Blueprint $table) {
            $table->string('group_name')->nullable(false)->change();
            // Atau:
            // $table->string('group_name')->default('')->change();
        });
    }
};
