{{-- resources/views/attendee/events/browse.blade.php --}}
@extends('layouts.app')
@section('title','Browse Events')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Browse Events</h1><p class="page-subtitle">Find and register for upcoming events.</p></div>
</div>
<div class="row g-4">
    @forelse($events as $event)
    <div class="col-md-6 col-lg-4">
        <div class="et-card h-100">
            @if($event->banner_image)
                <img src="{{ asset('storage/'.$event->banner_image) }}" class="w-100" style="height:160px;object-fit:cover;border-radius:14px 14px 0 0;">
            @else
                <div style="height:160px;background:linear-gradient(135deg,#1A56A0,#2E75B6);border-radius:14px 14px 0 0;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-calendar-event" style="font-size:3rem;color:rgba(255,255,255,0.4);"></i>
                </div>
            @endif
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    {!! $event->status_badge !!}
                    @if($event->category)
                        <span class="badge" style="background:{{ $event->category->color_hex }}20;color:{{ $event->category->color_hex }};">{{ $event->category->name }}</span>
                    @endif
                </div>
                <h5 style="font-weight:700;color:#0f172a;margin-bottom:8px;">{{ $event->title }}</h5>
                <p style="font-size:0.82rem;color:#64748b;margin-bottom:12px;">{{ Str::limit($event->description,80) }}</p>
                <div style="font-size:0.8rem;color:#64748b;" class="mb-1"><i class="bi bi-calendar3 me-1"></i>{{ $event->start_datetime->format('M j, Y g:i A') }}</div>
                @if($event->venue)<div style="font-size:0.8rem;color:#64748b;" class="mb-3"><i class="bi bi-geo-alt me-1"></i>{{ $event->venue->name }}</div>@endif
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-weight:700;color:{{ $event->is_free ? '#16A34A' : '#1A56A0' }};">
                        {{ $event->is_free ? 'FREE' : '₱'.number_format($event->fee_amount,2) }}
                    </span>
                    @if($event->status === 'published')
                        <form method="POST" action="{{ route('attendee.registrations.store', $event) }}">
                            @csrf
                            <button class="btn-et-primary btn-sm px-3" style="padding:7px 16px;font-size:0.82rem;">Register</button>
                        </form>
                    @else
                        <span class="text-muted" style="font-size:0.8rem;">Not available</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="et-card text-center py-5">
            <i class="bi bi-calendar-x" style="font-size:3rem;color:#cbd5e1;"></i>
            <p class="mt-3 text-muted">No published events available at the moment.</p>
        </div>
    </div>
    @endforelse
</div>
@endsection
