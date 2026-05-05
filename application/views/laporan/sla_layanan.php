<?php
$totalSelesai = 0;
$totalSesuai = 0;
foreach ($rows ?? [] as $row) {
  $totalSelesai += (int) ($row->total_selesai ?? 0);
  $totalSesuai += (int) ($row->sesuai_target ?? 0);
}
$persentaseGlobal = $totalSelesai > 0 ? round(($totalSesuai / $totalSelesai) * 100, 1) : 0;
?>
<?php $this->load->view('laporan/_report_theme'); ?>
<div class="container-fluid report-page">
  <div class="report-shell">
    <section class="report-hero">
      <div class="report-hero-grid">
        <div>
          <div class="report-eyebrow"><i class="ri ri-speed-up-line"></i> Service Level</div>
          <h1 class="report-title">Laporan SLA Layanan</h1>
          <p class="report-subtitle">Lihat seberapa konsisten tiap layanan diselesaikan di bawah target durasi yang sudah ditetapkan pada master jenis layanan.</p>
        </div>
        <div class="report-summary">
          <div class="report-summary-title">Filter Aktif</div>
          <form method="get" class="report-summary-list">
            <div class="report-summary-item">
              <span>Tanggal awal</span>
              <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control form-control-sm">
            </div>
            <div class="report-summary-item">
              <span>Tanggal akhir</span>
              <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control form-control-sm">
            </div>
            <?php if ($this->session->userdata('role_id') == 1): ?>
              <div class="report-summary-item">
                <span>Instansi</span>
                <select name="instansi_id" class="form-select form-select-sm">
                  <option value="">Semua instansi</option>
                  <?php foreach ($instansi_list as $instansi): ?>
                    <option value="<?= (int) $instansi->id ?>" <?= (int) $instansi_id === (int) $instansi->id ? 'selected' : '' ?>>
                      <?= html_escape($instansi->nama_instansi) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php endif; ?>
            <button class="btn btn-light btn-sm">Tampilkan</button>
          </form>
        </div>
      </div>
    </section>

    <section class="report-kpi-grid">
      <article class="report-kpi">
        <small>Total Selesai</small>
        <strong><?= number_format($totalSelesai) ?></strong>
        <span>Jumlah layanan yang selesai dan punya durasi valid.</span>
      </article>
      <article class="report-kpi">
        <small>Sesuai Target</small>
        <strong><?= number_format($totalSesuai) ?></strong>
        <span>Layanan yang selesai di bawah atau sama dengan target SLA.</span>
      </article>
      <article class="report-kpi">
        <small>Kepatuhan Global</small>
        <strong><?= number_format($persentaseGlobal, 1) ?>%</strong>
        <span>Persentase keseluruhan dari data yang masuk periode ini.</span>
      </article>
    </section>

    <section class="report-card">
      <div class="report-card-header">
        <div>
          <h2 class="report-card-title">Tabel SLA per Layanan</h2>
          <div class="report-card-note">Target SLA berasal dari master jenis layanan dan bisa diubah oleh superadmin.</div>
        </div>
      </div>
      <div class="report-card-body">
        <div class="table-responsive">
          <table class="table report-table align-middle">
            <thead>
              <tr>
                <th class="col-name-min">Instansi</th>
                <th class="col-name-min">Layanan</th>
                <th class="text-center col-nowrap">Target</th>
                <th class="text-center col-nowrap">Total Selesai</th>
                <th class="text-center col-nowrap">Sesuai Target</th>
                <th class="text-center col-nowrap">Persentase SLA</th>
                <th class="text-center col-nowrap">Rata Durasi</th>
                <th class="text-center col-nowrap">Durasi Terlama</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                  <tr>
                    <td><?= html_escape($row->nama_instansi) ?></td>
                    <td><?= html_escape($row->nama_layanan) ?></td>
                    <td class="text-center"><span class="report-pill neutral"><?= (int) $row->target_durasi_menit ?> menit</span></td>
                    <td class="text-center"><?= (int) $row->total_selesai ?></td>
                    <td class="text-center"><?= (int) $row->sesuai_target ?></td>
                    <td class="text-center">
                      <span class="report-pill <?= (float) $row->persentase_sla >= 85 ? 'success' : ((float) $row->persentase_sla >= 60 ? 'warning' : 'danger') ?>">
                        <?= number_format((float) $row->persentase_sla, 1) ?>%
                      </span>
                    </td>
                    <td class="text-center"><?= number_format((float) $row->rata_durasi_menit, 1) ?> menit</td>
                    <td class="text-center"><?= (int) $row->durasi_terlama_menit ?> menit</td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="8" class="report-empty">Belum ada data SLA pada periode ini.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
</div>
