<!-- Step 1: Basic Info -->
                    <div class="step-content" id="step1" style="display: {{ $currentStep == 1 ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    <i class="bi bi-info-circle"></i>
                                    Informasi Dasar Turnamen
                                </h5>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="bi bi-check-circle"></i>
                                        <div>
                                            <strong>Sukses!</strong> {{ session('success') }}
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        <div>
                                            <strong>Perbaiki kesalahan berikut:</strong>
                                            <ul class="mt-2 mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">
                                                <i class="bi bi-card-heading"></i>
                                                Nama Turnamen
                                                <span class="required">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name"
                                                value="{{ old('name', $tournamentData['name'] ?? '') }}" required
                                                placeholder="e.g., Ofs Champions League 2025">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slug" class="form-label">
                                                <i class="bi bi-link"></i>
                                                URL Slug
                                            </label>
                                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                                id="slug" name="slug"
                                                value="{{ old('slug', $tournamentData['slug'] ?? '') }}"
                                                placeholder="Auto-generates from name">
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description" class="form-label">
                                        <i class="bi bi-text-paragraph"></i>
                                        Description
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" rows="3"
                                        placeholder="Brief description of the tournament">{{ old('description', $tournamentData['description'] ?? '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date" class="form-label">
                                                <i class="bi bi-calendar-event"></i>
                                                Tanggal Mulai
                                                <span class="required">*</span>
                                            </label>
                                            <input type="date"
                                                class="form-control @error('start_date') is-invalid @enderror"
                                                id="start_date" name="start_date"
                                                value="{{ old('start_date', $tournamentData['start_date'] ?? '') }}"
                                                required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date" class="form-label">
                                                <i class="bi bi-calendar-check"></i>
                                                Tanggal Selesai
                                                <span class="required">*</span>
                                            </label>
                                            <input type="date"
                                                class="form-control @error('end_date') is-invalid @enderror"
                                                id="end_date" name="end_date"
                                                value="{{ old('end_date', $tournamentData['end_date'] ?? '') }}"
                                                required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="location" class="form-label">
                                                <i class="bi bi-geo-alt"></i>
                                                Location
                                            </label>
                                            <input type="text"
                                                class="form-control @error('location') is-invalid @enderror"
                                                id="location" name="location"
                                                value="{{ old('location', $tournamentData['location'] ?? '') }}"
                                                placeholder="e.g., Gor Ofs">
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="organizer" class="form-label">
                                                <i class="bi bi-building"></i>
                                                Organizer
                                            </label>
                                            <input type="text"
                                                class="form-control @error('organizer') is-invalid @enderror"
                                                id="organizer" name="organizer"
                                                value="{{ old('organizer', $tournamentData['organizer'] ?? '') }}"
                                                placeholder="e.g., OFs Sports Club">
                                            @error('organizer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Field untuk logo dan banner -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo" class="form-label">
                                                <i class="bi bi-image"></i>
                                                Tournament Logo
                                            </label>
                                            <div class="file-upload-container" onclick="document.getElementById('logo').click()">
                                                <i class="bi bi-cloud-upload"></i>
                                                <p class="mb-2">Click to upload logo</p>
                                                <small class="text-muted">Recommended: Square image, max 2MB, PNG/JPG format</small>
                                                <input type="file" class="form-control d-none @error('logo') is-invalid @enderror" 
                                                       id="logo" name="logo" accept="image/*" onchange="previewLogo(event)">
                                                @error('logo')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="file-preview" id="logoPreviewContainer" style="display: none;">
                                                <img id="logoPreview" alt="Logo Preview">
                                                <div class="file-name" id="logoFileName"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="banner" class="form-label">
                                                <i class="bi bi-image-fill"></i>
                                                Tournament Banner
                                            </label>
                                            <div class="file-upload-container" onclick="document.getElementById('banner').click()">
                                                <i class="bi bi-cloud-upload"></i>
                                                <p class="mb-2">Click to upload banner</p>
                                                <small class="text-muted">Recommended: Wide image (16:9), max 5MB, PNG/JPG format</small>
                                                <input type="file" class="form-control d-none @error('banner') is-invalid @enderror" 
                                                       id="banner" name="banner" accept="image/*" onchange="previewBanner(event)">
                                                @error('banner')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="file-preview" id="bannerPreviewContainer" style="display: none;">
                                                <img id="bannerPreview" alt="Banner Preview">
                                                <div class="file-name" id="bannerFileName"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type" class="form-label">
                                                <i class="bi bi-diagram-3"></i>
                                                Tipe Turnamen
                                                <span class="required">*</span>
                                            </label>
                                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                                <option value="" disabled {{ old('type', $tournamentData['type'] ?? '') ? '' : 'selected' }}>Select tournament format</option>
                                                <option value="league" {{ (old('type', $tournamentData['type'] ?? '') == 'league') ? 'selected' : '' }}>League (Round Robin)</option>
                                                <option value="knockout" {{ (old('type', $tournamentData['type'] ?? '') == 'knockout') ? 'selected' : '' }}>Knockout (Cup)</option>
                                                <option value="group_knockout" {{ (old('type', $tournamentData['type'] ?? '') == 'group_knockout') ? 'selected' : '' }}>Group Stage + Knockout</option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="form-label">
                                                <i class="bi bi-clock-history"></i>
                                                Status Awal
                                                <span class="required">*</span>
                                            </label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                id="status" name="status" required>
                                                <option value="upcoming" {{ (old('status', $tournamentData['status'] ?? '') == 'upcoming') ? 'selected' : '' }}>Upcoming</option>
                                                <option value="ongoing" {{ (old('status', $tournamentData['status'] ?? '') == 'ongoing') ? 'selected' : '' }}>Ongoing</option>
                                                <option value="completed" {{ (old('status', $tournamentData['status'] ?? '') == 'completed') ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ (old('status', $tournamentData['status'] ?? '') == 'cancelled') ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Group Stage Configuration -->
                                <div id="groupSettings" class="settings-section"
                                    style="display: {{ (old('type', $tournamentData['type'] ?? '') == 'group_knockout') ? 'block' : 'none' }};">
                                    <h6><i class="bi bi-grid-3x3"></i> Group Stage Configuration</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="groups_count" class="form-label">
                                                    Jumlah Grup
                                                    <span class="required">*</span>
                                                </label>
                                                <input type="number"
                                                    class="form-control @error('groups_count') is-invalid @enderror"
                                                    id="groups_count" name="groups_count"
                                                    value="{{ old('groups_count', $tournamentData['groups_count'] ?? 2) }}"
                                                    min="1" max="8">
                                                @error('groups_count')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="teams_per_group" class="form-label">
                                                    Tim per Grup
                                                    <span class="required">*</span>
                                                </label>
                                                <input type="number"
                                                    class="form-control @error('teams_per_group') is-invalid @enderror"
                                                    id="teams_per_group" name="teams_per_group"
                                                    value="{{ old('teams_per_group', $tournamentData['teams_per_group'] ?? 4) }}"
                                                    min="2" max="10">
                                                @error('teams_per_group')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                   <!-- Di dalam section <div id="groupSettings" class="settings-section" ...> -->
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="qualify_per_group" class="form-label">
                Tim Lolos per Grup
                <span class="required">*</span>
            </label>
            <input type="number"
                class="form-control @error('qualify_per_group') is-invalid @enderror"
                id="qualify_per_group" name="qualify_per_group"
                value="{{ old('qualify_per_group', $tournamentData['qualify_per_group'] ?? 2) }}"
                min="1" max="4">
            @error('qualify_per_group')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
                                </div>
                                </div>

                                <!-- Konfigurasi League -->
                                <div id="leagueSettings" class="settings-section" style="display: {{ (old('type', $tournamentData['type'] ?? '') == 'league') ? 'block' : 'none' }};">
                                    <h6><i class="bi bi-trophy"></i> Konfigurasi League</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="league_rounds" class="form-label">
                                                    Jumlah Ronde
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('league_rounds') is-invalid @enderror" id="league_rounds" name="league_rounds">
                                                    <option value="1" {{ (old('league_rounds', $tournamentData['league_rounds'] ?? 1) == 1) ? 'selected' : '' }}>Single Round</option>
                                                    <option value="2" {{ (old('league_rounds', $tournamentData['league_rounds'] ?? 1) == 2) ? 'selected' : '' }}>Double Round</option>
                                                </select>
                                                @error('league_rounds')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i>
                                                    Single round: Each team plays once. Double round: Each team plays home and away.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="league_standings_type" class="form-label">
                                                    Standings Tiebreaker
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('league_standings_type') is-invalid @enderror" id="league_standings_type" name="league_standings_type">
                                                    <option value="total_points" {{ (old('league_standings_type', $tournamentData['league_standings_type'] ?? 'total_points') == 'total_points') ? 'selected' : '' }}>Total Points</option>
                                                    <option value="head_to_head" {{ (old('league_standings_type', $tournamentData['league_standings_type'] ?? 'total_points') == 'head_to_head') ? 'selected' : '' }}>Head-to-Head</option>
                                                    <option value="goal_difference" {{ (old('league_standings_type', $tournamentData['league_standings_type'] ?? 'total_points') == 'goal_difference') ? 'selected' : '' }}>Goal Difference</option>
                                                </select>
                                                @error('league_standings_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i>
                                                    How to break ties when tim have equal points
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" id="league_allow_draw" name="league_allow_draw" value="1" {{ old('league_allow_draw', $tournamentData['league_allow_draw'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="league_allow_draw">
                                            Allow draws in league matches
                                        </label>
                                    </div>
                                </div>

                                <!-- Knockout Settings -->
                                <div id="knockoutSettings" class="settings-section" style="display: {{ (old('type', $tournamentData['type'] ?? '') == 'knockout') ? 'block' : 'none' }};">
                                    <h6><i class="bi bi-trophy"></i> Knockout (Cup) Configuration</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="knockout_format" class="form-label">
                                                    Tournament Format
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('knockout_format') is-invalid @enderror" id="knockout_format" name="knockout_format">
                                                    <option value="single_elimination" {{ (old('knockout_format', $tournamentData['knockout_format'] ?? 'single_elimination') == 'single_elimination') ? 'selected' : '' }}>Single Elimination</option>
                                                    <option value="double_elimination" {{ (old('knockout_format', $tournamentData['knockout_format'] ?? 'single_elimination') == 'double_elimination') ? 'selected' : '' }}>Double Elimination</option>
                                                </select>
                                                @error('knockout_format')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="knockout_tim" class="form-label">
                                                    Jumlah Tim
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('knockout_tim') is-invalid @enderror" id="knockout_tim" name="knockout_tim">
                                                    <option value="2" {{ (old('knockout_tim', $tournamentData['knockout_tim'] ?? 8) == 2) ? 'selected' : '' }}>2 Teams (Final)</option>
                                                    <option value="4" {{ (old('knockout_tim', $tournamentData['knockout_tim'] ?? 8) == 4) ? 'selected' : '' }}>4 Teams (Semi-Finals)</option>
                                                    <option value="8" {{ (old('knockout_tim', $tournamentData['knockout_tim'] ?? 8) == 8) ? 'selected' : '' }}>8 Teams (Quarter-Finals)</option>
                                                    <option value="16" {{ (old('knockout_tim', $tournamentData['knockout_tim'] ?? 8) == 16) ? 'selected' : '' }}>16 Teams (Round of 16)</option>
                                                    <option value="32" {{ (old('knockout_tim', $tournamentData['knockout_tim'] ?? 8) == 32) ? 'selected' : '' }}>32 Teams</option>
                                                </select>
                                                @error('knockout_tim')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="knockout_seeding" class="form-label">
                                                    Metode Seeding
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('knockout_seeding') is-invalid @enderror" id="knockout_seeding" name="knockout_seeding">
                                                    <option value="random" {{ (old('knockout_seeding', $tournamentData['knockout_seeding'] ?? 'random') == 'random') ? 'selected' : '' }}>Random Draw</option>
                                                    <option value="ranked" {{ (old('knockout_seeding', $tournamentData['knockout_seeding'] ?? 'random') == 'ranked') ? 'selected' : '' }}>Ranked Seeding</option>
                                                    <option value="manual" {{ (old('knockout_seeding', $tournamentData['knockout_seeding'] ?? 'random') == 'manual') ? 'selected' : '' }}>Manual Assignment</option>
                                                </select>
                                                @error('knockout_seeding')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="knockout_byes" class="form-label">
                                                    Jumlah Bye
                                                </label>
                                                <input type="number" class="form-control @error('knockout_byes') is-invalid @enderror" id="knockout_byes" name="knockout_byes"
                                                    value="{{ old('knockout_byes', $tournamentData['knockout_byes'] ?? 0) }}" min="0" max="16">
                                                @error('knockout_byes')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i>
                                                    Teams that get a free pass to next round
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" id="knockout_third_place" name="knockout_third_place" value="1" {{ old('knockout_third_place', $tournamentData['knockout_third_place'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="knockout_third_place">
                                            Include third place match
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
