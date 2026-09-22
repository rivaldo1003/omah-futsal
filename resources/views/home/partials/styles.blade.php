<style>
    /* ==========================================================================
       DESIGN SYSTEM TOKENS & BASE STYLES
       Adheres to design-guidelines-agentic-ai.md & layout-standards-agentic-ai.md
       ========================================================================== */
    :root {
        /* ==========================================================================
           ULTRA ANALYTICS ENGINE v3.0 — CORE TOKENS
           Adheres to ultra_analytics_engine_v3_0_design_system.md
           ========================================================================== */
        --v3-bg: #020408;
        --v3-surface: #070c14;
        --v3-card: #0d1524;
        --v3-border: #18263e;
        --v3-border-glow: rgba(0, 255, 135, 0.4);
        --v3-neon-green: #00ff87;
        --v3-neon-blue: #00e5ff;
        --v3-neon-pink: #ff0055;
        --v3-neon-yellow: #ffb700;
        --v3-text-main: #ffffff;
        --v3-text-sub: #8da1b9;
        --v3-text-muted: #4e6178;
        --v3-font-mono: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;

        /* Legacy token bridge (mapped to v3.0 palette) */
        --bg-page: var(--v3-bg);
        --surface: var(--v3-surface);
        --surface-subtle: var(--v3-card);
        --surface-hover: #101a2c;
        --border-color: var(--v3-border);
        --border-focus: var(--v3-neon-green);

        /* Typography */
        --text-primary: var(--v3-text-main);
        --text-secondary: var(--v3-text-sub);
        --text-muted: var(--v3-text-muted);

        /* Primary Accent (Neon Green) */
        --accent: var(--v3-neon-green);
        --accent-hover: #00cc6a;
        --accent-subtle: rgba(0, 255, 135, 0.08);

        /* Semantic Status Colors */
        --success: var(--v3-neon-green);
        --success-subtle: rgba(0, 255, 135, 0.1);
        --warning: var(--v3-neon-yellow);
        --warning-subtle: rgba(255, 183, 0, 0.1);
        --danger: var(--v3-neon-pink);
        --danger-subtle: rgba(255, 0, 85, 0.1);

        /* 4px Spacing Scale */
        --space-1: 4px;
        --space-2: 8px;
        --space-3: 12px;
        --space-4: 16px;
        --space-5: 24px;
        --space-6: 32px;
        --space-8: 48px;

        /* Radii */
        --radius-sm: 6px;
        --radius-md: 8px;
        --radius-lg: 10px;

        /* Z-Index Scale */
        --z-base: 0;
        --z-sticky: 10;
        --z-dropdown: 20;
        --z-drawer: 30;
        --z-modal-backdrop: 40;
        --z-modal: 41;
        --z-toast: 50;
    }

    /* Base Reset */
    * {
        box-sizing: border-box;
    }

    body {
        background-color: var(--bg-page);
        background-image:
            radial-gradient(ellipse 80% 50% at 50% -10%, rgba(0, 255, 135, 0.06), transparent),
            radial-gradient(ellipse 60% 40% at 90% 110%, rgba(0, 229, 255, 0.04), transparent);
        background-attachment: fixed;
        color: var(--text-primary);
        font-family: var(--v3-font-sans, system-ui), -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        font-size: 14px;
        line-height: 1.5;
        margin: 0;
        padding: 0;
        -webkit-font-smoothing: antialiased;
    }

    a {
        color: var(--accent);
        text-decoration: none;
        transition: color 150ms ease;
    }

    a:hover {
        color: var(--accent-hover);
    }

    /* Container constraints */
    .app-container {
        width: 100%;
        max-width: 1240px;
        margin-left: auto;
        margin-right: auto;
        padding-left: var(--space-4);
        padding-right: var(--space-4);
    }

    @media (min-width: 640px) {
        .app-container {
            padding-left: var(--space-5);
            padding-right: var(--space-5);
        }
    }

    @media (min-width: 1024px) {
        .app-container {
            padding-left: var(--space-6);
            padding-right: var(--space-6);
        }
    }

    /* ==========================================================================
       NAVBAR
       ========================================================================== */
    .app-navbar {
        background-color: rgba(2, 4, 8, 0.92);
        backdrop-filter: blur(8px);
        border-bottom: 1px solid var(--border-color);
        position: sticky;
        top: 0;
        z-index: var(--z-sticky);
        padding-top: var(--space-2);
        padding-bottom: var(--space-2);
    }

    .nav-brand-wrap {
        display: flex;
        align-items: center;
        gap: var(--space-2);
        text-decoration: none;
        color: var(--text-primary);
    }

    .nav-brand-logo {
        width: 32px;
        height: 32px;
        object-fit: contain;
    }

    .nav-brand-title {
        font-size: 15px;
        font-weight: 700;
        letter-spacing: -0.01em;
        line-height: 1.2;
    }

    .nav-brand-sub {
        font-size: 11px;
        font-weight: 500;
        color: var(--text-secondary);
        display: block;
    }

    .nav-menu-list {
        display: flex;
        align-items: center;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: var(--space-1);
    }

    .nav-menu-link {
        display: inline-flex;
        align-items: center;
        gap: var(--space-2);
        padding: var(--space-2) var(--space-3);
        font-size: 13px;
        font-weight: 500;
        color: var(--text-secondary);
        border-radius: var(--radius-sm);
        transition: all 150ms ease;
    }

    .nav-menu-link:hover {
        color: var(--text-primary);
        background-color: var(--surface-subtle);
    }

    .nav-menu-link.active {
        color: var(--accent);
        background-color: var(--accent-subtle);
        font-weight: 600;
    }

    .nav-btn-admin {
        display: inline-flex;
        align-items: center;
        gap: var(--space-1);
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 500;
        color: var(--accent);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: transparent;
    }

    .nav-btn-admin:hover {
        background-color: var(--surface-subtle);
        border-color: var(--accent);
    }

    .nav-btn-logout {
        border: none;
        background: none;
        color: var(--danger);
        font-size: 13px;
        font-weight: 500;
        padding: var(--space-2) var(--space-3);
        border-radius: var(--radius-sm);
        display: inline-flex;
        align-items: center;
        gap: var(--space-1);
        cursor: pointer;
    }

    .nav-btn-logout:hover {
        background-color: var(--danger-subtle);
    }

    .nav-btn-login {
        display: inline-flex;
        align-items: center;
        gap: var(--space-1);
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 500;
        color: #ffffff !important;
        background-color: var(--accent);
        border-radius: var(--radius-sm);
        border: none;
    }

    .nav-btn-login:hover {
        background-color: var(--accent-hover);
        color: #ffffff;
    }

    /* Mobile toggle button */
    .navbar-toggler {
        border: 1px solid var(--border-color);
        padding: var(--space-1) var(--space-2);
        border-radius: var(--radius-sm);
    }

    .navbar-toggler:focus {
        box-shadow: none;
        outline: 2px solid var(--accent);
    }

    /* ==========================================================================
       HERO SECTION
       ========================================================================== */
    .hero-wrap {
        border-bottom: 1px solid var(--border-color);
        position: relative;
        padding-top: var(--space-8);
        padding-bottom: var(--space-8);
    }

    .hero-content {
        max-width: 760px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 900;
        font-style: italic;
        text-transform: uppercase;
        line-height: 1.15;
        letter-spacing: -0.03em;
        text-shadow: 0 0 20px rgba(0, 255, 135, 0.35);
        margin-bottom: var(--space-3);
    }

    @media (max-width: 900px) {
        .hero-title {
            font-size: 1.8rem;
        }
    }

    .hero-subtitle {
        font-size: 1rem;
        line-height: 1.5;
        max-width: 620px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: var(--space-5);
    }

    .hero-cta {
        display: inline-flex;
        align-items: center;
        gap: var(--space-2);
        height: 44px;
        padding: 0 var(--space-5);
        font-size: 14px;
        font-weight: 600;
        border-radius: var(--radius-sm);
        text-decoration: none;
        cursor: pointer;
        transition: opacity 150ms ease;
    }

    .hero-cta:hover {
        opacity: 0.92;
    }

    .hero-image-zoom-hint {
        display: inline-flex;
        align-items: center;
        gap: var(--space-2);
        padding: 6px 14px;
        background-color: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        color: #ffffff;
        font-size: 12px;
        border-radius: var(--radius-sm);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-top: var(--space-4);
        cursor: pointer;
    }

    /* ==========================================================================
       CARD & SECTION ARCHITECTURE
       ========================================================================== */
    .content-section {
        margin-top: var(--space-6);
        margin-bottom: var(--space-6);
    }

    .app-card {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        margin-bottom: var(--space-5);
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.45);
    }

    .app-card-header {
        border-bottom: 1px solid var(--border-color);
    }

    .app-card-title {
        font-weight: 800;
        font-style: italic;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .app-card-title i {
        color: var(--v3-neon-green);
    }

    .app-card-header {
        padding: var(--space-3) var(--space-4);
        border-bottom: 1px solid var(--border-color);
        background-color: var(--surface);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .app-card-title {
        display: flex;
        align-items: center;
        gap: var(--space-2);
        font-size: 14px;
        font-weight: 800;
        font-style: italic;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-primary);
        margin: 0;
    }

    .app-card-title i {
        color: var(--v3-neon-green);
        font-size: 15px;
    }

    .app-card-body {
        padding: var(--space-4);
    }

    /* Badges & Chips */
    .app-badge {
        display: inline-flex;
        align-items: center;
        gap: var(--space-1);
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 500;
        border-radius: var(--radius-sm);
        line-height: 1.4;
    }

    .app-badge-default {
        background-color: var(--surface-subtle);
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
    }

    .app-badge-accent {
        background-color: var(--accent-subtle);
        color: var(--accent);
    }

    .app-badge-success {
        background-color: var(--success-subtle);
        color: var(--success);
    }

    .app-badge-warning {
        background-color: var(--warning-subtle);
        color: var(--warning);
    }

    .app-badge-danger {
        background-color: var(--danger-subtle);
        color: var(--danger);
    }

    /* Buttons */
    .btn-action-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-2);
        height: 36px;
        padding: 0 var(--space-4);
        font-size: 13px;
        font-weight: 500;
        color: #ffffff;
        background-color: var(--accent);
        border: 1px solid transparent;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: background-color 150ms ease;
        text-decoration: none;
    }

    .btn-action-primary:hover {
        background-color: var(--accent-hover);
        color: #ffffff;
    }

    .btn-action-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-2);
        height: 36px;
        padding: 0 var(--space-4);
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 150ms ease;
        text-decoration: none;
    }

    .btn-action-secondary:hover {
        background-color: var(--surface-subtle);
        color: var(--text-primary);
        border-color: var(--border-focus);
    }

    .btn-action-sm {
        height: 30px;
        padding: 0 10px;
        font-size: 12px;
    }

    /* ==========================================================================
       PAGINATION (Universal)
       ========================================================================== */
    .pagination {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 3px;
        list-style: none;
        padding: 0;
        flex-wrap: wrap;
    }

    .page-item .page-link {
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

    .page-item .page-link:hover {
        background-color: var(--surface-subtle);
        border-color: var(--border-focus);
        color: var(--accent);
    }

    .page-item.active .page-link {
        background-color: var(--accent);
        border-color: var(--accent);
        color: #ffffff;
        font-weight: 600;
        box-shadow: none;
    }

    .page-item.disabled .page-link {
        color: var(--text-muted);
        background-color: var(--surface-subtle);
        border-color: var(--border-color);
        cursor: not-allowed;
        opacity: 0.65;
    }

    /* ==========================================================================
       MATCH ITEMS (Today & Upcoming)
       ========================================================================== */
    .match-row-item {
        border-bottom: 1px solid var(--border-color);
        padding-top: var(--space-4);
        padding-bottom: var(--space-4);
    }

    .match-row-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .match-row-item:first-child {
        padding-top: 0;
    }

    .match-meta-box {
        text-align: center;
        padding: var(--space-2);
        background-color: var(--surface-subtle);
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
    }

    .match-time-text {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .match-venue-text {
        font-size: 11px;
        color: var(--text-secondary);
    }

    .match-teams-grid {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--space-3);
    }

    .match-team-side {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .match-team-side.home {
        text-align: right;
    }

    .match-team-side.away {
        text-align: left;
    }

    .match-team-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .match-team-stage {
        font-size: 11px;
        color: var(--text-secondary);
    }

    .match-score-center {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 80px;
    }

    .score-chip {
        font-size: 16px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: var(--radius-sm);
        background-color: var(--surface-subtle);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
    }

    .score-chip.live {
        background-color: var(--danger-subtle);
        color: var(--danger);
        border-color: rgba(220, 38, 38, 0.2);
    }

    .match-events-box {
        margin-top: var(--space-3);
        padding: var(--space-2) var(--space-3);
        background-color: var(--surface-subtle);
        border-radius: var(--radius-sm);
        font-size: 12px;
    }

    /* Upcoming Item */
    .upcoming-row {
        padding: var(--space-3) 0;
        border-bottom: 1px solid var(--border-color);
    }

    .upcoming-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .upcoming-row:first-child {
        padding-top: 0;
    }

    .team-pill {
        display: inline-flex;
        align-items: center;
        gap: var(--space-2);
    }

    .team-pill-logo {
        width: 24px;
        height: 24px;
        border-radius: 4px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .team-pill-fallback {
        width: 24px;
        height: 24px;
        border-radius: 4px;
        background-color: var(--surface-subtle);
        border: 1px solid var(--border-color);
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-primary);
        flex-shrink: 0;
    }

    /* ==========================================================================
       STANDINGS TABLE
       ========================================================================== */
    .standings-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin-bottom: 0;
    }

    .standings-table th {
        background-color: var(--surface-subtle);
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 11px;
        padding: 8px 10px;
        border-bottom: 1px solid var(--border-color);
        text-align: center;
    }

    .standings-table th:first-child,
    .standings-table th:nth-child(2) {
        text-align: left;
    }

    .standings-table td {
        padding: 10px 10px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        text-align: center;
    }

    .standings-table td:first-child,
    .standings-table td:nth-child(2) {
        text-align: left;
    }

    .standings-table tr:hover {
        background-color: var(--surface-hover);
    }

    .standings-table tr.row-qualified {
        background-color: rgba(22, 163, 74, 0.03);
    }

    .rank-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        color: var(--text-secondary);
        background-color: var(--surface-subtle);
    }

    .row-qualified .rank-num {
        background-color: var(--success-subtle);
        color: var(--success);
    }

    .gd-positive {
        color: var(--success);
        font-weight: 600;
    }

    .gd-negative {
        color: var(--danger);
        font-weight: 600;
    }

    .pts-badge {
        font-weight: 700;
        color: var(--accent);
    }

    .tie-break-box {
        background-color: var(--surface-subtle);
        border: 1px solid var(--border-color);
        border-left: 3px solid var(--accent);
        border-radius: var(--radius-sm);
        padding: var(--space-3);
        margin-top: var(--space-4);
        font-size: 12px;
        color: var(--text-secondary);
    }

    /* ==========================================================================
       TEAM CARDS & GRID
       ========================================================================== */

    .teams-grid-row {
        margin-left: -8px;
        margin-right: -8px;
    }

    .teams-grid-row > [class*="col-"] {
        display: flex;
        padding-left: 8px;
        padding-right: 8px;
    }

    .team-item-card {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: var(--space-4);
        display: flex;
        flex-direction: column;
        width: 100%;
        height: 100%;
        transition: border-color 150ms ease, box-shadow 150ms ease;
    }

    .team-item-card:hover {
        border-color: var(--border-focus);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    /* Top identity row: Logo + Name/Coach on Left, Status Badge on Right */
    .team-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: var(--space-3);
        margin-bottom: var(--space-3);
    }

    .team-card-identity {
        display: flex;
        align-items: center;
        gap: var(--space-3);
        min-width: 0;
        flex: 1;
    }

    .team-card-logo {
        width: 42px;
        height: 42px;
        border-radius: var(--radius-sm);
        object-fit: cover;
        border: 1px solid var(--border-color);
        background-color: var(--surface-subtle);
        flex-shrink: 0;
    }

    .team-card-logo-fallback {
        width: 42px;
        height: 42px;
        border-radius: var(--radius-sm);
        background-color: var(--surface-subtle);
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-primary);
        flex-shrink: 0;
        letter-spacing: 0.5px;
    }

    .team-card-info {
        min-width: 0;
        flex: 1;
    }

    .team-card-title {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .team-card-subtitle {
        font-size: 11.5px;
        color: var(--text-secondary);
        margin-top: 2px;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Stats row: clean, crisp hairlines, no heavy background tint */
    .team-card-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background-color: var(--surface-subtle);
        margin-bottom: var(--space-3);
        text-align: center;
        overflow: hidden;
    }

    .team-card-stat-box {
        padding: 6px 4px;
        border-right: 1px solid var(--border-color);
        min-width: 0;
    }

    .team-card-stat-box:last-child {
        border-right: none;
    }

    .team-card-stat-num {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .team-card-stat-lbl {
        font-size: 10px;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-top: 1px;
    }

    /* Roster preview section */
    .team-card-roster {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: var(--space-3);
        min-height: 24px;
    }

    .team-card-roster-lbl {
        font-size: 11px;
        color: var(--text-secondary);
        white-space: nowrap;
        flex-shrink: 0;
    }

    .team-card-roster-chips {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
        min-width: 0;
        flex: 1;
    }

    .player-pill-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 7px;
        border-radius: 4px;
        background-color: var(--surface-subtle);
        border: 1px solid var(--border-color);
        font-size: 11px;
        color: var(--text-secondary);
        max-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .player-pill-more {
        display: inline-flex;
        align-items: center;
        padding: 2px 6px;
        border-radius: 4px;
        background-color: var(--surface-subtle);
        border: 1px solid var(--border-color);
        font-size: 10.5px;
        font-weight: 600;
        color: var(--text-secondary);
    }

    /* Card footer: full width action bar pinned to bottom */
    .team-card-footer {
        margin-top: auto;
        padding-top: var(--space-3);
        border-top: 1px solid var(--border-color);
    }

    /* Search & Filter toolbar in all-teams */
    .team-toolbar-wrap {
        margin-bottom: var(--space-4);
    }

    .team-search-input-wrap {
        position: relative;
    }

    .team-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        pointer-events: none;
        font-size: 13px;
    }

    .team-search-input {
        width: 100%;
        height: 38px;
        padding-left: 34px;
        padding-right: 12px;
        font-size: 13px;
        color: var(--text-primary);
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        transition: border-color 150ms ease;
    }

    .team-search-input:focus {
        outline: none;
        border-color: var(--border-focus);
    }

    .team-sort-select {
        height: 38px;
        font-size: 13px;
        color: var(--text-primary);
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 0 12px;
        transition: border-color 150ms ease;
    }

    .team-sort-select:focus {
        outline: none;
        border-color: var(--border-focus);
    }

    /* ==========================================================================
       SIDEBAR CARDS (Top Scorers, Highlights, Recent Results)
       ========================================================================== */
    .scorer-row {
        display: flex;
        align-items: center;
        gap: var(--space-3);
        padding: var(--space-2) 0;
        border-bottom: 1px solid var(--border-color);
    }

    .scorer-row:last-child {
        border-bottom: none;
    }

    .scorer-rank {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        background-color: var(--surface-subtle);
        color: var(--text-secondary);
        flex-shrink: 0;
    }

    .scorer-rank.top-1 {
        background-color: rgba(217, 119, 6, 0.15);
        color: #b45309;
    }

    .scorer-rank.top-2 {
        background-color: rgba(100, 116, 139, 0.15);
        color: #475569;
    }

    .scorer-rank.top-3 {
        background-color: rgba(180, 83, 9, 0.1);
        color: #92400e;
    }

    .highlight-card {
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
        overflow: hidden;
        margin-bottom: var(--space-3);
        background-color: var(--surface);
    }

    .highlight-thumb-wrap {
        position: relative;
        height: 160px;
        background-color: #0f172a;
        cursor: pointer;
        overflow: hidden;
    }

    .highlight-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: opacity 150ms ease;
    }

    .highlight-thumb-wrap:hover .highlight-thumb-img {
        opacity: 0.9;
    }

    .play-btn-circle {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: rgba(15, 23, 42, 0.75);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        padding-left: 3px;
        backdrop-filter: blur(2px);
    }

    /* ==========================================================================
       RECENT RESULTS (Spacious 8-col layout)
       ========================================================================== */
    .recent-result-row {
        padding: var(--space-3) 0;
        border-bottom: 1px solid var(--border-color);
    }

    .recent-result-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .recent-match-scoreboard {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--space-3);
    }

    .recent-team-cell {
        display: flex;
        align-items: center;
        gap: var(--space-2);
        width: 42%;
        min-width: 0;
    }

    .recent-team-cell.home-team {
        justify-content: flex-end;
        text-align: right;
    }

    .recent-team-cell.away-team {
        justify-content: flex-start;
        text-align: left;
    }

    .recent-team-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .recent-score-center {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 90px;
        flex-shrink: 0;
    }

    .recent-score-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 4px 14px;
        font-size: 16px;
        font-weight: 800;
        color: var(--text-primary);
        background-color: var(--surface-subtle);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        letter-spacing: 1px;
    }

    .recent-score-divider {
        color: var(--text-secondary);
        font-weight: 400;
    }

    .recent-events-container {
        background-color: var(--surface-subtle);
        border-radius: var(--radius-sm);
        padding: var(--space-2) var(--space-3);
        border: 1px solid var(--border-color);
    }

    .recent-events-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .recent-event-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        color: var(--text-secondary);
        min-width: 0;
    }

    .recent-event-item .event-minute {
        color: var(--text-primary);
        font-size: 11px;
        flex-shrink: 0;
    }

    .recent-event-item .event-player {
        color: var(--text-secondary);
    }

    /* ==========================================================================
       MODALS & SQUAD VIEW (Adhering to Section 9 Modal Standards)
       ========================================================================== */
    .modal-content {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        background-color: var(--surface);
    }

    .modal-header {
        padding: var(--space-4);
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .modal-body {
        padding: var(--space-5);
    }

    .modal-footer {
        padding: var(--space-4);
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: var(--space-3);
    }

    /* Player detail inside modal */
    .player-squad-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: var(--space-3);
        max-height: 280px;
        overflow-y: auto;
        padding-right: var(--space-1);
    }

    .player-modal-card {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: var(--space-2);
        text-align: center;
        cursor: pointer;
        background-color: var(--surface);
        transition: all 150ms ease;
    }

    .player-modal-card:hover {
        border-color: var(--accent);
        background-color: var(--surface-subtle);
    }

    .player-modal-card.is-selected {
        border-color: var(--accent);
        background-color: var(--accent-subtle);
    }

    .player-modal-photo {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: var(--space-1);
        border: 1px solid var(--border-color);
        background-color: var(--surface-subtle);
    }

    .player-analytics-panel {
        background-color: var(--surface-subtle);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: var(--space-4);
        margin-bottom: var(--space-4);
    }

    .key-stat-cell {
        background-color: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: var(--space-2);
        text-align: center;
    }

    .key-stat-num {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .key-stat-lbl {
        font-size: 11px;
        color: var(--text-secondary);
    }

    .progress-bar-wrap {
        height: 6px;
        background-color: var(--border-color);
        border-radius: 3px;
        overflow: hidden;
        margin-top: 4px;
    }

    .progress-bar-fill {
        height: 100%;
        background-color: var(--accent);
        border-radius: 3px;
        transition: width 300ms ease;
    }

    /* ==========================================================================
       FOOTER
       ========================================================================== */
    .app-footer {
        background-color: var(--v3-surface);
        border-top: 1px solid var(--border-color);
        padding-top: var(--space-8);
        padding-bottom: var(--space-6);
        color: var(--text-secondary);
        font-size: 13px;
    }

    .footer-heading {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: var(--space-3);
    }

    .footer-nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-nav-list li {
        margin-bottom: var(--space-2);
    }

    .footer-nav-list a {
        color: var(--text-secondary);
    }

    .footer-nav-list a:hover {
        color: var(--accent);
    }

    .footer-bottom {
        border-top: 1px solid var(--border-color);
        margin-top: var(--space-6);
        padding-top: var(--space-4);
        font-size: 12px;
        display: flex;
        flex-direction: column;
        gap: var(--space-2);
        align-items: center;
        justify-content: space-between;
    }

    @media (min-width: 640px) {
        .footer-bottom {
            flex-direction: row;
        }
    }

    /* ==========================================================================
       ULTRA ANALYTICS ENGINE v3.0 — COMPONENT LAYER
       Telemetry HUD, Neon Titles, Glow Accents
       ========================================================================== */
    .v3-hud {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--v3-border);
        margin-bottom: 24px;
        font-size: 11px;
        font-weight: 800;
        color: var(--v3-text-muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .v3-hud-left,
    .v3-hud-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .v3-hud-sep {
        color: var(--v3-text-muted);
        opacity: 0.5;
    }

    .v3-hud-status {
        color: var(--v3-neon-green);
    }

    .v3-beacon {
        width: 8px;
        height: 8px;
        background: var(--v3-neon-green);
        border-radius: 50%;
        box-shadow: 0 0 12px var(--v3-neon-green);
        animation: v3BeaconPulse 1.2s infinite alternate;
        flex-shrink: 0;
    }

    @keyframes v3BeaconPulse {
        from { opacity: 0.3; transform: scale(0.8); }
        to { opacity: 1; transform: scale(1.2); }
    }

    .v3-title {
        font-weight: 900;
        font-style: italic;
        text-transform: uppercase;
        letter-spacing: -0.03em;
        text-shadow: 0 0 20px rgba(0, 255, 135, 0.35);
        color: var(--v3-text-main);
    }

    .v3-hud-tag {
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--v3-text-muted);
    }

    .v3-sc-val {
        font-weight: 900;
        font-style: italic;
        color: var(--v3-neon-green);
    }

    /* Neon glow hover for interactive cards */
    .team-item-card:hover,
    .highlight-card:hover {
        border-color: var(--v3-border-glow);
        box-shadow: 0 0 24px rgba(0, 255, 135, 0.12);
    }

    /* Live score chip — neon pink telemetry */
    .score-chip.live {
        border-color: rgba(255, 0, 85, 0.4);
        box-shadow: 0 0 12px rgba(255, 0, 85, 0.25);
    }

    /* Scrollbar — telemetry style */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: var(--v3-bg);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--v3-border);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--v3-neon-green);
    }

    /* Selection */
    ::selection {
        background: rgba(0, 255, 135, 0.25);
        color: var(--v3-text-main);
    }

    /* ==========================================================================
       TEAM SQUAD MODAL — ULTRA ANALYTICS ENGINE v3.0 THEME
       ========================================================================== */
    #teamDetailsModal .modal-content {
        background-color: var(--v3-surface);
        border: 1px solid var(--v3-border);
        color: var(--v3-text-main);
    }

    #teamDetailsModal .modal-header,
    #teamDetailsModal .modal-footer {
        border-color: var(--v3-border);
    }

    #teamDetailsModal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    #teamDetailsModal .player-squad-grid {
        background-image: radial-gradient(var(--v3-border) 1px, transparent 1px);
        background-size: 20px 20px;
        border-radius: var(--v3-radius-md, 12px);
        padding: 12px;
    }

    #teamDetailsModal .player-modal-card {
        background-color: var(--v3-card);
        border: 1px solid var(--v3-border);
        border-radius: var(--v3-radius-sm, 8px);
        color: var(--v3-text-main);
        transition: border-color 200ms ease, box-shadow 200ms ease;
    }

    #teamDetailsModal .player-modal-card:hover {
        border-color: var(--v3-border-glow);
        box-shadow: 0 0 12px rgba(0, 255, 135, 0.15);
    }

    #teamDetailsModal .player-modal-card.is-selected {
        border-color: var(--v3-neon-green);
        box-shadow: 0 0 15px rgba(0, 255, 135, 0.25);
    }

    #teamDetailsModal .player-modal-photo {
        border: 1px solid var(--v3-border);
        background-color: var(--v3-surface);
    }

    #teamDetailsModal .player-analytics-panel {
        background-color: var(--v3-card);
        border: 1px solid var(--v3-border);
        border-left: 3px solid var(--v3-neon-green);
        border-radius: var(--v3-radius-md, 12px);
        color: var(--v3-text-main);
    }

    #teamDetailsModal .key-stat-cell {
        background-color: var(--v3-surface);
        border: 1px solid var(--v3-border);
        border-radius: var(--v3-radius-sm, 8px);
    }

    #teamDetailsModal .key-stat-num {
        color: var(--v3-neon-green);
        font-weight: 900;
        font-style: italic;
    }

    #teamDetailsModal .key-stat-lbl {
        color: var(--v3-text-muted);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    #teamDetailsModal .progress-bar-wrap {
        background-color: var(--v3-surface);
        border: 1px solid var(--v3-border);
    }
</style>
