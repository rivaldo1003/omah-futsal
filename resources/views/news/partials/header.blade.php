{{-- ============================================================
     news/partials/header.blade.php
     Page title bar + category filter tabs
     Variables: $categories (Collection), $news (Paginator)
     ============================================================ --}}
<div class="news-page-header">
    <div class="app-container">
        <h1 class="news-page-title">
            <i class="bi bi-newspaper me-1"></i>
            @if(request()->has('category'))
                {{ request('category') }} News
            @else
                Latest News
            @endif
        </h1>
        <p class="news-page-subtitle">Stay updated with match reports, announcements, and stories from OFS Futsal Center</p>

        {{-- Category filter tabs --}}
        <nav class="news-filter-bar" aria-label="News categories">
            <a href="{{ route('news.index') }}"
               class="news-filter-tab {{ !request()->has('category') ? 'active' : '' }}">
                <i class="bi bi-grid"></i>
                All
                <span class="tab-count">{{ $news->total() }}</span>
            </a>

            @foreach($categories as $category)
                <a href="{{ route('news.index', ['category' => $category]) }}"
                   class="news-filter-tab {{ request('category') === $category ? 'active' : '' }}">
                    {{ $category }}
                </a>
            @endforeach
        </nav>
    </div>
</div>
