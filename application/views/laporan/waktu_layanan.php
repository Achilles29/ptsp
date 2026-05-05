<?php
$durations = [];
foreach ($data as $row) {
  if (!empty($row->durasi) && preg_match('/^\d{2}:\d{2}:\d{2}$/', $row->durasi)) {
    [$h, $m, $s] = array_map('intval', explode(':', $row->durasi));
    $durations[] = ($h * 3600) + ($m * 60) + $s;
  }
}
$avg_duration = '-';
if (!empty($durations)) {
  $avg = (int) round(array_sum($durations) / count($durations));
  $avg_duration = gmdate('H:i:s', $avg);
}
$max_duration = !empty($durations) ? gmdate('H:i:s', max($durations)) : '-';
?>
<?php $this->load->view('laporan/_report_theme'); ?>
<div class="container-fluid report-page">
  <div class="report-shell">
    <section class="report-hero">
      <div class="report-hero-grid">
        <div>
          <div class="report-eyebrow"><i class="ri ri-timer-line"></i> Analitik Durasi</div>
          <h1 class="report-title">Laporan Waktu Layanan</h1>
          <p class="report-subtitle">Evaluasi kecepatan pelayanan berdasarkan waktu panggil dan waktu selesai agar instansi bisa melihat ritme kerja petugas dengan lebih jelas.</p>
        </div>
        <div class="report-summary">
          <div class="report-summary-title">Ringkasan Periode</div>
          <div class="report-summary-list">
            <div class="report-summary-item">
              <span>Periode aktif</span>
              <strong><?= date('d M Y', strtotime($start_date)) ?> - <?= date('d M Y', strtotime($end_date)) ?></strong>
            </div>
            <div class="report-summary-item">
              <span>Batas tampilan</span>
              <strong><?= (int) $limit === 0 ? 'Semua data' : (int) $limit . ' data terbaru' ?></strong>
            </div>
            <div class="report-summary-item">
              <span>Total baris</span>
              <strong><?= number_format(count($data)) ?> data</strong>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="report-kpi-grid">
      <article class="report-kpi">
        <small>Data Tampil</small>
        <strong><?= number_format(count($data)) ?></strong>
        <span>Jumlah antrian selesai yang sedang ditampilkan.</span>
      </article>
      <article class="report-kpi">
        <small>Rata-rata Durasi</small>
        <strong><?= $avg_duration ?></strong>
        <span>Rata-rata lama pelayanan dari data yang punya waktu lengkap.</span>
      </article>
      <article class="report-kpi">
        <small>Durasi Terlama</small>
        <strong><?= $max_duration ?></strong>
        <span>Kasus paling lama dalam hasil tampilan saat ini.</span>
      </article>
      <article class="report-kpi">
        <small>Data Lengkap</small>
        <strong><?= number_format(count($durations)) ?></strong>
        <span>Baris yang memiliki waktu panggil dan selesai valid.</span>
      </article>
    </section>

    <section class="report-card">
      <div class="report-card-header">
        <div>
          <h2 class="report-card-title">Filter Durasi Layanan</h2>
          <div class="report-card-note">Cocok untuk audit beban kerja, konsistensi petugas, dan analisis bottleneck layanan.</div>
        </div>
        <div class="report-actions">
          <a href="<?= site_url('laporan/export_durasi_layanan_excel?start_date=' . urlencode($start_date) . '&end_date=' . urlencode($end_date) . '&instansi_id=' . urlencode((string) $instansi_id)) ?>" class="report-btn-export">
            <i class="ri ri-file-excel-2-line me-1"></i> Download Excel
          </a>
        </div>
      </div>
      <div class="report-card-body">
        <form method="get" class="report-filter row g-3 align-items-end">
          <div class="col-lg-3 col-md-6">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control">
          </div>
          <div class="col-lg-3 col-md-6">
            <label class="form-label">Tanggal Selesai</label>
            <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control">
          </div>
          <?php if ($this->session->userdata('role_id') == 1): ?>
            <div class="col-lg-2 col-md-6">
              <label class="form-label">Instansi</label>
              <select name="instansi_id" class="form-select">
                <option value="">Semua instansi</option>
                <?php foreach ($instansi_list as $instansi): ?>
                  <option value="<?= (int) $instansi->id ?>" <?= (int) $instansi_id === (int) $instansi->id ? 'selected' : '' ?>>
                    <?= html_escape($instansi->nama_instansi) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endif; ?>
          <div class="col-lg-2 col-md-6">
            <label class="form-label">Baris</label>
            <select name="limit" class="form-select">
              <?php foreach ([25, 50, 100] as $val): ?>
                <option value="<?= $val ?>" <?= $limit == $val ? 'selected' : '' ?>><?= $val ?> data</option>
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
          <h2 class="report-card-title">Tabel Durasi Pelayanan</h2>
          <div class="report-card-note">Data diurutkan dari penyelesaian terbaru ke yang lebih lama.</div>
        </div>
      </div>
      <div class="report-card-body">
        <div class="table-responsive">
          <table class="table report-table align-middle">
            <thead>
              <tr>
                <th class="text-center col-nowrap">No</th>
                <th class="text-center col-nowrap">Nomor Antrian</th>
                <th class="col-name-min">Instansi</th>
                <th class="col-name-min">Layanan</th>
                <th class="col-name-min">Nama Petugas</th>
                <th class="text-center col-nowrap">Durasi</th>
                <th class="text-center col-nowrap">Waktu Mulai</th>
                <th class="text-center col-nowrap">Waktu Selesai</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($data)): ?>
                <?php $no = 1; ?>
                <?php foreach ($data as $row): ?>
                  <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><strong><?= html_escape($row->nomor_antrian) ?></strong></td>
                    <td><?= html_escape($row->nama_instansi) ?></td>
                    <td><?= html_escape($row->nama_layanan) ?></td>
                    <td><?= html_escape($row->nama_petugas) ?></td>
                    <td class="text-center"><span class="report-pill success"><?= html_escape($row->durasi ?? '-') ?></span></td>
                    <td class="text-center"><?= $row->called_at ? date('H:i', strtotime($row->called_at)) : '-' ?></td>
                    <td class="text-center"><?= $row->selesai_at ? date('H:i', strtotime($row->selesai_at)) : '-' ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="report-empty">Tidak ada data waktu layanan pada periode ini.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
</div>
