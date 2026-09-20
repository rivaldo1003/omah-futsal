{{-- Standings Empty State Component --}}
<div class="standings-empty">
    <div class="standings-empty-icon">
        <i class="bi bi-bar-chart"></i>
    </div>
    <h4 class="standings-empty-title">No standings available</h4>
    <p class="standings-empty-text">
        Select a tournament to view standings
    </p>
    @if(isset($tournaments) && $tournaments->count() > 0)
        <div class="standings-tournament-buttons">
            @foreach($tournaments as $tournament)
                <a href="{{ route('standings') }}?tournament_id={{ $tournament->id }}" class="btn-action-secondary btn-action-sm">
                    <i class="bi bi-trophy"></i>
                    {{ $tournament->name }}
                </a>
            @endforeach
        </div>
    @endif
</div>
