<?php

namespace App\Services;

use App\Models\Court;
use App\Models\CourtBooking;
use Carbon\CarbonImmutable;

class CourtPricingService
{
    public function amountForRange(Court $court, string $startTime, int $durationMinutes, ?string $bookingDate = null): float
    {
        $timezone = CourtBooking::TIMEZONE;
        $date = $bookingDate ?? CarbonImmutable::now($timezone)->toDateString();
        $cursor = CarbonImmutable::parse($date.' '.$startTime, $timezone);
        $end = $cursor->addMinutes($durationMinutes);
        $nightStartsAt = $court->night_rate_starts_at_formatted;
        $nightEndsAt = $court->night_rate_ends_at_formatted;
        $amount = 0.0;

        while ($cursor->lessThan($end)) {
            $segmentEnd = $end;
            $dayBoundary = $cursor->startOfDay()->addDay();
            if ($dayBoundary->lessThan($segmentEnd)) {
                $segmentEnd = $dayBoundary;
            }

            foreach ([$nightStartsAt, $nightEndsAt] as $boundaryTime) {
                $boundary = CarbonImmutable::parse($cursor->toDateString().' '.$boundaryTime, $timezone);
                if ($boundary->lessThanOrEqualTo($cursor)) {
                    continue;
                }
                if ($boundary->lessThan($segmentEnd)) {
                    $segmentEnd = $boundary;
                }
            }

            $segmentMinutes = (int) (($segmentEnd->getTimestamp() - $cursor->getTimestamp()) / 60);
            if ($segmentMinutes < 1) {
                $segmentMinutes = 1;
                $segmentEnd = $cursor->addMinute();
            }

            $isWeekend = $cursor->isSaturday() || $cursor->isSunday();
            $time = $cursor->format('H:i');
            $isNight = $nightStartsAt < $nightEndsAt
                ? $time >= $nightStartsAt && $time < $nightEndsAt
                : $time >= $nightStartsAt || $time < $nightEndsAt;
            $hourlyRate = match (true) {
                $isWeekend && $isNight => (float) $court->weekend_night_hourly_rate,
                $isWeekend => (float) $court->weekend_day_hourly_rate,
                $isNight => (float) $court->night_hourly_rate,
                default => (float) $court->day_hourly_rate,
            };
            $amount += $hourlyRate * $segmentMinutes / 60;
            $cursor = $segmentEnd;
        }

        return round($amount, 2);
    }
}
