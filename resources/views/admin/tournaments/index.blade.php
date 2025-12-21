@extends('layouts.admin')


    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">


@section('title', 'Tournaments Management')@section('styles')
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

        /* Tournament Info */
        .tournament-name {
            font-weight: 500;
            color: var(--primary);
            margin-bottom: 2px;
            font-size: 0.875rem;
        }

        .tournament-location {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        /* Type Badges */
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

        .badge-league {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }

        .badge-knockout {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        /* Status Badges */
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

        .status-cancelled {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* Date & Count Info */
        .date-info {
            font-size: 0.875rem;
            color: var(--primary);
            font-weight: 500;
        }

        .count-info {
            font-weight: 600;
            color: var(--primary);
            font-size: 1rem;
        }

        .count-info small {
            font-size: 0.75rem;
            color: var(--secondary);
            font-weight: normal;
            display: block;
            margin-top: 2px;
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

        .btn-view:hover {
            background: var(--primary-light);
            color: white;
        }

        .btn-edit:hover {
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

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* bikin tinggi kiri & kanan sejajar */
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

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
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

            .action-buttons {
                flex-wrap: wrap;
                justify-content: center;
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

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size: 0.875rem; padding: 0; background: none;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tournaments</li>
        </ol>
    </nav>

    <div class="page-header">
        <h1>
            <!-- <i class="bi bi-trophy me-2"></i> -->
            Tournaments
        </h1>
        <div class="d-flex gap-2">
            <div class="search-box">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="searchInput" placeholder="Search tournaments..."
                    class="form-control form-control-sm">
            </div>
            <a href="{{ route('admin.tournaments.create.step', ['step' => 1]) }}" class="btn-create">
                <i class="bi bi-plus"></i>
                New Tournament
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
                    <div class="stat-value">{{ $tournaments->where('status', 'upcoming')->count() }}</div>
                </div>
                <div class="stat-icon" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
                    <i class="bi bi-clock"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div>
                    <div class="stat-title">Ongoing</div>
                    <div class="stat-value">{{ $tournaments->where('status', 'ongoing')->count() }}</div>
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
                    <div class="stat-value">{{ $tournaments->where('status', 'completed')->count() }}</div>
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
                    <div class="stat-value">{{ $tournaments->count() }}</div>
                </div>
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                    <i class="bi bi-trophy"></i>
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
            <option value="cancelled">Cancelled</option>
        </select>

        <select id="typeFilter" class="filter-select">
            <option value="">All Types</option>
            <option value="group_knockout">Group + Knockout</option>
            <option value="league">League</option>
            <option value="knockout">Knockout</option>
        </select>

        <input type="date" id="dateFrom" class="filter-select" placeholder="Date from">

        <button class="btn btn-outline-secondary btn-sm d-flex align-items-center" id="resetFilters">
            <i class="bi bi-arrow-clockwise me-1"></i> Reset
        </button>
    </div>

    <div class="main-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list me-2"></i> All Tournaments</h5>
        </div>

        <div class="card-body p-0">
            @if($tournaments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tournamentsTable">
                        <thead>
                            <tr>
                                <th>Tournament</th>
                                <th>Type</th>
                                <th>Dates</th>
                                <th>Status</th>
                                <th>Teams</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tournaments as $tournament)
                                <tr class="tournament-row" data-status="{{ $tournament->status }}"
                                    data-type="{{ $tournament->type }}" data-date="{{ $tournament->start_date->format('Y-m-d') }}"
                                    data-name="{{ strtolower($tournament->name) }}"
                                    data-location="{{ strtolower($tournament->location) }}">
                                    <td>
                                        <div class="tournament-name">{{ $tournament->name }}</div>
                                        <div class="tournament-location">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $tournament->location }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($tournament->type == 'group_knockout')
                                            <span class="badge badge-group">Group + Knockout</span>
                                        @elseif($tournament->type == 'league')
                                            <span class="badge badge-league">League</span>
                                        @else
                                            <span class="badge badge-knockout">Knockout</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="date-info">
                                            {{ $tournament->start_date->format('d M') }} -
                                            {{ $tournament->end_date->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($tournament->status == 'upcoming')
                                            <span class="status-badge status-upcoming">Upcoming</span>
                                        @elseif($tournament->status == 'ongoing')
                                            <span class="status-badge status-ongoing">Ongoing</span>
                                        @elseif($tournament->status == 'completed')
                                            <span class="status-badge status-completed">Completed</span>
                                        @else
                                            <span class="status-badge status-cancelled">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="count-info">
                                            {{ $tournament->teams->count() }}
                                            @if($tournament->type == 'group_knockout' && $tournament->groups_count)
                                                <small>in {{ $tournament->groups_count }} groups</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.tournaments.show', $tournament) }}" class="btn-small btn-view"
                                                title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.tournaments.edit', $tournament) }}" class="btn-small btn-edit"
                                                title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.tournaments.destroy', $tournament) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-small btn-delete"
                                                    onclick="return confirm('Delete this tournament?')" title="Delete">
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

                @if($tournaments->hasPages())
                    <div class="pagination-container d-flex justify-content-between align-items-center flex-wrap">
                        <div class="pagination-info">
                            Showing {{ $tournaments->firstItem() }} to {{ $tournaments->lastItem() }} of {{ $tournaments->total() }}
                            tournaments
                        </div>

                        <div class="pagination-controls">
                            <!-- Previous Button -->
                            @if($tournaments->onFirstPage())
                                <span class="pagination-btn disabled">
                                    <i class="bi bi-chevron-left"></i> Previous
                                </span>
                            @else
                                <a href="{{ $tournaments->previousPageUrl() }}" class="pagination-btn">
                                    <i class="bi bi-chevron-left"></i> Previous
                                </a>
                            @endif

                            <!-- Page Numbers -->
                            <div class="pagination-pages">
                                @php
                                    $current = $tournaments->currentPage();
                                    $last = $tournaments->lastPage();
                                    $start = max(1, $current - 2);
                                    $end = min($last, $current + 2);

                                    if ($start > 1) {
                                        echo '<a href="' . $tournaments->url(1) . '" class="page-link">1</a>';
                                        if ($start > 2) {
                                            echo '<span class="page-link disabled">...</span>';
                                        }
                                    }

                                    for ($i = $start; $i <= $end; $i++) {
                                        if ($i == $current) {
                                            echo '<span class="page-link active">' . $i . '</span>';
                                        } else {
                                            echo '<a href="' . $tournaments->url($i) . '" class="page-link">' . $i . '</a>';
                                        }
                                    }

                                    if ($end < $last) {
                                        if ($end < $last - 1) {
                                            echo '<span class="page-link disabled">...</span>';
                                        }
                                        echo '<a href="' . $tournaments->url($last) . '" class="page-link">' . $last . '</a>';
                                    }
                                @endphp
                            </div>

                            <!-- Next Button -->
                            @if($tournaments->hasMorePages())
                                <a href="{{ $tournaments->nextPageUrl() }}" class="pagination-btn">
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
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h4 class="empty-state-title">No Tournaments Found</h4>
                    <p class="empty-state-text">
                        Get started by creating your first tournament.
                    </p>
                    <a href="{{ route('admin.tournaments.create.step', ['step' => 1]) }}" class="btn-create">
                        <i class="bi bi-plus"></i>
                        Create First Tournament
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Filter functionality
        const statusFilter = document.getElementById('statusFilter');
        const typeFilter = document.getElementById('typeFilter');
        const dateFrom = document.getElementById('dateFrom');
        const resetFilters = document.getElementById('resetFilters');
        const searchInput = document.getElementById('searchInput');
        const tournamentRows = document.querySelectorAll('.tournament-row');

        function filterTournaments() {
            const status = statusFilter.value;
            const type = typeFilter.value;
            const date = dateFrom.value;
            const searchTerm = searchInput.value.toLowerCase();

            tournamentRows.forEach(row => {
                const rowStatus = row.dataset.status;
                const rowType = row.dataset.type;
                const rowDate = row.dataset.date;
                const rowName = row.dataset.name;
                const rowLocation = row.dataset.location;

                let show = true;

                if (status && rowStatus !== status) show = false;
                if (type && rowType !== type) show = false;
                if (date && rowDate < date) show = false;
                if (searchTerm && !rowName.includes(searchTerm) && !rowLocation.includes(searchTerm)) show = false;

                row.style.display = show ? '' : 'none';
            });
        }

        // Event listeners for filters
        if (statusFilter) statusFilter.addEventListener('change', filterTournaments);
        if (typeFilter) typeFilter.addEventListener('change', filterTournaments);
        if (dateFrom) dateFrom.addEventListener('change', filterTournaments);
        if (searchInput) searchInput.addEventListener('input', filterTournaments);

        // Reset filters
        if (resetFilters) {
            resetFilters.addEventListener('click', function () {
                statusFilter.value = '';
                typeFilter.value = '';
                dateFrom.value = '';
                searchInput.value = '';
                filterTournaments();
            });
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