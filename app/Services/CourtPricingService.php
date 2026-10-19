<?php

namespace App\Services;

use App\Models\Court;

class CourtPricingService
{
    public function amountForRange(Court $court, string $startTime, int $durationMinutes): float
    {
        $slotMinutes = max(15, (int) $court->slot_duration_minutes);
        $amount = 0.0;

        for ($elapsedMinutes = 0; $elapsedMinutes < $durationMinutes; $elapsedMinutes += $slotMinutes) {
            $amount += $this->amountForSegment(
                $court,
                $startTime,
                $elapsedMinutes,
                min($slotMinutes, $durationMinutes - $elapsedMinutes),
            );
        }

        return round($amount, 2);
    }

    private function amountForSegment(Court $court, string $startTime, int $offsetMinutes, int $durationMinutes): float
    {
        $startMinute = $this->minutesSinceMidnight($startTime) + $offsetMinutes;
        $endMinute = $startMinute + $durationMinutes;
        $nightStartMinute = $this->minutesSinceMidnight($court->night_rate_starts_at_formatted);
        $dayMinutes = max(0, min($endMinute, $nightStartMinute) - $startMinute);
        $nightMinutes = max(0, $durationMinutes - $dayMinutes);

        return round((
            ((float) $court->day_hourly_rate * $dayMinutes)
            + ((float) $court->night_hourly_rate * $nightMinutes)
        ) / 60, 2);
    }

    private function minutesSinceMidnight(string $time): int
    {
        [$hours, $minutes] = array_map('intval', explode(':', substr($time, 0, 5)));

        return ($hours * 60) + $minutes;
    }
}
