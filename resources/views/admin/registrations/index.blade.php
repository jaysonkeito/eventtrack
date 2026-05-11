{{-- Save as: resources/views/admin/registrations/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Registrations')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Registrations</h1><p class="page-subtitle">Manage all event registrations.</p></div>
</div>
<div class="et-card">
    <div class="card-body p-0">
        <table class="et-table">
            <thead><tr><th>#</th><th>Attendee</th><th>Event</th><th>Code</th><th>Status</th><th>Registered</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($registrations as $reg)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="font-weight:600;">{{ $reg->user->full_name }}</td>
                    <td style="font-size:0.82rem;">{{ Str::limit($reg->event->title, 25) }}</td>
                    <td><code style="font-size:0.78rem;">{{ $reg->registration_code }}</code></td>
                    <td>{!! $reg->status_badge !!}</td>
                    <td style="font-size:0.8rem;">{{ $reg->created_at->format('M j, Y') }}</td>
                    <td>
                        @if($reg->status === 'pending')
                        <form method="POST" action="{{ route('admin.registrations.approve', $reg) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-success me-1"><i class="bi bi-check-lg"></i> Approve</button>
                        </form>
                        <form method="POST" action="{{ route('admin.registrations.reject', $reg) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i> Reject</button>
                        </form>
                        @else
                            <span class="text-muted" style="font-size:0.8rem;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No registrations found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0">{{ $registrations->links() }}</div>
</div>
@endsection
