<?php

namespace App\Http\Controllers\Attendee;

use App\Http\Controllers\Controller;
use App\Models\Certificate;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with(['event'])
            ->where('user_id', auth()->id())
            ->latest()->get();
        return view('attendee.certificates.index', compact('certificates'));
    }

    public function download(Certificate $cert)
    {
        if ($cert->user_id !== auth()->id()) abort(403);
        if (!$cert->file_path) return back()->with('error', 'Certificate not available yet.');
        return response()->download(storage_path('app/public/certificates/' . $cert->file_path));
    }
}
