<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\CourtBooking;
use App\Models\CourtBookingBackdateAudit;
use App\Models\CourtBookingScheduleAudit;
use App\Models\CourtBookingPayment;
use App\Models\CourtBookingRentalAddition;
use App\Models\CourtBookingSetting;
use App\Models\CourtDailyRevenue;
use App\Models\CourtDailyRevenueEntry;
use App\Models\CourtDailyReportReview;
use App\Models\CourtMemberSchedule;
use App\Models\CourtMemberScheduleDayOff;
use App\Models\CourtBookingPaymentAudit;
use App\Models\CourtBookingFundSettlement;
use App\Models\CourtTournamentRental;
use App\Models\Game;
use App\Models\CourtRentalItem;
use App\Models\CourtRentalOrder;
use App\Models\Tournament;
use App\Services\CourtBookingAvailabilityService;
use App\Services\CourtBookingDiscountService;
use App\Services\CourtPricingService;
use App\Services\CourtRentalInventoryService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
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
            ->with(['court', 'verifiedBy', 'cancelledBy', 'createdBy', 'rentalLines', 'rentalAdditions.lines', 'rentalAdditions.verifiedBy', 'payments', 'paymentAudits.admin', 'scheduleAudits.admin', 'backdateAudits.admin', 'fundSettlements.recordedBy', 'fundSettlements.voidedBy'])
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
            ->whereHas('booking', fn ($query) => $query
                ->whereDate('booking_date', $todayReportDate)
                ->where('status', CourtBooking::STATUS_CONFIRMED))
            ->sum(DB::raw('COALESCE(verified_amount, amount)'))
            + (float) CourtBookingRentalAddition::query()
                ->whereNotNull('confirmed_at')
                ->whereHas('booking', fn ($query) => $query
                    ->whereDate('booking_date', $todayReportDate)
                    ->where('status', CourtBooking::STATUS_CONFIRMED))
                ->sum('amount')
            + (float) CourtBookingFundSettlement::query()
                ->where('type', CourtBookingFundSettlement::TYPE_RETAINED)
                ->whereNull('voided_at')
                ->whereHas('booking', fn ($query) => $query
                    ->whereDate('booking_date', $todayReportDate)
                    ->where('status', CourtBooking::STATUS_CANCELLED))
                ->sum('amount')
            - (float) CourtBookingFundSettlement::query()
                ->where('type', CourtBookingFundSettlement::TYPE_REFUND)
                ->whereNull('voided_at')
                ->whereHas('booking', fn ($query) => $query
                    ->whereDate('booking_date', $todayReportDate)
                    ->where('status', CourtBooking::STATUS_CONFIRMED))
                ->sum('amount');

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

        return view('admin.court-bookings.index', compact(
            'bookings',
            'counts',
            'filters',
            'todayReportDate',
            'todayConfirmedCount',
            'todayConfirmedMinutes',
            'todayReceivedPayments',
        ));
    }

    public function revenueReport(Request $request)
    {
        $this->ensureAdmin();

        $filters = $request->validate([
            'month' => ['nullable', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
        ]);
        $selectedMonth = $filters['month'] ?? CarbonImmutable::now(CourtBooking::TIMEZONE)->format('Y-m');
        $monthStart = CarbonImmutable::parse($selectedMonth.'-01', CourtBooking::TIMEZONE)->startOfMonth();
        $nextMonthStart = $monthStart->addMonth();
        $monthStartUtc = $monthStart->utc()->format('Y-m-d H:i:s');
        $nextMonthStartUtc = $nextMonthStart->utc()->format('Y-m-d H:i:s');
        $monthEndDate = $nextMonthStart->subDay()->toDateString();
        $selectedMonthLabel = $monthStart->locale('id')->translatedFormat('F Y');

        $monthlyIncomeByCategory = [
            'booking' => $this->emptyPaymentMethodTotals(),
            'rental_additions' => $this->emptyPaymentMethodTotals(),
            'standalone_rentals' => $this->emptyPaymentMethodTotals(),
            'parking' => $this->emptyPaymentMethodTotals(),
            'shop' => $this->emptyPaymentMethodTotals(),
            'other' => $this->emptyPaymentMethodTotals(),
            'refunds' => $this->emptyPaymentMethodTotals(),
        ];

        $bookingPayments = DB::table('court_booking_payments')
            ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
            ->whereRaw('COALESCE(recorded_at, verified_at, created_at) >= ? AND COALESCE(recorded_at, verified_at, created_at) < ?', [
                $monthStartUtc,
                $nextMonthStartUtc,
            ]);
        $this->accumulatePaymentMethodTotals(
            $monthlyIncomeByCategory['booking'],
            $bookingPayments,
            'payment_method',
            'COALESCE(verified_amount, amount)',
        );

        $monthlyTournamentRentalIncome = DB::table('court_daily_revenue_entries')
            ->whereNotNull('court_tournament_rental_id')
            ->whereNull('deleted_at')
            ->whereBetween('operating_date', [$monthStart->toDateString(), $monthEndDate]);
        $this->accumulatePaymentMethodTotals(
            $monthlyIncomeByCategory['booking'],
            $monthlyTournamentRentalIncome,
            'payment_method',
            'amount',
        );

        $bookingRentalAdditions = DB::table('court_booking_rental_additions')
            ->whereNotNull('confirmed_at')
            ->where('confirmed_at', '>=', $monthStartUtc)
            ->where('confirmed_at', '<', $nextMonthStartUtc);
        $this->accumulatePaymentMethodTotals(
            $monthlyIncomeByCategory['rental_additions'],
            $bookingRentalAdditions,
            'payment_method',
            'amount',
        );

        $standaloneRentals = DB::table('court_rental_orders')
            ->where('status', CourtRentalOrder::STATUS_CONFIRMED)
            ->whereNotNull('confirmed_at')
            ->where('confirmed_at', '>=', $monthStartUtc)
            ->where('confirmed_at', '<', $nextMonthStartUtc);
        $this->accumulatePaymentMethodTotals(
            $monthlyIncomeByCategory['standalone_rentals'],
            $standaloneRentals,
            'payment_method',
            'amount',
        );

        $monthlyDailyRevenue = DB::table('court_daily_revenues')
            ->whereBetween('operating_date', [$monthStart->toDateString(), $monthEndDate])
            ->selectRaw(
                'COALESCE(SUM(parking_cash_income), 0) as parking_cash, '
                .'COALESCE(SUM(parking_transfer_income), 0) as parking_transfer, '
                .'COALESCE(SUM(parking_qris_income), 0) as parking_qris, '
                .'COALESCE(SUM(parking_unclassified_income), 0) as parking_unclassified, '
                .'COALESCE(SUM(shop_cash_income), 0) as shop_cash, '
                .'COALESCE(SUM(shop_transfer_income), 0) as shop_transfer, '
                .'COALESCE(SUM(shop_qris_income), 0) as shop_qris, '
                .'COALESCE(SUM(shop_unclassified_income), 0) as shop_unclassified'
            )
            ->first();
        foreach (['cash', 'transfer', 'qris', 'unclassified'] as $method) {
            $monthlyIncomeByCategory['parking'][$method] = (float) ($monthlyDailyRevenue->{'parking_'.$method} ?? 0);
            $monthlyIncomeByCategory['shop'][$method] = (float) ($monthlyDailyRevenue->{'shop_'.$method} ?? 0);
        }

        $otherIncomeEntries = DB::table('court_daily_revenue_entries')
            ->whereNull('deleted_at')
            ->whereNull('court_tournament_rental_id')
            ->whereBetween('operating_date', [$monthStart->toDateString(), $monthEndDate]);
        $this->accumulatePaymentMethodTotals(
            $monthlyIncomeByCategory['other'],
            $otherIncomeEntries,
            'payment_method',
            'amount',
        );

        $monthlyRefunds = DB::table('court_booking_fund_settlements')
            ->where('type', CourtBookingFundSettlement::TYPE_REFUND)
            ->whereNull('voided_at')
            ->where('recorded_at', '>=', $monthStartUtc)
            ->where('recorded_at', '<', $nextMonthStartUtc);
        $this->accumulatePaymentMethodTotals(
            $monthlyIncomeByCategory['refunds'],
            $monthlyRefunds,
            'payment_method',
            'amount',
        );

        $monthlyIncomingByMethod = $this->emptyPaymentMethodTotals();
        foreach (['booking', 'rental_additions', 'standalone_rentals', 'parking', 'shop', 'other'] as $category) {
            foreach (array_keys($monthlyIncomingByMethod) as $method) {
                $monthlyIncomingByMethod[$method] += $monthlyIncomeByCategory[$category][$method];
            }
        }
        $monthlyNetByMethod = $monthlyIncomingByMethod;
        foreach (array_keys($monthlyNetByMethod) as $method) {
            $monthlyNetByMethod[$method] -= $monthlyIncomeByCategory['refunds'][$method];
        }
        $monthlyGrossReceipts = array_sum($monthlyIncomingByMethod);
        $monthlyRefundTotal = array_sum($monthlyIncomeByCategory['refunds']);
        $monthlyNetReceipts = array_sum($monthlyNetByMethod);

        $monthlyExpenseGroups = DB::table('court_operational_expenses')
            ->whereNull('deleted_at')
            ->whereBetween('expense_date', [$monthStart->toDateString(), $monthEndDate])
            ->selectRaw('category, payment_method, COALESCE(SUM(amount), 0) as total')
            ->groupBy('category', 'payment_method')
            ->get();
        $monthlyExpenseByCategory = [
            'salary' => $this->emptyPaymentMethodTotals(),
            'electricity' => $this->emptyPaymentMethodTotals(),
            'other' => $this->emptyPaymentMethodTotals(),
        ];
        foreach ($monthlyExpenseGroups as $expenseGroup) {
            $method = $expenseGroup->payment_method === 'bank_transfer' ? 'transfer' : $expenseGroup->payment_method;
            if (isset($monthlyExpenseByCategory[$expenseGroup->category][$method])) {
                $monthlyExpenseByCategory[$expenseGroup->category][$method] = (float) $expenseGroup->total;
            }
        }
        $monthlyExpenseTotal = (float) $monthlyExpenseGroups->sum('total');
        $monthlyOperatingSurplus = $monthlyNetReceipts - $monthlyExpenseTotal;

        $grossBookingPayments = (float) CourtBookingPayment::query()
            ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
            ->sum(DB::raw('COALESCE(verified_amount, amount)'))
            + (float) CourtBookingRentalAddition::query()->whereNotNull('confirmed_at')->sum('amount');
        $activeBookingPayments = (float) CourtBookingPayment::query()
            ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
            ->whereHas('booking', fn ($query) => $query->where('status', CourtBooking::STATUS_CONFIRMED))
            ->sum(DB::raw('COALESCE(verified_amount, amount)'));
        $activeBookingAdditionIncome = (float) CourtBookingRentalAddition::query()
            ->join('court_bookings as booking', 'booking.id', '=', 'court_booking_rental_additions.court_booking_id')
            ->whereNotNull('court_booking_rental_additions.confirmed_at')
            ->where('booking.status', CourtBooking::STATUS_CONFIRMED)
            ->sum('court_booking_rental_additions.amount');
        $bookingRefunds = (float) CourtBookingFundSettlement::query()
            ->where('type', CourtBookingFundSettlement::TYPE_REFUND)
            ->whereNull('voided_at')
            ->sum('amount');
        $activeBookingRefunds = (float) CourtBookingFundSettlement::query()
            ->where('type', CourtBookingFundSettlement::TYPE_REFUND)
            ->whereNull('voided_at')
            ->whereHas('booking', fn ($query) => $query->where('status', CourtBooking::STATUS_CONFIRMED))
            ->sum('amount');
        $retainedCancellationIncome = (float) CourtBookingFundSettlement::query()
            ->where('type', CourtBookingFundSettlement::TYPE_RETAINED)
            ->whereNull('voided_at')
            ->whereHas('booking', fn ($query) => $query->where('status', CourtBooking::STATUS_CANCELLED))
            ->sum('amount');
        $cancelledBookingPayments = (float) CourtBookingPayment::query()
            ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
            ->whereHas('booking', fn ($query) => $query->where('status', CourtBooking::STATUS_CANCELLED))
            ->sum(DB::raw('COALESCE(verified_amount, amount)'))
            + (float) CourtBookingRentalAddition::query()
                ->whereNotNull('confirmed_at')
                ->whereHas('booking', fn ($query) => $query->where('status', CourtBooking::STATUS_CANCELLED))
                ->sum('amount');
        $cancelledBookingSettlements = (float) CourtBookingFundSettlement::query()
            ->whereNull('voided_at')
            ->whereHas('booking', fn ($query) => $query->where('status', CourtBooking::STATUS_CANCELLED))
            ->sum('amount');
        $unresolvedCancellationFunds = max(0, round($cancelledBookingPayments - $cancelledBookingSettlements, 2));
        $bookingIncome = max(0, round($activeBookingPayments - $activeBookingRefunds, 2))
            + $activeBookingAdditionIncome + $retainedCancellationIncome
            + (float) CourtDailyRevenueEntry::query()->whereNotNull('court_tournament_rental_id')->sum('amount');
        $standaloneRentalIncome = (float) CourtRentalOrder::query()
            ->where('status', CourtRentalOrder::STATUS_CONFIRMED)
            ->sum('amount');
        $allTimeParkingIncome = (float) CourtDailyRevenue::query()->sum('parking_income');
        $allTimeShopIncome = (float) CourtDailyRevenue::query()->sum('shop_income');
        $allTimeOtherIncome = (float) CourtDailyRevenueEntry::query()->whereNull('court_tournament_rental_id')->sum('amount');
        $otherRentalIncome = $standaloneRentalIncome;
        $totalIncome = $bookingIncome + $otherRentalIncome + $allTimeParkingIncome + $allTimeShopIncome + $allTimeOtherIncome;
        $incomeByPaymentMethod = $this->recognizedIncomeByPaymentMethod();
        $totalIncome = array_sum($incomeByPaymentMethod);

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
            ->whereNotNull('addition.confirmed_at')
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

        $chartEnd = CarbonImmutable::now(CourtBooking::TIMEZONE)->endOfMonth();
        $chartStart = $chartEnd->startOfMonth()->subMonths(11);
        $dailyBookingStats = DB::table('court_booking_payments as payment')
            ->join('court_bookings as booking', 'booking.id', '=', 'payment.court_booking_id')
            ->select('booking.booking_date')
            ->selectRaw('COUNT(DISTINCT booking.id) as booking_count, SUM(COALESCE(payment.verified_amount, payment.amount)) as booking_value')
            ->where('payment.status', CourtBookingPayment::STATUS_CONFIRMED)
            ->where('booking.status', CourtBooking::STATUS_CONFIRMED)
            ->whereBetween('booking.booking_date', [$chartStart->toDateString(), $chartEnd->toDateString()])
            ->groupBy('booking.booking_date')
            ->get()
            ->concat(DB::table('court_booking_fund_settlements as settlement')
                ->join('court_bookings as booking', 'booking.id', '=', 'settlement.court_booking_id')
                ->select('booking.booking_date')
                ->selectRaw('0 as booking_count, -SUM(settlement.amount) as booking_value')
                ->where('settlement.type', CourtBookingFundSettlement::TYPE_REFUND)
                ->whereNull('settlement.voided_at')
                ->where('booking.status', CourtBooking::STATUS_CONFIRMED)
                ->whereBetween('booking.booking_date', [$chartStart->toDateString(), $chartEnd->toDateString()])
                ->groupBy('booking.booking_date')
                ->get())
            ->concat(DB::table('court_booking_fund_settlements as settlement')
                ->join('court_bookings as booking', 'booking.id', '=', 'settlement.court_booking_id')
                ->select('booking.booking_date')
                ->selectRaw('0 as booking_count, SUM(settlement.amount) as booking_value')
                ->where('settlement.type', CourtBookingFundSettlement::TYPE_RETAINED)
                ->whereNull('settlement.voided_at')
                ->where('booking.status', CourtBooking::STATUS_CANCELLED)
                ->whereBetween('booking.booking_date', [$chartStart->toDateString(), $chartEnd->toDateString()])
                ->groupBy('booking.booking_date')
                ->get())
            ->concat(DB::table('court_booking_rental_additions as addition')
                ->join('court_bookings as booking', 'booking.id', '=', 'addition.court_booking_id')
                ->select('booking.booking_date')
                ->selectRaw('COUNT(*) as booking_count, SUM(addition.amount) as booking_value')
                ->whereNotNull('addition.confirmed_at')
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
                ->get())
            ->concat(DB::table('court_daily_revenues')
                ->select('operating_date as booking_date')
                ->selectRaw('0 as booking_count, SUM(parking_income + shop_income) as booking_value')
                ->whereBetween('operating_date', [$chartStart->toDateString(), $chartEnd->toDateString()])
                ->groupBy('operating_date')
                ->get())
            ->concat(DB::table('court_daily_revenue_entries')
                ->select('operating_date as booking_date')
                ->selectRaw('0 as booking_count, SUM(amount) as booking_value')
                ->whereNull('deleted_at')
                ->whereBetween('operating_date', [$chartStart->toDateString(), $chartEnd->toDateString()])
                ->groupBy('operating_date')
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

        return view('admin.court-bookings.revenue', compact(
            'selectedMonth',
            'selectedMonthLabel',
            'monthlyIncomeByCategory',
            'monthlyIncomingByMethod',
            'monthlyNetByMethod',
            'monthlyGrossReceipts',
            'monthlyRefundTotal',
            'monthlyNetReceipts',
            'monthlyExpenseByCategory',
            'monthlyExpenseTotal',
            'monthlyOperatingSurplus',
            'totalIncome',
            'bookingIncome',
            'incomeByPaymentMethod',
            'grossBookingPayments',
            'bookingRefunds',
            'retainedCancellationIncome',
            'unresolvedCancellationFunds',
            'otherRentalIncome',
            'allTimeParkingIncome',
            'allTimeShopIncome',
            'allTimeOtherIncome',
            'rentalIncomeByItem',
            'monthlyBookingChart',
            'maximumMonthlyBookings',
            'busiestMonths',
            'chartStart',
            'chartEnd',
        ));
    }

    private function emptyPaymentMethodTotals(): array
    {
        return [
            'cash' => 0.0,
            'transfer' => 0.0,
            'qris' => 0.0,
            'unclassified' => 0.0,
        ];
    }

    private function accumulatePaymentMethodTotals(array &$totals, $query, string $methodColumn, string $amountExpression): void
    {
        $rows = $query
            ->select($methodColumn)
            ->selectRaw("SUM({$amountExpression}) as amount")
            ->groupBy($methodColumn)
            ->get();

        foreach ($rows as $row) {
            $method = match ($row->{$methodColumn}) {
                'cash' => 'cash',
                'bank_transfer' => 'transfer',
                'qris' => 'qris',
                default => 'unclassified',
            };
            $totals[$method] += (float) $row->amount;
        }
    }

    /**
     * Return recognized income by the payment channel used to receive it.
     * Cancelled booking receipts are excluded unless an amount was explicitly retained.
     */
    private function recognizedIncomeByPaymentMethod(?string $operatingDate = null): array
    {
        $totals = [
            'cash' => 0.0,
            'transfer' => 0.0,
            'qris' => 0.0,
            'unclassified' => 0.0,
        ];

        $sources = [];

        $bookingPayments = DB::table('court_booking_payments as payment')
            ->join('court_bookings as booking', 'booking.id', '=', 'payment.court_booking_id')
            ->where('payment.status', CourtBookingPayment::STATUS_CONFIRMED)
            ->where('booking.status', CourtBooking::STATUS_CONFIRMED);
        if ($operatingDate) {
            $bookingPayments->whereDate('booking.booking_date', $operatingDate);
        }
        $sources[] = [$bookingPayments, 'payment.payment_method', 'COALESCE(payment.verified_amount, payment.amount)'];

        $rentalAdditions = DB::table('court_booking_rental_additions as addition')
            ->join('court_bookings as booking', 'booking.id', '=', 'addition.court_booking_id')
            ->whereNotNull('addition.confirmed_at')
            ->where('booking.status', CourtBooking::STATUS_CONFIRMED);
        if ($operatingDate) {
            $rentalAdditions->whereDate('booking.booking_date', $operatingDate);
        }
        $sources[] = [$rentalAdditions, 'addition.payment_method', 'addition.amount'];

        $retainedSettlements = DB::table('court_booking_fund_settlements as settlement')
            ->join('court_bookings as booking', 'booking.id', '=', 'settlement.court_booking_id')
            ->where('settlement.type', CourtBookingFundSettlement::TYPE_RETAINED)
            ->whereNull('settlement.voided_at')
            ->where('booking.status', CourtBooking::STATUS_CANCELLED);
        if ($operatingDate) {
            $retainedSettlements->whereDate('booking.booking_date', $operatingDate);
        }
        $sources[] = [$retainedSettlements, 'settlement.payment_method', 'settlement.amount'];

        $standaloneRentals = DB::table('court_rental_orders as rental_order')
            ->where('rental_order.status', CourtRentalOrder::STATUS_CONFIRMED);
        if ($operatingDate) {
            $standaloneRentals->whereDate('rental_order.rental_date', $operatingDate);
        }
        $sources[] = [$standaloneRentals, 'rental_order.payment_method', 'rental_order.amount'];

        $otherRevenue = DB::table('court_daily_revenue_entries as entry');
        $otherRevenue->whereNull('entry.deleted_at');
        if ($operatingDate) {
            $otherRevenue->whereDate('entry.operating_date', $operatingDate);
        }
        $sources[] = [$otherRevenue, 'entry.payment_method', 'entry.amount'];

        foreach ($sources as [$query, $methodColumn, $amountColumn]) {
            $rows = $query
                ->select($methodColumn)
                ->selectRaw("SUM({$amountColumn}) as amount")
                ->groupBy($methodColumn)
                ->get();

            foreach ($rows as $row) {
                $bucket = match ($row->payment_method) {
                    'cash' => 'cash',
                    'bank_transfer' => 'transfer',
                    'qris' => 'qris',
                    default => 'unclassified',
                };
                $totals[$bucket] += (float) $row->amount;
            }
        }

        $bookingRefunds = DB::table('court_booking_fund_settlements as settlement')
            ->join('court_bookings as booking', 'booking.id', '=', 'settlement.court_booking_id')
            ->where('settlement.type', CourtBookingFundSettlement::TYPE_REFUND)
            ->whereNull('settlement.voided_at')
            ->where('booking.status', CourtBooking::STATUS_CONFIRMED);
        if ($operatingDate) {
            $bookingRefunds->whereDate('booking.booking_date', $operatingDate);
        }
        foreach ($bookingRefunds->select('settlement.payment_method')
            ->selectRaw('SUM(settlement.amount) as amount')
            ->groupBy('settlement.payment_method')
            ->get() as $refund) {
            $bucket = match ($refund->payment_method) {
                'cash' => 'cash',
                'bank_transfer' => 'transfer',
                'qris' => 'qris',
                default => 'unclassified',
            };
            $totals[$bucket] -= (float) $refund->amount;
        }

        $dailyRevenue = DB::table('court_daily_revenues');
        if ($operatingDate) {
            $dailyRevenue->where('operating_date', $operatingDate);
        }
        $revenueTotals = $dailyRevenue->selectRaw(
            'COALESCE(SUM(parking_cash_income + shop_cash_income), 0) as cash, '
            .'COALESCE(SUM(parking_transfer_income + shop_transfer_income), 0) as transfer, '
            .'COALESCE(SUM(parking_qris_income + shop_qris_income), 0) as qris, '
            .'COALESCE(SUM(parking_unclassified_income + shop_unclassified_income), 0) as unclassified'
        )->first();

        foreach (array_keys($totals) as $method) {
            $totals[$method] += (float) ($revenueTotals->{$method} ?? 0);
        }

        return array_map(fn (float $amount): float => round($amount, 2), $totals);
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
        $reportMonthStart = CarbonImmutable::parse($reportDate, CourtBooking::TIMEZONE)->startOfMonth();
        $reportMonthEnd = $reportMonthStart->endOfMonth();
        $dailyReconciliation = $this->dailyReportReconciliationProgress(
            $reportMonthStart->toDateString(),
            $reportMonthEnd->toDateString(),
            CarbonImmutable::today(CourtBooking::TIMEZONE),
        );

        $dailyBookings = CourtBooking::query()
            ->with(['court', 'cancelledBy', 'payments', 'rentalAdditions.verifiedBy', 'fundSettlements.recordedBy', 'fundSettlements.voidedBy'])
            ->whereDate('booking_date', $reportDate)
            ->orderBy('time_start')
            ->orderBy('id')
            ->get();
        $dailyMatches = Game::query()
            ->with(['tournament', 'homeTeam', 'awayTeam'])
            ->whereDate('match_date', $reportDate)
            ->where('is_cancelled', false)
            ->where('status', '!=', 'postponed')
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
            ->sum(fn ($payment) => $payment->recognized_amount))
            + (float) $dailyBookings
                ->sum(fn ($booking) => $booking->rentalAdditions
                    ->whereNotNull('confirmed_at')
                    ->sum('amount'));
        $dailyBookingRefunds = (float) CourtBookingFundSettlement::query()
            ->where('type', CourtBookingFundSettlement::TYPE_REFUND)
            ->whereNull('voided_at')
            ->whereHas('booking', fn ($query) => $query->whereDate('booking_date', $reportDate))
            ->sum('amount');
        $dailyCancelledGross = (float) $dailyBookings
            ->where('status', CourtBooking::STATUS_CANCELLED)
            ->sum(fn ($booking) => $booking->payments
                ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
                ->sum(fn ($payment) => $payment->recognized_amount)
                + $booking->rentalAdditions->whereNotNull('confirmed_at')->sum('amount'));
        $dailyCancelledSettled = (float) CourtBookingFundSettlement::query()
            ->whereNull('voided_at')
            ->whereHas('booking', fn ($query) => $query
                ->whereDate('booking_date', $reportDate)
                ->where('status', CourtBooking::STATUS_CANCELLED))
            ->sum('amount');
        $dailyUnresolvedCancellationFunds = max(0, round($dailyCancelledGross - $dailyCancelledSettled, 2));
        $dailyBookingIncome = max(0, round($dailyBookingPayments - $dailyBookingRefunds - $dailyUnresolvedCancellationFunds, 2));
        $dailyTournamentRentalIncome = (float) CourtDailyRevenueEntry::query()
            ->whereDate('operating_date', $reportDate)
            ->whereNotNull('court_tournament_rental_id')
            ->sum('amount');
        $dailyBookingIncome += $dailyTournamentRentalIncome;
        $dailyRentalAdditionIncome = (float) CourtBookingRentalAddition::query()
            ->join('court_bookings as booking', 'booking.id', '=', 'court_booking_rental_additions.court_booking_id')
            ->whereDate('booking.booking_date', $reportDate)
            ->where('booking.status', CourtBooking::STATUS_CONFIRMED)
            ->whereNotNull('court_booking_rental_additions.confirmed_at')
            ->sum('court_booking_rental_additions.amount');
        $dailyStandaloneRentalIncome = (float) CourtRentalOrder::query()
            ->whereDate('rental_date', $reportDate)
            ->where('status', CourtRentalOrder::STATUS_CONFIRMED)
            ->sum('amount');
        $dailyRevenueEntries = CourtDailyRevenueEntry::query()
            ->with(['updatedBy', 'tournament', 'court', 'courtTournamentRental.court'])
            ->where('operating_date', $reportDate)
            ->orderBy('id')
            ->get();
        $dailyOtherIncome = (float) $dailyRevenueEntries
            ->whereNull('court_tournament_rental_id')
            ->sum('amount');
        $dailyTotalIncome = $dailyBookingIncome + $dailyParkingIncome + $dailyShopIncome + $dailyOtherIncome + $dailyStandaloneRentalIncome;
        $dailyIncomeByPaymentMethod = $this->recognizedIncomeByPaymentMethod($reportDate);
        $dailyTotalIncome = array_sum($dailyIncomeByPaymentMethod);
        $tournaments = Tournament::query()->orderBy('name')->get(['id', 'name']);
        $courts = Court::query()->orderBy('sort_order')->orderBy('name')->get(['id', 'name']);
        $tournamentRentals = CourtTournamentRental::query()
            ->with('court:id,name')
            ->orderByDesc('rental_date')
            ->orderByDesc('time_start')
            ->orderByDesc('id')
            ->get();
        $dailyOutstandingBalance = (float) $dailyConfirmedBookings->sum(function ($booking): float {
            $paid = (float) $booking->payments
                ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
                ->sum(fn ($payment) => $payment->recognized_amount);
            $refunded = (float) $booking->fundSettlements
                ->where('type', CourtBookingFundSettlement::TYPE_REFUND)
                ->whereNull('voided_at')
                ->sum('amount');

            return max(0, (float) $booking->amount - max(0, $paid - $refunded));
        });

        return view('admin.court-bookings.daily-report', compact(
            'reportDate',
            'dailyBookings',
            'dailyMatches',
            'dailyRevenue',
            'dailyRevenueEntries',
            'dailyBookedMinutes',
            'dailyPendingMinutes',
            'dailyParkingIncome',
            'dailyShopIncome',
            'dailyOtherIncome',
            'dailyBookingPayments',
            'dailyBookingRefunds',
            'dailyUnresolvedCancellationFunds',
            'dailyBookingIncome',
            'dailyRentalAdditionIncome',
            'dailyStandaloneRentalIncome',
            'dailyTotalIncome',
            'dailyIncomeByPaymentMethod',
            'dailyOutstandingBalance',
            'dailyReconciliation',
            'reportMonthStart',
            'reportMonthEnd',
            'tournaments',
            'courts',
            'tournamentRentals',
            'dailyTournamentRentalIncome',
        ));
    }

    public function reviewDailyReport(Request $request)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'operating_date' => ['required', 'date_format:Y-m-d'],
        ]);
        $operatingDate = $validated['operating_date'];
        $today = CarbonImmutable::today(CourtBooking::TIMEZONE);

        if (CarbonImmutable::parse($operatingDate, CourtBooking::TIMEZONE)->greaterThan($today)) {
            throw ValidationException::withMessages([
                'operating_date' => 'Laporan tanggal mendatang belum dapat dicocokkan.',
            ]);
        }

        if (! CourtDailyRevenue::query()->where('operating_date', $operatingDate)->exists()) {
            throw ValidationException::withMessages([
                'operating_date' => 'Simpan catatan parkir dan toko terlebih dahulu. Jika tidak ada pemasukan, simpan nilai Rp 0.',
            ]);
        }

        $progress = $this->dailyReportReconciliationProgress($operatingDate, $operatingDate, $today);
        $day = $progress['days'][0] ?? null;
        abort_unless($day, 404);

        $now = now();
        DB::table('court_daily_report_reviews')->upsert([[
            'operating_date' => $operatingDate,
            'snapshot_hash' => $day['snapshot_hash'],
            'reviewed_by' => auth()->id(),
            'reviewed_by_name_snapshot' => auth()->user()?->name,
            'reviewed_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]], ['operating_date'], [
            'snapshot_hash',
            'reviewed_by',
            'reviewed_by_name_snapshot',
            'reviewed_at',
            'updated_at',
        ]);

        return redirect()->route('admin.court-bookings.reports.daily', ['date' => $operatingDate])
            ->with('success', 'Laporan harian ditandai sudah dicocokkan. Jika datanya berubah, statusnya otomatis meminta pemeriksaan ulang.');
    }

    /**
     * Build month rows and a data fingerprint so edits made after reconciliation
     * are visible instead of leaving a stale "complete" marker.
     */
    private function dailyReportReconciliationProgress(string $fromDate, string $toDate, CarbonImmutable $today): array
    {
        $bookingsByDate = CourtBooking::query()
            ->with(['payments', 'rentalAdditions', 'fundSettlements'])
            ->whereBetween('booking_date', [$fromDate, $toDate])
            ->orderBy('booking_date')
            ->orderBy('id')
            ->get()
            ->groupBy(fn (CourtBooking $booking): string => $booking->booking_date->toDateString());
        $rentalOrdersByDate = CourtRentalOrder::query()
            ->with('lines')
            ->whereBetween('rental_date', [$fromDate, $toDate])
            ->orderBy('rental_date')
            ->orderBy('id')
            ->get()
            ->groupBy(fn (CourtRentalOrder $order): string => $order->rental_date->toDateString());
        $dailyRevenuesByDate = CourtDailyRevenue::query()
            ->whereBetween('operating_date', [$fromDate, $toDate])
            ->get()
            ->keyBy(fn (CourtDailyRevenue $revenue): string => $revenue->operating_date->toDateString());
        $revenueEntriesByDate = CourtDailyRevenueEntry::withTrashed()
            ->whereBetween('operating_date', [$fromDate, $toDate])
            ->orderBy('operating_date')
            ->orderBy('id')
            ->get()
            ->groupBy(fn (CourtDailyRevenueEntry $entry): string => $entry->operating_date->toDateString());
        $reviewsByDate = CourtDailyReportReview::query()
            ->with('reviewedBy')
            ->whereBetween('operating_date', [$fromDate, $toDate])
            ->get()
            ->keyBy(fn (CourtDailyReportReview $review): string => $review->operating_date->toDateString());

        $days = [];
        $start = CarbonImmutable::parse($fromDate, CourtBooking::TIMEZONE)->startOfDay();
        $end = CarbonImmutable::parse($toDate, CourtBooking::TIMEZONE)->startOfDay();

        for ($date = $start; $date->lessThanOrEqualTo($end); $date = $date->addDay()) {
            $dateKey = $date->toDateString();
            $bookings = $bookingsByDate->get($dateKey, collect());
            $rentalOrders = $rentalOrdersByDate->get($dateKey, collect());
            $dailyRevenue = $dailyRevenuesByDate->get($dateKey);
            $allOtherEntries = $revenueEntriesByDate->get($dateKey, collect());
            $activeRevenueEntries = $allOtherEntries->whereNull('deleted_at');
            $activeTournamentRentalEntries = $activeRevenueEntries->whereNotNull('court_tournament_rental_id');
            $activeOtherEntries = $activeRevenueEntries->whereNull('court_tournament_rental_id');
            $review = $reviewsByDate->get($dateKey);

            $bookingIncome = 0.0;
            foreach ($bookings as $booking) {
                if ($booking->status === CourtBooking::STATUS_CONFIRMED) {
                    $bookingIncome += (float) $booking->payments
                        ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
                        ->sum(fn ($payment) => $payment->recognized_amount);
                    $bookingIncome += (float) $booking->rentalAdditions
                        ->whereNotNull('confirmed_at')
                        ->sum('amount');
                    $bookingIncome -= (float) $booking->fundSettlements
                        ->where('type', CourtBookingFundSettlement::TYPE_REFUND)
                        ->whereNull('voided_at')
                        ->sum('amount');
                } elseif ($booking->status === CourtBooking::STATUS_CANCELLED) {
                    $bookingIncome += (float) $booking->fundSettlements
                        ->where('type', CourtBookingFundSettlement::TYPE_RETAINED)
                        ->whereNull('voided_at')
                        ->sum('amount');
                }
            }
            $bookingIncome += (float) $activeTournamentRentalEntries->sum('amount');

            $snapshot = [
                'bookings' => $bookings->map(fn (CourtBooking $booking): array => $booking->toArray())->values()->all(),
                'rental_orders' => $rentalOrders->map(fn (CourtRentalOrder $order): array => $order->toArray())->values()->all(),
                'daily_revenue' => $dailyRevenue?->toArray(),
                'other_revenue_entries' => $allOtherEntries->map(fn (CourtDailyRevenueEntry $entry): array => $entry->toArray())->values()->all(),
            ];
            $snapshotHash = hash('sha256', json_encode(
                $snapshot,
                JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR,
            ));

            $isFuture = $date->greaterThan($today);
            $isCurrent = $review && hash_equals((string) $review->snapshot_hash, $snapshotHash);
            $status = $isFuture ? 'upcoming' : ($isCurrent ? 'matched' : ($review ? 'changed' : 'unchecked'));

            $days[] = [
                'date' => $dateKey,
                'label' => $date->locale('id')->translatedFormat('d M Y'),
                'booking_count' => $bookings->count(),
                'booking_income' => round($bookingIncome, 2),
                'rental_income' => round((float) $rentalOrders
                    ->where('status', CourtRentalOrder::STATUS_CONFIRMED)
                    ->sum('amount'), 2),
                'parking_income' => round((float) ($dailyRevenue?->parking_income ?? 0), 2),
                'shop_income' => round((float) ($dailyRevenue?->shop_income ?? 0), 2),
                'has_daily_revenue' => (bool) $dailyRevenue,
                'other_income' => round((float) $activeOtherEntries->sum('amount'), 2),
                'other_income_count' => $activeOtherEntries->count(),
                'status' => $status,
                'snapshot_hash' => $snapshotHash,
                'reviewed_at' => $review?->reviewed_at,
                'reviewed_by' => $review?->reviewedBy?->name ?? $review?->reviewed_by_name_snapshot,
            ];
        }

        $dueDays = collect($days)->where('status', '!=', 'upcoming');
        $reviewedDays = $dueDays->where('status', 'matched')->count();
        $totalDueDays = $dueDays->count();

        return [
            'days' => $days,
            'reviewed_days' => $reviewedDays,
            'total_due_days' => $totalDueDays,
            'remaining_days' => max(0, $totalDueDays - $reviewedDays),
            'percentage' => $totalDueDays > 0 ? (int) round($reviewedDays / $totalDueDays * 100) : 0,
        ];
    }

    public function teamHoursReport(Request $request)
    {
        $this->ensureAdmin();
        $today = CarbonImmutable::today(CourtBooking::TIMEZONE);
        $currentYear = (int) $today->format('Y');
        $filters = $request->validate([
            'year' => ['nullable', 'integer', 'min:2000', 'max:'.$currentYear],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
        ]);
        $selectedYear = filled($filters['year'] ?? null) ? (int) $filters['year'] : null;
        $fromDate = $filters['from'] ?? null;
        $toDate = $filters['to'] ?? $today->toDateString();
        if ($selectedYear !== null) {
            $fromDate = CarbonImmutable::create($selectedYear, 1, 1, 0, 0, 0, CourtBooking::TIMEZONE)->toDateString();
            $toDate = CarbonImmutable::create($selectedYear, 12, 31, 0, 0, 0, CourtBooking::TIMEZONE)->toDateString();
        }
        if ($fromDate && $toDate < $fromDate) {
            throw ValidationException::withMessages([
                'to' => 'Tanggal akhir harus sama atau sesudah tanggal awal.',
            ]);
        }

        $earliestBookingDate = CourtBooking::query()
            ->where('status', CourtBooking::STATUS_CONFIRMED)
            ->whereNotNull('team_name')
            ->whereRaw("TRIM(team_name) <> ''")
            ->min('booking_date');
        $earliestTournamentRentalDate = CourtTournamentRental::query()
            ->where('status', '!=', CourtTournamentRental::STATUS_CANCELLED)
            ->min('rental_date');
        $firstYearWithActivity = collect([$earliestBookingDate, $earliestTournamentRentalDate])
            ->filter()
            ->map(fn ($date) => (int) CarbonImmutable::parse($date, CourtBooking::TIMEZONE)->format('Y'))
            ->min() ?? $currentYear;
        $firstYearOption = min($firstYearWithActivity, $currentYear);
        $yearOptions = collect(range($currentYear, $firstYearOption));

        $bookingGroups = CourtBooking::query()
            ->where('status', CourtBooking::STATUS_CONFIRMED)
            ->whereNotNull('team_name')
            ->whereRaw("TRIM(team_name) <> ''")
            ->when($fromDate, fn ($query) => $query->whereDate('booking_date', '>=', $fromDate))
            ->whereDate('booking_date', '<=', $toDate)
            ->selectRaw('LOWER(TRIM(team_name)) as team_key, MIN(TRIM(team_name)) as team_name, COUNT(*) as booking_count, COALESCE(SUM(duration_minutes), 0) as booking_minutes')
            ->groupBy('team_key')
            ->get();

        $tournamentRentalGroups = CourtTournamentRental::query()
            ->where('status', '!=', CourtTournamentRental::STATUS_CANCELLED)
            ->when($fromDate, fn ($query) => $query->whereDate('rental_date', '>=', $fromDate))
            ->whereDate('rental_date', '<=', $toDate)
            ->selectRaw('LOWER(TRIM(tournament_name_snapshot)) as team_key, MIN(TRIM(tournament_name_snapshot)) as team_name, COUNT(*) as tournament_rental_count, COALESCE(SUM(duration_minutes), 0) as tournament_rental_minutes')
            ->groupBy('team_key')
            ->get();

        $normalizeTeamName = static function (string $name): string {
            $name = trim($name);
            $normalized = preg_replace('/\\s+/u', ' ', $name);

            return mb_strtolower($normalized ?? $name, 'UTF-8');
        };
        $teamTotals = [];
        foreach ($bookingGroups as $bookingGroup) {
            $key = $normalizeTeamName((string) $bookingGroup->team_name);
            if ($key === '') {
                continue;
            }

            $displayName = trim((string) $bookingGroup->team_name);
            $teamTotals[$key] ??= [
                'team_name' => $displayName,
                'booking_count' => 0,
                'booking_minutes' => 0,
                'tournament_rental_count' => 0,
                'tournament_rental_minutes' => 0,
                'total_minutes' => 0,
            ];
            if (strcasecmp($displayName, $teamTotals[$key]['team_name']) < 0) {
                $teamTotals[$key]['team_name'] = $displayName;
            }
            $teamTotals[$key]['booking_count'] += (int) $bookingGroup->booking_count;
            $teamTotals[$key]['booking_minutes'] += (int) $bookingGroup->booking_minutes;
            $teamTotals[$key]['total_minutes'] += (int) $bookingGroup->booking_minutes;
        }

        foreach ($tournamentRentalGroups as $rentalGroup) {
            $key = $normalizeTeamName((string) $rentalGroup->team_name);
            if ($key === '') {
                continue;
            }

            $displayName = trim((string) $rentalGroup->team_name);
            $teamTotals[$key] ??= [
                'team_name' => $displayName,
                'booking_count' => 0,
                'booking_minutes' => 0,
                'tournament_rental_count' => 0,
                'tournament_rental_minutes' => 0,
                'total_minutes' => 0,
            ];
            if (strcasecmp($displayName, $teamTotals[$key]['team_name']) < 0) {
                $teamTotals[$key]['team_name'] = $displayName;
            }
            $teamTotals[$key]['tournament_rental_count'] += (int) $rentalGroup->tournament_rental_count;
            $teamTotals[$key]['tournament_rental_minutes'] += (int) $rentalGroup->tournament_rental_minutes;
            $teamTotals[$key]['total_minutes'] += (int) $rentalGroup->tournament_rental_minutes;
        }

        $teamRows = collect(array_values($teamTotals))
            ->sort(function (array $left, array $right): int {
                return ($right['total_minutes'] <=> $left['total_minutes'])
                    ?: strcasecmp($left['team_name'], $right['team_name']);
            })
            ->values();
        $teamCount = $teamRows->count();
        $confirmedBookingCount = (int) $teamRows->sum('booking_count');
        $periodTournamentRentalCount = (int) $teamRows->sum('tournament_rental_count');
        $periodTournamentRentalMinutes = (int) $teamRows->sum('tournament_rental_minutes');
        $totalTeamMinutes = (int) $teamRows->sum('total_minutes');
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pageTeamRows = $teamRows
            ->forPage($currentPage, 30)
            ->map(static fn (array $team): object => (object) $team)
            ->values();
        $teams = new LengthAwarePaginator(
            $pageTeamRows,
            $teamRows->count(),
            30,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()],
        );

        $tournamentRentalRows = CourtTournamentRental::query()
            ->with(['court:id,name', 'tournament:id,name'])
            ->orderByDesc('rental_date')
            ->orderByDesc('time_start')
            ->orderByDesc('id');
        $activeTournamentRentals = (clone $tournamentRentalRows)
            ->where('status', '!=', CourtTournamentRental::STATUS_CANCELLED);
        $tournamentRentalCount = (clone $activeTournamentRentals)->count();
        $tournamentRentalMinutes = (int) (clone $activeTournamentRentals)->sum('duration_minutes');
        $tournamentRentals = $tournamentRentalRows
            ->paginate(20, ['*'], 'tournament_page')
            ->withQueryString();
        $currentTime = CarbonImmutable::now(CourtBooking::TIMEZONE);
        $tournamentRentals->getCollection()->transform(function (CourtTournamentRental $rental) use ($currentTime) {
            $displayStatus = $rental->status;
            if ($displayStatus === CourtTournamentRental::STATUS_SCHEDULED) {
                $startAt = CarbonImmutable::parse($rental->rental_date->toDateString().' '.$rental->time_start, CourtBooking::TIMEZONE);
                $endAt = CarbonImmutable::parse($rental->rental_date->toDateString().' '.$rental->time_end, CourtBooking::TIMEZONE);
                if (!$endAt->greaterThan($startAt)) {
                    $endAt = $endAt->addDay();
                }
                if ($endAt->lessThanOrEqualTo($currentTime)) {
                    $displayStatus = CourtTournamentRental::STATUS_COMPLETED;
                }
            }
            $rental->setAttribute('display_status', $displayStatus);

            return $rental;
        });

        return view('admin.court-bookings.team-hours', [
            'teams' => $teams,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'selectedYear' => $selectedYear,
            'yearOptions' => $yearOptions,
            'courts' => Court::query()->orderBy('sort_order')->orderBy('name')->get(['id', 'name']),
            'tournaments' => Tournament::query()->orderByDesc('start_date')->orderBy('name')->get(['id', 'name', 'start_date']),
            'tournamentRentals' => $tournamentRentals,
            'tournamentRentalCount' => $tournamentRentalCount,
            'tournamentRentalMinutes' => $tournamentRentalMinutes,
            'teamCount' => (int) $teamCount,
            'confirmedBookingCount' => $confirmedBookingCount,
            'periodTournamentRentalCount' => $periodTournamentRentalCount,
            'periodTournamentRentalMinutes' => $periodTournamentRentalMinutes,
            'totalTeamMinutes' => $totalTeamMinutes,
        ]);
    }

    public function storeTournamentRental(Request $request, CourtBookingAvailabilityService $availability)
    {
        $this->ensureAdmin();
        $rental = $this->persistTournamentRental(null, $this->validateTournamentRental($request), $availability);

        return back()->with('success', 'Sesi sewa turnamen '.$rental->tournament_name_snapshot.' berhasil dicatat. Durasi jeda termasuk dalam jam sewa.');
    }

    public function updateTournamentRental(
        Request $request,
        CourtTournamentRental $rental,
        CourtBookingAvailabilityService $availability,
    ) {
        $this->ensureAdmin();
        if ($rental->status === CourtTournamentRental::STATUS_CANCELLED) {
            throw ValidationException::withMessages(['tournament_rental' => 'Sesi sewa yang dibatalkan tidak dapat diedit. Catat sesi baru jika diperlukan.']);
        }

        $rental = $this->persistTournamentRental($rental, $this->validateTournamentRental($request), $availability);

        return back()->with('success', 'Sesi sewa turnamen berhasil diperbarui.');
    }

    public function cancelTournamentRental(Request $request, CourtTournamentRental $rental)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($rental, $validated): void {
            $lockedRental = CourtTournamentRental::query()->whereKey($rental->id)->lockForUpdate()->firstOrFail();
            if ($lockedRental->status === CourtTournamentRental::STATUS_CANCELLED) {
                return;
            }

            $lockedRental->update([
                'status' => CourtTournamentRental::STATUS_CANCELLED,
                'cancelled_by' => auth()->id(),
                'cancelled_at' => CarbonImmutable::now(CourtBooking::TIMEZONE),
                'cancellation_reason' => trim($validated['cancellation_reason']),
                'updated_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Sesi sewa turnamen dibatalkan. Catatan tetap tersimpan dan slotnya dilepas.');
    }

    private function validateTournamentRental(Request $request): array
    {
        return $request->validate([
            'court_id' => ['required', 'integer', Rule::exists('courts', 'id')],
            'tournament_id' => ['nullable', 'integer', Rule::exists('tournaments', 'id')],
            'tournament_name_snapshot' => ['nullable', 'required_without:tournament_id', 'string', 'max:150'],
            'rental_date' => ['required', 'date_format:Y-m-d'],
            'time_start' => ['required', 'date_format:H:i'],
            'time_end' => ['required', 'date_format:H:i'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ], [
            'tournament_name_snapshot.required_without' => 'Pilih turnamen atau isi nama turnamen.',
        ]);
    }

    private function persistTournamentRental(
        ?CourtTournamentRental $rental,
        array $validated,
        CourtBookingAvailabilityService $availability,
    ): CourtTournamentRental {
        return DB::transaction(function () use ($rental, $validated, $availability): CourtTournamentRental {
            $court = $availability->lockCourt((int) $validated['court_id']);
            if (!$court) {
                throw ValidationException::withMessages(['court_id' => 'Lapangan tidak ditemukan.']);
            }

            $lockedRental = $rental
                ? CourtTournamentRental::query()->whereKey($rental->id)->lockForUpdate()->firstOrFail()
                : new CourtTournamentRental;
            if ($lockedRental->exists && $lockedRental->status === CourtTournamentRental::STATUS_CANCELLED) {
                throw ValidationException::withMessages(['tournament_rental' => 'Sesi sewa yang dibatalkan tidak dapat diedit.']);
            }

            $startAt = CarbonImmutable::createFromFormat(
                'Y-m-d H:i',
                $validated['rental_date'].' '.$validated['time_start'],
                CourtBooking::TIMEZONE,
            );
            $endAt = CarbonImmutable::createFromFormat(
                'Y-m-d H:i',
                $validated['rental_date'].' '.$validated['time_end'],
                CourtBooking::TIMEZONE,
            );
            if (!$startAt || !$endAt) {
                throw ValidationException::withMessages(['time_end' => 'Tanggal dan jam sewa tidak valid.']);
            }
            if (!$endAt->greaterThan($startAt)) {
                $endAt = $endAt->addDay();
            }

            $durationMinutes = intdiv($endAt->getTimestamp() - $startAt->getTimestamp(), 60);
            if ($durationMinutes < 30 || $durationMinutes > 1440 || $durationMinutes % 30 !== 0) {
                throw ValidationException::withMessages(['time_end' => 'Durasi sewa harus kelipatan 30 menit dan maksimal 24 jam.']);
            }

            $openingAt = CarbonImmutable::parse($validated['rental_date'].' '.$court->opening_time, CourtBooking::TIMEZONE);
            $closingAt = CarbonImmutable::parse($validated['rental_date'].' '.$court->closing_time, CourtBooking::TIMEZONE);
            if (!$closingAt->greaterThan($openingAt)) {
                $closingAt = $closingAt->addDay();
            }
            if ($startAt->lessThan($openingAt) || $endAt->greaterThan($closingAt)) {
                throw ValidationException::withMessages([
                    'time_start' => 'Rentang sewa harus berada di dalam jam operasional lapangan.',
                ]);
            }

            $tournament = filled($validated['tournament_id'] ?? null)
                ? Tournament::query()->findOrFail((int) $validated['tournament_id'])
                : null;
            $tournamentId = $tournament?->id;
            $tournamentName = $tournament?->name ?: trim((string) ($validated['tournament_name_snapshot'] ?? ''));
            if ($tournamentName === '') {
                throw ValidationException::withMessages(['tournament_name_snapshot' => 'Nama turnamen wajib diisi.']);
            }

            $date = $validated['rental_date'];
            $start = $validated['time_start'].':00';
            $end = $validated['time_end'].':00';
            if ($availability->bookingConflict($court, $date, $start, $end)) {
                throw ValidationException::withMessages(['time_start' => 'Rentang sewa bertabrakan dengan booking lapangan yang sudah tercatat.']);
            }
            if ($availability->memberScheduleConflict($court, $date, $start, $end)) {
                throw ValidationException::withMessages(['time_start' => 'Rentang sewa bertabrakan dengan jadwal member.']);
            }
            if ($availability->matchConflict($court, $date, $start, $end, $tournamentId)) {
                throw ValidationException::withMessages(['time_start' => 'Rentang sewa bertabrakan dengan pertandingan lain di lapangan ini.']);
            }
            if ($availability->tournamentRentalConflict(
                $court,
                $date,
                $start,
                $end,
                $lockedRental->exists ? (int) $lockedRental->id : null,
            )) {
                throw ValidationException::withMessages(['time_start' => 'Rentang sewa bertabrakan dengan sesi sewa turnamen lain.']);
            }

            $this->assertTournamentMatchesFitRental($tournamentId, $court, $startAt, $endAt);

            $lockedRental->fill([
                'court_id' => $court->id,
                'tournament_id' => $tournamentId,
                'tournament_name_snapshot' => $tournamentName,
                'rental_date' => $date,
                'time_start' => $start,
                'time_end' => $end,
                'duration_minutes' => $durationMinutes,
                'status' => $endAt->lessThanOrEqualTo(CarbonImmutable::now(CourtBooking::TIMEZONE))
                    ? CourtTournamentRental::STATUS_COMPLETED
                    : CourtTournamentRental::STATUS_SCHEDULED,
                'admin_note' => filled($validated['admin_note'] ?? null) ? trim($validated['admin_note']) : null,
                'created_by' => $lockedRental->exists ? $lockedRental->created_by : auth()->id(),
                'updated_by' => auth()->id(),
                'cancelled_by' => null,
                'cancelled_at' => null,
                'cancellation_reason' => null,
            ])->save();

            return $lockedRental;
        }, 3);
    }

    private function assertTournamentMatchesFitRental(
        ?int $tournamentId,
        Court $court,
        CarbonImmutable $rentalStart,
        CarbonImmutable $rentalEnd,
    ): void {
        if (!$tournamentId) {
            return;
        }

        $matches = Game::query()
            ->where('tournament_id', $tournamentId)
            ->whereRaw('LOWER(TRIM(venue)) = ?', [mb_strtolower(trim($court->name))])
            ->where('is_cancelled', false)
            ->where('status', '!=', 'postponed')
            ->whereDate('match_date', '>=', $rentalStart->toDateString())
            ->whereDate('match_date', '<=', $rentalEnd->toDateString())
            ->whereNotNull('time_start')
            ->whereNotNull('time_end')
            ->get(['match_date', 'time_start', 'time_end']);

        foreach ($matches as $match) {
            $matchStart = CarbonImmutable::parse($match->match_date->toDateString().' '.$match->time_start, CourtBooking::TIMEZONE);
            $matchEnd = CarbonImmutable::parse($match->match_date->toDateString().' '.$match->time_end, CourtBooking::TIMEZONE);
            if (!$matchEnd->greaterThan($matchStart)) {
                $matchEnd = $matchEnd->addDay();
            }

            if ($matchStart->lessThan($rentalEnd) && $matchEnd->greaterThan($rentalStart)
                && ($matchStart->lessThan($rentalStart) || $matchEnd->greaterThan($rentalEnd))) {
                throw ValidationException::withMessages([
                    'time_start' => 'Rentang sewa harus mencakup jadwal pertandingan '.$matchStart->format('H:i').'–'.$matchEnd->format('H:i').'.',
                ]);
            }
        }
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

    public function memberSchedules()
    {
        $this->ensureAdmin();
        $courts = Court::query()->orderBy('sort_order')->orderBy('name')->get();
        $memberSchedules = CourtMemberSchedule::query()
            ->with(['court', 'daysOff' => fn ($query) => $query->whereDate('off_date', '>=', now(CourtBooking::TIMEZONE)->toDateString())->orderBy('off_date')])
            ->orderBy('court_id')->orderBy('day_of_week')->orderBy('time_start')->get();
        $memberScheduleGroups = $memberSchedules->groupBy(fn (CourtMemberSchedule $schedule) => mb_strtolower(
            preg_replace('/\\s+/u', ' ', trim($schedule->member_name)),
        ))->sortKeys(SORT_NATURAL | SORT_FLAG_CASE);
        $memberNames = $memberScheduleGroups
            ->map(fn ($schedules) => trim($schedules->first()->member_name))
            ->values();

        return view('admin.court-bookings.member-schedules', compact(
            'courts',
            'memberSchedules',
            'memberNames',
            'memberScheduleGroups',
        ));
    }

    public function createMemberBooking(
        Request $request,
        CourtMemberSchedule $schedule,
        CourtBookingAvailabilityService $availability,
        CourtPricingService $pricing,
    ) {
        $this->ensureAdmin();
        $schedule->loadMissing(['court', 'daysOff']);

        $today = CarbonImmutable::today(CourtBooking::TIMEZONE);
        $minimumDate = $today->subDays(30);
        $maximumDate = $today->addDays(60);
        $daysUntilSchedule = ((int) $schedule->day_of_week - $today->dayOfWeek + 7) % 7;
        $defaultDate = $today->addDays($daysUntilSchedule)->toDateString();
        $query = $request->validate(['date' => ['nullable', 'date_format:Y-m-d']]);
        $date = $query['date'] ?? $defaultDate;
        $dateError = null;
        $occurrence = null;
        $amount = null;
        $booking = null;

        if ($date < $minimumDate->toDateString() || $date > $maximumDate->toDateString()) {
            $dateError = 'Tanggal transaksi member dibatasi dari 30 hari yang lalu sampai 60 hari mendatang.';
        } else {
            $booking = $this->activeMemberOccurrence($schedule, $date);
            if (!$booking) {
                try {
                    $occurrence = $this->assertMemberScheduleOccurrence($schedule, $date, $availability);
                    $amount = $pricing->amountForRange(
                        $schedule->court,
                        $occurrence['time_start'],
                        $occurrence['duration_minutes'],
                        $date,
                    );
                } catch (ValidationException $exception) {
                    $dateError = collect($exception->errors())->flatten()->first();
                }
            }
        }

        return view('admin.court-bookings.member-booking-create', [
            'schedule' => $schedule,
            'date' => $date,
            'todayDate' => $today->toDateString(),
            'minimumDate' => $minimumDate->toDateString(),
            'maximumDate' => $maximumDate->toDateString(),
            'occurrence' => $occurrence,
            'amount' => $amount,
            'booking' => $booking,
            'dateError' => $dateError,
            'weekdayLabel' => ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][(int) $schedule->day_of_week] ?? 'Hari tidak diketahui',
            'requestKey' => (string) Str::uuid(),
            'isBackdated' => $date < $today->toDateString(),
        ]);
    }

    public function storeMemberBooking(
        Request $request,
        CourtMemberSchedule $schedule,
        CourtBookingAvailabilityService $availability,
        CourtPricingService $pricing,
        CourtBookingDiscountService $discounts,
    ) {
        $this->ensureAdmin();

        $today = CarbonImmutable::today(CourtBooking::TIMEZONE);
        $minimumDate = $today->subDays(30)->toDateString();
        $maximumDate = $today->addDays(60)->toDateString();
        $requestedDate = $request->input('booking_date');
        $isBackdated = is_string($requestedDate)
            && preg_match('/\A\d{4}-\d{2}-\d{2}\z/', $requestedDate) === 1
            && $requestedDate < $today->toDateString();

        $validated = $request->validate([
            'booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:'.$minimumDate, 'before_or_equal:'.$maximumDate],
            'member_booking_request_key' => ['required', 'uuid'],
            'amount_received' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'discount_type' => ['nullable', Rule::in(['none', 'fixed', 'percentage'])],
            'discount_value' => ['nullable', 'string', 'max:30'],
            'discount_reason' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', Rule::in(['none', 'cash', 'bank_transfer', 'qris'])],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
            'backdate_reason' => [$isBackdated ? 'required' : 'nullable', 'string', 'max:500'],
        ], [
            'booking_date.after_or_equal' => 'Tanggal pencatatan member maksimal 30 hari yang lalu.',
            'backdate_reason.required' => 'Alasan pencatatan tanggal lampau wajib diisi.',
        ]);

        $amountReceived = round((float) ($validated['amount_received'] ?? 0), 2);
        if ($amountReceived > 0 && $validated['payment_method'] === 'none') {
            throw ValidationException::withMessages(['payment_method' => 'Pilih metode pembayaran jika ada nominal yang diterima.']);
        }

        $fingerprint = hash('sha256', json_encode([
            'type' => 'member_schedule_booking',
            'schedule_id' => (int) $schedule->id,
            'booking_date' => $validated['booking_date'],
            'amount_received' => $amountReceived,
            'discount_type' => $validated['discount_type'] ?? 'none',
            'discount_value' => $discounts->normalizedValue($validated['discount_type'] ?? 'none', $validated['discount_value'] ?? ''),
            'discount_reason' => trim((string) ($validated['discount_reason'] ?? '')),
            'payment_method' => $validated['payment_method'],
            'payment_reference' => $validated['payment_reference'] ?? null,
            'admin_note' => $validated['admin_note'] ?? null,
            'backdate_reason' => $isBackdated ? trim($validated['backdate_reason']) : null,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $previousRequest = CourtBooking::query()
            ->where('idempotency_key', $validated['member_booking_request_key'])
            ->first();
        if ($previousRequest) {
            if (!hash_equals((string) $previousRequest->request_fingerprint, $fingerprint)) {
                throw ValidationException::withMessages(['booking_date' => 'Kunci transaksi ini sudah dipakai untuk data berbeda. Muat ulang halaman dan coba lagi.']);
            }

            return redirect()->route('admin.court-bookings.index', ['date' => $previousRequest->booking_date->toDateString()])
                ->with('success', 'Transaksi member ini sudah tercatat sebelumnya. Tidak ada transaksi duplikat yang dibuat.');
        }

        $booking = DB::transaction(function () use ($schedule, $validated, $availability, $pricing, $discounts, $amountReceived, $fingerprint, $isBackdated): CourtBooking {
            $lockedCourt = $availability->lockCourt((int) $schedule->court_id);
            if (!$lockedCourt) {
                throw ValidationException::withMessages(['booking_date' => 'Lapangan pada jadwal member ini sudah tidak tersedia.']);
            }

            $lockedSchedule = CourtMemberSchedule::query()
                ->whereKey($schedule->id)
                ->lockForUpdate()
                ->firstOrFail();
            if ((int) $lockedSchedule->court_id !== (int) $lockedCourt->id) {
                throw ValidationException::withMessages(['booking_date' => 'Jadwal member berubah. Muat ulang halaman sebelum mencatat transaksi.']);
            }

            $existingRequest = CourtBooking::query()
                ->where('idempotency_key', $validated['member_booking_request_key'])
                ->lockForUpdate()
                ->first();
            if ($existingRequest) {
                if (!hash_equals((string) $existingRequest->request_fingerprint, $fingerprint)) {
                    throw ValidationException::withMessages(['booking_date' => 'Kunci transaksi ini sudah dipakai untuk data berbeda. Muat ulang halaman dan coba lagi.']);
                }

                return $existingRequest;
            }

            if ($this->activeMemberOccurrence($lockedSchedule, $validated['booking_date'])) {
                throw ValidationException::withMessages(['booking_date' => 'Transaksi untuk jadwal member pada tanggal ini sudah pernah dicatat. Buka booking yang sudah ada untuk melihat pembayaran.']);
            }

            $occurrence = $this->assertMemberScheduleOccurrence($lockedSchedule, $validated['booking_date'], $availability);
            $courtAmount = $pricing->amountForRange(
                $lockedCourt,
                $occurrence['time_start'],
                $occurrence['duration_minutes'],
                $validated['booking_date'],
            );
            $discount = $discounts->forCourtAmount(
                $validated['discount_type'] ?? 'none',
                $validated['discount_value'] ?? '',
                $validated['discount_reason'] ?? '',
                (float) $courtAmount,
            );
            $subtotal = round((float) $courtAmount, 2);
            $amount = round(max(0, $subtotal - $discount['amount']), 2);
            if ($amountReceived > $amount) {
                throw ValidationException::withMessages([
                    'amount_received' => 'Nominal diterima tidak boleh melebihi total tagihan Rp '.number_format($amount, 0, ',', '.').'.',
                ]);
            }

            // Persist payment and confirmation timestamps in UTC, like the other booking flows.
            $now = now();
            $booking = CourtBooking::query()->create([
                'public_token' => (string) Str::uuid(),
                'idempotency_key' => $validated['member_booking_request_key'],
                'request_fingerprint' => $fingerprint,
                'court_id' => $lockedCourt->id,
                'court_member_schedule_id' => $lockedSchedule->id,
                'booking_date' => $validated['booking_date'],
                'time_start' => $occurrence['time_start'].':00',
                'time_end' => $occurrence['time_end'].':00',
                'duration_minutes' => $occurrence['duration_minutes'],
                'amount' => $amount,
                'subtotal_amount' => $subtotal,
                'discount_type' => $discount['type'],
                'discount_value' => $discount['value'],
                'discount_amount' => $discount['amount'],
                'discount_reason' => $discount['reason'],
                'team_name' => trim($lockedSchedule->member_name),
                'customer_name' => trim($lockedSchedule->member_name),
                'customer_phone' => null,
                'customer_email' => null,
                'payment_method' => $amountReceived > 0 ? $validated['payment_method'] : 'none',
                'source' => 'member',
                'created_by' => auth()->id(),
                'payment_reference' => $amountReceived > 0 ? ($validated['payment_reference'] ?? null) : null,
                'payment_instructions_snapshot' => $amountReceived > 0 ? 'Pembayaran member dicatat dan diverifikasi langsung oleh admin.' : null,
                'status' => CourtBooking::STATUS_CONFIRMED,
                'verified_by' => auth()->id(),
                'verified_at' => $now,
                'confirmed_at' => $now,
                'admin_note' => $validated['admin_note'] ?? null,
            ]);

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
                    'note' => $amountReceived >= $amount
                        ? 'Pembayaran booking member diterima lunas dan dicatat oleh admin.'
                        : 'DP booking member diterima dan dicatat oleh admin.',
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

        $confirmedPayments = (float) $booking->payments()
            ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
            ->sum('verified_amount');
        $balance = max(0, round((float) $booking->amount - $confirmedPayments, 2));
        $message = $confirmedPayments <= 0
            ? 'Booking member tercatat. Belum ada pembayaran yang diterima; sisa tagihan Rp '.number_format($balance, 0, ',', '.').'.'
            : ($balance > 0
                ? 'Booking member tercatat. Pembayaran Rp '.number_format($confirmedPayments, 0, ',', '.').' diterima; sisa tagihan Rp '.number_format($balance, 0, ',', '.').'.'
                : 'Booking member tercatat dan sudah lunas.');

        return redirect()->route('admin.court-bookings.index', ['date' => $booking->booking_date->toDateString()])
            ->with('success', $message);
    }

    private function activeMemberOccurrence(CourtMemberSchedule $schedule, string $date): ?CourtBooking
    {
        return CourtBooking::query()
            ->where('court_member_schedule_id', $schedule->id)
            ->whereDate('booking_date', $date)
            ->where(function ($query): void {
                $query->whereIn('status', [CourtBooking::STATUS_PENDING_REVIEW, CourtBooking::STATUS_CONFIRMED])
                    ->orWhere(function ($awaiting) {
                        $awaiting->where('status', CourtBooking::STATUS_AWAITING_PAYMENT)
                            ->where('expires_at', '>', now());
                    });
            })
            ->with('payments')
            ->latest('id')
            ->first();
    }

    private function assertMemberScheduleOccurrence(
        CourtMemberSchedule $schedule,
        string $date,
        CourtBookingAvailabilityService $availability,
    ): array {
        $day = CarbonImmutable::parse($date, CourtBooking::TIMEZONE);
        if ($day->dayOfWeek !== (int) $schedule->day_of_week) {
            throw ValidationException::withMessages(['booking_date' => 'Tanggal yang dipilih bukan hari rutin jadwal member ini.']);
        }
        $occurrenceDate = $day->toDateString();
        $startsOn = $schedule->starts_on?->toDateString();
        $endsOn = $schedule->ends_on?->toDateString();
        if (($startsOn && $occurrenceDate < $startsOn) || ($endsOn && $occurrenceDate > $endsOn)) {
            $validity = collect([
                $schedule->starts_on ? 'mulai '.$schedule->starts_on->translatedFormat('d M Y') : null,
                $schedule->ends_on ? 'sampai '.$schedule->ends_on->translatedFormat('d M Y') : null,
            ])->filter()->implode(' ');
            $validityText = $validity !== '' ? ' Jadwal ini berlaku '.$validity.'.' : '';

            throw ValidationException::withMessages([
                'booking_date' => 'Tanggal yang dipilih tidak termasuk masa berlaku jadwal member.'.$validityText,
            ]);
        }
        if ($schedule->daysOff()->whereDate('off_date', $date)->exists()) {
            throw ValidationException::withMessages(['booking_date' => 'Tanggal ini sudah ditandai sebagai hari libur jadwal member.']);
        }
        if ($day->greaterThanOrEqualTo(CarbonImmutable::today(CourtBooking::TIMEZONE)) && !$schedule->is_active) {
            throw ValidationException::withMessages(['booking_date' => 'Jadwal member sedang dijeda, sehingga tanggal hari ini atau mendatang tidak dapat dicatat.']);
        }

        $timeStart = substr((string) $schedule->time_start, 0, 5);
        $timeEnd = substr((string) $schedule->time_end, 0, 5);
        $startAt = CarbonImmutable::parse($date.' '.$timeStart, CourtBooking::TIMEZONE);
        $endAt = CarbonImmutable::parse($date.' '.$timeEnd, CourtBooking::TIMEZONE);
        if ($endAt->lessThanOrEqualTo($startAt)) {
            $endAt = $endAt->addDay();
        }

        if ($availability->bookingConflict($schedule->court, $date, $timeStart, $timeEnd)) {
            throw ValidationException::withMessages(['booking_date' => 'Jadwal ini bentrok dengan booking lapangan yang sudah tercatat.']);
        }
        if ($availability->matchConflict($schedule->court, $date, $timeStart, $timeEnd)) {
            throw ValidationException::withMessages(['booking_date' => 'Jadwal ini bentrok dengan pertandingan atau sesi sewa turnamen.']);
        }
        $otherMember = $availability->memberScheduleNameForSlot(
            $schedule->court,
            $date,
            $timeStart,
            $timeEnd,
            (int) $schedule->id,
        );
        if ($otherMember !== null) {
            throw ValidationException::withMessages(['booking_date' => 'Jadwal ini juga bertabrakan dengan jadwal member '. $otherMember .'.']);
        }

        return [
            'time_start' => $timeStart,
            'time_end' => $timeEnd,
            'duration_minutes' => (int) (($endAt->getTimestamp() - $startAt->getTimestamp()) / 60),
        ];
    }

    public function storeMemberSchedule(Request $request)
    {
        $this->ensureAdmin();
        $initial = $this->validateMemberSchedule($request);
        DB::transaction(function () use ($request, $initial): void {
            Court::query()->whereKey($initial['court_id'])->lockForUpdate()->firstOrFail();
            CourtMemberSchedule::query()->create($this->validateMemberSchedule($request));
        }, 3);

        return back()->with('success', 'Jadwal member berhasil ditambahkan.');
    }

    public function updateMemberSchedule(Request $request, CourtMemberSchedule $schedule)
    {
        $this->ensureAdmin();
        try {
            $initial = $this->validateMemberSchedule($request, $schedule);
            DB::transaction(function () use ($request, $schedule, $initial): void {
                $courtIds = collect([(int) $schedule->court_id, (int) $initial['court_id']])
                    ->unique()
                    ->sort()
                    ->values();
                $lockedCourts = Court::query()
                    ->whereIn('id', $courtIds)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');
                if ($lockedCourts->count() !== $courtIds->count()) {
                    throw ValidationException::withMessages(['court_id' => 'Lapangan yang dipilih tidak lagi tersedia. Muat ulang halaman lalu coba lagi.']);
                }

                $lockedSchedule = CourtMemberSchedule::query()
                    ->whereKey($schedule->id)
                    ->lockForUpdate()
                    ->firstOrFail();
                if (!$courtIds->contains((int) $lockedSchedule->court_id)) {
                    throw ValidationException::withMessages(['court_id' => 'Jadwal berubah saat sedang diedit. Muat ulang halaman lalu coba lagi.']);
                }

                $validated = $this->validateMemberSchedule($request, $lockedSchedule);
                $today = CarbonImmutable::today(CourtBooking::TIMEZONE)->toDateString();
                $futureDayOffs = $lockedSchedule->daysOff()
                    ->whereDate('off_date', '>=', $today)
                    ->get(['off_date']);
                $hasInvalidDayOff = $futureDayOffs->contains(function (CourtMemberScheduleDayOff $dayOff) use ($validated): bool {
                    $offDate = $dayOff->off_date->toDateString();

                    return $dayOff->off_date->dayOfWeek !== (int) $validated['day_of_week']
                        || (isset($validated['starts_on']) && $offDate < $validated['starts_on'])
                        || (isset($validated['ends_on']) && $offDate > $validated['ends_on']);
                });
                if ($hasInvalidDayOff) {
                    throw ValidationException::withMessages(['day_of_week' => 'Hapus atau sesuaikan tanggal libur mendatang sebelum mengubah hari atau masa berlaku jadwal.']);
                }

                $validated['is_active'] = $request->boolean('is_active');
                $lockedSchedule->update($validated);
            }, 3);
        } catch (ValidationException $exception) {
            return back()
                ->withInput()
                ->withErrors($exception->errors(), 'memberSchedule'.$schedule->id)
                ->with('open_member_schedule_modal', $schedule->id);
        }

        return back()->with('success', 'Jadwal member berhasil diperbarui.');
    }

    public function destroyMemberSchedule(CourtMemberSchedule $schedule)
    {
        $this->ensureAdmin();
        DB::transaction(function () use ($schedule): void {
            Court::query()->whereKey($schedule->court_id)->lockForUpdate()->firstOrFail();
            CourtMemberSchedule::query()->whereKey($schedule->id)->lockForUpdate()->firstOrFail()->delete();
        }, 3);

        return back()->with('success', 'Jadwal member berhasil dihapus.');
    }

    public function storeMemberDayOff(Request $request, CourtMemberSchedule $schedule)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'off_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:'.CarbonImmutable::today(CourtBooking::TIMEZONE)->toDateString()],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
        DB::transaction(function () use ($schedule, $validated): void {
            Court::query()->whereKey($schedule->court_id)->lockForUpdate()->firstOrFail();
            $lockedSchedule = CourtMemberSchedule::query()->whereKey($schedule->id)->lockForUpdate()->firstOrFail();
            $day = CarbonImmutable::parse($validated['off_date'], CourtBooking::TIMEZONE);
            if ($day->dayOfWeek !== (int) $lockedSchedule->day_of_week) {
                throw ValidationException::withMessages(['off_date' => 'Tanggal libur harus berada pada hari rutin jadwal member ini.']);
            }
            $offDate = $day->toDateString();
            $startsOn = $lockedSchedule->starts_on?->toDateString();
            $endsOn = $lockedSchedule->ends_on?->toDateString();
            if (($startsOn && $offDate < $startsOn) || ($endsOn && $offDate > $endsOn)) {
                throw ValidationException::withMessages(['off_date' => 'Tanggal libur berada di luar masa berlaku jadwal.']);
            }

            CourtMemberScheduleDayOff::query()->updateOrCreate(
                ['court_member_schedule_id' => $lockedSchedule->id, 'off_date' => $validated['off_date']],
                ['note' => $validated['note'] ?? null],
            );
        }, 3);

        return back()->with('success', 'Tanggal libur member berhasil disimpan. Slot kembali tersedia pada tanggal tersebut.');
    }

    public function destroyMemberDayOff(
        CourtMemberSchedule $schedule,
        CourtMemberScheduleDayOff $dayOff,
        CourtBookingAvailabilityService $availability,
    )
    {
        $this->ensureAdmin();
        abort_unless((int) $dayOff->court_member_schedule_id === (int) $schedule->id, 404);
        $date = $dayOff->off_date->toDateString();
        DB::transaction(function () use ($schedule, $dayOff, $availability, $date): void {
            $court = Court::query()->whereKey($schedule->court_id)->lockForUpdate()->firstOrFail();
            $lockedSchedule = CourtMemberSchedule::query()->whereKey($schedule->id)->lockForUpdate()->firstOrFail();
            $lockedDayOff = CourtMemberScheduleDayOff::query()
                ->whereKey($dayOff->id)
                ->where('court_member_schedule_id', $lockedSchedule->id)
                ->lockForUpdate()
                ->firstOrFail();
            if ($availability->bookingConflict($court, $date, $lockedSchedule->time_start, $lockedSchedule->time_end)
                || $availability->matchConflict($court, $date, $lockedSchedule->time_start, $lockedSchedule->time_end)) {
                throw ValidationException::withMessages(['off_date' => 'Tanggal libur tidak dapat dihapus karena slot tersebut sudah dipakai booking, pertandingan, atau sesi sewa turnamen.']);
            }
            $lockedDayOff->delete();
        }, 3);

        return back()->with('success', 'Tanggal libur dihapus. Jadwal rutin member aktif kembali pada tanggal tersebut.');
    }

    public function storeCourt(Request $request)
    {
        $this->ensureAdmin();

        try {
            $validated = $this->validateCourt($request);
        } catch (ValidationException $exception) {
            return back()
                ->withInput()
                ->withErrors($exception->errors(), 'addCourt')
                ->with('open_add_court_modal', true);
        }

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

    public function updateSchedule(
        Request $request,
        CourtBooking $booking,
        CourtBookingAvailabilityService $availability,
        CourtPricingService $pricing,
    ) {
        $this->ensureAdmin();
        $validated = $request->validate([
            '_schedule_edit_booking_id' => ['required', 'integer', 'in:'.$booking->id],
            'booking_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:'.CarbonImmutable::today(CourtBooking::TIMEZONE)->toDateString()],
            'time_start' => ['required', 'date_format:H:i'],
            'time_end' => ['required', 'date_format:H:i'],
            'reason' => ['required', 'string', 'max:500'],
            'refund_confirmed' => ['nullable', 'boolean'],
            'refund_payment_method' => ['nullable', Rule::in(['cash', 'bank_transfer', 'qris'])],
            'refund_reference' => ['nullable', 'string', 'max:100'],
        ], [
            'booking_date.before_or_equal' => 'Tanggal pemakaian sebenarnya tidak boleh setelah hari ini.',
        ]);
        $refundConfirmed = $request->boolean('refund_confirmed');

        $result = DB::transaction(function () use ($booking, $validated, $availability, $pricing, $refundConfirmed) {
            // Serialize edits and new bookings for this court, then lock the booking row.
            $court = $availability->lockCourt((int) $booking->court_id);
            if (!$court) {
                throw ValidationException::withMessages(['booking' => 'Lapangan booking ini sudah tidak tersedia.']);
            }

            $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($lockedBooking->status !== CourtBooking::STATUS_CONFIRMED) {
                throw ValidationException::withMessages(['booking' => 'Jadwal hanya dapat dikoreksi untuk booking yang sudah terkonfirmasi.']);
            }

            $start = CarbonImmutable::createFromFormat(
                'Y-m-d H:i',
                $validated['booking_date'].' '.$validated['time_start'],
                CourtBooking::TIMEZONE,
            );
            $end = CarbonImmutable::createFromFormat(
                'Y-m-d H:i',
                $validated['booking_date'].' '.$validated['time_end'],
                CourtBooking::TIMEZONE,
            );
            if (!$start || !$end) {
                throw ValidationException::withMessages(['time_end' => 'Tanggal atau jam booking tidak valid.']);
            }
            if ($end->lessThanOrEqualTo($start)) {
                $end = $end->addDay();
            }

            $durationMinutes = intdiv($end->getTimestamp() - $start->getTimestamp(), 60);
            $now = CarbonImmutable::now(CourtBooking::TIMEZONE);
            if ($durationMinutes < 1 || $durationMinutes > 1440) {
                throw ValidationException::withMessages(['time_end' => 'Durasi koreksi harus lebih dari 0 dan maksimal 24 jam.']);
            }
            if ($end->isAfter($now)) {
                throw ValidationException::withMessages(['time_end' => 'Koreksi hanya untuk pemakaian yang sudah selesai. Jam selesai harus sudah lewat.']);
            }

            $conflictingBooking = $availability->bookingConflict(
                $court,
                $validated['booking_date'],
                $validated['time_start'],
                $validated['time_end'],
                (int) $lockedBooking->id,
            );
            if ($conflictingBooking) {
                throw ValidationException::withMessages([
                    'time_start' => 'Jadwal berbenturan dengan booking lain (#'.str_pad((string) $conflictingBooking->id, 6, '0', STR_PAD_LEFT).'). Periksa kembali tanggal dan jamnya.',
                ]);
            }

            if ($availability->matchConflict(
                $court,
                $validated['booking_date'],
                $validated['time_start'],
                $validated['time_end'],
            )) {
                throw ValidationException::withMessages([
                    'booking_date' => 'Jadwal berbenturan dengan pertandingan atau sesi sewa turnamen.',
                ]);
            }

            $memberConflict = $availability->memberScheduleNameForSlot(
                $court,
                $validated['booking_date'],
                $validated['time_start'],
                $validated['time_end'],
                $lockedBooking->source === 'member' && $lockedBooking->court_member_schedule_id
                    ? (int) $lockedBooking->court_member_schedule_id
                    : null,
            );
            if ($memberConflict !== null) {
                throw ValidationException::withMessages([
                    'booking_date' => 'Jadwal berbenturan dengan jadwal member '.$memberConflict.'.',
                ]);
            }

            $before = [
                'booking_date' => $lockedBooking->booking_date->toDateString(),
                'time_start' => substr((string) $lockedBooking->time_start, 0, 5),
                'time_end' => substr((string) $lockedBooking->time_end, 0, 5),
                'duration_minutes' => (int) $lockedBooking->duration_minutes,
                'amount' => (float) $lockedBooking->amount,
            ];
            $dateOnlyCorrection = $before['time_start'] === $validated['time_start']
                && $before['time_end'] === $validated['time_end']
                && $before['duration_minutes'] === $durationMinutes;

            if ($dateOnlyCorrection) {
                // A date-only correction changes which operating day receives the income,
                // while preserving the amount that was actually charged and verified.
                $newAmount = (float) $lockedBooking->amount;
            } else {
                $courtAmount = $pricing->amountForRange(
                    $court,
                    $validated['time_start'],
                    $durationMinutes,
                    $validated['booking_date'],
                );
                $rentalAmount = (float) $lockedBooking->rentalLines()->sum('line_total');
                $newAmount = round($courtAmount + $rentalAmount, 2);
            }
            $grossPaidAmount = (float) $lockedBooking->payments()
                ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
                ->sum(DB::raw('COALESCE(verified_amount, amount)'));
            $previousRefunds = (float) $lockedBooking->fundSettlements()
                ->where('type', CourtBookingFundSettlement::TYPE_REFUND)
                ->whereNull('voided_at')
                ->sum('amount');
            $netPaidAmount = max(0, round($grossPaidAmount - $previousRefunds, 2));
            $refundAmount = max(0, round($netPaidAmount - $newAmount, 2));

            if ($refundAmount > 0 && !$refundConfirmed) {
                throw ValidationException::withMessages([
                    'refund_confirmed' => 'Tagihan baru Rp '.number_format($newAmount, 0, ',', '.').' lebih rendah dari dana bersih yang diterima Rp '.number_format($netPaidAmount, 0, ',', '.').'. Kembalikan selisih Rp '.number_format($refundAmount, 0, ',', '.').' kepada pelanggan, lalu konfirmasi refund di sini.',
                ]);
            }
            if ($refundAmount > 0 && empty($validated['refund_payment_method'])) {
                throw ValidationException::withMessages([
                    'refund_payment_method' => 'Pilih metode yang benar-benar digunakan untuk mengembalikan Rp '.number_format($refundAmount, 0, ',', '.').' kepada pelanggan.',
                ]);
            }

            $after = [
                'booking_date' => $validated['booking_date'],
                'time_start' => $validated['time_start'],
                'time_end' => $validated['time_end'],
                'duration_minutes' => $durationMinutes,
                'amount' => $newAmount,
            ];

            if ($before === $after) {
                throw ValidationException::withMessages(['booking' => 'Tidak ada perubahan jadwal atau total tagihan untuk disimpan.']);
            }

            $lockedBooking->update([
                'booking_date' => $validated['booking_date'],
                'time_start' => $validated['time_start'],
                'time_end' => $validated['time_end'],
                'duration_minutes' => $durationMinutes,
                'amount' => $newAmount,
            ]);

            CourtBookingScheduleAudit::query()->create([
                'court_booking_id' => $lockedBooking->id,
                'admin_id' => auth()->id(),
                'admin_name_snapshot' => auth()->user()?->name,
                'reason' => trim($validated['reason']),
                'before_data' => $before,
                'after_data' => $after,
            ]);

            if ($refundAmount > 0) {
                CourtBookingFundSettlement::query()->create([
                    'court_booking_id' => $lockedBooking->id,
                    'request_key' => (string) Str::uuid(),
                    'type' => CourtBookingFundSettlement::TYPE_REFUND,
                    'amount' => $refundAmount,
                    'payment_method' => $validated['refund_payment_method'],
                    'reference' => trim((string) ($validated['refund_reference'] ?? '')) ?: null,
                    'reason' => mb_substr('Refund selisih setelah koreksi jadwal: '.trim($validated['reason']), 0, 500),
                    'recorded_by' => auth()->id(),
                    'recorded_by_name_snapshot' => mb_substr((string) auth()->user()?->name, 0, 160) ?: null,
                    'recorded_at' => now(),
                ]);
            }

            return [
                'booking' => $lockedBooking->refresh(),
                'refund_amount' => $refundAmount,
            ];
        });

        $successMessage = 'Tanggal dan jadwal booking berhasil dikoreksi. Riwayat koreksi tersimpan.';
        if ($result['refund_amount'] > 0) {
            $successMessage .= ' Refund Rp '.number_format($result['refund_amount'], 0, ',', '.').' juga tercatat dengan metode pengembalian yang dipilih.';
        }

        return redirect()->route('admin.court-bookings.index', ['date' => $result['booking']->booking_date->toDateString()])
            ->with('success', $successMessage);
    }

    public function updateDailyRevenue(Request $request)
    {
        $this->ensureAdmin();

        $amountInputs = [];
        $incomeFields = [];
        foreach (['parking', 'shop'] as $source) {
            foreach (['cash', 'transfer', 'qris', 'unclassified'] as $method) {
                $incomeFields[] = "{$source}_{$method}_income";
            }
        }
        foreach ($incomeFields as $field) {
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
            ...array_fill_keys($incomeFields, ['required', 'integer', 'min:0', 'max:9999999999']),
        ]);

        $parkingIncome = array_sum(array_map(fn (string $method): int => (int) $validated["parking_{$method}_income"], ['cash', 'transfer', 'qris', 'unclassified']));
        $shopIncome = array_sum(array_map(fn (string $method): int => (int) $validated["shop_{$method}_income"], ['cash', 'transfer', 'qris', 'unclassified']));
        if ($parkingIncome > 9999999999 || $shopIncome > 9999999999) {
            throw ValidationException::withMessages(['operating_date' => 'Total pemasukan harian melebihi batas yang didukung.']);
        }

        // Laravel stores timestamps as UTC; the view converts them to WIB.
        $now = now();
        DB::table('court_daily_revenues')->upsert([[
            'operating_date' => $validated['operating_date'],
            'parking_income' => $parkingIncome,
            'shop_income' => $shopIncome,
            ...array_intersect_key($validated, array_flip($incomeFields)),
            'updated_by' => auth()->id(),
            'created_at' => $now,
            'updated_at' => $now,
        ]], ['operating_date'], ['parking_income', 'shop_income', ...$incomeFields, 'updated_by', 'updated_at']);

        return redirect()->route('admin.court-bookings.reports.daily', [
            'date' => $validated['operating_date'],
        ])->with('success', 'Pemasukan parkir dan toko untuk tanggal tersebut berhasil diperbarui.');
    }

    public function storeDailyRevenueEntry(Request $request)
    {
        $this->ensureAdmin();
        $validated = $this->validateDailyRevenueEntry($request, true);
        CourtDailyRevenueEntry::query()->create([
            ...$validated,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.court-bookings.reports.daily', ['date' => $validated['operating_date']])
            ->with('success', $validated['court_tournament_rental_id']
                ? 'Pemasukan sewa lapangan turnamen berhasil dicatat dan masuk ke pemasukan booking lapangan.'
                : 'Pemasukan lain berhasil dicatat.');
    }

    public function updateDailyRevenueEntry(Request $request, CourtDailyRevenueEntry $entry)
    {
        $this->ensureAdmin();
        $validated = $this->validateDailyRevenueEntry($request, false, $entry);
        $entry->update([...$validated, 'updated_by' => auth()->id()]);

        return redirect()->route('admin.court-bookings.reports.daily', ['date' => $entry->operating_date->toDateString()])
            ->with('success', $validated['court_tournament_rental_id']
                ? 'Catatan pemasukan sewa lapangan turnamen berhasil diperbarui.'
                : 'Catatan pemasukan lain berhasil diperbarui.');
    }

    private function validateDailyRevenueEntry(
        Request $request,
        bool $includeOperatingDate,
        ?CourtDailyRevenueEntry $entry = null,
    ): array {
        $this->normalizeRupiahInput($request, 'amount');

        $rules = [
            'title' => ['required', 'string', 'max:120'],
            'amount' => ['required', 'integer', 'min:1', 'max:9999999999'],
            'payment_method' => ['required', Rule::in(['cash', 'bank_transfer', 'qris', 'unclassified'])],
            'tournament_id' => ['nullable', 'integer', 'exists:tournaments,id'],
            'court_id' => ['nullable', 'integer', 'exists:courts,id'],
            'court_tournament_rental_id' => ['nullable', 'integer', 'exists:court_tournament_rentals,id'],
            'note' => ['nullable', 'string', 'max:500'],
        ];
        if ($includeOperatingDate) {
            $rules['operating_date'] = ['required', 'date_format:Y-m-d'];
        }

        $validated = $request->validate($rules);
        $rentalId = $validated['court_tournament_rental_id'] ?? null;
        $rental = $rentalId ? CourtTournamentRental::query()->findOrFail((int) $rentalId) : null;

        if ($rental?->status === CourtTournamentRental::STATUS_CANCELLED
            && (int) $entry?->court_tournament_rental_id !== (int) $rental->id) {
            throw ValidationException::withMessages([
                'court_tournament_rental_id' => 'Sesi sewa yang dibatalkan tidak dapat dipilih untuk pemasukan baru.',
            ]);
        }

        if ($rental) {
            $validated['tournament_id'] = $rental->tournament_id;
            $validated['court_id'] = $rental->court_id;
        }

        $validated['court_tournament_rental_id'] = $rental?->id;

        return $validated;
    }

    public function destroyDailyRevenueEntry(CourtDailyRevenueEntry $entry)
    {
        $this->ensureAdmin();
        $date = $entry->operating_date->toDateString();
        $entry->update(['updated_by' => auth()->id()]);
        $entry->delete();

        return redirect()->route('admin.court-bookings.reports.daily', ['date' => $date])
            ->with('success', 'Catatan pemasukan lain dihapus dari perhitungan.');
    }

    private function normalizeRupiahInput(Request $request, string $field): void
    {
        $value = $request->input($field);
        if (is_string($value)) {
            $value = preg_replace('/^Rp\\s*/i', '', trim($value));
            $request->merge([$field => str_replace('.', '', preg_replace('/\\s+/', '', $value))]);
        }
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
                ->sum(DB::raw('COALESCE(verified_amount, amount)'));
            $paidAmount -= (float) $lockedBooking->fundSettlements()
                ->where('type', CourtBookingFundSettlement::TYPE_REFUND)
                ->whereNull('voided_at')
                ->sum('amount');
            $paidAmount = max(0, $paidAmount);
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
            $lockedBooking->update([
                'payment_method' => $validated['payment_method'],
            ]);
        });

        return back()->with('success', 'Pembayaran booking berhasil dicatat.');
    }

    public function updatePayment(Request $request, CourtBooking $booking, CourtBookingPayment $payment)
    {
        $this->ensureAdmin();
        $this->normalizeRupiahInput($request, 'verified_amount');
        $validated = $request->validate([
            'verified_amount' => ['required', 'numeric', 'gt:0', 'max:9999999999.99'],
            'payment_method' => ['required', Rule::in(['cash', 'bank_transfer', 'qris'])],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:500'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($booking, $payment, $validated) {
            $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            $lockedPayment = CourtBookingPayment::query()
                ->whereKey($payment->id)
                ->where('court_booking_id', $lockedBooking->id)
                ->lockForUpdate()->firstOrFail();
            if (!in_array($lockedBooking->status, [CourtBooking::STATUS_CONFIRMED, CourtBooking::STATUS_CANCELLED], true)
                || $lockedPayment->status !== CourtBookingPayment::STATUS_CONFIRMED) {
                throw ValidationException::withMessages(['payment' => 'Hanya pembayaran terverifikasi pada booking aktif atau dibatalkan yang dapat dikoreksi.']);
            }

            $otherConfirmed = (float) $lockedBooking->payments()
                ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
                ->whereKeyNot($lockedPayment->id)
                ->sum(DB::raw('COALESCE(verified_amount, amount)'));
            $newAmount = round((float) $validated['verified_amount'], 2);
            $activeRefunds = (float) $lockedBooking->fundSettlements()
                ->where('type', CourtBookingFundSettlement::TYPE_REFUND)
                ->whereNull('voided_at')
                ->sum('amount');
            $netPaidAfterCorrection = max(0, round($otherConfirmed + $newAmount - $activeRefunds, 2));
            if ($netPaidAfterCorrection > (float) $lockedBooking->amount) {
                throw ValidationException::withMessages(['verified_amount' => 'Nominal ini membuat total pembayaran melebihi tagihan booking.']);
            }
            $activeSettlements = (float) $lockedBooking->fundSettlements()->whereNull('voided_at')->sum('amount');
            $confirmedAdditions = (float) $lockedBooking->rentalAdditions()->whereNotNull('confirmed_at')->sum('amount');
            if ($otherConfirmed + $newAmount + $confirmedAdditions < $activeSettlements) {
                throw ValidationException::withMessages(['verified_amount' => 'Nominal ini lebih rendah dari dana yang sudah dicatat sebagai refund atau biaya pembatalan. Batalkan catatan penyelesaian terkait terlebih dahulu.']);
            }

            $before = $this->paymentAuditSnapshot($lockedPayment);
            $paidAfter = $netPaidAfterCorrection;
            $lockedPayment->update([
                'amount' => $newAmount,
                'verified_amount' => $newAmount,
                'stage' => $paidAfter >= (float) $lockedBooking->amount
                    ? ($otherConfirmed > 0 ? CourtBookingPayment::STAGE_SETTLEMENT : CourtBookingPayment::STAGE_FULL)
                    : CourtBookingPayment::STAGE_DEPOSIT,
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'] ?? null,
                'note' => trim((string) ($validated['note'] ?? '')) ?: 'Koreksi pembayaran: '.$validated['reason'],
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);
            $this->writePaymentAudit($lockedBooking, $lockedPayment, 'payment_updated', $validated['reason'], $before);
        });

        return back()->with('success', 'Detail pembayaran berhasil dikoreksi dan perubahan tercatat.');
    }

    public function unverifyPayment(Request $request, CourtBooking $booking, CourtBookingPayment $payment)
    {
        $this->ensureAdmin();
        $validated = $request->validate(['reason' => ['required', 'string', 'max:500']]);

        DB::transaction(function () use ($booking, $payment, $validated) {
            $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            $lockedPayment = CourtBookingPayment::query()
                ->whereKey($payment->id)
                ->where('court_booking_id', $lockedBooking->id)
                ->lockForUpdate()->firstOrFail();
            if ($lockedPayment->status !== CourtBookingPayment::STATUS_CONFIRMED) {
                throw ValidationException::withMessages(['payment' => 'Hanya pembayaran yang sudah diverifikasi yang dapat dikembalikan ke antrean pemeriksaan.']);
            }
            if (!in_array($lockedBooking->status, [CourtBooking::STATUS_CONFIRMED, CourtBooking::STATUS_PENDING_REVIEW], true)) {
                throw ValidationException::withMessages(['booking' => 'Status booking tidak mendukung pembatalan verifikasi pembayaran.']);
            }

            $remainingConfirmedBeforeUpdate = (float) $lockedBooking->payments()
                ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
                ->whereKeyNot($lockedPayment->id)
                ->sum(DB::raw('COALESCE(verified_amount, amount)'));
            $confirmedAdditions = (float) $lockedBooking->rentalAdditions()->whereNotNull('confirmed_at')->sum('amount');
            $activeSettlements = (float) $lockedBooking->fundSettlements()->whereNull('voided_at')->sum('amount');
            if ($remainingConfirmedBeforeUpdate + $confirmedAdditions < $activeSettlements) {
                throw ValidationException::withMessages(['payment' => 'Pembayaran tidak dapat dikembalikan ke pemeriksaan karena dana refund/penyelesaian sudah tercatat. Koreksi catatan dana terlebih dahulu.']);
            }

            $before = $this->paymentAuditSnapshot($lockedPayment);
            $claimedAmount = $lockedPayment->claimed_amount ?? $lockedPayment->verified_amount ?? $lockedPayment->amount;
            $lockedPayment->update([
                'amount' => $claimedAmount,
                'claimed_amount' => $claimedAmount,
                'verified_amount' => null,
                'stage' => CourtBookingPayment::STAGE_UNVERIFIED,
                'status' => CourtBookingPayment::STATUS_PENDING_REVIEW,
                'verified_by' => null,
                'verified_at' => null,
                'note' => trim((string) ($lockedPayment->note ? $lockedPayment->note.' ' : '').'Pemeriksaan dibuka kembali: '.$validated['reason']),
            ]);

            $remainingConfirmed = (float) $lockedBooking->payments()
                ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
                ->sum(DB::raw('COALESCE(verified_amount, amount)'));
            if ($remainingConfirmed <= 0) {
                $lockedBooking->update([
                    'status' => CourtBooking::STATUS_PENDING_REVIEW,
                    'verified_by' => null,
                    'verified_at' => null,
                    'confirmed_at' => null,
                    'admin_note' => 'Pembayaran menunggu pemeriksaan ulang: '.$validated['reason'],
                ]);
            }

            $this->writePaymentAudit($lockedBooking, $lockedPayment, 'payment_unverified', $validated['reason'], $before);
        });

        return back()->with('success', 'Verifikasi dibatalkan. Pembayaran kembali menunggu pemeriksaan; slot tetap ditahan.');
    }

    private function paymentAuditSnapshot(CourtBookingPayment $payment): array
    {
        return $payment->only(['amount', 'claimed_amount', 'verified_amount', 'stage', 'payment_method', 'payment_reference', 'status', 'note', 'verified_by', 'verified_at']);
    }

    private function writePaymentAudit(CourtBooking $booking, CourtBookingPayment $payment, string $action, string $reason, array $before): void
    {
        CourtBookingPaymentAudit::query()->create([
            'court_booking_id' => $booking->id,
            'court_booking_payment_id' => $payment->id,
            'admin_id' => auth()->id(),
            'admin_name_snapshot' => mb_substr((string) auth()->user()?->name, 0, 160) ?: null,
            'action' => $action,
            'reason' => trim($reason),
            'before_data' => $before,
            'after_data' => $this->paymentAuditSnapshot($payment->fresh()),
        ]);
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
            ) || $availability->memberScheduleConflict(
                $court,
                $lockedBooking->booking_date->toDateString(),
                $lockedBooking->time_start,
                $lockedBooking->time_end,
            )) {
                throw ValidationException::withMessages(['booking' => 'Jadwal bertabrakan dengan booking, pertandingan, atau sesi sewa turnamen. Periksa jadwal sebelum mengonfirmasi.']);
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
                ->sum(DB::raw('COALESCE(verified_amount, amount)'));
            $activeRefunds = (float) $lockedBooking->fundSettlements()
                ->where('type', CourtBookingFundSettlement::TYPE_REFUND)
                ->whereNull('voided_at')
                ->sum('amount');
            $netConfirmedBefore = max(0, round($confirmedBefore - $activeRefunds, 2));
            $remainingBefore = max(0, round((float) $lockedBooking->amount - $netConfirmedBefore, 2));
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
                'cancelled_by_name_snapshot' => mb_substr((string) auth()->user()?->name, 0, 160) ?: null,
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

    public function storeFundSettlement(Request $request, CourtBooking $booking)
    {
        $this->ensureAdmin();
        $this->normalizeRupiahInput($request, 'amount');
        $validated = $request->validate([
            'request_key' => ['required', 'uuid'],
            'type' => ['required', Rule::in([CourtBookingFundSettlement::TYPE_REFUND, CourtBookingFundSettlement::TYPE_RETAINED])],
            'amount' => ['required', 'numeric', 'gt:0', 'max:9999999999.99'],
            'payment_method' => ['required', Rule::in(['cash', 'bank_transfer', 'qris', 'unclassified'])],
            'reference' => ['nullable', 'string', 'max:100'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $created = DB::transaction(function () use ($booking, $validated) {
            $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            $existingSettlement = CourtBookingFundSettlement::query()
                ->where('request_key', $validated['request_key'])
                ->lockForUpdate()
                ->first();
            if ($existingSettlement) {
                if ((int) $existingSettlement->court_booking_id !== (int) $lockedBooking->id) {
                    throw ValidationException::withMessages(['request_key' => 'Kunci transaksi sudah digunakan untuk booking lain. Muat ulang halaman.']);
                }

                return false;
            }

            if ($lockedBooking->status !== CourtBooking::STATUS_CANCELLED) {
                throw ValidationException::withMessages(['booking' => 'Pengembalian atau penahanan dana hanya dapat dicatat untuk booking yang sudah dibatalkan.']);
            }
            if ($lockedBooking->rental_picked_up_at && !$lockedBooking->rental_returned_at) {
                throw ValidationException::withMessages(['booking' => 'Selesaikan pengembalian perlengkapan sebelum memproses dana booking ini.']);
            }

            $receivedAmount = (float) $lockedBooking->payments()
                ->where('status', CourtBookingPayment::STATUS_CONFIRMED)
                ->sum(DB::raw('COALESCE(verified_amount, amount)'))
                + (float) $lockedBooking->rentalAdditions()->whereNotNull('confirmed_at')->sum('amount');
            $settledAmount = (float) $lockedBooking->fundSettlements()
                ->whereNull('voided_at')
                ->sum('amount');
            $availableAmount = max(0, round($receivedAmount - $settledAmount, 2));
            $settlementAmount = round((float) $validated['amount'], 2);

            if ($receivedAmount <= 0) {
                throw ValidationException::withMessages(['amount' => 'Booking ini belum memiliki pembayaran terverifikasi.']);
            }
            if ($settlementAmount > $availableAmount) {
                throw ValidationException::withMessages(['amount' => 'Nominal melebihi dana booking yang belum diselesaikan (Rp '.number_format($availableAmount, 0, ',', '.').').']);
            }

            CourtBookingFundSettlement::query()->create([
                'court_booking_id' => $lockedBooking->id,
                'request_key' => $validated['request_key'],
                'type' => $validated['type'],
                'amount' => $settlementAmount,
                'payment_method' => $validated['payment_method'],
                'reference' => trim((string) ($validated['reference'] ?? '')) ?: null,
                'reason' => trim($validated['reason']),
                'recorded_by' => auth()->id(),
                'recorded_by_name_snapshot' => mb_substr((string) auth()->user()?->name, 0, 160) ?: null,
                'recorded_at' => now(),
            ]);

            return true;
        });

        return back()->with('success', $created
            ? 'Penyelesaian dana booking tersimpan pada riwayat audit.'
            : 'Permintaan penyelesaian ini sudah tercatat. Tidak ada catatan duplikat yang dibuat.');
    }

    public function voidFundSettlement(Request $request, CourtBooking $booking, CourtBookingFundSettlement $settlement)
    {
        $this->ensureAdmin();
        $validated = $request->validate(['reason' => ['required', 'string', 'max:500']]);

        $voided = DB::transaction(function () use ($booking, $settlement, $validated) {
            $lockedBooking = CourtBooking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            $lockedSettlement = CourtBookingFundSettlement::query()
                ->whereKey($settlement->id)
                ->where('court_booking_id', $lockedBooking->id)
                ->lockForUpdate()
                ->firstOrFail();

            $canVoidCancellationSettlement = $lockedBooking->status === CourtBooking::STATUS_CANCELLED;
            $canVoidActiveRefund = $lockedBooking->status === CourtBooking::STATUS_CONFIRMED
                && $lockedSettlement->type === CourtBookingFundSettlement::TYPE_REFUND;
            if (!$canVoidCancellationSettlement && !$canVoidActiveRefund) {
                throw ValidationException::withMessages(['booking' => 'Refund booking aktif dapat dikoreksi selama booking masih terkonfirmasi. Catatan dana pembatalan hanya dapat dikoreksi pada booking yang dibatalkan.']);
            }
            if ($lockedSettlement->voided_at) {
                return false;
            }

            $lockedSettlement->update([
                'voided_by' => auth()->id(),
                'voided_by_name_snapshot' => mb_substr((string) auth()->user()?->name, 0, 160) ?: null,
                'voided_at' => now(),
                'void_reason' => trim($validated['reason']),
            ]);

            return true;
        });

        return back()->with('success', $voided
            ? 'Catatan penyelesaian dana dibatalkan dan tetap tersimpan di riwayat audit.'
            : 'Catatan tersebut sudah pernah dikoreksi. Tidak ada perubahan tambahan.');
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
        foreach (['day_hourly_rate', 'night_hourly_rate', 'weekend_day_hourly_rate', 'weekend_night_hourly_rate'] as $rateField) {
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
            'weekend_day_hourly_rate' => ['required', 'integer', 'min:0', 'max:100000000'],
            'weekend_night_hourly_rate' => ['required', 'integer', 'min:0', 'max:100000000'],
            'night_rate_starts_at' => ['required', 'date_format:H:i'],
            'night_rate_ends_at' => ['required', 'date_format:H:i'],
            'opening_time' => ['required', 'date_format:H:i'],
            'closing_time' => ['required', 'date_format:H:i'],
            'slot_duration_minutes' => ['required', 'integer', Rule::in([30])],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validated['closing_time'] !== '00:00' && $validated['closing_time'] <= $validated['opening_time']) {
            throw ValidationException::withMessages(['closing_time' => 'Jam tutup harus setelah jam buka. Gunakan 00.00 jika lapangan beroperasi sampai tengah malam.']);
        }
        if ($validated['night_rate_starts_at'] === $validated['night_rate_ends_at']) {
            throw ValidationException::withMessages(['night_rate_ends_at' => 'Jam akhir tarif malam harus berbeda dari jam mulainya.']);
        }

        return $validated + [
            // Keep the legacy column in sync for compatibility with older consumers.
            'hourly_rate' => $validated['day_hourly_rate'],
            'is_active' => $request->boolean('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }

    private function validateMemberSchedule(Request $request, ?CourtMemberSchedule $schedule = null): array
    {
        $validated = $request->validate([
            'court_id' => ['required', 'integer', 'exists:courts,id'],
            'member_name' => ['required', 'string', 'max:120'],
            'member_name_mode' => ['nullable', Rule::in(['existing', 'new'])],
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'time_start' => ['required', 'date_format:H:i'],
            'time_end' => ['required', 'date_format:H:i', 'after:time_start'],
            'starts_on' => ['nullable', 'date_format:Y-m-d'],
            'ends_on' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:starts_on'],
            'notes' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['member_name'] = preg_replace('/\\s+/u', ' ', trim($validated['member_name']));
        $normalizedMemberName = mb_strtolower($validated['member_name']);
        if (($validated['member_name_mode'] ?? null) === 'new') {
            $nameAlreadyExists = CourtMemberSchedule::query()
                ->whereRaw('LOWER(TRIM(member_name)) = ?', [$normalizedMemberName])
                ->exists();

            if ($nameAlreadyExists) {
                throw ValidationException::withMessages([
                    'member_name' => 'Nama ini sudah terdaftar. Pilih nama tersebut dari daftar agar penulisannya konsisten.',
                ]);
            }
        } elseif (($validated['member_name_mode'] ?? null) === 'existing') {
            $canonicalMemberName = CourtMemberSchedule::query()
                ->whereRaw('LOWER(TRIM(member_name)) = ?', [$normalizedMemberName])
                ->orderBy('id')
                ->value('member_name');

            if (!$canonicalMemberName) {
                throw ValidationException::withMessages([
                    'member_name' => 'Nama member tidak ditemukan. Pilih nama yang tersedia atau tambahkan sebagai nama baru.',
                ]);
            }

            $validated['member_name'] = $canonicalMemberName;
        }
        unset($validated['member_name_mode']);

        $candidateStart = $validated['starts_on'] ?? '0001-01-01';
        $candidateEnd = $validated['ends_on'] ?? '9999-12-31';
        $willBeActive = $schedule ? $request->boolean('is_active') : true;
        $overlappingSchedules = CourtMemberSchedule::query()
            ->where('court_id', $validated['court_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where('is_active', true)
            ->when(!$willBeActive, fn ($query) => $query->whereRaw('1 = 0'))
            ->when($schedule, fn ($query) => $query->whereKeyNot($schedule->id))
            ->where('time_start', '<', $validated['time_end'])
            ->where('time_end', '>', $validated['time_start'])
            ->get(['starts_on', 'ends_on']);
        $overlap = $overlappingSchedules->contains(function (CourtMemberSchedule $other) use ($candidateStart, $candidateEnd): bool {
            $otherStart = $other->starts_on?->toDateString() ?? '0001-01-01';
            $otherEnd = $other->ends_on?->toDateString() ?? '9999-12-31';

            return $candidateStart <= $otherEnd && $otherStart <= $candidateEnd;
        });

        if ($overlap) {
            throw ValidationException::withMessages(['time_start' => 'Jadwal ini bertabrakan dengan jadwal member rutin lain di lapangan dan hari yang sama.']);
        }

        $recurringConflict = $willBeActive
            ? $this->recurringScheduleConflictMessage($validated, $schedule?->id)
            : null;
        if ($recurringConflict !== null) {
            throw ValidationException::withMessages(['time_start' => $recurringConflict]);
        }

        return $validated + ['is_active' => $request->boolean('is_active')];
    }

    private function recurringScheduleConflictMessage(array $schedule, ?int $ignoreMemberScheduleId = null): ?string
    {
        $today = CarbonImmutable::today(CourtBooking::TIMEZONE)->toDateString();
        $firstDate = max($today, $schedule['starts_on'] ?? $today);
        $lastDate = $schedule['ends_on'] ?? '9999-12-31';
        if ($firstDate > $lastDate) {
            return null;
        }

        $previousWeekday = ((int) $schedule['day_of_week'] + 6) % 7;
        $firstQueryDate = CarbonImmutable::parse($firstDate, CourtBooking::TIMEZONE)->subDay()->toDateString();
        $bookingConflict = CourtBooking::query()
            ->where('court_id', $schedule['court_id'])
            ->whereDate('booking_date', '>=', $firstQueryDate)
            ->when($lastDate !== '9999-12-31', fn ($query) => $query->whereDate('booking_date', '<=', $lastDate))
            ->when($ignoreMemberScheduleId, fn ($query, $scheduleId) => $query->where(function ($linked) use ($scheduleId): void {
                $linked->whereNull('court_member_schedule_id')
                    ->orWhere('court_member_schedule_id', '!=', $scheduleId);
            }))
            ->where(function ($query) use ($schedule, $previousWeekday, $firstDate): void {
                $query->where(function ($sameDay) use ($schedule, $firstDate): void {
                    $sameDay->whereDate('booking_date', '>=', $firstDate)
                        ->whereRaw('DAYOFWEEK(booking_date) = ?', [(int) $schedule['day_of_week'] + 1])
                        ->where('time_start', '<', $schedule['time_end'])
                        ->where('time_end', '>', $schedule['time_start']);
                })->orWhere(function ($overnight) use ($schedule, $previousWeekday): void {
                    $overnight->whereRaw('DAYOFWEEK(booking_date) = ?', [$previousWeekday + 1])
                        ->whereColumn('time_end', '<=', 'time_start')
                        ->where('time_end', '>', $schedule['time_start']);
                });
            })
            ->where(function ($query): void {
                $query->whereIn('status', [CourtBooking::STATUS_PENDING_REVIEW, CourtBooking::STATUS_CONFIRMED])
                    ->orWhere(fn ($pending) => $pending->where('status', CourtBooking::STATUS_AWAITING_PAYMENT)->where('expires_at', '>', now()));
            })
            ->orderBy('booking_date')
            ->first(['id', 'booking_date', 'time_start', 'time_end']);
        if ($bookingConflict) {
            return 'Bentrok dengan booking #'.str_pad((string) $bookingConflict->id, 6, '0', STR_PAD_LEFT)
                .' pada '.$bookingConflict->booking_date->format('d/m/Y').' pukul '
                .substr((string) $bookingConflict->time_start, 0, 5).'-'.substr((string) $bookingConflict->time_end, 0, 5).'.';
        }

        $tournamentRentalConflict = CourtTournamentRental::query()
            ->where('court_id', $schedule['court_id'])
            ->where('status', '!=', CourtTournamentRental::STATUS_CANCELLED)
            ->whereDate('rental_date', '>=', $firstQueryDate)
            ->when($lastDate !== '9999-12-31', fn ($query) => $query->whereDate('rental_date', '<=', $lastDate))
            ->where(function ($query) use ($schedule, $previousWeekday, $firstDate): void {
                $query->where(function ($sameDay) use ($schedule, $firstDate): void {
                    $sameDay->whereDate('rental_date', '>=', $firstDate)
                        ->whereRaw('DAYOFWEEK(rental_date) = ?', [(int) $schedule['day_of_week'] + 1])
                        ->where('time_start', '<', $schedule['time_end'])
                        ->where('time_end', '>', $schedule['time_start']);
                })->orWhere(function ($overnight) use ($schedule, $previousWeekday): void {
                    $overnight->whereRaw('DAYOFWEEK(rental_date) = ?', [$previousWeekday + 1])
                        ->whereColumn('time_end', '<=', 'time_start')
                        ->where('time_end', '>', $schedule['time_start']);
                });
            })
            ->orderBy('rental_date')
            ->first(['rental_date', 'time_start', 'time_end']);
        if ($tournamentRentalConflict) {
            return 'Bentrok dengan sesi sewa turnamen pada '.$tournamentRentalConflict->rental_date->format('d/m/Y')
                .' pukul '.substr((string) $tournamentRentalConflict->time_start, 0, 5).'-'.substr((string) $tournamentRentalConflict->time_end, 0, 5).'.';
        }

        $courtName = Court::query()->whereKey($schedule['court_id'])->value('name');
        $matchConflict = Game::query()
            ->publiclyVisible()
            ->whereRaw('LOWER(TRIM(venue)) = ?', [mb_strtolower(trim((string) $courtName))])
            ->whereDate('match_date', '>=', $firstQueryDate)
            ->when($lastDate !== '9999-12-31', fn ($query) => $query->whereDate('match_date', '<=', $lastDate))
            ->where('status', '!=', 'postponed')
            ->whereNotNull('time_start')->whereNotNull('time_end')
            ->where(function ($query) use ($schedule, $previousWeekday, $firstDate): void {
                $query->where(function ($sameDay) use ($schedule, $firstDate): void {
                    $sameDay->whereDate('match_date', '>=', $firstDate)
                        ->whereRaw('DAYOFWEEK(match_date) = ?', [(int) $schedule['day_of_week'] + 1])
                        ->where('time_start', '<', $schedule['time_end'])
                        ->where('time_end', '>', $schedule['time_start']);
                })->orWhere(function ($overnight) use ($schedule, $previousWeekday): void {
                    $overnight->whereRaw('DAYOFWEEK(match_date) = ?', [$previousWeekday + 1])
                        ->whereColumn('time_end', '<=', 'time_start')
                        ->where('time_end', '>', $schedule['time_start']);
                });
            })
            ->orderBy('match_date')
            ->first(['match_date', 'time_start', 'time_end']);

        if ($matchConflict) {
            return 'Bentrok dengan pertandingan pada '.$matchConflict->match_date->format('d/m/Y')
                .' pukul '.substr((string) $matchConflict->time_start, 0, 5).'-'.substr((string) $matchConflict->time_end, 0, 5).'.';
        }

        return null;
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
