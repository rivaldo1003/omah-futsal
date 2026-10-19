@extends('layouts.admin')

@section('title', 'Riwayat Sewa Perlengkapan')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="mb-1">Riwayat sewa perlengkapan lama</h1>
            <p class="text-secondary mb-0">Pesanan perlengkapan baru hanya dibuat sebagai tambahan booking lapangan dan dikelola pada halaman booking.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.court-bookings.index') }}" class="btn btn-outline-secondary"><i class="bi bi-calendar-check me-1" aria-hidden="true"></i>Booking lapangan</a>
            <a href="{{ route('admin.court-rentals.settings') }}" class="btn btn-primary"><i class="bi bi-box-seam me-1"
                    aria-hidden="true"></i>Atur katalog dan stok</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>@endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body"><span class="small text-secondary">Perlu verifikasi</span>
                    <div class="fs-3 fw-bold">{{ number_format($counts['pending_review']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body"><span class="small text-secondary">Menunggu pembayaran</span>
                    <div class="fs-3 fw-bold">{{ number_format($counts['awaiting_payment']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body"><span class="small text-secondary">Terkonfirmasi</span>
                    <div class="fs-3 fw-bold">{{ number_format($counts['confirmed']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.court-rentals.index') }}" class="card mb-3">
        <div class="card-body d-flex flex-wrap align-items-end gap-3">
            <div><label class="form-label" for="rentalFilterDate">Tanggal sewa</label><input class="form-control"
                    type="date" id="rentalFilterDate" name="date" value="{{ $filters['date'] ?? '' }}"></div>
            <div><label class="form-label" for="rentalFilterStatus">Status</label><select class="form-select"
                    id="rentalFilterStatus" name="status">
                    <option value="">Semua status</option>
                    @foreach([
                            'awaiting_payment' => 'Menunggu pembayaran',
                            'pending_review' => 'Menunggu verifikasi',
                            'confirmed' => 'Terkonfirmasi',
                            'rejected' => 'Ditolak',
                            'cancelled' => 'Dibatalkan',
                            'expired' => 'Kedaluwarsa',
                        ] as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select></div>
            <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"
                    aria-hidden="true"></i>Filter</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.court-rentals.index') }}">Reset</a>
        </div>
    </form>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gap-2">
            <h5>Pesanan perlengkapan</h5><span class="small text-secondary">{{ $orders->total() }} data</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Waktu sewa</th>
                        <th>Pemesan</th>
                        <th>Perlengkapan</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th class="text-end">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $waPhone = preg_replace('/\D+/', '', $order->customer_phone);
                            $waPhone = str_starts_with($waPhone, '0') ? '62' . substr($waPhone, 1) : (str_starts_with($waPhone, '8') ? '62' . $waPhone : $waPhone);
                            $badgeClass = match ($order->status) {
                                'confirmed' => 'text-bg-success', 'pending_review' => 'text-bg-warning',
                                'awaiting_payment' => 'text-bg-info', 'rejected', 'cancelled' => 'text-bg-danger', default => 'text-bg-secondary',
                            };
                            $orderCode = str_pad((string) $order->id, 6, '0', STR_PAD_LEFT);
                            $orderUrl = route('rental.show', $order->public_token);
                            $waLines = [
                                '*OFS FUTSAL CENTER*',
                                '*PESANAN SEWA PERLENGKAPAN*',
                                '',
                                'Halo ' . $order->customer_name . ',',
                                $order->status === 'confirmed' ? 'Pembayaran Anda telah diverifikasi. Pesanan perlengkapan sudah dikonfirmasi.' : 'Berikut informasi terbaru mengenai pesanan perlengkapan Anda.',
                                '',
                                '*DETAIL PESANAN*',
                                '*Kode:* #' . $orderCode,
                                '*Status:* ' . $order->status_label,
                                '*Tanggal:* ' . $order->rental_date->translatedFormat('l, d F Y'),
                                '*Waktu:* ' . substr($order->time_start, 0, 5) . '–' . substr($order->time_end, 0, 5) . ' WIB',
                                '*Durasi:* ' . $order->duration_label,
                            ];
                            foreach ($order->lines as $line)
                                $waLines[] = $line->item_name_snapshot . ' × ' . $line->quantity;
                            $waLines[] = '*Total:* Rp ' . number_format((float) $order->amount, 0, ',', '.');
                            if ($order->admin_note && in_array($order->status, ['rejected', 'cancelled'], true))
                                $waLines[] = '*Catatan:* ' . $order->admin_note;
                            $waLines = array_merge($waLines, ['', '*Status dan detail pesanan:*', $orderUrl, '', 'Terima kasih telah memilih OFS Futsal Center.']);
                        @endphp
                        <tr>
                            <td style="min-width:185px">
                                <div class="fw-semibold">{{ $order->rental_date->format('d M Y') }}</div>
                                <div>{{ substr($order->time_start, 0, 5) }}–{{ substr($order->time_end, 0, 5) }} WIB</div>
                                <small class="text-secondary">{{ $order->duration_label }}</small><br><small
                                    class="text-secondary">#{{ $orderCode }}</small>
                                @if($order->source === 'walk_in')
                                    <div><span class="badge text-bg-light border">Walk-in</span><small class="text-secondary ms-1">{{ $order->createdBy?->name }}</small></div>
                                @endif
                            </td>
                            <td style="min-width:170px">
                                <div class="fw-semibold">{{ $order->customer_name }}</div><a href="https://wa.me/{{ $waPhone }}"
                                    target="_blank" rel="noopener">{{ $order->customer_phone }}</a>@if($order->customer_email)
                                    <div class="small text-secondary">{{ $order->customer_email }}</div>@endif
                            </td>
                            <td style="min-width:190px">@foreach($order->lines as $line)
                                <div>{{ $line->item_name_snapshot }} <span class="badge bg-light text-dark border">× {{ $line->quantity }}</span></div><small
                                    class="text-secondary">Rp
                            {{ number_format((float) $line->line_total, 0, ',', '.') }}</small>@endforeach
                            </td>
                            <td style="min-width:175px">
                                <div class="fw-semibold">Rp {{ number_format((float) $order->amount, 0, ',', '.') }}</div>
                                <div class="small text-secondary">{{ $order->payment_method_label }}</div>
                                @if($order->payment_reference)
                                <div class="small">Ref: {{ $order->payment_reference }}</div>@endif
                                @if($order->payment_proof_path)<a class="btn btn-sm btn-outline-primary mt-1"
                                    href="{{ route('admin.court-rentals.proof', $order) }}" target="_blank" rel="noopener"><i
                                class="bi bi-eye me-1" aria-hidden="true"></i>Pratinjau bukti</a>@endif
                            </td>
                            <td><span class="badge {{ $badgeClass }}">{{ $order->status_label }}</span>@if($order->cancelledBy)
                                <div class="small text-secondary mt-1">Dibatalkan oleh {{ $order->cancelledBy->name }}</div>
                            @elseif($order->verifiedBy)
                                <div class="small text-secondary mt-1">Diperiksa {{ $order->verifiedBy->name }}</div>@endif
                                @if($order->status === 'confirmed')
                                    @if($order->rental_returned_at)
                                        <div class="small text-success mt-2"><i class="bi bi-check2-circle me-1" aria-hidden="true"></i>Dikembalikan {{ $order->rental_returned_at->timezone(\App\Models\CourtRentalOrder::TIMEZONE)->format('H:i') }} WIB</div>
                                    @elseif($order->rental_picked_up_at)
                                        <div class="small text-warning-emphasis mt-2"><i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>Sedang disewa · diambil {{ $order->rental_picked_up_at->timezone(\App\Models\CourtRentalOrder::TIMEZONE)->format('H:i') }} WIB</div>
                                        @if(\Carbon\Carbon::parse($order->rental_date->toDateString().' '.$order->time_end, \App\Models\CourtRentalOrder::TIMEZONE)->lessThan(now()->timezone(\App\Models\CourtRentalOrder::TIMEZONE)))
                                            <span class="badge text-bg-warning mt-1">Lewat perkiraan kembali</span>
                                        @endif
                                    @else
                                        <div class="small text-secondary mt-2">Belum diambil</div>
                                    @endif
                                @endif
                            </td>
                            <td class="text-end" style="min-width:220px">
                                @if(in_array($order->status, ['pending_review', 'confirmed', 'rejected', 'cancelled'], true))
                                    <a class="btn btn-sm btn-outline-success mb-2"
                                        href="https://wa.me/{{ $waPhone }}?text={{ rawurlencode(implode("\n", $waLines)) }}"
                                        target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp me-1"
                                            aria-hidden="true"></i>Kirim info</a>
                                @endif
                                @if($order->status === 'pending_review')
                                    <div class="d-flex flex-wrap justify-content-end gap-2">
                                        <form method="POST" action="{{ route('admin.court-rentals.confirm', $order) }}"
                                            onsubmit="return confirm('Konfirmasi pembayaran yang sudah dicocokkan?')">@csrf<button
                                                class="btn btn-sm btn-success" type="submit"><i class="bi bi-check2 me-1"
                                                    aria-hidden="true"></i>Konfirmasi</button></form>
                                        <details class="text-start">
                                            <summary class="btn btn-sm btn-outline-danger">Tolak</summary>
                                            <form class="mt-2" method="POST"
                                                action="{{ route('admin.court-rentals.reject', $order) }}">@csrf<input
                                                    class="form-control form-control-sm mb-2" name="admin_note" maxlength="1000"
                                                    placeholder="Alasan (opsional)"><button class="btn btn-sm btn-danger"
                                                    type="submit">Tolak bukti</button></form>
                                        </details>
                                    </div>
                                @elseif($order->status === 'confirmed' && $order->lines->isNotEmpty())
                                    @if($order->rental_returned_at)
                                        <span class="small text-secondary">Sewa selesai</span>
                                    @elseif($order->rental_picked_up_at)
                                        <form method="POST" action="{{ route('admin.court-rentals.return', $order) }}" onsubmit="return confirm('Catat bahwa semua perlengkapan pada pesanan ini sudah dikembalikan?')">
                                            @csrf
                                            <button class="btn btn-sm btn-success" type="submit"><i class="bi bi-box-arrow-in-down-left me-1" aria-hidden="true"></i>Catat dikembalikan</button>
                                        </form>
                                    @elseif($order->rental_date->toDateString() <= now()->timezone(\App\Models\CourtRentalOrder::TIMEZONE)->toDateString())
                                        <form method="POST" action="{{ route('admin.court-rentals.pickup', $order) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-primary" type="submit"><i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>Catat diambil</button>
                                        </form>
                                    @else
                                        <span class="small text-secondary">Catat saat barang diserahkan</span>
                                    @endif
                                    @if(!$order->rental_picked_up_at)
                                        <details class="text-start mt-2">
                                            <summary class="btn btn-sm btn-outline-danger">Batalkan pesanan</summary>
                                            <form class="mt-2" method="POST" action="{{ route('admin.court-rentals.cancel', $order) }}" onsubmit="return confirm('Batalkan pesanan ini?')">
                                                @csrf<textarea class="form-control form-control-sm mb-2" name="admin_note" maxlength="1000" placeholder="Alasan pembatalan wajib diisi" required></textarea>
                                                <button class="btn btn-sm btn-danger" type="submit">Konfirmasi pembatalan</button>
                                            </form>
                                        </details>
                                    @endif
                                @elseif(in_array($order->status, ['awaiting_payment', 'confirmed'], true))
                                    <details class="text-start">
                                        <summary class="btn btn-sm btn-outline-danger">Batalkan pesanan</summary>
                                        <form class="mt-2" method="POST" action="{{ route('admin.court-rentals.cancel', $order) }}"
                                            onsubmit="return confirm('Batalkan pesanan ini?')">@csrf<textarea
                                                class="form-control form-control-sm mb-2" name="admin_note" maxlength="1000"
                                                placeholder="Alasan pembatalan wajib diisi" required></textarea><button
                                                class="btn btn-sm btn-danger" type="submit">Konfirmasi pembatalan</button></form>
                                    </details>
                                @else<span class="small text-secondary">—</span>@endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-5">Belum ada pesanan perlengkapan pada filter
                                ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="card-body">{{ $orders->links() }}</div>@endif
    </div>
@endsection
