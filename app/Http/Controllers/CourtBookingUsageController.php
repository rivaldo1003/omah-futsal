<?php

namespace App\Http\Controllers;

use App\Services\CourtBookingUsageService;

class CourtBookingUsageController extends Controller
{
    public function index(CourtBookingUsageService $usage)
    {
        return view('booking.usage', [
            'topCourtUsers' => $usage->topUsers(),
        ]);
    }
}
