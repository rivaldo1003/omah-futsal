{{-- Page Header: Tournament title + Selector dropdown --}}
<div class="standings-page-header">
    <div>
        <h1>
            <i class="bi bi-trophy-fill"></i>
            Tournament standings
        </h1>
        @if(isset($selectedTournament))
            <div class="standings-tournament-info">
                {{ $selectedTournament->name }}
            </div>
        @endif
    </div>

    @if(isset($tournaments) && $tournaments->count() > 0)
        <select class="standings-tournament-select" onchange="window.location.href = this.value">
            <option value="{{ route('standings') }}">Select tournament</option>
            @foreach($tournaments as $tournament)
                <option value="{{ route('standings') }}?tournament_id={{ $tournament->id }}"
                    {{ isset($selectedTournament) && $selectedTournament->id == $tournament->id ? 'selected' : '' }}>
                    {{ $tournament->name }}
                </option>
            @endforeach
        </select>
    @endif
</div>
