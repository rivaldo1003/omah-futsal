@extends('layouts.admin')

@section('title', $team->name . ' - Detail Tim')

@section('styles')
<style>
/* Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
}

.page-header h1 {
    font-size: 20px;
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
    transition: all 0.15s ease;
}

.btn-back:hover {
    border-color: var(--accent);
    color: var(--accent);
}

/* Banner tim */
.team-header {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 16px;
}

.team-logo-large {
    width: 64px;
    height: 64px;
    border-radius: 8px;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 24px;
    overflow: hidden;
    flex-shrink: 0;
}

.team-logo-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.team-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 4px;
    line-height: 1.2;
}

.team-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-top: 8px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: var(--text-secondary);
}

.status-badge {
    font-size: 12px;
    padding: 2px 12px;
    border-radius: 999px;
    font-weight: 500;
    display: inline-block;
}

.status-active {
    background: var(--success-light, #f0f9f4);
    color: var(--success, #1e7a46);
}

.status-pending {
    background: var(--warning-light, #fdf6ec);
    color: var(--warning, #b45309);
}

.status-inactive {
    background: var(--danger-light, #fdf2f3);
    color: var(--accent, #c01c28);
}

/* Stats */
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

/* Cards */
.main-card {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 24px;
}

.main-card .card-header {
    background: var(--bg);
    border-bottom: 1px solid var(--border);
    padding: 16px 24px;
}

.main-card .card-header h5 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.main-card .card-body {
    padding: 24px;
}

/* Info table */
.info-table {
    width: 100%;
    font-size: 14px;
    margin-bottom: 0;
}

.info-table td {
    padding: 8px 0;
    border: 0;
    color: var(--text-primary);
}

.info-table td:first-child {
    width: 140px;
    color: var(--text-secondary);
}

/* Color display */
.color-display {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 2px 8px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 12px;
    margin-right: 8px;
}

.color-box {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    border: 1px solid var(--border);
}

/* Contact badges */
.contact-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 13px;
    color: var(--text-primary);
    margin: 0 8px 8px 0;
    text-decoration: none;
}

/* Staff grid */
.staff-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
}

.staff-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 12px;
}

.staff-role {
    font-size: 12px;
    color: var(--text-secondary);
    margin-bottom: 4px;
}

.staff-name {
    font-size: 14px;
    font-weight: 500;
    color: var(--text-primary);
}

.staff-contact {
    font-size: 12px;
    color: var(--text-secondary);
    margin-top: 4px;
}

/* Players table */
.table {
    margin: 0;
    font-size: 14px;
}

.table thead th {
    background: var(--surface);
    color: var(--text-secondary);
    font-weight: 500;
    font-size: 13px;
    padding: 10px 16px;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}

.table tbody td {
    padding: 12px 16px;
    vertical-align: middle;
    border-bottom: 1px solid var(--border);
}

.table tbody tr:last-child td {
    border-bottom: none;
}

.table tbody tr:hover {
    background: var(--surface);
}

.player-photo {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--surface);
    border: 1px solid var(--border);
    margin-right: 12px;
    flex-shrink: 0;
}

.player-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.player-photo-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 13px;
}

.player-name {
    font-weight: 500;
    color: var(--text-primary);
    font-size: 14px;
}

.player-details {
    font-size: 12px;
    color: var(--text-secondary);
}

/* Badge posisi */
.position-badge {
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 6px;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    display: inline-block;
}

/* Action buttons */
.btn-action {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: var(--bg);
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 13px;
    transition: all 0.15s ease;
    padding: 0;
}

.btn-action:hover {
    border-color: var(--accent);
    color: var(--accent);
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
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 4px;
}

.empty-state-text {
    color: var(--text-secondary);
    font-size: 14px;
    max-width: 300px;
    margin: 0 auto 16px;
}

/* Alert */
.alert {
    border-radius: 6px;
    padding: 12px 16px;
    font-size: 14px;
    margin-bottom: 16px;
    border: none;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .banner-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .team-header {
        flex-direction: column;
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
<!-- Header -->
<div class="page-header">
    <div>
        <h1>Detail tim</h1>
        <p class="page-subtitle">Informasi lengkap {{ $team->name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.teams.edit', $team) }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>
        <a href="{{ route('admin.teams.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
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

<!-- Banner tim -->
<div class="team-header">
    <div class="d-flex align-items-center" style="gap: 16px;">
        <div class="team-logo-large">
            @if($team->logo && (Storage::disk('public')->exists($team->logo) || filter_var($team->logo, FILTER_VALIDATE_URL)))
                <img src="{{ filter_var($team->logo, FILTER_VALIDATE_URL) ? $team->logo : asset('storage/' . $team->logo) }}"
                    alt="{{ $team->name }}">
            @else
                {{ strtoupper(substr($team->name, 0, 1)) }}
            @endif
        </div>
        <div>
            <h2 class="team-title">{{ $team->name }}</h2>
            <div class="team-meta">
                @if($team->status == 'active')
                    <span class="status-badge status-active">Aktif</span>
                @elseif($team->status == 'pending')
                    <span class="status-badge status-pending">Pending</span>
                @else
                    <span class="status-badge status-inactive">Nonaktif</span>
                @endif

                @if($team->founded_year)
                    <span class="meta-item">
                        <i class="bi bi-calendar"></i> Berdiri {{ $team->founded_year }}
                    </span>
                @endif

                @if($team->home_venue)
                    <span class="meta-item">
                        <i class="bi bi-geo-alt"></i> {{ $team->home_venue }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="banner-stats">
        <div>
            <div class="banner-stat-value">{{ $team->players->count() }}</div>
            <div class="banner-stat-label">Pemain</div>
        </div>
        <div>
            <div class="banner-stat-value">{{ $stats['total_matches'] ?? 0 }}</div>
            <div class="banner-stat-label">Pertandingan</div>
        </div>
        <div>
            <div class="banner-stat-value">{{ $stats['points'] ?? 0 }}</div>
            <div class="banner-stat-label">Poin</div>
        </div>
        <div>
            <div class="banner-stat-value">{{ $stats['goal_difference'] > 0 ? '+' : '' }}{{ $stats['goal_difference'] ?? 0 }}</div>
            <div class="banner-stat-label">Selisih gol</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Kolom kiri -->
    <div class="col-lg-8">
        <!-- Informasi tim -->
        <div class="main-card">
            <div class="card-header">
                <h5>Informasi tim</h5>
            </div>
            <div class="card-body">
                <table class="info-table">
                    @if($team->short_name)
                        <tr>
                            <td>Kode singkat</td>
                            <td>{{ $team->short_name }}</td>
                        </tr>
                    @endif
                    @if($team->description)
                        <tr>
                            <td>Deskripsi</td>
                            <td>{{ $team->description }}</td>
                        </tr>
                    @endif
                    @if($team->primary_color || $team->secondary_color)
                        <tr>
                            <td>Warna tim</td>
                            <td>
                                @if($team->primary_color)
                                    <span class="color-display">
                                        <span class="color-box" style="background-color: {{ $team->primary_color }}"></span>
                                        {{ $team->primary_color }}
                                    </span>
                                @endif
                                @if($team->secondary_color)
                                    <span class="color-display">
                                        <span class="color-box" style="background-color: {{ $team->secondary_color }}"></span>
                                        {{ $team->secondary_color }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td>Status</td>
                        <td>
                            @if($team->status == 'active')
                                <span class="status-badge status-active">Aktif</span>
                            @elseif($team->status == 'pending')
                                <span class="status-badge status-pending">Pending</span>
                            @else
                                <span class="status-badge status-inactive">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Terdaftar</td>
                        <td>{{ $team->created_at->format('d M Y') }}</td>
                    </tr>
                </table>

                @if($team->email || $team->phone || $team->website || $team->address)
                    <div class="mt-4 pt-3 border-top">
                        <div class="section-label mb-2" style="font-size: 13px; font-weight: 600; color: var(--text-primary);">
                            Kontak
                        </div>
                        @if($team->email)
                            <a href="mailto:{{ $team->email }}" class="contact-badge">
                                <i class="bi bi-envelope"></i> {{ $team->email }}
                            </a>
                        @endif
                        @if($team->phone)
                            <span class="contact-badge">
                                <i class="bi bi-telephone"></i> {{ $team->phone }}
                            </span>
                        @endif
                        @if($team->website)
                            <a href="{{ $team->website }}" target="_blank" class="contact-badge">
                                <i class="bi bi-globe"></i> {{ parse_url($team->website, PHP_URL_HOST) ?: Str::limit($team->website, 25) }}
                            </a>
                        @endif
                        @if($team->address)
                            <span class="contact-badge">
                                <i class="bi bi-geo-alt"></i> {{ Str::limit($team->address, 30) }}
                            </span>
                        @endif
                    </div>
                @endif

                @if($team->coach_name || $team->head_coach || $team->assistant_coach || $team->goalkeeper_coach || $team->kitman)
                    <div class="mt-4 pt-3 border-top">
                        <div class="section-label mb-2" style="font-size: 13px; font-weight: 600; color: var(--text-primary);">
                            Staff pelatih
                        </div>
                        <div class="staff-grid">
                            @if($team->head_coach)
                                <div class="staff-card">
                                    <div class="staff-role">Head coach</div>
                                    <div class="staff-name">{{ $team->head_coach }}</div>
                                </div>
                            @endif

                            @if($team->assistant_coach)
                                <div class="staff-card">
                                    <div class="staff-role">Assistant coach</div>
                                    <div class="staff-name">{{ $team->assistant_coach }}</div>
                                </div>
                            @endif

                            @if($team->goalkeeper_coach)
                                <div class="staff-card">
                                    <div class="staff-role">Goalkeeper coach</div>
                                    <div class="staff-name">{{ $team->goalkeeper_coach }}</div>
                                </div>
                            @endif

                            @if($team->kitman)
                                <div class="staff-card">
                                    <div class="staff-role">Kitman</div>
                                    <div class="staff-name">{{ $team->kitman }}</div>
                                </div>
                            @endif

                            @if($team->coach_name && ($team->coach_email || $team->coach_phone))
                                <div class="staff-card">
                                    <div class="staff-role">Kontak coach</div>
                                    <div class="staff-contact">
                                        @if($team->coach_email)
                                            <div><i class="bi bi-envelope me-1"></i>{{ $team->coach_email }}</div>
                                        @endif
                                        @if($team->coach_phone)
                                            <div><i class="bi bi-telephone me-1"></i>{{ $team->coach_phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pemain -->
        <div class="main-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Pemain ({{ $team->players->count() }})</h5>
                <a href="{{ route('admin.players.create', ['team_id' => $team->id]) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah pemain
                </a>
            </div>
            <div class="card-body p-0">
                @if($team->players->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Pemain</th>
                                    <th>Posisi</th>
                                    <th>Nomor</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($team->players as $player)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="player-photo">
                                                    @php
                                                        $playerPhoto = $player->photo ?? null;
                                                        $photoPath = null;
                                                        if ($playerPhoto) {
                                                            if (filter_var($playerPhoto, FILTER_VALIDATE_URL)) {
                                                                $photoPath = $playerPhoto;
                                                            } elseif (Storage::disk('public')->exists($playerPhoto)) {
                                                                $photoPath = asset('storage/' . $playerPhoto);
                                                            }
                                                        }
                                                        $nameParts = explode(' ', $player->name);
                                                        $initials = count($nameParts) >= 2
                                                            ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                                                            : strtoupper(substr($player->name, 0, 2));
                                                    @endphp

                                                    @if($photoPath)
                                                        <img src="{{ $photoPath }}" alt="{{ $player->name }}"
                                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div class="player-photo-fallback" style="display: none;">
                                                            {{ $initials }}
                                                        </div>
                                                    @else
                                                        <div class="player-photo-fallback">
                                                            {{ $initials }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="player-name">{{ $player->name }}</div>
                                                    @if($player->date_of_birth)
                                                        <div class="player-details">
                                                            {{ \Carbon\Carbon::parse($player->date_of_birth)->format('d M Y') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($player->position)
                                                <span class="position-badge">{{ $player->position }}</span>
                                            @else
                                                <span class="text-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($player->jersey_number)
                                                <span class="position-badge">#{{ $player->jersey_number }}</span>
                                            @else
                                                <span class="text-secondary">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                <a href="{{ route('admin.players.show', $player) }}"
                                                    class="btn-action" title="Lihat detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.players.edit', $player) }}"
                                                    class="btn-action" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h4 class="empty-state-title">Belum ada pemain</h4>
                        <p class="empty-state-text">
                            Tambahkan pemain ke tim ini untuk mulai mengelola susunan pemain.
                        </p>
                        <a href="{{ route('admin.players.create', ['team_id' => $team->id]) }}"
                            class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> Tambah pemain pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Turnamen -->
        @if($team->tournaments->count() > 0)
            <div class="main-card">
                <div class="card-header">
                    <h5>Turnamen ({{ $team->tournaments->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($team->tournaments as $tournament)
                            <div class="col-md-6">
                                <div class="border rounded p-3" style="background: var(--surface);">
                                    <h6 class="mb-1" style="font-size: 14px; font-weight: 600;">
                                        {{ $tournament->name }}
                                    </h6>
                                    @if($tournament->start_date)
                                        <small class="text-secondary">
                                            <i class="bi bi-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($tournament->start_date)->format('d M Y') }}
                                            @if($tournament->end_date)
                                                – {{ \Carbon\Carbon::parse($tournament->end_date)->format('d M Y') }}
                                            @endif
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Statistik -->
        <div class="main-card">
            <div class="card-header">
                <h5>Statistik</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="banner-stat-label mb-1">Win rate</div>
                    @php
                        $totalMatches = $stats['total_matches'] ?? 0;
                        $wins = $stats['wins'] ?? 0;
                        $winRate = $totalMatches > 0 ? round(($wins / $totalMatches) * 100, 1) : 0;
                    @endphp
                    <div class="banner-stat-value">{{ $winRate }}%</div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar" style="width: {{ $winRate }}%; background-color: var(--success, #1e7a46);"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="banner-stat-label mb-1">Rekor</div>
                    <div class="banner-stat-value">
                        {{ $stats['wins'] ?? 0 }}M {{ $stats['draws'] ?? 0 }}S {{ $stats['losses'] ?? 0 }}K
                    </div>
                </div>

                <div>
                    <div class="banner-stat-label mb-1">Gol (cetak / kemasukan)</div>
                    <div class="banner-stat-value">
                        {{ $stats['goals_for'] ?? 0 }} / {{ $stats['goals_against'] ?? 0 }}
                    </div>
                </div>

                @if($topScorers->count() > 0)
                    <div class="mt-4 pt-3 border-top">
                        <div class="section-label mb-2" style="font-size: 13px; font-weight: 600; color: var(--text-primary);">
                            Top skor
                        </div>
                        @foreach($topScorers as $scorer)
                            <div class="d-flex justify-content-between mb-1" style="font-size: 13px;">
                                <span>{{ $scorer->name }}</span>
                                <span class="text-secondary">{{ $scorer->goals ?? 0 }} gol</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Pertandingan terbaru -->
        @if(isset($recentMatches) && $recentMatches->count() > 0)
            <div class="main-card">
                <div class="card-header">
                    <h5>Pertandingan terbaru</h5>
                </div>
                <div class="card-body p-0">
                    @foreach($recentMatches as $match)
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <div style="font-size: 14px; font-weight: 500;">
                                    {{ $match->homeTeam->name ?? 'Unknown' }} vs {{ $match->awayTeam->name ?? 'Unknown' }}
                                </div>
                                <small class="text-secondary">
                                    {{ \Carbon\Carbon::parse($match->match_date)->format('d M Y') }}
                                    @if($match->venue)
                                        • {{ $match->venue }}
                                    @endif
                                </small>
                            </div>
                            <div class="text-end">
                                @if($match->status == 'completed')
                                    <span class="position-badge">{{ $match->home_score }} - {{ $match->away_score }}</span>
                                @elseif($match->status == 'scheduled' || $match->status == 'upcoming')
                                    <span class="position-badge">Terjadwal</span>
                                @elseif($match->status == 'ongoing')
                                    <span class="position-badge">Berlangsung</span>
                                @elseif($match->status == 'postponed')
                                    <span class="position-badge">Ditunda</span>
                                @else
                                    <span class="position-badge">Dibatalkan</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
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