@extends('layouts.admin')

@section('title', 'Buat Pertandingan')

@section('styles')
    <style>
        .admin-match-create {
            --match-ink: #111111;
            --match-charcoal: #39393b;
            --match-muted: #707072;
            --match-cloud: #f5f5f5;
            --match-border: #e5e5e5;
            --match-border-strong: #cacacb;
            --match-danger: #b42318;
            max-width: 1440px;
            margin-inline: auto;
            color: var(--match-ink);
        }

        .admin-match-create .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 28px;
        }

        .admin-match-create .page-header h1 {
            margin: 0 0 8px;
            color: var(--match-ink);
            font-size: clamp(26px, 3vw, 32px);
            font-weight: 600;
            letter-spacing: -0.035em;
            line-height: 1.15;
        }

        .admin-match-create .page-subtitle {
            margin: 0;
            color: var(--match-muted);
            font-size: 15px;
            line-height: 1.5;
        }

        .admin-match-create .btn-back,
        .admin-match-create .match-actions .btn,
        .admin-match-create .empty-action {
            min-height: 48px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: 0;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            text-decoration: none;
            transition: background-color .16s ease, color .16s ease;
        }

        .admin-match-create .btn-back,
        .admin-match-create .match-actions .btn-outline-secondary {
            background: var(--match-cloud);
            color: var(--match-ink);
        }

        .admin-match-create .btn-back:hover,
        .admin-match-create .match-actions .btn-outline-secondary:hover {
            background: #eaeaec;
            color: var(--match-ink);
        }

        .admin-match-create .form-card {
            overflow: hidden;
            border: 1px solid var(--match-border);
            border-radius: 8px;
            background: #ffffff;
            box-shadow: none;
        }

        .admin-match-create .form-card .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--match-border);
            background: #ffffff;
        }

        .admin-match-create .form-card .card-header h5 {
            margin: 0;
            color: var(--match-ink);
            font-size: 16px;
            font-weight: 600;
            line-height: 1.5;
        }

        .admin-match-create .form-card .card-body {
            padding: 24px;
        }

        .admin-match-create .tournament-picker {
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--match-border);
        }

        .admin-match-create .form-section-title {
            margin: 0 0 18px;
            padding: 0 0 10px;
            border-bottom: 1px solid var(--match-border);
            color: var(--match-ink);
            font-size: 16px;
            font-weight: 600;
            line-height: 1.45;
        }

        .admin-match-create .form-label {
            margin-bottom: 7px;
            color: var(--match-charcoal);
            font-size: 14px;
            font-weight: 500;
        }

        .admin-match-create .required {
            color: var(--match-danger);
        }

        .admin-match-create .form-control,
        .admin-match-create .form-select,
        .admin-match-create .field-readonly {
            min-height: 44px;
            border: 1px solid var(--match-border-strong);
            border-radius: 8px;
            color: var(--match-ink);
            font-size: 14px;
        }

        .admin-match-create .form-control,
        .admin-match-create .form-select {
            background-color: #ffffff;
        }

        .admin-match-create textarea.form-control {
            min-height: 84px;
            resize: vertical;
        }

        .admin-match-create .form-control:focus,
        .admin-match-create .form-select:focus {
            border-color: var(--match-ink);
            box-shadow: 0 0 0 3px var(--match-cloud);
        }

        .admin-match-create .field-readonly {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            background: var(--match-cloud);
            color: var(--match-charcoal);
            cursor: not-allowed;
        }

        .admin-match-create .form-text {
            margin-top: 7px;
            color: var(--match-muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .admin-match-create .tournament-summary,
        .admin-match-create #teamGroupInfo {
            padding: 14px 16px;
            border: 1px solid var(--match-border);
            border-radius: 8px;
            background: var(--match-cloud);
            color: var(--match-charcoal);
            line-height: 1.6;
        }

        .admin-match-create .tournament-summary small {
            display: block;
            margin-top: 5px;
            color: var(--match-muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .admin-match-create .alert-danger {
            border: 1px solid #f0c9c5;
            border-radius: 8px;
        }

        .admin-match-create .match-divider {
            margin-top: 28px !important;
            padding-top: 24px !important;
            border-color: var(--match-border) !important;
        }

        .admin-match-create .match-actions {
            margin-top: 28px !important;
            padding-top: 20px !important;
            border-color: var(--match-border) !important;
        }

        .admin-match-create .match-actions .btn-primary,
        .admin-match-create .empty-action {
            background: var(--match-ink);
            color: #ffffff;
        }

        .admin-match-create .match-actions .btn-primary:hover,
        .admin-match-create .empty-action:hover {
            background: #39393b;
            color: #ffffff;
        }

        .admin-match-create .empty-state {
            padding: 40px 24px !important;
            color: var(--match-charcoal);
        }

        .admin-match-create .empty-state > i {
            color: var(--match-muted) !important;
            font-size: 28px;
        }

        .admin-match-create .empty-state h3,
        .admin-match-create .empty-state h4 {
            color: var(--match-ink);
            font-size: 18px;
            font-weight: 600;
        }

        .admin-match-create .tips-panel {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding: 18px 20px;
            border: 1px solid var(--match-border);
            border-radius: 8px;
            background: #ffffff;
            color: var(--match-charcoal);
        }

        .admin-match-create .tips-panel > i {
            margin-top: 2px;
            color: var(--match-muted);
        }

        .admin-match-create .tips-panel strong {
            color: var(--match-ink);
        }

        .admin-match-create .tips-panel ul {
            padding-left: 18px;
            color: var(--match-muted);
            font-size: 13px;
            line-height: 1.7;
        }

        @media (max-width: 767.98px) {
            .admin-match-create .page-header {
                align-items: flex-start;
                margin-bottom: 22px;
            }

            .admin-match-create .page-header h1 {
                font-size: 26px;
            }

            .admin-match-create .form-card .card-header {
                padding: 16px 18px;
            }

            .admin-match-create .form-card .card-body {
                padding: 18px;
            }

            .admin-match-create .match-actions {
                align-items: stretch !important;
            }
        }

        @media (max-width: 575.98px) {
            .admin-match-create .page-header {
                flex-direction: column;
                align-items: stretch;
                gap: 14px;
            }

            .admin-match-create .btn-back {
                align-self: flex-start;
            }

            .admin-match-create .match-actions {
                flex-direction: column-reverse;
            }

            .admin-match-create .match-actions .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <main class="admin-match-create">
    <div class="page-header">
        <div>
            <h1>Buat Pertandingan Baru</h1>
            <p class="page-subtitle">Jadwalkan pertandingan baru untuk turnamen Anda</p>
        </div>
        <a href="{{ route('admin.matches.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="form-card">
        <div class="card-header">
            <h5>Buat pertandingan</h5>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> Perbaiki kesalahan berikut:
                    <ul class="mt-2 mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Form pilih turnamen (GET, auto-submit) -->
            <form method="GET" action="{{ route('admin.matches.create') }}" class="tournament-picker">
                <div class="form-section-title">Pilih Turnamen <span class="required">*</span></div>

                <select class="form-select @error('tournament_id') is-invalid @enderror"
                    id="tournament_id" name="tournament_id" required
                    onchange="this.form.submit()">
                    <option value="">Pilih turnamen</option>
                    @foreach($tournaments as $tournament)
                        <option value="{{ $tournament->id }}"
                            {{ $tournamentId == $tournament->id ? 'selected' : '' }}>
                            {{ $tournament->name }}
                            ({{ ucfirst($tournament->type) }} |
                            {{ ucfirst($tournament->status) }} |
                            {{ $tournament->start_date->format('d M Y') }} - {{ $tournament->end_date->format('d M Y') }})
                        </option>
                    @endforeach
                </select>
                @error('tournament_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if($tournamentId)
                    @php
                        $selectedTournament = \App\Models\Tournament::find($tournamentId);
                        // 'settings' sudah di-cast ke array oleh model Tournament
                        $tournamentSettings = is_array($selectedTournament->settings)
                            ? $selectedTournament->settings
                            : (json_decode($selectedTournament->settings, true) ?? []);
                        $tournamentType = $selectedTournament->type;
                    @endphp
                    <div class="tournament-summary mt-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>{{ $selectedTournament->name }}</strong> dipilih.
                        <br>
                        <small>
                            <strong>Tipe:</strong> {{ ucfirst(str_replace('_', ' ', $tournamentType)) }} |
                            <strong>Tim:</strong> {{ $teams->count() }} |
                            @if($tournamentType === 'group_knockout')
                                <strong>Grup:</strong> {{ $selectedTournament->groups_count }} |
                                <strong>Kualifikasi:</strong> {{ $selectedTournament->qualify_per_group }} per grup
                            @elseif($tournamentType === 'league')
                                <strong>Ronde:</strong> {{ $tournamentSettings['league_rounds'] ?? 1 }} |
                                <strong>Format:</strong> Single Round-Robin (tanpa grup)
                            @elseif($tournamentType === 'knockout')
                                <strong>Format:</strong> {{ ucfirst(str_replace('_', ' ', $tournamentSettings['knockout_format'] ?? 'single_elimination')) }} |
                                <strong>Kapasitas:</strong> {{ $tournamentSettings['knockout_teams'] ?? 8 }} |
                                <strong>Bracket aktual:</strong> {{ $selectedTournament->knockout_bracket_size }}
                            @endif
                        </small>
                    </div>
                @endif
            </form>

            @if($tournamentId)
                <!-- Form buat pertandingan (hanya tampil jika turnamen dipilih) -->
                <form action="{{ route('admin.matches.store') }}" method="POST" id="matchForm">
                    @csrf
                    <input type="hidden" name="tournament_id" value="{{ $tournamentId }}">

                    @if($teams->count() > 0)
                        <div class="row g-4">
                            <!-- Kolom kiri: info dasar -->
                            <div class="col-md-6">
                                <div class="form-section-title">Informasi Dasar</div>

                                <!-- Tanggal -->
                                <div class="mb-3">
                                    <label for="match_date" class="form-label">Tanggal Pertandingan <span class="required">*</span></label>
                                    <input type="date" class="form-control @error('match_date') is-invalid @enderror"
                                        id="match_date" name="match_date"
                                        value="{{ old('match_date') }}"
                                        min="{{ $selectedTournament->start_date->format('Y-m-d') }}"
                                        max="{{ $selectedTournament->end_date->format('Y-m-d') }}"
                                        required>
                                    @error('match_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Rentang tanggal turnamen: {{ $selectedTournament->start_date->format('d M Y') }} - {{ $selectedTournament->end_date->format('d M Y') }}
                                    </div>
                                </div>

                                <!-- Waktu -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="time_start" class="form-label">Waktu Mulai <span class="required">*</span></label>
                                        <input type="time" class="form-control @error('time_start') is-invalid @enderror"
                                            id="time_start" name="time_start" value="{{ old('time_start', '14:00') }}" required>
                                        @error('time_start')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="time_end" class="form-label">Waktu Selesai <span class="required">*</span></label>
                                        <input type="time" class="form-control @error('time_end') is-invalid @enderror"
                                            id="time_end" name="time_end" value="{{ old('time_end', '15:40') }}" required>
                                        @error('time_end')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Venue -->
                                <div class="mb-0">
                                    <label for="venue" class="form-label">Venue</label>
                                    <input type="text" class="form-control @error('venue') is-invalid @enderror" id="venue"
                                        name="venue" value="{{ old('venue', $selectedTournament->location ?? 'Main Field') }}"
                                        placeholder="contoh: Main Field, Lapangan 1">
                                    @error('venue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kolom kanan: tim -->
                            <div class="col-md-6">
                                <div class="form-section-title">Tim</div>

                                <!-- Tim home -->
                                <div class="mb-3">
                                    <label for="team_home_id" class="form-label">Tim Home <span class="required">*</span></label>
                                    <select class="form-select @error('team_home_id') is-invalid @enderror" id="team_home_id"
                                        name="team_home_id" required>
                                        <option value="">Pilih tim home</option>
                                        @foreach($teams as $team)
                                            @php
                                                $teamPivot = $team->tournaments()
                                                    ->where('tournament_id', $tournamentId)
                                                    ->first()?->pivot;
                                                $teamGroup = $teamPivot->group_name ?? null;
                                                $teamSeed = $teamPivot->seed ?? '';
                                            @endphp
                                            <option value="{{ $team->id }}"
                                                data-group="{{ $teamGroup ?? '' }}"
                                                data-seed="{{ $teamSeed }}"
                                                {{ old('team_home_id') == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }}
                                                @if($teamGroup && $tournamentType === 'group_knockout')
                                                    (Group {{ $teamGroup }})
                                                @endif
                                                @if($teamSeed)
                                                    [Seed: {{ $teamSeed }}]
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('team_home_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tim away -->
                                <div class="mb-3">
                                    <label for="team_away_id" class="form-label">Tim Away <span class="required">*</span></label>
                                    <select class="form-select @error('team_away_id') is-invalid @enderror" id="team_away_id"
                                        name="team_away_id" required>
                                        <option value="">Pilih tim home terlebih dahulu</option>
                                        @foreach($teams as $team)
                                            @php
                                                $teamPivot = $team->tournaments()
                                                    ->where('tournament_id', $tournamentId)
                                                    ->first()?->pivot;
                                                $teamGroup = $teamPivot->group_name ?? null;
                                                $teamSeed = $teamPivot->seed ?? '';
                                            @endphp
                                            <option value="{{ $team->id }}"
                                                data-group="{{ $teamGroup ?? '' }}"
                                                data-seed="{{ $teamSeed }}"
                                                {{ old('team_away_id') == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }}
                                                @if($teamGroup && $tournamentType === 'group_knockout')
                                                    (Group {{ $teamGroup }})
                                                @endif
                                                @if($teamSeed)
                                                    [Seed: {{ $teamSeed }}]
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('team_away_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Info grup tim -->
                                <div id="teamGroupInfo" class="alert alert-info mt-3 mb-0" style="display: none;">
                                    <i class="bi bi-info-circle"></i>
                                    <span id="teamGroupMessage"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mt-0 pt-4 border-top match-divider">
                            <!-- Kolom kiri: pengaturan pertandingan -->
                            <div class="col-md-6">
                                <div class="form-section-title">Pengaturan Pertandingan</div>

                                <!-- Tipe ronde -->
                                <div class="mb-3">
                                    @if($tournamentType === 'league')
                                        <!-- League: round_type selalu 'league', group_name null -->
                                        <input type="hidden" name="round_type" value="league">
                                        <input type="hidden" name="group_name" value="">
                                        <input type="hidden" name="stage" value="league">

                                        <label class="form-label">Tipe Ronde <span class="required">*</span></label>
                                        <div class="field-readonly">
                                            <i class="bi bi-trophy me-1"></i> League Match (Single Round-Robin)
                                        </div>
                                        <div class="form-text text-success">
                                            <i class="bi bi-info-circle"></i> Format league: semua tim bertanding satu kali dalam satu tabel klasemen.
                                        </div>
                                    @else
                                        <label for="round_type" class="form-label">Tipe Ronde <span class="required">*</span></label>
                                        <select class="form-select @error('round_type') is-invalid @enderror" id="round_type"
                                            name="round_type" required>
                                            <option value="">Pilih tipe ronde</option>
                                            @if($tournamentType === 'knockout')
                                                @php
                                                    $knockoutTeams = $selectedTournament->knockout_bracket_size;
                                                @endphp
                                                @if($knockoutTeams >= 64)
                                                    <option value="round_of_64" {{ old('round_type') == 'round_of_64' ? 'selected' : '' }}>
                                                        Babak 64 Besar
                                                    </option>
                                                @endif
                                                @if($knockoutTeams >= 32)
                                                    <option value="round_of_32" {{ old('round_type') == 'round_of_32' ? 'selected' : '' }}>
                                                        Round of 32
                                                    </option>
                                                @endif
                                                @if($knockoutTeams >= 16)
                                                    <option value="round_of_16" {{ old('round_type') == 'round_of_16' ? 'selected' : '' }}>
                                                        Round of 16
                                                    </option>
                                                @endif
                                                @if($knockoutTeams >= 8)
                                                    <option value="quarterfinal" {{ old('round_type') == 'quarterfinal' ? 'selected' : '' }}>
                                                        Quarterfinal
                                                    </option>
                                                @endif
                                                @if($knockoutTeams >= 4)
                                                    <option value="semifinal" {{ old('round_type') == 'semifinal' ? 'selected' : '' }}>
                                                        Semifinal
                                                    </option>
                                                @endif
                                                <option value="final" {{ old('round_type') == 'final' ? 'selected' : '' }}>
                                                    Final
                                                </option>
                                                @if($tournamentSettings['knockout_third_place'] ?? false)
                                                    <option value="third_place" {{ old('round_type') == 'third_place' ? 'selected' : '' }}>
                                                        Third-place playoff (3rd/4th)
                                                    </option>
                                                @endif
                                            @elseif($tournamentType === 'group_knockout')
                                                <option value="group" {{ old('round_type', 'group') == 'group' ? 'selected' : '' }}>
                                                    Group Stage
                                                </option>
                                                @php
                                                    $qualifyPerGroup = $selectedTournament->qualify_per_group ?? 2;
                                                    $groupsCount = $selectedTournament->groups_count ?? 2;
                                                    $knockoutTeams = $qualifyPerGroup * $groupsCount;
                                                @endphp
                                                @if($knockoutTeams >= 8)
                                                    <option value="quarterfinal" {{ old('round_type') == 'quarterfinal' ? 'selected' : '' }}>
                                                        Quarterfinal
                                                    </option>
                                                @endif
                                                @if($knockoutTeams >= 4)
                                                    <option value="semifinal" {{ old('round_type') == 'semifinal' ? 'selected' : '' }}>
                                                        Semifinal
                                                    </option>
                                                @endif
                                                <option value="final" {{ old('round_type') == 'final' ? 'selected' : '' }}>
                                                    Final
                                                </option>
                                                @if($tournamentSettings['knockout_third_place'] ?? false)
                                                    <option value="third_place" {{ old('round_type') == 'third_place' ? 'selected' : '' }}>
                                                        Third-place playoff (3rd/4th)
                                                    </option>
                                                @endif
                                            @endif
                                        </select>
                                        @error('round_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text" id="roundTypeHelp">
                                            @if($tournamentType === 'knockout')
                                                Pertandingan bracket knockout
                                            @elseif($tournamentType === 'group_knockout')
                                                Pertandingan group stage dan knockout
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Nama grup (hanya group_knockout) -->
                                @if($tournamentType === 'group_knockout')
                                    <div class="mb-3" id="group_name_field">
                                        <label for="group_name" class="form-label">Nama Grup</label>
                                        <select class="form-select @error('group_name') is-invalid @enderror" id="group_name"
                                            name="group_name">
                                            <option value="">Pilih grup</option>
                                            @if($selectedTournament->groups_count > 0)
                                                @for($i = 0; $i < min($selectedTournament->groups_count, 8); $i++)
                                                    @php
                                                        $groupLetter = chr(65 + $i); // A, B, C, dst.
                                                    @endphp
                                                    <option value="{{ $groupLetter }}" {{ old('group_name') == $groupLetter ? 'selected' : '' }}>
                                                        Group {{ $groupLetter }}
                                                    </option>
                                                @endfor
                                            @else
                                                @foreach(['A', 'B', 'C', 'D'] as $group)
                                                    <option value="{{ $group }}" {{ old('group_name') == $group ? 'selected' : '' }}>
                                                        Group {{ $group }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('group_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Hanya wajib untuk pertandingan Group Stage</div>
                                    </div>
                                @elseif($tournamentType === 'knockout')
                                    <!-- Knockout: group_name null -->
                                    <input type="hidden" name="group_name" value="">
                                @endif

                                <!-- Stage -->
                                <div class="mb-0">
                                    @if($tournamentType === 'league')
                                        <label class="form-label">Stage</label>
                                        <div class="field-readonly">
                                            <i class="bi bi-trophy me-1"></i> League Stage
                                        </div>
                                    @else
                                        <label for="stage" class="form-label">Stage</label>
                                        <select class="form-select @error('stage') is-invalid @enderror" id="stage"
                                            name="stage">
                                            <option value="">Pilih stage</option>
                                            @if($tournamentType === 'group_knockout')
                                                <option value="group" {{ old('stage') == 'group' ? 'selected' : '' }}>Group Stage</option>
                                            @endif
                                            @if($tournamentType === 'knockout' || $tournamentType === 'group_knockout')
                                                <option value="knockout" {{ old('stage') == 'knockout' ? 'selected' : '' }}>Knockout Stage</option>
                                            @endif
                                            <option value="qualification" {{ old('stage') == 'qualification' ? 'selected' : '' }}>Qualification</option>
                                        </select>
                                        @error('stage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    @endif
                                </div>
                            </div>

                            <!-- Kolom kanan: status & info tambahan -->
                            <div class="col-md-6">
                                <div class="form-section-title">Status & Informasi</div>

                                <!-- Status -->
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status Pertandingan <span class="required">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                        required>
                                        <option value="upcoming" {{ old('status', 'upcoming') == 'upcoming' ? 'selected' : '' }}>
                                            Upcoming
                                        </option>
                                        <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>
                                            Ongoing
                                        </option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>
                                        <option value="postponed" {{ old('status') == 'postponed' ? 'selected' : '' }}>
                                            Postponed
                                        </option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Skor -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="home_score" class="form-label">Skor Home</label>
                                        <input type="number" class="form-control @error('home_score') is-invalid @enderror"
                                            id="home_score" name="home_score" value="{{ old('home_score', 0) }}" min="0">
                                        @error('home_score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="away_score" class="form-label">Skor Away</label>
                                        <input type="number" class="form-control @error('away_score') is-invalid @enderror"
                                            id="away_score" name="away_score" value="{{ old('away_score', 0) }}" min="0">
                                        @error('away_score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nomor ronde -->
                                <div class="mb-3">
                                    <label for="round" class="form-label">
                                        @if($tournamentType === 'league')
                                            Matchday (Pekan)
                                        @elseif($tournamentType === 'knockout')
                                            Putaran Bracket
                                        @else
                                            Matchday Grup
                                        @endif
                                    </label>
                                    <input type="number" class="form-control @error('round') is-invalid @enderror"
                                        id="round" name="round" value="{{ old('round', 1) }}" min="1">
                                    @error('round')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        @if($tournamentType === 'league')
                                            Ini pertandingan matchday ke berapa (biarkan 1 jika tidak yakin).
                                        @elseif($tournamentType === 'knockout')
                                            Sistem bracket otomatis mengatur putaran. Biarkan nilai default kecuali Anda yakin.
                                        @else
                                            Matchday ke berapa dalam grup (biarkan 1 jika tidak yakin).
                                        @endif
                                    </div>
                                </div>

                                <!-- Catatan -->
                                <div class="mb-0">
                                    <label for="notes" class="form-label">Catatan Tambahan</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                        rows="2" placeholder="Informasi tambahan tentang pertandingan ini...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Aksi form -->
                        <div class="d-flex justify-content-end align-items-center gap-2 mt-4 pt-3 border-top match-actions">
                            <a href="{{ route('admin.matches.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Buat Pertandingan
                            </button>
                        </div>
                    @else
                        <!-- Peringatan: belum ada tim -->
                        <div class="alert alert-warning text-center py-5 mb-0">
                            <i class="bi bi-people display-4"></i>
                            <h4 class="mt-3">Belum Ada Tim di Turnamen Ini</h4>
                            <p class="text-muted">Tambahkan tim ke turnamen terlebih dahulu sebelum membuat pertandingan.</p>
                            <a href="{{ route('admin.tournaments.teams', $tournamentId) }}" class="empty-action mt-3">
                                <i class="bi bi-plus-circle"></i> Tambah Tim ke Turnamen
                            </a>
                        </div>
                    @endif
                </form>
            @else
                <!-- Belum ada turnamen dipilih -->
                <div class="text-center py-5 empty-state">
                    <i class="bi bi-trophy display-4 text-muted"></i>
                    <h3 class="mt-3">Pilih Turnamen Terlebih Dahulu</h3>
                    <p class="text-muted">Silakan pilih turnamen dari dropdown di atas untuk membuat pertandingan.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tips -->
    @if($tournamentId && $teams->count() > 0)
        <div class="tips-panel">
            <i class="bi bi-lightbulb me-1"></i>
            <div>
                <strong>Tips untuk {{ $selectedTournament->name }} ({{ ucfirst(str_replace('_', ' ', $tournamentType)) }}):</strong>
                <ul class="mb-0 mt-2">
                    @if($tournamentType === 'group_knockout')
                        <li>Untuk group stage, kedua tim harus berasal dari grup yang sama</li>
                        <li>Pertandingan group stage menggunakan tipe ronde "Group"</li>
                        <li>Pertandingan knockout menggunakan tipe ronde Quarterfinal, Semifinal, Final</li>
                        <li>Grup akan terpilih otomatis berdasarkan tim yang dipilih</li>
                    @elseif($tournamentType === 'league')
                        <li>League menggunakan tipe ronde "League Match" (otomatis)</li>
                        <li>Tidak perlu pembagian grup. Format menggunakan single round-robin.</li>
                        <li>Semua tim saling bertanding satu kali</li>
                        <li>Hanya satu tabel klasemen untuk semua tim</li>
                        <li>Tipe ronde dan stage otomatis diisi "League"</li>
                    @elseif($tournamentType === 'knockout')
                        <li>Pertandingan knockout tidak memerlukan pembagian grup</li>
                        <li>Pilih tipe ronde sesuai format turnamen</li>
                        <li>Round of 32, Round of 16, Quarterfinal, Semifinal, Final tersedia</li>
                    @endif
                    <li>Tanggal pertandingan harus dalam rentang turnamen: {{ $selectedTournament->start_date->format('d M Y') }} - {{ $selectedTournament->end_date->format('d M Y') }}</li>
                    <li>Skor dapat diperbarui nanti jika pertandingan ongoing atau completed</li>
                </ul>
            </div>
        </div>
    @endif
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tournamentType = "{{ $tournamentType ?? '' }}";
            const roundTypeSelect = document.getElementById('round_type');
            const groupNameField = document.getElementById('group_name_field');
            const homeTeamSelect = document.getElementById('team_home_id');
            const awayTeamSelect = document.getElementById('team_away_id');
            const teamGroupInfo = document.getElementById('teamGroupInfo');
            const teamGroupMessage = document.getElementById('teamGroupMessage');

            // ===== Toggle field nama grup =====
            function toggleGroupField() {
                if (!roundTypeSelect || !tournamentType) return;

                const roundType = roundTypeSelect.value;
                const groupSelect = document.getElementById('group_name');

                // Field grup hanya tampil untuk group_knockout + round type 'group'
                if (tournamentType === 'group_knockout' && roundType === 'group') {
                    if (groupNameField) {
                        groupNameField.style.display = 'block';
                        if (groupSelect) {
                            groupSelect.required = true;
                        }
                    }
                } else {
                    if (groupNameField) {
                        groupNameField.style.display = 'none';
                        if (groupSelect) {
                            groupSelect.required = false;
                        }
                    }
                }
            }

            // ===== Auto-pilih grup berdasarkan tim terpilih =====
            function updateGroupSelection() {
                if (!homeTeamSelect || !awayTeamSelect || !teamGroupInfo || !teamGroupMessage) return;

                if (homeTeamSelect.value && awayTeamSelect.value) {
                    const homeOption = homeTeamSelect.options[homeTeamSelect.selectedIndex];
                    const awayOption = awayTeamSelect.options[awayTeamSelect.selectedIndex];

                    const homeGroup = homeOption.dataset.group || '';
                    const awayGroup = awayOption.dataset.group || '';

                    teamGroupInfo.style.display = 'block';

                    if (homeGroup && awayGroup) {
                        if (homeGroup === awayGroup) {
                            teamGroupMessage.innerHTML = `Kedua tim berada di <strong>Group ${homeGroup}</strong>. Grup akan terpilih otomatis.`;

                            const groupSelect = document.getElementById('group_name');
                            if (groupSelect) {
                                for (let option of groupSelect.options) {
                                    if (option.value === homeGroup) {
                                        groupSelect.value = homeGroup;
                                        break;
                                    }
                                }
                            }
                        } else {
                            teamGroupMessage.innerHTML = `
                                <span class="text-danger">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Tim berada di grup berbeda!
                                    Home: <strong>Group ${homeGroup}</strong>,
                                    Away: <strong>Group ${awayGroup}</strong>
                                </span>
                                <br>
                                <small>Untuk group stage, pilih tim dari grup yang sama.</small>
                            `;
                            const groupSelect = document.getElementById('group_name');
                            if (groupSelect) {
                                groupSelect.value = '';
                            }
                        }
                    } else if (tournamentType === 'league') {
                        teamGroupMessage.innerHTML = `
                            <span class="text-success">
                                <i class="bi bi-info-circle"></i>
                                Format league: tidak perlu pembagian grup.
                            </span>
                            <br>
                            <small>Semua tim bertanding dalam format single round-robin.</small>
                        `;
                    } else if (tournamentType === 'knockout') {
                        teamGroupMessage.innerHTML = `
                            <span class="text-success">
                                <i class="bi bi-info-circle"></i>
                                Format knockout: tidak perlu pembagian grup.
                            </span>
                            <br>
                            <small>Bracket eliminasi langsung.</small>
                        `;
                    } else if (tournamentType === 'group_knockout') {
                        teamGroupMessage.innerHTML = `
                            <span class="text-warning">
                                <i class="bi bi-info-circle"></i>
                                Satu atau kedua tim belum tergabung dalam grup.
                            </span>
                            <br>
                            <small>Pilih grup secara manual jika diperlukan.</small>
                        `;
                    } else {
                        teamGroupInfo.style.display = 'none';
                    }
                } else {
                    teamGroupInfo.style.display = 'none';
                }
            }

            // ===== Auto-isi waktu selesai (durasi futsal 1 jam 40 menit) =====
            const timeStart = document.getElementById('time_start');
            const timeEnd = document.getElementById('time_end');

            if (timeStart && timeEnd) {
                timeStart.addEventListener('change', function () {
                    if (timeStart.value && !timeEnd.value) {
                        const startTime = new Date(`1970-01-01T${timeStart.value}:00`);
                        startTime.setMinutes(startTime.getMinutes() + 100);

                        const hours = startTime.getHours().toString().padStart(2, '0');
                        const minutes = startTime.getMinutes().toString().padStart(2, '0');

                        timeEnd.value = `${hours}:${minutes}`;
                    }
                });
            }

            // ===== Event listeners (hanya untuk turnamen non-league) =====
            if (roundTypeSelect && tournamentType !== 'league') {
                toggleGroupField();
                roundTypeSelect.addEventListener('change', toggleGroupField);
            }

            if (homeTeamSelect && awayTeamSelect) {
                homeTeamSelect.addEventListener('change', updateGroupSelection);
                awayTeamSelect.addEventListener('change', updateGroupSelection);
                updateGroupSelection();
            }

            // ===== Filter tim away: hanya tim satu grup (group stage) & belum pernah bertemu =====
            const allAwayOptions = awayTeamSelect ? Array.from(awayTeamSelect.options) : [];
            const playedPairs = @json($playedPairs ?? []);

            function isPairAlreadyPlayed(homeId, awayId) {
                return playedPairs.some(p =>
                    (String(p[0]) === String(homeId) && String(p[1]) === String(awayId)) ||
                    (String(p[0]) === String(awayId) && String(p[1]) === String(homeId))
                );
            }

            function filterAwayTeams() {
                if (!homeTeamSelect || !awayTeamSelect) return;

                const homeOption = homeTeamSelect.options[homeTeamSelect.selectedIndex];
                const homeId = homeTeamSelect.value;
                const homeGroup = homeOption?.dataset.group || '';
                const isGroupStage = tournamentType === 'group_knockout' &&
                    (roundTypeSelect?.value === 'group' || !roundTypeSelect);

                awayTeamSelect.innerHTML = '';
                allAwayOptions.forEach(opt => {
                    if (!opt.value) {
                        awayTeamSelect.appendChild(opt.cloneNode(true));
                        return;
                    }
                    // Tim yang sama dengan home tidak boleh
                    if (opt.value === homeId) return;
                    // Group stage: hanya tim satu grup
                    if (isGroupStage && homeGroup && opt.dataset.group && opt.dataset.group !== homeGroup) return;
                    // Duplikat pasangan: tandai sebagai disabled (kecuali yang sedang terpilih dari old input)
                    if (isPairAlreadyPlayed(homeId, opt.value) && opt.value !== awayTeamSelect.dataset.oldValue) {
                        return;
                    }
                    awayTeamSelect.appendChild(opt.cloneNode(true));
                });

                // Tambahkan opsi terpilih dari old input jika tidak ada dalam daftar
                if (awayTeamSelect.dataset.oldValue &&
                    !Array.from(awayTeamSelect.options).some(o => o.value === awayTeamSelect.dataset.oldValue)) {
                    const oldOpt = allAwayOptions.find(o => o.value === awayTeamSelect.dataset.oldValue);
                    if (oldOpt) awayTeamSelect.appendChild(oldOpt.cloneNode(true));
                }

                // Placeholder kembali ke awal
                if (!awayTeamSelect.dataset.oldValue) {
                    awayTeamSelect.selectedIndex = 0;
                }
            }

            // Simpan old value sebelum filter
            if (awayTeamSelect) {
                awayTeamSelect.dataset.oldValue = awayTeamSelect.value || '{{ old('team_away_id') }}';
            }

            if (homeTeamSelect) {
                homeTeamSelect.addEventListener('change', function () {
                    filterAwayTeams();
                    updateGroupSelection();
                });
                filterAwayTeams();
            }

            if (roundTypeSelect) {
                roundTypeSelect.addEventListener('change', filterAwayTeams);
            }

            // ===== Validasi saat submit =====
            const matchForm = document.getElementById('matchForm');
            if (matchForm) {
                matchForm.addEventListener('submit', function (e) {
                    // League: tidak perlu validasi tambahan
                    if (tournamentType === 'league') {
                        return true;
                    }

                    const roundTypeSelect = document.getElementById('round_type');
                    const roundType = roundTypeSelect ? roundTypeSelect.value : 'league';

                    // Validasi group stage hanya untuk turnamen group_knockout
                    if (tournamentType === 'group_knockout' && roundType === 'group') {
                        const homeOption = homeTeamSelect.options[homeTeamSelect.selectedIndex];
                        const awayOption = awayTeamSelect.options[awayTeamSelect.selectedIndex];

                        const homeGroup = homeOption.dataset.group || '';
                        const awayGroup = awayOption.dataset.group || '';

                        if (homeGroup && awayGroup && homeGroup !== awayGroup) {
                            e.preventDefault();
                            alert('Untuk group stage, kedua tim harus berasal dari grup yang sama. Silakan pilih tim dari grup yang sama.');
                            return false;
                        }

                        const groupSelect = document.getElementById('group_name');
                        if (groupSelect && !groupSelect.value) {
                            e.preventDefault();
                            alert('Silakan pilih grup untuk pertandingan ini.');
                            return false;
                        }
                    }

                    return true;
                });
            }

            // ===== Tanggal default: hari ini jika belum diisi =====
            const matchDateInput = document.getElementById('match_date');
            if (matchDateInput && !matchDateInput.value) {
                matchDateInput.value = @json(\Carbon\CarbonImmutable::today(\App\Support\BusinessTime::TIMEZONE)->toDateString());
            }
        });
    </script>
@endsection
