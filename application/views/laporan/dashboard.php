<?php
$summary = $summary ?? (object) [];
$trend_labels = [];
$trend_total = [];
$trend_selesai = [];
foreach ($trend_rows ?? [] as $row) {
  $trend_labels[] = date('d M', strtotime($row->periode));
  $trend_total[] = (int) ($row->total_antrian ?? 0);
  $trend_selesai[] = (int) ($row->total_selesai ?? 0);
}

$sla_labels = [];
$sla_values = [];
foreach (array_slice($sla_rows ?? [], 0, 6) as $row) {
  $sla_labels[] = $row->nama_layanan;
  $sla_values[] = (float) ($row->persentase_sla ?? 0);
}

$source_labels = [];
$source_values = [];
foreach ($no_show_sources ?? [] as $row) {
  $source_labels[] = strtoupper($row->sumber_daftar);
  $source_values[] = (int) ($row->total_tidak_hadir ?? 0);
}
?>
<?php $this->load->view('laporan/_report_theme'); ?>
<div class="container-fluid report-page">
  <div class="report-shell">
    <section class="report-hero">
      <div class="report-hero-grid">
        <div>
          <div class="report-eyebrow"><i class="ri ri-dashboard-line"></i> Dashboard Laporan</div>
          <h1 class="report-title">Pusat Analitik Antrian</h1>
          <p class="report-subtitle">Pantau volume layanan, konversi antrian, kepatuhan SLA, dan titik risiko no-show dari satu dashboard yang langsung terbaca.</p>
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
            <button class="btn btn-light btn-sm">Perbarui Dashboard</button>
          </form>
        </div>
      </div>
    </section>

    <section class="report-kpi-grid">
      <article class="report-kpi">
        <small>Total Antrian</small>
        <strong><?= number_format((int) ($summary->total_antrian ?? 0)) ?></strong>
        <span>Semua nomor yang tercatat dalam periode aktif.</span>
      </article>
      <article class="report-kpi">
        <small>Total Hadir</small>
        <strong><?= number_format((int) ($summary->total_hadir ?? 0)) ?></strong>
        <span>Pengunjung yang sudah check-in atau hadir.</span>
      </article>
      <article class="report-kpi">
        <small>Total Selesai</small>
        <strong><?= number_format((int) ($summary->total_selesai ?? 0)) ?></strong>
        <span>Layanan yang sudah ditutup lengkap oleh petugas.</span>
      </article>
      <article class="report-kpi">
        <small>Rata Durasi</small>
        <strong><?= number_format((float) ($summary->rata_durasi_menit ?? 0), 1) ?> mnt</strong>
        <span>Rata-rata dari nomor yang sudah dipanggil dan selesai.</span>
      </article>
    </section>

    <section class="report-chart-grid">
      <article class="report-chart-card">
        <h3>Tren Harian</h3>
        <p>Bandingkan volume antrian terhadap jumlah layanan yang benar-benar selesai.</p>
        <div id="laporanDashboardTrend" class="report-chart-box"></div>
      </article>
      <article class="report-chart-card">
        <h3>Konversi Antrian</h3>
        <p>Funnel operasional dari terdaftar sampai status akhir.</p>
        <div id="laporanDashboardKonversi" class="report-chart-box"></div>
      </article>
    </section>

    <section class="report-grid-two">
      <article class="report-card">
        <div class="report-card-header">
          <div>
            <h2 class="report-card-title">SLA Terbaik</h2>
            <div class="report-card-note">Layanan dengan kepatuhan SLA tertinggi pada periode aktif.</div>
          </div>
        </div>
        <div class="report-card-body">
          <div id="laporanDashboardSla" class="report-chart-box"></div>
        </div>
      </article>

      <article class="report-card">
        <div class="report-card-header">
          <div>
            <h2 class="report-card-title">No-Show per Sumber</h2>
            <div class="report-card-note">Perbandingan ketidakhadiran antara pendaftaran online dan offline.</div>
          </div>
        </div>
        <div class="report-card-body">
          <div id="laporanDashboardSource" class="report-chart-box"></div>
        </div>
      </article>
    </section>

    <section class="report-grid-two">
      <article class="report-card">
        <div class="report-card-header">
          <div>
            <h2 class="report-card-title">Instansi Paling Sibuk</h2>
            <div class="report-card-note">Fokus cepat untuk melihat beban layanan tertinggi pada periode aktif.</div>
          </div>
        </div>
        <div class="report-card-body">
          <div class="report-mini-list">
            <?php if (!empty($top_instansi)): ?>
              <?php foreach ($top_instansi as $row): ?>
                <div class="report-mini-item">
                  <div>
                    <strong><?= html_escape($row->nama_instansi) ?></strong>
                    <span><?= (int) ($row->total_selesai ?? 0) ?> selesai</span>
                  </div>
                  <strong><?= (int) ($row->total_antrian ?? 0) ?> antrian</strong>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="report-empty">Belum ada data instansi pada periode ini.</div>
            <?php endif; ?>
          </div>
        </div>
      </article>

      <article class="report-card">
        <div class="report-card-header">
          <div>
            <h2 class="report-card-title">Catatan Analitik</h2>
            <div class="report-card-note">Ringkasan cepat untuk membaca dashboard tanpa membuka semua halaman laporan.</div>
          </div>
        </div>
        <div class="report-card-body">
          <div class="report-note-box">
            Dashboard ini memisahkan kewenangan secara otomatis. Admin layanan hanya melihat data instansinya sendiri, sedangkan superadmin bisa memantau lintas instansi. Target SLA diambil dari pengaturan jenis layanan dan sumber daftar dibedakan dari antrian online versus walk-in front desk.
          </div>
        </div>
      </article>
    </section>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  if (!window.ApexCharts) return;

  new ApexCharts(document.querySelector('#laporanDashboardTrend'), {
    chart: { type: 'area', height: 280, toolbar: { show: false } },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 3 },
    series: [
      { name: 'Total Antrian', data: <?= json_encode($trend_total) ?> },
      { name: 'Selesai', data: <?= json_encode($trend_selesai) ?> }
    ],
    colors: ['#2563eb', '#16a34a'],
    xaxis: { categories: <?= json_encode($trend_labels) ?> },
    legend: { position: 'top' },
    grid: { borderColor: '#e2e8f0' }
  }).render();

  new ApexCharts(document.querySelector('#laporanDashboardKonversi'), {
    chart: { type: 'bar', height: 280, toolbar: { show: false } },
    plotOptions: { bar: { borderRadius: 10, columnWidth: '50%' } },
    dataLabels: { enabled: true },
    series: [{
      name: 'Jumlah',
      data: [
        <?= (int) ($konversi->total_terdaftar ?? 0) ?>,
        <?= (int) ($konversi->total_hadir ?? 0) ?>,
        <?= (int) ($konversi->total_dipanggil ?? 0) ?>,
        <?= (int) ($konversi->total_selesai ?? 0) ?>,
        <?= (int) ($konversi->total_batal ?? 0) ?>
      ]
    }],
    xaxis: { categories: ['Terdaftar', 'Hadir', 'Dipanggil', 'Selesai', 'Batal'] },
    colors: ['#1d4ed8'],
    grid: { borderColor: '#e2e8f0' }
  }).render();

  new ApexCharts(document.querySelector('#laporanDashboardSla'), {
    chart: { type: 'bar', height: 280, toolbar: { show: false } },
    plotOptions: { bar: { horizontal: true, borderRadius: 8 } },
    dataLabels: { enabled: true },
    series: [{ name: 'SLA', data: <?= json_encode($sla_values) ?> }],
    xaxis: { categories: <?= json_encode($sla_labels) ?>, max: 100 },
    colors: ['#0f766e'],
    grid: { borderColor: '#e2e8f0' }
  }).render();

  new ApexCharts(document.querySelector('#laporanDashboardSource'), {
    chart: { type: 'donut', height: 280, toolbar: { show: false } },
    labels: <?= json_encode($source_labels) ?>,
    series: <?= json_encode($source_values) ?>,
    colors: ['#2563eb', '#f59e0b'],
    legend: { position: 'bottom' }
  }).render();
});
</script>
