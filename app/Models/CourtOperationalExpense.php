<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourtOperationalExpense extends Model
{
    use SoftDeletes;

    public const CATEGORY_SALARY = 'salary';
    public const CATEGORY_ELECTRICITY = 'electricity';
    public const CATEGORY_OTHER = 'other';

    protected $fillable = [
        'expense_date', 'category', 'title', 'amount', 'payment_method', 'note',
        'recorded_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'expense_date' => 'date:Y-m-d',
        'amount' => 'decimal:2',
    ];

    public function audits(): HasMany
    {
        return $this->hasMany(CourtOperationalExpenseAudit::class, 'expense_id')->orderBy('created_at')->orderBy('id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            self::CATEGORY_SALARY => 'Gaji',
            self::CATEGORY_ELECTRICITY => 'Listrik',
            default => 'Lain-lain',
        };
    }
}
