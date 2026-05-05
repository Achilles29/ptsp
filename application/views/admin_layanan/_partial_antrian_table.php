<?php if (empty($antrian)): ?>
<tr>
  <td colspan="8" class="text-center text-muted">Belum ada antrian hari ini</td>
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
  <td><?= $a->nama_lengkap ?? '-' ?></td>
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
      <button type="button"
              class="btn btn-sm btn-warning btn-panggil"
              data-id="<?= $a->id ?>">
        <i class="bi bi-megaphone"></i> Panggil Ulang
      </button>

    <?php elseif ($a->status === 'terdaftar' && (int) $a->hadir === 1 && !$ada_dipanggil): ?>

      <!-- 🔊 PANGGIL (tidak ada antrian aktif) -->
      <button type="button"
              class="btn btn-sm btn-warning btn-panggil"
              data-id="<?= $a->id ?>">
        <i class="bi bi-megaphone"></i> Panggil
      </button>

    <?php elseif ($a->status === 'terdaftar' && (int) $a->hadir === 1 && $ada_dipanggil): ?>

      <!-- 🔒 Terkunci karena ada antrian sedang aktif -->
      <span class="btn btn-sm btn-outline-warning disabled">
        <i class="bi bi-lock"></i> Ada aktif
      </span>

    <?php endif; ?>

    <?php if ($a->status === 'terdaftar' && (int) $a->hadir !== 1): ?>
      <span class="btn btn-sm btn-outline-secondary disabled">
        <i class="bi bi-clock-history"></i> Menunggu Check-In
      </span>
    <?php endif; ?>

    <?php if ($a->status === 'terdaftar'): ?>

      <!-- ❌ BATAL -->
      <button type="button"
              class="btn btn-sm btn-outline-danger btn-batal"
              data-url="<?= base_url('admin_layanan/batal/'.$a->id) ?>">
        <i class="bi bi-x-circle"></i> Batal
      </button>

    <?php elseif ($a->status === 'dipanggil'): ?>

      <!-- ✅ SELESAI -->
      <button type="button"
              class="btn btn-sm btn-success btn-selesai"
              data-id="<?= $a->id ?>">
        <i class="bi bi-check2-circle"></i> Selesai
      </button>

    <?php endif; ?>

  </div>
</td>

</tr>
<?php endforeach; ?>
<?php endif; ?>
