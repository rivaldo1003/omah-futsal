@extends('layouts.admin')

@section('title', 'Buat Tim Baru')

@section('styles')
    <style>
        /* ===== Create team page — design guidelines ===== */

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

        .form-check-label {
            cursor: pointer;
            width: 100%;
        }

        .form-check-label:hover {
            background-color: rgba(26, 95, 180, 0.05);
        }

        .btn-xs {
            padding: 2px 6px;
            font-size: 12px;
            line-height: 1.2;
            border-radius: 4px;
        }

        .form-control-color {
            width: 40px;
            padding: 4px;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Buat Tim Baru</h1>
            <p class="page-subtitle">Tambahkan tim baru ke turnamen</p>
        </div>
        <a href="{{ route('admin.teams.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <!-- Form utama -->
        <div class="col-lg-8">
            <div class="form-card">
                <div class="card-header">
                    <h5>Informasi Tim</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.teams.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Logo tim -->
                        <div class="form-section-title">Logo Tim</div>
                        <div class="mb-4">
                            <div class="text-center mb-2">
                                <div id="logoPreview"
                                    class="d-inline-flex align-items-center justify-content-center border rounded"
                                    style="width: 120px; height: 120px; background: var(--surface);">
                                    <div id="previewContainer"
                                        class="w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                        <i class="bi bi-image text-secondary" style="font-size: 2rem;"></i>
                                        <p class="text-secondary x-small mt-1 mb-0">Belum ada logo</p>
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

                        <div class="form-section-title">Detail Tim</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Tim <span class="required">*</span></label>
                                <input type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required
                                    placeholder="Masukkan nama tim">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="short_name" class="form-label">Kode Singkat</label>
                                <input type="text"
                                    class="form-control @error('short_name') is-invalid @enderror"
                                    id="short_name" name="short_name" value="{{ old('short_name') }}"
                                    maxlength="10" placeholder="contoh: TGR">
                                @error('short_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-section-title" style="margin-top: 24px;">Informasi Pelatih</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="head_coach" class="form-label">Head Coach</label>
                                <input type="text"
                                    class="form-control @error('head_coach') is-invalid @enderror"
                                    id="head_coach" name="head_coach" value="{{ old('head_coach') }}"
                                    placeholder="Nama head coach">
                                @error('head_coach')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="assistant_coach" class="form-label">Assistant Coach</label>
                                <input type="text"
                                    class="form-control @error('assistant_coach') is-invalid @enderror"
                                    id="assistant_coach" name="assistant_coach" value="{{ old('assistant_coach') }}"
                                    placeholder="Nama assistant coach">
                                @error('assistant_coach')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="goalkeeper_coach" class="form-label">Goalkeeper Coach</label>
                                <input type="text"
                                    class="form-control @error('goalkeeper_coach') is-invalid @enderror"
                                    id="goalkeeper_coach" name="goalkeeper_coach" value="{{ old('goalkeeper_coach') }}"
                                    placeholder="Nama goalkeeper coach">
                                @error('goalkeeper_coach')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="kitman" class="form-label">Kitman</label>
                                <input type="text"
                                    class="form-control @error('kitman') is-invalid @enderror"
                                    id="kitman" name="kitman" value="{{ old('kitman') }}"
                                    placeholder="Nama kitman">
                                @error('kitman')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-section-title" style="margin-top: 24px;">Warna Tim</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="primary_color" class="form-label">Warna Utama</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color"
                                        id="primary_color_picker" value="#007bff">
                                    <input type="text"
                                        class="form-control @error('primary_color') is-invalid @enderror"
                                        id="primary_color" name="primary_color"
                                        value="{{ old('primary_color', '#007bff') }}"
                                        maxlength="7" placeholder="#RRGGBB">
                                </div>
                                @error('primary_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="secondary_color" class="form-label">Warna Kedua</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color"
                                        id="secondary_color_picker" value="#6c757d">
                                    <input type="text"
                                        class="form-control @error('secondary_color') is-invalid @enderror"
                                        id="secondary_color" name="secondary_color"
                                        value="{{ old('secondary_color', '#6c757d') }}"
                                        maxlength="7" placeholder="#RRGGBB">
                                </div>
                                @error('secondary_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-section-title" style="margin-top: 24px;">Informasi Kontak</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Telepon</label>
                                <input type="text"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}"
                                    placeholder="Nomor telepon tim">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Email tim">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="coach_phone" class="form-label">Telepon Coach</label>
                                <input type="text"
                                    class="form-control @error('coach_phone') is-invalid @enderror"
                                    id="coach_phone" name="coach_phone" value="{{ old('coach_phone') }}"
                                    placeholder="Nomor telepon coach">
                                @error('coach_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="coach_email" class="form-label">Email Coach</label>
                                <input type="email"
                                    class="form-control @error('coach_email') is-invalid @enderror"
                                    id="coach_email" name="coach_email" value="{{ old('coach_email') }}"
                                    placeholder="Email coach">
                                @error('coach_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-section-title" style="margin-top: 24px;">Venue & Lainnya</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="home_venue" class="form-label">Home Venue</label>
                                <input type="text"
                                    class="form-control @error('home_venue') is-invalid @enderror"
                                    id="home_venue" name="home_venue" value="{{ old('home_venue') }}"
                                    placeholder="Stadion/venue kandang">
                                @error('home_venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="founded_year" class="form-label">Tahun Berdiri</label>
                                <input type="number"
                                    class="form-control @error('founded_year') is-invalid @enderror"
                                    id="founded_year" name="founded_year" value="{{ old('founded_year') }}"
                                    min="1900" max="{{ date('Y') }}" placeholder="contoh: 1990">
                                @error('founded_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="website" class="form-label">Website</label>
                                <input type="url"
                                    class="form-control @error('website') is-invalid @enderror"
                                    id="website" name="website" value="{{ old('website') }}"
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
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                    id="address" name="address" rows="2"
                                    placeholder="Alamat lengkap tim">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="3"
                                    placeholder="Deskripsi tim">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pilih pemain -->
                        @if($players->count() > 0)
                            <div class="form-section-title" style="margin-top: 24px;">
                                Tugaskan Pemain ({{ $players->count() }})
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-secondary">Centang pemain yang akan bergabung ke tim ini</small>
                                    <div>
                                        <button type="button" class="btn-xs btn-back" id="selectAllPlayers">Semua</button>
                                        <button type="button" class="btn-xs btn-back ms-1" id="deselectAllPlayers">Tidak ada</button>
                                    </div>
                                </div>
                                <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto; background: var(--surface);">
                                    <div class="row g-1">
                                        @foreach($players as $player)
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="player_ids[]" value="{{ $player->id }}"
                                                        id="player_{{ $player->id }}"
                                                        {{ in_array($player->id, old('player_ids', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="player_{{ $player->id }}">
                                                        <span class="badge bg-primary bg-opacity-10 text-primary me-1">{{ $player->jersey_number ?? '#' }}</span>
                                                        {{ $player->name }}
                                                        @if($player->position)
                                                            <small class="text-secondary ms-1">{{ $player->position }}</small>
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
                                Belum ada pemain bebas.
                                <a href="{{ route('admin.players.create') }}" class="alert-link">Buat pemain terlebih dahulu</a>
                            </div>
                        @endif

                        <!-- Pilih turnamen -->
                        @if($tournaments->count() > 0)
                            <div class="form-section-title" style="margin-top: 24px;">Daftarkan ke Turnamen</div>
                            <div class="mb-0">
                                <div class="border rounded p-2" style="background: var(--surface);">
                                    @foreach($tournaments as $tournament)
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="checkbox"
                                                name="tournament_ids[]" value="{{ $tournament->id }}"
                                                id="tournament_{{ $tournament->id }}"
                                                {{ in_array($tournament->id, old('tournament_ids', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label d-flex justify-content-between"
                                                for="tournament_{{ $tournament->id }}">
                                                <span>{{ $tournament->name }}</span>
                                                <small class="text-secondary">{{ $tournament->format }}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Aksi form -->
                        <div class="d-flex justify-content-end align-items-center gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Buat Tim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Tips -->
            <div class="form-card mb-4">
                <div class="card-header">
                    <h5><i class="bi bi-lightbulb me-2"></i>Tips Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="fw-medium mb-1">Field Wajib</div>
                        <div class="text-secondary" style="font-size: 13px;">Field bertanda <span class="required">*</span> wajib diisi</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-medium mb-1">Informasi Pelatih</div>
                        <div class="text-secondary" style="font-size: 13px;">Lengkapi semua field pelatih untuk manajemen tim yang lebih baik</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-medium mb-1">Warna Tim</div>
                        <div class="text-secondary" style="font-size: 13px;">Gunakan kode hex (#RRGGBB) untuk warna utama dan kedua</div>
                    </div>
                    <div class="mb-0">
                        <div class="fw-medium mb-1">Panduan Logo</div>
                        <div class="text-secondary" style="font-size: 13px;">Gambar persegi paling cocok untuk logo</div>
                    </div>
                </div>
            </div>

            <!-- Preview logo -->
            <div class="form-card mb-4">
                <div class="card-header">
                    <h5><i class="bi bi-image me-2"></i>Preview Logo</h5>
                </div>
                <div class="card-body text-center">
                    <div id="logoPreviewCard"
                        class="d-flex align-items-center justify-content-center border rounded mx-auto"
                        style="min-height: 100px; background: var(--surface);">
                        <div id="cardPreviewContainer"
                            class="w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-image text-secondary" style="font-size: 1.5rem;"></i>
                            <p class="text-secondary x-small mt-1 mb-0">Preview logo</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview warna -->
            <div class="form-card mb-4">
                <div class="card-header">
                    <h5><i class="bi bi-palette me-2"></i>Preview Warna Tim</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-4 mb-3">
                        <div>
                            <div class="x-small text-secondary mb-1">Warna Utama</div>
                            <div class="d-flex align-items-center">
                                <div id="primaryColorPreview" class="rounded me-2 border"
                                    style="width: 24px; height: 24px; background-color: #007bff;"></div>
                                <span id="primaryColorHex" style="font-size: 13px;">#007bff</span>
                            </div>
                        </div>
                        <div>
                            <div class="x-small text-secondary mb-1">Warna Kedua</div>
                            <div class="d-flex align-items-center">
                                <div id="secondaryColorPreview" class="rounded me-2 border"
                                    style="width: 24px; height: 24px; background-color: #6c757d;"></div>
                                <span id="secondaryColorHex" style="font-size: 13px;">#6c757d</span>
                            </div>
                        </div>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div id="colorBarPrimary" class="progress-bar" style="width: 60%; background-color: #007bff;"></div>
                        <div id="colorBarSecondary" class="progress-bar" style="width: 40%; background-color: #6c757d;"></div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan pemain -->
            @if($players->count() > 0)
                <div class="form-card">
                    <div class="card-header">
                        <h5><i class="bi bi-people me-2"></i>Pemain ({{ $players->count() }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <div style="max-height: 200px; overflow-y: auto;">
                            @foreach($players as $player)
                                <div class="p-2 border-bottom d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium" style="font-size: 14px;">{{ $player->name }}</div>
                                        <small class="text-secondary">{{ $player->position }}</small>
                                    </div>
                                    <span class="badge bg-light text-dark">#{{ $player->jersey_number }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Preview logo
        window.previewImage = function (input) {
            const file = input.files[0];

            if (file && file.type.startsWith('image/')) {
                // Validasi ukuran file (maks 2MB)
                const MAX_SIZE = 2 * 1024 * 1024;
                if (file.size > MAX_SIZE) {
                    alert('Ukuran file harus kurang dari 2MB');
                    clearImage();
                    return;
                }

                const reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('previewContainer').innerHTML =
                        `<img src="${e.target.result}" class="img-fluid rounded">`;
                    document.getElementById('cardPreviewContainer').innerHTML =
                        `<img src="${e.target.result}" class="img-fluid rounded">`;
                };

                reader.readAsDataURL(file);
            }
        };

        window.clearImage = function () {
            const logoInput = document.getElementById('logo');
            if (logoInput) {
                logoInput.value = '';
            }

            document.getElementById('previewContainer').innerHTML = `
                <i class="bi bi-image text-secondary" style="font-size: 2rem;"></i>
                <p class="text-secondary x-small mt-1 mb-0">Belum ada logo</p>
            `;

            document.getElementById('cardPreviewContainer').innerHTML = `
                <i class="bi bi-image text-secondary" style="font-size: 1.5rem;"></i>
                <p class="text-secondary x-small mt-1 mb-0">Preview logo</p>
            `;
        };

        // Preview warna
        function updateColorPreview() {
            const primaryColor = document.getElementById('primary_color').value;
            const secondaryColor = document.getElementById('secondary_color').value;

            document.getElementById('primaryColorPreview').style.backgroundColor = primaryColor;
            document.getElementById('secondaryColorPreview').style.backgroundColor = secondaryColor;

            document.getElementById('primaryColorHex').textContent = primaryColor;
            document.getElementById('secondaryColorHex').textContent = secondaryColor;

            document.getElementById('colorBarPrimary').style.backgroundColor = primaryColor;
            document.getElementById('colorBarSecondary').style.backgroundColor = secondaryColor;
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Upload logo
            const logoInput = document.getElementById('logo');
            const clearLogoBtn = document.getElementById('clearLogoBtn');

            if (logoInput) {
                logoInput.setAttribute('onchange', 'previewImage(this)');
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
                        updateColorPreview();
                    }
                });

                primaryColorPicker.addEventListener('input', function () {
                    primaryColorInput.value = this.value;
                    updateColorPreview();
                });
            }

            if (secondaryColorInput && secondaryColorPicker) {
                secondaryColorInput.addEventListener('input', function () {
                    if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                        secondaryColorPicker.value = this.value;
                        updateColorPreview();
                    }
                });

                secondaryColorPicker.addEventListener('input', function () {
                    secondaryColorInput.value = this.value;
                    updateColorPreview();
                });
            }

            updateColorPreview();

            // Pilih semua / batal pilih pemain
            const selectAllBtn = document.getElementById('selectAllPlayers');
            const deselectAllBtn = document.getElementById('deselectAllPlayers');
            const playerCheckboxes = document.querySelectorAll('input[name="player_ids[]"]');

            if (selectAllBtn && playerCheckboxes.length > 0) {
                selectAllBtn.addEventListener('click', () => {
                    playerCheckboxes.forEach(cb => cb.checked = true);
                });
            }

            if (deselectAllBtn && playerCheckboxes.length > 0) {
                deselectAllBtn.addEventListener('click', () => {
                    playerCheckboxes.forEach(cb => cb.checked = false);
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
                        this.value = '';
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
@endsection
