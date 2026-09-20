<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>{{ Str::limit($article->title, 60) }} — OFS News</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    {{-- Shared design tokens & layout shell --}}
    @include('home.partials.styles')

    {{-- Index-level news styles (sidebar widgets re-used here) --}}
    @include('news.partials.styles')

    {{-- Show-page specific styles --}}
    @include('news.partials.show-styles')
</head>
<body>

    {{-- ── Unified Navigation ────────────────────────────────── --}}
    @include('home.partials.navbar')

    {{-- ── Breadcrumb ────────────────────────────────────────── --}}
    @include('news.partials.show-breadcrumb')

    {{-- ── Main Content ─────────────────────────────────────── --}}
    <main class="app-container" style="padding-top: 24px; padding-bottom: 48px;">
        <div class="row g-4">

            {{-- Left: Article content + related --}}
            <div class="col-lg-8">
                @include('news.partials.show-article')
            </div>

            {{-- Right: Sidebar --}}
            <div class="col-lg-4">
                @include('news.partials.show-sidebar')
            </div>

        </div>
    </main>

    {{-- ── FAB Group ────────────────────────────────────────── --}}
    <div class="article-fab-group">
        <a href="#" id="fabScrollTop" class="article-fab article-fab-scroll" aria-label="Scroll to top">
            <i class="bi bi-chevron-up"></i>
        </a>
        <a href="javascript:window.print()" class="article-fab article-fab-print" aria-label="Print article">
            <i class="bi bi-printer"></i>
        </a>
        <a href="#" id="fabCopyLink" class="article-fab article-fab-copy" aria-label="Copy link">
            <i class="bi bi-link-45deg"></i>
        </a>
    </div>

    {{-- ── Unified Footer ────────────────────────────────────── --}}
    @include('home.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Page scripts --}}
    @include('news.partials.show-scripts')

</body>
</html>