@extends('layouts.admin')

@section('title', 'Laporan Operasional Harian')

@push('styles')
    <style>
        .booking-daily-report {
            --report-ink: #111111;
            --report-charcoal: #39393b;
            --report-muted: #707072;
            --report-line: #cacacb;
            --report-line-soft: #e5e5e5;
            --report-cloud: #f5f5f5;
            --report-success: #007d48;
            max-width: 1440px;
            margin-inline: auto;
            color: var(--report-ink);
            font-size: 15px;
            line-height: 1.5;
        }

        .daily-report-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 32px;
        }

        .daily-report-header h1 {
            margin: 0 0 8px;
            color: var(--report-ink);
            font-size: 32px;
            font-weight: 500;
            line-height: 1.2;
        }

        .daily-report-header p {
            max-width: 72ch;
            margin: 0;
            color: var(--report-muted);
            font-size: 15px;
        }

        .daily-report-actions,
        .daily-report-filter-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .booking-daily-report .btn {
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
            transition: background-color 150ms ease-out, color 150ms ease-out, border-color 150ms ease-out;
        }

        .booking-daily-report .btn-primary {
            border-color: var(--report-ink);
            background: var(--report-ink);
            color: #ffffff;
        }

        .booking-daily-report .btn-primary:hover,
        .booking-daily-report .btn-primary:active {
            border-color: var(--report-charcoal);
            background: var(--report-charcoal);
            color: #ffffff;
        }

        .booking-daily-report .btn-outline-primary,
        .booking-daily-report .btn-outline-secondary {
            border-color: transparent;
            background: var(--report-cloud);
            color: var(--report-ink);
        }

        .booking-daily-report .btn-outline-primary:hover,
        .booking-daily-report .btn-outline-secondary:hover {
            border-color: transparent;
            background: #e5e5e5;
            color: var(--report-ink);
        }

        .booking-daily-report .btn:focus-visible,
        .booking-daily-report a:focus-visible,
        .booking-daily-report summary:focus-visible,
        .booking-daily-report input:focus-visible {
            outline: 3px solid rgba(17, 17, 17, .22);
            outline-offset: 2px;
        }

        .booking-daily-report .alert {
            padding: 14px 18px;
            margin-bottom: 16px;
            border: 0;
            border-radius: 0;
            font-size: 14px;
        }

        .daily-report-filter {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            justify-content: space-between;
            gap: 16px 24px;
            padding: 20px 0;
            margin-bottom: 32px;
            border-block: 1px solid var(--report-line-soft);
        }

        .daily-report-filter .form-label {
            display: block;
            margin-bottom: 6px;
            color: var(--report-charcoal);
            font-size: 14px;
            font-weight: 500;
        }

        .daily-report-filter .form-control {
            width: min(100%, 260px);
            min-height: 44px;
            border-color: var(--report-line);
            border-radius: 9999px;
            background: #ffffff;
            color: var(--report-ink);
            font-size: 16px;
            box-shadow: none;
        }

        .daily-report-filter .form-control:focus {
            border-color: var(--report-ink);
            box-shadow: 0 0 0 3px rgba(17, 17, 17, .1);
        }

        .daily-report-filter small {
            display: block;
            margin-top: 5px;
            color: var(--report-muted);
            font-size: 12px;
        }

        .daily-report-overview {
            margin-bottom: 40px;
        }

        .daily-reconciliation {
            padding: 20px 22px;
            margin-bottom: 32px;
            border: 1px solid var(--report-line-soft);
            border-radius: 8px;
            background: #ffffff;
        }

        .daily-reconciliation-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px 24px;
        }

        .daily-reconciliation h2 {
            margin: 0 0 5px;
            color: var(--report-ink);
            font-size: 18px;
            font-weight: 500;
        }

        .daily-reconciliation p {
            max-width: 75ch;
            margin: 0;
            color: var(--report-muted);
            font-size: 13px;
        }

        .daily-reconciliation-progress-label {
            color: var(--report-ink) !important;
            font-size: 14px !important;
            font-weight: 500;
            white-space: nowrap;
        }

        .daily-reconciliation-progress {
            height: 6px;
            overflow: hidden;
            margin: 16px 0 14px;
            border-radius: 9999px;
            background: #e8e8e8;
        }

        .daily-reconciliation-progress > span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: var(--report-success);
            transition: width 180ms ease-out;
        }

        .daily-reconciliation details > summary {
            width: fit-content;
            color: var(--report-charcoal);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
        }

        .daily-reconciliation-table-wrap {
            overflow-x: auto;
            margin-top: 12px;
            border-top: 1px solid var(--report-line-soft);
        }

        .daily-reconciliation-table {
            min-width: 820px;
            margin: 0;
            color: var(--report-ink);
            font-size: 13px;
            --bs-table-bg: transparent;
        }

        .daily-reconciliation-table th {
            padding: 11px 10px;
            border-bottom: 1px solid var(--report-line);
            color: var(--report-muted);
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
        }

        .daily-reconciliation-table td {
            padding: 11px 10px;
            border-bottom: 1px solid var(--report-line-soft);
            vertical-align: middle;
            white-space: nowrap;
        }

        .daily-reconciliation-table tr:last-child td {
            border-bottom: 0;
        }

        .daily-reconciliation-table .is-selected td {
            background: #f5f5f5;
        }

        .daily-reconciliation-table .reconciliation-amount {
            font-variant-numeric: tabular-nums;
            text-align: right;
        }

        .reconciliation-state {
            display: inline-flex;
            align-items: center;
            min-height: 27px;
            padding: 4px 9px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
        }

        .reconciliation-state--matched {
            background: #e7f2ec;
            color: var(--report-success);
        }

        .reconciliation-state--changed {
            background: #f5f1e5;
            color: #67510c;
        }

        .reconciliation-state--unchecked,
        .reconciliation-state--upcoming {
            background: var(--report-cloud);
            color: var(--report-charcoal);
        }

        .daily-reconciliation-status small {
            display: block;
            margin-top: 3px;
            color: var(--report-muted);
            font-size: 11px;
        }

        .daily-reconciliation-open {
            color: var(--report-ink);
            font-size: 13px;
            font-weight: 500;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .daily-reconciliation-action {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px 24px;
            padding: 18px 0;
            margin: 28px 0 32px;
            border-block: 1px solid var(--report-line-soft);
        }

        .daily-reconciliation-action h2 {
            margin: 0 0 4px;
            color: var(--report-ink);
            font-size: 17px;
            font-weight: 500;
        }

        .daily-reconciliation-action p {
            margin: 0;
            color: var(--report-muted);
            font-size: 13px;
        }

        .daily-reconciliation-action .btn:disabled {
            border-color: var(--report-line-soft);
            background: var(--report-cloud);
            color: var(--report-muted);
        }

        .daily-income-methods {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin: -18px 0 36px;
            border-block: 1px solid var(--report-line-soft);
        }

        .daily-income-method {
            min-width: 0;
            padding: 14px 18px;
        }

        .daily-income-method + .daily-income-method {
            border-left: 1px solid var(--report-line-soft);
        }

        .daily-income-method span {
            display: block;
            color: var(--report-muted);
            font-size: 13px;
        }

        .daily-income-method strong {
            display: block;
            margin-top: 3px;
            color: var(--report-ink);
            font-size: 18px;
            font-weight: 500;
            font-variant-numeric: tabular-nums;
        }

        .daily-revenue-entry {
            display: grid;
            grid-template-columns: minmax(220px, .8fr) minmax(0, 1.6fr);
            align-items: center;
            gap: 24px;
            padding: 24px 0;
            margin-bottom: 32px;
            border-block: 1px solid var(--report-line-soft);
        }

        .daily-revenue-entry h2 {
            margin: 0 0 6px;
            color: var(--report-ink);
            font-size: 18px;
            font-weight: 500;
        }

        .daily-revenue-entry p,
        .daily-revenue-last-saved {
            margin: 0;
            color: var(--report-muted);
            font-size: 13px;
        }

        .daily-revenue-last-saved {
            margin-top: 8px;
        }

        .daily-revenue-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: end;
            gap: 12px;
        }

        .daily-income-group {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px 12px;
            min-width: 0;
            padding: 0;
            margin: 0;
            border: 0;
        }

        .daily-income-group legend {
            float: none;
            width: auto;
            margin-bottom: 10px;
            color: var(--report-ink);
            font-size: 15px;
            font-weight: 500;
        }

        .daily-revenue-form .form-label {
            display: block;
            margin-bottom: 6px;
            color: var(--report-charcoal);
            font-size: 13px;
            font-weight: 500;
        }

        .daily-revenue-form .form-control {
            min-height: 44px;
            border-color: var(--report-line);
            border-radius: 9999px;
            color: var(--report-ink);
            font-variant-numeric: tabular-nums;
        }

        .daily-other-income-panel {
            padding: 22px 24px;
            margin-bottom: 32px;
            border: 1px solid var(--report-line-soft);
            border-radius: 8px;
            background: #ffffff;
        }

        .daily-other-income-panel h2 {
            margin: 0 0 5px;
            color: var(--report-ink);
            font-size: 18px;
            font-weight: 500;
        }

        .daily-other-income-panel > p {
            margin: 0 0 18px;
            color: var(--report-muted);
            font-size: 13px;
        }

        .daily-other-income-form {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            align-items: end;
            gap: 12px;
            margin-bottom: 18px;
        }

        .daily-other-income-form .daily-income-note {
            grid-column: span 2;
        }

        .daily-other-income-table input {
            min-width: 130px;
        }

        .daily-other-income-table .daily-entry-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            min-width: 150px;
        }

        @media (max-width: 700px) {
            .daily-other-income-panel { padding: 18px 14px; }
            .daily-other-income-form { grid-template-columns: 1fr 1fr; }
            .daily-other-income-form .btn { grid-column: 1 / -1; width: 100%; }
            .daily-other-income-form .daily-income-note { grid-column: 1 / -1; }
        }

        .daily-revenue-form .form-control:focus {
            border-color: var(--report-ink);
            box-shadow: 0 0 0 3px rgba(17, 17, 17, .1);
        }

        .daily-report-total {
            padding: 0 0 24px;
            border-bottom: 1px solid var(--report-line);
        }

        .daily-report-total-label,
        .daily-report-metric span {
            display: block;
            color: var(--report-muted);
            font-size: 14px;
        }

        .daily-report-total strong {
            display: block;
            margin-top: 4px;
            color: var(--report-ink);
            font-size: clamp(32px, 4vw, 44px);
            font-weight: 500;
            line-height: 1.15;
            font-variant-numeric: tabular-nums;
        }

        .daily-report-total p {
            max-width: 72ch;
            margin: 8px 0 0;
            color: var(--report-muted);
            font-size: 14px;
        }

        .daily-report-metrics {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .daily-report-metric {
            min-width: 0;
            padding: 18px 24px 18px 0;
            border-bottom: 1px solid var(--report-line-soft);
        }

        .daily-report-metric:not(:nth-child(3n + 1)) {
            padding-left: 24px;
            border-left: 1px solid var(--report-line-soft);
        }

        .daily-report-metric strong {
            display: block;
            margin-top: 5px;
            color: var(--report-ink);
            font-size: 20px;
            font-weight: 500;
            line-height: 1.3;
            font-variant-numeric: tabular-nums;
        }

        .daily-report-table {
            border-top: 1px solid var(--report-line);
        }

        .daily-report-table-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px 24px;
            padding: 20px 0;
        }

        .daily-report-table-header h2 {
            margin: 0 0 5px;
            color: var(--report-ink);
            font-size: 20px;
            font-weight: 500;
            line-height: 1.25;
        }

        .daily-report-table-header p,
        .daily-report-table-count {
            margin: 0;
            color: var(--report-muted);
            font-size: 14px;
        }

        .daily-report-table-wrap {
            overflow-x: auto;
            border-top: 1px solid var(--report-line-soft);
        }

        .daily-report-table .table {
            min-width: 900px;
            margin: 0;
            color: var(--report-ink);
            font-size: 14px;
            --bs-table-bg: transparent;
        }

        .daily-report-table .table thead th {
            padding: 13px 12px;
            border-bottom: 1px solid var(--report-line);
            color: var(--report-muted);
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
        }

        .daily-report-table .table tbody td {
            padding: 16px 12px;
            border-bottom: 1px solid var(--report-line-soft);
            vertical-align: top;
        }

        .daily-report-table .table tbody tr:last-child td {
            border-bottom: 0;
        }

        .daily-report-table .text-secondary,
        .daily-report-table small {
            color: var(--report-muted) !important;
        }

        .daily-report-table .badge {
            padding: 6px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
        }

        .daily-report-table .badge.text-bg-success {
            background: #e7f2ec !important;
            color: var(--report-success) !important;
        }

        .daily-report-table .badge.text-bg-warning {
            background: #f5f1e5 !important;
            color: #67510c !important;
        }

        .daily-report-table .badge.text-bg-secondary {
            background: var(--report-cloud) !important;
            color: var(--report-charcoal) !important;
        }

        .daily-report-table .daily-team-edit-trigger {
            white-space: nowrap;
        }

        .daily-team-modal .modal-dialog {
            max-width: 480px;
        }

        .daily-team-modal .modal-content {
            overflow: hidden;
            border: 1px solid var(--report-line);
            border-radius: 0;
            background: #ffffff;
            color: var(--report-ink);
            box-shadow: none;
        }

        .daily-team-modal .modal-header,
        .daily-team-modal .modal-footer {
            padding: 20px 24px;
            border-color: var(--report-line-soft);
        }

        .daily-team-modal .modal-header {
            align-items: flex-start;
        }

        .daily-team-modal .modal-kicker {
            margin: 0 0 5px;
            color: var(--report-muted);
            font-size: 12px;
        }

        .daily-team-modal .modal-title {
            margin: 0;
            color: var(--report-ink);
            font-size: 20px;
            font-weight: 500;
        }

        .daily-team-modal .modal-body {
            padding: 24px;
        }

        .daily-team-modal .modal-help {
            margin: 0 0 20px;
            color: var(--report-muted);
            font-size: 14px;
        }

        .daily-team-modal .form-label {
            margin-bottom: 7px;
            color: var(--report-charcoal);
            font-size: 14px;
            font-weight: 500;
        }

        .daily-team-modal .form-control {
            min-height: 48px;
            border-color: var(--report-line);
            border-radius: 9999px;
            color: var(--report-ink);
            font-size: 16px;
            box-shadow: none;
        }

        .daily-team-modal .form-control:focus {
            border-color: var(--report-ink);
            box-shadow: 0 0 0 3px rgba(17, 17, 17, .1);
        }

        .daily-team-modal .modal-footer {
            gap: 8px;
        }

        .daily-team-modal .btn-outline-secondary {
            border-color: var(--report-line);
            background: #ffffff;
            color: var(--report-ink);
        }

        .daily-team-modal .btn-outline-secondary:hover {
            border-color: var(--report-charcoal);
            background: var(--report-cloud);
            color: var(--report-ink);
        }

        @media (max-width: 575.98px) {
            .daily-team-modal .modal-dialog {
                margin: 12px;
            }

            .daily-team-modal .modal-header,
            .daily-team-modal .modal-body,
            .daily-team-modal .modal-footer {
                padding-inline: 18px;
            }

            .daily-team-modal .modal-footer > .btn {
                flex: 1 1 auto;
            }
        }

        .daily-report-empty {
            padding: 48px 16px !important;
            color: var(--report-muted) !important;
            text-align: center;
        }

        @media (max-width: 767.98px) {
            .daily-report-header {
                gap: 16px;
                margin-bottom: 24px;
            }

            .daily-report-header h1 {
                font-size: 28px;
            }

            .daily-report-actions,
            .daily-report-actions .btn {
                width: 100%;
            }

            .daily-report-filter {
                align-items: stretch;
                margin-bottom: 28px;
            }

            .daily-report-filter > div:first-child,
            .daily-report-filter .form-control,
            .daily-report-filter-actions,
            .daily-report-filter-actions .btn {
                width: 100%;
            }

            .daily-report-filter-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .daily-report-overview {
                margin-bottom: 32px;
            }

            .daily-reconciliation {
                padding: 18px 14px;
            }

            .daily-reconciliation-header {
                display: block;
            }

            .daily-reconciliation-progress-label {
                display: block;
                margin-top: 10px !important;
            }

            .daily-reconciliation-action,
            .daily-reconciliation-action .btn {
                width: 100%;
            }

            .daily-income-methods {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                margin-bottom: 30px;
            }

            .daily-income-method:nth-child(odd) {
                border-left: 0;
            }

            .daily-income-method:nth-child(n + 3) {
                border-top: 1px solid var(--report-line-soft);
            }

            .daily-revenue-entry {
                grid-template-columns: 1fr;
                gap: 16px;
                margin-bottom: 28px;
            }

            .daily-revenue-form {
                grid-template-columns: 1fr 1fr;
            }

            .daily-income-group {
                grid-template-columns: 1fr;
                grid-column: 1 / -1;
            }

            .daily-revenue-form .btn {
                grid-column: 1 / -1;
                width: 100%;
            }

            .daily-report-metrics {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .daily-report-metric,
            .daily-report-metric:not(:nth-child(3n + 1)) {
                padding: 14px 12px 14px 0;
                border-left: 0;
            }

            .daily-report-metric:nth-child(even) {
                padding-right: 0;
                padding-left: 12px;
                border-left: 1px solid var(--report-line-soft);
            }

            .daily-report-metric strong {
                font-size: 18px;
            }

            .daily-report-table-header {
                align-items: flex-start;
                padding-block: 16px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .booking-daily-report .btn {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $formatRupiah = fn ($amount) => 'Rp ' . number_format((float) $amount, 0, ',', '.');
        $selectedDayReconciliation = collect($dailyReconciliation['days'])->firstWhere('date', $reportDate);
        $pendingReconciliationDays = collect($dailyReconciliation['days'])
            ->filter(fn ($day) => in_array($day['status'], ['unchecked', 'changed'], true))
            ->values();
    @endphp

    <main class="booking-daily-report" id="dailyReportTop">
        <header class="daily-report-header">
            <div>
                <h1>Laporan Operasional Harian</h1>
                <p>Rekap pemasukan dan pemakaian lapangan berdasarkan tanggal sewa. Refund dan dana booking batal yang belum diputuskan ditampilkan terpisah.</p>
            </div>
            <nav class="daily-report-actions" aria-label="Aksi laporan harian">
                <a href="{{ route('admin.court-bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Daftar booking
                </a>
                <a href="{{ route('admin.court-bookings.manual.create') }}" class="btn btn-outline-primary">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i>Catat booking langsung
                </a>
            </nav>
        </header>

        @if(session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
        @endif

        <form method="GET" action="{{ route('admin.court-bookings.reports.daily') }}" class="daily-report-filter">
            <div>
                <label class="form-label" for="operationsDate">Tanggal operasional</label>
                <input class="form-control" type="date" id="operationsDate" name="date" value="{{ $reportDate }}" required>
                <small>Booking ditampilkan berdasarkan tanggal pemakaian. Pilih tanggal sebelumnya untuk membuka riwayat.</small>
            </div>
            <div class="daily-report-filter-actions">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-calendar2-check" aria-hidden="true"></i>Tampilkan laporan
                </button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.court-bookings.reports.daily') }}">Hari ini</a>
            </div>
        </form>

        <section class="daily-reconciliation" aria-labelledby="dailyReconciliationTitle">
            <div class="daily-reconciliation-header">
                <div>
                    <h2 id="dailyReconciliationTitle">Progres pencatatan · {{ $reportMonthStart->locale('id')->translatedFormat('F Y') }}</h2>
                    <p>Cocokkan angka laporan tiap tanggal dengan catatan Excel. Tanggal mendatang tidak dihitung sebagai pekerjaan yang tertunda.</p>
                </div>
                <p class="daily-reconciliation-progress-label">
                    @if($dailyReconciliation['total_due_days'] > 0)
                        {{ $dailyReconciliation['reviewed_days'] }} dari {{ $dailyReconciliation['total_due_days'] }} tanggal sudah dicocokkan
                        @if($dailyReconciliation['remaining_days'] > 0)
                            · {{ $dailyReconciliation['remaining_days'] }} belum selesai
                        @endif
                    @else
                        Belum ada tanggal yang perlu dicocokkan
                    @endif
                </p>
            </div>
            <div class="daily-reconciliation-progress" role="progressbar" aria-label="Progres pencocokan laporan bulan ini"
                aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $dailyReconciliation['percentage'] }}">
                <span style="width: {{ $dailyReconciliation['percentage'] }}%"></span>
            </div>
            @if($pendingReconciliationDays->isNotEmpty())
                <p class="mb-3">
                    Belum selesai: {{ $pendingReconciliationDays->take(6)->pluck('label')->implode(', ') }}
                    @if($pendingReconciliationDays->count() > 6)
                        dan {{ $pendingReconciliationDays->count() - 6 }} tanggal lainnya
                    @endif
                </p>
            @endif
            <details>
                <summary>Lihat rincian {{ count($dailyReconciliation['days']) }} tanggal · booking dan pemasukan yang sudah dicatat</summary>
                <div class="daily-reconciliation-table-wrap" role="region" aria-label="Progres pencatatan per tanggal; gulir mendatar untuk melihat semua kolom" tabindex="0">
                    <table class="table daily-reconciliation-table align-middle">
                        <caption class="visually-hidden">Jumlah booking dan pemasukan yang tercatat serta status pencocokan tiap tanggal.</caption>
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Jumlah booking</th>
                                <th scope="col" class="text-end">Pemasukan booking</th>
                                <th scope="col" class="text-end">Parkir</th>
                                <th scope="col" class="text-end">Toko</th>
                                <th scope="col" class="text-end">Sewa alat</th>
                                <th scope="col" class="text-end">Pemasukan lain</th>
                                <th scope="col">Pencocokan</th>
                                <th scope="col">Laporan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyReconciliation['days'] as $day)
                                @php
                                    $statusLabel = match ($day['status']) {
                                        'matched' => 'Sudah dicocokkan',
                                        'changed' => 'Data berubah · cek ulang',
                                        'upcoming' => 'Belum waktunya',
                                        default => 'Belum dicocokkan',
                                    };
                                    $statusClass = match ($day['status']) {
                                        'matched' => 'matched',
                                        'changed' => 'changed',
                                        'upcoming' => 'upcoming',
                                        default => 'unchecked',
                                    };
                                @endphp
                                <tr @class(['is-selected' => $day['date'] === $reportDate])>
                                    <td><strong>{{ $day['label'] }}</strong></td>
                                    <td>{{ number_format($day['booking_count']) }} booking</td>
                                    <td class="reconciliation-amount">{{ $formatRupiah($day['booking_income']) }}</td>
                                    <td class="reconciliation-amount">
                                        @if($day['has_daily_revenue']){{ $formatRupiah($day['parking_income']) }}@else<span class="text-secondary">Belum diinput</span>@endif
                                    </td>
                                    <td class="reconciliation-amount">
                                        @if($day['has_daily_revenue']){{ $formatRupiah($day['shop_income']) }}@else<span class="text-secondary">Belum diinput</span>@endif
                                    </td>
                                    <td class="reconciliation-amount">{{ $formatRupiah($day['rental_income']) }}</td>
                                    <td class="reconciliation-amount">
                                        {{ $formatRupiah($day['other_income']) }}
                                        <small>{{ $day['other_income_count'] }} catatan</small>
                                    </td>
                                    <td class="daily-reconciliation-status">
                                        <span class="reconciliation-state reconciliation-state--{{ $statusClass }}">{{ $statusLabel }}</span>
                                        @if($day['reviewed_at'])
                                            <small>
                                                Dicek {{ $day['reviewed_at']->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M, H:i') }} WIB
                                                @if($day['reviewed_by']) · {{ $day['reviewed_by'] }}@endif
                                            </small>
                                        @endif
                                    </td>
                                    <td><a class="daily-reconciliation-open" href="{{ route('admin.court-bookings.reports.daily', ['date' => $day['date']]) }}#dailyReportTop">Buka</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </details>
        </section>

        <section class="daily-report-overview" aria-label="Ringkasan laporan {{ $reportDate }}">
            <div class="daily-report-total">
                <span class="daily-report-total-label">Total pemasukan tercatat · {{ \Carbon\CarbonImmutable::parse($reportDate)->locale('id')->translatedFormat('d F Y') }}</span>
                <strong>Rp {{ number_format($dailyTotalIncome, 0, ',', '.') }}</strong>
                <p>Nilai booking termasuk perlengkapan tambahan, dikurangi refund, dan memisahkan dana booking batal yang belum diputuskan. Sewa mandiri dihitung terpisah.</p>
            </div>

            <div class="daily-report-metrics">
                <div class="daily-report-metric">
                    <span>Jam terkonfirmasi</span>
                    <strong>{{ intdiv($dailyBookedMinutes, 60) }}j {{ $dailyBookedMinutes % 60 }}m</strong>
                </div>
                <div class="daily-report-metric">
                    <span>Jam menunggu</span>
                    <strong>{{ intdiv($dailyPendingMinutes, 60) }}j {{ $dailyPendingMinutes % 60 }}m</strong>
                </div>
                <div class="daily-report-metric">
                    <span>Pembayaran booking bruto</span>
                    <strong>Rp {{ number_format($dailyBookingPayments, 0, ',', '.') }}</strong>
                </div>
                <div class="daily-report-metric">
                    <span>Pemasukan booking diakui</span>
                    <strong>Rp {{ number_format($dailyBookingIncome, 0, ',', '.') }}</strong>
                </div>
                <div class="daily-report-metric">
                    <span>Termasuk sewa lapangan turnamen</span>
                    <strong>Rp {{ number_format($dailyTournamentRentalIncome, 0, ',', '.') }}</strong>
                </div>
                <div class="daily-report-metric">
                    <span>Tambahan perlengkapan (termasuk di pemasukan booking)</span>
                    <strong>Rp {{ number_format($dailyRentalAdditionIncome, 0, ',', '.') }}</strong>
                </div>
                <div class="daily-report-metric">
                    <span>Refund booking</span>
                    <strong>Rp {{ number_format($dailyBookingRefunds, 0, ',', '.') }}</strong>
                </div>
                <div class="daily-report-metric">
                    <span>Dana batal belum diputuskan</span>
                    <strong>Rp {{ number_format($dailyUnresolvedCancellationFunds, 0, ',', '.') }}</strong>
                </div>
                <div class="daily-report-metric">
                    <span>Sisa tagihan terkonfirmasi</span>
                    <strong>Rp {{ number_format($dailyOutstandingBalance, 0, ',', '.') }}</strong>
                </div>
                <div class="daily-report-metric">
                    <span>Parkir di luar booking</span>
                    <strong>Rp {{ number_format($dailyParkingIncome, 0, ',', '.') }}</strong>
                </div>
                <div class="daily-report-metric">
                    <span>Toko di luar booking</span>
                    <strong>Rp {{ number_format($dailyShopIncome, 0, ',', '.') }}</strong>
                </div>
                <div class="daily-report-metric">
                    <span>Pemasukan lain</span>
                    <strong>Rp {{ number_format($dailyOtherIncome, 0, ',', '.') }}</strong>
                </div>
            </div>
        </section>

        <section class="daily-income-methods" aria-label="Pemasukan diakui menurut metode pembayaran">
            @foreach([
                'cash' => 'Tunai',
                'transfer' => 'Transfer',
                'qris' => 'QRIS',
                'unclassified' => 'Belum dirinci',
            ] as $method => $label)
                <div class="daily-income-method">
                    <span>{{ $label }}</span>
                    <strong>Rp {{ number_format($dailyIncomeByPaymentMethod[$method] ?? 0, 0, ',', '.') }}</strong>
                </div>
            @endforeach
        </section>

        <section class="daily-revenue-entry" aria-labelledby="dailyRevenueEntryTitle">
            <div>
                <h2 id="dailyRevenueEntryTitle">Pemasukan harian di luar booking</h2>
                <p>Pilih tanggal di atas untuk memperbarui catatan parkir dan toko pada hari mana pun. Nilainya berdiri sendiri dan tidak terkait ke pesanan lapangan.</p>
                @if($dailyRevenue?->updated_at)
                    <p class="daily-revenue-last-saved">
                        Terakhir diperbarui {{ $dailyRevenue->updated_at->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M Y, H:i') }} WIB
                        @if($dailyRevenue->updatedBy)
                            oleh {{ $dailyRevenue->updatedBy->name }}
                        @endif
                    </p>
                @endif
            </div>

            <form class="daily-revenue-form" method="POST" action="{{ route('admin.court-bookings.reports.daily-revenue.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="operating_date" value="{{ $reportDate }}">
                @foreach(['parking' => 'Parkir', 'shop' => 'Toko'] as $source => $sourceLabel)
                    <fieldset class="daily-income-group">
                        <legend>{{ $sourceLabel }}</legend>
                        @foreach(['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS', 'unclassified' => 'Belum dirinci'] as $method => $methodLabel)
                            @php
                                $field = "{$source}_{$method}_income";
                            @endphp
                            <div>
                                <label class="form-label" for="daily{{ ucfirst($source) }}{{ ucfirst($method) }}">{{ $methodLabel }} (Rp)</label>
                                <input class="form-control" id="daily{{ ucfirst($source) }}{{ ucfirst($method) }}" name="{{ $field }}" type="text"
                                    inputmode="numeric" autocomplete="off" data-daily-rupiah
                                    value="{{ $formatRupiah(old($field, $dailyRevenue?->{$field} ?? 0)) }}" required>
                            </div>
                        @endforeach
                    </fieldset>
                @endforeach
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-check2" aria-hidden="true"></i>Simpan pemasukan
                </button>
            </form>
        </section>

        <section class="daily-other-income-panel" aria-labelledby="dailyOtherIncomeTitle">
            <h2 id="dailyOtherIncomeTitle">Catat pemasukan lain / sewa turnamen</h2>
            <p>Catat uang sewa turnamen di sini lalu pilih sesi sewanya agar masuk ke pemasukan booking lapangan dan rekap penyewa. Tanpa memilih sesi, uang tetap tercatat sebagai sponsor atau pemasukan lain. Jika uang sewa sudah dicatat, ubah catatan yang ada dan pilih sesi; jangan menambah catatan baru. Jadwal pertandingan tidak otomatis membuat catatan pembayaran.</p>

            <form class="daily-other-income-form" method="POST" action="{{ route('admin.court-bookings.reports.daily-revenue-entries.store') }}">
                @csrf
                <input type="hidden" name="operating_date" value="{{ $reportDate }}">
                <div>
                    <label class="form-label" for="otherIncomeTitle">Sumber pemasukan</label>
                    <input class="form-control" id="otherIncomeTitle" name="title" maxlength="120" placeholder="Contoh: Sponsor" required>
                </div>
                <div>
                    <label class="form-label" for="otherIncomeAmount">Nominal diterima</label>
                    <input class="form-control" id="otherIncomeAmount" name="amount" type="text" inputmode="numeric" autocomplete="off" data-daily-rupiah placeholder="Rp 0" required>
                </div>
                <div>
                    <label class="form-label" for="otherIncomeMethod">Metode pembayaran</label>
                    <select class="form-select" id="otherIncomeMethod" name="payment_method" required>
                        <option value="cash">Tunai</option>
                        <option value="bank_transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                        <option value="unclassified">Belum dirinci</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" for="otherIncomeRental">Sesi sewa lapangan turnamen <span class="text-secondary fw-normal">(opsional)</span></label>
                    <select class="form-select js-revenue-rental-select" id="otherIncomeRental" name="court_tournament_rental_id"
                        data-tournament-target="otherIncomeTournament" data-court-target="otherIncomeCourt">
                        <option value="">Tidak terkait sewa turnamen</option>
                        @foreach($tournamentRentals as $rental)
                            <option value="{{ $rental->id }}" data-tournament-id="{{ $rental->tournament_id }}" data-court-id="{{ $rental->court_id }}" @selected((string) old('court_tournament_rental_id') === (string) $rental->id)>
                                {{ $rental->tournament_name_snapshot }} · {{ $rental->rental_date->locale('id')->translatedFormat('d M Y') }} · {{ $rental->court?->name ?? 'Lapangan dihapus' }} · {{ substr((string) $rental->time_start, 0, 5) }}–{{ substr((string) $rental->time_end, 0, 5) }}{{ $rental->status === \App\Models\CourtTournamentRental::STATUS_CANCELLED ? ' · Dibatalkan' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Jika dipilih, turnamen dan lapangan mengikuti sesi sewa.</div>
                </div>
                <div>
                    <label class="form-label" for="otherIncomeTournament">Turnamen (opsional)</label>
                    <select class="form-select" id="otherIncomeTournament" name="tournament_id" data-original-value="{{ old('tournament_id') }}">
                        <option value="">Tidak terkait turnamen</option>
                        @foreach($tournaments as $tournament)
                            <option value="{{ $tournament->id }}">{{ $tournament->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label" for="otherIncomeCourt">Lapangan (opsional)</label>
                    <select class="form-select" id="otherIncomeCourt" name="court_id" data-original-value="{{ old('court_id') }}">
                        <option value="">Pilih lapangan</option>
                        @foreach($courts as $court)
                            <option value="{{ $court->id }}">{{ $court->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="daily-income-note">
                    <label class="form-label" for="otherIncomeNote">Catatan</label>
                    <input class="form-control" id="otherIncomeNote" name="note" maxlength="500" placeholder="Opsional">
                </div>
                <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i>Catat pemasukan</button>
            </form>

            <div class="table-responsive daily-other-income-table">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Sumber / keterkaitan</th><th>Nominal</th><th>Metode</th><th>Sesi sewa lapangan</th><th>Turnamen</th><th>Lapangan</th><th>Catatan</th><th>Aksi</th></tr></thead>
                    <tbody>
                    @forelse($dailyRevenueEntries as $entry)
                        <tr>
                            <td><label class="visually-hidden" for="otherIncomeTitle{{ $entry->id }}">Sumber pemasukan</label><input class="form-control" form="editOtherIncome{{ $entry->id }}" id="otherIncomeTitle{{ $entry->id }}" name="title" maxlength="120" value="{{ $entry->title }}" required>
                                @if($entry->court_tournament_rental_id)<span class="badge text-bg-light">Sewa lapangan turnamen</span>@else<span class="badge text-bg-light">Pemasukan lain</span>@endif
                                @if($entry->courtTournamentRental)<div class="small text-secondary">{{ $entry->courtTournamentRental->tournament_name_snapshot }} · {{ $entry->courtTournamentRental->rental_date->locale('id')->translatedFormat('d M Y') }} · {{ substr((string) $entry->courtTournamentRental->time_start, 0, 5) }}–{{ substr((string) $entry->courtTournamentRental->time_end, 0, 5) }}</div>@endif
                                @if($entry->tournament)<div class="small text-secondary">{{ $entry->tournament->name }}</div>@endif</td>
                            <td><label class="visually-hidden" for="otherIncomeAmount{{ $entry->id }}">Nominal diterima</label><input class="form-control" form="editOtherIncome{{ $entry->id }}" id="otherIncomeAmount{{ $entry->id }}" name="amount" type="text" inputmode="numeric" autocomplete="off" data-daily-rupiah value="{{ $formatRupiah($entry->amount) }}" required></td>
                            <td><label class="visually-hidden" for="otherIncomeMethod{{ $entry->id }}">Metode pembayaran</label><select class="form-select" form="editOtherIncome{{ $entry->id }}" id="otherIncomeMethod{{ $entry->id }}" name="payment_method" required>
                                @foreach(['cash' => 'Tunai', 'bank_transfer' => 'Transfer', 'qris' => 'QRIS', 'unclassified' => 'Belum dirinci'] as $method => $methodLabel)<option value="{{ $method }}" @selected($entry->payment_method === $method)>{{ $methodLabel }}</option>@endforeach
                            </select></td>
                            <td><label class="visually-hidden" for="otherIncomeRental{{ $entry->id }}">Sesi sewa lapangan turnamen</label><select class="form-select js-revenue-rental-select" form="editOtherIncome{{ $entry->id }}" id="otherIncomeRental{{ $entry->id }}" name="court_tournament_rental_id" data-tournament-target="otherIncomeTournament{{ $entry->id }}" data-court-target="otherIncomeCourt{{ $entry->id }}"><option value="">Tidak terkait sewa turnamen</option>@foreach($tournamentRentals as $rental)<option value="{{ $rental->id }}" data-tournament-id="{{ $rental->tournament_id }}" data-court-id="{{ $rental->court_id }}" @selected((int) $entry->court_tournament_rental_id === (int) $rental->id)>{{ $rental->tournament_name_snapshot }} · {{ $rental->rental_date->locale('id')->translatedFormat('d M Y') }} · {{ $rental->court?->name ?? 'Lapangan dihapus' }} · {{ substr((string) $rental->time_start, 0, 5) }}–{{ substr((string) $rental->time_end, 0, 5) }}{{ $rental->status === \App\Models\CourtTournamentRental::STATUS_CANCELLED ? ' · Dibatalkan' : '' }}</option>@endforeach</select></td>
                            <td><label class="visually-hidden" for="otherIncomeTournament{{ $entry->id }}">Turnamen</label><select class="form-select" form="editOtherIncome{{ $entry->id }}" id="otherIncomeTournament{{ $entry->id }}" name="tournament_id" data-original-value="{{ $entry->tournament_id }}"><option value="">Tidak terkait</option>@foreach($tournaments as $tournament)<option value="{{ $tournament->id }}" @selected((int) $entry->tournament_id === (int) $tournament->id)>{{ $tournament->name }}</option>@endforeach</select></td>
                            <td><label class="visually-hidden" for="otherIncomeCourt{{ $entry->id }}">Lapangan</label><select class="form-select" form="editOtherIncome{{ $entry->id }}" id="otherIncomeCourt{{ $entry->id }}" name="court_id" data-original-value="{{ $entry->court_id }}"><option value="">Tidak terkait</option>@foreach($courts as $court)<option value="{{ $court->id }}" @selected((int) $entry->court_id === (int) $court->id)>{{ $court->name }}</option>@endforeach</select></td>
                            <td><label class="visually-hidden" for="otherIncomeNote{{ $entry->id }}">Catatan</label><input class="form-control" form="editOtherIncome{{ $entry->id }}" id="otherIncomeNote{{ $entry->id }}" name="note" maxlength="500" value="{{ $entry->note }}" placeholder="Catatan opsional"></td>
                            <td>
                                <div class="daily-entry-actions">
                        <form id="editOtherIncome{{ $entry->id }}" method="POST" action="{{ route('admin.court-bookings.reports.daily-revenue-entries.update', $entry) }}">
                                        @csrf @method('PUT')
                                        <button class="btn btn-outline-primary btn-sm" type="submit">Simpan perubahan</button>
                                    </form>
                                    <button class="btn btn-outline-danger btn-sm" type="submit" form="deleteOtherIncome{{ $entry->id }}">Hapus</button>
                                </div>
                                <form id="deleteOtherIncome{{ $entry->id }}" method="POST" action="{{ route('admin.court-bookings.reports.daily-revenue-entries.destroy', $entry) }}" onsubmit="return confirm('Hapus catatan pemasukan {{ $entry->title }}?')">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-secondary">Belum ada pemasukan lain untuk tanggal ini.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="daily-report-table" aria-labelledby="dailyMatchesTitle">
            <header class="daily-report-table-header">
                <div>
                    <h2 id="dailyMatchesTitle">Jadwal pertandingan {{ \Carbon\CarbonImmutable::parse($reportDate)->locale('id')->translatedFormat('d F Y') }}</h2>
                    <p>Jadwal pertandingan menandai penggunaan lapangan. Uang sewanya dicatat pada sesi sewa turnamen; biaya pendaftaran atau sponsor dicatat sebagai pemasukan lain.</p>
                </div>
                <span class="daily-report-table-count">{{ number_format($dailyMatches->count()) }} pertandingan</span>
            </header>
            <div class="daily-report-table-wrap" role="region" aria-label="Daftar pertandingan harian" tabindex="0">
                <table class="table align-middle">
                    <caption class="visually-hidden">Daftar pertandingan dan turnamen pada tanggal operasional terpilih.</caption>
                    <thead>
                        <tr>
                            <th scope="col">Turnamen / jenis</th>
                            <th scope="col">Pertandingan</th>
                            <th scope="col">Lapangan</th>
                            <th scope="col">Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailyMatches as $dailyMatch)
                            <tr>
                                <td>{{ $dailyMatch->tournament?->name ?? 'Pertandingan persahabatan' }}</td>
                                <td>{{ $dailyMatch->homeTeam?->name ?? 'TBA' }} vs {{ $dailyMatch->awayTeam?->name ?? 'TBA' }}</td>
                                <td>{{ $dailyMatch->venue ?: 'Lapangan belum ditentukan' }}</td>
                                <td class="text-nowrap">{{ $dailyMatch->time_start ? substr($dailyMatch->time_start, 0, 5) : 'Jam belum ditentukan' }}@if($dailyMatch->time_end)–{{ substr($dailyMatch->time_end, 0, 5) }}@endif</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="daily-report-empty">Tidak ada jadwal pertandingan pada tanggal ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="daily-report-table" aria-labelledby="dailyBookingsTitle">
            <header class="daily-report-table-header">
                <div>
                    <h2 id="dailyBookingsTitle">Booking tanggal {{ \Carbon\CarbonImmutable::parse($reportDate)->locale('id')->translatedFormat('d F Y') }}</h2>
                    <p>Jam sewa dihitung dari booking terkonfirmasi. Parkir dan toko direkap terpisah di atas.</p>
                </div>
                <span class="daily-report-table-count">{{ number_format($dailyBookings->count()) }} booking</span>
            </header>

            <div class="daily-report-table-wrap" role="region" aria-label="Daftar booking harian, gulir mendatar untuk melihat kolom lainnya" tabindex="0">
                <table class="table align-middle">
                    <caption class="visually-hidden">Daftar booking harian beserta pemesan, jam sewa, status, dan pembayaran.</caption>
                    <thead>
                        <tr>
                            <th scope="col">Tim / pemesan</th>
                            <th scope="col">Jam sewa</th>
                            <th scope="col">Status</th>
                            <th scope="col">DP / pembayaran</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailyBookings as $dailyBooking)
                            @php
                                $dailyPaid = (float) $dailyBooking->payments
                                    ->where('status', \App\Models\CourtBookingPayment::STATUS_CONFIRMED)
                                    ->sum(fn ($payment) => $payment->recognized_amount);
                    $dailySettlements = $dailyBooking->fundSettlements;
                    $dailySettledAmount = (float) $dailySettlements->whereNull('voided_at')->sum('amount');
                    $dailyPaidAdditions = $dailyBooking->status === 'cancelled'
                        ? (float) $dailyBooking->rentalAdditions->whereNotNull('confirmed_at')->sum('amount')
                        : 0;
                    $dailyBookingFundsReceived = $dailyPaid + $dailyPaidAdditions;
                    $bookingFundsUnresolved = $dailyBooking->status === 'cancelled'
                        ? max(0, round($dailyBookingFundsReceived - $dailySettledAmount, 2))
                        : 0;
                                $dailyPending = (float) $dailyBooking->payments
                                    ->where('status', \App\Models\CourtBookingPayment::STATUS_PENDING_REVIEW)
                                    ->sum(fn ($payment) => (float) ($payment->claimed_amount ?? 0));
                                $dailyBalance = $dailyBooking->status === 'confirmed'
                                    ? max(0, (float) $dailyBooking->amount - $dailyPaid)
                                    : null;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $dailyBooking->team_name ?: 'Nama tim belum dicatat' }}</strong>
                                    <div class="small text-secondary">{{ $dailyBooking->customer_name }} · {{ $dailyBooking->court?->name ?? 'Lapangan dihapus' }}</div>
                                </td>
                                <td class="text-nowrap">
                                    {{ substr($dailyBooking->time_start, 0, 5) }}–{{ substr($dailyBooking->time_end, 0, 5) }}
                                    <div class="small text-secondary">{{ $dailyBooking->duration_label }}</div>
                                </td>
                                <td>
                                    <span class="badge text-bg-{{ $dailyBooking->status === 'confirmed' ? 'success' : (in_array($dailyBooking->status, ['pending_review', 'awaiting_payment'], true) ? 'warning' : 'secondary') }}">{{ $dailyBooking->status_label }}</span>
                                    @if($dailyBooking->status === 'cancelled')
                                        <div class="small text-secondary mt-1">
                                            Dibatalkan {{ $dailyBooking->cancelled_at?->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M, H:i') }}
                                            oleh {{ $dailyBooking->cancelled_by_name_snapshot ?? $dailyBooking->cancelledBy?->name ?? 'Admin tidak diketahui' }}
                                            @if($dailyBooking->admin_note)<br>Alasan: {{ $dailyBooking->admin_note }}@endif
                                        </div>
                                    @endif
                                </td>
                                <td style="min-width: 235px">
                                    @forelse($dailyBooking->payments as $dailyPayment)
                                        <div class="small {{ $dailyPayment->status === 'confirmed' ? '' : 'text-secondary' }}">
                                            {{ $dailyPayment->stage_label }} · {{ $dailyPayment->payment_method_label }} · {{ $dailyPayment->status_label }} ·
                                            @if($dailyPayment->status === 'confirmed')
                                                Terverifikasi Rp {{ number_format($dailyPayment->recognized_amount, 0, ',', '.') }}
                                            @elseif($dailyPayment->claimed_amount !== null)
                                                Klaim pelanggan Rp {{ number_format((float) $dailyPayment->claimed_amount, 0, ',', '.') }}
                                            @elseif($dailyPayment->status === 'rejected')
                                                Nominal tidak terverifikasi
                                            @else
                                                Bukti diterima; nominal menunggu pemeriksaan admin
                                            @endif
                                            <span class="text-secondary">{{ $dailyPayment->recorded_at?->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M, H:i') }}</span>
                                        </div>
                                        @if($dailyPayment->status === 'confirmed' && $dailyPayment->claimed_amount !== null && abs((float) $dailyPayment->claimed_amount - (float) $dailyPayment->verified_amount) >= 0.01)
                                            <div class="small text-secondary">Klaim awal Rp {{ number_format((float) $dailyPayment->claimed_amount, 0, ',', '.') }} · {{ $dailyPayment->note }}</div>
                                        @endif
                                    @empty
                                        <span class="small text-secondary">Belum ada pembayaran</span>
                                    @endforelse

                                    @if($dailyPaidAdditions > 0)
                                        <div class="small text-secondary">Pembayaran tambahan perlengkapan terverifikasi Rp {{ number_format($dailyPaidAdditions, 0, ',', '.') }}</div>
                                        @foreach($dailyBooking->rentalAdditions->whereNotNull('confirmed_at') as $paidAddition)
                                            <div class="small text-secondary">Tambahan terverifikasi {{ $paidAddition->confirmed_at->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M, H:i') }} · {{ $paidAddition->verifiedBy?->name ?? 'Admin dihapus' }} · Rp {{ number_format((float) $paidAddition->amount, 0, ',', '.') }}</div>
                                        @endforeach
                                    @endif

                                    @foreach($dailySettlements as $dailySettlement)
                                        <div class="small {{ $dailySettlement->voided_at ? 'text-secondary text-decoration-line-through' : '' }}">
                                            {{ $dailySettlement->type_label }} · Rp {{ number_format((float) $dailySettlement->amount, 0, ',', '.') }}
                                            @if($dailySettlement->payment_method_label) · {{ $dailySettlement->payment_method_label }}@endif
                                            · {{ $dailySettlement->recorded_at?->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M, H:i') }}
                                        </div>
                                        <div class="small text-secondary">Dicatat {{ $dailySettlement->recorded_by_name_snapshot ?? $dailySettlement->recordedBy?->name ?? 'Admin tidak diketahui' }} · {{ $dailySettlement->reason }}</div>
                                        @if($dailySettlement->voided_at)
                                            <div class="small text-secondary">Dibatalkan {{ $dailySettlement->voided_at->timezone(\App\Models\CourtBooking::TIMEZONE)->format('d M, H:i') }} oleh {{ $dailySettlement->voided_by_name_snapshot ?? $dailySettlement->voidedBy?->name ?? 'Admin tidak diketahui' }} · {{ $dailySettlement->void_reason }}</div>
                                        @endif
                                    @endforeach

                                    @if($dailyBooking->status === 'confirmed')
                                        <div class="small text-secondary mt-1">Diterima Rp {{ number_format($dailyPaid, 0, ',', '.') }} · Sisa tagihan Rp {{ number_format($dailyBalance, 0, ',', '.') }}</div>
                                    @elseif($dailyBooking->status === 'pending_review')
                                        <div class="small text-warning-emphasis mt-1">Belum dihitung sebagai diterima ·
                                            @if($dailyPending > 0)
                                                Klaim menunggu verifikasi Rp {{ number_format($dailyPending, 0, ',', '.') }}
                                            @else
                                                nominal menunggu pemeriksaan admin
                                            @endif
                                        </div>
                                    @elseif($dailyBooking->status === 'awaiting_payment')
                                        <div class="small text-secondary mt-1">Belum ada pembayaran terverifikasi · Tagihan Rp {{ number_format((float) $dailyBooking->amount, 0, ',', '.') }}</div>
                                    @elseif($dailyPaid > 0)
                                        <div class="small text-secondary mt-1">Pembayaran terverifikasi Rp {{ number_format($dailyPaid, 0, ',', '.') }}</div>
                                    @endif
                                    @if($bookingFundsUnresolved > 0)
                                        <div class="small text-warning-emphasis mt-1">Dana batal belum diputuskan Rp {{ number_format($bookingFundsUnresolved, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($dailyBooking->status === 'confirmed')
                                        <button class="btn btn-sm btn-outline-secondary daily-team-edit-trigger" type="button"
                                            data-bs-toggle="modal" data-bs-target="#dailyTeamModal{{ $dailyBooking->id }}"
                                            aria-haspopup="dialog">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>Ubah nama tim
                                        </button>
                                    @else
                                        <span class="small text-secondary">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="daily-report-empty">Belum ada booking pada tanggal ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="daily-reconciliation-action" aria-labelledby="dailyReconciliationActionTitle">
            <div>
                <h2 id="dailyReconciliationActionTitle">
                    @if($selectedDayReconciliation['status'] === 'matched')
                        Laporan tanggal ini sudah dicocokkan
                    @elseif($selectedDayReconciliation['status'] === 'changed')
                        Data berubah sejak pemeriksaan terakhir
                    @elseif($selectedDayReconciliation['status'] === 'upcoming')
                        Tanggal ini belum dapat dicocokkan
                    @else
                        Tandai laporan setelah dicocokkan
                    @endif
                </h2>
                <p>
                    @if($selectedDayReconciliation['status'] === 'upcoming')
                        Pencocokan tersedia setelah tanggal operasional berlangsung.
                    @elseif(!$selectedDayReconciliation['has_daily_revenue'])
                        Simpan pemasukan parkir dan toko untuk tanggal ini, termasuk Rp 0 bila tidak ada pemasukan, sebelum menandainya selesai.
                    @else
                        Setelah angka booking, parkir, toko, sewa alat, dan pemasukan lain cocok dengan Excel, tandai tanggal ini selesai.
                        Perubahan data sesudahnya akan meminta pemeriksaan ulang.
                    @endif
                </p>
            </div>
            @if($selectedDayReconciliation['status'] === 'upcoming')
                <button class="btn btn-primary" type="button" disabled>Belum dapat dicocokkan</button>
            @elseif(!$selectedDayReconciliation['has_daily_revenue'])
                <a class="btn btn-primary" href="#dailyRevenueEntryTitle">
                    <i class="bi bi-pencil-square" aria-hidden="true"></i>Isi parkir dan toko
                </a>
            @else
                <form method="POST" action="{{ route('admin.court-bookings.reports.daily-review.store') }}">
                    @csrf
                    <input type="hidden" name="operating_date" value="{{ $reportDate }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2-circle" aria-hidden="true"></i>
                        {{ $selectedDayReconciliation['status'] === 'matched' ? 'Perbarui pemeriksaan' : 'Tandai sudah dicocokkan' }}
                    </button>
                </form>
            @endif
        </section>

        @foreach($dailyBookings as $dailyBooking)
            @if($dailyBooking->status === 'confirmed')
                <div class="modal fade daily-team-modal" id="dailyTeamModal{{ $dailyBooking->id }}" tabindex="-1"
                    aria-labelledby="dailyTeamModalTitle{{ $dailyBooking->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admin.court-bookings.team.update', $dailyBooking) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="_team_booking_id" value="{{ $dailyBooking->id }}">

                                <div class="modal-header">
                                    <div>
                                        <p class="modal-kicker">Booking #{{ str_pad((string) $dailyBooking->id, 6, '0', STR_PAD_LEFT) }}</p>
                                        <h2 class="modal-title" id="dailyTeamModalTitle{{ $dailyBooking->id }}">Ubah nama tim</h2>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>

                                <div class="modal-body">
                                    <p class="modal-help">Nama ini digunakan pada laporan operasional untuk booking {{ $dailyBooking->court?->name ?? 'lapangan' }}.</p>
                                    <label class="form-label" for="dailyTeam{{ $dailyBooking->id }}">Nama tim</label>
                                    <input class="form-control" id="dailyTeam{{ $dailyBooking->id }}" name="team_name"
                                        maxlength="120" autocomplete="organization"
                                        value="{{ (string) old('_team_booking_id') === (string) $dailyBooking->id ? old('team_name', $dailyBooking->team_name) : $dailyBooking->team_name }}"
                                        required>
                                    @if((string) old('_team_booking_id') === (string) $dailyBooking->id)
                                        @error('team_name')
                                            <div class="invalid-feedback d-block" role="alert">{{ $message }}</div>
                                        @enderror
                                        @error('booking')
                                            <div class="invalid-feedback d-block" role="alert">{{ $message }}</div>
                                        @enderror
                                    @endif
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-check2" aria-hidden="true"></i>Simpan nama tim
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </main>
@endsection

@push('scripts')
    <script>
        (() => {
            const failedBookingId = @json(old('_team_booking_id'));
            if (failedBookingId) {
                const failedModal = document.getElementById(`dailyTeamModal${failedBookingId}`);
                if (failedModal && window.bootstrap?.Modal) {
                    window.bootstrap.Modal.getOrCreateInstance(failedModal).show();
                }
            }

            const formatter = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });
            const fields = document.querySelectorAll('[data-daily-rupiah]');
            const formatValue = (digits) => digits ? `Rp ${formatter.format(Number(digits))}` : '';

            document.querySelectorAll('.js-revenue-rental-select').forEach((rentalSelect) => {
                const tournamentSelect = document.getElementById(rentalSelect.dataset.tournamentTarget || '');
                const courtSelect = document.getElementById(rentalSelect.dataset.courtTarget || '');
                const syncRentalDetails = () => {
                    const selectedRental = rentalSelect.selectedOptions[0];
                    const hasRental = Boolean(rentalSelect.value);
                    [
                        [tournamentSelect, 'tournamentId'],
                        [courtSelect, 'courtId'],
                    ].forEach(([select, key]) => {
                        if (!select) return;
                        select.disabled = hasRental;
                        select.value = hasRental
                            ? (selectedRental?.dataset[key] || '')
                            : (select.dataset.originalValue || '');
                    });
                };
                rentalSelect.addEventListener('change', syncRentalDetails);
                syncRentalDetails();
            });

            fields.forEach((field) => field.addEventListener('input', () => {
                const previousValue = field.value;
                const caret = field.selectionStart ?? previousValue.length;
                const digitsBeforeCaret = (previousValue.slice(0, caret).match(/\d/g) || []).length;
                const digits = previousValue.replace(/\D/g, '');
                const formatted = formatValue(digits);
                field.value = formatted;

                let nextCaret = 0;
                let seenDigits = 0;
                while (nextCaret < formatted.length && seenDigits < digitsBeforeCaret) {
                    if (/\d/.test(formatted[nextCaret])) seenDigits += 1;
                    nextCaret += 1;
                }
                if (digitsBeforeCaret === 0) nextCaret = Math.min(3, formatted.length);
                field.setSelectionRange(nextCaret, nextCaret);
            }));
        })();
    </script>
@endpush
