{{-- Page title and category filters. Variables: $categories, $news. --}}
<div class="news-page-header">
    <div class="app-container">
        <h1 class="news-page-title">
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
                @class(['news-filter-tab', 'active' => !request()->has('category')])
                @if(!request()->has('category')) aria-current="page" @endif>
                <i class="bi bi-grid"></i>
                All
                <span class="tab-count">{{ $news->total() }}</span>
            </a>

            @foreach($categories as $category)
                <a href="{{ route('news.index', ['category' => $category]) }}"
                    @class(['news-filter-tab', 'active' => request('category') === $category])
                    @if(request('category') === $category) aria-current="page" @endif>
                    {{ $category }}
                </a>
            @endforeach
        </nav>
    </div>
</div>
