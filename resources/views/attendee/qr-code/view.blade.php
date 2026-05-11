@extends('layouts.app')
@section('title','My QR Code')
@section('content')
<div class="page-header"><div><h1 class="page-title">My QR Code</h1></div></div>
<div class="row justify-content-center"><div class="col-md-6">
<div class="et-card text-center">
    <div class="card-header">{{ $registration->event->title }}</div>
    <div class="card-body">
        @if($registration->qr_code_path)
            <img src="{{ asset('storage/qrcodes/'.$registration->qr_code_path) }}" alt="QR Code" style="width:250px;border-radius:12px;">
            <p class="mt-3 text-muted">Present this QR code at the event entrance.</p>
            <code style="font-size:1rem;font-weight:700;">{{ $registration->registration_code }}</code>
        @else
            <div class="py-4"><i class="bi bi-hourglass-split" style="font-size:3rem;color:#cbd5e1;"></i>
            <p class="mt-3 text-muted">QR code is being generated. Check back later.</p></div>
        @endif
    </div>
</div>
</div></div>
@endsection