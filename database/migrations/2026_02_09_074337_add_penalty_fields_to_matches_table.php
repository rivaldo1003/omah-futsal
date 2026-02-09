<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            // Tambah field is_penalty (boolean)
            $table->boolean('is_penalty')
                ->default(false)
                ->after('away_score')
                ->comment('Apakah match selesai dengan adu penalti?');

            // Tambah field penalty_score (string untuk format "3-2")
            $table->string('penalty_score', 10)
                ->nullable()
                ->after('is_penalty')
                ->comment('Skor adu penalti (format: home-away, contoh: 3-2)');

            // Opsional: field untuk extra time score jika perlu
            $table->string('et_score', 10)
                ->nullable()
                ->after('penalty_score')
                ->comment('Skor setelah extra time (format: home-away)');
        });
    }

    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['is_penalty', 'penalty_score', 'et_score']);
        });
    }
};