<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Attendance;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'         => User::count(),
            'total_events'        => Event::count(),
            'total_registrations' => Registration::count(),
            'total_attended'      => Attendance::count(),
            'pending_registrations' => Registration::where('status', 'pending')->count(),
            'published_events'    => Event::where('status', 'published')->count(),
            'ongoing_events'      => Event::where('status', 'ongoing')->count(),
        ];

        $recentEvents = Event::with(['organizer', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $recentRegistrations = Registration::with(['user', 'event'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentEvents', 'recentRegistrations'));
    }
}
