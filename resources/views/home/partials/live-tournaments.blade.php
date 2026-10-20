{{-- Other live tournaments --}}
<div class="v3-live-tournaments">
    @if(isset($liveTournaments) && $liveTournaments->count() > 1)
        <header class="v3-lt-header">
            <div>
                <h2 class="v3-title">Turnamen berjalan</h2>
                <p class="v3-lt-desc">Jadwal dan status kompetisi yang sedang aktif.</p>
            </div>
            <div class="v3-lt-stats">
                <div class="v3-stat-card">
                    <span class="v3-sc-lbl">Turnamen</span>
                    <span class="v3-sc-val">{{ $liveTournaments->count() }}</span>
                </div>
                <div class="v3-stat-card v3-stat-card--highlight">
                    <span class="v3-sc-lbl">Pilihan utama</span>
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
                                <span class="v3-lt-status v3-lt-status--ongoing"><span class="v3-lt-dot"></span>Berjalan</span>
                            @else
                                <span class="v3-lt-status v3-lt-status--upcoming">Akan datang</span>
                            @endif
                            @if($isActive)
                                <span class="v3-lt-featured"><i class="bi bi-star-fill" aria-hidden="true"></i> Pilihan utama</span>
                            @endif
                        </div>
                    </div>

                    <h3 class="v3-lt-name" title="{{ $lt->name }}">{{ $lt->name }}</h3>

                    <div class="v3-lt-meta-grid">
                        <div class="v3-lt-meta-box">
                            <span class="v3-sc-lbl">Format</span>
                            <span class="v3-lt-meta-val">{{ $lt->type_label }}</span>
                        </div>
                        <div class="v3-lt-meta-box">
                            <span class="v3-sc-lbl">Tim</span>
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
           Public light theme for the live tournament list
           ================================================================== */
        .v3-live-tournaments {
            --v3-bg: var(--bg-page);
            --v3-surface: var(--surface);
            --v3-card: var(--surface-subtle);
            --v3-border: var(--border-color);
            --v3-border-glow: var(--success);
            --v3-neon-green: var(--success);
            --v3-neon-blue: #1151ff;
            --v3-neon-pink: #b42318;
            --v3-neon-yellow: #805600;
            --v3-text-main: var(--text-primary);
            --v3-text-sub: var(--text-secondary);
            --v3-text-muted: var(--text-muted);
            --v3-font-sans: "Helvetica Neue", Helvetica, sans-serif;
            --v3-font-mono: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;
            --v3-radius-lg: 8px;
            --v3-radius-md: 8px;
            --v3-radius-sm: 8px;

            background: transparent;
            border: 0;
            border-radius: 0;
            padding: 0;
            margin-top: 0;
            font-family: var(--v3-font-sans);
            color: var(--v3-text-main);
            position: relative;
            overflow: visible;
            box-shadow: none;
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
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0;
            line-height: 1.2;
            text-shadow: none;
        }

        .v3-live-tournaments .v3-lt-desc {
            color: var(--v3-text-sub);
            margin: 6px 0 0;
            font-size: 0.85rem;
        }

        .v3-live-tournaments .v3-lt-stats { display: flex; gap: 24px; }

        .v3-live-tournaments .v3-stat-card {
            display: grid;
            gap: 2px;
            padding: 0;
            border: 0;
            background: transparent;
            text-align: right;
        }

        .v3-live-tournaments .v3-sc-lbl {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: none;
            letter-spacing: 0;
            color: var(--v3-text-muted);
        }

        .v3-live-tournaments .v3-sc-val {
            font-size: 18px;
            font-weight: 700;
            font-style: normal;
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
            background: var(--v3-surface);
            border: 1px solid var(--v3-border);
            border-radius: var(--v3-radius-sm);
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow: hidden;
            transition: border-color 150ms ease;
        }

        .v3-live-tournaments .v3-lt-card:hover {
            border-color: var(--v3-border-glow);
        }

        .v3-live-tournaments .v3-lt-card.is-active {
            border-color: var(--v3-neon-green);
            box-shadow: none;
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
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0;
            text-transform: none;
            padding: 3px 8px;
            border-radius: 9999px;
        }

        .v3-live-tournaments .v3-lt-status--ongoing {
            color: var(--v3-neon-green);
            border: 1px solid var(--v3-border-glow);
            background: var(--success-subtle);
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
            box-shadow: none;
            animation: none;
        }

        .v3-live-tournaments .v3-lt-featured {
            font-size: 12px;
            font-weight: 600;
            color: var(--v3-neon-green);
        }

        .v3-live-tournaments .v3-lt-name {
            font-size: 18px;
            font-weight: 700;
            font-style: normal;
            text-transform: none;
            letter-spacing: 0;
            margin: 0;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .v3-live-tournaments .v3-lt-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            border-top: 1px solid var(--v3-border);
            padding-top: 12px;
        }

        .v3-live-tournaments .v3-lt-meta-box {
            min-width: 0;
        }

        .v3-live-tournaments .v3-lt-meta-val {
            display: block;
            margin-top: 2px;
            font-size: 14px;
            font-weight: 600;
            color: var(--v3-text-main);
        }

        .v3-live-tournaments .v3-lt-dates {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            font-weight: 500;
            color: var(--v3-text-sub);
            font-family: var(--v3-font-sans);
        }

        /* Responsive */
        @media (max-width: 900px) {
            .v3-live-tournaments .v3-lt-header { flex-direction: column; align-items: flex-start; }
            .v3-live-tournaments .v3-title { font-size: 1.5rem; }
        }

        @media (max-width: 600px) {
            .v3-live-tournaments .v3-lt-grid { grid-template-columns: 1fr; }
            .v3-live-tournaments .v3-title { font-size: 1.25rem; }
        }
    </style>
</div>
