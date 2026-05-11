@extends('layouts.app')
@section('title','My Registrations')
@section('content')
<div class="page-header"><div><h1 class="page-title">My Registrations</h1></div></div>
<div class="et-card"><div class="card-body p-0">
<table class="et-table">
    <thead><tr><th>Event</th><th>Date</th><th>Code</th><th>Status</th><th>QR</th></tr></thead>
    <tbody>
        @forelse($registrations as $reg)
        <tr>
            <td style="font-weight:600;">{{ Str::limit($reg->event->title,30) }}</td>
            <td style="font-size:0.82rem;">{{ $reg->event->start_datetime->format('M j, Y') }}</td>
            <td><code style="font-size:0.78rem;">{{ $reg->registration_code }}</code></td>
            <td>{!! $reg->status_badge !!}</td>
            <td>@if($reg->status==='approved')<a href="{{ route('attendee.qr.show', $reg) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-qr-code"></i></a>@else<span class="text-muted" style="font-size:0.8rem;">Pending</span>@endif</td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center py-4 text-muted">No registrations yet.</td></tr>
        @endforelse
    </tbody>
</table>
</div><div class="card-body pt-0">{{ $registrations->links() }}</div></div>
@endsection