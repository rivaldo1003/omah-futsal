@extends('layouts.admin')

@section('title', 'Match Events')

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

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .page-header h1 {
            color: var(--primary);
            font-weight: 600;
            margin: 0;
            font-size: 1.5rem;
        }

        .match-info {
            font-size: 0.875rem;
            color: var(--secondary);
            margin-top: 0.25rem;
        }

        .match-teams {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .team-logo-small {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            background: #f8fafc;
        }

        .team-logo-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .team-initial {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .btn-back {
            background: white;
            color: var(--secondary);
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            text-decoration: none;
            margin-right: 0.5rem;
        }

        .btn-back:hover {
            background: #f8fafc;
        }

        .btn-add {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
        }

        .btn-add:hover {
            background: #1d4ed8;
            color: white;
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
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--primary);
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .team-logo-stat {
            width: 28px;
            height: 28px;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .team-logo-stat img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .stat-values {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
            text-align: center;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Timeline */
        .main-card {
            background: var(--bg-card);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-header {
            background: #f8fafc;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
            color: var(--primary);
        }

        .timeline {
            padding: 1rem;
        }

        .timeline-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .timeline-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .timeline-time {
            min-width: 60px;
            text-align: center;
            margin-right: 1rem;
        }

        .time-main {
            font-weight: 600;
            color: var(--primary);
            font-size: 0.875rem;
        }

        .time-extra {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        .timeline-content {
            flex: 1;
        }

        .event-main {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.25rem;
        }

        .event-type {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.125rem 0.5rem;
            border-radius: 4px;
            display: inline-block;
        }

        .type-goal {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }

        .type-yellow {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .type-red {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .type-substitution {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-light);
        }

        .player-name {
            font-weight: 500;
            color: var(--primary);
            font-size: 0.875rem;
        }

        .player-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .player-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .player-team-logo {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .player-team-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .event-details {
            font-size: 0.75rem;
            color: var(--secondary);
            margin-top: 0.25rem;
        }

        .event-actions {
            display: flex;
            gap: 0.25rem;
            margin-top: 0.5rem;
        }

        .btn-action {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--secondary);
            text-decoration: none;
            font-size: 0.75rem;
        }

        .btn-action:hover {
            border-color: var(--primary-light);
        }

        .btn-edit:hover {
            background: var(--primary-light);
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
            color: white;
        }

        /* Team Indicator */
        .team-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        .team-home {
            background: var(--primary);
        }

        .team-away {
            background: #dc2626;
        }

        /* Empty State */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 2.5rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        .empty-state-title {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-state-text {
            color: var(--secondary);
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        /* Modal */
        .modal-content {
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-values {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .timeline-item {
                flex-direction: column;
            }

            .timeline-time {
                margin-right: 0;
                margin-bottom: 0.5rem;
                text-align: left;
            }

            .event-main {
                flex-wrap: wrap;
            }

            .event-actions {
                margin-top: 0.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size: 0.875rem; padding: 0; background: none;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.matches.index') }}">Matches</a></li>
            <li class="breadcrumb-item active">Events</li>
        </ol>
    </nav>

    <div class="page-header">
        <div>
            <h1>
                <i class="bi bi-activity me-2"></i>
                Match Events
            </h1>
            <div class="match-info">
                {{ $match->homeTeam->name ?? 'Home Team' }} vs {{ $match->awayTeam->name ?? 'Away Team' }}
                • {{ $match->match_date->format('d M Y') }}
            </div>
            <div class="match-teams">
                <!-- Home Team Logo -->
                <div class="team-logo-small">
                    @if($match->homeTeam && $match->homeTeam->logo)
                        @if(Storage::disk('public')->exists($match->homeTeam->logo))
                            <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" 
                                 alt="{{ $match->homeTeam->name }}">
                        @elseif(filter_var($match->homeTeam->logo, FILTER_VALIDATE_URL))
                            <img src="{{ $match->homeTeam->logo }}" 
                                 alt="{{ $match->homeTeam->name }}">
                        @else
                            <div class="team-initial">
                                {{ strtoupper(substr($match->homeTeam->name, 0, 1)) }}
                            </div>
                        @endif
                    @else
                        <div class="team-initial">H</div>
                    @endif
                </div>
                
                <span class="text-muted">vs</span>
                
                <!-- Away Team Logo -->
                <div class="team-logo-small">
                    @if($match->awayTeam && $match->awayTeam->logo)
                        @if(Storage::disk('public')->exists($match->awayTeam->logo))
                            <img src="{{ asset('storage/' . $match->awayTeam->logo) }}" 
                                 alt="{{ $match->awayTeam->name }}">
                        @elseif(filter_var($match->awayTeam->logo, FILTER_VALIDATE_URL))
                            <img src="{{ $match->awayTeam->logo }}" 
                                 alt="{{ $match->awayTeam->name }}">
                        @else
                            <div class="team-initial">
                                {{ strtoupper(substr($match->awayTeam->name, 0, 1)) }}
                            </div>
                        @endif
                    @else
                        <div class="team-initial">A</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.matches.index') }}" class="btn-back">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
            <a href="{{ route('admin.matches.events.create', $match) }}" class="btn-add">
                <i class="bi bi-plus"></i> Add Event
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <!-- Home Team Stats -->
        <div class="stat-card">
            <div class="stat-header">
                @if($match->homeTeam && $match->homeTeam->logo)
                    <div class="team-logo-stat">
                        @if(Storage::disk('public')->exists($match->homeTeam->logo))
                            <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" 
                                 alt="{{ $match->homeTeam->name }}">
                        @elseif(filter_var($match->homeTeam->logo, FILTER_VALIDATE_URL))
                            <img src="{{ $match->homeTeam->logo }}" 
                                 alt="{{ $match->homeTeam->name }}">
                        @else
                            <div class="team-initial">
                                {{ strtoupper(substr($match->homeTeam->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                @endif
                {{ $match->homeTeam->name ?? 'Home Team' }}
            </div>
            <div class="stat-values">
                <div>
                    <div class="stat-value">{{ $eventStats['home']['goals'] ?? 0 }}</div>
                    <div class="stat-label">Goals</div>
                </div>
                <div>
                    <div class="stat-value">{{ $eventStats['home']['yellow_cards'] ?? 0 }}</div>
                    <div class="stat-label">YC</div>
                </div>
                <div>
                    <div class="stat-value">{{ $eventStats['home']['red_cards'] ?? 0 }}</div>
                    <div class="stat-label">RC</div>
                </div>
                <div>
                    <div class="stat-value">{{ $eventStats['home']['substitutions'] ?? 0 }}</div>
                    <div class="stat-label">Subs</div>
                </div>
            </div>
        </div>

        <!-- Away Team Stats -->
        <div class="stat-card">
            <div class="stat-header">
                @if($match->awayTeam && $match->awayTeam->logo)
                    <div class="team-logo-stat">
                        @if(Storage::disk('public')->exists($match->awayTeam->logo))
                            <img src="{{ asset('storage/' . $match->awayTeam->logo) }}" 
                                 alt="{{ $match->awayTeam->name }}">
                        @elseif(filter_var($match->awayTeam->logo, FILTER_VALIDATE_URL))
                            <img src="{{ $match->awayTeam->logo }}" 
                                 alt="{{ $match->awayTeam->name }}">
                        @else
                            <div class="team-initial">
                                {{ strtoupper(substr($match->awayTeam->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                @endif
                {{ $match->awayTeam->name ?? 'Away Team' }}
            </div>
            <div class="stat-values">
                <div>
                    <div class="stat-value">{{ $eventStats['away']['goals'] ?? 0 }}</div>
                    <div class="stat-label">Goals</div>
                </div>
                <div>
                    <div class="stat-value">{{ $eventStats['away']['yellow_cards'] ?? 0 }}</div>
                    <div class="stat-label">YC</div>
                </div>
                <div>
                    <div class="stat-value">{{ $eventStats['away']['red_cards'] ?? 0 }}</div>
                    <div class="stat-label">RC</div>
                </div>
                <div>
                    <div class="stat-value">{{ $eventStats['away']['substitutions'] ?? 0 }}</div>
                    <div class="stat-label">Subs</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Timeline -->
    <div class="main-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i> Timeline</h5>
        </div>
        <div class="card-body p-0">
            <div class="timeline">
                @if($match->events->count() > 0)
                    @foreach($match->events as $event)
                        <div class="timeline-item">
                            <div class="timeline-time">
                                <div class="time-main">{{ $event->minute }}'</div>
                                @if($event->extra_minute)
                                    <div class="time-extra">+{{ $event->extra_minute }}'</div>
                                @endif
                            </div>
                            <div class="timeline-content">
                                <div class="event-main">
                                    <span
                                        class="team-indicator {{ $event->team_id == $match->team_home_id ? 'team-home' : 'team-away' }}"></span>
                                    <div class="player-info">
                                        <div class="player-name">{{ $event->player->name ?? 'Unknown' }}</div>
                                        @if($event->player && $event->player->team && $event->player->team->logo)
                                            <div class="player-team-logo">
                                                @if(Storage::disk('public')->exists($event->player->team->logo))
                                                    <img src="{{ asset('storage/' . $event->player->team->logo) }}" 
                                                         alt="{{ $event->player->team->name }}">
                                                @elseif(filter_var($event->player->team->logo, FILTER_VALIDATE_URL))
                                                    <img src="{{ $event->player->team->logo }}" 
                                                         alt="{{ $event->player->team->name }}">
                                                @else
                                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; font-size: 0.6rem; font-weight: 600;">
                                                        {{ strtoupper(substr($event->player->team->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <span class="event-type type-{{ $event->event_type }}">
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
                                    <a href="{{ route('admin.matches.events.edit', [$match, $event]) }}" class="btn-action btn-edit"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.matches.events.destroy', [$match, $event]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete"
                                            onclick="return confirm('Delete this event?')" title="Delete">
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
                        <h4 class="empty-state-title">No Events Yet</h4>
                        <p class="empty-state-text">
                            Add match events like goals, cards, and substitutions
                        </p>
                        <a href="{{ route('admin.matches.events.create', $match) }}" class="btn-add">
                            <i class="bi bi-plus"></i> Add First Event
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Add event type class mapping
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.event-type').forEach(type => {
                const text = type.textContent.toLowerCase().trim();
                if (text.includes('goal')) {
                    type.classList.add('type-goal');
                } else if (text.includes('yellow')) {
                    type.classList.add('type-yellow');
                } else if (text.includes('red')) {
                    type.classList.add('type-red');
                } else if (text.includes('substitution')) {
                    type.classList.add('type-substitution');
                }
            });
        });
    </script>
@endsection