<style>
/* =========================================================
   NEWS INDEX — Page-scoped styles
   Design tokens inherited from home/partials/styles.blade.php
   ========================================================= */

/* ---- Page Header ---- */
.news-page-header {
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
    padding: 20px 0 0;
}

.news-page-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.news-page-subtitle {
    font-size: 0.85rem;
    color: #64748b;
    margin-bottom: 16px;
}

/* ---- Category Filter Tabs ---- */
.news-filter-bar {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
    padding-bottom: 0;
    border-bottom: 2px solid #e2e8f0;
}

.news-filter-tab {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 14px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    border-radius: 4px 4px 0 0;
    border: 1px solid transparent;
    border-bottom: none;
    margin-bottom: -2px;
    transition: color 0.15s, background 0.15s;
}

.news-filter-tab:hover {
    color: #1a5fb4;
    background: #f1f5f9;
}

.news-filter-tab.active {
    color: #1a5fb4;
    background: #fff;
    border-color: #e2e8f0 #e2e8f0 #fff;
    border-bottom: 2px solid #fff;
}

.news-filter-tab .tab-count {
    background: #e2e8f0;
    color: #475569;
    font-size: 0.7rem;
    padding: 1px 5px;
    border-radius: 8px;
    font-weight: 700;
}

.news-filter-tab.active .tab-count {
    background: #dbeafe;
    color: #1a5fb4;
}

/* ---- Featured Article Card ---- */
.news-featured-card {
    display: flex;
    gap: 0;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    margin-bottom: 20px;
}

.news-featured-image-wrap {
    flex: 0 0 45%;
    position: relative;
    min-height: 180px;
    background: #f1f5f9;
    overflow: hidden;
}

.news-featured-image-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.news-featured-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 2.5rem;
}

.news-featured-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: #1a5fb4;
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 3px;
    letter-spacing: 0.4px;
    text-transform: uppercase;
}

.news-featured-body {
    flex: 1;
    padding: 16px;
    display: flex;
    flex-direction: column;
}

.news-featured-category {
    font-size: 0.72rem;
    font-weight: 700;
    color: #1a5fb4;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}

.news-featured-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.35;
    margin-bottom: 8px;
}

.news-featured-excerpt {
    font-size: 0.82rem;
    color: #64748b;
    line-height: 1.5;
    flex: 1;
    margin-bottom: 12px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.news-featured-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.75rem;
    color: #94a3b8;
    margin-bottom: 12px;
}

/* ---- Article Grid ---- */
.news-grid-heading {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.news-article-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: box-shadow 0.2s, transform 0.2s;
}

.news-article-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.news-article-image-wrap {
    position: relative;
    height: 160px;
    background: #f1f5f9;
    overflow: hidden;
    flex-shrink: 0;
}

.news-article-image-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.news-article-card:hover .news-article-image-wrap img {
    transform: scale(1.03);
}

.news-article-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 2rem;
}

.news-article-cat-tag {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(26, 95, 180, 0.9);
    color: #fff;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 3px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.news-article-feat-tag {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #f59e0b;
    color: #fff;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 3px;
    text-transform: uppercase;
}

.news-article-body {
    padding: 12px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.news-article-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.35;
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.news-article-excerpt {
    font-size: 0.78rem;
    color: #64748b;
    line-height: 1.45;
    flex: 1;
    margin-bottom: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.news-article-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 8px;
    border-top: 1px solid #f1f5f9;
}

.news-article-source {
    font-size: 0.72rem;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 3px;
}

.news-article-date {
    font-size: 0.72rem;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 3px;
}

.news-read-more {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #1a5fb4;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    margin-top: 8px;
    transition: gap 0.15s;
}

.news-read-more:hover {
    gap: 6px;
    color: #1248a0;
}

/* ---- Sidebar ---- */
.news-sidebar-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    overflow: hidden;
    margin-bottom: 16px;
}

.news-sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-size: 0.85rem;
    font-weight: 700;
    color: #334155;
}

.news-sidebar-header-left {
    display: flex;
    align-items: center;
    gap: 6px;
}

.news-sidebar-item {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    padding: 10px 14px;
    border-bottom: 1px solid #f1f5f9;
    text-decoration: none;
    color: inherit;
    transition: background 0.15s;
}

.news-sidebar-item:last-child {
    border-bottom: none;
}

.news-sidebar-item:hover {
    background: #f8fafc;
}

.news-sidebar-thumbnail {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    border-radius: 4px;
    overflow: hidden;
    background: #f1f5f9;
}

.news-sidebar-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.news-sidebar-info {
    flex: 1;
    min-width: 0;
}

.news-sidebar-title {
    font-size: 0.8rem;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.3;
    margin-bottom: 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.news-sidebar-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 4px;
}

.news-sidebar-cat {
    font-size: 0.68rem;
    font-weight: 600;
    padding: 1px 5px;
    border-radius: 3px;
    background: #e2e8f0;
    color: #475569;
    white-space: nowrap;
}

.news-sidebar-time {
    font-size: 0.68rem;
    color: #94a3b8;
    white-space: nowrap;
}

/* Categories widget */
.news-cat-pill {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    transition: background 0.15s, color 0.15s;
}

.news-cat-pill:hover,
.news-cat-pill.active {
    background: #dbeafe;
    color: #1a5fb4;
    border-color: #bfdbfe;
}

/* Popular — views badge */
.news-views-badge {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 3px;
    background: #fef3c7;
    color: #92400e;
    white-space: nowrap;
}

/* ---- Empty State ---- */
.news-empty-state {
    text-align: center;
    padding: 48px 24px;
    color: #64748b;
}

.news-empty-state i {
    font-size: 2.4rem;
    color: #cbd5e1;
    margin-bottom: 12px;
    display: block;
}

.news-empty-state h5 {
    font-size: 1rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 4px;
}

.news-empty-state p {
    font-size: 0.82rem;
    color: #94a3b8;
    margin-bottom: 16px;
}

/* ---- Pagination wrapper ---- */
.news-pagination-wrap {
    margin-top: 20px;
}

.news-pagination-wrap .pagination {
    justify-content: center;
    gap: 3px;
}

.news-pagination-wrap .page-link {
    border-radius: 4px;
    padding: 5px 10px;
    font-size: 0.82rem;
    border-color: #e2e8f0;
    color: #475569;
}

.news-pagination-wrap .page-item.active .page-link {
    background-color: #1a5fb4;
    border-color: #1a5fb4;
    color: #fff;
}

.news-pagination-wrap .page-item.disabled .page-link {
    color: #cbd5e1;
}

/* ---- Responsive ---- */
@media (max-width: 768px) {
    .news-featured-card {
        flex-direction: column;
    }

    .news-featured-image-wrap {
        flex: none;
        height: 180px;
        width: 100%;
    }

    .news-page-title {
        font-size: 1.1rem;
    }
}

@media (max-width: 576px) {
    .news-filter-tab {
        padding: 5px 10px;
        font-size: 0.78rem;
    }

    .news-article-image-wrap {
        height: 140px;
    }
}
</style>
