<style>
/* =========================================================
   HIGHLIGHTS INDEX — Page-scoped styles
   ========================================================= */

/* ---- Page Header ---- */
.highlights-page-header {
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
    padding: 20px 0;
}

.highlights-page-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.highlights-page-subtitle {
    font-size: 0.85rem;
    color: #64748b;
    margin: 0;
}

/* ---- Highlight Card ---- */
.highlight-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    height: 100%;
    cursor: pointer;
    transition: box-shadow 0.2s, transform 0.2s;
    display: flex;
    flex-direction: column;
}

.highlight-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.09);
}

/* ---- Thumbnail ---- */
.highlight-thumbnail {
    position: relative;
    overflow: hidden;
    height: 190px;
    background: #111;
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
    background: #1a1a2e;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ef4444;
    font-size: 3.5rem;
}

/* Play overlay */
.play-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.32);
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
    background: rgba(26, 95, 180, 0.92);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.4rem;
    transform: scale(0.85);
    transition: transform 0.2s;
}

.highlight-card:hover .play-btn-circle {
    transform: scale(1);
}

/* YouTube badge */
.yt-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: #ef4444;
    color: #fff;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 3px;
    display: flex;
    align-items: center;
    gap: 4px;
    z-index: 5;
}

/* Duration badge */
.duration-badge {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: rgba(0, 0, 0, 0.72);
    color: #fff;
    font-size: 0.72rem;
    font-weight: 600;
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
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
    line-height: 1.3;
}

.highlight-match-score {
    color: #16a34a;
    font-weight: 800;
    margin-right: 6px;
    font-size: 0.95rem;
}

.highlight-match-meta {
    font-size: 0.75rem;
    color: #64748b;
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
    border-top: 1px solid #f1f5f9;
    margin-top: auto;
}

.highlight-uploaded-time {
    font-size: 0.72rem;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 3px;
}

.highlight-watch-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #ef4444;
    color: #fff;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 3px;
}

/* ---- Empty State ---- */
.highlights-empty {
    text-align: center;
    padding: 60px 24px;
    color: #64748b;
}

.highlights-empty i {
    font-size: 3rem;
    color: #ef4444;
    opacity: 0.5;
    display: block;
    margin-bottom: 16px;
}

.highlights-empty h4 {
    font-size: 1.05rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
}

.highlights-empty p {
    font-size: 0.85rem;
    color: #94a3b8;
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
    border-color: #e2e8f0;
    color: #475569;
}

.highlights-pagination .page-item.active .page-link {
    background-color: #1a5fb4;
    border-color: #1a5fb4;
    color: #fff;
}

.highlights-pagination .page-item.disabled .page-link {
    color: #cbd5e1;
}

/* ---- Video Modal ---- */
.modal-content {
    border-radius: 8px;
    overflow: hidden;
}

.modal-header {
    padding: 12px 16px;
    border-bottom: 1px solid #e2e8f0;
}

.modal-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}

.modal-body.modal-video-body {
    padding: 0;
}

.modal-match-details {
    padding: 16px;
}

.modal-detail-label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #94a3b8;
    letter-spacing: 0.5px;
    display: block;
    margin-bottom: 2px;
}

.modal-detail-value {
    font-size: 0.85rem;
    color: #334155;
}

/* ---- Responsive ---- */
@media (max-width: 576px) {
    .highlights-page-title {
        font-size: 1.1rem;
    }

    .highlight-thumbnail {
        height: 160px;
    }
}
</style>
