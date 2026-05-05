<?php
$nama_user = $this->session->userdata('nama_lengkap')
    ?: $this->session->userdata('nama')
    ?: 'Super Admin';
$status_chart_labels = ['Terdaftar', 'Dipanggil', 'Selesai', 'Batal'];
$status_chart_series = [
    (int) ($status_hari_ini['terdaftar'] ?? 0),
    (int) ($status_hari_ini['dipanggil'] ?? 0),
    (int) ($status_hari_ini['selesai'] ?? 0),
    (int) ($status_hari_ini['batal'] ?? 0),
];
$trend_labels = array_column($tren_7_hari ?? [], 'label');
$trend_series = array_map(static function ($item) {
    return (int) ($item['total'] ?? 0);
}, $tren_7_hari ?? []);
$instansi_teratas = !empty($top_instansi) ? $top_instansi[0] : null;
?>

<style>
  .sa-dashboard .sa-hero {
    padding: 1.75rem;
    border-radius: 28px;
    background: linear-gradient(135deg, #0a2f53 0%, #0f4c81 55%, #1b6fb1 100%);
    box-shadow: 0 28px 60px rgba(10, 47, 83, 0.18);
    color: #fff;
  }

  .sa-dashboard .sa-eyebrow {
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

  .sa-dashboard .sa-title {
    margin: 0 0 .8rem;
    font-size: clamp(2rem, 3vw, 3rem);
    line-height: 1.04;
    color: #fff;
  }

  .sa-dashboard .sa-subtitle {
    margin: 0;
    max-width: 56rem;
    color: rgba(255,255,255,.84);
    line-height: 1.7;
  }

  .sa-dashboard .sa-metric-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: .8rem;
    margin-top: 1.25rem;
  }

  .sa-dashboard .sa-metric {
    padding: .95rem 1rem;
    border-radius: 18px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.12);
  }

  .sa-dashboard .sa-metric small {
    display: block;
    color: rgba(255,255,255,.72);
  }

  .sa-dashboard .sa-metric strong {
    display: block;
    margin-top: .18rem;
    font-size: 1.4rem;
    color: #fff;
  }

  .sa-dashboard .sa-summary {
    height: 100%;
    padding: 1.15rem 1.2rem;
    border-radius: 22px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.14);
  }

  .sa-dashboard .sa-summary h3 {
    margin: 0 0 .45rem;
    font-size: 1.9rem;
    color: #fff;
  }

  .sa-dashboard .sa-summary p {
    margin: 0 0 1rem;
    color: rgba(255,255,255,.8);
    line-height: 1.6;
  }

  .sa-dashboard .sa-summary-list {
    display: grid;
    gap: .75rem;
  }

  .sa-dashboard .sa-summary-row {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding-bottom: .7rem;
    border-bottom: 1px dashed rgba(255,255,255,.18);
  }

  .sa-dashboard .sa-summary-row:last-child {
    padding-bottom: 0;
    border-bottom: 0;
  }

  .sa-dashboard .sa-summary-row span {
    color: rgba(255,255,255,.78);
  }

  .sa-dashboard .sa-summary-row strong {
    color: #fff;
    text-align: right;
  }

  .sa-dashboard .sa-stat-card {
    height: 100%;
    border: 1px solid rgba(15, 76, 129, .12);
    border-top-width: 4px;
    border-radius: 22px;
    background: linear-gradient(180deg, #fff 0%, #f8fbff 100%);
    box-shadow: 0 16px 30px rgba(15, 23, 42, .05);
  }

  .sa-dashboard .sa-stat-card.card-primary { border-top-color: #0f4c81; }
  .sa-dashboard .sa-stat-card.card-success { border-top-color: #059669; }
  .sa-dashboard .sa-stat-card.card-dark { border-top-color: #334155; }
  .sa-dashboard .sa-stat-card.card-warning { border-top-color: #d97706; }

  .sa-dashboard .sa-stat-body {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.2rem;
  }

  .sa-dashboard .sa-stat-value {
    margin: .15rem 0 .25rem;
    font-size: 2rem;
    font-weight: 800;
    color: #14233c;
  }

  .sa-dashboard .sa-stat-icon {
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

  .sa-dashboard .sa-card {
    height: 100%;
    border: 1px solid rgba(15, 76, 129, .12);
    border-radius: 24px;
    background: #fff;
    box-shadow: 0 16px 30px rgba(15, 23, 42, .05);
  }

  .sa-dashboard .sa-card-header {
    padding: 1.15rem 1.25rem;
    border-bottom: 1px solid rgba(148, 163, 184, .16);
  }

  .sa-dashboard .sa-card-body {
    padding: 1.25rem;
  }

  .sa-dashboard .sa-note {
    color: #64748b;
    line-height: 1.6;
  }

  .sa-dashboard .sa-action-grid {
    display: grid;
    gap: .8rem;
    margin-top: 1rem;
  }

  .sa-dashboard .sa-action {
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

  .sa-dashboard .sa-action:hover {
    color: #14233c;
    box-shadow: 0 14px 28px rgba(15,23,42,.08);
    transform: translateY(-1px);
  }

  .sa-dashboard .sa-action i {
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

  .sa-dashboard .sa-action strong {
    display: block;
    margin-bottom: .1rem;
  }

  .sa-dashboard .sa-action span {
    display: block;
    color: #64748b;
    line-height: 1.5;
  }

  .sa-dashboard .sa-data-list {
    display: grid;
    gap: .75rem;
  }

  .sa-dashboard .sa-data-row {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding-bottom: .75rem;
    border-bottom: 1px dashed rgba(148,163,184,.28);
  }

  .sa-dashboard .sa-data-row:last-child {
    padding-bottom: 0;
    border-bottom: 0;
  }

  .sa-dashboard .sa-data-row strong {
    color: #14233c;
  }
</style>

<div class="container-fluid px-4 mt-4 sa-dashboard">
  <div class="row g-3">
    <div class="col-12">
      <div class="sa-hero">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-8">
            <div class="sa-eyebrow"><i class="ri ri-dashboard-line"></i> Ringkasan Operasional</div>
            <h1 class="sa-title"><?= html_escape($title) ?></h1>
            <p class="sa-subtitle">Pantau kondisi layanan lintas instansi, status antrian hari ini, dan titik yang perlu perhatian cepat dari satu dashboard yang lebih informatif.</p>
            <div class="sa-metric-grid">
              <div class="sa-metric"><small>Instansi aktif</small><strong><?= (int) $total_instansi_aktif ?></strong></div>
              <div class="sa-metric"><small>Layanan buka sekarang</small><strong><?= (int) $total_instansi_buka ?></strong></div>
              <div class="sa-metric"><small>Perlu verifikasi</small><strong><?= (int) $pending_verification ?></strong></div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="sa-summary">
              <div class="sa-eyebrow"><i class="ri ri-radar-line"></i> Fokus Hari Ini</div>
              <h3><?= (int) $antrian_hari_ini ?> antrian tercatat</h3>
              <p>Ringkasan cepat untuk membaca kondisi portal sebelum masuk ke pengelolaan detail.</p>
              <div class="sa-summary-list">
                <div class="sa-summary-row"><span>Instansi terpadat</span><strong><?= $instansi_teratas ? html_escape($instansi_teratas->nama_instansi) : 'Belum ada data' ?></strong></div>
                <div class="sa-summary-row"><span>Status selesai</span><strong><?= (int) ($status_hari_ini['selesai'] ?? 0) ?></strong></div>
                <div class="sa-summary-row"><span>Status dipanggil</span><strong><?= (int) ($status_hari_ini['dipanggil'] ?? 0) ?></strong></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-xl-3">
      <div class="sa-stat-card card-primary">
        <div class="sa-stat-body">
          <div>
            <div class="text-muted">Total Instansi</div>
            <div class="sa-stat-value"><?= (int) $total_instansi ?></div>
            <div class="small text-muted">Instansi terdaftar di portal</div>
          </div>
          <span class="sa-stat-icon"><i class="ri ri-government-line"></i></span>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="sa-stat-card card-success">
        <div class="sa-stat-body">
          <div>
            <div class="text-muted">Jenis Layanan</div>
            <div class="sa-stat-value"><?= (int) $total_layanan ?></div>
            <div class="small text-muted">Layanan yang bisa dipilih masyarakat</div>
          </div>
          <span class="sa-stat-icon"><i class="ri ri-briefcase-2-line"></i></span>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="sa-stat-card card-dark">
        <div class="sa-stat-body">
          <div>
            <div class="text-muted">Admin Layanan</div>
            <div class="sa-stat-value"><?= (int) $total_admin ?></div>
            <div class="small text-muted">Petugas internal yang aktif di UI</div>
          </div>
          <span class="sa-stat-icon"><i class="ri ri-user-settings-line"></i></span>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="sa-stat-card card-warning">
        <div class="sa-stat-body">
          <div>
            <div class="text-muted">Antrian Hari Ini</div>
            <div class="sa-stat-value"><?= (int) $antrian_hari_ini ?></div>
            <div class="small text-muted">Total antrian dari semua instansi</div>
          </div>
          <span class="sa-stat-icon"><i class="ri ri-team-line"></i></span>
        </div>
      </div>
    </div>

    <div class="col-xl-8">
      <div class="sa-card">
        <div class="sa-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div>
            <h5 class="mb-1">Tren Antrian 7 Hari Terakhir</h5>
            <div class="sa-note">Membantu melihat lonjakan trafik terbaru tanpa membuka laporan detail.</div>
          </div>
          <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">Update harian</span>
        </div>
        <div class="sa-card-body">
          <div id="superadminTrendChart" class="portal-chart"></div>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="sa-card">
        <div class="sa-card-header">
          <h5 class="mb-1">Status Antrian Hari Ini</h5>
          <div class="sa-note">Proporsi status aktif dan penyelesaian layanan hari ini.</div>
        </div>
        <div class="sa-card-body">
          <div id="superadminStatusChart" class="portal-chart"></div>
        </div>
      </div>
    </div>

    <div class="col-xl-5">
      <div class="sa-card">
        <div class="sa-card-header">
          <h5 class="mb-1">Ringkasan Tindakan</h5>
          <div class="sa-note">Arahkan perhatian ke proses yang paling mendesak hari ini.</div>
        </div>
        <div class="sa-card-body">
          <div class="sa-data-list">
            <div class="sa-data-row"><span>Perlu verifikasi</span><strong><?= (int) $pending_verification ?></strong></div>
            <div class="sa-data-row"><span>Status terdaftar</span><strong><?= (int) ($status_hari_ini['terdaftar'] ?? 0) ?></strong></div>
            <div class="sa-data-row"><span>Status batal</span><strong><?= (int) ($status_hari_ini['batal'] ?? 0) ?></strong></div>
          </div>
          <div class="mt-4">
            <h5 class="mb-2">Aksi Cepat</h5>
            <div class="sa-note">Masuk ke area operasional yang paling sering dipakai tanpa muter menu.</div>
            <div class="sa-action-grid">
              <a href="<?= base_url('superadmin/users') ?>" class="sa-action"><i class="ri ri-user-settings-line"></i><span><strong>Manajemen User</strong><span>Kelola akun internal dan masyarakat.</span></span></a>
              <a href="<?= base_url('superadmin/instansi') ?>" class="sa-action"><i class="ri ri-government-line"></i><span><strong>Instansi</strong><span>Atur struktur layanan dan operasional instansi.</span></span></a>
              <a href="<?= base_url('superadmin/kelola_layanan') ?>" class="sa-action"><i class="ri ri-toggle-line"></i><span><strong>Status Layanan</strong><span>Buka, tutup, dan kontrol mode operasional.</span></span></a>
              <a href="<?= base_url('antrian_display') ?>" class="sa-action"><i class="ri ri-tv-2-line"></i><span><strong>Monitor Display</strong><span>Kelola tampilan monitor antrian umum dan sektor.</span></span></a>
              <a href="<?= base_url('pendaftaran/manual') ?>" class="sa-action"><i class="ri ri-user-follow-line"></i><span><strong>Front Desk</strong><span>Layani pendaftaran offline dan bantuan di loket.</span></span></a>
              <a href="<?= base_url('superadmin/verifikasi_user') ?>" class="sa-action"><i class="ri ri-shield-check-line"></i><span><strong>Verifikasi Akun</strong><span>Periksa akun baru yang masih menunggu aktivasi.</span></span></a>
            </div>
            <div class="alert alert-primary mt-3 mb-0">
              <strong><?= html_escape($nama_user) ?></strong>, saat ini ada <strong><?= (int) $pending_verification ?></strong> akun menunggu verifikasi dan <strong><?= (int) $total_instansi_buka ?></strong> instansi yang sedang membuka layanan.
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-7">
      <div class="sa-card">
        <div class="sa-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div>
            <h5 class="mb-1">Instansi Tersibuk Hari Ini</h5>
            <div class="sa-note">Peringkat cepat untuk memantau titik layanan dengan beban paling tinggi.</div>
          </div>
          <a href="<?= base_url('laporan/rekap_antrian') ?>" class="btn btn-sm btn-outline-primary">
            <i class="ri ri-bar-chart-box-line me-1"></i> Buka Laporan
          </a>
        </div>
        <div class="sa-card-body">
          <?php if (empty($top_instansi)): ?>
            <div class="portal-empty">Belum ada antrian yang tercatat hari ini.</div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table align-middle">
                <thead>
                  <tr>
                    <th style="width: 72px;">Peringkat</th>
                    <th>Instansi</th>
                    <th class="text-end">Total Antrian</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($top_instansi as $index => $row): ?>
                    <tr>
                      <td><span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">#<?= $index + 1 ?></span></td>
                      <td class="fw-semibold"><?= html_escape($row->nama_instansi) ?></td>
                      <td class="text-end fw-bold"><?= (int) $row->total ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const trendEl = document.querySelector('#superadminTrendChart');
  const statusEl = document.querySelector('#superadminStatusChart');

  if (trendEl && window.ApexCharts) {
    new ApexCharts(trendEl, {
      chart: {
        type: 'area',
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
      stroke: {
        curve: 'smooth',
        width: 3
      },
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.34,
          opacityTo: 0.06
        }
      },
      colors: ['#0f4c81'],
      dataLabels: { enabled: false },
      grid: {
        borderColor: '#e2e8f0',
        strokeDashArray: 4
      },
      tooltip: {
        y: {
          formatter: function (value) {
            return value + ' antrian';
          }
        }
      }
    }).render();
  }

  if (statusEl && window.ApexCharts) {
    new ApexCharts(statusEl, {
      chart: {
        type: 'donut',
        height: 320
      },
      series: <?= json_encode($status_chart_series) ?>,
      labels: <?= json_encode($status_chart_labels) ?>,
      colors: ['#3b82f6', '#f59e0b', '#22c55e', '#ef4444'],
      stroke: { width: 0 },
      legend: {
        position: 'bottom'
      },
      dataLabels: {
        enabled: true
      }
    }).render();
  }
});
</script>
