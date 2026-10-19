@extends('layouts.admin')

@section('title', 'Booking Lapangan')

@push('styles')
    <style>
        .booking-admin-page {
            --booking-ink: #111111;
            --booking-charcoal: #39393b;
            --booking-muted: #707072;
            --booking-stone: #9e9ea0;
            --booking-line: #cacacb;
            --booking-line-soft: #e5e5e5;
            --booking-cloud: #f5f5f5;
            --booking-canvas: #ffffff;
            --booking-success: #007d48;
            --booking-danger: #d30005;
            max-width: 1440px;
            margin: 0 auto;
            color: var(--booking-ink);
            font-size: 15px;
            line-height: 1.5;
        }

        .booking-page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 32px;
        }

        .booking-page-heading h1 {
            margin: 0 0 8px;
            color: var(--booking-ink);
            font-size: 32px;
            font-weight: 500;
            line-height: 1.2;
            letter-spacing: 0;
        }

        .booking-page-heading p {
            max-width: 65ch;
            margin: 0;
            color: var(--booking-muted);
            font-size: 15px;
            line-height: 1.5;
        }

        .booking-page-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .booking-admin-page .btn {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 9999px;
            box-shadow: none;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            white-space: normal;
            transition: background-color 150ms ease-out, color 150ms ease-out, border-color 150ms ease-out;
        }

        .booking-page-actions .btn {
            min-height: 48px;
            padding-inline: 24px;
            white-space: nowrap;
        }

        .booking-admin-page .btn-primary {
            border-color: var(--booking-ink);
            background: var(--booking-ink);
            color: #ffffff;
        }

        .booking-admin-page .btn-primary:hover,
        .booking-admin-page .btn-primary:active {
            border-color: var(--booking-charcoal);
            background: var(--booking-charcoal);
            color: #ffffff;
        }

        .booking-admin-page .btn-outline-primary,
        .booking-admin-page .btn-outline-secondary {
            border-color: transparent;
            background: var(--booking-cloud);
            color: var(--booking-ink);
        }

        .booking-admin-page .btn-outline-primary:hover,
        .booking-admin-page .btn-outline-secondary:hover {
            border-color: transparent;
            background: #e5e5e5;
            color: var(--booking-ink);
        }

        .booking-admin-page .btn-success {
            border-color: var(--booking-success);
            background: var(--booking-success);
            color: #ffffff;
        }

        .booking-admin-page .btn-outline-success {
            border-color: transparent;
            background: var(--booking-cloud);
            color: var(--booking-success);
        }

        .booking-admin-page .btn-danger {
            border-color: var(--booking-danger);
            background: var(--booking-danger);
            color: #ffffff;
        }

        .booking-admin-page .btn-outline-danger {
            border-color: #e5b9b9;
            background: #ffffff;
            color: #780700;
        }

        .booking-admin-page .card {
            overflow: hidden;
            border: 1px solid var(--booking-line-soft);
            border-radius: 8px;
            background: var(--booking-canvas);
            box-shadow: none;
        }

        .booking-admin-page > section,
        .booking-admin-page > form.card {
            margin-bottom: 24px !important;
        }

        .booking-admin-page .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--booking-line-soft);
            background: var(--booking-canvas);
        }

        .booking-admin-page .card-header h5,
        .booking-report-summary h5 {
            margin: 0 0 6px;
            color: var(--booking-ink);
            font-size: 18px;
            font-weight: 500;
            line-height: 1.25;
        }

        .booking-admin-page .card-header p {
            max-width: 75ch;
            margin: 0;
            color: var(--booking-muted);
            font-size: 14px;
            line-height: 1.5;
        }

        .booking-income-overview {
            display: grid;
            grid-template-columns: minmax(300px, 1.25fr) repeat(2, minmax(220px, 1fr));
            margin-bottom: 24px !important;
            overflow: hidden;
            background: var(--booking-canvas);
        }

        .booking-income-primary,
        .booking-income-breakdown {
            min-width: 0;
            padding: 24px;
        }

        .booking-income-primary {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .booking-income-breakdown {
            border-left: 1px solid var(--booking-line-soft);
        }

        .booking-metric-label {
            display: block;
            margin-bottom: 8px;
            color: var(--booking-muted);
            font-size: 13px;
            font-weight: 500;
            line-height: 1.4;
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        .booking-income-total {
            color: var(--booking-ink);
            font-size: clamp(28px, 2.8vw, 38px);
            font-weight: 500;
            line-height: 1.15;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .booking-income-value {
            color: var(--booking-ink);
            font-size: 23px;
            font-weight: 500;
            line-height: 1.25;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .booking-metric-note {
            display: block;
            margin-top: 8px;
            color: var(--booking-muted);
            font-size: 14px;
            line-height: 1.45;
        }

        .booking-status-strip {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-bottom: 24px;
            overflow: hidden;
            border: 1px solid var(--booking-line-soft);
            border-radius: 8px;
            background: var(--booking-canvas);
        }

        .booking-status-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            min-width: 0;
            padding: 16px 20px;
        }

        .booking-status-item + .booking-status-item {
            border-left: 1px solid var(--booking-line-soft);
        }

        .booking-status-label {
            color: var(--booking-charcoal);
            font-size: 14px;
            line-height: 1.4;
        }

        .booking-status-count {
            color: var(--booking-ink);
            font-size: 24px;
            font-weight: 500;
            font-variant-numeric: tabular-nums;
        }

        .booking-admin-page .card-body {
            padding: 24px;
        }

        .booking-report-summary {
            display: grid;
            grid-template-columns: minmax(220px, 1fr) minmax(0, 2fr) auto;
            align-items: center;
            gap: 24px;
        }

        .booking-report-summary h5 {
            margin-bottom: 6px;
        }

        .booking-report-summary p {
            max-width: 48ch;
            color: var(--booking-muted);
            font-size: 14px;
            line-height: 1.5;
        }

        .booking-report-facts {
            display: flex;
            flex-wrap: wrap;
            gap: 16px 28px;
        }

        .booking-report-fact {
            display: grid;
            gap: 4px;
        }

        .booking-report-fact > span {
            color: var(--booking-muted) !important;
        }

        .booking-report-fact strong {
            color: var(--booking-ink);
            font-size: 15px;
            font-variant-numeric: tabular-nums;
        }

        .booking-list-count {
            flex: none;
            color: var(--booking-muted);
            font-size: 14px;
            font-variant-numeric: tabular-nums;
        }

        .booking-admin-page .small,
        .booking-admin-page small {
            font-size: 13px;
        }

        .booking-admin-page .text-secondary {
            color: var(--booking-muted) !important;
        }

        .booking-admin-page .table {
            margin: 0;
            color: var(--booking-ink);
            vertical-align: top;
            --bs-table-bg: transparent;
            --bs-table-color: var(--booking-ink);
            --bs-table-border-color: var(--booking-line-soft);
        }

        .booking-admin-page .table thead th {
            padding: 13px 16px;
            border-bottom: 1px solid var(--booking-line);
            background: var(--booking-cloud);
            color: var(--booking-charcoal);
            font-size: 13px;
            font-weight: 500;
            letter-spacing: .02em;
            white-space: nowrap;
        }

        .booking-admin-page .table tbody td,
        .booking-admin-page .table tbody th {
            padding: 16px;
            border-bottom-color: var(--booking-line-soft);
            line-height: 1.5;
        }

        .booking-admin-page .table tbody tr:last-child td,
        .booking-admin-page .table tbody tr:last-child th {
            border-bottom: 0;
        }

        .booking-admin-page .table-responsive {
            border-radius: 8px;
        }

        .booking-admin-page .form-control,
        .booking-admin-page .form-select,
        .booking-admin-page .input-group-text {
            min-height: 44px;
            border-color: var(--booking-line);
            border-radius: 8px;
            background-color: #ffffff;
            color: var(--booking-ink);
            font-size: 14px;
            box-shadow: none;
        }

        .booking-admin-page .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }

        .booking-admin-page .input-group-text {
            padding-inline: 14px;
            border-radius: 8px 0 0 8px;
            background: var(--booking-cloud);
            color: var(--booking-charcoal);
        }

        .booking-admin-page .form-control:focus,
        .booking-admin-page .form-select:focus {
            border-color: var(--booking-ink);
            box-shadow: 0 0 0 3px rgba(17, 17, 17, .1);
        }

        .booking-admin-page .form-label {
            color: var(--booking-charcoal);
            font-size: 14px;
            font-weight: 500;
        }

        .booking-admin-page .btn:focus-visible,
        .booking-admin-page summary:focus-visible,
        .booking-admin-page a:focus-visible,
        .booking-admin-page input:focus-visible,
        .booking-admin-page select:focus-visible,
        .booking-admin-page textarea:focus-visible {
            outline: 3px solid rgba(17, 17, 17, .22);
            outline-offset: 2px;
        }

        .booking-admin-page .alert {
            padding: 14px 18px;
            margin-bottom: 16px;
            border: 1px solid var(--booking-line-soft);
            border-radius: 8px;
            font-size: 14px;
        }

        .booking-admin-page .badge {
            padding: 7px 12px;
            border: 1px solid transparent;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.3;
            text-align: left;
            white-space: normal;
        }

        .booking-admin-page .badge.text-bg-success {
            background: #e8f3ed !important;
            color: var(--booking-success) !important;
        }

        .booking-admin-page .badge.text-bg-warning,
        .booking-admin-page .badge.text-bg-info,
        .booking-admin-page .badge.text-bg-secondary {
            border-color: var(--booking-line-soft);
            background: var(--booking-cloud) !important;
            color: var(--booking-charcoal) !important;
        }

        .booking-admin-page .badge.text-bg-danger {
            background: #f8e8e8 !important;
            color: #780700 !important;
        }

        .booking-admin-page .border.rounded {
            border-color: var(--booking-line) !important;
            border-radius: 8px !important;
            background: var(--booking-cloud);
        }

        .booking-filter-grid {
            display: grid;
            grid-template-columns: minmax(190px, 240px) minmax(210px, 280px) auto auto;
            align-items: end;
            gap: 12px;
        }

        .booking-filter-grid .form-label {
            margin-bottom: 6px;
        }

        .booking-filter-grid .form-control,
        .booking-filter-grid .form-select,
        .booking-filter-grid .btn {
            min-height: 44px;
        }

        .booking-filter-grid .btn-primary {
            border-color: transparent;
            background: var(--booking-cloud);
            color: var(--booking-ink);
        }

        .booking-filter-grid .btn-primary:hover {
            border-color: transparent;
            background: #e5e5e5;
            color: var(--booking-ink);
        }

        .booking-table-wrap {
            overflow-x: auto;
        }

        .booking-table-wrap:focus-visible {
            outline: 3px solid rgba(17, 17, 17, .2);
            outline-offset: -3px;
        }

        .booking-table {
            min-width: 1080px;
        }

        .booking-table th:nth-child(1),
        .booking-table td:nth-child(1) { width: 190px; }
        .booking-table th:nth-child(2),
        .booking-table td:nth-child(2) { width: 180px; }
        .booking-table th:nth-child(3),
        .booking-table td:nth-child(3) { width: 250px; }
        .booking-table th:nth-child(4),
        .booking-table td:nth-child(4) { width: 165px; }

        .booking-table td {
            overflow-wrap: anywhere;
        }

        .booking-table .booking-actions-cell {
            width: 270px;
            min-width: 270px;
            text-align: left;
            vertical-align: top;
        }

        .booking-table .booking-actions-heading {
            width: 270px;
            text-align: left;
        }

        .booking-actions-cell > * {
            width: 100%;
            margin-top: 0 !important;
            margin-bottom: 8px !important;
        }

        .booking-actions-cell .btn {
            min-height: 44px;
            justify-content: center;
            text-align: center;
        }

        .booking-actions-cell details > summary.btn {
            display: flex;
            width: 100%;
            list-style: none;
            cursor: pointer;
        }

        .booking-actions-cell details > summary.btn::-webkit-details-marker {
            display: none;
        }

        .booking-actions-cell details > form {
            padding: 12px;
            border: 1px solid var(--booking-line-soft);
            border-radius: 8px !important;
            background: var(--booking-cloud);
            text-align: left;
        }

        .booking-actions-cell > .d-flex {
            display: flex !important;
            flex-direction: column;
            align-items: stretch;
            justify-content: flex-start !important;
            gap: 8px !important;
        }

        .booking-actions-cell > .d-flex > details,
        .booking-actions-cell > .d-flex > button,
        .booking-actions-cell > .d-flex > span {
            width: 100%;
        }

        .booking-actions-cell .small.text-secondary {
            display: block;
            color: var(--booking-muted) !important;
            font-size: 12px;
            line-height: 1.4;
            text-align: left;
        }

        .booking-actions-cell .btn-link {
            padding-inline: 0;
            background: transparent;
            color: var(--booking-charcoal);
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .booking-table .booking-money {
            color: var(--booking-ink);
            font-weight: 500;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .booking-chart-frame {
            position: relative;
            height: 310px;
        }

        .booking-chart-canvas {
            width: 100% !important;
            height: 100% !important;
            outline: none;
        }

        .booking-chart-canvas:focus-visible {
            outline: 3px solid rgba(17, 17, 17, .2);
            outline-offset: 3px;
        }

        .booking-chart-readout {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, auto));
            justify-content: start;
            gap: 8px 40px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--booking-line-soft);
            font-variant-numeric: tabular-nums;
        }

        .booking-chart-readout-label {
            display: block;
            color: var(--booking-muted);
            font-size: 14px;
        }

        .booking-chart-readout-value {
            display: block;
            color: var(--booking-ink);
            font-weight: 500;
        }

        .booking-chart-readout-value.is-updating {
            animation: booking-chart-value-in 180ms ease-out both;
        }

        @keyframes booking-chart-value-in {
            from { opacity: .55; transform: translateY(2px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .booking-chart-highlight {
            min-width: 180px;
            padding-left: 20px;
            border-left: 1px solid var(--booking-line-soft);
            line-height: 1.4;
            font-variant-numeric: tabular-nums;
        }

        .booking-chart-highlight-label {
            margin-bottom: 4px;
            color: var(--booking-muted);
            font-size: 14px;
        }

        .booking-chart-details summary {
            width: fit-content;
            color: var(--booking-ink);
            cursor: pointer;
            font-weight: 500;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .booking-chart-details summary:focus-visible {
            outline: 3px solid rgba(17, 17, 17, .2);
            outline-offset: 3px;
        }

        .booking-admin-page details > summary.btn {
            list-style: none;
            cursor: pointer;
        }

        .booking-admin-page details > summary.btn::-webkit-details-marker {
            display: none;
        }

        .booking-payment-modal .modal-dialog {
            max-width: 640px;
        }

        .booking-cancel-modal .modal-dialog {
            max-width: 560px;
        }

        .booking-payment-modal .modal-content {
            overflow: hidden;
            border: 1px solid var(--booking-line-soft);
            border-radius: 8px;
            background: #ffffff;
            box-shadow: none;
        }

        .booking-payment-modal .modal-header,
        .booking-payment-modal .modal-footer {
            padding: 20px 24px;
            border-color: var(--booking-line-soft);
        }

        .booking-payment-modal .modal-title {
            color: var(--booking-ink);
            font-size: 24px;
            font-weight: 500;
            line-height: 1.25;
        }

        .booking-payment-modal .modal-body {
            padding: 24px;
        }

        .booking-payment-modal .modal-footer {
            justify-content: space-between;
        }

        .booking-payment-modal-kicker {
            margin-bottom: 5px;
            color: var(--booking-muted);
            font-size: 13px;
            font-weight: 500;
            letter-spacing: .03em;
        }

        .booking-payment-context {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 12px 24px;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--booking-line-soft);
        }

        .booking-payment-context > div {
            display: grid;
            gap: 3px;
        }

        .booking-payment-context strong {
            color: var(--booking-ink);
            font-size: 15px;
            font-weight: 500;
        }

        .booking-payment-context span {
            color: var(--booking-muted);
            font-size: 14px;
        }

        .booking-payment-facts {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin: 20px 0;
            overflow: hidden;
            border-radius: 8px;
            background: var(--booking-cloud);
        }

        .booking-payment-fact {
            display: grid;
            gap: 5px;
            min-width: 0;
            padding: 14px 16px;
        }

        .booking-payment-fact + .booking-payment-fact {
            border-left: 1px solid var(--booking-line-soft);
        }

        .booking-payment-fact span {
            color: var(--booking-muted);
            font-size: 13px;
        }

        .booking-payment-fact strong {
            color: var(--booking-ink);
            font-size: 15px;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .booking-payment-claim {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 8px 16px;
            margin-top: 18px;
            padding: 12px 0;
            border-block: 1px solid var(--booking-line-soft);
            color: var(--booking-charcoal);
            font-size: 14px;
        }

        .booking-payment-claim strong {
            color: var(--booking-ink);
            font-weight: 500;
        }

        .booking-payment-proof-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 14px;
            padding: 0;
            border: 0;
            background: transparent;
            color: var(--booking-ink);
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .booking-payment-proof-link:focus-visible {
            outline: 3px solid rgba(17, 17, 17, .22);
            outline-offset: 3px;
        }

        .booking-proof-modal .modal-dialog {
            max-width: 1040px;
        }

        .booking-proof-modal .modal-content {
            overflow: hidden;
            border: 1px solid var(--booking-line-soft);
            border-radius: 8px;
            background: #ffffff;
            box-shadow: none;
        }

        .booking-proof-modal .modal-header,
        .booking-proof-modal .modal-footer {
            padding: 16px 20px;
            border-color: var(--booking-line-soft);
        }

        .booking-proof-modal .modal-body {
            padding: 0;
            background: var(--booking-cloud);
        }

        .booking-proof-frame {
            display: block;
            width: 100%;
            height: min(76vh, 820px);
            min-height: 420px;
            border: 0;
            background: var(--booking-cloud);
        }

        .booking-proof-modal .modal-footer {
            justify-content: space-between;
        }

        .booking-admin-page .pagination {
            gap: 4px;
        }

        .booking-admin-page .page-link {
            min-width: 40px;
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 9999px !important;
            background: var(--booking-cloud);
            color: var(--booking-ink);
        }

        .booking-admin-page .page-item.active .page-link {
            background: var(--booking-ink);
            color: #ffffff;
        }

        @media (max-width: 1199.98px) {
            .booking-income-overview {
                grid-template-columns: minmax(240px, 1fr) repeat(2, minmax(180px, .85fr));
            }

            .booking-income-primary,
            .booking-income-breakdown {
                padding: 20px;
            }

            .booking-report-summary {
                grid-template-columns: 1fr 2fr;
            }

            .booking-report-summary > a {
                grid-column: 1 / -1;
                justify-self: start;
            }
        }

        @media (max-width: 767.98px) {
            .booking-page-header {
                display: block;
                margin-bottom: 24px;
            }

            .booking-page-heading h1 {
                font-size: 28px;
            }

            .booking-page-actions {
                justify-content: flex-start;
                margin-top: 20px;
            }

            .booking-income-overview {
                grid-template-columns: 1fr 1fr;
                margin-bottom: 24px !important;
            }

            .booking-income-primary {
                grid-column: 1 / -1;
            }

            .booking-income-breakdown {
                border-top: 1px solid var(--booking-line-soft);
                border-left: 0;
            }

            .booking-status-strip {
                grid-template-columns: 1fr;
                margin-bottom: 24px;
            }

            .booking-status-item + .booking-status-item {
                border-top: 1px solid var(--booking-line-soft);
                border-left: 0;
            }

            .booking-report-summary {
                grid-template-columns: 1fr;
                align-items: start;
            }

            .booking-report-summary > a {
                grid-column: auto;
            }

            .booking-chart-highlight {
                padding: 12px 0 0;
                border-top: 1px solid var(--booking-line-soft);
                border-left: 0;
            }

            .booking-filter-grid {
                grid-template-columns: 1fr 1fr;
            }

            .booking-admin-page > section,
            .booking-admin-page > form.card {
                margin-bottom: 24px !important;
            }
        }

        @media (max-width: 575.98px) {
            .booking-page-actions .btn {
                flex: 1 1 calc(50% - 8px);
                min-height: 44px;
                padding-inline: 12px;
                white-space: normal;
            }

            .booking-income-overview {
                grid-template-columns: 1fr;
            }

            .booking-income-primary {
                grid-column: auto;
            }

            .booking-income-breakdown {
                border-top: 1px solid var(--booking-line-soft);
                border-left: 0;
            }

            .booking-income-total {
                font-size: 30px;
                white-space: normal;
            }

            .booking-filter-grid {
                grid-template-columns: 1fr;
            }

            .booking-chart-frame {
                height: 250px;
            }

            .booking-payment-modal .modal-dialog {
                margin: 8px;
            }

            .booking-payment-modal .modal-header,
            .booking-payment-modal .modal-body,
            .booking-payment-modal .modal-footer {
                padding: 18px 16px;
            }

            .booking-proof-modal .modal-dialog {
                margin: 8px;
            }

            .booking-proof-modal .modal-header,
            .booking-proof-modal .modal-footer {
                padding: 14px 16px;
            }

            .booking-proof-frame {
                height: 72vh;
                min-height: 320px;
            }

            .booking-payment-facts {
                grid-template-columns: 1fr;
            }

            .booking-payment-fact + .booking-payment-fact {
                border-top: 1px solid var(--booking-line-soft);
                border-left: 0;
            }

            .booking-chart-readout {
                grid-template-columns: 1fr 1fr;
                gap: 12px 20px;
            }

            .booking-chart-readout > div:last-child {
                grid-column: 1 / -1;
            }

            .booking-admin-page .card-header,
            .booking-admin-page .card-body {
                padding: 18px 16px;
            }

            .booking-status-item {
                padding-inline: 16px;
            }

            .booking-admin-page .btn {
                min-height: 44px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .booking-admin-page *,
            .booking-admin-page *::before,
            .booking-admin-page *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
@endpush

@section('content')
    <main class="booking-admin-page">
        <header class="booking-page-header">
            <div class="booking-page-heading">
                <h1>Booking Lapangan</h1>
                <p>Kelola jadwal, verifikasi pembayaran, dan pantau perlengkapan yang disewa.</p>
            </div>
            <nav class="booking-page-actions" aria-label="Aksi booking lapangan">
                <a href="{{ route('admin.court-bookings.manual.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i>Catat booking langsung
                </a>
                <a href="{{ route('booking.index') }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">
                    <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>Halaman publik
                </a>
                <a href="{{ route('admin.court-rentals.settings') }}" class="btn btn-outline-primary">
                    <i class="bi bi-box-seam" aria-hidden="true"></i>Katalog perlengkapan
                </a>
                <a href="{{ route('admin.court-bookings.settings') }}" class="btn btn-outline-primary">
                    <i class="bi bi-sliders" aria-hidden="true"></i>Pengaturan booking
                </a>
            </nav>
        </header>

        @if(session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
        @endif

        <section class="card booking-income-overview" aria-label="Ringkasan pemasukan">
            <div class="booking-income-primary">
                <span class="booking-metric-label">Total pemasukan tercatat</span>
                <strong class="booking-income-total">Rp {{ number_format((float) $totalIncome, 0, ',', '.') }}</strong>
                <span class="booking-metric-note">Pembayaran terverifikasi, perlengkapan, serta pemasukan parkir dan toko yang dicatat terpisah; belum dikurangi refund</span>
            </div>
            <div class="booking-income-breakdown">
                <span class="booking-metric-label">Pembayaran booking diterima</span>
                <strong class="booking-income-value">Rp {{ number_format((float) $courtIncome, 0, ',', '.') }}</strong>
                <span class="booking-metric-note">Mencakup tagihan lapangan dan perlengkapan awal</span>
            </div>
            <div class="booking-income-breakdown">
                <span class="booking-metric-label">Nilai perlengkapan</span>
                <strong class="booking-income-value">Rp {{ number_format((float) $rentalIncome, 0, ',', '.') }}</strong>
                <span class="booking-metric-note">Rincian nilai barang; pembayaran tercatat pada total</span>
            </div>
        </section>

        <section class="booking-status-strip" aria-label="Ringkasan status booking">
            <div class="booking-status-item">
                <span class="booking-status-label">Perlu verifikasi pembayaran</span>
                <strong class="booking-status-count">{{ $counts['pending_review'] }}</strong>
            </div>
            <div class="booking-status-item">
                <span class="booking-status-label">Menunggu pembayaran</span>
                <strong class="booking-status-count">{{ $counts['awaiting_payment'] }}</strong>
            </div>
            <div class="booking-status-item">
                <span class="booking-status-label">Booking terkonfirmasi</span>
                <strong class="booking-status-count">{{ $counts['confirmed'] }}</strong>
            </div>
        </section>

        <section class="card mb-4" aria-labelledby="dailyReportSummaryTitle">
            <div class="card-body booking-report-summary">
                <div>
                    <h5 id="dailyReportSummaryTitle">Laporan operasional harian</h5>
                    <p class="text-secondary small mb-0">Rekap booking dan pembayaran; parkir serta toko dicatat terpisah per tanggal.</p>
                </div>
                <div class="booking-report-facts" aria-label="Ringkasan booking hari ini">
                    <div class="booking-report-fact">
                        <span class="small text-secondary">Hari ini</span>
                        <strong>{{ \Carbon\CarbonImmutable::parse($todayReportDate)->locale('id')->translatedFormat('d M Y') }}</strong>
                    </div>
                    <div class="booking-report-fact">
                        <span class="small text-secondary">Booking terkonfirmasi</span>
                        <strong>{{ number_format($todayConfirmedCount) }}</strong>
                    </div>
                    <div class="booking-report-fact">
                        <span class="small text-secondary">Jam terpesan</span>
                        <strong>{{ intdiv($todayConfirmedMinutes, 60) }}j {{ $todayConfirmedMinutes % 60 }}m</strong>
                    </div>
                    <div class="booking-report-fact">
                        <span class="small text-secondary">Pembayaran diterima</span>
                        <strong>Rp {{ number_format($todayReceivedPayments, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <a href="{{ route('admin.court-bookings.reports.daily', ['date' => $todayReportDate]) }}" class="btn btn-outline-primary text-nowrap">
                    <i class="bi bi-clipboard-data me-1" aria-hidden="true"></i>Buka laporan
                </a>
            </div>
        </section>

    <section class="card mb-4" aria-labelledby="rentalIncomeTitle">
        <div class="card-header">
                        <h5 id="rentalIncomeTitle">Nilai perlengkapan</h5>
            <p class="text-secondary mb-0">Nilai barang dari perlengkapan awal, tambahan booking, dan pesanan mandiri yang sudah dikonfirmasi. Nilai ini merupakan rincian transaksi, bukan tambahan di luar pembayaran booking.</p>
        </div>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead><tr><th scope="col">Perlengkapan</th><th scope="col" class="text-end">Jumlah disewa</th><th scope="col" class="text-end">Nilai transaksi</th></tr></thead>
                <tbody>
                    @forelse($rentalIncomeByItem as $itemIncome)
                        <tr>
                            <th scope="row" class="fw-normal">{{ $itemIncome->name }}</th>
                            <td class="text-end">{{ number_format((int) $itemIncome->quantity) }}</td>
                    <td class="text-end booking-money">Rp {{ number_format((float) $itemIncome->income, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-secondary py-3">Belum ada transaksi perlengkapan yang terkonfirmasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="card mb-4" aria-labelledby="bookingActivityTitle">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <h5 id="bookingActivityTitle">Aktivitas transaksi per bulan</h5>
                    <p class="text-secondary mb-0">12 bulan terakhir
                    ({{ $chartStart->format('m/Y') }}–{{ $chartEnd->format('m/Y') }}), dikelompokkan menurut tanggal sewa.
                    Booking lapangan dihitung dari pembayaran yang sudah diverifikasi; nilai tambahan perlengkapan dan pesanan mandiri berasal dari transaksi terkonfirmasi.</p>
            </div>
            @if($busiestMonths)
                <div class="booking-chart-highlight">
                    <div class="booking-chart-highlight-label">Puncak periode</div>
                    <div class="fw-semibold">{{ $busiestMonths }} <span class="text-secondary">·
                            {{ number_format($maximumMonthlyBookings) }} transaksi</span></div>
                </div>
            @endif
        </div>
        <div class="card-body">
            @if($maximumMonthlyBookings > 0)
                @php
                    $chartMonths = collect($monthlyBookingChart)->values();
                    $activeIndex = (int) $chartMonths->search(fn ($month) => (int) $month['booking_count'] === (int) $maximumMonthlyBookings);
                    $activeMonth = $chartMonths[$activeIndex];
                    $chartPayload = $chartMonths->map(fn ($month) => [
                        'label' => $month['label'],
                        'year' => (string) $month['year'],
                        'booking_count' => (int) $month['booking_count'],
                        'booking_value' => (float) $month['booking_value'],
                    ])->all();
                @endphp
                <figure class="mb-0" data-booking-chart aria-labelledby="bookingActivityTitle">
                    <figcaption id="bookingChartInstructions" class="visually-hidden">
                        Grafik jumlah transaksi terkonfirmasi per bulan. Arahkan kursor ke batang atau gunakan tombol panah
                        setelah fokus pada grafik untuk melihat nilai. Rincian lengkap juga tersedia pada tabel di bawah.
                    </figcaption>
                    <div class="booking-chart-frame">
                        <canvas class="booking-chart-canvas" data-booking-chart-canvas role="img" tabindex="0"
                            aria-describedby="bookingChartInstructions"
                            aria-label="Grafik transaksi terkonfirmasi selama 12 bulan. Bulan teramai {{ $busiestMonths }} dengan {{ number_format($maximumMonthlyBookings) }} transaksi.">
                            Grafik transaksi terkonfirmasi selama 12 bulan. Bulan teramai {{ $busiestMonths }} dengan
                            {{ number_format($maximumMonthlyBookings) }} transaksi.
                        </canvas>
                    </div>
                    <script type="application/json" data-booking-chart-data>@json($chartPayload)</script>
                    <div class="booking-chart-readout" aria-live="polite" aria-atomic="true">
                        <div>
                            <span class="booking-chart-readout-label">Bulan dipilih</span>
                            <span class="booking-chart-readout-value" data-chart-readout="label">{{ $activeMonth['label'] }} {{ $activeMonth['year'] }}</span>
                        </div>
                        <div>
                            <span class="booking-chart-readout-label">Transaksi terkonfirmasi</span>
                            <span class="booking-chart-readout-value" data-chart-readout="count">{{ number_format((int) $activeMonth['booking_count']) }}</span>
                        </div>
                        <div>
                            <span class="booking-chart-readout-label">Pembayaran tercatat</span>
                            <span class="booking-chart-readout-value" data-chart-readout="value">Rp {{ number_format($activeMonth['booking_value'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </figure>
            @else
                <p class="text-secondary mb-0">Belum ada booking terkonfirmasi dalam 12 bulan terakhir.</p>
            @endif

            <details class="booking-chart-details mt-3">
                <summary>Lihat jumlah booking dan nilai per bulan</summary>
                <div class="table-responsive mt-3">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Bulan</th>
                                <th scope="col" class="text-end">Transaksi terkonfirmasi</th>
                                <th scope="col" class="text-end">Pembayaran tercatat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyBookingChart as $month)
                                <tr>
                                    <th scope="row" class="fw-normal">{{ $month['label'] }} {{ $month['year'] }}</th>
                                    <td class="text-end">{{ number_format($month['booking_count']) }}</td>
                                    <td class="text-end">Rp {{ number_format($month['booking_value'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </details>
        </div>
    </section>

    @push('scripts')
        @vite('resources/js/admin/court-bookings-chart.js')
    @endpush

    @push('scripts')
        <script>
            (() => {
                const formatThousands = (digits) => digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                document.querySelectorAll('[data-rupiah-input]').forEach((input) => {
                    const form = input.closest('form');
                    const rawValue = form?.querySelector('[data-rupiah-value]');
                    if (!form || !rawValue) return;

                    const syncValue = (preserveCaret = false) => {
                        const previousValue = input.value;
                        const previousCaret = input.selectionStart ?? previousValue.length;
                        const digitsBeforeCaret = previousValue.slice(0, previousCaret).replace(/\D/g, '').length;
                        const digits = previousValue.replace(/\D/g, '').replace(/^0+(?=\d)/, '');
                        const formatted = formatThousands(digits);

                        input.value = formatted;
                        rawValue.value = digits;

                        if (preserveCaret) {
                            let caret = 0;
                            let countedDigits = 0;
                            while (caret < formatted.length && countedDigits < digitsBeforeCaret) {
                                if (/\d/.test(formatted[caret])) countedDigits++;
                                caret++;
                            }
                            input.setSelectionRange(caret, caret);
                        }
                    };

                    input.addEventListener('input', () => syncValue(true));
                    input.addEventListener('blur', () => syncValue());
                    form.addEventListener('submit', () => syncValue());
                    syncValue();
                });
            })();
        </script>
    @endpush

    <form method="GET" action="{{ route('admin.court-bookings.index') }}" class="card mb-3">
        <div class="card-header">
            <h5>Filter booking</h5>
        </div>
        <div class="card-body booking-filter-grid">
            <div>
                <label class="form-label" for="filterDate">Tanggal booking</label>
                <input class="form-control" type="date" id="filterDate" name="date" value="{{ $filters['date'] ?? '' }}">
            </div>
            <div>
                <label class="form-label" for="filterStatus">Status booking / tambahan</label>
                <select class="form-select" id="filterStatus" name="status">
                    <option value="">Semua status</option>
                    @foreach([
                            'awaiting_payment' => 'Menunggu pembayaran',
                            'pending_review' => 'Menunggu verifikasi',
                            'confirmed' => 'Terkonfirmasi',
                            'rejected' => 'Ditolak',
                            'cancelled' => 'Dibatalkan',
                            'expired' => 'Kedaluwarsa',
                        ] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1" aria-hidden="true"></i>Terapkan</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.court-bookings.index') }}">Reset</a>
        </div>
    </form>

    <section class="card" aria-labelledby="bookingListTitle">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 id="bookingListTitle">Daftar booking</h5>
                <p class="text-secondary mb-0">Jadwal, pembayaran, status, dan tindakan untuk setiap pemesanan.</p>
            </div>
            <span class="booking-list-count">{{ number_format($bookings->total()) }} booking</span>
        </div>
        <div class="table-responsive booking-table-wrap" role="region" aria-label="Daftar booking, gulir mendatar untuk melihat semua kolom" tabindex="0">
            <table class="table align-middle booking-table">
                <caption class="visually-hidden">Daftar booking lapangan beserta jadwal, pemesan, pembayaran, status, dan tindakan.</caption>
                <thead>
                    <tr>
                        <th scope="col">Jadwal</th>
                        <th scope="col">Pemesan</th>
                        <th scope="col">Pembayaran</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="booking-actions-heading">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        @php
                            $confirmedRentalAdditions = $booking->rentalAdditions->where('status', \App\Models\CourtBookingRentalAddition::STATUS_CONFIRMED);
                            $hasRentalItems = $booking->rentalLines->isNotEmpty() || $confirmedRentalAdditions->contains(fn ($addition) => $addition->lines->isNotEmpty());
                            $hasUnresolvedAdditions = $booking->rentalAdditions->contains(fn ($addition) => in_array($addition->status, [
                                \App\Models\CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT,
                                \App\Models\CourtBookingRentalAddition::STATUS_PENDING_REVIEW,
                            ], true));
                            $bookingStart = \Carbon\Carbon::parse($booking->booking_date->toDateString().' '.$booking->time_start, \App\Models\CourtBooking::TIMEZONE);
                            $paidAmount = (float) $booking->payments->where('status', \App\Models\CourtBookingPayment::STATUS_CONFIRMED)->sum(fn ($payment) => $payment->recognized_amount);
                            $pendingPayment = $booking->payments->firstWhere('status', \App\Models\CourtBookingPayment::STATUS_PENDING_REVIEW);
                            $remainingAmount = in_array($booking->status, ['confirmed', 'pending_review', 'awaiting_payment'], true)
                                ? max(0, (float) $booking->amount - $paidAmount)
                                : null;
                            $canAddRentalItems = $booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED
                                && !$booking->rental_picked_up_at
                                && !$booking->rental_returned_at
                                && $bookingStart->isAfter(now(\App\Models\CourtBooking::TIMEZONE));
                        @endphp
                        <tr>
                            <td style="min-width:190px">
                                <div class="fw-semibold">{{ $booking->court?->name ?? 'Lapangan dihapus' }}</div>
                                <div>{{ $booking->booking_date->format('d M Y') }} ·
                                    {{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }} ·
                                    {{ $booking->duration_label }}</div>
                                <small
                                    class="text-secondary">#{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</small>
                                @if($booking->source === 'walk_in')
                                    <div><span class="badge text-bg-light border">Walk-in</span><small class="text-secondary ms-1">{{ $booking->createdBy?->name }}</small></div>
                                @endif
                            </td>
                            <td style="min-width:180px">
                                <div class="fw-semibold">{{ $booking->customer_name }}</div>
                                @php
                                    $waPhone = preg_replace('/\D+/', '', $booking->customer_phone);
                                    $waPhone = str_starts_with($waPhone, '0') ? '62' . substr($waPhone, 1) : (str_starts_with($waPhone, '8') ? '62' . $waPhone : $waPhone);
                                @endphp
                                <a href="https://wa.me/{{ $waPhone }}" target="_blank"
                                    rel="noopener">{{ $booking->customer_phone }}</a>
                                @if($booking->customer_email)
                                <div class="small text-secondary">{{ $booking->customer_email }}</div>@endif
                                <div class="small text-secondary">Tim: <strong class="text-body">{{ $booking->team_name ?: 'Belum dicatat' }}</strong></div>
                            </td>
                            <td style="min-width:180px">
                                <div class="booking-money">Rp {{ number_format((float) $booking->amount, 0, ',', '.') }}</div>
                                <div class="small text-secondary">{{ $booking->payment_method_label }}</div>
                                <div class="small">Diterima: Rp {{ number_format($paidAmount, 0, ',', '.') }}</div>
                                @if($remainingAmount !== null)
                                    <div class="small text-secondary">Sisa: Rp {{ number_format($remainingAmount, 0, ',', '.') }}</div>
                                @endif
                                @foreach($booking->payments as $payment)
                                    <div class="small text-secondary">{{ $payment->stage_label }} · {{ $payment->payment_method_label }} · {{ $payment->status_label }}</div>
                                    <div class="small {{ $payment->status === \App\Models\CourtBookingPayment::STATUS_CONFIRMED ? 'text-success' : 'text-secondary' }}">
                                        @if($payment->status === \App\Models\CourtBookingPayment::STATUS_CONFIRMED)
                                            Nominal terverifikasi: Rp {{ number_format($payment->recognized_amount, 0, ',', '.') }}
                                        @elseif($payment->claimed_amount !== null)
                                            Klaim pelanggan: Rp {{ number_format((float) $payment->claimed_amount, 0, ',', '.') }}
                                        @elseif($payment->status === \App\Models\CourtBookingPayment::STATUS_REJECTED)
                                            Nominal tidak terverifikasi
                                        @else
                                            Nominal menunggu pemeriksaan admin
                                        @endif
                                    </div>
                                    @if($payment->status === \App\Models\CourtBookingPayment::STATUS_CONFIRMED && $payment->claimed_amount !== null && abs((float) $payment->claimed_amount - (float) $payment->verified_amount) >= 0.01)
                                        <div class="small text-secondary">Klaim awal: Rp {{ number_format((float) $payment->claimed_amount, 0, ',', '.') }} · Catatan: {{ $payment->note ?: 'Tidak ada catatan' }}</div>
                                    @endif
                                    @if($payment->payment_reference)<div class="small text-secondary">Ref: {{ $payment->payment_reference }}</div>@endif
                                @endforeach
                                @foreach($booking->rentalLines as $line)
                                    <div class="small text-secondary">{{ $line->item_name_snapshot }} × {{ $line->quantity }} · Rp {{ number_format((float) $line->line_total, 0, ',', '.') }}</div>
                                @endforeach
                                @foreach($booking->rentalAdditions as $addition)
                                    <div class="border-top mt-2 pt-2">
                                        <div class="small fw-semibold">Tambahan perlengkapan · Rp {{ number_format((float) $addition->amount, 0, ',', '.') }}</div>
                                        @foreach($addition->lines as $line)
                                            <div class="small text-secondary">{{ $line->item_name_snapshot }} × {{ $line->quantity }}</div>
                                        @endforeach
                                        <span class="badge {{ $addition->status === 'confirmed' ? 'text-bg-success' : (in_array($addition->status, ['pending_review', 'awaiting_payment'], true) ? 'text-bg-warning' : 'text-bg-secondary') }}">{{ $addition->status_label }}</span>
                                        @if($addition->payment_reference)<div class="small">Ref tambahan: {{ $addition->payment_reference }}</div>@endif
                                        @if($addition->payment_proof_path)
                                            <button class="btn btn-sm btn-outline-primary mt-1" type="button" aria-haspopup="dialog" data-booking-proof-modal="bookingRentalAdditionProof{{ $booking->id }}{{ $addition->id }}">Pratinjau bukti tambahan</button>
                                            <a class="btn btn-sm btn-link mt-1" href="{{ route('admin.court-bookings.rental-additions.proof.download', [$booking, $addition]) }}">Unduh</a>
                                        @endif
                                        @if($addition->status === \App\Models\CourtBookingRentalAddition::STATUS_PENDING_REVIEW)
                                            <div class="d-flex flex-wrap gap-1 mt-2">
                                                <form method="POST" action="{{ route('admin.court-bookings.rental-additions.confirm', [$booking, $addition]) }}" onsubmit="return confirm('Konfirmasi pembayaran tambahan ini?')">
                                                    @csrf<button class="btn btn-sm btn-success" type="submit">Konfirmasi tambahan</button>
                                                </form>
                                                <details><summary class="btn btn-sm btn-outline-danger">Tolak</summary>
                                                    <form class="mt-2" method="POST" action="{{ route('admin.court-bookings.rental-additions.reject', [$booking, $addition]) }}">
                                                        @csrf<input class="form-control form-control-sm mb-2" name="admin_note" maxlength="1000" placeholder="Alasan (opsional)">
                                                        <button class="btn btn-sm btn-danger" type="submit">Tolak bukti tambahan</button>
                                                    </form>
                                                </details>
                                                <form method="POST" action="{{ route('admin.court-bookings.rental-additions.cancel', [$booking, $addition]) }}" onsubmit="return confirm('Batalkan tambahan ini dan lepaskan stoknya?')">
                                                    @csrf<button class="btn btn-sm btn-link text-danger" type="submit">Batalkan tambahan</button>
                                                </form>
                                            </div>
                                        @elseif($addition->status === \App\Models\CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT && $addition->expires_at)
                                            <div class="small text-secondary">Ditahan sampai {{ $addition->expires_at->timezone(\App\Models\CourtBooking::TIMEZONE)->format('H:i') }} WIB</div>
                                        @endif
                                        @if($addition->status === \App\Models\CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT)
                                            <form class="mt-1" method="POST" action="{{ route('admin.court-bookings.rental-additions.cancel', [$booking, $addition]) }}" onsubmit="return confirm('Batalkan tambahan ini dan lepaskan stoknya?')">
                                                @csrf<button class="btn btn-sm btn-link text-danger p-0" type="submit">Batalkan tambahan</button>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                                @if($booking->payment_reference)
                                <div class="small">Ref: {{ $booking->payment_reference }}</div>@endif
                                @if($booking->payment_proof_path)
                                    <button class="btn btn-sm btn-outline-primary mt-1" type="button" aria-haspopup="dialog"
                                        data-booking-proof-modal="bookingPaymentProof{{ $booking->id }}">
                                        <i class="bi bi-eye me-1" aria-hidden="true"></i>Pratinjau bukti
                                    </button>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badge = match ($booking->status) {
                                        'confirmed' => 'text-bg-success',
                                        'pending_review' => 'text-bg-warning',
                                        'awaiting_payment' => 'text-bg-info',
                                        'rejected', 'cancelled' => 'text-bg-danger',
                                        default => 'text-bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ $booking->status_label }}</span>
                                @if($booking->status === 'cancelled' && $booking->cancelledBy)
                                    <div class="small text-secondary mt-1">Dibatalkan oleh {{ $booking->cancelledBy->name }}</div>
                                @elseif($booking->verifiedBy)
                                    <div class="small text-secondary mt-1">Diperiksa oleh {{ $booking->verifiedBy->name }}</div>
                                @endif
                                @if($booking->status === 'confirmed' && $hasRentalItems)
                                    @if($booking->rental_returned_at)
                                        <div class="small text-success mt-2">Perlengkapan kembali {{ $booking->rental_returned_at->timezone(\App\Models\CourtBooking::TIMEZONE)->format('H:i') }} WIB</div>
                                    @elseif($booking->rental_picked_up_at)
                                        <div class="small text-warning-emphasis mt-2">Perlengkapan masih disewa</div>
                                        @if(\Carbon\Carbon::parse($booking->booking_date->toDateString().' '.$booking->time_end, \App\Models\CourtBooking::TIMEZONE)->lessThan(now()->timezone(\App\Models\CourtBooking::TIMEZONE)))
                                            <span class="badge text-bg-warning mt-1">Lewat perkiraan kembali</span>
                                        @endif
                                    @else
                                        <div class="small text-secondary mt-2">Perlengkapan belum diambil</div>
                                    @endif
                                @endif
                            </td>
                            <td class="booking-actions-cell">
                                @if($remainingAmount > 0 && $booking->status === 'confirmed')
                                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#bookingPaymentRecord{{ $booking->id }}" aria-haspopup="dialog">
                                        <i class="bi bi-wallet2" aria-hidden="true"></i>Catat pembayaran / pelunasan
                                    </button>
                                @endif
                                @if($canAddRentalItems)
                                    <a class="btn btn-sm btn-outline-primary mb-2" href="{{ route('admin.court-bookings.rental-additions.create', $booking) }}"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Tambah perlengkapan</a>
                                @endif
                                @if($booking->status === 'confirmed' && $hasRentalItems)
                                    @if($booking->rental_picked_up_at && !$booking->rental_returned_at)
                                        <form class="mb-2" method="POST" action="{{ route('admin.court-bookings.rentals.return', $booking) }}" onsubmit="return confirm('Catat bahwa semua perlengkapan booking ini sudah dikembalikan?')">
                                            @csrf
                                            <button class="btn btn-sm btn-success" type="submit"><i class="bi bi-box-arrow-in-down-left me-1" aria-hidden="true"></i>Catat perlengkapan kembali</button>
                                        </form>
                                    @elseif(!$booking->rental_picked_up_at && !$hasUnresolvedAdditions && $booking->booking_date->toDateString() <= now()->timezone(\App\Models\CourtBooking::TIMEZONE)->toDateString())
                                        <form class="mb-2" method="POST" action="{{ route('admin.court-bookings.rentals.pickup', $booking) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-primary" type="submit"><i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>Catat perlengkapan diambil</button>
                                        </form>
                                    @endif
                                @endif
                                @if(in_array($booking->status, ['pending_review', 'confirmed', 'rejected', 'cancelled'], true))
                                    @php
                                        $bookingCode = str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT);
                                        $bookingUrl = route('booking.show', $booking->public_token);
                                        $pendingPayments = $booking->payments->where('status', \App\Models\CourtBookingPayment::STATUS_PENDING_REVIEW);
                                        $paymentToVerify = (float) $pendingPayments->sum(fn ($payment) => (float) ($payment->claimed_amount ?? 0));
                                        $bookingWhatsAppTitle = match ($booking->status) {
                                            'confirmed' => 'BOOKING TERKONFIRMASI',
                                            'pending_review' => 'PEMBAYARAN SEDANG DIPERIKSA',
                                            'rejected' => 'PEMBAYARAN BELUM TERVERIFIKASI',
                                            'cancelled' => 'BOOKING DIBATALKAN',
                                        };
                                        $bookingWhatsAppIntro = match ($booking->status) {
                                            'confirmed' => 'Pembayaran Anda telah diverifikasi. Booking lapangan Anda resmi dikonfirmasi.',
                                            'pending_review' => 'Bukti pembayaran Anda sudah kami terima dan sedang diperiksa oleh admin.',
                                            'rejected' => 'Pembayaran Anda belum dapat kami verifikasi. Silakan hubungi admin untuk bantuan.',
                                            'cancelled' => 'Booking lapangan Anda telah dibatalkan. Silakan hubungi admin jika membutuhkan bantuan lebih lanjut.',
                                        };
                                        $bookingWhatsAppLines = [
                                            '*OFS FUTSAL CENTER*',
                                            '*' . $bookingWhatsAppTitle . '*',
                                            '',
                                            'Halo ' . $booking->customer_name . ',',
                                            $bookingWhatsAppIntro,
                                            '',
                                            '*DETAIL BOOKING*',
                                            '*Kode booking:* #' . $bookingCode,
                                            '*Status:* ' . $booking->status_label,
                                            '*Nama tim:* ' . ($booking->team_name ?: 'Belum dicatat'),
                                            '*Lapangan:* ' . ($booking->court?->name ?? 'Tidak tersedia'),
                                            '*Tanggal:* ' . $booking->booking_date->translatedFormat('l, d F Y'),
                                            '*Waktu:* ' . substr($booking->time_start, 0, 5) . '–' . substr($booking->time_end, 0, 5) . ' WIB',
                                            '*Durasi:* ' . $booking->duration_label,
                                            '*Total tagihan:* Rp ' . number_format((float) $booking->amount, 0, ',', '.'),
                                        ];
                                        if ($booking->status === 'pending_review') {
                                            $bookingWhatsAppLines[] = $paymentToVerify > 0
                                                ? '*Klaim pembayaran menunggu verifikasi:* Rp ' . number_format($paymentToVerify, 0, ',', '.')
                                                : 'Nominal pembayaran akan dicocokkan dan dicatat admin setelah pemeriksaan bukti.';
                                        } elseif ($booking->status === 'confirmed') {
                                            $bookingWhatsAppLines[] = '*Pembayaran terverifikasi:* Rp ' . number_format($paidAmount, 0, ',', '.');
                                            $bookingWhatsAppLines[] = '*Sisa tagihan:* Rp ' . number_format($remainingAmount, 0, ',', '.');
                                        }
                                        foreach ($booking->rentalLines as $line) {
                                            $bookingWhatsAppLines[] = '*Sewa ' . $line->item_name_snapshot . ':* ' . $line->quantity . ' × Rp ' . number_format((float) $line->unit_price, 0, ',', '.') . ' = Rp ' . number_format((float) $line->line_total, 0, ',', '.');
                                        }
                                        foreach ($booking->rentalAdditions as $addition) {
                                            $bookingWhatsAppLines[] = '*Tambahan perlengkapan (' . $addition->status_label . '):* Rp ' . number_format((float) $addition->amount, 0, ',', '.');
                                            foreach ($addition->lines as $line) {
                                                $bookingWhatsAppLines[] = '  · ' . $line->item_name_snapshot . ' × ' . $line->quantity . ' = Rp ' . number_format((float) $line->line_total, 0, ',', '.');
                                            }
                                        }
                                        if ($booking->admin_note && in_array($booking->status, ['rejected', 'cancelled'], true)) {
                                            $bookingWhatsAppLines[] = '*Catatan admin:* ' . $booking->admin_note;
                                        }
                                        $bookingWhatsAppLines = array_merge($bookingWhatsAppLines, [
                                            '',
                                            '*Lihat detail dan status booking:*',
                                            $bookingUrl,
                                            '',
                                            'Terima kasih telah memilih OFS Futsal Center.',
                                        ]);
                                        $bookingWhatsAppMessage = implode("\n", $bookingWhatsAppLines);
                                    @endphp
                                    <a class="btn btn-sm btn-outline-success mb-2"
                                        href="https://wa.me/{{ $waPhone }}?text={{ rawurlencode($bookingWhatsAppMessage) }}"
                                        target="_blank" rel="noopener noreferrer">
                                        <i class="bi bi-whatsapp me-1" aria-hidden="true"></i>Kirim info ke WhatsApp
                                    </a>
                                    <div class="small text-secondary mb-2">Pesan siap dikirim; admin tetap perlu menekan tombol
                                        kirim di WhatsApp.</div>
                                @endif
                                @if($booking->status === 'pending_review')
                                    <div class="d-flex flex-wrap justify-content-end gap-2">
                                        @if($pendingPayment)
                                            <button class="btn btn-sm btn-success" type="button" data-bs-toggle="modal" data-bs-target="#bookingPaymentVerification{{ $booking->id }}" aria-haspopup="dialog">
                                                <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>Verifikasi pembayaran
                                            </button>
                                        @else
                                            <span class="small text-secondary">Tidak ada pembayaran yang menunggu verifikasi.</span>
                                        @endif
                                        <details class="text-start">
                                            <summary class="btn btn-sm btn-outline-danger">Tolak</summary>
                                            <form class="mt-2" method="POST"
                                                action="{{ route('admin.court-bookings.reject', $booking) }}">
                                                @csrf
                                                <input class="form-control form-control-sm mb-2" name="admin_note" maxlength="1000"
                                                    placeholder="Alasan (opsional)">
                                                <button class="btn btn-sm btn-danger" type="submit">Tolak bukti</button>
                                            </form>
                                        </details>
                                    </div>
                                @elseif($booking->status === 'awaiting_payment' || ($booking->status === 'confirmed' && !$booking->rental_picked_up_at))
                                    <button class="btn btn-sm btn-outline-danger" type="button"
                                        data-bs-toggle="modal" data-bs-target="#bookingCancellation{{ $booking->id }}"
                                        aria-haspopup="dialog" aria-controls="bookingCancellation{{ $booking->id }}">
                                        <i class="bi bi-x-circle" aria-hidden="true"></i>Batalkan booking
                                    </button>
                                @else
                                    <span class="text-secondary small">Tidak ada tindakan tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-5">Belum ada booking pada filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
            <div class="card-body">{{ $bookings->links() }}</div>
        @endif
    </section>

    @foreach($bookings as $booking)
        @if($booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED)
            @php
                $recordPaymentPaid = (float) $booking->payments
                    ->where('status', \App\Models\CourtBookingPayment::STATUS_CONFIRMED)
                    ->sum(fn ($payment) => $payment->recognized_amount);
                $recordPaymentBalance = max(0, (float) $booking->amount - $recordPaymentPaid);
                $recordPaymentOldInput = (string) old('_record_payment_booking_id', '') === (string) $booking->id;
                $recordPaymentDefaultAmount = $recordPaymentOldInput ? old('amount') : ($recordPaymentBalance > 0 ? (int) $recordPaymentBalance : '');
            @endphp

            @if($recordPaymentBalance > 0)
                <div class="modal fade booking-payment-modal" id="bookingPaymentRecord{{ $booking->id }}" tabindex="-1" aria-labelledby="bookingPaymentRecordTitle{{ $booking->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admin.court-bookings.payments.store', $booking) }}" onsubmit="return confirm('Simpan pembayaran yang sudah diterima?')">
                                @csrf
                                <input type="hidden" name="_record_payment_booking_id" value="{{ $booking->id }}">

                                <div class="modal-header">
                                    <div>
                                        <div class="booking-payment-modal-kicker">PEMBAYARAN BOOKING · #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        <h2 class="modal-title" id="bookingPaymentRecordTitle{{ $booking->id }}">Catat pembayaran</h2>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="booking-payment-context">
                                        <div>
                                            <strong>{{ $booking->customer_name }}</strong>
                                            <span>{{ $booking->team_name ?: 'Nama tim belum dicatat' }}</span>
                                        </div>
                                        <div>
                                            <strong>{{ $booking->court?->name ?? 'Lapangan dihapus' }}</strong>
                                            <span>{{ $booking->booking_date->translatedFormat('l, d F Y') }} · {{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }} WIB</span>
                                        </div>
                                    </div>

                                    <div class="booking-payment-facts" aria-label="Ringkasan pembayaran booking">
                                        <div class="booking-payment-fact">
                                            <span>Total tagihan</span>
                                            <strong>Rp {{ number_format((float) $booking->amount, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="booking-payment-fact">
                                            <span>Sudah diterima</span>
                                            <strong>Rp {{ number_format($recordPaymentPaid, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="booking-payment-fact">
                                            <span>Sisa tagihan</span>
                                            <strong>Rp {{ number_format($recordPaymentBalance, 0, ',', '.') }}</strong>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label class="form-label fw-medium" for="recordPaymentAmount{{ $booking->id }}">Nominal yang benar-benar diterima</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input class="form-control" id="recordPaymentAmount{{ $booking->id }}" type="text" inputmode="numeric" autocomplete="off" spellcheck="false" value="{{ $recordPaymentDefaultAmount }}" placeholder="Masukkan nominal pembayaran" data-rupiah-input required aria-describedby="recordPaymentAmountHelp{{ $booking->id }}">
                                            <input type="hidden" name="amount" value="{{ $recordPaymentOldInput ? preg_replace('/\D+/', '', (string) old('amount')) : '' }}" data-rupiah-value>
                                        </div>
                                        <div class="form-text" id="recordPaymentAmountHelp{{ $booking->id }}">Pembayaran boleh sebagian. Nominal tidak boleh melebihi sisa tagihan.</div>
                                        @if($recordPaymentOldInput && $errors->has('amount'))
                                            <div class="text-danger small mt-1">{{ $errors->first('amount') }}</div>
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <label class="form-label" for="recordPaymentMethod{{ $booking->id }}">Metode pembayaran</label>
                                        <select class="form-select" id="recordPaymentMethod{{ $booking->id }}" name="payment_method" required>
                                            <option value="cash" @selected(!$recordPaymentOldInput || old('payment_method') === 'cash')>Tunai</option>
                                            <option value="bank_transfer" @selected($recordPaymentOldInput && old('payment_method') === 'bank_transfer')>Transfer bank</option>
                                            <option value="qris" @selected($recordPaymentOldInput && old('payment_method') === 'qris')>QRIS</option>
                                        </select>
                                        @if($recordPaymentOldInput && $errors->has('payment_method'))
                                            <div class="text-danger small mt-1">{{ $errors->first('payment_method') }}</div>
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <label class="form-label" for="recordPaymentReference{{ $booking->id }}">Referensi pembayaran <span class="text-secondary fw-normal">(opsional)</span></label>
                                        <input class="form-control" id="recordPaymentReference{{ $booking->id }}" name="payment_reference" maxlength="100" value="{{ $recordPaymentOldInput ? old('payment_reference') : '' }}" placeholder="Nomor referensi transfer atau QRIS">
                                        @if($recordPaymentOldInput && $errors->has('payment_reference'))
                                            <div class="text-danger small mt-1">{{ $errors->first('payment_reference') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check2-circle" aria-hidden="true"></i>Simpan pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        @if($booking->payment_proof_path)
            <div class="modal fade booking-proof-modal" id="bookingPaymentProof{{ $booking->id }}" tabindex="-1" aria-labelledby="bookingPaymentProofTitle{{ $booking->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>
                                <div class="booking-payment-modal-kicker">BUKTI PEMBAYARAN · #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                <h2 class="modal-title fs-5" id="bookingPaymentProofTitle{{ $booking->id }}">Pratinjau bukti</h2>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup pratinjau"></button>
                        </div>
                        <div class="modal-body">
                            <iframe class="booking-proof-frame"
                                src="{{ route('admin.court-bookings.proof.preview', $booking) }}"
                                title="Pratinjau bukti pembayaran booking #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}"
                                loading="lazy"></iframe>
                        </div>
                        <div class="modal-footer">
                                <span class="small text-secondary text-break">{{ $booking->payment_proof_original_name ?: 'Bukti pembayaran booking' }}</span>
                            <a class="btn btn-primary" href="{{ route('admin.court-bookings.proof.download', $booking) }}">
                                <i class="bi bi-download" aria-hidden="true"></i>Unduh bukti
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @foreach($booking->rentalAdditions as $addition)
            @if($addition->payment_proof_path)
                <div class="modal fade booking-proof-modal" id="bookingRentalAdditionProof{{ $booking->id }}{{ $addition->id }}" tabindex="-1" aria-labelledby="bookingRentalAdditionProofTitle{{ $booking->id }}{{ $addition->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div>
                                    <div class="booking-payment-modal-kicker">BUKTI PEMBAYARAN TAMBAHAN · #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                    <h2 class="modal-title fs-5" id="bookingRentalAdditionProofTitle{{ $booking->id }}{{ $addition->id }}">Pratinjau bukti tambahan</h2>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup pratinjau"></button>
                            </div>
                            <div class="modal-body">
                                <iframe class="booking-proof-frame"
                                    src="{{ route('admin.court-bookings.rental-additions.proof', [$booking, $addition]) }}"
                                    title="Pratinjau bukti pembayaran tambahan booking #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}"
                                    loading="lazy"></iframe>
                            </div>
                            <div class="modal-footer">
                                <span class="small text-secondary text-break">{{ $addition->payment_proof_original_name ?: 'Bukti pembayaran tambahan' }}</span>
                                <a class="btn btn-primary" href="{{ route('admin.court-bookings.rental-additions.proof.download', [$booking, $addition]) }}">
                                    <i class="bi bi-download" aria-hidden="true"></i>Unduh bukti
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        @if($booking->status === \App\Models\CourtBooking::STATUS_PENDING_REVIEW)
            @php
                $verificationPayment = $booking->payments->firstWhere('status', \App\Models\CourtBookingPayment::STATUS_PENDING_REVIEW);
                $verificationPaidAmount = (float) $booking->payments
                    ->where('status', \App\Models\CourtBookingPayment::STATUS_CONFIRMED)
                    ->sum(fn ($payment) => $payment->recognized_amount);
                $verificationRemaining = max(0, (float) $booking->amount - $verificationPaidAmount);
                $verificationCode = str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT);
                $verificationOldInput = (string) old('_verification_booking_id', '') === (string) $booking->id;
                $verificationDefaultAmount = $verificationOldInput
                    ? old('verified_amount')
                    : ($verificationPayment?->claimed_amount !== null ? (int) $verificationPayment->claimed_amount : '');
            @endphp

            @if($verificationPayment)
                <div class="modal fade booking-payment-modal" id="bookingPaymentVerification{{ $booking->id }}" tabindex="-1" aria-labelledby="bookingPaymentVerificationTitle{{ $booking->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admin.court-bookings.confirm', $booking) }}">
                                @csrf
                                <input type="hidden" name="_verification_booking_id" value="{{ $booking->id }}">

                                <div class="modal-header">
                                    <div>
                                        <div class="booking-payment-modal-kicker">VERIFIKASI PEMBAYARAN · #{{ $verificationCode }}</div>
                                        <h2 class="modal-title" id="bookingPaymentVerificationTitle{{ $booking->id }}">Konfirmasi pembayaran</h2>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="booking-payment-context">
                                        <div>
                                            <strong>{{ $booking->customer_name }}</strong>
                                            <span>{{ $booking->team_name ?: 'Nama tim belum dicatat' }}</span>
                                        </div>
                                        <div>
                                            <strong>{{ $booking->court?->name ?? 'Lapangan dihapus' }}</strong>
                                            <span>{{ $booking->booking_date->translatedFormat('l, d F Y') }} · {{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }} WIB</span>
                                        </div>
                                    </div>

                                    <div class="booking-payment-facts" aria-label="Ringkasan tagihan booking">
                                        <div class="booking-payment-fact">
                                            <span>Total tagihan</span>
                                            <strong>Rp {{ number_format((float) $booking->amount, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="booking-payment-fact">
                                            <span>Sudah diterima</span>
                                            <strong>Rp {{ number_format($verificationPaidAmount, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="booking-payment-fact">
                                            <span>Sisa tagihan</span>
                                            <strong>Rp {{ number_format($verificationRemaining, 0, ',', '.') }}</strong>
                                        </div>
                                    </div>

                                    <div class="booking-payment-claim">
                                        <span>Metode: <strong>{{ $verificationPayment->payment_method_label }}</strong></span>
                                        @if($verificationPayment->payment_reference)
                                            <span>Referensi: <strong>{{ $verificationPayment->payment_reference }}</strong></span>
                                        @endif
                                        @if($verificationPayment->claimed_amount !== null)
                                            <span>Nominal klaim pelanggan: <strong>Rp {{ number_format((float) $verificationPayment->claimed_amount, 0, ',', '.') }}</strong></span>
                                        @else
                                            <span>Pelanggan mengirim bukti tanpa mengisi nominal.</span>
                                        @endif
                                    </div>

                                    @if($booking->payment_proof_path)
                                        <button class="booking-payment-proof-link" type="button" aria-haspopup="dialog"
                                            data-booking-proof-modal="bookingPaymentProof{{ $booking->id }}"
                                            data-return-to="bookingPaymentVerification{{ $booking->id }}">
                                            <i class="bi bi-eye" aria-hidden="true"></i>Lihat bukti pembayaran
                                        </button>
                                    @else
                                        <p class="small text-secondary mt-3 mb-0">Bukti pembayaran tidak tersedia. Pastikan pembayaran diterima sebelum booking dikonfirmasi.</p>
                                    @endif

                                    <div class="mt-4">
                                        <label class="form-label fw-medium" for="verifiedAmount{{ $booking->id }}">Nominal yang benar-benar diterima</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input class="form-control" id="verifiedAmount{{ $booking->id }}" name="verified_amount_display" type="text" inputmode="numeric" autocomplete="off" spellcheck="false" value="{{ $verificationDefaultAmount }}" placeholder="Masukkan nominal pada bukti" data-rupiah-input required aria-describedby="verifiedAmountHelp{{ $booking->id }}">
                                            <input type="hidden" name="verified_amount" value="{{ $verificationOldInput ? preg_replace('/\D+/', '', (string) old('verified_amount')) : '' }}" data-rupiah-value>
                                        </div>
                                        <div class="form-text" id="verifiedAmountHelp{{ $booking->id }}">Masukkan nominal sesuai bukti dan tidak melebihi sisa tagihan. Booking akan dikonfirmasi setelah verifikasi.</div>
                                        @if($verificationOldInput && $errors->has('verified_amount'))
                                            <div class="text-danger small mt-1">{{ $errors->first('verified_amount') }}</div>
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <label class="form-label" for="verificationNote{{ $booking->id }}">Catatan verifikasi <span class="text-secondary fw-normal">(wajib bila berbeda dari klaim)</span></label>
                                        <textarea class="form-control" id="verificationNote{{ $booking->id }}" name="verification_note" rows="2" maxlength="500" placeholder="Contoh: nominal pada bukti berbeda dari klaim pelanggan">{{ $verificationOldInput ? old('verification_note') : '' }}</textarea>
                                        @if($verificationOldInput && $errors->has('verification_note'))
                                            <div class="text-danger small mt-1">{{ $errors->first('verification_note') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Periksa lagi nanti</button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check2-circle" aria-hidden="true"></i>Verifikasi &amp; konfirmasi booking
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        @if($booking->status === \App\Models\CourtBooking::STATUS_AWAITING_PAYMENT || ($booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED && !$booking->rental_picked_up_at))
            @php
                $cancellationCode = str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT);
                $cancellationOldInput = (string) old('_cancel_booking_id', '') === (string) $booking->id;
            @endphp
            <div class="modal fade booking-payment-modal booking-cancel-modal" id="bookingCancellation{{ $booking->id }}" tabindex="-1" aria-labelledby="bookingCancellationTitle{{ $booking->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('admin.court-bookings.cancel', $booking) }}">
                            @csrf
                            <input type="hidden" name="_cancel_booking_id" value="{{ $booking->id }}">

                            <div class="modal-header">
                                <div>
                                    <div class="booking-payment-modal-kicker">PEMBATALAN BOOKING · #{{ $cancellationCode }}</div>
                                    <h2 class="modal-title" id="bookingCancellationTitle{{ $booking->id }}">Batalkan booking</h2>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>

                            <div class="modal-body">
                                <div class="booking-payment-context">
                                    <div>
                                        <strong>{{ $booking->customer_name }}</strong>
                                        <span>{{ $booking->team_name ?: 'Nama tim belum dicatat' }}</span>
                                    </div>
                                    <div>
                                        <strong>{{ $booking->court?->name ?? 'Lapangan dihapus' }}</strong>
                                        <span>{{ $booking->booking_date->translatedFormat('l, d F Y') }} · {{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }} WIB</span>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="form-label" for="bookingCancellationReason{{ $booking->id }}">Alasan pembatalan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="bookingCancellationReason{{ $booking->id }}" name="admin_note" rows="3" maxlength="1000" placeholder="Tuliskan alasan pembatalan" required>{{ $cancellationOldInput ? old('admin_note') : '' }}</textarea>
                                    <div class="form-text">Alasan ini akan tersimpan pada catatan booking.</div>
                                    @if($cancellationOldInput && $errors->has('admin_note'))
                                        <div class="text-danger small mt-1">{{ $errors->first('admin_note') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-circle" aria-hidden="true"></i>Konfirmasi pembatalan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    </main>

    @if((string) old('_verification_booking_id', '') !== '' && $errors->hasAny(['verified_amount', 'verification_note', 'booking']))
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const modal = document.getElementById('bookingPaymentVerification{{ old('_verification_booking_id') }}');
                    if (modal && window.bootstrap?.Modal) {
                        window.bootstrap.Modal.getOrCreateInstance(modal).show();
                    }
                });
            </script>
        @endpush
    @endif

    @if((string) old('_record_payment_booking_id', '') !== '' && $errors->hasAny(['amount', 'payment_method', 'payment_reference', 'note']))
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const modal = document.getElementById('bookingPaymentRecord{{ old('_record_payment_booking_id') }}');
                    if (modal && window.bootstrap?.Modal) {
                        window.bootstrap.Modal.getOrCreateInstance(modal).show();
                    }
                });
            </script>
        @endpush
    @endif

    @if((string) old('_cancel_booking_id', '') !== '' && $errors->has('admin_note'))
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const bookingId = @json((string) old('_cancel_booking_id'));
                    const modal = document.getElementById(`bookingCancellation${bookingId}`);
                    if (modal && window.bootstrap?.Modal) {
                        window.bootstrap.Modal.getOrCreateInstance(modal).show();
                    }
                });
            </script>
        @endpush
    @endif

    @push('scripts')
        <script>
            (() => {
                if (!window.bootstrap?.Modal) return;

                document.addEventListener('click', (event) => {
                    const trigger = event.target.closest('[data-booking-proof-modal]');
                    if (!trigger) return;

                    const proofModalElement = document.getElementById(trigger.dataset.bookingProofModal);
                    if (!proofModalElement) return;

                    const verificationModalElement = trigger.dataset.returnTo
                        ? document.getElementById(trigger.dataset.returnTo)
                        : trigger.closest('.modal.show');
                    const showProofModal = () => window.bootstrap.Modal.getOrCreateInstance(proofModalElement).show();
                    proofModalElement.dataset.returnTo = trigger.dataset.returnTo || verificationModalElement?.id || '';
                    if (verificationModalElement?.classList.contains('show')) {
                        verificationModalElement.addEventListener('hidden.bs.modal', showProofModal, { once: true });
                        window.bootstrap.Modal.getOrCreateInstance(verificationModalElement).hide();
                        return;
                    }

                    showProofModal();
                });

                document.querySelectorAll('.booking-proof-modal').forEach((proofModalElement) => {
                    proofModalElement.addEventListener('hidden.bs.modal', () => {
                        const verificationModalId = proofModalElement.dataset.returnTo;
                        proofModalElement.dataset.returnTo = '';

                        if (verificationModalId) {
                            const verificationModalElement = document.getElementById(verificationModalId);
                            if (verificationModalElement) {
                                window.bootstrap.Modal.getOrCreateInstance(verificationModalElement).show();
                            }
                        }
                    });
                });
            })();
        </script>
    @endpush
@endsection
