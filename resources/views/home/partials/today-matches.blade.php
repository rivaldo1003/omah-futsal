@if($todayMatches->count() > 0)
    <section class="app-card mb-5">
        <div class="app-card-header">
            <h2 class="app-card-title">
                <i class="bi bi-calendar-event"></i>
                <span>Today's matches</span>
            </h2>
            <span class="app-badge app-badge-accent">{{ $todayMatches->count() }} matches</span>
        </div>
        <div class="app-card-body">
            @foreach($todayMatches as $match)
                <div class="match-row-item">
                    <div class="row align-items-center g-3">
                        <!-- Match Time & Venue -->
                        <div class="col-12 col-md-3">
                            <div class="match-meta-box">
                                <div class="match-time-text">{{ date('H:i', strtotime($match->time_start)) }}</div>
                                <div class="match-venue-text text-truncate">{{ $match->venue ?? 'Main field' }}</div>
                            </div>
                        </div>

                        <!-- Teams & Scoreboard -->
                        <div class="col-12 col-md-7">
                            <div class="match-teams-grid">
                                <!-- Home Team -->
                                <div class="match-team-side home">
                                    <span class="match-team-name">{{ $match->homeTeam->name ?? 'TBA' }}</span>
                                    <span class="match-team-stage">
                                        {{ $match->group_name ? 'Group ' . $match->group_name : ucfirst(str_replace('_', ' ', $match->round_type ?? '')) }}
                                    </span>
                                </div>

                                <!-- Score / Status -->
                                <div class="match-score-center">
                                    @if($match->status === 'completed')
                                        <div class="score-chip">{{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}</div>
                                        <span class="app-badge app-badge-default mt-1">FT</span>
                                    @elseif($match->status === 'ongoing')
                                        <div class="score-chip live">{{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}</div>
                                        <span class="app-badge app-badge-danger mt-1">LIVE</span>
                                    @else
                                        <div class="score-chip">VS</div>
                                        <span class="app-badge app-badge-default mt-1">Upcoming</span>
                                    @endif
                                </div>

                                <!-- Away Team -->
                                <div class="match-team-side away">
                                    <span class="match-team-name">{{ $match->awayTeam->name ?? 'TBA' }}</span>
                                    <span class="match-team-stage">{{ ucfirst($match->status ?? 'Upcoming') }}</span>
                                </div>
                            </div>

                            <!-- Match Timeline Events (Goals, Cards) -->
                            @if(isset($match->events) && $match->events->count() > 0 && ($match->status == 'completed' || $match->status == 'ongoing'))
                                @php
                                    $indirectRedPlayerIds = $match->events->where('event_type', 'red_card')
                                        ->where('description', 'Kartu Kuning Kedua (Indirect Red)')
                                        ->pluck('player_id')->toArray();
                                @endphp
                                <div class="match-events-box">
                                    <div class="row g-2">
                                        <!-- Home Team Events -->
                                        <div class="col-6">
                                            @foreach($match->events->where('team_id', $match->team_home_id) as $event)
                                                @if($event->event_type == 'yellow_card' && in_array($event->player_id, $indirectRedPlayerIds))
                                                    @continue
                                                @endif
                                                <div class="d-flex align-items-center gap-1 mb-1">
                                                    @if($event->event_type == 'goal')
                                                        <span class="app-badge app-badge-success">{{ $event->minute }}'</span>
                                                        <span class="text-truncate">
                                                            {{ $event->player->short_name ?? $event->player->name ?? 'Unknown' }}
                                                            @if($event->is_penalty)<span class="text-secondary">(P)</span>@endif
                                                            @if($event->is_own_goal)<span class="text-danger">(OG)</span>@endif
                                                        </span>
                                                    @elseif($event->event_type == 'yellow_card')
                                                        <span class="app-badge app-badge-warning">{{ $event->minute }}'</span>
                                                        <span class="text-truncate">{{ $event->player->short_name ?? $event->player->name ?? 'Unknown' }}</span>
                                                    @elseif($event->event_type == 'red_card')
                                                        <span class="app-badge app-badge-danger">{{ $event->minute }}'</span>
                                                        <span class="text-truncate">{{ $event->player->short_name ?? $event->player->name ?? 'Unknown' }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Away Team Events -->
                                        <div class="col-6">
                                            @foreach($match->events->where('team_id', $match->team_away_id) as $event)
                                                @if($event->event_type == 'yellow_card' && in_array($event->player_id, $indirectRedPlayerIds))
                                                    @continue
                                                @endif
                                                <div class="d-flex align-items-center gap-1 mb-1">
                                                    @if($event->event_type == 'goal')
                                                        <span class="app-badge app-badge-success">{{ $event->minute }}'</span>
                                                        <span class="text-truncate">
                                                            {{ $event->player->short_name ?? $event->player->name ?? 'Unknown' }}
                                                            @if($event->is_penalty)<span class="text-secondary">(P)</span>@endif
                                                            @if($event->is_own_goal)<span class="text-danger">(OG)</span>@endif
                                                        </span>
                                                    @elseif($event->event_type == 'yellow_card')
                                                        <span class="app-badge app-badge-warning">{{ $event->minute }}'</span>
                                                        <span class="text-truncate">{{ $event->player->short_name ?? $event->player->name ?? 'Unknown' }}</span>
                                                    @elseif($event->event_type == 'red_card')
                                                        <span class="app-badge app-badge-danger">{{ $event->minute }}'</span>
                                                        <span class="text-truncate">{{ $event->player->short_name ?? $event->player->name ?? 'Unknown' }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action Link -->
                        <div class="col-12 col-md-2 text-md-end text-center">
                            <a href="{{ route('matches.show', $match->id) }}" class="btn-action-secondary btn-action-sm w-100 w-md-auto">
                                <i class="bi bi-arrow-up-right"></i>
                                <span>Details</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
