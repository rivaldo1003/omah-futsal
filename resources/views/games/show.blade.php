<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Details - {{ $game->homeTeam->name ?? 'Home' }} vs {{ $game->awayTeam->name ?? 'Away' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800;900&family=IBM+Plex+Mono:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    /* ==========================================================================
       ULTRA ANALYTICS ENGINE v3.0 — MATCH DETAILS
       Adheres to ultra_analytics_engine_v3_0_design_system.md
       ========================================================================== */
    :root {
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
        --v3-font-sans: 'IBM Plex Sans', sans-serif;
        --v3-font-display: 'Barlow Condensed', sans-serif;
        --v3-font-mono: 'IBM Plex Mono', monospace;
        --v3-radius-lg: 20px;
        --v3-radius-md: 12px;
        --v3-radius-sm: 8px;
    }

    body {
        background-color: var(--v3-bg);
        font-family: var(--v3-font-sans);
        color: var(--v3-text-main);
        line-height: 1.5;
    }

    ::selection { background: rgba(0, 255, 135, 0.25); color: var(--v3-text-main); }

    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: var(--v3-bg); }
    ::-webkit-scrollbar-thumb { background: var(--v3-border); border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--v3-neon-green); }

    /* ===== Hero ===== */
    .match-hero {
        background: var(--v3-surface);
        border-bottom: 1px solid var(--v3-border);
        color: var(--v3-text-main);
        padding: 3rem 0 2.5rem;
        margin-bottom: 2rem;
    }
    .match-title {
        font-family: var(--v3-font-display);
        font-weight: 900;
        font-style: italic;
        text-transform: uppercase;
        letter-spacing: -0.03em;
        font-size: 2.5rem;
    }
    .match-subtitle { font-size: 1.1rem; color: var(--v3-text-sub); }

    .back-button {
        position: absolute;
        top: 1.5rem;
        left: 1.5rem;
        background: rgba(13, 21, 36, 0.9);
        border: 1px solid var(--v3-border);
        border-radius: var(--v3-radius-sm);
        padding: 0.5rem 1rem;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--v3-neon-green);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: background-color 160ms ease-out, border-color 160ms ease-out, color 160ms ease-out;
    }
    .back-button:hover {
        border-color: var(--v3-border-glow);
        background: var(--v3-card);
        color: var(--v3-neon-green);
        text-decoration: none;
    }
    .back-button:focus-visible {
        outline: 2px solid var(--v3-neon-green);
        outline-offset: 3px;
    }

    /* ===== Cards ===== */
    .card {
        border: 1px solid var(--v3-border);
        border-radius: var(--v3-radius-md);
        overflow: hidden;
        margin-bottom: 1.5rem;
        background: var(--v3-surface);
    }
    .card-header {
        background: var(--v3-card);
        border-bottom: 1px solid var(--v3-border);
        padding: 1rem 1.5rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--v3-text-main);
        font-size: 0.95rem;
        display: flex;
        align-items: center;
    }
    .card-header i { margin-right: 10px; color: var(--v3-neon-green); }
    .card-body { padding: 1.5rem; }

    /* ===== Score Card ===== */
    .score-card {
        background: var(--v3-surface);
        border: 1px solid var(--v3-border);
        border-radius: var(--v3-radius-lg);
        padding: 2rem;
    }
    .team-logo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid var(--v3-border);
        background: var(--v3-card);
    }
    .team-name {
        font-family: var(--v3-font-display);
        font-weight: 900;
        text-transform: uppercase;
        font-size: 1.3rem;
        margin-top: 1rem;
        color: var(--v3-text-main);
    }
    .team-city { font-size: 0.9rem; color: var(--v3-text-sub); margin-top: 0.25rem; }

    .score-display {
        font-size: 4rem;
        font-weight: 900;
        color: var(--v3-neon-green);
        line-height: 1;
        font-family: var(--v3-font-mono);
    }
    .score-display.live {
        color: var(--v3-neon-pink);
    }
    .match-status { font-size: 1rem; font-weight: 800; margin-top: 0.5rem; display: block; }
    .status-badge {
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        font-weight: 800;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .status-badge.bg-success {
        background: rgba(0, 255, 135, 0.12) !important;
        color: var(--v3-neon-green) !important;
        border: 1px solid var(--v3-border-glow);
    }
    .status-badge.bg-danger {
        background: rgba(255, 0, 85, 0.12) !important;
        color: var(--v3-neon-pink) !important;
        border: 1px solid rgba(255, 0, 85, 0.4);
    }
    .status-badge.bg-secondary {
        background: rgba(77, 97, 120, 0.15) !important;
        color: var(--v3-text-sub) !important;
        border: 1px solid var(--v3-border);
    }
    .badge.bg-info {
        background: rgba(0, 229, 255, 0.1) !important;
        color: var(--v3-neon-blue) !important;
        border: 1px solid rgba(0, 229, 255, 0.35);
    }

    /* ===== Match Info ===== */
    .match-info-item { padding: 0.75rem 0; }
    .match-info-content h6 {
        margin: 0;
        font-weight: 800;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--v3-text-sub);
    }
    .match-info-content p { margin: 0; font-size: 0.95rem; color: var(--v3-text-main); }

    /* ===== Timeline ===== */
    .timeline-container { display: flex; flex-direction: column; gap: 0; }
    .timeline-item {
        position: relative;
        display: grid;
        grid-template-columns: 42px 32px minmax(0, 1fr);
        column-gap: 8px;
        align-items: start;
        margin: 0;
    }
    .timeline-item:not(:last-child)::after {
        position: absolute;
        top: 32px;
        bottom: 0;
        left: 66px;
        width: 1px;
        background: var(--v3-border);
        content: '';
        pointer-events: none;
    }
    .timeline-marker {
        position: relative;
        z-index: 2;
        grid-column: 2;
        grid-row: 1;
        display: flex;
        width: 32px;
        height: 32px;
        align-items: center;
        justify-content: center;
        background: var(--v3-bg);
    }
    .timeline-marker .badge {
        display: flex;
        width: 28px;
        height: 28px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid var(--v3-border);
    }
    .timeline-content {
        grid-column: 3;
        grid-row: 1;
        min-width: 0;
        padding: 10px 0;
        border-bottom: 1px solid var(--v3-border);
        color: var(--v3-text-main);
    }
    .timeline-item:last-child .timeline-content { border-bottom: 0; }
    .timeline-minute {
        grid-column: 1;
        grid-row: 1;
        width: 100%;
        margin-top: 9px;
        line-height: 1;
        font-weight: 900;
        color: var(--v3-neon-green);
        font-size: 0.78rem;
        font-family: var(--v3-font-mono);
        text-align: right;
        white-space: nowrap;
    }
    .timeline-event-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }
    .timeline-player { min-width: 0; overflow-wrap: anywhere; }
    .timeline-player strong { font-size: 0.83rem; }
    .timeline-event-type { margin-left: 6px; color: var(--v3-text-sub); font-size: 0.72rem; }
    .timeline-team-badge {
        flex: 0 0 auto;
        max-width: 40%;
        overflow: hidden;
        padding: 3px 7px;
        border: 1px solid var(--v3-border);
        border-radius: 4px;
        color: var(--v3-text-sub);
        font-size: 8px;
        font-weight: 800;
        letter-spacing: 0.7px;
        line-height: 1.2;
        text-transform: uppercase;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .timeline-team-badge.is-home { border-color: var(--v3-border-glow); color: var(--v3-neon-green); }
    .timeline-team-badge.is-away { border-color: rgba(255, 0, 85, 0.4); color: var(--v3-neon-pink); }
    .timeline-event-details { display: flex; flex-wrap: wrap; align-items: center; gap: 4px 10px; margin-top: 3px; }
    .timeline-event-detail { color: var(--v3-text-sub); font-size: 0.7rem; }
    .timeline-event-detail.is-penalty { color: var(--v3-neon-yellow); }
    .timeline-event-detail.is-own-goal { color: var(--v3-neon-pink); }
    .timeline-description { margin: 5px 0 0; color: var(--v3-text-sub); font-size: 0.8rem; line-height: 1.4; }
    .timeline-event-assist { color: var(--v3-text-sub); font-size: 0.72rem; }
    .timeline-event-assist strong { color: var(--v3-text-sub); font-weight: 600; }

    /* ===== Statistics ===== */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
    .stat-item {
        text-align: center;
        padding: 1rem;
        background: var(--v3-card);
        border: 1px solid var(--v3-border);
        border-radius: var(--v3-radius-sm);
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 900;
        line-height: 1;
        font-family: var(--v3-font-mono);
    }
    .stat-label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--v3-text-sub);
        margin-top: 0.5rem;
    }
    .stat-value.text-primary { color: var(--v3-neon-green) !important; }
    .stat-value.text-warning { color: var(--v3-neon-yellow) !important; }
    .stat-value.text-danger { color: var(--v3-neon-pink) !important; }

    /* ===== Section Title & Empty State ===== */
    .section-title {
        color: var(--v3-neon-green);
        font-weight: 800;
        font-style: italic;
        text-transform: uppercase;
        font-size: 1rem;
        letter-spacing: 0.5px;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--v3-border);
    }
    .empty-state { text-align: center; padding: 3rem 1rem; }
    .empty-state i { font-size: 3rem; color: var(--v3-text-sub); margin-bottom: 1rem; }
    .empty-state p { color: var(--v3-text-sub); margin: 0; }

    .text-muted { color: var(--v3-text-sub) !important; }
    .bg-light { background-color: var(--v3-card) !important; }
    .border-top { border-color: var(--v3-border) !important; }
    .border-bottom { border-color: var(--v3-border) !important; }

    /* ===== Compact match center ===== */
    .match-hero {
        padding: 14px 0;
        margin-bottom: 16px;
    }
    .match-hero .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }
    .match-hero-copy { min-width: 0; }
    .match-hud {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
        color: var(--v3-text-sub);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .match-title {
        font-family: var(--v3-font-display);
        margin: 0;
        font-size: 1.55rem;
        line-height: 1.1;
    }
    .match-subtitle { margin: 4px 0 0; font-size: 0.82rem; }
    .back-button {
        position: static;
        flex: 0 0 auto;
        padding: 8px 12px;
        font-size: 10px;
    }
    .main-container { max-width: 1240px; padding-bottom: 28px; }
    .score-card {
        padding: 16px 18px;
        border-radius: var(--v3-radius-md);
    }
    .score-card > .row { --bs-gutter-x: 0.75rem; }
    .score-team { min-width: 0; }
    .score-team-block { margin-bottom: 0 !important; }
    .team-logo {
        width: 58px;
        height: 58px;
        border-width: 1px;
        object-fit: contain;
    }
    .team-name {
        margin-top: 7px;
        font-size: 1rem;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }
    .team-city { margin-top: 2px; font-size: 0.75rem; }
    .scoreline {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
    }
    .score-display {
        display: inline-block;
        font-size: 2.65rem;
    }
    .score-display.score-separator {
        color: var(--v3-text-sub);
        font-size: 1.7rem;
    }
    .match-status { margin-top: 7px; font-size: 0.75rem; }
    .status-badge { padding: 5px 10px; font-size: 0.67rem; letter-spacing: 0.8px; }
    .match-details {
        margin-top: 14px;
        padding-top: 8px;
        border-top: 1px solid var(--v3-border);
    }
    .card {
        margin-bottom: 12px;
        border-radius: var(--v3-radius-sm);
    }
    .card-header { gap: 8px; padding: 10px 14px; font-family: var(--v3-font-display); font-size: 0.9rem; }
    .card-header i { margin-right: 0; }
    .card-body { padding: 14px; }
    .match-info-grid { row-gap: 0; }
    .match-info-item { min-height: 52px; padding: 7px 10px; border-right: 1px solid var(--v3-border); }
    .match-info-grid > [class*="col-"]:nth-child(2n) .match-info-item { border-right: 0; }
    .match-info-content h6 { font-size: 0.68rem; letter-spacing: 0.08em; }
    .match-info-content p { font-size: 0.84rem; line-height: 1.35; }
    .match-notes { margin-top: 10px; padding-top: 10px; }
    .tournament-context { margin-top: 10px; padding-top: 10px; color: var(--v3-text-sub); font-size: 0.78rem; }
    .section-title { margin-bottom: 8px; padding-bottom: 6px; font-size: 0.75rem; }
    .lineup-card .card-header { justify-content: space-between; }
    .lineup-caption { margin-left: auto; color: var(--v3-text-sub); font-size: 10px; font-weight: 600; }
    .lineup-team { min-width: 0; }
    .lineup-team-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 8px;
    }
    .lineup-team-name { display: flex; align-items: center; gap: 8px; min-width: 0; }
    .lineup-team-name img,
    .lineup-team-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        flex: 0 0 28px;
        border: 1px solid var(--v3-border);
        border-radius: 50%;
        background: var(--v3-card);
        object-fit: contain;
    }
    .lineup-team-fallback { color: var(--v3-text-sub); font-size: 12px; }
    .lineup-team-name strong {
        overflow: hidden;
        color: var(--v3-text-main);
        font-size: 0.82rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .lineup-team-count { color: var(--v3-text-sub); font-size: 0.72rem; white-space: nowrap; }
    .lineup-team-note { margin: -4px 0 7px; color: var(--v3-text-sub); font-size: 0.72rem; }
    .lineup-pitch {
        position: relative;
        display: flex;
        min-height: 246px;
        flex-direction: column;
        justify-content: space-around;
        gap: 8px;
        overflow: hidden;
        padding: 15px 12px;
        border: 1px solid var(--v3-border);
        border-radius: var(--v3-radius-sm);
        background: var(--v3-card);
    }
    .lineup-pitch::before {
        position: absolute;
        inset: 8px;
        border: 1px solid var(--v3-border);
        border-radius: 5px;
        content: '';
        pointer-events: none;
    }
    .lineup-pitch::after {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 62px;
        height: 62px;
        border: 1px solid var(--v3-border);
        border-radius: 50%;
        content: '';
        transform: translate(-50%, -50%);
        pointer-events: none;
    }
    .lineup-lane { position: relative; z-index: 1; display: flex; align-items: center; gap: 8px; }
    .lineup-role {
        width: 44px;
        flex: 0 0 44px;
        color: var(--v3-text-sub);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.8px;
        text-align: right;
        text-transform: uppercase;
    }
    .lineup-lane-players { display: flex; flex: 1; flex-wrap: wrap; justify-content: center; gap: 8px 6px; min-width: 0; }
    .lineup-player { display: flex; width: 68px; flex-direction: column; align-items: center; gap: 3px; text-align: center; }
    .lineup-avatar {
        position: relative;
        display: flex;
        width: 38px;
        height: 38px;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px solid var(--v3-border);
        border-radius: 50%;
        background: var(--v3-surface);
        color: var(--v3-neon-green);
        font-size: 0.8rem;
        font-weight: 900;
    }
    .lineup-avatar img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .lineup-number {
        position: absolute;
        right: -2px;
        bottom: -2px;
        display: flex;
        min-width: 15px;
        height: 15px;
        align-items: center;
        justify-content: center;
        padding: 0 3px;
        border: 1px solid var(--v3-neon-green);
        border-radius: 10px;
        background: var(--v3-bg);
        color: var(--v3-neon-green);
        font-family: var(--v3-font-mono);
        font-size: 8px;
        font-weight: 800;
    }
    .lineup-player-name {
        max-width: 68px;
        overflow: hidden;
        color: var(--v3-text-main);
        font-size: 10px;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .lineup-player-position { color: var(--v3-text-sub); font-size: 9px; line-height: 1.1; }
    .stats-sides {
        display: grid;
        grid-template-columns: 1fr auto auto;
        gap: 12px;
        margin: 0 10px 4px;
        color: var(--v3-text-sub);
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .stats-sides span:nth-child(2),
    .stats-sides span:nth-child(3) { width: 24px; text-align: center; }
    .lineup-empty {
        position: relative;
        z-index: 1;
        display: flex;
        min-height: 210px;
        align-items: center;
        justify-content: center;
        color: var(--v3-text-sub);
        font-size: 0.8rem;
        text-align: center;
    }
    .stats-grid { grid-template-columns: 1fr; gap: 6px; }
    .stat-item {
        display: grid;
        grid-template-columns: 1fr auto auto;
        align-items: center;
        gap: 12px;
        padding: 8px 10px;
        background: transparent;
        border: 0;
        border-bottom: 1px solid var(--v3-border);
        border-radius: 0;
        text-align: left;
    }
    .stat-item:last-child { border-bottom: 0; }
    .stat-value { font-size: 1.1rem; }
    .stat-label { grid-column: 1; grid-row: 1; margin: 0; font-size: 0.68rem; text-align: left; }
    .stat-item .stat-value:nth-child(2) { grid-column: 2; grid-row: 1; }
    .stat-item .stat-value:nth-child(3) { grid-column: 3; grid-row: 1; }
    .timeline-container { padding-left: 0; }
    .empty-state { padding: 22px 12px; }
    .empty-state i { margin-bottom: 6px; font-size: 1.7rem; }

    /* ===== Responsive ===== */
    @media (min-width: 992px) {
        .match-info-grid > [class*="col-"]:nth-child(2n) .match-info-item { border-right: 1px solid var(--v3-border); }
        .match-info-grid > [class*="col-"]:last-child .match-info-item { border-right: 0; }
    }
    @media (max-width: 900px) {
        .score-card { padding: 14px; }
        .team-logo { width: 50px; height: 50px; }
        .score-display { font-size: 2.25rem; }
        .lineup-pitch { min-height: 230px; }
    }
    @media (max-width: 576px) {
        .match-hero .container { gap: 8px; }
        .match-title { font-size: 1.25rem; }
        .match-subtitle { font-size: 0.72rem; }
        .back-button { padding: 7px 9px; font-size: 9px; }
        .score-card { padding: 12px 8px; }
        .score-card > .row { --bs-gutter-x: 0.25rem; }
        .team-logo { width: 42px; height: 42px; }
        .team-name { font-size: 0.78rem; }
        .team-city { font-size: 0.65rem; }
        .scoreline { gap: 3px; }
        .score-display { font-size: 1.7rem; }
        .score-display.score-separator { font-size: 1rem; }
        .status-badge { padding: 4px 6px; font-size: 0.55rem; letter-spacing: 0.3px; }
        .match-status { font-size: 0.65rem; }
        .match-info-grid > [class*="col-"] { width: 50%; }
        .lineup-card .card-body { padding: 10px; }
        .lineup-pitch { min-height: 205px; padding: 12px 8px; }
        .lineup-role { width: 36px; flex-basis: 36px; font-size: 9px; }
        .lineup-lane-players { gap: 7px 3px; }
        .lineup-player { width: 58px; }
        .lineup-player-name { max-width: 58px; font-size: 9px; }
        .lineup-avatar { width: 34px; height: 34px; }
        .timeline-item:not(:last-child)::after { top: 28px; bottom: 0; left: 56px; }
        .timeline-item { grid-template-columns: 36px 28px minmax(0, 1fr); column-gap: 6px; }
        .timeline-marker { width: 28px; height: 28px; }
        .timeline-marker .badge { width: 24px; height: 24px; font-size: 0.7rem; }
        .timeline-minute { margin-top: 8px; font-size: 0.66rem; }
        .timeline-content { padding: 8px 0; }
        .timeline-player strong { font-size: 0.8rem; }
        .timeline-event-type { display: block; margin: 2px 0 0; }
        .timeline-team-badge { padding: 3px 5px; font-size: 9px; }
        .timeline-event-detail { font-size: 0.72rem; }
        .timeline-description { font-size: 0.78rem; }
        .card-header { padding: 9px 11px; }
        .card-body { padding: 11px; }
    }
    </style>
</head>

<body>
    <header class="match-hero">
        <div class="container">
            <div class="match-hero-copy">
                <div class="match-hud">
                    <span>Match Center</span>
                    <span>/</span>
                    <span>{{ ucfirst($game->status) }}</span>
                </div>
                <h1 class="match-title">Match Details</h1>
                <p class="match-subtitle">{{ $game->tournament->name ?? 'Futsal Match' }}</p>
            </div>
            <a href="{{ route('schedule') }}" class="back-button">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                <span>Schedule</span>
            </a>
        </div>
    </header>

    <main class="container main-container">
        <section class="score-card mb-3" aria-label="Match score">
            <div class="row align-items-center">
                <div class="col-5 score-team text-center">
                    <div class="score-team-block">
                        @if($game->homeTeam?->logo)
                            <img src="{{ asset('storage/' . $game->homeTeam->logo) }}" alt="" class="team-logo">
                        @else
                            <span class="team-logo bg-light d-inline-flex align-items-center justify-content-center" aria-hidden="true">
                                <i class="bi bi-shield-fill text-muted"></i>
                            </span>
                        @endif
                        <div class="team-name">{{ $game->homeTeam->name ?? 'Home Team' }}</div>
                        @if($game->homeTeam?->city)
                            <div class="team-city">{{ $game->homeTeam->city }}</div>
                        @endif
                    </div>
                </div>

                <div class="col-2 text-center">
                    <div class="scoreline" aria-label="Score">
                        @if($game->status === 'completed' || $game->status === 'ongoing')
                            <span class="score-display {{ $game->status === 'ongoing' ? 'live' : '' }}">{{ $game->home_score ?? 0 }}</span>
                            <span class="score-display score-separator">:</span>
                            <span class="score-display {{ $game->status === 'ongoing' ? 'live' : '' }}">{{ $game->away_score ?? 0 }}</span>
                        @else
                            <span class="score-display">VS</span>
                        @endif
                    </div>
                    @if($game->status === 'completed')
                        <span class="match-status badge bg-success status-badge"><i class="bi bi-check-circle me-1" aria-hidden="true"></i>Full time</span>
                    @elseif($game->status === 'ongoing')
                        <span class="match-status badge bg-danger status-badge"><i class="bi bi-play-circle me-1" aria-hidden="true"></i>Live</span>
                    @elseif($game->status === 'postponed')
                        <span class="match-status badge bg-secondary status-badge"><i class="bi bi-pause-circle me-1" aria-hidden="true"></i>Postponed</span>
                    @else
                        <span class="match-status badge bg-secondary status-badge"><i class="bi bi-clock me-1" aria-hidden="true"></i>Upcoming</span>
                    @endif
                </div>

                <div class="col-5 score-team text-center">
                    <div class="score-team-block">
                        @if($game->awayTeam?->logo)
                            <img src="{{ asset('storage/' . $game->awayTeam->logo) }}" alt="" class="team-logo">
                        @else
                            <span class="team-logo bg-light d-inline-flex align-items-center justify-content-center" aria-hidden="true">
                                <i class="bi bi-shield-fill text-muted"></i>
                            </span>
                        @endif
                        <div class="team-name">{{ $game->awayTeam->name ?? 'Away Team' }}</div>
                        @if($game->awayTeam?->city)
                            <div class="team-city">{{ $game->awayTeam->city }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="match-details" aria-label="Match information">
                <div class="row match-info-grid">
                    <div class="col-6 col-lg-3">
                        <div class="match-info-item">
                            <div class="match-info-content">
                                <h6>Date</h6>
                                <p>{{ $game->match_date->format('l, d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="match-info-item">
                            <div class="match-info-content">
                                <h6>Time</h6>
                                <p>{{ date('H:i', strtotime($game->time_start)) }} – {{ date('H:i', strtotime($game->time_end)) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="match-info-item">
                            <div class="match-info-content">
                                <h6>Venue</h6>
                                <p>{{ $game->venue ?? 'Main Field' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="match-info-item">
                            <div class="match-info-content">
                                <h6>Stage</h6>
                                <p>{{ $game->round_type === 'group' ? 'Group Stage · ' . ($game->group_name ?? 'Group') : ucfirst(str_replace('_', ' ', $game->round_type ?? 'Match')) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($game->notes)
                    <div class="match-notes border-top">
                        <h2 class="section-title">Match Notes</h2>
                        <p class="mb-0">{{ $game->notes }}</p>
                    </div>
                @endif

                @if($game->tournament)
                    <div class="tournament-context border-top mt-2 pt-2 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <span>
                            {{ $game->tournament->start_date?->format('d M Y') }} – {{ $game->tournament->end_date?->format('d M Y') }}
                        </span>
                        <span class="tournament-status">{{ ucfirst($game->tournament->status) }}</span>
                    </div>
                @endif
            </div>
        </section>

        <section class="card lineup-card" aria-labelledby="lineup-title">
            <div class="card-header" id="lineup-title">
                <i class="bi bi-diagram-3" aria-hidden="true"></i>
                <span>Match Lineup</span>
                <span class="lineup-caption">Players arranged by position</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <section class="lineup-team" aria-label="{{ $game->homeTeam->name ?? 'Home Team' }} lineup">
                            <div class="lineup-team-heading">
                                <div class="lineup-team-name">
                                    @if($game->homeTeam?->logo)
                                        <img src="{{ asset('storage/' . $game->homeTeam->logo) }}" alt="" loading="lazy">
                                    @else
                                        <span class="lineup-team-fallback" aria-hidden="true"><i class="bi bi-shield-fill"></i></span>
                                    @endif
                                    <strong>{{ $game->homeTeam->name ?? 'Home Team' }}</strong>
                                </div>
                                <span class="lineup-team-count">{{ $homeLineupPlayers->count() }} players</span>
                            </div>
                            @unless($homeLineupIsSet)
                                <p class="lineup-team-note">Showing full squad; match lineup has not been selected.</p>
                            @endunless
                            <div class="lineup-pitch">
                                @forelse($homeLineupRows as $role => $players)
                                    <div class="lineup-lane">
                                        <span class="lineup-role">{{ $role }}</span>
                                        <div class="lineup-lane-players">
                                            @foreach($players as $player)
                                                <div class="lineup-player" title="{{ $player->name }} — {{ $player->position ?? $role }}">
                                                    <span class="lineup-avatar">
                                                        <span>{{ $player->initial }}</span>
                                                        @if($player->photo_url)
                                                            <img src="{{ $player->photo_url }}" alt="" loading="lazy" onerror="this.remove()">
                                                        @endif
                                                        @if($player->jersey_number)
                                                            <span class="lineup-number">{{ $player->jersey_number }}</span>
                                                        @endif
                                                    </span>
                                                    <span class="lineup-player-name">{{ $player->name }}</span>
                                                    @if($player->position)
                                                        <span class="lineup-player-position">{{ $player->position }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="lineup-empty">No players available for this team.</div>
                                @endforelse
                            </div>
                        </section>
                    </div>

                    <div class="col-lg-6">
                        <section class="lineup-team" aria-label="{{ $game->awayTeam->name ?? 'Away Team' }} lineup">
                            <div class="lineup-team-heading">
                                <div class="lineup-team-name">
                                    @if($game->awayTeam?->logo)
                                        <img src="{{ asset('storage/' . $game->awayTeam->logo) }}" alt="" loading="lazy">
                                    @else
                                        <span class="lineup-team-fallback" aria-hidden="true"><i class="bi bi-shield-fill"></i></span>
                                    @endif
                                    <strong>{{ $game->awayTeam->name ?? 'Away Team' }}</strong>
                                </div>
                                <span class="lineup-team-count">{{ $awayLineupPlayers->count() }} players</span>
                            </div>
                            @unless($awayLineupIsSet)
                                <p class="lineup-team-note">Showing full squad; match lineup has not been selected.</p>
                            @endunless
                            <div class="lineup-pitch">
                                @forelse($awayLineupRows as $role => $players)
                                    <div class="lineup-lane">
                                        <span class="lineup-role">{{ $role }}</span>
                                        <div class="lineup-lane-players">
                                            @foreach($players as $player)
                                                <div class="lineup-player" title="{{ $player->name }} — {{ $player->position ?? $role }}">
                                                    <span class="lineup-avatar">
                                                        <span>{{ $player->initial }}</span>
                                                        @if($player->photo_url)
                                                            <img src="{{ $player->photo_url }}" alt="" loading="lazy" onerror="this.remove()">
                                                        @endif
                                                        @if($player->jersey_number)
                                                            <span class="lineup-number">{{ $player->jersey_number }}</span>
                                                        @endif
                                                    </span>
                                                    <span class="lineup-player-name">{{ $player->name }}</span>
                                                    @if($player->position)
                                                        <span class="lineup-player-position">{{ $player->position }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="lineup-empty">No players available for this team.</div>
                                @endforelse
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>

        <div class="row g-3">
            @if($game->status === 'completed' || $game->status === 'ongoing')
                <div class="col-lg-4">
                    <section class="card" aria-labelledby="stats-title">
                        <div class="card-header" id="stats-title">
                            <i class="bi bi-bar-chart" aria-hidden="true"></i> Match Statistics
                        </div>
                        <div class="card-body">
                            <div class="stats-sides" aria-hidden="true">
                                <span></span>
                                <span>Home</span>
                                <span>Away</span>
                            </div>
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <span class="stat-label">Goals</span>
                                    <span class="stat-value text-primary">{{ $homeTeamStats['goals'] ?? 0 }}</span>
                                    <span class="stat-value text-primary">{{ $awayTeamStats['goals'] ?? 0 }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Yellow cards</span>
                                    <span class="stat-value text-warning">{{ $homeTeamStats['yellow_cards'] ?? 0 }}</span>
                                    <span class="stat-value text-warning">{{ $awayTeamStats['yellow_cards'] ?? 0 }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Red cards</span>
                                    <span class="stat-value text-danger">{{ $homeTeamStats['red_cards'] ?? 0 }}</span>
                                    <span class="stat-value text-danger">{{ $awayTeamStats['red_cards'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            @endif

            <div class="{{ $game->status === 'completed' || $game->status === 'ongoing' ? 'col-lg-8' : 'col-12' }}">
                <section class="card" aria-labelledby="timeline-title">
                    <div class="card-header" id="timeline-title">
                        <i class="bi bi-clock-history" aria-hidden="true"></i> Match Timeline
                        <span class="lineup-caption">{{ $game->events->count() }} events</span>
                    </div>
                    <div class="card-body">
                        @if($game->events->count() > 0)
                            <div class="timeline-container">
                                @foreach($game->events->sortBy('minute') as $event)
                                    @php
                                        $isHomeEvent = $event->team_id == $game->team_home_id;
                                        $eventTeamName = $isHomeEvent
                                            ? ($game->homeTeam->short_name ?? $game->homeTeam->name ?? 'Home')
                                            : ($game->awayTeam->short_name ?? $game->awayTeam->name ?? 'Away');
                                    @endphp
                                    <article class="timeline-item {{ $isHomeEvent ? 'home' : 'away' }}">
                                        <span class="timeline-minute" aria-label="{{ $event->minute }} minutes">{{ $event->minute }}′</span>
                                        <div class="timeline-marker">
                                            @switch($event->event_type)
                                                @case('goal')
                                                    <span class="badge bg-success"><i class="bi bi-soccer" aria-hidden="true"></i></span>
                                                    @break
                                                @case('yellow_card')
                                                    <span class="badge bg-warning"><i class="bi bi-card-text" aria-hidden="true"></i></span>
                                                    @break
                                                @case('red_card')
                                                    <span class="badge bg-danger"><i class="bi bi-card-text" aria-hidden="true"></i></span>
                                                    @break
                                                @case('substitution')
                                                    <span class="badge bg-info"><i class="bi bi-arrow-left-right" aria-hidden="true"></i></span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary"><i class="bi bi-circle-fill" aria-hidden="true"></i></span>
                                            @endswitch
                                        </div>
                                        <div class="timeline-content">
                                            <div class="timeline-event-head">
                                                <div class="timeline-player">
                                                    <strong>{{ $event->player->name ?? 'Unknown Player' }}</strong>
                                                    <span class="timeline-event-type">{{ ucfirst(str_replace('_', ' ', $event->event_type)) }}</span>
                                                </div>
                                                <span class="timeline-team-badge {{ $isHomeEvent ? 'is-home' : 'is-away' }}">{{ $eventTeamName }}</span>
                                            </div>
                                            @if(($event->event_type === 'goal' && ($event->is_penalty || $event->is_own_goal)) || $event->related_player_id)
                                                <div class="timeline-event-details">
                                                    @if($event->event_type === 'goal' && $event->is_penalty)
                                                        <span class="timeline-event-detail is-penalty">Penalty</span>
                                                    @endif
                                                    @if($event->event_type === 'goal' && $event->is_own_goal)
                                                        <span class="timeline-event-detail is-own-goal">Own goal</span>
                                                    @endif
                                                    @if($event->related_player_id)
                                                        <span class="timeline-event-assist">Assist: <strong>{{ $event->relatedPlayer->name ?? 'Unknown' }}</strong></span>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($event->description)
                                                <p class="timeline-description">{{ $event->description }}</p>
                                            @endif
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="bi bi-activity" aria-hidden="true"></i>
                                <p>No match events recorded yet.</p>
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Auto refresh for live matches
    function refreshLiveMatch() {
        const matchStatus = "{{ $game->status }}";

        if (matchStatus === 'ongoing') {
            setTimeout(() => {
                location.reload();
            }, 30000); // Refresh every 30 seconds for live matches
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        refreshLiveMatch();

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
    </script>
</body>

</html>
