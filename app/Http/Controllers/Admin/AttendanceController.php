<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Registration;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendances = Attendance::with(['user', 'event', 'scannedBy'])
            ->when($request->event_id, fn($q) => $q->where('event_id', $request->event_id))
            ->latest('time_in')
            ->paginate(15);

        $events = Event::where('status', 'ongoing')->orWhere('status', 'published')->get();

        return view('admin.attendance.index', compact('attendances', 'events'));
    }

    public function scanner()
    {
        $events = Event::whereIn('status', ['published', 'ongoing'])->get();
        return view('admin.attendance.scanner', compact('events'));
    }

    /**
     * Handle QR scan AJAX request.
     * Called by qr-scanner.js when a QR is detected.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_data'  => 'required|string',
            'event_id' => 'required|exists:events,id',
        ]);

        $registration = Registration::with('user')
            ->where('registration_code', $request->qr_data)
            ->where('event_id', $request->event_id)
            ->where('status', 'approved')
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code or registration not approved for this event.',
            ], 404);
        }

        // Prevent duplicate scan
        if ($registration->attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendee ' . $registration->user->full_name . ' is already marked present.',
                'already_scanned' => true,
            ], 409);
        }

        $attendance = Attendance::create([
            'registration_id' => $registration->id,
            'event_id'        => $registration->event_id,
            'user_id'         => $registration->user_id,
            'scanned_by'      => auth()->id(),
            'time_in'         => now(),
            'scan_method'     => 'qr_scan',
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Attendance recorded for ' . $registration->user->full_name,
            'attendee'  => [
                'name'    => $registration->user->full_name,
                'code'    => $registration->registration_code,
                'time_in' => $attendance->time_in->format('h:i A'),
            ],
        ]);
    }

    public function manual(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'notes'           => 'nullable|string|max:255',
        ]);

        $registration = Registration::with('user')->findOrFail($request->registration_id);

        if ($registration->attendance) {
            return back()->with('error', 'Attendance already recorded for this registrant.');
        }

        Attendance::create([
            'registration_id' => $registration->id,
            'event_id'        => $registration->event_id,
            'user_id'         => $registration->user_id,
            'scanned_by'      => auth()->id(),
            'time_in'         => now(),
            'scan_method'     => 'manual',
            'notes'           => $request->notes,
        ]);

        return back()->with('success', 'Manual attendance recorded for ' . $registration->user->full_name);
    }
}
