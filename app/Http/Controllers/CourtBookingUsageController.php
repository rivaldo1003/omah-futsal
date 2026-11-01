<?php

namespace App\Http\Controllers;

use App\Services\CourtBookingUsageService;
use App\Support\BusinessTime;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class CourtBookingUsageController extends Controller
{
    public function index(Request $request, CourtBookingUsageService $usage)
    {
        $today = CarbonImmutable::today(BusinessTime::TIMEZONE);
        $usageAsOfMonth = $today
            ->locale('id')
            ->translatedFormat('F Y');
        $period = $request->query('period') === 'month' ? 'month' : 'all';
        $monthInput = $request->query('month');

        if (! is_string($monthInput) || ! preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $monthInput)) {
            $monthInput = $today->format('Y-m');
        }

        $selectedMonth = CarbonImmutable::createFromFormat('!Y-m', $monthInput, BusinessTime::TIMEZONE);
        $selectedMonthLabel = $selectedMonth->locale('id')->translatedFormat('F Y');
        $startDate = $period === 'month' ? $selectedMonth->toDateString() : null;
        $endDate = $period === 'month' ? $selectedMonth->endOfMonth()->toDateString() : null;
        $usagePeriodLabel = $period === 'month'
            ? 'Bulan '.$selectedMonthLabel
            : 'Sejak awal pencatatan · diperbarui hingga '.$usageAsOfMonth;

        return view('booking.usage', [
            'topCourtUsers' => $usage->topUsers(startDate: $startDate, endDate: $endDate),
            'usageAsOfMonth' => $usageAsOfMonth,
            'usagePeriod' => $period,
            'usageMonth' => $monthInput,
            'usagePeriodLabel' => $usagePeriodLabel,
            'selectedMonthLabel' => $selectedMonthLabel,
        ]);
    }
}
