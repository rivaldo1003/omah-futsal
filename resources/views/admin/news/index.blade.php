@extends('layouts.admin')

@section('title', 'News Management - OFS Futsal Center Admin')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
:root {
    --primary-color: #1a5fb4;
    --secondary-color: #5e5c64;
    --success-color: #26a269;
    --danger-color: #c01c28;
    --warning-color: #f5c211;
    --info-color: #1c71d8;
    --dark-color: #0f172a;
    --light-color: #f8fafc;
    --accent-color: #3b82f6;
}

/* Admin Header */
.admin-header {
    background: white;
    border-radius: 8px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.admin-header-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.admin-header-actions {
    display: flex;
    gap: 0.8rem;
}

/* Admin Buttons */
.btn-admin {
    border-radius: 6px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-admin-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border: none;
}

.btn-admin-secondary {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.btn-admin-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
}

.btn-admin-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
}

.btn-admin-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    border: none;
}

/* Stats Cards */
.stats-card {
    background: white;
    border-radius: 8px;
    padding: 1.2rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    margin-bottom: 1.5rem;
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-bottom: 0.8rem;
}

.stats-icon.primary {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(29, 78, 216, 0.05));
    color: #3b82f6;
}

.stats-icon.success {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.05));
    color: #10b981;
}

.stats-icon.warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.05));
    color: #f59e0b;
}

.stats-icon.danger {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.05));
    color: #ef4444;
}

.stats-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.2rem;
}

.stats-label {
    font-size: 0.85rem;
    color: #64748b;
}

/* Table */
.admin-table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    margin-bottom: 1.5rem;
}

.table-header {
    padding: 1.2rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
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
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
    padding: 0.9rem 1rem;
    font-weight: 600;
    color: #475569;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table tbody td {
    padding: 0.9rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #e2e8f0;
    font-size: 0.9rem;
}

.table tbody tr:hover {
    background-color: #f8fafc;
}

/* Status Badges */
.status-badge {
    padding: 0.25rem 0.6rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.status-active {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.status-inactive {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.status-featured {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.4rem;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.2s;
}

.btn-action-edit {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.btn-action-edit:hover {
    background: #3b82f6;
    color: white;
}

.btn-action-delete {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.btn-action-delete:hover {
    background: #ef4444;
    color: white;
}

.btn-action-view {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.btn-action-view:hover {
    background: #10b981;
    color: white;
}

/* Pagination */
.pagination-wrapper {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pagination-info {
    font-size: 0.85rem;
    color: #64748b;
}

/* Alert */
.alert-admin {
    border-radius: 6px;
    padding: 0.9rem 1rem;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
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
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .admin-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .admin-header-actions {
        width: 100%;
        flex-wrap: wrap;
    }

    .stats-card {
        margin-bottom: 1rem;
    }

    .table-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .action-buttons {
        flex-wrap: wrap;
    }
}

/* Quick Stats */
.quick-stats {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.quick-stats-title {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
}

.quick-stats-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.quick-stats-change {
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 4px;
}

.quick-stats-change.positive {
    color: #10b981;
}

.quick-stats-change.negative {
    color: #ef4444;
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
        <i class="bi bi-newspaper me-2"></i>News Management
    </h1>

    <div class="admin-header-actions">
        <a href="{{ route('news.index') }}" target="_blank" class="btn btn-admin btn-admin-secondary">
            <i class="bi bi-eye"></i> View Public News
        </a>
        <a href="{{ route('admin.news.create') }}" class="btn btn-admin btn-admin-primary">
            <i class="bi bi-plus-lg"></i> Add New Article
        </a>
    </div>
</div>

<!-- Quick Overview -->
<div class="quick-stats">
    <div class="row align-items-center">
        <div class="col-md-3">
            <div class="quick-stats-title">Total Articles</div>
            <div class="quick-stats-value">{{ $totalArticles }}</div>
            <div class="quick-stats-change positive">
                <i class="bi bi-arrow-up-right"></i>
                12% from last month
            </div>
        </div>
        <div class="col-md-3">
            <div class="quick-stats-title">Active Articles</div>
            <div class="quick-stats-value">{{ $activeArticles }}</div>
            <div class="quick-stats-change positive">
                <i class="bi bi-arrow-up-right"></i>
                {{ number_format(($activeArticles / $totalArticles) * 100, 1) }}% active rate
            </div>
        </div>
        <div class="col-md-3">
            <div class="quick-stats-title">Featured</div>
            <div class="quick-stats-value">{{ $featuredArticles }}</div>
            <div class="quick-stats-change">
                <i class="bi bi-star-fill"></i>
                Featured articles
            </div>
        </div>
        <div class="col-md-3">
            <div class="quick-stats-title">Total Views</div>
            <div class="quick-stats-value">{{ number_format($totalViews) }}</div>
            <div class="quick-stats-change positive">
                <i class="bi bi-eye-fill"></i>
                All-time views
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="stats-card">
            <div class="stats-icon primary">
                <i class="bi bi-newspaper"></i>
            </div>
            <div class="stats-number">{{ $totalArticles }}</div>
            <div class="stats-label">Total Articles</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stats-card">
            <div class="stats-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stats-number">{{ $activeArticles }}</div>
            <div class="stats-label">Active Articles</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stats-card">
            <div class="stats-icon warning">
                <i class="bi bi-star"></i>
            </div>
            <div class="stats-number">{{ $featuredArticles }}</div>
            <div class="stats-label">Featured Articles</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stats-card">
            <div class="stats-icon danger">
                <i class="bi bi-eye"></i>
            </div>
            <div class="stats-number">{{ number_format($totalViews) }}</div>
            <div class="stats-label">Total Views</div>
        </div>
    </div>
</div>

<!-- Articles Table -->
<div class="admin-table">
    <div class="table-header">
        <h2 class="table-title">Articles List</h2>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" style="width: auto;" id="filterStatus">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <select class="form-select form-select-sm" style="width: auto;" id="filterCategory">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
            <!-- <button class="btn btn-sm btn-outline-primary" id="exportBtn">
                <i class="bi bi-download"></i> Export
            </button> -->
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Title</th>
                    <th width="120">Category</th>
                    <th width="100">Status</th>
                    <th width="100">Featured</th>
                    <th width="100">Views</th>
                    <th width="100">Published</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr>
                    <td>{{ ($articles->currentPage() - 1) * $articles->perPage() + $loop->iteration }}</td>
                    <td>
                        <div class="fw-semibold">{{ Str::limit($article->title, 60) }}</div>
                        <small class="text-muted">by {{ $article->author ?? 'Admin' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $article->category }}</span>
                    </td>
                    <td>
                        @if($article->is_active)
                        <span class="status-badge status-active">
                            <i class="bi bi-check-circle"></i> Active
                        </span>
                        @else
                        <span class="status-badge status-inactive">
                            <i class="bi bi-x-circle"></i> Inactive
                        </span>
                        @endif
                    </td>
                    <td>
                        @if($article->is_featured)
                        <span class="status-badge status-featured">
                            <i class="bi bi-star"></i> Featured
                        </span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold">{{ number_format($article->views_count) }}</div>
                    </td>
                    <td>
                        {{ $article->published_at->format('d M Y') }}
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('news.show', $article->id) }}" target="_blank"
                                class="btn-action btn-action-view" title="View" data-bs-toggle="tooltip">
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
                                <button type="submit" class="btn-action btn-action-delete" title="Delete"
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
                        <div class="text-muted">
                            <i class="bi bi-newspaper display-6 d-block mb-2"></i>
                            No articles found.
                            <a href="{{ route('admin.news.create') }}" class="d-block mt-2">Create your first
                                article</a>
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
            Showing {{ $articles->firstItem() }} to {{ $articles->lastItem() }} of {{ $articles->total() }}
            entries
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
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
    const exportBtn = document.getElementById('exportBtn');

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

    // Export functionality
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            showLoading();
            // Get current filters for export
            const status = filterStatus ? filterStatus.value : '';
            const category = filterCategory ? filterCategory.value : '';

            // Simulate export (you would replace this with actual export logic)
            setTimeout(() => {
                hideLoading();
                const format = 'CSV';
                alert(`Articles exported as ${format} successfully!`);
                // In real implementation, you would trigger a download
                // window.location.href = `/admin/news/export?status=${status}&category=${category}&format=csv`;
            }, 1500);
        });
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
            `Are you sure you want to delete "${title}"?\n\nThis action cannot be undone and all associated data will be permanently removed.`
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