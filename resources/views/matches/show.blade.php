@extends('layouts.app')

@section('title', $player->name . ' - Detail Pemain')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profil Pemain</h5>
                </div>
                <div class="card-body text-center">
                    <div class="player-avatar mx-auto mb-3" style="background-color: {{ $player->team->primary_color }}">
                        <i class="fas fa-user fa-3x"></i>
                    </div>
                    <h4>{{ $player->name }}</h4>
                    <p class="text-muted mb-2">
                        <i class="fas fa-users me-1"></i> {{ $player->team->name }}
                    </p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-primary">No. {{ $player->jersey_number }}</span>
                        <span class="badge bg-info">{{ $player->position }}</span>
                        @if($player->is_captain)
                            <span class="badge bg-warning"><i class="fas fa-crown"></i> Kapten</span>
                        @endif
                    </div>

                    <div class="row text-start">
                        <div class="col-6">
                            <p class="mb-1"><strong>Usia:</strong></p>
                            <p class="mb-1"><strong>Tinggi:</strong></p>
                            <p class="mb-1"><strong>Berat:</strong></p>
                            <p class="mb-1"><strong>Pertandingan:</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1">{{ $player->age ? $player->age . ' tahun' : '-' }}</p>
                            <p class="mb-1">{{ $player->height ? $player->height . ' cm' : '-' }}</p>
                            <p class="mb-1">{{ $player->weight ? $player->weight . ' kg' : '-' }}</p>
                            <p class="mb-1">{{ $player->matches_played }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tim -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tim</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="team-logo" style="background-color: {{ $player->team->primary_color }}">
                            {{ substr($player->team->name, 0, 2) }}
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $player->team->name }}</h6>
                            <small class="text-muted">{{ $player->team->coach }}</small>
                        </div>
                    </div>
                    <a href="{{ route('teams.show', $player->team_id) }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-eye me-1"></i> Lihat Detail Tim
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Statistik -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="p-3 border rounded">
                                <h3 class="text-primary">{{ $player->total_goals }}</h3>
                                <p class="mb-0 text-muted">Total Gol</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 border rounded">
                                <h3 class="text-success">{{ $player->total_assists }}</h3>
                                <p class="mb-0 text-muted">Assist</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 border rounded">
                                <h3 class="text-warning">{{ $player->yellow_cards }}</h3>
                                <p class="mb-0 text-muted">Kartu Kuning</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 border rounded">
                                <h3 class="text-danger">{{ $player->red_cards }}</h3>
                                <p class="mb-0 text-muted">Kartu Merah</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6>Gol per Pertandingan</h6>
                                <h4 class="mb-0">{{ $goalsPerMatch }}</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6>Persentase Gol</h6>
                                <h4 class="mb-0">
                                    @if($player->matches_played > 0)
                                        {{ round(($player->total_goals / ($player->matches_played * 3)) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </h4>
                                <small class="text-muted">dari rata-rata 3 gol/tim/pertandingan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Gol -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Riwayat Gol</h5>
                </div>
                <div class="card-body">
                    @if($player->goals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Pertandingan</th>
                                        <th>Menit</th>
                                        <th>Tipe</th>
                                        <th>Assist</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($player->goals as $goal)
                                        <tr>
                                            <td>
                                                {{ $goal->match->homeTeam->name }} vs {{ $goal->match->awayTeam->name }}
                                            </td>
                                            <td><span class="badge bg-dark">{{ $goal->minute }}'</span></td>
                                            <td>
                                                <span class="badge 
                                                            @if($goal->type == 'Penalty') bg-danger
                                                            @elseif($goal->type == 'Free Kick') bg-info
                                                            @else bg-primary
                                                            @endif">
                                                    {{ $goal->type }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($goal->assistPlayer)
                                                    {{ $goal->assistPlayer->name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $goal->match->match_date->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">Belum mencetak gol</p>
                    @endif
                </div>
            </div>

            <!-- Riwayat Kartu -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Riwayat Kartu</h5>
                </div>
                <div class="card-body">
                    @if($player->cards->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Pertandingan</th>
                                        <th>Menit</th>
                                        <th>Tipe</th>
                                        <th>Alasan</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($player->cards as $card)
                                        <tr>
                                            <td>
                                                {{ $card->match->homeTeam->name }} vs {{ $card->match->awayTeam->name }}
                                            </td>
                                            <td><span class="badge bg-dark">{{ $card->minute }}'</span></td>
                                            <td>
                                                <span class="badge 
                                                            @if($card->card_type == 'Red') bg-danger
                                                            @else bg-warning
                                                            @endif">
                                                    {{ $card->card_type }}
                                                </span>
                                            </td>
                                            <td>{{ $card->reason ?: '-' }}</td>
                                            <td>{{ $card->match->match_date->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">Tidak ada kartu</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection