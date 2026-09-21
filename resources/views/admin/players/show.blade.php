@extends('layouts.admin')

@section('title', $player->name . ' - Detail Pemain')

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

/* Banner pemain */
.player-header {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.player-photo-container {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    background: var(--surface);
    border: 1px solid var(--border);
    flex-shrink: 0;
}

.player-photo-large {
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
    color: var(--text-secondary);
    font-size: 28px;
    font-weight: 600;
}

.player-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 4px;
    line-height: 1.2;
}

.player-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-top: 8px;
    align-items: center;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: var(--text-secondary);
}

/* Badges */
.position-badge {
    font-size: 12px;
    padding: 2px 12px;
    border-radius: 999px;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    font-weight: 500;
    display: inline-block;
}

.jersey-badge {
    font-size: 12px;
    padding: 2px 12px;
    border-radius: 999px;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-primary);
    font-weight: 600;
    display: inline-block;
}

/* Team badge */
.team-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 14px;
    color: var(--text-primary);
    text-decoration: none;
}

.team-logo-small {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    object-fit: cover;
    border: 1px solid var(--border);
}

.team-initial-small {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 11px;
}

/* Stats banner */
.banner-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
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

/* Event table */
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

.event-badge {
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-weight: 500;
}

.event-goal {
    background: var(--success-light, #f0f9f4);
    color: var(--success, #1e7a46);
}

.event-yellow {
    background: var(--warning-light, #fdf6ec);
    color: var(--warning, #b45309);
}

.event-red {
    background: var(--danger-light, #fdf2f3);
    color: var(--accent, #c01c28);
}

.event-other {
    background: var(--surface);
    color: var(--text-secondary);
    border: 1px solid var(--border);
}

/* Action buttons */
.btn-action-block {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: var(--bg);
    color: var(--text-primary);
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s ease;
}

.btn-action-block:hover {
    border-color: var(--accent);
    color: var(--accent);
}

.btn-action-block.btn-danger-hover:hover {
    border-color: var(--accent);
    color: var(--accent);
}

/* Activity */
.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 14px;
}

.activity-title {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-primary);
}

.activity-text {
    font-size: 12px;
    color: var(--text-secondary);
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

    .player-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .banner-stats {
        grid-template-columns: repeat(2, 1fr);
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
        <h1>Detail pemain</h1>
        <p class="page-subtitle">Statistik dan informasi {{ $player->name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.players.edit', $player) }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>
        <a href="{{ route('admin.players.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<!-- Banner pemain -->
<div class="player-header">
    <div class="d-flex align-items-center" style="gap: 16px;">
        <div class="player-photo-container">
            @php
                $playerPhoto = $player->photo ?? null;
                $nameParts = explode(' ', $player->name);
                $initials = count($nameParts) >= 2
                    ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                    : strtoupper(substr($player->name, 0, 2));

                $photoPath = null;
                if ($playerPhoto) {
                    if (filter_var($playerPhoto, FILTER_VALIDATE_URL)) {
                        $photoPath = $playerPhoto;
                    } elseif (Storage::disk('public')->exists($playerPhoto)) {
                        $photoPath = asset('storage/' . $playerPhoto);
                    }
                }
            @endphp

            @if($photoPath)
                <img src="{{ $photoPath }}" alt="{{ $player->name }}" class="player-photo-large"
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
            <h2 class="player-title">{{ $player->name }}</h2>
            <div class="player-meta">
                @if($player->jersey_number)
                    <span class="jersey-badge">#{{ $player->jersey_number }}</span>
                @endif

                @if($player->position)
                    <span class="position-badge">{{ $player->position }}</span>
                @endif

                @if($player->team)
                    <a href="{{ route('admin.teams.show', $player->team) }}" class="team-badge">
                        @if($player->team->logo && (Storage::disk('public')->exists($player->team->logo) || filter_var($player->team->logo, FILTER_VALIDATE_URL)))
                            <img src="{{ filter_var($player->team->logo, FILTER_VALIDATE_URL) ? $player->team->logo : asset('storage/' . $player->team->logo) }}"
                                alt="{{ $player->team->name }}" class="team-logo-small"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        @endif
                        <span class="team-initial-small"
                            style="@if($player->team->logo && (Storage::disk('public')->exists($player->team->logo) || filter_var($player->team->logo, FILTER_VALIDATE_URL))) display: none; @endif">
                            {{ strtoupper(substr($player->team->name, 0, 1)) }}
                        </span>
                        {{ $player->team->name }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="banner-stats">
        @if($player->formatted_market_value)
            <div>
                <div class="banner-stat-value text-accent">{{ $player->formatted_market_value }}</div>
                <div class="banner-stat-label">Market value</div>
            </div>
        @endif
        <div>
            <div class="banner-stat-value">{{ $player->goals ?? 0 }}</div>
            <div class="banner-stat-label">Gol</div>
        </div>
        <div>
            <div class="banner-stat-value">{{ $player->assists ?? 0 }}</div>
            <div class="banner-stat-label">Assist</div>
        </div>
        <div>
            <div class="banner-stat-value">{{ $player->penalty_goals ?? 0 }}</div>
            <div class="banner-stat-label">Gol penalti</div>
        </div>
        <div>
            <div class="banner-stat-value">{{ $player->yellow_cards ?? 0 }} / {{ $player->red_cards ?? 0 }}</div>
            <div class="banner-stat-label">Kartu kuning / merah</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Kolom kiri -->
    <div class="col-lg-8">
        <!-- Informasi pemain -->
        <div class="main-card">
            <div class="card-header">
                <h5>Informasi pemain</h5>
            </div>
            <div class="card-body">
                @php
                    $hasBirthDate = !empty($player->birth_date);
                    $hasBirthPlace = !empty($player->birth_place);
                    $hasBio = !empty($player->biography);
                    $hasMarketValue = !empty($player->market_value);
                @endphp

                @if($hasBirthDate || $hasBirthPlace || $hasBio || $player->nationality || $hasMarketValue)
                    <table class="info-table">
                        @if($hasMarketValue)
                            <tr>
                                <td>Market value</td>
                                <td>
                                    <strong>{{ $player->formatted_market_value }}</strong>
                                    <span class="text-secondary small ms-1">(Rp {{ number_format($player->market_value, 0, ',', '.') }})</span>
                                </td>
                            </tr>
                        @endif
                        @if($player->nationality)
                            <tr>
                                <td>Kewarganegaraan</td>
                                <td>{{ $player->nationality }}</td>
                            </tr>
                        @endif
                        @if($hasBirthDate)
                            <tr>
                                <td>Tanggal lahir</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($player->birth_date)->format('d M Y') }}
                                    <small class="text-secondary">({{ \Carbon\Carbon::parse($player->birth_date)->age }} tahun)</small>
                                </td>
                            </tr>
                        @endif
                        @if($hasBirthPlace)
                            <tr>
                                <td>Tempat lahir</td>
                                <td>{{ $player->birth_place }}</td>
                            </tr>
                        @endif
                        @if($hasBio)
                            <tr>
                                <td>Bio</td>
                                <td>{{ Str::limit($player->biography, 200) }}</td>
                            </tr>
                        @endif
                    </table>
                @else
                    <div class="text-secondary d-flex align-items-center gap-2 py-2" style="font-size: 14px;">
                        <i class="bi bi-info-circle"></i>
                        Belum ada informasi detail (tanggal lahir, tempat lahir, bio) untuk pemain ini.
                    </div>
                @endif
            </div>
        </div>

        <!-- Riwayat event per pertandingan -->
        @if(isset($matchEvents) && $matchEvents->count() > 0)
            <div class="main-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Riwayat pertandingan</h5>
                    <span class="position-badge">{{ $matchEvents->count() }} event</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pertandingan</th>
                                    <th>Turnamen</th>
                                    <th class="text-center">Menit</th>
                                    <th>Event</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($matchEvents as $event)
                                    @php
                                        $match = $event->match;
                                        $eventMeta = match ($event->event_type) {
                                            'goal' => ['label' => $event->is_penalty ? 'Gol (penalti)' : 'Gol', 'class' => 'event-goal', 'icon' => 'bullseye'],
                                            'yellow_card' => ['label' => 'Kartu kuning', 'class' => 'event-yellow', 'icon' => 'card-text'],
                                            'red_card' => ['label' => 'Kartu merah', 'class' => 'event-red', 'icon' => 'card-text'],
                                            default => ['label' => ucfirst($event->event_type), 'class' => 'event-other', 'icon' => 'circle'],
                                        };
                                    @endphp
                                    <tr>
                                        <td class="small">{{ $match?->match_date?->format('d M Y') ?? '-' }}</td>
                                        <td class="small">
                                            <strong>{{ $match?->homeTeam?->name ?? '?' }}</strong>
                                            <span class="text-secondary mx-1">{{ $match?->home_score ?? 0 }} - {{ $match?->away_score ?? 0 }}</span>
                                            <strong>{{ $match?->awayTeam?->name ?? '?' }}</strong>
                                        </td>
                                        <td class="small text-secondary">{{ $match?->tournament?->name ?? '-' }}</td>
                                        <td class="text-center small">{{ $event->minute }}'</td>
                                        <td>
                                            <span class="event-badge {{ $eventMeta['class'] }}">
                                                <i class="bi bi-{{ $eventMeta['icon'] }}"></i> {{ $eventMeta['label'] }}
                                            </span>
                                            @if($event->is_own_goal)
                                                <span class="event-badge event-other ms-1">Gol bunuh diri</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Kolom kanan -->
    <div class="col-lg-4">
        <!-- Aksi -->
        <div class="main-card">
            <div class="card-header">
                <h5>Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn-action-block" data-bs-toggle="modal"
                        data-bs-target="#updateStatsModal">
                        <i class="bi bi-plus-circle"></i> Perbarui statistik
                    </button>

                    <button type="button" class="btn-action-block btn-danger-hover" data-bs-toggle="modal"
                        data-bs-target="#deleteModal">
                        <i class="bi bi-trash"></i> Hapus pemain
                    </button>
                </div>
            </div>
        </div>

        <!-- Aktivitas -->
        <div class="main-card">
            <div class="card-header">
                <h5>Aktivitas</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <div>
                            <div class="activity-title">Pemain ditambahkan</div>
                            <div class="activity-text">{{ $player->created_at->format('d M Y') }}</div>
                        </div>
                    </div>

                    @if($player->team)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div>
                                <div class="activity-title">Bergabung dengan tim</div>
                                <div class="activity-text">{{ $player->team->name }}</div>
                            </div>
                        </div>
                    @endif

                    @if($player->goals > 0)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <div>
                                <div class="activity-title">Gol dicetak</div>
                                <div class="activity-text">{{ $player->goals }} total</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Stats Modal -->
<div class="modal fade" id="updateStatsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Perbarui statistik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.players.update', $player) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">Gol</label>
                            <input type="number" name="goals" class="form-control"
                                value="{{ $player->goals ?? 0 }}" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Assist</label>
                            <input type="number" name="assists" class="form-control"
                                value="{{ $player->assists ?? 0 }}" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Kartu kuning</label>
                            <input type="number" name="yellow_cards" class="form-control"
                                value="{{ $player->yellow_cards ?? 0 }}" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Kartu merah</label>
                            <input type="number" name="red_cards" class="form-control"
                                value="{{ $player->red_cards ?? 0 }}" min="0">
                        </div>
                        <input type="hidden" name="update_type" value="stats_only">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus pemain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Hapus <strong>{{ $player->name }}</strong>? Tindakan ini tidak bisa dibatalkan.</p>
                <div class="alert alert-warning small mb-0">
                    <i class="bi bi-info-circle me-1"></i> Semua data pemain akan dihapus permanen.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.players.destroy', $player) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle photo loading errors
    document.querySelectorAll('.player-photo-large').forEach(img => {
        img.addEventListener('error', function () {
            this.style.display = 'none';
            const fallback = this.nextElementSibling;
            if (fallback && fallback.classList.contains('player-photo-fallback')) {
                fallback.style.display = 'flex';
            }
        });
    });

    // Handle team logo loading errors
    document.querySelectorAll('.team-logo-small').forEach(img => {
        img.addEventListener('error', function () {
            this.style.display = 'none';
            const initial = this.nextElementSibling;
            if (initial && initial.classList.contains('team-initial-small')) {
                initial.style.display = 'flex';
            }
        });
    });
});
</script>
@endsection