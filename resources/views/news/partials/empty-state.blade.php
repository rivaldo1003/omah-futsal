{{-- ============================================================
     news/partials/empty-state.blade.php
     No articles found message
     ============================================================ --}}
<div class="col-12">
    <div class="news-empty-state">
        <i class="bi bi-newspaper"></i>
        <h5>No Articles Found</h5>
        <p>
            @if(request()->has('category'))
                There are no articles in the <strong>{{ request('category') }}</strong> category.
            @else
                There are no news articles to display at the moment.
            @endif
        </p>
        @if(request()->has('category'))
            <a href="{{ route('news.index') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> View All News
            </a>
        @endif
    </div>
</div>
