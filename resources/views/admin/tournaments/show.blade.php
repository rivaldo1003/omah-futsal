@extends('layouts.admin')

@section('title', 'Detail Turnamen')

@section('styles')
    <style>
        .admin-tournament-show {
            --show-ink: #111111;
            --show-charcoal: #39393b;
            --show-muted: #707072;
            --show-cloud: #f5f5f5;
            --show-rule: #e5e5e5;
            --show-border: #cacacb;
            --show-success: #007d48;
            --show-danger: #d30005;
            color: var(--show-ink);
        }

        .admin-tournament-show .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .admin-tournament-show .page-header h1 {
            margin: 0 0 4px;
            color: var(--show-ink);
            font-size: 24px;
            font-weight: 600;
            line-height: 1.2;
        }

        .admin-tournament-show .page-subtitle,
        .admin-tournament-show .meta-item { color: var(--show-muted); }
        .admin-tournament-show .page-subtitle { margin: 0; font-size: 14px; }

        .admin-tournament-show .page-header .btn,
        .admin-tournament-show .btn-back {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 999px;
            padding: 0 18px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .admin-tournament-show .page-header .btn-primary {
            border-color: var(--show-ink);
            background: var(--show-ink);
            color: #fff;
        }

        .admin-tournament-show .btn-primary {
            border-color: var(--show-ink);
            background: var(--show-ink);
            color: #fff;
        }

        .admin-tournament-show .btn-primary:hover { border-color: var(--show-charcoal); background: var(--show-charcoal); color: #fff; }

        .admin-tournament-show .page-header .btn-outline-primary,
        .admin-tournament-show .btn-back {
            border: 1px solid var(--show-rule);
            background: var(--show-cloud);
            color: var(--show-ink);
        }

        .admin-tournament-show .page-header .btn-outline-primary:hover,
        .admin-tournament-show .btn-back:hover { border-color: var(--show-border); color: var(--show-ink); }

        .admin-tournament-show .tournament-header,
        .admin-tournament-show .compact-tabs,
        .admin-tournament-show .danger-zone {
            border: 1px solid var(--show-rule);
            border-radius: 8px;
            background: #fff;
        }

        .admin-tournament-show .tournament-header { padding: 24px; margin-bottom: 24px; }
        .admin-tournament-show .tournament-title { margin: 0 0 10px; font-size: 22px; font-weight: 600; line-height: 1.25; }
        .admin-tournament-show .tournament-meta { display: flex; flex-wrap: wrap; gap: 8px 18px; margin-bottom: 8px; }
        .admin-tournament-show .meta-item { display: inline-flex; align-items: center; gap: 7px; font-size: 14px; }
        .admin-tournament-show .tournament-header p { max-width: 760px; color: var(--show-charcoal) !important; line-height: 1.55; }

        .admin-tournament-show .badge-status,
        .admin-tournament-show .tab-badge,
        .admin-tournament-show .round-badge,
        .admin-tournament-show .team-group {
            display: inline-flex;
            align-items: center;
            border: 1px solid var(--show-rule);
            border-radius: 999px;
            background: var(--show-cloud);
            color: var(--show-charcoal);
            font-weight: 500;
            line-height: 1.4;
        }

        .admin-tournament-show .badge-status { padding: 4px 10px; font-size: 12px; }
        .admin-tournament-show .badge-status-ongoing { border-color: #b9dfcd; background: #edf7f1; color: var(--show-success); }
        .admin-tournament-show .badge-status-upcoming,
        .admin-tournament-show .badge-status-completed { color: var(--show-charcoal); }
        .admin-tournament-show .banner-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--show-rule);
        }
        .admin-tournament-show .banner-stats > div { padding: 0 16px; border-left: 1px solid var(--show-rule); }
        .admin-tournament-show .banner-stats > div:first-child { padding-left: 0; border-left: 0; }
        .admin-tournament-show .banner-stat-value { color: var(--show-ink); font-size: 22px; font-weight: 600; line-height: 1.2; font-variant-numeric: tabular-nums; }
        .admin-tournament-show .banner-stat-label { margin-top: 3px; color: var(--show-muted); font-size: 13px; }

        .admin-tournament-show .compact-tabs { margin-bottom: 24px; overflow: hidden; }
        .admin-tournament-show .tabs-header {
            display: flex;
            overflow-x: auto;
            border-bottom: 1px solid var(--show-rule);
            background: #fff;
            scrollbar-width: none;
        }
        .admin-tournament-show .tabs-header::-webkit-scrollbar { display: none; }
        .admin-tournament-show .tab-btn {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex: 0 0 auto;
            padding: 0 18px;
            border: 0;
            border-bottom: 2px solid transparent;
            background: transparent;
            color: var(--show-muted);
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            cursor: pointer;
        }
        .admin-tournament-show .tab-btn:hover { color: var(--show-ink); }
        .admin-tournament-show .tab-btn.active { border-bottom-color: var(--show-ink); color: var(--show-ink); }
        .admin-tournament-show .tab-btn:focus-visible,
        .admin-tournament-show .btn:focus-visible,
        .admin-tournament-show .btn-back:focus-visible { outline: 2px solid var(--show-ink); outline-offset: 2px; box-shadow: none; /* taste-skill-ignore: suppress Bootstrap's second focus halo. */ }
        .admin-tournament-show .tab-badge { min-width: 20px; justify-content: center; padding: 1px 6px; font-size: 11px; }
        .admin-tournament-show .tab-content { padding: 24px; }
        .admin-tournament-show .tab-content h6 { color: var(--show-ink); font-size: 15px; font-weight: 600; }
        .admin-tournament-show .btn-group .btn {
            border-color: var(--show-rule);
            border-radius: 999px;
            background: #fff;
            color: var(--show-charcoal);
            font-size: 13px;
        }
        .admin-tournament-show .btn-group .btn.active { border-color: var(--show-ink); background: var(--show-ink); color: #fff; }
        .admin-tournament-show .btn-group .btn:hover:not(.active) { border-color: var(--show-border); background: var(--show-cloud); color: var(--show-ink); }

        .admin-tournament-show .detail-table { margin-bottom: 0; }
        .admin-tournament-show .detail-table td { padding: 7px 10px 7px 0; border: 0; color: var(--show-ink); font-size: 14px; }
        .admin-tournament-show .detail-table td:first-child { width: 160px; color: var(--show-muted); }

        .admin-tournament-show .teams-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); column-gap: 24px; }
        .admin-tournament-show .team-card {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            padding: 12px 0;
            border-bottom: 1px solid var(--show-rule);
        }
        .admin-tournament-show .team-avatar {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid var(--show-rule);
            border-radius: 50%;
            background: var(--show-cloud);
            color: var(--show-ink);
            font-size: 17px;
            font-weight: 600;
        }
        .admin-tournament-show .team-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .admin-tournament-show .team-name { min-width: 0; flex: 1; margin: 0 0 4px; color: var(--show-ink); font-size: 14px; font-weight: 600; overflow-wrap: anywhere; }
        .admin-tournament-show .team-group { padding: 2px 8px; font-size: 11px; }
        .admin-tournament-show .group-badge { display: inline-block; padding: 3px 9px; border-radius: 999px; background: var(--show-cloud); color: var(--show-ink); font-size: 12px; font-weight: 600; }

        .admin-tournament-show .compact-table { width: 100%; border-collapse: collapse; border-spacing: 0; }
        .admin-tournament-show .compact-table th {
            padding: 10px 12px;
            border-bottom: 1px solid var(--show-border);
            background: #fff;
            color: var(--show-muted);
            font-size: 12px;
            font-weight: 500;
            text-align: left;
            white-space: nowrap;
        }
        .admin-tournament-show .compact-table td { padding: 12px; border-bottom: 1px solid var(--show-rule); color: var(--show-ink); font-size: 13px; vertical-align: middle; }
        .admin-tournament-show .compact-table tbody tr:last-child td { border-bottom: 0; }
        .admin-tournament-show .compact-table tbody tr:hover { background: #fafafa; }
        .admin-tournament-show .match-row { cursor: default; }
        .admin-tournament-show .match-teams { display: flex; align-items: center; justify-content: space-between; gap: 8px; min-width: 280px; }
        .admin-tournament-show .team-side { flex: 1; min-width: 0; text-align: left; }
        .admin-tournament-show .team-side.home { text-align: right; }
        .admin-tournament-show .team-name-sm { margin-bottom: 2px; font-size: 13px; font-weight: 600; overflow-wrap: anywhere; }
        .admin-tournament-show .team-group-sm,
        .admin-tournament-show .match-status,
        .admin-tournament-show .match-info { color: var(--show-muted); font-size: 12px; }
        .admin-tournament-show .match-score { min-width: 72px; padding: 0 8px; text-align: center; }
        .admin-tournament-show .score { color: var(--show-ink); font-size: 14px; font-weight: 600; font-variant-numeric: tabular-nums; }
        .admin-tournament-show .match-info { display: flex; align-items: center; gap: 8px; white-space: nowrap; }
        .admin-tournament-show .round-badge { padding: 3px 9px; font-size: 11px; }

        .admin-tournament-show .settings-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); column-gap: 24px; }
        .admin-tournament-show .setting-item { padding: 12px 0; border-bottom: 1px solid var(--show-rule); }
        .admin-tournament-show .setting-label { margin-bottom: 3px; color: var(--show-muted); font-size: 13px; }
        .admin-tournament-show .setting-value { color: var(--show-ink); font-size: 14px; font-weight: 600; }
        .admin-tournament-show .danger-zone { margin-top: 24px; padding: 20px 24px; }
        .admin-tournament-show .danger-header { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; color: var(--show-danger); font-weight: 600; }
        .admin-tournament-show .danger-text { max-width: 760px; margin-bottom: 14px; color: var(--show-charcoal); font-size: 14px; }
        .admin-tournament-show .btn-danger { border-radius: 999px; }
        .admin-tournament-show .empty-state { padding: 40px 16px; text-align: center; }
        .admin-tournament-show .empty-icon { margin-bottom: 12px; color: var(--show-muted); font-size: 28px; }
        .admin-tournament-show .empty-title { margin-bottom: 4px; color: var(--show-ink); font-size: 15px; font-weight: 600; }
        .admin-tournament-show .empty-text { max-width: 320px; margin: 0 auto 16px; color: var(--show-muted); font-size: 14px; }

        .admin-tournament-show .alert { border-radius: 8px; }
        .admin-tournament-show .alert-info { border-color: var(--show-rule); background: var(--show-cloud); color: var(--show-charcoal); }
        .admin-tournament-show .alert-info h6 { color: var(--show-ink); }
        .admin-tournament-show .alert-info .btn-primary { border-color: var(--show-ink); background: var(--show-ink); }

        @media (max-width: 768px) {
            .admin-tournament-show .page-header { align-items: flex-start; flex-direction: column; }
            .admin-tournament-show .page-header > .d-flex { width: 100%; }
            .admin-tournament-show .tournament-header > .d-flex { flex-direction: column; gap: 12px; }
            .admin-tournament-show .tournament-header > .d-flex > .text-end { text-align: left !important; }
            .admin-tournament-show .teams-list,
            .admin-tournament-show .settings-grid { grid-template-columns: 1fr; }
            .admin-tournament-show .banner-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); row-gap: 16px; }
            .admin-tournament-show .banner-stats > div:nth-child(3) { padding-left: 0; border-left: 0; }
            .admin-tournament-show .match-teams { min-width: 270px; }
            .admin-tournament-show .tab-content { padding: 18px; }
        }

        @media (max-width: 480px) {
            .admin-tournament-show .tournament-header { padding: 18px; }
            .admin-tournament-show .banner-stats > div { padding-right: 8px; padding-left: 10px; }
            .admin-tournament-show .banner-stats > div:nth-child(odd) { padding-left: 0; border-left: 0; }
            .admin-tournament-show .detail-table td:first-child { width: 125px; }
            .admin-tournament-show .tab-content { padding: 14px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-tournament-show *, .admin-tournament-show *::before, .admin-tournament-show *::after { transition: none !important; animation: none !important; }
        }
    </style>
@endsection

@section('content')
    <div class="admin-tournament-show">
    <!-- Page header -->
    <div class="page-header">
        <div>
            <h1>Detail Turnamen</h1>
            <p class="page-subtitle">Ringkasan, peserta, jadwal, dan aturan turnamen.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if($tournament->type === 'knockout')
                <a href="{{ route('admin.tournaments.draw.show', $tournament) }}" class="btn btn-outline-primary">
                    <i class="bi bi-diagram-3 me-1"></i> Undian knockout
                </a>
            @endif
            <a href="{{ route('admin.tournaments.edit', $tournament) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>
            <a href="{{ route('admin.tournaments.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali
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

    <!-- Banner turnamen -->
    <div class="tournament-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="tournament-title">{{ $tournament->name }}</h1>
                <div class="tournament-meta">
                    <span class="meta-item">
                        <i class="bi bi-geo-alt"></i>
                        {{ $tournament->location ?? 'Lokasi belum ditentukan' }}
                    </span>
                    <span class="meta-item">
                        <i class="bi bi-calendar"></i>
                        {{ $tournament->formatted_dates }}
                    </span>
                    <span class="meta-item">
                        <i class="bi bi-people"></i>
                        {{ $tournament->teams_count }} tim
                    </span>
                </div>
                @if($tournament->description)
                    <p class="mt-2 mb-0" style="font-size: 14px; color: var(--text-secondary);">{{ $tournament->description }}
                    </p>
                @endif
            </div>
            <div class="text-end">
                <span class="badge-status badge-status-{{ $tournament->status }}">
                    {{ ['upcoming' => 'Akan datang', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai'][$tournament->status] ?? ucfirst($tournament->status) }}
                </span>
                <div class="mt-2">
                    <small style="color: var(--text-secondary);">{{ ['knockout' => 'Knockout', 'group_knockout' => 'Grup + knockout', 'league' => 'Liga', 'group' => 'Grup'][$tournament->type] ?? ucfirst(str_replace('_', ' ', $tournament->type)) }}</small>
                </div>
            </div>
        </div>

        <div class="banner-stats">
            <div>
                <div class="banner-stat-value">{{ $tournament->teams_count }}</div>
                <div class="banner-stat-label">Tim</div>
            </div>
            <div>
                <div class="banner-stat-value">{{ $tournament->matches_count }}</div>
                <div class="banner-stat-label">Pertandingan</div>
            </div>
            <div>
                <div class="banner-stat-value">{{ $completedMatches ?? 0 }}</div>
                <div class="banner-stat-label">Selesai</div>
            </div>
            <div>
                <div class="banner-stat-value">{{ $tournament->duration }}</div>
                <div class="banner-stat-label">Hari</div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="compact-tabs">
        <div class="tabs-header">
            <button class="tab-btn active" data-tab="overview">
                <i class="bi bi-info-circle"></i>
                <span>Ringkasan</span>
            </button>
            <button class="tab-btn" data-tab="teams">
                <i class="bi bi-people"></i>
                <span>Tim</span>
                <span class="tab-badge">{{ $tournament->teams_count }}</span>
            </button>
            <button class="tab-btn" data-tab="matches">
                <i class="bi bi-calendar-event"></i>
                <span>Pertandingan</span>
                <span class="tab-badge">{{ $tournament->matches_count }}</span>
            </button>
            @if($tournament->type === 'knockout')
                <button class="tab-btn" data-tab="bracket">
                    <i class="bi bi-diagram-3"></i>
                    <span>Bagan & Undi</span>
                </button>
            @endif
            <button class="tab-btn" data-tab="settings">
                <i class="bi bi-gear"></i>
                <span>Pengaturan</span>
            </button>
        </div>

        <div class="tab-content">
            <!-- Tab ringkasan -->
            <div id="overview-tab" class="tab-pane active">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3"><i class="bi bi-info-square me-2"></i>Detail</h6>
                        <table class="table detail-table">
                            <tr>
                                <td>Penyelenggara</td>
                                <td>{{ $tournament->organizer ?? 'Belum ditentukan' }}</td>
                            </tr>
                            <tr>
                                <td>Lokasi</td>
                                <td>{{ $tournament->location ?? 'Belum ditentukan' }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Mulai</td>
                                <td>{{ $tournament->start_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Selesai</td>
                                <td>{{ $tournament->end_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>Dibuat</td>
                                <td>{{ $tournament->created_at->diffForHumans() }}</td>
                            </tr>
                            @if($tournament->type == 'group_knockout')
                                <tr>
                                    <td>Grup</td>
                                    <td>{{ $tournament->groups_count }}</td>
                                </tr>
                                <tr>
                                    <td>Kualifikasi per Grup</td>
                                    <td>{{ $tournament->qualify_per_group }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3"><i class="bi bi-bar-chart me-2"></i>Statistik Pertandingan</h6>
                        <div class="settings-grid">
                            <div class="setting-item">
                                <div class="setting-label">Durasi waktu normal</div>
                                <div class="setting-value">{{ $tournament->match_duration }} menit</div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-label">Istirahat antar babak</div>
                                <div class="setting-value">{{ $tournament->half_time }} menit</div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-label">Poin menang</div>
                                <div class="setting-value">{{ $tournament->points_win }}</div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-label">Poin seri</div>
                                <div class="setting-value">{{ $tournament->points_draw }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab tim -->
            <div id="teams-tab" class="tab-pane" style="display: none;">
                @if($tournament->teams_count > 0)
                    @if($tournament->type == 'group_knockout')
                        @php
                            $groupedTeams = $tournament->teams->groupBy('pivot.group_name');
                        @endphp
                        @foreach($groupedTeams as $groupName => $teams)
                            <h6 class="mb-3 mt-4">
                                <span class="group-badge me-2">Grup {{ $groupName }}</span>
                                <small class="text-secondary">{{ $teams->count() }} tim</small>
                            </h6>
                            <div class="teams-list mb-4">
                                @foreach($teams as $team)
                                    <div class="team-card">
                                        <div class="team-avatar">
                                            @if($team->logo_url)
                                                <img src="{{ $team->logo_url }}" alt="{{ $team->name }}"
                                                    onerror="this.onerror=null; this.parentElement.innerHTML='{{ strtoupper(substr($team->name, 0, 1)) }}';">
                                            @else
                                                {{ strtoupper(substr($team->name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div class="team-name">{{ $team->name }}</div>
                                        <div class="team-group">
                                            {{ $team->pivot->seed ? 'Seed #' . $team->pivot->seed : 'Seed tidak tersedia' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <div class="teams-list">
                            @foreach($tournament->teams as $team)
                                <div class="team-card">
                                    <div class="team-avatar">
                                        @if($team->logo_url)
                                            <img src="{{ $team->logo_url }}" alt="{{ $team->name }}"
                                                onerror="this.onerror=null; this.parentElement.innerHTML='{{ strtoupper(substr($team->name, 0, 1)) }}';">
                                        @else
                                            {{ strtoupper(substr($team->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="team-name">{{ $team->name }}</div>
                                    @if($tournament->type == 'knockout' || $tournament->type == 'league')
                                        <div class="team-group">
                                            Seed #{{ $team->pivot->seed ?? $loop->iteration }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h6 class="empty-title">Belum Ada Tim Terdaftar</h6>
                        <p class="empty-text">Tambahkan tim ke turnamen ini untuk memulai.</p>
                        <a href="{{ route('admin.tournaments.edit', $tournament) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Tim
                        </a>
                    </div>
                @endif
            </div>

            <!-- Tab pertandingan -->
            <div id="matches-tab" class="tab-pane" style="display: none;">
                @if($tournament->matches_count > 0)
                    <div class="mb-3">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active" data-filter="all">Semua</button>
                            <button type="button" class="btn btn-outline-secondary" data-filter="completed">Selesai</button>
                            <button type="button" class="btn btn-outline-secondary" data-filter="upcoming">Akan datang</button>
                            <button type="button" class="btn btn-outline-secondary" data-filter="ongoing">Berlangsung</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="compact-table">
                            <thead>
                                <tr>
                                    <th>Pertandingan</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Lokasi</th>
                                    <th>Ronde</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tournament->matches as $match)
                                    <tr class="match-row" data-status="{{ $match->status }}">
                                        <td>
                                            <div class="match-teams">
                                                <div class="team-side">
                                                    <div class="team-name-sm">{{ $match->homeTeam->name ?? 'Belum ditentukan' }}</div>
                                                    @if($match->group_name)
                                                        <div class="team-group-sm">Grup {{ $match->group_name }}</div>
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
                                                    <div class="match-status">{{ ['upcoming' => 'Akan datang', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai'][$match->status] ?? ucfirst($match->status) }}</div>
                                                </div>
                                                <div class="team-side home">
                                                    <div class="team-name-sm">{{ $match->awayTeam->name ?? 'Belum ditentukan' }}</div>
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
                                            <small>{{ $match->venue ?? 'Lapangan belum ditentukan' }}</small>
                                        </td>
                                        <td>
                                            <span class="round-badge">
                                                {{ ucfirst(str_replace('_', ' ', $match->round_type)) }}
                                            </span>
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
                        <h6 class="empty-title">Belum Ada Pertandingan</h6>
                        <p class="empty-text">Jadwalkan pertandingan untuk mengisi turnamen ini.</p>
                        <a href="{{ route('admin.matches.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Jadwalkan Pertandingan
                        </a>
                    </div>
                @endif
            </div>

            @if($tournament->type === 'knockout')
                <!-- Tab bagan & undi tim (knockout) -->
                <div id="bracket-tab" class="tab-pane" style="display: none;">
                    <div class="alert alert-info mb-0">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h6 class="mb-1"><i class="bi bi-diagram-3 me-2"></i>Bagan knockout</h6>
                                <p class="mb-0">Undian, BYE, progres pemenang, dan pertandingan dikelola di halaman Undian Knockout.</p>
                            </div>
                            <a href="{{ route('admin.tournaments.draw.show', $tournament) }}" class="btn btn-primary btn-sm">
                                Buka undian knockout <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tab pengaturan -->
            <div id="settings-tab" class="tab-pane" style="display: none;">
                <div class="settings-grid">
                    <div class="setting-item">
                        <div class="setting-label">Durasi waktu normal</div>
                        <div class="setting-value">{{ $tournament->match_duration }} menit</div>
                    </div>
                    <div class="setting-item">
                        <div class="setting-label">Istirahat antar babak</div>
                        <div class="setting-value">{{ $tournament->half_time }} menit</div>
                    </div>
                    <div class="setting-item">
                        <div class="setting-label">Extra time per babak</div>
                        <div class="setting-value">{{ $settings['extra_time'] ?? 10 }} menit</div>
                    </div>
                    <div class="setting-item">
                        <div class="setting-label">Maksimal pemain cadangan</div>
                        <div class="setting-value">{{ $settings['max_substitutes'] ?? 5 }}</div>
                    </div>
                    <div class="setting-item">
                        <div class="setting-label">Batas kartu kuning</div>
                        <div class="setting-value">{{ $settings['yellow_card_suspension'] ?? 3 }}</div>
                    </div>
                    @if(in_array($tournament->type, ['knockout', 'group_knockout'], true))
                        <div class="setting-item">
                            <div class="setting-label">Perebutan juara 3/4</div>
                            <div class="setting-value">
                                {{ ($settings['knockout_third_place'] ?? $tournament->knockout_third_place) ? 'Aktif' : 'Tidak aktif' }}
                            </div>
                        </div>
                    @endif
                    <div class="setting-item">
                        <div class="setting-label">Poin (menang / seri / kalah)</div>
                        <div class="setting-value">
                            {{ $tournament->points_win }} / {{ $tournament->points_draw }} / {{ $tournament->points_loss }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger zone -->
    <div class="danger-zone">
        <div class="danger-header">
            <i class="bi bi-exclamation-triangle"></i>
            <span>Zona Bahaya</span>
        </div>
        <p class="danger-text">Menghapus turnamen ini akan menghapus semua data terkait termasuk pertandingan, klasemen, dan
            statistik. Tindakan ini tidak dapat dibatalkan.</p>
        <form action="{{ route('admin.tournaments.destroy', $tournament) }}" method="POST"
            onsubmit="return confirm('Yakin? Turnamen dan seluruh datanya akan dihapus permanen!')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-trash me-1"></i> Hapus Turnamen
            </button>
        </form>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ===== Tab switching =====
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const tabId = this.getAttribute('data-tab');

                    tabBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

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

            // ===== Filter pertandingan =====
            const filterBtns = document.querySelectorAll('[data-filter]');
            const matchRows = document.querySelectorAll('.match-row');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const filter = this.getAttribute('data-filter');

                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    matchRows.forEach(row => {
                        row.style.display = (filter === 'all' || row.getAttribute('data-status') === filter) ? '' : 'none';
                    });
                });
            });

            // ===== Klik baris pertandingan =====
            matchRows.forEach(row => {
                row.addEventListener('click', function () {
                    // Fungsi detail pertandingan bisa ditambahkan di sini
                });
            });

            // ===== Auto-dismiss alerts =====
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    try {
                        new bootstrap.Alert(alert).close();
                    } catch (e) {
                        // Silently fail
                    }
                });
            }, 3000);
        });
    </script>
@endsection
