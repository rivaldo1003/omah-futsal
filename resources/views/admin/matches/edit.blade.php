@extends('layouts.admin')

@section('title', 'Edit Match')

@section('styles')
    <style>
        .admin-match-edit {
            --match-ink: #111111;
            --match-charcoal: #39393b;
            --match-muted: #707072;
            --match-cloud: #f5f5f5;
            --match-border: #e5e5e5;
            --match-border-strong: #cacacb;
            --match-success: #007d48;
            --match-danger: #b42318;
            max-width: 1440px;
            margin-inline: auto;
            color: var(--match-ink);
        }

        .admin-match-edit .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 28px;
        }

        .admin-match-edit .page-header h1 {
            margin: 0 0 8px;
            color: var(--match-ink);
            font-size: clamp(26px, 3vw, 32px);
            font-weight: 600;
            letter-spacing: -0.035em;
            line-height: 1.15;
        }

        .admin-match-edit .page-subtitle {
            max-width: 68ch;
            margin: 0;
            color: var(--match-muted);
            font-size: 15px;
            line-height: 1.5;
        }

        .admin-match-edit .btn-back {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: 0;
            border-radius: 30px;
            background: var(--match-cloud);
            color: var(--match-ink);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: background-color .15s ease;
        }

        .admin-match-edit .btn-back:hover {
            background: #eaeaec;
            color: var(--match-ink);
        }

        .admin-match-edit .form-card {
            overflow: hidden;
            border: 1px solid var(--match-border);
            border-radius: 8px;
            background: #ffffff;
            box-shadow: none;
        }

        .admin-match-edit .form-card > .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--match-border);
            background: #ffffff;
        }

        .admin-match-edit .form-card .card-header h5 {
            margin: 0;
            color: var(--match-ink);
            font-size: 16px;
            font-weight: 600;
        }

        .admin-match-edit .form-card .card-body {
            padding: 24px;
        }

        .admin-match-edit .form-card .card-body > .alert {
            margin-bottom: 22px;
            padding: 14px 16px;
            border: 1px solid var(--match-border);
            border-radius: 8px;
            background: var(--match-cloud);
            color: var(--match-charcoal);
            box-shadow: none;
        }

        .admin-match-edit > .alert {
            padding: 14px 16px;
            border: 1px solid #f0cbc7;
            border-radius: 8px;
            background: #fff5f4;
            color: #8f1d17;
            box-shadow: none;
        }

        .admin-match-edit .form-card .card-body > .alert-info {
            border-color: var(--match-border);
            background: var(--match-cloud);
            color: var(--match-charcoal);
        }

        .admin-match-edit .form-card .card-body > .alert-danger {
            border-color: #f0cbc7;
            background: #fff5f4;
            color: #8f1d17;
        }

        .admin-match-edit .form-section-title {
            margin: 0 0 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--match-border);
            color: var(--match-ink);
            font-size: 16px;
            font-weight: 600;
            line-height: 1.45;
        }

        .admin-match-edit .form-label {
            margin-bottom: 7px;
            color: var(--match-charcoal);
            font-size: 14px;
            font-weight: 500;
        }

        .admin-match-edit .required {
            color: var(--match-danger);
        }

        .admin-match-edit .form-control,
        .admin-match-edit .form-select {
            min-height: 46px;
            padding: 10px 14px;
            border: 1px solid var(--match-border-strong);
            border-radius: 8px;
            background-color: #ffffff;
            color: var(--match-ink);
            box-shadow: none;
            transition: border-color .15s ease, background-color .15s ease;
        }

        .admin-match-edit .form-control:focus,
        .admin-match-edit .form-select:focus {
            padding: 9px 13px;
            border: 2px solid var(--match-ink);
            box-shadow: none;
            outline: none;
        }

        .admin-match-edit .form-control:disabled,
        .admin-match-edit .form-select:disabled,
        .admin-match-edit .form-control[aria-readonly="true"] {
            background-color: var(--match-cloud) !important;
            color: var(--match-muted);
            opacity: 1;
        }

        .admin-match-edit .form-control.is-invalid,
        .admin-match-edit .form-select.is-invalid {
            border-color: var(--match-danger);
        }

        .admin-match-edit .form-text {
            margin-top: 6px;
            color: var(--match-muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .admin-match-edit .invalid-feedback,
        .admin-match-edit .validation-error {
            color: var(--match-danger);
        }

        .admin-match-edit .validation-success {
            color: var(--match-success);
        }

        .admin-match-edit .badge-friendly {
            padding: 5px 9px;
            border: 1px solid var(--match-border);
            border-radius: 4px;
            background: var(--match-cloud);
            color: var(--match-charcoal);
            font-size: 12px;
            font-weight: 600;
        }

        .admin-match-edit .form-control.bg-light {
            gap: 8px;
        }

        .admin-match-edit .team-select {
            min-width: 0;
            text-overflow: ellipsis;
        }

        .admin-match-edit .form-check-input {
            border-color: var(--match-border-strong);
            box-shadow: none;
        }

        .admin-match-edit .form-check-input:checked {
            border-color: var(--match-ink);
            background-color: var(--match-ink);
        }

        .admin-match-edit .form-check-input:focus {
            border-color: var(--match-ink);
            box-shadow: none;
        }

        .admin-match-edit .form-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid var(--match-border);
        }

        .admin-match-edit .form-actions .btn {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 22px;
            border: 0;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            box-shadow: none;
            text-decoration: none;
            transition: background-color .15s ease, color .15s ease;
        }

        .admin-match-edit .form-actions .btn-outline-secondary {
            background: var(--match-cloud);
            color: var(--match-ink);
        }

        .admin-match-edit .form-actions .btn-outline-secondary:hover {
            background: #eaeaec;
            color: var(--match-ink);
        }

        .admin-match-edit .form-actions .btn-primary {
            background: var(--match-ink);
            color: #ffffff;
        }

        .admin-match-edit .form-actions .btn-primary:hover {
            background: #303032;
            color: #ffffff;
        }

        .admin-match-edit :focus-visible:not(.form-control):not(.form-select):not(.form-check-input) {
            outline: 2px solid var(--match-ink);
            outline-offset: 2px;
        }

        @media (max-width: 768px) {
            .admin-match-edit .page-header {
                align-items: flex-start;
            }

            .admin-match-edit .form-card > .card-header {
                padding: 16px 18px;
            }

            .admin-match-edit .form-card .card-body {
                padding: 18px;
            }

            .admin-match-edit .form-actions .btn {
                min-width: 130px;
            }
        }

        @media (max-width: 575.98px) {
            .admin-match-edit .form-actions {
                align-items: stretch;
                flex-direction: column-reverse;
            }

            .admin-match-edit .form-actions .btn {
                width: 100%;
            }

            .admin-match-edit #team_home_id,
            .admin-match-edit #team_away_id {
                min-height: 54px;
                padding-inline: 9px;
                font-size: 12px;
            }

            .admin-match-edit #team_home_id:focus,
            .admin-match-edit #team_away_id:focus {
                padding-inline: 8px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-match-edit *,
            .admin-match-edit *::before,
            .admin-match-edit *::after {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
    </style>
@endsection

@section('content')
    <main class="admin-match-edit">
    <div class="page-header">
        <div>
            <h1>Edit Pertandingan</h1>
            <p class="page-subtitle">
                {{ $isBracketManaged ? 'Atur jadwal fixture bracket. Peserta tetap mengikuti progres pertandingan.' : 'Perbarui detail dan jadwal pertandingan' }}
            </p>
        </div>
        <a href="{{ route('admin.matches.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> Perbaiki kesalahan berikut:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="form-card">
        <div class="card-header">
            <h5>Detail pertandingan</h5>
        </div>

        <div class="card-body">
            @if($isBracketManaged)
                <div class="alert alert-info" role="status">
                    Fixture ini terhubung ke bracket. Tanggal, waktu, venue, dan catatan dapat diatur saat peserta masih TBA;
                    nama tim dan hasil tetap dikelola otomatis oleh bracket.
                </div>
            @endif

            <form action="{{ route('admin.matches.update', $match) }}" method="POST" id="matchForm">
                @csrf
                @method('PUT')

                @php
                    /* Opsi tim + grup asal (dari pivot turnamen) — dihitung sekali untuk kedua select */
                    $teamOptions = $teams->map(function ($team) use ($match) {
                        $group = $team->tournaments
                            ->where('id', $match->tournament_id)
                            ->first()
                            ->pivot
                            ->group_name ?? '';

                        return [
                            'id' => $team->id,
                            'name' => $team->name,
                            'group' => $group,
                        ];
                    });
                @endphp

                <div class="row g-4">
                    <!-- Kolom kiri: turnamen & info dasar -->
                    <div class="col-md-6">
                        <div class="form-section-title">Turnamen & Informasi dasar</div>

                        <!-- Turnamen -->
                        <div class="mb-3">
                            @if($isFriendly ?? false)
                                <label class="form-label">Tipe Pertandingan</label>
                                <div class="form-control bg-light d-flex align-items-center">
                                    <span class="badge badge-friendly">Friendly Match</span>
                                    <small class="text-muted ms-2">Tidak terikat turnamen</small>
                                </div>
                            @elseif($isBracketManaged)
                                <label class="form-label">Turnamen</label>
                                <div class="form-control bg-light d-flex align-items-center" aria-readonly="true">
                                    {{ $match->tournament?->name ?? 'Turnamen bracket' }}
                                </div>
                            @else
                                <label for="tournament_id" class="form-label">Turnamen <span class="required">*</span></label>
                                <select name="tournament_id" id="tournament_id"
                                    class="form-select @error('tournament_id') is-invalid @enderror" required>
                                    <option value="">Pilih turnamen</option>
                                    @foreach($tournaments as $tournament)
                                        <option value="{{ $tournament->id }}"
                                            {{ old('tournament_id', $match->tournament_id) == $tournament->id ? 'selected' : '' }}>
                                            {{ $tournament->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tournament_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        <!-- Tipe ronde -->
                        <div class="mb-3">
                            <label for="round_type" class="form-label">Tipe Ronde @unless($isBracketManaged)<span class="required">*</span>@endunless</label>
                            <select name="round_type" id="round_type"
                                class="form-select @error('round_type') is-invalid @enderror"
                                {{ ($isFriendly ?? false) ? '' : 'required' }} @disabled($isBracketManaged)>
                                <option value="">Pilih tipe ronde</option>

                                @if($isFriendly ?? false)
                                    <option value="friendly" {{ old('round_type', $match->round_type) == 'friendly' ? 'selected' : '' }}>
                                        Friendly Match
                                    </option>
                                @endif

                                <option value="league" {{ old('round_type', $match->round_type) == 'league' ? 'selected' : '' }}>
                                    League
                                </option>

                                {{-- Ronde kualifikasi / pendahuluan --}}
                                <option value="preliminary" {{ old('round_type', $match->round_type) == 'preliminary' ? 'selected' : '' }}>
                                    Preliminary Round
                                </option>
                                <option value="qualifying" {{ old('round_type', $match->round_type) == 'qualifying' ? 'selected' : '' }}>
                                    Qualifying Round
                                </option>

                                {{-- Ronde knockout utama --}}
                                <option value="round_of_64" {{ old('round_type', $match->round_type) == 'round_of_64' ? 'selected' : '' }}>
                                    Round of 64
                                </option>
                                <option value="round_of_32" {{ old('round_type', $match->round_type) == 'round_of_32' ? 'selected' : '' }}>
                                    Round of 32
                                </option>
                                <option value="round_of_16" {{ old('round_type', $match->round_type) == 'round_of_16' ? 'selected' : '' }}>
                                    Round of 16
                                </option>
                                <option value="quarterfinal" {{ old('round_type', $match->round_type) == 'quarterfinal' ? 'selected' : '' }}>
                                    Quarter Final
                                </option>
                                <option value="semifinal" {{ old('round_type', $match->round_type) == 'semifinal' ? 'selected' : '' }}>
                                    Semi Final
                                </option>
                                <option value="final" {{ old('round_type', $match->round_type) == 'final' ? 'selected' : '' }}>
                                    Final
                                </option>
                                <option value="third_place" {{ old('round_type', $match->round_type) == 'third_place' ? 'selected' : '' }}>
                                    Third-place playoff (3rd/4th)
                                </option>

                                {{-- Group stage (untuk turnamen grup + knockout) --}}
                                <option value="group" {{ old('round_type', $match->round_type) == 'group' ? 'selected' : '' }}>
                                    Group Stage
                                </option>
                            </select>
                            @error('round_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama grup (hanya tampil untuk group stage) -->
                        <div class="mb-3 toggle-field" id="groupField"
                            style="{{ old('round_type', $match->round_type) == 'group' ? '' : 'display: none;' }}">
                            <label for="group_name" class="form-label">Nama Grup <span class="required">*</span></label>
                            <select name="group_name" id="group_name"
                                class="form-select @error('group_name') is-invalid @enderror">
                                <option value="">Pilih grup</option>
                                @foreach($groupOptions as $group)
                                    <option value="{{ $group }}" {{ old('group_name', $match->group_name) == $group ? 'selected' : '' }}>
                                        Group {{ $group }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal pertandingan -->
                        <div class="mb-3">
                            <label for="match_date" class="form-label">Tanggal Pertandingan <span class="required">*</span></label>
                            <input type="date" name="match_date" id="match_date"
                                class="form-control @error('match_date') is-invalid @enderror"
                                value="{{ old('match_date', $match->match_date?->format('Y-m-d')) }}" required>
                            @error('match_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Waktu -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="time_start" class="form-label">Waktu Mulai <span class="required">*</span></label>
                                <input type="time" name="time_start" id="time_start"
                                    class="form-control @error('time_start') is-invalid @enderror"
                                    value="{{ old('time_start', $match->time_start) }}" required>
                                @error('time_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="time_end" class="form-label">Waktu Selesai <span class="required">*</span></label>
                                <input type="time" name="time_end" id="time_end"
                                    class="form-control @error('time_end') is-invalid @enderror"
                                    value="{{ old('time_end', $match->time_end) }}" required>
                                @error('time_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Kolom kanan: tim & status -->
                    <div class="col-md-6">
                        <div class="form-section-title">Tim & Status</div>

                        <!-- Pilihan tim -->
                        <div class="mb-3">
                            <label class="form-label">Tim @unless($isBracketManaged)<span class="required">*</span>@endunless</label>
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <select name="team_home_id" id="team_home_id"
                                        class="form-select team-select @error('team_home_id') is-invalid @enderror"
                                        @unless($isBracketManaged) required @endunless
                                        @disabled($isBracketManaged)>
                                        <option value="" @selected($isBracketManaged && !$match->team_home_id)>
                                            {{ $isBracketManaged ? 'TBA · Menunggu hasil babak sebelumnya' : 'Pilih tim home' }}
                                        </option>
                                        @foreach($teamOptions as $team)
                                            <option value="{{ $team['id'] }}"
                                                {{ old('team_home_id', $match->team_home_id) == $team['id'] ? 'selected' : '' }}
                                                data-group="{{ $team['group'] }}">
                                                {{ $team['name'] }}@if($team['group']) (Group {{ $team['group'] }})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('team_home_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-2 text-center">
                                    <span class="fw-bold text-secondary">VS</span>
                                </div>
                                <div class="col-5">
                                    <select name="team_away_id" id="team_away_id"
                                        class="form-select team-select @error('team_away_id') is-invalid @enderror"
                                        @unless($isBracketManaged) required @endunless
                                        @disabled($isBracketManaged)>
                                        <option value="" @selected($isBracketManaged && !$match->team_away_id)>
                                            {{ $isBracketManaged ? 'TBA · Menunggu hasil babak sebelumnya' : 'Pilih tim away' }}
                                        </option>
                                        @foreach($teamOptions as $team)
                                            <option value="{{ $team['id'] }}"
                                                {{ old('team_away_id', $match->team_away_id) == $team['id'] ? 'selected' : '' }}
                                                data-group="{{ $team['group'] }}">
                                                {{ $team['name'] }}@if($team['group']) (Group {{ $team['group'] }})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('team_away_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-text mt-2">
                                <div id="teamValidationMessage" class="validation-error" style="display: none;">
                                    <i class="bi bi-exclamation-triangle"></i> <span id="validationText"></span>
                                </div>
                                <div id="teamInfoMessage" class="validation-success" style="display: none;">
                                    <i class="bi bi-check-circle"></i> <span id="infoText"></span>
                                </div>
                                <div id="defaultMessage">
                                    {{ $isBracketManaged ? 'Peserta fixture ini tidak dapat diubah dari halaman jadwal.' : 'Pastikan kedua tim berasal dari grup yang sama untuk pertandingan group stage.' }}
                                </div>
                            </div>
                        </div>

                        <!-- Venue -->
                        <div class="mb-3">
                            <label for="venue" class="form-label">Venue</label>
                            <input type="text" name="venue" id="venue"
                                class="form-control @error('venue') is-invalid @enderror"
                                value="{{ old('venue', $match->venue) }}" placeholder="contoh: Stadion Utama">
                            @error('venue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Pertandingan @unless($isBracketManaged)<span class="required">*</span>@endunless</label>
                            <select name="status" id="status"
                                class="form-select @error('status') is-invalid @enderror" required
                                @disabled($isBracketManaged)>
                                <option value="upcoming" {{ old('status', $match->status) == 'upcoming' ? 'selected' : '' }}>
                                    Upcoming
                                </option>
                                <option value="ongoing" {{ old('status', $match->status) == 'ongoing' ? 'selected' : '' }}>
                                    Ongoing
                                </option>
                                <option value="completed" {{ old('status', $match->status) == 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                                <option value="postponed" {{ old('status', $match->status) == 'postponed' ? 'selected' : '' }}>
                                    Postponed
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Skor (hanya tampil jika status completed) -->
                        <div class="mb-3 toggle-field" id="scoreField"
                            style="{{ old('status', $match->status) == 'completed' ? '' : 'display: none;' }}">
                            <label class="form-label">Skor Akhir</label>
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <input type="number" name="home_score"
                                        class="form-control @error('home_score') is-invalid @enderror"
                                        value="{{ old('home_score', $match->home_score) }}" min="0" placeholder="Skor home"
                                        @disabled($isBracketManaged)>
                                    @error('home_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-2 text-center">
                                    <span class="fw-bold text-secondary">-</span>
                                </div>
                                <div class="col-5">
                                    <input type="number" name="away_score"
                                        class="form-control @error('away_score') is-invalid @enderror"
                                        value="{{ old('away_score', $match->away_score) }}" min="0" placeholder="Skor away"
                                        @disabled($isBracketManaged)>
                                    @error('away_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-text">Isi skor hanya jika pertandingan sudah selesai.</div>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="mb-0">
                    <label for="notes" class="form-label">Catatan Tambahan</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="form-control @error('notes') is-invalid @enderror"
                        placeholder="Informasi tambahan tentang pertandingan ini...">{{ old('notes', $match->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Aksi form: batal di kiri, tombol utama di kanan bawah -->
                <div class="form-actions">
                    <a href="{{ route('admin.matches.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ $isBracketManaged ? 'Simpan Jadwal' : 'Perbarui Pertandingan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ===== Referensi elemen =====
            const form = document.getElementById('matchForm');
            const roundTypeSelect = document.getElementById('round_type');
            const groupField = document.getElementById('groupField');
            const groupNameSelect = document.getElementById('group_name');
            const statusSelect = document.getElementById('status');
            const scoreField = document.getElementById('scoreField');
            const tournamentSelect = document.getElementById('tournament_id');
            const homeTeamSelect = document.getElementById('team_home_id');
            const awayTeamSelect = document.getElementById('team_away_id');
            const matchDateInput = document.getElementById('match_date');
            const timeStartInput = document.getElementById('time_start');
            const timeEndInput = document.getElementById('time_end');

            const validationMessage = document.getElementById('teamValidationMessage');
            const validationText = document.getElementById('validationText');
            const infoMessage = document.getElementById('teamInfoMessage');
            const infoText = document.getElementById('infoText');
            const defaultMessage = document.getElementById('defaultMessage');

            const show = (el) => { el.style.display = 'block'; };
            const hide = (el) => { el.style.display = 'none'; };

            // ===== Data grup tim (dari atribut data-group) =====
            const teamGroups = {};

            [homeTeamSelect, awayTeamSelect].forEach(function (select) {
                Array.from(select.options).forEach(function (option) {
                    if (option.value && option.dataset.group && !teamGroups[option.value]) {
                        teamGroups[option.value] = option.dataset.group;
                    }
                });
            });

            // ===== Toggle field =====
            function toggleGroupField() {
                if (roundTypeSelect.value === 'group') {
                    show(groupField);
                    groupNameSelect.setAttribute('required', 'required');
                    validateTeamsInSameGroup();
                } else {
                    hide(groupField);
                    groupNameSelect.removeAttribute('required');
                    clearTeamValidationMessages();
                }
            }

            function toggleScoreField() {
                if (statusSelect.value === 'completed') {
                    show(scoreField);
                } else {
                    hide(scoreField);
                }
            }

            // ===== Validasi tim satu grup =====
            function clearTeamValidationMessages() {
                hide(validationMessage);
                hide(infoMessage);
                show(defaultMessage);
            }

            function validateTeamsInSameGroup() {
                hide(validationMessage);
                hide(infoMessage);
                show(defaultMessage);

                const homeTeamId = homeTeamSelect.value;
                const awayTeamId = awayTeamSelect.value;

                if (!homeTeamId || !awayTeamId || homeTeamId === awayTeamId) {
                    return true;
                }

                if (roundTypeSelect.value !== 'group') {
                    return true;
                }

                const homeGroup = teamGroups[homeTeamId];
                const awayGroup = teamGroups[awayTeamId];

                if (!homeGroup || !awayGroup) {
                    validationText.textContent =
                        'Satu atau kedua tim belum tergabung dalam grup. Silakan cek registrasi tim.';
                    show(validationMessage);
                    hide(defaultMessage);
                    return false;
                }

                if (homeGroup !== awayGroup) {
                    validationText.textContent =
                        `Tim berada di grup berbeda (Group ${homeGroup} vs Group ${awayGroup}). Pertandingan group stage harus berasal dari grup yang sama.`;
                    show(validationMessage);
                    hide(defaultMessage);
                    return false;
                }

                infoText.textContent = `Kedua tim berada di Group ${homeGroup}.`;
                show(infoMessage);
                hide(defaultMessage);
                return true;
            }

            // ===== Filter opsi tim berdasarkan grup terpilih =====
            function filterTeamsByGroup(groupName) {
                if (!groupName) return;

                [homeTeamSelect, awayTeamSelect].forEach(function (select) {
                    Array.from(select.options).forEach(function (option) {
                        if (option.value === '') return;

                        const teamGroup = teamGroups[option.value];
                        const disabled = Boolean(teamGroup) && teamGroup !== groupName;

                        option.disabled = disabled;
                        option.style.display = disabled ? 'none' : '';
                    });
                });

                validateTeamsInSameGroup();
            }

            // ===== Validasi urutan waktu =====
            function validateTimeOrder() {
                const start = timeStartInput.value;
                const end = timeEndInput.value;

                if (start && end && start >= end) {
                    alert('Waktu selesai harus setelah waktu mulai');
                    timeEndInput.focus();
                }
            }

            // ===== Tanggal minimal: pertandingan yang sudah lewat tetap bisa diedit =====
            const currentMatchDateString = '{{ $match->match_date ? $match->match_date->format("Y-m-d") : "" }}';
            const today = @json(\Carbon\CarbonImmutable::today(\App\Support\BusinessTime::TIMEZONE)->toDateString());

            if (currentMatchDateString >= today) {
                matchDateInput.setAttribute('min', today);
            } else {
                matchDateInput.removeAttribute('min');
            }

            // ===== Kondisi awal =====
            toggleGroupField();
            toggleScoreField();

            if (roundTypeSelect.value === 'group' && groupNameSelect.value) {
                filterTeamsByGroup(groupNameSelect.value);
            }

            // ===== Event listeners =====
            roundTypeSelect.addEventListener('change', toggleGroupField);
            statusSelect.addEventListener('change', toggleScoreField);

            homeTeamSelect.addEventListener('change', validateTeamsInSameGroup);
            awayTeamSelect.addEventListener('change', validateTeamsInSameGroup);

            groupNameSelect.addEventListener('change', function () {
                filterTeamsByGroup(this.value);
            });

            timeStartInput.addEventListener('change', validateTimeOrder);
            timeEndInput.addEventListener('change', validateTimeOrder);

            // Reload halaman saat turnamen diganti agar data grup ikut ter-update
            if (tournamentSelect) {
                tournamentSelect.addEventListener('change', function () {
                    if (this.value) {
                        window.location.href = `{{ route('admin.matches.edit', $match) }}?tournament_id=${this.value}`;
                    }
                });
            }

            // ===== Validasi saat submit =====
            form.addEventListener('submit', function (e) {
                const homeTeamId = homeTeamSelect.value;
                const awayTeamId = awayTeamSelect.value;
                const roundType = roundTypeSelect.value;

                @unless($isBracketManaged)
                // Tim home & away tidak boleh sama
                if (homeTeamId && awayTeamId && homeTeamId === awayTeamId) {
                    e.preventDefault();
                    alert('Tim home dan tim away tidak boleh sama!');
                    return;
                }
                @endunless

                @if(!($isFriendly ?? false) && !$isBracketManaged)
                    // Turnamen wajib dipilih (non-friendly)
                    if (!tournamentSelect.value) {
                        e.preventDefault();
                        alert('Silakan pilih turnamen');
                        tournamentSelect.focus();
                        return;
                    }
                @endif

                @unless($isBracketManaged)
                // Validasi khusus group stage
                if (roundType === 'group') {
                    if (!groupNameSelect.value) {
                        e.preventDefault();
                        alert('Silakan pilih nama grup untuk pertandingan group stage');
                        groupNameSelect.focus();
                        return;
                    }

                    const homeGroup = teamGroups[homeTeamId];
                    const awayGroup = teamGroups[awayTeamId];

                    if (!homeGroup || !awayGroup) {
                        e.preventDefault();
                        alert('Satu atau kedua tim belum tergabung dalam grup. Silakan pilih tim lain.');
                        return;
                    }

                    if (homeGroup !== awayGroup) {
                        e.preventDefault();
                        alert(`Tidak dapat membuat pertandingan group stage: tim berada di grup berbeda (Group ${homeGroup} vs Group ${awayGroup}). Silakan pilih tim dari grup yang sama.`);
                        return;
                    }

                    if (groupNameSelect.value !== homeGroup) {
                        e.preventDefault();
                        alert(`Nama grup (${groupNameSelect.value}) tidak sesuai dengan grup tim (${homeGroup}). Silakan pilih grup yang benar.`);
                        return;
                    }
                }
                @endunless

                // Urutan waktu
                const start = timeStartInput.value;
                const end = timeEndInput.value;

                if (start && end && start >= end) {
                    e.preventDefault();
                    alert('Waktu selesai harus setelah waktu mulai');
                    timeEndInput.focus();
                }
            });
        });
    </script>
@endsection
