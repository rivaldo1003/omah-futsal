@extends('layouts.admin')

@section('title', 'Edit Tim - ' . $team->name)

@section('styles')
<style>
/* Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
}

.page-header h1 {
    font-size: 20px;
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
    transition: all 0.15s ease;
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

.x-small {
    font-size: 12px;
}

.form-control,
.form-select {
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 14px;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(192, 28, 40, 0.1);
}

.form-text {
    color: var(--text-secondary);
    font-size: 13px;
}

.form-check-input:checked {
    background-color: var(--accent);
    border-color: var(--accent);
}

.form-check-input:disabled + .form-check-label {
    opacity: 0.6;
    cursor: not-allowed;
}

.form-control-color {
    width: 40px;
    padding: 4px;
}

/* Logo preview */
#logoPreview,
#logoPreviewCard {
    overflow: hidden;
}

#logoPreview img,
#logoPreviewCard img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 6px;
}

/* Selection boxes */
.selection-box {
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 8px;
    background: var(--surface);
    max-height: 200px;
    overflow-y: auto;
}

/* Actions */
.btn-submit {
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 6px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.15s ease;
}

.btn-submit:hover {
    background: var(--accent-hover, #a51822);
    color: white;
}

/* Current info */
.current-info-item {
    font-size: 13px;
}

.current-info-item .label {
    color: var(--text-secondary);
}

.current-info-item .value {
    color: var(--text-primary);
    font-weight: 500;
}
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>Edit tim: {{ $team->name }}</h1>
        <p class="page-subtitle">Perbarui informasi tim</p>
    </div>
    <a href="{{ route('admin.teams.show', $team) }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali ke detail
    </a>
</div>

<div class="row g-4">
    <!-- Form utama -->
    <div class="col-lg-8">
        <div class="form-card">
            <div class="card-header">
                <h5>Informasi tim</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.teams.update', $team) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Logo tim -->
                    <div class="form-section-title">Logo tim</div>
                    <div class="mb-4">
                        <div class="text-center mb-2">
                            <div id="logoPreview"
                                class="d-inline-flex align-items-center justify-content-center border rounded"
                                style="width: 120px; height: 120px; background: var(--surface);">
                                <div id="previewContainer"
                                    class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    @if($team->logo && (Storage::disk('public')->exists($team->logo) || filter_var($team->logo, FILTER_VALIDATE_URL)))
                                        <img src="{{ filter_var($team->logo, FILTER_VALIDATE_URL) ? $team->logo : asset('storage/' . $team->logo) }}"
                                            alt="{{ $team->name }}">
                                    @else
                                        <i class="bi bi-image text-secondary" style="font-size: 2rem;"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <input type="file"
                                class="form-control @error('logo') is-invalid @enderror"
                                id="logo" name="logo" accept="image/*">
                            <button type="button" class="btn-back" id="clearLogoBtn">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        @error('logo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-secondary d-block mt-2 x-small">
                            JPG, PNG, GIF, SVG, WebP • Maks 2MB • Rekomendasi: 200×200
                        </small>
                    </div>

                    <div class="form-section-title">Detail tim</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama tim <span class="required">*</span></label>
                            <input type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $team->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="short_name" class="form-label">Kode singkat</label>
                            <input type="text"
                                class="form-control @error('short_name') is-invalid @enderror"
                                id="short_name" name="short_name" value="{{ old('short_name', $team->short_name) }}"
                                maxlength="10">
                            @error('short_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section-title" style="margin-top: 24px;">Informasi pelatih</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="head_coach" class="form-label">Head coach</label>
                            <input type="text"
                                class="form-control @error('head_coach') is-invalid @enderror"
                                id="head_coach" name="head_coach" value="{{ old('head_coach', $team->head_coach) }}">
                            @error('head_coach')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="assistant_coach" class="form-label">Assistant coach</label>
                            <input type="text"
                                class="form-control @error('assistant_coach') is-invalid @enderror"
                                id="assistant_coach" name="assistant_coach" value="{{ old('assistant_coach', $team->assistant_coach) }}">
                            @error('assistant_coach')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="goalkeeper_coach" class="form-label">Goalkeeper coach</label>
                            <input type="text"
                                class="form-control @error('goalkeeper_coach') is-invalid @enderror"
                                id="goalkeeper_coach" name="goalkeeper_coach" value="{{ old('goalkeeper_coach', $team->goalkeeper_coach) }}">
                            @error('goalkeeper_coach')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="kitman" class="form-label">Kitman</label>
                            <input type="text"
                                class="form-control @error('kitman') is-invalid @enderror"
                                id="kitman" name="kitman" value="{{ old('kitman', $team->kitman) }}">
                            @error('kitman')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="coach_phone" class="form-label">Telepon coach</label>
                            <input type="text"
                                class="form-control @error('coach_phone') is-invalid @enderror"
                                id="coach_phone" name="coach_phone" value="{{ old('coach_phone', $team->coach_phone) }}">
                            @error('coach_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="coach_email" class="form-label">Email coach</label>
                            <input type="email"
                                class="form-control @error('coach_email') is-invalid @enderror"
                                id="coach_email" name="coach_email" value="{{ old('coach_email', $team->coach_email) }}">
                            @error('coach_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section-title" style="margin-top: 24px;">Warna tim</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="primary_color" class="form-label">Warna utama</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color"
                                    id="primary_color_picker" value="{{ old('primary_color', $team->primary_color ?: '#007bff') }}">
                                <input type="text"
                                    class="form-control @error('primary_color') is-invalid @enderror"
                                    id="primary_color" name="primary_color"
                                    value="{{ old('primary_color', $team->primary_color) }}"
                                    maxlength="7" placeholder="#RRGGBB">
                            </div>
                            @error('primary_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="secondary_color" class="form-label">Warna kedua</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color"
                                    id="secondary_color_picker" value="{{ old('secondary_color', $team->secondary_color ?: '#6c757d') }}">
                                <input type="text"
                                    class="form-control @error('secondary_color') is-invalid @enderror"
                                    id="secondary_color" name="secondary_color"
                                    value="{{ old('secondary_color', $team->secondary_color) }}"
                                    maxlength="7" placeholder="#RRGGBB">
                            </div>
                            @error('secondary_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section-title" style="margin-top: 24px;">Informasi kontak</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Telepon tim</label>
                            <input type="text"
                                class="form-control @error('phone') is-invalid @enderror"
                                id="phone" name="phone" value="{{ old('phone', $team->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email tim</label>
                            <input type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $team->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section-title" style="margin-top: 24px;">Venue & lainnya</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="home_venue" class="form-label">Home venue</label>
                            <input type="text"
                                class="form-control @error('home_venue') is-invalid @enderror"
                                id="home_venue" name="home_venue" value="{{ old('home_venue', $team->home_venue) }}">
                            @error('home_venue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="founded_year" class="form-label">Tahun berdiri</label>
                            <input type="number"
                                class="form-control @error('founded_year') is-invalid @enderror"
                                id="founded_year" name="founded_year" value="{{ old('founded_year', $team->founded_year) }}"
                                min="1900" max="{{ date('Y') }}">
                            @error('founded_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="website" class="form-label">Website</label>
                            <input type="url"
                                class="form-control @error('website') is-invalid @enderror"
                                id="website" name="website" value="{{ old('website', $team->website) }}"
                                placeholder="https://example.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="required">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status"
                                name="status" required>
                                <option value="">Pilih status</option>
                                <option value="active" {{ old('status', $team->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="pending" {{ old('status', $team->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="inactive" {{ old('status', $team->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                id="address" name="address" rows="2">{{ old('address', $team->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="3">{{ old('description', $team->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Pilih pemain -->
                    @if($players->count() > 0)
                        <div class="form-section-title" style="margin-top: 24px;">
                            Tugaskan pemain
                            <span class="fw-normal">({{ $players->where('team_id', null)->count() }} bebas,
                                {{ $players->where('team_id', $team->id)->count() }} di tim ini)</span>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-secondary">Pemain di tim lain tidak bisa dipilih</small>
                                <div>
                                    <button type="button" class="btn-xs btn-back" id="selectAllPlayers">Semua</button>
                                    <button type="button" class="btn-xs btn-back ms-1" id="deselectAllPlayers">Tidak ada</button>
                                </div>
                            </div>
                            <div class="selection-box">
                                <div class="row g-1">
                                    @foreach($players as $player)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="player_ids[]" value="{{ $player->id }}"
                                                    id="player_{{ $player->id }}"
                                                    {{ in_array($player->id, old('player_ids', $currentPlayers)) ? 'checked' : '' }}
                                                    {{ $player->team_id && $player->team_id != $team->id ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="player_{{ $player->id }}">
                                                    <span class="badge bg-light text-dark me-1">{{ $player->jersey_number ?? '#' }}</span>
                                                    {{ $player->name }}
                                                    @if($player->position)
                                                        <small class="text-secondary ms-1">{{ $player->position }}</small>
                                                    @endif
                                                    @if($player->team_id && $player->team_id != $team->id)
                                                        <small class="text-danger ms-1">({{ $player->team->name }})</small>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Belum ada pemain.
                            <a href="{{ route('admin.players.create') }}" class="alert-link">Buat pemain terlebih dahulu</a>
                        </div>
                    @endif

                    <!-- Pilih turnamen -->
                    @if($tournaments->count() > 0)
                        <div class="form-section-title" style="margin-top: 24px;">Daftarkan ke turnamen</div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-secondary">Centang turnamen yang diikuti tim ini</small>
                                <div>
                                    <button type="button" class="btn-xs btn-back" id="selectAllTournaments">Semua</button>
                                    <button type="button" class="btn-xs btn-back ms-1" id="deselectAllTournaments">Tidak ada</button>
                                </div>
                            </div>
                            <div class="selection-box">
                                @foreach($tournaments as $tournament)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox"
                                            name="tournament_ids[]" value="{{ $tournament->id }}"
                                            id="tournament_{{ $tournament->id }}"
                                            {{ in_array($tournament->id, old('tournament_ids', $currentTournaments)) ? 'checked' : '' }}>
                                        <label class="form-check-label d-flex justify-content-between"
                                            for="tournament_{{ $tournament->id }}">
                                            <span>{{ $tournament->name }}</span>
                                            <small class="text-secondary">{{ $tournament->type_label ?? '' }}</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Aksi form -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-1"></i> Hapus tim
                        </button>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.teams.show', $team) }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn-submit">
                                <i class="bi bi-check-lg"></i> Simpan perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Info tim saat ini -->
        <div class="form-card mb-4">
            <div class="card-header">
                <h5>Info tim saat ini</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="team-logo-container" style="width: 40px; height: 40px; margin-right: 12px;">
                        @if($team->logo && (Storage::disk('public')->exists($team->logo) || filter_var($team->logo, FILTER_VALIDATE_URL)))
                            <img src="{{ filter_var($team->logo, FILTER_VALIDATE_URL) ? $team->logo : asset('storage/' . $team->logo) }}"
                                alt="{{ $team->name }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center w-100 h-100"
                                style="color: var(--text-secondary); font-weight: 600;">
                                {{ strtoupper(substr($team->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="fw-medium" style="font-size: 14px;">{{ $team->name }}</div>
                        @if($team->short_name)
                            <small class="text-secondary">({{ $team->short_name }})</small>
                        @endif
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-6 current-info-item">
                        <div class="label">Status</div>
                        <div class="value">{{ ucfirst($team->status) }}</div>
                    </div>
                    <div class="col-6 current-info-item">
                        <div class="label">Pemain</div>
                        <div class="value">{{ $team->players->count() }}</div>
                    </div>
                    <div class="col-6 current-info-item">
                        <div class="label">Turnamen</div>
                        <div class="value">{{ $team->tournaments->count() }}</div>
                    </div>
                    <div class="col-6 current-info-item">
                        <div class="label">Dibuat</div>
                        <div class="value">{{ $team->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview logo -->
        <div class="form-card">
            <div class="card-header">
                <h5>Preview logo</h5>
            </div>
            <div class="card-body text-center">
                <div id="logoPreviewCard"
                    class="d-flex align-items-center justify-content-center border rounded mx-auto"
                    style="min-height: 100px; background: var(--surface);">
                    <div id="cardPreviewContainer"
                        class="w-100 h-100 d-flex align-items-center justify-content-center">
                        @if($team->logo && (Storage::disk('public')->exists($team->logo) || filter_var($team->logo, FILTER_VALIDATE_URL)))
                            <img src="{{ filter_var($team->logo, FILTER_VALIDATE_URL) ? $team->logo : asset('storage/' . $team->logo) }}"
                                alt="{{ $team->name }}">
                        @else
                            <i class="bi bi-image text-secondary" style="font-size: 1.5rem;"></i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus tim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small">
                <p class="mb-2">Hapus <strong>{{ $team->name }}</strong>?</p>
                <div class="alert alert-warning py-1 mb-2">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Tindakan ini tidak bisa dibatalkan.
                </div>
                <div class="text-secondary x-small">
                    {{ $team->players->count() }} pemain • {{ $team->tournaments->count() }} turnamen
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Preview logo
window.previewImage = function (input) {
    const file = input.files[0];

    if (file && file.type.startsWith('image/')) {
        const MAX_SIZE = 2 * 1024 * 1024;
        if (file.size > MAX_SIZE) {
            alert('Ukuran file harus kurang dari 2MB');
            clearImage();
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('previewContainer').innerHTML =
                `<img src="${e.target.result}">`;
            document.getElementById('cardPreviewContainer').innerHTML =
                `<img src="${e.target.result}">`;
        };

        reader.readAsDataURL(file);
    }
}

window.clearImage = function () {
    const logoInput = document.getElementById('logo');
    if (logoInput) {
        logoInput.value = '';
    }

    @if($team->logo && (Storage::disk('public')->exists($team->logo) || filter_var($team->logo, FILTER_VALIDATE_URL)))
    const originalLogo = `<img src="{{ filter_var($team->logo, FILTER_VALIDATE_URL) ? $team->logo : asset('storage/' . $team->logo) }}">`;
    document.getElementById('previewContainer').innerHTML = originalLogo;
    document.getElementById('cardPreviewContainer').innerHTML = originalLogo;
    @else
    document.getElementById('previewContainer').innerHTML =
        '<i class="bi bi-image text-secondary" style="font-size: 2rem;"></i>';
    document.getElementById('cardPreviewContainer').innerHTML =
        '<i class="bi bi-image text-secondary" style="font-size: 1.5rem;"></i>';
    @endif
}

document.addEventListener('DOMContentLoaded', function () {
    // Upload logo
    const logoInput = document.getElementById('logo');
    const clearLogoBtn = document.getElementById('clearLogoBtn');

    if (logoInput) {
        logoInput.addEventListener('change', function () {
            previewImage(this);
        });
    }

    if (clearLogoBtn) {
        clearLogoBtn.addEventListener('click', clearImage);
    }

    // Color picker
    const primaryColorInput = document.getElementById('primary_color');
    const secondaryColorInput = document.getElementById('secondary_color');
    const primaryColorPicker = document.getElementById('primary_color_picker');
    const secondaryColorPicker = document.getElementById('secondary_color_picker');

    if (primaryColorInput && primaryColorPicker) {
        primaryColorInput.addEventListener('input', function () {
            if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                primaryColorPicker.value = this.value;
            }
        });

        primaryColorPicker.addEventListener('input', function () {
            primaryColorInput.value = this.value;
        });
    }

    if (secondaryColorInput && secondaryColorPicker) {
        secondaryColorInput.addEventListener('input', function () {
            if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                secondaryColorPicker.value = this.value;
            }
        });

        secondaryColorPicker.addEventListener('input', function () {
            secondaryColorInput.value = this.value;
        });
    }

    // Pilih semua / batal pilih pemain
    const selectAllPlayersBtn = document.getElementById('selectAllPlayers');
    const deselectAllPlayersBtn = document.getElementById('deselectAllPlayers');
    const playerCheckboxes = document.querySelectorAll('input[name="player_ids[]"]:not(:disabled)');

    if (selectAllPlayersBtn && playerCheckboxes.length > 0) {
        selectAllPlayersBtn.addEventListener('click', () => {
            playerCheckboxes.forEach(cb => cb.checked = true);
        });
    }

    if (deselectAllPlayersBtn && playerCheckboxes.length > 0) {
        deselectAllPlayersBtn.addEventListener('click', () => {
            document.querySelectorAll('input[name="player_ids[]"]').forEach(cb => cb.checked = false);
        });
    }

    // Pilih semua / batal pilih turnamen
    const selectAllTournamentsBtn = document.getElementById('selectAllTournaments');
    const deselectAllTournamentsBtn = document.getElementById('deselectAllTournaments');
    const tournamentCheckboxes = document.querySelectorAll('input[name="tournament_ids[]"]');

    if (selectAllTournamentsBtn && tournamentCheckboxes.length > 0) {
        selectAllTournamentsBtn.addEventListener('click', () => {
            tournamentCheckboxes.forEach(cb => cb.checked = true);
        });
    }

    if (deselectAllTournamentsBtn && tournamentCheckboxes.length > 0) {
        deselectAllTournamentsBtn.addEventListener('click', () => {
            tournamentCheckboxes.forEach(cb => cb.checked = false);
        });
    }

    // Kode singkat otomatis uppercase
    const shortNameInput = document.getElementById('short_name');
    if (shortNameInput) {
        shortNameInput.addEventListener('input', function () {
            this.value = this.value.toUpperCase();
        });
    }

    // Validasi tahun berdiri
    const yearInput = document.getElementById('founded_year');
    if (yearInput) {
        yearInput.addEventListener('blur', function () {
            const year = parseInt(this.value);
            if (this.value && (year < 1900 || year > new Date().getFullYear())) {
                this.value = '{{ old('founded_year', $team->founded_year) }}';
                alert('Masukkan tahun yang valid antara 1900 dan ' + new Date().getFullYear());
            }
        });
    }

    // Format nomor telepon
    const phoneInputs = document.querySelectorAll('input[name="phone"], input[name="coach_phone"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9+\-\s()]/g, '');
        });
    });
});
</script>