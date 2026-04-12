@extends('layouts.admin')

@section('title', 'Manage Match Lineup')

@section('styles')
    <style>
        .player-card-item {
            transition: all 0.2s;
            border-left: 4px solid transparent;
            cursor: pointer;
        }

        .player-card-item:hover {
            background-color: #f8fafc;
        }

        .home-team-card .player-card-item.selected {
            border-left-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.05);
        }

        .away-team-card .player-card-item.selected {
            border-left-color: #dc3545;
            background-color: rgba(220, 53, 69, 0.05);
        }

        .jersey-badge {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.85rem;
        }

        .sticky-submit {
            position: sticky;
            bottom: 20px;
            z-index: 100;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 fw-bold text-primary">Match Lineup</h4>
                <p class="text-muted mb-0">{{ $match->tournament->name ?? 'Tournament Match' }}</p>
            </div>
            <a href="{{ route('admin.matches.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Matches
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Gagal!</strong> {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="row align-items-center text-center">
                    <div class="col">
                        <h5 class="mb-0 fw-bold">{{ $match->homeTeam->name }}</h5>
                        <span class="badge bg-primary-subtle text-primary">HOME</span>
                    </div>
                    <div class="col-auto">
                        <div class="bg-light px-4 py-2 rounded-pill fw-bold border">VS</div>
                    </div>
                    <div class="col">
                        <h5 class="mb-0 fw-bold">{{ $match->awayTeam->name }}</h5>
                        <span class="badge bg-danger-subtle text-danger">AWAY</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.matches.save-lineup', $match->id) }}" method="POST">
                @csrf
                <div class="card-body bg-light-subtle">
                    <div class="alert alert-primary border-0 shadow-sm d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Petunjuk:</strong> Centang pemain yang hadir dan ikut serta dalam pertandingan.
                            Pemain yang dipilih akan mendapatkan <strong>+1 Penampilan</strong> pada statistik profil
                            mereka.
                        </div>
                    </div>

                    <div class="row">
                        <!-- Home Team -->
                        <div class="col-md-6 mb-4">
                            <div class="card home-team-card border-0 shadow-sm h-100">
                                <div
                                    class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                                    <h6 class="mb-0"><i class="bi bi-people-fill me-2"></i>{{ $match->homeTeam->name }}</h6>
                                    <div class="form-check mb-0">
                                        <input class="form-check-input select-all" type="checkbox"
                                            data-target="home-players" id="checkAllHome">
                                        <label class="form-check-label small cursor-pointer" for="checkAllHome">Pilih
                                            Semua</label>
                                    </div>
                                </div>
                                <div class="list-group list-group-flush home-players">
                                    @foreach($match->homeTeam->players as $player)
                                        <label
                                            class="list-group-item player-card-item {{ in_array($player->id, $currentLineupIds) ? 'selected' : '' }}"
                                            for="p{{ $player->id }}">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input player-cb" type="checkbox"
                                                        name="player_ids[]" value="{{ $player->id }}" id="p{{ $player->id }}" {{ in_array($player->id, $currentLineupIds) ? 'checked' : '' }}>
                                                </div>
                                                <div class="jersey-badge bg-primary-subtle text-primary me-3">
                                                    #{{ $player->jersey_number }}</div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold">{{ $player->name }}</div>
                                                    <small class="text-muted">{{ $player->position }}</small>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Away Team -->
                        <div class="col-md-6 mb-4">
                            <div class="card away-team-card border-0 shadow-sm h-100">
                                <div
                                    class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-3">
                                    <h6 class="mb-0"><i class="bi bi-people-fill me-2"></i>{{ $match->awayTeam->name }}</h6>
                                    <div class="form-check mb-0">
                                        <input class="form-check-input select-all" type="checkbox"
                                            data-target="away-players" id="checkAllAway">
                                        <label class="form-check-label small cursor-pointer" for="checkAllAway">Pilih
                                            Semua</label>
                                    </div>
                                </div>
                                <div class="list-group list-group-flush away-players">
                                    @foreach($match->awayTeam->players as $player)
                                        <label
                                            class="list-group-item player-card-item {{ in_array($player->id, $currentLineupIds) ? 'selected' : '' }}"
                                            for="p{{ $player->id }}">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input player-cb" type="checkbox"
                                                        name="player_ids[]" value="{{ $player->id }}" id="p{{ $player->id }}" {{ in_array($player->id, $currentLineupIds) ? 'checked' : '' }}>
                                                </div>
                                                <div class="jersey-badge bg-danger-subtle text-danger me-3">
                                                    #{{ $player->jersey_number }}</div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold">{{ $player->name }}</div>
                                                    <small class="text-muted">{{ $player->position }}</small>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-center py-4 border-top-0 sticky-submit">
                    <button type="submit" class="btn btn-lg btn-success px-5 shadow rounded-pill">
                        <i class="bi bi-check2-circle me-2"></i> Simpan Daftar Pemain (Lineup)
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Logika Pilih Semua
            document.querySelectorAll('.select-all').forEach(cb => {
                cb.addEventListener('change', function () {
                    const targetGroup = this.dataset.target;
                    const playersCbs = document.querySelectorAll(`.${targetGroup} .player-cb`);
                    playersCbs.forEach(playerCb => {
                        playerCb.checked = this.checked;
                        playerCb.closest('.player-card-item').classList.toggle('selected', this.checked);
                    });
                });
            });

            // Update visual saat checkbox satuan diklik
            document.querySelectorAll('.player-cb').forEach(cb => {
                cb.addEventListener('change', function () {
                    this.closest('.player-card-item').classList.toggle('selected', this.checked);
                });
            });
        });
    </script>
@endsection