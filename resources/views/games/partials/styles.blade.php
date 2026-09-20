<style>
    /* ==========================================================================
       MATCH SCHEDULE — SPECIFIC STYLES
       Shared design tokens (--bg-page, --surface, --accent, spacing, radii)
       are inherited from home styles.
       ========================================================================== */

    /* Breadcrumb */
    .schedule-breadcrumb {
        display: flex;
        align-items: center;
        gap: var(--space-2);
        list-style: none;
        padding: 0;
        margin: 0 0 var(--space-4) 0;
        font-size: 13px;
        color: var(--text-secondary);
    }

    .schedule-breadcrumb li {
        display: flex;
        align-items: center;
        gap: var(--space-2);
    }

    .schedule-breadcrumb li:not(:last-child)::after {
        content: "/";
        color: var(--border-color);
        font-size: 11px;
    }

    .schedule-breadcrumb a {
        color: var(--text-secondary);
        text-decoration: none;
    }

    .schedule-breadcrumb a:hover {
        color: var(--accent);
    }

    .schedule-breadcrumb .active {
        color: var(--text-primary);
        font-weight: 500;
    }

    /* Page Header */
    .schedule-page-header {
        margin-bottom: var(--space-5);
    }

    .schedule-page-header h1 {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 var(--space-1) 0;
        display: flex;
        align-items: center;
        gap: var(--space-2);
        letter-spacing: -0.01em;
    }

    .schedule-page-header h1 i {
        color: var(--accent);
    }

    .schedule-page-header p {
        font-size: 14px;
        color: var(--text-secondary);
        margin: 0;
    }

    /* Tournament Selector Card */
    .schedule-selector-card {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: var(--space-4);
        margin-bottom: var(--space-4);
    }

    .schedule-selector-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: var(--space-2);
    }

    .schedule-badge-chip {
        display: inline-flex;
        align-items: center;
        gap: var(--space-1);
        padding: 4px 10px;
        border-radius: var(--radius-sm);
        font-size: 12px;
        font-weight: 600;
    }

    .schedule-badge-accent {
        background-color: var(--accent-subtle);
        color: var(--accent);
        border: 1px solid rgba(29, 78, 216, 0.2);
    }

    .schedule-badge-friendly {
        background-color: rgba(15, 118, 110, 0.1);
        color: #0f766e;
        border: 1px solid rgba(15, 118, 110, 0.2);
    }

    /* Tournament Info Banner */
    .schedule-info-banner {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-left: 3px solid var(--accent);
        border-radius: var(--radius-md);
        padding: var(--space-3) var(--space-4);
        margin-bottom: var(--space-4);
    }

    .schedule-info-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 2px;
    }

    .schedule-info-meta {
        font-size: 12px;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: var(--space-3);
        flex-wrap: wrap;
    }

    .schedule-info-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Filter Toolbar */
    .schedule-filter-card {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: var(--space-4);
        margin-bottom: var(--space-5);
    }

    .schedule-filter-card .form-control,
    .schedule-filter-card .form-select {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: var(--space-2) var(--space-3);
        font-size: 13px;
        color: var(--text-primary);
        background-color: var(--surface);
        transition: border-color 150ms ease;
    }

    .schedule-filter-card .form-control:focus,
    .schedule-filter-card .form-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 2px var(--accent-subtle);
        outline: none;
    }

    /* Stat Summary Cards */
    .schedule-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--space-3);
        margin-bottom: var(--space-5);
    }

    .schedule-stat-item {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: var(--space-3) var(--space-4);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .schedule-stat-label {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-secondary);
        margin: 0 0 2px 0;
    }

    .schedule-stat-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .schedule-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .stat-icon-total {
        background-color: var(--accent-subtle);
        color: var(--accent);
    }

    .stat-icon-completed {
        background-color: var(--success-subtle);
        color: var(--success);
    }

    .stat-icon-upcoming {
        background-color: var(--warning-subtle);
        color: var(--warning);
    }

    .stat-icon-ongoing {
        background-color: var(--danger-subtle);
        color: var(--danger);
    }

    /* Date-Grouped Match Card */
    .schedule-day-group {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        overflow: hidden;
        margin-bottom: var(--space-4);
    }

    .schedule-date-header {
        background-color: var(--surface-subtle);
        border-bottom: 1px solid var(--border-color);
        padding: var(--space-3) var(--space-4);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .schedule-date-header i {
        color: var(--accent);
        margin-right: var(--space-1);
    }

    .schedule-match-count {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-secondary);
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        padding: 2px 8px;
        border-radius: 12px;
    }

    /* Individual Match Row */
    .schedule-match-row {
        padding: var(--space-3) var(--space-4);
        border-bottom: 1px solid var(--surface-subtle);
        transition: background-color 150ms ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--space-4);
    }

    .schedule-match-row:hover {
        background-color: var(--surface-hover);
    }

    .schedule-match-row:last-child {
        border-bottom: none;
    }

    /* Match Time & Venue */
    .schedule-match-time-col {
        min-width: 90px;
        flex-shrink: 0;
    }

    .schedule-time-text {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .schedule-venue-text {
        font-size: 11px;
        color: var(--text-secondary);
        margin-top: 2px;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    /* Teams & Score Center Container */
    .schedule-teams-center {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-3);
        min-width: 260px;
    }

    .schedule-team-item {
        flex: 1;
        display: flex;
        align-items: center;
        gap: var(--space-2);
        min-width: 100px;
    }

    .schedule-team-home {
        justify-content: flex-end;
        text-align: right;
    }

    .schedule-team-away {
        justify-content: flex-start;
        text-align: left;
    }

    .schedule-team-logo {
        width: 26px;
        height: 26px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--surface-subtle);
        border: 1px solid var(--border-color);
        font-size: 10px;
        font-weight: 700;
        color: var(--accent);
        flex-shrink: 0;
        overflow: hidden;
    }

    .schedule-team-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .schedule-team-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        line-height: 1.3;
    }

    /* Score Badge */
    .schedule-score-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 80px;
    }

    .schedule-score-badge {
        background-color: var(--surface-subtle);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        font-size: 14px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: var(--radius-sm);
        letter-spacing: 0.02em;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .schedule-score-badge.is-live {
        background-color: var(--danger-subtle);
        color: var(--danger);
        border-color: rgba(220, 38, 38, 0.3);
    }

    .schedule-live-dot {
        width: 6px;
        height: 6px;
        background-color: var(--danger);
        border-radius: 50%;
        display: inline-block;
    }

    .schedule-score-badge.is-vs {
        background-color: transparent;
        border: 1px dashed var(--border-color);
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
    }

    /* Extra Info Chips (ET, Penalties) */
    .schedule-extra-scores {
        display: flex;
        gap: 4px;
        margin-top: 4px;
        font-size: 10px;
        font-weight: 600;
    }

    .schedule-chip-et {
        background-color: rgba(13, 202, 240, 0.1);
        color: #087990;
        border: 1px solid rgba(13, 202, 240, 0.2);
        padding: 1px 5px;
        border-radius: 3px;
    }

    .schedule-chip-penalty {
        background-color: var(--warning-subtle);
        color: var(--warning);
        border: 1px solid rgba(217, 119, 6, 0.2);
        padding: 1px 5px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }

    /* Right Action & Status Column */
    .schedule-match-action-col {
        min-width: 130px;
        text-align: right;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: var(--space-1);
    }

    /* Status Badges */
    .schedule-status-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 4px;
        display: inline-block;
    }

    .status-badge-completed {
        background-color: var(--success-subtle);
        color: var(--success);
    }

    .status-badge-ongoing {
        background-color: var(--danger-subtle);
        color: var(--danger);
    }

    .status-badge-upcoming {
        background-color: var(--warning-subtle);
        color: var(--warning);
    }

    .schedule-round-badge {
        font-size: 10px;
        font-weight: 500;
        color: var(--text-secondary);
        background-color: var(--surface-subtle);
        border: 1px solid var(--border-color);
        padding: 1px 6px;
        border-radius: 3px;
        display: inline-block;
    }

    /* Empty State */
    .schedule-empty {
        text-align: center;
        padding: var(--space-8) var(--space-4);
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        margin: var(--space-4) 0;
    }

    .schedule-empty-icon {
        font-size: 40px;
        color: var(--border-color);
        margin-bottom: var(--space-3);
    }

    .schedule-empty-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: var(--space-2);
    }

    .schedule-empty-text {
        font-size: 13px;
        color: var(--text-secondary);
        max-width: 440px;
        margin: 0 auto var(--space-4) auto;
    }

    /* ==========================================================================
       PAGINATION (Bootstrap 5 & Clean Design System)
       ========================================================================== */
    .schedule-pagination-wrapper {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: var(--space-3) var(--space-4);
        margin-top: var(--space-4);
    }

    .schedule-pagination-wrapper nav {
        width: 100%;
    }

    .schedule-pagination-wrapper .pagination {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 3px;
        list-style: none;
        padding: 0;
        flex-wrap: wrap;
    }

    .schedule-pagination-wrapper .page-item .page-link {
        color: var(--text-primary);
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 5px 11px;
        font-size: 13px;
        font-weight: 500;
        line-height: 1.4;
        transition: all 150ms ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
    }

    .schedule-pagination-wrapper .page-item .page-link:hover {
        background-color: var(--surface-subtle);
        border-color: var(--border-focus);
        color: var(--accent);
    }

    .schedule-pagination-wrapper .page-item.active .page-link {
        background-color: var(--accent);
        border-color: var(--accent);
        color: #ffffff;
        font-weight: 600;
        box-shadow: none;
    }

    .schedule-pagination-wrapper .page-item.disabled .page-link {
        color: var(--text-muted);
        background-color: var(--surface-subtle);
        border-color: var(--border-color);
        cursor: not-allowed;
        opacity: 0.65;
    }

    .schedule-pagination-wrapper svg {
        width: 14px;
        height: 14px;
        display: inline-block;
        vertical-align: middle;
    }

    .schedule-pagination-wrapper p {
        margin-bottom: 0;
        font-size: 13px;
        color: var(--text-secondary);
    }

    .schedule-pagination-wrapper p span {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .schedule-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .schedule-stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: var(--space-2);
        }

        .schedule-match-row {
            flex-direction: column;
            align-items: stretch;
            gap: var(--space-3);
            text-align: center;
        }

        .schedule-match-time-col {
            text-align: center;
        }

        .schedule-teams-center {
            flex-direction: column;
            gap: var(--space-2);
            min-width: unset;
        }

        .schedule-team-home,
        .schedule-team-away {
            justify-content: center;
            text-align: center;
        }

        .schedule-match-action-col {
            align-items: center;
            text-align: center;
            width: 100%;
        }

        .schedule-match-action-col .btn-action-sm {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .schedule-stats-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>
