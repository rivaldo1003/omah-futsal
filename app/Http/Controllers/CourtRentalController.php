<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\CourtBooking;
use App\Models\CourtBookingSetting;
use App\Models\CourtRentalItem;
use App\Models\CourtRentalOrder;
use App\Services\CourtBookingAvailabilityService;
use App\Services\CourtRentalInventoryService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CourtRentalController extends Controller
{
    public function index(
        Request $request,
        CourtBookingAvailabilityService $bookingAvailability,
        CourtRentalInventoryService $inventory,
    ) {
        $today = Carbon::today(CourtRentalOrder::TIMEZONE);
        $maxDate = $today->copy()->addDays(60)->toDateString();
        $validated = $request->validate([
            'mode' => ['nullable', 'in:now,schedule'],
            'date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:'.$today->toDateString(), 'before_or_equal:'.$maxDate],
            'time_start' => ['nullable', 'date_format:H:i'],
            'duration_minutes' => ['nullable', 'integer', 'in:30,60,90,120,180,240'],
            'rental_items' => ['nullable', 'array'],
            'rental_items.*' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);
        $mode = $validated['mode'] ?? old('mode', 'now');
        $oldDate = old('rental_date');
        $oldDateIsValid = is_string($oldDate)
            && preg_match('/^\d{4}-\d{2}-\d{2}$/', $oldDate)
            && checkdate((int) substr($oldDate, 5, 2), (int) substr($oldDate, 8, 2), (int) substr($oldDate, 0, 4))
            && $oldDate >= $today->toDateString()
            && $oldDate <= $maxDate;
        $date = $mode === 'now' ? $today->toDateString() : ($validated['date'] ?? ($oldDateIsValid ? $oldDate : $today->toDateString()));
        $durationMinutes = (int) ($validated['duration_minutes'] ?? old('duration_minutes', 60));
        $timeStart = $mode === 'now'
            ? Carbon::now(CourtRentalOrder::TIMEZONE)->format('H:i')
            : ($validated['time_start'] ?? old('time_start', ''));
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
            $periodError = "Penyewaan hanya dapat dilakukan antara {$openingTime} dan {$closingTime}.";
            $periodSelected = false;
        } elseif ($periodSelected && $mode === 'schedule' && Carbon::parse($date.' '.$timeStart, CourtRentalOrder::TIMEZONE)->lessThanOrEqualTo(Carbon::now(CourtRentalOrder::TIMEZONE))) {
            $periodError = 'Waktu pengambilan sudah lewat. Pilih waktu yang akan datang.';
            $periodSelected = false;
        }

        $bookingAvailability->expireUnpaidBookings(date: $date);
        $inventory->expireUnpaidOrders($date);
        $items = CourtRentalItem::query()
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->where('unit_price', '>', 0)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $reservations = $inventory->reservationsForDate($date, $items->modelKeys());
        $availableStock = $items->mapWithKeys(function (CourtRentalItem $item) use ($reservations, $timeStart, $timeEnd, $periodSelected) {
            if (!$periodSelected) {
                return [$item->id => null];
            }

            $reserved = $reservations
                ->filter(fn ($reservation) => (int) $reservation->item_id === (int) $item->id
                    && $reservation->time_start < $timeEnd
                    && $reservation->time_end > $timeStart)
                ->sum('quantity');

            return [$item->id => max(0, (int) $item->stock_quantity - (int) $reserved)];
        });
        $settings = CourtBookingSetting::query()->first()
            ?? new CourtBookingSetting(['payment_hold_minutes' => 60]);
        $hasPaymentOption = ($settings->hasBankTransfer() || $settings->hasQris()) && filled($settings->booking_policy);
        $selectedRentalQuantities = collect($validated['rental_items'] ?? [])
            ->mapWithKeys(fn ($quantity, $itemId) => [(int) $itemId => (int) $quantity]);

        return view('rental.index', [
            'items' => $items,
            'selectedRentalQuantities' => $selectedRentalQuantities,
            'availableStock' => $availableStock,
            'date' => $date,
            'todayDate' => $today->toDateString(),
            'maxDate' => $maxDate,
            'mode' => $mode,
            'durationMinutes' => $durationMinutes,
            'durationOptions' => $this->durationOptions(),
            'timeStart' => $timeStart,
            'timeEnd' => $timeEnd,
            'openingTime' => $openingTime,
            'closingTime' => $closingTime,
            'periodSelected' => $periodSelected,
            'periodError' => $periodError,
            'settings' => $settings,
            'hasPaymentOption' => $hasPaymentOption,
            'requestKey' => (string) Str::uuid(),
        ]);
    }

    public function store(
        Request $request,
        CourtBookingAvailabilityService $bookingAvailability,
        CourtRentalInventoryService $inventory,
    ) {
        $today = Carbon::today(CourtRentalOrder::TIMEZONE);
        $validated = $request->validate([
            'mode' => ['required', 'in:now,schedule'],
            'rental_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:'.$today->toDateString(), 'before_or_equal:'.$today->copy()->addDays(60)->toDateString()],
            'time_start' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['required', 'integer', 'in:30,60,90,120,180,240'],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[0-9+().\s-]+$/'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'payment_method' => ['required', 'in:bank_transfer,qris'],
            'rental_request_key' => ['required', 'uuid'],
            'rental_items' => ['required', 'array'],
            'rental_items.*' => ['required', 'integer', 'min:0', 'max:65535'],
            'quoted_amount' => ['required', 'numeric', 'min:0'],
            'booking_policy_hash' => ['required', 'string', 'size:64'],
            'accept_booking_rules' => ['accepted'],
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
            $scheduleError = 'Waktu pengambilan sudah lewat. Pilih waktu yang akan datang.';
        }

        $endAt = $start->copy()->addMinutes((int) $validated['duration_minutes']);
        $timeEnd = $endAt->format('H:i');
        if ($validated['time_start'] < $openingTime || $endAt->greaterThan(Carbon::parse($validated['rental_date'].' '.$closingTime, CourtRentalOrder::TIMEZONE))) {
            $scheduleError ??= "Waktu sewa harus berada di antara {$openingTime} dan {$closingTime}.";
        }

        $rentalSelections = collect($validated['rental_items'])
            ->mapWithKeys(fn ($quantity, $itemId) => [(int) $itemId => (int) $quantity])
            ->filter(fn ($quantity) => $quantity > 0)
            ->sortKeys()
            ->all();
        if ($rentalSelections === []) {
            throw ValidationException::withMessages(['rental_items' => 'Pilih setidaknya satu perlengkapan dan jumlah sewanya.']);
        }

        $fingerprintPayload = json_encode([
            'rental_date' => $validated['rental_date'],
            'mode' => $validated['mode'],
            'time_start' => $validated['time_start'],
            'duration_minutes' => (int) $validated['duration_minutes'],
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'payment_method' => $validated['payment_method'],
            'rental_items' => $rentalSelections,
            'quoted_amount' => number_format((float) $validated['quoted_amount'], 2, '.', ''),
            'booking_policy_hash' => $validated['booking_policy_hash'],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $fingerprint = hash_hmac('sha256', $fingerprintPayload, (string) config('app.key'));

        $existing = CourtRentalOrder::query()->where('idempotency_key', $validated['rental_request_key'])->first();
        if ($existing) {
            $this->ensureSameRequest($existing, $fingerprint);

            return redirect()->route('rental.show', $existing->public_token)
                ->with('success', 'Pesanan sewa ini sudah pernah dibuat. Berikut status pesanannya.');
        }

        if ($scheduleError) {
            throw ValidationException::withMessages(['time_start' => $scheduleError]);
        }

        try {
            $order = DB::transaction(function () use ($validated, $rentalSelections, $inventory, $bookingAvailability, $fingerprint, $timeEnd) {
                $lockedItems = CourtRentalItem::query()
                    ->whereIn('id', array_keys($rentalSelections))
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');
                if ($lockedItems->count() !== count($rentalSelections)) {
                    throw ValidationException::withMessages(['rental_items' => 'Salah satu perlengkapan sudah tidak tersedia. Muat ulang halaman dan pilih kembali.']);
                }

                $existingOrder = CourtRentalOrder::query()
                    ->where('idempotency_key', $validated['rental_request_key'])
                    ->lockForUpdate()
                    ->first();
                if ($existingOrder) {
                    $this->ensureSameRequest($existingOrder, $fingerprint);

                    return $existingOrder;
                }

                $bookingAvailability->expireUnpaidBookings(date: $validated['rental_date']);
                $inventory->expireUnpaidOrders($validated['rental_date']);
                $currentSettings = CourtBookingSetting::query()->whereKey(1)->sharedLock()->first();
                if (
                    !$currentSettings
                    || !filled($currentSettings->booking_policy)
                    || !hash_equals(hash('sha256', $currentSettings->booking_policy), $validated['booking_policy_hash'])
                ) {
                    throw ValidationException::withMessages(['accept_booking_rules' => 'Kebijakan sewa baru saja berubah. Muat ulang halaman, periksa aturan, lalu kirim kembali.']);
                }
                if ($validated['payment_method'] === 'bank_transfer' && !$currentSettings->hasBankTransfer()) {
                    throw ValidationException::withMessages(['payment_method' => 'Informasi rekening berubah. Muat ulang halaman dan pilih metode yang tersedia.']);
                }
                if ($validated['payment_method'] === 'qris' && !$currentSettings->hasQris()) {
                    throw ValidationException::withMessages(['payment_method' => 'Informasi QRIS berubah. Muat ulang halaman dan pilih metode yang tersedia.']);
                }

                $reservations = $inventory->reservationsForDate(
                    $validated['rental_date'], array_keys($rentalSelections), lockForUpdate: true,
                )->filter(fn ($reservation) => $reservation->time_start < $timeEnd
                    && $reservation->time_end > $validated['time_start'])
                    ->groupBy('item_id')
                    ->map(fn ($lines) => $lines->sum('quantity'));

                $amount = 0.0;
                foreach ($rentalSelections as $itemId => $quantity) {
                    $item = $lockedItems->get($itemId);
                    $available = max(0, (int) $item->stock_quantity - (int) ($reservations[$itemId] ?? 0));
                    if (!$item->is_active || (float) $item->unit_price <= 0 || $quantity > $available) {
                        throw ValidationException::withMessages([
                            'rental_items' => "Stok {$item->name} untuk waktu sewa tersebut tidak mencukupi. Perbarui ketersediaan lalu sesuaikan jumlahnya.",
                        ]);
                    }

                    $amount += round((float) $item->unit_price * $quantity, 2);
                }
                if ($amount > 9999999999.99 || number_format($amount, 2, '.', '') !== number_format((float) $validated['quoted_amount'], 2, '.', '')) {
                    throw ValidationException::withMessages(['quoted_amount' => 'Harga perlengkapan berubah. Muat ulang katalog dan periksa totalnya sebelum memesan.']);
                }

                $order = CourtRentalOrder::query()->create([
                    'public_token' => (string) Str::uuid(),
                    'idempotency_key' => $validated['rental_request_key'],
                    'request_fingerprint' => $fingerprint,
                    'rental_date' => $validated['rental_date'],
                    'rental_mode' => $validated['mode'],
                    'time_start' => $validated['time_start'],
                    'time_end' => $timeEnd,
                    'duration_minutes' => (int) $validated['duration_minutes'],
                    'amount' => $amount,
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'customer_email' => $validated['customer_email'] ?? null,
                    'payment_method' => $validated['payment_method'],
                    'payment_bank_name' => $validated['payment_method'] === 'bank_transfer' ? $currentSettings->bank_name : null,
                    'payment_bank_account_number' => $validated['payment_method'] === 'bank_transfer' ? $currentSettings->bank_account_number : null,
                    'payment_bank_account_name' => $validated['payment_method'] === 'bank_transfer' ? $currentSettings->bank_account_name : null,
                    'payment_qris_image_path' => $validated['payment_method'] === 'qris' ? $currentSettings->qris_image_path : null,
                    'payment_instructions_snapshot' => $currentSettings->payment_instructions,
                    'booking_policy_snapshot' => $currentSettings->booking_policy,
                    'status' => CourtRentalOrder::STATUS_AWAITING_PAYMENT,
                    'expires_at' => now()->addMinutes(max(10, (int) $currentSettings->payment_hold_minutes)),
                ]);

                foreach ($rentalSelections as $itemId => $quantity) {
                    $item = $lockedItems->get($itemId);
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
            $existing = CourtRentalOrder::query()->where('idempotency_key', $validated['rental_request_key'])->first();
            if (!$existing) {
                throw $exception;
            }
            $this->ensureSameRequest($existing, $fingerprint);
            $order = $existing;
        }

        return redirect()->route('rental.show', $order->public_token)
            ->with('success', 'Pesanan perlengkapan dibuat. Selesaikan pembayaran dan simpan tautan ini untuk melihat statusnya.');
    }

    public function show(string $token, CourtRentalInventoryService $inventory)
    {
        $order = CourtRentalOrder::query()->with('lines')->where('public_token', $token)->firstOrFail();
        if (
            $order->status === CourtRentalOrder::STATUS_AWAITING_PAYMENT
            && $order->expires_at
            && $order->expires_at->isPast()
        ) {
            $inventory->expireUnpaidOrders($order->rental_date->toDateString());
            $order->refresh()->load('lines');
        }
        $scheduleHasPassed = $order->status === CourtRentalOrder::STATUS_CONFIRMED
            && Carbon::parse($order->rental_date->toDateString().' '.$order->time_end, CourtRentalOrder::TIMEZONE)
                ->lessThanOrEqualTo(Carbon::now(CourtRentalOrder::TIMEZONE));

        return view('rental.show', compact('order', 'scheduleHasPassed'));
    }

    public function status(string $token, CourtRentalInventoryService $inventory)
    {
        $order = CourtRentalOrder::query()->where('public_token', $token)->firstOrFail();
        if (
            $order->status === CourtRentalOrder::STATUS_AWAITING_PAYMENT
            && $order->expires_at
            && $order->expires_at->isPast()
        ) {
            $inventory->expireUnpaidOrders($order->rental_date->toDateString());
            $order->refresh();
        }

        return response()->json([
            'token' => $order->public_token,
            'status' => $order->status,
            'statusLabel' => $order->status_label,
            'expiresAt' => $order->expires_at?->toIso8601String(),
            'rentalDate' => $order->rental_date->toDateString(),
            'timeStart' => substr($order->time_start, 0, 5),
            'timeEnd' => substr($order->time_end, 0, 5),
            'durationMinutes' => (int) ($order->duration_minutes ?: Carbon::parse($order->rental_date->toDateString().' '.$order->time_start, CourtRentalOrder::TIMEZONE)->diffInMinutes(Carbon::parse($order->rental_date->toDateString().' '.$order->time_end, CourtRentalOrder::TIMEZONE))),
            'amount' => (float) $order->amount,
        ])->header('Cache-Control', 'private, no-store, max-age=0');
    }

    public function submitPaymentProof(Request $request, string $token)
    {
        $validated = $request->validate([
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);
        $order = CourtRentalOrder::query()->where('public_token', $token)->firstOrFail();
        if (!$this->canSubmitProof($order)) {
            return back()->withErrors(['payment_proof' => 'Waktu pembayaran berakhir. Stok telah dilepas; buat pesanan baru untuk melanjutkan.']);
        }

        $path = $request->file('payment_proof')->store('court-rental-proofs', 'local');
        if (!$path) {
            return back()->withErrors(['payment_proof' => 'Bukti pembayaran gagal disimpan. Coba unggah kembali.']);
        }

        try {
            $updated = DB::transaction(function () use ($order, $validated, $path, $request) {
                $lockedOrder = CourtRentalOrder::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();
                if (!$this->canSubmitProof($lockedOrder)) {
                    return false;
                }
                $lockedOrder->update([
                    'payment_reference' => $validated['payment_reference'] ?? null,
                    'payment_proof_path' => $path,
                    'payment_proof_original_name' => $request->file('payment_proof')->getClientOriginalName(),
                    'status' => CourtRentalOrder::STATUS_PENDING_REVIEW,
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
            app(CourtRentalInventoryService::class)->expireUnpaidOrders($order->rental_date->toDateString());

            return back()->withErrors(['payment_proof' => 'Waktu pembayaran berakhir. Stok telah dilepas; buat pesanan baru untuk melanjutkan.']);
        }

        return redirect()->route('rental.show', $order->public_token)
            ->with('success', 'Bukti pembayaran terkirim dan sedang diperiksa admin.');
    }

    private function operatingHours(): array
    {
        $courts = Court::query()->where('is_active', true);

        return [
            substr((string) ($courts->min('opening_time') ?: '08:00:00'), 0, 5),
            substr((string) ($courts->max('closing_time') ?: '23:00:00'), 0, 5),
        ];
    }

    private function durationOptions(): array
    {
        return [30 => '30 menit', 60 => '1 jam', 90 => '1 jam 30 menit', 120 => '2 jam', 180 => '3 jam', 240 => '4 jam'];
    }

    private function canSubmitProof(CourtRentalOrder $order): bool
    {
        return $order->status === CourtRentalOrder::STATUS_AWAITING_PAYMENT
            && $order->expires_at !== null
            && !$order->expires_at->isPast();
    }

    private function ensureSameRequest(CourtRentalOrder $order, string $fingerprint): void
    {
        if (!$order->request_fingerprint || !hash_equals($order->request_fingerprint, $fingerprint)) {
            throw ValidationException::withMessages([
                'rental_request_key' => 'Kunci pesanan sudah digunakan untuk data berbeda. Muat ulang katalog sebelum memesan lagi.',
            ]);
        }
    }
}
