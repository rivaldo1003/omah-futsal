<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourtRentalItem;
use App\Models\CourtRentalOrder;
use App\Services\CourtRentalInventoryService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CourtRentalOrderController extends Controller
{
    public function index(Request $request, CourtRentalInventoryService $inventory)
    {
        $this->ensureAdmin();
        $inventory->expireUnpaidOrders();

        $filters = $request->validate([
            'status' => ['nullable', Rule::in([
                CourtRentalOrder::STATUS_AWAITING_PAYMENT,
                CourtRentalOrder::STATUS_PENDING_REVIEW,
                CourtRentalOrder::STATUS_CONFIRMED,
                CourtRentalOrder::STATUS_REJECTED,
                CourtRentalOrder::STATUS_CANCELLED,
                CourtRentalOrder::STATUS_EXPIRED,
            ])],
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $orders = CourtRentalOrder::query()
            ->with(['lines', 'verifiedBy', 'cancelledBy', 'createdBy'])
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['date'] ?? null, fn ($query, $date) => $query->whereDate('rental_date', $date))
            ->orderByRaw("CASE WHEN status = 'pending_review' THEN 0 WHEN status = 'awaiting_payment' THEN 1 ELSE 2 END")
            ->orderByDesc('rental_date')
            ->orderBy('time_start')
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'pending_review' => CourtRentalOrder::where('status', CourtRentalOrder::STATUS_PENDING_REVIEW)->count(),
            'awaiting_payment' => CourtRentalOrder::where('status', CourtRentalOrder::STATUS_AWAITING_PAYMENT)->count(),
            'confirmed' => CourtRentalOrder::where('status', CourtRentalOrder::STATUS_CONFIRMED)->count(),
        ];

        return view('admin.court-rentals.index', compact('orders', 'filters', 'counts'));
    }

    public function proof(CourtRentalOrder $order)
    {
        $this->ensureAdmin();
        $path = $this->existingPaymentProofPath($order);
        $mimeType = Storage::disk('local')->mimeType($path) ?: 'application/octet-stream';
        abort_unless(in_array($mimeType, ['application/pdf', 'image/jpeg', 'image/png'], true), 415);

        return view('admin.court-rentals.proof', [
            'order' => $order->loadMissing('lines'),
            'mimeType' => $mimeType,
            'fileName' => $this->paymentProofFileName($order),
        ]);
    }

    public function proofPreview(CourtRentalOrder $order)
    {
        $this->ensureAdmin();
        $path = $this->existingPaymentProofPath($order);
        $mimeType = Storage::disk('local')->mimeType($path) ?: 'application/octet-stream';
        abort_unless(in_array($mimeType, ['application/pdf', 'image/jpeg', 'image/png'], true), 415);
        $fileName = $this->paymentProofFileName($order);
        $fallback = trim(preg_replace('/[^A-Za-z0-9._ -]/', '_', $fileName), ' ._');

        return response()->file(Storage::disk('local')->path($path), [
            'Content-Type' => $mimeType,
            'Content-Disposition' => HeaderUtils::makeDisposition(
                ResponseHeaderBag::DISPOSITION_INLINE,
                $fileName,
                $fallback !== '' ? $fallback : 'bukti-pembayaran',
            ),
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function downloadProof(CourtRentalOrder $order)
    {
        $this->ensureAdmin();
        $path = $this->existingPaymentProofPath($order);

        return Storage::disk('local')->download($path, $this->paymentProofFileName($order));
    }

    public function confirm(CourtRentalOrder $order)
    {
        $this->ensureAdmin();

        DB::transaction(function () use ($order) {
            $itemIds = DB::table('court_rental_order_lines')
                ->where('court_rental_order_id', $order->id)
                ->whereNotNull('court_rental_item_id')
                ->pluck('court_rental_item_id')
                ->map(fn ($id) => (int) $id)
                ->all();
            CourtRentalItem::query()->whereIn('id', $itemIds)->orderBy('id')->lockForUpdate()->get();
            $locked = CourtRentalOrder::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($locked->status !== CourtRentalOrder::STATUS_PENDING_REVIEW) {
                throw ValidationException::withMessages(['order' => 'Hanya pesanan yang menunggu verifikasi yang dapat dikonfirmasi.']);
            }

            $locked->update([
                'status' => CourtRentalOrder::STATUS_CONFIRMED,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'confirmed_at' => now(),
                'admin_note' => null,
            ]);
        });

        return back()->with('success', 'Pembayaran cocok dan pesanan perlengkapan telah dikonfirmasi.');
    }

    public function reject(Request $request, CourtRentalOrder $order)
    {
        $this->ensureAdmin();
        $validated = $request->validate(['admin_note' => ['nullable', 'string', 'max:1000']]);

        DB::transaction(function () use ($order, $validated) {
            $locked = CourtRentalOrder::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($locked->status !== CourtRentalOrder::STATUS_PENDING_REVIEW) {
                throw ValidationException::withMessages(['order' => 'Hanya pesanan yang menunggu verifikasi yang dapat ditolak.']);
            }

            $locked->update([
                'status' => CourtRentalOrder::STATUS_REJECTED,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'expires_at' => null,
                'admin_note' => $validated['admin_note'] ?? null,
            ]);
        });

        return back()->with('success', 'Pesanan ditolak dan stok kembali tersedia.');
    }

    public function cancel(Request $request, CourtRentalOrder $order)
    {
        $this->ensureAdmin();
        $validated = $request->validate(['admin_note' => ['required', 'string', 'max:1000']]);

        DB::transaction(function () use ($order, $validated) {
            $locked = CourtRentalOrder::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($locked->rental_picked_up_at) {
                throw ValidationException::withMessages(['order' => 'Pesanan tidak dapat dibatalkan setelah perlengkapan diambil. Catat pengembalian barang terlebih dahulu.']);
            }
            if (!in_array($locked->status, [
                CourtRentalOrder::STATUS_AWAITING_PAYMENT,
                CourtRentalOrder::STATUS_PENDING_REVIEW,
                CourtRentalOrder::STATUS_CONFIRMED,
            ], true)) {
                throw ValidationException::withMessages(['order' => 'Status pesanan ini tidak dapat dibatalkan.']);
            }

            $locked->update([
                'status' => CourtRentalOrder::STATUS_CANCELLED,
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'expires_at' => null,
                'admin_note' => $validated['admin_note'],
            ]);
        });

        return back()->with('success', 'Pesanan dibatalkan. Pastikan pelanggan menerima informasi pengembalian dana bila diperlukan.');
    }

    public function markPickedUp(CourtRentalOrder $order)
    {
        $this->ensureAdmin();

        DB::transaction(function () use ($order) {
            $itemIds = DB::table('court_rental_order_lines')
                ->where('court_rental_order_id', $order->id)
                ->whereNotNull('court_rental_item_id')
                ->pluck('court_rental_item_id')
                ->map(fn ($id) => (int) $id)
                ->all();
            CourtRentalItem::query()->whereIn('id', $itemIds)->orderBy('id')->lockForUpdate()->get();
            $locked = CourtRentalOrder::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($locked->status !== CourtRentalOrder::STATUS_CONFIRMED || !$locked->lines()->exists()) {
                throw ValidationException::withMessages(['order' => 'Pengambilan hanya dapat dicatat untuk pesanan perlengkapan terkonfirmasi.']);
            }
            if ($locked->rental_returned_at) {
                throw ValidationException::withMessages(['order' => 'Perlengkapan pada pesanan ini sudah tercatat dikembalikan.']);
            }
            if ($locked->rental_picked_up_at) {
                return;
            }

            $today = CarbonImmutable::today(CourtRentalOrder::TIMEZONE)->toDateString();
            if ($locked->rental_date->toDateString() > $today) {
                throw ValidationException::withMessages(['order' => 'Pengambilan belum dapat dicatat sebelum tanggal sewa.']);
            }

            $locked->update(['rental_picked_up_at' => now()]);
        });

        return back()->with('success', 'Waktu pengambilan perlengkapan berhasil dicatat.');
    }

    public function markReturned(CourtRentalOrder $order)
    {
        $this->ensureAdmin();

        DB::transaction(function () use ($order) {
            $locked = CourtRentalOrder::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($locked->status !== CourtRentalOrder::STATUS_CONFIRMED || !$locked->rental_picked_up_at) {
                throw ValidationException::withMessages(['order' => 'Catat pengambilan dan konfirmasi pesanan sebelum mencatat pengembalian.']);
            }
            if ($locked->rental_returned_at) {
                throw ValidationException::withMessages(['order' => 'Pengembalian perlengkapan sudah tercatat.']);
            }

            $locked->update(['rental_returned_at' => now()]);
        });

        return back()->with('success', 'Waktu pengembalian perlengkapan berhasil dicatat dan stok diperbarui.');
    }

    private function existingPaymentProofPath(CourtRentalOrder $order): string
    {
        $path = $order->payment_proof_path;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return $path;
    }

    private function paymentProofFileName(CourtRentalOrder $order): string
    {
        $originalName = (string) ($order->payment_proof_original_name ?: 'bukti-pembayaran');
        $fileName = basename(str_replace('\\', '/', $originalName));

        return preg_replace('/[\r\n]+/', '', $fileName) ?: 'bukti-pembayaran';
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
    }
}
