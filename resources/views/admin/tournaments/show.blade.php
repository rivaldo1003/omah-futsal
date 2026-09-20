@extends('layouts.admin')

@section('title', 'Detail Turnamen')

@section('styles')
    <style>
        /* ===== Tournament detail page — design guidelines ===== */

        :root {
            --primary: var(--accent);
            --secondary: var(--text-secondary);
            --success: #1E7A46;
            --warning: #B45309;
            --danger: #c01c28;
            --dark: var(--text-primary);
            --light: var(--surface);
            --card-bg: var(--bg);
            --radius: 12px;
        }

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

        .btn-back {
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text-primary);
            border-radius: 6px;
            height: 40px;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-back:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Tournament header banner */
        .tournament-header {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 24px;
        }

        .tournament-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .tournament-meta {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .badge-status {
            padding: 2px 12px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 12px;
        }

        .badge-status-ongoing {
            background: #FDF6EC;
            border: 1px solid rgba(180, 83, 9, 0.2);
            color: #B45309;
        }

        .badge-status-upcoming {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .badge-status-completed {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        /* Statistik di dalam banner */
        .banner-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .banner-stat-value {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .banner-stat-label {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .tab-badge {
            font-size: 11px;
            font-weight: 500;
            padding: 0 8px;
            border-radius: 999px;
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .group-badge {
            font-size: 12px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 6px;
            background: var(--accent);
            color: #fff;
        }

        .round-badge {
            font-size: 12px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 6px;
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        /* Tabel detail ringkasan */
        .detail-table {
            margin-bottom: 0;
        }

        .detail-table td {
            padding: 6px 0;
            border: 0;
            font-size: 14px;
            color: var(--text-primary);
        }

        .detail-table td:first-child {
            width: 140px;
            color: var(--text-secondary);
        }

        /* Tabs */
        .compact-tabs {
            background: var(--card-bg);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            margin-bottom: 24px;
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
            padding: 12px 20px;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            color: var(--secondary);
            font-weight: 500;
            font-size: 14px;
            white-space: nowrap;
            cursor: pointer;
            transition: color 0.15s ease, border-color 0.15s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn:hover {
            color: var(--primary);
        }

        .tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .tab-btn .badge {
            font-size: 11px;
            font-weight: 500;
        }

        .tab-content {
            padding: 24px;
        }

        /* Teams list */
        .teams-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }

        .team-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px;
            text-align: center;
            transition: border-color 0.15s ease;
        }

        .team-card:hover {
            border-color: var(--primary);
        }

        .team-avatar {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 20px;
            margin: 0 auto 12px;
            border-radius: 8px;
            overflow: hidden;
            background: var(--light);
        }

        .team-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .team-name {
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 4px;
            font-size: 14px;
        }

        .team-group {
            font-size: 12px;
            color: var(--secondary);
            padding: 2px 8px;
            background: var(--light);
            border: 1px solid var(--border);
            border-radius: 6px;
            display: inline-block;
        }

        /* Matches table */
        .compact-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .compact-table thead th {
            background: var(--light);
            color: var(--secondary);
            font-weight: 500;
            font-size: 13px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
        }

        .compact-table tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            color: var(--dark);
            font-size: 14px;
        }

        .compact-table tbody tr:last-child td {
            border-bottom: none;
        }

        .compact-table tbody tr:hover {
            background: var(--light);
        }

        .match-row {
            cursor: pointer;
        }

        .match-teams {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .team-side {
            flex: 1;
            text-align: left;
        }

        .team-side.home {
            text-align: right;
        }

        .team-name-sm {
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .team-group-sm {
            font-size: 12px;
            color: var(--secondary);
        }

        .match-score {
            padding: 0 16px;
            min-width: 80px;
            text-align: center;
        }

        .score {
            font-weight: 600;
            font-size: 14px;
            color: var(--dark);
        }

        .match-status {
            font-size: 12px;
            color: var(--secondary);
            margin-top: 2px;
        }

        .match-info {
            font-size: 12px;
            color: var(--secondary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Settings grid */
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .setting-item {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px;
        }

        .setting-label {
            font-size: 12px;
            color: var(--secondary);
            margin-bottom: 4px;
            
        }

        .setting-value {
            font-weight: 600;
            color: var(--dark);
            font-size: 14px;
        }

        /* Danger zone */
        .danger-zone {
            background: #FDF2F3;
            border: 1px solid rgba(192, 28, 40, 0.2);
            border-radius: var(--radius);
            padding: 24px;
            margin-top: 24px;
        }

        .danger-header {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #c01c28;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .danger-text {
            color: rgba(192, 28, 40, 0.8);
            font-size: 14px;
            margin-bottom: 16px;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 48px 16px;
        }

        .empty-icon {
            font-size: 32px;
            color: var(--border);
            margin-bottom: 12px;
        }

        .empty-title {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 4px;
        }

        .empty-text {
            color: var(--secondary);
            font-size: 14px;
            max-width: 300px;
            margin: 0 auto 16px;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .tournament-meta {
                justify-content: flex-start;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .teams-list {
                grid-template-columns: repeat(2, 1fr);
            }

            .match-teams {
                flex-direction: column;
                gap: 4px;
            }

            .team-side {
                text-align: center !important;
                width: 100%;
            }

            .match-score {
                order: -1;
                padding: 8px 0;
            }

            .settings-grid {
                grid-template-columns: 1fr;
            }

            .banner-stats {
                grid-template-columns: repeat(2, 1fr);
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
    <!-- Page header -->
    <div class="page-header">
        <div>
            <h1>Detail Turnamen</h1>
            <p class="page-subtitle">Kelola dan lihat turnamen {{ $tournament->name }}</p>
        </div>
        <div class="d-flex gap-2">
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
                        {{ $tournament->teams_count }} tim
                    </span>
                </div>
                @if($tournament->description)
                    <p class="mt-2 mb-0" style="font-size: 14px; color: var(--text-secondary);">{{ $tournament->description }}</p>
                @endif
            </div>
            <div class="text-end">
                <span class="badge-status badge-status-{{ $tournament->status }}">
                    {{ ucfirst($tournament->status) }}
                </span>
                <div class="mt-2">
                    <small style="color: var(--text-secondary);">{{ ucfirst(str_replace('_', ' ', $tournament->type)) }}</small>
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
                                <td>{{ $tournament->organizer ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Lokasi</td>
                                <td>{{ $tournament->location ?? 'N/A' }}</td>
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
                                <div class="setting-label">Durasi Pertandingan</div>
                                <div class="setting-value">{{ $tournament->match_duration }} menit</div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-label">Half Time</div>
                                <div class="setting-value">{{ $tournament->half_time }} menit</div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-label">Poin Menang</div>
                                <div class="setting-value">{{ $tournament->points_win }}</div>
                            </div>
                            <div class="setting-item">
                                <div class="setting-label">Poin Seri</div>
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
                                <span class="group-badge me-2">Group {{ $groupName }}</span>
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
                                            Seed #{{ $team->pivot->seed ?? '-' }}
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
                            <button type="button" class="btn btn-outline-secondary" data-filter="completed">Completed</button>
                            <button type="button" class="btn btn-outline-secondary" data-filter="upcoming">Upcoming</button>
                            <button type="button" class="btn btn-outline-secondary" data-filter="ongoing">Ongoing</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="compact-table">
                            <thead>
                                <tr>
                                    <th>Pertandingan</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Venue</th>
                                    <th>Ronde</th>
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

            <!-- Tab pengaturan -->
            <div id="settings-tab" class="tab-pane" style="display: none;">
                <div class="settings-grid">
                    <div class="setting-item">
                        <div class="setting-label">Durasi Pertandingan</div>
                        <div class="setting-value">{{ $tournament->match_duration }} menit</div>
                    </div>
                    <div class="setting-item">
                        <div class="setting-label">Half Time</div>
                        <div class="setting-value">{{ $tournament->half_time }} menit</div>
                    </div>
                    <div class="setting-item">
                        <div class="setting-label">Extra Time</div>
                        <div class="setting-value">{{ $settings['extra_time'] ?? 10 }} menit</div>
                    </div>
                    <div class="setting-item">
                        <div class="setting-label">Maksimal Pemain Cadangan</div>
                        <div class="setting-value">{{ $settings['max_substitutes'] ?? 5 }}</div>
                    </div>
                    <div class="setting-item">
                        <div class="setting-label">Pertandingan per Hari</div>
                        <div class="setting-value">{{ $settings['matches_per_day'] ?? 4 }}</div>
                    </div>
                    <div class="setting-item">
                        <div class="setting-label">Interval Pertandingan</div>
                        <div class="setting-value">{{ $settings['match_interval'] ?? 30 }} menit</div>
                    </div>
                    <div class="setting-item">
                        <div class="setting-label">Batas Kartu Kuning</div>
                        <div class="setting-value">{{ $settings['yellow_card_suspension'] ?? 3 }}</div>
                    </div>
                    <div class="setting-item">
                        <div class="setting-label">VAR Aktif</div>
                        <div class="setting-value">
                            @if($settings['var_enabled'] ?? false)
                                <span class="badge bg-success">Ya</span>
                            @else
                                <span class="badge bg-secondary">Tidak</span>
                            @endif
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
