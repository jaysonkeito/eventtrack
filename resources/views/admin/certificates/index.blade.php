@extends('layouts.app')
@section('title','Certificates')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Certificates</h1><p class="page-subtitle">Auto-generated certificates for attended events.</p></div>
</div>
<div class="et-card">
    <div class="card-body p-0">
        <table class="et-table">
            <thead><tr><th>#</th><th>Attendee</th><th>Event</th><th>Certificate Code</th><th>Issued</th><th>Download</th></tr></thead>
            <tbody>
                @forelse($certificates ?? [] as $cert)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="font-weight:600;">{{ $cert->user->full_name }}</td>
                    <td style="font-size:0.82rem;">{{ Str::limit($cert->event->title,25) }}</td>
                    <td><code style="font-size:0.75rem;">{{ $cert->certificate_code }}</code></td>
                    <td style="font-size:0.8rem;">{{ $cert->created_at->format('M j, Y') }}</td>
                    <td>
                        @if($cert->file_path)
                            <a href="{{ $cert->download_url }}" class="btn btn-sm btn-outline-success" target="_blank"><i class="bi bi-download"></i></a>
                        @else
                            <span class="text-muted" style="font-size:0.8rem;">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No certificates generated yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
