@extends('layouts.admin')

@section('title', 'Buat Pemain Baru')

@section('styles')
    <style>
        /* ===== Create player page — design guidelines ===== */

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

        /* Stats input group */
        .stats-input-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
        }

        .stat-input .form-control {
            text-align: center;
            font-weight: 600;
        }

        .stat-label {
            font-size: 11px;
            color: var(--text-secondary);
            margin-top: 4px;
            text-align: center;
        }

        /* Photo upload */
        .photo-upload-container {
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 32px;
            text-align: center;
            background: var(--surface);
            transition: border-color 0.15s ease, background-color 0.15s ease;
            cursor: pointer;
            margin-bottom: 16px;
        }

        .photo-upload-container:hover,
        .photo-upload-container.dragover {
            border-color: var(--accent);
            background: rgba(26, 95, 180, 0.05);
        }

        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 16px;
            display: none;
            border: 4px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #F0F0F2;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 16px;
        }

        .photo-upload-text {
            color: var(--text-primary);
            font-size: 14px;
            margin-bottom: 4px;
        }

        .photo-upload-hint {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .file-info {
            margin-top: 16px;
            padding: 12px;
            background: var(--surface);
            border-radius: 8px;
            border: 1px solid var(--border);
            font-size: 14px;
            display: none;
        }

        .file-info.show {
            display: block;
        }

        .file-info .file-name {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 2px;
        }

        .file-info .file-size {
            color: var(--text-secondary);
            font-size: 12px;
        }

        .photo-options {
            display: flex;
            gap: 8px;
            margin-top: 16px;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-card .card-body {
                padding: 16px;
            }

            .stats-input-group {
                grid-template-columns: repeat(2, 1fr);
            }

            .photo-options {
                flex-direction: column;
                gap: 8px;
            }
        }

        @media (max-width: 576px) {
            .stats-input-group {
                grid-template-columns: 1fr;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                transition: none !important;
                animation: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Buat Pemain Baru</h1>
            <p class="page-subtitle">Tambahkan pemain baru ke dalam sistem</p>
        </div>
        <a href="{{ route('admin.players.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <strong>Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="form-card">
        <div class="card-header">
            <h5>Informasi Pemain</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.players.store') }}" method="POST" enctype="multipart/form-data" id="playerForm">
                @csrf

                <!-- Informasi dasar -->
                <div class="form-section-title">Informasi Dasar</div>

                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap pemain" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Masukkan nama lengkap pemain</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="jersey_number" class="form-label">Nomor Punggung</label>
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
                            <label for="birth_date" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                max="{{ now()->format('Y-m-d') }}">
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tanggal lahir pemain (opsional)</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="birth_place" class="form-label">Tempat Lahir</label>
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

                <!-- Tim & posisi -->
                <div class="form-section-title" style="margin-top: 24px;">Tim & Posisi</div>

                <div class="row g-3 mb-0">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="team_id" class="form-label">Tim</label>
                            <select class="form-select @error('team_id') is-invalid @enderror" id="team_id"
                                name="team_id">
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
                <div class="form-section-title" style="margin-top: 24px;">Statistik Awal</div>

                <div class="stats-input-group mb-0">
                    <div class="stat-input">
                        <input type="number" class="form-control @error('goals') is-invalid @enderror" id="goals"
                            name="goals" value="{{ old('goals', 0) }}" min="0">
                        <div class="stat-label">Goals</div>
                        @error('goals')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="stat-input">
                        <input type="number" class="form-control @error('assists') is-invalid @enderror" id="assists"
                            name="assists" value="{{ old('assists', 0) }}" min="0">
                        <div class="stat-label">Assists</div>
                        @error('assists')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="stat-input">
                        <input type="number" class="form-control @error('yellow_cards') is-invalid @enderror"
                            id="yellow_cards" name="yellow_cards" value="{{ old('yellow_cards', 0) }}" min="0">
                        <div class="stat-label">Yellow Cards</div>
                        @error('yellow_cards')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="stat-input">
                        <input type="number" class="form-control @error('red_cards') is-invalid @enderror"
                            id="red_cards" name="red_cards" value="{{ old('red_cards', 0) }}" min="0">
                        <div class="stat-label">Red Cards</div>
                        @error('red_cards')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Foto pemain -->
                <div class="form-section-title" style="margin-top: 24px;">Foto Pemain</div>

                <div class="mb-0">
                    <div class="photo-upload-container" id="photoUploadContainer"
                        onclick="document.getElementById('photo').click()">
                        <img id="photoPreview" class="photo-preview" alt="Preview">
                        <div id="photoPlaceholder" class="photo-placeholder">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="photo-upload-text">
                            Klik untuk upload atau drag and drop
                        </div>
                        <div class="photo-upload-hint">
                            PNG, JPG, JPEG hingga 2MB
                        </div>
                    </div>

                    <div id="fileInfo" class="file-info">
                        <div class="file-name" id="fileName"></div>
                        <div class="file-size" id="fileSize"></div>
                    </div>

                    <input type="file" id="photo" name="photo" class="d-none" accept="image/*"
                        onchange="handleFileSelect(event)">

                    <div class="photo-options">
                        <button type="button" class="btn-back"
                            onclick="document.getElementById('photo').click()">
                            <i class="bi bi-upload"></i> Pilih File
                        </button>
                        <button type="button" class="btn-back" onclick="clearPhoto()"
                            id="clearBtn" style="display: none;">
                            <i class="bi bi-x-circle"></i> Hapus Foto
                        </button>
                    </div>

                    @error('photo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Upload foto pemain yang jelas. Ukuran rekomendasi: 400x400px</div>
                </div>

                <!-- Aksi form -->
                <div class="d-flex justify-content-end align-items-center gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.players.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Buat Pemain
                    </button>
                </div>
            </form>
        </div>
    </div>
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
