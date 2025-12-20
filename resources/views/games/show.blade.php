<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Details - {{ $game->homeTeam->name ?? 'Home' }} vs {{ $game->awayTeam->name ?? 'Away' }}</title>
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
        --dark-color: #1e1e1e;
        --light-color: #f6f5f4;
    }

    body {
        background-color: #f8f9fa;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        color: #333;
        line-height: 1.6;
    }

    /* Player Cards dengan Foto */
    .player-card {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        background: white;
        border: 1px solid #e1e8ed;
        border-radius: 10px;
        margin-bottom: 0.5rem;
        transition: all 0.2s;
    }


    .player-card:hover {
        border-color: var(--primary-color);
        transform: translateX(4px);
    }

    /* .player-avatar-container {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 0.75rem;
        flex-shrink: 0;
        position: relative;
        background: linear-gradient(135deg, #1a5fb4, #2d7dd2);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    } */

    .player-avatar-container {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        margin-right: 0.75rem;
        flex-shrink: 0;
        position: relative;
        background: linear-gradient(135deg, #1a5fb4, #2d7dd2);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .player-avatar {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        /* Tambahkan border-radius ke gambar */
    }

    .player-initials {
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        text-transform: uppercase;
    }

    /* Perbaikan Player Jersey Badge */
    .player-jersey-badge {
        position: absolute;
        top: -2px;
        /* Kurangi dari -5px ke -2px */
        right: -2px;
        /* Kurangi dari -5px ke -2px */
        width: 22px;
        /* Kurangi sedikit ukuran */
        height: 22px;
        /* Kurangi sedikit ukuran */
        background: var(--danger-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        /* Kurangi ukuran font */
        font-weight: bold;
        border: 2px solid white;
        z-index: 10;
        /* Pastikan berada di atas */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .player-avatar-container::before {
        content: '';
        position: absolute;
        top: -2px;
        right: -2px;
        width: 22px;
        height: 22px;
        background: var(--danger-color);
        border-radius: 50%;
        border: 2px solid white;
        z-index: 5;
    }

    .jersey-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 24px;
        height: 24px;
        background: var(--danger-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: bold;
        border: 2px solid white;
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .player-info {
        flex: 1;
        min-width: 0;
    }

    .player-info h6 {
        margin: 0;
        font-weight: 600;
        font-size: 0.95rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .player-position {
        font-size: 0.8rem;
        color: #666;
        margin-top: 2px;
    }

    .player-stats {
        display: flex;
        gap: 0.5rem;
        margin-left: 0.5rem;
        flex-wrap: wrap;
        justify-content: flex-end;
        min-width: 100px;
    }

    .player-stats .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        min-width: 40px;
        text-align: center;
    }

    /* Hero Section */
    .match-hero {
        background: linear-gradient(135deg, var(--primary-color), #2d7dd2);
        color: white;
        padding: 2.5rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 20px 20px;
        position: relative;
        overflow: hidden;
    }

    .match-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23000000' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.1;
    }

    .match-title {
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .match-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    /* Cards */
    .card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        background: white;
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #e1e8ed;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        color: #333;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
    }

    .card-header i {
        margin-right: 10px;
        color: var(--primary-color);
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Match Score Card */
    .score-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .team-logo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .team-name {
        font-weight: 700;
        font-size: 1.4rem;
        margin-top: 1rem;
        color: #333;
    }

    .team-city {
        font-size: 0.9rem;
        color: #666;
        margin-top: 0.25rem;
    }

    .score-display {
        font-size: 4rem;
        font-weight: 800;
        color: var(--primary-color);
        line-height: 1;
    }

    .score-display.live {
        color: var(--danger-color);
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }

        100% {
            opacity: 1;
        }
    }

    .match-status {
        font-size: 1rem;
        font-weight: 600;
        margin-top: 0.5rem;
        display: block;
    }

    .status-badge {
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Match Info */
    .match-info-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .match-info-item:last-child {
        border-bottom: none;
    }

    .match-info-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f0f7ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        margin-right: 1rem;
    }

    .match-info-content h6 {
        margin: 0;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .match-info-content p {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
    }

    /* Timeline */
    .timeline-container {
        position: relative;
        padding-left: 40px;
    }

    .timeline-container::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #e9ecef, var(--primary-color), #e9ecef);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
    }

    .timeline-marker {
        position: absolute;
        left: -40px;
        top: 0;
        z-index: 2;
        background: white;
    }

    .timeline-marker .badge {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .timeline-content {
        flex: 1;
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 10px;
        border-left: 4px solid #6c757d;
    }

    .timeline-item.home .timeline-content {
        border-left-color: var(--primary-color);
    }

    .timeline-item.away .timeline-content {
        border-left-color: var(--danger-color);
    }

    .timeline-minute {
        font-weight: 700;
        color: #333;
        font-size: 0.9rem;
    }

    /* Statistics */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #666;
        margin-top: 0.5rem;
    }

    /* Player Cards */
    .player-card {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        background: white;
        border: 1px solid #e1e8ed;
        border-radius: 10px;
        margin-bottom: 0.5rem;
        transition: all 0.2s;
        position: relative;
        /* Tambahkan ini */
    }

    .player-card:hover {
        border-color: var(--primary-color);
        transform: translateX(4px);
    }

    .player-jersey {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .player-info {
        flex: 1;
        min-width: 0;
    }

    .player-info h6 {
        margin: 0;
        font-weight: 600;
        font-size: 0.95rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .player-position {
        font-size: 0.8rem;
        color: #666;
    }

    .player-stats {
        display: flex;
        gap: 0.5rem;
        margin-left: 0.5rem;
    }

    .player-stats .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    /* Back Button */
    .back-button {
        position: absolute;
        top: 2rem;
        left: 2rem;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        color: var(--primary-color);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .back-button:hover {
        background: white;
        color: var(--primary-color);
        transform: translateX(-2px);
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .match-hero {
            padding: 2rem 0;
        }

        .match-title {
            font-size: 1.6rem;
        }

        .score-display {
            font-size: 3rem;
        }

        .team-logo {
            width: 80px;
            height: 80px;
        }

        .team-name {
            font-size: 1.2rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .back-button {
            top: 1rem;
            left: 1rem;
        }
    }

    @media (max-width: 576px) {
        .timeline-container {
            padding-left: 30px;
        }

        .timeline-container::before {
            left: 15px;
        }

        .timeline-marker {
            left: -30px;
        }

        .timeline-marker .badge {
            width: 30px;
            height: 30px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Utility Classes */
    .text-muted {
        color: #666 !important;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    /* Spacing */
    .section-title {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e1e8ed;
    }

    /* Empty States */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #ccc;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #666;
        margin: 0;
    }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <div class="match-hero">
        <a href="{{ route('schedule') }}" class="back-button">
            <i class="bi bi-arrow-left"></i> Back to Schedule
        </a>

        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-10">
                    <h1 class="match-title">
                        <i class="bi bi-trophy me-2"></i>Match Details
                    </h1>
                    <p class="match-subtitle">
                        {{ $game->homeTeam->name ?? 'Home Team' }} vs {{ $game->awayTeam->name ?? 'Away Team' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container main-container">
        <!-- Score Card -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="score-card">
                    <div class="row align-items-center">
                        <!-- Home Team -->
                        <div class="col-md-5 text-center">
                            <div class="mb-4">
                                @if($game->homeTeam->logo)
                                <img src="{{ asset('storage/' . $game->homeTeam->logo) }}"
                                    alt="{{ $game->homeTeam->name }}" class="team-logo">
                                @else
                                <div class="team-logo bg-light d-inline-flex align-items-center justify-content-center">
                                    <i class="bi bi-people fs-1 text-muted"></i>
                                </div>
                                @endif
                                <div class="team-name">{{ $game->homeTeam->name ?? 'Home Team' }}</div>
                                <div class="team-city">{{ $game->homeTeam->city ?? '' }}</div>
                            </div>
                        </div>

                        <!-- Score -->
                        <div class="col-md-2 text-center">
                            @if($game->status == 'completed')
                            <div class="score-display">{{ $game->home_score ?? 0 }}</div>
                            <div class="score-display">:</div>
                            <div class="score-display">{{ $game->away_score ?? 0 }}</div>
                            <span class="match-status badge bg-success status-badge">
                                <i class="bi bi-check-circle me-1"></i> FULL TIME
                            </span>
                            @elseif($game->status == 'ongoing')
                            <div class="score-display live">{{ $game->home_score ?? 0 }}</div>
                            <div class="score-display live">:</div>
                            <div class="score-display live">{{ $game->away_score ?? 0 }}</div>
                            <span class="match-status badge bg-danger status-badge">
                                <i class="bi bi-play-circle me-1"></i> LIVE
                            </span>
                            @else
                            <div class="score-display">VS</div>
                            <span class="match-status badge bg-secondary status-badge">
                                <i class="bi bi-clock me-1"></i> UPCOMING
                            </span>
                            @endif
                        </div>

                        <!-- Away Team -->
                        <div class="col-md-5 text-center">
                            <div class="mb-4">
                                @if($game->awayTeam->logo)
                                <img src="{{ asset('storage/' . $game->awayTeam->logo) }}"
                                    alt="{{ $game->awayTeam->name }}" class="team-logo">
                                @else
                                <div class="team-logo bg-light d-inline-flex align-items-center justify-content-center">
                                    <i class="bi bi-people fs-1 text-muted"></i>
                                </div>
                                @endif
                                <div class="team-name">{{ $game->awayTeam->name ?? 'Away Team' }}</div>
                                <div class="team-city">{{ $game->awayTeam->city ?? '' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tournament Info -->
                    @if($game->tournament)
                    <div class="row mt-4 pt-4 border-top">
                        <div class="col-12 text-center">
                            <span class="badge bg-info px-3 py-2">
                                <i class="bi bi-trophy me-1"></i>
                                {{ $game->tournament->name }}
                                @if($game->group_name)
                                • Group {{ $game->group_name }}
                                @endif
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Match Information -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> Match Information
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="match-info-item">
                                    <div class="match-info-icon">
                                        <i class="bi bi-calendar-event"></i>
                                    </div>
                                    <div class="match-info-content">
                                        <h6>Match Date</h6>
                                        <p>{{ $game->match_date->format('l, d F Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="match-info-item">
                                    <div class="match-info-icon">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div class="match-info-content">
                                        <h6>Time</h6>
                                        <p>{{ date('H:i', strtotime($game->time_start)) }} -
                                            {{ date('H:i', strtotime($game->time_end)) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="match-info-item">
                                    <div class="match-info-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div class="match-info-content">
                                        <h6>Venue</h6>
                                        <p>{{ $game->venue ?? 'Main Field' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="match-info-item">
                                    <div class="match-info-icon">
                                        <i class="bi bi-diagram-3"></i>
                                    </div>
                                    <div class="match-info-content">
                                        <h6>Stage</h6>
                                        <p>
                                            @if($game->round_type == 'group')
                                            Group Stage • Group {{ $game->group_name }}
                                            @else
                                            {{ ucfirst(str_replace('_', ' ', $game->round_type)) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Match Notes -->
                        @if($game->notes)
                        <div class="mt-4 pt-4 border-top">
                            <h6 class="section-title">Match Notes</h6>
                            <p class="mb-0">{{ $game->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Match Statistics -->
                @if($game->status == 'completed' || $game->status == 'ongoing')
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bar-chart"></i> Match Statistics
                    </div>
                    <div class="card-body">
                        <div class="stats-grid">
                            <!-- Goals -->
                            <div class="stat-item">
                                <div class="stat-value text-primary">{{ $homeTeamStats['goals'] ?? 0 }}</div>
                                <div class="stat-label">Goals</div>
                                <div class="stat-value text-primary">{{ $awayTeamStats['goals'] ?? 0 }}</div>
                            </div>
                            <!-- Yellow Cards -->
                            <div class="stat-item">
                                <div class="stat-value text-warning">{{ $homeTeamStats['yellow_cards'] ?? 0 }}</div>
                                <div class="stat-label">Yellow Cards</div>
                                <div class="stat-value text-warning">{{ $awayTeamStats['yellow_cards'] ?? 0 }}</div>
                            </div>
                            <!-- Red Cards -->
                            <div class="stat-item">
                                <div class="stat-value text-danger">{{ $homeTeamStats['red_cards'] ?? 0 }}</div>
                                <div class="stat-label">Red Cards</div>
                                <div class="stat-value text-danger">{{ $awayTeamStats['red_cards'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Match Timeline -->
                @if($game->events->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-clock-history"></i> Match Timeline
                    </div>
                    <div class="card-body">
                        <div class="timeline-container">
                            @foreach($game->events->sortBy('minute') as $event)
                            <div class="timeline-item {{ $event->team_id == $game->team_home_id ? 'home' : 'away' }}">
                                <div class="timeline-marker">
                                    @switch($event->event_type)
                                    @case('goal')
                                    <span class="badge bg-success">
                                        <i class="bi bi-soccer"></i>
                                    </span>
                                    @break
                                    @case('yellow_card')
                                    <span class="badge bg-warning">
                                        <i class="bi bi-card-text"></i>
                                    </span>
                                    @break
                                    @case('red_card')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-card-text"></i>
                                    </span>
                                    @break
                                    @case('substitution')
                                    <span class="badge bg-info">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </span>
                                    @break
                                    @default
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-circle-fill"></i>
                                    </span>
                                    @endswitch
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $event->player->name ?? 'Unknown Player' }}</strong>
                                            <small class="text-muted ms-2">
                                                {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                                                @if($event->event_type == 'goal' && $event->is_penalty)
                                                <span class="text-muted">(Penalty)</span>
                                                @endif
                                                @if($event->event_type == 'goal' && $event->is_own_goal)
                                                <span class="text-danger">(Own Goal)</span>
                                                @endif
                                            </small>
                                            @if($event->description)
                                            <p class="mb-0 mt-1 small">{{ $event->description }}</p>
                                            @endif
                                            @if($event->related_player_id)
                                            <small class="text-muted">
                                                Assisted by: {{ $event->relatedPlayer->name ?? 'Unknown' }}
                                            </small>
                                            @endif
                                        </div>
                                        <div class="timeline-minute">
                                            {{ $event->minute }}'
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-clock-history"></i> Match Timeline
                    </div>
                    <div class="card-body">
                        <div class="empty-state">
                            <i class="bi bi-activity"></i>
                            <p>No match events recorded yet.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Home Team Players -->
                <!-- Home Team Players -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-people"></i> {{ $game->homeTeam->name ?? 'Home Team' }} Squad
                        <span class="badge bg-primary ms-2">{{ $game->homeTeam->players->count() }} players</span>
                    </div>
                    <div class="card-body">
                        @if($game->homeTeam->players->count() > 0)
                        @foreach($game->homeTeam->players->sortBy('jersey_number') as $player)
                        <div class="player-card">
                            <!-- Player Avatar -->
                            <div class="player-avatar-container">
                                @if($player->photo_url)
                                <img src="{{ $player->photo_url }}" alt="{{ $player->name }}" class="player-avatar"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                @endif
                                <div class="player-initials"
                                    style="{{ $player->photo_url ? 'display: none;' : 'display: flex;' }}">
                                    {{ $player->initial }}
                                </div>

                                <!-- Jersey Number Badge -->
                                @if($player->jersey_number)
                                <div class="player-jersey-badge">
                                    {{ $player->jersey_number }}
                                </div>
                                @endif
                            </div>

                            <!-- Player Info -->
                            <div class="player-info">
                                <h6>{{ $player->name }}</h6>
                                <div class="player-position">
                                    {{ $player->position ?? 'Player' }}
                                    @if($player->position)
                                    <span class="text-muted ms-2">•</span>
                                    @endif
                                    <small class="text-muted">
                                        @if($player->goals > 0)
                                        {{ $player->goals }}G
                                        @endif
                                        @if($player->assists > 0)
                                        {{ $player->assists > 0 && $player->goals > 0 ? '/' : '' }}{{ $player->assists }}A
                                        @endif
                                    </small>
                                </div>
                            </div>

                            <!-- Player Stats Badges -->
                            <div class="player-stats">
                                @if($player->goals > 0)
                                <span class="badge bg-success" title="Goals">
                                    <i class="bi bi-soccer me-1"></i>{{ $player->goals }}
                                </span>
                                @endif
                                @if($player->assists > 0)
                                <span class="badge bg-primary" title="Assists">
                                    <i class="bi bi-share me-1"></i>{{ $player->assists }}
                                </span>
                                @endif
                                @if($player->yellow_cards > 0)
                                <span class="badge bg-warning" title="Yellow Cards">
                                    <i class="bi bi-card-text me-1"></i>{{ $player->yellow_cards }}
                                </span>
                                @endif
                                @if($player->red_cards > 0)
                                <span class="badge bg-danger" title="Red Cards">
                                    <i class="bi bi-card-text me-1"></i>{{ $player->red_cards }}
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="empty-state">
                            <i class="bi bi-person-x"></i>
                            <p>No players available for this team</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Away Team Players -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-people"></i> {{ $game->awayTeam->name ?? 'Away Team' }} Squad
                        <span class="badge bg-primary ms-2">{{ $game->awayTeam->players->count() }} players</span>
                    </div>
                    <div class="card-body">
                        @if($game->awayTeam->players->count() > 0)
                        @foreach($game->awayTeam->players->sortBy('jersey_number') as $player)
                        <div class="player-card">
                            <!-- Player Avatar -->
                            <div class="player-avatar-container">
                                @if($player->photo_url)
                                <img src="{{ $player->photo_url }}" alt="{{ $player->name }}" class="player-avatar"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                @endif
                                <div class="player-initials"
                                    style="{{ $player->photo_url ? 'display: none;' : 'display: flex;' }}">
                                    {{ $player->initial }}
                                </div>

                                <!-- Jersey Number Badge -->
                                @if($player->jersey_number)
                                <div class="player-jersey-badge">
                                    {{ $player->jersey_number }}
                                </div>
                                @endif
                            </div>

                            <!-- Player Info -->
                            <div class="player-info">
                                <h6>{{ $player->name }}</h6>
                                <div class="player-position">
                                    {{ $player->position ?? 'Player' }}
                                    @if($player->position)
                                    <span class="text-muted ms-2">•</span>
                                    @endif
                                    <small class="text-muted">
                                        @if($player->goals > 0)
                                        {{ $player->goals }}G
                                        @endif
                                        @if($player->assists > 0)
                                        {{ $player->assists > 0 && $player->goals > 0 ? '/' : '' }}{{ $player->assists }}A
                                        @endif
                                    </small>
                                </div>
                            </div>

                            <!-- Player Stats Badges -->
                            <div class="player-stats">
                                @if($player->goals > 0)
                                <span class="badge bg-success" title="Goals">
                                    <i class="bi bi-soccer me-1"></i>{{ $player->goals }}
                                </span>
                                @endif
                                @if($player->assists > 0)
                                <span class="badge bg-primary" title="Assists">
                                    <i class="bi bi-share me-1"></i>{{ $player->assists }}
                                </span>
                                @endif
                                @if($player->yellow_cards > 0)
                                <span class="badge bg-warning" title="Yellow Cards">
                                    <i class="bi bi-card-text me-1"></i>{{ $player->yellow_cards }}
                                </span>
                                @endif
                                @if($player->red_cards > 0)
                                <span class="badge bg-danger" title="Red Cards">
                                    <i class="bi bi-card-text me-1"></i>{{ $player->red_cards }}
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="empty-state">
                            <i class="bi bi-person-x"></i>
                            <p>No players available for this team</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tournament Info -->
                @if($game->tournament)
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-trophy"></i> Tournament Information
                    </div>
                    <div class="card-body">
                        <h6 class="mb-3">{{ $game->tournament->name }}</h6>
                        <p class="small text-muted mb-3">
                            {{ $game->tournament->description ?? 'No description available' }}</p>
                        <div class="match-info-item">
                            <div class="match-info-icon">
                                <i class="bi bi-calendar"></i>
                            </div>
                            <div class="match-info-content">
                                <h6>Tournament Dates</h6>
                                <p>{{ $game->tournament->start_date->format('d M Y') }} -
                                    {{ $game->tournament->end_date->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="match-info-item">
                            <div class="match-info-icon">
                                <i class="bi bi-flag"></i>
                            </div>
                            <div class="match-info-content">
                                <h6>Status</h6>
                                <p>
                                    <span
                                        class="badge bg-{{ $game->tournament->status == 'ongoing' ? 'success' : ($game->tournament->status == 'upcoming' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($game->tournament->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Auto refresh for live matches
    function refreshLiveMatch() {
        const matchStatus = "{{ $game->status }}";

        if (matchStatus === 'ongoing') {
            setTimeout(() => {
                location.reload();
            }, 30000); // Refresh every 30 seconds for live matches
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        refreshLiveMatch();

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
    </script>
</body>

</html>