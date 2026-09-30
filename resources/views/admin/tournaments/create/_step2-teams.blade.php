<!-- Step 2: Pilih Tim -->
                    <div class="step-content" id="step2" style="display: {{ $currentStep == 2 ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-people"></i> Pilih Tim</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $selectedTeamIds = array_map('strval', old('teams', $tournamentData['teams'] ?? []));
                                @endphp

                                <!-- Ringkasan & batas -->
                                <div class="team-picker-header">
                                    <div class="team-picker-search">
                                        <i class="bi bi-search"></i>
                                        <input type="text" id="team_search" class="form-control"
                                            placeholder="Cari nama tim atau coach...">
                                    </div>
                                    <div class="team-picker-counter">
                                        <div class="counter-box">
                                            <span class="counter-num" id="selectedTeamsCount">0</span>
                                            <span class="counter-lbl">Dipilih</span>
                                        </div>
                                        <div class="counter-box">
                                            <span class="counter-num" id="maxTeamsCount">∞</span>
                                            <span class="counter-lbl">Maks</span>
                                        </div>
                                        <div class="counter-box">
                                            <span class="counter-num" id="totalTeamsCount">{{ $teams->count() }}</span>
                                            <span class="counter-lbl">Tersedia</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="team-picker-hint" id="teamPickerHint">
                                    <i class="bi bi-info-circle"></i>
                                    <span>Klik kartu tim untuk memilih / membatalkan.</span>
                                </div>

                                <!-- Grid kartu tim -->
                                <div class="team-picker-grid" id="teamPickerGrid">
                                    @foreach($teams as $team)
                                        @php
                                            $isSelected = in_array((string) $team->id, $selectedTeamIds, true);
                                            $logoUrl = ($team->logo && Storage::disk('public')->exists($team->logo))
                                                ? Storage::url($team->logo)
                                                : null;
                                        @endphp
                                        <button type="button"
                                            class="team-pick-card {{ $isSelected ? 'is-selected' : '' }}"
                                            data-team-id="{{ $team->id }}"
                                            data-search="{{ strtolower($team->name . ' ' . ($team->coach_name ?? '')) }}">
                                            <span class="team-pick-check"><i class="bi bi-check-lg"></i></span>
                                            <span class="team-pick-logo">
                                                @if($logoUrl)
                                                    <img src="{{ $logoUrl }}" alt="{{ $team->name }}">
                                                @else
                                                    {{ strtoupper(substr($team->name, 0, 2)) }}
                                                @endif
                                            </span>
                                            <span class="team-pick-info">
                                                <span class="team-pick-name" title="{{ $team->name }}">{{ $team->name }}</span>
                                                <span class="team-pick-coach">
                                                    @if($team->coach_name)
                                                        <i class="bi bi-person"></i> {{ $team->coach_name }}
                                                    @else
                                                        <span class="text-muted">Tanpa coach</span>
                                                    @endif
                                                </span>
                                            </span>
                                        </button>
                                    @endforeach
                                </div>

                                <div class="team-picker-empty d-none" id="teamPickerEmpty">
                                    <i class="bi bi-search"></i>
                                    <span>Tidak ada tim yang cocok dengan pencarian.</span>
                                </div>

                                <!-- Sumber nilai untuk form (disembunyikan) -->
                                <select id="teams" name="teams[]" multiple class="d-none">
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}"
                                            data-name="{{ $team->name }}"
                                            data-logo="{{ $team->logo ? Storage::url($team->logo) : '' }}"
                                            data-coach="{{ $team->coach_name }}"
                                            {{ in_array((string) $team->id, $selectedTeamIds, true) ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="teams_required" id="teams_required"
                                    value="{{ count($selectedTeamIds) > 0 ? '1' : '0' }}">

                                @error('teams')
                                    <div class="text-danger small mt-2"><i class="bi bi-exclamation-triangle"></i> {{ $message }}</div>
                                @enderror

                                <!-- Preview Bagan Knockout (hanya untuk tipe knockout) -->
                                <div class="settings-section" id="bracketPreviewSection"
                                    style="display: {{ (old('type', $tournamentData['type'] ?? '') == 'knockout') ? 'block' : 'none' }};">
                                    <h6><i class="bi bi-diagram-3"></i> Preview Bagan Knockout</h6>
                                    <div class="bracket-preview" id="bracketPreview">
                                        <div class="bracket-preview-empty">
                                            <i class="bi bi-diagram-3"></i>
                                            <span>Pilih tim untuk melihat bentuk bagan.</span>
                                        </div>
                                    </div>
                                    <div class="bracket-preview-legend">
                                        <span class="legend-item"><span class="legend-dot legend-team"></span> Tim</span>
                                        <span class="legend-item"><span class="legend-dot legend-bye"></span> Bye (lolos otomatis)</span>
                                        <span class="legend-item"><span class="legend-dot legend-tbd"></span> Menunggu pemenang</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>