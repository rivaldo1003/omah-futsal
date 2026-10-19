<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\CourtBooking;
use App\Models\CourtBookingPayment;
use App\Models\CourtBookingRentalAddition;
use App\Models\CourtBookingSetting;
use App\Models\CourtDailyRevenue;
use App\Models\Game;
use App\Models\CourtRentalItem;
use App\Models\CourtRentalOrder;
use App\Services\CourtBookingAvailabilityService;
use App\Services\CourtRentalInventoryService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CourtBookingController extends Controller
{
    public function index(Request $request, CourtBookingAvailabilityService $availability, CourtRentalInventoryService $rentalInventory)
    {
        $this->ensureAdmin();
        $availability->expireUnpaidBookings();
        $rentalInventory->expireUnpaidOrders();

        $filters = $request->validate([
            'status' => ['nullable', Rule::in([
                CourtBooking::STATUS_AWAITING_PAYMENT,
                CourtBooking::STATUS_PENDING_REVIEW,
                CourtBooking::STATUS_CONFIRMED,
                CourtBooking::STATUS_REJECTED,
                CourtBooking::STATUS_CANCELLED,
                CourtBooking::STATUS_EXPIRED,
            ])],
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $bookings = CourtBooking::query()
            ->with(['court', 'verifiedBy', 'cancelledBy', 'createdBy', 'rentalLines', 'rentalAdditions.lines', 'payments'])
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where(function ($bookingStatus) use ($status) {
                    $bookingStatus->where('status', $status);
                    if ($status === CourtBookingRentalAddition::STATUS_PENDING_REVIEW) {
                        $bookingStatus->orWhere(function ($additionBooking) {
                            $additionBooking->where('status', CourtBooking::STATUS_CONFIRMED)
                                ->whereHas('rentalAdditions', fn ($additions) => $additions->where('status', CourtBookingRentalAddition::STATUS_PENDING_REVIEW));
                        });
                    } elseif ($status === CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT) {
                        $bookingStatus->orWhere(function ($additionBooking) {
                            $additionBooking->where('status', CourtBooking::STATUS_CONFIRMED)
                                ->whereHas('rentalAdditions', fn ($additions) => $additions
                                    ->where('status', CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT)
                                    ->where('expires_at', '>', now()));
                        });
                    }
                });
            })
            ->when($filters['date'] ?? null, fn ($query, $date) => $query->whereDate('booking_date', $date))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $todayReportDate = CarbonImmutable::today(CourtBooking::TIMEZONE)->toDateString();
        $todayConfirmedBookings = CourtBooking::query()
            ->whereDate('booking_date', $todayReportDate)
            ->where('status', CourtBooking::STATUS_CONFIRMED);
        $todayConfirmedCount = (clone $todayConfirmedBookings)->count();
        $todayConfirmedMinutes = (int) (clone $todayConfirmedBookings)->sum('duration_minutes');
        $todayReceivedPayments = (float) CourtBookingPayment::query()
            ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
            ->whereHas('booking', fn ($query) => $query->whereDate('booking_date', $todayReportDate))
            ->sum('verified_amount');

        $counts = [
            'pending_review' => CourtBooking::where('status', CourtBooking::STATUS_PENDING_REVIEW)->count()
                + CourtBookingRentalAddition::where('status', CourtBookingRentalAddition::STATUS_PENDING_REVIEW)
                    ->whereHas('booking', fn ($query) => $query->where('status', CourtBooking::STATUS_CONFIRMED))->count(),
            'awaiting_payment' => CourtBooking::where('status', CourtBooking::STATUS_AWAITING_PAYMENT)->count()
                + CourtBookingRentalAddition::where('status', CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT)
                    ->where('expires_at', '>', now())
                    ->whereHas('booking', fn ($query) => $query->where('status', CourtBooking::STATUS_CONFIRMED))->count(),
            'confirmed' => CourtBooking::where('status', CourtBooking::STATUS_CONFIRMED)->count(),
        ];

        $bookingIncome = DB::table('court_booking_payments as payment')
            ->join('court_bookings as booking', 'booking.id', '=', 'payment.court_booking_id')
            ->where('payment.status', CourtBookingPayment::STATUS_CONFIRMED)
            ->sum('payment.verified_amount');
        $standaloneRentalIncome = CourtRentalOrder::query()
            ->where('status', CourtRentalOrder::STATUS_CONFIRMED)
            ->sum('amount');
        $bookingAdditionIncome = CourtBookingRentalAddition::query()
            ->join('court_bookings as booking', 'booking.id', '=', 'court_booking_rental_additions.court_booking_id')
            ->where('court_booking_rental_additions.status', CourtBookingRentalAddition::STATUS_CONFIRMED)
            ->where('booking.status', CourtBooking::STATUS_CONFIRMED)
            ->sum('court_booking_rental_additions.amount');
        $allTimeParkingIncome = (float) CourtDailyRevenue::query()->sum('parking_income');
        $allTimeShopIncome = (float) CourtDailyRevenue::query()->sum('shop_income');
        $totalIncome = (float) $bookingIncome + (float) $standaloneRentalIncome + (float) $bookingAdditionIncome + $allTimeParkingIncome + $allTimeShopIncome;
        $bookingRentalIncome = DB::table('court_booking_rental_lines as line')
            ->join('court_bookings as booking', 'booking.id', '=', 'line.court_booking_id')
            ->where('booking.status', CourtBooking::STATUS_CONFIRMED)
            ->sum('line.line_total');
        $standaloneRentalLineIncome = DB::table('court_rental_order_lines as line')
            ->join('court_rental_orders as rental_order', 'rental_order.id', '=', 'line.court_rental_order_id')
            ->where('rental_order.status', CourtRentalOrder::STATUS_CONFIRMED)
            ->sum('line.line_total');
        $additionRentalLineIncome = DB::table('court_booking_rental_addition_lines as line')
            ->join('court_booking_rental_additions as addition', 'addition.id', '=', 'line.court_booking_rental_addition_id')
            ->join('court_bookings as booking', 'booking.id', '=', 'addition.court_booking_id')
            ->where('addition.status', CourtBookingRentalAddition::STATUS_CONFIRMED)
            ->where('booking.status', CourtBooking::STATUS_CONFIRMED)
            ->sum('line.line_total');
        $rentalIncome = (float) $bookingRentalIncome + (float) $standaloneRentalLineIncome + (float) $additionRentalLineIncome;
        $bookingRentalIncomeByItem = DB::table('court_booking_rental_lines as line')
            ->join('court_bookings as booking', 'booking.id', '=', 'line.court_booking_id')
            ->where('booking.status', CourtBooking::STATUS_CONFIRMED)
            ->groupBy('line.item_name_snapshot')
            ->orderByRaw('SUM(line.line_total) DESC')
            ->get([
                'line.item_name_snapshot as name',
                DB::raw('SUM(line.quantity) as quantity'),
                DB::raw('SUM(line.line_total) as income'),
            ]);
        $standaloneRentalIncomeByItem = DB::table('court_rental_order_lines as line')
            ->join('court_rental_orders as rental_order', 'rental_order.id', '=', 'line.court_rental_order_id')
            ->where('rental_order.status', CourtRentalOrder::STATUS_CONFIRMED)
            ->groupBy('line.item_name_snapshot')
            ->get([
                'line.item_name_snapshot as name',
                DB::raw('SUM(line.quantity) as quantity'),
                DB::raw('SUM(line.line_total) as income'),
            ]);
        $additionRentalIncomeByItem = DB::table('court_booking_rental_addition_lines as line')
            ->join('court_booking_rental_additions as addition', 'addition.id', '=', 'line.court_booking_rental_addition_id')
            ->join('court_bookings as booking', 'booking.id', '=', 'addition.court_booking_id')
            ->where('addition.status', CourtBookingRentalAddition::STATUS_CONFIRMED)
            ->where('booking.status', CourtBooking::STATUS_CONFIRMED)
            ->groupBy('line.item_name_snapshot')
            ->get([
                'line.item_name_snapshot as name',
                DB::raw('SUM(line.quantity) as quantity'),
                DB::raw('SUM(line.line_total) as income'),
            ]);
        $rentalIncomeByItem = collect($bookingRentalIncomeByItem)
            ->concat($standaloneRentalIncomeByItem)
            ->concat($additionRentalIncomeByItem)
            ->groupBy('name')
            ->map(fn ($rows, $name) => (object) [
                'name' => $name,
                'quantity' => $rows->sum('quantity'),
                'income' => $rows->sum('income'),
            ])
            ->sortByDesc('income')
            ->values();
        // Booking payments can cover both court time and rental items, so keep this
        // figure intact instead of allocating a partial payment to a product category.
        $courtIncome = (float) $bookingIncome;

        $chartEnd = CarbonImmutable::now(CourtBooking::TIMEZONE)->endOfMonth();
        $chartStart = $chartEnd->startOfMonth()->subMonths(11);
        $dailyBookingStats = DB::table('court_booking_payments as payment')
            ->join('court_bookings as booking', 'booking.id', '=', 'payment.court_booking_id')
            ->select('booking.booking_date')
            ->selectRaw('COUNT(DISTINCT booking.id) as booking_count, SUM(payment.verified_amount) as booking_value')
            ->where('payment.status', CourtBookingPayment::STATUS_CONFIRMED)
            ->whereBetween('booking.booking_date', [$chartStart->toDateString(), $chartEnd->toDateString()])
            ->groupBy('booking.booking_date')
            ->get()
            ->concat(DB::table('court_booking_rental_additions as addition')
                ->join('court_bookings as booking', 'booking.id', '=', 'addition.court_booking_id')
                ->select('booking.booking_date')
                ->selectRaw('COUNT(*) as booking_count, SUM(addition.amount) as booking_value')
                ->where('addition.status', CourtBookingRentalAddition::STATUS_CONFIRMED)
                ->where('booking.status', CourtBooking::STATUS_CONFIRMED)
                ->whereBetween('booking.booking_date', [$chartStart->toDateString(), $chartEnd->toDateString()])
                ->groupBy('booking.booking_date')
                ->get())
            ->concat(DB::table('court_rental_orders')
                ->select('rental_date as booking_date')
                ->selectRaw('COUNT(*) as booking_count, SUM(amount) as booking_value')
                ->where('status', CourtRentalOrder::STATUS_CONFIRMED)
                ->whereBetween('rental_date', [$chartStart->toDateString(), $chartEnd->toDateString()])
                ->groupBy('rental_date')
                ->get());

        $monthlyTotals = [];
        foreach ($dailyBookingStats as $dailyStat) {
            $monthKey = substr((string) $dailyStat->booking_date, 0, 7);
            $monthlyTotals[$monthKey] ??= ['booking_count' => 0, 'booking_value' => 0];
            $monthlyTotals[$monthKey]['booking_count'] += (int) $dailyStat->booking_count;
            $monthlyTotals[$monthKey]['booking_value'] += (float) $dailyStat->booking_value;
        }

        $monthNames = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
        ];
        $monthlyBookingChart = collect(range(0, 11))
            ->map(function (int $offset) use ($chartStart, $monthNames, $monthlyTotals) {
                $month = $chartStart->addMonths($offset);
                $monthKey = $month->format('Y-m');
                $totals = $monthlyTotals[$monthKey] ?? ['booking_count' => 0, 'booking_value' => 0];

                return [
                    'key' => $monthKey,
                    'label' => $monthNames[(int) $month->format('n') - 1],
                    'year' => $month->format('Y'),
                    'booking_count' => $totals['booking_count'],
                    'booking_value' => $totals['booking_value'],
                ];
            });

        $maximumMonthlyBookings = (int) $monthlyBookingChart->max('booking_count');
        $busiestMonths = $maximumMonthlyBookings > 0
            ? $monthlyBookingChart
                ->where('booking_count', $maximumMonthlyBookings)
                ->pluck('label')
                ->implode(', ')
            : null;

        return view('admin.court-bookings.index', compact(
            'bookings',
            'counts',
            'filters',
            'totalIncome',
            'courtIncome',
            'rentalIncome',
            'rentalIncomeByItem',
            'monthlyBookingChart',
            'maximumMonthlyBookings',
            'busiestMonths',
            'chartStart',
            'chartEnd',
            'todayReportDate',
            'todayConfirmedCount',
            'todayConfirmedMinutes',
            'todayReceivedPayments',
        ));
    }

    public function dailyReport(Request $request, CourtBookingAvailabilityService $availability, CourtRentalInventoryService $rentalInventory)
    {
        $this->ensureAdmin();
        $availability->expireUnpaidBookings();
        $rentalInventory->expireUnpaidOrders();

        $filters = $request->validate([
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);
        $reportDate = $filters['date'] ?? CarbonImmutable::today(CourtBooking::TIMEZONE)->toDateString();

        $dailyBookings = CourtBooking::query()
            ->with(['court', 'payments'])
            ->whereDate('booking_date', $reportDate)
            ->orderBy('time_start')
            ->orderBy('id')
            ->get();
        $dailyConfirmedBookings = $dailyBookings->where('status', CourtBooking::STATUS_CONFIRMED);
        $dailyPendingBookings = $dailyBookings->whereIn('status', [
            CourtBooking::STATUS_AWAITING_PAYMENT,
            CourtBooking::STATUS_PENDING_REVIEW,
        ]);
        $dailyBookedMinutes = (int) $dailyConfirmedBookings->sum('duration_minutes');
        $dailyPendingMinutes = (int) $dailyPendingBookings->sum('duration_minutes');
        $dailyRevenue = CourtDailyRevenue::query()
            ->with('updatedBy')
            ->where('operating_date', $reportDate)
            ->first();
        $dailyParkingIncome = (float) ($dailyRevenue?->parking_income ?? 0);
        $dailyShopIncome = (float) ($dailyRevenue?->shop_income ?? 0);
        $dailyBookingPayments = (float) $dailyBookings->sum(fn ($booking) => $booking->payments
            ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
            ->sum(fn ($payment) => $payment->recognized_amount));
        $dailyRentalAdditionIncome = (float) CourtBookingRentalAddition::query()
            ->join('court_bookings as booking', 'booking.id', '=', 'court_booking_rental_additions.court_booking_id')
            ->whereDate('booking.booking_date', $reportDate)
            ->where('booking.status', CourtBooking::STATUS_CONFIRMED)
            ->where('court_booking_rental_additions.status', CourtBookingRentalAddition::STATUS_CONFIRMED)
            ->sum('court_booking_rental_additions.amount');
        $dailyStandaloneRentalIncome = (float) CourtRentalOrder::query()
            ->whereDate('rental_date', $reportDate)
            ->where('status', CourtRentalOrder::STATUS_CONFIRMED)
            ->sum('amount');
        $dailyTotalIncome = $dailyBookingPayments + $dailyParkingIncome + $dailyShopIncome + $dailyRentalAdditionIncome + $dailyStandaloneRentalIncome;
        $dailyOutstandingBalance = (float) $dailyConfirmedBookings->sum(fn ($booking) => max(
            0,
            (float) $booking->amount - (float) $booking->payments
                ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
                ->sum(fn ($payment) => $payment->recognized_amount),
        ));

        return view('admin.court-bookings.daily-report', compact(
            'reportDate',
            'dailyBookings',
            'dailyRevenue',
            'dailyBookedMinutes',
            'dailyPendingMinutes',
            'dailyParkingIncome',
            'dailyShopIncome',
            'dailyBookingPayments',
            'dailyTotalIncome',
            'dailyOutstandingBalance',
        ));
    }

    public function settings()
    {
        $this->ensureAdmin();
        $settings = CourtBookingSetting::query()->firstOrCreate(
            ['id' => 1],
            ['payment_hold_minutes' => 60],
        );
        $courts = Court::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.court-bookings.settings', compact('settings', 'courts'));
    }

    public function storeCourt(Request $request)
    {
        $this->ensureAdmin();
        $validated = $this->validateCourt($request);
        Court::query()->create($validated);

        return back()->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function updateCourt(Request $request, Court $court)
    {
        $this->ensureAdmin();
        $validated = $this->validateCourt($request, $court);

        DB::transaction(function () use ($court, $validated) {
            $lockedCourt = Court::query()->whereKey($court->id)->lockForUpdate()->firstOrFail();
            $scheduleChanged = $lockedCourt->name !== $validated['name']
                || $lockedCourt->opening_time_formatted !== $validated['opening_time']
                || $lockedCourt->closing_time_formatted !== $validated['closing_time']
                || (int) $lockedCourt->slot_duration_minutes !== (int) $validated['slot_duration_minutes']
                || (bool) $lockedCourt->is_active !== (bool) $validated['is_active'];

            if ($scheduleChanged && $this->hasFutureBookings($lockedCourt)) {
                throw ValidationException::withMessages(['court' => 'Jadwal lapangan tidak dapat diubah saat masih ada booking aktif mendatang. Selesaikan atau batalkan booking terlebih dahulu.']);
            }

            if ($lockedCourt->name !== $validated['name']) {
                $hasFutureMatches = Game::query()
                    ->publiclyVisible()
                    ->whereRaw('LOWER(TRIM(venue)) = ?', [mb_strtolower(trim($lockedCourt->name))])
                    ->whereDate('match_date', '>=', now(CourtBooking::TIMEZONE)->toDateString())
                    ->where('status', '!=', 'postponed')
                    ->exists();

                if ($hasFutureMatches) {
                    throw ValidationException::withMessages(['name' => 'Nama lapangan masih dipakai oleh jadwal pertandingan mendatang. Perbarui venue pertandingan terlebih dahulu.']);
                }
            }

            $lockedCourt->update($validated);
        });

        return back()->with('success', 'Pengaturan lapangan berhasil diperbarui.');
    }

    public function updatePaymentSettings(Request $request)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:100'],
            'bank_account_name' => ['nullable', 'string', 'max:120'],
            'payment_instructions' => ['nullable', 'string', 'max:3000'],
            'booking_policy' => ['required', 'string', 'max:3000'],
            'payment_hold_minutes' => ['required', 'integer', 'min:10', 'max:240'],
            'qris_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_qris_image' => ['nullable', 'boolean'],
        ]);

        $settings = CourtBookingSetting::query()->firstOrCreate(
            ['id' => 1],
            ['payment_hold_minutes' => 60],
        );

        if ($request->boolean('remove_qris_image') && $settings->qris_image_path) {
            $settings->qris_image_path = null;
        }

        if ($request->hasFile('qris_image')) {
            $settings->qris_image_path = $request->file('qris_image')->store('booking-payment', 'public');
        }

        $settings->fill([
            'bank_name' => $validated['bank_name'] ?? null,
            'bank_account_number' => $validated['bank_account_number'] ?? null,
            'bank_account_name' => $validated['bank_account_name'] ?? null,
            'payment_instructions' => $validated['payment_instructions'] ?? null,
            'booking_policy' => $validated['booking_policy'],
            'payment_hold_minutes' => $validated['payment_hold_minutes'],
            'updated_by' => auth()->id(),
        ])->save();

        return back()->with('success', 'Informasi pembayaran berhasil disimpan.');
    }

    public function proof(CourtBooking $booking)
    {
        $this->ensureAdmin();
        $path = $this->existingPaymentProofPath($booking);
        $mimeType = Storage::disk('local')->mimeType($path) ?: 'application/octet-stream';
        abort_unless(in_array($mimeType, ['application/pdf', 'image/jpeg', 'image/png'], true), 415);

        return view('admin.court-bookings.proof', [
            'booking' => $booking->loadMissing('court'),
            'mimeType' => $mimeType,
            'fileName' => $this->paymentProofFileName($booking),
        ]);
    }

    public function proofPreview(CourtBooking $booking)
    {
        $this->ensureAdmin();
        $path = $this->existingPaymentProofPath($booking);
        $mimeType = Storage::disk('local')->mimeType($path) ?: 'application/octet-stream';
        abort_unless(in_array($mimeType, ['application/pdf', 'image/jpeg', 'image/png'], true), 415);
        $fileName = $this->paymentProofFileName($booking);
        $fileNameFallback = trim(preg_replace('/[^A-Za-z0-9._ -]/', '_', $fileName), ' ._');

        return response()->file(Storage::disk('local')->path($path), [
            'Content-Type' => $mimeType,
            'Content-Disposition' => HeaderUtils::makeDisposition(
                ResponseHeaderBag::DISPOSITION_INLINE,
                $fileName,
                $fileNameFallback !== '' ? $fileNameFallback : 'bukti-pembayaran',
            ),
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function downloadProof(CourtBooking $booking)
    {
        $this->ensureAdmin();
        $path = $this->existingPaymentProofPath($booking);

        return Storage::disk('local')->download($path, $this->paymentProofFileName($booking));
    }

    private function existingPaymentProofPath(CourtBooking $booking): string
    {
        $path = $booking->payment_proof_path;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return $path;
    }

    private function paymentProofFileName(CourtBooking $booking): string
    {
        $originalName = (string) ($booking->payment_proof_original_name ?: 'bukti-pembayaran');
        $fileName = basename(str_replace('\\', '/', $originalName));
        $fileName = preg_replace('/[\r\n]+/', '', $fileName) ?: 'bukti-pembayaran';

        return $fileName;
    }

    public function updateTeamName(Request $request, CourtBooking $booking)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'team_name' => ['required', 'string', 'max:120'],
        ]);

        DB::transaction(function () use ($booking, $validated) {
            $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($lockedBooking->status !== CourtBooking::STATUS_CONFIRMED) {
                throw ValidationException::withMessages(['booking' => 'Nama tim hanya dapat diperbarui untuk booking terkonfirmasi.']);
            }

            $lockedBooking->update(['team_name' => trim($validated['team_name'])]);
        });

        return back()->with('success', 'Nama tim booking berhasil disimpan.');
    }

    public function updateDailyRevenue(Request $request)
    {
        $this->ensureAdmin();

        $amountInputs = [];
        foreach (['parking_income', 'shop_income'] as $field) {
            $value = $request->input($field);
            if (is_string($value)) {
                $value = preg_replace('/^Rp\s*/i', '', trim($value));
                $amountInputs[$field] = str_replace('.', '', $value);
            }
        }
        if ($amountInputs !== []) {
            $request->merge($amountInputs);
        }

        $validated = $request->validate([
            'operating_date' => ['required', 'date_format:Y-m-d'],
            'parking_income' => ['required', 'integer', 'min:0', 'max:9999999999'],
            'shop_income' => ['required', 'integer', 'min:0', 'max:9999999999'],
        ]);

        // Laravel stores timestamps as UTC; the view converts them to WIB.
        $now = now();
        DB::table('court_daily_revenues')->upsert([[
            'operating_date' => $validated['operating_date'],
            'parking_income' => (int) $validated['parking_income'],
            'shop_income' => (int) $validated['shop_income'],
            'updated_by' => auth()->id(),
            'created_at' => $now,
            'updated_at' => $now,
        ]], ['operating_date'], ['parking_income', 'shop_income', 'updated_by', 'updated_at']);

        return redirect()->route('admin.court-bookings.reports.daily', [
            'date' => $validated['operating_date'],
        ])->with('success', 'Pemasukan parkir dan toko untuk tanggal tersebut berhasil disimpan.');
    }

    public function recordPayment(Request $request, CourtBooking $booking)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0', 'max:9999999999.99'],
            'payment_method' => ['required', Rule::in(['cash', 'bank_transfer', 'qris'])],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($booking, $validated) {
            $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($lockedBooking->status !== CourtBooking::STATUS_CONFIRMED) {
                throw ValidationException::withMessages(['booking' => 'Pembayaran lanjutan hanya dapat dicatat untuk booking terkonfirmasi.']);
            }

            $paidAmount = (float) $lockedBooking->payments()
                ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
                ->sum('verified_amount');
            $balance = max(0, round((float) $lockedBooking->amount - $paidAmount, 2));
            $paymentAmount = round((float) $validated['amount'], 2);

            if ($balance <= 0) {
                throw ValidationException::withMessages(['amount' => 'Booking ini sudah lunas.']);
            }
            if ($paymentAmount > $balance) {
                throw ValidationException::withMessages(['amount' => 'Nominal tidak boleh melebihi sisa pembayaran Rp '.number_format($balance, 0, ',', '.').'.']);
            }

            $now = now();
            $lockedBooking->payments()->create([
                'amount' => $paymentAmount,
                'verified_amount' => $paymentAmount,
                'stage' => $paymentAmount >= $balance
                    ? CourtBookingPayment::STAGE_SETTLEMENT
                    : CourtBookingPayment::STAGE_DEPOSIT,
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'] ?? null,
                'status' => CourtBookingPayment::STATUS_CONFIRMED,
                'note' => $validated['note'] ?? null,
                'recorded_by' => auth()->id(),
                'verified_by' => auth()->id(),
                'recorded_at' => $now,
                'verified_at' => $now,
            ]);
        });

        return back()->with('success', 'Pembayaran booking berhasil dicatat.');
    }

    public function confirm(Request $request, CourtBooking $booking, CourtBookingAvailabilityService $availability)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'verified_amount' => ['required', 'numeric', 'gt:0', 'max:9999999999.99'],
            'verification_note' => ['nullable', 'string', 'max:500'],
        ]);
        $verifiedAmount = round((float) $validated['verified_amount'], 2);
        $verificationNote = trim((string) ($validated['verification_note'] ?? ''));

        DB::transaction(function () use ($booking, $availability, $verifiedAmount, $verificationNote) {
            $court = $availability->lockCourt($booking->court_id);
            $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($lockedBooking->status !== CourtBooking::STATUS_PENDING_REVIEW) {
                throw ValidationException::withMessages(['booking' => 'Hanya booking yang menunggu verifikasi yang dapat dikonfirmasi.']);
            }

            if (!$court || $availability->bookingConflict(
                $court,
                $lockedBooking->booking_date->toDateString(),
                $lockedBooking->time_start,
                $lockedBooking->time_end,
                $lockedBooking->id,
            ) || $availability->matchConflict(
                $court,
                $lockedBooking->booking_date->toDateString(),
                $lockedBooking->time_start,
                $lockedBooking->time_end,
            )) {
                throw ValidationException::withMessages(['booking' => 'Jadwal bertabrakan dengan booking atau pertandingan lain. Periksa jadwal sebelum mengonfirmasi.']);
            }

            $payment = $lockedBooking->payments()
                ->where('status', CourtBookingPayment::STATUS_PENDING_REVIEW)
                ->orderBy('id')
                ->lockForUpdate()
                ->first();
            if (!$payment) {
                throw ValidationException::withMessages(['booking' => 'Tidak ada pembayaran yang menunggu verifikasi untuk booking ini.']);
            }

            $confirmedBefore = (float) $lockedBooking->payments()
                ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
                ->sum('verified_amount');
            $remainingBefore = max(0, round((float) $lockedBooking->amount - $confirmedBefore, 2));
            if ($verifiedAmount > $remainingBefore) {
                throw ValidationException::withMessages(['booking' => 'Nominal pembayaran melebihi sisa tagihan. Periksa riwayat pembayaran booking ini.']);
            }
            if (
                $payment->claimed_amount !== null
                && abs($verifiedAmount - (float) $payment->claimed_amount) >= 0.01
                && $verificationNote === ''
            ) {
                throw ValidationException::withMessages(['verification_note' => 'Catatan wajib diisi jika nominal terverifikasi berbeda dari nominal yang diklaim pelanggan.']);
            }

            $payment->update([
                'amount' => $verifiedAmount,
                'verified_amount' => $verifiedAmount,
                'stage' => $verifiedAmount >= (float) $lockedBooking->amount
                    ? CourtBookingPayment::STAGE_FULL
                    : CourtBookingPayment::STAGE_DEPOSIT,
                'note' => $verificationNote !== '' ? $verificationNote : $payment->note,
                'status' => CourtBookingPayment::STATUS_CONFIRMED,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $lockedBooking->update([
                'status' => CourtBooking::STATUS_CONFIRMED,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'confirmed_at' => now(),
                'admin_note' => null,
            ]);
        });

        return back()->with('success', 'Nominal pembayaran terverifikasi dan booking telah dikonfirmasi. Sisa tagihan dihitung dari nominal yang dimasukkan admin.');
    }

    public function reject(Request $request, CourtBooking $booking)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($booking, $validated) {
            $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($lockedBooking->status !== CourtBooking::STATUS_PENDING_REVIEW) {
                throw ValidationException::withMessages(['booking' => 'Hanya booking yang menunggu verifikasi yang dapat ditolak.']);
            }

            $lockedBooking->update([
                'status' => CourtBooking::STATUS_REJECTED,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'admin_note' => $validated['admin_note'] ?? null,
            ]);
            $lockedBooking->payments()
                ->where('status', CourtBookingPayment::STATUS_PENDING_REVIEW)
                ->update([
                    'status' => CourtBookingPayment::STATUS_REJECTED,
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'note' => $validated['admin_note'] ?? null,
                    'updated_at' => now(),
                ]);
        });

        return back()->with('success', 'Booking ditolak dan slot kembali tersedia.');
    }

    public function cancel(Request $request, CourtBooking $booking)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'admin_note' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($booking, $validated) {
            $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($lockedBooking->rental_picked_up_at) {
                throw ValidationException::withMessages(['booking' => 'Booking yang perlengkapannya sudah diambil tidak dapat dibatalkan sampai barang dikembalikan.']);
            }
            if (!in_array($lockedBooking->status, [
                CourtBooking::STATUS_AWAITING_PAYMENT,
                CourtBooking::STATUS_PENDING_REVIEW,
                CourtBooking::STATUS_CONFIRMED,
            ], true)) {
                throw ValidationException::withMessages(['booking' => 'Status booking ini tidak dapat dibatalkan.']);
            }

            $lockedBooking->update([
                'status' => CourtBooking::STATUS_CANCELLED,
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'admin_note' => $validated['admin_note'],
            ]);
            $lockedBooking->rentalAdditions()
                ->whereIn('status', [
                    CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT,
                    CourtBookingRentalAddition::STATUS_PENDING_REVIEW,
                    CourtBookingRentalAddition::STATUS_CONFIRMED,
                ])
                ->update([
                    'status' => CourtBookingRentalAddition::STATUS_CANCELLED,
                    'expires_at' => null,
                    'admin_note' => 'Booking lapangan dibatalkan. Admin perlu menindaklanjuti pengembalian pembayaran tambahan bila sudah dibayar.',
                    'updated_at' => now(),
                ]);
        });

        return back()->with('success', 'Booking dibatalkan. Pastikan pelanggan menerima informasi pengembalian dana bila diperlukan.');
    }

    public function markRentalPickedUp(CourtBooking $booking, CourtRentalInventoryService $inventory)
    {
        $this->ensureAdmin();
        $inventory->expireUnpaidOrders($booking->booking_date->toDateString());

        DB::transaction(function () use ($booking) {
            $itemIds = DB::table('court_booking_rental_lines')
                ->where('court_booking_id', $booking->id)
                ->whereNotNull('court_rental_item_id')
                ->pluck('court_rental_item_id')
                ->map(fn ($id) => (int) $id)
                ->merge(DB::table('court_booking_rental_addition_lines as line')
                    ->join('court_booking_rental_additions as addition', 'addition.id', '=', 'line.court_booking_rental_addition_id')
                    ->where('addition.court_booking_id', $booking->id)
                    ->where('addition.status', CourtBookingRentalAddition::STATUS_CONFIRMED)
                    ->whereNotNull('line.court_rental_item_id')->pluck('line.court_rental_item_id')->map(fn ($id) => (int) $id))
                ->unique()->values()->all();
            CourtRentalItem::query()->whereIn('id', $itemIds)->orderBy('id')->lockForUpdate()->get();
            $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            $hasConfirmedAddition = $lockedBooking->rentalAdditions()->where('status', CourtBookingRentalAddition::STATUS_CONFIRMED)->exists();
            $hasUnresolvedAddition = $lockedBooking->rentalAdditions()->whereIn('status', [
                CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT,
                CourtBookingRentalAddition::STATUS_PENDING_REVIEW,
            ])->exists();
            if ($lockedBooking->status !== CourtBooking::STATUS_CONFIRMED || (!$lockedBooking->rentalLines()->exists() && !$hasConfirmedAddition)) {
                throw ValidationException::withMessages(['booking' => 'Pengambilan hanya dapat dicatat untuk booking terkonfirmasi yang memiliki perlengkapan.']);
            }
            if ($hasUnresolvedAddition) {
                throw ValidationException::withMessages(['booking' => 'Selesaikan atau batalkan pembayaran tambahan perlengkapan sebelum mencatat pengambilan.']);
            }
            if ($lockedBooking->rental_returned_at) {
                throw ValidationException::withMessages(['booking' => 'Perlengkapan pada booking ini sudah tercatat dikembalikan.']);
            }
            if ($lockedBooking->rental_picked_up_at) {
                return;
            }

            $today = CarbonImmutable::today(CourtBooking::TIMEZONE)->toDateString();
            if ($lockedBooking->booking_date->toDateString() > $today) {
                throw ValidationException::withMessages(['booking' => 'Pengambilan belum dapat dicatat sebelum tanggal booking.']);
            }

            $lockedBooking->update(['rental_picked_up_at' => now()]);
        });

        return back()->with('success', 'Waktu pengambilan perlengkapan pada booking berhasil dicatat.');
    }

    public function markRentalReturned(CourtBooking $booking)
    {
        $this->ensureAdmin();

        DB::transaction(function () use ($booking) {
            $itemIds = DB::table('court_booking_rental_lines')
                ->where('court_booking_id', $booking->id)
                ->whereNotNull('court_rental_item_id')
                ->pluck('court_rental_item_id')
                ->map(fn ($id) => (int) $id)
                ->merge(DB::table('court_booking_rental_addition_lines as line')
                    ->join('court_booking_rental_additions as addition', 'addition.id', '=', 'line.court_booking_rental_addition_id')
                    ->where('addition.court_booking_id', $booking->id)
                    ->where('addition.status', CourtBookingRentalAddition::STATUS_CONFIRMED)
                    ->whereNotNull('line.court_rental_item_id')->pluck('line.court_rental_item_id')->map(fn ($id) => (int) $id))
                ->unique()->values()->all();
            CourtRentalItem::query()->whereIn('id', $itemIds)->orderBy('id')->lockForUpdate()->get();
            $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($lockedBooking->status !== CourtBooking::STATUS_CONFIRMED || !$lockedBooking->rental_picked_up_at) {
                throw ValidationException::withMessages(['booking' => 'Catat pengambilan dan konfirmasi booking sebelum mencatat pengembalian.']);
            }
            if ($lockedBooking->rental_returned_at) {
                throw ValidationException::withMessages(['booking' => 'Pengembalian perlengkapan sudah tercatat.']);
            }

            $lockedBooking->update(['rental_returned_at' => now()]);
        });

        return back()->with('success', 'Waktu pengembalian perlengkapan berhasil dicatat dan stok diperbarui.');
    }

    private function validateCourt(Request $request, ?Court $court = null): array
    {
        foreach (['day_hourly_rate', 'night_hourly_rate'] as $rateField) {
            $rate = $request->input($rateField);
            if (is_string($rate)) {
                $rate = preg_replace('/^Rp\s*/i', '', trim($rate));
                $rate = preg_replace('/\s+/', '', $rate);
                $request->merge([
                    $rateField => str_replace('.', '', $rate),
                ]);
            }
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('courts', 'name')->ignore($court?->id)],
            'day_hourly_rate' => ['required', 'integer', 'min:0', 'max:100000000'],
            'night_hourly_rate' => ['required', 'integer', 'min:0', 'max:100000000'],
            'night_rate_starts_at' => ['required', 'date_format:H:i'],
            'opening_time' => ['required', 'date_format:H:i'],
            'closing_time' => ['required', 'date_format:H:i', 'after:opening_time'],
            'slot_duration_minutes' => ['required', 'integer', Rule::in([30, 60, 90, 120])],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        return $validated + [
            // Keep the legacy column in sync for compatibility with older consumers.
            'hourly_rate' => $validated['day_hourly_rate'],
            'is_active' => $request->boolean('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
    }

    private function hasFutureBookings(Court $court): bool
    {
        return CourtBooking::query()
            ->where('court_id', $court->id)
            ->where('booking_date', '>=', now(CourtBooking::TIMEZONE)->toDateString())
            ->where(function ($query) {
                $query->whereIn('status', [CourtBooking::STATUS_PENDING_REVIEW, CourtBooking::STATUS_CONFIRMED])
                    ->orWhere(function ($query) {
                        $query->where('status', CourtBooking::STATUS_AWAITING_PAYMENT)
                            ->where('expires_at', '>', now());
                    });
            })
            ->exists();
    }
}
