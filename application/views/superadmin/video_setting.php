<div class="container mt-4">
    <h4><i class="bi bi-camera-video me-2"></i><?= $title ?></h4>
    <hr>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" action="<?= base_url('superadmin/save_video_setting') ?>" class="p-4 border rounded bg-white shadow-sm">
        <div class="mb-3">
            <label class="form-label fw-bold">Sumber Video</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="source_type" id="optFile" value="file" <?= $video->source_type == 'file' ? 'checked' : '' ?>>
                <label class="form-check-label" for="optFile">Upload File</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="source_type" id="optYoutube" value="youtube" <?= $video->source_type == 'youtube' ? 'checked' : '' ?>>
                <label class="form-check-label" for="optYoutube">Link YouTube</label>
            </div>
        </div>

        <div id="fileUploadSection" class="mb-3">
            <label class="form-label">Upload Video (mp4/mkv)</label>
            <input type="file" name="video_file" class="form-control">
            <?php if (!empty($video->file_path)): ?>
                <p class="mt-2 text-muted">File saat ini: <a href="<?= base_url($video->file_path) ?>" target="_blank"><?= basename($video->file_path) ?></a></p>
            <?php endif; ?>
        </div>

        <div id="youtubeSection" class="mb-3" style="display:none;">
            <label class="form-label">Link YouTube</label>
            <input type="url" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." value="<?= $video->youtube_url ?>">
            <?php if (!empty($video->youtube_url)): ?>
                <div class="mt-3">
                    <iframe width="100%" height="250" src="<?= str_replace('watch?v=', 'embed/', $video->youtube_url) ?>" allowfullscreen></iframe>
                </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Pengaturan Suara</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_muted" id="optMuted" value="1"
                    <?= empty($video->is_muted) || $video->is_muted == 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="optMuted">Tanpa Suara (Default)</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_muted" id="optSoundOn" value="0"
                    <?= isset($video->is_muted) && $video->is_muted == 0 ? 'checked' : '' ?>>
                <label class="form-check-label" for="optSoundOn">Aktifkan Suara</label>
            </div>
        </div>

        <button class="btn btn-primary mt-3"><i class="bi bi-save"></i> Simpan Pengaturan</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const optFile = document.getElementById('optFile');
        const optYoutube = document.getElementById('optYoutube');
        const fileSec = document.getElementById('fileUploadSection');
        const ytSec = document.getElementById('youtubeSection');

        function toggleSections() {
            if (optYoutube.checked) {
                ytSec.style.display = 'block';
                fileSec.style.display = 'none';
            } else {
                ytSec.style.display = 'none';
                fileSec.style.display = 'block';
            }
        }
        toggleSections();
        optFile.addEventListener('change', toggleSections);
        optYoutube.addEventListener('change', toggleSections);
    });
</script>