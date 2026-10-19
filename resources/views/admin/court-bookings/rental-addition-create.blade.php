@extends('layouts.admin')

@section('title', 'Tambah Perlengkapan Booking')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Tambah perlengkapan</h1>
            <p class="text-secondary mb-0">Tambahan dicatat sebagai transaksi baru. Pembayaran booking awal tidak berubah.</p>
        </div>
        <a href="{{ route('admin.court-bookings.index', ['date' => $booking->booking_date->toDateString()]) }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-header"><h2 class="h6 mb-0">Jadwal terkonfirmasi</h2></div>
        <div class="card-body d-flex flex-wrap gap-4">
            <div><span class="small text-secondary d-block">Pelanggan</span><strong>{{ $booking->customer_name }}</strong></div>
            <div><span class="small text-secondary d-block">Lapangan</span><strong>{{ $booking->court?->name ?? '—' }}</strong></div>
            <div><span class="small text-secondary d-block">Tanggal dan waktu</span><strong>{{ $booking->booking_date->translatedFormat('d F Y') }} · {{ substr($booking->time_start, 0, 5) }}–{{ substr($booking->time_end, 0, 5) }}</strong></div>
            <div><span class="small text-secondary d-block">Kode</span><strong>#{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}</strong></div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.court-bookings.rental-additions.store', $booking) }}" class="card" id="rentalAdditionForm">
        @csrf
        <input type="hidden" name="request_key" value="{{ $requestKey }}">
        <div class="card-header"><h2 class="h6 mb-0">Pilih barang dan jumlah</h2></div>
        <div class="card-body">
            @if($items->isEmpty())
                <div class="alert alert-info mb-0">Belum ada perlengkapan aktif dengan harga yang dapat disewakan.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Perlengkapan</th><th>Harga / item</th><th>Stok tersedia untuk jadwal ini</th><th style="width:150px">Jumlah tambahan</th></tr></thead>
                        <tbody>
                            @foreach($items as $item)
                                @php($available = (int) ($availableStock[$item->id] ?? 0))
                                <tr>
                                    <td class="fw-semibold">{{ $item->name }}</td>
                                    <td>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                                    <td>{{ $available }} unit</td>
                                    <td><input class="form-control rental-addition-quantity" type="number" name="rental_items[{{ $item->id }}]" min="0" max="{{ $available }}" value="0" data-price="{{ (float) $item->unit_price }}" {{ $available < 1 ? 'disabled' : '' }} aria-label="Jumlah {{ $item->name }}"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 p-3 rounded bg-light border mb-4">
                    <span class="text-secondary">Total transaksi tambahan</span>
                    <strong class="fs-5" id="rentalAdditionTotal">Rp 0</strong>
                </div>

                <fieldset class="mb-3">
                    <legend class="form-label fw-semibold">Pembayaran tambahan</legend>
                    <div class="d-grid gap-2">
                        <label class="border rounded p-3 d-flex gap-2 align-items-start">
                            <input class="form-check-input mt-1" type="radio" name="payment_method" value="cash" required>
                            <span><strong>Tunai diterima sekarang</strong><small class="d-block text-secondary">Pilih hanya setelah uang tambahan diterima langsung.</small></span>
                        </label>
                        @if($settings->hasBankTransfer())
                            <label class="border rounded p-3 d-flex gap-2 align-items-start">
                                <input class="form-check-input mt-1" type="radio" name="payment_method" value="bank_transfer" required>
                                <span><strong>Transfer bank</strong><small class="d-block text-secondary">Pelanggan akan melihat instruksi dan mengunggah bukti di halaman booking.</small></span>
                            </label>
                        @endif
                        @if($settings->hasQris())
                            <label class="border rounded p-3 d-flex gap-2 align-items-start">
                                <input class="form-check-input mt-1" type="radio" name="payment_method" value="qris" required>
                                <span><strong>QRIS</strong><small class="d-block text-secondary">Pelanggan akan melihat QR dan mengunggah bukti di halaman booking.</small></span>
                            </label>
                        @endif
                    </div>
                </fieldset>

                <div class="form-check mb-3" id="paymentReceivedWrap" hidden>
                    <input class="form-check-input" type="checkbox" name="payment_received" value="1" id="paymentReceived">
                    <label class="form-check-label" for="paymentReceived">Saya sudah menerima pembayaran tunai sesuai total tambahan.</label>
                </div>
                <div class="small text-secondary mb-3">Jika memilih transfer atau QRIS, stok ditahan maksimal {{ $holdMinutes }} menit atau sampai jadwal booking dimulai, mana yang lebih dulu. Jika tambahan kedaluwarsa, booking lapangan tetap terkonfirmasi.</div>
                <button class="btn btn-primary" type="submit"><i class="bi bi-plus-circle me-1" aria-hidden="true"></i>Simpan tambahan</button>
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
@endsection
