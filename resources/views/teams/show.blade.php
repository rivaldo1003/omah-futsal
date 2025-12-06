@extends('layouts.app')

@section('title', $team->name . ' - Detail Tim')

@section('content')
    <div class="row">
        <!-- Team Info -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header" style="background-color: {{ $team->primary_color }}; color: white;">
                    <h4 class="card-title mb-0">{{ $team->name }}</h4>
                </div>
                <div class="card-body text-center">
                    <div class="team-logo mx-auto mb-3"
                        style="width: 120px; height: 120px; background-color: {{ $team->primary_color }}">
                        <i class="fas fa-users fa-4x text-white"></i>
                    </div>

                    <h4>{{ $team->name }}</h4>
                    <p class="text-muted mb-3">
                        <i class="fas fa-user-tie me-1"></i>{{ $team->coach }}
                    </p>

                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="p-2 border rounded">
                                <h6 class="mb-0 text-primary">{{ $team->matches_played }}</h6>
                                <small>Match</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 border rounded">
                                <h6 class="mb-0 text-success">{{ $team->won }}</h6>
                                <small>Menang</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-4">
                            <div class="p-2 border rounded">
                                <h6 class="mb-0 text-warning">{{ $team->drawn }}</h6>
                                <small>Seri</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 border rounded">
                                <h6 class="mb-0 text-danger">{{ $team->lost }}</h6>
                                <small>Kalah</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 border rounded">
                                <h6 class="mb-0 text-info">{{ $team->goal_difference }}</h6>
                                <small>SG</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-warning">{{ $team->points }} Poin</h2>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar"
                                style="width: {{ $team->matches_played > 0 ? ($team->points / ($team->matches_played * 3)) * 100 : 0 }}%">
                            </div>
                        </div>
                        <small class="text-muted">
                            {{ $team->matches_played > 0 ? round(($team->points / ($team->matches_played * 3)) * 100, 1) : 0 }}%
                            dari poin maksimal
                        </small>
                    </div>

                    @if($team->description)
                        <div class="border-top pt-3">
                            <h6>Deskripsi Tim</h6>
                            <p class="text-muted">{{ $team->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Statistik Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-2 border rounded">
                                <h5 class="text-success">{{ $team->goals_for }}</h5>
                                <small class="text-muted">Gol Memasukkan</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-2 border rounded">
                                <h5 class="text-danger">{{ $team->goals_against }}</h5>
                                <small class="text-muted">Gol Kemasukan</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 border rounded">
                                <h5 class="text-primary">{{ $matchStats['overall']['win_percentage'] }}%</h5>
                                <small class="text-muted">Persen Menang</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 border rounded">
                                <h5 class="text-info">{{ $playerStats['total_players'] }}</h5>
                                <small class="text-muted">Total Pemain</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Players -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star me-2"></i>Pemain Terbaik
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Top Scorer -->
                    @if($playerStats['top_scorer'])
                        <div class="mb-3">
                            <h6>Top Scorer</h6>
                            <div class="d-flex align-items-center">
                                <div class="player-avatar me-3" style="background-color: {{ $team->primary_color }}">
                                    <i class="fas fa-futbol"></i>
                                </div>
                                <div>
                                    <strong>{{ $playerStats['top_scorer']->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $playerStats['top_scorer']->total_goals }} gol •
                                        {{ $playerStats['top_scorer']->total_assists }} assist
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Most Assists -->
                    @if($playerStats['most_assists'])
                        <div class="mb-3">
                            <h6>Most Assists</h6>
                            <div class="d-flex align-items-center">
                                <div class="player-avatar me-3" style="background-color: {{ $team->primary_color }}">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div>
                                    <strong>{{ $playerStats['most_assists']->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $playerStats['most_assists']->total_assists }} assist •
                                        {{ $playerStats['most_assists']->total_goals }} gol
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Captain -->
                    @if($playerStats['captain'])
                        <div>
                            <h6>Kapten Tim</h6>
                            <div class="d-flex align-items-center">
                                <div class="player-avatar me-3" style="background-color: {{ $team->primary_color }}">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div>
                                    <strong>{{ $playerStats['captain']->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        No. {{ $playerStats['captain']->jersey_number }} •
                                        {{ $playerStats['captain']->position }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Match Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Statistik Pertandingan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <h5 class="text-success">{{ $matchStats['overall']['wins'] }}</h5>
                                <p class="mb-0 text-muted">Total Menang</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <h5 class="text-warning">{{ $matchStats['overall']['draws'] }}</h5>
                                <p class="mb-0 text-muted">Total Seri</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <h5 class="text-danger">{{ $matchStats['overall']['losses'] }}</h5>
                                <p class="mb-0 text-muted">Total Kalah</p>
                            </div>
                        </div>
                    </div>

                    <!-- Home vs Away Performance -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="card-title mb-0">Kandang</h6>
                                </div>
                                <div class="card-body text-center">
                                    <h3 class="text-primary">{{ $matchStats['home']['total'] }}</h3>
                                    <p class="text-muted mb-2">Total Pertandingan</p>
                                    <div class="d-flex justify-content-center gap-2 mb-2">
                                        <span class="badge bg-success">{{ $matchStats['home']['wins'] }} M</span>
                                        <span class="badge bg-warning">{{ $matchStats['home']['draws'] }} S</span>
                                        <span class="badge bg-danger">{{ $matchStats['home']['losses'] }} K</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success"
                                            style="width: {{ $matchStats['home']['total'] > 0 ? ($matchStats['home']['wins'] / $matchStats['home']['total']) * 100 : 0 }}%">
                                        </div>
                                        <div class="progress-bar bg-warning"
                                            style="width: {{ $matchStats['home']['total'] > 0 ? ($matchStats['home']['draws'] / $matchStats['home']['total']) * 100 : 0 }}%">
                                        </div>
                                        <div class="progress-bar bg-danger"
                                            style="width: {{ $matchStats['home']['total'] > 0 ? ($matchStats['home']['losses'] / $matchStats['home']['total']) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        Menang: {{ $matchStats['home']['win_percentage'] }}%
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="card-title mb-0">Tandang</h6>
                                </div>
                                <div class="card-body text-center">
                                    <h3 class="text-info">{{ $matchStats['away']['total'] }}</h3>
                                    <p class="text-muted mb-2">Total Pertandingan</p>
                                    <div class="d-flex justify-content-center gap-2 mb-2">
                                        <span class="badge bg-success">{{ $matchStats['away']['wins'] }} M</span>
                                        <span class="badge bg-warning">{{ $matchStats['away']['draws'] }} S</span>
                                        <span class="badge bg-danger">{{ $matchStats['away']['losses'] }} K</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success"
                                            style="width: {{ $matchStats['away']['total'] > 0 ? ($matchStats['away']['wins'] / $matchStats['away']['total']) * 100 : 0 }}%">
                                        </div>
                                        <div class="progress-bar bg-warning"
                                            style="width: {{ $matchStats['away']['total'] > 0 ? ($matchStats['away']['draws'] / $matchStats['away']['total']) * 100 : 0 }}%">
                                        </div>
                                        <div class="progress-bar bg-danger"
                                            style="width: {{ $matchStats['away']['total'] > 0 ? ($matchStats['away']['losses'] / $matchStats['away']['total']) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        Menang: {{ $matchStats['away']['win_percentage'] }}%
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Players by Position -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Daftar Pemain
                        <span class="badge bg-primary float-end">{{ $playerStats['total_players'] }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Position Tabs -->
                    <ul class="nav nav-tabs mb-3" id="positionTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all"
                                type="button">
                                Semua ({{ $team->players->count() }})
                            </button>
                        </li>
                        @foreach($playersByPosition as $position => $players)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="{{ strtolower($position) }}-tab" data-bs-toggle="tab"
                                    data-bs-target="#{{ strtolower($position) }}" type="button">
                                    {{ $position }} ({{ $players->count() }})
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Position Content -->
                    <div class="tab-content" id="positionTabContent">
                        <!-- All Players -->
                        <div class="tab-pane fade show active" id="all">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50">No.</th>
                                            <th>Nama Pemain</th>
                                            <th>Posisi</th>
                                            <th class="text-center">Gol</th>
                                            <th class="text-center">Assist</th>
                                            <th class="text-center">Kartu</th>
                                            <th class="text-center">Match</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($team->players as $player)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary">{{ $player->jersey_number }}</span>
                                                    @if($player->is_captain)
                                                        <i class="fas fa-crown text-warning"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $player->name }}</strong>
                                                    @if($player->birth_date)
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $player->age }} tahun •
                                                            {{ $player->height }} cm •
                                                            {{ $player->weight }} kg
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $player->position }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success">{{ $player->total_goals }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary">{{ $player->total_assists }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @if($player->yellow_cards > 0)
                                                        <span class="badge bg-warning me-1">{{ $player->yellow_cards }}🟨</span>
                                                    @endif
                                                    @if($player->red_cards > 0)
                                                        <span class="badge bg-danger">{{ $player->red_cards }}🟥</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{ $player->matches_played }}
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('players.show', $player->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- By Position -->
                        @foreach($playersByPosition as $position => $players)
                            <div class="tab-pane fade" id="{{ strtolower($position) }}">
                                <div class="row">
                                    @foreach($players as $player)
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="player-avatar me-3"
                                                            style="background-color: {{ $team->primary_color }}">
                                                            {{ $player->jersey_number }}
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">{{ $player->name }}</h6>
                                                            <p class="text-muted mb-1">
                                                                No. {{ $player->jersey_number }}
                                                                @if($player->is_captain)
                                                                    <span class="badge bg-warning ms-2">Kapten</span>
                                                                @endif
                                                            </p>
                                                            <div class="d-flex gap-2">
                                                                <span class="badge bg-success">{{ $player->total_goals }} Gol</span>
                                                                <span class="badge bg-primary">{{ $player->total_assists }}
                                                                    Assist</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <a href="{{ route('players.show', $player->id) }}"
                                                            class="btn btn-sm btn-outline-primary w-100">
                                                            <i class="fas fa-chart-line me-1"></i>Lihat Statistik
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Matches -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Riwayat Pertandingan
                    </h5>
                </div>
                <div class="card-body">
                    @if($matches->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="120">Tanggal</th>
                                        <th>Pertandingan</th>
                                        <th class="text-center">Hasil</th>
                                        <th>Venue</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matches as $match)
                                        <tr>
                                            <td>
                                                <small class="text-muted d-block">{{ $match->match_date->format('d/m/Y') }}</small>
                                                <strong>{{ $match->match_time }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="team-logo me-2"
                                                        style="background-color: {{ $match->homeTeam->primary_color }}">
                                                        {{ substr($match->homeTeam->name, 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $match->homeTeam->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">vs {{ $match->awayTeam->name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($match->status == 'Completed' && !is_null($match->home_score) && !is_null($match->away_score))
                                                    <h5 class="mb-0">
                                                        <span class="badge bg-dark">{{ $match->home_score }}</span>
                                                        <span class="mx-1">-</span>
                                                        <span class="badge bg-dark">{{ $match->away_score }}</span>
                                                    </h5>
                                                    <small>
                                                        @if($match->home_score > $match->away_score)
                                                            @if($match->home_team_id == $team->id)
                                                                <span class="text-success">Menang</span>
                                                            @else
                                                                <span class="text-danger">Kalah</span>
                                                            @endif
                                                        @elseif($match->away_score > $match->home_score)
                                                            @if($match->away_team_id == $team->id)
                                                                <span class="text-success">Menang</span>
                                                            @else
                                                                <span class="text-danger">Kalah</span>
                                                            @endif
                                                        @else
                                                            <span class="text-warning">Seri</span>
                                                        @endif
                                                    </small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $match->venue->name }}</small>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                            @if($match->status == 'Completed') bg-success
                                                            @elseif($match->status == 'Ongoing') bg-warning
                                                            @elseif($match->status == 'Cancelled') bg-danger
                                                            @else bg-info
                                                            @endif">
                                                    {{ $match->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('matches.show', $match->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $matches->withQueryString()->links() }}
                        </div>
                    @else
                        <p class="text-muted text-center">Belum ada pertandingan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .team-card {
            transition: transform 0.3s ease;
        }

        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .player-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }
    </style>

    @push('scripts')
        <script>
            // Activate Bootstrap tabs
            var triggerTabList = [].slice.call(document.querySelectorAll('#positionTab button'))
            triggerTabList.forEach(function (triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl)

                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault()
                    tabTrigger.show()
                })
            })
        </script>
    @endpush
@endsection