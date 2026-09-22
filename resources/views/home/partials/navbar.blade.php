{{-- ============================================================
     NAVBAR + MOBILE SIDEBAR DRAWER
     ============================================================ --}}
<nav class="navbar app-navbar">
    <div class="app-container d-flex align-items-center justify-content-between">
        <a class="nav-brand-wrap" href="{{ url('/') }}">
            <img src="{{ asset('images/logo-ofs.png') }}" alt="OFS Logo" class="nav-brand-logo">
            <div>
                <span class="nav-brand-title">OFS FUTSAL</span>
                <span class="nav-brand-sub">Center</span>
            </div>
        </a>

        {{-- Desktop menu --}}
        <ul class="nav-menu-list nav-menu-desktop ms-auto align-items-center">
            <li>
                <a class="nav-menu-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                    <i class="bi bi-house-door"></i><span>Home</span>
                </a>
            </li>
            <li>
                <a class="nav-menu-link {{ request()->is('news*') ? 'active' : '' }}" href="{{ route('news.index') }}">
                    <i class="bi bi-newspaper"></i><span>News</span>
                </a>
            </li>
            @auth
                @if(auth()->user()->role === 'admin')
                    <li>
                        <a class="nav-menu-link {{ request()->is('tournaments*') ? 'active' : '' }}" href="{{ route('tournaments.index') }}">
                            <i class="bi bi-trophy"></i><span>Tournaments</span>
                        </a>
                    </li>
                @endif
            @endauth
            <li>
                <a class="nav-menu-link {{ request()->is('schedule*') || request()->is('matches*') ? 'active' : '' }}" href="{{ route('schedule') }}">
                    <i class="bi bi-calendar2-week"></i><span>Schedule</span>
                </a>
            </li>
            <li>
                <a class="nav-menu-link {{ request()->is('standings*') ? 'active' : '' }}" href="{{ route('standings') }}">
                    <i class="bi bi-bar-chart-line"></i><span>Standings</span>
                </a>
            </li>
            <li>
                <a class="nav-menu-link {{ request()->is('highlights*') ? 'active' : '' }}" href="{{ route('highlights.index') }}">
                    <i class="bi bi-play-circle"></i><span>Highlights</span>
                </a>
            </li>
            @auth
                @if(auth()->user()->role === 'admin')
                    <li>
                        <a class="nav-menu-link {{ request()->is('teams*') ? 'active' : '' }}" href="{{ route('teams.index') }}">
                            <i class="bi bi-people"></i><span>Teams</span>
                        </a>
                    </li>
                    <li class="ms-lg-2">
                        <a class="nav-btn-admin" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i><span>Admin panel</span>
                        </a>
                    </li>
                @endif
                <li class="ms-lg-2">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-btn-logout">
                            <i class="bi bi-box-arrow-right"></i><span>Logout</span>
                        </button>
                    </form>
                </li>
            @else
                <li class="ms-lg-2">
                    <a class="nav-btn-login" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i><span>Login</span>
                    </a>
                </li>
            @endauth
        </ul>

        {{-- Hamburger (mobile only) --}}
        <button class="navbar-toggler" id="sidebarToggle" type="button" aria-label="Open menu" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

{{-- ── SIDEBAR DRAWER (mobile) ─────────────────────────────────── --}}
<div class="mobile-sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

<aside class="mobile-sidebar" id="mobileSidebar" role="dialog" aria-modal="true" aria-label="Navigation menu">
    {{-- Sidebar header --}}
    <div class="msb-header">
        <a class="nav-brand-wrap" href="{{ url('/') }}">
            <img src="{{ asset('images/logo-ofs.png') }}" alt="OFS Logo" class="nav-brand-logo">
            <div>
                <span class="nav-brand-title">OFS FUTSAL</span>
                <span class="nav-brand-sub">Center</span>
            </div>
        </a>
        <button class="msb-close" id="sidebarClose" aria-label="Close menu">&times;</button>
    </div>

    {{-- Sidebar nav links --}}
    <nav class="msb-nav">
        <a class="msb-link {{ request()->is('/') ? 'is-active' : '' }}" href="{{ url('/') }}">
            <i class="bi bi-house-door"></i> Home
        </a>
        <a class="msb-link {{ request()->is('news*') ? 'is-active' : '' }}" href="{{ route('news.index') }}">
            <i class="bi bi-newspaper"></i> News
        </a>
        <a class="msb-link {{ request()->is('schedule*') || request()->is('matches*') ? 'is-active' : '' }}" href="{{ route('schedule') }}">
            <i class="bi bi-calendar2-week"></i> Schedule
        </a>
        <a class="msb-link {{ request()->is('standings*') ? 'is-active' : '' }}" href="{{ route('standings') }}">
            <i class="bi bi-bar-chart-line"></i> Standings
        </a>
        <a class="msb-link {{ request()->is('highlights*') ? 'is-active' : '' }}" href="{{ route('highlights.index') }}">
            <i class="bi bi-play-circle"></i> Highlights
        </a>
        @auth
            @if(auth()->user()->role === 'admin')
                <a class="msb-link {{ request()->is('tournaments*') ? 'is-active' : '' }}" href="{{ route('tournaments.index') }}">
                    <i class="bi bi-trophy"></i> Tournaments
                </a>
                <a class="msb-link {{ request()->is('teams*') ? 'is-active' : '' }}" href="{{ route('teams.index') }}">
                    <i class="bi bi-people"></i> Teams
                </a>
            @endif
        @endauth
    </nav>

    {{-- Bottom action buttons --}}
    <div class="msb-footer">
        @auth
            @if(auth()->user()->role === 'admin')
                <a class="msb-btn-admin" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Admin Panel
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="msb-btn-logout">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        @else
            <a class="msb-btn-login" href="{{ route('login') }}">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
        @endauth
    </div>
</aside>

<style>
/* ── Desktop: show desktop menu, hide hamburger ── */
@media (min-width: 992px) {
    .nav-menu-desktop { display: flex !important; gap: 4px; }
    #sidebarToggle    { display: none !important; }
}

/* ── Mobile: hide desktop menu, show hamburger ── */
@media (max-width: 991.98px) {
    .nav-menu-desktop { display: none !important; }
    #sidebarToggle    { display: inline-flex !important; }
}

/* ── Overlay ── */
.mobile-sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 1040;
    opacity: 0;
    transition: opacity 280ms ease;
}
.mobile-sidebar-overlay.is-open {
    display: block;
    opacity: 1;
}

/* ── Sidebar drawer ── */
.mobile-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: 280px;
    max-width: 85vw;
    background: #090e18;
    border-right: 1px solid rgba(255, 255, 255, 0.08);
    z-index: 1050;
    display: flex;
    flex-direction: column;
    transform: translateX(-100%);
    transition: transform 300ms cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 4px 0 30px rgba(0, 0, 0, 0.5);
}
.mobile-sidebar.is-open {
    transform: translateX(0);
}

/* Sidebar header */
.msb-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 18px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.07);
}
.msb-close {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 1.6rem;
    line-height: 1;
    cursor: pointer;
    padding: 0 4px;
    transition: color 150ms ease;
}
.msb-close:hover { color: #fff; }

/* Sidebar nav links */
.msb-nav {
    flex: 1;
    overflow-y: auto;
    padding: 12px 10px;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.msb-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    color: #94a3b8;
    text-decoration: none;
    transition: all 150ms ease;
}
.msb-link i { font-size: 1rem; width: 20px; text-align: center; }
.msb-link:hover { color: #fff; background: rgba(255, 255, 255, 0.06); }
.msb-link.is-active { color: #00ff87; background: rgba(0, 255, 135, 0.08); font-weight: 600; }

/* Sidebar footer */
.msb-footer {
    padding: 12px 10px 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.07);
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.msb-btn-admin,
.msb-btn-login {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #00ff87;
    border: 1px solid rgba(0, 255, 135, 0.25);
    background: rgba(0, 255, 135, 0.07);
    text-decoration: none;
    transition: all 150ms ease;
}
.msb-btn-admin:hover,
.msb-btn-login:hover { background: rgba(0, 255, 135, 0.15); color: #00ff87; }

.msb-btn-logout {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #f87171;
    border: 1px solid rgba(248, 113, 113, 0.2);
    background: rgba(248, 113, 113, 0.05);
    cursor: pointer;
    transition: all 150ms ease;
}
.msb-btn-logout:hover { background: rgba(248, 113, 113, 0.12); }
</style>

<script>
(function () {
    const toggle   = document.getElementById('sidebarToggle');
    const sidebar  = document.getElementById('mobileSidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const closeBtn = document.getElementById('sidebarClose');

    function openSidebar() {
        sidebar.classList.add('is-open');
        overlay.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('is-open');
        overlay.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    toggle.addEventListener('click', openSidebar);
    closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
            closeSidebar();
        }
    });
})();
</script>
