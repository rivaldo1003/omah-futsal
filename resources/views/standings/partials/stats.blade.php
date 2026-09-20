{{-- Tournament Stats — Preserved from original, currently commented out.
     Uncomment and pass $tournamentStats from controller to activate. --}}
{{--
@if(isset($tournamentStats))
    <div class="standings-stats-grid">
        <div class="standings-stat-card">
            <div class="standings-stat-icon icon-accent">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div>
                <div class="standings-stat-value">{{ $tournamentStats['total_matches'] ?? 0 }}</div>
                <p class="standings-stat-label">Matches</p>
            </div>
        </div>
        <div class="standings-stat-card">
            <div class="standings-stat-icon icon-success">
                <i class="bi bi-dribbble"></i>
            </div>
            <div>
                <div class="standings-stat-value">{{ $tournamentStats['total_goals'] ?? 0 }}</div>
                <p class="standings-stat-label">Goals</p>
            </div>
        </div>
        <div class="standings-stat-card">
            <div class="standings-stat-icon icon-warning">
                <i class="bi bi-graph-up"></i>
            </div>
            <div>
                <div class="standings-stat-value">{{ $tournamentStats['avg_goals_per_match'] ?? 0 }}</div>
                <p class="standings-stat-label">Avg goals</p>
            </div>
        </div>
        <div class="standings-stat-card">
            <div class="standings-stat-icon icon-danger">
                <i class="bi bi-award"></i>
            </div>
            <div>
                <div class="standings-stat-value">
                    @if(isset($tournamentStats['top_team']) && $tournamentStats['top_team']->team)
                        {{ substr($tournamentStats['top_team']->team->name, 0, 3) }}
                    @else
                        -
                    @endif
                </div>
                <p class="standings-stat-label">Leader</p>
            </div>
        </div>
    </div>
@endif
--}}
