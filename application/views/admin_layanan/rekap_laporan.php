<div class="container mt-4">
    <h4 class="fw-bold mb-3"><i class="fas fa-chart-bar me-1"></i> Rekap Laporan Antrian</h4>

    <form method="get" class="row gy-2 gx-3 align-items-center mb-3">
        <div class="col-auto">
            <label for="tanggal">Tanggal:</label>
            <input type="date" name="tanggal" value="<?= $tanggal ?>" class="form-control">
        </div>
        <div class="col-auto">
            <label>Limit:</label>
            <select name="limit" class="form-select">
                <option <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                <option <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                <option <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                <option <?= $limit == 100 ? 'selected' : '' ?>>100</option>
            </select>
        </div>
        <div class="col-auto mt-4">
            <button class="btn btn-primary"><i class="fas fa-filter me-1"></i> Tampilkan</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-primary text-center">
                <tr>
                    <th>#</th>
                    <th>Instansi</th>
                    <th>Total</th>
                    <th>Terdaftar</th>
                    <th>Dipanggil</th>
                    <th>Selesai</th>
                    <th>Tidak Hadir</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rekap)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Tidak ada data</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rekap as $i => $r): ?>
                        <tr class="text-center">
                            <td><?= $page * $limit - $limit + $i + 1 ?></td>
                            <td class="text-start"><?= $r->nama_instansi ?></td>
                            <td><?= $r->total ?></td>
                            <td><?= $r->terdaftar ?></td>
                            <td><?= $r->dipanggil ?></td>
                            <td><?= $r->selesai ?></td>
                            <td><?= $r->tidak_hadir ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <small class="text-muted">Menampilkan <?= count($rekap) ?> dari <?= $total_rows ?> instansi</small>
        <div><?= $this->pagination->create_links() ?></div>
    </div>
</div>