@extends('layouts.app')
@section('title','My Certificates')
@section('content')
<div class="page-header"><div><h1 class="page-title">My Certificates</h1></div></div>
<div class="row g-4">
    @forelse($certificates as $cert)
    <div class="col-md-6 col-lg-4"><div class="et-card text-center p-4">
        <i class="bi bi-award" style="font-size:3rem;color:#F59E0B;"></i>
        <h5 style="font-weight:700;margin-top:12px;">{{ $cert->event->title }}</h5>
        <p style="font-size:0.8rem;color:#64748b;">{{ $cert->certificate_code }}</p>
        <p style="font-size:0.8rem;color:#64748b;">Issued: {{ $cert->created_at->format('M j, Y') }}</p>
    </div></div>
    @empty
    <div class="col-12"><div class="et-card text-center py-5">
        <i class="bi bi-award" style="font-size:3rem;color:#cbd5e1;"></i>
        <p class="mt-3 text-muted">No certificates yet. Attend events to earn certificates!</p>
    </div></div>
    @endforelse
</div>
@endsection