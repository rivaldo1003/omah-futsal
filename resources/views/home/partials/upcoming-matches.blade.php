<div class="app-card">
    <div class="app-card-header">
        <h3 class="app-card-title">
            <i class="bi bi-calendar-week"></i>
            <span>Upcoming matches</span>
        </h3>
    </div>
    <div class="app-card-body">
        @if($upcomingMatches->count() > 0)
            @foreach($upcomingMatches as $match)
                @php
                    $homeTeam = $match->homeTeam;
                    $homeLogo = $homeTeam->logo ?? null;
                    $homeName = $homeTeam->name ?? 'TBA';

                    $awayTeam = $match->awayTeam;
                    $awayLogo = $awayTeam->logo ?? null;
                    $awayName = $awayTeam->name ?? 'TBA';
                @endphp
                <div class="upcoming-row">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="app-badge app-badge-default">{{ date('d M', strtotime($match->match_date)) }}</span>
                            <span class="text-secondary small">{{ date('H:i', strtotime($match->time_start)) }}</span>
                            @if(($match->round_type ?? null) === 'friendly')
                                <span class="app-badge app-badge-accent">Friendly</span>
                            @endif
                        </div>
                        <span class="app-badge app-badge-warning">Upcoming</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between py-1">
                        <!-- Home Team -->
                        <div class="team-pill" style="width: 45%;">
                            @if($homeLogo && Storage::disk('public')->exists($homeLogo))
                                <img src="{{ asset('storage/' . $homeLogo) }}" alt="{{ $homeName }}" class="team-pill-logo">
                            @else
                                <div class="team-pill-fallback">{{ strtoupper(substr($homeName, 0, 1)) }}</div>
                            @endif
                            <span class="text-truncate fw-medium">{{ $homeName }}</span>
                        </div>

                        <span class="text-secondary small fw-semibold px-2">vs</span>

                        <!-- Away Team -->
                        <div class="team-pill justify-content-end text-end" style="width: 45%;">
                            <span class="text-truncate fw-medium me-2">{{ $awayName }}</span>
                            @if($awayLogo && Storage::disk('public')->exists($awayLogo))
                                <img src="{{ asset('storage/' . $awayLogo) }}" alt="{{ $awayName }}" class="team-pill-logo">
                            @else
                                <div class="team-pill-fallback">{{ strtoupper(substr($awayName, 0, 1)) }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-2 text-secondary small">
                        <span><i class="bi bi-geo-alt me-1"></i>{{ $match->venue ?? 'Main field' }}</span>
                        @if($match->group_name)
                            <span><i class="bi bi-diagram-3 me-1"></i>Group {{ $match->group_name }}</span>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="pt-3">
                <a href="{{ route('schedule') }}" class="btn-action-secondary w-100">
                    <i class="bi bi-calendar3"></i>
                    <span>View full schedule</span>
                </a>
            </div>
        @else
            <div class="text-center py-4 text-secondary">
                <i class="bi bi-calendar-x fs-4 d-block mb-1"></i>
                <span>No upcoming matches scheduled</span>
            </div>
        @endif
    </div>
</div>
