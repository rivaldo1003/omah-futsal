<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('court_rental_orders', function (Blueprint $table) {
            $table->string('rental_mode', 20)->default('scheduled')->after('rental_date');
            $table->unsignedSmallInteger('duration_minutes')->nullable()->after('time_end');
            $table->timestamp('rental_picked_up_at')->nullable()->after('confirmed_at');
            $table->timestamp('rental_returned_at')->nullable()->after('rental_picked_up_at');
        });

        Schema::table('court_bookings', function (Blueprint $table) {
            $table->timestamp('rental_picked_up_at')->nullable()->after('confirmed_at');
            $table->timestamp('rental_returned_at')->nullable()->after('rental_picked_up_at');
        });
    }

    public function down(): void
    {
        Schema::table('court_bookings', function (Blueprint $table) {
            $table->dropColumn(['rental_picked_up_at', 'rental_returned_at']);
        });

        Schema::table('court_rental_orders', function (Blueprint $table) {
            $table->dropColumn(['rental_mode', 'duration_minutes', 'rental_picked_up_at', 'rental_returned_at']);
        });
    }
};
