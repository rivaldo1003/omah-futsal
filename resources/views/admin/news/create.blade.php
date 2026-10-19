@extends('layouts.admin')

@section('title', 'Tulis Artikel - OFS Futsal Center Admin')

@section('styles')
    <style>
        .news-create-page {
            --news-ink: #111111;
            --news-charcoal: #39393b;
            --news-muted: #707072;
            --news-line: #cacacb;
            --news-line-soft: #e5e5e5;
            --news-cloud: #f5f5f5;
            --news-white: #ffffff;
            --news-danger: #b42318;
            width: 100%;
            max-width: 1440px;
            margin: 0 auto;
            color: var(--news-ink);
            font-size: 15px;
            line-height: 1.5;
        }

        .news-create-page .admin-header {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 20px 24px;
            margin-bottom: 24px;
        }

        .news-create-page .admin-header-copy {
            min-width: 0;
        }

        .news-create-page .admin-header-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 8px;
            color: var(--news-ink);
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
            letter-spacing: -.02em;
        }

        .news-create-page .admin-header-title i {
            font-size: .82em;
        }

        .news-create-page .admin-header-copy p {
            max-width: 68ch;
            margin: 0;
            color: var(--news-muted);
            font-size: 14px;
        }

        .news-create-page .btn-back,
        .news-create-page .btn-submit {
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

        .news-create-page .btn-back {
            background: var(--news-cloud);
            color: var(--news-ink);
        }

        .news-create-page .btn-back:hover {
            background: #eeeeee;
            color: var(--news-ink);
        }

        .news-create-page .news-form-card {
            padding: 24px;
            border: 1px solid var(--news-line-soft);
            border-radius: 8px;
            background: var(--news-white);
            box-shadow: none;
        }

        .news-create-page .news-form-card > form > .row {
            --bs-gutter-x: 32px;
            --bs-gutter-y: 20px;
        }

        .news-create-page .news-form-sidebar {
            padding-left: 24px;
            border-left: 1px solid var(--news-line-soft);
        }

        .news-create-page .form-label {
            margin-bottom: 7px;
            color: var(--news-charcoal);
            font-size: 14px;
            font-weight: 500;
        }

        .news-create-page .form-label .required {
            color: var(--news-danger);
        }

        .news-create-page .form-control,
        .news-create-page .form-select {
            min-height: 44px;
            border: 1px solid var(--news-line);
            border-radius: 8px;
            background-color: var(--news-white);
            color: var(--news-ink);
            font: inherit;
            font-size: 14px;
            box-shadow: none;
            transition: border-color 150ms ease-out, box-shadow 150ms ease-out;
        }

        .news-create-page textarea.form-control {
            min-height: 96px;
            resize: vertical;
        }

        .news-create-page .form-control:focus,
        .news-create-page .form-select:focus {
            border-color: var(--news-ink);
            box-shadow: 0 0 0 3px var(--news-cloud);
        }

        .news-create-page .form-control.is-invalid:focus,
        .news-create-page .form-select.is-invalid:focus {
            border-color: var(--news-danger);
            box-shadow: 0 0 0 3px #fdf1f0;
        }

        .news-create-page .form-control[type="file"] {
            min-height: 48px;
            padding: 5px;
        }

        .news-create-page .form-control[type="file"]::file-selector-button {
            min-height: 36px;
            margin: 0 10px 0 0;
            padding: 7px 14px;
            border: 0;
            border-radius: 9999px;
            background: var(--news-cloud);
            color: var(--news-ink);
            font: inherit;
            font-size: 13px;
            cursor: pointer;
        }

        .news-create-page .form-text {
            margin-top: 7px;
            color: var(--news-muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .news-create-page .form-check-input {
            border-color: var(--news-line);
            box-shadow: none;
        }

        .news-create-page .form-check-input:checked {
            border-color: var(--news-ink);
            background-color: var(--news-ink);
        }

        .news-create-page .form-check-input:focus {
            border-color: var(--news-ink);
            box-shadow: 0 0 0 3px var(--news-cloud);
        }

        .news-create-page .form-check-label {
            color: var(--news-charcoal);
            font-size: 13px;
        }

        .news-create-page .sidebar-section + .sidebar-section {
            margin-top: 22px;
            padding-top: 20px;
            border-top: 1px solid var(--news-line-soft);
        }

        .news-create-page .section-label {
            margin-bottom: 14px;
            color: var(--news-ink);
            font-size: 15px;
            font-weight: 500;
            line-height: 1.4;
        }

        .news-create-page .cke_chrome {
            overflow: hidden;
            border: 1px solid var(--news-line) !important;
            border-radius: 8px;
            box-shadow: none !important;
        }

        .news-create-page .cke_top,
        .news-create-page .cke_bottom {
            border-color: var(--news-line-soft) !important;
            background: var(--news-cloud) !important;
            box-shadow: none !important;
        }

        .news-create-page .btn-submit {
            width: 100%;
            border-color: var(--news-ink);
            background: var(--news-ink);
            color: var(--news-white);
            cursor: pointer;
        }

        .news-create-page .btn-submit:hover {
            border-color: var(--news-charcoal);
            background: var(--news-charcoal);
            color: var(--news-white);
        }

        .news-create-page .alert-danger {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
            padding: 14px 16px;
            border: 1px solid #e8c8c5;
            border-radius: 8px;
            background: #fdf1f0;
            color: var(--news-danger);
            font-size: 14px;
            line-height: 1.5;
            box-shadow: none;
        }

        .news-create-page .alert-success {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
            padding: 14px 16px;
            border: 1px solid #d4e9dd;
            border-radius: 8px;
            background: #edf6f1;
            color: #007d48;
            font-size: 14px;
            line-height: 1.5;
        }

        .news-create-page .alert-danger ul {
            margin: 6px 0 0;
            padding-left: 18px;
        }

        .news-create-page :is(a, button, input, select, textarea):focus-visible {
            outline: 2px solid var(--news-ink);
            outline-offset: 3px;
        }

        @media (max-width: 767.98px) {
            .news-create-page .admin-header {
                grid-template-columns: minmax(0, 1fr);
                align-items: start;
            }

            .news-create-page .admin-header .btn-back {
                width: 100%;
            }

            .news-create-page .news-form-card {
                padding: 18px;
            }

            .news-create-page .news-form-card > form > .row {
                --bs-gutter-x: 20px;
            }

            .news-create-page .news-form-sidebar {
                padding-left: 12px;
                padding-top: 18px;
                border-left: 0;
                border-top: 1px solid var(--news-line-soft);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .news-create-page * {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
@endsection

@section('content')
    <main class="news-create-page">
        <div class="admin-header">
            <div class="admin-header-copy">
                <h1 class="admin-header-title">
                    <i class="bi bi-newspaper"></i> Tulis artikel baru
                </h1>
                <p>Buat artikel dan atur detail publikasinya.</p>
            </div>
            <a href="{{ route('admin.news.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali ke daftar
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success" role="status" aria-live="polite">
                <i class="bi bi-check-circle" aria-hidden="true"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
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

        <div class="news-form-card">
            <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row news-form-layout">
                    <div class="col-md-8">
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul <span class="required">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" value="{{ old('title') }}" required>
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

                    <div class="col-md-4 news-form-sidebar">
                        <div class="sidebar-section">
                            <div class="section-label">Kategori & atribusi</div>

                            <!-- Category -->
                            <div class="mb-3">
                                <label for="category" class="form-label">Kategori <span class="required">*</span></label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category"
                                    name="category" required>
                                    <option value="">Pilih kategori</option>
                                    @foreach(['Tournament', 'Match', 'Team', 'Player', 'Event', 'General'] as $category)
                                        <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Author -->
                            <div class="mb-3">
                                <label for="author" class="form-label">Penulis</label>
                                <input type="text" class="form-control @error('author') is-invalid @enderror" id="author"
                                    name="author" value="{{ old('author') }}">
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Source -->
                            <div class="mb-3">
                                <label for="source" class="form-label">Sumber</label>
                                <input type="text" class="form-control @error('source') is-invalid @enderror" id="source"
                                    name="source" value="{{ old('source') }}">
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
                                    class="form-control @error('published_at') is-invalid @enderror" id="published_at"
                                    name="published_at"
                                    value="{{ old('published_at', now(\App\Support\BusinessTime::TIMEZONE)->format('Y-m-d\TH:i')) }}"
                                    required>
                                <div class="form-text">Waktu menggunakan WIB.</div>
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar utama</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                    name="image" accept="image/*">
                                <div class="form-text">Maksimal 2MB. Format: JPG, PNG, GIF.</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status Options -->
                            <div class="mb-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Aktif
                                    </label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                        value="1" {{ old('is_featured') ? 'checked' : '' }}>
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
    </main>
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
