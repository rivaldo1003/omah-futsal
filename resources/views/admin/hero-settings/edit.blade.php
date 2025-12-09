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
                                            value="{{ old('text_color', $heroSetting->text_color ?? '#ffffff') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Subtitle</label>
                                <textarea name="subtitle" class="form-control" rows="3"
                                    required>{{ old('subtitle', $heroSetting->subtitle) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">CTA Button Text</label>
                                        <input type="text" name="cta_button_text" class="form-control"
                                            value="{{ old('cta_button_text', $heroSetting->cta_button_text) }}"
                                            placeholder="e.g., View Schedule">
                                        <small class="text-muted">Leave empty to hide button</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">CTA Button Link</label>
                                        <input type="text" name="cta_button_link" class="form-control"
                                            value="{{ old('cta_button_link', $heroSetting->cta_button_link) }}"
                                            placeholder="e.g., /schedule">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Button Color</label>
                                        <input type="color" name="button_color" class="form-control form-control-color"
                                            value="{{ old('button_color', $heroSetting->button_color ?? '#3b82f6') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Button Text Color</label>
                                        <input type="color" name="button_text_color" class="form-control form-control-color"
                                            value="{{ old('button_text_color', $heroSetting->button_text_color ?? '#ffffff') }}">
                                    </div>
                                </div>
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

                            <div id="gradient-fields" class="form-group mb-3"
                                style="display: {{ $heroSetting->background_type == 'gradient' ? 'block' : 'none' }}">
                                <label class="form-label">Gradient Colors</label>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label small">Start Color</label>
                                        <input type="color" name="gradient_start" class="form-control form-control-color"
                                            value="{{ old('gradient_start', $heroSetting->gradient_start ?? '#0f172a') }}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label small">End Color</label>
                                        <input type="color" name="gradient_end" class="form-control form-control-color"
                                            value="{{ old('gradient_end', $heroSetting->gradient_end ?? '#1e293b') }}">
                                    </div>
                                </div>
                            </div>

                            <div id="image-field" class="form-group mb-3"
                                style="display: {{ $heroSetting->background_type == 'image' ? 'block' : 'none' }}">
                                <label class="form-label">Background Image</label>

                                @if($heroSetting->background_image)
                                    <div class="mb-3">
                                        <img src="{{ Storage::url($heroSetting->background_image) }}" alt="Current Background"
                                            style="max-width: 200px; border-radius: 8px; border: 1px solid #ddd;">
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

                                <!-- Overlay Opacity Control -->
                                <div class="mt-3" id="overlay-control">
                                    <label class="form-label">Image Overlay Opacity</label>
                                    <div class="d-flex align-items-center">
                                        <input type="range" name="overlay_opacity" class="form-range" min="0" max="100"
                                            step="1"
                                            value="{{ old('overlay_opacity', $heroSetting->overlay_opacity ?? 50) }}"
                                            id="overlay-opacity-slider">
                                        <span class="ms-3" id="overlay-opacity-value">
                                            {{ old('overlay_opacity', $heroSetting->overlay_opacity ?? 50) }}%
                                        </span>
                                    </div>
                                    <div class="text-muted small">
                                        Adjust the darkness overlay on top of background image (0% = no overlay, 100% =
                                        fully black)
                                    </div>
                                </div>
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
                                         style="max-width: 200px; border-radius: 8px; border: 1px solid #ddd;">
                                    <div class="text-muted small mt-1">New image preview</div>
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