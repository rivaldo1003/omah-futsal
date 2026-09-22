# ULTRA ANALYTICS ENGINE v3.0 — DESIGN SYSTEM SPECIFICATION

> **Target Version**: Ultra Analytics Engine v3.0  
> **Aesthetic Profile**: Cybernetic Tactical Dashboard, High-Contrast Telemetry, eSports Broadcast Grade  
> **Audience**: Agentic AI Builders, UI/UX Engineers, Frontend Developers  

---

## 1. CORE PHILOSOPHY & ARCHITECTURE

The **Ultra Analytics Engine v3.0** design language is engineered to present complex multi-dimensional statistical data with absolute visual clarity, dynamic interactivity, and an immersive eSports broadcast feel. 

### Key Design Pillars:
1. **Telemetry First**: Information is treated as live signal data. Elements include status beacons, real-time counters, metrics grids, and live telemetry feeds.
2. **Neon-Accented High Contrast**: Deep void backgrounds paired with energetic neon accents create an immediate visual hierarchy and draw focus to active data points.
3. **Motion & Feedback Depth**: Every user interaction triggers multi-sensory feedback through smooth 3D tilt transformations, canvas physics, tab transitions, and Web Audio synthesis.
4. **Context-Driven Visualizations**: Standard charts are replaced with thematic UI constructs—Hexagonal Radar Nets, Radial Tactical Heatmaps, and Interactive Head-to-Head Delta Bars.

---

## 2. COLOR PALETTE & CSS CUSTOM PROPERTIES

Agentic AIs and developers **MUST** use these standardized CSS Custom Properties for all styling. Hardcoded color hex codes outside this list are strictly prohibited.

```css
:root {
    /* ==========================================================================
       BACKGROUNDS & SURFACES
       ========================================================================== */
    --v3-bg: #020408;              /* Main void background */
    --v3-surface: #070c14;         /* Primary container & modal surface */
    --v3-card: #0d1524;            /* Inner component & card background */
    --v3-border: #18263e;          /* Structural borders & grid lines */
    --v3-border-glow: rgba(0, 255, 135, 0.4); /* Highlight border state */

    /* ==========================================================================
       NEON ACCENTS & DATA HIGHLIGHTS
       ========================================================================== */
    --v3-neon-green: #00ff87;      /* Primary actions, Rank #1, positive metrics */
    --v3-neon-blue: #00e5ff;       /* Secondary accents, comparison baselines */
    --v3-neon-pink: #ff0055;       /* Alerts, physical attributes, critical deltas */
    --v3-neon-yellow: #ffb700;     /* Secondary highlights, warnings */

    /* ==========================================================================
       TYPOGRAPHY & HIERARCHY
       ========================================================================== */
    --v3-text-main: #ffffff;       /* Primary headings, primary numbers */
    --v3-text-sub: #8da1b9;        /* Secondary body text, sub-labels */
    --v3-text-muted: #4e6178;      /* Structural labels, grid headers */

    /* ==========================================================================
       FONTS & SPACING
       ========================================================================== */
    --v3-font-sans: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    --v3-font-mono: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;
    --v3-radius-lg: 20px;
    --v3-radius-md: 12px;
    --v3-radius-sm: 8px;
}
```

---

## 3. TYPOGRAPHY & TEXT STYLES

### A. Title & Hero Text (`.v3-title`)
* **Font Weight**: `900` (Black/Heavy)
* **Font Style**: `italic`
* **Text Transform**: `uppercase`
* **Letter Spacing**: `-0.03em`
* **Text Glow Effect**:
  ```css
  text-shadow: 0 0 20px rgba(0, 255, 135, 0.35);
  ```

### B. Telemetry Labels & Sub-headers (`.v3-hud-tag`, `.v3-sc-lbl`)
* **Font Size**: `8px` to `11px`
* **Font Weight**: `800` (Extra Bold)
* **Text Transform**: `uppercase`
* **Letter Spacing**: `1.5px`
* **Color**: `var(--v3-text-muted)`

### C. Numeric Values & Metrics (`.v3-sc-val`, `.v3-vb-val`)
* **Font Weight**: `900`
* **Font Style**: `italic` or `monospace`
* **Color**: `var(--v3-neon-green)` or `var(--v3-text-main)`

---

## 4. COMPONENT BLUEPRINTS FOR AGENTIC AI

When instructed to generate new components or views under this system, Agentic AI must follow these exact structural templates.

```
+-------------------------------------------------------------------+
| 1. TOP HUD TELEMETRY BAR                                          |
| [Beacon] SYSTEM ONLINE | Currency: IDR v | SFX: ON | [Analyze H2H]  |
+-------------------------------------------------------------------+
| 2. MODULE HEADER                                                  |
| TITLE: ULTIMATE FUTSAL MATRIX      | Total Athletes: 12           |
| Subtitle: Dynamic Telemetry        | Total Value: Rp 45.0 B       |
+-------------------------------------------------------------------+
| 3. MAIN ARENA GRID (2 Columns on Desktop)                         |
| +-------------------------------+-------------------------------+ |
| | Left: 3D Holographic Viewport | Right: Analytics Matrix       | |
| | - Card with Parallax Tilt     | - Player Rank & Value Box     | |
| | - Pos & Jersey Badges         | - Navigation Tabs             | |
| | - Pedestal Light Glow         | - Skill Radar / Heatmap / Grid| |
| |                               | - League Dominance Index Bar  | |
| +-------------------------------+-------------------------------+ |
+-------------------------------------------------------------------+
| 4. CAROUSEL SELECTION RAIL                                        |
| [ Search input... ]  [ ALL ] [ FWD ] [ ALA ] [ FIXO ] [ GK ]      |
| [<] [Card #01] [Card #02] [Card #03] [Card #04] ...           [>] |
+-------------------------------------------------------------------+
```

### 1. Top Telemetry HUD (`.v3-hud`)
A slim horizontal status indicator bar anchored at the top of every analytics view.

```html
<div class="v3-hud">
    <div class="v3-hud-left">
        <span class="v3-beacon"></span>
        <span class="v3-hud-tag">NEURAL TELEMETRY ENGINE v3.0</span>
        <span class="v3-hud-sep">/</span>
        <span class="v3-hud-status">STATUS: ONLINE</span>
    </div>
    <div class="v3-hud-right">
        <!-- Optional controls: Currency, SFX Toggle, Actions -->
    </div>
</div>
```

```css
.v3-hud {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--v3-border);
    margin-bottom: 24px;
    font-size: 11px;
    font-weight: 800;
}

.v3-beacon {
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
```

### 2. Holographic 3D Player Viewport (`.v3-viewport`)
The central showcase focal point featuring full 3D parallax displacement.

```html
<div class="v3-viewport" id="v3Viewport">
    <div class="v3-energy-ring"></div>
    <div class="v3-card-3d" id="v3Card3d">
        <div class="v3-card-glare"></div>
        <div class="v3-card-badge">FWD</div>
        <div class="v3-card-num">#10</div>
        <img src="player-cutout.png" alt="Player Cutout" draggable="false">
        <div class="v3-card-overlay">
            <span>PLAYER NAME</span>
            <span>TEAM NAME</span>
        </div>
    </div>
    <div class="v3-pedestal"></div>
</div>
```

```css
.v3-viewport {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    perspective: 1200px;
}

.v3-card-3d {
    width: 290px;
    height: 400px;
    background: linear-gradient(135deg, rgba(13, 21, 36, 0.95), rgba(7, 12, 20, 0.98));
    border: 1px solid var(--v3-border-glow);
    border-radius: var(--v3-radius-lg);
    position: relative;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.85);
    transform-style: preserve-3d;
    transition: transform 120ms ease-out;
    overflow: hidden;
}
```

### 3. Analytical Tabs & Panes
Controls switching between visual chart canvases and numerical stat grids.

```html
<div class="v3-tabs">
    <button type="button" class="v3-tab is-active" data-pane="radar">SKILL RADAR</button>
    <button type="button" class="v3-tab" data-pane="pitch">PITCH CONTROL</button>
    <button type="button" class="v3-tab" data-pane="stats">STATISTICS</button>
</div>
```

```css
.v3-tabs {
    display: flex;
    gap: 10px;
    border-bottom: 1px solid var(--v3-border);
    margin: 20px 0;
}

.v3-tab {
    background: none;
    border: none;
    color: var(--v3-text-muted);
    font-size: 11px;
    font-weight: 800;
    padding: 10px 16px;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 200ms ease;
}

.v3-tab.is-active {
    color: var(--v3-neon-green);
    border-color: var(--v3-neon-green);
}
```

---

## 5. INTERACTION & AUDIO-VISUAL SPECIFICATIONS

### A. Web Audio API Synthesizer Script
Do not rely on external audio asset files (`.mp3` / `.wav`). All user interface feedback **MUST** be generated using the Web Audio API.

```javascript
const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

function playV3Sound(freq = 600, duration = 0.08, type = 'sine') {
    try {
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = type;
        osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
        gain.gain.setValueAtTime(0.05, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + duration);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + duration);
    } catch (e) {
        // AudioContext playback muted or blocked by browser policy
    }
}

// Preset SFX Triggers
const SFX = {
    hover: () => playV3Sound(450, 0.04, 'sine'),
    select: () => playV3Sound(680, 0.08, 'sine'),
    tabSwitch: () => playV3Sound(520, 0.05, 'triangle'),
    modalOpen: () => playV3Sound(800, 0.12, 'sawtooth')
};
```

### B. 3D Card Tilt Math Blueprint
Implement this exact calculation logic to drive 3D holographic parallax effects:

```javascript
const viewport = document.getElementById('v3Viewport');
const card = document.getElementById('v3Card3d');

viewport.addEventListener('mousemove', (e) => {
    const rect = viewport.getBoundingClientRect();
    const x = e.clientX - rect.left - (rect.width / 2);
    const y = e.clientY - rect.top - (rect.height / 2);
    
    const rotateX = (-y / rect.height) * 28; // Max pitch rotation
    const rotateY = (x / rect.width) * 28;  // Max yaw rotation
    
    card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
});

viewport.addEventListener('mouseleave', () => {
    card.style.transform = `rotateX(0deg) rotateY(0deg)`;
});
```

---

## 6. RESPONSIVE BREAKPOINTS & LAYOUT COLLAPSE

All pages designed under this specification must gracefully degrade using the following layout rules:

| Breakpoint | Main Arena Layout | Title Size | Rail Cards Display |
| :--- | :--- | :--- | :--- |
| **> 1024px** (Desktop) | 2 Columns (`380px 1fr`) | `2.5rem` | Full horizontal strip |
| **768px - 1024px** (Tablet) | 1 Column Stacked | `2.0rem` | Touch scroll rail |
| **< 768px** (Mobile) | 1 Column Stacked | `1.6rem` | Grid / Multi-row wrap |

```css
@media (max-width: 900px) {
    .fmvs-v5-arena {
        grid-template-columns: 1fr;
        padding: 18px;
    }

    .fmvs-v5-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .fmvs-v5-title {
        font-size: 1.8rem;
    }

    .fmvs-v5-viewport {
        margin-bottom: 20px;
    }
}
```

---

## 7. AGENTIC AI PROMPT INSTRUCTION SHEET

When instructing an Agentic AI to produce code or UI modules using this design system, copy and prepend the block below to your prompt:

```text
[SYSTEM DIRECTIVE: APPLY ULTRA ANALYTICS ENGINE v3.0 DESIGN SYSTEM]
Build the requested UI module following the strict directives in DESIGN.md:
1. Color Palette: Use ONLY `--v3-*` custom CSS properties (`--v3-bg`, `--v3-surface`, `--v3-neon-green`, `--v3-neon-blue`, etc.).
2. Header Style: Heavy bold italic titles (`font-weight: 900`, `font-style: italic`, `text-transform: uppercase`) with neon visual glow effects.
3. Telemetry HUD: Include top-level telemetry bar with live pulse beacon indicator.
4. Canvas Graphics: Use HTML5 Canvas for Radar Charts and Pitch Heatmaps with dynamic radial gradients.
5. Sound & Physics: Wire up Web Audio API synthesizers for interactive hover/click feedback and include spring-mass cursor particle mechanics.
6. Responsive Collapse: Ensure seamless stacking transition into a single column on screens under 900px width.
```