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

    {{-- Basic Information --}}
    <div class="col-12 mb-3">
        <h6 style="font-weight:700;color:#1A56A0;border-bottom:2px solid #EFF6FF;padding-bottom:8px;">
            Basic Information
        </h6>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label">Student ID *</label>
            <input type="text" name="student_id"
                   class="form-control @error('student_id') is-invalid @enderror"
                   value="{{ old('student_id') }}"
                   placeholder="e.g. 202300123" required>
            @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">First Name *</label>
            <input type="text" name="first_name"
                   class="form-control @error('first_name') is-invalid @enderror"
                   value="{{ old('first_name') }}" required>
            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Last Name *</label>
            <input type="text" name="last_name" id="lastNameInput"
                   class="form-control @error('last_name') is-invalid @enderror"
                   value="{{ old('last_name') }}" required
                   oninput="updatePasswordHint()">
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
    </div>

    {{-- Account Information --}}
    <div class="col-12 mb-3 mt-2">
        <h6 style="font-weight:700;color:#1A56A0;border-bottom:2px solid #EFF6FF;padding-bottom:8px;">
            Account Information
        </h6>
    </div>

    {{-- Role selector --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label">Role *</label>
            <select name="role" id="roleSelect" class="form-select" onchange="toggleAccountFields()">
                <option value="attendee" {{ old('role','attendee')==='attendee'?'selected':'' }}>Student (Attendee)</option>
                <option value="organizer" {{ old('role')==='organizer'?'selected':'' }}>Organizer</option>
                <option value="admin"     {{ old('role')==='admin'?'selected':'' }}>Admin</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Phone <span id="phoneOptional" style="color:#94a3b8;font-weight:400;">(optional)</span></label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="09xxxxxxxxx">
        </div>
        <div class="col-md-4">
            <label class="form-label">
                Email Address
                <span id="emailOptional" style="color:#94a3b8;font-weight:400;">(optional for students)</span>
            </label>
            <input type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   id="emailInput"
                   placeholder="Optional — student fills later">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Password section --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div id="passwordSection">
                {{-- Student password notice --}}
                <div id="studentPasswordNotice"
                     style="background:#F0FDF4;border:1.5px solid #86EFAC;border-radius:10px;padding:14px 16px;font-size:0.83rem;color:#166534;">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Default password</strong> will automatically be set to the student's
                    <strong>last name in lowercase</strong>.
                    <span id="passwordPreview" style="font-family:monospace;background:#DCFCE7;padding:2px 8px;border-radius:4px;margin-left:4px;"></span>
                    <br>
                    <span style="color:#15803D;font-size:0.78rem;margin-top:4px;display:inline-block;">
                        <i class="bi bi-lock me-1"></i>Students can update this after logging in. All passwords are stored lowercase.
                    </span>
                    <div class="mt-2">
                        <button type="button" onclick="toggleCustomPassword()"
                                style="background:none;border:1px solid #86EFAC;border-radius:6px;padding:4px 12px;font-size:0.78rem;color:#166534;cursor:pointer;">
                            <i class="bi bi-key me-1"></i> Set a custom password instead
                        </button>
                    </div>
                </div>

                {{-- Custom password fields (hidden by default for students) --}}
                <div id="customPasswordFields" style="display:none;" class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Leave blank to use last name">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <div class="col-12">
                        <button type="button" onclick="toggleCustomPassword()"
                                style="background:none;border:none;font-size:0.78rem;color:#94a3b8;cursor:pointer;">
                            <i class="bi bi-x me-1"></i> Cancel — use default (last name)
                        </button>
                    </div>
                </div>

                {{-- Non-student password (required) --}}
                <div id="staffPasswordFields" style="display:none;" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Minimum 8 characters">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn-et-primary px-5">
        <i class="bi bi-person-plus me-1"></i> Create User
    </button>
</form>
</div></div>

<script>
const colleges = @json($colleges);

// College → Program dynamic dropdown
const collegeSelect = document.getElementById('collegeSelect');
const programSelect = document.getElementById('programSelect');
const oldCollege    = "{{ old('college') }}";
const oldProgram    = "{{ old('program') }}";

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

// Password preview as last name is typed
function updatePasswordHint() {
    const lastName = document.getElementById('lastNameInput').value.trim().toLowerCase();
    const preview  = document.getElementById('passwordPreview');
    if (preview) preview.textContent = lastName ? '→ "' + lastName + '"' : '';
}
updatePasswordHint();

// Toggle custom password for students
let customPasswordVisible = false;
function toggleCustomPassword() {
    customPasswordVisible = !customPasswordVisible;
    document.getElementById('customPasswordFields').style.display = customPasswordVisible ? '' : 'none';
    document.getElementById('studentPasswordNotice').style.display = customPasswordVisible ? 'none' : '';
}

// Show/hide password fields based on role
function toggleAccountFields() {
    const role    = document.getElementById('roleSelect').value;
    const isStaff = role === 'admin' || role === 'organizer';

    document.getElementById('studentPasswordNotice').style.display = isStaff ? 'none' : '';
    document.getElementById('customPasswordFields').style.display  = 'none';
    document.getElementById('staffPasswordFields').style.display   = isStaff ? '' : 'none';

    const emailInput    = document.getElementById('emailInput');
    const emailOptional = document.getElementById('emailOptional');

    if (isStaff) {
        emailInput.placeholder    = 'Required for admin/organizer';
        emailInput.required       = true;
        emailOptional.textContent = '(required)';
        emailOptional.style.color = '#DC2626';
    } else {
        emailInput.placeholder    = 'Optional — student fills later';
        emailInput.required       = false;
        emailOptional.textContent = '(optional for students)';
        emailOptional.style.color = '#94a3b8';
    }

    if (customPasswordVisible) toggleCustomPassword();
}
toggleAccountFields();
</script>
@endsection
