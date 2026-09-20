<div class="app-card">
    <div class="app-card-header">
        <h3 class="app-card-title">
            <i class="bi bi-play-circle"></i>
            <span>Match highlights</span>
        </h3>
        <a href="{{ route('highlights.index') }}" class="text-secondary small" title="View all highlights">
            <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="app-card-body">
        @if($recentHighlights->count() > 0)
            @foreach($recentHighlights as $match)
                <div class="highlight-card">
                    <div class="highlight-thumb-wrap" data-bs-toggle="modal" data-bs-target="#highlightModal{{ $match->id }}">
                        <img src="{{ $match->display_thumbnail_url }}"
                            alt="{{ $match->homeTeam->name ?? 'Home' }} vs {{ $match->awayTeam->name ?? 'Away' }}"
                            class="highlight-thumb-img">

                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="app-badge app-badge-danger">
                                <i class="bi bi-youtube"></i>
                                <span>YouTube</span>
                            </span>
                        </div>

                        <div class="play-btn-circle">
                            <i class="bi bi-play-fill"></i>
                        </div>

                        @if($match->youtube_duration_formatted)
                            <div class="position-absolute bottom-0 end-0 m-2">
                                <span class="app-badge app-badge-default" style="background: rgba(15, 23, 42, 0.75); color: #ffffff; border: none;">
                                    {{ $match->youtube_duration_formatted }}
                                </span>
                            </div>
                        @endif

                        @if($match->home_score !== null && $match->away_score !== null)
                            <div class="position-absolute bottom-0 start-0 m-2">
                                <span class="app-badge app-badge-success">
                                    {{ $match->home_score }} - {{ $match->away_score }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="p-3">
                        <div class="fw-semibold text-truncate small mb-1">
                            {{ $match->homeTeam->name ?? 'Home' }} vs {{ $match->awayTeam->name ?? 'Away' }}
                        </div>
                        <div class="d-flex align-items-center justify-content-between text-secondary" style="font-size: 11px;">
                            <span><i class="bi bi-calendar me-1"></i>{{ $match->match_date->format('d M Y') }}</span>
                            @if($match->youtube_uploaded_at)
                                <span>{{ $match->youtube_uploaded_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="pt-2">
                <a href="{{ route('highlights.index') }}" class="btn-action-secondary w-100">
                    <i class="bi bi-collection-play"></i>
                    <span>View all highlights</span>
                </a>
            </div>
        @else
            <div class="text-center py-4 text-secondary">
                <i class="bi bi-film fs-4 d-block mb-1"></i>
                <span class="small">No highlights available yet</span>
            </div>
        @endif
    </div>
</div>
