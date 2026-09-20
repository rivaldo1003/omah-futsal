{{-- Single Group Standings Table Component
     Receives: $group, $groupStandings, $selectedTournament --}}
<div class="standings-group-card h-100">
    <div class="standings-group-header">
        <h3 class="standings-group-title">
            <span class="standings-group-badge">{{ $selectedTournament->type === 'league' ? 'L' : 'G' }}</span>
            {{ $selectedTournament->type === 'league' ? 'Klasemen Utama' : 'Group ' . $group }}
        </h3>
        <span class="standings-group-meta">{{ count($groupStandings) }} teams</span>
    </div>

    <div class="table-responsive">
        <table class="table standings-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Team</th>
                    <th>P</th>
                    <th>W</th>
                    <th>D</th>
                    <th>L</th>
                    <th>GD</th>
                    <th>Pts</th>
                    <th>Form</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupStandings as $index => $team)
                    @php
                        // ── Resolve team name & logo ──
                        $teamName = '';
                        $teamLogo = null;

                        if (isset($team->team) && is_object($team->team)) {
                            $teamName = $team->team->name ?? 'Unknown';
                            $teamLogo = $team->team->logo ?? null;
                        } elseif (isset($team->team_name)) {
                            $teamName = $team->team_name;
                            $teamLogo = $team->team_logo ?? null;
                        } elseif (isset($team->name)) {
                            $teamName = $team->name;
                            $teamLogo = $team->logo ?? null;
                        } else {
                            $teamName = 'Unknown';
                        }

                        // ── Abbreviation fallback ──
                        $teamAbbr = '';
                        if (!empty($teamName)) {
                            $words = explode(' ', $teamName);
                            $teamAbbr = count($words) >= 2
                                ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
                                : strtoupper(substr($teamName, 0, 2));
                        }

                        // ── Goal difference ──
                        $gdValue = $team->goal_difference ?? 0;
                        $gdClass = $gdValue > 0 ? 'standings-gd-positive' : ($gdValue < 0 ? 'standings-gd-negative' : 'standings-gd-neutral');
                        $gdDisplay = $gdValue > 0 ? '+' . $gdValue : $gdValue;

                        // ── Recent form dots ──
                        $recentForm = isset($team->recent_form) ? $team->recent_form : [];
                    @endphp

                    <tr>
                        {{-- Position --}}
                        <td>
                            <span class="standings-position">{{ $index + 1 }}</span>
                        </td>

                        {{-- Team Info --}}
                        <td>
                            <div class="standings-team-info">
                                <div class="standings-team-logo">
                                    @php
                                        $logoPath = null;
                                        if ($teamLogo) {
                                            if (filter_var($teamLogo, FILTER_VALIDATE_URL)) {
                                                $logoPath = $teamLogo;
                                            } else {
                                                try {
                                                    if (Storage::disk('public')->exists($teamLogo)) {
                                                        $logoPath = asset('storage/' . $teamLogo);
                                                    }
                                                } catch (Exception $e) {
                                                    $logoPath = null;
                                                }
                                            }
                                        }
                                    @endphp

                                    @if($logoPath)
                                        <img src="{{ $logoPath }}" alt="{{ $teamName }}"
                                            onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'standings-team-abbr\'>{{ $teamAbbr }}</span>';">
                                    @else
                                        <span class="standings-team-abbr">{{ $teamAbbr }}</span>
                                    @endif
                                </div>

                                <div>
                                    <div class="standings-team-name">{{ Str::limit($teamName, 20) }}</div>
                                    @if(($team->played ?? 0) > 0)
                                        <div class="standings-team-meta">{{ $team->played }} played</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Stats Columns --}}
                        <td class="standings-stat-played">{{ $team->played ?? 0 }}</td>
                        <td class="standings-stat-win">{{ $team->won ?? 0 }}</td>
                        <td class="standings-stat-draw">{{ $team->drawn ?? 0 }}</td>
                        <td class="standings-stat-loss">{{ $team->lost ?? 0 }}</td>

                        {{-- Goal Difference --}}
                        <td>
                            <span class="standings-gd {{ $gdClass }}">{{ $gdDisplay }}</span>
                        </td>

                        {{-- Points --}}
                        <td>
                            <span class="standings-points">{{ $team->points ?? 0 }}</span>
                        </td>

                        {{-- Form Dots --}}
                        <td>
                            <div class="standings-form">
                                @if(!empty($recentForm) && is_array($recentForm))
                                    @foreach(array_slice($recentForm, 0, 5) as $result)
                                        @if($result == 'W')
                                            <span class="standings-form-dot standings-dot-win"></span>
                                        @elseif($result == 'D')
                                            <span class="standings-form-dot standings-dot-draw"></span>
                                        @elseif($result == 'L')
                                            <span class="standings-form-dot standings-dot-loss"></span>
                                        @else
                                            <span class="standings-form-dot standings-dot-empty"></span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="standings-team-meta">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
