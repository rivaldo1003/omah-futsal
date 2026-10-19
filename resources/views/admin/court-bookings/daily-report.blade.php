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

        .daily-report-overview {
            margin-bottom: 40px;
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
            grid-template-columns: repeat(2, minmax(0, 1fr)) auto;
            align-items: end;
            gap: 12px;
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

            .daily-revenue-entry {
                grid-template-columns: 1fr;
                gap: 16px;
                margin-bottom: 28px;
            }

            .daily-revenue-form {
                grid-template-columns: 1fr 1fr;
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
    @endphp

    <main class="booking-daily-report">
        <header class="daily-report-header">
            <div>
                <h1>Laporan Operasional Harian</h1>
                <p>Rekap pemasukan dan pemakaian lapangan berdasarkan tanggal sewa. Bukti pembayaran yang menunggu verifikasi belum dihitung sebagai uang diterima.</p>
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
            </div>
            <div class="daily-report-filter-actions">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-calendar2-check" aria-hidden="true"></i>Tampilkan laporan
                </button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.court-bookings.reports.daily') }}">Hari ini</a>
            </div>
        </form>

        <section class="daily-report-overview" aria-label="Ringkasan laporan {{ $reportDate }}">
            <div class="daily-report-total">
                <span class="daily-report-total-label">Total pemasukan tercatat · {{ \Carbon\CarbonImmutable::parse($reportDate)->locale('id')->translatedFormat('d F Y') }}</span>
                <strong>Rp {{ number_format($dailyTotalIncome, 0, ',', '.') }}</strong>
                <p>Termasuk pembayaran booking dan perlengkapan, serta total parkir dan toko harian yang dicatat terpisah dari booking.</p>
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
                    <span>Pembayaran diterima</span>
                    <strong>Rp {{ number_format($dailyBookingPayments, 0, ',', '.') }}</strong>
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
            </div>
        </section>

        <section class="daily-revenue-entry" aria-labelledby="dailyRevenueEntryTitle">
            <div>
                <h2 id="dailyRevenueEntryTitle">Pemasukan harian di luar booking</h2>
                <p>Catat total uang parkir dan toko untuk tanggal ini. Angka ini berdiri sendiri dan tidak dikaitkan ke pesanan lapangan.</p>
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

                <div>
                    <label class="form-label" for="dailyParkingIncome">Parkir (Rp)</label>
                    <input class="form-control" id="dailyParkingIncome" name="parking_income" type="text"
                        inputmode="numeric" autocomplete="off" data-daily-rupiah
                        value="{{ $formatRupiah(old('parking_income', $dailyParkingIncome)) }}" required>
                </div>
                <div>
                    <label class="form-label" for="dailyShopIncome">Toko (Rp)</label>
                    <input class="form-control" id="dailyShopIncome" name="shop_income" type="text"
                        inputmode="numeric" autocomplete="off" data-daily-rupiah
                        value="{{ $formatRupiah(old('shop_income', $dailyShopIncome)) }}" required>
                </div>
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-check2" aria-hidden="true"></i>Simpan pemasukan
                </button>
            </form>
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
