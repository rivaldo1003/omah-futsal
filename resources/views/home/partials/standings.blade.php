@if(!$isCupType)
    <div class="app-card">
        <div class="app-card-header">
            <h3 class="app-card-title">
                <i class="bi bi-bar-chart-line"></i>
                <span>Group standings</span>
            </h3>
            @if($activeTournament)
                <span class="app-badge app-badge-default">{{ $activeTournament->name }}</span>
            @endif
        </div>
        <div class="app-card-body">
            @if(isset($standings) && count($standings) > 0)
                <div class="row g-4">
                    @foreach($standings as $group => $groupStandings)
                        <div class="col-12 col-xl-6">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-semibold small text-secondary">Group {{ $group }}</span>
                            </div>
                            <div class="table-responsive border rounded" style="border-color: var(--border-color) !important;">
                                <table class="standings-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 32px;">#</th>
                                            <th>Team</th>
                                            <th title="Played">P</th>
                                            <th title="Won">W</th>
                                            <th title="Drawn">D</th>
                                            <th title="Lost">L</th>
                                            <th title="Goal Difference">GD</th>
                                            <th title="Points">PTS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($groupStandings as $index => $standing)
                                            @php
                                                $team = $standing->team ?? null;
                                                $teamName = $team->name ?? $standing->team_name ?? $standing->name ?? 'Unknown team';
                                                $teamLogo = $team->logo ?? null;
                                                $hasPlayed = isset($standing->matches_played) && $standing->matches_played > 0;

                                                $teamAbbr = '';
                                                if (!empty($teamName)) {
                                                    $words = explode(' ', $teamName);
                                                    if (count($words) >= 2) {
                                                        $teamAbbr = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                                                    } else {
                                                        $teamAbbr = strtoupper(substr($teamName, 0, 2));
                                                    }
                                                }

                                                $gdValue = $standing->goal_difference ?? 0;
                                                $gdDisplay = $gdValue > 0 ? '+' . $gdValue : $gdValue;

                                                // Render the logo whenever it is set (URL or storage path).
                                                // Avoids Storage::exists() false-negatives on servers where
                                                // files live under public/storage but not storage/app/public.
                                                $logoExists = !empty($teamLogo);
                                            @endphp
                                            <tr class="{{ $index < 2 && $hasPlayed ? 'row-qualified' : '' }}">
                                                <td>
                                                    <span class="rank-num">{{ $index + 1 }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if($logoExists)
                                                            <img src="{{ filter_var($teamLogo, FILTER_VALIDATE_URL) ? $teamLogo : asset('storage/' . $teamLogo) }}"
                                                                alt="{{ $teamName }}" class="team-pill-logo">
                                                        @else
                                                            <div class="team-pill-fallback">{{ $teamAbbr }}</div>
                                                        @endif
                                                        <span class="text-truncate fw-medium" style="max-width: 130px;" title="{{ $teamName }}">
                                                            {{ $teamName }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-secondary">{{ $standing->matches_played ?? 0 }}</td>
                                                <td class="text-secondary">{{ $standing->wins ?? 0 }}</td>
                                                <td class="text-secondary">{{ $standing->draws ?? 0 }}</td>
                                                <td class="text-secondary">{{ $standing->losses ?? 0 }}</td>
                                                <td>
                                                    <span class="{{ $gdValue > 0 ? 'gd-positive' : ($gdValue < 0 ? 'gd-negative' : 'text-secondary') }}">
                                                        {{ $gdDisplay }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="pts-badge">{{ $standing->points ?? 0 }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(!empty($activeTournament->tie_breakers))
                    <div class="tie-break-box">
                        <div class="fw-semibold mb-1">
                            <i class="bi bi-info-circle me-1"></i>
                            <span>Penentuan juara dan runner-up:</span>
                        </div>
                        <ol class="mb-0 ps-3">
                            @foreach($activeTournament->tie_breakers as $rule)
                                <li>{{ $rule }}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                <div class="pt-3">
                    <a href="{{ route('standings') }}" class="btn-action-secondary w-100">
                        <i class="bi bi-table"></i>
                        <span>View full standings</span>
                    </a>
                </div>
            @else
                <div class="text-center py-4 text-secondary">
                    <i class="bi bi-bar-chart fs-4 d-block mb-1"></i>
                    <span>No group standings available</span>
                </div>
            @endif
        </div>
    </div>
@else
    @if($activeTournament)
        <div class="app-card">
            <div class="app-card-header">
                <h3 class="app-card-title">
                    <i class="bi bi-diagram-3"></i>
                    <span>Tournament format</span>
                </h3>
            </div>
            <div class="app-card-body">
                <div class="p-3 bg-light rounded border" style="border-color: var(--border-color) !important;">
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi bi-trophy text-primary fs-4 mt-1"></i>
                        <div>
                            <h6 class="fw-semibold mb-1">{{ $activeTournament->name }} - Knockout Tournament</h6>
                            <p class="text-secondary small mb-1">This is a knockout tournament format. Group standings are not applicable.</p>
                            <span class="text-muted small">Follow the knockout bracket progression in the tournament schedule.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
