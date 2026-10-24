<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\CourtBooking;
use App\Models\CourtBookingBackdateAudit;
use App\Models\CourtBookingPayment;
use App\Models\CourtRentalItem;
use App\Models\CourtRentalOrder;
use App\Services\CourtBookingAvailabilityService;
use App\Services\CourtPricingService;
use App\Services\CourtRentalInventoryService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CourtWalkInOrderController extends Controller
{
    private const MAX_BACKDATE_DAYS = 30;

    public function createBooking(
        Request $request,
        CourtBookingAvailabilityService $availability,
        CourtPricingService $pricing,
        CourtRentalInventoryService $inventory,
    ) {
        $this->ensureAdmin();

        $today = Carbon::today(CourtBooking::TIMEZONE);
        $minimumDate = $today->copy()->subDays(self::MAX_BACKDATE_DAYS);
        $filters = $request->validate([
            'date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:'.$minimumDate->toDateString(), 'before_or_equal:'.$today->copy()->addDays(60)->toDateString()],
            'court_id' => ['nullable', 'integer', Rule::exists('courts', 'id')],
            'range' => ['nullable', 'regex:/^(?:now|\d{2}:\d{2})\|\d{2}:\d{2}$/'],
            'manual_request_key' => ['nullable', 'uuid'],
        ]);

        $date = $filters['date'] ?? $today->toDateString();
        $courts = Court::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $selectedCourtId = (int) ($filters['court_id'] ?? $courts->first()?->id);
        $selectedCourt = $courts->firstWhere('id', $selectedCourtId);
        $rentalItems = $this->activeRentalItems();
        $courtSlots = collect();
        $availableRanges = collect();
        $selectedRange = null;
        $availableStock = collect();

        $availability->expireUnpaidBookings($selectedCourtId ?: null, $date);
        $inventory->expireUnpaidOrders($date);

        if ($selectedCourt) {
            $courtSlots = $this->courtSlotGrid($selectedCourt, $date, $availability, $pricing);
            $availableRanges = $this->availableCourtRanges($selectedCourt, $date, $courtSlots, $pricing);

            $currentRange = $this->currentWalkInRange($selectedCourt, $date, $availability, $pricing);
            if ($currentRange) {
                $availableRanges->prepend($currentRange);
                $courtSlots = $courtSlots->map(function (array $slot) use ($currentRange): array {
                    if ($slot['start'] === $currentRange['block_start'] && $slot['end'] === $currentRange['end']) {
                        $slot['instant_range'] = $currentRange;
                    }

                    return $slot;
                });
            }

            $selectedRange = $availableRanges->firstWhere('value', $filters['range'] ?? null);

            if ($selectedRange) {
                $availableStock = $this->availableRentalStock(
                    $date,
                    $selectedRange['start'].':00',
                    $selectedRange['end'].':00',
                    $rentalItems,
                    $inventory,
                );
            }
        }

        return view('admin.court-orders.booking-create', [
            'date' => $date,
            'todayDate' => $today->toDateString(),
            'minimumDate' => $minimumDate->toDateString(),
            'maxDate' => $today->copy()->addDays(60)->toDateString(),
            'courts' => $courts,
            'selectedCourt' => $selectedCourt,
            'selectedCourtId' => $selectedCourtId,
            'courtSlots' => $courtSlots,
            'availableRanges' => $availableRanges,
            'selectedRange' => $selectedRange,
            'rangeWasRequested' => filled($filters['range'] ?? null),
            'rentalItems' => $rentalItems,
            'availableStock' => $availableStock,
            'requestKey' => $filters['manual_request_key'] ?? (string) Str::uuid(),
        ]);
    }

    public function storeBooking(
        Request $request,
        CourtBookingAvailabilityService $availability,
        CourtPricingService $pricing,
        CourtRentalInventoryService $inventory,
    ) {
        $this->ensureAdmin();

        $today = Carbon::today(CourtBooking::TIMEZONE);
        $minimumDate = $today->copy()->subDays(self::MAX_BACKDATE_DAYS);
        $requestedDate = $request->input('booking_date');
        $isBackdated = is_string($requestedDate)
            && preg_match('/\A\d{4}-\d{2}-\d{2}\z/', $requestedDate) === 1
            && $requestedDate < $today->toDateString();

        $rules = [
            'court_id' => ['required', 'integer', 'exists:courts,id'],
            'booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:'.$minimumDate->toDateString(), 'before_or_equal:'.$today->copy()->addDays(60)->toDateString()],
            'range' => ['required', 'regex:/^(?:now|\d{2}:\d{2})\|\d{2}:\d{2}$/'],
            'manual_request_key' => ['required', 'uuid'],
            'customer_name' => ['required', 'string', 'max:120'],
            'team_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[0-9+().\s-]+$/'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'payment_method' => ['required', Rule::in(['none', 'cash', 'bank_transfer', 'qris'])],
            'amount_received' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
            'backdate_reason' => [$isBackdated ? 'required' : 'nullable', 'string', 'max:500'],
            'rental_items' => ['nullable', 'array'],
            'rental_items.*' => ['required', 'integer', 'min:0', 'max:65535'],
        ];
        $validated = $request->validate($rules, [
            'booking_date.after_or_equal' => 'Tanggal booking lampau maksimal '.self::MAX_BACKDATE_DAYS.' hari dari hari ini.',
            'backdate_reason.required' => 'Alasan pencatatan tanggal lampau wajib diisi.',
        ]);

        $rentalSelections = $this->normalizeRentalSelections($validated['rental_items'] ?? []);
        $fingerprint = $this->fingerprint([
            'type' => 'walk_in_booking',
            'court_id' => (int) $validated['court_id'],
            'booking_date' => $validated['booking_date'],
            'range' => $validated['range'],
            'customer_name' => trim($validated['customer_name']),
            'team_name' => trim($validated['team_name']),
            'customer_phone' => trim($validated['customer_phone']),
            'customer_email' => $validated['customer_email'] ?? null,
            'payment_method' => $validated['payment_method'],
            'amount_received' => round((float) ($validated['amount_received'] ?? 0), 2),
            'payment_reference' => $validated['payment_reference'] ?? null,
            'admin_note' => $validated['admin_note'] ?? null,
            'backdate_reason' => $isBackdated ? trim($validated['backdate_reason']) : null,
            'rental_items' => $rentalSelections,
        ]);

        $existing = CourtBooking::query()->where('idempotency_key', $validated['manual_request_key'])->first();
        if ($existing) {
            $this->ensureSameFingerprint($existing, $fingerprint, 'manual_request_key');

            return redirect()->route('admin.court-bookings.index', ['date' => $existing->booking_date->toDateString()])
                ->with('success', 'Pesanan walk-in ini sudah tercatat sebelumnya. Tidak ada pesanan duplikat yang dibuat.');
        }

        try {
            $booking = DB::transaction(function () use ($validated, $rentalSelections, $availability, $pricing, $inventory, $fingerprint, $isBackdated) {
                $court = $availability->lockCourt((int) $validated['court_id']);
                if (!$court || !$court->is_active) {
                    throw ValidationException::withMessages(['court_id' => 'Lapangan ini sedang tidak menerima booking. Pilih lapangan aktif.']);
                }

                $existingBooking = CourtBooking::query()
                    ->where('idempotency_key', $validated['manual_request_key'])
                    ->lockForUpdate()
                    ->first();
                if ($existingBooking) {
                    $this->ensureSameFingerprint($existingBooking, $fingerprint, 'manual_request_key');

                    return $existingBooking;
                }

                $availability->expireUnpaidBookings($court->id, $validated['booking_date']);
                $slots = $this->courtSlotGrid($court, $validated['booking_date'], $availability, $pricing);
                $isInstantRange = str_starts_with($validated['range'], 'now|');
                $availableRange = $isInstantRange
                    ? $this->currentWalkInRange($court, $validated['booking_date'], $availability, $pricing)
                    : $this->availableCourtRanges($court, $validated['booking_date'], $slots, $pricing)->firstWhere('value', $validated['range']);
                if ($availableRange && $availableRange['value'] !== $validated['range']) {
                    $availableRange = null;
                }
                if (!$availableRange) {
                    throw ValidationException::withMessages(['range' => 'Slot baru saja terisi, sudah lewat, atau sisa waktunya kurang dari 30 menit. Muat ulang jadwal dan pilih waktu lain.']);
                }

                $rentalItems = $this->lockRentalItems($rentalSelections);
                $rentalTotal = $this->validateAndCalculateRentalTotal(
                    $validated['booking_date'],
                    $availableRange['start'].':00',
                    $availableRange['end'].':00',
                    $rentalSelections,
                    $rentalItems,
                    $inventory,
                );
                $amount = round((float) $availableRange['amount'] + $rentalTotal, 2);
                if ($amount > 9999999999.99) {
                    throw ValidationException::withMessages(['rental_items' => 'Total transaksi melebihi batas yang dapat disimpan. Kurangi jumlah perlengkapan.']);
                }

                $amountReceived = round((float) ($validated['amount_received'] ?? 0), 2);
                if ($amountReceived > 0 && $validated['payment_method'] === 'none') {
                    throw ValidationException::withMessages([
                        'payment_method' => 'Pilih metode pembayaran jika ada nominal yang diterima.',
                    ]);
                }
                if ($amountReceived > $amount) {
                    throw ValidationException::withMessages([
                        'amount_received' => 'Nominal diterima tidak boleh melebihi total tagihan Rp '.number_format($amount, 0, ',', '.').'.',
                    ]);
                }

                $now = now();
                $booking = CourtBooking::query()->create([
                    'public_token' => (string) Str::uuid(),
                    'idempotency_key' => $validated['manual_request_key'],
                    'request_fingerprint' => $fingerprint,
                    'court_id' => $court->id,
                    'booking_date' => $validated['booking_date'],
                    'time_start' => $availableRange['start'].':00',
                    'time_end' => $availableRange['end'].':00',
                    'duration_minutes' => $availableRange['duration'],
                    'amount' => $amount,
                    'customer_name' => trim($validated['customer_name']),
                    'team_name' => trim($validated['team_name']),
                    'customer_phone' => trim($validated['customer_phone']),
                    'customer_email' => $validated['customer_email'] ?? null,
                    'payment_method' => $amountReceived > 0 ? $validated['payment_method'] : 'none',
                    'source' => 'walk_in',
                    'created_by' => auth()->id(),
                    'payment_reference' => $amountReceived > 0 ? ($validated['payment_reference'] ?? null) : null,
                    'payment_instructions_snapshot' => $amountReceived > 0 ? $this->paymentReceiptNote($validated['payment_method']) : null,
                    'status' => CourtBooking::STATUS_CONFIRMED,
                    'verified_by' => auth()->id(),
                    'verified_at' => $now,
                    'confirmed_at' => $now,
                    'admin_note' => $validated['admin_note'] ?? null,
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

                if ($amountReceived > 0) {
                    $booking->payments()->create([
                        'amount' => $amountReceived,
                        'verified_amount' => $amountReceived,
                        'stage' => $amountReceived >= $amount
                            ? CourtBookingPayment::STAGE_FULL
                            : CourtBookingPayment::STAGE_DEPOSIT,
                        'payment_method' => $validated['payment_method'],
                        'payment_reference' => $validated['payment_reference'] ?? null,
                        'status' => CourtBookingPayment::STATUS_CONFIRMED,
                        'note' => ($availableRange['is_instant'] ?? false)
                            ? 'Booking mulai sekarang; biaya lapangan mengikuti tarif penuh satu slot '.$availableRange['slot_minutes'].' menit. '.($amountReceived >= $amount ? 'Pembayaran lunas diterima.' : 'DP diterima saat booking walk-in.')
                            : ($amountReceived >= $amount
                                ? 'Pembayaran lunas diterima saat booking walk-in.'
                                : 'DP diterima saat booking walk-in.'),
                        'recorded_by' => auth()->id(),
                        'verified_by' => auth()->id(),
                        'recorded_at' => $now,
                        'verified_at' => $now,
                    ]);
                }

                if ($isBackdated) {
                    CourtBookingBackdateAudit::query()->create([
                        'court_booking_id' => $booking->id,
                        'admin_id' => auth()->id(),
                        'admin_name_snapshot' => auth()->user()?->name,
                        'reason' => trim($validated['backdate_reason']),
                    ]);
                }

                return $booking;
            }, 3);
        } catch (QueryException $exception) {
            $booking = CourtBooking::query()->where('idempotency_key', $validated['manual_request_key'])->first();
            if (!$booking) {
                throw $exception;
            }
            $this->ensureSameFingerprint($booking, $fingerprint, 'manual_request_key');
        }

        $receivedAmount = (float) $booking->payments()
            ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
            ->sum('verified_amount');
        $balance = max(0, round((float) $booking->amount - $receivedAmount, 2));
        $successMessage = $receivedAmount <= 0
            ? 'Booking walk-in berhasil dikonfirmasi tanpa pembayaran awal. Sisa tagihan Rp '.number_format($balance, 0, ',', '.').' dapat dicatat kemudian.'
            : ($balance > 0
            ? 'Booking walk-in terkonfirmasi. DP Rp '.number_format($receivedAmount, 0, ',', '.').' tercatat; sisa tagihan Rp '.number_format($balance, 0, ',', '.').'.'
            : 'Booking walk-in berhasil dicatat sebagai lunas dan terkonfirmasi.');

        return redirect()->route('admin.court-bookings.index', ['date' => $booking->booking_date->toDateString()])
            ->with('success', $successMessage);
    }

    public function createRental(Request $request, CourtRentalInventoryService $inventory, CourtBookingAvailabilityService $bookingAvailability)
    {
        $this->ensureAdmin();

        $today = Carbon::today(CourtRentalOrder::TIMEZONE);
        $filters = $request->validate([
            'mode' => ['nullable', 'in:now,schedule'],
            'date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:'.$today->toDateString(), 'before_or_equal:'.$today->copy()->addDays(60)->toDateString()],
            'time_start' => ['nullable', 'date_format:H:i'],
            'duration_minutes' => ['nullable', 'integer', 'in:30,60,90,120,180,240'],
            'rental_items' => ['nullable', 'array'],
            'rental_items.*' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'manual_request_key' => ['nullable', 'uuid'],
        ]);

        $mode = $filters['mode'] ?? old('mode', 'now');
        $oldDate = old('rental_date');
        $maxDate = $today->copy()->addDays(60)->toDateString();
        $oldDateIsValid = is_string($oldDate)
            && preg_match('/^\d{4}-\d{2}-\d{2}$/', $oldDate)
            && checkdate((int) substr($oldDate, 5, 2), (int) substr($oldDate, 8, 2), (int) substr($oldDate, 0, 4))
            && $oldDate >= $today->toDateString()
            && $oldDate <= $maxDate;
        $date = $mode === 'now' ? $today->toDateString() : ($filters['date'] ?? ($oldDateIsValid ? $oldDate : $today->toDateString()));
        $durationMinutes = (int) ($filters['duration_minutes'] ?? old('duration_minutes', 60));
        $timeStart = $mode === 'now'
            ? Carbon::now(CourtRentalOrder::TIMEZONE)->format('H:i')
            : ($filters['time_start'] ?? old('time_start', ''));
        if ($timeStart !== '' && !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $timeStart)) {
            $timeStart = '';
        }
        $timeEnd = '';
        [$openingTime, $closingTime] = $this->operatingHours();
        $periodError = null;
        $periodSelected = $timeStart !== '';
        $startAt = null;
        $endAt = null;
        if ($periodSelected) {
            $startAt = Carbon::createFromFormat('Y-m-d H:i', $date.' '.$timeStart, CourtRentalOrder::TIMEZONE);
            $endAt = $startAt->copy()->addMinutes($durationMinutes);
            $timeEnd = $endAt->format('H:i');
        }

        if ($periodSelected && ($timeStart < $openingTime || $endAt->greaterThan(Carbon::parse($date.' '.$closingTime, CourtRentalOrder::TIMEZONE)))) {
            $periodError = "Waktu sewa harus berada di antara {$openingTime} dan {$closingTime}.";
            $periodSelected = false;
        } elseif ($periodSelected && $mode === 'schedule' && Carbon::parse($date.' '.$timeStart, CourtRentalOrder::TIMEZONE)->lessThanOrEqualTo(Carbon::now(CourtRentalOrder::TIMEZONE))) {
            $periodError = 'Waktu mulai sudah lewat. Pilih rentang waktu yang akan datang.';
            $periodSelected = false;
        }

        $bookingAvailability->expireUnpaidBookings(date: $date);
        $inventory->expireUnpaidOrders($date);
        $rentalItems = $this->activeRentalItems();
        $availableStock = $rentalItems->mapWithKeys(fn (CourtRentalItem $item) => [$item->id => null]);
        $selectedRentalQuantities = collect($filters['rental_items'] ?? [])
            ->mapWithKeys(fn ($quantity, $itemId) => [(int) $itemId => (int) $quantity]);

        if ($periodSelected) {
            $availableStock = $this->availableRentalStock(
                $date,
                $timeStart.':00',
                $timeEnd.':00',
                $rentalItems,
                $inventory,
            );
        }

        return view('admin.court-orders.rental-create', [
            'date' => $date,
            'todayDate' => $today->toDateString(),
            'maxDate' => $today->copy()->addDays(60)->toDateString(),
            'mode' => $mode,
            'durationMinutes' => $durationMinutes,
            'durationOptions' => [30 => '30 menit', 60 => '1 jam', 90 => '1 jam 30 menit', 120 => '2 jam', 180 => '3 jam', 240 => '4 jam'],
            'timeStart' => $timeStart,
            'timeEnd' => $timeEnd,
            'openingTime' => $openingTime,
            'closingTime' => $closingTime,
            'periodSelected' => $periodSelected,
            'periodError' => $periodError,
            'rentalItems' => $rentalItems,
            'selectedRentalQuantities' => $selectedRentalQuantities,
            'availableStock' => $availableStock,
            'requestKey' => $filters['manual_request_key'] ?? (string) Str::uuid(),
        ]);
    }

    public function storeRental(Request $request, CourtRentalInventoryService $inventory, CourtBookingAvailabilityService $bookingAvailability)
    {
        $this->ensureAdmin();

        $today = Carbon::today(CourtRentalOrder::TIMEZONE);
        $validated = $request->validate([
            'mode' => ['required', 'in:now,schedule'],
            'rental_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:'.$today->toDateString(), 'before_or_equal:'.$today->copy()->addDays(60)->toDateString()],
            'time_start' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['required', 'integer', 'in:30,60,90,120,180,240'],
            'manual_request_key' => ['required', 'uuid'],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[0-9+().\s-]+$/'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'payment_method' => ['required', Rule::in(['cash', 'bank_transfer', 'qris'])],
            'payment_received' => ['required', 'accepted'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
            'rental_items' => ['required', 'array'],
            'rental_items.*' => ['required', 'integer', 'min:0', 'max:65535'],
        ]);

        [$openingTime, $closingTime] = $this->operatingHours();
        $start = Carbon::createFromFormat('Y-m-d H:i', $validated['rental_date'].' '.$validated['time_start'], CourtRentalOrder::TIMEZONE);
        $now = Carbon::now(CourtRentalOrder::TIMEZONE);
        $scheduleError = null;
        if ($validated['mode'] === 'now') {
            if ($validated['rental_date'] !== $today->toDateString() || $start->lessThan($now->copy()->subMinutes(10)) || $start->greaterThan($now->copy()->addMinutes(2))) {
                $scheduleError = 'Jadwal Pakai sekarang sudah terlalu lama. Muat ulang halaman agar waktunya diperbarui.';
            }
        } elseif ($start->lessThanOrEqualTo($now)) {
            $scheduleError = 'Waktu mulai sudah lewat. Pilih rentang waktu yang akan datang.';
        }
        $endAt = $start->copy()->addMinutes((int) $validated['duration_minutes']);
        $timeEnd = $endAt->format('H:i');
        if ($validated['time_start'] < $openingTime || $endAt->greaterThan(Carbon::parse($validated['rental_date'].' '.$closingTime, CourtRentalOrder::TIMEZONE))) {
            $scheduleError ??= "Waktu sewa harus berada di antara {$openingTime} dan {$closingTime}.";
        }

        $rentalSelections = $this->normalizeRentalSelections($validated['rental_items']);
        if ($rentalSelections === []) {
            throw ValidationException::withMessages(['rental_items' => 'Pilih setidaknya satu perlengkapan dan jumlah sewanya.']);
        }

        $fingerprint = $this->fingerprint([
            'type' => 'walk_in_rental',
            'rental_date' => $validated['rental_date'],
            'mode' => $validated['mode'],
            'time_start' => $validated['time_start'],
            'duration_minutes' => (int) $validated['duration_minutes'],
            'customer_name' => trim($validated['customer_name']),
            'customer_phone' => trim($validated['customer_phone']),
            'customer_email' => $validated['customer_email'] ?? null,
            'payment_method' => $validated['payment_method'],
            'payment_reference' => $validated['payment_reference'] ?? null,
            'admin_note' => $validated['admin_note'] ?? null,
            'rental_items' => $rentalSelections,
        ]);

        $existing = CourtRentalOrder::query()->where('idempotency_key', $validated['manual_request_key'])->first();
        if ($existing) {
            $this->ensureSameFingerprint($existing, $fingerprint, 'manual_request_key');

            return redirect()->route('admin.court-rentals.index', ['date' => $existing->rental_date->toDateString()])
                ->with('success', 'Pesanan walk-in ini sudah tercatat sebelumnya. Tidak ada pesanan duplikat yang dibuat.');
        }

        if ($scheduleError) {
            throw ValidationException::withMessages(['time_start' => $scheduleError]);
        }

        try {
            $order = DB::transaction(function () use ($validated, $rentalSelections, $inventory, $bookingAvailability, $fingerprint, $timeEnd) {
                $items = $this->lockRentalItems($rentalSelections);
                $existingOrder = CourtRentalOrder::query()
                    ->where('idempotency_key', $validated['manual_request_key'])
                    ->lockForUpdate()
                    ->first();
                if ($existingOrder) {
                    $this->ensureSameFingerprint($existingOrder, $fingerprint, 'manual_request_key');

                    return $existingOrder;
                }

                $bookingAvailability->expireUnpaidBookings(date: $validated['rental_date']);
                $inventory->expireUnpaidOrders($validated['rental_date']);
                $amount = $this->validateAndCalculateRentalTotal(
                    $validated['rental_date'],
                    $validated['time_start'].':00',
                    $timeEnd.':00',
                    $rentalSelections,
                    $items,
                    $inventory,
                );

                $now = now();
                $order = CourtRentalOrder::query()->create([
                    'public_token' => (string) Str::uuid(),
                    'idempotency_key' => $validated['manual_request_key'],
                    'request_fingerprint' => $fingerprint,
                    'rental_date' => $validated['rental_date'],
                    'rental_mode' => $validated['mode'],
                    'time_start' => $validated['time_start'].':00',
                    'time_end' => $timeEnd.':00',
                    'duration_minutes' => (int) $validated['duration_minutes'],
                    'amount' => $amount,
                    'customer_name' => trim($validated['customer_name']),
                    'customer_phone' => trim($validated['customer_phone']),
                    'customer_email' => $validated['customer_email'] ?? null,
                    'payment_method' => $validated['payment_method'],
                    'source' => 'walk_in',
                    'created_by' => auth()->id(),
                    'payment_reference' => $validated['payment_reference'] ?? null,
                    'payment_instructions_snapshot' => $this->paymentReceiptNote($validated['payment_method']),
                    'status' => CourtRentalOrder::STATUS_CONFIRMED,
                    'verified_by' => auth()->id(),
                    'verified_at' => $now,
                    'confirmed_at' => $now,
                    'rental_picked_up_at' => $validated['mode'] === 'now' ? $now : null,
                    'admin_note' => $validated['admin_note'] ?? null,
                ]);

                foreach ($rentalSelections as $itemId => $quantity) {
                    $item = $items->get($itemId);
                    $lineTotal = round((float) $item->unit_price * $quantity, 2);
                    $order->lines()->create([
                        'court_rental_item_id' => $item->id,
                        'item_name_snapshot' => $item->name,
                        'quantity' => $quantity,
                        'unit_price' => $item->unit_price,
                        'line_total' => $lineTotal,
                    ]);
                }

                return $order;
            }, 3);
        } catch (QueryException $exception) {
            $order = CourtRentalOrder::query()->where('idempotency_key', $validated['manual_request_key'])->first();
            if (!$order) {
                throw $exception;
            }
            $this->ensureSameFingerprint($order, $fingerprint, 'manual_request_key');
        }

        return redirect()->route('admin.court-rentals.index', ['date' => $order->rental_date->toDateString()])
            ->with('success', 'Pesanan perlengkapan walk-in berhasil dicatat sebagai lunas dan terkonfirmasi.');
    }

    private function courtSlotGrid(
        Court $court,
        string $date,
        CourtBookingAvailabilityService $availability,
        CourtPricingService $pricing,
    ): Collection {
        $slotMinutes = 30;
        $opening = Carbon::parse($date.' '.$court->opening_time, CourtBooking::TIMEZONE);
        $closing = Carbon::parse($date.' '.$court->closing_time, CourtBooking::TIMEZONE);
        if ($closing->lessThanOrEqualTo($opening)) {
            $closing->addDay();
        }
        $slots = [];
        $cursor = $opening->copy();

        // Keep selectable start times on the selected booking date; an end at 00:00 is valid.
        while ($cursor->toDateString() === $date
            && $cursor->copy()->addMinutes($slotMinutes)->lessThanOrEqualTo($closing)) {
            $end = $cursor->copy()->addMinutes($slotMinutes);
            $startTime = $cursor->format('H:i:s');
            $endTime = $end->format('H:i:s');
            $slotAvailability = $availability->slotAvailability($court, $date, $startTime, $endTime);
            $slots[] = [
                'start' => $cursor->format('H:i'),
                'end' => $end->format('H:i'),
                'starts_at' => Carbon::parse($date.' '.$startTime, CourtBooking::TIMEZONE)->toIso8601String(),
                'status' => $slotAvailability['status'],
                'display_name' => $slotAvailability['display_name'],
                'match_details' => $slotAvailability['match_details'],
                'amount' => $pricing->amountForRange($court, $startTime, $slotMinutes, $date),
            ];
            $cursor = $end;
        }

        return collect($slots);
    }

    private function availableCourtRanges(Court $court, string $date, Collection $courtSlots, CourtPricingService $pricing): Collection
    {
        $slots = $courtSlots->values()->all();
        $slotMinutes = 30;
        $ranges = [];
        foreach ($slots as $startIndex => $slot) {
            if (!in_array($slot['status'], ['available', 'past_available'], true)) {
                continue;
            }

            for ($endIndex = $startIndex; $endIndex < count($slots) && in_array($slots[$endIndex]['status'], ['available', 'past_available'], true); $endIndex++) {
                $duration = ($endIndex - $startIndex + 1) * $slotMinutes;
                $start = $slot['start'];
                $end = $slots[$endIndex]['end'];
                $amount = $pricing->amountForRange($court, $start.':00', $duration, $date);
                $ranges[] = [
                    'value' => $start.'|'.$end,
                    'start' => $start,
                    'end' => $end,
                    'duration' => $duration,
                    'duration_label' => $this->durationLabel($duration),
                    'amount' => $amount,
                    'label' => $start.'–'.$end.' · '.$this->durationLabel($duration).' · Rp '.number_format($amount, 0, ',', '.'),
                ];
            }
        }

        return collect($ranges);
    }

    private function currentWalkInRange(
        Court $court,
        string $date,
        CourtBookingAvailabilityService $availability,
        CourtPricingService $pricing,
    ): ?array {
        $now = Carbon::now(CourtBooking::TIMEZONE);
        if ($date !== $now->toDateString()) {
            return null;
        }

        $slotMinutes = 30;
        $opening = Carbon::parse($date.' '.$court->opening_time, CourtBooking::TIMEZONE);
        $closing = Carbon::parse($date.' '.$court->closing_time, CourtBooking::TIMEZONE);
        if ($closing->lessThanOrEqualTo($opening)) {
            $closing->addDay();
        }
        if ($now->lessThan($opening) || $now->greaterThanOrEqualTo($closing)) {
            return null;
        }

        $elapsedSeconds = $now->getTimestamp() - $opening->getTimestamp();
        $slotIndex = intdiv(max(0, $elapsedSeconds), $slotMinutes * 60);
        $blockStart = $opening->copy()->addMinutes($slotIndex * $slotMinutes);
        $blockEnd = $blockStart->copy()->addMinutes($slotMinutes);
        if ($blockEnd->greaterThan($closing)) {
            return null;
        }

        // Store minute-precision times, rounding a request with seconds up to the next minute.
        $startsAt = $now->copy()->startOfMinute();
        if ($now->second > 0) {
            $startsAt->addMinute();
        }

        $lastStartAt = $blockEnd->copy()->subMinutes(30);
        $remainingSeconds = $blockEnd->getTimestamp() - $startsAt->getTimestamp();
        if ($startsAt->greaterThan($lastStartAt) || $remainingSeconds < 30 * 60) {
            return null;
        }

        $blockStartTime = $blockStart->format('H:i:s');
        $blockEndTime = $blockEnd->format('H:i:s');
        if ($availability->bookingConflict($court, $date, $blockStartTime, $blockEndTime)
            || $availability->matchConflict($court, $date, $blockStartTime, $blockEndTime)
            || $availability->memberScheduleConflict($court, $date, $blockStartTime, $blockEndTime)) {
            return null;
        }

        $duration = intdiv($remainingSeconds, 60);
        $start = $startsAt->format('H:i');
        $end = $blockEnd->format('H:i');
        $amount = $pricing->amountForRange($court, $blockStartTime, $slotMinutes, $date);

        return [
            'value' => 'now|'.$end,
            'start' => $start,
            'end' => $end,
            'block_start' => $blockStart->format('H:i'),
            'duration' => $duration,
            'duration_label' => $this->durationLabel($duration),
            'amount' => $amount,
            'is_instant' => true,
            'slot_minutes' => $slotMinutes,
            'starts_at' => $startsAt->toIso8601String(),
            'ends_at' => $blockEnd->toIso8601String(),
            'last_start_at' => $lastStartAt->toIso8601String(),
            'label' => 'Mulai sekarang–'.$end.' · '.$this->durationLabel($duration)
                .' (tarif penuh '.$this->durationLabel($slotMinutes).') · Rp '.number_format($amount, 0, ',', '.'),
        ];
    }

    private function activeRentalItems(): Collection
    {
        return CourtRentalItem::query()
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->where('unit_price', '>', 0)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    private function availableRentalStock(
        string $date,
        string $start,
        string $end,
        Collection $items,
        CourtRentalInventoryService $inventory,
    ): Collection {
        $reservations = $inventory->reservationsForInterval($date, $start, $end, $items->modelKeys())
            ->filter(fn ($reservation) => $inventory->reservationOverlapsInterval($date, $start, $end, $reservation))
            ->unique(fn ($reservation) => $reservation->reservation_source.':'.$reservation->reservation_record_id.':'.$reservation->item_id)
            ->groupBy('item_id')
            ->map(fn ($lines) => $lines->sum('quantity'));

        return $items->mapWithKeys(fn (CourtRentalItem $item) => [
            $item->id => max(0, (int) $item->stock_quantity - (int) ($reservations[$item->id] ?? 0)),
        ]);
    }

    private function validateAndCalculateRentalTotal(
        string $date,
        string $start,
        string $end,
        array $selections,
        Collection $items,
        CourtRentalInventoryService $inventory,
    ): float {
        if ($selections === []) {
            return 0.0;
        }

        if ($items->count() !== count($selections)) {
            throw ValidationException::withMessages(['rental_items' => 'Salah satu perlengkapan sudah tidak tersedia. Muat ulang daftar dan sesuaikan pesanan.']);
        }

        $inventory->expireUnpaidOrders($date);
        $reservations = $inventory->reservationsForInterval($date, $start, $end, array_keys($selections), lockForUpdate: true)
            ->filter(fn ($reservation) => $inventory->reservationOverlapsInterval($date, $start, $end, $reservation))
            ->unique(fn ($reservation) => $reservation->reservation_source.':'.$reservation->reservation_record_id.':'.$reservation->item_id)
            ->groupBy('item_id')
            ->map(fn ($lines) => $lines->sum('quantity'));

        $total = 0.0;
        foreach ($selections as $itemId => $quantity) {
            $item = $items->get($itemId);
            $available = max(0, (int) $item->stock_quantity - (int) ($reservations[$itemId] ?? 0));
            if (!$item->is_active || (float) $item->unit_price <= 0 || $quantity > $available) {
                throw ValidationException::withMessages([
                    'rental_items' => "Stok {$item->name} untuk waktu tersebut tidak mencukupi. Muat ulang stok dan sesuaikan jumlahnya.",
                ]);
            }

            $lineTotal = round((float) $item->unit_price * $quantity, 2);
            if ($lineTotal > 9999999999.99) {
                throw ValidationException::withMessages(['rental_items' => 'Total perlengkapan melebihi batas transaksi. Kurangi jumlah barang.']);
            }
            $total += $lineTotal;
        }

        return round($total, 2);
    }

    private function lockRentalItems(array $selections): Collection
    {
        if ($selections === []) {
            return collect();
        }

        return CourtRentalItem::query()
            ->whereIn('id', array_keys($selections))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
    }

    private function normalizeRentalSelections(array $selections): array
    {
        return collect($selections)
            ->mapWithKeys(fn ($quantity, $itemId) => [(int) $itemId => (int) $quantity])
            ->filter(fn ($quantity) => $quantity > 0)
            ->sortKeys()
            ->all();
    }

    private function operatingHours(): array
    {
        $courts = Court::query()->where('is_active', true);

        return [
            substr((string) ($courts->min('opening_time') ?: '08:00:00'), 0, 5),
            substr((string) ($courts->max('closing_time') ?: '23:00:00'), 0, 5),
        ];
    }

    private function durationLabel(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remaining = $minutes % 60;

        return $hours === 0 ? $remaining.' menit' : ($remaining === 0 ? $hours.' jam' : $hours.' jam '.$remaining.' menit');
    }

    private function paymentReceiptNote(string $method): string
    {
        return $method === 'cash'
            ? 'Pembayaran tunai diterima langsung oleh admin saat transaksi walk-in.'
            : 'Pembayaran diterima dan dicocokkan langsung oleh admin saat transaksi walk-in.';
    }

    private function fingerprint(array $payload): string
    {
        return hash_hmac(
            'sha256',
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            (string) config('app.key'),
        );
    }

    private function ensureSameFingerprint(CourtBooking|CourtRentalOrder $order, string $fingerprint, string $field): void
    {
        if (!$order->request_fingerprint || !hash_equals($order->request_fingerprint, $fingerprint)) {
            throw ValidationException::withMessages([
                $field => 'Kunci pesanan ini sudah digunakan untuk transaksi lain. Muat ulang form sebelum membuat pesanan baru.',
            ]);
        }
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
    }
}
