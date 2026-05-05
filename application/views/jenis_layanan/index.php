<div class="container-fluid px-4 mt-4">
    <div class="portal-page">
        <section class="portal-page-intro">
            <div>
                <div class="portal-page-eyebrow"><i class="ri ri-briefcase-2-line"></i> Pengaturan Sistem</div>
                <h1 class="portal-page-title"><?= html_escape($title) ?></h1>
                <p class="portal-page-subtitle">
                    Kelola jenis layanan yang tersedia di setiap instansi beserta kode antrian dan target SLA.
                </p>
            </div>
            <div class="portal-inline-metrics">
                <div class="portal-inline-metric">
                    <small>Total data</small>
                    <strong><?= (int) $total_rows ?></strong>
                </div>
            </div>
        </section>

        <section class="portal-filter-card">
            <div class="portal-toolbar">
                <div class="portal-toolbar-actions">
                    <?php if ($this->session->userdata('role_id') == 1): ?>
                    <button class="btn btn-primary" onclick="tambahData()">
                        <i class="ri-add-line me-1"></i> Tambah
                    </button>
                    <?php endif; ?>
                    <input type="text" id="searchLayanan" class="form-control" placeholder="Cari layanan..." style="width:min(100%, 260px);">
                </div>
                <form method="get" class="portal-toolbar-actions">
                    <label class="text-muted mb-0">Tampilkan</label>
                    <select name="limit" onchange="this.form.submit()" class="form-select w-auto">
                        <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                        <option value="<?= $total_rows ?>" <?= $limit == $total_rows ? 'selected' : '' ?>>Semua</option>
                    </select>
                </form>
            </div>
        </section>

        <section class="card portal-section-card portal-table-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="portal-section-title">Daftar Jenis Layanan</h5>
                    <div class="portal-card-note">Kode huruf digunakan sebagai awalan nomor antrian.</div>
                </div>
                <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle"><?= count($jenis_layanan) ?> baris</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-scroll-x" id="layananTable">
                        <thead>
                            <tr class="text-center">
                                <th class="col-nowrap">#</th>
                                <th class="col-name-min">Instansi</th>
                                <th class="col-nowrap">Kode</th>
                                <th class="col-nowrap">Kode Huruf</th>
                                <th class="col-name-min">Nama Layanan</th>
                                <th class="d-mobile-none col-text-min">Deskripsi</th>
                                <th class="d-mobile-none col-nowrap">Target SLA</th>
                                <?php if ($this->session->userdata('role_id') == 1): ?>
                                    <th class="col-nowrap">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="layananTableBody">
                            <?php if (empty($jenis_layanan)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-3">Tidak ada data layanan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($jenis_layanan as $i => $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $start + $i + 1 ?></td>
                                        <td><?= htmlspecialchars($row->nama_instansi ?? '-') ?></td>
                                        <td class="text-center"><span class="badge bg-dark-subtle text-dark border"><?= htmlspecialchars($row->kode) ?></span></td>
                                        <td class="text-center"><span class="badge bg-info-subtle text-info-emphasis border border-info-subtle"><?= htmlspecialchars($row->kode_huruf) ?></span></td>
                                        <td class="fw-semibold"><?= htmlspecialchars($row->nama_layanan) ?></td>
                                        <td class="d-mobile-none text-muted"><?= htmlspecialchars($row->deskripsi ?? '') ?></td>
                                        <td class="text-center d-mobile-none"><?= (int) ($row->target_durasi_menit ?? 30) ?> mnt</td>
                                        <?php if ($this->session->userdata('role_id') == 1): ?>
                                            <td>
                                                <div class="table-action-group">
                                                    <button class="btn btn-sm btn-warning" onclick="editData(<?= $row->id ?>)">
                                                        <i class="ri-edit-line"></i> <span class="btn-label">Edit</span>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="deleteJenisLayanan(<?= $row->id ?>)">
                                                        <i class="ri-delete-bin-line"></i> <span class="btn-label">Hapus</span>
                                                    </button>
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                    <small class="text-muted">Menampilkan <?= count($jenis_layanan) ?> dari <?= $total_rows ?> data</small>
                    <div id="paginationContainer"><?= $pagination ?></div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= base_url('jenislayanan/simpan') ?>">
            <input type="hidden" name="id" id="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Jenis Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Instansi</label>
                        <select name="instansi_id" id="instansi_id" class="form-select" required>
                            <option value="">-- Pilih Instansi --</option>
                            <?php foreach ($instansi as $ins): ?>
                                <option value="<?= $ins->id ?>"><?= htmlspecialchars($ins->nama_instansi) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text" name="kode" class="form-control" placeholder="Contoh: 1" id="kode" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Huruf</label>
                        <input type="text" name="kode_huruf" class="form-control" placeholder="Contoh: A" id="kode_huruf" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Layanan</label>
                        <input type="text" name="nama_layanan" class="form-control" placeholder="Nama Layanan" id="nama_layanan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" placeholder="Deskripsi" id="deskripsi"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Target SLA (menit)</label>
                        <input type="number" min="1" name="target_durasi_menit" class="form-control" placeholder="Contoh: 30" id="target_durasi_menit" value="30" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function tambahData() {
        $('#id').val('');
        $('#instansi_id').val('');
        $('#kode').val('');
        $('#kode_huruf').val('');
        $('#nama_layanan').val('');
        $('#deskripsi').val('');
        $('#target_durasi_menit').val('30');
        $('#modalForm').modal('show');
    }

    function editData(id) {
        $.get('<?= base_url("jenislayanan/get_by_id/") ?>' + id, function(data) {
            const d = JSON.parse(data);
            $('#id').val(d.id);
            $('#instansi_id').val(d.instansi_id);
            $('#kode').val(d.kode);
            $('#kode_huruf').val(d.kode_huruf);
            $('#nama_layanan').val(d.nama_layanan);
            $('#deskripsi').val(d.deskripsi);
            $('#target_durasi_menit').val(d.target_durasi_menit || 30);
            $('#modalForm').modal('show');
        });
    }

    function deleteJenisLayanan(id) {
        if (confirm('Yakin ingin menghapus jenis layanan ini?')) {
            $.ajax({
                url: '<?= base_url("jenislayanan/jenis_layanan_delete/") ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    alert(res.message);
                    location.reload();
                }
            });
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('searchLayanan');
        const tbody = document.getElementById('layananTableBody');
        const paginationContainer = document.getElementById('paginationContainer');
        const isSuperadmin = <?= $this->session->userdata('role_id') == 1 ? 'true' : 'false' ?>;

        input?.addEventListener('keyup', function() {
            const keyword = this.value.trim();
            if (keyword.length < 2 && keyword !== '') return;

            fetch(`<?= base_url('jenislayanan/search_ajax?keyword=') ?>${encodeURIComponent(keyword)}`)
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = '';
                    paginationContainer.parentElement.querySelector('small') && (paginationContainer.parentElement.querySelector('small').textContent = '');
                    paginationContainer.style.display = 'none';

                    if (data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>`;
                        return;
                    }

                    data.forEach((d, i) => {
                        let aksi = '';
                        if (isSuperadmin) {
                            aksi = `
                                <td>
                                    <div class="table-action-group">
                                        <button class="btn btn-sm btn-warning" onclick="editData(${d.id})">
                                            <i class="ri-edit-line"></i> <span class="btn-label">Edit</span>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteJenisLayanan(${d.id})">
                                            <i class="ri-delete-bin-line"></i> <span class="btn-label">Hapus</span>
                                        </button>
                                    </div>
                                </td>`;
                        }

                        tbody.innerHTML += `
                            <tr>
                                <td class="text-center">${i + 1}</td>
                                <td>${d.nama_instansi ?? '-'}</td>
                                <td class="text-center"><span class="badge bg-dark-subtle text-dark border">${d.kode ?? ''}</span></td>
                                <td class="text-center"><span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">${d.kode_huruf ?? ''}</span></td>
                                <td class="fw-semibold">${d.nama_layanan ?? ''}</td>
                                <td class="d-mobile-none text-muted">${d.deskripsi ?? ''}</td>
                                <td class="text-center d-mobile-none">${parseInt(d.target_durasi_menit ?? 30, 10)} mnt</td>
                                ${aksi}
                            </tr>`;
                    });
                })
                .catch(err => console.error('Error:', err));

            if (keyword === '') {
                location.reload();
            }
        });
    });
</script>
