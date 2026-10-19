@extends('layouts.admin')

@section('title', 'Data Pemain - OFS Futsal Center Admin')

@section('styles')
    <style>
        @include('admin.players.partials.admin-styles')
    </style>
@endsection

@section('content')
    <div class="admin-player-page">
        <div class="page-header">
            <div class="page-heading">
                <h1>
                    <i class="bi bi-person-badge"></i> Data pemain
                </h1>
                <p class="page-subtitle">Kelola profil, keanggotaan tim, dan statistik pemain.</p>
            </div>

            <div class="player-header-actions d-flex gap-2 align-items-center">
                <div class="search-box">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="searchInput" placeholder="Cari pemain... (Enter untuk cari)" class="form-control"
                        value="{{ request('search') }}" autocomplete="off" aria-label="Cari pemain">
                    @if(request('search'))
                        <button type="button" id="clearSearchBtn" class="clear-search-btn" title="Bersihkan pencarian"
                            aria-label="Bersihkan pencarian">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    @endif
                </div>

                <form action="{{ route('admin.players.recalculate-stats') }}" method="POST"
                    onsubmit="return confirm('Hitung ulang statistik SEMUA pemain (gol, assist, kartu) dari data pertandingan?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary d-flex align-items-center"
                        style="height: 40px; gap: 6px;" title="Sinkronkan statistik pemain dengan data pertandingan">
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
                        <table class="table table-hover mb-0 players-table" id="playersTable">
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
                                            <div class="player-identity">
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
                                                <div class="player-identity-copy">
                                                    <div class="player-name">{{ $player->name }}</div>
                                                    <div class="player-details d-flex align-items-center flex-wrap gap-1">
                                                        @if($player->jersey_number)
                                                            <span>#{{ $player->jersey_number }}</span>
                                                        @endif
                                                        @if($player->nationality)
                                                            <span>{{ $player->nationality }}</span>
                                                        @endif
                                                        @if($player->formatted_market_value)
                                                            <span class="badge bg-light text-secondary border ms-1"
                                                                style="font-size: 10px; font-weight: 500;">{{ $player->formatted_market_value }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($player->team)
                                                <span class="team-badge">
                                                    <span
                                                        class="team-initial">{{ strtoupper(substr($player->team->name, 0, 1)) }}</span>
                                                    {{ $player->team->name }}
                                                </span>
                                            @else
                                                <span class="text-secondary">Tanpa tim</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($player->position)
                                                <span class="position-badge">{{ $player->position }}</span>
                                            @else
                                                <span class="text-secondary">Belum ditetapkan</span>
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
                                                    title="Lihat detail" aria-label="Lihat detail {{ $player->name }}">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.players.edit', $player) }}" class="btn-action" title="Edit"
                                                    aria-label="Edit {{ $player->name }}">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.players.destroy', $player) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action btn-action-danger"
                                                        onclick="return confirm('Hapus pemain ini?')" title="Hapus"
                                                        aria-label="Hapus pemain">
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
                                Menampilkan {{ $players->firstItem() }}–{{ $players->lastItem() }} dari {{ $players->total() }}
                                pemain
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

                                <span class="pagination-info">Halaman {{ $players->currentPage() }} /
                                    {{ $players->lastPage() }}</span>

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
