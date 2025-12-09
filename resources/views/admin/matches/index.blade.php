@extends('layouts.admin')

@section('title', 'Matches Management')

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

.btn-create {
    background: var(--primary);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    font-size: 0.875rem;
}

.btn-create:hover {
    background: #1d4ed8;
    color: white;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    /* <-- bikin tinggi rata tengah */
    margin-bottom: 20px;
}

.search-box {
    position: relative;
}

.search-box .search-icon {
    position: absolute;
    top: 50%;
    left: 8px;
    transform: translateY(-50%);
    color: #888;
}

.search-box input {
    padding-left: 28px;
}

.btn-create {
    background: #0d6efd;
    color: white;
    border-radius: 4px;
    padding: 5px 12px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
}

/* Search & Filters */
.search-box {
    position: relative;
    width: 300px;
}

.search-box input {
    padding-left: 2.5rem;
    border-radius: 6px;
    border: 1px solid var(--border-color);
    width: 100%;
    font-size: 0.875rem;
}

.search-box input:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    outline: none;
}

.search-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary);
    pointer-events: none;
}

.filter-select {
    border-radius: 6px;
    border: 1px solid var(--border-color);
    padding: 0.5rem;
    background: white;
    color: var(--primary);
    font-size: 0.875rem;
    min-width: 120px;
}

.filter-select:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    outline: none;
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

/* Main Card */
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

/* Table Styling */
.table {
    margin: 0;
    font-size: 0.875rem;
}

.table thead th {
    background: #f8fafc;
    color: var(--secondary);
    font-weight: 600;
    border-bottom: 1px solid var(--border-color);
    padding: 0.75rem 1rem;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.table tbody td {
    padding: 0.75rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
}

.table tbody tr:hover {
    background-color: rgba(59, 130, 246, 0.02);
}

/* Match Info */
.match-date {
    font-weight: 500;
    color: var(--primary);
    margin-bottom: 2px;
    font-size: 0.875rem;
}

.match-time {
    font-size: 0.75rem;
    color: var(--secondary);
}

.match-teams {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.team-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.team-logo-small {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    border: 1px solid var(--border-color);
    overflow: hidden;
    background: #f8fafc;
    flex-shrink: 0;
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

.team-name {
    font-weight: 500;
    color: var(--primary);
    flex: 1;
    text-align: right;
    font-size: 0.875rem;
}

.team-name.away {
    text-align: left;
}

.vs {
    color: var(--secondary);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0 0.5rem;
}

.score {
    font-weight: 600;
    color: var(--primary);
    font-size: 0.875rem;
    text-align: center;
    margin-top: 0.25rem;
}

/* Stage Badge */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    display: inline-block;
}

.badge-group {
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary-light);
}

.badge-knockout {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

/* Status Badge */
.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    display: inline-block;
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

.status-postponed {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
}

.btn-highlight {
    background: #ff0000;
    color: white;
    border-color: #ff0000;
}

.btn-highlight:hover {
    background: #cc0000;
    color: white;
    border-color: #cc0000;
}

.btn-small {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    border: 1px solid var(--border-color);
    background: white;
    color: var(--secondary);
    text-decoration: none;
    transition: all 0.2s;
}

.btn-small:hover {
    border-color: var(--primary-light);
}

.btn-edit:hover {
    background: var(--primary-light);
    color: white;
}

.btn-events:hover {
    background: #10b981;
    color: white;
}

.btn-score:hover {
    background: #f59e0b;
    color: white;
}

.btn-delete:hover {
    background: #dc2626;
    color: white;
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

/* Pagination */
.pagination-container {
    padding: 0.75rem 1rem;
    border-top: 1px solid var(--border-color);
    background: #f8fafc;
}

.pagination-info {
    font-size: 0.75rem;
    color: var(--secondary);
}

.pagination-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
}

.pagination-btn {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 4px;
    border: 1px solid var(--border-color);
    background: white;
    color: var(--secondary);
    text-decoration: none;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.pagination-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f8fafc;
}

.pagination-pages {
    display: flex;
    gap: 0.25rem;
}

.page-link {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 4px;
    border: 1px solid var(--border-color);
    background: white;
    color: var(--secondary);
    text-decoration: none;
    transition: all 0.2s;
    min-width: 32px;
    text-align: center;
}

.page-link:hover {
    background: #f8fafc;
    color: var(--primary);
    border-color: var(--primary-light);
}

.page-link.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.page-link.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Alert */
.alert {
    border-radius: 6px;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    margin-bottom: 1rem;
    border: none;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
}

/* Modal */
.modal-content {
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.modal-header {
    border-bottom: 1px solid var(--border-color);
    padding: 1rem;
}

.modal-body {
    padding: 1.5rem;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .search-box {
        width: 100%;
    }

    .filter-select {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .stats-grid {
        grid-template-columns: 1fr 1fr;
    }

    .table-responsive {
        font-size: 0.75rem;
    }

    .table thead th,
    .table tbody td {
        padding: 0.5rem;
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
        text-align: center !important;
        flex: none;
    }

    .pagination-container {
        flex-direction: column;
        gap: 0.75rem;
    }

    .pagination-controls {
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination-pages {
        order: -1;
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 0.5rem;
    }
}

.team-logo-modal {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    border: 2px solid var(--border-color);
    overflow: hidden;
    margin: 0 auto;
    background: #f8fafc;
}

.team-logo-modal img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.team-initial-modal {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    font-weight: 700;
    font-size: 1.25rem;
}

/* Highlight button colors */
.btn-highlight {
    background: #8b5cf6;
    color: white;
    border-color: #8b5cf6;
}

.btn-highlight:hover {
    background: #7c3aed;
    color: white;
    border-color: #7c3aed;
}

.btn-upload {
    background: #10b981;
    color: white;
    border-color: #10b981;
}

.btn-upload:hover {
    background: #059669;
    color: white;
    border-color: #059669;
}

.team-modal-info {
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Additional styles for highlight modals */
.file-upload-area {
    border: 2px dashed #e5e7eb;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    background: #f8fafc;
    transition: border-color 0.3s;
}

.file-upload-area:hover {
    border-color: var(--primary-light);
}

.file-upload-area input[type="file"] {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 6px;
}

#videoPreview video {
    max-height: 200px;
    background: #000;
}

#highlightPlayerContainer video {
    width: 100%;
    height: auto;
    background: #000;
}

.ratio-16x9 {
    position: relative;
    padding-bottom: 56.25%;
    /* 16:9 Aspect Ratio */
    height: 0;
    overflow: hidden;
}

.ratio-16x9 video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
}

@media (max-width: 576px) {
    .team-logo-modal {
        width: 40px;
        height: 40px;
    }

    .team-initial-modal {
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-wrap: wrap;
        justify-content: center;
    }

    .modal-dialog {
        margin: 0.5rem;
    }
}
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb" style="font-size: 0.875rem; padding: 0; background: none;">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Matches</li>
    </ol>
</nav>

<div class="page-header">
    <h1>
        <!-- <i class="bi bi-calendar-event me-2"></i> -->
        Matches
    </h1>
    <div class="d-flex gap-2">
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" placeholder="Search matches..." class="form-control form-control-sm">
        </div>
        <a href="{{ route('admin.matches.create') }}" class="btn-create">
            <i class="bi bi-plus"></i>
            New Match
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="d-flex align-items-center">
            <div>
                <div class="stat-title">Upcoming</div>
                <div class="stat-value">{{ $matches->where('status', 'upcoming')->count() }}</div>
            </div>
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i class="bi bi-clock"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="d-flex align-items-center">
            <div>
                <div class="stat-title">Ongoing</div>
                <div class="stat-value">{{ $matches->where('status', 'ongoing')->count() }}</div>
            </div>
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="bi bi-play-circle"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="d-flex align-items-center">
            <div>
                <div class="stat-title">Completed</div>
                <div class="stat-value">{{ $matches->where('status', 'completed')->count() }}</div>
            </div>
            <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="d-flex align-items-center">
            <div>
                <div class="stat-title">Total</div>
                <div class="stat-value">{{ $matches->count() }}</div>
            </div>
            <div class="stat-icon" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
                <i class="bi bi-calendar-week"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="d-flex gap-2 mb-3 flex-wrap">
    <select id="statusFilter" class="filter-select">
        <option value="">All Status</option>
        <option value="upcoming">Upcoming</option>
        <option value="ongoing">Ongoing</option>
        <option value="completed">Completed</option>
        <option value="postponed">Postponed</option>
    </select>

    <select id="roundFilter" class="filter-select">
        <option value="">All Rounds</option>
        <option value="group">Group Stage</option>
        <option value="quarterfinal">Quarterfinal</option>
        <option value="semifinal">Semifinal</option>
        <option value="final">Final</option>
    </select>

    <input type="date" id="dateFrom" class="filter-select" placeholder="Date from">

    <button class="btn btn-outline-secondary btn-sm d-flex align-items-center" id="resetFilters">
        <i class="bi bi-arrow-clockwise me-1"></i> Reset
    </button>
</div>

<div class="main-card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list me-2"></i> All Matches</h5>
    </div>

    <div class="card-body p-0">
        @if($matches->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="matchesTable">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Match</th>
                        <th>Stage</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matches as $match)
                    <tr class="match-row" data-status="{{ $match->status }}" data-round="{{ $match->round_type }}"
                        data-date="{{ $match->match_date->format('Y-m-d') }}">
                        <td>
                            <div class="match-date">{{ $match->match_date->format('d M Y') }}</div>
                            <div class="match-time">
                                <i class="bi bi-clock me-1"></i>{{ $match->time_start }}
                            </div>
                        </td>
                        <td>
                            <div class="match-teams">
                                <!-- Home Team -->
                                <div class="team-info">
                                    <div class="team-name">{{ $match->homeTeam->name ?? 'TBA' }}</div>
                                    <div class="team-logo-small">
                                        @if($match->homeTeam && $match->homeTeam->logo)
                                        @if(Storage::disk('public')->exists($match->homeTeam->logo))
                                        <img src="{{ asset('storage/' . $match->homeTeam->logo) }}"
                                            alt="{{ $match->homeTeam->name }}">
                                        @elseif(filter_var($match->homeTeam->logo, FILTER_VALIDATE_URL))
                                        <img src="{{ $match->homeTeam->logo }}" alt="{{ $match->homeTeam->name }}">
                                        @else
                                        <div class="team-initial">
                                            {{ strtoupper(substr($match->homeTeam->name, 0, 1)) }}
                                        </div>
                                        @endif
                                        @else
                                        <div class="team-initial">H</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- VS -->
                                <div class="vs">VS</div>

                                <!-- Away Team -->
                                <div class="team-info">
                                    <div class="team-logo-small">
                                        @if($match->awayTeam && $match->awayTeam->logo)
                                        @if(Storage::disk('public')->exists($match->awayTeam->logo))
                                        <img src="{{ asset('storage/' . $match->awayTeam->logo) }}"
                                            alt="{{ $match->awayTeam->name }}">
                                        @elseif(filter_var($match->awayTeam->logo, FILTER_VALIDATE_URL))
                                        <img src="{{ $match->awayTeam->logo }}" alt="{{ $match->awayTeam->name }}">
                                        @else
                                        <div class="team-initial">
                                            {{ strtoupper(substr($match->awayTeam->name, 0, 1)) }}
                                        </div>
                                        @endif
                                        @else
                                        <div class="team-initial">A</div>
                                        @endif
                                    </div>
                                    <div class="team-name away">{{ $match->awayTeam->name ?? 'TBA' }}</div>
                                </div>
                            </div>
                            @if($match->status === 'completed' && $match->home_score !== null && $match->away_score !==
                            null)
                            <div class="score">
                                {{ $match->home_score }} - {{ $match->away_score }}
                            </div>
                            @endif
                        </td>
                        <td>
                            @if($match->round_type === 'group')
                            <span class="badge badge-group">
                                {{ $match->group_name }}
                            </span>
                            @else
                            <span class="badge badge-knockout">
                                {{ ucfirst($match->round_type) }}
                            </span>
                            @endif
                        </td>
                        <td>
                            @if($match->status === 'upcoming')
                            <span class="status-badge status-upcoming">Upcoming</span>
                            @elseif($match->status === 'ongoing')
                            <span class="status-badge status-ongoing">Ongoing</span>
                            @elseif($match->status === 'completed')
                            <span class="status-badge status-completed">Completed</span>
                            @else
                            <span class="status-badge status-postponed">Postponed</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="action-buttons">
                                <a href="{{ route('admin.matches.edit', $match) }}" class="btn-small btn-edit"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.matches.events.index', $match) }}" class="btn-small btn-events"
                                    title="Events">
                                    <i class="bi bi-activity"></i>
                                </a>
                                <button type="button" class="btn-small btn-score update-score-btn" title="Update Score"
                                    data-match-id="{{ $match->id }}"
                                    data-home-team="{{ $match->homeTeam->name ?? 'Home Team' }}"
                                    data-away-team="{{ $match->awayTeam->name ?? 'Away Team' }}"
                                    data-home-score="{{ $match->home_score ?? 0 }}"
                                    data-away-score="{{ $match->away_score ?? 0 }}"
                                    data-update-url="{{ route('admin.matches.update-score', $match) }}">
                                    <i class="bi bi-bar-chart"></i>
                                </button>
                                <form action="{{ route('admin.matches.destroy', $match) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small btn-delete"
                                        onclick="return confirm('Delete this match?')" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                                @if($match->status === 'completed')
                                <!-- Tombol YouTube Highlight -->
                                <button type="button" class="btn-small btn-highlight youtube-highlight-btn"
                                    title="YouTube Highlight" data-match-id="{{ $match->id }}"
                                    data-match-teams="{{ ($match->homeTeam->name ?? 'Home') . ' vs ' . ($match->awayTeam->name ?? 'Away') }}"
                                    data-has-highlight="{{ $match->youtube_id ? 'true' : 'false' }}"
                                    data-youtube-id="{{ $match->youtube_id }}">
                                    <i class="bi bi-youtube"></i>
                                </button>
                                @endif



                                <!-- YouTube Highlight Modal -->
                                <div class="modal fade" id="youtubeHighlightModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-youtube text-danger"></i> YouTube Highlight
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form id="youtubeHighlightForm">
                                                @csrf
                                                <input type="hidden" name="match_id" id="youtubeMatchId">
                                                <input type="hidden" name="_method" id="youtubeMethod" value="POST">

                                                <div class="modal-body">
                                                    <div class="alert alert-info mb-3">
                                                        <i class="bi bi-info-circle"></i>
                                                        <span id="youtubeModalTeams"></span>
                                                    </div>

                                                    <!-- Current Highlight (if exists) -->
                                                    <div id="currentHighlightSection" class="mb-3">
                                                        <div class="card">
                                                            <div
                                                                class="card-header bg-light d-flex justify-content-between align-items-center">
                                                                <h6 class="mb-0">Current Highlight</h6>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    id="removeHighlightBtn">
                                                                    <i class="bi bi-trash"></i> Remove Highlight
                                                                </button>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="ratio ratio-16x9 mb-2"
                                                                    id="currentVideoContainer">
                                                                    <!-- YouTube embed akan dimuat di sini -->
                                                                </div>
                                                                <small class="text-muted" id="currentVideoInfo"></small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Add/Update Highlight Form -->
                                                    <div id="addHighlightForm">
                                                        <!-- YouTube URL Input -->
                                                        <div class="mb-3">
                                                            <label for="youtube_url" class="form-label fw-bold">YouTube
                                                                URL *</label>
                                                            <input type="url" name="youtube_url" id="youtube_url"
                                                                class="form-control"
                                                                placeholder="https://www.youtube.com/watch?v=..."
                                                                required>
                                                            <div class="form-text">
                                                                <i class="bi bi-info-circle"></i>
                                                                Supported formats: youtube.com/watch?v=...,
                                                                youtu.be/..., youtube.com/embed/...
                                                            </div>
                                                        </div>

                                                        <!-- Preview (akan muncul setelah URL valid) -->
                                                        <div id="youtubePreview" class="d-none">
                                                            <div class="card">
                                                                <div class="card-header bg-light">
                                                                    <h6 class="mb-0">Preview</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="ratio ratio-16x9 mb-2"
                                                                        id="previewContainer">
                                                                        <!-- Preview embed akan dimuat di sini -->
                                                                    </div>
                                                                    <small class="text-muted" id="previewInfo"></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Status Messages -->
                                                    <div id="youtubeStatus" class="d-none"></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary" id="saveYoutubeBtn">
                                                        <span id="saveButtonText">Save</span>
                                                        <span class="spinner-border spinner-border-sm d-none"
                                                            id="saveSpinner"></span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($matches->hasPages())
        <div class="pagination-container d-flex justify-content-between align-items-center flex-wrap">
            <div class="pagination-info">
                Showing {{ $matches->firstItem() }} to {{ $matches->lastItem() }} of {{ $matches->total() }} matches
            </div>

            <div class="pagination-controls">
                <!-- Previous Button -->
                @if($matches->onFirstPage())
                <span class="pagination-btn disabled">
                    <i class="bi bi-chevron-left"></i> Previous
                </span>
                @else
                <a href="{{ $matches->previousPageUrl() }}" class="pagination-btn">
                    <i class="bi bi-chevron-left"></i> Previous
                </a>
                @endif

                <!-- Page Numbers -->
                <div class="pagination-pages">
                    @php
                    $current = $matches->currentPage();
                    $last = $matches->lastPage();
                    $start = max(1, $current - 2);
                    $end = min($last, $current + 2);

                    if ($start > 1) {
                    echo '<a href="' . $matches->url(1) . '" class="page-link">1</a>';
                    if ($start > 2) {
                    echo '<span class="page-link disabled">...</span>';
                    }
                    }

                    for ($i = $start; $i <= $end; $i++) { if ($i==$current) { echo '<span class="page-link active">' .
                        $i . '</span>' ; } else { echo '<a href="' . $matches->url($i) . '" class="page-link">' . $i .
                        '</a>';
                        }
                        }

                        if ($end < $last) { if ($end < $last - 1) { echo '<span class="page-link disabled">...</span>' ;
                            } echo '<a href="' . $matches->url($last) . '" class="page-link">' . $last . '</a>';
                            }
                            @endphp
                </div>

                <!-- Next Button -->
                @if($matches->hasMorePages())
                <a href="{{ $matches->nextPageUrl() }}" class="pagination-btn">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
                @else
                <span class="pagination-btn disabled">
                    Next <i class="bi bi-chevron-right"></i>
                </span>
                @endif
            </div>
        </div>
        @endif
        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-calendar-x"></i>
            </div>
            <h4 class="empty-state-title">No Matches Found</h4>
            <p class="empty-state-text">
                Start by creating your first tournament match.
            </p>
            <a href="{{ route('admin.matches.create') }}" class="btn-create">
                <i class="bi bi-plus"></i>
                Create First Match
            </a>
        </div>
        @endif
    </div>
</div>
</div>

<!-- Score Update Modal -->
<div class="modal fade" id="scoreUpdateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="scoreUpdateForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update Score</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Enter the final score:</p>

                    <!-- Teams with Logos -->
                    <div class="row align-items-center mb-4">
                        <div class="col-5 text-center">
                            <div class="team-modal-info">
                                <div class="team-logo-modal mb-2" id="modalHomeLogo">
                                    <!-- Logo akan diisi oleh JavaScript -->
                                </div>
                                <div class="fw-semibold text-truncate" id="modalHomeTeam"></div>
                            </div>
                        </div>
                        <div class="col-2 text-center">
                            <span class="text-muted fw-bold">VS</span>
                        </div>
                        <div class="col-5 text-center">
                            <div class="team-modal-info">
                                <div class="team-logo-modal mb-2" id="modalAwayLogo">
                                    <!-- Logo akan diisi oleh JavaScript -->
                                </div>
                                <div class="fw-semibold text-truncate" id="modalAwayTeam"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Score Inputs -->
                    <div class="row align-items-center">
                        <div class="col-5">
                            <input type="number" name="home_score" id="modalHomeScore"
                                class="form-control form-control-lg text-center fw-bold" min="0" max="99" required
                                style="font-size: 1.25rem;">
                        </div>
                        <div class="col-2 text-center">
                            <span class="fw-bold fs-4">:</span>
                        </div>
                        <div class="col-5">
                            <input type="number" name="away_score" id="modalAwayScore"
                                class="form-control form-control-lg text-center fw-bold" min="0" max="99" required
                                style="font-size: 1.25rem;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Score</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Highlight Modal -->
<div class="modal fade" id="uploadHighlightModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Match Highlight</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Uploading highlight for: <strong id="uploadMatchTeams"></strong>
                    </div>
                </div>

                <form id="highlightUploadForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="match_id" id="uploadMatchId">

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Video Upload -->
                            <div class="mb-4">
                                <label for="highlight_video" class="form-label fw-bold">Video File *</label>
                                <div class="file-upload-area">
                                    <input type="file" name="highlight_video" id="highlight_video" class="form-control"
                                        accept=".mp4,.mov,.avi,.wmv,.mkv,.flv" required>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i>
                                        Max file size: 200MB. Supported formats: MP4, MOV, AVI, WMV, MKV, FLV
                                    </div>
                                </div>
                            </div>

                            <!-- Thumbnail Upload -->
                            <div class="mb-4">
                                <label for="highlight_thumbnail" class="form-label fw-bold">Thumbnail Image
                                    (Optional)</label>
                                <input type="file" name="highlight_thumbnail" id="highlight_thumbnail"
                                    class="form-control" accept=".jpg,.jpeg,.png,.gif">
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i>
                                    Optional. If not provided, a thumbnail will be generated from the video.
                                    Max 5MB. Supported formats: JPG, PNG, GIF
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Video Preview -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Preview</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div id="videoPreview" class="d-none">
                                        <video id="previewPlayer" width="100%" height="auto" controls class="rounded">
                                            Your browser does not support the video tag.
                                        </video>
                                        <div class="mt-2">
                                            <small id="videoInfo" class="text-muted"></small>
                                        </div>
                                    </div>
                                    <div id="defaultPreview" class="text-muted">
                                        <i class="bi bi-film" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Video preview will appear here</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress mb-3 d-none" id="uploadProgress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                            style="width: 0%"></div>
                    </div>

                    <!-- Status Messages -->
                    <div id="uploadStatus" class="d-none"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitUploadBtn">Upload Highlight</button>
            </div>
        </div>
    </div>
</div>

<!-- View Highlight Modal -->
<div class="modal fade" id="viewHighlightModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Match Highlight</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <h6 id="viewMatchTeams" class="text-primary"></h6>
                </div>

                <div id="highlightPlayerContainer">
                    <!-- Video player will be loaded here -->
                </div>

                <!-- Highlight Info -->
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Video Information</h6>
                                <ul class="list-unstyled">
                                    <li><strong>File Size:</strong> <span id="infoSize"></span></li>
                                    <li><strong>Duration:</strong> <span id="infoDuration"></span></li>
                                    <li><strong>Uploaded:</strong> <span id="infoUploaded"></span></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Actions</h6>
                                <div class="d-grid gap-2">
                                    <a href="#" id="downloadVideoBtn" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-download"></i> Download Video
                                    </a>
                                    <button type="button" id="deleteHighlightBtn" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete Highlight
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

// Update bagian JavaScript di section scripts:

@section('scripts')
<script>
// Filter functionality
const statusFilter = document.getElementById('statusFilter');
const roundFilter = document.getElementById('roundFilter');
const dateFrom = document.getElementById('dateFrom');
const resetFilters = document.getElementById('resetFilters');
const matchRows = document.querySelectorAll('.match-row');

function filterMatches() {
    const status = statusFilter.value;
    const round = roundFilter.value;
    const date = dateFrom.value;

    matchRows.forEach(row => {
        const rowStatus = row.dataset.status;
        const rowRound = row.dataset.round;
        const rowDate = row.dataset.date;

        let show = true;

        if (status && rowStatus !== status) show = false;
        if (round && rowRound !== round) show = false;
        if (date && rowDate < date) show = false;

        row.style.display = show ? '' : 'none';
    });
}

// Event listeners for filters
if (statusFilter) statusFilter.addEventListener('change', filterMatches);
if (roundFilter) roundFilter.addEventListener('change', filterMatches);
if (dateFrom) dateFrom.addEventListener('change', filterMatches);

// Reset filters
if (resetFilters) {
    resetFilters.addEventListener('click', function() {
        statusFilter.value = '';
        roundFilter.value = '';
        dateFrom.value = '';
        filterMatches();
    });
}

// Score Update Modal
let scoreUpdateModal = null;

// Initialize modals after DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const scoreUpdateModalEl = document.getElementById('scoreUpdateModal');
    if (scoreUpdateModalEl) {
        scoreUpdateModal = new bootstrap.Modal(scoreUpdateModalEl);
    }

    const scoreUpdateForm = document.getElementById('scoreUpdateForm');

    // Handle update score button clicks
    document.querySelectorAll('.update-score-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Get data from button attributes
            const homeTeam = this.dataset.homeTeam;
            const awayTeam = this.dataset.awayTeam;
            const homeScore = this.dataset.homeScore;
            const awayScore = this.dataset.awayScore;
            const updateUrl = this.dataset.updateUrl;

            // Set modal data
            document.getElementById('modalHomeTeam').textContent = homeTeam;
            document.getElementById('modalAwayTeam').textContent = awayTeam;
            document.getElementById('modalHomeScore').value = homeScore;
            document.getElementById('modalAwayScore').value = awayScore;

            // Set form action
            if (scoreUpdateForm) {
                scoreUpdateForm.action = updateUrl;
            }

            // Show modal
            if (scoreUpdateModal) {
                scoreUpdateModal.show();
            }
        });
    });
});

// Highlight Upload Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token from form
    function getCsrfToken() {
        // Try multiple sources
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) return csrfMeta.getAttribute('content');

        const csrfInput = document.querySelector('input[name="_token"]');
        if (csrfInput) return csrfInput.value;

        return '';
    }

    const csrfToken = getCsrfToken();

    // Log untuk debugging
    console.log('CSRF Token found:', csrfToken ? 'Yes' : 'No');
    console.log('Modal elements check:', {
        uploadModal: document.getElementById('uploadHighlightModal') ? 'Found' : 'Not found',
        viewModal: document.getElementById('viewHighlightModal') ? 'Found' : 'Not found'
    });

    // Upload Modal
    const uploadModalEl = document.getElementById('uploadHighlightModal');
    const viewModalEl = document.getElementById('viewHighlightModal');

    if (!uploadModalEl || !viewModalEl) {
        console.error('Required modal elements not found');
        return;
    }

    const uploadModal = new bootstrap.Modal(uploadModalEl);
    const viewModal = new bootstrap.Modal(viewModalEl);

    // Video preview functionality
    const videoInput = document.getElementById('highlight_video');
    const videoPreview = document.getElementById('videoPreview');
    const previewPlayer = document.getElementById('previewPlayer');
    const defaultPreview = document.getElementById('defaultPreview');
    const videoInfo = document.getElementById('videoInfo');

    if (videoInput) {
        videoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (!file) return;

            // Validate file size (200MB = 209715200 bytes)
            if (file.size > 209715200) {
                alert('File size exceeds 200MB limit. Please choose a smaller file.');
                this.value = '';
                return;
            }

            // Validate file type
            const validTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo',
                'video/x-ms-wmv', 'video/x-matroska', 'video/x-flv'
            ];
            if (!validTypes.includes(file.type)) {
                alert('Invalid file type. Please upload a video file (MP4, MOV, AVI, WMV, MKV, FLV).');
                this.value = '';
                return;
            }

            // Show preview
            if (previewPlayer) {
                const videoURL = URL.createObjectURL(file);
                previewPlayer.src = videoURL;
                previewPlayer.load();
            }

            // Show file info
            if (videoInfo) {
                const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                videoInfo.textContent = `${file.name} (${fileSize} MB)`;
            }

            // Toggle preview visibility
            if (videoPreview && defaultPreview) {
                videoPreview.classList.remove('d-none');
                defaultPreview.classList.add('d-none');
            }
        });
    }

    // Upload button click handler
    document.querySelectorAll('.upload-highlight-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const matchId = this.dataset.matchId;
            const matchTeams = this.dataset.matchTeams;

            console.log('Upload clicked for match:', matchId);

            // Set modal data
            const uploadMatchId = document.getElementById('uploadMatchId');
            const uploadMatchTeams = document.getElementById('uploadMatchTeams');

            if (uploadMatchId) uploadMatchId.value = matchId;
            if (uploadMatchTeams) uploadMatchTeams.textContent = matchTeams;

            // Reset form and preview
            const highlightForm = document.getElementById('highlightUploadForm');
            if (highlightForm) highlightForm.reset();

            if (videoPreview) videoPreview.classList.add('d-none');
            if (defaultPreview) defaultPreview.classList.remove('d-none');
            if (previewPlayer) previewPlayer.src = '';

            const uploadProgress = document.getElementById('uploadProgress');
            const uploadStatus = document.getElementById('uploadStatus');

            if (uploadProgress) {
                uploadProgress.classList.add('d-none');
                const progressFill = uploadProgress.querySelector('.progress-bar');
                if (progressFill) {
                    progressFill.style.width = '0%';
                    progressFill.textContent = '0%';
                }
            }
            if (uploadStatus) {
                uploadStatus.classList.add('d-none');
                uploadStatus.innerHTML = '';
            }

            // Show modal
            uploadModal.show();
        });
    });

    // View highlight button click handler
    document.querySelectorAll('.view-highlight-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const matchId = this.dataset.matchId;
            const matchTeams = this.dataset.matchTeams;

            console.log('View highlight for match:', matchId);

            // Set modal header
            const viewMatchTeams = document.getElementById('viewMatchTeams');
            if (viewMatchTeams) viewMatchTeams.textContent = matchTeams;

            // Load highlight info via AJAX
            loadHighlightInfo(matchId);

            // Show modal
            viewModal.show();
        });
    });

    // Submit upload button
    const submitUploadBtn = document.getElementById('submitUploadBtn');
    if (submitUploadBtn) {
        submitUploadBtn.addEventListener('click', function() {
            console.log('Submit upload clicked');
            uploadHighlight();
        });
    }

    // Delete highlight button
    const deleteHighlightBtn = document.getElementById('deleteHighlightBtn');
    if (deleteHighlightBtn) {
        deleteHighlightBtn.addEventListener('click', function() {
            const matchId = this.dataset.matchId;
            console.log('Delete highlight for match:', matchId);
            deleteHighlight(matchId);
        });
    }

    // UPLOAD HIGHLIGHT FUNCTION - IMPROVED VERSION
    function uploadHighlight() {
        const matchId = document.getElementById('uploadMatchId')?.value;
        if (!matchId) {
            alert('Match ID not found');
            return;
        }

        const form = document.getElementById('highlightUploadForm');
        if (!form) {
            alert('Form not found');
            return;
        }

        // Validate file input
        const videoFile = document.getElementById('highlight_video')?.files[0];
        if (!videoFile) {
            alert('Please select a video file');
            return;
        }

        // Validate file size
        if (videoFile.size > 209715200) { // 200MB
            alert('File size exceeds 200MB limit');
            return;
        }

        // Create FormData
        const formData = new FormData(form);

        // Show progress bar
        const progressBar = document.getElementById('uploadProgress');
        const progressFill = progressBar ? progressBar.querySelector('.progress-bar') : null;
        const statusDiv = document.getElementById('uploadStatus');

        if (progressBar) {
            progressBar.classList.remove('d-none');
        }
        if (progressFill) {
            progressFill.style.width = '0%';
            progressFill.textContent = '0%';
        }
        if (statusDiv) {
            statusDiv.classList.add('d-none');
            statusDiv.innerHTML = '';
        }

        // Create and configure XMLHttpRequest
        const xhr = new XMLHttpRequest();

        // DEBUG: Log the URL we're trying to access
        const uploadUrl = `/admin/matches/${matchId}/upload-highlight`;
        console.log('Uploading to URL:', uploadUrl);

        xhr.open('POST', uploadUrl);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        // Add CSRF token to headers if available
        if (csrfToken) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        }

        // Progress tracking
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable && progressFill) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressFill.style.width = percentComplete + '%';
                progressFill.textContent = Math.round(percentComplete) + '%';
            }
        });

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                console.log('Response received:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseType: xhr.responseType,
                    responseText: xhr.responseText.substring(0, 500) // First 500 chars
                });

                try {
                    // Try to parse as JSON
                    const response = JSON.parse(xhr.responseText);

                    if (xhr.status === 200 || xhr.status === 201) {
                        // Success
                        if (statusDiv) {
                            statusDiv.innerHTML = `
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle"></i> ${response.message || 'Upload successful!'}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            `;
                            statusDiv.classList.remove('d-none');
                        }

                        // Close modal after delay
                        setTimeout(() => {
                            uploadModal.hide();
                            location.reload();
                        }, 2000);

                    } else {
                        // Server returned error
                        handleUploadError(statusDiv, progressBar,
                            response.message || `Server error: ${xhr.status}`);
                    }

                } catch (error) {
                    // Response is not JSON (likely HTML error page)
                    console.error('Failed to parse JSON:', error);

                    let errorMessage = 'Server error occurred';

                    // Try to extract error from HTML
                    if (xhr.responseText.includes('404')) {
                        errorMessage = 'Route not found (404). Please check if the upload endpoint exists.';
                    } else if (xhr.responseText.includes('403')) {
                        errorMessage = 'Access forbidden (403). You may need to re-login.';
                    } else if (xhr.responseText.includes('500')) {
                        errorMessage = 'Internal server error (500). Please try again later.';
                    } else if (xhr.responseText.includes('419')) {
                        errorMessage = 'Session expired (419). Please refresh the page and try again.';
                    }

                    handleUploadError(statusDiv, progressBar, errorMessage);
                }
            }
        };

        xhr.onerror = function() {
            console.error('Network error during upload');
            handleUploadError(statusDiv, progressBar, 'Network error. Please check your connection.');
        };

        xhr.send(formData);
    }

    // Helper function for upload errors
    function handleUploadError(statusDiv, progressBar, message) {
        console.error('Upload error:', message);

        if (statusDiv) {
            statusDiv.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            statusDiv.classList.remove('d-none');
        }

        if (progressBar) {
            progressBar.classList.add('d-none');
        }
    }

    // LOAD HIGHLIGHT INFO - IMPROVED VERSION
    function loadHighlightInfo(matchId) {
        const playerContainer = document.getElementById('highlightPlayerContainer');
        const infoSize = document.getElementById('infoSize');
        const infoDuration = document.getElementById('infoDuration');
        const infoUploaded = document.getElementById('infoUploaded');
        const downloadBtn = document.getElementById('downloadVideoBtn');
        const deleteBtn = document.getElementById('deleteHighlightBtn');

        // Clear previous content
        if (playerContainer) playerContainer.innerHTML =
            '<div class="text-center p-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading highlight...</p></div>';
        if (infoSize) infoSize.textContent = 'Loading...';
        if (infoDuration) infoDuration.textContent = 'Loading...';
        if (infoUploaded) infoUploaded.textContent = 'Loading...';
        if (downloadBtn) downloadBtn.href = '#';
        if (deleteBtn) deleteBtn.dataset.matchId = matchId;

        fetch(`/admin/matches/${matchId}/highlight-info`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Highlight info response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server did not return JSON');
                }

                return response.json();
            })
            .then(data => {
                console.log('Highlight info data:', data);

                if (data.success && data.data) {
                    const highlight = data.data;

                    // Set info
                    if (infoSize) infoSize.textContent = highlight.size || 'N/A';
                    if (infoDuration) infoDuration.textContent = highlight.duration || 'N/A';
                    if (infoUploaded) infoUploaded.textContent = highlight.uploaded_at || 'N/A';

                    // Set download link
                    if (downloadBtn && highlight.video_url) {
                        downloadBtn.href = highlight.video_url;
                        downloadBtn.target = '_blank';
                    }

                    // Create video player
                    if (playerContainer && highlight.video_url) {
                        playerContainer.innerHTML = `
                        <div class="ratio ratio-16x9">
                            <video controls class="rounded" poster="${highlight.thumbnail_url || ''}" style="background: #000;">
                                <source src="${highlight.video_url}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    `;
                    }
                } else {
                    throw new Error(data.message || 'Failed to load highlight info');
                }
            })
            .catch(error => {
                console.error('Error loading highlight info:', error);

                if (playerContainer) {
                    playerContainer.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> ${error.message || 'Failed to load highlight information'}
                    </div>
                `;
                }

                if (infoSize) infoSize.textContent = 'Error';
                if (infoDuration) infoDuration.textContent = 'Error';
                if (infoUploaded) infoUploaded.textContent = 'Error';
            });
    }

    // DELETE HIGHLIGHT - IMPROVED VERSION
    function deleteHighlight(matchId) {
        if (!confirm('Are you sure you want to delete this highlight? This action cannot be undone.')) {
            return;
        }

        // Create FormData for DELETE request
        const formData = new FormData();
        formData.append('_method', 'DELETE');

        // Add CSRF token
        const csrfInput = document.querySelector('#highlightUploadForm input[name="_token"]');
        if (csrfInput && csrfInput.value) {
            formData.append('_token', csrfInput.value);
        } else if (csrfToken) {
            formData.append('_token', csrfToken);
        }

        fetch(`/admin/matches/${matchId}/delete-highlight`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server did not return JSON');
                }

                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Highlight deleted successfully');
                    viewModal.hide();
                    location.reload();
                } else {
                    throw new Error(data.message || 'Failed to delete highlight');
                }
            })
            .catch(error => {
                console.error('Error deleting highlight:', error);
                alert(`Error deleting highlight: ${error.message}`);
            });
    }
});

// Auto-dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.alert:not(.alert-permanent)').forEach(alert => {
        try {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        } catch (e) {
            // Silently fail
        }
    });
}, 5000);

// YouTube Highlight Modal
const youtubeModalEl = document.getElementById('youtubeHighlightModal');
const youtubeModal = youtubeModalEl ? new bootstrap.Modal(youtubeModalEl) : null;
const youtubeForm = document.getElementById('youtubeHighlightForm');
const removeHighlightBtn = document.getElementById('removeHighlightBtn');
const currentHighlightSection = document.getElementById('currentHighlightSection');
const addHighlightForm = document.getElementById('addHighlightForm');

// Handle YouTube highlight button clicks
document.querySelectorAll('.youtube-highlight-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();

        const matchId = this.dataset.matchId;
        const matchTeams = this.dataset.matchTeams;
        const hasHighlight = this.dataset.hasHighlight === 'true';
        const youtubeId = this.dataset.youtubeId;

        console.log('YouTube highlight clicked:', {
            matchId,
            hasHighlight,
            youtubeId
        });

        // Reset form
        if (youtubeForm) youtubeForm.reset();

        // Set modal data
        const youtubeMatchId = document.getElementById('youtubeMatchId');
        const youtubeModalTeams = document.getElementById('youtubeModalTeams');
        const saveButtonText = document.getElementById('saveButtonText');
        const youtubeMethod = document.getElementById('youtubeMethod');
        const youtubeUrlInput = document.getElementById('youtube_url');

        if (youtubeMatchId) youtubeMatchId.value = matchId;
        if (youtubeModalTeams) youtubeModalTeams.textContent = matchTeams;

        // Tampilkan form yang sesuai
        if (hasHighlight && youtubeId) {
            // Ada highlight, tampilkan current highlight dan tombol delete
            if (currentHighlightSection) currentHighlightSection.classList.remove('d-none');
            if (addHighlightForm) addHighlightForm.classList.add('d-none');

            // Load current highlight
            loadCurrentHighlight(matchId);

            // Set form method untuk update
            if (youtubeMethod) youtubeMethod.value = 'PUT';
            if (saveButtonText) saveButtonText.textContent = 'Update';
        } else {
            // Tidak ada highlight, tampilkan form untuk add
            if (currentHighlightSection) currentHighlightSection.classList.add('d-none');
            if (addHighlightForm) addHighlightForm.classList.remove('d-none');

            // Set form method untuk create
            if (youtubeMethod) youtubeMethod.value = 'POST';
            if (saveButtonText) saveButtonText.textContent = 'Save';

            // Reset preview
            const previewSection = document.getElementById('youtubePreview');
            if (previewSection) previewSection.classList.add('d-none');
        }

        // Show modal
        if (youtubeModal) youtubeModal.show();
    });
});

// Load current highlight information
// GANTI fungsi loadCurrentHighlight dengan yang ini:
function loadCurrentHighlight(matchId) {
    const currentVideoContainer = document.getElementById('currentVideoContainer');
    const currentVideoInfo = document.getElementById('currentVideoInfo');
    const youtubeUrlInput = document.getElementById('youtube_url');

    if (!currentVideoContainer || !currentVideoInfo) return;

    // Tampilkan loading
    currentVideoContainer.innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Loading highlight...</p>
        </div>
    `;
    currentVideoInfo.textContent = 'Loading...';

    // Coba load dari data yang sudah ada di tombol
    const youtubeBtn = document.querySelector(`.youtube-highlight-btn[data-match-id="${matchId}"]`);
    const youtubeId = youtubeBtn ? youtubeBtn.dataset.youtubeId : null;

    if (youtubeId) {
        // Jika ada youtubeId langsung di tombol, gunakan itu
        displayYoutubeEmbed(youtubeId);

        if (youtubeUrlInput) {
            youtubeUrlInput.value = `https://www.youtube.com/watch?v=${youtubeId}`;
        }

        // Coba juga fetch dari API untuk info tambahan
        fetchYoutubeInfo(matchId, youtubeId);
    } else {
        // Coba fetch dari API
        fetch(`/admin/matches/${matchId}/youtube-info`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                // Cek content type
                const contentType = response.headers.get('content-type');

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status} - ${response.statusText}`);
                }

                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // Jika bukan JSON, coba parsing sebagai HTML atau text
                    return response.text().then(text => {
                        console.error('Server returned non-JSON:', text.substring(0, 200));
                        throw new Error('Server returned non-JSON response');
                    });
                }
            })
            .then(data => {
                if (data.success && data.data && data.data.youtube_id) {
                    const highlight = data.data;
                    displayYoutubeEmbed(highlight.youtube_id);
                    currentVideoInfo.textContent =
                        `Video ID: ${highlight.youtube_id} | Uploaded: ${highlight.uploaded_relative || 'N/A'}`;

                    if (youtubeUrlInput) {
                        youtubeUrlInput.value = `https://www.youtube.com/watch?v=${highlight.youtube_id}`;
                        validateYoutubeUrl(youtubeUrlInput.value);
                    }
                } else {
                    showNoHighlight();
                }
            })
            .catch(error => {
                console.warn('Error loading from API, using button data:', error);

                // Fallback ke data dari tombol
                if (youtubeBtn && youtubeBtn.dataset.youtubeId) {
                    const fallbackYoutubeId = youtubeBtn.dataset.youtubeId;
                    displayYoutubeEmbed(fallbackYoutubeId);
                    currentVideoInfo.textContent = `Video ID: ${fallbackYoutubeId}`;

                    if (youtubeUrlInput) {
                        youtubeUrlInput.value = `https://www.youtube.com/watch?v=${fallbackYoutubeId}`;
                        validateYoutubeUrl(youtubeUrlInput.value);
                    }
                } else {
                    showNoHighlight();
                }
            });
    }

    // Helper function untuk menampilkan embed
    function displayYoutubeEmbed(youtubeId) {
        currentVideoContainer.innerHTML = `
            <iframe src="https://www.youtube.com/embed/${youtubeId}?rel=0&showinfo=0&modestbranding=1" 
                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen style="border-radius: 4px; width: 100%; height: 100%;">
            </iframe>
        `;
    }

    // Helper function untuk fetch info tambahan
    function fetchYoutubeInfo(matchId, youtubeId) {
        fetch(`/admin/matches/${matchId}/youtube-info`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.ok ? response.json().catch(() => null) : null)
            .then(data => {
                if (data && data.success && data.data) {
                    const highlight = data.data;
                    currentVideoInfo.textContent =
                        `Video ID: ${highlight.youtube_id} | Uploaded: ${highlight.uploaded_relative || 'N/A'}`;
                }
            })
            .catch(() => {
                // Ignore error for additional info
            });
    }

    // Helper function untuk menampilkan no highlight
    function showNoHighlight() {
        currentVideoContainer.innerHTML = `
            <div class="alert alert-warning p-3 text-center">
                <i class="bi bi-exclamation-triangle"></i> No highlight available
            </div>
        `;
        currentVideoInfo.textContent = 'No highlight available';
    }
}

// Fungsi untuk extract YouTube ID dari URL
function extractYoutubeId(url) {
    if (!url) return null;

    const patterns = [
        /youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/,
        /youtu\.be\/([a-zA-Z0-9_-]{11})/,
        /youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/,
        /youtube\.com\/v\/([a-zA-Z0-9_-]{11})/,
        /youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/
    ];

    for (const pattern of patterns) {
        const match = url.match(pattern);
        if (match) return match[1];
    }
    return null;
}

// Perbaikan fungsi validateYoutubeUrl
function validateYoutubeUrl(url) {
    const previewSection = document.getElementById('youtubePreview');
    const previewContainer = document.getElementById('previewContainer');
    const previewInfo = document.getElementById('previewInfo');

    if (!url || !previewSection || !previewContainer) {
        if (previewSection) previewSection.classList.add('d-none');
        return false;
    }

    const videoId = extractYoutubeId(url);

    if (!videoId) {
        previewSection.classList.add('d-none');
        showYoutubeStatus('Invalid YouTube URL format', 'danger');
        return false;
    }

    // Show preview
    previewContainer.innerHTML = `
        <iframe src="https://www.youtube.com/embed/${videoId}?rel=0&showinfo=0&modestbranding=1" 
                frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen style="border-radius: 4px; width: 100%; height: 100%;">
        </iframe>
    `;

    if (previewInfo) {
        previewInfo.textContent = `Video ID: ${videoId}`;
    }

    previewSection.classList.remove('d-none');
    showYoutubeStatus('Valid YouTube URL detected', 'success');

    // Update form method dan button jika ada current video
    const currentVideoId = document.querySelector('.youtube-highlight-btn[data-match-id]')?.dataset.youtubeId;
    if (currentVideoId) {
        const youtubeMethod = document.getElementById('youtubeMethod');
        const saveButtonText = document.getElementById('saveButtonText');

        if (youtubeMethod) youtubeMethod.value = 'PUT';
        if (saveButtonText) saveButtonText.textContent = 'Update';
    }

    return true;
}

// Handle remove highlight button
if (removeHighlightBtn) {
    removeHighlightBtn.addEventListener('click', function() {
        const matchId = document.getElementById('youtubeMatchId')?.value;

        if (!matchId) {
            alert('Match ID not found');
            return;
        }

        if (!confirm('Are you sure you want to delete this YouTube highlight? This action cannot be undone.')) {
            return;
        }

        // Get CSRF token safely
        const csrfToken = getCsrfToken();
        if (!csrfToken) {
            alert('Security token not found. Please refresh the page.');
            return;
        }

        // Show loading state
        const originalText = this.innerHTML;
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Deleting...';
        this.disabled = true;

        fetch(`/admin/matches/${matchId}/youtube-highlight`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Tampilkan success message
                    showYoutubeStatus('YouTube highlight deleted successfully!', 'success');

                    // Refresh page setelah delay
                    setTimeout(() => {
                        if (youtubeModal) youtubeModal.hide();
                        location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Error deleting highlight');
                }
            })
            .catch(error => {
                console.error('Error deleting highlight:', error);
                showYoutubeStatus('Error deleting highlight: ' + error.message, 'danger');

                // Restore button state
                this.innerHTML = originalText;
                this.disabled = false;
            });
    });
}

// YouTube URL validation and preview
const youtubeUrlInput = document.getElementById('youtube_url');
if (youtubeUrlInput) {
    youtubeUrlInput.addEventListener('blur', function() {
        validateYoutubeUrl(this.value);
    });

    youtubeUrlInput.addEventListener('input', function() {
        if (!this.value) {
            const previewSection = document.getElementById('youtubePreview');
            if (previewSection) previewSection.classList.add('d-none');
        }
    });
}

// Validate YouTube URL and show preview
function validateYoutubeUrl(url) {
    const previewSection = document.getElementById('youtubePreview');
    const previewContainer = document.getElementById('previewContainer');
    const previewInfo = document.getElementById('previewInfo');

    if (!url || !previewSection || !previewContainer) {
        if (previewSection) previewSection.classList.add('d-none');
        return false;
    }

    // Simple YouTube URL validation
    const youtubePatterns = [
        /youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/,
        /youtu\.be\/([a-zA-Z0-9_-]{11})/,
        /youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/,
        /youtube\.com\/v\/([a-zA-Z0-9_-]{11})/,
        /youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/
    ];

    let videoId = null;
    for (const pattern of youtubePatterns) {
        const match = url.match(pattern);
        if (match) {
            videoId = match[1];
            break;
        }
    }

    if (!videoId) {
        previewSection.classList.add('d-none');
        showYoutubeStatus('Invalid YouTube URL format', 'danger');
        return false;
    }

    // Show preview
    previewContainer.innerHTML = `
        <iframe src="https://www.youtube.com/embed/${videoId}?rel=0&showinfo=0&modestbranding=1" 
                frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen style="border-radius: 4px;">
        </iframe>
    `;

    if (previewInfo) {
        previewInfo.textContent = `Video ID: ${videoId}`;
    }

    previewSection.classList.remove('d-none');
    showYoutubeStatus('Valid YouTube URL detected', 'success');
    return true;
}

// Helper function to get CSRF token safely
function getCsrfToken() {
    // Try multiple sources
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (csrfMeta) return csrfMeta.getAttribute('content');

    const csrfInput = document.querySelector('input[name="_token"]');
    if (csrfInput) return csrfInput.value;

    // Try to find token in forms
    const forms = document.querySelectorAll('form');
    for (const form of forms) {
        const tokenInput = form.querySelector('input[name="_token"]');
        if (tokenInput) return tokenInput.value;
    }

    console.warn('CSRF token not found');
    return '';
}

// Handle form submission
if (youtubeForm) {
    youtubeForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const matchId = document.getElementById('youtubeMatchId')?.value;
        const youtubeUrl = document.getElementById('youtube_url')?.value;
        const method = document.getElementById('youtubeMethod')?.value;

        if (!matchId || !method) {
            showYoutubeStatus('Missing required information', 'danger');
            return;
        }

        // Untuk update, URL tidak wajib jika hanya ingin melihat current
        if (method === 'POST' && !youtubeUrl) {
            showYoutubeStatus('Please enter a valid YouTube URL', 'danger');
            return;
        }

        if (method === 'POST' && !validateYoutubeUrl(youtubeUrl)) {
            showYoutubeStatus('Please enter a valid YouTube URL', 'danger');
            return;
        }

        const saveBtn = document.getElementById('saveYoutubeBtn');
        const saveSpinner = document.getElementById('saveSpinner');
        const saveButtonText = document.getElementById('saveButtonText');

        // Show loading state
        if (saveBtn) saveBtn.disabled = true;
        if (saveSpinner) saveSpinner?.classList.remove('d-none');
        if (saveButtonText) saveButtonText?.classList.add('d-none');

        // Get CSRF token safely
        const csrfToken = getCsrfToken();
        if (!csrfToken) {
            showYoutubeStatus('Security token not found. Please refresh the page.', 'danger');
            if (saveBtn) saveBtn.disabled = false;
            if (saveSpinner) saveSpinner?.classList.add('d-none');
            if (saveButtonText) saveButtonText?.classList.remove('d-none');
            return;
        }

        // Prepare request
        const url = `/admin/matches/${matchId}/youtube-highlight`;

        const formData = new FormData();
        if (youtubeUrl) {
            formData.append('youtube_url', youtubeUrl);
        }
        formData.append('_method', method);
        formData.append('_token', csrfToken);

        fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showYoutubeStatus(data.message || 'YouTube highlight saved successfully!', 'success');

                    // Close modal and reload page after delay
                    setTimeout(() => {
                        if (youtubeModal) youtubeModal.hide();
                        location.reload();
                    }, 1500);
                } else {
                    showYoutubeStatus(data.message || 'Error saving highlight', 'danger');
                }
            })
            .catch(error => {
                console.error('Error saving highlight:', error);
                showYoutubeStatus('Network error occurred: ' + error.message, 'danger');
            })
            .finally(() => {
                // Restore button state
                if (saveBtn) saveBtn.disabled = false;
                if (saveSpinner) saveSpinner?.classList.add('d-none');
                if (saveButtonText) saveButtonText?.classList.remove('d-none');
            });
    });
}

// Helper function to show status messages
function showYoutubeStatus(message, type = 'info') {
    const statusDiv = document.getElementById('youtubeStatus');
    if (!statusDiv) return;

    const alertClass = type === 'success' ? 'alert-success' :
        type === 'danger' ? 'alert-danger' :
        type === 'warning' ? 'alert-warning' : 'alert-info';

    statusDiv.innerHTML = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="bi ${type === 'success' ? 'bi-check-circle' : 
                          type === 'danger' ? 'bi-exclamation-triangle' : 
                          'bi-info-circle'}"></i> 
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    statusDiv.classList.remove('d-none');

    // Auto-hide after 5 seconds (except for success which will auto-close)
    if (type !== 'success') {
        setTimeout(() => {
            statusDiv.classList.add('d-none');
        }, 5000);
    }
}

// Function to toggle form visibility
function toggleHighlightForm(showAddForm) {
    if (showAddForm) {
        if (currentHighlightSection) currentHighlightSection.classList.add('d-none');
        if (addHighlightForm) addHighlightForm.classList.remove('d-none');
    } else {
        if (currentHighlightSection) currentHighlightSection.classList.remove('d-none');
        if (addHighlightForm) addHighlightForm.classList.add('d-none');
    }
}
</script>
@endsection