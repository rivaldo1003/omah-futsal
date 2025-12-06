<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Scorers - OFS Futsal Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #1a5fb4;
            --success: #26a269;
            --warning: #f5c211;
            --danger: #c01c28;
            --light-gray: #f8f9fa;
            --border: #e1e8ed;
        }
        
        body {
            background: var(--light-gray);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding-bottom: 2rem;
        }
        
        /* Header */
        .app-header {
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
        }
        
        .logo img {
            height: 36px;
        }
        
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary), #2d7dd2);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 1.5rem;
            border-radius: 0 0 12px 12px;
        }
        
        .tournament-badge {
            background: rgba(255,255,255,0.15);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        /* Tournament Selector */
        .tournament-selector {
            background: white;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            border: 1px solid var(--border);
        }
        
        .tournament-info {
            background: #f0f7ff;
            border-left: 4px solid var(--primary);
            padding: 0.75rem 1rem;
            border-radius: 6px;
        }
        
        /* Player Cards */
        .player-row {
            background: white;
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 0.75rem;
            padding: 1rem;
            transition: all 0.2s ease;
        }
        
        .player-row:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(26, 95, 180, 0.08);
            transform: translateY(-1px);
        }
        
        .player-rank {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        
        .rank-1 { background: linear-gradient(135deg, #FFD700, #FFA500); color: #333; }
        .rank-2 { background: linear-gradient(135deg, #C0C0C0, #A9A9A9); color: white; }
        .rank-3 { background: linear-gradient(135deg, #CD7F32, #A0522D); color: white; }
        .rank-other { background: #6c757d; color: white; }
        
        .player-avatar {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary), #2d7dd2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        
        .player-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .player-details h6 {
            font-weight: 600;
            margin-bottom: 0.125rem;
            line-height: 1.3;
        }
        
        .player-meta {
            font-size: 0.8rem;
            color: #666;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .player-team {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }
        
        .team-logo {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            object-fit: cover;
        }
        
        .jersey-badge {
            background: #e9ecef;
            color: #495057;
            padding: 0.125rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .position-badge {
            background: #f8f9fa;
            color: #495057;
            border: 1px solid #dee2e6;
            padding: 0.125rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        
        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-weight: 700;
            font-size: 1.1rem;
            line-height: 1.2;
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: #666;
            margin-top: 0.125rem;
        }
        
        .goals { color: var(--success); }
        .assists { color: var(--primary); }
        .yellow { color: var(--warning); }
        .red { color: var(--danger); }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        
        .empty-icon {
            font-size: 3rem;
            color: #adb5bd;
            margin-bottom: 1rem;
        }
        
        /* Footer */
        .page-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            text-align: center;
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.5rem;
            }
            
            .tournament-selector {
                padding: 1rem;
            }
            
            .player-row {
                padding: 0.875rem;
            }
            
            .player-avatar {
                width: 36px;
                height: 36px;
                font-size: 0.85rem;
            }
            
            .player-rank {
                width: 32px;
                height: 32px;
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
                margin-top: 0.75rem;
            }
            
            .player-meta {
                flex-wrap: wrap;
                gap: 0.375rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="app-header">
        <div class="container">
            <div class="header-content">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('images/logo-ofs.png') }}" alt="OFS Futsal Center">
                    <span>OFS Futsal Center</span>
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Home
                </a>
            </div>
        </div>
    </header>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="bi bi-trophy me-2"></i>Top Scorers
                    </h1>
                    <p class="mb-0 opacity-90">Leading goal scorers in the tournament</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    @if($activeTournament)
                        <div class="tournament-badge">
                            <div class="small opacity-80">Current Tournament</div>
                            <div class="fw-bold">{{ $activeTournament->name }}</div>
                            @if($activeTournament->season)
                                <div class="small">Season {{ $activeTournament->season }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Tournament Selector -->
        <div class="tournament-selector">
            <form method="GET" action="{{ route('top-scorers') }}">
                <div class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <label for="tournament" class="form-label fw-semibold">Select Tournament:</label>
                        <select class="form-select" id="tournament" name="tournament" onchange="this.form.submit()">
                            <option value="">All Tournaments</option>
                            @foreach($tournaments as $tournament)
                                <option value="{{ $tournament->id }}" 
                                    {{ $activeTournament && $activeTournament->id == $tournament->id ? 'selected' : '' }}>
                                    {{ $tournament->name }}
                                    @if($tournament->season)
                                        ({{ $tournament->season }})
                                    @endif
                                    - {{ ucfirst($tournament->status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-7">
                        <div class="tournament-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($activeTournament)
                                        <i class="bi bi-info-circle me-2 text-primary"></i>
                                        <span class="fw-medium">{{ $activeTournament->name }}</span>
                                        @if($activeTournament->season)
                                            <span class="text-muted">(Season {{ $activeTournament->season }})</span>
                                        @endif
                                    @else
                                        <i class="bi bi-info-circle me-2"></i>
                                        <span>Showing scorers from all tournaments</span>
                                    @endif
                                </div>
                                <span class="badge bg-primary rounded-pill">
                                    {{ $topScorers->count() }} players
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Players List -->
        <div class="mb-4">
            @if($topScorers->count() > 0)
                @foreach($topScorers as $index => $player)
                    @php
                        // Data handling
                        $playerName = $player->name ?? $player->player_name ?? 'Unknown';
                        $playerPhoto = $player->photo ?? null;
                        $jerseyNumber = $player->jersey_number ?? null;
                        $position = $player->position ?? null;
                        $goals = $player->goals ?? $player->tournament_goals ?? 0;
                        $assists = $player->assists ?? $player->tournament_assists ?? 0;
                        $yellowCards = $player->yellow_cards ?? $player->tournament_yellow_cards ?? 0;
                        $redCards = $player->red_cards ?? $player->tournament_red_cards ?? 0;

                        // Team info
                        $team = $player->team ?? null;
                        $teamName = $team->name ?? $player->team_name ?? null;
                        $teamLogo = $team->logo ?? $player->team_logo ?? null;

                        // Avatar initials
                        $nameParts = explode(' ', $playerName);
                        $initials = count($nameParts) >= 2
                            ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                            : strtoupper(substr($playerName, 0, 2));
                    @endphp

                    <div class="player-row">
                        <div class="row align-items-center">
                            <!-- Rank -->
                            <div class="col-auto">
                                <div class="player-rank rank-{{ min($index + 1, 4) }}">
                                    {{ $index + 1 }}
                                </div>
                            </div>

                            <!-- Player Info -->
                            <div class="col">
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Avatar -->
                                    <div class="player-avatar">
                                        @if($playerPhoto && Storage::disk('public')->exists($playerPhoto))
                                            <img src="{{ asset('storage/' . $playerPhoto) }}" alt="{{ $playerName }}">
                                        @else
                                            {{ $initials }}
                                        @endif
                                    </div>

                                    <!-- Details -->
                                    <div class="player-details flex-grow-1">
                                        <h6 class="mb-1">{{ $playerName }}</h6>
                                        <div class="player-meta">
                                            @if($teamName)
                                                <div class="player-team">
                                                    @if($teamLogo && Storage::disk('public')->exists($teamLogo))
                                                        <img src="{{ asset('storage/' . $teamLogo) }}" 
                                                             alt="{{ $teamName }}" class="team-logo">
                                                    @endif
                                                    <span>{{ $teamName }}</span>
                                                </div>
                                            @endif

                                            @if($position)
                                                <span class="position-badge">{{ $position }}</span>
                                            @endif

                                            @if($jerseyNumber)
                                                <span class="jersey-badge">#{{ $jerseyNumber }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="col-md-4 col-lg-3">
                                <div class="stats-grid">
                                    <div class="stat-item">
                                        <div class="stat-value goals">{{ $goals }}</div>
                                        <div class="stat-label">Goals</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value assists">{{ $assists }}</div>
                                        <div class="stat-label">Assists</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value yellow">{{ $yellowCards }}</div>
                                        <div class="stat-label">Yellow</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value red">{{ $redCards }}</div>
                                        <div class="stat-label">Red</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Summary -->
                <div class="mt-4 text-center text-muted small">
                    Showing {{ $topScorers->count() }} top scorers
                    @if($activeTournament)
                        in <strong>{{ $activeTournament->name }}</strong>
                    @endif
                </div>

            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-person-x"></i>
                    </div>
                    <h5 class="text-muted mb-2">No scorers found</h5>
                    <p class="text-muted mb-0">
                        @if($activeTournament)
                            No goals scored in <strong>{{ $activeTournament->name }}</strong> yet
                        @else
                            No player statistics available
                        @endif
                    </p>
                </div>
            @endif
        </div>
        
        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i> Back to Homepage
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} OFS Futsal Center. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tournament selector auto-submit
        document.getElementById('tournament')?.addEventListener('change', function() {
            this.form.submit();
        });
        
        // Add subtle animation to player cards on load
        document.addEventListener('DOMContentLoaded', function() {
            const playerRows = document.querySelectorAll('.player-row');
            playerRows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(10px)';
                
                setTimeout(() => {
                    row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
    </script>
</body>
</html>