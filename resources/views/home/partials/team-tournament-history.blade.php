@php
    $tournamentHistory = $team->tournament_history ?? collect();
    $latestEvent = $tournamentHistory->first();
    $latestStateClass = match($latestEvent['state'] ?? 'neutral') {
        'champion' => 'is-champion',
        'runner-up' => 'is-runner-up',
        'eliminated' => 'is-eliminated',
        'ongoing' => 'is-ongoing',
        'upcoming' => 'is-upcoming',
        'placement' => 'is-placement',
        default => 'is-neutral',
    };
@endphp

<div class="team-history-preview">
    <div class="team-history-preview-copy">
        <span class="team-history-preview-count">
            <i class="bi bi-trophy" aria-hidden="true"></i>
            {{ $tournamentHistory->count() }} turnamen
        </span>
        @if($latestEvent)
            <a class="team-history-preview-event" href="{{ route('standings', ['tournament_id' => $latestEvent['id']]) }}">
                {{ $latestEvent['name'] }}
            </a>
            <span class="team-card-event-outcome {{ $latestStateClass }}">{{ $latestEvent['outcome'] }}</span>
        @else
            <span class="team-history-preview-empty">Belum mengikuti turnamen</span>
        @endif
    </div>
    <a class="team-history-preview-link" href="{{ route('teams.history', $team) }}"
        aria-label="Lihat profil dan riwayat turnamen {{ $team->name }}">
        <span>Riwayat tim</span>
        <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
    </a>
</div>
