<div class="container-fluid px-4 mt-4">
    <h4 class="text-maroon"><i class="fas fa-history me-2"></i><?= $title ?></h4>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-maroon text-white">
                        <tr>
                            <th>No</th>
                            <th>Nomor Antrian</th> <!-- Kolom baru -->
                            <th>Layanan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($antrian)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada antrian.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1;
                            foreach ($antrian as $a): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= $a->nomor_antrian ?></strong></td> <!-- Kolom baru -->
                                    <td><?= $a->nama_layanan ?></td>
                                    <td><?= date('d M Y', strtotime($a->tanggal)) ?></td>
                                    <td>
                                        <?php if ($a->status == 'terdaftar'): ?>
                                            <span class="badge bg-primary">Terdaftar</span>
                                        <?php elseif ($a->status == 'dipanggil'): ?>
                                            <span class="badge bg-warning text-dark">Dipanggil</span>
                                        <?php elseif ($a->status == 'selesai'): ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Batal</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (in_array($a->status, ['terdaftar', 'dipanggil'])): ?>
                                            <a href="<?= base_url('masyarakat/batalkan_antrian/' . $a->id) ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin ingin membatalkan antrian ini?')">
                                                <i class="fas fa-times"></i> Batalkan
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>