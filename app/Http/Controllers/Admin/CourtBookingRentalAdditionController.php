<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourtBooking;
use App\Models\CourtBookingRentalAddition;
use App\Models\CourtBookingSetting;
use App\Models\CourtRentalItem;
use App\Services\CourtRentalInventoryService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CourtBookingRentalAdditionController extends Controller
{
    public function create(CourtBooking $booking, CourtRentalInventoryService $inventory)
    {
        $this->ensureAdmin();
        $this->assertCanAddItems($booking);
        $inventory->expireUnpaidOrders($booking->booking_date->toDateString());

        $items = CourtRentalItem::query()
            ->where('is_active', true)
            ->where('unit_price', '>', 0)
            ->orderBy('sort_order')->orderBy('name')->get();
        $reservations = $inventory->reservationsForDate($booking->booking_date->toDateString(), $items->modelKeys());
        $availableStock = $items->mapWithKeys(function (CourtRentalItem $item) use ($reservations, $booking) {
            $reserved = $reservations->where('item_id', $item->id)
                ->filter(fn ($reservation) => $reservation->time_start < $booking->time_end && $reservation->time_end > $booking->time_start)
                ->sum('quantity');

            return [$item->id => max(0, (int) $item->stock_quantity - (int) $reserved)];
        });
        $settings = CourtBookingSetting::query()->first() ?? new CourtBookingSetting();

        return view('admin.court-bookings.rental-addition-create', [
            'booking' => $booking->loadMissing(['court', 'rentalLines']),
            'items' => $items,
            'availableStock' => $availableStock,
            'settings' => $settings,
            'requestKey' => (string) Str::uuid(),
            'holdMinutes' => max(10, (int) ($settings->payment_hold_minutes ?: 60)),
        ]);
    }

    public function store(Request $request, CourtBooking $booking, CourtRentalInventoryService $inventory)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'request_key' => ['required', 'uuid'],
            'payment_method' => ['required', Rule::in(['cash', 'bank_transfer', 'qris'])],
            'payment_received' => ['required_if:payment_method,cash', 'accepted'],
            'rental_items' => ['required', 'array', 'min:1'],
            'rental_items.*' => ['required', 'integer', 'min:0', 'max:65535'],
        ]);
        $selections = collect($validated['rental_items'])
            ->mapWithKeys(fn ($quantity, $itemId) => [(int) $itemId => (int) $quantity])
            ->filter(fn ($quantity) => $quantity > 0)->sortKeys()->all();
        if ($selections === []) {
            throw ValidationException::withMessages(['rental_items' => 'Pilih minimal satu perlengkapan dan jumlahnya.']);
        }

        $fingerprint = hash_hmac('sha256', json_encode([
            'booking_id' => (int) $booking->id,
            'payment_method' => $validated['payment_method'],
            'payment_received' => $validated['payment_method'] === 'cash',
            'items' => $selections,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR), (string) config('app.key'));
        $existing = CourtBookingRentalAddition::query()->where('idempotency_key', $validated['request_key'])->first();
        if ($existing) {
            $this->assertSameFingerprint($existing, $fingerprint);

            return redirect()->route('admin.court-bookings.index', ['date' => $booking->booking_date->toDateString()])
                ->with('success', 'Tambahan perlengkapan ini sudah tercatat. Tidak ada transaksi duplikat yang dibuat.');
        }

        $settings = CourtBookingSetting::query()->first();
        if ($validated['payment_method'] === 'bank_transfer' && (!$settings || !$settings->hasBankTransfer())) {
            throw ValidationException::withMessages(['payment_method' => 'Rekening transfer belum diatur. Pilih metode lain atau lengkapi pengaturan pembayaran.']);
        }
        if ($validated['payment_method'] === 'qris' && (!$settings || !$settings->hasQris())) {
            throw ValidationException::withMessages(['payment_method' => 'QRIS belum diatur. Pilih metode lain atau lengkapi pengaturan pembayaran.']);
        }

        try {
            $addition = DB::transaction(function () use ($booking, $validated, $selections, $settings, $fingerprint, $inventory) {
                $items = CourtRentalItem::query()->whereIn('id', array_keys($selections))
                    ->orderBy('id')->lockForUpdate()->get()->keyBy('id');
                $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
                $this->assertCanAddItems($lockedBooking);
                $settings = CourtBookingSetting::query()->whereKey(1)->sharedLock()->first();
                if ($validated['payment_method'] === 'bank_transfer' && (!$settings || !$settings->hasBankTransfer())) {
                    throw ValidationException::withMessages(['payment_method' => 'Informasi rekening berubah. Muat ulang dan pilih metode yang masih tersedia.']);
                }
                if ($validated['payment_method'] === 'qris' && (!$settings || !$settings->hasQris())) {
                    throw ValidationException::withMessages(['payment_method' => 'Informasi QRIS berubah. Muat ulang dan pilih metode yang masih tersedia.']);
                }

                $duplicate = CourtBookingRentalAddition::query()
                    ->where('idempotency_key', $validated['request_key'])->lockForUpdate()->first();
                if ($duplicate) {
                    $this->assertSameFingerprint($duplicate, $fingerprint);
                    return $duplicate;
                }
                if ($items->count() !== count($selections)) {
                    throw ValidationException::withMessages(['rental_items' => 'Ada perlengkapan yang sudah dihapus. Muat ulang halaman dan pilih ulang.']);
                }

                $date = $lockedBooking->booking_date->toDateString();
                $inventory->expireUnpaidOrders($date);
                $reservations = $inventory->reservationsForDate($date, array_keys($selections), lockForUpdate: true)
                    ->filter(fn ($row) => $row->time_start < $lockedBooking->time_end && $row->time_end > $lockedBooking->time_start)
                    ->groupBy('item_id')->map(fn ($rows) => $rows->sum('quantity'));
                $amount = 0.0;
                foreach ($selections as $itemId => $quantity) {
                    /** @var CourtRentalItem $item */
                    $item = $items->get($itemId);
                    $available = max(0, (int) $item->stock_quantity - (int) ($reservations[$itemId] ?? 0));
                    if (!$item->is_active || (float) $item->unit_price <= 0 || $quantity > $available) {
                        throw ValidationException::withMessages([
                            'rental_items' => "Stok {$item->name} tidak mencukupi untuk jadwal booking ini. Muat ulang halaman dan sesuaikan jumlah.",
                        ]);
                    }
                    $amount += round((float) $item->unit_price * $quantity, 2);
                }
                if ($amount <= 0 || $amount > 9999999999.99) {
                    throw ValidationException::withMessages(['rental_items' => 'Total tambahan tidak valid. Periksa jumlah perlengkapan.']);
                }

                $now = now();
                $isCash = $validated['payment_method'] === 'cash';
                $bookingStart = Carbon::parse($date.' '.$lockedBooking->time_start, CourtBooking::TIMEZONE);
                $holdExpiresAt = $now->copy()->addMinutes(max(10, (int) ($settings?->payment_hold_minutes ?: 60)));
                $addition = $lockedBooking->rentalAdditions()->create([
                    'idempotency_key' => $validated['request_key'],
                    'request_fingerprint' => $fingerprint,
                    'amount' => $amount,
                    'payment_method' => $validated['payment_method'],
                    'payment_bank_name' => $validated['payment_method'] === 'bank_transfer' ? $settings?->bank_name : null,
                    'payment_bank_account_number' => $validated['payment_method'] === 'bank_transfer' ? $settings?->bank_account_number : null,
                    'payment_bank_account_name' => $validated['payment_method'] === 'bank_transfer' ? $settings?->bank_account_name : null,
                    'payment_qris_image_path' => $validated['payment_method'] === 'qris' ? $settings?->qris_image_path : null,
                    'payment_instructions_snapshot' => $settings?->payment_instructions,
                    'status' => $isCash ? CourtBookingRentalAddition::STATUS_CONFIRMED : CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT,
                    'expires_at' => $isCash ? null : ($holdExpiresAt->lessThan($bookingStart) ? $holdExpiresAt : $bookingStart),
                    'created_by' => auth()->id(),
                    'verified_by' => $isCash ? auth()->id() : null,
                    'verified_at' => $isCash ? $now : null,
                    'confirmed_at' => $isCash ? $now : null,
                ]);

                foreach ($selections as $itemId => $quantity) {
                    $item = $items->get($itemId);
                    $lineTotal = round((float) $item->unit_price * $quantity, 2);
                    $addition->lines()->create([
                        'court_rental_item_id' => $item->id,
                        'item_name_snapshot' => $item->name,
                        'quantity' => $quantity,
                        'unit_price' => $item->unit_price,
                        'line_total' => $lineTotal,
                    ]);
                }

                return $addition;
            }, 3);
        } catch (QueryException $exception) {
            $addition = CourtBookingRentalAddition::query()->where('idempotency_key', $validated['request_key'])->first();
            if (!$addition) throw $exception;
            $this->assertSameFingerprint($addition, $fingerprint);
        }

        $message = $addition->status === CourtBookingRentalAddition::STATUS_CONFIRMED
            ? 'Tambahan perlengkapan tercatat dan pembayaran tunai dikonfirmasi.'
            : 'Tambahan perlengkapan tercatat. Pelanggan dapat melanjutkan pembayaran melalui tautan booking.';

        return redirect()->route('admin.court-bookings.index', ['date' => $booking->booking_date->toDateString()])->with('success', $message);
    }

    public function confirm(CourtBooking $booking, CourtBookingRentalAddition $addition)
    {
        $this->ensureAdmin();
        abort_unless((int) $addition->court_booking_id === (int) $booking->id, 404);
        DB::transaction(function () use ($booking, $addition) {
            $itemIds = DB::table('court_booking_rental_addition_lines')
                ->where('court_booking_rental_addition_id', $addition->id)->whereNotNull('court_rental_item_id')
                ->pluck('court_rental_item_id')->map(fn ($id) => (int) $id)->all();
            CourtRentalItem::query()->whereIn('id', $itemIds)->orderBy('id')->lockForUpdate()->get();
            $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            $lockedAddition = CourtBookingRentalAddition::query()->whereKey($addition->id)->lockForUpdate()->firstOrFail();
            if ($lockedBooking->status !== CourtBooking::STATUS_CONFIRMED || $lockedAddition->status !== CourtBookingRentalAddition::STATUS_PENDING_REVIEW) {
                throw ValidationException::withMessages(['addition' => 'Hanya tambahan pada booking terkonfirmasi yang menunggu pemeriksaan yang dapat dikonfirmasi.']);
            }
            $lockedAddition->update([
                'status' => CourtBookingRentalAddition::STATUS_CONFIRMED,
                'verified_by' => auth()->id(), 'verified_at' => now(), 'confirmed_at' => now(), 'admin_note' => null,
            ]);
        });

        return back()->with('success', 'Pembayaran tambahan perlengkapan berhasil dikonfirmasi.');
    }

    public function reject(Request $request, CourtBooking $booking, CourtBookingRentalAddition $addition)
    {
        $this->ensureAdmin();
        abort_unless((int) $addition->court_booking_id === (int) $booking->id, 404);
        $validated = $request->validate(['admin_note' => ['nullable', 'string', 'max:1000']]);
        DB::transaction(function () use ($booking, $addition, $validated) {
            CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            $locked = CourtBookingRentalAddition::query()->whereKey($addition->id)->lockForUpdate()->firstOrFail();
            if ($locked->status !== CourtBookingRentalAddition::STATUS_PENDING_REVIEW) {
                throw ValidationException::withMessages(['addition' => 'Hanya bukti tambahan yang menunggu pemeriksaan yang dapat ditolak.']);
            }
            $locked->update([
                'status' => CourtBookingRentalAddition::STATUS_REJECTED,
                'verified_by' => auth()->id(), 'verified_at' => now(), 'expires_at' => null,
                'admin_note' => $validated['admin_note'] ?? null,
            ]);
        });

        return back()->with('success', 'Bukti pembayaran tambahan ditolak dan stok tambahan dilepas.');
    }

    public function cancel(Request $request, CourtBooking $booking, CourtBookingRentalAddition $addition)
    {
        $this->ensureAdmin();
        abort_unless((int) $addition->court_booking_id === (int) $booking->id, 404);
        $validated = $request->validate(['admin_note' => ['nullable', 'string', 'max:1000']]);
        DB::transaction(function () use ($booking, $addition, $validated) {
            CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            $locked = CourtBookingRentalAddition::query()->whereKey($addition->id)->lockForUpdate()->firstOrFail();
            if (!in_array($locked->status, [
                CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT,
                CourtBookingRentalAddition::STATUS_PENDING_REVIEW,
            ], true)) {
                throw ValidationException::withMessages(['addition' => 'Hanya tambahan yang belum dikonfirmasi pembayarannya yang dapat dibatalkan.']);
            }
            $locked->update([
                'status' => CourtBookingRentalAddition::STATUS_CANCELLED,
                'expires_at' => null,
                'admin_note' => $validated['admin_note'] ?? 'Tambahan dibatalkan oleh admin.',
            ]);
        });

        return back()->with('success', 'Tambahan dibatalkan dan stoknya kembali tersedia.');
    }

    public function proofPreview(CourtBooking $booking, CourtBookingRentalAddition $addition)
    {
        $this->ensureAdmin();
        abort_unless((int) $addition->court_booking_id === (int) $booking->id, 404);
        $path = $this->proofPath($addition);
        $mime = Storage::disk('local')->mimeType($path) ?: 'application/octet-stream';
        abort_unless(in_array($mime, ['application/pdf', 'image/jpeg', 'image/png'], true), 415);
        $filename = $this->proofFileName($addition);
        $fallback = trim(preg_replace('/[^A-Za-z0-9._ -]/', '_', $filename), ' ._');

        return response()->file(Storage::disk('local')->path($path), [
            'Content-Type' => $mime,
            'Content-Disposition' => HeaderUtils::makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $filename, $fallback ?: 'bukti-tambahan'),
            'Cache-Control' => 'private, no-store, max-age=0', 'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function downloadProof(CourtBooking $booking, CourtBookingRentalAddition $addition)
    {
        $this->ensureAdmin();
        abort_unless((int) $addition->court_booking_id === (int) $booking->id, 404);

        return Storage::disk('local')->download($this->proofPath($addition), $this->proofFileName($addition));
    }

    private function proofPath(CourtBookingRentalAddition $addition): string
    {
        $path = $addition->payment_proof_path;
        abort_unless($path && Storage::disk('local')->exists($path), 404);
        return $path;
    }

    private function proofFileName(CourtBookingRentalAddition $addition): string
    {
        $name = (string) ($addition->payment_proof_original_name ?: 'bukti-pembayaran-tambahan');
        return preg_replace('/[\r\n]+/', '', basename(str_replace('\\', '/', $name))) ?: 'bukti-pembayaran-tambahan';
    }

    private function assertCanAddItems(CourtBooking $booking): void
    {
        if ($booking->status !== CourtBooking::STATUS_CONFIRMED) {
            throw ValidationException::withMessages(['booking' => 'Tambahan hanya dapat dibuat pada booking yang sudah terkonfirmasi.']);
        }
        if ($booking->rental_picked_up_at || $booking->rental_returned_at) {
            throw ValidationException::withMessages(['booking' => 'Tambahan tidak dapat dibuat setelah perlengkapan booking diambil.']);
        }
        $start = Carbon::parse($booking->booking_date->toDateString().' '.$booking->time_start, CourtBooking::TIMEZONE);
        if (!$start->isAfter(now(CourtBooking::TIMEZONE))) {
            throw ValidationException::withMessages(['booking' => 'Batas penambahan perlengkapan sudah lewat karena jadwal booking telah dimulai.']);
        }
    }

    private function assertSameFingerprint(CourtBookingRentalAddition $addition, string $fingerprint): void
    {
        if (!$addition->request_fingerprint || !hash_equals($addition->request_fingerprint, $fingerprint)) {
            throw ValidationException::withMessages(['request_key' => 'Kunci transaksi sudah digunakan untuk detail tambahan yang berbeda. Muat ulang lalu coba lagi.']);
        }
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
    }
}
