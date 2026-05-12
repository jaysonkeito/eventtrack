<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // NORSU Colleges and Programs
    public static array $colleges = [
        'College of Agriculture and Forestry' => [
            'Bachelor of Science in Agronomy',
            'Bachelor of Science in Animal Science',
            'Bachelor of Science in Forestry',
        ],
        'College of Arts and Sciences' => [
            'Bachelor of Science in Information Technology',
            'Bachelor of Science in Computer Science',
        ],
        'College of Business Administration' => [
            'Bachelor of Science in Business Administration Major in Human Resource Management',
            'Bachelor of Science in Hospitality Management',
            'Bachelor of Science in Office Management',
        ],
        'College of Criminal Justice Education' => [
            'Bachelor of Science in Criminology',
        ],
        'College of Industrial Technology' => [
            'Bachelor of Science in Industrial Technology Major in Automotive',
            'Bachelor of Science in Industrial Technology Major in Computer Technology',
            'Bachelor of Science in Industrial Technology Major in Electronics',
        ],
        'College of Teacher Education' => [
            'Bachelor of Elementary Education Major in General Curriculum',
            'Bachelor of Secondary Education Major in English',
            'Bachelor of Secondary Education Major in Mathematics',
            'Bachelor of Secondary Education Major in Science',
        ],
    ];

    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $role = $request->input('role', 'attendee');

        // ── STUDENT REGISTRATION ──────────────────────────────
        if ($role === 'attendee') {
            $request->validate([
                'student_id' => ['required', 'string', 'max:20'],
            ]);

            $studentId = trim($request->student_id);

            // Find pre-registered student by admin
            $student = User::where('student_id', $studentId)
                           ->where('role', 'attendee')
                           ->first();

            if (!$student) {
                return back()->withErrors([
                    'student_id' => 'Student ID ' . $studentId . ' is not registered in the system. Please contact the administrator.',
                ])->withInput();
            }

            if ($student->status === 'banned') {
                return back()->withErrors([
                    'student_id' => 'This account has been suspended.',
                ])->withInput();
            }

            // Check if already self-registered (email already set)
            if ($student->email_verified_at) {
                return back()->withErrors([
                    'student_id' => 'This Student ID has already been registered. Please log in instead.',
                ])->withInput();
            }

            // Mark as verified/activated — they can now log in
            $student->update([
                'email_verified_at' => now(),
                'status'            => 'active',
            ]);

            return redirect()->route('login')
                ->with('success', 'Your student account is ready! Log in using your Student ID and your last name as the default password.');
        }

        // ── ORGANIZER REGISTRATION ────────────────────────────
        if ($role === 'organizer') {
            $request->validate([
                'student_id' => ['required', 'string', 'max:20'],
                'email'      => ['required', 'email', 'max:150'],
            ]);

            $studentId = trim($request->student_id);

            // Must be a pre-registered student
            $student = User::where('student_id', $studentId)
                           ->where('role', 'attendee')
                           ->first();

            if (!$student) {
                return back()->withErrors([
                    'student_id' => 'Student ID ' . $studentId . ' is not registered in the system.',
                ])->withInput();
            }

            // Check if already an organizer application exists
            $existing = User::where('student_id', $studentId)
                            ->where('role', 'organizer')
                            ->first();

            if ($existing) {
                return back()->withErrors([
                    'student_id' => 'An organizer application already exists for this Student ID.',
                ])->withInput();
            }

            // Create organizer account (inactive = pending approval)
            User::create([
                'first_name' => $student->first_name,
                'last_name'  => $student->last_name,
                'email'      => trim($request->email),
                'student_id' => $studentId . '-ORG',   // separate record
                'year_level' => $student->year_level,
                'college'    => $student->college,
                'program'    => $student->program,
                'password'   => Hash::make(strtolower($student->last_name)),
                'role'       => 'organizer',
                'status'     => 'inactive',             // pending admin approval
            ]);

            return redirect()->route('login')
                ->with('success', 'Your organizer application has been submitted. You will be notified once approved by the admin. Default password will be your last name.');
        }

        return back()->with('error', 'Invalid registration type.');
    }
}
