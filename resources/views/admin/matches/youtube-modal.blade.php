<!-- Modal untuk add YouTube link -->
<div class="modal fade" id="youtubeModal{{ $match->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-youtube text-danger me-2"></i>
                    Add YouTube Highlight
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="youtubeForm{{ $match->id }}" class="youtube-form">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">YouTube URL</label>
                        <input type="url" name="youtube_url" class="form-control"
                            placeholder="https://youtube.com/watch?v=..." required>
                        <div class="form-text">
                            Supported formats: youtube.com/watch?v=..., youtu.be/..., youtube.com/embed/...
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Video will be embedded directly from YouTube. No file upload required.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="saveYouTubeHighlight({{ $match->id }})">
                    <i class="bi bi-youtube me-1"></i> Save YouTube Link
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function saveYouTubeHighlight(matchId) {
    const form = document.getElementById(`youtubeForm${matchId}`);
    const formData = new FormData(form);

    fetch(`/matches/${matchId}/youtube-highlight`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('YouTube highlight added successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
}
</script>