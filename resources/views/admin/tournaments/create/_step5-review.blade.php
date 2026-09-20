<!-- Step 5: Review -->
                    <div class="step-content" id="step5" style="display: {{ $currentStep == 5 ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    <i class="bi bi-eye"></i>
                                    Review & Buat Turnamen
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="tournament-preview mb-4">
                                    <h6>Ringkasan turnamen</h6>
                                    <h4 id="reviewName">
                                        {{ old('name', $tournamentData['name'] ?? 'Ofs Champions League 2025') }}
                                    </h4>

                                    <div class="preview-grid">
                                        <div class="preview-item">
                                            <label>Tanggal</label>
                                            <div class="value" id="reviewDates">
                                                @if(!empty($tournamentData['start_date']) && !empty($tournamentData['end_date']))
                                                    {{ date('d M Y', strtotime($tournamentData['start_date'])) }} to
                                                    {{ date('d M Y', strtotime($tournamentData['end_date'])) }}
                                                @else
                                                    -- -- ---- to -- -- ----
                                                @endif
                                            </div>
                                        </div>
                                        <div class="preview-item">
                                            <label>Tipe</label>
                                            <div class="value badge bg-primary" id="reviewType">
                                                @php
                                                    $typeNames = [
                                                        'league' => 'League',
                                                        'knockout' => 'Knockout',
                                                        'group_knockout' => 'Group + Knockout'
                                                    ];
                                                    $selectedType = old('type', $tournamentData['type'] ?? '');
                                                @endphp
                                                {{ $typeNames[$selectedType] ?? 'Select Type' }}
                                            </div>
                                        </div>
                                        <div class="preview-item">
                                            <label>Tim</label>
                                            <div class="value" id="reviewTeams">
                                                {{ count(old('teams', $tournamentData['teams'] ?? [])) }} tim
                                            </div>
                                        </div>
                                        <div class="preview-item">
                                            <label>Pertandingan</label>
                                            <div class="value" id="reviewMatches">0 matches</div>
                                        </div>
                                        <div class="preview-item">
                                            <label>Lokasi</label>
                                            <div class="value" id="reviewLocation">
                                                {{ old('location', $tournamentData['location'] ?? '--') }}
                                            </div>
                                        </div>
                                        <div class="preview-item">
                                            <label>Penyelenggara</label>
                                            <div class="value" id="reviewOrganizer">
                                                {{ old('organizer', $tournamentData['organizer'] ?? '--') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-people"></i> Tim Peserta</h6>
                                    <div class="selected-tim-preview" id="reviewTeamsList">
                                        @if(!empty($tournamentData['teams']))
                                            @foreach($teams->whereIn('id', $tournamentData['teams']) as $team)
                                                @php
                                                    $logoUrl = $team->logo ? Storage::url($team->logo) : null;
                                                @endphp
                                                <div class="selected-team-item">
                                                    @if($logoUrl)
                                                        <img src="{{ $logoUrl }}" alt="{{ $team->name }}"
                                                            style="width: 30px; height: 30px; border-radius: 6px; object-fit: cover;">
                                                    @else
                                                        <div class="selected-team-logo">{{ substr($team->name, 0, 1) }}</div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $team->name }}</div>
                                                        @if($team->coach_name)
                                                            <small class="text-muted">Coach: {{ $team->coach_name }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-muted">Belum ada tim dipilih</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-joystick"></i> Ringkasan Aturan Pertandingan</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="preview-item">
                                                <label>Match Duration</label>
                                                <div class="value" id="reviewDuration">
                                                    {{ old('match_duration', $tournamentData['match_duration'] ?? 40) }}
                                                    mins
                                                    ({{ old('half_time', $tournamentData['half_time'] ?? 10) }} mins
                                                    half)
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="preview-item">
                                                <label>Points System</label>
                                                <div class="value" id="reviewPoints">
                                                    Menang: {{ old('points_win', $tournamentData['points_win'] ?? 3) }},
                                                    Seri: {{ old('points_draw', $tournamentData['points_draw'] ?? 1) }},
                                                    Kalah: {{ old('points_loss', $tournamentData['points_loss'] ?? 0) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="preview-item">
                                                <label>Max Substitutes</label>
                                                <div class="value" id="reviewSubstitutes">
                                                    {{ old('max_substitutes', $tournamentData['max_substitutes'] ?? 5) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview Logo dan Banner -->
                                <div class="settings-section">
                                    <h6><i class="bi bi-images"></i> Media Turnamen</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <p class="mb-2"><strong>Logo</strong></p>
                                                <div id="reviewLogoContainer" class="mb-3">
                                                    @if(!empty($tournamentData['logo']))
                                                        <img src="{{ Storage::url($tournamentData['logo']) }}" 
                                                             alt="Tournament Logo" 
                                                             style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                                                    @else
                                                        <div class="text-muted">Belum ada logo</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <p class="mb-2"><strong>Banner</strong></p>
                                                <div id="reviewBannerContainer" class="mb-3">
                                                    @if(!empty($tournamentData['banner']))
                                                        <img src="{{ Storage::url($tournamentData['banner']) }}" 
                                                             alt="Tournament Banner" 
                                                             style="max-width: 300px; max-height: 150px; border-radius: 8px;">
                                                    @else
                                                        <div class="text-muted">Belum ada banner</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-box mt-4">
                                    <i class="bi bi-lightbulb"></i>
                                    <p>
                                        <strong>Ready to create your tournament?</strong><br>
                                        Review all the information above. Once created, you'll be able to:
                                        1. Generate the match schedule automatically
                                        2. Add team players and staff
                                        3. Start managing matches and results
                                    </p>
                                </div>

                                <div class="warning-box mt-3">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <p>
                                        <strong>Important:</strong> Make sure all information is correct before
                                        proceeding.
                                        Tournament name, dates, type, and selected tim cannot be easily changed after
                                        creation.
                                    </p>
                                </div>

                               <div class="form-check mt-4">
                                    <input class="form-check-input @error('confirmTournament') is-invalid @enderror"
                                        type="checkbox" id="confirmTournament" name="confirmTournament" value="1" 
                                        {{ old('confirmTournament', isset($tournamentData['confirmTournament']) ? 'checked' : '') }}>
                                    <label class="form-check-label" for="confirmTournament">
                                        <strong>Saya konfirmasi semua informasi sudah benar dan ingin membuat turnamen ini</strong>
                                        <span class="required">*</span>
                                    </label>
                                    @error('confirmTournament')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
