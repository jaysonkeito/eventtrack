// ============================================================
//  EventTrack — QR Scanner JS
//  Supports: Camera scan + Manual code input fallback
// ============================================================

let videoStream = null;
let scanInterval = null;

// ── Start Camera ─────────────────────────────────────────────
function startScanner() {
    const eventId = document.getElementById('eventSelect').value;
    if (!eventId) {
        showResult('Please select an event first.', 'danger');
        return;
    }

    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(stream => {
            videoStream = stream;
            const video = document.getElementById('qr-video');
            video.srcObject = stream;
            document.getElementById('startBtn').style.display = 'none';
            document.getElementById('stopBtn').style.display = '';

            // Poll for QR using canvas
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            scanInterval = setInterval(() => {
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvas.width  = video.videoWidth;
                    canvas.height = video.videoHeight;
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // If jsQR is available
                    if (typeof jsQR !== 'undefined') {
                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        const code = jsQR(imageData.data, imageData.width, imageData.height);
                        if (code) {
                            processQrCode(code.data, eventId);
                        }
                    }
                }
            }, 500);
        })
        .catch(err => {
            showResult('Camera not available: ' + err.message + '. Please use manual input below.', 'warning');
        });
}

// ── Stop Camera ───────────────────────────────────────────────
function stopScanner() {
    if (videoStream) {
        videoStream.getTracks().forEach(t => t.stop());
        videoStream = null;
    }
    if (scanInterval) {
        clearInterval(scanInterval);
        scanInterval = null;
    }
    const video = document.getElementById('qr-video');
    if (video) video.srcObject = null;

    document.getElementById('startBtn').style.display = '';
    document.getElementById('stopBtn').style.display  = 'none';
}

// ── Manual Input Submit ───────────────────────────────────────
function submitManual() {
    const eventId = document.getElementById('eventSelect').value;
    const code    = document.getElementById('manualCode').value.trim().toUpperCase();

    if (!eventId) {
        showResult('Please select an event first.', 'danger');
        return;
    }
    if (!code) {
        showResult('Please enter a registration code.', 'danger');
        return;
    }

    processQrCode(code, eventId);
    document.getElementById('manualCode').value = '';
}

// ── Process QR / Code ─────────────────────────────────────────
function processQrCode(code, eventId) {
    const btn = document.getElementById('manualSubmitBtn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Checking...';
    }

    // Determine scan endpoint based on current URL
    let scanUrl = '/api/qr/scan';
    if (window.location.pathname.includes('/admin/')) {
        scanUrl = '/api/qr/scan';
    } else if (window.location.pathname.includes('/organizer/')) {
        scanUrl = '/api/qr/scan';
    }

    fetch(scanUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ qr_data: code, event_id: eventId }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showResult('✅ ' + data.message, 'success');
            addToLog(data.attendee);
        } else if (data.already_scanned) {
            showResult('⚠️ ' + data.message, 'warning');
        } else {
            showResult('❌ ' + data.message, 'danger');
        }
    })
    .catch(err => {
        showResult('❌ Network error: ' + err.message, 'danger');
    })
    .finally(() => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Mark Attendance';
        }
    });
}

// ── Show Result Banner ────────────────────────────────────────
function showResult(message, type) {
    const el = document.getElementById('qr-result');
    if (!el) return;

    const colors = {
        success: { bg: '#F0FDF4', color: '#16A34A', border: '#86EFAC' },
        danger:  { bg: '#FEF2F2', color: '#DC2626', border: '#FCA5A5' },
        warning: { bg: '#FFFBEB', color: '#B45309', border: '#FCD34D' },
    };
    const c = colors[type] || colors.danger;
    el.style.display     = 'block';
    el.style.background  = c.bg;
    el.style.color       = c.color;
    el.style.border      = '1.5px solid ' + c.border;
    el.style.borderRadius = '10px';
    el.style.padding     = '12px 16px';
    el.style.fontWeight  = '600';
    el.style.marginTop   = '12px';
    el.textContent       = message;

    // Auto-hide after 4 seconds
    setTimeout(() => { el.style.display = 'none'; }, 4000);
}

// ── Add to Scan Log ───────────────────────────────────────────
function addToLog(attendee) {
    const tbody = document.getElementById('scanLogBody');
    if (!tbody) return;

    // Remove "no scans yet" row
    const emptyRow = tbody.querySelector('td[colspan]');
    if (emptyRow) emptyRow.parentElement.remove();

    const row = document.createElement('tr');
    row.innerHTML = `
        <td style="font-weight:600;">${attendee.name}</td>
        <td><code style="font-size:0.75rem;">${attendee.code}</code></td>
        <td style="font-size:0.82rem;">${attendee.time_in}</td>
    `;
    row.style.animation = 'fadeInRow 0.3s ease';
    tbody.insertBefore(row, tbody.firstChild);
}

// ── Enter key triggers manual submit ─────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('manualCode');
    if (input) {
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') submitManual();
        });
    }
});
