<?php

namespace App\Http\Controllers\Attendee;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user     = auth()->user();
        $colleges = RegisterController::$colleges;

        // Check if user can edit this month
        $canEdit     = $this->canEditThisMonth($user);
        $nextEditDate = $user->profile_updated_at
            ? \Carbon\Carbon::parse($user->profile_updated_at)->addMonth()->format('F j, Y')
            : null;

        return view('attendee.profile', compact('user', 'colleges', 'canEdit', 'nextEditDate'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // ── Monthly edit limit check ──────────────────────────
        if (!$this->canEditThisMonth($user)) {
            return back()->with('error',
                'You can only update your profile once per month. ' .
                'Next allowed update: ' .
                \Carbon\Carbon::parse($user->profile_updated_at)->addMonth()->format('F j, Y')
            );
        }

        $request->validate([
            'email'    => 'nullable|email|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|min:4|confirmed',
        ]);

        $updates = [
            'profile_updated_at' => now(),
        ];

        if ($request->filled('email')) {
            $updates['email'] = $request->email;
        }

        if ($request->filled('phone')) {
            $updates['phone'] = $request->phone;
        }

        // Password stored as lowercase
        if ($request->filled('password')) {
            $updates['password'] = Hash::make(strtolower($request->password));
        }

        $user->update($updates);

        return back()->with('success', 'Profile updated successfully! You can update again next month.');
    }

    // ── Check if user can edit (once per month) ───────────────
    private function canEditThisMonth($user): bool
    {
        if (!$user->profile_updated_at) return true;

        $lastUpdate = \Carbon\Carbon::parse($user->profile_updated_at);
        return $lastUpdate->addMonth()->isPast();
    }
}
