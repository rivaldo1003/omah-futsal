<!-- Langkah 1: Informasi dasar -->
                    <div class="step-content" id="step1" style="display: {{ $currentStep == 1 ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    <i class="bi bi-info-circle"></i>
                                    Informasi Dasar Turnamen
                                </h5>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="bi bi-check-circle"></i>
                                        <div>
                                            <strong>Sukses!</strong> {{ session('success') }}
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Tutup"></button>
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        <div>
                                            <strong>Perbaiki kesalahan berikut:</strong>
                                            <ul class="mt-2 mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Tutup"></button>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">
                                                <i class="bi bi-card-heading"></i>
                                                Nama Turnamen
                                                <span class="required">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name"
                                                value="{{ old('name', $tournamentData['name'] ?? '') }}" required
                                                placeholder="Contoh: OFS Champions League 2026">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slug" class="form-label">
                                                <i class="bi bi-link"></i>
                                                URL Slug
                                            </label>
                                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                                id="slug" name="slug"
                                                value="{{ old('slug', $tournamentData['slug'] ?? '') }}"
                                                placeholder="Dibuat otomatis dari nama turnamen">
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description" class="form-label">
                                        <i class="bi bi-text-paragraph"></i>
                                        Deskripsi
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" rows="3"
                                        placeholder="Ceritakan secara singkat tentang turnamen ini">{{ old('description', $tournamentData['description'] ?? '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date" class="form-label">
                                                <i class="bi bi-calendar-event"></i>
                                                Tanggal Mulai
                                                <span class="required">*</span>
                                            </label>
                                            <input type="date"
                                                class="form-control @error('start_date') is-invalid @enderror"
                                                id="start_date" name="start_date"
                                                value="{{ old('start_date', $tournamentData['start_date'] ?? '') }}"
                                                required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date" class="form-label">
                                                <i class="bi bi-calendar-check"></i>
                                                Tanggal Selesai
                                                <span class="required">*</span>
                                            </label>
                                            <input type="date"
                                                class="form-control @error('end_date') is-invalid @enderror"
                                                id="end_date" name="end_date"
                                                value="{{ old('end_date', $tournamentData['end_date'] ?? '') }}"
                                                required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="location" class="form-label">
                                                <i class="bi bi-geo-alt"></i>
                                                Lokasi
                                            </label>
                                            <input type="text"
                                                class="form-control @error('location') is-invalid @enderror"
                                                id="location" name="location"
                                                value="{{ old('location', $tournamentData['location'] ?? '') }}"
                                                placeholder="Contoh: GOR OFS">
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="organizer" class="form-label">
                                                <i class="bi bi-building"></i>
                                                Penyelenggara
                                            </label>
                                            <input type="text"
                                                class="form-control @error('organizer') is-invalid @enderror"
                                                id="organizer" name="organizer"
                                                value="{{ old('organizer', $tournamentData['organizer'] ?? '') }}"
                                                placeholder="Contoh: OFS Futsal Center">
                                            @error('organizer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Field untuk logo dan banner -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo" class="form-label">
                                                <i class="bi bi-image"></i>
                                                Logo turnamen
                                            </label>
                                            <div class="file-upload-container" onclick="document.getElementById('logo').click()">
                                                <i class="bi bi-cloud-upload"></i>
                                                <p class="mb-2">Pilih berkas logo</p>
                                                <small class="text-muted">Gunakan gambar persegi, maksimal 2 MB, format PNG atau JPG.</small>
                                                <input type="file" class="form-control d-none @error('logo') is-invalid @enderror" 
                                                       id="logo" name="logo" accept="image/*" onchange="previewLogo(event)" aria-label="Pilih berkas logo turnamen">
                                                @error('logo')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="file-preview" id="logoPreviewContainer" style="display: none;">
                                                <img id="logoPreview" alt="Pratinjau logo turnamen">
                                                <div class="file-name" id="logoFileName"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="banner" class="form-label">
                                                <i class="bi bi-image-fill"></i>
                                                Banner turnamen
                                            </label>
                                            <div class="file-upload-container" onclick="document.getElementById('banner').click()">
                                                <i class="bi bi-cloud-upload"></i>
                                                <p class="mb-2">Pilih berkas banner</p>
                                                <small class="text-muted">Gunakan gambar landscape 16:9, maksimal 5 MB, format PNG atau JPG.</small>
                                                <input type="file" class="form-control d-none @error('banner') is-invalid @enderror" 
                                                       id="banner" name="banner" accept="image/*" onchange="previewBanner(event)" aria-label="Pilih berkas banner turnamen">
                                                @error('banner')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="file-preview" id="bannerPreviewContainer" style="display: none;">
                                                <img id="bannerPreview" alt="Pratinjau banner turnamen">
                                                <div class="file-name" id="bannerFileName"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type" class="form-label">
                                                <i class="bi bi-diagram-3"></i>
                                                Tipe Turnamen
                                                <span class="required">*</span>
                                            </label>
                                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                                <option value="" disabled {{ old('type', $tournamentData['type'] ?? '') ? '' : 'selected' }}>Pilih format turnamen</option>
                                                <option value="league" {{ (old('type', $tournamentData['type'] ?? '') == 'league') ? 'selected' : '' }}>Liga (setiap tim bertemu)</option>
                                                <option value="knockout" {{ (old('type', $tournamentData['type'] ?? '') == 'knockout') ? 'selected' : '' }}>Sistem gugur</option>
                                                <option value="group_knockout" {{ (old('type', $tournamentData['type'] ?? '') == 'group_knockout') ? 'selected' : '' }}>Grup dan sistem gugur</option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="form-label">
                                                <i class="bi bi-clock-history"></i>
                                                Status Awal
                                                <span class="required">*</span>
                                            </label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                id="status" name="status" required>
                                                <option value="upcoming" {{ (old('status', $tournamentData['status'] ?? '') == 'upcoming') ? 'selected' : '' }}>Akan datang</option>
                                                <option value="ongoing" {{ (old('status', $tournamentData['status'] ?? '') == 'ongoing') ? 'selected' : '' }}>Berlangsung</option>
                                                <option value="completed" {{ (old('status', $tournamentData['status'] ?? '') == 'completed') ? 'selected' : '' }}>Selesai</option>
                                                <option value="cancelled" {{ (old('status', $tournamentData['status'] ?? '') == 'cancelled') ? 'selected' : '' }}>Dibatalkan</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Pengaturan grup -->
                                <div id="groupSettings" class="settings-section"
                                    style="display: {{ (old('type', $tournamentData['type'] ?? '') == 'group_knockout') ? 'block' : 'none' }};">
                                    <h6><i class="bi bi-grid-3x3"></i> Pengaturan grup</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="groups_count" class="form-label">
                                                    Jumlah Grup
                                                    <span class="required">*</span>
                                                </label>
                                                <input type="number"
                                                    class="form-control @error('groups_count') is-invalid @enderror"
                                                    id="groups_count" name="groups_count"
                                                    value="{{ old('groups_count', $tournamentData['groups_count'] ?? 2) }}"
                                                    min="1" max="8">
                                                @error('groups_count')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="teams_per_group" class="form-label">
                                                    Tim per Grup
                                                    <span class="required">*</span>
                                                </label>
                                                <input type="number"
                                                    class="form-control @error('teams_per_group') is-invalid @enderror"
                                                    id="teams_per_group" name="teams_per_group"
                                                    value="{{ old('teams_per_group', $tournamentData['teams_per_group'] ?? 4) }}"
                                                    min="2" max="10">
                                                @error('teams_per_group')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                   <!-- Di dalam section <div id="groupSettings" class="settings-section" ...> -->
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="qualify_per_group" class="form-label">
                Tim Lolos per Grup
                <span class="required">*</span>
            </label>
            <input type="number"
                class="form-control @error('qualify_per_group') is-invalid @enderror"
                id="qualify_per_group" name="qualify_per_group"
                value="{{ old('qualify_per_group', $tournamentData['qualify_per_group'] ?? 2) }}"
                min="1" max="4">
            @error('qualify_per_group')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
                                </div>
                                </div>

                                <!-- Konfigurasi League -->
                                <div id="leagueSettings" class="settings-section" style="display: {{ (old('type', $tournamentData['type'] ?? '') == 'league') ? 'block' : 'none' }};">
                                    <h6><i class="bi bi-trophy"></i> Pengaturan liga</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="league_rounds" class="form-label">
                                                    Jumlah Ronde
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('league_rounds') is-invalid @enderror" id="league_rounds" name="league_rounds">
                                                    <option value="1" {{ (old('league_rounds', $tournamentData['league_rounds'] ?? 1) == 1) ? 'selected' : '' }}>Satu putaran</option>
                                                    <option value="2" {{ (old('league_rounds', $tournamentData['league_rounds'] ?? 1) == 2) ? 'selected' : '' }}>Dua putaran</option>
                                                </select>
                                                @error('league_rounds')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i>
                                                    Satu putaran: setiap tim bertemu sekali. Dua putaran: setiap tim bertemu dua kali.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="league_standings_type" class="form-label">
                                                    Penentu peringkat liga
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('league_standings_type') is-invalid @enderror" id="league_standings_type" name="league_standings_type">
                                                    <option value="total_points" {{ (old('league_standings_type', $tournamentData['league_standings_type'] ?? 'total_points') == 'total_points') ? 'selected' : '' }}>Jumlah poin</option>
                                                    <option value="head_to_head" {{ (old('league_standings_type', $tournamentData['league_standings_type'] ?? 'total_points') == 'head_to_head') ? 'selected' : '' }}>Hasil pertemuan langsung</option>
                                                    <option value="goal_difference" {{ (old('league_standings_type', $tournamentData['league_standings_type'] ?? 'total_points') == 'goal_difference') ? 'selected' : '' }}>Selisih gol</option>
                                                </select>
                                                @error('league_standings_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i>
                                                    Cara menentukan peringkat jika tim memiliki poin yang sama.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-text mt-2">
                                        <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                                        Hasil seri otomatis diperbolehkan pada pertandingan liga.
                                    </div>
                                </div>

                                <!-- Pengaturan sistem gugur -->
                                <div id="knockoutSettings" class="settings-section" style="display: {{ (old('type', $tournamentData['type'] ?? '') == 'knockout') ? 'block' : 'none' }};">
                                    <h6><i class="bi bi-trophy"></i> Pengaturan sistem gugur</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="knockout_format" class="form-label">
                                                    Format turnamen
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('knockout_format') is-invalid @enderror" id="knockout_format" name="knockout_format">
                                                    <option value="single_elimination" {{ (old('knockout_format', $tournamentData['knockout_format'] ?? 'single_elimination') == 'single_elimination') ? 'selected' : '' }}>Sistem gugur tunggal</option>
                                                </select>
                                                @error('knockout_format')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i>
                                                    Tim yang kalah langsung tersingkir dari turnamen.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="knockout_teams" class="form-label">
                                                    Ukuran Bagan
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('knockout_teams') is-invalid @enderror" id="knockout_teams" name="knockout_teams">
                                                    <option value="2" {{ (old('knockout_teams', $tournamentData['knockout_teams'] ?? 8) == 2) ? 'selected' : '' }}>Maksimal 2 tim</option>
                                                    <option value="4" {{ (old('knockout_teams', $tournamentData['knockout_teams'] ?? 8) == 4) ? 'selected' : '' }}>Maksimal 4 tim</option>
                                                    <option value="8" {{ (old('knockout_teams', $tournamentData['knockout_teams'] ?? 8) == 8) ? 'selected' : '' }}>Maksimal 8 tim</option>
                                                    <option value="16" {{ (old('knockout_teams', $tournamentData['knockout_teams'] ?? 8) == 16) ? 'selected' : '' }}>Maksimal 16 tim</option>
                                                    <option value="32" {{ (old('knockout_teams', $tournamentData['knockout_teams'] ?? 8) == 32) ? 'selected' : '' }}>Maksimal 32 tim</option>
                                                    <option value="64" {{ (old('knockout_teams', $tournamentData['knockout_teams'] ?? 8) == 64) ? 'selected' : '' }}>Maksimal 64 tim</option>
                                                </select>
                                                @error('knockout_teams')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i>
                                                    Ini batas maksimal peserta. Bagan menyesuaikan jumlah tim ke kapasitas pangkat dua terkecil yang cukup. Sebagian tim dapat langsung lolos ke babak berikutnya. Contoh: 27 dari kapasitas 64 memakai bagan 32 slot dengan 5 tim lolos otomatis.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="knockout_seeding" class="form-label">
                                                    Metode penempatan tim
                                                    <span class="required">*</span>
                                                </label>
                                                <select class="form-select @error('knockout_seeding') is-invalid @enderror" id="knockout_seeding" name="knockout_seeding">
                                                    <option value="random" {{ (old('knockout_seeding', $tournamentData['knockout_seeding'] ?? 'random') == 'random') ? 'selected' : '' }}>Undian acak</option>
                                                    <option value="ranked" {{ (old('knockout_seeding', $tournamentData['knockout_seeding'] ?? 'random') == 'ranked') ? 'selected' : '' }}>Unggulan</option>
                                                    <option value="manual" {{ (old('knockout_seeding', $tournamentData['knockout_seeding'] ?? 'random') == 'manual') ? 'selected' : '' }}>Urutan manual</option>
                                                </select>
                                                @error('knockout_seeding')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i>
                                                    Acak: tim diundi. Unggulan: tim unggulan disebar merata. Manual: mengikuti urutan pendaftaran tim.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    Lolos otomatis
                                                </label>
                                                <div class="form-control d-flex align-items-center" style="background:#f8fafc;">
                                                    <i class="bi bi-magic me-2"></i>
                                                    <span id="byeInfoText">Dihitung otomatis saat tim didaftarkan</span>
                                                </div>
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i>
                                                    Tim lolos otomatis langsung maju ke babak berikutnya tanpa bertanding. Jumlahnya dihitung dari <strong>kapasitas bagan dikurangi jumlah tim terdaftar</strong>.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="thirdPlaceSettings" class="form-check mt-3 mb-3"
                                    style="display: {{ in_array(old('type', $tournamentData['type'] ?? ''), ['knockout', 'group_knockout'], true) ? 'block' : 'none' }};">
                                    <input type="hidden" name="knockout_third_place" value="0">
                                    <input class="form-check-input" type="checkbox" id="knockout_third_place" name="knockout_third_place" value="1" {{ old('knockout_third_place', $tournamentData['knockout_third_place'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="knockout_third_place">
                                        Aktifkan laga perebutan juara 3/4
                                    </label>
                                    <div class="form-text">Dua tim yang kalah di semifinal bertanding. Pemenangnya menjadi juara 3 dan tim lainnya menjadi juara 4. Pertandingan ini masuk ke bagan jika diaktifkan.</div>
                                </div>
                            </div>
                        </div>
                    </div>
