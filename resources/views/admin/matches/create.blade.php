@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.matches.index') }}">Matches</a></li>
            <li class="breadcrumb-item active">Create Match</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="bi bi-plus-circle"></i> Create New Match</h1>
            <p class="lead">Schedule a new match for your tournament</p>
        </div>
        <div>
            <a href="{{ route('admin.matches.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Matches
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mt-2 mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tournament Selection Form -->
            <form method="GET" action="{{ route('admin.matches.create') }}" class="mb-4">
                <div class="card border-primary mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-trophy"></i> Tournament Selection</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="tournament_id" class="form-label">Select Tournament <span class="text-danger">*</span></label>
                            <select class="form-select @error('tournament_id') is-invalid @enderror" 
                                    id="tournament_id" name="tournament_id" required
                                    onchange="this.form.submit()">
                                <option value="">-- Select a Tournament --</option>
                                @foreach($tournaments as $tournament)
                                    <option value="{{ $tournament->id }}" 
                                        {{ $tournamentId == $tournament->id ? 'selected' : '' }}>
                                        {{ $tournament->name }} 
                                        ({{ ucfirst($tournament->status) }} | 
                                        {{ $tournament->start_date->format('d M Y') }} - {{ $tournament->end_date->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('tournament_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($tournamentId)
                            @php
                                $selectedTournament = $tournaments->where('id', $tournamentId)->first();
                            @endphp
                            <div class="alert alert-success">
                                <i class="bi bi-info-circle"></i>
                                <strong>{{ $selectedTournament->name }}</strong> selected.
                                <br>
                                <small>
                                    Teams: {{ $teams->count() }} | 
                                    Groups: {{ $selectedTournament->groups_count }} |
                                    Type: {{ ucfirst(str_replace('_', ' ', $selectedTournament->type)) }}
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </form>

            @if($tournamentId)
                <!-- Match Creation Form (Only show if tournament is selected) -->
                <form action="{{ route('admin.matches.store') }}" method="POST" id="matchForm">
                    @csrf
                    <input type="hidden" name="tournament_id" value="{{ $tournamentId }}">

                    @if($teams->count() > 0)
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="bi bi-info-circle"></i> Basic Information</h5>

                                <div class="mb-3">
                                    <label for="match_date" class="form-label">Match Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('match_date') is-invalid @enderror"
                                        id="match_date" name="match_date" 
                                        value="{{ old('match_date') }}" 
                                        min="{{ $selectedTournament->start_date->format('Y-m-d') }}"
                                        max="{{ $selectedTournament->end_date->format('Y-m-d') }}"
                                        required>
                                    @error('match_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Tournament date range: {{ $selectedTournament->start_date->format('d M Y') }} - {{ $selectedTournament->end_date->format('d M Y') }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="time_start" class="form-label">Start Time <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control @error('time_start') is-invalid @enderror"
                                            id="time_start" name="time_start" value="{{ old('time_start', '14:00') }}" required>
                                        @error('time_start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="time_end" class="form-label">End Time <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control @error('time_end') is-invalid @enderror"
                                            id="time_end" name="time_end" value="{{ old('time_end', '15:40') }}" required>
                                        @error('time_end')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="venue" class="form-label">Venue</label>
                                    <input type="text" class="form-control @error('venue') is-invalid @enderror" id="venue"
                                        name="venue" value="{{ old('venue', $selectedTournament->location ?? 'Main Field') }}"
                                        placeholder="e.g., Main Field, Court 1">
                                    @error('venue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Teams Selection -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="bi bi-people"></i> Teams Selection</h5>

                                <div class="mb-3">
                                    <label for="team_home_id" class="form-label">Home Team <span class="text-danger">*</span></label>
                                    <select class="form-select @error('team_home_id') is-invalid @enderror" id="team_home_id"
                                        name="team_home_id" required>
                                        <option value="">-- Select Home Team --</option>
                                        @foreach($teams as $team)
                                            @php
                                                $teamGroup = $team->tournaments()
                                                    ->where('tournament_id', $tournamentId)
                                                    ->first()->pivot->group_name ?? 'N/A';
                                            @endphp
                                            <option value="{{ $team->id }}" 
                                                data-group="{{ $teamGroup }}"
                                                {{ old('team_home_id') == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }} (Group {{ $teamGroup }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('team_home_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="team_away_id" class="form-label">Away Team <span class="text-danger">*</span></label>
                                    <select class="form-select @error('team_away_id') is-invalid @enderror" id="team_away_id"
                                        name="team_away_id" required>
                                        <option value="">-- Select Away Team --</option>
                                        @foreach($teams as $team)
                                            @php
                                                $teamGroup = $team->tournaments()
                                                    ->where('tournament_id', $tournamentId)
                                                    ->first()->pivot->group_name ?? 'N/A';
                                            @endphp
                                            <option value="{{ $team->id }}" 
                                                data-group="{{ $teamGroup }}"
                                                {{ old('team_away_id') == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }} (Group {{ $teamGroup }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('team_away_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="teamGroupInfo" class="alert alert-info mt-3" style="display: none;">
                                    <i class="bi bi-info-circle"></i>
                                    <span id="teamGroupMessage"></span>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <!-- Match Settings -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="bi bi-gear"></i> Match Settings</h5>

                                <div class="mb-3">
                                    <label for="round_type" class="form-label">Round Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('round_type') is-invalid @enderror" id="round_type"
                                        name="round_type" required>
                                        @foreach($roundTypes as $type)
                                            <option value="{{ $type }}" {{ old('round_type', 'group') == $type ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('round_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                               <!-- Update this section in your view -->
<div class="mb-3" id="group_name_field">
    <label for="group_name" class="form-label">Group Name</label>
    <select class="form-select @error('group_name') is-invalid @enderror" id="group_name"
            name="group_name">
        <option value="">-- Auto-select based on teams --</option>
        @if($tournamentId)
            @php
                $tournament = \App\Models\Tournament::find($tournamentId);
                $groupOptions = [];
                if ($tournament && $tournament->groups_count) {
                    for ($i = 0; $i < min($tournament->groups_count, 8); $i++) {
                        $groupOptions[] = chr(65 + $i); // A, B, C, dst
                    }
                }
            @endphp
            @foreach($groupOptions as $group)
                <option value="{{ $group }}" {{ old('group_name') == $group ? 'selected' : '' }}>
                    Group {{ $group }}
                </option>
            @endforeach
        @else
            @foreach(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'] as $group)
                <option value="{{ $group }}" {{ old('group_name') == $group ? 'selected' : '' }}>
                    Group {{ $group }}
                </option>
            @endforeach
        @endif
    </select>
    @error('group_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text">Will auto-select based on teams' group</div>
</div>
                            </div>

                            <!-- Status & Additional Info -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="bi bi-clock-history"></i> Status & Information</h5>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Match Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                        required>
                                        @foreach($statusOptions as $status)
                                            <option value="{{ $status }}" {{ old('status', 'upcoming') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="home_score" class="form-label">Home Score</label>
                                            <input type="number" class="form-control @error('home_score') is-invalid @enderror"
                                                id="home_score" name="home_score" value="{{ old('home_score', 0) }}" min="0">
                                            @error('home_score')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="away_score" class="form-label">Away Score</label>
                                            <input type="number" class="form-control @error('away_score') is-invalid @enderror"
                                                id="away_score" name="away_score" value="{{ old('away_score', 0) }}" min="0">
                                            @error('away_score')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                                placeholder="Any additional information about this match...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.matches.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create Match
                            </button>
                        </div>
                    @else
                        <!-- No Teams Warning -->
                        <div class="alert alert-warning text-center py-5">
                            <i class="bi bi-people display-4"></i>
                            <h4 class="mt-3">No Teams in This Tournament</h4>
                            <p class="text-muted">You need to add teams to the tournament before creating matches.</p>
                            <a href="{{ route('admin.tournaments.teams', $tournamentId) }}" class="btn btn-primary mt-3">
                                <i class="bi bi-plus-circle"></i> Add Teams to Tournament
                            </a>
                        </div>
                    @endif
                </form>
            @else
                <!-- No Tournament Selected -->
                <div class="text-center py-5">
                    <i class="bi bi-trophy display-4 text-muted"></i>
                    <h3 class="mt-3">Select a Tournament First</h3>
                    <p class="text-muted">Please select a tournament from the dropdown above to create a match.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Information Box -->
    @if($tournamentId && $teams->count() > 0)
        <div class="alert alert-info mt-4">
            <i class="bi bi-lightbulb"></i>
            <div>
                <strong>Quick Tips for {{ $selectedTournament->name }}:</strong>
                <ul class="mb-0 mt-2">
                    <li>For group stage matches, both teams must be in the same group</li>
                    <li>Knockout matches don't require group assignment</li>
                    <li>Match date must be within tournament dates: {{ $selectedTournament->start_date->format('d M Y') }} - {{ $selectedTournament->end_date->format('d M Y') }}</li>
                    <li>You can update scores later if the match is ongoing or completed</li>
                </ul>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roundTypeSelect = document.getElementById('round_type');
        const groupNameField = document.getElementById('group_name_field');
        const groupNameSelect = document.getElementById('group_name');
        const homeTeamSelect = document.getElementById('team_home_id');
        const awayTeamSelect = document.getElementById('team_away_id');
        const teamGroupInfo = document.getElementById('teamGroupInfo');
        const teamGroupMessage = document.getElementById('teamGroupMessage');
        
        function toggleGroupField() {
            if (roundTypeSelect.value === 'group') {
                groupNameField.style.display = 'block';
            } else {
                groupNameField.style.display = 'none';
            }
        }

        // Auto-select group based on selected teams
        function updateGroupSelection() {
            if (homeTeamSelect.value && awayTeamSelect.value) {
                const homeGroup = homeTeamSelect.options[homeTeamSelect.selectedIndex].dataset.group;
                const awayGroup = awayTeamSelect.options[awayTeamSelect.selectedIndex].dataset.group;
                
                // Show group info
                teamGroupInfo.style.display = 'block';
                
                if (homeGroup === awayGroup) {
                    teamGroupMessage.innerHTML = `Both teams are in <strong>Group ${homeGroup}</strong>. Group will auto-select.`;
                    
                    // Auto-select group in dropdown
                    for (let option of groupNameSelect.options) {
                        if (option.value === homeGroup) {
                            groupNameSelect.value = homeGroup;
                            break;
                        }
                    }
                } else {
                    teamGroupMessage.innerHTML = `
                        <span class="text-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                            Teams are in different groups! 
                            Home: <strong>Group ${homeGroup}</strong>, 
                            Away: <strong>Group ${awayGroup}</strong>
                        </span>
                        <br>
                        <small>For group stage matches, select teams from the same group.</small>
                    `;
                    groupNameSelect.value = '';
                }
            } else {
                teamGroupInfo.style.display = 'none';
            }
        }

        // Initial state
        if (roundTypeSelect) {
            toggleGroupField();
            roundTypeSelect.addEventListener('change', toggleGroupField);
        }

        // Auto-calculate end time based on start time
        const timeStart = document.getElementById('time_start');
        const timeEnd = document.getElementById('time_end');

        if (timeStart && timeEnd) {
            timeStart.addEventListener('change', function () {
                if (timeStart.value && !timeEnd.value) {
                    const startTime = new Date(`1970-01-01T${timeStart.value}:00`);
                    startTime.setMinutes(startTime.getMinutes() + 100); // 1 hour 40 minutes for futsal

                    const hours = startTime.getHours().toString().padStart(2, '0');
                    const minutes = startTime.getMinutes().toString().padStart(2, '0');

                    timeEnd.value = `${hours}:${minutes}`;
                }
            });
        }

        // Team selection events
        if (homeTeamSelect && awayTeamSelect) {
            homeTeamSelect.addEventListener('change', updateGroupSelection);
            awayTeamSelect.addEventListener('change', updateGroupSelection);
            
            // Initial check
            updateGroupSelection();
        }

        // Form validation for group stage matches
        const matchForm = document.getElementById('matchForm');
        if (matchForm) {
            matchForm.addEventListener('submit', function (e) {
                if (roundTypeSelect.value === 'group') {
                    const homeGroup = homeTeamSelect.options[homeTeamSelect.selectedIndex].dataset.group;
                    const awayGroup = awayTeamSelect.options[awayTeamSelect.selectedIndex].dataset.group;
                    
                    if (homeGroup !== awayGroup) {
                        e.preventDefault();
                        alert('For group stage matches, both teams must be in the same group. Please select teams from the same group.');
                        return false;
                    }
                }
                return true;
            });
        }
    });
</script>
@endsection