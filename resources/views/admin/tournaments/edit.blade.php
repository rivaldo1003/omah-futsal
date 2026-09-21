@extends('layouts.admin')

@section('title', 'Edit Tournament')



@section('styles')
    <style>
        :root {
            --primary: var(--text-primary);
            --secondary: var(--text-secondary);
            --bg-main: var(--surface);
            --bg-card: var(--bg);
            --border-color: var(--border);
            --shadow-sm: none;
            --shadow-md: none;
            --transition: all 0.15s ease;
            --success-light: #F0F9F4;
            --success-dark: #1E7A46;
            --warning-light: #FDF6EC;
            --warning-dark: #B45309;
            --danger-light: #FDF2F3;
            --danger-dark: #c01c28;
        }

        /* Page header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
            margin: 0 0 4px;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            margin: 0;
        }

        .btn-back {
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text-primary);
            border-radius: 6px;
            height: 40px;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-back:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Main Card */
        .main-card {
            background: var(--bg-card);
            border-radius: 0.75rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .main-card .card-header {
            background-color: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            color: var(--primary);
            font-weight: 600;
        }

        .main-card .card-body {
            padding: 1.5rem;
        }

        /* Form Styles */
        .form-label {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 10px 15px;
            color: var(--primary);
            transition: var(--transition);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-text {
            color: var(--secondary);
            font-size: 0.875rem;
        }

        /* Checkbox Styling */
        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--accent);
            border-color: var(--accent);
            padding: 10px 25px;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-outline-secondary {
            border: 1px solid var(--border-color);
            color: var(--secondary);
            padding: 10px 25px;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-outline-secondary:hover {
            background-color: var(--bg-main);
            border-color: var(--secondary);
            color: var(--primary);
        }

        /* Tie-breakers */
        .tie-preset {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .btn-preset {
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text-secondary);
            border-radius: 6px;
            padding: 4px 12px;
            font-size: 13px;
            font-weight: 500;
        }

        .btn-preset:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-preset.active {
            border-color: var(--accent);
            color: var(--accent);
            background: rgba(26, 95, 180, 0.05);
        }

        /* Team Selection */
        .team-selection-container {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 15px;
            background-color: var(--bg-main);
        }

        .team-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .team-item:last-child {
            border-bottom: none;
        }

        .team-item:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .team-checkbox {
            margin-right: 12px;
        }

        .team-info {
            flex-grow: 1;
        }

        .team-name {
            font-weight: 500;
            color: var(--primary);
            margin-bottom: 2px;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 0.5rem;
            padding: 15px 20px;
            margin-bottom: 25px;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .alert i {
            font-size: 1.3rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-success {
            background-color: var(--success-light);
            border-left: 4px solid var(--success-dark);
            color: var(--success-dark);
        }

        .alert-danger {
            background-color: var(--danger-light);
            border-left: 4px solid var(--danger-dark);
            color: var(--danger-dark);
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .main-card .card-body {
                padding: 1rem;
            }

            .btn-primary,
            .btn-outline-secondary {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Turnamen</h1>
            <p class="page-subtitle">Perbarui detail turnamen {{ $tournament->name }}</p>
        </div>
        <a href="{{ route('admin.tournaments.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i>
            <div>
                <strong>Sukses!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            <div>
                <strong>Perbaiki kesalahan berikut:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="main-card">
        <div class="card-header">
            <h5>Informasi turnamen</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.tournaments.update', $tournament) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-12 mb-4">
                        <h6 class="mb-3" style="color: var(--primary); font-weight: 600;">
                            Informasi Dasar
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Turnamen *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $tournament->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="slug" class="form-label">Slug URL</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                                    name="slug" value="{{ old('slug', $tournament->slug) }}">
                                <div class="form-text">Kosongkan untuk generate otomatis dari nama</div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                    name="description"
                                    rows="3">{{ old('description', $tournament->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Dates and Lokasi -->
                    <div class="col-md-12 mb-4">
                        <h6 class="mb-3" style="color: var(--primary); font-weight: 600;">
                            Tanggal & Lokasi
                        </h6>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai *</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                    id="start_date" name="start_date"
                                    value="{{ old('start_date', $tournament->start_date->format('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">Tanggal Selesai *</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                    id="end_date" name="end_date"
                                    value="{{ old('end_date', $tournament->end_date->format('Y-m-d')) }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="location" name="location" value="{{ old('location', $tournament->location) }}">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="organizer" class="form-label">Penyelenggara</label>
                                <input type="text" class="form-control @error('organizer') is-invalid @enderror"
                                    id="organizer" name="organizer" value="{{ old('organizer', $tournament->organizer) }}">
                                @error('organizer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tournament Type and Status -->
                    <div class="col-md-12 mb-4">
                        <h6 class="mb-3" style="color: var(--primary); font-weight: 600;">
                            Tipe & Status
                        </h6>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Tipe Turnamen *</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type"
                                    required>
                                    <option value="group_knockout" {{ old('type', $tournament->type) == 'group_knockout' ? 'selected' : '' }}>Group + Knockout</option>
                                    <option value="league" {{ old('type', $tournament->type) == 'league' ? 'selected' : '' }}>
                                        League</option>
                                    <option value="knockout" {{ old('type', $tournament->type) == 'knockout' ? 'selected' : '' }}>Knockout</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                    required>
                                    <option value="upcoming" {{ old('status', $tournament->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="ongoing" {{ old('status', $tournament->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ old('status', $tournament->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $tournament->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Group Settings (visible only for group_knockout) -->
                            <div class="col-md-4 mb-3 group-settings"
                                style="{{ $tournament->type != 'group_knockout' ? 'display: none;' : '' }}">
                                <label for="groups_count" class="form-label">Jumlah Grup</label>
                                <input type="number" class="form-control @error('groups_count') is-invalid @enderror"
                                    id="groups_count" name="groups_count" min="1" max="8"
                                    value="{{ old('groups_count', $tournament->groups_count) }}">
                                @error('groups_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Team Selection -->
                    <div class="col-md-12 mb-4">
                        <h6 class="mb-3" style="color: var(--primary); font-weight: 600;">
                            Pilih Tim
                        </h6>

                        <div class="team-selection-container">
                            @if($teams->count() > 0)
                                @foreach($teams as $team)
                                    @php
                                        // Get current group assignment from tournament's teams relationship
                                        $currentGroup = null;
                                        $teamInTournament = $tournament->teams->firstWhere('id', $team->id);
                                        if ($teamInTournament && isset($teamInTournament->pivot->group_name)) {
                                            $currentGroup = $teamInTournament->pivot->group_name;
                                        }
                                    @endphp
                                    <div class="team-item" data-team-id="{{ $team->id }}">
                                        <div class="team-checkbox">
                                            <input type="checkbox" class="form-check-input team-checkbox-input" 
                                                id="team_{{ $team->id }}" name="teams[]"
                                                value="{{ $team->id }}" {{ in_array($team->id, $selectedTeams) ? 'checked' : '' }}>
                                        </div>
                                        <div class="team-info flex-grow-1">
                                            <div class="team-name">{{ $team->name }}</div>
                                            @if($team->coach_name)
                                                <small class="text-muted">Coach: {{ $team->coach_name }}</small>
                                            @endif
                                        </div>
                                        <!-- Group Assignment Dropdown (only for group_knockout) -->
                                        <div class="group-assignment-container" style="display: {{ $tournament->type == 'group_knockout' ? 'block' : 'none' }};">
                                            <select class="form-select form-select-sm group-select" 
                                                name="group_assignments[{{ $team->id }}]"
                                                style="width: auto; min-width: 120px;"
                                                {{ !in_array($team->id, $selectedTeams) ? 'disabled' : '' }}>
                                                <option value="">Pilih Grup</option>
                                                @for($i = 1; $i <= ($tournament->groups_count ?? 2); $i++)
                                                    @php $groupLetter = chr(64 + $i); @endphp
                                                    <option value="{{ $groupLetter }}"
                                                        {{ $currentGroup == $groupLetter ? 'selected' : '' }}>
                                                        Group {{ $groupLetter }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @if($currentGroup && in_array($team->id, $selectedTeams))
                                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                                    Saat ini di Group {{ $currentGroup }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center py-4">Belum ada tim tersedia. Buat tim terlebih dahulu.</p>
                            @endif
                        </div>
                        @error('teams')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                        @error('group_assignments')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Standings & Tie-breakers Rules -->
                    <div class="col-md-12 mb-4 tie-breakers-section" style="{{ $tournament->type == 'group_knockout' ? '' : 'display: none;' }}">
                        <h6 class="mb-1" style="color: var(--primary); font-weight: 600;">
                            Penentuan peringkat (tie-breakers)
                        </h6>
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
                                    'penalty' => 'Adu penalti'
                                ];
                                
                                // Get current rules from settings - normalize to a list of keys
                                $currentRules = $settings['tie_breakers'] ?? null;
                                if (empty($currentRules)) {
                                    $currentRules = array_keys($tieBreakerOptions);
                                } elseif (is_array($currentRules)) {
                                    // Support both formats: list of keys ["points","goal_difference"]
                                    // or assoc map ["points" => "Poin ...", ...]
                                    $currentRules = array_is_list($currentRules) ? $currentRules : array_keys($currentRules);
                                } else {
                                    $currentRules = [(string) $currentRules];
                                }
                            @endphp
                            @foreach($currentRules as $index => $rule)
                                @php
                                    // Data sudah dinormalisasi menjadi list of keys
                                    $ruleKey = is_string($rule) ? $rule : (is_array($rule) && isset($rule['key']) ? $rule['key'] : 'points');
                                @endphp
                                <div class="input-group mb-2 tie-breaker-item" draggable="true" style="cursor: move; transition: all 0.2s;">
                                    <span class="input-group-text bg-light text-secondary fw-bold drag-handle" style="cursor: grab;">
                                        <i class="bi bi-grip-vertical"></i> {{ $index + 1 }}
                                    </span>
                                    <select class="form-select tie-breaker-select" name="tie_breakers[{{ $index }}][key]" required>
                                        @foreach($tieBreakerOptions as $key => $label)
                                            <option value="{{ $key }}" {{ $ruleKey == $key ? 'selected' : '' }}>{{ $label }}</option>
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

                    <!-- Tie-breakers for other tournament types (simpler version) -->
                    <div class="col-md-12 mb-4 tie-breakers-section-other" style="{{ $tournament->type != 'group_knockout' ? '' : 'display: none;' }}">
                        <h6 class="mb-1" style="color: var(--primary); font-weight: 600;">
                            Penentuan peringkat (tie-breakers)
                        </h6>
                        <small class="text-secondary d-block mb-3">Urutan menentukan prioritas klasemen saat poin tim sama.</small>
                        <div id="tie-breakers-container-other">
                            @php
                                $tieBreakerOptionsSimple = [
                                    'points' => 'Poin',
                                    'goal_difference' => 'Selisih gol',
                                    'goals_scored' => 'Gol mencetak',
                                    'fair_play' => 'Fair play',
                                    'penalty' => 'Adu penalti'
                                ];
                                
                                // Get current rules from settings - normalize to a list of keys
                                $currentRulesOther = $settings['tie_breakers'] ?? null;
                                if (empty($currentRulesOther)) {
                                    $currentRulesOther = array_keys($tieBreakerOptionsSimple);
                                } elseif (is_array($currentRulesOther)) {
                                    $currentRulesOther = array_is_list($currentRulesOther) ? $currentRulesOther : array_keys($currentRulesOther);
                                } else {
                                    $currentRulesOther = [(string) $currentRulesOther];
                                }
                            @endphp
                            @foreach($currentRulesOther as $index => $rule)
                                @php
                                    // Data sudah dinormalisasi menjadi list of keys
                                    $ruleKeyOther = is_string($rule) ? $rule : (is_array($rule) && isset($rule['key']) ? $rule['key'] : 'points');
                                @endphp
                                <div class="input-group mb-2 tie-breaker-item-other">
                                    <span class="input-group-text bg-light text-secondary fw-bold">{{ $index + 1 }}</span>
                                    <select class="form-select" name="tie_breakers_other[{{ $index }}][key]" required>
                                        @foreach($tieBreakerOptionsSimple as $key => $label)
                                            <option value="{{ $key }}" {{ $ruleKeyOther == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-danger remove-rule-other">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-rule-other">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Aturan
                        </button>

                    </div>

                    <!-- Match Settings -->
                    <div class="col-md-12 mb-4">
                        <h6 class="mb-3" style="color: var(--primary); font-weight: 600;">
                            Durasi & Waktu Pertandingan
                        </h6>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="match_duration" class="form-label">Durasi waktu normal (menit) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('match_duration') is-invalid @enderror" 
                                    id="match_duration" name="match_duration" min="10" max="120" 
                                    value="{{ old('match_duration', $settings['match_duration'] ?? 40) }}" required>
                                <div class="form-text">Total waktu efektif babak normal. Futsal standar: 2 × 20 = 40 menit</div>
                                @error('match_duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="half_time" class="form-label">Istirahat antar babak (menit) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('half_time') is-invalid @enderror" 
                                    id="half_time" name="half_time" min="5" max="30" 
                                    value="{{ old('half_time', $settings['half_time'] ?? 10) }}" required>
                                <div class="form-text">Jeda antara babak 1 dan babak 2</div>
                                @error('half_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="extra_time" class="form-label">Durasi per babak extra time (menit)</label>
                                <input type="number" class="form-control @error('extra_time') is-invalid @enderror" 
                                    id="extra_time" name="extra_time" min="0" max="30" 
                                    value="{{ old('extra_time', $settings['extra_time'] ?? 10) }}">
                                <div class="form-text">Dimainkan jika skor seri di knockout</div>
                                @error('extra_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Points System -->
                    <div class="col-md-12 mb-4">
                        <h6 class="mb-3" style="color: var(--primary); font-weight: 600;">
                            Sistem Poin
                        </h6>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="points_win" class="form-label">Menang <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('points_win') is-invalid @enderror" 
                                    id="points_win" name="points_win" min="1" max="10" 
                                    value="{{ old('points_win', $settings['points_win'] ?? 3) }}" required>
                                @error('points_win')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="points_draw" class="form-label">Seri <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('points_draw') is-invalid @enderror" 
                                    id="points_draw" name="points_draw" min="0" max="5" 
                                    value="{{ old('points_draw', $settings['points_draw'] ?? 1) }}" required>
                                @error('points_draw')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="points_loss" class="form-label">Kalah <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('points_loss') is-invalid @enderror" 
                                    id="points_loss" name="points_loss" min="0" max="5" 
                                    value="{{ old('points_loss', $settings['points_loss'] ?? 0) }}" required>
                                @error('points_loss')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="points_no_show" class="form-label">Poin Tidak Hadir</label>
                                <input type="number" class="form-control @error('points_no_show') is-invalid @enderror" 
                                    id="points_no_show" name="points_no_show" min="-10" max="0" 
                                    value="{{ old('points_no_show', $settings['points_no_show'] ?? -1) }}">
                                @error('points_no_show')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Match Rules & Options -->
                    <div class="col-md-12 mb-4">
                        <h6 class="mb-3" style="color: var(--primary); font-weight: 600;">
                            Aturan & Opsi Pertandingan
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="max_substitutes" class="form-label">Maksimal pemain cadangan <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('max_substitutes') is-invalid @enderror" 
                                    id="max_substitutes" name="max_substitutes" min="0" max="20" 
                                    value="{{ old('max_substitutes', $settings['max_substitutes'] ?? 5) }}" required>
                                @error('max_substitutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="yellow_card_suspension" class="form-label">Batas Kartu Kuning</label>
                                <input type="number" class="form-control @error('yellow_card_suspension') is-invalid @enderror" 
                                    id="yellow_card_suspension" name="yellow_card_suspension" min="1" max="10" 
                                    value="{{ old('yellow_card_suspension', $settings['yellow_card_suspension'] ?? 3) }}">
                                @error('yellow_card_suspension')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="allow_draw" name="allow_draw" value="1" 
                                        {{ old('allow_draw', $settings['allow_draw'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allow_draw">
                                        <strong>Izinkan hasil seri di group stage</strong>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="extra_time_enabled" name="extra_time_enabled" value="1" 
                                        {{ old('extra_time_enabled', $settings['extra_time_enabled'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="extra_time_enabled">
                                        <strong>Extra time untuk pertandingan knockout</strong>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="penalty_shootout" name="penalty_shootout" value="1" 
                                        {{ old('penalty_shootout', $settings['penalty_shootout'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="penalty_shootout">
                                        <strong>Adu penalti setelah extra time</strong>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="var_enabled" name="var_enabled" value="1" 
                                        {{ old('var_enabled', $settings['var_enabled'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="var_enabled">
                                        <strong>Aktifkan VAR (Video Assistant Referee)</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <a href="{{ route('admin.tournaments.show', $tournament) }}" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-eye me-2"></i>Lihat Detail
                        </a>
                        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3" style="color: var(--primary); font-weight: 500;">Menyimpan Turnamen...</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-generate slug from name
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            nameInput.addEventListener('blur', function () {
                if (!slugInput.value) {
                    const slug = nameInput.value
                        .toLowerCase()
                        .replace(/[^\w\s]/gi, '')
                        .replace(/\s+/g, '-');
                    slugInput.value = slug;
                }
            });

            // Show/hide group settings based on tournament type
            const typeSelect = document.getElementById('type');
            const groupSettings = document.querySelector('.group-settings');
            const groupAssignmentContainers = document.querySelectorAll('.group-assignment-container');

            function toggleGroupSettings() {
                if (typeSelect.value === 'group_knockout') {
                    groupSettings.style.display = 'block';
                    groupAssignmentContainers.forEach(container => {
                        container.style.display = 'block';
                    });
                } else {
                    groupSettings.style.display = 'none';
                    groupAssignmentContainers.forEach(container => {
                        container.style.display = 'none';
                    });
                }
                updateGroupSelectState();
            }

            typeSelect.addEventListener('change', toggleGroupSettings);

            // Update group dropdown options when groups_count changes
            const groupsCountInput = document.getElementById('groups_count');
            
            function updateGroupDropdowns() {
                if (typeSelect.value !== 'group_knockout') return;
                
                const groupsCount = parseInt(groupsCountInput.value) || 2;
                const groupSelects = document.querySelectorAll('.group-select');
                
                groupSelects.forEach(select => {
                    const currentValue = select.value;
                    const isChecked = select.closest('.team-item').querySelector('.team-checkbox-input').checked;
                    
                    // Clear existing options except the first one
                    select.innerHTML = '<option value="">Pilih Grup</option>';
                    
                    // Add new group options
                    for (let i = 1; i <= Math.min(groupsCount, 26); i++) {
                        const groupLetter = String.fromCharCode(64 + i); // A, B, C, ...
                        const option = document.createElement('option');
                        option.value = groupLetter;
                        option.textContent = `Group ${groupLetter}`;
                        if (currentValue === groupLetter) {
                            option.selected = true;
                        }
                        select.appendChild(option);
                    }
                    
                    // Disable if team is not checked
                    if (!isChecked) {
                        select.disabled = true;
                    }
                });
            }

            if (groupsCountInput) {
                groupsCountInput.addEventListener('change', updateGroupDropdowns);
                groupsCountInput.addEventListener('input', updateGroupDropdowns);
            }

            // Show loading overlay on form submit
            const form = document.querySelector('form');
            const loadingOverlay = document.getElementById('loadingOverlay');

            form.addEventListener('submit', function () {                
                loadingOverlay.style.display = 'flex';
            });

            // Validate at least 2 teams selected
            form.addEventListener('submit', function (e) {
                const checkedTeams = form.querySelectorAll('input[name="teams[]"]:checked').length;
                if (checkedTeams < 2) {
                    e.preventDefault();
                    alert('Pilih minimal 2 tim untuk turnamen ini.');
                    loadingOverlay.style.display = 'none';
                }
            });

            // Team selection and group assignment
            const teamCheckboxes = document.querySelectorAll('.team-checkbox-input');
            const groupSelects = document.querySelectorAll('.group-select');

            function updateGroupSelectState() {
                teamCheckboxes.forEach(checkbox => {
                    const teamItem = checkbox.closest('.team-item');
                    const groupSelect = teamItem.querySelector('.group-select');
                    
                    if (checkbox.checked) {
                        groupSelect.disabled = false;
                    } else {
                        groupSelect.disabled = true;
                        groupSelect.value = ''; // Reset group selection
                    }
                });
            }

            // Initialize group select states
            updateGroupSelectState();

            // Add event listeners to checkboxes
            teamCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateGroupSelectState);
            });

            // ==============================================
            // TIE-BREAKERS MANAGEMENT
            // ==============================================
            
            // Group Knockout Tie-breakers (with drag-and-drop)
            const tieBreakersContainer = document.getElementById('tie-breakers-container');
            const addRuleBtn = document.getElementById('add-rule');

            if (addRuleBtn) {
                addRuleBtn.addEventListener('click', function () {
                    const itemCount = tieBreakersContainer.querySelectorAll('.tie-breaker-item').length;
                    const newRule = document.createElement('div');
                    newRule.className = 'input-group mb-2 tie-breaker-item';
                    newRule.draggable = true;
                    newRule.style.cursor = 'move';
                    newRule.style.transition = 'all 0.2s';
                    newRule.innerHTML = `
                            <span class="input-group-text bg-light text-secondary fw-bold drag-handle" style="cursor: grab;">
                                <i class="bi bi-grip-vertical"></i> ${itemCount + 1}
                            </span>
                            <select class="form-select tie-breaker-select" name="tie_breakers[${itemCount}][key]" required>
                                <option value="">Pilih aturan</option>
                                <option value="points">Poin</option>
                                <option value="head_to_head">Head-to-head</option>
                                <option value="goal_difference">Selisih gol</option>
                                <option value="goals_scored">Gol mencetak</option>
                                <option value="fair_play">Fair play</option>
                                <option value="penalty">Adu penalti</option>
                            </select>
                            <button type="button" class="btn btn-outline-danger remove-rule">
                                <i class="bi bi-trash"></i>
                            </button>
                        `;
                    tieBreakersContainer.appendChild(newRule);
                    attachRemoveEvent(newRule.querySelector('.remove-rule'), 'group');
                    attachDragEvents(newRule);
                    
                    // Re-index all tie-breaker items after adding new one
                    reindexTieBreakerItems();
                });
            }
            
            // ==============================================
            // TIE-BREAKER PRESET TEMPLATES
            // ==============================================
            const tieBreakerLabels = {
                'points': 'Poin',
                'head_to_head': 'Head-to-head',
                'goal_difference': 'Selisih gol',
                'goals_scored': 'Gol mencetak',
                'fair_play': 'Fair play',
                'penalty': 'Adu penalti'
            };

            const tieBreakerPresets = {
                'gd_first':   ['goal_difference', 'head_to_head', 'goals_scored', 'fair_play', 'penalty'],
                'h2h_first':  ['head_to_head', 'goal_difference', 'goals_scored', 'fair_play', 'penalty'],
                'fifa':       ['points', 'goal_difference', 'goals_scored', 'head_to_head', 'fair_play', 'penalty'],
                'goals_first': ['goals_scored', 'head_to_head', 'goal_difference', 'fair_play', 'penalty']
            };

            // Simpan urutan awal (dari server) untuk tombol Reset
            const initialTieBreakerKeys = Array.from(
                tieBreakersContainer.querySelectorAll('.tie-breaker-select')
            ).map(select => select.value);

            function buildTieBreakerItem(key, index) {
                const item = document.createElement('div');
                item.className = 'input-group mb-2 tie-breaker-item';
                item.draggable = true;
                item.style.cursor = 'move';
                item.style.transition = 'all 0.2s';
                item.innerHTML = `
                        <span class="input-group-text bg-light text-secondary fw-bold drag-handle" style="cursor: grab;">
                            <i class="bi bi-grip-vertical"></i> ${index + 1}
                        </span>
                        <select class="form-select tie-breaker-select" name="tie_breakers[${index}][key]" required>
                            <option value="">Pilih aturan</option>
                            ${Object.entries(tieBreakerLabels).map(([k, label]) =>
                                `<option value="${k}" ${k === key ? 'selected' : ''}>${label}</option>`
                            ).join('')}
                        </select>
                        <button type="button" class="btn btn-outline-danger remove-rule">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                return item;
            }

            function applyTieBreakerKeys(keys) {
                tieBreakersContainer.innerHTML = '';
                keys.forEach((key, index) => {
                    const item = buildTieBreakerItem(key, index);
                    tieBreakersContainer.appendChild(item);
                    attachDragEvents(item);
                    attachRemoveEvent(item.querySelector('.remove-rule'), 'group');
                });
            }

            document.querySelectorAll('.preset-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const presetKey = this.dataset.preset;
                    const keys = tieBreakerPresets[presetKey];
                    if (!keys) return;

                    applyTieBreakerKeys(keys);

                    // Highlight tombol yang dipilih
                    document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            const resetTieBreakersBtn = document.getElementById('resetTieBreakers');
            if (resetTieBreakersBtn) {
                resetTieBreakersBtn.addEventListener('click', function () {
                    applyTieBreakerKeys(initialTieBreakerKeys);
                    document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
                });
            }

            // Function to re-index tie-breaker items
            function reindexTieBreakerItems() {
                const items = tieBreakersContainer.querySelectorAll('.tie-breaker-item');
                items.forEach((item, index) => {
                    // Update the number in the drag handle
                    const numberSpan = item.querySelector('.drag-handle');
                    if (numberSpan) {
                        numberSpan.innerHTML = `<i class="bi bi-grip-vertical"></i> ${index + 1}`;
                    }
                    
                    // Update the select name attribute
                    const select = item.querySelector('.tie-breaker-select');
                    if (select) {
                        select.name = `tie_breakers[${index}][key]`;
                    }
                });
            }

            // Other Tournament Types Tie-breakers (without drag-and-drop)
            const tieBreakersContainerOther = document.getElementById('tie-breakers-container-other');
            const addRuleBtnOther = document.getElementById('add-rule-other');

            if (addRuleBtnOther) {
                addRuleBtnOther.addEventListener('click', function () {
                    const itemCount = tieBreakersContainerOther.querySelectorAll('.tie-breaker-item-other').length;
                    const newRule = document.createElement('div');
                    newRule.className = 'input-group mb-2 tie-breaker-item-other';
                    newRule.innerHTML = `
                            <span class="input-group-text bg-light text-secondary fw-bold">${itemCount + 1}</span>
                            <select class="form-select" name="tie_breakers_other[${itemCount}][key]" required>
                                <option value="">Pilih aturan</option>
                                <option value="points">Poin</option>
                                <option value="goal_difference">Selisih gol</option>
                                <option value="goals_scored">Gol mencetak</option>
                                <option value="fair_play">Fair play</option>
                                <option value="penalty">Adu penalti</option>
                            </select>
                            <button type="button" class="btn btn-outline-danger remove-rule-other">
                                <i class="bi bi-trash"></i>
                            </button>
                        `;
                    tieBreakersContainerOther.appendChild(newRule);
                    attachRemoveEvent(newRule.querySelector('.remove-rule-other'), 'other');
                });
            }

            function attachRemoveEvent(btn, type) {
                btn.addEventListener('click', function () {
                    const item = this.closest(type === 'group' ? '.tie-breaker-item' : '.tie-breaker-item-other');
                    item.remove();
                    // Re-index numbering and select names
                    reindexTieBreakers(type);
                    if (type === 'group') {
                        reindexTieBreakerItems();
                    }
                });
            }

            function reindexTieBreakers(type) {
                const container = type === 'group' ? tieBreakersContainer : tieBreakersContainerOther;
                const items = container.querySelectorAll(type === 'group' ? '.tie-breaker-item' : '.tie-breaker-item-other');
                items.forEach((item, index) => {
                    const numberSpan = item.querySelector(type === 'group' ? '.drag-handle' : '.input-group-text');
                    if (numberSpan) {
                        if (type === 'group') {
                            numberSpan.innerHTML = `<i class="bi bi-grip-vertical"></i> ${index + 1}`;
                        } else {
                            numberSpan.textContent = index + 1;
                        }
                    }
                });
            }

            // Drag and Drop functionality for group_knockout (Desktop + Mobile)
            let draggedItem = null;
            let draggedIndex = -1;
            let touchStartY = 0;
            let touchCurrentItem = null;

            function attachDragEvents(item) {
                // Desktop drag events
                item.addEventListener('dragstart', function (e) {
                    draggedItem = this;
                    draggedIndex = Array.from(tieBreakersContainer.querySelectorAll('.tie-breaker-item')).indexOf(this);
                    this.style.opacity = '0.5';
                    this.style.transform = 'scale(1.02)';
                    e.dataTransfer.effectAllowed = 'move';
                });

                item.addEventListener('dragend', function () {
                    this.style.opacity = '1';
                    this.style.transform = 'scale(1)';
                    draggedItem = null;
                    draggedIndex = -1;
                    
                    // Remove all drag-over classes
                    tieBreakersContainer.querySelectorAll('.tie-breaker-item').forEach(item => {
                        item.style.borderTop = '';
                        item.style.borderBottom = '';
                    });
                });

                item.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    
                    if (draggedItem && draggedItem !== this) {
                        const bounding = this.getBoundingClientRect();
                        const offset = bounding.y + (bounding.height / 2);
                        
                        if (e.clientY - offset > 0) {
                            this.style.borderBottom = '2px solid var(--accent)';
                            this.style.borderTop = '';
                        } else {
                            this.style.borderTop = '2px solid var(--accent)';
                            this.style.borderBottom = '';
                        }
                    }
                });

                item.addEventListener('dragleave', function () {
                    this.style.borderTop = '';
                    this.style.borderBottom = '';
                });

                item.addEventListener('drop', function (e) {
                    e.preventDefault();
                    this.style.borderTop = '';
                    this.style.borderBottom = '';
                    
                    if (draggedItem && draggedItem !== this) {
                        const items = Array.from(tieBreakersContainer.querySelectorAll('.tie-breaker-item'));
                        const targetIndex = items.indexOf(this);
                        
                        if (draggedIndex !== -1 && targetIndex !== -1 && draggedIndex !== targetIndex) {
                            // Determine insertion point based on mouse position
                            const bounding = this.getBoundingClientRect();
                            const offset = bounding.y + (bounding.height / 2);
                            const insertBefore = e.clientY - offset < 0;
                            
                            if (insertBefore) {
                                this.parentNode.insertBefore(draggedItem, this);
                            } else {
                                this.parentNode.insertBefore(draggedItem, this.nextSibling);
                            }
                            
                            // Re-index numbering and select names after drag
                            reindexTieBreakers('group');
                            reindexTieBreakerItems();
                        }
                    }
                });

                // Mobile touch events
                item.addEventListener('touchstart', function (e) {
                    touchCurrentItem = this;
                    touchStartY = e.touches[0].clientY;
                    this.style.opacity = '0.5';
                    this.style.transform = 'scale(1.02)';
                }, { passive: true });

                item.addEventListener('touchmove', function (e) {
                    if (!touchCurrentItem) return;
                    
                    e.preventDefault();
                    const touchY = e.touches[0].clientY;
                    const elementAtPoint = document.elementFromPoint(
                        e.touches[0].clientX,
                        touchY
                    );
                    
                    // Find the closest tie-breaker item
                    const targetItem = elementAtPoint ? elementAtPoint.closest('.tie-breaker-item') : null;
                    
                    // Remove all borders
                    tieBreakersContainer.querySelectorAll('.tie-breaker-item').forEach(item => {
                        item.style.borderTop = '';
                        item.style.borderBottom = '';
                    });
                    
                    // Add border to target
                    if (targetItem && targetItem !== touchCurrentItem) {
                        const bounding = targetItem.getBoundingClientRect();
                        const offset = bounding.y + (bounding.height / 2);
                        
                        if (touchY - offset > 0) {
                            targetItem.style.borderBottom = '2px solid var(--accent)';
                        } else {
                            targetItem.style.borderTop = '2px solid var(--accent)';
                        }
                    }
                }, { passive: false });

                item.addEventListener('touchend', function (e) {
                    if (!touchCurrentItem) return;
                    
                    const touchY = e.changedTouches[0].clientY;
                    const elementAtPoint = document.elementFromPoint(
                        e.changedTouches[0].clientX,
                        touchY
                    );
                    
                    const targetItem = elementAtPoint ? elementAtPoint.closest('.tie-breaker-item') : null;
                    
                    if (targetItem && targetItem !== touchCurrentItem) {
                        const items = Array.from(tieBreakersContainer.querySelectorAll('.tie-breaker-item'));
                        const currentIndex = items.indexOf(touchCurrentItem);
                        const targetIndex = items.indexOf(targetItem);
                        
                        if (currentIndex !== -1 && targetIndex !== -1 && currentIndex !== targetIndex) {
                            const bounding = targetItem.getBoundingClientRect();
                            const offset = bounding.y + (bounding.height / 2);
                            const insertBefore = touchY - offset < 0;
                            
                            if (insertBefore) {
                                targetItem.parentNode.insertBefore(touchCurrentItem, targetItem);
                            } else {
                                targetItem.parentNode.insertBefore(touchCurrentItem, targetItem.nextSibling);
                            }
                            
                            // Re-index numbering and select names after drag
                            reindexTieBreakers('group');
                            reindexTieBreakerItems();
                        }
                    }
                    
                    // Cleanup
                    touchCurrentItem.style.opacity = '1';
                    touchCurrentItem.style.transform = 'scale(1)';
                    tieBreakersContainer.querySelectorAll('.tie-breaker-item').forEach(item => {
                        item.style.borderTop = '';
                        item.style.borderBottom = '';
                    });
                    
                    touchCurrentItem = null;
                });
            }

            // Attach drag events to existing tie-breaker items (group_knockout)
            if (tieBreakersContainer) {
                tieBreakersContainer.querySelectorAll('.tie-breaker-item').forEach(attachDragEvents);
                tieBreakersContainer.querySelectorAll('.remove-rule').forEach(btn => attachRemoveEvent(btn, 'group'));
            }

            // Attach remove events to other tournament types tie-breakers
            if (tieBreakersContainerOther) {
                tieBreakersContainerOther.querySelectorAll('.remove-rule-other').forEach(btn => attachRemoveEvent(btn, 'other'));
            }

            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
@endsection