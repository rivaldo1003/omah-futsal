<?php

namespace App\Services;

use App\Models\Court;
use App\Models\CourtBooking;
use App\Models\CourtTournamentRental;
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

    public function matchConflict(Court $court, string $date, string $start, string $end, ?int $ignoreTournamentId = null): bool
    {
        return $this->matchingGameQuery($court, $date, $start, $end, $ignoreTournamentId)->exists()
            || $this->tournamentRentalConflict($court, $date, $start, $end, null, $ignoreTournamentId);
    }

    public function scheduleCorrectionConflict(
        CourtBooking $booking,
        Court $court,
        string $date,
        string $start,
        string $end,
    ): ?string {
        $bookingStart = Carbon::parse($date.' '.$start, CourtBooking::TIMEZONE);
        $bookingEnd = Carbon::parse($date.' '.$end, CourtBooking::TIMEZONE);
        if ($bookingEnd->lessThanOrEqualTo($bookingStart)) {
            $bookingEnd->addDay();
        }

        $linkedTournamentIds = $booking->tournaments()
            ->pluck('tournaments.id')
            ->map(fn ($id) => (int) $id)
            ->all();
        if ($booking->tournament_id !== null) {
            $linkedTournamentIds[] = (int) $booking->tournament_id;
        }
        $linkedTournamentIds = array_values(array_unique($linkedTournamentIds));

        $games = $this->matchingGameQuery($court, $date, $start, $end)
            ->with('tournament:id,name')
            ->get(['id', 'tournament_id', 'match_date', 'time_start', 'time_end']);

        foreach ($games as $game) {
            $matchStart = Carbon::parse(
                $game->match_date->toDateString().' '.substr((string) $game->time_start, 0, 8),
                CourtBooking::TIMEZONE,
            );
            $matchEnd = Carbon::parse(
                $game->match_date->toDateString().' '.substr((string) $game->time_end, 0, 8),
                CourtBooking::TIMEZONE,
            );
            if ($matchEnd->lessThanOrEqualTo($matchStart)) {
                $matchEnd->addDay();
            }

            $sameTournament = $game->tournament_id !== null
                && in_array((int) $game->tournament_id, $linkedTournamentIds, true);
            $bookingCoversMatch = $bookingStart->lessThanOrEqualTo($matchStart)
                && $bookingEnd->greaterThanOrEqualTo($matchEnd);

            if ($sameTournament && $bookingCoversMatch) {
                continue;
            }

            $tournamentName = $game->tournament?->name;
            $matchLabel = $tournamentName ? ' pertandingan '.$tournamentName : ' pertandingan';
            $matchTime = $matchStart->format('d/m/Y').' pukul '.$matchStart->format('H:i').'-'.$matchEnd->format('H:i');

            if ($sameTournament) {
                return 'Rentang booking koreksi harus mencakup seluruh pertandingan'.$matchLabel.' pada '.$matchTime.'.';
            }

            return 'Jadwal berbenturan dengan'.$matchLabel.' pada '.$matchTime.'. Jika booking ini memang untuk turnamen tersebut, kaitkan booking ke turnamennya melalui menu Kaitkan reservasi turnamen terlebih dahulu.';
        }

        $rentals = $this->tournamentRentalQuery($court, $date, $start, $end)
            ->with('tournament:id,name')
            ->get(['id', 'tournament_id', 'tournament_name_snapshot', 'rental_date', 'time_start', 'time_end']);

        foreach ($rentals as $rental) {
            $rentalStart = Carbon::parse(
                $rental->rental_date->toDateString().' '.substr((string) $rental->time_start, 0, 8),
                CourtBooking::TIMEZONE,
            );
            $rentalEnd = Carbon::parse(
                $rental->rental_date->toDateString().' '.substr((string) $rental->time_end, 0, 8),
                CourtBooking::TIMEZONE,
            );
            if ($rentalEnd->lessThanOrEqualTo($rentalStart)) {
                $rentalEnd->addDay();
            }

            $sameTournament = $rental->tournament_id !== null
                && in_array((int) $rental->tournament_id, $linkedTournamentIds, true);
            $bookingCoversRental = $bookingStart->lessThanOrEqualTo($rentalStart)
                && $bookingEnd->greaterThanOrEqualTo($rentalEnd);

            if ($sameTournament && $bookingCoversRental) {
                continue;
            }

            $rentalName = $rental->tournament?->name ?: $rental->tournament_name_snapshot;
            $rentalLabel = filled($rentalName) ? ' sesi sewa turnamen '.$rentalName : ' sesi sewa turnamen';
            $rentalTime = $rentalStart->format('d/m/Y').' pukul '.$rentalStart->format('H:i').'-'.$rentalEnd->format('H:i');

            if ($sameTournament) {
                return 'Rentang booking koreksi harus mencakup seluruh'.$rentalLabel.' pada '.$rentalTime.'.';
            }

            return 'Jadwal berbenturan dengan'.$rentalLabel.' pada '.$rentalTime.'.';
        }

        return null;
    }

    public function matchForSlot(Court $court, string $date, string $start, string $end): ?Game
    {
        return $this->matchingGameQuery($court, $date, $start, $end)
            ->with(['tournament:id,name', 'homeTeam:id,name', 'awayTeam:id,name'])
            ->first();
    }

    public function tournamentRentalForSlot(Court $court, string $date, string $start, string $end): ?CourtTournamentRental
    {
        return $this->tournamentRentalQuery($court, $date, $start, $end)
            ->with('tournament:id,name')
            ->orderBy('time_start')
            ->orderBy('id')
            ->first();
    }

    public function tournamentRentalConflict(
        Court $court,
        string $date,
        string $start,
        string $end,
        ?int $ignoreRentalId = null,
        ?int $ignoreTournamentId = null,
    ): bool {
        return $this->tournamentRentalQuery($court, $date, $start, $end)
            ->when($ignoreRentalId, fn ($query, $id) => $query->whereKeyNot($id))
            ->when($ignoreTournamentId, fn ($query, $id) => $query->where(function ($linked) use ($id) {
                $linked->whereNull('tournament_id')->orWhere('tournament_id', '!=', $id);
            }))
            ->exists();
    }

    private function tournamentRentalQuery(Court $court, string $date, string $start, string $end)
    {
        $startAt = Carbon::parse($date.' '.$start, CourtBooking::TIMEZONE);
        $endAt = Carbon::parse($date.' '.$end, CourtBooking::TIMEZONE);
        if ($endAt->lessThanOrEqualTo($startAt)) {
            $endAt->addDay();
        }

        return CourtTournamentRental::query()
            ->where('court_id', $court->id)
            ->where('status', '!=', CourtTournamentRental::STATUS_CANCELLED)
            ->whereDate('rental_date', '>=', $startAt->copy()->subDay()->toDateString())
            ->whereDate('rental_date', '<=', $endAt->toDateString())
            ->whereRaw('TIMESTAMP(rental_date, time_start) < ?', [$endAt->format('Y-m-d H:i:s')])
            ->whereRaw("(CASE WHEN time_end <= time_start THEN DATE_ADD(TIMESTAMP(rental_date, time_end), INTERVAL 1 DAY) ELSE TIMESTAMP(rental_date, time_end) END) > ?", [$startAt->format('Y-m-d H:i:s')]);
    }

    private function matchingGameQuery(Court $court, string $date, string $start, string $end, ?int $ignoreTournamentId = null): Builder
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
            ->when($ignoreTournamentId, fn ($query, $id) => $query->where(function ($linked) use ($id) {
                $linked->whereNull('tournament_id')->orWhere('tournament_id', '!=', $id);
            }))
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

    public function memberScheduleNameForSlot(
        Court $court,
        string $date,
        string $start,
        string $end,
        ?int $ignoreScheduleId = null,
    ): ?string
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
            if ($ignoreScheduleId !== null && (int) $schedule->id === $ignoreScheduleId) {
                continue;
            }

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

    /** @return array{status: string, display_name: ?string, match_details: ?array{competition_name: string, home_team: string, away_team: string}, tournament_rental_details: ?array{tournament_name: string, rental_start: string, rental_end: string}} */
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
                'tournament_rental_details' => null,
            ];
        }

        $match = $includeMatchDetails ? $this->matchForSlot($court, $date, $start, $end) : null;
        // Keep a fixture's detail badge distinct from a tournament-wide rental
        // block, even when callers skip loading match relations for the slot grid.
        $hasMatchConflict = $match !== null || (!$includeMatchDetails && $this->matchingGameQuery($court, $date, $start, $end)->exists());
        if ($hasMatchConflict) {
            return [
                'status' => 'match',
                'display_name' => null,
                'match_details' => $match ? [
                    'competition_name' => $match->tournament?->name ?: 'Pertandingan persahabatan',
                    'home_team' => $match->homeTeam?->name ?: 'TBA',
                    'away_team' => $match->awayTeam?->name ?: 'TBA',
                ] : null,
                'tournament_rental_details' => null,
            ];
        }

        $tournamentRental = $this->tournamentRentalForSlot($court, $date, $start, $end);
        if ($tournamentRental) {
            return [
                'status' => 'tournament_rental',
                'display_name' => $tournamentRental->tournament_name_snapshot,
                'match_details' => null,
                'tournament_rental_details' => [
                    'tournament_name' => $tournamentRental->tournament_name_snapshot,
                    'rental_start' => substr((string) $tournamentRental->time_start, 0, 5),
                    'rental_end' => substr((string) $tournamentRental->time_end, 0, 5),
                ],
            ];
        }

        $memberName = $this->memberScheduleNameForSlot($court, $date, $start, $end);
        if ($memberName !== null) {
            return ['status' => 'member', 'display_name' => $memberName, 'match_details' => null, 'tournament_rental_details' => null];
        }

        if ($hasPassed) {
            return ['status' => 'past_available', 'display_name' => null, 'match_details' => null, 'tournament_rental_details' => null];
        }

        return ['status' => 'available', 'display_name' => null, 'match_details' => null, 'tournament_rental_details' => null];
    }

    public function lockCourt(int $courtId): ?Court
    {
        return Court::query()->whereKey($courtId)->lockForUpdate()->first();
    }
}
