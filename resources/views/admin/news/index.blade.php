@extends('layouts.admin')

@section('title', 'News Management - OFS Futsal Center Admin')

@section('styles')
<style>
/* Header */
.admin-header {
    background: var(--bg-card, white);
    border: 1px solid var(--border, #e5e5e7);
    border-radius: 12px;
    padding: 16px 24px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.admin-header-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-primary, #111113);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.admin-header-actions {
    display: flex;
    gap: 8px;
}

/* Buttons */
.btn-admin {
    border-radius: 6px;
    font-weight: 500;
    padding: 8px 16px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.15s ease;
}

.btn-admin-primary {
    background: var(--accent, #c01c28);
    color: white;
    border: none;
}

.btn-admin-primary:hover {
    background: var(--accent-hover, #a51822);
    color: white;
}

.btn-admin-secondary {
    background: var(--bg-card, white);
    color: var(--text-secondary, #6b6b70);
    border: 1px solid var(--border, #e5e5e7);
}

.btn-admin-secondary:hover {
    border-color: var(--accent, #c01c28);
    color: var(--accent, #c01c28);
}

/* Stat ringkas: satu baris, dipisah hairline */
.stats-row {
    background: var(--bg-card, white);
    border: 1px solid var(--border, #e5e5e7);
    border-radius: 12px;
    padding: 16px 24px;
    margin-bottom: 24px;
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
}

.stats-item {
    flex: 1;
    min-width: 140px;
}

.stats-number {
    font-size: 24px;
    font-weight: 600;
    color: var(--text-primary, #111113);
    line-height: 1.2;
}

.stats-label {
    font-size: 13px;
    color: var(--text-secondary, #6b6b70);
    margin-top: 2px;
}

/* Table */
.admin-table {
    background: var(--bg-card, white);
    border: 1px solid var(--border, #e5e5e7);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 24px;
}

.table-header {
    padding: 16px 24px;
    border-bottom: 1px solid var(--border, #e5e5e7);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.table-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary, #111113);
    margin: 0;
}

.table-responsive {
    overflow-x: auto;
}

.table {
    margin: 0;
    width: 100%;
}

.table thead th {
    background: var(--surface, #f7f7f8);
    border-bottom: 1px solid var(--border, #e5e5e7);
    padding: 10px 16px;
    font-weight: 500;
    color: var(--text-secondary, #6b6b70);
    font-size: 13px;
    white-space: nowrap;
}

.table tbody td {
    padding: 12px 16px;
    vertical-align: middle;
    border-bottom: 1px solid var(--border, #e5e5e7);
    font-size: 14px;
}

.table tbody tr:last-child td {
    border-bottom: none;
}

.table tbody tr:hover {
    background-color: var(--surface, #f7f7f8);
}

/* Status badges */
.status-badge {
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.status-active {
    background: var(--success-light, #f0f9f4);
    color: var(--success, #1e7a46);
}

.status-inactive {
    background: var(--danger-light, #fdf0f0);
    color: var(--accent, #c01c28);
}

.status-featured {
    background: var(--warning-light, #fdf6ec);
    color: var(--warning, #b45309);
}

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 4px;
}

.btn-action {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    border: 1px solid var(--border, #e5e5e7);
    background: var(--bg-card, white);
    color: var(--text-secondary, #6b6b70);
    display: inline-flex;
    align-items: center;
    justify-content: center;
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
.pagination-wrapper {
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

/* Alert */
.alert-admin {
    border-radius: 6px;
    padding: 12px 16px;
    font-size: 14px;
    margin-bottom: 16px;
    border: none;
}

/* Loading */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    display: none;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid var(--border, #e5e5e7);
    border-top-color: var(--accent, #c01c28);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .admin-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .table-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .action-buttons {
        flex-wrap: wrap;
    }
}
</style>
@endsection

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success alert-admin">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-admin">
    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
</div>
@endif

<!-- Header -->
<div class="admin-header">
    <h1 class="admin-header-title">
        <i class="bi bi-newspaper"></i> News Management
    </h1>

    <div class="admin-header-actions">
        <a href="{{ route('news.index') }}" target="_blank" class="btn btn-admin btn-admin-secondary">
            <i class="bi bi-box-arrow-up-right"></i> Lihat halaman publik
        </a>
        <a href="{{ route('admin.news.create') }}" class="btn btn-admin btn-admin-primary">
            <i class="bi bi-plus-lg"></i> Tulis artikel baru
        </a>
    </div>
</div>

<!-- Statistik ringkas -->
<div class="stats-row">
    <div class="stats-item">
        <div class="stats-number">{{ $totalArticles }}</div>
        <div class="stats-label">Total artikel</div>
    </div>
    <div class="stats-item">
        <div class="stats-number">{{ $activeArticles }}</div>
        <div class="stats-label">Artikel aktif</div>
    </div>
    <div class="stats-item">
        <div class="stats-number">{{ $featuredArticles }}</div>
        <div class="stats-label">Artikel unggulan</div>
    </div>
    <div class="stats-item">
        <div class="stats-number">{{ number_format($totalViews) }}</div>
        <div class="stats-label">Total views</div>
    </div>
</div>

<!-- Articles Table -->
<div class="admin-table">
    <div class="table-header">
        <h2 class="table-title">Daftar artikel</h2>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" style="width: auto;" id="filterStatus">
                <option value="">Semua status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
            <select class="form-select form-select-sm" style="width: auto;" id="filterCategory">
                <option value="">Semua kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Judul</th>
                    <th width="120">Kategori</th>
                    <th width="100">Status</th>
                    <th width="100">Unggulan</th>
                    <th width="100">Views</th>
                    <th width="110">Terbit</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr>
                    <td>{{ ($articles->currentPage() - 1) * $articles->perPage() + $loop->iteration }}</td>
                    <td>
                        <div class="fw-semibold">{{ Str::limit($article->title, 60) }}</div>
                        <small class="text-secondary">oleh {{ $article->author ?? 'Admin' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $article->category }}</span>
                    </td>
                    <td>
                        @if($article->is_active)
                        <span class="status-badge status-active">
                            <i class="bi bi-check-circle"></i> Aktif
                        </span>
                        @else
                        <span class="status-badge status-inactive">
                            <i class="bi bi-x-circle"></i> Nonaktif
                        </span>
                        @endif
                    </td>
                    <td>
                        @if($article->is_featured)
                        <span class="status-badge status-featured">
                            <i class="bi bi-star"></i> Unggulan
                        </span>
                        @else
                        <span class="text-secondary">-</span>
                        @endif
                    </td>
                    <td>{{ number_format($article->views_count) }}</td>
                    <td>{{ $article->published_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('news.show', $article->id) }}" target="_blank"
                                class="btn-action btn-action-view" title="Lihat" data-bs-toggle="tooltip">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.news.edit', $article->id) }}" class="btn-action btn-action-edit"
                                title="Edit" data-bs-toggle="tooltip">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.news.destroy', $article->id) }}" method="POST"
                                class="d-inline"
                                onsubmit="return confirmDelete(event, '{{ addslashes($article->title) }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-action-delete" title="Hapus"
                                    data-bs-toggle="tooltip">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="text-secondary">
                            Belum ada artikel.
                            <a href="{{ route('admin.news.create') }}" class="d-block mt-2">Tulis artikel pertama</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($articles->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Menampilkan {{ $articles->firstItem() }}–{{ $articles->lastItem() }} dari {{ $articles->total() }} artikel
        </div>
        <div>
            {{ $articles->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Filter functionality
    const filterStatus = document.getElementById('filterStatus');
    const filterCategory = document.getElementById('filterCategory');

    function applyFilters() {
        const status = filterStatus ? filterStatus.value : '';
        const category = filterCategory ? filterCategory.value : '';

        let url = new URL(window.location.href);
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }

        if (category) {
            url.searchParams.set('category', category);
        } else {
            url.searchParams.delete('category');
        }

        // Reset to page 1 when filtering
        url.searchParams.set('page', '1');

        window.location.href = url.toString();
    }

    if (filterStatus) {
        filterStatus.addEventListener('change', applyFilters);
    }

    if (filterCategory) {
        filterCategory.addEventListener('change', applyFilters);
    }

    // Set current filter values from URL
    const urlParams = new URLSearchParams(window.location.search);
    if (filterStatus) {
        filterStatus.value = urlParams.get('status') || '';
    }
    if (filterCategory) {
        filterCategory.value = urlParams.get('category') || '';
    }

    // Show loading overlay on form submits
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        if (form.method.toLowerCase() === 'post' || form.method.toLowerCase() === 'put' || form
            .method.toLowerCase() === 'delete') {
            form.addEventListener('submit', function() {
                showLoading();
            });
        }
    });

    // Auto-hide success messages after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-admin');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Confirm delete with article title
function confirmDelete(event, title) {
    if (!confirm(
            `Hapus artikel "${title}"?\n\nTindakan ini tidak bisa dibatalkan dan semua data terkait akan dihapus permanen.`
        )) {
        event.preventDefault();
        return false;
    }

    // Show loading overlay
    showLoading();
    return true;
}

// Loading overlay control
function showLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.style.display = 'flex';
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.style.display = 'none';
}

// Quick toggle status (if you want to add AJAX toggling)
function toggleArticleStatus(articleId, currentStatus) {
    if (!confirm(`Are you sure you want to ${currentStatus ? 'deactivate' : 'activate'} this article?`)) {
        return;
    }

    showLoading();

    fetch(`/admin/news/${articleId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                _method: 'PATCH'
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Failed to update status');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
}

// Quick toggle featured (if you want to add AJAX toggling)
function toggleFeatured(articleId, currentFeatured) {
    if (!confirm(
            `Are you sure you want to ${currentFeatured ? 'remove from featured' : 'mark as featured'} this article?`
        )) {
        return;
    }

    showLoading();

    fetch(`/admin/news/${articleId}/toggle-featured`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                _method: 'PATCH'
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Failed to update featured status');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
}
</script>
@endsection