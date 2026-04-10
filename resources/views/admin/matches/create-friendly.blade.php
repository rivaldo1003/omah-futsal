@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.matches.index') }}">Matches</a></li>
            <li class="breadcrumb-item active">Create Friendly Match</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="bi bi-plus-circle"></i> Create Friendly Match</h1>
            <p class="lead mb-0">Buat match ujicoba (tidak terikat tournament)</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.matches.create') }}" class="btn btn-outline-primary">
                <i class="bi bi-trophy"></i> Create Tournament Match
            </a>
            <a href="{{ route('admin.matches.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Matches
            </a>
        </div>
    </div>

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

            <form action="{{ route('admin.matches.friendly.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <h5 class="mb-3"><i class="bi bi-info-circle"></i> Basic Information</h5>

                        <div class="mb-3">
                            <label for="match_date" class="form-label">Match Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('match_date') is-invalid @enderror"
                                   id="match_date" name="match_date"
                                   value="{{ old('match_date') }}" required>
                            @error('match_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <input type="text" class="form-control @error('venue') is-invalid @enderror"
                                   id="venue" name="venue" value="{{ old('venue', 'Main Field') }}"
                                   placeholder="e.g., Main Field, Court 1">
                            @error('venue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                @foreach($statusOptions as $st)
                                    <option value="{{ $st }}" {{ old('status', 'upcoming') === $st ? 'selected' : '' }}>
                                        {{ ucfirst($st) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Teams Selection -->
                    <div class="col-md-6">
                        <h5 class="mb-3"><i class="bi bi-people"></i> Teams Selection</h5>

                        <div class="mb-3">
                            <label for="team_home_id" class="form-label">Home Team <span class="text-danger">*</span></label>
                            <select class="form-select @error('team_home_id') is-invalid @enderror" id="team_home_id" name="team_home_id" required>
                                <option value="">-- Select Home Team --</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('team_home_id') == $team->id ? 'selected' : '' }}>
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('team_home_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="team_away_id" class="form-label">Away Team <span class="text-danger">*</span></label>
                            <select class="form-select @error('team_away_id') is-invalid @enderror" id="team_away_id" name="team_away_id" required>
                                <option value="">-- Select Away Team --</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('team_away_id') == $team->id ? 'selected' : '' }}>
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('team_away_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3"><i class="bi bi-clipboard-data"></i> Score (optional)</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="home_score" class="form-label">Home Score</label>
                                <input type="number" min="0" class="form-control @error('home_score') is-invalid @enderror"
                                       id="home_score" name="home_score" value="{{ old('home_score') }}">
                                @error('home_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="away_score" class="form-label">Away Score</label>
                                <input type="number" min="0" class="form-control @error('away_score') is-invalid @enderror"
                                       id="away_score" name="away_score" value="{{ old('away_score') }}">
                                @error('away_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                                      placeholder="Catatan ujicoba, aturan khusus, dsb.">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Create Friendly Match
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

