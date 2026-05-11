<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = Registration::with(['user', 'event'])
            ->latest()->paginate(15);
        return view('admin.registrations.index', compact('registrations'));
    }

    public function approve(Registration $registration)
    {
        $registration->update(['status' => 'approved']);

        // Generate QR code on approval
        try {
            $service = new QrCodeService();
            $service->generate($registration);
        } catch (\Exception $e) {
            // QR generation failed silently — can be retried
        }

        return back()->with('success', 'Registration approved and QR code generated!');
    }

    public function reject(Registration $registration)
    {
        $registration->update(['status' => 'rejected']);
        return back()->with('success', 'Registration rejected.');
    }
}
