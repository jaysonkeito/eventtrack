{{-- resources/views/admin/attendance/index.blade.php --}}
@extends('layouts.app')
@section('title','Attendance Records')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Attendance Records</h1></div>
    <a href="{{ route('admin.attendance.scanner') }}" class="btn-et-primary"><i class="bi bi-qr-code-scan me-1"></i> Open Scanner</a>
</div>
<div class="et-card">
    <div class="card-body p-0">
        <table class="et-table">
            <thead><tr><th>#</th><th>Attendee</th><th>Event</th><th>Time In</th><th>Method</th><th>Scanned By</th></tr></thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="font-weight:600;">{{ $att->user->full_name }}</td>
                    <td style="font-size:0.82rem;">{{ Str::limit($att->event->title,25) }}</td>
                    <td style="font-size:0.82rem;">{{ $att->time_in->format('M j, Y g:i A') }}</td>
                    <td><span class="badge bg-primary-soft">{{ ucfirst(str_replace('_',' ',$att->scan_method)) }}</span></td>
                    <td style="font-size:0.82rem;">{{ $att->scannedBy?->full_name ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No attendance records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0">{{ $attendances->links() }}</div>
</div>
@endsection
