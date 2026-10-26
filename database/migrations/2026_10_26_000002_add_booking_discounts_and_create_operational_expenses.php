<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('court_bookings')) {
            $columns = [];
            if (!Schema::hasColumn('court_bookings', 'subtotal_amount')) {
                $columns[] = 'subtotal_amount';
            }
            if (!Schema::hasColumn('court_bookings', 'discount_type')) {
                $columns[] = 'discount_type';
            }
            if (!Schema::hasColumn('court_bookings', 'discount_value')) {
                $columns[] = 'discount_value';
            }
            if (!Schema::hasColumn('court_bookings', 'discount_amount')) {
                $columns[] = 'discount_amount';
            }
            if (!Schema::hasColumn('court_bookings', 'discount_reason')) {
                $columns[] = 'discount_reason';
            }

            if ($columns !== []) {
                Schema::table('court_bookings', function (Blueprint $table) use ($columns): void {
                    if (in_array('subtotal_amount', $columns, true)) {
                        $table->decimal('subtotal_amount', 12, 2)->nullable()->after('amount');
                    }
                    if (in_array('discount_type', $columns, true)) {
                        $table->string('discount_type', 20)->default('none')->after('subtotal_amount');
                    }
                    if (in_array('discount_value', $columns, true)) {
                        $table->decimal('discount_value', 12, 2)->default(0)->after('discount_type');
                    }
                    if (in_array('discount_amount', $columns, true)) {
                        $table->decimal('discount_amount', 12, 2)->default(0)->after('discount_value');
                    }
                    if (in_array('discount_reason', $columns, true)) {
                        $table->string('discount_reason', 255)->nullable()->after('discount_amount');
                    }
                });
            }

            if (Schema::hasColumn('court_bookings', 'subtotal_amount')) {
                DB::table('court_bookings')->whereNull('subtotal_amount')->update(['subtotal_amount' => DB::raw('amount')]);
            }
        }

        if (!Schema::hasTable('court_operational_expenses')) {
            Schema::create('court_operational_expenses', function (Blueprint $table): void {
                $table->id();
                $table->date('expense_date')->index();
                $table->string('category', 30)->index();
                $table->string('title', 150);
                $table->decimal('amount', 12, 2);
                $table->string('payment_method', 30)->default('unclassified');
                $table->text('note')->nullable();
                $table->unsignedBigInteger('recorded_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->unsignedBigInteger('deleted_by')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index(['expense_date', 'category', 'payment_method'], 'court_operational_expenses_report_idx');
            });
        }

        if (!Schema::hasTable('court_operational_expense_audits')) {
            Schema::create('court_operational_expense_audits', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('expense_id')->index();
                $table->unsignedBigInteger('actor_id')->nullable()->index();
                $table->string('actor_name_snapshot', 120)->nullable();
                $table->string('action', 20);
                $table->string('reason', 500)->nullable();
                $table->json('before_data')->nullable();
                $table->json('after_data')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('expense_id', 'court_operational_expense_audits_expense_fk')
                    ->references('id')->on('court_operational_expenses')->cascadeOnDelete();
                $table->foreign('actor_id', 'court_operational_expense_audits_actor_fk')
                    ->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('court_operational_expense_audits');
        Schema::dropIfExists('court_operational_expenses');

        if (Schema::hasTable('court_bookings')) {
            $columns = array_values(array_filter(
                ['subtotal_amount', 'discount_type', 'discount_value', 'discount_amount', 'discount_reason'],
                fn (string $column): bool => Schema::hasColumn('court_bookings', $column),
            ));
            if ($columns !== []) {
                Schema::table('court_bookings', fn (Blueprint $table) => $table->dropColumn($columns));
            }
        }
    }
};
