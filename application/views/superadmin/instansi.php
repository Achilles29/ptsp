<div class="container mt-4">
    <h4><?= $title ?></h4>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="ri-building-line"></i> Tambah Instansi
            </button>
            <input type="text" id="searchInstansi" class="form-control form-control-sm" placeholder="Cari instansi..." style="width:220px;">
        </div>

        <form method="get" class="d-flex align-items-center">
            <label class="me-2 mb-0">Tampilkan:</label>
            <select name="limit" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                <option value="<?= $total_rows ?>" <?= $limit == $total_rows ? 'selected' : '' ?>>Semua</option>
            </select>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle" id="instansiTable">
            <thead class="table-light">
                <tr class="text-center">
                    <th style="width:5%">#</th>
                    <th>Kode</th>
                    <th>Nama Instansi</th>
                    <th>Deskripsi</th>
                    <th>Loket</th>
                    <th>Status Layanan</th>
                    <th>Status Aktif</th>
                    <th style="width:15%">Aksi</th>
                </tr>
            </thead>
            <tbody id="instansiTableBody">
                <?php foreach ($instansi as $i => $ins): ?>
                    <tr>
                        <td class="text-center"><?= $start + $i + 1 ?></td>
                        <td><?= $ins->kode_instansi ?></td>
                        <td><?= $ins->nama_instansi ?></td>
                        <td><?= $ins->deskripsi ?></td>
                        <td class="text-center"><?= $ins->loket ?></td>
                        <td class="text-center">
                            <span class="badge bg-<?= $ins->status_layanan === 'buka' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($ins->status_layanan) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-<?= $ins->is_aktif ? 'primary' : 'danger' ?>">
                                <?= $ins->is_aktif ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning btn-edit"
                                data-id="<?= $ins->id ?>"
                                data-kode="<?= $ins->kode_instansi ?>"
                                data-nama="<?= $ins->nama_instansi ?>"
                                data-deskripsi="<?= $ins->deskripsi ?>"
                                data-loket="<?= $ins->loket ?>"
                                data-status="<?= $ins->status_layanan ?>"
                                data-aktif="<?= $ins->is_aktif ?>"
                                data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="ri-edit-line"></i> Edit
                            </button>
                            <a href="<?= base_url('superadmin/instansi_delete/' . $ins->id) ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Hapus instansi ini?')">
                                <i class="ri-delete-bin-line"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>


        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
        <small class="text-muted">Menampilkan <?= count($instansi) ?> dari <?= $total_rows ?> data</small>
        <div id="paginationContainer"><?= $pagination ?></div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="<?= base_url('superadmin/instansi_add') ?>">
            <div class="modal-header">
                <h5>Tambah Instansi</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label>Kode Instansi</label><input name="kode_instansi" class="form-control" required></div>
                <div class="mb-3"><label>Nama Instansi</label><input name="nama_instansi" class="form-control" required></div>
                <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"></textarea></div>
                <div class="mb-3"><label>Loket</label><input name="loket" class="form-control" placeholder="Contoh: Loket A / Front Desk"></div>
                <div class="mb-3">
                    <label>Status Layanan</label>
                    <select name="status_layanan" class="form-select">
                        <option value="buka">Buka</option>
                        <option value="tutup">Tutup</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Status Aktif</label>
                    <select name="is_aktif" class="form-select">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

            </div>
            <div class="modal-footer"><button class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="" id="editForm">
            <div class="modal-header">
                <h5>Edit Instansi</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label>Kode Instansi</label><input name="kode_instansi" class="form-control" required></div>
                <div class="mb-3"><label>Nama Instansi</label><input name="nama_instansi" class="form-control" required></div>
                <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"></textarea></div>
                <div class="mb-3"><label>Loket</label><input name="loket" class="form-control" placeholder="Contoh: Loket A / Front Desk"></div>
                <div class="mb-3">
                    <label>Status Layanan</label>
                    <select name="status_layanan" class="form-select">
                        <option value="buka">Buka</option>
                        <option value="tutup">Tutup</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Status Aktif</label>
                    <select name="is_aktif" class="form-select">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

            </div>
            <div class="modal-footer"><button class="btn btn-primary">Update</button></div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const f = document.getElementById('editForm');
            f.action = '<?= base_url('superadmin/instansi_edit/') ?>' + this.dataset.id;
            f.kode_instansi.value = this.dataset.kode;
            f.nama_instansi.value = this.dataset.nama;
            f.deskripsi.value = this.dataset.deskripsi;
            f.loket.value = this.dataset.loket;
            f.status_layanan.value = this.dataset.status;
            f.is_aktif.value = this.dataset.aktif;

        });
    });

    document.getElementById('searchInstansi').addEventListener('keyup', function() {
        const keyword = this.value.trim();
        const pagination = document.getElementById('paginationContainer');
        if (keyword.length < 2 && keyword !== '') return;

        fetch(`<?= base_url('superadmin/search_instansi_ajax?keyword=') ?>${encodeURIComponent(keyword)}`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('instansiTableBody');
                tbody.innerHTML = '';
                pagination.style.display = 'none';

                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>`;
                    return;
                }

                data.forEach((i, idx) => {
                    tbody.innerHTML += `
          <tr>
            <td class="text-center">${idx + 1}</td>
            <td>${i.kode_instansi ?? ''}</td>
            <td>${i.nama_instansi ?? ''}</td>
            <td>${i.deskripsi ?? ''}</td>
            <td class="text-center">
              <button class="btn btn-sm btn-warning"><i class="ri-edit-line"></i> Edit</button>
              <a href="<?= base_url('superadmin/instansi_delete/') ?>${i.id}" class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i> Hapus</a>
            </td>
          </tr>`;
                });
            });

        if (keyword === '') location.reload();
    });
</script>