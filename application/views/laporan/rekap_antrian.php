<?php
$total_pendaftar = 0;
$total_datang = 0;
$total_tidak_datang = 0;
$total_selesai = 0;
foreach ($rekap as $row) {
  $total_pendaftar += (int) ($row->total_pendaftar ?? 0);
  $total_datang += (int) ($row->datang ?? 0);
  $total_tidak_datang += (int) ($row->tidak_datang ?? 0);
  $total_selesai += (int) ($row->selesai ?? 0);
}

$chart_labels = [];
$chart_total = [];
$chart_hadir = [];
$chart_selesai = [];
$chart_batal = [];
foreach ($chart_rows ?? [] as $row) {
  $chart_labels[] = date('d M', strtotime($row->tanggal));
  $chart_total[] = (int) ($row->total ?? 0);
  $chart_hadir[] = (int) ($row->hadir ?? 0);
  $chart_selesai[] = (int) ($row->selesai ?? 0);
  $chart_batal[] = (int) ($row->batal ?? 0);
}
?>
<?php $this->load->view('laporan/_report_theme'); ?>
<div class="container-fluid report-page">
  <div class="report-shell">
    <section class="report-hero">
      <div class="report-hero-grid">
        <div>
          <div class="report-eyebrow"><i class="ri ri-bar-chart-grouped-line"></i> Laporan Operasional</div>
          <h1 class="report-title">Rekap Antrian per Hari</h1>
          <p class="report-subtitle">Pantau volume pendaftar, tingkat kehadiran, dan jumlah layanan yang sudah selesai dalam satu ringkasan yang lebih enak dibaca.</p>
        </div>
        <div class="report-summary">
          <div class="report-summary-title">Periode Aktif</div>
          <div class="report-summary-list">
            <div class="report-summary-item">
              <span>Rentang tanggal</span>
              <strong><?= date('d M Y', strtotime($start_date)) ?> - <?= date('d M Y', strtotime($end_date)) ?></strong>
            </div>
            <div class="report-summary-item">
              <span>Baris per halaman</span>
              <strong><?= (int) $limit === 0 ? 'Semua data' : (int) $limit . ' baris' ?></strong>
            </div>
            <div class="report-summary-item">
              <span>Total grup rekap</span>
              <strong><?= count($rekap) ?> grup</strong>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="report-kpi-grid">
      <article class="report-kpi">
        <small>Total Pendaftar</small>
        <strong><?= number_format($total_pendaftar) ?></strong>
        <span>Akumulasi data pada hasil rekap yang sedang ditampilkan.</span>
      </article>
      <article class="report-kpi">
        <small>Masyarakat Hadir</small>
        <strong><?= number_format($total_datang) ?></strong>
        <span>Nomor yang sudah check-in atau tercatat hadir.</span>
      </article>
      <article class="report-kpi">
        <small>Tidak Hadir</small>
        <strong><?= number_format($total_tidak_datang) ?></strong>
        <span>Nomor yang terdaftar namun belum hadir saat direkap.</span>
      </article>
      <article class="report-kpi">
        <small>Layanan Selesai</small>
        <strong><?= number_format($total_selesai) ?></strong>
        <span>Jumlah antrian yang sudah punya hasil layanan.</span>
      </article>
    </section>

    <section class="report-card">
      <div class="report-card-header">
        <div>
          <h2 class="report-card-title">Filter dan Ekspor Rekap</h2>
          <div class="report-card-note">Atur periode dan jumlah baris tampilan, lalu unduh jika ingin diproses lebih lanjut di Excel.</div>
        </div>
        <div class="report-actions">
          <a href="<?= site_url('laporan/export_excel?start_date=' . urlencode($start_date) . '&end_date=' . urlencode($end_date) . '&limit=' . (int) $limit) ?>" class="report-btn-export">
            <i class="ri ri-file-excel-2-line me-1"></i> Download Excel
          </a>
        </div>
      </div>
      <div class="report-card-body">
        <form method="get" class="report-filter row g-3 align-items-end">
          <div class="col-lg-3 col-md-6">
            <label class="form-label">Tanggal Awal</label>
            <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control" required>
          </div>
          <div class="col-lg-3 col-md-6">
            <label class="form-label">Tanggal Akhir</label>
            <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control" required>
          </div>
          <?php if ($this->session->userdata('role_id') == 1): ?>
            <div class="col-lg-3 col-md-6">
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
              <option <?= $limit == 25 ? 'selected' : '' ?> value="25">25 baris</option>
              <option <?= $limit == 50 ? 'selected' : '' ?> value="50">50 baris</option>
              <option <?= $limit == 100 ? 'selected' : '' ?> value="100">100 baris</option>
              <option <?= $limit == 0 ? 'selected' : '' ?> value="0">Semua data</option>
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

    <section class="report-chart-grid">
      <article class="report-chart-card">
        <h3>Grafik Rekap Harian</h3>
        <p>Tren total antrian, kehadiran, penyelesaian, dan pembatalan untuk membaca kondisi operasional lebih cepat.</p>
        <div id="rekapTrendChart" class="report-chart-box"></div>
      </article>
      <article class="report-chart-card">
        <h3>Konversi Periode</h3>
        <p>Ringkasan funnel dari antrian terdaftar sampai selesai pada periode aktif.</p>
        <div id="rekapKonversiChart" class="report-chart-box"></div>
      </article>
    </section>

    <section class="report-card">
      <div class="report-card-header">
        <div>
          <h2 class="report-card-title">Tabel Rekap Harian</h2>
          <div class="report-card-note">Tiap baris mewakili kombinasi tanggal, instansi, dan layanan.</div>
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
                <th class="text-center col-nowrap">Total</th>
                <th class="text-center col-nowrap">Datang</th>
                <th class="text-center col-nowrap">Tidak Hadir</th>
                <th class="text-center col-nowrap">Selesai</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($rekap)): ?>
                <?php $no = $limit > 0 ? (($page - 1) * $limit) + 1 : 1; ?>
                <?php foreach ($rekap as $r): ?>
                  <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= date('d/m/Y', strtotime($r->tanggal)) ?></td>
                    <td><?= html_escape($r->nama_instansi) ?></td>
                    <td><?= html_escape($r->nama_layanan) ?></td>
                    <td class="text-center"><span class="report-pill neutral"><?= (int) $r->total_pendaftar ?></span></td>
                    <td class="text-center"><span class="report-pill success"><?= (int) $r->datang ?></span></td>
                    <td class="text-center"><span class="report-pill warning"><?= (int) $r->tidak_datang ?></span></td>
                    <td class="text-center"><span class="report-pill info"><?= (int) $r->selesai ?></span></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="report-empty">Belum ada data rekap pada periode ini.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <?php if ((int) $limit !== 0 && !empty($pagination)): ?>
          <div class="report-pagination mt-4">
            <?= $pagination ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const trendTarget = document.querySelector('#rekapTrendChart');
  if (trendTarget && window.ApexCharts) {
    new ApexCharts(trendTarget, {
      chart: { type: 'line', height: 280, toolbar: { show: false } },
      stroke: { curve: 'smooth', width: [3, 3, 3, 2] },
      series: [
        { name: 'Total', data: <?= json_encode($chart_total) ?> },
        { name: 'Hadir', data: <?= json_encode($chart_hadir) ?> },
        { name: 'Selesai', data: <?= json_encode($chart_selesai) ?> },
        { name: 'Batal', data: <?= json_encode($chart_batal) ?> }
      ],
      xaxis: { categories: <?= json_encode($chart_labels) ?> },
      colors: ['#2563eb', '#16a34a', '#0f766e', '#dc2626'],
      dataLabels: { enabled: false },
      grid: { borderColor: '#e2e8f0' },
      legend: { position: 'top' }
    }).render();
  }

  const konversiTarget = document.querySelector('#rekapKonversiChart');
  if (konversiTarget && window.ApexCharts) {
    new ApexCharts(konversiTarget, {
      chart: { type: 'bar', height: 280, toolbar: { show: false } },
      plotOptions: { bar: { borderRadius: 10, columnWidth: '48%' } },
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
      dataLabels: { enabled: true },
      grid: { borderColor: '#e2e8f0' }
    }).render();
  }
});
</script>
