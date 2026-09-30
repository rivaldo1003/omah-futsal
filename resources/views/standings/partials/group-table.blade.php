{{-- Single Group Standings Table — Ultra Analytics Engine v3.0
     Receives: $group, $groupStandings, $selectedTournament --}}
<div class="v3-standings-group">
    <div class="standings-group-card h-100">
        {{-- Module Header (v3) --}}
        <div class="v3-sg-header">
            <div class="v3-sg-title-wrap">
                <span class="v3-beacon"></span>
                <h3 class="standings-group-title v3-sg-title">
                    <span class="standings-group-badge v3-sg-badge">{{ $selectedTournament->type === 'league' ? 'L' : 'G' }}</span>
                    {{ $selectedTournament->type === 'league' ? 'KLASEMEN UTAMA' : 'GROUP ' . $group }}
                </h3>
            </div>
            <span class="v3-sg-meta">
                <span class="v3-sc-lbl">TEAMS</span>
                <span class="v3-sc-val">{{ count($groupStandings) }}</span>
            </span>
        </div>

        <div class="table-responsive">
            <table class="table standings-table v3-sg-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>TEAM</th>
                        <th>P</th>
                        <th>W</th>
                        <th>D</th>
                        <th>L</th>
                        <th>GD</th>
                        <th>PTS</th>
                        <th>FORM</th>
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

                            // ── Rank emphasis (top 2 qualify) ──
                            $isTop = $index < 2;
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

    <style>
        /* ==================================================================
           ULTRA ANALYTICS ENGINE v3.0 — standings group module
           Scoped tokens (fallback to spec values)
           ================================================================== */
        .v3-standings-group {
            --v3-bg: #020408;
            --v3-surface: #070c14;
            --v3-card: #0d1524;
            --v3-border: #18263e;
            --v3-border-glow: rgba(0, 255, 135, 0.4);
            --v3-neon-green: #00ff87;
            --v3-neon-blue: #00e5ff;
            --v3-neon-pink: #ff0055;
            --v3-neon-yellow: #ffb700;
            --v3-text-main: #ffffff;
            --v3-text-sub: #8da1b9;
            --v3-text-muted: #4e6178;
            --v3-font-sans: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            --v3-font-mono: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;
            --v3-radius-md: 12px;
            --v3-radius-sm: 8px;
        }

        .v3-standings-group .standings-group-card {
            background: linear-gradient(145deg, var(--v3-card), var(--v3-surface));
            border: 1px solid var(--v3-border);
            border-radius: var(--v3-radius-md);
            overflow: hidden;
            color: var(--v3-text-main);
            font-family: var(--v3-font-sans);
        }

        /* Header */
        .v3-standings-group .v3-sg-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-bottom: 1px solid var(--v3-border);
            background: var(--v3-surface);
        }

        .v3-standings-group .v3-sg-title-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .v3-standings-group .v3-beacon {
            width: 8px;
            height: 8px;
            background: var(--v3-neon-green);
            border-radius: 50%;
            box-shadow: 0 0 12px var(--v3-neon-green);
            animation: v3SgBeacon 1.2s infinite alternate;
            flex-shrink: 0;
        }

        @keyframes v3SgBeacon {
            from { opacity: 0.3; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1.2); }
        }

        .v3-standings-group .v3-sg-title {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 900;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: -0.01em;
            color: var(--v3-text-main);
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-shadow: 0 0 18px rgba(0, 255, 135, 0.25);
        }

        .v3-standings-group .v3-sg-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 4px;
            background: var(--v3-neon-green);
            color: #000;
            font-size: 11px;
            font-weight: 900;
            font-style: normal;
        }

        .v3-standings-group .v3-sg-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            text-align: right;
        }

        .v3-standings-group .v3-sc-lbl {
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--v3-text-muted);
        }

        .v3-standings-group .v3-sc-val {
            font-size: 1.1rem;
            font-weight: 900;
            font-style: italic;
            color: var(--v3-neon-green);
        }

        /* Table — neutralize Bootstrap .table light vars */
        .v3-standings-group .v3-sg-table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--v3-text-main);
            --bs-table-border-color: var(--v3-border);
            --bs-table-striped-bg: transparent;
            --bs-table-striped-color: var(--v3-text-main);
            --bs-table-active-bg: rgba(0, 255, 135, 0.06);
            --bs-table-active-color: var(--v3-text-main);
            --bs-table-hover-bg: rgba(0, 255, 135, 0.05);
            --bs-table-hover-color: var(--v3-text-main);
            margin: 0;
            width: 100%;
            font-size: 13px;
            background: transparent !important;
            color: var(--v3-text-main);
            border-color: var(--v3-border);
        }

        /* Force dark surfaces at every level (override any parent light bg) */
        .v3-standings-group .v3-sg-table > :not(caption) > * > * {
            background-color: transparent !important;
            color: var(--v3-text-sub);
            box-shadow: none;
        }

        .v3-standings-group .v3-sg-table tbody tr {
            background-color: transparent !important;
        }

        .v3-standings-group .v3-sg-table thead tr {
            background-color: var(--v3-bg) !important;
        }

        .v3-standings-group .v3-sg-table thead th {
            background: var(--v3-bg);
            color: var(--v3-text-muted);
            font-weight: 800;
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 1px solid var(--v3-border);
            padding: 10px 8px;
            text-align: center;
            white-space: nowrap;
        }

        .v3-standings-group .v3-sg-table thead th:first-child { text-align: center; }
        .v3-standings-group .v3-sg-table thead th:nth-child(2) { text-align: left; }

        .v3-standings-group .v3-sg-table tbody td {
            padding: 10px 8px;
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid var(--v3-border);
            color: var(--v3-text-sub);
        }

        .v3-standings-group .v3-sg-table tbody td:nth-child(2) { text-align: left; }

        .v3-standings-group .v3-sg-table tbody tr {
            transition: background-color 150ms ease;
        }

        .v3-standings-group .v3-sg-table tbody tr:hover {
            background: rgba(0, 255, 135, 0.04);
        }

        .v3-standings-group .v3-sg-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Top-2 qualify accent */
        .v3-standings-group .v3-sg-row--top td:first-child {
            box-shadow: inset 3px 0 0 var(--v3-neon-green);
        }

        /* Position */
        .v3-standings-group .v3-sg-pos {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 4px;
            font-weight: 900;
            font-style: italic;
            font-size: 11px;
            color: var(--v3-text-sub);
            background: var(--v3-bg);
            border: 1px solid var(--v3-border);
        }

        .v3-standings-group .v3-sg-pos.is-top {
            color: var(--v3-neon-green);
            border-color: var(--v3-border-glow);
            background: rgba(0, 255, 135, 0.08);
        }

        /* Team info */
        .v3-standings-group .standings-team-info {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 160px;
        }

        .v3-standings-group .standings-team-logo {
            width: 30px;
            height: 30px;
            border-radius: var(--v3-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--v3-bg);
            border: 1px solid var(--v3-border);
        }

        .v3-standings-group .standings-team-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .v3-standings-group .standings-team-abbr {
            font-weight: 800;
            color: var(--v3-neon-green);
            font-size: 10px;
        }

        .v3-standings-group .v3-sg-name {
            font-weight: 800;
            color: var(--v3-text-main);
            font-size: 13px;
            line-height: 1.3;
        }

        .v3-standings-group .standings-team-meta {
            font-size: 10px;
            font-weight: 700;
            color: var(--v3-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Stat columns */
        .v3-standings-group .standings-stat-played { font-weight: 800; color: var(--v3-text-main); }
        .v3-standings-group .standings-stat-win { color: var(--v3-neon-green); font-weight: 800; }
        .v3-standings-group .standings-stat-draw { color: var(--v3-text-sub); }
        .v3-standings-group .standings-stat-loss { color: var(--v3-neon-pink); }

        /* GD */
        .v3-standings-group .standings-gd {
            font-weight: 800;
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 4px;
            display: inline-block;
            font-family: var(--v3-font-mono);
        }

        .v3-standings-group .standings-gd-positive {
            background: rgba(0, 255, 135, 0.12);
            color: var(--v3-neon-green);
        }

        .v3-standings-group .standings-gd-negative {
            background: rgba(255, 0, 85, 0.12);
            color: var(--v3-neon-pink);
        }

        .v3-standings-group .standings-gd-neutral {
            background: var(--v3-bg);
            color: var(--v3-text-sub);
            border: 1px solid var(--v3-border);
        }

        /* Points */
        .v3-standings-group .v3-sg-pts {
            background: rgba(0, 255, 135, 0.12);
            color: var(--v3-neon-green);
            font-weight: 900;
            font-style: italic;
            font-size: 13px;
            padding: 2px 9px;
            border-radius: 4px;
            min-width: 36px;
            text-align: center;
            display: inline-block;
            border: 1px solid var(--v3-border-glow);
        }

        /* Form dots */
        .v3-standings-group .standings-form {
            display: flex;
            gap: 4px;
            justify-content: center;
            align-items: center;
        }

        .v3-standings-group .standings-form-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 6px currentColor;
        }

        .v3-standings-group .standings-dot-win { background: var(--v3-neon-green); color: var(--v3-neon-green); }
        .v3-standings-group .standings-dot-draw { background: var(--v3-neon-yellow); color: var(--v3-neon-yellow); }
        .v3-standings-group .standings-dot-loss { background: var(--v3-neon-pink); color: var(--v3-neon-pink); }
        .v3-standings-group .standings-dot-empty { background: var(--v3-border); color: transparent; box-shadow: none; }

        /* Responsive */
        @media (max-width: 576px) {
            .v3-standings-group .v3-sg-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            .v3-standings-group .v3-sg-meta { align-items: flex-start; text-align: left; }
            .v3-standings-group .standings-team-info { min-width: auto; }
            .v3-standings-group .v3-sg-name { font-size: 12px; }
        }
    </style>
</div>