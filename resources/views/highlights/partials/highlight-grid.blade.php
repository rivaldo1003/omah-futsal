{{-- ============================================================
     highlights/partials/highlight-grid.blade.php
     Cards grid + modals + pagination
     Variable: $highlights (LengthAwarePaginator)
     ============================================================ --}}

@if($highlights->count() > 0)

    {{-- ---- Cards ---- --}}
    <div class="row g-3">
        @foreach($highlights as $highlight)
            <div class="col-md-6 col-lg-4">
                <div class="highlight-card"
                     data-bs-toggle="modal"
                     data-bs-target="#ytModal{{ $highlight->id }}"
                     role="button"
                     aria-label="Watch {{ $highlight->homeTeam->name ?? 'Home' }} vs {{ $highlight->awayTeam->name ?? 'Away' }} highlight">

                    {{-- Thumbnail --}}
                    <div class="highlight-thumbnail">
                        <span class="yt-badge"><i class="bi bi-youtube"></i> YouTube</span>

                        @if($highlight->youtube_id)
                            <img src="{{ $highlight->display_thumbnail_url ?? 'https://img.youtube.com/vi/' . $highlight->youtube_id . '/maxresdefault.jpg' }}"
                                 alt="{{ $highlight->homeTeam->name ?? 'Home' }} vs {{ $highlight->awayTeam->name ?? 'Away' }}"
                                 loading="lazy"
                                 onerror="this.src='https://img.youtube.com/vi/{{ $highlight->youtube_id }}/hqdefault.jpg'">
                        @else
                            <div class="highlight-thumbnail-placeholder">
                                <i class="bi bi-youtube"></i>
                            </div>
                        @endif

                        <div class="play-overlay">
                            <div class="play-btn-circle">
                                <i class="bi bi-play-fill"></i>
                            </div>
                        </div>

                        @if($highlight->youtube_duration_formatted)
                            <span class="duration-badge">{{ $highlight->youtube_duration_formatted }}</span>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="highlight-info">
                        <h2 class="highlight-match-title">
                            @if($highlight->home_score !== null && $highlight->away_score !== null)
                                <span class="highlight-match-score">{{ $highlight->home_score }} - {{ $highlight->away_score }}</span>
                            @endif
                            {{ $highlight->homeTeam->name ?? 'Home' }} vs {{ $highlight->awayTeam->name ?? 'Away' }}
                        </h2>

                        <div class="highlight-match-meta">
                            <div class="highlight-match-meta-row">
                                <span><i class="bi bi-calendar me-1"></i>{{ $highlight->match_date->format('d M Y') }}</span>
                                <span><i class="bi bi-clock me-1"></i>{{ $highlight->time_start }}</span>
                            </div>
                            <span><i class="bi bi-geo-alt me-1"></i>{{ $highlight->venue ?? 'Main Field' }}</span>
                        </div>

                        <div class="highlight-card-footer">
                            <span class="highlight-uploaded-time">
                                @if($highlight->youtube_uploaded_at)
                                    <i class="bi bi-clock-history"></i>
                                    {{ $highlight->youtube_uploaded_at->diffForHumans() }}
                                @endif
                            </span>
                            <span class="highlight-watch-badge">
                                <i class="bi bi-youtube"></i> Watch
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ---- Pagination ---- --}}
    @if($highlights->hasPages())
        <div class="highlights-pagination">
            <nav aria-label="Highlights pagination">
                {{ $highlights->links() }}
            </nav>
        </div>
    @endif

    {{-- ---- YouTube Modals ---- --}}
    @foreach($highlights as $highlight)
        @if($highlight->youtube_id)
            <div class="modal fade" id="ytModal{{ $highlight->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">

                        {{-- Modal Header --}}
                        <div class="modal-header">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-youtube text-danger" style="font-size:1.1rem;"></i>
                                <h5 class="modal-title mb-0">
                                    {{ $highlight->homeTeam->name ?? 'Home' }} vs {{ $highlight->awayTeam->name ?? 'Away' }}
                                    @if($highlight->home_score !== null && $highlight->away_score !== null)
                                        <span class="badge bg-success ms-1" style="font-size:0.72rem;">
                                            {{ $highlight->home_score }} - {{ $highlight->away_score }}
                                        </span>
                                    @endif
                                </h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        {{-- Video Embed --}}
                        <div class="modal-body modal-video-body">
                            <div class="ratio ratio-16x9">
                                <iframe
                                    src="https://www.youtube.com/embed/{{ $highlight->youtube_id }}?rel=0&showinfo=0&modestbranding=1"
                                    title="Highlight: {{ $highlight->homeTeam->name ?? 'Home' }} vs {{ $highlight->awayTeam->name ?? 'Away' }}"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                    style="background:#000;">
                                </iframe>
                            </div>

                            {{-- Match details inside modal --}}
                            <div class="modal-match-details">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <h6 class="mb-2" style="font-size:0.82rem;font-weight:700;color:#334155;">
                                            <i class="bi bi-info-circle me-1"></i>Match Information
                                        </h6>
                                        <ul class="list-unstyled mb-0" style="font-size:0.82rem;">
                                            <li class="mb-2">
                                                <span class="modal-detail-label">Date &amp; Time</span>
                                                <span class="modal-detail-value">{{ $highlight->match_date->format('d M Y') }} · {{ $highlight->time_start }}</span>
                                            </li>
                                            <li class="mb-2">
                                                <span class="modal-detail-label">Venue</span>
                                                <span class="modal-detail-value">{{ $highlight->venue ?? 'Main Field' }}</span>
                                            </li>
                                            <li class="mb-2">
                                                <span class="modal-detail-label">Stage</span>
                                                <span class="modal-detail-value">{{ ucfirst(str_replace('_', ' ', $highlight->round_type ?? 'group')) }}</span>
                                            </li>
                                            @if($highlight->group_name)
                                                <li class="mb-0">
                                                    <span class="modal-detail-label">Group</span>
                                                    <span class="modal-detail-value">Group {{ $highlight->group_name }}</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-2" style="font-size:0.82rem;font-weight:700;color:#334155;">
                                            <i class="bi bi-youtube text-danger me-1"></i>YouTube Info
                                        </h6>
                                        <ul class="list-unstyled mb-0" style="font-size:0.82rem;">
                                            @if($highlight->youtube_duration_formatted)
                                                <li class="mb-2">
                                                    <span class="modal-detail-label">Duration</span>
                                                    <span class="modal-detail-value">{{ $highlight->youtube_duration_formatted }}</span>
                                                </li>
                                            @endif
                                            @if($highlight->youtube_uploaded_at)
                                                <li class="mb-2">
                                                    <span class="modal-detail-label">Added</span>
                                                    <span class="modal-detail-value">{{ $highlight->youtube_uploaded_at->format('d M Y H:i') }}</span>
                                                </li>
                                            @endif
                                            <li class="mb-0">
                                                <span class="modal-detail-label">Watch on YouTube</span>
                                                <a href="https://youtube.com/watch?v={{ $highlight->youtube_id }}"
                                                   target="_blank"
                                                   class="modal-detail-value text-danger text-decoration-none fw-semibold">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i>Open in YouTube
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="modal-footer" style="padding:10px 16px;border-top:1px solid #e2e8f0;">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Close
                            </button>
                            <a href="https://youtube.com/watch?v={{ $highlight->youtube_id }}"
                               target="_blank"
                               class="btn btn-danger btn-sm">
                                <i class="bi bi-youtube me-1"></i>Watch on YouTube
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    @endforeach

@else
    {{-- ---- Empty State ---- --}}
    <div class="highlights-empty">
        <i class="bi bi-youtube"></i>
        <h4>No Highlights Available</h4>
        <p>Check back later for match highlights on YouTube.</p>
        <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Home
        </a>
    </div>
@endif
