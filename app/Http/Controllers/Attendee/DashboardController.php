<?php

namespace App\Http\Controllers\Attendee;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Certificate;

class DashboardController extends Controller
{
    public function index()
    {
        $registrations = Registration::with(['event'])
            ->where('user_id', auth()->id())
            ->latest()->get();

        $myRegistrations = $registrations->count();
        $attended        = $registrations->filter(fn($r) => $r->attendance)->count();
        $certificates    = Certificate::where('user_id', auth()->id())->count();

        return view('attendee.dashboard', compact(
            'registrations', 'myRegistrations', 'attended', 'certificates'
        ));
    }
}
