{{-- ============================================================
     news/partials/show-article.blade.php
     Article main column: header, image, body, source, share, related
     Variable: $article, $relatedNews
     ============================================================ --}}

{{-- Back link --}}
<a href="{{ route('news.index') }}" class="article-back-link">
    <i class="bi bi-arrow-left"></i> Back to News
</a>

{{-- Article Header --}}
<div class="article-header mb-3">
    <a href="{{ route('news.index', ['category' => $article->category]) }}" class="article-category-tag">
        <i class="bi bi-tag"></i> {{ $article->category }}
    </a>

    <h1 class="article-title">{{ $article->title }}</h1>

    <div class="article-meta-row">
        <span class="article-meta-item">
            <i class="bi bi-calendar"></i>
            {{ $article->published_at->format('d M Y') }}
        </span>

        @if($article->author)
            <span class="article-meta-item">
                <i class="bi bi-person"></i>
                {{ $article->author }}
            </span>
        @endif

        @if($article->source)
            <span class="article-meta-item">
                <i class="bi bi-link-45deg"></i>
                {{ $article->source }}
            </span>
        @endif

        <span class="article-meta-item">
            <i class="bi bi-eye"></i>
            {{ number_format($article->views_count) }} views
        </span>

        @if($article->is_featured)
            <span class="article-featured-badge">
                <i class="bi bi-star-fill"></i> Featured
            </span>
        @endif
    </div>
</div>

{{-- Hero Image --}}
<div class="article-hero-image-wrap">
    @if($article->image_url)
        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="article-hero-image" loading="eager">
    @else
        <div class="article-hero-image-placeholder">
            <i class="bi bi-newspaper"></i>
        </div>
    @endif
</div>

{{-- Article Body --}}
<div class="article-body">
    {!! $article->content !!}
</div>

{{-- Source URL --}}
@if($article->source_url)
    <div class="article-source-alert">
        <i class="bi bi-link-45deg mt-1"></i>
        <div>
            <strong>Source:</strong>
            <a href="{{ $article->source_url }}" target="_blank" rel="noopener noreferrer">
                {{ Str::limit($article->source_url, 60) }}
            </a>
        </div>
    </div>
@endif

{{-- Share + Timestamp bar --}}
<div class="article-action-bar">
    <div class="d-flex align-items-center gap-2">
        <span class="share-label">Share:</span>
        <div class="share-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
               target="_blank" class="share-btn share-facebook" aria-label="Share on Facebook">
                <i class="bi bi-facebook"></i>
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode(Str::limit($article->title, 100)) }}"
               target="_blank" class="share-btn share-twitter" aria-label="Share on Twitter">
                <i class="bi bi-twitter"></i>
            </a>
            <a href="https://wa.me/?text={{ urlencode(Str::limit($article->title, 100) . ' ' . url()->current()) }}"
               target="_blank" class="share-btn share-whatsapp" aria-label="Share on WhatsApp">
                <i class="bi bi-whatsapp"></i>
            </a>
            <a href="https://t.me/share/url?url={{ url()->current() }}&text={{ urlencode(Str::limit($article->title, 100)) }}"
               target="_blank" class="share-btn share-telegram" aria-label="Share on Telegram">
                <i class="bi bi-telegram"></i>
            </a>
        </div>
    </div>
    <div class="article-publish-time">
        <i class="bi bi-clock"></i>
        Published {{ $article->published_at->diffForHumans() }}
    </div>
</div>

{{-- Related News --}}
@if($relatedNews->count() > 0)
    <section class="mt-4" aria-label="Related articles">
        <h2 class="related-section-title">
            <i class="bi bi-newspaper"></i> Related Articles
        </h2>
        <div class="row g-3">
            @foreach($relatedNews as $related)
                <div class="col-md-6">
                    <div class="related-card">
                        <div class="related-card-image-wrap">
                            @if($related->image_url)
                                <img src="{{ $related->image_url }}" alt="{{ $related->title }}" loading="lazy">
                            @else
                                <div class="related-card-image-placeholder">
                                    <i class="bi bi-newspaper"></i>
                                </div>
                            @endif
                        </div>
                        <div class="related-card-body">
                            <div class="related-card-cat">{{ $related->category }}</div>
                            <h3 class="related-card-title">{{ Str::limit($related->title, 70) }}</h3>
                            <div class="related-card-meta">
                                <span>{{ $related->published_at->diffForHumans() }}</span>
                                <span><i class="bi bi-eye me-1"></i>{{ number_format($related->views_count) }}</span>
                            </div>
                            <a href="{{ route('news.show', $related->id) }}" class="btn btn-outline-primary btn-sm w-100">
                                Read More <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
