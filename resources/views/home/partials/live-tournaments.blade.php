{{-- Ultra Analytics Engine v3.0 — Live Tournaments Telemetry --}}
<div class="v3-live-tournaments">
    @if(isset($liveTournaments) && $liveTournaments->count() > 1)
        {{-- Telemetry HUD --}}
        <div class="v3-hud">
            <div class="v3-hud-left">
                <span class="v3-beacon"></span>
                <span class="v3-hud-tag">NEURAL TOURNAMENT TELEMETRY</span>
                <span class="v3-hud-sep">/</span>
                <span class="v3-hud-status">STATUS: {{ $liveTournaments->count() }} LIVE</span>
            </div>
            <div class="v3-hud-right">
                <span class="v3-hud-tag">OFS FUTSAL CENTER</span>
            </div>
        </div>

        {{-- Module Header --}}
        <header class="v3-lt-header">
            <div>
                <h2 class="v3-title">TOURNAMENT <span>MATRIX</span></h2>
                <p class="v3-lt-desc">Turnamen berjalan &middot; telemetri liga aktif</p>
            </div>
            <div class="v3-lt-stats">
                <div class="v3-stat-card">
                    <span class="v3-sc-lbl">LIVE EVENTS</span>
                    <span class="v3-sc-val">{{ $liveTournaments->count() }}</span>
                </div>
                <div class="v3-stat-card v3-stat-card--highlight">
                    <span class="v3-sc-lbl">FEATURED</span>
                    <span class="v3-sc-val">{{ $liveTournaments->where('is_featured', true)->count() }}</span>
                </div>
            </div>
        </header>

        {{-- Tournament Grid --}}
        <div class="v3-lt-grid">
            @foreach($liveTournaments as $lt)
                @php
                    $isActive = isset($activeTournament) && $activeTournament && $activeTournament->id === $lt->id;
                    $logoUrl = ($lt->logo && Storage::disk('public')->exists($lt->logo)) ? Storage::url($lt->logo) : null;
                @endphp
                <article class="v3-lt-card {{ $isActive ? 'is-active' : '' }}">
                    <span class="v3-lt-index">#{{ sprintf('%02d', $loop->iteration) }}</span>

                    <div class="v3-lt-card-top">
                        <div class="v3-lt-logo">
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="{{ $lt->name }}">
                            @else
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                            @endif
                        </div>
                        <div class="v3-lt-badges">
                            @if($lt->status === 'ongoing')
                                <span class="v3-lt-status v3-lt-status--ongoing"><span class="v3-lt-dot"></span>ONGOING</span>
                            @else
                                <span class="v3-lt-status v3-lt-status--upcoming">UPCOMING</span>
                            @endif
                            @if($isActive)
                                <span class="v3-lt-featured">★ FEATURED</span>
                            @endif
                        </div>
                    </div>

                    <h3 class="v3-lt-name" title="{{ $lt->name }}">{{ $lt->name }}</h3>

                    <div class="v3-lt-meta-grid">
                        <div class="v3-lt-meta-box">
                            <span class="v3-sc-lbl">FORMAT</span>
                            <span class="v3-lt-meta-val">{{ $lt->type_label }}</span>
                        </div>
                        <div class="v3-lt-meta-box">
                            <span class="v3-sc-lbl">TEAMS</span>
                            <span class="v3-lt-meta-val">{{ $lt->teams_count }}</span>
                        </div>
                    </div>

                    <div class="v3-lt-dates">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        {{ $lt->formatted_dates }}
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    <style>
        /* ==================================================================
           ULTRA ANALYTICS ENGINE v3.0 — live tournament telemetry module
           Scoped tokens (fallback to spec values)
           ================================================================== */
        .v3-live-tournaments {
            --v3-bg: #020408;
            --v3-surface: #070c14;
            --v3-card: #0d1524;
            --v3-border: #18263e;
            --v3-border-glow: rgba(0, 255, 135, 0.4);
            --v3-neon-green: #00ff87;
            --v3-neon-blue: #00e5ff;
            --v3-neon-pink: #ff0055;
            --v3-neon-yellow: #ffb700;
            --v3-text-main: #ffffff;
            --v3-text-sub: #8da1b9;
            --v3-text-muted: #4e6178;
            --v3-font-sans: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            --v3-font-mono: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;
            --v3-radius-lg: 20px;
            --v3-radius-md: 12px;
            --v3-radius-sm: 8px;

            background: var(--v3-bg);
            border: 1px solid var(--v3-border);
            border-radius: var(--v3-radius-lg);
            padding: 24px;
            margin-top: 24px;
            font-family: var(--v3-font-sans);
            color: var(--v3-text-main);
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.8);
        }

        /* Telemetry HUD */
        .v3-live-tournaments .v3-hud {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--v3-border);
            margin-bottom: 20px;
            font-size: 11px;
            font-weight: 800;
        }

        .v3-live-tournaments .v3-hud-left,
        .v3-live-tournaments .v3-hud-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .v3-live-tournaments .v3-beacon {
            width: 8px;
            height: 8px;
            background: var(--v3-neon-green);
            border-radius: 50%;
            box-shadow: 0 0 12px var(--v3-neon-green);
            animation: v3BeaconPulse 1.2s infinite alternate;
        }

        @keyframes v3BeaconPulse {
            from { opacity: 0.3; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1.2); }
        }

        .v3-live-tournaments .v3-hud-tag {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--v3-text-muted);
        }

        .v3-live-tournaments .v3-hud-sep { color: var(--v3-border); }
        .v3-live-tournaments .v3-hud-status {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            color: var(--v3-neon-green);
        }

        /* Header */
        .v3-live-tournaments .v3-lt-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .v3-live-tournaments .v3-title {
            font-size: 1.9rem;
            font-weight: 900;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: -0.03em;
            margin: 0;
            line-height: 1;
            text-shadow: 0 0 20px rgba(0, 255, 135, 0.35);
        }

        .v3-live-tournaments .v3-title span { color: var(--v3-neon-green); }

        .v3-live-tournaments .v3-lt-desc {
            color: var(--v3-text-sub);
            margin: 6px 0 0;
            font-size: 0.85rem;
        }

        .v3-live-tournaments .v3-lt-stats { display: flex; gap: 12px; }

        .v3-live-tournaments .v3-stat-card {
            background: var(--v3-surface);
            border: 1px solid var(--v3-border);
            padding: 10px 16px;
            border-radius: var(--v3-radius-sm);
            text-align: right;
        }

        .v3-live-tournaments .v3-sc-lbl {
            display: block;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--v3-text-muted);
        }

        .v3-live-tournaments .v3-sc-val {
            font-size: 1.2rem;
            font-weight: 900;
            font-style: italic;
            color: var(--v3-text-main);
        }

        .v3-live-tournaments .v3-stat-card--highlight { border-color: var(--v3-border-glow); }
        .v3-live-tournaments .v3-stat-card--highlight .v3-sc-val { color: var(--v3-neon-green); }

        /* Grid */
        .v3-live-tournaments .v3-lt-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 16px;
        }

        /* Card */
        .v3-live-tournaments .v3-lt-card {
            position: relative;
            background: linear-gradient(145deg, var(--v3-card), var(--v3-surface));
            border: 1px solid var(--v3-border);
            border-radius: var(--v3-radius-md);
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow: hidden;
            transition: transform 200ms ease, border-color 200ms ease, box-shadow 200ms ease;
        }

        .v3-live-tournaments .v3-lt-card:hover {
            transform: translateY(-3px);
            border-color: var(--v3-border-glow);
        }

        .v3-live-tournaments .v3-lt-card.is-active {
            border-color: var(--v3-neon-green);
            box-shadow: 0 0 24px rgba(0, 255, 135, 0.18), inset 0 0 0 1px rgba(0, 255, 135, 0.15);
        }

        .v3-live-tournaments .v3-lt-index {
            position: absolute;
            top: 6px;
            right: 10px;
            font-size: 12px;
            font-weight: 900;
            font-style: italic;
            color: var(--v3-text-muted);
            opacity: 0.5;
        }

        .v3-live-tournaments .v3-lt-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .v3-live-tournaments .v3-lt-logo {
            width: 42px;
            height: 42px;
            border-radius: var(--v3-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: var(--v3-bg);
            border: 1px solid var(--v3-border);
            color: var(--v3-neon-green);
            flex-shrink: 0;
        }

        .v3-live-tournaments .v3-lt-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .v3-live-tournaments .v3-lt-badges {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
        }

        .v3-live-tournaments .v3-lt-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 4px;
        }

        .v3-live-tournaments .v3-lt-status--ongoing {
            color: var(--v3-neon-green);
            border: 1px solid var(--v3-border-glow);
            background: rgba(0, 255, 135, 0.08);
        }

        .v3-live-tournaments .v3-lt-status--upcoming {
            color: var(--v3-text-sub);
            border: 1px solid var(--v3-border);
            background: var(--v3-surface);
        }

        .v3-live-tournaments .v3-lt-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--v3-neon-green);
            box-shadow: 0 0 8px var(--v3-neon-green);
            animation: v3BeaconPulse 1.2s infinite alternate;
        }

        .v3-live-tournaments .v3-lt-featured {
            font-size: 9px;
            font-weight: 900;
            font-style: italic;
            letter-spacing: 0.5px;
            color: var(--v3-neon-yellow);
        }

        .v3-live-tournaments .v3-lt-name {
            font-size: 1rem;
            font-weight: 900;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: -0.01em;
            margin: 0;
            line-height: 1.15;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .v3-live-tournaments .v3-lt-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .v3-live-tournaments .v3-lt-meta-box {
            background: var(--v3-bg);
            border: 1px solid var(--v3-border);
            border-radius: var(--v3-radius-sm);
            padding: 8px 10px;
        }

        .v3-live-tournaments .v3-lt-meta-val {
            display: block;
            margin-top: 2px;
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--v3-text-main);
        }

        .v3-live-tournaments .v3-lt-dates {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            color: var(--v3-text-sub);
            font-family: var(--v3-font-mono);
        }

        /* Responsive */
        @media (max-width: 900px) {
            .v3-live-tournaments { padding: 16px; }
            .v3-live-tournaments .v3-lt-header { flex-direction: column; align-items: flex-start; }
            .v3-live-tournaments .v3-title { font-size: 1.5rem; }
        }

        @media (max-width: 600px) {
            .v3-live-tournaments .v3-hud { flex-direction: column; align-items: flex-start; gap: 8px; }
            .v3-live-tournaments .v3-lt-grid { grid-template-columns: 1fr; }
            .v3-live-tournaments .v3-title { font-size: 1.25rem; }
        }
    </style>
</div>