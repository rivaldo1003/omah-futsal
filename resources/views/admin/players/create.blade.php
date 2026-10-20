@extends('layouts.admin')

@section('title', 'Tambah pemain')

@section('styles')
    <style>
        @include('admin.players.partials.admin-styles')

        .admin-player-form-page .form-card .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .admin-player-form-page .form-card-context {
            color: var(--player-muted);
            font-size: 13px;
        }

        .admin-player-form-page .form-section-title {
            margin-top: 28px;
        }

        .admin-player-form-page .form-card .card-body form > .form-section-title:first-of-type {
            margin-top: 0;
        }

        .admin-player-form-page .stats-input-group {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .admin-player-form-page .stat-input {
            min-width: 0;
        }

        .admin-player-form-page .stat-label {
            display: block;
            margin-bottom: 7px;
            color: var(--player-charcoal);
            font-size: 13px;
            font-weight: 500;
        }

        .admin-player-form-page .stat-input .form-control {
            font-variant-numeric: tabular-nums;
        }

        .admin-player-form-page .photo-upload-container {
            display: grid;
            grid-template-columns: 72px minmax(0, 1fr) auto;
            align-items: center;
            gap: 16px;
            padding: 16px;
            border: 1px solid var(--player-line-soft);
            border-radius: 8px;
            background: var(--player-cloud);
            transition: border-color 150ms ease-out, background-color 150ms ease-out;
        }

        .admin-player-form-page .photo-upload-container.dragover {
            border-color: var(--player-ink);
            background: #eeeeee;
        }

        .admin-player-form-page .photo-preview,
        .admin-player-form-page .photo-placeholder {
            width: 72px;
            height: 72px;
            margin: 0;
            border: 1px solid var(--player-line-soft);
            border-radius: 50%;
        }

        .admin-player-form-page .photo-preview {
            display: none;
            object-fit: cover;
            background: var(--player-white);
        }

        .admin-player-form-page .photo-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--player-white);
            color: var(--player-muted);
            font-size: 28px;
        }

        .admin-player-form-page .photo-upload-text {
            margin: 0 0 3px;
            color: var(--player-ink);
            font-size: 14px;
            font-weight: 500;
        }

        .admin-player-form-page .photo-upload-hint {
            color: var(--player-muted);
            font-size: 12px;
        }

        .admin-player-form-page .photo-options {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 8px;
        }

        .admin-player-form-page .photo-action-btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 16px;
            border: 1px solid transparent;
            border-radius: 9999px;
            background: var(--player-white);
            color: var(--player-ink);
            font: inherit;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
        }

        .admin-player-form-page .photo-action-btn:hover {
            background: #eeeeee;
        }

        .admin-player-form-page .photo-action-remove {
            color: var(--player-danger);
        }

        .admin-player-form-page .file-info {
            display: none;
            margin-top: 10px;
            padding: 10px 12px;
            border: 1px solid var(--player-line-soft);
            border-radius: 8px;
            background: var(--player-white);
            font-size: 13px;
        }

        .admin-player-form-page .file-info.show {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 4px 12px;
        }

        .admin-player-form-page .file-name {
            min-width: 0;
            overflow-wrap: anywhere;
            color: var(--player-ink);
            font-weight: 500;
        }

        .admin-player-form-page .file-size {
            color: var(--player-muted);
            font-size: 12px;
        }

        .admin-player-form-page .form-control:focus,
        .admin-player-form-page .form-select:focus,
        .admin-player-form-page .form-control.is-invalid:focus,
        .admin-player-form-page .form-select.is-invalid:focus {
            border: 2px solid var(--player-ink);
            outline: none;
            box-shadow: none; /* taste-skill-ignore: keep field focus to a single border */
        }

        .admin-player-form-page .form-control.is-invalid:focus,
        .admin-player-form-page .form-select.is-invalid:focus {
            border-color: var(--player-danger);
        }

        .admin-player-form-page :is(input, select, textarea):focus-visible {
            outline: none;
        }

        .admin-player-form-page :is(a, button):focus-visible {
            outline: 2px solid var(--player-ink);
            outline-offset: 3px;
        }

        @media (max-width: 767.98px) {
            .admin-player-form-page .form-card .card-header {
                flex-wrap: wrap;
            }

            .admin-player-form-page .stats-input-group {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .admin-player-form-page .photo-upload-container {
                grid-template-columns: 64px minmax(0, 1fr);
                gap: 12px;
                padding: 14px;
            }

            .admin-player-form-page .photo-preview,
            .admin-player-form-page .photo-placeholder {
                width: 64px;
                height: 64px;
            }

            .admin-player-form-page .photo-options {
                grid-column: 1 / -1;
                justify-content: flex-start;
            }

            .admin-player-form-page .player-form-actions .btn {
                flex: 1 1 140px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-player-form-page *,
            .admin-player-form-page *::before,
            .admin-player-form-page *::after {
                transition: none !important;
                animation: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <main class="admin-player-page admin-player-form-page">
    <div class="page-header">
        <div>
            <h1>Tambah pemain</h1>
            <p class="page-subtitle">Tambahkan pemain baru ke dalam sistem</p>
        </div>
        <a href="{{ route('admin.players.index') }}" class="btn-back">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke daftar pemain
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i>
            <strong>Terjadi kesalahan.</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2" aria-hidden="true"></i>
            <strong>Berhasil.</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="form-card">
        <div class="card-header">
            <h5>Informasi pemain</h5>
            <span class="form-card-context">Kolom bertanda <span class="required">*</span> wajib diisi</span>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.players.store') }}" method="POST" enctype="multipart/form-data" id="playerForm">
                @csrf

                <!-- Informasi dasar -->
                <div class="form-section-title">Informasi dasar</div>

                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama lengkap <span class="required" aria-hidden="true">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap pemain" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Nama ini akan ditampilkan pada profil pemain.</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="jersey_number" class="form-label">Nomor punggung</label>
                            <input type="number" class="form-control @error('jersey_number') is-invalid @enderror"
                                id="jersey_number" name="jersey_number" value="{{ old('jersey_number') }}"
                                placeholder="contoh: 10" min="1" max="99">
                            @error('jersey_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Nomor punggung pemain (1-99)</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-0">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="birth_date" class="form-label">Tanggal lahir</label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                max="{{ \Carbon\CarbonImmutable::today(\App\Support\BusinessTime::TIMEZONE)->toDateString() }}">
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tanggal lahir pemain (opsional)</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="birth_place" class="form-label">Tempat lahir</label>
                            <input type="text" class="form-control @error('birth_place') is-invalid @enderror"
                                id="birth_place" name="birth_place" value="{{ old('birth_place') }}"
                                placeholder="contoh: Jombang" maxlength="100">
                            @error('birth_place')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tempat lahir pemain (opsional)</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-0">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="market_value" class="form-label">Nilai pasar pemain</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('market_value') is-invalid @enderror"
                                    id="market_value" name="market_value" value="{{ old('market_value') }}"
                                    placeholder="contoh: 25000000" min="0" step="100000">
                            </div>
                            @error('market_value')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Nominal Rupiah penuh, misalnya 25000000 untuk Rp 25 juta.</div>
                        </div>
                    </div>
                </div>

                <!-- Tim & posisi -->
                <div class="form-section-title">Tim & posisi</div>

                <div class="row g-3 mb-0">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="team_id" class="form-label">Tim</label>
                            <select class="form-select @error('team_id') is-invalid @enderror" id="team_id" name="team_id">
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
                            <div class="form-text">Pilih tim tempat pemain bergabung</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="position" class="form-label">Posisi</label>
                            <select class="form-select @error('position') is-invalid @enderror" id="position"
                                name="position">
                                <option value="">Pilih posisi</option>
                                <option value="Flank" {{ old('position') == 'Flank' ? 'selected' : '' }}>Flank</option>
                                <option value="Anchor" {{ old('position') == 'Anchor' ? 'selected' : '' }}>Anchor</option>
                                <option value="Pivot" {{ old('position') == 'Pivot' ? 'selected' : '' }}>Pivot</option>
                                <option value="Kiper" {{ old('position') == 'Kiper' ? 'selected' : '' }}>Kiper</option>
                            </select>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Pilih posisi utama pemain</div>
                        </div>
                    </div>
                </div>

                <!-- Statistik awal -->
                <div class="form-section-title">Statistik awal</div>

                <div class="stats-input-group mb-0">
                    <div class="stat-input">
                        <label class="stat-label" for="goals">Gol</label>
                        <input type="number" class="form-control @error('goals') is-invalid @enderror" id="goals"
                            name="goals" value="{{ old('goals', 0) }}" min="0">
                        @error('goals')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="stat-input">
                        <label class="stat-label" for="assists">Assist</label>
                        <input type="number" class="form-control @error('assists') is-invalid @enderror" id="assists"
                            name="assists" value="{{ old('assists', 0) }}" min="0">
                        @error('assists')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="stat-input">
                        <label class="stat-label" for="yellow_cards">Kartu kuning</label>
                        <input type="number" class="form-control @error('yellow_cards') is-invalid @enderror"
                            id="yellow_cards" name="yellow_cards" value="{{ old('yellow_cards', 0) }}" min="0">
                        @error('yellow_cards')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="stat-input">
                        <label class="stat-label" for="red_cards">Kartu merah</label>
                        <input type="number" class="form-control @error('red_cards') is-invalid @enderror" id="red_cards"
                            name="red_cards" value="{{ old('red_cards', 0) }}" min="0">
                        @error('red_cards')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Foto pemain -->
                <div class="form-section-title">Foto pemain</div>

                <div class="mb-0">
                    <div class="photo-upload-container" id="photoUploadContainer">
                        <img id="photoPreview" class="photo-preview" alt="Pratinjau foto pemain">
                        <div id="photoPlaceholder" class="photo-placeholder">
                            <i class="bi bi-person" aria-hidden="true"></i>
                        </div>
                        <div class="photo-upload-copy">
                            <div class="photo-upload-text">Foto pemain</div>
                            <div class="photo-upload-hint" id="photoUploadHint">Seret gambar ke sini atau pilih file JPG, PNG, GIF hingga 2 MB.</div>
                        </div>
                        <div class="photo-options">
                            <button type="button" class="photo-action-btn" onclick="document.getElementById('photo').click()">
                                <i class="bi bi-upload" aria-hidden="true"></i> Pilih foto
                            </button>
                            <button type="button" class="photo-action-btn photo-action-remove" onclick="clearPhoto()"
                                id="clearBtn" style="display: none;">
                                <i class="bi bi-x-circle" aria-hidden="true"></i> Hapus foto
                            </button>
                        </div>
                    </div>

                    <div id="fileInfo" class="file-info" aria-live="polite">
                        <div class="file-name" id="fileName"></div>
                        <div class="file-size" id="fileSize"></div>
                    </div>

                    <input type="file" id="photo" name="photo" class="d-none"
                        accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif"
                        onchange="handleFileSelect(event)" aria-label="Pilih foto pemain" aria-describedby="photoUploadHint">

                    @error('photo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Upload foto pemain yang jelas. Ukuran rekomendasi: 400x400px</div>
                </div>

                <!-- Aksi form -->
                <div class="player-form-actions">
                    <a href="{{ route('admin.players.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg" aria-hidden="true"></i> Simpan pemain
                    </button>
                </div>
            </form>
        </div>
    </div>
    </main>
@endsection

@section('scripts')
    <script>
        // Pilih file & validasi
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Pilih file gambar yang valid (JPEG, PNG, GIF)');
                clearPhoto();
                return;
            }

            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                alert('Ukuran file harus kurang dari 2MB');
                clearPhoto();
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('photoPreview');
                const placeholder = document.getElementById('photoPlaceholder');
                const fileInfo = document.getElementById('fileInfo');
                const fileName = document.getElementById('fileName');
                const fileSize = document.getElementById('fileSize');
                const clearBtn = document.getElementById('clearBtn');

                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';

                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                fileInfo.classList.add('show');

                clearBtn.style.display = 'inline-flex';
            };
            reader.readAsDataURL(file);
        }

        // Hapus foto terpilih
        function clearPhoto() {
            const input = document.getElementById('photo');
            const preview = document.getElementById('photoPreview');
            const placeholder = document.getElementById('photoPlaceholder');
            const fileInfo = document.getElementById('fileInfo');
            const clearBtn = document.getElementById('clearBtn');

            input.value = '';
            preview.style.display = 'none';
            placeholder.style.display = 'flex';
            fileInfo.classList.remove('show');
            clearBtn.style.display = 'none';
        }

        // Format ukuran file
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('photoUploadContainer');
            const fileInput = document.getElementById('photo');

            // Cegah default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                container.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Highlight area drop
            ['dragenter', 'dragover'].forEach(eventName => {
                container.addEventListener(eventName, () => container.classList.add('dragover'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                container.addEventListener(eventName, () => container.classList.remove('dragover'), false);
            });

            // Handle drop
            container.addEventListener('drop', function (e) {
                const files = e.dataTransfer.files;

                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect({ target: { files: files } });
                }
            }, false);

            // Validasi saat submit
            document.getElementById('playerForm').addEventListener('submit', function (e) {
                const name = document.getElementById('name').value.trim();
                if (!name) {
                    e.preventDefault();
                    alert('Masukkan nama pemain');
                    document.getElementById('name').focus();
                    return;
                }

                const fileInput = document.getElementById('photo');
                if (fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    const maxSize = 2 * 1024 * 1024;

                    if (!validTypes.includes(file.type)) {
                        e.preventDefault();
                        alert('Pilih file gambar yang valid (JPEG, PNG, GIF)');
                        return;
                    }

                    if (file.size > maxSize) {
                        e.preventDefault();
                        alert('Ukuran file harus kurang dari 2MB');
                        return;
                    }
                }
            });

            // Inisialisasi tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
