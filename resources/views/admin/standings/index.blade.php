@extends('layouts.admin')

@section('title', 'Standings')

@section('styles')
    <style>
        /* Keep the standings page aligned with admin/matches/index. */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .page-header h1 {
            margin: 0 0 4px;
            color: var(--text-primary);
            font-size: 24px;
            font-weight: 600;
            line-height: 1.2;
        }

        .page-subtitle {
            margin: 0;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .btn-create {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 40px;
            padding: 0 16px;
            border: 0;
            border-radius: 6px;
            background: var(--accent);
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-create:hover {
            background: var(--accent-hover);
            color: #fff;
        }

        .btn-reset {
            display: inline-flex;
            align-items: center;
            height: 40px;
            padding: 0 12px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--bg);
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
        }

        .btn-reset:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .filter-select {
            height: 40px;
            padding: 0 12px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--bg);
            color: var(--text-primary);
            font-size: 14px;
        }

        .filter-select:focus {
            border-color: var(--accent);
            outline: none;
        }

        .filters-row {
            margin-bottom: 24px;
        }

        .main-card {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--bg);
        }

        .main-card .card-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            background: var(--bg);
        }

        .main-card .card-header h5 {
            margin: 0;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 600;
        }

        .main-card .card-body {
            background: var(--bg);
        }

        .standing-group-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 600;
        }

        .standing-group-heading small {
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 400;
        }

        .standings-table {
            min-width: 760px;
            margin-bottom: 0;
        }

        .standings-table thead th {
            color: var(--text-primary);
            font-size: 13px;
            white-space: nowrap;
        }

        .standings-table tbody td {
            vertical-align: middle;
        }

        .standing-number {
            text-align: center;
            font-variant-numeric: tabular-nums;
        }

        .standing-team {
            min-width: 180px;
            font-weight: 500;
        }

        .admin-bracket-team {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-bracket-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            width: 28px;
            height: 28px;
            flex: 0 0 28px;
            border-radius: 50%;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .admin-bracket-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .standing-points {
            font-weight: 700;
        }

        .empty-state {
            padding: 48px 16px;
            text-align: center;
        }

        .empty-state-icon {
            margin-bottom: 12px;
            color: var(--border);
            font-size: 32px;
        }

        .empty-state-title {
            color: var(--text-primary);
            font-size: 16px;
            font-weight: 600;
        }

        .empty-state-text {
            margin-bottom: 16px;
            color: var(--text-secondary);
            font-size: 14px;
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                transition: none !important;
                animation: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Standings</h1>
            <p class="page-subtitle">Lihat klasemen dan hasil babak turnamen</p>
        </div>
    </div>

    <form action="{{ route('admin.standings.index') }}" method="GET"
        class="filters-row d-flex gap-2 flex-wrap align-items-end">
        <div>
            <label for="tournamentFilter" class="form-label small text-muted mb-1">Turnamen</label>
            <select class="filter-select" id="tournamentFilter" name="tournament_id" aria-label="Pilih turnamen">
                <option value="">Pilih turnamen</option>
                @foreach($tournaments as $tournament)
                    <option value="{{ $tournament->id }}" @selected(optional($selectedTournament)->id == $tournament->id)>
                        {{ $tournament->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-create">
            <i class="bi bi-funnel" aria-hidden="true"></i> Tampilkan
        </button>
    </form>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2" aria-hidden="true"></i>
            {{ session('success') }}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i>
            {{ session('error') }}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if(!$selectedTournament)
        <div class="main-card">
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></div>
                @if($tournaments->isEmpty())
                    <h4 class="empty-state-title">Belum ada turnamen</h4>
                    <p class="empty-state-text">Buat turnamen terlebih dahulu untuk melihat klasemen.</p>
                    <a href="{{ route('admin.tournaments.create.step', ['step' => 1]) }}" class="btn-create">
                        <i class="bi bi-plus" aria-hidden="true"></i> Buat turnamen
                    </a>
                @else
                    <h4 class="empty-state-title">Pilih turnamen</h4>
                    <p class="empty-state-text">Pilih turnamen di atas, lalu tekan Tampilkan untuk melihat klasemennya.</p>
                @endif
            </div>
        </div>
    @elseif($tournamentType === 'knockout')
        <div class="main-card">
            <div class="card-header">
                <h5>{{ $selectedTournament->name }} — Bracket</h5>
            </div>
            <div class="card-body p-0">
                @if(!empty($knockoutBracket))
                    @php
                        $roundLabels = [
                            'round_of_64' => 'Babak 64 Besar',
                            'round_of_32' => 'Babak 32 Besar',
                            'round_of_16' => 'Babak 16 Besar',
                            'quarterfinal' => 'Perempat Final',
                            'semifinal' => 'Semifinal',
                            'third_place' => 'Perebutan Juara 3/4',
                            'final' => 'Final',
                        ];
                    @endphp

                    @foreach($knockoutBracket as $round => $matches)
                        <div class="standing-group-heading">
                            <span>{{ $roundLabels[$round] ?? ucfirst(str_replace('_', ' ', $round)) }}</span>
                            <small>{{ count($matches) }} pertandingan</small>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover standings-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Tim kandang</th>
                                        <th scope="col" class="text-center">Skor</th>
                                        <th scope="col">Tim tandang</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matches as $match)
                                        @php
                                            $homePlacement = null;
                                            $awayPlacement = null;
                                            if ($round === 'third_place' && ($match['status'] ?? null) === 'completed') {
                                                $winner = $match['winner'] ?? null;
                                                $homePlacement = $winner === 'home' ? 'Juara 3' : ($winner === 'away' ? 'Juara 4' : null);
                                                $awayPlacement = $winner === 'away' ? 'Juara 3' : ($winner === 'home' ? 'Juara 4' : null);
                                            }
                                        @endphp
                                        <tr>
                                            <td class="standing-team">
                                                <div class="admin-bracket-team">
                                                    @include('partials.team-crest', ['logo' => $match['home_team']['logo'] ?? null, 'class' => 'admin-bracket-logo'])
                                                    <span>{{ $match['home_team']['name'] ?? 'TBD' }}</span>
                                                    @if($homePlacement)
                                                        <span class="badge bg-success-subtle text-success">{{ $homePlacement }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="standing-number fw-semibold">
                                                @if(in_array($match['status'] ?? 'upcoming', ['ongoing', 'completed'], true) && (($match['home_team']['score'] ?? null) !== null || ($match['away_team']['score'] ?? null) !== null))
                                                    {{ $match['home_team']['score'] ?? '-' }} – {{ $match['away_team']['score'] ?? '-' }}
                                                @else
                                                    vs
                                                @endif
                                            </td>
                                            <td class="standing-team">
                                                <div class="admin-bracket-team">
                                                    @include('partials.team-crest', ['logo' => $match['away_team']['logo'] ?? null, 'class' => 'admin-bracket-logo'])
                                                    <span>{{ $match['away_team']['name'] ?? 'TBD' }}</span>
                                                    @if($awayPlacement)
                                                        <span class="badge bg-success-subtle text-success">{{ $awayPlacement }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @switch($match['status'])
                                                    @case('completed')
                                                        <span class="badge bg-success">Selesai</span>
                                                        @break
                                                    @case('ongoing')
                                                        <span class="badge bg-warning text-dark">Berlangsung</span>
                                                        @break
                                                    @case('postponed')
                                                        <span class="badge bg-secondary">Ditunda</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-light text-dark border">Akan datang</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></div>
                        <h4 class="empty-state-title">Bracket belum tersedia</h4>
                        <p class="empty-state-text mb-0">Belum ada pertandingan knockout untuk turnamen ini.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="main-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5>Klasemen — {{ $selectedTournament->name }}</h5>
                @if($groupedStandingsWithPosition->isNotEmpty())
                    <form action="{{ route('admin.standings.recalculate') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tournament_id" value="{{ $selectedTournament->id }}">
                        <button type="submit" class="btn-reset">
                            <i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i>
                            Hitung ulang
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-body p-0">
                @if($groupedStandingsWithPosition->isNotEmpty())
                    @foreach($groupedStandingsWithPosition as $group => $groupStandings)
                        <div class="standing-group-heading">
                            <span>{{ $selectedTournament->type === 'league' ? 'Klasemen' : 'Grup ' . $group }}</span>
                            <small>{{ $groupStandings->count() }} tim</small>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover standings-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tim</th>
                                        <th scope="col" class="text-center">Main</th>
                                        <th scope="col" class="text-center">Menang</th>
                                        <th scope="col" class="text-center">Seri</th>
                                        <th scope="col" class="text-center">Kalah</th>
                                        <th scope="col" class="text-center">Gol masuk</th>
                                        <th scope="col" class="text-center">Gol lawan</th>
                                        <th scope="col" class="text-center">Selisih</th>
                                        <th scope="col" class="text-center">Poin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupStandings as $standing)
                                        <tr>
                                            <td class="text-muted">{{ $standing->position ?? '—' }}</td>
                                            <td class="standing-team">{{ $standing->team->name ?? 'Tim tidak diketahui' }}</td>
                                            <td class="standing-number">{{ $standing->matches_played ?? 0 }}</td>
                                            <td class="standing-number">{{ $standing->wins ?? 0 }}</td>
                                            <td class="standing-number">{{ $standing->draws ?? 0 }}</td>
                                            <td class="standing-number">{{ $standing->losses ?? 0 }}</td>
                                            <td class="standing-number">{{ $standing->goals_for ?? 0 }}</td>
                                            <td class="standing-number">{{ $standing->goals_against ?? 0 }}</td>
                                            <td class="standing-number">{{ $standing->goal_difference ?? 0 }}</td>
                                            <td class="standing-number standing-points">{{ $standing->points ?? 0 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></div>
                        <h4 class="empty-state-title">Belum ada tim di klasemen</h4>
                        <p class="empty-state-text mb-0">Tambahkan tim ke turnamen ini untuk menampilkan tabel klasemen.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection
