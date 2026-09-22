<style>
/* =========================================================
   NEWS INDEX — Page-scoped styles
   ULTRA ANALYTICS ENGINE v3.0 — dark neon telemetry theme
   Tokens inherited from home/partials/styles.blade.php
   ========================================================= */

/* ---- Page Header ---- */
.news-page-header {
    background: transparent;
    border-bottom: 1px solid var(--v3-border);
    padding: 20px 0 0;
}

.news-page-title {
    font-size: 1.6rem;
    font-weight: 900;
    font-style: italic;
    text-transform: uppercase;
    letter-spacing: -0.03em;
    color: var(--v3-text-main);
    text-shadow: 0 0 20px rgba(0, 255, 135, 0.35);
    margin-bottom: 4px;
}

.news-page-subtitle {
    font-size: 0.85rem;
    color: var(--v3-text-sub);
    margin-bottom: 16px;
}

/* ---- Category Filter Tabs ---- */
.news-filter-bar {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
    padding-bottom: 0;
    border-bottom: 2px solid var(--v3-border);
}

.news-filter-tab {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 14px;
    font-size: 0.82rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--v3-text-muted);
    text-decoration: none;
    border-radius: 4px 4px 0 0;
    border: 1px solid transparent;
    border-bottom: none;
    margin-bottom: -2px;
    transition: color 0.15s, background 0.15s;
}

.news-filter-tab:hover {
    color: var(--v3-neon-green);
    background: var(--v3-card);
}

.news-filter-tab.active {
    color: var(--v3-neon-green);
    background: var(--v3-surface);
    border-color: var(--v3-border) var(--v3-border) var(--v3-surface);
    border-bottom: 2px solid var(--v3-neon-green);
}

.news-filter-tab .tab-count {
    background: var(--v3-border);
    color: var(--v3-text-sub);
    font-size: 0.7rem;
    padding: 1px 5px;
    border-radius: 8px;
    font-weight: 700;
}

.news-filter-tab.active .tab-count {
    background: rgba(0, 255, 135, 0.15);
    color: var(--v3-neon-green);
}

/* ---- Featured Article Card ---- */
.news-featured-card {
    display: flex;
    gap: 0;
    border: 1px solid var(--v3-border);
    border-radius: var(--v3-radius-md, 12px);
    overflow: hidden;
    background: var(--v3-card);
    margin-bottom: 20px;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.news-featured-card:hover {
    border-color: var(--v3-border-glow);
    box-shadow: 0 0 24px rgba(0, 255, 135, 0.12);
}

.news-featured-image-wrap {
    flex: 0 0 45%;
    position: relative;
    min-height: 180px;
    background: var(--v3-surface);
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
    background: var(--v3-surface);
    color: var(--v3-text-muted);
    font-size: 2.5rem;
}

.news-featured-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: var(--v3-neon-green);
    color: #000;
    font-size: 0.7rem;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 3px;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    box-shadow: 0 0 12px rgba(0, 255, 135, 0.4);
}

.news-featured-body {
    flex: 1;
    padding: 16px;
    display: flex;
    flex-direction: column;
}

.news-featured-category {
    font-size: 0.72rem;
    font-weight: 800;
    color: var(--v3-neon-green);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 6px;
}

.news-featured-title {
    font-size: 1.05rem;
    font-weight: 800;
    font-style: italic;
    text-transform: uppercase;
    color: var(--v3-text-main);
    line-height: 1.35;
    margin-bottom: 8px;
}

.news-featured-excerpt {
    font-size: 0.82rem;
    color: var(--v3-text-sub);
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
    color: var(--v3-text-muted);
    margin-bottom: 12px;
}

/* ---- Article Grid ---- */
.news-grid-heading {
    font-size: 0.9rem;
    font-weight: 800;
    font-style: italic;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--v3-text-main);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.news-grid-heading i {
    color: var(--v3-neon-green);
}

.news-article-card {
    border: 1px solid var(--v3-border);
    border-radius: var(--v3-radius-sm, 8px);
    overflow: hidden;
    background: var(--v3-card);
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
}

.news-article-card:hover {
    border-color: var(--v3-border-glow);
    box-shadow: 0 0 20px rgba(0, 255, 135, 0.12);
    transform: translateY(-2px);
}

.news-article-image-wrap {
    position: relative;
    height: 160px;
    background: var(--v3-surface);
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
    color: var(--v3-text-muted);
    font-size: 2rem;
}

.news-article-cat-tag {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(0, 255, 135, 0.9);
    color: #000;
    font-size: 0.68rem;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 3px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.news-article-feat-tag {
    position: absolute;
    top: 8px;
    right: 8px;
    background: var(--v3-neon-yellow);
    color: #000;
    font-size: 0.68rem;
    font-weight: 800;
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
    font-weight: 800;
    color: var(--v3-text-main);
    line-height: 1.35;
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.news-article-excerpt {
    font-size: 0.78rem;
    color: var(--v3-text-sub);
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
    border-top: 1px solid var(--v3-border);
}

.news-article-source {
    font-size: 0.72rem;
    color: var(--v3-text-muted);
    display: flex;
    align-items: center;
    gap: 3px;
}

.news-article-date {
    font-size: 0.72rem;
    color: var(--v3-text-muted);
    display: flex;
    align-items: center;
    gap: 3px;
}

.news-read-more {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: var(--v3-neon-green);
    font-size: 0.8rem;
    font-weight: 800;
    text-decoration: none;
    margin-top: 8px;
    transition: gap 0.15s;
}

.news-read-more:hover {
    gap: 6px;
    color: var(--v3-neon-green);
    text-shadow: 0 0 12px rgba(0, 255, 135, 0.5);
}

/* ---- Sidebar ---- */
.news-sidebar-card {
    border: 1px solid var(--v3-border);
    border-radius: var(--v3-radius-sm, 8px);
    background: var(--v3-card);
    overflow: hidden;
    margin-bottom: 16px;
}

.news-sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    background: var(--v3-surface);
    border-bottom: 1px solid var(--v3-border);
    font-size: 0.85rem;
    font-weight: 800;
    font-style: italic;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--v3-text-main);
}

.news-sidebar-header i {
    color: var(--v3-neon-green);
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
    border-bottom: 1px solid var(--v3-border);
    text-decoration: none;
    color: inherit;
    transition: background 0.15s;
}

.news-sidebar-item:last-child {
    border-bottom: none;
}

.news-sidebar-item:hover {
    background: var(--v3-surface);
}

.news-sidebar-thumbnail {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    border-radius: 4px;
    overflow: hidden;
    background: var(--v3-surface);
    border: 1px solid var(--v3-border);
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
    font-weight: 700;
    color: var(--v3-text-main);
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
    font-weight: 800;
    padding: 1px 5px;
    border-radius: 3px;
    background: var(--v3-border);
    color: var(--v3-text-sub);
    white-space: nowrap;
    text-transform: uppercase;
}

.news-sidebar-time {
    font-size: 0.68rem;
    color: var(--v3-text-muted);
    white-space: nowrap;
}

/* Categories widget */
.news-cat-pill {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 700;
    text-decoration: none;
    background: var(--v3-surface);
    color: var(--v3-text-sub);
    border: 1px solid var(--v3-border);
    transition: background 0.15s, color 0.15s, border-color 0.15s;
}

.news-cat-pill:hover,
.news-cat-pill.active {
    background: rgba(0, 255, 135, 0.12);
    color: var(--v3-neon-green);
    border-color: var(--v3-border-glow);
}

/* Popular — views badge */
.news-views-badge {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 0.68rem;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 3px;
    background: rgba(255, 183, 0, 0.12);
    color: var(--v3-neon-yellow);
    white-space: nowrap;
}

/* ---- Empty State ---- */
.news-empty-state {
    text-align: center;
    padding: 48px 24px;
    color: var(--v3-text-sub);
}

.news-empty-state i {
    font-size: 2.4rem;
    color: var(--v3-border);
    margin-bottom: 12px;
    display: block;
}

.news-empty-state h5 {
    font-size: 1rem;
    font-weight: 800;
    color: var(--v3-text-main);
    margin-bottom: 4px;
}

.news-empty-state p {
    font-size: 0.82rem;
    color: var(--v3-text-muted);
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
    border-color: var(--v3-border);
    color: var(--v3-text-sub);
    background-color: var(--v3-card);
}

.news-pagination-wrap .page-link:hover {
    border-color: var(--v3-border-glow);
    color: var(--v3-neon-green);
    background-color: var(--v3-surface);
}

.news-pagination-wrap .page-item.active .page-link {
    background-color: var(--v3-neon-green);
    border-color: var(--v3-neon-green);
    color: #000;
    font-weight: 800;
}

.news-pagination-wrap .page-item.disabled .page-link {
    color: var(--v3-text-muted);
    background-color: var(--v3-surface);
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
        font-size: 1.2rem;
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