<div class="standings-page-header">
    <div class="standings-page-heading">
        <p class="standings-page-eyebrow">OFS Futsal Center</p>
        <h1>
            Tournament standings
        </h1>
        @if(isset($selectedTournament))
            <div class="standings-tournament-info">
                {{ $selectedTournament->name }}
            </div>
        @endif
    </div>

    @if(isset($tournaments) && $tournaments->count() > 0)
        <label class="standings-tournament-control">
            <span class="standings-tournament-label">Tournament</span>
            <select class="standings-tournament-select" onchange="window.location.href = this.value"
                aria-label="Select tournament">
                <option value="{{ route('standings') }}">Select tournament</option>
                @foreach($tournaments as $tournament)
                    <option value="{{ route('standings') }}?tournament_id={{ $tournament->id }}" {{ isset($selectedTournament) && $selectedTournament->id == $tournament->id ? 'selected' : '' }}>
                        {{ $tournament->name }}
                    </option>
                @endforeach
            </select>
        </label>
    @endif
</div>