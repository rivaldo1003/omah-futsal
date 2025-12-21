<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Standings</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
    :root {
        --primary: #2563eb;
        --primary-light: #3b82f6;
        --primary-dark: #1e40af;
        --secondary: #64748b;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --bg-main: #f8fafc;
        --bg-card: #ffffff;
        --border-color: #e2e8f0;
        --hover-color: #f1f5f9;
    }

    body {
        background: var(--bg-main);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: #334155;
        line-height: 1.5;
    }

    .container {
        max-width: 1200px;
        padding: 1rem;
    }

    /* Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .page-header h1 {
        font-weight: 700;
        margin: 0;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tournament-info {
        font-size: 0.875rem;
        opacity: 0.9;
        margin-top: 0.25rem;
    }

    .tournament-select {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        color: white;
        font-size: 0.875rem;
        min-width: 200px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .tournament-select:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .tournament-select:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
    }

    .tournament-select option {
        color: #1e293b;
        background: white;
    }

    /* Compact Stats */
    .stats-compact {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .stat-compact {
        background: var(--bg-card);
        border-radius: 8px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border: 1px solid var(--border-color);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-compact:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
    }

    .stat-icon.primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .stat-icon.success {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .stat-icon.warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-icon.danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .stat-content h4 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
        line-height: 1.2;
    }

    .stat-content p {
        font-size: 0.75rem;
        color: var(--secondary);
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Group Cards - Compact */
    .group-card-compact {
        background: var(--bg-card);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        transition: box-shadow 0.2s;
    }

    .group-card-compact:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .group-header-compact {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .group-title-compact {
        margin: 0;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .group-badge {
        background: var(--primary);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    /* Compact Table */
    .compact-table {
        margin: 0;
        font-size: 0.8125rem;
    }

    .compact-table thead th {
        background: var(--bg-main);
        color: var(--secondary);
        font-weight: 600;
        border-bottom: 2px solid var(--border-color);
        padding: 0.75rem 0.5rem;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        text-align: center;
        white-space: nowrap;
    }

    .compact-table thead th:first-child {
        text-align: left;
        padding-left: 1rem;
    }

    .compact-table tbody td {
        padding: 0.625rem 0.5rem;
        vertical-align: middle;
        text-align: start;
        border-bottom: 1px solid #f1f5f9;
    }

    .compact-table tbody td:first-child {
        text-align: left;
        padding-left: 1rem;
    }

    .compact-table tbody tr {
        transition: background-color 0.15s;
    }

    .compact-table tbody tr:hover {
        background-color: var(--hover-color);
    }

    /* Position Indicator */
    .position-indicator {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.75rem;
        color: black;
        margin-right: 0.75rem;
    }

    /* .pos-1 { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .pos-2 { background: linear-gradient(135deg, #64748b, #475569); }
        .pos-3 { background: linear-gradient(135deg, #92400e, #78350f); }
        .pos-4-6 { background: linear-gradient(135deg, #4b5563, #374151); }
        .pos-7-10 { background: linear-gradient(135deg, #6b7280, #4b5563); } */

    /* Team Info Compact */
    .team-info-compact {
        display: flex;
        align-items: center;
        min-width: 180px;
    }

    .team-logo-compact {
        width: 32px;
        height: 32px;
        background: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-right: 0.75rem;
        /* border: 1px solid var(--border-color); */
        flex-shrink: 0;
    }

    .team-logo-compact img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .team-abbr {
        font-weight: 700;
        color: var(--primary-dark);
        font-size: 0.75rem;
    }

    .team-name-compact {
        font-weight: 500;
        color: #334155;
        font-size: 0.875rem;
        line-height: 1.2;
        margin-bottom: 1px;
    }

    .team-meta-compact {
        font-size: 0.7rem;
        color: var(--secondary);
    }

    /* Form Indicators */
    .form-mini {
        display: flex;
        gap: 2px;
        justify-content: center;
    }

    .form-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .dot-win {
        background: var(--success);
    }

    .dot-draw {
        background: var(--warning);
    }

    .dot-loss {
        background: var(--danger);
    }

    .dot-empty {
        background: #e2e8f0;
    }

    /* Points Badge */
    .points-badge {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: var(--primary-dark);
        font-weight: 700;
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        min-width: 40px;
        text-align: center;
    }

    /* GD Indicator */
    .gd-indicator {
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
    }

    .gd-positive {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .gd-negative {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .gd-neutral {
        background: #f1f5f9;
        color: var(--secondary);
    }

    /* Qualification Indicators */
    .qual-indicator {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        margin-left: 0.5rem;
        font-size: 0.6rem;
        font-weight: 700;
    }

    .qual-q {
        background: rgba(16, 185, 129, 0.2);
        color: var(--success);
    }

    .qual-p {
        background: rgba(245, 158, 11, 0.2);
        color: var(--warning);
    }

    /* Empty State */
    .empty-state-compact {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-icon {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-title {
        color: var(--primary-dark);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: var(--secondary);
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
    }

    .tournament-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .tournament-btn {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        color: var(--primary);
        text-decoration: none;
        transition: all 0.2s;
    }

    .tournament-btn:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(37, 99, 235, 0.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .tournament-select {
            width: 100%;
        }

        .stats-compact {
            grid-template-columns: repeat(2, 1fr);
        }

        /* .compact-table thead th:nth-child(5),
            .compact-table thead th:nth-child(6),
            .compact-table tbody td:nth-child(5),
            .compact-table tbody td:nth-child(6) {
                display: none;
            } */

        .team-info-compact {
            min-width: auto;
        }

        .team-name-compact {
            font-size: 0.8125rem;
        }
    }

    @media (max-width: 576px) {
        .stats-compact {
            grid-template-columns: 1fr;
        }

        /* .compact-table thead th:nth-child(4),
            .compact-table thead th:nth-child(7),
            .compact-table tbody td:nth-child(4),
            .compact-table tbody td:nth-child(7) {
                display: none;
            } */

        .group-header-compact {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .group-card-compact {
        animation: fadeIn 0.3s ease-out;
    }
    </style>
</head>

<body>
    <div class="container py-3">
        <!-- Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="bi bi-trophy-fill"></i>
                    Tournament Standings
                </h1>
                @if(isset($selectedTournament))
                <div class="tournament-info">
                    {{ $selectedTournament->name }}
                </div>
                @endif
            </div>

            @if(isset($tournaments) && $tournaments->count() > 0)
            <select class="tournament-select" onchange="window.location.href = this.value">
                <option value="{{ route('standings') }}">Select Tournament</option>
                @foreach($tournaments as $tournament)
                <option value="{{ route('standings') }}?tournament_id={{ $tournament->id }}"
                    {{ isset($selectedTournament) && $selectedTournament->id == $tournament->id ? 'selected' : '' }}>
                    {{ $tournament->name }}
                </option>
                @endforeach
            </select>
            @endif
        </div>

        @if(isset($selectedTournament) && isset($groupedStandingsWithPosition) && $groupedStandingsWithPosition->count()
        > 0)
        <!-- Compact Stats -->
        <!-- @if(isset($tournamentStats))
                <div class="stats-compact">
                    <div class="stat-compact">
                        <div class="stat-icon primary">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div class="stat-content">
                            <h4>{{ $tournamentStats['total_matches'] ?? 0 }}</h4>
                            <p>Matches</p>
                        </div>
                    </div>
                    <div class="stat-compact">
                        <div class="stat-icon success">
                            <i class="bi bi-soccer"></i>
                        </div>
                        <div class="stat-content">
                            <h4>{{ $tournamentStats['total_goals'] ?? 0 }}</h4>
                            <p>Goals</p>
                        </div>
                    </div>
                    <div class="stat-compact">
                        <div class="stat-icon warning">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="stat-content">
                            <h4>{{ $tournamentStats['avg_goals_per_match'] ?? 0 }}</h4>
                            <p>Avg Goals</p>
                        </div>
                    </div>
                    <div class="stat-compact">
                        <div class="stat-icon danger">
                            <i class="bi bi-award"></i>
                        </div>
                        <div class="stat-content">
                            <h4>
                                @if(isset($tournamentStats['top_team']) && $tournamentStats['top_team']->team)
                                    {{ substr($tournamentStats['top_team']->team->name, 0, 3) }}
                                @else
                                    -
                                @endif
                            </h4>
                            <p>Leader</p>
                        </div>
                    </div>
                </div>
            @endif -->

        <!-- Groups -->
        @foreach($groupedStandingsWithPosition as $group => $groupStandings)
        <div class="group-card-compact">
            <div class="group-header-compact">
                <h3 class="group-title-compact">
                    <span class="group-badge">G</span>
                    Group {{ $group }}
                </h3>
                <span class="text-muted small fw-medium">{{ count($groupStandings) }} teams</span>
            </div>

            <div class="table-responsive">
                <table class="table compact-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Team</th>
                            <th>P</th>
                            <th>W</th>
                            <th>D</th>
                            <th>L</th>
                            <th>GD</th>
                            <th>PTS</th>
                            <th>Form</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupStandings as $index => $team)
                        @php
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

                        $positionClass = '';
                        if ($index == 0) $positionClass = 'pos-1';
                        elseif ($index == 1) $positionClass = 'pos-2';
                        elseif ($index == 2) $positionClass = 'pos-3';
                        elseif ($index < 6) $positionClass='pos-4-6' ; else $positionClass='pos-7-10' ; $teamAbbr='' ;
                            if (!empty($teamName)) { $words=explode(' ', $teamName);
                                            if (count($words) >= 2) {
                                                $teamAbbr = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                                            } else {
                                                $teamAbbr = strtoupper(substr($teamName, 0, 2));
                                            }
                                        }

                                        $gdValue = $team->goal_difference ?? 0;
                                        $gdClass = $gdValue > 0 ? ' gd-positive' : ($gdValue < 0 ? 'gd-negative'
                            : 'gd-neutral' ); $gdDisplay=$gdValue> 0 ? '+' . $gdValue : $gdValue;

                            $qualBadge = '';
                            if ($index < 2 && ($team->played ?? 0) > 0) {
                                $qualBadge = '<span class="qual-indicator qual-q" title="Qualified">Q</span>';
                                } elseif ($index == 2 && ($team->played ?? 0) > 0) {
                                $qualBadge = '<span class="qual-indicator qual-p" title="Play-off">P</span>';
                                }

                                $recentForm = isset($team->recent_form) ? $team->recent_form : [];
                                $formDots = '';

                                if (!empty($recentForm) && is_array($recentForm)) {
                                foreach (array_slice($recentForm, 0, 5) as $result) {
                                if ($result == 'W') {
                                $formDots .= '<span class="form-dot dot-win"></span>';
                                } elseif ($result == 'D') {
                                $formDots .= '<span class="form-dot dot-draw"></span>';
                                } elseif ($result == 'L') {
                                $formDots .= '<span class="form-dot dot-loss"></span>';
                                } else {
                                $formDots .= '<span class="form-dot dot-empty"></span>';
                                }
                                }
                                } else {
                                $formDots = '<span class="text-muted small">-</span>';
                                }
                                @endphp

                                <tr>
                                    <td class="text-center">
                                        <div class="position-indicator {{ $positionClass }}">
                                            {{ $index + 1 }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="team-info-compact">
                                            <div class="team-logo-compact">
                                                @php
                                                if ($teamLogo) {
                                                if (filter_var($teamLogo, FILTER_VALIDATE_URL)) {
                                                $logoPath = $teamLogo;
                                                } else {
                                                try {
                                                if (Storage::disk('public')->exists($teamLogo)) {
                                                $logoPath = asset('storage/' . $teamLogo);
                                                } else {
                                                $logoPath = null;
                                                }
                                                } catch (Exception $e) {
                                                $logoPath = null;
                                                }
                                                }
                                                } else {
                                                $logoPath = null;
                                                }
                                                @endphp

                                                @if($logoPath)
                                                <img src="{{ $logoPath }}" alt="{{ $teamName }}"
                                                    onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'team-abbr\'>{{ $teamAbbr }}</span>';">
                                                @else
                                                <span class="team-abbr">{{ $teamAbbr }}</span>
                                                @endif
                                            </div>

                                            <div>
                                                <div class="team-name-compact">
                                                    {{ Str::limit($teamName, 20) }}
                                                    {!! $qualBadge !!}
                                                </div>
                                                <div class="team-meta-compact">
                                                    @if(($team->played ?? 0) > 0)
                                                    {{ $team->played }} played
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="fw-bold">{{ $team->played ?? 0 }}</td>
                                    <td class="text-success fw-bold">{{ $team->won ?? 0 }}</td>
                                    <td>{{ $team->drawn ?? 0 }}</td>
                                    <td class="text-danger">{{ $team->lost ?? 0 }}</td>
                                    <td>
                                        <span class="gd-indicator {{ $gdClass }}">
                                            {{ $gdDisplay }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="points-badge">{{ $team->points ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <div class="form-mini">{!! $formDots !!}</div>
                                    </td>
                                </tr>
                                @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

        @else
        <!-- Empty State -->
        <div class="empty-state-compact">
            <div class="empty-icon">
                <i class="bi bi-bar-chart"></i>
            </div>
            <h4 class="empty-title">No Standings Available</h4>
            <p class="empty-text">
                Select a tournament to view standings
            </p>
            @if(isset($tournaments) && $tournaments->count() > 0)
            <div class="tournament-buttons">
                @foreach($tournaments as $tournament)
                <a href="{{ route('standings') }}?tournament_id={{ $tournament->id }}" class="tournament-btn">
                    {{ $tournament->name }}
                </a>
                @endforeach
            </div>
            @endif
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Auto-refresh every 30 seconds
    setTimeout(() => {
        window.location.reload();
    }, 30000);

    // Add subtle animations on load
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.group-card-compact');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
    </script>
</body>

</html>