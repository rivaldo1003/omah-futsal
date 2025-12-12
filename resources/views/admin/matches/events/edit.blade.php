@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.matches.index') }}">Matches</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.matches.events.index', $match) }}">Match Events</a>
                </li>
                <li class="breadcrumb-item active">Edit Event</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2">
                    <i class="bi bi-pencil text-primary"></i> Edit Match Event
                </h1>
                <p class="text-muted mb-0">
                    {{ $match->homeTeam->name ?? 'Home Team' }} vs {{ $match->awayTeam->name ?? 'Away Team' }}
                    - {{ $match->match_date->format('d M Y') }}
                </p>
            </div>
            <div>
                <a href="{{ route('admin.matches.events.index', $match) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i> Back to Events
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Edit Event Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.matches.events.update', [$match, $event]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Event Type -->
                        <div class="col-md-6 mb-3">
                            <label for="event_type" class="form-label">Event Type *</label>
                            <select class="form-select @error('event_type') is-invalid @enderror" id="event_type"
                                name="event_type" required>
                                <option value="">Select Event Type</option>
                                @foreach($eventTypes as $key => $value)
                                    <option value="{{ $key }}" {{ old('event_type', $event->event_type) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Team -->
                        <div class="col-md-6 mb-3">
                            <label for="team_id" class="form-label">Team *</label>
                            <select class="form-select @error('team_id') is-invalid @enderror" id="team_id" name="team_id"
                                required>
                                <option value="">Select Team</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('team_id', $event->team_id) == $team->id ? 'selected' : '' }}>
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('team_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Player -->
                        <div class="col-md-6 mb-3">
                            <label for="player_id" class="form-label">Player *</label>
                            <select class="form-select @error('player_id') is-invalid @enderror" id="player_id"
                                name="player_id" required>
                                <option value="">Select Player</option>
                                @foreach($players as $player)
                                    <option value="{{ $player->id }}" {{ old('player_id', $event->player_id) == $player->id ? 'selected' : '' }}>
                                        {{ $player->name }} ({{ $player->team->name ?? 'No Team' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('player_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Related Player -->
                        <div class="col-md-6 mb-3">
                            <label for="related_player_id" class="form-label">Related Player (Optional)</label>
                            <select class="form-select @error('related_player_id') is-invalid @enderror"
                                id="related_player_id" name="related_player_id">
                                <option value="">Select Related Player</option>
                                @foreach($players as $player)
                                    <option value="{{ $player->id }}" {{ old('related_player_id', $event->related_player_id) == $player->id ? 'selected' : '' }}>
                                        {{ $player->name }} ({{ $player->team->name ?? 'No Team' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">For assists or substitutions</small>
                            @error('related_player_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Minute -->
                        <div class="col-md-4 mb-3">
                            <label for="minute" class="form-label">Minute *</label>
                            <input type="number" class="form-control @error('minute') is-invalid @enderror" id="minute"
                                name="minute" value="{{ old('minute', $event->minute) }}" min="1" max="120" required>
                            @error('minute')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Checkboxes -->
                        <div class="col-md-8 mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="is_own_goal" name="is_own_goal"
                                    value="1" {{ old('is_own_goal', $event->is_own_goal) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_own_goal">Own Goal</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="is_penalty" name="is_penalty" value="1"
                                    {{ old('is_penalty', $event->is_penalty) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_penalty">Penalty</label>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="3">{{ old('description', $event->description) }}</textarea>
                            <small class="text-muted">Additional details about the event</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- JavaScript untuk toggle field -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const eventTypeSelect = document.getElementById('event_type');
                            const isOwnGoalCheck = document.getElementById('is_own_goal');
                            const isPenaltyCheck = document.getElementById('is_penalty');
                            const relatedPlayerSelect = document.getElementById('related_player_id');

                            function toggleFields() {
                                const selectedEvent = eventTypeSelect.value;

                                // Toggle own goal checkbox (hanya untuk goal)
                                if (selectedEvent === 'goal') {
                                    isOwnGoalCheck.parentElement.style.display = 'inline-block';
                                    isPenaltyCheck.parentElement.style.display = 'inline-block';
                                } else {
                                    isOwnGoalCheck.parentElement.style.display = 'none';
                                    isPenaltyCheck.parentElement.style.display = 'none';
                                }

                                // Toggle related player (untuk goal dan substitution)
                                if (selectedEvent === 'goal' || selectedEvent === 'substitution') {
                                    relatedPlayerSelect.closest('.mb-3').style.display = 'block';
                                } else {
                                    relatedPlayerSelect.closest('.mb-3').style.display = 'none';
                                }
                            }

                            eventTypeSelect.addEventListener('change', toggleFields);
                            toggleFields(); // Initial call
                        });
                    </script>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.matches.events.index', $match) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i> Cancel
                        </a>
                        <div>
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-check-circle me-2"></i> Update Event
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .08);
            border-radius: 10px;
            overflow: hidden;
        }

        .form-check {
            margin-right: 20px;
        }
    </style>
@endsection