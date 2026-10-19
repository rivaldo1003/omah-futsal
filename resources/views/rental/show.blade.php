<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>Status Sewa Perlengkapan - OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @include('home.partials.styles')
    <style>
        .rental-status-page { max-width:800px; padding-top:42px; padding-bottom:68px; }
        .rental-status-card { overflow:hidden; background:var(--surface); border:1px solid var(--border-color); border-radius:var(--v3-radius-md); }
        .rental-status-head { display:flex; justify-content:space-between; align-items:center; gap:16px; padding:22px 24px; border-bottom:1px solid var(--border-color); }
        .rental-status-head h1 { margin:0 0 5px; font-size:22px; font-weight:800; }
        .rental-status-code { color:var(--text-secondary); font:12px var(--v3-font-mono); }
        .rental-status-badge { white-space:nowrap; border-radius:999px; padding:7px 11px; background:rgba(255,183,0,.12); color:#ffd15a; font-size:12px; font-weight:750; }
        .rental-status-badge.success { background:rgba(0,255,135,.1); color:var(--v3-neon-green); }
        .rental-status-badge.danger { background:rgba(255,0,85,.1); color:#ff789f; }
        .rental-status-body { padding:24px; }
        .rental-status-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:17px; margin-bottom:22px; }
        .rental-status-label { display:block; margin-bottom:3px; color:var(--text-muted); font-size:12px; }
        .rental-status-value { font-weight:680; }
        .rental-status-panel { padding:17px; margin-top:17px; border:1px solid var(--border-color); border-radius:var(--radius-md); background:var(--v3-card); }
        .rental-status-panel h2 { margin:0 0 13px; font-size:16px; font-weight:750; }
        .rental-status-note { padding:13px 15px; border:1px solid rgba(0,229,255,.18); border-radius:var(--radius-sm); background:rgba(0,229,255,.06); color:var(--text-secondary); }
        .rental-status-instructions { color:var(--text-secondary); white-space:pre-line; font-size:14px; }
        .rental-qris { display:block; max-width:250px; max-height:250px; margin:12px auto; padding:10px; object-fit:contain; border-radius:8px; background:#fff; }
        .rental-proof-form .form-control { color:var(--text-primary); background:var(--v3-surface); border-color:var(--border-color); }
        .rental-share-panel { display:flex; justify-content:space-between; align-items:center; gap:16px; padding:15px 16px; margin-bottom:18px; border:1px solid var(--border-color); border-radius:var(--radius-md); background:var(--v3-card); }
        .rental-share-panel strong,.rental-share-panel span { display:block; }
        .rental-share-panel span { margin-top:3px; color:var(--text-secondary); font-size:13px; line-height:1.45; }
        .rental-share-actions { display:flex; flex-wrap:wrap; gap:8px; flex:none; }
        @media(max-width:575.98px) { .rental-status-head,.rental-status-body { padding:18px; } .rental-status-head { align-items:flex-start; flex-direction:column; } .rental-status-grid { grid-template-columns:1fr; gap:13px; } .rental-share-panel { align-items:stretch; flex-direction:column; } .rental-share-actions { display:grid; grid-template-columns:1fr; } }
    </style>
</head>
<body>
    @include('home.partials.navbar')
    <main class="app-container rental-status-page">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
        @php
            $badgeClass = $order->status === \App\Models\CourtRentalOrder::STATUS_CONFIRMED ? 'success' : (in_array($order->status, ['rejected', 'cancelled', 'expired'], true) ? 'danger' : '');
            $orderCode = str_pad((string) $order->id, 6, '0', STR_PAD_LEFT);
            $orderUrl = route('rental.show', $order->public_token);
            $shareText = 'Detail sewa perlengkapan OFS Futsal Center #'.$orderCode.': '.$orderUrl;
        @endphp
        <section class="rental-status-card">
            <header class="rental-status-head">
                <div><h1>Status sewa perlengkapan</h1><div class="rental-status-code">Kode pesanan #{{ $orderCode }}</div></div>
                <span class="rental-status-badge {{ $badgeClass }}">{{ $order->status_label }}</span>
            </header>
            <div class="rental-status-body">
                @if($scheduleHasPassed)
                    @if($order->rental_picked_up_at && !$order->rental_returned_at)
                        <div class="alert alert-warning mb-3">Perkiraan waktu kembali sudah lewat. Petugas akan mencatat pengembalian saat perlengkapan diterima agar stok dapat digunakan kembali.</div>
                    @else
                        <div class="rental-status-note mb-3">Waktu sewa telah lewat. Status pembayaran tetap tercatat terkonfirmasi.</div>
                    @endif
                @elseif($order->status === \App\Models\CourtRentalOrder::STATUS_EXPIRED)
                    <div class="rental-status-note mb-3">Batas pembayaran berakhir. Stok sudah tersedia kembali dan pesanan ini tidak dapat dibayar lagi.</div>
                @endif
                <div class="rental-status-grid">
                    <div><span class="rental-status-label">Tanggal sewa</span><span class="rental-status-value">{{ $order->rental_date->translatedFormat('l, d F Y') }}</span></div>
                    <div><span class="rental-status-label">Pengambilan dan perkiraan kembali</span><span class="rental-status-value">{{ substr($order->time_start, 0, 5) }}–{{ substr($order->time_end, 0, 5) }} WIB</span></div>
                    <div><span class="rental-status-label">Cara sewa dan durasi</span><span class="rental-status-value">{{ $order->rental_mode === 'now' ? 'Pakai sekarang' : 'Terjadwal' }} · {{ $order->duration_label }}</span></div>
                    <div><span class="rental-status-label">Nama pemesan</span><span class="rental-status-value">{{ $order->customer_name }}</span></div>
                    <div><span class="rental-status-label">Nomor WhatsApp</span><span class="rental-status-value">{{ $order->customer_phone }}</span></div>
                    <div><span class="rental-status-label">Metode pembayaran</span><span class="rental-status-value">{{ $order->payment_method_label }}</span></div>
                    <div><span class="rental-status-label">Total pembayaran</span><span class="rental-status-value">Rp {{ number_format((float) $order->amount, 0, ',', '.') }}</span></div>
                </div>

                @if($order->rental_picked_up_at)
                    <div class="rental-status-note mb-3">
                        @if($order->rental_returned_at)
                            Perlengkapan tercatat diambil {{ $order->rental_picked_up_at->timezone(\App\Models\CourtRentalOrder::TIMEZONE)->translatedFormat('H:i') }} dan dikembalikan {{ $order->rental_returned_at->timezone(\App\Models\CourtRentalOrder::TIMEZONE)->translatedFormat('H:i') }} WIB.
                        @else
                            Perlengkapan sudah diambil {{ $order->rental_picked_up_at->timezone(\App\Models\CourtRentalOrder::TIMEZONE)->translatedFormat('H:i') }} WIB dan masih menunggu pengembalian.
                        @endif
                    </div>
                @endif

                <section class="rental-status-panel" aria-labelledby="rentalItemsSummary">
                    <h2 id="rentalItemsSummary">Perlengkapan yang dipesan</h2>
                    <div class="d-grid gap-2">
                        @foreach($order->lines as $line)
                            <div class="d-flex justify-content-between align-items-center gap-3">
                                <span>{{ $line->item_name_snapshot }} × {{ $line->quantity }} <span class="text-secondary">(Rp {{ number_format((float) $line->unit_price, 0, ',', '.') }} / item)</span></span>
                                <strong class="text-nowrap">Rp {{ number_format((float) $line->line_total, 0, ',', '.') }}</strong>
                            </div>
                        @endforeach
                    </div>
                </section>

                @if($order->booking_policy_snapshot)
                    <div class="rental-status-instructions mt-3"><span class="rental-status-label">Kebijakan yang disetujui</span>{{ $order->booking_policy_snapshot }}</div>
                @endif

                <section class="rental-share-panel mt-3" aria-label="Simpan atau bagikan detail pesanan">
                    <div><strong>{{ match($order->status) {
                        \App\Models\CourtRentalOrder::STATUS_AWAITING_PAYMENT => 'Simpan tautan untuk melanjutkan pembayaran',
                        \App\Models\CourtRentalOrder::STATUS_PENDING_REVIEW => 'Simpan tautan untuk mengecek verifikasi',
                        \App\Models\CourtRentalOrder::STATUS_CONFIRMED => 'Tunjukkan halaman ini saat mengambil perlengkapan',
                        default => 'Simpan tautan untuk melihat detail pesanan',
                    } }}</strong><span>Tautan ini berisi status, jadwal, dan rincian pesanan Anda. Tidak perlu akun pelanggan.</span></div>
                    <div class="rental-share-actions">
                        <button class="btn btn-outline-light" type="button" id="copyRentalOrderLink" data-order-url="{{ $orderUrl }}"><i class="bi bi-link-45deg me-1" aria-hidden="true"></i>Salin tautan</button>
                        <a class="btn btn-outline-light" href="https://wa.me/?text={{ rawurlencode($shareText) }}" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp me-1" aria-hidden="true"></i>Bagikan via WhatsApp</a>
                    </div>
                </section>

                @if($order->status === \App\Models\CourtRentalOrder::STATUS_AWAITING_PAYMENT)
                    <div class="rental-status-note mb-3">Stok ditahan sampai <strong>{{ $order->expires_at?->timezone(\App\Models\CourtRentalOrder::TIMEZONE)->translatedFormat('H:i, d M Y') }}</strong>. Selesaikan pembayaran penuh dan kirim bukti sebelum waktu tersebut.</div>
                    <section class="rental-status-panel">
                        <h2>Instruksi pembayaran</h2>
                        @if($order->payment_method === 'bank_transfer')
                            <div class="rental-status-grid mb-0">
                                <div><span class="rental-status-label">Bank</span><span class="rental-status-value">{{ $order->payment_bank_name }}</span></div>
                                <div><span class="rental-status-label">Nomor rekening</span><span class="rental-status-value">{{ $order->payment_bank_account_number }}</span></div>
                                <div><span class="rental-status-label">Atas nama</span><span class="rental-status-value">{{ $order->payment_bank_account_name }}</span></div>
                                <div><span class="rental-status-label">Nominal transfer</span><span class="rental-status-value text-success">Rp {{ number_format((float) $order->amount, 0, ',', '.') }}</span></div>
                            </div>
                        @elseif($order->payment_qris_image_path)
                            <img class="rental-qris" src="{{ asset('storage/'.$order->payment_qris_image_path) }}" alt="Kode QRIS pembayaran OFS Futsal Center">
                            <p class="text-center mb-0">Bayar tepat <strong>Rp {{ number_format((float) $order->amount, 0, ',', '.') }}</strong> melalui QRIS di atas.</p>
                        @endif
                        @if($order->payment_instructions_snapshot)<div class="rental-status-instructions mt-3">{{ $order->payment_instructions_snapshot }}</div>@endif
                    </section>
                    <form class="rental-proof-form mt-4" method="POST" action="{{ route('rental.payment-proof', $order->public_token) }}" enctype="multipart/form-data">
                        @csrf
                        <h2 class="fs-6 fw-bold mb-3">Kirim bukti pembayaran</h2>
                        <div class="mb-3"><label class="form-label" for="paymentReference">Nomor referensi / nama pengirim <span class="text-secondary">(opsional)</span></label><input class="form-control" id="paymentReference" name="payment_reference" maxlength="100" value="{{ old('payment_reference') }}"></div>
                        <div class="mb-3"><label class="form-label" for="paymentProof">Bukti transfer atau pembayaran QRIS</label><input class="form-control" id="paymentProof" type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required><div class="form-text text-secondary">Format JPG, PNG, atau PDF. Maksimal 5 MB.</div></div>
                        <button class="btn btn-primary fw-bold" type="submit"><i class="bi bi-upload me-1" aria-hidden="true"></i>Kirim bukti pembayaran</button>
                    </form>
                @elseif($order->status === \App\Models\CourtRentalOrder::STATUS_PENDING_REVIEW)
                    <div class="rental-status-note"><i class="bi bi-hourglass-split me-2" aria-hidden="true"></i>Bukti pembayaran diterima dan sedang diperiksa admin. Stok tetap ditahan selama verifikasi.</div>
                @elseif($order->status === \App\Models\CourtRentalOrder::STATUS_CONFIRMED)
                    <div class="rental-status-note"><i class="bi bi-check-circle me-2" aria-hidden="true"></i>Pembayaran sudah dikonfirmasi. Tunjukkan halaman ini kepada petugas saat mengambil perlengkapan. Pengembalian dicatat oleh petugas.</div>
                @elseif(in_array($order->status, [\App\Models\CourtRentalOrder::STATUS_REJECTED, \App\Models\CourtRentalOrder::STATUS_CANCELLED], true))
                    <div class="rental-status-note">Pesanan ini {{ $order->status === 'rejected' ? 'ditolak' : 'dibatalkan' }} dan stok telah dilepas. @if($order->admin_note) Catatan admin: {{ $order->admin_note }} @endif</div>
                @elseif($order->status === \App\Models\CourtRentalOrder::STATUS_EXPIRED)
                    <div class="rental-status-note">Batas pembayaran telah berakhir. Silakan buat pesanan baru jika masih ingin menyewa perlengkapan.</div>
                @endif
            </div>
        </section>
        <div class="text-center mt-4"><a class="nav-menu-link" href="{{ route('booking.index') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke booking lapangan</a></div>
    </main>
    @include('home.partials.footer')
    @php
        $rentalResumeData = [
            'token' => $order->public_token,
            'code' => str_pad((string) $order->id, 6, '0', STR_PAD_LEFT),
            'status' => $order->status,
            'statusLabel' => $order->status_label,
            'expiresAt' => $order->expires_at?->toIso8601String(),
            'rentalDate' => $order->rental_date->toDateString(),
            'timeStart' => substr($order->time_start, 0, 5),
            'timeEnd' => substr($order->time_end, 0, 5),
            'amount' => (float) $order->amount,
        ];
    @endphp
    <script>
        (() => {
            const storageKey = 'ofs_rental_orders_resume_v1';
            const order = @json($rentalResumeData);
            try {
                const saved = JSON.parse(window.localStorage.getItem(storageKey) || '[]');
                const items = Array.isArray(saved) ? saved.filter((item) => item && item.token !== order.token) : [];
                items.unshift({ ...order, savedAt: Date.now() });
                window.localStorage.setItem(storageKey, JSON.stringify(items.slice(0, 10)));
            } catch { /* The saved page link and WhatsApp sharing remain available. */ }

            const button = document.getElementById('copyRentalOrderLink');
            if (!button) return;
            button.addEventListener('click', async () => {
                let copied = false;
                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(button.dataset.orderUrl);
                        copied = true;
                    } else {
                        const temporaryInput = document.createElement('textarea');
                        temporaryInput.value = button.dataset.orderUrl;
                        temporaryInput.setAttribute('readonly', '');
                        temporaryInput.style.position = 'fixed';
                        temporaryInput.style.opacity = '0';
                        document.body.append(temporaryInput);
                        temporaryInput.select();
                        copied = document.execCommand('copy');
                        temporaryInput.remove();
                    }
                } catch { copied = false; }
                if (copied) {
                    button.innerHTML = '<i class="bi bi-check2 me-1" aria-hidden="true"></i>Tautan tersalin';
                } else {
                    window.prompt('Salin tautan pesanan ini:', button.dataset.orderUrl);
                }
            });

            if (order.status === 'pending_review') {
                window.setInterval(async () => {
                    try {
                        const response = await fetch(`${window.location.pathname}/status`, {
                            headers: { Accept: 'application/json' }, cache: 'no-store', credentials: 'same-origin',
                        });
                        if (!response.ok) return;
                        const latest = await response.json();
                        if (latest.status !== 'pending_review') window.location.reload();
                    } catch { /* Customer can refresh this page to check again. */ }
                }, 30000);
            }
        })();
    </script>
</body>
</html>
