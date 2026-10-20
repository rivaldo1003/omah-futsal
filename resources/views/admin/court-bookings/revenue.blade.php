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
            grid-template-columns: minmax(260px, 1.35fr) repeat(4, minmax(150px, 1fr));
        }

        .revenue-total,
        .revenue-source {
            min-width: 0;
            padding: 22px 24px;
        }

        .revenue-source {
            border-left: 1px solid var(--revenue-line);
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
                <p>Ringkasan pembayaran terverifikasi dari booking, sewa perlengkapan, parkir, dan toko.</p>
            </div>
            <nav class="revenue-page-actions" aria-label="Navigasi laporan pemasukan">
                <a href="{{ route('admin.court-bookings.index') }}" class="btn">
                    <i class="bi bi-calendar-check" aria-hidden="true"></i>Booking lapangan
                </a>
                <a href="{{ route('admin.court-bookings.reports.daily') }}" class="btn">
                    <i class="bi bi-clipboard-data" aria-hidden="true"></i>Laporan operasional
                </a>
            </nav>
        </header>

        <section class="report-panel revenue-overview" aria-label="Total pemasukan tercatat">
            <div class="revenue-total">
                <span class="revenue-label">Total pemasukan tercatat</span>
                <strong>Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong>
                <span class="revenue-caption">Akumulasi seluruh data tersimpan. Refund belum dikurangkan.</span>
            </div>
            <div class="revenue-source">
                <span class="revenue-label">Pembayaran booking</span>
                <strong>Rp {{ number_format($bookingIncome, 0, ',', '.') }}</strong>
                <span class="revenue-caption">Pembayaran yang sudah diverifikasi</span>
            </div>
            <div class="revenue-source">
                <span class="revenue-label">Sewa tambahan dan mandiri</span>
                <strong>Rp {{ number_format($otherRentalIncome, 0, ',', '.') }}</strong>
                <span class="revenue-caption">Tambahan booking dan pesanan perlengkapan</span>
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
                                <td class="text-end revenue-money">Rp {{ number_format((float) $itemIncome->income, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-secondary py-4">Belum ada sewa perlengkapan yang dikonfirmasi.</td>
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
                    <p>12 bulan terakhir ({{ $chartStart->format('m/Y') }}–{{ $chartEnd->format('m/Y') }}). Grafik menampilkan
                        transaksi booking dan sewa terkonfirmasi; pemasukan parkir dan toko termasuk pada total, dicatat terpisah per hari.</p>
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
                                <strong data-chart-readout="label">{{ $activeMonth['label'] }} {{ $activeMonth['year'] }}</strong>
                            </div>
                            <div>
                                <span>Transaksi terkonfirmasi</span>
                                <strong data-chart-readout="count">{{ number_format((int) $activeMonth['booking_count']) }}</strong>
                            </div>
                            <div>
                                <span>Pembayaran booking dan sewa</span>
                                <strong data-chart-readout="value">Rp {{ number_format($activeMonth['booking_value'], 0, ',', '.') }}</strong>
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
                                        <td class="text-end revenue-money">Rp {{ number_format($month['booking_value'], 0, ',', '.') }}</td>
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
