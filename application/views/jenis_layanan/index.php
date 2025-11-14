<div class="container-fluid">
    <h4><?= $title ?></h4>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <?php if ($this->session->userdata('role_id') == 1): ?>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-primary" onclick="tambahData()">
                    <i class="ri-add-line"></i> Tambah
                </button>
                <input type="text" id="searchLayanan" class="form-control form-control-sm" placeholder="Cari layanan..." style="width:220px;">
            </div>
        <?php endif; ?>

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

    <table class="table table-bordered table-striped">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Instansi</th>
                <th>Kode</th>
                <th>Kode Huruf</th>
                <th>Nama Layanan</th>
                <th>Deskripsi</th>
                <?php if ($this->session->userdata('role_id') == 1): ?>
                    <th>Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>

        <tbody id="layananTableBody">
            <?php if (empty($jenis_layanan)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">Tidak ada data layanan.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($jenis_layanan as $i => $row): ?>
                    <tr>
                        <td class="text-center"><?= $start + $i + 1 ?></td>
                        <td><?= $row->nama_instansi ?? '-' ?></td>
                        <td class="text-center"><?= $row->kode ?></td>
                        <td class="text-center"><?= $row->kode_huruf ?></td>
                        <td><?= $row->nama_layanan ?></td>
                        <td><?= $row->deskripsi ?></td>
                        <?php if ($this->session->userdata('role_id') == 1): ?>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" onclick="editData(<?= $row->id ?>)">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteJenisLayanan(<?= $row->id ?>)">Hapus</button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div id="paginationContainer"><?= $pagination ?></div>
</div>

<!-- Modal -->
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
                    <div class="mb-2">
                        <label>Instansi</label>
                        <select name="instansi_id" id="instansi_id" class="form-select" required>
                            <option value="">-- Pilih Instansi --</option>
                            <?php foreach ($instansi as $ins): ?>
                                <option value="<?= $ins->id ?>"><?= $ins->nama_instansi ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="text" name="kode" class="form-control mb-2" placeholder="Kode" id="kode" required>
                    <input type="text" name="kode_huruf" class="form-control mb-2" placeholder="Kode Huruf" id="kode_huruf" required>
                    <input type="text" name="nama_layanan" class="form-control mb-2" placeholder="Nama Layanan" id="nama_layanan" required>
                    <textarea name="deskripsi" class="form-control" placeholder="Deskripsi" id="deskripsi"></textarea>
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
                    paginationContainer.style.display = 'none';

                    if (data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data ditemukan.</td></tr>`;
                        return;
                    }

                    data.forEach((d, i) => {
                        let aksi = '';
                        if (isSuperadmin) {
                            aksi = `
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning" onclick="editData(${d.id})">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteJenisLayanan(${d.id})">Hapus</button>
                                </td>`;
                        }

                        tbody.innerHTML += `
                            <tr>
                                <td class="text-center">${i + 1}</td>
                                <td>${d.nama_instansi ?? '-'}</td>
                                <td class="text-center">${d.kode ?? ''}</td>
                                <td class="text-center">${d.kode_huruf ?? ''}</td>
                                <td>${d.nama_layanan ?? ''}</td>
                                <td>${d.deskripsi ?? ''}</td>
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