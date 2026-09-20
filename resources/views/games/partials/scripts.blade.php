{{-- Match Schedule Interactive Scripts --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit filter form when date picker changes
        const dateInput = document.querySelector('input[name="date"]');
        if (dateInput) {
            dateInput.addEventListener('change', function() {
                this.form.submit();
            });
        }

        // Auto-submit filter form when group or status dropdown changes
        const autoSelects = document.querySelectorAll('select[name="group"], select[name="status"]');
        autoSelects.forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });

        @if(($ongoingMatches ?? 0) > 0)
            // If live matches are active, auto-refresh every 30 seconds
            setTimeout(() => {
                window.location.reload();
            }, 30000);
        @endif
    });
</script>
