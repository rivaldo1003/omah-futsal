<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Court extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hourly_rate',
        'day_hourly_rate',
        'night_hourly_rate',
        'weekend_day_hourly_rate',
        'weekend_night_hourly_rate',
        'night_rate_starts_at',
        'night_rate_ends_at',
        'opening_time',
        'closing_time',
        'slot_duration_minutes',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'day_hourly_rate' => 'decimal:2',
        'night_hourly_rate' => 'decimal:2',
        'weekend_day_hourly_rate' => 'decimal:2',
        'weekend_night_hourly_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getDayHourlyRateAttribute($value): string
    {
        return number_format((float) ($value ?? $this->attributes['hourly_rate'] ?? 0), 2, '.', '');
    }

    public function getNightHourlyRateAttribute($value): string
    {
        return number_format((float) ($value ?? $this->attributes['hourly_rate'] ?? 0), 2, '.', '');
    }

    public function getWeekendDayHourlyRateAttribute($value): string
    {
        return number_format((float) ($value ?? $this->day_hourly_rate), 2, '.', '');
    }

    public function getWeekendNightHourlyRateAttribute($value): string
    {
        return number_format((float) ($value ?? $this->night_hourly_rate), 2, '.', '');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(CourtBooking::class);
    }

    public function memberSchedules(): HasMany
    {
        return $this->hasMany(CourtMemberSchedule::class);
    }

    public function getOpeningTimeFormattedAttribute(): string
    {
        return substr((string) $this->opening_time, 0, 5);
    }

    public function getClosingTimeFormattedAttribute(): string
    {
        return substr((string) $this->closing_time, 0, 5);
    }

    public function getNightRateStartsAtFormattedAttribute(): string
    {
        return substr((string) ($this->night_rate_starts_at ?? '18:00:00'), 0, 5);
    }

    public function getNightRateEndsAtFormattedAttribute(): string
    {
        return substr((string) ($this->night_rate_ends_at ?? '04:00:00'), 0, 5);
    }
}
