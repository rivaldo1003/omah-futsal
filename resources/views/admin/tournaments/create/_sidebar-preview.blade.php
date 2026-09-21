<div class="card">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-eye"></i>
                            Ringkasan
                        </h5>
                    </div>
                    <div class="card-body">
                        <h4 class="review-name" id="previewName">
                            {{ old('name', $tournamentData['name'] ?? 'Belum ada nama') }}
                        </h4>

                        <dl class="review-list">
                            <div class="review-row">
                                <dt>Tipe</dt>
                                <dd id="previewTournamentType">
                                    @php
                                        $type = old('type', $tournamentData['type'] ?? '');
                                        $typeNames = [
                                            'league' => 'League',
                                            'knockout' => 'Knockout',
                                            'group_knockout' => 'Group + Knockout'
                                        ];
                                    @endphp
                                    {{ $typeNames[$type] ?? 'Belum dipilih' }}
                                </dd>
                            </div>
                            <div class="review-row">
                                <dt>Tim</dt>
                                <dd>
                                    <span id="previewTeamCount">{{ count(old('teams', $tournamentData['teams'] ?? [])) }}</span>
                                    tim
                                </dd>
                            </div>
                            <div class="review-row">
                                <dt>Pertandingan</dt>
                                <dd id="previewMatchCount">0</dd>
                            </div>
                            <div class="review-row">
                                <dt>Durasi</dt>
                                <dd id="previewDurationDays">
                                    @if(!empty($tournamentData['start_date']) && !empty($tournamentData['end_date']))
                                        @php
                                            $start = new DateTime($tournamentData['start_date']);
                                            $end = new DateTime($tournamentData['end_date']);
                                            $interval = $start->diff($end);
                                            echo ($interval->days + 1) . ' hari';
                                        @endphp
                                    @else
                                        0 hari
                                    @endif
                                </dd>
                            </div>
                        </dl>

                        <div class="sidebar-section">
                            <div class="section-label">Tim terpilih</div>
                            <div class="row g-2" id="selectedTeamsPreview">
                                @if(!empty($tournamentData['teams']))
                                    @foreach($teams->whereIn('id', $tournamentData['teams'])->take(5) as $team)
                                        @php
                                            $logoUrl = ($team->logo && Storage::disk('public')->exists($team->logo)) ? Storage::url($team->logo) : null;
                                        @endphp
                                        <div class="col-12">
                                            <div class="team-card static">
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
                                    @if(count($tournamentData['teams']) > 5)
                                        <div class="col-12">
                                            <small class="text-secondary">+ {{ count($tournamentData['teams']) - 5 }} tim lainnya</small>
                                        </div>
                                    @endif
                                @else
                                    <div class="col-12 text-secondary">Belum ada tim dipilih</div>
                                @endif
                            </div>
                        </div>

                        <div class="sidebar-section">
                            <div class="section-label">Logo</div>
                            <div id="previewLogoContainer">
                                @if(!empty($tournamentData['logo']))
                                    <img src="{{ Storage::url($tournamentData['logo']) }}"
                                        alt="Logo turnamen" class="review-media">
                                @else
                                    <div class="text-secondary">Belum ada logo</div>
                                @endif
                            </div>
                        </div>

                        <p class="group-hint" style="margin: 16px 0 0;">
                            Ringkasan diperbarui otomatis mengikuti isian form.
                        </p>
                    </div>
                </div>