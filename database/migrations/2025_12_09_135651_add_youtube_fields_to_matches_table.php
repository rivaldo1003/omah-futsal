<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            // Tambah field untuk YouTube
            $table->string('youtube_id')->nullable()->after('notes');
            $table->string('youtube_thumbnail')->nullable()->after('youtube_id');
            $table->integer('youtube_duration')->nullable()->after('youtube_thumbnail');
            $table->timestamp('youtube_uploaded_at')->nullable()->after('youtube_duration');
            
            // Hapus atau comment field lama yang tidak bisa digunakan
            // $table->dropColumn(['highlight_video', 'highlight_thumbnail', 'highlight_video_size', 'highlight_video_duration', 'highlight_uploaded_at', 'highlight_original_name']);
        });
    }

    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['youtube_id', 'youtube_thumbnail', 'youtube_duration', 'youtube_uploaded_at']);
        });
    }
};