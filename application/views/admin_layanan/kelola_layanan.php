<div class="container mt-4">
    <h4>Pengaturan Status Layanan</h4>
    <hr>

    <div class="card">
        <div class="card-body">
            <?php if (!empty($instansi)): ?>
                <p><strong>Instansi:</strong> <?= htmlspecialchars($instansi->nama_instansi) ?></p>
                <p><strong>Deskripsi:</strong> <?= htmlspecialchars($instansi->deskripsi) ?></p>

                <form action="<?= base_url('admin_layanan/update_status_layanan') ?>" method="post">
                    <input type="hidden" name="instansi_id" value="<?= $instansi->id ?>">
                    <div class="form-group">
                        <label>Status Layanan</label>
                        <select name="status_layanan" class="form-control" required>
                            <option value="buka" <?= $instansi->status_layanan === 'buka' ? 'selected' : '' ?>>Buka (Aktif)</option>
                            <option value="tutup" <?= $instansi->status_layanan === 'tutup' ? 'selected' : '' ?>>Tutup (Nonaktif)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </form>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success mt-3">
                        <?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-danger">
                    Data instansi tidak ditemukan. Silakan cek pengaturan akun Anda atau hubungi administrator.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>