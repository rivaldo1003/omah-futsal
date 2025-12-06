@extends('layouts.app')

@section('title', 'Dashboard - Omah Futsal Centre')

@section('styles')
    <style>
        /* Variables for consistency */
        :root {
            --primary-color: #1e40af;
            --secondary-color: #3b82f6;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --hover-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s ease;
        }

        /* Hero Section - More Premium */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e3a8a 100%);
            color: white;
            padding: 80px 0;
            border-radius: 20px;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,192C672,181,768,139,864,128C960,117,1056,139,1152,149.3C1248,160,1344,160,1392,160L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            opacity: 0.3;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .hero-section .lead {
            font-size: 1.3rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto 2rem;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            flex-wrap: wrap;
            margin-top: 3rem;
        }

        .hero-stat {
            text-align: center;
        }

        .hero-stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--accent-color);
        }

        .hero-stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Premium Stat Cards */
        .stat-card {
            border: none;
            border-radius: 16px;
            transition: var(--transition);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            position: relative;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--hover-shadow);
        }

        .stat-card .rounded-circle {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        /* Modern Card Design */
        .modern-card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            overflow: hidden;
        }

        .modern-card:hover {
            box-shadow: var(--hover-shadow);
        }

        .modern-card .card-header {
            background: linear-gradient(135deg, var(--light-color) 0%, #fff 100%);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        .modern-card .card-title {
            color: var(--dark-color);
            font-weight: 600;
            font-size: 1.25rem;
        }

        .modern-card .card-title i {
            color: var(--primary-color);
            margin-right: 10px;
        }

        /* Team Logo - Premium Design */
        .team-logo-premium {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
            margin-right: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            transition: var(--transition);
        }

        .team-logo-premium:hover {
            transform: scale(1.1);
        }

        /* Match List Items */
        .match-item {
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: var(--transition);
            background: white;
        }

        .match-item:hover {
            border-color: var(--secondary-color);
            background: rgba(59, 130, 246, 0.02);
        }

        .match-item .team-name {
            font-weight: 600;
            color: var(--dark-color);
        }

        .match-item .match-time {
            font-size: 0.85rem;
            color: #6b7280;
            background: var(--light-color);
            padding: 4px 12px;
            border-radius: 20px;
        }

        /* Standings Table - Premium */
        .standings-table {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .standings-table thead th {
            background: linear-gradient(135deg, var(--primary-color), #1e3a8a);
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .standings-table tbody tr {
            transition: var(--transition);
        }

        .standings-table tbody tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        .position-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
        }

        .position-1 .position-badge { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .position-2 .position-badge { background: linear-gradient(135deg, #94a3b8, #64748b); }
        .position-3 .position-badge { background: linear-gradient(135deg, #f97316, #ea580c); }

        /* Top Scorer Cards - Premium */
        .player-card-premium {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: var(--transition);
            box-shadow: var(--card-shadow);
            position: relative;
        }

        .player-card-premium:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .player-card-premium .rank-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .player-card-premium .player-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .player-card-premium .player-stats {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .player-card-premium .stat-item {
            text-align: center;
        }

        .player-card-premium .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .player-card-premium .stat-label {
            font-size: 0.8rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Quick Links - Premium */
        .quick-link-premium {
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: var(--transition);
            text-decoration: none;
            color: var(--dark-color);
            display: block;
            height: 100%;
        }

        .quick-link-premium:hover {
            transform: translateY(-5px);
            border-color: var(--secondary-color);
            box-shadow: var(--hover-shadow);
            text-decoration: none;
            color: var(--dark-color);
        }

        .quick-link-premium i {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            display: block;
        }

        .quick-link-premium h5 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .quick-link-premium p {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        /* Section Headers */
        .section-header {
            margin: 3rem 0 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(0, 0, 0, 0.05);
        }

        .section-header h3 {
            color: var(--dark-color);
            font-weight: 700;
            font-size: 1.75rem;
        }

        .section-header h3 i {
            color: var(--primary-color);
            margin-right: 10px;
        }

        /* Badge Styles */
        .badge-premium {
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 20px;
            }

            .hero-section h1 {
                font-size: 2.5rem;
            }

            .hero-stats {
                gap: 1.5rem;
            }

            .hero-stat-number {
                font-size: 2rem;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container position-relative">
            <div class="text-center">
                <h1>Omah Futsal Centre Surabaya</h1>
                <p class="lead">Liga Futsal Premium Terbaik di Surabaya — Pantau jadwal, klasemen, top score, dan statistik tim secara real-time</p>

                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-number">{{ $totalTeams }}</div>
                        <div class="hero-stat-label">Tim Bertanding</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">{{ $totalPlayers }}</div>
                        <div class="hero-stat-label">Pemain</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">{{ $totalMatches }}</div>
                        <div class="hero-stat-label">Pertandingan</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">{{ $totalGoals }}</div>
                        <div class="hero-stat-label">Total Gol</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Statistik Cepat -->
        <div class="row mb-5">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted text-uppercase small fw-bold">Total Tim</h6>
                                <h2 class="card-title display-6 fw-bold text-primary">{{ $totalTeams }}</h2>
                                <p class="text-muted small mb-0">2 Grup (A & B)</p>
                            </div>
                            <div class="rounded-circle">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted text-uppercase small fw-bold">Total Pemain</h6>
                                <h2 class="card-title display-6 fw-bold text-success">{{ $totalPlayers }}</h2>
                                <p class="text-muted small mb-0">Aktif Bertanding</p>
                            </div>
                            <div class="rounded-circle bg-success">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted text-uppercase small fw-bold">Pertandingan</h6>
                                <h2 class="card-title display-6 fw-bold text-warning">{{ $totalMatches }}</h2>
                                <p class="text-muted small mb-0">Jadwal & Riwayat</p>
                            </div>
                            <div class="rounded-circle bg-warning">
                                <i class="fas fa-futbol fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted text-uppercase small fw-bold">Total Gol</h6>
                                <h2 class="card-title display-6 fw-bold text-danger">{{ $totalGoals }}</h2>
                                <p class="text-muted small mb-0">Tercetak</p>
                            </div>
                            <div class="rounded-circle bg-danger">
                                <i class="fas fa-bullseye fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal & Klasemen -->
        <div class="row mb-5">
            <div class="col-lg-6 mb-4">
                <div class="modern-card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-calendar-alt me-2"></i> Jadwal Mendatang</h5>
                    </div>
                    <div class="card-body">
                        @if($upcomingMatches->count() > 0)
                            <div class="match-list">
                                @foreach($upcomingMatches as $match)
                                    <div class="match-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <div class="team-logo-premium" style="background: linear-gradient(135deg, {{ $match->homeTeam->primary_color ?? '#3b82f6' }}, #1e40af)">
                                                    {{ substr($match->homeTeam->name, 0, 2) }}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="team-name">{{ $match->homeTeam->name }}</span>
                                                        <span class="badge-premium bg-secondary">{{ $match->homeTeam->group }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="team-name">{{ $match->awayTeam->name }}</span>
                                                        <span class="badge-premium bg-secondary">{{ $match->awayTeam->group }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end ms-3">
                                                <span class="match-time d-block">{{ $match->match_time }}</span>
                                                <small class="text-muted">{{ $match->formatted_date }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada jadwal mendatang</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-top-0 text-end">
                        <a href="{{ route('matches.index') }}" class="btn btn-primary">
                            <i class="fas fa-calendar me-1"></i> Lihat Jadwal Lengkap
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="modern-card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-trophy me-2"></i> Top 5 Klasemen</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table standings-table mb-0">
                                <thead>
                                    <tr>
                                        <th width="70" class="text-center">Posisi</th>
                                        <th>Tim</th>
                                        <th class="text-center">M</th>
                                        <th class="text-center">M/S/K</th>
                                        <th class="text-center">Poin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topStandings as $standing)
                                        <tr class="position-{{ $standing->position }}">
                                            <td class="text-center">
                                                <div class="position-badge">
                                                    {{ $standing->position }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="team-logo-premium" style="background: linear-gradient(135deg, {{ $standing->team->primary_color ?? '#6b7280' }}, #4b5563)">
                                                        {{ substr($standing->team->name, 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $standing->team->name }}</strong>
                                                        <div class="small text-muted">Grup {{ $standing->team->group }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <strong>{{ $standing->played }}</strong>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="text-success fw-bold">{{ $standing->won }}</span> /
                                                <span class="text-warning">{{ $standing->drawn }}</span> /
                                                <span class="text-danger">{{ $standing->lost }}</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge-premium bg-primary fs-6">{{ $standing->points }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 text-end">
                        <a href="{{ route('standings.index') }}" class="btn btn-primary">
                            <i class="fas fa-chart-line me-1"></i> Lihat Klasemen Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Scorer Section -->
        <div class="section-header">
            <h3><i class="fas fa-star me-2"></i> Top Scorer Liga</h3>
        </div>

        <div class="row mb-5">
            @foreach($topScorers as $index => $player)
                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                    <div class="player-card-premium h-100">
                        <div class="rank-badge" style="background: linear-gradient(135deg, 
                            @if($index == 0) #f59e0b, #d97706
                            @elseif($index == 1) #94a3b8, #64748b
                            @elseif($index == 2) #f97316, #ea580c
                            @else #3b82f6, #1e40af
                            @endif
                        )">
                            {{ $index + 1 }}
                        </div>

                        <div class="card-body text-center pt-5 pb-4">
                            <div class="player-avatar" style="background: linear-gradient(135deg, {{ $player->team->primary_color ?? '#3b82f6' }}, #1e40af)">
                                <i class="fas fa-user"></i>
                            </div>

                            <h5 class="card-title mb-2">{{ $player->name }}</h5>
                            <p class="text-muted mb-3">
                                <i class="fas fa-shield-alt me-1"></i>{{ $player->team->name }}
                                <span class="badge-premium bg-secondary ms-2">Grup {{ $player->team->group }}</span>
                            </p>

                            <div class="player-stats">
                                <div class="stat-item">
                                    <div class="stat-value text-success">{{ $player->total_goals }}</div>
                                    <div class="stat-label">Gol</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value text-warning">{{ $player->yellow_cards }}</div>
                                    <div class="stat-label">Kuning</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value text-danger">{{ $player->red_cards }}</div>
                                    <div class="stat-label">Merah</div>
                                </div>
                            </div>

                            <a href="{{ route('players.show', $player->id) }}" class="btn btn-primary mt-4">
                                <i class="fas fa-eye me-1"></i> Lihat Profil
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Quick Links -->
        <div class="section-header">
            <h3><i class="fas fa-bolt me-2"></i> Akses Cepat</h3>
        </div>

        <div class="row mb-5">
            <div class="col-lg-3 col-md-6 mb-4">
                <a href="{{ route('matches.index') }}" class="quick-link-premium">
                    <i class="fas fa-calendar-alt text-primary"></i>
                    <h5>Jadwal Lengkap</h5>
                    <p>Semua pertandingan dan hasil lengkap</p>
                </a>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <a href="{{ route('standings.index') }}" class="quick-link-premium">
                    <i class="fas fa-trophy text-warning"></i>
                    <h5>Klasemen</h5>
                    <p>Peringkat tim dan performa grup</p>
                </a>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <a href="{{ route('top-scores.index') }}" class="quick-link-premium">
                    <i class="fas fa-chart-bar text-success"></i>
                    <h5>Statistik Pemain</h5>
                    <p>Top scorer dan statistik individu</p>
                </a>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <a href="{{ route('teams.index') }}" class="quick-link-premium">
                    <i class="fas fa-users text-info"></i>
                    <h5>Data Tim</h5>
                    <p>Profil tim dan daftar pemain</p>
                </a>
            </div>
        </div>

        <!-- Informasi Turnamen -->
        <div class="modern-card mb-5">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i> Informasi Turnamen</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-users text-white fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Format Turnamen</h6>
                                <p class="text-muted mb-0">10 Tim • 2 Grup (A & B)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-trophy text-white fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Sistem Kompetisi</h6>
                                <p class="text-muted mb-0">Grup → Semifinal → Final</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-warning me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-clock text-white fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Durasi Pertandingan</h6>
                                <p class="text-muted mb-0">50 Menit per Match</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection