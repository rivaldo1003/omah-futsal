<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Value — OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @include('home.partials.styles')

    <style>
        :root {
            --mv-bg: #05070a;
            --mv-surface: #0a0f18;
            --mv-card: #111827;
            --mv-border: #1f293d;
            --mv-accent: #00ff87;
            --mv-cyan: #00e5ff;
            --mv-pink: #ff0055;
            --mv-muted: #64748b;
        }

        body {
            background: var(--mv-bg);
            color: #fff;
            font-family: system-ui, -apple-system, sans-serif;
        }

        .mv-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 20px 60px;
        }

        .mv-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0 0 20px;
            font-size: 13px;
            color: var(--mv-muted);
        }

        .mv-breadcrumb a { color: var(--mv-muted); text-decoration: none; }
        .mv-breadcrumb a:hover { color: var(--mv-accent); }
        .mv-breadcrumb .active { color: #fff; font-weight: 600; }

        .mv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .mv-title {
            font-size: 2.2rem;
            font-weight: 900;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: -0.03em;
            margin: 0;
            line-height: 1;
            text-shadow: 0 0 20px rgba(0, 255, 135, 0.35);
        }

        .mv-title span { color: var(--mv-accent); }

        .mv-desc { color: var(--mv-muted); margin: 8px 0 0; font-size: 0.9rem; }

        .mv-stats { display: flex; gap: 12px; flex-wrap: wrap; }

        .mv-stat-card {
            background: var(--mv-surface);
            border: 1px solid var(--mv-border);
            padding: 10px 16px;
            border-radius: 10px;
            text-align: right;
            min-width: 140px;
        }

        .mv-sc-label { display: block; font-size: 9px; font-weight: 800; color: var(--mv-muted); letter-spacing: 1.5px; }
        .mv-sc-val { font-size: 1.2rem; font-weight: 900; font-style: italic; }
        .mv-stat-card--highlight .mv-sc-val { color: var(--mv-accent); }

        /* Filter toolbar */
        .mv-filter-card {
            background: var(--mv-surface);
            border: 1px solid var(--mv-border);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .mv-search-wrap {
            flex: 1;
            min-width: 240px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--mv-card);
            border: 1px solid var(--mv-border);
            border-radius: 8px;
            padding: 8px 12px;
        }

        .mv-search-wrap i { color: var(--mv-muted); }

        .mv-search-wrap input {
            background: transparent;
            border: none;
            outline: none;
            color: #fff;
            width: 100%;
            font-size: 0.9rem;
        }

        .mv-search-wrap input::placeholder { color: var(--mv-muted); }

        .mv-filter-card select {
            background: var(--mv-card);
            color: #fff;
            border: 1px solid var(--mv-border);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
        }

        .mv-filter-card select:focus { outline: none; border-color: var(--mv-accent); }

        .mv-btn-filter {
            background: var(--mv-accent);
            color: #000;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 0.8rem;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .mv-btn-filter:hover { background: #00db74; }

        /* Player rows */
        .mv-list { display: flex; flex-direction: column; gap: 10px; }

        .mv-row {
            display: grid;
            grid-template-columns: 56px 56px 1fr 130px 90px 90px 150px;
            align-items: center;
            gap: 14px;
            background: var(--mv-surface);
            border: 1px solid var(--mv-border);
            border-radius: 12px;
            padding: 12px 18px;
            transition: all 200ms ease;
        }

        .mv-row:hover { border-color: rgba(0, 255, 135, 0.4); background: var(--mv-card); }

        .mv-rank {
            font-size: 1.1rem;
            font-weight: 900;
            font-style: italic;
            color: var(--mv-muted);
        }

        .mv-row:nth-child(1) .mv-rank { color: var(--mv-accent); }
        .mv-row:nth-child(2) .mv-rank { color: var(--mv-cyan); }
        .mv-row:nth-child(3) .mv-rank { color: var(--mv-pink); }

        .mv-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            background: var(--mv-card);
            border: 1px solid var(--mv-border);
            flex-shrink: 0;
        }

        .mv-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .mv-player-info { min-width: 0; }
        .mv-player-name { font-weight: 800; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .mv-player-meta { font-size: 0.75rem; color: var(--mv-muted); font-weight: 600; }

        .mv-pos-badge {
            display: inline-block;
            background: var(--mv-accent);
            color: #000;
            font-size: 9px;
            font-weight: 900;
            font-style: italic;
            padding: 2px 7px;
            border-radius: 4px;
            margin-right: 6px;
        }

        .mv-stat { text-align: center; }
        .mv-stat-val { display: block; font-size: 1rem; font-weight: 900; }
        .mv-stat-lbl { font-size: 8px; font-weight: 800; color: var(--mv-muted); letter-spacing: 1px; }

        .mv-value-cell { text-align: right; }
        .mv-value-amount { font-size: 1.05rem; font-weight: 900; font-style: italic; color: var(--mv-accent); }

        .mv-value-bar {
            height: 5px;
            background: var(--mv-card);
            border: 1px solid var(--mv-border);
            border-radius: 3px;
            overflow: hidden;
            margin-top: 5px;
        }

        .mv-value-fill { height: 100%; background: var(--mv-accent); border-radius: 3px; }

        .mv-empty {
            text-align: center;
            padding: 60px 20px;
            background: var(--mv-surface);
            border: 1px solid var(--mv-border);
            border-radius: 12px;
            color: var(--mv-muted);
        }

        .mv-empty i { font-size: 40px; display: block; margin-bottom: 12px; color: var(--mv-border); }

        @media (max-width: 900px) {
            .mv-row {
                grid-template-columns: 40px 48px 1fr 110px;
                grid-template-areas:
                    "rank avatar info value"
                    "rank avatar stats value";
            }
            .mv-rank { grid-area: rank; }
            .mv-avatar { grid-area: avatar; width: 48px; height: 48px; }
            .mv-player-info { grid-area: info; }
            .mv-value-cell { grid-area: value; }
            .mv-stat { display: none; }
        }
    </style>
</head>

<body>
    <div class="mv-page">
        <nav aria-label="breadcrumb">
            <ol class="mv-breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="active" aria-current="page">Market Value</li>
            </ol>
        </nav>

        <header class="mv-header">
            <div>
                <h1 class="mv-title">MARKET VALUE <span>OFS FUTSAL CENTER</span></h1>
                <p class="mv-desc">Direktori lengkap valuasi pasar seluruh pemain — cari, filter, dan bandingkan</p>
            </div>
            <div class="mv-stats">
                <div class="mv-stat-card">
                    <span class="mv-sc-label">TOTAL PLAYERS</span>
                    <span class="mv-sc-val">{{ $players->count() }}</span>
                </div>
                <div class="mv-stat-card mv-stat-card--highlight">
                    <span class="mv-sc-label">TOTAL PORTFOLIO VALUE</span>
                    <span class="mv-sc-val">{{ \App\Models\Player::formatMarketValue($totalValuation) ?? 'Rp 0' }}</span>
                </div>
            </div>
        </header>

        {{-- Search & Filter Toolbar --}}
        <form method="GET" action="{{ route('market-value.index') }}" class="mv-filter-card">
            <div class="mv-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama pemain atau tim..." autocomplete="off">
            </div>

            <select name="position">
                <option value="all" {{ $position === 'all' || !$position ? 'selected' : '' }}>Semua posisi</option>
                @foreach(['FWD', 'ALA', 'FIXO', 'GK'] as $pos)
                    <option value="{{ $pos }}" {{ $position === $pos ? 'selected' : '' }}>{{ $pos }}</option>
                @endforeach
            </select>

            <select name="sort">
                <option value="value_desc" {{ $sort === 'value_desc' ? 'selected' : '' }}>Value tertinggi</option>
                <option value="value_asc" {{ $sort === 'value_asc' ? 'selected' : '' }}>Value terendah</option>
                <option value="goals_desc" {{ $sort === 'goals_desc' ? 'selected' : '' }}>Gol terbanyak</option>
                <option value="assists_desc" {{ $sort === 'assists_desc' ? 'selected' : '' }}>Assist terbanyak</option>
                <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
            </select>

            <button type="submit" class="mv-btn-filter">
                <i class="bi bi-funnel"></i> Terapkan
            </button>

            @if($search || ($position && $position !== 'all') || $sort !== 'value_desc')
                <a href="{{ route('market-value.index') }}" class="mv-btn-filter" style="background: var(--mv-card); color: #fff; border: 1px solid var(--mv-border);">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </a>
            @endif
        </form>

        {{-- Player List --}}
        @if($players->count() > 0)
            <div class="mv-list">
                @foreach($players as $idx => $player)
                    @php
                        $photoUrl = $player->photo_url;
                        $pct = $maxValue > 0 ? round(($player->market_value / $maxValue) * 100) : 0;
                    @endphp
                    <div class="mv-row">
                        <span class="mv-rank">{{ sprintf('%02d', $idx + 1) }}</span>

                        <div class="mv-avatar">
                            <img src="{{ $photoUrl ?? asset('assets/img/player-placeholder-new.png') }}"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/img/player-placeholder-new.png') }}'"
                                 alt="{{ $player->name }}" loading="lazy">
                        </div>

                        <div class="mv-player-info">
                            <div class="mv-player-name">{{ $player->name }}</div>
                            <div class="mv-player-meta">
                                <span class="mv-pos-badge">{{ strtoupper($player->position ?? 'FWD') }}</span>
                                {{ $player->team?->name ?? 'Tanpa Tim' }}
                                @if($player->jersey_number) · #{{ $player->jersey_number }} @endif
                            </div>
                        </div>

                        <div class="mv-stat">
                            <span class="mv-stat-val">{{ (int) ($player->goals ?? 0) }}</span>
                            <span class="mv-stat-lbl">GOALS</span>
                        </div>

                        <div class="mv-stat">
                            <span class="mv-stat-val">{{ (int) ($player->assists ?? 0) }}</span>
                            <span class="mv-stat-lbl">ASSISTS</span>
                        </div>

                        <div class="mv-stat">
                            <span class="mv-stat-val">{{ (int) $player->appearances_count }}</span>
                            <span class="mv-stat-lbl">MATCHES</span>
                        </div>

                        <div class="mv-value-cell">
                            <span class="mv-value-amount">{{ $player->formatted_market_value ?? 'Rp 0' }}</span>
                            <div class="mv-value-bar">
                                <div class="mv-value-fill" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="mv-empty">
                <i class="bi bi-people"></i>
                <h5 style="color:#fff; font-weight:800;">Tidak ada pemain ditemukan</h5>
                <p class="mb-0">Coba ubah kata kunci pencarian atau filter posisi.</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>