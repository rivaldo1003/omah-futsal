{{-- ============================================================
     news/partials/news-grid.blade.php
     Article grid (2-col on md+) + pagination
     Variables: $news (LengthAwarePaginator)
     ============================================================ --}}
<div class="news-grid-heading">
    <i class="bi bi-newspaper"></i>
    @if(request()->has('category'))
        {{ request('category') }} Articles
    @else
        All Articles
    @endif
    <span class="badge bg-secondary ms-1 fw-normal" style="font-size:0.72rem;">{{ $news->total() }}</span>
</div>

@if($news->count() > 0)
    <div class="row g-3 mb-3">
        @foreach($news as $article)
            <div class="col-md-6">
                <div class="news-article-card">
                    {{-- Image --}}
                    <div class="news-article-image-wrap">
                        @if($article->image_url)
                            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" loading="lazy">
                        @else
                            <div class="news-article-image-placeholder">
                                <i class="bi bi-newspaper"></i>
                            </div>
                        @endif
                        <span class="news-article-cat-tag">{{ $article->category }}</span>
                        @if($article->is_featured)
                            <span class="news-article-feat-tag">Featured</span>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="news-article-body">
                        <h3 class="news-article-title">{{ Str::limit($article->title, 70) }}</h3>
                        <p class="news-article-excerpt">{{ Str::limit($article->excerpt, 90) }}</p>

                        <div class="news-article-footer">
                            <span class="news-article-source">
                                <i class="bi bi-link-45deg"></i>
                                {{ $article->source ?? 'OFS News' }}
                            </span>
                            <span class="news-article-date">
                                <i class="bi bi-clock"></i>
                                {{ $article->published_at->diffForHumans() }}
                            </span>
                        </div>

                        <a href="{{ route('news.show', $article->id) }}" class="news-read-more">
                            Read More <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($news->hasPages())
        <div class="news-pagination-wrap">
            <nav aria-label="News pagination">
                {{ $news->withQueryString()->links() }}
            </nav>
        </div>
    @endif

@else
    @include('news.partials.empty-state')
@endif
