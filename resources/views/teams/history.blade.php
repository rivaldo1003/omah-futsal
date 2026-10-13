<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="theme-color" content="#020408">
    <title>{{ $team->name }} — Riwayat Turnamen | OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @include('home.partials.styles')

    <style>
        .team-history-page { max-width: 1080px; }

        .team-history-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 44px;
            margin-bottom: 16px;
            color: var(--text-secondary);
            font-size: 14px;
            text-decoration: none;
        }

        .team-history-back:hover { color: var(--accent); }

        .team-history-back:focus-visible,
        .team-record-link:focus-visible {
            outline: 2px solid var(--border-focus);
            outline-offset: 3px;
            border-radius: 3px;
        }

        .team-profile-overview {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            align-items: center;
            gap: 22px;
            padding: 26px;
            border: 1px solid var(--border-color);
            border-radius: var(--v3-radius-md);
            background: var(--surface);
            margin-bottom: 28px;
        }

        .team-profile-crest {
            width: 88px;
            height: 88px;
            object-fit: contain;
            flex: 0 0 auto;
        }

        .team-profile-crest-fallback {
            display: grid;
            place-items: center;
            width: 88px;
            height: 88px;
            border: 1px solid var(--border-color);
            border-radius: var(--v3-radius-md);
            color: var(--accent);
            font-size: 25px;
            font-weight: 750;
            letter-spacing: .04em;
            background: var(--surface-subtle);
        }

        .team-profile-name {
            margin: 0;
            color: var(--text-primary);
            font-size: clamp(25px, 4vw, 36px);
            font-weight: 750;
            line-height: 1.12;
            overflow-wrap: anywhere;
        }

        .team-profile-subtitle {
            margin: 8px 0 0;
            color: var(--text-secondary);
            font-size: 15px;
        }

        .team-profile-facts {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 18px;
            margin: 15px 0 0;
            padding: 0;
            list-style: none;
            color: var(--text-secondary);
            font-size: 13px;
        }

        .team-profile-facts li { display: inline-flex; align-items: center; gap: 7px; }
        .team-profile-facts i { color: var(--accent); }

        .team-profile-count {
            min-width: 112px;
            padding-left: 20px;
            border-left: 1px solid var(--border-color);
            text-align: center;
        }

        .team-profile-count strong {
            display: block;
            color: var(--text-primary);
            font-size: 30px;
            font-variant-numeric: tabular-nums;
            line-height: 1.1;
        }

        .team-profile-count span { color: var(--text-secondary); font-size: 12px; }

        .team-records-section {
            overflow: hidden;
            border: 1px solid var(--border-color);
            border-radius: var(--v3-radius-md);
            background: var(--surface);
        }

        .team-records-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 19px 22px;
            border-bottom: 1px solid var(--border-color);
        }

        .team-records-heading h2 {
            margin: 0;
            color: var(--text-primary);
            font-size: 20px;
            font-weight: 700;
        }

        .team-records-heading p {
            margin: 4px 0 0;
            color: var(--text-secondary);
            font-size: 13px;
        }

        .team-records-count {
            flex: 0 0 auto;
            color: var(--text-secondary);
            font-size: 13px;
            font-variant-numeric: tabular-nums;
        }

        .team-tournament-records {
            margin: 0;
            padding: 0 22px;
            list-style: none;
        }

        .team-tournament-record {
            display: grid;
            grid-template-columns: 94px minmax(0, 1fr) auto;
            align-items: center;
            gap: 18px;
            min-height: 88px;
            padding: 16px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .team-tournament-record:last-child { border-bottom: 0; }

        .team-record-date {
            color: var(--text-secondary);
            font-size: 13px;
            font-variant-numeric: tabular-nums;
        }

        .team-record-link {
            display: inline-block;
            color: var(--text-primary);
            font-size: 16px;
            font-weight: 650;
            line-height: 1.35;
            text-decoration: none;
            overflow-wrap: anywhere;
        }

        .team-record-link:hover { color: var(--accent); }

        .team-record-meta {
            display: block;
            margin-top: 4px;
            color: var(--text-secondary);
            font-size: 13px;
        }

        .team-record-outcome {
            max-width: 250px;
            padding: 7px 10px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            font-size: 13px;
            line-height: 1.3;
            text-align: center;
        }

        .team-record-outcome.is-champion {
            border-color: rgba(255, 183, 0, .4);
            color: var(--warning);
            background: var(--warning-subtle);
        }

        .team-record-outcome.is-runner-up,
        .team-record-outcome.is-placement { color: var(--accent); }

        .team-record-outcome.is-ongoing { color: var(--accent); background: var(--accent-subtle); }
        .team-record-outcome.is-upcoming { color: var(--v3-neon-blue); }

        .team-record-empty {
            padding: 42px 24px;
            color: var(--text-secondary);
            text-align: center;
        }

        .team-record-empty i { display: block; margin-bottom: 10px; color: var(--text-muted); font-size: 28px; }
        .team-record-empty p { margin: 0; font-size: 15px; }

        @media (max-width: 640px) {
            .team-profile-overview { grid-template-columns: auto minmax(0, 1fr); gap: 15px; padding: 18px; }
            .team-profile-crest, .team-profile-crest-fallback { width: 64px; height: 64px; }
            .team-profile-crest-fallback { font-size: 20px; }
            .team-profile-count { grid-column: 1 / -1; display: flex; align-items: baseline; gap: 8px; padding: 12px 0 0; border-left: 0; border-top: 1px solid var(--border-color); text-align: left; }
            .team-profile-count strong { font-size: 22px; }
            .team-records-heading { padding: 16px; }
            .team-tournament-records { padding: 0 16px; }
            .team-tournament-record { grid-template-columns: minmax(0, 1fr); gap: 7px; padding: 16px 0; }
            .team-record-date { font-size: 12px; }
            .team-record-outcome { justify-self: start; max-width: 100%; text-align: left; }
        }
    </style>
</head>

<body>
    @include('home.partials.navbar')

    <main class="app-container content-section team-history-page">
        <a class="team-history-back" href="{{ route('all-teams') }}">
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
            Kembali ke semua tim
        </a>

        <section class="team-profile-overview" aria-labelledby="teamProfileName">
            @if($team->logo && Storage::disk('public')->exists($team->logo))
                <img class="team-profile-crest" src="{{ asset('storage/' . $team->logo) }}" alt="Logo {{ $team->name }}">
            @else
                <div class="team-profile-crest-fallback" aria-hidden="true">{{ strtoupper(substr($team->name, 0, 2)) }}</div>
            @endif

            <div>
                <h1 class="team-profile-name" id="teamProfileName">{{ $team->name }}</h1>
                <p class="team-profile-subtitle">Profil tim dan catatan partisipasi turnamen</p>
                <ul class="team-profile-facts">
                    @if($team->coach_name)
                        <li><i class="bi bi-person" aria-hidden="true"></i>{{ $team->coach_name }}</li>
                    @endif
                    @if($team->home_venue)
                        <li><i class="bi bi-geo-alt" aria-hidden="true"></i>{{ $team->home_venue }}</li>
                    @endif
                    @if($team->founded_year)
                        <li><i class="bi bi-calendar3" aria-hidden="true"></i>Berdiri {{ $team->founded_year }}</li>
                    @endif
                </ul>
            </div>

            <div class="team-profile-count" aria-label="Jumlah turnamen">
                <strong>{{ $tournamentHistory->count() }}</strong>
                <span>turnamen</span>
            </div>
        </section>

        <section class="team-records-section" aria-labelledby="teamHistoryHeading">
            <header class="team-records-heading">
                <div>
                    <h2 id="teamHistoryHeading">Riwayat turnamen</h2>
                    <p>Hasil dan babak terakhir tim di setiap event.</p>
                </div>
                <span class="team-records-count">{{ $tournamentHistory->count() }} event</span>
            </header>

            @if($tournamentHistory->isNotEmpty())
                <ol class="team-tournament-records">
                    @foreach($tournamentHistory as $event)
                        @php
                            $eventStateClass = match($event['state']) {
                                'champion' => 'is-champion',
                                'runner-up' => 'is-runner-up',
                                'placement' => 'is-placement',
                                'ongoing' => 'is-ongoing',
                                'upcoming' => 'is-upcoming',
                                default => 'is-neutral',
                            };
                            $eventType = match($event['type'] ?? null) {
                                'league' => 'Liga',
                                'knockout' => 'Knockout',
                                'group_knockout' => 'Grup & knockout',
                                default => 'Turnamen',
                            };
                        @endphp
                        <li class="team-tournament-record">
                            <time class="team-record-date" @if($event['start_date']) datetime="{{ $event['start_date']->format('Y-m-d') }}" @endif>
                                {{ $event['start_date']?->format('Y') ?? '—' }}
                            </time>
                            <div>
                                <a class="team-record-link" href="{{ route('standings', ['tournament_id' => $event['id']]) }}">
                                    {{ $event['name'] }}
                                    <i class="bi bi-arrow-up-right ms-1" aria-hidden="true"></i>
                                </a>
                                <span class="team-record-meta">{{ $eventType }}</span>
                            </div>
                            <span class="team-record-outcome {{ $eventStateClass }}">{{ $event['outcome'] }}</span>
                        </li>
                    @endforeach
                </ol>
            @else
                <div class="team-record-empty">
                    <i class="bi bi-trophy" aria-hidden="true"></i>
                    <p>Tim ini belum tercatat mengikuti turnamen.</p>
                </div>
            @endif
        </section>
    </main>

    @include('home.partials.footer')
</body>

</html>
