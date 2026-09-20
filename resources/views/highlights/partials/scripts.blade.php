<script>
document.addEventListener('DOMContentLoaded', function () {
    // ---- YouTube modals: pause/reload iframe on show/hide ----
    document.querySelectorAll('[id^="ytModal"]').forEach(modal => {
        // Reload src when modal opens (ensures autoplay works)
        modal.addEventListener('shown.bs.modal', function () {
            const iframe = this.querySelector('iframe');
            if (iframe) {
                const src = iframe.src;
                iframe.src = '';
                setTimeout(() => { iframe.src = src; }, 80);
            }
        });

        // Stop video when modal closes
        modal.addEventListener('hide.bs.modal', function () {
            const iframe = this.querySelector('iframe');
            if (iframe) iframe.src = iframe.src;
        });
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

    // ---- Lazy image fade-in ----
    document.querySelectorAll('.highlight-thumbnail img').forEach(img => {
        if (!img.complete) {
            img.style.opacity = '0';
            img.addEventListener('load', function () {
                this.style.transition = 'opacity 0.3s ease';
                this.style.opacity = '1';
            });
        }
    });
});
</script>
