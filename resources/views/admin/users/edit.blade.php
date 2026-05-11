@extends('layouts.app')
@section('title','Edit User')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Edit User</h1></div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
<div class="et-card"><div class="card-body">
<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">First Name *</label><input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required></div>
        <div class="col-md-6"><label class="form-label">Last Name *</label><input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required></div>
        <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}"></div>
        <div class="col-md-4"><label class="form-label">Role</label><select name="role" class="form-select">@foreach(['attendee','organizer','admin'] as $r)<option value="{{ $r }}" {{ old('role',$user->role)===$r?'selected':'' }}>{{ ucfirst($r) }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['active','inactive','banned'] as $s)<option value="{{ $s }}" {{ old('status',$user->status)===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">New Password</label><input type="password" name="password" class="form-control"></div>
        <div class="col-12"><button type="submit" class="btn-et-primary px-5">Update User</button></div>
    </div>
</form>
</div></div>
@endsection