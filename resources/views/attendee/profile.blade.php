@extends('layouts.app')
@section('title', 'My Profile')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">My Profile</h1>
        <p class="page-subtitle">View and update your personal information.</p>
    </div>
</div>

<div class="row g-4">

    {{-- Profile Summary Card --}}
    <div class="col-lg-4">
        <div class="et-card text-center" style="padding:32px 24px;">
            {{-- Avatar --}}
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#1A56A0,#2E75B6);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:2rem;font-weight:800;color:#fff;">
                {{ $user->initials }}
            </div>
            <h5 style="font-weight:700;color:#0f172a;margin-bottom:4px;">{{ $user->full_name }}</h5>
            <div style="font-size:0.82rem;color:#64748b;margin-bottom:16px;">{{ $user->email }}</div>

            {{-- Student Info --}}
            <div style="background:#F8FAFC;border-radius:10px;padding:14px;text-align:left;">
                <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:0.78rem;color:#64748b;">Student ID</span>
                    <span style="font-size:0.82rem;font-weight:600;font-family:monospace;">{{ $user->student_id ?? '—' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:0.78rem;color:#64748b;">Year Level</span>
                    <span style="font-size:0.82rem;font-weight:600;">{{ $user->year_level ?? '—' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:0.78rem;color:#64748b;">College</span>
                    <span style="font-size:0.78rem;font-weight:600;text-align:right;max-width:140px;">{{ $user->college ?? '—' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:6px 0;">
                    <span style="font-size:0.78rem;color:#64748b;">Program</span>
                    <span style="font-size:0.75rem;font-weight:600;text-align:right;max-width:140px;">{{ $user->program ?? '—' }}</span>
                </div>
            </div>

            {{-- Edit limit notice --}}
            <div style="margin-top:16px;">
                @if($canEdit)
                    <div style="background:#F0FDF4;border:1px solid #86EFAC;border-radius:8px;padding:10px;font-size:0.78rem;color:#166534;">
                        <i class="bi bi-check-circle me-1"></i>
                        You can update your profile information.
                    </div>
                @else
                    <div style="background:#FFF7ED;border:1px solid #FED7AA;border-radius:8px;padding:10px;font-size:0.78rem;color:#92400E;">
                        <i class="bi bi-clock me-1"></i>
                        Next profile update allowed on:<br>
                        <strong>{{ $nextEditDate }}</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Edit Form Card --}}
    <div class="col-lg-8">
        <div class="et-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-pencil-square me-2"></i>Update Profile</span>
                @if(!$canEdit)
                    <span class="badge bg-warning text-dark">
                        <i class="bi bi-lock me-1"></i>Locked until {{ $nextEditDate }}
                    </span>
                @endif
            </div>
            <div class="card-body">

                @if(!$canEdit)
                <div style="background:#FFF7ED;border:1.5px solid #FED7AA;border-radius:10px;padding:16px;margin-bottom:20px;font-size:0.85rem;color:#92400E;">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Profile editing is locked.</strong> You can only update your profile <strong>once per month</strong>.
                    Your next allowed update is on <strong>{{ $nextEditDate }}</strong>.
                </div>
                @endif

                <div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:10px;padding:14px;margin-bottom:20px;font-size:0.82rem;color:#1e40af;">
                    <i class="bi bi-shield-check me-2"></i>
                    <strong>Note:</strong> Only email, phone, and password can be updated here.
                    Academic information (Student ID, College, Program) can only be changed by the administrator.
                </div>

                <form method="POST" action="{{ route('attendee.profile.update') }}">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        {{-- Read-only fields --}}
                        <div class="col-md-6">
                            <label class="form-label" style="color:#94a3b8;">First Name <span style="font-size:0.72rem;">(read-only)</span></label>
                            <input type="text" class="form-control" value="{{ $user->first_name }}" disabled style="background:#f8fafc;color:#94a3b8;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="color:#94a3b8;">Last Name <span style="font-size:0.72rem;">(read-only)</span></label>
                            <input type="text" class="form-control" value="{{ $user->last_name }}" disabled style="background:#f8fafc;color:#94a3b8;">
                        </div>

                        {{-- Editable fields --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Email Address
                                <span style="font-size:0.72rem;color:#64748b;">(optional)</span>
                            </label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="your@email.com"
                                   {{ !$canEdit ? 'disabled' : '' }}>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span style="font-size:0.72rem;color:#64748b;">(optional)</span></label>
                            <input type="text" name="phone"
                                   class="form-control"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="09xxxxxxxxx"
                                   {{ !$canEdit ? 'disabled' : '' }}>
                        </div>

                        {{-- Password section --}}
                        <div class="col-12">
                            <hr style="border-color:#f1f5f9;">
                            <h6 style="font-weight:600;color:#374151;margin-bottom:14px;">
                                <i class="bi bi-key me-2"></i>Change Password
                                <span style="font-size:0.75rem;color:#64748b;font-weight:400;">(leave blank to keep current)</span>
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Enter new password"
                                   {{ !$canEdit ? 'disabled' : '' }}>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px;">
                                <i class="bi bi-info-circle me-1"></i>Password will be stored in lowercase automatically.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control"
                                   placeholder="Re-enter new password"
                                   {{ !$canEdit ? 'disabled' : '' }}>
                        </div>

                        @if($canEdit)
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn-et-primary px-5">
                                <i class="bi bi-check-circle me-1"></i> Save Changes
                            </button>
                            <div style="font-size:0.75rem;color:#94a3b8;margin-top:8px;">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                After saving, you won't be able to update again until next month.
                            </div>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
