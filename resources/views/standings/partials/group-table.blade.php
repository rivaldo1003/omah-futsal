{{-- Group and league table. Receives: $group, $groupStandings, $selectedTournament. --}}
<div class="v3-standings-group {{ $selectedTournament->type === 'league' ? 'is-league' : 'is-group' }}">
    <div class="standings-group-card h-100">
        {{-- Module Header (v3) --}}
        <div class="v3-sg-header">
            <div class="v3-sg-title-wrap">
                <h3 class="standings-group-title v3-sg-title">
                    <span class="standings-group-badge v3-sg-badge">{{ $selectedTournament->type === 'league' ? 'L' : substr($group, 0, 1) }}</span>
                    {{ $selectedTournament->type === 'league' ? 'Klasemen utama' : 'Grup ' . $group }}
                </h3>
            </div>
            <span class="v3-sg-meta">
                <span class="v3-sc-val">{{ count($groupStandings) }} tim</span>
            </span>
        </div>

        <p class="v3-sg-scroll-hint">
            <i class="bi bi-arrow-left-right" aria-hidden="true"></i>
            Geser tabel ke samping untuk melihat semua statistik.
        </p>
        <div class="table-responsive v3-sg-table-wrap" tabindex="0" role="region"
            aria-label="{{ $selectedTournament->type === 'league' ? 'Klasemen utama' : 'Klasemen grup ' . $group }}; geser ke samping untuk melihat semua kolom">
            <table class="table standings-table v3-sg-table">
                <caption class="visually-hidden">
                    {{ $selectedTournament->type === 'league' ? 'Klasemen utama' : 'Klasemen grup ' . $group }}
                </caption>
                <thead>
                    <tr>
                        <th scope="col" title="Posisi">#</th>
                        <th scope="col">Tim</th>
                        <th scope="col" title="Dimainkan">P</th>
                        <th scope="col" title="Menang">W</th>
                        <th scope="col" title="Seri">D</th>
                        <th scope="col" title="Kalah">L</th>
                        <th scope="col" title="Selisih gol">GD</th>
                        <th scope="col" title="Poin">PTS</th>
                        <th scope="col" title="Hasil lima pertandingan terakhir">Form</th>
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

                            // Highlight qualifying positions using the tournament's configured quota.
                            $qualifyingPlaces = $selectedTournament->type === 'group_knockout'
                                ? max(1, (int) ($selectedTournament->qualify_per_group ?? 2))
                                : 2;
                            $groupKey = strtoupper(trim((string) $group));
                            $manualQualifierIds = $selectedTournament->settings['group_knockout_qualifier_overrides'][$groupKey] ?? null;
                            $teamId = (int) ($team->team_id ?? ($team->team->id ?? 0));
                            $manualQualifier = $selectedTournament->type === 'group_knockout'
                                && is_array($manualQualifierIds)
                                && in_array($teamId, array_map('intval', $manualQualifierIds), true);
                            $isTop = $selectedTournament->type === 'league'
                                ? $index === 0
                                : ($selectedTournament->type === 'group_knockout' && is_array($manualQualifierIds)
                                    ? $manualQualifier
                                    : $index < $qualifyingPlaces);
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
                                        @if($manualQualifier)
                                            <span class="badge rounded-pill text-bg-warning">Pilihan panitia</span>
                                        @endif
                                        @if(($team->played ?? 0) > 0)
                                            <div class="standings-team-meta">{{ $team->played }} laga</div>
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
                                            <span class="standings-form-dot standings-dot-win" role="img" aria-label="Menang" title="Menang"></span>
                                            @elseif($result == 'D')
                                            <span class="standings-form-dot standings-dot-draw" role="img" aria-label="Seri" title="Seri"></span>
                                            @elseif($result == 'L')
                                            <span class="standings-form-dot standings-dot-loss" role="img" aria-label="Kalah" title="Kalah"></span>
                                            @else
                                            <span class="standings-form-dot standings-dot-empty" role="img" aria-label="Hasil belum tersedia" title="Hasil belum tersedia"></span>
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
            flex: 0 0 20px;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            color: #ffffff;
            background: #07824e;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 800;
        }

        .v3-standings-group .v3-sg-meta {
            display: flex;
            flex: 0 0 auto;
            align-items: flex-end;
            text-align: right;
        }

        .v3-standings-group .v3-sc-lbl {
            color: var(--table-muted);
            font-size: 11px;
            font-weight: 500;
        }

        .v3-standings-group .v3-sc-val {
            color: var(--table-muted);
            font-size: 12px;
            font-weight: 600;
            line-height: 1.2;
        }

        .v3-standings-group .v3-sg-table-wrap {
            max-width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-x: contain;
            scrollbar-color: #cacacb transparent;
            scrollbar-width: thin;
        }

        .v3-standings-group .v3-sg-scroll-hint {
            display: none;
            margin: 0 12px 8px;
            color: var(--table-muted);
            font-size: 11px;
            line-height: 1.4;
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
            padding: 10px 8px;
            color: var(--table-muted);
            background: var(--table-cloud);
            border-bottom: 1px solid var(--table-border);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-align: center;
            white-space: nowrap;
            text-transform: uppercase;
        }

        .v3-standings-group .v3-sg-table thead th:first-child {
            width: 40px;
        }

        .v3-standings-group .v3-sg-table thead th:nth-child(2) {
            min-width: 180px;
            text-align: left;
        }

        .v3-standings-group .v3-sg-table tbody td {
            padding: 10px 8px;
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
            width: 24px;
            height: 24px;
            color: var(--table-charcoal);
            background: #f5f5f5;
            border: 1px solid var(--table-border);
            border-radius: 4px;
            font-size: 11px;
            font-style: italic;
            font-weight: 800;
        }

        .v3-standings-group .v3-sg-pos.is-top {
            color: #07824e;
            background: var(--table-success-soft);
            border-color: #c8e7d7;
        }

        .v3-standings-group .standings-team-info {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 180px;
        }

        .v3-standings-group .standings-team-logo {
            display: flex;
            flex: 0 0 30px;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            overflow: hidden;
            background: var(--table-cloud);
            border: 1px solid var(--table-border);
            border-radius: 4px;
        }

        .v3-standings-group .standings-team-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .v3-standings-group .standings-team-abbr {
            color: #07824e;
            font-size: 10px;
            font-weight: 800;
        }

        .v3-standings-group .v3-sg-name {
            overflow: hidden;
            color: var(--table-ink);
            font-size: 13px;
            font-weight: 600;
            line-height: 1.3;
            text-overflow: ellipsis;
            font-size: 13px;
            font-weight: 700;
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
            padding: 2px 9px;
            color: #07824e;
            background: var(--table-success-soft);
            border: 1px solid #c8e7d7;
            border-radius: 4px;
            font-size: 13px;
            font-style: italic;
            font-weight: 800;
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
            .v3-standings-group .v3-sg-scroll-hint {
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .v3-standings-group .v3-sg-table-wrap:focus-visible {
                outline: 2px solid #07824e;
                outline-offset: 2px;
            }

            .v3-standings-group .v3-sg-header {
                min-height: 62px;
                padding: 12px 14px;
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
