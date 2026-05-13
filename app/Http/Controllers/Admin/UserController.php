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
        $users = User::when($request->role,    fn($q) => $q->where('role', $request->role))
            ->when($request->status,  fn($q) => $q->where('status', $request->status))
            ->when($request->college, fn($q) => $q->where('college', $request->college))
            ->when($request->search,  fn($q) => $q->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('last_name',   'like', '%'.$request->search.'%')
                  ->orWhere('student_id',  'like', '%'.$request->search.'%')
                  ->orWhere('email',       'like', '%'.$request->search.'%');
            }))
            ->latest()->paginate(15)->withQueryString();

        $pendingOrganizers = User::where('role', 'organizer')->where('status', 'inactive')->count();
        $colleges          = array_keys(RegisterController::$colleges);

        return view('admin.users.index', compact('users', 'pendingOrganizers', 'colleges'));
    }

    // ── AJAX Live Search ──────────────────────────────────────
    public function search(Request $request)
    {
        $q    = $request->get('q', '');
        $role = $request->get('role', '');

        $users = User::where(function ($query) use ($q) {
                $query->where('first_name', 'like', "%$q%")
                      ->orWhere('last_name',  'like', "%$q%")
                      ->orWhere('student_id', 'like', "%$q%")
                      ->orWhere('email',      'like', "%$q%");
            })
            ->when($role, fn($query) => $query->where('role', $role))
            ->limit(20)
            ->get(['id', 'first_name', 'last_name', 'student_id',
                   'email', 'role', 'status', 'college', 'program', 'year_level']);

        return response()->json($users);
    }

    public function create()
    {
        $colleges = RegisterController::$colleges;
        return view('admin.users.create', compact('colleges'));
    }

    public function store(Request $request)
    {
        $isStudent = $request->role === 'attendee';

        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'student_id' => 'required|string|max:20|unique:users,student_id',
            'role'       => 'required|in:admin,organizer,attendee',
            'college'    => 'nullable|string|max:150',
            'program'    => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:20',
            'phone'      => 'nullable|string|max:20',
        ];

        // Email optional for students, required for admin/organizer
        if ($isStudent) {
            $rules['email']    = 'nullable|email|unique:users,email';
            $rules['password'] = 'nullable|min:4|confirmed';
        } else {
            $rules['email']    = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:8|confirmed';
        }

        $request->validate($rules);

        $lastName = trim($request->last_name);

        // ── Password logic ────────────────────────────────────
        // Always stored as lowercase, whether default or custom
        if ($request->filled('password')) {
            $passwordToStore = Hash::make(strtolower($request->password));
        } else {
            // Default = last name in lowercase
            $passwordToStore = Hash::make(strtolower($lastName));
        }

        // ── Email fallback for students ───────────────────────
        $email = $request->filled('email')
            ? $request->email
            : strtolower(str_replace(' ', '', $request->first_name . '.' . $lastName)) . '@norsu.edu.ph';

        User::create([
            'first_name' => trim($request->first_name),
            'last_name'  => $lastName,
            'email'      => $email,
            'student_id' => trim($request->student_id),
            'year_level' => $request->year_level,
            'college'    => $request->college,
            'program'    => $request->program,
            'phone'      => $request->phone,
            'password'   => $passwordToStore,
            'role'       => $request->role,
            'status'     => 'active',
        ]);

        $msg = $isStudent
            ? "Student added! Default password is \"" . strtolower($lastName) . "\" (last name, lowercase)."
            : 'Account created successfully!';

        return redirect()->route('admin.users.index')->with('success', $msg);
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
            'email'      => 'nullable|email|unique:users,email,'.$user->id,
            'student_id' => 'nullable|string|max:20|unique:users,student_id,'.$user->id,
            'role'       => 'required|in:admin,organizer,attendee',
            'status'     => 'required|in:active,inactive,banned',
        ]);

        $user->update([
            'first_name' => trim($request->first_name),
            'last_name'  => trim($request->last_name),
            'email'      => $request->email,
            'student_id' => trim($request->student_id),
            'year_level' => $request->year_level,
            'college'    => $request->college,
            'program'    => $request->program,
            'phone'      => $request->phone,
            'role'       => $request->role,
            'status'     => $request->status,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make(strtolower($request->password))]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted!');
    }

    public function approveOrganizer(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', $user->full_name . ' has been approved as Organizer!');
    }

    public function showUpload()
    {
        return view('admin.users.upload');
    }

    public function processUpload(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt|max:5120']);

        $handle  = fopen($request->file('csv_file')->getRealPath(), 'r');
        $header  = fgetcsv($handle);
        $created = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 3) { $skipped++; continue; }
            [$studentId, $firstName, $lastName, $yearLevel, $college, $program] = array_pad($row, 6, '');

            $studentId = trim($studentId);
            $lastName  = trim($lastName);

            if (User::where('student_id', $studentId)->exists()) { $skipped++; continue; }

            try {
                User::create([
                    'first_name' => trim($firstName),
                    'last_name'  => $lastName,
                    'email'      => strtolower(str_replace(' ', '', trim($firstName) . '.' . $lastName)) . '@norsu.edu.ph',
                    'student_id' => $studentId,
                    'year_level' => trim($yearLevel),
                    'college'    => trim($college),
                    'program'    => trim($program),
                    'password'   => Hash::make(strtolower($lastName)),
                    'role'       => 'attendee',
                    'status'     => 'active',
                ]);
                $created++;
            } catch (\Exception $e) {
                $skipped++;
            }
        }

        fclose($handle);

        return redirect()->route('admin.users.index')
            ->with('success', "Import complete: {$created} students added, {$skipped} skipped. Default password is each student's last name (lowercase).");
    }
}
