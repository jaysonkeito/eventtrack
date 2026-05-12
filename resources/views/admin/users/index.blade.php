@extends('layouts.app')
@section('title', 'Manage Users')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Users</h1>
        <p class="page-subtitle">Manage all NORSU EventTrack users.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.upload') }}" class="btn btn-outline-primary">
            <i class="bi bi-upload me-1"></i> Import Students
        </a>
        <a href="{{ route('admin.users.create') }}" class="btn-et-primary">
            <i class="bi bi-plus-lg me-1"></i> Add User
        </a>
    </div>
</div>

{{-- Pending Organizers Alert --}}
@if($pendingOrganizers > 0)
<div class="alert" style="background:#FFFBEB;border:1.5px solid #FCD34D;border-radius:12px;color:#92400E;margin-bottom:20px;">
    <i class="bi bi-hourglass-split me-2"></i>
    <strong>{{ $pendingOrganizers }} organizer account(s)</strong> are pending approval.
    <a href="?role=organizer&status=inactive" style="color:#92400E;font-weight:700;text-decoration:underline;">Review now</a>
</div>
@endif

{{-- Filters --}}
<div class="et-card mb-3">
    <div class="card-body">
        <form class="d-flex flex-wrap gap-2" method="GET">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name, ID, email..." value="{{ request('search') }}" style="max-width:220px;">
            <select name="role" class="form-select form-select-sm" style="max-width:130px;">
                <option value="">All Roles</option>
                @foreach(['admin','organizer','attendee'] as $r)
                    <option value="{{ $r }}" {{ request('role')===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select form-select-sm" style="max-width:130px;">
                <option value="">All Status</option>
                @foreach(['active','inactive','banned'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="college" class="form-select form-select-sm" style="max-width:220px;">
                <option value="">All Colleges</option>
                @foreach($colleges as $c)
                    <option value="{{ $c }}" {{ request('college')===$c?'selected':'' }}>{{ $c }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-outline-primary">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </form>
    </div>
</div>

<div class="et-card">
    <div class="card-body p-0">
        <table class="et-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>College / Program</th>
                    <th>Year</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="font-family:monospace;font-size:0.82rem;">{{ $user->student_id ?? '—' }}</td>
                    <td>
                        <div style="font-weight:600;color:#0f172a;">{{ $user->full_name }}</div>
                        <div style="font-size:0.75rem;color:#64748b;">{{ $user->email }}</div>
                    </td>
                    <td style="font-size:0.78rem;">
                        <div style="color:#1A56A0;font-weight:500;">{{ $user->college ?? '—' }}</div>
                        <div style="color:#64748b;">{{ $user->program ? \Str::limit($user->program, 40) : '' }}</div>
                    </td>
                    <td style="font-size:0.82rem;">{{ $user->year_level ?? '—' }}</td>
                    <td><span class="badge bg-primary-soft">{{ ucfirst($user->role) }}</span></td>
                    <td>
                        @php $statusColors = ['active'=>'success','inactive'=>'warning','banned'=>'danger']; @endphp
                        <span class="badge bg-{{ $statusColors[$user->status] ?? 'secondary' }}">{{ ucfirst($user->status) }}</span>
                    </td>
                    <td>
                        {{-- Approve organizer button --}}
                        @if($user->role === 'organizer' && $user->status === 'inactive')
                        <form method="POST" action="{{ route('admin.users.approve', $user) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-success me-1" title="Approve Organizer">
                                <i class="bi bi-check-lg"></i> Approve
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" data-confirm="Delete {{ $user->full_name }}?">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0">{{ $users->links() }}</div>
</div>
@endsection
