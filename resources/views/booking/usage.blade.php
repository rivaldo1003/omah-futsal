<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lihat rekap pemakaian lapangan oleh tim dan penyewa OFS Futsal Center.">
    <title>Pemakaian Lapangan - OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @include('home.partials.styles')
    <style>
        .booking-usage-page {
            max-width: 1080px;
            padding-top: 42px;
            padding-bottom: 64px;
        }

        .booking-usage-heading {
            margin-bottom: 22px;
        }

        .booking-usage-heading .eyebrow {
            margin: 0 0 7px;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .booking-usage-heading h1 {
            margin: 0;
            color: var(--text-primary);
            font-size: clamp(27px, 4vw, 38px);
            font-weight: 750;
            letter-spacing: -.035em;
            line-height: 1.12;
        }

        .booking-usage-heading > p:last-child {
            max-width: 660px;
            margin: 10px 0 0;
            color: var(--text-secondary);
            font-size: 15px;
            line-height: 1.55;
        }

        .booking-usage-empty {
            padding: 34px 24px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--surface);
            text-align: center;
        }

        .booking-usage-empty i {
            display: inline-grid;
            width: 44px;
            height: 44px;
            margin-bottom: 12px;
            place-items: center;
            border-radius: 50%;
            background: var(--surface-subtle);
            color: var(--text-secondary);
            font-size: 18px;
        }

        .booking-usage-empty h2 {
            margin: 0;
            color: var(--text-primary);
            font-size: 17px;
            font-weight: 700;
        }

        .booking-usage-empty p {
            margin: 7px 0 0;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .booking-usage-filter {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
            padding: 18px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--surface);
        }

        .booking-usage-filter form {
            display: flex;
            align-items: end;
            gap: 10px;
            margin: 0;
        }

        .booking-usage-filter label {
            display: block;
            margin-bottom: 6px;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
        }

        .booking-usage-filter input[type="month"] {
            min-width: 190px;
            min-height: 44px;
            padding: 9px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: var(--surface);
            color: var(--text-primary);
            font: inherit;
        }

        .booking-usage-filter button,
        .booking-usage-filter .usage-all-link {
            display: inline-flex;
            min-height: 44px;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 15px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: var(--surface-subtle);
            color: var(--text-primary);
            font: inherit;
            font-size: 14px;
            font-weight: 650;
            text-decoration: none;
            white-space: nowrap;
        }

        .booking-usage-filter button {
            border-color: var(--text-primary);
            background: var(--text-primary);
            color: var(--surface);
        }

        .booking-usage-filter .usage-all-link[aria-current="page"] {
            background: var(--surface);
            color: var(--text-primary);
        }

        .booking-usage-period {
            margin: -5px 0 14px;
            color: var(--text-secondary);
            font-size: 13px;
        }

        .booking-usage-page .home-court-usage {
            margin-bottom: 0;
        }

        @media (max-width: 575.98px) {
            .booking-usage-page {
                padding-top: 28px;
                padding-bottom: 44px;
            }

            .booking-usage-heading {
                margin-bottom: 18px;
            }

            .booking-usage-filter,
            .booking-usage-filter form {
                align-items: stretch;
                flex-direction: column;
            }

            .booking-usage-filter {
                gap: 10px;
                padding: 14px;
            }

            .booking-usage-filter input[type="month"] {
                width: 100%;
                min-width: 0;
            }

            .booking-usage-filter button,
            .booking-usage-filter .usage-all-link {
                width: 100%;
            }
        }
    </style>
</head>

<body class="home-page">
    @include('home.partials.navbar')

    <main class="app-container content-section home-content booking-usage-page" aria-labelledby="bookingUsageTitle">
        <header class="booking-usage-heading">
            <p class="eyebrow">Booking lapangan</p>
            <h1 id="bookingUsageTitle">Pemakaian lapangan</h1>
            <p>Lihat tim dan penyewa dengan durasi pemakaian tertinggi, baik per bulan maupun sejak awal pencatatan.</p>
        </header>

        <section class="booking-usage-filter" aria-label="Filter periode pemakaian">
            <form method="GET" action="{{ route('booking.usage') }}">
                <input type="hidden" name="period" value="month">
                <div>
                    <label for="usageMonth">Pilih bulan</label>
                    <input type="month" id="usageMonth" name="month" value="{{ $usageMonth }}"
                        max="{{ now(\App\Support\BusinessTime::TIMEZONE)->format('Y-m') }}" required>
                </div>
                <button type="submit"><i class="bi bi-calendar2-check" aria-hidden="true"></i> Tampilkan bulan</button>
            </form>
            <a class="usage-all-link" href="{{ route('booking.usage', ['period' => 'all']) }}"
                @if($usagePeriod === 'all') aria-current="page" @endif>
                <i class="bi bi-arrows-fullscreen" aria-hidden="true"></i> Lihat keseluruhan
            </a>
        </section>
        <p class="booking-usage-period">Periode: {{ $usagePeriodLabel }} · maksimal 32 tim/penyewa teratas</p>

        @if($topCourtUsers->isNotEmpty())
            @include('home.partials.top-court-users')
        @else
            <section class="booking-usage-empty" aria-live="polite">
                <i class="bi bi-calendar2-week" aria-hidden="true"></i>
                <h2>{{ $usagePeriod === 'month' ? 'Belum ada pemakaian tercatat pada '.$selectedMonthLabel : 'Belum ada pemakaian tercatat' }}</h2>
                <p>Rekap mencakup booking terkonfirmasi dan sesi sewa turnamen yang dicatat.</p>
            </section>
        @endif
    </main>

    @include('home.partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
