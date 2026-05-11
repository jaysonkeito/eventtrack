<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Attendance;

class ReportController extends Controller
{
    public function index()
    {
        $events = Event::withCount('attendances')
            ->where('status', 'completed')
            ->orWhere('status', 'ongoing')
            ->latest()->take(10)->get();

        $labels = $events->pluck('title')->map(fn($t) => \Str::limit($t, 20))->toArray();
        $data   = $events->pluck('attendances_count')->toArray();

        return view('admin.reports.index', compact('labels', 'data'));
    }

    public function export()
    {
        $attendances = Attendance::with(['user', 'event'])->latest()->get();

        $csv = "Name,Email,Event,Time In,Method\n";
        foreach ($attendances as $att) {
            $csv .= implode(',', [
                '"' . $att->user->full_name . '"',
                '"' . $att->user->email . '"',
                '"' . $att->event->title . '"',
                '"' . $att->time_in->format('Y-m-d H:i:s') . '"',
                '"' . $att->scan_method . '"',
            ]) . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="attendance_report_' . date('Y-m-d') . '.csv"',
        ]);
    }
}
