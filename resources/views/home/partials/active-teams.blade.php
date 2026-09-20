<div class="app-card">
    <div class="app-card-header">
        <h3 class="app-card-title">
            <i class="bi bi-trophy"></i>
            <span>Teams in ongoing tournament</span>
        </h3>
        <div class="d-flex align-items-center gap-2">
            @if(isset($activeTournament) && $activeTournament)
                <span class="app-badge app-badge-default">{{ $activeTournament->name }}</span>
            @endif
            <span class="app-badge app-badge-accent">{{ $teamsInActiveTournament->count() }} teams</span>
        </div>
    </div>
    <div class="app-card-body">
        @if($teamsInActiveTournament->count() > 0)
            <div class="row g-3 teams-grid-row">
                @foreach($teamsInActiveTournament as $index => $team)
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
                            ];
                        })->toArray();

                        $playersJson = json_encode($playersData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
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
                                                <span><i class="bi bi-shield-check me-1"></i>Tournament squad</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="app-badge app-badge-success flex-shrink-0">
                                    <i class="bi bi-circle-fill" style="font-size: 5px;"></i> Active
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
                                    <div class="team-card-stat-lbl">Seed</div>
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
                                    data-team-index="active_{{ $index }}">
                                    <i class="bi bi-people"></i>
                                    <span>View squad roster</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 text-secondary">
                <i class="bi bi-people fs-4 d-block mb-1"></i>
                <span>No teams currently registered in this ongoing tournament</span>
            </div>
        @endif
    </div>
</div>
