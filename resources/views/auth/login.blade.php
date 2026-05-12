@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div style="text-align:center;margin-bottom:28px;">
    <div style="font-size:0.72rem;font-weight:700;color:#64748b;letter-spacing:1.2px;text-transform:uppercase;margin-bottom:6px;">
        Negros Oriental State University
    </div>
    <h2 class="auth-title" style="margin-bottom:4px;">Welcome back!</h2>
    <p class="auth-subtitle">Sign in to your EventTrack account</p>
</div>

@if(session('success'))
    <div class="alert alert-success" style="border-radius:10px;font-size:0.85rem;margin-bottom:20px;">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger" style="border-radius:10px;font-size:0.85rem;margin-bottom:20px;">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first('login_id') }}
    </div>
@endif

{{-- Login hint tabs --}}
<div style="display:flex;background:#F1F5FB;border-radius:10px;padding:4px;margin-bottom:24px;gap:4px;">
    <button type="button" id="tabStudent"
        onclick="setLoginMode('student')"
        style="flex:1;border:none;border-radius:8px;padding:8px;font-size:0.82rem;font-weight:600;cursor:pointer;transition:all 0.2s;background:#fff;color:#1A56A0;box-shadow:0 1px 4px rgba(0,0,0,0.08);">
        <i class="bi bi-person me-1"></i> Student
    </button>
    <button type="button" id="tabStaff"
        onclick="setLoginMode('staff')"
        style="flex:1;border:none;border-radius:8px;padding:8px;font-size:0.82rem;font-weight:600;cursor:pointer;transition:all 0.2s;background:transparent;color:#64748b;">
        <i class="bi bi-shield-lock me-1"></i> Admin / Organizer
    </button>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label" id="loginLabel">Student ID</label>
        <div style="position:relative;">
            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;">
                <i class="bi bi-person-badge" id="loginIcon"></i>
            </span>
            <input type="text"
                   name="login_id"
                   id="loginInput"
                   class="form-control @error('login_id') is-invalid @enderror"
                   style="padding-left:36px;"
                   value="{{ old('login_id') }}"
                   placeholder="Enter your Student ID"
                   required
                   autofocus>
        </div>
        <div id="loginHint" style="font-size:0.75rem;color:#64748b;margin-top:5px;">
            <i class="bi bi-info-circle me-1"></i>Students: use your 9-digit Student ID (e.g. 202300123)
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label">Password</label>
        <div style="position:relative;">
            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;">
                <i class="bi bi-lock"></i>
            </span>
            <input type="password"
                   name="password"
                   id="passwordInput"
                   class="form-control"
                   style="padding-left:36px;padding-right:40px;"
                   placeholder="Enter your password"
                   required>
            <button type="button"
                    onclick="togglePassword()"
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#94a3b8;cursor:pointer;">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
        </div>
        <div id="passwordHint" style="font-size:0.75rem;color:#64748b;margin-top:5px;">
            <i class="bi bi-info-circle me-1"></i>Default password is your <strong>last name</strong> (lowercase)
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember" style="font-size:0.82rem;">Remember me</label>
        </div>
        <a href="{{ route('password.request') }}" style="font-size:0.82rem;color:var(--et-primary);">Forgot password?</a>
    </div>

    <button type="submit" class="btn-et-primary w-100 py-2 mb-3">
        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
    </button>

    <p style="text-align:center;font-size:0.82rem;color:#64748b;margin:0;">
        Don't have an account?
        <a href="{{ route('register') }}" style="color:var(--et-primary);font-weight:600;">Register here</a>
    </p>
</form>

<script>
function setLoginMode(mode) {
    const label    = document.getElementById('loginLabel');
    const input    = document.getElementById('loginInput');
    const icon     = document.getElementById('loginIcon');
    const hint     = document.getElementById('loginHint');
    const tabStu   = document.getElementById('tabStudent');
    const tabStaff = document.getElementById('tabStaff');
    const pwHint   = document.getElementById('passwordHint');

    if (mode === 'student') {
        label.textContent       = 'Student ID';
        input.placeholder       = 'Enter your Student ID (e.g. 202300123)';
        input.type              = 'text';
        icon.className          = 'bi bi-person-badge';
        hint.innerHTML          = '<i class="bi bi-info-circle me-1"></i>Students: use your 9-digit Student ID';
        pwHint.innerHTML        = '<i class="bi bi-info-circle me-1"></i>Default password is your <strong>last name</strong> (lowercase)';
        tabStu.style.background = '#fff';
        tabStu.style.color      = '#1A56A0';
        tabStu.style.boxShadow  = '0 1px 4px rgba(0,0,0,0.08)';
        tabStaff.style.background = 'transparent';
        tabStaff.style.color    = '#64748b';
        tabStaff.style.boxShadow = 'none';
    } else {
        label.textContent       = 'Email Address';
        input.placeholder       = 'Enter your email address';
        input.type              = 'email';
        icon.className          = 'bi bi-envelope';
        hint.innerHTML          = '<i class="bi bi-info-circle me-1"></i>Admin & Organizers: use your registered email';
        pwHint.innerHTML        = '<i class="bi bi-info-circle me-1"></i>Organizers: default password is your <strong>last name</strong> (lowercase)';
        tabStaff.style.background = '#fff';
        tabStaff.style.color    = '#1A56A0';
        tabStaff.style.boxShadow = '0 1px 4px rgba(0,0,0,0.08)';
        tabStu.style.background = 'transparent';
        tabStu.style.color      = '#64748b';
        tabStu.style.boxShadow  = 'none';
    }
}

function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type    = 'password' === input.type ? 'text' : 'password';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type    = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Fix toggle
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'text' ? 'bi bi-eye-slash' : 'bi bi-eye';
}
</script>
@endsection
