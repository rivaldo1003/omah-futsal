{{-- Date-Grouped Matches List Component --}}
@foreach($groupedMatches as $date => $matchesOnDate)
    <div class="schedule-day-group">
        <div class="schedule-date-header">
            <div>
                <i class="bi bi-calendar-date"></i>
                {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}
            </div>
            <span class="schedule-match-count">
                {{ $matchesOnDate->count() }} {{ Str::plural('match', $matchesOnDate->count()) }}
            </span>
        </div>

        @foreach($matchesOnDate as $match)
            @php
                $homeName = $match->homeTeam->name ?? 'TBA';
                $awayName = $match->awayTeam->name ?? 'TBA';
                $homeLogo = $match->homeTeam->logo ?? null;
                $awayLogo = $match->awayTeam->logo ?? null;

                $homeAbbr = 'TBA';
                if (!empty($homeName) && $homeName !== 'TBA') {
                    $words = explode(' ', $homeName);
                    $homeAbbr = count($words) >= 2
                        ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
                        : strtoupper(substr($homeName, 0, 2));
                }

                $awayAbbr = 'TBA';
                if (!empty($awayName) && $awayName !== 'TBA') {
                    $words = explode(' ', $awayName);
                    $awayAbbr = count($words) >= 2
                        ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
                        : strtoupper(substr($awayName, 0, 2));
                }

                $homeLogoUrl = null;
                if ($homeLogo) {
                    if (filter_var($homeLogo, FILTER_VALIDATE_URL)) {
                        $homeLogoUrl = $homeLogo;
                    } else {
                        try {
                            if (Storage::disk('public')->exists($homeLogo)) {
                                $homeLogoUrl = asset('storage/' . $homeLogo);
                            }
                        } catch (Exception $e) {
                            $homeLogoUrl = null;
                        }
                    }
                }

                $awayLogoUrl = null;
                if ($awayLogo) {
                    if (filter_var($awayLogo, FILTER_VALIDATE_URL)) {
                        $awayLogoUrl = $awayLogo;
                    } else {
                        try {
                            if (Storage::disk('public')->exists($awayLogo)) {
                                $awayLogoUrl = asset('storage/' . $awayLogo);
                            }
                        } catch (Exception $e) {
                            $awayLogoUrl = null;
                        }
                    }
                }

                $hasExtras = ($match->et_score || ($match->is_penalty && $match->penalty_score));
            @endphp

            <div class="schedule-match-row">
                {{-- Match Time & Venue --}}
                <div class="schedule-match-time-col">
                    <div class="schedule-time-text">
                        {{ $match->time_start ? date('H:i', strtotime($match->time_start)) : 'TBD' }}
                    </div>
                    <div class="schedule-venue-text" title="{{ $match->venue ?? 'Main Field' }}">
                        <i class="bi bi-geo-alt"></i>
                        {{ Str::limit($match->venue ?? 'Main Field', 14) }}
                    </div>
                </div>

                {{-- Teams & Score --}}
                <div class="schedule-teams-center">
                    {{-- Home Team --}}
                    <div class="schedule-team-item schedule-team-home">
                        <span class="schedule-team-name" title="{{ $homeName }}">
                            {{ Str::limit($homeName, 18) }}
                        </span>
                        <div class="schedule-team-logo" title="{{ $homeName }}">
                            @if($homeLogoUrl)
                                <img src="{{ $homeLogoUrl }}" alt="{{ $homeName }}"
                                     onerror="this.style.display='none'; this.parentElement.innerText='{{ $homeAbbr }}';">
                            @else
                                {{ $homeAbbr }}
                            @endif
                        </div>
                    </div>

                    {{-- Score Container --}}
                    <div class="schedule-score-container">
                        @if($match->status === 'completed')
                            <div class="schedule-score-badge">
                                {{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}
                            </div>
                            @if($hasExtras)
                                <div class="schedule-extra-scores">
                                    @if($match->et_score)
                                        <span class="schedule-chip-et">ET {{ $match->et_score }}</span>
                                    @endif
                                    @if($match->is_penalty && $match->penalty_score)
                                        <span class="schedule-chip-penalty">
                                            <i class="bi bi-flag"></i> {{ $match->penalty_score }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        @elseif($match->status === 'ongoing')
                            <div class="schedule-score-badge is-live">
                                <span class="schedule-live-dot"></span>
                                {{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}
                            </div>
                        @else
                            <div class="schedule-score-badge is-vs">VS</div>
                        @endif
                    </div>

                    {{-- Away Team --}}
                    <div class="schedule-team-item schedule-team-away">
                        <div class="schedule-team-logo" title="{{ $awayName }}">
                            @if($awayLogoUrl)
                                <img src="{{ $awayLogoUrl }}" alt="{{ $awayName }}"
                                     onerror="this.style.display='none'; this.parentElement.innerText='{{ $awayAbbr }}';">
                            @else
                                {{ $awayAbbr }}
                            @endif
                        </div>
                        <span class="schedule-team-name" title="{{ $awayName }}">
                            {{ Str::limit($awayName, 18) }}
                        </span>
                    </div>
                </div>

                {{-- Status, Round & Action --}}
                <div class="schedule-match-action-col">
                    <div class="d-flex align-items-center gap-1 flex-wrap justify-content-end">
                        @if($match->status === 'completed')
                            <span class="schedule-status-badge status-badge-completed">Completed</span>
                        @elseif($match->status === 'ongoing')
                            <span class="schedule-status-badge status-badge-ongoing">Live</span>
                        @else
                            <span class="schedule-status-badge status-badge-upcoming">Upcoming</span>
                        @endif

                        @if($match->round_type)
                            <span class="schedule-round-badge">
                                @if($match->round_type === 'friendly')
                                    Friendly
                                @elseif($match->round_type === 'group')
                                    Gr. {{ $match->group_name }}
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $match->round_type)) }}
                                @endif
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('matches.show', $match->id) }}" class="btn-action-secondary btn-action-sm mt-1">
                        <i class="bi bi-eye"></i> View
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endforeach

{{-- Pagination Links --}}
@if($matches->hasPages())
    <div class="schedule-pagination-wrapper">
        {{ $matches->links('pagination::bootstrap-5') }}
    </div>
@endif

