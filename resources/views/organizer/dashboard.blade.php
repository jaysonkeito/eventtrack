{{-- resources/views/organizer/dashboard.blade.php --}}
@extends('layouts.app')
@section('title','Organizer Dashboard')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">My Dashboard</h1><p class="page-subtitle">Welcome, {{ auth()->user()->first_name }}!</p></div>
    <a href="{{ route('organizer.events.create') }}" class="btn-et-primary"><i class="bi bi-plus-lg me-1"></i> Create Event</a>
</div>
<div class="row g-4 mb-4">
    <div class="col-sm-4">
        <div class="stat-card"><div class="stat-icon blue"><i class="bi bi-calendar-event"></i></div>
            <div><div class="stat-value">{{ $myEvents }}</div><div class="stat-label">My Events</div></div></div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card"><div class="stat-icon green"><i class="bi bi-person-check"></i></div>
            <div><div class="stat-value">{{ $totalRegistrations }}</div><div class="stat-label">Total Registrations</div></div></div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card"><div class="stat-icon amber"><i class="bi bi-qr-code-scan"></i></div>
            <div><div class="stat-value">{{ $totalAttended }}</div><div class="stat-label">Total Attended</div></div></div>
    </div>
</div>
<div class="et-card">
    <div class="card-header"><i class="bi bi-calendar-event me-2"></i>My Events</div>
    <div class="card-body p-0">
        <table class="et-table">
            <thead><tr><th>Event</th><th>Date</th><th>Registered</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($events as $event)
                <tr>
                    <td style="font-weight:600;">{{ $event->title }}</td>
                    <td style="font-size:0.82rem;">{{ $event->start_datetime->format('M j, Y') }}</td>
                    <td><span class="badge bg-primary-soft">{{ $event->registrations_count }}</span></td>
                    <td>{!! $event->status_badge !!}</td>
                    <td>
                        <a href="{{ route('organizer.events.edit', $event) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                        <a href="{{ route('organizer.attendance.scanner') }}" class="btn btn-sm btn-outline-success"><i class="bi bi-qr-code-scan"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">No events yet. <a href="{{ route('organizer.events.create') }}">Create one!</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
