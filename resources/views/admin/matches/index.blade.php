@extends('layouts.admin')

@section('title', 'Pertandingan - OFS Futsal Center Admin')

@section('styles')
    <style>
        .admin-matches-page {
            --match-ink: #111111;
            --match-charcoal: #39393b;
            --match-muted: #707072;
            --match-line: #cacacb;
            --match-line-soft: #e5e5e5;
            --match-cloud: #f5f5f5;
            --match-white: #ffffff;
            --match-success: #007d48;
            --match-warning: #8a4b00;
            --match-danger: #b42318;
            max-width: 1440px;
            margin: 0 auto;
            color: var(--match-ink);
            font-size: 16px;
            line-height: 1.5;
        }

        .admin-matches-page .page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px 24px;
            margin-bottom: 24px;
        }

        .admin-matches-page .page-header h1 {
            margin: 0 0 8px;
            color: var(--match-ink);
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
            text-wrap: balance;
        }

        .admin-matches-page .page-subtitle {
            max-width: 66ch;
            margin: 0;
            color: var(--match-muted);
            font-size: 15px;
        }

        .admin-matches-page .header-tools,
        .admin-matches-page .search-box,
        .admin-matches-page .action-buttons,
        .admin-matches-page .pagination-container,
        .admin-matches-page .pagination-controls,
        .admin-matches-page .pagination-pages {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }

        .admin-matches-page .header-tools,
        .admin-matches-page .search-box,
        .admin-matches-page .action-buttons,
        .admin-matches-page .pagination-container,
        .admin-matches-page .pagination-controls,
        .admin-matches-page .pagination-pages {
            gap: 8px;
        }

        .admin-matches-page .search-box {
            position: relative;
            width: 100%;
            min-width: 0;
            flex: 1 1 220px;
        }

        .admin-matches-page .search-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            color: var(--match-muted);
            font-size: 14px;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .admin-matches-page .search-input,
        .admin-matches-page .filter-select {
            min-height: 44px;
            border: 1px solid var(--match-line);
            border-radius: 24px;
            background-color: var(--match-white);
            color: var(--match-ink);
            font: inherit;
            font-size: 14px;
            line-height: 1.25;
        }

        .admin-matches-page .search-input {
            width: 100%;
            padding: 9px 16px 9px 42px;
            background: var(--match-cloud);
        }

        .admin-matches-page .filter-select {
            min-width: 150px;
            padding: 8px 38px 8px 16px;
        }

        .admin-matches-page select.filter-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23111111' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 14px 14px;
            cursor: pointer;
        }

        .admin-matches-page :is(a, button):focus-visible {
            outline: 2px solid var(--match-ink);
            outline-offset: 2px;
        }

        .admin-matches-page .search-input:focus,
        .admin-matches-page .filter-select:focus,
        .admin-matches-page .score-input:focus,
        .admin-matches-page .form-control:focus,
        .admin-matches-page .form-select:focus {
            border-color: var(--match-ink);
            box-shadow: none;
        }

        .admin-matches-page :is(input:not([type="checkbox"]):not([type="radio"]), select):focus-visible {
            border: 2px solid var(--match-ink);
            outline: none;
            box-shadow: none;
        }

        .admin-matches-page .btn-create,
        .admin-matches-page .btn-reset,
        .admin-matches-page .pagination-btn,
        .admin-matches-page .pagination-pages .page-link {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 18px;
            border: 1px solid transparent;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            text-decoration: none;
            white-space: nowrap;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
            transition: background-color 150ms ease-out, border-color 150ms ease-out, color 150ms ease-out;
        }

        .admin-matches-page .btn-create {
            border-color: var(--match-ink);
            background: var(--match-ink);
            color: var(--match-white);
        }

        .admin-matches-page .btn-create:hover {
            border-color: var(--match-charcoal);
            background: var(--match-charcoal);
            color: var(--match-white);
        }

        .admin-matches-page .btn-create.btn-friendly,
        .admin-matches-page .btn-create.btn-generate,
        .admin-matches-page .btn-reset,
        .admin-matches-page .pagination-btn,
        .admin-matches-page .pagination-pages .page-link {
            background: var(--match-cloud);
            color: var(--match-ink);
        }

        .admin-matches-page .btn-create.btn-friendly:hover,
        .admin-matches-page .btn-create.btn-generate:hover,
        .admin-matches-page .btn-reset:hover,
        .admin-matches-page .pagination-btn:hover,
        .admin-matches-page .pagination-pages a.page-link:hover {
            background: #eeeeee;
            color: var(--match-ink);
        }

        .admin-matches-page .alert-admin {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 16px;
            padding: 14px 16px;
            border: 1px solid var(--match-line-soft);
            border-radius: 8px;
            background: var(--match-cloud);
            color: var(--match-charcoal);
            font-size: 14px;
            line-height: 1.5;
        }

        .admin-matches-page .alert-admin-success { color: var(--match-success); }
        .admin-matches-page .alert-admin-danger { color: var(--match-danger); }
        .admin-matches-page .alert-admin-warning { color: var(--match-warning); }
        .admin-matches-page .alert-close { flex: 0 0 auto; margin-left: auto; }

        .admin-matches-page .stats-grid,
        .admin-matches-page .bulk-bar,
        .admin-matches-page .main-card {
            border: 1px solid var(--match-line-soft);
            border-radius: 8px;
            background: var(--match-white);
        }

        .admin-matches-page .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 24px;
            overflow: hidden;
        }

        .admin-matches-page .stat-card {
            min-width: 0;
            padding: 18px 20px;
            border-right: 1px solid var(--match-line-soft);
        }

        .admin-matches-page .stat-card:last-child { border-right: 0; }

        .admin-matches-page .stat-title {
            color: var(--match-muted);
            font-size: 14px;
            line-height: 1.4;
        }

        .admin-matches-page .stat-value {
            margin-top: 4px;
            color: var(--match-ink);
            font-size: 26px;
            font-variant-numeric: tabular-nums;
            font-weight: 500;
            line-height: 1.2;
        }

        .admin-matches-page .filters-row {
            display: grid;
            grid-template-columns: minmax(220px, 1.45fr) repeat(3, minmax(145px, 1fr)) minmax(165px, 1fr) auto;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            padding: 12px;
            border: 1px solid var(--match-line-soft);
            border-radius: 8px;
            background: var(--match-white);
        }

        .admin-matches-page .filters-row .search-box { max-width: 320px; }

        .admin-matches-page .filters-row .filter-select {
            width: 100%;
            min-width: 0;
        }

        .admin-matches-page .btn-reset {
            background: var(--match-cloud);
            color: var(--match-ink);
            cursor: pointer;
        }

        .admin-matches-page .bulk-bar {
            margin-bottom: 16px;
            padding: 12px 16px;
        }

        .admin-matches-page .bulk-count {
            color: var(--match-ink);
            font-size: 14px;
            font-weight: 500;
        }

        .admin-matches-page .bulk-separator {
            width: 1px;
            height: 20px;
            background: var(--match-line-soft);
        }

        .admin-matches-page .main-card {
            margin-bottom: 24px;
            overflow: hidden;
        }

        .admin-matches-page .main-card .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--match-line-soft);
            background: var(--match-white);
        }

        .admin-matches-page .main-card .card-header h2 {
            margin: 0;
            color: var(--match-ink);
            font-size: 20px;
            font-weight: 500;
            line-height: 1.25;
        }

        .admin-matches-page .match-total {
            flex: 0 0 auto;
            color: var(--match-muted);
            font-size: 14px;
            font-variant-numeric: tabular-nums;
        }

        .admin-matches-page .table-responsive {
            overflow-x: auto;
            overscroll-behavior-x: contain;
        }

        .admin-matches-page #matchesTable {
            width: 100%;
            min-width: 1260px;
            margin: 0;
            border-collapse: collapse;
            color: var(--match-ink);
        }

        .admin-matches-page #matchesTable thead th {
            padding: 12px 14px;
            border-bottom: 1px solid var(--match-line-soft);
            background: var(--match-cloud);
            color: var(--match-muted);
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
        }

        .admin-matches-page #matchesTable thead th:first-child,
        .admin-matches-page #matchesTable tbody td:first-child {
            width: 48px;
            padding-inline: 14px 8px;
            text-align: center;
        }

        .admin-matches-page #matchesTable tbody td {
            padding: 14px;
            border-bottom: 1px solid var(--match-line-soft);
            background: var(--match-white);
            color: var(--match-ink);
            font-size: 14px;
            vertical-align: middle;
        }

        .admin-matches-page #matchesTable tbody tr:last-child td { border-bottom: 0; }

        .admin-matches-page #matchesTable tbody tr:hover td { background: #fafafa; }

        .admin-matches-page #matchesTable tbody .filter-empty-cell {
            padding: 28px 20px;
            color: var(--match-muted);
            text-align: center;
        }

        .admin-matches-page #matchesTable .match-checkbox,
        .admin-matches-page #matchesTable #selectAllMatches {
            width: 18px;
            height: 18px;
            accent-color: var(--match-ink);
            cursor: pointer;
        }

        .admin-matches-page .match-date {
            color: var(--match-ink);
            font-size: 14px;
            font-variant-numeric: tabular-nums;
            font-weight: 500;
            white-space: nowrap;
        }

        .admin-matches-page .match-time {
            margin-top: 3px;
            color: var(--match-muted);
            font-size: 13px;
        }

        .admin-matches-page .match-teams {
            display: flex;
            min-width: 350px;
            align-items: center;
            gap: 10px;
        }

        .admin-matches-page .team-info {
            display: flex;
            min-width: 0;
            flex: 1 1 0;
            align-items: center;
            gap: 8px;
        }

        .admin-matches-page .team-name {
            overflow: hidden;
            color: var(--match-ink);
            font-size: 14px;
            font-weight: 500;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-matches-page .team-logo-small {
            width: 32px;
            height: 32px;
            display: grid;
            flex: 0 0 32px;
            place-items: center;
            overflow: hidden;
            border: 1px solid var(--match-line-soft);
            border-radius: 50%;
            background: var(--match-white);
        }

        .admin-matches-page .team-logo-small img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .admin-matches-page .team-initial {
            color: var(--match-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .admin-matches-page .vs {
            flex: 0 0 auto;
            color: var(--match-muted);
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .admin-matches-page .score-display-admin {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 9px;
        }

        .admin-matches-page .score-display-admin .score {
            padding: 3px 9px;
            border: 1px solid var(--match-line-soft);
            border-radius: 9999px;
            background: var(--match-cloud);
            color: var(--match-ink);
            font-size: 14px;
            font-variant-numeric: tabular-nums;
            font-weight: 600;
        }

        .admin-matches-page .score-display-admin .score.live {
            border-color: #c4e4d3;
            background: #edf8f2;
            color: var(--match-success);
        }

        .admin-matches-page .extra-info-admin {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .admin-matches-page .extra-info-admin .badge,
        .admin-matches-page #matchesTable .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 9px;
            border: 1px solid var(--match-line-soft);
            border-radius: 9999px;
            background: var(--match-cloud);
            color: var(--match-charcoal);
            font-size: 12px;
            font-weight: 500;
            line-height: 1.3;
        }

        .admin-matches-page .live-badge,
        .admin-matches-page .status-badge.status-ongoing {
            border-color: #c4e4d3 !important;
            background: #edf8f2 !important;
            color: var(--match-success) !important;
        }

        .admin-matches-page .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border: 1px solid var(--match-line-soft);
            border-radius: 9999px;
            background: var(--match-cloud);
            color: var(--match-charcoal);
            font-size: 13px;
            font-weight: 500;
            line-height: 1.3;
            white-space: nowrap;
        }

        .admin-matches-page .status-completed {
            border-color: #c4e4d3;
            background: #edf8f2;
            color: var(--match-success);
        }

        .admin-matches-page .status-postponed {
            background: var(--match-white);
            color: var(--match-muted);
        }

        .admin-matches-page .status-cancelled {
            border-color: #f0cdca;
            background: #fdf2f1;
            color: var(--match-danger);
        }

        .admin-matches-page .action-buttons {
            flex-wrap: nowrap;
            justify-content: flex-end;
            gap: 4px;
            min-width: max-content;
        }

        .admin-matches-page .action-buttons form { margin: 0; }

        .admin-matches-page .btn-small {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 8px 12px;
            border: 1px solid transparent;
            border-radius: 9999px;
            background: var(--match-cloud);
            color: var(--match-ink);
            font-size: 14px;
            line-height: 1.2;
            text-decoration: none;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
            transition: background-color 150ms ease-out, color 150ms ease-out;
        }

        .admin-matches-page .action-buttons .btn-small {
            width: 40px;
            height: 40px;
            min-height: 40px;
            padding: 0;
            font-size: 14px;
        }

        .admin-matches-page .btn-small:hover {
            background: #e5e5e5;
            color: var(--match-ink);
        }

        .admin-matches-page .btn-small:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .admin-matches-page .btn-small.btn-delete:hover,
        .admin-matches-page .btn-small.btn-delete:disabled {
            background: #fdf2f1;
            color: var(--match-danger);
        }

        .admin-matches-page .bulk-action-btn:disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        .admin-matches-page #bulkDeleteBtn:not(:disabled) {
            border-color: #f0cdca;
            background: #fdf2f1;
            color: var(--match-danger);
        }

        .admin-matches-page #bulkDeleteBtn:not(:disabled):hover {
            border-color: #e7aaa4;
            background: #fbe4e1;
            color: var(--match-danger);
        }

        .admin-matches-page #bulkDeleteBtn:disabled {
            border-color: var(--match-line-soft);
            background: var(--match-cloud);
            color: var(--match-muted);
            opacity: 1;
        }

        .admin-matches-page #deleteAllTournamentBtn {
            border-color: var(--match-danger);
            background: var(--match-danger);
            color: var(--match-white);
        }

        .admin-matches-page #deleteAllTournamentBtn:hover {
            border-color: #780700;
            background: #780700;
            color: var(--match-white);
        }

        .admin-matches-page .pagination-container {
            justify-content: space-between;
            padding: 16px 20px;
            border-top: 1px solid var(--match-line-soft);
        }

        .admin-matches-page .pagination-info {
            color: var(--match-muted);
            font-size: 14px;
        }

        .admin-matches-page .pagination-controls { gap: 4px; }
        .admin-matches-page .pagination-pages { gap: 4px; }

        .admin-matches-page .pagination-pages .page-link {
            min-width: 40px;
            min-height: 40px;
            padding: 8px 12px;
        }

        .admin-matches-page .pagination-pages .page-link.active {
            border-color: var(--match-ink);
            background: var(--match-ink);
            color: var(--match-white);
        }

        .admin-matches-page .pagination-btn.disabled,
        .admin-matches-page .pagination-pages .page-link.disabled {
            background: var(--match-white);
            color: var(--match-muted);
            cursor: default;
        }

        .admin-matches-page .empty-state {
            padding: 40px 20px;
            text-align: center;
        }

        .admin-matches-page .empty-state-icon {
            margin-bottom: 12px;
            color: var(--match-muted);
            font-size: 28px;
        }

        .admin-matches-page .empty-state-title {
            margin: 0 0 6px;
            color: var(--match-ink);
            font-size: 18px;
            font-weight: 500;
        }

        .admin-matches-page .empty-state-text {
            margin: 0 0 16px;
            color: var(--match-muted);
            font-size: 14px;
        }

        .admin-matches-page .modal-content {
            overflow: hidden;
            border: 1px solid var(--match-line-soft);
            border-radius: 8px;
            background: var(--match-white);
            box-shadow: none;
        }

        .admin-matches-page .modal-header,
        .admin-matches-page .modal-footer {
            padding: 16px 20px;
            border-color: var(--match-line-soft);
            background: var(--match-white);
        }

        .admin-matches-page .modal-body { padding: 20px; }
        .admin-matches-page .modal-body { overscroll-behavior: contain; }

        .admin-matches-page .modal-title {
            color: var(--match-ink);
            font-size: 18px;
            font-weight: 500;
        }

        .admin-matches-page .modal .btn-primary {
            border-color: var(--match-ink);
            background: var(--match-ink);
            color: var(--match-white);
        }

        .admin-matches-page .modal .btn-primary:hover {
            border-color: var(--match-charcoal);
            background: var(--match-charcoal);
        }

        .admin-matches-page .modal .btn-outline-secondary {
            border-color: var(--match-line);
            background: var(--match-white);
            color: var(--match-charcoal);
        }

        .admin-matches-page .modal .btn-outline-primary {
            border-color: var(--match-line);
            background: var(--match-white);
            color: var(--match-ink);
        }

        .admin-matches-page .modal .btn-outline-primary:hover {
            border-color: var(--match-line);
            background: var(--match-cloud);
            color: var(--match-ink);
        }

        .admin-matches-page .modal .text-primary { color: var(--match-ink) !important; }

        .admin-matches-page .modal .form-control,
        .admin-matches-page .modal .form-select {
            min-height: 42px;
            border-color: var(--match-line);
            border-radius: 8px;
            background-color: var(--match-white);
            color: var(--match-ink);
        }

        .admin-matches-page .modal .form-check-input:checked {
            border-color: var(--match-ink);
            background-color: var(--match-ink);
        }

        .admin-matches-page .modal .alert-info,
        .admin-matches-page .modal .alert-secondary {
            border-color: var(--match-line-soft);
            background: var(--match-cloud);
            color: var(--match-charcoal);
        }

        .admin-matches-page .modal .alert-warning {
            border-color: #f0d6b3;
            background: #fff8eb;
            color: var(--match-warning);
        }

        .admin-matches-page .modal .card {
            border-color: var(--match-line-soft);
            border-radius: 8px;
            box-shadow: none;
        }

        .admin-matches-page .modal .card-header {
            border-bottom-color: var(--match-line-soft);
            background: var(--match-cloud);
        }

        .admin-matches-page #scoreUpdateModal .modal-dialog { max-width: 680px; }

        .admin-matches-page .score-modal-title {
            margin: 0;
            color: var(--match-ink);
            font-size: 18px;
            font-weight: 600;
        }

        .admin-matches-page .score-modal-description {
            margin: 4px 0 0;
            color: var(--match-muted);
            font-size: 13px;
        }

        .admin-matches-page #scoreUpdateModal .modal-body {
            display: grid;
            gap: 16px;
        }

        .admin-matches-page .score-modal-section-title {
            margin: 0 0 10px;
            color: var(--match-ink);
            font-size: 14px;
            font-weight: 600;
        }

        .admin-matches-page .score-modal-main {
            padding: 16px;
            border: 1px solid var(--match-line-soft);
            border-radius: 8px;
            background: var(--match-white);
        }

        .admin-matches-page .score-modal-matchup {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 28px minmax(0, 1fr);
            align-items: end;
            gap: 10px;
        }

        .admin-matches-page .score-modal-team {
            display: grid;
            min-width: 0;
            gap: 6px;
        }

        .admin-matches-page .score-modal-team label,
        .admin-matches-page .score-modal-side-label {
            overflow: hidden;
            color: var(--match-muted);
            font-size: 12px;
            font-weight: 600;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-matches-page .score-modal-vs {
            padding-bottom: 12px;
            color: var(--match-muted);
            font-size: 17px;
            font-weight: 600;
            text-align: center;
        }

        .admin-matches-page .score-input {
            height: 52px;
            border-color: var(--match-line);
            border-radius: 8px;
            color: var(--match-ink);
            font-size: 24px;
            font-variant-numeric: tabular-nums;
            font-weight: 600;
            text-align: center;
        }

        .admin-matches-page .score-modal-periods {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .admin-matches-page .score-modal-period {
            min-width: 0;
            padding: 14px;
            border: 1px solid var(--match-line-soft);
            border-radius: 8px;
            background: var(--match-cloud);
        }

        .admin-matches-page .score-modal-period-heading {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .admin-matches-page .score-modal-period-heading .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            min-height: 0;
            margin: 0;
        }

        .admin-matches-page .score-modal-period-heading .form-check-input {
            float: none;
            flex: 0 0 auto;
            margin: 0;
            accent-color: var(--match-ink);
        }

        .admin-matches-page .score-modal-period-heading .form-check-label {
            color: var(--match-ink);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .admin-matches-page .score-modal-period-note {
            margin: 0 0 10px;
            color: var(--match-muted);
            font-size: 12px;
        }

        .admin-matches-page .score-modal-period .score-modal-matchup {
            grid-template-columns: minmax(0, 1fr) 18px minmax(0, 1fr);
            gap: 6px;
        }

        .admin-matches-page .score-modal-period .score-input {
            height: 40px;
            font-size: 17px;
        }

        .admin-matches-page .score-modal-period .score-modal-vs {
            padding-bottom: 9px;
            font-size: 14px;
        }

        .admin-matches-page #scoreUpdateModal .modal-footer .btn {
            min-height: 40px;
            padding-inline: 16px;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 500;
        }

        @media (max-width: 1100px) {
            .admin-matches-page .page-header { align-items: flex-start; }
            .admin-matches-page .header-tools { justify-content: flex-start; }
            .admin-matches-page .filters-row {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
            .admin-matches-page .filters-row .search-box { grid-column: span 2; max-width: none; }
        }

        @media (max-width: 900px) {
            .admin-matches-page .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .admin-matches-page .stat-card:nth-child(2) { border-right: 0; }
            .admin-matches-page .stat-card:nth-child(-n + 2) { border-bottom: 1px solid var(--match-line-soft); }
        }

        @media (max-width: 640px) {
            .admin-matches-page .page-header { align-items: stretch; margin-bottom: 24px; }
            .admin-matches-page .header-tools { width: 100%; }
            .admin-matches-page .header-tools { align-items: stretch; }
            .admin-matches-page .btn-create { flex: 1 1 auto; }
            .admin-matches-page .filters-row { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .admin-matches-page .filters-row .search-box { grid-column: 1 / -1; }
            .admin-matches-page .filters-row .btn-reset { grid-column: 1 / -1; }
            .admin-matches-page .filter-select { width: 100%; min-width: 0; }
            .admin-matches-page .pagination-container { align-items: flex-start; }
            .admin-matches-page .action-buttons .btn-small {
                width: 44px;
                height: 44px;
                min-height: 44px;
            }
            .admin-matches-page #scoreUpdateModal .modal-header,
            .admin-matches-page #scoreUpdateModal .modal-footer,
            .admin-matches-page #scoreUpdateModal .modal-body { padding: 14px; }
        }

        @media (max-width: 480px) {
            .admin-matches-page .header-tools { display: grid; grid-template-columns: minmax(0, 1fr); }
            .admin-matches-page .filters-row { grid-template-columns: minmax(0, 1fr); }
            .admin-matches-page .filters-row .search-box { grid-column: auto; }
            .admin-matches-page .filters-row .btn-reset { grid-column: auto; }
            .admin-matches-page .stat-card { padding: 15px 14px; }
            .admin-matches-page .stat-value { font-size: 23px; }
            .admin-matches-page .main-card .card-header { align-items: flex-start; padding: 14px 16px; }
            .admin-matches-page .score-modal-periods { grid-template-columns: 1fr; }
            .admin-matches-page .score-modal-main { padding: 12px; }
            .admin-matches-page .score-modal-matchup { gap: 6px; }
            .admin-matches-page .score-input { height: 46px; font-size: 20px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-matches-page .btn-create,
            .admin-matches-page .btn-reset,
            .admin-matches-page .btn-small,
            .admin-matches-page .pagination-btn,
            .admin-matches-page .pagination-pages .page-link { transition: none; }
        }
    </style>
@endsection

@section('content')
    <main class="admin-matches-page">
        <header class="page-header">
            <div>
                <h1>Pertandingan</h1>
                <p class="page-subtitle">Kelola dan jadwalkan pertandingan turnamen.</p>
            </div>
            <div class="header-tools">
                <a href="{{ route('admin.matches.create') }}" class="btn-create">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i> Buat pertandingan
                </a>
                <a href="{{ route('admin.matches.create', ['mode' => 'friendly']) }}" class="btn-create btn-friendly">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i> Pertandingan persahabatan
                </a>
                <button type="button" class="btn-create btn-generate" data-bs-toggle="modal" data-bs-target="#generateModal">
                    <i class="bi bi-magic" aria-hidden="true"></i> Jadwal otomatis
                </button>
            </div>
        </header>

    <!-- Form tersembunyi untuk bulk delete -->
    <form id="bulkDeleteForm" action="{{ route('admin.matches.bulk-destroy') }}" method="POST" style="display:none;">
        @csrf
        <div id="bulkDeleteInputs"></div>
    </form>
    <!-- Form tersembunyi untuk hapus semua match turnamen -->
    <form id="deleteAllForm" action="" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- ===== Modal: Generate Semua Match Otomatis ===== -->
    <div class="modal fade" id="generateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-magic me-2"></i>Buat jadwal otomatis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form id="generateForm" action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="generateTournamentSelect">
                                Pilih turnamen <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="generateTournamentSelect" required>
                                <option value="">Pilih turnamen</option>
                                @foreach($tournaments->whereIn('status', ['upcoming', 'ongoing']) as $tournament)
                                    @php
                                        $teamCount = $tournament->teams()->count();
                                        $estMatches = 0;
                                        if ($tournament->type === 'league') {
                                            $estMatches = $teamCount * ($teamCount - 1) / 2;
                                        } elseif ($tournament->type === 'knockout') {
                                            $estMatches = max(0, $teamCount - 1);
                                        } elseif ($tournament->type === 'group_knockout') {
                                            $g = $tournament->groups_count ?? 2;
                                            $tpg = $tournament->teams_per_group ?? 4;
                                            $estMatches = $g * ($tpg * ($tpg - 1) / 2) + max(0, $g * ($tournament->qualify_per_group ?? 2) - 1);
                                        }
                                    @endphp
                                    <option value="{{ $tournament->id }}" data-type="{{ $tournament->type }}"
                                        data-teams="{{ $teamCount }}" data-est="{{ $estMatches }}"
                                        data-existing="{{ \App\Models\Game::where('tournament_id', $tournament->id)->count() }}"
                                        {{ request('tournament_id') == $tournament->id ? 'selected' : '' }}>
                                        {{ $tournament->name }} ({{ ucfirst($tournament->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="generateInfo" class="alert alert-info mb-3" style="display: none;">
                            <div><strong id="genInfoType"></strong></div>
                            <div class="small">Tim terdaftar: <strong id="genInfoTeams"></strong> &middot; Perkiraan pertandingan:
                                <strong id="genInfoEst"></strong>
                            </div>
                        </div>

                        <div id="generateWarning" class="alert alert-warning mb-3" style="display: none;">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Turnamen ini sudah memiliki <strong id="genInfoExisting"></strong> pertandingan.
                            Jadwal ulang akan mengganti pertandingan yang sudah ada.
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="confirmDeleteMatches">
                                <label class="form-check-label small" for="confirmDeleteMatches">
                                    Hapus pertandingan lama, lalu buat jadwal ulang
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-secondary mb-0 small">
                            <i class="bi bi-info-circle me-1"></i>
                            Sistem menyusun jadwal sesuai format turnamen: <strong>liga</strong> mempertemukan semua tim,
                            <strong>knockout</strong> menggunakan bagan, dan <strong>grup + knockout</strong> mencakup fase grup serta bagan.
                            Tanggal, waktu, dan venue mengikuti pengaturan turnamen.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="generateSubmitBtn" disabled>
                            <i class="bi bi-magic me-1"></i> Buat jadwal
                        </button>
                    </div>
                </form>
                <form id="deleteMatchesForm" action="" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-admin alert-admin-success alert-dismissible fade show" role="status" aria-live="polite">
            <i class="bi bi-check-circle" aria-hidden="true"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close alert-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-admin alert-admin-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close alert-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <!-- Ringkasan -->
    <section class="stats-grid" aria-label="Ringkasan pertandingan pada halaman ini">
        <div class="stat-card">
            <div class="stat-title">Akan datang</div>
            <div class="stat-value">{{ $matches->where('status', 'upcoming')->where('is_cancelled', false)->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Berlangsung</div>
            <div class="stat-value">{{ $matches->where('status', 'ongoing')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Selesai</div>
            <div class="stat-value">{{ $matches->where('status', 'completed')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Total di halaman ini</div>
            <div class="stat-value">{{ $matches->count() }}</div>
        </div>
    </section>

    <!-- Filters -->
    <div class="filters-row" role="group" aria-label="Filter pertandingan">
        <div class="search-box">
            <i class="bi bi-search search-icon" aria-hidden="true"></i>
            <input type="search" id="searchInput" name="search" autocomplete="off"
                placeholder="Cari nama tim…" class="search-input"
                aria-label="Cari pertandingan berdasarkan nama tim">
        </div>
        <label class="visually-hidden" for="tournamentFilter">Filter turnamen</label>
        <select id="tournamentFilter" name="tournament_id" class="filter-select">
            <option value="">Semua turnamen</option>
            @foreach($tournaments as $tournament)
                <option value="{{ $tournament->id }}" {{ request('tournament_id') == $tournament->id ? 'selected' : '' }}>
                    {{ $tournament->name }}
                </option>
            @endforeach
        </select>

        <label class="visually-hidden" for="statusFilter">Filter status</label>
        <select id="statusFilter" name="status" class="filter-select">
            <option value="">Semua status</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan datang</option>
            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
            <option value="postponed" {{ request('status') == 'postponed' ? 'selected' : '' }}>Ditunda</option>
        </select>

        <label class="visually-hidden" for="roundFilter">Filter babak</label>
        <select id="roundFilter" name="round" class="filter-select">
            <option value="">Semua babak</option>
            <option value="group" {{ request('round') == 'group' ? 'selected' : '' }}>Fase grup</option>
            <option value="quarterfinal" {{ request('round') == 'quarterfinal' ? 'selected' : '' }}>Perempat final</option>
            <option value="semifinal" {{ request('round') == 'semifinal' ? 'selected' : '' }}>Semifinal</option>
            <option value="third_place" {{ request('round') == 'third_place' ? 'selected' : '' }}>Perebutan juara 3/4</option>
            <option value="final" {{ request('round') == 'final' ? 'selected' : '' }}>Final</option>
        </select>

        <label class="visually-hidden" for="dateFrom">Mulai tanggal</label>
        <input type="date" id="dateFrom" name="date" class="filter-select"
            aria-label="Tampilkan pertandingan mulai tanggal" value="{{ request('date') }}">

        <button type="button" class="btn-reset" id="resetFilters">
            <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Reset filter
        </button>
    </div>

    <!-- ===== Bulk Action Bar ===== -->
    <div class="bulk-bar" id="bulkBar">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="bulk-count" id="bulkCount">0 dipilih</span>
            <button type="button" class="btn-small btn-delete bulk-action-btn" id="bulkDeleteBtn" disabled>
                <i class="bi bi-trash" aria-hidden="true"></i> Hapus terpilih
            </button>
            <span class="bulk-separator" aria-hidden="true"></span>
            <button type="button" class="btn-small btn-delete bulk-action-btn" id="deleteAllTournamentBtn">
                <i class="bi bi-trash-fill" aria-hidden="true"></i> Hapus semua pertandingan di turnamen ini
            </button>
        </div>
    </div>

    <div class="main-card">
        <div class="card-header">
            <h2>Daftar pertandingan</h2>
            <span class="match-total" id="matchResultCount">{{ $matches->count() }} pertandingan</span>
        </div>

        <div class="card-body p-0">
            @if($matches->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="matchesTable">
                        <thead>
                            <tr>
                                <th scope="col" style="width:36px;">
                                    <input type="checkbox" id="selectAllMatches" title="Pilih semua" aria-label="Pilih semua pertandingan di halaman ini">
                                </th>
                                <th scope="col">Tanggal dan waktu</th>
                                <th scope="col">Pertandingan</th>
                                <th scope="col">Turnamen</th>
                                <th scope="col">Babak</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matches as $match)
                                @php
                                    $isDrawManagedKnockoutMatch = in_array((int) $match->tournament_id, $drawManagedTournamentIds, true)
                                        && in_array($match->round_type, [
                                            'round_of_64',
                                            'round_of_32',
                                            'round_of_16',
                                            'quarterfinal',
                                            'semifinal',
                                            'third_place',
                                            'final',
                                            'knockout',
                                        ], true);
                                @endphp
                                <tr class="match-row {{ ($match->status == 'completed' && ($match->et_score || $match->is_penalty)) ? 'row-has-extras' : '' }}"
                                    data-status="{{ $match->is_cancelled ? 'cancelled' : $match->status }}"
                                    data-round="{{ $match->round_type }}" data-date="{{ $match->match_date->format('Y-m-d') }}"
                                    data-tournament="{{ $match->tournament_id }}">
                                    <td>
                                        <input type="checkbox" class="match-checkbox" value="{{ $match->id }}"
                                            data-tournament="{{ $match->tournament_id }}"
                                            aria-label="Pilih pertandingan {{ $match->homeTeam->name ?? 'Belum ditentukan' }} melawan {{ $match->awayTeam->name ?? 'Belum ditentukan' }}"
                                            @if($isDrawManagedKnockoutMatch) disabled
                                            title="Fixture bracket draw hanya dapat dihapus melalui pengelolaan bracket." @endif>
                                    </td>
                                    <td>
                                        <div class="match-date">{{ $match->match_date->format('d M Y') }}</div>
                                        <div class="match-time">
                                            <i class="bi bi-clock me-1" aria-hidden="true"></i>{{ $match->time_start }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="match-teams">
                                            <!-- Home Team -->
                                            <div class="team-info">
                                                <div class="team-name">{{ $match->homeTeam->name ?? 'Belum ditentukan' }}</div>
                                                <div class="team-logo-small">
                                                    @if($match->homeTeam && $match->homeTeam->logo)
                                                        @if(Storage::disk('public')->exists($match->homeTeam->logo))
                                                            <img src="{{ asset('storage/' . $match->homeTeam->logo) }}"
                                                                alt="" width="32" height="32" loading="lazy">
                                                        @elseif(filter_var($match->homeTeam->logo, FILTER_VALIDATE_URL))
                                                            <img src="{{ $match->homeTeam->logo }}" alt="" width="32" height="32" loading="lazy">
                                                        @else
                                                            <div class="team-initial">
                                                                {{ strtoupper(substr($match->homeTeam->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="team-initial">H</div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- VS -->
                                            <div class="vs">vs</div>

                                            <!-- Away Team -->
                                            <div class="team-info">
                                                <div class="team-logo-small">
                                                    @if($match->awayTeam && $match->awayTeam->logo)
                                                        @if(Storage::disk('public')->exists($match->awayTeam->logo))
                                                            <img src="{{ asset('storage/' . $match->awayTeam->logo) }}"
                                                                alt="" width="32" height="32" loading="lazy">
                                                        @elseif(filter_var($match->awayTeam->logo, FILTER_VALIDATE_URL))
                                                            <img src="{{ $match->awayTeam->logo }}" alt="" width="32" height="32" loading="lazy">
                                                        @else
                                                            <div class="team-initial">
                                                                {{ strtoupper(substr($match->awayTeam->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="team-initial">A</div>
                                                    @endif
                                                </div>
                                                <div class="team-name away">{{ $match->awayTeam->name ?? 'Belum ditentukan' }}</div>
                                            </div>
                                        </div>

                                        <!-- Score Section with Extras Info -->
                                        @if($match->status === 'completed' && $match->home_score !== null && $match->away_score !== null)
                                            <div class="score-display-admin">
                                                <span class="score">
                                                    {{ $match->home_score }} - {{ $match->away_score }}
                                                </span>

                                                <!-- Extra Time and Penalty Info -->
                                                @if($match->et_score || ($match->is_penalty && $match->penalty_score))
                                                    <div class="extra-info-admin">
                                                        @if($match->et_score)
                                                            <small class="badge et-badge me-1" style="font-size: 11px;">
                                        <i class="bi bi-clock-history" aria-hidden="true"></i> ET {{ $match->et_score }}
                                                            </small>
                                                        @endif

                                                        @if($match->is_penalty && $match->penalty_score)
                                                            <small class="badge pen-badge" style="font-size: 11px;">
                                        <i class="bi bi-flag" aria-hidden="true"></i> Pen. {{ $match->penalty_score }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($match->status === 'ongoing')
                                            <div class="score-display-admin">
                                                <span class="score live">
                                                    {{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}
                                                </span>
                                                <span class="badge live-badge">Live</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge tournament-badge">
                                            {{ $match->tournament->name ?? ($match->round_type === 'friendly' ? 'Pertandingan persahabatan' : 'Tidak tersedia') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($match->round_type === 'group')
                                            <span class="badge badge-group">
                                                {{ $match->group_name }}
                                            </span>
                                        @else
                                            <span class="badge badge-knockout">
                                                @switch($match->round_type)
                                                    @case('round_of_64') Babak 64 besar @break
                                                    @case('round_of_32') Babak 32 besar @break
                                                    @case('round_of_16') Babak 16 besar @break
                                                    @case('quarterfinal') Perempat final @break
                                                    @case('semifinal') Semifinal @break
                                                    @case('third_place') Perebutan juara 3/4 @break
                                                    @case('final') Final @break
                                                    @case('knockout') Knockout @break
                                                    @default {{ ucfirst(str_replace('_', ' ', $match->round_type)) }}
                                                @endswitch
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($match->is_cancelled)
                                            <span class="status-badge status-cancelled">Dibatalkan</span>
                                        @elseif($match->status === 'upcoming')
                                            <span class="status-badge status-upcoming">Akan datang</span>
                                        @elseif($match->status === 'ongoing')
                                            <span class="status-badge status-ongoing">Berlangsung</span>
                                        @elseif($match->status === 'completed')
                                            <span class="status-badge status-completed">Selesai</span>
                                        @else
                                            <span class="status-badge status-postponed">Ditunda</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="action-buttons">
                                            @if($match->is_cancelled)
                                                <a href="{{ route('admin.tournaments.edit', $match->tournament_id) }}"
                                                    class="btn-small btn-edit" title="Atur kembali perebutan juara 3/4"
                                                    aria-label="Atur kembali perebutan juara 3/4">
                                                    <i class="bi bi-toggles" aria-hidden="true"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.matches.edit', $match) }}" class="btn-small btn-edit"
                                                    title="Ubah pertandingan" aria-label="Ubah pertandingan">
                                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                                </a>
                                            @endif
                                            @unless($match->is_cancelled)
                                                <a href="{{ route('admin.matches.events.index', $match) }}" class="btn-small btn-events"
                                                    title="Event pertandingan" aria-label="Kelola event pertandingan">
                                                    <i class="bi bi-activity" aria-hidden="true"></i>
                                                </a>
                                                <a href="{{ route('admin.matches.lineup', $match) }}" class="btn-small btn-lineup"
                                                    title="Kelola susunan pemain" aria-label="Kelola susunan pemain">
                                                    <i class="bi bi-people-fill" aria-hidden="true"></i>
                                                </a>
                                            @endunless
                                            @if($match->is_cancelled)
                                                <button type="button" class="btn-small btn-score"
                                                    title="Aktifkan lagi dari pengaturan turnamen"
                                                    aria-label="Pertandingan dibatalkan, ubah pengaturan turnamen untuk mengaktifkan" disabled>
                                                    <i class="bi bi-lock-fill" aria-hidden="true"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn-small btn-score update-score-btn" title="Perbarui skor"
                                                    aria-label="Perbarui skor {{ $match->homeTeam->name ?? 'Tim tuan rumah' }} melawan {{ $match->awayTeam->name ?? 'Tim tamu' }}"
                                                    data-match-id="{{ $match->id }}"
                                                    data-home-team="{{ $match->homeTeam->name ?? 'Tim tuan rumah' }}"
                                                    data-away-team="{{ $match->awayTeam->name ?? 'Tim tamu' }}"
                                                    data-home-score="{{ $match->home_score ?? 0 }}"
                                                    data-away-score="{{ $match->away_score ?? 0 }}"
                                                    data-et-score="{{ $match->et_score ?? '' }}"
                                                    data-is-penalty="{{ $match->is_penalty ? '1' : '0' }}"
                                                    data-penalty-score="{{ $match->penalty_score ?? '' }}"
                                                    data-update-url="{{ route('admin.matches.update-score', $match) }}">
                                                    <i class="bi bi-bar-chart" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                            @if($isDrawManagedKnockoutMatch)
                                                <button type="button" class="btn-small btn-delete" disabled
                                                    title="Fixture bracket draw tidak dapat dihapus satu per satu. Gunakan Update Score atau Reset Draw."
                                                    aria-label="Pertandingan bagian bracket draw tidak dapat dihapus satu per satu">
                                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                                </button>
                                            @else
                                                <form action="{{ route('admin.matches.destroy', $match) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-small btn-delete"
                                                        onclick="return confirm('Hapus pertandingan ini?')" title="Hapus pertandingan"
                                                        aria-label="Hapus pertandingan {{ $match->homeTeam->name ?? 'Belum ditentukan' }} melawan {{ $match->awayTeam->name ?? 'Belum ditentukan' }}">
                                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($match->status === 'completed')
                                                <!-- Tombol YouTube Highlight -->
                                                <button type="button" class="btn-small btn-highlight youtube-highlight-btn"
                                                    title="Cuplikan YouTube" aria-label="Kelola cuplikan YouTube pertandingan"
                                                    data-match-id="{{ $match->id }}"
                                                    data-match-teams="{{ ($match->homeTeam->name ?? 'Home') . ' vs ' . ($match->awayTeam->name ?? 'Away') }}"
                                                    data-has-highlight="{{ $match->youtube_id ? 'true' : 'false' }}"
                                                    data-youtube-id="{{ $match->youtube_id }}">
                                                    <i class="bi bi-youtube" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr id="filterEmptyRow" hidden>
                                <td colspan="7" class="filter-empty-cell">
                                    Tidak ada pertandingan pada halaman ini yang sesuai dengan filter. Ubah atau reset filter.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($matches->hasPages())
                    <div class="pagination-container d-flex justify-content-between align-items-center flex-wrap">
                        <div class="pagination-info">
                            Menampilkan {{ $matches->firstItem() }}–{{ $matches->lastItem() }} dari {{ $matches->total() }}
                            pertandingan
                        </div>

                        <div class="pagination-controls">
                            <!-- Previous Button -->
                            @if($matches->onFirstPage())
                                <span class="pagination-btn disabled">
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i> Sebelumnya
                                </span>
                            @else
                                <a href="{{ $matches->previousPageUrl() }}" class="pagination-btn">
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i> Sebelumnya
                                </a>
                            @endif

                            <!-- Page Numbers -->
                            <div class="pagination-pages">
                                @php
                                    $current = $matches->currentPage();
                                    $last = $matches->lastPage();
                                    $start = max(1, $current - 2);
                                    $end = min($last, $current + 2);

                                    if ($start > 1) {
                                        echo '<a href="' . $matches->url(1) . '" class="page-link" aria-label="Halaman 1">1</a>';
                                        if ($start > 2) {
                                            echo '<span class="page-link disabled" aria-hidden="true">…</span>';
                                        }
                                    }

                                    for ($i = $start; $i <= $end; $i++) {
                                        if ($i == $current) {
                                    echo '<span class="page-link active" aria-current="page">' .
                                                $i . '</span>';
                                        } else {
                                            echo '<a href="' . $matches->url($i) . '" class="page-link">' . $i .
                                                '</a>';
                                        }
                                    }

                                    if ($end < $last) {
                                        if ($end < $last - 1) {
                                            echo '<span class="page-link disabled" aria-hidden="true">…</span>';
                                        }
                                        echo '<a href="' . $matches->url($last) . '" class="page-link">' . $last . '</a>';
                                    }
                                @endphp
                            </div>

                            <!-- Next Button -->
                            @if($matches->hasMorePages())
                                <a href="{{ $matches->nextPageUrl() }}" class="pagination-btn">
                                    Berikutnya <i class="bi bi-chevron-right" aria-hidden="true"></i>
                                </a>
                            @else
                                <span class="pagination-btn disabled">
                                    Berikutnya <i class="bi bi-chevron-right" aria-hidden="true"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-state-icon" aria-hidden="true">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <h4 class="empty-state-title">Belum ada pertandingan</h4>
                    <p class="empty-state-text">
                        Mulai dengan membuat pertandingan turnamen pertama Anda.
                    </p>
                    <a href="{{ route('admin.matches.create') }}" class="btn-create">
                        <i class="bi bi-plus"></i> Buat pertandingan
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- YouTube Highlight Modal -->
    <div class="modal fade" id="youtubeHighlightModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-youtube text-danger"></i> YouTube Highlight
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form id="youtubeHighlightForm">
                    @csrf
                    <input type="hidden" name="match_id" id="youtubeMatchId">
                    <input type="hidden" name="_method" id="youtubeMethod" value="POST">

                    <div class="modal-body">
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle"></i>
                            <span id="youtubeModalTeams"></span>
                        </div>

                        <!-- Current Highlight (if exists) -->
                        <div id="currentHighlightSection" class="mb-3">
                            <div class="card">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Current Highlight</h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="removeHighlightBtn">
                                        <i class="bi bi-trash"></i> Remove Highlight
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="ratio ratio-16x9 mb-2" id="currentVideoContainer">
                                        <!-- YouTube embed akan dimuat di sini -->
                                    </div>
                                    <small class="text-muted" id="currentVideoInfo"></small>
                                </div>
                            </div>
                        </div>

                        <!-- Add/Update Highlight Form -->
                        <div id="addHighlightForm">
                            <!-- YouTube URL Input -->
                            <div class="mb-3">
                                <label for="youtube_url" class="form-label fw-bold">YouTube
                                    URL *</label>
                                <input type="url" name="youtube_url" id="youtube_url" class="form-control"
                                    placeholder="https://www.youtube.com/watch?v=VIDEO_ID…" required>
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i>
                                    Format yang didukung: youtube.com/watch?v=VIDEO_ID, youtu.be/VIDEO_ID,
                                    dan youtube.com/embed/VIDEO_ID.
                                </div>
                            </div>

                            <!-- Preview (akan muncul setelah URL valid) -->
                            <div id="youtubePreview" class="d-none">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Preview</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="ratio ratio-16x9 mb-2" id="previewContainer">
                                            <!-- Preview embed akan dimuat di sini -->
                                        </div>
                                        <small class="text-muted" id="previewInfo"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Messages -->
                        <div id="youtubeStatus" class="d-none"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveYoutubeBtn">
                            <span id="saveButtonText">Save</span>
                            <span class="spinner-border spinner-border-sm d-none" id="saveSpinner"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="scoreUpdateModal" tabindex="-1" aria-labelledby="scoreUpdateTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="scoreUpdateForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <div>
                            <h2 class="score-modal-title" id="scoreUpdateTitle">Perbarui skor</h2>
                            <p class="score-modal-description">Masukkan skor pertandingan dan hasil babak tambahan jika ada.
                            </p>
                            <p class="score-modal-description">Untuk Clean Sheet per kiper, catat setiap gol lewat Event
                                Pertandingan dan pilih kiper yang kebobolan. Event gol juga menambah skor; skor manual saja
                                tidak cukup untuk atribusi kiper.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <section class="score-modal-main" aria-labelledby="regularScoreLabel">
                            <h3 class="score-modal-section-title" id="regularScoreLabel">Skor waktu normal</h3>
                            <div class="score-modal-matchup">
                                <div class="score-modal-team">
                                    <label for="modalHomeScore" id="modalHomeTeamLabel">Tim tuan rumah</label>
                                    <input type="number" name="home_score" id="modalHomeScore"
                                        class="form-control score-input" min="0" inputmode="numeric" required
                                        placeholder="0">
                                </div>
                                <span class="score-modal-vs" aria-hidden="true">:</span>
                                <div class="score-modal-team">
                                    <label for="modalAwayScore" id="modalAwayTeamLabel">Tim tamu</label>
                                    <input type="number" name="away_score" id="modalAwayScore"
                                        class="form-control score-input" min="0" inputmode="numeric" required
                                        placeholder="0">
                                </div>
                            </div>
                        </section>

                        <div class="score-modal-periods">
                            <section class="score-modal-period" aria-labelledby="extraTimeLabel">
                                <div class="score-modal-period-heading">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="hasExtraTime"
                                            aria-controls="extraTimeSection">
                                        <label class="form-check-label" for="hasExtraTime" id="extraTimeLabel">
                                            <i class="bi bi-clock-history me-1" aria-hidden="true"></i>Extra time
                                        </label>
                                    </div>
                                </div>
                                <p class="score-modal-period-note">Skor gol di extra time</p>
                                <div id="extraTimeSection" class="d-none">
                                    <input type="hidden" name="et_score" id="modalETScore">
                                    <div class="score-modal-matchup">
                                        <div class="score-modal-team">
                                            <label class="score-modal-side-label" for="modalETHome">Tuan rumah</label>
                                            <input type="number" id="modalETHome" class="form-control score-input" min="0"
                                                inputmode="numeric" placeholder="0">
                                        </div>
                                        <span class="score-modal-vs" aria-hidden="true">:</span>
                                        <div class="score-modal-team">
                                            <label class="score-modal-side-label" for="modalETAway">Tim tamu</label>
                                            <input type="number" id="modalETAway" class="form-control score-input" min="0"
                                                inputmode="numeric" placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="score-modal-period" aria-labelledby="penaltyLabel">
                                <div class="score-modal-period-heading">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="hasPenalty" name="is_penalty"
                                            value="1" aria-controls="penaltySection">
                                        <label class="form-check-label" for="hasPenalty" id="penaltyLabel">
                                            <i class="bi bi-flag me-1" aria-hidden="true"></i>Adu penalti
                                        </label>
                                    </div>
                                </div>
                                <p class="score-modal-period-note">Isi jika pemenang ditentukan lewat penalti</p>
                                <div id="penaltySection" class="d-none">
                                    <input type="hidden" name="penalty_score" id="modalPenaltyScore">
                                    <div class="score-modal-matchup">
                                        <div class="score-modal-team">
                                            <label class="score-modal-side-label" for="modalPenaltyHome">Tuan rumah</label>
                                            <input type="number" id="modalPenaltyHome" class="form-control score-input"
                                                min="0" inputmode="numeric" placeholder="0">
                                        </div>
                                        <span class="score-modal-vs" aria-hidden="true">:</span>
                                        <div class="score-modal-team">
                                            <label class="score-modal-side-label" for="modalPenaltyAway">Tim tamu</label>
                                            <input type="number" id="modalPenaltyAway" class="form-control score-input"
                                                min="0" inputmode="numeric" placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2 me-1" aria-hidden="true"></i>Simpan skor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('scoreUpdateForm');
            const modalEl = document.getElementById('scoreUpdateModal');
            const scoreUpdateModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            const fields = {
                extraTime: {
                    checkbox: document.getElementById('hasExtraTime'),
                    section: document.getElementById('extraTimeSection'),
                    home: document.getElementById('modalETHome'),
                    away: document.getElementById('modalETAway'),
                    combined: document.getElementById('modalETScore')
                },
                penalty: {
                    checkbox: document.getElementById('hasPenalty'),
                    section: document.getElementById('penaltySection'),
                    home: document.getElementById('modalPenaltyHome'),
                    away: document.getElementById('modalPenaltyAway'),
                    combined: document.getElementById('modalPenaltyScore')
                }
            };

            function parseScore(value) {
                const match = (value || '').match(/^(\d+)\s*-\s*(\d+)$/);
                return match ? { home: match[1], away: match[2] } : { home: '', away: '' };
            }

            function syncPeriod(period, clearWhenDisabled = false) {
                const enabled = period.checkbox.checked;
                period.section.classList.toggle('d-none', !enabled);
                period.home.required = enabled;
                period.away.required = enabled;

                if (!enabled && clearWhenDisabled) {
                    period.home.value = '';
                    period.away.value = '';
                }

                period.combined.value = enabled && period.home.value !== '' && period.away.value !== ''
                    ? `${period.home.value}-${period.away.value}`
                    : '';
            }

            Object.values(fields).forEach(period => {
                period.checkbox.addEventListener('change', () => syncPeriod(period, true));
                period.home.addEventListener('input', () => syncPeriod(period));
                period.away.addEventListener('input', () => syncPeriod(period));
            });

            form.addEventListener('submit', function () {
                Object.values(fields).forEach(period => syncPeriod(period));
            });

            modalEl.addEventListener('shown.bs.modal', function () {
                document.getElementById('modalHomeScore').focus();
            });

            document.querySelectorAll('.update-score-btn').forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    document.getElementById('modalHomeTeamLabel').textContent = this.dataset.homeTeam || 'Tim tuan rumah';
                    document.getElementById('modalAwayTeamLabel').textContent = this.dataset.awayTeam || 'Tim tamu';
                    document.getElementById('modalHomeScore').value = this.dataset.homeScore || 0;
                    document.getElementById('modalAwayScore').value = this.dataset.awayScore || 0;
                    form.action = this.dataset.updateUrl;

                    const etScore = parseScore(this.dataset.etScore);
                    fields.extraTime.home.value = etScore.home;
                    fields.extraTime.away.value = etScore.away;
                    fields.extraTime.checkbox.checked = Boolean(this.dataset.etScore);
                    syncPeriod(fields.extraTime, !fields.extraTime.checkbox.checked);

                    const penaltyScore = parseScore(this.dataset.penaltyScore);
                    fields.penalty.home.value = penaltyScore.home;
                    fields.penalty.away.value = penaltyScore.away;
                    fields.penalty.checkbox.checked = this.dataset.isPenalty === '1' || Boolean(this.dataset.penaltyScore);
                    syncPeriod(fields.penalty, !fields.penalty.checkbox.checked);

                    scoreUpdateModal.show();
                });
            });
        });
    </script>

    <!-- Upload Highlight Modal -->
    <div class="modal fade" id="uploadHighlightModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Unggah cuplikan pertandingan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            Cuplikan pertandingan: <strong id="uploadMatchTeams"></strong>
                        </div>
                    </div>

                    <form id="highlightUploadForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="match_id" id="uploadMatchId">

                        <div class="row">
                            <div class="col-md-8">
                                <!-- Video Upload -->
                                <div class="mb-4">
                                    <label for="highlight_video" class="form-label fw-bold">File video *</label>
                                    <div class="file-upload-area">
                                        <input type="file" name="highlight_video" id="highlight_video" class="form-control"
                                            accept=".mp4,.mov,.avi,.wmv,.mkv,.flv" required>
                                        <div class="form-text">
                                            <i class="bi bi-info-circle"></i>
                                            Ukuran maksimal 200 MB. Format: MP4, MOV, AVI, WMV, MKV, FLV
                                        </div>
                                    </div>
                                </div>

                                <!-- Thumbnail Upload -->
                                <div class="mb-4">
                                    <label for="highlight_thumbnail" class="form-label fw-bold">Gambar thumbnail
                                        (opsional)</label>
                                    <input type="file" name="highlight_thumbnail" id="highlight_thumbnail"
                                        class="form-control" accept=".jpg,.jpeg,.png,.gif">
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i>
                                        Jika tidak diunggah, thumbnail akan dibuat dari video.
                                        Max 5MB. Supported formats: JPG, PNG, GIF
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Video Preview -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Preview</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div id="videoPreview" class="d-none">
                                            <video id="previewPlayer" width="100%" height="auto" controls class="rounded">
                                                Your browser does not support the video tag.
                                            </video>
                                            <div class="mt-2">
                                                <small id="videoInfo" class="text-muted"></small>
                                            </div>
                                        </div>
                                        <div id="defaultPreview" class="text-muted">
                                            <i class="bi bi-film" style="font-size: 3rem;"></i>
                                            <p class="mt-2">Video preview will appear here</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress mb-3 d-none" id="uploadProgress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                style="width: 0%"></div>
                        </div>

                        <!-- Status Messages -->
                        <div id="uploadStatus" class="d-none"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitUploadBtn">Upload Highlight</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Highlight Modal -->
    <div class="modal fade" id="viewHighlightModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Match Highlight</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <h6 id="viewMatchTeams" class="text-primary"></h6>
                    </div>

                    <div id="highlightPlayerContainer">
                        <!-- Video player will be loaded here -->
                    </div>

                    <!-- Highlight Info -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Video Information</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>File Size:</strong> <span id="infoSize"></span></li>
                                        <li><strong>Duration:</strong> <span id="infoDuration"></span></li>
                                        <li><strong>Uploaded:</strong> <span id="infoUploaded"></span></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Actions</h6>
                                    <div class="d-grid gap-2">
                                        <a href="#" id="downloadVideoBtn" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-download"></i> Download Video
                                        </a>
                                        <button type="button" id="deleteHighlightBtn" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i> Delete Highlight
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    </main>
@endsection

@section('scripts')
    <script>
        function isIOS() {
            return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        }

        // Fix modal untuk iOS
        if (isIOS()) {
            // Fix backdrop click untuk iOS
            document.addEventListener('touchstart', function (e) {
                if (e.target.classList.contains('modal-backdrop') ||
                    e.target.classList.contains('modal') &&
                    !e.target.classList.contains('modal-dialog')) {

                    // Tutup semua modal yang terbuka
                    document.querySelectorAll('.modal.show').forEach(modalEl => {
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) {
                            modal.hide();
                        }
                    });

                    e.preventDefault();
                    e.stopPropagation();
                }
            }, {
                passive: false
            });

            // Fix untuk mencegah body scroll ketika modal terbuka
            document.addEventListener('show.bs.modal', function () {
                document.body.style.overflow = 'hidden';
                document.body.style.position = 'fixed';
                document.body.style.width = '100%';
            });

            document.addEventListener('hide.bs.modal', function () {
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.width = '';
            });
        }

        // Filter functionality
        const tournamentFilter = document.getElementById('tournamentFilter');
        const statusFilter = document.getElementById('statusFilter');
        const roundFilter = document.getElementById('roundFilter');
        const dateFrom = document.getElementById('dateFrom');
        const resetFilters = document.getElementById('resetFilters');
        const searchInput = document.getElementById('searchInput');
        const matchRows = document.querySelectorAll('.match-row');
        const filterEmptyRow = document.getElementById('filterEmptyRow');
        const matchResultCount = document.getElementById('matchResultCount');
        const matchCountFormatter = new Intl.NumberFormat('id-ID');

        // Function to filter matches
        function filterMatches() {
            const tournament = tournamentFilter.value;
            const status = statusFilter.value;
            const round = roundFilter.value;
            const date = dateFrom.value;
            const search = searchInput.value.toLowerCase();
            let visibleMatches = 0;

            matchRows.forEach(row => {
                const rowTournament = row.dataset.tournament;
                const rowStatus = row.dataset.status;
                const rowRound = row.dataset.round;
                const rowDate = row.dataset.date;

                // Get team names for search
                const homeTeam = row.querySelector('.team-name:not(.away)')?.textContent.toLowerCase() || '';
                const awayTeam = row.querySelector('.team-name.away')?.textContent.toLowerCase() || '';

                let show = true;

                if (tournament && rowTournament !== tournament) show = false;
                if (status && rowStatus !== status) show = false;
                if (round && rowRound !== round) show = false;
                if (date && rowDate < date) show = false;
                if (search && !homeTeam.includes(search) && !awayTeam.includes(search)) show = false;

                row.style.display = show ? '' : 'none';
                if (show) visibleMatches += 1;
            });

            if (filterEmptyRow) filterEmptyRow.hidden = visibleMatches > 0;
            if (matchResultCount) {
                matchResultCount.textContent = `${matchCountFormatter.format(visibleMatches)} pertandingan`;
            }
        }

        // Function to apply URL filters
        function applyUrlFilters() {
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.has('tournament_id')) {
                const tournamentId = urlParams.get('tournament_id');
                if (tournamentFilter) {
                    tournamentFilter.value = tournamentId;
                }
            }

            if (urlParams.has('status')) {
                const status = urlParams.get('status');
                if (statusFilter) {
                    statusFilter.value = status;
                }
            }

            if (urlParams.has('round')) {
                const round = urlParams.get('round');
                if (roundFilter) {
                    roundFilter.value = round;
                }
            }

            if (urlParams.has('date')) {
                const date = urlParams.get('date');
                if (dateFrom) {
                    dateFrom.value = date;
                }
            }

            if (urlParams.has('search')) {
                const search = urlParams.get('search');
                if (searchInput) {
                    searchInput.value = search;
                }
            }

            // Apply filters on page load
            filterMatches();
        }

        // Event listeners for filters
        document.addEventListener('DOMContentLoaded', function () {
            applyUrlFilters();

            if (tournamentFilter) tournamentFilter.addEventListener('change', function () {
                updateUrlParam('tournament_id', this.value);
                filterMatches();
            });

            if (statusFilter) statusFilter.addEventListener('change', function () {
                updateUrlParam('status', this.value);
                filterMatches();
            });

            if (roundFilter) roundFilter.addEventListener('change', function () {
                updateUrlParam('round', this.value);
                filterMatches();
            });

            if (dateFrom) dateFrom.addEventListener('change', function () {
                updateUrlParam('date', this.value);
                filterMatches();
            });

            if (searchInput) searchInput.addEventListener('input', function () {
                updateUrlParam('search', this.value);
                filterMatches();
            });

            // Reset filters
            if (resetFilters) {
                resetFilters.addEventListener('click', function () {
                    // Clear all filters
                    if (tournamentFilter) tournamentFilter.value = '';
                    if (statusFilter) statusFilter.value = '';
                    if (roundFilter) roundFilter.value = '';
                    if (dateFrom) dateFrom.value = '';
                    if (searchInput) searchInput.value = '';

                    // Clear URL parameters
                    clearUrlParams();

                    // Apply filters
                    filterMatches();
                });
            }
        });

        // Function to update URL parameter
        function updateUrlParam(key, value) {
            const url = new URL(window.location);

            if (value) {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }

            // Preserve pagination if exists
            const pageParam = url.searchParams.get('page');

            // Update URL without reloading page
            history.replaceState({}, '', url.toString());
        }

        // Function to clear all URL parameters
        function clearUrlParams() {
            const url = new URL(window.location);
            url.search = '';
            history.replaceState({}, '', url.toString());
        }

        // Highlight Upload Functionality
        document.addEventListener('DOMContentLoaded', function () {
            // Get CSRF token from form
            function getCsrfToken() {
                // Try multiple sources
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                if (csrfMeta) return csrfMeta.getAttribute('content');

                const csrfInput = document.querySelector('input[name="_token"]');
                if (csrfInput) return csrfInput.value;

                return '';
            }

            const csrfToken = getCsrfToken();

            // Log untuk debugging
            console.log('CSRF Token found:', csrfToken ? 'Yes' : 'No');
            console.log('Modal elements check:', {
                uploadModal: document.getElementById('uploadHighlightModal') ? 'Found' : 'Not found',
                viewModal: document.getElementById('viewHighlightModal') ? 'Found' : 'Not found'
            });

            // Upload Modal
            const uploadModalEl = document.getElementById('uploadHighlightModal');
            const viewModalEl = document.getElementById('viewHighlightModal');

            if (!uploadModalEl || !viewModalEl) {
                console.error('Required modal elements not found');
                return;
            }

            const uploadModal = new bootstrap.Modal(uploadModalEl);
            const viewModal = new bootstrap.Modal(viewModalEl);

            // Video preview functionality
            const videoInput = document.getElementById('highlight_video');
            const videoPreview = document.getElementById('videoPreview');
            const previewPlayer = document.getElementById('previewPlayer');
            const defaultPreview = document.getElementById('defaultPreview');
            const videoInfo = document.getElementById('videoInfo');

            if (videoInput) {
                videoInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];

                    if (!file) return;

                    // Validate file size (200MB = 209715200 bytes)
                    if (file.size > 209715200) {
                        alert('File size exceeds 200MB limit. Please choose a smaller file.');
                        this.value = '';
                        return;
                    }

                    // Validate file type
                    const validTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo',
                        'video/x-ms-wmv', 'video/x-matroska', 'video/x-flv'
                    ];
                    if (!validTypes.includes(file.type)) {
                        alert('Invalid file type. Please upload a video file (MP4, MOV, AVI, WMV, MKV, FLV).');
                        this.value = '';
                        return;
                    }

                    // Show preview
                    if (previewPlayer) {
                        const videoURL = URL.createObjectURL(file);
                        previewPlayer.src = videoURL;
                        previewPlayer.load();
                    }

                    // Show file info
                    if (videoInfo) {
                        const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                        videoInfo.textContent = `${file.name} (${fileSize} MB)`;
                    }

                    // Toggle preview visibility
                    if (videoPreview && defaultPreview) {
                        videoPreview.classList.remove('d-none');
                        defaultPreview.classList.add('d-none');
                    }
                });
            }

            // Upload button click handler
            document.querySelectorAll('.upload-highlight-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    const matchId = this.dataset.matchId;
                    const matchTeams = this.dataset.matchTeams;

                    console.log('Upload clicked for match:', matchId);

                    // Set modal data
                    const uploadMatchId = document.getElementById('uploadMatchId');
                    const uploadMatchTeams = document.getElementById('uploadMatchTeams');

                    if (uploadMatchId) uploadMatchId.value = matchId;
                    if (uploadMatchTeams) uploadMatchTeams.textContent = matchTeams;

                    // Reset form and preview
                    const highlightForm = document.getElementById('highlightUploadForm');
                    if (highlightForm) highlightForm.reset();

                    if (videoPreview) videoPreview.classList.add('d-none');
                    if (defaultPreview) defaultPreview.classList.remove('d-none');
                    if (previewPlayer) previewPlayer.src = '';

                    const uploadProgress = document.getElementById('uploadProgress');
                    const uploadStatus = document.getElementById('uploadStatus');

                    if (uploadProgress) {
                        uploadProgress.classList.add('d-none');
                        const progressFill = uploadProgress.querySelector('.progress-bar');
                        if (progressFill) {
                            progressFill.style.width = '0%';
                            progressFill.textContent = '0%';
                        }
                    }
                    if (uploadStatus) {
                        uploadStatus.classList.add('d-none');
                        uploadStatus.innerHTML = '';
                    }

                    // Show modal
                    uploadModal.show();
                });
            });

            // View highlight button click handler
            document.querySelectorAll('.view-highlight-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    const matchId = this.dataset.matchId;
                    const matchTeams = this.dataset.matchTeams;

                    console.log('View highlight for match:', matchId);

                    // Set modal header
                    const viewMatchTeams = document.getElementById('viewMatchTeams');
                    if (viewMatchTeams) viewMatchTeams.textContent = matchTeams;

                    // Load highlight info via AJAX
                    loadHighlightInfo(matchId);

                    // Show modal
                    viewModal.show();
                });
            });

            // Submit upload button
            const submitUploadBtn = document.getElementById('submitUploadBtn');
            if (submitUploadBtn) {
                submitUploadBtn.addEventListener('click', function () {
                    console.log('Submit upload clicked');
                    uploadHighlight();
                });
            }

            // Delete highlight button
            const deleteHighlightBtn = document.getElementById('deleteHighlightBtn');
            if (deleteHighlightBtn) {
                deleteHighlightBtn.addEventListener('click', function () {
                    const matchId = this.dataset.matchId;
                    console.log('Delete highlight for match:', matchId);
                    deleteHighlight(matchId);
                });
            }

            // UPLOAD HIGHLIGHT FUNCTION - IMPROVED VERSION
            function uploadHighlight() {
                const matchId = document.getElementById('uploadMatchId')?.value;
                if (!matchId) {
                    alert('Match ID not found');
                    return;
                }

                const form = document.getElementById('highlightUploadForm');
                if (!form) {
                    alert('Form not found');
                    return;
                }

                // Validate file input
                const videoFile = document.getElementById('highlight_video')?.files[0];
                if (!videoFile) {
                    alert('Please select a video file');
                    return;
                }

                // Validate file size
                if (videoFile.size > 209715200) { // 200MB
                    alert('File size exceeds 200MB limit');
                    return;
                }

                // Create FormData
                const formData = new FormData(form);

                // Show progress bar
                const progressBar = document.getElementById('uploadProgress');
                const progressFill = progressBar ? progressBar.querySelector('.progress-bar') : null;
                const statusDiv = document.getElementById('uploadStatus');

                if (progressBar) {
                    progressBar.classList.remove('d-none');
                }
                if (progressFill) {
                    progressFill.style.width = '0%';
                    progressFill.textContent = '0%';
                }
                if (statusDiv) {
                    statusDiv.classList.add('d-none');
                    statusDiv.innerHTML = '';
                }

                // Create and configure XMLHttpRequest
                const xhr = new XMLHttpRequest();

                // DEBUG: Log the URL we're trying to access
                const uploadUrl = `/admin/matches/${matchId}/upload-highlight`;
                console.log('Uploading to URL:', uploadUrl);

                xhr.open('POST', uploadUrl);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                // Add CSRF token to headers if available
                if (csrfToken) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                }

                // Progress tracking
                xhr.upload.addEventListener('progress', function (e) {
                    if (e.lengthComputable && progressFill) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        progressFill.style.width = percentComplete + '%';
                        progressFill.textContent = Math.round(percentComplete) + '%';
                    }
                });

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        console.log('Response received:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseType: xhr.responseType,
                            responseText: xhr.responseText.substring(0, 500) // First 500 chars
                        });

                        try {
                            // Try to parse as JSON
                            const response = JSON.parse(xhr.responseText);

                            if (xhr.status === 200 || xhr.status === 201) {
                                // Success
                                if (statusDiv) {
                                    statusDiv.innerHTML = `
                                                                                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                                                                                <i class="bi bi-check-circle"></i> ${response.message || 'Upload successful!'}
                                                                                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                                                                                                            </div>
                                                                                                        `;
                                    statusDiv.classList.remove('d-none');
                                }

                                // Close modal after delay
                                setTimeout(() => {
                                    uploadModal.hide();
                                    location.reload();
                                }, 2000);

                            } else {
                                // Server returned error
                                handleUploadError(statusDiv, progressBar,
                                    response.message || `Server error: ${xhr.status}`);
                            }

                        } catch (error) {
                            // Response is not JSON (likely HTML error page)
                            console.error('Failed to parse JSON:', error);

                            let errorMessage = 'Server error occurred';

                            // Try to extract error from HTML
                            if (xhr.responseText.includes('404')) {
                                errorMessage = 'Route not found (404). Please check if the upload endpoint exists.';
                            } else if (xhr.responseText.includes('403')) {
                                errorMessage = 'Access forbidden (403). You may need to re-login.';
                            } else if (xhr.responseText.includes('500')) {
                                errorMessage = 'Internal server error (500). Please try again later.';
                            } else if (xhr.responseText.includes('419')) {
                                errorMessage = 'Session expired (419). Please refresh the page and try again.';
                            }

                            handleUploadError(statusDiv, progressBar, errorMessage);
                        }
                    }
                };

                xhr.onerror = function () {
                    console.error('Network error during upload');
                    handleUploadError(statusDiv, progressBar, 'Network error. Please check your connection.');
                };

                xhr.send(formData);
            }

            // Helper function for upload errors
            function handleUploadError(statusDiv, progressBar, message) {
                console.error('Upload error:', message);

                if (statusDiv) {
                    statusDiv.innerHTML = `
                                                                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                                                <i class="bi bi-exclamation-triangle"></i> ${message}
                                                                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                                                                                            </div>
                                                                                        `;
                    statusDiv.classList.remove('d-none');
                }

                if (progressBar) {
                    progressBar.classList.add('d-none');
                }
            }

            // LOAD HIGHLIGHT INFO - IMPROVED VERSION
            function loadHighlightInfo(matchId) {
                const playerContainer = document.getElementById('highlightPlayerContainer');
                const infoSize = document.getElementById('infoSize');
                const infoDuration = document.getElementById('infoDuration');
                const infoUploaded = document.getElementById('infoUploaded');
                const downloadBtn = document.getElementById('downloadVideoBtn');
                const deleteBtn = document.getElementById('deleteHighlightBtn');

                // Clear previous content
                if (playerContainer) playerContainer.innerHTML =
                    '<div class="text-center p-4"><div class="spinner-border text-primary" role="status" aria-label="Memuat cuplikan"></div><p class="mt-2">Memuat cuplikan…</p></div>';
                if (infoSize) infoSize.textContent = 'Memuat…';
                if (infoDuration) infoDuration.textContent = 'Memuat…';
                if (infoUploaded) infoUploaded.textContent = 'Memuat…';
                if (downloadBtn) downloadBtn.href = '#';
                if (deleteBtn) deleteBtn.dataset.matchId = matchId;

                fetch(`/admin/matches/${matchId}/highlight-info`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        console.log('Highlight info response status:', response.status);

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Server did not return JSON');
                        }

                        return response.json();
                    })
                    .then(data => {
                        console.log('Highlight info data:', data);

                        if (data.success && data.data) {
                            const highlight = data.data;

                            // Set info
                            if (infoSize) infoSize.textContent = highlight.size || 'N/A';
                            if (infoDuration) infoDuration.textContent = highlight.duration || 'N/A';
                            if (infoUploaded) infoUploaded.textContent = highlight.uploaded_at_label || highlight.uploaded_at || 'N/A';

                            // Set download link
                            if (downloadBtn && highlight.video_url) {
                                downloadBtn.href = highlight.video_url;
                                downloadBtn.target = '_blank';
                            }

                            // Create video player
                            if (playerContainer && highlight.video_url) {
                                playerContainer.innerHTML = `
                                                                                                    <div class="ratio ratio-16x9">
                                                                                                        <video controls class="rounded" poster="${highlight.thumbnail_url || ''}" style="background: #000;">
                                                                                                            <source src="${highlight.video_url}" type="video/mp4">
                                                                                                            Your browser does not support the video tag.
                                                                                                        </video>
                                                                                                    </div>
                                                                                                `;
                            }
                        } else {
                            throw new Error(data.message || 'Failed to load highlight info');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading highlight info:', error);

                        if (playerContainer) {
                            playerContainer.innerHTML = `
                                                                                                <div class="alert alert-warning">
                                                                                                    <i class="bi bi-exclamation-triangle"></i> ${error.message || 'Failed to load highlight information'}
                                                                                                </div>
                                                                                            `;
                        }

                        if (infoSize) infoSize.textContent = 'Error';
                        if (infoDuration) infoDuration.textContent = 'Error';
                        if (infoUploaded) infoUploaded.textContent = 'Error';
                    });
            }

            // DELETE HIGHLIGHT - IMPROVED VERSION
            function deleteHighlight(matchId) {
                if (!confirm('Are you sure you want to delete this highlight? This action cannot be undone.')) {
                    return;
                }

                // Create FormData for DELETE request
                const formData = new FormData();
                formData.append('_method', 'DELETE');

                // Add CSRF token
                const csrfInput = document.querySelector('#highlightUploadForm input[name="_token"]');
                if (csrfInput && csrfInput.value) {
                    formData.append('_token', csrfInput.value);
                } else if (csrfToken) {
                    formData.append('_token', csrfToken);
                }

                fetch(`/admin/matches/${matchId}/delete-highlight`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Server did not return JSON');
                        }

                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert('Highlight deleted successfully');
                            viewModal.hide();
                            location.reload();
                        } else {
                            throw new Error(data.message || 'Failed to delete highlight');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting highlight:', error);
                        alert(`Error deleting highlight: ${error.message}`);
                    });
            }
        });

        // Auto-dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert:not(.alert-permanent)').forEach(alert => {
                try {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                } catch (e) {
                    // Silently fail
                }
            });
        }, 5000);

        // YouTube Highlight Modal
        const youtubeModalEl = document.getElementById('youtubeHighlightModal');
        const youtubeModal = youtubeModalEl ? new bootstrap.Modal(youtubeModalEl) : null;
        const youtubeForm = document.getElementById('youtubeHighlightForm');
        const removeHighlightBtn = document.getElementById('removeHighlightBtn');
        const currentHighlightSection = document.getElementById('currentHighlightSection');
        const addHighlightForm = document.getElementById('addHighlightForm');

        // Handle YouTube highlight button clicks
        document.querySelectorAll('.youtube-highlight-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const matchId = this.dataset.matchId;
                const matchTeams = this.dataset.matchTeams;
                const hasHighlight = this.dataset.hasHighlight === 'true';
                const youtubeId = this.dataset.youtubeId;

                console.log('YouTube highlight clicked:', {
                    matchId,
                    hasHighlight,
                    youtubeId
                });

                // Reset form
                if (youtubeForm) youtubeForm.reset();

                // Set modal data
                const youtubeMatchId = document.getElementById('youtubeMatchId');
                const youtubeModalTeams = document.getElementById('youtubeModalTeams');
                const saveButtonText = document.getElementById('saveButtonText');
                const youtubeMethod = document.getElementById('youtubeMethod');
                const youtubeUrlInput = document.getElementById('youtube_url');

                if (youtubeMatchId) youtubeMatchId.value = matchId;
                if (youtubeModalTeams) youtubeModalTeams.textContent = matchTeams;

                // Tampilkan form yang sesuai
                if (hasHighlight && youtubeId) {
                    // Ada highlight, tampilkan current highlight dan tombol delete
                    if (currentHighlightSection) currentHighlightSection.classList.remove('d-none');
                    if (addHighlightForm) addHighlightForm.classList.add('d-none');

                    // Load current highlight
                    loadCurrentHighlight(matchId);

                    // Set form method untuk update
                    if (youtubeMethod) youtubeMethod.value = 'PUT';
                    if (saveButtonText) saveButtonText.textContent = 'Update';
                } else {
                    // Tidak ada highlight, tampilkan form untuk add
                    if (currentHighlightSection) currentHighlightSection.classList.add('d-none');
                    if (addHighlightForm) addHighlightForm.classList.remove('d-none');

                    // Set form method untuk create
                    if (youtubeMethod) youtubeMethod.value = 'POST';
                    if (saveButtonText) saveButtonText.textContent = 'Save';

                    // Reset preview
                    const previewSection = document.getElementById('youtubePreview');
                    if (previewSection) previewSection.classList.add('d-none');
                }

                // Show modal
                if (youtubeModal) youtubeModal.show();
            });
        });

        // Load current highlight information
        function loadCurrentHighlight(matchId) {
            const currentVideoContainer = document.getElementById('currentVideoContainer');
            const currentVideoInfo = document.getElementById('currentVideoInfo');
            const youtubeUrlInput = document.getElementById('youtube_url');

            if (!currentVideoContainer || !currentVideoInfo) return;

            // Tampilkan loading
            currentVideoContainer.innerHTML = `
                                                                                    <div class="text-center p-4">
                                                                                        <div class="spinner-border text-primary" role="status"></div>
                                                                                        <p class="mt-2">Memuat cuplikan…</p>
                                                                                    </div>
                                                                                `;
            currentVideoInfo.textContent = 'Memuat…';

            // Coba load dari data yang sudah ada di tombol
            const youtubeBtn = document.querySelector(`.youtube-highlight-btn[data-match-id="${matchId}"]`);
            const youtubeId = youtubeBtn ? youtubeBtn.dataset.youtubeId : null;

            if (youtubeId) {
                // Jika ada youtubeId langsung di tombol, gunakan itu
                displayYoutubeEmbed(youtubeId);

                if (youtubeUrlInput) {
                    youtubeUrlInput.value = `https://www.youtube.com/watch?v=${youtubeId}`;
                }

                // Coba juga fetch dari API untuk info tambahan
                fetchYoutubeInfo(matchId, youtubeId);
            } else {
                // Coba fetch dari API
                fetch(`/admin/matches/${matchId}/youtube-info`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        // Cek content type
                        const contentType = response.headers.get('content-type');

                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status} - ${response.statusText}`);
                        }

                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            // Jika bukan JSON, coba parsing sebagai HTML atau text
                            return response.text().then(text => {
                                console.error('Server returned non-JSON:', text.substring(0, 200));
                                throw new Error('Server returned non-JSON response');
                            });
                        }
                    })
                    .then(data => {
                        if (data.success && data.data && data.data.youtube_id) {
                            const highlight = data.data;
                            displayYoutubeEmbed(highlight.youtube_id);
                            currentVideoInfo.textContent =
                                `Video ID: ${highlight.youtube_id} | Uploaded: ${highlight.uploaded_relative || 'N/A'}`;

                            if (youtubeUrlInput) {
                                youtubeUrlInput.value = `https://www.youtube.com/watch?v=${highlight.youtube_id}`;
                                validateYoutubeUrl(youtubeUrlInput.value);
                            }
                        } else {
                            showNoHighlight();
                        }
                    })
                    .catch(error => {
                        console.warn('Error loading from API, using button data:', error);

                        // Fallback ke data dari tombol
                        if (youtubeBtn && youtubeBtn.dataset.youtubeId) {
                            const fallbackYoutubeId = youtubeBtn.dataset.youtubeId;
                            displayYoutubeEmbed(fallbackYoutubeId);
                            currentVideoInfo.textContent = `Video ID: ${fallbackYoutubeId}`;

                            if (youtubeUrlInput) {
                                youtubeUrlInput.value = `https://www.youtube.com/watch?v=${fallbackYoutubeId}`;
                                validateYoutubeUrl(youtubeUrlInput.value);
                            }
                        } else {
                            showNoHighlight();
                        }
                    });
            }

            // Helper function untuk menampilkan embed
            function displayYoutubeEmbed(youtubeId) {
                currentVideoContainer.innerHTML = `
                                                                                        <iframe src="https://www.youtube.com/embed/${youtubeId}?rel=0&showinfo=0&modestbranding=1"
                                                                                                frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                                                                allowfullscreen style="border-radius: 4px; width: 100%; height: 100%;">
                                                                                        </iframe>
                                                                                    `;
            }

            // Helper function untuk fetch info tambahan
            function fetchYoutubeInfo(matchId, youtubeId) {
                fetch(`/admin/matches/${matchId}/youtube-info`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.ok ? response.json().catch(() => null) : null)
                    .then(data => {
                        if (data && data.success && data.data) {
                            const highlight = data.data;
                            currentVideoInfo.textContent =
                                `Video ID: ${highlight.youtube_id} | Uploaded: ${highlight.uploaded_relative || 'N/A'}`;
                        }
                    })
                    .catch(() => {
                        // Ignore error for additional info
                    });
            }

            // Helper function untuk menampilkan no highlight
            function showNoHighlight() {
                currentVideoContainer.innerHTML = `
                                                                                        <div class="alert alert-warning p-3 text-center">
                                                                                            <i class="bi bi-exclamation-triangle"></i> No highlight available
                                                                                        </div>
                                                                                    `;
                currentVideoInfo.textContent = 'No highlight available';
            }
        }

        // Fungsi untuk extract YouTube ID dari URL
        function extractYoutubeId(url) {
            if (!url) return null;

            const patterns = [
                /youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/,
                /youtu\.be\/([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/v\/([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/
            ];

            for (const pattern of patterns) {
                const match = url.match(pattern);
                if (match) return match[1];
            }
            return null;
        }

        // Perbaikan fungsi validateYoutubeUrl
        function validateYoutubeUrl(url) {
            const previewSection = document.getElementById('youtubePreview');
            const previewContainer = document.getElementById('previewContainer');
            const previewInfo = document.getElementById('previewInfo');

            if (!url || !previewSection || !previewContainer) {
                if (previewSection) previewSection.classList.add('d-none');
                return false;
            }

            const videoId = extractYoutubeId(url);

            if (!videoId) {
                previewSection.classList.add('d-none');
                showYoutubeStatus('Invalid YouTube URL format', 'danger');
                return false;
            }

            // Show preview
            previewContainer.innerHTML = `
                                                                                    <iframe src="https://www.youtube.com/embed/${videoId}?rel=0&showinfo=0&modestbranding=1"
                                                                                            frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                                                            allowfullscreen style="border-radius: 4px; width: 100%; height: 100%;">
                                                                                    </iframe>
                                                                                `;

            if (previewInfo) {
                previewInfo.textContent = `Video ID: ${videoId}`;
            }

            previewSection.classList.remove('d-none');
            showYoutubeStatus('Valid YouTube URL detected', 'success');

            // Update form method dan button jika ada current video
            const currentVideoId = document.querySelector('.youtube-highlight-btn[data-match-id]')?.dataset.youtubeId;
            if (currentVideoId) {
                const youtubeMethod = document.getElementById('youtubeMethod');
                const saveButtonText = document.getElementById('saveButtonText');

                if (youtubeMethod) youtubeMethod.value = 'PUT';
                if (saveButtonText) saveButtonText.textContent = 'Update';
            }

            return true;
        }

        // Handle remove highlight button
        if (removeHighlightBtn) {
            removeHighlightBtn.addEventListener('click', function () {
                const matchId = document.getElementById('youtubeMatchId')?.value;

                if (!matchId) {
                    alert('Match ID not found');
                    return;
                }

                if (!confirm('Are you sure you want to delete this YouTube highlight? This action cannot be undone.')) {
                    return;
                }

                // Get CSRF token safely
                const csrfToken = getCsrfToken();
                if (!csrfToken) {
                    alert('Security token not found. Please refresh the page.');
                    return;
                }

                // Show loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Menghapus…';
                this.disabled = true;

                fetch(`/admin/matches/${matchId}/youtube-highlight`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Tampilkan success message
                            showYoutubeStatus('YouTube highlight deleted successfully!', 'success');

                            // Refresh page setelah delay
                            setTimeout(() => {
                                if (youtubeModal) youtubeModal.hide();
                                location.reload();
                            }, 1500);
                        } else {
                            throw new Error(data.message || 'Error deleting highlight');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting highlight:', error);
                        showYoutubeStatus('Error deleting highlight: ' + error.message, 'danger');

                        // Restore button state
                        this.innerHTML = originalText;
                        this.disabled = false;
                    });
            });
        }

        // YouTube URL validation and preview
        const youtubeUrlInput = document.getElementById('youtube_url');
        if (youtubeUrlInput) {
            youtubeUrlInput.addEventListener('blur', function () {
                validateYoutubeUrl(this.value);
            });

            youtubeUrlInput.addEventListener('input', function () {
                if (!this.value) {
                    const previewSection = document.getElementById('youtubePreview');
                    if (previewSection) previewSection.classList.add('d-none');
                }
            });
        }

        // Helper function to get CSRF token safely
        function getCsrfToken() {
            // Try multiple sources
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) return csrfMeta.getAttribute('content');

            const csrfInput = document.querySelector('input[name="_token"]');
            if (csrfInput) return csrfInput.value;

            // Try to find token in forms
            const forms = document.querySelectorAll('form');
            for (const form of forms) {
                const tokenInput = form.querySelector('input[name="_token"]');
                if (tokenInput) return tokenInput.value;
            }

            console.warn('CSRF token not found');
            return '';
        }

        // Handle form submission
        if (youtubeForm) {
            youtubeForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const matchId = document.getElementById('youtubeMatchId')?.value;
                const youtubeUrl = document.getElementById('youtube_url')?.value;
                const method = document.getElementById('youtubeMethod')?.value;

                if (!matchId || !method) {
                    showYoutubeStatus('Missing required information', 'danger');
                    return;
                }

                // Untuk update, URL tidak wajib jika hanya ingin melihat current
                if (method === 'POST' && !youtubeUrl) {
                    showYoutubeStatus('Please enter a valid YouTube URL', 'danger');
                    return;
                }

                if (method === 'POST' && !validateYoutubeUrl(youtubeUrl)) {
                    showYoutubeStatus('Please enter a valid YouTube URL', 'danger');
                    return;
                }

                const saveBtn = document.getElementById('saveYoutubeBtn');
                const saveSpinner = document.getElementById('saveSpinner');
                const saveButtonText = document.getElementById('saveButtonText');

                // Show loading state
                if (saveBtn) saveBtn.disabled = true;
                if (saveSpinner) saveSpinner?.classList.remove('d-none');
                if (saveButtonText) saveButtonText?.classList.add('d-none');

                // Get CSRF token safely
                const csrfToken = getCsrfToken();
                if (!csrfToken) {
                    showYoutubeStatus('Security token not found. Please refresh the page.', 'danger');
                    if (saveBtn) saveBtn.disabled = false;
                    if (saveSpinner) saveSpinner?.classList.add('d-none');
                    if (saveButtonText) saveButtonText?.classList.remove('d-none');
                    return;
                }

                // Prepare request
                const url = `/admin/matches/${matchId}/youtube-highlight`;

                const formData = new FormData();
                if (youtubeUrl) {
                    formData.append('youtube_url', youtubeUrl);
                }
                formData.append('_method', method);
                formData.append('_token', csrfToken);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showYoutubeStatus(data.message || 'YouTube highlight saved successfully!', 'success');

                            // Close modal and reload page after delay
                            setTimeout(() => {
                                if (youtubeModal) youtubeModal.hide();
                                location.reload();
                            }, 1500);
                        } else {
                            showYoutubeStatus(data.message || 'Error saving highlight', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error saving highlight:', error);
                        showYoutubeStatus('Network error occurred: ' + error.message, 'danger');
                    })
                    .finally(() => {
                        // Restore button state
                        if (saveBtn) saveBtn.disabled = false;
                        if (saveSpinner) saveSpinner?.classList.add('d-none');
                        if (saveButtonText) saveButtonText?.classList.remove('d-none');
                    });
            });
        }

        // ===== Bulk Delete Matches =====
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAllMatches');
            const bulkCount = document.getElementById('bulkCount');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            const deleteAllBtn = document.getElementById('deleteAllTournamentBtn');
            const bulkForm = document.getElementById('bulkDeleteForm');
            const bulkInputs = document.getElementById('bulkDeleteInputs');
            const deleteAllForm = document.getElementById('deleteAllForm');
            const deleteAllUrlTemplate = '{{ route('admin.admin.tournaments.delete-matches', ['tournament' => ':id']) }}';

            function updateBulkState() {
                const checked = Array.from(document.querySelectorAll('.match-checkbox:checked:not(:disabled)'));
                if (bulkCount) bulkCount.textContent = checked.length + ' dipilih';
                if (bulkDeleteBtn) bulkDeleteBtn.disabled = checked.length === 0;
            }

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    document.querySelectorAll('.match-checkbox:not(:disabled)').forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateBulkState();
                });
            }

            document.querySelectorAll('.match-checkbox').forEach(cb => {
                cb.addEventListener('change', updateBulkState);
            });

            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', function () {
                    const checked = Array.from(document.querySelectorAll('.match-checkbox:checked:not(:disabled)'));

                    // ===== VALIDASI =====
                    if (checked.length === 0) {
                        alert('Pilih minimal satu match terlebih dahulu.');
                        return;
                    }

                    if (!confirm(checked.length + ' match yang dipilih akan DIHAPUS permanen' +
                        ' (termasuk event, statistik pemain, dan klasemen terkait). Lanjutkan?')) {
                        return;
                    }

                    // Isi input match_ids lalu submit
                    bulkInputs.innerHTML = '';
                    checked.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'match_ids[]';
                        input.value = cb.value;
                        bulkInputs.appendChild(input);
                    });
                    bulkForm.submit();
                });
            }

            if (deleteAllBtn) {
                deleteAllBtn.addEventListener('click', function () {
                    const tournamentId = tournamentFilter?.value;

                    // ===== VALIDASI: harus pilih turnamen dulu =====
                    if (!tournamentId) {
                        alert('Pilih turnamen pada filter terlebih dahulu, lalu klik tombol ini.');
                        return;
                    }

                    const tournamentName = tournamentFilter.options[tournamentFilter.selectedIndex]?.textContent || 'turnamen ini';

                    if (!confirm('SEMUA match di "' + tournamentName.trim() + '" akan DIHAPUS permanen' +
                        ' (termasuk event, statistik pemain, dan seluruh klasemen turnamen). Lanjutkan?')) {
                        return;
                    }

                    // Konfirmasi ganda (ketik HAPUS)
                    const typed = prompt('Untuk konfirmasi, ketik: HAPUS');
                    if (typed !== 'HAPUS') {
                        alert('Konfirmasi tidak valid. Penghapusan dibatalkan.');
                        return;
                    }

                    deleteAllForm.action = deleteAllUrlTemplate.replace(':id', tournamentId);
                    deleteAllForm.submit();
                });
            }
        });

        // ===== Generate Otomatis Modal =====
        document.addEventListener('DOMContentLoaded', function () {
            const genSelect = document.getElementById('generateTournamentSelect');
            const genInfo = document.getElementById('generateInfo');
            const genWarning = document.getElementById('generateWarning');
            const genInfoType = document.getElementById('genInfoType');
            const genInfoTeams = document.getElementById('genInfoTeams');
            const genInfoEst = document.getElementById('genInfoEst');
            const genInfoExisting = document.getElementById('genInfoExisting');
            const confirmDelete = document.getElementById('confirmDeleteMatches');
            const genSubmitBtn = document.getElementById('generateSubmitBtn');
            const genForm = document.getElementById('generateForm');
            const deleteForm = document.getElementById('deleteMatchesForm');

            const generateUrlTemplate = '{{ route('admin.admin.tournaments.generate-matches', ['tournament' => ':id']) }}';
            const deleteUrlTemplate = '{{ route('admin.admin.tournaments.delete-matches', ['tournament' => ':id']) }}';

            function updateGenerateModal() {
                const opt = genSelect.options[genSelect.selectedIndex];
                if (!opt || !opt.value) {
                    genInfo.style.display = 'none';
                    genWarning.style.display = 'none';
                    genSubmitBtn.disabled = true;
                    return;
                }

                const existing = parseInt(opt.dataset.existing || '0', 10);

                genInfoType.textContent = 'Tipe: ' + opt.dataset.type.replace(/_/g, ' ');
                genInfoTeams.textContent = opt.dataset.teams;
                genInfoEst.textContent = opt.dataset.est;
                genInfo.style.display = 'block';

                if (existing > 0) {
                    genInfoExisting.textContent = existing;
                    genWarning.style.display = 'block';
                    genSubmitBtn.disabled = !confirmDelete.checked;
                } else {
                    genWarning.style.display = 'none';
                    genSubmitBtn.disabled = false;
                }

                genForm.action = generateUrlTemplate.replace(':id', opt.value);
                deleteForm.action = deleteUrlTemplate.replace(':id', opt.value);
            }

            if (genSelect) {
                genSelect.addEventListener('change', updateGenerateModal);
                updateGenerateModal();
            }

            if (confirmDelete) {
                confirmDelete.addEventListener('change', function () {
                    genSubmitBtn.disabled = !this.checked;
                });
            }

            if (genForm) {
                genForm.addEventListener('submit', function (e) {
                    const opt = genSelect.options[genSelect.selectedIndex];
                    const existing = parseInt(opt?.dataset.existing || '0', 10);

                    if (existing > 0 && confirmDelete.checked) {
                        e.preventDefault();
                        if (!confirm('Semua match lama akan DIHAPUS permanen (termasuk statistik terkait). Lanjutkan generate ulang?')) {
                            return;
                        }
                        // Submit delete form; setelah redirect, admin bisa klik generate lagi
                        deleteForm.submit();
                        return;
                    }

                    if (!confirm('Generate semua match untuk turnamen ini?')) {
                        e.preventDefault();
                    }
                });
            }
        });

        // Helper function to show status messages
        function showYoutubeStatus(message, type = 'info') {
            const statusDiv = document.getElementById('youtubeStatus');
            if (!statusDiv) return;

            const alertClass = type === 'success' ? 'alert-success' :
                type === 'danger' ? 'alert-danger' :
                    type === 'warning' ? 'alert-warning' : 'alert-info';

            statusDiv.innerHTML = `
                                                                                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                                                                                        <i class="bi ${type === 'success' ? 'bi-check-circle' :
                    type === 'danger' ? 'bi-exclamation-triangle' :
                        'bi-info-circle'}"></i> 
                                                                                        ${message}
                                                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                                                                                    </div>
                                                                                `;
            statusDiv.classList.remove('d-none');

            // Auto-hide after 5 seconds (except for success which will auto-close)
            if (type !== 'success') {
                setTimeout(() => {
                    statusDiv.classList.add('d-none');
                }, 5000);
            }
        }
    </script>
@endsection
