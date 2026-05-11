<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Attendance;
use Illuminate\Http\Request;

class QrScanController extends Controller
{
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
            return response()->json(['success' => false, 'message' => 'Invalid QR code or registration not approved.'], 404);
        }

        if ($registration->attendance) {
            return response()->json(['success' => false, 'message' => $registration->user->full_name . ' already checked in.', 'already_scanned' => true], 409);
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
}
