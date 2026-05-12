@extends('layouts.app')
@section('title', 'Add User')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Add User</h1><p class="page-subtitle">Manually add a student or staff account.</p></div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="et-card"><div class="card-body">
<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <div class="row g-3">
        {{-- Basic Info --}}
        <div class="col-12"><h6 style="font-weight:700;color:#1A56A0;border-bottom:2px solid #EFF6FF;padding-bottom:8px;">Basic Information</h6></div>

        <div class="col-md-4">
            <label class="form-label">Student ID</label>
            <input type="text" name="student_id" class="form-control @error('student_id') is-invalid @enderror" value="{{ old('student_id') }}" placeholder="2021-00123">
            @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">First Name *</label>
            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Last Name *</label>
            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Year Level</label>
            <select name="year_level" class="form-select">
                <option value="">-- Select --</option>
                @foreach(['1st Year','2nd Year','3rd Year','4th Year','5th Year'] as $yr)
                    <option value="{{ $yr }}" {{ old('year_level')===$yr?'selected':'' }}>{{ $yr }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">College</label>
            <select name="college" id="collegeSelect" class="form-select">
                <option value="">-- Select College --</option>
                @foreach(array_keys($colleges) as $college)
                    <option value="{{ $college }}" {{ old('college')===$college?'selected':'' }}>{{ $college }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Program</label>
            <select name="program" id="programSelect" class="form-select">
                <option value="">-- Select College First --</option>
            </select>
        </div>

        {{-- Account Info --}}
        <div class="col-12 mt-2"><h6 style="font-weight:700;color:#1A56A0;border-bottom:2px solid #EFF6FF;padding-bottom:8px;">Account Information</h6></div>

        <div class="col-md-6">
            <label class="form-label">Email Address *</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Role *</label>
            <select name="role" class="form-select">
                <option value="attendee" {{ old('role')==='attendee'?'selected':'' }}>Student (Attendee)</option>
                <option value="organizer" {{ old('role')==='organizer'?'selected':'' }}>Organizer</option>
                <option value="admin" {{ old('role')==='admin'?'selected':'' }}>Admin</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Password *</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Confirm Password *</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="col-12 mt-2">
            <button type="submit" class="btn-et-primary px-5">Create User</button>
        </div>
    </div>
</form>
</div></div>

<script>
const colleges = @json($colleges);
const collegeSelect = document.getElementById('collegeSelect');
const programSelect = document.getElementById('programSelect');
const oldCollege = "{{ old('college') }}";
const oldProgram = "{{ old('program') }}";

function loadPrograms(college, selected = '') {
    programSelect.innerHTML = '<option value="">-- Select Program --</option>';
    if (college && colleges[college]) {
        colleges[college].forEach(p => {
            const opt = document.createElement('option');
            opt.value = p; opt.textContent = p;
            if (p === selected) opt.selected = true;
            programSelect.appendChild(opt);
        });
    }
}
if (oldCollege) loadPrograms(oldCollege, oldProgram);
collegeSelect.addEventListener('change', () => loadPrograms(collegeSelect.value));
</script>
@endsection
