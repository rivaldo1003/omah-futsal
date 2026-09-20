<script>
document.addEventListener('DOMContentLoaded', function () {
    // ---- Share buttons — open popup window ----
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const w = 580, h = 400;
            const left = Math.round((screen.width - w) / 2);
            const top  = Math.round((screen.height - h) / 2);
            window.open(
                this.href, 'share',
                `width=${w},height=${h},left=${left},top=${top},menubar=no,toolbar=no,scrollbars=yes,resizable=yes`
            );
        });
    });

    // ---- FAB: Scroll to top ----
    const scrollFab = document.getElementById('fabScrollTop');
    if (scrollFab) {
        scrollFab.addEventListener('click', e => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        window.addEventListener('scroll', () => {
            scrollFab.style.opacity = window.pageYOffset > 300 ? '1' : '0';
            scrollFab.style.pointerEvents = window.pageYOffset > 300 ? 'auto' : 'none';
        }, { passive: true });
        // Start hidden
        scrollFab.style.opacity = '0';
        scrollFab.style.transition = 'opacity 0.2s';
        scrollFab.style.pointerEvents = 'none';
    }

    // ---- FAB: Copy link ----
    const copyFab = document.getElementById('fabCopyLink');
    if (copyFab) {
        copyFab.addEventListener('click', e => {
            e.preventDefault();
            navigator.clipboard.writeText(window.location.href).then(() => {
                const orig = copyFab.innerHTML;
                copyFab.innerHTML = '<i class="bi bi-check"></i>';
                copyFab.style.background = '#0d9488';
                setTimeout(() => {
                    copyFab.innerHTML = orig;
                    copyFab.style.background = '';
                }, 2000);
            });
        });
    }

    // ---- Lazy image fade-in ----
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

    // ---- Increment views (fire-and-forget after 2s) ----
    setTimeout(() => {
        fetch(`/news/{{ $article->id }}/increment-views`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        }).catch(() => {}); // ignore errors silently
    }, 2000);
});
</script>
