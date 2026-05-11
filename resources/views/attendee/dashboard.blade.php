{{-- resources/views/attendee/dashboard.blade.php --}}
@extends('layouts.app')
@section('title','My Dashboard')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">My Dashboard</h1><p class="page-subtitle">Welcome, {{ auth()->user()->first_name }}!</p></div>
    <a href="{{ route('attendee.events.browse') }}" class="btn-et-primary"><i class="bi bi-search me-1"></i> Browse Events</a>
</div>
<div class="row g-4 mb-4">
    <div class="col-sm-4">
        <div class="stat-card"><div class="stat-icon blue"><i class="bi bi-journal-check"></i></div>
            <div><div class="stat-value">{{ $myRegistrations }}</div><div class="stat-label">My Registrations</div></div></div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card"><div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div><div class="stat-value">{{ $attended }}</div><div class="stat-label">Events Attended</div></div></div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card"><div class="stat-icon amber"><i class="bi bi-award"></i></div>
            <div><div class="stat-value">{{ $certificates }}</div><div class="stat-label">Certificates</div></div></div>
    </div>
</div>
<div class="et-card">
    <div class="card-header"><i class="bi bi-journal-check me-2"></i>My Registrations</div>
    <div class="card-body p-0">
        <table class="et-table">
            <thead><tr><th>Event</th><th>Date</th><th>Code</th><th>Status</th><th>QR Code</th></tr></thead>
            <tbody>
                @forelse($registrations as $reg)
                <tr>
                    <td style="font-weight:600;">{{ Str::limit($reg->event->title,30) }}</td>
                    <td style="font-size:0.82rem;">{{ $reg->event->start_datetime->format('M j, Y') }}</td>
                    <td><code style="font-size:0.78rem;">{{ $reg->registration_code }}</code></td>
                    <td>{!! $reg->status_badge !!}</td>
                    <td>
                        @if($reg->status === 'approved')
                            <a href="{{ route('attendee.qr.show', $reg) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-qr-code"></i> View QR</a>
                        @else
                            <span class="text-muted" style="font-size:0.8rem;">Pending approval</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">No registrations yet. <a href="{{ route('attendee.events.browse') }}">Browse events!</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
