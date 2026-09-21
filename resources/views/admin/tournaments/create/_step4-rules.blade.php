<!-- Step 4: Match Rules -->
                    <div class="step-content" id="step4" style="display: {{ $currentStep == 4 ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    <i class="bi bi-joystick"></i>
                                    Aturan & Pengaturan Pertandingan
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="group-hint">
                                    Semua pengaturan di bawah sudah memiliki nilai default yang masuk akal. Ubah hanya jika perlu.
                                </p>

                                <div class="settings-section">
                                    <h6><i class="bi bi-clock"></i> Durasi Pertandingan</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="match_duration" class="form-label">Durasi waktu normal
                                                    (menit)</label>
                                                <input type="number"
                                                    class="form-control @error('match_duration') is-invalid @enderror"
                                                    id="match_duration" name="match_duration"
                                                    value="{{ old('match_duration', $tournamentData['match_duration'] ?? 40) }}"
                                                    min="10" max="120">
                                                <div class="form-text">Total waktu efektif babak normal. Futsal standar: 2 × 20 = 40 menit</div>
                                                @error('match_duration')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="half_time" class="form-label">Istirahat antar babak
                                                    (menit)</label>
                                                <input type="number"
                                                    class="form-control @error('half_time') is-invalid @enderror"
                                                    id="half_time" name="half_time"
                                                    value="{{ old('half_time', $tournamentData['half_time'] ?? 10) }}"
                                                    min="5" max="30">
                                                <div class="form-text">Jeda antara babak 1 dan babak 2</div>
                                                @error('half_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check mb-3" style="margin-top: 38px;">
                                                <input class="form-check-input" type="checkbox" id="extra_time_enabled"
                                                    name="extra_time_enabled" value="1" {{ old('extra_time_enabled', $tournamentData['extra_time_enabled'] ?? true) ? 'checked' : '' }}
                                                    onchange="document.getElementById('extraTimeField').style.display = this.checked ? 'block' : 'none';">
                                                <label class="form-check-label" for="extra_time_enabled">
                                                    Extra time untuk pertandingan knockout
                                                </label>
                                            </div>
                                            <div class="form-group" id="extraTimeField"
                                                style="display: {{ old('extra_time_enabled', $tournamentData['extra_time_enabled'] ?? true) ? 'block' : 'none' }};">
                                                <label for="extra_time" class="form-label">Durasi per babak extra time
                                                    (menit)</label>
                                                <input type="number"
                                                    class="form-control @error('extra_time') is-invalid @enderror"
                                                    id="extra_time" name="extra_time"
                                                    value="{{ old('extra_time', $tournamentData['extra_time'] ?? 10) }}"
                                                    min="0" max="30">
                                                <div class="form-text">Dimainkan jika skor seri di knockout</div>
                                                @error('extra_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-flag"></i> Sistem Poin</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="points_win" class="form-label">Menang</label>
                                                <input type="number"
                                                    class="form-control @error('points_win') is-invalid @enderror"
                                                    id="points_win" name="points_win"
                                                    value="{{ old('points_win', $tournamentData['points_win'] ?? 3) }}"
                                                    min="1" max="10">
                                                @error('points_win')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="points_draw" class="form-label">Seri</label>
                                                <input type="number"
                                                    class="form-control @error('points_draw') is-invalid @enderror"
                                                    id="points_draw" name="points_draw"
                                                    value="{{ old('points_draw', $tournamentData['points_draw'] ?? 1) }}"
                                                    min="0" max="5">
                                                @error('points_draw')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="points_loss" class="form-label">Kalah</label>
                                                <input type="number"
                                                    class="form-control @error('points_loss') is-invalid @enderror"
                                                    id="points_loss" name="points_loss"
                                                    value="{{ old('points_loss', $tournamentData['points_loss'] ?? 0) }}"
                                                    min="0" max="5">
                                                @error('points_loss')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="points_no_show" class="form-label">No show</label>
                                                <input type="number"
                                                    class="form-control @error('points_no_show') is-invalid @enderror"
                                                    id="points_no_show" name="points_no_show"
                                                    value="{{ old('points_no_show', $tournamentData['points_no_show'] ?? -1) }}"
                                                    min="-10" max="0">
                                                @error('points_no_show')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section" id="tiebreakerSettings" style="display: {{ (old('type', $tournamentData['type'] ?? '') == 'group_knockout') ? 'block' : 'none' }};">
                                    <h6>Penentuan peringkat (tie-breakers) — Group Stage</h6>
                                    <small class="text-secondary d-block mb-3">Urutan menentukan prioritas klasemen saat poin tim sama. Seret item untuk mengubah urutan.</small>

                                    <div class="tie-preset">
                                        <small class="text-secondary">Template cepat:</small>
                                        <button type="button" class="btn-preset preset-btn" data-preset="gd_first">Selisih gol dulu</button>
                                        <button type="button" class="btn-preset preset-btn" data-preset="h2h_first">Head-to-head dulu</button>
                                        <button type="button" class="btn-preset preset-btn" data-preset="fifa">Standar FIFA</button>
                                        <button type="button" class="btn-preset preset-btn" data-preset="goals_first">Produktivitas gol dulu</button>
                                        <button type="button" class="btn-preset" id="resetTieBreakers">Reset</button>
                                    </div>

                                    <div id="tie-breakers-container">
                                        @php
                                            $tieBreakerOptions = [
                                                'points' => 'Poin',
                                                'head_to_head' => 'Head-to-head',
                                                'goal_difference' => 'Selisih gol',
                                                'goals_scored' => 'Gol mencetak',
                                                'fair_play' => 'Fair play',
                                                'penalty' => 'Adu penalti',
                                            ];

                                            $currentRules = old('tie_breakers', $tournamentData['tie_breakers'] ?? null);
                                            if (empty($currentRules)) {
                                                $currentRules = ['head_to_head', 'goal_difference', 'goals_scored', 'fair_play', 'penalty'];
                                            } elseif (is_array($currentRules)) {
                                                $currentRules = array_is_list($currentRules) ? $currentRules : array_keys($currentRules);
                                            } else {
                                                $currentRules = [(string) $currentRules];
                                            }
                                        @endphp
                                        @foreach($currentRules as $index => $rule)
                                            <div class="input-group mb-2 tie-breaker-item" draggable="true" style="cursor: move;">
                                                <span class="input-group-text bg-light text-secondary fw-bold drag-handle" style="cursor: grab;">
                                                    <i class="bi bi-grip-vertical"></i> {{ $index + 1 }}
                                                </span>
                                                <select class="form-select tie-breaker-select" name="tie_breakers[{{ $index }}][key]" required>
                                                    @foreach($tieBreakerOptions as $key => $label)
                                                        <option value="{{ $key }}" {{ $rule == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn btn-outline-danger remove-rule">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-rule">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah Aturan
                                    </button>
                                    <small class="text-secondary d-block mt-2">Urutan ini juga dipakai untuk penentuan juara dan runner-up di halaman Home.</small>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-card-checklist"></i> Aturan Pertandingan</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="max_substitutes" class="form-label">Maksimal pemain
                                                    cadangan</label>
                                                <input type="number"
                                                    class="form-control @error('max_substitutes') is-invalid @enderror"
                                                    id="max_substitutes" name="max_substitutes"
                                                    value="{{ old('max_substitutes', $tournamentData['max_substitutes'] ?? 5) }}"
                                                    min="0" max="20">
                                                @error('max_substitutes')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="yellow_card_suspension" class="form-label">Kartu kuning untuk
                                                    suspensi</label>
                                                <input type="number"
                                                    class="form-control @error('yellow_card_suspension') is-invalid @enderror"
                                                    id="yellow_card_suspension" name="yellow_card_suspension"
                                                    value="{{ old('yellow_card_suspension', $tournamentData['yellow_card_suspension'] ?? 3) }}"
                                                    min="1" max="10">
                                                @error('yellow_card_suspension')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="allow_draw"
                                                    name="allow_draw" value="1" {{ old('allow_draw', $tournamentData['allow_draw'] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="allow_draw">
                                                    Izinkan hasil seri di group stage
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="penalty_shootout"
                                                    name="penalty_shootout" value="1" {{ old('penalty_shootout', $tournamentData['penalty_shootout'] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="penalty_shootout">
                                                    Adu penalti setelah extra time
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pengaturan jadwal otomatis dihapus dari form create.
                                     Nilai default (4 pertandingan/hari, jeda 30 menit, slot 14:00-20:00)
                                     diisi otomatis saat create tournament dan dipakai fitur Generate Jadwal. -->
                            </div>
                        </div>
                    </div>