<?php if (empty($antrian)): ?>
    <tr>
        <td colspan="8" class="text-center text-muted">Data tidak ditemukan</td>
    </tr>
<?php else: ?>
    <?php $no = $offset + 1;
    foreach ($antrian as $a): ?>
        <tr class="text-center">
            <td><?= $no++ ?></td>
            <td class="fw-bold"><?= $a->nomor_antrian ?></td>
            <td class="text-start"><?= $a->nama_lengkap ?? '-' ?></td>
            <td><?= $a->no_hp ?? '-' ?></td>
            <td><?= $a->nama_layanan ?? '-' ?></td>
            <td>
                <?php if ($a->hadir): ?>
                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Hadir</span>
                <?php else: ?>
                    <span class="badge bg-secondary"><i class="bi bi-clock"></i> Belum</span>
                <?php endif; ?>
            </td>
            <td>
                <?php
                $cls = $a->status == 'selesai' ? 'success' : ($a->status == 'batal' ? 'danger' : ($a->status == 'dipanggil' ? 'warning text-dark' : 'secondary'));
                ?>
                <span class="badge bg-<?= $cls ?>"><?= ucfirst($a->status) ?></span>
            </td>
            <td>
                <div class="d-flex flex-wrap justify-content-center gap-1">
                    <?php if ($a->status == 'terdaftar'): ?>
                        <button class="btn btn-sm btn-warning text-dark btn-panggil" data-id="<?= $a->id ?>">
                            <i class="bi bi-megaphone"></i> Panggil
                        </button>
                        <a href="#" class="btn btn-sm btn-outline-danger btn-batal"
                            data-url="<?= base_url('admin_layanan/batal/' . $a->id) ?>">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    <?php elseif ($a->status == 'dipanggil'): ?>
                        <a href="#" class="btn btn-sm btn-success btn-selesai"
                            data-url="<?= base_url('admin_layanan/selesai/' . $a->id) ?>">
                            <i class="bi bi-check2-circle"></i> Selesai
                        </a>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>