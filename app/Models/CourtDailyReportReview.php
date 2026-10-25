<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtDailyReportReview extends Model
{
    protected $fillable = [
        'operating_date',
        'snapshot_hash',
        'reviewed_by',
        'reviewed_by_name_snapshot',
        'reviewed_at',
    ];

    protected $casts = [
        'operating_date' => 'date:Y-m-d',
        'reviewed_at' => 'datetime',
    ];

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
