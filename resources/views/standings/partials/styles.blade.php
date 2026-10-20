<style>
    /* ==========================================================================
       STANDINGS PAGE — SPECIFIC STYLES
       Shared design tokens (--bg-page, --surface, --accent, spacing, radii)
       are inherited from the home styles included in the main entry file.
       ========================================================================== */

    /* Page Header — restrained editorial heading with a clear tournament control */
    .standings-page-header {
        padding: 0 0 var(--space-5);
        margin-bottom: var(--space-6);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--space-5);
        border-bottom: 1px solid var(--border-color);
    }

    .standings-page-heading {
        min-width: 0;
    }

    .standings-page-eyebrow {
        margin: 0 0 var(--space-1);
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 500;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .standings-page-header h1 {
        font-size: clamp(24px, 3vw, 32px);
        font-weight: 600;
        letter-spacing: -0.035em;
        color: var(--text-primary);
        margin: 0;
        line-height: 1.15;
    }

    .standings-tournament-info {
        font-size: 14px;
        color: var(--text-secondary);
        margin-top: var(--space-2);
    }

    /* Tournament Selector — neutral, accessible pill control */
    .standings-tournament-control {
        display: grid;
        gap: var(--space-1);
        min-width: min(280px, 42%);
        margin: 0;
    }

    .standings-tournament-label {
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 500;
    }

    .standings-tournament-select {
        width: 100%;
        min-height: 44px;
        background-color: var(--surface-subtle);
        border: 1px solid var(--border-color);
        border-radius: 9999px;
        padding: 10px 16px;
        color: var(--text-primary);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: border-color 150ms ease, background-color 150ms ease;
        appearance: auto;
    }

    .standings-tournament-select:hover {
        border-color: #cacacb;
        background-color: #eeeeee;
    }

    .standings-tournament-select:focus {
        outline: none;
        border-color: var(--text-primary);
        box-shadow: 0 0 0 2px rgba(17, 17, 17, 0.12);
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

    .standings-stat-icon.icon-accent {
        background-color: var(--accent);
    }

    .standings-stat-icon.icon-success {
        background-color: var(--success);
    }

    .standings-stat-icon.icon-warning {
        background-color: var(--warning);
    }

    .standings-stat-icon.icon-danger {
        background-color: var(--danger);
    }

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

    .standings-dot-win {
        background-color: var(--success);
    }

    .standings-dot-draw {
        background-color: var(--warning);
    }

    .standings-dot-loss {
        background-color: var(--danger);
    }

    .standings-dot-empty {
        background-color: var(--border-color);
    }

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

    /* Knockout bracket */
    .standings-bracket-champion-banner {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: var(--space-3);
        margin-bottom: var(--space-4);
        padding: var(--space-3) var(--space-4);
        border: 1px solid var(--success);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--text-primary);
        animation: bracket-champion-arrive 760ms cubic-bezier(.2, .75, .25, 1) both;
    }

    .standings-bracket-champion-banner::before {
        position: absolute;
        z-index: 0;
        top: 0;
        bottom: 0;
        left: -28%;
        width: 18%;
        background: rgba(255, 255, 255, 0.08);
        content: '';
        opacity: 0;
        pointer-events: none;
        transform: skewX(-22deg);
        animation: bracket-champion-sweep 1.05s 420ms ease-out both;
    }

    .standings-bracket-champion-banner::after {
        position: absolute;
        z-index: 0;
        top: -10px;
        right: 18%;
        width: 5px;
        height: 9px;
        border-radius: 1px;
        background: var(--warning);
        box-shadow:
            -31px 18px 0 -1px var(--success),
            -17px 39px 0 -1px var(--warning),
            13px 8px 0 -1px var(--success),
            29px 27px 0 -1px var(--warning),
            44px 46px 0 -1px var(--success),
            61px 13px 0 -1px var(--warning),
            78px 34px 0 -1px var(--success);
        opacity: 0;
        pointer-events: none;
        content: '';
        animation: bracket-champion-confetti 1.7s 180ms ease-out both;
    }

    .standings-bracket-champion-emblem {
        position: relative;
        z-index: 1;
        display: grid;
        align-items: center;
        justify-items: center;
        width: 46px;
        height: 46px;
        flex: 0 0 46px;
        border: 1px solid var(--warning);
        border-radius: 50%;
        background: var(--warning-subtle);
        color: var(--warning);
        font-size: 18px;
    }

    .standings-bracket-champion-emblem>i {
        animation: bracket-champion-trophy 700ms 160ms cubic-bezier(.2, .75, .25, 1) both;
    }

    .standings-bracket-champion-emblem::before {
        position: absolute;
        inset: -5px;
        border: 1px solid var(--warning);
        border-radius: inherit;
        content: '';
        opacity: 0;
        pointer-events: none;
        animation: bracket-champion-ripple 900ms 400ms ease-out both;
    }

    .standings-bracket-champion-emblem::after {
        position: absolute;
        top: -5px;
        right: -3px;
        color: var(--warning);
        content: '✦';
        font-size: 11px;
        animation: bracket-champion-spark 1.15s 470ms ease-out both;
    }

    .standings-bracket-champion-logo {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        border-radius: 50%;
        border: 1px solid var(--border-color);
        background: var(--surface-hover);
        padding: 5px;
        animation: bracket-champion-logo 620ms 260ms cubic-bezier(.2, .75, .25, 1) both;
    }

    .standings-bracket-champion-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .standings-bracket-champion-copy {
        position: relative;
        z-index: 1;
        display: flex;
        flex: 1;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .standings-bracket-champion-label {
        color: var(--success);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .7px;
        line-height: 1.3;
        animation: bracket-champion-copy 460ms 390ms ease-out both;
    }

    .standings-bracket-champion-copy>strong {
        overflow: hidden;
        color: var(--text-primary);
        font-size: 19px;
        font-weight: 900;
        letter-spacing: -.25px;
        line-height: 1.2;
        text-overflow: ellipsis;
        white-space: nowrap;
        animation: bracket-champion-copy 520ms 500ms ease-out both;
    }

    @keyframes bracket-champion-arrive {
        0% {
            opacity: 0;
            clip-path: inset(0 100% 0 0);
            transform: translateY(10px) scale(.985);
        }

        68% {
            opacity: 1;
            clip-path: inset(0 0 0 0);
        }

        100% {
            transform: translateY(0) scale(1);
        }
    }

    @keyframes bracket-champion-sweep {
        0% {
            opacity: 0;
            transform: translateX(0) skewX(-22deg);
        }

        18% {
            opacity: .8;
        }

        100% {
            opacity: 0;
            transform: translateX(760%) skewX(-22deg);
        }
    }

    @keyframes bracket-champion-confetti {
        0% {
            opacity: 0;
            transform: translateY(-5px) rotate(0);
        }

        14% {
            opacity: .9;
        }

        100% {
            opacity: 0;
            transform: translateY(74px) rotate(110deg);
        }
    }

    @keyframes bracket-champion-trophy {
        0% {
            opacity: 0;
            transform: translateY(5px) rotate(-14deg) scale(.76);
        }

        72% {
            opacity: 1;
            transform: translateY(-1px) rotate(3deg) scale(1.04);
        }

        100% {
            transform: translateY(0) rotate(0) scale(1);
        }
    }

    @keyframes bracket-champion-ripple {
        0% {
            opacity: .7;
            transform: scale(.72);
        }

        100% {
            opacity: 0;
            transform: scale(1.4);
        }
    }

    @keyframes bracket-champion-logo {
        from {
            opacity: 0;
            transform: scale(.82) rotate(-8deg);
        }

        to {
            opacity: 1;
            transform: scale(1) rotate(0);
        }
    }

    @keyframes bracket-champion-copy {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bracket-champion-card {

        0%,
        100% {
            box-shadow: inset 3px 0 0 var(--success);
        }

        42% {
            box-shadow: inset 3px 0 0 var(--success), 0 0 0 4px var(--success-subtle);
        }
    }

    @keyframes bracket-champion-row {
        0% {
            background: transparent;
        }

        52% {
            background: var(--success-subtle);
        }

        100% {
            background: var(--success-subtle);
        }
    }

    @keyframes bracket-champion-spark {
        0% {
            opacity: 0;
            transform: scale(.4) rotate(-25deg);
        }

        45% {
            opacity: 1;
            transform: scale(1.1) rotate(12deg);
        }

        100% {
            opacity: .35;
            transform: scale(.8) rotate(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .standings-bracket-champion-banner,
        .standings-bracket-champion-banner::before,
        .standings-bracket-champion-banner::after,
        .standings-bracket-champion-emblem::before,
        .standings-bracket-champion-emblem>i,
        .standings-bracket-champion-emblem::after,
        .standings-bracket-champion-logo,
        .standings-bracket-champion-label,
        .standings-bracket-champion-copy>strong,
        .standings-bracket-match.is-champion-decided,
        .standings-bracket-team.is-champion {
            animation: none;
        }
    }

    .standings-bracket-scroller {
        overflow-x: auto;
        padding-bottom: var(--space-3);
        scroll-snap-type: x proximity;
        scrollbar-color: var(--border-color) var(--surface);
    }

    .standings-bracket-board {
        position: relative;
        isolation: isolate;
        display: flex;
        align-items: stretch;
        gap: var(--space-6, 24px);
        width: max-content;
        min-width: 100%;
    }

    .standings-bracket-connections {
        position: absolute;
        inset: 0;
        z-index: 0;
        width: 100%;
        height: 100%;
        overflow: visible;
        pointer-events: none;
    }

    .standings-bracket-connector-path {
        fill: none;
        stroke: var(--border-focus, var(--border-color));
        stroke-width: 1.75;
        stroke-linecap: round;
        stroke-linejoin: round;
        opacity: .55;
    }

    .standings-bracket-connector-junction {
        fill: var(--surface);
        stroke: var(--border-focus, var(--border-color));
        stroke-width: 1.5;
        opacity: .7;
    }

    .standings-bracket-round {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        flex: 0 0 260px;
        width: 260px;
        scroll-snap-align: start;
    }

    .standings-bracket-round-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--space-2);
        min-height: 42px;
        margin-bottom: var(--space-3);
        padding: var(--space-2) var(--space-3);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: var(--surface);
        color: var(--text-primary);
        font-size: 13px;
        font-weight: 700;
    }

    .standings-bracket-round-header small {
        color: var(--text-secondary);
        font-size: 11px;
        font-weight: 500;
        white-space: nowrap;
    }

    .standings-bracket-round.is-final .standings-bracket-round-header {
        border-color: var(--accent);
    }

    .standings-bracket-matches {
        display: flex;
        flex: 1;
        flex-direction: column;
        justify-content: space-around;
        gap: var(--space-3);
    }

    .standings-bracket-match {
        position: relative;
        padding: var(--space-3);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: var(--surface);
    }

    .standings-bracket-match.is-champion-decided {
        border-color: var(--success);
        box-shadow: inset 3px 0 0 var(--success);
        animation: bracket-champion-card 1s 1.05s ease-out both;
    }

    .standings-bracket-match-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--space-2);
        margin-bottom: var(--space-2);
        color: var(--text-secondary);
        font-size: 11px;
    }

    .standings-bracket-status {
        padding: 2px 7px;
        border-radius: 999px;
        background: var(--surface-hover);
        color: var(--text-secondary);
        font-size: 10px;
        font-weight: 600;
        white-space: nowrap;
    }

    .standings-bracket-status.is-completed {
        background: var(--success-subtle);
        color: var(--success);
    }

    .standings-bracket-status.is-bye {
        background: var(--success-subtle);
        color: var(--success);
    }

    .standings-bracket-status.is-ongoing {
        background: var(--warning-subtle);
        color: var(--warning);
    }

    .standings-bracket-team {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--space-2);
        min-height: 30px;
        color: var(--text-primary);
        font-size: 13px;
    }

    .standings-bracket-identity {
        display: flex;
        align-items: center;
        flex: 1;
        gap: var(--space-2);
        min-width: 0;
    }

    .standings-bracket-logo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        width: 24px;
        height: 24px;
        flex: 0 0 24px;
        border-radius: 50%;
        color: var(--text-secondary);
        font-size: 13px;
    }

    .standings-bracket-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .standings-bracket-identity>span:last-child {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .standings-bracket-team+.standings-bracket-team {
        border-top: 1px solid var(--border-color);
    }

    .standings-bracket-team.is-winner {
        font-weight: 700;
    }

    .standings-bracket-team.is-champion {
        padding: 2px 5px;
        border-radius: 4px;
        background: var(--success-subtle);
        color: var(--success);
        animation: bracket-champion-row 900ms 1.28s ease-out both;
    }

    .standings-bracket-score {
        min-width: 20px;
        color: var(--text-primary);
        font-variant-numeric: tabular-nums;
        font-weight: 700;
        text-align: right;
    }

    .standings-bracket-placement {
        flex: 0 0 auto;
        padding: 2px 5px;
        border-radius: 4px;
        background: var(--success-subtle);
        color: var(--success);
        font-size: 10px;
        font-weight: 700;
        line-height: 1.2;
        white-space: nowrap;
    }

    .standings-bracket-placement.is-champion {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 7px;
        border-radius: 999px;
        letter-spacing: .2px;
    }

    .standings-bracket-extras {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: var(--space-3);
        margin-top: var(--space-2);
        padding-top: var(--space-2);
        border-top: 1px solid var(--border-color);
    }

    .standings-bracket-extra {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: var(--text-secondary);
        font-size: 11px;
    }

    .standings-bracket-extra strong {
        color: var(--text-primary);
        font-size: 11px;
        font-variant-numeric: tabular-nums;
    }

    .standings-bracket-extra.is-penalty {
        color: var(--accent);
        font-weight: 600;
    }

    .standings-bracket-extra.is-penalty strong {
        color: var(--text-primary);
    }

    .standings-bracket-empty {
        min-height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: var(--space-3);
        border: 1px dashed var(--border-color);
        border-radius: var(--radius-sm);
        color: var(--text-secondary);
        font-size: 12px;
        text-align: center;
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

        .standings-tournament-control {
            width: 100%;
            min-width: 0;
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

        .standings-bracket-champion-banner {
            gap: var(--space-2);
            padding: var(--space-2) var(--space-3);
        }

        .standings-bracket-champion-emblem {
            width: 38px;
            height: 38px;
            flex-basis: 38px;
            font-size: 15px;
        }

        .standings-bracket-champion-logo {
            width: 42px;
            height: 42px;
            flex-basis: 42px;
        }

        .standings-bracket-champion-copy>strong {
            font-size: 15px;
        }

        .standings-group-header {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-2);
        }

        .standings-bracket-round {
            flex-basis: 230px;
            width: 230px;
        }
    }
</style>