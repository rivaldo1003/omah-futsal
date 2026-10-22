<?php

namespace App\Services;

use App\Models\Court;
use App\Models\CourtBooking;
use App\Models\CourtMemberSchedule;
use App\Models\Game;
use Carbon\Carbon;

class CourtBookingAvailabilityService
{
    private array $memberScheduleCache = [];

    public function expireUnpaidBookings(?int $courtId = null, ?string $date = null): int
    {
        return CourtBooking::query()
            ->where('status', CourtBooking::STATUS_AWAITING_PAYMENT)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->when($courtId, fn ($query) => $query->where('court_id', $courtId))
            ->when($date, fn ($query) => $query->whereDate('booking_date', $date))
            ->update([
                'status' => CourtBooking::STATUS_EXPIRED,
                'updated_at' => now(),
            ]);
    }

    public function bookingConflict(
        Court $court,
        string $date,
        string $start,
        string $end,
        ?int $ignoreBookingId = null,
    ): ?CourtBooking {
        $startAt = Carbon::parse($date.' '.$start, CourtBooking::TIMEZONE);
        $endAt = Carbon::parse($date.' '.$end, CourtBooking::TIMEZONE);
        if ($endAt->lessThanOrEqualTo($startAt)) {
            $endAt->addDay();
        }

        return CourtBooking::query()
            ->where('court_id', $court->id)
            ->whereDate('booking_date', '>=', $startAt->copy()->subDay()->toDateString())
            ->whereDate('booking_date', '<=', $endAt->toDateString())
            ->whereRaw('TIMESTAMP(booking_date, time_start) < ?', [$endAt->format('Y-m-d H:i:s')])
            ->whereRaw("(CASE WHEN time_end <= time_start THEN DATE_ADD(TIMESTAMP(booking_date, time_end), INTERVAL 1 DAY) ELSE TIMESTAMP(booking_date, time_end) END) > ?", [$startAt->format('Y-m-d H:i:s')])
            ->when($ignoreBookingId, fn ($query, $id) => $query->whereKeyNot($id))
            ->where(function ($query) {
                $query->whereIn('status', [
                    CourtBooking::STATUS_PENDING_REVIEW,
                    CourtBooking::STATUS_CONFIRMED,
                ])->orWhere(function ($query) {
                    $query->where('status', CourtBooking::STATUS_AWAITING_PAYMENT)
                        ->where('expires_at', '>', now());
                });
            })
            ->first();
    }

    public function matchConflict(Court $court, string $date, string $start, string $end): bool
    {
        $startAt = Carbon::parse($date.' '.$start, CourtBooking::TIMEZONE);
        $endAt = Carbon::parse($date.' '.$end, CourtBooking::TIMEZONE);
        if ($endAt->lessThanOrEqualTo($startAt)) {
            $endAt->addDay();
        }

        return Game::query()
            ->publiclyVisible()
            ->whereDate('match_date', '>=', $startAt->copy()->subDay()->toDateString())
            ->whereDate('match_date', '<=', $endAt->toDateString())
            ->whereRaw('LOWER(TRIM(venue)) = ?', [mb_strtolower(trim($court->name))])
            ->where('status', '!=', 'postponed')
            ->whereRaw('TIMESTAMP(match_date, time_start) < ?', [$endAt->format('Y-m-d H:i:s')])
            ->whereRaw("(CASE WHEN time_end <= time_start THEN DATE_ADD(TIMESTAMP(match_date, time_end), INTERVAL 1 DAY) ELSE TIMESTAMP(match_date, time_end) END) > ?", [$startAt->format('Y-m-d H:i:s')])
            ->exists();
    }

    public function memberScheduleConflict(Court $court, string $date, string $start, string $end): bool
    {
        $startAt = Carbon::parse($date.' '.$start, CourtBooking::TIMEZONE);
        $endAt = Carbon::parse($date.' '.$end, CourtBooking::TIMEZONE);
        if ($endAt->lessThanOrEqualTo($startAt)) {
            $endAt->addDay();
        }

        $dates = [$startAt->toDateString(), $startAt->copy()->subDay()->toDateString()];
        $cacheKey = $court->id.'|'.$dates[0];
        if (!array_key_exists($cacheKey, $this->memberScheduleCache)) {
            $this->memberScheduleCache[$cacheKey] = CourtMemberSchedule::query()
                ->with('daysOff')
                ->where('court_id', $court->id)
                ->where('is_active', true)
                ->whereIn('day_of_week', [
                    Carbon::parse($dates[0], CourtBooking::TIMEZONE)->dayOfWeek,
                    Carbon::parse($dates[1], CourtBooking::TIMEZONE)->dayOfWeek,
                ])
                ->where(fn ($query) => $query->whereNull('starts_on')->orWhereDate('starts_on', '<=', $dates[0]))
                ->where(fn ($query) => $query->whereNull('ends_on')->orWhereDate('ends_on', '>=', $dates[1]))
                ->get();
        }
        $schedules = $this->memberScheduleCache[$cacheKey];

        foreach ($schedules as $schedule) {
            $baseDate = (int) $schedule->day_of_week === Carbon::parse($dates[0], CourtBooking::TIMEZONE)->dayOfWeek
                ? $dates[0]
                : $dates[1];
            if (($schedule->starts_on && $schedule->starts_on->toDateString() > $baseDate)
                || ($schedule->ends_on && $schedule->ends_on->toDateString() < $baseDate)) {
                continue;
            }
            if ($schedule->daysOff->contains(fn ($dayOff) => $dayOff->off_date->toDateString() === $baseDate)) {
                continue;
            }

            $memberStart = Carbon::parse($baseDate.' '.$schedule->time_start, CourtBooking::TIMEZONE);
            $memberEnd = Carbon::parse($baseDate.' '.$schedule->time_end, CourtBooking::TIMEZONE);
            if ($memberEnd->lessThanOrEqualTo($memberStart)) {
                $memberEnd->addDay();
            }

            if ($memberStart->lessThan($endAt) && $memberEnd->greaterThan($startAt)) {
                return true;
            }
        }

        return false;
    }

    public function slotStatus(Court $court, string $date, string $start, string $end): string
    {
        $slotStart = Carbon::parse($date.' '.$start, CourtBooking::TIMEZONE);
        if ($slotStart->lessThanOrEqualTo(now(CourtBooking::TIMEZONE))) {
            return 'past';
        }

        $booking = $this->bookingConflict($court, $date, $start, $end);
        if ($booking) {
            return in_array($booking->status, [
                CourtBooking::STATUS_AWAITING_PAYMENT,
                CourtBooking::STATUS_PENDING_REVIEW,
            ], true) ? 'pending' : 'booked';
        }

        if ($this->matchConflict($court, $date, $start, $end)) {
            return 'booked';
        }

        if ($this->memberScheduleConflict($court, $date, $start, $end)) {
            return 'member';
        }

        return 'available';
    }

    public function lockCourt(int $courtId): ?Court
    {
        return Court::query()->whereKey($courtId)->lockForUpdate()->first();
    }
}
