<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->string('highlight_video')->nullable()->after('status');
            $table->string('highlight_thumbnail')->nullable()->after('highlight_video');
            $table->integer('highlight_video_size')->nullable()->after('highlight_thumbnail')->comment('Size in bytes');
            $table->string('highlight_video_duration')->nullable()->after('highlight_video_size')->comment('Duration in HH:MM:SS format');
            $table->timestamp('highlight_uploaded_at')->nullable()->after('highlight_video_duration');
            $table->string('highlight_original_name')->nullable()->after('highlight_uploaded_at');
        });
    }

    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn([
                'highlight_video',
                'highlight_thumbnail',
                'highlight_video_size',
                'highlight_video_duration',
                'highlight_uploaded_at',
                'highlight_original_name'
            ]);
        });
    }
};