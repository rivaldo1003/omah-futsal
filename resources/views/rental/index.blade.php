<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Perlengkapan - OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @include('home.partials.styles')
    <style>
        .rental-page { max-width: 1080px; padding-top: 42px; padding-bottom: 72px; }
        .rental-kicker { display:inline-flex; align-items:center; gap:8px; color:var(--v3-neon-green); font-size:12px; font-weight:750; letter-spacing:.1em; text-transform:uppercase; }
        .rental-heading { display:flex; justify-content:space-between; align-items:flex-end; gap:24px; margin:12px 0 26px; }
        .rental-heading h1 { margin:0 0 8px; font-size:clamp(28px,4vw,42px); line-height:1.08; font-weight:850; letter-spacing:-.04em; }
        .rental-heading p { max-width:680px; margin:0; color:var(--text-secondary); line-height:1.65; }
        .rental-court-link { flex:none; color:var(--text-primary); border:1px solid var(--border-color); border-radius:var(--radius-sm); padding:10px 13px; font-size:13px; text-decoration:none; }
        .rental-court-link:hover { border-color:var(--v3-neon-green); color:var(--v3-neon-green); }
        .rental-section { background:var(--surface); border:1px solid var(--border-color); border-radius:var(--v3-radius-md); overflow:hidden; margin-bottom:18px; }
        .rental-section-head { display:flex; justify-content:space-between; align-items:center; gap:15px; padding:17px 20px; border-bottom:1px solid var(--border-color); }
        .rental-section-head h2 { margin:0; font-size:16px; font-weight:750; }
        .rental-section-head p { margin:4px 0 0; color:var(--text-secondary); font-size:13px; }
        .rental-period-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:13px; padding:18px 20px; }
        .rental-mode-options { display:flex; flex-wrap:wrap; gap:10px; padding:18px 20px 0; }
        .rental-mode-choice { display:flex; align-items:center; gap:9px; min-height:44px; padding:8px 13px; border:1px solid var(--border-color); border-radius:var(--radius-sm); color:var(--text-primary); cursor:pointer; }
        .rental-mode-choice:has(input:checked) { border-color:var(--v3-neon-green); box-shadow:inset 0 0 0 1px var(--v3-neon-green); }
        .rental-mode-choice input { accent-color:var(--v3-neon-green); }
        .rental-period-summary { padding:0 20px 14px; color:var(--text-primary); font-size:14px; font-weight:700; }
        .rental-period-summary span { color:var(--text-secondary); font-weight:500; }
        .rental-field label { display:block; margin-bottom:6px; color:var(--text-secondary); font-size:12px; font-weight:650; }
        .rental-field .form-control { min-height:44px; color:var(--text-primary); background:var(--v3-card); border-color:var(--border-color); }
        .rental-field input::-webkit-calendar-picker-indicator { filter:invert(1); }
        .rental-period-actions { padding:0 20px 18px; display:flex; align-items:center; justify-content:space-between; gap:12px; }
        .rental-period-hint { color:var(--text-secondary); font-size:12px; }
        .rental-items { padding:8px 20px 18px; }
        .rental-item { display:grid; grid-template-columns:minmax(0,1fr) auto auto; align-items:center; gap:22px; padding:16px 0; border-bottom:1px solid rgba(130,153,184,.15); }
        .rental-item:last-child { border-bottom:0; }
        .rental-item-info { display:flex; align-items:center; gap:14px; min-width:0; }
        .rental-item-icon { display:grid; place-items:center; flex:none; width:42px; height:42px; border:1px solid rgba(0,255,135,.24); border-radius:11px; color:var(--v3-neon-green); background:rgba(0,255,135,.055); font-size:18px; }
        .rental-item-name { overflow-wrap:anywhere; font-weight:720; }
        .rental-item-meta { margin-top:4px; color:var(--text-secondary); font-size:12px; }
        .rental-item-price { text-align:right; white-space:nowrap; }
        .rental-item-price strong { display:block; font-size:14px; }
        .rental-item-price span { color:var(--text-secondary); font-size:11px; }
        .rental-quantity { width:90px; min-height:42px; text-align:center; color:var(--text-primary); background:var(--v3-card); border-color:var(--border-color); }
        .rental-unavailable { color:#f0bd62; font-size:12px; }
        .rental-checkout { display:grid; grid-template-columns:minmax(0,1fr) 290px; gap:18px; align-items:start; }
        .rental-form-body { padding:19px 20px; }
        .rental-customer-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:13px; }
        .rental-payment-method { display:flex; gap:10px; flex-wrap:wrap; }
        .rental-payment-choice { display:flex; align-items:center; gap:8px; min-height:44px; padding:8px 12px; background:var(--v3-card); border:1px solid var(--border-color); border-radius:var(--radius-sm); }
        .rental-policy { max-height:130px; overflow:auto; padding:12px; border:1px solid var(--border-color); border-radius:var(--radius-sm); color:var(--text-secondary); white-space:pre-line; font-size:12px; line-height:1.55; }
        .rental-total { position:sticky; top:18px; padding:20px; background:var(--v3-card); border:1px solid var(--border-color); border-radius:var(--v3-radius-md); }
        .rental-total-label { color:var(--text-secondary); font-size:12px; }
        .rental-total-value { margin:4px 0 14px; font-size:25px; font-weight:850; letter-spacing:-.035em; }
        .rental-total-note { color:var(--text-secondary); font-size:12px; line-height:1.5; }
        .rental-empty { padding:36px 20px; color:var(--text-secondary); text-align:center; }
        .rental-errors { margin-bottom:18px; }
        .rental-resume { display:none; margin-bottom:18px; background:var(--surface); border:1px solid var(--border-color); border-radius:var(--v3-radius-md); overflow:hidden; }
        .rental-resume.is-visible { display:block; }
        .rental-resume-head { padding:15px 18px; border-bottom:1px solid var(--border-color); }
        .rental-resume-head h2 { margin:0 0 3px; font-size:15px; font-weight:750; }
        .rental-resume-head p { margin:0; color:var(--text-secondary); font-size:12px; }
        .rental-resume-list { display:grid; }
        .rental-resume-item { display:flex; justify-content:space-between; align-items:center; gap:14px; padding:13px 18px; border-bottom:1px solid rgba(130,153,184,.13); }
        .rental-resume-item:last-child { border-bottom:0; }
        .rental-resume-link { min-width:0; color:var(--text-primary); text-decoration:none; }
        .rental-resume-link:hover { color:var(--v3-neon-green); }
        .rental-resume-link strong,.rental-resume-link span { display:block; }
        .rental-resume-link span { margin-top:3px; color:var(--text-secondary); font-size:12px; }
        .rental-resume-status { flex:none; color:var(--text-secondary); font-size:12px; font-weight:700; }
        .rental-resume-status.is-confirmed { color:var(--v3-neon-green); }
        .rental-resume-status.is-expired,.rental-resume-status.is-cancelled,.rental-resume-status.is-rejected { color:#ff789f; }
        .rental-resume-remove { flex:none; border:0; background:none; color:var(--text-muted); padding:5px; }
        .rental-resume-remove:hover { color:#ff789f; }
        @media(max-width:767.98px) { .rental-heading { align-items:flex-start; flex-direction:column; } .rental-period-grid { grid-template-columns:1fr; } .rental-checkout { grid-template-columns:1fr; } .rental-total { position:static; } }
        @media(max-width:575.98px) { .rental-page { padding-top:28px; } .rental-item { grid-template-columns:minmax(0,1fr) auto; gap:12px; } .rental-item-price { grid-column:1; grid-row:2; text-align:left; margin-left:56px; } .rental-quantity { grid-column:2; grid-row:1 / span 2; } .rental-period-actions { align-items:flex-start; flex-direction:column; } .rental-customer-grid { grid-template-columns:1fr; } .rental-section-head,.rental-form-body { padding-left:16px; padding-right:16px; } .rental-items { padding-left:16px; padding-right:16px; } }
        @media(prefers-reduced-motion:reduce) { *,*::before,*::after { scroll-behavior:auto !important; transition:none !important; } }
    </style>
</head>
<body>
    @include('home.partials.navbar')
    <main class="app-container rental-page">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger rental-errors">{{ $errors->first() }}</div>@endif

        <div class="rental-kicker"><i class="bi bi-bag-check" aria-hidden="true"></i> Perlengkapan OFS</div>
        <header class="rental-heading">
            <div>
                <h1>Sewa perlengkapan</h1>
                <p>Pilih perlengkapan dan waktu sewanya. Anda bisa memesan tanpa lapangan. Jika ingin menyewa keduanya, mulai dari booking lapangan; perlengkapan dapat ditambahkan sebelum pesanan dikirim sehingga data dan pembayaran cukup diisi sekali.</p>
            </div>
            <a class="rental-court-link" href="{{ route('booking.index', ['date' => $date]) }}"><i class="bi bi-calendar3 me-1" aria-hidden="true"></i>Booking lapangan dulu</a>
        </header>

        <section class="rental-resume" id="rentalResume" data-status-base="{{ url('/sewa-perlengkapan') }}" aria-labelledby="rentalResumeTitle" aria-live="polite">
            <div class="rental-resume-head"><h2 id="rentalResumeTitle">Pesanan Anda di perangkat ini</h2><p>Status diperbarui saat katalog dibuka. Simpan atau bagikan tautan pesanan untuk membukanya dari perangkat lain.</p></div>
            <div class="rental-resume-list" id="rentalResumeList"></div>
        </section>

        <form method="GET" action="{{ route('rental.index') }}" class="rental-section" id="rentalAvailabilityForm">
            <div class="rental-section-head">
                <div><h2>Jadwal perlengkapan</h2><p>Pilih pakai sekarang atau tentukan waktu pengambilan. Waktu kembali dihitung dari durasi.</p></div>
                <span class="small text-secondary"><i class="bi bi-clock me-1" aria-hidden="true"></i>{{ $openingTime }}–{{ $closingTime }} WIB</span>
            </div>
            <div class="rental-mode-options" role="radiogroup" aria-label="Cara mulai sewa">
                <label class="rental-mode-choice"><input type="radio" name="mode" value="now" @checked($mode === 'now')><span><strong>Pakai sekarang</strong><small class="d-block text-secondary">Ambil hari ini</small></span></label>
                <label class="rental-mode-choice"><input type="radio" name="mode" value="schedule" @checked($mode === 'schedule')><span><strong>Jadwalkan</strong><small class="d-block text-secondary">Pilih tanggal dan jam</small></span></label>
            </div>
            <div class="rental-period-grid">
                <div class="rental-field" id="scheduleDateField" @if($mode === 'now') hidden @endif>
                    <label for="rentalDate">Tanggal pengambilan</label>
                    <input class="form-control" type="date" id="rentalDate" name="date" value="{{ $date }}" min="{{ $todayDate }}" max="{{ $maxDate }}" @disabled($mode === 'now')>
                </div>
                <input type="hidden" id="rentalNowDate" name="date" value="{{ $todayDate }}" @disabled($mode !== 'now')>
                <div class="rental-field" id="scheduleStartField" @if($mode === 'now') hidden @endif>
                    <label for="rentalStart">Waktu pengambilan</label>
                    <input class="form-control" type="time" id="rentalStart" name="time_start" value="{{ $mode === 'schedule' ? $timeStart : '' }}" min="{{ $openingTime }}" max="{{ $closingTime }}" @disabled($mode === 'now')>
                </div>
                <input type="hidden" id="rentalNowStart" name="time_start" value="{{ $mode === 'now' ? $timeStart : '' }}" @disabled($mode !== 'now')>
                <div class="rental-field">
                    <label for="rentalDuration">Durasi sewa</label>
                    <select class="form-control" id="rentalDuration" name="duration_minutes" required>
                        @foreach($durationOptions as $minutes => $label)
                            <option value="{{ $minutes }}" @selected($durationMinutes === $minutes)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="rental-field" id="nowStartNote" @if($mode !== 'now') hidden @endif>
                    <label>Pengambilan</label><div class="form-control d-flex align-items-center" id="nowStartDisplay">Hari ini, {{ $timeStart }} WIB</div>
                </div>
            </div>
            @if($periodSelected)
                <div class="rental-period-summary" id="rentalPeriodSummary">Ambil {{ $mode === 'now' ? 'sekarang, '.$timeStart : $date.', '.$timeStart }} WIB <span>· {{ $durationOptions[$durationMinutes] ?? '—' }} · kembali sekitar {{ $timeEnd }} WIB</span></div>
            @else
                <div class="rental-period-summary" id="rentalPeriodSummary" hidden></div>
            @endif
            <div class="rental-period-actions">
                <span class="rental-period-hint">Harga berlaku per item per sesi; durasi dipakai untuk menghitung jadwal dan stok.</span>
                <button class="btn btn-outline-light" type="submit"><i class="bi bi-search me-1" aria-hidden="true"></i>Periksa stok</button>
            </div>
        </form>

        @if($periodError)<div class="alert alert-warning">{{ $periodError }}</div>@endif

        <form method="POST" action="{{ route('rental.store') }}" id="rentalOrderForm">
            @csrf
            <input type="hidden" name="mode" value="{{ $mode }}">
            <input type="hidden" name="rental_date" value="{{ $date }}">
            <input type="hidden" name="time_start" value="{{ $timeStart }}">
            <input type="hidden" name="duration_minutes" value="{{ $durationMinutes }}">
            <input type="hidden" name="rental_request_key" value="{{ old('rental_request_key', $requestKey) }}">
            <input type="hidden" name="quoted_amount" id="rentalQuotedAmount" value="{{ old('quoted_amount', '0.00') }}">
            <input type="hidden" name="booking_policy_hash" value="{{ hash('sha256', (string) $settings->booking_policy) }}">

            <section class="rental-section" aria-labelledby="rentalCatalogTitle">
                <div class="rental-section-head">
                    <div><h2 id="rentalCatalogTitle">Katalog perlengkapan</h2><p>Harga per item untuk satu sesi sewa. Varian ukuran atau warna ditampilkan terpisah bila disediakan.</p></div>
                    @if($periodSelected)<span class="small text-success" id="rentalStockCheckStatus"><i class="bi bi-check-circle me-1" aria-hidden="true"></i>Stok diperiksa</span>@endif
                </div>
                @if($items->isEmpty())
                    <div class="rental-empty"><i class="bi bi-inboxes fs-3 d-block mb-2" aria-hidden="true"></i>Belum ada perlengkapan yang tersedia untuk disewa.</div>
                @else
                    <div class="rental-items">
                        @foreach($items as $item)
                            @php
                                $name = mb_strtolower($item->name);
                                $icon = str_contains($name, 'sepatu') ? 'bi-box-seam' : (str_contains($name, 'jersey') ? 'bi-person-bounding-box' : (str_contains($name, 'rompi') ? 'bi-shield' : 'bi-bag'));
                                $available = $availableStock[$item->id] ?? null;
                                $selectedQuantity = (int) old('rental_items.'.$item->id, $selectedRentalQuantities[$item->id] ?? 0);
                            @endphp
                            <div class="rental-item">
                                <div class="rental-item-info">
                                    <span class="rental-item-icon"><i class="bi {{ $icon }}" aria-hidden="true"></i></span>
                                    <div class="min-w-0"><div class="rental-item-name">{{ $item->name }}</div>
                                        <div class="rental-item-meta">
                                            @if(!$periodSelected) Pilih jadwal sewa untuk melihat stok
                                            @elseif($available > 0) {{ $available }} tersedia pada jadwal ini
                                            @else <span class="rental-unavailable">Tidak tersedia pada jadwal ini</span>
                                            @endif
                                            @if($periodSelected && $selectedQuantity > (int) $available)<span class="d-block text-warning">Jumlah disesuaikan karena stok jadwal ini terbatas.</span>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="rental-item-price"><strong>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</strong><span>per item / sesi</span></div>
                                @if($periodSelected && $available > 0)
                                    <input class="form-control rental-quantity" type="number" name="rental_items[{{ $item->id }}]" min="0" max="{{ $available }}" value="{{ min($selectedQuantity, (int) $available) }}" inputmode="numeric" aria-label="Jumlah {{ $item->name }}" data-price="{{ (float) $item->unit_price }}">
                                @else
                                    <input class="form-control rental-quantity" type="number" value="0" disabled aria-label="Jumlah {{ $item->name }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <div class="rental-checkout">
                <section class="rental-section" aria-labelledby="rentalCheckoutTitle">
                    <div class="rental-section-head"><div><h2 id="rentalCheckoutTitle">Data pemesan dan pembayaran</h2><p>Pesanan mandiri menggunakan transfer bank atau QRIS yang tersedia.</p></div></div>
                    <div class="rental-form-body">
                        <div class="rental-customer-grid mb-3">
                            <div class="rental-field"><label for="customerName">Nama lengkap</label><input class="form-control" id="customerName" name="customer_name" maxlength="120" value="{{ old('customer_name') }}" autocomplete="name" required></div>
                            <div class="rental-field"><label for="customerPhone">Nomor WhatsApp</label><input class="form-control" id="customerPhone" name="customer_phone" maxlength="30" value="{{ old('customer_phone') }}" autocomplete="tel" inputmode="tel" required></div>
                            <div class="rental-field"><label for="customerEmail">Email <span class="fw-normal">(opsional)</span></label><input class="form-control" id="customerEmail" type="email" name="customer_email" maxlength="255" value="{{ old('customer_email') }}" autocomplete="email"></div>
                        </div>

                        @if($hasPaymentOption)
                            <div class="rental-field mb-3"><label>Metode pembayaran</label><div class="rental-payment-method">
                                @if($settings->hasBankTransfer())<label class="rental-payment-choice"><input type="radio" name="payment_method" value="bank_transfer" @checked(old('payment_method', 'bank_transfer') === 'bank_transfer') required><span>Transfer bank</span></label>@endif
                                @if($settings->hasQris())<label class="rental-payment-choice"><input type="radio" name="payment_method" value="qris" @checked(old('payment_method') === 'qris' || (!$settings->hasBankTransfer() && !old('payment_method'))) required><span>QRIS</span></label>@endif
                            </div></div>
                            <div class="rental-policy mb-3" tabindex="0">{{ $settings->booking_policy }}</div>
                            <label class="d-flex align-items-start gap-2 small text-secondary mb-0"><input type="checkbox" name="accept_booking_rules" value="1" @checked(old('accept_booking_rules')) required><span>Saya telah membaca dan menyetujui kebijakan penyewaan.</span></label>
                        @else
                            <div class="alert alert-warning mb-0">Admin belum mengatur metode pembayaran dan kebijakan. Pemesanan perlengkapan belum dapat dikirim.</div>
                        @endif
                    </div>
                </section>

                <aside class="rental-total" aria-label="Ringkasan pembayaran">
                    <div class="rental-total-label">Total pesanan</div>
                    <div class="rental-total-value" id="rentalTotalDisplay">Rp 0</div>
                    <p class="rental-total-note">Harga dikunci saat pesanan dibuat. Unggah bukti sebelum batas {{ (int) $settings->payment_hold_minutes }} menit; setelah diterima, stok tetap ditahan selama verifikasi admin.</p>
                    @if($hasPaymentOption && $periodSelected && $items->isNotEmpty())
                        <button class="btn btn-success w-100 fw-bold" id="submitRentalOrder" type="submit" disabled><i class="bi bi-lock me-1" aria-hidden="true"></i>Buat pesanan</button>
                    @else
                        <button class="btn btn-success w-100 fw-bold" type="button" disabled>{{ !$periodSelected ? 'Periksa stok terlebih dahulu' : 'Pemesanan belum tersedia' }}</button>
                    @endif
                    <div class="small text-secondary text-center mt-2">Tanpa akun pelanggan</div>
                </aside>
            </div>
        </form>
    </main>
    @include('home.partials.footer')
    <script>
        (() => {
            const storageKey = 'ofs_rental_orders_resume_v1';
            const panel = document.getElementById('rentalResume');
            const list = document.getElementById('rentalResumeList');
            const tokenPattern = /^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;
            const savedOrders = (() => {
                try {
                    const parsed = JSON.parse(window.localStorage.getItem(storageKey) || '[]');
                    return Array.isArray(parsed) ? parsed.filter((item) => item && tokenPattern.test(String(item.token || ''))).slice(0, 10) : [];
                } catch { return []; }
            })();
            const statusLabels = {
                awaiting_payment: 'Menunggu pembayaran', pending_review: 'Menunggu verifikasi', confirmed: 'Terkonfirmasi',
                rejected: 'Ditolak', cancelled: 'Dibatalkan', expired: 'Kedaluwarsa',
            };
            const formatMoney = (amount) => `Rp ${new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(Number(amount) || 0)}`;
            const formatDate = (dateValue) => {
                if (!dateValue) return '';
                const date = new Date(`${dateValue}T00:00:00`);
                return Number.isNaN(date.getTime()) ? dateValue : new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(date);
            };
            const saveOrders = () => {
                try { window.localStorage.setItem(storageKey, JSON.stringify(savedOrders.slice(0, 10))); } catch { /* The detail link remains available. */ }
            };
            const renderOrders = () => {
                list.replaceChildren();
                panel.classList.toggle('is-visible', savedOrders.length > 0);
                savedOrders.sort((a, b) => Number(b.savedAt || 0) - Number(a.savedAt || 0)).slice(0, 10).forEach((order) => {
                    const row = document.createElement('div'); row.className = 'rental-resume-item';
                    const link = document.createElement('a'); link.className = 'rental-resume-link'; link.href = `${panel.dataset.statusBase}/${encodeURIComponent(order.token)}`;
                    const title = document.createElement('strong'); title.textContent = `Pesanan #${order.code || '—'}`;
                    const details = document.createElement('span'); details.textContent = `${formatDate(order.rentalDate)}${order.timeStart && order.timeEnd ? ` · ${order.timeStart}–${order.timeEnd}` : ''} · ${formatMoney(order.amount)}`;
                    link.append(title, details);
                    const status = document.createElement('span'); status.className = `rental-resume-status${order.status ? ` is-${order.status}` : ''}`; status.textContent = order.statusLabel || statusLabels[order.status] || 'Memuat status…';
                    const remove = document.createElement('button'); remove.type = 'button'; remove.className = 'rental-resume-remove'; remove.setAttribute('aria-label', `Hapus pesanan ${order.code || ''} dari riwayat perangkat`); remove.innerHTML = '<i class="bi bi-x-lg" aria-hidden="true"></i>';
                    remove.addEventListener('click', () => { const index = savedOrders.findIndex((entry) => entry.token === order.token); if (index >= 0) savedOrders.splice(index, 1); saveOrders(); renderOrders(); });
                    row.append(link, status, remove); list.append(row);
                });
            };
            if (savedOrders.length) {
                renderOrders();
                const base = panel.dataset.statusBase.replace(/\/$/, '');
                Promise.all(savedOrders.map(async (order) => {
                    try {
                        const response = await fetch(`${base}/${encodeURIComponent(order.token)}/status`, { headers: { Accept: 'application/json' }, cache: 'no-store', credentials: 'same-origin' });
                        if (!response.ok) return;
                        Object.assign(order, await response.json());
                    } catch { /* Cached order information still lets the customer reopen the order page. */ }
                })).then(() => { saveOrders(); renderOrders(); });
            }

            const form = document.getElementById('rentalOrderForm');
            if (!form) return;
            const availabilityForm = document.getElementById('rentalAvailabilityForm');
            const modeInputs = [...availabilityForm.querySelectorAll('input[name="mode"]')];
            const scheduleDate = document.getElementById('rentalDate');
            const nowDate = document.getElementById('rentalNowDate');
            const scheduleStart = document.getElementById('rentalStart');
            const nowStart = document.getElementById('rentalNowStart');
            const durationSelect = document.getElementById('rentalDuration');
            const summary = document.getElementById('rentalPeriodSummary');
            const modeValue = () => modeInputs.find((input) => input.checked)?.value || 'now';
            const formatEndTime = (start, minutes) => {
                if (!start || !/^\d{2}:\d{2}$/.test(start)) return '';
                const [hours, mins] = start.split(':').map(Number);
                const total = hours * 60 + mins + Number(minutes || 0);
                if (total >= 24 * 60) return '';
                return `${String(Math.floor(total / 60)).padStart(2, '0')}:${String(total % 60).padStart(2, '0')}`;
            };
            const updateScheduleControls = () => {
                const isNow = modeValue() === 'now';
                document.getElementById('scheduleDateField').hidden = isNow;
                document.getElementById('scheduleStartField').hidden = isNow;
                document.getElementById('nowStartNote').hidden = !isNow;
                scheduleDate.disabled = isNow;
                scheduleStart.disabled = isNow;
                scheduleDate.required = !isNow;
                scheduleStart.required = !isNow;
                nowDate.disabled = !isNow;
                nowStart.disabled = !isNow;
                const start = isNow ? nowStart.value : scheduleStart.value;
                const date = isNow ? nowDate.value : scheduleDate.value;
                const end = formatEndTime(start, durationSelect.value);
                if (start && end && date) {
                    const dateLabel = isNow
                        ? `hari ini, ${start}`
                        : `${new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(`${date}T00:00:00`))}, ${start}`;
                    summary.textContent = `Ambil ${dateLabel} WIB · ${durationSelect.selectedOptions[0].text} · kembali sekitar ${end} WIB`;
                    summary.hidden = false;
                } else {
                    summary.hidden = true;
                }
            };
            modeInputs.forEach((input) => input.addEventListener('change', updateScheduleControls));
            [scheduleDate, scheduleStart, durationSelect].forEach((input) => input.addEventListener('input', updateScheduleControls));
            updateScheduleControls();

            const quantities = [...form.querySelectorAll('[data-price]')];
            const total = document.getElementById('rentalTotalDisplay');
            const quotedAmount = document.getElementById('rentalQuotedAmount');
            const submit = document.getElementById('submitRentalOrder');
            let scheduleDirty = false;
            const markScheduleDirty = () => {
                scheduleDirty = true;
                const status = document.getElementById('rentalStockCheckStatus');
                if (status) {
                    status.className = 'small text-warning';
                    status.innerHTML = '<i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i>Jadwal berubah · periksa stok ulang';
                }
                if (submit) submit.disabled = true;
            };
            modeInputs.forEach((input) => input.addEventListener('change', markScheduleDirty));
            [scheduleDate, scheduleStart, durationSelect].forEach((input) => input.addEventListener('change', markScheduleDirty));
            availabilityForm.addEventListener('submit', () => {
                availabilityForm.querySelectorAll('[data-preserved-rental]').forEach((input) => input.remove());
                quantities.forEach((quantity) => {
                    if (quantity.disabled) return;
                    const preserved = document.createElement('input');
                    preserved.type = 'hidden';
                    preserved.name = quantity.name;
                    preserved.value = quantity.value || '0';
                    preserved.dataset.preservedRental = 'true';
                    availabilityForm.append(preserved);
                });
            });
            const formatRupiah = (value) => new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value);
            const updateTotal = () => {
                const amount = quantities.reduce((sum, field) => {
                    const max = Number(field.max || 0);
                    const quantity = Math.min(max, Math.max(0, Number.parseInt(field.value || '0', 10) || 0));
                    field.value = String(quantity);
                    return sum + quantity * Number(field.dataset.price || 0);
                }, 0);
                total.textContent = `Rp ${formatRupiah(amount)}`;
                quotedAmount.value = amount.toFixed(2);
                if (submit) submit.disabled = amount <= 0 || scheduleDirty;
            };
            quantities.forEach((field) => field.addEventListener('input', updateTotal));
            updateTotal();
        })();
    </script>
</body>
</html>
