@extends('layouts.admin')

@section('title', $player->name . ' - Detail Pemain')

@section('styles')
<style>
    @include('admin.players.partials.admin-styles')

    .admin-player-page .player-detail-header {
        margin-bottom: 24px;
    }

    .admin-player-page .player-detail-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        white-space: nowrap;
    }

    .admin-player-page .player-header {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 20px;
        margin-bottom: 24px;
        padding: 24px;
        border: 1px solid var(--player-line-soft);
        border-radius: 8px;
        background: var(--player-white);
    }

    .admin-player-page .player-profile {
        display: flex;
        align-items: center;
        gap: 18px;
        min-width: 0;
    }

    .admin-player-page .player-photo-container {
        width: 80px;
        height: 80px;
        flex: 0 0 80px;
        overflow: hidden;
        border: 1px solid var(--player-line-soft);
        border-radius: 50%;
        background: var(--player-cloud);
    }

    .admin-player-page .player-photo-large {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .admin-player-page .player-profile-copy {
        min-width: 0;
    }

    .admin-player-page .player-title {
        margin: 0;
        color: var(--player-ink);
        font-size: clamp(1.5rem, 2.2vw, 1.875rem);
        font-weight: 500;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }

    .admin-player-page .player-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-top: 12px;
    }

    .admin-player-page .jersey-badge,
    .admin-player-page .position-badge {
        display: inline-flex;
        align-items: center;
        min-height: 30px;
        padding: 4px 11px;
        border: 1px solid var(--player-line-soft);
        border-radius: 9999px;
        background: var(--player-cloud);
        color: var(--player-charcoal);
        font-size: 12px;
        font-weight: 400;
        line-height: 1.3;
    }

    .admin-player-page .jersey-badge {
        color: var(--player-ink);
        font-weight: 500;
    }

    .admin-player-page .team-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 34px;
        max-width: 100%;
        padding: 5px 10px;
        border: 1px solid var(--player-line-soft);
        border-radius: 9999px;
        background: var(--player-white);
        color: var(--player-charcoal);
        font-size: 13px;
        line-height: 1.3;
        text-decoration: none;
    }

    .admin-player-page .team-badge:hover {
        background: var(--player-cloud);
        color: var(--player-ink);
    }

    .admin-player-page .team-logo-small,
    .admin-player-page .team-initial-small {
        width: 22px;
        height: 22px;
        flex: 0 0 22px;
        border: 1px solid var(--player-line-soft);
        border-radius: 50%;
        object-fit: cover;
    }

    .admin-player-page .team-initial-small {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--player-cloud);
        color: var(--player-charcoal);
        font-size: 11px;
        font-weight: 500;
    }

    .admin-player-page .banner-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
        margin: 0;
        padding-top: 20px;
        border-top: 1px solid var(--player-line-soft);
    }

    .admin-player-page .banner-stat {
        min-width: 0;
        padding: 12px 14px;
        border-radius: 8px;
        background: var(--player-cloud);
    }

    .admin-player-page .banner-stat-value {
        color: var(--player-ink);
        font-size: 20px;
        font-weight: 500;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .admin-player-page .banner-stat-label {
        margin-top: 4px;
        color: var(--player-muted);
        font-size: 12px;
        line-height: 1.4;
    }

    .admin-player-page .player-detail-columns {
        --bs-gutter-x: 20px;
        --bs-gutter-y: 20px;
    }

    .admin-player-page .info-table {
        width: 100%;
        margin: 0;
        font-size: 14px;
    }

    .admin-player-page .info-table td {
        padding: 12px 8px;
        border-bottom: 1px solid var(--player-line-soft);
        color: var(--player-charcoal);
        vertical-align: top;
    }

    .admin-player-page .info-table tr:last-child td {
        border-bottom: 0;
    }

    .admin-player-page .info-table td:first-child {
        width: 180px;
        padding-left: 0;
        color: var(--player-muted);
    }

    .admin-player-page .info-table td:last-child {
        padding-right: 0;
        overflow-wrap: anywhere;
    }

    .admin-player-page .event-table {
        min-width: 760px;
        margin: 0;
        color: var(--player-charcoal);
        font-size: 13px;
        --bs-table-bg: transparent;
        --bs-table-hover-bg: var(--player-cloud);
    }

    .admin-player-page .event-table > :not(caption) > * > * {
        padding: 12px 16px;
        border-bottom-color: var(--player-line-soft);
        vertical-align: middle;
    }

    .admin-player-page .event-table thead th {
        background: var(--player-white);
        color: var(--player-muted);
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
    }

    .admin-player-page .event-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .admin-player-page .event-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        min-height: 26px;
        padding: 4px 9px;
        border: 1px solid var(--player-line-soft);
        border-radius: 9999px;
        background: var(--player-cloud);
        color: var(--player-charcoal);
        font-size: 11px;
        font-weight: 500;
        line-height: 1.3;
        white-space: nowrap;
    }

    .admin-player-page .event-goal {
        border-color: #d4e9dd;
        background: #edf6f1;
        color: var(--player-success);
    }

    .admin-player-page .event-yellow {
        border-color: #eadfbd;
        background: #faf5e8;
        color: #785b13;
    }

    .admin-player-page .event-red {
        border-color: #e8c8c5;
        background: #fdf1f0;
        color: var(--player-danger);
    }

    .admin-player-page .btn-action-block {
        width: 100%;
        min-height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: flex-start;
        gap: 10px;
        padding: 11px 14px;
        border: 1px solid var(--player-line-soft);
        border-radius: 9999px;
        background: var(--player-white);
        color: var(--player-ink);
        font-size: 14px;
        font-weight: 500;
        text-align: left;
        transition: background-color 150ms ease-out, border-color 150ms ease-out;
    }

    .admin-player-page .btn-action-block:hover {
        border-color: var(--player-line);
        background: var(--player-cloud);
        color: var(--player-ink);
    }

    .admin-player-page .btn-action-block.btn-danger-hover:hover {
        border-color: #e8c8c5;
        background: #fdf1f0;
        color: var(--player-danger);
    }

    .admin-player-page .activity-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .admin-player-page .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .admin-player-page .activity-icon {
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--player-line-soft);
        border-radius: 50%;
        background: var(--player-cloud);
        color: var(--player-charcoal);
        font-size: 14px;
    }

    .admin-player-page .activity-copy {
        min-width: 0;
    }

    .admin-player-page .activity-title {
        color: var(--player-ink);
        font-size: 13px;
        font-weight: 500;
    }

    .admin-player-page .activity-text {
        color: var(--player-muted);
        font-size: 12px;
        overflow-wrap: anywhere;
    }

    .admin-player-page .modal-content {
        overflow: hidden;
        border: 1px solid var(--player-line-soft);
        border-radius: 8px;
        background: var(--player-white);
        box-shadow: none;
    }

    .admin-player-page .modal-header,
    .admin-player-page .modal-footer {
        padding: 16px 20px;
        border-color: var(--player-line-soft);
    }

    .admin-player-page .modal-title {
        color: var(--player-ink);
        font-size: 16px;
        font-weight: 500;
    }

    .admin-player-page .modal-body {
        padding: 20px;
        color: var(--player-charcoal);
    }

    .admin-player-page .modal .btn-primary {
        border-color: var(--player-ink);
        background: var(--player-ink);
        color: var(--player-white);
    }

    .admin-player-page .modal .btn-primary:hover {
        border-color: var(--player-charcoal);
        background: var(--player-charcoal);
    }

    .admin-player-page .modal .btn-outline-secondary {
        border-color: var(--player-line);
        background: var(--player-white);
        color: var(--player-charcoal);
    }

    @media (max-width: 991.98px) {
        .admin-player-page .player-detail-actions {
            justify-content: flex-start;
        }

        .admin-player-page .player-detail-columns > [class*="col-lg-"] {
            width: 100%;
        }
    }

    @media (max-width: 767.98px) {
        .admin-player-page .player-detail-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            width: 100%;
        }

        .admin-player-page .player-detail-actions > a {
            width: 100%;
        }

        .admin-player-page .player-header {
            padding: 18px;
        }

        .admin-player-page .player-profile {
            align-items: flex-start;
            gap: 14px;
        }

        .admin-player-page .player-photo-container {
            width: 64px;
            height: 64px;
            flex-basis: 64px;
        }

        .admin-player-page .banner-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .admin-player-page .banner-stat {
            padding: 10px 12px;
        }

        .admin-player-page .info-table td {
            padding: 10px 6px;
        }

        .admin-player-page .info-table td:first-child {
            width: 120px;
        }
    }

    @media (max-width: 420px) {
        .admin-player-page .player-detail-actions {
            grid-template-columns: minmax(0, 1fr);
        }

        .admin-player-page .player-meta {
            gap: 6px;
        }
    }
</style>
@endsection

@section('content')
<div class="admin-player-page">
<div class="page-header player-detail-header">
    <div class="page-heading">
        <h1><i class="bi bi-person-badge"></i> Detail pemain</h1>
        <p class="page-subtitle">Statistik dan informasi {{ $player->name }}</p>
    </div>
    <div class="player-detail-actions">
        <a href="{{ route('admin.players.edit', $player) }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>
        <a href="{{ route('admin.players.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="player-header">
    <div class="player-profile">
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
        <div class="player-profile-copy">
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
            <div class="banner-stat">
                <div class="banner-stat-value">{{ $player->formatted_market_value }}</div>
                <div class="banner-stat-label">Market value</div>
            </div>
        @endif
        <div class="banner-stat">
            <div class="banner-stat-value">{{ $player->goals ?? 0 }}</div>
            <div class="banner-stat-label">Gol</div>
        </div>
        <div class="banner-stat">
            <div class="banner-stat-value">{{ $player->assists ?? 0 }}</div>
            <div class="banner-stat-label">Assist</div>
        </div>
        <div class="banner-stat">
            <div class="banner-stat-value">{{ $player->penalty_goals ?? 0 }}</div>
            <div class="banner-stat-label">Gol penalti</div>
        </div>
        <div class="banner-stat">
            <div class="banner-stat-value">{{ $player->yellow_cards ?? 0 }} / {{ $player->red_cards ?? 0 }}</div>
            <div class="banner-stat-label">Kartu kuning / merah</div>
        </div>
    </div>
</div>

<div class="row g-4 player-detail-columns">
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
                        <table class="table table-hover mb-0 event-table">
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
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <div class="activity-copy">
                            <div class="activity-title">Pemain ditambahkan</div>
                            <div class="activity-text">{{ $player->created_at->timezone(\App\Support\BusinessTime::TIMEZONE)->format('d M Y') }}</div>
                        </div>
                    </div>

                    @if($player->team)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="activity-copy">
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
                            <div class="activity-copy">
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
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
