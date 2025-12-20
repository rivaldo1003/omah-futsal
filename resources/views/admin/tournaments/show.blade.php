@extends('layouts.admin')

@section('title', 'Tournament Details')

@section('styles')
<style>
:root {
    --primary: #3B82F6;
    --secondary: #6B7280;
    --success: #10B981;
    --warning: #F59E0B;
    --danger: #EF4444;
    --dark: #111827;
    --light: #F9FAFB;
    --card-bg: #FFFFFF;
    --border: #E5E7EB;
    --radius: 10px;
}

.page-header {
    background: white;
    border-radius: var(--radius);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--border);
}

.tournament-header {
    background: linear-gradient(135deg, var(--primary), #2563EB);
    color: white;
    border-radius: var(--radius);
    padding: 2rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}

.tournament-header::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 40%;
    height: 200%;
    background: rgba(255, 255, 255, 0.1);
    transform: rotate(30deg);
}

.tournament-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.tournament-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    opacity: 0.9;
}

.badge-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
}

.badge-status-ongoing {
    background: #DCFCE7;
    color: #15803D;
}

.badge-status-upcoming {
    background: #FEF3C7;
    color: #D97706;
}

.badge-status-completed {
    background: #F1F5F9;
    color: #64748B;
}

/* Stats Grid - Compact */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1rem;
    text-align: center;
    transition: all 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.8rem;
    color: var(--secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Compact Tabs */
.compact-tabs {
    background: white;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.tabs-header {
    display: flex;
    background: var(--light);
    border-bottom: 1px solid var(--border);
    overflow-x: auto;
    scrollbar-width: none;
}

.tabs-header::-webkit-scrollbar {
    display: none;
}

.tab-btn {
    padding: 0.875rem 1.25rem;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    color: var(--secondary);
    font-weight: 600;
    font-size: 0.875rem;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.tab-btn:hover {
    color: var(--primary);
    background: rgba(59, 130, 246, 0.05);
}

.tab-btn.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
    background: rgba(59, 130, 246, 0.05);
}

.tab-content {
    padding: 1.5rem;
}

/* Teams List - Compact */
.teams-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.team-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1rem;
    text-align: center;
    transition: all 0.2s;
}

.team-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
}

.team-avatar {
    width: 48px;
    height: 48px;
    /* background: linear-gradient(135deg, var(--primary), #2563EB); */
    /* border-radius: 50%; */
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.25rem;
    margin: 0 auto 0.75rem;
}

.team-name {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}

.team-group {
    font-size: 0.75rem;
    color: var(--secondary);
    padding: 0.25rem 0.5rem;
    background: var(--light);
    border-radius: 12px;
    display: inline-block;
}

/* Matches Table - Compact */
.compact-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.compact-table thead th {
    background: var(--light);
    color: var(--secondary);
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--border);
}

.compact-table tbody td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--border);
    color: var(--dark);
    font-size: 0.875rem;
}

.compact-table tbody tr:last-child td {
    border-bottom: none;
}

.compact-table tbody tr:hover {
    background: var(--light);
}

.match-row {
    cursor: pointer;
    transition: all 0.2s;
}

.match-row:hover {
    background: rgba(59, 130, 246, 0.05);
}

.match-teams {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
}

.team-side {
    flex: 1;
    text-align: left;
}

.team-side.home {
    text-align: right;
}

.team-name-sm {
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 0.125rem;
}

.team-group-sm {
    font-size: 0.7rem;
    color: var(--secondary);
}

.match-score {
    padding: 0 1rem;
    min-width: 80px;
    text-align: center;
}

.score {
    font-weight: 700;
    font-size: 1rem;
    color: var(--dark);
}

.match-status {
    font-size: 0.7rem;
    color: var(--secondary);
    text-transform: uppercase;
    margin-top: 0.125rem;
}

.match-info {
    font-size: 0.75rem;
    color: var(--secondary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Settings Grid */
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.setting-item {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1rem;
}

.setting-label {
    font-size: 0.8rem;
    color: var(--secondary);
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.setting-value {
    font-weight: 600;
    color: var(--dark);
    font-size: 1rem;
}

/* Danger Zone */
.danger-zone {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    border-radius: var(--radius);
    padding: 1.5rem;
    margin-top: 2rem;
}

.danger-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #DC2626;
    margin-bottom: 0.75rem;
    font-weight: 600;
}

.danger-text {
    color: #991B1B;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    font-size: 3rem;
    color: var(--border);
    margin-bottom: 1rem;
}

.empty-title {
    color: var(--secondary);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: var(--secondary);
    font-size: 0.875rem;
    max-width: 300px;
    margin: 0 auto 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .tournament-meta {
        justify-content: center;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .teams-list {
        grid-template-columns: repeat(2, 1fr);
    }

    .compact-table {
        font-size: 0.8rem;
    }

    .match-teams {
        flex-direction: column;
        gap: 0.25rem;
    }

    .team-side {
        text-align: center !important;
        width: 100%;
    }

    .match-score {
        order: -1;
        padding: 0.5rem 0;
    }

    .settings-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .teams-list {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="h3 mb-2">
            <i class="bi bi-trophy me-2"></i>Tournament Details
        </h1>
        <p class="text-muted mb-0">Manage and view {{ $tournament->name }} tournament</p>
    </div>
    <div class="d-flex gap-2 mt-2">
        <a href="{{ route('admin.tournaments.edit', $tournament) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>
        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Tournament Header -->
<div class="tournament-header">
    <div class="d-flex justify-content-between align-items-start">
        <div class="position-relative" style="z-index: 1;">
            <h1 class="tournament-title">{{ $tournament->name }}</h1>
            <div class="tournament-meta">
                <span class="meta-item">
                    <i class="bi bi-geo-alt"></i>
                    {{ $tournament->location ?? 'N/A' }}
                </span>
                <span class="meta-item">
                    <i class="bi bi-calendar"></i>
                    {{ $tournament->formatted_dates }}
                </span>
                <span class="meta-item">
                    <i class="bi bi-people"></i>
                    {{ $tournament->teams_count }} teams
                </span>
            </div>
            @if($tournament->description)
            <p class="mt-2" style="opacity: 0.9; font-size: 0.95rem;">{{ $tournament->description }}</p>
            @endif
        </div>
        <div class="text-end position-relative" style="z-index: 1;">
            <div class="mb-2">
                <span class="badge-status badge-status-{{ $tournament->status }}">
                    {{ ucfirst($tournament->status) }}
                </span>
            </div>
            <small class="opacity-75">{{ strtoupper($tournament->type) }}</small>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $tournament->teams_count }}</div>
        <div class="stat-label">Teams</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $tournament->matches_count }}</div>
        <div class="stat-label">Matches</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $completedMatches ?? 0 }}</div>
        <div class="stat-label">Completed</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $tournament->duration }}</div>
        <div class="stat-label">Days</div>
    </div>
</div>

<!-- Tabs -->
<div class="compact-tabs">
    <div class="tabs-header">
        <button class="tab-btn active" data-tab="overview">
            <i class="bi bi-info-circle"></i>
            <span>Overview</span>
        </button>
        <button class="tab-btn" data-tab="teams">
            <i class="bi bi-people"></i>
            <span>Teams</span>
            <span class="badge bg-primary rounded-pill" style="font-size: 0.6rem;">{{ $tournament->teams_count }}</span>
        </button>
        <button class="tab-btn" data-tab="matches">
            <i class="bi bi-calendar-event"></i>
            <span>Matches</span>
            <span class="badge bg-primary rounded-pill"
                style="font-size: 0.6rem;">{{ $tournament->matches_count }}</span>
        </button>
        <button class="tab-btn" data-tab="settings">
            <i class="bi bi-gear"></i>
            <span>Settings</span>
        </button>
    </div>

    <div class="tab-content">
        <!-- Overview Tab -->
        <div id="overview-tab" class="tab-pane active">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-3"><i class="bi bi-info-square me-2"></i>Details</h6>
                    <table class="table table-sm">
                        <tr>
                            <td width="120"><small class="text-muted">Organizer</small></td>
                            <td>{{ $tournament->organizer ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><small class="text-muted">Location</small></td>
                            <td>{{ $tournament->location ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><small class="text-muted">Start Date</small></td>
                            <td>{{ $tournament->start_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td><small class="text-muted">End Date</small></td>
                            <td>{{ $tournament->end_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td><small class="text-muted">Created</small></td>
                            <td>{{ $tournament->created_at->diffForHumans() }}</td>
                        </tr>
                        @if($tournament->type == 'group_knockout')
                        <tr>
                            <td><small class="text-muted">Groups</small></td>
                            <td>{{ $tournament->groups_count }}</td>
                        </tr>
                        <tr>
                            <td><small class="text-muted">Qualify/Group</small></td>
                            <td>{{ $tournament->qualify_per_group }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-3"><i class="bi bi-bar-chart me-2"></i>Match Stats</h6>
                    <div class="settings-grid">
                        <div class="setting-item">
                            <div class="setting-label">Match Duration</div>
                            <div class="setting-value">{{ $tournament->match_duration }} min</div>
                        </div>
                        <div class="setting-item">
                            <div class="setting-label">Half Time</div>
                            <div class="setting-value">{{ $tournament->half_time }} min</div>
                        </div>
                        <div class="setting-item">
                            <div class="setting-label">Win Points</div>
                            <div class="setting-value">{{ $tournament->points_win }}</div>
                        </div>
                        <div class="setting-item">
                            <div class="setting-label">Draw Points</div>
                            <div class="setting-value">{{ $tournament->points_draw }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teams Tab -->
        <div id="teams-tab" class="tab-pane" style="display: none;">
            @if($tournament->teams_count > 0)
            <div class="teams-list">
                @foreach($tournament->teams as $team)
                <div class="team-card">
                    <div class="team-avatar">
                        @if($team->logo_url)
                        <img src="{{ $team->logo_url }}" alt="{{ $team->name }}"
                            style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;"
                            onerror="this.onerror=null; this.parentElement.innerHTML='{{ strtoupper(substr($team->name, 0, 1)) }}';">
                        @else
                        {{ strtoupper(substr($team->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="team-name">{{ $team->name }}</div>
                    
                    <!-- PERBAIKAN: Tampilkan group hanya untuk tournament yang membutuhkannya -->
                    <!-- @if($tournament->type == 'group_knockout' || ($tournament->type == 'league' && $team->pivot->group_name))
                    <div class="team-group">
                        Group {{ $team->pivot->group_name ?? 'A' }}
                    </div> -->
                    <!-- @endif -->
                    
                    <!-- <small class="text-muted d-block mt-2">
                        {{ $team->players_count ?? 0 }} players
                        
                        @if($tournament->type == 'knockout' || $tournament->type == 'league')
                        • Seed #{{ $team->pivot->seed ?? $loop->iteration }}
                        @endif
                    </small> -->
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h6 class="empty-title">No Teams Registered</h6>
                <p class="empty-text">Add teams to this tournament to get started.</p>
                <a href="{{ route('admin.tournaments.edit', $tournament) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add Teams
                </a>
            </div>
            @endif
        </div>

        <!-- Matches Tab -->
        <div id="matches-tab" class="tab-pane" style="display: none;">
            @if($tournament->matches_count > 0)
            <div class="mb-3">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary active" data-filter="all">All</button>
                    <button type="button" class="btn btn-outline-secondary" data-filter="completed">Completed</button>
                    <button type="button" class="btn btn-outline-secondary" data-filter="upcoming">Upcoming</button>
                    <button type="button" class="btn btn-outline-secondary" data-filter="ongoing">Ongoing</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Match</th>
                            <th>Date & Time</th>
                            <th>Venue</th>
                            <th>Round</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tournament->matches as $match)
                        <tr class="match-row" data-status="{{ $match->status }}">
                            <td>
                                <div class="match-teams">
                                    <div class="team-side">
                                        <div class="team-name-sm">{{ $match->homeTeam->name }}</div>
                                        @if($match->group_name)
                                        <div class="team-group-sm">Group {{ $match->group_name }}</div>
                                        @endif
                                    </div>
                                    <div class="match-score">
                                        <div class="score">
                                            @if($match->status == 'completed')
                                            {{ $match->home_score }} - {{ $match->away_score }}
                                            @elseif($match->status == 'ongoing')
                                            {{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}
                                            @else
                                            VS
                                            @endif
                                        </div>
                                        <div class="match-status">{{ ucfirst($match->status) }}</div>
                                    </div>
                                    <div class="team-side home">
                                        <div class="team-name-sm">{{ $match->awayTeam->name }}</div>
                                        @if($match->round_type != 'group')
                                        <div class="team-group-sm">{{ ucfirst($match->round_type) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="match-info">
                                    <span>{{ $match->match_date->format('d M') }}</span>
                                    <span>{{ date('H:i', strtotime($match->time_start)) }}</span>
                                </div>
                            </td>
                            <td>
                                <small>{{ $match->venue ?? 'Main Field' }}</small>
                            </td>
                            <td>
                                <small class="badge bg-light text-dark">
                                    {{ ucfirst(str_replace('_', ' ', $match->round_type)) }}
                                </small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <h6 class="empty-title">No Matches Scheduled</h6>
                <p class="empty-text">Schedule matches to populate this tournament.</p>
                <a href="{{ route('admin.matches.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Schedule Match
                </a>
            </div>
            @endif
        </div>

        <!-- Settings Tab -->
        <div id="settings-tab" class="tab-pane" style="display: none;">
            <div class="settings-grid">
                <div class="setting-item">
                    <div class="setting-label">Match Duration</div>
                    <div class="setting-value">{{ $tournament->match_duration }} minutes</div>
                </div>
                <div class="setting-item">
                    <div class="setting-label">Half Time</div>
                    <div class="setting-value">{{ $tournament->half_time }} minutes</div>
                </div>
                <div class="setting-item">
                    <div class="setting-label">Extra Time</div>
                    <div class="setting-value">{{ $settings['extra_time'] ?? 10 }} minutes</div>
                </div>
                <div class="setting-item">
                    <div class="setting-label">Max Substitutes</div>
                    <div class="setting-value">{{ $settings['max_substitutes'] ?? 5 }}</div>
                </div>
                <div class="setting-item">
                    <div class="setting-label">Matches per Day</div>
                    <div class="setting-value">{{ $settings['matches_per_day'] ?? 4 }}</div>
                </div>
                <div class="setting-item">
                    <div class="setting-label">Match Interval</div>
                    <div class="setting-value">{{ $settings['match_interval'] ?? 30 }} min</div>
                </div>
                <div class="setting-item">
                    <div class="setting-label">Yellow Card Limit</div>
                    <div class="setting-value">{{ $settings['yellow_card_suspension'] ?? 3 }}</div>
                </div>
                <div class="setting-item">
                    <div class="setting-label">VAR Enabled</div>
                    <div class="setting-value">
                        @if($settings['var_enabled'] ?? false)
                        <span class="badge bg-success">Yes</span>
                        @else
                        <span class="badge bg-secondary">No</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Danger Zone -->
<div class="danger-zone">
    <div class="danger-header">
        <i class="bi bi-exclamation-triangle"></i>
        <span>Danger Zone</span>
    </div>
    <p class="danger-text">Deleting this tournament will remove all associated data including matches, standings, and
        statistics. This action cannot be undone.</p>
    <form action="{{ route('admin.tournaments.destroy', $tournament) }}" method="POST"
        onsubmit="return confirm('Are you sure? This will permanently delete the tournament and all its data!')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="bi bi-trash me-1"></i> Delete Tournament
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');

            // Update active button
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Show active tab
            tabPanes.forEach(pane => {
                pane.style.display = 'none';
                pane.classList.remove('active');
            });

            const activePane = document.getElementById(tabId + '-tab');
            if (activePane) {
                activePane.style.display = 'block';
                activePane.classList.add('active');
            }
        });
    });

    // Match filtering
    const filterBtns = document.querySelectorAll('[data-filter]');
    const matchRows = document.querySelectorAll('.match-row');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');

            // Update active filter button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Filter matches
            matchRows.forEach(row => {
                if (filter === 'all' || row.getAttribute('data-status') === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // Match row click
    matchRows.forEach(row => {
        row.addEventListener('click', function() {
            // Add match detail view functionality here
            console.log('View match details');
        });
    });

    // Auto-dismiss alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 3000);
});
</script>
@endsection