{{-- resources/views/admin/hero-settings/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Pengaturan Halaman Utama - OFS Futsal Center Admin')

@section('styles')
<style>
    .hero-settings-page {
        --settings-ink: #111111;
        --settings-charcoal: #39393b;
        --settings-muted: #707072;
        --settings-line: #cacacb;
        --settings-line-soft: #e5e5e5;
        --settings-cloud: #f5f5f5;
        --settings-white: #ffffff;
        width: 100%;
        max-width: 1120px;
        margin: 0 auto;
        color: var(--settings-ink);
        font-size: 15px;
        line-height: 1.5;
    }

    .hero-settings-page .admin-header {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: center;
        gap: 20px 24px;
        margin-bottom: 24px;
    }

    .hero-settings-page .admin-header-copy {
        min-width: 0;
    }

    .hero-settings-page .admin-header-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 8px;
        color: var(--settings-ink);
        font-size: clamp(1.75rem, 2.5vw, 2rem);
        font-weight: 500;
        line-height: 1.2;
        letter-spacing: -.02em;
    }

    .hero-settings-page .admin-header-title i {
        font-size: .8em;
    }

    .hero-settings-page .admin-header-subtitle {
        max-width: 68ch;
        margin: 0;
        color: var(--settings-muted);
        font-size: 14px;
    }

    .hero-settings-page .btn-back,
    .hero-settings-page .btn-submit {
        min-height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 20px;
        border: 1px solid transparent;
        border-radius: 9999px;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.4;
        text-decoration: none;
        white-space: nowrap;
        box-shadow: none;
        transition: background-color 150ms ease-out, border-color 150ms ease-out, color 150ms ease-out;
    }

    .hero-settings-page .btn-back {
        background: var(--settings-cloud);
        color: var(--settings-ink);
    }

    .hero-settings-page .btn-back:hover {
        background: #eeeeee;
        color: var(--settings-ink);
    }

    .hero-settings-page .btn-submit {
        border-color: var(--settings-ink);
        background: var(--settings-ink);
        color: var(--settings-white);
        cursor: pointer;
    }

    .hero-settings-page .btn-submit:hover {
        border-color: var(--settings-charcoal);
        background: var(--settings-charcoal);
        color: var(--settings-white);
    }

    .hero-settings-page .settings-card {
        margin-bottom: 20px;
        padding: 22px 24px;
        border: 1px solid var(--settings-line-soft);
        border-radius: 8px;
        background: var(--settings-white);
        box-shadow: none;
    }

    .hero-settings-page .settings-card h2 {
        margin: 0 0 6px;
        color: var(--settings-ink);
        font-size: 16px;
        font-weight: 500;
        line-height: 1.4;
    }

    .hero-settings-page .settings-card .card-desc {
        margin: 0 0 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--settings-line-soft);
        color: var(--settings-muted);
        font-size: 13px;
    }

    .hero-settings-page .form-label {
        margin-bottom: 7px;
        color: var(--settings-charcoal);
        font-size: 14px;
        font-weight: 500;
    }

    .hero-settings-page .form-control,
    .hero-settings-page .form-select {
        min-height: 46px;
        border: 1px solid var(--settings-line);
        border-radius: 8px;
        background-color: var(--settings-white);
        color: var(--settings-ink);
        font: inherit;
        font-size: 14px;
        box-shadow: none;
        transition: border-color 150ms ease-out, box-shadow 150ms ease-out;
    }

    .hero-settings-page textarea.form-control {
        min-height: 96px;
        resize: vertical;
    }

    .hero-settings-page .form-control:focus,
    .hero-settings-page .form-select:focus {
        border-color: var(--settings-ink);
        box-shadow: 0 0 0 3px var(--settings-cloud);
    }

    .hero-settings-page input.form-control-color {
        width: 82px;
        height: 48px;
        min-height: 48px;
        padding: 5px;
        border-radius: 8px;
        cursor: pointer;
    }

    .hero-settings-page .form-text {
        margin-top: 7px;
        color: var(--settings-muted);
        font-size: 12px;
        line-height: 1.5;
    }

    .hero-settings-page .form-check-input {
        border-color: var(--settings-line);
        box-shadow: none;
    }

    .hero-settings-page .form-check-input:checked {
        border-color: var(--settings-ink);
        background-color: var(--settings-ink);
    }

    .hero-settings-page .form-check-input:focus {
        border-color: var(--settings-ink);
        box-shadow: 0 0 0 3px var(--settings-cloud);
    }

    .hero-settings-page .form-check-label {
        color: var(--settings-charcoal);
        font-size: 13px;
    }

    .hero-settings-page .toggle-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 0;
    }

    .hero-settings-page .toggle-row + .toggle-row {
        border-top: 1px solid var(--settings-line-soft);
    }

    .hero-settings-page .toggle-row .form-check-input {
        flex: 0 0 auto;
        margin-top: 3px;
    }

    .hero-settings-page .toggle-label {
        color: var(--settings-ink);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
    }

    .hero-settings-page .toggle-desc {
        margin-top: 3px;
        color: var(--settings-muted);
        font-size: 12px;
        line-height: 1.45;
    }

    .hero-settings-page .review-media,
    .hero-settings-page .image-preview img {
        display: block;
        width: min(100%, 480px);
        max-height: 260px;
        aspect-ratio: 16 / 6;
        object-fit: cover;
        border: 1px solid var(--settings-line-soft);
        border-radius: 8px;
        background: var(--settings-cloud);
    }

    .hero-settings-page .image-preview {
        margin-top: 10px;
    }

    .hero-settings-page #overlay-opacity-value {
        width: 48px;
        flex: 0 0 48px;
        color: var(--settings-charcoal);
        font-variant-numeric: tabular-nums;
        text-align: right;
    }

    .hero-settings-page .alert-success,
    .hero-settings-page .alert-danger {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 20px;
        padding: 14px 16px;
        border: 1px solid var(--settings-line-soft);
        border-radius: 8px;
        font-size: 14px;
        line-height: 1.5;
        box-shadow: none;
    }

    .hero-settings-page .alert-success {
        border-color: #d4e9dd;
        background: #edf6f1;
        color: #007d48;
    }

    .hero-settings-page .alert-danger {
        border-color: #e8c8c5;
        background: #fdf1f0;
        color: #b42318;
    }

    .hero-settings-page .alert-danger ul {
        margin: 6px 0 0;
        padding-left: 18px;
    }

    .hero-settings-page .action-bar {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 4px;
        padding: 18px 0 4px;
        border-top: 1px solid var(--settings-line-soft);
    }

    .hero-settings-page :is(a, button, input, select, textarea):focus-visible {
        outline: 2px solid var(--settings-ink);
        outline-offset: 3px;
    }

    @media (max-width: 767.98px) {
        .hero-settings-page .admin-header {
            grid-template-columns: minmax(0, 1fr);
            align-items: start;
        }

        .hero-settings-page .admin-header .btn-back,
        .hero-settings-page .action-bar .btn-submit {
            width: 100%;
        }

        .hero-settings-page .settings-card {
            padding: 18px;
        }

        .hero-settings-page .action-bar {
            padding-top: 16px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .hero-settings-page * {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
        }
    }
</style>
@endsection

@section('content')
<div class="hero-settings-page">
    <div class="admin-header">
        <div class="admin-header-copy">
            <h1 class="admin-header-title">
                <i class="bi bi-sliders"></i> Pengaturan Halaman Utama
            </h1>
            <p class="admin-header-subtitle">Atur banner, teks, tombol, dan tampilan utama untuk pengunjung.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-danger" role="alert">
            <i class="bi bi-exclamation-circle"></i>
            <div>
                <strong>Periksa kembali isian berikut:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.hero-settings.update') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="settings-card">
            <h2>Tampilan</h2>
            <p class="card-desc">Atur bagian mana yang tampil di halaman utama.</p>

            <div class="toggle-row">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                    value="1" {{ old('is_active', $heroSetting->is_active) ? 'checked' : '' }}>
                <div>
                    <label class="toggle-label" for="is_active">Banner utama (hero)</label>
                    <div class="toggle-desc">Banner besar di bagian paling atas halaman utama.</div>
                </div>
            </div>

            <div class="toggle-row">
                <input type="checkbox" name="show_market_value_stars" class="form-check-input"
                    id="show_market_value_stars" value="1"
                    {{ old('show_market_value_stars', $heroSetting->show_market_value_stars ?? true) ? 'checked' : '' }}>
                <div>
                    <label class="toggle-label" for="show_market_value_stars">Market Value Stars</label>
                    <div class="toggle-desc">Showcase pemain dengan nilai pasar tertinggi.</div>
                </div>
            </div>
        </div>

        <div class="settings-card">
            <h2>Konten banner</h2>
            <p class="card-desc">Teks yang tampil di dalam banner utama.</p>

            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="title" class="form-control"
                    value="{{ old('title', $heroSetting->title) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Subjudul</label>
                <textarea name="subtitle" class="form-control" rows="2">{{ old('subtitle', $heroSetting->subtitle) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Warna teks</label>
                    <input type="color" name="text_color" class="form-control form-control-color"
                        value="{{ old('text_color', $heroSetting->text_color ?? '#ffffff') }}">
                </div>
            </div>
        </div>

        <div class="settings-card">
            <h2>Tombol CTA</h2>
            <p class="card-desc">Tombol aksi di dalam banner. Kosongkan teks tombol untuk menyembunyikannya.</p>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Teks tombol</label>
                    <input type="text" name="cta_button_text" class="form-control"
                        value="{{ old('cta_button_text', $heroSetting->cta_button_text) }}"
                        placeholder="misal: Lihat Jadwal">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Link tombol</label>
                    <input type="text" name="cta_button_link" class="form-control"
                        value="{{ old('cta_button_link', $heroSetting->cta_button_link) }}"
                        placeholder="misal: /schedule">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Warna tombol</label>
                    <input type="color" name="button_color" class="form-control form-control-color"
                        value="{{ old('button_color', $heroSetting->button_color ?? '#3b82f6') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Warna teks tombol</label>
                    <input type="color" name="button_text_color" class="form-control form-control-color"
                        value="{{ old('button_text_color', $heroSetting->button_text_color ?? '#ffffff') }}">
                </div>
            </div>
        </div>

        <div class="settings-card">
            <h2>Latar belakang banner</h2>
            <p class="card-desc">Tampilan visual di belakang teks banner.</p>

            <div class="mb-3">
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

            <div id="color-field" class="mb-3"
                style="display: {{ $heroSetting->background_type == 'color' ? 'block' : 'none' }}">
                <label class="form-label">Warna latar</label>
                <input type="color" name="background_color" class="form-control form-control-color"
                    value="{{ old('background_color', $heroSetting->background_color ?? '#0f172a') }}">
            </div>

            <div id="gradient-fields" class="mb-3"
                style="display: {{ $heroSetting->background_type == 'gradient' ? 'block' : 'none' }}">
                <label class="form-label">Warna gradient</label>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label form-text">Warna awal</label>
                        <input type="color" name="gradient_start" class="form-control form-control-color"
                            value="{{ old('gradient_start', $heroSetting->gradient_start ?? '#0f172a') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label form-text">Warna akhir</label>
                        <input type="color" name="gradient_end" class="form-control form-control-color"
                            value="{{ old('gradient_end', $heroSetting->gradient_end ?? '#1e293b') }}">
                    </div>
                </div>
            </div>

            <div id="image-field" style="display: {{ $heroSetting->background_type == 'image' ? 'block' : 'none' }}">
                <div class="mb-3">
                    <label class="form-label">Gambar latar</label>

                    @if($heroSetting->background_image)
                        <div class="current-image-preview mb-3">
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
                </div>

                <div id="overlay-control">
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

        <div class="action-bar">
            <button type="submit" class="btn-submit">
                <i class="bi bi-check-lg"></i> Simpan perubahan
            </button>
        </div>
    </form>
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

            function toggleFields() {
                const type = backgroundType.value;

                colorField.style.display = type === 'color' ? 'block' : 'none';
                imageField.style.display = type === 'image' ? 'block' : 'none';
                gradientFields.style.display = type === 'gradient' ? 'block' : 'none';
                overlayControl.style.display = type === 'image' ? 'block' : 'none';
            }

            toggleFields();
            backgroundType.addEventListener('change', toggleFields);

            if (overlaySlider) {
                overlaySlider.addEventListener('input', function () {
                    overlayValue.textContent = this.value + '%';
                });
            }

            const imageInput = document.querySelector('input[name="background_image"]');
            if (imageInput) {
                imageInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const existingPreview = document.querySelector('.image-preview');
                            if (existingPreview) existingPreview.remove();

                            const preview = document.createElement('div');
                            preview.className = 'image-preview mt-2';
                            preview.innerHTML = '<img src="' + e.target.result + '" alt="Pratinjau gambar baru"><div class="form-text mt-1">Pratinjau gambar baru</div>';
                            imageInput.parentNode.insertBefore(preview, imageInput.nextSibling);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endpush
