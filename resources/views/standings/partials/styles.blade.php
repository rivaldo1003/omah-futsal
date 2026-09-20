<style>
    /* ==========================================================================
       STANDINGS PAGE — SPECIFIC STYLES
       Shared design tokens (--bg-page, --surface, --accent, spacing, radii)
       are inherited from the home styles included in the main entry file.
       ========================================================================== */

    /* Page Header — clean surface card with accent left border, no gradient */
    .standings-page-header {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-left: 3px solid var(--accent);
        border-radius: var(--radius-md);
        padding: var(--space-4) var(--space-5);
        margin-bottom: var(--space-5);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--space-4);
    }

    .standings-page-header h1 {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--space-2);
        line-height: 1.3;
    }

    .standings-page-header h1 i {
        color: var(--accent);
        font-size: 20px;
    }

    .standings-tournament-info {
        font-size: 13px;
        color: var(--text-secondary);
        margin-top: var(--space-1);
    }

    /* Tournament Selector — clean input style */
    .standings-tournament-select {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: var(--space-2) var(--space-3);
        color: var(--text-primary);
        font-size: 13px;
        font-weight: 500;
        min-width: 200px;
        cursor: pointer;
        transition: border-color 150ms ease;
        appearance: auto;
    }

    .standings-tournament-select:hover {
        border-color: var(--border-focus);
    }

    .standings-tournament-select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 2px var(--accent-subtle);
    }

    /* Compact Stats Grid (preserved, commented-out in template) */
    .standings-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: var(--space-3);
        margin-bottom: var(--space-5);
    }

    .standings-stat-card {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: var(--space-3) var(--space-4);
        display: flex;
        align-items: center;
        gap: var(--space-3);
        transition: border-color 150ms ease;
    }

    .standings-stat-card:hover {
        border-color: var(--border-focus);
    }

    .standings-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: #ffffff;
        flex-shrink: 0;
    }

    .standings-stat-icon.icon-accent { background-color: var(--accent); }
    .standings-stat-icon.icon-success { background-color: var(--success); }
    .standings-stat-icon.icon-warning { background-color: var(--warning); }
    .standings-stat-icon.icon-danger { background-color: var(--danger); }

    .standings-stat-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        line-height: 1.2;
    }

    .standings-stat-label {
        font-size: 11px;
        color: var(--text-secondary);
        margin: 0;
    }

    /* ==========================================================================
       GROUP CARD
       ========================================================================== */
    .standings-group-card {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        overflow: hidden;
        margin-bottom: 0;
    }

    .standings-group-header {
        padding: var(--space-3) var(--space-4);
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .standings-group-title {
        margin: 0;
        font-weight: 600;
        font-size: 14px;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: var(--space-2);
    }

    .standings-group-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        background-color: var(--accent);
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        border-radius: 4px;
    }

    .standings-group-meta {
        font-size: 12px;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* ==========================================================================
       STANDINGS TABLE
       ========================================================================== */
    .standings-table {
        margin: 0;
        font-size: 13px;
        width: 100%;
    }

    .standings-table thead th {
        background-color: var(--surface-subtle);
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 11px;
        border-bottom: 1px solid var(--border-color);
        padding: var(--space-2) var(--space-2);
        text-align: center;
        white-space: nowrap;
    }

    .standings-table thead th:first-child {
        text-align: center;
        padding-left: var(--space-3);
    }

    .standings-table thead th:nth-child(2) {
        text-align: left;
        padding-left: var(--space-3);
    }

    .standings-table tbody td {
        padding: var(--space-2) var(--space-2);
        vertical-align: middle;
        text-align: center;
        border-bottom: 1px solid var(--surface-subtle);
    }

    .standings-table tbody td:first-child {
        padding-left: var(--space-3);
    }

    .standings-table tbody td:nth-child(2) {
        text-align: left;
        padding-left: var(--space-3);
    }

    .standings-table tbody tr {
        transition: background-color 150ms ease;
    }

    .standings-table tbody tr:hover {
        background-color: var(--surface-hover);
    }

    .standings-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Position Indicator */
    .standings-position {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 4px;
        font-weight: 700;
        font-size: 11px;
        color: var(--text-secondary);
        background-color: var(--surface-subtle);
    }

    /* Team Info */
    .standings-team-info {
        display: flex;
        align-items: center;
        gap: var(--space-2);
        min-width: 160px;
    }

    .standings-team-logo {
        width: 28px;
        height: 28px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
        background-color: var(--surface-subtle);
        border: 1px solid var(--border-color);
    }

    .standings-team-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .standings-team-abbr {
        font-weight: 700;
        color: var(--accent);
        font-size: 10px;
    }

    .standings-team-name {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 13px;
        line-height: 1.3;
    }

    .standings-team-meta {
        font-size: 11px;
        color: var(--text-secondary);
    }

    /* Stat columns: W/D/L coloring */
    .standings-stat-win {
        color: var(--success);
        font-weight: 600;
    }

    .standings-stat-draw {
        color: var(--text-secondary);
    }

    .standings-stat-loss {
        color: var(--danger);
    }

    .standings-stat-played {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* GD Indicator — flat semantic colors, no gradients */
    .standings-gd {
        font-weight: 600;
        font-size: 12px;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
    }

    .standings-gd-positive {
        background-color: var(--success-subtle);
        color: var(--success);
    }

    .standings-gd-negative {
        background-color: var(--danger-subtle);
        color: var(--danger);
    }

    .standings-gd-neutral {
        background-color: var(--surface-subtle);
        color: var(--text-secondary);
    }

    /* Points Badge — flat accent */
    .standings-points {
        background-color: var(--accent-subtle);
        color: var(--accent);
        font-weight: 700;
        font-size: 13px;
        padding: 2px 8px;
        border-radius: 4px;
        min-width: 36px;
        text-align: center;
        display: inline-block;
    }

    /* Form Dots */
    .standings-form {
        display: flex;
        gap: 3px;
        justify-content: center;
        align-items: center;
    }

    .standings-form-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }

    .standings-dot-win { background-color: var(--success); }
    .standings-dot-draw { background-color: var(--warning); }
    .standings-dot-loss { background-color: var(--danger); }
    .standings-dot-empty { background-color: var(--border-color); }

    /* ==========================================================================
       EMPTY STATE
       ========================================================================== */
    .standings-empty {
        text-align: center;
        padding: var(--space-8) var(--space-4);
    }

    .standings-empty-icon {
        font-size: 48px;
        color: var(--border-color);
        margin-bottom: var(--space-4);
    }

    .standings-empty-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: var(--space-2);
    }

    .standings-empty-text {
        font-size: 14px;
        color: var(--text-secondary);
        margin-bottom: var(--space-5);
    }

    .standings-tournament-buttons {
        display: flex;
        gap: var(--space-2);
        justify-content: center;
        flex-wrap: wrap;
    }

    /* ==========================================================================
       RESPONSIVE
       ========================================================================== */
    @media (max-width: 768px) {
        .standings-page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-3);
        }

        .standings-tournament-select {
            width: 100%;
        }

        .standings-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .standings-team-info {
            min-width: auto;
        }

        .standings-team-name {
            font-size: 12px;
        }
    }

    @media (max-width: 576px) {
        .standings-stats-grid {
            grid-template-columns: 1fr;
        }

        .standings-group-header {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-2);
        }
    }
</style>
