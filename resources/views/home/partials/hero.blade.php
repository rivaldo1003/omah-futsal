@if($heroSetting->is_active)
    <header class="hero-wrap" id="mainHeroSection" style="
        @if($heroSetting->background_type === 'gradient')
            background: linear-gradient(135deg, {{ $heroSetting->gradient_start ?? '#0f172a' }}, {{ $heroSetting->gradient_end ?? '#1e293b' }});
        @elseif($heroSetting->background_type === 'color' && $heroSetting->background_color)
            background-color: {{ $heroSetting->background_color }};
        @elseif($heroSetting->background_type === 'image' && $heroSetting->background_image)
            background-image: url('{{ Storage::url($heroSetting->background_image) }}');
            background-size: cover;
            background-position: center;
            cursor: pointer;
        @else
            background: linear-gradient(135deg, #0f172a, #1e293b);
        @endif
        color: {{ $heroSetting->text_color ?? '#ffffff' }};
    " @if($heroSetting->background_type === 'image' && $heroSetting->background_image)
        onclick="openHeroFullscreen('{{ Storage::url($heroSetting->background_image) }}', '{{ addslashes($heroSetting->title) }}')"
        title="Click to view full image"
    @endif>

        @if($heroSetting->background_type === 'image' && $heroSetting->background_image && $heroSetting->overlay_opacity > 0)
            <div style="
                position: absolute;
                inset: 0;
                background-color: rgba(0, 0, 0, {{ $heroSetting->overlay_opacity / 100 }});
                z-index: 1;
                pointer-events: none;
            "></div>
        @endif

        <div class="app-container">
            <div class="hero-content">
                <h1 class="hero-title" style="color: {{ $heroSetting->text_color ?? '#ffffff' }};">
                    {{ $heroSetting->title }}
                </h1>

                @if($heroSetting->subtitle)
                    <p class="hero-subtitle" style="color: {{ $heroSetting->text_color ?? '#ffffff' }}; opacity: 0.9;">
                        {{ $heroSetting->subtitle }}
                    </p>
                @endif

                @if($heroSetting->cta_button_text)
                    <div>
                        <a href="{{ $heroSetting->cta_button_link ?? '#' }}" class="hero-cta hero-cta-button" style="
                            background-color: {{ $heroSetting->button_color ?? 'var(--accent)' }};
                            color: {{ $heroSetting->button_text_color ?? '#ffffff' }};
                        ">
                            <span>{{ $heroSetting->cta_button_text }}</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @endif

                @if($heroSetting->background_type === 'image' && $heroSetting->background_image)
                    <div class="mt-3">
                        <div class="hero-image-zoom-hint zoom-indicator">
                            <i class="bi bi-zoom-in"></i>
                            <span>Click background to view full image</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </header>
@else
    <div class="app-container mt-4">
        <div class="app-badge app-badge-default w-100 justify-content-center py-3">
            <i class="bi bi-info-circle me-1"></i>
            <span>Hero section is currently disabled.</span>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.hero-settings.edit') }}" class="ms-2 text-decoration-underline">Configure in admin</a>
                @endif
            @endauth
        </div>
    </div>
@endif
