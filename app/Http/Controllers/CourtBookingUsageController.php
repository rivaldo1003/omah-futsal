<?php

namespace App\Http\Controllers;

use App\Services\CourtBookingUsageService;
use App\Support\BusinessTime;
use Carbon\CarbonImmutable;

class CourtBookingUsageController extends Controller
{
    public function index(CourtBookingUsageService $usage)
    {
        $today = CarbonImmutable::today(BusinessTime::TIMEZONE);
        $usageAsOfMonth = $today
            ->locale('id')
            ->translatedFormat('F Y');

        return view('booking.usage', [
            'topCourtUsers' => $usage->topUsers(),
            'usageAsOfMonth' => $usageAsOfMonth,
        ]);
    }
}
