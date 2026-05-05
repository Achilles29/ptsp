<?php
$stages = [
  ['label' => 'Terdaftar', 'value' => (int) ($konversi->total_terdaftar ?? 0)],
  ['label' => 'Hadir', 'value' => (int) ($konversi->total_hadir ?? 0)],
  ['label' => 'Dipanggil', 'value' => (int) ($konversi->total_dipanggil ?? 0)],
  ['label' => 'Selesai', 'value' => (int) ($konversi->total_selesai ?? 0)],
  ['label' => 'Batal', 'value' => (int) ($konversi->total_batal ?? 0)],
];
$base = max(1, (int) ($konversi->total_terdaftar ?? 0));
?>
<?php $this->load->view('laporan/_report_theme'); ?>
<div class="container-fluid report-page">
  <div class="report-shell">
    <section class="report-hero">
      <div class="report-hero-grid">
        <div>
          <div class="report-eyebrow"><i class="ri ri-funnel-line"></i> Funnel Operasional</div>
          <h1 class="report-title">Laporan Konversi Antrian</h1>
          <p class="report-subtitle">Lihat antrian yang berhasil bergerak dari pendaftaran sampai selesai, lalu identifikasi titik drop-off terbesar di tengah proses layanan.</p>
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

    <section class="report-stage-grid">
      <?php foreach ($stages as $stage): ?>
        <article class="report-stage">
          <small><?= $stage['label'] ?></small>
          <strong><?= number_format($stage['value']) ?></strong>
          <span><?= number_format(($stage['value'] / $base) * 100, 1) ?>% dari total terdaftar</span>
        </article>
      <?php endforeach; ?>
    </section>

    <section class="report-card">
      <div class="report-card-header">
        <div>
          <h2 class="report-card-title">Visual Funnel</h2>
          <div class="report-card-note">Batang ini memudahkan melihat penyusutan volume dari setiap tahap.</div>
        </div>
      </div>
      <div class="report-card-body">
        <div id="konversiFunnelChart" class="report-chart-box"></div>
      </div>
    </section>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  if (!window.ApexCharts) return;
  new ApexCharts(document.querySelector('#konversiFunnelChart'), {
    chart: { type: 'bar', height: 300, toolbar: { show: false } },
    plotOptions: { bar: { horizontal: true, borderRadius: 8 } },
    dataLabels: { enabled: true },
    series: [{ name: 'Jumlah', data: <?= json_encode(array_column($stages, 'value')) ?> }],
    xaxis: { categories: <?= json_encode(array_column($stages, 'label')) ?> },
    colors: ['#2563eb'],
    grid: { borderColor: '#e2e8f0' }
  }).render();
});
</script>
