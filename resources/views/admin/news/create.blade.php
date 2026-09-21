@extends('layouts.admin')

@section('title', 'Tulis Artikel - OFS Futsal Center Admin')

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
.news-form-card {
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

.form-label .required {
    color: var(--accent, #c01c28);
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
    justify-content: center;
    gap: 8px;
    width: 100%;
    transition: all 0.15s ease;
}

.btn-submit:hover {
    background: var(--accent-hover, #a51822);
    color: white;
}

/* Section label di kolom kanan */
.sidebar-section {
    padding-top: 16px;
    margin-top: 4px;
    border-top: 1px solid var(--border, #e5e5e7);
}

.sidebar-section:first-child {
    padding-top: 0;
    margin-top: 0;
    border-top: none;
}

.section-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary, #111113);
    margin-bottom: 12px;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="admin-header">
        <h1 class="admin-header-title">
            <i class="bi bi-newspaper"></i> Tulis artikel baru
        </h1>
        <a href="{{ route('admin.news.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali ke daftar
        </a>
    </div>

    <div class="news-form-card">
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul <span class="required">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                            id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Excerpt -->
                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Ringkasan</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt"
                            name="excerpt" rows="3"
                            placeholder="Ringkasan singkat yang tampil di daftar artikel">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label">Isi artikel <span class="required">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content"
                            name="content" rows="10" required>{{ old('content') }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="sidebar-section">
                        <div class="section-label">Kategori & atribusi</div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori <span class="required">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category"
                                name="category" required>
                                <option value="">Pilih kategori</option>
                                <option value="Tournament">Tournament</option>
                                <option value="Match">Match</option>
                                <option value="Team">Team</option>
                                <option value="Player">Player</option>
                                <option value="Event">Event</option>
                                <option value="General">General</option>
                            </select>
                            @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Author -->
                        <div class="mb-3">
                            <label for="author" class="form-label">Penulis</label>
                            <input type="text" class="form-control @error('author') is-invalid @enderror"
                                id="author" name="author" value="{{ old('author') }}">
                            @error('author')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Source -->
                        <div class="mb-3">
                            <label for="source" class="form-label">Sumber</label>
                            <input type="text" class="form-control @error('source') is-invalid @enderror"
                                id="source" name="source" value="{{ old('source') }}">
                            @error('source')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Source URL -->
                        <div class="mb-3">
                            <label for="source_url" class="form-label">URL sumber</label>
                            <input type="url" class="form-control @error('source_url') is-invalid @enderror"
                                id="source_url" name="source_url" value="{{ old('source_url') }}"
                                placeholder="https://">
                            @error('source_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="sidebar-section">
                        <div class="section-label">Publikasi</div>

                        <!-- Published Date -->
                        <div class="mb-3">
                            <label for="published_at" class="form-label">Tanggal terbit <span
                                    class="required">*</span></label>
                            <input type="datetime-local"
                                class="form-control @error('published_at') is-invalid @enderror"
                                id="published_at" name="published_at"
                                value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('published_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar utama</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                id="image" name="image" accept="image/*">
                            <div class="form-text">Maksimal 2MB. Format: JPG, PNG, GIF.</div>
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Options -->
                        <div class="mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1" checked>
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_featured"
                                    name="is_featured" value="1">
                                <label class="form-check-label" for="is_featured">
                                    Unggulan
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-check-lg"></i> Simpan artikel
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('content', {
    toolbar: [
        ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'],
        ['NumberedList', 'BulletedList', 'Blockquote'],
        ['Link', 'Unlink'],
        ['Image', 'Table', 'HorizontalRule'],
        ['Styles', 'Format', 'Font', 'FontSize'],
        ['TextColor', 'BGColor'],
        ['Maximize', 'Source']
    ],
    height: 300
});
</script>
@endsection