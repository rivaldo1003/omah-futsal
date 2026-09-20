{{-- ============================================================
     news/partials/show-breadcrumb.blade.php
     Breadcrumb bar for article detail page
     Variable: $article
     ============================================================ --}}
<div class="article-breadcrumb-bar">
    <div class="app-container">
        <nav class="article-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ url('/') }}"><i class="bi bi-house-door me-1"></i>Home</a>
            <span class="article-breadcrumb-sep"><i class="bi bi-chevron-right"></i></span>
            <a href="{{ route('news.index') }}">News</a>
            <span class="article-breadcrumb-sep"><i class="bi bi-chevron-right"></i></span>
            <span class="article-breadcrumb-current">{{ Str::limit($article->title, 50) }}</span>
        </nav>
    </div>
</div>
