@extends('layouts.admin')

@section('title', 'Tambah Event')

@section('styles')
    <style>
        /* ===== Create match event page — design guidelines ===== */

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

        /* Cards */
        .form-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .form-card .card-header {
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            padding: 12px 16px;
        }

        .form-card .card-header h5 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .form-card .card-body {
            padding: 24px;
        }

        /* Match info */
        .score-badge {
            font-size: 20px;
            font-weight: 600;
            padding: 4px 16px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-primary);
            display: inline-block;
            min-width: 48px;
        }

        /* Form fields */
        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .required {
            color: #c01c28;
        }

        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Tambah Event Pertandingan</h1>
            <p class="page-subtitle">
                {{ $match->homeTeam->name ?? 'Tim Home' }} vs {{ $match->awayTeam->name ?? 'Tim Away' }}, {{ $match->match_date->format('d M Y') }}
            </p>
        </div>
        <a href="{{ route('admin.matches.events.index', $match) }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Events
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Info pertandingan -->
    <div class="form-card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-5 text-end">
                    <h5 class="mb-2">{{ $match->homeTeam->name ?? 'Tim Home' }}</h5>
                    <span class="score-badge">{{ $match->home_score ?? 0 }}</span>
                </div>
                <div class="col-md-2 text-center">
                    <span class="fw-bold text-secondary">VS</span>
                </div>
                <div class="col-md-5">
                    <h5 class="mb-2">{{ $match->awayTeam->name ?? 'Tim Away' }}</h5>
                    <span class="score-badge">{{ $match->away_score ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form event -->
    <div class="form-card">
        <div class="card-header">
            <h5>Detail Event</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.matches.events.store', $match) }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Tipe event -->
                    <div class="col-md-6 mb-3">
                        <label for="event_type" class="form-label">Tipe Event <span class="required">*</span></label>
                        <select class="form-select @error('event_type') is-invalid @enderror" id="event_type"
                            name="event_type" required>
                            <option value="">Pilih tipe event</option>
                            @foreach($eventTypes as $key => $value)
                                <option value="{{ $key }}" {{ old('event_type') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tim -->
                    <div class="col-md-6 mb-3">
                        <label for="team_id" class="form-label">Tim <span class="required">*</span></label>
                        <select class="form-select @error('team_id') is-invalid @enderror" id="team_id" name="team_id"
                            required>
                            <option value="">Pilih tim</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('team_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pemain -->
                    <div class="col-md-6 mb-3">
                        <label for="player_id" class="form-label">Pemain <span class="required">*</span></label>
                        <select class="form-select @error('player_id') is-invalid @enderror" id="player_id"
                            name="player_id" required>
                            <option value="">Pilih pemain</option>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}" {{ old('player_id') == $player->id ? 'selected' : '' }}
                                    data-position="{{ strtolower($player->position ?? '') }}">
                                    {{ $player->name }} ({{ $player->team->name ?? 'Tanpa Tim' }})
                                </option>
                            @endforeach
                        </select>
                        @error('player_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pemain terkait -->
                    <div class="col-md-6 mb-3">
                        <label for="related_player_id" class="form-label">Pemain Terkait (Opsional)</label>
                        <select class="form-select @error('related_player_id') is-invalid @enderror"
                            id="related_player_id" name="related_player_id">
                            <option value="">Pilih pemain terkait</option>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}" {{ old('related_player_id') == $player->id ? 'selected' : '' }}>
                                    {{ $player->name }} ({{ $player->team->name ?? 'Tanpa Tim' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Untuk assist atau substitusi</small>
                        @error('related_player_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Menit -->
                    <div class="col-md-3 mb-3">
                        <label for="minute" class="form-label">Menit <span class="required">*</span></label>
                        <input type="number" class="form-control @error('minute') is-invalid @enderror" id="minute"
                            name="minute" value="{{ old('minute') }}" min="1" max="120" required>
                        @error('minute')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Checkbox -->
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="is_own_goal" name="is_own_goal"
                                    value="1" {{ old('is_own_goal') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_own_goal">Gol Bunuh Diri</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="is_penalty" name="is_penalty" value="1"
                                    {{ old('is_penalty') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_penalty">Penalti</label>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-12 mb-0">
                        <label for="description" class="form-label">Deskripsi (Opsional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="3">{{ old('description') }}</textarea>
                        <small class="text-muted">Detail tambahan tentang event ini</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Aksi form -->
                <div class="d-flex justify-content-end align-items-center gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.matches.events.index', $match) }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Simpan Event
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Toggle field berdasarkan tipe event
        document.addEventListener('DOMContentLoaded', function () {
            const eventTypeSelect = document.getElementById('event_type');
            const isOwnGoalCheck = document.getElementById('is_own_goal');
            const isPenaltyCheck = document.getElementById('is_penalty');
            const relatedPlayerSelect = document.getElementById('related_player_id');
            const playerSelect = document.getElementById('player_id');

            function toggleFields() {
                const selectedEvent = eventTypeSelect.value;
                const isGoalkeeperEvent = selectedEvent === 'save' || selectedEvent === 'clean_sheet';

                // Filter pemain berdasarkan tipe event (kiper untuk save/clean sheet)
                Array.from(playerSelect.options).forEach(option => {
                    if (option.value === "") return;

                    const position = option.getAttribute('data-position') || '';
                    const isGoalkeeper = position.includes('goalkeeper') ||
                        position.includes('kiper') ||
                        position.includes('keeper') ||
                        position.includes('gk');

                    if (isGoalkeeperEvent) {
                        if (isGoalkeeper) {
                            option.hidden = false;
                            option.disabled = false;
                        } else {
                            option.hidden = true;
                            option.disabled = true;
                            if (option.selected) playerSelect.value = "";
                        }
                    } else {
                        option.hidden = false;
                        option.disabled = false;
                    }
                });

                // Checkbox own goal & penalti hanya untuk tipe goal
                if (selectedEvent === 'goal') {
                    isOwnGoalCheck.parentElement.style.display = 'inline-block';
                    isPenaltyCheck.parentElement.style.display = 'inline-block';
                } else {
                    isOwnGoalCheck.parentElement.style.display = 'none';
                    isPenaltyCheck.parentElement.style.display = 'none';
                    isOwnGoalCheck.checked = false;
                    isPenaltyCheck.checked = false;
                }

                // Pemain terkait hanya untuk goal & substitution
                if (selectedEvent === 'goal' || selectedEvent === 'substitution') {
                    relatedPlayerSelect.closest('.mb-3').style.display = 'block';
                } else {
                    relatedPlayerSelect.closest('.mb-3').style.display = 'none';
                    relatedPlayerSelect.value = '';
                }
            }

            eventTypeSelect.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
@endsection
