@extends('layouts.app')
@section('title','Add User')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Add User</h1></div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
<div class="et-card"><div class="card-body">
<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">First Name *</label><input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required></div>
        <div class="col-md-6"><label class="form-label">Last Name *</label><input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required></div>
        <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
        <div class="col-md-4"><label class="form-label">Role *</label><select name="role" class="form-select"><option value="attendee">Attendee</option><option value="organizer">Organizer</option><option value="admin">Admin</option></select></div>
        <div class="col-md-4"><label class="form-label">Password *</label><input type="password" name="password" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">Confirm Password *</label><input type="password" name="password_confirmation" class="form-control" required></div>
        <div class="col-12"><button type="submit" class="btn-et-primary px-5">Create User</button></div>
    </div>
</form>
</div></div>
@endsection