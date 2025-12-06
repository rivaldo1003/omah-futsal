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

            .team-modal-info {
                display: flex;
                flex-direction: column;
                align-items: center;
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
                                        @if($match->status === 'completed' && $match->home_score !== null && $match->away_score !== null)
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

                                    for ($i = $start; $i <= $end; $i++) {
                                        if ($i == $current) {
                                            echo '<span class="page-link active">' . $i . '</span>';
                                        } else {
                                            echo '<a href="' . $matches->url($i) . '" class="page-link">' . $i . '</a>';
                                        }
                                    }

                                    if ($end < $last) {
                                        if ($end < $last - 1) {
                                            echo '<span class="page-link disabled">...</span>';
                                        }
                                        echo '<a href="' . $matches->url($last) . '" class="page-link">' . $last . '</a>';
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
@endsection

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
            resetFilters.addEventListener('click', function () {
                statusFilter.value = '';
                roundFilter.value = '';
                dateFrom.value = '';
                filterMatches();
            });
        }

        // Score Update Modal
        const scoreUpdateModal = new bootstrap.Modal(document.getElementById('scoreUpdateModal'));
        const scoreUpdateForm = document.getElementById('scoreUpdateForm');

        // Handle update score button clicks
        document.querySelectorAll('.update-score-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                // Get data from button attributes
                const homeTeam = this.dataset.homeTeam;
                const awayTeam = this.dataset.awayTeam;
                const homeScore = this.dataset.homeScore;
                const awayScore = this.dataset.awayScore;
                const updateUrl = this.dataset.updateUrl;

                // Get team IDs for logo data
                const homeTeamId = this.dataset.homeTeamId;
                const awayTeamId = this.dataset.awayTeamId;
                const homeTeamLogo = this.dataset.homeTeamLogo;
                const awayTeamLogo = this.dataset.awayTeamLogo;

                // Set modal data
                document.getElementById('modalHomeTeam').textContent = homeTeam;
                document.getElementById('modalAwayTeam').textContent = awayTeam;
                document.getElementById('modalHomeScore').value = homeScore;
                document.getElementById('modalAwayScore').value = awayScore;

                // Set team logos
                setTeamLogo('modalHomeLogo', homeTeam, homeTeamLogo);
                setTeamLogo('modalAwayLogo', awayTeam, awayTeamLogo);

                // Set form action
                document.getElementById('scoreUpdateForm').action = updateUrl;

                // Show modal
                new bootstrap.Modal(document.getElementById('scoreUpdateModal')).show();
            });
        });

        function setTeamLogo(elementId, teamName, logoPath) {
            const element = document.getElementById(elementId);
            if (!element) return;

            element.innerHTML = ''; // Clear existing content

            if (logoPath) {
                // Check if it's a URL or local path
                let imgSrc = logoPath;
                if (!logoPath.startsWith('http') && !logoPath.startsWith('//')) {
                    imgSrc = '/storage/' + logoPath;
                }

                const img = document.createElement('img');
                img.src = imgSrc;
                img.alt = teamName;
                img.title = teamName;
                img.onerror = function () {
                    // If image fails to load, show initial
                    element.innerHTML = `<div class="team-initial-modal">${getInitial(teamName)}</div>`;
                };
                element.appendChild(img);
            } else {
                // No logo, show initial
                element.innerHTML = `<div class="team-initial-modal">${getInitial(teamName)}</div>`;
            }
        }

        function getInitial(teamName) {
            if (!teamName) return '?';
            return teamName.charAt(0).toUpperCase();
        }

        // Auto-dismiss alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endsection