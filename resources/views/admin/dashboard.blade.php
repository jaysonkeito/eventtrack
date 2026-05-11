@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Welcome back, {{ auth()->user()->first_name }}!</p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="btn-et-primary">
        <i class="bi bi-plus-lg me-1"></i> New Event
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-calendar-event"></i></div>
            <div><div class="stat-value">{{ $stats['total_events'] }}</div><div class="stat-label">Total Events</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-people"></i></div>
            <div><div class="stat-value">{{ $stats['total_users'] }}</div><div class="stat-label">Total Users</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-person-check"></i></div>
            <div><div class="stat-value">{{ $stats['total_registrations'] }}</div><div class="stat-label">Registrations</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-qr-code-scan"></i></div>
            <div><div class="stat-value">{{ $stats['total_attended'] }}</div><div class="stat-label">Attended</div></div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-value">{{ $stats['pending_registrations'] }}</div><div class="stat-label">Pending Approvals</div></div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-broadcast"></i></div>
            <div><div class="stat-value">{{ $stats['published_events'] }}</div><div class="stat-label">Published Events</div></div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-play-circle"></i></div>
            <div><div class="stat-value">{{ $stats['ongoing_events'] }}</div><div class="stat-label">Ongoing Events</div></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="et-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-event me-2"></i>Recent Events</span>
                <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="et-table">
                    <thead><tr><th>Event</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        @forelse($recentEvents as $event)
                        <tr>
                            <td>
                                <div style="font-weight:600;">{{ Str::limit($event->title, 30) }}</div>
                                <div style="font-size:0.75rem;color:#64748b;">{{ $event->category->name ?? 'No Category' }}</div>
                            </td>
                            <td style="font-size:0.8rem;">{{ $event->start_datetime->format('M j, Y') }}</td>
                            <td>{!! $event->status_badge !!}</td>
                            <td><a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">No events yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="et-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person-check me-2"></i>Recent Registrations</span>
                <a href="{{ route('admin.registrations.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="et-table">
                    <thead><tr><th>Attendee</th><th>Event</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($recentRegistrations as $reg)
                        <tr>
                            <td style="font-size:0.82rem;font-weight:600;">{{ $reg->user->first_name }} {{ $reg->user->last_name }}</td>
                            <td style="font-size:0.78rem;color:#64748b;">{{ Str::limit($reg->event->title, 20) }}</td>
                            <td>{!! $reg->status_badge !!}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4 text-muted">No registrations yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
