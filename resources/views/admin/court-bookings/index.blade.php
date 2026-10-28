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
            font-size: 16px;
            line-height: 1.5;
        }

        .booking-page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px 24px;
            margin-bottom: 24px;
        }

        .booking-page-heading h1 {
            margin: 0 0 8px;
            color: var(--booking-ink);
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
            letter-spacing: 0;
            text-wrap: balance;
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

        .booking-admin-page>section,
        .booking-admin-page>form.card {
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

        .booking-status-item+.booking-status-item {
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

        .booking-report-fact>span {
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
            border-bottom: 1px solid var(--booking-line-soft);
            background: transparent;
            color: var(--booking-muted);
            font-size: 13px;
            font-weight: 500;
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

        .booking-table th:first-child,
        .booking-table td:first-child {
            position: sticky;
            left: 0;
            background: var(--booking-canvas);
        }

        .booking-table th:first-child {
            z-index: 2;
        }

        .booking-table td:first-child {
            z-index: 1;
        }

        .booking-table tbody tr:hover td {
            background: var(--booking-cloud);
        }

        .booking-table tbody tr:hover td:first-child {
            background: var(--booking-cloud);
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
            border: 2px solid var(--booking-ink);
            box-shadow: none;
            outline: none;
        }

        .booking-admin-page .form-label {
            color: var(--booking-charcoal);
            font-size: 14px;
            font-weight: 500;
        }

        .booking-admin-page .btn:focus-visible,
        .booking-admin-page summary:focus-visible,
        .booking-admin-page a:focus-visible {
            outline: 3px solid rgba(17, 17, 17, .22);
            outline-offset: 2px;
        }

        .booking-admin-page :is(input:not([type="checkbox"]):not([type="radio"]), select, textarea):focus-visible {
            border: 2px solid var(--booking-ink);
            box-shadow: none;
            outline: none;
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
            width: 100%;
            min-width: 1120px;
            table-layout: fixed;
        }

        .booking-table col:nth-child(1) {
            width: 18%;
        }

        .booking-table col:nth-child(2) {
            width: 19%;
        }

        .booking-table col:nth-child(3) {
            width: 24%;
        }

        .booking-table col:nth-child(4) {
            width: 15%;
        }

        .booking-table td {
            word-break: normal;
            overflow-wrap: break-word;
            vertical-align: top;
        }

        .booking-table .booking-actions-cell {
            width: 24%;
            min-width: 250px;
            text-align: left;
            vertical-align: top;
        }

        .booking-table .booking-actions-heading {
            width: 24%;
            text-align: left;
        }

        .booking-cell-stack {
            display: grid;
            gap: 4px;
            min-width: 0;
        }

        .booking-cell-primary {
            color: var(--booking-ink);
            font-weight: 500;
            line-height: 1.35;
            overflow-wrap: normal;
        }

        .booking-cell-secondary {
            color: var(--booking-muted);
            font-size: 13px;
            line-height: 1.4;
        }

        .booking-schedule-time {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 4px 8px;
            color: var(--booking-ink);
            font-size: 14px;
            font-variant-numeric: tabular-nums;
            line-height: 1.4;
        }

        .booking-schedule-time strong,
        .booking-customer-phone {
            color: var(--booking-ink);
            font-weight: 500;
            white-space: nowrap;
        }

        .booking-schedule-time span {
            color: var(--booking-muted);
            font-size: 12px;
        }

        .booking-booking-code {
            color: var(--booking-muted);
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 12px;
            font-variant-numeric: tabular-nums;
        }

        .booking-customer-phone {
            width: fit-content;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .booking-customer-email {
            overflow-wrap: anywhere;
        }

        .booking-customer-team {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            color: var(--booking-muted);
            font-size: 13px;
        }

        .booking-customer-team strong {
            color: var(--booking-charcoal);
            font-weight: 500;
        }

        .booking-payment-summary {
            display: grid;
            gap: 8px;
            min-width: 0;
        }

        .booking-payment-total,
        .booking-payment-balance>div {
            display: grid;
            gap: 2px;
            min-width: 0;
        }

        .booking-payment-total span,
        .booking-payment-balance span {
            color: var(--booking-muted);
            font-size: 12px;
            line-height: 1.35;
        }

        .booking-payment-total strong {
            color: var(--booking-ink);
            font-size: 16px;
            font-weight: 500;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .booking-payment-balance {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            padding-top: 8px;
            border-top: 1px solid var(--booking-line-soft);
        }

        .booking-payment-balance strong {
            color: var(--booking-charcoal);
            font-size: 13px;
            font-weight: 500;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .booking-payment-method {
            color: var(--booking-muted);
            font-size: 12px;
        }

        .booking-row-details {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid var(--booking-line-soft);
        }

        .booking-row-details>summary {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 4px 8px;
            color: var(--booking-charcoal);
            font-size: 13px;
            line-height: 1.4;
            cursor: pointer;
            list-style: none;
        }

        .booking-row-details>summary::-webkit-details-marker {
            display: none;
        }

        .booking-row-details>summary::after {
            width: 7px;
            height: 7px;
            margin-left: auto;
            border-right: 1.5px solid currentColor;
            border-bottom: 1.5px solid currentColor;
            content: '';
            transform: rotate(45deg) translateY(-2px);
            transition: transform 150ms ease-out;
        }

        .booking-row-details[open]>summary::after {
            transform: rotate(225deg) translate(-1px, -1px);
        }

        .booking-row-details-content {
            display: grid;
            gap: 8px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--booking-line-soft);
        }

        .booking-row-details-content>* {
            margin-top: 0 !important;
        }

        .booking-row-details-attention {
            color: #8a4b00;
            font-size: 12px;
            font-weight: 500;
        }

        .booking-actions-cell>* {
            width: 100%;
            margin-top: 0 !important;
            margin-bottom: 8px !important;
        }

        .booking-actions-cell .btn {
            min-height: 44px;
            justify-content: center;
            text-align: center;
        }

        .booking-actions-cell details>summary.btn {
            display: flex;
            width: 100%;
            list-style: none;
            cursor: pointer;
        }

        .booking-actions-cell details>summary.btn::-webkit-details-marker {
            display: none;
        }

        .booking-actions-cell details>form {
            padding: 12px;
            border: 1px solid var(--booking-line-soft);
            border-radius: 8px !important;
            background: var(--booking-cloud);
            text-align: left;
        }

        .booking-actions-cell>.d-flex {
            display: flex !important;
            flex-direction: column;
            align-items: stretch;
            justify-content: flex-start !important;
            gap: 8px !important;
        }

        .booking-actions-cell>.d-flex>details,
        .booking-actions-cell>.d-flex>button,
        .booking-actions-cell>.d-flex>span {
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

        .booking-admin-page details>summary.btn {
            list-style: none;
            cursor: pointer;
        }

        .booking-admin-page details>summary.btn::-webkit-details-marker {
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

        .booking-discount-modal .modal-content>form {
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
        }

        .booking-discount-modal .modal-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }

        .booking-discount-modal .modal-footer {
            flex: 0 0 auto;
            gap: 10px;
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

        .booking-payment-context>div {
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

        .booking-discount-facts {
            margin-top: 18px;
        }

        .booking-payment-fact {
            display: grid;
            gap: 5px;
            min-width: 0;
            padding: 14px 16px;
        }

        .booking-payment-fact+.booking-payment-fact {
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
            .booking-report-summary {
                grid-template-columns: 1fr 2fr;
            }

            .booking-report-summary>a {
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

            .booking-status-strip {
                grid-template-columns: 1fr;
                margin-bottom: 24px;
            }

            .booking-status-item+.booking-status-item {
                border-top: 1px solid var(--booking-line-soft);
                border-left: 0;
            }

            .booking-report-summary {
                grid-template-columns: 1fr;
                align-items: start;
            }

            .booking-report-summary>a {
                grid-column: auto;
            }

            .booking-filter-grid {
                grid-template-columns: 1fr 1fr;
            }

            .booking-admin-page>section,
            .booking-admin-page>form.card {
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

            .booking-filter-grid {
                grid-template-columns: 1fr;
            }

            .booking-payment-modal .modal-dialog {
                margin: 8px;
            }

            .booking-discount-facts {
                grid-template-columns: 1fr;
            }

            .booking-discount-facts .booking-payment-fact+.booking-payment-fact {
                border-top: 1px solid var(--booking-line-soft);
                border-left: 0;
            }

            .booking-payment-modal .modal-header,
            .booking-payment-modal .modal-body,
            .booking-payment-modal .modal-footer {
                padding: 18px 16px;
            }

            .booking-discount-modal .modal-header,
            .booking-discount-modal .modal-body {
                padding: 16px;
            }

            .booking-discount-modal .modal-footer {
                padding: 12px 16px;
            }

            .booking-discount-modal .modal-footer .btn {
                flex: 1 1 0;
                min-width: 0;
                white-space: nowrap;
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

            .booking-payment-fact+.booking-payment-fact {
                border-top: 1px solid var(--booking-line-soft);
                border-left: 0;
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

        @media (max-width: 359.98px) {
            .booking-discount-modal .modal-footer .btn {
                padding-inline: 8px;
                font-size: 13px;
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
                <a href="{{ route('booking.index') }}" target="_blank" rel="noopener noreferrer"
                    class="btn btn-outline-primary">
                    <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>Halaman publik
                </a>
                <a href="{{ route('admin.court-bookings.revenue') }}" class="btn btn-outline-primary">
                    <i class="bi bi-cash-stack" aria-hidden="true"></i>Laporan pemasukan
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
                    <p class="text-secondary small mb-0">Rekap booking dan pembayaran; parkir serta toko dicatat terpisah
                        per tanggal.</p>
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
                        <span class="small text-secondary">Pemasukan booking diakui</span>
                        <strong>Rp {{ number_format($todayReceivedPayments, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <a href="{{ route('admin.court-bookings.reports.daily', ['date' => $todayReportDate]) }}"
                    class="btn btn-outline-primary text-nowrap">
                    <i class="bi bi-clipboard-data me-1" aria-hidden="true"></i>Buka laporan
                </a>
            </div>
        </section>

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

                    document.querySelectorAll('[data-fund-settlement-form]').forEach((form) => {
                        const type = form.querySelector('[data-fund-settlement-type]');
                        const paymentMethod = form.querySelector('[name="payment_method"]');
                        const paymentLabel = form.querySelector('[data-fund-method-label]');
                        const syncPaymentMethod = () => {
                            const isRefund = type?.value === 'refund';
                            if (paymentMethod) paymentMethod.required = true;
                            if (paymentLabel) {
                                paymentLabel.textContent = isRefund
                                    ? 'Metode pengembalian'
                                    : 'Metode pembayaran asal dana';
                            }
                        };
                        type?.addEventListener('change', syncPaymentMethod);
                        syncPaymentMethod();
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
                    <input class="form-control" type="date" id="filterDate" name="date"
                        value="{{ $filters['date'] ?? '' }}">
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
                <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"
                        aria-hidden="true"></i>Terapkan</button>
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
            <div class="table-responsive booking-table-wrap" role="region"
                aria-label="Daftar booking, gulir mendatar untuk melihat semua kolom" tabindex="0">
                <table class="table align-middle booking-table">
                    <caption class="visually-hidden">Daftar booking lapangan beserta jadwal, pemesan, pembayaran, status,
                        dan tindakan.</caption>
                    <colgroup>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                    </colgroup>
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
                                $hasRentalItems = $booking->rentalLines->isNotEmpty() || $confirmedRentalAdditions->contains(fn($addition) => $addition->lines->isNotEmpty());
                                $hasUnresolvedAdditions = $booking->rentalAdditions->contains(fn($addition) => in_array($addition->status, [
                                    \App\Models\CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT,
                                    \App\Models\CourtBookingRentalAddition::STATUS_PENDING_REVIEW,
                                ], true));
                                $pendingReviewAdditionCount = $booking->rentalAdditions
                                    ->where('status', \App\Models\CourtBookingRentalAddition::STATUS_PENDING_REVIEW)
                                    ->count();
                                $bookingStart = \Carbon\Carbon::parse($booking->booking_date->toDateString() . ' ' . $booking->time_start, \App\Models\CourtBooking::TIMEZONE);
                                $canCorrectSchedule = $booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED;
                                $grossPaidAmount = (float) $booking->payments->where('status', \App\Models\CourtBookingPayment::STATUS_CONFIRMED)->sum(fn($payment) => $payment->recognized_amount);
                                $bookingRefundAmount = (float) $booking->fundSettlements
                                    ->where('type', \App\Models\CourtBookingFundSettlement::TYPE_REFUND)
                                    ->whereNull('voided_at')
                                    ->sum('amount');
                                $paidAmount = $booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED
                                    ? max(0, $grossPaidAmount - $bookingRefundAmount)
                                    : $grossPaidAmount;
                                $paidRentalAdditionAmount = $booking->status === \App\Models\CourtBooking::STATUS_CANCELLED
                                    ? (float) $booking->rentalAdditions->whereNotNull('confirmed_at')->sum('amount')
                                    : 0;
                                $bookingFundsReceived = $grossPaidAmount + $paidRentalAdditionAmount;
                                $activeFundSettlements = $booking->fundSettlements->whereNull('voided_at');
                                $settledFundAmount = (float) $activeFundSettlements->sum('amount');
                                $unresolvedCancelledFunds = $booking->status === \App\Models\CourtBooking::STATUS_CANCELLED
                                    ? max(0, round($bookingFundsReceived - $settledFundAmount, 2))
                                    : 0;
                                $pendingPayment = $booking->payments->firstWhere('status', \App\Models\CourtBookingPayment::STATUS_PENDING_REVIEW);
                                $waPhone = preg_replace('/\D+/', '', (string) $booking->customer_phone);
                                $waPhone = $waPhone === ''
                                    ? null
                                    : (str_starts_with($waPhone, '0') ? '62' . substr($waPhone, 1) : (str_starts_with($waPhone, '8') ? '62' . $waPhone : $waPhone));
                                $remainingAmount = in_array($booking->status, ['confirmed', 'pending_review', 'awaiting_payment'], true)
                                    ? max(0, (float) $booking->amount - $paidAmount)
                                    : null;
                                $canAddRentalItems = $booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED
                                    && !$booking->rental_picked_up_at
                                    && !$booking->rental_returned_at
                                    && $bookingStart->isAfter(now(\App\Models\CourtBooking::TIMEZONE));
                            @endphp
                            <tr>
                                <td class="booking-schedule-cell">
                                    <div class="booking-cell-stack">
                                        <div class="booking-cell-primary">{{ $booking->court?->name ?? 'Lapangan dihapus' }}
                                        </div>
                                        <div class="booking-cell-secondary">
                                            {{ $booking->booking_date->translatedFormat('D, d M Y') }}
                                        </div>
                                        <div class="booking-schedule-time">
                                            <strong>{{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }}</strong>
                                            <span>{{ $booking->duration_label }}</span>
                                        </div>
                                        <div class="booking-booking-code">Booking
                                            #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        @if(in_array($booking->source, ['walk_in', 'member'], true))
                                            <div class="booking-cell-secondary">
                                                <span
                                                    class="badge text-bg-light border">{{ $booking->source === 'member' ? 'Member' : 'Walk-in' }}</span>
                                                @if($booking->createdBy)<span>{{ $booking->createdBy->name }}</span>@endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="booking-customer-cell">
                                    <div class="booking-cell-stack">
                                        <div class="booking-cell-primary">{{ $booking->customer_name }}</div>
                                        @if($booking->customer_phone && $waPhone)
                                            <a href="https://wa.me/{{ $waPhone }}" target="_blank" rel="noopener"
                                                class="booking-customer-phone">{{ $booking->customer_phone }}</a>
                                        @elseif($booking->source === 'member')
                                            <div class="booking-cell-secondary">Terdaftar dari jadwal member</div>
                                        @endif
                                        @if($booking->customer_email)
                                            <div class="booking-cell-secondary booking-customer-email">
                                                {{ $booking->customer_email }}
                                            </div>
                                        @endif
                                        <div class="booking-customer-team">
                                            <span>Tim</span>
                                            <strong>{{ $booking->team_name ?: 'Belum dicatat' }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td class="booking-payment-cell">
                                    <div class="booking-payment-summary">
                                        <div class="booking-payment-total">
                                            <span>Total tagihan</span>
                                            <strong>Rp {{ number_format((float) $booking->amount, 0, ',', '.') }}</strong>
                                        </div>
                                        @if((float) ($booking->discount_amount ?? 0) > 0)
                                            <div class="small text-secondary mt-1">Harga awal Rp
                                                {{ number_format((float) ($booking->subtotal_amount ?? ($booking->amount + $booking->discount_amount)), 0, ',', '.') }}
                                                · Diskon Rp
                                                {{ number_format((float) $booking->discount_amount, 0, ',', '.') }}{{ $booking->discount_reason ? ' · ' . $booking->discount_reason : '' }}
                                            </div>
                                        @endif
                                        <div class="booking-payment-balance">
                                            <div>
                                                <span>Diterima</span>
                                                <strong>Rp {{ number_format($paidAmount, 0, ',', '.') }}</strong>
                                            </div>
                                            @if($remainingAmount !== null)
                                                <div>
                                                    <span>Sisa tagihan</span>
                                                    <strong>Rp {{ number_format($remainingAmount, 0, ',', '.') }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="booking-payment-method">{{ $booking->payment_method_label }}</div>
                                        @if($booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED && $bookingRefundAmount > 0)
                                            <div class="small text-secondary mt-1">Refund tercatat: Rp
                                                {{ number_format($bookingRefundAmount, 0, ',', '.') }}
                                            </div>
                                        @endif
                                        @if($booking->status === \App\Models\CourtBooking::STATUS_CANCELLED && $bookingFundsReceived > 0)
                                            <div
                                                class="small mt-2 {{ $unresolvedCancelledFunds > 0 ? 'text-warning-emphasis' : 'text-success' }}">
                                                @if($unresolvedCancelledFunds > 0)
                                                    Dana batal belum diputuskan: Rp
                                                    {{ number_format($unresolvedCancelledFunds, 0, ',', '.') }}
                                                @else
                                                    Penyelesaian dana tercatat: Rp {{ number_format($settledFundAmount, 0, ',', '.') }}
                                                @endif
                                            </div>
                                            @if($paidRentalAdditionAmount > 0)
                                                <div class="small text-secondary">Termasuk tambahan perlengkapan terbayar Rp
                                                    {{ number_format($paidRentalAdditionAmount, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    @if($booking->payments->isNotEmpty() || $booking->paymentAudits->isNotEmpty() || $booking->scheduleAudits->isNotEmpty() || $booking->discountAudits->isNotEmpty() || $booking->backdateAudits->isNotEmpty() || $booking->fundSettlements->isNotEmpty() || $booking->rentalLines->isNotEmpty() || $booking->rentalAdditions->isNotEmpty() || $booking->payment_reference || $booking->payment_proof_path || $booking->cancelled_at)
                                        <details class="booking-row-details">
                                            <summary>
                                                <span>Rincian transaksi</span>
                                                @if($pendingReviewAdditionCount > 0)
                                                    <span class="booking-row-details-attention">{{ $pendingReviewAdditionCount }}
                                                        tambahan perlu diverifikasi</span>
                                                @endif
                                            </summary>
                                            <div class="booking-row-details-content">
                                                @if($booking->status === \App\Models\CourtBooking::STATUS_CANCELLED)
                                                    <div class="small mb-2">
                                                        <strong>Pembatalan:</strong>
                                                        {{ $booking->cancelled_at?->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M Y, H:i') }}
                                                        WIB
                                                        · dicatat oleh
                                                        {{ $booking->cancelled_by_name_snapshot ?? $booking->cancelledBy?->name ?? 'Admin tidak diketahui' }}
                                                        @if($booking->admin_note)<br><span class="text-secondary">Alasan:
                                                        {{ $booking->admin_note }}</span>@endif
                                                    </div>
                                                @endif
                                                @foreach($booking->payments as $payment)
                                                    <div class="small text-secondary">{{ $payment->stage_label }} ·
                                                        {{ $payment->payment_method_label }} · {{ $payment->status_label }}
                                                    </div>
                                                    <div
                                                        class="small {{ $payment->status === \App\Models\CourtBookingPayment::STATUS_CONFIRMED ? 'text-success' : 'text-secondary' }}">
                                                        @if($payment->status === \App\Models\CourtBookingPayment::STATUS_CONFIRMED)
                                                            Nominal terverifikasi: Rp
                                                            {{ number_format($payment->recognized_amount, 0, ',', '.') }}
                                                        @elseif($payment->claimed_amount !== null)
                                                            Klaim pelanggan: Rp
                                                            {{ number_format((float) $payment->claimed_amount, 0, ',', '.') }}
                                                        @elseif($payment->status === \App\Models\CourtBookingPayment::STATUS_REJECTED)
                                                            Nominal tidak terverifikasi
                                                        @else
                                                            Nominal menunggu pemeriksaan admin
                                                        @endif
                                                    </div>
                                                    @if($payment->status === \App\Models\CourtBookingPayment::STATUS_CONFIRMED && $payment->claimed_amount !== null && abs((float) $payment->claimed_amount - (float) $payment->verified_amount) >= 0.01)
                                                        <div class="small text-secondary">Klaim awal: Rp
                                                            {{ number_format((float) $payment->claimed_amount, 0, ',', '.') }} · Catatan:
                                                            {{ $payment->note ?: 'Tidak ada catatan' }}
                                                        </div>
                                                    @endif
                                                    @if($payment->payment_reference)
                                                    <div class="small text-secondary">Ref: {{ $payment->payment_reference }}</div>@endif
                                                    @if(in_array($booking->status, [\App\Models\CourtBooking::STATUS_CONFIRMED, \App\Models\CourtBooking::STATUS_CANCELLED], true) && $payment->status === \App\Models\CourtBookingPayment::STATUS_CONFIRMED)
                                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#bookingPaymentCorrection{{ $booking->id }}{{ $payment->id }}">
                                                                <i class="bi bi-pencil" aria-hidden="true"></i> Koreksi pembayaran
                                                            </button>
                                                            @if($booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED)
                                                                <button class="btn btn-sm btn-outline-danger" type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#bookingPaymentUnverify{{ $booking->id }}{{ $payment->id }}">
                                                                    <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Batalkan
                                                                    verifikasi
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                                @if($booking->fundSettlements->isNotEmpty())
                                                    <div class="border-top mt-3 pt-2">
                                                        <div class="small fw-semibold mb-2">Riwayat penyelesaian dana booking</div>
                                                        @foreach($booking->fundSettlements as $settlement)
                                                            <div
                                                                class="small {{ $settlement->voided_at ? 'text-secondary text-decoration-line-through' : '' }}">
                                                                {{ $settlement->type_label }} · Rp
                                                                {{ number_format((float) $settlement->amount, 0, ',', '.') }}
                                                                @if($settlement->payment_method_label) ·
                                                                {{ $settlement->payment_method_label }}@endif
                                                                ·
                                                                {{ $settlement->recorded_at?->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M Y, H:i') }}
                                                                WIB
                                                            </div>
                                                            <div class="small text-secondary">
                                                                Dicatat oleh
                                                                {{ $settlement->recorded_by_name_snapshot ?? $settlement->recordedBy?->name ?? 'Admin tidak diketahui' }}
                                                                · {{ $settlement->reason }}
                                                                @if($settlement->reference) · Ref: {{ $settlement->reference }}@endif
                                                            </div>
                                                            @if($settlement->voided_at)
                                                                <div class="small text-secondary mb-2">Catatan dibatalkan
                                                                    {{ $settlement->voided_at->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M Y, H:i') }}
                                                                    WIB oleh
                                                                    {{ $settlement->voided_by_name_snapshot ?? $settlement->voidedBy?->name ?? 'Admin tidak diketahui' }}.
                                                                    Alasan: {{ $settlement->void_reason }}
                                                                </div>
                                                            @else
                                                                <button class="btn btn-sm btn-link text-danger px-0 mb-2" type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#voidFundSettlement{{ $settlement->id }}">
                                                                    Koreksi catatan
                                                                </button>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @if($booking->paymentAudits->isNotEmpty())
                                                    <div class="border-top mt-3 pt-2">
                                                        <div class="small fw-semibold mb-2">Riwayat koreksi pembayaran</div>
                                                        @foreach($booking->paymentAudits as $paymentAudit)
                                                            @php
                                                                $auditBeforeAmount = data_get($paymentAudit->before_data, 'verified_amount') ?? data_get($paymentAudit->before_data, 'amount');
                                                                $auditAfterAmount = data_get($paymentAudit->after_data, 'verified_amount') ?? data_get($paymentAudit->after_data, 'amount');
                                                            @endphp
                                                            <div class="small">
                                                                {{ $paymentAudit->action === 'payment_unverified' ? 'Verifikasi dibatalkan' : 'Pembayaran dikoreksi' }}
                                                                ·
                                                                {{ $paymentAudit->created_at?->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M Y, H:i') }}
                                                                WIB
                                                                ·
                                                                {{ $paymentAudit->admin_name_snapshot ?? $paymentAudit->admin?->name ?? 'Admin tidak diketahui' }}
                                                            </div>
                                                            @if($auditBeforeAmount !== null || $auditAfterAmount !== null)
                                                                <div class="small text-secondary">Sebelum Rp
                                                                    {{ number_format((float) $auditBeforeAmount, 0, ',', '.') }} · Sesudah Rp
                                                                    {{ number_format((float) $auditAfterAmount, 0, ',', '.') }}
                                                                </div>
                                                            @endif
                                                            <div class="small text-secondary mb-2">Alasan: {{ $paymentAudit->reason }}</div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @if($booking->scheduleAudits->isNotEmpty())
                                                    <div class="border-top mt-3 pt-2">
                                                        <div class="small fw-semibold mb-2">Riwayat koreksi jadwal</div>
                                                        @foreach($booking->scheduleAudits as $scheduleAudit)
                                                            @php
                                                                $scheduleBefore = $scheduleAudit->before_data ?? [];
                                                                $scheduleAfter = $scheduleAudit->after_data ?? [];
                                                            @endphp
                                                            <div class="small">
                                                                {{ $scheduleAudit->created_at?->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M Y, H:i') }}
                                                                WIB
                                                                ·
                                                                {{ $scheduleAudit->admin_name_snapshot ?? $scheduleAudit->admin?->name ?? 'Admin tidak diketahui' }}
                                                            </div>
                                                            <div class="small text-secondary">
                                                                Sebelum: {{ data_get($scheduleBefore, 'booking_date') }}
                                                                {{ data_get($scheduleBefore, 'time_start') }}–{{ data_get($scheduleBefore, 'time_end') }}
                                                                ({{ (int) data_get($scheduleBefore, 'duration_minutes') }} menit), total Rp
                                                                {{ number_format((float) data_get($scheduleBefore, 'amount'), 0, ',', '.') }}.
                                                            </div>
                                                            <div class="small text-secondary">
                                                                Sesudah: {{ data_get($scheduleAfter, 'booking_date') }}
                                                                {{ data_get($scheduleAfter, 'time_start') }}–{{ data_get($scheduleAfter, 'time_end') }}
                                                                ({{ (int) data_get($scheduleAfter, 'duration_minutes') }} menit), total Rp
                                                                {{ number_format((float) data_get($scheduleAfter, 'amount'), 0, ',', '.') }}.
                                                            </div>
                                                            <div class="small text-secondary mb-2">Alasan: {{ $scheduleAudit->reason }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @if($booking->discountAudits->isNotEmpty())
                                                    <div class="border-top mt-3 pt-2">
                                                        <div class="small fw-semibold mb-2">Riwayat koreksi diskon</div>
                                                        @foreach($booking->discountAudits as $discountAudit)
                                                            @php
                                                                $discountBefore = $discountAudit->before_data ?? [];
                                                                $discountAfter = $discountAudit->after_data ?? [];
                                                                $discountTypeLabel = fn($type) => match ($type) {
                                                                    'fixed' => 'Potongan nominal',
                                                                    'percentage' => 'Potongan persentase',
                                                                    default => 'Tanpa diskon',
                                                                };
                                                            @endphp
                                                            <div class="small">
                                                                {{ $discountAudit->created_at?->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M Y, H:i') }}
                                                                WIB
                                                                ·
                                                                {{ $discountAudit->admin_name_snapshot ?? $discountAudit->admin?->name ?? 'Admin tidak diketahui' }}
                                                            </div>
                                                            <div class="small text-secondary">
                                                                Sebelum:
                                                                {{ $discountTypeLabel(data_get($discountBefore, 'discount_type')) }} ·
                                                                diskon Rp
                                                                {{ number_format((float) data_get($discountBefore, 'discount_amount', 0), 0, ',', '.') }}
                                                                · tagihan Rp
                                                                {{ number_format((float) data_get($discountBefore, 'amount', 0), 0, ',', '.') }}.
                                                            </div>
                                                            <div class="small text-secondary">
                                                                Sesudah: {{ $discountTypeLabel(data_get($discountAfter, 'discount_type')) }}
                                                                · diskon Rp
                                                                {{ number_format((float) data_get($discountAfter, 'discount_amount', 0), 0, ',', '.') }}
                                                                · tagihan Rp
                                                                {{ number_format((float) data_get($discountAfter, 'amount', 0), 0, ',', '.') }}.
                                                            </div>
                                                            <div class="small text-secondary mb-2">Alasan koreksi:
                                                                {{ $discountAudit->reason }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @if($booking->backdateAudits->isNotEmpty())
                                                    <div class="border-top mt-3 pt-2">
                                                        <div class="small fw-semibold mb-2">Riwayat pencatatan booking tanggal lampau
                                                        </div>
                                                        @foreach($booking->backdateAudits as $backdateAudit)
                                                            <div class="small">
                                                                Dicatat
                                                                {{ $backdateAudit->created_at?->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M Y, H:i') }}
                                                                WIB
                                                                ·
                                                                {{ $backdateAudit->admin_name_snapshot ?? $backdateAudit->admin?->name ?? 'Admin tidak diketahui' }}
                                                            </div>
                                                            <div class="small text-secondary mb-2">Alasan: {{ $backdateAudit->reason }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @foreach($booking->rentalLines as $line)
                                                    <div class="small text-secondary">{{ $line->item_name_snapshot }} ×
                                                        {{ $line->quantity }} ·
                                                        Rp {{ number_format((float) $line->line_total, 0, ',', '.') }}
                                                    </div>
                                                @endforeach
                                                @foreach($booking->rentalAdditions as $addition)
                                                    <div class="border-top mt-2 pt-2">
                                                        <div class="small fw-semibold">Tambahan perlengkapan · Rp
                                                            {{ number_format((float) $addition->amount, 0, ',', '.') }}
                                                        </div>
                                                        @foreach($addition->lines as $line)
                                                            <div class="small text-secondary">{{ $line->item_name_snapshot }} ×
                                                                {{ $line->quantity }}
                                                            </div>
                                                        @endforeach
                                                        <span
                                                            class="badge {{ $addition->status === 'confirmed' ? 'text-bg-success' : (in_array($addition->status, ['pending_review', 'awaiting_payment'], true) ? 'text-bg-warning' : 'text-bg-secondary') }}">{{ $addition->status_label }}</span>
                                                        @if($addition->confirmed_at)
                                                            <div class="small text-success">Pembayaran tambahan pernah dikonfirmasi
                                                                {{ $addition->confirmed_at->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M Y, H:i') }}
                                                                WIB oleh {{ $addition->verifiedBy?->name ?? 'Admin dihapus' }}.
                                                            </div>
                                                        @endif
                                                        @if($addition->payment_reference)
                                                        <div class="small">Ref tambahan: {{ $addition->payment_reference }}</div>@endif
                                                        @if($addition->payment_proof_path)
                                                            <button class="btn btn-sm btn-outline-primary mt-1" type="button"
                                                                aria-haspopup="dialog"
                                                                data-booking-proof-modal="bookingRentalAdditionProof{{ $booking->id }}{{ $addition->id }}">Pratinjau
                                                                bukti tambahan</button>
                                                            <a class="btn btn-sm btn-link mt-1"
                                                                href="{{ route('admin.court-bookings.rental-additions.proof.download', [$booking, $addition]) }}">Unduh</a>
                                                        @endif
                                                        @if($addition->status === \App\Models\CourtBookingRentalAddition::STATUS_PENDING_REVIEW)
                                                            <div class="d-flex flex-wrap gap-1 mt-2">
                                                                <form method="POST"
                                                                    action="{{ route('admin.court-bookings.rental-additions.confirm', [$booking, $addition]) }}"
                                                                    onsubmit="return confirm('Konfirmasi pembayaran tambahan ini?')">
                                                                    @csrf<button class="btn btn-sm btn-success" type="submit">Konfirmasi
                                                                        tambahan</button>
                                                                </form>
                                                                <details>
                                                                    <summary class="btn btn-sm btn-outline-danger">Tolak</summary>
                                                                    <form class="mt-2" method="POST"
                                                                        action="{{ route('admin.court-bookings.rental-additions.reject', [$booking, $addition]) }}">
                                                                        @csrf<input class="form-control form-control-sm mb-2"
                                                                            name="admin_note" maxlength="1000"
                                                                            placeholder="Alasan (opsional)">
                                                                        <button class="btn btn-sm btn-danger" type="submit">Tolak bukti
                                                                            tambahan</button>
                                                                    </form>
                                                                </details>
                                                                <form method="POST"
                                                                    action="{{ route('admin.court-bookings.rental-additions.cancel', [$booking, $addition]) }}"
                                                                    onsubmit="return confirm('Batalkan tambahan ini dan lepaskan stoknya?')">
                                                                    @csrf<button class="btn btn-sm btn-link text-danger"
                                                                        type="submit">Batalkan
                                                                        tambahan</button>
                                                                </form>
                                                            </div>
                                                        @elseif($addition->status === \App\Models\CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT && $addition->expires_at)
                                                            <div class="small text-secondary">Ditahan sampai
                                                                {{ $addition->expires_at->timezone(\App\Models\CourtBooking::TIMEZONE)->format('H:i') }}
                                                                WIB
                                                            </div>
                                                        @endif
                                                        @if($addition->status === \App\Models\CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT)
                                                            <form class="mt-1" method="POST"
                                                                action="{{ route('admin.court-bookings.rental-additions.cancel', [$booking, $addition]) }}"
                                                                onsubmit="return confirm('Batalkan tambahan ini dan lepaskan stoknya?')">
                                                                @csrf<button class="btn btn-sm btn-link text-danger p-0"
                                                                    type="submit">Batalkan
                                                                    tambahan</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @endforeach
                                                @if($booking->payment_reference)
                                                <div class="small">Ref: {{ $booking->payment_reference }}</div>@endif
                                                @if($booking->payment_proof_path)
                                                    <button class="btn btn-sm btn-outline-primary mt-1" type="button"
                                                        aria-haspopup="dialog"
                                                        data-booking-proof-modal="bookingPaymentProof{{ $booking->id }}">
                                                        <i class="bi bi-eye me-1" aria-hidden="true"></i>Pratinjau bukti
                                                    </button>
                                                @endif
                                            </div>
                                        </details>
                                    @endif
                                </td>
                                <td class="booking-status-cell">
                                    <div class="booking-cell-stack">
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
                                            <div class="small text-secondary mt-1">Dibatalkan oleh {{ $booking->cancelledBy->name }}
                                            </div>
                                        @elseif($booking->verifiedBy)
                                            <div class="small text-secondary mt-1">Diperiksa oleh {{ $booking->verifiedBy->name }}
                                            </div>
                                        @endif
                                        @if($booking->status === 'confirmed' && $hasRentalItems)
                                            @if($booking->rental_returned_at)
                                                <div class="small text-success mt-2">Perlengkapan kembali
                                                    {{ $booking->rental_returned_at->timezone(\App\Models\CourtBooking::TIMEZONE)->format('H:i') }}
                                                    WIB
                                                </div>
                                            @elseif($booking->rental_picked_up_at)
                                                <div class="small text-warning-emphasis mt-2">Perlengkapan masih disewa</div>
                                                @if(\Carbon\Carbon::parse($booking->booking_date->toDateString() . ' ' . $booking->time_end, \App\Models\CourtBooking::TIMEZONE)->lessThan(now()->timezone(\App\Models\CourtBooking::TIMEZONE)))
                                                    <span class="badge text-bg-warning mt-1">Lewat perkiraan kembali</span>
                                                @endif
                                            @else
                                                <div class="small text-secondary mt-2">Perlengkapan belum diambil</div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="booking-actions-cell">
                                    @if($canCorrectSchedule && in_array($booking->source, ['walk_in', 'member'], true))
                                        <button class="btn btn-sm btn-outline-secondary mb-2" type="button" data-bs-toggle="modal"
                                            data-bs-target="#bookingDiscountCorrection{{ $booking->id }}" aria-haspopup="dialog">
                                            <i class="bi bi-tag me-1" aria-hidden="true"></i>Ubah diskon
                                        </button>
                                    @endif
                                    @if($canCorrectSchedule)
                                        <button class="btn btn-sm btn-outline-secondary mb-2" type="button" data-bs-toggle="modal"
                                            data-bs-target="#bookingScheduleCorrection{{ $booking->id }}" aria-haspopup="dialog">
                                            <i class="bi bi-clock-history me-1" aria-hidden="true"></i>Koreksi tanggal/jam
                                        </button>
                                    @endif
                                    @if($remainingAmount > 0 && $booking->status === 'confirmed')
                                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal"
                                            data-bs-target="#bookingPaymentRecord{{ $booking->id }}" aria-haspopup="dialog">
                                            <i class="bi bi-wallet2" aria-hidden="true"></i>Catat pembayaran / pelunasan
                                        </button>
                                    @endif
                                    @if($canAddRentalItems)
                                        <a class="btn btn-sm btn-outline-primary mb-2"
                                            href="{{ route('admin.court-bookings.rental-additions.create', $booking) }}"><i
                                                class="bi bi-plus-lg me-1" aria-hidden="true"></i>Tambah perlengkapan</a>
                                    @endif
                                    @if($booking->status === 'confirmed' && $hasRentalItems)
                                        @if($booking->rental_picked_up_at && !$booking->rental_returned_at)
                                            <form class="mb-2" method="POST"
                                                action="{{ route('admin.court-bookings.rentals.return', $booking) }}"
                                                onsubmit="return confirm('Catat bahwa semua perlengkapan booking ini sudah dikembalikan?')">
                                                @csrf
                                                <button class="btn btn-sm btn-success" type="submit"><i
                                                        class="bi bi-box-arrow-in-down-left me-1" aria-hidden="true"></i>Catat
                                                    perlengkapan kembali</button>
                                            </form>
                                        @elseif(!$booking->rental_picked_up_at && !$hasUnresolvedAdditions && $booking->booking_date->toDateString() <= now()->timezone(\App\Models\CourtBooking::TIMEZONE)->toDateString())
                                            <form class="mb-2" method="POST"
                                                action="{{ route('admin.court-bookings.rentals.pickup', $booking) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-primary" type="submit"><i
                                                        class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>Catat perlengkapan
                                                    diambil</button>
                                            </form>
                                        @endif
                                    @endif
                                    @if($waPhone && in_array($booking->status, ['pending_review', 'confirmed', 'rejected', 'cancelled'], true))
                                        @php
                                            $bookingCode = str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT);
                                            $bookingUrl = route('booking.show', $booking->public_token);
                                            $pendingPayments = $booking->payments->where('status', \App\Models\CourtBookingPayment::STATUS_PENDING_REVIEW);
                                            $paymentToVerify = (float) $pendingPayments->sum(fn($payment) => (float) ($payment->claimed_amount ?? 0));
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
                                                <button class="btn btn-sm btn-success" type="button" data-bs-toggle="modal"
                                                    data-bs-target="#bookingPaymentVerification{{ $booking->id }}"
                                                    aria-haspopup="dialog">
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
                                                    <input class="form-control form-control-sm mb-2" name="admin_note"
                                                        maxlength="1000" placeholder="Alasan (opsional)">
                                                    <button class="btn btn-sm btn-danger" type="submit">Tolak bukti</button>
                                                </form>
                                            </details>
                                            <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal"
                                                data-bs-target="#bookingCancellation{{ $booking->id }}" aria-haspopup="dialog">
                                                <i class="bi bi-x-circle" aria-hidden="true"></i>Batalkan booking
                                            </button>
                                        </div>
                                    @elseif($booking->status === 'awaiting_payment' || ($booking->status === 'confirmed' && !$booking->rental_picked_up_at))
                                        <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal"
                                            data-bs-target="#bookingCancellation{{ $booking->id }}" aria-haspopup="dialog"
                                            aria-controls="bookingCancellation{{ $booking->id }}">
                                            <i class="bi bi-x-circle" aria-hidden="true"></i>Batalkan booking
                                        </button>
                                    @elseif($booking->status === \App\Models\CourtBooking::STATUS_CANCELLED && $unresolvedCancelledFunds > 0)
                                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal"
                                            data-bs-target="#bookingFundSettlement{{ $booking->id }}" aria-haspopup="dialog">
                                            <i class="bi bi-wallet2" aria-hidden="true"></i>Putuskan dana
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
            @php
                $bookingFundPaid = (float) $booking->payments
                    ->where('status', \App\Models\CourtBookingPayment::STATUS_CONFIRMED)
                    ->sum(fn($payment) => $payment->recognized_amount)
                    + (float) $booking->rentalAdditions->whereNotNull('confirmed_at')->sum('amount');
                $bookingFundSettled = (float) $booking->fundSettlements->whereNull('voided_at')->sum('amount');
                $bookingFundAvailable = max(0, round($bookingFundPaid - $bookingFundSettled, 2));
                $fundSettlementOldInput = (string) old('_fund_settlement_booking_id', '') === (string) $booking->id;
            @endphp
            @if($booking->status === \App\Models\CourtBooking::STATUS_CANCELLED && $bookingFundPaid > 0 && $bookingFundAvailable > 0)
                <div class="modal fade booking-payment-modal" id="bookingFundSettlement{{ $booking->id }}" tabindex="-1"
                    aria-labelledby="bookingFundSettlementTitle{{ $booking->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form data-fund-settlement-form method="POST"
                                action="{{ route('admin.court-bookings.fund-settlements.store', $booking) }}">
                                @csrf
                                <input type="hidden" name="request_key"
                                    value="{{ $fundSettlementOldInput ? old('request_key') : (string) \Illuminate\Support\Str::uuid() }}">
                                <input type="hidden" name="_fund_settlement_booking_id" value="{{ $booking->id }}">
                                <div class="modal-header">
                                    <div>
                                        <div class="booking-payment-modal-kicker">PENYELESAIAN DANA ·
                                            #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        <h2 class="modal-title" id="bookingFundSettlementTitle{{ $booking->id }}">Putuskan dana
                                            booking</h2>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="small text-secondary">Dana terverifikasi Rp
                                        {{ number_format($bookingFundPaid, 0, ',', '.') }}. Belum diputuskan Rp
                                        {{ number_format($bookingFundAvailable, 0, ',', '.') }}. Pembayaran asli tetap tersimpan
                                        sebagai histori.
                                    </p>
                                    <label class="form-label" for="fundSettlementType{{ $booking->id }}">Keputusan dana</label>
                                    <select class="form-select mb-3" id="fundSettlementType{{ $booking->id }}" name="type"
                                        data-fund-settlement-type required>
                                        <option value="refund" @selected($fundSettlementOldInput ? old('type') === 'refund' : true)>
                                            Sudah dikembalikan kepada pelanggan</option>
                                        <option value="retained" @selected($fundSettlementOldInput && old('type') === 'retained')>
                                            Ditahan sebagai biaya pembatalan</option>
                                    </select>
                                    <label class="form-label" for="fundSettlementAmount{{ $booking->id }}">Nominal keputusan</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Rp</span>
                                        <input class="form-control" id="fundSettlementAmount{{ $booking->id }}" type="text"
                                            inputmode="numeric" autocomplete="off"
                                            value="{{ $fundSettlementOldInput ? old('amount') : (int) $bookingFundAvailable }}"
                                            data-rupiah-input required>
                                        <input type="hidden" name="amount"
                                            value="{{ $fundSettlementOldInput ? preg_replace('/\D+/', '', (string) old('amount')) : (int) $bookingFundAvailable }}"
                                            data-rupiah-value>
                                    </div>
                                    <label class="form-label" for="fundSettlementMethod{{ $booking->id }}"
                                        data-fund-method-label>Metode pengembalian</label>
                                    <select class="form-select mb-3" id="fundSettlementMethod{{ $booking->id }}"
                                        name="payment_method" required>
                                        <option value="cash" @selected($fundSettlementOldInput && old('payment_method') === 'cash')>
                                            Tunai</option>
                                        <option value="bank_transfer" @selected(!$fundSettlementOldInput || old('payment_method') === 'bank_transfer')>Transfer bank</option>
                                        <option value="qris" @selected($fundSettlementOldInput && old('payment_method') === 'qris')>
                                            QRIS</option>
                                        <option value="unclassified" @selected($fundSettlementOldInput && old('payment_method') === 'unclassified')>Belum dirinci</option>
                                    </select>
                                    <label class="form-label" for="fundSettlementReference{{ $booking->id }}">Nomor referensi
                                        (opsional)</label>
                                    <input class="form-control mb-3" id="fundSettlementReference{{ $booking->id }}" name="reference"
                                        maxlength="100" value="{{ $fundSettlementOldInput ? old('reference') : '' }}"
                                        placeholder="Nomor transaksi penyelesaian dana">
                                    <label class="form-label" for="fundSettlementReason{{ $booking->id }}">Alasan / catatan
                                        audit</label>
                                    <textarea class="form-control" id="fundSettlementReason{{ $booking->id }}" name="reason"
                                        rows="3" maxlength="500" required
                                        placeholder="Contoh: dana dikembalikan penuh melalui transfer bank">{{ $fundSettlementOldInput ? old('reason') : '' }}</textarea>
                                    <div class="form-text mt-2">Catat sebagai refund hanya setelah uang benar-benar dikembalikan.
                                        Pilih dana ditahan hanya jika nominal tersebut memang disepakati sebagai biaya pembatalan.
                                    </div>
                                    @if($fundSettlementOldInput && $errors->hasAny(['type', 'amount', 'payment_method', 'reference', 'reason', 'booking']))
                                        <div class="text-danger small mt-2">{{ $errors->first() }}</div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                                    <button class="btn btn-primary" type="submit">Simpan keputusan dana</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @foreach($booking->fundSettlements->whereNull('voided_at') as $settlement)
                <div class="modal fade booking-payment-modal booking-cancel-modal" id="voidFundSettlement{{ $settlement->id }}"
                    tabindex="-1" aria-labelledby="voidFundSettlementTitle{{ $settlement->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST"
                                action="{{ route('admin.court-bookings.fund-settlements.void', [$booking, $settlement]) }}">
                                @csrf
                                <input type="hidden" name="_void_fund_settlement_id" value="{{ $settlement->id }}">
                                <div class="modal-header">
                                    <div>
                                        <div class="booking-payment-modal-kicker">KOREKSI CATATAN DANA ·
                                            #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        <h2 class="modal-title" id="voidFundSettlementTitle{{ $settlement->id }}">Koreksi catatan
                                            penyelesaian</h2>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <p>{{ $settlement->type_label }} Rp
                                        {{ number_format((float) $settlement->amount, 0, ',', '.') }} akan ditandai sebagai catatan
                                        tidak berlaku. Catatan lama tetap tersimpan.
                                    </p>
                                    <p class="small text-secondary">Tindakan ini hanya mengoreksi pencatatan. Tindakan ini tidak
                                        membatalkan atau mengirim transfer uang. Jika dana benar-benar berpindah, buat catatan
                                        pengganti yang sesuai setelah koreksi.</p>
                                    <label class="form-label" for="voidFundSettlementReason{{ $settlement->id }}">Alasan
                                        koreksi</label>
                                    <textarea class="form-control" id="voidFundSettlementReason{{ $settlement->id }}" name="reason"
                                        rows="3" maxlength="500"
                                        required>{{ (string) old('_void_fund_settlement_id') === (string) $settlement->id ? old('reason') : '' }}</textarea>
                                    @if((string) old('_void_fund_settlement_id') === (string) $settlement->id && $errors->has('reason'))
                                        <div class="text-danger small mt-2">{{ $errors->first('reason') }}</div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Kembali</button>
                                    <button class="btn btn-danger" type="submit">Tandai tidak berlaku</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED)
                @php
                    $recordPaymentPaid = (float) $booking->payments
                        ->where('status', \App\Models\CourtBookingPayment::STATUS_CONFIRMED)
                        ->sum(fn($payment) => $payment->recognized_amount);
                    $recordPaymentBalance = max(0, (float) $booking->amount - $recordPaymentPaid);
                    $recordPaymentOldInput = (string) old('_record_payment_booking_id', '') === (string) $booking->id;
                    $recordPaymentDefaultAmount = $recordPaymentOldInput ? old('amount') : ($recordPaymentBalance > 0 ? (int) $recordPaymentBalance : '');
                @endphp

                @if($recordPaymentBalance > 0)
                    <div class="modal fade booking-payment-modal" id="bookingPaymentRecord{{ $booking->id }}" tabindex="-1"
                        aria-labelledby="bookingPaymentRecordTitle{{ $booking->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.court-bookings.payments.store', $booking) }}"
                                    onsubmit="return confirm('Simpan pembayaran yang sudah diterima?')">
                                    @csrf
                                    <input type="hidden" name="_record_payment_booking_id" value="{{ $booking->id }}">

                                    <div class="modal-header">
                                        <div>
                                            <div class="booking-payment-modal-kicker">PEMBAYARAN BOOKING ·
                                                #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                            <h2 class="modal-title" id="bookingPaymentRecordTitle{{ $booking->id }}">Catat pembayaran
                                            </h2>
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
                                                <span>{{ $booking->booking_date->translatedFormat('l, d F Y') }} ·
                                                    {{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }}
                                                    WIB</span>
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
                                            <label class="form-label fw-medium" for="recordPaymentAmount{{ $booking->id }}">Nominal yang
                                                benar-benar diterima</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input class="form-control" id="recordPaymentAmount{{ $booking->id }}" type="text"
                                                    inputmode="numeric" autocomplete="off" spellcheck="false"
                                                    value="{{ $recordPaymentDefaultAmount }}" placeholder="Masukkan nominal pembayaran"
                                                    data-rupiah-input required
                                                    aria-describedby="recordPaymentAmountHelp{{ $booking->id }}">
                                                <input type="hidden" name="amount"
                                                    value="{{ $recordPaymentOldInput ? preg_replace('/\D+/', '', (string) old('amount')) : '' }}"
                                                    data-rupiah-value>
                                            </div>
                                            <div class="form-text" id="recordPaymentAmountHelp{{ $booking->id }}">Pembayaran boleh
                                                sebagian. Nominal tidak boleh melebihi sisa tagihan.</div>
                                            @if($recordPaymentOldInput && $errors->has('amount'))
                                                <div class="text-danger small mt-1">{{ $errors->first('amount') }}</div>
                                            @endif
                                        </div>

                                        <div class="mt-3">
                                            <label class="form-label" for="recordPaymentMethod{{ $booking->id }}">Metode
                                                pembayaran</label>
                                            <select class="form-select" id="recordPaymentMethod{{ $booking->id }}" name="payment_method"
                                                required>
                                                <option value="cash" @selected(!$recordPaymentOldInput || old('payment_method') === 'cash')>Tunai</option>
                                                <option value="bank_transfer" @selected($recordPaymentOldInput && old('payment_method') === 'bank_transfer')>Transfer bank</option>
                                                <option value="qris" @selected($recordPaymentOldInput && old('payment_method') === 'qris')>QRIS</option>
                                            </select>
                                            @if($recordPaymentOldInput && $errors->has('payment_method'))
                                                <div class="text-danger small mt-1">{{ $errors->first('payment_method') }}</div>
                                            @endif
                                        </div>

                                        <div class="mt-3">
                                            <label class="form-label" for="recordPaymentReference{{ $booking->id }}">Referensi
                                                pembayaran <span class="text-secondary fw-normal">(opsional)</span></label>
                                            <input class="form-control" id="recordPaymentReference{{ $booking->id }}"
                                                name="payment_reference" maxlength="100"
                                                value="{{ $recordPaymentOldInput ? old('payment_reference') : '' }}"
                                                placeholder="Nomor referensi transfer atau QRIS">
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

            @foreach($booking->payments as $payment)
                @if(in_array($booking->status, [\App\Models\CourtBooking::STATUS_CONFIRMED, \App\Models\CourtBooking::STATUS_CANCELLED], true) && $payment->status === \App\Models\CourtBookingPayment::STATUS_CONFIRMED)
                    @php
                        $paymentCorrectionId = $booking->id . '_' . $payment->id;
                        $paymentCorrectionOld = (string) old('_payment_edit_id', '') === (string) $payment->id;
                        $paymentUnverifyOld = (string) old('_payment_unverify_id', '') === (string) $payment->id;
                    @endphp
                    <div class="modal fade booking-payment-modal" id="bookingPaymentCorrection{{ $booking->id }}{{ $payment->id }}"
                        tabindex="-1" aria-labelledby="bookingPaymentCorrectionTitle{{ $paymentCorrectionId }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.court-bookings.payments.update', [$booking, $payment]) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="_payment_edit_id" value="{{ $payment->id }}">
                                    <input type="hidden" name="_payment_edit_booking_id" value="{{ $booking->id }}">
                                    <div class="modal-header">
                                        <div>
                                            <div class="booking-payment-modal-kicker">KOREKSI PEMBAYARAN ·
                                                #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                            <h2 class="modal-title" id="bookingPaymentCorrectionTitle{{ $paymentCorrectionId }}">Ubah
                                                pembayaran terverifikasi</h2>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="small text-secondary">Perubahan akan dicatat bersama nominal lama, nominal baru,
                                            admin, dan alasan koreksi.</p>
                                        <label class="form-label" for="correctedPaymentAmount{{ $paymentCorrectionId }}">Nominal
                                            diterima</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp</span>
                                            <input class="form-control" id="correctedPaymentAmount{{ $paymentCorrectionId }}"
                                                type="text" inputmode="numeric" autocomplete="off"
                                                value="{{ $paymentCorrectionOld ? old('verified_amount') : (int) $payment->verified_amount }}"
                                                data-rupiah-input required>
                                            <input type="hidden" name="verified_amount"
                                                value="{{ $paymentCorrectionOld ? preg_replace('/\D+/', '', (string) old('verified_amount')) : (int) $payment->verified_amount }}"
                                                data-rupiah-value>
                                        </div>
                                        <label class="form-label" for="correctedPaymentMethod{{ $paymentCorrectionId }}">Metode
                                            pembayaran</label>
                                        <select class="form-select mb-3" id="correctedPaymentMethod{{ $paymentCorrectionId }}"
                                            name="payment_method" required>
                                            <option value="cash" @selected(($paymentCorrectionOld ? old('payment_method') : $payment->payment_method) === 'cash')>Tunai</option>
                                            <option value="bank_transfer" @selected(($paymentCorrectionOld ? old('payment_method') : $payment->payment_method) === 'bank_transfer')>Transfer bank</option>
                                            <option value="qris" @selected(($paymentCorrectionOld ? old('payment_method') : $payment->payment_method) === 'qris')>QRIS</option>
                                        </select>
                                        <label class="form-label" for="correctedPaymentReference{{ $paymentCorrectionId }}">Referensi
                                            pembayaran</label>
                                        <input class="form-control mb-3" id="correctedPaymentReference{{ $paymentCorrectionId }}"
                                            name="payment_reference" maxlength="100"
                                            value="{{ $paymentCorrectionOld ? old('payment_reference') : $payment->payment_reference }}">
                                        <label class="form-label" for="correctedPaymentNote{{ $paymentCorrectionId }}">Catatan</label>
                                        <textarea class="form-control mb-3" id="correctedPaymentNote{{ $paymentCorrectionId }}"
                                            name="note" rows="2"
                                            maxlength="500">{{ $paymentCorrectionOld ? old('note') : $payment->note }}</textarea>
                                        <label class="form-label" for="paymentCorrectionReason{{ $paymentCorrectionId }}">Alasan
                                            koreksi</label>
                                        <textarea class="form-control" id="paymentCorrectionReason{{ $paymentCorrectionId }}"
                                            name="reason" rows="2" maxlength="500"
                                            required>{{ $paymentCorrectionOld ? old('reason') : '' }}</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-primary" type="submit">Simpan koreksi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if($booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED)
                        <div class="modal fade booking-payment-modal booking-cancel-modal"
                            id="bookingPaymentUnverify{{ $booking->id }}{{ $payment->id }}" tabindex="-1"
                            aria-labelledby="bookingPaymentUnverifyTitle{{ $paymentCorrectionId }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST"
                                        action="{{ route('admin.court-bookings.payments.unverify', [$booking, $payment]) }}">
                                        @csrf
                                        <input type="hidden" name="_payment_unverify_id" value="{{ $payment->id }}">
                                        <input type="hidden" name="_payment_unverify_booking_id" value="{{ $booking->id }}">
                                        <div class="modal-header">
                                            <div>
                                                <div class="booking-payment-modal-kicker">PEMBATALAN VERIFIKASI ·
                                                    #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                                <h2 class="modal-title" id="bookingPaymentUnverifyTitle{{ $paymentCorrectionId }}">
                                                    Kembalikan pembayaran ke pemeriksaan</h2>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Pembayaran Rp {{ number_format($payment->recognized_amount, 0, ',', '.') }} akan kembali ke
                                                status menunggu verifikasi. Slot booking tetap ditahan.</p>
                                            <label class="form-label" for="paymentUnverifyReason{{ $paymentCorrectionId }}">Alasan</label>
                                            <textarea class="form-control" id="paymentUnverifyReason{{ $paymentCorrectionId }}"
                                                name="reason" rows="3" maxlength="500"
                                                required>{{ $paymentUnverifyOld ? old('reason') : '' }}</textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                                            <button class="btn btn-danger" type="submit">Batalkan verifikasi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach

            @if($booking->payment_proof_path)
                <div class="modal fade booking-proof-modal" id="bookingPaymentProof{{ $booking->id }}" tabindex="-1"
                    aria-labelledby="bookingPaymentProofTitle{{ $booking->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div>
                                    <div class="booking-payment-modal-kicker">BUKTI PEMBAYARAN ·
                                        #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                    <h2 class="modal-title fs-5" id="bookingPaymentProofTitle{{ $booking->id }}">Pratinjau bukti
                                    </h2>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup pratinjau"></button>
                            </div>
                            <div class="modal-body">
                                <iframe class="booking-proof-frame"
                                    src="{{ route('admin.court-bookings.proof.preview', $booking) }}"
                                    title="Pratinjau bukti pembayaran booking #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}"
                                    loading="lazy"></iframe>
                            </div>
                            <div class="modal-footer">
                                <span
                                    class="small text-secondary text-break">{{ $booking->payment_proof_original_name ?: 'Bukti pembayaran booking' }}</span>
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
                    <div class="modal fade booking-proof-modal" id="bookingRentalAdditionProof{{ $booking->id }}{{ $addition->id }}"
                        tabindex="-1" aria-labelledby="bookingRentalAdditionProofTitle{{ $booking->id }}{{ $addition->id }}"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div>
                                        <div class="booking-payment-modal-kicker">BUKTI PEMBAYARAN TAMBAHAN ·
                                            #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        <h2 class="modal-title fs-5"
                                            id="bookingRentalAdditionProofTitle{{ $booking->id }}{{ $addition->id }}">Pratinjau bukti
                                            tambahan</h2>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Tutup pratinjau"></button>
                                </div>
                                <div class="modal-body">
                                    <iframe class="booking-proof-frame"
                                        src="{{ route('admin.court-bookings.rental-additions.proof', [$booking, $addition]) }}"
                                        title="Pratinjau bukti pembayaran tambahan booking #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}"
                                        loading="lazy"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <span
                                        class="small text-secondary text-break">{{ $addition->payment_proof_original_name ?: 'Bukti pembayaran tambahan' }}</span>
                                    <a class="btn btn-primary"
                                        href="{{ route('admin.court-bookings.rental-additions.proof.download', [$booking, $addition]) }}">
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
                        ->sum(fn($payment) => $payment->recognized_amount);
                    $verificationRemaining = max(0, (float) $booking->amount - $verificationPaidAmount);
                    $verificationCode = str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT);
                    $verificationOldInput = (string) old('_verification_booking_id', '') === (string) $booking->id;
                    $verificationDefaultAmount = $verificationOldInput
                        ? old('verified_amount')
                        : ($verificationPayment?->claimed_amount !== null ? (int) $verificationPayment->claimed_amount : '');
                @endphp

                @if($verificationPayment)
                    <div class="modal fade booking-payment-modal" id="bookingPaymentVerification{{ $booking->id }}" tabindex="-1"
                        aria-labelledby="bookingPaymentVerificationTitle{{ $booking->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.court-bookings.confirm', $booking) }}">
                                    @csrf
                                    <input type="hidden" name="_verification_booking_id" value="{{ $booking->id }}">

                                    <div class="modal-header">
                                        <div>
                                            <div class="booking-payment-modal-kicker">VERIFIKASI PEMBAYARAN · #{{ $verificationCode }}
                                            </div>
                                            <h2 class="modal-title" id="bookingPaymentVerificationTitle{{ $booking->id }}">Konfirmasi
                                                pembayaran</h2>
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
                                                <span>{{ $booking->booking_date->translatedFormat('l, d F Y') }} ·
                                                    {{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }}
                                                    WIB</span>
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
                                                <span>Nominal klaim pelanggan: <strong>Rp
                                                        {{ number_format((float) $verificationPayment->claimed_amount, 0, ',', '.') }}</strong></span>
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
                                            <p class="small text-secondary mt-3 mb-0">Bukti pembayaran tidak tersedia. Pastikan pembayaran
                                                diterima sebelum booking dikonfirmasi.</p>
                                        @endif

                                        <div class="mt-4">
                                            <label class="form-label fw-medium" for="verifiedAmount{{ $booking->id }}">Nominal yang
                                                benar-benar diterima</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input class="form-control" id="verifiedAmount{{ $booking->id }}"
                                                    name="verified_amount_display" type="text" inputmode="numeric" autocomplete="off"
                                                    spellcheck="false" value="{{ $verificationDefaultAmount }}"
                                                    placeholder="Masukkan nominal pada bukti" data-rupiah-input required
                                                    aria-describedby="verifiedAmountHelp{{ $booking->id }}">
                                                <input type="hidden" name="verified_amount"
                                                    value="{{ $verificationOldInput ? preg_replace('/\D+/', '', (string) old('verified_amount')) : '' }}"
                                                    data-rupiah-value>
                                            </div>
                                            <div class="form-text" id="verifiedAmountHelp{{ $booking->id }}">Masukkan nominal sesuai
                                                bukti dan tidak melebihi sisa tagihan. Booking akan dikonfirmasi setelah verifikasi.
                                            </div>
                                            @if($verificationOldInput && $errors->has('verified_amount'))
                                                <div class="text-danger small mt-1">{{ $errors->first('verified_amount') }}</div>
                                            @endif
                                        </div>

                                        <div class="mt-3">
                                            <label class="form-label" for="verificationNote{{ $booking->id }}">Catatan verifikasi <span
                                                    class="text-secondary fw-normal">(wajib bila berbeda dari klaim)</span></label>
                                            <textarea class="form-control" id="verificationNote{{ $booking->id }}"
                                                name="verification_note" rows="2" maxlength="500"
                                                placeholder="Contoh: nominal pada bukti berbeda dari klaim pelanggan">{{ $verificationOldInput ? old('verification_note') : '' }}</textarea>
                                            @if($verificationOldInput && $errors->has('verification_note'))
                                                <div class="text-danger small mt-1">{{ $errors->first('verification_note') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Periksa lagi
                                            nanti</button>
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

            @if(in_array($booking->status, [\App\Models\CourtBooking::STATUS_AWAITING_PAYMENT, \App\Models\CourtBooking::STATUS_PENDING_REVIEW], true) || ($booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED && !$booking->rental_picked_up_at))
                @php
                    $cancellationCode = str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT);
                    $cancellationOldInput = (string) old('_cancel_booking_id', '') === (string) $booking->id;
                @endphp
                <div class="modal fade booking-payment-modal booking-cancel-modal" id="bookingCancellation{{ $booking->id }}"
                    tabindex="-1" aria-labelledby="bookingCancellationTitle{{ $booking->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admin.court-bookings.cancel', $booking) }}">
                                @csrf
                                <input type="hidden" name="_cancel_booking_id" value="{{ $booking->id }}">

                                <div class="modal-header">
                                    <div>
                                        <div class="booking-payment-modal-kicker">PEMBATALAN BOOKING · #{{ $cancellationCode }}
                                        </div>
                                        <h2 class="modal-title" id="bookingCancellationTitle{{ $booking->id }}">Batalkan booking
                                        </h2>
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
                                            <span>{{ $booking->booking_date->translatedFormat('l, d F Y') }} ·
                                                {{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }}
                                                WIB</span>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label class="form-label" for="bookingCancellationReason{{ $booking->id }}">Alasan
                                            pembatalan <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="bookingCancellationReason{{ $booking->id }}"
                                            name="admin_note" rows="3" maxlength="1000" placeholder="Tuliskan alasan pembatalan"
                                            required>{{ $cancellationOldInput ? old('admin_note') : '' }}</textarea>
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

            @php
                $scheduleCorrectionOldInput = (string) old('_schedule_edit_booking_id', '') === (string) $booking->id;
                $canShowScheduleCorrection = $booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED;
                $correctionMaxDate = now(\App\Models\CourtBooking::TIMEZONE)->addDays(60)->toDateString();
                $correctionDefaultDate = $booking->booking_date->toDateString();
            @endphp
            @if($canShowScheduleCorrection)
                <div class="modal fade booking-payment-modal" id="bookingScheduleCorrection{{ $booking->id }}" tabindex="-1"
                    aria-labelledby="bookingScheduleCorrectionTitle{{ $booking->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admin.court-bookings.schedule.update', $booking) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="_schedule_edit_booking_id" value="{{ $booking->id }}">
                                <div class="modal-header">
                                    <div>
                                        <div class="booking-payment-modal-kicker">KOREKSI BOOKING ·
                                            #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        <h2 class="modal-title" id="bookingScheduleCorrectionTitle{{ $booking->id }}">Koreksi
                                            tanggal dan jam</h2>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="booking-payment-context mb-3">
                                        <div>
                                            <strong>{{ $booking->team_name ?: $booking->customer_name }}</strong>
                                            <span>{{ $booking->court?->name ?? 'Lapangan dihapus' }}</span>
                                        </div>
                                        <div>
                                            <strong>Jadwal tercatat</strong>
                                            <span>{{ $booking->booking_date->format('d/m/Y') }} ·
                                                {{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }}
                                                WIB</span>
                                        </div>
                                    </div>
                                    <div class="alert alert-light border small" role="note">
                                        Admin dapat mengoreksi booking mendatang sebelum waktu mulai atau memperbarui pemakaian setelah
                                        jadwal selesai. Booking yang sedang berlangsung tidak dapat diubah. Tanggal mendatang dibatasi
                                        hingga 60 hari. Jika hanya tanggal yang diubah, total tetap; perubahan jam atau durasi akan
                                        menghitung ulang tagihan. Jadwal yang bentrok ditolak dan semua koreksi dicatat di riwayat.
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-sm-4">
                                            <label class="form-label" for="scheduleCorrectionDate{{ $booking->id }}">Tanggal</label>
                                            <input class="form-control" type="date" id="scheduleCorrectionDate{{ $booking->id }}"
                                                name="booking_date" max="{{ $correctionMaxDate }}"
                                                value="{{ $scheduleCorrectionOldInput ? old('booking_date') : $correctionDefaultDate }}"
                                                required>
                                            @if($scheduleCorrectionOldInput && $errors->has('booking_date'))
                                            <div class="text-danger small mt-1">{{ $errors->first('booking_date') }}</div>@endif
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label" for="scheduleCorrectionStart{{ $booking->id }}">Jam
                                                mulai</label>
                                            <input class="form-control" type="time" id="scheduleCorrectionStart{{ $booking->id }}"
                                                name="time_start" step="60"
                                                value="{{ $scheduleCorrectionOldInput ? old('time_start') : substr($booking->time_start, 0, 5) }}"
                                                required>
                                            @if($scheduleCorrectionOldInput && $errors->has('time_start'))
                                            <div class="text-danger small mt-1">{{ $errors->first('time_start') }}</div>@endif
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label" for="scheduleCorrectionEnd{{ $booking->id }}">Jam
                                                selesai</label>
                                            <input class="form-control" type="time" id="scheduleCorrectionEnd{{ $booking->id }}"
                                                name="time_end" step="60"
                                                value="{{ $scheduleCorrectionOldInput ? old('time_end') : substr($booking->time_end, 0, 5) }}"
                                                required>
                                            @if($scheduleCorrectionOldInput && $errors->has('time_end'))
                                            <div class="text-danger small mt-1">{{ $errors->first('time_end') }}</div>@endif
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label" for="scheduleCorrectionReason{{ $booking->id }}">Alasan koreksi
                                            <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="scheduleCorrectionReason{{ $booking->id }}" name="reason"
                                            rows="3" maxlength="500"
                                            placeholder="Contoh: tanggal salah dicatat; seharusnya 1 Oktober 2026"
                                            required>{{ $scheduleCorrectionOldInput ? old('reason') : '' }}</textarea>
                                        @if($scheduleCorrectionOldInput && $errors->has('reason'))
                                        <div class="text-danger small mt-1">{{ $errors->first('reason') }}</div>@endif
                                    </div>
                                    <section class="border rounded p-3 mt-3"
                                        aria-labelledby="scheduleRefundHeading{{ $booking->id }}">
                                        <h3 class="h6 mb-1" id="scheduleRefundHeading{{ $booking->id }}">Jika total baru lebih
                                            rendah</h3>
                                        <p class="small text-secondary mb-3">Kembalikan kelebihan dana kepada pelanggan terlebih
                                            dahulu. Jika dana bersih yang diterima melebihi total baru, sistem mencatat refund
                                            sesuai nominal selisih dan metode pengembalian.</p>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox"
                                                id="scheduleRefundConfirmed{{ $booking->id }}" name="refund_confirmed" value="1"
                                                @checked($scheduleCorrectionOldInput && old('refund_confirmed'))>
                                            <label class="form-check-label" for="scheduleRefundConfirmed{{ $booking->id }}">Saya
                                                sudah benar-benar mengembalikan selisih dana kepada pelanggan.</label>
                                            @if($scheduleCorrectionOldInput && $errors->has('refund_confirmed'))
                                            <div class="text-danger small mt-1">{{ $errors->first('refund_confirmed') }}</div>@endif
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <label class="form-label" for="scheduleRefundMethod{{ $booking->id }}">Dikembalikan
                                                    melalui</label>
                                                <select class="form-select" id="scheduleRefundMethod{{ $booking->id }}"
                                                    name="refund_payment_method">
                                                    <option value="">Pilih metode</option>
                                                    <option value="cash" @selected($scheduleCorrectionOldInput && old('refund_payment_method') === 'cash')>Tunai</option>
                                                    <option value="bank_transfer" @selected($scheduleCorrectionOldInput && old('refund_payment_method') === 'bank_transfer')>Transfer bank</option>
                                                    <option value="qris" @selected($scheduleCorrectionOldInput && old('refund_payment_method') === 'qris')>QRIS</option>
                                                </select>
                                                @if($scheduleCorrectionOldInput && $errors->has('refund_payment_method'))
                                                    <div class="text-danger small mt-1">{{ $errors->first('refund_payment_method') }}
                                                </div>@endif
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label" for="scheduleRefundReference{{ $booking->id }}">Referensi
                                                    refund <span class="text-secondary">(opsional)</span></label>
                                                <input class="form-control" type="text"
                                                    id="scheduleRefundReference{{ $booking->id }}" name="refund_reference"
                                                    maxlength="100"
                                                    value="{{ $scheduleCorrectionOldInput ? old('refund_reference') : '' }}"
                                                    placeholder="Contoh: nomor referensi transfer">
                                                @if($scheduleCorrectionOldInput && $errors->has('refund_reference'))
                                                    <div class="text-danger small mt-1">{{ $errors->first('refund_reference') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </section>
                                    @if($scheduleCorrectionOldInput && $errors->has('booking'))
                                        <div class="alert alert-danger mt-3 mb-0">{{ $errors->first('booking') }}</div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-dark"><i class="bi bi-clock-history me-1"
                                            aria-hidden="true"></i>Simpan koreksi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if($canShowScheduleCorrection && in_array($booking->source, ['walk_in', 'member'], true))
                @php
                    $discountCorrectionOld = (string) old('_discount_edit_booking_id', '') === (string) $booking->id;
                    $discountGrossSubtotal = round((float) ($booking->subtotal_amount ?? ((float) $booking->amount + (float) ($booking->discount_amount ?? 0))), 2);
                    $discountRentalTotal = round((float) $booking->rentalLines->sum('line_total'), 2);
                    $discountCourtSubtotal = max(0, round($discountGrossSubtotal - $discountRentalTotal, 2));
                    $discountPaymentsGross = (float) $booking->payments
                        ->where('status', \App\Models\CourtBookingPayment::STATUS_CONFIRMED)
                        ->sum(fn($payment) => $payment->recognized_amount);
                    $discountRefunds = (float) $booking->fundSettlements
                        ->where('type', \App\Models\CourtBookingFundSettlement::TYPE_REFUND)
                        ->whereNull('voided_at')
                        ->sum('amount');
                    $discountNetPaid = max(0, round($discountPaymentsGross - $discountRefunds, 2));
                    $discountCurrentType = $discountCorrectionOld ? old('discount_type', $booking->discount_type ?: 'none') : ($booking->discount_type ?: 'none');
                @endphp
                <div class="modal fade booking-payment-modal booking-discount-modal"
                    id="bookingDiscountCorrection{{ $booking->id }}" tabindex="-1"
                    aria-labelledby="bookingDiscountCorrectionTitle{{ $booking->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admin.court-bookings.discount.update', $booking) }}"
                                data-booking-discount-form data-court-subtotal="{{ $discountCourtSubtotal }}"
                                data-gross-subtotal="{{ $discountGrossSubtotal }}" data-net-paid="{{ $discountNetPaid }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="_discount_edit_booking_id" value="{{ $booking->id }}">
                                <div class="modal-header">
                                    <div>
                                        <div class="booking-payment-modal-kicker">KOREKSI BOOKING ·
                                            #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        <h2 class="modal-title" id="bookingDiscountCorrectionTitle{{ $booking->id }}">Ubah diskon
                                        </h2>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="booking-payment-context mb-3">
                                        <div>
                                            <strong>{{ $booking->team_name ?: $booking->customer_name }}</strong>
                                            <span>{{ $booking->source === 'member' ? 'Booking member' : 'Booking walk-in' }}</span>
                                        </div>
                                        <div>
                                            <strong>{{ $booking->court?->name ?? 'Lapangan dihapus' }}</strong>
                                            <span>{{ $booking->booking_date->translatedFormat('d F Y') }} ·
                                                {{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }}</span>
                                        </div>
                                    </div>
                                    <p class="small text-secondary">Diskon hanya mengurangi tarif lapangan. Biaya perlengkapan tetap
                                        sama. Setiap koreksi dicatat agar perubahan tagihan dapat ditelusuri.</p>

                                    <div class="row g-3">
                                        <div class="col-sm-5">
                                            <label class="form-label" for="bookingDiscountType{{ $booking->id }}">Jenis
                                                diskon</label>
                                            <select class="form-select" id="bookingDiscountType{{ $booking->id }}"
                                                name="discount_type" data-discount-type required>
                                                <option value="none" @selected($discountCurrentType === 'none')>Tanpa diskon</option>
                                                <option value="fixed" @selected($discountCurrentType === 'fixed')>Potongan nominal
                                                </option>
                                                <option value="percentage" @selected($discountCurrentType === 'percentage')>Potongan
                                                    persentase</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-7">
                                            <label class="form-label" for="bookingDiscountValue{{ $booking->id }}">Nilai
                                                diskon</label>
                                            <div class="input-group">
                                                <input class="form-control" id="bookingDiscountValue{{ $booking->id }}"
                                                    name="discount_value" type="text" inputmode="decimal" autocomplete="off"
                                                    data-discount-value
                                                    value="{{ $discountCorrectionOld ? old('discount_value', $booking->discount_value) : ($booking->discount_type === 'fixed' ? number_format((float) $booking->discount_value, 0, ',', '.') : (float) ($booking->discount_value ?? 0)) }}"
                                                    placeholder="Contoh: 50.000">
                                                <span class="input-group-text" data-discount-suffix>Rp</span>
                                            </div>
                                            @if($discountCorrectionOld && $errors->has('discount_value'))
                                            <div class="text-danger small mt-1">{{ $errors->first('discount_value') }}</div>@endif
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="form-label" for="bookingDiscountReason{{ $booking->id }}">Alasan
                                            diskon</label>
                                        <input class="form-control" id="bookingDiscountReason{{ $booking->id }}"
                                            name="discount_reason" maxlength="255"
                                            value="{{ $discountCorrectionOld ? old('discount_reason', $booking->discount_reason) : $booking->discount_reason }}"
                                            placeholder="Contoh: potongan member">
                                        @if($discountCorrectionOld && $errors->has('discount_reason'))
                                        <div class="text-danger small mt-1">{{ $errors->first('discount_reason') }}</div>@endif
                                    </div>

                                    <div class="booking-payment-facts booking-discount-facts"
                                        aria-label="Perkiraan total setelah diskon">
                                        <div class="booking-payment-fact">
                                            <span>Subtotal sebelum diskon</span>
                                            <strong data-discount-gross>Rp 0</strong>
                                        </div>
                                        <div class="booking-payment-fact">
                                            <span>Diskon baru</span>
                                            <strong data-discount-amount>Rp 0</strong>
                                        </div>
                                        <div class="booking-payment-fact">
                                            <span>Total tagihan baru</span>
                                            <strong data-discount-total>Rp 0</strong>
                                        </div>
                                    </div>
                                    <div class="small text-secondary mb-3" data-discount-balance></div>

                                    <section class="border rounded p-3" data-discount-refund-section hidden
                                        aria-labelledby="bookingDiscountRefundHeading{{ $booking->id }}">
                                        <h3 class="h6 mb-1" id="bookingDiscountRefundHeading{{ $booking->id }}">Pengembalian selisih
                                        </h3>
                                        <p class="small text-secondary">Total baru lebih rendah daripada dana bersih yang diterima.
                                            Kembalikan selisihnya terlebih dahulu, lalu catat metode pengembalian di sini.</p>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox"
                                                id="bookingDiscountRefundConfirmed{{ $booking->id }}" name="refund_confirmed"
                                                value="1" @checked($discountCorrectionOld && old('refund_confirmed'))
                                                data-discount-refund-confirmed>
                                            <label class="form-check-label"
                                                for="bookingDiscountRefundConfirmed{{ $booking->id }}">Saya sudah benar-benar
                                                mengembalikan selisih dana.</label>
                                            @if($discountCorrectionOld && $errors->has('refund_confirmed'))
                                            <div class="text-danger small mt-1">{{ $errors->first('refund_confirmed') }}</div>@endif
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <label class="form-label"
                                                    for="bookingDiscountRefundMethod{{ $booking->id }}">Dikembalikan melalui</label>
                                                <select class="form-select" id="bookingDiscountRefundMethod{{ $booking->id }}"
                                                    name="refund_payment_method" data-discount-refund-method>
                                                    <option value="">Pilih metode</option>
                                                    <option value="cash" @selected($discountCorrectionOld && old('refund_payment_method') === 'cash')>Tunai</option>
                                                    <option value="bank_transfer" @selected($discountCorrectionOld && old('refund_payment_method') === 'bank_transfer')>Transfer bank</option>
                                                    <option value="qris" @selected($discountCorrectionOld && old('refund_payment_method') === 'qris')>QRIS</option>
                                                </select>
                                                @if($discountCorrectionOld && $errors->has('refund_payment_method'))
                                                    <div class="text-danger small mt-1">{{ $errors->first('refund_payment_method') }}
                                                </div>@endif
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label"
                                                    for="bookingDiscountRefundReference{{ $booking->id }}">Referensi <span
                                                        class="text-secondary">(opsional)</span></label>
                                                <input class="form-control" id="bookingDiscountRefundReference{{ $booking->id }}"
                                                    name="refund_reference" maxlength="100"
                                                    value="{{ $discountCorrectionOld ? old('refund_reference') : '' }}"
                                                    placeholder="Nomor referensi">
                                                @if($discountCorrectionOld && $errors->has('refund_reference'))
                                                    <div class="text-danger small mt-1">{{ $errors->first('refund_reference') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </section>

                                    <div class="mt-3">
                                        <label class="form-label" for="bookingDiscountAuditReason{{ $booking->id }}">Alasan koreksi
                                            <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="bookingDiscountAuditReason{{ $booking->id }}"
                                            name="reason" rows="2" maxlength="500" placeholder="Contoh: diskon awal salah dicatat"
                                            required>{{ $discountCorrectionOld ? old('reason') : '' }}</textarea>
                                        @if($discountCorrectionOld && $errors->has('reason'))
                                        <div class="text-danger small mt-1">{{ $errors->first('reason') }}</div>@endif
                                    </div>
                                    @if($discountCorrectionOld && $errors->has('booking'))
                                        <div class="alert alert-danger mt-3 mb-0">{{ $errors->first('booking') }}</div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-dark" type="submit"><i class="bi bi-check2 me-1"
                                            aria-hidden="true"></i>Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </main>

    @push('scripts')
        <script>
            (() => {
                const formatRupiah = (amount) => `Rp ${new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(Math.max(0, Math.round(amount || 0)))}`;

                document.querySelectorAll('[data-booking-discount-form]').forEach((form) => {
                    const type = form.querySelector('[data-discount-type]');
                    const value = form.querySelector('[data-discount-value]');
                    const reason = form.querySelector('[name="discount_reason"]');
                    const suffix = form.querySelector('[data-discount-suffix]');
                    const grossOutput = form.querySelector('[data-discount-gross]');
                    const discountOutput = form.querySelector('[data-discount-amount]');
                    const totalOutput = form.querySelector('[data-discount-total]');
                    const balanceOutput = form.querySelector('[data-discount-balance]');
                    const refundSection = form.querySelector('[data-discount-refund-section]');
                    const refundConfirmed = form.querySelector('[data-discount-refund-confirmed]');
                    const refundMethod = form.querySelector('[data-discount-refund-method]');
                    const gross = Number(form.dataset.grossSubtotal || 0);
                    const courtSubtotal = Number(form.dataset.courtSubtotal || 0);
                    const paid = Number(form.dataset.netPaid || 0);

                    if (!type || !value || !grossOutput || !discountOutput || !totalOutput || !balanceOutput || !refundSection || !refundConfirmed || !refundMethod) return;

                    const formatFixedInput = () => {
                        const digits = value.value.replace(/\D/g, '').replace(/^0+(?=\d)/, '');
                        value.value = digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    };
                    const updatePreview = () => {
                        const selectedType = type.value;
                        suffix.textContent = selectedType === 'percentage' ? '%' : 'Rp';
                        value.placeholder = selectedType === 'percentage' ? 'Contoh: 10' : 'Contoh: 50.000';
                        const rawValue = selectedType === 'fixed'
                            ? Number(value.value.replace(/\D/g, '') || 0)
                            : Number(value.value.replace(',', '.') || 0);
                        const discount = selectedType === 'fixed'
                            ? Math.min(courtSubtotal, Math.max(0, rawValue))
                            : selectedType === 'percentage'
                                ? courtSubtotal * Math.min(100, Math.max(0, rawValue)) / 100
                                : 0;
                        const newTotal = Math.max(0, gross - discount);
                        const refund = Math.max(0, paid - newTotal);
                        const balance = Math.max(0, newTotal - paid);

                        reason.required = discount > 0;
                        grossOutput.textContent = formatRupiah(gross);
                        discountOutput.textContent = formatRupiah(discount);
                        totalOutput.textContent = formatRupiah(newTotal);
                        balanceOutput.textContent = refund > 0
                            ? `Dana bersih diterima ${formatRupiah(paid)} · Selisih yang harus dikembalikan ${formatRupiah(refund)}`
                            : `Dana bersih diterima ${formatRupiah(paid)} · Sisa tagihan ${formatRupiah(balance)}`;
                        refundSection.hidden = refund <= 0;
                        refundConfirmed.required = refund > 0;
                        refundMethod.required = refund > 0;
                    };

                    let previousType = type.value;
                    type.addEventListener('change', () => {
                        if (type.value !== previousType) {
                            value.value = '';
                            previousType = type.value;
                        }
                        if (type.value === 'fixed') formatFixedInput();
                        updatePreview();
                    });
                    value.addEventListener('input', () => {
                        if (type.value === 'fixed') formatFixedInput();
                        updatePreview();
                    });
                    value.addEventListener('blur', updatePreview);
                    if (type.value === 'fixed') formatFixedInput();
                    updatePreview();
                });
            })();
        </script>
    @endpush

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

    @if((string) old('_payment_edit_id', '') !== '' && $errors->hasAny(['verified_amount', 'payment_method', 'payment_reference', 'note', 'reason']))
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const modal = document.getElementById('bookingPaymentCorrection{{ old('_payment_edit_booking_id') }}{{ old('_payment_edit_id') }}');
                    if (modal && window.bootstrap?.Modal) window.bootstrap.Modal.getOrCreateInstance(modal).show();
                });
            </script>
        @endpush
    @endif

    @if((string) old('_fund_settlement_booking_id', '') !== '' && $errors->hasAny(['request_key', 'type', 'amount', 'payment_method', 'reference', 'reason', 'booking']))
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const modal = document.getElementById('bookingFundSettlement{{ old('_fund_settlement_booking_id') }}');
                    if (modal && window.bootstrap?.Modal) window.bootstrap.Modal.getOrCreateInstance(modal).show();
                });
            </script>
        @endpush
    @endif

    @if((string) old('_void_fund_settlement_id', '') !== '' && $errors->hasAny(['reason', 'settlement', 'booking']))
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const modal = document.getElementById('voidFundSettlement{{ old('_void_fund_settlement_id') }}');
                    if (modal && window.bootstrap?.Modal) window.bootstrap.Modal.getOrCreateInstance(modal).show();
                });
            </script>
        @endpush
    @endif

    @if((string) old('_payment_unverify_id', '') !== '' && $errors->has('reason'))
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const modal = document.getElementById('bookingPaymentUnverify{{ old('_payment_unverify_booking_id') }}{{ old('_payment_unverify_id') }}');
                    if (modal && window.bootstrap?.Modal) window.bootstrap.Modal.getOrCreateInstance(modal).show();
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

    @if((string) old('_schedule_edit_booking_id', '') !== '' && $errors->hasAny(['booking_date', 'time_start', 'time_end', 'reason', 'refund_confirmed', 'refund_payment_method', 'refund_reference', 'booking']))
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const modal = document.getElementById('bookingScheduleCorrection{{ old('_schedule_edit_booking_id') }}');
                    if (modal && window.bootstrap?.Modal) window.bootstrap.Modal.getOrCreateInstance(modal).show();
                });
            </script>
        @endpush
    @endif

    @if((string) old('_discount_edit_booking_id', '') !== '' && $errors->hasAny(['discount_type', 'discount_value', 'discount_reason', 'reason', 'refund_confirmed', 'refund_payment_method', 'refund_reference', 'booking']))
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const modal = document.getElementById('bookingDiscountCorrection{{ old('_discount_edit_booking_id') }}');
                    if (modal && window.bootstrap?.Modal) window.bootstrap.Modal.getOrCreateInstance(modal).show();
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
