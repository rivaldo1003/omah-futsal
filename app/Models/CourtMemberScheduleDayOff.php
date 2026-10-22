<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtMemberScheduleDayOff extends Model
{
    protected $table = 'court_member_schedule_days_off';

    protected $fillable = ['court_member_schedule_id', 'off_date', 'note'];

    protected $casts = ['off_date' => 'date:Y-m-d'];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(CourtMemberSchedule::class, 'court_member_schedule_id');
    }
}
