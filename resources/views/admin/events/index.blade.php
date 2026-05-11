@extends('layouts.app')
@section('title', 'Manage Events')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Events</h1><p class="page-subtitle">Manage all events in the system.</p></div>
    <a href="{{ route('admin.events.create') }}" class="btn-et-primary"><i class="bi bi-plus-lg me-1"></i> New Event</a>
</div>

<div class="et-card">
    <div class="card-header">
        <form class="d-flex gap-2" method="GET">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search events..." value="{{ request('search') }}" style="max-width:220px;">
            <select name="status" class="form-select form-select-sm" style="max-width:150px;">
                <option value="">All Status</option>
                @foreach(['draft','published','ongoing','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-outline-primary">Filter</button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="et-table">
            <thead><tr><th>#</th><th>Event</th><th>Date</th><th>Venue</th><th>Registered</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($events as $event)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $event->title }}</div>
                        <div style="font-size:0.75rem;color:#64748b;">{{ $event->category->name ?? '—' }}</div>
                    </td>
                    <td style="font-size:0.82rem;">
                        {{ $event->start_datetime->format('M j, Y') }}<br>
                        <span style="color:#64748b;">{{ $event->start_datetime->format('g:i A') }}</span>
                    </td>
                    <td style="font-size:0.82rem;">{{ $event->venue->name ?? '—' }}</td>
                    <td><span class="badge bg-primary-soft">{{ $event->registered_count }}</span></td>
                    <td>{!! $event->status_badge !!}</td>
                    <td>
                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" data-confirm="Delete this event?"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No events found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0">{{ $events->links() }}</div>
</div>
@endsection
