@extends('layouts.app')

@section('title', 'Data Tim - Omah Futsal Centre')

@section('content')
<!-- Header dengan Statistik -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>Data Tim Futsal
                </h4>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h5 class="text-primary">{{ $stats['total_teams'] }}</h5>
                            <p class="mb-0">Total Tim Aktif</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h5 class="text-success">{{ $stats['total_players'] }}</h5>
                            <p class="mb-0">Total Pemain</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h5 class="text-warning">{{ round($stats['avg_players_per_team'], 1) }}</h5>
                            <p class="mb-0">Rata-rata Pemain/Tim</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h5 class="text-info">{{ $stats['most_wins']->won ?? 0 }}</h5>
                            <p class="mb-0">Kemenangan Terbanyak</p>
                            @if($stats['most_wins'])
                                <small class="text-muted">{{ $stats['most_wins']->name }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Filter dan Search -->
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Filter & Pencarian</h5>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form method="GET" action="{{ route('teams.index') }}">
                    <div class="mb-3">
                        <label for="search" class="form-label">Cari Tim</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ $search }}" placeholder="Nama tim atau pelatih...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="sort" class="form-label">Urutkan Berdasarkan</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="points" {{ $sort == 'points' ? 'selected' : '' }}>Poin</option>
                            <option value="name" {{ $sort == 'name' ? 'selected' : '' }}>Nama Tim</option>
                            <option value="matches_played" {{ $sort == 'matches_played' ? 'selected' : '' }}>Jumlah Pertandingan</option>
                            <option value="won" {{ $sort == 'won' ? 'selected' : '' }}>Kemenangan</option>
                            <option value="goals_for" {{ $sort == 'goals_for' ? 'selected' : '' }}>Gol Memasukkan</option>
                            <option value="goals_against" {{ $sort == 'goals_against' ? 'selected' : '' }}>Gol Kemasukan</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="order" class="form-label">Urutan</label>
                        <select name="order" id="order" class="form-select">
                            <option value="desc" {{ $order == 'desc' ? 'selected' : '' }}>Tertinggi ke Terendah</option>
                            <option value="asc" {{ $order == 'asc' ? 'selected' : '' }}>Terendah ke Tertinggi</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Terapkan
                        </button>
                        <a href="{{ route('teams.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Top Performers -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy me-2"></i>Top Performers
                </h5>
            </div>
            <div class="card-body">
                <!-- Most Goals -->
                @if($stats['most_goals'])
                    <div class="mb-3">
                        <h6>Serangan Terbaik</h6>
                        <div class="d-flex align-items-center">
                            <div class="team-logo me-2" style="background-color: {{ $stats['most_goals']->primary_color }}">
                                {{ substr($stats['most_goals']->name, 0, 2) }}
                            </div>
                            <div>
                                <strong>{{ $stats['most_goals']->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $stats['most_goals']->goals_for }} gol</small>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Best Defense -->
                @if($stats['best_defense'])
                    <div>
                        <h6>Pertahanan Terbaik</h6>
                        <div class="d-flex align-items-center">
                            <div class="team-logo me-2" style="background-color: {{ $stats['best_defense']->primary_color }}">
                                {{ substr($stats['best_defense']->name, 0, 2) }}
                            </div>
                            <div>
                                <strong>{{ $stats['best_defense']->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $stats['best_defense']->goals_against }} gol kemasukan</small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Recent Matches -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Pertandingan Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if($recentMatches->count() > 0)
                    <div class="list-group">
                        @foreach($recentMatches as $match)
                            <a href="{{ route('matches.show', $match->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">{{ $match->match_date->format('d/m') }}</small>
                                        <h6 class="mb-1">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</h6>
                                    </div>
                                    @if($match->status == 'Completed')
                                        <span class="badge bg-dark">
                                            {{ $match->home_score }} - {{ $match->away_score }}
                                        </span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">Tidak ada pertandingan</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Teams Grid -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Tim</h5>
                    <span class="badge bg-primary">Total: {{ $teams->total() }}</span>
                </div>
            </div>
            <div class="card-body">
                @if($teams->count() > 0)
                    <div class="row">
                        @foreach($teams as $team)
                            <div class="col-md-4 mb-4">
                                <div class="card team-card h-100" 
                                     style="border-top: 4px solid {{ $team->primary_color }}">
                                    <div class="card-body">
                                        <!-- Team Header -->
                                        <div class="text-center mb-3">
                                            <div class="team-logo mx-auto mb-3" 
                                                 style="width: 80px; height: 80px; background-color: {{ $team->primary_color }}">
                                                <i class="fas fa-users fa-3x text-white"></i>
                                            </div>
                                            <h4 class="card-title">{{ $team->name }}</h4>
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-user-tie me-1"></i>{{ $team->coach }}
                                            </p>
                                        </div>
                                        
                                        <!-- Team Stats -->
                                        <div class="row text-center mb-3">
                                            <div class="col-4">
                                                <div class="p-2 border rounded">
                                                    <h6 class="mb-0 text-primary">{{ $team->matches_played }}</h6>
                                                    <small>Match</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 border rounded">
                                                    <h6 class="mb-0 text-success">{{ $team->won }}</h6>
                                                    <small>Menang</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 border rounded">
                                                    <h6 class="mb-0 text-danger">{{ $team->lost }}</h6>
                                                    <small>Kalah</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Goals Stats -->
                                        <div class="row text-center mb-3">
                                            <div class="col-6">
                                                <div class="p-2 border rounded">
                                                    <h6 class="mb-0 text-success">{{ $team->goals_for }}</h6>
                                                    <small>GM</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2 border rounded">
                                                    <h6 class="mb-0 text-danger">{{ $team->goals_against }}</h6>
                                                    <small>GK</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Points and Players -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <span class="badge bg-warning fs-6">{{ $team->points }} Poin</span>
                                            </div>
                                            <div>
                                                <span class="badge bg-info">{{ $team->players_count }} Pemain</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Action Buttons -->
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('teams.show', $team->id) }}" class="btn btn-primary">
                                                <i class="fas fa-eye me-1"></i>Detail Tim
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $teams->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                        <h4>Tidak ada tim</h4>
                        <p class="text-muted">Tidak ada tim yang sesuai dengan pencarian.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Teams Table View (Alternative) -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-table me-2"></i>Tabel Statistik Tim
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40">#</th>
                                <th>Tim</th>
                                <th class="text-center">M</th>
                                <th class="text-center">M</th>
                                <th class="text-center">S</th>
                                <th class="text-center">K</th>
                                <th class="text-center">GM</th>
                                <th class="text-center">GK</th>
                                <th class="text-center">SG</th>
                                <th class="text-center">Poin</th>
                                <th class="text-center">%</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teams as $index => $team)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="team-logo me-3" style="background-color: {{ $team->primary_color }}">
                                                {{ substr($team->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $team->name }}</h6>
                                                <small class="text-muted">{{ $team->coach }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ $team->matches_played }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $team->won }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning">{{ $team->drawn }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $team->lost }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $team->goals_for }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $team->goals_against }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $team->goal_difference >= 0 ? 'bg-info' : 'bg-danger' }}">
                                            {{ $team->goal_difference > 0 ? '+' : '' }}{{ $team->goal_difference }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <strong class="fs-5">{{ $team->points }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-dark">
                                            {{ $team->matches_played > 0 ? round(($team->points / ($team->matches_played * 3)) * 100, 1) : 0 }}%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('teams.show', $team->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection