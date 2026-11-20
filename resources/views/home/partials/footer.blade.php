<footer class="app-footer">
    <div class="app-container">
        <div class="row g-4">
            <!-- Brand & Social -->
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="{{ asset('images/logo-ofs.png') }}" alt="Logo OFS Futsal Center" style="width: 28px; height: 28px; object-fit: contain;">
                    <span class="fw-bold text-dark">OFS Futsal Center</span>
                </div>
                <p class="text-secondary small mb-3">
                    Pusat futsal dan kompetisi untuk tim di Jombang.
                </p>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" class="btn-action-secondary btn-action-sm px-2 text-secondary" title="Facebook OFS Futsal Center"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn-action-secondary btn-action-sm px-2 text-secondary" title="Instagram OFS Futsal Center"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="btn-action-secondary btn-action-sm px-2 text-secondary" title="YouTube OFS Futsal Center"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-6 col-md-4">
                <div class="footer-heading">Tautan cepat</div>
                <ul class="footer-nav-list small">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('schedule') }}">Jadwal pertandingan</a></li>
                    <li><a href="{{ route('standings') }}">Klasemen turnamen</a></li>
                    <li><a href="{{ route('tournaments.history') }}">Riwayat turnamen</a></li>
                    <li><a href="{{ route('highlights.index') }}">Sorotan pertandingan</a></li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="col-6 col-md-4">
                <div class="footer-heading">Informasi kontak</div>
                <div class="small text-secondary d-flex flex-column gap-2">
                    <div><i class="bi bi-geo-alt me-2 text-dark"></i>OFS Futsal Center Jombang</div>
                    <div><i class="bi bi-telephone me-2 text-dark"></i>+62 812 4752 1076</div>
                    <div><i class="bi bi-envelope me-2 text-dark"></i>ofsfutsalcenter@gmail.com</div>
                    <div><i class="bi bi-clock me-2 text-dark"></i>Setiap hari, 07.00–23.30</div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ now(\App\Support\BusinessTime::TIMEZONE)->year }} OFS Futsal Center. Hak cipta dilindungi.</span>
            <span class="text-secondary">Futsal, turnamen, dan komunitas.</span>
        </div>
    </div>
</footer>
