<div class="container mt-4">
    <h4 class="fw-bold mb-3"><i class="fas fa-history me-1"></i> Riwayat Antrian</h4>

    <!-- Filter Form -->
    <form method="get" class="row gy-2 gx-3 align-items-center mb-3">
        <div class="col-auto">
            <input type="date" name="tanggal" value="<?= $tanggal ?>" class="form-control">
        </div>
        <!-- ... Filter Form -->
        <div class="col-auto">
            <input type="text" name="search" id="searchInput" value="<?= $search ?>" class="form-control" placeholder="Cari nama / nomor">
        </div>

        <div class="col-auto">
            <select name="limit" class="form-select">
                <option <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                <option <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                <option <?= $limit == 100 ? 'selected' : '' ?>>100</option>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary"><i class="fas fa-filter me-1"></i> Filter</button>
        </div>
    </form>

    <!-- Tabel Riwayat Antrian -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-primary">
                <tr class="text-center">
                    <th>#</th>
                    <th>Nomor</th>
                    <th>Nama</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th>Ubah Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = $start + 1;
                foreach ($antrian as $a): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="fw-bold"><?= $a->nomor_antrian ?></td>
                        <td><?= $a->nama_lengkap ?? '-' ?></td>
                        <td><?= $a->nama_layanan ?></td>
                        <td class="text-center">
                            <?php if ($a->status == 'selesai'): ?>
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                            <?php elseif ($a->status == 'dipanggil'): ?>
                                <span class="badge bg-warning text-dark"><i class="fas fa-bell me-1"></i> Dipanggil</span>
                            <?php elseif ($a->status == 'batal'): ?>
                                <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Batal</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><i class="fas fa-user-clock me-1"></i> Terdaftar</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <form action="<?= base_url('admin_layanan/update_status_antrian') ?>" method="post" class="d-flex">
                                <input type="hidden" name="id" value="<?= $a->id ?>">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="terdaftar" <?= $a->status == 'terdaftar' ? 'selected' : '' ?>>Terdaftar</option>
                                    <option value="dipanggil" <?= $a->status == 'dipanggil' ? 'selected' : '' ?>>Dipanggil</option>
                                    <option value="selesai" <?= $a->status == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($antrian)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

    <!-- Pagination -->
    <div class="mt-3">
        <?= $this->pagination->create_links(); ?>
    </div>
</div>


<script>
    document.querySelector('input[name="search"]').addEventListener('keyup', function() {
        const keyword = this.value.trim();
        if (keyword.length < 2 && keyword !== '') return;

        const tanggal = document.querySelector('input[name="tanggal"]').value;
        const tbody = document.querySelector('tbody');
        const pagination = document.querySelector('.pagination');

        fetch(`<?= base_url('admin_layanan/riwayat_antrian_ajax') ?>?tanggal=${tanggal}&search=${encodeURIComponent(keyword)}`)
            .then(res => res.text())
            .then(html => {
                tbody.innerHTML = html;
                if (pagination) pagination.style.display = 'none';
            });

        if (keyword === '') location.reload();
    });
</script>