<?php foreach ($antrian as $i => $a): ?>
    <tr>
        <td class="text-center"><?= $start + $i + 1 ?></td>
        <td class="fw-bold"><?= $a->nomor_antrian ?></td>
        <td><?= $a->nama_lengkap ?? '-' ?></td>
        <td><?= $a->nama_layanan ?? '-' ?></td>
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
                    <option value="batal" <?= $a->status == 'batal' ? 'selected' : '' ?>>Batal</option>
                </select>
            </form>
        </td>
    </tr>
<?php endforeach; ?>