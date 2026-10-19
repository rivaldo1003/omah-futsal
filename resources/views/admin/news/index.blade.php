@extends('layouts.admin')

@section('title', 'Manajemen Berita - OFS Futsal Center Admin')

@section('styles')
    <style>
        .admin-news-page {
            --news-ink: #111111;
            --news-charcoal: #39393b;
            --news-muted: #707072;
            --news-line: #cacacb;
            --news-line-soft: #e5e5e5;
            --news-cloud: #f5f5f5;
            --news-canvas: #ffffff;
            --news-success: #007d48;
            --news-danger: #b42318;
            max-width: 1440px;
            margin: 0 auto;
            color: var(--news-ink);
            font-size: 16px;
            line-height: 1.5;
        }

        .admin-news-page .admin-header {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 20px 24px;
            margin-bottom: 28px;
        }

        .admin-news-page .admin-header-info {
            min-width: 0;
        }

        .admin-news-page .admin-header-title {
            margin: 0 0 8px;
            color: var(--news-ink);
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
        }

        .admin-news-page .admin-header-title i {
            margin-right: 8px;
            font-size: .85em;
        }

        .admin-news-page .admin-header-subtitle {
            max-width: 66ch;
            margin: 0;
            color: var(--news-muted);
            font-size: 15px;
            line-height: 1.5;
        }

        .admin-news-page .admin-header-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .admin-news-page .btn-admin {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            border: 1px solid transparent;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            text-decoration: none;
            white-space: nowrap;
            transition: background-color 150ms ease-out, border-color 150ms ease-out, color 150ms ease-out;
        }

        .admin-news-page .btn-admin-primary {
            border-color: var(--news-ink);
            background: var(--news-ink);
            color: #ffffff;
        }

        .admin-news-page .btn-admin-primary:hover {
            border-color: var(--news-charcoal);
            background: var(--news-charcoal);
            color: #ffffff;
        }

        .admin-news-page .btn-admin-secondary {
            border-color: transparent;
            background: var(--news-cloud);
            color: var(--news-ink);
        }

        .admin-news-page .btn-admin-secondary:hover {
            border-color: var(--news-line);
            background: #eeeeee;
            color: var(--news-ink);
        }

        .admin-news-page :is(a, button, select):focus-visible {
            outline: 2px solid var(--news-ink);
            outline-offset: 3px;
        }

        .admin-news-page .alert-admin {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
            padding: 14px 16px;
            border: 1px solid var(--news-line-soft);
            border-radius: 8px;
            background: var(--news-cloud);
            color: var(--news-charcoal);
            font-size: 14px;
            line-height: 1.5;
        }

        .admin-news-page .alert-admin.alert-success {
            border-color: var(--news-line-soft);
            background: var(--news-cloud);
            color: var(--news-success);
        }

        .admin-news-page .alert-admin.alert-danger {
            border-color: var(--news-line-soft);
            background: var(--news-cloud);
            color: var(--news-danger);
        }

        .admin-news-page .stats-row,
        .admin-news-page .admin-table {
            border: 1px solid var(--news-line-soft);
            border-radius: 8px;
            background: var(--news-canvas);
        }

        .admin-news-page .stats-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 24px;
            overflow: hidden;
        }

        .admin-news-page .stats-item {
            min-width: 0;
            padding: 18px 20px;
            border-right: 1px solid var(--news-line-soft);
        }

        .admin-news-page .stats-item:last-child {
            border-right: 0;
        }

        .admin-news-page .stats-number {
            color: var(--news-ink);
            font-size: 28px;
            font-variant-numeric: tabular-nums;
            font-weight: 500;
            line-height: 1.2;
        }

        .admin-news-page .stats-label {
            margin-top: 4px;
            color: var(--news-muted);
            font-size: 14px;
            line-height: 1.4;
        }

        .admin-news-page .admin-table {
            margin-bottom: 24px;
            overflow: hidden;
        }

        .admin-news-page .table-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px 20px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--news-line-soft);
        }

        .admin-news-page .table-title {
            margin: 0;
            color: var(--news-ink);
            font-size: 22px;
            font-weight: 500;
            line-height: 1.25;
        }

        .admin-news-page .table-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .admin-news-page .filter-control {
            min-width: 150px;
            min-height: 44px;
            padding: 8px 36px 8px 16px;
            border: 1px solid var(--news-line);
            border-radius: 24px;
            appearance: none;
            background-color: var(--news-canvas);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23111111' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 14px 14px;
            color: var(--news-ink);
            cursor: pointer;
            font-size: 14px;
            line-height: 1.25;
        }

        .admin-news-page .filter-control:focus {
            border-color: var(--news-ink);
            box-shadow: 0 0 0 3px var(--news-cloud);
        }

        .admin-news-page .table-responsive {
            overflow-x: auto;
        }

        .admin-news-page .news-table {
            width: 100%;
            min-width: 1120px;
            margin: 0;
            border-collapse: collapse;
            color: var(--news-ink);
            table-layout: fixed;
        }

        .admin-news-page .news-table thead th {
            padding: 12px 16px;
            border-bottom: 1px solid var(--news-line-soft);
            background: var(--news-cloud);
            color: var(--news-muted);
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
        }

        .admin-news-page .news-table th:first-child,
        .admin-news-page .news-table td:first-child {
            width: 56px;
        }

        .admin-news-page .news-table th:last-child,
        .admin-news-page .news-table td:last-child {
            width: 164px;
        }

        .admin-news-page .news-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--news-line-soft);
            background: var(--news-canvas);
            font-size: 14px;
            vertical-align: middle;
        }

        .admin-news-page .news-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .admin-news-page .news-table tbody tr:hover td {
            background: #fafafa;
        }

        .admin-news-page .article-title {
            color: var(--news-ink);
            font-size: 15px;
            font-weight: 500;
            line-height: 1.4;
        }

        .admin-news-page .article-meta {
            margin-top: 3px;
            color: var(--news-muted);
            font-size: 13px;
        }

        .admin-news-page .category-label {
            display: inline-flex;
            align-items: center;
            min-height: 30px;
            padding: 4px 10px;
            border: 1px solid var(--news-line-soft);
            border-radius: 9999px;
            color: var(--news-charcoal);
            font-size: 13px;
            line-height: 1.3;
        }

        .admin-news-page .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--news-muted);
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
        }

        .admin-news-page .status-active {
            color: var(--news-success);
        }

        .admin-news-page .status-inactive {
            color: var(--news-muted);
        }

        .admin-news-page .status-featured {
            color: var(--news-charcoal);
        }

        .admin-news-page .number-cell {
            font-variant-numeric: tabular-nums;
        }

        .admin-news-page .action-buttons {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .admin-news-page .action-buttons form {
            display: inline-flex;
            flex: 0 0 auto;
            margin: 0;
        }

        .admin-news-page .btn-action {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 1px solid transparent;
            border-radius: 9999px;
            background: var(--news-cloud);
            color: var(--news-ink);
            font-size: 15px;
            text-decoration: none;
            transition: background-color 150ms ease-out, color 150ms ease-out;
        }

        .admin-news-page .btn-action:hover {
            background: #e5e5e5;
            color: var(--news-ink);
        }

        .admin-news-page .pagination-wrapper {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px;
            border-top: 1px solid var(--news-line-soft);
        }

        .admin-news-page .pagination-info {
            color: var(--news-muted);
            font-size: 14px;
        }

        .admin-news-page .pagination {
            flex-wrap: wrap;
            gap: 4px;
            margin: 0;
        }

        .admin-news-page .pagination .page-link {
            min-width: 40px;
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--news-line-soft);
            border-radius: 9999px;
            background: var(--news-canvas);
            color: var(--news-ink);
        }

        .admin-news-page .pagination .page-item.active .page-link {
            border-color: var(--news-ink);
            background: var(--news-ink);
            color: #ffffff;
        }

        .admin-news-page .pagination .page-item.disabled .page-link {
            color: var(--news-muted);
        }

        .admin-news-page .empty-state {
            padding: 32px 20px;
            color: var(--news-muted);
            font-size: 15px;
            text-align: center;
        }

        .admin-news-page .empty-state a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            margin-top: 12px;
            padding: 9px 18px;
            border-radius: 9999px;
            background: var(--news-ink);
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }

        .admin-news-page .loading-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .82);
        }

        .admin-news-page .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--news-line-soft);
            border-top-color: var(--news-ink);
            border-radius: 50%;
            animation: news-spinner 800ms linear infinite;
        }

        @keyframes news-spinner {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 900px) {
            .admin-news-page .stats-row {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .admin-news-page .stats-item:nth-child(2) {
                border-right: 0;
            }

            .admin-news-page .stats-item:nth-child(-n + 2) {
                border-bottom: 1px solid var(--news-line-soft);
            }
        }

        @media (max-width: 640px) {
            .admin-news-page .admin-header {
                grid-template-columns: minmax(0, 1fr);
                align-items: stretch;
                margin-bottom: 24px;
            }

            .admin-news-page .admin-header-actions {
                width: 100%;
            }

            .admin-news-page .admin-header-actions .btn-admin {
                flex: 1 1 auto;
                white-space: normal;
            }

            .admin-news-page .stats-item {
                padding: 15px 14px;
            }

            .admin-news-page .table-header {
                align-items: stretch;
                padding: 16px;
            }

            .admin-news-page .table-filters {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
            }

            .admin-news-page .filter-control {
                width: 100%;
            }

            .admin-news-page .pagination-wrapper {
                padding: 14px 16px;
            }
        }

        @media (max-width: 420px) {
            .admin-news-page .admin-header-actions {
                flex-direction: column;
            }

            .admin-news-page .stats-number {
                font-size: 24px;
            }

            .admin-news-page .stats-label {
                font-size: 13px;
            }

            .admin-news-page .news-table thead th,
            .admin-news-page .news-table tbody td {
                padding-right: 12px;
                padding-left: 12px;
            }

            .admin-news-page .action-buttons {
                gap: 4px;
            }

            .admin-news-page .btn-action {
                width: 36px;
                height: 36px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-news-page .btn-admin,
            .admin-news-page .btn-action {
                transition: none;
            }

            .admin-news-page .loading-spinner {
                animation: none;
            }
        }
    </style>
@endsection

@section('content')
    <main class="admin-news-page">
        @if(session('success'))
            <div class="alert alert-success alert-admin" role="status" aria-live="polite">
                <i class="bi bi-check-circle" aria-hidden="true"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-admin" role="alert">
                <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>{{ session('error') }}
            </div>
        @endif

        <header class="admin-header">
            <div class="admin-header-info">
                <h1 class="admin-header-title"><i class="bi bi-newspaper" aria-hidden="true"></i> Manajemen Berita</h1>
                <p class="admin-header-subtitle">Kelola artikel, status publikasi, dan berita unggulan.</p>
            </div>

            <div class="admin-header-actions">
                <a href="{{ route('news.index') }}" target="_blank" rel="noopener"
                    class="btn-admin btn-admin-secondary">
                    <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i> Lihat halaman publik
                </a>
                <a href="{{ route('admin.news.create') }}" class="btn-admin btn-admin-primary">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i> Tulis artikel baru
                </a>
            </div>
        </header>

        <section class="stats-row" aria-label="Ringkasan artikel">
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
        </section>

        <section class="admin-table" aria-labelledby="news-list-title">
            <div class="table-header">
                <h2 class="table-title" id="news-list-title">Daftar artikel</h2>
                <div class="table-filters" role="group" aria-label="Filter artikel">
                    <label class="visually-hidden" for="filterStatus">Filter berdasarkan status</label>
                    <select class="filter-control" id="filterStatus">
                        <option value="">Semua status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                    <label class="visually-hidden" for="filterCategory">Filter berdasarkan kategori</label>
                    <select class="filter-control" id="filterCategory">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover news-table">
                    <thead>
                        <tr>
                            <th scope="col" width="50">#</th>
                            <th scope="col">Judul</th>
                            <th scope="col" width="120">Kategori</th>
                            <th scope="col" width="100">Status</th>
                            <th scope="col" width="100">Unggulan</th>
                            <th scope="col" width="100">Views</th>
                            <th scope="col" width="110">Terbit</th>
                            <th scope="col" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                            <tr>
                                <td class="number-cell">{{ ($articles->currentPage() - 1) * $articles->perPage() + $loop->iteration }}</td>
                                <td>
                                    <div class="article-title">{{ Str::limit($article->title, 60) }}</div>
                                    <div class="article-meta">oleh {{ $article->author ?? 'Admin' }}</div>
                                </td>
                                <td><span class="category-label">{{ $article->category }}</span></td>
                                <td>
                                    @if($article->is_active)
                                        <span class="status-badge status-active">
                                            <i class="bi bi-check-circle" aria-hidden="true"></i> Aktif
                                        </span>
                                    @else
                                        <span class="status-badge status-inactive">
                                            <i class="bi bi-x-circle" aria-hidden="true"></i> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->is_featured)
                                        <span class="status-badge status-featured">
                                            <i class="bi bi-star" aria-hidden="true"></i> Unggulan
                                        </span>
                                    @else
                                        <span class="text-secondary">Tidak</span>
                                    @endif
                                </td>
                                <td class="number-cell">{{ number_format($article->views_count) }}</td>
                                <td>{{ $article->published_at->timezone(\App\Support\BusinessTime::TIMEZONE)->format('d M Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('news.show', $article->id) }}" target="_blank" rel="noopener"
                                            class="btn-action btn-action-view" aria-label="Lihat {{ $article->title }}"
                                            title="Lihat artikel" data-bs-toggle="tooltip">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('admin.news.edit', $article->id) }}"
                                            class="btn-action btn-action-edit" aria-label="Edit {{ $article->title }}"
                                            title="Edit artikel" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>
                                        </a>
                                        <form action="{{ route('admin.news.destroy', $article->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirmDelete(event, '{{ addslashes($article->title) }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete"
                                                aria-label="Hapus {{ $article->title }}" title="Hapus artikel"
                                                data-bs-toggle="tooltip">
                                                <i class="bi bi-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        Belum ada artikel.
                                        <a href="{{ route('admin.news.create') }}">Tulis artikel pertama</a>
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
                    <div>{{ $articles->links() }}</div>
                </div>
            @endif
        </section>

        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-spinner" role="status" aria-label="Memuat"></div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
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
                    form.addEventListener('submit', function (event) {
                        if (event.defaultPrevented) return;
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
