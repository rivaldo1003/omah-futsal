<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">

    <!-- External Assets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Design Guidelines System Tokens & Layout -->
    @include('home.partials.styles')
    @include('partials.knockout-bracket-styles')
</head>

<body class="home-page">
    <!-- Top Navigation Bar -->
    @include('home.partials.navbar')

    <!-- Dynamic Hero Banner -->
    @include('home.partials.hero')

    <!-- Main Content Container -->
    <main class="app-container content-section home-content" aria-label="Informasi OFS Futsal Center">
        @if($activeTournament)
            <section class="home-section home-section--feature" aria-label="Klasemen atau bagan turnamen yang sedang berlangsung">
                @include('home.partials.standings')
            </section>
        @endif

        <section class="home-section" aria-label="Pertandingan hari ini">
            @include('home.partials.today-matches')
        </section>

        <section class="home-section home-section--columns" aria-label="Jadwal, hasil, dan statistik pertandingan">
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="home-section-stack">
                        @include('home.partials.upcoming-matches')
                        @include('home.partials.recent-results')
                    </div>
                </div>

                <aside class="col-12 col-lg-4">
                    <div class="home-section-stack">
                        @include('home.partials.top-scorers')
                        @include('home.partials.highlights')
                    </div>
                </aside>
            </div>
        </section>

        <section class="home-section home-section-stack" aria-label="Informasi tim">
            @if($teamSectionTournament)
                @include('home.partials.active-teams')
            @endif

            @include('home.partials.all-teams')
        </section>

        @if(isset($liveTournaments) && $liveTournaments->count() > 1)
            <section class="home-section" aria-label="Turnamen berjalan lainnya">
                @include('home.partials.live-tournaments')
            </section>
        @endif

        @if(!empty($recentCompletedTournament))
            <section class="home-section" aria-label="Turnamen terakhir selesai">
                @include('home.partials.completed-tournament')
            </section>
        @endif
    </main>

    <!-- Footer -->
    @include('home.partials.footer')

    <!-- Modal Dialogs (Team Details, Highlights, Hero Fullscreen) -->
    @include('home.partials.modals')

    <!-- Bootstrap Bundle & Modular Interactive Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('home.partials.scripts')
</body>

</html>
