@extends('layouts.admin')

@section('title', 'Tournaments')

@section('styles')
    <style>
        .admin-tournaments-page {
            --tournament-ink: #111111;
            --tournament-charcoal: #39393b;
            --tournament-muted: #707072;
            --tournament-line: #cacacb;
            --tournament-line-soft: #e5e5e5;
            --tournament-cloud: #f5f5f5;
            --tournament-white: #ffffff;
            --tournament-success: #007d48;
            --tournament-warning: #8a4b00;
            --tournament-danger: #b42318;
            max-width: 1440px;
            margin: 0 auto;
            color: var(--tournament-ink);
            font-size: 16px;
            line-height: 1.5;
        }

        .admin-tournaments-page .page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px 24px;
            margin-bottom: 28px;
        }

        .admin-tournaments-page .page-header h1 {
            margin: 0 0 8px;
            color: var(--tournament-ink);
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
        }

        .admin-tournaments-page .page-subtitle {
            max-width: 66ch;
            margin: 0;
            color: var(--tournament-muted);
            font-size: 15px;
        }

        .admin-tournaments-page .header-actions,
        .admin-tournaments-page .header-tools,
        .admin-tournaments-page .filters-row,
        .admin-tournaments-page .action-buttons,
        .admin-tournaments-page .pagination-controls,
        .admin-tournaments-page .pagination-pages {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }

        .admin-tournaments-page .header-actions,
        .admin-tournaments-page .header-tools,
        .admin-tournaments-page .filters-row {
            gap: 8px;
        }

        .admin-tournaments-page .search-box {
            position: relative;
            width: min(260px, 100%);
        }

        .admin-tournaments-page .search-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            color: var(--tournament-muted);
            font-size: 14px;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .admin-tournaments-page .search-input,
        .admin-tournaments-page .filter-select {
            min-height: 44px;
            border: 1px solid var(--tournament-line);
            border-radius: 24px;
            background-color: var(--tournament-white);
            color: var(--tournament-ink);
            font: inherit;
            font-size: 14px;
            line-height: 1.25;
        }

        .admin-tournaments-page .search-input {
            width: 100%;
            padding: 9px 16px 9px 42px;
            background: var(--tournament-cloud);
        }

        .admin-tournaments-page .filter-select {
            min-width: 150px;
            padding: 8px 38px 8px 16px;
        }

        .admin-tournaments-page select.filter-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23111111' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 14px 14px;
            cursor: pointer;
        }

        .admin-tournaments-page :is(a, button, input, select):focus-visible {
            outline: 2px solid var(--tournament-ink);
            outline-offset: 3px;
        }

        .admin-tournaments-page .search-input:focus,
        .admin-tournaments-page .filter-select:focus {
            border-color: var(--tournament-ink);
            box-shadow: 0 0 0 3px var(--tournament-cloud);
        }

        .admin-tournaments-page .btn-create,
        .admin-tournaments-page .btn-reset,
        .admin-tournaments-page .pagination-btn,
        .admin-tournaments-page .pagination-pages .page-link {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 18px;
            border: 1px solid transparent;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            text-decoration: none;
            white-space: nowrap;
            transition: background-color 150ms ease-out, border-color 150ms ease-out, color 150ms ease-out;
        }

        .admin-tournaments-page .btn-create {
            border-color: var(--tournament-ink);
            background: var(--tournament-ink);
            color: var(--tournament-white);
        }

        .admin-tournaments-page .btn-create:hover {
            border-color: var(--tournament-charcoal);
            background: var(--tournament-charcoal);
            color: var(--tournament-white);
        }

        .admin-tournaments-page .btn-create.is-disabled {
            border-color: var(--tournament-cloud);
            background: var(--tournament-cloud);
            color: var(--tournament-muted);
            cursor: not-allowed;
        }

        .admin-tournaments-page .btn-reset,
        .admin-tournaments-page .pagination-btn,
        .admin-tournaments-page .pagination-pages .page-link {
            background: var(--tournament-cloud);
            color: var(--tournament-ink);
            cursor: pointer;
        }

        .admin-tournaments-page .btn-reset:hover,
        .admin-tournaments-page .pagination-btn:hover,
        .admin-tournaments-page .pagination-pages a.page-link:hover {
            background: #eeeeee;
            color: var(--tournament-ink);
        }

        .admin-tournaments-page .alert-admin {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 16px;
            padding: 14px 16px;
            border: 1px solid var(--tournament-line-soft);
            border-radius: 8px;
            background: var(--tournament-cloud);
            color: var(--tournament-charcoal);
            font-size: 14px;
            line-height: 1.5;
        }

        .admin-tournaments-page .alert-admin-success {
            color: var(--tournament-success);
        }

        .admin-tournaments-page .alert-admin-danger {
            color: var(--tournament-danger);
        }

        .admin-tournaments-page .alert-admin-warning {
            color: var(--tournament-warning);
        }

        .admin-tournaments-page .alert-close {
            flex: 0 0 auto;
            margin-left: auto;
        }

        .admin-tournaments-page .stats-grid,
        .admin-tournaments-page .main-card {
            border: 1px solid var(--tournament-line-soft);
            border-radius: 8px;
            background: var(--tournament-white);
        }

        .admin-tournaments-page .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 24px;
            overflow: hidden;
        }

        .admin-tournaments-page .stat-card {
            min-width: 0;
            padding: 18px 20px;
            border-right: 1px solid var(--tournament-line-soft);
        }

        .admin-tournaments-page .stat-card:last-child {
            border-right: 0;
        }

        .admin-tournaments-page .stat-title {
            color: var(--tournament-muted);
            font-size: 14px;
            line-height: 1.4;
        }

        .admin-tournaments-page .stat-value {
            margin-top: 4px;
            color: var(--tournament-ink);
            font-size: 26px;
            font-variant-numeric: tabular-nums;
            font-weight: 500;
            line-height: 1.2;
        }

        .admin-tournaments-page .stat-upcoming .stat-value {
            color: var(--tournament-charcoal);
        }

        .admin-tournaments-page .stat-ongoing .stat-value {
            color: var(--tournament-warning);
        }

        .admin-tournaments-page .stat-completed .stat-value {
            color: var(--tournament-success);
        }

        .admin-tournaments-page .main-card {
            margin-bottom: 24px;
            overflow: hidden;
        }

        .admin-tournaments-page .card-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px 20px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--tournament-line-soft);
            background: var(--tournament-white);
        }

        .admin-tournaments-page .card-header h2 {
            margin: 0;
            color: var(--tournament-ink);
            font-size: 22px;
            font-weight: 500;
            line-height: 1.25;
        }

        .admin-tournaments-page .filters-row {
            margin: 0;
        }

        .admin-tournaments-page .table-responsive {
            overflow-x: auto;
        }

        .admin-tournaments-page #tournamentsTable {
            width: 100%;
            min-width: 900px;
            margin: 0;
            border-collapse: collapse;
            color: var(--tournament-ink);
        }

        .admin-tournaments-page #tournamentsTable thead th {
            padding: 12px 16px;
            border-bottom: 1px solid var(--tournament-line-soft);
            background: var(--tournament-cloud);
            color: var(--tournament-muted);
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
        }

        .admin-tournaments-page #tournamentsTable tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--tournament-line-soft);
            background: var(--tournament-white);
            font-size: 14px;
            vertical-align: middle;
        }

        .admin-tournaments-page #tournamentsTable tbody tr:last-child td {
            border-bottom: 0;
        }

        .admin-tournaments-page #tournamentsTable tbody tr:hover td {
            background: #fafafa;
        }

        .admin-tournaments-page .tournament-name {
            color: var(--tournament-ink);
            font-size: 15px;
            font-weight: 500;
            line-height: 1.4;
        }

        .admin-tournaments-page .tournament-location {
            margin-top: 3px;
            color: var(--tournament-muted);
            font-size: 13px;
        }

        .admin-tournaments-page .date-info,
        .admin-tournaments-page .count-info {
            color: var(--tournament-ink);
            font-variant-numeric: tabular-nums;
            font-size: 14px;
        }

        .admin-tournaments-page .count-info {
            font-weight: 500;
        }

        .admin-tournaments-page .count-info small {
            display: block;
            margin-top: 3px;
            color: var(--tournament-muted);
            font-size: 12px;
            font-weight: 400;
        }

        .admin-tournaments-page .type-badge,
        .admin-tournaments-page .featured-badge,
        .admin-tournaments-page .status-badge {
            min-height: 30px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border: 1px solid var(--tournament-line-soft);
            border-radius: 9999px;
            color: var(--tournament-charcoal);
            font-size: 13px;
            font-weight: 500;
            line-height: 1.3;
            white-space: nowrap;
        }

        .admin-tournaments-page .featured-badge {
            margin-left: 6px;
            background: var(--tournament-cloud);
        }

        .admin-tournaments-page .status-upcoming {
            background: var(--tournament-cloud);
            color: var(--tournament-charcoal);
        }

        .admin-tournaments-page .status-ongoing {
            border-color: #f0d6b3;
            background: #fff8eb;
            color: var(--tournament-warning);
        }

        .admin-tournaments-page .status-completed {
            border-color: #c4e4d3;
            background: #edf8f2;
            color: var(--tournament-success);
        }

        .admin-tournaments-page .status-cancelled {
            border-color: #f0cdca;
            background: #fdf2f1;
            color: var(--tournament-danger);
        }

        .admin-tournaments-page .action-buttons {
            justify-content: flex-end;
            gap: 6px;
        }

        .admin-tournaments-page .action-buttons form {
            margin: 0;
        }

        .admin-tournaments-page .btn-small {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 1px solid transparent;
            border-radius: 9999px;
            background: var(--tournament-cloud);
            color: var(--tournament-ink);
            font-size: 15px;
            text-decoration: none;
            transition: background-color 150ms ease-out, color 150ms ease-out;
        }

        .admin-tournaments-page .btn-small:hover {
            background: #e5e5e5;
            color: var(--tournament-ink);
        }

        .admin-tournaments-page .btn-small.btn-featured {
            background: var(--tournament-ink);
            color: var(--tournament-white);
        }

        .admin-tournaments-page .btn-small.btn-delete:hover {
            background: #fdf2f1;
            color: var(--tournament-danger);
        }

        .admin-tournaments-page .pagination-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px;
            border-top: 1px solid var(--tournament-line-soft);
        }

        .admin-tournaments-page .pagination-info {
            color: var(--tournament-muted);
            font-size: 14px;
        }

        .admin-tournaments-page .pagination-controls {
            gap: 4px;
        }

        .admin-tournaments-page .pagination-btn,
        .admin-tournaments-page .pagination-pages .page-link {
            min-width: 40px;
            min-height: 40px;
            padding: 8px 14px;
        }

        .admin-tournaments-page .pagination-pages {
            gap: 4px;
        }

        .admin-tournaments-page .pagination-pages .page-link.active {
            border-color: var(--tournament-ink);
            background: var(--tournament-ink);
            color: var(--tournament-white);
        }

        .admin-tournaments-page .pagination-btn.disabled,
        .admin-tournaments-page .pagination-pages .page-link.disabled {
            background: var(--tournament-white);
            color: var(--tournament-muted);
            cursor: default;
        }

        .admin-tournaments-page .empty-state {
            padding: 40px 20px;
            text-align: center;
        }

        .admin-tournaments-page .empty-state-icon {
            margin-bottom: 12px;
            color: var(--tournament-muted);
            font-size: 28px;
        }

        .admin-tournaments-page .empty-state-title {
            margin: 0 0 6px;
            color: var(--tournament-ink);
            font-size: 18px;
            font-weight: 500;
        }

        .admin-tournaments-page .empty-state-text {
            margin: 0 0 16px;
            color: var(--tournament-muted);
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .admin-tournaments-page .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .admin-tournaments-page .stat-card:nth-child(2) {
                border-right: 0;
            }

            .admin-tournaments-page .stat-card:nth-child(-n + 2) {
                border-bottom: 1px solid var(--tournament-line-soft);
            }
        }

        @media (max-width: 640px) {
            .admin-tournaments-page .page-header {
                align-items: stretch;
                margin-bottom: 24px;
            }

            .admin-tournaments-page .header-tools,
            .admin-tournaments-page .search-box {
                width: 100%;
            }

            .admin-tournaments-page .btn-create {
                flex: 1;
            }

            .admin-tournaments-page .card-header {
                align-items: stretch;
                padding: 16px;
            }

            .admin-tournaments-page .filters-row {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .admin-tournaments-page .filter-select {
                width: 100%;
                min-width: 0;
            }

            .admin-tournaments-page .pagination-container {
                padding: 14px 16px;
            }
        }

        @media (max-width: 420px) {
            .admin-tournaments-page .header-tools {
                align-items: stretch !important;
                flex-direction: column;
            }

            .admin-tournaments-page .header-actions,
            .admin-tournaments-page .filters-row {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
            }

            .admin-tournaments-page .stat-card {
                padding: 15px 14px;
            }

            .admin-tournaments-page .stat-value {
                font-size: 23px;
            }

            .admin-tournaments-page .pagination-controls {
                justify-content: flex-start;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-tournaments-page .btn-create,
            .admin-tournaments-page .btn-reset,
            .admin-tournaments-page .btn-small,
            .admin-tournaments-page .pagination-btn,
            .admin-tournaments-page .pagination-pages .page-link {
                transition: none;
            }
        }
    </style>
@endsection

@section('content')
    <main class="admin-tournaments-page">
        <header class="page-header">
            <div>
                <h1>Turnamen</h1>
                <p class="page-subtitle">Kelola jadwal, format, dan peserta turnamen.</p>
            </div>
            <div class="header-tools">
                <div class="search-box">
                    <i class="bi bi-search search-icon" aria-hidden="true"></i>
                    <input type="search" id="searchInput" placeholder="Cari nama atau lokasi"
                        class="search-input" aria-label="Cari turnamen berdasarkan nama atau lokasi">
                </div>
                @if($activeTournament)
                    <span class="btn-create is-disabled" aria-disabled="true"
                        title="Masih ada turnamen aktif: {{ $activeTournament->name }}">
                        <i class="bi bi-lock" aria-hidden="true"></i> Buat turnamen
                    </span>
                @else
                    <a href="{{ route('admin.tournaments.create.step', ['step' => 1]) }}" class="btn-create">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i> Buat turnamen
                    </a>
                @endif
            </div>
        </header>

        @if(session('success'))
            <div class="alert alert-admin alert-admin-success alert-dismissible fade show" role="status" aria-live="polite">
                <i class="bi bi-check-circle" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close alert-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-admin alert-admin-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close alert-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if($activeTournament)
            <div class="alert alert-admin alert-admin-warning" role="status">
                <i class="bi bi-lock-fill" aria-hidden="true"></i>
                <div>
                    <strong>Hanya satu turnamen yang dapat berjalan pada satu waktu.</strong>
                    <span>Turnamen “{{ $activeTournament->name }}” masih berstatus
                        @switch($activeTournament->status)
                            @case('upcoming') akan datang @break
                            @case('ongoing') berlangsung @break
                            @case('completed') selesai @break
                            @case('cancelled') dibatalkan @break
                            @default {{ $activeTournament->status }}
                        @endswitch.
                        Selesaikan atau batalkan turnamen tersebut sebelum membuat turnamen baru.
                    </span>
                </div>
            </div>
        @endif

        <section class="stats-grid" aria-label="Ringkasan turnamen">
            <div class="stat-card stat-upcoming">
                <div class="stat-title">Akan datang</div>
                <div class="stat-value">{{ $stats['upcoming'] }}</div>
            </div>
            <div class="stat-card stat-ongoing">
                <div class="stat-title">Berlangsung</div>
                <div class="stat-value">{{ $stats['ongoing'] }}</div>
            </div>
            <div class="stat-card stat-completed">
                <div class="stat-title">Selesai</div>
                <div class="stat-value">{{ $stats['completed'] }}</div>
            </div>
            <div class="stat-card stat-total">
                <div class="stat-title">Total turnamen</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
        </section>

        <section class="main-card" aria-labelledby="tournament-list-title">
            <div class="card-header">
                <h2 id="tournament-list-title">Daftar turnamen</h2>
                <div class="filters-row" role="group" aria-label="Filter turnamen">
                    <label class="visually-hidden" for="statusFilter">Filter status</label>
                    <select id="statusFilter" class="filter-select">
                        <option value="">Semua status</option>
                        <option value="upcoming">Akan datang</option>
                        <option value="ongoing">Berlangsung</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>

                    <label class="visually-hidden" for="typeFilter">Filter format</label>
                    <select id="typeFilter" class="filter-select">
                        <option value="">Semua format</option>
                        <option value="group_knockout">Grup + knockout</option>
                        <option value="league">Liga</option>
                        <option value="knockout">Knockout</option>
                    </select>

                    <label class="visually-hidden" for="dateFrom">Tanggal mulai dari</label>
                    <input type="date" id="dateFrom" class="filter-select" aria-label="Tanggal mulai dari">

                    <button type="button" class="btn-reset" id="resetFilters">
                        <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Reset
                    </button>
                </div>
            </div>

            @if($tournaments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="tournamentsTable">
                        <thead>
                            <tr>
                                <th scope="col">Turnamen</th>
                                <th scope="col">Format</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Status</th>
                                <th scope="col">Tim</th>
                                <th scope="col" class="text-end">Tindakan</th>
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
                                        <div class="tournament-name">
                                            {{ $tournament->name }}
                                            @if($tournament->is_featured)
                                                <span class="featured-badge" title="Turnamen unggulan di halaman utama">
                                                    <i class="bi bi-star-fill" aria-hidden="true"></i> Unggulan
                                                </span>
                                            @endif
                                        </div>
                                        <div class="tournament-location">
                                            <i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $tournament->location }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($tournament->type == 'group_knockout')
                                            <span class="type-badge">Grup + knockout</span>
                                        @elseif($tournament->type == 'league')
                                            <span class="type-badge">Liga</span>
                                        @else
                                            <span class="type-badge">Knockout</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="date-info">
                                            {{ $tournament->start_date->format('d M Y') }} sampai
                                            {{ $tournament->end_date->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($tournament->status == 'upcoming')
                                            <span class="status-badge status-upcoming">Akan datang</span>
                                        @elseif($tournament->status == 'ongoing')
                                            <span class="status-badge status-ongoing">Berlangsung</span>
                                        @elseif($tournament->status == 'completed')
                                            <span class="status-badge status-completed">Selesai</span>
                                        @else
                                            <span class="status-badge status-cancelled">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="count-info">
                                            {{ $tournament->teams->count() }}
                                            @if($tournament->type == 'group_knockout' && $tournament->groups_count)
                                                <small>{{ $tournament->groups_count }} grup</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="action-buttons">
                                            <form action="{{ route('admin.tournaments.feature', $tournament) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn-small {{ $tournament->is_featured ? 'btn-featured' : '' }}"
                                                    aria-label="{{ $tournament->is_featured ? 'Lepas dari unggulan' : 'Jadikan unggulan' }}: {{ $tournament->name }}"
                                                    title="{{ $tournament->is_featured ? 'Lepas dari unggulan halaman utama' : 'Jadikan unggulan di halaman utama' }}">
                                                    <i class="bi {{ $tournament->is_featured ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.tournaments.show', $tournament) }}" class="btn-small"
                                                aria-label="Lihat {{ $tournament->name }}" title="Lihat turnamen">
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </a>
                                            <a href="{{ route('admin.tournaments.edit', $tournament) }}" class="btn-small"
                                                aria-label="Edit {{ $tournament->name }}" title="Edit turnamen">
                                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                            </a>
                                            <form action="{{ route('admin.tournaments.destroy', $tournament) }}" method="POST"
                                                onsubmit="return confirm('Hapus turnamen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-small btn-delete"
                                                    aria-label="Hapus {{ $tournament->name }}" title="Hapus turnamen">
                                                    <i class="bi bi-trash" aria-hidden="true"></i>
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
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Menampilkan {{ $tournaments->firstItem() }}–{{ $tournaments->lastItem() }} dari
                            {{ $tournaments->total() }} turnamen
                        </div>

                        <nav class="pagination-controls" aria-label="Navigasi halaman turnamen">
                            @if($tournaments->onFirstPage())
                                <span class="pagination-btn disabled" aria-disabled="true">
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i> Sebelumnya
                                </span>
                            @else
                                <a href="{{ $tournaments->previousPageUrl() }}" class="pagination-btn">
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i> Sebelumnya
                                </a>
                            @endif

                            <div class="pagination-pages">
                                @php
                                    $current = $tournaments->currentPage();
                                    $last = $tournaments->lastPage();
                                    $start = max(1, $current - 2);
                                    $end = min($last, $current + 2);

                                    if ($start > 1) {
                                        echo '<a href="' . $tournaments->url(1) . '" class="page-link" aria-label="Halaman 1">1</a>';
                                        if ($start > 2) {
                                            echo '<span class="page-link disabled" aria-hidden="true">…</span>';
                                        }
                                    }

                                    for ($i = $start; $i <= $end; $i++) {
                                        if ($i == $current) {
                                            echo '<span class="page-link active" aria-current="page">' . $i . '</span>';
                                        } else {
                                            echo '<a href="' . $tournaments->url($i) . '" class="page-link" aria-label="Halaman ' . $i . '">' . $i . '</a>';
                                        }
                                    }

                                    if ($end < $last) {
                                        if ($end < $last - 1) {
                                            echo '<span class="page-link disabled" aria-hidden="true">…</span>';
                                        }
                                        echo '<a href="' . $tournaments->url($last) . '" class="page-link" aria-label="Halaman ' . $last . '">' . $last . '</a>';
                                    }
                                @endphp
                            </div>

                            @if($tournaments->hasMorePages())
                                <a href="{{ $tournaments->nextPageUrl() }}" class="pagination-btn">
                                    Berikutnya <i class="bi bi-chevron-right" aria-hidden="true"></i>
                                </a>
                            @else
                                <span class="pagination-btn disabled" aria-disabled="true">
                                    Berikutnya <i class="bi bi-chevron-right" aria-hidden="true"></i>
                                </span>
                            @endif
                        </nav>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-state-icon" aria-hidden="true">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h3 class="empty-state-title">Belum ada turnamen</h3>
                    <p class="empty-state-text">Buat turnamen pertama untuk mulai mengatur pertandingan dan peserta.</p>
                    @if(!$activeTournament)
                        <a href="{{ route('admin.tournaments.create.step', ['step' => 1]) }}" class="btn-create">
                            <i class="bi bi-plus-lg" aria-hidden="true"></i> Buat turnamen pertama
                        </a>
                    @endif
                </div>
            @endif
        </section>
    </main>
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
