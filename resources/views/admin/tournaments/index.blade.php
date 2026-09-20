@extends('layouts.admin')

@section('title', 'Tournaments')

@section('styles')
    <style>
        /* ===== Tournaments page — design guidelines ===== */

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
            height: 40px;
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

        .btn-create:hover {
            background: var(--accent-hover);
            color: #fff;
        }

        /* Filters */
        .filter-select {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 0 12px;
            font-size: 14px;
            height: 40px;
            background: var(--bg);
            color: var(--text-primary);
        }

        .filter-select:focus {
            border-color: var(--accent);
            outline: none;
        }

        .btn-reset {
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text-secondary);
            border-radius: 6px;
            height: 40px;
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

        .filters-row {
            margin-bottom: 24px;
        }

        /* Stat cards — simple & compact */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 16px;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
            background: var(--stat-bg, var(--surface));
            color: var(--stat-color, var(--accent));
        }

        .stat-info {
            min-width: 0;
        }

        .stat-title {
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.3;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .stat-card.stat-upcoming {
            --stat-color: var(--text-secondary);
            --stat-bg: var(--surface);
        }

        .stat-card.stat-ongoing {
            --stat-color: var(--warning);
            --stat-bg: var(--warning-bg);
        }

        .stat-card.stat-completed {
            --stat-color: var(--success);
            --stat-bg: var(--success-bg);
        }

        .stat-card.stat-total {
            --stat-color: var(--accent);
            --stat-bg: rgba(26, 95, 180, 0.08);
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

        #tournamentsTable thead th {
            background: var(--surface);
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 13px;
            border-bottom: 1px solid var(--border);
            padding: 12px 16px;
            white-space: nowrap;
        }

        #tournamentsTable tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            color: var(--text-primary);
        }

        #tournamentsTable tbody tr:last-child td {
            border-bottom: none;
        }

        #tournamentsTable tbody tr:hover {
            background: var(--surface);
        }

        /* Tournament info */
        .tournament-name {
            font-weight: 500;
            color: var(--text-primary);
            font-size: 14px;
        }

        .tournament-location {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .date-info {
            font-size: 14px;
            color: var(--text-primary);
            font-weight: 500;
        }

        .count-info {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .count-info small {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: normal;
            display: block;
            margin-top: 2px;
        }

        /* Badges */
        #tournamentsTable .badge {
            font-size: 12px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .badge-group,
        .badge-league,
        .badge-knockout {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-secondary);
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

        .status-cancelled {
            background: #FDF2F3;
            color: #c01c28;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 4px;
            justify-content: flex-end;
            align-items: center;
        }

        .btn-small {
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

        .btn-small:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-small.btn-delete:hover {
            border-color: #c01c28;
            color: #c01c28;
            background: #FDF2F3;
        }

        /* Pagination */
        .pagination-container {
            padding: 12px 16px;
            border-top: 1px solid var(--border);
            gap: 12px;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

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

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-box,
            .search-box input {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
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
    <div class="page-header">
        <div>
            <h1>Turnamen</h1>
            <p class="page-subtitle">Kelola turnamen futsal Anda</p>
        </div>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <div class="search-box">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="searchInput" placeholder="Cari turnamen..." class="form-control form-control-sm">
            </div>
            <a href="{{ route('admin.tournaments.create.step', ['step' => 1]) }}" class="btn-create">
                <i class="bi bi-plus"></i> Buat Turnamen
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

    <!-- Statistik -->
    <div class="stats-grid">
        <div class="stat-card stat-upcoming">
            <div class="stat-icon"><i class="bi bi-clock"></i></div>
            <div class="stat-info">
                <div class="stat-title">Upcoming</div>
                <div class="stat-value">{{ $tournaments->where('status', 'upcoming')->count() }}</div>
            </div>
        </div>

        <div class="stat-card stat-ongoing">
            <div class="stat-icon"><i class="bi bi-play-circle"></i></div>
            <div class="stat-info">
                <div class="stat-title">Ongoing</div>
                <div class="stat-value">{{ $tournaments->where('status', 'ongoing')->count() }}</div>
            </div>
        </div>

        <div class="stat-card stat-completed">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-title">Completed</div>
                <div class="stat-value">{{ $tournaments->where('status', 'completed')->count() }}</div>
            </div>
        </div>

        <div class="stat-card stat-total">
            <div class="stat-icon"><i class="bi bi-trophy"></i></div>
            <div class="stat-info">
                <div class="stat-title">Total</div>
                <div class="stat-value">{{ $tournaments->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="filters-row d-flex gap-2 flex-wrap align-items-center">
        <select id="statusFilter" class="filter-select">
            <option value="">Semua status</option>
            <option value="upcoming">Upcoming</option>
            <option value="ongoing">Ongoing</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <select id="typeFilter" class="filter-select">
            <option value="">Semua tipe</option>
            <option value="group_knockout">Group + Knockout</option>
            <option value="league">League</option>
            <option value="knockout">Knockout</option>
        </select>

        <input type="date" id="dateFrom" class="filter-select">

        <button type="button" class="btn-reset" id="resetFilters">
            <i class="bi bi-arrow-clockwise me-1"></i> Reset
        </button>
    </div>

    <div class="main-card">
        <div class="card-header">
            <h5>Daftar turnamen</h5>
        </div>

        <div class="card-body p-0">
            @if($tournaments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tournamentsTable">
                        <thead>
                            <tr>
                                <th>Turnamen</th>
                                <th>Tipe</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Tim</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tournaments as $tournament)
                                <tr class="tournament-row" data-status="{{ $tournament->status }}"
                                    data-type="{{ $tournament->type }}"
                                    data-date="{{ $tournament->start_date->format('Y-m-d') }}"
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
                                                <small>di {{ $tournament->groups_count }} grup</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.tournaments.show', $tournament) }}" class="btn-small"
                                                title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.tournaments.edit', $tournament) }}" class="btn-small"
                                                title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.tournaments.destroy', $tournament) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-small btn-delete"
                                                    onclick="return confirm('Hapus turnamen ini?')" title="Hapus">
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
                            Menampilkan {{ $tournaments->firstItem() }}–{{ $tournaments->lastItem() }} dari {{ $tournaments->total() }} turnamen
                        </div>

                        <div class="pagination-controls">
                            <!-- Tombol sebelumnya -->
                            @if($tournaments->onFirstPage())
                                <span class="pagination-btn disabled">
                                    <i class="bi bi-chevron-left"></i> Sebelumnya
                                </span>
                            @else
                                <a href="{{ $tournaments->previousPageUrl() }}" class="pagination-btn">
                                    <i class="bi bi-chevron-left"></i> Sebelumnya
                                </a>
                            @endif

                            <!-- Nomor halaman -->
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

                            <!-- Tombol berikutnya -->
                            @if($tournaments->hasMorePages())
                                <a href="{{ $tournaments->nextPageUrl() }}" class="pagination-btn">
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
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h4 class="empty-state-title">Belum ada turnamen</h4>
                    <p class="empty-state-text">
                        Mulai dengan membuat turnamen pertama Anda.
                    </p>
                    <a href="{{ route('admin.tournaments.create.step', ['step' => 1]) }}" class="btn-create">
                        <i class="bi bi-plus"></i> Buat Turnamen Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ===== Referensi elemen =====
            const statusFilter = document.getElementById('statusFilter');
            const typeFilter = document.getElementById('typeFilter');
            const dateFrom = document.getElementById('dateFrom');
            const resetFilters = document.getElementById('resetFilters');
            const searchInput = document.getElementById('searchInput');
            const tournamentRows = document.querySelectorAll('.tournament-row');

            // ===== Filter turnamen =====
            function filterTournaments() {
                const status = statusFilter.value;
                const type = typeFilter.value;
                const date = dateFrom.value;
                const searchTerm = searchInput.value.toLowerCase();

                tournamentRows.forEach(row => {
                    let show = true;

                    if (status && row.dataset.status !== status) show = false;
                    if (type && row.dataset.type !== type) show = false;
                    if (date && row.dataset.date < date) show = false;
                    if (searchTerm && !row.dataset.name.includes(searchTerm) &&
                        !row.dataset.location.includes(searchTerm)) show = false;

                    row.style.display = show ? '' : 'none';
                });
            }

            // ===== Event listeners =====
            statusFilter.addEventListener('change', filterTournaments);
            typeFilter.addEventListener('change', filterTournaments);
            dateFrom.addEventListener('change', filterTournaments);
            searchInput.addEventListener('input', filterTournaments);

            // Reset filter
            resetFilters.addEventListener('click', function () {
                statusFilter.value = '';
                typeFilter.value = '';
                dateFrom.value = '';
                searchInput.value = '';
                filterTournaments();
            });

            // Auto-dismiss alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    try {
                        new bootstrap.Alert(alert).close();
                    } catch (e) {
                        // Silently fail
                    }
                });
            }, 5000);
        });
    </script>
@endsection
