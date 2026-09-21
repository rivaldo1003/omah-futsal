@extends('layouts.admin')

@section('title', 'Data Tim - OFS Futsal Center Admin')

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

/* Search & filter */
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

.filter-select {
    border: 1px solid var(--border, #e5e5e7);
    border-radius: 6px;
    height: 40px;
    padding: 0 12px;
    font-size: 14px;
    background: var(--bg-card, white);
    color: var(--text-primary, #111113);
}

.filter-select:focus {
    border-color: var(--accent, #c01c28);
    box-shadow: 0 0 0 3px rgba(192, 28, 40, 0.1);
    outline: none;
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

/* Team info */
.team-logo-container {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    overflow: hidden;
    background: var(--surface, #f7f7f8);
    border: 1px solid var(--border, #e5e5e7);
    flex-shrink: 0;
}

.team-logo-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.team-avatar {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    background: var(--surface, #f7f7f8);
    border: 1px solid var(--border, #e5e5e7);
    color: var(--text-secondary, #6b6b70);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
}

.team-name {
    font-weight: 500;
    color: var(--text-primary, #111113);
    font-size: 14px;
    text-decoration: none;
}

.team-name:hover {
    color: var(--accent, #c01c28);
}

.team-details {
    font-size: 12px;
    color: var(--text-secondary, #6b6b70);
}

/* Badges */
.stats-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    background: var(--surface, #f7f7f8);
    border: 1px solid var(--border, #e5e5e7);
    border-radius: 6px;
    font-size: 12px;
    color: var(--text-primary, #111113);
}

.tournament-badge {
    display: block;
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 6px;
    background: var(--surface, #f7f7f8);
    border: 1px solid var(--border, #e5e5e7);
    color: var(--text-secondary, #6b6b70);
    margin-bottom: 4px;
}

.staff-badge {
    display: inline-block;
    background: var(--surface, #f7f7f8);
    border: 1px solid var(--border, #e5e5e7);
    color: var(--text-secondary, #6b6b70);
    padding: 1px 6px;
    border-radius: 4px;
    font-size: 12px;
    margin: 0 2px 2px 0;
    max-width: 130px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.status-badge {
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 6px;
    display: inline-block;
    font-weight: 500;
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

/* Detail expand */
.toggle-details {
    font-size: 12px;
    color: var(--text-secondary, #6b6b70);
    cursor: pointer;
    user-select: none;
    display: inline-block;
    margin-top: 4px;
}

.toggle-details:hover {
    color: var(--accent, #c01c28);
}

.team-expanded-info {
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px dashed var(--border, #e5e5e7);
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 4px;
}

.info-item {
    font-size: 12px;
    color: var(--text-secondary, #6b6b70);
}

.info-label {
    font-weight: 500;
}

.info-value {
    color: var(--text-primary, #111113);
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

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-people"></i> Data tim
    </h1>

    <div class="d-flex gap-2 align-items-center flex-wrap">
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" placeholder="Cari nama tim atau pelatih..."
                class="form-control" value="{{ request('search') }}">
        </div>

        <a href="{{ route('admin.teams.create') }}" class="btn-create">
            <i class="bi bi-plus-lg"></i> Tambah tim
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

<!-- Filter -->
<div class="d-flex gap-2 mb-3 flex-wrap align-items-center">
    <select id="statusFilter" class="filter-select">
        <option value="">Semua status</option>
        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
    </select>

    <select id="sortFilter" class="filter-select">
        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
        <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Terbaru</option>
        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
    </select>

    @if(request()->has('search') || request()->has('status') || request()->has('sort'))
    <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center">
        <i class="bi bi-x me-1"></i> Hapus filter
    </a>
    @endif
</div>

<div class="main-card">
    <div class="card-header">
        <h5>Daftar tim</h5>
    </div>

    <div class="card-body p-0">
        @if($teams->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="teamsTable">
                <thead>
                    <tr>
                        <th>Tim</th>
                        <th>Pemain</th>
                        <th>Turnamen</th>
                        <th>Staff pelatih</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teams as $team)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
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
                                        {{ $team->coach_name ?? $team->head_coach }}
                                    </div>

                                    <span class="toggle-details" onclick="toggleDetails('{{ $team->id }}')">
                                        <i class="bi bi-chevron-down" id="icon-{{ $team->id }}"></i> Info lainnya
                                    </span>

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
                                                    <span style="display: inline-block; width: 12px; height: 12px; background-color: {{ $team->primary_color }}; border-radius: 2px; vertical-align: middle; margin-right: 4px;"></span>
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
                                                <span class="info-value">{{ Str::limit(parse_url($team->website, PHP_URL_HOST) ?: $team->website, 15) }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="stats-badge">
                                <i class="bi bi-people"></i>
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
                                        <span class="badge bg-light text-dark">{{ $position }}: {{ $count }}</span>
                                    @endforeach
                                </small>
                            </div>
                            @endif
                        </td>
                        <td>
                            @if($team->tournaments->count() > 0)
                            <div style="max-height: 60px; overflow-y: auto;">
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
                            <span class="text-secondary">-</span>
                            @endif
                        </td>
                        <td style="min-width: 160px;">
                            <div style="max-height: 60px; overflow-y: auto;">
                                @if($team->head_coach)
                                <span class="staff-badge" title="Head coach: {{ $team->head_coach }}">
                                    {{ Str::limit($team->head_coach, 15) }}
                                </span>
                                @endif

                                @if($team->assistant_coach)
                                <span class="staff-badge" title="Assistant coach: {{ $team->assistant_coach }}">
                                    {{ Str::limit($team->assistant_coach, 15) }}
                                </span>
                                @endif

                                @if($team->goalkeeper_coach)
                                <span class="staff-badge" title="Goalkeeper coach: {{ $team->goalkeeper_coach }}">
                                    {{ Str::limit($team->goalkeeper_coach, 15) }}
                                </span>
                                @endif

                                @if($team->kitman)
                                <span class="staff-badge" title="Kitman: {{ $team->kitman }}">
                                    {{ Str::limit($team->kitman, 15) }}
                                </span>
                                @endif

                                @if(!$team->head_coach && !$team->assistant_coach && !$team->goalkeeper_coach && !$team->kitman)
                                <span class="text-secondary">-</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($team->status == 'active')
                            <span class="status-badge status-active">Aktif</span>
                            @elseif($team->status == 'pending')
                            <span class="status-badge status-pending">Pending</span>
                            @else
                            <span class="status-badge status-inactive">Nonaktif</span>
                            @endif

                            @if($team->created_at)
                            <div class="mt-1">
                                <small class="text-secondary">{{ $team->created_at->format('d/m/Y') }}</small>
                            </div>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="action-buttons">
                                <a href="{{ route('admin.teams.show', $team) }}" class="btn-action"
                                    title="Lihat detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.teams.edit', $team) }}" class="btn-action"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn-action" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $team->id }}" title="Hapus">
                                    <i class="bi bi-trash"></i>
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
                <span class="btn btn-outline-secondary btn-sm disabled">
                    <i class="bi bi-chevron-left"></i> Sebelumnya
                </span>
                @else
                <a href="{{ $teams->previousPageUrl() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-chevron-left"></i> Sebelumnya
                </a>
                @endif

                <span class="pagination-info">Halaman {{ $teams->currentPage() }} / {{ $teams->lastPage() }}</span>

                @if($teams->hasMorePages())
                <a href="{{ $teams->nextPageUrl() }}" class="btn btn-outline-secondary btn-sm">
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
                <i class="bi bi-people"></i>
            </div>
            <h4 class="empty-state-title">Belum ada tim</h4>
            <p class="empty-state-text">
                @if(request()->has('search') || request()->has('status'))
                Tidak ada tim yang cocok dengan pencarian atau filter. Coba ubah kriteria pencarian.
                @else
                Tambahkan tim pertama untuk mulai mengelola turnamen.
                @endif
            </p>
            <a href="{{ route('admin.teams.create') }}" class="btn-create">
                <i class="bi bi-plus-lg"></i> Tambah tim
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
@endsection

@section('scripts')
<script>
// Search dengan debounce
let searchTimeout;
document.getElementById('searchInput').addEventListener('keyup', function(e) {
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
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        clearTimeout(searchTimeout);
        updateFilters();
    }
});

// Toggle details function
function toggleDetails(teamId) {
    const detailsDiv = document.getElementById('details-' + teamId);
    const icon = document.getElementById('icon-' + teamId);

    if (detailsDiv.style.display === 'none') {
        detailsDiv.style.display = 'block';
        icon.classList.remove('bi-chevron-down');
        icon.classList.add('bi-chevron-up');
    } else {
        detailsDiv.style.display = 'none';
        icon.classList.remove('bi-chevron-up');
        icon.classList.add('bi-chevron-down');
    }
}

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