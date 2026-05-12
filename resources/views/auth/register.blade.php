@extends('layouts.auth')
@section('title', 'Register')

@section('content')
<div style="text-align:center;margin-bottom:24px;">
    <div style="font-size:0.72rem;font-weight:700;color:#64748b;letter-spacing:1.2px;text-transform:uppercase;margin-bottom:6px;">
        Negros Oriental State University
    </div>
    <h2 class="auth-title" style="margin-bottom:4px;">Create Account</h2>
    <p class="auth-subtitle">Join NORSU EventTrack</p>
</div>

@if($errors->any())
    <div class="alert alert-danger" style="border-radius:10px;font-size:0.85rem;margin-bottom:20px;">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Role Selector --}}
<div class="mb-4">
    <label class="form-label">I am registering as *</label>
    <div class="d-flex gap-3">
        <label style="flex:1;cursor:pointer;">
            <input type="radio" name="role" value="attendee" id="roleAttendee"
                   {{ old('role','attendee')==='attendee'?'checked':'' }} class="d-none">
            <div id="cardAttendee" style="border:2px solid #1A56A0;border-radius:12px;padding:16px;text-align:center;background:#EFF6FF;transition:all 0.2s;">
                <i class="bi bi-person-fill" style="font-size:1.6rem;color:#1A56A0;"></i>
                <div style="font-weight:700;font-size:0.88rem;margin-top:6px;color:#0f172a;">Student</div>
                <div style="font-size:0.72rem;color:#64748b;margin-top:2px;">Browse & join events</div>
            </div>
        </label>
        <label style="flex:1;cursor:pointer;">
            <input type="radio" name="role" value="organizer" id="roleOrganizer"
                   {{ old('role')==='organizer'?'checked':'' }} class="d-none">
            <div id="cardOrganizer" style="border:2px solid #e2e8f0;border-radius:12px;padding:16px;text-align:center;background:#fff;transition:all 0.2s;">
                <i class="bi bi-calendar-check-fill" style="font-size:1.6rem;color:#1A56A0;"></i>
                <div style="font-weight:700;font-size:0.88rem;margin-top:6px;color:#0f172a;">Organizer</div>
                <div style="font-size:0.72rem;color:#64748b;margin-top:2px;">Manage events</div>
            </div>
        </label>
    </div>
</div>

{{-- STUDENT FORM ─────────────────────────────────────────── --}}
<div id="studentForm">
    <div style="background:#EFF6FF;border:1.5px solid #BFDBFE;border-radius:10px;padding:14px 16px;margin-bottom:20px;font-size:0.82rem;color:#1e40af;">
        <i class="bi bi-info-circle me-2"></i>
        Enter your <strong>Student ID</strong>. If you're pre-registered by the admin, your account will be activated and you can log in using your Student ID and last name as your default password.
    </div>

    <div class="mb-4">
        <label class="form-label">Student ID *</label>
        <div style="position:relative;">
            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;">
                <i class="bi bi-person-badge"></i>
            </span>
            <input type="text"
                   name="student_id"
                   class="form-control @error('student_id') is-invalid @enderror"
                   style="padding-left:36px;font-family:monospace;font-size:1rem;letter-spacing:1px;"
                   value="{{ old('student_id') }}"
                   placeholder="e.g. 202300123"
                   maxlength="20"
                   autofocus>
            @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div style="font-size:0.75rem;color:#64748b;margin-top:6px;">
            <i class="bi bi-shield-check me-1"></i>Only pre-registered students can activate their account.
        </div>
    </div>

    <button type="submit" form="registerForm" class="btn-et-primary w-100 py-2 mb-3" id="studentSubmitBtn">
        <i class="bi bi-check-circle me-1"></i> Activate My Account
    </button>
</div>

{{-- ORGANIZER FORM ───────────────────────────────────────── --}}
<div id="organizerForm" style="display:none;">
    <div style="background:#FFFBEB;border:1.5px solid #FCD34D;border-radius:10px;padding:14px 16px;margin-bottom:20px;font-size:0.82rem;color:#92400E;">
        <i class="bi bi-hourglass-split me-2"></i>
        Organizer accounts require <strong>admin approval</strong>. You must be a registered NORSU student to apply.
        Once approved, log in using your email address.
    </div>

    <div class="mb-3">
        <label class="form-label">Student ID *</label>
        <div style="position:relative;">
            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;">
                <i class="bi bi-person-badge"></i>
            </span>
            <input type="text"
                   name="student_id"
                   class="form-control @error('student_id') is-invalid @enderror"
                   style="padding-left:36px;font-family:monospace;"
                   value="{{ old('student_id') }}"
                   placeholder="Your registered Student ID">
            @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label">Email Address *</label>
        <div style="position:relative;">
            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;">
                <i class="bi bi-envelope"></i>
            </span>
            <input type="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   style="padding-left:36px;"
                   value="{{ old('email') }}"
                   placeholder="you@norsu.edu.ph">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div style="font-size:0.75rem;color:#64748b;margin-top:6px;">
            <i class="bi bi-info-circle me-1"></i>You'll use this email to log in once approved. Default password is your last name.
        </div>
    </div>

    <button type="submit" form="registerForm" class="btn-et-primary w-100 py-2 mb-3">
        <i class="bi bi-send me-1"></i> Submit Organizer Application
    </button>
</div>

<form id="registerForm" method="POST" action="{{ route('register') }}">
    @csrf
    <input type="hidden" name="role" id="hiddenRole" value="{{ old('role','attendee') }}">
</form>

<p style="text-align:center;font-size:0.82rem;color:#64748b;margin-top:8px;">
    Already have an account?
    <a href="{{ route('login') }}" style="color:var(--et-primary);font-weight:600;">Sign In</a>
</p>

<script>
// Move form inputs into hidden form on submit
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const role = document.getElementById('hiddenRole').value;
    const form = this;

    // Copy student_id inputs
    document.querySelectorAll('input[name="student_id"]').forEach(input => {
        if (input.value) {
            const hidden = document.createElement('input');
            hidden.type  = 'hidden';
            hidden.name  = 'student_id';
            hidden.value = input.value;
            form.appendChild(hidden);
        }
    });

    // Copy email
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput && emailInput.value) {
        const hidden = document.createElement('input');
        hidden.type  = 'hidden';
        hidden.name  = 'email';
        hidden.value = emailInput.value;
        form.appendChild(hidden);
    }
});

// Role card toggle
function setRole(role) {
    document.getElementById('hiddenRole').value = role;

    const cardA  = document.getElementById('cardAttendee');
    const cardO  = document.getElementById('cardOrganizer');
    const formS  = document.getElementById('studentForm');
    const formO  = document.getElementById('organizerForm');

    if (role === 'attendee') {
        cardA.style.border      = '2px solid #1A56A0';
        cardA.style.background  = '#EFF6FF';
        cardO.style.border      = '2px solid #e2e8f0';
        cardO.style.background  = '#fff';
        formS.style.display     = 'block';
        formO.style.display     = 'none';
    } else {
        cardO.style.border      = '2px solid #1A56A0';
        cardO.style.background  = '#EFF6FF';
        cardA.style.border      = '2px solid #e2e8f0';
        cardA.style.background  = '#fff';
        formO.style.display     = 'block';
        formS.style.display     = 'none';
    }
}

document.getElementById('roleAttendee').addEventListener('change', () => setRole('attendee'));
document.getElementById('roleOrganizer').addEventListener('change', () => setRole('organizer'));

// Init on load
setRole('{{ old('role','attendee') }}');
</script>
@endsection
