// ============================================================
//  EventTrack — Global App JavaScript
// ============================================================

document.addEventListener('DOMContentLoaded', () => {

    // ── Sidebar Toggle (mobile) ─────────────────────────────
    const sidebar       = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const menuToggle    = document.getElementById('menuToggle');

    menuToggle?.addEventListener('click', () => sidebar?.classList.add('show'));
    sidebarToggle?.addEventListener('click', () => sidebar?.classList.remove('show'));

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        if (sidebar?.classList.contains('show') &&
            !sidebar.contains(e.target) &&
            e.target !== menuToggle) {
            sidebar.classList.remove('show');
        }
    });

    // ── Auto-dismiss flash alerts ───────────────────────────
    document.querySelectorAll('.toast-alert').forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert?.close();
        }, 4500);
    });

    // ── Confirm delete dialogs ──────────────────────────────
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', (e) => {
            const msg = el.dataset.confirm || 'Are you sure you want to delete this?';
            if (!confirm(msg)) e.preventDefault();
        });
    });

    // ── Tooltips ────────────────────────────────────────────
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });

});

// ── AJAX Helper ──────────────────────────────────────────────
async function fetchJSON(url, options = {}) {
    try {
        const res = await fetch(url, {
            headers: { 'Content-Type': 'application/json', ...options.headers },
            ...options
        });
        return await res.json();
    } catch (err) {
        console.error('fetchJSON error:', err);
        return { success: false, message: 'Network error' };
    }
}

// ── Show/hide loading spinner ─────────────────────────────────
function showSpinner(btnEl) {
    btnEl.disabled = true;
    btnEl.dataset.originalText = btnEl.innerHTML;
    btnEl.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Please wait...';
}

function hideSpinner(btnEl) {
    btnEl.disabled = false;
    btnEl.innerHTML = btnEl.dataset.originalText || 'Submit';
}
