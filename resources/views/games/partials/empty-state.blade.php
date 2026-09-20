{{-- Empty State when no matches match filter criteria --}}
<div class="schedule-empty">
    <div class="schedule-empty-icon">
        <i class="bi bi-calendar-x"></i>
    </div>
    <h3 class="schedule-empty-title">No matches found</h3>
    <p class="schedule-empty-text">
        @if(request()->anyFilled(['search', 'group', 'status', 'date']))
            Try adjusting your search criteria or clear the filters to view all scheduled matches.
        @elseif(isset($selectedTournament) && $selectedTournament)
            No matches scheduled for {{ $selectedTournament->name }} yet.
        @else
            No matches found for the selected criteria.
        @endif
    </p>
    <a href="{{ route('schedule') }}" class="btn-action-primary btn-action-sm d-inline-flex">
        <i class="bi bi-arrow-clockwise"></i> Clear filters
    </a>
</div>
