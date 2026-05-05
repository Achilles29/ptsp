<?php
$instansi_nama = $this->session->userdata('nama_instansi') ?: 'Instansi Layanan';
$status_chart_labels = ['Terdaftar', 'Dipanggil', 'Selesai', 'Batal'];
$status_chart_series = [
    (int) ($status_breakdown['terdaftar'] ?? 0),
    (int) ($status_breakdown['dipanggil'] ?? 0),
    (int) ($status_breakdown['selesai'] ?? 0),
    (int) ($status_breakdown['batal'] ?? 0),
];
$trend_labels = array_column($trend_harian ?? [], 'label');
$trend_series = array_map(static function ($item) {
    return (int) ($item['total'] ?? 0);
}, $trend_harian ?? []);
?>

<style>
  .al-dashboard .al-hero {
    padding: 1.75rem;
    border-radius: 28px;
    background: linear-gradient(135deg, #0a2f53 0%, #0f4c81 55%, #1b6fb1 100%);
    box-shadow: 0 28px 60px rgba(10, 47, 83, 0.18);
    color: #fff;
  }

  .al-dashboard .al-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    margin-bottom: .65rem;
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: rgba(255,255,255,.74);
  }

  .al-dashboard .al-title {
    margin: 0 0 .8rem;
    font-size: clamp(1.8rem, 2.5vw, 2.6rem);
    line-height: 1.08;
    color: #fff;
    max-width: 18ch;
  }

  .al-dashboard .al-subtitle {
    margin: 0;
    max-width: 52rem;
    color: rgba(255,255,255,.84);
    line-height: 1.7;
  }

  .al-dashboard .al-metric-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: .8rem;
    margin-top: 1.25rem;
  }

  .al-dashboard .al-metric {
    padding: .95rem 1rem;
    border-radius: 18px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.12);
  }

  .al-dashboard .al-metric small {
    display: block;
    color: rgba(255,255,255,.72);
  }

  .al-dashboard .al-metric strong {
    display: block;
    margin-top: .18rem;
    font-size: 1.35rem;
    color: #fff;
  }

  .al-dashboard .al-summary {
    height: 100%;
    padding: 1.15rem 1.2rem;
    border-radius: 22px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.14);
  }

  .al-dashboard .al-summary h3 {
    margin: 0 0 .45rem;
    font-size: 1.65rem;
    color: #fff;
  }

  .al-dashboard .al-summary p {
    margin: 0 0 1rem;
    color: rgba(255,255,255,.8);
    line-height: 1.6;
  }

  .al-dashboard .al-summary-list {
    display: grid;
    gap: .75rem;
  }

  .al-dashboard .al-summary-row {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding-bottom: .7rem;
    border-bottom: 1px dashed rgba(255,255,255,.18);
  }

  .al-dashboard .al-summary-row:last-child {
    padding-bottom: 0;
    border-bottom: 0;
  }

  .al-dashboard .al-summary-row span {
    color: rgba(255,255,255,.78);
  }

  .al-dashboard .al-summary-row strong {
    color: #fff;
    text-align: right;
  }

  .al-dashboard .al-stat-card {
    height: 100%;
    border: 1px solid rgba(15, 76, 129, .12);
    border-top-width: 4px;
    border-radius: 22px;
    background: linear-gradient(180deg, #fff 0%, #f8fbff 100%);
    box-shadow: 0 16px 30px rgba(15, 23, 42, .05);
  }

  .al-dashboard .al-stat-card.card-primary { border-top-color: #0f4c81; }
  .al-dashboard .al-stat-card.card-warning { border-top-color: #d97706; }
  .al-dashboard .al-stat-card.card-dark { border-top-color: #334155; }
  .al-dashboard .al-stat-card.card-success { border-top-color: #059669; }

  .al-dashboard .al-stat-body {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.2rem;
  }

  .al-dashboard .al-stat-value {
    margin: .15rem 0 .25rem;
    font-size: 2rem;
    font-weight: 800;
    color: #14233c;
  }

  .al-dashboard .al-stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #0f4c81;
    flex: 0 0 3rem;
  }

  .al-dashboard .al-card {
    height: 100%;
    border: 1px solid rgba(15, 76, 129, .12);
    border-radius: 24px;
    background: #fff;
    box-shadow: 0 16px 30px rgba(15, 23, 42, .05);
  }

  .al-dashboard .al-card-header {
    padding: 1.15rem 1.25rem;
    border-bottom: 1px solid rgba(148, 163, 184, .16);
  }

  .al-dashboard .al-card-body {
    padding: 1.25rem;
  }

  .al-dashboard .al-note {
    color: #64748b;
    line-height: 1.6;
  }

  .al-dashboard .al-data-list {
    display: grid;
    gap: .75rem;
  }

  .al-dashboard .al-data-row {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding-bottom: .75rem;
    border-bottom: 1px dashed rgba(148,163,184,.28);
  }

  .al-dashboard .al-data-row:last-child {
    padding-bottom: 0;
    border-bottom: 0;
  }

  .al-dashboard .al-data-row strong {
    color: #14233c;
  }

  .al-dashboard .al-action-grid {
    display: grid;
    gap: .8rem;
    margin-top: 1rem;
  }

  .al-dashboard .al-action {
    display: flex;
    align-items: center;
    gap: .8rem;
    padding: .95rem 1rem;
    border-radius: 18px;
    text-decoration: none;
    border: 1px solid rgba(148,163,184,.18);
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    color: #14233c;
  }

  .al-dashboard .al-action:hover {
    color: #14233c;
    box-shadow: 0 14px 28px rgba(15,23,42,.08);
    transform: translateY(-1px);
  }

  .al-dashboard .al-action i {
    width: 2.75rem;
    height: 2.75rem;
    border-radius: .95rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #0f4c81;
    flex: 0 0 2.75rem;
  }

  .al-dashboard .al-action strong {
    display: block;
    margin-bottom: .1rem;
  }

  .al-dashboard .al-action span {
    display: block;
    color: #64748b;
    line-height: 1.5;
  }
</style>

<div class="container-fluid px-4 mt-4 al-dashboard">
  <div class="row g-3">
    <div class="col-12">
      <div class="al-hero">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-8">
            <div class="al-eyebrow"><i class="ri ri-building-line"></i> Operasional Instansi</div>
            <h1 class="al-title"><?= html_escape($instansi_nama) ?></h1>
            <p class="al-subtitle">Lihat performa layanan hari ini, antrian yang sedang berjalan, kehadiran masyarakat, dan distribusi layanan dari satu layar kerja yang lebih jelas.</p>
            <div class="al-metric-grid">
              <div class="al-metric"><small>Layanan tersedia</small><strong><?= (int) $jumlah_layanan ?></strong></div>
              <div class="al-metric"><small>Hadir hari ini</small><strong><?= (int) ($attendance_breakdown['hadir'] ?? 0) ?></strong></div>
              <div class="al-metric"><small>Belum check-in</small><strong><?= (int) ($attendance_breakdown['belum_hadir'] ?? 0) ?></strong></div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="al-summary">
              <div class="al-eyebrow"><i class="ri ri-megaphone-line"></i> Ringkasan Panggilan</div>
              <h3><?= $current_call ? html_escape($current_call->nomor_antrian) : 'Belum ada panggilan aktif' ?></h3>
              <p><?= $current_call ? 'Nomor terakhir yang sudah dipanggil akan muncul di sini bersama konteks layanan.' : 'Saat petugas mulai memanggil antrian, nomor aktif dan info layanan akan tampil di panel ini.' ?></p>
              <div class="al-summary-list">
                <div class="al-summary-row"><span>Layanan</span><strong><?= $current_call ? html_escape($current_call->nama_layanan ?? '-') : '-' ?></strong></div>
                <div class="al-summary-row"><span>Sudah check-in</span><strong><?= (int) ($attendance_breakdown['hadir'] ?? 0) ?></strong></div>
                <div class="al-summary-row"><span>Menunggu</span><strong><?= (int) ($ringkasan['menunggu'] ?? 0) ?></strong></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-xl-3">
      <div class="al-stat-card card-primary">
        <div class="al-stat-body">
          <div>
            <div class="text-muted">Total Antrian</div>
            <div class="al-stat-value"><?= (int) ($ringkasan['total'] ?? 0) ?></div>
            <div class="small text-muted">Pendaftaran hari ini di instansi Anda</div>
          </div>
          <span class="al-stat-icon"><i class="ri ri-team-line"></i></span>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="al-stat-card card-warning">
        <div class="al-stat-body">
          <div>
            <div class="text-muted">Menunggu</div>
            <div class="al-stat-value"><?= (int) ($ringkasan['menunggu'] ?? 0) ?></div>
            <div class="small text-muted">Termasuk yang sudah check-in dan yang belum hadir</div>
          </div>
          <span class="al-stat-icon"><i class="ri ri-time-line"></i></span>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="al-stat-card card-dark">
        <div class="al-stat-body">
          <div>
            <div class="text-muted">Sedang Dipanggil</div>
            <div class="al-stat-value"><?= (int) ($ringkasan['dipanggil'] ?? 0) ?></div>
            <div class="small text-muted">Panggilan aktif yang masih berjalan</div>
          </div>
          <span class="al-stat-icon"><i class="ri ri-megaphone-line"></i></span>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="al-stat-card card-success">
        <div class="al-stat-body">
          <div>
            <div class="text-muted">Selesai</div>
            <div class="al-stat-value"><?= (int) ($ringkasan['selesai'] ?? 0) ?></div>
            <div class="small text-muted">Antrian yang telah dituntaskan hari ini</div>
          </div>
          <span class="al-stat-icon"><i class="ri ri-checkbox-circle-line"></i></span>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="al-card">
        <div class="al-card-header">
          <h5 class="mb-1">Ritme Hari Ini</h5>
          <div class="al-note">Membaca kondisi operasional secara cepat dari kehadiran dan progres penyelesaian.</div>
        </div>
        <div class="al-card-body">
          <div class="al-data-list">
            <div class="al-data-row"><span>Hadir</span><strong><?= (int) ($attendance_breakdown['hadir'] ?? 0) ?></strong></div>
            <div class="al-data-row"><span>Belum hadir</span><strong><?= (int) ($attendance_breakdown['belum_hadir'] ?? 0) ?></strong></div>
            <div class="al-data-row"><span>Layanan tersedia</span><strong><?= (int) $jumlah_layanan ?></strong></div>
          </div>
          <div class="mt-4">
            <h5 class="mb-2">Distribusi Layanan</h5>
            <div class="al-note mb-3">Layanan dengan jumlah pendaftaran terbanyak hari ini.</div>
            <?php if (empty($layanan_breakdown)): ?>
              <div class="portal-empty">Belum ada layanan yang menerima antrian hari ini.</div>
            <?php else: ?>
              <div class="al-data-list">
                <?php foreach ($layanan_breakdown as $layanan): ?>
                  <div class="al-data-row"><span><?= html_escape($layanan->nama_layanan) ?></span><strong><?= (int) $layanan->total ?> antrian</strong></div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="al-card">
        <div class="al-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div>
            <h5 class="mb-1">Status Hari Ini</h5>
            <div class="al-note">Komposisi antrian berdasarkan status operasional.</div>
          </div>
          <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">Live</span>
        </div>
        <div class="al-card-body">
          <div id="adminStatusChart" class="portal-chart"></div>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="al-card">
        <div class="al-card-header">
          <h5 class="mb-1">Aksi Cepat</h5>
          <div class="al-note">Shortcut ke proses yang paling sering dilakukan petugas.</div>
        </div>
        <div class="al-card-body">
          <div class="al-action-grid">
            <a href="<?= base_url('admin_layanan/antrian_hari_ini') ?>" class="al-action"><i class="ri ri-megaphone-line"></i><span><strong>Panggil Antrian</strong><span>Masuk ke daftar antrian hari ini.</span></span></a>
            <a href="<?= base_url('admin_layanan/riwayat_antrian') ?>" class="al-action"><i class="ri ri-history-line"></i><span><strong>Riwayat Antrian</strong><span>Lihat hasil pelayanan sebelumnya.</span></span></a>
            <a href="<?= base_url('laporan/dashboard') ?>" class="al-action"><i class="ri ri-bar-chart-grouped-line"></i><span><strong>Laporan</strong><span>Buka dashboard laporan khusus instansi Anda.</span></span></a>
          </div>
          <div class="alert alert-primary mt-3 mb-0">
            Kehadiran hari ini: <strong><?= (int) ($attendance_breakdown['hadir'] ?? 0) ?></strong> sudah check-in,
            <strong><?= (int) ($attendance_breakdown['belum_hadir'] ?? 0) ?></strong> belum hadir.
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="al-card">
        <div class="al-card-header">
          <h5 class="mb-1">Antrian Terbaru</h5>
          <div class="al-note">Masuk terbaru yang membantu membaca ritme kedatangan masyarakat.</div>
        </div>
        <div class="al-card-body">
          <?php if (empty($antrian_terbaru)): ?>
            <div class="portal-empty">Belum ada antrian masuk hari ini.</div>
          <?php else: ?>
            <div class="al-data-list">
              <?php foreach ($antrian_terbaru as $a): ?>
                <div class="al-data-row">
                  <span><?= html_escape($a->nama_layanan ?? '-') ?><br><small class="text-muted"><?= !empty($a->created_at) ? date('H:i', strtotime($a->created_at)) : '-' ?></small></span>
                  <strong><?= html_escape($a->nomor_antrian) ?></strong>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="col-xl-8">
      <div class="al-card">
        <div class="al-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div>
            <h5 class="mb-1">Tren 7 Hari Terakhir</h5>
            <div class="al-note">Pergerakan jumlah pendaftaran di instansi Anda.</div>
          </div>
          <a href="<?= base_url('admin_layanan/riwayat_antrian') ?>" class="btn btn-sm btn-outline-primary">
            <i class="ri ri-history-line me-1"></i> Buka Riwayat
          </a>
        </div>
        <div class="al-card-body">
          <div id="adminTrendChart" class="portal-chart"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let antrianTerakhir = <?= (int) ($ringkasan['total'] ?? 0) ?>;

document.addEventListener('DOMContentLoaded', function () {
  const statusEl = document.querySelector('#adminStatusChart');
  const trendEl = document.querySelector('#adminTrendChart');

  if (statusEl && window.ApexCharts) {
    new ApexCharts(statusEl, {
      chart: {
        type: 'donut',
        height: 300
      },
      series: <?= json_encode($status_chart_series) ?>,
      labels: <?= json_encode($status_chart_labels) ?>,
      colors: ['#3b82f6', '#f59e0b', '#22c55e', '#ef4444'],
      stroke: { width: 0 },
      legend: { position: 'bottom' },
      dataLabels: { enabled: true }
    }).render();
  }

  if (trendEl && window.ApexCharts) {
    new ApexCharts(trendEl, {
      chart: {
        type: 'bar',
        height: 320,
        toolbar: { show: false }
      },
      series: [{
        name: 'Antrian',
        data: <?= json_encode($trend_series) ?>
      }],
      xaxis: {
        categories: <?= json_encode($trend_labels) ?>
      },
      colors: ['#0f4c81'],
      plotOptions: {
        bar: {
          borderRadius: 8,
          columnWidth: '48%'
        }
      },
      dataLabels: { enabled: false },
      grid: {
        borderColor: '#e2e8f0',
        strokeDashArray: 4
      }
    }).render();
  }
});

function cekNotifikasiAntrianBaru() {
  $.getJSON('<?= base_url('admin_layanan/cek_total_antrian_json') ?>', function (res) {
    const totalBaru = parseInt(res.total, 10);

    if (totalBaru > antrianTerakhir) {
      antrianTerakhir = totalBaru;

      Swal.fire({
        title: 'Antrian Baru',
        text: 'Ada antrian baru masuk.',
        icon: 'info',
        toast: true,
        timer: 4000,
        position: 'top-end',
        showConfirmButton: false
      });

      const audio = document.getElementById('notifikasiSound');
      if (audio) {
        audio.play().catch(() => {});
      }
    }
  });
}

setInterval(cekNotifikasiAntrianBaru, 10000);
</script>

<audio id="notifikasiSound" src="<?= base_url('assets/sounds/antrian.mp3') ?>" preload="auto"></audio>
