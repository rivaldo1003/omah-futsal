@extends('layouts.admin')

@section('title', 'Add New Player')

@section('styles')
    <style>
        /* Menggunakan Variabel CSS Global dari Dashboard Sebelumnya */
        :root {
            --accent: #3B82F6;
            --primary: #1F2937;
            --secondary: #4B5563;
            --bg-main: #F9FAFB;
            --bg-card: #FFFFFF;
            --border-color: #E5E7EB;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

            /* Warna Status/Aksi */
            --success-light: #DCFCE7;
            --success-dark: #15803D;
            --warning-light: #FEF3C7;
            --warning-dark: #D97706;
            --danger-light: #FEE2E2;
            --danger-dark: #B91C1C;
        }

        body {
            background-color: var(--bg-main) !important;
        }

        /* Page Header - Clean & Defined */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 0;
            font-size: 1.8rem;
        }

        /* Form Card - Elevated & Rounded */
        .form-card {
            background: var(--bg-card);
            border-radius: 0.75rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            margin-bottom: 30px;
            overflow: hidden;
            transition: var(--transition);
        }

        .form-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .form-card .card-header {
            background-color: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            color: var(--primary);
            font-weight: 600;
        }

        .form-card .card-header h5 {
            margin-bottom: 0;
            font-weight: 600;
        }

        .form-card .card-body {
            padding: 1.5rem;
        }

        /* Form Styling */
        .form-label {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            transition: var(--transition);
            color: var(--primary);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .form-text {
            font-size: 0.75rem;
            color: var(--secondary);
            margin-top: 0.25rem;
        }

        /* Required Field Indicator */
        .required::after {
            content: " *";
            color: var(--danger-dark);
        }

        /* Form Sections */
        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .form-section:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--accent);
            border-color: var(--accent);
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-secondary {
            background-color: var(--secondary);
            border-color: var(--secondary);
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: var(--transition);
        }

        .btn-secondary:hover {
            background-color: #374151;
            border-color: #374151;
            transform: translateY(-1px);
        }

        /* Error Messages */
        .invalid-feedback {
            font-size: 0.75rem;
            color: var(--danger-dark);
            margin-top: 0.25rem;
        }

        .is-invalid {
            border-color: var(--danger-dark) !important;
        }

        .is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.1) !important;
        }

        /* Stats Input Group */
        .stats-input-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .stat-input {
            text-align: center;
        }

        .stat-input .form-control {
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.25rem;
        }

        /* Photo Upload Section */
        .photo-upload-container {
            border: 2px dashed var(--border-color);
            border-radius: 0.75rem;
            padding: 2rem;
            text-align: center;
            background: var(--bg-main);
            transition: var(--transition);
            cursor: pointer;
            margin-bottom: 1rem;
        }

        .photo-upload-container:hover {
            border-color: var(--accent);
            background: rgba(59, 130, 246, 0.05);
        }

        .photo-upload-container.dragover {
            border-color: var(--accent);
            background: rgba(59, 130, 246, 0.1);
        }

        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1rem;
            display: none;
            border: 4px solid white;
            box-shadow: var(--shadow-md);
        }

        .photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #6366F1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 1rem;
        }

        .photo-upload-text {
            color: var(--secondary);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .photo-upload-hint {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        .file-info {
            margin-top: 1rem;
            padding: 0.75rem;
            background: var(--bg-main);
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            font-size: 0.875rem;
            display: none;
        }

        .file-info.show {
            display: block;
        }

        .file-info .file-name {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }

        .file-info .file-size {
            color: var(--secondary);
            font-size: 0.75rem;
        }

        .photo-options {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            justify-content: center;
        }

        .btn-outline-secondary {
            border: 1px solid var(--border-color);
            color: var(--secondary);
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 0.375rem;
            transition: var(--transition);
        }

        .btn-outline-secondary:hover {
            background: var(--bg-main);
            border-color: var(--secondary);
        }

        /* Alert Messages */
        .alert {
            border: none;
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 1rem;
            border-left: 4px solid;
        }

        .alert i {
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .alert-success {
            background-color: var(--success-light);
            border-left-color: var(--success-dark);
            color: var(--success-dark);
        }

        .alert-danger {
            background-color: var(--danger-light);
            border-left-color: var(--danger-dark);
            color: var(--danger-dark);
        }

        .alert-warning {
            background-color: var(--warning-light);
            border-left-color: var(--warning-dark);
            color: var(--warning-dark);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .form-card .card-body {
                padding: 1rem;
            }

            .stats-input-group {
                grid-template-columns: repeat(2, 1fr);
            }

            .btn-group {
                width: 100%;
            }

            .btn-group .btn {
                flex: 1;
            }

            .photo-options {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .stats-input-group {
                grid-template-columns: 1fr;
            }

            .form-section-title {
                font-size: 1rem;
            }

            .photo-preview {
                width: 120px;
                height: 120px;
            }

            .photo-placeholder {
                width: 100px;
                height: 100px;
                font-size: 2rem;
            }
        }
    </style>
@endsection

@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                    style="color: var(--secondary);">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.players.index') }}"
                    style="color: var(--secondary);">Players</a></li>
            <li class="breadcrumb-item active" style="color: var(--primary);">Add New Player</li>
        </ol>
    </nav>

    <div class="page-header">
        <h1>
            <i class="bi bi-person-plus me-2"></i>
            <span>Add New Player</span>
        </h1>
        <a href="{{ route('admin.players.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Back to Players
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            <div>
                <strong>Error!</strong> {{ session('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i>
            <div>
                <strong>Success!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="form-card">
        <div class="card-header">
            <h5><i class="bi bi-person-circle me-2"></i> Player Information</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.players.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Personal Information Section -->
                <div class="form-section">
                    <h6 class="form-section-title">
                        <i class="bi bi-person-vcard"></i>
                        Basic Information
                    </h6>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label required">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}" placeholder="Enter player's full name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Enter the player's complete name</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="jersey_number" class="form-label">Jersey Number</label>
                                <input type="number" class="form-control @error('jersey_number') is-invalid @enderror"
                                    id="jersey_number" name="jersey_number" value="{{ old('jersey_number') }}"
                                    placeholder="e.g., 10" min="1" max="99">
                                @error('jersey_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Player's jersey number (1-99)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team & Position Section -->
                <div class="form-section">
                    <h6 class="form-section-title">
                        <i class="bi bi-people"></i>
                        Team & Position
                    </h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="team_id" class="form-label">Team</label>
                                <select class="form-select @error('team_id') is-invalid @enderror" id="team_id"
                                    name="team_id">
                                    <option value="">Select Team</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('team_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Select the team this player belongs to</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="position" class="form-label">Position</label>
                                <select class="form-select @error('position') is-invalid @enderror" id="position"
                                    name="position">
                                    <option value="">Select Position</option>
                                    <option value="Flank" {{ old('position') == 'Flank' ? 'selected' : '' }}>Flank</option>
                                    <option value="Anchor" {{ old('position') == 'Anchor' ? 'selected' : '' }}>Anchor</option>
                                    <option value="Pivot" {{ old('position') == 'Pivot' ? 'selected' : '' }}>Pivot</option>
                                    <option value="Kiper" {{ old('position') == 'Kiper' ? 'selected' : '' }}>Kiper</option>
                                </select>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Select the player's main position</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Section -->
                <div class="form-section">
                    <h6 class="form-section-title">
                        <i class="bi bi-bar-chart"></i>
                        Initial Statistics
                    </h6>

                    <div class="stats-input-group">
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
                </div>

                <!-- Photo Upload Section -->
                <div class="form-section">
                    <h6 class="form-section-title">
                        <i class="bi bi-camera"></i>
                        Player Photo
                    </h6>

                    <div class="mb-4">
                        <div class="photo-upload-container" id="photoUploadContainer"
                            onclick="document.getElementById('photo').click()">
                            <img id="photoPreview" class="photo-preview" alt="Preview">
                            <div id="photoPlaceholder" class="photo-placeholder">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="photo-upload-text">
                                Click to upload or drag and drop
                            </div>
                            <div class="photo-upload-hint">
                                PNG, JPG, JPEG up to 2MB
                            </div>
                        </div>

                        <div id="fileInfo" class="file-info">
                            <div class="file-name" id="fileName"></div>
                            <div class="file-size" id="fileSize"></div>
                        </div>

                        <input type="file" id="photo" name="photo" class="d-none" accept="image/*"
                            onchange="handleFileSelect(event)">

                        <div class="photo-options">
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                onclick="document.getElementById('photo').click()">
                                <i class="bi bi-upload me-1"></i> Choose File
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearPhoto()"
                                id="clearBtn" style="display: none;">
                                <i class="bi bi-x-circle me-1"></i> Remove Photo
                            </button>
                        </div>

                        @error('photo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Upload a clear photo of the player. Recommended size: 400x400px</div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="{{ route('admin.players.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        Create Player
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Handle file selection
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, GIF)');
                clearPhoto();
                return;
            }

            // Validate file size (2MB)
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            if (file.size > maxSize) {
                alert('File size must be less than 2MB');
                clearPhoto();
                return;
            }

            // Show preview
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

                clearBtn.style.display = 'inline-block';
            };
            reader.readAsDataURL(file);
        }

        // Clear photo selection
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

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Drag and drop functionality
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('photoUploadContainer');
            const fileInput = document.getElementById('photo');

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                container.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Highlight drop area
            ['dragenter', 'dragover'].forEach(eventName => {
                container.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                container.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                container.classList.add('dragover');
            }

            function unhighlight() {
                container.classList.remove('dragover');
            }

            // Handle drop
            container.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect({ target: { files: files } });
                }
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function (e) {
            const name = document.getElementById('name').value.trim();
            if (!name) {
                e.preventDefault();
                alert('Please enter player name');
                document.getElementById('name').focus();
                return false;
            }

            // Validate file if selected
            const fileInput = document.getElementById('photo');
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                const maxSize = 2 * 1024 * 1024;

                if (!validTypes.includes(file.type)) {
                    e.preventDefault();
                    alert('Please select a valid image file (JPEG, PNG, GIF)');
                    return false;
                }

                if (file.size > maxSize) {
                    e.preventDefault();
                    alert('File size must be less than 2MB');
                    return false;
                }
            }
        });

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection