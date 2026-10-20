@extends('layouts.admin')

@section('title', 'Buat Friendly Match')

@section('styles')
    <style>
        .admin-friendly-create {
            --friendly-ink: #111111;
            --friendly-charcoal: #39393b;
            --friendly-muted: #707072;
            --friendly-cloud: #f5f5f5;
            --friendly-border: #e5e5e5;
            --friendly-border-strong: #cacacb;
            --friendly-danger: #b42318;
            max-width: 1440px;
            margin-inline: auto;
            color: var(--friendly-ink);
        }

        .admin-friendly-create .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
        }

        .admin-friendly-create .page-header h1 {
            margin: 0 0 8px;
            color: var(--friendly-ink);
            font-size: clamp(26px, 3vw, 32px);
            font-weight: 600;
            letter-spacing: -0.035em;
            line-height: 1.15;
        }

        .admin-friendly-create .page-subtitle {
            max-width: 62ch;
            margin: 0;
            color: var(--friendly-muted);
            font-size: 15px;
            line-height: 1.5;
        }

        .admin-friendly-create .page-header-actions,
        .admin-friendly-create .match-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
        }

        .admin-friendly-create .page-header-actions .btn,
        .admin-friendly-create .match-actions .btn {
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
            text-decoration: none;
            transition: background-color .15s ease, color .15s ease;
        }

        .admin-friendly-create .page-header-actions .btn,
        .admin-friendly-create .match-actions .btn-outline-secondary {
            background: var(--friendly-cloud);
            color: var(--friendly-ink);
        }

        .admin-friendly-create .page-header-actions .btn:hover,
        .admin-friendly-create .match-actions .btn-outline-secondary:hover {
            background: #eaeaec;
            color: var(--friendly-ink);
        }

        .admin-friendly-create .form-card {
            overflow: hidden;
            border: 1px solid var(--friendly-border);
            border-radius: 8px;
            background: #ffffff;
            box-shadow: none;
        }

        .admin-friendly-create .form-card .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--friendly-border);
            background: #ffffff;
        }

        .admin-friendly-create .form-card .card-header h2 {
            margin: 0;
            color: var(--friendly-ink);
            font-size: 16px;
            font-weight: 600;
            line-height: 1.5;
        }

        .admin-friendly-create .form-card .card-body {
            padding: 24px;
        }

        .admin-friendly-create .form-section-title {
            margin: 0 0 18px;
            padding: 0 0 10px;
            border-bottom: 1px solid var(--friendly-border);
            color: var(--friendly-ink);
            font-size: 16px;
            font-weight: 600;
            line-height: 1.45;
        }

        .admin-friendly-create .form-label {
            margin-bottom: 7px;
            color: var(--friendly-charcoal);
            font-size: 14px;
            font-weight: 500;
        }

        .admin-friendly-create .required {
            color: var(--friendly-danger);
        }

        .admin-friendly-create .form-control,
        .admin-friendly-create .form-select {
            min-height: 44px;
            border: 1px solid var(--friendly-border-strong);
            border-radius: 8px;
            background-color: #ffffff;
            color: var(--friendly-ink);
            font-size: 14px;
            box-shadow: none;
        }

        .admin-friendly-create textarea.form-control {
            min-height: 96px;
            resize: vertical;
        }

        .admin-friendly-create .form-control:focus:not(.is-invalid),
        .admin-friendly-create .form-select:focus:not(.is-invalid) {
            border: 2px solid var(--friendly-ink);
            outline: none;
            box-shadow: none;
        }

        .admin-friendly-create .form-control.is-invalid:focus,
        .admin-friendly-create .form-select.is-invalid:focus {
            border-color: var(--friendly-danger);
            box-shadow: none;
        }

        .admin-friendly-create .invalid-feedback {
            font-size: 12px;
        }

        .admin-friendly-create .alert-danger {
            border: 1px solid #f0c9c5;
            border-radius: 8px;
        }

        .admin-friendly-create .match-actions {
            justify-content: flex-end;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--friendly-border);
        }

        .admin-friendly-create .match-actions .btn-primary {
            background: var(--friendly-ink);
            color: #ffffff;
        }

        .admin-friendly-create .match-actions .btn-primary:hover {
            background: var(--friendly-charcoal);
            color: #ffffff;
        }

        .admin-friendly-create :is(a, button):focus-visible {
            outline: 2px solid var(--friendly-ink);
            outline-offset: 2px;
        }

        .admin-friendly-create :is(.form-control, .form-select):focus-visible {
            border: 2px solid var(--friendly-ink);
            outline: none;
            box-shadow: none;
        }

        @media (max-width: 767.98px) {
            .admin-friendly-create .page-header {
                align-items: flex-start;
                margin-bottom: 22px;
            }

            .admin-friendly-create .form-card .card-header {
                padding: 16px 18px;
            }

            .admin-friendly-create .form-card .card-body {
                padding: 20px 18px;
            }
        }

        @media (max-width: 575.98px) {
            .admin-friendly-create .page-header {
                flex-direction: column;
                align-items: stretch;
                gap: 14px;
            }

            .admin-friendly-create .page-header-actions {
                width: 100%;
            }

            .admin-friendly-create .page-header-actions .btn {
                flex: 1 1 auto;
            }

            .admin-friendly-create .form-card .card-body {
                padding: 18px 16px;
            }

            .admin-friendly-create .match-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .admin-friendly-create .match-actions .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <main class="admin-friendly-create">
    <header class="page-header">
        <div>
            <h1>Buat Friendly Match</h1>
            <p class="page-subtitle">Jadwalkan pertandingan uji coba di luar turnamen.</p>
        </div>
        <nav class="page-header-actions" aria-label="Navigasi pertandingan">
            <a href="{{ route('admin.matches.create') }}" class="btn">
                <i class="bi bi-trophy" aria-hidden="true"></i>Match Turnamen
            </a>
            <a href="{{ route('admin.matches.index') }}" class="btn">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>Kembali
            </a>
        </nav>
    </header>

    <div class="form-card">
        <div class="card-header">
            <h2>Detail pertandingan</h2>
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

            <form action="{{ route('admin.matches.friendly.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <!-- Kolom kiri: info dasar -->
                    <div class="col-md-6">
                        <h2 class="form-section-title">Jadwal dan lokasi</h2>

                        <!-- Tanggal -->
                        <div class="mb-3">
                            <label for="match_date" class="form-label">Tanggal Pertandingan <span
                                    class="required">*</span></label>
                            <input type="date" class="form-control @error('match_date') is-invalid @enderror"
                                id="match_date" name="match_date" value="{{ old('match_date') }}" required>
                            @error('match_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Waktu -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="time_start" class="form-label">Waktu Mulai <span
                                        class="required">*</span></label>
                                <input type="time" class="form-control @error('time_start') is-invalid @enderror"
                                    id="time_start" name="time_start" value="{{ old('time_start', '14:00') }}" required>
                                @error('time_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="time_end" class="form-label">Waktu Selesai <span
                                        class="required">*</span></label>
                                <input type="time" class="form-control @error('time_end') is-invalid @enderror"
                                    id="time_end" name="time_end" value="{{ old('time_end', '15:40') }}" required>
                                @error('time_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Venue -->
                        <div class="mb-3">
                            <label for="venue" class="form-label">Venue</label>
                            <input type="text" class="form-control @error('venue') is-invalid @enderror" id="venue"
                                name="venue" value="{{ old('venue', 'Main Field') }}"
                                placeholder="contoh: Main Field, Lapangan 1">
                            @error('venue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-0">
                            <label for="status" class="form-label">Status <span class="required">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                required>
                                @foreach($statusOptions as $st)
                                    <option value="{{ $st }}" {{ old('status', 'upcoming') === $st ? 'selected' : '' }}>
                                        {{ ucfirst($st) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom kanan: tim & skor -->
                    <div class="col-md-6">
                        <h2 class="form-section-title">Peserta dan hasil</h2>

                        <!-- Tim home -->
                        <div class="mb-3">
                            <label for="team_home_id" class="form-label">Tim Home <span class="required">*</span></label>
                            <select class="form-select @error('team_home_id') is-invalid @enderror" id="team_home_id"
                                name="team_home_id" required>
                                <option value="">Pilih tim home</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('team_home_id') == $team->id ? 'selected' : '' }}>
                                        {{ $team->name }}
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
                                <option value="">Pilih tim away</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('team_away_id') == $team->id ? 'selected' : '' }}>
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('team_away_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h3 class="form-section-title mt-4">Skor (opsional)</h3>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="home_score" class="form-label">Skor Home</label>
                                <input type="number" min="0" class="form-control @error('home_score') is-invalid @enderror"
                                    id="home_score" name="home_score" value="{{ old('home_score') }}">
                                @error('home_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="away_score" class="form-label">Skor Away</label>
                                <input type="number" min="0" class="form-control @error('away_score') is-invalid @enderror"
                                    id="away_score" name="away_score" value="{{ old('away_score') }}">
                                @error('away_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-0">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                rows="3" placeholder="Catatan ujicoba, aturan khusus, dsb.">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Aksi form -->
                <div class="match-actions">
                    <a href="{{ route('admin.matches.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1" aria-hidden="true"></i>Buat Friendly Match
                    </button>
                </div>
            </form>
        </div>
    </div>
    </main>
@endsection
