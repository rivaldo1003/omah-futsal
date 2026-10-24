<?php

namespace App\Services;

use App\Models\Court;
use App\Models\CourtBooking;
use App\Models\CourtMemberSchedule;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

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
        return $this->matchingGameQuery($court, $date, $start, $end)->exists();
    }

    public function matchForSlot(Court $court, string $date, string $start, string $end): ?Game
    {
        return $this->matchingGameQuery($court, $date, $start, $end)
            ->with(['tournament:id,name', 'homeTeam:id,name', 'awayTeam:id,name'])
            ->first();
    }

    private function matchingGameQuery(Court $court, string $date, string $start, string $end): Builder
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
            ->orderBy('match_date')
            ->orderBy('time_start')
            ->orderBy('id');
    }

    public function memberScheduleConflict(Court $court, string $date, string $start, string $end): bool
    {
        return $this->memberScheduleNameForSlot($court, $date, $start, $end) !== null;
    }

    public function memberScheduleNameForSlot(Court $court, string $date, string $start, string $end): ?string
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
                return trim((string) $schedule->member_name);
            }
        }

        return null;
    }

    public function slotStatus(Court $court, string $date, string $start, string $end): string
    {
        return $this->slotAvailability($court, $date, $start, $end, false)['status'];
    }

    /** @return array{status: string, display_name: ?string, match_details: ?array{competition_name: string, home_team: string, away_team: string}} */
    public function slotAvailability(Court $court, string $date, string $start, string $end, bool $includeMatchDetails = true): array
    {
        $slotStart = Carbon::parse($date.' '.$start, CourtBooking::TIMEZONE);
        $hasPassed = $slotStart->lessThanOrEqualTo(now(CourtBooking::TIMEZONE));

        $booking = $this->bookingConflict($court, $date, $start, $end);
        if ($booking) {
            return [
                'status' => in_array($booking->status, [
                    CourtBooking::STATUS_AWAITING_PAYMENT,
                    CourtBooking::STATUS_PENDING_REVIEW,
                ], true) ? 'pending' : 'booked',
                // Only the explicit team/member label is public. Never expose the
                // customer's personal name, phone number, or email here.
                'display_name' => filled($booking->team_name) ? trim($booking->team_name) : null,
                'match_details' => null,
            ];
        }

        $match = $includeMatchDetails ? $this->matchForSlot($court, $date, $start, $end) : null;
        $hasMatchConflict = $match !== null || (!$includeMatchDetails && $this->matchConflict($court, $date, $start, $end));
        if ($hasMatchConflict) {
            return [
                'status' => 'match',
                'display_name' => null,
                'match_details' => $match ? [
                    'competition_name' => $match->tournament?->name ?: 'Pertandingan persahabatan',
                    'home_team' => $match->homeTeam?->name ?: 'TBA',
                    'away_team' => $match->awayTeam?->name ?: 'TBA',
                ] : null,
            ];
        }

        $memberName = $this->memberScheduleNameForSlot($court, $date, $start, $end);
        if ($memberName !== null) {
            return ['status' => 'member', 'display_name' => $memberName, 'match_details' => null];
        }

        if ($hasPassed) {
            return ['status' => 'past_available', 'display_name' => null, 'match_details' => null];
        }

        return ['status' => 'available', 'display_name' => null, 'match_details' => null];
    }

    public function lockCourt(int $courtId): ?Court
    {
        return Court::query()->whereKey($courtId)->lockForUpdate()->first();
    }
}
