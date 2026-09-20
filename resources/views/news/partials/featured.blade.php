{{-- ============================================================
     news/partials/featured.blade.php
     Featured article horizontal card (shown when $featuredNews is non-empty)
     Variables: $featuredNews (Collection)
     ============================================================ --}}
@if($featuredNews->count() > 0)
    @php $featured = $featuredNews->first(); @endphp
    <div class="news-featured-card">
        {{-- Thumbnail --}}
        <div class="news-featured-image-wrap">
            @if($featured->image_url)
                <img src="{{ $featured->image_url }}" alt="{{ $featured->title }}" loading="lazy">
            @else
                <div class="news-featured-image-placeholder">
                    <i class="bi bi-newspaper"></i>
                </div>
            @endif
            <span class="news-featured-badge">Featured</span>
        </div>

        {{-- Body --}}
        <div class="news-featured-body">
            <div class="news-featured-category">{{ $featured->category }}</div>
            <h2 class="news-featured-title">{{ Str::limit($featured->title, 70) }}</h2>
            <p class="news-featured-excerpt">{{ Str::limit($featured->excerpt, 130) }}</p>

            <div class="news-featured-meta">
                <span><i class="bi bi-calendar me-1"></i>{{ $featured->published_at->format('d M Y') }}</span>
                <span><i class="bi bi-eye me-1"></i>{{ number_format($featured->views_count) }} views</span>
            </div>

            <div>
                <a href="{{ route('news.show', $featured->id) }}" class="btn btn-primary btn-sm">
                    Read Full Story <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
@endif
