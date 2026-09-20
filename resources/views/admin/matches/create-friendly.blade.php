@extends('layouts.admin')

@section('title', 'Buat Friendly Match')

@section('styles')
    <style>
        /* ===== Create friendly match page — design guidelines ===== */

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

        .btn-friendly-alt {
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

        .btn-friendly-alt:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Form card */
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

        /* Form sections & fields */
        .form-section-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin: 0 0 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .required {
            color: #c01c28;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Buat Friendly Match</h1>
            <p class="page-subtitle">Buat pertandingan ujicoba (tidak terikat turnamen)</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.matches.create') }}" class="btn-friendly-alt">
                <i class="bi bi-trophy"></i> Match Turnamen
            </a>
            <a href="{{ route('admin.matches.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="form-card">
        <div class="card-header">
            <h5>Detail pertandingan</h5>
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
                        <div class="form-section-title">Informasi Dasar</div>

                        <!-- Tanggal -->
                        <div class="mb-3">
                            <label for="match_date" class="form-label">Tanggal Pertandingan <span class="required">*</span></label>
                            <input type="date" class="form-control @error('match_date') is-invalid @enderror"
                                id="match_date" name="match_date" value="{{ old('match_date') }}" required>
                            @error('match_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                        <div class="mb-3">
                            <label for="venue" class="form-label">Venue</label>
                            <input type="text" class="form-control @error('venue') is-invalid @enderror"
                                id="venue" name="venue" value="{{ old('venue', 'Main Field') }}"
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
                        <div class="form-section-title">Tim</div>

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

                        <div class="form-section-title" style="margin-top: 24px;">Skor (opsional)</div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="home_score" class="form-label">Skor Home</label>
                                <input type="number" min="0"
                                    class="form-control @error('home_score') is-invalid @enderror"
                                    id="home_score" name="home_score" value="{{ old('home_score') }}">
                                @error('home_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="away_score" class="form-label">Skor Away</label>
                                <input type="number" min="0"
                                    class="form-control @error('away_score') is-invalid @enderror"
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
                <div class="d-flex justify-content-end align-items-center gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.matches.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Buat Friendly Match
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
