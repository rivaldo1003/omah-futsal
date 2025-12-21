@extends('layouts.admin')

@section('title', 'Edit Tournament')

    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">


@section('styles')
    <style>
        :root {
            --accent: #3B82F6;
            --primary: #1F2937;
            --secondary: #4B5563;
            --bg-main: #F9FAFB;
            --bg-card: #FFFFFF;
            --border-color: #E5E7EB;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --success-light: #DCFCE7;
            --success-dark: #15803D;
            --warning-light: #FEF3C7;
            --warning-dark: #D97706;
            --danger-light: #FEE2E2;
            --danger-dark: #B91C1C;
        }

        body {
            background-color: var(--bg-main) !important;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 0;
            font-size: 1.8rem;
        }

        /* Main Card */
        .main-card {
            background: var(--bg-card);
            border-radius: 0.75rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .main-card .card-header {
            background-color: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            color: var(--primary);
            font-weight: 600;
        }

        .main-card .card-body {
            padding: 1.5rem;
        }

        /* Form Styles */
        .form-label {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 10px 15px;
            color: var(--primary);
            transition: var(--transition);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-text {
            color: var(--secondary);
            font-size: 0.875rem;
        }

        /* Checkbox Styling */
        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--accent);
            border-color: var(--accent);
            padding: 10px 25px;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .btn-outline-secondary {
            border: 1px solid var(--border-color);
            color: var(--secondary);
            padding: 10px 25px;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-outline-secondary:hover {
            background-color: var(--bg-main);
            border-color: var(--secondary);
            color: var(--primary);
        }

        /* Team Selection */
        .team-selection-container {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 15px;
            background-color: var(--bg-main);
        }

        .team-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .team-item:last-child {
            border-bottom: none;
        }

        .team-item:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .team-checkbox {
            margin-right: 12px;
        }

        .team-info {
            flex-grow: 1;
        }

        .team-name {
            font-weight: 500;
            color: var(--primary);
            margin-bottom: 2px;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 0.5rem;
            padding: 15px 20px;
            margin-bottom: 25px;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .alert i {
            font-size: 1.3rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-success {
            background-color: var(--success-light);
            border-left: 4px solid var(--success-dark);
            color: var(--success-dark);
        }

        .alert-danger {
            background-color: var(--danger-light);
            border-left: 4px solid var(--danger-dark);
            color: var(--danger-dark);
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .main-card .card-body {
                padding: 1rem;
            }

            .btn-primary,
            .btn-outline-secondary {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
@endsection

@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                    style="color: var(--secondary);">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tournaments.index') }}"
                    style="color: var(--secondary);">Tournaments</a></li>
            <li class="breadcrumb-item active" style="color: var(--primary);">Edit Tournament</li>
        </ol>
    </nav>

    <div class="page-header">
        <h1>
            <i class="bi bi-pencil-square me-2"></i>
            <span>Edit Tournament: {{ $tournament->name }}</span>
        </h1>
        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i>
            <div>
                <strong>Success!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            <div>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="main-card">
        <div class="card-header">
            <h5><i class="bi bi-gear me-2"></i> Tournament Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.tournaments.update', $tournament) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-12 mb-4">
                        <h6 class="mb-3" style="color: var(--primary); font-weight: 600;">
                            <i class="bi bi-info-circle me-2"></i>Basic Information
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Tournament Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $tournament->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="slug" class="form-label">URL Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                                    name="slug" value="{{ old('slug', $tournament->slug) }}">
                                <div class="form-text">Leave empty to auto-generate from name</div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                    name="description"
                                    rows="3">{{ old('description', $tournament->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Dates and Location -->
                    <div class="col-md-12 mb-4">
                        <h6 class="mb-3" style="color: var(--primary); font-weight: 600;">
                            <i class="bi bi-calendar me-2"></i>Dates & Location
                        </h6>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Start Date *</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                    id="start_date" name="start_date"
                                    value="{{ old('start_date', $tournament->start_date->format('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">End Date *</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                    id="end_date" name="end_date"
                                    value="{{ old('end_date', $tournament->end_date->format('Y-m-d')) }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="location" name="location" value="{{ old('location', $tournament->location) }}">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="organizer" class="form-label">Organizer</label>
                                <input type="text" class="form-control @error('organizer') is-invalid @enderror"
                                    id="organizer" name="organizer" value="{{ old('organizer', $tournament->organizer) }}">
                                @error('organizer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tournament Type and Status -->
                    <div class="col-md-12 mb-4">
                        <h6 class="mb-3" style="color: var(--primary); font-weight: 600;">
                            <i class="bi bi-diagram-3 me-2"></i>Type & Status
                        </h6>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Tournament Type *</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type"
                                    required>
                                    <option value="group_knockout" {{ old('type', $tournament->type) == 'group_knockout' ? 'selected' : '' }}>Group + Knockout</option>
                                    <option value="league" {{ old('type', $tournament->type) == 'league' ? 'selected' : '' }}>
                                        League</option>
                                    <option value="knockout" {{ old('type', $tournament->type) == 'knockout' ? 'selected' : '' }}>Knockout</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                    required>
                                    <option value="upcoming" {{ old('status', $tournament->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="ongoing" {{ old('status', $tournament->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ old('status', $tournament->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $tournament->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Group Settings (visible only for group_knockout) -->
                            <div class="col-md-4 mb-3 group-settings"
                                style="{{ $tournament->type != 'group_knockout' ? 'display: none;' : '' }}">
                                <label for="groups_count" class="form-label">Number of Groups</label>
                                <input type="number" class="form-control @error('groups_count') is-invalid @enderror"
                                    id="groups_count" name="groups_count" min="1" max="8"
                                    value="{{ old('groups_count', $tournament->groups_count) }}">
                                @error('groups_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Team Selection -->
                    <div class="col-md-12 mb-4">
                        <h6 class="mb-3" style="color: var(--primary); font-weight: 600;">
                            <i class="bi bi-people me-2"></i>Select Teams
                        </h6>

                        <div class="team-selection-container">
                            @if($teams->count() > 0)
                                @foreach($teams as $team)
                                    <div class="team-item">
                                        <div class="team-checkbox">
                                            <input type="checkbox" class="form-check-input" id="team_{{ $team->id }}" name="teams[]"
                                                value="{{ $team->id }}" {{ in_array($team->id, $selectedTeams) ? 'checked' : '' }}>
                                        </div>
                                        <div class="team-info">
                                            <div class="team-name">{{ $team->name }}</div>
                                            @if($team->coach_name)
                                                <small class="text-muted">Coach: {{ $team->coach_name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center py-4">No teams available. Create teams first.</p>
                            @endif
                        </div>
                        @error('teams')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Match Settings -->
                    <div class="col-md-12 mb-4">
                        <h6 class="mb-3" style="color: var(--primary); font-weight: 600;">
                            <i class="bi bi-stopwatch me-2"></i>Match Settings
                        </h6>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="match_duration" class="form-label">Match Duration (minutes)</label>
                                <input type="number" class="form-control" id="match_duration" name="match_duration" min="10"
                                    max="120" value="{{ $settings['match_duration'] ?? 40 }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="half_time" class="form-label">Half Time (minutes)</label>
                                <input type="number" class="form-control" id="half_time" name="half_time" min="5" max="30"
                                    value="{{ $settings['half_time'] ?? 10 }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="points_win" class="form-label">Points for Win</label>
                                <input type="number" class="form-control" id="points_win" name="points_win" min="0" max="10"
                                    value="{{ $settings['points_win'] ?? 3 }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="points_draw" class="form-label">Points for Draw</label>
                                <input type="number" class="form-control" id="points_draw" name="points_draw" min="0"
                                    max="5" value="{{ $settings['points_draw'] ?? 1 }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <a href="{{ route('admin.tournaments.show', $tournament) }}" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-eye me-2"></i>View Details
                        </a>
                        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Tournament
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3" style="color: var(--primary); font-weight: 500;">Updating Tournament...</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-generate slug from name
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            nameInput.addEventListener('blur', function () {
                if (!slugInput.value) {
                    const slug = nameInput.value
                        .toLowerCase()
                        .replace(/[^\w\s]/gi, '')
                        .replace(/\s+/g, '-');
                    slugInput.value = slug;
                }
            });

            // Show/hide group settings based on tournament type
            const typeSelect = document.getElementById('type');
            const groupSettings = document.querySelector('.group-settings');

            function toggleGroupSettings() {
                if (typeSelect.value === 'group_knockout') {
                    groupSettings.style.display = 'block';
                } else {
                    groupSettings.style.display = 'none';
                }
            }

            typeSelect.addEventListener('change', toggleGroupSettings);

            // Show loading overlay on form submit
            const form = document.querySelector('form');
            const loadingOverlay = document.getElementById('loadingOverlay');

            form.addEventListener('submit', function () {
                loadingOverlay.style.display = 'flex';
            });

            // Validate at least 2 teams selected
            form.addEventListener('submit', function (e) {
                const checkedTeams = form.querySelectorAll('input[name="teams[]"]:checked').length;
                if (checkedTeams < 2) {
                    e.preventDefault();
                    alert('Please select at least 2 teams for the tournament.');
                    loadingOverlay.style.display = 'none';
                }
            });

            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
@endsection