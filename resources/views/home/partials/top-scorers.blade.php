<div class="app-card">
    <div class="app-card-header">
        <h3 class="app-card-title">
            <i class="bi bi-trophy"></i>
            <span>Top scorers</span>
        </h3>
        @if($activeTournament)
            <span class="app-badge app-badge-default text-truncate" style="max-width: 140px;">{{ $activeTournament->name }}</span>
        @endif
    </div>
    <div class="app-card-body">
        @if($topScorers->count() > 0)
            @foreach($topScorers as $index => $player)
                <div class="scorer-row">
                    <div class="scorer-rank top-{{ min($index + 1, 4) }}">
                        {{ $index + 1 }}
                    </div>

                    <div class="overflow-hidden flex-grow-1">
                        <div class="fw-semibold text-truncate small" title="{{ $player->name ?? 'Unknown player' }}">
                            {{ $player->name ?? 'Unknown player' }}
                        </div>
                        <div class="text-secondary small d-flex align-items-center gap-1 text-truncate">
                            <span class="text-truncate" style="max-width: 100px;">
                                {{ $player->team_name ?? ($player->team->name ?? 'No team') }}
                            </span>
                            @if(isset($player->jersey_number) && $player->jersey_number)
                                <span class="app-badge app-badge-default py-0 px-1" style="font-size: 10px;">#{{ $player->jersey_number }}</span>
                            @endif
                            @php
                                $marketVal = $player->formatted_market_value ?? null;
                            @endphp
                            @if($marketVal)
                                <span class="app-badge app-badge-default py-0 px-1 text-muted" style="font-size: 10px;">{{ $marketVal }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="text-end ps-2 flex-shrink-0">
                        <span class="fw-bold small text-primary d-block">{{ $player->goals ?? 0 }} Gol</span>
                        <div class="d-flex justify-content-end gap-1 small mt-1" style="font-size: 11px;">
                            @if(($player->yellow_cards ?? 0) > 0)
                                <span class="text-warning d-inline-flex align-items-center" title="Yellow cards">
                                    <i class="bi bi-square-fill me-1" style="font-size: 9px;"></i>{{ $player->yellow_cards }}
                                </span>
                            @endif
                            @if(($player->red_cards ?? 0) > 0)
                                <span class="text-danger d-inline-flex align-items-center" title="Red cards">
                                    <i class="bi bi-square-fill me-1" style="font-size: 9px;"></i>{{ $player->red_cards }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-4 text-secondary">
                <i class="bi bi-person-x fs-4 d-block mb-1"></i>
                <span class="small">No tournament statistics available</span>
            </div>
        @endif
    </div>
</div>
