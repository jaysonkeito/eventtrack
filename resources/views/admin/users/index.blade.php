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
<div class="alert" style="background:#FFFBEB;border:1.5px solid #FCD34D;border-radius:12px;color:#92400E;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
    <i class="bi bi-hourglass-split" style="font-size:1.2rem;"></i>
    <div>
        <strong>{{ $pendingOrganizers }} organizer account(s)</strong> are pending your approval.
        <a href="?role=organizer&status=inactive" style="color:#92400E;font-weight:700;text-decoration:underline;margin-left:6px;">Review now →</a>
    </div>
</div>
@endif

{{-- Search & Filter Card --}}
<div class="et-card mb-3">
    <div class="card-body">
        {{-- Live Search Row --}}
        <div class="d-flex gap-2 mb-3 align-items-center">
            <div style="position:relative;flex:1;max-width:380px;">
                <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;"></i>
                <input type="text"
                       id="liveSearch"
                       class="form-control"
                       style="padding-left:36px;"
                       placeholder="Live search: name, Student ID, email..."
                       autocomplete="off">
                {{-- Live search dropdown --}}
                <div id="searchDropdown"
                     style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;background:#fff;border:1.5px solid #e2e8f0;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,0.12);z-index:1000;max-height:320px;overflow-y:auto;">
                    <div id="searchResults"></div>
                </div>
            </div>
            <div style="font-size:0.78rem;color:#94a3b8;white-space:nowrap;">
                <i class="bi bi-lightning me-1"></i>Results appear as you type
            </div>
        </div>

        {{-- Filter Row --}}
        <form class="d-flex flex-wrap gap-2 align-items-center" method="GET" id="filterForm">
            <select name="role" id="roleFilter" class="form-select form-select-sm" style="max-width:140px;">
                <option value="">All Roles</option>
                @foreach(['admin','organizer','attendee'] as $r)
                    <option value="{{ $r }}" {{ request('role')===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select form-select-sm" style="max-width:140px;">
                <option value="">All Status</option>
                @foreach(['active','inactive','banned'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="college" class="form-select form-select-sm" style="max-width:240px;">
                <option value="">All Colleges</option>
                @foreach($colleges as $c)
                    <option value="{{ $c }}" {{ request('college')===$c?'selected':'' }}>{{ $c }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x me-1"></i> Reset
            </a>
        </form>
    </div>
</div>

{{-- Users Table --}}
<div class="et-card" id="mainTableCard">
    <div class="card-body p-0" id="tableWrapper">
        <table class="et-table" id="mainTable">
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
            <tbody id="mainTableBody">
                @forelse($users as $user)
                @include('admin.users._row', ['user' => $user, 'loop' => $loop])
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0" id="paginationWrapper">{{ $users->links() }}</div>
</div>

@push('scripts')
<script>
const searchInput    = document.getElementById('liveSearch');
const dropdown       = document.getElementById('searchDropdown');
const resultsDiv     = document.getElementById('searchResults');
const roleFilter     = document.getElementById('roleFilter');

let searchTimeout = null;

const statusColors = { active: '#16A34A', inactive: '#D97706', banned: '#DC2626' };
const roleColors   = { admin: '#7C3AED', organizer: '#0891B2', attendee: '#1A56A0' };

function renderDropdownRow(user) {
    return `
    <div class="live-result-row" onclick="selectUser(${user.id})"
         style="padding:10px 16px;cursor:pointer;border-bottom:1px solid #f1f5f9;transition:background 0.15s;"
         onmouseenter="this.style.background='#F8FAFC'" onmouseleave="this.style.background='#fff'">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:36px;height:36px;border-radius:50%;background:#EFF6FF;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.82rem;color:#1A56A0;flex-shrink:0;">
                ${(user.first_name[0]+user.last_name[0]).toUpperCase()}
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-weight:600;font-size:0.875rem;color:#0f172a;">${user.first_name} ${user.last_name}</div>
                <div style="font-size:0.75rem;color:#64748b;">${user.student_id || user.email || '—'}</div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <span style="font-size:0.7rem;font-weight:700;padding:2px 8px;border-radius:20px;background:${roleColors[user.role]}20;color:${roleColors[user.role]};">${user.role.charAt(0).toUpperCase()+user.role.slice(1)}</span>
                <br>
                <span style="font-size:0.68rem;color:${statusColors[user.status]};">${user.status.charAt(0).toUpperCase()+user.status.slice(1)}</span>
            </div>
        </div>
        ${user.college ? `<div style="font-size:0.72rem;color:#94a3b8;margin-top:4px;padding-left:48px;">${user.college}</div>` : ''}
    </div>`;
}

function selectUser(userId) {
    // Navigate to edit page
    window.location.href = `/admin/users/${userId}/edit`;
}

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const q = this.value.trim();

    if (q.length < 1) {
        dropdown.style.display = 'none';
        return;
    }

    searchTimeout = setTimeout(() => {
        const role = roleFilter.value;
        fetch(`/admin/users/search?q=${encodeURIComponent(q)}&role=${role}`)
            .then(r => r.json())
            .then(users => {
                if (users.length === 0) {
                    resultsDiv.innerHTML = `<div style="padding:16px;text-align:center;color:#94a3b8;font-size:0.85rem;"><i class="bi bi-search me-2"></i>No results for "${q}"</div>`;
                } else {
                    resultsDiv.innerHTML = users.map(renderDropdownRow).join('');
                }
                dropdown.style.display = 'block';
            })
            .catch(() => {
                dropdown.style.display = 'none';
            });
    }, 250);
});

// Hide dropdown on outside click
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});

// Role filter auto-submit for live search
roleFilter.addEventListener('change', function() {
    if (searchInput.value.trim().length > 0) {
        searchInput.dispatchEvent(new Event('input'));
    }
});
</script>
@endpush
@endsection
