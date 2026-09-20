@extends('layouts.admin')

@section('title', 'Match Events')

@section('styles')
    <style>
        /* ===== Match events page — design guidelines ===== */

        /* Page header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
            margin: 0 0 4px;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            margin: 0;
        }

        .match-teams {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .team-logo-small {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: 1px solid var(--border);
            overflow: hidden;
            background: var(--surface);
        }

        .team-logo-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-back {
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text-primary);
            border-radius: 6px;
            height: 40px;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-back:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-add {
            background: var(--accent);
            color: #fff;
            border: none;
            height: 40px;
            padding: 0 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add:hover {
            background: var(--accent-hover);
            color: #fff;
        }

        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 14px;
            color: var(--text-primary);
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }

        .team-logo-stat {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid var(--border);
            flex-shrink: 0;
        }

        .team-logo-stat img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .stat-values {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            text-align: center;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 2px;
        }

        .stat-label {
            font-size: 11px;
            color: var(--text-secondary);
            
        }

        /* Timeline card */
        .main-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .main-card .card-header {
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            padding: 12px 16px;
        }

        .main-card .card-header h5 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .timeline {
            padding: 16px;
        }

        .timeline-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .timeline-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .timeline-time {
            min-width: 60px;
            text-align: center;
            margin-right: 16px;
        }

        .time-main {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .timeline-content {
            flex: 1;
        }

        .event-main {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
            flex-wrap: wrap;
        }

        .event-type {
            font-size: 12px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        .type-goal {
            background: #F0F9F4;
            color: #1E7A46;
        }

        .type-yellow {
            background: #FDF6EC;
            color: #B45309;
        }

        .type-red {
            background: #FDF2F3;
            color: #c01c28;
        }

        .type-substitution {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .player-name {
            font-weight: 500;
            color: var(--text-primary);
            font-size: 14px;
        }

        .player-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .player-team-logo {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .player-team-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .event-details {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        .event-actions {
            display: flex;
            gap: 4px;
            margin-top: 8px;
        }

        .btn-action {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13px;
        }

        .btn-action:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-action.btn-delete:hover {
            border-color: #c01c28;
            color: #c01c28;
            background: #FDF2F3;
        }

        /* Team indicator */
        .team-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .team-home {
            background: var(--accent);
        }

        .team-away {
            background: #c01c28;
        }

        /* Empty state */
        .empty-state {
            padding: 48px 16px;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 32px;
            color: var(--border);
            margin-bottom: 12px;
        }

        .empty-state-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .empty-state-text {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 16px;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-values {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .timeline-item {
                flex-direction: column;
            }

            .timeline-time {
                margin-right: 0;
                margin-bottom: 8px;
                text-align: left;
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
    <div class="page-header">
        <div>
            <h1>Event Pertandingan</h1>
            <p class="page-subtitle">
                {{ $match->homeTeam->name ?? 'Tim Home' }} vs {{ $match->awayTeam->name ?? 'Tim Away' }}, {{ $match->match_date->format('d M Y') }}
            </p>
            <div class="match-teams">
                @include('partials.team-logo', ['team' => $match->homeTeam, 'class' => 'team-logo-small'])
                <span class="text-secondary" style="font-size: 12px;">vs</span>
                @include('partials.team-logo', ['team' => $match->awayTeam, 'class' => 'team-logo-small'])
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.matches.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.matches.events.create', $match) }}" class="btn-add">
                <i class="bi bi-plus"></i> Tambah Event
            </a>
        </div>
    </div>

    <!-- Statistik per tim -->
    <div class="stats-grid">
        @foreach(['home' => $match->homeTeam, 'away' => $match->awayTeam] as $side => $team)
            <div class="stat-card">
                <div class="stat-header">
                    @include('partials.team-logo', ['team' => $team, 'class' => 'team-logo-stat'])
                    {{ $team->name ?? ($side === 'home' ? 'Tim Home' : 'Tim Away') }}
                </div>
                <div class="stat-values">
                    <div>
                        <div class="stat-value">{{ $eventStats[$side]['goals'] ?? 0 }}</div>
                        <div class="stat-label">Goals</div>
                    </div>
                    <div>
                        <div class="stat-value">{{ $eventStats[$side]['yellow_cards'] ?? 0 }}</div>
                        <div class="stat-label">YC</div>
                    </div>
                    <div>
                        <div class="stat-value">{{ $eventStats[$side]['red_cards'] ?? 0 }}</div>
                        <div class="stat-label">RC</div>
                    </div>
                    <div>
                        <div class="stat-value">{{ $eventStats[$side]['substitutions'] ?? 0 }}</div>
                        <div class="stat-label">Subs</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Timeline event -->
    <div class="main-card">
        <div class="card-header">
            <h5><i class="bi bi-clock-history me-2"></i>Timeline</h5>
        </div>

        <div class="card-body p-0">
            <div class="timeline">
                @if($match->events->count() > 0)
                    @foreach($match->events as $event)
                        @php
                            /* Mapping tipe event ke class badge (pengganti JS, hasil sama) */
                            $eventTypeClass = match (true) {
                                str_contains($event->event_type, 'goal') => 'type-goal',
                                str_contains($event->event_type, 'yellow') => 'type-yellow',
                                str_contains($event->event_type, 'red') => 'type-red',
                                str_contains($event->event_type, 'substitution') => 'type-substitution',
                                default => '',
                            };
                        @endphp

                        <div class="timeline-item">
                            <div class="timeline-time">
                                <div class="time-main">{{ $event->minute }}'</div>
                            </div>

                            <div class="timeline-content">
                                <div class="event-main">
                                    <span class="team-indicator {{ $event->team_id == $match->team_home_id ? 'team-home' : 'team-away' }}"></span>

                                    <div class="player-info">
                                        <div class="player-name">{{ $event->player->name ?? 'Unknown' }}</div>
                                        @if($event->player && $event->player->team)
                                            @include('partials.team-logo', ['team' => $event->player->team, 'class' => 'player-team-logo'])
                                        @endif
                                    </div>

                                    <span class="event-type {{ $eventTypeClass }}">
                                        {{ str_replace('_', ' ', $event->event_type) }}
                                    </span>
                                </div>

                                <div class="event-details">
                                    @if($event->event_type === 'goal')
                                        @if($event->is_penalty)
                                            <span class="badge bg-warning bg-opacity-10 text-warning">Penalty</span>
                                        @endif
                                        @if($event->is_own_goal)
                                            <span class="badge bg-danger bg-opacity-10 text-danger">Own Goal</span>
                                        @endif
                                        @if($event->related_player_id)
                                            • Assist: {{ $event->relatedPlayer->name ?? 'Unknown' }}
                                        @endif
                                    @endif

                                    @if($event->event_type === 'substitution' && $event->related_player_id)
                                        • For: {{ $event->relatedPlayer->name ?? 'Unknown' }}
                                    @endif

                                    @if($event->description)
                                        • {{ $event->description }}
                                    @endif
                                </div>

                                <div class="event-actions">
                                    <a href="{{ route('admin.matches.events.edit', [$match, $event]) }}" class="btn-action"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.matches.events.destroy', [$match, $event]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete"
                                            onclick="return confirm('Hapus event ini?')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-activity"></i>
                        </div>
                        <h4 class="empty-state-title">Belum ada event</h4>
                        <p class="empty-state-text">
                            Tambahkan event pertandingan seperti gol, kartu, dan substitusi.
                        </p>
                        <a href="{{ route('admin.matches.events.create', $match) }}" class="btn-add">
                            <i class="bi bi-plus"></i> Tambah Event Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
