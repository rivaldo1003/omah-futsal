@extends('layouts.admin')

    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">


@section('content')
    <div class="container-fluid py-3">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h4 mb-0">Create New Team</h1>
                <p class="text-muted small mb-0">Add a new team to the tournament</p>
            </div>
            <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-2">
                        <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i> Team Information</h6>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('admin.teams.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Team Logo -->
                            <div class="mb-3">
                                <label class="form-label small fw-medium">Team Logo</label>
                                <div class="border rounded p-2 bg-light">
                                    <div class="text-center mb-2">
                                        <div id="logoPreview" class="d-inline-block p-2 border rounded bg-white" style="width: 120px; height: 120px;">
                                            <div id="previewContainer" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                                <p class="text-muted x-small mt-1 mb-0">No logo</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <input type="file" class="form-control form-control-sm @error('logo') is-invalid @enderror" 
                                               id="logo" name="logo" accept="image/*">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clearLogoBtn">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    @error('logo')
                                        <div class="invalid-feedback d-block small">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1 x-small">
                                        JPG, PNG, GIF, SVG, WebP • Max 2MB • Recommended: 200×200
                                    </small>
                                </div>
                            </div>

                            <!-- Team Details -->
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="name" class="form-label small fw-medium">Team Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required
                                           placeholder="Enter team name">
                                    @error('name')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="short_name" class="form-label small fw-medium">Short Code</label>
                                    <input type="text" class="form-control form-control-sm @error('short_name') is-invalid @enderror" 
                                           id="short_name" name="short_name" value="{{ old('short_name') }}" 
                                           maxlength="4" placeholder="e.g., TGR">
                                    @error('short_name')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="founded_year" class="form-label small fw-medium">Founded Year</label>
                                    <input type="number" class="form-control form-control-sm @error('founded_year') is-invalid @enderror" 
                                           id="founded_year" name="founded_year" value="{{ old('founded_year') }}"
                                           min="1900" max="{{ date('Y') }}" placeholder="e.g., 1990">
                                    @error('founded_year')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="coach_name" class="form-label small fw-medium">Coach</label>
                                    <input type="text" class="form-control form-control-sm @error('coach_name') is-invalid @enderror"
                                           id="coach_name" name="coach_name" value="{{ old('coach_name') }}"
                                           placeholder="Coach name">
                                    @error('coach_name')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="city" class="form-label small fw-medium">City</label>
                                    <input type="text" class="form-control form-control-sm @error('city') is-invalid @enderror"
                                           id="city" name="city" value="{{ old('city') }}"
                                           placeholder="City">
                                    @error('city')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="country" class="form-label small fw-medium">Country</label>
                                    <input type="text" class="form-control form-control-sm @error('country') is-invalid @enderror"
                                           id="country" name="country" value="{{ old('country') }}"
                                           placeholder="Country">
                                    @error('country')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-label small fw-medium">Status <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="description" class="form-label small fw-medium">Description</label>
                                    <textarea class="form-control form-control-sm @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="1"
                                              placeholder="Team description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Players Selection -->
                                @if($players->count() > 0)
                                    <div class="col-12 mt-1">
                                        <label class="form-label small fw-medium d-flex justify-content-between align-items-center">
                                            <span>Assign Players <span class="text-muted">({{ $players->count() }})</span></span>
                                            <div>
                                                <button type="button" class="btn btn-xs btn-outline-primary px-2 py-0" id="selectAllPlayers">
                                                    All
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-secondary px-2 py-0 ms-1" id="deselectAllPlayers">
                                                    None
                                                </button>
                                            </div>
                                        </label>
                                        <div class="border rounded p-2 bg-light" style="max-height: 150px; overflow-y: auto;">
                                            <div class="row g-1">
                                                @foreach($players as $player)
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   name="player_ids[]" value="{{ $player->id }}" 
                                                                   id="player_{{ $player->id }}"
                                                                   {{ in_array($player->id, old('player_ids', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label small" for="player_{{ $player->id }}">
                                                                <span class="badge bg-primary bg-opacity-10 text-primary me-1">{{ $player->jersey_number ?? '#' }}</span>
                                                                {{ $player->name }}
                                                                @if($player->position)
                                                                    <small class="text-muted ms-1">{{ $player->position }}</small>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-warning alert-sm small py-1">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            No free players. 
                                            <a href="{{ route('admin.players.create') }}" class="alert-link">Create players first</a>
                                        </div>
                                    </div>
                                @endif

                                <!-- Tournament Selection -->
                                @if($tournaments->count() > 0)
                                    <div class="col-12 mt-1">
                                        <label class="form-label small fw-medium">Assign to Tournaments</label>
                                        <div class="border rounded p-2 bg-light">
                                            @foreach($tournaments as $tournament)
                                                <div class="form-check mb-1">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="tournament_ids[]" value="{{ $tournament->id }}" 
                                                           id="tournament_{{ $tournament->id }}"
                                                           {{ in_array($tournament->id, old('tournament_ids', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label small d-flex justify-content-between" 
                                                           for="tournament_{{ $tournament->id }}">
                                                        <span>{{ $tournament->name }}</span>
                                                        <small class="text-muted">{{ $tournament->format }}</small>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Submit Buttons -->
                                <div class="col-12 mt-3 pt-2 border-top">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-secondary btn-sm">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-sm px-3">
                                            <i class="bi bi-check-circle me-1"></i> Create Team
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Tips -->
                <div class="card border-0 shadow-sm mb-2">
                    <div class="card-header bg-white py-2">
                        <h6 class="mb-0"><i class="bi bi-lightbulb me-2"></i> Quick Tips</h6>
                    </div>
                    <div class="card-body p-2 small">
                        <div class="mb-2">
                            <div class="text-primary mb-0"><strong>Required Fields</strong></div>
                            <div class="text-muted">Fields marked with <span class="text-danger">*</span> are required</div>
                        </div>
                        <div class="mb-2">
                            <div class="text-primary mb-0"><strong>Player Assignment</strong></div>
                            <div class="text-muted">Players can belong to only one team</div>
                        </div>
                        <div class="mb-0">
                            <div class="text-primary mb-0"><strong>Logo Guidelines</strong></div>
                            <div class="text-muted">Square images work best for logos</div>
                        </div>
                    </div>
                </div>

                <!-- Logo Preview -->
                <div class="card border-0 shadow-sm mb-2">
                    <div class="card-header bg-white py-2">
                        <h6 class="mb-0"><i class="bi bi-image me-2"></i> Logo Preview</h6>
                    </div>
                    <div class="card-body p-2 text-center">
                        <div id="logoPreviewCard" class="p-2 border rounded bg-light" style="min-height: 100px;">
                            <div id="cardPreviewContainer" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-image text-muted" style="font-size: 1.5rem;"></i>
                                <p class="text-muted x-small mt-1 mb-0">Logo preview</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Players Summary -->
                @if($players->count() > 0)
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-2">
                            <h6 class="mb-0"><i class="bi bi-people me-2"></i> Players ({{ $players->count() }})</h6>
                        </div>
                        <div class="card-body p-0">
                            <div style="max-height: 150px; overflow-y: auto;">
                                @foreach($players as $player)
                                    <div class="p-2 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-medium small">{{ $player->name }}</div>
                                                <small class="text-muted">{{ $player->position }}</small>
                                            </div>
                                            <span class="badge bg-light text-dark small">#{{ $player->jersey_number }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border-radius: 0.375rem;
        }
        
        .card-header {
            border-bottom: 1px solid rgba(0,0,0,.1);
        }
        
        .form-control-sm, .form-select-sm {
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
        }
        
        .form-label.small {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        
        .x-small {
            font-size: 0.75rem;
        }
        
        .alert-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        #logoPreview, #logoPreviewCard {
            overflow: hidden;
        }
        
        #logoPreview img,
        #logoPreviewCard img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 0.25rem;
        }
        
        .form-check-label {
            cursor: pointer;
            width: 100%;
        }
        
        .form-check-label:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
        
        .btn-xs {
            padding: 0.125rem 0.375rem;
            font-size: 0.75rem;
            line-height: 1.2;
            border-radius: 0.25rem;
        }
    </style>
@endpush

@push('scripts')
<script>
// Define functions globally BEFORE DOMContentLoaded
window.previewImage = function(input) {
    const file = input.files[0];
    
    if (file && file.type.startsWith('image/')) {
        // Validate file size (2MB max)
        const MAX_SIZE = 2 * 1024 * 1024;
        if (file.size > MAX_SIZE) {
            alert('File size must be less than 2MB');
            clearImage();
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // Update main preview
            const previewContainer = document.getElementById('previewContainer');
            previewContainer.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded">`;
            
            // Update sidebar preview
            const cardPreviewContainer = document.getElementById('cardPreviewContainer');
            cardPreviewContainer.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded">`;
        };
        
        reader.readAsDataURL(file);
    }
}

window.clearImage = function() {
    const logoInput = document.getElementById('logo');
    if (logoInput) {
        logoInput.value = '';
    }
    
    // Reset main preview
    const previewContainer = document.getElementById('previewContainer');
    previewContainer.innerHTML = `
        <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
        <p class="text-muted x-small mt-1 mb-0">No logo</p>
    `;
    
    // Reset sidebar preview
    const cardPreviewContainer = document.getElementById('cardPreviewContainer');
    cardPreviewContainer.innerHTML = `
        <i class="bi bi-image text-muted" style="font-size: 1.5rem;"></i>
        <p class="text-muted x-small mt-1 mb-0">Logo preview</p>
    `;
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Logo upload event listener
    const logoInput = document.getElementById('logo');
    const clearLogoBtn = document.getElementById('clearLogoBtn');
    
    if (logoInput) {
        // Add inline onchange for immediate response
        logoInput.setAttribute('onchange', 'previewImage(this)');
        
        // Also add event listener as backup
        logoInput.addEventListener('change', function(event) {
            previewImage(this);
        });
    }
    
    if (clearLogoBtn) {
        clearLogoBtn.addEventListener('click', function() {
            clearImage();
        });
    }

    // Player selection
    const selectAllBtn = document.getElementById('selectAllPlayers');
    const deselectAllBtn = document.getElementById('deselectAllPlayers');
    const playerCheckboxes = document.querySelectorAll('input[name="player_ids[]"]');

    if (selectAllBtn && playerCheckboxes.length > 0) {
        selectAllBtn.addEventListener('click', () => {
            playerCheckboxes.forEach(cb => cb.checked = true);
        });
    }

    if (deselectAllBtn && playerCheckboxes.length > 0) {
        deselectAllBtn.addEventListener('click', () => {
            playerCheckboxes.forEach(cb => cb.checked = false);
        });
    }

    // Short name uppercase
    const shortNameInput = document.getElementById('short_name');
    if (shortNameInput) {
        shortNameInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    // Year validation
    const yearInput = document.getElementById('founded_year');
    if (yearInput) {
        yearInput.addEventListener('blur', function() {
            const year = parseInt(this.value);
            if (this.value && (year < 1900 || year > new Date().getFullYear())) {
                this.value = '';
                alert('Please enter a valid year between 1900 and ' + new Date().getFullYear());
            }
        });
    }
});
</script>
@endpush