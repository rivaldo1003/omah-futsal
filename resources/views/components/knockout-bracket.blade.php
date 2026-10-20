@props(['knockoutBracket' => [], 'variant' => 'standings'])

@php
    $isHome = ($variant ?? 'standings') === 'home';
    $roundLabels = [
        'round_of_64' => 'Babak 64 Besar',
        'round_of_32' => 'Babak 32 Besar',
        'round_of_16' => 'Babak 16 Besar',
        'quarterfinal' => 'Perempat Final',
        'semifinal' => 'Semifinal',
        'third_place' => 'Perebutan Juara 3/4',
        'final' => 'Final',
        'qualifying' => 'Kualifikasi',
        'preliminary' => 'Pendahuluan',
    ];
    $statusLabels = [
        'upcoming' => 'Akan datang',
        'ongoing' => 'Berlangsung',
        'completed' => 'Selesai',
        'postponed' => 'Ditunda',
        'bye' => 'BYE · Lolos otomatis',
    ];
    $champion = null;
    foreach (($knockoutBracket['final'] ?? []) as $finalMatch) {
        if (($finalMatch['status'] ?? null) !== 'completed') {
            continue;
        }

        $winnerSide = $finalMatch['winner'] ?? null;
        if (in_array($winnerSide, ['home', 'away'], true)) {
            $champion = $finalMatch[$winnerSide . '_team'] ?? null;
            if ($champion) {
                break;
            }
        }
    }
@endphp

@if($knockoutBracket && count($knockoutBracket) > 0)
    @if($champion)
        <div class="{{ $isHome ? 'v3-bracket-champion-banner' : 'standings-bracket-champion-banner' }}" role="status"
            aria-label="Juara turnamen: {{ $champion['name'] }}">
            <span class="{{ $isHome ? 'v3-bracket-champion-emblem' : 'standings-bracket-champion-emblem' }}" aria-hidden="true">
                <i class="bi bi-trophy-fill"></i>
            </span>
            @include('partials.team-crest', ['logo' => $champion['logo'] ?? null, 'class' => $isHome ? 'v3-bracket-champion-logo' : 'standings-bracket-champion-logo'])
            <span class="{{ $isHome ? 'v3-bracket-champion-copy' : 'standings-bracket-champion-copy' }}">
                <span class="{{ $isHome ? 'v3-bracket-champion-label' : 'standings-bracket-champion-label' }}">JUARA TURNAMEN ·
                    FINAL</span>
                <strong>{{ $champion['name'] }}</strong>
            </span>
        </div>
    @endif
    <div class="{{ $isHome ? 'v3-bracket-scroll' : 'standings-bracket-scroller' }}" tabindex="0"
        aria-label="Bracket knockout; geser untuk melihat ronde berikutnya">
        <div class="{{ $isHome ? 'v3-bracket-board' : 'standings-bracket-board' }}" data-bracket-connectors>
            <svg class="{{ $isHome ? 'v3-bracket-connections' : 'standings-bracket-connections' }}" aria-hidden="true"
                focusable="false"></svg>
            @foreach($knockoutBracket as $round => $matches)
                @if(count($matches) > 0)
                    <section
                        class="{{ $isHome ? 'v3-bracket-round' : 'standings-bracket-round' }} {{ $round === 'final' ? 'is-final' : '' }}"
                        data-bracket-round="{{ $round }}" data-bracket-advances="{{ $round === 'third_place' ? 'false' : 'true' }}">
                        <header class="{{ $isHome ? 'v3-bracket-round-header' : 'standings-bracket-round-header' }}">
                            <span>{{ $roundLabels[$round] ?? ucfirst(str_replace('_', ' ', $round)) }}</span>
                            @php
                                $byeCount = collect($matches)->filter(fn($match) => !empty($match['is_bye']))->count();
                                $fixtureCount = count($matches) - $byeCount;
                            @endphp
                            <small>{{ $fixtureCount }} match{{ $byeCount ? ' · ' . $byeCount . ' bye' : '' }}</small>
                        </header>

                        <div class="{{ $isHome ? 'v3-bracket-round-matches' : 'standings-bracket-matches' }}">
                            @foreach($matches as $match)
                                @php
                                    $home = $match['home_team'] ?? null;
                                    $away = $match['away_team'] ?? null;
                                    $winner = $match['winner'] ?? null;
                                    $status = $match['status'] ?? 'upcoming';
                                    $isBye = !empty($match['is_bye']);
                                    $matchNumber = (int) ($match['bracket_index'] ?? $loop->index) + 1;
                                    $showScore = in_array($status, ['ongoing', 'completed'], true);
                                    $extras = $match['extras'] ?? [];
                                    $homeRowClass = $isHome ? 'v3-bracket-team-row' : 'standings-bracket-team';
                                    $homeIdentityClass = $isHome ? 'v3-bracket-team-identity' : 'standings-bracket-identity';
                                    $homeLogoClass = $isHome ? 'v3-bracket-logo' : 'standings-bracket-logo';
                                    $homeNameClass = $isHome ? 'v3-bracket-team' : '';
                                    $scoreClass = $isHome ? 'v3-bracket-score' : 'standings-bracket-score';
                                    $homePlacement = null;
                                    $awayPlacement = null;
                                    $homeIsChampion = $round === 'final' && $status === 'completed' && $winner === 'home';
                                    $awayIsChampion = $round === 'final' && $status === 'completed' && $winner === 'away';
                                    $championDecided = $homeIsChampion || $awayIsChampion;
                                    if ($round === 'third_place' && $status === 'completed') {
                                        $homePlacement = $winner === 'home' ? 'Juara 3' : ($winner === 'away' ? 'Juara 4' : null);
                                        $awayPlacement = $winner === 'away' ? 'Juara 3' : ($winner === 'home' ? 'Juara 4' : null);
                                    }
                                    if ($homeIsChampion) {
                                        $homePlacement = 'CHAMPION';
                                    } elseif ($awayIsChampion) {
                                        $awayPlacement = 'CHAMPION';
                                    }
                                @endphp

                                <article
                                    class="{{ $isHome ? 'v3-bracket-match' : 'standings-bracket-match' }} {{ $championDecided ? 'is-champion-decided' : '' }} {{ $isBye ? 'is-bye' : '' }}"
                                    data-bracket-match-index="{{ $match['bracket_index'] ?? $loop->index }}">
                                    <div class="{{ $isHome ? 'v3-bracket-match-meta' : 'standings-bracket-match-meta' }}">
                                        <span>{{ $isHome ? '#' . $matchNumber : 'Match ' . $matchNumber }}</span>
                                        <span
                                            class="{{ $isHome ? 'v3-knockout-status' : 'standings-bracket-status' }} is-{{ $status }}">
                                            {{ $statusLabels[$status] ?? ucfirst($status) }}
                                        </span>
                                    </div>

                                    @if($home || $away || $isHome)
                                        <div
                                            class="{{ $homeRowClass }} {{ $winner === 'home' ? 'is-winner' : '' }} {{ $homeIsChampion ? 'is-champion' : '' }}">
                                            <span class="{{ $homeIdentityClass }}">
                                                @if(!empty($home['is_bye']))
                                                    <span class="{{ $homeLogoClass }}" aria-hidden="true"><i
                                                            class="bi bi-arrow-return-right"></i></span>
                                                @else
                                                    @include('partials.team-crest', ['logo' => $home['logo'] ?? null, 'class' => $homeLogoClass])
                                                @endif
                                                <span class="{{ $homeNameClass }}">{{ $home['name'] ?? 'Menunggu pemenang' }}</span>
                                            </span>
                                            @if($homePlacement)
                                                <small
                                                    class="{{ $isHome ? 'v3-bracket-placement' : 'standings-bracket-placement' }} {{ $homeIsChampion ? 'is-champion' : '' }}">
                                                    @if($homeIsChampion)<i class="bi bi-trophy-fill" aria-hidden="true"></i>@endif
                                                    {{ $homePlacement }}
                                                </small>
                                            @endif
                                            <span class="{{ $scoreClass }}">{{ $showScore ? ($home['score'] ?? '–') : '–' }}</span>
                                        </div>
                                        <div
                                            class="{{ $homeRowClass }} {{ $winner === 'away' ? 'is-winner' : '' }} {{ $awayIsChampion ? 'is-champion' : '' }}">
                                            <span class="{{ $homeIdentityClass }}">
                                                @if(!empty($away['is_bye']))
                                                    <span class="{{ $homeLogoClass }}" aria-hidden="true"><i
                                                            class="bi bi-arrow-return-right"></i></span>
                                                @else
                                                    @include('partials.team-crest', ['logo' => $away['logo'] ?? null, 'class' => $homeLogoClass])
                                                @endif
                                                <span class="{{ $homeNameClass }}">{{ $away['name'] ?? 'Menunggu pemenang' }}</span>
                                            </span>
                                            @if($awayPlacement)
                                                <small
                                                    class="{{ $isHome ? 'v3-bracket-placement' : 'standings-bracket-placement' }} {{ $awayIsChampion ? 'is-champion' : '' }}">
                                                    @if($awayIsChampion)<i class="bi bi-trophy-fill" aria-hidden="true"></i>@endif
                                                    {{ $awayPlacement }}
                                                </small>
                                            @endif
                                            <span class="{{ $scoreClass }}">{{ $showScore ? ($away['score'] ?? '–') : '–' }}</span>
                                        </div>
                                    @else
                                        <div class="standings-bracket-empty">Peserta belum ditentukan</div>
                                    @endif

                                    @if($status === 'completed' && (!empty($extras['penalty_score']) || !empty($extras['et_score'])))
                                        <div class="{{ $isHome ? 'v3-bracket-extras' : 'standings-bracket-extras' }}"
                                            aria-label="Hasil babak tambahan">
                                            @if(!empty($extras['et_score']))
                                                <span class="{{ $isHome ? 'v3-bracket-extra' : 'standings-bracket-extra' }}">
                                                    <i class="bi bi-clock-history" aria-hidden="true"></i>
                                                    <span>ET</span>
                                                    <strong>{{ $extras['et_score'] }}</strong>
                                                </span>
                                            @endif
                                            @if(!empty($extras['penalty_score']))
                                                <span class="{{ $isHome ? 'v3-bracket-extra' : 'standings-bracket-extra' }} is-penalty">
                                                    <i class="bi bi-flag" aria-hidden="true"></i>
                                                    <span>Penalti</span>
                                                    <strong>{{ $extras['penalty_score'] }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach
        </div>
    </div>
    @include('partials.bracket-connectors-script')
@elseif(!$isHome)
    <div class="standings-empty">
        <div class="standings-empty-icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></div>
        <h2 class="standings-empty-title">Bracket belum tersedia</h2>
        <p class="standings-empty-text">Generate match dari bracket undian untuk menampilkan bagan turnamen.</p>
    </div>
@endif