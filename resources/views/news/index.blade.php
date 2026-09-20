<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>News — OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    {{-- Shared design tokens & layout shell --}}
    @include('home.partials.styles')

    {{-- Page-scoped styles --}}
    @include('news.partials.styles')
</head>
<body>

    {{-- ── Unified Navigation ────────────────────────────────── --}}
    @include('home.partials.navbar')

    {{-- ── Page Header + Category Tabs ─────────────────────── --}}
    @include('news.partials.header')

    {{-- ── Main Content ─────────────────────────────────────── --}}
    <main class="app-container" style="padding-top: 24px; padding-bottom: 40px;">
        <div class="row g-4">

            {{-- Left: Featured + Article Grid --}}
            <div class="col-lg-8">
                @include('news.partials.featured')
                @include('news.partials.news-grid')
            </div>

            {{-- Right: Sidebar --}}
            <div class="col-lg-4">
                @include('news.partials.sidebar')
            </div>

        </div>
    </main>

    {{-- ── Unified Footer ────────────────────────────────────── --}}
    @include('home.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Page scripts --}}
    @include('news.partials.scripts')

</body>
</html>