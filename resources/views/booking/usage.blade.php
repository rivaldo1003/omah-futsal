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
        }
    </style>
</head>

<body class="home-page">
    @include('home.partials.navbar')

    <main class="app-container content-section home-content booking-usage-page" aria-labelledby="bookingUsageTitle">
        <header class="booking-usage-heading">
            <p class="eyebrow">Booking lapangan</p>
            <h1 id="bookingUsageTitle">Pemakaian lapangan</h1>
            <p>Lihat tim dan penyewa dengan durasi pemakaian tertinggi sejak awal pencatatan.</p>
        </header>

        @if($topCourtUsers->isNotEmpty())
            @include('home.partials.top-court-users')
        @else
            <section class="booking-usage-empty" aria-live="polite">
                <i class="bi bi-calendar2-week" aria-hidden="true"></i>
                <h2>Belum ada pemakaian tercatat</h2>
                <p>Rekap akan muncul setelah booking dikonfirmasi atau sesi sewa turnamen dicatat.</p>
            </section>
        @endif
    </main>

    @include('home.partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
