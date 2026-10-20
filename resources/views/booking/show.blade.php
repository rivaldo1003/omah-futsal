<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>Status Booking - OFS Futsal Center</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ofs.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @include('home.partials.styles')
    <style>
        .booking-status-page { max-width:800px; padding-top:44px; padding-bottom:64px; }
        .status-card { background:var(--surface); border:1px solid var(--border-color); border-radius:var(--v3-radius-md); overflow:hidden; }
        .status-head { padding:22px 24px; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between; gap:16px; align-items:center; }
        .status-head h1 { font-size:22px; font-weight:800; margin:0 0 5px; }
        .status-code { color:var(--text-secondary); font:12px var(--v3-font-mono); }
        .status-badge { border:1px solid #e6d7ae; border-radius:999px; padding:7px 11px; font-size:12px; font-weight:700; background:var(--warning-subtle); color:var(--warning); white-space:nowrap; }
        .status-badge.success { border-color:#b7d7c4; background:var(--success-subtle); color:var(--success); }
        .status-badge.danger { border-color:#e5c1bd; background:var(--danger-subtle); color:var(--danger); }
        .status-body { padding:24px; }
        .booking-details { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:18px; margin-bottom:22px; }
        .detail-label { display:block; color:var(--text-muted); font-size:12px; margin-bottom:3px; }
        .detail-value { display:block; font-weight:650; }
        .payment-panel { border:1px solid var(--border-color); border-radius:var(--radius-md); background:var(--v3-card); padding:18px; margin-top:18px; }
        .payment-panel h2 { font-size:16px; font-weight:750; margin:0 0 14px; }
        .qris-image { display:block; max-width:250px; max-height:250px; object-fit:contain; background:#fff; padding:10px; border-radius:8px; margin:12px auto; }
        .payment-instructions { color:var(--text-secondary); white-space:pre-line; font-size:14px; }
        .proof-form .form-control { background:var(--v3-surface); color:var(--text-primary); border-color:var(--border-color); }
        .status-note { color:var(--text-secondary); background:rgba(0,229,255,.06); border:1px solid rgba(0,229,255,.18); border-radius:var(--radius-sm); padding:13px 15px; }
        .booking-link-panel { display:flex; justify-content:space-between; align-items:center; gap:16px; padding:15px 16px; margin:0 0 18px; background:var(--v3-card); border:1px solid var(--border-color); border-radius:var(--radius-md); }
        .booking-link-copy { min-width:0; }
        .booking-link-copy strong { display:block; font-size:14px; }
        .booking-link-copy span { display:block; margin-top:3px; color:var(--text-secondary); font-size:13px; line-height:1.45; }
        .booking-link-actions { display:flex; flex-wrap:wrap; gap:8px; flex-shrink:0; }
        .booking-link-actions .btn {
            min-height: 44px;
            border: 1px solid var(--border-color);
            background: var(--surface);
            color: var(--text-primary);
            font-weight: 600;
        }
        .booking-link-actions .btn:hover {
            border-color: var(--text-primary);
            background: var(--surface-hover);
            color: var(--text-primary);
        }
        .booking-link-actions .btn:focus-visible {
            outline: 3px solid var(--border-focus);
            outline-offset: 2px;
        }
        .booking-link-feedback { display:block; min-height:18px; margin-top:4px; color:var(--v3-neon-green); font-size:13px; }
        @media(max-width:575.98px) { .status-head,.status-body { padding:18px; } .booking-details { grid-template-columns:1fr; gap:13px; } .status-head { align-items:flex-start; flex-direction:column; } }
        @media(max-width:575.98px) { .booking-link-panel { align-items:stretch; flex-direction:column; } .booking-link-actions { display:grid; grid-template-columns:1fr; } }
    </style>
</head>

<body>
    @include('home.partials.navbar')
    <main class="app-container booking-status-page">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

        @php
            $badgeClass = $booking->status === 'confirmed' ? 'success' : (in_array($booking->status, ['rejected', 'cancelled', 'expired'], true) ? 'danger' : '');
        @endphp
        <section class="status-card">
            <header class="status-head">
                <div>
                    <h1>Status booking</h1>
                    <div class="status-code">Kode booking #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
                <span class="status-badge {{ $badgeClass }}">{{ $booking->status_label }}</span>
            </header>
            <div class="status-body">
                @if($scheduleHasPassed)
                    <div class="status-note mb-3">Jadwal booking telah lewat. Pembayaran tetap tercatat terkonfirmasi.</div>
                @elseif($booking->status === \App\Models\CourtBooking::STATUS_EXPIRED)
                    <div class="status-note mb-3">Batas pembayaran habis. Slot telah tersedia kembali dan booking ini tidak dapat dibayar lagi.</div>
                @endif
                <div class="booking-details">
                    <div><span class="detail-label">Lapangan</span><span class="detail-value">{{ $booking->court->name }}</span></div>
                    <div><span class="detail-label">Tanggal</span><span class="detail-value">{{ $booking->booking_date->translatedFormat('l, d F Y') }}</span></div>
                    <div><span class="detail-label">Waktu</span><span class="detail-value">{{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }} ({{ $booking->duration_label }})</span></div>
                    <div><span class="detail-label">Total tagihan booking</span><span class="detail-value">Rp {{ number_format((float) $booking->amount, 0, ',', '.') }}</span></div>
                    <div><span class="detail-label">Nama pemesan</span><span class="detail-value">{{ $booking->customer_name }}</span></div>
                    <div><span class="detail-label">Nama tim</span><span class="detail-value">{{ $booking->team_name ?: '—' }}</span></div>
                    <div><span class="detail-label">Metode pembayaran</span><span class="detail-value">{{ $booking->payment_method_label }}</span></div>
                </div>
                @php
                    $paidAmount = (float) ($booking->paid_amount ?? 0);
                    $remainingAmount = in_array($booking->status, [
                        \App\Models\CourtBooking::STATUS_AWAITING_PAYMENT,
                        \App\Models\CourtBooking::STATUS_PENDING_REVIEW,
                        \App\Models\CourtBooking::STATUS_CONFIRMED,
                    ], true) ? max(0, (float) $booking->amount - $paidAmount) : null;
                @endphp
                <section class="payment-panel mb-3" aria-labelledby="paymentSummaryTitle">
                    <h2 id="paymentSummaryTitle">Ringkasan pembayaran</h2>
                    <div class="booking-details mb-0">
                        <div><span class="detail-label">Sudah dibayar dan diverifikasi</span><span class="detail-value">Rp {{ number_format($paidAmount, 0, ',', '.') }}</span></div>
                        <div><span class="detail-label">Sisa pembayaran</span><span class="detail-value">{{ $remainingAmount === null ? '—' : 'Rp '.number_format($remainingAmount, 0, ',', '.') }}</span></div>
                    </div>
                    @if($booking->payments->isNotEmpty())
                        <div class="table-responsive mt-3">
                            <table class="table table-sm align-middle mb-0">
                                <thead><tr><th scope="col">Pembayaran</th><th scope="col">Status</th><th scope="col" class="text-end">Nominal</th></tr></thead>
                                <tbody>
                                    @foreach($booking->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->stage_label }} · {{ $payment->payment_method_label }} <span class="small text-secondary">· {{ $payment->recorded_at?->timezone(\App\Models\CourtBooking::TIMEZONE)->translatedFormat('d M Y, H:i') }} WIB</span></td>
                                            <td>{{ $payment->status_label }}</td>
                                            <td class="text-end">
                                                @if($payment->status === \App\Models\CourtBookingPayment::STATUS_CONFIRMED)
                                                    Rp {{ number_format($payment->recognized_amount, 0, ',', '.') }}
                                                @elseif($payment->claimed_amount !== null)
                                                    Klaim Rp {{ number_format((float) $payment->claimed_amount, 0, ',', '.') }}
                                                @elseif($payment->status === \App\Models\CourtBookingPayment::STATUS_REJECTED)
                                                    Tidak terverifikasi
                                                @else
                                                    Dicatat admin setelah pemeriksaan
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
                @if($booking->rentalLines->isNotEmpty())
                    <section class="payment-panel mb-3" aria-labelledby="rentalSummaryTitle">
                        <h2 id="rentalSummaryTitle">Perlengkapan sewa</h2>
                        <p class="small text-secondary mb-3">Pengambilan dan perkiraan pengembalian mengikuti jadwal booking lapangan.</p>
                        <div class="d-grid gap-2">
                            @foreach($booking->rentalLines as $line)
                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <span>{{ $line->item_name_snapshot }} × {{ $line->quantity }} <span class="text-secondary">(Rp {{ number_format((float) $line->unit_price, 0, ',', '.') }} / item)</span></span>
                                    <strong class="text-nowrap">Rp {{ number_format((float) $line->line_total, 0, ',', '.') }}</strong>
                                </div>
                            @endforeach
                        </div>
                        @if($booking->rental_picked_up_at)
                            <div class="small mt-3">
                                @if($booking->rental_returned_at)
                                    Perlengkapan diambil {{ $booking->rental_picked_up_at->timezone(\App\Models\CourtBooking::TIMEZONE)->format('H:i') }} dan dikembalikan {{ $booking->rental_returned_at->timezone(\App\Models\CourtBooking::TIMEZONE)->format('H:i') }} WIB.
                                @else
                                    Perlengkapan telah diambil dan menunggu pengembalian.
                                @endif
                            </div>
                        @endif
                    </section>
                @endif
                @if($booking->rentalAdditions->isNotEmpty())
                    <section class="payment-panel mb-3" aria-labelledby="rentalAdditionTitle">
                        <h2 id="rentalAdditionTitle">Tambahan perlengkapan</h2>
                        <p class="small text-secondary mb-3">Transaksi ini terpisah dari pembayaran awal. Status setiap tambahan ditampilkan di sini.</p>
                        @foreach($booking->rentalAdditions as $addition)
                            @php
                                $additionStart = \Carbon\Carbon::parse($booking->booking_date->toDateString().' '.$booking->time_start, \App\Models\CourtBooking::TIMEZONE);
                                $additionBadge = match ($addition->status) {
                                    'confirmed' => 'success',
                                    'rejected', 'cancelled', 'expired' => 'danger',
                                    default => '',
                                };
                            @endphp
                            <div class="border rounded p-3 mb-3" data-rental-addition="{{ $addition->id }}">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                    <div><strong>Tambahan #{{ str_pad((string) $addition->id, 4, '0', STR_PAD_LEFT) }}</strong><div class="small text-secondary">{{ $addition->payment_method_label }} · Rp {{ number_format((float) $addition->amount, 0, ',', '.') }}</div></div>
                                    <span class="status-badge {{ $additionBadge }}">{{ $addition->status_label }}</span>
                                </div>
                                <div class="d-grid gap-2 mb-3">
                                    @foreach($addition->lines as $line)
                                        <div class="d-flex justify-content-between gap-3"><span>{{ $line->item_name_snapshot }} × {{ $line->quantity }}</span><strong>Rp {{ number_format((float) $line->line_total, 0, ',', '.') }}</strong></div>
                                    @endforeach
                                </div>
                                @if($addition->status === \App\Models\CourtBookingRentalAddition::STATUS_AWAITING_PAYMENT)
                                    @if($addition->expires_at)
                                        <div class="status-note mb-3">Stok tambahan ditahan sampai <strong>{{ $addition->expires_at->timezone(\App\Models\CourtBooking::TIMEZONE)->translatedFormat('H:i, d M Y') }}</strong>. Pembayaran booking lapangan tetap aman jika tambahan ini kedaluwarsa.</div>
                                    @endif
                                    @if($addition->payment_method === 'bank_transfer')
                                        <div class="booking-details mb-2">
                                            <div><span class="detail-label">Bank</span><span class="detail-value">{{ $addition->payment_bank_name }}</span></div>
                                            <div><span class="detail-label">Nomor rekening</span><span class="detail-value">{{ $addition->payment_bank_account_number }}</span></div>
                                            <div><span class="detail-label">Atas nama</span><span class="detail-value">{{ $addition->payment_bank_account_name }}</span></div>
                                            <div><span class="detail-label">Nominal tambahan</span><span class="detail-value text-success">Rp {{ number_format((float) $addition->amount, 0, ',', '.') }}</span></div>
                                        </div>
                                    @elseif($addition->payment_qris_image_path)
                                        <img class="qris-image" src="{{ asset('storage/'.$addition->payment_qris_image_path) }}" alt="Kode QRIS pembayaran tambahan">
                                        <p class="text-center">Bayar tepat <strong>Rp {{ number_format((float) $addition->amount, 0, ',', '.') }}</strong> melalui QRIS.</p>
                                    @endif
                                    @if($addition->payment_instructions_snapshot)<div class="payment-instructions mb-3">{{ $addition->payment_instructions_snapshot }}</div>@endif
                                    @if($booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED && !$booking->rental_picked_up_at && $additionStart->isAfter(now(\App\Models\CourtBooking::TIMEZONE)) && $addition->expires_at?->isFuture())
                                        <form class="proof-form" method="POST" action="{{ route('booking.rental-additions.payment-proof', [$booking->public_token, $addition]) }}" enctype="multipart/form-data">
                                            @csrf
                                            <h3 class="fs-6 fw-bold">Unggah bukti pembayaran tambahan</h3>
                                            <label class="form-label" for="additionReference{{ $addition->id }}">Referensi / nama pengirim <span class="text-secondary">(opsional)</span></label>
                                            <input class="form-control mb-2" id="additionReference{{ $addition->id }}" name="payment_reference" maxlength="100">
                                            <label class="form-label" for="additionProof{{ $addition->id }}">Bukti transfer atau QRIS</label>
                                            <input class="form-control mb-3" id="additionProof{{ $addition->id }}" type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required>
                                            <button class="btn btn-primary fw-bold" type="submit"><i class="bi bi-upload me-1" aria-hidden="true"></i>Kirim bukti tambahan</button>
                                        </form>
                                    @endif
                                @elseif($addition->status === \App\Models\CourtBookingRentalAddition::STATUS_PENDING_REVIEW)
                                    <div class="status-note">Bukti pembayaran tambahan sudah diterima dan menunggu pemeriksaan admin.</div>
                                @elseif($addition->status === \App\Models\CourtBookingRentalAddition::STATUS_CONFIRMED)
                                    <div class="status-note"><i class="bi bi-check-circle me-2" aria-hidden="true"></i>Pembayaran tambahan telah dikonfirmasi.</div>
                                @elseif($addition->status === \App\Models\CourtBookingRentalAddition::STATUS_EXPIRED)
                                    <div class="status-note">Waktu pembayaran tambahan berakhir. Booking lapangan dan pembayaran awal tetap terkonfirmasi.</div>
                                @elseif($addition->status === \App\Models\CourtBookingRentalAddition::STATUS_REJECTED)
                                    <div class="status-note">Pembayaran tambahan belum dapat diverifikasi. @if($addition->admin_note) Catatan admin: {{ $addition->admin_note }} @endif</div>
                                @elseif($addition->status === \App\Models\CourtBookingRentalAddition::STATUS_CANCELLED)
                                    <div class="status-note">Tambahan perlengkapan dibatalkan. @if($addition->admin_note) {{ $addition->admin_note }} @endif</div>
                                @endif
                            </div>
                        @endforeach
                    </section>
                @endif
                @if($booking->booking_policy_snapshot)
                    <div class="payment-instructions mb-3"><span class="detail-label">Kebijakan booking yang disetujui</span>{{ $booking->booking_policy_snapshot }}</div>
                @endif

                @php
                    $bookingResumeUrl = route('booking.show', $booking->public_token);
                    $bookingShareText = 'Detail booking OFS Futsal Center #'.str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT).': '.$bookingResumeUrl;
                    $bookingLinkHeading = match ($booking->status) {
                        \App\Models\CourtBooking::STATUS_AWAITING_PAYMENT => 'Simpan tautan untuk melanjutkan pembayaran',
                        \App\Models\CourtBooking::STATUS_PENDING_REVIEW => 'Simpan tautan untuk mengecek hasil verifikasi',
                        \App\Models\CourtBooking::STATUS_CONFIRMED => 'Tunjukkan halaman ini saat datang ke lapangan',
                        default => 'Simpan tautan untuk membuka detail booking',
                    };
                    $bookingLinkMessage = match ($booking->status) {
                        \App\Models\CourtBooking::STATUS_AWAITING_PAYMENT => 'Tautan ini membuka instruksi pembayaran dan status booking. Batas pembayaran tetap berjalan.',
                        \App\Models\CourtBooking::STATUS_PENDING_REVIEW => 'Status akan menampilkan hasil terbaru saat Anda membuka kembali tautan ini.',
                        \App\Models\CourtBooking::STATUS_CONFIRMED => 'Halaman ini menampilkan kode, lapangan, tanggal, dan jam booking yang sudah dikonfirmasi.',
                        default => 'Tautan ini hanya dapat dibuka oleh orang yang memilikinya. Simpan untuk referensi Anda.',
                    };
                @endphp
                <section class="booking-link-panel" aria-label="Simpan atau bagikan detail booking">
                    <div class="booking-link-copy">
                        <strong>{{ $bookingLinkHeading }}</strong>
                        <span>{{ $bookingLinkMessage }}</span>
                        <span class="booking-link-feedback" id="bookingLinkFeedback" role="status" aria-live="polite"></span>
                    </div>
                    <div class="booking-link-actions">
                        <button class="btn" type="button" id="copyBookingLink" data-booking-url="{{ $bookingResumeUrl }}">
                            <i class="bi bi-link-45deg me-1" aria-hidden="true"></i>Salin tautan
                        </button>
                        <a class="btn" href="https://wa.me/?text={{ rawurlencode($bookingShareText) }}" target="_blank" rel="noopener noreferrer">
                            <i class="bi bi-whatsapp me-1" aria-hidden="true"></i>Bagikan via WhatsApp
                        </a>
                    </div>
                </section>

                @if($booking->status === \App\Models\CourtBooking::STATUS_AWAITING_PAYMENT)
                    <div class="status-note mb-3">
                        Slot ditahan sampai <strong>{{ $booking->expires_at?->timezone(\App\Models\CourtBooking::TIMEZONE)->translatedFormat('H:i, d M Y') }}</strong>. Anda dapat membayar DP atau melunasi, lalu unggah bukti sebelum waktu tersebut. Booking dikonfirmasi setelah admin memverifikasi pembayaran.
                    </div>
                    <div class="payment-panel">
                        <h2>Instruksi pembayaran</h2>
                        @if($booking->payment_method === 'bank_transfer')
                            <div class="booking-details mb-0">
                                <div><span class="detail-label">Bank</span><span class="detail-value">{{ $booking->payment_bank_name }}</span></div>
                                <div><span class="detail-label">Nomor rekening</span><span class="detail-value">{{ $booking->payment_bank_account_number }}</span></div>
                                <div><span class="detail-label">Atas nama</span><span class="detail-value">{{ $booking->payment_bank_account_name }}</span></div>
                                <div><span class="detail-label">Maksimal pembayaran</span><span class="detail-value text-success">Rp {{ number_format((float) $booking->amount, 0, ',', '.') }}</span></div>
                            </div>
                        @elseif($booking->payment_qris_image_path)
                            <img class="qris-image" src="{{ asset('storage/'.$booking->payment_qris_image_path) }}" alt="Kode QRIS pembayaran OFS Futsal Center">
                            <p class="text-center mb-0">Pindai QRIS lalu bayar DP atau lunas, maksimal <strong>Rp {{ number_format((float) $booking->amount, 0, ',', '.') }}</strong>.</p>
                        @endif
                        @if($booking->payment_instructions_snapshot)
                            <div class="payment-instructions mt-3">{{ $booking->payment_instructions_snapshot }}</div>
                        @endif
                    </div>

                    <form class="proof-form mt-4" method="POST" action="{{ route('booking.payment-proof', $booking->public_token) }}" enctype="multipart/form-data">
                        @csrf
                        <h2 class="fs-6 fw-bold mb-3">Kirim bukti pembayaran</h2>
                        <p class="small text-secondary">Unggah bukti pembayaran saja. Admin akan mencocokkan nominal dengan mutasi rekening atau pembayaran yang diterima, lalu mencatat DP atau pelunasan yang terverifikasi.</p>
                        <div class="mb-3">
                            <label for="paymentReference" class="form-label">Nomor referensi / nama pengirim <span class="text-secondary">(opsional)</span></label>
                            <input class="form-control" id="paymentReference" name="payment_reference" maxlength="100" value="{{ old('payment_reference') }}">
                        </div>
                        <div class="mb-3">
                            <label for="paymentProof" class="form-label">Bukti transfer atau pembayaran QRIS</label>
                            <input class="form-control" id="paymentProof" type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required>
                            <div class="form-text text-secondary">Format JPG, PNG, atau PDF. Maksimal 5 MB.</div>
                        </div>
                        <button class="btn btn-primary fw-bold" type="submit"><i class="bi bi-upload me-1"></i> Kirim bukti pembayaran</button>
                    </form>
                @elseif($booking->status === \App\Models\CourtBooking::STATUS_PENDING_REVIEW)
                    <div class="status-note"><i class="bi bi-hourglass-split me-2"></i>Bukti pembayaran sudah diterima. Admin sedang memeriksa pembayaran; slot tetap ditahan selama proses verifikasi.</div>
                @elseif($booking->status === \App\Models\CourtBooking::STATUS_CONFIRMED)
                    <div class="status-note"><i class="bi bi-check-circle me-2"></i>Booking sudah dikonfirmasi. Simpan halaman ini sebagai bukti jadwal Anda.@if($remainingAmount > 0) Masih ada sisa pembayaran Rp {{ number_format($remainingAmount, 0, ',', '.') }} yang perlu dilunasi kepada admin.@endif</div>
                @elseif($booking->status === \App\Models\CourtBooking::STATUS_REJECTED)
                    <div class="status-note">Pembayaran belum dapat diverifikasi dan slot sudah dilepas. @if($booking->admin_note) Catatan admin: {{ $booking->admin_note }} @endif</div>
                @elseif($booking->status === \App\Models\CourtBooking::STATUS_CANCELLED)
                    <div class="status-note">Booking ini telah dibatalkan. @if($booking->admin_note) Catatan: {{ $booking->admin_note }} @endif Silakan hubungi admin mengenai pengembalian dana bila diperlukan.</div>
                @endif
            </div>
        </section>
        <div class="text-center mt-4"><a class="nav-menu-link" href="{{ route('booking.index') }}"><i class="bi bi-arrow-left"></i> Kembali ke jadwal lapangan</a></div>
    </main>
    @include('home.partials.footer')
    @php
        $bookingResumeData = [
            'token' => $booking->public_token,
            'code' => str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT),
            'status' => $booking->status,
            'expiresAt' => $booking->expires_at?->toIso8601String(),
            'courtName' => $booking->court->name,
            'bookingDate' => $booking->booking_date->toDateString(),
            'timeStart' => substr($booking->time_start, 0, 5),
            'timeEnd' => substr($booking->time_end, 0, 5),
            'amount' => (float) $booking->amount,
            'scheduleHasPassed' => $scheduleHasPassed,
            'additions' => $booking->rentalAdditions->map(fn ($addition) => ['id' => $addition->id, 'status' => $addition->status])->values(),
        ];
    @endphp
    <script>
        (() => {
            const storageKey = 'ofs_booking_resume_v1';
            const booking = @json($bookingResumeData);
            try {
                const saved = JSON.parse(window.localStorage.getItem(storageKey) || '[]');
                const items = Array.isArray(saved) ? saved.filter((item) => item && item.token !== booking.token) : [];

                items.unshift({ ...booking, savedAt: Date.now() });

                window.localStorage.setItem(storageKey, JSON.stringify(items.slice(0, 10)));
            } catch {
                // This page still works; copy and WhatsApp sharing remain available without local storage.
            }

            const copyButton = document.getElementById('copyBookingLink');
            const feedback = document.getElementById('bookingLinkFeedback');
            if (!copyButton || !feedback) return;

            copyButton.addEventListener('click', async () => {
                const text = copyButton.dataset.bookingUrl;
                let copied = false;

                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(text);
                        copied = true;
                    } else {
                        const temporaryInput = document.createElement('textarea');
                        temporaryInput.value = text;
                        temporaryInput.setAttribute('readonly', '');
                        temporaryInput.setAttribute('aria-hidden', 'true');
                        temporaryInput.style.position = 'fixed';
                        temporaryInput.style.opacity = '0';
                        document.body.append(temporaryInput);
                        temporaryInput.select();
                        copied = document.execCommand('copy');
                        temporaryInput.remove();
                    }
                } catch {
                    copied = false;
                }

                feedback.textContent = copied
                    ? 'Tautan booking berhasil disalin.'
                    : 'Tautan belum bisa disalin. Gunakan tombol Bagikan via WhatsApp.';
            });

            if (booking.status === 'pending_review' || booking.status === 'confirmed' || booking.additions.some(addition => ['awaiting_payment', 'pending_review'].includes(addition.status))) {
                window.setInterval(async () => {
                    try {
                        const response = await fetch(`${window.location.pathname}/status`, {
                            headers: { Accept: 'application/json' },
                            cache: 'no-store',
                            credentials: 'same-origin',
                        });
                        if (!response.ok) return;
                        const latest = await response.json();
                        const latestAdditions = latest.rental_additions || [];
                        const additionsChanged = JSON.stringify(latestAdditions.map(item => [item.id, item.status]))
                            !== JSON.stringify(booking.additions.map(item => [item.id, item.status]));
                        if (latest.status !== booking.status || additionsChanged) window.location.reload();
                    } catch {
                        // The customer can still refresh this page manually to check the booking.
                    }
                }, 30000);
            }
        })();
    </script>
</body>
</html>
