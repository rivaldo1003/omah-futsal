@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
    <style>
        .admin-dashboard {
            --dashboard-ink: #111111;
            --dashboard-charcoal: #39393b;
            --dashboard-muted: #707072;
            --dashboard-line: #cacacb;
            --dashboard-line-soft: #e5e5e5;
            --dashboard-cloud: #f5f5f5;
            --dashboard-canvas: #ffffff;
            --dashboard-success: #007d48;
            --dashboard-warning: #8a5200;
            max-width: 1440px;
            margin: 0 auto;
            color: var(--dashboard-ink);
            font-size: 16px;
            line-height: 1.5;
        }

        .admin-dashboard .dashboard-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px 24px;
            margin-bottom: 32px;
        }

        .admin-dashboard .dashboard-header h1 {
            margin: 0 0 8px;
            color: var(--dashboard-ink);
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
        }

        .admin-dashboard .dashboard-header p {
            max-width: 68ch;
            margin: 0;
            color: var(--dashboard-muted);
            font-size: 15px;
            line-height: 1.5;
        }

        .admin-dashboard .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 40px;
            overflow: hidden;
            border: 1px solid var(--dashboard-line-soft);
            border-radius: 8px;
            background: var(--dashboard-canvas);
        }

        .admin-dashboard .stat-card {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            min-width: 0;
            padding: 18px 20px;
            border-right: 1px solid var(--dashboard-line-soft);
        }

        .admin-dashboard .stat-card:last-child {
            border-right: 0;
        }

        .admin-dashboard .stat-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 20px;
            width: 20px;
            height: 24px;
            color: var(--dashboard-muted);
            font-size: 16px;
        }

        .admin-dashboard .stat-info {
            display: grid;
            gap: 4px;
            min-width: 0;
        }

        .admin-dashboard .stat-title {
            color: var(--dashboard-muted);
            font-size: 14px;
            line-height: 1.35;
        }

        .admin-dashboard .stat-value {
            color: var(--dashboard-ink);
            font-size: 28px;
            font-variant-numeric: tabular-nums;
            font-weight: 500;
            line-height: 1.15;
        }

        .admin-dashboard .dashboard-columns {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .admin-dashboard .section-card {
            min-width: 0;
            margin: 0;
            padding: 0 20px 20px;
            border: 1px solid var(--dashboard-line-soft);
            border-radius: 8px;
            background: var(--dashboard-canvas);
        }

        .admin-dashboard .card-header {
            padding: 16px 0 12px;
            border-bottom: 1px solid var(--dashboard-line-soft);
            background: transparent;
        }

        .admin-dashboard .card-header h2 {
            margin: 0;
            color: var(--dashboard-ink);
            font-size: 22px;
            font-weight: 500;
            line-height: 1.25;
        }

        .admin-dashboard .card-body {
            padding: 0;
        }

        .admin-dashboard .match-item,
        .admin-dashboard .activity-item {
            padding: 16px 0;
            border-bottom: 1px solid var(--dashboard-line-soft);
        }

        .admin-dashboard .match-item:last-of-type,
        .admin-dashboard .activity-item:last-child {
            border-bottom: 0;
        }

        .admin-dashboard .match-teams {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
            align-items: center;
            gap: 12px;
            margin-bottom: 9px;
        }

        .admin-dashboard .team-info {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
        }

        .admin-dashboard .team-info:last-child {
            justify-content: flex-end;
        }

        .admin-dashboard .team-logo-small,
        .admin-dashboard .activity-logo {
            display: grid;
            flex: 0 0 32px;
            width: 32px;
            height: 32px;
            overflow: hidden;
            place-items: center;
            background: var(--dashboard-cloud);
        }

        .admin-dashboard .team-logo-small img,
        .admin-dashboard .activity-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-dashboard .logo-initial {
            display: grid;
            width: 100%;
            height: 100%;
            place-items: center;
            background: var(--dashboard-cloud);
            color: var(--dashboard-charcoal);
            font-size: 12px;
            font-weight: 500;
        }

        .admin-dashboard .team-name {
            max-width: 180px;
            overflow: hidden;
            color: var(--dashboard-ink);
            font-size: 14px;
            font-weight: 500;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-dashboard .team-name.home {
            text-align: right;
        }

        .admin-dashboard .match-score {
            min-width: 56px;
            color: var(--dashboard-ink);
            font-size: 15px;
            font-variant-numeric: tabular-nums;
            font-weight: 600;
            text-align: center;
        }

        .admin-dashboard .match-score--pending {
            color: var(--dashboard-muted);
            font-weight: 500;
        }

        .admin-dashboard .match-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 8px 16px;
        }

        .admin-dashboard .match-date,
        .admin-dashboard .activity-info {
            color: var(--dashboard-muted);
            font-size: 13px;
            line-height: 1.4;
        }

        .admin-dashboard .status-badge {
            padding: 0;
            color: var(--dashboard-muted);
            font-size: 13px;
            font-weight: 500;
            line-height: 1.4;
        }

        .admin-dashboard .status-ongoing {
            color: var(--dashboard-warning);
        }

        .admin-dashboard .status-completed {
            color: var(--dashboard-success);
        }

        .admin-dashboard .activity-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .admin-dashboard .activity-content {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .admin-dashboard .activity-details {
            min-width: 0;
        }

        .admin-dashboard .activity-name {
            overflow: hidden;
            color: var(--dashboard-ink);
            font-size: 14px;
            font-weight: 500;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-dashboard .view-all {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 44px;
            margin-top: 8px;
            padding: 8px 16px;
            border-radius: 9999px;
            background: var(--dashboard-cloud);
            color: var(--dashboard-ink);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 150ms ease-out;
        }

        .admin-dashboard .view-all:hover,
        .admin-dashboard .dashboard-action--secondary:hover {
            background: #e5e5e5;
            color: var(--dashboard-ink);
        }

        .admin-dashboard .empty-state {
            padding: 18px 0 22px;
            text-align: left;
        }

        .admin-dashboard .empty-state-icon {
            display: none;
        }

        .admin-dashboard .empty-state-text {
            margin: 0 0 14px;
            color: var(--dashboard-muted);
            font-size: 15px;
        }

        .admin-dashboard .dashboard-actions-section {
            margin-bottom: 24px;
        }

        .admin-dashboard .dashboard-actions-section .card-body {
            padding-top: 16px;
        }

        .admin-dashboard .dashboard-action-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
        }

        .admin-dashboard .dashboard-activity-actions {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin-top: 16px;
        }

        .admin-dashboard .dashboard-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 48px;
            padding: 10px 16px;
            border: 1px solid transparent;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            text-align: center;
            text-decoration: none;
            transition: background-color 150ms ease-out, color 150ms ease-out;
        }

        .admin-dashboard .dashboard-action--primary {
            background: var(--dashboard-ink);
            color: #ffffff;
        }

        .admin-dashboard .dashboard-action--primary:hover {
            background: var(--dashboard-charcoal);
            color: #ffffff;
        }

        .admin-dashboard .dashboard-action--secondary {
            background: var(--dashboard-cloud);
            color: var(--dashboard-ink);
        }

        .admin-dashboard :is(a, button):focus-visible {
            outline: 2px solid var(--dashboard-ink);
            outline-offset: 3px;
        }

        @media (max-width: 1000px) {
            .admin-dashboard .dashboard-columns {
                gap: 20px;
            }

            .admin-dashboard .dashboard-action-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .admin-dashboard .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .admin-dashboard .stat-card:nth-child(2) {
                border-right: 0;
            }

            .admin-dashboard .stat-card:nth-child(-n + 2) {
                border-bottom: 1px solid var(--dashboard-line-soft);
            }

            .admin-dashboard .dashboard-columns {
                grid-template-columns: minmax(0, 1fr);
                gap: 32px;
            }
        }

        @media (max-width: 480px) {
            .admin-dashboard .dashboard-header {
                margin-bottom: 24px;
            }

            .admin-dashboard .stats-grid {
                grid-template-columns: minmax(0, 1fr);
                margin-bottom: 32px;
            }

            .admin-dashboard .stat-card {
                padding: 14px 8px;
                border-right: 0;
                border-bottom: 1px solid var(--dashboard-line-soft);
            }

            .admin-dashboard .stat-card:nth-child(2) {
                border-bottom: 1px solid var(--dashboard-line-soft);
            }

            .admin-dashboard .stat-card:last-child {
                border-bottom: 0;
            }

            .admin-dashboard .stat-value {
                font-size: 25px;
            }

            .admin-dashboard .match-teams {
                gap: 7px;
            }

            .admin-dashboard .team-info {
                gap: 6px;
            }

            .admin-dashboard .team-logo-small {
                flex-basis: 28px;
                width: 28px;
                height: 28px;
            }

            .admin-dashboard .team-name {
                font-size: 13px;
            }

            .admin-dashboard .match-score {
                min-width: 42px;
                font-size: 14px;
            }

            .admin-dashboard .dashboard-action-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-dashboard .view-all,
            .admin-dashboard .dashboard-action {
                transition: none;
            }
        }
    </style>
@endsection

@section('content')
    <main class="admin-dashboard">
        <header class="dashboard-header">
            <div>
                <h1>Dashboard</h1>
                <p>Selamat datang kembali, {{ auth()->user()->name ?? 'Administrator' }}. Hari ini
                    {{ now(\App\Support\BusinessTime::TIMEZONE)->translatedFormat('d F Y') }}.</p>
            </div>
        </header>

        <section class="stats-grid" aria-label="Ringkasan sistem">
            <div class="stat-card stat-teams">
                <div class="stat-icon" aria-hidden="true"><i class="bi bi-shield-shaded"></i></div>
                <div class="stat-info">
                    <div class="stat-title">Tim aktif</div>
                    <div class="stat-value">{{ $totalTeams }}</div>
                </div>
            </div>

            <div class="stat-card stat-players">
                <div class="stat-icon" aria-hidden="true"><i class="bi bi-person-arms-up"></i></div>
                <div class="stat-info">
                    <div class="stat-title">Total pemain</div>
                    <div class="stat-value">{{ $totalPlayers }}</div>
                </div>
            </div>

            <div class="stat-card stat-matches">
                <div class="stat-icon" aria-hidden="true"><i class="bi bi-stopwatch"></i></div>
                <div class="stat-info">
                    <div class="stat-title">Total pertandingan</div>
                    <div class="stat-value">{{ $totalMatches }}</div>
                </div>
            </div>

            <div class="stat-card stat-completed">
                <div class="stat-icon" aria-hidden="true"><i class="bi bi-award"></i></div>
                <div class="stat-info">
                    <div class="stat-title">Selesai</div>
                    <div class="stat-value">{{ $completedMatches }}</div>
                </div>
            </div>
        </section>

        <div class="dashboard-columns">
            <section class="section-card" aria-labelledby="recent-matches-heading">
                <div class="card-header">
                    <h2 id="recent-matches-heading">Pertandingan terbaru</h2>
                </div>
                <div class="card-body">
                    @if($recentMatches->count() > 0)
                        @foreach($recentMatches as $match)
                            <article class="match-item">
                                <div class="match-teams">
                                    <div class="team-info">
                                        <div class="team-logo-small">
                                            @include('partials.team-logo', ['team' => $match->homeTeam])
                                        </div>
                                        <div class="team-name home">{{ $match->homeTeam->name ?? 'Belum ditentukan' }}</div>
                                    </div>

                                    @if($match->status === 'completed' && $match->home_score !== null && $match->away_score !== null)
                                        <div class="match-score">{{ $match->home_score }} – {{ $match->away_score }}</div>
                                    @else
                                        <div class="match-score match-score--pending">vs</div>
                                    @endif

                                    <div class="team-info">
                                        <div class="team-name away">{{ $match->awayTeam->name ?? 'Belum ditentukan' }}</div>
                                        <div class="team-logo-small">
                                            @include('partials.team-logo', ['team' => $match->awayTeam])
                                        </div>
                                    </div>
                                </div>

                                <div class="match-meta">
                                    <div class="match-date">
                                        {{ \Carbon\Carbon::parse($match->match_date)->translatedFormat('d M') }}@if($match->time_start),
                                        {{ $match->time_start }}@endif
                                    </div>
                                    <span class="status-badge status-{{ $match->status }}">{{ ucfirst($match->status) }}</span>
                                </div>
                            </article>
                        @endforeach
                        <a href="{{ route('admin.matches.index') }}" class="view-all">
                            Lihat semua pertandingan <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>
                    @else
                        <div class="empty-state">
                            <p class="empty-state-text">Belum ada pertandingan yang dijadwalkan.</p>
                            <a href="{{ route('admin.matches.create') }}" class="dashboard-action dashboard-action--primary">
                                Jadwalkan pertandingan
                            </a>
                        </div>
                    @endif
                </div>
            </section>

            <section class="section-card" aria-labelledby="recent-activity-heading">
                <div class="card-header">
                    <h2 id="recent-activity-heading">Aktivitas terbaru</h2>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        @php
                            $latestTeams = \App\Models\Team::latest()->take(3)->get();
                            $latestPlayers = \App\Models\Player::latest()->take(3)->get();
                        @endphp

                        @foreach($latestTeams as $team)
                            <div class="activity-item">
                                <div class="activity-content">
                                    <div class="activity-logo">
                                        @include('partials.team-logo', ['team' => $team])
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-name">{{ $team->name }}</div>
                                        <div class="activity-info">
                                            Tim baru, {{ $team->created_at->diffForHumans() }}, {{ $team->players_count ?? 0 }} pemain
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @foreach($latestPlayers as $player)
                            <div class="activity-item">
                                <div class="activity-content">
                                    <div class="activity-logo">
                                        <div class="logo-initial">
                                            @php
                                                $nameParts = explode(' ', $player->name);
                                                $initials = count($nameParts) >= 2
                                                    ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                                                    : strtoupper(substr($player->name, 0, 2));
                                            @endphp
                                            {{ $initials }}
                                        </div>
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-name">{{ $player->name }}</div>
                                        <div class="activity-info">
                                            @if($player->team)
                                                Pemain baru, {{ $player->team->name }}
                                            @else
                                                Pemain baru, tanpa tim
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="dashboard-action-grid dashboard-activity-actions">
                        <a href="{{ route('admin.teams.index') }}" class="dashboard-action dashboard-action--secondary">
                            <i class="bi bi-people" aria-hidden="true"></i> Lihat tim
                        </a>
                        <a href="{{ route('admin.players.index') }}" class="dashboard-action dashboard-action--secondary">
                            <i class="bi bi-person" aria-hidden="true"></i> Lihat pemain
                        </a>
                    </div>
                </div>
            </section>
        </div>

        <section class="section-card dashboard-actions-section" aria-labelledby="quick-actions-heading">
            <div class="card-header">
                <h2 id="quick-actions-heading">Aksi cepat</h2>
            </div>
            <div class="card-body">
                <nav class="dashboard-action-grid" aria-label="Aksi cepat">
                    <a href="{{ route('admin.tournaments.create.step', ['step' => 1]) }}" class="dashboard-action dashboard-action--primary">
                        <i class="bi bi-trophy" aria-hidden="true"></i> Buat turnamen
                    </a>
                    <a href="{{ route('admin.teams.create') }}" class="dashboard-action dashboard-action--secondary">
                        <i class="bi bi-people" aria-hidden="true"></i> Buat tim
                    </a>
                    <a href="{{ route('admin.players.create') }}" class="dashboard-action dashboard-action--secondary">
                        <i class="bi bi-person-plus" aria-hidden="true"></i> Buat pemain
                    </a>
                    <a href="{{ route('admin.matches.create') }}" class="dashboard-action dashboard-action--secondary">
                        <i class="bi bi-calendar-plus" aria-hidden="true"></i> Buat pertandingan
                    </a>
                </nav>
            </div>
        </section>
    </main>
@endsection
