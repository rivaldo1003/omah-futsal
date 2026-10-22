<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\CourtBooking;
use App\Models\CourtBookingPayment;
use App\Models\CourtBookingRentalAddition;
use App\Models\CourtBookingSetting;
use App\Models\CourtRentalItem;
use App\Services\CourtBookingAvailabilityService;
use App\Services\CourtRentalInventoryService;
use App\Services\CourtPricingService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CourtBookingController extends Controller
{
    public function index(
        Request $request,
        CourtBookingAvailabilityService $availability,
        CourtPricingService $pricing,
        CourtRentalInventoryService $rentalInventory,
    )
    {
        $today = Carbon::today(CourtBooking::TIMEZONE);
        $maxDate = $today->copy()->addDays(60)->toDateString();
        $validated = $request->validate([
            'date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:'.$today->toDateString(), 'before_or_equal:'.$maxDate],
        ]);
        $date = Carbon::parse($validated['date'] ?? $today->toDateString(), CourtBooking::TIMEZONE);

        $availability->expireUnpaidBookings(date: $date->toDateString());
        $rentalInventory->expireUnpaidOrders($date->toDateString());

        $courts = Court::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $settings = CourtBookingSetting::query()->first()
            ?? new CourtBookingSetting(['payment_hold_minutes' => 60]);
        $rentalItems = CourtRentalItem::query()
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->where('unit_price', '>', 0)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $rentalReservations = $rentalInventory->reservationsForDate($date->toDateString(), $rentalItems->modelKeys())
            ->concat($rentalInventory->reservationsForDate($date->copy()->addDay()->toDateString(), $rentalItems->modelKeys()));

        $schedules = $courts->map(function (Court $court) use ($date, $availability, $pricing, $rentalItems, $rentalReservations, $rentalInventory) {
            $slots = [];
            $cursor = Carbon::parse($date->toDateString().' '.$court->opening_time, CourtBooking::TIMEZONE);
            $closing = Carbon::parse($date->toDateString().' '.$court->closing_time, CourtBooking::TIMEZONE);
            if ($closing->lessThanOrEqualTo($cursor)) {
                $closing->addDay();
            }
            $duration = 30;

            // The booking date identifies the date play starts. Keep start slots
            // on that date, while allowing the last slot to finish at 00:00.
            while ($cursor->toDateString() === $date->toDateString()
                && $cursor->copy()->addMinutes($duration)->lessThanOrEqualTo($closing)) {
                $end = $cursor->copy()->addMinutes($duration);
                $startTime = $cursor->format('H:i:s');
                $endTime = $end->format('H:i:s');
                $slotAvailability = $availability->slotAvailability($court, $date->toDateString(), $startTime, $endTime);

                $slots[] = [
                    'start' => $cursor->format('H:i'),
                    'end' => $end->format('H:i'),
                    'starts_at' => $cursor->toIso8601String(),
                    'status' => $slotAvailability['status'],
                    'display_name' => $slotAvailability['display_name'],
                    'amount' => $pricing->amountForRange($court, $startTime, $duration, $date->toDateString()),
                    'duration' => $duration,
                    'request_key' => (string) Str::uuid(),
                    'rental_availability' => $rentalItems->mapWithKeys(function (CourtRentalItem $item) use ($rentalReservations, $startTime, $endTime, $date, $rentalInventory) {
                        $reserved = $rentalReservations
                            ->where('item_id', $item->id)
                            ->filter(fn ($reservation) => $rentalInventory->reservationOverlapsInterval($date->toDateString(), $startTime, $endTime, $reservation))
                            ->sum('quantity');

                        return [$item->id => max(0, (int) $item->stock_quantity - (int) $reserved)];
                    })->all(),
                ];

                $cursor = $end;
            }

            return [
                'court' => $court,
                'slots' => $slots,
            ];
        });

        return view('booking.index', [
            'date' => $date,
            'todayDate' => $today->toDateString(),
            'maxDate' => $maxDate,
            'schedules' => $schedules,
            'rentalItems' => $rentalItems,
            'settings' => $settings,
            'hasPaymentOption' => ($settings->hasBankTransfer() || $settings->hasQris()) && filled($settings->booking_policy),
        ]);
    }

    public function store(
        Request $request,
        CourtBookingAvailabilityService $availability,
        CourtPricingService $pricing,
        CourtRentalInventoryService $rentalInventory,
    )
    {
        $validated = $request->validate([
            'court_id' => ['required', 'integer', 'exists:courts,id'],
            'booking_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:'.Carbon::today(CourtBooking::TIMEZONE)->toDateString(),
                'before_or_equal:'.Carbon::today(CourtBooking::TIMEZONE)->addDays(60)->toDateString(),
            ],
            'time_start' => ['required', 'date_format:H:i'],
            'customer_name' => ['required', 'string', 'max:120'],
            'team_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[0-9+().\s-]+$/'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'payment_method' => ['required', 'in:bank_transfer,qris'],
            'booking_request_key' => ['required', 'uuid'],
            'quoted_duration_minutes' => ['required', 'integer', 'min:30', 'max:1440'],
            'quoted_amount' => ['required', 'numeric', 'min:0'],
            'rental_items' => ['nullable', 'array'],
            'rental_items.*' => ['required', 'integer', 'min:0', 'max:65535'],
            'booking_policy_hash' => ['required', 'string', 'size:64'],
            'accept_booking_rules' => ['accepted'],
        ]);

        $rentalSelections = collect($validated['rental_items'] ?? [])
            ->mapWithKeys(fn ($quantity, $itemId) => [(int) $itemId => (int) $quantity])
            ->filter(fn ($quantity) => $quantity > 0)
            ->sortKeys()
            ->all();

        $fingerprintPayload = json_encode([
            'court_id' => (int) $validated['court_id'],
            'booking_date' => $validated['booking_date'],
            'time_start' => $validated['time_start'],
            'customer_name' => $validated['customer_name'],
            'team_name' => trim($validated['team_name']),
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'payment_method' => $validated['payment_method'],
            'quoted_duration_minutes' => (int) $validated['quoted_duration_minutes'],
            'quoted_amount' => number_format((float) $validated['quoted_amount'], 2, '.', ''),
            'rental_items' => $rentalSelections,
            'booking_policy_hash' => $validated['booking_policy_hash'],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $requestFingerprint = hash_hmac('sha256', $fingerprintPayload, (string) config('app.key'));

        $existingBooking = CourtBooking::query()
            ->where('idempotency_key', $validated['booking_request_key'])
            ->first();
        if ($existingBooking) {
            $this->ensureSameIdempotentRequest($existingBooking, $requestFingerprint);

            return redirect()->route('booking.show', $existingBooking->public_token)
                ->with('success', 'Booking ini sudah pernah dibuat. Berikut status booking Anda.');
        }

        try {
            $booking = DB::transaction(function () use ($validated, $availability, $pricing, $rentalInventory, $requestFingerprint, $rentalSelections) {
                $court = $availability->lockCourt((int) $validated['court_id']);
                if (!$court || !$court->is_active) {
                    throw ValidationException::withMessages(['court_id' => 'Lapangan ini sedang tidak menerima booking.']);
                }

                // Locking read ini mengantisipasi request ulang yang menunggu lock lapangan.
                $existingBooking = CourtBooking::query()
                    ->where('idempotency_key', $validated['booking_request_key'])
                    ->lockForUpdate()
                    ->first();
                if ($existingBooking) {
                    $this->ensureSameIdempotentRequest($existingBooking, $requestFingerprint);

                    return $existingBooking;
                }

                // Shared lock menjaga snapshot pengaturan stabil tetapi tetap mengizinkan
                // booking di lapangan berbeda diproses bersama-sama.
                $settings = CourtBookingSetting::query()->whereKey(1)->sharedLock()->first();
                if (
                    !$settings
                    || !filled($settings->booking_policy)
                    || !hash_equals(hash('sha256', $settings->booking_policy), $validated['booking_policy_hash'])
                ) {
                    throw ValidationException::withMessages(['accept_booking_rules' => 'Kebijakan booking baru saja berubah. Muat ulang jadwal lalu periksa kembali sebelum memesan.']);
                }
                if ($validated['payment_method'] === 'bank_transfer' && !$settings->hasBankTransfer()) {
                    throw ValidationException::withMessages(['payment_method' => 'Informasi rekening baru saja berubah. Muat ulang jadwal dan pilih metode yang tersedia.']);
                }
                if ($validated['payment_method'] === 'qris' && !$settings->hasQris()) {
                    throw ValidationException::withMessages(['payment_method' => 'Pembayaran QRIS baru saja berubah. Muat ulang jadwal dan pilih metode yang tersedia.']);
                }

                $availability->expireUnpaidBookings($court->id, $validated['booking_date']);
                $slotDuration = 30;
                $duration = (int) $validated['quoted_duration_minutes'];
                if ($duration < $slotDuration || $duration % $slotDuration !== 0) {
                    throw ValidationException::withMessages(['time_start' => 'Durasi booking harus mengikuti kelipatan slot lapangan. Pilih ulang slot yang berurutan.']);
                }
                $opening = Carbon::parse($validated['booking_date'].' '.$court->opening_time, CourtBooking::TIMEZONE);
                $closing = Carbon::parse($validated['booking_date'].' '.$court->closing_time, CourtBooking::TIMEZONE);
                if ($closing->lessThanOrEqualTo($opening)) {
                    $closing->addDay();
                }
                $start = Carbon::createFromFormat('Y-m-d H:i', $validated['booking_date'].' '.$validated['time_start'], CourtBooking::TIMEZONE);
                $end = $start->copy()->addMinutes($duration);
                $offset = $opening->diffInMinutes($start, false);

                if ($start->lessThan($opening) || $end->greaterThan($closing) || $offset < 0 || $offset % $slotDuration !== 0) {
                    throw ValidationException::withMessages(['time_start' => 'Jam booking tidak sesuai dengan jadwal lapangan. Silakan pilih slot yang tersedia.']);
                }
                if ($start->lessThanOrEqualTo(now(CourtBooking::TIMEZONE))) {
                    throw ValidationException::withMessages(['time_start' => 'Jam yang dipilih sudah lewat. Silakan pilih jam lain.']);
                }

                $startTime = $start->format('H:i:s');
                $endTime = $end->format('H:i:s');
                if ($availability->bookingConflict($court, $validated['booking_date'], $startTime, $endTime)) {
                    throw ValidationException::withMessages(['time_start' => 'Slot baru saja dipesan. Silakan pilih jadwal lain.']);
                }
                if ($availability->matchConflict($court, $validated['booking_date'], $startTime, $endTime)) {
                    throw ValidationException::withMessages(['time_start' => 'Lapangan memiliki pertandingan pada jam tersebut. Silakan pilih jadwal lain.']);
                }
                if ($availability->memberScheduleConflict($court, $validated['booking_date'], $startTime, $endTime)) {
                    throw ValidationException::withMessages(['time_start' => 'Slot tersebut dialokasikan untuk jadwal member. Silakan pilih waktu lain.']);
                }

                $amount = $pricing->amountForRange($court, $startTime, $duration, $validated['booking_date']);

                $rentalItems = collect();
                $rentalTotal = 0.0;
                if ($rentalSelections !== []) {
                    $rentalItems = CourtRentalItem::query()
                        ->whereIn('id', array_keys($rentalSelections))
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    if ($rentalItems->count() !== count($rentalSelections)) {
                        throw ValidationException::withMessages(['rental_items' => 'Salah satu perlengkapan sudah tidak tersedia. Muat ulang halaman lalu pilih kembali.']);
                    }

                    $rentalInventory->expireUnpaidOrders($validated['booking_date']);
                    // Current locking reads from both order sources see requests that
                    // waited on the same item locks, including under MySQL repeatable read.
                    $reservations = $rentalInventory->reservationsForInterval(
                        $validated['booking_date'],
                        $startTime,
                        $endTime,
                        array_keys($rentalSelections),
                        lockForUpdate: true,
                    );
                    $reservedQuantities = $reservations
                        ->filter(fn ($reservation) => $rentalInventory->reservationOverlapsInterval($validated['booking_date'], $startTime, $endTime, $reservation))
                        ->unique(fn ($reservation) => $reservation->reservation_source.':'.$reservation->reservation_record_id.':'.$reservation->item_id)
                        ->groupBy('item_id')
                        ->map(fn ($lines) => $lines->sum('quantity'));

                    foreach ($rentalSelections as $itemId => $quantity) {
                        /** @var CourtRentalItem|null $item */
                        $item = $rentalItems->get($itemId);
                        $reserved = (int) ($reservedQuantities[$itemId] ?? 0);
                        $available = max(0, (int) $item->stock_quantity - $reserved);

                        if (!$item->is_active || (float) $item->unit_price <= 0 || $quantity > $available) {
                            throw ValidationException::withMessages([
                                'rental_items' => "Stok {$item->name} untuk rentang waktu ini tidak mencukupi. Muat ulang jadwal dan sesuaikan jumlahnya.",
                            ]);
                        }

                        $lineTotal = round((float) $item->unit_price * $quantity, 2);
                        if ($lineTotal > 9999999999.99) {
                            throw ValidationException::withMessages(['rental_items' => 'Total harga perlengkapan melebihi batas transaksi. Kurangi jumlah barang.']);
                        }
                        $rentalTotal += $lineTotal;
                    }
                }

                $amount += $rentalTotal;
                if ($amount > 9999999999.99) {
                    throw ValidationException::withMessages(['quoted_amount' => 'Total booking melebihi batas transaksi.']);
                }
                if (number_format($amount, 2, '.', '') !== number_format((float) $validated['quoted_amount'], 2, '.', '')) {
                    throw ValidationException::withMessages(['quoted_amount' => 'Rincian harga booking baru saja berubah. Muat ulang jadwal dan periksa kembali total sebelum memesan.']);
                }

                $booking = CourtBooking::query()->create([
                    'public_token' => (string) Str::uuid(),
                    'idempotency_key' => $validated['booking_request_key'],
                    'request_fingerprint' => $requestFingerprint,
                    'court_id' => $court->id,
                    'booking_date' => $validated['booking_date'],
                    'time_start' => $startTime,
                    'time_end' => $endTime,
                    'duration_minutes' => $duration,
                    'amount' => $amount,
                    'customer_name' => $validated['customer_name'],
                    'team_name' => trim($validated['team_name']),
                    'customer_phone' => $validated['customer_phone'],
                    'customer_email' => $validated['customer_email'] ?? null,
                    'payment_method' => $validated['payment_method'],
                    'payment_bank_name' => $validated['payment_method'] === 'bank_transfer' ? $settings->bank_name : null,
                    'payment_bank_account_number' => $validated['payment_method'] === 'bank_transfer' ? $settings->bank_account_number : null,
                    'payment_bank_account_name' => $validated['payment_method'] === 'bank_transfer' ? $settings->bank_account_name : null,
                    'payment_qris_image_path' => $validated['payment_method'] === 'qris' ? $settings->qris_image_path : null,
                    'payment_instructions_snapshot' => $settings->payment_instructions,
                    'booking_policy_snapshot' => $settings->booking_policy,
                    'status' => CourtBooking::STATUS_AWAITING_PAYMENT,
                    'expires_at' => now()->addMinutes(max(10, (int) $settings->payment_hold_minutes)),
                ]);

                foreach ($rentalSelections as $itemId => $quantity) {
                    $item = $rentalItems->get($itemId);
                    $lineTotal = round((float) $item->unit_price * $quantity, 2);
                    $booking->rentalLines()->create([
                        'court_rental_item_id' => $item->id,
                        'item_name_snapshot' => $item->name,
                        'quantity' => $quantity,
                        'unit_price' => $item->unit_price,
                        'line_total' => $lineTotal,
                    ]);
                }

                return $booking;
            }, 3);
        } catch (QueryException $exception) {
            $existingBooking = CourtBooking::query()
                ->where('idempotency_key', $validated['booking_request_key'])
                ->first();

            if (!$existingBooking) {
                throw $exception;
            }

            $this->ensureSameIdempotentRequest($existingBooking, $requestFingerprint);
            $booking = $existingBooking;
        }

        return redirect()->route('booking.show', $booking->public_token)
            ->with('success', 'Detail booking dan status pembayaran Anda tersedia di halaman ini.');
    }

    public function show(string $token, CourtBookingAvailabilityService $availability)
    {
        $booking = CourtBooking::query()
            ->with(['court', 'rentalLines', 'rentalAdditions.lines', 'payments'])
            ->where('public_token', $token)
            ->firstOrFail();

        app(CourtRentalInventoryService::class)->expireUnpaidOrders($booking->booking_date->toDateString());
        if (
            $booking->status === CourtBooking::STATUS_AWAITING_PAYMENT
            && $booking->expires_at
            && $booking->expires_at->isPast()
        ) {
            $availability->expireUnpaidBookings($booking->court_id, $booking->booking_date->toDateString());
            $booking->refresh();
        }

        $booking->load(['court', 'rentalLines', 'rentalAdditions.lines', 'payments']);
        $booking->setAttribute('paid_amount', $booking->payments
            ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
            ->sum(fn (CourtBookingPayment $payment) => $payment->recognized_amount));

        $scheduleHasPassed = $this->scheduleHasPassed($booking);

        return view('booking.show', compact('booking', 'scheduleHasPassed'));
    }

    public function status(string $token, CourtBookingAvailabilityService $availability, CourtRentalInventoryService $rentalInventory)
    {
        $booking = CourtBooking::query()
            ->with('court')
            ->where('public_token', $token)
            ->firstOrFail();

        if (
            $booking->status === CourtBooking::STATUS_AWAITING_PAYMENT
            && $booking->expires_at
            && $booking->expires_at->isPast()
        ) {
            $availability->expireUnpaidBookings($booking->court_id, $booking->booking_date->toDateString());
            $booking->refresh()->loadMissing('court');
        }

        $rentalInventory->expireUnpaidOrders($booking->booking_date->toDateString());
        $booking->load('rentalAdditions.lines');

        return response()->json([
            'code' => str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT),
            'status' => $booking->status,
            'status_label' => $booking->status_label,
            'court_name' => $booking->court->name,
            'booking_date' => $booking->booking_date->toDateString(),
            'time_start' => substr($booking->time_start, 0, 5),
            'time_end' => substr($booking->time_end, 0, 5),
            'amount' => (float) $booking->amount,
            'schedule_has_passed' => $this->scheduleHasPassed($booking),
            'expires_at' => $booking->expires_at?->toIso8601String(),
            'rental_additions' => $booking->rentalAdditions->map(fn (CourtBookingRentalAddition $addition) => [
                'id' => $addition->id,
                'status' => $addition->status,
                'amount' => (float) $addition->amount,
                'expires_at' => $addition->expires_at?->toIso8601String(),
            ])->values(),
        ], 200, [
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function submitPaymentProof(Request $request, string $token)
    {
        $validated = $request->validate([
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $booking = CourtBooking::query()->where('public_token', $token)->firstOrFail();
        if (
            $booking->status !== CourtBooking::STATUS_AWAITING_PAYMENT
            || !$booking->expires_at
            || $booking->expires_at->isPast()
        ) {
            return back()->withErrors(['payment_proof' => 'Waktu pembayaran sudah berakhir. Buat booking baru untuk memilih slot kembali.']);
        }

        $path = $request->file('payment_proof')->store('court-booking-proofs', 'local');
        if (!$path) {
            return back()->withErrors(['payment_proof' => 'Bukti pembayaran gagal disimpan. Coba unggah kembali.']);
        }

        try {
            $updated = DB::transaction(function () use ($booking, $validated, $path, $request) {
                $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
                if (
                    $lockedBooking->status !== CourtBooking::STATUS_AWAITING_PAYMENT
                    || !$lockedBooking->expires_at
                    || $lockedBooking->expires_at->isPast()
                ) {
                    return false;
                }

                $recordedAt = now();

                $lockedBooking->payments()->create([
                    // Amount is recorded by an admin after checking the proof.
                    'amount' => 0,
                    'stage' => CourtBookingPayment::STAGE_UNVERIFIED,
                    'payment_method' => $lockedBooking->payment_method,
                    'payment_reference' => $validated['payment_reference'] ?? null,
                    'payment_proof_path' => $path,
                    'payment_proof_original_name' => $request->file('payment_proof')->getClientOriginalName(),
                    'status' => CourtBookingPayment::STATUS_PENDING_REVIEW,
                    'recorded_at' => $recordedAt,
                ]);

                $lockedBooking->update([
                    'payment_reference' => $validated['payment_reference'] ?? null,
                    'payment_proof_path' => $path,
                    'payment_proof_original_name' => $request->file('payment_proof')->getClientOriginalName(),
                    'status' => CourtBooking::STATUS_PENDING_REVIEW,
                    'expires_at' => null,
                ]);

                return true;
            });
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }

        if (!$updated) {
            Storage::disk('local')->delete($path);
            app(CourtBookingAvailabilityService::class)->expireUnpaidBookings($booking->court_id, $booking->booking_date->toDateString());
            return back()->withErrors(['payment_proof' => 'Waktu pembayaran sudah berakhir. Buat booking baru untuk memilih slot kembali.']);
        }

        return redirect()->route('booking.show', $booking->public_token)
            ->with('success', 'Bukti pembayaran terkirim. Booking menunggu pemeriksaan admin.');
    }

    public function submitRentalAdditionProof(Request $request, string $token, CourtBookingRentalAddition $addition)
    {
        $validated = $request->validate([
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);
        $booking = CourtBooking::query()->where('public_token', $token)->firstOrFail();
        abort_unless((int) $addition->court_booking_id === (int) $booking->id, 404);
        $path = $request->file('payment_proof')->store('court-booking-addition-proofs', 'local');
        if (!$path) {
            return back()->withErrors(['payment_proof' => 'Bukti pembayaran gagal disimpan. Coba unggah kembali.']);
        }

        try {
            $updated = DB::transaction(function () use ($booking, $addition, $validated, $path, $request) {
                $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
                $lockedAddition = CourtBookingRentalAddition::query()->whereKey($addition->id)->lockForUpdate()->firstOrFail();
                $start = Carbon::parse($lockedBooking->booking_date->toDateString().' '.$lockedBooking->time_start, CourtBooking::TIMEZONE);
                if (
                    $lockedBooking->status !== CourtBooking::STATUS_CONFIRMED
                    || $lockedBooking->rental_picked_up_at
                    || !$start->isAfter(now(CourtBooking::TIMEZONE))
                    || $lockedAddition->status !== CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT
                    || !$lockedAddition->expires_at
                    || $lockedAddition->expires_at->isPast()
                ) {
                    return false;
                }

                $lockedAddition->update([
                    'payment_reference' => $validated['payment_reference'] ?? null,
                    'payment_proof_path' => $path,
                    'payment_proof_original_name' => $request->file('payment_proof')->getClientOriginalName(),
                    'status' => CourtBookingRentalAddition::STATUS_PENDING_REVIEW,
                    'expires_at' => null,
                ]);

                return true;
            });
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }

        if (!$updated) {
            Storage::disk('local')->delete($path);
            app(CourtRentalInventoryService::class)->expireUnpaidOrders($booking->booking_date->toDateString());
            return back()->withErrors(['payment_proof' => 'Waktu pembayaran tambahan berakhir atau booking sudah dimulai. Hubungi admin untuk bantuan.']);
        }

        return redirect()->route('booking.show', $booking->public_token)
            ->with('success', 'Bukti pembayaran tambahan terkirim. Admin sedang memeriksanya.');
    }

    private function ensureSameIdempotentRequest(CourtBooking $booking, string $fingerprint): void
    {
        if (!$booking->request_fingerprint || !hash_equals($booking->request_fingerprint, $fingerprint)) {
            throw ValidationException::withMessages([
                'booking_request_key' => 'Kunci booking sudah digunakan untuk data yang berbeda. Muat ulang jadwal dan mulai booking baru.',
            ]);
        }
    }

    private function scheduleHasPassed(CourtBooking $booking): bool
    {
        if ($booking->status !== CourtBooking::STATUS_CONFIRMED) {
            return false;
        }

        $bookingEnd = Carbon::parse(
            $booking->booking_date->toDateString().' '.$booking->time_end,
            CourtBooking::TIMEZONE,
        );

        return $bookingEnd->lessThanOrEqualTo(Carbon::now(CourtBooking::TIMEZONE));
    }
}
