{{-- resources/views/admin/hero-settings/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Pengaturan Hero - OFS Futsal Center Admin')

@section('styles')
<style>
/* Header */
.admin-header {
    background: var(--bg-card, white);
    border: 1px solid var(--border, #e5e5e7);
    border-radius: 12px;
    padding: 16px 24px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.admin-header-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-primary, #111113);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-back {
    border: 1px solid var(--border, #e5e5e7);
    background: var(--bg-card, white);
    color: var(--text-primary, #111113);
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.15s ease;
}

.btn-back:hover {
    border-color: var(--accent, #c01c28);
    color: var(--accent, #c01c28);
}

/* Form */
.settings-card {
    background: var(--bg-card, white);
    border: 1px solid var(--border, #e5e5e7);
    border-radius: 12px;
    padding: 24px;
}

.form-label {
    font-weight: 500;
    color: var(--text-primary, #111113);
    margin-bottom: 6px;
    font-size: 14px;
}

.form-control,
.form-select {
    border: 1px solid var(--border, #e5e5e7);
    border-radius: 6px;
    padding: 10px 12px;
    font-size: 14px;
    transition: all 0.15s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--accent, #c01c28);
    box-shadow: 0 0 0 3px rgba(192, 28, 40, 0.1);
}

.form-text {
    color: var(--text-secondary, #6b6b70);
    font-size: 13px;
}

.form-check-input:checked {
    background-color: var(--accent, #c01c28);
    border-color: var(--accent, #c01c28);
}

/* Pengelompokan field */
.settings-group {
    padding-top: 20px;
    margin-top: 4px;
    border-top: 1px solid var(--border, #e5e5e7);
}

.settings-group:first-child {
    padding-top: 0;
    margin-top: 0;
    border-top: none;
}

.group-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary, #111113);
    margin-bottom: 12px;
}

/* Preview media */
.review-media {
    max-width: 200px;
    border-radius: 6px;
    border: 1px solid var(--border, #e5e5e7);
}

/* Aksi */
.btn-submit {
    background: var(--accent, #c01c28);
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
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="admin-header">
        <h1 class="admin-header-title">
            <i class="bi bi-sliders"></i> Pengaturan hero section
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="settings-card">
        <form action="{{ route('admin.hero-settings.update') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="settings-group">
                <div class="group-label">Konten</div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group mb-3">
                            <label class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control"
                                value="{{ old('title', $heroSetting->title) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label">Warna teks</label>
                            <input type="color" name="text_color" class="form-control form-control-color"
                                value="{{ old('text_color', $heroSetting->text_color ?? '#ffffff') }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Subjudul <span class="text-danger">*</span></label>
                    <textarea name="subtitle" class="form-control" rows="3"
                        required>{{ old('subtitle', $heroSetting->subtitle) }}</textarea>
                </div>
            </div>

            <div class="settings-group">
                <div class="group-label">Tombol CTA</div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label">Teks tombol</label>
                            <input type="text" name="cta_button_text" class="form-control"
                                value="{{ old('cta_button_text', $heroSetting->cta_button_text) }}"
                                placeholder="misal: Lihat Jadwal">
                            <div class="form-text">Kosongkan untuk menyembunyikan tombol.</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label">Link tombol</label>
                            <input type="text" name="cta_button_link" class="form-control"
                                value="{{ old('cta_button_link', $heroSetting->cta_button_link) }}"
                                placeholder="misal: /schedule">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label class="form-label">Warna tombol</label>
                            <input type="color" name="button_color" class="form-control form-control-color"
                                value="{{ old('button_color', $heroSetting->button_color ?? '#3b82f6') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label class="form-label">Warna teks tombol</label>
                            <input type="color" name="button_text_color" class="form-control form-control-color"
                                value="{{ old('button_text_color', $heroSetting->button_text_color ?? '#ffffff') }}">
                        </div>
                    </div>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                        value="1" {{ $heroSetting->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Tampilkan di halaman utama
                    </label>
                </div>
            </div>

            <div class="settings-group">
                <div class="group-label">Latar belakang</div>

                <div class="form-group mb-3">
                    <label class="form-label">Tipe latar</label>
                    <select name="background_type" class="form-select" id="background-type">
                        <option value="gradient" {{ $heroSetting->background_type == 'gradient' ? 'selected' : '' }}>
                            Gradient
                        </option>
                        <option value="image" {{ $heroSetting->background_type == 'image' ? 'selected' : '' }}>
                            Gambar
                        </option>
                        <option value="color" {{ $heroSetting->background_type == 'color' ? 'selected' : '' }}>
                            Warna solid
                        </option>
                    </select>
                </div>

                <div id="color-field" class="form-group mb-3"
                    style="display: {{ $heroSetting->background_type == 'color' ? 'block' : 'none' }}">
                    <label class="form-label">Warna latar</label>
                    <input type="color" name="background_color" class="form-control form-control-color"
                        value="{{ old('background_color', $heroSetting->background_color ?? '#0f172a') }}">
                </div>

                <div id="gradient-fields" class="form-group mb-3"
                    style="display: {{ $heroSetting->background_type == 'gradient' ? 'block' : 'none' }}">
                    <label class="form-label">Warna gradient</label>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label form-text">Warna awal</label>
                            <input type="color" name="gradient_start" class="form-control form-control-color"
                                value="{{ old('gradient_start', $heroSetting->gradient_start ?? '#0f172a') }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label form-text">Warna akhir</label>
                            <input type="color" name="gradient_end" class="form-control form-control-color"
                                value="{{ old('gradient_end', $heroSetting->gradient_end ?? '#1e293b') }}">
                        </div>
                    </div>
                </div>

                <div id="image-field" class="form-group mb-3"
                    style="display: {{ $heroSetting->background_type == 'image' ? 'block' : 'none' }}">
                    <label class="form-label">Gambar latar</label>

                    @if($heroSetting->background_image)
                        <div class="mb-3">
                            <img src="{{ Storage::url($heroSetting->background_image) }}" alt="Latar saat ini"
                                class="review-media">
                            <div class="form-check mt-2">
                                <input type="checkbox" name="remove_image" class="form-check-input"
                                    id="remove_image" value="1">
                                <label class="form-check-label" for="remove_image">
                                    Hapus gambar saat ini
                                </label>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="background_image" class="form-control" accept="image/*">
                    <div class="form-text">Ukuran yang disarankan: 1920×600px.</div>

                    <!-- Overlay Opacity Control -->
                    <div class="mt-3" id="overlay-control">
                        <label class="form-label">Opasitas overlay gambar</label>
                        <div class="d-flex align-items-center">
                            <input type="range" name="overlay_opacity" class="form-range" min="0" max="100"
                                step="1"
                                value="{{ old('overlay_opacity', $heroSetting->overlay_opacity ?? 50) }}"
                                id="overlay-opacity-slider">
                            <span class="ms-3" id="overlay-opacity-value">
                                {{ old('overlay_opacity', $heroSetting->overlay_opacity ?? 50) }}%
                            </span>
                        </div>
                        <div class="form-text">
                            Gelapnya lapisan di atas gambar latar (0% = tanpa overlay, 100% = hitam penuh).
                        </div>
                    </div>
                </div>
            </div>

            <div class="settings-group">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-lg"></i> Simpan perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const backgroundType = document.getElementById('background-type');
            const colorField = document.getElementById('color-field');
            const imageField = document.getElementById('image-field');
            const gradientFields = document.getElementById('gradient-fields');
            const overlayControl = document.getElementById('overlay-control');
            const overlaySlider = document.getElementById('overlay-opacity-slider');
            const overlayValue = document.getElementById('overlay-opacity-value');

            // Function to toggle fields
            function toggleFields() {
                const type = backgroundType.value;

                colorField.style.display = type === 'color' ? 'block' : 'none';
                imageField.style.display = type === 'image' ? 'block' : 'none';
                gradientFields.style.display = type === 'gradient' ? 'block' : 'none';

                // Show overlay control only for image background
                if (type === 'image') {
                    overlayControl.style.display = 'block';
                } else {
                    overlayControl.style.display = 'none';
                }
            }

            // Initial toggle
            toggleFields();

            // Add event listener
            backgroundType.addEventListener('change', toggleFields);

            // Update overlay opacity value display
            if (overlaySlider) {
                overlaySlider.addEventListener('input', function () {
                    overlayValue.textContent = this.value + '%';
                });
            }

            // Preview image before upload
            const imageInput = document.querySelector('input[name="background_image"]');
            if (imageInput) {
                imageInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            // Create preview image
                            const preview = document.createElement('div');
                            preview.className = 'mt-2';
                            preview.innerHTML = `
                                    <img src="${e.target.result}" alt="Preview"
                                         style="max-width: 200px; border-radius: 6px; border: 1px solid var(--border, #e5e5e7);">
                                    <div class="form-text mt-1">Preview gambar baru</div>
                                `;

                            // Remove existing preview
                            const existingPreview = document.querySelector('.image-preview');
                            if (existingPreview) {
                                existingPreview.remove();
                            }

                            preview.className = 'image-preview';
                            imageInput.parentNode.insertBefore(preview, imageInput.nextSibling);
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endpush