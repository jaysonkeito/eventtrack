<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Registration;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function scanner()
    {
        $events = Event::where('organizer_id', auth()->id())
            ->whereIn('status', ['published', 'ongoing'])->get();
        return view('organizer.attendance.scanner', compact('events'));
    }

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
            return response()->json(['success' => false, 'message' => 'Invalid QR code or not approved.'], 404);
        }

        if ($registration->attendance) {
            return response()->json(['success' => false, 'message' => $registration->user->full_name . ' already scanned.', 'already_scanned' => true], 409);
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
            'success'  => true,
            'message'  => 'Attendance recorded for ' . $registration->user->full_name,
            'attendee' => [
                'name'    => $registration->user->full_name,
                'code'    => $registration->registration_code,
                'time_in' => $attendance->time_in->format('h:i A'),
            ],
        ]);
    }

    public function records()
    {
        $myEventIds = Event::where('organizer_id', auth()->id())->pluck('id');
        $attendances = Attendance::with(['user', 'event'])
            ->whereIn('event_id', $myEventIds)
            ->latest('time_in')->paginate(15);
        return view('organizer.attendance.records', compact('attendances'));
    }
}
