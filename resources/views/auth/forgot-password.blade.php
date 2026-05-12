@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')
<div style="text-align:center;margin-bottom:24px;">
    <div style="width:56px;height:56px;border-radius:50%;background:#EFF6FF;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <i class="bi bi-lock-fill" style="font-size:1.5rem;color:#1A56A0;"></i>
    </div>
    <h2 class="auth-title" style="margin-bottom:4px;">Forgot Password?</h2>
    <p class="auth-subtitle">Enter your email and we'll help you reset it.</p>
</div>

@if(session('success'))
    <div class="alert alert-success" style="border-radius:10px;font-size:0.85rem;">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger" style="border-radius:10px;font-size:0.85rem;">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div style="background:#FFF7ED;border:1px solid #FED7AA;border-radius:10px;padding:14px 16px;margin-bottom:20px;font-size:0.82rem;color:#92400E;">
    <i class="bi bi-info-circle me-1"></i>
    <strong>NORSU EventTrack</strong> — If you're having trouble accessing your account, please contact your system administrator directly.
</div>

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="mb-4">
        <label class="form-label">Email Address *</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" placeholder="you@norsu.edu.ph" required autofocus>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn-et-primary w-100 py-2 mb-3">Send Reset Link</button>

    <p style="text-align:center;font-size:0.85rem;color:#64748b;margin:0;">
        <a href="{{ route('login') }}" style="color:var(--et-primary);font-weight:600;">
            <i class="bi bi-arrow-left me-1"></i>Back to Login
        </a>
    </p>
</form>
@endsection
