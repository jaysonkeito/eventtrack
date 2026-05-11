<nav class="top-navbar">
    <button class="sidebar-toggle-btn d-lg-none" id="menuToggle">
        <i class="bi bi-list"></i>
    </button>
    <div class="ms-auto d-flex align-items-center gap-3">
        <div class="dropdown">
            <button class="btn btn-light btn-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <div style="width:28px;height:28px;border-radius:50%;background:var(--et-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;">
                    {{ auth()->user()->initials }}
                </div>
                <span style="font-size:0.85rem;font-weight:600;">{{ auth()->user()->first_name }}</span>
                <i class="bi bi-chevron-down" style="font-size:0.7rem;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">{{ auth()->user()->full_name }}</h6></li>
                <li><span class="dropdown-item-text text-muted" style="font-size:0.8rem;">{{ ucfirst(auth()->user()->role) }}</span></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
