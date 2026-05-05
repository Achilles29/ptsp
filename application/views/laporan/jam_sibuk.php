<?php
$days = [
  2 => 'Senin',
  3 => 'Selasa',
  4 => 'Rabu',
  5 => 'Kamis',
  6 => 'Jumat',
  7 => 'Sabtu',
  1 => 'Minggu',
];
$hours = range(7, 17);
$matrix = [];
$maxTotal = 0;
foreach ($matrix_rows ?? [] as $row) {
  $dayKey = (int) $row->weekday_index;
  $hourKey = (int) $row->hour_of_day;
  $total = (int) $row->total;
  $matrix[$dayKey][$hourKey] = $total;
  $maxTotal = max($maxTotal, $total);
}
$topHourLabels = [];
$topHourValues = [];
foreach ($top_hours ?? [] as $row) {
  $topHourLabels[] = sprintf('%02d:00', (int) $row->hour_of_day);
  $topHourValues[] = (int) $row->total;
}
?>
<?php $this->load->view('laporan/_report_theme'); ?>
<div class="container-fluid report-page">
  <div class="report-shell">
    <section class="report-hero">
      <div class="report-hero-grid">
        <div>
          <div class="report-eyebrow"><i class="ri ri-timer-flash-line"></i> Analitik Kunjungan</div>
          <h1 class="report-title">Laporan Jam Sibuk</h1>
          <p class="report-subtitle">Heatmap ini membantu melihat lonjakan jam ramai per hari untuk menentukan kebutuhan petugas, loket, dan penjadwalan layanan.</p>
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

    <section class="report-card">
      <div class="report-card-header">
        <div>
          <h2 class="report-card-title">Heatmap Jam Ramai</h2>
          <div class="report-card-note">Semakin gelap warnanya, semakin tinggi frekuensi antrian pada hari dan jam tersebut.</div>
        </div>
      </div>
      <div class="report-card-body">
        <div class="report-heatmap">
          <div class="report-heatmap-row">
            <div class="report-heatmap-label">Hari / Jam</div>
            <?php foreach ($hours as $hour): ?>
              <div class="report-heatmap-cell level-0"><?= sprintf('%02d', $hour) ?></div>
            <?php endforeach; ?>
          </div>
          <?php foreach ($days as $dayIndex => $dayLabel): ?>
            <div class="report-heatmap-row">
              <div class="report-heatmap-label"><?= $dayLabel ?></div>
              <?php foreach ($hours as $hour): ?>
                <?php
                $value = (int) ($matrix[$dayIndex][$hour] ?? 0);
                $ratio = $maxTotal > 0 ? $value / $maxTotal : 0;
                $level = $value === 0 ? 0 : ($ratio >= 0.75 ? 4 : ($ratio >= 0.5 ? 3 : ($ratio >= 0.25 ? 2 : 1)));
                ?>
                <div class="report-heatmap-cell level-<?= $level ?>" title="<?= $dayLabel ?> <?= sprintf('%02d:00', $hour) ?>: <?= $value ?> antrian">
                  <?= $value ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="report-grid-two">
      <article class="report-card">
        <div class="report-card-header">
          <div>
            <h2 class="report-card-title">Jam Paling Sibuk</h2>
            <div class="report-card-note">Lima sampai enam jam dengan frekuensi antrian tertinggi.</div>
          </div>
        </div>
        <div class="report-card-body">
          <div id="jamSibukChart" class="report-chart-box"></div>
        </div>
      </article>

      <article class="report-card">
        <div class="report-card-header">
          <div>
            <h2 class="report-card-title">Catatan Pembacaan</h2>
            <div class="report-card-note">Penjelasan singkat tentang sumber waktu kunjungan.</div>
          </div>
        </div>
        <div class="report-card-body">
          <div class="report-note-box">
            Laporan ini memakai waktu panggil ketika tersedia, dan fallback ke waktu pencatatan antrian untuk kasus yang belum pernah dipanggil. Ini membantu membaca pola ramai meski sumber kunjungannya campuran online dan offline.
          </div>
        </div>
      </article>
    </section>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  if (!window.ApexCharts) return;
  new ApexCharts(document.querySelector('#jamSibukChart'), {
    chart: { type: 'bar', height: 280, toolbar: { show: false } },
    plotOptions: { bar: { borderRadius: 10, columnWidth: '50%' } },
    dataLabels: { enabled: true },
    series: [{ name: 'Jumlah Antrian', data: <?= json_encode($topHourValues) ?> }],
    xaxis: { categories: <?= json_encode($topHourLabels) ?> },
    colors: ['#2563eb'],
    grid: { borderColor: '#e2e8f0' }
  }).render();
});
</script>
