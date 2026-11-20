@extends('layouts.admin')

@section('title', 'Kaitkan Reservasi Turnamen')

@section('styles')
    <style>
        .tournament-reservation-page {
            --tr-ink: #111;
            --tr-muted: #707072;
            --tr-line: #e5e5e5;
            --tr-soft: #f5f5f5;
            max-width: 1120px;
            margin-inline: auto;
            color: var(--tr-ink);
            font-size: 15px;
            line-height: 1.5;
        }

        .tr-header { display:flex; justify-content:space-between; align-items:flex-start; gap:20px; margin-bottom:24px; }
        .tr-header h1 { margin:0 0 8px; font-size:clamp(1.65rem, 2.5vw, 2rem); font-weight:500; line-height:1.2; }
        .tr-header p { max-width:72ch; margin:0; color:var(--tr-muted); }
        .tr-card { overflow:hidden; margin-bottom:20px; border:1px solid var(--tr-line); border-radius:8px; background:#fff; }
        .tr-card-heading { padding:18px 22px; border-bottom:1px solid var(--tr-line); }
        .tr-card-heading h2 { margin:0 0 4px; font-size:18px; font-weight:500; }
        .tr-card-heading p { margin:0; color:var(--tr-muted); font-size:14px; }
        .tr-card-body { padding:22px; }
        .tr-context { display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:12px; }
        .tr-context-item { min-width:0; padding:14px 16px; background:var(--tr-soft); border-radius:6px; }
        .tr-context-item span { display:block; margin-bottom:4px; color:var(--tr-muted); font-size:13px; }
        .tr-context-item strong { display:block; overflow-wrap:anywhere; font-weight:600; }
        .tr-range-form { display:grid; grid-template-columns:repeat(2, minmax(180px, 1fr)) auto; align-items:end; gap:14px; }
        .tr-range-form .form-label, .tr-tournament-label { font-weight:500; }
        .tr-range-form .btn, .tr-footer .btn { min-height:44px; padding:10px 20px; border-radius:999px; }
        .tr-range-form .btn-dark, .tr-footer .btn-dark { border-color:var(--tr-ink); background:var(--tr-ink); }
        .tr-booking-list { display:grid; gap:10px; }
        .tr-booking-option { display:grid; grid-template-columns:auto minmax(0, 1fr) auto; align-items:center; gap:14px; padding:14px 16px; border:1px solid var(--tr-line); border-radius:6px; cursor:pointer; }
        .tr-booking-option:has(input:checked) { border-color:#777; background:#f8f8f8; }
        .tr-booking-option input, .tr-tournament-option input { width:18px; height:18px; accent-color:#111; }
        .tr-booking-main { min-width:0; }
        .tr-booking-main strong { display:block; font-weight:600; }
        .tr-booking-main span, .tr-booking-time { color:var(--tr-muted); font-size:14px; }
        .tr-tournament-list { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:10px; }
        .tr-tournament-option { display:flex; align-items:flex-start; gap:12px; padding:14px 16px; border:1px solid var(--tr-line); border-radius:6px; cursor:pointer; }
        .tr-tournament-option:has(input:checked) { border-color:#777; background:#f8f8f8; }
        .tr-tournament-option strong { display:block; font-weight:600; }
        .tr-tournament-option span { display:block; color:var(--tr-muted); font-size:13px; }
        .tr-empty { padding:18px; border:1px dashed #bdbdbd; border-radius:6px; color:var(--tr-muted); }
        .tr-footer { display:flex; flex-wrap:wrap; justify-content:flex-end; gap:10px; padding:18px 22px; border-top:1px solid var(--tr-line); }
        .tr-footer .btn-light { background:var(--tr-soft); }
        .tr-notice { padding:14px 16px; border-left:3px solid #333; background:var(--tr-soft); color:#454545; }
        .tr-section-title { margin:0 0 4px; font-size:16px; font-weight:600; }
        .tr-section-copy { margin:0 0 16px; color:var(--tr-muted); font-size:14px; }
        @media (max-width: 767.98px) {
            .tr-header { flex-direction:column; align-items:stretch; }
            .tr-context { grid-template-columns:repeat(2, minmax(0, 1fr)); }
            .tr-range-form { grid-template-columns:1fr; }
            .tr-range-form .btn { width:100%; }
            .tr-tournament-list { grid-template-columns:1fr; }
            .tr-booking-option { grid-template-columns:auto minmax(0, 1fr); align-items:start; }
            .tr-booking-time { grid-column:2; }
            .tr-card-body { padding:16px; }
            .tr-footer { flex-direction:column-reverse; padding:16px; }
            .tr-footer .btn { width:100%; }
        }
    </style>
@endsection

@section('content')
    @php
        $selectedBookingIds = array_map('intval', (array) $selectedBookingIds);
        $selectedTournamentIds = array_map('intval', (array) $selectedTournamentIds);
    @endphp
    <main class="tournament-reservation-page">
        <header class="tr-header">
            <div>
                <h1>Kaitkan reservasi turnamen</h1>
                <p>Pilih booking harian milik penyelenggara yang sama, lalu kaitkan ke satu atau beberapa turnamen sekaligus.</p>
            </div>
            <a href="{{ route('admin.court-bookings.index', ['date' => $seedBooking->booking_date->toDateString()]) }}" class="btn btn-light rounded-pill px-4 py-2">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke booking
            </a>
        </header>

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Periksa kembali pilihan Anda.</strong>
                <ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section class="tr-card" aria-labelledby="tr-seed-title">
            <div class="tr-card-heading">
                <h2 id="tr-seed-title">Booking awal</h2>
                <p>Gunakan booking ini untuk menemukan booking penyelenggara yang sama pada tanggal lain.</p>
            </div>
            <div class="tr-card-body">
                <div class="tr-context">
                    <div class="tr-context-item"><span>Penyelenggara / tim</span><strong>{{ $seedBooking->team_name ?: $seedBooking->customer_name }}</strong></div>
                    <div class="tr-context-item"><span>Nomor kontak</span><strong>{{ $seedBooking->customer_phone ?: 'Tidak dicatat' }}</strong></div>
                    <div class="tr-context-item"><span>Lapangan</span><strong>{{ $seedBooking->court?->name ?? 'Lapangan dihapus' }}</strong></div>
                    <div class="tr-context-item"><span>Tanggal dan jam</span><strong>{{ $seedBooking->booking_date->translatedFormat('d M Y') }} · {{ substr($seedBooking->time_start, 0, 5) }}–{{ substr($seedBooking->time_end, 0, 5) }}</strong></div>
                </div>
            </div>
        </section>

        <section class="tr-card" aria-labelledby="tr-range-title">
            <div class="tr-card-heading">
                <h2 id="tr-range-title">Pilih rentang tanggal</h2>
                <p>Daftar hanya menampilkan booking walk-in aktif di lapangan dan dengan nomor kontak penyelenggara yang sama.</p>
            </div>
            <div class="tr-card-body">
                <form class="tr-range-form" method="GET" action="{{ route('admin.court-bookings.tournament-reservations.create') }}">
                    <input type="hidden" name="booking_id" value="{{ $seedBooking->id }}">
                    <div>
                        <label class="form-label" for="reservationDateFrom">Dari tanggal</label>
                        <input class="form-control" type="date" id="reservationDateFrom" name="date_from" value="{{ $dateFrom }}" required>
                    </div>
                    <div>
                        <label class="form-label" for="reservationDateTo">Sampai tanggal</label>
                        <input class="form-control" type="date" id="reservationDateTo" name="date_to" value="{{ $dateTo }}" required>
                    </div>
                    <button class="btn btn-dark" type="submit"><i class="bi bi-search" aria-hidden="true"></i> Tampilkan booking</button>
                </form>
            </div>
        </section>

        <form method="POST" action="{{ route('admin.court-bookings.tournament-reservations.store') }}">
            @csrf
            <input type="hidden" name="seed_booking_id" value="{{ $seedBooking->id }}">
            <input type="hidden" name="date_from" value="{{ $dateFrom }}">
            <input type="hidden" name="date_to" value="{{ $dateTo }}">

            <section class="tr-card" aria-labelledby="tr-bookings-title">
                <div class="tr-card-heading">
                    <h2 id="tr-bookings-title">Booking dalam rentang ini <span class="text-secondary">· {{ $bookings->count() }}</span></h2>
                    <p>Booking yang cocok dipilih otomatis. Hapus centang jika tanggal tersebut bukan bagian dari reservasi turnamen.</p>
                </div>
                <div class="tr-card-body">
                    @if($bookings->isEmpty())
                        <div class="tr-empty">Tidak ada booking aktif yang cocok. Periksa rentang tanggal atau gunakan booking awal yang benar.</div>
                    @else
                        <div class="tr-booking-list">
                            @foreach($bookings as $booking)
                                <label class="tr-booking-option" for="reservationBooking{{ $booking->id }}">
                                    <input id="reservationBooking{{ $booking->id }}" type="checkbox" name="booking_ids[]" value="{{ $booking->id }}" @checked(in_array((int) $booking->id, $selectedBookingIds, true))>
                                    <span class="tr-booking-main">
                                        <strong>{{ $booking->booking_date->translatedFormat('l, d F Y') }} · Booking #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                        <span>{{ $booking->team_name ?: $booking->customer_name }} · {{ $booking->status_label }}</span>
                                    </span>
                                    <span class="tr-booking-time">{{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }} · {{ $booking->duration_label }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

            <section class="tr-card" aria-labelledby="tr-tournaments-title">
                <div class="tr-card-heading">
                    <h2 id="tr-tournaments-title">Pilih turnamen</h2>
                    <p>Semua turnamen yang dipilih akan dikaitkan ke setiap booking yang dicentang di atas.</p>
                </div>
                <div class="tr-card-body">
                    @if($tournaments->isEmpty())
                        <div class="tr-empty">Belum ada data turnamen yang dapat dipilih.</div>
                    @else
                        <div class="tr-tournament-list">
                            @foreach($tournaments as $tournament)
                                <label class="tr-tournament-option" for="reservationTournament{{ $tournament->id }}">
                                    <input id="reservationTournament{{ $tournament->id }}" type="checkbox" name="tournament_ids[]" value="{{ $tournament->id }}" @checked(in_array((int) $tournament->id, $selectedTournamentIds, true))>
                                    <span>
                                        <strong>{{ $tournament->name }}</strong>
                                        <span>{{ $tournament->start_date?->translatedFormat('d M Y') ?? 'Tanggal belum ditentukan' }}@if($tournament->end_date) – {{ $tournament->end_date->translatedFormat('d M Y') }}@endif</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                    <p class="tr-section-copy mt-3 mb-0">Turnamen yang tidak dicentang akan dilepas dari booking terpilih. Kaitan ini hanya mengizinkan fixture turnamen memakai jam booking tersebut; nominal dan pembayaran tiap tanggal tetap terpisah.</p>
                </div>
                <div class="tr-footer">
                    <a class="btn btn-light" href="{{ route('admin.court-bookings.index', ['date' => $seedBooking->booking_date->toDateString()]) }}">Batal</a>
                    <button class="btn btn-dark" type="submit" @disabled($bookings->isEmpty() || $tournaments->isEmpty())>
                        <i class="bi bi-link-45deg" aria-hidden="true"></i> Simpan kaitan untuk booking terpilih
                    </button>
                </div>
            </section>
        </form>
    </main>
@endsection
