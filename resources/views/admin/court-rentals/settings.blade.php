@extends('layouts.admin')

@section('title', 'Katalog Perlengkapan')

@section('styles')
    <style>
        .rental-catalog-page {
            --catalog-ink: #111111;
            --catalog-charcoal: #39393b;
            --catalog-muted: #707072;
            --catalog-border: #cacacb;
            --catalog-border-soft: #e5e5e5;
            --catalog-cloud: #f5f5f5;
            --catalog-green: #007d48;
            color: var(--catalog-ink);
        }

        .rental-catalog-page .card {
            border: 1px solid var(--catalog-border-soft);
            border-radius: 0;
            box-shadow: none;
        }

        .rental-catalog-page .card-header {
            padding: 16px 20px;
            background: #ffffff;
            border-bottom-color: var(--catalog-border-soft);
        }

        .rental-catalog-page .card-body {
            padding: 20px;
        }

        .rental-catalog-page h1 {
            color: var(--catalog-ink);
            font-size: clamp(1.5rem, 2vw, 2rem);
            letter-spacing: -0.025em;
        }

        .rental-catalog-page h2 {
            color: var(--catalog-ink);
            font-size: 1rem;
            font-weight: 600;
        }

        .rental-catalog-page .text-secondary,
        .catalog-list-meta {
            color: var(--catalog-muted) !important;
        }

        .rental-catalog-page .form-label {
            color: var(--catalog-ink);
            font-size: .875rem;
            font-weight: 500;
        }

        .rental-catalog-page .form-text {
            color: var(--catalog-muted);
            font-size: .8125rem;
        }

        .rental-catalog-page .form-control,
        .rental-catalog-page .form-select {
            min-height: 44px;
            border-color: var(--catalog-border);
            border-radius: 24px;
            color: var(--catalog-ink);
            background-color: #ffffff;
        }

        .rental-catalog-page .form-control:focus,
        .rental-catalog-page .form-select:focus {
            border-color: var(--catalog-ink);
            box-shadow: 0 0 0 3px var(--catalog-cloud);
        }

        .rental-catalog-page .form-check-input:checked {
            background-color: var(--catalog-ink);
            border-color: var(--catalog-ink);
        }

        .rental-catalog-page .form-check-input:focus {
            border-color: var(--catalog-ink);
            box-shadow: 0 0 0 3px var(--catalog-cloud);
        }

        .rental-catalog-page .btn {
            min-height: 44px;
            padding: 9px 20px;
            border-radius: 999px;
            font-size: .875rem;
            font-weight: 500;
            transition: background-color .15s ease, color .15s ease, border-color .15s ease;
        }

        .rental-catalog-page .btn-primary {
            border-color: var(--catalog-ink);
            background: var(--catalog-ink);
            color: #ffffff;
        }

        .rental-catalog-page .btn-primary:hover {
            border-color: var(--catalog-charcoal);
            background: var(--catalog-charcoal);
            color: #ffffff;
        }

        .rental-catalog-page .btn-outline-primary,
        .rental-catalog-page .btn-outline-secondary {
            border-color: var(--catalog-border);
            background: #ffffff;
            color: var(--catalog-ink);
        }

        .rental-catalog-page .btn-outline-primary:hover,
        .rental-catalog-page .btn-outline-secondary:hover {
            border-color: var(--catalog-ink);
            background: var(--catalog-cloud);
            color: var(--catalog-ink);
        }

        .rental-catalog-page :is(a, button, summary, input, select):focus-visible {
            outline: 2px solid var(--catalog-ink);
            outline-offset: 3px;
        }

        .catalog-intro {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 24px;
            padding: 0 0 18px;
            border-bottom: 1px solid var(--catalog-border-soft);
            color: var(--catalog-charcoal);
        }

        .catalog-intro i {
            margin-top: 2px;
            color: var(--catalog-ink);
            font-size: 1rem;
        }

        .catalog-intro strong {
            color: var(--catalog-ink);
            font-size: .9375rem;
            font-weight: 600;
        }

        .catalog-intro p {
            margin: 4px 0 0;
            max-width: 78ch;
            line-height: 1.5;
            font-size: .875rem;
        }

        .catalog-form-grid {
            display: grid;
            grid-template-columns: minmax(220px, 1.7fr) minmax(170px, .9fr) minmax(140px, .65fr);
            gap: 16px;
            align-items: end;
        }

        .catalog-form-footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: .8rem;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--catalog-border-soft);
        }

        .catalog-list-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: .75rem;
        }

        .catalog-search {
            flex: 1 1 260px;
        }

        .catalog-filter {
            flex: 0 1 220px;
        }

        .catalog-list-meta {
            color: var(--catalog-muted);
            font-size: .8125rem;
        }

        .catalog-items {
            overflow: hidden;
            border-top: 1px solid var(--catalog-border-soft);
            border-bottom: 1px solid var(--catalog-border-soft);
        }

        .catalog-item+.catalog-item {
            border-top: 1px solid var(--catalog-border-soft);
        }

        .catalog-item-main {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto auto;
            align-items: center;
            gap: 16px;
            padding: 16px 8px;
        }

        .catalog-item-name {
            display: block;
            margin-bottom: 4px;
            color: var(--catalog-ink);
            font-size: .9375rem;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .catalog-item-meta {
            color: var(--catalog-muted);
            font-size: .8125rem;
            font-variant-numeric: tabular-nums;
        }

        .catalog-item-status {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            white-space: nowrap;
            padding: 6px 10px;
            border-radius: 999px;
            background: var(--catalog-cloud);
            font-size: .75rem;
            font-weight: 600;
        }

        .catalog-item-status.is-available {
            color: var(--catalog-green);
            background: #edf7f1;
        }

        .catalog-item-status.is-empty {
            color: #765000;
            background: #fff7e5;
        }

        .catalog-item-status.is-hidden {
            color: var(--catalog-muted);
        }

        .catalog-item-edit summary {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            min-height: 40px;
            padding: 8px 14px;
            border: 1px solid var(--catalog-border);
            border-radius: 999px;
            background: #ffffff;
            color: var(--catalog-ink);
            cursor: pointer;
            font-size: .8125rem;
            font-weight: 500;
            list-style: none;
            white-space: nowrap;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .catalog-item-edit summary::-webkit-details-marker {
            display: none;
        }

        .catalog-item-edit summary:hover {
            border-color: var(--catalog-ink);
            background: var(--catalog-cloud);
        }

        .catalog-item-edit[open] {
            grid-column: 1 / -1;
            width: 100%;
        }

        .catalog-item-edit[open] summary {
            width: max-content;
            margin-left: auto;
            margin-bottom: 12px;
        }

        .catalog-editor {
            padding: 16px;
            border-top: 1px solid var(--catalog-border-soft);
            background: var(--catalog-cloud);
        }

        .catalog-advanced {
            margin-top: 0;
            color: var(--catalog-charcoal);
            font-size: .8125rem;
        }

        .catalog-advanced summary {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            cursor: pointer;
            font-weight: 600;
        }

        .catalog-advanced[open] .catalog-advanced-field {
            margin-top: .75rem;
            max-width: 220px;
        }

        .catalog-empty-state {
            padding: 40px 16px;
            color: var(--catalog-muted);
            text-align: center;
        }

        .catalog-empty-state i {
            color: var(--catalog-ink);
        }

        @media (prefers-reduced-motion: reduce) {
            .rental-catalog-page *,
            .rental-catalog-page *::before,
            .rental-catalog-page *::after {
                scroll-behavior: auto !important;
                transition: none !important;
            }
        }

        @media (max-width: 767.98px) {
            .catalog-form-grid {
                grid-template-columns: 1fr 1fr;
            }

            .catalog-form-grid .catalog-name-field {
                grid-column: 1 / -1;
            }

            .catalog-item-main {
                grid-template-columns: minmax(0, 1fr) auto;
                gap: 8px 12px;
            }

            .catalog-item-status {
                grid-column: 1;
                grid-row: 2;
            }

            .catalog-item-edit {
                grid-column: 2;
                grid-row: 1 / span 2;
            }

            .catalog-item-edit[open] {
                grid-column: 1 / -1;
                grid-row: auto;
            }
        }

        @media (max-width: 420px) {
            .catalog-form-grid {
                grid-template-columns: 1fr;
            }

            .catalog-form-grid .catalog-name-field {
                grid-column: auto;
            }

            .catalog-item-main {
                padding: 12px;
            }

            .catalog-editor {
                padding: 12px;
            }

            .catalog-list-toolbar>* {
                flex-basis: 100%;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $formatRupiah = function ($value) {
            if (!filled($value))
                return '';
            $digits = preg_replace('/\D+/', '', (string) $value);
            return $digits === '' ? '' : 'Rp ' . number_format((int) $digits, 0, ',', '.');
        };
        $availableCount = $rentalItems->filter(fn($item) => $item->is_active && (int) $item->stock_quantity > 0)->count();
        $emptyCount = $rentalItems->filter(fn($item) => $item->is_active && (int) $item->stock_quantity === 0)->count();
    @endphp

    <main class="rental-catalog-page">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h1 class="mb-1">Katalog Perlengkapan</h1>
                <p class="text-secondary mb-0">Atur barang dan stok yang dapat ditambahkan pelanggan saat booking lapangan.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.court-bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Kembali ke booking
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-circle me-1" aria-hidden="true"></i>{{ $errors->first() }}
            </div>
        @endif

        <div class="catalog-intro" role="note">
            <i class="bi bi-info-circle" aria-hidden="true"></i>
            <div>
                <strong>Kelola barang dan variannya satu per satu.</strong>
                <p>Harga berlaku per unit untuk satu sesi sewa. Buat item terpisah untuk ukuran atau warna berbeda, misalnya
                    “Sepatu ukuran 40” atau “Rompi merah”. Barang yang sedang dipesan tidak bisa dikurangi stoknya.</p>
            </div>
        </div>

        <section class="card mb-4" aria-labelledby="newRentalItemTitle">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h2 class="h5 mb-0" id="newRentalItemTitle">Tambah barang</h2>
                <span class="small text-secondary">Urutan tampil diatur otomatis</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.court-rentals.items.store') }}">
                    @csrf
                    <div class="catalog-form-grid">
                        <div class="catalog-name-field">
                            <label class="form-label" for="newRentalItemName">Nama barang atau varian</label>
                            <input class="form-control" id="newRentalItemName" name="name" required maxlength="100"
                                value="{{ old('name') }}" placeholder="Contoh: Rompi merah ukuran L">
                            <div class="form-text">Gunakan nama yang mudah dikenali pelanggan.</div>
                        </div>
                        <div>
                            <label class="form-label" for="newRentalItemPrice">Harga sewa per unit</label>
                            <input class="form-control" id="newRentalItemPrice" name="unit_price" type="text"
                                inputmode="numeric" autocomplete="off" maxlength="18" data-rupiah-input required
                                value="{{ $formatRupiah(old('unit_price')) }}" placeholder="Rp 25.000">
                        </div>
                        <div>
                            <label class="form-label" for="newRentalItemStock">Stok tersedia</label>
                            <input class="form-control" id="newRentalItemStock" name="stock_quantity" type="number" min="0"
                                max="65535" value="{{ old('stock_quantity') }}" required>
                            <div class="form-text">Dalam unit.</div>
                        </div>
                    </div>
                    <div class="catalog-form-footer">
                        <label class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                @checked(old('is_active', true))>
                            <span class="form-check-label">Tersedia sebagai tambahan booking lapangan</span>
                        </label>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Tambah barang
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section class="card" aria-labelledby="rentalItemsListTitle">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-end gap-3">
                <div>
                    <h2 class="h5 mb-1" id="rentalItemsListTitle">Daftar barang</h2>
                    <div class="catalog-list-meta">
                        {{ $rentalItems->count() }} jenis · {{ $availableCount }} ditawarkan · {{ $emptyCount }} stok habis
                    </div>
                </div>
                @if($rentalItems->isNotEmpty())
                    <div class="catalog-list-toolbar">
                        <div class="catalog-search">
                            <label class="form-label small mb-1" for="catalogSearch">Cari barang</label>
                            <input class="form-control" id="catalogSearch" type="search" placeholder="Nama atau varian…"
                                autocomplete="off">
                        </div>
                        <div class="catalog-filter">
                            <label class="form-label small mb-1" for="catalogStatus">Tampilkan</label>
                            <select class="form-select" id="catalogStatus">
                                <option value="all">Semua barang</option>
                                <option value="available">Ditawarkan</option>
                                <option value="empty">Stok habis</option>
                                <option value="hidden">Disembunyikan</option>
                            </select>
                        </div>
                    </div>
                @endif
            </div>

            <div class="card-body">
                @if($rentalItems->isEmpty())
                    <div class="catalog-empty-state">
                        <i class="bi bi-box-seam fs-3 d-block mb-2" aria-hidden="true"></i>
                        <strong class="d-block text-dark">Belum ada barang di katalog</strong>
                        <span>Tambahkan barang di atas untuk mulai menawarkan perlengkapan sewa.</span>
                    </div>
                @else
                    <div class="catalog-items" id="catalogItems">
                        @foreach($rentalItems as $item)
                            @php
                                $itemState = !$item->is_active ? 'hidden' : ((int) $item->stock_quantity < 1 ? 'empty' : 'available');
                                $stateLabel = match ($itemState) {
                                    'available' => 'Ditawarkan ke pelanggan',
                                    'empty' => 'Stok habis',
                                    default => 'Tidak ditawarkan',
                                };
                                $stateIcon = match ($itemState) {
                                    'available' => 'bi-check-circle-fill',
                                    'empty' => 'bi-exclamation-circle-fill',
                                    default => 'bi-eye-slash-fill',
                                };
                            @endphp
                            <article class="catalog-item" data-catalog-item data-state="{{ $itemState }}"
                                data-name="{{ mb_strtolower($item->name) }}">
                                <div class="catalog-item-main">
                                    <div>
                                        <span class="catalog-item-name">{{ $item->name }}</span>
                                        <div class="catalog-item-meta">
                                            Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }} per unit / sesi
                                            <span aria-hidden="true">·</span>
                                            Stok {{ number_format((int) $item->stock_quantity) }} unit
                                        </div>
                                    </div>
                                    <span class="catalog-item-status is-{{ $itemState }}">
                                        <i class="bi {{ $stateIcon }}" aria-hidden="true"></i>{{ $stateLabel }}
                                    </span>
                                    <details class="catalog-item-edit">
                                        <summary><i class="bi bi-pencil" aria-hidden="true"></i>Ubah</summary>
                                        <div class="catalog-editor">
                                            <form method="POST" action="{{ route('admin.court-rentals.items.update', $item) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="row g-3">
                                                    <div class="col-12 col-lg-5">
                                                        <label class="form-label" for="rentalName{{ $item->id }}">Nama barang atau
                                                            varian</label>
                                                        <input class="form-control" id="rentalName{{ $item->id }}" name="name"
                                                            required maxlength="100" value="{{ $item->name }}">
                                                    </div>
                                                    <div class="col-6 col-lg-3">
                                                        <label class="form-label" for="rentalPrice{{ $item->id }}">Harga sewa per
                                                            unit</label>
                                                        <input class="form-control" id="rentalPrice{{ $item->id }}"
                                                            name="unit_price" type="text" inputmode="numeric" autocomplete="off"
                                                            maxlength="18" data-rupiah-input required
                                                            value="{{ $formatRupiah($item->unit_price) }}">
                                                    </div>
                                                    <div class="col-6 col-lg-2">
                                                        <label class="form-label" for="rentalStock{{ $item->id }}">Stok unit</label>
                                                        <input class="form-control" id="rentalStock{{ $item->id }}"
                                                            name="stock_quantity" type="number" min="0" max="65535"
                                                            value="{{ $item->stock_quantity }}" required>
                                                    </div>
                                                    <div class="col-12 col-lg-2 d-flex align-items-end">
                                                        <button class="btn btn-outline-primary w-100" type="submit"><i
                                                                class="bi bi-check2 me-1" aria-hidden="true"></i>Simpan</button>
                                                    </div>
                                                </div>

                                                <div class="catalog-form-footer">
                                                    <label class="form-check mb-0">
                                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                            @checked($item->is_active)>
                                                        <span class="form-check-label">Tersedia sebagai tambahan booking
                                                            lapangan</span>
                                                    </label>
                                                    <details class="catalog-advanced">
                                                        <summary><i class="bi bi-sliders" aria-hidden="true"></i>Urutan daftar
                                                        </summary>
                                                        <div class="catalog-advanced-field">
                                                            <label class="form-label" for="rentalOrder{{ $item->id }}">Nomor
                                                                urutan</label>
                                                            <input class="form-control" id="rentalOrder{{ $item->id }}"
                                                                name="sort_order" type="number" min="0" max="65535"
                                                                value="{{ $item->sort_order }}">
                                                            <div class="form-text">Angka lebih kecil tampil lebih dahulu.</div>
                                                        </div>
                                                    </details>
                                                </div>
                                            </form>
                                        </div>
                                    </details>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="catalog-empty-state" id="catalogNoResults" hidden>
                        <strong class="d-block text-dark">Barang tidak ditemukan</strong>
                        <span>Ubah kata pencarian atau status yang dipilih.</span>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        (() => {
            const formatter = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });
            const fields = document.querySelectorAll('[data-rupiah-input]');
            const digitsOnly = (value) => value.replace(/\D/g, '');
            const format = (digits) => digits ? `Rp ${formatter.format(Number(digits))}` : '';
            const digitCountBefore = (value, position) => (value.slice(0, position).match(/\d/g) || []).length;
            const positionAfterDigit = (value, digitIndex) => {
                if (digitIndex === 0) return Math.min(3, value.length);
                let digitsSeen = 0;
                for (let index = 0; index < value.length; index += 1) {
                    if (/\d/.test(value[index]) && ++digitsSeen === digitIndex) return index + 1;
                }
                return value.length;
            };

            fields.forEach((field) => field.addEventListener('input', () => {
                const previousValue = field.value;
                const caret = field.selectionStart ?? previousValue.length;
                const digitIndex = digitCountBefore(previousValue, caret);
                const formatted = format(digitsOnly(previousValue));
                field.value = formatted;
                const nextCaret = positionAfterDigit(formatted, digitIndex);
                field.setSelectionRange(nextCaret, nextCaret);
            }));

            const search = document.getElementById('catalogSearch');
            const status = document.getElementById('catalogStatus');
            const items = [...document.querySelectorAll('[data-catalog-item]')];
            const noResults = document.getElementById('catalogNoResults');
            if (!search || !status || !items.length || !noResults) return;

            const filterItems = () => {
                const query = search.value.trim().toLocaleLowerCase('id-ID');
                const selectedState = status.value;
                let visibleCount = 0;
                items.forEach((item) => {
                    const matchesName = item.dataset.name.includes(query);
                    const matchesState = selectedState === 'all' || item.dataset.state === selectedState;
                    const visible = matchesName && matchesState;
                    item.hidden = !visible;
                    if (visible) visibleCount += 1;
                });
                noResults.hidden = visibleCount > 0;
            };

            search.addEventListener('input', filterItems);
            status.addEventListener('change', filterItems);
        })();
    </script>
@endpush
