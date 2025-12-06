{{-- resources/views/admin/hero-settings/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-sliders me-2"></i>
                            Hero Section Settings
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.hero-settings.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ old('title', $heroSetting->title) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Text Color</label>
                                        <input type="color" name="text_color" class="form-control form-control-color"
                                            value="{{ old('text_color', $heroSetting->text_color) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Subtitle</label>
                                <textarea name="subtitle" class="form-control" rows="3"
                                    required>{{ old('subtitle', $heroSetting->subtitle) }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                        value="1" {{ $heroSetting->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active (Show on homepage)
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Background Type</label>
                                <select name="background_type" class="form-select" id="background-type">
                                    <option value="gradient" {{ $heroSetting->background_type == 'gradient' ? 'selected' : '' }}>
                                        Gradient
                                    </option>
                                    <option value="image" {{ $heroSetting->background_type == 'image' ? 'selected' : '' }}>
                                        Image
                                    </option>
                                    <option value="color" {{ $heroSetting->background_type == 'color' ? 'selected' : '' }}>
                                        Solid Color
                                    </option>
                                </select>
                            </div>

                            <div id="color-field" class="form-group mb-3"
                                style="display: {{ $heroSetting->background_type == 'color' ? 'block' : 'none' }}">
                                <label class="form-label">Background Color</label>
                                <input type="color" name="background_color" class="form-control form-control-color"
                                    value="{{ old('background_color', $heroSetting->background_color ?? '#0f172a') }}">
                            </div>

                            <div id="image-field" class="form-group mb-3"
                                style="display: {{ $heroSetting->background_type == 'image' ? 'block' : 'none' }}">
                                <label class="form-label">Background Image</label>

                                @if($heroSetting->background_image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($heroSetting->background_image) }}" alt="Current Background"
                                            style="max-width: 200px; border-radius: 8px;">
                                        <div class="form-check mt-2">
                                            <input type="checkbox" name="remove_image" class="form-check-input"
                                                id="remove_image" value="1">
                                            <label class="form-check-label" for="remove_image">
                                                Remove current image
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                <input type="file" name="background_image" class="form-control" accept="image/*">
                                <small class="text-muted">Recommended size: 1920x600px</small>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i> Save Changes
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('background-type').addEventListener('change', function () {
            const type = this.value;
            document.getElementById('color-field').style.display = type === 'color' ? 'block' : 'none';
            document.getElementById('image-field').style.display = type === 'image' ? 'block' : 'none';
        });
    </script>
@endpush