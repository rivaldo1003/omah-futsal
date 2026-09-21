<div class="app-card">
    <div class="app-card-header">
        <h3 class="app-card-title">
            <i class="bi bi-people"></i>
            <span>All registered teams</span>
        </h3>
        @if($teams->count() > 0)
            <span class="app-badge app-badge-default">{{ $teams->count() }} teams</span>
        @endif
    </div>
    <div class="app-card-body">
        @if($teams->count() > 0)
            <!-- Search & Sort Controls Toolbar -->
            <div class="team-toolbar-wrap">
                <div class="row g-2">
                    <div class="col-12 col-md-8">
                        <div class="team-search-input-wrap">
                            <i class="bi bi-search team-search-icon"></i>
                            <input type="text" id="teamSearch" class="team-search-input"
                                placeholder="Search teams, coaches, or players...">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="position-relative">
                            <select id="teamSort" class="form-select team-sort-select">
                                <option value="name">Sort by name (A-Z)</option>
                                <option value="players">Sort by squad size</option>
                                <option value="tournaments">Sort by tournaments</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teams Grid -->
            <div class="row g-3 teams-grid-row" id="teamsGrid">
                @foreach($teams as $index => $team)
                    @php
                        $totalPlayers = $team->players->count();
                        $totalTournaments = $team->tournaments->count();
                        $keyPlayers = $team->players->take(2);

                        $playersData = $team->players->map(function ($player) {
                            return [
                                'id' => $player->id ?? 0,
                                'name' => $player->name ?? 'Unknown',
                                'jersey_number' => $player->jersey_number ?? '',
                                'position' => $player->position ?? '',
                                'photo' => $player->photo ?? '',
                                'goals' => $player->goals ?? 0,
                                'assists' => $player->assists ?? 0,
                                'saves' => $player->saves ?? 0,
                                'clean_sheets' => $player->clean_sheets ?? 0,
                                'yellow_cards' => $player->yellow_cards ?? 0,
                                'red_cards' => $player->red_cards ?? 0,
                                'appearances' => $player->appearances_count ?? 0,
                                'market_value' => $player->formatted_market_value ?? '',
                            ];
                        })->toArray();

                        $playersJson = json_encode($playersData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                        $isActive = strtolower($team->status ?? '') === 'active';
                    @endphp
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 team-card"
                        data-team-id="{{ $team->id }}"
                        data-team-name="{{ strtolower($team->name) }}"
                        data-team-players="{{ $totalPlayers }}"
                        data-team-tournaments="{{ $totalTournaments }}"
                        data-team-coach="{{ $team->coach_name }}"
                        data-team-logo="{{ $team->logo }}"
                        data-players-json="{{ $playersJson }}">
                        <div class="team-item-card">
                            <div class="team-card-header">
                                <div class="team-card-identity">
                                    @if($team->logo && Storage::disk('public')->exists($team->logo))
                                        <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" class="team-card-logo">
                                    @else
                                        <div class="team-card-logo-fallback">{{ strtoupper(substr($team->name, 0, 2)) }}</div>
                                    @endif
                                    <div class="team-card-info">
                                        <h6 class="team-card-title" title="{{ $team->name }}">{{ $team->name }}</h6>
                                        <div class="team-card-subtitle">
                                            @if($team->coach_name)
                                                <span class="text-truncate" title="{{ $team->coach_name }}">
                                                    <i class="bi bi-person me-1"></i>{{ $team->coach_name }}
                                                </span>
                                            @else
                                                <span><i class="bi bi-shield me-1"></i>Club member</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="app-badge {{ $isActive ? 'app-badge-success' : 'app-badge-default' }} flex-shrink-0">
                                    {{ ucfirst($team->status ?? 'Registered') }}
                                </span>
                            </div>

                            <div class="team-card-stats-grid">
                                <div class="team-card-stat-box">
                                    <div class="team-card-stat-num">{{ $totalPlayers }}</div>
                                    <div class="team-card-stat-lbl">Players</div>
                                </div>
                                <div class="team-card-stat-box">
                                    <div class="team-card-stat-num">{{ $totalTournaments }}</div>
                                    <div class="team-card-stat-lbl">Events</div>
                                </div>
                                <div class="team-card-stat-box">
                                    <div class="team-card-stat-num">#{{ $team->id }}</div>
                                    <div class="team-card-stat-lbl">ID</div>
                                </div>
                            </div>

                            <div class="team-card-roster">
                                <span class="team-card-roster-lbl"><i class="bi bi-person-lines-fill me-1"></i>Squad:</span>
                                @if($keyPlayers->count() > 0)
                                    <div class="team-card-roster-chips">
                                        @foreach($keyPlayers as $player)
                                            <span class="player-pill-chip" title="{{ $player->name }}">
                                                <span class="fw-bold text-secondary">#{{ $player->jersey_number ?? '0' }}</span>
                                                <span>{{ $player->name }}</span>
                                            </span>
                                        @endforeach
                                        @if($totalPlayers > 2)
                                            <span class="player-pill-more">+{{ $totalPlayers - 2 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="small text-secondary fst-italic">No squad roster registered</span>
                                @endif
                            </div>

                            <div class="team-card-footer">
                                <button type="button" class="btn-action-secondary btn-action-sm w-100 view-team-details"
                                    data-team-index="{{ $index }}">
                                    <i class="bi bi-people"></i>
                                    <span>View squad roster</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- No Search Results -->
            <div id="noTeamsFound" class="text-center py-5 d-none text-secondary">
                <i class="bi bi-search fs-3 d-block mb-2"></i>
                <h6 class="fw-semibold mb-1">No matching teams found</h6>
                <span class="small">Try searching with a different team, coach, or player name.</span>
            </div>
        @else
            <div class="text-center py-5 text-secondary">
                <i class="bi bi-people fs-2 d-block mb-2"></i>
                <h6 class="fw-semibold mb-1">No teams available</h6>
                <span class="small">Teams will appear here once registered.</span>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <div class="mt-3">
                            <a href="{{ route('teams.create') }}" class="btn-action-primary btn-action-sm">
                                <i class="bi bi-plus-lg"></i>
                                <span>Create first team</span>
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</div>
