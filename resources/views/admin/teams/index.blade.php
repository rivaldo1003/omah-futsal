@extends('layouts.admin')

@section('title', 'Teams Management')@section('styles')<style>
:root {
    --primary: #1e3a8a;
    --primary-light: #3b82f6;
    --secondary: #6b7280;
    --bg-main: #f8fafc;
    --bg-card: #ffffff;
    --border-color: #e5e7eb;
    --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.page-header h1 {
    color: var(--primary);
    font-weight: 600;
    margin: 0;
    font-size: 1.5rem;
}

.btn-create {
    background: var(--primary);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    font-size: 0.875rem;
}

.btn-create:hover {
    background: #1d4ed8;
    color: white;
}

/* Search Box */
.search-box {
    position: relative;
    width: 300px;
}

.search-box input {
    padding-left: 2.5rem;
    border-radius: 6px;
    border: 1px solid var(--border-color);
    width: 100%;
    font-size: 0.875rem;
}

.search-box input:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    outline: none;
}

.search-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary);
    pointer-events: none;
}

/* Filter Selects */
.filter-select {
    border-radius: 6px;
    border: 1px solid var(--border-color);
    padding: 0.5rem;
    background: white;
    color: var(--primary);
    font-size: 0.875rem;
    min-width: 120px;
}

.filter-select:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    outline: none;
}

/* Main Card */
.main-card {
    background: var(--bg-card);
    border-radius: 8px;
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.card-header {
    background: #f8fafc;
    border-bottom: 1px solid var(--border-color);
    padding: 1rem;
}

.card-header h5 {
    margin: 0;
    font-weight: 600;
    font-size: 1rem;
    color: var(--primary);
}

/* Table Styling */
.table {
    margin: 0;
    font-size: 0.875rem;
}

.table thead th {
    background: #f8fafc;
    color: var(--secondary);
    font-weight: 600;
    border-bottom: 1px solid var(--border-color);
    padding: 0.75rem 1rem;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.table tbody td {
    padding: 0.75rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
}

.table tbody tr:hover {
    background-color: rgba(59, 130, 246, 0.02);
}

/* Team Info */
.team-avatar {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.team-name {
    font-weight: 500;
    color: var(--primary);
    margin-bottom: 2px;
    font-size: 0.875rem;
    text-decoration: none;
}

.team-name:hover {
    color: var(--primary-light);
}

.team-details {
    font-size: 0.75rem;
    color: var(--secondary);
}

.team-shortname {
    color: var(--primary-light);
    font-weight: 600;
    margin-right: 0.5rem;
}

/* Stats Badges */
.stats-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    background: #f8fafc;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    font-size: 0.75rem;
    color: var(--primary);
}

/* Group Badge */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    display: inline-block;
}

.badge-group {
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary-light);
}

/* Status Badges */
.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    display: inline-block;
}

.status-active {
    background: rgba(34, 197, 94, 0.1);
    color: #16a34a;
}

.status-pending {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.status-inactive {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
}

.btn-action {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    border: 1px solid var(--border-color);
    background: white;
    color: var(--secondary);
    text-decoration: none;
    transition: all 0.2s;
}

.btn-action:hover {
    border-color: var(--primary-light);
}

.btn-view:hover {
    background: var(--primary-light);
    color: white;
}

.btn-edit:hover {
    background: #f59e0b;
    color: white;
}

.btn-delete:hover {
    background: #dc2626;
    color: white;
}

/* Empty State */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-state-icon {
    font-size: 2.5rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

.empty-state-title {
    color: var(--primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: var(--secondary);
    font-size: 0.875rem;
    margin-bottom: 1.5rem;
    max-width: 300px;
    margin-left: auto;
    margin-right: auto;
}

/* Pagination */
.pagination-container {
    padding: 0.75rem 1rem;
    border-top: 1px solid var(--border-color);
    background: #f8fafc;
}

.pagination-info {
    font-size: 0.75rem;
    color: var(--secondary);
}

.pagination-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
}

.pagination-btn {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 4px;
    border: 1px solid var(--border-color);
    background: white;
    color: var(--secondary);
    text-decoration: none;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.pagination-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f8fafc;
}

.pagination-pages {
    display: flex;
    gap: 0.25rem;
}

.page-link {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 4px;
    border: 1px solid var(--border-color);
    background: white;
    color: var(--secondary);
    text-decoration: none;
    transition: all 0.2s;
    min-width: 32px;
    text-align: center;
}

.team-logo-container {

    flex-shrink: 0;
    /* border: 1px solid var(--border-color); */
    border-radius: 6px;
    overflow: hidden;
    background: #f8fafc;
}

.team-logo {
    display: block;
}

.team-logo-placeholder {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.page-link:hover {
    background: #f8fafc;
    color: var(--primary);
    border-color: var(--primary-light);
}

.page-link.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.page-link.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Alert */
.alert {
    border-radius: 6px;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    margin-bottom: 1rem;
    border: none;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
}

/* Modal */
.modal-content {
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.modal-body {
    padding: 1.5rem;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .search-box {
        width: 100%;
    }

    .filter-select {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .table-responsive {
        font-size: 0.75rem;
    }

    .table thead th,
    .table tbody td {
        padding: 0.5rem;
    }

    .pagination-container {
        flex-direction: column;
        gap: 0.75rem;
    }

    .pagination-controls {
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination-pages {
        order: -1;
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb" style="font-size: 0.875rem; padding: 0; background: none;">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Teams</li>
    </ol>
</nav>

<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="d-flex align-items-center m-0">
        <i class="bi bi-people-fill me-2"></i>
        Teams
    </h1>

    <div class="d-flex gap-2 align-items-center">
        <div class="search-box d-flex align-items-center" style="height: 38px; position: relative;">
            <i class="bi bi-search search-icon" style="position: absolute; left: 10px; color: #888;"></i>

            <input type="text" id="searchInput" placeholder="Search teams..." class="form-control"
                style="padding-left: 32px; height: 38px;" value="{{ request('search') }}">
        </div>

        <a href="{{ route('admin.teams.create') }}" class="btn-create d-flex align-items-center justify-content-center"
            style="height: 38px; padding: 0 14px;">
            <i class="bi bi-plus me-1"></i>
            Add Team
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

<!-- Filters Row -->
<div class="d-flex gap-2 mb-3 flex-wrap">
    <select id="statusFilter" class="filter-select">
        <option value="">All Status</option>
        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>

    <select id="sortFilter" class="filter-select">
        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
        <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Most Recent</option>
        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
    </select>

    @if(request()->has('search') || request()->has('status') || request()->has('sort'))
    <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center">
        <i class="bi bi-x me-1"></i> Clear Filters
    </a>
    @endif
</div>

<div class="main-card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list me-2"></i> Teams List</h5>
    </div>

    <div class="card-body p-0">
        @if($teams->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="teamsTable">
                <thead>
                    <tr>
                        <th>Team</th>
                        <th>Players</th>
                        <th>Group</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teams as $team)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($team->logo && Storage::disk('public')->exists($team->logo))
                                <div class="team-logo-container"
                                    style="width: 36px; height: 36px; margin-right: 0.75rem;">
                                    <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}"
                                        class="team-logo"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">
                                </div>
                                @elseif($team->logo && filter_var($team->logo, FILTER_VALIDATE_URL))
                                <div class="team-logo-container"
                                    style="width: 36px; height: 36px; margin-right: 0.75rem;">
                                    <img src="{{ $team->logo }}" alt="{{ $team->name }}" class="team-logo"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">
                                </div>
                                @else
                                <div class="team-avatar">
                                    {{ strtoupper(substr($team->name, 0, 1)) }}
                                </div>
                                @endif

                                <div>
                                    <a href="{{ route('admin.teams.show', $team) }}" class="team-name">
                                        {{ $team->name }}
                                    </a>
                                    <div class="team-details">
                                        @if($team->short_name)
                                        <span class="team-shortname">({{ $team->short_name }})</span>
                                        @endif
                                        @if($team->coach_name)
                                        <span class="text-muted">{{ $team->coach_name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="stats-badge">
                                <i class="bi bi-people"></i>
                                {{ $team->players_count ?? $team->players->count() }} players
                            </span>
                        </td>
                        <td>
                            @if($team->group_name)
                            <span class="badge badge-group">
                                {{ $team->group_name }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($team->status == 'active')
                            <span class="status-badge status-active">Active</span>
                            @elseif($team->status == 'pending')
                            <span class="status-badge status-pending">Pending</span>
                            @else
                            <span class="status-badge status-inactive">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="action-buttons">
                                <a href="{{ route('admin.teams.show', $team) }}" class="btn-action btn-view"
                                    title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.teams.edit', $team) }}" class="btn-action btn-edit"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn-action btn-delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $team->id }}" title="Delete">
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
        <div class="pagination-container d-flex justify-content-between align-items-center flex-wrap">
            <div class="pagination-info">
                Showing {{ $teams->firstItem() }} to {{ $teams->lastItem() }} of {{ $teams->total() }} teams
            </div>

            <div class="pagination-controls">
                <!-- Previous Button -->
                @if($teams->onFirstPage())
                <span class="pagination-btn disabled">
                    <i class="bi bi-chevron-left"></i> Previous
                </span>
                @else
                <a href="{{ $teams->previousPageUrl() }}" class="pagination-btn">
                    <i class="bi bi-chevron-left"></i> Previous
                </a>
                @endif

                <!-- Page Numbers -->
                <div class="pagination-pages">
                    @php
                    $current = $teams->currentPage();
                    $last = $teams->lastPage();
                    $start = max(1, $current - 2);
                    $end = min($last, $current + 2);

                    if ($start > 1) {
                    echo '<a href="' . $teams->url(1) . '" class="page-link">1</a>';
                    if ($start > 2) {
                    echo '<span class="page-link disabled">...</span>';
                    }
                    }

                    for ($i = $start; $i <= $end; $i++) { if ($i==$current) { echo '<span class="page-link active">' .
                        $i . '</span>' ; } else { echo '<a href="' . $teams->url($i) . '" class="page-link">' . $i .
                        '</a>';
                        }
                        }

                        if ($end < $last) { if ($end < $last - 1) { echo '<span class="page-link disabled">...</span>' ;
                            } echo '<a href="' . $teams->url($last) . '" class="page-link">' . $last . '</a>';
                            }
                            @endphp
                </div>

                <!-- Next Button -->
                @if($teams->hasMorePages())
                <a href="{{ $teams->nextPageUrl() }}" class="pagination-btn">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
                @else
                <span class="pagination-btn disabled">
                    Next <i class="bi bi-chevron-right"></i>
                </span>
                @endif
            </div>
        </div>
        @endif
        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <h4 class="empty-state-title">No Teams Found</h4>
            <p class="empty-state-text">
                @if(request()->has('search') || request()->has('status'))
                Try adjusting your search or filter to find what you're looking for.
                @else
                Start by adding your first team to the system.
                @endif
            </p>
            <a href="{{ route('admin.teams.create') }}" class="btn-create">
                <i class="bi bi-plus"></i>
                Add First Team
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
                <h5 class="modal-title">Delete Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bi bi-exclamation-triangle text-danger fs-1"></i>
                </div>
                <p class="text-center">
                    Delete "<strong>{{ $team->name }}</strong>"?
                </p>
                <div class="alert alert-warning mt-3 text-start">
                    <i class="bi bi-info-circle me-2"></i>
                    This team has {{ $team->players->count() }} players and {{ $team->tournaments->count() }}
                    tournaments.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        Delete Team
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
// Search functionality
let searchTimeout;
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        const search = this.value;
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
    }, 500);
});

// Filter change handlers
document.getElementById('statusFilter').addEventListener('change', function() {
    updateFilters();
});

document.getElementById('sortFilter').addEventListener('change', function() {
    updateFilters();
});

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
        this.dispatchEvent(new Event('keyup'));
    }
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