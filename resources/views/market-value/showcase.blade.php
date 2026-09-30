<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Market Value — OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @include('home.partials.styles')

    <style>
        .mv-showcase-page {
            padding-top: var(--space-6);
            padding-bottom: var(--space-6);
        }

        .mv-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0 0 20px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .mv-breadcrumb a { color: var(--text-secondary); text-decoration: none; }
        .mv-breadcrumb a:hover { color: var(--accent); }
        .mv-breadcrumb .active { color: var(--text-primary); font-weight: 600; }
    </style>
</head>

<body>
    @include('home.partials.navbar')

    <main class="app-container mv-showcase-page">
        <nav aria-label="breadcrumb">
            <ol class="mv-breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('market-value.index') }}">Market Value</a></li>
                <li class="active" aria-current="page">Ultra Analytics</li>
            </ol>
        </nav>

        {{-- Ultra Analytics showcase (moved from home) --}}
        @include('home.partials.market-value-stars')
    </main>

    @include('home.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('home.partials.scripts')
</body>

</html>