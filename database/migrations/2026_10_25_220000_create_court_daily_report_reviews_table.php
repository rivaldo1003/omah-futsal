<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('court_daily_report_reviews')) {
            Schema::create('court_daily_report_reviews', function (Blueprint $table): void {
                $table->id();
                $table->date('operating_date')->unique('court_daily_report_reviews_date_unique');
                $table->char('snapshot_hash', 64);
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('reviewed_by_name_snapshot', 120)->nullable();
                $table->timestamp('reviewed_at');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('court_daily_report_reviews');
    }
};
