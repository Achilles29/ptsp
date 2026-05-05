<div class="container-fluid px-4 mt-4">
    <div class="portal-page">
        <section class="portal-page-intro">
            <div>
                <div class="portal-page-eyebrow"><i class="ri ri-layout-grid-line"></i> Pengaturan Sistem</div>
                <h1 class="portal-page-title"><?= html_escape($title) ?></h1>
                <p class="portal-page-subtitle">
                    Kelola sektor display antrian beserta slug URL dan lokasi monitor yang digunakan.
                </p>
            </div>
            <div class="portal-inline-metrics">
                <div class="portal-inline-metric">
                    <small>Total sektor</small>
                    <strong><?= count($sektor_list) ?></strong>
                </div>
            </div>
        </section>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php elseif ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>

        <section class="portal-filter-card">
            <div class="portal-toolbar">
                <div class="portal-toolbar-actions">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSektorModal">
                        <i class="ri-add-line me-1"></i> Tambah Sektor
                    </button>
                </div>
            </div>
        </section>

        <section class="card portal-section-card portal-table-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="portal-section-title">Daftar Sektor Display</h5>
                    <div class="portal-card-note">Slug digunakan sebagai URL monitor display antrian.</div>
                </div>
                <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle"><?= count($sektor_list) ?> sektor</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-scroll-x">
                        <thead>
                            <tr class="text-center">
                                <th class="col-nowrap">#</th>
                                <th class="col-nowrap">Kode</th>
                                <th class="col-name-min">Nama Sektor</th>
                                <th class="d-mobile-none col-nowrap">Slug URL</th>
                                <th class="d-mobile-none col-name-min">Lokasi Display</th>
                                <th class="d-mobile-none col-text-min">URL Display</th>
                                <th class="col-nowrap">Status</th>
                                <th class="col-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($sektor_list)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-3">Belum ada data sektor.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($sektor_list as $i => $s): ?>
                                    <tr>
                                        <td class="text-center"><?= $i + 1 ?></td>
                                        <td><span class="badge bg-dark-subtle text-dark border"><?= htmlspecialchars($s->kode_sektor) ?></span></td>
                                        <td class="fw-semibold"><?= htmlspecialchars($s->nama_sektor) ?></td>
                                        <td class="d-mobile-none"><code><?= htmlspecialchars($s->slug) ?></code></td>
                                        <td class="d-mobile-none"><?= htmlspecialchars($s->lokasi_display ?: '-') ?></td>
                                        <td class="d-mobile-none">
                                            <code class="text-truncate-url"><?= site_url('antrian_display/index/' . $s->slug) ?></code>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-<?= (int) $s->is_aktif === 1 ? 'success' : 'secondary' ?>">
                                                <?= (int) $s->is_aktif === 1 ? 'Aktif' : 'Nonaktif' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="table-action-group">
                                                <button class="btn btn-sm btn-warning btn-edit-sektor"
                                                    data-id="<?= $s->id ?>"
                                                    data-kode="<?= htmlspecialchars($s->kode_sektor) ?>"
                                                    data-nama="<?= htmlspecialchars($s->nama_sektor, ENT_QUOTES) ?>"
                                                    data-lokasi="<?= htmlspecialchars($s->lokasi_display ?? '', ENT_QUOTES) ?>"
                                                    data-aktif="<?= (int) $s->is_aktif ?>"
                                                    data-bs-toggle="modal" data-bs-target="#editSektorModal">
                                                    <i class="ri-edit-line"></i> <span class="btn-label">Edit</span>
                                                </button>
                                                <a href="<?= base_url('superadmin/sektor_delete/' . $s->id) ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Hapus sektor ini?')">
                                                    <i class="ri-delete-bin-line"></i> <span class="btn-label">Hapus</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="addSektorModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="<?= base_url('superadmin/sektor_add') ?>">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Sektor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Kode Sektor</label>
                    <input type="text" name="kode_sektor" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Sektor</label>
                    <input type="text" name="nama_sektor" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Lokasi Display</label>
                    <input type="text" name="lokasi_display" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="is_aktif" class="form-select">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editSektorModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="post" id="editSektorForm">
            <div class="modal-header">
                <h5 class="modal-title">Edit Sektor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Kode Sektor</label>
                    <input type="text" name="kode_sektor" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Sektor</label>
                    <input type="text" name="nama_sektor" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Lokasi Display</label>
                    <input type="text" name="lokasi_display" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="is_aktif" class="form-select">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.btn-edit-sektor').forEach(btn => {
    btn.addEventListener('click', function() {
        const f = document.getElementById('editSektorForm');
        f.action = '<?= base_url('superadmin/sektor_edit/') ?>' + this.dataset.id;
        f.kode_sektor.value = this.dataset.kode;
        f.nama_sektor.value = this.dataset.nama;
        f.lokasi_display.value = this.dataset.lokasi;
        f.is_aktif.value = this.dataset.aktif;
    });
});
</script>
