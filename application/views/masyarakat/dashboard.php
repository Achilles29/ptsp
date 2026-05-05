<?php
$nama_lengkap = trim((string) ($this->session->userdata('nama_lengkap') ?? 'Pengguna'));
$nama_pendek = strtok($nama_lengkap, ' ') ?: $nama_lengkap;
$layanan_aktif_count = is_array($layanan_aktif ?? null) ? count($layanan_aktif) : 0;
$layanan_aktif_preview = [];
if (!empty($layanan_aktif)) {
  foreach ($layanan_aktif as $layanan_item) {
    if (!empty($layanan_item->nama_layanan)) {
      $layanan_aktif_preview[] = $layanan_item->nama_layanan;
    }
    if (count($layanan_aktif_preview) >= 2) {
      break;
    }
  }
}
?>

<style>
  .masyarakat-dashboard .quick-action-card {
    display: block;
    height: 100%;
    padding: 1rem 1.1rem;
    border-radius: 20px;
    text-decoration: none;
    border: 1px solid rgba(148, 163, 184, 0.18);
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    color: #14233c;
    transition: transform .18s ease, box-shadow .18s ease;
  }

  .masyarakat-dashboard .quick-action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 18px 34px rgba(15, 23, 42, 0.1);
    color: #14233c;
  }

  .masyarakat-dashboard .quick-action-head {
    display: flex;
    align-items: center;
    gap: .8rem;
    margin-bottom: .55rem;
  }

  .masyarakat-dashboard .quick-action-icon {
    width: 2.8rem;
    height: 2.8rem;
    border-radius: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #0f4c81;
    flex: 0 0 2.8rem;
  }

  .masyarakat-dashboard .quick-action-title {
    font-size: 1rem;
    font-weight: 800;
    color: #14233c;
  }

  .masyarakat-dashboard .quick-action-text {
    margin: 0;
    color: #485a75;
    line-height: 1.6;
    font-size: .93rem;
  }
</style>

<div class="row g-3 g-xl-4 masyarakat-dashboard">
  <div class="col-12">
    <div class="card nm-hero-card" style="background-color:#0a2f53;">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <div class="page-pretitle text-white-50">Portal Masyarakat</div>
          <h2 class="mb-2">Selamat datang, <?= html_escape($nama_pendek) ?>.</h2>
          <p class="mb-0 opacity-75">
            Kelola pendaftaran antrian, cek status layanan, dan pantau riwayat kunjungan Anda dalam satu portal yang nyaman di semua perangkat.
          </p>

          <div class="nm-hero-actions">
            <a href="<?= base_url('masyarakat/daftar_antrian') ?>" class="btn btn-light">
              <i class="ti ti-circle-plus me-1"></i> Daftar Antrian Baru
            </a>
            <a href="<?= base_url('masyarakat/antrian_saya') ?>" class="btn btn-outline-light">
              <i class="ti ti-ticket me-1"></i> Lihat Antrian Aktif
            </a>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="row g-2">
            <div class="col-6 col-lg-12">
              <div class="nm-hero-stat">
                <small>Antrian aktif</small>
                <strong><?= (int) $aktif ?></strong>
              </div>
            </div>
            <div class="col-6 col-lg-12">
              <div class="nm-hero-stat">
                <small>Total riwayat</small>
                <strong><?= (int) $riwayat ?></strong>
              </div>
            </div>
            <div class="col-12">
              <div class="nm-hero-stat">
                <small>Status hari ini</small>
                <strong><?= $antrian_detail ? $antrian_detail['my_number'] : 'Belum ada antrian aktif' ?></strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-6 col-xl-4">
    <div class="card nm-kpi-card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between gap-3">
          <div>
            <div class="text-muted mb-1">Antrian Aktif</div>
            <div class="nm-kpi-value text-primary"><?= (int) $aktif ?></div>
            <div class="small text-muted">Nomor aktif yang masih berjalan hari ini</div>
          </div>
          <span class="avatar bg-primary-lt">
            <i class="ti ti-ticket"></i>
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-6 col-xl-4">
    <div class="card nm-kpi-card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between gap-3">
          <div>
            <div class="text-muted mb-1">Riwayat Kunjungan</div>
            <div class="nm-kpi-value text-info"><?= (int) $riwayat ?></div>
            <div class="small text-muted">Total pendaftaran yang pernah Anda lakukan</div>
          </div>
          <span class="avatar bg-azure-lt">
            <i class="ti ti-history"></i>
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-6 col-xl-4">
    <div class="card nm-kpi-card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between gap-3">
          <div>
            <div class="text-muted mb-1">Layanan Aktif</div>
            <div class="nm-kpi-value text-warning"><?= (int) $layanan_aktif_count ?></div>
            <div class="small text-muted">
              <?= !empty($layanan_aktif_preview) ? html_escape(implode(', ', $layanan_aktif_preview)) : 'Belum ada layanan aktif yang sedang Anda ikuti' ?>
            </div>
          </div>
          <span class="avatar bg-yellow-lt">
            <i class="ti ti-building-community"></i>
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-xl-7">
    <div class="card nm-queue-card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start gap-3 flex-column flex-md-row">
          <div>
            <div class="page-pretitle">Ringkasan Antrian</div>
            <h3 class="mb-1">Status layanan Anda saat ini</h3>
            <p class="text-muted mb-0">Pantau nomor yang sedang berjalan dan estimasi sisa antrean tanpa harus refresh berulang.</p>
          </div>

          <a href="<?= base_url('masyarakat/antrian_saya') ?>" class="btn btn-outline-primary">
            <i class="ti ti-arrow-right me-1"></i> Detail Antrian
          </a>
        </div>

        <?php if ($antrian_detail): ?>
          <div class="row g-3 mt-1 align-items-end">
            <div class="col-md-5">
              <div class="text-muted">Nomor Anda</div>
              <div class="nm-queue-number"><?= $antrian_detail['my_number'] ?></div>
            </div>
            <div class="col-md-7">
              <div class="nm-queue-meta">
                <div class="nm-queue-meta-item">
                  <i class="ti ti-volume"></i>
                  <div>
                    <div class="fw-semibold">Sedang dipanggil</div>
                    <div class="text-muted"><?= $antrian_detail['called_number'] ?: 'Belum ada panggilan' ?></div>
                  </div>
                </div>
                <div class="nm-queue-meta-item">
                  <i class="ti ti-users-group"></i>
                  <div>
                    <div class="fw-semibold">Perkiraan sisa antrean</div>
                    <div class="text-muted">
                      <?= is_numeric($antrian_detail['remaining']) ? $antrian_detail['remaining'] . ' orang lagi' : $antrian_detail['remaining']; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php else: ?>
          <div class="nm-empty-state">
            <span class="avatar avatar-xl bg-yellow-lt mb-3">
              <i class="ti ti-ticket-off fs-1"></i>
            </span>
            <h3 class="mb-1">Belum ada antrian aktif</h3>
            <p class="text-muted mb-3">Saat Anda membuat antrian baru, statusnya akan langsung tampil di sini.</p>
            <a href="<?= base_url('masyarakat/daftar_antrian') ?>" class="btn btn-primary">
              <i class="ti ti-circle-plus me-1"></i> Buat Antrian Sekarang
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-12 col-xl-5">
    <div class="card h-100">
      <div class="card-body">
        <div class="page-pretitle">Aksi Cepat</div>
        <h3 class="mb-1">Masuk ke fitur yang paling sering dipakai</h3>
        <p class="text-muted">Desain ini tetap nyaman di desktop, tablet, maupun ponsel.</p>

        <div class="row g-3 mt-1">
          <div class="col-12 col-sm-6">
            <a class="quick-action-card" href="<?= base_url('masyarakat/daftar_antrian') ?>">
              <div class="quick-action-head">
                <span class="quick-action-icon"><i class="ti ti-circle-plus"></i></span>
                <span class="quick-action-title">Daftar Antrian Baru</span>
              </div>
              <p class="quick-action-text">Pilih instansi dan layanan yang ingin Anda kunjungi.</p>
            </a>
          </div>
          <div class="col-12 col-sm-6">
            <a class="quick-action-card" href="<?= base_url('masyarakat/antrian_saya') ?>">
              <div class="quick-action-head">
                <span class="quick-action-icon"><i class="ti ti-ticket"></i></span>
                <span class="quick-action-title">Lihat Antrian Aktif</span>
              </div>
              <p class="quick-action-text">Pantau nomor berjalan dan sisa antrean Anda secara langsung.</p>
            </a>
          </div>
          <div class="col-12 col-sm-6">
            <a class="quick-action-card" href="<?= base_url('masyarakat/riwayat_antrian') ?>">
              <div class="quick-action-head">
                <span class="quick-action-icon"><i class="ti ti-history"></i></span>
                <span class="quick-action-title">Riwayat Kunjungan</span>
              </div>
              <p class="quick-action-text">Cek daftar pendaftaran yang pernah Anda lakukan sebelumnya.</p>
            </a>
          </div>
          <div class="col-12 col-sm-6">
            <a class="quick-action-card" href="<?= base_url('auth/logout') ?>">
              <div class="quick-action-head">
                <span class="quick-action-icon"><i class="ti ti-logout"></i></span>
                <span class="quick-action-title">Keluar dari Akun</span>
              </div>
              <p class="quick-action-text">Aman untuk digunakan saat Anda sudah selesai memakai portal.</p>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
