<?php if (empty($antrian)): ?>
<tr>
  <td colspan="8" class="text-center text-muted">Belum ada antrian hari ini</td>
</tr>
<?php else: ?>
<?php $no = $offset + 1; foreach ($antrian as $a): ?>
<tr class="text-center">
  <td><?= $no++ ?></td>
  <td class="fw-bold"><?= $a->nomor_antrian ?></td>
  <td class="text-start"><?= $a->nama_lengkap ?? '-' ?></td>
  <td><?= $a->no_hp ?? '-' ?></td>
  <td><?= $a->nama_layanan ?? '-' ?></td>

  <td><?= $a->hadir ? '<span class="badge bg-success">Hadir</span>' : '<span class="badge bg-secondary">Belum</span>' ?></td>

  <td>
    <?php
      $badge = 'secondary';
      if ($a->status === 'dipanggil') $badge = 'warning text-dark';
      elseif ($a->status === 'selesai') $badge = 'success';
      elseif ($a->status === 'batal') $badge = 'danger';
    ?>
    <span class="badge bg-<?= $badge ?>">
      <?= ucfirst($a->status) ?>
    </span>
  </td>

<td>
  <div class="aksi-wrap">

    <?php if ($a->status === 'dipanggil' && (int) $a->hadir === 1): ?>

      <a href="javascript:void(0)" data-endpoint="<?= site_url('admin_layanan/panggil/') . (int) $a->id ?>"
              class="btn btn-sm btn-warning btn-panggil"
              data-action="panggil"
              onclick="event.preventDefault();event.stopPropagation();if(window.runQueueAction){window.runQueueAction(this);}return false;"
              data-id="<?= (int) $a->id ?>"
             >
        <i class="bi bi-megaphone"></i> Panggil Ulang
      </a>

    <?php elseif ($a->status === 'terdaftar'): ?>

      <a href="javascript:void(0)" data-endpoint="<?= site_url('admin_layanan/panggil/') . (int) $a->id ?>"
              class="btn btn-sm btn-warning btn-panggil"
              data-action="panggil"
              onclick="event.preventDefault();event.stopPropagation();if(window.runQueueAction){window.runQueueAction(this);}return false;"
              data-id="<?= (int) $a->id ?>"
             >
        <i class="bi bi-megaphone"></i> <?= (int) $a->hadir === 1 ? 'Panggil' : 'Panggil (Auto Check-In)' ?>
      </a>

    <?php endif; ?>

    <?php if ($a->status === 'terdaftar'): ?>

      <a href="javascript:void(0)" data-endpoint="<?= site_url('admin_layanan/batal/') . (int) $a->id ?>"
              class="btn btn-sm btn-outline-danger btn-batal"
              data-action="batal"
              onclick="event.preventDefault();event.stopPropagation();if(window.runQueueAction){window.runQueueAction(this);}return false;"
              data-id="<?= (int) $a->id ?>"
             >
        <i class="bi bi-x-circle"></i> Batal
      </a>

    <?php elseif ($a->status === 'dipanggil'): ?>

      <a href="javascript:void(0)" data-endpoint="<?= site_url('admin_layanan/selesai/') . (int) $a->id ?>"
              class="btn btn-sm btn-success btn-selesai"
              data-action="selesai"
              onclick="event.preventDefault();event.stopPropagation();if(window.runQueueAction){window.runQueueAction(this);}return false;"
              data-id="<?= (int) $a->id ?>"
             >
        <i class="bi bi-check2-circle"></i> Selesai
      </a>

    <?php endif; ?>

  </div>
</td>

</tr>
<?php endforeach; ?>
<?php endif; ?>
