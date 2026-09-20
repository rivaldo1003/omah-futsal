{{-- ============================================================
     news/partials/sidebar.blade.php
     Three widgets: Latest Updates · Categories · Most Popular
     Variables: $latestNews, $categories
     ============================================================ --}}

{{-- ---- Latest Updates ---- --}}
<div class="news-sidebar-card">
    <div class="news-sidebar-header">
        <div class="news-sidebar-header-left">
            <i class="bi bi-clock-history"></i>
            <span>Latest Updates</span>
        </div>
        <span class="badge bg-primary" style="font-size:0.68rem;">{{ $latestNews->count() }}</span>
    </div>

    @if($latestNews->count() > 0)
        @foreach($latestNews as $article)
            <a href="{{ route('news.show', $article->id) }}" class="news-sidebar-item">
                @if($article->image_url)
                    <div class="news-sidebar-thumbnail">
                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" loading="lazy">
                    </div>
                @endif
                <div class="news-sidebar-info">
                    <div class="news-sidebar-title">{{ Str::limit($article->title, 55) }}</div>
                    <div class="news-sidebar-meta">
                        <span class="news-sidebar-cat">{{ $article->category }}</span>
                        <span class="news-sidebar-time">{{ $article->published_at->diffForHumans() }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    @else
        <div class="text-center py-3">
            <i class="bi bi-newspaper text-muted" style="font-size:1.4rem;"></i>
            <p class="mt-1 mb-0" style="font-size:0.8rem;color:#94a3b8;">No recent updates</p>
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
               class="news-cat-pill {{ request('category') === $category ? 'active' : '' }}">
                {{ $category }}
            </a>
        @endforeach
    </div>
</div>

{{-- ---- Most Popular ---- --}}
@php
    $popularNews = \App\Models\NewsArticle::where('is_active', true)
        ->orderBy('views_count', 'desc')
        ->take(5)
        ->get();
@endphp

<div class="news-sidebar-card">
    <div class="news-sidebar-header">
        <div class="news-sidebar-header-left">
            <i class="bi bi-fire"></i>
            <span>Most Popular</span>
        </div>
        <i class="bi bi-bar-chart text-warning"></i>
    </div>

    @if($popularNews->count() > 0)
        @foreach($popularNews as $article)
            <a href="{{ route('news.show', $article->id) }}" class="news-sidebar-item">
                <div class="news-sidebar-info">
                    <div class="news-sidebar-title">{{ Str::limit($article->title, 55) }}</div>
                    <div class="news-sidebar-meta">
                        <span class="news-sidebar-cat">{{ $article->category }}</span>
                        <span class="news-views-badge">
                            <i class="bi bi-eye"></i> {{ number_format($article->views_count) }}
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
