@extends('layouts.admin')

@section('title', 'Jam Lapangan per Tim')

@push('styles')
    <style>
        .team-hours-page {
            --team-ink: #111111;
            --team-muted: #707072;
            --team-line: #e5e5e5;
            --team-soft: #f5f5f5;
            max-width: 1440px;
            margin-inline: auto;
            color: var(--team-ink);
            font-size: 15px;
            line-height: 1.5;
        }

        .team-hours-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px 24px;
            margin-bottom: 24px;
        }

        .team-hours-header h1 {
            margin: 0 0 7px;
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
        }

        .team-hours-header p {
            max-width: 72ch;
            margin: 0;
            color: var(--team-muted);
        }

        .team-hours-page .btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 17px;
            border-radius: 9999px;
            box-shadow: none;
        }

        .team-hours-page .btn-primary {
            border-color: var(--team-ink);
            background: var(--team-ink);
            color: #ffffff;
        }

        .team-hours-page .btn-primary:hover {
            border-color: #39393b;
            background: #39393b;
        }

        .team-hours-filter,
        .team-hours-summary,
        .team-hours-table {
            border: 1px solid var(--team-line);
            border-radius: 8px;
            background: #ffffff;
        }

        .team-hours-filter {
            padding: 18px 20px;
            margin-bottom: 18px;
        }

        .team-hours-year-form,
        .team-hours-custom-form {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 14px;
        }

        .team-hours-year-form .field {
            width: min(100%, 260px);
        }

        .team-hours-custom-form .field {
            width: min(100%, 220px);
        }

        .team-hours-filter label {
            display: block;
            margin-bottom: 6px;
            color: #39393b;
            font-size: 13px;
            font-weight: 500;
        }

        .team-hours-filter .form-control,
        .team-hours-filter .form-select {
            min-height: 42px;
            border-color: #cacacb;
            border-radius: 9999px;
            box-shadow: none;
        }

        .team-hours-filter .form-control:focus,
        .team-hours-filter .form-select:focus {
            border-color: var(--team-ink);
            box-shadow: 0 0 0 3px rgb(17 17 17 / 10%);
        }

        .team-hours-custom-details {
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid var(--team-line);
        }

        .team-hours-custom-details summary {
            width: fit-content;
            color: #39393b;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
        }

        .team-hours-custom-form {
            margin-top: 14px;
        }

        .team-hours-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-bottom: 20px;
        }

        .team-hours-summary > div {
            min-width: 0;
            padding: 17px 20px;
        }

        .team-hours-summary > div + div {
            border-left: 1px solid var(--team-line);
        }

        .team-hours-summary span {
            display: block;
            margin-bottom: 4px;
            color: var(--team-muted);
            font-size: 13px;
        }

        .team-hours-summary strong {
            font-size: 23px;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        .team-hours-table {
            overflow: hidden;
        }

        .team-hours-table header {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            justify-content: space-between;
            gap: 8px 16px;
            padding: 17px 20px;
            border-bottom: 1px solid var(--team-line);
        }

        .team-hours-table h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
        }

        .team-hours-table header span {
            color: var(--team-muted);
            font-size: 13px;
        }

        .team-hours-table table {
            margin: 0;
        }

        .team-hours-table th {
            color: var(--team-muted);
            font-size: 13px;
            font-weight: 500;
        }

        .team-hours-table td,
        .team-hours-table th {
            padding: 13px 20px;
            border-color: var(--team-line);
            vertical-align: middle;
        }

        .team-hours-number {
            text-align: right;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .team-hours-empty {
            padding: 34px 20px !important;
            color: var(--team-muted);
            text-align: center;
        }

        .team-hours-breakdown {
            display: grid;
            gap: 3px;
            min-width: 130px;
        }

        .team-hours-breakdown strong {
            color: var(--team-ink);
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        .team-hours-breakdown span {
            color: var(--team-muted);
            font-size: 12px;
            font-variant-numeric: tabular-nums;
        }

        .team-hours-note {
            margin: 14px 20px 18px;
            color: var(--team-muted);
            font-size: 13px;
        }

        .team-hours-section + .team-hours-section {
            margin-top: 22px;
        }

        .tournament-rental-intro {
            padding: 15px 20px;
            border-bottom: 1px solid var(--team-line);
            color: var(--team-muted);
            font-size: 13px;
        }

        .tournament-rental-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 8px;
        }

        .tournament-rental-status {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 4px 9px;
            border: 1px solid #cbded2;
            border-radius: 999px;
            background: #edf6f0;
            color: #17633d;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .tournament-rental-status.is-cancelled {
            border-color: #efc9c5;
            background: #fff1ef;
            color: #9a342b;
        }

        .tournament-rental-status.is-scheduled {
            border-color: #c9d9eb;
            background: #eff5fb;
            color: #315c88;
        }

        .tournament-rental-meta {
            display: grid;
            gap: 2px;
            min-width: 155px;
        }

        .tournament-rental-meta strong {
            color: var(--team-ink);
            font-weight: 600;
        }

        .tournament-rental-meta span {
            color: var(--team-muted);
            font-size: 12px;
        }

        .tournament-rental-modal .modal-content {
            border: 1px solid var(--team-line);
            border-radius: 10px;
        }

        .tournament-rental-modal .modal-header,
        .tournament-rental-modal .modal-footer {
            border-color: var(--team-line);
        }

        .tournament-rental-modal label {
            display: block;
            margin-bottom: 6px;
            color: #39393b;
            font-size: 13px;
            font-weight: 500;
        }

        .tournament-rental-modal .form-control,
        .tournament-rental-modal .form-select {
            min-height: 42px;
            border-color: #cacacb;
            border-radius: 7px;
            box-shadow: none;
        }

        .tournament-rental-modal .form-control:focus,
        .tournament-rental-modal .form-select:focus {
            border-color: var(--team-ink);
            box-shadow: 0 0 0 3px rgb(17 17 17 / 10%);
        }

        @media (max-width: 700px) {
            .team-hours-summary {
                grid-template-columns: 1fr;
            }

            .team-hours-summary > div + div {
                border-top: 1px solid var(--team-line);
                border-left: 0;
            }

            .team-hours-table td,
            .team-hours-table th {
                padding: 12px;
            }

            .team-hours-note {
                margin-inline: 12px;
            }

            .tournament-rental-actions {
                justify-content: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <main class="team-hours-page">
        <header class="team-hours-header">
            <div>
                <h1>Jam lapangan per tim</h1>
                <p>Rekap pemakaian booking tim dan sesi sewa turnamen dicatat terpisah. Sesi turnamen dihitung sebagai satu penyewa, termasuk waktu jeda.</p>
            </div>
            <a class="btn btn-light" href="{{ route('admin.court-bookings.reports.daily') }}">
                <i class="bi bi-clipboard-data" aria-hidden="true"></i>Laporan operasional
            </a>
        </header>

        @if($errors->any())
            <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
        @endif

        <section class="team-hours-filter" aria-label="Filter periode laporan">
            <form class="team-hours-year-form" method="GET" action="{{ route('admin.court-bookings.reports.team-hours') }}">
                <div class="field">
                    <label for="teamHoursYear">Tahun laporan</label>
                    <select class="form-select" id="teamHoursYear" name="year" required>
                        <option value="" disabled @selected($selectedYear === null)>Pilih tahun</option>
                        @foreach($yearOptions as $yearOption)
                            <option value="{{ $yearOption }}" @selected($selectedYear === (int) $yearOption)>{{ $yearOption }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" type="submit"><i class="bi bi-calendar-check" aria-hidden="true"></i>Tampilkan tahun</button>
                <a class="btn btn-light" href="{{ route('admin.court-bookings.reports.team-hours') }}">Semua riwayat</a>
            </form>

            <details class="team-hours-custom-details" @if(!$selectedYear && request()->hasAny(['from', 'to'])) open @endif>
                <summary>Gunakan rentang tanggal khusus</summary>
                <form class="team-hours-custom-form" method="GET" action="{{ route('admin.court-bookings.reports.team-hours') }}">
                    <div class="field">
                        <label for="teamHoursFrom">Dari tanggal</label>
                        <input class="form-control" type="date" id="teamHoursFrom" name="from" value="{{ $fromDate }}" max="{{ $toDate }}">
                    </div>
                    <div class="field">
                        <label for="teamHoursTo">Sampai tanggal</label>
                        <input class="form-control" type="date" id="teamHoursTo" name="to" value="{{ $toDate }}" min="{{ $fromDate }}" required>
                    </div>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-funnel" aria-hidden="true"></i>Terapkan rentang</button>
                </form>
            </details>
        </section>

        <section class="team-hours-summary" aria-label="Ringkasan jam lapangan per tim">
            <div>
                <span>Tim dengan booking terkonfirmasi</span>
                <strong>{{ number_format($teamCount) }}</strong>
            </div>
            <div>
                <span>Jumlah booking terkonfirmasi</span>
                <strong>{{ number_format($confirmedBookingCount) }}</strong>
            </div>
            <div>
                <span>Total durasi booking tim</span>
                <strong>{{ intdiv($totalTeamMinutes, 60) }} jam {{ $totalTeamMinutes % 60 }} menit</strong>
            </div>
        </section>

        <section class="team-hours-table team-hours-section" aria-labelledby="teamHoursTableTitle">
            <header>
                <h2 id="teamHoursTableTitle">Pemakaian lapangan per tim</h2>
                <span>
                    @if($selectedYear)
                        Tahun kalender {{ $selectedYear }}
                    @elseif($fromDate)
                        {{ \Carbon\CarbonImmutable::parse($fromDate)->locale('id')->translatedFormat('d M Y') }} –
                    @else
                        Sejak awal –
                    @endif
                    {{ \Carbon\CarbonImmutable::parse($toDate)->locale('id')->translatedFormat('d M Y') }}
                </span>
            </header>
            <div class="table-responsive">
                <table class="table align-middle">
                    <caption class="visually-hidden">Durasi booking terkonfirmasi untuk setiap tim.</caption>
                    <thead>
                        <tr>
                            <th scope="col">Nama tim</th>
                            <th scope="col" class="team-hours-number">Jumlah booking</th>
                            <th scope="col" class="team-hours-number">Total durasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teams as $team)
                            <tr>
                                <th scope="row" class="text-dark">{{ $team->team_name }}</th>
                                <td class="team-hours-number">
                                    <div class="team-hours-breakdown">
                                        <span>{{ number_format((int) $team->booking_count) }} booking</span>
                                    </div>
                                </td>
                                <td class="team-hours-number"><strong>{{ intdiv((int) $team->booking_minutes, 60) }} jam {{ (int) $team->booking_minutes % 60 }} menit</strong></td>
                            </tr>
                        @empty
                            <tr><td class="team-hours-empty" colspan="3">Belum ada booking tim yang terkonfirmasi pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($teams->hasPages())
                <div class="p-3">{{ $teams->links() }}</div>
            @endif
            <p class="team-hours-note">Booking member masuk ke rekap setelah pemakaian pada tanggal tersebut dicatat admin. Fixture turnamen tidak dibagikan ke tim peserta dan tidak menambah jam pada tabel ini.</p>
        </section>

        <section class="team-hours-table team-hours-section" aria-labelledby="tournamentRentalTitle">
            <header>
                <div>
                    <h2 id="tournamentRentalTitle">Sesi sewa turnamen</h2>
                    <span>Semua tanggal · {{ number_format($tournamentRentalCount) }} sesi tidak dibatalkan · {{ intdiv($tournamentRentalMinutes, 60) }} jam {{ $tournamentRentalMinutes % 60 }} menit</span>
                </div>
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#tournamentRentalModal"
                    data-mode="create" data-action="{{ route('admin.court-bookings.tournament-rentals.store') }}">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i>Catat sewa turnamen
                </button>
            </header>
            <p class="tournament-rental-intro">Daftar ini menampilkan semua tanggal, terlepas dari filter rekap tim di atas. Catat tanggal dan rentang sewa lapangan turnamen secara langsung. Seluruh rentang dihitung sebagai satu penyewa, termasuk jeda antarpertandingan. Catatan ini terpisah dari pemasukan dan rekap jam tiap tim.</p>
            <div class="table-responsive">
                <table class="table align-middle">
                    <caption class="visually-hidden">Riwayat sesi sewa turnamen yang dicatat admin.</caption>
                    <thead>
                        <tr>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Turnamen</th>
                            <th scope="col">Lapangan</th>
                            <th scope="col">Rentang sewa</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tournamentRentals as $rental)
                            @php
                                $rentalStart = substr((string) $rental->time_start, 0, 5);
                                $rentalEnd = substr((string) $rental->time_end, 0, 5);
                                $rentalDisplayStatus = $rental->display_status;
                                $rentalStatusLabel = match ($rentalDisplayStatus) {
                                    \App\Models\CourtTournamentRental::STATUS_CANCELLED => 'Dibatalkan',
                                    \App\Models\CourtTournamentRental::STATUS_COMPLETED => 'Selesai',
                                    default => 'Terjadwal',
                                };
                                $rentalStatusClass = $rentalDisplayStatus === \App\Models\CourtTournamentRental::STATUS_CANCELLED
                                    ? 'is-cancelled'
                                    : ($rentalDisplayStatus === \App\Models\CourtTournamentRental::STATUS_SCHEDULED ? 'is-scheduled' : '');
                            @endphp
                            <tr>
                                <td>{{ $rental->rental_date->locale('id')->translatedFormat('d M Y') }}</td>
                                <th scope="row" class="text-dark">{{ $rental->tournament_name_snapshot }}</th>
                                <td>{{ $rental->court?->name ?? 'Lapangan dihapus' }}</td>
                                <td>
                                    <div class="tournament-rental-meta">
                                        <strong>{{ $rentalStart }}–{{ $rentalEnd }}</strong>
                                        <span>{{ intdiv((int) $rental->duration_minutes, 60) }} jam {{ (int) $rental->duration_minutes % 60 }} menit · jeda termasuk</span>
                                    </div>
                                </td>
                                <td><span class="tournament-rental-status {{ $rentalStatusClass }}">{{ $rentalStatusLabel }}</span></td>
                                <td>
                                    <div class="tournament-rental-actions">
                                        @if($rental->status !== \App\Models\CourtTournamentRental::STATUS_CANCELLED)
                                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#tournamentRentalModal"
                                                data-mode="edit" data-action="{{ route('admin.court-bookings.tournament-rentals.update', $rental) }}"
                                                data-rental-id="{{ $rental->id }}" data-court-id="{{ $rental->court_id }}"
                                                data-tournament-id="{{ $rental->tournament_id }}" data-tournament-name="{{ $rental->tournament_name_snapshot }}"
                                                data-date="{{ $rental->rental_date->toDateString() }}" data-time-start="{{ $rentalStart }}"
                                                data-time-end="{{ $rentalEnd }}" data-note="{{ $rental->admin_note }}">
                                                <i class="bi bi-pencil" aria-hidden="true"></i>Ubah
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#cancelTournamentRentalModal"
                                                data-action="{{ route('admin.court-bookings.tournament-rentals.cancel', $rental) }}"
                                                data-rental-id="{{ $rental->id }}" data-rental-name="{{ $rental->tournament_name_snapshot }}">
                                                <i class="bi bi-x-circle" aria-hidden="true"></i>Batalkan
                                            </button>
                                        @else
                                            <span class="text-secondary small">Alasan: {{ $rental->cancellation_reason ?: 'Tidak dicantumkan' }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="team-hours-empty" colspan="6">Belum ada sesi sewa turnamen yang dicatat pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tournamentRentals->hasPages())
                <div class="p-3">{{ $tournamentRentals->links() }}</div>
            @endif
        </section>
    </main>

    @php($editingRentalId = old('rental_id'))
    <div class="modal fade tournament-rental-modal" id="tournamentRentalModal" tabindex="-1" aria-labelledby="tournamentRentalModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ $editingRentalId ? route('admin.court-bookings.tournament-rentals.update', $editingRentalId) : route('admin.court-bookings.tournament-rentals.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="tournamentRentalMethod" value="{{ $editingRentalId ? 'PUT' : '' }}" @disabled(!$editingRentalId)>
                    <input type="hidden" name="rental_id" id="tournamentRentalId" value="{{ $editingRentalId }}">
                    <div class="modal-header">
                        <div>
                            <h2 class="modal-title fs-5 fw-semibold" id="tournamentRentalModalTitle">Catat sewa turnamen</h2>
                            <p class="small text-secondary mb-0" id="tournamentRentalModalDescription">Isi rentang sewa lapangan. Waktu jeda tetap masuk dalam durasi.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        @if($errors->hasAny(['court_id', 'tournament_id', 'tournament_name_snapshot', 'rental_date', 'time_start', 'time_end', 'admin_note']))
                            <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                        @endif
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="tournamentRentalCourt">Lapangan</label>
                                <select class="form-select" id="tournamentRentalCourt" name="court_id" required>
                                    <option value="">Pilih lapangan</option>
                                    @foreach($courts as $court)
                                        <option value="{{ $court->id }}" @selected((string) old('court_id') === (string) $court->id)>{{ $court->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tournamentRentalTournament">Pilih turnamen <span class="text-secondary fw-normal">(opsional)</span></label>
                                <select class="form-select" id="tournamentRentalTournament" name="tournament_id">
                                    <option value="">Turnamen tidak terhubung</option>
                                    @foreach($tournaments as $tournament)
                                        <option value="{{ $tournament->id }}" @selected((string) old('tournament_id') === (string) $tournament->id)>{{ $tournament->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="tournamentRentalName">Nama turnamen / penyewa</label>
                                <input class="form-control" id="tournamentRentalName" name="tournament_name_snapshot" value="{{ old('tournament_name_snapshot') }}" maxlength="150" placeholder="Diisi jika tidak memilih turnamen">
                                <div class="form-text">Jika memilih turnamen di atas, nama diambil dari data turnamen. Jika tidak, isi nama secara manual.</div>
                            </div>
                            <div class="col-md-4">
                                <label for="tournamentRentalDate">Tanggal sewa</label>
                                <input class="form-control" type="date" id="tournamentRentalDate" name="rental_date" value="{{ old('rental_date') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="tournamentRentalStart">Jam mulai</label>
                                <input class="form-control" type="time" id="tournamentRentalStart" name="time_start" value="{{ old('time_start') }}" step="1800" required>
                            </div>
                            <div class="col-md-4">
                                <label for="tournamentRentalEnd">Jam selesai</label>
                                <input class="form-control" type="time" id="tournamentRentalEnd" name="time_end" value="{{ old('time_end') }}" step="1800" required>
                            </div>
                            <div class="col-12">
                                <label for="tournamentRentalNote">Catatan <span class="text-secondary fw-normal">(opsional)</span></label>
                                <textarea class="form-control" id="tournamentRentalNote" name="admin_note" rows="2" maxlength="1000" placeholder="Contoh: jeda final dan perebutan juara tiga">{{ old('admin_note') }}</textarea>
                            </div>
                        </div>
                        <p class="small text-secondary mt-3 mb-0">Durasi minimal 30 menit, mengikuti kelipatan 30 menit, dan harus berada dalam jam operasional. Sesi tidak membuat catatan pembayaran.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2" aria-hidden="true"></i>Simpan sesi sewa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade tournament-rental-modal" id="cancelTournamentRentalModal" tabindex="-1" aria-labelledby="cancelTournamentRentalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="cancelTournamentRentalForm" method="POST" action="{{ $editingRentalId ? route('admin.court-bookings.tournament-rentals.cancel', $editingRentalId) : '#' }}">
                    @csrf
                    <input type="hidden" name="rental_id" id="cancelTournamentRentalId" value="{{ old('rental_id') }}">
                    <div class="modal-header">
                        <div>
                            <h2 class="modal-title fs-5 fw-semibold" id="cancelTournamentRentalTitle">Batalkan sesi sewa</h2>
                            <p class="small text-secondary mb-0" id="cancelTournamentRentalName"></p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        @if($errors->has('cancellation_reason'))
                            <div class="alert alert-danger" role="alert">{{ $errors->first('cancellation_reason') }}</div>
                        @endif
                        <label for="tournamentRentalCancellationReason">Alasan pembatalan</label>
                        <textarea class="form-control" id="tournamentRentalCancellationReason" name="cancellation_reason" rows="3" maxlength="500" required placeholder="Tuliskan alasan pembatalan">{{ old('cancellation_reason') }}</textarea>
                        <p class="small text-secondary mt-2 mb-0">Catatan sesi tetap disimpan untuk riwayat. Slot lapangan akan tersedia kembali.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-danger">Batalkan sesi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (() => {
            const rentalModal = document.getElementById('tournamentRentalModal');
            const rentalForm = rentalModal?.querySelector('form');
            const methodInput = document.getElementById('tournamentRentalMethod');
            const idInput = document.getElementById('tournamentRentalId');
            const fields = {
                court: document.getElementById('tournamentRentalCourt'),
                tournament: document.getElementById('tournamentRentalTournament'),
                name: document.getElementById('tournamentRentalName'),
                date: document.getElementById('tournamentRentalDate'),
                start: document.getElementById('tournamentRentalStart'),
                end: document.getElementById('tournamentRentalEnd'),
                note: document.getElementById('tournamentRentalNote'),
            };
            const nameInput = fields.name;
            const tournamentSelect = fields.tournament;
            const toggleNameInput = () => {
                if (!nameInput || !tournamentSelect) return;
                nameInput.required = !tournamentSelect.value;
                nameInput.disabled = Boolean(tournamentSelect.value);
                if (tournamentSelect.value) nameInput.value = '';
            };
            tournamentSelect?.addEventListener('change', toggleNameInput);

            const populateRentalModal = (trigger) => {
                if (!trigger || !rentalForm) return;
                const editing = trigger.dataset.mode === 'edit';
                rentalForm.action = trigger.dataset.action || '';
                methodInput.value = editing ? 'PUT' : '';
                methodInput.disabled = !editing;
                idInput.value = editing ? (trigger.dataset.rentalId || '') : '';
                fields.court.value = editing ? (trigger.dataset.courtId || '') : '';
                fields.tournament.value = editing ? (trigger.dataset.tournamentId || '') : '';
                fields.name.value = editing ? (trigger.dataset.tournamentName || '') : '';
                fields.date.value = editing ? (trigger.dataset.date || '') : '';
                fields.start.value = editing ? (trigger.dataset.timeStart || '') : '';
                fields.end.value = editing ? (trigger.dataset.timeEnd || '') : '';
                fields.note.value = editing ? (trigger.dataset.note || '') : '';
                document.getElementById('tournamentRentalModalTitle').textContent = editing ? 'Ubah sesi sewa turnamen' : 'Catat sewa turnamen';
                document.getElementById('tournamentRentalModalDescription').textContent = editing
                    ? 'Perbarui rentang sewa aktual atau rencana sewa. Waktu jeda tetap dihitung.'
                    : 'Isi rentang sewa lapangan. Waktu jeda tetap masuk dalam durasi.';
                toggleNameInput();
            };
            rentalModal?.addEventListener('show.bs.modal', (event) => populateRentalModal(event.relatedTarget));

            const cancelModal = document.getElementById('cancelTournamentRentalModal');
            cancelModal?.addEventListener('show.bs.modal', (event) => {
                const trigger = event.relatedTarget;
                if (!trigger) return;
                document.getElementById('cancelTournamentRentalForm').action = trigger.dataset.action || '';
                document.getElementById('cancelTournamentRentalId').value = trigger.dataset.rentalId || '';
                document.getElementById('cancelTournamentRentalName').textContent = trigger.dataset.rentalName || '';
                document.getElementById('tournamentRentalCancellationReason').value = '';
            });

            @if($errors->hasAny(['court_id', 'tournament_id', 'tournament_name_snapshot', 'rental_date', 'time_start', 'time_end', 'admin_note']))
                if (window.bootstrap?.Modal && rentalModal) {
                    const trigger = document.querySelector(`[data-rental-id="{{ old('rental_id') }}"]`);
                    if (trigger) populateRentalModal(trigger);
                    else {
                        fields.court.value = @json(old('court_id', ''));
                        fields.tournament.value = @json(old('tournament_id', ''));
                        fields.name.value = @json(old('tournament_name_snapshot', ''));
                        fields.date.value = @json(old('rental_date', ''));
                        fields.start.value = @json(old('time_start', ''));
                        fields.end.value = @json(old('time_end', ''));
                        fields.note.value = @json(old('admin_note', ''));
                        toggleNameInput();
                    }
                    window.bootstrap.Modal.getOrCreateInstance(rentalModal).show();
                }
            @elseif($errors->has('cancellation_reason'))
                if (window.bootstrap?.Modal && cancelModal) {
                    const trigger = document.querySelector(`[data-rental-id="{{ old('rental_id') }}"][data-action*="/cancel"]`);
                    if (trigger) {
                        document.getElementById('cancelTournamentRentalForm').action = trigger.dataset.action;
                        document.getElementById('cancelTournamentRentalId').value = trigger.dataset.rentalId;
                        document.getElementById('cancelTournamentRentalName').textContent = trigger.dataset.rentalName;
                    }
                    window.bootstrap.Modal.getOrCreateInstance(cancelModal).show();
                }
            @endif
        })();
    </script>
    @endpush
@endsection
