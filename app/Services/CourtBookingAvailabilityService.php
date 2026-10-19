<?php

namespace App\Services;

use App\Models\Court;
use App\Models\CourtBooking;
use App\Models\Game;
use Carbon\Carbon;

class CourtBookingAvailabilityService
{
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
        return CourtBooking::query()
            ->where('court_id', $court->id)
            ->where('booking_date', $date)
            ->where('time_start', '<', $end)
            ->where('time_end', '>', $start)
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
        return Game::query()
            ->publiclyVisible()
            ->whereDate('match_date', $date)
            ->whereRaw('LOWER(TRIM(venue)) = ?', [mb_strtolower(trim($court->name))])
            ->where('status', '!=', 'postponed')
            ->where('time_start', '<', $end)
            ->where('time_end', '>', $start)
            ->exists();
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

        return 'available';
    }

    public function lockCourt(int $courtId): ?Court
    {
        return Court::query()->whereKey($courtId)->lockForUpdate()->first();
    }
}
