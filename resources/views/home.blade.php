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
</head>

<body>
    <!-- Top Navigation Bar -->
    @include('home.partials.navbar')

    <!-- Dynamic Hero Banner -->
    @include('home.partials.hero')

    <!-- Main Content Container -->
    <main class="app-container content-section">
        <!-- Today's Active / Live Matches (Full Width) -->
        @include('home.partials.today-matches')

        <!-- Two-Column Primary Workspace Grid -->
        <div class="row g-4">
            <!-- Left Primary Column (8 Cols) -->
            <div class="col-12 col-lg-8">
                <!-- Upcoming Matches -->
                @include('home.partials.upcoming-matches')

                <!-- Recent Match Results -->
                @include('home.partials.recent-results')

                <!-- Group Standings / Knockout Information -->
                @include('home.partials.standings')
            </div>

            <!-- Right Secondary Column (4 Cols) -->
            <aside class="col-12 col-lg-4">
                <!-- Top Scorers Leaderboard -->
                @include('home.partials.top-scorers')

                <!-- Match Highlights (YouTube) -->
                @include('home.partials.highlights')
            </aside>
        </div>

        <!-- Full-Width Teams Workspace Section -->
        <section class="mt-4 pt-2">
            <!-- Active Tournament Teams (Full Width) -->
            @include('home.partials.active-teams')

            <!-- All Registered Teams (Search, Sort, Quick View - Full Width) -->
            @include('home.partials.all-teams')
        </section>
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