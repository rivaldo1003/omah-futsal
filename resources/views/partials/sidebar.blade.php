<style>
/* Variabel CSS untuk tampilan premium dan minimalis */
:root {
    --sidebar-bg: #0f172a;
    --sidebar-link: #cbd5e1;
    --sidebar-link-hover: #ffffff;
    --sidebar-active-bg: rgba(255, 255, 255, 0.08);
    --sidebar-active-border: #3b82f6;
    --divider-color: #334155;
    --text-muted: #94a3b8;
    --shadow-dark: rgba(0, 0, 0, 0.25);
    --accent-color: #3b82f6;
}

.sidebar {
    width: 250px;
    height: 100vh;
    background: linear-gradient(180deg, var(--sidebar-bg) 0%, #1e293b 100%);
    color: var(--sidebar-link);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1030;
    transition: transform 0.3s ease, width 0.3s ease;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 4px 0 15px var(--shadow-dark);
    border-right: 1px solid rgba(255, 255, 255, 0.05);
}

/* Mobile State */
@media (max-width: 991.98px) {
    .sidebar {
        transform: translateX(-250px);
        box-shadow: 4px 0 20px var(--shadow-dark);
    }

    .sidebar.open {
        transform: translateX(0);
    }
}

/* Header/Logo Section - Compact dan Premium */
.sidebar-header {
    padding: 20px 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    flex-shrink: 0;
    background: rgba(0, 0, 0, 0.2);
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 12px;
    transition: transform 0.2s ease;
}

.logo-container:hover {
    transform: translateY(-1px);
}

.logo-img {
    width: 36px;
    height: 36px;
    object-fit: contain;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    transition: transform 0.3s ease;
}

.logo-img:hover {
    transform: scale(1.05);
}

.logo-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.logo-main {
    font-size: 1.2rem;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: 0.5px;
    background: linear-gradient(135deg, #ffffff 0%, var(--accent-color) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.logo-sub {
    font-size: 0.65rem;
    color: var(--text-muted);
    letter-spacing: 1.5px;
    text-transform: uppercase;
    font-weight: 500;
    margin-top: 2px;
}

/* Navigation Container */
.sidebar-content {
    flex: 1;
    overflow-y: auto;
    padding: 15px 10px;
}

/* Navigation Links - Compact */
.nav {
    padding: 0 5px;
}

.nav-link {
    color: var(--sidebar-link);
    padding: 10px 12px;
    border-radius: 8px;
    margin-bottom: 4px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    outline: none !important;
    box-shadow: none !important;
    position: relative;
    overflow: hidden;
}

.nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 0;
    background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0) 100%);
    transition: width 0.3s ease;
}

.nav-link i {
    font-size: 1rem;
    margin-right: 10px;
    color: var(--text-muted);
    transition: all 0.2s ease;
    min-width: 20px;
    text-align: center;
}

.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.05);
    color: var(--sidebar-link-hover);
    transform: translateX(2px);
}

.nav-link:hover::before {
    width: 100%;
}

.nav-link:hover i {
    color: var(--accent-color);
    transform: scale(1.1);
}

/* Active Link State - Premium */
.nav-link.active {
    color: var(--sidebar-link-hover);
    background: var(--sidebar-active-bg);
    font-weight: 600;
    box-shadow: inset 0 0 0 1px rgba(59, 130, 246, 0.2);
}

.nav-link.active i {
    color: var(--accent-color);
}

.nav-link.active::after {
    content: "";
    position: absolute;
    right: -1px;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 70%;
    background: linear-gradient(to bottom, var(--accent-color), #60a5fa);
    border-radius: 3px 0 0 3px;
    animation: pulse 2s infinite;
}

@keyframes pulse {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: 0.6;
    }
}

/* Divider - Lebih Minimalis */
.nav-divider {
    font-size: 0.65rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--text-muted);
    padding: 15px 12px 6px 12px;
    margin-top: 8px;
    position: relative;
    display: flex;
    align-items: center;
}

.nav-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255, 255, 255, 0.1);
    margin-left: 8px;
}

/* News Counter Badge */
.news-count-badge {
    background: linear-gradient(135deg, var(--accent-color), #60a5fa);
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 10px;
    margin-left: auto;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Utility Link - Back to Site */
.nav-link.utility-link {
    color: var(--text-muted) !important;
    font-size: 0.8rem;
    padding: 8px 12px;
}

.nav-link.utility-link:hover {
    color: var(--accent-color) !important;
    background-color: rgba(59, 130, 246, 0.1);
}

/* User and Logout Section - Compact */
.user-profile-section {
    padding: 12px 15px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(0, 0, 0, 0.2);
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
    border-radius: 10px;
    background: linear-gradient(135deg, var(--accent-color), #60a5fa);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
    margin-right: 10px;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.user-details {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-weight: 600;
    color: #ffffff;
    font-size: 0.85rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-role {
    font-size: 0.7rem;
    color: var(--text-muted);
    margin-top: 1px;
}

.btn-logout {
    width: 100%;
    text-align: left;
    color: var(--sidebar-link);
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.2s ease;
    font-weight: 500;
    font-size: 0.85rem;
    outline: none !important;
    box-shadow: none !important;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-logout:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border-color: rgba(239, 68, 68, 0.2);
    transform: translateY(-1px);
}

.btn-logout i {
    margin-right: 6px;
    transition: transform 0.2s ease;
}

.btn-logout:hover i {
    transform: translateX(2px);
}

/* Mobile Toggle Button - Modern */
.sidebar-toggle {
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1031;
    background: linear-gradient(135deg, var(--accent-color), #60a5fa);
    color: white;
    border: none;
    padding: 8px 10px;
    border-radius: 10px;
    font-size: 1rem;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.sidebar-toggle:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
}

.sidebar-toggle:active {
    transform: scale(0.95);
}

/* Custom scrollbar yang lebih halus */
.sidebar-content::-webkit-scrollbar {
    width: 4px;
}

.sidebar-content::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 2px;
}

.sidebar-content::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, var(--accent-color), #60a5fa);
    border-radius: 2px;
}

.sidebar-content::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #60a5fa, var(--accent-color));
}

/* Animasi untuk menu aktif */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.nav-link {
    animation: slideIn 0.3s ease backwards;
}

.nav-link:nth-child(1) {
    animation-delay: 0.1s;
}

.nav-link:nth-child(2) {
    animation-delay: 0.15s;
}

.nav-link:nth-child(3) {
    animation-delay: 0.2s;
}

.nav-link:nth-child(4) {
    animation-delay: 0.25s;
}

.nav-link:nth-child(5) {
    animation-delay: 0.3s;
}

.nav-link:nth-child(6) {
    animation-delay: 0.35s;
}

.nav-link:nth-child(7) {
    animation-delay: 0.4s;
}

.nav-link:nth-child(8) {
    animation-delay: 0.45s;
}

.nav-link:nth-child(9) {
    animation-delay: 0.5s;
}

.nav-link:nth-child(10) {
    animation-delay: 0.55s;
}
</style>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <img src="{{ asset('images/logo-ofs.png') }}" alt="OFS Logo" class="logo-img">
            <div class="logo-text">
                <div class="logo-main">OFS FUTSAL</div>
                <div class="logo-sub">CENTER</div>
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

            <div class="nav-divider">Content Management</div>

            {{-- NEWS MANAGEMENT --}}
            <a class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}"
                href="{{ route('admin.news.index') }}">
                <i class="bi bi-newspaper"></i> News Management
                @php
                $totalNews = \App\Models\NewsArticle::count();
                $unpublishedNews = \App\Models\NewsArticle::where('is_active', false)->count();
                @endphp
                <span class="news-count-badge">
                    {{ $totalNews }}
                    @if($unpublishedNews > 0)
                    <span style="font-size: 0.6rem; opacity: 0.9;">({{ $unpublishedNews }} draft)</span>
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

            <div class="nav-divider">Team & Players</div>

            {{-- TEAMS --}}
            <a class="nav-link {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}"
                href="{{ route('admin.teams.index') }}">
                <i class="bi bi-people-fill"></i> Teams
            </a>

            {{-- PLAYERS --}}
            <a class="nav-link {{ request()->routeIs('admin.players.*') ? 'active' : '' }}"
                href="{{ route('admin.players.index') }}">
                <i class="bi bi-person-badge-fill"></i> Players
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
                <i class="bi bi-image"></i> Hero Settings
            </a>

            {{-- SETTINGS --}}
            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                href="{{ route('admin.settings.index') }}">
                <i class="bi bi-gear"></i> System Settings
            </a>

        </nav>

        {{-- Quick News Actions --}}
        <div class="nav flex-column mt-2" style="padding: 0 5px;">
            <div class="nav-divider">Quick Actions</div>

            <a class="nav-link utility-link" href="{{ route('admin.news.create') }}">
                <i class="bi bi-plus-circle me-2"></i> Create New Article
            </a>

            <!-- <a class="nav-link utility-link" href="{{ route('admin.news.index') }}?status=inactive">
                <i class="bi bi-eye-slash me-2"></i> View Drafts
            </a> -->

            <a class="nav-link utility-link" href="{{ route('news.index') }}" target="_blank">
                <i class="bi bi-eye me-2"></i> View Public News
            </a>
        </div>

        {{-- Back to Site --}}
        <nav class="nav flex-column mt-2" style="padding: 0 5px;">
            <a class="nav-link utility-link" href="/">
                <i class="bi bi-house-door me-2"></i> Back to Site
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
                <div class="user-role">
                    <i class="bi bi-shield-check me-1" style="font-size: 0.6rem;"></i>
                    Administrator
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-logout">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</div>

<button class="sidebar-toggle d-lg-none" id="sidebarToggle">
    <i class="bi bi-grid-3x3-gap"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('sidebarToggle');

    // Toggle sidebar on button click
    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            // Animasi tombol toggle
            this.style.transform = sidebar.classList.contains('open') ? 'rotate(90deg)' : 'rotate(0)';
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 992) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnToggle = toggleButton && toggleButton.contains(event.target);

            if (sidebar.classList.contains('open') && !isClickInsideSidebar && !isClickOnToggle) {
                sidebar.classList.remove('open');
                if (toggleButton) toggleButton.style.transform = 'rotate(0)';
            }
        }
    });

    // Smooth hover effects
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(3px)';
        });

        link.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateX(0)';
            }
        });

        // Add click effect
        link.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                sidebar.classList.remove('open');
                if (toggleButton) toggleButton.style.transform = 'rotate(0)';
            }
        });
    });

    // Update news count periodically
    function updateNewsCount() {
        // This could be enhanced with AJAX to get real-time updates
        console.log('News count update check - could implement AJAX here');
    }

    // Check every 60 seconds for updates
    setInterval(updateNewsCount, 60000);
});
</script>