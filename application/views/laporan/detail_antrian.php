<?php
$hadir_count = 0;
$tidak_hadir_count = 0;
$selesai_count = 0;
foreach ($detail as $item) {
  if ((int) ($item->hadir ?? 0) === 1) {
    $hadir_count++;
  } else {
    $tidak_hadir_count++;
  }
  if (($item->status ?? '') === 'selesai') {
    $selesai_count++;
  }
}
$export_query = http_build_query([
  'start_date' => $start_date,
  'end_date' => $end_date,
  'instansi_id' => $instansi_id,
  'layanan_id' => $layanan_id
]);
?>
<?php $this->load->view('laporan/_report_theme'); ?>
<div class="container-fluid report-page">
  <div class="report-shell">
    <section class="report-hero">
      <div class="report-hero-grid">
        <div>
          <div class="report-eyebrow"><i class="ri ri-file-list-3-line"></i> Laporan Detail</div>
          <h1 class="report-title">Laporan Detail Antrian</h1>
          <p class="report-subtitle">Lihat seluruh nomor antrian dengan status kehadiran, progres layanan, dan waktu selesai pada periode tertentu tanpa tampilan yang berantakan.</p>
        </div>
        <div class="report-summary">
          <div class="report-summary-title">Konteks Tampilan</div>
          <div class="report-summary-list">
            <div class="report-summary-item">
              <span>Rentang tanggal</span>
              <strong><?= date('d M Y', strtotime($start_date)) ?> - <?= date('d M Y', strtotime($end_date)) ?></strong>
            </div>
            <div class="report-summary-item">
              <span>Total data</span>
              <strong><?= number_format((int) $total_rows) ?> antrian</strong>
            </div>
            <div class="report-summary-item">
              <span>Hak akses</span>
              <strong><?= $this->session->userdata('role_id') == 1 ? 'Lintas instansi' : 'Instansi sendiri' ?></strong>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="report-kpi-grid">
      <article class="report-kpi">
        <small>Data Ditampilkan</small>
        <strong><?= number_format(count($detail)) ?></strong>
        <span>Jumlah baris pada halaman aktif.</span>
      </article>
      <article class="report-kpi">
        <small>Hadir</small>
        <strong><?= number_format($hadir_count) ?></strong>
        <span>Antrian yang sudah check-in pada halaman ini.</span>
      </article>
      <article class="report-kpi">
        <small>Tidak Hadir</small>
        <strong><?= number_format($tidak_hadir_count) ?></strong>
        <span>Antrian yang belum tercatat hadir.</span>
      </article>
      <article class="report-kpi">
        <small>Selesai</small>
        <strong><?= number_format($selesai_count) ?></strong>
        <span>Layanan yang sudah selesai pada halaman ini.</span>
      </article>
    </section>

    <section class="report-card">
      <div class="report-card-header">
        <div>
          <h2 class="report-card-title">Filter Detail Antrian</h2>
          <div class="report-card-note">Filter instansi dan layanan hanya tampil sesuai hak akses akun yang sedang login.</div>
        </div>
        <div class="report-actions">
          <a href="<?= site_url('laporan/export_detail_antrian_excel?' . $export_query) ?>" class="report-btn-export">
            <i class="ri ri-file-excel-2-line me-1"></i> Download Excel
          </a>
        </div>
      </div>
      <div class="report-card-body">
        <form method="get" class="report-filter row g-3 align-items-end">
          <div class="col-lg-2 col-md-6">
            <label class="form-label">Tanggal Awal</label>
            <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control">
          </div>
          <div class="col-lg-2 col-md-6">
            <label class="form-label">Tanggal Akhir</label>
            <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control">
          </div>

          <?php if ($this->session->userdata('role_id') == 1): ?>
            <div class="col-lg-3 col-md-6">
              <label class="form-label">Instansi</label>
              <select name="instansi_id" class="form-select" onchange="this.form.submit()">
                <option value="">Semua instansi</option>
                <?php foreach ($instansi_list as $instansi): ?>
                  <option value="<?= $instansi->id ?>" <?= $instansi->id == $instansi_id ? 'selected' : '' ?>>
                    <?= $instansi->nama_instansi ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endif; ?>

          <div class="col-lg-3 col-md-6">
            <label class="form-label">Layanan</label>
            <select name="layanan_id" class="form-select">
              <option value="">Semua layanan</option>
              <?php foreach ($layanan_list as $layanan): ?>
                <option value="<?= $layanan->id ?>" <?= $layanan->id == $layanan_id ? 'selected' : '' ?>>
                  <?= $layanan->nama_layanan ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-lg-2 col-md-6">
            <label class="form-label">Baris</label>
            <select name="limit" class="form-select">
              <?php foreach ([25, 50, 100] as $l): ?>
                <option value="<?= $l ?>" <?= $limit == $l ? 'selected' : '' ?>><?= $l ?> baris</option>
              <?php endforeach; ?>
              <option value="0" <?= (int) $limit === 0 ? 'selected' : '' ?>>Semua data</option>
            </select>
          </div>

          <div class="col-lg-2 col-md-6 d-grid">
            <button class="btn btn-primary report-btn-primary">
              <i class="ri ri-search-line me-1"></i> Tampilkan
            </button>
          </div>
        </form>
      </div>
    </section>

    <section class="report-card">
      <div class="report-card-header">
        <div>
          <h2 class="report-card-title">Tabel Detail Antrian</h2>
          <div class="report-card-note">Urutan mengikuti hasil query laporan dan pagination tetap mempertahankan filter aktif.</div>
        </div>
      </div>
      <div class="report-card-body">
        <div class="table-responsive">
          <table class="table report-table align-middle">
            <thead>
              <tr>
                <th class="text-center col-nowrap">No</th>
                <th class="col-nowrap">Tanggal</th>
                <th class="col-name-min">Instansi</th>
                <th class="col-name-min">Layanan</th>
                <th class="col-name-min">Nama</th>
                <th class="text-center col-nowrap">Nomor</th>
                <th class="text-center col-nowrap">Hadir</th>
                <th class="text-center col-nowrap">Status</th>
                <th class="col-name-min">Hasil</th>
                <th class="text-center col-nowrap">Waktu Selesai</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($detail)): ?>
                <?php $no = $limit > 0 ? (1 + $limit * ($page - 1)) : 1; ?>
                <?php foreach ($detail as $d): ?>
                  <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= date('d/m/Y', strtotime($d->tanggal)) ?></td>
                    <td><?= html_escape($d->nama_instansi) ?></td>
                    <td><?= html_escape($d->nama_layanan) ?></td>
                    <td><?= html_escape($d->nama_lengkap) ?></td>
                    <td class="text-center"><strong><?= html_escape($d->nomor_antrian) ?></strong></td>
                    <td class="text-center">
                      <?php if ((int) $d->hadir === 1): ?>
                        <span class="report-pill success">Datang</span>
                      <?php else: ?>
                        <span class="report-pill warning">Tidak Hadir</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <?php
                      $status = strtolower((string) $d->status);
                      $status_class = 'neutral';
                      if ($status === 'selesai') $status_class = 'success';
                      elseif ($status === 'dipanggil') $status_class = 'info';
                      elseif ($status === 'batal') $status_class = 'danger';
                      elseif ($status === 'terdaftar') $status_class = 'warning';
                      ?>
                      <span class="report-pill <?= $status_class ?>"><?= ucfirst($status ?: '-') ?></span>
                    </td>
                    <td><?= $d->jenis_hasil ? html_escape($d->jenis_hasil) : '-' ?></td>
                    <td class="text-center"><?= $d->selesai_at ? date('H:i', strtotime($d->selesai_at)) : '-' ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="10" class="report-empty">Tidak ada data detail antrian pada periode ini.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <?php if ((int) $limit > 0 && !empty($pagination)): ?>
          <div class="report-pagination mt-4">
            <?= $pagination ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </div>
</div>
