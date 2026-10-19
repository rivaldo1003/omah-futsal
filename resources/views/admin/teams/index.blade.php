@extends('layouts.admin')

@section('title', 'Data Tim - OFS Futsal Center Admin')

@section('styles')
    <style>

        .admin-teams-page {
            --teams-ink: #111111;
            --teams-charcoal: #39393b;
            --teams-muted: #707072;
            --teams-line: #cacacb;
            --teams-line-soft: #e5e5e5;
            --teams-cloud: #f5f5f5;
            --teams-white: #ffffff;
            --teams-success: #007d48;
            --teams-warning: #8a4b00;
            --teams-danger: #b42318;
            max-width: 1440px;
            margin: 0 auto;
            color: var(--teams-ink);
            font-size: 16px;
            line-height: 1.5;
        }

        .admin-teams-page .page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px 24px;
            margin-bottom: 24px;
        }

        .admin-teams-page .page-header h1 {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 8px;
            color: var(--teams-ink);
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
        }

        .admin-teams-page .page-header h1 i {
            font-size: .78em;
        }

        .admin-teams-page .page-subtitle {
            max-width: 66ch;
            margin: 0;
            color: var(--teams-muted);
            font-size: 15px;
        }

        .admin-teams-page .header-actions,
        .admin-teams-page .filters-row,
        .admin-teams-page .action-buttons,
        .admin-teams-page .pagination-container,
        .admin-teams-page .pagination-controls {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .admin-teams-page .header-actions {
            justify-content: flex-end;
        }

        .admin-teams-page .search-box {
            position: relative;
            width: min(300px, 100%);
        }

        .admin-teams-page .search-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            color: var(--teams-muted);
            font-size: 14px;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .admin-teams-page .search-input,
        .admin-teams-page .filter-select {
            min-height: 44px;
            border: 1px solid var(--teams-line);
            border-radius: 9999px;
            background: var(--teams-white);
            color: var(--teams-ink);
            font: inherit;
            font-size: 14px;
            line-height: 1.3;
        }

        .admin-teams-page .search-input {
            width: 100%;
            padding: 9px 16px 9px 42px;
            background: var(--teams-cloud);
        }

        .admin-teams-page .filter-select {
            min-width: 160px;
            padding: 8px 38px 8px 16px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23111111' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 14px;
            cursor: pointer;
        }

        .admin-teams-page :is(a, button, input, select):focus-visible {
            outline: 2px solid var(--teams-ink);
            outline-offset: 3px;
        }

        .admin-teams-page .search-input:focus,
        .admin-teams-page .filter-select:focus {
            border-color: var(--teams-ink);
            box-shadow: 0 0 0 3px var(--teams-cloud);
        }

        .admin-teams-page .btn-create,
        .admin-teams-page .btn-secondary,
        .admin-teams-page .pagination-controls .btn {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border: 1px solid transparent;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            text-decoration: none;
            white-space: nowrap;
            transition: background-color 150ms ease-out, border-color 150ms ease-out, color 150ms ease-out;
        }

        .admin-teams-page .btn-create {
            border-color: var(--teams-ink);
            background: var(--teams-ink);
            color: var(--teams-white);
        }

        .admin-teams-page .btn-create:hover {
            border-color: var(--teams-charcoal);
            background: var(--teams-charcoal);
            color: var(--teams-white);
        }

        .admin-teams-page .btn-secondary,
        .admin-teams-page .pagination-controls .btn {
            background: var(--teams-cloud);
            color: var(--teams-ink);
        }

        .admin-teams-page .btn-secondary:hover,
        .admin-teams-page .pagination-controls a.btn:hover {
            background: #eeeeee;
            color: var(--teams-ink);
        }

        .admin-teams-page .btn-sm {
            min-height: 40px;
            padding: 8px 16px;
            font-size: 13px;
        }

        .admin-teams-page .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 16px;
            padding: 14px 16px;
            border: 1px solid var(--teams-line-soft);
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.5;
        }

        .admin-teams-page .alert-success {
            background: #edf6f1;
            color: var(--teams-success);
        }

        .admin-teams-page .alert-danger {
            background: #fdf1f0;
            color: var(--teams-danger);
        }

        .admin-teams-page .alert-warning {
            border-color: #f0dfbd;
            background: #fff6e7;
            color: var(--teams-warning);
        }

        .admin-teams-page .alert-close {
            flex: 0 0 auto;
            margin-left: auto;
        }

        .admin-teams-page .filters-row {
            margin-bottom: 16px;
        }

        .admin-teams-page .main-card {
            overflow: hidden;
            border: 1px solid var(--teams-line-soft);
            border-radius: 8px;
            background: var(--teams-white);
        }

        .admin-teams-page .main-card .card-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--teams-line-soft);
            background: var(--teams-white);
        }

        .admin-teams-page .main-card .card-header h2 {
            margin: 0;
            color: var(--teams-ink);
            font-size: 18px;
            font-weight: 500;
            line-height: 1.35;
        }

        .admin-teams-page .table-responsive {
            width: 100%;
        }

        .admin-teams-page .table {
            min-width: 980px;
            margin: 0;
            color: var(--teams-charcoal);
            font-size: 14px;
            --bs-table-bg: transparent;
            --bs-table-hover-bg: var(--teams-cloud);
        }

        .admin-teams-page .table > :not(caption) > * > * {
            padding: 14px 16px;
            border-bottom-color: var(--teams-line-soft);
            vertical-align: middle;
        }

        .admin-teams-page .table thead th {
            background: var(--teams-white);
            color: var(--teams-muted);
            font-size: 12px;
            font-weight: 500;
            letter-spacing: .025em;
            white-space: nowrap;
        }

        .admin-teams-page .table tbody tr:last-child > * {
            border-bottom: 0;
        }

        .admin-teams-page .team-cell {
            min-width: 280px;
        }

        .admin-teams-page .team-identity {
            display: flex;
            min-width: 0;
            align-items: center;
        }

        .admin-teams-page .team-logo-container,
        .admin-teams-page .team-avatar {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            overflow: hidden;
            border: 1px solid var(--teams-line-soft);
            border-radius: 50%;
            background: var(--teams-cloud);
        }

        .admin-teams-page .team-logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .admin-teams-page .team-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--teams-muted);
            font-size: 14px;
            font-weight: 500;
        }

        .admin-teams-page .team-name {
            color: var(--teams-ink);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }

        .admin-teams-page .team-name:hover {
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .admin-teams-page .team-details,
        .admin-teams-page .pagination-info,
        .admin-teams-page .team-details-note {
            color: var(--teams-muted);
            font-size: 13px;
        }

        .admin-teams-page .toggle-details {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 5px;
            padding: 0;
            border: 0;
            background: transparent;
            color: var(--teams-muted);
            font: inherit;
            font-size: 12px;
            cursor: pointer;
        }

        .admin-teams-page .toggle-details:hover {
            color: var(--teams-ink);
        }

        .admin-teams-page .team-expanded-info {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--teams-line-soft);
        }

        .admin-teams-page .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(145px, 1fr));
            gap: 8px 14px;
        }

        .admin-teams-page .info-item {
            min-width: 0;
            color: var(--teams-muted);
            font-size: 12px;
            overflow-wrap: anywhere;
        }

        .admin-teams-page .info-label {
            color: var(--teams-charcoal);
            font-weight: 500;
        }

        .admin-teams-page .info-value {
            color: var(--teams-ink);
        }

        .admin-teams-page .stats-badge,
        .admin-teams-page .tournament-badge,
        .admin-teams-page .staff-badge,
        .admin-teams-page .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            max-width: 100%;
            padding: 5px 10px;
            border: 1px solid var(--teams-line-soft);
            border-radius: 9999px;
            background: var(--teams-cloud);
            color: var(--teams-charcoal);
            font-size: 12px;
            font-weight: 400;
            line-height: 1.25;
            vertical-align: middle;
        }

        .admin-teams-page .position-chip {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 9999px;
            background: var(--teams-cloud);
            color: var(--teams-charcoal);
            font-size: 12px;
            line-height: 1.25;
        }

        .admin-teams-page .tournament-list,
        .admin-teams-page .staff-list {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            gap: 5px;
            max-width: 240px;
        }

        .admin-teams-page .tournament-badge,
        .admin-teams-page .staff-badge {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-teams-page .status-active {
            border-color: #d4e9dd;
            background: #edf6f1;
            color: var(--teams-success);
        }

        .admin-teams-page .status-pending {
            border-color: #f0dfbd;
            background: #fff6e7;
            color: var(--teams-warning);
        }

        .admin-teams-page .status-inactive {
            border-color: var(--teams-line-soft);
            background: var(--teams-cloud);
            color: var(--teams-muted);
        }

        .admin-teams-page .action-buttons {
            justify-content: flex-end;
            gap: 6px;
        }

        .admin-teams-page .btn-action {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 1px solid var(--teams-line-soft);
            border-radius: 50%;
            background: var(--teams-white);
            color: var(--teams-charcoal);
            font-size: 15px;
            text-decoration: none;
            transition: background-color 150ms ease-out, color 150ms ease-out;
        }

        .admin-teams-page .btn-action:hover {
            background: var(--teams-cloud);
            color: var(--teams-ink);
        }

        .admin-teams-page .pagination-container {
            justify-content: space-between;
            padding: 16px 20px;
            border-top: 1px solid var(--teams-line-soft);
        }

        .admin-teams-page .pagination-controls .btn.disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        .admin-teams-page .empty-state {
            padding: 48px 20px;
            text-align: center;
        }

        .admin-teams-page .empty-state-icon {
            margin-bottom: 12px;
            color: var(--teams-muted);
            font-size: 32px;
        }

        .admin-teams-page .empty-state-title {
            margin: 0 0 6px;
            color: var(--teams-ink);
            font-size: 18px;
            font-weight: 500;
        }

        .admin-teams-page .empty-state-text {
            max-width: 54ch;
            margin: 0 auto 16px;
            color: var(--teams-muted);
            font-size: 14px;
        }

        .admin-teams-page .modal-content {
            overflow: hidden;
            border: 1px solid var(--teams-line-soft);
            border-radius: 8px;
            background: var(--teams-white);
            box-shadow: none;
        }

        .admin-teams-page .modal-header,
        .admin-teams-page .modal-footer {
            padding: 16px 20px;
            border-color: var(--teams-line-soft) !important;
        }

        .admin-teams-page .modal-title {
            color: var(--teams-ink);
            font-size: 18px;
            font-weight: 500;
        }

        .admin-teams-page .modal-body {
            color: var(--teams-charcoal);
        }

        .admin-teams-page .modal-footer .btn {
            min-height: 44px;
            padding: 9px 18px;
            border-radius: 9999px;
        }

        .admin-teams-page .modal-footer .btn-outline-secondary {
            border-color: transparent;
            background: var(--teams-cloud);
            color: var(--teams-ink);
        }

        .admin-teams-page .modal-footer .btn-danger {
            border-color: var(--teams-danger);
            background: var(--teams-danger);
            color: var(--teams-white);
        }

        .admin-teams-page .modal .alert-warning {
            border: 1px solid #f0dfbd;
            border-radius: 8px;
        }

        @media (max-width: 767.98px) {
            .admin-teams-page .page-header {
                align-items: flex-start;
                margin-bottom: 20px;
            }

            .admin-teams-page .header-actions,
            .admin-teams-page .search-box,
            .admin-teams-page .header-actions .btn-create {
                width: 100%;
            }

            .admin-teams-page .filters-row {
                align-items: stretch;
            }

            .admin-teams-page .filters-row .filter-select,
            .admin-teams-page .filters-row .btn,
            .admin-teams-page .filters-row .btn-secondary {
                flex: 1 1 100%;
                width: 100%;
            }

            .admin-teams-page .main-card .card-header {
                padding: 16px;
            }

            .admin-teams-page .pagination-container {
                align-items: flex-start;
                flex-direction: column;
                padding: 16px;
            }

            .admin-teams-page .pagination-controls {
                width: 100%;
                justify-content: space-between;
            }

            .admin-teams-page .info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-teams-page *,
            .admin-teams-page *::before,
            .admin-teams-page *::after {
                transition: none !important;
                animation: none !important;
            }
        }
        </style>
@endsection

@section('content')
    <main class="admin-teams-page">
        <header class="page-header">
            <div>
                <h1>
                    <i class="bi bi-people" aria-hidden="true"></i> Data Tim
                </h1>
                <p class="page-subtitle">Kelola daftar tim, pemain, staf, dan status pendaftaran turnamen.</p>
            </div>

            <div class="header-actions">
                <div class="search-box">
                    <i class="bi bi-search search-icon" aria-hidden="true"></i>
                    <input type="search" id="searchInput" placeholder="Cari tim atau pelatih" class="form-control search-input"
                        aria-label="Cari nama tim atau pelatih" value="{{ request('search') }}">
                </div>

                <a href="{{ route('admin.teams.create') }}" class="btn-create">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah tim
                </a>
            </div>
        </header>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close alert-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close alert-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

        <div class="filters-row" role="group" aria-label="Filter dan urutan tim">
            <label class="visually-hidden" for="statusFilter">Filter status tim</label>
        <select id="statusFilter" class="filter-select" aria-label="Filter status tim">
            <option value="">Semua status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>

            <label class="visually-hidden" for="sortFilter">Urutkan tim</label>
        <select id="sortFilter" class="filter-select" aria-label="Urutkan tim">
            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Terbaru</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
        </select>

        @if(request()->has('search') || request()->has('status') || request()->has('sort'))
                <a href="{{ route('admin.teams.index') }}" class="btn-secondary btn-sm">
                    <i class="bi bi-x" aria-hidden="true"></i> Hapus filter
            </a>
        @endif
    </div>

    <div class="main-card">
        <div class="card-header">
                <h2>Daftar tim</h2>
        </div>

        <div class="card-body p-0">
            @if($teams->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="teamsTable">
                        <thead>
                            <tr>
                                    <th scope="col">Tim</th>
                                    <th scope="col">Pemain</th>
                                    <th scope="col">Turnamen</th>
                                    <th scope="col">Staf pelatih</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teams as $team)
                                <tr>
                                    <td class="team-cell">
                                            <div class="team-identity">
                                            @if($team->logo && Storage::disk('public')->exists($team->logo))
                                                <div class="team-logo-container me-3">
                                                    <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}">
                                                </div>
                                            @elseif($team->logo && filter_var($team->logo, FILTER_VALIDATE_URL))
                                                <div class="team-logo-container me-3">
                                                    <img src="{{ $team->logo }}" alt="{{ $team->name }}">
                                                </div>
                                            @else
                                                <div class="team-avatar me-3">
                                                    {{ strtoupper(substr($team->name, 0, 1)) }}
                                                </div>
                                            @endif

                                            <div style="flex: 1; min-width: 0;">
                                                    <a href="{{ route('admin.teams.show', $team) }}" class="team-name">
                                                    {{ $team->name }}
                                                </a>
                                                <div class="team-details">
                                                    @if($team->short_name)
                                                        <span class="me-2">({{ $team->short_name }})</span>
                                                    @endif
                                                        {{ $team->coach_name ?? $team->head_coach ?? 'Pelatih belum diisi' }}
                                                </div>

                                                    <button type="button" class="toggle-details" id="toggle-details-{{ $team->id }}"
                                                        onclick="toggleDetails('{{ $team->id }}')" aria-expanded="false"
                                                        aria-controls="details-{{ $team->id }}">
                                                        <i class="bi bi-chevron-down" id="icon-{{ $team->id }}" aria-hidden="true"></i>
                                                        Info lainnya
                                                    </button>

                                                <div class="team-expanded-info" id="details-{{ $team->id }}" style="display: none;">
                                                    <div class="info-grid">
                                                        @if($team->founded_year)
                                                            <div class="info-item">
                                                                <span class="info-label">Berdiri:</span>
                                                                <span class="info-value">{{ $team->founded_year }}</span>
                                                            </div>
                                                        @endif

                                                        @if($team->home_venue)
                                                            <div class="info-item">
                                                                <span class="info-label">Venue:</span>
                                                                <span class="info-value">{{ Str::limit($team->home_venue, 15) }}</span>
                                                            </div>
                                                        @endif

                                                        @if($team->primary_color)
                                                            <div class="info-item">
                                                                <span class="info-label">Warna:</span>
                                                                <span class="info-value">
                                                                    <span
                                                                        style="display: inline-block; width: 12px; height: 12px; background-color: {{ $team->primary_color }}; border-radius: 50%; vertical-align: middle; margin-right: 4px;"></span>
                                                                    {{ $team->primary_color }}
                                                                </span>
                                                            </div>
                                                        @endif

                                                        @if($team->email)
                                                            <div class="info-item">
                                                                <span class="info-label">Email:</span>
                                                                <span class="info-value">{{ Str::limit($team->email, 15) }}</span>
                                                            </div>
                                                        @endif

                                                        @if($team->phone)
                                                            <div class="info-item">
                                                                <span class="info-label">Telepon:</span>
                                                                <span class="info-value">{{ $team->phone }}</span>
                                                            </div>
                                                        @endif

                                                        @if($team->website)
                                                            <div class="info-item">
                                                                <span class="info-label">Website:</span>
                                                                <span
                                                                    class="info-value">{{ Str::limit(parse_url($team->website, PHP_URL_HOST) ?: $team->website, 15) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="stats-badge">
                                                <i class="bi bi-people" aria-hidden="true"></i>
                                            {{ $team->players->count() }} pemain
                                        </span>
                                        @if($team->players->count() > 0)
                                            <div class="mt-1">
                                                <small class="text-secondary">
                                                    @php
                                                        $positions = $team->players->groupBy('position')->map->count();
                                                        $topPositions = $positions->sortDesc()->take(2);
                                                    @endphp
                                                    @foreach($topPositions as $position => $count)
                                                            <span class="position-chip">{{ $position }}: {{ $count }}</span>
                                                    @endforeach
                                                </small>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($team->tournaments->count() > 0)
                                                <div class="tournament-list">
                                                @foreach($team->tournaments->take(3) as $tournament)
                                                    <span class="tournament-badge">{{ Str::limit($tournament->name, 20) }}</span>
                                                @endforeach
                                                @if($team->tournaments->count() > 3)
                                                    <span class="text-secondary" style="font-size: 12px;">
                                                        +{{ $team->tournaments->count() - 3 }} lainnya
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                                <span class="text-secondary">Belum terdaftar</span>
                                        @endif
                                    </td>
                                    <td style="min-width: 160px;">
                                            <div class="staff-list">
                                            @if($team->head_coach)
                                                    <span class="staff-badge" title="Kepala pelatih: {{ $team->head_coach }}">
                                                        {{ Str::limit($team->head_coach, 20) }}
                                                </span>
                                            @endif

                                            @if($team->assistant_coach)
                                                    <span class="staff-badge" title="Asisten pelatih: {{ $team->assistant_coach }}">
                                                        {{ Str::limit($team->assistant_coach, 20) }}
                                                </span>
                                            @endif

                                            @if($team->goalkeeper_coach)
                                                    <span class="staff-badge" title="Pelatih kiper: {{ $team->goalkeeper_coach }}">
                                                        {{ Str::limit($team->goalkeeper_coach, 20) }}
                                                </span>
                                            @endif

                                            @if($team->kitman)
                                                    <span class="staff-badge" title="Petugas perlengkapan: {{ $team->kitman }}">
                                                        {{ Str::limit($team->kitman, 20) }}
                                                </span>
                                            @endif

                                            @if(!$team->head_coach && !$team->assistant_coach && !$team->goalkeeper_coach && !$team->kitman)
                                                    <span class="text-secondary">Belum ada data</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($team->status == 'active')
                                            <span class="status-badge status-active">Aktif</span>
                                        @elseif($team->status == 'pending')
                                                <span class="status-badge status-pending">Menunggu</span>
                                        @else
                                            <span class="status-badge status-inactive">Nonaktif</span>
                                        @endif

                                        @if($team->created_at)
                                            <div class="mt-1">
                                                <small
                                                    class="text-secondary">{{ $team->created_at->timezone(\App\Support\BusinessTime::TIMEZONE)->format('d/m/Y') }}</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="action-buttons">
                                                <a href="{{ route('admin.teams.show', $team) }}" class="btn-action"
                                                    title="Lihat detail" aria-label="Lihat detail tim {{ $team->name }}">
                                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                            </a>
                                                <a href="{{ route('admin.teams.edit', $team) }}" class="btn-action"
                                                    title="Edit" aria-label="Edit tim {{ $team->name }}">
                                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                            </a>
                                                <button type="button" class="btn-action" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $team->id }}" title="Hapus"
                                                    aria-label="Hapus tim {{ $team->name }}">
                                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($teams->hasPages())
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Menampilkan {{ $teams->firstItem() }}–{{ $teams->lastItem() }} dari {{ $teams->total() }} tim
                        </div>

                        <div class="pagination-controls d-flex align-items-center gap-2">
                            @if($teams->onFirstPage())
                                    <span class="btn btn-sm disabled" aria-disabled="true">
                                        <i class="bi bi-chevron-left" aria-hidden="true"></i> Sebelumnya
                                </span>
                            @else
                                    <a href="{{ $teams->previousPageUrl() }}" class="btn btn-sm">
                                        <i class="bi bi-chevron-left" aria-hidden="true"></i> Sebelumnya
                                </a>
                            @endif

                            <span class="pagination-info">Halaman {{ $teams->currentPage() }} / {{ $teams->lastPage() }}</span>

                            @if($teams->hasMorePages())
                                    <a href="{{ $teams->nextPageUrl() }}" class="btn btn-sm">
                                        Berikutnya <i class="bi bi-chevron-right" aria-hidden="true"></i>
                                </a>
                            @else
                                    <span class="btn btn-sm disabled" aria-disabled="true">
                                        Berikutnya <i class="bi bi-chevron-right" aria-hidden="true"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h4 class="empty-state-title">Belum ada tim</h4>
                    <p class="empty-state-text">
                        @if(request()->has('search') || request()->has('status') || request()->has('sort'))
                                Tidak ada tim yang cocok dengan pencarian atau filter. Ubah kriteria lalu coba lagi.
                        @else
                            Tambahkan tim pertama untuk mulai mengelola turnamen.
                        @endif
                    </p>
                        <a href="{{ route('admin.teams.create') }}" class="btn-create">
                            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah tim
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Modals -->
    @foreach($teams as $team)
        <div class="modal fade" id="deleteModal{{ $team->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title">Hapus tim</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <i class="bi bi-exclamation-triangle text-danger fs-1"></i>
                        </div>
                        <p class="text-center">
                            Hapus tim "<strong>{{ $team->name }}</strong>"?
                        </p>
                        <div class="alert alert-warning mt-3 text-start">
                            <i class="bi bi-info-circle me-2"></i>
                            Tim ini memiliki {{ $team->players->count() }} pemain dan terdaftar di
                            {{ $team->tournaments->count() }} turnamen.
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Hapus tim
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    </main>
@endsection

@section('scripts')
    <script>
        // Search dengan debounce
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('keyup', function (e) {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                updateFilters();
            }, 500);
        });

        // Filter change handlers
        document.getElementById('statusFilter').addEventListener('change', updateFilters);
        document.getElementById('sortFilter').addEventListener('change', updateFilters);

        function updateFilters() {
            const search = document.getElementById('searchInput').value;
            const status = document.getElementById('statusFilter').value;
            const sort = document.getElementById('sortFilter').value;

            let url = '{{ route("admin.teams.index") }}?';

            if (search) {
                url += 'search=' + encodeURIComponent(search) + '&';
            }

            if (status) {
                url += 'status=' + status + '&';
            }

            if (sort) {
                url += 'sort=' + sort;
            }

            // Remove trailing & if exists
            if (url.endsWith('&')) {
                url = url.slice(0, -1);
            }

            window.location.href = url;
        }

        // Enter key to submit search
        document.getElementById('searchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                clearTimeout(searchTimeout);
                updateFilters();
            }
        });

        // Toggle details function
        function toggleDetails(teamId) {
            const detailsDiv = document.getElementById('details-' + teamId);
            const icon = document.getElementById('icon-' + teamId);
            const toggleButton = document.getElementById('toggle-details-' + teamId);

            if (detailsDiv.style.display === 'none') {
                detailsDiv.style.display = 'block';
                toggleButton.setAttribute('aria-expanded', 'true');
                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-up');
            } else {
                detailsDiv.style.display = 'none';
                toggleButton.setAttribute('aria-expanded', 'false');
                icon.classList.remove('bi-chevron-up');
                icon.classList.add('bi-chevron-down');
            }
        }

        // Auto-dismiss alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.admin-teams-page > .alert.alert-dismissible');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endsection
