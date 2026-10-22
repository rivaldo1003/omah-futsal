<style>
    .sidebar {
        --sidebar-bg: #ffffff;
        --sidebar-border: #e5e5e5;
        --sidebar-ink: #111111;
        --sidebar-charcoal: #39393b;
        --sidebar-mute: #707072;
        --sidebar-soft: #f5f5f5;
        width: 250px;
        height: 100vh;
        height: 100dvh;
        background: var(--sidebar-bg);
        color: var(--sidebar-ink);
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1030;
        transition: transform 0.22s ease;
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
        padding: 18px 18px;
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
        font-weight: 700;
        color: var(--sidebar-ink);
        letter-spacing: -0.02em;
    }

    .logo-sub {
        font-size: 0.75rem;
        color: var(--sidebar-mute);
        margin-top: 2px;
    }

    .sidebar-content {
        flex: 1;
        overflow-y: auto;
        padding: 12px 12px 20px;
    }

    .nav {
        padding: 0;
    }

    .nav-link {
        color: var(--sidebar-charcoal);
        min-height: 40px;
        padding: 8px 12px;
        border-radius: 999px;
        margin-bottom: 2px;
        font-weight: 500;
        font-size: 0.875rem;
        line-height: 1.35;
        transition: background-color 0.15s ease, color 0.15s ease;
        display: flex;
        align-items: center;
    }

    .nav-link i {
        font-size: 1rem;
        margin-right: 10px;
        min-width: 20px;
        text-align: center;
    }

    .nav-link:hover {
        background-color: var(--sidebar-soft);
        color: var(--sidebar-ink);
    }

    .nav-link.active {
        color: #ffffff;
        background-color: var(--sidebar-ink);
        font-weight: 600;
    }

    .nav-divider {
        font-size: 0.6875rem;
        font-weight: 600;
        line-height: 1.4;
        letter-spacing: 0.045em;
        text-transform: uppercase;
        color: var(--sidebar-mute);
        padding: 18px 12px 7px;
    }

    .news-count-badge {
        background: var(--sidebar-soft);
        color: var(--sidebar-mute);
        font-size: 0.6875rem;
        font-weight: 500;
        line-height: 1.5;
        padding: 2px 9px;
        border-radius: 999px;
        margin-left: auto;
        white-space: nowrap;
    }

    .nav-link.active .news-count-badge {
        background: rgba(255, 255, 255, 0.16);
        color: #ffffff;
    }

    .booking-pending-badge {
        min-width: 22px;
        margin-left: auto;
        padding: 2px 7px;
        border-radius: 999px;
        background: #b42318;
        color: #ffffff;
        font-size: 0.7rem;
        font-weight: 700;
        line-height: 1.4;
        text-align: center;
        font-variant-numeric: tabular-nums;
    }

    .nav-link.utility-link {
        font-size: 0.8125rem;
        min-height: 36px;
        padding: 6px 12px;
        color: var(--sidebar-mute);
    }

    .user-profile-section {
        padding: 14px 14px 16px;
        border-top: 1px solid var(--sidebar-border);
        flex-shrink: 0;
        background: var(--sidebar-bg);
    }

    .user-info {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--sidebar-ink);
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
        font-weight: 600;
        color: var(--sidebar-ink);
        font-size: 0.875rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        font-size: 0.75rem;
        color: var(--sidebar-mute);
        margin-top: 1px;
    }

    .btn-logout {
        width: 100%;
        text-align: left;
        color: var(--sidebar-charcoal);
        background: #ffffff;
        border: 1px solid var(--sidebar-border);
        padding: 8px 14px;
        border-radius: 999px;
        transition: background-color 0.15s ease, color 0.15s ease;
        font-weight: 500;
        font-size: 0.8125rem;
        display: flex;
        align-items: center;
    }

    .btn-logout:hover {
        background: var(--sidebar-soft);
        color: var(--sidebar-ink);
        border-color: #cacacb;
    }

    .btn-logout i {
        margin-right: 8px;
    }

    .app-version {
        margin-top: 9px;
        color: var(--sidebar-mute);
        font-size: 0.6875rem;
        line-height: 1.4;
        text-align: right;
        font-variant-numeric: tabular-nums;
    }

    .sidebar-toggle {
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1031;
        background: #ffffff;
        color: #111111;
        border: 1px solid #e5e5e5;
        width: 42px;
        height: 42px;
        padding: 0;
        border-radius: 50%;
        font-size: 1rem;
        display: none;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    @media (max-width: 991.98px) {
        .sidebar-toggle {
            display: flex;
        }
    }

    .sidebar-content::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-content::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-content::-webkit-scrollbar-thumb {
        background: #cacacb;
        border-radius: 999px;
    }

    .sidebar .nav-link:focus-visible,
    .sidebar .btn-logout:focus-visible,
    .sidebar-toggle:focus-visible {
        outline: 2px solid #111111;
        outline-offset: 3px;
    }

    @media (prefers-reduced-motion: reduce) {
        .sidebar,
        .sidebar *,
        .sidebar-toggle,
        .sidebar-toggle * {
            scroll-behavior: auto !important;
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
        <nav class="nav flex-column" aria-label="Navigasi admin">
            {{-- DASHBOARD --}}
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nav-divider">Konten</div>

            {{-- NEWS MANAGEMENT --}}
            <a class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}"
                href="{{ route('admin.news.index') }}">
                <i class="bi bi-newspaper"></i> Berita
                @php
                    $totalNews = \App\Models\NewsArticle::count();
                    $unpublishedNews = \App\Models\NewsArticle::where('is_active', false)->count();
                @endphp
                <span class="news-count-badge">
                    {{ $totalNews }}@if($unpublishedNews > 0)
                        <span style="opacity: 0.7;">({{ $unpublishedNews }} draf)</span>
                    @endif
                </span>
            </a>

            <div class="nav-divider">Operasional</div>

            <a class="nav-link {{ request()->routeIs('admin.court-bookings.index', 'admin.court-bookings.manual.*', 'admin.court-bookings.settings', 'admin.court-bookings.member-schedules.*') ? 'active' : '' }}"
                href="{{ route('admin.court-bookings.index') }}">
                <i class="bi bi-calendar-check"></i> Booking lapangan
                @php
                    $waitingCourtBookings = \App\Models\CourtBooking::where(function ($query) {
                        $query->where('status', \App\Models\CourtBooking::STATUS_PENDING_REVIEW)
                            ->orWhere(function ($pending) {
                                $pending->where('status', \App\Models\CourtBooking::STATUS_AWAITING_PAYMENT)
                                    ->where(function ($hold) {
                                        $hold->whereNull('expires_at')->orWhere('expires_at', '>', now());
                                    });
                            });
                    })->count();
                @endphp
                @if($waitingCourtBookings > 0)
                    <span class="booking-pending-badge" title="Booking menunggu pembayaran atau verifikasi">{{ $waitingCourtBookings }}</span>
                @endif
            </a>

            <a class="nav-link {{ request()->routeIs('admin.court-bookings.reports.daily*') ? 'active' : '' }}"
                href="{{ route('admin.court-bookings.reports.daily') }}">
                <i class="bi bi-clipboard-data"></i> Laporan operasional
            </a>

            <a class="nav-link {{ request()->routeIs('admin.court-bookings.revenue') ? 'active' : '' }}"
                href="{{ route('admin.court-bookings.revenue') }}">
                <i class="bi bi-cash-stack"></i> Laporan pemasukan
            </a>

            <a class="nav-link {{ request()->routeIs('admin.court-rentals.settings', 'admin.court-rentals.items.*') ? 'active' : '' }}"
                href="{{ route('admin.court-rentals.settings') }}">
                <i class="bi bi-box-seam"></i> Katalog perlengkapan
            </a>

            <div class="nav-divider">Turnamen</div>

            {{-- TOURNAMENTS --}}
            <a class="nav-link {{ request()->routeIs('admin.tournaments.*') ? 'active' : '' }}"
                href="{{ route('admin.tournaments.index') }}">
                <i class="bi bi-trophy"></i> Turnamen
            </a>

            {{-- MATCHES --}}
            <a class="nav-link {{ request()->routeIs('admin.matches.*') ? 'active' : '' }}"
                href="{{ route('admin.matches.index') }}">
                <i class="bi bi-calendar2-event"></i> Pertandingan
            </a>

            {{-- STANDINGS --}}
            <a class="nav-link {{ request()->routeIs('admin.standings.*') ? 'active' : '' }}"
                href="{{ route('admin.standings.index') }}">
                <i class="bi bi-bar-chart-line"></i> Klasemen
            </a>

            <div class="nav-divider">Tim & pemain</div>

            {{-- TEAMS --}}
            <a class="nav-link {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}"
                href="{{ route('admin.teams.index') }}">
                <i class="bi bi-people"></i> Tim
            </a>

            {{-- PLAYERS --}}
            <a class="nav-link {{ request()->routeIs('admin.players.*') ? 'active' : '' }}"
                href="{{ route('admin.players.index') }}">
                <i class="bi bi-person-badge"></i> Pemain
            </a>

            <div class="nav-divider">Pengaturan situs</div>

            {{-- HIGHLIGHTS --}}
            <a class="nav-link {{ request()->routeIs('highlights.*') ? 'active' : '' }}"
                href="{{ route('highlights.index') }}">
                <i class="bi bi-play-circle"></i> Sorotan
            </a>

            {{-- HERO SETTINGS --}}
            <a class="nav-link {{ request()->routeIs('admin.hero-settings.*') ? 'active' : '' }}"
                href="{{ route('admin.hero-settings.index') }}">
                <i class="bi bi-image"></i> Banner beranda
            </a>
        </nav>

        {{-- Quick actions --}}
        <nav class="nav flex-column mt-2" aria-label="Pintasan">
            <div class="nav-divider">Pintasan</div>

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

        <div class="app-version">Versi aplikasi {{ config('app.version', 'v1.1.0') }}</div>
    </div>
</div>

<button class="sidebar-toggle d-lg-none" id="sidebarToggle" type="button" aria-label="Buka atau tutup navigasi" aria-controls="sidebar" aria-expanded="false">
    <i class="bi bi-list"></i>
</button>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('sidebarToggle');

        if (toggleButton && sidebar) {
            const closeSidebar = function () {
                sidebar.classList.remove('open');
                toggleButton.setAttribute('aria-expanded', 'false');
            };

            toggleButton.addEventListener('click', function (e) {
                e.stopPropagation();
                sidebar.classList.toggle('open');
                toggleButton.setAttribute('aria-expanded', sidebar.classList.contains('open') ? 'true' : 'false');
            });

            // Tutup sidebar saat klik di luar (mobile)
            document.addEventListener('click', function (event) {
                if (window.innerWidth < 992 && sidebar.classList.contains('open')) {
                    const inside = sidebar.contains(event.target);
                    const onToggle = toggleButton.contains(event.target);
                    if (!inside && !onToggle) {
                        closeSidebar();
                    }
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && sidebar.classList.contains('open')) {
                    closeSidebar();
                    toggleButton.focus();
                }
            });

            // Tutup sidebar di mobile setelah navigasi
            sidebar.querySelectorAll('.nav-link').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth < 992) {
                        closeSidebar();
                    }
                });
            });
        }
    });
</script>
