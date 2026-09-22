@if(isset($topValuedPlayers) && $topValuedPlayers->count() > 0)
@php
    $totalValuation = $topValuedPlayers->sum('market_value');
    $maxValue       = $topValuedPlayers->max('market_value');

    // Max values across the listed players for real normalization (no dummy data)
    $maxGoals  = max($topValuedPlayers->max('goals') ?? 0, 1);
    $maxAssists = max($topValuedPlayers->max('assists') ?? 0, 1);
    $maxApps   = max($topValuedPlayers->max('appearances_count') ?? 0, 1);
    $maxSaves  = max($topValuedPlayers->max('saves') ?? 0, 1);
    $maxCS     = max($topValuedPlayers->max('clean_sheets') ?? 0, 1);

    $playersJson = $topValuedPlayers->map(function ($p, $idx) use ($maxValue, $maxGoals, $maxAssists, $maxApps, $maxSaves, $maxCS) {
        $id = $p->id ?? ($idx + 1);

        $goals       = (int) ($p->goals ?? 0);
        $assists     = (int) ($p->assists ?? 0);
        $appearances = (int) ($p->appearances_count ?? 0);
        $yellow      = (int) ($p->yellow_cards ?? 0);
        $red         = (int) ($p->red_cards ?? 0);
        $saves       = (int) ($p->saves ?? 0);
        $cleanSheets = (int) ($p->clean_sheets ?? 0);
        $value       = (int) $p->market_value;

        // Real, normalized metrics (0-100) relative to the top-valued cohort
        $skills = [
            'gol'  => $maxGoals   > 0 ? round(($goals / $maxGoals) * 100) : 0,
            'ast'  => $maxAssists > 0 ? round(($assists / $maxAssists) * 100) : 0,
            'app'  => $maxApps    > 0 ? round(($appearances / $maxApps) * 100) : 0,
            'gm'   => $goals > 0 ? min(100, round(($goals / max($appearances, 1)) * 100 * 2)) : 0, // goals-per-match impact
            'gk'   => max(round(($saves / $maxSaves) * 100), round(($cleanSheets / $maxCS) * 100)),
            'val'  => $maxValue > 0 ? round(($value / $maxValue) * 100) : 0,
        ];

        return [
            'id'             => $p->id,
            'name'           => $p->name,
            'photo'          => $p->photo_url,
            'cutout'         => $p->photo_cutout_url,
            'market_value'   => $value,
            'formatted'      => $p->formatted_market_value,
            'jersey_number'  => $p->jersey_number ?? ($idx + 1),
            'position'       => $p->position ?? 'FWD',
            'preferred_foot' => $p->preferred_foot ?? null,
            'age'            => $p->birth_date ? \Carbon\Carbon::parse($p->birth_date)->age : null,
            'team'           => $p->team?->name,
            'goals'          => $goals,
            'penalty_goals'  => (int) ($p->penalty_goals ?? 0),
            'assists'        => $assists,
            'appearances'    => $appearances,
            'yellow_cards'   => $yellow,
            'red_cards'      => $red,
            'saves'          => $saves,
            'clean_sheets'   => (int) ($p->clean_sheets ?? 0),
            'rank'           => $idx + 1,
            'pct'            => $maxValue > 0 ? round(($value / $maxValue) * 100) : 0,
            'skills'         => $skills,
        ];
    })->values()->toJson(JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

<section class="futsal-mvs-ultra" id="marketValueUltraSection" data-players="{{ $playersJson }}">
    
    {{-- Top Command Bar --}}
    <div class="fmvs-cmd-bar">
        <div class="fmvs-cmd-left">
            <div class="fmvs-status-badge">
                <span class="fmvs-radar-sweep"></span>
                <span>ULTRA ANALYTICS ENGINE v3.0</span>
            </div>
            <div class="fmvs-control-group">
                <label for="fmvsCurrencySelect">CURRENCY:</label>
                <select id="fmvsCurrencySelect">
                    <option value="IDR" selected>IDR (Rp)</option>
                    <option value="EUR">EUR (€)</option>
                    <option value="USD">USD ($)</option>
                </select>
            </div>
        </div>

        <div class="fmvs-cmd-right">
            <button type="button" class="fmvs-btn-icon" id="fmvsViewModeBtn" title="Toggle 3D View">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                <span>3D DEPTH</span>
            </button>
            <button type="button" class="fmvs-btn-icon" id="fmvsSfxBtn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5L6 9H2v6h4l5 4V5z"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/></svg>
                <span id="fmvsSfxStatus">SFX: ON</span>
            </button>
            <button type="button" class="fmvs-btn-action" id="fmvsOpenCompare">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 3h5v5M4 20L21 3M21 16v5h-5M15 15l6 6M4 4l5 5"/></svg>
                H2H ANALYZER
            </button>
        </div>
    </div>

    {{-- Main Header --}}
    <header class="fmvs-u-header">
        <div>
            <h1 class="fmvs-u-title">MARKET VALUE <span>OFS FUTSAL CENTER</span></h1>
            <p class="fmvs-u-desc">Sistem Evaluasi Kinerja, Value Matrix, dan Visualisasi Taktis Pemain</p>
        </div>
        <div class="fmvs-u-stats">
            <div class="fmvs-stat-card">
                <span class="fmvs-sc-label">TOTAL PLAYERS</span>
                <span class="fmvs-sc-val">{{ $topValuedPlayers->count() }}</span>
            </div>
            <div class="fmvs-stat-card fmvs-stat-card--highlight">
                <span class="fmvs-sc-label">TOTAL PORTFOLIO VALUE</span>
                <span class="fmvs-sc-val" id="fmvsCumulativeVal">{{ \App\Models\Player::formatMarketValue($totalValuation) }}</span>
            </div>
            <a href="{{ route('market-value.index') }}" class="fmvs-btn-action" style="text-decoration: none; align-self: center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                VIEW ALL
            </a>
        </div>
    </header>

    {{-- ── ULTRA STAGE: 3D PARALLAX SHOWCASE & DUAL CANVAS ── --}}
    <div class="fmvs-u-stage" id="fmvs3dStage">
        {{-- Background Effects --}}
        <div class="fmvs-stage-ambient">
            <div class="fmvs-ambient-glow" id="fmvsGlow"></div>
            <div class="fmvs-tech-grid"></div>
            <div class="fmvs-big-rank" id="fmvsBigRank">01</div>
        </div>

        {{-- Left: 3D Perspective Player Viewport --}}
        <div class="fmvs-3d-viewport" id="fmvsViewport3D">
            <div class="fmvs-3d-card" id="fmvsCard3D">
                <div class="fmvs-card-glare"></div>
                <div class="fmvs-card-badge" id="fmvsPosBadge">FWD</div>
                <div class="fmvs-card-number" id="fmvsJerseyNum">#10</div>
                <img id="fmvsPlayerCutout" src="" alt="" draggable="false">
            </div>
        </div>

        {{-- Right: Multi-Tab Analytics Panel --}}
        <div class="fmvs-u-panel">
            <div class="fmvs-panel-header">
                <div>
                    <h2 class="fmvs-p-name" id="fmvsPlayerName">—</h2>
                    <span class="fmvs-p-team" id="fmvsPlayerTeam">—</span>
                </div>
                <div class="fmvs-val-badge">
                    <span class="fmvs-vb-title">CURRENT VALUATION</span>
                    <span class="fmvs-vb-amount" id="fmvsValAmount">—</span>
                </div>
            </div>

            {{-- Navigation Tabs --}}
            <div class="fmvs-tabs">
                <button type="button" class="fmvs-tab is-active" data-tab="radar">PERFORMANCE RADAR</button>
                <button type="button" class="fmvs-tab" data-tab="breakdown">EVENT BREAKDOWN</button>
                <button type="button" class="fmvs-tab" data-tab="stats">PERFORMANCE METRICS</button>
            </div>

            {{-- Tab Views --}}
            <div class="fmvs-tab-content">
                {{-- Tab 1: Radar Canvas (real normalized stats) --}}
                <div class="fmvs-tab-pane is-active" id="paneRadar">
                    <div class="fmvs-canvas-wrap">
                        <canvas id="fmvsProRadar" width="220" height="220"></canvas>
                    </div>
                </div>

                {{-- Tab 2: Real Event Breakdown Bars --}}
                <div class="fmvs-tab-pane" id="paneBreakdown">
                    <div class="fmvs-breakdown" id="fmvsBreakdown"></div>
                </div>

                {{-- Tab 3: Detailed Metrics Grid --}}
                <div class="fmvs-tab-pane" id="paneStats">
                    <div class="fmvs-metrics-grid">
                        <div class="fmvs-metric-box">
                            <span class="fmvs-mb-val" id="mGoal">0</span>
                            <span class="fmvs-mb-lbl">GOALS</span>
                        </div>
                        <div class="fmvs-metric-box">
                            <span class="fmvs-mb-val" id="mAssist">0</span>
                            <span class="fmvs-mb-lbl">ASSISTS</span>
                        </div>
                        <div class="fmvs-metric-box">
                            <span class="fmvs-mb-val" id="mMatch">0</span>
                            <span class="fmvs-mb-lbl">MATCHES</span>
                        </div>
                        <div class="fmvs-metric-box">
                            <span class="fmvs-mb-val" id="mExtra">0</span>
                            <span class="fmvs-mb-lbl" id="mExtraLbl">CLEAN SHEETS</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Relative Meter --}}
            <div class="fmvs-relative-meter">
                <div class="fmvs-rm-text">
                    <span>INDEX COMPARISON vs LEAGUE TOP</span>
                    <span id="fmvsRelativePct">100%</span>
                </div>
                <div class="fmvs-rm-bar"><div id="fmvsRelativeFill"></div></div>
            </div>
        </div>
    </div>

    {{-- ── CAROUSEL RAIL & SEARCH ── --}}
    <div class="fmvs-bottom-rail">
        <div class="fmvs-rail-header">
            <input type="text" id="fmvsSearchBox" placeholder="Filter by player name..." autocomplete="off">
            <div class="fmvs-pos-pills" id="fmvsPosPills">
                <button type="button" class="is-active" data-pos="ALL">ALL</button>
                <button type="button" data-pos="FWD">FWD</button>
                <button type="button" data-pos="ALA">ALA</button>
                <button type="button" data-pos="FIXO">FIXO</button>
                <button type="button" data-pos="GK">GK</button>
            </div>
        </div>

        <div class="fmvs-carousel-viewport" id="fmvsCarouselViewport">
            <div class="fmvs-carousel-track" id="fmvsCarouselTrack">
                @foreach($topValuedPlayers as $idx => $player)
                @php
                    $photo = $player->photo ?? null;
                    $photoUrl = null;
                    if ($photo) {
                        if (filter_var($photo, FILTER_VALIDATE_URL)) {
                            $photoUrl = $photo;
                        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($photo)) {
                            $photoUrl = asset('storage/' . $photo);
                        }
                    }
                @endphp
                <div class="fmvs-u-card{{ $idx === 0 ? ' is-active' : '' }}"
                     data-index="{{ $idx }}"
                     data-pos="{{ strtoupper($player->position ?? 'FWD') }}"
                     data-name="{{ strtolower($player->name) }}">
                    <span class="fmvs-uc-rank">#{{ sprintf('%02d', $idx + 1) }}</span>
                    <div class="fmvs-uc-avatar">
                        <img src="{{ $photoUrl ?? asset('assets/img/player-placeholder-new.png') }}"
                             onerror="this.onerror=null; this.src='{{ asset('assets/img/player-placeholder-new.png') }}'"
                             alt="{{ $player->name }}" loading="lazy">
                    </div>
                    <div class="fmvs-uc-details">
                        <strong>{{ $player->name }}</strong>
                        <span>{{ $player->formatted_market_value }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── H2H ANALYZER MODAL ── --}}
    <div class="fmvs-u-modal" id="fmvsH2hModal" hidden>
        <div class="fmvs-um-backdrop" id="fmvsModalBackdrop"></div>
        <div class="fmvs-um-window">
            <div class="fmvs-um-header">
                <h3>HEAD-TO-HEAD TACTICAL COMPARISON</h3>
                <button type="button" id="fmvsCloseModal">&times;</button>
            </div>
            <div class="fmvs-um-body">
                <div class="fmvs-h2h-selects">
                    <select id="h2hSelect1"></select>
                    <span class="fmvs-h2h-vs">VS</span>
                    <select id="h2hSelect2"></select>
                </div>
                <div class="fmvs-h2h-comparison" id="h2hComparisonRows"></div>
            </div>
        </div>
    </div>
</section>

<style>
/* =============================================================
   ULTRA BROADCAST STYLES (v3.0) - DARK FUTURISTIC
   ============================================================= */

:root {
    --u-bg: #05070a;
    --u-surface: #0a0f18;
    --u-card: #111827;
    --u-border: #1f293d;
    --u-accent: #00ff87; /* Neo Green */
    --u-accent-cyan: #00e5ff;
    --u-text: #ffffff;
    --u-muted: #64748b;
    --u-font: system-ui, -apple-system, sans-serif;
}

.futsal-mvs-ultra {
    background: var(--u-bg);
    border: 1px solid var(--u-border);
    border-radius: 16px;
    padding: 24px;
    font-family: var(--u-font);
    color: var(--u-text);
    position: relative;
    overflow: hidden;
    box-shadow: 0 30px 60px rgba(0,0,0,0.8);
}

/* Command Bar */
.fmvs-cmd-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--u-border);
    margin-bottom: 20px;
    font-size: 11px;
    font-weight: 800;
}

.fmvs-cmd-left, .fmvs-cmd-right { display: flex; align-items: center; gap: 14px; }

.fmvs-status-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--u-accent);
    letter-spacing: 1px;
}

.fmvs-radar-sweep {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--u-accent);
    box-shadow: 0 0 12px var(--u-accent);
    animation: uPulse 1.8s infinite;
}

@keyframes uPulse {
    0% { transform: scale(0.9); opacity: 0.8; }
    50% { transform: scale(1.2); opacity: 1; box-shadow: 0 0 20px var(--u-accent); }
    100% { transform: scale(0.9); opacity: 0.8; }
}

.fmvs-control-group select {
    background: var(--u-surface);
    color: var(--u-text);
    border: 1px solid var(--u-border);
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 700;
}

.fmvs-btn-icon, .fmvs-btn-action {
    background: var(--u-surface);
    border: 1px solid var(--u-border);
    color: var(--u-text);
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 10px;
    font-weight: 800;
    transition: all 200ms ease;
}

.fmvs-btn-action { background: var(--u-accent); color: #000; border-color: var(--u-accent); }
.fmvs-btn-action:hover { background: #00db74; transform: translateY(-1px); }

/* Header */
.fmvs-u-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; }
.fmvs-u-title { font-size: 2rem; font-weight: 900; font-style: italic; margin: 0; line-height: 1; }
.fmvs-u-title span { color: var(--u-accent); }
.fmvs-u-desc { color: var(--u-muted); margin: 6px 0 0; font-size: 0.85rem; }

.fmvs-u-stats { display: flex; gap: 12px; }
.fmvs-stat-card {
    background: var(--u-surface);
    border: 1px solid var(--u-border);
    padding: 10px 16px;
    border-radius: 8px;
    text-align: right;
}
.fmvs-sc-label { display: block; font-size: 9px; font-weight: 800; color: var(--u-muted); }
.fmvs-sc-val { font-size: 1.1rem; font-weight: 900; }
.fmvs-stat-card--highlight .fmvs-sc-val { color: var(--u-accent); }

/* 3D Stage */
.fmvs-u-stage {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 24px;
    background: var(--u-surface);
    border: 1px solid var(--u-border);
    border-radius: 12px;
    padding: 24px;
    position: relative;
    min-height: 440px;
    margin-bottom: 24px;
    overflow: hidden;
}

.fmvs-stage-ambient { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
.fmvs-ambient-glow {
    position: absolute;
    top: 50%;
    left: 20%;
    transform: translate(-50%, -50%);
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(0,255,135,0.15) 0%, transparent 70%);
    transition: all 500ms ease;
}

.fmvs-tech-grid {
    position: absolute; inset: 0;
    background-image: radial-gradient(var(--u-border) 1px, transparent 1px);
    background-size: 20px 20px;
    opacity: 0.3;
}

.fmvs-big-rank {
    position: absolute; right: 10px; bottom: -40px;
    font-size: 200px; font-weight: 900; font-style: italic;
    color: rgba(255,255,255,0.02); line-height: 1; user-select: none;
}

/* 3D Viewport Card */
.fmvs-3d-viewport {
    perspective: 1000px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.fmvs-3d-card {
    width: 280px;
    height: 380px;
    background: linear-gradient(145deg, #182232, #0d131d);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    position: relative;
    box-shadow: 0 20px 40px rgba(0,0,0,0.6);
    transform-style: preserve-3d;
    transition: transform 150ms ease-out;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    overflow: hidden;
}

.fmvs-card-glare {
    position: absolute; inset: 0;
    background: linear-gradient(125deg, rgba(255,255,255,0.15) 0%, transparent 60%);
    pointer-events: none;
}

.fmvs-card-badge {
    position: absolute; top: 16px; left: 16px;
    background: var(--u-accent); color: #000;
    font-weight: 900; font-style: italic;
    padding: 4px 10px; border-radius: 4px; font-size: 12px;
}

.fmvs-card-number {
    position: absolute; top: 12px; right: 16px;
    font-size: 2rem; font-weight: 900; font-style: italic;
    color: rgba(255,255,255,0.2);
}

.fmvs-3d-card img {
    height: 320px;
    object-fit: contain;
    filter: drop-shadow(0 15px 20px rgba(0,0,0,0.8));
    transform: translateZ(30px);
    transition: transform 200ms ease;
}

/* Panel Right */
.fmvs-u-panel { display: flex; flex-direction: column; justify-content: space-between; z-index: 2; }
.fmvs-panel-header { display: flex; justify-content: space-between; align-items: center; }
.fmvs-p-name { font-size: 2rem; font-weight: 900; font-style: italic; margin: 0; line-height: 1; text-transform: uppercase; }
.fmvs-p-team { color: var(--u-muted); font-size: 0.85rem; font-weight: 700; }

.fmvs-val-badge { text-align: right; }
.fmvs-vb-title { display: block; font-size: 8px; font-weight: 800; color: var(--u-muted); }
.fmvs-vb-amount { font-size: 1.8rem; font-weight: 900; font-style: italic; color: var(--u-accent); }

/* Tabs */
.fmvs-tabs { display: flex; gap: 8px; border-bottom: 1px solid var(--u-border); margin: 16px 0; }
.fmvs-tab {
    background: none; border: none; color: var(--u-muted);
    font-size: 11px; font-weight: 800; padding: 8px 12px;
    cursor: pointer; border-bottom: 2px solid transparent; transition: all 200ms ease;
}
.fmvs-tab.is-active { color: var(--u-accent); border-color: var(--u-accent); }

.fmvs-tab-content { min-height: 200px; display: flex; align-items: center; justify-content: center; }
.fmvs-tab-pane { display: none; width: 100%; }
.fmvs-tab-pane.is-active { display: block; }

.fmvs-canvas-wrap { display: flex; justify-content: center; }

/* Event Breakdown Bars */
.fmvs-breakdown { display: flex; flex-direction: column; gap: 10px; padding: 4px 8px; }
.fmvs-bd-row { display: grid; grid-template-columns: 110px 1fr 40px; align-items: center; gap: 10px; }
.fmvs-bd-lbl { font-size: 10px; font-weight: 800; color: var(--u-muted); letter-spacing: 1px; }
.fmvs-bd-bar { height: 8px; background: var(--u-card); border: 1px solid var(--u-border); border-radius: 4px; overflow: hidden; }
.fmvs-bd-fill { height: 100%; background: var(--u-accent); border-radius: 4px; transition: width 500ms cubic-bezier(0.16, 1, 0.3, 1); }
.fmvs-bd-fill--warn { background: var(--u-accent-cyan); }
.fmvs-bd-fill--bad { background: #ff0055; }
.fmvs-bd-val { font-size: 12px; font-weight: 900; text-align: right; }

/* Metrics */
.fmvs-metrics-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
.fmvs-metric-box { background: var(--u-card); border: 1px solid var(--u-border); padding: 12px; border-radius: 8px; text-align: center; }
.fmvs-mb-val { display: block; font-size: 1.5rem; font-weight: 900; color: #fff; }
.fmvs-mb-lbl { font-size: 9px; font-weight: 800; color: var(--u-muted); }

/* Relative Bar */
.fmvs-relative-meter { margin-top: 16px; }
.fmvs-rm-text { display: flex; justify-content: space-between; font-size: 9px; font-weight: 800; color: var(--u-muted); margin-bottom: 6px; }
.fmvs-rm-bar { height: 6px; background: var(--u-card); border-radius: 3px; overflow: hidden; border: 1px solid var(--u-border); }
#fmvsRelativeFill { height: 100%; background: var(--u-accent); width: 0%; transition: width 600ms cubic-bezier(0.16, 1, 0.3, 1); }

/* Bottom Rail */
.fmvs-rail-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.fmvs-rail-header input {
    background: var(--u-surface); border: 1px solid var(--u-border);
    color: #fff; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; width: 220px;
}
.fmvs-pos-pills { display: flex; gap: 4px; }
.fmvs-pos-pills button {
    background: var(--u-surface); border: 1px solid var(--u-border);
    color: var(--u-muted); padding: 4px 10px; border-radius: 4px;
    font-size: 10px; font-weight: 800; cursor: pointer;
}
.fmvs-pos-pills button.is-active { background: var(--u-accent); color: #000; border-color: var(--u-accent); }

.fmvs-carousel-viewport { overflow-x: auto; scrollbar-width: none; }
.fmvs-carousel-viewport::-webkit-scrollbar { display: none; }
.fmvs-carousel-track { display: flex; gap: 12px; }

.fmvs-u-card {
    flex: 0 0 180px; background: var(--u-surface); border: 1px solid var(--u-border);
    padding: 10px; border-radius: 8px; display: flex; align-items: center; gap: 10px;
    cursor: pointer; position: relative; transition: all 200ms ease;
}
.fmvs-u-card.is-active { border-color: var(--u-accent); background: var(--u-card); box-shadow: 0 0 15px rgba(0,255,135,0.2); }
.fmvs-uc-rank { position: absolute; top: 4px; right: 6px; font-size: 9px; font-weight: 900; color: var(--u-muted); }
.fmvs-uc-avatar { width: 36px; height: 36px; border-radius: 50%; overflow: hidden; background: var(--u-bg); }
.fmvs-uc-avatar img { width: 100%; height: 100%; object-fit: cover; }
.fmvs-uc-details strong { display: block; font-size: 0.8rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.fmvs-uc-details span { font-size: 0.75rem; color: var(--u-accent); font-weight: 700; }

/* Modal */
.fmvs-u-modal { position: fixed; inset: 0; z-index: 99999; display: flex; align-items: center; justify-content: center; }
.fmvs-u-modal[hidden] { display: none; }
.fmvs-um-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(10px); }
.fmvs-um-window { position: relative; z-index: 2; background: var(--u-surface); border: 1px solid var(--u-border); border-radius: 16px; width: 90%; max-width: 650px; padding: 24px; }
.fmvs-um-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--u-border); padding-bottom: 12px; }
.fmvs-um-header h3 { margin: 0; font-size: 1.2rem; font-style: italic; font-weight: 900; }
.fmvs-um-header button { background: none; border: none; color: #fff; font-size: 1.5rem; cursor: pointer; }
.fmvs-h2h-selects { display: grid; grid-template-columns: 1fr 40px 1fr; gap: 12px; align-items: center; text-align: center; margin-bottom: 20px; }
.fmvs-h2h-selects select { background: var(--u-bg); color: #fff; border: 1px solid var(--u-border); padding: 8px; border-radius: 6px; font-weight: 700; width: 100%; }
.fmvs-h2h-vs { font-weight: 900; font-style: italic; color: var(--u-accent); }
.fmvs-h2h-comparison { display: flex; flex-direction: column; gap: 8px; }
.fmvs-h2h-row { display: grid; grid-template-columns: 1fr 120px 1fr; padding: 10px; background: var(--u-card); border-radius: 6px; text-align: center; font-size: 0.85rem; font-weight: 800; }
.fmvs-h2h-row .win { color: var(--u-accent); }

/* ── Mobile ≤ 850px ──────────────────────────────────────────── */
@media (max-width: 850px) {
    .futsal-mvs-ultra { padding: 14px 12px; }

    /* Command bar: stack two rows on narrow screens */
    .fmvs-cmd-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        padding-bottom: 12px;
        margin-bottom: 14px;
    }
    .fmvs-cmd-right { flex-wrap: wrap; gap: 8px; }
    /* Hide 3D + SFX buttons on mobile – save space */
    #fmvsViewModeBtn, #fmvsSfxBtn { display: none; }

    /* Header: stack */
    .fmvs-u-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 16px;
    }
    .fmvs-u-title { font-size: 1.3rem; }
    .fmvs-u-stats { flex-wrap: wrap; gap: 8px; width: 100%; }
    .fmvs-stat-card { flex: 1 1 120px; text-align: left; }

    /* Stage: single column, shrunk */
    .fmvs-u-stage {
        grid-template-columns: 1fr;
        min-height: auto;
        padding: 14px 12px;
        gap: 16px;
    }

    /* 3D card: smaller & centered */
    .fmvs-3d-viewport { min-height: 220px; }
    .fmvs-3d-card { width: 180px; height: 240px; }
    .fmvs-3d-card img { height: 200px; }
    .fmvs-card-number { font-size: 1.3rem; }

    /* Analytics panel: full width */
    .fmvs-u-panel { width: 100%; }
    .fmvs-panel-header { flex-wrap: wrap; gap: 8px; }
    .fmvs-p-name { font-size: 1.3rem; }
    .fmvs-vb-amount { font-size: 1.2rem; }

    /* ── Tabs: scrollable, no wrap ── */
    .fmvs-tabs { overflow-x: auto; scrollbar-width: none; gap: 4px; }
    .fmvs-tabs::-webkit-scrollbar { display: none; }
    .fmvs-tab { font-size: 10px; padding: 6px 10px; white-space: nowrap; }

    /* ── Tab content area ── */
    .fmvs-tab-content {
        min-height: 0;          /* let content dictate height */
        align-items: flex-start;
        padding-top: 8px;
    }
    .fmvs-tab-pane { width: 100%; }

    /* ── PERFORMANCE RADAR: responsive canvas ── */
    .fmvs-canvas-wrap {
        display: flex;
        justify-content: center;
        padding: 8px 0;
    }
    /* Override the hard-coded 220×220 attribute via CSS */
    #fmvsProRadar {
        max-width: 100%;
        width: 200px !important;
        height: 200px !important;
    }

    /* ── EVENT BREAKDOWN: fix label + bar layout on narrow screens ── */
    .fmvs-breakdown { padding: 4px 0; gap: 8px; }
    .fmvs-bd-row {
        grid-template-columns: 90px 1fr 32px;
        gap: 8px;
    }
    .fmvs-bd-lbl { font-size: 9px; }
    .fmvs-bd-val { font-size: 11px; }

    /* ── PERFORMANCE METRICS grid ── */
    .fmvs-metrics-grid { grid-template-columns: repeat(4, 1fr); gap: 8px; }
    .fmvs-mb-val { font-size: 1.1rem; }
    .fmvs-metric-box { padding: 8px 6px; }

    /* Bottom rail: wrap search + pills */
    .fmvs-rail-header { flex-wrap: wrap; gap: 8px; }
    .fmvs-rail-header input { width: 100%; }
    .fmvs-pos-pills { flex-wrap: wrap; }

    /* Carousel cards */
    .fmvs-u-card { flex: 0 0 150px; }
}

/* ── Mobile ≤ 480px ──────────────────────────────────────────── */
@media (max-width: 480px) {
    .fmvs-u-title { font-size: 1.1rem; }
    .fmvs-3d-card { width: 150px; height: 200px; }
    .fmvs-3d-card img { height: 165px; }

    /* Radar: even smaller on tiny phones */
    #fmvsProRadar { width: 170px !important; height: 170px !important; }

    /* Breakdown: collapse label further */
    .fmvs-bd-row { grid-template-columns: 72px 1fr 28px; }
    .fmvs-bd-lbl { font-size: 8px; letter-spacing: 0.5px; }

    /* Metrics: 2×2 on very small screens */
    .fmvs-metrics-grid { grid-template-columns: repeat(2, 1fr); }

    .fmvs-u-card { flex: 0 0 130px; }
    .fmvs-uc-avatar { width: 30px; height: 30px; }
    .fmvs-status-badge span { display: none; }
    .fmvs-control-group label { display: none; }
}
</style>

<script>
(function() {
    'use strict';

    const root = document.getElementById('marketValueUltraSection');
    if (!root) return;

    let players = [];
    try { players = JSON.parse(root.dataset.players || '[]'); } catch(e) { return; }
    if (!players.length) return;

    // References
    const card3D = document.getElementById('fmvsCard3D');
    const viewport3D = document.getElementById('fmvsViewport3D');
    const cutout = document.getElementById('fmvsPlayerCutout');
    const badgePos = document.getElementById('fmvsPosBadge');
    const jerseyNum = document.getElementById('fmvsJerseyNum');
    const pName = document.getElementById('fmvsPlayerName');
    const pTeam = document.getElementById('fmvsPlayerTeam');
    const valAmount = document.getElementById('fmvsValAmount');
    const bigRank = document.getElementById('fmvsBigRank');
    const relFill = document.getElementById('fmvsRelativeFill');
    const relPct = document.getElementById('fmvsRelativePct');
    
    const mGoal = document.getElementById('mGoal');
    const mAssist = document.getElementById('mAssist');
    const mMatch = document.getElementById('mMatch');
    const mExtra = document.getElementById('mExtra');
    const mExtraLbl = document.getElementById('mExtraLbl');

    const radarCanvas = document.getElementById('fmvsProRadar');
    const radarCtx = radarCanvas.getContext('2d');
    const breakdownEl = document.getElementById('fmvsBreakdown');

    let currIdx = 0;
    let currency = 'IDR';
    let sfx = true;
    let mode3D = true;

    const RATES = { IDR: 1, EUR: 0.000060, USD: 0.000065 };
    const SYMBOLS = { IDR: 'Rp ', EUR: '€', USD: '$' };

    function formatVal(idrVal) {
        const converted = idrVal * RATES[currency];
        if (currency === 'IDR') {
            if (converted >= 1e6) {
                const jt = (converted / 1e6).toFixed(1).replace(/\.0$/, '');
                return 'Rp ' + jt + ' Jt';
            }
            return 'Rp ' + converted.toLocaleString('id-ID');
        }
        return SYMBOLS[currency] + Math.round(converted).toLocaleString('en-US');
    }

    // Audio Synthesizer (eSports Dual-Oscillator Sound)
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    function playEsportsSfx(type = 'hover') {
        if (!sfx) return;
        try {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = type === 'hover' ? 'sine' : 'triangle';
            
            const startFreq = type === 'hover' ? 440 : 880;
            const endFreq = type === 'hover' ? 880 : 220;

            osc.frequency.setValueAtTime(startFreq, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(endFreq, audioCtx.currentTime + 0.08);

            gain.gain.setValueAtTime(0.04, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.08);

            osc.connect(gain);
            gain.connect(audioCtx.destination);

            osc.start();
            osc.stop(audioCtx.currentTime + 0.08);
        } catch(e) {}
    }

    // Parallax 3D Card Hover Effect
    viewport3D.addEventListener('mousemove', e => {
        if (!mode3D) return;
        const rect = viewport3D.getBoundingClientRect();
        const x = e.clientX - rect.left - (rect.width / 2);
        const y = e.clientY - rect.top - (rect.height / 2);
        
        const rotateX = (-y / rect.height) * 30;
        const rotateY = (x / rect.width) * 30;

        card3D.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
    });

    viewport3D.addEventListener('mouseleave', () => {
        card3D.style.transform = `rotateX(0deg) rotateY(0deg)`;
    });

    // Draw Performance Radar (real normalized stats, responsive)
    function drawRadar(skills) {
        const labels = ['GOL', 'AST', 'APP', 'G/M', 'GK', 'VAL'];
        const keys   = ['gol', 'ast', 'app', 'gm', 'gk', 'val'];
        const num    = labels.length;

        // Sync canvas buffer size to its CSS-rendered size for crisp output
        const cssW = radarCanvas.clientWidth  || radarCanvas.width;
        const cssH = radarCanvas.clientHeight || radarCanvas.height;
        if (radarCanvas.width !== cssW || radarCanvas.height !== cssH) {
            radarCanvas.width  = cssW;
            radarCanvas.height = cssH;
        }

        const c = cssW / 2;        // dynamic center X & Y
        const r = Math.min(c, cssH / 2) * 0.68;  // radius = 68% of half-size

        radarCtx.clearRect(0, 0, cssW, cssH);

        // Web grid rings
        radarCtx.strokeStyle = '#1f293d';
        radarCtx.lineWidth = 1;
        for (let l = 1; l <= 3; l++) {
            radarCtx.beginPath();
            const lr = (r / 3) * l;
            for (let i = 0; i < num; i++) {
                const a = (Math.PI * 2 / num) * i - Math.PI / 2;
                const x = c + lr * Math.cos(a);
                const y = c + lr * Math.sin(a);
                i === 0 ? radarCtx.moveTo(x, y) : radarCtx.lineTo(x, y);
            }
            radarCtx.closePath();
            radarCtx.stroke();
        }

        // Spoke lines
        radarCtx.strokeStyle = '#1f293d';
        for (let i = 0; i < num; i++) {
            const a = (Math.PI * 2 / num) * i - Math.PI / 2;
            radarCtx.beginPath();
            radarCtx.moveTo(c, c);
            radarCtx.lineTo(c + r * Math.cos(a), c + r * Math.sin(a));
            radarCtx.stroke();
        }

        // Polygon fill
        radarCtx.beginPath();
        for (let i = 0; i < num; i++) {
            const val = skills[keys[i]] || 0;
            const sr  = r * (val / 100);
            const a   = (Math.PI * 2 / num) * i - Math.PI / 2;
            const x = c + sr * Math.cos(a);
            const y = c + sr * Math.sin(a);
            i === 0 ? radarCtx.moveTo(x, y) : radarCtx.lineTo(x, y);
        }
        radarCtx.closePath();
        radarCtx.fillStyle   = 'rgba(0, 255, 135, 0.25)';
        radarCtx.fill();
        radarCtx.strokeStyle = '#00ff87';
        radarCtx.lineWidth   = 2;
        radarCtx.stroke();

        // Labels
        const fontSize = Math.max(8, Math.round(cssW / 22));
        radarCtx.fillStyle  = '#64748b';
        radarCtx.font       = `${fontSize}px sans-serif`;
        radarCtx.textAlign  = 'center';
        for (let i = 0; i < num; i++) {
            const a = (Math.PI * 2 / num) * i - Math.PI / 2;
            const x = c + (r + fontSize + 4) * Math.cos(a);
            const y = c + (r + fontSize + 4) * Math.sin(a) + fontSize * 0.35;
            radarCtx.fillText(labels[i], x, y);
        }
    }

    // Render Real Event Breakdown Bars
    function drawBreakdown(p) {
        const items = [
            { lbl: 'GOALS', val: p.goals, cls: '' },
            { lbl: 'PENALTY GOALS', val: p.penalty_goals, cls: '' },
            { lbl: 'ASSISTS', val: p.assists, cls: '' },
            { lbl: 'APPEARANCES', val: p.appearances, cls: 'fmvs-bd-fill--warn' },
            { lbl: 'YELLOW CARDS', val: p.yellow_cards, cls: 'fmvs-bd-fill--warn' },
            { lbl: 'RED CARDS', val: p.red_cards, cls: 'fmvs-bd-fill--bad' },
            { lbl: 'SAVES', val: p.saves, cls: 'fmvs-bd-fill--warn' },
            { lbl: 'CLEAN SHEETS', val: p.clean_sheets, cls: '' },
        ];
        const max = Math.max(...items.map(i => i.val), 1);

        breakdownEl.innerHTML = items.map(i => `
            <div class="fmvs-bd-row">
                <span class="fmvs-bd-lbl">${i.lbl}</span>
                <div class="fmvs-bd-bar">
                    <div class="fmvs-bd-fill ${i.cls}" style="width:${Math.round((i.val / max) * 100)}%"></div>
                </div>
                <span class="fmvs-bd-val">${i.val}</span>
            </div>
        `).join('');
    }

    // Active Player Update
    function renderPlayer(i) {
        currIdx = i;
        const p = players[currIdx];

        bigRank.textContent = String(p.rank).padStart(2, '0');
        cutout.onerror = function() { this.onerror = null; this.src = '{{ asset('assets/img/player-placeholder-new.png') }}'; };
        cutout.src = p.cutout || p.photo || '{{ asset('assets/img/player-placeholder-new.png') }}';
        badgePos.textContent = p.position;
        jerseyNum.textContent = '#' + (p.jersey_number || p.rank);
        pName.textContent = p.name;
        pTeam.textContent = [p.position, p.team, p.age ? p.age + ' YRS' : null].filter(Boolean).join(' · ');
        valAmount.textContent = formatVal(p.market_value);
        relPct.textContent = p.pct + '%';
        relFill.style.width = p.pct + '%';

        mGoal.textContent = p.goals;
        mAssist.textContent = p.assists;
        mMatch.textContent = p.appearances;

        // Fourth metric adapts to position: GK shows saves, others show clean sheets / cards
        if ((p.position || '').toUpperCase() === 'GK') {
            mExtra.textContent = p.saves;
            mExtraLbl.textContent = 'SAVES';
        } else {
            mExtra.textContent = p.clean_sheets;
            mExtraLbl.textContent = 'CLEAN SHEETS';
        }

        document.querySelectorAll('.fmvs-u-card').forEach((c, idx) => {
            c.classList.toggle('is-active', idx === currIdx);
        });

        drawRadar(p.skills);
        drawBreakdown(p);
        playEsportsSfx('switch');
    }

    // Tabs Switch
    document.querySelectorAll('.fmvs-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.fmvs-tab').forEach(t => t.classList.remove('is-active'));
            document.querySelectorAll('.fmvs-tab-pane').forEach(p => p.classList.remove('is-active'));
            tab.classList.add('is-active');
            const paneId = tab.dataset.tab === 'radar' ? 'paneRadar' : tab.dataset.tab === 'breakdown' ? 'paneBreakdown' : 'paneStats';
            document.getElementById(paneId).classList.add('is-active');
            playEsportsSfx('hover');
        });
    });

    // Control Handlers
    document.getElementById('fmvsCurrencySelect').addEventListener('change', e => {
        currency = e.target.value;
        renderPlayer(currIdx);
    });

    document.getElementById('fmvsSfxBtn').addEventListener('click', () => {
        sfx = !sfx;
        document.getElementById('fmvsSfxStatus').textContent = 'SFX: ' + (sfx ? 'ON' : 'OFF');
    });

    document.getElementById('fmvsViewModeBtn').addEventListener('click', () => {
        mode3D = !mode3D;
        if (!mode3D) card3D.style.transform = 'none';
    });

    // Carousel Cards Click
    document.querySelectorAll('.fmvs-u-card').forEach((card, idx) => {
        card.addEventListener('click', () => renderPlayer(idx));
    });

    // Search Filter
    document.getElementById('fmvsSearchBox').addEventListener('input', e => {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('.fmvs-u-card').forEach(card => {
            card.style.display = (card.dataset.name || '').includes(q) ? '' : 'none';
        });
    });

    // Position Pills Filter
    document.querySelectorAll('#fmvsPosPills button').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#fmvsPosPills button').forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');
            const pos = btn.dataset.pos;
            document.querySelectorAll('.fmvs-u-card').forEach(card => {
                card.style.display = (pos === 'ALL' || card.dataset.pos === pos) ? '' : 'none';
            });
        });
    });

    // Head-to-Head Modal
    const modal = document.getElementById('fmvsH2hModal');
    const sel1 = document.getElementById('h2hSelect1');
    const sel2 = document.getElementById('h2hSelect2');
    const rows = document.getElementById('h2hComparisonRows');

    function renderH2H() {
        const p1 = players[sel1.value];
        const p2 = players[sel2.value];

        const list = [
            { name: 'MARKET VALUE', a: formatVal(p1.market_value), b: formatVal(p2.market_value), rawA: p1.market_value, rawB: p2.market_value },
            { name: 'GOALS', a: p1.goals, b: p2.goals, rawA: p1.goals, rawB: p2.goals },
            { name: 'ASSISTS', a: p1.assists, b: p2.assists, rawA: p1.assists, rawB: p2.assists },
            { name: 'MATCHES', a: p1.appearances, b: p2.appearances, rawA: p1.appearances, rawB: p2.appearances },
            { name: 'YELLOW CARDS', a: p1.yellow_cards, b: p2.yellow_cards, rawA: p2.yellow_cards, rawB: p1.yellow_cards },
            { name: 'RED CARDS', a: p1.red_cards, b: p2.red_cards, rawA: p2.red_cards, rawB: p1.red_cards }
        ];

        rows.innerHTML = list.map(m => `
            <div class="fmvs-h2h-row">
                <div class="${m.rawA > m.rawB ? 'win' : ''}">${m.a}</div>
                <div style="color:var(--u-muted); font-size:10px">${m.name}</div>
                <div class="${m.rawB > m.rawA ? 'win' : ''}">${m.b}</div>
            </div>
        `).join('');
    }

    document.getElementById('fmvsOpenCompare').addEventListener('click', () => {
        sel1.innerHTML = players.map((p, i) => `<option value="${i}">${p.name}</option>`).join('');
        sel2.innerHTML = players.map((p, i) => `<option value="${i}" ${i === 1 ? 'selected' : ''}>${p.name}</option>`).join('');
        renderH2H();
        modal.hidden = false;
    });

    document.getElementById('fmvsCloseModal').addEventListener('click', () => modal.hidden = true);
    document.getElementById('fmvsModalBackdrop').addEventListener('click', () => modal.hidden = true);
    sel1.addEventListener('change', renderH2H);
    sel2.addEventListener('change', renderH2H);

    // Initial Load
    renderPlayer(0);
})();
</script>
@endif