<style>
/* =========================================================
   NEWS SHOW — Page-scoped styles
   ULTRA ANALYTICS ENGINE v3.0 — dark neon telemetry theme
   ========================================================= */

/* ---- Breadcrumb ---- */
.article-breadcrumb-bar {
    background: transparent;
    border-bottom: 1px solid var(--v3-border);
    padding: 10px 0;
}

.article-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: var(--v3-text-muted);
    flex-wrap: wrap;
}

.article-breadcrumb a {
    color: var(--v3-neon-green);
    text-decoration: none;
    font-weight: 700;
}

.article-breadcrumb a:hover {
    text-shadow: 0 0 12px rgba(0, 255, 135, 0.5);
}

.article-breadcrumb-sep {
    color: var(--v3-text-muted);
    font-size: 0.7rem;
}

.article-breadcrumb-current {
    color: var(--v3-text-sub);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 260px;
}

/* ---- Article Header ---- */
.article-header {
    padding: 24px 0 20px;
    border-bottom: 1px solid var(--v3-border);
}

.article-category-tag {
    display: inline-flex;
    align-items: center;
    font-size: 0.72rem;
    font-weight: 800;
    color: var(--v3-neon-green);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 10px;
    text-decoration: none;
    gap: 4px;
}

.article-category-tag:hover {
    text-shadow: 0 0 12px rgba(0, 255, 135, 0.5);
}

.article-title {
    font-size: 1.6rem;
    font-weight: 900;
    font-style: italic;
    text-transform: uppercase;
    letter-spacing: -0.03em;
    color: var(--v3-text-main);
    text-shadow: 0 0 20px rgba(0, 255, 135, 0.35);
    line-height: 1.3;
    margin-bottom: 14px;
}

.article-meta-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 14px;
    font-size: 0.78rem;
    color: var(--v3-text-muted);
}

.article-meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
}

.article-featured-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(255, 183, 0, 0.12);
    color: var(--v3-neon-yellow);
    font-size: 0.72rem;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 3px;
    border: 1px solid rgba(255, 183, 0, 0.3);
    text-transform: uppercase;
}

/* ---- Article Hero Image ---- */
.article-hero-image-wrap {
    margin-bottom: 28px;
}

.article-hero-image {
    width: 100%;
    height: 320px;
    object-fit: cover;
    border-radius: var(--v3-radius-md, 12px);
    border: 1px solid var(--v3-border);
    display: block;
}

.article-hero-image-placeholder {
    width: 100%;
    height: 280px;
    background: var(--v3-card);
    border: 1px solid var(--v3-border);
    border-radius: var(--v3-radius-md, 12px);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--v3-text-muted);
    font-size: 3rem;
}

/* ---- Article Body Content ---- */
.article-body {
    font-size: 0.95rem;
    line-height: 1.75;
    color: var(--v3-text-sub);
}

.article-body h2 {
    font-size: 1.25rem;
    font-weight: 900;
    font-style: italic;
    text-transform: uppercase;
    margin: 28px 0 12px;
    color: var(--v3-text-main);
}

.article-body h3 {
    font-size: 1.05rem;
    font-weight: 800;
    margin: 20px 0 8px;
    color: var(--v3-text-main);
}

.article-body p {
    margin-bottom: 16px;
}

.article-body img {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    border: 1px solid var(--v3-border);
    margin: 20px 0;
}

.article-body blockquote {
    border-left: 3px solid var(--v3-neon-green);
    padding: 12px 16px;
    margin: 20px 0;
    background: var(--v3-card);
    border-radius: 0 6px 6px 0;
    font-style: italic;
    color: var(--v3-text-sub);
}

.article-body ul,
.article-body ol {
    margin-bottom: 16px;
    padding-left: 20px;
}

.article-body li {
    margin-bottom: 4px;
}

/* ---- Source Alert ---- */
.article-source-alert {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    background: rgba(0, 229, 255, 0.06);
    border: 1px solid rgba(0, 229, 255, 0.25);
    border-radius: 6px;
    padding: 10px 14px;
    font-size: 0.82rem;
    color: var(--v3-neon-blue);
    margin-top: 20px;
}

.article-source-alert a {
    color: var(--v3-neon-blue);
    font-weight: 700;
    word-break: break-all;
}

/* ---- Share + Actions bar ---- */
.article-action-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 14px 0;
    margin: 20px 0;
    border-top: 1px solid var(--v3-border);
    border-bottom: 1px solid var(--v3-border);
}

.share-label {
    font-size: 0.8rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--v3-text-muted);
}

.share-buttons {
    display: flex;
    gap: 6px;
    align-items: center;
}

.share-btn {
    width: 34px;
    height: 34px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 0.9rem;
    transition: transform 0.15s, opacity 0.15s;
}

.share-btn:hover {
    transform: translateY(-2px);
    opacity: 0.9;
}

.share-facebook  { background: #1877f2; color: #fff; }
.share-twitter   { background: #1da1f2; color: #fff; }
.share-whatsapp  { background: #25d366; color: #fff; }
.share-telegram  { background: #0088cc; color: #fff; }

.article-publish-time {
    font-size: 0.78rem;
    color: var(--v3-text-muted);
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ---- Back link ---- */
.article-back-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: var(--v3-neon-green);
    font-size: 0.82rem;
    font-weight: 800;
    text-decoration: none;
    margin-bottom: 20px;
    transition: gap 0.15s;
}

.article-back-link:hover {
    gap: 8px;
    color: var(--v3-neon-green);
    text-shadow: 0 0 12px rgba(0, 255, 135, 0.5);
}

/* ---- Related News ---- */
.related-section-title {
    font-size: 1rem;
    font-weight: 900;
    font-style: italic;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--v3-text-main);
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.related-section-title i {
    color: var(--v3-neon-green);
}

.related-card {
    border: 1px solid var(--v3-border);
    border-radius: var(--v3-radius-sm, 8px);
    overflow: hidden;
    background: var(--v3-card);
    height: 100%;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
    display: flex;
    flex-direction: column;
}

.related-card:hover {
    border-color: var(--v3-border-glow);
    box-shadow: 0 0 20px rgba(0, 255, 135, 0.12);
    transform: translateY(-2px);
}

.related-card-image-wrap {
    height: 140px;
    background: var(--v3-surface);
    overflow: hidden;
    flex-shrink: 0;
}

.related-card-image-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.related-card:hover .related-card-image-wrap img {
    transform: scale(1.04);
}

.related-card-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--v3-text-muted);
    font-size: 1.8rem;
}

.related-card-body {
    padding: 12px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.related-card-cat {
    font-size: 0.68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--v3-neon-green);
    margin-bottom: 5px;
}

.related-card-title {
    font-size: 0.85rem;
    font-weight: 800;
    color: var(--v3-text-main);
    line-height: 1.3;
    margin-bottom: 8px;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.related-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.72rem;
    color: var(--v3-text-muted);
    margin-bottom: 10px;
}

/* ---- Sidebar (reuses news-sidebar-* from index styles) ---- */

/* ---- FAB group ---- */
.article-fab-group {
    position: fixed;
    bottom: 20px;
    right: 20px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    z-index: 1050;
}

.article-fab {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
    transition: transform 0.15s, box-shadow 0.15s;
    text-decoration: none;
    font-size: 0.9rem;
    color: #fff;
}

.article-fab:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.5);
    color: #fff;
}

.article-fab-scroll { background: var(--v3-neon-green); color: #000; box-shadow: 0 0 12px rgba(0, 255, 135, 0.4); }
.article-fab-print  { background: var(--v3-card); border: 1px solid var(--v3-border); color: var(--v3-text-sub); }
.article-fab-copy   { background: var(--v3-neon-blue); color: #000; }

/* ---- Responsive ---- */
@media (min-width: 768px) {
    .article-title {
        font-size: 2rem;
    }

    .article-hero-image {
        height: 380px;
    }

    .article-body {
        font-size: 1rem;
    }
}

@media (min-width: 992px) {
    .article-title {
        font-size: 2.2rem;
    }

    .article-hero-image {
        height: 420px;
    }

    .article-body {
        font-size: 1.02rem;
        line-height: 1.8;
    }
}

@media (max-width: 576px) {
    .article-title {
        font-size: 1.3rem;
    }

    .article-hero-image {
        height: 200px;
    }

    .article-fab-group {
        bottom: 12px;
        right: 12px;
    }

    .article-fab {
        width: 34px;
        height: 34px;
        font-size: 0.8rem;
    }
}
</style>