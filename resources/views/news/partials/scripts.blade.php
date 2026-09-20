<script>
document.addEventListener('DOMContentLoaded', function () {
    // ---- Scroll-to-top button ----
    const btn = document.createElement('button');
    btn.innerHTML = '<i class="bi bi-chevron-up"></i>';
    btn.id = 'scrollTopBtn';
    btn.setAttribute('aria-label', 'Scroll to top');
    Object.assign(btn.style, {
        position: 'fixed',
        bottom: '20px',
        right: '20px',
        width: '40px',
        height: '40px',
        borderRadius: '50%',
        border: 'none',
        background: '#1a5fb4',
        color: '#fff',
        fontSize: '1rem',
        cursor: 'pointer',
        zIndex: '1050',
        display: 'none',
        alignItems: 'center',
        justifyContent: 'center',
        boxShadow: '0 2px 8px rgba(0,0,0,0.15)',
    });
    document.body.appendChild(btn);

    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    window.addEventListener('scroll', () => {
        btn.style.display = window.pageYOffset > 300 ? 'flex' : 'none';
    }, { passive: true });

    // ---- Lazy-load image fade ----
    document.querySelectorAll('img').forEach(img => {
        if (!img.complete) {
            img.style.opacity = '0';
            img.addEventListener('load', function () {
                this.style.transition = 'opacity 0.3s ease';
                this.style.opacity = '1';
            });
        }
    });

    // ---- Mobile navbar auto-close ----
    const collapse = document.querySelector('.navbar-collapse');
    if (collapse) {
        document.querySelectorAll('.nav-menu-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    const bsCollapse = bootstrap.Collapse.getInstance(collapse);
                    if (bsCollapse) bsCollapse.hide();
                }
            });
        });
    }
});
</script>
