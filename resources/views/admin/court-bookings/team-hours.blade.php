@extends('layouts.admin')

@section('title', 'Jam Booking per Tim')

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
        }
    </style>
@endpush

@section('content')
    <main class="team-hours-page">
        <header class="team-hours-header">
            <div>
                <h1>Jam booking per tim</h1>
                <p>Rekap durasi booking terkonfirmasi per tim. Booking member ikut dihitung setelah admin mencatat pemakaian pada tanggalnya; jadwal rutin yang belum dicatat tidak masuk.</p>
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

        <section class="team-hours-summary" aria-label="Ringkasan durasi booking">
            <div>
                <span>Tim dengan booking terkonfirmasi</span>
                <strong>{{ number_format($teamCount) }}</strong>
            </div>
            <div>
                <span>Jumlah booking terkonfirmasi</span>
                <strong>{{ number_format($confirmedBookingCount) }}</strong>
            </div>
            <div>
                <span>Total waktu lapangan terbooking</span>
                <strong>{{ intdiv($confirmedMinutes, 60) }} jam {{ $confirmedMinutes % 60 }} menit</strong>
            </div>
        </section>

        <section class="team-hours-table" aria-labelledby="teamHoursTableTitle">
            <header>
                <h2 id="teamHoursTableTitle">Rincian per tim</h2>
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
                    <caption class="visually-hidden">Jumlah booking dan total durasi terkonfirmasi untuk setiap tim.</caption>
                    <thead>
                        <tr>
                            <th scope="col">Nama tim</th>
                            <th scope="col" class="team-hours-number">Jumlah booking</th>
                            <th scope="col" class="team-hours-number">Total durasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teams as $team)
                            @php($minutes = (int) $team->total_minutes)
                            <tr>
                                <th scope="row" class="text-dark">{{ $team->team_name }}</th>
                                <td class="team-hours-number">{{ number_format((int) $team->booking_count) }}</td>
                                <td class="team-hours-number"><strong>{{ intdiv($minutes, 60) }} jam {{ $minutes % 60 }} menit</strong></td>
                            </tr>
                        @empty
                            <tr><td class="team-hours-empty" colspan="3">Belum ada booking terkonfirmasi pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($teams->hasPages())
                <div class="p-3">{{ $teams->links() }}</div>
            @endif
        </section>
    </main>
@endsection
