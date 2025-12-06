@extends('layouts.admin')

@section('title', 'Edit Player')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size: 0.875rem; padding: 0; background: none;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.players.index') }}">Players</a></li>
            <li class="breadcrumb-item active">Edit {{ $player->name }}</li>
        </ol>
    </nav>

    <div class="page-header">
        <h1>
            <i class="bi bi-pencil-square me-2"></i>
            Edit Player
        </h1>
        <div>
            <a href="{{ route('admin.players.show', $player) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="main-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i> Edit Player Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.players.update', $player) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Left Column - Photo & Stats -->
                    <div class="col-lg-4">
                        <!-- Photo Upload -->
                        <div class="card border mb-4">
                            <div class="card-body">
                                <h6 class="card-title mb-3"><i class="bi bi-camera me-2"></i>Player Photo</h6>

                                <div class="text-center mb-4">
                                    <div class="player-photo-edit mb-3 mx-auto">
                                        @php
                                            $playerPhoto = $player->photo ?? null;
                                            $nameParts = explode(' ', $player->name);
                                            $initials = count($nameParts) >= 2
                                                ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                                                : strtoupper(substr($player->name, 0, 2));

                                            $photoPath = null;
                                            if ($playerPhoto) {
                                                if (filter_var($playerPhoto, FILTER_VALIDATE_URL)) {
                                                    $photoPath = $playerPhoto;
                                                } else {
                                                    try {
                                                        if (Storage::disk('public')->exists($playerPhoto)) {
                                                            $photoPath = asset('storage/' . $playerPhoto);
                                                        }
                                                    } catch (Exception $e) {
                                                        $photoPath = null;
                                                    }
                                                }
                                            }
                                        @endphp

                                        <div class="player-photo-preview" id="photoPreview">
                                            @if($photoPath)
                                                <img src="{{ $photoPath }}" alt="{{ $player->name }}" id="previewImage"
                                                    onerror="handleImageError(this)">
                                            @endif
                                            <div class="player-photo-fallback-edit" id="photoFallback"
                                                style="{{ $photoPath ? 'display: none;' : '' }}">
                                                {{ $initials }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="photo" class="form-label small">Change Photo</label>
                                        <input type="file" class="form-control form-control-sm" id="photo" name="photo"
                                            accept="image/*" onchange="previewImage(event)">
                                        <div class="form-text small">
                                            Max size: 2MB. Formats: JPG, PNG, GIF, WebP
                                        </div>
                                    </div>

                                    @if($photoPath)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="removePhoto"
                                                name="remove_photo">
                                            <label class="form-check-label small" for="removePhoto">
                                                Remove current photo
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Stats -->
                        <!-- <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title mb-3"><i class="bi bi-bar-chart me-2"></i>Player Statistics</h6>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="goals" class="form-label small">Goals</label>
                                            <input type="number" class="form-control form-control-sm" id="goals" name="goals"
                                                value="{{ old('goals', $player->goals ?? 0) }}" min="0">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="assists" class="form-label small">Assists</label>
                                            <input type="number" class="form-control form-control-sm" id="assists"
                                                name="assists" value="{{ old('assists', $player->assists ?? 0) }}" min="0">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="yellow_cards" class="form-label small">Yellow Cards</label>
                                            <input type="number" class="form-control form-control-sm" id="yellow_cards"
                                                name="yellow_cards"
                                                value="{{ old('yellow_cards', $player->yellow_cards ?? 0) }}" min="0">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="red_cards" class="form-label small">Red Cards</label>
                                            <input type="number" class="form-control form-control-sm" id="red_cards"
                                                name="red_cards" value="{{ old('red_cards', $player->red_cards ?? 0) }}"
                                                min="0">
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                    </div>

                    <!-- Right Column - Player Details -->
                    <div class="col-lg-8">
                        <div class="card border">
                            <div class="card-body">
                                <h6 class="card-title mb-3"><i class="bi bi-info-circle me-2"></i>Basic Information</h6>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label small">Full Name *</label>
                                        <input type="text" class="form-control form-control-sm" id="name" name="name"
                                            value="{{ old('name', $player->name) }}" required placeholder="Enter full name">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="jersey_number" class="form-label small">Jersey Number</label>
                                        <input type="number" class="form-control form-control-sm" id="jersey_number"
                                            name="jersey_number" value="{{ old('jersey_number', $player->jersey_number) }}"
                                            min="0" max="99" placeholder="e.g., 7">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="team_id" class="form-label small">Team</label>
                                        <select class="form-select form-select-sm" id="team_id" name="team_id">
                                            <option value="">No Team</option>
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id }}" {{ old('team_id', $player->team_id) == $team->id ? 'selected' : '' }}>
                                                    {{ $team->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="position" class="form-label small">Position</label>
                                        <select class="form-select form-select-sm" id="position" name="position">
                                            <option value="">Select position</option>
                                            <option value="Flank" {{ old('position', $player->position) == 'Flank' ? 'selected' : '' }}>Flank</option>
                                            <option value="Anchor" {{ old('position', $player->position) == 'Anchor' ? 'selected' : '' }}>Anchor</option>
                                            <option value="Pivot" {{ old('position', $player->position) == 'Pivot' ? 'selected' : '' }}>Pivot</option>
                                            <option value="Kiper" {{ old('position', $player->position) == 'Kiper' ? 'selected' : '' }}>Kiper</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="card border mt-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('admin.players.show', $player) }}"
                                            class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-x-circle me-1"></i> Cancel
                                        </a>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-check-circle me-1"></i> Update Player
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary: #6b7280;
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --border-color: #e5e7eb;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .page-header h1 {
            color: var(--primary);
            font-weight: 600;
            margin: 0;
            font-size: 1.5rem;
        }

        .main-card {
            background: var(--bg-card);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-header {
            background: #f8fafc;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
            color: var(--primary);
        }

        .card {
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .card-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--primary);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .form-control-sm,
        .form-select-sm {
            font-size: 0.875rem;
        }

        .player-photo-edit {
            position: relative;
            width: 150px;
            height: 150px;
        }

        .player-photo-preview {
            width: 100%;
            height: 100%;
            border-radius: 12px;
            overflow: hidden;
            border: 3px solid var(--border-color);
            background: #ffffff;
        }

        .player-photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .player-photo-fallback-edit {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            font-size: 2.5rem;
            font-weight: 600;
        }

        .alert {
            border-radius: 6px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            border: none;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 6px;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        .btn-outline-secondary {
            color: var(--secondary);
            border-color: var(--border-color);
        }

        .btn-outline-secondary:hover {
            background: #f8fafc;
            border-color: var(--primary-light);
            color: var(--primary);
        }

        .form-text {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .player-photo-edit {
                width: 120px;
                height: 120px;
            }

            .player-photo-fallback-edit {
                font-size: 2rem;
            }

            .col-md-6,
            .col-12 {
                padding: 0.25rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('previewImage');
            const fallback = document.getElementById('photoFallback');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    if (!preview) {
                        // Create image element if it doesn't exist
                        const img = document.createElement('img');
                        img.id = 'previewImage';
                        img.alt = 'Preview';
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'cover';

                        const previewContainer = document.getElementById('photoPreview');
                        previewContainer.insertBefore(img, fallback);
                        img.src = e.target.result;
                    } else {
                        preview.src = e.target.result;
                    }

                    if (fallback) {
                        fallback.style.display = 'none';
                    }
                    if (preview) {
                        preview.style.display = 'block';
                    }
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function handleImageError(img) {
            console.log('Image failed to load:', img.src);
            img.style.display = 'none';
            const fallback = document.getElementById('photoFallback');
            if (fallback) {
                fallback.style.display = 'flex';
            }
        }

        // Auto-dismiss alert
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Initialize the form
        document.addEventListener('DOMContentLoaded', function () {
            // Handle remove photo checkbox
            const removePhotoCheckbox = document.getElementById('removePhoto');
            const photoInput = document.getElementById('photo');

            if (removePhotoCheckbox) {
                removePhotoCheckbox.addEventListener('change', function () {
                    if (this.checked) {
                        photoInput.disabled = true;
                        photoInput.value = '';

                        // Hide preview and show fallback
                        const preview = document.getElementById('previewImage');
                        const fallback = document.getElementById('photoFallback');

                        if (preview) preview.style.display = 'none';
                        if (fallback) fallback.style.display = 'flex';
                    } else {
                        photoInput.disabled = false;
                    }
                });
            }
        });
    </script>
@endsection