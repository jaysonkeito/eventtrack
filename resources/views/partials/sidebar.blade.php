<nav id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('home') }}" class="sidebar-brand">
            <i class="bi bi-calendar-check-fill"></i>
            <span>EventTrack</span>
        </a>
    </div>

    <div class="sidebar-user">
        <div class="sidebar-avatar">{{ auth()->user()->initials }}</div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ auth()->user()->full_name }}</div>
            <div class="sidebar-user-role">
                <span class="badge bg-primary-soft">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
            @if(auth()->user()->student_id)
            <div style="font-size:0.7rem;color:rgba(255,255,255,0.4);margin-top:2px;font-family:monospace;">
                {{ auth()->user()->student_id }}
            </div>
            @endif
        </div>
    </div>

    <ul class="sidebar-nav">

        {{-- ── ADMIN MENU ──────────────────────────────── --}}
        @if(auth()->user()->isAdmin())
            <li class="sidebar-label">Main</li>
            <li class="sidebar-item">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="sidebar-label">Management</li>
            <li class="sidebar-item">
                <a href="{{ route('admin.events.index') }}" class="sidebar-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i> Events
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('admin.registrations.index') }}" class="sidebar-link {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}">
                    <i class="bi bi-person-check"></i> Registrations
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('admin.attendance.scanner') }}" class="sidebar-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                    <i class="bi bi-qr-code-scan"></i> QR Attendance
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('admin.certificates.index') }}" class="sidebar-link {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                    <i class="bi bi-award"></i> Certificates
                </a>
            </li>
            <li class="sidebar-label">System</li>
            <li class="sidebar-item">
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Users
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Categories
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('admin.venues.index') }}" class="sidebar-link {{ request()->routeIs('admin.venues.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i> Venues
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i> Reports
                </a>
            </li>

        {{-- ── ORGANIZER MENU ──────────────────────────── --}}
        @elseif(auth()->user()->isOrganizer())
            <li class="sidebar-label">Main</li>
            <li class="sidebar-item">
                <a href="{{ route('organizer.dashboard') }}" class="sidebar-link {{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="sidebar-label">My Events</li>
            <li class="sidebar-item">
                <a href="{{ route('organizer.events.index') }}" class="sidebar-link {{ request()->routeIs('organizer.events.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i> Manage Events
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('organizer.attendance.scanner') }}" class="sidebar-link {{ request()->routeIs('organizer.attendance.*') ? 'active' : '' }}">
                    <i class="bi bi-qr-code-scan"></i> QR Scanner
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('organizer.reports.index') }}" class="sidebar-link {{ request()->routeIs('organizer.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i> Reports
                </a>
            </li>

        {{-- ── ATTENDEE MENU ───────────────────────────── --}}
        @elseif(auth()->user()->isAttendee())
            <li class="sidebar-label">Main</li>
            <li class="sidebar-item">
                <a href="{{ route('attendee.dashboard') }}" class="sidebar-link {{ request()->routeIs('attendee.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="sidebar-label">Events</li>
            <li class="sidebar-item">
                <a href="{{ route('attendee.events.browse') }}" class="sidebar-link {{ request()->routeIs('attendee.events.browse') ? 'active' : '' }}">
                    <i class="bi bi-search"></i> Browse Events
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('attendee.registrations.index') }}" class="sidebar-link {{ request()->routeIs('attendee.registrations.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-check"></i> My Registrations
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('attendee.certificates.index') }}" class="sidebar-link {{ request()->routeIs('attendee.certificates.*') ? 'active' : '' }}">
                    <i class="bi bi-award"></i> My Certificates
                </a>
            </li>
            <li class="sidebar-label">Account</li>
            <li class="sidebar-item">
                <a href="{{ route('attendee.profile') }}" class="sidebar-link {{ request()->routeIs('attendee.profile') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i> My Profile
                </a>
            </li>
        @endif

        <li class="sidebar-divider"></li>
        <li class="sidebar-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link text-danger w-100 border-0 bg-transparent text-start">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </li>

    </ul>
</nav>
