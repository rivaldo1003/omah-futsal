@extends('layouts.admin')

@section('title', 'Pratinjau Bukti Pembayaran')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <a href="{{ route('admin.court-bookings.index') }}" class="btn btn-sm btn-link px-0 text-decoration-none mb-2">
            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Kembali ke daftar booking
        </a>
        <h1 class="mb-1">Pratinjau bukti pembayaran</h1>
        <p class="text-secondary mb-0">
            Booking #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}
            · {{ $booking->court?->name ?? 'Lapangan dihapus' }}
            · {{ $booking->customer_name }}
        </p>
    </div>
    <a href="{{ route('admin.court-bookings.proof.download', $booking) }}" class="btn btn-primary">
        <i class="bi bi-download me-1" aria-hidden="true"></i>Unduh bukti
    </a>
</div>

<section class="card proof-card" aria-label="Pratinjau file bukti pembayaran">
    <header class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <div class="fw-semibold text-break">{{ $fileName }}</div>
            <small class="text-secondary">{{ $mimeType === 'application/pdf' ? 'Dokumen PDF' : 'Gambar bukti pembayaran' }}</small>
        </div>
        <a href="{{ route('admin.court-bookings.proof.preview', $booking) }}" class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener">
            <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>Buka file penuh
        </a>
    </header>
    <div class="card-body p-0">
        @if(str_starts_with($mimeType, 'image/'))
            <div class="proof-image-stage">
                <img src="{{ route('admin.court-bookings.proof.preview', $booking) }}"
                    alt="Bukti pembayaran booking #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}"
                    class="proof-image">
            </div>
        @else
            <iframe src="{{ route('admin.court-bookings.proof.preview', $booking) }}"
                class="proof-pdf-frame"
                title="Pratinjau PDF bukti pembayaran booking #{{ str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) }}">
                Browser tidak dapat menampilkan PDF. Gunakan tombol Unduh Bukti.
            </iframe>
        @endif
    </div>
</section>

<style>
    .proof-card { overflow:hidden; }
    .proof-image-stage { display:flex; justify-content:center; align-items:center; min-height:360px; max-height:78vh; padding:20px; overflow:auto; background:#eef0f3; }
    .proof-image { display:block; max-width:100%; max-height:calc(78vh - 40px); width:auto; height:auto; object-fit:contain; }
    .proof-pdf-frame { display:block; width:100%; height:78vh; min-height:520px; border:0; background:#f1f3f5; }
    @media(max-width:575.98px) {
        .proof-image-stage { min-height:260px; max-height:72vh; padding:10px; }
        .proof-image { max-height:calc(72vh - 20px); }
        .proof-pdf-frame { height:72vh; min-height:440px; }
    }
</style>
@endsection
