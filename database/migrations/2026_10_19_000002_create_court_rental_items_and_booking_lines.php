<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('court_rental_items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->unsignedSmallInteger('stock_quantity')->default(0);
            $table->boolean('is_active')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('court_booking_rental_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_booking_id')->constrained('court_bookings')->cascadeOnDelete();
            $table->foreignId('court_rental_item_id')->nullable()->constrained('court_rental_items')->nullOnDelete();
            $table->string('item_name_snapshot', 100);
            $table->unsignedSmallInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('line_total', 12, 2);
            $table->timestamps();
            $table->unique(['court_booking_id', 'court_rental_item_id'], 'court_booking_rental_item_unique');
            $table->index('court_rental_item_id');
        });

        $now = now();
        DB::table('court_rental_items')->insertOrIgnore([
            ['name' => 'Rompi', 'unit_price' => 0, 'stock_quantity' => 0, 'is_active' => false, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sepatu', 'unit_price' => 0, 'stock_quantity' => 0, 'is_active' => false, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Jersey', 'unit_price' => 0, 'stock_quantity' => 0, 'is_active' => false, 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('court_booking_rental_lines');
        Schema::dropIfExists('court_rental_items');
    }
};
