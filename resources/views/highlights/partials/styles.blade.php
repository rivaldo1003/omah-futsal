<style>
/* =========================================================
   HIGHLIGHTS INDEX — Page-scoped styles
   ULTRA ANALYTICS ENGINE v3.0 — dark neon telemetry theme
   ========================================================= */

/* ---- Page Header ---- */
.highlights-page-header {
    background: transparent;
    border-bottom: 1px solid var(--v3-border);
    padding: 20px 0;
}

.highlights-page-title {
    font-size: 1.6rem;
    font-weight: 900;
    font-style: italic;
    text-transform: uppercase;
    letter-spacing: -0.03em;
    color: var(--v3-text-main);
    text-shadow: 0 0 20px rgba(0, 255, 135, 0.35);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.highlights-page-title i {
    color: var(--v3-neon-green);
}

.highlights-page-subtitle {
    font-size: 0.85rem;
    color: var(--v3-text-sub);
    margin: 0;
}

/* ---- Highlight Card ---- */
.highlight-card {
    border: 1px solid var(--v3-border);
    border-radius: var(--v3-radius-sm, 8px);
    overflow: hidden;
    background: var(--v3-card);
    height: 100%;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
    display: flex;
    flex-direction: column;
}

.highlight-card:hover {
    transform: translateY(-3px);
    border-color: var(--v3-border-glow);
    box-shadow: 0 0 24px rgba(0, 255, 135, 0.12);
}

/* ---- Thumbnail ---- */
.highlight-thumbnail {
    position: relative;
    overflow: hidden;
    height: 190px;
    background: var(--v3-surface);
    flex-shrink: 0;
}

.highlight-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
    display: block;
}

.highlight-card:hover .highlight-thumbnail img {
    transform: scale(1.04);
}

.highlight-thumbnail-placeholder {
    width: 100%;
    height: 100%;
    background: var(--v3-surface);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--v3-neon-pink);
    font-size: 3.5rem;
}

/* Play overlay */
.play-overlay {
    position: absolute;
    inset: 0;
    background: rgba(2, 4, 8, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.25s;
}

.highlight-card:hover .play-overlay {
    opacity: 1;
}

.play-btn-circle {
    width: 56px;
    height: 56px;
    background: rgba(0, 255, 135, 0.92);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000;
    font-size: 1.4rem;
    transform: scale(0.85);
    transition: transform 0.2s;
    box-shadow: 0 0 20px rgba(0, 255, 135, 0.5);
}

.highlight-card:hover .play-btn-circle {
    transform: scale(1);
}

/* YouTube badge */
.yt-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: var(--v3-neon-pink);
    color: #fff;
    font-size: 0.68rem;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 3px;
    display: flex;
    align-items: center;
    gap: 4px;
    z-index: 5;
    text-transform: uppercase;
}

/* Duration badge */
.duration-badge {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: rgba(2, 4, 8, 0.8);
    border: 1px solid var(--v3-border);
    color: var(--v3-text-main);
    font-size: 0.72rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 3px;
}

/* ---- Card Info ---- */
.highlight-info {
    padding: 14px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.highlight-match-title {
    font-size: 0.9rem;
    font-weight: 800;
    color: var(--v3-text-main);
    margin-bottom: 4px;
    line-height: 1.3;
}

.highlight-match-score {
    color: var(--v3-neon-green);
    font-weight: 900;
    font-style: italic;
    margin-right: 6px;
    font-size: 0.95rem;
}

.highlight-match-meta {
    font-size: 0.75rem;
    color: var(--v3-text-muted);
    margin-bottom: 10px;
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.highlight-match-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.highlight-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 10px;
    border-top: 1px solid var(--v3-border);
    margin-top: auto;
}

.highlight-uploaded-time {
    font-size: 0.72rem;
    color: var(--v3-text-muted);
    display: flex;
    align-items: center;
    gap: 3px;
}

.highlight-watch-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: var(--v3-neon-pink);
    color: #fff;
    font-size: 0.72rem;
    font-weight: 800;
    padding: 3px 9px;
    border-radius: 3px;
    text-transform: uppercase;
}

/* ---- Empty State ---- */
.highlights-empty {
    text-align: center;
    padding: 60px 24px;
    color: var(--v3-text-sub);
}

.highlights-empty i {
    font-size: 3rem;
    color: var(--v3-neon-pink);
    opacity: 0.6;
    display: block;
    margin-bottom: 16px;
}

.highlights-empty h4 {
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--v3-text-main);
    margin-bottom: 6px;
}

.highlights-empty p {
    font-size: 0.85rem;
    color: var(--v3-text-muted);
    margin-bottom: 20px;
}

/* ---- Pagination ---- */
.highlights-pagination {
    margin-top: 24px;
}

.highlights-pagination .pagination {
    justify-content: center;
    gap: 3px;
}

.highlights-pagination .page-link {
    border-radius: 4px;
    padding: 5px 10px;
    font-size: 0.82rem;
    border-color: var(--v3-border);
    color: var(--v3-text-sub);
    background-color: var(--v3-card);
}

.highlights-pagination .page-link:hover {
    border-color: var(--v3-border-glow);
    color: var(--v3-neon-green);
    background-color: var(--v3-surface);
}

.highlights-pagination .page-item.active .page-link {
    background-color: var(--v3-neon-green);
    border-color: var(--v3-neon-green);
    color: #000;
    font-weight: 800;
}

.highlights-pagination .page-item.disabled .page-link {
    color: var(--v3-text-muted);
    background-color: var(--v3-surface);
}

/* ---- Video Modal ---- */
.modal-content {
    border-radius: var(--v3-radius-sm, 8px);
    overflow: hidden;
    background-color: var(--v3-surface);
    border: 1px solid var(--v3-border);
    color: var(--v3-text-main);
}

.modal-header {
    padding: 12px 16px;
    border-bottom: 1px solid var(--v3-border);
}

.modal-title {
    font-size: 0.95rem;
    font-weight: 800;
    font-style: italic;
    text-transform: uppercase;
    color: var(--v3-text-main);
}

.modal-body.modal-video-body {
    padding: 0;
}

.modal-match-details {
    padding: 16px;
}

.modal-detail-label {
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--v3-text-muted);
    letter-spacing: 1.5px;
    display: block;
    margin-bottom: 2px;
}

.modal-detail-value {
    font-size: 0.85rem;
    color: var(--v3-text-main);
}

/* ---- Responsive ---- */
@media (max-width: 576px) {
    .highlights-page-title {
        font-size: 1.2rem;
    }

    .highlight-thumbnail {
        height: 160px;
    }
}
</style>