<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when($request->role, fn($q) => $q->where('role', $request->role))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->college, fn($q) => $q->where('college', $request->college))
            ->when($request->search, fn($q) => $q->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('last_name', 'like', '%'.$request->search.'%')
                  ->orWhere('student_id', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            }))
            ->latest()->paginate(15);

        $pendingOrganizers = User::where('role', 'organizer')->where('status', 'inactive')->count();
        $colleges = array_keys(RegisterController::$colleges);

        return view('admin.users.index', compact('users', 'pendingOrganizers', 'colleges'));
    }

    public function create()
    {
        $colleges = RegisterController::$colleges;
        return view('admin.users.create', compact('colleges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users',
            'student_id' => 'nullable|string|max:20|unique:users,student_id',
            'password'   => 'required|min:8|confirmed',
            'role'       => 'required|in:admin,organizer,attendee',
            'college'    => 'nullable|string|max:150',
            'program'    => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:20',
        ]);

        User::create([
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
            'status'     => 'active',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        $colleges = RegisterController::$colleges;
        return view('admin.users.edit', compact('user', 'colleges'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,'.$user->id,
            'student_id' => 'nullable|string|max:20|unique:users,student_id,'.$user->id,
            'role'       => 'required|in:admin,organizer,attendee',
            'status'     => 'required|in:active,inactive,banned',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'student_id' => $request->student_id,
            'year_level' => $request->year_level,
            'college'    => $request->college,
            'program'    => $request->program,
            'phone'      => $request->phone,
            'role'       => $request->role,
            'status'     => $request->status,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted!');
    }

    // ── Approve Organizer ─────────────────────────────────────
    public function approveOrganizer(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', $user->full_name . ' has been approved as an Organizer!');
    }

    // ── CSV Upload for Students ───────────────────────────────
    public function showUpload()
    {
        return view('admin.users.upload');
    }

    public function processUpload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file    = $request->file('csv_file');
        $handle  = fopen($file->getRealPath(), 'r');
        $header  = fgetcsv($handle); // skip header row

        $created = 0;
        $skipped = 0;
        $errors  = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 5) { $skipped++; continue; }

            [$studentId, $firstName, $lastName, $yearLevel, $college, $program, $email] = array_pad($row, 7, '');

            $studentId = trim($studentId);
            $email     = trim($email) ?: strtolower(str_replace(' ', '', $firstName . '.' . $lastName)) . '@norsu.edu.ph';

            // Skip duplicates
            if (User::where('student_id', $studentId)->orWhere('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            try {
                User::create([
                    'first_name' => trim($firstName),
                    'last_name'  => trim($lastName),
                    'email'      => $email,
                    'student_id' => $studentId,
                    'year_level' => trim($yearLevel),
                    'college'    => trim($college),
                    'program'    => trim($program),
                    'password'   => Hash::make($studentId ?: 'norsu1234'),
                    'role'       => 'attendee',
                    'status'     => 'active',
                ]);
                $created++;
            } catch (\Exception $e) {
                $errors[] = "Row error: " . $e->getMessage();
                $skipped++;
            }
        }

        fclose($handle);

        $msg = "Import complete: {$created} students added, {$skipped} skipped.";
        if ($errors) $msg .= ' Some rows had errors.';

        return redirect()->route('admin.users.index')->with('success', $msg);
    }
}
