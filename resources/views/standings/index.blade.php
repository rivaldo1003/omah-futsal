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

        .tournament-type-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        /* Knockout Bracket Styles */
        .bracket-container {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .bracket-title {
            color: var(--primary-dark);
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary-light);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .round-section {
            margin-bottom: 2rem;
        }

        .round-title {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary);
        }

        .match-card-bracket {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            transition: all 0.2s;
        }

        .match-card-bracket:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .match-teams {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .team-info-bracket {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        .team-logo-bracket {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        .team-logo-bracket img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .team-name-bracket {
            font-weight: 500;
            color: #334155;
            font-size: 0.9rem;
        }

        .team-abbr-bracket {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 0.8rem;
        }

        .match-score {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 80px;
            justify-content: center;
        }

        .score-badge-bracket {
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.9rem;
            min-width: 60px;
            text-align: center;
        }

        .score-badge-bracket.live {
            background: var(--danger);
            animation: pulse 2s infinite;
        }

        .score-badge-bracket.draw {
            background: var(--warning);
        }

        .vs-text {
            color: var(--secondary);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .match-status-bracket {
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .status-scheduled {
            background: rgba(100, 116, 139, 0.1);
            color: var(--secondary);
        }

        .status-ongoing {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .status-completed {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .match-details-bracket {
            font-size: 0.75rem;
            color: var(--secondary);
            margin-top: 0.5rem;
            display: flex;
            gap: 1rem;
        }

        /* Responsive for bracket */
        @media (max-width: 768px) {
            .bracket-container {
                padding: 1rem;
            }

            .match-teams {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }

            .team-info-bracket {
                justify-content: flex-start;
            }

            .match-score {
                justify-content: center;
                margin: 0.5rem 0;
            }

            .match-details-bracket {
                flex-direction: column;
                gap: 0.25rem;
            }
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.8; }
            100% { opacity: 1; }
        }

        /* Empty state for knockout */
        .empty-knockout {
            text-align: center;
            padding: 3rem 1rem;
            background: var(--bg-main);
            border-radius: 8px;
            border: 2px dashed var(--border-color);
        }

        .empty-knockout i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
            opacity: 0.5;
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
                    @if($selectedTournament)
                        <span class="tournament-type-badge">
                            {{ strtoupper(str_replace('_', ' ', $selectedTournament->type)) }}
                        </span>
                    @endif
                </h1>
                @if(isset($selectedTournament))
                <div class="tournament-info">
                    {{ $selectedTournament->name }}
                    @if($selectedTournament->status == 'ongoing')
                        <span class="badge bg-success ms-1">Active</span>
                    @elseif($selectedTournament->status == 'upcoming')
                        <span class="badge bg-warning ms-1">Upcoming</span>
                    @else
                        <span class="badge bg-secondary ms-1">Completed</span>
                    @endif
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
                    @if($tournament->status == 'ongoing')
                        (Active)
                    @endif
                </option>
                @endforeach
            </select>
            @endif
        </div>

        @if(isset($selectedTournament))
            @if($tournamentType == 'knockout')
                <!-- Knockout Bracket View -->
                <div class="bracket-container">
                    <h3 class="bracket-title">
                        <i class="bi bi-diagram-3"></i>
                        Knockout Bracket
                    </h3>
                    
                    @if($knockoutBracket && count($knockoutBracket) > 0)
                        @foreach($knockoutBracket as $round => $matches)
                        <div class="round-section">
                            <h4 class="round-title">
                                {{ ucfirst(str_replace('_', ' ', $round)) }}
                            </h4>
                            
                            @foreach($matches as $match)
                            <div class="match-card-bracket">
                                <div class="match-teams">
                                    <!-- Home Team -->
                                    <div class="team-info-bracket">
                                        @if($match['home_team'])
                                        <div class="team-logo-bracket">
                                            @if($match['home_team']['logo'])
                                                @php
                                                $logoPath = null;
                                                if (filter_var($match['home_team']['logo'], FILTER_VALIDATE_URL)) {
                                                    $logoPath = $match['home_team']['logo'];
                                                } else {
                                                    try {
                                                        if (Storage::disk('public')->exists($match['home_team']['logo'])) {
                                                            $logoPath = asset('storage/' . $match['home_team']['logo']);
                                                        }
                                                    } catch (Exception $e) {}
                                                }
                                                @endphp
                                                
                                                @if($logoPath)
                                                    <img src="{{ $logoPath }}" alt="{{ $match['home_team']['name'] }}"
                                                         onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'team-abbr-bracket\'>{{ substr($match['home_team']['name'], 0, 2) }}</span>'">
                                                @else
                                                    <span class="team-abbr-bracket">
                                                        {{ substr($match['home_team']['name'], 0, 2) }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="team-abbr-bracket">
                                                    {{ substr($match['home_team']['name'], 0, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="team-name-bracket">{{ $match['home_team']['name'] }}</div>
                                            @if($match['status'] == 'completed' && $match['winner'] == 'home')
                                            <small class="text-success fw-bold">Winner</small>
                                            @endif
                                        </div>
                                        @else
                                        <div class="text-muted">TBD</div>
                                        @endif
                                    </div>
                                    
                                    <!-- Score -->
                                    <div class="match-score">
                                        @if($match['status'] == 'completed')
                                            <span class="score-badge-bracket {{ $match['home_team'] && $match['away_team'] && $match['home_team']['score'] == $match['away_team']['score'] ? 'draw' : '' }}">
                                                {{ $match['home_team']['score'] ?? 0 }} - {{ $match['away_team']['score'] ?? 0 }}
                                            </span>
                                        @elseif($match['status'] == 'ongoing')
                                            <span class="score-badge-bracket live">
                                                {{ $match['home_team']['score'] ?? 0 }} - {{ $match['away_team']['score'] ?? 0 }}
                                            </span>
                                        @else
                                            <span class="vs-text">VS</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Away Team -->
                                    <div class="team-info-bracket" style="flex-direction: row-reverse; text-align: right;">
                                        @if($match['away_team'])
                                        <div class="team-logo-bracket">
                                            @if($match['away_team']['logo'])
                                                @php
                                                $logoPath = null;
                                                if (filter_var($match['away_team']['logo'], FILTER_VALIDATE_URL)) {
                                                    $logoPath = $match['away_team']['logo'];
                                                } else {
                                                    try {
                                                        if (Storage::disk('public')->exists($match['away_team']['logo'])) {
                                                            $logoPath = asset('storage/' . $match['away_team']['logo']);
                                                        }
                                                    } catch (Exception $e) {}
                                                }
                                                @endphp
                                                
                                                @if($logoPath)
                                                    <img src="{{ $logoPath }}" alt="{{ $match['away_team']['name'] }}"
                                                         onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'team-abbr-bracket\'>{{ substr($match['away_team']['name'], 0, 2) }}</span>'">
                                                @else
                                                    <span class="team-abbr-bracket">
                                                        {{ substr($match['away_team']['name'], 0, 2) }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="team-abbr-bracket">
                                                    {{ substr($match['away_team']['name'], 0, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="team-name-bracket">{{ $match['away_team']['name'] }}</div>
                                            @if($match['status'] == 'completed' && $match['winner'] == 'away')
                                            <small class="text-success fw-bold">Winner</small>
                                            @endif
                                        </div>
                                        @else
                                        <div class="text-muted">TBD</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Match Details -->
                                <div class="match-details-bracket">
                                    <span class="match-status-bracket status-{{ $match['status'] }}">
                                        {{ ucfirst($match['status']) }}
                                    </span>
                                    @if($match['date'])
                                    <span>
                                        <i class="bi bi-calendar"></i>
                                        {{ \Carbon\Carbon::parse($match['date'])->format('M d') }}
                                    </span>
                                    @endif
                                    @if($match['time'])
                                    <span>
                                        <i class="bi bi-clock"></i>
                                        {{ date('H:i', strtotime($match['time'])) }}
                                    </span>
                                    @endif
                                    <a href="{{ route('matches.show', $match['id']) }}" class="text-primary">
                                        <i class="bi bi-eye"></i> Details
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    @else
                        <div class="empty-knockout">
                            <i class="bi bi-diagram-3"></i>
                            <h4>No Knockout Matches Yet</h4>
                            <p class="text-muted">The knockout bracket will be displayed here once matches are scheduled.</p>
                        </div>
                    @endif
                </div>

            @elseif(in_array($tournamentType, ['league', 'group_knockout']))
                <!-- Group Standings View (existing code remains) -->
                <!-- Your existing group standings HTML code goes here -->
                <!-- ... -->
                
            @else
                <div class="empty-state-compact">
                    <div class="empty-icon">
                        <i class="bi bi-bar-chart"></i>
                    </div>
                    <h4 class="empty-title">Unsupported Tournament Type</h4>
                    <p class="empty-text">
                        This tournament type doesn't have standings display.
                    </p>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state-compact">
                <div class="empty-icon">
                    <i class="bi bi-bar-chart"></i>
                </div>
                <h4 class="empty-title">No Tournament Selected</h4>
                <p class="empty-text">
                    Select a tournament to view standings or bracket
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
    </script>
</body>
</html>