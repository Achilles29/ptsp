<?php
$labels = [];
$totalSeries = [];
$selesaiSeries = [];
$batalSeries = [];
foreach ($rows ?? [] as $row) {
  $labels[] = $mode === 'bulanan' ? date('M Y', strtotime($row->periode . '-01')) : date('d M', strtotime($row->periode));
  $totalSeries[] = (int) ($row->total_antrian ?? 0);
  $selesaiSeries[] = (int) ($row->total_selesai ?? 0);
  $batalSeries[] = (int) ($row->total_batal ?? 0);
}
?>
<?php $this->load->view('laporan/_report_theme'); ?>
<div class="container-fluid report-page">
  <div class="report-shell">
    <section class="report-hero">
      <div class="report-hero-grid">
        <div>
          <div class="report-eyebrow"><i class="ri ri-line-chart-line"></i> Tren Operasional</div>
          <h1 class="report-title">Laporan Tren Harian/Bulanan</h1>
          <p class="report-subtitle">Gunakan mode harian untuk memantau lonjakan jangka pendek, dan mode bulanan untuk membaca pola musiman atau beban jangka menengah.</p>
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
            <div class="report-summary-item">
              <span>Mode</span>
              <select name="mode" class="form-select form-select-sm">
                <option value="harian" <?= $mode === 'harian' ? 'selected' : '' ?>>Harian</option>
                <option value="bulanan" <?= $mode === 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
              </select>
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

    <section class="report-card">
      <div class="report-card-header">
        <div>
          <h2 class="report-card-title">Grafik Tren</h2>
          <div class="report-card-note">Membandingkan total antrian, yang berhasil selesai, dan yang berakhir batal.</div>
        </div>
      </div>
      <div class="report-card-body">
        <div id="trenAntrianChart" class="report-chart-box"></div>
      </div>
    </section>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  if (!window.ApexCharts) return;
  new ApexCharts(document.querySelector('#trenAntrianChart'), {
    chart: { type: 'line', height: 320, toolbar: { show: false } },
    stroke: { curve: 'smooth', width: [3, 3, 2] },
    dataLabels: { enabled: false },
    series: [
      { name: 'Total Antrian', data: <?= json_encode($totalSeries) ?> },
      { name: 'Selesai', data: <?= json_encode($selesaiSeries) ?> },
      { name: 'Batal', data: <?= json_encode($batalSeries) ?> }
    ],
    xaxis: { categories: <?= json_encode($labels) ?> },
    colors: ['#2563eb', '#16a34a', '#dc2626'],
    legend: { position: 'top' },
    grid: { borderColor: '#e2e8f0' }
  }).render();
});
</script>
