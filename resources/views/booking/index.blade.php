<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Lapangan - OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @include('home.partials.styles')
    <style>
        .booking-page {
            padding-top: 38px;
            padding-bottom: 64px;
        }

        .booking-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 24px;
        }

        .booking-heading h1 {
            margin: 0 0 7px;
            font-size: clamp(26px, 3vw, 38px);
            font-weight: 800;
            letter-spacing: -.03em;
        }

        .booking-heading p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 15px;
        }

        .booking-date {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            padding: 16px;
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: var(--v3-radius-md);
            margin-bottom: 14px;
        }

        .booking-date label {
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
        }

        .booking-date input {
            width: auto;
            min-width: 190px;
            background: var(--v3-card);
            color: var(--text-main);
            border-color: var(--border-color);
        }

        .booking-date input::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        .booking-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 18px;
            color: var(--text-secondary);
            font-size: 13px;
            margin: 0 0 22px;
        }

        .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .legend-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--v3-neon-green);
        }

        .legend-dot.pending {
            background: var(--v3-neon-yellow);
        }

        .legend-dot.booked {
            background: #8ca2bf;
        }

        .legend-dot.past {
            background: #64707e;
        }

        .court-schedule {
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: var(--v3-radius-md);
            overflow: hidden;
            margin-bottom: 18px;
        }

        .court-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .court-head h2 {
            font-size: 17px;
            font-weight: 750;
            margin: 0;
        }

        .court-rate {
            color: var(--text-secondary);
            font-size: 13px;
            max-width: 62%;
            text-align: right;
            line-height: 1.4;
        }

        .slot-list {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            padding: 16px 20px 20px;
        }

        .slot-button {
            min-height: 72px;
            text-align: left;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background: var(--v3-card);
            color: var(--text-primary);
            padding: 11px 13px;
            transition: border-color 150ms ease, background-color 150ms ease;
        }

        .slot-button strong {
            display: block;
            font-size: 14px;
            font-weight: 700;
        }

        .slot-button > span {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
            color: var(--text-secondary);
            font-size: 12px;
        }

        .slot-button .slot-status-copy {
            display: inline;
            margin: 0;
            color: inherit;
            font: inherit;
        }

        .slot-button.available {
            cursor: pointer;
        }

        .slot-button.available:hover {
            border-color: var(--v3-neon-green);
            background: rgba(0, 255, 135, .07);
        }

        .slot-button.available:focus-visible {
            outline: 2px solid var(--v3-neon-green);
            outline-offset: 2px;
        }

        .slot-button.pending {
            border-color: rgba(255, 183, 0, .28);
            background: rgba(255, 183, 0, .055);
            color: #f7d77b;
            cursor: not-allowed;
        }

        .slot-button.pending > span {
            color: #f1ce73;
        }

        .slot-button.unavailable {
            border-color: #303846;
            background: #0d121a;
            color: #a3acb8;
            cursor: not-allowed;
            opacity: 1;
        }

        .slot-button.booked {
            border-color: #42536b;
            background: #111b2a;
            color: #e1eaf5;
            cursor: not-allowed;
            opacity: 1;
        }

        .slot-button.booked > span {
            color: #b4c2d5;
        }

        .slot-button.past {
            border-style: dashed;
            border-color: #39424e;
            background: #0a0e14;
            color: #a3acb8;
            cursor: not-allowed;
            opacity: 1;
        }

        .slot-button.past > span {
            color: #a3acb8;
        }

        .slot-button.selected,
        .slot-button.available[aria-pressed="true"] {
            border-color: var(--v3-neon-green);
            background: rgba(0, 255, 135, .1);
            box-shadow: inset 0 0 0 1px var(--v3-neon-green);
        }

        .booking-selection-help {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.5;
            margin: -7px 0 14px;
        }

        .booking-selection {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            padding: 15px 18px;
            margin-bottom: 20px;
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: var(--v3-radius-md);
        }

        .booking-selection[hidden] {
            display: none;
        }

        .booking-selection-copy strong {
            display: block;
            color: var(--text-primary);
            font-size: 15px;
        }

        .booking-selection-copy span {
            display: block;
            margin-top: 4px;
            color: var(--text-secondary);
            font-size: 13px;
        }

        .booking-selection-message {
            min-height: 18px;
            margin-top: 4px;
            color: var(--v3-neon-green) !important;
        }

        .booking-selection-actions {
            display: flex;
            align-items: center;
            gap: 9px;
            flex-shrink: 0;
        }

        .booking-selection-actions .btn {
            min-height: 48px;
            touch-action: manipulation;
        }

        .booking-empty {
            padding: 30px;
            border: 1px dashed var(--border-color);
            border-radius: var(--v3-radius-md);
            color: var(--text-secondary);
            text-align: center;
        }

        .booking-alert {
            background: var(--warning-subtle);
            color: #f7d77b;
            border: 1px solid rgba(255, 183, 0, .28);
            border-radius: var(--radius-md);
            padding: 13px 16px;
            margin-bottom: 18px;
        }

        .booking-resume {
            margin-bottom: 20px;
            padding: 18px 20px;
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: var(--v3-radius-md);
        }

        .booking-resume[hidden] {
            display: none;
        }

        .booking-resume h2 {
            margin: 0 0 5px;
            font-size: 17px;
            font-weight: 750;
        }

        .booking-resume-intro {
            margin: 0;
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.5;
        }

        .booking-resume-list {
            margin-top: 12px;
        }

        .booking-resume-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 13px 0;
            border-top: 1px solid var(--border-color);
        }

        .booking-resume-copy {
            min-width: 0;
        }

        .booking-resume-copy strong {
            display: block;
            color: var(--text-primary);
            font-size: 14px;
        }

        .booking-resume-copy span {
            display: block;
            margin-top: 3px;
            color: var(--text-secondary);
            font-size: 13px;
            line-height: 1.45;
        }

        .booking-resume-state-note {
            display: inline-flex !important;
            width: fit-content;
            margin-top: 7px !important;
            padding: 3px 8px;
            border: 1px solid var(--border-color);
            border-radius: 999px;
            color: var(--text-secondary) !important;
            font-size: 12px !important;
            font-weight: 650;
        }

        .booking-resume-state-note[hidden] {
            display: none !important;
        }

        .booking-resume-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-shrink: 0;
            gap: 8px;
        }

        .booking-resume-item .btn {
            min-height: 44px;
            flex-shrink: 0;
        }

        .booking-modal .modal-content {
            background: var(--v3-surface);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--v3-radius-md);
        }

        .booking-modal .modal-header,
        .booking-modal .modal-footer {
            border-color: var(--border-color);
        }

        .booking-modal .btn-close {
            filter: invert(1);
        }

        .booking-modal .form-control,
        .booking-modal .form-select {
            background: var(--v3-card);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .booking-modal .form-control:focus,
        .booking-modal .form-select:focus {
            border-color: var(--v3-neon-green);
            box-shadow: 0 0 0 3px rgba(0, 255, 135, .12);
        }

        .payment-choice {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .payment-option {
            flex: 1;
            min-width: 145px;
        }

        .payment-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .payment-option label {
            display: block;
            height: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            background: var(--v3-card);
            color: var(--text-secondary);
            cursor: pointer;
        }

        .payment-option input:checked+label {
            border-color: var(--v3-neon-green);
            color: var(--text-primary);
        }

        .booking-price {
            display: flex;
            justify-content: space-between;
            padding: 13px 0;
            margin: 14px 0;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .booking-price strong {
            color: var(--v3-neon-green);
            font-size: 18px;
        }

        .rental-choice-item {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 76px minmax(72px, auto);
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            background: var(--v3-card);
        }

        .rental-choice-copy { min-width: 0; }
        .rental-choice-copy strong,
        .rental-choice-copy span,
        .rental-choice-copy small { display: block; }
        .rental-choice-copy strong { color: var(--text-primary); font-size: 14px; }
        .rental-choice-copy span { color: var(--text-secondary); font-size: 12px; }
        .rental-choice-copy small { margin-top: 2px; color: var(--text-muted); font-size: 11px; line-height: 1.35; }
        .rental-choice-quantity .form-control { min-width: 0; padding-inline: 7px; text-align: center; }
        .rental-choice-total { color: var(--text-primary); text-align: right; font-size: 12px; font-weight: 650; font-variant-numeric: tabular-nums; }

        @media(max-width:420px) {
            .rental-choice-item { grid-template-columns: minmax(0, 1fr) 66px; }
            .rental-choice-total { grid-column: 1 / -1; text-align: left; }
        }

        .booking-rules {
            color: var(--text-secondary);
            font-size: 12px;
        }

        @media(max-width:767.98px) {
            .booking-page {
                padding-top: 25px;
            }

            .booking-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .slot-list {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                padding: 13px;
                gap: 8px;
            }

            .court-head {
                padding: 15px;
            }

            .court-rate {
                font-size: 12px;
                max-width: 58%;
            }

            .booking-date {
                padding: 13px;
            }

            .booking-date input {
                width: 100%;
            }

            .booking-selection {
                align-items: stretch;
                flex-direction: column;
                padding: 14px;
            }

            .booking-selection-actions {
                display: grid;
                grid-template-columns: 1fr 1.3fr;
            }

            .booking-resume {
                padding: 15px;
            }

            .booking-resume-item {
                align-items: stretch;
                flex-direction: column;
                gap: 10px;
            }

            .booking-resume-item .btn {
                width: 100%;
            }

            .booking-resume-actions {
                display: grid;
                grid-template-columns: 1fr auto;
            }

            .booking-resume-actions .btn {
                width: auto;
            }
        }

        @media(prefers-reduced-motion:reduce) {

            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body>
    @include('home.partials.navbar')

    <main class="app-container booking-page">
        <div class="booking-heading">
            <div>
                <h1>Booking Lapangan</h1>
                <p>Pilih slot, lalu tambahkan perlengkapan jika perlu. Perlengkapan hanya tersedia bersama booking lapangan dan tercatat dalam satu pesanan.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-3 align-items-center">
                <a href="{{ route('schedule') }}" class="nav-menu-link"><i class="bi bi-calendar2-week"></i> Jadwal pertandingan</a>
            </div>
        </div>

        @if(session('info'))
            <div class="alert alert-info" role="status">{{ session('info') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <section class="booking-resume" id="bookingResume" data-booking-base="{{ url('/booking') }}" hidden
            aria-labelledby="bookingResumeTitle" aria-live="polite">
            <h2 id="bookingResumeTitle">Riwayat booking di perangkat ini</h2>
            <p class="booking-resume-intro">Status diperbarui saat halaman dibuka. Simpan atau bagikan tautan booking
                jika ingin membukanya dari perangkat lain.</p>
            <div class="booking-resume-list" id="bookingResumeList"></div>
        </section>

        <form class="booking-date" method="GET" action="{{ route('booking.index') }}">
            <label for="bookingDate">Pilih tanggal</label>
            <input class="form-control" type="date" id="bookingDate" name="date" value="{{ $date->toDateString() }}"
                min="{{ $todayDate }}" max="{{ $maxDate }}" onchange="this.form.submit()">
            <span class="text-secondary small">Jadwal ditampilkan per lapangan dan jam.</span>
        </form>

        <div class="booking-legend" aria-label="Keterangan status slot">
            <span class="legend-item"><span class="legend-dot"></span> Tersedia</span>
            <span class="legend-item"><span class="legend-dot booked"></span> Terisi atau terjadwal</span>
            <span class="legend-item"><span class="legend-dot pending"></span> Sedang diproses</span>
            <span class="legend-item"><span class="legend-dot past"></span> Waktu sudah lewat</span>
        </div>

        @if($hasPaymentOption)
            <p class="booking-selection-help" id="bookingSelectionHelp">Untuk booking lebih dari satu slot, pilih jam
                pertama lalu pilih slot di sebelahnya secara berurutan. Semua jam akan masuk dalam satu booking.</p>
        @endif
        <section class="booking-selection" id="bookingSelection" hidden aria-live="polite"
            aria-label="Ringkasan pilihan booking">
            <div class="booking-selection-copy">
                <strong id="bookingSelectionSummary">Belum ada slot dipilih</strong>
                <span id="bookingSelectionDetails"></span>
                <span class="booking-selection-message" id="bookingSelectionMessage"></span>
            </div>
            <div class="booking-selection-actions">
                <button type="button" class="btn btn-outline-light" id="clearBookingSelection">Hapus pilihan</button>
                <button type="button" class="btn btn-primary fw-bold" id="continueBooking" disabled>Isi data
                    pemesan</button>
            </div>
        </section>

        @if(!$hasPaymentOption)
            <div class="booking-alert"><i class="bi bi-info-circle me-2"></i>Booking online belum dibuka karena instruksi
                pembayaran atau kebijakan booking belum disiapkan. Silakan hubungi admin.</div>
        @endif

        @forelse($schedules as $schedule)
            <section class="court-schedule" aria-labelledby="court-{{ $schedule['court']->id }}">
                <header class="court-head">
                    <h2 id="court-{{ $schedule['court']->id }}">{{ $schedule['court']->name }}</h2>
                    <span class="court-rate">
                        Siang Rp {{ number_format((float) $schedule['court']->day_hourly_rate, 0, ',', '.') }}/jam ·
                        Malam Rp {{ number_format((float) $schedule['court']->night_hourly_rate, 0, ',', '.') }}/jam mulai
                        {{ $schedule['court']->night_rate_starts_at_formatted }} · {{ $schedule['court']->slot_duration_minutes }} menit per slot
                    </span>
                </header>
                @if(count($schedule['slots']))
                    <div class="slot-list">
                        @foreach($schedule['slots'] as $slot)
                            @php
                                $slotDisplayStatus = $slot['status'];
                                if ($slotDisplayStatus === 'available' && !$hasPaymentOption) {
                                    $slotDisplayStatus = 'unavailable';
                                }
                                $statusLabels = [
                                    'available' => 'Tersedia',
                                    'pending' => 'Sedang diproses',
                                    'booked' => 'Sudah terisi',
                                    'past' => 'Waktu sudah lewat',
                                    'unavailable' => 'Booking online belum dibuka',
                                ];
                                $statusIcons = [
                                    'pending' => 'bi-hourglass-split',
                                    'booked' => 'bi-calendar2-check',
                                    'past' => 'bi-clock-history',
                                    'unavailable' => 'bi-info-circle',
                                ];
                            @endphp
                            @if($slot['status'] === 'available' && $hasPaymentOption)
                                <button type="button" class="slot-button available js-book-slot"
                                    data-court-id="{{ $schedule['court']->id }}" data-court-name="{{ $schedule['court']->name }}"
                                    data-date="{{ $date->toDateString() }}" data-date-label="{{ $date->translatedFormat('l, d F Y') }}"
                                    data-request-key="{{ $slot['request_key'] }}" data-starts-at="{{ $slot['starts_at'] }}"
                                    data-start="{{ $slot['start'] }}" data-end="{{ $slot['end'] }}"
                                    data-duration="{{ $slot['duration'] }}"
                                    data-amount-cents="{{ (int) round((float) $slot['amount'] * 100) }}"
                                    data-rental-availability='@json($slot['rental_availability'])'
                                    aria-describedby="bookingSelectionHelp" aria-pressed="false">
                                    <strong>{{ $slot['start'] }}–{{ $slot['end'] }}</strong>
                                    <span>Rp {{ number_format($slot['amount'], 0, ',', '.') }} · Pilih slot</span>
                                </button>
                            @else
                                <button type="button" class="slot-button {{ $slotDisplayStatus }}" disabled
                                    aria-label="{{ $slot['start'] }} sampai {{ $slot['end'] }}: {{ $statusLabels[$slotDisplayStatus] }}">
                                    <strong>{{ $slot['start'] }}–{{ $slot['end'] }}</strong>
                                    <span>
                                        <i class="bi {{ $statusIcons[$slotDisplayStatus] }}" aria-hidden="true"></i>
                                        <span class="slot-status-copy">{{ $statusLabels[$slotDisplayStatus] }}</span>
                                    </span>
                                </button>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="booking-empty">Belum ada slot yang tersedia untuk tanggal ini.</div>
                @endif
            </section>
        @empty
            <div class="booking-empty">
                <strong>Jadwal booking belum tersedia.</strong>
                <div class="mt-1">Admin sedang menyiapkan daftar lapangan. Silakan cek kembali nanti.</div>
            </div>
        @endforelse
    </main>

    @include('home.partials.footer')

    <div class="modal fade booking-modal" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('booking.store') }}">
                    @csrf
                    <div class="modal-header">
                        <div>
                            <h2 class="modal-title fs-5 fw-bold" id="bookingModalTitle">Data pemesan</h2>
                            <div class="text-secondary small" id="selectedSlotLabel">Pilih slot untuk melanjutkan</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="court_id" id="selectedCourtId">
                        <input type="hidden" name="booking_date" id="selectedBookingDate">
                        <input type="hidden" name="booking_request_key" id="selectedBookingRequestKey">
                        <input type="hidden" name="time_start" id="selectedTimeStart">
                        <input type="hidden" name="quoted_duration_minutes" id="selectedDuration">
                        <input type="hidden" name="quoted_amount" id="selectedQuotedAmount">
                        <input type="hidden" name="booking_policy_hash"
                            value="{{ hash('sha256', $settings->booking_policy) }}">
                        <div class="booking-price"><span>Total tagihan booking</span><strong
                                id="selectedAmount">Rp 0</strong></div>

                        <div class="mb-3">
                            <label for="customerName" class="form-label">Nama pemesan</label>
                            <input class="form-control" id="customerName" name="customer_name" maxlength="120"
                                autocomplete="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="teamName" class="form-label">Nama tim</label>
                            <input class="form-control" id="teamName" name="team_name" maxlength="120"
                                autocomplete="organization" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerPhone" class="form-label">Nomor WhatsApp</label>
                            <input class="form-control" id="customerPhone" name="customer_phone" type="tel"
                                maxlength="30" autocomplete="tel" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerEmail" class="form-label">Email <span
                                    class="text-secondary">(opsional)</span></label>
                            <input class="form-control" id="customerEmail" name="customer_email" type="email"
                                maxlength="255" autocomplete="email">
                        </div>

                        @if($rentalItems->isNotEmpty())
                            <fieldset class="mb-3 rental-choice" aria-describedby="rentalChoiceHelp">
                                <legend class="form-label mb-1">Tambahkan perlengkapan <span class="text-secondary">(opsional)</span></legend>
                                <p class="small text-secondary mb-2" id="rentalChoiceHelp">Perlengkapan hanya disewakan bersama booking lapangan ini. Jadwalnya mengikuti tanggal dan jam lapangan, dan biayanya masuk ke total booking.</p>
                                <div class="d-grid gap-2">
                                    @foreach($rentalItems as $item)
                                        <div class="rental-choice-item" data-rental-item data-item-id="{{ $item->id }}"
                                            data-unit-price-cents="{{ (int) round((float) $item->unit_price * 100) }}"
                                            data-stock="{{ (int) $item->stock_quantity }}">
                                            <div class="rental-choice-copy">
                                                <strong>{{ $item->name }}</strong>
                                                <span>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }} / item</span>
                                                <small data-rental-availability-copy>Stok tersedia untuk waktu ini akan ditampilkan setelah memilih jam.</small>
                                            </div>
                                            <div class="rental-choice-quantity">
                                                <label class="visually-hidden" for="rentalItem{{ $item->id }}">Jumlah {{ $item->name }}</label>
                                                <input class="form-control" id="rentalItem{{ $item->id }}" type="number"
                                                    name="rental_items[{{ $item->id }}]" min="0" max="{{ $item->stock_quantity }}"
                                                    value="0" inputmode="numeric" data-rental-quantity aria-describedby="rentalTotal{{ $item->id }}">
                                            </div>
                                            <span class="rental-choice-total" id="rentalTotal{{ $item->id }}" data-rental-line-total>Rp 0</span>
                                        </div>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endif

                        <fieldset class="mb-3">
                            <legend class="form-label">Metode pembayaran</legend>
                            <div class="payment-choice">
                                @if($settings->hasBankTransfer())
                                    <div class="payment-option">
                                        <input type="radio" name="payment_method" id="methodTransfer" value="bank_transfer"
                                            required>
                                        <label for="methodTransfer"><i class="bi bi-bank me-1"></i> Transfer bank</label>
                                    </div>
                                @endif
                                @if($settings->hasQris())
                                    <div class="payment-option">
                                        <input type="radio" name="payment_method" id="methodQris" value="qris" required>
                                        <label for="methodQris"><i class="bi bi-qr-code me-1"></i> QRIS</label>
                                    </div>
                                @endif
                            </div>
                        </fieldset>

                        <div class="booking-rules mb-2">{{ $settings->booking_policy }}</div>
                        <label class="booking-rules d-flex align-items-start gap-2">
                            <input type="checkbox" name="accept_booking_rules" value="1" required class="mt-1">
                            <span>Saya sudah membaca dan menyetujui kebijakan booking di atas. Booking dikonfirmasi
                                setelah admin memverifikasi pembayaran.</span>
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary fw-bold">Lanjut ke pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const bookingResumeStorageKey = 'ofs_booking_resume_v1';
        const bookingResumePanel = document.getElementById('bookingResume');
        const bookingResumeList = document.getElementById('bookingResumeList');
        const bookingTokenPattern = /^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;

        const readSavedBookings = () => {
            try {
                const items = JSON.parse(window.localStorage.getItem(bookingResumeStorageKey) || '[]');
                return Array.isArray(items) ? items : [];
            } catch {
                return [];
            }
        };

        const savedBookings = readSavedBookings()
            .filter((item) => item && bookingTokenPattern.test(String(item.token || '')))
            .sort((first, second) => Number(second.savedAt || 0) - Number(first.savedAt || 0))
            .slice(0, 10);

        if (savedBookings.length) {
            const bookingBase = bookingResumePanel.dataset.bookingBase.replace(/\/$/, '');
            const statusLabels = {
                awaiting_payment: 'Menunggu pembayaran',
                pending_review: 'Menunggu verifikasi',
                confirmed: 'Terkonfirmasi',
                rejected: 'Ditolak',
                cancelled: 'Dibatalkan',
                expired: 'Kedaluwarsa',
            };
            const dateFormatter = new Intl.DateTimeFormat('id-ID', {
                day: 'numeric', month: 'short', year: 'numeric',
            });
            const moneyFormatter = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });
            const formatRupiah = (amount) => `Rp ${moneyFormatter.format(Number(amount) || 0)}`;

            const bookingDetails = (booking) => {
                const parts = [booking.statusLabel || statusLabels[booking.status] || 'Status booking'];
                if (booking.courtName) parts.push(booking.courtName);
                if (booking.bookingDate) {
                    const date = new Date(`${booking.bookingDate}T00:00:00`);
                    const dateLabel = Number.isNaN(date.getTime()) ? booking.bookingDate : dateFormatter.format(date);
                    const timeLabel = booking.timeStart && booking.timeEnd ? ` · ${booking.timeStart}–${booking.timeEnd}` : '';
                    parts.push(`${dateLabel}${timeLabel}`);
                }
                if (Number.isFinite(Number(booking.amount))) parts.push(formatRupiah(booking.amount));
                return parts.join(' · ');
            };

            const rows = new Map();
            const links = new Map();
            const stateNotes = new Map();
            const removeButtons = new Map();
            const dismissedTokens = new Set();
            const stateNoteForBooking = (booking) => {
                if (booking.status === 'expired') return 'Batas pembayaran habis · slot tersedia kembali';
                if (booking.status === 'confirmed' && booking.scheduleHasPassed) return 'Jadwal telah lewat · pembayaran tetap terkonfirmasi';
                return '';
            };
            const actionForBooking = (booking) => {
                if (booking.status === 'awaiting_payment') return { label: 'Lanjutkan pembayaran', primary: true };
                if (booking.status === 'confirmed' && booking.scheduleHasPassed) return { label: 'Lihat riwayat', primary: false };
                if (booking.status === 'confirmed') return { label: 'Tampilkan booking', primary: false };
                if (booking.status === 'pending_review') return { label: 'Lihat status', primary: false };
                return { label: 'Lihat detail', primary: false };
            };

            savedBookings.forEach((booking) => {
                const row = document.createElement('div');
                row.className = 'booking-resume-item';

                const copy = document.createElement('div');
                copy.className = 'booking-resume-copy';

                const title = document.createElement('strong');
                title.textContent = `Booking #${booking.code}`;

                const detail = document.createElement('span');
                detail.textContent = bookingDetails(booking);

                const stateNote = document.createElement('span');
                stateNote.className = 'booking-resume-state-note';
                stateNote.textContent = stateNoteForBooking(booking);
                stateNote.hidden = !stateNote.textContent;

                const link = document.createElement('a');
                const action = actionForBooking(booking);
                link.className = action.primary ? 'btn btn-primary fw-bold' : 'btn btn-outline-light';
                link.href = `${bookingBase}/${encodeURIComponent(booking.token)}`;
                link.textContent = action.label;

                const actions = document.createElement('div');
                actions.className = 'booking-resume-actions';
                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'btn btn-sm btn-outline-light';
                removeButton.textContent = 'Hapus dari perangkat';
                removeButton.setAttribute('aria-label', `Hapus booking #${booking.code} dari riwayat di perangkat ini`);
                removeButton.hidden = booking.status !== 'expired';
                removeButton.addEventListener('click', () => {
                    try {
                        const remaining = readSavedBookings().filter((item) => item?.token !== booking.token);
                        window.localStorage.setItem(bookingResumeStorageKey, JSON.stringify(remaining));
                        dismissedTokens.add(booking.token);
                        row.remove();
                        if (!bookingResumeList.children.length) bookingResumePanel.hidden = true;
                    } catch {
                        removeButton.textContent = 'Gagal menghapus';
                        removeButton.disabled = true;
                    }
                });

                copy.append(title, detail, stateNote);
                actions.append(link, removeButton);
                row.append(copy, actions);
                bookingResumeList.append(row);
                rows.set(booking.token, detail);
                links.set(booking.token, link);
                stateNotes.set(booking.token, stateNote);
                removeButtons.set(booking.token, removeButton);
            });

            bookingResumePanel.hidden = false;

            Promise.all(savedBookings.map(async (booking) => {
                try {
                    const response = await fetch(`${bookingBase}/${encodeURIComponent(booking.token)}/status`, {
                        headers: { Accept: 'application/json' },
                        cache: 'no-store',
                        credentials: 'same-origin',
                    });
                    if (!response.ok) return booking;

                    const latest = await response.json();
                    const refreshedBooking = {
                        ...booking,
                        code: latest.code,
                        status: latest.status,
                        statusLabel: latest.status_label,
                        courtName: latest.court_name,
                        bookingDate: latest.booking_date,
                        timeStart: latest.time_start,
                        timeEnd: latest.time_end,
                        amount: latest.amount,
                        scheduleHasPassed: latest.schedule_has_passed,
                        expiresAt: latest.expires_at,
                    };
                    const detail = rows.get(booking.token);
                    if (detail) detail.textContent = bookingDetails(refreshedBooking);
                    const stateNote = stateNotes.get(booking.token);
                    if (stateNote) {
                        stateNote.textContent = stateNoteForBooking(refreshedBooking);
                        stateNote.hidden = !stateNote.textContent;
                    }
                    const removeButton = removeButtons.get(booking.token);
                    if (removeButton) removeButton.hidden = refreshedBooking.status !== 'expired';
                    const link = links.get(booking.token);
                    if (link) {
                        const action = actionForBooking(refreshedBooking);
                        link.textContent = action.label;
                        link.className = action.primary ? 'btn btn-primary fw-bold' : 'btn btn-outline-light';
                    }
                    return refreshedBooking;
                } catch {
                    return booking;
                }
            })).then((refreshedBookings) => {
                try {
                    window.localStorage.setItem(
                        bookingResumeStorageKey,
                        JSON.stringify(refreshedBookings.filter((booking) => !dismissedTokens.has(booking.token))),
                    );
                } catch {
                    // Saved links remain usable even when this browser blocks local storage.
                }
            });
        }

        let selectedSlots = [];
        const slotButtons = [...document.querySelectorAll('.js-book-slot')];
        const selectionPanel = document.getElementById('bookingSelection');
        const selectionSummary = document.getElementById('bookingSelectionSummary');
        const selectionDetails = document.getElementById('bookingSelectionDetails');
        const selectionMessage = document.getElementById('bookingSelectionMessage');
        const continueBookingButton = document.getElementById('continueBooking');
        const rupiahNumberFormatter = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });
        const formatRupiah = (amount) => `Rp ${rupiahNumberFormatter.format(Number(amount) || 0)}`;

        const rentalItemRows = [...document.querySelectorAll('[data-rental-item]')];
        const rentalAvailabilityForItem = (itemId) => {
            if (!selectedSlots.length) return 0;

            return Math.min(...selectedSlots.map((button) => {
                try {
                    const availability = JSON.parse(button.dataset.rentalAvailability || '{}');
                    return Number(availability[itemId] ?? 0);
                } catch {
                    return 0;
                }
            }));
        };

        const updateRentalTotals = () => {
            let rentalAmountCents = 0;

            rentalItemRows.forEach((row) => {
                const itemId = row.dataset.itemId;
                const quantityInput = row.querySelector('[data-rental-quantity]');
                const available = rentalAvailabilityForItem(itemId);
                const quantity = Math.min(Math.max(0, Number.parseInt(quantityInput.value, 10) || 0), available);
                quantityInput.max = String(available);
                quantityInput.value = String(quantity);
                row.querySelector('[data-rental-availability-copy]').textContent = `${available} unit tersedia pada jam ini`;
                const lineTotalCents = quantity * Number(row.dataset.unitPriceCents);
                rentalAmountCents += lineTotalCents;
                row.querySelector('[data-rental-line-total]').textContent = formatRupiah(lineTotalCents / 100);
            });

            const totalCents = selectedAmountCents() + rentalAmountCents;
            document.getElementById('selectedQuotedAmount').value = (totalCents / 100).toFixed(2);
            document.getElementById('selectedAmount').textContent = formatRupiah(totalCents / 100);
        };

        rentalItemRows.forEach((row) => {
            row.querySelector('[data-rental-quantity]').addEventListener('input', updateRentalTotals);
        });

        const selectedDuration = () => selectedSlots.reduce((total, button) => total + Number(button.dataset.duration), 0);
        const selectedAmountCents = () => {
            if (!selectedSlots.length) return 0;
            return selectedSlots.reduce((total, button) => total + Number(button.dataset.amountCents), 0);
        };
        const formatDuration = (minutes) => {
            const hours = Math.floor(minutes / 60);
            const remainingMinutes = minutes % 60;
            if (!hours) return `${minutes} menit`;
            return remainingMinutes ? `${hours} jam ${remainingMinutes} menit` : `${hours} jam`;
        };

        const updateSelection = (message = '') => {
            const selectedSet = new Set(selectedSlots);

            slotButtons.forEach((button) => {
                const selected = selectedSet.has(button);
                button.classList.toggle('selected', selected);
                button.setAttribute('aria-pressed', selected ? 'true' : 'false');
                const slotAmount = Number(button.dataset.amountCents) / 100;
                button.querySelector('span').textContent = selected
                    ? 'Bagian booking'
                    : `${formatRupiah(slotAmount)} · Pilih slot`;
            });

            selectionPanel.hidden = selectedSlots.length === 0;
            continueBookingButton.disabled = selectedSlots.length === 0;
            selectionMessage.textContent = message;

            if (!selectedSlots.length) return;

            const first = selectedSlots[0];
            const last = selectedSlots[selectedSlots.length - 1];
            const duration = selectedDuration();
            const amount = formatRupiah(selectedAmountCents() / 100);

            selectionSummary.textContent = `${first.dataset.courtName} · ${first.dataset.start}–${last.dataset.end}`;
            selectionDetails.textContent = `${selectedSlots.length} slot · ${formatDuration(duration)} · ${amount}`;
        };

        const selectSlot = (button) => {
            const selectedIndex = selectedSlots.indexOf(button);
            if (selectedIndex !== -1) {
                if (selectedSlots.length === 1) {
                    selectedSlots = [];
                } else if (selectedIndex === 0) {
                    selectedSlots = selectedSlots.slice(1);
                } else {
                    selectedSlots = selectedSlots.slice(0, selectedIndex);
                }
                const message = selectedSlots.length === 0
                    ? 'Pilihan booking dihapus.'
                    : selectedIndex === 0
                        ? 'Slot pertama dilepas; slot setelahnya tetap dipilih.'
                        : 'Slot ini dan jam setelahnya dilepas dari rentang.';
                updateSelection(message);
                return;
            }

            if (!selectedSlots.length) {
                selectedSlots = [button];
                updateSelection();
                return;
            }

            const first = selectedSlots[0];
            const last = selectedSlots[selectedSlots.length - 1];
            const sameSchedule = button.dataset.courtId === first.dataset.courtId
                && button.dataset.date === first.dataset.date;

            if (sameSchedule && button.dataset.start === last.dataset.end) {
                selectedSlots.push(button);
                updateSelection();
            } else if (sameSchedule && button.dataset.end === first.dataset.start) {
                selectedSlots.unshift(button);
                updateSelection();
            } else {
                selectedSlots = [button];
                updateSelection('Booking harus berupa slot berurutan pada lapangan dan tanggal yang sama. Pilihan dimulai dari slot ini.');
            }
        };

        const disableSlotsThatHaveStarted = () => {
            const now = Date.now();
            let selectionExpired = false;
            slotButtons.filter((button) => !button.disabled).forEach((button) => {
                if (Date.parse(button.dataset.startsAt) <= now) {
                    button.disabled = true;
                    button.classList.remove('available');
                    button.classList.add('past');
                    button.setAttribute('aria-label', `${button.dataset.start} sampai ${button.dataset.end}: Waktu sudah lewat`);
                    const slotStatus = button.querySelector(':scope > span');
                    if (slotStatus) {
                        const icon = document.createElement('i');
                        icon.className = 'bi bi-clock-history';
                        icon.setAttribute('aria-hidden', 'true');

                        const label = document.createElement('span');
                        label.className = 'slot-status-copy';
                        label.textContent = 'Waktu sudah lewat';

                        slotStatus.replaceChildren(icon, label);
                    }
                    selectionExpired = selectionExpired || selectedSlots.includes(button);
                }
            });

            if (selectionExpired) {
                selectedSlots = [];
                updateSelection('Slot yang dipilih sudah dimulai. Pilih rentang waktu yang lain.');
            }
        };

        slotButtons.forEach((button) => {
            button.addEventListener('click', () => selectSlot(button));
        });

        document.getElementById('clearBookingSelection').addEventListener('click', () => {
            selectedSlots = [];
            updateSelection('Pilihan booking dihapus.');
        });

        continueBookingButton.addEventListener('click', () => {
            if (!selectedSlots.length) return;

            const first = selectedSlots[0];
            const last = selectedSlots[selectedSlots.length - 1];
            const duration = selectedDuration();
            const amountCents = selectedAmountCents();
            const durationLabel = formatDuration(duration);

            document.getElementById('selectedCourtId').value = first.dataset.courtId;
            document.getElementById('selectedBookingDate').value = first.dataset.date;
            document.getElementById('selectedBookingRequestKey').value = first.dataset.requestKey;
            document.getElementById('selectedTimeStart').value = first.dataset.start;
            document.getElementById('selectedDuration').value = duration;
            document.getElementById('selectedSlotLabel').textContent = `${first.dataset.courtName} · ${first.dataset.dateLabel} · ${first.dataset.start}–${last.dataset.end} (${durationLabel})`;
            updateRentalTotals();

            bootstrap.Modal.getOrCreateInstance(document.getElementById('bookingModal')).show();
        });

        disableSlotsThatHaveStarted();
        window.setInterval(disableSlotsThatHaveStarted, 15000);
    </script>
</body>

</html>
