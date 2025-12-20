<div class="team-details-modal">
    <!-- Team Header -->
    <div class="text-center mb-4">
        <!-- Team Logo -->
        <div class="mb-3">
            @if($team->logo && Storage::disk('public')->exists($team->logo))
                <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" 
                     class="img-fluid rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
            @else
                <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle mx-auto"
                     style="width: 120px; height: 120px; font-size: 2.5rem; font-weight: bold;">
                    {{ strtoupper(substr($team->name, 0, 2)) }}
                </div>
            @endif
        </div>
        
        <h4 class="fw-bold mb-2">{{ $team->name }}</h4>
        @if($team->short_name)
            <span class="badge bg-secondary mb-3">{{ $team->short_name }}</span>
        @endif
        
        <div class="d-flex justify-content-center gap-3 mb-3">
            <span class="badge bg-{{ $team->status == 'active' ? 'success' : ($team->status == 'pending' ? 'warning' : 'secondary') }}">
                {{ ucfirst($team->status) }}
            </span>
            <span class="badge bg-info">
                <i class="bi bi-people"></i> {{ $team->players_count }} Players
            </span>
        </div>
    </div>

    <!-- Team Information -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h6><i class="bi bi-info-circle me-2"></i>Team Info</h6>
            <ul class="list-unstyled">
                @if($team->coach_name)
                    <li class="mb-2">
                        <strong class="text-muted">Coach:</strong> {{ $team->coach_name }}
                    </li>
                @endif
                @if($team->contact_email)
                    <li class="mb-2">
                        <strong class="text-muted">Email:</strong> 
                        <a href="mailto:{{ $team->contact_email }}">{{ $team->contact_email }}</a>
                    </li>
                @endif
                @if($team->contact_phone)
                    <li class="mb-2">
                        <strong class="text-muted">Phone:</strong> {{ $team->contact_phone }}
                    </li>
                @endif
                @if($team->description)
                    <li class="mb-2">
                        <strong class="text-muted">About:</strong> {{ $team->description }}
                    </li>
                @endif
            </ul>
        </div>
        
        <!-- Statistics -->
        <div class="col-md-6">
            <h6><i class="bi bi-bar-chart me-2"></i>Statistics</h6>
            <div class="row text-center">
                <div class="col-3">
                    <div class="border rounded p-2">
                        <div class="fw-bold">{{ $stats['total_matches'] }}</div>
                        <small class="text-muted">Matches</small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="border rounded p-2 bg-success text-white">
                        <div class="fw-bold">{{ $stats['wins'] }}</div>
                        <small>Wins</small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="border rounded p-2">
                        <div class="fw-bold">{{ $stats['draws'] }}</div>
                        <small class="text-muted">Draws</small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="border rounded p-2 bg-danger text-white">
                        <div class="fw-bold">{{ $stats['losses'] }}</div>
                        <small>Losses</small>
                    </div>
                </div>
            </div>
            <div class="row text-center mt-2">
                <div class="col-4">
                    <div class="border rounded p-2">
                        <div class="fw-bold">{{ $stats['goals_for'] }}</div>
                        <small class="text-muted">GF</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="border rounded p-2">
                        <div class="fw-bold">{{ $stats['goals_against'] }}</div>
                        <small class="text-muted">GA</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="border rounded p-2 bg-primary text-white">
                        <div class="fw-bold">{{ $stats['points'] }}</div>
                        <small>Points</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Players -->
    <div class="mb-4">
        <h6><i class="bi bi-trophy me-2"></i>Top Players</h6>
        @if($team->players->count() > 0)
            <div class="list-group">
                @foreach($team->players->take(5) as $player)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $player->name }}</strong>
                            <small class="text-muted d-block">
                                {{ $player->position ?? 'Player' }}
                                @if($player->jersey_number)
                                    <span class="badge bg-secondary ms-2">#{{ $player->jersey_number }}</span>
                                @endif
                            </small>
                        </div>
                        <div class="text-end">
                            @if($player->goals > 0)
                                <span class="badge bg-success me-1">
                                    <i class="bi bi-soccer"></i> {{ $player->goals }}
                                </span>
                            @endif
                            @if($player->assists > 0)
                                <span class="badge bg-info me-1">
                                    <i class="bi bi-send"></i> {{ $player->assists }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @if($team->players->count() > 5)
                <div class="text-center mt-2">
                    <small class="text-muted">
                        +{{ $team->players->count() - 5 }} more players
                    </small>
                </div>
            @endif
        @else
            <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle"></i> No players registered yet
            </div>
        @endif
    </div>

    <!-- Tournaments -->
    @if($team->tournaments->count() > 0)
        <div class="mb-4">
            <h6><i class="bi bi-trophy me-2"></i>Current Tournaments</h6>
            <div class="row">
                @foreach($team->tournaments->take(3) as $tournament)
                    <div class="col-md-6 mb-2">
                        <div class="border rounded p-3">
                            <strong>{{ $tournament->name }}</strong>
                            <small class="text-muted d-block">
                                {{ $tournament->start_date->format('d M Y') }} - {{ $tournament->end_date->format('d M Y') }}
                            </small>
                            <span class="badge bg-{{ $tournament->status == 'ongoing' ? 'success' : 'warning' }}">
                                {{ ucfirst($tournament->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recent Matches -->
    @if(count($team->homeMatches) > 0 || count($team->awayMatches) > 0)
        <div>
            <h6><i class="bi bi-clock-history me-2"></i>Recent Matches</h6>
            <div class="list-group">
                @php
                    $recentMatches = $team->homeMatches->merge($team->awayMatches)
                        ->sortByDesc('match_date')
                        ->take(3);
                @endphp
                @foreach($recentMatches as $match)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">{{ $match->match_date->format('d M Y') }}</small>
                                <div class="mt-1">
                                    {{ $match->homeTeam->name }} {{ $match->home_score }} - 
                                    {{ $match->away_score }} {{ $match->awayTeam->name }}
                                </div>
                            </div>
                            @if($match->homeTeam->id == $team->id)
                                <span class="badge bg-{{ $match->home_score > $match->away_score ? 'success' : ($match->home_score < $match->away_score ? 'danger' : 'secondary') }}">
                                    {{ $match->home_score > $match->away_score ? 'W' : ($match->home_score < $match->away_score ? 'L' : 'D') }}
                                </span>
                            @else
                                <span class="badge bg-{{ $match->away_score > $match->home_score ? 'success' : ($match->away_score < $match->home_score ? 'danger' : 'secondary') }}">
                                    {{ $match->away_score > $match->home_score ? 'W' : ($match->away_score < $match->home_score ? 'L' : 'D') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>