@extends('layouts.admin')

@section('title', 'Matches')

@section('styles')
    <style>
        /* ===== Matches page — design guidelines ===== */

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

        .btn-reset {
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text-secondary);
            border-radius: 6px;
            padding: 0 12px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .btn-reset:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .row-has-extras {
            box-shadow: inset 3px 0 0 var(--accent);
        }

        .search-box {
            position: relative;
        }

        .search-box .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 13px;
            pointer-events: none;
        }

        .search-box input {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 8px 12px 8px 32px;
            font-size: 14px;
            height: 38px;
            width: 240px;
        }

        .search-box input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.12);
            outline: none;
        }

        .btn-create {
            background: var(--accent);
            color: #fff;
            border: none;
            height: 38px;
            padding: 0 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-create:hover {
            background: var(--accent-hover);
            color: #fff;
        }

        .btn-create.btn-friendly {
            background: var(--bg);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-create.btn-friendly:hover {
            background: var(--bg);
        }

        .btn-create.btn-friendly:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        /* Filters row: input & select tinggi seragam 38px agar sejajar */
        .filter-select {
            height: 38px;
        }

        .btn-reset {
            height: 38px;
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

        /* Filters */
        .filter-select {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 0 12px;
            font-size: 14px;
            background: var(--bg);
            color: var(--text-primary);
        }

        .filter-select:focus {
            border-color: var(--accent);
            outline: none;
        }

        /* Filters row: jarak 24px ke card di bawahnya (space-5) */
        .filters-row {
            margin-bottom: 24px;
        }

        /* Main card / table */
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

        #matchesTable thead th {
            background: var(--surface);
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 13px;
            border-bottom: 1px solid var(--border);
            padding: 10px 16px;
            white-space: nowrap;
        }

        #matchesTable tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            color: var(--text-primary);
        }

        #matchesTable tbody tr:last-child td {
            border-bottom: none;
        }

        #matchesTable tbody tr:hover {
            background: var(--surface);
        }

        /* Teams & score */
        .match-teams {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .team-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .team-name {
            font-weight: 500;
            font-size: 14px;
            color: var(--text-primary);
        }

        .team-logo-small {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            overflow: hidden;
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
            background: #F0F0F2;
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 600;
        }

        .vs {
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 500;
        }

        .score-display-admin {
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .score-display-admin .score {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-primary);
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 2px 8px;
            border-radius: 6px;
        }

        .score-display-admin .score.live {
            color: #c01c28;
            border-color: rgba(192, 28, 40, 0.3);
            background: #FDF2F3;
        }

        .extra-info-admin {
            display: flex;
            gap: 4px;
        }

        .extra-info-admin .badge {
            font-size: 11px;
            font-weight: 500;
        }

        /* Badges */
        .match-date {
            font-size: 14px;
            color: var(--text-primary);
            font-weight: 500;
        }

        .match-time {
            font-size: 12px;
            color: var(--text-secondary);
        }

        #matchesTable .badge {
            font-size: 12px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .badge-group,
        .badge-knockout,
        .tournament-badge {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .et-badge,
        .pen-badge {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .live-badge {
            background: #FDF2F3;
            color: #c01c28;
            font-weight: 600;
        }

        #matchesTable .badge[style*="e0e7ff"] {
            background: var(--surface) !important;
            border: 1px solid var(--border);
            color: var(--text-secondary) !important;
        }

        .status-badge {
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 500;
        }

        .status-upcoming {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .status-ongoing {
            background: #FDF6EC;
            color: #B45309;
        }

        .status-completed {
            background: #F0F9F4;
            color: #1E7A46;
        }

        .status-postponed {
            background: var(--surface);
            color: var(--text-secondary);
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 4px;
            justify-content: flex-end;
            align-items: center;
        }

        .btn-small {
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text-secondary);
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: border-color 0.15s ease, color 0.15s ease, background-color 0.15s ease;
        }

        .btn-small:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-small.btn-delete:hover {
            border-color: #c01c28;
            color: #c01c28;
            background: #FDF2F3;
        }

        .btn-small.btn-lineup {
            border-color: var(--border);
            color: var(--text-secondary);
        }

        .btn-small.btn-lineup:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Pagination */
        .pagination-info {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .pagination-btn,
        .pagination-pages .page-link {
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text-primary);
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            margin: 0 2px;
        }

        .pagination-btn:hover,
        .pagination-pages .page-link:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .pagination-btn.disabled,
        .pagination-pages .page-link.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        .pagination-pages .page-link.active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
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
            <h1>Matches</h1>
            <p class="page-subtitle">Kelola dan jadwalkan pertandingan turnamen</p>
        </div>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <div class="search-box">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="searchInput" placeholder="Cari pertandingan..." class="form-control form-control-sm">
            </div>
            <a href="{{ route('admin.matches.create', ['mode' => 'friendly']) }}" class="btn-create btn-friendly">
                <i class="bi bi-plus"></i> Friendly match
            </a>
            <a href="{{ route('admin.matches.create') }}" class="btn-create">
                <i class="bi bi-plus"></i> Buat pertandingan
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

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Upcoming</div>
            <div class="stat-value">{{ $matches->where('status', 'upcoming')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Ongoing</div>
            <div class="stat-value">{{ $matches->where('status', 'ongoing')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Completed</div>
            <div class="stat-value">{{ $matches->where('status', 'completed')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Total</div>
            <div class="stat-value">{{ $matches->count() }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-row d-flex gap-2 flex-wrap align-items-center">
        <select id="tournamentFilter" class="filter-select">
            <option value="">Semua turnamen</option>
            @foreach($tournaments as $tournament)
                <option value="{{ $tournament->id }}" {{ request('tournament_id') == $tournament->id ? 'selected' : '' }}>
                    {{ $tournament->name }}
                </option>
            @endforeach
        </select>

        <select id="statusFilter" class="filter-select">
            <option value="">Semua status</option>
            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="postponed" {{ request('status') == 'postponed' ? 'selected' : '' }}>Postponed</option>
        </select>

        <select id="roundFilter" class="filter-select">
            <option value="">Semua ronde</option>
            <option value="group" {{ request('round') == 'group' ? 'selected' : '' }}>Group Stage</option>
            <option value="quarterfinal" {{ request('round') == 'quarterfinal' ? 'selected' : '' }}>Quarterfinal</option>
            <option value="semifinal" {{ request('round') == 'semifinal' ? 'selected' : '' }}>Semifinal</option>
            <option value="final" {{ request('round') == 'final' ? 'selected' : '' }}>Final</option>
        </select>

        <input type="date" id="dateFrom" class="filter-select" placeholder="Date from" value="{{ request('date') }}">

        <button type="button" class="btn-reset" id="resetFilters">
            <i class="bi bi-arrow-clockwise me-1"></i> Reset
        </button>
    </div>

    <div class="main-card">
        <div class="card-header">
            <h5 class="mb-0">Daftar pertandingan</h5>
        </div>

        <div class="card-body p-0">
            @if($matches->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="matchesTable">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Match</th>
                                <th>Tournament</th>
                                <th>Stage</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matches as $match)
                                <tr class="match-row {{ ($match->status == 'completed' && ($match->et_score || $match->is_penalty)) ? 'row-has-extras' : '' }}"
                                    data-status="{{ $match->status }}" data-round="{{ $match->round_type }}"
                                    data-date="{{ $match->match_date->format('Y-m-d') }}"
                                    data-tournament="{{ $match->tournament_id }}">
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
                                            <div class="vs">vs</div>

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

                                        <!-- Score Section with Extras Info -->
                                        @if($match->status === 'completed' && $match->home_score !== null && $match->away_score !== null)
                                            <div class="score-display-admin">
                                                <span class="score">
                                                    {{ $match->home_score }} - {{ $match->away_score }}
                                                </span>

                                                <!-- Extra Time and Penalty Info -->
                                                @if($match->et_score || ($match->is_penalty && $match->penalty_score))
                                                    <div class="extra-info-admin">
                                                        @if($match->et_score)
                                                            <small class="badge et-badge me-1" style="font-size: 11px;">
                                                                <i class="bi bi-clock-history"></i> ET {{ $match->et_score }}
                                                            </small>
                                                        @endif

                                                        @if($match->is_penalty && $match->penalty_score)
                                                            <small class="badge pen-badge" style="font-size: 11px;">
                                                                <i class="bi bi-flag"></i> Pen. {{ $match->penalty_score }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($match->status === 'ongoing')
                                            <div class="score-display-admin">
                                                <span class="score live">
                                                    {{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}
                                                </span>
                                                <span class="badge live-badge" style="font-size: 11px;">Live</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge tournament-badge">
                                            {{ $match->tournament->name ?? 'N/A' }}
                                        </span>
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
                                            <a href="{{ route('admin.matches.lineup', $match) }}" class="btn-small btn-lineup"
                                                title="Manage Lineup">
                                                <i class="bi bi-people-fill"></i>
                                            </a>
                                            <button type="button" class="btn-small btn-score update-score-btn" title="Update Score"
                                                data-match-id="{{ $match->id }}"
                                                data-home-team="{{ $match->homeTeam->name ?? 'Home Team' }}"
                                                data-away-team="{{ $match->awayTeam->name ?? 'Away Team' }}"
                                                data-home-score="{{ $match->home_score ?? 0 }}"
                                                data-away-score="{{ $match->away_score ?? 0 }}"
                                                data-et-score="{{ $match->et_score ?? '' }}"
                                                data-is-penalty="{{ $match->is_penalty ? '1' : '0' }}"
                                                data-penalty-score="{{ $match->penalty_score ?? '' }}"
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
                            Menampilkan {{ $matches->firstItem() }}–{{ $matches->lastItem() }} dari {{ $matches->total() }} pertandingan
                        </div>

                        <div class="pagination-controls">
                            <!-- Previous Button -->
                            @if($matches->onFirstPage())
                                <span class="pagination-btn disabled">
                                    <i class="bi bi-chevron-left"></i> Previous
                                </span>
                            @else
                                <a href="{{ $matches->previousPageUrl() }}" class="pagination-btn">
                                    <i class="bi bi-chevron-left"></i> Sebelumnya
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
                                            echo '<span class="page-link active">' .
                                                $i . '</span>';
                                        } else {
                                            echo '<a href="' . $matches->url($i) . '" class="page-link">' . $i .
                                                '</a>';
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
                                    Berikutnya <i class="bi bi-chevron-right"></i>
                                </a>
                            @else
                                <span class="pagination-btn disabled">
                                    Berikutnya <i class="bi bi-chevron-right"></i>
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
                    <h4 class="empty-state-title">Belum ada pertandingan</h4>
                    <p class="empty-state-text">
                        Mulai dengan membuat pertandingan turnamen pertama Anda.
                    </p>
                    <a href="{{ route('admin.matches.create') }}" class="btn-create">
                        <i class="bi bi-plus"></i> Buat pertandingan
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- YouTube Highlight Modal -->
    <div class="modal fade" id="youtubeHighlightModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-youtube text-danger"></i> YouTube Highlight
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Current Highlight</h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="removeHighlightBtn">
                                        <i class="bi bi-trash"></i> Remove Highlight
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="ratio ratio-16x9 mb-2" id="currentVideoContainer">
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
                                <input type="url" name="youtube_url" id="youtube_url" class="form-control"
                                    placeholder="https://www.youtube.com/watch?v=..." required>
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
                                        <div class="ratio ratio-16x9 mb-2" id="previewContainer">
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
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveYoutubeBtn">
                            <span id="saveButtonText">Save</span>
                            <span class="spinner-border spinner-border-sm d-none" id="saveSpinner"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Score Update Modal -->
    <!-- Score Update Modal -->
    <div class="modal fade" id="scoreUpdateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="scoreUpdateForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Score</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Regular Time -->
                        <div class="mb-4">
                            <h6 class="text-center mb-3 text-primary">Regular Time Score</h6>
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <input type="number" name="home_score" id="modalHomeScore"
                                        class="form-control form-control-lg text-center fw-bold" min="0" max="99" required
                                        placeholder="0">
                                </div>
                                <div class="col-2 text-center">
                                    <span class="fw-bold fs-4">:</span>
                                </div>
                                <div class="col-5">
                                    <input type="number" name="away_score" id="modalAwayScore"
                                        class="form-control form-control-lg text-center fw-bold" min="0" max="99" required
                                        placeholder="0">
                                </div>
                            </div>
                        </div>

                        <!-- Extra Time (Optional) -->
                        <div class="mb-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="hasExtraTime">
                                <label class="form-check-label fw-bold" for="hasExtraTime">
                                    <i class="bi bi-clock-history me-1"></i> Extra Time
                                </label>
                            </div>

                            <div id="extraTimeSection" class="d-none">
                                <div class="row align-items-center">
                                    <div class="col-5">
                                        <input type="text" name="et_score" id="modalETScore"
                                            class="form-control text-center" pattern="\d+-\d+" placeholder="0-0"
                                            title="Format: home-away (contoh: 1-0)">
                                    </div>
                                    <div class="col-2 text-center">
                                        <span class="fw-bold">:</span>
                                    </div>
                                    <div class="col-5">
                                        <small class="text-muted">Format: home-away</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Penalty Shootout (Optional) -->
                        <div class="mb-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="hasPenalty" name="is_penalty" value="1">
                                <label class="form-check-label fw-bold" for="hasPenalty">
                                    <i class="bi bi-flag me-1"></i> Penalty Shootout
                                </label>
                            </div>

                            <div id="penaltySection" class="d-none">
                                <div class="row align-items-center">
                                    <div class="col-5">
                                        <input type="text" name="penalty_score" id="modalPenaltyScore"
                                            class="form-control text-center" pattern="\d+-\d+" placeholder="3-2"
                                            title="Format: home-away (contoh: 3-2)">
                                    </div>
                                    <div class="col-2 text-center">
                                        <span class="fw-bold">:</span>
                                    </div>
                                    <div class="col-5">
                                        <small class="text-muted">Format: home-away</small>
                                    </div>
                                </div>
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

    <!-- JavaScript untuk toggle extra time & penalty -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle Extra Time
            document.getElementById('hasExtraTime').addEventListener('change', function () {
                const extraTimeSection = document.getElementById('extraTimeSection');
                if (this.checked) {
                    extraTimeSection.classList.remove('d-none');
                    document.getElementById('modalETScore').required = true;
                } else {
                    extraTimeSection.classList.add('d-none');
                    document.getElementById('modalETScore').required = false;
                    document.getElementById('modalETScore').value = '';
                }
            });

            // Toggle Penalty
            document.getElementById('hasPenalty').addEventListener('change', function () {
                const penaltySection = document.getElementById('penaltySection');
                if (this.checked) {
                    penaltySection.classList.remove('d-none');
                    document.getElementById('modalPenaltyScore').required = true;
                } else {
                    penaltySection.classList.add('d-none');
                    document.getElementById('modalPenaltyScore').required = false;
                    document.getElementById('modalPenaltyScore').value = '';
                }
            });
        });
    </script>

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

@section('scripts')
    <script>
        function isIOS() {
            return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        }

        // Fix modal untuk iOS
        if (isIOS()) {
            // Fix backdrop click untuk iOS
            document.addEventListener('touchstart', function (e) {
                if (e.target.classList.contains('modal-backdrop') ||
                    e.target.classList.contains('modal') &&
                    !e.target.classList.contains('modal-dialog')) {

                    // Tutup semua modal yang terbuka
                    document.querySelectorAll('.modal.show').forEach(modalEl => {
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) {
                            modal.hide();
                        }
                    });

                    e.preventDefault();
                    e.stopPropagation();
                }
            }, {
                passive: false
            });

            // Fix untuk mencegah body scroll ketika modal terbuka
            document.addEventListener('show.bs.modal', function () {
                document.body.style.overflow = 'hidden';
                document.body.style.position = 'fixed';
                document.body.style.width = '100%';
            });

            document.addEventListener('hide.bs.modal', function () {
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.width = '';
            });
        }

        // Filter functionality
        const tournamentFilter = document.getElementById('tournamentFilter');
        const statusFilter = document.getElementById('statusFilter');
        const roundFilter = document.getElementById('roundFilter');
        const dateFrom = document.getElementById('dateFrom');
        const resetFilters = document.getElementById('resetFilters');
        const searchInput = document.getElementById('searchInput');
        const matchRows = document.querySelectorAll('.match-row');

        // Function to filter matches
        function filterMatches() {
            const tournament = tournamentFilter.value;
            const status = statusFilter.value;
            const round = roundFilter.value;
            const date = dateFrom.value;
            const search = searchInput.value.toLowerCase();

            matchRows.forEach(row => {
                const rowTournament = row.dataset.tournament;
                const rowStatus = row.dataset.status;
                const rowRound = row.dataset.round;
                const rowDate = row.dataset.date;

                // Get team names for search
                const homeTeam = row.querySelector('.team-name:not(.away)')?.textContent.toLowerCase() || '';
                const awayTeam = row.querySelector('.team-name.away')?.textContent.toLowerCase() || '';

                let show = true;

                if (tournament && rowTournament !== tournament) show = false;
                if (status && rowStatus !== status) show = false;
                if (round && rowRound !== round) show = false;
                if (date && rowDate < date) show = false;
                if (search && !homeTeam.includes(search) && !awayTeam.includes(search)) show = false;

                row.style.display = show ? '' : 'none';
            });
        }

        // Function to apply URL filters
        function applyUrlFilters() {
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.has('tournament_id')) {
                const tournamentId = urlParams.get('tournament_id');
                if (tournamentFilter) {
                    tournamentFilter.value = tournamentId;
                }
            }

            if (urlParams.has('status')) {
                const status = urlParams.get('status');
                if (statusFilter) {
                    statusFilter.value = status;
                }
            }

            if (urlParams.has('round')) {
                const round = urlParams.get('round');
                if (roundFilter) {
                    roundFilter.value = round;
                }
            }

            if (urlParams.has('date')) {
                const date = urlParams.get('date');
                if (dateFrom) {
                    dateFrom.value = date;
                }
            }

            if (urlParams.has('search')) {
                const search = urlParams.get('search');
                if (searchInput) {
                    searchInput.value = search;
                }
            }

            // Apply filters on page load
            filterMatches();
        }

        // Event listeners for filters
        document.addEventListener('DOMContentLoaded', function () {
            applyUrlFilters();

            if (tournamentFilter) tournamentFilter.addEventListener('change', function () {
                updateUrlParam('tournament_id', this.value);
                filterMatches();
            });

            if (statusFilter) statusFilter.addEventListener('change', function () {
                updateUrlParam('status', this.value);
                filterMatches();
            });

            if (roundFilter) roundFilter.addEventListener('change', function () {
                updateUrlParam('round', this.value);
                filterMatches();
            });

            if (dateFrom) dateFrom.addEventListener('change', function () {
                updateUrlParam('date', this.value);
                filterMatches();
            });

            if (searchInput) searchInput.addEventListener('input', function () {
                updateUrlParam('search', this.value);
                filterMatches();
            });

            // Reset filters
            if (resetFilters) {
                resetFilters.addEventListener('click', function () {
                    // Clear all filters
                    if (tournamentFilter) tournamentFilter.value = '';
                    if (statusFilter) statusFilter.value = '';
                    if (roundFilter) roundFilter.value = '';
                    if (dateFrom) dateFrom.value = '';
                    if (searchInput) searchInput.value = '';

                    // Clear URL parameters
                    clearUrlParams();

                    // Apply filters
                    filterMatches();
                });
            }
        });

        // Function to update URL parameter
        function updateUrlParam(key, value) {
            const url = new URL(window.location);

            if (value) {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }

            // Preserve pagination if exists
            const pageParam = url.searchParams.get('page');

            // Update URL without reloading page
            history.replaceState({}, '', url.toString());
        }

        // Function to clear all URL parameters
        function clearUrlParams() {
            const url = new URL(window.location);
            url.search = '';
            history.replaceState({}, '', url.toString());
        }

        // Score Update Modal
        let scoreUpdateModal = null;

        // Initialize modals after DOM is loaded
        document.addEventListener('DOMContentLoaded', function () {
            const scoreUpdateModalEl = document.getElementById('scoreUpdateModal');
            if (scoreUpdateModalEl) {
                scoreUpdateModal = new bootstrap.Modal(scoreUpdateModalEl);
            }

            const scoreUpdateForm = document.getElementById('scoreUpdateForm');

            // Handle update score button clicks
            document.querySelectorAll('.update-score-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Get data from button attributes
                    const matchId = this.dataset.matchId;
                    const homeTeam = this.dataset.homeTeam;
                    const awayTeam = this.dataset.awayTeam;
                    const homeScore = this.dataset.homeScore;
                    const awayScore = this.dataset.awayScore;
                    const updateUrl = this.dataset.updateUrl;

                    // Cari row match untuk mendapatkan logo
                    const matchRow = this.closest('.match-row');

                    // DAPATKAN LOGO DARI ROW YANG SAMA
                    let homeLogoHtml = '<div class="team-initial-modal">' +
                        (homeTeam ? homeTeam.charAt(0).toUpperCase() : 'H') +
                        '</div>';
                    let awayLogoHtml = '<div class="team-initial-modal">' +
                        (awayTeam ? awayTeam.charAt(0).toUpperCase() : 'A') +
                        '</div>';

                    // Coba ambil logo dari tabel
                    if (matchRow) {
                        // Home team logo dari row
                        const homeLogoContainer = matchRow.querySelector('.team-info:first-child .team-logo-small');
                        if (homeLogoContainer) {
                            // Clone logo content dari tabel
                            homeLogoHtml = homeLogoContainer.innerHTML;
                        }

                        // Away team logo dari row
                        const awayLogoContainer = matchRow.querySelector('.team-info:last-child .team-logo-small');
                        if (awayLogoContainer) {
                            // Clone logo content dari tabel
                            awayLogoHtml = awayLogoContainer.innerHTML;
                        }
                    }

                    // // Set modal data
                    // document.getElementById('modalHomeTeam').textContent = homeTeam;
                    // document.getElementById('modalAwayTeam').textContent = awayTeam;
                    document.getElementById('modalHomeScore').value = homeScore;
                    document.getElementById('modalAwayScore').value = awayScore;

                    // SET LOGO KE MODAL
                    const modalHomeLogo = document.getElementById('modalHomeLogo');
                    const modalAwayLogo = document.getElementById('modalAwayLogo');

                    if (modalHomeLogo) {
                        modalHomeLogo.innerHTML = '';

                        // Buat container logo baru untuk modal
                        const homeLogoDiv = document.createElement('div');
                        homeLogoDiv.className = 'team-logo-modal';

                        // Ambil hanya elemen gambar atau initial dari HTML tabel
                        if (homeLogoHtml.includes('<img')) {
                            // Jika ada gambar, extract src dan alt
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = homeLogoHtml;
                            const img = tempDiv.querySelector('img');
                            if (img) {
                                const newImg = document.createElement('img');
                                newImg.src = img.src;
                                newImg.alt = img.alt || homeTeam;
                                newImg.style.width = '100%';
                                newImg.style.height = '100%';
                                newImg.style.objectFit = 'cover';
                                homeLogoDiv.appendChild(newImg);
                            } else {
                                homeLogoDiv.innerHTML = homeLogoHtml;
                            }
                        } else {
                            // Jika hanya initial/fallback
                            homeLogoDiv.innerHTML = homeLogoHtml;
                        }

                        modalHomeLogo.appendChild(homeLogoDiv);
                    }

                    if (modalAwayLogo) {
                        modalAwayLogo.innerHTML = '';

                        // Buat container logo baru untuk modal
                        const awayLogoDiv = document.createElement('div');
                        awayLogoDiv.className = 'team-logo-modal';

                        // Ambil hanya elemen gambar atau initial dari HTML tabel
                        if (awayLogoHtml.includes('<img')) {
                            // Jika ada gambar, extract src dan alt
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = awayLogoHtml;
                            const img = tempDiv.querySelector('img');
                            if (img) {
                                const newImg = document.createElement('img');
                                newImg.src = img.src;
                                newImg.alt = img.alt || awayTeam;
                                newImg.style.width = '100%';
                                newImg.style.height = '100%';
                                newImg.style.objectFit = 'cover';
                                awayLogoDiv.appendChild(newImg);
                            } else {
                                awayLogoDiv.innerHTML = awayLogoHtml;
                            }
                        } else {
                            // Jika hanya initial/fallback
                            awayLogoDiv.innerHTML = awayLogoHtml;
                        }

                        modalAwayLogo.appendChild(awayLogoDiv);
                    }

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
        document.addEventListener('DOMContentLoaded', function () {
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
                videoInput.addEventListener('change', function (e) {
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
                button.addEventListener('click', function (e) {
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
                button.addEventListener('click', function (e) {
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
                submitUploadBtn.addEventListener('click', function () {
                    console.log('Submit upload clicked');
                    uploadHighlight();
                });
            }

            // Delete highlight button
            const deleteHighlightBtn = document.getElementById('deleteHighlightBtn');
            if (deleteHighlightBtn) {
                deleteHighlightBtn.addEventListener('click', function () {
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
                xhr.upload.addEventListener('progress', function (e) {
                    if (e.lengthComputable && progressFill) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        progressFill.style.width = percentComplete + '%';
                        progressFill.textContent = Math.round(percentComplete) + '%';
                    }
                });

                xhr.onreadystatechange = function () {
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

                xhr.onerror = function () {
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
            button.addEventListener('click', function (e) {
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
            removeHighlightBtn.addEventListener('click', function () {
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
            youtubeUrlInput.addEventListener('blur', function () {
                validateYoutubeUrl(this.value);
            });

            youtubeUrlInput.addEventListener('input', function () {
                if (!this.value) {
                    const previewSection = document.getElementById('youtubePreview');
                    if (previewSection) previewSection.classList.add('d-none');
                }
            });
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
            youtubeForm.addEventListener('submit', function (e) {
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
    </script>
@endsection
