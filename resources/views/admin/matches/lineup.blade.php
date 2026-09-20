@extends('layouts.admin')

@section('title', 'Kelola Lineup')

@section('styles')
    <style>
        /* ===== Match lineup page — design guidelines ===== */

        /* Page header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
            margin: 0 0 4px;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            margin: 0;
        }

        .btn-back {
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text-primary);
            border-radius: 6px;
            height: 40px;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-back:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Match card */
        .main-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .main-card .card-header {
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            padding: 16px;
        }

        .team-badge {
            font-size: 12px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .badge-home {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--accent);
        }

        .badge-away {
            background: #FDF2F3;
            color: #c01c28;
        }

        .vs-pill {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 4px 16px;
            font-weight: 600;
            font-size: 13px;
            color: var(--text-secondary);
        }

        /* Info banner */
        .info-banner {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
            color: var(--text-primary);
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 24px;
        }

        .info-banner i {
            color: var(--accent);
            font-size: 16px;
            margin-top: 2px;
        }

        /* Team player cards */
        .team-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
        }

        .team-card .card-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .team-card.home .card-header {
            background: var(--accent);
            color: #fff;
        }

        .team-card.away .card-header {
            background: #c01c28;
            color: #fff;
        }

        .team-card .card-header h6 {
            font-size: 14px;
            font-weight: 600;
            margin: 0;
        }

        .team-card .card-header .form-check-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
        }

        .team-card .card-header .form-check-input {
            border-color: rgba(255, 255, 255, 0.6);
        }

        .team-card .card-header .form-check-input:checked {
            background-color: #fff;
            border-color: #fff;
        }

        .team-card .card-header .form-check-input:checked[type="checkbox"] {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%231a5fb4' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e");
        }

        /* Player item */
        .player-card-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            border-left: 3px solid transparent;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .player-card-item:last-child {
            border-bottom: none;
        }

        .player-card-item:hover {
            background: var(--surface);
        }

        .team-card.home .player-card-item.selected {
            border-left-color: var(--accent);
            background: rgba(26, 95, 180, 0.05);
        }

        .team-card.away .player-card-item.selected {
            border-left-color: #c01c28;
            background: rgba(192, 28, 40, 0.05);
        }

        .jersey-badge {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            flex-shrink: 0;
        }

        .team-card.home .jersey-badge {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--accent);
        }

        .team-card.away .jersey-badge {
            background: #FDF2F3;
            color: #c01c28;
        }

        .player-name {
            font-weight: 500;
            font-size: 14px;
            color: var(--text-primary);
        }

        .player-position {
            font-size: 12px;
            color: var(--text-secondary);
        }

        /* Submit */
        .sticky-submit {
            position: sticky;
            bottom: 20px;
            z-index: 10;
            padding: 16px;
            text-align: center;
        }

        .btn-save-lineup {
            background: #1E7A46;
            color: #fff;
            border: none;
            border-radius: 8px;
            height: 44px;
            padding: 0 32px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-save-lineup:hover {
            background: #186238;
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Lineup Pertandingan</h1>
            <p class="page-subtitle">{{ $match->tournament->name ?? 'Pertandingan Turnamen' }}</p>
        </div>
        <a href="{{ route('admin.matches.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="main-card">
        <div class="card-header">
            <div class="row align-items-center text-center">
                <div class="col">
                    <h5 class="mb-1">{{ $match->homeTeam->name }}</h5>
                    <span class="team-badge badge-home">HOME</span>
                </div>
                <div class="col-auto">
                    <span class="vs-pill">VS</span>
                </div>
                <div class="col">
                    <h5 class="mb-1">{{ $match->awayTeam->name }}</h5>
                    <span class="team-badge badge-away">AWAY</span>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.matches.save-lineup', $match->id) }}" method="POST">
            @csrf

            <div class="card-body" style="padding: 24px;">
                <div class="info-banner">
                    <i class="bi bi-info-circle"></i>
                    <div>
                        <strong>Petunjuk:</strong> Centang pemain yang hadir dan ikut serta dalam pertandingan.
                        Pemain yang dipilih akan mendapatkan <strong>+1 Penampilan</strong> pada statistik profil mereka.
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Tim Home -->
                    <div class="col-md-6">
                        <div class="team-card home">
                            <div class="card-header">
                                <h6><i class="bi bi-people-fill me-2"></i>{{ $match->homeTeam->name }}</h6>
                                <div class="form-check mb-0">
                                    <input class="form-check-input select-all" type="checkbox"
                                        data-target="home-players" id="checkAllHome">
                                    <label class="form-check-label" for="checkAllHome">Pilih Semua</label>
                                </div>
                            </div>
                            <div class="home-players">
                                @foreach($match->homeTeam->players as $player)
                                    <label class="player-card-item {{ in_array($player->id, $currentLineupIds) ? 'selected' : '' }}"
                                        for="p{{ $player->id }}">
                                        <div class="form-check me-3">
                                            <input class="form-check-input player-cb" type="checkbox"
                                                name="player_ids[]" value="{{ $player->id }}" id="p{{ $player->id }}"
                                                {{ in_array($player->id, $currentLineupIds) ? 'checked' : '' }}>
                                        </div>
                                        <div class="jersey-badge me-3">#{{ $player->jersey_number }}</div>
                                        <div class="flex-grow-1">
                                            <div class="player-name">{{ $player->name }}</div>
                                            <div class="player-position">{{ $player->position }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Tim Away -->
                    <div class="col-md-6">
                        <div class="team-card away">
                            <div class="card-header">
                                <h6><i class="bi bi-people-fill me-2"></i>{{ $match->awayTeam->name }}</h6>
                                <div class="form-check mb-0">
                                    <input class="form-check-input select-all" type="checkbox"
                                        data-target="away-players" id="checkAllAway">
                                    <label class="form-check-label" for="checkAllAway">Pilih Semua</label>
                                </div>
                            </div>
                            <div class="away-players">
                                @foreach($match->awayTeam->players as $player)
                                    <label class="player-card-item {{ in_array($player->id, $currentLineupIds) ? 'selected' : '' }}"
                                        for="p{{ $player->id }}">
                                        <div class="form-check me-3">
                                            <input class="form-check-input player-cb" type="checkbox"
                                                name="player_ids[]" value="{{ $player->id }}" id="p{{ $player->id }}"
                                                {{ in_array($player->id, $currentLineupIds) ? 'checked' : '' }}>
                                        </div>
                                        <div class="jersey-badge me-3">#{{ $player->jersey_number }}</div>
                                        <div class="flex-grow-1">
                                            <div class="player-name">{{ $player->name }}</div>
                                            <div class="player-position">{{ $player->position }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit sticky -->
                <div class="sticky-submit">
                    <button type="submit" class="btn-save-lineup">
                        <i class="bi bi-check2-circle me-2"></i> Simpan Daftar Pemain (Lineup)
                    </button>
                </div>
            </div>
        </form>
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
