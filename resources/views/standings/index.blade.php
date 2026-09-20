<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>{{ isset($selectedTournament) ? $selectedTournament->name . ' - Standings' : 'Tournament Standings' }} | OFS Futsal</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">

    <!-- External Assets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Design Guidelines System Tokens & Layout (Shared with Home) -->
    @include('home.partials.styles')

    <!-- Standings Page Specific Styles -->
    @include('standings.partials.styles')
</head>

<body>
    <!-- Top Navigation Bar (Shared with Home) -->
    @include('home.partials.navbar')

    <!-- Main Content Container -->
    <main class="app-container content-section">
        <!-- Page Header: Tournament Title + Selector Dropdown -->
        @include('standings.partials.header')

        @if(isset($selectedTournament) && isset($groupedStandingsWithPosition) && $groupedStandingsWithPosition->count() > 0)
            <!-- Tournament Stats (Preserved for future activation) -->
            @include('standings.partials.stats')

            <!-- Groups / League Standings Grid -->
            <div class="row g-4">
                @foreach($groupedStandingsWithPosition as $group => $groupStandings)
                    @php
                        // Jika liga, gunakan lebar penuh (col-12). Jika grup, gunakan col-xl-6 agar 2 kolom
                        $columnClass = (isset($selectedTournament) && $selectedTournament->type === 'league') ? 'col-12' : 'col-xl-6';
                    @endphp
                    <div class="{{ $columnClass }}">
                        @include('standings.partials.group-table', [
                            'group' => $group,
                            'groupStandings' => $groupStandings,
                            'selectedTournament' => $selectedTournament
                        ])
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            @include('standings.partials.empty-state')
        @endif
    </main>

    <!-- Footer (Shared with Home) -->
    @include('home.partials.footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('standings.partials.scripts')
</body>

</html>