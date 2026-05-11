@extends('layouts.app')
@section('title','My Events')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">My Events</h1></div>
    <a href="{{ route('organizer.events.create') }}" class="btn-et-primary"><i class="bi bi-plus-lg me-1"></i> Create Event</a>
</div>
<div class="et-card"><div class="card-body p-0">
<table class="et-table">
    <thead><tr><th>Event</th><th>Date</th><th>Registered</th><th>Attended</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
        @forelse($events as $event)
        <tr>
            <td style="font-weight:600;">{{ $event->title }}</td>
            <td style="font-size:0.82rem;">{{ $event->start_datetime->format('M j, Y') }}</td>
            <td>{{ $event->registrations_count }}</td>
            <td>{{ $event->attendances_count }}</td>
            <td>{!! $event->status_badge !!}</td>
            <td>
                <a href="{{ route('organizer.events.edit', $event) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('organizer.events.destroy', $event) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" data-confirm="Delete event?"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4 text-muted">No events yet.</td></tr>
        @endforelse
    </tbody>
</table>
</div><div class="card-body pt-0">{{ $events->links() }}</div></div>
@endsection