{{-- Group and league table. Receives: $group, $groupStandings, $selectedTournament. --}}
<div class="v3-standings-group {{ $selectedTournament->type === 'league' ? 'is-league' : 'is-group' }}">
    <div class="standings-group-card h-100">
        {{-- Module Header (v3) --}}
        <div class="v3-sg-header">
            <div class="v3-sg-title-wrap">
                <h3 class="standings-group-title v3-sg-title">
                    <span class="standings-group-badge v3-sg-badge">{{ $selectedTournament->type === 'league' ? 'L' : 'G' }}</span>
                    {{ $selectedTournament->type === 'league' ? 'League table' : 'Group ' . $group }}
                </h3>
            </div>
            <span class="v3-sg-meta">
                <span class="v3-sc-lbl">Teams</span>
                <span class="v3-sc-val">{{ count($groupStandings) }}</span>
            </span>
        </div>

        <div class="table-responsive">
            <table class="table standings-table v3-sg-table">
                <caption class="visually-hidden">
                    {{ $selectedTournament->type === 'league' ? 'League standings' : 'Group ' . $group . ' standings' }}
                </caption>
                <thead>
                    <tr>
                        <th scope="col" title="Position">#</th>
                        <th scope="col">Team</th>
                        <th scope="col" title="Played">P</th>
                        <th scope="col" title="Won">W</th>
                        <th scope="col" title="Drawn">D</th>
                        <th scope="col" title="Lost">L</th>
                        <th scope="col" title="Goal difference">GD</th>
                        <th scope="col" title="Points">Pts</th>
                        <th scope="col" title="Results in the last five matches">Form</th>
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

                            // Highlight the league leader, or the top two group positions.
                            $isTop = $selectedTournament->type === 'league' ? $index === 0 : $index < 2;
                        @endphp

                        <tr class="{{ $isTop ? 'v3-sg-row--top' : '' }}">
                            {{-- Position --}}
                            <td>
                                <span class="standings-position v3-sg-pos {{ $isTop ? 'is-top' : '' }}">{{ $index + 1 }}</span>
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
                                        <div class="standings-team-name v3-sg-name">{{ Str::limit($teamName, 20) }}</div>
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
                                <span class="standings-points v3-sg-pts">{{ $team->points ?? 0 }}</span>
                            </td>

                            {{-- Form Dots --}}
                            <td>
                                <div class="standings-form">
                                    @if(!empty($recentForm) && is_array($recentForm))
                                        @foreach(array_slice($recentForm, 0, 5) as $result)
                                            @if($result == 'W')
                                            <span class="standings-form-dot standings-dot-win" role="img" aria-label="Win" title="Win"></span>
                                            @elseif($result == 'D')
                                            <span class="standings-form-dot standings-dot-draw" role="img" aria-label="Draw" title="Draw"></span>
                                            @elseif($result == 'L')
                                            <span class="standings-form-dot standings-dot-loss" role="img" aria-label="Loss" title="Loss"></span>
                                            @else
                                            <span class="standings-form-dot standings-dot-empty" role="img" aria-label="Result unavailable" title="Result unavailable"></span>
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

    <style>
        .v3-standings-group {
            --table-ink: #111111;
            --table-charcoal: #39393b;
            --table-muted: #707072;
            --table-border: #e5e5e5;
            --table-cloud: #f5f5f5;
            --table-success: #007d48;
            --table-success-soft: #e8f3ed;
            --table-danger: #b42318;
            --table-danger-soft: #f9e9e7;
            --table-warning: #805600;
            color: var(--table-ink);
            font-family: var(--v3-font-sans, "Helvetica Neue", Helvetica, sans-serif);
        }

        .v3-standings-group .standings-group-card {
            height: 100%;
            overflow: hidden;
            color: var(--table-ink);
            background: #ffffff;
            border: 1px solid var(--table-border);
            border-radius: 8px;
        }

        .v3-standings-group .v3-sg-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            min-height: 68px;
            padding: 16px 20px;
            background: #ffffff;
            border-bottom: 1px solid var(--table-border);
        }

        .v3-standings-group .v3-sg-title-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .v3-standings-group .v3-sg-title {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            margin: 0;
            overflow: hidden;
            color: var(--table-ink);
            font-size: 16px;
            font-weight: 600;
            letter-spacing: -0.02em;
            line-height: 1.25;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .v3-standings-group .v3-sg-badge {
            display: inline-flex;
            flex: 0 0 28px;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            color: #ffffff;
            background: var(--table-ink);
            border-radius: 50%;
            font-size: 12px;
            font-weight: 600;
        }

        .v3-standings-group .v3-sg-meta {
            display: flex;
            flex: 0 0 auto;
            flex-direction: column;
            align-items: flex-end;
            gap: 1px;
            text-align: right;
        }

        .v3-standings-group .v3-sc-lbl {
            color: var(--table-muted);
            font-size: 11px;
            font-weight: 500;
        }

        .v3-standings-group .v3-sc-val {
            color: var(--table-ink);
            font-size: 16px;
            font-weight: 600;
            line-height: 1.2;
        }

        .v3-standings-group .table-responsive {
            scrollbar-color: #cacacb transparent;
            scrollbar-width: thin;
        }

        .v3-standings-group .v3-sg-table {
            --bs-table-bg: #ffffff;
            --bs-table-color: var(--table-ink);
            --bs-table-border-color: var(--table-border);
            --bs-table-striped-bg: #ffffff;
            --bs-table-striped-color: var(--table-ink);
            --bs-table-hover-bg: var(--table-cloud);
            --bs-table-hover-color: var(--table-ink);
            width: 100%;
            min-width: 630px;
            margin: 0;
            color: var(--table-ink);
            background: #ffffff;
            border-color: var(--table-border);
            font-size: 13px;
        }

        .v3-standings-group .v3-sg-table > :not(caption) > * > * {
            color: var(--table-charcoal);
            background-color: #ffffff;
            box-shadow: none;
        }

        .v3-standings-group .v3-sg-table thead th {
            padding: 12px 10px;
            color: var(--table-muted);
            background: var(--table-cloud);
            border-bottom: 1px solid var(--table-border);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.035em;
            text-align: center;
            white-space: nowrap;
        }

        .v3-standings-group .v3-sg-table thead th:first-child {
            width: 52px;
        }

        .v3-standings-group .v3-sg-table thead th:nth-child(2) {
            min-width: 190px;
            text-align: left;
        }

        .v3-standings-group .v3-sg-table tbody td {
            padding: 12px 10px;
            color: var(--table-charcoal);
            border-bottom: 1px solid var(--table-border);
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }

        .v3-standings-group .v3-sg-table tbody td:nth-child(2) {
            text-align: left;
        }

        .v3-standings-group .v3-sg-table tbody tr {
            transition: background-color 150ms ease;
        }

        .v3-standings-group .v3-sg-table tbody tr:hover > * {
            background-color: var(--table-cloud);
        }

        .v3-standings-group .v3-sg-table tbody tr:last-child > * {
            border-bottom: 0;
        }

        .v3-standings-group .v3-sg-row--top > td:first-child {
            box-shadow: inset 3px 0 0 var(--table-success);
        }

        .v3-standings-group .v3-sg-pos {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            color: var(--table-charcoal);
            background: var(--table-cloud);
            border: 1px solid var(--table-border);
            border-radius: 50%;
            font-size: 12px;
            font-weight: 600;
        }

        .v3-standings-group .v3-sg-pos.is-top {
            color: var(--table-success);
            background: var(--table-success-soft);
            border-color: transparent;
        }

        .v3-standings-group .standings-team-info {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 190px;
        }

        .v3-standings-group .standings-team-logo {
            display: flex;
            flex: 0 0 36px;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            overflow: hidden;
            background: var(--table-cloud);
            border: 1px solid var(--table-border);
            border-radius: 50%;
        }

        .v3-standings-group .standings-team-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .v3-standings-group .standings-team-abbr {
            color: var(--table-muted);
            font-size: 10px;
            font-weight: 600;
        }

        .v3-standings-group .v3-sg-name {
            overflow: hidden;
            color: var(--table-ink);
            font-size: 13px;
            font-weight: 600;
            line-height: 1.3;
            text-overflow: ellipsis;
        }

        .v3-standings-group .standings-team-meta {
            margin-top: 2px;
            color: var(--table-muted);
            font-size: 11px;
            font-weight: 400;
        }

        .v3-standings-group .standings-stat-played {
            color: var(--table-ink);
            font-weight: 600;
        }

        .v3-standings-group .standings-stat-win {
            color: var(--table-success);
            font-weight: 600;
        }

        .v3-standings-group .standings-stat-draw {
            color: var(--table-charcoal);
        }

        .v3-standings-group .standings-stat-loss {
            color: var(--table-danger);
        }

        .v3-standings-group .standings-gd {
            display: inline-block;
            min-width: 36px;
            padding: 3px 7px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
        }

        .v3-standings-group .standings-gd-positive {
            color: var(--table-success);
            background: var(--table-success-soft);
        }

        .v3-standings-group .standings-gd-negative {
            color: var(--table-danger);
            background: var(--table-danger-soft);
        }

        .v3-standings-group .standings-gd-neutral {
            color: var(--table-muted);
            background: var(--table-cloud);
        }

        .v3-standings-group .v3-sg-pts {
            display: inline-block;
            min-width: 36px;
            padding: 4px 9px;
            color: #ffffff;
            background: var(--table-ink);
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
        }

        .v3-standings-group .standings-form {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            min-width: 68px;
        }

        .v3-standings-group .standings-form-dot {
            display: inline-block;
            width: 9px;
            height: 9px;
            border-radius: 50%;
        }

        .v3-standings-group .standings-dot-win { background: var(--table-success); }
        .v3-standings-group .standings-dot-draw { background: #b58212; }
        .v3-standings-group .standings-dot-loss { background: var(--table-danger); }
        .v3-standings-group .standings-dot-empty { background: #cacacb; }

        @media (max-width: 576px) {
            .v3-standings-group .v3-sg-header {
                min-height: 62px;
                padding: 14px 16px;
            }

            .v3-standings-group .v3-sg-title {
                font-size: 15px;
            }

            .v3-standings-group .standings-team-info {
                min-width: 180px;
            }
        }
    </style>
</div>
