<div class="container mt-4">
    <h4><i class="bi bi-volume-up me-2"></i><?= $title ?></h4>
    <hr>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('superadmin/save_audio_speed_setting') ?>" class="p-4 border rounded bg-white shadow-sm">
        <div class="mb-3">
            <label for="audio_speed" class="form-label fw-bold">Kecepatan Suara Panggilan</label>
            <input
                type="number"
                class="form-control"
                id="audio_speed"
                name="audio_speed"
                min="0.50"
                max="3.00"
                step="0.05"
                value="<?= isset($video->audio_speed) ? (float) $video->audio_speed : 1.50 ?>"
                required>
            <small class="text-muted">Rekomendasi: 1.50. Rentang nilai: 0.50 s.d. 3.00</small>
        </div>

        <button class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan Pengaturan
        </button>
    </form>
</div>
