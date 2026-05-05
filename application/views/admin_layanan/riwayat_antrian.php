<div class="container-fluid px-4 mt-4">
    <div class="portal-page">
        <section class="portal-page-intro">
            <div>
                <div class="portal-page-eyebrow"><i class="ri ri-history-line"></i> Histori Operasional</div>
                <h1 class="portal-page-title">Riwayat Antrian</h1>
                <p class="portal-page-subtitle">
                    Telusuri hasil layanan per tanggal, ubah status jika dibutuhkan, dan cari nomor atau nama masyarakat dengan lebih cepat.
                </p>
            </div>
        </section>

        <section class="portal-filter-card">
            <form method="get" class="row gy-2 gx-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" value="<?= $tanggal_awal ?>" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" value="<?= $tanggal_akhir ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="search" value="<?= $search ?>" class="form-control" placeholder="Cari nama / nomor / layanan">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Baris</label>
                    <select name="limit" class="form-select">
                        <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                        <option value="0" <?= $limit == 0 ? 'selected' : '' ?>>Semua</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                </div>
            </form>
            <div class="portal-card-note mt-3">
                Default menampilkan data bulan berjalan sampai hari ini. Urutan riwayat disusun dari tanggal dan nomor terbaru paling atas.
            </div>
        </section>

        <section class="card portal-section-card portal-table-card">
            <div class="card-header">
                <h5 class="portal-section-title">Data Riwayat</h5>
                <div class="portal-card-note">Menampilkan <?= (int) ($total_rows ?? 0) ?> data pada rentang tanggal yang dipilih.</div>
            </div>
            <div class="card-body">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-primary">
                <tr class="text-center">
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Nomor</th>
                    <th>Nama</th>
                    <th>Layanan</th>
                    <th>Hadir</th>
                    <th>Status</th>
                    <th>Ubah Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = $start + 1;
                foreach ($antrian as $a): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center"><?= date('d-m-Y', strtotime($a->tanggal)) ?></td>
                        <td class="text-center"><?= !empty($a->created_at) ? date('H:i', strtotime($a->created_at)) : '-' ?></td>
                        <td class="fw-bold"><?= $a->nomor_antrian ?></td>
                        <td><?= $a->nama_lengkap ?? '-' ?></td>
                        <td><?= $a->nama_layanan ?></td>
                        <td class="text-center">
                            <?php if ((int) ($a->hadir ?? 0) === 1): ?>
                                <span class="badge bg-success"><i class="fas fa-user-check me-1"></i> Hadir</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><i class="fas fa-user-clock me-1"></i> Tidak Hadir</span>
                            <?php endif; ?>
                        </td>
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
                        <td colspan="9" class="text-center text-muted">Tidak ada data pada rentang tanggal ini</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

    <!-- Pagination -->
    <div class="mt-3">
        <?= $pagination_links ?? '' ?>
    </div>
            </div>
        </section>
    </div>
</div>
