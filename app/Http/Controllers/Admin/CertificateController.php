<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with(['user', 'event'])->latest()->paginate(15);
        return view('admin.certificates.index', compact('certificates'));
    }

    public function generate(Event $event)
    {
        $attendances = Attendance::with(['user', 'registration'])
            ->where('event_id', $event->id)
            ->get();

        $count = 0;
        foreach ($attendances as $attendance) {
            // Skip if certificate already exists
            if ($attendance->certificate) continue;

            Certificate::create([
                'attendance_id'    => $attendance->id,
                'registration_id'  => $attendance->registration_id,
                'user_id'          => $attendance->user_id,
                'event_id'         => $event->id,
                'certificate_code' => 'CERT-EVT' . str_pad($event->id, 3, '0', STR_PAD_LEFT)
                                     . '-' . date('Y') . '-' . str_pad($attendance->user_id, 5, '0', STR_PAD_LEFT),
            ]);
            $count++;
        }

        return back()->with('success', "$count certificate(s) generated successfully!");
    }

    public function download(Certificate $cert)
    {
        if (!$cert->file_path) {
            return back()->with('error', 'Certificate file not available yet.');
        }
        return response()->download(storage_path('app/public/certificates/' . $cert->file_path));
    }
}
