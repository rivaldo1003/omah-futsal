@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary: #6b7280;
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --border-color: #e5e7eb;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Dashboard Header */
        .dashboard-header {
            margin-bottom: 1.5rem;
        }

        .dashboard-header h1 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 1.5rem;
        }

        .dashboard-header p {
            color: var(--secondary);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .greeting-badge {
            display: inline-block;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            color: var(--secondary);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            transition: all 0.2s;
        }

        .stat-card:hover {
            border-color: var(--primary-light);
        }

        .stat-content {
            display: flex;
            align-items: center;
        }

        .stat-info {
            flex: 1;
        }

        .stat-title {
            font-size: 0.75rem;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
        }

        /* Section Cards */
        .section-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: #f8fafc;
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1rem;
        }

        /* Recent Matches */
        .match-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .match-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .match-teams {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .team-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .team-logo-small {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            /* background: linear-gradient(135deg, var(--primary), var(--primary-light)); */
            overflow: hidden;
            flex-shrink: 0;
            /* border: 1px solid var(--border-color); */
        }

        .team-logo-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .team-name {
            font-weight: 500;
            color: var(--primary);
            font-size: 0.875rem;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .team-name.home {
            text-align: right;
            justify-content: flex-end;
        }

        .team-name.away {
            text-align: left;
            justify-content: flex-start;
        }

        .match-score {
            font-weight: 600;
            color: var(--primary);
            font-size: 0.875rem;
            text-align: center;
            min-width: 60px;
            padding: 0 0.5rem;
        }

        .match-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
        }

        .match-date {
            font-size: 0.75rem;
            color: var(--secondary);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .match-time {
            font-size: 0.75rem;
            color: var(--primary-light);
            font-weight: 500;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.125rem 0.5rem;
            border-radius: 4px;
        }

        .status-upcoming {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
        }

        .status-ongoing {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .status-completed {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }

        /* Activity Items */
        .activity-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .activity-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .activity-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .activity-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .activity-logo {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            flex-shrink: 0;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .activity-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .activity-details {
            flex: 1;
        }

        .activity-name {
            font-weight: 500;
            color: var(--primary);
            margin-bottom: 0.125rem;
            font-size: 0.875rem;
        }

        .activity-info {
            font-size: 0.75rem;
            color: var(--secondary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* View All Links */
        .view-all {
            display: block;
            text-align: center;
            padding: 0.5rem;
            border-top: 1px solid var(--border-color);
            color: var(--primary-light);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
        }

        .view-all:hover {
            background: #f8fafc;
            color: var(--primary);
        }

        /* Empty State */
        .empty-state {
            padding: 2rem 1rem;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 2rem;
            color: #d1d5db;
            margin-bottom: 0.75rem;
        }

        .empty-state-text {
            color: var(--secondary);
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .btn-small {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-small:hover {
            background: #1d4ed8;
            color: white;
        }

        /* Two Column Layout */
        .dashboard-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
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
                gap: 0.5rem;
            }

            .team-info {
                width: 100%;
                justify-content: center;
            }

            .team-name {
                max-width: 150px;
            }

            .match-score {
                order: -1;
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 0.75rem;
            }

            .stat-value {
                font-size: 1.25rem;
            }
        }

        /* Logo Functions */
        .logo-initial {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>Welcome back, {{ auth()->user()->name ?? 'Administrator' }}</p>
        <div class="greeting-badge">
            <i class="bi bi-calendar3 me-1"></i>{{ now()->format('F j, Y') }}
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <div class="stat-title">Active Teams</div>
                    <div class="stat-value">{{ $totalTeams }}</div>
                </div>
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                    <i class="bi bi-trophy"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <div class="stat-title">Total Players</div>
                    <div class="stat-value">{{ $totalPlayers }}</div>
                </div>
                <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <div class="stat-title">Total Matches</div>
                    <div class="stat-value">{{ $totalMatches }}</div>
                </div>
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <div class="stat-title">Completed</div>
                    <div class="stat-value">{{ $completedMatches }}</div>
                </div>
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="dashboard-columns">
        <!-- Recent Matches -->
        <div class="section-card">
            <div class="card-header">
                <h5><i class="bi bi-clock-history"></i> Recent Matches</h5>
            </div>
            <div class="card-body">
                @if($recentMatches->count() > 0)
                    @foreach($recentMatches as $match)
                        <div class="match-item">
                            <div class="match-teams">
                                <!-- Home Team -->
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
                                            <div class="logo-initial">
                                                TBD
                                            </div>
                                        @endif
                                    </div>
                                    <div class="team-name home">
                                        {{ $match->homeTeam->name ?? 'TBD' }}
                                    </div>
                                </div>

                                <!-- Score -->
                                @if($match->status === 'completed' && $match->home_score !== null && $match->away_score !== null)
                                    <div class="match-score">
                                        {{ $match->home_score }} - {{ $match->away_score }}
                                    </div>
                                @else
                                    <div class="match-score" style="color: var(--secondary);">
                                        VS
                                    </div>
                                @endif

                                <!-- Away Team -->
                                <div class="team-info">
                                    <div class="team-name away">
                                        {{ $match->awayTeam->name ?? 'TBD' }}
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
                                            <div class="logo-initial">
                                                TBD
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="match-meta">
                                <div class="match-date">
                                    <i class="bi bi-calendar3"></i>
                                    {{ \Carbon\Carbon::parse($match->match_date)->format('M d') }}
                                    @if($match->time_start)
                                        <span class="match-time">
                                            <i class="bi bi-clock ms-1"></i> {{ $match->time_start }}
                                        </span>
                                    @endif
                                </div>
                                <span class="status-badge status-{{ $match->status }}">
                                    {{ ucfirst($match->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                    <a href="{{ route('admin.matches.index') }}" class="view-all">
                        View all matches <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <p class="empty-state-text">No matches scheduled yet</p>
                        <a href="{{ route('admin.matches.create') }}" class="btn-small">
                            <i class="bi bi-plus me-1"></i> Schedule Match
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="section-card">
            <div class="card-header">
                <h5><i class="bi bi-activity"></i> Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    <!-- Latest Teams -->
                    @php
                        $latestTeams = \App\Models\Team::latest()->take(3)->get();
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
                                        <span><i class="bi bi-clock"></i> {{ $team->created_at->diffForHumans() }}</span>
                                        <span>·</span>
                                        <span>{{ $team->players_count ?? 0 }} players</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Latest Players -->
                    @php
                        $latestPlayers = \App\Models\Player::latest()->take(3)->get();
                    @endphp

                    @foreach($latestPlayers as $player)
                        <div class="activity-item">
                            <div class="activity-content">
                                <div class="activity-logo">
                                    <div class="logo-initial" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
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
                                            <span><i class="bi bi-people"></i> {{ $player->team->name }}</span>
                                        @else
                                            <span><i class="bi bi-person-x"></i> Free Agent</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-primary btn-sm me-2">
                        View Teams
                    </a>
                    <a href="{{ route('admin.players.index') }}" class="btn btn-outline-primary btn-sm">
                        View Players
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="section-card">
        <div class="card-header">
            <h5><i class="bi bi-lightning"></i> Quick Actions</h5>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.tournaments.create.step', ['step' => 1]) }}"
                        class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-trophy"></i> New Tournament
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.teams.create') }}"
                        class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-people-fill"></i> New Team
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.players.create') }}"
                        class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-person-fill"></i> New Player
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.matches.create') }}"
                        class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-calendar-plus"></i> New Match
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Simple script for dashboard
        document.addEventListener('DOMContentLoaded', function () {
            // Update live match status if any
            const ongoingBadges = document.querySelectorAll('.status-ongoing');
            ongoingBadges.forEach(badge => {
                // Add pulsing animation for ongoing matches
                badge.style.animation = 'pulse 2s infinite';
            });

            // Add CSS for pulse animation
            const style = document.createElement('style');
            style.textContent = `
                    @keyframes pulse {
                        0% { opacity: 1; }
                        50% { opacity: 0.6; }
                        100% { opacity: 1; }
                    }
                `;
            document.head.appendChild(style);

            // Auto-dismiss alerts if any
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
@endsection