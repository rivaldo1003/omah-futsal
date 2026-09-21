@extends('layouts.admin')

@section('title', 'Edit Pemain - ' . $player->name)

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

/* Photo preview */
.player-photo-preview {
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: var(--surface);
}

.player-photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.player-photo-fallback-edit {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--surface);
    color: var(--text-secondary);
    font-size: 36px;
    font-weight: 600;
}

/* Alert */
.alert {
    border-radius: 6px;
    padding: 12px 16px;
    font-size: 14px;
    margin-bottom: 16px;
    border: none;
}
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>Edit pemain: {{ $player->name }}</h1>
        <p class="page-subtitle">Perbarui informasi pemain</p>
    </div>
    <a href="{{ route('admin.players.show', $player) }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali ke detail
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Perbaiki kesalahan berikut:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.players.update', $player) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')
<div class="row g-4">
    <!-- Kolom kiri: foto -->
    <div class="col-lg-4">
        <div class="form-card">
            <div class="card-header">
                <h5>Foto pemain</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="player-photo-preview mx-auto rounded"
                        style="width: 150px; height: 150px;" id="photoPreview">
                        @php
                            $playerPhoto = $player->photo ?? null;
                            $nameParts = explode(' ', $player->name);
                            $initials = count($nameParts) >= 2
                                ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                                : strtoupper(substr($player->name, 0, 2));

                            $photoPath = null;
                            if ($playerPhoto) {
                                if (filter_var($playerPhoto, FILTER_VALIDATE_URL)) {
                                    $photoPath = $playerPhoto;
                                } elseif (Storage::disk('public')->exists($playerPhoto)) {
                                    $photoPath = asset('storage/' . $playerPhoto);
                                }
                            }
                        @endphp

                        @if($photoPath)
                            <img src="{{ $photoPath }}" alt="{{ $player->name }}" id="previewImage"
                                onerror="handleImageError(this)">
                        @endif
                        <div class="player-photo-fallback-edit" id="photoFallback"
                            style="{{ $photoPath ? 'display: none;' : '' }}">
                            {{ $initials }}
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label">Ganti foto</label>
                    <input type="file" class="form-control @error('photo') is-invalid @enderror"
                        id="photo" name="photo" accept="image/*" onchange="previewImage(event)">
                    <div class="form-text">Maks 2MB. Format: JPG, PNG, GIF, WebP</div>
                    @error('photo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                @if($photoPath)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="removePhoto"
                            name="remove_photo">
                        <label class="form-check-label" for="removePhoto" style="font-size: 14px;">
                            Hapus foto saat ini
                        </label>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kolom kanan: detail pemain -->
    <div class="col-lg-8">
        <div class="form-card">
            <div class="card-header">
                <h5>Informasi pemain</h5>
            </div>

            <div class="card-body">


                    <div class="form-section-title">Informasi dasar</div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama lengkap <span class="required">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $player->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="jersey_number" class="form-label">Nomor punggung</label>
                            <input type="number" class="form-control @error('jersey_number') is-invalid @enderror"
                                id="jersey_number" name="jersey_number"
                                value="{{ old('jersey_number', $player->jersey_number) }}" min="0" max="99">
                            @error('jersey_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section-title" style="margin-top: 24px;">Tim & posisi</div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="team_id" class="form-label">Tim</label>
                            <select class="form-select @error('team_id') is-invalid @enderror" id="team_id"
                                name="team_id">
                                <option value="">Tanpa tim</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('team_id', $player->team_id) == $team->id ? 'selected' : '' }}>
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('team_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="position" class="form-label">Posisi</label>
                            <select class="form-select @error('position') is-invalid @enderror" id="position"
                                name="position">
                                <option value="">Pilih posisi</option>
                                <option value="Flank" {{ old('position', $player->position) == 'Flank' ? 'selected' : '' }}>Flank</option>
                                <option value="Anchor" {{ old('position', $player->position) == 'Anchor' ? 'selected' : '' }}>Anchor</option>
                                <option value="Pivot" {{ old('position', $player->position) == 'Pivot' ? 'selected' : '' }}>Pivot</option>
                                <option value="Kiper" {{ old('position', $player->position) == 'Kiper' ? 'selected' : '' }}>Kiper</option>
                            </select>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section-title" style="margin-top: 24px;">Informasi tambahan</div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="birth_date" class="form-label">Tanggal lahir</label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                id="birth_date" name="birth_date"
                                value="{{ old('birth_date', $player->birth_date?->format('Y-m-d')) }}"
                                max="{{ now()->format('Y-m-d') }}">
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="birth_place" class="form-label">Tempat lahir</label>
                            <input type="text" class="form-control @error('birth_place') is-invalid @enderror"
                                id="birth_place" name="birth_place"
                                value="{{ old('birth_place', $player->birth_place) }}" maxlength="100">
                            @error('birth_place')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="market_value" class="form-label">Market Value (Nilai Pasar)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('market_value') is-invalid @enderror"
                                    id="market_value" name="market_value"
                                    value="{{ old('market_value', $player->market_value) }}"
                                    placeholder="contoh: 25000000" min="0" step="100000">
                            </div>
                            @error('market_value')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Nominal dalam Rupiah (opsional, contoh: 25000000 untuk 25 jt)</div>
                        </div>
                    </div>

                    <!-- Aksi form -->
                    <div class="d-flex justify-content-end align-items-center gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('admin.players.show', $player) }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan perubahan
                        </button>
                    </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@section('scripts')
<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('previewImage');
    const fallback = document.getElementById('photoFallback');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            if (!preview) {
                // Buat elemen image jika belum ada
                const img = document.createElement('img');
                img.id = 'previewImage';
                img.alt = 'Preview';
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';

                const previewContainer = document.getElementById('photoPreview');
                previewContainer.insertBefore(img, fallback);
                img.src = e.target.result;
            } else {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }

            if (fallback) {
                fallback.style.display = 'none';
            }
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function handleImageError(img) {
    img.style.display = 'none';
    const fallback = document.getElementById('photoFallback');
    if (fallback) {
        fallback.style.display = 'flex';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Handle remove photo checkbox
    const removePhotoCheckbox = document.getElementById('removePhoto');
    const photoInput = document.getElementById('photo');

    if (removePhotoCheckbox) {
        removePhotoCheckbox.addEventListener('change', function () {
            if (this.checked) {
                photoInput.disabled = true;
                photoInput.value = '';

                // Sembunyikan preview, tampilkan fallback
                const preview = document.getElementById('previewImage');
                const fallback = document.getElementById('photoFallback');

                if (preview) preview.style.display = 'none';
                if (fallback) fallback.style.display = 'flex';
            } else {
                photoInput.disabled = false;
            }
        });
    }

    // Auto-dismiss alert
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