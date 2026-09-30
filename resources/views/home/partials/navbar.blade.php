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

        {{-- Desktop menu (primary links only — secondary grouped under "More") --}}
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

            {{-- "More" dropdown: secondary public links + admin shortcuts --}}
            <li class="nav-dropdown">
                <button type="button" class="nav-menu-link nav-dropdown-toggle" id="navMoreToggle"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="bi bi-grid"></i><span>More</span>
                    <i class="bi bi-chevron-down nav-dropdown-caret"></i>
                </button>
                <div class="nav-dropdown-menu" id="navMoreMenu" role="menu">
                    <a class="nav-dropdown-item {{ request()->is('highlights*') ? 'active' : '' }}" href="{{ route('highlights.index') }}" role="menuitem">
                        <i class="bi bi-play-circle"></i><span>Highlights</span>
                    </a>
                    <a class="nav-dropdown-item {{ request()->is('all-teams*') ? 'active' : '' }}" href="{{ route('all-teams') }}" role="menuitem">
                        <i class="bi bi-people"></i><span>Teams</span>
                    </a>
                    <a class="nav-dropdown-item {{ request()->is('market-value*') ? 'active' : '' }}" href="{{ route('market-value.showcase') }}" role="menuitem">
                        <i class="bi bi-graph-up-arrow"></i><span>Market value</span>
                    </a>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <div class="nav-dropdown-divider"></div>
                            <a class="nav-dropdown-item {{ request()->is('tournaments*') ? 'active' : '' }}" href="{{ route('tournaments.index') }}" role="menuitem">
                                <i class="bi bi-trophy"></i><span>Tournaments</span>
                            </a>
                            <a class="nav-dropdown-item {{ request()->is('teams*') ? 'active' : '' }}" href="{{ route('teams.index') }}" role="menuitem">
                                <i class="bi bi-gear"></i><span>Manage teams</span>
                            </a>
                        @endif
                    @endauth
                </div>
            </li>

            @auth
                @if(auth()->user()->role === 'admin')
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
        <a class="msb-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo-ofs.png') }}" alt="OFS Logo" class="msb-brand-logo">
            <span class="msb-brand-text">
                <span class="msb-brand-title">OFS Futsal</span>
                <span class="msb-brand-sub">Center</span>
            </span>
        </a>
        <button class="msb-close" id="sidebarClose" aria-label="Close menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- Sidebar nav links --}}
    <nav class="msb-nav">
        <span class="msb-section-label">Menu</span>

        <a class="msb-link {{ request()->is('/') ? 'is-active' : '' }}" href="{{ url('/') }}">
            <i class="bi bi-house-door"></i><span>Home</span>
        </a>
        <a class="msb-link {{ request()->is('news*') ? 'is-active' : '' }}" href="{{ route('news.index') }}">
            <i class="bi bi-newspaper"></i><span>News</span>
        </a>
        <a class="msb-link {{ request()->is('schedule*') || request()->is('matches*') ? 'is-active' : '' }}" href="{{ route('schedule') }}">
            <i class="bi bi-calendar2-week"></i><span>Schedule</span>
        </a>
        <a class="msb-link {{ request()->is('standings*') ? 'is-active' : '' }}" href="{{ route('standings') }}">
            <i class="bi bi-bar-chart-line"></i><span>Standings</span>
        </a>
        <a class="msb-link {{ request()->is('highlights*') ? 'is-active' : '' }}" href="{{ route('highlights.index') }}">
            <i class="bi bi-play-circle"></i><span>Highlights</span>
        </a>
        <a class="msb-link {{ request()->is('all-teams*') ? 'is-active' : '' }}" href="{{ route('all-teams') }}">
            <i class="bi bi-people"></i><span>Teams</span>
        </a>
        <a class="msb-link {{ request()->is('market-value*') ? 'is-active' : '' }}" href="{{ route('market-value.showcase') }}">
            <i class="bi bi-graph-up-arrow"></i><span>Market value</span>
        </a>

        @auth
            @if(auth()->user()->role === 'admin')
                <span class="msb-section-label">Admin</span>
                <a class="msb-link {{ request()->is('tournaments*') ? 'is-active' : '' }}" href="{{ route('tournaments.index') }}">
                    <i class="bi bi-trophy"></i><span>Tournaments</span>
                </a>
                <a class="msb-link {{ request()->is('teams*') ? 'is-active' : '' }}" href="{{ route('teams.index') }}">
                    <i class="bi bi-gear"></i><span>Manage teams</span>
                </a>
            @endif
        @endauth
    </nav>

    {{-- Bottom action buttons --}}
    <div class="msb-footer">
        @auth
            @if(auth()->user()->role === 'admin')
                <a class="msb-btn-admin" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i><span>Admin panel</span>
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="msb-btn-logout">
                    <i class="bi bi-box-arrow-right"></i><span>Logout</span>
                </button>
            </form>
        @else
            <a class="msb-btn-login" href="{{ route('login') }}">
                <i class="bi bi-box-arrow-in-right"></i><span>Login</span>
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
    width: 276px;
    max-width: 84vw;
    background: var(--v3-surface);
    border-right: 1px solid var(--v3-border);
    z-index: 1050;
    display: flex;
    flex-direction: column;
    transform: translateX(-100%);
    transition: transform 300ms cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--v3-shadow-drawer);
}
.mobile-sidebar.is-open {
    transform: translateX(0);
}

/* Sidebar header */
.msb-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 18px 16px;
    border-bottom: 1px solid var(--v3-border);
}

.msb-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.msb-brand-logo {
    width: 30px;
    height: 30px;
    object-fit: contain;
}

.msb-brand-text {
    display: flex;
    flex-direction: column;
    line-height: 1.1;
}

.msb-brand-title {
    font-size: 13px;
    font-weight: 800;
    font-style: italic;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--v3-text-main);
}

.msb-brand-sub {
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--v3-neon-green);
}

.msb-close {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: none;
    border: 1px solid var(--v3-border);
    border-radius: var(--v3-radius-sm);
    color: var(--v3-text-sub);
    font-size: 0.8rem;
    line-height: 1;
    cursor: pointer;
    transition: color 150ms ease, border-color 150ms ease, background 150ms ease;
}
.msb-close:hover {
    color: var(--v3-neon-green);
    border-color: var(--v3-border-glow);
    background: rgba(0, 255, 135, 0.06);
}

/* Sidebar nav links */
.msb-nav {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.msb-section-label {
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--v3-text-muted);
    padding: 14px 12px 6px;
}
.msb-section-label:first-child {
    padding-top: 2px;
}

.msb-link {
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: var(--v3-radius-sm);
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--v3-text-sub);
    text-decoration: none;
    transition: color 150ms ease, background 150ms ease;
}

.msb-link i {
    font-size: 0.95rem;
    width: 18px;
    text-align: center;
    color: var(--v3-text-muted);
    transition: color 150ms ease;
}

.msb-link:hover {
    color: var(--v3-text-main);
    background: var(--v3-card);
}
.msb-link:hover i {
    color: var(--v3-text-sub);
}

.msb-link.is-active {
    color: var(--v3-neon-green);
    background: rgba(0, 255, 135, 0.07);
    font-weight: 600;
}
.msb-link.is-active i {
    color: var(--v3-neon-green);
}

.msb-link.is-active::before {
    content: "";
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 18px;
    border-radius: 0 3px 3px 0;
    background: var(--v3-neon-green);
}

/* Sidebar footer */
.msb-footer {
    padding: 14px 12px 18px;
    border-top: 1px solid var(--v3-border);
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.msb-btn-admin,
.msb-btn-login {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 40px;
    border-radius: var(--v3-radius-sm);
    font-size: 0.8125rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    text-decoration: none;
    transition: filter 150ms ease, transform 150ms ease, background 150ms ease, color 150ms ease;
}

/* Login = primary solid neon */
.msb-btn-login {
    color: #000;
    background: var(--v3-neon-green);
    border: 1px solid var(--v3-neon-green);
}
.msb-btn-login:hover {
    filter: brightness(1.08);
    color: #000;
    transform: translateY(-1px);
}

/* Admin = outlined neon (secondary emphasis) */
.msb-btn-admin {
    color: var(--v3-neon-green);
    background: rgba(0, 255, 135, 0.06);
    border: 1px solid rgba(0, 255, 135, 0.28);
}
.msb-btn-admin:hover {
    background: rgba(0, 255, 135, 0.14);
    color: var(--v3-neon-green);
}

.msb-btn-logout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    height: 40px;
    border-radius: var(--v3-radius-sm);
    font-size: 0.8125rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    color: var(--v3-text-sub);
    border: 1px solid var(--v3-border);
    background: transparent;
    cursor: pointer;
    transition: color 150ms ease, border-color 150ms ease, background 150ms ease;
}
.msb-btn-logout:hover {
    color: var(--v3-neon-pink);
    border-color: rgba(255, 0, 85, 0.4);
    background: rgba(255, 0, 85, 0.06);
}

/* ── Desktop "More" dropdown ── */
.nav-dropdown {
    position: relative;
}

.nav-dropdown-toggle {
    background: none;
    border: none;
    cursor: pointer;
    font-family: inherit;
}

.nav-dropdown-caret {
    font-size: 10px !important;
    transition: transform 180ms ease;
    opacity: 0.7;
}

.nav-dropdown.is-open .nav-dropdown-caret {
    transform: rotate(180deg);
}

.nav-dropdown-menu {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    min-width: 210px;
    background: #090e18;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    padding: 6px;
    box-shadow: var(--v3-shadow-pop);
    display: flex;
    flex-direction: column;
    gap: 2px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-6px);
    transition: opacity 160ms ease, transform 160ms ease, visibility 160ms;
    z-index: 60;
}

.nav-dropdown.is-open .nav-dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.nav-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    border-radius: 7px;
    font-size: 13px;
    font-weight: 500;
    color: #94a3b8;
    text-decoration: none;
    transition: color 150ms ease, background 150ms ease;
}

.nav-dropdown-item i {
    font-size: 15px;
    width: 18px;
    text-align: center;
}

.nav-dropdown-item:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.06);
}

.nav-dropdown-item.active {
    color: #00ff87;
    background: rgba(0, 255, 135, 0.08);
    font-weight: 600;
}

.nav-dropdown-divider {
    height: 1px;
    background: rgba(255, 255, 255, 0.08);
    margin: 4px 6px;
}
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

    // ── Desktop "More" dropdown toggle ──
    const moreToggle = document.getElementById('navMoreToggle');
    const moreMenu   = document.getElementById('navMoreMenu');

    if (moreToggle && moreMenu) {
        const dropdown = moreToggle.closest('.nav-dropdown');

        function setDropdown(open) {
            dropdown.classList.toggle('is-open', open);
            moreToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }

        moreToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            setDropdown(!dropdown.classList.contains('is-open'));
        });

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            if (dropdown.classList.contains('is-open') && !dropdown.contains(e.target)) {
                setDropdown(false);
            }
        });

        // Close on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && dropdown.classList.contains('is-open')) {
                setDropdown(false);
            }
        });
    }
})();
</script>
