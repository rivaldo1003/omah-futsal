<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>{{ isset($selectedTournament) && $selectedTournament ? $selectedTournament->name . ' - Match Schedule' : 'Match Schedule' }} | OFS Futsal</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">

    <!-- External Assets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Design Guidelines System Tokens & Layout (Shared across site) -->
    @include('home.partials.styles')

    <!-- Schedule Page Specific Styles -->
    @include('games.partials.styles')
</head>

<body>
    <!-- Top Navigation Bar (Shared across site) -->
    @include('home.partials.navbar')

    <!-- Main Content Container -->
    <main class="app-container content-section">
        <!-- Page Header: Breadcrumb + Title + Tournament Selector Card -->
        @include('games.partials.header')

        <!-- Tournament Info Banner -->
        @include('games.partials.tournament-info')

        <!-- Filter Toolbar -->
        @include('games.partials.filters')

        <!-- Matches & Summary Stats -->
        @if($matches->count() > 0)
            @include('games.partials.stats')
            @include('games.partials.match-list')
        @else
            @include('games.partials.empty-state')
        @endif
    </main>

    <!-- Footer (Shared across site) -->
    @include('home.partials.footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('games.partials.scripts')
</body>

</html>