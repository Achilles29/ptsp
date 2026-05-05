<?php
$sourceLabels = [];
$sourcePercent = [];
foreach ($source_summary ?? [] as $row) {
  $sourceLabels[] = strtoupper($row->sumber_daftar);
  $totalPendaftar = (int) ($row->total_pendaftar ?? 0);
  $totalTidakHadir = (int) ($row->total_tidak_hadir ?? 0);
  $sourcePercent[] = $totalPendaftar > 0 ? round(($totalTidakHadir / $totalPendaftar) * 100, 1) : 0;
}
?>
<?php $this->load->view('laporan/_report_theme'); ?>
<div class="container-fluid report-page">
  <div class="report-shell">
    <section class="report-hero">
      <div class="report-hero-grid">
        <div>
          <div class="report-eyebrow"><i class="ri ri-user-unfollow-line"></i> Risiko Kehadiran</div>
          <h1 class="report-title">Laporan No-Show</h1>
          <p class="report-subtitle">Tingkat ketidakhadiran ini dibedakan menurut layanan, tanggal, dan sumber pendaftaran untuk membantu evaluasi reminder dan jadwal layanan.</p>
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

    <section class="report-chart-grid">
      <article class="report-chart-card">
        <h3>No-Show per Sumber</h3>
        <p>Bandingkan tingkat ketidakhadiran antara jalur online dan offline.</p>
        <div id="noShowSourceChart" class="report-chart-box"></div>
      </article>
      <article class="report-chart-card">
        <h3>Catatan Sumber</h3>
        <p>Sumber daftar diturunkan dari cara pembuatan antrian di sistem.</p>
        <div class="report-note-box">
          Online berarti antrian dibuat oleh akun masyarakat yang login. Offline berarti nomor dibuat dari front desk atau walk-in. Pemisahan ini membantu melihat apakah no-show lebih sering terjadi pada pendaftaran jarak jauh dibanding pendaftaran langsung.
        </div>
      </article>
    </section>

    <section class="report-card">
      <div class="report-card-header">
        <div>
          <h2 class="report-card-title">Detail No-Show</h2>
          <div class="report-card-note">Urutan teratas diprioritaskan pada persentase ketidakhadiran tertinggi.</div>
        </div>
      </div>
      <div class="report-card-body">
        <div class="table-responsive">
          <table class="table report-table align-middle">
            <thead>
              <tr>
                <th class="col-nowrap">Tanggal</th>
                <th class="col-name-min">Instansi</th>
                <th class="col-name-min">Layanan</th>
                <th class="text-center col-nowrap">Sumber</th>
                <th class="text-center col-nowrap">Pendaftar</th>
                <th class="text-center col-nowrap">Tidak Hadir</th>
                <th class="text-center col-nowrap">Persentase</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                  <tr>
                    <td><?= date('d/m/Y', strtotime($row->tanggal)) ?></td>
                    <td><?= html_escape($row->nama_instansi) ?></td>
                    <td><?= html_escape($row->nama_layanan) ?></td>
                    <td class="text-center"><span class="report-pill neutral"><?= strtoupper($row->sumber_daftar) ?></span></td>
                    <td class="text-center"><?= (int) $row->total_pendaftar ?></td>
                    <td class="text-center"><?= (int) $row->total_tidak_hadir ?></td>
                    <td class="text-center">
                      <span class="report-pill <?= (float) $row->persentase_tidak_hadir >= 40 ? 'danger' : ((float) $row->persentase_tidak_hadir >= 20 ? 'warning' : 'success') ?>">
                        <?= number_format((float) $row->persentase_tidak_hadir, 1) ?>%
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="7" class="report-empty">Belum ada data no-show pada periode ini.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  if (!window.ApexCharts) return;
  new ApexCharts(document.querySelector('#noShowSourceChart'), {
    chart: { type: 'bar', height: 280, toolbar: { show: false } },
    plotOptions: { bar: { borderRadius: 10, columnWidth: '45%' } },
    dataLabels: { enabled: true },
    series: [{ name: 'No-Show %', data: <?= json_encode($sourcePercent) ?> }],
    xaxis: { categories: <?= json_encode($sourceLabels) ?>, max: 100 },
    colors: ['#dc2626'],
    grid: { borderColor: '#e2e8f0' }
  }).render();
});
</script>
