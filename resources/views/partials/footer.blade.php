{{-- resources/views/partials/footer.blade.php --}}
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5 class="footer-title">
                    <i class="bi bi-trophy-fill"></i> OFS Futsal Center
                </h5>
                <div class="social-icons">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <h5 class="footer-title">Quick Links</h5>
                <div class="footer-links">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('schedule') }}">Schedule</a>
                    <a href="{{ route('standings') }}">Standings</a>
                    <a href="{{ route('highlights.index') }}">Highlights</a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <h5 class="footer-title">Contact Info</h5>
                <div class="footer-contact">
                    <p class="mb-2"><i class="bi bi-geo-alt"></i> OFS Futsal Center Jombang</p>
                    <p class="mb-2"><i class="bi bi-telephone"></i> +62 812 4752 1076</p>
                    <p class="mb-2"><i class="bi bi-envelope"></i> ofsfutsalcenter@gmail.com</p>
                    <p class="mb-0"><i class="bi bi-clock"></i> Mon-Sun: 07.00-23.30</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="copyright">
                    <p class="mb-0">&copy; {{ date('Y') }} OFS Futsal Center. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</footer>