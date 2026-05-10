@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<h2 class="auth-title">Welcome back!</h2>
<p class="auth-subtitle">Sign in to your EventTrack account</p>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
               placeholder="••••••••" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember" style="font-size:0.85rem;">Remember me</label>
        </div>
        <a href="{{ route('password.request') }}" style="font-size:0.85rem; color:var(--et-primary);">Forgot password?</a>
    </div>

    <button type="submit" class="btn-et-primary w-100 py-2 mb-3">Sign In</button>

    <p style="text-align:center; font-size:0.85rem; color:#64748b; margin:0;">
        Don't have an account?
        <a href="{{ route('register') }}" style="color:var(--et-primary); font-weight:600;">Create one</a>
    </p>
</form>
@endsection
