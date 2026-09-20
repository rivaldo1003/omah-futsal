<div class="app-card">
    <div class="app-card-header">
        <h3 class="app-card-title">
            <i class="bi bi-clock-history"></i>
            <span>Recent results</span>
        </h3>
        <span class="app-badge app-badge-default">Completed matches</span>
    </div>
    <div class="app-card-body">
        @if($recentResults->count() > 0)
            @foreach($recentResults as $match)
                @php
                    $homeTeam = $match->homeTeam;
                    $homeLogo = $homeTeam->logo ?? null;
                    $homeName = $homeTeam->name ?? 'TBA';

                    $awayTeam = $match->awayTeam;
                    $awayLogo = $awayTeam->logo ?? null;
                    $awayName = $awayTeam->name ?? 'TBA';
                @endphp
                <div class="recent-result-row">
                    <!-- Top Meta: Date, Stage, Group & Badge -->
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2 text-secondary small">
                            <span class="app-badge app-badge-default">{{ date('d M Y', strtotime($match->match_date)) }}</span>
                            @if($match->time_start)
                                <span>{{ date('H:i', strtotime($match->time_start)) }}</span>
                            @endif
                            <span class="d-none d-sm-inline">•</span>
                            <span>{{ ucfirst(str_replace('_', ' ', $match->round_type ?? 'Match')) }}</span>
                            @if($match->group_name)
                                <span class="d-none d-sm-inline">•</span>
                                <span>Group {{ $match->group_name }}</span>
                            @endif
                        </div>
                        <span class="app-badge app-badge-success">Full Time</span>
                    </div>

                    <!-- Teams & Scoreboard Grid -->
                    <div class="recent-match-scoreboard py-1">
                        <!-- Home Team -->
                        <div class="recent-team-cell home-team">
                            <span class="recent-team-name text-truncate" title="{{ $homeName }}">
                                {{ $homeName }}
                            </span>
                            @if($homeLogo && Storage::disk('public')->exists($homeLogo))
                                <img src="{{ asset('storage/' . $homeLogo) }}" alt="{{ $homeName }}" class="team-pill-logo">
                            @else
                                <div class="team-pill-fallback">{{ strtoupper(substr($homeName, 0, 1)) }}</div>
                            @endif
                        </div>

                        <!-- Score Center -->
                        <div class="recent-score-center">
                            <div class="recent-score-badge">
                                <span>{{ $match->home_score ?? 0 }}</span>
                                <span class="recent-score-divider">-</span>
                                <span>{{ $match->away_score ?? 0 }}</span>
                            </div>

                            @if($match->et_score || ($match->is_penalty && $match->penalty_score))
                                <div class="d-flex justify-content-center gap-1 mt-1">
                                    @if($match->et_score)
                                        <span class="app-badge app-badge-default" style="font-size: 10px;">ET {{ $match->et_score }}</span>
                                    @endif
                                    @if($match->is_penalty && $match->penalty_score)
                                        <span class="app-badge app-badge-warning" style="font-size: 10px;">Pen. {{ $match->penalty_score }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Away Team -->
                        <div class="recent-team-cell away-team">
                            @if($awayLogo && Storage::disk('public')->exists($awayLogo))
                                <img src="{{ asset('storage/' . $awayLogo) }}" alt="{{ $awayName }}" class="team-pill-logo">
                            @else
                                <div class="team-pill-fallback">{{ strtoupper(substr($awayName, 0, 1)) }}</div>
                            @endif
                            <span class="recent-team-name text-truncate" title="{{ $awayName }}">
                                {{ $awayName }}
                            </span>
                        </div>
                    </div>

                    <!-- Venue Info -->
                    @if($match->venue)
                        <div class="text-secondary small d-flex align-items-center gap-1 mt-1 mb-2">
                            <i class="bi bi-geo-alt"></i>
                            <span>{{ $match->venue }}</span>
                        </div>
                    @endif

                    <!-- Goals & Cards in Recent Results -->
                    @if(isset($match->events) && $match->events->count() > 0)
                        @php
                            $indirectRedResults = $match->events->where('event_type', 'red_card')
                                ->where('description', 'Kartu Kuning Kedua (Indirect Red)')
                                ->pluck('player_id')->toArray();
                            $homeEvents = $match->events->where('team_id', $match->team_home_id);
                            $awayEvents = $match->events->where('team_id', $match->team_away_id);
                        @endphp
                        @if($homeEvents->count() > 0 || $awayEvents->count() > 0)
                            <div class="recent-events-container mt-2">
                                <div class="row g-2">
                                    <!-- Home Team Events -->
                                    <div class="col-6">
                                        <div class="recent-events-list home-events">
                                            @foreach($homeEvents as $event)
                                                @if($event->event_type == 'yellow_card' && in_array($event->player_id, $indirectRedResults))
                                                    @continue
                                                @endif
                                                <div class="recent-event-item">
                                                    @if($event->event_type == 'goal')
                                                        <i class="bi bi-bullseye text-success"></i>
                                                    @elseif($event->event_type == 'yellow_card')
                                                        <i class="bi bi-square-fill text-warning" style="font-size: 8px;"></i>
                                                    @elseif($event->event_type == 'red_card')
                                                        <i class="bi bi-square-fill text-danger" style="font-size: 8px;"></i>
                                                    @endif
                                                    <span class="event-minute fw-semibold">{{ $event->minute }}'</span>
                                                    <span class="event-player text-truncate" title="{{ $event->player->name ?? 'Player' }}">
                                                        {{ $event->player->short_name ?? $event->player->name ?? 'Player' }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Away Team Events -->
                                    <div class="col-6">
                                        <div class="recent-events-list away-events text-end">
                                            @foreach($awayEvents as $event)
                                                @if($event->event_type == 'yellow_card' && in_array($event->player_id, $indirectRedResults))
                                                    @continue
                                                @endif
                                                <div class="recent-event-item justify-content-end">
                                                    <span class="event-player text-truncate" title="{{ $event->player->name ?? 'Player' }}">
                                                        {{ $event->player->short_name ?? $event->player->name ?? 'Player' }}
                                                    </span>
                                                    <span class="event-minute fw-semibold">{{ $event->minute }}'</span>
                                                    @if($event->event_type == 'goal')
                                                        <i class="bi bi-bullseye text-success"></i>
                                                    @elseif($event->event_type == 'yellow_card')
                                                        <i class="bi bi-square-fill text-warning" style="font-size: 8px;"></i>
                                                    @elseif($event->event_type == 'red_card')
                                                        <i class="bi bi-square-fill text-danger" style="font-size: 8px;"></i>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach

            <div class="pt-3">
                <a href="{{ route('schedule') }}" class="btn-action-secondary w-100">
                    <i class="bi bi-calendar-check"></i>
                    <span>View all match results & schedule</span>
                </a>
            </div>
        @else
            <div class="text-center py-4 text-secondary">
                <i class="bi bi-clock-history fs-4 d-block mb-1"></i>
                <span class="small">No recent completed matches</span>
            </div>
        @endif
    </div>
</div>
