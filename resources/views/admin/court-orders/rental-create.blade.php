@extends('layouts.admin')

@section('title', 'Buat Sewa Perlengkapan Walk-in')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="mb-1">Buat sewa perlengkapan walk-in</h1>
            <p class="text-secondary mb-0">Catat pelanggan yang menyewa perlengkapan langsung di lokasi tanpa booking lapangan.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.court-bookings.manual.create') }}" class="btn btn-outline-primary"><i class="bi bi-calendar-plus me-1" aria-hidden="true"></i>Booking lapangan + perlengkapan</a>
            <a href="{{ route('admin.court-rentals.index') }}" class="btn btn-outline-secondary">Kembali ke pesanan sewa</a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif

    <form method="GET" action="{{ route('admin.court-rentals.manual.create') }}" class="card mb-4">
        <div class="card-header"><h5>Jadwal perlengkapan</h5></div>
        <div class="card-body">
            <input type="hidden" name="manual_request_key" value="{{ $requestKey }}">
            <div class="d-flex flex-wrap gap-3 mb-3" role="radiogroup" aria-label="Cara mulai sewa">
                <label class="form-check border rounded px-3 py-2 d-flex align-items-center gap-2">
                    <input class="form-check-input mt-0" type="radio" name="mode" value="now" @checked($mode === 'now')>
                    <span><strong>Pakai sekarang</strong><small class="d-block text-secondary">Ambil hari ini</small></span>
                </label>
                <label class="form-check border rounded px-3 py-2 d-flex align-items-center gap-2">
                    <input class="form-check-input mt-0" type="radio" name="mode" value="schedule" @checked($mode === 'schedule')>
                    <span><strong>Jadwalkan</strong><small class="d-block text-secondary">Pilih tanggal dan jam</small></span>
                </label>
            </div>
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4" id="walkInScheduleDateField" @if($mode === 'now') hidden @endif>
                    <label class="form-label" for="walkInRentalDate">Tanggal pengambilan</label>
                    <input class="form-control" type="date" id="walkInRentalDate" name="date" value="{{ $date }}" min="{{ $todayDate }}" max="{{ $maxDate }}" @disabled($mode === 'now')>
                </div>
                <input type="hidden" name="date" id="walkInRentalNowDate" value="{{ $todayDate }}" @disabled($mode !== 'now')>
                <div class="col-12 col-md-3" id="walkInScheduleStartField" @if($mode === 'now') hidden @endif>
                    <label class="form-label" for="walkInRentalStart">Waktu pengambilan</label>
                    <input class="form-control" type="time" id="walkInRentalStart" name="time_start" value="{{ $mode === 'schedule' ? $timeStart : '' }}" min="{{ $openingTime }}" max="{{ $closingTime }}" @disabled($mode === 'now')>
                </div>
                <input type="hidden" name="time_start" id="walkInRentalNowStart" value="{{ $mode === 'now' ? $timeStart : '' }}" @disabled($mode !== 'now')>
                <div class="col-12 col-md-3" id="walkInNowStartNote" @if($mode !== 'now') hidden @endif>
                    <label class="form-label">Waktu pengambilan</label>
                    <div class="form-control">Hari ini, {{ $timeStart }} WIB</div>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label" for="walkInRentalDuration">Durasi sewa</label>
                    <select class="form-select" id="walkInRentalDuration" name="duration_minutes" required>
                        @foreach($durationOptions as $minutes => $label)
                            <option value="{{ $minutes }}" @selected($durationMinutes === $minutes)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search me-1" aria-hidden="true"></i>Cek stok</button>
                </div>
            </div>
            <div class="form-text mt-2">Jam operasional {{ $openingTime }}–{{ $closingTime }} WIB. Harga berlaku per item per sesi; durasi dipakai untuk menghitung jadwal dan stok.</div>
            @if($periodSelected)
                <div class="mt-3 fw-semibold">Ambil {{ $mode === 'now' ? 'sekarang, '.$timeStart : \Carbon\Carbon::parse($date)->translatedFormat('d M Y').', '.$timeStart }} WIB <span class="text-secondary fw-normal">· {{ $durationOptions[$durationMinutes] ?? '' }} · kembali sekitar {{ $timeEnd }} WIB</span></div>
            @endif
        </div>
    </form>

    @if($periodError)
        <div class="alert alert-warning">{{ $periodError }}</div>
    @endif

    @if($periodSelected)
        <div class="alert alert-light border d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div><strong>{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</strong> · {{ $timeStart }}–{{ $timeEnd }} WIB</div>
            <span class="small text-secondary">Stok dihitung ulang saat pesanan disimpan</span>
        </div>

        @if($rentalItems->isEmpty())
            <div class="alert alert-info">Belum ada perlengkapan aktif dengan harga dan stok. Atur katalog terlebih dahulu.</div>
        @else
            <form method="POST" action="{{ route('admin.court-rentals.manual.store') }}" id="walkInRentalForm">
                @csrf
                <input type="hidden" name="manual_request_key" value="{{ old('manual_request_key', $requestKey) }}">
                <input type="hidden" name="mode" value="{{ $mode }}">
                <input type="hidden" name="rental_date" value="{{ $date }}">
                <input type="hidden" name="time_start" value="{{ $timeStart }}">
                <input type="hidden" name="duration_minutes" value="{{ $durationMinutes }}">

                <section class="card mb-4" aria-labelledby="walkInRentalItemsTitle">
                    <div class="card-header"><h5 id="walkInRentalItemsTitle">Perlengkapan yang disewa</h5></div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>Perlengkapan</th><th>Harga per item</th><th>Stok tersedia</th><th style="width:140px">Jumlah</th></tr></thead>
                            <tbody>
                                @foreach($rentalItems as $item)
                                    @php($stock = (int) ($availableStock[$item->id] ?? 0))
                                    @php($selectedQuantity = (int) old('rental_items.'.$item->id, $selectedRentalQuantities[$item->id] ?? 0))
                                    <tr>
                                        <th scope="row" class="fw-normal">{{ $item->name }}</th>
                                        <td>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                                        <td>{{ $stock }} unit</td>
                                        <td><label class="visually-hidden" for="walkInRental{{ $item->id }}">Jumlah {{ $item->name }}</label><input class="form-control form-control-sm walk-in-rental-quantity" id="walkInRental{{ $item->id }}" type="number" name="rental_items[{{ $item->id }}]" min="0" max="{{ $stock }}" value="{{ min($selectedQuantity, $stock) }}" data-price="{{ (float) $item->unit_price }}" @disabled($stock < 1)></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card mb-4" aria-labelledby="walkInRentalCustomerTitle">
                    <div class="card-header"><h5 id="walkInRentalCustomerTitle">Data pelanggan dan pembayaran</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="walkInRentalName">Nama pelanggan</label>
                                <input class="form-control" id="walkInRentalName" name="customer_name" maxlength="120" autocomplete="name" value="{{ old('customer_name') }}" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="walkInRentalPhone">Nomor WhatsApp</label>
                                <input class="form-control" id="walkInRentalPhone" name="customer_phone" maxlength="30" autocomplete="tel" inputmode="tel" value="{{ old('customer_phone') }}" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="walkInRentalEmail">Email <span class="text-secondary">(opsional)</span></label>
                                <input class="form-control" id="walkInRentalEmail" type="email" name="customer_email" maxlength="255" autocomplete="email" value="{{ old('customer_email') }}">
                            </div>
                            <div class="col-12 col-md-5">
                                <label class="form-label" for="walkInRentalPayment">Pembayaran yang sudah diterima</label>
                                <select class="form-select" id="walkInRentalPayment" name="payment_method" required>
                                    <option value="cash" @selected(old('payment_method', 'cash') === 'cash')>Tunai</option>
                                    <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Transfer bank</option>
                                    <option value="qris" @selected(old('payment_method') === 'qris')>QRIS</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-7">
                                <label class="form-label" for="walkInRentalReference">Nomor referensi pembayaran <span class="text-secondary">(opsional)</span></label>
                                <input class="form-control" id="walkInRentalReference" name="payment_reference" maxlength="100" value="{{ old('payment_reference') }}">
                            </div>
                        </div>
                        <div class="form-text mt-3">Pesanan manual langsung tersimpan sebagai terkonfirmasi. Pastikan uang atau pembayaran digital sudah diterima sebelum menyimpan.</div>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="walkInRentalPaymentReceived" name="payment_received" value="1" @checked(old('payment_received')) required>
                            <label class="form-check-label" for="walkInRentalPaymentReceived">Saya sudah menerima dan mencocokkan pembayaran sebesar total transaksi.</label>
                        </div>
                    </div>
                </section>

                <section class="card mb-4">
                    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div><h5 class="mb-1">Total yang diterima</h5><span class="small text-secondary">Harga perlengkapan yang dipilih</span></div>
                        <strong class="fs-4" id="walkInRentalTotal">Rp 0</strong>
                    </div>
                </section>

                <div class="card mb-4"><div class="card-body">
                    <label class="form-label" for="walkInRentalNote">Catatan admin <span class="text-secondary">(opsional)</span></label>
                    <textarea class="form-control" id="walkInRentalNote" name="admin_note" rows="2" maxlength="1000">{{ old('admin_note') }}</textarea>
                </div></div>

                <div class="d-flex flex-wrap justify-content-end gap-2">
                    <a class="btn btn-outline-secondary" href="{{ route('admin.court-rentals.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle me-1" aria-hidden="true"></i>Simpan pesanan lunas</button>
                </div>
            </form>
        @endif
    @endif
@endsection

@section('scripts')
    <script>
        (() => {
            const availability = document.querySelector('form[action="{{ route('admin.court-rentals.manual.create') }}"]');
            if (availability) {
                const modes = [...availability.querySelectorAll('input[name="mode"]')];
                const date = document.getElementById('walkInRentalDate');
                const nowDate = document.getElementById('walkInRentalNowDate');
                const start = document.getElementById('walkInRentalStart');
                const nowStart = document.getElementById('walkInRentalNowStart');
                const updateMode = () => {
                    const isNow = modes.find((input) => input.checked)?.value === 'now';
                    document.getElementById('walkInScheduleDateField').hidden = isNow;
                    document.getElementById('walkInScheduleStartField').hidden = isNow;
                    document.getElementById('walkInNowStartNote').hidden = !isNow;
                    date.disabled = isNow;
                    start.disabled = isNow;
                    date.required = !isNow;
                    start.required = !isNow;
                    nowDate.disabled = !isNow;
                    nowStart.disabled = !isNow;
                };
                modes.forEach((input) => input.addEventListener('change', updateMode));
                availability.addEventListener('submit', () => {
                    availability.querySelectorAll('[data-preserved-rental]').forEach((input) => input.remove());
                    document.querySelectorAll('#walkInRentalForm .walk-in-rental-quantity').forEach((quantity) => {
                        if (quantity.disabled) return;
                        const preserved = document.createElement('input');
                        preserved.type = 'hidden';
                        preserved.name = quantity.name;
                        preserved.value = quantity.value || '0';
                        preserved.dataset.preservedRental = 'true';
                        availability.append(preserved);
                    });
                });
                updateMode();
            }

            const form = document.getElementById('walkInRentalForm');
            const total = document.getElementById('walkInRentalTotal');
            if (!form || !total) return;

            const money = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 });
            const updateTotal = () => {
                const amount = [...form.querySelectorAll('.walk-in-rental-quantity')].reduce((sum, input) => {
                    const maximum = Number(input.max || 0);
                    const quantity = Math.min(maximum, Math.max(0, Number.parseInt(input.value || '0', 10) || 0));
                    input.value = String(quantity);
                    return sum + quantity * Number(input.dataset.price || 0);
                }, 0);
                total.textContent = `Rp ${money.format(amount)}`;
            };

            form.querySelectorAll('.walk-in-rental-quantity').forEach((input) => input.addEventListener('input', updateTotal));
            updateTotal();
        })();
    </script>
@endsection
