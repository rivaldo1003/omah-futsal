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
                                <div class="tournament-preview">
                                    <h4 class="review-name" id="reviewName">
                                        {{ old('name', $tournamentData['name'] ?? 'Ofs Champions League 2025') }}
                                    </h4>

                                    <dl class="review-list">
                                        <div class="review-row">
                                            <dt>Tanggal</dt>
                                            <dd id="reviewDates">
                                                @if(!empty($tournamentData['start_date']) && !empty($tournamentData['end_date']))
                                                    {{ date('d M Y', strtotime($tournamentData['start_date'])) }} –
                                                    {{ date('d M Y', strtotime($tournamentData['end_date'])) }}
                                                @else
                                                    Belum ditentukan
                                                @endif
                                            </dd>
                                        </div>
                                        <div class="review-row">
                                            <dt>Tipe</dt>
                                            <dd id="reviewType">
                                                @php
                                                    $typeNames = [
                                                        'league' => 'League',
                                                        'knockout' => 'Knockout',
                                                        'group_knockout' => 'Group + Knockout'
                                                    ];
                                                    $selectedType = old('type', $tournamentData['type'] ?? '');
                                                @endphp
                                                {{ $typeNames[$selectedType] ?? 'Belum dipilih' }}
                                            </dd>
                                        </div>
                                        <div class="review-row">
                                            <dt>Tim</dt>
                                            <dd id="reviewTeams">
                                                {{ count(old('teams', $tournamentData['teams'] ?? [])) }} tim
                                            </dd>
                                        </div>
                                        <div class="review-row">
                                            <dt>Pertandingan</dt>
                                            <dd id="reviewMatches">0</dd>
                                        </div>
                                        <div class="review-row">
                                            <dt>Lokasi</dt>
                                            <dd id="reviewLocation">
                                                {{ old('location', $tournamentData['location'] ?? '—') }}
                                            </dd>
                                        </div>
                                        <div class="review-row">
                                            <dt>Penyelenggara</dt>
                                            <dd id="reviewOrganizer">
                                                {{ old('organizer', $tournamentData['organizer'] ?? '—') }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-people"></i> Tim peserta</h6>
                                    <div class="row g-2" id="reviewTeamsList">
                                        @if(!empty($tournamentData['teams']))
                                            @foreach($teams->whereIn('id', $tournamentData['teams']) as $team)
                                                @php
                                                    $logoUrl = ($team->logo && Storage::disk('public')->exists($team->logo)) ? Storage::url($team->logo) : null;
                                                @endphp
                                                <div class="col-6 col-md-4 col-lg-3">
                                                    <div class="team-card">
                                                        <div class="team-logo-placeholder">
                                                            @if($logoUrl)
                                                                <img src="{{ $logoUrl }}" alt="{{ $team->name }}">
                                                            @else
                                                                {{ substr($team->name, 0, 2) }}
                                                            @endif
                                                        </div>
                                                        <div class="team-card-info">
                                                            <span class="team-card-name">{{ $team->name }}</span>
                                                            @if($team->coach_name)
                                                                <span class="team-card-coach">{{ $team->coach_name }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12 text-muted">Belum ada tim dipilih</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-joystick"></i> Aturan pertandingan</h6>
                                    <dl class="review-list">
                                        <div class="review-row">
                                            <dt>Durasi</dt>
                                            <dd id="reviewDuration">
                                                Waktu normal {{ old('match_duration', $tournamentData['match_duration'] ?? 40) }} menit,
                                                istirahat {{ old('half_time', $tournamentData['half_time'] ?? 10) }} menit
                                            </dd>
                                        </div>
                                        <div class="review-row">
                                            <dt>Poin</dt>
                                            <dd id="reviewPoints">
                                                Menang {{ old('points_win', $tournamentData['points_win'] ?? 3) }} ·
                                                Seri {{ old('points_draw', $tournamentData['points_draw'] ?? 1) }} ·
                                                Kalah {{ old('points_loss', $tournamentData['points_loss'] ?? 0) }}
                                            </dd>
                                        </div>
                                        <div class="review-row">
                                            <dt>Pemain cadangan</dt>
                                            <dd id="reviewSubstitutes">
                                                Maksimal {{ old('max_substitutes', $tournamentData['max_substitutes'] ?? 5) }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>

                                <div class="settings-section">
                                    <h6><i class="bi bi-images"></i> Media turnamen</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="section-label">Logo</div>
                                            <div id="reviewLogoContainer">
                                                @if(!empty($tournamentData['logo']))
                                                    <img src="{{ Storage::url($tournamentData['logo']) }}"
                                                        alt="Logo turnamen" class="review-media">
                                                @else
                                                    <div class="text-secondary">Belum ada logo</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="section-label">Banner</div>
                                            <div id="reviewBannerContainer">
                                                @if(!empty($tournamentData['banner']))
                                                    <img src="{{ Storage::url($tournamentData['banner']) }}"
                                                        alt="Banner turnamen" class="review-banner">
                                                @else
                                                    <div class="text-secondary">Belum ada banner</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <p class="group-hint">
                                    Setelah turnamen dibuat, Anda bisa generate jadwal pertandingan, menambah pemain,
                                    dan mengelola hasil pertandingan. Nama, tanggal, tipe, dan tim peserta tidak bisa
                                    diubah setelah dibuat — pastikan sudah benar.
                                </p>

                                <div class="form-check">
                                    <input class="form-check-input @error('confirmTournament') is-invalid @enderror"
                                        type="checkbox" id="confirmTournament" name="confirmTournament" value="1"
                                        {{ old('confirmTournament', isset($tournamentData['confirmTournament']) ? 'checked' : '') }}>
                                    <label class="form-check-label" for="confirmTournament">
                                        Saya konfirmasi semua informasi sudah benar dan ingin membuat turnamen ini
                                        <span class="required">*</span>
                                    </label>
                                    @error('confirmTournament')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>