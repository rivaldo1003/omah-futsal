@extends('layouts.admin')

@section('title', 'Tournament Draw — ' . $tournament->name)

@section('styles')
    <!-- Canvas Confetti Library -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        /* =============================================================
           ULTRA ANALYTICS ENGINE v3.0 — TOURNAMENT DRAW
           ============================================================= */
        /* v3 tokens shared by the stage and the fullscreen overlay
           (the overlay is a sibling, so it needs the variables too). */
        .tdw-stage,
        .tdw-overlay {
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
        }

        .tdw-stage {
            background: var(--v3-bg);
            color: var(--v3-text-main);
            font-family: var(--v3-font-sans);
            border: 1px solid var(--v3-border);
            border-radius: var(--v3-radius-lg);
            padding: 24px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.8);
        }

        .v3-hud {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--v3-border);
            margin-bottom: 24px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--v3-text-sub);
        }

        .v3-hud-left, .v3-hud-right { display: flex; align-items: center; gap: 10px; }
        .v3-hud-sep { opacity: 0.5; }
        .v3-hud-status { color: var(--v3-neon-green); }

        .v3-beacon {
            width: 8px;
            height: 8px;
            background: var(--v3-neon-green);
            border-radius: 50%;
            box-shadow: 0 0 12px var(--v3-neon-green);
            animation: v3BeaconPulse 1.2s infinite alternate;
            flex-shrink: 0;
        }

        @keyframes v3BeaconPulse {
            from { opacity: 0.3; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1.2); }
        }

        .tdw-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 18px;
            margin-bottom: 24px;
        }

        .tdw-title {
            font-size: 2rem;
            font-weight: 900;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: -0.03em;
            line-height: 1;
            margin: 0;
            color: var(--v3-text-main);
            text-shadow: 0 0 20px rgba(0, 255, 135, 0.35);
        }

        .tdw-title span { color: var(--v3-neon-green); }

        .tdw-sub { color: var(--v3-text-sub); font-size: 0.85rem; margin: 8px 0 0; }

        .tdw-status {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 8px 16px;
            border-radius: 999px;
            border: 1px solid var(--v3-border);
            background: var(--v3-surface);
            color: var(--v3-text-sub);
        }

        .tdw-status .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--v3-neon-green);
            box-shadow: 0 0 12px var(--v3-neon-green);
        }

        .tdw-status[data-state="DRAFT"] .dot { background: var(--v3-text-muted); box-shadow: none; }
        .tdw-status[data-state="DRAWING"] .dot { background: var(--v3-neon-yellow); box-shadow: 0 0 12px var(--v3-neon-yellow); }
        .tdw-status[data-state="DRAW_COMPLETED"] .dot { background: var(--v3-neon-green); box-shadow: 0 0 12px var(--v3-neon-green); }
        .tdw-status[data-state="IN_PROGRESS"] .dot { background: var(--v3-neon-blue); box-shadow: 0 0 12px var(--v3-neon-blue); }

        .tdw-grid {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 20px;
            align-items: start;
        }

        .tdw-panel {
            background: var(--v3-surface);
            border: 1px solid var(--v3-border);
            border-radius: var(--v3-radius-md);
            overflow: hidden;
        }

        .tdw-panel + .tdw-panel { margin-top: 16px; }

        .tdw-panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            border-bottom: 1px solid var(--v3-border);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--v3-text-sub);
        }

        .tdw-panel-head .count {
            color: var(--v3-neon-green);
            font-family: var(--v3-font-mono);
            font-variant-numeric: tabular-nums;
        }

        .tdw-panel-body { padding: 18px; }

        .tdw-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 38px;
            padding: 0 16px;
            border-radius: var(--v3-radius-sm);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            cursor: pointer;
            border: 1px solid var(--v3-border);
            background: var(--v3-card);
            color: var(--v3-text-main);
            transition: border-color 150ms ease, background 150ms ease, color 150ms ease, box-shadow 150ms ease;
        }

        .tdw-btn:hover {
            border-color: var(--v3-border-glow);
            color: var(--v3-neon-green);
            box-shadow: 0 0 16px rgba(0, 255, 135, 0.15);
        }

        .tdw-btn:disabled { opacity: 0.45; cursor: not-allowed; }

        .tdw-btn--primary {
            background: rgba(0, 255, 135, 0.12);
            color: var(--v3-text-main);
            border-color: var(--v3-neon-green);
            box-shadow: 0 0 18px rgba(0, 255, 135, 0.25);
        }
        .tdw-btn--primary:hover {
            background: rgba(0, 255, 135, 0.22);
            color: var(--v3-text-main);
            box-shadow: 0 0 26px rgba(0, 255, 135, 0.4);
        }

        .tdw-btn--danger { color: var(--v3-neon-pink); border-color: rgba(255, 0, 85, 0.35); }
        .tdw-btn--danger:hover { background: rgba(255, 0, 85, 0.08); border-color: var(--v3-neon-pink); box-shadow: 0 0 16px rgba(255, 0, 85, 0.2); }

        .tdw-team-scroll { max-height: 400px; overflow-y: auto; padding-right: 4px; }
        .tdw-team-scroll::-webkit-scrollbar { width: 6px; }
        .tdw-team-scroll::-webkit-scrollbar-track { background: var(--v3-bg); }
        .tdw-team-scroll::-webkit-scrollbar-thumb { background: var(--v3-border); border-radius: 4px; }

        .tdw-team {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            border: 1px solid var(--v3-border);
            border-radius: var(--v3-radius-sm);
            margin-bottom: 8px;
            background: var(--v3-card);
            transition: border-color 150ms ease, box-shadow 150ms ease, opacity 300ms ease, transform 300ms ease;
        }

        .tdw-team:hover { border-color: var(--v3-border-glow); }
        .tdw-team.is-drawing { border-color: var(--v3-neon-green); box-shadow: 0 0 18px rgba(0, 255, 135, 0.25); }
        .tdw-team.is-leaving { opacity: 0; transform: translateX(-24px); }

        .tdw-team-name { font-weight: 700; font-size: 0.9rem; color: var(--v3-text-main); }
        .tdw-team-meta { font-size: 11px; color: var(--v3-text-sub); margin-top: 2px; }

        .tdw-slots { display: flex; flex-wrap: wrap; gap: 7px; }

        .tdw-slot-num {
            min-width: 34px;
            height: 34px;
            padding: 0 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--v3-radius-sm);
            border: 1px solid var(--v3-border);
            background: var(--v3-card);
            font-size: 12px;
            font-weight: 800;
            font-family: var(--v3-font-mono);
            font-variant-numeric: tabular-nums;
            color: var(--v3-text-main);
            transition: opacity 300ms ease, transform 300ms ease, background 300ms ease, color 300ms ease;
        }

        .tdw-slot-num.taken { opacity: 0.25; text-decoration: line-through; border-color: transparent; background: transparent; }

        .tdw-slot-num.just-taken {
            background: rgba(0, 255, 135, 0.15);
            color: var(--v3-text-main);
            border-color: var(--v3-neon-green);
            transform: scale(1.25);
            box-shadow: 0 0 22px var(--v3-neon-green);
        }

        .tdw-bracket-wrapper { overflow-x: auto; padding: 8px 0; }
        .tdw-bracket-wrapper::-webkit-scrollbar { height: 8px; }
        .tdw-bracket-wrapper::-webkit-scrollbar-thumb { background: var(--v3-border); border-radius: 4px; }

        .tdw-bracket { display: flex; gap: 44px; min-width: max-content; }

        .tdw-round { display: flex; flex-direction: column; width: 240px; }

        .tdw-round-title {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--v3-neon-green);
            margin-bottom: 18px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--v3-border);
            border-left: 3px solid var(--v3-neon-green);
            padding-left: 10px;
        }

        .tdw-match-list { display: flex; flex-direction: column; justify-content: space-around; flex-grow: 1; }

        .tdw-match {
            position: relative;
            border: 1px solid var(--v3-border);
            border-radius: var(--v3-radius-sm);
            background: var(--v3-card);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.45);
            margin: 10px 0;
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }

        .tdw-match.is-final { border-color: var(--v3-border-glow); box-shadow: 0 0 22px rgba(0, 255, 135, 0.18); }

        .tdw-round:not(:last-child) .tdw-match::after {
            content: '';
            position: absolute;
            right: -22px;
            top: 50%;
            width: 22px;
            height: 1px;
            background: var(--v3-border);
            z-index: 1;
        }

        .tdw-side {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 13px;
            font-size: 0.86rem;
            min-height: 46px;
            position: relative;
            transition: background 300ms ease, box-shadow 300ms ease, transform 300ms ease;
        }

        .tdw-side + .tdw-side { border-top: 1px solid var(--v3-border); }

        .tdw-side .seed {
            font-size: 10px;
            font-weight: 800;
            color: var(--v3-text-sub);
            font-family: var(--v3-font-mono);
            min-width: 28px;
            height: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--v3-bg);
            border: 1px solid var(--v3-border);
            border-radius: 6px;
            font-variant-numeric: tabular-nums;
        }

        .tdw-side .nm {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: var(--v3-text-sub);
            font-weight: 500;
        }

        .tdw-side .nm.filled { color: var(--v3-text-main); font-weight: 700; }

        .tdw-pending {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            max-width: 100%;
            padding: 4px 9px;
            border: 1px solid rgba(0, 229, 255, 0.2);
            border-radius: 999px;
            background: rgba(0, 229, 255, 0.06);
            color: var(--v3-text-sub);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.08em;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .tdw-pending::before {
            content: '';
            width: 5px;
            height: 5px;
            flex: 0 0 5px;
            border-radius: 50%;
            background: var(--v3-neon-blue);
            box-shadow: 0 0 8px rgba(0, 229, 255, 0.6);
        }

        .tdw-side.slot-highlight {
            background: rgba(0, 255, 135, 0.18);
            box-shadow: 0 0 28px rgba(0, 255, 135, 0.55);
            transform: scale(1.03);
            z-index: 10;
            border-radius: var(--v3-radius-sm);
        }
        .tdw-side.slot-highlight .seed {
            background: rgba(0, 255, 135, 0.18);
            color: var(--v3-text-main);
            border-color: var(--v3-neon-green);
            box-shadow: 0 0 12px rgba(0, 255, 135, 0.5);
        }

        .tdw-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background: radial-gradient(circle at 50% 50%, rgba(13, 21, 36, 0.96) 0%, rgba(2, 4, 8, 0.99) 100%);
            backdrop-filter: blur(20px);
            opacity: 0;
            visibility: hidden;
            transition: opacity 400ms ease, visibility 400ms ease;
            overflow: hidden;
        }

        .tdw-overlay.is-open { opacity: 1; visibility: visible; }

        .tdw-ov-ring {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            border: 1px solid var(--v3-neon-green);
            opacity: 0.1;
            animation: pulseRing 3s infinite linear;
            pointer-events: none;
        }

        @keyframes pulseRing {
            0% { transform: scale(0.5); opacity: 0.6; }
            100% { transform: scale(2.2); opacity: 0; }
        }

        .tdw-ov-hud {
            position: absolute;
            top: 24px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--v3-text-sub);
        }

        .tdw-ov-stage { text-align: center; position: relative; z-index: 2; }

        .tdw-ov-team {
            font-size: 2rem;
            font-weight: 900;
            font-style: italic;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--v3-text-main);
            margin-bottom: 22px;
            text-shadow: 0 0 20px rgba(0, 255, 135, 0.35);
            transform: translateY(20px);
            opacity: 0;
            transition: transform 400ms ease, opacity 400ms ease;
        }

        .tdw-overlay.is-open .tdw-ov-team { transform: translateY(0); opacity: 1; }

        .tdw-ov-box {
            position: relative;
            width: 240px;
            height: 200px;
            margin: 0 auto 22px;
            background: var(--v3-surface);
            border: 1px solid var(--v3-border-glow);
            border-radius: var(--v3-radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 60px rgba(0, 255, 135, 0.2);
            perspective: 1000px;
        }

        .tdw-ov-number {
            font-size: 6.5rem;
            line-height: 1;
            font-weight: 900;
            font-style: italic;
            font-family: var(--v3-font-mono);
            color: var(--v3-neon-green);
            font-variant-numeric: tabular-nums;
            text-shadow: 0 0 40px var(--v3-neon-green);
            transition: transform 300ms cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .tdw-ov-number.is-rolling { animation: rollBlur 80ms infinite alternate; color: var(--v3-text-main); }
        .tdw-ov-number.is-locked { transform: scale(1.2); color: var(--v3-neon-green); }

        @keyframes rollBlur {
            0% { transform: translateY(-8px) scale(0.95); filter: blur(3px); }
            100% { transform: translateY(8px) scale(1.05); filter: blur(0px); }
        }

        .tdw-ov-caption {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--v3-text-sub);
        }

        .flying-badge {
            position: fixed;
            z-index: 100000;
            background: var(--v3-surface);
            color: var(--v3-text-main);
            border: 1px solid var(--v3-neon-green);
            font-weight: 900;
            padding: 10px 20px;
            border-radius: var(--v3-radius-sm);
            box-shadow: 0 0 30px var(--v3-neon-green);
            pointer-events: none;
            transition: all 850ms cubic-bezier(0.19, 1, 0.22, 1);
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        @media (max-width: 900px) {
            .tdw-stage { padding: 16px; }
            .tdw-grid { grid-template-columns: 1fr; }
            .tdw-title { font-size: 1.6rem; }
            .tdw-ov-team { font-size: 1.4rem; }
            .tdw-ov-box { width: 180px; height: 140px; }
            .tdw-ov-number { font-size: 4.5rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            .tdw-stage *, .tdw-overlay * {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="tdw-stage">
        {{-- Top Telemetry HUD --}}
        <div class="v3-hud">
            <div class="v3-hud-left">
                <span class="v3-beacon"></span>
                <span>ULTRA ANALYTICS ENGINE v3.0</span>
                <span class="v3-hud-sep">/</span>
                <span class="v3-hud-status">DRAW SYSTEM ONLINE</span>
            </div>
            <div class="v3-hud-right">
                <span>{{ $teamCount }} TIM</span>
                <span class="v3-hud-sep">/</span>
                @if($bracketCapacity > $bracketSize)
                    <span>KAPASITAS {{ $bracketCapacity }} / BAGAN AKTUAL {{ $bracketSize }} SLOT</span>
                @else
                    <span>BRACKET {{ $bracketSize }} SLOT</span>
                @endif
                @if($byeCount > 0)
                    <span class="v3-hud-sep">/</span>
                    <span>{{ $byeCount }} BYE</span>
                @endif
            </div>
        </div>

        {{-- Module header --}}
        <div class="tdw-head">
            <div>
                <h1 class="tdw-title">TOURNAMENT <span>DRAW STAGE</span></h1>
                <p class="tdw-sub">{{ $tournament->name }}</p>
            </div>
            <div class="tdw-status" data-state="{{ $status }}">
                <span class="dot"></span>
                <span id="drawStatusLabel">{{ $status }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="tdw-panel" style="margin-bottom:16px; border-color: var(--v3-border-glow);">
                <div class="tdw-panel-body" style="color:var(--v3-neon-green);">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="tdw-panel" role="alert" style="margin-bottom:16px; border-color: rgba(255, 0, 85, .55);">
                <div class="tdw-panel-body" style="color:#ff7b9d;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                </div>
            </div>
        @endif

        <div class="tdw-grid">
            {{-- LEFT column --}}
            <div>
                <div class="tdw-panel">
                    <div class="tdw-panel-head">Controls</div>
                    <div class="tdw-panel-body">
                        <div style="display:flex; flex-wrap:wrap; gap:8px;">
                            @if(!$hasBracket && !$hasKnockoutFixtures && !$hasDrawHistory && $teamCount >= 2)
                                <form method="POST" action="{{ route('admin.tournaments.draw.build', $tournament) }}">
                                    @csrf
                                    <button class="tdw-btn tdw-btn--primary" type="submit">
                                        <i class="bi bi-diagram-3"></i> Generate Bracket
                                    </button>
                                </form>
                            @endif

                            @if($hasBracket && $remainingTeams->count() > 0)
                                <form method="POST" action="{{ route('admin.tournaments.draw.all', $tournament) }}">
                                    @csrf
                                    <button class="tdw-btn" type="submit">
                                        <i class="bi bi-shuffle"></i> Undi Otomatis Sisa
                                    </button>
                                </form>
                            @endif

                            @if($status === 'DRAW_COMPLETED')
                                <form method="POST" action="{{ route('admin.tournaments.draw.generate-matches', $tournament) }}">
                                    @csrf
                                    <button class="tdw-btn tdw-btn--primary" type="submit" title="Buat match untuk seluruh babak bracket">
                                        <i class="bi bi-calendar2-check"></i> Generate Semua Match
                                    </button>
                                </form>
                            @endif

                            @if($hasBracket || $hasKnockoutFixtures || $hasDrawHistory)
                                <form method="POST" action="{{ route('admin.tournaments.draw.reset', $tournament) }}"
                                    onsubmit="return confirm('Reset akan menghapus undian dan fixture knockout yang belum dimainkan. Pertandingan yang sudah dimulai atau selesai tidak dapat direset. Lanjutkan?');">
                                    @csrf
                                    <input type="hidden" name="rebuild" value="1">
                                    <button class="tdw-btn tdw-btn--danger" type="submit">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if(!$hasBracket && !$hasKnockoutFixtures && !$hasDrawHistory && $teamCount < 2)
                            <p class="mb-0 mt-3" style="color: var(--v3-text-sub);">
                                Daftarkan minimal 2 tim untuk membuat bracket dan memulai undian.
                            </p>
                        @elseif(!$hasBracket && ($hasKnockoutFixtures || $hasDrawHistory))
                            <p class="mb-0 mt-3" style="color: var(--v3-text-sub);">
                                Data undian atau fixture lama ditemukan tanpa struktur bracket. Gunakan Reset untuk memulihkan;
                                fixture yang sudah dimulai atau memiliki hasil/event akan membuat reset ditolak demi menjaga data.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Team list --}}
                <div class="tdw-panel">
                    <div class="tdw-panel-head">
                        <span>Tim Belum Cabut Lot</span>
                        <span class="count" id="remainingCount">{{ $remainingTeams->count() }}</span>
                    </div>
                    <div class="tdw-panel-body">
                        <div class="tdw-team-scroll" id="teamList">
                            @forelse($remainingTeams as $team)
                                <div class="tdw-team" data-team-id="{{ $team->id }}" data-team-name="{{ $team->name }}">
                                    <div>
                                        <div class="tdw-team-name">{{ $team->name }}</div>
                                        <div class="tdw-team-meta">
                                            <i class="bi bi-person-fill me-1"></i>{{ $team->coach_name ?: 'Club member' }}
                                        </div>
                                    </div>
                                    @if($hasBracket)
                                        <button type="button" class="tdw-btn cabut-btn" data-team-id="{{ $team->id }}"
                                            data-team-name="{{ $team->name }}">
                                            <i class="bi bi-ticket-perforated"></i> Cabut
                                        </button>
                                    @else
                                        <button type="button" class="tdw-btn" disabled title="Generate Bracket terlebih dahulu">
                                            <i class="bi bi-lock"></i> Belum aktif
                                        </button>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-4" style="color: var(--v3-text-sub);">
                                    <i class="bi bi-check-all fs-3 d-block mb-2" style="color: var(--v3-neon-green);"></i>
                                    Semua tim telah diundi.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Available slots --}}
                <div class="tdw-panel">
                    <div class="tdw-panel-head">
                        <span>Ketersediaan Slot</span>
                        <span class="count" id="slotCount">{{ count($availableSlots) }}</span>
                    </div>
                    <div class="tdw-panel-body">
                        <div class="tdw-slots" id="slotChips">
                            @for($n = 1; $n <= $bracketSize; $n++)
                                @php $taken = !in_array($n, $availableSlots); @endphp
                                <span class="tdw-slot-num {{ $taken ? 'taken' : '' }}" data-slot="{{ $n }}">{{ $n }}</span>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT column: bracket --}}
            <div class="tdw-panel">
                <div class="tdw-panel-head">
                    <span>Knockout Bracket Visualizer</span>
                    <span style="color: var(--v3-neon-green);">
                        <i class="bi bi-broadcast me-1"></i>LIVE STAGE
                    </span>
                </div>
                <div class="tdw-panel-body">
                    @if(!$hasBracket)
                        <div class="text-center py-5" style="color: var(--v3-text-sub);">
                            <i class="bi bi-diagram-3 fs-1 d-block mb-3" style="color: var(--v3-text-muted);"></i>
                            @if($hasKnockoutFixtures || $hasDrawHistory)
                                Data undian lama ditemukan tanpa bracket. Gunakan Reset untuk memulihkan sebelum mengundi kembali.
                            @elseif($teamCount < 2)
                                Bracket bisa dibuat setelah minimal 2 tim terdaftar.
                            @else
                                Bracket belum dibuat. Klik <strong style="color: var(--v3-neon-green);">Generate Bracket</strong> untuk masuk ke mode undian.
                            @endif
                        </div>
                    @else
                        <div class="tdw-bracket-wrapper">
                            <div class="tdw-bracket" id="bracketTree">
                                @foreach($tree as $round)
                                    <div class="tdw-round">
                                        <div class="tdw-round-title">{{ $round['label'] }}</div>
                                        <div class="tdw-match-list">
                                            @foreach($round['matches'] as $rm)
                                                <div class="tdw-match {{ $rm['is_final'] ? 'is-final' : '' }}">
                                                    @foreach(['home' => $rm['home'], 'away' => $rm['away']] as $side => $slot)
                                                        @php
                                                            $isBye = $slot && $slot->source === 'bye';
                                                            $teamName = $slot && $slot->team ? $slot->team->name : null;
                                                        @endphp
                                                        <div class="tdw-side"
                                                            @if($slot && $slot->slot_number) data-slot="{{ $slot->slot_number }}" @endif>
                                                            <span class="seed">
                                                                {{ $slot && $slot->slot_number ? '#' . $slot->slot_number : '–' }}
                                                            </span>
                                                            <span class="nm {{ $teamName ? 'filled' : '' }}">
                                                                @if($teamName)
                                                                    {{ $teamName }}
                                                                @elseif($isBye)
                                                                    <em style="color: var(--v3-text-sub);">BYE</em>
                                                                @else
                                                                    <span class="tdw-pending" aria-label="Slot menunggu diisi">Menunggu</span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Fullscreen broadcast stage overlay --}}
    <div class="tdw-overlay" id="drawOverlay" aria-hidden="true">
        <div class="tdw-ov-hud">
            <span class="v3-beacon"></span>
            <span>LIVE DRAW</span>
        </div>
        <div class="tdw-ov-ring"></div>
        <div class="tdw-ov-stage">
            <div class="tdw-ov-team" id="ovTeam">—</div>
            <div class="tdw-ov-box">
                <div class="tdw-ov-number is-rolling" id="ovNumber">00</div>
            </div>
            <div class="tdw-ov-caption" id="ovCaption">MENGUNDI POSISI SLOT…</div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function () {
            const csrf = '{{ csrf_token() }}';
            const drawUrl = '{{ route('admin.tournaments.draw.cabut', $tournament) }}';
            const bracketSize = {{ (int) $bracketSize }};

            const remainingCount = document.getElementById('remainingCount');
            const slotCount = document.getElementById('slotCount');
            const statusLabel = document.getElementById('drawStatusLabel');
            const statusWrap = document.querySelector('.tdw-status');

            const overlay = document.getElementById('drawOverlay');
            const ovTeam = document.getElementById('ovTeam');
            const ovNumber = document.getElementById('ovNumber');
            const ovCaption = document.getElementById('ovCaption');

            const delay = (ms) => new Promise(r => setTimeout(r, ms));
            let busy = false;

            let audioCtx = null;

            function initAudio() {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
            }

            function playTickSFX() {
                try {
                    initAudio();
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = 'triangle';
                    osc.frequency.setValueAtTime(300 + Math.random() * 400, audioCtx.currentTime);
                    gain.gain.setValueAtTime(0.07, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.04);
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.04);
                } catch (e) {}
            }

            function playLockSFX() {
                try {
                    initAudio();
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(160, audioCtx.currentTime);
                    osc.frequency.exponentialRampToValueAtTime(35, audioCtx.currentTime + 0.4);
                    gain.gain.setValueAtTime(0.35, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.4);
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.4);
                } catch (e) {}
            }

            function playFanfareSFX() {
                try {
                    initAudio();
                    const notes = [523.25, 659.25, 783.99, 1046.50];
                    notes.forEach((freq, idx) => {
                        const osc = audioCtx.createOscillator();
                        const gain = audioCtx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(freq, audioCtx.currentTime + (idx * 0.08));
                        gain.gain.setValueAtTime(0.14, audioCtx.currentTime + (idx * 0.08));
                        gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + (idx * 0.08) + 0.3);
                        osc.connect(gain);
                        gain.connect(audioCtx.destination);
                        osc.start(audioCtx.currentTime + (idx * 0.08));
                        osc.stop(audioCtx.currentTime + (idx * 0.08) + 0.3);
                    });
                } catch (e) {}
            }

            function openOverlay(teamName) {
                ovTeam.textContent = teamName;
                ovNumber.textContent = '00';
                ovNumber.className = 'tdw-ov-number is-rolling';
                ovCaption.textContent = 'MENGUNDI POSISI SLOT…';
                overlay.classList.add('is-open');
            }

            function closeOverlay() {
                overlay.classList.remove('is-open');
            }

            function triggerConfetti() {
                if (typeof confetti === 'function') {
                    confetti({
                        particleCount: 100,
                        spread: 80,
                        origin: { y: 0.6 },
                        colors: ['#00ff87', '#00e5ff', '#ffffff']
                    });
                }
            }

            function animateFlyToSlot(teamName, slotNumber) {
                const targetSide = document.querySelector(`.tdw-side[data-slot="${slotNumber}"]`);
                if (!targetSide) return;

                const flyer = document.createElement('div');
                flyer.className = 'flying-badge';
                flyer.innerHTML = `<i class="bi bi-shield-lock-fill me-2"></i>${teamName} → SLOT #${slotNumber}`;
                document.body.appendChild(flyer);

                const startX = window.innerWidth / 2 - 120;
                const startY = window.innerHeight / 2 - 30;
                flyer.style.left = `${startX}px`;
                flyer.style.top = `${startY}px`;

                const rect = targetSide.getBoundingClientRect();
                const targetX = rect.left;
                const targetY = rect.top;

                requestAnimationFrame(() => {
                    flyer.style.left = `${targetX}px`;
                    flyer.style.top = `${targetY}px`;
                    flyer.style.transform = 'scale(0.75)';
                    flyer.style.opacity = '0.9';
                });

                setTimeout(() => {
                    flyer.remove();

                    const nm = targetSide.querySelector('.nm');
                    if (nm) {
                        nm.classList.add('filled');
                        nm.textContent = teamName;
                    }

                    playFanfareSFX();

                    targetSide.classList.add('slot-highlight');
                    targetSide.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });

                    setTimeout(() => targetSide.classList.remove('slot-highlight'), 2000);
                }, 850);
            }

            async function cabut(teamId, teamName, btn) {
                if (busy) return;
                busy = true;
                if (btn) btn.disabled = true;

                const row = document.querySelector(`.tdw-team[data-team-id="${teamId}"]`);
                if (row) row.classList.add('is-drawing');

                initAudio();
                openOverlay(teamName);

                const rollTimer = setInterval(() => {
                    const n = Math.floor(Math.random() * bracketSize) + 1;
                    ovNumber.textContent = String(n).padStart(2, '0');
                    playTickSFX();
                }, 55);

                const startedAt = performance.now();
                let data = null;

                try {
                    const res = await fetch(drawUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ team_id: teamId })
                    });
                    data = await res.json();
                } catch (e) {
                    data = { ok: false, message: e.message };
                }

                // Rolling duration: 5 seconds
                const DRAW_DURATION = 5000;
                const elapsed = performance.now() - startedAt;
                if (elapsed < DRAW_DURATION) await delay(DRAW_DURATION - elapsed);

                clearInterval(rollTimer);

                if (!data || !data.ok) {
                    ovCaption.textContent = 'GAGAL DIUNDI';
                    await delay(800);
                    closeOverlay();
                    if (row) row.classList.remove('is-drawing');
                    alert((data && data.message) || 'Gagal melakukan undian.');
                    if (btn) btn.disabled = false;
                    busy = false;
                    return;
                }

                const slotNumber = data.slot_number;

                playLockSFX();
                ovNumber.className = 'tdw-ov-number is-locked';
                ovNumber.textContent = String(slotNumber).padStart(2, '0');
                ovCaption.textContent = `TERKUNCI DI SLOT #${slotNumber}`;

                triggerConfetti();
                await delay(1300);

                closeOverlay();
                await delay(300);

                animateFlyToSlot(data.team.name, slotNumber);
                updateCounters(slotNumber);

                if (row) {
                    row.classList.remove('is-drawing');
                    row.classList.add('is-leaving');
                    setTimeout(() => row.remove(), 320);
                }

                if (data.status) {
                    statusLabel.textContent = data.status;
                    statusWrap.setAttribute('data-state', data.status);
                }

                if (data.remaining === 0) {
                    setTimeout(() => location.reload(), 2500);
                } else if (btn) {
                    btn.disabled = false;
                }

                busy = false;
            }

            function updateCounters(slotNumber) {
                if (remainingCount) {
                    remainingCount.textContent = Math.max(0, parseInt(remainingCount.textContent, 10) - 1);
                }
                if (slotCount) {
                    slotCount.textContent = Math.max(0, parseInt(slotCount.textContent, 10) - 1);
                }

                const chip = document.querySelector(`.tdw-slot-num[data-slot="${slotNumber}"]`);
                if (chip) {
                    chip.classList.add('just-taken');
                    setTimeout(() => chip.classList.add('taken'), 600);
                    setTimeout(() => chip.classList.remove('just-taken'), 1000);
                }
            }

            document.querySelectorAll('.cabut-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    cabut(btn.dataset.teamId, btn.dataset.teamName, btn);
                });
            });
        })();
    </script>
@endsection
