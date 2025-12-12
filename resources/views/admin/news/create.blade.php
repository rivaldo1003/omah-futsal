@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Create News Article</h1>
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Excerpt -->
                                <div class="mb-3">
                                    <label for="excerpt" class="form-label">Excerpt</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt"
                                        name="excerpt" rows="3">{{ old('excerpt') }}</textarea>
                                    @error('excerpt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content *</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" id="content"
                                        name="content" rows="10" required>{{ old('content') }}</textarea>
                                    @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Category -->
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category"
                                        name="category" required>
                                        <option value="">Select Category</option>
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
                                    <label for="author" class="form-label">Author</label>
                                    <input type="text" class="form-control @error('author') is-invalid @enderror"
                                        id="author" name="author" value="{{ old('author') }}">
                                    @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Source -->
                                <div class="mb-3">
                                    <label for="source" class="form-label">Source</label>
                                    <input type="text" class="form-control @error('source') is-invalid @enderror"
                                        id="source" name="source" value="{{ old('source') }}">
                                    @error('source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Source URL -->
                                <div class="mb-3">
                                    <label for="source_url" class="form-label">Source URL</label>
                                    <input type="url" class="form-control @error('source_url') is-invalid @enderror"
                                        id="source_url" name="source_url" value="{{ old('source_url') }}">
                                    @error('source_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Published Date -->
                                <div class="mb-3">
                                    <label for="published_at" class="form-label">Published Date *</label>
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
                                    <label for="image" class="form-label">Featured Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        id="image" name="image" accept="image/*">
                                    <div class="form-text">Max size: 2MB. Supported formats: JPG, PNG, GIF.</div>
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
                                            Active
                                        </label>
                                    </div>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_featured"
                                            name="is_featured" value="1">
                                        <label class="form-check-label" for="is_featured">
                                            Featured
                                        </label>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Create Article
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add CKEditor or TinyMCE for content editor -->
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
@endsection