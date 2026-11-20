<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="Arsip turnamen OFS Futsal Center yang telah selesai, termasuk nama juara dan hasil kompetisi.">
    <title>Riwayat Turnamen | OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @include('home.partials.styles')
    <style>
        .tournament-history-page {
            padding-top: clamp(28px, 5vw, 64px);
            padding-bottom: clamp(48px, 7vw, 88px);
        }

        .history-intro {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: end;
            gap: 24px;
            margin-bottom: 34px;
        }

        .history-intro h1 {
            margin: 0;
            color: var(--v3-text-main);
            font-size: clamp(2.25rem, 5vw, 4rem);
            font-weight: 650;
            letter-spacing: -.055em;
            line-height: .98;
        }

        .history-intro p {
            max-width: 620px;
            margin: 14px 0 0;
            color: var(--v3-text-muted);
            font-size: clamp(1rem, 1.5vw, 1.15rem);
        }

        .history-total {
            min-width: 160px;
            padding: 0 0 4px 22px;
            border-left: 3px solid var(--v3-neon-green);
        }

        .history-total strong,
        .history-total span { display: block; }
        .history-total strong {
            color: var(--v3-text-main);
            font-size: 2rem;
            font-variant-numeric: tabular-nums;
            letter-spacing: -.04em;
            line-height: 1;
        }
        .history-total span {
            margin-top: 7px;
            color: var(--v3-text-muted);
            font-size: .9rem;
        }

        .archive-sheet {
            overflow: hidden;
            background: var(--v3-surface);
            border-radius: 8px;
            border-top: 8px solid var(--v3-text-main);
        }

        .archive-sheet-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 24px;
            padding: 26px 30px 22px;
            border-bottom: 1px solid var(--v3-border);
        }

        .archive-sheet-head h2 {
            margin: 0;
            color: var(--v3-text-main);
            font-size: clamp(1.25rem, 2vw, 1.65rem);
            font-weight: 650;
            letter-spacing: -.035em;
        }

        .archive-sheet-head p {
            margin: 6px 0 0;
            color: var(--v3-text-muted);
            font-size: .95rem;
        }

        .history-search {
            display: flex;
            width: min(100%, 390px);
            gap: 8px;
        }

        .history-search .form-control {
            min-height: 44px;
            border: 1px solid #c9c9c9;
            border-radius: 999px;
            padding-inline: 17px;
            box-shadow: none;
        }

        .history-search .form-control:focus {
            border-color: var(--v3-text-main);
            box-shadow: 0 0 0 3px rgba(17, 17, 17, .1);
        }

        .history-search button {
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
            padding: 0 19px;
            border: 0;
            border-radius: 999px;
            background: var(--v3-text-main);
            color: #fff;
            font-weight: 600;
        }

        .history-search button:hover { background: #39393b; }

        .history-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .history-row {
            display: grid;
            grid-template-columns: 92px minmax(0, 1fr) auto;
            align-items: center;
            gap: 24px;
            padding: 24px 30px;
            border-bottom: 1px solid var(--v3-border);
        }

        .history-year {
            color: #8a8a8c;
            font-size: 1.15rem;
            font-variant-numeric: tabular-nums;
            font-weight: 600;
            letter-spacing: -.03em;
        }

        .history-main { min-width: 0; }

        .history-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px 13px;
            margin-bottom: 7px;
            color: var(--v3-text-muted);
            font-size: .85rem;
        }

        .history-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #006b3f;
            font-weight: 700;
        }

        .history-main h3 {
            margin: 0;
            color: var(--v3-text-main);
            font-size: clamp(1.1rem, 2vw, 1.4rem);
            font-weight: 650;
            letter-spacing: -.035em;
            overflow-wrap: anywhere;
        }

        .history-details {
            display: flex;
            flex-wrap: wrap;
            gap: 7px 18px;
            margin-top: 10px;
            color: var(--v3-text-muted);
            font-size: .9rem;
        }

        .history-details span { display: inline-flex; align-items: center; gap: 7px; }

        .history-winner {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }

        .history-winner .team-crest {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            padding: 4px;
            border: 1px solid var(--v3-border);
            border-radius: 50%;
            background: #f5f5f5;
        }

        .history-winner .team-crest img { width: 100%; height: 100%; object-fit: contain; }

        .history-winner-copy { display: grid; gap: 1px; min-width: 0; }
        .history-winner-copy span {
            color: var(--v3-neon-green);
            font-size: .72rem;
            font-weight: 750;
            letter-spacing: .07em;
            text-transform: uppercase;
        }
        .history-winner-copy strong {
            color: var(--v3-text-main);
            font-size: .98rem;
            font-weight: 650;
            overflow-wrap: anywhere;
        }
        .history-winner-missing {
            color: var(--v3-text-muted);
            font-size: .92rem;
        }

        .history-result-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 42px;
            padding: 0 14px;
            border-radius: 999px;
            background: #f2f2f2;
            color: var(--v3-text-main);
            font-size: .88rem;
            font-weight: 650;
            white-space: nowrap;
        }
        .history-result-link:hover { background: #e8e8e8; color: var(--v3-text-main); }
        .history-result-link:focus-visible { outline: 2px solid var(--v3-text-main); outline-offset: 3px; }

        .history-empty {
            padding: 56px 24px;
            text-align: center;
        }
        .history-empty h3 { margin: 0; font-size: 1.2rem; font-weight: 650; }
        .history-empty p { margin: 8px 0 0; color: var(--v3-text-muted); }

        .history-pagination { padding: 20px 28px; }
        .history-pagination .pagination { justify-content: center; margin: 0; }

        @media (max-width: 767.98px) {
            .history-intro { grid-template-columns: 1fr; gap: 20px; align-items: start; }
            .history-total { min-width: 0; padding-left: 14px; }
            .history-total strong { font-size: 1.65rem; }
            .archive-sheet-head { align-items: stretch; flex-direction: column; gap: 18px; padding: 22px 18px; }
            .history-search { width: 100%; }
            .history-search button { padding-inline: 15px; }
            .history-row { grid-template-columns: 1fr auto; gap: 10px 14px; padding: 20px 18px; }
            .history-year { grid-column: 1 / -1; font-size: .95rem; }
            .history-main { grid-column: 1; }
            .history-result-link { grid-column: 2; grid-row: 2; align-self: start; padding-inline: 12px; font-size: .82rem; }
            .history-pagination { padding: 18px 12px; }
        }

        @media (max-width: 420px) {
            .history-row { grid-template-columns: minmax(0, 1fr); }
            .history-main, .history-result-link { grid-column: 1; grid-row: auto; }
            .history-result-link { justify-self: start; margin-top: 6px; }
            .history-search button .history-search-label { display: none; }
        }
    </style>
</head>

<body>
    @include('home.partials.navbar')

    <main class="app-container tournament-history-page">
        <header class="history-intro">
            <div>
                <h1>Riwayat turnamen</h1>
                <p>Telusuri kompetisi yang telah selesai, lihat tim juara, lalu buka klasemen atau bagan hasilnya.</p>
            </div>
            <div class="history-total" aria-label="{{ $completedTournamentCount }} turnamen telah selesai">
                <strong>{{ number_format($completedTournamentCount, 0, ',', '.') }}</strong>
                <span>turnamen selesai</span>
            </div>
        </header>

        <section class="archive-sheet" aria-labelledby="history-list-title">
            <div class="archive-sheet-head">
                <div>
                    <h2 id="history-list-title">Arsip hasil kompetisi</h2>
                    <p>Juara diambil dari hasil final atau posisi teratas klasemen akhir.</p>
                </div>
                <form class="history-search" method="GET" action="{{ route('tournaments.history') }}" role="search">
                    <label class="visually-hidden" for="tournamentHistorySearch">Cari nama turnamen</label>
                    <input class="form-control" id="tournamentHistorySearch" type="search" name="q"
                        value="{{ $search }}" placeholder="Cari nama turnamen">
                    <button type="submit" aria-label="Cari turnamen">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <span class="history-search-label">Cari</span>
                    </button>
                </form>
            </div>

            @if($tournaments->isNotEmpty())
                <ol class="history-list">
                    @foreach($tournaments as $tournament)
                        @php
                            $startDate = $tournament->start_date ? \Illuminate\Support\Carbon::parse($tournament->start_date)->locale('id')->translatedFormat('d M Y') : null;
                            $endDate = $tournament->end_date ? \Illuminate\Support\Carbon::parse($tournament->end_date)->locale('id')->translatedFormat('d M Y') : null;
                            $dateLabel = $startDate && $endDate
                                ? ($startDate === $endDate ? $startDate : $startDate . ' sampai ' . $endDate)
                                : ($endDate ?? $startDate ?? 'Tanggal belum dicatat');
                            $yearLabel = $tournament->end_date?->format('Y') ?? $tournament->start_date?->format('Y') ?? 'Tahun belum dicatat';
                            $formatLabel = match($tournament->type) {
                                'league' => 'Liga',
                                'knockout' => 'Sistem gugur',
                                'group_knockout' => 'Grup dan sistem gugur',
                                default => 'Kompetisi',
                            };
                            $resultLabel = in_array($tournament->type, ['knockout', 'group_knockout'], true)
                                ? 'Lihat bagan'
                                : 'Lihat klasemen';
                        @endphp
                        <li class="history-row">
                            <div class="history-year">{{ $yearLabel }}</div>
                            <div class="history-main">
                                <div class="history-meta">
                                    <span class="history-status"><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Selesai</span>
                                    <span>{{ $formatLabel }}</span>
                                    <span>{{ $dateLabel }}</span>
                                </div>
                                <h3>{{ $tournament->name }}</h3>
                                <div class="history-details">
                                    @if($tournament->location)
                                        <span><i class="bi bi-geo-alt" aria-hidden="true"></i>{{ $tournament->location }}</span>
                                    @endif
                                    <span><i class="bi bi-people" aria-hidden="true"></i>{{ number_format((int) $tournament->history_teams_count, 0, ',', '.') }} tim</span>
                                    <span><i class="bi bi-calendar2-event" aria-hidden="true"></i>{{ number_format((int) $tournament->history_matches_count, 0, ',', '.') }} pertandingan</span>
                                </div>
                                <div class="history-winner">
                                    @if(!empty($tournament->history_champion['name']))
                                        @include('partials.team-crest', [
                                            'logo' => $tournament->history_champion['logo'] ?? null,
                                            'class' => 'history-team-crest',
                                        ])
                                        <div class="history-winner-copy">
                                            <span>Juara</span>
                                            <strong>{{ $tournament->history_champion['name'] }}</strong>
                                        </div>
                                    @else
                                        <span class="history-winner-missing">Juara belum tercatat</span>
                                    @endif
                                </div>
                            </div>
                            <a class="history-result-link" href="{{ route('standings', ['tournament_id' => $tournament->id]) }}">
                                {{ $resultLabel }} <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                            </a>
                        </li>
                    @endforeach
                </ol>

                @if($tournaments->hasPages())
                    <div class="history-pagination">
                        {{ $tournaments->links() }}
                    </div>
                @endif
            @else
                <div class="history-empty">
                    <h3>{{ $search !== '' ? 'Turnamen tidak ditemukan' : 'Belum ada riwayat turnamen' }}</h3>
                    <p>{{ $search !== '' ? 'Coba kata kunci lain untuk mencari turnamen.' : 'Turnamen akan tampil di sini setelah statusnya diubah menjadi selesai.' }}</p>
                </div>
            @endif
        </section>
    </main>

    @include('home.partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('home.partials.scripts')
</body>

</html>
