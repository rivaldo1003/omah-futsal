<!-- resources/views/admin/tournaments/schedule.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-calendar-event"></i> Tournament Schedule: {{ $tournament->name }}</h1>
            <p class="lead">Manage matches and schedule for {{ $tournament->name }}</p>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateScheduleModal">
                <i class="bi bi-magic"></i> Generate Schedule
            </button>
            <a href="{{ route('admin.tournaments.show', $tournament) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Tournament
            </a>
        </div>
    </div>

    <!-- Schedule Tabs -->
    <ul class="nav nav-tabs mb-4" id="scheduleTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="group-tab" data-bs-toggle="tab" data-bs-target="#group" type="button">
                <i class="bi bi-grid-3x3"></i> Group Stage
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="knockout-tab" data-bs-toggle="tab" data-bs-target="#knockout" type="button">
                <i class="bi bi-trophy"></i> Knockout Stage
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar" type="button">
                <i class="bi bi-calendar-week"></i> Calendar View
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="scheduleTabsContent">
        <!-- Group Stage -->
        <div class="tab-pane fade show active" id="group" role="tabpanel">
            @php
                $groupMatches = $tournament->matches()
                    ->where('round_type', 'group')
                    ->with(['homeTeam', 'awayTeam'])
                    ->orderBy('match_date')
                    ->orderBy('time_start')
                    ->get()
                    ->groupBy('group_name');
            @endphp
            
            @foreach($groupMatches as $groupName => $matches)
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-people"></i> Group {{ $groupName }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Match</th>
                                        <th>Venue</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matches as $match)
                                        <tr>
                                            <td>
                                                <div>{{ $match->match_date->format('d M Y') }}</div>
                                                <small class="text-muted">{{ $match->time_start }} - {{ $match->time_end }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="text-end" style="width: 40%;">
                                                        <strong>{{ $match->homeTeam->name }}</strong>
                                                        <br>
                                                        <span class="badge bg-primary">{{ $match->home_score }}</span>
                                                    </div>
                                                    <div class="text-center mx-2" style="width: 20%;">
                                                        <small class="text-muted">vs</small>
                                                    </div>
                                                    <div class="text-start" style="width: 40%;">
                                                        <strong>{{ $match->awayTeam->name }}</strong>
                                                        <br>
                                                        <span class="badge bg-primary">{{ $match->away_score }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $match->venue ?? $tournament->location }}</td>
                                            <td>
                                                @switch($match->status)
                                                    @case('upcoming')
                                                        <span class="badge bg-secondary">Upcoming</span>
                                                        @break
                                                    @case('ongoing')
                                                        <span class="badge bg-warning">Ongoing</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-success">Completed</span>
                                                        @break
                                                    @case('postponed')
                                                        <span class="badge bg-danger">Postponed</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editMatchModal"
                                                        data-match-id="{{ $match->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Knockout Stage -->
        <div class="tab-pane fade" id="knockout" role="tabpanel">
            @php
                $knockoutMatches = $tournament->matches()
                    ->where('round_type', '!=', 'group')
                    ->with(['homeTeam', 'awayTeam'])
                    ->orderBy('match_date')
                    ->orderBy('time_start')
                    ->get()
                    ->groupBy('round_type');
            @endphp
            
            <div class="row">
                @foreach($knockoutMatches as $roundType => $matches)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-trophy"></i> 
                                    {{ ucfirst(str_replace('_', ' ', $roundType)) }}
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($matches as $match)
                                    <div class="match-card mb-3 p-3 border rounded">
                                        <div class="text-center mb-2">
                                            <small class="text-muted">
                                                {{ $match->match_date->format('d M Y') }} | 
                                                {{ $match->time_start }}
                                            </small>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center" style="width: 45%;">
                                                <strong>{{ $match->homeTeam->name ?? 'TBD' }}</strong>
                                                <div class="score {{ $match->home_score > $match->away_score ? 'fw-bold' : '' }}">
                                                    {{ $match->home_score }}
                                                </div>
                                            </div>
                                            <div class="text-center" style="width: 10%;">
                                                <small class="text-muted">vs</small>
                                            </div>
                                            <div class="text-center" style="width: 45%;">
                                                <strong>{{ $match->awayTeam->name ?? 'TBD' }}</strong>
                                                <div class="score {{ $match->away_score > $match->home_score ? 'fw-bold' : '' }}">
                                                    {{ $match->away_score }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center mt-2">
                                            <span class="badge bg-secondary">{{ $match->venue ?? $tournament->location }}</span>
                                            <span class="badge bg-{{ $match->status === 'completed' ? 'success' : 'warning' }}">
                                                {{ $match->status }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Calendar View -->
        <div class="tab-pane fade" id="calendar" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Schedule Calendar</h5>
                </div>
                <div class="card-body">
                    <div id="scheduleCalendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Schedule Modal -->
<div class="modal fade" id="generateScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-magic"></i> Generate Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.tournaments.schedule.generate', $tournament) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        This will generate a complete schedule based on tournament settings and team assignments.
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="clearExisting" name="clear_existing" checked>
                        <label class="form-check-label" for="clearExisting">
                            Clear existing matches before generating
                        </label>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Warning:</strong> This action cannot be undone. All existing matches will be deleted.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-magic"></i> Generate Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Match Modal -->
<div class="modal fade" id="editMatchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil"></i> Edit Match</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editMatchForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Form will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<style>
    .match-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transition: all 0.3s;
    }
    
    .match-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .score {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2C3E50;
    }
    
    .fc-event {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize FullCalendar
        var calendarEl = document.getElementById('scheduleCalendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($calendarEvents ?? []),
            eventClick: function(info) {
                // Load match details when event is clicked
                loadMatchDetails(info.event.id);
            }
        });
        calendar.render();
        
        // Load edit match form
        $('#editMatchModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var matchId = button.data('match-id');
            
            $.ajax({
                url: '/admin/matches/' + matchId + '/edit',
                method: 'GET',
                success: function(response) {
                    $('#editMatchForm .modal-body').html(response);
                    $('#editMatchForm').attr('action', '/admin/matches/' + matchId);
                }
            });
        });
    });
    
    function loadMatchDetails(matchId) {
        // Implement match details loading
        console.log('Loading match:', matchId);
    }
</script>
@endpush