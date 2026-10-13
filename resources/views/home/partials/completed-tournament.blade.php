@if(!empty($recentCompletedTournament))
    @php
        $archivedTournament = $recentCompletedTournament['tournament'];
        $archivedChampion = $recentCompletedTournament['champion'];
        $archiveLinkLabel = in_array($archivedTournament->type, ['knockout', 'group_knockout'], true)
            ? 'Lihat bracket'
            : 'Lihat klasemen';
    @endphp

    <section class="v3-completed-tournament" aria-labelledby="v3-completed-tournament-title">
        <div class="v3-completed-tournament-main">
            <span class="v3-completed-tournament-trophy" aria-hidden="true">
                <i class="bi bi-trophy-fill"></i>
            </span>

            <div class="v3-completed-tournament-copy">
                <p class="v3-completed-tournament-kicker">Turnamen terakhir selesai</p>
                <h2 id="v3-completed-tournament-title">{{ $archivedTournament->name }}</h2>

                <div class="v3-completed-tournament-champion">
                    @if($archivedChampion)
                        @include('partials.team-crest', [
                            'logo' => $archivedChampion['logo'] ?? null,
                            'class' => 'v3-completed-tournament-crest',
                        ])
                        <span>
                            <small>JUARA</small>
                            <strong>{{ $archivedChampion['name'] }}</strong>
                        </span>
                    @else
                        <span class="v3-completed-tournament-no-champion">Data juara belum tersedia</span>
                    @endif
                </div>
            </div>
        </div>

        <a class="v3-completed-tournament-link"
            href="{{ route('standings', ['tournament_id' => $archivedTournament->id]) }}">
            <span>{{ $archiveLinkLabel }}</span>
            <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
        </a>
    </section>

    <style>
        .v3-completed-tournament {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-top: 24px;
            padding: 18px 20px;
            border: 1px solid var(--v3-border);
            border-radius: var(--radius-lg, 10px);
            background: var(--v3-surface);
            color: var(--v3-text-main);
        }

        .v3-completed-tournament-main {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .v3-completed-tournament-trophy {
            display: grid;
            flex: 0 0 44px;
            width: 44px;
            height: 44px;
            place-items: center;
            border: 1px solid rgba(255, 183, 0, .38);
            border-radius: 50%;
            background: rgba(255, 183, 0, .08);
            color: var(--v3-neon-yellow);
            font-size: 18px;
        }

        .v3-completed-tournament-copy { min-width: 0; }

        .v3-completed-tournament-kicker {
            margin: 0 0 3px;
            color: var(--v3-text-sub);
            font-size: 12px;
            line-height: 1.4;
        }

        .v3-completed-tournament-copy h2 {
            margin: 0;
            overflow-wrap: anywhere;
            color: var(--v3-text-main);
            font-size: 18px;
            font-weight: 800;
            line-height: 1.3;
        }

        .v3-completed-tournament-champion {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .v3-completed-tournament-crest {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            flex-basis: 28px;
            overflow: hidden;
            border-radius: 50%;
            border: 1px solid var(--v3-border);
            background: var(--v3-bg);
            padding: 3px;
        }

        .v3-completed-tournament-crest img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .v3-completed-tournament-champion > span:not(.team-crest) {
            display: flex;
            flex-direction: column;
            gap: 1px;
            min-width: 0;
        }

        .v3-completed-tournament-champion small {
            color: var(--v3-neon-green);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .08em;
            line-height: 1.3;
        }

        .v3-completed-tournament-champion strong {
            overflow-wrap: anywhere;
            color: var(--v3-text-main);
            font-size: 14px;
            line-height: 1.35;
        }

        .v3-completed-tournament-no-champion {
            color: var(--v3-text-sub);
            font-size: 13px;
        }

        .v3-completed-tournament-link {
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
            padding: 0 14px;
            border: 1px solid var(--v3-border);
            border-radius: var(--radius-md, 8px);
            color: var(--v3-text-main);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: border-color 150ms ease, color 150ms ease, background-color 150ms ease;
        }

        .v3-completed-tournament-link:hover,
        .v3-completed-tournament-link:focus-visible {
            border-color: var(--v3-neon-green);
            background: var(--accent-subtle);
            color: var(--v3-neon-green);
        }

        .v3-completed-tournament-link:focus-visible {
            outline: 2px solid var(--v3-neon-green);
            outline-offset: 3px;
        }

        @media (max-width: 575.98px) {
            .v3-completed-tournament {
                align-items: flex-start;
                flex-direction: column;
                gap: 14px;
                padding: 16px;
            }

            .v3-completed-tournament-main { align-items: flex-start; gap: 12px; }
            .v3-completed-tournament-trophy { flex-basis: 38px; width: 38px; height: 38px; }
            .v3-completed-tournament-link { width: 100%; }
        }
    </style>
@endif
