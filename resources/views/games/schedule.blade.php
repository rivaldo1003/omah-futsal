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
            margin-bottom: 1.5rem;
        }

        .page-header h1 {
            font-weight: 800;
            color: #1e293b;
            font-size: 1.8rem;
            margin-bottom: 0.25rem;
        }

        .page-header p {
            color: #64748b;
            font-size: 0.95rem;
            max-width: 700px;
        }

        /* Tournament Selector */
        .tournament-selector {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px var(--card-shadow);
            border: 1px solid #e2e8f0;
        }

        .tournament-badge {
            background: linear-gradient(135deg, var(--accent-color), #60a5fa);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Filter Section Compact */
        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px var(--card-shadow);
            border: 1px solid #e2e8f0;
        }

        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        /* Tournament Info */
        .tournament-info {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-left: 4px solid var(--accent-color);
            padding: 0.75rem 1rem;
            border-radius: 0 8px 8px 0;
            margin-bottom: 1.5rem;
        }

        /* Match Day Card Compact */
        .match-day {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 1rem;
            background: white;
            box-shadow: 0 2px 8px var(--card-shadow);
        }

        .date-header {
            background: linear-gradient(135deg, var(--accent-color), #60a5fa);
            color: white;
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .date-header i {
            margin-right: 8px;
        }

        .date-header .badge {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.8rem;
        }

        /* Match Card Compact */
        .match-card {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.2s;
        }

        .match-card:hover {
            background-color: #f8fafc;
        }

        .match-card:last-child {
            border-bottom: none;
        }

        /* Compact Match Layout */
        .match-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .match-time {
            min-width: 70px;
            font-weight: 600;
            color: var(--accent-color);
            font-size: 0.9rem;
        }

        .match-venue {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        .match-teams {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            min-width: 200px;
        }

        .team-name {
            font-weight: 600;
            color: #334155;
            font-size: 0.9rem;
            text-align: center;
            min-width: 100px;
        }

        .team-home {
            text-align: right;
        }

        .team-away {
            text-align: left;
        }

        .score-container {
            min-width: 70px;
            text-align: center;
        }

        .score-badge {
            background: linear-gradient(135deg, var(--accent-color), #60a5fa);
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.95rem;
            display: inline-block;
        }

        .score-badge.live {
            background: linear-gradient(135deg, #ef4444, #f87171);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.8; }
            100% { opacity: 1; }
        }

        .match-details {
            min-width: 120px;
            text-align: right;
        }

        /* Status Badge */
        .status-badge {
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 0.25rem;
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

        .round-badge {
            font-size: 0.7rem;
            background: #e2e8f0;
            color: #64748b;
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
            display: inline-block;
        }

        /* Stats Cards Compact */
        .stats-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: white;
            padding: 1rem;
            text-align: center;
            height: 100%;
            box-shadow: 0 2px 8px var(--card-shadow);
        }

        .stats-card .card-title {
            font-weight: 600;
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-card h3 {
            font-weight: 700;
            font-size: 1.5rem;
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
            padding: 3rem 1rem;
            background: white;
            border-radius: 12px;
            border: 2px dashed #e2e8f0;
            margin: 2rem 0;
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
            opacity: 0.6;
        }

        .empty-state h3 {
            color: #334155;
            margin-bottom: 0.5rem;
            font-weight: 700;
            font-size: 1.25rem;
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
            padding: 0.5rem 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-color), #60a5fa);
            border: none;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, var(--accent-color));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
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
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: white;
            padding: 2rem 0 1rem;
            margin-top: 3rem;
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
            font-size: 0.85rem;
            text-align: center;
        }

        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 1.5rem;
        }

        .page-link {
            border-color: #e2e8f0;
            color: var(--accent-color);
            border-radius: 6px;
            margin: 0 0.125rem;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--accent-color), #60a5fa);
            border-color: var(--accent-color);
            box-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            .date-header {
                padding: 0.5rem 0.75rem;
                font-size: 0.85rem;
            }

            .match-info {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }

            .match-time, .match-details {
                text-align: center;
            }

            .team-name {
                min-width: auto;
                font-size: 0.85rem;
            }

            .match-teams {
                flex-direction: column;
                gap: 0.5rem;
            }

            .stats-card {
                padding: 0.75rem;
            }

            .stats-card h3 {
                font-size: 1.25rem;
            }

            .filter-section {
                padding: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar (tetap sama) -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <div class="brand-container">
                    <img src="{{ asset('images/logo-ofs.png') }}" alt="OFS Logo" class="brand-logo">
                    <div class="brand-text">
                        <div class="brand-main">OFS FUTSAL</div>
                        <div class="brand-sub">CENTER</div>
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
                <i class="bi bi-calendar2-week me-2"></i> Match Schedule
            </h1>
            <p class="text-muted">View and filter matches by tournament, date, and status</p>
        </div>

        <!-- Tournament Selector -->
        <div class="tournament-selector">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="mb-2" style="font-weight: 600; color: #334155;">Tournament Filter</h6>
                    <form action="{{ route('schedule') }}" method="GET" class="d-flex gap-2">
                        <select name="tournament" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ !request('tournament') || request('tournament') == 'all' ? 'selected' : '' }}>All Tournaments</option>
                            @foreach($allTournaments as $tournament)
                                <option value="{{ $tournament->id }}" 
                                    {{ request('tournament') == $tournament->id ? 'selected' : '' }}
                                    {{ $tournament->id == ($activeTournament->id ?? null) ? 'class="fw-bold"' : '' }}>
                                    {{ $tournament->name }}
                                    @if($tournament->status == 'ongoing')
                                        <span class="badge bg-success ms-1">Active</span>
                                    @elseif($tournament->status == 'upcoming')
                                        <span class="badge bg-warning ms-1">Upcoming</span>
                                    @else
                                        <span class="badge bg-secondary ms-1">Completed</span>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="col-md-4 text-md-end">
                    @if($selectedTournament)
                        <div class="d-inline-block">
                            <span class="tournament-badge">
                                <i class="bi bi-trophy-fill me-1"></i>
                                {{ $selectedTournament->name }}
                            </span>
                            <div class="text-muted small mt-1">
                                {{ \Carbon\Carbon::parse($selectedTournament->start_date)->format('M d') }} - 
                                {{ \Carbon\Carbon::parse($selectedTournament->end_date)->format('M d, Y') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tournament Info (if selected) -->
        @if($selectedTournament)
            <div class="tournament-info">
                <div class="row">
                    <div class="col-md-8">
                        <h5 style="font-weight: 600; font-size: 0.95rem; margin-bottom: 0.25rem; color: #1e293b;">
                            {{ $selectedTournament->name }}
                        </h5>
                        <p class="mb-0" style="font-size: 0.8rem; color: #64748b;">
                            <i class="bi bi-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($selectedTournament->start_date)->format('F d, Y') }} - 
                            {{ \Carbon\Carbon::parse($selectedTournament->end_date)->format('F d, Y') }}
                            | 
                            <i class="bi bi-geo-alt me-1 ms-2"></i>{{ $selectedTournament->location ?? 'Venue TBD' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="badge {{ $selectedTournament->status == 'ongoing' ? 'bg-success' : ($selectedTournament->status == 'upcoming' ? 'bg-warning' : 'bg-secondary') }}">
                            {{ ucfirst($selectedTournament->status) }}
                        </span>
                        <span class="badge bg-info ms-1">{{ ucfirst(str_replace('_', ' ', $selectedTournament->type)) }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="filter-section">
            <form action="{{ route('schedule') }}" method="GET" class="row g-2">
                @if(request('tournament'))
                    <input type="hidden" name="tournament" value="{{ request('tournament') }}">
                @endif
                
                <div class="col-lg-3 col-md-6">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search teams..." value="{{ request('search') }}">
                </div>
                
                @if($groups->isNotEmpty())
                    <div class="col-lg-2 col-md-4">
                        <select name="group" class="form-select">
                            <option value="all">All Groups</option>
                            @foreach($groups as $group)
                                <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>
                                    Group {{ $group }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                
                <div class="col-lg-2 col-md-4">
                    <select name="status" class="form-select">
                        <option value="all">All Status</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Live</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <input type="date" name="date" class="form-control" 
                           value="{{ request('date') }}">
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter me-1"></i>Filter
                    </button>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <a href="{{ route('schedule') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Schedule Content -->
        @if($matches->count() > 0)
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-6 col-md-3 mb-3">
                    <div class="stats-card stats-total">
                        <div class="card-title">Total Matches</div>
                        <h3>{{ $totalMatches }}</h3>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="stats-card stats-completed">
                        <div class="card-title">Completed</div>
                        <h3>{{ $completedMatches }}</h3>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="stats-card stats-upcoming">
                        <div class="card-title">Upcoming</div>
                        <h3>{{ $upcomingMatches }}</h3>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="stats-card stats-ongoing">
                        <div class="card-title">Live</div>
                        <h3>{{ $ongoingMatches }}</h3>
                    </div>
                </div>
            </div>

            <!-- Matches List -->
            @foreach($groupedMatches as $date => $matchesOnDate)
                <div class="match-day">
                    <div class="date-header">
                        <div>
                            <i class="bi bi-calendar-date me-1"></i>
                            {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}
                        </div>
                        <span class="badge">{{ $matchesOnDate->count() }} match{{ $matchesOnDate->count() > 1 ? 'es' : '' }}</span>
                    </div>

                    @foreach($matchesOnDate as $match)
                        <div class="match-card">
                            <div class="match-info">
                                <!-- Time -->
                                <div class="match-time">
                                    {{ date('H:i', strtotime($match->time_start)) }}
                                    <div class="match-venue">{{ $match->venue ?? 'Main Field' }}</div>
                                </div>

                                <!-- Teams & Score -->
                                <div class="match-teams">
                                    <div class="team-name team-home">
                                        {{ $match->homeTeam->name ?? 'TBA' }}
                                    </div>

                                    <div class="score-container">
                                        @if($match->status == 'completed')
                                            <div class="score-badge">{{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}</div>
                                        @elseif($match->status == 'ongoing')
                                            <div class="score-badge live">{{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}</div>
                                        @else
                                            <div class="score-badge">VS</div>
                                        @endif
                                    </div>

                                    <div class="team-name team-away">
                                        {{ $match->awayTeam->name ?? 'TBA' }}
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="match-details">
                                    @if($match->status == 'completed')
                                        <span class="status-badge status-completed">Completed</span>
                                    @elseif($match->status == 'ongoing')
                                        <span class="status-badge status-ongoing">Live</span>
                                    @else
                                        <span class="status-badge status-upcoming">Upcoming</span>
                                    @endif

                                    @if($match->round_type)
                                        <div class="round-badge mt-1">
                                            @if($match->round_type == 'group')
                                                Group {{ $match->group_name }}
                                            @else
                                                {{ ucfirst(str_replace('_', ' ', $match->round_type)) }}
                                            @endif
                                        </div>
                                    @endif

                                    <div class="mt-2">
                                        <a href="{{ route('matches.show', $match->id) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <!-- Pagination -->
            @if($matches->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $matches->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="bi bi-calendar-x"></i>
                <h3>No Matches Found</h3>
                <p class="text-muted">
                    @if(request()->anyFilled(['search', 'group', 'status', 'date']))
                        Try adjusting your filters or
                    @endif
                    @if($selectedTournament)
                        No matches scheduled for {{ $selectedTournament->name }} yet.
                    @else
                        No matches found for the selected criteria.
                    @endif
                </p>
                <a href="{{ route('schedule') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise me-1"></i> Clear Filters
                </a>
            </div>
        @endif
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
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 4px 20px rgba(15, 23, 42, 0.15)';
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                navbar.style.boxShadow = '0 4px 20px rgba(15, 23, 42, 0.1)';
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            }
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

        // Auto-submit form when date is selected
        document.querySelector('input[name="date"]')?.addEventListener('change', function () {
            this.form.submit();
        });

        // Auto-submit form when group/status changes (except search)
        document.querySelectorAll('select[name="group"], select[name="status"]').forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            refreshLiveMatches();
            
            // Refresh live badges every 5 seconds
            setInterval(refreshLiveMatches, 5000);
        });
    </script>
</body>
</html>