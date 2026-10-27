@extends('layouts.admin')

@section('title', 'Laporan Pemasukan')

@push('styles')
    <style>
        .booking-revenue-page {
            --revenue-ink: #111111;
            --revenue-muted: #707072;
            --revenue-line: #e5e5e5;
            --revenue-cloud: #f5f5f5;
            --revenue-canvas: #ffffff;
            max-width: 1440px;
            margin: 0 auto;
            color: var(--revenue-ink);
            font-size: 16px;
            line-height: 1.5;
        }

        .revenue-page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px 24px;
            margin-bottom: 24px;
        }

        .revenue-page-header h1 {
            margin: 0 0 8px;
            color: var(--revenue-ink);
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
        }

        .revenue-page-header p {
            max-width: 72ch;
            margin: 0;
            color: var(--revenue-muted);
            font-size: 15px;
        }

        .revenue-page-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .booking-revenue-page .btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 10px 18px;
            border: 0;
            border-radius: 9999px;
            background: var(--revenue-cloud);
            color: var(--revenue-ink);
            font-size: 14px;
            font-weight: 500;
            box-shadow: none;
        }

        .booking-revenue-page .btn:hover {
            background: #e5e5e5;
            color: var(--revenue-ink);
        }

        .booking-revenue-page .report-panel {
            margin-bottom: 24px;
            overflow: hidden;
            border: 1px solid var(--revenue-line);
            border-radius: 8px;
            background: var(--revenue-canvas);
        }

        .revenue-overview {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .revenue-method-panel {
            margin: -4px 0 24px;
            border-block: 1px solid var(--revenue-line);
        }

        .revenue-method-panel h2 {
            margin: 0;
            padding: 15px 0 11px;
            color: var(--revenue-muted);
            font-size: 13px;
            font-weight: 500;
        }

        .revenue-method-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .revenue-method-summary > div {
            min-width: 0;
            padding: 12px 18px 16px 0;
        }

        .revenue-method-summary > div + div {
            padding-left: 18px;
            border-left: 1px solid var(--revenue-line);
        }

        .revenue-method-summary span {
            display: block;
            color: var(--revenue-muted);
            font-size: 13px;
        }

        .revenue-method-summary strong {
            display: block;
            margin-top: 3px;
            color: var(--revenue-ink);
            font-size: 19px;
            font-weight: 500;
            font-variant-numeric: tabular-nums;
        }

        .revenue-month-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            border-bottom: 1px solid var(--revenue-line);
        }

        .revenue-month-summary--net {
            grid-template-columns: minmax(0, 1fr);
        }

        .revenue-month-summary > div {
            min-width: 0;
            padding: 17px 22px;
        }

        .revenue-month-summary > div + div {
            border-left: 1px solid var(--revenue-line);
        }

        .revenue-month-summary span {
            display: block;
            color: var(--revenue-muted);
            font-size: 13px;
        }

        .revenue-month-summary strong {
            display: block;
            margin-top: 3px;
            font-size: 21px;
            font-weight: 500;
            font-variant-numeric: tabular-nums;
        }

        .revenue-net-result {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 20px 22px;
            border-left: 4px solid var(--revenue-ink);
            border-bottom: 1px solid var(--revenue-line);
            background: var(--revenue-cloud);
        }

        .revenue-net-result span {
            display: block;
            color: var(--revenue-ink);
            font-size: 15px;
            font-weight: 600;
        }

        .revenue-net-result p {
            margin: 4px 0 0;
            color: var(--revenue-muted);
            font-size: 13px;
        }

        .revenue-net-result strong {
            flex: 0 0 auto;
            color: var(--revenue-ink);
            font-size: clamp(24px, 3vw, 32px);
            font-weight: 650;
            line-height: 1.15;
            font-variant-numeric: tabular-nums;
        }

        .revenue-net-result.is-negative strong {
            color: #b42318;
        }

        .revenue-expense-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .revenue-month-filter {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 10px;
        }

        .revenue-month-filter label {
            display: block;
            margin-bottom: 5px;
            color: var(--revenue-muted);
            font-size: 12px;
        }

        .revenue-month-filter .form-control {
            width: 190px;
            min-height: 42px;
            border: 1px solid #cacacb;
            border-radius: 9999px;
            box-shadow: none;
        }

        .revenue-month-filter .form-control:focus {
            border-color: var(--revenue-ink);
            box-shadow: 0 0 0 3px rgb(17 17 17 / 10%);
        }

        .revenue-month-filter .btn-primary {
            background: var(--revenue-ink);
            color: #ffffff;
        }

        .revenue-month-filter .btn-primary:hover {
            background: #39393b;
            color: #ffffff;
        }

        .revenue-month-table tfoot th,
        .revenue-month-table tfoot td {
            border-top: 1px solid #c9c9c9;
            font-weight: 500;
        }

        .revenue-month-table tfoot tr:last-child {
            background: #f5f5f5;
        }

        .revenue-month-note {
            margin: 0;
            padding: 13px 22px 17px;
            color: var(--revenue-muted);
            font-size: 13px;
        }

        .refund-audit {
            border-top: 1px solid var(--revenue-line);
        }

        .refund-audit-summary {
            min-height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 22px;
            color: var(--revenue-ink);
            cursor: pointer;
            list-style: none;
        }

        .refund-audit-summary::-webkit-details-marker {
            display: none;
        }

        .refund-audit-summary > span:first-child {
            min-width: 0;
        }

        .refund-audit-summary:focus-visible {
            outline: 2px solid var(--revenue-ink);
            outline-offset: -3px;
        }

        .refund-audit-title {
            display: block;
            font-weight: 500;
        }

        .refund-audit-count {
            display: block;
            margin-top: 2px;
            color: var(--revenue-muted);
            font-size: 13px;
        }

        .refund-audit-total {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            flex: 0 0 auto;
            font-variant-numeric: tabular-nums;
        }

        .refund-audit-chevron {
            color: var(--revenue-muted);
            transition: transform 160ms ease;
        }

        .refund-audit[open] .refund-audit-chevron {
            transform: rotate(180deg);
        }

        .refund-audit-list {
            padding: 0 22px;
            border-top: 1px solid var(--revenue-line);
        }

        .refund-entry {
            padding: 17px 0;
        }

        .refund-entry + .refund-entry {
            border-top: 1px solid var(--revenue-line);
        }

        .refund-entry-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .refund-entry-top > div:first-child {
            min-width: 0;
        }

        .refund-booking-link {
            color: var(--revenue-ink);
            font-weight: 600;
            text-decoration: none;
        }

        .refund-booking-link:hover {
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .refund-booking-person,
        .refund-booking-contact {
            display: block;
            margin-top: 3px;
        }

        .refund-booking-contact,
        .refund-entry-meta dt {
            color: var(--revenue-muted);
            font-size: 13px;
        }

        .refund-entry-amount {
            flex: 0 0 auto;
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .refund-entry-amount span {
            display: block;
            color: var(--revenue-muted);
            font-size: 12px;
        }

        .refund-entry-amount strong {
            display: block;
            margin-top: 2px;
            font-weight: 600;
        }

        .refund-entry-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 24px;
            margin: 13px 0 0;
        }

        .refund-entry-meta div {
            min-width: 0;
        }

        .refund-entry-meta dt {
            margin: 0 0 2px;
        }

        .refund-entry-meta dd {
            margin: 0;
            overflow-wrap: anywhere;
        }

        .refund-audit-empty {
            margin: 0;
            padding: 20px 0;
            color: var(--revenue-muted);
        }

        .refund-audit-pagination {
            padding: 12px 22px;
            border-top: 1px solid var(--revenue-line);
        }

        .refund-audit-pagination nav > div:first-child {
            display: none;
        }

        .refund-audit-pagination nav > div:last-child {
            justify-content: flex-end;
        }

        .revenue-total,
        .revenue-source {
            min-width: 0;
            padding: 22px 24px;
        }

        .revenue-source {
            border-left: 1px solid var(--revenue-line);
        }

        .revenue-total {
            grid-column: 1 / -1;
            border-bottom: 1px solid var(--revenue-line);
        }

        .revenue-source:nth-child(3n + 2) {
            border-left: 0;
        }

        .revenue-source:nth-child(n + 5) {
            border-top: 1px solid var(--revenue-line);
        }

        .revenue-label {
            display: block;
            margin-bottom: 8px;
            color: var(--revenue-muted);
            font-size: 13px;
            line-height: 1.4;
        }

        .revenue-total strong {
            display: block;
            font-size: clamp(25px, 2.4vw, 34px);
            font-weight: 500;
            line-height: 1.2;
            font-variant-numeric: tabular-nums;
        }

        .revenue-source strong {
            display: block;
            font-size: 20px;
            font-weight: 500;
            line-height: 1.3;
            font-variant-numeric: tabular-nums;
        }

        .revenue-caption {
            display: block;
            margin-top: 7px;
            color: var(--revenue-muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .revenue-booking-methods {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px 14px;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid var(--revenue-line);
        }

        .revenue-booking-methods > div {
            display: grid;
            gap: 2px;
            min-width: 0;
            padding: 8px 10px;
            border-radius: 6px;
            background: var(--revenue-cloud);
        }

        .revenue-booking-methods span {
            color: var(--revenue-muted);
            font-size: 12px;
            line-height: 1.35;
        }

        .revenue-booking-methods strong {
            font-size: 13px;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .report-panel-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px 24px;
            padding: 18px 22px;
            border-bottom: 1px solid var(--revenue-line);
        }

        .report-panel-header h2 {
            margin: 0 0 5px;
            color: var(--revenue-ink);
            font-size: 18px;
            font-weight: 500;
            line-height: 1.3;
        }

        .report-panel-header p {
            max-width: 78ch;
            margin: 0;
            color: var(--revenue-muted);
            font-size: 14px;
            line-height: 1.5;
        }

        .revenue-month-header {
            align-items: center;
        }

        .revenue-table {
            margin: 0;
            color: var(--revenue-ink);
        }

        .revenue-table th,
        .revenue-table td {
            padding: 13px 22px;
            border-color: var(--revenue-line);
            vertical-align: middle;
        }

        .revenue-table thead th {
            color: var(--revenue-muted);
            font-size: 13px;
            font-weight: 500;
        }

        .revenue-table tbody th {
            font-weight: 400;
        }

        .revenue-money {
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .revenue-chart-body {
            padding: 18px 22px 22px;
        }

        .revenue-chart-frame {
            position: relative;
            height: 300px;
        }

        .revenue-chart-canvas {
            width: 100% !important;
            height: 100% !important;
            outline: none;
        }

        .revenue-chart-canvas:focus-visible {
            outline: 2px solid var(--revenue-ink);
            outline-offset: 3px;
        }

        .revenue-chart-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, max-content));
            gap: 12px 40px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--revenue-line);
            font-variant-numeric: tabular-nums;
        }

        .revenue-chart-summary span {
            display: block;
            color: var(--revenue-muted);
            font-size: 13px;
        }

        .revenue-chart-summary strong {
            display: block;
            font-size: 15px;
            font-weight: 500;
        }

        .revenue-chart-details summary {
            width: fit-content;
            color: var(--revenue-ink);
            cursor: pointer;
            font-weight: 500;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .revenue-peak {
            min-width: 180px;
            padding-left: 20px;
            border-left: 1px solid var(--revenue-line);
        }

        .revenue-peak span {
            display: block;
            margin-bottom: 3px;
            color: var(--revenue-muted);
            font-size: 13px;
        }

        @media (max-width: 1199.98px) {
            .revenue-overview {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

            .revenue-total {
                grid-column: 1 / -1;
                border-bottom: 1px solid var(--revenue-line);
            }

            .revenue-source:nth-child(2) {
                border-left: 0;
            }

            .revenue-source:nth-child(4n + 2) {
                border-left: 0;
            }

            .revenue-source:nth-child(n + 6) {
                border-top: 1px solid var(--revenue-line);
            }
        }

        @media (max-width: 767.98px) {
            .revenue-page-header {
                display: block;
            }

            .revenue-page-actions {
                justify-content: flex-start;
                margin-top: 18px;
            }

            .revenue-overview {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .revenue-method-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .revenue-month-summary {
                grid-template-columns: 1fr;
            }

            .revenue-net-result {
                align-items: flex-start;
                flex-direction: column;
                gap: 8px;
                padding: 18px 16px;
            }

            .revenue-net-result strong {
                font-size: 28px;
            }

            .revenue-month-summary > div + div {
                border-top: 1px solid var(--revenue-line);
                border-left: 0;
            }

            .revenue-month-header {
                align-items: stretch;
            }

            .revenue-month-filter {
                width: 100%;
            }

            .revenue-month-filter .field,
            .revenue-month-filter .form-control {
                width: 100%;
            }

            .revenue-month-filter .btn {
                flex: 1 1 auto;
            }

            .revenue-method-summary > div:nth-child(odd) {
                padding-left: 0;
                border-left: 0;
            }

            .revenue-method-summary > div:nth-child(n + 3) {
                border-top: 1px solid var(--revenue-line);
            }

            .revenue-source:nth-child(2n) {
                border-left: 0;
            }

            .revenue-source:nth-child(n + 3) {
                border-top: 1px solid var(--revenue-line);
            }

            .report-panel-header,
            .revenue-chart-body {
                padding-inline: 16px;
            }

            .revenue-chart-summary {
                grid-template-columns: repeat(2, minmax(0, max-content));
                gap: 12px 24px;
            }

            .revenue-chart-summary>div:last-child {
                grid-column: 1 / -1;
            }

            .revenue-peak {
                min-width: 0;
                padding: 12px 0 0;
                border-top: 1px solid var(--revenue-line);
                border-left: 0;
            }
        }

        @media (max-width: 575.98px) {
            .revenue-page-actions .btn {
                flex: 1 1 calc(50% - 8px);
                padding-inline: 12px;
            }

            .revenue-overview {
                grid-template-columns: 1fr;
            }

            .revenue-total {
                grid-column: auto;
            }

            .revenue-source,
            .revenue-source:nth-child(2) {
                border-top: 1px solid var(--revenue-line);
                border-left: 0;
            }

            .revenue-total,
            .revenue-source {
                padding: 18px 16px;
            }

            .revenue-method-summary > div {
                padding-inline: 10px;
            }

            .revenue-month-summary > div {
                padding: 15px 16px;
            }

            .revenue-month-note {
                padding-inline: 16px;
            }

            .refund-audit-summary {
                align-items: flex-start;
                padding-inline: 16px;
            }

            .refund-audit-total {
                gap: 7px;
                font-size: 14px;
            }

            .refund-audit-list {
                padding-inline: 16px;
            }

            .refund-entry-top {
                gap: 12px;
            }

            .refund-entry-meta {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .refund-audit-pagination {
                padding-inline: 16px;
            }

            .revenue-method-summary > div:nth-child(odd) {
                padding-left: 0;
            }

            .revenue-total strong {
                font-size: 28px;
            }

            .revenue-chart-frame {
                height: 250px;
            }

            .revenue-table th,
            .revenue-table td {
                padding-inline: 14px;
            }
        }
    </style>
@endpush

@section('content')
    <main class="booking-revenue-page">
        <header class="revenue-page-header">
            <div>
                <h1>Laporan pemasukan</h1>
                <p>Ringkasan pembayaran terverifikasi dari booking, sewa perlengkapan, parkir, toko, dan pemasukan lain.</p>
            </div>
            <nav class="revenue-page-actions" aria-label="Navigasi laporan pemasukan">
                <a href="{{ route('admin.court-bookings.index') }}" class="btn">
                    <i class="bi bi-calendar-check" aria-hidden="true"></i>Booking lapangan
                </a>
                <a href="{{ route('admin.court-bookings.reports.daily') }}" class="btn">
                    <i class="bi bi-clipboard-data" aria-hidden="true"></i>Laporan operasional
                </a>
                <a href="{{ route('admin.court-bookings.expenses.index', ['month' => $selectedMonth]) }}" class="btn">
                    <i class="bi bi-receipt" aria-hidden="true"></i>Pengeluaran
                </a>
            </nav>
        </header>

        <section class="report-panel" aria-labelledby="monthlyRevenueTitle">
            <header class="report-panel-header revenue-month-header">
                <div>
                    <h2 id="monthlyRevenueTitle">Rekap pemasukan bulanan</h2>
                    <p>{{ $selectedMonthLabel }} · Booking publik, walk-in, member, dan sewa lapangan turnamen, serta parkir, toko, perlengkapan, dan pemasukan lain.</p>
                </div>
                <form class="revenue-month-filter" method="GET" action="{{ route('admin.court-bookings.revenue') }}">
                    <div class="field">
                        <label for="revenueMonth">Pilih bulan</label>
                        <input class="form-control" id="revenueMonth" name="month" type="month" value="{{ $selectedMonth }}" max="{{ now(\App\Models\CourtBooking::TIMEZONE)->format('Y-m') }}" required>
                    </div>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-calendar-check" aria-hidden="true"></i>Tampilkan</button>
                </form>
            </header>

            <div class="revenue-month-summary revenue-month-summary--net" aria-label="Ringkasan penerimaan bulan {{ $selectedMonthLabel }}">
                <div>
                    <span>Total pemasukan setelah refund · {{ $selectedMonthLabel }}</span>
                    <strong>Rp {{ number_format($monthlyNetReceipts, 0, ',', '.') }}</strong>
                </div>
            </div>

            @php
                $monthlyCategoryLabels = [
                    'booking' => 'Booking lapangan bersih',
                    'rental_additions' => 'Tambahan perlengkapan booking',
                    'standalone_rentals' => 'Sewa perlengkapan mandiri',
                    'parking' => 'Parkir',
                    'shop' => 'Toko',
                    'other' => 'Sponsor / pemasukan lain',
                ];
                $monthlyMethodLabels = [
                    'cash' => 'Tunai',
                    'transfer' => 'Transfer',
                    'qris' => 'QRIS',
                    'unclassified' => 'Belum dirinci',
                ];
            @endphp
            <div class="table-responsive">
                <table class="table align-middle revenue-table revenue-month-table">
                    <caption class="visually-hidden">Pemasukan setelah refund berdasarkan sumber serta metode pembayaran untuk {{ $selectedMonthLabel }}.</caption>
                    <thead>
                        <tr>
                            <th scope="col">Sumber transaksi</th>
                            @foreach($monthlyMethodLabels as $methodLabel)
                                <th scope="col" class="text-end">{{ $methodLabel }}</th>
                            @endforeach
                            <th scope="col" class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyCategoryLabels as $category => $categoryLabel)
                            @php
                                $categoryAmounts = $category === 'booking'
                                    ? $monthlyNetBookingByMethod
                                    : $monthlyIncomeByCategory[$category];
                                $categoryTotal = array_sum($categoryAmounts);
                            @endphp
                            <tr>
                                <th scope="row">{{ $categoryLabel }}</th>
                                @foreach($monthlyMethodLabels as $method => $methodLabel)
                                    @php $amount = $categoryAmounts[$method]; @endphp
                                    <td class="text-end revenue-money">{{ $amount < 0 ? '− ' : '' }}Rp {{ number_format(abs($amount), 0, ',', '.') }}</td>
                                @endforeach
                                <td class="text-end revenue-money"><strong>{{ $categoryTotal < 0 ? '− ' : '' }}Rp {{ number_format(abs($categoryTotal), 0, ',', '.') }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row">Total pemasukan setelah refund</th>
                            @foreach($monthlyMethodLabels as $method => $methodLabel)
                                @php
                                    $netAmount = $monthlyNetByMethod[$method];
                                @endphp
                                <td class="text-end revenue-money">{{ $netAmount < 0 ? '− ' : '' }}Rp {{ number_format(abs($netAmount), 0, ',', '.') }}</td>
                            @endforeach
                            <td class="text-end revenue-money">{{ $monthlyNetReceipts < 0 ? '− ' : '' }}Rp {{ number_format(abs($monthlyNetReceipts), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <p class="revenue-month-note">Baris booking lapangan sudah dikurangi refund sesuai metode dana dikembalikan, sehingga tunai dan transfer menunjukkan nilai bersih. Refund tetap tersimpan dan dapat ditelusuri di rincian refund atau daftar booking untuk dikoreksi. Pembayaran booking masuk ke bulan saat dicatat. Pemasukan sewa turnamen masuk ke baris booking jika ditautkan ke sesi sewanya. Parkir, toko, sponsor, dan pemasukan lain mengikuti tanggal operasional yang dipilih admin. Total ini menunjukkan arus kas setelah refund, sebelum pengeluaran.</p>

            <details class="refund-audit" @if(request()->filled('refund_page')) open @endif>
                <summary class="refund-audit-summary">
                    <span>
                        <span class="refund-audit-title">Rincian refund booking</span>
                        <span class="refund-audit-count">{{ number_format($monthlyRefundDetails->total(), 0, ',', '.') }} catatan pada {{ $selectedMonthLabel }}</span>
                    </span>
                    <span class="refund-audit-total">
                        <strong>Rp {{ number_format($monthlyRefundTotal, 0, ',', '.') }}</strong>
                        <i class="bi bi-chevron-down refund-audit-chevron" aria-hidden="true"></i>
                    </span>
                </summary>

                <div class="refund-audit-list">
                    @if($monthlyRefundDetails->total() === 0)
                        <p class="refund-audit-empty">Belum ada refund booking yang dicatat pada {{ $selectedMonthLabel }}.</p>
                    @else
                        @foreach($monthlyRefundDetails as $refund)
                            @php
                                $refundRecordedAt = \Carbon\CarbonImmutable::parse($refund->recorded_at, 'UTC')
                                    ->setTimezone(\App\Models\CourtBooking::TIMEZONE)
                                    ->locale('id');
                                $refundBookingDate = \Carbon\CarbonImmutable::parse($refund->booking_date, \App\Models\CourtBooking::TIMEZONE)
                                    ->locale('id')
                                    ->translatedFormat('d M Y');
                                $refundMethodLabel = match ($refund->payment_method) {
                                    'cash' => 'Tunai',
                                    'bank_transfer' => 'Transfer bank',
                                    'qris' => 'QRIS',
                                    default => 'Belum dirinci',
                                };
                                $refundDisplayName = filled($refund->team_name)
                                    ? $refund->team_name
                                    : $refund->customer_name;
                            @endphp
                            <article class="refund-entry">
                                <div class="refund-entry-top">
                                    <div>
                                        <a class="refund-booking-link" href="{{ route('admin.court-bookings.index', ['date' => $refund->booking_date]) }}">
                                            Booking #{{ str_pad((string) $refund->booking_id, 6, '0', STR_PAD_LEFT) }}
                                        </a>
                                        <span class="refund-booking-person">{{ $refundDisplayName }}</span>
                                        <span class="refund-booking-contact">Catatan refund #{{ $refund->settlement_id }}</span>
                                        @if(filled($refund->team_name) && filled($refund->customer_name) && $refund->team_name !== $refund->customer_name)
                                            <span class="refund-booking-contact">Pemesan: {{ $refund->customer_name }}</span>
                                        @endif
                                    </div>
                                    <div class="refund-entry-amount">
                                        <span>Dana dikembalikan</span>
                                        <strong>− Rp {{ number_format((float) $refund->amount, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                                <dl class="refund-entry-meta">
                                    <div>
                                        <dt>Jadwal pemakaian</dt>
                                        <dd>{{ $refundBookingDate }} · {{ $refund->court_name ?: 'Lapangan' }} · {{ substr((string) $refund->time_start, 0, 5) }}–{{ substr((string) $refund->time_end, 0, 5) }}</dd>
                                    </div>
                                    <div>
                                        <dt>Refund dicatat</dt>
                                        <dd>{{ $refundRecordedAt->translatedFormat('d M Y, H:i') }} WIB · {{ $refundMethodLabel }}</dd>
                                    </div>
                                    <div>
                                        <dt>Dicatat oleh</dt>
                                        <dd>{{ $refund->recorded_by_name_snapshot ?: 'Admin' }}</dd>
                                    </div>
                                    <div>
                                        <dt>Alasan</dt>
                                        <dd>{{ $refund->reason }}</dd>
                                    </div>
                                    @if(filled($refund->reference))
                                        <div>
                                            <dt>Referensi</dt>
                                            <dd>{{ $refund->reference }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </article>
                        @endforeach

                        @if($monthlyRefundDetails->hasPages())
                            <div class="refund-audit-pagination">
                                {{ $monthlyRefundDetails->onEachSide(1)->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </details>
        </section>

        <section class="report-panel" aria-labelledby="monthlyExpensesTitle">
            <header class="report-panel-header">
                <div>
                    <h2 id="monthlyExpensesTitle">Pengeluaran dan hasil bersih · {{ $selectedMonthLabel }}</h2>
                    <p>Pengeluaran operasional dikurangkan setelah penerimaan booking dan pemasukan lain dikurangi refund.</p>
                </div>
                <a class="btn" href="{{ route('admin.court-bookings.expenses.index', ['month' => $selectedMonth]) }}">Kelola pengeluaran</a>
            </header>
            <div class="revenue-net-result{{ $monthlyOperatingSurplus < 0 ? ' is-negative' : '' }}" aria-label="Sisa arus kas setelah pengeluaran tercatat">
                <div>
                    <span>Sisa setelah pengeluaran tercatat</span>
                    <p>Pemasukan bulan ini setelah refund, dikurangi pengeluaran yang dicatat di sistem.</p>
                </div>
                <strong>{{ $monthlyOperatingSurplus < 0 ? '− ' : '' }}Rp {{ number_format(abs($monthlyOperatingSurplus), 0, ',', '.') }}</strong>
            </div>
            <div class="revenue-month-summary revenue-expense-summary">
                <div><span>Total pengeluaran</span><strong>Rp {{ number_format($monthlyExpenseTotal, 0, ',', '.') }}</strong></div>
                <div><span>Rincian pembayaran</span><strong class="small">Tunai · transfer · QRIS</strong></div>
            </div>
            @php
                $expenseCategoryLabels = ['salary' => 'Gaji', 'electricity' => 'Listrik', 'other' => 'Lain-lain'];
                $expenseMethodLabels = ['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS', 'unclassified' => 'Belum dirinci'];
            @endphp
            <div class="table-responsive">
                <table class="table align-middle revenue-table revenue-month-table">
                    <caption class="visually-hidden">Pengeluaran menurut kategori dan metode pembayaran untuk {{ $selectedMonthLabel }}.</caption>
                    <thead><tr><th scope="col">Kategori pengeluaran</th>@foreach($expenseMethodLabels as $label)<th scope="col" class="text-end">{{ $label }}</th>@endforeach<th scope="col" class="text-end">Total</th></tr></thead>
                    <tbody>
                        @foreach($expenseCategoryLabels as $category => $label)
                            @php
                                $categoryTotal = array_sum($monthlyExpenseByCategory[$category]);
                            @endphp
                            <tr><th scope="row">{{ $label }}</th>@foreach($expenseMethodLabels as $method => $methodLabel)<td class="text-end revenue-money">Rp {{ number_format($monthlyExpenseByCategory[$category][$method], 0, ',', '.') }}</td>@endforeach<td class="text-end revenue-money"><strong>Rp {{ number_format($categoryTotal, 0, ',', '.') }}</strong></td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="revenue-month-note">Hasil setelah pengeluaran adalah ringkasan arus kas periode ini, bukan laporan laba akuntansi. Catatan yang dihapus tetap ada di riwayat audit tetapi tidak dihitung.</p>
        </section>

        <section class="report-panel revenue-overview" aria-label="Total pemasukan tercatat">
            <div class="revenue-total">
                <span class="revenue-label">Total pemasukan tercatat</span>
                <strong>Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong>
                <span class="revenue-caption">Pemasukan yang diakui setelah refund dan dana pembatalan belum diputuskan
                    dipisahkan.</span>
            </div>
            <div class="revenue-source">
                <span class="revenue-label">Pemasukan booking diakui</span>
                <strong>Rp {{ number_format($bookingIncome, 0, ',', '.') }}</strong>
                <span class="revenue-caption">Booking aktif, sewa lapangan turnamen, perlengkapan tambahan, dan biaya pembatalan yang ditahan</span>
                <div class="revenue-booking-methods" aria-label="Rincian pemasukan booking bersih berdasarkan metode pembayaran">
                    @foreach(['cash' => 'Tunai bersih', 'transfer' => 'Transfer bersih', 'qris' => 'QRIS bersih', 'unclassified' => 'Belum dirinci'] as $method => $label)
                        <div>
                            <span>{{ $label }}</span>
                            <strong>Rp {{ number_format($bookingIncomeByPaymentMethod[$method] ?? 0, 0, ',', '.') }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="revenue-source">
                <span class="revenue-label">Dana batal belum diputuskan</span>
                <strong>Rp {{ number_format($unresolvedCancellationFunds, 0, ',', '.') }}</strong>
                <span class="revenue-caption">Belum dicatat sebagai refund atau biaya pembatalan</span>
            </div>
            <div class="revenue-source">
                <span class="revenue-label">Biaya pembatalan ditahan</span>
                <strong>Rp {{ number_format($retainedCancellationIncome, 0, ',', '.') }}</strong>
                <span class="revenue-caption">Dana yang admin catat sebagai pendapatan pembatalan</span>
            </div>
            <div class="revenue-source">
                <span class="revenue-label">Sewa perlengkapan mandiri</span>
                <strong>Rp {{ number_format($otherRentalIncome, 0, ',', '.') }}</strong>
                <span class="revenue-caption">Pesanan perlengkapan di luar booking lapangan</span>
            </div>
            <div class="revenue-source">
                <span class="revenue-label">Parkir</span>
                <strong>Rp {{ number_format($allTimeParkingIncome, 0, ',', '.') }}</strong>
                <span class="revenue-caption">Catatan pemasukan harian</span>
            </div>
            <div class="revenue-source">
                <span class="revenue-label">Toko</span>
                <strong>Rp {{ number_format($allTimeShopIncome, 0, ',', '.') }}</strong>
                <span class="revenue-caption">Catatan pemasukan harian</span>
            </div>
            <div class="revenue-source">
                <span class="revenue-label">Pemasukan lain</span>
                <strong>Rp {{ number_format($allTimeOtherIncome, 0, ',', '.') }}</strong>
                <span class="revenue-caption">Sponsor dan sumber lain</span>
            </div>
        </section>

        <section class="revenue-method-panel" aria-labelledby="revenueMethodTitle">
            <h2 id="revenueMethodTitle">Pemasukan diakui berdasarkan metode pembayaran</h2>
            <div class="revenue-method-summary">
                @foreach([
                    'cash' => 'Tunai',
                    'transfer' => 'Transfer',
                    'qris' => 'QRIS',
                    'unclassified' => 'Belum dirinci',
                ] as $method => $label)
                    <div>
                        <span>{{ $label }}</span>
                        <strong>Rp {{ number_format($incomeByPaymentMethod[$method] ?? 0, 0, ',', '.') }}</strong>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="report-panel" aria-labelledby="rentalIncomeTitle">
            <header class="report-panel-header">
                <div>
                    <h2 id="rentalIncomeTitle">Rincian sewa perlengkapan</h2>
                    <p>Rincian nilai perlengkapan dari booking dan pesanan yang dikonfirmasi. Nilai barang tidak dijumlahkan
                        lagi ke pemasukan; pembayaran yang benar-benar diterima tercatat pada sumber transaksi di atas.</p>
                </div>
            </header>
            <div class="table-responsive">
                <table class="table table-sm align-middle revenue-table">
                    <thead>
                        <tr>
                            <th scope="col">Perlengkapan</th>
                            <th scope="col" class="text-end">Jumlah disewa</th>
                            <th scope="col" class="text-end">Nilai transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rentalIncomeByItem as $itemIncome)
                            <tr>
                                <th scope="row">{{ $itemIncome->name }}</th>
                                <td class="text-end">{{ number_format((int) $itemIncome->quantity) }}</td>
                                <td class="text-end revenue-money">Rp
                                    {{ number_format((float) $itemIncome->income, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-secondary py-4">Belum ada sewa perlengkapan yang
                                    dikonfirmasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="report-panel" aria-labelledby="bookingActivityTitle">
            <header class="report-panel-header">
                <div>
                    <h2 id="bookingActivityTitle">Aktivitas transaksi per bulan</h2>
                    <p>12 bulan terakhir ({{ $chartStart->format('m/Y') }}–{{ $chartEnd->format('m/Y') }}). Grafik mengikuti
                        booking aktif dan biaya pembatalan yang dicatat ditahan.</p>
                </div>
                @if($busiestMonths)
                    <div class="revenue-peak">
                        <span>Bulan dengan transaksi terbanyak</span>
                        <strong>{{ $busiestMonths }} · {{ number_format($maximumMonthlyBookings) }} transaksi</strong>
                    </div>
                @endif
            </header>
            <div class="revenue-chart-body">
                @if($maximumMonthlyBookings > 0)
                    @php
                        $chartMonths = collect($monthlyBookingChart)->values();
                        $activeIndex = (int) $chartMonths->search(fn($month) => (int) $month['booking_count'] === (int) $maximumMonthlyBookings);
                        $activeMonth = $chartMonths[$activeIndex];
                        $chartPayload = $chartMonths->map(fn($month) => [
                            'label' => $month['label'],
                            'year' => (string) $month['year'],
                            'booking_count' => (int) $month['booking_count'],
                            'booking_value' => (float) $month['booking_value'],
                        ])->all();
                    @endphp
                    <figure class="mb-0" data-booking-chart aria-labelledby="bookingActivityTitle">
                        <figcaption id="bookingChartInstructions" class="visually-hidden">
                            Grafik jumlah transaksi terkonfirmasi per bulan. Arahkan kursor ke batang atau gunakan tombol panah
                            setelah fokus pada grafik untuk melihat rincian. Tabel lengkap tersedia di bawah.
                        </figcaption>
                        <div class="revenue-chart-frame">
                            <canvas class="revenue-chart-canvas" data-booking-chart-canvas role="img" tabindex="0"
                                aria-describedby="bookingChartInstructions"
                                aria-label="Grafik transaksi terkonfirmasi selama 12 bulan. Bulan terbanyak {{ $busiestMonths }} dengan {{ number_format($maximumMonthlyBookings) }} transaksi.">
                                Grafik transaksi terkonfirmasi selama 12 bulan.
                            </canvas>
                        </div>
                        <script type="application/json" data-booking-chart-data>@json($chartPayload)</script>
                        <div class="revenue-chart-summary" aria-live="polite" aria-atomic="true">
                            <div>
                                <span>Bulan dipilih</span>
                                <strong data-chart-readout="label">{{ $activeMonth['label'] }}
                                    {{ $activeMonth['year'] }}</strong>
                            </div>
                            <div>
                                <span>Transaksi terkonfirmasi</span>
                                <strong
                                    data-chart-readout="count">{{ number_format((int) $activeMonth['booking_count']) }}</strong>
                            </div>
                            <div>
                                <span>Pembayaran booking dan sewa</span>
                                <strong data-chart-readout="value">Rp
                                    {{ number_format($activeMonth['booking_value'], 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </figure>
                @else
                    <p class="text-secondary mb-0">Belum ada transaksi terkonfirmasi dalam 12 bulan terakhir.</p>
                @endif

                <details class="revenue-chart-details mt-3">
                    <summary>Lihat rincian transaksi tiap bulan</summary>
                    <div class="table-responsive mt-3">
                        <table class="table table-sm align-middle revenue-table">
                            <thead>
                                <tr>
                                    <th scope="col">Bulan</th>
                                    <th scope="col" class="text-end">Transaksi terkonfirmasi</th>
                                    <th scope="col" class="text-end">Pembayaran booking dan sewa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyBookingChart as $month)
                                    <tr>
                                        <th scope="row">{{ $month['label'] }} {{ $month['year'] }}</th>
                                        <td class="text-end">{{ number_format($month['booking_count']) }}</td>
                                        <td class="text-end revenue-money">Rp
                                            {{ number_format($month['booking_value'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </details>
            </div>
        </section>
    </main>

    @push('scripts')
        @vite('resources/js/admin/court-bookings-chart.js')
    @endpush
@endsection
