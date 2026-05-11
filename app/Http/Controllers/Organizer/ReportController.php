<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;

class ReportController extends Controller
{
    public function index()
    {
        $events = Event::where('organizer_id', auth()->id())
            ->withCount(['registrations', 'attendances'])
            ->latest()->get();

        $labels = $events->pluck('title')->map(fn($t) => \Str::limit($t, 20))->toArray();
        $data   = $events->pluck('attendances_count')->toArray();

        return view('organizer.reports.index', compact('events', 'labels', 'data'));
    }
}
