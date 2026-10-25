@extends('layouts.admin')

@section('title', 'Buat Booking Walk-in')

@section('styles')
    @include('components.court-booking-slot-styles')
    <style>
        .walk-in-admin-page {
            --walk-in-ink: #111111;
            --walk-in-charcoal: #39393b;
            --walk-in-muted: #707072;
            --walk-in-line: #cacacb;
            --walk-in-line-soft: #e5e5e5;
            --walk-in-cloud: #f5f5f5;
            --walk-in-success: #007d48;
            --walk-in-warning: #8a4b00;
            --walk-in-danger: #b42318;
            --walk-in-white: #ffffff;
            max-width: 1440px;
            margin-inline: auto;
            color: var(--walk-in-ink);
            font-size: 15px;
            line-height: 1.5;
        }

        .walk-in-admin-page .walk-in-page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 28px;
        }

        .walk-in-admin-page .walk-in-page-header h1 {
            margin: 0 0 8px;
            color: var(--walk-in-ink);
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
        }

        .walk-in-admin-page .walk-in-page-header p {
            max-width: 72ch;
            margin: 0;
            color: var(--walk-in-muted);
            font-size: 15px;
        }

        .walk-in-admin-page .walk-in-page-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .walk-in-admin-page .btn {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 12px 24px;
            border-radius: 9999px;
            box-shadow: none;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            transition: background-color 150ms ease-out, color 150ms ease-out, border-color 150ms ease-out;
        }

        .walk-in-admin-page .btn-primary {
            border-color: var(--walk-in-ink);
            background: var(--walk-in-ink);
            color: #ffffff;
        }

        .walk-in-admin-page .btn-primary:hover,
        .walk-in-admin-page .btn-primary:active {
            border-color: var(--walk-in-charcoal);
            background: var(--walk-in-charcoal);
            color: #ffffff;
        }

        .walk-in-admin-page .btn-outline-primary,
        .walk-in-admin-page .btn-outline-secondary {
            border-color: transparent;
            background: var(--walk-in-cloud);
            color: var(--walk-in-ink);
        }

        .walk-in-admin-page .btn-outline-primary:hover,
        .walk-in-admin-page .btn-outline-secondary:hover {
            border-color: transparent;
            background: #e5e5e5;
            color: var(--walk-in-ink);
        }

        .walk-in-admin-page .btn:focus-visible,
        .walk-in-admin-page a:focus-visible,
        .walk-in-admin-page summary:focus-visible {
            outline: 2px solid var(--walk-in-ink);
            outline-offset: 3px;
        }

        .walk-in-admin-page .card {
            overflow: hidden;
            border: 1px solid var(--walk-in-line-soft);
            border-radius: 8px;
            background: var(--walk-in-white);
            box-shadow: none;
        }

        .walk-in-admin-page .card-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--walk-in-line-soft);
            background: var(--walk-in-white);
        }

        .walk-in-admin-page .card-body {
            padding: 20px;
        }

        .walk-in-admin-page .card-header h2,
        .walk-in-admin-page .card-header h5 {
            margin: 0;
            color: var(--walk-in-ink);
            font-size: 18px;
            font-weight: 500;
            line-height: 1.25;
        }

        .walk-in-admin-page .form-label {
            margin-bottom: 6px;
            color: var(--walk-in-charcoal);
            font-size: 14px;
            font-weight: 500;
        }

        .walk-in-admin-page .form-control,
        .walk-in-admin-page .form-select {
            min-height: 44px;
            border-color: var(--walk-in-line);
            border-radius: 8px;
            background-color: var(--walk-in-white);
            color: var(--walk-in-ink);
            font-size: 16px;
            box-shadow: none;
        }

        .walk-in-admin-page textarea.form-control {
            min-height: 88px;
            padding: 12px 16px;
            border-radius: 8px;
        }

        .walk-in-admin-page .form-control:focus,
        .walk-in-admin-page .form-select:focus {
            border: 2px solid var(--walk-in-ink);
            box-shadow: none;
            outline: none;
        }

        .walk-in-admin-page .form-check-input {
            border-color: var(--walk-in-line);
            box-shadow: none;
        }

        .walk-in-admin-page .form-check-input:checked {
            border-color: var(--walk-in-ink);
            background-color: var(--walk-in-ink);
        }

        .walk-in-admin-page .form-text,
        .walk-in-admin-page .text-secondary {
            color: var(--walk-in-muted) !important;
        }

        .walk-in-filter-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: start;
            gap: 20px 24px;
        }

        .walk-in-filter-field {
            min-width: 0;
        }

        .walk-in-filter-field .form-text {
            margin-top: 7px;
            font-size: 13px;
            line-height: 1.4;
        }

        .walk-in-filter-footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px 20px;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--walk-in-line-soft);
        }

        .walk-in-filter-footer p {
            margin: 0;
            color: var(--walk-in-muted);
            font-size: 13px;
            line-height: 1.45;
        }

        @media (max-width: 767.98px) {
            .walk-in-filter-fields {
                grid-template-columns: minmax(0, 1fr);
                gap: 16px;
            }

            .walk-in-filter-footer {
                align-items: stretch;
                flex-direction: column;
                gap: 14px;
            }

            .walk-in-filter-footer .btn {
                width: 100%;
            }
        }

        .walk-in-admin-page .alert {
            padding: 14px 18px;
            margin-bottom: 16px;
            border: 1px solid var(--walk-in-line-soft);
            border-radius: 8px;
            font-size: 14px;
        }

        .walk-in-admin-page .alert-info,
        .walk-in-admin-page .alert-light {
            background: var(--walk-in-cloud);
            color: var(--walk-in-charcoal);
        }

        .walk-in-admin-page .alert-warning {
            border-color: #f0dfbd;
            background: #fff6e7;
            color: var(--walk-in-warning);
        }

        .walk-in-admin-page .alert-danger {
            border-color: #f0d2cf;
            background: #fdf1f0;
            color: var(--walk-in-danger);
        }

        .walk-in-schedule-heading {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 8px 24px;
        }

        .walk-in-schedule-meta {
            max-width: 72ch;
            color: var(--walk-in-muted);
            font-size: 14px;
            text-align: right;
            font-variant-numeric: tabular-nums;
            line-height: 1.7;
        }

        .walk-in-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 20px;
            margin: 0 0 16px;
            color: var(--walk-in-charcoal);
            font-size: 14px;
        }

        .walk-in-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .walk-in-legend-mark {
            width: 10px;
            height: 10px;
            flex: 0 0 auto;
            border-radius: 50%;
            background: var(--walk-in-success);
        }

        .walk-in-legend-mark.pending {
            background: var(--walk-in-warning);
        }

        .walk-in-legend-mark.booked {
            background: #52606b;
        }

        .walk-in-legend-mark.match {
            background: #3478c4;
        }

        .walk-in-legend-mark.tournament-rental {
            background: #7059a8;
        }

        .walk-in-legend-mark.member {
            background: #7762b5;
        }

        .walk-in-legend-mark.past {
            background: #9e9ea0;
        }

        .walk-in-legend-mark.instant {
            border: 2px solid var(--walk-in-success);
            background: var(--walk-in-white);
        }

        .walk-in-detail-modal .modal-content {
            overflow: hidden;
            border: 1px solid var(--walk-in-line);
            border-radius: 12px;
            background: var(--walk-in-white);
            box-shadow: 0 18px 50px rgb(17 17 17 / 16%);
        }

        .walk-in-detail-modal .modal-header,
        .walk-in-detail-modal .modal-footer {
            border-color: var(--walk-in-line-soft);
            padding: 18px 22px;
        }

        .walk-in-detail-modal .modal-body {
            padding: 22px;
        }

        .walk-in-detail-summary {
            padding: 16px;
            border-radius: 8px;
            background: var(--walk-in-cloud);
        }

        .walk-in-detail-summary-label,
        .walk-in-detail-facts dt {
            margin: 0 0 5px;
            color: var(--walk-in-muted);
            font-size: 12px;
            font-weight: 500;
        }

        .walk-in-detail-summary strong {
            display: block;
            color: var(--walk-in-ink);
            font-size: 19px;
            font-weight: 600;
            line-height: 1.35;
        }

        .walk-in-detail-status {
            display: inline-flex;
            margin-top: 14px;
            padding: 5px 9px;
            border: 1px solid var(--walk-in-line);
            border-radius: 999px;
            background: var(--walk-in-cloud);
            color: var(--walk-in-charcoal);
            font-size: 12px;
            font-weight: 600;
        }

        .walk-in-detail-status--pending {
            border-color: #f0dfbd;
            background: #fff6e7;
            color: var(--walk-in-warning);
        }

        .walk-in-detail-status--booked {
            border-color: var(--walk-in-line-soft);
            background: var(--walk-in-cloud);
            color: var(--walk-in-charcoal);
        }

        .walk-in-detail-status--member {
            border-color: #c9bee9;
            background: #f0edfa;
            color: #493b73;
        }

        .walk-in-detail-status--match {
            border-color: #b6d4f2;
            background: #dcecff;
            color: #164f84;
        }

        .walk-in-detail-status--tournament-rental {
            border-color: #d6caec;
            background: #e9e1f7;
            color: #4c3b79;
        }

        .walk-in-detail-note {
            margin: 12px 0 0;
            color: var(--walk-in-muted);
            font-size: 13px;
        }

        .walk-in-detail-facts {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px 24px;
            margin: 20px 0 0;
        }

        .walk-in-detail-facts>div[hidden] {
            display: none;
        }

        .walk-in-detail-facts dd {
            margin: 0;
            color: var(--walk-in-ink);
            font-size: 14px;
            font-weight: 500;
        }

        .walk-in-selection {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px 24px;
            margin-top: 20px;
            padding: 16px 0 0;
            border-top: 1px solid var(--walk-in-line-soft);
        }

        .walk-in-selection-summary {
            color: var(--walk-in-muted);
        }

        .walk-in-selection-summary strong {
            display: block;
            color: var(--walk-in-ink);
            font-size: 18px;
            font-weight: 500;
        }

        .walk-in-booking-context {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 8px 24px;
            padding: 16px 20px;
            margin-bottom: 24px;
            border: 1px solid var(--walk-in-line-soft);
            border-radius: 8px;
            background: var(--walk-in-cloud);
        }

        .walk-in-booking-context strong,
        .walk-in-total strong {
            color: var(--walk-in-ink);
            font-weight: 500;
        }

        .walk-in-booking-context-total {
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }

        .walk-in-admin-page .table {
            color: var(--walk-in-ink);
            --bs-table-bg: transparent;
        }

        .walk-in-admin-page .table thead th {
            padding: 12px 16px;
            border-bottom: 1px solid var(--walk-in-line);
            color: var(--walk-in-muted);
            font-size: 12px;
            letter-spacing: .025em;
            font-weight: 500;
            white-space: nowrap;
        }

        .walk-in-admin-page .table tbody th,
        .walk-in-admin-page .table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--walk-in-line-soft);
            vertical-align: middle;
        }

        .walk-in-admin-page .table tbody tr:last-child th,
        .walk-in-admin-page .table tbody tr:last-child td {
            border-bottom: 0;
        }

        .walk-in-admin-page .table .form-control {
            min-height: 40px;
            font-size: 16px;
        }

        .walk-in-total {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px 24px;
            padding: 20px;
            border: 1px solid var(--walk-in-line-soft);
            border-radius: 8px;
            background: var(--walk-in-cloud);
        }

        .walk-in-total h2 {
            margin: 0 0 4px;
            color: var(--walk-in-ink);
            font-size: 20px;
            font-weight: 500;
        }

        .walk-in-total strong {
            font-size: 28px;
            font-variant-numeric: tabular-nums;
        }

        .walk-in-note-section {
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid var(--walk-in-line-soft);
            border-radius: 8px;
            background: var(--walk-in-white);
        }

        .walk-in-form-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 8px;
        }

        @media (max-width: 767.98px) {
            .walk-in-admin-page .walk-in-page-header {
                gap: 16px;
                margin-bottom: 24px;
            }

            .walk-in-admin-page .walk-in-page-header h1 {
                font-size: 28px;
            }

            .walk-in-page-actions,
            .walk-in-page-actions .btn {
                width: 100%;
            }

            .walk-in-page-actions .btn {
                justify-content: center;
            }

            .walk-in-admin-page .card-body {
                padding: 16px;
            }

            .walk-in-schedule-meta {
                text-align: left;
            }

            .walk-in-detail-modal .modal-header,
            .walk-in-detail-modal .modal-footer,
            .walk-in-detail-modal .modal-body {
                padding: 16px;
            }

            .walk-in-selection,
            .walk-in-selection>.btn {
                width: 100%;
            }

            .walk-in-booking-context {
                align-items: flex-start;
                flex-direction: column;
            }

            .walk-in-total strong {
                font-size: 24px;
            }

            .walk-in-form-actions,
            .walk-in-form-actions .btn {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .walk-in-admin-page .btn {
                transition: none;
            }
        }
    </style>
@endsection

@section('content')
<main class="walk-in-admin-page">
    <header class="walk-in-page-header">
        <div>
            <h1>Buat booking walk-in</h1>
            <p>Catat booking pelanggan di lokasi. Perlengkapan opsional mengikuti jadwal lapangan dan masuk ke transaksi
                yang sama.</p>
        </div>
        <nav class="walk-in-page-actions" aria-label="Aksi booking manual">
            <a href="{{ route('admin.court-bookings.index') }}" class="btn btn-outline-secondary"><i
                    class="bi bi-arrow-left" aria-hidden="true"></i>Kembali ke booking</a>
        </nav>
    </header>

    @if($errors->any())
        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif

    <form method="GET" action="{{ route('admin.court-bookings.manual.create') }}" class="card mb-4"
        id="walkInScheduleFilter">
        <div class="card-header">
            <h2>1. Pilih tanggal dan lapangan</h2>
        </div>
        <div class="card-body">
            <input type="hidden" name="manual_request_key" value="{{ $requestKey }}">
            <div class="walk-in-filter-fields">
                <div class="walk-in-filter-field">
                    <label class="form-label" for="walkInDate">Tanggal booking</label>
                    <input class="form-control" type="date" id="walkInDate" name="date" value="{{ $date }}"
                        min="{{ $minimumDate }}" max="{{ $maxDate }}" required>
                    <div class="form-text">Admin dapat mencatat tanggal hingga 30 hari ke belakang.</div>
                </div>
                <div class="walk-in-filter-field">
                    <label class="form-label" for="walkInCourt">Lapangan</label>
                    <select class="form-select" id="walkInCourt" name="court_id" required>
                        <option value="">Pilih lapangan</option>
                        @foreach($courts as $court)
                            <option value="{{ $court->id }}" @selected((int) $selectedCourtId === (int) $court->id)>
                                {{ $court->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="walk-in-filter-footer">
                <p>Jadwal yang tersedia akan ditampilkan setelah tanggal dan lapangan dipilih.</p>
                <button class="btn {{ $selectedRange ? 'btn-outline-secondary' : 'btn-primary' }}" type="submit"><i
                        class="bi bi-arrow-repeat" aria-hidden="true"></i>Tampilkan jadwal</button>
            </div>
        </div>
    </form>

    @if($selectedCourt && $courtSlots->isNotEmpty())
    <form method="GET" action="{{ route('admin.court-bookings.manual.create') }}"
        class="card walk-in-schedule-card mb-4" id="walkInSlotForm">
        <div class="card-header walk-in-schedule-heading">
            <h2>2. Pilih slot lapangan</h2>
            <div class="walk-in-schedule-meta">
                {{ $selectedCourt->name }} · {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                · 30 menit per slot
                @if(\Carbon\CarbonImmutable::parse($date)->isWeekend())
                    · Siang akhir pekan Rp
                    {{ number_format((float) $selectedCourt->weekend_day_hourly_rate, 0, ',', '.') }}/jam
                    · Malam akhir pekan Rp
                    {{ number_format((float) $selectedCourt->weekend_night_hourly_rate, 0, ',', '.') }}/jam
                @else
                    · Siang Rp {{ number_format((float) $selectedCourt->day_hourly_rate, 0, ',', '.') }}/jam
                    · Malam Rp {{ number_format((float) $selectedCourt->night_hourly_rate, 0, ',', '.') }}/jam
                @endif
            </div>
        </div>
        <div class="card-body">
            <input type="hidden" name="date" value="{{ $date }}">
            <input type="hidden" name="court_id" value="{{ $selectedCourt->id }}">
            <input type="hidden" name="manual_request_key" value="{{ $requestKey }}">
            <input type="hidden" name="range" id="walkInRange" value="{{ $selectedRange['value'] ?? '' }}">

            <div class="walk-in-legend" aria-label="Keterangan status slot">
                <span class="walk-in-legend-item"><span class="walk-in-legend-mark"></span>Tersedia</span>
                <span class="walk-in-legend-item"><span class="walk-in-legend-mark pending"></span>Sedang
                    diproses</span>
                <span class="walk-in-legend-item"><span class="walk-in-legend-mark booked"></span>Booking terisi</span>
                <span class="walk-in-legend-item"><span class="walk-in-legend-mark match"></span>Jadwal
                    pertandingan</span>
                <span class="walk-in-legend-item"><span class="walk-in-legend-mark tournament-rental"></span>Sewa turnamen</span>
                <span class="walk-in-legend-item"><span class="walk-in-legend-mark member"></span>Jadwal member</span>
                <span class="walk-in-legend-item"><span class="walk-in-legend-mark past"></span>Waktu lewat · tetap bisa dipilih</span>
                <span class="walk-in-legend-item"><span class="walk-in-legend-mark instant"></span>Mulai sekarang ·
                    tarif penuh satu slot</span>
            </div>

            <p class="small text-secondary mb-3">Pilih satu slot atau beberapa slot berurutan. Slot kosong yang waktunya
                sudah lewat tetap dapat dicatat untuk administrasi; booking aktif, jadwal member, pertandingan, dan sesi sewa
                turnamen tetap mengunci slot. Untuk slot yang sedang berjalan, admin juga dapat memilih mulai sekarang jika masih tersisa
                sedikitnya 30 menit.</p>
            <div class="court-slot-grid" role="group" aria-label="Jadwal slot {{ $selectedCourt->name }}">
                @php
                    $slotStatusLabels = [
                        'available' => 'Tersedia',
                        'past_available' => 'Waktu lewat, tetap bisa dipilih',
                        'pending' => 'Sedang diproses',
                        'booked' => 'Sudah terisi',
                        'match' => 'Jadwal pertandingan',
                        'tournament_rental' => 'Sewa turnamen',
                        'member' => 'Jadwal member',
                        'past' => 'Waktu sudah lewat',
                    ];
                    $slotIcons = [
                        'available' => 'bi-plus-circle',
                        'pending' => 'bi-hourglass-split',
                        'booked' => 'bi-calendar2-check',
                        'match' => 'bi-calendar-event',
                        'tournament_rental' => 'bi-trophy',
                        'member' => 'bi-person-lock',
                        'past' => 'bi-clock-history',
                    ];
                @endphp
                @foreach($courtSlots as $slot)
                @php
                    $instantRange = $slot['instant_range'] ?? null;
                    $minutesForTime = fn($time) => ((int) substr($time, 0, 2) * 60) + (int) substr($time, 3, 2);
                    $selectedStartMinutes = $selectedRange ? $minutesForTime($selectedRange['start']) : null;
                    $selectedEndMinutes = $selectedRange ? $minutesForTime($selectedRange['end']) : null;
                    if ($selectedRange && $selectedEndMinutes <= $selectedStartMinutes) {
                        $selectedEndMinutes += 1440;
                    }
                    $slotStartMinutes = $minutesForTime($instantRange['start'] ?? $slot['start']);
                    $slotEndMinutes = $minutesForTime($slot['end']);
                    if ($slotEndMinutes <= $slotStartMinutes) {
                        $slotEndMinutes += 1440;
                    }
                    $isSelectedSlot = $selectedRange && ($instantRange
                        ? $selectedRange['value'] === $instantRange['value']
                        : (!($selectedRange['is_instant'] ?? false) && $slotStartMinutes >= $selectedStartMinutes && $slotEndMinutes <= $selectedEndMinutes));
                    $isSelectableSlot = in_array($slot['status'], ['available', 'past_available'], true) || $instantRange;
                    $slotAppearance = $isSelectableSlot ? 'available' : $slot['status'];
                    $slotExtraClasses = 'walk-in-slot';
                    if ($slot['status'] === 'past_available') $slotExtraClasses .= ' is-past-available';
                    if ($instantRange) $slotExtraClasses .= ' is-instant';
                    if ($isSelectedSlot) $slotExtraClasses .= ' is-selected';
                @endphp
                @if($isSelectableSlot)
                    <x-court-booking-slot :status="$slotAppearance" :class="$slotExtraClasses"
                        :time-label="$instantRange ? 'Mulai sekarang · sampai '.$slot['end'] : $slot['start'].'–'.$slot['end']"
                        data-start="{{ $instantRange['start'] ?? $slot['start'] }}" data-end="{{ $slot['end'] }}"
                        data-starts-at="{{ $instantRange['starts_at'] ?? $slot['starts_at'] }}"
                        data-time-passed="{{ $slot['status'] === 'past_available' ? 'true' : 'false' }}"
                        data-amount="{{ $instantRange['amount'] ?? $slot['amount'] }}"
                        data-range-value="{{ $instantRange['value'] ?? ($slot['start'] . '|' . $slot['end']) }}"
                        data-instant="{{ $instantRange ? 'true' : 'false' }}"
                        data-slot-minutes="{{ $instantRange['slot_minutes'] ?? '' }}"
                        data-ends-at="{{ $instantRange['ends_at'] ?? '' }}"
                        data-last-start-at="{{ $instantRange['last_start_at'] ?? '' }}"
                        aria-pressed="{{ $isSelectedSlot ? 'true' : 'false' }}">
                        {{ $slot['status'] === 'past_available' ? 'Waktu lewat · ' : '' }}Rp
                            {{ number_format($instantRange['amount'] ?? $slot['amount'], 0, ',', '.') }} ·
                            {{ $instantRange ? 'Tarif penuh ' . $instantRange['slot_minutes'] . ' menit' : ($isSelectedSlot ? 'Dipilih' : 'Pilih slot') }}
                    </x-court-booking-slot>
                @elseif($slot['status'] === 'match' && filled($slot['match_details']))
                    <x-court-booking-slot status="match" class="walk-in-slot slot-button--inspect js-walk-in-slot-detail"
                        :time-label="$slot['start'].'–'.$slot['end']" :disabled="false"
                        data-detail-kind="Pertandingan"
                        data-detail-name="{{ $slot['match_details']['home_team'] }} vs {{ $slot['match_details']['away_team'] }}"
                        data-detail-competition="{{ $slot['match_details']['competition_name'] }}"
                        data-detail-status="match" data-detail-court="{{ $selectedCourt->name }}"
                        data-detail-date="{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}"
                        data-detail-time="{{ $slot['start'] }}–{{ $slot['end'] }}" aria-haspopup="dialog"
                        aria-controls="walkInSlotDetailModal"
                        aria-label="Lihat detail pertandingan {{ $slot['match_details']['home_team'] }} melawan {{ $slot['match_details']['away_team'] }}, {{ $slot['start'] }} sampai {{ $slot['end'] }}">
                        <i class="bi bi-calendar-event" aria-hidden="true"></i>
                        <span class="slot-status-copy slot-status-badge--match">Jadwal pertandingan</span>
                        <i class="bi bi-chevron-right slot-inspect-icon" aria-hidden="true"></i>
                    </x-court-booking-slot>
                @elseif($slot['status'] === 'tournament_rental' && filled($slot['tournament_rental_details']))
                    <x-court-booking-slot status="tournament_rental" class="walk-in-slot slot-button--inspect js-walk-in-slot-detail"
                        :time-label="$slot['start'].'–'.$slot['end']" :disabled="false"
                        data-detail-kind="Sewa turnamen"
                        data-detail-name="{{ $slot['tournament_rental_details']['tournament_name'] }}"
                        data-detail-rental-start="{{ $slot['tournament_rental_details']['rental_start'] }}"
                        data-detail-rental-end="{{ $slot['tournament_rental_details']['rental_end'] }}"
                        data-detail-status="tournament_rental" data-detail-court="{{ $selectedCourt->name }}"
                        data-detail-date="{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}"
                        data-detail-time="{{ $slot['start'] }}–{{ $slot['end'] }}" aria-haspopup="dialog"
                        aria-controls="walkInSlotDetailModal"
                        aria-label="Lihat detail sewa turnamen {{ $slot['tournament_rental_details']['tournament_name'] }}, {{ $slot['start'] }} sampai {{ $slot['end'] }}">
                        <i class="bi bi-trophy" aria-hidden="true"></i>
                        <span class="slot-status-copy slot-status-badge--tournament-rental">Sewa turnamen</span>
                        <i class="bi bi-chevron-right slot-inspect-icon" aria-hidden="true"></i>
                    </x-court-booking-slot>
                @elseif(in_array($slot['status'], ['pending', 'booked', 'member'], true) && filled($slot['display_name']))
                @php($slotNamePrefix = $slot['status'] === 'member' ? 'Member: ' : 'Tim: ')
                    <x-court-booking-slot :status="$slot['status']" class="walk-in-slot slot-button--inspect js-walk-in-slot-detail"
                        :time-label="$slot['start'].'–'.$slot['end']" :disabled="false"
                        data-detail-kind="{{ $slot['status'] === 'member' ? 'Member' : 'Tim' }}"
                        data-detail-name="{{ trim($slot['display_name']) }}" data-detail-status="{{ $slot['status'] }}"
                        data-detail-court="{{ $selectedCourt->name }}"
                        data-detail-date="{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}"
                        data-detail-time="{{ $slot['start'] }}–{{ $slot['end'] }}" aria-haspopup="dialog"
                        aria-controls="walkInSlotDetailModal"
                        aria-label="Lihat detail {{ $slotNamePrefix . trim($slot['display_name']) }}, {{ $slot['start'] }} sampai {{ $slot['end'] }}">
                        <span class="slot-status-copy slot-occupant-copy" title="{{ $slotNamePrefix.trim($slot['display_name']) }}">
                            {{ $slotNamePrefix.trim($slot['display_name']) }}
                        </span>
                        <i class="bi bi-chevron-right slot-inspect-icon" aria-hidden="true"></i>
                    </x-court-booking-slot>
                @else
                    <x-court-booking-slot :status="$slot['status']" :time-label="$slot['start'].'–'.$slot['end']"
                        :disabled="true"
                        aria-label="{{ $slot['start'] }} sampai {{ $slot['end'] }}: {{ $slotStatusLabels[$slot['status']] }}">
                        <i class="bi {{ $slotIcons[$slot['status']] }}" aria-hidden="true"></i>
                        <span class="slot-status-copy">{{ $slotStatusLabels[$slot['status']] }}</span>
                    </x-court-booking-slot>
                @endif
                @endforeach
            </div>

            <div class="walk-in-selection" aria-live="polite">
                <div class="walk-in-selection-summary">
                    <strong
                        id="walkInSelectionSummary">{{ $selectedRange ? (($selectedRange['is_instant'] ?? false) ? 'Mulai sekarang · sampai ' . $selectedRange['end'] : $selectedRange['start'] . '–' . $selectedRange['end']) : 'Belum ada slot dipilih' }}</strong>
                    <span id="walkInSelectionDetails"
                        class="small text-secondary">{{ $selectedRange ? $selectedRange['duration_label'] . (($selectedRange['is_instant'] ?? false) ? ' aktual · tarif penuh ' . $selectedRange['slot_minutes'] . ' menit · ' : ' · ') . 'Rp ' . number_format($selectedRange['amount'], 0, ',', '.') : 'Pilih slot yang tersedia untuk melanjutkan.' }}</span>
                </div>
                <button class="btn btn-outline-secondary" type="submit" id="walkInContinue" @disabled(!$selectedRange)>
                    <i class="bi bi-arrow-right me-1" aria-hidden="true"></i>Lanjut ke data pelanggan
                </button>
            </div>
            <p class="small text-secondary mt-2 mb-0" id="walkInSelectionMessage" role="status"></p>
        </div>
    </form>
    @endif

    @if($selectedCourt && $courtSlots->isNotEmpty())
        <div class="modal fade walk-in-detail-modal" id="walkInSlotDetailModal" tabindex="-1"
            aria-labelledby="walkInSlotDetailTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5 fw-semibold" id="walkInSlotDetailTitle">Detail jadwal</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="walk-in-detail-summary">
                            <p class="walk-in-detail-summary-label" id="walkInSlotDetailKind"></p>
                            <strong id="walkInSlotDetailName"></strong>
                        </div>
                        <span class="walk-in-detail-status" id="walkInSlotDetailStatus"></span>
                        <p class="walk-in-detail-note" id="walkInSlotDetailNote"></p>
                        <dl class="walk-in-detail-facts">
                            <div id="walkInSlotDetailCompetitionFact" hidden>
                                <dt>Turnamen / kompetisi</dt>
                                <dd id="walkInSlotDetailCompetition"></dd>
                            </div>
                            <div id="walkInSlotDetailRentalFact" hidden>
                                <dt>Rentang sewa turnamen</dt>
                                <dd id="walkInSlotDetailRentalPeriod"></dd>
                            </div>
                            <div>
                                <dt>Lapangan</dt>
                                <dd id="walkInSlotDetailCourt"></dd>
                            </div>
                            <div>
                                <dt>Tanggal</dt>
                                <dd id="walkInSlotDetailDate"></dd>
                            </div>
                            <div>
                                <dt>Jam</dt>
                                <dd id="walkInSlotDetailTime"></dd>
                            </div>
                        </dl>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($courts->isEmpty())
        <div class="alert alert-info">Belum ada lapangan aktif. Aktifkan atau tambahkan lapangan dari pengaturan booking.
        </div>
    @elseif(!$selectedCourt)
        <div class="alert alert-warning">Lapangan yang dipilih tidak aktif. Pilih lapangan aktif dari daftar.</div>
    @elseif($availableRanges->isEmpty())
        <div class="alert alert-info">Tidak ada slot tersedia untuk lapangan dan tanggal ini. Pilih tanggal atau lapangan
            lain.</div>
    @elseif($rangeWasRequested && !$selectedRange)
        <div class="alert alert-warning">Slot yang dipilih tidak tersedia lagi. Pilih ulang jadwal dari daftar.</div>
    @endif

    @if($selectedRange)
    <div id="walkInBookingDetails">
        <form method="POST" action="{{ route('admin.court-bookings.manual.store') }}" id="walkInBookingForm">
            @csrf
            <input type="hidden" name="manual_request_key" value="{{ old('manual_request_key', $requestKey) }}">
            <input type="hidden" name="court_id" value="{{ $selectedCourt->id }}">
            <input type="hidden" name="booking_date" value="{{ $date }}">
            <input type="hidden" name="range" value="{{ $selectedRange['value'] }}">

            <div class="walk-in-booking-context">
                <div><strong>{{ $selectedCourt->name }}</strong> ·
                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }} ·
                    @if($selectedRange['is_instant'] ?? false)
                        Mulai sekarang sampai {{ $selectedRange['end'] }} · {{ $selectedRange['duration_label'] }} aktual ·
                        tarif penuh {{ $selectedRange['slot_minutes'] }} menit
                    @else
                        {{ $selectedRange['start'] }}–{{ $selectedRange['end'] }} ({{ $selectedRange['duration_label'] }})
                    @endif
                </div>
                <span class="walk-in-booking-context-total">Sewa lapangan: <strong>Rp
                        {{ number_format($selectedRange['amount'], 0, ',', '.') }}</strong></span>
            </div>

            <section class="card mb-4" aria-labelledby="walkInCustomerTitle">
                <div class="card-header">
                    <h2 id="walkInCustomerTitle">3. Data pelanggan dan pembayaran</h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($date < $todayDate)
                            <div class="col-12">
                                <div class="alert alert-warning mb-0" role="note">
                                    <strong>Pencatatan booking tanggal lampau.</strong>
                                    Alasan wajib dicatat. Jadwal tetap diperiksa agar tidak berbenturan dengan booking, pertandingan, atau jadwal member.
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="walkInBackdateReason">Alasan pencatatan terlambat <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="walkInBackdateReason" name="backdate_reason" rows="2" maxlength="500"
                                    placeholder="Contoh: Booking walk-in kemarin baru dilaporkan oleh petugas hari ini." required>{{ old('backdate_reason') }}</textarea>
                                @if($errors->has('backdate_reason'))
                                    <div class="text-danger small mt-1">{{ $errors->first('backdate_reason') }}</div>
                                @endif
                            </div>
                        @endif
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="walkInName">Nama pelanggan</label>
                            <input class="form-control" id="walkInName" name="customer_name" maxlength="120"
                                autocomplete="name" value="{{ old('customer_name') }}" required>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="walkInTeam">Nama tim</label>
                            <input class="form-control" id="walkInTeam" name="team_name" maxlength="120"
                                autocomplete="organization" value="{{ old('team_name') }}" required>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="walkInPhone">Nomor WhatsApp</label>
                            <input class="form-control" id="walkInPhone" name="customer_phone" maxlength="30"
                                autocomplete="tel" inputmode="tel" value="{{ old('customer_phone') }}" required>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="walkInEmail">Email <span
                                    class="text-secondary">(opsional)</span></label>
                            <input class="form-control" id="walkInEmail" type="email" name="customer_email"
                                maxlength="255" autocomplete="email" value="{{ old('customer_email') }}">
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label" for="walkInPayment">Metode pembayaran</label>
                            <select class="form-select" id="walkInPayment" name="payment_method" required>
                                <option value="none" @selected(old('payment_method', 'none') === 'none')>Belum ada pembayaran</option>
                                <option value="cash" @selected(old('payment_method') === 'cash')>Tunai</option>
                                <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>
                                    Transfer bank</option>
                                <option value="qris" @selected(old('payment_method') === 'qris')>QRIS</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-7">
                            <label class="form-label" for="walkInAmountReceivedDisplay">Nominal yang diterima sekarang
                                <span class="text-secondary">(opsional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input class="form-control" id="walkInAmountReceivedDisplay" type="text"
                                    inputmode="numeric" autocomplete="off" spellcheck="false" maxlength="16"
                                    value="{{ old('amount_received') !== null ? number_format((float) old('amount_received'), 0, ',', '.') : '' }}"
                                    placeholder="Kosongkan jika belum menerima pembayaran" data-rupiah-input
                                    aria-describedby="walkInAmountReceivedHelp">
                                <input type="hidden" id="walkInAmountReceived" name="amount_received"
                                    value="{{ old('amount_received', '') }}" data-rupiah-value>
                            </div>
                            <div class="form-text" id="walkInAmountReceivedHelp">
                                Kosongkan atau isi 0 jika booking dikonfirmasi tanpa DP. Jika ada pembayaran, masukkan nominal yang benar-benar diterima.
                            </div>
                            <div class="small mt-1" aria-live="polite">
                                Sisa tagihan: <strong id="walkInBalance">Rp {{ number_format($selectedRange['amount'], 0, ',', '.') }}</strong>
                            </div>
                            <div class="text-danger small mt-1" id="walkInAmountReceivedError" role="alert"
                                @if(!$errors->has('amount_received')) hidden @endif>
                                {{ $errors->first('amount_received') }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="walkInReference">Nomor referensi pembayaran <span
                                    class="text-secondary">(opsional)</span></label>
                            <input class="form-control" id="walkInReference" name="payment_reference" maxlength="100"
                                value="{{ old('payment_reference') }}">
                        </div>
                    </div>
                    <div class="form-text mt-3">Booking walk-in langsung terkonfirmasi. Pembayaran awal boleh dikosongkan;
                        sisa tagihan tetap tercatat dan dapat diperbarui dari daftar booking.</div>
                </div>
            </section>

            @if($rentalItems->isNotEmpty())
            <section class="card mb-4" aria-labelledby="walkInRentalTitle">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h2 id="walkInRentalTitle">Perlengkapan <span class="text-secondary fw-normal">(opsional)</span>
                        </h2>
                        <div class="small text-secondary mt-1">Stok diperiksa untuk jam booking yang dipilih.</div>
                    </div>
                    <span class="small text-secondary">Harga ditambahkan ke transaksi yang sama</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Perlengkapan</th>
                                <th>Harga per item</th>
                                <th>Stok tersedia</th>
                                <th style="width:140px">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentalItems as $item)
                            @php($stock = (int) ($availableStock[$item->id] ?? 0))
                            <tr>
                                <th scope="row" class="fw-normal">{{ $item->name }}</th>
                                <td>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                                <td>{{ $stock }} unit</td>
                                <td><label class="visually-hidden" for="walkInRental{{ $item->id }}">Jumlah
                                        {{ $item->name }}</label><input
                                        class="form-control form-control-sm walk-in-rental-quantity"
                                        id="walkInRental{{ $item->id }}" type="number"
                                        name="rental_items[{{ $item->id }}]" min="0" max="{{ $stock }}"
                                        value="{{ old('rental_items.' . $item->id, 0) }}"
                                        data-price="{{ (float) $item->unit_price }}" @disabled($stock < 1)></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            @endif

            <section class="walk-in-total mb-4" aria-labelledby="walkInSummaryTitle">
                <div>
                    <h2 id="walkInSummaryTitle">Total tagihan</h2><span class="small text-secondary">Lapangan dan
                        perlengkapan yang dipilih</span>
                </div>
                <strong id="walkInTotal" data-court-amount="{{ $selectedRange['amount'] }}">Rp
                    {{ number_format($selectedRange['amount'], 0, ',', '.') }}</strong>
            </section>

            <div class="walk-in-note-section">
                <label class="form-label" for="walkInNote">Catatan admin <span
                        class="text-secondary">(opsional)</span></label>
                <textarea class="form-control" id="walkInNote" name="admin_note" rows="2"
                    maxlength="1000">{{ old('admin_note') }}</textarea>
            </div>

            <div class="walk-in-form-actions">
                <a class="btn btn-outline-secondary" href="{{ route('admin.court-bookings.index') }}">Batal</a>
                <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle"
                        aria-hidden="true"></i>Simpan booking</button>
            </div>
        </form>
    </div>
    @endif
</main>
@endsection

@section('scripts')
    <script>
        (() => {
            const slotDetailModal = document.getElementById('walkInSlotDetailModal');
            const slotDetailContent = {
                pending: {
                    label: 'Sedang diproses',
                    className: 'walk-in-detail-status--pending',
                    note: 'Pemesanan ini masih dalam proses pembayaran atau verifikasi.',
                },
                booked: {
                    label: 'Sudah terisi',
                    className: 'walk-in-detail-status--booked',
                    note: 'Slot ini sudah dipesan untuk tim yang ditampilkan.',
                },
                member: {
                    label: 'Jadwal member',
                    className: 'walk-in-detail-status--member',
                    note: 'Slot ini digunakan sesuai jadwal rutin member.',
                },
                match: {
                    label: 'Jadwal pertandingan',
                    className: 'walk-in-detail-status--match',
                    note: 'Lapangan digunakan untuk pertandingan ini dan tidak tersedia untuk booking umum.',
                },
                tournament_rental: {
                    label: 'Sewa turnamen',
                    className: 'walk-in-detail-status--tournament-rental',
                    note: 'Rentang sewa ini dicatat untuk satu penyewa turnamen. Waktu jeda termasuk dalam durasi sewa.',
                },
            };

            document.querySelectorAll('.js-walk-in-slot-detail').forEach((button) => {
                button.addEventListener('click', () => {
                    const detail = slotDetailContent[button.dataset.detailStatus];
                    if (!detail) return;

                    document.getElementById('walkInSlotDetailKind').textContent = button.dataset.detailKind || '';
                    document.getElementById('walkInSlotDetailName').textContent = button.dataset.detailName || '';
                    document.getElementById('walkInSlotDetailCourt').textContent = button.dataset.detailCourt || '';
                    document.getElementById('walkInSlotDetailDate').textContent = button.dataset.detailDate || '';
                    document.getElementById('walkInSlotDetailTime').textContent = button.dataset.detailTime || '';
                    document.getElementById('walkInSlotDetailNote').textContent = detail.note;

                    const competitionFact = document.getElementById('walkInSlotDetailCompetitionFact');
                    competitionFact.hidden = button.dataset.detailStatus !== 'match';
                    document.getElementById('walkInSlotDetailCompetition').textContent = button.dataset.detailCompetition || '';
                    const rentalFact = document.getElementById('walkInSlotDetailRentalFact');
                    rentalFact.hidden = button.dataset.detailStatus !== 'tournament_rental';
                    document.getElementById('walkInSlotDetailRentalPeriod').textContent = button.dataset.detailRentalStart && button.dataset.detailRentalEnd
                        ? `${button.dataset.detailRentalStart}–${button.dataset.detailRentalEnd} · termasuk jeda`
                        : '';

                    const status = document.getElementById('walkInSlotDetailStatus');
                    status.className = `walk-in-detail-status ${detail.className}`;
                    status.textContent = detail.label;

                    if (slotDetailModal && window.bootstrap?.Modal) {
                        window.bootstrap.Modal.getOrCreateInstance(slotDetailModal).show();
                    }
                });
            });

            const scheduleForm = document.getElementById('walkInSlotForm');
            if (!scheduleForm) return;

            const slots = [...scheduleForm.querySelectorAll('.walk-in-slot.available')];
            const rangeInput = document.getElementById('walkInRange');
            const continueButton = document.getElementById('walkInContinue');
            const summary = document.getElementById('walkInSelectionSummary');
            const details = document.getElementById('walkInSelectionDetails');
            const message = document.getElementById('walkInSelectionMessage');
            const bookingDetails = document.getElementById('walkInBookingDetails');
            const currentRange = rangeInput.value;
            const formatter = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });
            const amountLabel = (amount) => `Rp ${formatter.format(Number(amount) || 0)}`;
            const timeToMinutes = (value) => {
                const [hours, minutes] = value.split(':').map(Number);
                return hours * 60 + minutes;
            };
            const slotDurationMinutes = (start, end) => {
                const difference = timeToMinutes(end) - timeToMinutes(start);
                return difference > 0 ? difference : difference + (24 * 60);
            };
            const rangeParts = currentRange.split('|');
            const rangeStartMinutes = rangeParts[0] ? timeToMinutes(rangeParts[0]) : null;
            let rangeEndMinutes = rangeParts[1] ? timeToMinutes(rangeParts[1]) : null;
            if (rangeStartMinutes !== null && rangeEndMinutes !== null && rangeEndMinutes <= rangeStartMinutes) {
                rangeEndMinutes += 24 * 60;
            }
            let selectedSlots = currentRange.startsWith('now|')
                ? slots.filter((slot) => slot.dataset.rangeValue === currentRange)
                : (rangeParts.length === 2 && rangeParts[0] && rangeParts[1]
                    ? slots.filter((slot) => {
                        if (slot.dataset.instant === 'true') return false;
                        const start = timeToMinutes(slot.dataset.start);
                        const end = start + slotDurationMinutes(slot.dataset.start, slot.dataset.end);
                        return start >= rangeStartMinutes && end <= rangeEndMinutes;
                    })
                    : []);
            const durationLabel = (minutes) => {
                const hours = Math.floor(minutes / 60);
                const remaining = minutes % 60;
                if (!hours) return `${minutes} menit`;
                return remaining ? `${hours} jam ${remaining} menit` : `${hours} jam`;
            };

            const instantExpired = (slot) => slot?.dataset.instant === 'true'
                && Date.parse(slot.dataset.lastStartAt) < Date.now();

            const updateSelection = (notice = '') => {
                const selected = new Set(selectedSlots);
                let amount = 0;
                let duration = 0;

                slots.forEach((slot) => {
                    const isSelected = selected.has(slot);
                    slot.classList.toggle('is-selected', isSelected);
                    slot.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
                    const slotAmount = Number(slot.dataset.amount || 0);
                    slot.querySelector('.walk-in-slot-copy').textContent = slot.dataset.instant === 'true'
                        ? `${amountLabel(slotAmount)} · ${isSelected ? 'Dipilih' : `Tarif penuh ${slot.dataset.slotMinutes} menit`}`
                        : (slot.dataset.timePassed === 'true'
                            ? `Waktu lewat · ${amountLabel(slotAmount)} · ${isSelected ? 'Dipilih' : 'Pilih slot'}`
                            : (isSelected ? `${amountLabel(slotAmount)} · Dipilih` : `${amountLabel(slotAmount)} · Pilih slot`));

                    if (isSelected) {
                        amount += slotAmount;
                        duration += slotDurationMinutes(slot.dataset.start, slot.dataset.end);
                    }
                });

                const first = selectedSlots[0];
                const last = selectedSlots[selectedSlots.length - 1];
                const instant = selectedSlots.length === 1 && first?.dataset.instant === 'true';
                const instantIsExpired = instant && instantExpired(first);
                rangeInput.value = instant
                    ? first.dataset.rangeValue
                    : (first && last ? `${first.dataset.start}|${last.dataset.end}` : '');
                continueButton.disabled = selectedSlots.length === 0 || instantIsExpired;
                summary.textContent = instant
                    ? (instantIsExpired ? 'Batas mulai sekarang sudah lewat' : `Mulai sekarang · sampai ${first.dataset.end}`)
                    : (first && last ? `${first.dataset.start}–${last.dataset.end}` : 'Belum ada slot dipilih');
                if (instant && !instantIsExpired) {
                    const endsAt = Date.parse(first.dataset.endsAt);
                    const remaining = Math.max(0, Math.floor((endsAt - Date.now()) / 60000));
                    details.textContent = `Sisa ${durationLabel(remaining)} · tarif penuh ${first.dataset.slotMinutes} menit · ${amountLabel(amount)}`;
                } else {
                    details.textContent = instantIsExpired
                        ? 'Muat ulang jadwal untuk melihat slot yang masih dapat dipesan.'
                        : (first && last
                            ? `${selectedSlots.length} slot · ${durationLabel(duration)} · ${amountLabel(amount)}`
                            : 'Pilih slot yang tersedia untuk melanjutkan.');
                }
                message.textContent = notice || (instantIsExpired ? 'Batas pemesanan slot ini telah lewat.' : '');

                if (bookingDetails) {
                    bookingDetails.hidden = rangeInput.value !== currentRange;
                    const saveButton = bookingDetails.querySelector('button[type="submit"]');
                    if (saveButton && instant) saveButton.disabled = instantIsExpired;
                }
            };

            const selectSlot = (slot) => {
                if (slot.dataset.instant === 'true') {
                    selectedSlots = selectedSlots.includes(slot) ? [] : [slot];
                    updateSelection(selectedSlots.length ? 'Tarif mulai-sekarang dihitung penuh untuk satu slot.' : 'Pilihan booking dihapus.');
                    return;
                }

                if (selectedSlots.some((selectedSlot) => selectedSlot.dataset.instant === 'true')) {
                    selectedSlots = [];
                }

                const selectedIndex = selectedSlots.indexOf(slot);
                if (selectedIndex !== -1) {
                    if (selectedSlots.length === 1) {
                        selectedSlots = [];
                    } else if (selectedIndex === 0) {
                        selectedSlots = selectedSlots.slice(1);
                    } else {
                        selectedSlots = selectedSlots.slice(0, selectedIndex);
                    }
                    updateSelection(selectedSlots.length ? 'Rentang slot diperbarui.' : 'Pilihan booking dihapus.');
                    return;
                }

                if (!selectedSlots.length) {
                    selectedSlots = [slot];
                    updateSelection();
                    return;
                }

                const first = selectedSlots[0];
                const last = selectedSlots[selectedSlots.length - 1];
                if (slot.dataset.start === last.dataset.end) {
                    selectedSlots.push(slot);
                    updateSelection();
                } else if (slot.dataset.end === first.dataset.start) {
                    selectedSlots.unshift(slot);
                    updateSelection();
                } else {
                    selectedSlots = [slot];
                    updateSelection('Rentang harus berurutan. Pilihan dimulai dari slot ini.');
                }
            };

            slots.forEach((slot) => slot.addEventListener('click', () => selectSlot(slot)));
            updateSelection();

            const updateElapsedSlotStates = () => {
                const now = Date.now();
                let selectionChanged = false;
                slots.forEach((slot) => {
                    if (slot.dataset.instant === 'true' && !slot.disabled && Date.parse(slot.dataset.lastStartAt) < now) {
                        slot.disabled = true;
                        slot.classList.remove('available', 'is-selected');
                        slot.classList.add('is-past');
                        slot.setAttribute('aria-pressed', 'false');
                        slot.querySelector('.walk-in-slot-copy').textContent = 'Batas pemesanan lewat';
                        selectionChanged ||= selectedSlots.includes(slot);
                        selectedSlots = selectedSlots.filter((selectedSlot) => selectedSlot !== slot);
                    } else if (slot.dataset.instant !== 'true' && Date.parse(slot.dataset.startsAt) <= now && slot.dataset.timePassed !== 'true') {
                        slot.dataset.timePassed = 'true';
                        slot.classList.add('is-past-available');
                        slot.setAttribute('aria-label', `${slot.dataset.start} sampai ${slot.dataset.end}: Waktu lewat, tetap bisa dipilih`);
                    }
                });
                if (selectionChanged) updateSelection('Batas memilih mulai-sekarang telah lewat. Slot penuh yang waktunya lewat tetap dapat dicatat.');
                else updateSelection();
            };

            updateElapsedSlotStates();
            window.setInterval(updateElapsedSlotStates, 30000);
        })();

        (() => {
            const form = document.getElementById('walkInBookingForm');
            const total = document.getElementById('walkInTotal');
            const amountDisplay = document.getElementById('walkInAmountReceivedDisplay');
            const amountValue = document.getElementById('walkInAmountReceived');
            const balance = document.getElementById('walkInBalance');
            const amountError = document.getElementById('walkInAmountReceivedError');
            const paymentMethod = document.getElementById('walkInPayment');
            if (!form || !total || !amountDisplay || !amountValue || !balance || !amountError) return;

            const money = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });
            const formatThousands = (digits) => digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            let totalAmount = Number(total.dataset.courtAmount || 0);

            const updateBalance = () => {
                const received = Number(amountValue.value || 0);
                balance.classList.toggle('text-danger', received > totalAmount);
                balance.textContent = !received
                    ? `Rp ${money.format(totalAmount)}`
                    : received > totalAmount
                        ? `Melebihi total tagihan Rp ${money.format(totalAmount)}`
                        : `Rp ${money.format(Math.max(0, totalAmount - received))}`;
            };

            const syncReceivedAmount = () => {
                const digits = amountDisplay.value.replace(/\D/g, '').replace(/^0+(?=\d)/, '');
                amountDisplay.value = formatThousands(digits);
                amountValue.value = digits;
                updateBalance();
            };

            const updateTotal = () => {
                const rentalTotal = [...form.querySelectorAll('.walk-in-rental-quantity')].reduce((sum, input) => {
                    const maximum = Number(input.max || 0);
                    const quantity = Math.min(maximum, Math.max(0, Number.parseInt(input.value || '0', 10) || 0));
                    input.value = String(quantity);
                    return sum + quantity * Number(input.dataset.price || 0);
                }, 0);
                totalAmount = Number(total.dataset.courtAmount || 0) + rentalTotal;
                total.textContent = `Rp ${money.format(totalAmount)}`;
                updateBalance();
            };

            amountDisplay.addEventListener('input', () => {
                amountDisplay.setCustomValidity('');
                amountError.hidden = true;
                syncReceivedAmount();
            });
            amountDisplay.addEventListener('blur', syncReceivedAmount);
            form.addEventListener('submit', (event) => {
                syncReceivedAmount();
                const received = Number(amountValue.value || 0);
                const error = received > totalAmount
                        ? `Nominal tidak boleh melebihi total tagihan Rp ${money.format(totalAmount)}.`
                        : (received > 0 && paymentMethod?.value === 'none'
                            ? 'Pilih metode pembayaran jika ada nominal yang diterima.'
                            : '');

                amountDisplay.setCustomValidity(error);
                amountError.textContent = error;
                amountError.hidden = !error;

                if (error) {
                    event.preventDefault();
                    amountDisplay.reportValidity();
                }
            });
            form.querySelectorAll('.walk-in-rental-quantity').forEach((input) => input.addEventListener('input', updateTotal));
            syncReceivedAmount();
            updateTotal();
        })();
    </script>
@endsection
