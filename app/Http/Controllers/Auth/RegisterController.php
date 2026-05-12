<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

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
        $colleges = array_keys(self::$colleges);
        $programs = self::$colleges;
        return view('auth.register', compact('colleges', 'programs'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name'  => ['required', 'string', 'max:100'],
            'last_name'   => ['required', 'string', 'max:100'],
            'email'       => ['required', 'string', 'email', 'max:150', 'unique:users'],
            'student_id'  => ['nullable', 'string', 'max:20', 'unique:users,student_id'],
            'year_level'  => ['nullable', 'string', 'max:20'],
            'college'     => ['nullable', 'string', 'max:150'],
            'program'     => ['nullable', 'string', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'role'        => ['required', 'in:organizer,attendee'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Organizers need admin approval — set status to inactive
        $status = $request->role === 'organizer' ? 'inactive' : 'active';

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'student_id' => $request->student_id,
            'year_level' => $request->year_level,
            'college'    => $request->college,
            'program'    => $request->program,
            'phone'      => $request->phone,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'status'     => $status,
        ]);

        // If organizer — don't log them in, show pending message
        if ($request->role === 'organizer') {
            return redirect()->route('login')
                ->with('success', 'Your organizer account has been submitted for approval. You will be notified once approved by the admin.');
        }

        // Attendee — log in immediately
        event(new Registered($user));
        auth()->login($user);

        return redirect()->route('attendee.dashboard')
            ->with('success', 'Welcome to NORSU EventTrack, ' . $user->first_name . '!');
    }
}
