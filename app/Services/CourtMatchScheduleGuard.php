<?php

namespace App\Services;

use App\Models\Court;
use App\Models\CourtBooking;
use App\Models\CourtTournamentRental;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CourtMatchScheduleGuard
{
    public function assertAvailable(Game $match): void
    {
        if ($match->is_cancelled || $match->status === 'postponed' || !filled($match->venue)) {
            return;
        }

        if (DB::transactionLevel() < 1) {
            throw new \LogicException('Jadwal pertandingan harus disimpan di dalam transaksi.');
        }

        $court = Court::query()
            ->whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower(trim((string) $match->venue))])
            ->lockForUpdate()
            ->first();

        // Venue yang bukan lapangan booking tidak perlu dikunci.
        if (!$court) {
            return;
        }

        // Simpan nama venue secara kanonis agar pemeriksaan booking dan match
        // memakai nilai yang sama, termasuk jika input admin memiliki spasi.
        $match->venue = $court->name;

        if (!$match->match_date || !$match->time_start || !$match->time_end) {
            throw ValidationException::withMessages([
                'match_date' => 'Tanggal, waktu mulai, dan waktu selesai wajib diisi untuk jadwal lapangan ini.',
            ]);
        }

        $date = Carbon::parse($match->match_date)->toDateString();
        $start = Carbon::parse((string) $match->time_start)->format('H:i:s');
        $end = Carbon::parse((string) $match->time_end)->format('H:i:s');

        if ($start >= $end) {
            throw ValidationException::withMessages([
                'time_end' => 'Waktu selesai pertandingan harus setelah waktu mulai.',
            ]);
        }

        $now = now(CourtBooking::TIMEZONE);
        $matchEnd = Carbon::parse($date.' '.$end, CourtBooking::TIMEZONE);
        $rentalConflict = app(CourtBookingAvailabilityService::class)->tournamentRentalConflict(
            $court,
            $date,
            $start,
            $end,
            null,
            $match->tournament_id ? (int) $match->tournament_id : null,
        );

        if ($rentalConflict) {
            throw ValidationException::withMessages([
                'venue' => 'Jadwal bertabrakan dengan sesi sewa turnamen lain yang mencakup waktu jeda. Periksa kembali lapangan dan jam sewanya.',
            ]);
        }

        if ($match->tournament_id) {
            $matchStart = Carbon::parse($date.' '.$start, CourtBooking::TIMEZONE);
            $matchEndAt = Carbon::parse($date.' '.$end, CourtBooking::TIMEZONE);
            if ($matchEndAt->lessThanOrEqualTo($matchStart)) {
                $matchEndAt->addDay();
            }
            $matchingRentals = CourtTournamentRental::query()
                ->where('court_id', $court->id)
                ->where('tournament_id', $match->tournament_id)
                ->where('status', '!=', CourtTournamentRental::STATUS_CANCELLED)
                ->whereDate('rental_date', '>=', $matchStart->copy()->subDay()->toDateString())
                ->whereDate('rental_date', '<=', $matchEndAt->toDateString())
                ->get();

            foreach ($matchingRentals as $matchingRental) {
                $rentalStart = Carbon::parse($matchingRental->rental_date->toDateString().' '.$matchingRental->time_start, CourtBooking::TIMEZONE);
                $rentalEnd = Carbon::parse($matchingRental->rental_date->toDateString().' '.$matchingRental->time_end, CourtBooking::TIMEZONE);
                if ($rentalEnd->lessThanOrEqualTo($rentalStart)) {
                    $rentalEnd->addDay();
                }
                if ($rentalStart->lessThan($matchEndAt) && $rentalEnd->greaterThan($matchStart)
                    && ($matchStart->lessThan($rentalStart) || $matchEndAt->greaterThan($rentalEnd))) {
                    throw ValidationException::withMessages([
                        'time_start' => 'Jadwal pertandingan harus berada di dalam rentang sewa turnamennya.',
                    ]);
                }
            }
        }

        // Historical fixtures still have to fit the explicitly recorded rental
        // session; only booking overlap checks can be skipped for elapsed games.
        if ($matchEnd->lessThanOrEqualTo($now)) {
            return;
        }

        $matchStart = Carbon::parse($date.' '.$start, CourtBooking::TIMEZONE);
        $matchEnd = Carbon::parse($date.' '.$end, CourtBooking::TIMEZONE);
        $bookingCandidates = CourtBooking::query()
            ->where('court_id', $court->id)
            ->whereDate('booking_date', '>=', $matchStart->copy()->subDay()->toDateString())
            ->whereDate('booking_date', '<=', $matchStart->toDateString())
            ->where(function ($query) {
                $query->whereIn('status', [
                    CourtBooking::STATUS_PENDING_REVIEW,
                    CourtBooking::STATUS_CONFIRMED,
                ])->orWhere(function ($query) {
                    $query->where('status', CourtBooking::STATUS_AWAITING_PAYMENT)
                        ->where('expires_at', '>', now());
                });
            })
            ->with('tournaments:id')
            ->get(['id', 'tournament_id', 'booking_date', 'time_start', 'time_end', 'source']);

        foreach ($bookingCandidates as $booking) {
            $bookingStart = Carbon::parse(
                $booking->booking_date->toDateString().' '.$booking->time_start,
                CourtBooking::TIMEZONE,
            );
            $bookingEnd = Carbon::parse(
                $booking->booking_date->toDateString().' '.$booking->time_end,
                CourtBooking::TIMEZONE,
            );
            if ($bookingEnd->lessThanOrEqualTo($bookingStart)) {
                $bookingEnd->addDay();
            }

            if (! $bookingStart->lessThan($matchEnd) || ! $bookingEnd->greaterThan($matchStart)) {
                continue;
            }

            // A booking may be shared by several tournaments when one organizer
            // rents the court for multiple competitions on the same date range.
            $linkedTournamentIds = $booking->tournaments->pluck('id')->map(fn ($id) => (int) $id)->all();
            if ($booking->tournament_id !== null) {
                $linkedTournamentIds[] = (int) $booking->tournament_id;
            }

            // Only a reservation explicitly linked to this same tournament can
            // be reused by its fixture. Unrelated bookings remain blocking.
            if ($match->tournament_id !== null && in_array((int) $match->tournament_id, $linkedTournamentIds, true)) {
                if ($matchStart->greaterThanOrEqualTo($bookingStart) && $matchEnd->lessThanOrEqualTo($bookingEnd)) {
                    continue;
                }

                throw ValidationException::withMessages([
                    'time_start' => 'Jadwal pertandingan harus berada di dalam rentang booking turnamen yang terkait.',
                ]);
            }

            if ($match->tournament_id !== null
                && $linkedTournamentIds === []
                && $booking->source === 'walk_in') {
                throw ValidationException::withMessages([
                    'venue' => 'Booking #'.str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT)
                        .' belum dikaitkan ke turnamen. Jika ini reservasi turnamen, buka Booking Lapangan dan pilih “Kaitkan reservasi turnamen” untuk menghubungkan satu atau beberapa booking sekaligus.',
                ]);
            }

            throw ValidationException::withMessages([
                'venue' => 'Jadwal bertabrakan dengan booking lapangan yang masih aktif.',
            ]);
        }
    }
}
