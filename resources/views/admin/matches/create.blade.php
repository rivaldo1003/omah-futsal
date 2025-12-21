@extends('layouts.admin')

    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">


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
                                        ({{ ucfirst($tournament->type) }} | 
                                        {{ ucfirst($tournament->status) }} | 
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
                                $selectedTournament = \App\Models\Tournament::find($tournamentId);
                                $tournamentSettings = json_decode($selectedTournament->settings, true) ?? [];
                                $tournamentType = $selectedTournament->type;
                            @endphp
                            <div class="alert alert-success">
                                <i class="bi bi-info-circle"></i>
                                <strong>{{ $selectedTournament->name }}</strong> selected.
                                <br>
                                <small>
                                    <strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $tournamentType)) }} |
                                    <strong>Teams:</strong> {{ $teams->count() }} |
                                    @if($tournamentType === 'group_knockout')
                                        <strong>Groups:</strong> {{ $selectedTournament->groups_count }} |
                                        <strong>Qualify:</strong> {{ $selectedTournament->qualify_per_group }} per group
                                    @elseif($tournamentType === 'league')
                                        <strong>Rounds:</strong> {{ $tournamentSettings['league_rounds'] ?? 1 }} |
                                        <strong>Format:</strong> Single Round-Robin (No Groups)
                                    @elseif($tournamentType === 'knockout')
                                        <strong>Format:</strong> {{ ucfirst(str_replace('_', ' ', $tournamentSettings['knockout_format'] ?? 'single_elimination')) }} |
                                        <strong>Teams:</strong> {{ $tournamentSettings['knockout_teams'] ?? 8 }}
                                    @endif
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
                                                    ->first()->pivot->group_name ?? null;
                                                $teamSeed = $team->tournaments()
                                                    ->where('tournament_id', $tournamentId)
                                                    ->first()->pivot->seed ?? '';
                                            @endphp
                                            <option value="{{ $team->id }}" 
                                                data-group="{{ $teamGroup ?? '' }}"
                                                data-seed="{{ $teamSeed }}"
                                                {{ old('team_home_id') == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }} 
                                                @if($teamGroup && $tournamentType === 'group_knockout')
                                                    (Group {{ $teamGroup }})
                                                @endif
                                                @if($teamSeed)
                                                    [Seed: {{ $teamSeed }}]
                                                @endif
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
                                                    ->first()->pivot->group_name ?? null;
                                                $teamSeed = $team->tournaments()
                                                    ->where('tournament_id', $tournamentId)
                                                    ->first()->pivot->seed ?? '';
                                            @endphp
                                            <option value="{{ $team->id }}" 
                                                data-group="{{ $teamGroup ?? '' }}"
                                                data-seed="{{ $teamSeed }}"
                                                {{ old('team_away_id') == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }} 
                                                @if($teamGroup && $tournamentType === 'group_knockout')
                                                    (Group {{ $teamGroup }})
                                                @endif
                                                @if($teamSeed)
                                                    [Seed: {{ $teamSeed }}]
                                                @endif
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

                                <!-- Round Type Field -->
                                <div class="mb-3">
                                    @if($tournamentType === 'league')
                                        <!-- Untuk league: round_type selalu 'league' dan group_name null -->
                                        <input type="hidden" name="round_type" value="league">
                                        <input type="hidden" name="group_name" value="">
                                        <input type="hidden" name="stage" value="league">
                                        
                                        <label class="form-label">Round Type <span class="text-danger">*</span></label>
                                        <div class="form-control bg-light" style="cursor: not-allowed;">
                                            <i class="bi bi-trophy"></i> League Match (Single Round-Robin)
                                        </div>
                                        <div class="form-text text-success">
                                            <i class="bi bi-info-circle"></i> League format: All teams play each other once in a single standings table.
                                        </div>
                                    @else
                                        <!-- Untuk tournament lain: dropdown biasa -->
                                        <label for="round_type" class="form-label">Round Type <span class="text-danger">*</span></label>
                                        <select class="form-select @error('round_type') is-invalid @enderror" id="round_type"
                                                name="round_type" required>
                                            <option value="">-- Select Round Type --</option>
                                            @if($tournamentType === 'knockout')
                                                @php
                                                    $knockoutTeams = $tournamentSettings['knockout_teams'] ?? 8;
                                                @endphp
                                                @if($knockoutTeams >= 32)
                                                    <option value="round_of_32" {{ old('round_type') == 'round_of_32' ? 'selected' : '' }}>
                                                        Round of 32
                                                    </option>
                                                @endif
                                                @if($knockoutTeams >= 16)
                                                    <option value="round_of_16" {{ old('round_type') == 'round_of_16' ? 'selected' : '' }}>
                                                        Round of 16
                                                    </option>
                                                @endif
                                                @if($knockoutTeams >= 8)
                                                    <option value="quarterfinal" {{ old('round_type') == 'quarterfinal' ? 'selected' : '' }}>
                                                        Quarterfinal
                                                    </option>
                                                @endif
                                                @if($knockoutTeams >= 4)
                                                    <option value="semifinal" {{ old('round_type') == 'semifinal' ? 'selected' : '' }}>
                                                        Semifinal
                                                    </option>
                                                @endif
                                                <option value="final" {{ old('round_type') == 'final' ? 'selected' : '' }}>
                                                    Final
                                                </option>
                                                @if($tournamentSettings['knockout_third_place'] ?? false)
                                                    <option value="third_place" {{ old('round_type') == 'third_place' ? 'selected' : '' }}>
                                                        Third Place
                                                    </option>
                                                @endif
                                            @elseif($tournamentType === 'group_knockout')
                                                <!-- Group + Knockout -->
                                                <option value="group" {{ old('round_type', 'group') == 'group' ? 'selected' : '' }}>
                                                    Group Stage
                                                </option>
                                                @php
                                                    $qualifyPerGroup = $selectedTournament->qualify_per_group ?? 2;
                                                    $groupsCount = $selectedTournament->groups_count ?? 2;
                                                    $knockoutTeams = $qualifyPerGroup * $groupsCount;
                                                @endphp
                                                @if($knockoutTeams >= 8)
                                                    <option value="quarterfinal" {{ old('round_type') == 'quarterfinal' ? 'selected' : '' }}>
                                                        Quarterfinal
                                                    </option>
                                                @endif
                                                @if($knockoutTeams >= 4)
                                                    <option value="semifinal" {{ old('round_type') == 'semifinal' ? 'selected' : '' }}>
                                                        Semifinal
                                                    </option>
                                                @endif
                                                <option value="final" {{ old('round_type') == 'final' ? 'selected' : '' }}>
                                                    Final
                                                </option>
                                                @if($tournamentSettings['knockout_third_place'] ?? false)
                                                    <option value="third_place" {{ old('round_type') == 'third_place' ? 'selected' : '' }}>
                                                        Third Place
                                                    </option>
                                                @endif
                                            @endif
                                        </select>
                                        @error('round_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text" id="roundTypeHelp">
                                            @if($tournamentType === 'knockout')
                                                Knockout bracket matches
                                            @elseif($tournamentType === 'group_knockout')
                                                Group stage and knockout matches
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Group Name Field - Hanya untuk group_knockout dengan round_type group -->
                                @if($tournamentType === 'group_knockout')
                                    <div class="mb-3" id="group_name_field">
                                        <label for="group_name" class="form-label">Group Name</label>
                                        <select class="form-select @error('group_name') is-invalid @enderror" id="group_name"
                                                name="group_name">
                                            <option value="">-- Select Group --</option>
                                            @if($selectedTournament->groups_count > 0)
                                                @for($i = 0; $i < min($selectedTournament->groups_count, 8); $i++)
                                                    @php
                                                        $groupLetter = chr(65 + $i); // A, B, C, etc.
                                                    @endphp
                                                    <option value="{{ $groupLetter }}" {{ old('group_name') == $groupLetter ? 'selected' : '' }}>
                                                        Group {{ $groupLetter }}
                                                    </option>
                                                @endfor
                                            @else
                                                @foreach(['A', 'B', 'C', 'D'] as $group)
                                                    <option value="{{ $group }}" {{ old('group_name') == $group ? 'selected' : '' }}>
                                                        Group {{ $group }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('group_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            Only required for Group Stage matches
                                        </div>
                                    </div>
                                @elseif($tournamentType === 'knockout')
                                    <!-- Untuk knockout: hidden input group_name = null -->
                                    <input type="hidden" name="group_name" value="">
                                @endif

                                <!-- Stage Field - Auto-set untuk league -->
                                <div class="mb-3">
                                    @if($tournamentType === 'league')
                                        <label class="form-label">Stage</label>
                                        <div class="form-control bg-light" style="cursor: not-allowed;">
                                            <i class="bi bi-trophy"></i> League Stage
                                        </div>
                                    @else
                                        <label for="stage" class="form-label">Stage</label>
                                        <select class="form-select @error('stage') is-invalid @enderror" id="stage"
                                                name="stage">
                                            <option value="">-- Select Stage --</option>
                                            @if($tournamentType === 'group_knockout')
                                                <option value="group" {{ old('stage') == 'group' ? 'selected' : '' }}>Group Stage</option>
                                            @endif
                                            @if($tournamentType === 'knockout' || $tournamentType === 'group_knockout')
                                                <option value="knockout" {{ old('stage') == 'knockout' ? 'selected' : '' }}>Knockout Stage</option>
                                            @endif
                                            <option value="qualification" {{ old('stage') == 'qualification' ? 'selected' : '' }}>Qualification</option>
                                        </select>
                                        @error('stage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    @endif
                                </div>
                            </div>

                            <!-- Status & Additional Info -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="bi bi-clock-history"></i> Status & Information</h5>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Match Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                        required>
                                        <option value="upcoming" {{ old('status', 'upcoming') == 'upcoming' ? 'selected' : '' }}>
                                            Upcoming
                                        </option>
                                        <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>
                                            Ongoing
                                        </option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>
                                        <option value="postponed" {{ old('status') == 'postponed' ? 'selected' : '' }}>
                                            Postponed
                                        </option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Scores -->
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

                                <!-- Round Number -->
                                <div class="mb-3">
                                    <label for="round" class="form-label">Round Number</label>
                                    <input type="number" class="form-control @error('round') is-invalid @enderror"
                                        id="round" name="round" value="{{ old('round', 1) }}" min="1">
                                    @error('round')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        @if($tournamentType === 'league')
                                            League match round number (1, 2, 3, etc.)
                                        @elseif($tournamentType === 'knockout')
                                            Knockout round (1 = first round, 2 = second round, etc.)
                                        @else
                                            Group stage round number
                                        @endif
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Additional Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2"
                                        placeholder="Any additional information about this match...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
                <strong>Quick Tips for {{ $selectedTournament->name }} ({{ ucfirst(str_replace('_', ' ', $tournamentType)) }}):</strong>
                <ul class="mb-0 mt-2">
                    @if($tournamentType === 'group_knockout')
                        <li>For group stage matches, both teams must be in the same group</li>
                        <li>Group stage matches use "Group" round type</li>
                        <li>Knockout stage matches use Quarterfinal, Semifinal, Final round types</li>
                        <li>Group will auto-select based on selected teams</li>
                    @elseif($tournamentType === 'league')
                        <li>League uses "League Match" round type (auto-selected)</li>
                        <li>No group assignment needed - it's a single round-robin</li>
                        <li>All teams play against each other once</li>
                        <li>Only one standings table for all teams</li>
                        <li>Round type and stage are automatically set to "League"</li>
                    @elseif($tournamentType === 'knockout')
                        <li>Knockout matches don't require group assignment</li>
                        <li>Select appropriate round type based on tournament format</li>
                        <li>Round of 32, Round of 16, Quarterfinal, Semifinal, Final available</li>
                    @endif
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
        const tournamentType = "{{ $tournamentType ?? '' }}";
        const roundTypeSelect = document.getElementById('round_type');
        const groupNameField = document.getElementById('group_name_field');
        const homeTeamSelect = document.getElementById('team_home_id');
        const awayTeamSelect = document.getElementById('team_away_id');
        const teamGroupInfo = document.getElementById('teamGroupInfo');
        const teamGroupMessage = document.getElementById('teamGroupMessage');
        
        // Function untuk toggle group field
        function toggleGroupField() {
            if (!roundTypeSelect || !tournamentType) return;
            
            const roundType = roundTypeSelect.value;
            const groupSelect = document.getElementById('group_name');
            
            // Tampilkan group field hanya untuk:
            // 1. Tournament type group_knockout
            // 2. Round type 'group'
            if (tournamentType === 'group_knockout' && roundType === 'group') {
                if (groupNameField) {
                    groupNameField.style.display = 'block';
                    if (groupSelect) {
                        groupSelect.required = true;
                    }
                }
            } else {
                if (groupNameField) {
                    groupNameField.style.display = 'none';
                    if (groupSelect) {
                        groupSelect.required = false;
                    }
                }
            }
        }

        // Auto-select group based on selected teams
        function updateGroupSelection() {
            if (!homeTeamSelect || !awayTeamSelect || !teamGroupInfo || !teamGroupMessage) return;
            
            if (homeTeamSelect.value && awayTeamSelect.value) {
                const homeOption = homeTeamSelect.options[homeTeamSelect.selectedIndex];
                const awayOption = awayTeamSelect.options[awayTeamSelect.selectedIndex];
                
                const homeGroup = homeOption.dataset.group || '';
                const awayGroup = awayOption.dataset.group || '';
                
                // Show group info
                teamGroupInfo.style.display = 'block';
                
                if (homeGroup && awayGroup) {
                    if (homeGroup === awayGroup) {
                        teamGroupMessage.innerHTML = `Both teams are in <strong>Group ${homeGroup}</strong>. Group will auto-select.`;
                        
                        // Auto-select group in dropdown
                        const groupSelect = document.getElementById('group_name');
                        if (groupSelect) {
                            for (let option of groupSelect.options) {
                                if (option.value === homeGroup) {
                                    groupSelect.value = homeGroup;
                                    break;
                                }
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
                        const groupSelect = document.getElementById('group_name');
                        if (groupSelect) {
                            groupSelect.value = '';
                        }
                    }
                } else if (tournamentType === 'league') {
                    teamGroupMessage.innerHTML = `
                        <span class="text-success">
                            <i class="bi bi-info-circle"></i>
                            League format - no group assignment needed.
                        </span>
                        <br>
                        <small>All teams play in a single round-robin format.</small>
                    `;
                } else if (tournamentType === 'knockout') {
                    teamGroupMessage.innerHTML = `
                        <span class="text-success">
                            <i class="bi bi-info-circle"></i>
                            Knockout format - no group assignment needed.
                        </span>
                        <br>
                        <small>Direct elimination bracket.</small>
                    `;
                } else if (tournamentType === 'group_knockout') {
                    teamGroupMessage.innerHTML = `
                        <span class="text-warning">
                            <i class="bi bi-info-circle"></i>
                            One or both teams are not assigned to a group.
                        </span>
                        <br>
                        <small>Select group manually if needed.</small>
                    `;
                } else {
                    teamGroupInfo.style.display = 'none';
                }
            } else {
                teamGroupInfo.style.display = 'none';
            }
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

        // Event Listeners - Hanya untuk tournament yang BUKAN league
        if (roundTypeSelect && tournamentType !== 'league') {
            // Initial state
            toggleGroupField();
            
            // Event listener
            roundTypeSelect.addEventListener('change', toggleGroupField);
        }

        if (homeTeamSelect && awayTeamSelect) {
            homeTeamSelect.addEventListener('change', updateGroupSelection);
            awayTeamSelect.addEventListener('change', updateGroupSelection);
            
            // Initial check
            updateGroupSelection();
        }

        // Form validation
        const matchForm = document.getElementById('matchForm');
        if (matchForm) {
            matchForm.addEventListener('submit', function (e) {
                // Untuk league: tidak perlu validasi tambahan
                if (tournamentType === 'league') {
                    return true;
                }
                
                // Untuk tournament lain
                const roundTypeSelect = document.getElementById('round_type');
                const roundType = roundTypeSelect ? roundTypeSelect.value : 'league';
                
                // Group stage validation hanya untuk group_knockout tournament
                if (tournamentType === 'group_knockout' && roundType === 'group') {
                    const homeOption = homeTeamSelect.options[homeTeamSelect.selectedIndex];
                    const awayOption = awayTeamSelect.options[awayTeamSelect.selectedIndex];
                    
                    const homeGroup = homeOption.dataset.group || '';
                    const awayGroup = awayOption.dataset.group || '';
                    
                    if (homeGroup && awayGroup && homeGroup !== awayGroup) {
                        e.preventDefault();
                        alert('For group stage matches, both teams must be in the same group. Please select teams from the same group.');
                        return false;
                    }
                    
                    // Validasi group_name dipilih
                    const groupSelect = document.getElementById('group_name');
                    if (groupSelect && !groupSelect.value) {
                        e.preventDefault();
                        alert('Please select a group for this match.');
                        return false;
                    }
                }
                
                return true;
            });
        }

        // Set tanggal default ke hari ini jika belum diisi
        const matchDateInput = document.getElementById('match_date');
        if (matchDateInput && !matchDateInput.value) {
            const today = new Date();
            const formattedDate = today.toISOString().split('T')[0];
            matchDateInput.value = formattedDate;
        }
    });
</script>
@endsection