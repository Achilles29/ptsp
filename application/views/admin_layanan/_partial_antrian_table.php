<?php foreach ($antrian as $i => $a): ?>
    <tr class="text-center">
        <td><?= $i + 1 ?></td>
        <td class="fw-bold"><?= $a->nomor_antrian ?></td>
        <td class="text-start"><?= $a->nama_lengkap ?></td>
        <td><?= $a->no_hp ?></td>
        <td><?= $a->nama_layanan ?? '-' ?></td>
        <td>
            <?php if ($a->status == 'terdaftar'): ?>
                <span class="badge bg-secondary">Terdaftar</span>
            <?php elseif ($a->status == 'dipanggil'): ?>
                <span class="badge bg-warning text-dark">Dipanggil</span>
            <?php elseif ($a->status == 'selesai'): ?>
                <span class="badge bg-success">Selesai</span>
            <?php else: ?>
                <span class="badge bg-danger">Batal</span>
            <?php endif; ?>
        </td>
        <td>
            <div class="d-flex flex-wrap justify-content-center gap-1">
                <?php if ($a->status == 'terdaftar'): ?>
                    <button class="btn btn-sm btn-success btn-panggil" data-id="<?= $a->id ?>">
                        <i class="bi bi-megaphone"></i> Panggil
                    </button>
                    <a href="#" class="btn btn-sm btn-outline-danger btn-loading btn-batal"
                        data-url="<?= base_url('admin_layanan/batal/' . $a->id) ?>">
                        <i class="fas fa-times me-1"></i> Batal
                        <span class="spinner-border spinner-border-sm text-light ms-1"></span>
                    </a>
                <?php elseif ($a->status == 'dipanggil'): ?>
                    <a href="#" class="btn btn-sm btn-success btn-loading btn-selesai"
                        data-url="<?= base_url('admin_layanan/selesai/' . $a->id) ?>">
                        <i class="fas fa-check me-1"></i> Selesai
                        <span class="spinner-border spinner-border-sm text-light ms-1"></span>
                    </a>
                <?php endif; ?>
            </div>
        </td>
    </tr>
<?php endforeach; ?>