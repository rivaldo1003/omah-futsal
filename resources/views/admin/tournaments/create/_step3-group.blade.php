<!-- STEP 3 CONTENT - GROUP KNOCKOUT -->
                    <div class="step-content step3-group" id="step3Group" 
                         style="display: {{ ($currentStep == 3 && (old('type', $tournamentData['type'] ?? 'group_knockout') == 'group_knockout')) ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bi bi-grid-3x3"></i> Pembagian Grup</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info mb-4">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Drag and drop tim between groups.</strong> Drag tim from "Tim Tersedia" to any group, or between groups.
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="groups_count_assign" class="form-label">
                                                <i class="bi bi-hash"></i>
                                                Jumlah Grup
                                            </label>
                                            <input type="number" class="form-control" id="groups_count_assign"
                                                name="groups_count_assign"
                                                value="{{ old('groups_count', $tournamentData['groups_count'] ?? 2) }}"
                                                min="1" max="8">
                                            <div class="form-text">Berapa jumlah grup yang diinginkan?</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-end h-100">
                                            <button type="button" class="btn btn-outline-primary me-2"
                                                onclick="autoDistribute()">
                                                <i class="bi bi-shuffle"></i> Bagi otomatis
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="resetGroups()">
                                                <i class="bi bi-arrow-clockwise"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0"><i class="bi bi-people"></i> Tim Tersedia</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="drag-info">
                                            <i class="bi bi-arrow-down-up"></i> Seret tim dari sini ke grup di bawah
                                        </div>
                                        <div class="row" id="availableTeamsContainer">
                                            @if(!empty($tournamentData['teams']))
                                                @foreach($teams->whereIn('id', $tournamentData['teams']) as $team)
                                                    @php
                                                        $logoUrl = $team->logo ? Storage::url($team->logo) : null;
                                                    @endphp
                                                    <div class="col-md-3 mb-3 team-card-container"
                                                        id="team-container-{{ $team->id }}">
                                                        <div class="team-card draggable-item" data-team-id="{{ $team->id }}"
                                                            draggable="true">
                                                            <div class="team-logo-placeholder">
                                                                @if($logoUrl)
                                                                    <img src="{{ $logoUrl }}" alt="{{ $team->name }}"
                                                                        style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                                                @else
                                                                    {{ substr($team->name, 0, 2) }}
                                                                @endif
                                                            </div>
                                                            <h6 class="mb-1">{{ $team->name }}</h6>
                                                            @if($team->coach_name)
                                                                <small class="text-muted">Coach: {{ $team->coach_name }}</small>
                                                            @endif
                                                            <div class="mt-2">
                                                                <span class="badge bg-light text-dark"
                                                                    id="team-group-{{ $team->id }}">Belum dibagi</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="groupsContainer">
                                    <!-- Groups will be generated by JavaScript -->
                                </div>

                                <input type="hidden" name="group_assignments" id="groupAssignments"
                                    value="{{ json_encode($tournamentData['group_assignments'] ?? []) }}">

                                <div class="alert alert-light mt-4">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <i class="bi bi-check-circle text-success"></i>
                                            <span id="assignedCount">0</span> tim dibagi
                                        </div>
                                        <div>
                                            <i class="bi bi-clock text-warning"></i>
                                            <span
                                                id="unassignedCount">{{ count($tournamentData['teams'] ?? []) }}</span>
                                            tim belum dibagi
                                        </div>
                                        <div>
                                            <i class="bi bi-grid-3x3 text-primary"></i>
                                            <span id="groupsCount">0</span> grup dibuat
                                        </div>
                                    </div>
                                </div>

                                <div class="info-box">
                                    <i class="bi bi-lightbulb"></i>
                                    <p>
                                        <strong>How to assign tim:</strong><br>
                                        1. Drag tim from "Tim Tersedia" to any group<br>
                                        2. Drag tim between groups to move them<br>
                                        3. Click "Bagi otomatis" for random distribution<br>
                                        4. Each group should have similar number of tim
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
