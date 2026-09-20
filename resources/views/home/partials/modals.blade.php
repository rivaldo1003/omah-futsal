<!-- Team Details & Player Analytics Modal -->
<div class="modal fade" id="teamDetailsModal" tabindex="-1" aria-labelledby="teamDetailsModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="teamDetailsModalTitle">
                    <i class="bi bi-people text-secondary"></i>
                    <span>Team squad & players</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="teamDetailsContent">
                <!-- Injected dynamically by showTeamPlayers() in scripts.blade.php -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Highlight Video Modals -->
@foreach($recentHighlights as $match)
    @if($match->youtube_id)
        <div class="modal fade" id="highlightModal{{ $match->id }}" tabindex="-1" aria-labelledby="highlightModalLabel{{ $match->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-youtube text-danger fs-5"></i>
                            <h5 class="modal-title" id="highlightModalLabel{{ $match->id }}">
                                {{ $match->homeTeam->name ?? 'Home' }} vs {{ $match->awayTeam->name ?? 'Away' }}
                                @if($match->home_score !== null && $match->away_score !== null)
                                    <span class="app-badge app-badge-success ms-2">{{ $match->home_score }} - {{ $match->away_score }}</span>
                                @endif
                            </h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="ratio ratio-16x9" style="background: #000;">
                            <iframe src="{{ $match->youtube_embed_url }}"
                                title="Highlight: {{ $match->homeTeam->name ?? 'Home' }} vs {{ $match->awayTeam->name ?? 'Away' }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen>
                            </iframe>
                        </div>

                        <div class="p-4">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <h6 class="fw-semibold small text-secondary mb-2">Match information</h6>
                                    <ul class="list-unstyled small text-secondary mb-0">
                                        <li class="mb-1">
                                            <strong class="text-dark">Date:</strong> {{ $match->match_date->format('d M Y') }} • {{ $match->time_start }}
                                        </li>
                                        <li class="mb-1">
                                            <strong class="text-dark">Venue:</strong> {{ $match->venue ?? 'Main field' }}
                                        </li>
                                        <li class="mb-1">
                                            <strong class="text-dark">Stage:</strong> {{ ucfirst(str_replace('_', ' ', $match->round_type)) }}
                                        </li>
                                        @if($match->group_name)
                                            <li class="mb-1">
                                                <strong class="text-dark">Group:</strong> Group {{ $match->group_name }}
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <div class="col-12 col-md-6">
                                    <h6 class="fw-semibold small text-secondary mb-2">YouTube details</h6>
                                    <ul class="list-unstyled small text-secondary mb-0">
                                        <li class="mb-1">
                                            <strong class="text-dark">Video ID:</strong> <code>{{ $match->youtube_id }}</code>
                                        </li>
                                        @if($match->youtube_duration_formatted)
                                            <li class="mb-1">
                                                <strong class="text-dark">Duration:</strong> {{ $match->youtube_duration_formatted }}
                                            </li>
                                        @endif
                                        @if($match->youtube_uploaded_at)
                                            <li class="mb-1">
                                                <strong class="text-dark">Added:</strong> {{ $match->youtube_uploaded_at->format('d M Y H:i') }}
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-action-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="https://youtube.com/watch?v={{ $match->youtube_id }}" target="_blank" class="btn-action-primary" style="background-color: var(--danger);">
                            <i class="bi bi-box-arrow-up-right"></i>
                            <span>Watch on YouTube</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<!-- Hero Fullscreen Image Modal -->
<div class="modal fade" id="heroFullscreenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="background: rgba(15, 23, 42, 0.96); backdrop-filter: blur(8px);">
            <div class="modal-header border-0">
                <span class="text-white small fw-medium">Hero image viewer</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center p-0">
                <div id="heroFullscreenImage" class="w-100 h-100 d-flex align-items-center justify-content-center">
                    <!-- Image rendered dynamically -->
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-3">
                <button type="button" class="btn-action-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    <span>Close</span>
                </button>
                <button type="button" class="btn-action-secondary text-white" id="downloadHeroBtn" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);">
                    <i class="bi bi-download"></i>
                    <span>Download image</span>
                </button>
            </div>
        </div>
    </div>
</div>
