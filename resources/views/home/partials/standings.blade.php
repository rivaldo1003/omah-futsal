{{-- Home standings / tournament format — Ultra Analytics Engine v3.0 --}}
<div class="v3-home-standings">
@if(!$isCupType)
    <div class="app-card v3-card">
        <div class="app-card-header v3-card-header">
            <h3 class="app-card-title v3-card-title">
                <span class="v3-beacon"></span>
                <i class="bi bi-bar-chart-line"></i>
                <span>{{ $activeTournament && $activeTournament->type === 'league' ? 'KLASEMEN UTAMA' : 'GROUP STANDINGS' }}</span>
            </h3>
            @if($activeTournament)
                <span class="v3-hud-tag">{{ $activeTournament->name }}</span>
            @endif
        </div>
        <div class="app-card-body">
            @if(isset($standings) && count($standings) > 0)
                <div class="row g-4">
                    @foreach($standings as $group => $groupStandings)
                        <div class="col-12 col-xl-6">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="v3-group-label">
                                    <span class="v3-group-badge">{{ $activeTournament && $activeTournament->type === 'league' ? 'L' : substr($group, 0, 1) }}</span>
                                    {{ $activeTournament && $activeTournament->type === 'league' ? 'Klasemen' : 'Group ' . $group }}
                                </span>
                                <span class="v3-hud-tag">{{ count($groupStandings) }} TEAMS</span>
                            </div>
                            <div class="table-responsive v3-table-wrap">
                                <table class="standings-table v3-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 32px;">#</th>
                                            <th>TEAM</th>
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

                                                $logoExists = !empty($teamLogo);
                                                $isTop = $index < 2 && $hasPlayed;
                                            @endphp
                                            <tr class="{{ $isTop ? 'v3-row-top' : '' }}">
                                                <td>
                                                    <span class="v3-pos {{ $isTop ? 'is-top' : '' }}">{{ $index + 1 }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if($logoExists)
                                                            <img src="{{ filter_var($teamLogo, FILTER_VALIDATE_URL) ? $teamLogo : asset('storage/' . $teamLogo) }}"
                                                                alt="{{ $teamName }}" class="v3-team-logo">
                                                        @else
                                                            <div class="v3-team-fallback">{{ $teamAbbr }}</div>
                                                        @endif
                                                        <span class="v3-team-name" title="{{ $teamName }}">
                                                            {{ $teamName }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="v3-stat">{{ $standing->matches_played ?? 0 }}</td>
                                                <td class="v3-stat v3-stat-win">{{ $standing->wins ?? 0 }}</td>
                                                <td class="v3-stat">{{ $standing->draws ?? 0 }}</td>
                                                <td class="v3-stat v3-stat-loss">{{ $standing->losses ?? 0 }}</td>
                                                <td>
                                                    <span class="v3-gd {{ $gdValue > 0 ? 'is-pos' : ($gdValue < 0 ? 'is-neg' : 'is-neu') }}">
                                                        {{ $gdDisplay }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="v3-pts">{{ $standing->points ?? 0 }}</span>
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
                    <div class="v3-tiebreak">
                        <div class="v3-tiebreak-head">
                            <i class="bi bi-info-circle me-1"></i>
                            <span>PENENTUAN JUARA & RUNNER-UP</span>
                        </div>
                        <ol class="mb-0 ps-3">
                            @foreach($activeTournament->tie_breakers as $rule)
                                <li>{{ $rule }}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                <div class="pt-3">
                    <a href="{{ route('standings') }}" class="btn-action-secondary w-100 v3-action">
                        <i class="bi bi-table"></i>
                        <span>VIEW FULL STANDINGS</span>
                    </a>
                </div>
            @else
                <div class="text-center py-4 v3-empty">
                    <i class="bi bi-bar-chart fs-4 d-block mb-1"></i>
                    <span>No group standings available</span>
                </div>
            @endif
        </div>
    </div>
@else
    @if($activeTournament)
        <div class="app-card v3-card">
            <div class="app-card-header v3-card-header">
                <h3 class="app-card-title v3-card-title">
                    <span class="v3-beacon"></span>
                    <i class="bi bi-diagram-3"></i>
                    <span>TOURNAMENT FORMAT</span>
                </h3>
                <span class="v3-hud-tag">KNOCKOUT</span>
            </div>
            <div class="app-card-body">
                <div class="v3-format-panel">
                    <div class="v3-format-glow"></div>
                    <div class="v3-format-body">
                        <div class="v3-format-icon">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <div class="v3-format-text">
                            <div class="v3-format-meta">
                                <span class="v3-sc-lbl">FORMAT</span>
                                <span class="v3-format-badge">SINGLE ELIMINATION</span>
                            </div>
                            <h6 class="v3-format-name">{{ $activeTournament->name }}</h6>
                            <p class="v3-format-desc">
                                Turnamen sistem gugur (cup). Klasemen grup tidak berlaku —
                                pemenang ditentukan langsung dari hasil tiap pertandingan.
                            </p>
                            <span class="v3-format-hint">
                                <i class="bi bi-diagram-3 me-1"></i>
                                Ikuti progres bagan knockout pada jadwal turnamen.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif

    <style>
        /* ==================================================================
           ULTRA ANALYTICS ENGINE v3.0 — home standings & tournament format
           ================================================================== */
        .v3-home-standings {
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

        .v3-home-standings .v3-card {
            background: linear-gradient(145deg, var(--v3-card), var(--v3-surface));
            border: 1px solid var(--v3-border);
            border-radius: var(--v3-radius-md);
            overflow: hidden;
            color: var(--v3-text-main);
            font-family: var(--v3-font-sans);
        }

        .v3-home-standings .v3-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-bottom: 1px solid var(--v3-border);
            background: var(--v3-surface);
        }

        .v3-home-standings .v3-card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            font-size: 0.95rem;
            font-weight: 900;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: -0.01em;
            color: var(--v3-text-main);
            text-shadow: 0 0 18px rgba(0, 255, 135, 0.25);
        }

        .v3-home-standings .v3-card-title i { color: var(--v3-neon-green); }

        .v3-home-standings .v3-beacon {
            width: 8px;
            height: 8px;
            background: var(--v3-neon-green);
            border-radius: 50%;
            box-shadow: 0 0 12px var(--v3-neon-green);
            animation: v3HsBeacon 1.2s infinite alternate;
            flex-shrink: 0;
        }

        @keyframes v3HsBeacon {
            from { opacity: 0.3; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1.2); }
        }

        .v3-home-standings .v3-hud-tag {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--v3-text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 45%;
        }

        .v3-home-standings .v3-group-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--v3-text-sub);
        }

        .v3-home-standings .v3-group-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 4px;
            background: var(--v3-neon-green);
            color: #000;
            font-size: 10px;
            font-weight: 900;
        }

        .v3-home-standings .v3-table-wrap {
            border: 1px solid var(--v3-border) !important;
            border-radius: var(--v3-radius-sm);
            overflow: hidden;
        }

        .v3-home-standings .v3-table {
            margin: 0;
            width: 100%;
            font-size: 13px;
            background: transparent;
            color: var(--v3-text-main);
        }

        .v3-home-standings .v3-table thead th {
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

        .v3-home-standings .v3-table thead th:nth-child(2) { text-align: left; }

        .v3-home-standings .v3-table tbody td {
            padding: 10px 8px;
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid var(--v3-border);
            color: var(--v3-text-sub);
        }

        .v3-home-standings .v3-table tbody td:nth-child(2) { text-align: left; }

        .v3-home-standings .v3-table tbody tr { transition: background-color 150ms ease; }
        .v3-home-standings .v3-table tbody tr:hover { background: rgba(0, 255, 135, 0.04); }
        .v3-home-standings .v3-table tbody tr:last-child td { border-bottom: none; }

        .v3-home-standings .v3-row-top td:first-child { box-shadow: inset 3px 0 0 var(--v3-neon-green); }

        .v3-home-standings .v3-pos {
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

        .v3-home-standings .v3-pos.is-top {
            color: var(--v3-neon-green);
            border-color: var(--v3-border-glow);
            background: rgba(0, 255, 135, 0.08);
        }

        .v3-home-standings .v3-team-logo {
            width: 30px;
            height: 30px;
            border-radius: var(--v3-radius-sm);
            object-fit: cover;
            border: 1px solid var(--v3-border);
            flex-shrink: 0;
        }

        .v3-home-standings .v3-team-fallback {
            width: 30px;
            height: 30px;
            border-radius: var(--v3-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--v3-bg);
            border: 1px solid var(--v3-border);
            color: var(--v3-neon-green);
            font-weight: 800;
            font-size: 10px;
            flex-shrink: 0;
        }

        .v3-home-standings .v3-team-name {
            font-weight: 800;
            color: var(--v3-text-main);
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .v3-home-standings .v3-stat { color: var(--v3-text-sub); }
        .v3-home-standings .v3-stat-win { color: var(--v3-neon-green); font-weight: 800; }
        .v3-home-standings .v3-stat-loss { color: var(--v3-neon-pink); }

        .v3-home-standings .v3-gd {
            font-weight: 800;
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 4px;
            display: inline-block;
            font-family: var(--v3-font-mono);
        }

        .v3-home-standings .v3-gd.is-pos { background: rgba(0, 255, 135, 0.12); color: var(--v3-neon-green); }
        .v3-home-standings .v3-gd.is-neg { background: rgba(255, 0, 85, 0.12); color: var(--v3-neon-pink); }
        .v3-home-standings .v3-gd.is-neu { background: var(--v3-bg); color: var(--v3-text-sub); border: 1px solid var(--v3-border); }

        .v3-home-standings .v3-pts {
            background: rgba(0, 255, 135, 0.12);
            color: var(--v3-neon-green);
            font-weight: 900;
            font-style: italic;
            font-size: 13px;
            padding: 2px 9px;
            border-radius: 4px;
            min-width: 36px;
            display: inline-block;
            border: 1px solid var(--v3-border-glow);
        }

        /* Tie-breakers */
        .v3-home-standings .v3-tiebreak {
            margin-top: 16px;
            background: var(--v3-bg);
            border: 1px solid var(--v3-border);
            border-radius: var(--v3-radius-sm);
            padding: 12px 16px;
            font-size: 12px;
            color: var(--v3-text-sub);
        }

        .v3-home-standings .v3-tiebreak-head {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--v3-neon-green);
            margin-bottom: 6px;
        }

        .v3-home-standings .v3-empty { color: var(--v3-text-muted); }

        .v3-home-standings .v3-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--v3-surface);
            border: 1px solid var(--v3-border);
            color: var(--v3-text-main);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 11px;
            border-radius: var(--v3-radius-sm);
            padding: 10px 14px;
            text-decoration: none;
            transition: background-color 200ms ease, border-color 200ms ease, color 200ms ease, opacity 200ms ease, transform 200ms ease, box-shadow 200ms ease;
        }

        .v3-home-standings .v3-action:hover {
            border-color: var(--v3-border-glow);
            color: var(--v3-neon-green);
        }

        /* Tournament format panel */
        .v3-home-standings .v3-format-panel {
            position: relative;
            background: var(--v3-bg);
            border: 1px solid var(--v3-border);
            border-radius: var(--v3-radius-sm);
            padding: 20px;
            overflow: hidden;
        }

        .v3-home-standings .v3-format-glow {
            position: absolute;
            top: 50%;
            left: 12%;
            transform: translate(-50%, -50%);
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, rgba(0, 255, 135, 0.14) 0%, transparent 70%);
            pointer-events: none;
        }

        .v3-home-standings .v3-format-body {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: flex-start;
            gap: 18px;
        }

        .v3-home-standings .v3-format-icon {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--v3-radius-sm);
            background: rgba(0, 255, 135, 0.08);
            border: 1px solid var(--v3-border-glow);
            color: var(--v3-neon-green);
            font-size: 22px;
            box-shadow: 0 0 24px rgba(0, 255, 135, 0.18);
        }

        .v3-home-standings .v3-format-text { min-width: 0; }

        .v3-home-standings .v3-format-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 4px;
        }

        .v3-home-standings .v3-sc-lbl {
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--v3-text-muted);
        }

        .v3-home-standings .v3-format-badge {
            font-size: 9px;
            font-weight: 900;
            font-style: italic;
            letter-spacing: 1px;
            padding: 2px 8px;
            border-radius: 4px;
            color: var(--v3-neon-green);
            border: 1px solid var(--v3-border-glow);
            background: rgba(0, 255, 135, 0.08);
        }

        .v3-home-standings .v3-format-name {
            font-size: 1.1rem;
            font-weight: 900;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: -0.01em;
            margin: 0 0 6px;
            color: var(--v3-text-main);
            text-shadow: 0 0 18px rgba(0, 255, 135, 0.25);
        }

        .v3-home-standings .v3-format-desc {
            font-size: 12.5px;
            color: var(--v3-text-sub);
            margin: 0 0 8px;
        }

        .v3-home-standings .v3-format-hint {
            font-size: 11px;
            font-weight: 700;
            color: var(--v3-text-muted);
        }

        /* Responsive */
        @media (max-width: 576px) {
            .v3-home-standings .v3-card-header { flex-direction: column; align-items: flex-start; gap: 8px; }
            .v3-home-standings .v3-hud-tag { max-width: 100%; }
            .v3-home-standings .v3-format-body { flex-direction: column; }
            .v3-home-standings .v3-team-name { max-width: 110px; font-size: 12px; }
        }
    </style>
</div>