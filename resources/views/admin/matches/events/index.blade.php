@extends('layouts.admin')

@section('title', 'Match Events')

@section('styles')
    <style>
        .admin-match-events {
            --events-ink: #111111;
            --events-charcoal: #39393b;
            --events-muted: #707072;
            --events-cloud: #f5f5f5;
            --events-border: #e5e5e5;
            --events-border-strong: #cacacb;
            --events-success: #007d48;
            --events-danger: #b42318;
            max-width: 1440px;
            margin-inline: auto;
            color: var(--events-ink);
        }

        .admin-match-events .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 28px;
        }

        .admin-match-events .page-header h1 {
            font-size: clamp(26px, 3vw, 32px);
            font-weight: 600;
            color: var(--events-ink);
            letter-spacing: -0.035em;
            line-height: 1.15;
            margin: 0 0 8px;
        }

        .admin-match-events .page-subtitle {
            color: var(--events-muted);
            font-size: 15px;
            margin: 0;
            line-height: 1.5;
        }

        .admin-match-events .match-teams {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 12px;
        }

        .admin-match-events .team-logo-small {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid var(--events-border);
            overflow: hidden;
            background: var(--events-cloud);
        }

        .admin-match-events .team-logo-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-match-events .page-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
        }

        .admin-match-events .btn-back,
        .admin-match-events .btn-add {
            min-height: 46px;
            padding: 11px 20px;
            border: 0;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            transition: background-color .15s ease, color .15s ease;
        }

        .admin-match-events .btn-back {
            background: var(--events-cloud);
            color: var(--events-ink);
        }

        .admin-match-events .btn-back:hover {
            background: #eaeaec;
            color: var(--events-ink);
        }

        .admin-match-events .btn-add {
            background: var(--events-ink);
            color: #ffffff;
        }

        .admin-match-events .btn-add:hover {
            background: #303032;
            color: #ffffff;
        }

        .admin-match-events .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .admin-match-events .stat-card {
            min-width: 0;
            padding: 18px 20px 16px;
            border: 1px solid var(--events-border);
            border-radius: 8px;
            background: #ffffff;
        }

        .admin-match-events .stat-header {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-size: 15px;
            color: var(--events-ink);
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--events-border);
        }

        .admin-match-events .team-logo-stat {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            overflow: hidden;
            border: 1px solid var(--events-border);
            background: var(--events-cloud);
            flex-shrink: 0;
        }

        .admin-match-events .team-logo-stat img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-match-events .stat-values {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .admin-match-events .stat-values > div {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 8px;
            padding-bottom: 7px;
            border-bottom: 1px solid var(--events-border);
        }

        .admin-match-events .stat-value {
            order: 2;
            margin: 0;
            color: var(--events-ink);
            font-size: 18px;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        .admin-match-events .stat-label {
            color: var(--events-muted);
            font-size: 13px;
        }

        .admin-match-events .main-card {
            overflow: hidden;
            border: 1px solid var(--events-border);
            border-radius: 8px;
            background: #ffffff;
            box-shadow: none;
        }

        .admin-match-events .main-card .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 58px;
            padding: 14px 20px;
            border-bottom: 1px solid var(--events-border);
            background: #ffffff;
        }

        .admin-match-events .main-card .card-header h5 {
            margin: 0;
            color: var(--events-ink);
            font-size: 16px;
            font-weight: 600;
        }

        .admin-match-events .timeline {
            padding: 4px 20px;
        }

        .admin-match-events .timeline-item {
            display: grid;
            grid-template-columns: 64px minmax(0, 1fr);
            gap: 16px;
            align-items: start;
            margin: 0;
            padding: 20px 0;
            border-bottom: 1px solid var(--events-border);
        }

        .admin-match-events .timeline-item:last-child {
            border-bottom: 0;
        }

        .admin-match-events .timeline-time {
            min-width: 0;
            margin: 0;
            padding-top: 1px;
            text-align: left;
        }

        .admin-match-events .time-main {
            color: var(--events-ink);
            font-size: 15px;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        .admin-match-events .timeline-content {
            min-width: 0;
        }

        .admin-match-events .event-main {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
            flex-wrap: wrap;
        }

        .admin-match-events .event-type {
            display: inline-flex;
            align-items: center;
            min-height: 24px;
            padding: 3px 9px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .admin-match-events .type-goal {
            background: #eaf5ef;
            color: #075c39;
        }

        .admin-match-events .type-yellow {
            background: #fff5dc;
            color: #704400;
        }

        .admin-match-events .type-red {
            background: #fff0ef;
            color: #8f1d17;
        }

        .admin-match-events .type-substitution,
        .admin-match-events .event-type:not(.type-goal):not(.type-yellow):not(.type-red) {
            border: 1px solid var(--events-border);
            background: var(--events-cloud);
            color: var(--events-charcoal);
        }

        .admin-match-events .player-name {
            color: var(--events-ink);
            font-size: 15px;
            font-weight: 600;
        }

        .admin-match-events .player-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-match-events .player-team-logo {
            width: 22px;
            height: 22px;
            overflow: hidden;
            border: 1px solid var(--events-border);
            border-radius: 50%;
        }

        .admin-match-events .player-team-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-match-events .event-details {
            margin-top: 4px;
            color: var(--events-muted);
            font-size: 13px;
            line-height: 1.55;
        }

        .admin-match-events .event-details .badge {
            vertical-align: baseline;
            font-weight: 500;
        }

        .admin-match-events .team-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            flex: 0 0 auto;
            border-radius: 50%;
        }

        .admin-match-events .team-home {
            background: var(--events-success);
        }

        .admin-match-events .team-away {
            background: #646467;
        }

        .admin-match-events .event-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .admin-match-events .event-actions form {
            margin: 0;
        }

        .admin-match-events .btn-action {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--events-border-strong);
            border-radius: 50%;
            background: #ffffff;
            color: var(--events-charcoal);
            text-decoration: none;
            font-size: 14px;
            transition: border-color .15s ease, background-color .15s ease, color .15s ease;
        }

        .admin-match-events .btn-action:hover {
            border-color: var(--events-ink);
            background: var(--events-cloud);
            color: var(--events-ink);
        }

        .admin-match-events .btn-action.btn-delete:hover {
            border-color: var(--events-danger);
            background: #fff5f4;
            color: var(--events-danger);
        }

        .admin-match-events .empty-state {
            padding: 40px 20px;
            text-align: center;
        }

        .admin-match-events .empty-state-icon {
            margin-bottom: 10px;
            color: var(--events-muted);
            font-size: 26px;
        }

        .admin-match-events .empty-state-title {
            margin: 0 0 6px;
            color: var(--events-ink);
            font-size: 17px;
            font-weight: 600;
        }

        .admin-match-events .empty-state-text {
            margin: 0 0 18px;
            color: var(--events-muted);
            font-size: 14px;
        }

        .admin-match-events :focus-visible {
            outline: 2px solid var(--events-ink);
            outline-offset: 2px;
        }

        @media (max-width: 768px) {
            .admin-match-events .page-header {
                align-items: flex-start;
            }

            .admin-match-events .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .admin-match-events .stat-values {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px 16px;
            }

            .admin-match-events .page-actions {
                width: 100%;
            }

            .admin-match-events .page-actions .btn-back,
            .admin-match-events .page-actions .btn-add {
                flex: 1 1 auto;
            }

            .admin-match-events .timeline-item {
                grid-template-columns: 48px minmax(0, 1fr);
                gap: 12px;
            }
        }

        @media (max-width: 575.98px) {
            .admin-match-events .main-card .card-header {
                padding-inline: 16px;
            }

            .admin-match-events .timeline {
                padding-inline: 16px;
            }

            .admin-match-events .page-actions .btn-back,
            .admin-match-events .page-actions .btn-add {
                padding-inline: 14px;
            }

            .admin-match-events .btn-action {
                width: 44px;
                height: 44px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-match-events *,
            .admin-match-events *::before,
            .admin-match-events *::after {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
    </style>
@endsection

@section('content')
    <main class="admin-match-events">
    <div class="page-header">
        <div>
            <h1>Event Pertandingan</h1>
            <p class="page-subtitle">
                {{ $match->homeTeam->name ?? 'Tim Home' }} vs {{ $match->awayTeam->name ?? 'Tim Away' }},
                {{ $match->match_date->format('d M Y') }}
            </p>
            <div class="match-teams">
                @include('partials.team-logo', ['team' => $match->homeTeam, 'class' => 'team-logo-small'])
                <span class="text-secondary" style="font-size: 12px;">vs</span>
                @include('partials.team-logo', ['team' => $match->awayTeam, 'class' => 'team-logo-small'])
            </div>
        </div>
        <div class="page-actions">
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
                        <div class="stat-label">Gol</div>
                    </div>
                    <div>
                        <div class="stat-value">{{ $eventStats[$side]['yellow_cards'] ?? 0 }}</div>
                        <div class="stat-label">Kartu kuning</div>
                    </div>
                    <div>
                        <div class="stat-value">{{ $eventStats[$side]['red_cards'] ?? 0 }}</div>
                        <div class="stat-label">Kartu merah</div>
                    </div>
                    <div>
                        <div class="stat-value">{{ $eventStats[$side]['substitutions'] ?? 0 }}</div>
                        <div class="stat-label">Pergantian</div>
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
                                    <span
                                        class="team-indicator {{ $event->team_id == $match->team_home_id ? 'team-home' : 'team-away' }}"></span>

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
                                        @if($event->conceded_by_goalkeeper_id)
                                            • Kiper kebobolan: {{ $event->concededByGoalkeeper->name ?? 'Unknown' }}
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
                                        title="Ubah" aria-label="Ubah event">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.matches.events.destroy', [$match, $event]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete"
                                            onclick="return confirm('Hapus event ini?')" title="Hapus" aria-label="Hapus event">
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
    </main>
@endsection
