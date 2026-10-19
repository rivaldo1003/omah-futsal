@extends('layouts.admin')

@section('title', 'Klasemen')

@section('styles')
    <style>
        .admin-standings-page {
            --standings-ink: #111111;
            --standings-charcoal: #39393b;
            --standings-muted: #707072;
            --standings-line: #cacacb;
            --standings-line-soft: #e5e5e5;
            --standings-cloud: #f5f5f5;
            --standings-white: #ffffff;
            --standings-success: #007d48;
            --standings-warning: #8a4b00;
            --standings-danger: #b42318;
            max-width: 1440px;
            margin: 0 auto;
            color: var(--standings-ink);
            font-size: 16px;
            line-height: 1.5;
        }

        .admin-standings-page .page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px 24px;
            margin-bottom: 28px;
        }

        .admin-standings-page .page-header h1 {
            margin: 0 0 8px;
            color: var(--standings-ink);
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
        }

        .admin-standings-page .page-subtitle {
            max-width: 66ch;
            margin: 0;
            color: var(--standings-muted);
            font-size: 15px;
        }

        .admin-standings-page .filter-form {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 8px;
            margin-bottom: 20px;
        }

        .admin-standings-page .filter-field {
            display: grid;
            gap: 6px;
        }

        .admin-standings-page .filter-label {
            color: var(--standings-charcoal);
            font-size: 13px;
            font-weight: 500;
        }

        .admin-standings-page .filter-select {
            min-width: min(320px, 100%);
            min-height: 44px;
            padding: 8px 40px 8px 16px;
            border: 1px solid var(--standings-line);
            border-radius: 9999px;
            appearance: none;
            background-color: var(--standings-white);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23111111' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 14px;
            color: var(--standings-ink);
            font: inherit;
            font-size: 14px;
            cursor: pointer;
        }

        .admin-standings-page .btn-primary,
        .admin-standings-page .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
            padding: 9px 18px;
            border: 1px solid transparent;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            text-decoration: none;
            white-space: nowrap;
            transition: background-color 150ms ease-out, border-color 150ms ease-out, color 150ms ease-out;
        }

        .admin-standings-page .btn-primary {
            border-color: var(--standings-ink);
            background: var(--standings-ink);
            color: var(--standings-white);
        }

        .admin-standings-page .btn-primary:hover {
            border-color: var(--standings-charcoal);
            background: var(--standings-charcoal);
            color: var(--standings-white);
        }

        .admin-standings-page .btn-secondary {
            background: var(--standings-cloud);
            color: var(--standings-ink);
        }

        .admin-standings-page .btn-secondary:hover {
            background: #eeeeee;
            color: var(--standings-ink);
        }

        .admin-standings-page :is(a, button, select):focus-visible {
            outline: 2px solid var(--standings-ink);
            outline-offset: 3px;
        }

        .admin-standings-page .filter-select:focus {
            border-color: var(--standings-ink);
            box-shadow: 0 0 0 3px var(--standings-cloud);
        }

        .admin-standings-page .alert-admin {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 16px;
            padding: 14px 16px;
            border: 1px solid var(--standings-line-soft);
            border-radius: 8px;
            background: var(--standings-cloud);
            color: var(--standings-charcoal);
            font-size: 14px;
            line-height: 1.5;
        }

        .admin-standings-page .alert-admin-success { color: var(--standings-success); }
        .admin-standings-page .alert-admin-danger { color: var(--standings-danger); }
        .admin-standings-page .alert-close { flex: 0 0 auto; margin-left: auto; }

        .admin-standings-page .main-card {
            overflow: hidden;
            border: 1px solid var(--standings-line-soft);
            border-radius: 8px;
            background: var(--standings-white);
        }

        .admin-standings-page .card-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px 20px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--standings-line-soft);
            background: var(--standings-white);
        }

        .admin-standings-page .card-header h2 {
            margin: 0;
            color: var(--standings-ink);
            font-size: 18px;
            font-weight: 500;
            line-height: 1.35;
        }

        .admin-standings-page .card-context {
            margin-top: 3px;
            color: var(--standings-muted);
            font-size: 14px;
        }

        .admin-standings-page .card-body {
            background: var(--standings-white);
        }

        .admin-standings-page .standing-group-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 20px;
            border-bottom: 1px solid var(--standings-line-soft);
            background: var(--standings-cloud);
            color: var(--standings-ink);
            font-size: 14px;
            font-weight: 500;
        }

        .admin-standings-page .standing-group-heading small {
            color: var(--standings-muted);
            font-size: 13px;
            font-weight: 400;
        }

        .admin-standings-page .standings-table {
            min-width: 780px;
            margin-bottom: 0;
        }

        .admin-standings-page .standings-table > :not(caption) > * > * {
            padding: 13px 16px;
            border-bottom-color: var(--standings-line-soft);
        }

        .admin-standings-page .standings-table thead th {
            background: var(--standings-white);
            color: var(--standings-muted);
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.025em;
            white-space: nowrap;
        }

        .admin-standings-page .standings-table tbody td {
            vertical-align: middle;
            color: var(--standings-charcoal);
            font-size: 14px;
        }

        .admin-standings-page .standings-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .admin-standings-page .standing-number {
            text-align: center;
            font-variant-numeric: tabular-nums;
        }

        .admin-standings-page .standing-team {
            min-width: 190px;
            color: var(--standings-ink);
            font-weight: 500;
        }

        .admin-standings-page .admin-bracket-team {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-standings-page .admin-bracket-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            width: 32px;
            height: 32px;
            flex: 0 0 32px;
            border-radius: 50%;
            background: var(--standings-cloud);
            color: var(--standings-muted);
            font-size: 14px;
        }

        .admin-standings-page .admin-bracket-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .admin-standings-page .standing-points {
            color: var(--standings-ink) !important;
            font-weight: 600;
        }

        .admin-standings-page .standing-position,
        .admin-standings-page .match-score-empty {
            color: var(--standings-muted);
            font-size: 13px;
        }

        .admin-standings-page .placement-badge,
        .admin-standings-page .status-badge {
            display: inline-flex;
            align-items: center;
            min-height: 26px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
            line-height: 1.2;
            white-space: nowrap;
        }

        .admin-standings-page .placement-badge,
        .admin-standings-page .status-completed {
            background: #e8f4ee;
            color: var(--standings-success);
        }

        .admin-standings-page .status-ongoing {
            background: #fff2dc;
            color: var(--standings-warning);
        }

        .admin-standings-page .status-postponed {
            background: var(--standings-cloud);
            color: var(--standings-charcoal);
        }

        .admin-standings-page .status-upcoming {
            background: var(--standings-cloud);
            color: var(--standings-muted);
        }

        .admin-standings-page .empty-state {
            padding: 48px 20px;
            text-align: center;
        }

        .admin-standings-page .empty-state-icon {
            margin-bottom: 12px;
            color: var(--standings-muted);
            font-size: 32px;
        }

        .admin-standings-page .empty-state-title {
            margin: 0 0 6px;
            color: var(--standings-ink);
            font-size: 16px;
            font-weight: 500;
        }

        .admin-standings-page .empty-state-text {
            max-width: 54ch;
            margin: 0 auto 16px;
            color: var(--standings-muted);
            font-size: 14px;
        }

        @media (max-width: 640px) {
            .admin-standings-page .filter-field,
            .admin-standings-page .filter-select,
            .admin-standings-page .filter-form .btn-primary {
                width: 100%;
            }

            .admin-standings-page .card-header {
                align-items: flex-start;
                padding: 16px;
            }

            .admin-standings-page .standing-group-heading {
                padding: 12px 16px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-standings-page *,
            .admin-standings-page *::before,
            .admin-standings-page *::after {
                transition: none !important;
                animation: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <main class="admin-standings-page">
        <div class="page-header">
            <div>
                <h1>Klasemen</h1>
                <p class="page-subtitle">Pantau peringkat liga, hasil grup, dan progres babak gugur.</p>
            </div>
        </div>

        <form action="{{ route('admin.standings.index') }}" method="GET" class="filter-form">
            <div class="filter-field">
                <label for="tournamentFilter" class="filter-label">Turnamen</label>
                <select class="filter-select" id="tournamentFilter" name="tournament_id" aria-label="Pilih turnamen">
                    <option value="">Pilih turnamen</option>
                    @foreach($tournaments as $tournament)
                        <option value="{{ $tournament->id }}" @selected(optional($selectedTournament)->id == $tournament->id)>
                            {{ $tournament->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">
                <i class="bi bi-funnel" aria-hidden="true"></i> Tampilkan klasemen
            </button>
        </form>

        @if(session('success'))
            <div class="alert-admin alert-admin-success" role="status">
                <i class="bi bi-check-circle" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close alert-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-admin alert-admin-danger" role="alert">
                <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close alert-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if(!$selectedTournament)
            <section class="main-card">
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></div>
                    @if($tournaments->isEmpty())
                        <h2 class="empty-state-title">Belum ada turnamen</h2>
                        <p class="empty-state-text">Buat turnamen terlebih dahulu untuk melihat klasemennya.</p>
                        <a href="{{ route('admin.tournaments.create.step', ['step' => 1]) }}" class="btn-primary">
                            <i class="bi bi-plus" aria-hidden="true"></i> Buat turnamen
                        </a>
                    @else
                        <h2 class="empty-state-title">Pilih turnamen</h2>
                        <p class="empty-state-text">Pilih turnamen, lalu tampilkan klasemen untuk melihat hasilnya.</p>
                    @endif
                </div>
            </section>
        @elseif($tournamentType === 'knockout')
            <section class="main-card">
                <header class="card-header">
                    <div>
                        <h2>Bracket</h2>
                        <p class="card-context">{{ $selectedTournament->name }}</p>
                    </div>
                </header>
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
                                                $matchStatus = $match['status'] ?? 'upcoming';
                                            @endphp
                                            <tr>
                                                <td class="standing-team">
                                                    <div class="admin-bracket-team">
                                                        @include('partials.team-crest', ['logo' => $match['home_team']['logo'] ?? null, 'class' => 'admin-bracket-logo'])
                                                        <span>{{ $match['home_team']['name'] ?? 'Belum ditentukan' }}</span>
                                                        @if($homePlacement)
                                                            <span class="placement-badge">{{ $homePlacement }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="standing-number fw-semibold">
                                                    @if(in_array($matchStatus, ['ongoing', 'completed'], true) && ($match['home_team']['score'] ?? null) !== null && ($match['away_team']['score'] ?? null) !== null)
                                                        {{ $match['home_team']['score'] }} – {{ $match['away_team']['score'] }}
                                                    @else
                                                        <span class="match-score-empty">
                                                            {{ in_array($matchStatus, ['ongoing', 'completed'], true) ? 'Skor belum tersedia' : 'Belum dimainkan' }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="standing-team">
                                                    <div class="admin-bracket-team">
                                                        @include('partials.team-crest', ['logo' => $match['away_team']['logo'] ?? null, 'class' => 'admin-bracket-logo'])
                                                        <span>{{ $match['away_team']['name'] ?? 'Belum ditentukan' }}</span>
                                                        @if($awayPlacement)
                                                            <span class="placement-badge">{{ $awayPlacement }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @switch($matchStatus)
                                                        @case('completed')
                                                            <span class="status-badge status-completed">Selesai</span>
                                                            @break
                                                        @case('ongoing')
                                                            <span class="status-badge status-ongoing">Berlangsung</span>
                                                            @break
                                                        @case('postponed')
                                                            <span class="status-badge status-postponed">Ditunda</span>
                                                            @break
                                                        @default
                                                            <span class="status-badge status-upcoming">Akan datang</span>
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
                            <h2 class="empty-state-title">Bracket belum tersedia</h2>
                            <p class="empty-state-text mb-0">Belum ada pertandingan knockout untuk turnamen ini.</p>
                        </div>
                    @endif
                </div>
            </section>
        @else
            <section class="main-card">
                <header class="card-header">
                    <div>
                        <h2>Klasemen</h2>
                        <p class="card-context">{{ $selectedTournament->name }}</p>
                    </div>
                    @if($groupedStandingsWithPosition->isNotEmpty())
                        <form action="{{ route('admin.standings.recalculate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tournament_id" value="{{ $selectedTournament->id }}">
                            <button type="submit" class="btn-secondary">
                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Hitung ulang
                            </button>
                        </form>
                    @endif
                </header>
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
                                            <th scope="col">Posisi</th>
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
                                                <td class="standing-number">
                                                    @if($standing->position !== null)
                                                        {{ $standing->position }}
                                                    @else
                                                        <span class="standing-position">Belum tersedia</span>
                                                    @endif
                                                </td>
                                                <td class="standing-team">
                                                    <div class="admin-bracket-team">
                                                        @include('partials.team-crest', ['logo' => $standing->team->logo ?? null, 'class' => 'admin-bracket-logo'])
                                                        <span>{{ $standing->team->name ?? 'Tim tidak diketahui' }}</span>
                                                    </div>
                                                </td>
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
                            <h2 class="empty-state-title">Belum ada tim di klasemen</h2>
                            <p class="empty-state-text mb-0">Tambahkan tim ke turnamen ini untuk menampilkan tabel klasemen.</p>
                        </div>
                    @endif
                </div>
            </section>
        @endif
    </main>
@endsection
