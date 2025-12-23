@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-3">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h4 mb-0">Edit Team: {{ $team->name }}</h1>
                    <p class="text-muted small mb-0">Update team information</p>
                </div>
                <a href="{{ route('admin.teams.show', $team) }}" class="btn btn-outline-secondary btn-sm">
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
                            <form action="{{ route('admin.teams.update', $team) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Team Logo -->
                                <div class="mb-3">
                                    <label class="form-label small fw-medium">Team Logo</label>
                                    <div class="border rounded p-2 bg-light">
                                        <div class="text-center mb-2">
                                            <div id="logoPreview" class="d-inline-block p-2 border rounded bg-white" style="width: 120px; height: 120px;">
                                                <div id="previewContainer" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                                    @if($team->logo)
                                                        <img src="{{ filter_var($team->logo, FILTER_VALIDATE_URL) ? $team->logo : asset('storage/' . $team->logo) }}" 
                                                             alt="{{ $team->name }}" class="img-fluid rounded">
                                                    @else
                                                        <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                                        <p class="text-muted x-small mt-1 mb-0">No logo</p>
                                                    @endif
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
                                               id="name" name="name" value="{{ old('name', $team->name) }}" required
                                               placeholder="Enter team name">
                                        @error('name')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="short_name" class="form-label small fw-medium">Short Code</label>
                                        <input type="text" class="form-control form-control-sm @error('short_name') is-invalid @enderror" 
                                               id="short_name" name="short_name" value="{{ old('short_name', $team->short_name) }}" 
                                               maxlength="10" placeholder="e.g., TGR">
                                        @error('short_name')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Coach Information Section -->
                                    <div class="col-12 mt-2">
                                        <h6 class="border-bottom pb-1 mb-2 small fw-medium">Coach Information</h6>
                                    </div>

                                    <!-- <div class="col-md-6">
                                        <label for="coach_name" class="form-label small fw-medium">Coach</label>
                                        <input type="text" class="form-control form-control-sm @error('coach_name') is-invalid @enderror"
                                               id="coach_name" name="coach_name" value="{{ old('coach_name', $team->coach_name) }}"
                                               placeholder="Coach name">
                                        @error('coach_name')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div> -->

                                    <div class="col-md-6">
                                        <label for="head_coach" class="form-label small fw-medium">Head Coach</label>
                                        <input type="text" class="form-control form-control-sm @error('head_coach') is-invalid @enderror"
                                               id="head_coach" name="head_coach" value="{{ old('head_coach', $team->head_coach) }}"
                                               placeholder="Head coach name">
                                        @error('head_coach')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="assistant_coach" class="form-label small fw-medium">Assistant Coach</label>
                                        <input type="text" class="form-control form-control-sm @error('assistant_coach') is-invalid @enderror"
                                               id="assistant_coach" name="assistant_coach" value="{{ old('assistant_coach', $team->assistant_coach) }}"
                                               placeholder="Assistant coach name">
                                        @error('assistant_coach')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="goalkeeper_coach" class="form-label small fw-medium">Goalkeeper Coach</label>
                                        <input type="text" class="form-control form-control-sm @error('goalkeeper_coach') is-invalid @enderror"
                                               id="goalkeeper_coach" name="goalkeeper_coach" value="{{ old('goalkeeper_coach', $team->goalkeeper_coach) }}"
                                               placeholder="Goalkeeper coach name">
                                        @error('goalkeeper_coach')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="kitman" class="form-label small fw-medium">Kitman</label>
                                        <input type="text" class="form-control form-control-sm @error('kitman') is-invalid @enderror"
                                               id="kitman" name="kitman" value="{{ old('kitman', $team->kitman) }}"
                                               placeholder="Kitman name">
                                        @error('kitman')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="coach_phone" class="form-label small fw-medium">Coach Phone</label>
                                        <input type="text" class="form-control form-control-sm @error('coach_phone') is-invalid @enderror"
                                               id="coach_phone" name="coach_phone" value="{{ old('coach_phone', $team->coach_phone) }}"
                                               placeholder="Coach phone number">
                                        @error('coach_phone')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="coach_email" class="form-label small fw-medium">Coach Email</label>
                                        <input type="email" class="form-control form-control-sm @error('coach_email') is-invalid @enderror"
                                               id="coach_email" name="coach_email" value="{{ old('coach_email', $team->coach_email) }}"
                                               placeholder="Coach email address">
                                        @error('coach_email')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Team Colors -->
                                    <div class="col-12 mt-2">
                                        <h6 class="border-bottom pb-1 mb-2 small fw-medium">Team Colors</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="primary_color" class="form-label small fw-medium">Primary Color</label>
                                        <div class="input-group input-group-sm">
                                            <input type="color" class="form-control form-control-color p-1" 
                                                   id="primary_color_picker" value="{{ old('primary_color', $team->primary_color ?: '#007bff') }}">
                                            <input type="text" class="form-control form-control-sm @error('primary_color') is-invalid @enderror"
                                                   id="primary_color" name="primary_color" value="{{ old('primary_color', $team->primary_color) }}"
                                                   maxlength="7" placeholder="#RRGGBB">
                                        </div>
                                        @error('primary_color')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="secondary_color" class="form-label small fw-medium">Secondary Color</label>
                                        <div class="input-group input-group-sm">
                                            <input type="color" class="form-control form-control-color p-1" 
                                                   id="secondary_color_picker" value="{{ old('secondary_color', $team->secondary_color ?: '#6c757d') }}">
                                            <input type="text" class="form-control form-control-sm @error('secondary_color') is-invalid @enderror"
                                                   id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $team->secondary_color) }}"
                                                   maxlength="7" placeholder="#RRGGBB">
                                        </div>
                                        @error('secondary_color')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="col-12 mt-2">
                                        <h6 class="border-bottom pb-1 mb-2 small fw-medium">Contact Information</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label small fw-medium">Team Phone</label>
                                        <input type="text" class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                               id="phone" name="phone" value="{{ old('phone', $team->phone) }}"
                                               placeholder="Team phone number">
                                        @error('phone')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label small fw-medium">Team Email</label>
                                        <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email', $team->email) }}"
                                               placeholder="Team email address">
                                        @error('email')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- <div class="col-md-6">
                                        <label for="contact_phone" class="form-label small fw-medium">Contact Phone</label>
                                        <input type="text" class="form-control form-control-sm @error('contact_phone') is-invalid @enderror"
                                               id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $team->contact_phone) }}"
                                               placeholder="Contact phone number">
                                        @error('contact_phone')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="contact_email" class="form-label small fw-medium">Contact Email</label>
                                        <input type="email" class="form-control form-control-sm @error('contact_email') is-invalid @enderror"
                                               id="contact_email" name="contact_email" value="{{ old('contact_email', $team->contact_email) }}"
                                               placeholder="Contact email address">
                                        @error('contact_email')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div> -->

                                    <!-- Venue and Address -->
                                    <div class="col-md-6">
                                        <label for="home_venue" class="form-label small fw-medium">Home Venue</label>
                                        <input type="text" class="form-control form-control-sm @error('home_venue') is-invalid @enderror"
                                               id="home_venue" name="home_venue" value="{{ old('home_venue', $team->home_venue) }}"
                                               placeholder="Home stadium/venue">
                                        @error('home_venue')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="founded_year" class="form-label small fw-medium">Founded Year</label>
                                        <input type="number" class="form-control form-control-sm @error('founded_year') is-invalid @enderror" 
                                               id="founded_year" name="founded_year" value="{{ old('founded_year', $team->founded_year) }}"
                                               min="1900" max="{{ date('Y') }}" placeholder="e.g., 1990">
                                        @error('founded_year')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- <div class="col-md-6">
                                        <label for="city" class="form-label small fw-medium">City</label>
                                        <input type="text" class="form-control form-control-sm @error('city') is-invalid @enderror"
                                               id="city" name="city" value="{{ old('city', $team->city) }}"
                                               placeholder="City">
                                        @error('city')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="country" class="form-label small fw-medium">Country</label>
                                        <input type="text" class="form-control form-control-sm @error('country') is-invalid @enderror"
                                               id="country" name="country" value="{{ old('country', $team->country) }}"
                                               placeholder="Country">
                                        @error('country')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div> -->

                                    <div class="col-md-6">
                                        <label for="website" class="form-label small fw-medium">Website</label>
                                        <input type="url" class="form-control form-control-sm @error('website') is-invalid @enderror"
                                               id="website" name="website" value="{{ old('website', $team->website) }}"
                                               placeholder="https://example.com">
                                        @error('website')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="status" class="form-label small fw-medium">Status <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="active" {{ old('status', $team->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="pending" {{ old('status', $team->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="inactive" {{ old('status', $team->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="address" class="form-label small fw-medium">Address</label>
                                        <textarea class="form-control form-control-sm @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="2"
                                                  placeholder="Full team address">{{ old('address', $team->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="description" class="form-label small fw-medium">Description</label>
                                        <textarea class="form-control form-control-sm @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="3"
                                                  placeholder="Team description">{{ old('description', $team->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Players Selection -->
                                    @if($players->count() > 0)
                                        <div class="col-12 mt-1">
                                            <label class="form-label small fw-medium d-flex justify-content-between align-items-center">
                                                <span>Assign Players 
                                                    <span class="text-muted">
                                                        ({{ $players->where('team_id', null)->count() }} free, 
                                                        {{ $players->where('team_id', $team->id)->count() }} in team)
                                                    </span>
                                                </span>
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
                                                                       {{ in_array($player->id, old('player_ids', $currentPlayers)) ? 'checked' : '' }}
                                                                       {{ $player->team_id && $player->team_id != $team->id ? 'disabled' : '' }}>
                                                                <label class="form-check-label small" for="player_{{ $player->id }}">
                                                                    <span class="badge bg-primary bg-opacity-10 text-primary me-1">{{ $player->jersey_number ?? '#' }}</span>
                                                                    {{ $player->name }}
                                                                    @if($player->position)
                                                                        <small class="text-muted ms-1">{{ $player->position }}</small>
                                                                    @endif
                                                                    @if($player->team_id && $player->team_id != $team->id)
                                                                        <small class="text-danger ms-1">({{ $player->team->name }})</small>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-1 x-small">
                                                Players in other teams cannot be selected.
                                            </small>
                                        </div>
                                    @else
                                        <div class="col-12">
                                            <div class="alert alert-warning alert-sm small py-1">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                No players available. 
                                                <a href="{{ route('admin.players.create') }}" class="alert-link">Create players</a>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Tournament Selection -->
                                    @if($tournaments->count() > 0)
                                        <div class="col-12 mt-1">
                                            <label class="form-label small fw-medium d-flex justify-content-between align-items-center">
                                                <span>Assign to Tournaments</span>
                                                <div>
                                                    <button type="button" class="btn btn-xs btn-outline-primary px-2 py-0" id="selectAllTournaments">
                                                        All
                                                    </button>
                                                    <button type="button" class="btn btn-xs btn-outline-secondary px-2 py-0 ms-1" id="deselectAllTournaments">
                                                        None
                                                    </button>
                                                </div>
                                            </label>
                                            <div class="border rounded p-2 bg-light">
                                                @foreach($tournaments as $tournament)
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="tournament_ids[]" value="{{ $tournament->id }}" 
                                                               id="tournament_{{ $tournament->id }}"
                                                               {{ in_array($tournament->id, old('tournament_ids', $currentTournaments)) ? 'checked' : '' }}>
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
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <a href="{{ route('admin.teams.show', $team) }}" class="btn btn-outline-secondary btn-sm">
                                                    Cancel
                                                </a>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                    <i class="bi bi-trash me-1"></i> Delete
                                                </button>
                                                <button type="submit" class="btn btn-primary btn-sm px-3">
                                                    <i class="bi bi-check-circle me-1"></i> Update
                                                </button>
                                            </div>
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
                                <div class="text-primary mb-0"><strong>Coach Information</strong></div>
                                <div class="text-muted">Complete all coach fields for better team management</div>
                            </div>
                            <div class="mb-2">
                                <div class="text-primary mb-0"><strong>Team Colors</strong></div>
                                <div class="text-muted">Use hex color codes (#RRGGBB) for primary and secondary colors</div>
                            </div>
                            <div class="mb-0">
                                <div class="text-primary mb-0"><strong>Status</strong></div>
                                <div class="text-muted">
                                    <span class="badge bg-success">Active</span> - Can play
                                    <span class="badge bg-warning ms-1">Pending</span> - Awaiting
                                    <span class="badge bg-danger ms-1">Inactive</span> - Cannot play
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Info -->
                    <div class="card border-0 shadow-sm mb-2">
                        <div class="card-header bg-white py-2">
                            <h6 class="mb-0"><i class="bi bi-info-square me-2"></i> Current Info</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center mb-2">
                                @if($team->logo)
                                    <div class="me-2">
                                        <img src="{{ filter_var($team->logo, FILTER_VALIDATE_URL) ? $team->logo : asset('storage/' . $team->logo) }}" 
                                             alt="{{ $team->name }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-medium small">{{ $team->name }}</div>
                                    @if($team->short_name)
                                        <small class="text-muted">({{ $team->short_name }})</small>
                                    @endif
                                </div>
                            </div>

                            <div class="row g-1 small">
                                <div class="col-6">
                                    <div class="text-muted">Status</div>
                                    <div class="badge bg-{{ $team->status == 'active' ? 'success' : ($team->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($team->status) }}
                                    </div>
                                </div>
                                @if($team->city)
                                    <div class="col-6">
                                        <div class="text-muted">City</div>
                                        <div>{{ $team->city }}</div>
                                    </div>
                                @endif
                                @if($team->coach_name)
                                    <div class="col-6">
                                        <div class="text-muted">Coach</div>
                                        <div>{{ $team->coach_name }}</div>
                                    </div>
                                @endif
                                <div class="col-6">
                                    <div class="text-muted">Players</div>
                                    <div>{{ $team->players->count() }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Tournaments</div>
                                    <div>{{ $team->tournaments->count() }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Created</div>
                                    <div>{{ $team->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Preview -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-2">
                            <h6 class="mb-0"><i class="bi bi-image me-2"></i> Logo Preview</h6>
                        </div>
                        <div class="card-body p-2 text-center">
                            <div id="logoPreviewCard" class="p-2 border rounded bg-light" style="min-height: 100px;">
                                <div id="cardPreviewContainer" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    @if($team->logo)
                                        <img src="{{ filter_var($team->logo, FILTER_VALIDATE_URL) ? $team->logo : asset('storage/' . $team->logo) }}" 
                                             alt="{{ $team->name }}" class="img-fluid rounded">
                                    @else
                                        <i class="bi bi-image text-muted" style="font-size: 1.5rem;"></i>
                                        <p class="text-muted x-small mt-1 mb-0">No logo</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white py-2">
                        <h6 class="modal-title mb-0"><i class="bi bi-exclamation-triangle me-1"></i> Delete Team</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3 small">
                        <p class="mb-2">Delete <strong>{{ $team->name }}</strong>?</p>
                        <div class="alert alert-warning small py-1 mb-2">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            This cannot be undone.
                        </div>
                        <div class="text-muted x-small">
                            {{ $team->players->count() }} players • {{ $team->tournaments->count() }} tournaments
                        </div>
                    </div>
                    <div class="modal-footer p-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
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

        .form-check-input:disabled + .form-check-label {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-xs {
            padding: 0.125rem 0.375rem;
            font-size: 0.75rem;
            line-height: 1.2;
            border-radius: 0.25rem;
        }

        .modal-sm {
            max-width: 300px;
        }

        .form-control-color {
            width: 40px;
            height: calc(1.8125rem + 2px);
            padding: 0.25rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
    // Define functions globally
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

        // Reset to original logo
        const previewContainer = document.getElementById('previewContainer');
        @if($team->logo)
            previewContainer.innerHTML = `<img src="{{ filter_var($team->logo, FILTER_VALIDATE_URL) ? $team->logo : asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" class="img-fluid rounded">`;
        @else
            previewContainer.innerHTML = `
                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                <p class="text-muted x-small mt-1 mb-0">No logo</p>
            `;
        @endif

        // Reset sidebar preview
        const cardPreviewContainer = document.getElementById('cardPreviewContainer');
        @if($team->logo)
            cardPreviewContainer.innerHTML = `<img src="{{ filter_var($team->logo, FILTER_VALIDATE_URL) ? $team->logo : asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" class="img-fluid rounded">`;
        @else
            cardPreviewContainer.innerHTML = `
                <i class="bi bi-image text-muted" style="font-size: 1.5rem;"></i>
                <p class="text-muted x-small mt-1 mb-0">No logo</p>
            `;
        @endif
    }

    // Color picker functions
    function updateColorPreview() {
        const primaryColor = document.getElementById('primary_color').value;
        const secondaryColor = document.getElementById('secondary_color').value;
        
        // Update color pickers
        document.getElementById('primary_color_picker').value = primaryColor;
        document.getElementById('secondary_color_picker').value = secondaryColor;
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

        // Color picker event listeners
        const primaryColorInput = document.getElementById('primary_color');
        const secondaryColorInput = document.getElementById('secondary_color');
        const primaryColorPicker = document.getElementById('primary_color_picker');
        const secondaryColorPicker = document.getElementById('secondary_color_picker');
        
        if (primaryColorInput && primaryColorPicker) {
            primaryColorInput.addEventListener('input', function() {
                if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                    primaryColorPicker.value = this.value;
                }
            });
            
            primaryColorPicker.addEventListener('input', function() {
                primaryColorInput.value = this.value;
            });
        }
        
        if (secondaryColorInput && secondaryColorPicker) {
            secondaryColorInput.addEventListener('input', function() {
                if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                    secondaryColorPicker.value = this.value;
                }
            });
            
            secondaryColorPicker.addEventListener('input', function() {
                secondaryColorInput.value = this.value;
            });
        }
        
        // Initialize color pickers
        updateColorPreview();

        // Player selection
        const selectAllPlayersBtn = document.getElementById('selectAllPlayers');
        const deselectAllPlayersBtn = document.getElementById('deselectAllPlayers');
        const playerCheckboxes = document.querySelectorAll('input[name="player_ids[]"]:not(:disabled)');

        if (selectAllPlayersBtn && playerCheckboxes.length > 0) {
            selectAllPlayersBtn.addEventListener('click', () => {
                playerCheckboxes.forEach(cb => cb.checked = true);
            });
        }

        if (deselectAllPlayersBtn && playerCheckboxes.length > 0) {
            deselectAllPlayersBtn.addEventListener('click', () => {
                document.querySelectorAll('input[name="player_ids[]"]').forEach(cb => cb.checked = false);
            });
        }

        // Tournament selection
        const selectAllTournamentsBtn = document.getElementById('selectAllTournaments');
        const deselectAllTournamentsBtn = document.getElementById('deselectAllTournaments');
        const tournamentCheckboxes = document.querySelectorAll('input[name="tournament_ids[]"]');

        if (selectAllTournamentsBtn && tournamentCheckboxes.length > 0) {
            selectAllTournamentsBtn.addEventListener('click', () => {
                tournamentCheckboxes.forEach(cb => cb.checked = true);
            });
        }

        if (deselectAllTournamentsBtn && tournamentCheckboxes.length > 0) {
            deselectAllTournamentsBtn.addEventListener('click', () => {
                tournamentCheckboxes.forEach(cb => cb.checked = false);
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
                    this.value = '{{ old('founded_year', $team->founded_year) }}';
                    alert('Please enter a valid year between 1900 and ' + new Date().getFullYear());
                }
            });
        }

        // Phone number formatting (optional)
        const phoneInputs = document.querySelectorAll('input[name="phone"], input[name="coach_phone"], input[name="contact_phone"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9+\-\s()]/g, '');
            });
        });
    });
    </script>
@endpush