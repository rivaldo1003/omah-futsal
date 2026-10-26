@extends('layouts.admin')

@section('title', 'Catat Booking Member')

@section('styles')
    <style>
        .member-booking-page {
            --mb-ink: #111111;
            --mb-charcoal: #39393b;
            --mb-muted: #707072;
            --mb-line: #cacacb;
            --mb-line-soft: #e5e5e5;
            --mb-soft: #f5f5f5;
            --mb-red: #780700;
            max-width: 1200px;
            margin-inline: auto;
            color: var(--mb-ink);
            font-size: 15px;
            line-height: 1.5;
        }

        .member-booking-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 28px;
        }

        .member-booking-header h1 {
            margin: 0 0 8px;
            font-size: clamp(1.75rem, 2.5vw, 2rem);
            font-weight: 500;
            line-height: 1.2;
        }

        .member-booking-header p { max-width: 72ch; margin: 0; color: var(--mb-muted); font-size: 15px; }

        .member-booking-page .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.3;
            transition: background-color .16s ease, color .16s ease, border-color .16s ease;
        }

        .member-booking-page .btn-primary {
            border-color: var(--mb-ink);
            background: var(--mb-ink);
            color: #fff;
        }

        .member-booking-page .btn-primary:hover,
        .member-booking-page .btn-primary:focus-visible { border-color: var(--mb-charcoal); background: var(--mb-charcoal); color: #fff; }

        .member-booking-page .btn-outline-secondary {
            border-color: transparent;
            background: var(--mb-soft);
            color: var(--mb-ink);
        }

        .member-booking-page .btn-outline-secondary:hover,
        .member-booking-page .btn-outline-secondary:focus-visible { border-color: transparent; background: #ebebeb; color: var(--mb-ink); }

        .member-booking-page :is(a, button):focus-visible {
            outline: 2px solid var(--mb-ink);
            outline-offset: 2px;
        }

        .member-booking-panel {
            overflow: hidden;
            border: 1px solid var(--mb-line-soft);
            border-radius: 8px;
            background: #fff;
        }

        .member-booking-panel-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 24px;
            border-bottom: 1px solid var(--mb-line-soft);
        }

        .member-booking-panel-heading h2 { margin: 0; font-size: 18px; font-weight: 500; }
        .member-booking-panel-body { padding: 24px; }
        .member-booking-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin: 0 0 24px;
            border-bottom: 1px solid var(--mb-line-soft);
            padding-bottom: 22px;
        }
        .member-booking-fact { min-width: 0; padding: 0 16px; }
        .member-booking-fact:first-child { padding-left: 0; }
        .member-booking-fact + .member-booking-fact { border-left: 1px solid var(--mb-line-soft); }
        .member-booking-fact span { display: block; margin-bottom: 5px; color: var(--mb-muted); font-size: 12px; }
        .member-booking-fact strong { display: block; overflow-wrap: anywhere; color: var(--mb-ink); font-size: 14px; font-weight: 500; }
        .member-booking-section-heading { margin: 0 0 14px; }
        .member-booking-section-heading h3 { margin: 0 0 3px; font-size: 15px; font-weight: 500; }
        .member-booking-section-heading p { margin: 0; color: var(--mb-muted); font-size: 13px; }
        .member-booking-date-form { display: grid; grid-template-columns: minmax(240px, 360px) auto; align-items: end; gap: 12px; }
        .member-booking-fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px 20px; }
        .member-booking-fields .form-label { margin-bottom: 7px; color: var(--mb-charcoal); font-size: 14px; font-weight: 500; }
        .member-booking-fields .grid-column-span-2 { grid-column: 1 / -1; }
        .member-booking-fields .form-control, .member-booking-fields .form-select {
            min-height: 42px;
            border: 1px solid var(--mb-line);
            border-radius: 6px;
            color: var(--mb-ink);
        }
        .member-booking-fields .form-control:focus, .member-booking-fields .form-select:focus {
            border: 2px solid var(--mb-ink);
            box-shadow: none;
        }
        .member-booking-field-help { display: block; margin-top: 6px; color: var(--mb-muted); font-size: 12px; line-height: 1.45; }
        .member-booking-date-actions { display: flex; align-items: flex-end; }
        .member-booking-date-pending {
            margin-top: 12px;
            padding: 11px 14px;
            border: 1px solid #ead9a7;
            border-radius: 6px;
            background: #fff9e8;
            color: #725100;
            font-size: 13px;
        }
        .member-booking-date-pending[hidden] { display: none; }
        .member-booking-date-confirmation {
            display: block;
            margin: 0 0 12px;
            border-color: var(--mb-line-soft);
            background: var(--mb-soft);
            color: var(--mb-charcoal);
        }
        .member-booking-price {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin: 22px 0 20px;
            padding: 16px 18px;
            border-radius: 6px;
            background: var(--mb-soft);
        }
        .member-booking-price span { display: block; color: var(--mb-muted); font-size: 13px; }
        .member-booking-price strong { display: block; margin-top: 2px; color: var(--mb-ink); font-size: 22px; font-weight: 500; }
        .member-booking-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 10px; margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--mb-line-soft); }
        .member-booking-notice { display: flex; align-items: flex-start; gap: 10px; margin: 0 0 18px; padding: 12px 14px; border: 1px solid #c8e5d7; border-radius: 6px; background: #eff8f3; color: #165b3d; font-size: 13px; }
        .member-booking-notice--warning { border-color: #ead9a7; background: #fff9e8; color: #725100; }
        .member-booking-alert { margin: 18px 0 0; padding: 12px 14px; border: 1px solid #e1b9b5; border-radius: 6px; background: #fff5f4; color: var(--mb-red); font-size: 13px; }
        .member-booking-existing { display: grid; gap: 14px; margin-top: 22px; }
        .member-booking-existing dl { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; margin: 0; padding: 16px 0; border-block: 1px solid var(--mb-line-soft); }
        .member-booking-existing dl div + div { padding-left: 20px; border-left: 1px solid var(--mb-line-soft); }
        .member-booking-existing dt { color: var(--mb-muted); font-size: 12px; font-weight: 400; }
        .member-booking-existing dd { margin: 4px 0 0; color: var(--mb-ink); font-size: 16px; font-weight: 500; }
        .member-booking-page :is(a, button) { min-height: 44px; }

        @media (max-width: 800px) {
            .member-booking-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px 0; }
            .member-booking-fact:nth-child(odd) { padding-left: 0; border-left: 0; }
            .member-booking-date-form { grid-template-columns: minmax(0, 1fr) auto; }
            .member-booking-fields { gap: 14px; }
            .member-booking-existing dl { grid-template-columns: 1fr; }
            .member-booking-existing dl div + div { padding: 12px 0 0; border-top: 1px solid var(--mb-line-soft); border-left: 0; }
        }

        @media (max-width: 575px) {
            .member-booking-header { flex-direction: column; }
            .member-booking-header .btn { width: 100%; }
            .member-booking-header { gap: 16px; margin-bottom: 20px; }
            .member-booking-panel-heading { padding: 16px 18px; }
            .member-booking-panel-body { padding: 18px; }
            .member-booking-summary { grid-template-columns: 1fr; gap: 0; padding-bottom: 14px; }
            .member-booking-fact, .member-booking-fact:first-child { padding: 10px 0; }
            .member-booking-fact + .member-booking-fact { border-top: 1px solid var(--mb-line-soft); border-left: 0; }
            .member-booking-date-form { grid-template-columns: 1fr; }
            .member-booking-date-actions .btn { width: 100%; }
            .member-booking-fields { grid-template-columns: 1fr; }
            .member-booking-price { align-items: flex-start; flex-direction: column; gap: 4px; }
            .member-booking-actions { display: grid; }
            .member-booking-actions .btn { width: 100%; }
        }

        @media (prefers-reduced-motion: reduce) {
            .member-booking-page *, .member-booking-page *::before, .member-booking-page *::after { transition-duration: .01ms !important; }
        }
    </style>
@endsection

@section('content')
    <main class="member-booking-page" data-ui="settings">
        <header class="member-booking-header">
            <div>
                <h1>Catat booking member</h1>
                <p>Catat pemakaian pada satu tanggal jadwal rutin, sekaligus masukkan pembayaran yang benar-benar diterima.</p>
            </div>
            <a class="btn btn-outline-secondary" href="{{ route('admin.court-bookings.member-schedules.index') }}">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke jadwal member
            </a>
        </header>

        @if(session('success'))
            <div class="member-booking-notice" role="status">{{ session('success') }}</div>
        @endif

        <section class="member-booking-panel" aria-labelledby="memberOccurrenceHeading">
            <div class="member-booking-panel-heading">
                <h2 id="memberOccurrenceHeading">Informasi jadwal</h2>
            </div>
            <div class="member-booking-panel-body">
                <div class="member-booking-summary">
                    <div class="member-booking-fact"><span>Member / tim</span><strong>{{ $schedule->member_name }}</strong></div>
                    <div class="member-booking-fact"><span>Lapangan</span><strong>{{ $schedule->court->name }}</strong></div>
                    <div class="member-booking-fact"><span>Jadwal rutin</span><strong>{{ $weekdayLabel }} · {{ substr($schedule->time_start, 0, 5) }}–{{ substr($schedule->time_end, 0, 5) }}</strong></div>
                    <div class="member-booking-fact"><span>Masa berlaku jadwal</span><strong>{{ $schedule->starts_on?->translatedFormat('d M Y') ?? 'Tanpa batas awal' }} – {{ $schedule->ends_on?->translatedFormat('d M Y') ?? 'Tanpa batas akhir' }}</strong></div>
                </div>

                <div class="member-booking-section-heading">
                    <h3>Pilih tanggal pemakaian</h3>
                    <p>Pilih salah satu tanggal yang masuk ke periode jadwal rutin ini.</p>
                </div>
                <form id="memberBookingDateForm" method="GET" action="{{ route('admin.court-bookings.member-schedules.booking.create', $schedule) }}" class="member-booking-date-form">
                    <div>
                        <label class="form-label" for="memberBookingDate">Tanggal pemakaian</label>
                        <input class="form-control" id="memberBookingDate" name="date" type="date" value="{{ $date }}"
                            min="{{ $minimumDate }}" max="{{ $maximumDate }}" required>
                        <small class="member-booking-field-help">Bisa mencatat pemakaian hingga 30 hari ke belakang atau 60 hari ke depan.</small>
                    </div>
                    <div class="member-booking-date-actions">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-calendar2-check" aria-hidden="true"></i> Tampilkan tanggal
                        </button>
                    </div>
                </form>
                <div class="member-booking-date-pending" id="memberBookingDatePending" role="status" hidden>
                    Tanggal berubah. Tekan <strong>Tampilkan tanggal</strong> untuk memuat jadwal dan tarif sebelum menyimpan.
                </div>

                @if($dateError)
                    <div class="member-booking-alert mt-3" role="alert">{{ $dateError }}</div>
                @endif

                @if($booking)
                    @php
                        $received = (float) $booking->payments->where('status', \App\Models\CourtBookingPayment::STATUS_CONFIRMED)->sum(fn ($payment) => $payment->recognized_amount);
                        $balance = max(0, (float) $booking->amount - $received);
                    @endphp
                    <div class="member-booking-existing mt-3">
                        <div class="member-booking-notice" role="status">
                            <i class="bi bi-check-circle" aria-hidden="true"></i>
                            <span>Booking member untuk tanggal ini sudah tercatat. Tambahkan pembayaran melalui daftar booking.</span>
                        </div>
                        <dl>
                            <div><dt>Total tagihan</dt><dd>Rp {{ number_format((float) $booking->amount, 0, ',', '.') }}</dd></div>
                            <div><dt>Total diterima</dt><dd>Rp {{ number_format($received, 0, ',', '.') }}</dd></div>
                            <div><dt>Sisa tagihan</dt><dd>Rp {{ number_format($balance, 0, ',', '.') }}</dd></div>
                        </dl>
                        <a class="btn btn-primary align-self-start" href="{{ route('admin.court-bookings.index', ['date' => $booking->booking_date->toDateString()]) }}">
                            Buka booking dan catat pembayaran
                        </a>
                    </div>
                @elseif($occurrence && $amount !== null)
                    @if($isBackdated)
                        <div class="member-booking-notice member-booking-notice--warning mt-3" role="note">
                            <i class="bi bi-clock-history" aria-hidden="true"></i>
                            <span>Pencatatan tanggal lampau masuk ke audit. Jelaskan alasan pencatatan agar perubahan dapat ditelusuri.</span>
                        </div>
                    @endif

                    <form id="memberBookingForm" method="POST" action="{{ route('admin.court-bookings.member-schedules.booking.store', $schedule) }}" class="mt-4" data-applied-date="{{ $date }}">
                        @csrf
                        <input type="hidden" name="booking_date" value="{{ $date }}">
                        <input type="hidden" name="member_booking_request_key" value="{{ old('member_booking_request_key', $requestKey) }}">

                        <div class="member-booking-date-pending member-booking-date-confirmation" id="memberBookingDateConfirmation" role="note">
                            Tanggal yang akan dicatat: <strong>{{ \Carbon\CarbonImmutable::parse($date)->locale('id')->translatedFormat('l, d F Y') }}</strong>
                        </div>

                        <div class="member-booking-price">
                            <span>Total tagihan setelah diskon</span>
                            <strong id="memberBookingTotal" data-court-amount="{{ $amount }}">Rp {{ number_format($amount, 0, ',', '.') }}</strong>
                            <small class="d-block text-secondary mt-1" id="memberBookingPriceBreakdown">Tarif lapangan Rp {{ number_format($amount, 0, ',', '.') }} · diskon Rp 0</small>
                        </div>

                        <div class="member-booking-fields mt-3">
                            <div>
                                <label class="form-label" for="memberDiscountType">Diskon booking lapangan <span class="text-secondary">(opsional)</span></label>
                                <select class="form-select" id="memberDiscountType" name="discount_type">
                                    <option value="none" @selected(old('discount_type', 'none') === 'none')>Tanpa diskon</option>
                                    <option value="fixed" @selected(old('discount_type') === 'fixed')>Potongan nominal</option>
                                    <option value="percentage" @selected(old('discount_type') === 'percentage')>Potongan persentase</option>
                                </select>
                            </div>
                            <div id="memberDiscountValueField" hidden>
                                <label class="form-label" for="memberDiscountValue" id="memberDiscountValueLabel">Nominal diskon</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="memberDiscountPrefix">Rp</span>
                                    <input class="form-control" id="memberDiscountValue" name="discount_value" type="text" inputmode="decimal"
                                        autocomplete="off" value="{{ old('discount_value') }}" placeholder="Contoh: 50.000">
                                </div>
                                @error('discount_value') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div id="memberDiscountReasonField" hidden>
                                <label class="form-label" for="memberDiscountReason">Alasan diskon <span class="text-danger">*</span></label>
                                <input class="form-control" id="memberDiscountReason" name="discount_reason" maxlength="255"
                                    value="{{ old('discount_reason') }}" placeholder="Contoh: potongan khusus member">
                                @error('discount_reason') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <small class="member-booking-field-help">Diskon hanya mengurangi tarif lapangan. Alasan diskon disimpan bersama transaksi.</small>

                        <div class="member-booking-section-heading">
                            <h3>Catat pembayaran</h3>
                            <p>Masukkan nominal yang benar-benar diterima. Pembayaran tercatat masuk ke laporan pemasukan.</p>
                        </div>

                        <div class="member-booking-fields">
                            <div>
                                <label class="form-label" for="memberAmountReceivedDisplay">Nominal diterima</label>
                                <input class="form-control" id="memberAmountReceivedDisplay" type="text" inputmode="numeric"
                                    autocomplete="off" value="{{ old('amount_received', '0') }}" aria-describedby="memberAmountHelp">
                                <input id="memberAmountReceived" name="amount_received" type="hidden" value="{{ old('amount_received', '0') }}">
                                <small class="member-booking-field-help" id="memberAmountHelp">Isi 0 jika belum menerima pembayaran. Bisa dicatat DP atau lunas.</small>
                                @error('amount_received') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label" for="memberPaymentMethod">Metode pembayaran</label>
                                <select class="form-select" id="memberPaymentMethod" name="payment_method" required>
                                    <option value="none" @selected(old('payment_method', 'none') === 'none')>Belum dibayar</option>
                                    <option value="cash" @selected(old('payment_method') === 'cash')>Tunai</option>
                                    <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Transfer bank</option>
                                    <option value="qris" @selected(old('payment_method') === 'qris')>QRIS</option>
                                </select>
                                @error('payment_method') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label" for="memberPaymentReference">Referensi pembayaran</label>
                                <input class="form-control" id="memberPaymentReference" name="payment_reference" maxlength="100"
                                    value="{{ old('payment_reference') }}" placeholder="Opsional">
                            </div>
                            @if($isBackdated)
                                <div>
                                    <label class="form-label" for="memberBackdateReason">Alasan pencatatan tanggal lampau</label>
                                    <input class="form-control" id="memberBackdateReason" name="backdate_reason" maxlength="500"
                                        value="{{ old('backdate_reason') }}" required>
                                    @error('backdate_reason') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            @endif
                            <div class="grid-column-span-2">
                                <label class="form-label" for="memberBookingNote">Catatan admin</label>
                                <textarea class="form-control" id="memberBookingNote" name="admin_note" rows="2" maxlength="1000"
                                    placeholder="Opsional">{{ old('admin_note') }}</textarea>
                            </div>
                        </div>

                        <div class="member-booking-actions">
                            <a class="btn btn-outline-secondary" href="{{ route('admin.court-bookings.member-schedules.index') }}">Batal</a>
                            <button id="memberBookingSubmit" class="btn btn-primary" type="submit">
                                <i class="bi bi-check2" aria-hidden="true"></i> Simpan booking member
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </section>
    </main>

    <script>
        (() => {
            const dateForm = document.getElementById('memberBookingDateForm');
            const dateInput = document.getElementById('memberBookingDate');
            const pendingNotice = document.getElementById('memberBookingDatePending');
            const bookingForm = document.getElementById('memberBookingForm');
            const bookingSubmit = document.getElementById('memberBookingSubmit');

            if (dateForm && dateInput && pendingNotice && bookingForm && bookingSubmit) {
                const appliedDate = bookingForm.dataset.appliedDate;
                const syncDateState = () => {
                    const hasUnappliedDate = dateInput.value !== appliedDate;
                    pendingNotice.hidden = !hasUnappliedDate;
                    bookingSubmit.disabled = hasUnappliedDate;
                    bookingSubmit.setAttribute('aria-disabled', String(hasUnappliedDate));
                };

                dateInput.addEventListener('input', syncDateState);
                dateInput.addEventListener('change', syncDateState);
                bookingForm.addEventListener('submit', (event) => {
                    if (dateInput.value !== appliedDate) {
                        event.preventDefault();
                        pendingNotice.hidden = false;
                        dateForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
                syncDateState();
            }

            const display = document.getElementById('memberAmountReceivedDisplay');
            const raw = document.getElementById('memberAmountReceived');
            if (!display || !raw) return;

            const digits = (value) => value.replace(/\D/g, '');
            const format = (value) => {
                const amount = digits(value);
                if (!amount) return '';
                return 'Rp ' + Number(amount).toLocaleString('id-ID');
            };

            display.value = format(raw.value);
            display.addEventListener('input', () => {
                const amount = digits(display.value);
                raw.value = amount || '0';
                display.value = format(amount);
                display.setSelectionRange(display.value.length, display.value.length);
            });
            display.addEventListener('blur', () => {
                if (!digits(display.value)) {
                    raw.value = '0';
                    display.value = format('0');
                }
            });

            const bookingTotal = document.getElementById('memberBookingTotal');
            const breakdown = document.getElementById('memberBookingPriceBreakdown');
            const discountType = document.getElementById('memberDiscountType');
            const discountValue = document.getElementById('memberDiscountValue');
            const discountValueField = document.getElementById('memberDiscountValueField');
            const reasonField = document.getElementById('memberDiscountReasonField');
            const reason = document.getElementById('memberDiscountReason');
            const valueLabel = document.getElementById('memberDiscountValueLabel');
            const prefix = document.getElementById('memberDiscountPrefix');
            const money = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });

            const updateDiscount = () => {
                if (!bookingTotal || !discountType || !discountValue) return;
                const type = discountType.value;
                const active = type !== 'none';
                discountValueField.hidden = !active;
                reasonField.hidden = !active;
                const digitsOnly = discountValue.value.replace(/\D/g, '');
                const value = type === 'fixed'
                    ? Number(digitsOnly || 0)
                    : Number(discountValue.value.replace(',', '.').replace(/[^\d.]/g, '') || 0);
                const courtAmount = Number(bookingTotal.dataset.courtAmount || 0);
                const discount = type === 'fixed'
                    ? Math.min(courtAmount, value)
                    : type === 'percentage' ? courtAmount * Math.min(100, value) / 100 : 0;
                const finalAmount = Math.max(0, courtAmount - discount);
                bookingTotal.textContent = `Rp ${money.format(finalAmount)}`;
                breakdown.textContent = `Tarif lapangan Rp ${money.format(courtAmount)} · diskon Rp ${money.format(discount)}`;
                reason.required = active && value > 0;
                valueLabel.textContent = type === 'percentage' ? 'Persentase diskon' : 'Nominal diskon';
                prefix.textContent = type === 'percentage' ? '%' : 'Rp';
                discountValue.placeholder = type === 'percentage' ? 'Contoh: 10' : 'Contoh: 50.000';
            };

            discountType?.addEventListener('change', updateDiscount);
            discountValue?.addEventListener('input', () => {
                if (discountType.value === 'fixed') {
                    const value = discountValue.value.replace(/\D/g, '').replace(/^0+(?=\d)/, '');
                    discountValue.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }
                updateDiscount();
            });
            updateDiscount();
        })();
    </script>
@endsection
