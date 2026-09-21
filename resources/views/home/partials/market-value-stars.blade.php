@if(isset($topValuedPlayers) && $topValuedPlayers->count() > 0)
@php
    $totalValuation = $topValuedPlayers->sum('market_value');
    $maxValue       = $topValuedPlayers->max('market_value');
@endphp

<section class="mvs" id="marketValueSection" aria-label="Pemain market value tertinggi">

    {{-- Header --}}
    <div class="mvs__header">
        <div>
            <h2 class="mvs__title">Market Value Stars</h2>
            <p class="mvs__subtitle">Pemain dengan nilai pasar tertinggi · diperbarui manual oleh admin</p>
        </div>
        <div class="mvs__meta">
            <span>{{ $topValuedPlayers->count() }} pemain</span>
            @if($totalValuation > 0)
                <span class="mvs__sep" aria-hidden="true">·</span>
                <span>Total {{ \App\Models\Player::formatMarketValue($totalValuation) }}</span>
            @endif
        </div>
    </div>

    {{-- Carousel --}}
    <div class="mvs__carousel" id="mvsOuter">
        <button class="mvs__arrow mvs__arrow--prev" id="mvsArrowPrev" aria-label="Geser kiri">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <button class="mvs__arrow mvs__arrow--next" id="mvsArrowNext" aria-label="Geser kanan">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
        </button>

        <div class="mvs__viewport" id="mvsViewport">
            <div class="mvs__track" id="mvsTrack">
                @foreach($topValuedPlayers as $idx => $player)
                @php
                    $rank     = $idx + 1;
                    $pct      = $maxValue > 0 ? round(($player->market_value / $maxValue) * 100) : 0;
                    $photo    = $player->photo ?? null;
                    $photoUrl = null;
                    if ($photo) {
                        if (filter_var($photo, FILTER_VALIDATE_URL)) {
                            $photoUrl = $photo;
                        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($photo)) {
                            $photoUrl = asset('storage/' . $photo);
                        }
                    }
                @endphp
                <article class="mvs__card{{ $rank === 1 ? ' mvs__card--first' : '' }}"
                         data-rank="{{ $rank }}"
                         data-value="{{ $player->market_value }}"
                         data-pct="{{ $pct }}"
                         data-stagger="{{ $idx }}">

                    {{-- Rank --}}
                    <span class="mvs__rank mvs__rank--{{ $rank <= 3 ? $rank : 'rest' }}" aria-label="Peringkat {{ $rank }}">
                        {{ $rank }}
                    </span>

                    {{-- Avatar --}}
                    <div class="mvs__avatar">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="{{ $player->name }}"
                                 onerror="this.style.display='none';this.nextElementSibling.removeAttribute('hidden');">
                            <span class="mvs__avatar-fallback" hidden>{{ strtoupper(substr($player->name, 0, 2)) }}</span>
                        @else
                            <span class="mvs__avatar-fallback">{{ strtoupper(substr($player->name, 0, 2)) }}</span>
                        @endif
                    </div>

                    {{-- Identity --}}
                    <div class="mvs__identity">
                        <strong class="mvs__name" title="{{ $player->name }}">{{ $player->name }}</strong>
                        <span class="mvs__meta-line">
                            @if($player->jersey_number)<span>#{{ $player->jersey_number }}</span>@endif
                            @if($player->position)<span>{{ $player->position }}</span>@endif
                            @if($player->team)<span>{{ $player->team->name }}</span>@endif
                        </span>
                    </div>

                    {{-- Value --}}
                    <div class="mvs__value-block">
                        <span class="mvs__value-num"
                              data-raw="{{ $player->market_value }}"
                              data-formatted="{{ $player->formatted_market_value }}">
                            {{ $player->formatted_market_value }}
                        </span>
                        <div class="mvs__bar-track" aria-hidden="true">
                            <div class="mvs__bar" data-pct="{{ $pct }}" style="width:0%">
                                <span class="mvs__bar-shine"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <dl class="mvs__stats">
                        <div class="mvs__stat">
                            <dt>Gol</dt>
                            <dd data-count="{{ $player->goals ?? 0 }}">{{ $player->goals ?? 0 }}</dd>
                        </div>
                        <div class="mvs__stat">
                            <dt>Assist</dt>
                            <dd data-count="{{ $player->assists ?? 0 }}">{{ $player->assists ?? 0 }}</dd>
                        </div>
                        <div class="mvs__stat">
                            <dt>Main</dt>
                            <dd data-count="{{ $player->appearances_count ?? 0 }}">{{ $player->appearances_count ?? 0 }}</dd>
                        </div>
                    </dl>

                </article>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Dots --}}
    <div class="mvs__dots" id="mvsDots" role="tablist" aria-label="Pilih slide">
        @foreach($topValuedPlayers as $idx => $player)
            <button class="mvs__dot{{ $idx === 0 ? ' is-active' : '' }}"
                    role="tab"
                    aria-selected="{{ $idx === 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $idx + 1 }}"
                    data-index="{{ $idx }}"></button>
        @endforeach
    </div>

</section>

<style>
/* =============================================================
   Market Value Stars  ·  design-guidelines-agentic-ai.md
   ============================================================= */

.mvs {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: var(--space-5);
    margin-bottom: var(--space-5);
    /* Section entrance — triggered by IntersectionObserver */
    opacity: 0;
    transform: translateY(16px);
    transition: opacity 450ms ease, transform 450ms ease;
}
.mvs.is-visible {
    opacity: 1;
    transform: translateY(0);
}

/* Header */
.mvs__header {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: var(--space-2);
    margin-bottom: var(--space-5);
    padding-bottom: var(--space-4);
    border-bottom: 1px solid var(--border-color);
}
.mvs__title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 2px;
    letter-spacing: -0.01em;
}
.mvs__subtitle {
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin: 0;
}
.mvs__meta {
    font-size: 0.8rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}
.mvs__sep { opacity: 0.4; }

/* Carousel shell */
.mvs__carousel { position: relative; }
.mvs__viewport {
    overflow: hidden;
    border-radius: var(--radius-md);
    cursor: grab;
}
.mvs__viewport:active { cursor: grabbing; }
.mvs__track {
    display: flex;
    gap: 12px;
    padding: 2px 1px 8px;
    transition: transform 400ms cubic-bezier(0.25, 0.46, 0.45, 0.94);
    will-change: transform;
}

/* Arrow buttons */
.mvs__arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    border: 1px solid var(--border-color);
    background: var(--surface);
    color: var(--text-secondary);
    cursor: pointer;
    transition: color 150ms ease, border-color 150ms ease, box-shadow 150ms ease, transform 150ms ease;
}
.mvs__arrow:hover {
    color: var(--text-primary);
    border-color: var(--border-focus);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transform: translateY(-50%) scale(1.1);
}
.mvs__arrow[hidden] { display: none; }
.mvs__arrow--prev { left: -16px; }
.mvs__arrow--next { right: -16px; }

/* ─── Cards ─────────────────────────────────────────────── */
.mvs__card {
    flex: 0 0 196px;
    width: 196px;
    background: var(--surface-subtle);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: var(--space-4);
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
    position: relative;
    /* Card stagger entrance — driven by JS + IntersectionObserver */
    opacity: 0;
    transform: translateY(20px);
    /* 3D tilt base */
    transform-style: preserve-3d;
    will-change: transform;
    transition:
        border-color 200ms ease,
        box-shadow 200ms ease;
}
/* Visible state applied by JS */
.mvs__card.is-visible {
    opacity: 1;
    transform: translateY(0);
    transition:
        opacity 400ms ease,
        transform 400ms cubic-bezier(0.34, 1.56, 0.64, 1),
        border-color 200ms ease,
        box-shadow 200ms ease;
}
/* While 3D tilt is active, let JS control transform */
.mvs__card.is-tilting {
    transition:
        border-color 200ms ease,
        box-shadow 200ms ease;
}

.mvs__card:hover {
    border-color: var(--border-focus);
    box-shadow: 0 8px 24px rgba(0,0,0,0.09);
}

/* #1 card left accent */
.mvs__card--first {
    box-shadow: inset 3px 0 0 var(--warning);
}
.mvs__card--first:hover {
    box-shadow: inset 3px 0 0 var(--warning), 0 8px 24px rgba(0,0,0,0.09);
}

/* Rank badge */
.mvs__rank {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 22px;
    height: 22px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    line-height: 1;
    transition: transform 200ms cubic-bezier(0.34, 1.56, 0.64, 1);
}
.mvs__card:hover .mvs__rank {
    transform: scale(1.2);
}
.mvs__rank--1 { background: #fef3c7; color: #92400e; }
.mvs__rank--2 { background: var(--surface-subtle); color: var(--text-secondary); border: 1px solid var(--border-color); }
.mvs__rank--3 { background: #fff7ed; color: #9a3412; }
.mvs__rank--rest { background: var(--surface-subtle); color: var(--text-muted); border: 1px solid var(--border-color); }

/* Avatar */
.mvs__avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    overflow: hidden;
    border: 1px solid var(--border-color);
    background: var(--surface-subtle);
    flex-shrink: 0;
    align-self: flex-start;
    transition: transform 250ms cubic-bezier(0.34, 1.56, 0.64, 1), border-color 200ms ease;
}
.mvs__card:hover .mvs__avatar {
    transform: scale(1.08);
    border-color: var(--border-focus);
}
.mvs__avatar img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
}
.mvs__avatar-fallback {
    width: 100%; height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-secondary);
    background: var(--surface-subtle);
}

/* Identity */
.mvs__identity { overflow: hidden; }
.mvs__name {
    display: block;
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 4px;
}
.mvs__meta-line {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    align-items: center;
    font-size: 11px;
    color: var(--text-secondary);
}
.mvs__meta-line span + span::before {
    content: '·';
    margin-right: 4px;
    opacity: 0.4;
}

/* Value block */
.mvs__value-block {
    padding: var(--space-3);
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    transition: border-color 200ms ease;
}
.mvs__card:hover .mvs__value-block {
    border-color: rgba(217, 119, 6, 0.3);
}
.mvs__value-num {
    display: block;
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--warning);
    letter-spacing: -0.02em;
    margin-bottom: 8px;
    /* count-up animation: starts hidden, revealed by JS */
    transition: opacity 200ms ease;
}

/* Progress bar + shimmer */
.mvs__bar-track {
    height: 3px;
    background: var(--border-color);
    border-radius: 99px;
    overflow: hidden;
    position: relative;
}
.mvs__bar {
    height: 100%;
    background: var(--warning);
    border-radius: 99px;
    transition: width 900ms cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
}
/* Shimmer sweep that runs after bar fills */
.mvs__bar-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 60%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.55), transparent);
    animation: none;
}
.mvs__bar.did-fill .mvs__bar-shine {
    animation: mvs-bar-shine 600ms ease 50ms forwards;
}
@keyframes mvs-bar-shine {
    from { left: -60%; }
    to   { left: 110%; }
}

/* Stats */
.mvs__stats {
    display: flex;
    gap: 0;
    margin: 0;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    overflow: hidden;
}
.mvs__stat {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 6px 4px;
    text-align: center;
    transition: background 200ms ease;
}
.mvs__stat:hover { background: var(--surface-hover); }
.mvs__stat + .mvs__stat { border-left: 1px solid var(--border-color); }
.mvs__stat dt {
    font-size: 9px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 1px;
}
.mvs__stat dd {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    /* count-up start state */
    transition: transform 200ms cubic-bezier(0.34, 1.56, 0.64, 1);
}
.mvs__stat:hover dd {
    transform: scale(1.15);
}

/* Dots */
.mvs__dots {
    display: flex;
    justify-content: center;
    gap: 5px;
    margin-top: 16px;
}
.mvs__dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    border: none;
    padding: 0;
    background: var(--border-color);
    cursor: pointer;
    transition: background 200ms ease, width 250ms cubic-bezier(0.34, 1.56, 0.64, 1), border-radius 200ms ease;
}
.mvs__dot.is-active {
    width: 18px;
    border-radius: 3px;
    background: var(--warning);
}

/* Responsive */
@media (max-width: 767px) {
    .mvs { padding: var(--space-4); }
    .mvs__arrow--prev { left: -8px; }
    .mvs__arrow--next { right: -8px; }
    .mvs__card { flex: 0 0 172px; width: 172px; }
    .mvs__avatar { width: 48px; height: 48px; }
}

@media (prefers-reduced-motion: reduce) {
    .mvs,
    .mvs__card,
    .mvs__track,
    .mvs__bar,
    .mvs__dot,
    .mvs__arrow,
    .mvs__rank,
    .mvs__avatar,
    .mvs__bar-shine { transition: none !important; animation: none !important; }
    .mvs,
    .mvs__card { opacity: 1 !important; transform: none !important; }
}
</style>

<script>
(function () {
    'use strict';

    /* ── Helpers ─────────────────────────────────────── */
    function clamp(v, lo, hi) { return Math.min(hi, Math.max(lo, v)); }

    function easeOutExpo(t) {
        return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
    }

    /**
     * Animate a numeric value from 0 → target in `duration` ms.
     * Calls onTick(currentValue) each frame.
     */
    function countUp(from, to, duration, onTick, onDone) {
        const start = performance.now();
        function step(now) {
            const elapsed = now - start;
            const t = clamp(elapsed / duration, 0, 1);
            const value = Math.round(from + (to - from) * easeOutExpo(t));
            onTick(value);
            if (t < 1) requestAnimationFrame(step);
            else if (onDone) onDone();
        }
        requestAnimationFrame(step);
    }

    /** Format number to compact Rupiah string matching PHP accessor */
    function formatRp(n) {
        if (!n || n <= 0) return '-';
        if (n >= 1_000_000_000) return 'Rp ' + (n / 1_000_000_000).toFixed(
            n % 1_000_000_000 === 0 ? 0 : 1
        ).replace('.', ',') + ' M';
        if (n >= 1_000_000) return 'Rp ' + (n / 1_000_000).toFixed(
            n % 1_000_000 === 0 ? 0 : 1
        ).replace('.', ',') + ' jt';
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    /* ── Elements ─────────────────────────────────────── */
    const section  = document.getElementById('marketValueSection');
    const track    = document.getElementById('mvsTrack');
    const viewport = document.getElementById('mvsViewport');
    const prev     = document.getElementById('mvsArrowPrev');
    const next     = document.getElementById('mvsArrowNext');
    const dots     = document.querySelectorAll('#mvsDots .mvs__dot');
    const cards    = document.querySelectorAll('.mvs__card');
    if (!section || !track || !cards.length) return;

    /* ── Carousel state ───────────────────────────────── */
    let index = 0;
    let timer = null;
    const DELAY = 3500;

    function cardWidth() { return cards[0].offsetWidth + 12; }
    function visCount()  { return Math.max(1, Math.floor(viewport.clientWidth / cardWidth())); }
    function maxIndex()  { return Math.max(0, cards.length - visCount()); }

    function go(i, instant) {
        index = clamp(i, 0, maxIndex());
        track.style.transition = instant
            ? 'none'
            : 'transform 400ms cubic-bezier(0.25,0.46,0.45,0.94)';
        track.style.transform = `translateX(-${index * cardWidth()}px)`;
        dots.forEach((d, j) => {
            const active = j === index;
            d.classList.toggle('is-active', active);
            d.setAttribute('aria-selected', String(active));
        });
        prev.toggleAttribute('hidden', index === 0);
        next.toggleAttribute('hidden', index >= maxIndex());
    }

    function startTimer() {
        clearInterval(timer);
        timer = setInterval(() => go(index >= maxIndex() ? 0 : index + 1), DELAY);
    }
    function stopTimer() { clearInterval(timer); }

    /* ── Buttons / touch / drag ───────────────────────── */
    prev.addEventListener('click', () => { stopTimer(); go(index - 1); startTimer(); });
    next.addEventListener('click', () => { stopTimer(); go(index + 1); startTimer(); });
    dots.forEach((d, i) => d.addEventListener('click', () => { stopTimer(); go(i); startTimer(); }));

    section.addEventListener('mouseenter', stopTimer);
    section.addEventListener('mouseleave', startTimer);

    let tx = 0;
    viewport.addEventListener('touchstart', e => { tx = e.touches[0].clientX; stopTimer(); }, { passive: true });
    viewport.addEventListener('touchend',   e => {
        const dx = e.changedTouches[0].clientX - tx;
        if (Math.abs(dx) > 40) go(dx < 0 ? index + 1 : index - 1);
        startTimer();
    }, { passive: true });

    let dragging = false, mx = 0, mi = 0;
    viewport.addEventListener('mousedown', e => { dragging = true; mx = e.clientX; mi = index; stopTimer(); });
    document.addEventListener('mouseup', e => {
        if (!dragging) return;
        dragging = false;
        const dx = e.clientX - mx;
        go(Math.abs(dx) > 40 ? (dx < 0 ? mi + 1 : mi - 1) : mi);
        startTimer();
    });

    document.addEventListener('keydown', e => {
        if (!section.matches(':hover')) return;
        if (e.key === 'ArrowLeft')  { stopTimer(); go(index - 1); startTimer(); }
        if (e.key === 'ArrowRight') { stopTimer(); go(index + 1); startTimer(); }
    });

    let rt;
    window.addEventListener('resize', () => {
        clearTimeout(rt);
        rt = setTimeout(() => go(index, true), 150);
    });

    /* ── 3D card tilt on mousemove ────────────────────── */
    cards.forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect   = card.getBoundingClientRect();
            const cx     = rect.left + rect.width  / 2;
            const cy     = rect.top  + rect.height / 2;
            const dx     = (e.clientX - cx) / (rect.width  / 2); // -1 … 1
            const dy     = (e.clientY - cy) / (rect.height / 2); // -1 … 1
            const rotX   = clamp(-dy * 7, -7, 7);   // tilt up/down  ±7°
            const rotY   = clamp( dx * 7, -7, 7);   // tilt left/right ±7°
            card.classList.add('is-tilting');
            card.style.transform = `perspective(600px) rotateX(${rotX}deg) rotateY(${rotY}deg) translateY(-4px)`;
            card.style.boxShadow = `${-rotY * 1.5}px ${rotX * 1.5 + 6}px 20px rgba(0,0,0,0.1)`;
        });
        card.addEventListener('mouseleave', () => {
            card.classList.remove('is-tilting');
            card.style.transform = '';
            card.style.boxShadow = '';
        });
    });

    /* ── IntersectionObserver: entrance + bars + count-up ── */
    let animated = false;

    const io = new IntersectionObserver(([entry]) => {
        if (!entry.isIntersecting || animated) return;
        animated = true;

        /* 1. Section fade-in */
        section.classList.add('is-visible');

        /* 2. Staggered card entrance */
        cards.forEach((card, i) => {
            setTimeout(() => {
                card.classList.add('is-visible');
            }, i * 80);
        });

        /* 3. Value bars fill + shimmer after */
        document.querySelectorAll('.mvs__bar').forEach((bar, i) => {
            setTimeout(() => {
                const pct = bar.dataset.pct || 0;
                bar.style.width = pct + '%';
                /* Trigger shimmer after the bar fill transition (900ms) */
                setTimeout(() => bar.classList.add('did-fill'), 920);
            }, i * 80 + 200);
        });

        /* 4. Count-up: value numbers */
        document.querySelectorAll('.mvs__value-num[data-raw]').forEach((el, i) => {
            const raw       = parseInt(el.dataset.raw, 10) || 0;
            const formatted = el.dataset.formatted || '';
            setTimeout(() => {
                countUp(0, raw, 1100, val => {
                    el.textContent = formatRp(val);
                }, () => {
                    el.textContent = formatted; // snap to exact PHP-formatted string
                });
            }, i * 80 + 150);
        });

        /* 5. Count-up: stat numbers */
        document.querySelectorAll('.mvs__stat dd[data-count]').forEach((el, i) => {
            const target = parseInt(el.dataset.count, 10) || 0;
            if (target === 0) return;
            setTimeout(() => {
                countUp(0, target, 800, val => { el.textContent = val; });
            }, i * 40 + 300);
        });

        io.disconnect();
    }, { threshold: 0.15 });

    io.observe(section);

    /* ── Init ─────────────────────────────────────────── */
    go(0, true);
    startTimer();
})();
</script>
@endif
