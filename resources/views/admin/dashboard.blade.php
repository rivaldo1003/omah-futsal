@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
    <style>
        :root {
            --bg: #FFFFFF;
            --surface: #F7F7F8;
            --border: #E5E5E7;
            --text-primary: #111113;
            --text-secondary: #6B6B70;
            --accent: #1a5fb4;
            --accent-hover: #164e95;
            --success: #1E7A46;
            --success-bg: #F0F9F4;
            --warning: #B45309;
            --warning-bg: #FDF6EC;
            --muted-bg: #F0F0F2;
        }

        .dashboard-header {
            margin-bottom: 24px;
        }

        .dashboard-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
            margin: 0 0 4px;
        }

        .dashboard-header p {
            color: var(--text-secondary);
            font-size: 14px;
            margin: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
        }

        .stat-title {
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .section-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card-header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 12px 16px;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 14px;
            color: var(--text-primary);
        }

        .card-body {
            padding: 16px;
        }

        /* Recent matches */
        .match-item {
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }

        .match-item:last-of-type {
            border-bottom: none;
            padding-bottom: 0;
        }

        .match-teams {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-bottom: 8px;
        }

        .team-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .team-logo-small {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--muted-bg);
        }

        .team-logo-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-initial {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 12px;
            background: var(--muted-bg);
        }

        .team-name {
            font-weight: 500;
            color: var(--text-primary);
            font-size: 14px;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .team-name.home {
            text-align: right;
        }

        .match-score {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
            text-align: center;
            min-width: 60px;
        }

        .match-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .match-date {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .status-badge {
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .status-upcoming {
            background: var(--muted-bg);
            color: var(--text-secondary);
        }

        .status-ongoing {
            background: var(--warning-bg);
            color: var(--warning);
        }

        .status-completed {
            background: var(--success-bg);
            color: var(--success);
        }

        /* Activity list */
        .activity-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .activity-item {
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }

        .activity-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .activity-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .activity-logo {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--muted-bg);
        }

        .activity-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .activity-name {
            font-weight: 500;
            color: var(--text-primary);
            font-size: 14px;
        }

        .activity-info {
            font-size: 12px;
            color: var(--text-secondary);
        }

        /* Links & buttons */
        .view-all {
            display: block;
            text-align: center;
            padding: 10px;
            border-top: 1px solid var(--border);
            color: var(--accent);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }

        .view-all:hover {
            background: var(--surface);
            color: var(--accent-hover);
        }

        .empty-state {
            padding: 32px 16px;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 24px;
            color: var(--border);
            margin-bottom: 12px;
        }

        .empty-state-text {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 16px;
        }

        .btn-small {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-small:hover {
            background: var(--accent-hover);
            color: #fff;
        }

        .btn-outline {
            border: 1px solid var(--border);
            color: var(--text-primary);
            background: var(--bg);
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-outline:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: var(--surface);
        }

        .dashboard-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .dashboard-columns {
                grid-template-columns: 1fr;
            }

            .match-teams {
                flex-direction: column;
                gap: 8px;
            }

            .team-info {
                width: 100%;
                justify-content: center;
            }

            .match-score {
                order: -1;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
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
@endsection

@section('content')
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>Selamat datang kembali, {{ auth()->user()->name ?? 'Administrator' }} — {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <!-- Statistik -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Tim aktif</div>
            <div class="stat-value">{{ $totalTeams }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Total pemain</div>
            <div class="stat-value">{{ $totalPlayers }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Total pertandingan</div>
            <div class="stat-value">{{ $totalMatches }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Selesai</div>
            <div class="stat-value">{{ $completedMatches }}</div>
        </div>
    </div>

    <div class="dashboard-columns">
        <!-- Pertandingan terbaru -->
        <div class="section-card">
            <div class="card-header">
                <h5>Pertandingan terbaru</h5>
            </div>
            <div class="card-body">
                @if($recentMatches->count() > 0)
                    @foreach($recentMatches as $match)
                        <div class="match-item">
                            <div class="match-teams">
                                <div class="team-info">
                                    <div class="team-logo-small">
                                        @if($match->homeTeam && $match->homeTeam->logo)
                                            @if(Storage::disk('public')->exists($match->homeTeam->logo))
                                                <img src="{{ asset('storage/' . $match->homeTeam->logo) }}"
                                                    alt="{{ $match->homeTeam->name }}">
                                            @elseif(filter_var($match->homeTeam->logo, FILTER_VALIDATE_URL))
                                                <img src="{{ $match->homeTeam->logo }}" alt="{{ $match->homeTeam->name }}">
                                            @else
                                                <div class="logo-initial">
                                                    {{ strtoupper(substr($match->homeTeam->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        @elseif($match->homeTeam)
                                            <div class="logo-initial">
                                                {{ strtoupper(substr($match->homeTeam->name, 0, 1)) }}
                                            </div>
                                        @else
                                            <div class="logo-initial">?</div>
                                        @endif
                                    </div>
                                    <div class="team-name home">
                                        {{ $match->homeTeam->name ?? 'Belum ditentukan' }}
                                    </div>
                                </div>

                                @if($match->status === 'completed' && $match->home_score !== null && $match->away_score !== null)
                                    <div class="match-score">
                                        {{ $match->home_score }} – {{ $match->away_score }}
                                    </div>
                                @else
                                    <div class="match-score" style="color: var(--text-secondary);">vs</div>
                                @endif

                                <div class="team-info">
                                    <div class="team-name away">
                                        {{ $match->awayTeam->name ?? 'Belum ditentukan' }}
                                    </div>
                                    <div class="team-logo-small">
                                        @if($match->awayTeam && $match->awayTeam->logo)
                                            @if(Storage::disk('public')->exists($match->awayTeam->logo))
                                                <img src="{{ asset('storage/' . $match->awayTeam->logo) }}"
                                                    alt="{{ $match->awayTeam->name }}">
                                            @elseif(filter_var($match->awayTeam->logo, FILTER_VALIDATE_URL))
                                                <img src="{{ $match->awayTeam->logo }}" alt="{{ $match->awayTeam->name }}">
                                            @else
                                                <div class="logo-initial">
                                                    {{ strtoupper(substr($match->awayTeam->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        @elseif($match->awayTeam)
                                            <div class="logo-initial">
                                                {{ strtoupper(substr($match->awayTeam->name, 0, 1)) }}
                                            </div>
                                        @else
                                            <div class="logo-initial">?</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="match-meta">
                                <div class="match-date">
                                    {{ \Carbon\Carbon::parse($match->match_date)->translatedFormat('d M') }}
                                    @if($match->time_start)
                                        · {{ $match->time_start }}
                                    @endif
                                </div>
                                <span class="status-badge status-{{ $match->status }}">
                                    {{ ucfirst($match->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                    <a href="{{ route('admin.matches.index') }}" class="view-all">
                        Lihat semua pertandingan
                    </a>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <p class="empty-state-text">Belum ada pertandingan yang dijadwalkan.</p>
                        <a href="{{ route('admin.matches.create') }}" class="btn-small">
                            Jadwalkan pertandingan
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Aktivitas terbaru -->
        <div class="section-card">
            <div class="card-header">
                <h5>Aktivitas terbaru</h5>
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
                                    @if($team->logo)
                                        @if(Storage::disk('public')->exists($team->logo))
                                            <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}">
                                        @elseif(filter_var($team->logo, FILTER_VALIDATE_URL))
                                            <img src="{{ $team->logo }}" alt="{{ $team->name }}">
                                        @else
                                            <div class="logo-initial">
                                                {{ strtoupper(substr($team->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    @else
                                        <div class="logo-initial">
                                            {{ strtoupper(substr($team->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="activity-details">
                                    <div class="activity-name">{{ $team->name }}</div>
                                    <div class="activity-info">
                                        Tim baru · {{ $team->created_at->diffForHumans() }} ·
                                        {{ $team->players_count ?? 0 }} pemain
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
                                            Pemain baru · {{ $player->team->name }}
                                        @else
                                            Pemain baru · tanpa tim
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3 d-flex gap-2">
                    <a href="{{ route('admin.teams.index') }}" class="btn btn-outline btn-sm">
                        Lihat tim
                    </a>
                    <a href="{{ route('admin.players.index') }}" class="btn btn-outline btn-sm">
                        Lihat pemain
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Aksi cepat -->
    <div class="section-card">
        <div class="card-header">
            <h5>Aksi cepat</h5>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.tournaments.create.step', ['step' => 1]) }}"
                        class="btn btn-outline w-100">
                        <i class="bi bi-trophy me-1"></i> Buat turnamen
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.teams.create') }}" class="btn btn-outline w-100">
                        <i class="bi bi-people me-1"></i> Buat tim
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.players.create') }}" class="btn btn-outline w-100">
                        <i class="bi bi-person-plus me-1"></i> Buat pemain
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.matches.create') }}" class="btn btn-outline w-100">
                        <i class="bi bi-calendar-plus me-1"></i> Buat pertandingan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection