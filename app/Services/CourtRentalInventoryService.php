<?php

namespace App\Services;

use App\Models\CourtBooking;
use App\Models\CourtBookingRentalAddition;
use App\Models\CourtRentalOrder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CourtRentalInventoryService
{
    public function expireUnpaidOrders(?string $date = null): int
    {
        $localNow = Carbon::now(CourtBooking::TIMEZONE);
        $bookingStarted = DB::table('court_bookings as booking')
            ->select('booking.id')
            ->whereColumn('booking.id', 'court_booking_rental_additions.court_booking_id')
            ->where(function ($started) use ($localNow) {
                $started->whereDate('booking.booking_date', '<', $localNow->toDateString())
                    ->orWhere(function ($sameDay) use ($localNow) {
                        $sameDay->whereDate('booking.booking_date', $localNow->toDateString())
                            ->where('booking.time_start', '<=', $localNow->format('H:i:s'));
                    });
            });
        $expiredOrders = CourtRentalOrder::query()
            ->where('status', CourtRentalOrder::STATUS_AWAITING_PAYMENT)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->when($date, fn ($query) => $query->whereDate('rental_date', $date))
            ->update(['status' => CourtRentalOrder::STATUS_EXPIRED, 'updated_at' => now()]);

        $expiredAdditions = DB::table('court_booking_rental_additions')
            ->where(function ($expired) use ($date, $bookingStarted) {
                $expired->where(function ($byHoldExpiry) use ($date) {
                    $byHoldExpiry->where('status', CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT)
                        ->whereNotNull('expires_at')->where('expires_at', '<=', now())
                        ->when($date, fn ($query) => $query->whereIn('court_booking_id', DB::table('court_bookings')
                            ->select('id')->whereDate('booking_date', $date)));
                })->orWhere(function ($bySessionStart) use ($date, $bookingStarted) {
                    $bySessionStart->where('status', CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT)
                        ->whereNotNull('expires_at')
                        ->when($date, fn ($query) => $query->whereIn('court_booking_id', DB::table('court_bookings')
                            ->select('id')->whereDate('booking_date', $date)))
                        ->whereExists($bookingStarted);
                });
            })
            ->update(['status' => CourtBookingRentalAddition::STATUS_EXPIRED, 'updated_at' => now()]);

        return $expiredOrders + $expiredAdditions;
    }

    public function reservationsForDate(string $date, array $itemIds, bool $lockForUpdate = false): Collection
    {
        if ($itemIds === []) {
            return collect();
        }

        $bookingQuery = DB::table('court_booking_rental_lines as line')
            ->join('court_bookings as reservation', 'reservation.id', '=', 'line.court_booking_id')
            ->whereIn('line.court_rental_item_id', $itemIds)
            ->where(function ($scheduled) use ($date) {
                $scheduled->whereDate('reservation.booking_date', $date)
                    ->orWhere(function ($carried) use ($date) {
                        $carried->whereDate('reservation.booking_date', '<', $date)
                            ->whereNotNull('reservation.rental_picked_up_at')
                            ->whereNull('reservation.rental_returned_at');
                    })->orWhere(function ($overnight) use ($date) {
                        $overnight->whereDate('reservation.booking_date', Carbon::parse($date, CourtBooking::TIMEZONE)->subDay()->toDateString())
                            ->whereColumn('reservation.time_end', '<=', 'reservation.time_start');
                    });
            })
            ->where($this->activeReservationPredicate(...));

        $orderQuery = DB::table('court_rental_order_lines as line')
            ->join('court_rental_orders as reservation', 'reservation.id', '=', 'line.court_rental_order_id')
            ->whereIn('line.court_rental_item_id', $itemIds)
            ->where(function ($scheduled) use ($date) {
                $scheduled->whereDate('reservation.rental_date', $date)
                    ->orWhere(function ($carried) use ($date) {
                        $carried->whereDate('reservation.rental_date', '<', $date)
                            ->whereNotNull('reservation.rental_picked_up_at')
                            ->whereNull('reservation.rental_returned_at');
                    })->orWhere(function ($overnight) use ($date) {
                        $overnight->whereDate('reservation.rental_date', Carbon::parse($date, CourtRentalOrder::TIMEZONE)->subDay()->toDateString())
                            ->whereColumn('reservation.time_end', '<=', 'reservation.time_start');
                    });
            })
            ->where($this->activeReservationPredicate(...));

        $additionQuery = DB::table('court_booking_rental_addition_lines as line')
            ->join('court_booking_rental_additions as addition', 'addition.id', '=', 'line.court_booking_rental_addition_id')
            ->join('court_bookings as reservation', 'reservation.id', '=', 'addition.court_booking_id')
            ->whereIn('line.court_rental_item_id', $itemIds)
            ->where('reservation.status', CourtBooking::STATUS_CONFIRMED)
            ->where(function ($scheduled) use ($date) {
                $scheduled->whereDate('reservation.booking_date', $date)
                    ->orWhere(function ($carried) use ($date) {
                        $carried->whereDate('reservation.booking_date', '<', $date)
                            ->whereNotNull('reservation.rental_picked_up_at')
                            ->whereNull('reservation.rental_returned_at');
                    })->orWhere(function ($overnight) use ($date) {
                        $overnight->whereDate('reservation.booking_date', Carbon::parse($date, CourtBooking::TIMEZONE)->subDay()->toDateString())
                            ->whereColumn('reservation.time_end', '<=', 'reservation.time_start');
                    });
            })
            ->where($this->activeAdditionReservationPredicate(...));

        if ($lockForUpdate) {
            $bookingQuery->lockForUpdate();
            $orderQuery->lockForUpdate();
            $additionQuery->lockForUpdate();
        }

        return $bookingQuery->get([
            'reservation.id as reservation_record_id', DB::raw("'booking' as reservation_source"),
            'line.court_rental_item_id as item_id', 'line.quantity',
            'reservation.booking_date as reservation_date', 'reservation.time_start', 'reservation.time_end',
            'reservation.rental_picked_up_at', 'reservation.rental_returned_at',
        ])->concat($orderQuery->get([
            'reservation.id as reservation_record_id', DB::raw("'order' as reservation_source"),
            'line.court_rental_item_id as item_id', 'line.quantity',
            'reservation.rental_date as reservation_date', 'reservation.time_start', 'reservation.time_end',
            'reservation.rental_picked_up_at', 'reservation.rental_returned_at',
        ]))->concat($additionQuery->get([
            'reservation.id as reservation_record_id', DB::raw("'addition' as reservation_source"),
            'line.court_rental_item_id as item_id', 'line.quantity',
            'reservation.booking_date as reservation_date', 'reservation.time_start', 'reservation.time_end',
            'reservation.rental_picked_up_at', 'reservation.rental_returned_at',
        ]))->flatMap(fn ($reservation) => $this->reservationOnDate($reservation, $date))->values();
    }

    public function reservationsForInterval(string $date, string $start, string $end, array $itemIds, bool $lockForUpdate = false): Collection
    {
        if ($itemIds === []) {
            return collect();
        }

        $startAt = Carbon::parse($date.' '.$start, CourtBooking::TIMEZONE);
        $endAt = Carbon::parse($date.' '.$end, CourtBooking::TIMEZONE);
        if ($endAt->lessThanOrEqualTo($startAt)) {
            $endAt->addDay();
        }
        $lastDate = $endAt->copy()->subSecond()->toDateString();
        $reservations = collect();
        for ($cursor = $startAt->copy()->startOfDay(); $cursor->toDateString() <= $lastDate; $cursor->addDay()) {
            $reservations = $reservations->concat($this->reservationsForDate($cursor->toDateString(), $itemIds, $lockForUpdate));
        }

        return $reservations->values();
    }

    public function reservationOverlapsInterval(string $date, string $start, string $end, object $reservation): bool
    {
        $startAt = Carbon::parse($date.' '.$start, CourtBooking::TIMEZONE);
        $endAt = Carbon::parse($date.' '.$end, CourtBooking::TIMEZONE);
        if ($endAt->lessThanOrEqualTo($startAt)) {
            $endAt->addDay();
        }

        $reservationDate = (string) ($reservation->reservation_date ?? $date);
        $reservationStart = Carbon::parse($reservationDate.' '.$reservation->time_start, CourtBooking::TIMEZONE);
        $reservationEnd = Carbon::parse($reservationDate.' '.$reservation->time_end, CourtBooking::TIMEZONE);
        if ($reservationEnd->lessThanOrEqualTo($reservationStart)) {
            $reservationEnd->addDay();
        }

        return $reservationStart->lessThan($endAt) && $reservationEnd->greaterThan($startAt);
    }

    public function maximumConcurrentReservedQuantity(int $itemId): int
    {
        $today = Carbon::today(CourtRentalOrder::TIMEZONE)->toDateString();
        $bookingReservations = DB::table('court_booking_rental_lines as line')
            ->join('court_bookings as reservation', 'reservation.id', '=', 'line.court_booking_id')
            ->where('line.court_rental_item_id', $itemId)
            ->whereDate('reservation.booking_date', '>=', $today)
            ->where($this->activeReservationPredicate(...))
            ->get([
                'reservation.booking_date as rental_date', 'reservation.time_start', 'reservation.time_end', 'line.quantity',
                'reservation.rental_picked_up_at', 'reservation.rental_returned_at',
            ]);
        $orderReservations = DB::table('court_rental_order_lines as line')
            ->join('court_rental_orders as reservation', 'reservation.id', '=', 'line.court_rental_order_id')
            ->where('line.court_rental_item_id', $itemId)
            ->whereDate('reservation.rental_date', '>=', $today)
            ->where($this->activeReservationPredicate(...))
            ->get([
                'reservation.rental_date', 'reservation.time_start', 'reservation.time_end', 'line.quantity',
                'reservation.rental_picked_up_at', 'reservation.rental_returned_at',
            ]);
        $additionReservations = DB::table('court_booking_rental_addition_lines as line')
            ->join('court_booking_rental_additions as addition', 'addition.id', '=', 'line.court_booking_rental_addition_id')
            ->join('court_bookings as reservation', 'reservation.id', '=', 'addition.court_booking_id')
            ->where('line.court_rental_item_id', $itemId)
            ->where('reservation.status', CourtBooking::STATUS_CONFIRMED)
            ->whereDate('reservation.booking_date', '>=', $today)
            ->where($this->activeAdditionReservationPredicate(...))
            ->get([
                'reservation.booking_date as rental_date', 'reservation.time_start', 'reservation.time_end', 'line.quantity',
                'reservation.rental_picked_up_at', 'reservation.rental_returned_at',
            ]);

        $carriedFromEarlierDays = $this->checkedOutQuantityBefore($itemId, $today);
        $maximum = $carriedFromEarlierDays;
        foreach ($bookingReservations->concat($orderReservations)->concat($additionReservations)->groupBy(fn ($reservation) => (string) $reservation->rental_date) as $sameDayReservations) {
            $reservationDate = (string) $sameDayReservations->first()->rental_date;
            $carriedForDate = $reservationDate > $today
                ? $this->checkedOutQuantityBefore($itemId, $reservationDate)
                : $carriedFromEarlierDays;
            $events = [];
            foreach ($sameDayReservations as $reservation) {
                $startAt = Carbon::parse($reservation->rental_date.' '.$reservation->time_start, CourtRentalOrder::TIMEZONE);
                $scheduledEnd = Carbon::parse($reservation->rental_date.' '.$reservation->time_end, CourtRentalOrder::TIMEZONE);
                if ($reservation->rental_returned_at) {
                    $endAt = $this->databaseTimestamp($reservation->rental_returned_at);
                } elseif ($reservation->rental_picked_up_at && $scheduledEnd->lessThanOrEqualTo(Carbon::now(CourtRentalOrder::TIMEZONE))) {
                    $endAt = Carbon::parse($reservation->rental_date.' 23:59:59', CourtRentalOrder::TIMEZONE);
                } else {
                    $endAt = $scheduledEnd;
                }
                if ($endAt->lessThanOrEqualTo($startAt)) {
                    continue;
                }
                $events[] = ['time' => $startAt->timestamp, 'delta' => (int) $reservation->quantity];
                $events[] = ['time' => $endAt->timestamp, 'delta' => -(int) $reservation->quantity];
            }

            usort($events, fn ($left, $right) => [$left['time'], $left['delta']] <=> [$right['time'], $right['delta']]);
            $reserved = (int) $carriedForDate;
            foreach ($events as $event) {
                $reserved += $event['delta'];
                $maximum = max($maximum, $reserved);
            }
        }

        return $maximum;
    }

    private function reservationOnDate(object $reservation, string $date): Collection
    {
        $timezone = CourtRentalOrder::TIMEZONE;
        $dayStart = Carbon::parse($date.' 00:00:00', $timezone);
        $dayEnd = Carbon::parse($date.' 23:59:59', $timezone);
        $start = Carbon::parse($reservation->reservation_date.' '.$reservation->time_start, $timezone);
        $scheduledEnd = Carbon::parse($reservation->reservation_date.' '.$reservation->time_end, $timezone);
        if ($scheduledEnd->lessThanOrEqualTo($start)) {
            $scheduledEnd->addDay();
        }
        $pickedUpAt = $reservation->rental_picked_up_at
            ? $this->databaseTimestamp($reservation->rental_picked_up_at)
            : null;
        $returnedAt = $reservation->rental_returned_at
            ? $this->databaseTimestamp($reservation->rental_returned_at)
            : null;

        if ($pickedUpAt && $pickedUpAt->lessThan($start)) {
            $start = $pickedUpAt->copy();
        }

        if ($returnedAt) {
            $end = $returnedAt->copy();
        } elseif ($pickedUpAt && $scheduledEnd->lessThanOrEqualTo(Carbon::now($timezone))) {
            // Once checked out, overdue gear stays unavailable until an admin records its return.
            $end = $dayEnd->copy();
        } else {
            $end = $scheduledEnd->copy();
        }

        if ($start->greaterThanOrEqualTo($dayEnd) || $end->lessThanOrEqualTo($dayStart) || $end->lessThanOrEqualTo($start)) {
            return collect();
        }

        return collect([(object) [
            'reservation_record_id' => (int) ($reservation->reservation_record_id ?? 0),
            'reservation_source' => (string) ($reservation->reservation_source ?? ''),
            'item_id' => (int) $reservation->item_id,
            'quantity' => (int) $reservation->quantity,
            'reservation_date' => $date,
            'time_start' => $start->greaterThan($dayStart) ? $start->format('H:i:s') : '00:00:00',
            'time_end' => $end->lessThan($dayEnd) ? $end->format('H:i:s') : '23:59:59',
        ]]);
    }

    private function databaseTimestamp(string $timestamp): Carbon
    {
        return Carbon::parse($timestamp, config('app.timezone', 'UTC'))
            ->timezone(CourtRentalOrder::TIMEZONE);
    }

    private function checkedOutQuantityBefore(int $itemId, string $date): int
    {
        $bookings = DB::table('court_booking_rental_lines as line')
            ->join('court_bookings as reservation', 'reservation.id', '=', 'line.court_booking_id')
            ->where('line.court_rental_item_id', $itemId)
            ->whereDate('reservation.booking_date', '<', $date)
            ->where('reservation.status', CourtBooking::STATUS_CONFIRMED)
            ->whereNotNull('reservation.rental_picked_up_at')
            ->whereNull('reservation.rental_returned_at')
            ->sum('line.quantity');
        $orders = DB::table('court_rental_order_lines as line')
            ->join('court_rental_orders as reservation', 'reservation.id', '=', 'line.court_rental_order_id')
            ->where('line.court_rental_item_id', $itemId)
            ->whereDate('reservation.rental_date', '<', $date)
            ->where('reservation.status', CourtRentalOrder::STATUS_CONFIRMED)
            ->whereNotNull('reservation.rental_picked_up_at')
            ->whereNull('reservation.rental_returned_at')
            ->sum('line.quantity');
        $additions = DB::table('court_booking_rental_addition_lines as line')
            ->join('court_booking_rental_additions as addition', 'addition.id', '=', 'line.court_booking_rental_addition_id')
            ->join('court_bookings as reservation', 'reservation.id', '=', 'addition.court_booking_id')
            ->where('line.court_rental_item_id', $itemId)
            ->whereDate('reservation.booking_date', '<', $date)
            ->where('reservation.status', CourtBooking::STATUS_CONFIRMED)
            ->where('addition.status', CourtBookingRentalAddition::STATUS_CONFIRMED)
            ->whereNotNull('reservation.rental_picked_up_at')
            ->whereNull('reservation.rental_returned_at')
            ->sum('line.quantity');

        return (int) $bookings + (int) $orders + (int) $additions;
    }

    private function activeReservationPredicate($query): void
    {
        $query->where(function ($active) {
            $active->whereIn('reservation.status', [
                CourtBooking::STATUS_PENDING_REVIEW,
                CourtBooking::STATUS_CONFIRMED,
            ])->orWhere(function ($awaiting) {
                $awaiting->where('reservation.status', CourtBooking::STATUS_AWAITING_PAYMENT)
                    ->where('reservation.expires_at', '>', now());
            });
        });
    }

    private function activeAdditionReservationPredicate($query): void
    {
        $now = Carbon::now(CourtBooking::TIMEZONE);
        $query->where(function ($active) use ($now) {
            $active->whereIn('addition.status', [
                CourtBookingRentalAddition::STATUS_PENDING_REVIEW,
                CourtBookingRentalAddition::STATUS_CONFIRMED,
            ])->orWhere(function ($awaiting) use ($now) {
                $awaiting->where('addition.status', CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT)
                    ->where('addition.expires_at', '>', now())
                    ->where(function ($notStarted) use ($now) {
                        $notStarted->whereDate('reservation.booking_date', '>', $now->toDateString())
                            ->orWhere(function ($sameDay) use ($now) {
                                $sameDay->whereDate('reservation.booking_date', $now->toDateString())
                                    ->where('reservation.time_start', '>', $now->format('H:i:s'));
                            });
                    });
            });
        });
    }
}
