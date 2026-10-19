<?php

namespace App\Services;

use App\Models\Court;
use App\Models\CourtBooking;
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
        if ($matchEnd->lessThanOrEqualTo($now)) {
            return;
        }

        $bookingConflict = CourtBooking::query()
            ->where('court_id', $court->id)
            ->where('booking_date', $date)
            ->where('time_start', '<', $end)
            ->where('time_end', '>', $start)
            ->where(function ($query) use ($now) {
                $query->where('booking_date', '>', $now->toDateString())
                    ->orWhere(function ($query) use ($now) {
                        $query->where('booking_date', $now->toDateString())
                            ->where('time_end', '>', $now->format('H:i:s'));
                    });
            })
            ->where(function ($query) {
                $query->whereIn('status', [
                    CourtBooking::STATUS_PENDING_REVIEW,
                    CourtBooking::STATUS_CONFIRMED,
                ])->orWhere(function ($query) {
                    $query->where('status', CourtBooking::STATUS_AWAITING_PAYMENT)
                        ->where('expires_at', '>', now());
                });
            })
            ->exists();

        if ($bookingConflict) {
            throw ValidationException::withMessages([
                'venue' => 'Jadwal bertabrakan dengan booking lapangan yang masih aktif.',
            ]);
        }
    }
}
