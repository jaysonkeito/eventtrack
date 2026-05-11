@extends('layouts.app')
@section('title', 'QR Attendance Scanner')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">QR Attendance Scanner</h1>
    <p class="page-subtitle">Scan QR codes or enter registration codes manually.</p></div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="et-card">
            <div class="card-header"><i class="bi bi-qr-code-scan me-2"></i>Scanner</div>
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Select Event *</label>
                    <select id="eventSelect" class="form-select">
                        <option value="">-- Select Event --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="qr-scanner-container mb-3">
                    <video id="qr-video" autoplay playsinline style="width:100%;max-width:400px;border-radius:10px;border:2px solid #F59E0B;"></video>
                    <div id="qr-result"></div>
                </div>

                <div class="d-flex gap-2 mb-4">
                    <button id="startBtn" class="btn-et-primary flex-fill" onclick="startScanner()">
                        <i class="bi bi-camera-video me-1"></i> Start Camera
                    </button>
                    <button id="stopBtn" class="btn btn-outline-secondary flex-fill" onclick="stopScanner()" style="display:none;">
                        <i class="bi bi-stop-circle me-1"></i> Stop Camera
                    </button>
                </div>

                <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                    <hr style="flex:1;margin:0;">
                    <span style="font-size:0.78rem;color:#94a3b8;font-weight:600;white-space:nowrap;">OR ENTER MANUALLY</span>
                    <hr style="flex:1;margin:0;">
                </div>

                <div style="background:#F8FAFC;border-radius:12px;padding:20px;border:1.5px solid #E2E8F0;">
                    <label class="form-label">Registration Code</label>
                    <div class="d-flex gap-2">
                        <input type="text"
                               id="manualCode"
                               class="form-control"
                               placeholder="e.g. EVT-2026-00001-AB12"
                               style="font-family:monospace;font-size:0.9rem;letter-spacing:0.5px;"
                               autocomplete="off">
                        <button id="manualSubmitBtn"
                                class="btn-et-primary px-4"
                                onclick="submitManual()"
                                style="white-space:nowrap;">
                            <i class="bi bi-check-circle me-1"></i> Mark Attendance
                        </button>
                    </div>
                    <p style="font-size:0.78rem;color:#94a3b8;margin-top:8px;margin-bottom:0;">
                        <i class="bi bi-info-circle me-1"></i>
                        Type the registration code and press Enter or click Mark Attendance.
                    </p>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="et-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-check me-2"></i>Scan Log</span>
                <span id="scanCount" class="badge bg-primary-soft">0 scanned</span>
            </div>
            <div class="card-body p-0">
                <table class="et-table">
                    <thead><tr><th>Name</th><th>Code</th><th>Time In</th></tr></thead>
                    <tbody id="scanLogBody">
                        <tr><td colspan="3" class="text-center py-4 text-muted">No scans yet.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="et-card mt-4">
            <div class="card-header"><i class="bi bi-bar-chart me-2"></i>Session Stats</div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div style="font-size:1.8rem;font-weight:800;color:#1A56A0;" id="statScanned">0</div>
                        <div style="font-size:0.75rem;color:#64748b;">Scanned</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:1.8rem;font-weight:800;color:#16A34A;" id="statSuccess">0</div>
                        <div style="font-size:0.75rem;color:#64748b;">Successful</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:1.8rem;font-weight:800;color:#DC2626;" id="statFailed">0</div>
                        <div style="font-size:0.75rem;color:#64748b;">Failed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/qr-scanner.js') }}"></script>
@endpush
@endsection
