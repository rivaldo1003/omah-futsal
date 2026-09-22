<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Details - {{ $game->homeTeam->name ?? 'Home' }} vs {{ $game->awayTeam->name ?? 'Away' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
    /* ==========================================================================
       ULTRA ANALYTICS ENGINE v3.0 — MATCH DETAILS
       Adheres to ultra_analytics_engine_v3_0_design_system.md
       ========================================================================== */
    :root {
        --v3-bg: #020408;
        --v3-surface: #070c14;
        --v3-card: #0d1524;
        --v3-border: #18263e;
        --v3-border-glow: rgba(0, 255, 135, 0.4);
        --v3-neon-green: #00ff87;
        --v3-neon-blue: #00e5ff;
        --v3-neon-pink: #ff0055;
        --v3-neon-yellow: #ffb700;
        --v3-text-main: #ffffff;
        --v3-text-sub: #8da1b9;
        --v3-text-muted: #4e6178;
        --v3-radius-lg: 20px;
        --v3-radius-md: 12px;
        --v3-radius-sm: 8px;
    }

    body {
        background-color: var(--v3-bg);
        background-image:
            radial-gradient(ellipse 80% 50% at 50% -10%, rgba(0, 255, 135, 0.06), transparent),
            radial-gradient(ellipse 60% 40% at 90% 110%, rgba(0, 229, 255, 0.04), transparent);
        background-attachment: fixed;
        font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        color: var(--v3-text-main);
        line-height: 1.6;
    }

    ::selection { background: rgba(0, 255, 135, 0.25); color: #fff; }

    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: var(--v3-bg); }
    ::-webkit-scrollbar-thumb { background: var(--v3-border); border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--v3-neon-green); }

    /* ===== Telemetry HUD ===== */
    .v3-hud {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 24px;
        border-bottom: 1px solid var(--v3-border);
        font-size: 11px;
        font-weight: 800;
        color: var(--v3-text-muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        background: var(--v3-surface);
    }
    .v3-hud-left, .v3-hud-right { display: flex; align-items: center; gap: 10px; }
    .v3-hud-status { color: var(--v3-neon-green); }
    .v3-beacon {
        width: 8px; height: 8px;
        background: var(--v3-neon-green);
        border-radius: 50%;
        box-shadow: 0 0 12px var(--v3-neon-green);
        animation: v3BeaconPulse 1.2s infinite alternate;
    }
    @keyframes v3BeaconPulse {
        from { opacity: 0.3; transform: scale(0.8); }
        to { opacity: 1; transform: scale(1.2); }
    }

    /* ===== Hero ===== */
    .match-hero {
        background: linear-gradient(135deg, rgba(7, 12, 20, 0.98), rgba(2, 4, 8, 1));
        border-bottom: 1px solid var(--v3-border);
        color: var(--v3-text-main);
        padding: 3rem 0 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .match-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 60% 80% at 50% 120%, rgba(0, 255, 135, 0.12), transparent);
        pointer-events: none;
    }
    .match-title {
        font-weight: 900;
        font-style: italic;
        text-transform: uppercase;
        letter-spacing: -0.03em;
        font-size: 2.5rem;
        text-shadow: 0 0 20px rgba(0, 255, 135, 0.35);
    }
    .match-subtitle { font-size: 1.1rem; color: var(--v3-text-sub); }

    .back-button {
        position: absolute;
        top: 1.5rem;
        left: 1.5rem;
        background: rgba(13, 21, 36, 0.9);
        border: 1px solid var(--v3-border);
        border-radius: var(--v3-radius-sm);
        padding: 0.5rem 1rem;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--v3-neon-green);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        z-index: 5;
    }
    .back-button:hover {
        border-color: var(--v3-border-glow);
        box-shadow: 0 0 16px rgba(0, 255, 135, 0.2);
        color: var(--v3-neon-green);
        transform: translateX(-2px);
        text-decoration: none;
    }

    /* ===== Cards ===== */
    .card {
        border: 1px solid var(--v3-border);
        border-radius: var(--v3-radius-md);
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.45);
        background: var(--v3-surface);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        border-color: var(--v3-border-glow);
        box-shadow: 0 0 24px rgba(0, 255, 135, 0.1);
    }
    .card-header {
        background: var(--v3-card);
        border-bottom: 1px solid var(--v3-border);
        padding: 1rem 1.5rem;
        font-weight: 800;
        font-style: italic;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--v3-text-main);
        font-size: 0.95rem;
        display: flex;
        align-items: center;
    }
    .card-header i { margin-right: 10px; color: var(--v3-neon-green); }
    .card-body { padding: 1.5rem; }

    /* ===== Score Card ===== */
    .score-card {
        background: var(--v3-surface);
        border: 1px solid var(--v3-border);
        border-radius: var(--v3-radius-lg);
        padding: 2rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
    }
    .team-logo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--v3-border-glow);
        box-shadow: 0 0 24px rgba(0, 255, 135, 0.15);
        background: var(--v3-card);
    }
    .team-name {
        font-weight: 900;
        font-style: italic;
        text-transform: uppercase;
        font-size: 1.3rem;
        margin-top: 1rem;
        color: var(--v3-text-main);
    }
    .team-city { font-size: 0.9rem; color: var(--v3-text-sub); margin-top: 0.25rem; }

    .score-display {
        font-size: 4rem;
        font-weight: 900;
        font-style: italic;
        color: var(--v3-neon-green);
        text-shadow: 0 0 24px rgba(0, 255, 135, 0.4);
        line-height: 1;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
    }
    .score-display.live {
        color: var(--v3-neon-pink);
        text-shadow: 0 0 24px rgba(255, 0, 85, 0.5);
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    .match-status { font-size: 1rem; font-weight: 800; margin-top: 0.5rem; display: block; }
    .status-badge {
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        font-weight: 800;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .status-badge.bg-success {
        background: rgba(0, 255, 135, 0.12) !important;
        color: var(--v3-neon-green) !important;
        border: 1px solid var(--v3-border-glow);
    }
    .status-badge.bg-danger {
        background: rgba(255, 0, 85, 0.12) !important;
        color: var(--v3-neon-pink) !important;
        border: 1px solid rgba(255, 0, 85, 0.4);
    }
    .status-badge.bg-secondary {
        background: rgba(77, 97, 120, 0.15) !important;
        color: var(--v3-text-sub) !important;
        border: 1px solid var(--v3-border);
    }
    .badge.bg-info {
        background: rgba(0, 229, 255, 0.1) !important;
        color: var(--v3-neon-blue) !important;
        border: 1px solid rgba(0, 229, 255, 0.35);
    }

    /* ===== Match Info ===== */
    .match-info-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--v3-border);
    }
    .match-info-item:last-child { border-bottom: none; }
    .match-info-icon {
        width: 40px; height: 40px;
        border-radius: var(--v3-radius-sm);
        background: rgba(0, 255, 135, 0.08);
        border: 1px solid var(--v3-border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--v3-neon-green);
        margin-right: 1rem;
        flex-shrink: 0;
    }
    .match-info-content h6 {
        margin: 0;
        font-weight: 800;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--v3-text-muted);
    }
    .match-info-content p { margin: 0; font-size: 0.95rem; color: var(--v3-text-main); }

    /* ===== Timeline ===== */
    .timeline-container { position: relative; padding-left: 40px; }
    .timeline-container::before {
        content: '';
        position: absolute;
        left: 20px; top: 0; bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--v3-border), var(--v3-neon-green), var(--v3-border));
    }
    .timeline-item { position: relative; margin-bottom: 1.5rem; display: flex; align-items: flex-start; }
    .timeline-marker { position: absolute; left: -40px; top: 0; z-index: 2; background: var(--v3-bg); }
    .timeline-marker .badge {
        width: 40px; height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--v3-border);
        box-shadow: 0 0 12px rgba(0, 255, 135, 0.15);
    }
    .timeline-content {
        flex: 1;
        background: var(--v3-card);
        padding: 1rem;
        border-radius: var(--v3-radius-sm);
        border-left: 3px solid var(--v3-text-muted);
        color: var(--v3-text-main);
    }
    .timeline-item.home .timeline-content { border-left-color: var(--v3-neon-green); }
    .timeline-item.away .timeline-content { border-left-color: var(--v3-neon-pink); }
    .timeline-minute {
        font-weight: 900;
        font-style: italic;
        color: var(--v3-neon-green);
        font-size: 1rem;
        font-family: 'JetBrains Mono', monospace;
    }

    /* ===== Statistics ===== */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
    .stat-item {
        text-align: center;
        padding: 1rem;
        background: var(--v3-card);
        border: 1px solid var(--v3-border);
        border-radius: var(--v3-radius-sm);
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 900;
        font-style: italic;
        line-height: 1;
        font-family: 'JetBrains Mono', monospace;
    }
    .stat-label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--v3-text-muted);
        margin-top: 0.5rem;
    }
    .stat-value.text-primary { color: var(--v3-neon-green) !important; }
    .stat-value.text-warning { color: var(--v3-neon-yellow) !important; }
    .stat-value.text-danger { color: var(--v3-neon-pink) !important; }

    /* ===== Player Cards ===== */
    .player-card {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        background: var(--v3-card);
        border: 1px solid var(--v3-border);
        border-radius: var(--v3-radius-sm);
        margin-bottom: 0.5rem;
        transition: all 0.2s;
        position: relative;
    }
    .player-card:hover {
        border-color: var(--v3-border-glow);
        box-shadow: 0 0 16px rgba(0, 255, 135, 0.12);
        transform: translateX(4px);
    }
    .player-avatar-container {
        width: 48px; height: 48px;
        border-radius: 50%;
        margin-right: 0.75rem;
        flex-shrink: 0;
        position: relative;
        background: linear-gradient(135deg, var(--v3-card), var(--v3-border));
        border: 1px solid var(--v3-border);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: visible;
    }
    .player-avatar { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .player-initials {
        color: var(--v3-neon-green);
        font-weight: 900;
        font-size: 1.1rem;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%; height: 100%;
    }
    .player-jersey-badge {
        position: absolute;
        top: -4px; right: -4px;
        min-width: 20px; height: 20px;
        padding: 0 4px;
        background: var(--v3-neon-pink);
        color: #fff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 900;
        border: 2px solid var(--v3-bg);
        z-index: 10;
        font-family: 'JetBrains Mono', monospace;
    }
    .player-info { flex: 1; min-width: 0; }
    .player-info h6 {
        margin: 0;
        font-weight: 700;
        font-size: 0.95rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--v3-text-main);
    }
    .player-position { font-size: 0.8rem; color: var(--v3-text-sub); margin-top: 2px; }
    .player-stats {
        display: flex;
        gap: 0.4rem;
        margin-left: 0.5rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    .player-stats .badge {
        font-size: 0.72rem;
        padding: 0.25rem 0.5rem;
        min-width: 36px;
        text-align: center;
        font-weight: 800;
        font-family: 'JetBrains Mono', monospace;
    }
    .player-stats .badge.bg-success {
        background: rgba(0, 255, 135, 0.12) !important;
        color: var(--v3-neon-green) !important;
        border: 1px solid var(--v3-border-glow);
    }
    .player-stats .badge.bg-primary {
        background: rgba(0, 229, 255, 0.1) !important;
        color: var(--v3-neon-blue) !important;
        border: 1px solid rgba(0, 229, 255, 0.35);
    }
    .player-stats .badge.bg-warning {
        background: rgba(255, 183, 0, 0.1) !important;
        color: var(--v3-neon-yellow) !important;
        border: 1px solid rgba(255, 183, 0, 0.35);
    }
    .player-stats .badge.bg-danger {
        background: rgba(255, 0, 85, 0.1) !important;
        color: var(--v3-neon-pink) !important;
        border: 1px solid rgba(255, 0, 85, 0.35);
    }
    .card-header .badge.bg-primary {
        background: rgba(0, 229, 255, 0.1) !important;
        color: var(--v3-neon-blue) !important;
        border: 1px solid rgba(0, 229, 255, 0.35);
    }

    /* ===== Section Title & Empty State ===== */
    .section-title {
        color: var(--v3-neon-green);
        font-weight: 800;
        font-style: italic;
        text-transform: uppercase;
        font-size: 1rem;
        letter-spacing: 0.5px;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--v3-border);
    }
    .empty-state { text-align: center; padding: 3rem 1rem; }
    .empty-state i { font-size: 3rem; color: var(--v3-text-muted); margin-bottom: 1rem; }
    .empty-state p { color: var(--v3-text-sub); margin: 0; }

    .text-muted { color: var(--v3-text-muted) !important; }
    .bg-light { background-color: var(--v3-card) !important; }
    .border-top { border-color: var(--v3-border) !important; }
    .border-bottom { border-color: var(--v3-border) !important; }

    /* ===== Responsive ===== */
    @media (max-width: 900px) {
        .match-title { font-size: 1.8rem; }
        .score-display { font-size: 3rem; }
        .team-logo { width: 80px; height: 80px; }
        .team-name { font-size: 1.1rem; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .back-button { top: 1rem; left: 1rem; }
    }
    @media (max-width: 576px) {
        .timeline-container { padding-left: 30px; }
        .timeline-container::before { left: 15px; }
        .timeline-marker { left: -30px; }
        .timeline-marker .badge { width: 30px; height: 30px; }
        .stats-grid { grid-template-columns: 1fr; }
        .match-title { font-size: 1.6rem; }
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
                                        {{-- {{ $player->goals }}G --}}
                                        @endif
                                        @if($player->assists > 0)
                                        {{ $player->assists > 0 && $player->goals > 0 ? '/' : '' }}{{ $player->assists }}A
                                        @endif
                                    </small>
                                </div>
                            </div>

                            <!-- Player Stats Badges -->
<div class="player-stats">
    @if($player->tournament_goals > 0)
    <span class="badge bg-success" title="Goals in this tournament">
        <i class="bi bi-soccer me-1"></i>{{ $player->tournament_goals }}
    </span>
    @endif
    @if($player->tournament_assists > 0)
    <span class="badge bg-primary" title="Assists in this tournament">
        <i class="bi bi-share me-1"></i>{{ $player->tournament_assists }}
    </span>
    @endif
    @if($player->tournament_yellow_cards > 0)
    <span class="badge bg-warning" title="Yellow Cards in this tournament">
        <i class="bi bi-card-text me-1"></i>{{ $player->tournament_yellow_cards }}
    </span>
    @endif
    @if($player->tournament_red_cards > 0)
    <span class="badge bg-danger" title="Red Cards in this tournament">
        <i class="bi bi-card-text me-1"></i>{{ $player->tournament_red_cards }}
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