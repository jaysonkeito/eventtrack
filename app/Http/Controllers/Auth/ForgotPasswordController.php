<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request)
    {
        // For local/university use, simply show a message
        // In production you would send a reset email
        $request->validate(['email' => 'required|email|exists:users,email']);

        return back()->with('success',
            'If that email is registered, a password reset link has been sent. Please contact the system administrator for assistance.'
        );
    }
}
