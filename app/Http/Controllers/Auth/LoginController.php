<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_id' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginId  = trim($request->login_id);
        // ── Always lowercase the password before matching ─────
        // This means students can type "Francisco" or "FRANCISCO"
        // and it will still match the stored lowercase hash
        $password = strtolower(trim($request->password));

        $isEmail  = filter_var($loginId, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            // Admin and Organizer login via email
            $user = User::where('email', $loginId)
                        ->whereIn('role', ['admin', 'organizer'])
                        ->first();

            if (!$user) {
                return back()->withErrors([
                    'login_id' => 'No admin or organizer account found with that email.',
                ])->onlyInput('login_id');
            }
        } else {
            // Student login via Student ID
            $user = User::where('student_id', $loginId)
                        ->where('role', 'attendee')
                        ->first();

            if (!$user) {
                return back()->withErrors([
                    'login_id' => 'Student ID not found. Please check your ID or contact the administrator.',
                ])->onlyInput('login_id');
            }
        }

        // ── Check password (always compared as lowercase) ─────
        if (!Hash::check($password, $user->password)) {
            $hint = $isEmail
                ? 'Incorrect password.'
                : 'Incorrect password. Default password is your last name in lowercase (e.g. "' . strtolower($user->last_name) . '").';

            return back()->withErrors(['login_id' => $hint])->onlyInput('login_id');
        }

        // ── Check account status ──────────────────────────────
        if ($user->status === 'inactive') {
            return back()->withErrors([
                'login_id' => 'Your account is pending approval by the administrator.',
            ])->onlyInput('login_id');
        }

        if ($user->status === 'banned') {
            return back()->withErrors([
                'login_id' => 'Your account has been suspended. Please contact the administrator.',
            ])->onlyInput('login_id');
        }

        // ── Login ─────────────────────────────────────────────
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route($user->role . '.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
