<?php

namespace App\Http\Controllers\Attendee;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = Registration::with(['event'])
            ->where('user_id', auth()->id())
            ->latest()->paginate(15);
        return view('attendee.events.my-registrations', compact('registrations'));
    }

    public function store(Event $event)
    {
        // Check if already registered
        $exists = Registration::where('event_id', $event->id)
            ->where('user_id', auth()->id())->exists();

        if ($exists) {
            return back()->with('error', 'You are already registered for this event.');
        }

        // Check capacity
        if ($event->max_capacity && $event->registered_count >= $event->max_capacity) {
            return back()->with('error', 'This event is already full.');
        }

        Registration::create([
            'event_id'          => $event->id,
            'user_id'           => auth()->id(),
            'registration_code' => 'EVT-' . date('Y') . '-' . str_pad($event->id, 5, '0', STR_PAD_LEFT) . '-' . strtoupper(substr(uniqid(), -4)),
            'status'            => 'pending',
        ]);

        return back()->with('success', 'Registration submitted! Awaiting approval.');
    }

    public function cancel(Registration $registration)
    {
        if ($registration->user_id !== auth()->id()) abort(403);
        $registration->update(['status' => 'cancelled']);
        return back()->with('success', 'Registration cancelled.');
    }

    public function qrCode(Registration $registration)
    {
        if ($registration->user_id !== auth()->id()) abort(403);
        return view('attendee.qr-code.view', compact('registration'));
    }
}
