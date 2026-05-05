<div class="container-fluid px-4 mt-4">
    <div class="portal-page">
        <section class="portal-page-intro">
            <div>
                <div class="portal-page-eyebrow"><i class="ri ri-government-line"></i> Pengaturan Instansi</div>
                <h1 class="portal-page-title"><?= html_escape($title) ?></h1>
                <p class="portal-page-subtitle">
                    Atur identitas instansi, sektor display, loket, dan jam operasional layanan dari satu halaman yang lebih mudah dipindai.
                </p>
            </div>
            <div class="portal-inline-metrics">
                <div class="portal-inline-metric">
                    <small>Total instansi</small>
                    <strong><?= (int) $total_rows ?></strong>
                </div>
            </div>
        </section>

        <section class="portal-filter-card">
            <div class="portal-toolbar">
                <div class="portal-toolbar-actions">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="ri-building-line me-1"></i> Tambah Instansi
                    </button>
                    <input type="text" id="searchInstansi" class="form-control" placeholder="Cari instansi..." style="width:min(100%, 260px);">
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
            <div class="card-header">
                <h5 class="portal-section-title">Daftar Instansi</h5>
                <div class="portal-card-note">Jam operasional dan mode status layanan ditampilkan langsung agar mudah diaudit.</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-scroll-x" id="instansiTable">
            <thead class="table-light">
                <tr class="text-center">
                    <th class="col-nowrap" style="width:3rem">#</th>
                    <th class="col-nowrap">Kode</th>
                    <th class="col-text-min">Nama Instansi</th>
                    <th class="d-mobile-none col-name-min">Sektor</th>
                    <th class="d-mobile-none col-nowrap">Status Layanan</th>
                    <th class="col-nowrap">Status Aktif</th>
                    <th class="col-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody id="instansiTableBody">
                <?php foreach ($instansi as $i => $ins): ?>
                    <tr>
                        <td class="text-center"><?= $start + $i + 1 ?></td>
                        <td><?= $ins->kode_instansi ?></td>
                        <td><?= $ins->nama_instansi ?></td>
                        <td class="d-mobile-none"><?= $ins->nama_sektor ?? '-' ?></td>
                        <td class="text-center d-mobile-none">
                            <span class="badge bg-<?= $ins->status_layanan === 'buka' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($ins->status_layanan) ?>
                            </span>
                            <div><small class="text-muted"><?= ucfirst($ins->status_layanan_mode ?? 'otomatis') ?></small></div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-<?= $ins->is_aktif ? 'primary' : 'danger' ?>">
                                <?= $ins->is_aktif ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </td>
                        <td>
                            <div class="table-action-group">
                            <button class="btn btn-sm btn-info text-white btn-detail"
                                data-kode="<?= htmlspecialchars($ins->kode_instansi ?? '') ?>"
                                data-nama="<?= htmlspecialchars($ins->nama_instansi ?? '') ?>"
                                data-sektor="<?= htmlspecialchars($ins->nama_sektor ?? '-') ?>"
                                data-deskripsi="<?= htmlspecialchars($ins->deskripsi ?? '') ?>"
                                data-loket="<?= htmlspecialchars($ins->loket ?? '-') ?>"
                                data-jam-tutup-pendaftaran="<?= substr($ins->jam_tutup_pendaftaran ?? '15:30:00', 0, 5) ?>"
                                data-jam-layanan-mulai="<?= substr($ins->jam_layanan_mulai ?? '08:30:00', 0, 5) ?>"
                                data-jam-layanan-selesai="<?= substr($ins->jam_layanan_selesai ?? '16:00:00', 0, 5) ?>"
                                data-jam-tutup-kantor="<?= substr($ins->jam_tutup_kantor ?? '16:30:00', 0, 5) ?>"
                                data-status-layanan="<?= ucfirst($ins->status_layanan ?? '-') ?>"
                                data-status-mode="<?= ucfirst($ins->status_layanan_mode ?? 'otomatis') ?>"
                                data-status-aktif="<?= $ins->is_aktif ? 'Aktif' : 'Nonaktif' ?>"
                                data-bs-toggle="modal" data-bs-target="#detailInstansiModal">
                                <i class="ri-eye-line"></i> <span class="btn-label">Detail</span>
                            </button>
                            <button class="btn btn-sm btn-warning btn-edit"
                                data-id="<?= $ins->id ?>"
                                data-kode="<?= $ins->kode_instansi ?>"
                                data-nama="<?= $ins->nama_instansi ?>"
                                data-sektor_id="<?= $ins->sektor_id ?>"
                                data-deskripsi="<?= $ins->deskripsi ?>"
                                data-loket="<?= $ins->loket ?>"
                                data-status_mode="<?= $ins->status_layanan_mode ?? 'otomatis' ?>"
                                data-jam_tutup_pendaftaran="<?= substr($ins->jam_tutup_pendaftaran ?? '15:30:00', 0, 5) ?>"
                                data-jam_layanan_mulai="<?= substr($ins->jam_layanan_mulai ?? '08:30:00', 0, 5) ?>"
                                data-jam_layanan_selesai="<?= substr($ins->jam_layanan_selesai ?? '16:00:00', 0, 5) ?>"
                                data-jam_tutup_kantor="<?= substr($ins->jam_tutup_kantor ?? '16:30:00', 0, 5) ?>"
                                data-aktif="<?= $ins->is_aktif ?>"
                                data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="ri-edit-line"></i> <span class="btn-label">Edit</span>
                            </button>
                            <a href="<?= base_url('superadmin/instansi_delete/' . $ins->id) ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Hapus instansi ini?')">
                                <i class="ri-delete-bin-line"></i> <span class="btn-label">Hapus</span>
                            </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                    <small class="text-muted">Menampilkan <?= count($instansi) ?> dari <?= $total_rows ?> data</small>
                    <div id="paginationContainer"><?= $pagination ?></div>
                </div>
            </div>
        </section>
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
                <div class="mb-3">
                    <label>Sektor Display</label>
                    <select name="sektor_id" class="form-select" required>
                        <option value="">Pilih Sektor</option>
                        <?php foreach ($sektor_list as $s): ?>
                            <option value="<?= $s->id ?>"><?= $s->nama_sektor ?> (<?= $s->kode_sektor ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"></textarea></div>
                <div class="mb-3"><label>Loket</label><input name="loket" class="form-control" placeholder="Contoh: Loket A / Front Desk"></div>
                <div class="mb-3">
                    <label>Jam Tutup Pendaftaran Online</label>
                    <input name="jam_tutup_pendaftaran" type="time" class="form-control" value="15:30" required>
                </div>
                <div class="mb-3">
                    <label>Jam Layanan Mulai</label>
                    <input name="jam_layanan_mulai" type="time" class="form-control" value="08:30" required>
                </div>
                <div class="mb-3">
                    <label>Jam Layanan Selesai</label>
                    <input name="jam_layanan_selesai" type="time" class="form-control" value="16:00" required>
                </div>
                <div class="mb-3">
                    <label>Jam Tutup Kantor</label>
                    <input name="jam_tutup_kantor" type="time" class="form-control" value="16:30" required>
                </div>
                <div class="mb-3">
                    <label>Mode Status Layanan</label>
                    <select name="status_layanan_mode" class="form-select">
                        <option value="otomatis">Otomatis</option>
                        <option value="buka">Paksa buka</option>
                        <option value="tutup">Paksa tutup</option>
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
                <div class="mb-3">
                    <label>Sektor Display</label>
                    <select name="sektor_id" class="form-select" required>
                        <option value="">Pilih Sektor</option>
                        <?php foreach ($sektor_list as $s): ?>
                            <option value="<?= $s->id ?>"><?= $s->nama_sektor ?> (<?= $s->kode_sektor ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"></textarea></div>
                <div class="mb-3"><label>Loket</label><input name="loket" class="form-control" placeholder="Contoh: Loket A / Front Desk"></div>
                <div class="mb-3">
                    <label>Jam Tutup Pendaftaran Online</label>
                    <input name="jam_tutup_pendaftaran" type="time" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Jam Layanan Mulai</label>
                    <input name="jam_layanan_mulai" type="time" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Jam Layanan Selesai</label>
                    <input name="jam_layanan_selesai" type="time" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Jam Tutup Kantor</label>
                    <input name="jam_tutup_kantor" type="time" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Mode Status Layanan</label>
                    <select name="status_layanan_mode" class="form-select">
                        <option value="otomatis">Otomatis</option>
                        <option value="buka">Paksa buka</option>
                        <option value="tutup">Paksa tutup</option>
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

<!-- Modal Detail -->
<div class="modal fade" id="detailInstansiModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Instansi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4"><small class="text-muted d-block">Kode</small><strong id="d_kode">-</strong></div>
                    <div class="col-md-8"><small class="text-muted d-block">Nama Instansi</small><strong id="d_nama">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Sektor</small><strong id="d_sektor">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Loket</small><strong id="d_loket">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Status Layanan</small><strong id="d_status_layanan">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Mode Status</small><strong id="d_status_mode">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Status Aktif</small><strong id="d_status_aktif">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Jam Tutup Pendaftaran</small><strong id="d_jam_tutup_pendaftaran">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Jam Layanan</small><strong id="d_jam_layanan">-</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Jam Tutup Kantor</small><strong id="d_jam_tutup_kantor">-</strong></div>
                    <div class="col-12"><small class="text-muted d-block">Deskripsi</small><strong id="d_deskripsi">-</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function bindEditButtons() {
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const f = document.getElementById('editForm');
                f.action = '<?= base_url('superadmin/instansi_edit/') ?>' + this.dataset.id;
                f.kode_instansi.value = this.dataset.kode;
                f.nama_instansi.value = this.dataset.nama;
                f.sektor_id.value = this.dataset.sektor_id || '';
                f.deskripsi.value = this.dataset.deskripsi;
                f.loket.value = this.dataset.loket;
                f.jam_tutup_pendaftaran.value = this.dataset.jam_tutup_pendaftaran;
                f.jam_layanan_mulai.value = this.dataset.jam_layanan_mulai;
                f.jam_layanan_selesai.value = this.dataset.jam_layanan_selesai;
                f.jam_tutup_kantor.value = this.dataset.jam_tutup_kantor;
                f.status_layanan_mode.value = this.dataset.status_mode;
                f.is_aktif.value = this.dataset.aktif;
            });
        });
    }

    function bindDetailButtons() {
        const setText = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value && String(value).trim() !== '' ? value : '-';
        };

        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', function() {
                setText('d_kode', this.dataset.kode);
                setText('d_nama', this.dataset.nama);
                setText('d_sektor', this.dataset.sektor);
                setText('d_deskripsi', this.dataset.deskripsi);
                setText('d_loket', this.dataset.loket);
                setText('d_status_layanan', this.dataset.statusLayanan);
                setText('d_status_mode', this.dataset.statusMode);
                setText('d_status_aktif', this.dataset.statusAktif);
                setText('d_jam_tutup_pendaftaran', this.dataset.jamTutupPendaftaran);
                setText('d_jam_layanan', `${this.dataset.jamLayananMulai} - ${this.dataset.jamLayananSelesai}`);
                setText('d_jam_tutup_kantor', this.dataset.jamTutupKantor);
            });
        });
    }

    bindEditButtons();
    bindDetailButtons();

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
                    tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>`;
                    return;
                }

                data.forEach((i, idx) => {
                    const statusLayanan = (i.status_layanan ?? '').toLowerCase() === 'buka' ? 'success' : 'secondary';
                    const statusAktif = Number(i.is_aktif) === 1 ? 'primary' : 'danger';
                    const statusMode = i.status_layanan_mode ?? 'otomatis';
                    tbody.innerHTML += `
          <tr>
            <td class="text-center">${idx + 1}</td>
            <td>${i.kode_instansi ?? ''}</td>
            <td>${i.nama_instansi ?? ''}</td>
            <td>${i.nama_sektor ?? '-'}</td>
            <td class="text-center">
              <span class="badge bg-${statusLayanan}">${(i.status_layanan ?? '-').charAt(0).toUpperCase() + (i.status_layanan ?? '-').slice(1)}</span>
              <div><small class="text-muted">${(statusMode.charAt(0).toUpperCase() + statusMode.slice(1))}</small></div>
            </td>
            <td class="text-center"><span class="badge bg-${statusAktif}">${Number(i.is_aktif) === 1 ? 'Aktif' : 'Nonaktif'}</span></td>
            <td class="text-center">
              <button class="btn btn-sm btn-info text-white btn-detail"
                data-kode="${i.kode_instansi ?? ''}"
                data-nama="${i.nama_instansi ?? ''}"
                data-sektor="${i.nama_sektor ?? '-'}"
                data-deskripsi="${i.deskripsi ?? ''}"
                data-loket="${i.loket ?? '-'}"
                data-jam-tutup-pendaftaran="${String(i.jam_tutup_pendaftaran ?? '15:30:00').substring(0,5)}"
                data-jam-layanan-mulai="${String(i.jam_layanan_mulai ?? '08:30:00').substring(0,5)}"
                data-jam-layanan-selesai="${String(i.jam_layanan_selesai ?? '16:00:00').substring(0,5)}"
                data-jam-tutup-kantor="${String(i.jam_tutup_kantor ?? '16:30:00').substring(0,5)}"
                data-status-layanan="${(i.status_layanan ?? '-').charAt(0).toUpperCase() + (i.status_layanan ?? '-').slice(1)}"
                data-status-mode="${(statusMode.charAt(0).toUpperCase() + statusMode.slice(1))}"
                data-status-aktif="${Number(i.is_aktif) === 1 ? 'Aktif' : 'Nonaktif'}"
                data-bs-toggle="modal" data-bs-target="#detailInstansiModal">
                <i class="ri-eye-line"></i> Detail
              </button>
              <button class="btn btn-sm btn-warning btn-edit"
                data-id="${i.id}"
                data-kode="${i.kode_instansi ?? ''}"
                data-nama="${i.nama_instansi ?? ''}"
                data-sektor_id="${i.sektor_id ?? ''}"
                data-deskripsi="${i.deskripsi ?? ''}"
                data-loket="${i.loket ?? ''}"
                data-status_mode="${statusMode}"
                data-jam_tutup_pendaftaran="${String(i.jam_tutup_pendaftaran ?? '15:30:00').substring(0,5)}"
                data-jam_layanan_mulai="${String(i.jam_layanan_mulai ?? '08:30:00').substring(0,5)}"
                data-jam_layanan_selesai="${String(i.jam_layanan_selesai ?? '16:00:00').substring(0,5)}"
                data-jam_tutup_kantor="${String(i.jam_tutup_kantor ?? '16:30:00').substring(0,5)}"
                data-aktif="${Number(i.is_aktif) === 1 ? 1 : 0}"
                data-bs-toggle="modal" data-bs-target="#editModal">
                <i class="ri-edit-line"></i> Edit
              </button>
              <a href="<?= base_url('superadmin/instansi_delete/') ?>${i.id}" class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i> Hapus</a>
            </td>
          </tr>`;
                });
                bindEditButtons();
                bindDetailButtons();
            });

        if (keyword === '') location.reload();
    });
</script>
