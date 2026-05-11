<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $events = Event::where('organizer_id', auth()->id())
            ->withCount(['registrations', 'attendances'])
            ->latest()->get();

        $myEvents           = $events->count();
        $totalRegistrations = $events->sum('registrations_count');
        $totalAttended      = $events->sum('attendances_count');

        return view('organizer.dashboard', compact(
            'events', 'myEvents', 'totalRegistrations', 'totalAttended'
        ));
    }
}
