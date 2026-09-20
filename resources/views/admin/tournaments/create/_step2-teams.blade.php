<!-- Step 2: Pilih Tim -->
                    <div class="step-content" id="step2" style="display: {{ $currentStep == 2 ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-people"></i> Pilih Tim</h5>
                            </div>
                            <div class="card-body">
                                <div class="settings-section">
                                    <h6><i class="bi bi-filter"></i> Filter Tim</h6>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="team_search" class="form-label">Cari Tim</label>
                                                <input type="text" class="form-control" id="team_search"
                                                    placeholder="Cari nama tim atau coach...">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-list-check"></i> Select Tim Peserta</h6>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <span class="text-muted">Total tim tersedia: </span>
                                            <span class="fw-bold" id="totalTeamsCount">{{ $teams->count() }}</span>
                                        </div>
                                        <div>
                                            <span class="text-muted">Dipilih: </span>
                                            <span class="fw-bold text-success" id="selectedTeamsCount">0</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="teams" class="form-label">
                                            <i class="bi bi-check2-square"></i>
                                            Select Teams
                                            <span class="required">*</span>
                                        </label>
                                        <select class="form-control @error('teams') is-invalid @enderror" id="teams"
                                            name="teams[]" multiple="multiple">
                                            @foreach($teams as $team)
                                                @php
                                                    $logoUrl = $team->logo ? Storage::url($team->logo) : null;
                                                @endphp
                                                <option value="{{ $team->id }}" data-name="{{ $team->name }}"
                                                    data-logo="{{ $logoUrl }}" data-coach="{{ $team->coach_name }}" {{ in_array($team->id, old('teams', $tournamentData['teams'] ?? [])) ? 'selected' : '' }}>
                                                    {{ $team->name }}
                                                    @if($team->coach_name)
                                                        ({{ $team->coach_name }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="teams_required" id="teams_required"
                                            value="{{ count(old('teams', $tournamentData['teams'] ?? [])) > 0 ? '1' : '0' }}">

                                        @error('teams')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="bi bi-info-circle"></i>
                                            Pilih semua tim yang akan ikut turnamen ini
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
