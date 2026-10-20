@extends('layouts.admin')

@section('title', 'Kelola Lineup')

@section('styles')
    <style>
        .admin-match-lineup {
            --lineup-ink: #111111;
            --lineup-charcoal: #39393b;
            --lineup-muted: #707072;
            --lineup-cloud: #f5f5f5;
            --lineup-border: #e5e5e5;
            --lineup-border-strong: #cacacb;
            --lineup-success: #007d48;
            max-width: 1440px;
            margin-inline: auto;
            color: var(--lineup-ink);
        }

        .admin-match-lineup .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 28px;
        }

        .admin-match-lineup .page-header h1 {
            margin: 0 0 8px;
            color: var(--lineup-ink);
            font-size: clamp(26px, 3vw, 32px);
            font-weight: 600;
            letter-spacing: -0.035em;
            line-height: 1.15;
        }

        .admin-match-lineup .page-subtitle {
            margin: 0;
            color: var(--lineup-muted);
            font-size: 15px;
            line-height: 1.5;
        }

        .admin-match-lineup .btn-back {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: 0;
            border-radius: 30px;
            background: var(--lineup-cloud);
            color: var(--lineup-ink);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: background-color .15s ease;
        }

        .admin-match-lineup .btn-back:hover {
            background: #eaeaec;
            color: var(--lineup-ink);
        }

        .admin-match-lineup .main-card {
            overflow: hidden;
            border: 1px solid var(--lineup-border);
            border-radius: 8px;
            background: #ffffff;
            box-shadow: none;
        }

        .admin-match-lineup .main-card > .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--lineup-border);
            background: #ffffff;
        }

        .admin-match-lineup .main-card > .card-header h5 {
            margin: 0 0 8px !important;
            color: var(--lineup-ink);
            font-size: 18px;
            font-weight: 600;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .admin-match-lineup .team-badge {
            min-height: 22px;
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border: 1px solid var(--lineup-border);
            border-radius: 4px;
            background: var(--lineup-cloud);
            color: var(--lineup-muted);
            font-size: 12px;
            font-weight: 500;
        }

        .admin-match-lineup .vs-pill {
            padding: 6px 12px;
            border: 1px solid var(--lineup-border);
            border-radius: 30px;
            background: #ffffff;
            color: var(--lineup-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .admin-match-lineup .main-card .card-body {
            padding: 24px;
        }

        .admin-match-lineup .info-banner {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 24px;
            padding: 14px 16px;
            border: 1px solid var(--lineup-border);
            border-radius: 8px;
            background: var(--lineup-cloud);
            color: var(--lineup-charcoal);
            font-size: 14px;
            line-height: 1.55;
        }

        .admin-match-lineup .info-banner i {
            flex: 0 0 auto;
            margin-top: 3px;
            color: var(--lineup-muted);
            font-size: 16px;
        }

        .admin-match-lineup .team-card {
            height: 100%;
            overflow: hidden;
            border: 1px solid var(--lineup-border);
            border-radius: 8px;
            background: #ffffff;
        }

        .admin-match-lineup .team-card .card-header {
            min-height: 62px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--lineup-border);
            background: #ffffff;
        }

        .admin-match-lineup .team-card .card-header h6 {
            min-width: 0;
            margin: 0;
            color: var(--lineup-ink);
            font-size: 15px;
            font-weight: 600;
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        .admin-match-lineup .team-card .card-header .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 0 0 auto;
            padding-left: 0;
        }

        .admin-match-lineup .team-card .card-header .form-check-label {
            color: var(--lineup-charcoal);
            font-size: 13px;
            white-space: nowrap;
        }

        .admin-match-lineup .form-check-input {
            width: 18px;
            height: 18px;
            margin: 0;
            border-color: var(--lineup-border-strong);
            box-shadow: none;
        }

        .admin-match-lineup .form-check-input:checked {
            border-color: var(--lineup-success);
            background-color: var(--lineup-success);
        }

        .admin-match-lineup .form-check-input:focus {
            border-color: var(--lineup-ink);
            box-shadow: none;
        }

        .admin-match-lineup .form-check-input:focus-visible {
            border: 2px solid var(--lineup-ink);
            outline: none;
        }

        .admin-match-lineup .player-card-item > .form-check {
            margin: 0 12px 0 0 !important;
            padding-left: 0;
        }

        .admin-match-lineup .player-card-item > .form-check .form-check-input {
            position: static;
            float: none;
        }

        .admin-match-lineup .player-card-item {
            min-height: 68px;
            display: flex;
            align-items: center;
            gap: 0;
            padding: 12px 16px;
            border-bottom: 1px solid var(--lineup-border);
            border-left: 3px solid transparent;
            color: var(--lineup-ink);
            cursor: pointer;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .admin-match-lineup .player-card-item:last-child {
            border-bottom: 0;
        }

        .admin-match-lineup .player-card-item:hover {
            background: #fafafa;
        }

        .admin-match-lineup .player-card-item.selected {
            border-left-color: var(--lineup-success);
            background: #f0f8f4;
        }

        .admin-match-lineup .jersey-badge {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid var(--lineup-border);
            border-radius: 8px;
            background: var(--lineup-cloud);
            color: var(--lineup-charcoal);
            font-size: 13px;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        .admin-match-lineup .player-name {
            color: var(--lineup-ink);
            font-size: 14px;
            font-weight: 600;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .admin-match-lineup .player-position {
            margin-top: 3px;
            color: var(--lineup-muted);
            font-size: 12px;
            line-height: 1.35;
        }

        .admin-match-lineup .empty-roster {
            padding: 22px 16px;
            color: var(--lineup-muted);
            font-size: 14px;
        }

        .admin-match-lineup .sticky-submit {
            position: sticky;
            bottom: 0;
            z-index: 10;
            margin-top: 18px;
            padding: 16px 0 0;
            border-top: 1px solid var(--lineup-border);
            background: #ffffff;
            text-align: right;
        }

        .admin-match-lineup .btn-save-lineup {
            min-height: 48px;
            padding: 12px 22px;
            border: 0;
            border-radius: 30px;
            background: var(--lineup-ink);
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            transition: background-color .15s ease;
        }

        .admin-match-lineup .btn-save-lineup:hover {
            background: #303032;
            color: #ffffff;
        }

        .admin-match-lineup .alert {
            border-radius: 8px;
            box-shadow: none;
        }

        .admin-match-lineup :focus-visible:not(.form-check-input) {
            outline: 2px solid var(--lineup-ink);
            outline-offset: 2px;
        }

        @media (max-width: 768px) {
            .admin-match-lineup .page-header {
                align-items: flex-start;
            }

            .admin-match-lineup .main-card > .card-header,
            .admin-match-lineup .main-card .card-body {
                padding: 18px;
            }

            .admin-match-lineup .sticky-submit {
                text-align: left;
            }

            .admin-match-lineup .btn-save-lineup {
                width: 100%;
            }
        }

        @media (max-width: 575.98px) {
            .admin-match-lineup .team-card .card-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .admin-match-lineup .player-card-item {
                padding-inline: 12px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-match-lineup *,
            .admin-match-lineup *::before,
            .admin-match-lineup *::after {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
    </style>
@endsection

@section('content')
    <main class="admin-match-lineup">
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
                    <h5 class="mb-1">{{ $match->homeTeam->name ?? 'TBD' }}</h5>
                    <span class="team-badge badge-home">HOME</span>
                </div>
                <div class="col-auto">
                    <span class="vs-pill">VS</span>
                </div>
                <div class="col">
                    <h5 class="mb-1">{{ $match->awayTeam->name ?? 'TBD' }}</h5>
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
                                <h6><i class="bi bi-people-fill me-2"></i>{{ $match->homeTeam->name ?? 'TBD' }}</h6>
                                <div class="form-check mb-0">
                                    <input class="form-check-input select-all" type="checkbox"
                                        data-target="home-players" id="checkAllHome">
                                    <label class="form-check-label" for="checkAllHome">Pilih Semua</label>
                                </div>
                            </div>
                            <div class="home-players">
                                @forelse(($match->homeTeam->players ?? collect()) as $player)
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
                                @empty
                                    <div class="empty-roster">Belum ada pemain pada tim ini.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Tim Away -->
                    <div class="col-md-6">
                        <div class="team-card away">
                            <div class="card-header">
                                <h6><i class="bi bi-people-fill me-2"></i>{{ $match->awayTeam->name ?? 'TBD' }}</h6>
                                <div class="form-check mb-0">
                                    <input class="form-check-input select-all" type="checkbox"
                                        data-target="away-players" id="checkAllAway">
                                    <label class="form-check-label" for="checkAllAway">Pilih Semua</label>
                                </div>
                            </div>
                            <div class="away-players">
                                @forelse(($match->awayTeam->players ?? collect()) as $player)
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
                                @empty
                                    <div class="empty-roster">Belum ada pemain pada tim ini.</div>
                                @endforelse
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
    </main>
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
