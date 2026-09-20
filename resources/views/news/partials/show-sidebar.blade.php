{{-- ============================================================
     news/partials/show-sidebar.blade.php
     Sidebar for show page: Latest News + Categories + Most Popular
     Variable: $article (to exclude current article)
     ============================================================ --}}

@php
    $latestNews = \App\Models\NewsArticle::where('is_active', true)
        ->where('id', '!=', $article->id)
        ->latest('published_at')
        ->take(5)
        ->get();

    $categories = \App\Models\NewsArticle::where('is_active', true)
        ->select('category')
        ->distinct()
        ->pluck('category');

    $popularNews = \App\Models\NewsArticle::where('is_active', true)
        ->where('id', '!=', $article->id)
        ->orderBy('views_count', 'desc')
        ->take(5)
        ->get();
@endphp

{{-- ---- Latest News ---- --}}
<div class="news-sidebar-card">
    <div class="news-sidebar-header">
        <div class="news-sidebar-header-left">
            <i class="bi bi-clock-history"></i>
            <span>Latest News</span>
        </div>
        <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-primary" style="padding:2px 8px;font-size:0.72rem;">
            All <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    @if($latestNews->count() > 0)
        @foreach($latestNews as $newsItem)
            <a href="{{ route('news.show', $newsItem->id) }}" class="news-sidebar-item">
                @if($newsItem->image_url)
                    <div class="news-sidebar-thumbnail">
                        <img src="{{ $newsItem->image_url }}" alt="{{ $newsItem->title }}" loading="lazy">
                    </div>
                @endif
                <div class="news-sidebar-info">
                    <div class="news-sidebar-title">{{ Str::limit($newsItem->title, 55) }}</div>
                    <div class="news-sidebar-meta">
                        <span class="news-sidebar-cat">{{ $newsItem->category }}</span>
                        <span class="news-sidebar-time">{{ $newsItem->published_at->diffForHumans() }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    @else
        <div class="text-center py-3">
            <i class="bi bi-newspaper text-muted" style="font-size:1.4rem;"></i>
            <p class="mt-1 mb-0" style="font-size:0.8rem;color:#94a3b8;">No other news</p>
        </div>
    @endif
</div>

{{-- ---- Categories ---- --}}
<div class="news-sidebar-card">
    <div class="news-sidebar-header">
        <div class="news-sidebar-header-left">
            <i class="bi bi-tags"></i>
            <span>Categories</span>
        </div>
        <span class="badge bg-secondary" style="font-size:0.68rem;">{{ $categories->count() }}</span>
    </div>
    <div class="p-3 d-flex flex-wrap gap-2">
        @foreach($categories as $category)
            <a href="{{ route('news.index', ['category' => $category]) }}"
               class="news-cat-pill {{ $article->category === $category ? 'active' : '' }}">
                {{ $category }}
            </a>
        @endforeach
    </div>
</div>

{{-- ---- Most Popular ---- --}}
<div class="news-sidebar-card">
    <div class="news-sidebar-header">
        <div class="news-sidebar-header-left">
            <i class="bi bi-fire"></i>
            <span>Most Popular</span>
        </div>
        <i class="bi bi-bar-chart text-warning"></i>
    </div>

    @if($popularNews->count() > 0)
        @foreach($popularNews as $newsItem)
            <a href="{{ route('news.show', $newsItem->id) }}" class="news-sidebar-item">
                <div class="news-sidebar-info">
                    <div class="news-sidebar-title">{{ Str::limit($newsItem->title, 55) }}</div>
                    <div class="news-sidebar-meta">
                        <span class="news-sidebar-cat">{{ $newsItem->category }}</span>
                        <span class="news-views-badge">
                            <i class="bi bi-eye"></i> {{ number_format($newsItem->views_count) }}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    @else
        <div class="text-center py-3">
            <i class="bi bi-bar-chart text-muted" style="font-size:1.4rem;"></i>
            <p class="mt-1 mb-0" style="font-size:0.8rem;color:#94a3b8;">No popular articles yet</p>
        </div>
    @endif
</div>
