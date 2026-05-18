<?php if (empty($antrian)): ?>
<tr>
  <td colspan="8" class="text-center text-muted">Data tidak ditemukan</td>
</tr>
<?php else: ?>
<?php
$ada_dipanggil = false;
foreach ($antrian as $_a) {
    if ($_a->status === 'dipanggil') { $ada_dipanggil = true; break; }
}
?>
<?php $no = $offset + 1; foreach ($antrian as $a): ?>
<tr class="text-center">
  <td><?= $no++ ?></td>
  <td class="fw-bold"><?= $a->nomor_antrian ?></td>
  <td class="text-start"><?= $a->nama_lengkap ?? '-' ?></td>
  <td><?= $a->no_hp ?? '-' ?></td>
  <td><?= $a->nama_layanan ?? '-' ?></td>

  <td><?= $a->hadir ? '<span class="badge bg-success">Hadir</span>' : '<span class="badge bg-secondary">Belum</span>' ?></td>

  <td>
    <span class="badge bg-<?= $a->status === 'dipanggil' ? 'warning text-dark' : 'secondary' ?>">
      <?= ucfirst($a->status) ?>
    </span>
  </td>

<td>
  <div class="d-flex flex-wrap justify-content-center gap-1">

    <?php if ($a->status === 'dipanggil' && (int) $a->hadir === 1): ?>

      <!-- 🔊 PANGGIL ULANG (antrian aktif) -->
      <a href="<?= base_url('admin_layanan/panggil_sync/'.$a->id) ?>"
              class="btn btn-sm btn-warning btn-panggil"
              onclick="return confirm('Panggil ulang antrian ini?')">
        <i class="bi bi-megaphone"></i> Panggil Ulang
      </a>

    <?php elseif ($a->status === 'terdaftar'): ?>

      <!-- 🔊 PANGGIL -->
      <a href="<?= base_url('admin_layanan/panggil_sync/'.$a->id) ?>"
              class="btn btn-sm btn-warning btn-panggil"
              onclick="return confirm('Panggil antrian ini?')">
        <i class="bi bi-megaphone"></i> <?= (int) $a->hadir === 1 ? 'Panggil' : 'Panggil (Auto Check-In)' ?>
      </a>

    <?php endif; ?>

    <?php if ($a->status === 'terdaftar'): ?>

      <!-- ❌ BATAL -->
      <a href="<?= base_url('admin_layanan/batal/'.$a->id) ?>"
              class="btn btn-sm btn-outline-danger btn-batal"
              onclick="return confirm('Batalkan antrian ini?')">
        <i class="bi bi-x-circle"></i> Batal
      </a>

    <?php elseif ($a->status === 'dipanggil'): ?>

      <!-- ✅ SELESAI -->
      <a href="<?= base_url('admin_layanan/selesai/'.$a->id) ?>"
              class="btn btn-sm btn-success btn-selesai"
              onclick="return confirm('Selesaikan antrian ini?')">
        <i class="bi bi-check2-circle"></i> Selesai
      </a>

    <?php endif; ?>

  </div>
</td>

</tr>
<?php endforeach; ?>
<?php endif; ?>
