@extends('layouts.auth')
@section('title', 'Create Account')

@section('content')
<div style="text-align:center;margin-bottom:24px;">
    <div style="font-size:0.75rem;font-weight:700;color:#64748b;letter-spacing:1px;text-transform:uppercase;margin-bottom:6px;">
        Negros Oriental State University
    </div>
    <h2 class="auth-title" style="margin-bottom:4px;">Create Account</h2>
    <p class="auth-subtitle">Join NORSU EventTrack</p>
</div>

@if($errors->any())
    <div class="alert alert-danger" style="border-radius:10px;font-size:0.85rem;">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf

    {{-- Account Type --}}
    <div class="mb-4">
        <label class="form-label">I am registering as *</label>
        <div class="d-flex gap-3">
            <label style="flex:1;cursor:pointer;">
                <input type="radio" name="role" value="attendee" {{ old('role','attendee')==='attendee'?'checked':'' }} class="d-none role-radio" id="roleAttendee">
                <div class="role-card {{ old('role','attendee')==='attendee'?'selected':'' }}" id="cardAttendee"
                     style="border:2px solid {{ old('role','attendee')==='attendee'?'#1A56A0':'#e2e8f0' }};border-radius:10px;padding:14px;text-align:center;transition:all 0.2s;background:{{ old('role','attendee')==='attendee'?'#EFF6FF':'#fff' }};">
                    <i class="bi bi-person-fill" style="font-size:1.5rem;color:#1A56A0;"></i>
                    <div style="font-weight:700;font-size:0.85rem;margin-top:4px;color:#0f172a;">Student</div>
                    <div style="font-size:0.72rem;color:#64748b;">Browse & register for events</div>
                </div>
            </label>
            <label style="flex:1;cursor:pointer;">
                <input type="radio" name="role" value="organizer" {{ old('role')==='organizer'?'checked':'' }} class="d-none role-radio" id="roleOrganizer">
                <div class="role-card {{ old('role')==='organizer'?'selected':'' }}" id="cardOrganizer"
                     style="border:2px solid {{ old('role')==='organizer'?'#1A56A0':'#e2e8f0' }};border-radius:10px;padding:14px;text-align:center;transition:all 0.2s;background:{{ old('role')==='organizer'?'#EFF6FF':'#fff' }};">
                    <i class="bi bi-calendar-check-fill" style="font-size:1.5rem;color:#1A56A0;"></i>
                    <div style="font-weight:700;font-size:0.85rem;margin-top:4px;color:#0f172a;">Organizer</div>
                    <div style="font-size:0.72rem;color:#64748b;">Manage & organize events</div>
                </div>
            </label>
        </div>
        {{-- Organizer notice --}}
        <div id="organizerNotice" style="display:{{ old('role')==='organizer'?'block':'none' }};margin-top:10px;background:#FFFBEB;border:1px solid #FCD34D;border-radius:8px;padding:10px 14px;font-size:0.8rem;color:#92400E;">
            <i class="bi bi-info-circle me-1"></i>
            Organizer accounts require <strong>admin approval</strong> before you can log in. You will be notified once approved.
        </div>
    </div>

    {{-- Name --}}
    <div class="row g-2 mb-3">
        <div class="col-6">
            <label class="form-label">First Name *</label>
            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                   value="{{ old('first_name') }}" placeholder="Juan" required>
            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-6">
            <label class="form-label">Last Name *</label>
            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                   value="{{ old('last_name') }}" placeholder="Dela Cruz" required>
            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Student ID --}}
    <div class="mb-3">
        <label class="form-label">Student ID</label>
        <input type="text" name="student_id" class="form-control @error('student_id') is-invalid @enderror"
               value="{{ old('student_id') }}" placeholder="e.g. 2021-00123">
        @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Year Level --}}
    <div class="mb-3">
        <label class="form-label">Year Level</label>
        <select name="year_level" class="form-select">
            <option value="">-- Select Year Level --</option>
            @foreach(['1st Year','2nd Year','3rd Year','4th Year','5th Year'] as $yr)
                <option value="{{ $yr }}" {{ old('year_level')===$yr?'selected':'' }}>{{ $yr }}</option>
            @endforeach
        </select>
    </div>

    {{-- College --}}
    <div class="mb-3">
        <label class="form-label">College</label>
        <select name="college" id="collegeSelect" class="form-select">
            <option value="">-- Select College --</option>
            @foreach($colleges as $college)
                <option value="{{ $college }}" {{ old('college')===$college?'selected':'' }}>{{ $college }}</option>
            @endforeach
        </select>
    </div>

    {{-- Program (dynamic based on college) --}}
    <div class="mb-3">
        <label class="form-label">Program</label>
        <select name="program" id="programSelect" class="form-select">
            <option value="">-- Select College First --</option>
        </select>
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label class="form-label">Email Address *</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" placeholder="you@norsu.edu.ph" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Phone --}}
    <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="09xxxxxxxxx">
    </div>

    {{-- Password --}}
    <div class="mb-3">
        <label class="form-label">Password *</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
               placeholder="Minimum 8 characters" required>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-4">
        <label class="form-label">Confirm Password *</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password" required>
    </div>

    <button type="submit" class="btn-et-primary w-100 py-2 mb-3">Create Account</button>

    <p style="text-align:center;font-size:0.85rem;color:#64748b;margin:0;">
        Already have an account?
        <a href="{{ route('login') }}" style="color:var(--et-primary);font-weight:600;">Sign In</a>
    </p>
</form>

<script>
// Programs per college
const programs = @json($programs);

const collegeSelect  = document.getElementById('collegeSelect');
const programSelect  = document.getElementById('programSelect');
const oldCollege     = "{{ old('college') }}";
const oldProgram     = "{{ old('program') }}";

function loadPrograms(college, selectedProgram = '') {
    programSelect.innerHTML = '<option value="">-- Select Program --</option>';
    if (college && programs[college]) {
        programs[college].forEach(p => {
            const opt = document.createElement('option');
            opt.value = p;
            opt.textContent = p;
            if (p === selectedProgram) opt.selected = true;
            programSelect.appendChild(opt);
        });
    } else {
        programSelect.innerHTML = '<option value="">-- Select College First --</option>';
    }
}

// Load on page load if old value exists
if (oldCollege) loadPrograms(oldCollege, oldProgram);

collegeSelect.addEventListener('change', () => loadPrograms(collegeSelect.value));

// Role card toggle
document.querySelectorAll('.role-radio').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.role-card').forEach(card => {
            card.style.border      = '2px solid #e2e8f0';
            card.style.background  = '#fff';
        });
        const card = radio.nextElementSibling;
        card.style.border     = '2px solid #1A56A0';
        card.style.background = '#EFF6FF';
        document.getElementById('organizerNotice').style.display =
            radio.value === 'organizer' ? 'block' : 'none';
    });
});
</script>
@endsection
