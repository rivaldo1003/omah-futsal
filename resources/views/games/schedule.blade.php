<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Schedule - OFS Futsal Center</title>
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
        --sidebar-bg: #0f172a;
        --sidebar-link: #cbd5e1;
        --nav-bg: rgba(255, 255, 255, 0.98);
        --nav-shadow: rgba(15, 23, 42, 0.1);
        --card-shadow: rgba(0, 0, 0, 0.05);
    }

    body {
        background-color: var(--light-color);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        color: #334155;
        line-height: 1.6;
    }

    /* Navigation - Premium Redesign */
    .navbar {
        background: var(--nav-bg);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px var(--nav-shadow);
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(59, 130, 246, 0.1);
        position: sticky;
        top: 0;
        z-index: 1040;
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0.5rem 0;
    }

    .brand-container {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .brand-logo {
        width: 40px;
        height: 40px;
        object-fit: contain;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }

    .brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .brand-main {
        font-size: 1.3rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary-color), #60a5fa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: 0.5px;
    }

    .brand-sub {
        font-size: 0.7rem;
        color: #64748b;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .nav-link {
        color: #475569 !important;
        font-weight: 600;
        padding: 0.6rem 1rem !important;
        border-radius: 8px;
        margin: 0 3px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
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

    .nav-link:hover {
        background: rgba(59, 130, 246, 0.08);
        color: var(--accent-color) !important;
        transform: translateY(-1px);
    }

    .nav-link:hover::before {
        width: 100%;
    }

    .nav-link.active {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(96, 165, 250, 0.1));
        color: var(--accent-color) !important;
        border: 1px solid rgba(59, 130, 246, 0.2);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
    }

    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 3px;
        background: linear-gradient(90deg, var(--accent-color), #60a5fa);
        border-radius: 3px;
    }

    .nav-link i {
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .nav-link:hover i {
        transform: scale(1.1);
        color: var(--accent-color) !important;
    }

    .nav-link.active i {
        color: var(--accent-color) !important;
    }

    /* Admin Badge */
    .admin-badge {
        background: linear-gradient(135deg, var(--warning-color), #fbbf24);
        color: #000 !important;
        border: 2px solid rgba(245, 194, 17, 0.3);
        font-weight: 700;
        padding: 0.4rem 0.8rem !important;
        box-shadow: 0 4px 12px rgba(245, 194, 17, 0.2);
    }

    .admin-badge:hover {
        background: linear-gradient(135deg, #f5c211, #f59e0b) !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 194, 17, 0.3);
    }

    /* Logout Button */
    .btn-logout-nav {
        background: rgba(239, 68, 68, 0.1) !important;
        color: #ef4444 !important;
        border: 1px solid rgba(239, 68, 68, 0.2) !important;
        padding: 0.6rem 1rem !important;
        transition: all 0.3s ease !important;
    }

    .btn-logout-nav:hover {
        background: rgba(239, 68, 68, 0.2) !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
    }

    /* Login Button */
    .btn-login-nav {
        background: linear-gradient(135deg, var(--accent-color), #60a5fa) !important;
        color: white !important;
        border: none !important;
        padding: 0.6rem 1.2rem !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        transition: all 0.3s ease !important;
    }

    .btn-login-nav:hover {
        background: linear-gradient(135deg, #2563eb, var(--accent-color)) !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }

    /* Main Content */
    .main-container {
        padding-top: 2rem;
        padding-bottom: 3rem;
    }

    /* Breadcrumb */
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 1.5rem;
        border-radius: 8px;
    }

    .breadcrumb-item a {
        color: var(--accent-color);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }

    .breadcrumb-item a:hover {
        color: #2563eb;
    }

    .breadcrumb-item.active {
        color: #64748b;
    }

    /* Page Header */
    .page-header {
        margin-bottom: 2.5rem;
    }

    .page-header h1 {
        font-weight: 800;
        color: #1e293b;
        font-size: 2.2rem;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #334155, #1e293b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .page-header p {
        color: #64748b;
        font-size: 1.1rem;
        max-width: 700px;
    }

    /* Filter Section */
    .filter-section {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.75rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 4px 12px var(--card-shadow);
    }

    .form-label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control,
    .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.6rem 0.85rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    /* Match Day Card */
    .match-day {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 2rem;
        background: white;
        box-shadow: 0 4px 12px var(--card-shadow);
        transition: all 0.3s ease;
    }

    .match-day:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .date-header {
        background: linear-gradient(135deg, var(--accent-color), #60a5fa);
        color: white;
        padding: 1.25rem 2rem;
        margin: 0;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .date-header i {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .date-header .badge {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        font-weight: 600;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        backdrop-filter: blur(10px);
    }

    /* Match Card */
    .match-card {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .match-card:last-child {
        border-bottom: none;
    }

    .match-card:hover {
        background-color: #f8fafc;
    }

    .match-time {
        font-weight: 700;
        color: var(--accent-color);
        font-size: 1rem;
        text-align: center;
        margin-bottom: 0.3rem;
    }

    .match-venue {
        font-size: 0.85rem;
        color: #64748b;
        text-align: center;
    }

    .team-name {
        font-weight: 700;
        color: #334155;
        font-size: 1rem;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    /* Team Logo */
    .team-logo {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        margin: 0 auto 0.75rem auto;
        transition: all 0.3s ease;
    }

    .match-card:hover .team-logo {
        border-color: var(--accent-color);
        transform: scale(1.05);
    }

    .team-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .team-logo-fallback {
        font-weight: 800;
        color: #334155;
        font-size: 1.1rem;
    }

    /* Team Info Container */
    .team-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0.5rem;
    }

    /* Score Section */
    .score-badge {
        background: linear-gradient(135deg, var(--accent-color), #60a5fa);
        color: white;
        padding: 0.7rem 1.2rem;
        border-radius: 10px;
        font-weight: 800;
        font-size: 1.4rem;
        min-width: 90px;
        text-align: center;
        display: inline-block;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .score-badge.live {
        background: linear-gradient(135deg, #ef4444, #f87171);
        animation: pulse 2s infinite;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    @keyframes pulse {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.8;
        }

        100% {
            opacity: 1;
        }
    }

    /* Status Badge */
    .status-badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-weight: 600;
    }

    .status-completed {
        background: linear-gradient(135deg, var(--success-color), #2dd4bf);
        color: white;
    }

    .status-ongoing {
        background: linear-gradient(135deg, var(--danger-color), #f87171);
        color: white;
    }

    .status-upcoming {
        background: linear-gradient(135deg, var(--warning-color), #fbbf24);
        color: #000;
    }

    /* Stats Cards */
    .stats-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 4px 12px var(--card-shadow);
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .stats-card .card-title {
        font-weight: 600;
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-card h2 {
        font-weight: 800;
        font-size: 2.2rem;
        margin: 0;
    }

    .stats-total .card-title {
        color: var(--accent-color);
    }

    .stats-completed .card-title {
        color: var(--success-color);
    }

    .stats-upcoming .card-title {
        color: var(--warning-color);
    }

    .stats-ongoing .card-title {
        color: var(--danger-color);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        background: white;
        border-radius: 12px;
        border: 2px dashed #e2e8f0;
        margin: 2rem 0;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
        opacity: 0.6;
    }

    .empty-state h3 {
        color: #334155;
        margin-bottom: 0.5rem;
        font-weight: 700;
        font-size: 1.5rem;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 1.5rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Buttons */
    .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.6rem 1.3rem;
        border: 1px solid transparent;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn i {
        margin-right: 6px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent-color), #60a5fa);
        border: none;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, var(--accent-color));
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }

    .btn-outline-primary {
        color: var(--accent-color);
        border-color: var(--accent-color);
        background: transparent;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, var(--accent-color), #60a5fa);
        border-color: var(--accent-color);
        color: white;
        transform: translateY(-1px);
    }

    .btn-sm {
        padding: 0.4rem 0.9rem;
        font-size: 0.85rem;
    }

    /* Footer */
    .footer {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: white;
        padding: 3rem 0 1.5rem;
        margin-top: 4rem;
        position: relative;
    }

    .footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.5), transparent);
    }

    .copyright {
        color: #94a3b8;
        font-size: 0.9rem;
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

    /* Match Details Layout */
    .teams-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .team-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .team-home {
        text-align: center;
    }

    .team-away {
        text-align: center;
    }

    .score-container {
        flex-shrink: 0;
        text-align: center;
        min-width: 100px;
    }

    .match-round {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 4px;
        font-weight: 500;
    }

    /* Team with Logo */
    .team-with-logo {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.8rem;
        }

        .date-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
        }

        .date-header .badge {
            align-self: flex-start;
        }

        .match-card {
            padding: 1.25rem;
        }

        .team-name {
            font-size: 0.95rem;
        }

        .team-logo {
            width: 40px;
            height: 40px;
        }

        .score-badge {
            font-size: 1.2rem;
            min-width: 80px;
            padding: 0.6rem 1rem;
        }

        .stats-card {
            padding: 1.25rem;
        }

        .stats-card h2 {
            font-size: 1.8rem;
        }

        .filter-section {
            padding: 1.25rem;
        }
    }

    /* Utility Classes */
    .text-muted {
        color: #64748b !important;
        font-size: 0.9rem;
    }

    .bg-light {
        background-color: #f8fafc !important;
    }

    .border-light {
        border-color: #e2e8f0 !important;
    }

    /* Badges */
    .badge {
        border-radius: 6px;
        font-weight: 600;
        padding: 0.35rem 0.65rem;
    }

    /* Navbar Toggler */
    .navbar-toggler {
        border: 1px solid rgba(59, 130, 246, 0.2);
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .navbar-toggler:hover {
        border-color: var(--accent-color);
        background: rgba(59, 130, 246, 0.05);
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2859, 130, 246, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                            <i class="bi bi-house-door"></i> Home
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
                    @endif

                    @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link admin-badge" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Admin Panel
                        </a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn-logout-nav btn btn-link p-0 border-0">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link btn-login-nav" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container main-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Match Schedule</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <!-- <i class="bi bi-calendar2-week"> -->

                </i> Match Schedule
            </h1>
            <p class="text-muted">View all matches, filter by date, group, or status.</p>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form action="{{ route('schedule') }}" method="GET" class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Search Team</label>
                    <input type="text" name="search" class="form-control" placeholder="Search team name..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Group</label>
                    <select name="group" class="form-select">
                        <option value="all">All Groups</option>
                        @foreach(['A', 'B'] as $group)
                        <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>
                            Group {{ $group }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="all">All Status</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming
                        </option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                        </option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-lg-2 col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Schedule Content -->
        <div class="row">
            <div class="col-12">
                @if($matches->count() > 0)
                @foreach($groupedMatches as $date => $matchesOnDate)
                <div class="match-day">
                    <div class="date-header">
                        <div>
                            <i class="bi bi-calendar-date"></i>
                            {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}
                        </div>
                        <span class="badge">{{ $matchesOnDate->count() }} matches</span>
                    </div>

                    <div class="match-list">
                        @foreach($matchesOnDate as $match)
                        <div class="match-card">
                            <div class="row align-items-center">
                                <!-- Time & Venue -->
                                <div class="col-lg-2 col-md-3 mb-3 mb-md-0">
                                    <div class="text-center">
                                        <div class="match-time">{{ date('H:i', strtotime($match->time_start)) }}</div>
                                        <div class="match-venue">{{ $match->venue ?? 'Main Field' }}</div>
                                    </div>
                                </div>

                                <!-- Teams & Score -->
                                <div class="col-lg-7 col-md-6">
                                    <div class="teams-container">
                                        <!-- Home Team -->
                                        <div class="team-container team-home">
                                            <div class="team-with-logo">
                                                <!-- Home Team Logo -->
                                                @php
                                                $homeTeam = $match->homeTeam ?? null;
                                                $homeTeamName = $homeTeam->name ?? 'TBA';
                                                $homeTeamLogo = $homeTeam->logo ?? null;
                                                $homeTeamAbbr = strtoupper(substr($homeTeamName, 0, 1));
                                                @endphp

                                                <div class="team-logo">
                                                    @if($homeTeamLogo)
                                                    @php
                                                    // Process logo path
                                                    if (filter_var($homeTeamLogo, FILTER_VALIDATE_URL)) {
                                                    $logoPath = $homeTeamLogo;
                                                    } else {
                                                    try {
                                                    if (Storage::disk('public')->exists($homeTeamLogo)) {
                                                    $logoPath = asset('storage/' . $homeTeamLogo);
                                                    } else {
                                                    $logoPath = null;
                                                    }
                                                    } catch (Exception $e) {
                                                    $logoPath = null;
                                                    }
                                                    }
                                                    @endphp

                                                    @if($logoPath)
                                                    <img src="{{ $logoPath }}" alt="{{ $homeTeamName }}"
                                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <span class="team-logo-fallback" style="display: none;">
                                                        {{ $homeTeamAbbr }}
                                                    </span>
                                                    @else
                                                    <span class="team-logo-fallback">
                                                        {{ $homeTeamAbbr }}
                                                    </span>
                                                    @endif
                                                    @else
                                                    <span class="team-logo-fallback">
                                                        {{ $homeTeamAbbr }}
                                                    </span>
                                                    @endif
                                                </div>

                                                <div class="team-name">{{ $homeTeamName }}</div>
                                                <div class="match-round">
                                                    @if($match->round_type == 'group')
                                                    Group {{ $match->group_name }}
                                                    @else
                                                    {{ ucfirst(str_replace('_', ' ', $match->round_type)) }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Score -->
                                        <div class="score-container">
                                            @if($match->status == 'completed')
                                            <div class="score-badge">{{ $match->home_score }} - {{ $match->away_score }}
                                            </div>
                                            @elseif($match->status == 'ongoing')
                                            <div class="score-badge live">{{ $match->home_score ?? 0 }} -
                                                {{ $match->away_score ?? 0 }}</div>
                                            @else
                                            <div class="score-badge bg-secondary">VS</div>
                                            @endif
                                        </div>

                                        <!-- Away Team -->
                                        <div class="team-container team-away">
                                            <div class="team-with-logo">
                                                <!-- Away Team Logo -->
                                                @php
                                                $awayTeam = $match->awayTeam ?? null;
                                                $awayTeamName = $awayTeam->name ?? 'TBA';
                                                $awayTeamLogo = $awayTeam->logo ?? null;
                                                $awayTeamAbbr = strtoupper(substr($awayTeamName, 0, 1));
                                                @endphp

                                                <div class="team-logo">
                                                    @if($awayTeamLogo)
                                                    @php
                                                    // Process logo path
                                                    if (filter_var($awayTeamLogo, FILTER_VALIDATE_URL)) {
                                                    $logoPath = $awayTeamLogo;
                                                    } else {
                                                    try {
                                                    if (Storage::disk('public')->exists($awayTeamLogo)) {
                                                    $logoPath = asset('storage/' . $awayTeamLogo);
                                                    } else {
                                                    $logoPath = null;
                                                    }
                                                    } catch (Exception $e) {
                                                    $logoPath = null;
                                                    }
                                                    }
                                                    @endphp

                                                    @if($logoPath)
                                                    <img src="{{ $logoPath }}" alt="{{ $awayTeamName }}"
                                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <span class="team-logo-fallback" style="display: none;">
                                                        {{ $awayTeamAbbr }}
                                                    </span>
                                                    @else
                                                    <span class="team-logo-fallback">
                                                        {{ $awayTeamAbbr }}
                                                    </span>
                                                    @endif
                                                    @else
                                                    <span class="team-logo-fallback">
                                                        {{ $awayTeamAbbr }}
                                                    </span>
                                                    @endif
                                                </div>

                                                <div class="team-name">{{ $awayTeamName }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-lg-1 col-md-2 text-center mb-3 mb-md-0">
                                    @if($match->status == 'completed')
                                    <span class="status-badge status-completed">Completed</span>
                                    @elseif($match->status == 'ongoing')
                                    <span class="status-badge status-ongoing">Live</span>
                                    @else
                                    <span class="status-badge status-upcoming">Upcoming</span>
                                    @endif
                                </div>

                                <!-- Action -->
                                <div class="col-lg-2 col-md-1 text-center text-md-end">
                                    <a href="{{ route('matches.show', $match->id) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye"></i> Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <!-- Pagination -->
                @if($matches->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $matches->links() }}
                </div>
                @endif
                @else
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h3>No Matches Found</h3>
                    <p class="text-muted">Try adjusting your filters or check back later.</p>
                    <a href="{{ route('schedule') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise"></i> Clear Filters
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mt-5">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card stats-total">
                    <div class="card-title">Total Matches</div>
                    <h2>{{ $matches->total() }}</h2>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card stats-completed">
                    <div class="card-title">Completed</div>
                    <h2>{{ $matches->where('status', 'completed')->count() }}</h2>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card stats-upcoming">
                    <div class="card-title">Upcoming</div>
                    <h2>{{ $matches->where('status', 'upcoming')->count() }}</h2>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card stats-ongoing">
                    <div class="card-title">Ongoing</div>
                    <h2>{{ $matches->where('status', 'ongoing')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
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
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.style.boxShadow = '0 4px 20px rgba(15, 23, 42, 0.15)';
            navbar.style.background = 'rgba(255, 255, 255, 0.98)';
        } else {
            navbar.style.boxShadow = '0 4px 20px rgba(15, 23, 42, 0.1)';
            navbar.style.background = 'rgba(255, 255, 255, 0.98)';
        }
    });

    // Auto-submit form when date is selected
    document.querySelector('input[name="date"]')?.addEventListener('change', function() {
        this.form.submit();
    });

    // Live badge animation
    function refreshLiveMatches() {
        const liveBadges = document.querySelectorAll('.score-badge.live');
        liveBadges.forEach(badge => {
            badge.style.animation = 'none';
            setTimeout(() => {
                badge.style.animation = 'pulse 2s infinite';
            }, 10);
        });
    }

    // Card hover effects
    document.addEventListener('DOMContentLoaded', function() {
        refreshLiveMatches();

        // Match day card hover
        document.querySelectorAll('.match-day').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Stats card hover
        document.querySelectorAll('.stats-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Handle image loading errors
        document.querySelectorAll('.team-logo img').forEach(img => {
            img.addEventListener('error', function() {
                this.style.display = 'none';
                const fallback = this.nextElementSibling;
                if (fallback && fallback.classList.contains('team-logo-fallback')) {
                    fallback.style.display = 'flex';
                }
            });
        });

        // Refresh live badges every 5 seconds
        setInterval(refreshLiveMatches, 5000);
    });
    </script>
</body>

</html>