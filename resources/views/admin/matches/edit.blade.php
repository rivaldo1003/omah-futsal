@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.matches.index') }}">Matches</a></li>
            <li class="breadcrumb-item active">Edit Match</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="bi bi-pencil-square"></i> Edit Match</h1>
            <p class="lead">Update match details and scheduling</p>
        </div>
        <div>
            <a href="{{ route('admin.matches.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Matches
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> Please fix the following errors:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.matches.update', $match) }}" method="POST" id="matchForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Left Column: Tournament & Basic Info -->
                    <div class="col-md-6">
                        <h5 class="mb-4 text-primary">Tournament & Basic Information</h5>
                        
                        <!-- Tournament Selection -->
                        <div class="mb-3">
                            <label for="tournament_id" class="form-label">Tournament *</label>
                            <select name="tournament_id" id="tournament_id" class="form-select @error('tournament_id') is-invalid @enderror" required>
                                <option value="">Select Tournament</option>
                                @foreach($tournaments as $tournament)
                                    <option value="{{ $tournament->id }}" 
                                        {{ old('tournament_id', $match->tournament_id) == $tournament->id ? 'selected' : '' }}
                                        data-groups-count="{{ $tournament->groups_count ?? 0 }}">
                                        {{ $tournament->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tournament_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Round Type -->
                        <div class="mb-3">
                            <label for="round_type" class="form-label">Round Type *</label>
                            <select name="round_type" id="round_type" class="form-select @error('round_type') is-invalid @enderror" required>
                                <option value="">Select Round Type</option>
                                <option value="group" {{ old('round_type', $match->round_type) == 'group' ? 'selected' : '' }}>Group Stage</option>
                                <option value="quarterfinal" {{ old('round_type', $match->round_type) == 'quarterfinal' ? 'selected' : '' }}>Quarter Final</option>
                                <option value="semifinal" {{ old('round_type', $match->round_type) == 'semifinal' ? 'selected' : '' }}>Semi Final</option>
                                <option value="final" {{ old('round_type', $match->round_type) == 'final' ? 'selected' : '' }}>Final</option>
                                <option value="third_place" {{ old('round_type', $match->round_type) == 'third_place' ? 'selected' : '' }}>Third Place</option>
                            </select>
                            @error('round_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Group Name (only show if group stage) -->
                        <div class="mb-3" id="groupField" style="{{ old('round_type', $match->round_type) == 'group' ? '' : 'display: none;' }}">
                            <label for="group_name" class="form-label">Group Name *</label>
                            <select name="group_name" id="group_name" class="form-select @error('group_name') is-invalid @enderror">
                                <option value="">Select Group</option>
                                @foreach($groupOptions as $group)
                                    <option value="{{ $group }}" {{ old('group_name', $match->group_name) == $group ? 'selected' : '' }}>
                                        Group {{ $group }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Match Date -->
                        <div class="mb-3">
                            <label for="match_date" class="form-label">Match Date *</label>
                            <input type="date" name="match_date" id="match_date" 
                                   class="form-control @error('match_date') is-invalid @enderror" 
                                   value="{{ old('match_date', $match->match_date ? $match->match_date->format('Y-m-d') : '') }}" 
                                   required>
                            @error('match_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Match Time -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="time_start" class="form-label">Start Time *</label>
                                <input type="time" name="time_start" id="time_start" 
                                       class="form-control @error('time_start') is-invalid @enderror" 
                                       value="{{ old('time_start', $match->time_start) }}" 
                                       required>
                                @error('time_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="time_end" class="form-label">End Time *</label>
                                <input type="time" name="time_end" id="time_end" 
                                       class="form-control @error('time_end') is-invalid @enderror" 
                                       value="{{ old('time_end', $match->time_end) }}" 
                                       required>
                                @error('time_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Teams & Status -->
                    <div class="col-md-6">
                        <h5 class="mb-4 text-primary">Teams & Status</h5>
                        
                        <!-- Teams Selection -->
                        <div class="mb-3">
                            <label class="form-label">Teams *</label>
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <select name="team_home_id" id="team_home_id" class="form-select @error('team_home_id') is-invalid @enderror" required>
                                        <option value="">Select Home Team</option>
                                        @foreach($teams as $team)
                                            @php
                                                // Ambil group_name dari pivot table
                                                $teamGroup = $team->tournaments
                                                    ->where('id', $match->tournament_id)
                                                    ->first()
                                                    ->pivot
                                                    ->group_name ?? '';
                                            @endphp
                                            <option value="{{ $team->id }}" 
                                                {{ old('team_home_id', $match->team_home_id) == $team->id ? 'selected' : '' }}
                                                data-group="{{ $teamGroup }}">
                                                {{ $team->name }} @if($teamGroup) (Group {{ $teamGroup }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('team_home_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-2 text-center">
                                    <span class="fw-bold">VS</span>
                                </div>
                                <div class="col-5">
                                    <select name="team_away_id" id="team_away_id" class="form-select @error('team_away_id') is-invalid @enderror" required>
                                        <option value="">Select Away Team</option>
                                        @foreach($teams as $team)
                                            @php
                                                // Ambil group_name dari pivot table
                                                $teamGroup = $team->tournaments
                                                    ->where('id', $match->tournament_id)
                                                    ->first()
                                                    ->pivot
                                                    ->group_name ?? '';
                                            @endphp
                                            <option value="{{ $team->id }}" 
                                                {{ old('team_away_id', $match->team_away_id) == $team->id ? 'selected' : '' }}
                                                data-group="{{ $teamGroup }}">
                                                {{ $team->name }} @if($teamGroup) (Group {{ $teamGroup }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('team_away_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-text mt-2">
                                <div id="teamValidationMessage" class="text-danger" style="display: none;">
                                    <i class="bi bi-exclamation-triangle"></i> <span id="validationText"></span>
                                </div>
                                <div id="teamInfoMessage" class="text-success" style="display: none;">
                                    <i class="bi bi-check-circle"></i> <span id="infoText"></span>
                                </div>
                                <div id="defaultMessage">
                                    Ensure both teams are from the same group for group stage matches.
                                </div>
                            </div>
                        </div>

                        <!-- Venue -->
                        <div class="mb-3">
                            <label for="venue" class="form-label">Venue</label>
                            <input type="text" name="venue" id="venue" 
                                   class="form-control @error('venue') is-invalid @enderror" 
                                   value="{{ old('venue', $match->venue) }}" 
                                   placeholder="e.g., Main Stadium">
                            @error('venue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Match Status *</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="upcoming" {{ old('status', $match->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ old('status', $match->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status', $match->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="postponed" {{ old('status', $match->status) == 'postponed' ? 'selected' : '' }}>Postponed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Scores (only show if completed) -->
                        <div class="mb-3" id="scoreField" style="{{ old('status', $match->status) == 'completed' ? '' : 'display: none;' }}">
                            <label class="form-label">Final Score</label>
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <input type="number" name="home_score" 
                                           class="form-control @error('home_score') is-invalid @enderror" 
                                           value="{{ old('home_score', $match->home_score) }}" 
                                           min="0" placeholder="Home score">
                                    @error('home_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-2 text-center">
                                    <span class="fw-bold">-</span>
                                </div>
                                <div class="col-5">
                                    <input type="number" name="away_score" 
                                           class="form-control @error('away_score') is-invalid @enderror" 
                                           value="{{ old('away_score', $match->away_score) }}" 
                                           min="0" placeholder="Away score">
                                    @error('away_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-text">
                                Fill scores only if match is completed.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-4">
                    <label for="notes" class="form-label">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="form-control @error('notes') is-invalid @enderror" 
                              placeholder="Any additional information about this match...">{{ old('notes', $match->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top">
                    <div>
                        <a href="{{ route('admin.matches.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Match
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roundTypeSelect = document.getElementById('round_type');
    const groupField = document.getElementById('groupField');
    const statusSelect = document.getElementById('status');
    const scoreField = document.getElementById('scoreField');
    const tournamentSelect = document.getElementById('tournament_id');
    const homeTeamSelect = document.getElementById('team_home_id');
    const awayTeamSelect = document.getElementById('team_away_id');
    const groupNameSelect = document.getElementById('group_name');
    const matchForm = document.getElementById('matchForm');
    
    // Message elements
    const validationMessage = document.getElementById('teamValidationMessage');
    const validationText = document.getElementById('validationText');
    const infoMessage = document.getElementById('teamInfoMessage');
    const infoText = document.getElementById('infoText');
    const defaultMessage = document.getElementById('defaultMessage');

    // Store team group data from data-group attributes
    const teamGroups = {};
    
    // Collect data from home team options
    Array.from(homeTeamSelect.options).forEach(option => {
        if (option.value && option.dataset.group) {
            teamGroups[option.value] = option.dataset.group;
        }
    });
    
    // Collect data from away team options
    Array.from(awayTeamSelect.options).forEach(option => {
        if (option.value && option.dataset.group && !teamGroups[option.value]) {
            teamGroups[option.value] = option.dataset.group;
        }
    });

    console.log('Team groups data:', teamGroups); // For debugging

    // Toggle group field based on round type
    function toggleGroupField() {
        if (roundTypeSelect.value === 'group') {
            groupField.style.display = 'block';
            groupNameSelect.setAttribute('required', 'required');
            validateTeamsInSameGroup();
        } else {
            groupField.style.display = 'none';
            groupNameSelect.removeAttribute('required');
            clearTeamValidationMessages();
        }
    }

    // Toggle score field based on status
    function toggleScoreField() {
        if (statusSelect.value === 'completed') {
            scoreField.style.display = 'block';
        } else {
            scoreField.style.display = 'none';
        }
    }

    // Validate if teams are in the same group
    function validateTeamsInSameGroup() {
        const homeTeamId = homeTeamSelect.value;
        const awayTeamId = awayTeamSelect.value;
        const roundType = roundTypeSelect.value;
        
        // Clear previous messages
        validationMessage.style.display = 'none';
        infoMessage.style.display = 'none';
        defaultMessage.style.display = 'block';
        
        if (!homeTeamId || !awayTeamId || homeTeamId === awayTeamId) {
            return;
        }
        
        const homeGroup = teamGroups[homeTeamId];
        const awayGroup = teamGroups[awayTeamId];
        
        console.log('Validating teams:', { homeTeamId, awayTeamId, homeGroup, awayGroup, roundType });
        
        if (roundType === 'group') {
            if (!homeGroup || !awayGroup) {
                // Teams don't have group data
                validationText.textContent = 'One or both teams are not assigned to a group. Please check team registration.';
                validationMessage.style.display = 'block';
                defaultMessage.style.display = 'none';
                return false;
            }
            
            if (homeGroup !== awayGroup) {
                // Teams are in different groups
                validationText.textContent = `Teams are in different groups (Group ${homeGroup} vs Group ${awayGroup}). Group stage matches require teams from the same group.`;
                validationMessage.style.display = 'block';
                defaultMessage.style.display = 'none';
                return false;
            } else {
                // Teams are in the same group
                infoText.textContent = `Both teams are in Group ${homeGroup}.`;
                infoMessage.style.display = 'block';
                defaultMessage.style.display = 'none';
                return true;
            }
        }
        
        return true;
    }

    // Clear validation messages
    function clearTeamValidationMessages() {
        validationMessage.style.display = 'none';
        infoMessage.style.display = 'none';
        defaultMessage.style.display = 'block';
    }

    // Filter teams by selected group
    function filterTeamsByGroup(groupName) {
        if (!groupName) return;
        
        console.log('Filtering teams by group:', groupName);
        
        // Enable/disable options based on group
        Array.from(homeTeamSelect.options).forEach(option => {
            if (option.value === '') return;
            const teamGroup = teamGroups[option.value];
            option.disabled = teamGroup && teamGroup !== groupName;
            if (option.disabled) {
                option.style.display = 'none';
            } else {
                option.style.display = '';
            }
        });
        
        Array.from(awayTeamSelect.options).forEach(option => {
            if (option.value === '') return;
            const teamGroup = teamGroups[option.value];
            option.disabled = teamGroup && teamGroup !== groupName;
            if (option.disabled) {
                option.style.display = 'none';
            } else {
                option.style.display = '';
            }
        });
        
        validateTeamsInSameGroup();
    }

    // Initialize
    toggleGroupField();
    toggleScoreField();
    
    // If it's group stage, filter teams by current group
    if (roundTypeSelect.value === 'group' && groupNameSelect.value) {
        filterTeamsByGroup(groupNameSelect.value);
    }

    roundTypeSelect.addEventListener('change', toggleGroupField);
statusSelect.addEventListener('change', toggleScoreField);

// Team selection events
homeTeamSelect.addEventListener('change', validateTeamsInSameGroup);
awayTeamSelect.addEventListener('change', validateTeamsInSameGroup);

// Group selection event
groupNameSelect.addEventListener('change', function() {
    filterTeamsByGroup(this.value);
});

// Set min date to today for match date
const matchDateInput = document.getElementById('match_date');
const currentMatchDateString = '{{ $match->match_date ? $match->match_date->format("Y-m-d") : "" }}';
const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD

// Bandingkan string tanggal (YYYY-MM-DD), bukan Date object
if (currentMatchDateString >= today) {
    // Match belum terjadi atau hari ini, set min = today
    matchDateInput.setAttribute('min', today);
    console.log('Match belum terjadi, min date set to:', today);
} else {
    // Match sudah lewat, biarkan bisa diubah (hapus min attribute)
    matchDateInput.removeAttribute('min');
    console.log('Match sudah lewat, min date dihapus');
}

// Validate end time is after start time
document.getElementById('time_start').addEventListener('change', function() {
    const startTime = this.value;
    const endTimeInput = document.getElementById('time_end');
    
    if (startTime && endTimeInput.value && startTime >= endTimeInput.value) {
        alert('End time must be after start time');
        endTimeInput.focus();
    }
});

    // Form validation
    matchForm.addEventListener('submit', function(e) {
        const homeTeamId = homeTeamSelect.value;
        const awayTeamId = awayTeamSelect.value;
        const roundType = roundTypeSelect.value;
        
        console.log('Form submission validation:', { homeTeamId, awayTeamId, roundType });
        
        // Check if teams are the same
        if (homeTeamId && awayTeamId && homeTeamId === awayTeamId) {
            e.preventDefault();
            alert('Home and away teams cannot be the same!');
            return false;
        }
        
        // Validate tournament selection
        const tournamentId = tournamentSelect.value;
        if (!tournamentId) {
            e.preventDefault();
            alert('Please select a tournament');
            tournamentSelect.focus();
            return false;
        }
        
        // Validate group name for group stage
        if (roundType === 'group') {
            const groupName = groupNameSelect.value;
            if (!groupName) {
                e.preventDefault();
                alert('Please select a group name for group stage matches');
                groupNameSelect.focus();
                return false;
            }
            
            // Validate teams are in the same group
            const homeGroup = teamGroups[homeTeamId];
            const awayGroup = teamGroups[awayTeamId];
            
            console.log('Checking groups for validation:', { homeGroup, awayGroup, groupName });
            
            if (!homeGroup || !awayGroup) {
                e.preventDefault();
                alert('One or both teams are not assigned to a group. Please select different teams.');
                return false;
            }
            
            if (homeGroup !== awayGroup) {
                e.preventDefault();
                alert(`Cannot create group stage match: Teams are in different groups (Group ${homeGroup} vs Group ${awayGroup}). Please select teams from the same group.`);
                return false;
            }
            
            // Validate group name matches team group
            if (groupName !== homeGroup) {
                e.preventDefault();
                alert(`Group name (${groupName}) does not match the teams' group (${homeGroup}). Please select the correct group.`);
                return false;
            }
        }
        
        // Auto-update time validation
        const startTime = document.getElementById('time_start').value;
        const endTime = document.getElementById('time_end').value;
        
        if (startTime && endTime && startTime >= endTime) {
            e.preventDefault();
            alert('End time must be after start time');
            document.getElementById('time_end').focus();
            return false;
        }
        
        return true;
    });

    // Auto-update time validation
    document.getElementById('time_end').addEventListener('change', function() {
        const startTime = document.getElementById('time_start').value;
        const endTime = this.value;
        
        if (startTime && endTime && startTime >= endTime) {
            alert('End time must be after start time');
            this.focus();
        }
    });

    // Tournament change event
    tournamentSelect.addEventListener('change', function() {
        const tournamentId = this.value;
        
        if (tournamentId) {
            // Refresh page with selected tournament
            window.location.href = `{{ route('admin.matches.edit', $match) }}?tournament_id=${tournamentId}`;
        }
    });
});
</script>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .invalid-feedback {
        display: block;
    }
    
    #groupField, #scoreField {
        transition: all 0.3s ease;
    }
    
    .form-text {
        font-size: 0.875em;
        color: #6c757d;
    }
    
    select option:disabled {
        color: #ccc;
        background-color: #f8f9fa;
    }
    
    .text-danger {
        color: #dc3545;
    }
    
    .text-success {
        color: #198754;
    }
</style>
@endsection