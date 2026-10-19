<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('court_bookings', function (Blueprint $table) {
            $table->string('source', 20)->default('public')->after('payment_method');
            $table->foreignId('created_by')->nullable()->after('source')->constrained('users')->nullOnDelete();
        });

        Schema::table('court_rental_orders', function (Blueprint $table) {
            $table->string('source', 20)->default('public')->after('payment_method');
            $table->foreignId('created_by')->nullable()->after('source')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('court_rental_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn('source');
        });

        Schema::table('court_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn('source');
        });
    }
};
