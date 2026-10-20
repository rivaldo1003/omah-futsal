@extends('layouts.admin')

@section('title', 'Tambah Perlengkapan Booking')

@section('styles')
<style>
    .rental-addition-page {
        --addition-ink: #111111;
        --addition-charcoal: #39393b;
        --addition-muted: #707072;
        --addition-border: #cacacb;
        --addition-border-soft: #e5e5e5;
        --addition-cloud: #f5f5f5;
        max-width: 1440px;
        margin-inline: auto;
        color: var(--addition-ink);
        line-height: 1.5;
    }

    .rental-addition-page .addition-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .rental-addition-page h1 {
        margin: 0 0 6px;
        color: var(--addition-ink);
        font-size: clamp(1.75rem, 2.5vw, 2rem);
        font-weight: 500;
        line-height: 1.2;
    }

    .rental-addition-page h2 {
        color: var(--addition-ink);
        font-size: 18px;
        font-weight: 500;
    }

    .rental-addition-page .text-secondary,
    .addition-summary-label {
        color: var(--addition-muted) !important;
    }

    .rental-addition-page .card {
        overflow: hidden;
        border: 1px solid var(--addition-border-soft);
        border-radius: 8px;
        background: #ffffff;
        box-shadow: none;
    }

    .rental-addition-page .card-header {
        padding: 18px 24px;
        border-bottom-color: var(--addition-border-soft);
        background: #ffffff;
    }

    .rental-addition-page .card-body {
        padding: 24px;
    }

    .rental-addition-page .btn {
        min-height: 44px;
        padding: 9px 20px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 500;
    }

    .rental-addition-page .btn-primary {
        border-color: var(--addition-ink);
        background: var(--addition-ink);
        color: #ffffff;
    }

    .rental-addition-page .btn-primary:hover {
        border-color: var(--addition-charcoal);
        background: var(--addition-charcoal);
        color: #ffffff;
    }

    .rental-addition-page .btn-outline-secondary {
        border-color: transparent;
        background: var(--addition-cloud);
        color: var(--addition-ink);
    }

    .rental-addition-page .btn-outline-secondary:hover {
        border-color: transparent;
        background: #e5e5e5;
        color: var(--addition-ink);
    }

    .rental-addition-page :is(a, button):focus-visible {
        outline: 2px solid var(--addition-ink);
        outline-offset: 2px;
    }

    .addition-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0;
    }

    .addition-summary-item {
        min-width: 0;
        padding: 2px 20px;
        border-left: 1px solid var(--addition-border-soft);
    }

    .addition-summary-item:first-child {
        padding-left: 0;
        border-left: 0;
    }

    .addition-summary-item:last-child {
        padding-right: 0;
    }

    .addition-summary-label {
        display: block;
        margin-bottom: 4px;
        font-size: 13px;
    }

    .addition-summary-value {
        display: block;
        overflow-wrap: anywhere;
        color: var(--addition-ink);
        font-size: 15px;
        font-weight: 600;
    }

    .addition-items-table {
        margin: 0;
        color: var(--addition-ink);
        --bs-table-bg: transparent;
    }

    .addition-items-table thead th {
        padding: 12px 14px;
        border-bottom: 1px solid var(--addition-border-soft);
        color: var(--addition-muted);
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
    }

    .addition-items-table tbody td {
        padding: 16px 14px;
        border-bottom: 1px solid var(--addition-border-soft);
        vertical-align: middle;
    }

    .addition-items-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .addition-items-table .addition-quantity-cell {
        width: 150px;
    }

    .rental-addition-page .form-control {
        min-height: 44px;
        border: 1px solid var(--addition-border);
        border-radius: 8px;
        color: var(--addition-ink);
        box-shadow: none;
    }

    .rental-addition-page .form-control:focus,
    .rental-addition-page .form-control:focus-visible {
        border: 2px solid var(--addition-ink);
        outline: none;
        box-shadow: none;
    }

    .addition-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin: 20px 0 28px;
        padding: 16px 18px;
        border: 1px solid var(--addition-border-soft);
        border-radius: 8px;
        background: var(--addition-cloud);
    }

    .addition-total strong {
        color: var(--addition-ink);
        font-size: 22px;
        font-variant-numeric: tabular-nums;
    }

    .addition-payment-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr));
        gap: 10px;
    }

    .addition-payment-option {
        display: flex;
        min-height: 82px;
        align-items: flex-start;
        gap: 10px;
        padding: 14px;
        border: 1px solid var(--addition-border-soft);
        border-radius: 8px;
        background: #ffffff;
        cursor: pointer;
    }

    .addition-payment-option:has(input:checked) {
        border-color: var(--addition-ink);
        background: var(--addition-cloud);
    }

    .addition-payment-option .form-check-input {
        flex: 0 0 auto;
        margin-top: 3px;
        accent-color: var(--addition-ink);
    }

    .rental-addition-page .form-check-input:checked {
        border-color: var(--addition-ink);
        background-color: var(--addition-ink);
    }

    .rental-addition-page .form-check-input:focus {
        border-color: var(--addition-ink);
        box-shadow: none;
    }

    .rental-addition-page .form-check-input:focus-visible {
        outline: 2px solid var(--addition-ink);
        outline-offset: 2px;
    }

    .addition-payment-option strong {
        display: block;
        color: var(--addition-ink);
        font-size: 14px;
        font-weight: 600;
    }

    .addition-payment-option small {
        display: block;
        margin-top: 3px;
        color: var(--addition-muted);
        font-size: 12px;
        line-height: 1.45;
    }

    .addition-payment-note {
        max-width: 86ch;
        color: var(--addition-muted);
        font-size: 13px;
    }

    @media (max-width: 767.98px) {
        .addition-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px 0;
        }

        .addition-summary-item:nth-child(odd) {
            padding-left: 0;
            border-left: 0;
        }

        .addition-summary-item:nth-child(even) {
            padding-right: 0;
        }

        .addition-payment-options {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575.98px) {
        .rental-addition-page .addition-heading {
            align-items: stretch;
            flex-direction: column;
        }

        .rental-addition-page .addition-heading .btn {
            align-self: flex-start;
        }

        .rental-addition-page .card-header,
        .rental-addition-page .card-body {
            padding: 18px 16px;
        }

        .addition-summary-item {
            padding-inline: 12px;
        }

        .addition-items-table {
            min-width: 620px;
        }

        .addition-total {
            align-items: flex-start;
            flex-direction: column;
            gap: 4px;
        }
    }
</style>
@endsection

@section('content')
<main class="rental-addition-page">
    <header class="addition-heading">
        <div>
            <h1>Tambah perlengkapan</h1>
            <p class="text-secondary mb-0">Tambahan dicatat sebagai transaksi baru. Pembayaran booking awal tidak berubah.</p>
        </div>
        <a href="{{ route('admin.court-bookings.index', ['date' => $booking->booking_date->toDateString()]) }}"
            class="btn btn-outline-secondary">Kembali</a>
    </header>

@if($errors->any())
    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
@endif

<div class="card mb-3">
    <div class="card-header">
        <h2 class="mb-0">Jadwal terkonfirmasi</h2>
    </div>
    <div class="card-body">
        <div class="addition-summary-grid">
            <div class="addition-summary-item">
                <span class="addition-summary-label">Pelanggan</span>
                <strong class="addition-summary-value">{{ $booking->customer_name }}</strong>
            </div>
            <div class="addition-summary-item">
                <span class="addition-summary-label">Lapangan</span>
                <strong class="addition-summary-value">{{ $booking->court?->name ?? 'Belum tersedia' }}</strong>
            </div>
            <div class="addition-summary-item">
                <span class="addition-summary-label">Tanggal dan waktu</span>
                <strong class="addition-summary-value">{{ $booking->booking_date->translatedFormat('d F Y') }} · {{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }}</strong>
            </div>
            <div class="addition-summary-item">
                <span class="addition-summary-label">Kode booking</span>
                <strong class="addition-summary-value">#{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</strong>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.court-bookings.rental-additions.store', $booking) }}" class="card"
    id="rentalAdditionForm">
    @csrf
    <input type="hidden" name="request_key" value="{{ $requestKey }}">
    <div class="card-header">
        <h2 class="mb-0">Pilih barang dan jumlah</h2>
    </div>
    <div class="card-body">
        @if($items->isEmpty())
            <div class="alert alert-info mb-0">Belum ada perlengkapan aktif dengan harga yang dapat disewakan.</div>
        @else
        <div class="table-responsive">
            <table class="table align-middle addition-items-table">
                <thead>
                    <tr>
                        <th>Perlengkapan</th>
                        <th>Harga / item</th>
                        <th>Stok tersedia untuk jadwal ini</th>
                        <th class="addition-quantity-cell">Jumlah tambahan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    @php($available = (int) ($availableStock[$item->id] ?? 0))
                    <tr>
                        <td class="fw-semibold">{{ $item->name }}</td>
                        <td>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                        <td>{{ $available }} unit</td>
                        <td class="addition-quantity-cell"><input class="form-control rental-addition-quantity" type="number"
                                name="rental_items[{{ $item->id }}]" min="0" max="{{ $available }}" value="0"
                                data-price="{{ (float) $item->unit_price }}" {{ $available < 1 ? 'disabled' : '' }}
                                aria-label="Jumlah {{ $item->name }}"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="addition-total">
            <span class="text-secondary">Total transaksi tambahan</span>
            <strong class="fs-5" id="rentalAdditionTotal" aria-live="polite">Rp 0</strong>
        </div>

        <fieldset class="mb-3">
            <legend class="form-label fw-semibold">Pembayaran tambahan</legend>
            <div class="addition-payment-options">
                <label class="addition-payment-option">
                    <input class="form-check-input mt-1" type="radio" name="payment_method" value="cash" required>
                    <span><strong>Tunai diterima sekarang</strong><small class="d-block text-secondary">Pilih hanya
                            setelah uang tambahan diterima langsung.</small></span>
                </label>
                @if($settings->hasBankTransfer())
                    <label class="addition-payment-option">
                        <input class="form-check-input mt-1" type="radio" name="payment_method" value="bank_transfer"
                            required>
                        <span><strong>Transfer bank</strong><small class="d-block text-secondary">Pelanggan akan melihat
                                instruksi dan mengunggah bukti di halaman booking.</small></span>
                    </label>
                @endif
                @if($settings->hasQris())
                    <label class="addition-payment-option">
                        <input class="form-check-input mt-1" type="radio" name="payment_method" value="qris" required>
                        <span><strong>QRIS</strong><small class="d-block text-secondary">Pelanggan akan melihat QR dan
                                mengunggah bukti di halaman booking.</small></span>
                    </label>
                @endif
            </div>
        </fieldset>

        <div class="form-check mb-3" id="paymentReceivedWrap" hidden>
            <input class="form-check-input" type="checkbox" name="payment_received" value="1" id="paymentReceived">
            <label class="form-check-label" for="paymentReceived">Saya sudah menerima pembayaran tunai sesuai total
                tambahan.</label>
        </div>
        <div class="addition-payment-note mb-3">Jika memilih transfer atau QRIS, stok ditahan maksimal {{ $holdMinutes }}
            menit atau sampai jadwal booking dimulai, mana yang lebih dulu. Jika tambahan kedaluwarsa, booking lapangan
            tetap terkonfirmasi.</div>
        <button class="btn btn-primary" type="submit"><i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Simpan
            tambahan</button>
        @endif
    </div>
</form>

<script>
    (() => {
        const form = document.getElementById('rentalAdditionForm');
        if (!form) return;
        const quantities = [...form.querySelectorAll('.rental-addition-quantity')];
        const total = document.getElementById('rentalAdditionTotal');
        const cashWrap = document.getElementById('paymentReceivedWrap');
        const cashCheck = document.getElementById('paymentReceived');
        const format = value => new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value);
        const updateTotal = () => {
            const amount = quantities.reduce((sum, input) => sum + Math.max(0, Number(input.value) || 0) * Number(input.dataset.price || 0), 0);
            total.textContent = `Rp ${format(amount)}`;
        };
        quantities.forEach(input => input.addEventListener('input', updateTotal));
        form.querySelectorAll('[name="payment_method"]').forEach(input => input.addEventListener('change', () => {
            const isCash = input.checked && input.value === 'cash';
            if (input.checked) {
                cashWrap.hidden = !isCash;
                cashCheck.required = isCash;
                if (!isCash) cashCheck.checked = false;
            }
        }));
        form.addEventListener('submit', event => {
            if (quantities.every(input => Number(input.value) < 1)) {
                event.preventDefault();
                alert('Pilih setidaknya satu perlengkapan.');
            }
        });
    })();
</script>
</main>
@endsection
