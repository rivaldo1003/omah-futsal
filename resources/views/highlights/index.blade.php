{{-- resources/views/highlights/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Highlights - OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
    :root {
        --primary-color: #1a5fb4;
        --secondary-color: #5e5c64;
        --success-color: #26a269;
        --danger-color: #c01c28;
        --warning-color: #f5c211;
        --info-color: #1c71d8;
        --dark-color: #0f172a;
        --light-color: #f8fafc;
        --accent-color: #3b82f6;
        --nav-bg: rgba(255, 255, 255, 0.98);
        --nav-shadow: rgba(15, 23, 42, 0.1);
        --card-shadow: rgba(0, 0, 0, 0.05);
    }

    /* Reset & Base */
    * {
        box-sizing: border-box;
    }

    body {
        background-color: var(--light-color);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        color: #334155;
        line-height: 1.6;
        overflow-x: hidden;
        font-size: 15px;
    }

    /* Compact Container */
    .container {
        padding-left: max(12px, env(safe-area-inset-left));
        padding-right: max(12px, env(safe-area-inset-right));
        max-width: 1200px;
    }

    /* Navigation - Fully Responsive */
    /* Compact Navigation */
    .navbar {
        background: var(--nav-bg);
        backdrop-filter: blur(10px);
        box-shadow: 0 1px 3px var(--nav-shadow);
        padding: 0.4rem 0;
        position: sticky;
        top: 0;
        z-index: 1040;
    }

    .navbar-brand {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0;
        margin-right: 0;
    }

    .brand-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }


    .brand-logo {
        width: 35px;
        height: 35px;
        object-fit: contain;
    }

    .brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .brand-main {
        font-size: 1rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary-color), #60a5fa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .brand-sub {
        font-size: 0.6rem;
        color: #64748b;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        font-weight: 600;
    }

    /* Mobile First Nav */
    .navbar-toggler {
        border: 1px solid rgba(59, 130, 246, 0.2);
        padding: 0.4rem 0.6rem;
        font-size: 0.9rem;
    }

    .navbar-collapse {
        margin-top: 0.5rem;
        border-radius: 8px;
        background: var(--nav-bg);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .nav-link {
        color: #475569 !important;
        font-weight: 600;
        padding: 0.5rem 0.8rem !important;
        border-radius: 4px;
        margin: 1px 0;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
    }

    .nav-link i {
        font-size: 0.9rem;
        width: 18px;
        text-align: center;
    }

    .nav-link.active {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.12), rgba(96, 165, 250, 0.08));
        color: var(--accent-color) !important;
    }

    /* Mobile Optimized Buttons */
    .btn {
        border-radius: 6px;
        font-weight: 600;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .btn-sm {
        padding: 0.35rem 0.7rem;
        font-size: 0.85rem;
    }

    .btn-login-nav,
    .admin-badge,
    .btn-logout-nav {
        width: 100%;
        margin: 5px 0;
        justify-content: center;
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        position: relative;
    }

    .page-header h1 {
        font-weight: 800;
        font-size: 2.2rem;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    /* Highlight Cards */
    .highlight-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        background: white;
        transition: all 0.3s ease;
        height: 100%;
        cursor: pointer;
    }

    .highlight-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .highlight-thumbnail {
        position: relative;
        overflow: hidden;
        height: 200px;
    }

    .highlight-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .highlight-card:hover .highlight-thumbnail img {
        transform: scale(1.05);
    }

    .play-button-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .highlight-card:hover .play-button-overlay {
        opacity: 1;
    }

    .play-button {
        width: 60px;
        height: 60px;
        background: rgba(59, 130, 246, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        transform: scale(0.8);
        transition: transform 0.3s ease;
    }

    .highlight-card:hover .play-button {
        transform: scale(1);
    }

    .highlight-info {
        padding: 1.25rem;
    }

    .match-teams {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        color: var(--dark-color);
    }

    .match-score {
        color: var(--success-color);
        font-weight: 700;
        margin-right: 0.5rem;
    }

    .match-details {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .duration-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #64748b;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* YouTube Badge */
    .youtube-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #ff0000;
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 4px;
        z-index: 10;
    }

    /* Modal styles */
    .modal-video {
        border-radius: 8px;
        overflow: hidden;
    }

    /* Footer */
    .footer {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: white;
        padding: 2rem 0 1.2rem;
        margin-top: 2.5rem;
        font-size: 0.9rem;
    }

    .footer-title {
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 1.1rem;
        color: #e2e8f0;
    }

    .footer-links a {
        color: #cbd5e1;
        text-decoration: none;
        display: block;
        margin-bottom: 0.5rem;
        padding: 0.2rem 0;
    }

    .footer-contact {
        color: #cbd5e1;
        font-size: 0.85rem;
    }

    .social-icons {
        display: flex;
        gap: 10px;
        margin-top: 1rem;
    }

    .social-icons a {
        color: #cbd5e1;
        font-size: 1.2rem;
        transition: color 0.2s;
    }

    .copyright {
        color: #94a3b8;
        font-size: 0.8rem;
        padding-top: 1.2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
    }

    /* Pagination */
    .pagination {
        justify-content: center;
        margin-top: 2rem;
    }

    .page-link {
        border-color: #e2e8f0;
        color: var(--accent-color);
        border-radius: 8px;
        margin: 0 0.25rem;
        font-weight: 500;
    }

    .page-link:hover {
        background-color: rgba(59, 130, 246, 0.1);
        border-color: var(--accent-color);
        transform: translateY(-1px);
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--accent-color), #60a5fa);
        border-color: var(--accent-color);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }

    /* Responsive */
    @media (min-width: 768px) {
        .navbar-collapse {
            margin-top: 0;
            background: transparent;
            box-shadow: none;
        }

        .nav-link {
            padding: 0.6rem 1rem !important;
            margin: 0 3px;
            width: auto;
        }

        .btn-login-nav,
        .admin-badge,
        .btn-logout-nav {
            width: auto;
            margin: 0;
        }
    }

    @media (max-width: 576px) {
        .page-header {
            padding: 2rem 0;
        }

        .page-header h1 {
            font-size: 1.8rem;
        }

        .highlight-thumbnail {
            height: 150px;
        }
    }

    .compact-text {
        font-size: 0.85rem;
        line-height: 1.3;
    }

    .compact-meta {
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .compact-heading {
        font-size: 1.1rem;
        margin-bottom: 0.8rem;
    }

    /* Better Spacing */
    .mb-compact-1 {
        margin-bottom: 0.5rem;
    }

    .mb-compact-2 {
        margin-bottom: 1rem;
    }

    .mb-compact-3 {
        margin-bottom: 1.5rem;
    }

    .p-compact-1 {
        padding: 0.5rem;
    }
    </style>
</head>

<body>
    <!-- Navigation Bar (Sama seperti Home) -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <div class="brand-container">
                    <img src="{{ asset('images/logo-ofs.png') }}" alt="OFS Logo" class="brand-logo">
                    <div class="brand-text">
                        <div class="brand-main">OFS FUTSAL</div>
                        <div class="brand-sub">Arena Center</div>
                    </div>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('news*') ? 'active' : '' }}"
                            href="{{ route('news.index') }}">
                            <i class="bi bi-newspaper"></i> News
                        </a>
                    </li>

                    @auth
                    @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('tournaments*') ? 'active' : '' }}"
                            href="{{ route('tournaments.index') }}">
                            <i class="bi bi-trophy"></i> Tournaments
                        </a>
                    </li>
                    @endif
                    @endauth

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('schedule*') || request()->is('matches*') ? 'active' : '' }}"
                            href="{{ route('schedule') }}">
                            <i class="bi bi-calendar2-week"></i> Schedule
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('standings*') ? 'active' : '' }}"
                            href="{{ route('standings') }}">
                            <i class="bi bi-bar-chart-line"></i> Standings
                        </a>
                    </li>

                    <!-- Highlights Link - Active State -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('highlights*') ? 'active' : '' }}"
                            href="{{ route('highlights.index') }}">
                            <i class="bi bi-play-circle"></i> Highlights
                        </a>
                    </li>

                    @auth
                    @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('teams*') ? 'active' : '' }}"
                            href="{{ route('teams.index') }}">
                            <i class="bi bi-people-fill"></i> Teams
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link admin-badge" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Admin
                        </a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link btn-logout-nav p-0 border-0">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary btn-login-nav" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="display-5 fw-bold mb-3">
                        <i class="bi bi-youtube me-2"></i>Match Highlights
                    </h1>
                    <p class="lead mb-0">
                        Watch YouTube highlights from recent matches
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        @if($highlights->count() > 0)
        <div class="row">
            @foreach($highlights as $highlight)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="highlight-card" data-bs-toggle="modal" data-bs-target="#youtubeModal{{ $highlight->id }}">
                    <!-- Thumbnail -->
                    <div class="highlight-thumbnail">
                        <!-- YouTube Badge -->
                        <div class="youtube-badge">
                            <i class="bi bi-youtube"></i>
                            <span>YouTube</span>
                        </div>

                        <!-- YouTube Thumbnail -->
                        @if($highlight->youtube_id)
                        <img src="{{ $highlight->display_thumbnail_url ?? 'https://img.youtube.com/vi/' . $highlight->youtube_id . '/maxresdefault.jpg' }}"
                            alt="{{ $highlight->homeTeam->name ?? 'Home' }} vs {{ $highlight->awayTeam->name ?? 'Away' }}"
                            onerror="this.src='https://img.youtube.com/vi/{{ $highlight->youtube_id }}/hqdefault.jpg'">
                        @else
                        <div class="bg-dark h-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-youtube text-danger" style="font-size: 4rem;"></i>
                        </div>
                        @endif

                        <!-- Play Button Overlay -->
                        <div class="play-button-overlay">
                            <div class="play-button">
                                <i class="bi bi-play-fill"></i>
                            </div>
                        </div>

                        <!-- Duration Badge -->
                        @if($highlight->youtube_duration_formatted)
                        <span class="duration-badge">
                            {{ $highlight->youtube_duration_formatted }}
                        </span>
                        @endif
                    </div>

                    <!-- Match Info -->
                    <div class="highlight-info">
                        <h6 class="match-teams mb-2">
                            @if($highlight->home_score !== null && $highlight->away_score !== null)
                            <span class="match-score">{{ $highlight->home_score }} -
                                {{ $highlight->away_score }}</span>
                            @endif
                            {{ $highlight->homeTeam->name ?? 'Home' }} vs {{ $highlight->awayTeam->name ?? 'Away' }}
                        </h6>

                        <div class="match-details">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small>
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $highlight->match_date->format('d M Y') }}
                                </small>
                                <small>
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $highlight->time_start }}
                                </small>
                            </div>
                            <small>
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $highlight->venue ?? 'Main Field' }}
                            </small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">
                                @if($highlight->youtube_uploaded_at)
                                <i class="bi bi-clock-history me-1"></i>
                                {{ $highlight->youtube_uploaded_at->diffForHumans() }}
                                @endif
                            </small>
                            <span class="badge bg-danger">
                                <i class="bi bi-youtube me-1"></i> Watch
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($highlights->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{ $highlights->links() }}
                    </ul>
                </nav>
            </div>
        </div>
        @endif
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="bi bi-youtube"></i>
            <h4 class="mt-3">No YouTube Highlights Available</h4>
            <p class="text-muted">Check back later for match highlights on YouTube</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left me-1"></i> Back to Home
            </a>
        </div>
        @endif
    </div>

    <!-- YouTube Modals -->
    @foreach($highlights as $highlight)
    @if($highlight->youtube_id)
    <div class="modal fade" id="youtubeModal{{ $highlight->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-youtube text-danger" style="font-size: 1.2rem;"></i>
                        <h5 class="modal-title mb-0">
                            {{ $highlight->homeTeam->name ?? 'Home' }} vs {{ $highlight->awayTeam->name ?? 'Away' }}
                            @if($highlight->home_score !== null && $highlight->away_score !== null)
                            <span class="badge bg-success ms-2">{{ $highlight->home_score }} -
                                {{ $highlight->away_score }}</span>
                            @endif
                        </h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <!-- YouTube Embed -->
                    <div class="ratio ratio-16x9">
                        <iframe
                            src="https://www.youtube.com/embed/{{ $highlight->youtube_id }}?rel=0&showinfo=0&modestbranding=1"
                            title="Highlight: {{ $highlight->homeTeam->name ?? 'Home' }} vs {{ $highlight->awayTeam->name ?? 'Away' }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen style="background: #000;">
                        </iframe>
                    </div>

                    <!-- Match Details -->
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">
                                    <i class="bi bi-info-circle me-2"></i>Match Information
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <strong class="d-block text-muted small">Date & Time</strong>
                                        <span>{{ $highlight->match_date->format('d M Y') }} •
                                            {{ $highlight->time_start }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <strong class="d-block text-muted small">Venue</strong>
                                        <span>{{ $highlight->venue ?? 'Main Field' }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <strong class="d-block text-muted small">Stage</strong>
                                        <span>{{ ucfirst(str_replace('_', ' ', $highlight->round_type ?? 'group')) }}</span>
                                    </li>
                                    @if($highlight->group_name)
                                    <li class="mb-2">
                                        <strong class="d-block text-muted small">Group</strong>
                                        <span>Group {{ $highlight->group_name }}</span>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">
                                    <i class="bi bi-youtube text-danger me-2"></i>YouTube Information
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <strong class="d-block text-muted small">Video ID</strong>
                                        <code>{{ $highlight->youtube_id }}</code>
                                    </li>
                                    @if($highlight->youtube_duration_formatted)
                                    <li class="mb-2">
                                        <strong class="d-block text-muted small">Duration</strong>
                                        <span>{{ $highlight->youtube_duration_formatted }}</span>
                                    </li>
                                    @endif
                                    @if($highlight->youtube_uploaded_at)
                                    <li class="mb-2">
                                        <strong class="d-block text-muted small">Added</strong>
                                        <span>{{ $highlight->youtube_uploaded_at->format('d M Y H:i') }}</span>
                                    </li>
                                    @endif
                                    <li class="mb-2">
                                        <strong class="d-block text-muted small">Watch on YouTube</strong>
                                        <a href="https://youtube.com/watch?v={{ $highlight->youtube_id }}"
                                            target="_blank" class="text-decoration-none">
                                            <i class="bi bi-box-arrow-up-right me-1"></i>
                                            Open in YouTube
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Close
                    </button>
                    <a href="https://youtube.com/watch?v={{ $highlight->youtube_id }}" target="_blank"
                        class="btn btn-danger">
                        <i class="bi bi-youtube me-1"></i> Watch on YouTube
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title">
                        <i class="bi bi-trophy-fill"></i> OFS Futsal Center
                    </h5>
                    <div class="social-icons">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title">Quick Links</h5>
                    <div class="footer-links">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('schedule') }}">Schedule</a>
                        <a href="{{ route('standings') }}">Standings</a>
                        <a href="{{ route('highlights.index') }}">Highlights</a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-title">Contact Info</h5>
                    <div class="footer-contact">
                        <p class="mb-2"><i class="bi bi-geo-alt"></i> OFS Futsal Center Jombang</p>
                        <p class="mb-2"><i class="bi bi-telephone"></i> +62 812 4752 1076</p>
                        <p class="mb-2"><i class="bi bi-envelope"></i> ofsfutsalcenter@gmail.com</p>
                        <p class="mb-0"><i class="bi bi-clock"></i> Mon-Sun: 07.00-23.30</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        <p class="mb-0">&copy; {{ date('Y') }} OFS Futsal Center. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh YouTube iframes when modal is shown
        const youtubeModals = document.querySelectorAll('[id^="youtubeModal"]');

        youtubeModals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function() {
                const iframe = this.querySelector('iframe');
                if (iframe) {
                    // Refresh iframe to ensure proper sizing
                    const src = iframe.src;
                    iframe.src = '';
                    setTimeout(() => {
                        iframe.src = src;
                    }, 100);
                }
            });
        });

        // Thumbnail hover effects
        const thumbnails = document.querySelectorAll('.highlight-thumbnail img');
        thumbnails.forEach(thumb => {
            thumb.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });

            thumb.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });

        // Navbar auto-close on mobile
        const navLinks = document.querySelectorAll('.nav-link');
        const navbarCollapse = document.querySelector('.navbar-collapse');

        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                    if (bsCollapse) {
                        bsCollapse.hide();
                    }
                }
            });
        });

        // Debug info (optional, remove in production)
        console.log('Highlights count:', {
            {
                $highlights - > count()
            }
        });
        @foreach($highlights as $highlight)
        console.log('Highlight {{ $loop->iteration }}:', {
            id: {
                {
                    $highlight - > id
                }
            },
            youtube_id: '{{ $highlight->youtube_id }}',
            teams: '{{ $highlight->homeTeam->name ?? "Home" }} vs {{ $highlight->awayTeam->name ?? "Away" }}'
        });
        @endforeach
    });
    </script>
</body>

</html>