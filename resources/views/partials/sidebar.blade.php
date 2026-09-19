<style>
    :root {
        --sidebar-bg: #18181B;
        --sidebar-border: #2A2A2E;
        --sidebar-text: #9A9AA0;
        --sidebar-text-active: #F2F2F3;
        --sidebar-hover-bg: rgba(255, 255, 255, 0.05);
        --accent: #1a5fb4;
        --danger: #c01c28;
    }

    .sidebar {
        width: 250px;
        height: 100vh;
        background: var(--sidebar-bg);
        color: var(--sidebar-text);
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1030;
        transition: transform 0.2s ease;
        display: flex;
        flex-direction: column;
        border-right: 1px solid var(--sidebar-border);
    }

    @media (max-width: 991.98px) {
        .sidebar {
            transform: translateX(-250px);
        }

        .sidebar.open {
            transform: translateX(0);
        }
    }

    .sidebar-header {
        padding: 20px 16px;
        border-bottom: 1px solid var(--sidebar-border);
        flex-shrink: 0;
    }

    .logo-container {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo-img {
        width: 36px;
        height: 36px;
        object-fit: contain;
    }

    .logo-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .logo-main {
        font-size: 1rem;
        font-weight: 600;
        color: var(--sidebar-text-active);
    }

    .logo-sub {
        font-size: 0.75rem;
        color: var(--sidebar-text);
        margin-top: 2px;
    }

    .sidebar-content {
        flex: 1;
        overflow-y: auto;
        padding: 12px 10px;
    }

    .nav {
        padding: 0;
    }

    .nav-link {
        color: var(--sidebar-text);
        padding: 8px 12px;
        border-radius: 6px;
        margin-bottom: 2px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: background-color 0.15s ease, color 0.15s ease;
        display: flex;
        align-items: center;
        outline: none !important;
        box-shadow: none !important;
    }

    .nav-link i {
        font-size: 1rem;
        margin-right: 10px;
        min-width: 20px;
        text-align: center;
    }

    .nav-link:hover {
        background-color: var(--sidebar-hover-bg);
        color: var(--sidebar-text-active);
    }

    .nav-link.active {
        color: var(--sidebar-text-active);
        background-color: var(--sidebar-hover-bg);
        font-weight: 600;
        box-shadow: inset 2px 0 0 var(--accent);
    }

    .nav-divider {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--sidebar-text);
        padding: 16px 12px 6px;
        opacity: 0.8;
    }

    .news-count-badge {
        background: rgba(255, 255, 255, 0.08);
        color: var(--sidebar-text);
        font-size: 0.75rem;
        font-weight: 500;
        padding: 1px 8px;
        border-radius: 6px;
        margin-left: auto;
    }

    .nav-link.utility-link {
        font-size: 0.8125rem;
        padding: 6px 12px;
        color: var(--sidebar-text);
    }

    .user-profile-section {
        padding: 12px 15px;
        border-top: 1px solid var(--sidebar-border);
        flex-shrink: 0;
    }

    .user-info {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: var(--accent);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .user-details {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-weight: 500;
        color: var(--sidebar-text-active);
        font-size: 0.875rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        font-size: 0.75rem;
        color: var(--sidebar-text);
        margin-top: 1px;
    }

    .btn-logout {
        width: 100%;
        text-align: left;
        color: var(--sidebar-text);
        background: transparent;
        border: 1px solid var(--sidebar-border);
        padding: 8px 12px;
        border-radius: 6px;
        transition: background-color 0.15s ease, color 0.15s ease;
        font-weight: 500;
        font-size: 0.8125rem;
        outline: none !important;
        box-shadow: none !important;
        display: flex;
        align-items: center;
    }

    .btn-logout:hover {
        background: rgba(192, 28, 40, 0.12);
        color: #e5484d;
        border-color: rgba(192, 28, 40, 0.3);
    }

    .btn-logout i {
        margin-right: 8px;
    }

    .sidebar-toggle {
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1031;
        background: var(--sidebar-bg);
        color: var(--sidebar-text-active);
        border: 1px solid var(--sidebar-border);
        padding: 8px 10px;
        border-radius: 6px;
        font-size: 1rem;
        display: none;
    }

    @media (max-width: 991.98px) {
        .sidebar-toggle {
            display: block;
        }
    }

    .sidebar-content::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-content::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-content::-webkit-scrollbar-thumb {
        background: var(--sidebar-border);
        border-radius: 2px;
    }

    @media (prefers-reduced-motion: reduce) {

        *,
        *::before,
        *::after {
            transition: none !important;
            animation: none !important;
        }
    }
</style>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <img src="{{ asset('images/logo-ofs.png') }}" alt="OFS Logo" class="logo-img">
            <div class="logo-text">
                <div class="logo-main">OFS Futsal</div>
                <div class="logo-sub">Center</div>
            </div>
        </div>
    </div>

    <div class="sidebar-content">
        <nav class="nav flex-column">
            {{-- DASHBOARD --}}
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nav-divider">Content management</div>

            {{-- NEWS MANAGEMENT --}}
            <a class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}"
                href="{{ route('admin.news.index') }}">
                <i class="bi bi-newspaper"></i> News
                @php
                $totalNews = \App\Models\NewsArticle::count();
                $unpublishedNews = \App\Models\NewsArticle::where('is_active', false)->count();
                @endphp
                <span class="news-count-badge">
                    {{ $totalNews }}@if($unpublishedNews > 0)
                    <span style="opacity: 0.7;">({{ $unpublishedNews }} draft)</span>
                    @endif
                </span>
            </a>

            <div class="nav-divider">Tournament</div>

            {{-- TOURNAMENTS --}}
            <a class="nav-link {{ request()->routeIs('admin.tournaments.*') ? 'active' : '' }}"
                href="{{ route('admin.tournaments.index') }}">
                <i class="bi bi-trophy"></i> Tournaments
            </a>

            {{-- MATCHES --}}
            <a class="nav-link {{ request()->routeIs('admin.matches.*') ? 'active' : '' }}"
                href="{{ route('admin.matches.index') }}">
                <i class="bi bi-calendar2-event"></i> Matches
            </a>

            {{-- STANDINGS --}}
            <a class="nav-link {{ request()->routeIs('admin.standings.*') ? 'active' : '' }}"
                href="{{ route('admin.standings.index') }}">
                <i class="bi bi-bar-chart-line"></i> Standings
            </a>

            <div class="nav-divider">Team & players</div>

            {{-- TEAMS --}}
            <a class="nav-link {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}"
                href="{{ route('admin.teams.index') }}">
                <i class="bi bi-people"></i> Teams
            </a>

            {{-- PLAYERS --}}
            <a class="nav-link {{ request()->routeIs('admin.players.*') ? 'active' : '' }}"
                href="{{ route('admin.players.index') }}">
                <i class="bi bi-person-badge"></i> Players
            </a>

            <div class="nav-divider">Configuration</div>

            {{-- HIGHLIGHTS --}}
            <a class="nav-link {{ request()->routeIs('highlights.*') ? 'active' : '' }}"
                href="{{ route('highlights.index') }}">
                <i class="bi bi-play-circle"></i> Highlights
            </a>

            {{-- HERO SETTINGS --}}
            <a class="nav-link {{ request()->routeIs('admin.hero-settings.*') ? 'active' : '' }}"
                href="{{ route('admin.hero-settings.index') }}">
                <i class="bi bi-image"></i> Hero settings
            </a>
        </nav>

        {{-- Quick actions --}}
        <nav class="nav flex-column mt-2">
            <div class="nav-divider">Quick actions</div>

            <a class="nav-link utility-link" href="{{ route('admin.news.create') }}">
                <i class="bi bi-plus-circle me-2"></i> Buat artikel baru
            </a>

            <a class="nav-link utility-link" href="{{ route('news.index') }}" target="_blank">
                <i class="bi bi-eye me-2"></i> Lihat berita publik
            </a>

            <a class="nav-link utility-link" href="/">
                <i class="bi bi-house-door me-2"></i> Kembali ke situs
            </a>
        </nav>
    </div>

    <div class="user-profile-section">
        <div class="user-info">
            <div class="user-avatar">
                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="user-details">
                <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-logout">
                <i class="bi bi-box-arrow-right"></i> Keluar
            </button>
        </form>
    </div>
</div>

<button class="sidebar-toggle d-lg-none" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
</button>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('sidebarToggle');

        if (toggleButton && sidebar) {
            toggleButton.addEventListener('click', function (e) {
                e.stopPropagation();
                sidebar.classList.toggle('open');
            });

            // Tutup sidebar saat klik di luar (mobile)
            document.addEventListener('click', function (event) {
                if (window.innerWidth < 992 && sidebar.classList.contains('open')) {
                    const inside = sidebar.contains(event.target);
                    const onToggle = toggleButton.contains(event.target);
                    if (!inside && !onToggle) {
                        sidebar.classList.remove('open');
                    }
                }
            });

            // Tutup sidebar di mobile setelah navigasi
            sidebar.querySelectorAll('.nav-link').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth < 992) {
                        sidebar.classList.remove('open');
                    }
                });
            });
        }
    });
</script>