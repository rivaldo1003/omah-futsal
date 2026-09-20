<div class="card">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-eye"></i>
                            Preview Turnamen
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="tournament-preview mb-4">
                            <h6>Preview cepat</h6>
                            <h4 id="previewName">{{ old('name', $tournamentData['name'] ?? '--') }}</h4>

                            <div class="preview-grid">
                                <div class="preview-item">
                                    <label>Tim</label>
                                    <div class="value" id="previewTeamCount">
                                        {{ count(old('teams', $tournamentData['teams'] ?? [])) }}
                                    </div>
                                </div>
                                <div class="preview-item">
                                    <label>Pertandingan</label>
                                    <div class="value" id="previewMatchCount">0</div>
                                </div>
                                <div class="preview-item">
                                    <label>Durasi</label>
                                    <div class="value" id="previewDurationDays">
                                        @if(!empty($tournamentData['start_date']) && !empty($tournamentData['end_date']))
                                            @php
                                                $start = new DateTime($tournamentData['start_date']);
                                                $end = new DateTime($tournamentData['end_date']);
                                                $interval = $start->diff($end);
                                                echo ($interval->days + 1) . ' days';
                                            @endphp
                                        @else
                                            0 days
                                        @endif
                                    </div>
                                </div>
                                <div class="preview-item">
                                    <label>Tipe</label>
                                    <div class="value" id="previewTournamentType">
                                        @php
                                            $type = old('type', $tournamentData['type'] ?? '');
                                            $typeNames = [
                                                'league' => 'League',
                                                'knockout' => 'Knockout',
                                                'group_knockout' => 'Group + Knockout'
                                            ];
                                        @endphp
                                        {{ $typeNames[$type] ?? '--' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="settings-section">
                            <h6><i class="bi bi-people"></i> Tim Terpilih</h6>
                            <div class="selected-tim-preview" id="selectedTeamsPreview">
                                @if(!empty($tournamentData['teams']))
                                    @foreach($teams->whereIn('id', $tournamentData['teams'])->take(5) as $team)
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
                                    @if(count($tournamentData['teams']) > 5)
                                        <div class="text-center text-muted mt-2">
                                            + {{ count($tournamentData['teams']) - 5 }} more tim
                                        </div>
                                    @endif
                                @else
                                    <div class="text-muted">Belum ada tim dipilih</div>
                                @endif
                            </div>
                        </div>

                        <div class="quick-stats mt-4">
                            <div class="stat-box">
                                <div class="stat-value" id="quickTotalTeams">
                                    {{ count(old('teams', $tournamentData['teams'] ?? [])) }}
                                </div>
                                <div class="stat-label">Total Tim</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-value" id="quickTotalMatches">0</div>
                                <div class="stat-label">Total Pertandingan</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-value" id="quickGroups">
                                    {{ old('groups_count', $tournamentData['groups_count'] ?? 0) }}
                                </div>
                                <div class="stat-label">Grup</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-value" id="quickDuration">
                                    @if(!empty($tournamentData['start_date']) && !empty($tournamentData['end_date']))
                                        @php
                                            $start = new DateTime($tournamentData['start_date']);
                                            $end = new DateTime($tournamentData['end_date']);
                                            echo $start->diff($end)->days + 1;
                                        @endphp
                                    @else
                                        0
                                    @endif
                                </div>
                                <div class="stat-label">Hari</div>
                            </div>
                        </div>

                        <!-- Preview Logo -->
                        <div class="settings-section mt-4">
                            <h6><i class="bi bi-image"></i> Tournament Logo</h6>
                            <div class="text-center">
                                <div id="previewLogoContainer">
                                    @if(!empty($tournamentData['logo']))
                                        <img src="{{ Storage::url($tournamentData['logo']) }}" 
                                             alt="Tournament Logo" 
                                             style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                                    @else
                                        <div class="text-muted">
                                            <i class="bi bi-image" style="font-size: 3rem;"></i>
                                            <p>Belum ada logo</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="info-box mt-4">
                            <i class="bi bi-lightbulb"></i>
                            <p>
                                <strong>Preview Turnamen</strong><br>
                                This preview updates in real-time as you fill out the form. All calculations are based
                                on your current selections.
                            </p>
                        </div>
                    </div>
                </div>
