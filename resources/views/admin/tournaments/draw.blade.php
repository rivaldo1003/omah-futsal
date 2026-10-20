@extends('layouts.admin')

@section('title', 'Undian knockout: ' . $tournament->name)

@section('styles')
    <!-- Canvas Confetti Library -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        .tdw-stage,
        .tdw-overlay {
            --tdw-canvas: #f5f5f5;
            --tdw-surface: #ffffff;
            --tdw-soft: #f5f5f5;
            --tdw-ink: #111111;
            --tdw-body: #39393b;
            --tdw-muted: #707072;
            --tdw-rule: #e5e5e5;
            --tdw-border: #cacacb;
            --tdw-success: #007d48;
            --tdw-success-soft: #edf7f1;
            --tdw-info: #1151ff;
            --tdw-danger: #d30005;
            --tdw-radius: 8px;
            --tdw-radius-control: 6px;
        }

        .tdw-stage {
            padding: 24px;
            border: 1px solid var(--tdw-rule);
            border-radius: var(--tdw-radius);
            background: var(--tdw-surface);
            color: var(--tdw-ink);
            font-family: inherit;
        }

        .v3-hud {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            padding-bottom: 14px;
            margin-bottom: 22px;
            border-bottom: 1px solid var(--tdw-rule);
            color: var(--tdw-muted);
            font-size: 12px;
            font-weight: 500;
        }

        .v3-hud-left,
        .v3-hud-right { display: flex; align-items: center; gap: 9px; flex-wrap: wrap; }
        .v3-hud-sep { color: var(--tdw-border); }
        .v3-hud-status { color: var(--tdw-ink); }
        .v3-beacon,
        .tdw-status .dot { width: 7px; height: 7px; flex: 0 0 7px; border-radius: 50%; background: var(--tdw-success); }

        .tdw-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 20px;
        }

        .tdw-title { margin: 0; color: var(--tdw-ink); font-size: 24px; font-weight: 600; letter-spacing: -0.02em; line-height: 1.2; }
        .tdw-title span { color: inherit; }
        .tdw-sub { margin: 5px 0 0; color: var(--tdw-muted); font-size: 14px; }
        .tdw-back-link { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 7px; color: var(--tdw-muted); font-size: 13px; text-decoration: none; }
        .tdw-back-link:hover { color: var(--tdw-ink); }
        .tdw-back-link:focus-visible { outline: 2px solid var(--tdw-ink); outline-offset: 2px; }
        .tdw-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 10px;
            border: 1px solid var(--tdw-rule);
            border-radius: 999px;
            background: var(--tdw-soft);
            color: var(--tdw-body);
            font-size: 12px;
            font-weight: 600;
        }
        .tdw-status[data-state="DRAFT"] .dot,
        .tdw-status[data-state="DRAWING"] .dot { background: var(--tdw-muted); }
        .tdw-status[data-state="DRAW_COMPLETED"] .dot { background: var(--tdw-success); }
        .tdw-status[data-state="IN_PROGRESS"] .dot { background: var(--tdw-info); }

        .tdw-grid { display: grid; grid-template-columns: minmax(300px, 340px) minmax(0, 1fr); gap: 16px; align-items: start; }
        .tdw-panel { overflow: hidden; border: 1px solid var(--tdw-rule); border-radius: var(--tdw-radius); background: var(--tdw-surface); }
        .tdw-panel + .tdw-panel { margin-top: 16px; }
        .tdw-panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 13px 16px;
            border-bottom: 1px solid var(--tdw-rule);
            color: var(--tdw-ink);
            font-size: 14px;
            font-weight: 600;
        }
        .tdw-panel-head .count { color: var(--tdw-muted); font-variant-numeric: tabular-nums; }
        .tdw-panel-body { padding: 16px; }

        .tdw-btn {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 15px;
            border: 1px solid var(--tdw-border);
            border-radius: 999px;
            background: var(--tdw-soft);
            color: var(--tdw-ink);
            font: inherit;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.2;
            cursor: pointer;
            transition: background-color 150ms ease, border-color 150ms ease, color 150ms ease;
        }
        .tdw-btn:hover { border-color: var(--tdw-border); background: var(--tdw-rule); color: var(--tdw-ink); }
        .tdw-btn:disabled { opacity: .5; cursor: not-allowed; }
        .tdw-btn--primary,
        .tdw-btn--primary:hover { border-color: var(--tdw-ink); background: var(--tdw-ink); color: #fff; }
        .tdw-btn--danger,
        .tdw-btn--danger:hover { border-color: #e5b6b8; background: #fff; color: var(--tdw-danger); }
        .tdw-btn:focus-visible { outline: 2px solid var(--tdw-ink); outline-offset: 2px; }

        .tdw-team-scroll { max-height: 400px; overflow-y: auto; padding-right: 4px; }
        .tdw-team-scroll::-webkit-scrollbar { width: 6px; }
        .tdw-team-scroll::-webkit-scrollbar-track { background: var(--tdw-surface); }
        .tdw-team-scroll::-webkit-scrollbar-thumb { border-radius: 4px; background: var(--tdw-border); }
        .tdw-team {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 10px;
            min-width: 0;
            padding: 12px 0;
            border-bottom: 1px solid var(--tdw-rule);
            background: var(--tdw-surface);
            transition: background-color 150ms ease, opacity 200ms ease, transform 200ms ease;
        }
        .tdw-team:last-child { border-bottom: 0; }
        .tdw-team.is-drawing,
        .tdw-team.is-manual-selected { padding-left: 10px; border-left: 3px solid var(--tdw-ink); background: var(--tdw-soft); }
        .tdw-team.is-leaving { opacity: 0; transform: translateX(-12px); }
        .tdw-team-name { overflow-wrap: anywhere; color: var(--tdw-ink); font-size: 14px; font-weight: 600; }
        .tdw-team-meta { margin-top: 3px; color: var(--tdw-muted); font-size: 12px; }
        .tdw-team-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 7px; width: 100%; min-width: 0; }
        .tdw-team-actions .tdw-btn { min-height: 38px; padding: 7px 12px; font-size: 12px; white-space: nowrap; }

        .tdw-placed-list { display: grid; max-height: 280px; overflow-y: auto; }
        .tdw-placed-row { display: grid; grid-template-columns: minmax(0, 1fr) auto; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--tdw-rule); }
        .tdw-placed-row:last-child { border-bottom: 0; }
        .tdw-placed-summary { display: flex; align-items: center; gap: 9px; min-width: 0; }
        .tdw-placed-slot { flex: 0 0 auto; padding: 4px 7px; border: 1px solid var(--tdw-rule); border-radius: 4px; color: var(--tdw-body); font-size: 11px; font-weight: 600; font-variant-numeric: tabular-nums; }
        .tdw-placed-name { min-width: 0; overflow-wrap: anywhere; color: var(--tdw-ink); font-size: 14px; font-weight: 600; }
        .tdw-placed-row .tdw-btn { min-height: 40px; padding: 7px 12px; white-space: nowrap; }
        .tdw-placement-lock { color: var(--tdw-muted); font-size: 13px; text-align: right; }
        .tdw-placement-help { margin: 0 0 12px; color: var(--tdw-muted); font-size: 13px; line-height: 1.5; }
        .tdw-manual-hint { margin-bottom: 12px; padding: 10px 12px; border: 1px solid var(--tdw-rule); border-radius: var(--tdw-radius-control); background: var(--tdw-soft); color: var(--tdw-body); font-size: 13px; line-height: 1.5; }
        .tdw-manual-hint.is-active { border-color: var(--tdw-ink); color: var(--tdw-ink); }

        .tdw-slots { display: flex; flex-wrap: wrap; gap: 7px; }
        .tdw-slot-num { min-width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; padding: 0 8px; border: 1px solid var(--tdw-rule); border-radius: var(--tdw-radius-control); background: #fff; color: var(--tdw-ink); font-size: 13px; font-weight: 600; font-variant-numeric: tabular-nums; transition: background-color 150ms ease, color 150ms ease; }
        .tdw-slot-num.taken { border-color: transparent; background: var(--tdw-soft); color: var(--tdw-muted); text-decoration: line-through; }
        .tdw-slot-num.just-taken { border-color: #b9dfcd; background: var(--tdw-success-soft); color: var(--tdw-success); }

        .tdw-bracket-wrapper { overflow-x: auto; padding: 16px; border-radius: var(--tdw-radius); background: var(--tdw-canvas); }
        .tdw-bracket-wrapper::-webkit-scrollbar { height: 8px; }
        .tdw-bracket-wrapper::-webkit-scrollbar-thumb { border-radius: 4px; background: var(--tdw-border); }
        .tdw-bracket { position: relative; isolation: isolate; display: flex; gap: 32px; min-width: max-content; }
        .tdw-bracket-connections { position: absolute; inset: 0; z-index: 0; width: 100%; height: 100%; overflow: visible; pointer-events: none; }
        .tdw-bracket .standings-bracket-connector-path,
        .tdw-bracket .v3-bracket-connector-path { fill: none; stroke: var(--tdw-success); stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; opacity: .72; }
        .tdw-bracket .standings-bracket-connector-junction,
        .tdw-bracket .v3-bracket-connector-junction { fill: var(--tdw-surface); stroke: var(--tdw-success); stroke-width: 1.75; }
        .tdw-round { position: relative; z-index: 1; display: flex; flex-direction: column; width: 240px; }
        .tdw-round-title { margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid var(--tdw-border); color: var(--tdw-ink); font-size: 13px; font-weight: 600; }
        .tdw-match-list { display: flex; flex-direction: column; justify-content: space-around; flex-grow: 1; }
        .tdw-match { position: relative; margin: 10px 0; border: 1px solid var(--tdw-rule); border-radius: var(--tdw-radius-control); background: #fff; }
        .tdw-match.is-final { border-color: var(--tdw-ink); }
        .tdw-side { position: relative; min-height: 44px; display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: var(--tdw-ink); font-size: 13px; transition: background-color 150ms ease; }
        .tdw-side + .tdw-side { border-top: 1px solid var(--tdw-rule); }
        .tdw-side .seed { min-width: 28px; height: 22px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--tdw-rule); border-radius: 4px; background: var(--tdw-soft); color: var(--tdw-muted); font-size: 10px; font-weight: 600; font-variant-numeric: tabular-nums; }
        .tdw-side .nm { flex: 1; overflow: hidden; color: var(--tdw-muted); font-weight: 500; text-overflow: ellipsis; white-space: nowrap; }
        .tdw-side .nm.filled { color: var(--tdw-ink); font-weight: 600; }
        .tdw-side.is-manual-target { background: var(--tdw-soft); cursor: pointer; }
        .tdw-side.is-manual-target:hover { outline: 1px solid var(--tdw-ink); outline-offset: -1px; background: #e5e5e5; }
        .tdw-side.is-manual-target:focus-visible { outline: 2px solid var(--tdw-ink); outline-offset: -2px; }
        .tdw-side.is-manual-target::after { flex: 0 0 auto; color: var(--tdw-ink); content: 'Pilih slot'; font-size: 10px; font-weight: 600; }
        .tdw-pending { display: inline-flex; align-items: center; gap: 7px; max-width: 100%; padding: 4px 8px; border: 1px dashed var(--tdw-border); border-radius: 4px; color: var(--tdw-muted); font-size: 11px; line-height: 1.3; }
        .tdw-pending::before { width: 5px; height: 5px; flex: 0 0 5px; border-radius: 50%; background: var(--tdw-muted); content: ''; }
        .tdw-side.slot-highlight { z-index: 1; background: var(--tdw-success-soft); }
        .tdw-side.slot-highlight .seed { border-color: #b9dfcd; background: var(--tdw-success-soft); color: var(--tdw-success); }

        .tdw-overlay { position: fixed; z-index: 1000; inset: 0; display: flex; align-items: center; justify-content: center; flex-direction: column; overflow: hidden; background: rgba(255, 255, 255, .98); opacity: 0; visibility: hidden; transition: opacity 250ms ease, visibility 250ms ease; }
        .tdw-overlay.is-open { opacity: 1; visibility: visible; }
        .tdw-ov-hud { position: absolute; top: 24px; left: 0; right: 0; display: flex; justify-content: center; align-items: center; gap: 10px; color: var(--tdw-muted); font-size: 12px; font-weight: 600; }
        .tdw-ov-stage { position: relative; z-index: 2; text-align: center; }
        .tdw-ov-team { max-width: calc(100vw - 40px); margin: 0 auto 18px; color: var(--tdw-ink); font-size: 24px; font-weight: 600; overflow-wrap: anywhere; transform: translateY(8px); opacity: 0; transition: transform 250ms ease, opacity 250ms ease; }
        .tdw-overlay.is-open .tdw-ov-team { transform: translateY(0); opacity: 1; }
        .tdw-ov-box { width: 200px; height: 160px; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; border: 1px solid var(--tdw-rule); border-radius: var(--tdw-radius); background: var(--tdw-soft); }
        .tdw-ov-number { color: var(--tdw-ink); font-size: 64px; font-weight: 600; line-height: 1; font-variant-numeric: tabular-nums; transition: color 160ms ease; }
        .tdw-ov-number.is-rolling { animation: tdwNumberRoll 180ms infinite alternate; color: var(--tdw-body); }
        .tdw-ov-number.is-locked { color: var(--tdw-success); }
        @keyframes tdwNumberRoll { from { transform: translateY(-2px); } to { transform: translateY(2px); } }
        .tdw-ov-caption { color: var(--tdw-muted); font-size: 13px; }
        .flying-badge { position: fixed; z-index: 1001; padding: 10px 16px; border: 1px solid #111; border-radius: 6px; background: #fff; color: #111; font-size: 13px; font-weight: 600; pointer-events: none; transition: left 650ms ease-out, top 650ms ease-out, transform 650ms ease-out, opacity 650ms ease-out; }

        @media (max-width: 900px) {
            .tdw-grid { grid-template-columns: minmax(0, 1fr); }
            .tdw-bracket-wrapper { padding: 12px; }
        }

        @media (max-width: 600px) {
            .tdw-stage { padding: 16px; }
            .v3-hud { align-items: flex-start; font-size: 11px; }
            .tdw-title { font-size: 21px; }
            .tdw-panel-body { padding: 14px; }
            .tdw-team-actions { justify-content: flex-start; }
            .tdw-team-actions .tdw-btn { min-height: 44px; }
            .tdw-placed-row { grid-template-columns: minmax(0, 1fr); }
            .tdw-placed-row .tdw-btn { justify-self: start; }
            .tdw-bracket { gap: 24px; }
            .tdw-round { width: 220px; }
            .tdw-ov-team { font-size: 20px; }
            .tdw-ov-box { width: 160px; height: 130px; }
            .tdw-ov-number { font-size: 52px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .tdw-stage *, .tdw-overlay * { animation: none !important; transition: none !important; }
        }
    </style>
@endsection

@section('content')
    <div class="tdw-stage">
        {{-- Ringkasan undian --}}
        <div class="v3-hud">
            <div class="v3-hud-left">
                <span class="v3-beacon"></span>
                <span>OFS Futsal Center</span>
                <span class="v3-hud-sep">/</span>
                <span class="v3-hud-status">Undian knockout</span>
            </div>
            <div class="v3-hud-right">
                <span>{{ $teamCount }} tim</span>
                <span class="v3-hud-sep">/</span>
                @if($bracketCapacity > $bracketSize)
                    <span>Kapasitas {{ $bracketCapacity }} / bagan {{ $bracketSize }} slot</span>
                @else
                    <span>Bagan {{ $bracketSize }} slot</span>
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
                <a class="tdw-back-link" href="{{ route('admin.tournaments.show', $tournament) }}">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke detail turnamen
                </a>
                <h1 class="tdw-title">Undian Knockout</h1>
                <p class="tdw-sub">{{ $tournament->name }}</p>
            </div>
            <div class="tdw-status" data-state="{{ $status }}">
                <span class="dot"></span>
                <span id="drawStatusLabel">{{ ['DRAFT' => 'Belum dimulai', 'DRAWING' => 'Sedang mengundi', 'DRAW_COMPLETED' => 'Undian selesai', 'IN_PROGRESS' => 'Turnamen berjalan'][$status] ?? 'Status turnamen' }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="tdw-panel" style="margin-bottom:16px; border-color: var(--tdw-border);">
                <div class="tdw-panel-body" style="color:var(--tdw-success);">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="tdw-panel" role="alert" style="margin-bottom:16px; border-color: #e5b6b8;">
                <div class="tdw-panel-body" style="color: var(--tdw-danger);">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                </div>
            </div>
        @endif

        <div class="tdw-grid">
            {{-- LEFT column --}}
            <div>
                <div class="tdw-panel">
                    <div class="tdw-panel-head">Aksi undian</div>
                    <div class="tdw-panel-body">
                        <div style="display:flex; flex-wrap:wrap; gap:8px;">
                            @if(!$hasBracket && !$hasKnockoutFixtures && !$hasDrawHistory && $teamCount >= 2)
                                <form method="POST" action="{{ route('admin.tournaments.draw.build', $tournament) }}">
                                    @csrf
                                    <button class="tdw-btn tdw-btn--primary" type="submit">
                                        <i class="bi bi-diagram-3"></i> Buat bagan
                                    </button>
                                </form>
                            @endif

                            @if($hasBracket && $remainingTeams->count() > 0)
                                <form method="POST" action="{{ route('admin.tournaments.draw.all', $tournament) }}">
                                    @csrf
                                    <button class="tdw-btn" type="submit">
                                        <i class="bi bi-shuffle"></i> Undi acak tim tersisa
                                    </button>
                                </form>
                            @endif

                            @if($status === 'DRAW_COMPLETED')
                                <form method="POST" action="{{ route('admin.tournaments.draw.generate-matches', $tournament) }}">
                                    @csrf
                                    <button class="tdw-btn tdw-btn--primary" type="submit" title="Buat match untuk seluruh babak bracket">
                                        <i class="bi bi-calendar2-check"></i> Buat semua pertandingan
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
                            <p class="mb-0 mt-3" style="color: var(--tdw-muted);">
                                Daftarkan minimal 2 tim untuk membuat bracket dan memulai undian.
                            </p>
                        @elseif(!$hasBracket && ($hasKnockoutFixtures || $hasDrawHistory))
                            <p class="mb-0 mt-3" style="color: var(--tdw-muted);">
                                Data undian atau fixture lama ditemukan tanpa struktur bracket. Gunakan Reset untuk memulihkan;
                                fixture yang sudah dimulai atau memiliki hasil/event akan membuat reset ditolak demi menjaga data.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Team list --}}
                <div class="tdw-panel">
                    <div class="tdw-panel-head">
                        <span>Tim Belum Ditempatkan</span>
                        <span class="count" id="remainingCount">{{ $remainingTeams->count() }}</span>
                    </div>
                    <div class="tdw-panel-body">
                        @if($hasBracket)
                            <div class="tdw-manual-hint" id="manualPlacementHint" role="status" aria-live="polite">
                                <i class="bi bi-cursor me-1"></i>
                                Manual: pilih <strong>Atur Slot</strong> pada tim, lalu klik slot kosong di ronde pertama.
                                Slot BYE dan slot terisi tidak bisa dipilih. Atau gunakan <strong>Undi Acak</strong>.
                            </div>
                        @endif
                        <div class="tdw-team-scroll" id="teamList">
                            @forelse($remainingTeams as $team)
                                <div class="tdw-team" data-team-id="{{ $team->id }}" data-team-name="{{ $team->name }}">
                                    <div>
                                        <div class="tdw-team-name">{{ $team->name }}</div>
                                        <div class="tdw-team-meta">
                                            <i class="bi bi-person-fill me-1"></i>{{ $team->coach_name ?: 'Pelatih belum diatur' }}
                                        </div>
                                    </div>
                                    @if($hasBracket)
                                        <div class="tdw-team-actions">
                                            <button type="button" class="tdw-btn manual-place-btn" data-team-id="{{ $team->id }}"
                                                data-team-name="{{ $team->name }}" aria-pressed="false">
                                                <i class="bi bi-cursor"></i> Atur Slot
                                            </button>
                                            <button type="button" class="tdw-btn cabut-btn" data-team-id="{{ $team->id }}"
                                                data-team-name="{{ $team->name }}">
                                                <i class="bi bi-shuffle"></i> Undi Acak
                                            </button>
                                        </div>
                                    @else
                                        <button type="button" class="tdw-btn" disabled title="Generate Bracket terlebih dahulu">
                                            <i class="bi bi-lock"></i> Belum aktif
                                        </button>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-4" style="color: var(--tdw-muted);">
                                    <i class="bi bi-check-all fs-3 d-block mb-2" style="color: var(--tdw-success);"></i>
                                    Semua tim telah diundi.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                @if($drawnTeams->isNotEmpty())
                    <div class="tdw-panel">
                        <div class="tdw-panel-head">
                            <span>Tim Sudah Ditempatkan</span>
                            <span class="count">{{ $drawnTeams->count() }}</span>
                        </div>
                        <div class="tdw-panel-body">
                            @if($canCancelPlacement)
                                <p class="tdw-placement-help">Batalkan satu penempatan untuk mengembalikan tim ke daftar. Penempatan tim lain tetap.</p>
                            @elseif(!$hasBracket)
                                <p class="tdw-placement-help">Penempatan lama terkunci karena struktur bracket belum tersedia.</p>
                            @else
                                <p class="tdw-placement-help">Penempatan terkunci karena pertandingan knockout sudah dimulai atau memiliki hasil/event.</p>
                            @endif

                            <div class="tdw-placed-list">
                                @foreach($drawnTeams as $drawnTeam)
                                    <div class="tdw-placed-row">
                                        <div class="tdw-placed-summary">
                                            <span class="tdw-placed-slot">Slot #{{ $drawnTeam->slot_number }}</span>
                                            <span class="tdw-placed-name">{{ $drawnTeam->team?->name ?? ('Tim #' . $drawnTeam->team_id) }}</span>
                                        </div>

                                        @if($canCancelPlacement && $drawnTeam->team)
                                            <form method="POST"
                                                action="{{ route('admin.tournaments.draw.cancel-placement', [$tournament, $drawnTeam->team_id]) }}"
                                                onsubmit="return confirm('Kembalikan tim dari slot #{{ (int) $drawnTeam->slot_number }} ke daftar undian? Match yang belum dimainkan akan disesuaikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="tdw-btn tdw-btn--danger"
                                                    aria-label="Batalkan penempatan {{ $drawnTeam->team->name }} dari slot {{ $drawnTeam->slot_number }}"
                                                    title="Kembalikan tim ke daftar undian">
                                                    <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                                                    <span>Batalkan</span>
                                                </button>
                                            </form>
                                        @else
                                            <span class="tdw-placement-lock">
                                                <i class="bi bi-lock-fill me-1" aria-hidden="true"></i>Terkunci
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

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
                    <span>Bagan pertandingan</span>
                    <span style="color: var(--tdw-muted);">
                        {{ $tournament->name }}
                    </span>
                </div>
                <div class="tdw-panel-body">
                    @if(!$hasBracket)
                        <div class="text-center py-5" style="color: var(--tdw-muted);">
                            <i class="bi bi-diagram-3 fs-1 d-block mb-3" style="color: var(--tdw-muted);"></i>
                            @if($hasKnockoutFixtures || $hasDrawHistory)
                                Data undian lama ditemukan tanpa bracket. Gunakan Reset untuk memulihkan sebelum mengundi kembali.
                            @elseif($teamCount < 2)
                                Bracket bisa dibuat setelah minimal 2 tim terdaftar.
                            @else
                                Bagan belum dibuat. Klik <strong style="color: var(--tdw-success);">Buat bagan</strong> untuk memulai undian.
                            @endif
                        </div>
                    @else
                        <div class="tdw-bracket-wrapper">
                            <div class="tdw-bracket" id="bracketTree" data-bracket-connectors>
                                <svg class="tdw-bracket-connections" aria-hidden="true" focusable="false"></svg>
                                @foreach($tree as $round)
                                    <div class="tdw-round" data-bracket-round="{{ $round['round_number'] }}"
                                        data-bracket-advances="{{ $loop->last ? 'false' : 'true' }}">
                                        <div class="tdw-round-title">{{ $round['label'] }}</div>
                                        <div class="tdw-match-list">
                                            @foreach($round['matches'] as $rm)
                                                <div class="tdw-match {{ $rm['is_final'] ? 'is-final' : '' }}"
                                                    data-bracket-match-index="{{ $rm['match_index'] }}">
                                                    @foreach(['home' => $rm['home'], 'away' => $rm['away']] as $side => $slot)
                                                        @php
                                                            $isBye = $slot && $slot->source === 'bye';
                                                            $teamName = $slot && $slot->team ? $slot->team->name : null;
                                                            $canManualPlace = $slot
                                                                && $rm['round_number'] === 1
                                                                && $slot->slot_number
                                                                && in_array((int) $slot->slot_number, $availableSlots, true);
                                                        @endphp
                                                        <div class="tdw-side"
                                                            @if($slot && $slot->slot_number) data-slot="{{ $slot->slot_number }}" @endif
                                                            data-manual-slot="{{ $canManualPlace ? 'true' : 'false' }}"
                                                            @if($canManualPlace) role="button" tabindex="-1" aria-disabled="true" aria-label="Pilih slot {{ $slot->slot_number }} untuk tim" @endif>
                                                            <span class="seed">
                                                                {{ $slot && $slot->slot_number ? '#' . $slot->slot_number : 'Belum' }}
                                                            </span>
                                                            <span class="nm {{ $teamName ? 'filled' : '' }}">
                                                                @if($teamName)
                                                                    {{ $teamName }}
                                                                @elseif($isBye)
                                                                    <em style="color: var(--tdw-muted);">BYE</em>
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
            <span>Undian turnamen</span>
        </div>
        <div class="tdw-ov-stage">
            <div class="tdw-ov-team" id="ovTeam">Tim dipilih</div>
            <div class="tdw-ov-box">
                <div class="tdw-ov-number is-rolling" id="ovNumber">00</div>
            </div>
            <div class="tdw-ov-caption" id="ovCaption">Memilih slot...</div>
        </div>
    </div>
    @include('partials.bracket-connectors-script')
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
            const statusLabels = {
                DRAFT: 'Belum dimulai',
                DRAWING: 'Sedang mengundi',
                DRAW_COMPLETED: 'Undian selesai',
                IN_PROGRESS: 'Turnamen berjalan'
            };

            const overlay = document.getElementById('drawOverlay');
            const ovTeam = document.getElementById('ovTeam');
            const ovNumber = document.getElementById('ovNumber');
            const ovCaption = document.getElementById('ovCaption');
            const manualHint = document.getElementById('manualPlacementHint');
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            const delay = (ms) => new Promise(r => setTimeout(r, ms));
            let busy = false;
            let manualSelection = null;

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
                if (prefersReducedMotion) return;
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
                if (prefersReducedMotion) return;
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
                if (prefersReducedMotion) return;
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
                ovCaption.textContent = 'Memilih slot...';
                overlay.classList.add('is-open');
            }

            function closeOverlay() {
                overlay.classList.remove('is-open');
            }

            function triggerConfetti() {
                if (!prefersReducedMotion && typeof confetti === 'function') {
                    confetti({
                        particleCount: 100,
                        spread: 80,
                        origin: { y: 0.6 },
                        colors: ['#111111', '#007d48', '#e5e5e5']
                    });
                }
            }

            function animateFlyToSlot(teamName, slotNumber) {
                const targetSide = document.querySelector(`.tdw-side[data-slot="${slotNumber}"]`);
                if (!targetSide) return;

                if (prefersReducedMotion) {
                    const nm = targetSide.querySelector('.nm');
                    if (nm) {
                        nm.classList.add('filled');
                        nm.textContent = teamName;
                    }
                    targetSide.classList.add('slot-highlight');
                    targetSide.scrollIntoView({ behavior: 'auto', block: 'center', inline: 'center' });
                    setTimeout(() => targetSide.classList.remove('slot-highlight'), 800);
                    return;
                }

                const flyer = document.createElement('div');
                flyer.className = 'flying-badge';
                const icon = document.createElement('i');
                icon.className = 'bi bi-shield-lock-fill me-2';
                flyer.append(icon, document.createTextNode(`${teamName} → SLOT #${slotNumber}`));
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

            function syncManualSelection() {
                document.querySelectorAll('.manual-place-btn').forEach(button => {
                    const selected = manualSelection && button.dataset.teamId === manualSelection.teamId;
                    const row = button.closest('.tdw-team');
                    button.setAttribute('aria-pressed', selected ? 'true' : 'false');
                    button.classList.toggle('tdw-btn--primary', !!selected);
                    button.innerHTML = selected
                        ? '<i class="bi bi-crosshair"></i> Pilih Slot'
                        : '<i class="bi bi-cursor"></i> Atur Slot';
                    if (row) row.classList.toggle('is-manual-selected', !!selected);
                });

                document.querySelectorAll('.tdw-side[data-manual-slot="true"]').forEach(slot => {
                    const enabled = !!manualSelection;
                    slot.classList.toggle('is-manual-target', enabled);
                    slot.setAttribute('aria-disabled', enabled ? 'false' : 'true');
                    slot.tabIndex = enabled ? 0 : -1;
                });

                if (manualHint) {
                    manualHint.classList.toggle('is-active', !!manualSelection);
                    manualHint.textContent = manualSelection
                        ? `Tim ${manualSelection.teamName} dipilih. Klik slot kosong di ronde pertama; klik tombol tim lagi untuk membatalkan.`
                        : 'Manual: pilih Atur Slot pada tim, lalu klik slot kosong di ronde pertama. Slot BYE dan slot terisi tidak bisa dipilih. Atau gunakan Undi Acak.';
                }
            }

            function toggleManualSelection(button) {
                if (busy) return;

                if (manualSelection && manualSelection.teamId === button.dataset.teamId) {
                    manualSelection = null;
                } else {
                    manualSelection = {
                        teamId: button.dataset.teamId,
                        teamName: button.dataset.teamName,
                        button
                    };
                }

                syncManualSelection();
            }

            async function assignSlot(teamId, teamName, btn, preferredSlotNumber = null) {
                if (busy) return;
                busy = true;
                if (btn) btn.disabled = true;
                const isManualPlacement = preferredSlotNumber !== null;

                const row = document.querySelector(`.tdw-team[data-team-id="${teamId}"]`);
                if (row) row.classList.add('is-drawing');

                if (!prefersReducedMotion) initAudio();
                openOverlay(teamName);

                let rollTimer = null;
                if (isManualPlacement) {
                    ovNumber.textContent = String(preferredSlotNumber).padStart(2, '0');
                    ovCaption.textContent = `Memeriksa slot pilihan #${preferredSlotNumber}`;
                } else if (!prefersReducedMotion) {
                    rollTimer = setInterval(() => {
                        const n = Math.floor(Math.random() * bracketSize) + 1;
                        ovNumber.textContent = String(n).padStart(2, '0');
                        playTickSFX();
                    }, 55);
                }

                const startedAt = performance.now();
                let data = null;
                const requestBody = { team_id: teamId };
                if (isManualPlacement) requestBody.slot_number = preferredSlotNumber;

                try {
                    const res = await fetch(drawUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(requestBody)
                    });
                    data = await res.json();
                } catch (e) {
                    data = { ok: false, message: e.message };
                }

                const DRAW_DURATION = prefersReducedMotion ? 0 : (isManualPlacement ? 700 : 5000);
                const elapsed = performance.now() - startedAt;
                if (elapsed < DRAW_DURATION) await delay(DRAW_DURATION - elapsed);

                if (rollTimer) clearInterval(rollTimer);

                if (!data || !data.ok) {
                    ovCaption.textContent = isManualPlacement ? 'Gagal menempatkan tim' : 'Undian gagal';
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
                ovCaption.textContent = isManualPlacement
                    ? `Ditempatkan manual di slot #${slotNumber}`
                    : `Tim mendapat slot #${slotNumber}`;

                triggerConfetti();
                await delay(prefersReducedMotion ? 0 : 1300);

                closeOverlay();
                await delay(prefersReducedMotion ? 0 : 300);

                animateFlyToSlot(data.team.name, slotNumber);
                updateCounters(slotNumber);

                if (row) {
                    row.classList.remove('is-drawing');
                    row.classList.add('is-leaving');
                    setTimeout(() => row.remove(), 320);
                }

                if (data.status) {
                    statusLabel.textContent = statusLabels[data.status] || 'Status turnamen';
                    statusWrap.setAttribute('data-state', data.status);
                }

                if (manualSelection && manualSelection.teamId === String(teamId)) {
                    manualSelection = null;
                    syncManualSelection();
                }

                const refreshForAccurateBracket = isManualPlacement && data.remaining > 0;
                if (data.remaining === 0 || refreshForAccurateBracket) {
                    if (refreshForAccurateBracket && manualHint) {
                        manualHint.textContent = 'Penempatan tersimpan. Bracket sedang diperbarui sebelum Anda memilih slot berikutnya.';
                    }
                    setTimeout(() => location.reload(), 2500);
                } else if (btn) {
                    btn.disabled = false;
                }

                // Manual placements may advance a team over a BYE. Reload the
                // resolved bracket before allowing the next placement.
                busy = data.remaining === 0 || refreshForAccurateBracket;
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

                const bracketSlot = document.querySelector(`.tdw-side[data-slot="${slotNumber}"]`);
                if (bracketSlot) {
                    bracketSlot.dataset.manualSlot = 'false';
                    bracketSlot.classList.remove('is-manual-target');
                    bracketSlot.setAttribute('aria-disabled', 'true');
                    bracketSlot.tabIndex = -1;
                }
            }

            document.querySelectorAll('.manual-place-btn').forEach(button => {
                button.addEventListener('click', () => toggleManualSelection(button));
            });

            document.querySelectorAll('.cabut-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    assignSlot(btn.dataset.teamId, btn.dataset.teamName, btn);
                });
            });

            document.querySelectorAll('.tdw-side[data-manual-slot="true"]').forEach(slot => {
                const placeTeamInSlot = () => {
                    if (!manualSelection || busy) return;
                    assignSlot(
                        manualSelection.teamId,
                        manualSelection.teamName,
                        manualSelection.button,
                        Number(slot.dataset.slot)
                    );
                };

                slot.addEventListener('click', placeTeamInSlot);
                slot.addEventListener('keydown', event => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        placeTeamInSlot();
                    }
                });
            });
        })();
    </script>
@endsection
