@extends('layouts.admin')

@section('title', 'Data Pemain - OFS Futsal Center Admin')

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
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-create {
    background: var(--accent, #c01c28);
    color: white;
    border: none;
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

.btn-create:hover {
    background: var(--accent-hover, #a51822);
    color: white;
}

/* Search */
.search-box {
    position: relative;
    width: 260px;
}

.search-box .search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
    pointer-events: none;
    font-size: 14px;
}

.search-box input {
    padding-left: 34px;
    padding-right: 34px;
    border: 1px solid var(--border, #e5e5e7);
    border-radius: 6px;
    height: 40px;
    font-size: 14px;
}

.search-box input:focus {
    border-color: var(--accent, #c01c28);
    box-shadow: 0 0 0 3px rgba(192, 28, 40, 0.1);
    outline: none;
}

.clear-search-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    padding: 0;
    cursor: pointer;
    color: var(--text-secondary);
}

/* Main card */
.main-card {
    background: var(--bg-card, white);
    border: 1px solid var(--border, #e5e5e7);
    border-radius: 12px;
    overflow: hidden;
}

.main-card .card-header {
    background: var(--bg-card, white);
    border-bottom: 1px solid var(--border, #e5e5e7);
    padding: 16px 24px;
}

.main-card .card-header h5 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary, #111113);
    margin: 0;
}

/* Table */
.table {
    margin: 0;
    font-size: 14px;
}

.table thead th {
    background: var(--surface, #f7f7f8);
    color: var(--text-secondary, #6b6b70);
    font-weight: 500;
    font-size: 13px;
    padding: 10px 16px;
    border-bottom: 1px solid var(--border, #e5e5e7);
    white-space: nowrap;
}

.table tbody td {
    padding: 12px 16px;
    vertical-align: middle;
    border-bottom: 1px solid var(--border, #e5e5e7);
}

.table tbody tr:last-child td {
    border-bottom: none;
}

.table tbody tr:hover {
    background: var(--surface, #f7f7f8);
}

/* Player info */
.player-photo {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--surface, #f7f7f8);
    border: 1px solid var(--border, #e5e5e7);
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
    color: var(--text-secondary, #6b6b70);
    font-size: 13px;
}

.player-name {
    font-weight: 500;
    color: var(--text-primary, #111113);
    font-size: 14px;
}

.player-details {
    font-size: 12px;
    color: var(--text-secondary, #6b6b70);
}

/* Team badge */
.team-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 2px 8px;
    background: var(--surface, #f7f7f8);
    border: 1px solid var(--border, #e5e5e7);
    border-radius: 6px;
    font-size: 12px;
    color: var(--text-primary, #111113);
}

.team-initial {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    background: var(--surface, #f7f7f8);
    border: 1px solid var(--border, #e5e5e7);
    color: var(--text-secondary, #6b6b70);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 11px;
}

/* Position badge */
.position-badge {
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 6px;
    background: var(--surface, #f7f7f8);
    border: 1px solid var(--border, #e5e5e7);
    color: var(--text-secondary, #6b6b70);
    display: inline-block;
}

/* Stats */
.stats-grid {
    display: flex;
    gap: 16px;
}

.stat-item {
    text-align: center;
    min-width: 32px;
}

.stat-value {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary, #111113);
    line-height: 1.2;
}

.stat-label {
    font-size: 11px;
    color: var(--text-secondary, #6b6b70);
}

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 4px;
    justify-content: flex-end;
}

.btn-action {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid var(--border, #e5e5e7);
    background: var(--bg-card, white);
    color: var(--text-secondary, #6b6b70);
    text-decoration: none;
    font-size: 13px;
    transition: all 0.15s ease;
    padding: 0;
}

.btn-action:hover {
    border-color: var(--accent, #c01c28);
    color: var(--accent, #c01c28);
}

/* Pagination */
.pagination-container {
    padding: 12px 24px;
    border-top: 1px solid var(--border, #e5e5e7);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.pagination-info {
    font-size: 13px;
    color: var(--text-secondary, #6b6b70);
}

/* Empty state */
.empty-state {
    padding: 48px 16px;
    text-align: center;
}

.empty-state-icon {
    font-size: 32px;
    color: var(--border, #e5e5e7);
    margin-bottom: 12px;
}

.empty-state-title {
    color: var(--text-primary, #111113);
    font-weight: 600;
    margin-bottom: 4px;
}

.empty-state-text {
    color: var(--text-secondary, #6b6b70);
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

    .search-box {
        width: 100%;
    }

    .stats-grid {
        gap: 8px;
    }
}
</style>
@endsection

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-person-badge"></i> Data pemain
    </h1>

    <div class="d-flex gap-2 align-items-center flex-wrap">
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" placeholder="Cari pemain... (Enter untuk cari)"
                class="form-control" value="{{ request('search') }}" autocomplete="off">
            @if(request('search'))
                <button type="button" id="clearSearchBtn" class="clear-search-btn" title="Bersihkan pencarian">
                    <i class="bi bi-x-circle"></i>
                </button>
            @endif
        </div>

        <form action="{{ route('admin.players.recalculate-stats') }}" method="POST"
            onsubmit="return confirm('Hitung ulang statistik SEMUA pemain (gol, assist, kartu) dari data pertandingan?')">
            @csrf
            <button type="submit" class="btn btn-outline-secondary d-flex align-items-center"
                style="height: 40px; gap: 6px;"
                title="Sinkronkan statistik pemain dengan data pertandingan">
                <i class="bi bi-arrow-repeat me-1"></i> Hitung ulang statistik
            </button>
        </form>

        <a href="{{ route('admin.players.create') }}" class="btn-create">
            <i class="bi bi-plus-lg"></i> Tambah pemain
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

<div class="main-card">
    <div class="card-header">
        <h5>Daftar pemain</h5>
    </div>

    <div class="card-body p-0">
        @if($players->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="playersTable">
                    <thead>
                        <tr>
                            <th>Pemain</th>
                            <th>Tim</th>
                            <th>Posisi</th>
                            <th>Statistik</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($players as $player)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="player-photo">
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
                                            <div class="player-details d-flex align-items-center flex-wrap gap-1">
                                                @if($player->jersey_number)
                                                    <span>#{{ $player->jersey_number }}</span>
                                                @endif
                                                @if($player->nationality)
                                                    <span>{{ $player->nationality }}</span>
                                                @endif
                                                @if($player->formatted_market_value)
                                                    <span class="badge bg-light text-secondary border ms-1" style="font-size: 10px; font-weight: 500;">{{ $player->formatted_market_value }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($player->team)
                                        <span class="team-badge">
                                            <span class="team-initial">{{ strtoupper(substr($player->team->name, 0, 1)) }}</span>
                                            {{ $player->team->name }}
                                        </span>
                                    @else
                                        <span class="text-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($player->position)
                                        <span class="position-badge">{{ $player->position }}</span>
                                    @else
                                        <span class="text-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="stats-grid">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $player->goals }}</div>
                                            <div class="stat-label">Gol</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $player->assists }}</div>
                                            <div class="stat-label">Assist</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $player->yellow_cards }}</div>
                                            <div class="stat-label">Kuning</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $player->red_cards }}</div>
                                            <div class="stat-label">Merah</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.players.show', $player) }}" class="btn-action"
                                            title="Lihat detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.players.edit', $player) }}" class="btn-action"
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.players.destroy', $player) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action"
                                                onclick="return confirm('Hapus pemain ini?')" title="Hapus">
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

            @if($players->hasPages())
                <div class="pagination-container">
                    <div class="pagination-info">
                        Menampilkan {{ $players->firstItem() }}–{{ $players->lastItem() }} dari {{ $players->total() }} pemain
                    </div>

                    <div class="pagination-controls d-flex align-items-center gap-2">
                        @if($players->onFirstPage())
                            <span class="btn btn-outline-secondary btn-sm disabled">
                                <i class="bi bi-chevron-left"></i> Sebelumnya
                            </span>
                        @else
                            <a href="{{ $players->previousPageUrl() }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-chevron-left"></i> Sebelumnya
                            </a>
                        @endif

                        <span class="pagination-info">Halaman {{ $players->currentPage() }} / {{ $players->lastPage() }}</span>

                        @if($players->hasMorePages())
                            <a href="{{ $players->nextPageUrl() }}" class="btn btn-outline-secondary btn-sm">
                                Berikutnya <i class="bi bi-chevron-right"></i>
                            </a>
                        @else
                            <span class="btn btn-outline-secondary btn-sm disabled">
                                Berikutnya <i class="bi bi-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-person-badge"></i>
                </div>
                <h4 class="empty-state-title">Belum ada pemain</h4>
                <p class="empty-state-text">
                    @if(request('search'))
                        Tidak ada pemain yang cocok dengan pencarian "{{ request('search') }}".
                    @else
                        Tambahkan pemain pertama untuk mulai mengelola data pemain.
                    @endif
                </p>
                <a href="{{ route('admin.players.create') }}" class="btn-create">
                    <i class="bi bi-plus-lg"></i> Tambah pemain
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
// Search functionality - hanya cari saat Enter atau klik tombol search,
// TIDAK otomatis redirect tiap ketik (fix: input tidak hilang fokus saat mengetik)
const searchInput = document.getElementById('searchInput');
const clearSearchBtn = document.getElementById('clearSearchBtn');
const playersIndexUrl = '{{ route("admin.players.index") }}';

function submitPlayerSearch() {
    const search = searchInput.value.trim();
    if (search) {
        window.location.href = playersIndexUrl + '?search=' + encodeURIComponent(search);
    } else {
        window.location.href = playersIndexUrl;
    }
}

// Enter = cari
searchInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        submitPlayerSearch();
    }
    // Escape = bersihkan & reset
    if (e.key === 'Escape') {
        window.location.href = playersIndexUrl;
    }
});

// Tombol clear (x) = reset ke semua pemain
if (clearSearchBtn) {
    clearSearchBtn.addEventListener('click', function () {
        window.location.href = playersIndexUrl;
    });
}

// Handle photo loading errors
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.player-photo img').forEach(img => {
        img.addEventListener('error', function () {
            this.style.display = 'none';
            const fallback = this.nextElementSibling;
            if (fallback && fallback.classList.contains('player-photo-fallback')) {
                fallback.style.display = 'flex';
            }
        });
    });
});

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