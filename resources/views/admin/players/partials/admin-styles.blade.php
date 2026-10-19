.admin-player-page {
    --player-ink: #111111;
    --player-charcoal: #39393b;
    --player-muted: #707072;
    --player-line: #cacacb;
    --player-line-soft: #e5e5e5;
    --player-cloud: #f5f5f5;
    --player-white: #ffffff;
    --player-success: #007d48;
    --player-danger: #b42318;
    max-width: 1440px;
    margin: 0 auto;
    color: var(--player-ink);
    font-size: 15px;
    line-height: 1.5;
}

.admin-player-page .page-header {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    gap: 20px 32px;
    margin-bottom: 24px;
}

.admin-player-page .page-heading {
    min-width: 0;
}

.admin-player-page .page-header h1 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 8px;
    color: var(--player-ink);
    font-size: clamp(1.75rem, 2.5vw, 2rem);
    font-weight: 500;
    line-height: 1.2;
    letter-spacing: -.02em;
}

.admin-player-page .page-header h1 i {
    font-size: .78em;
}

.admin-player-page .page-subtitle {
    max-width: 66ch;
    margin: 0;
    color: var(--player-muted);
    font-size: 15px;
}

.admin-player-page .player-header-actions,
.admin-player-page .pagination-controls,
.admin-player-page .action-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
}

.admin-player-page .player-header-actions {
    display: grid !important;
    grid-template-columns: minmax(220px, 300px) max-content max-content;
    flex: none;
    justify-content: flex-end;
    gap: 8px !important;
    white-space: nowrap;
}

.admin-player-page .player-header-actions > form {
    margin: 0;
}

.admin-player-page .search-box {
    position: relative;
    width: min(300px, 100%);
}

.admin-player-page .search-icon {
    position: absolute;
    top: 50%;
    left: 16px;
    color: var(--player-muted);
    font-size: 14px;
    pointer-events: none;
    transform: translateY(-50%);
}

.admin-player-page .search-box .form-control {
    min-height: 44px;
    padding: 9px 44px 9px 42px;
    border-radius: 9999px;
    background: var(--player-cloud);
}

.admin-player-page .clear-search-btn {
    position: absolute;
    top: 50%;
    right: 8px;
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    border: 0;
    border-radius: 50%;
    background: transparent;
    color: var(--player-muted);
    cursor: pointer;
    transform: translateY(-50%);
}

.admin-player-page .clear-search-btn:hover {
    background: var(--player-white);
    color: var(--player-ink);
}

.admin-player-page .btn-create,
.admin-player-page .btn-back,
.admin-player-page .player-detail-actions .btn,
.admin-player-page .player-header-actions .btn,
.admin-player-page .pagination-controls .btn,
.admin-player-page .player-form-actions .btn {
    min-height: 48px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 22px;
    border: 1px solid transparent;
    border-radius: 9999px;
    font-size: 14px;
    font-weight: 500;
    line-height: 1.4;
    text-decoration: none;
    white-space: nowrap;
    box-shadow: none;
    transition: background-color 150ms ease-out, border-color 150ms ease-out, color 150ms ease-out;
}

.admin-player-page .btn-create,
.admin-player-page .player-detail-actions .btn-primary,
.admin-player-page .player-header-actions .btn-primary,
.admin-player-page .player-form-actions .btn-primary {
    border-color: var(--player-ink);
    background: var(--player-ink);
    color: var(--player-white);
}

.admin-player-page .btn-create:hover,
.admin-player-page .player-detail-actions .btn-primary:hover,
.admin-player-page .player-header-actions .btn-primary:hover,
.admin-player-page .player-form-actions .btn-primary:hover {
    border-color: var(--player-charcoal);
    background: var(--player-charcoal);
    color: var(--player-white);
}

.admin-player-page .player-header-actions .btn-outline-secondary,
.admin-player-page .pagination-controls .btn,
.admin-player-page .player-form-actions .btn-outline-secondary,
.admin-player-page .btn-back {
    background: var(--player-cloud);
    color: var(--player-ink);
}

.admin-player-page .player-header-actions .btn-outline-secondary:hover,
.admin-player-page .pagination-controls a.btn:hover,
.admin-player-page .player-form-actions .btn-outline-secondary:hover,
.admin-player-page .btn-back:hover {
    background: #eeeeee;
    color: var(--player-ink);
}

.admin-player-page .btn-sm {
    min-height: 40px !important;
    padding: 8px 16px !important;
    font-size: 13px !important;
}

.admin-player-page .main-card,
.admin-player-page .form-card {
    overflow: hidden;
    border: 1px solid var(--player-line-soft);
    border-radius: 8px;
    background: var(--player-white);
    box-shadow: none;
}

.admin-player-page .main-card .card-header,
.admin-player-page .form-card .card-header {
    padding: 18px 20px;
    border-bottom: 1px solid var(--player-line-soft);
    background: var(--player-white);
}

.admin-player-page .main-card .card-header h5,
.admin-player-page .form-card .card-header h5 {
    margin: 0;
    color: var(--player-ink);
    font-size: 16px;
    font-weight: 500;
    line-height: 1.4;
}

.admin-player-page .form-card .card-body {
    padding: 20px;
}

.admin-player-page .table-responsive {
    width: 100%;
}

.admin-player-page .players-table {
    width: 100%;
    min-width: 1200px;
    margin: 0;
    color: var(--player-charcoal);
    font-size: 14px;
    table-layout: fixed;
    --bs-table-bg: transparent;
    --bs-table-hover-bg: var(--player-cloud);
}

.admin-player-page .players-table th:nth-child(1),
.admin-player-page .players-table td:nth-child(1) { width: 31%; }

.admin-player-page .players-table th:nth-child(2),
.admin-player-page .players-table td:nth-child(2) { width: 18%; }

.admin-player-page .players-table th:nth-child(3),
.admin-player-page .players-table td:nth-child(3) { width: 14%; }

.admin-player-page .players-table th:nth-child(4),
.admin-player-page .players-table td:nth-child(4) { width: 21%; }

.admin-player-page .players-table th:nth-child(5),
.admin-player-page .players-table td:nth-child(5) { width: 16%; }

.admin-player-page .players-table > :not(caption) > * > * {
    padding: 14px 16px;
    border-bottom-color: var(--player-line-soft);
    vertical-align: middle;
}

.admin-player-page .players-table thead th {
    background: var(--player-white);
    color: var(--player-muted);
    font-size: 12px;
    font-weight: 500;
    letter-spacing: .025em;
    white-space: nowrap;
}

.admin-player-page .players-table tbody tr:last-child > * {
    border-bottom: 0;
}

.admin-player-page .player-photo,
.admin-player-page .player-photo-preview {
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    overflow: hidden;
    border: 1px solid var(--player-line-soft);
    border-radius: 50%;
    background: var(--player-cloud);
}

.admin-player-page .player-identity {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
}

.admin-player-page .player-identity-copy {
    min-width: 0;
}

.admin-player-page .player-photo img,
.admin-player-page .player-photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.admin-player-page .player-photo-fallback,
.admin-player-page .player-photo-fallback-edit {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--player-cloud);
    color: var(--player-muted);
    font-size: 13px;
    font-weight: 500;
}

.admin-player-page .player-photo-preview {
    width: 160px !important;
    height: 160px !important;
    flex-basis: 160px;
    margin-bottom: 20px;
}

.admin-player-page .player-name {
    color: var(--player-ink);
    font-size: 14px;
    font-weight: 500;
}

.admin-player-page .player-details,
.admin-player-page .pagination-info {
    color: var(--player-muted);
    font-size: 12px;
}

.admin-player-page .player-details .badge {
    border-color: var(--player-line-soft) !important;
    border-radius: 9999px;
    background: var(--player-cloud) !important;
    color: var(--player-charcoal) !important;
    font-size: 11px !important;
    font-weight: 400 !important;
}

.admin-player-page .team-badge,
.admin-player-page .position-badge,
.admin-player-page .team-initial {
    display: inline-flex;
    align-items: center;
    border: 1px solid var(--player-line-soft);
    background: var(--player-cloud);
    color: var(--player-charcoal);
}

.admin-player-page .team-badge,
.admin-player-page .position-badge {
    max-width: 240px;
    gap: 7px;
    padding: 5px 10px;
    border-radius: 9999px;
    font-size: 12px;
    line-height: 1.3;
}

.admin-player-page .team-badge {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.admin-player-page .team-initial {
    width: 22px;
    height: 22px;
    flex: 0 0 22px;
    justify-content: center;
    border-radius: 50%;
    font-size: 11px;
    font-weight: 500;
}

.admin-player-page .position-badge {
    font-weight: 400;
}

.admin-player-page .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(42px, 1fr));
    gap: 8px;
    min-width: 220px;
}

.admin-player-page .stat-item {
    min-width: 42px;
    text-align: center;
}

.admin-player-page .stat-value {
    color: var(--player-ink);
    font-size: 14px;
    font-weight: 500;
    line-height: 1.3;
}

.admin-player-page .stat-label {
    color: var(--player-muted);
    font-size: 11px;
}

.admin-player-page .action-buttons {
    justify-content: flex-end;
    gap: 6px;
}

.admin-player-page .action-buttons form {
    display: inline-flex;
    margin: 0;
}

.admin-player-page .btn-action {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    border: 1px solid var(--player-line-soft);
    border-radius: 50%;
    background: var(--player-white);
    color: var(--player-charcoal);
    font-size: 15px;
    text-decoration: none;
    transition: background-color 150ms ease-out, color 150ms ease-out;
}

.admin-player-page .btn-action:hover {
    background: var(--player-cloud);
    color: var(--player-ink);
}

.admin-player-page .btn-action-danger:hover {
    border-color: #e8c8c5;
    background: #fdf1f0;
    color: var(--player-danger);
}

.admin-player-page .pagination-container {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px 20px;
    padding: 16px 20px;
    border-top: 1px solid var(--player-line-soft);
}

.admin-player-page .pagination-info {
    font-size: 13px;
}

.admin-player-page .pagination-controls .btn.disabled {
    opacity: .5;
}

.admin-player-page .empty-state {
    padding: 56px 20px;
    text-align: center;
}

.admin-player-page .empty-state-icon {
    margin-bottom: 12px;
    color: var(--player-muted);
    font-size: 28px;
}

.admin-player-page .empty-state-title {
    margin: 0 0 6px;
    color: var(--player-ink);
    font-size: 18px;
    font-weight: 500;
}

.admin-player-page .empty-state-text {
    max-width: 52ch;
    margin: 0 auto 20px;
    color: var(--player-muted);
    font-size: 14px;
}

.admin-player-page .form-section-title {
    margin: 28px 0 16px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--player-line-soft);
    color: var(--player-ink);
    font-size: 15px;
    font-weight: 500;
    line-height: 1.4;
}

.admin-player-page .form-card .card-body > .form-section-title:first-child {
    margin-top: 0;
}

.admin-player-page .form-label {
    margin-bottom: 7px;
    color: var(--player-charcoal);
    font-size: 14px;
    font-weight: 500;
}

.admin-player-page .required {
    color: var(--player-danger);
}

.admin-player-page .form-control,
.admin-player-page .form-select {
    min-height: 44px;
    border: 1px solid var(--player-line);
    border-radius: 8px;
    background-color: var(--player-white);
    color: var(--player-ink);
    font: inherit;
    font-size: 14px;
    box-shadow: none;
}

.admin-player-page .form-control:focus,
.admin-player-page .form-select:focus {
    border-color: var(--player-ink);
    box-shadow: 0 0 0 3px var(--player-cloud);
}

.admin-player-page .form-control.is-invalid:focus,
.admin-player-page .form-select.is-invalid:focus {
    border-color: var(--player-danger);
    box-shadow: 0 0 0 3px #fdf1f0;
}

.admin-player-page .form-control[type="file"] {
    min-height: 48px;
    padding: 5px;
}

.admin-player-page .form-control[type="file"]::file-selector-button {
    min-height: 36px;
    margin: 0 10px 0 0;
    padding: 7px 14px;
    border: 0;
    border-radius: 9999px;
    background: var(--player-cloud);
    color: var(--player-ink);
    font: inherit;
    font-size: 13px;
    cursor: pointer;
}

.admin-player-page .input-group-text {
    border-color: var(--player-line);
    border-radius: 8px 0 0 8px;
    background: var(--player-cloud);
    color: var(--player-charcoal);
    font-size: 14px;
}

.admin-player-page .input-group > .form-control:not(:first-child) {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.admin-player-page .form-text {
    margin-top: 7px;
    color: var(--player-muted);
    font-size: 12px;
}

.admin-player-page .form-check-input {
    border-color: var(--player-line);
}

.admin-player-page .form-check-input:checked {
    border-color: var(--player-ink);
    background-color: var(--player-ink);
}

.admin-player-page .form-check-input:focus {
    border-color: var(--player-ink);
    box-shadow: 0 0 0 3px var(--player-cloud);
}

.admin-player-page .form-check-label {
    color: var(--player-charcoal);
    font-size: 13px;
}

.admin-player-page .player-form-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 28px !important;
    padding-top: 20px !important;
    border-top: 1px solid var(--player-line-soft) !important;
}

.admin-player-page .alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 14px 16px;
    border: 1px solid var(--player-line-soft);
    border-radius: 8px;
    font-size: 14px;
    line-height: 1.5;
    box-shadow: none;
}

.admin-player-page .alert-success {
    border-color: #d4e9dd;
    background: #edf6f1;
    color: var(--player-success);
}

.admin-player-page .alert-danger {
    border-color: #e8c8c5;
    background: #fdf1f0;
    color: var(--player-danger);
}

.admin-player-page .alert-content {
    min-width: 0;
    flex: 1 1 auto;
}

.admin-player-page .alert-content strong {
    display: block;
}

.admin-player-page :is(a, button, input, select, textarea):focus-visible {
    outline: 2px solid var(--player-ink);
    outline-offset: 3px;
}

@media (max-width: 991.98px) {
    .admin-player-page .page-header {
        grid-template-columns: minmax(0, 1fr);
        align-items: start;
    }

    .admin-player-page .player-header-actions {
        grid-template-columns: minmax(200px, 1fr) max-content max-content;
        justify-content: start;
    }

    .admin-player-page .player-header-actions .search-box {
        width: 100%;
    }
}

@media (max-width: 767.98px) {
    .admin-player-page .page-header {
        align-items: stretch;
    }

    .admin-player-page .player-header-actions,
    .admin-player-page .player-header-actions > *,
    .admin-player-page .player-header-actions form,
    .admin-player-page .player-header-actions .btn,
    .admin-player-page .btn-create {
        width: 100%;
    }

    .admin-player-page .player-header-actions {
        grid-template-columns: minmax(0, 1fr);
        justify-content: stretch;
        white-space: normal;
    }

    .admin-player-page .search-box {
        width: 100%;
    }

    .admin-player-page .page-header .btn-back {
        width: 100%;
    }

    .admin-player-page .form-card .card-header,
    .admin-player-page .main-card .card-header {
        padding: 16px;
    }

    .admin-player-page .form-card .card-body {
        padding: 16px;
    }

    .admin-player-page .pagination-container {
        align-items: flex-start;
        padding: 16px;
    }

    .admin-player-page .pagination-controls {
        width: 100%;
        justify-content: space-between;
    }

    .admin-player-page .player-form-actions .btn {
        flex: 1 1 140px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .admin-player-page * {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
    }
}
