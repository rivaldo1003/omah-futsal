{{-- Summary Stat Metric Cards: Total, Completed, Upcoming, Live --}}
<div class="schedule-stats-grid">
    <div class="schedule-stat-item">
        <div>
            <div class="schedule-stat-label">Total matches</div>
            <div class="schedule-stat-value">{{ $totalMatches ?? 0 }}</div>
        </div>
        <div class="schedule-stat-icon stat-icon-total">
            <i class="bi bi-calendar2-event"></i>
        </div>
    </div>

    <div class="schedule-stat-item">
        <div>
            <div class="schedule-stat-label">Completed</div>
            <div class="schedule-stat-value">{{ $completedMatches ?? 0 }}</div>
        </div>
        <div class="schedule-stat-icon stat-icon-completed">
            <i class="bi bi-check2-circle"></i>
        </div>
    </div>

    <div class="schedule-stat-item">
        <div>
            <div class="schedule-stat-label">Upcoming</div>
            <div class="schedule-stat-value">{{ $upcomingMatches ?? 0 }}</div>
        </div>
        <div class="schedule-stat-icon stat-icon-upcoming">
            <i class="bi bi-clock"></i>
        </div>
    </div>

    <div class="schedule-stat-item">
        <div>
            <div class="schedule-stat-label">Live</div>
            <div class="schedule-stat-value">{{ $ongoingMatches ?? 0 }}</div>
        </div>
        <div class="schedule-stat-icon stat-icon-ongoing">
            <i class="bi bi-broadcast"></i>
        </div>
    </div>
</div>
