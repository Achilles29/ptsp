<?php
$seg1 = $this->uri->segment(1);
$seg2 = $this->uri->segment(2);
$current = $seg1 . '/' . $seg2;

if (!function_exists('masyarakat_nav_active')) {
  function masyarakat_nav_active($current, $target)
  {
    return $current === $target ? 'is-active' : '';
  }
}

$nama_lengkap = trim((string) ($this->session->userdata('nama_lengkap') ?? 'Pengguna'));
$nama_pendek = strtok($nama_lengkap, ' ') ?: $nama_lengkap;
$inisial = strtoupper(substr($nama_lengkap, 0, 1));
$nav_items = [
  ['href' => base_url('masyarakat/dashboard'), 'target' => 'masyarakat/dashboard', 'icon' => 'ti ti-home', 'label' => 'Beranda'],
  ['href' => base_url('masyarakat/daftar_antrian'), 'target' => 'masyarakat/daftar_antrian', 'icon' => 'ti ti-circle-plus', 'label' => 'Daftar Antrian'],
  ['href' => base_url('masyarakat/antrian_saya'), 'target' => 'masyarakat/antrian_saya', 'icon' => 'ti ti-ticket', 'label' => 'Antrian Saya'],
  ['href' => base_url('masyarakat/riwayat_antrian'), 'target' => 'masyarakat/riwayat_antrian', 'icon' => 'ti ti-history', 'label' => 'Riwayat'],
];
?>
<header class="nm-public-header">
  <div class="container-xl app-container">
    <div class="nm-public-topbar">
      <a class="nm-public-brand" href="<?= base_url('masyarakat/dashboard') ?>">
        <span class="nm-public-brand-mark">
          <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo MPP">
        </span>
        <span class="nm-public-brand-text">
          <strong>MPP Rembang</strong>
          <span>Layanan publik digital yang responsif</span>
        </span>
      </a>

      <nav class="nm-public-nav d-none d-lg-flex">
        <?php foreach ($nav_items as $item): ?>
          <a class="nm-public-nav-link <?= masyarakat_nav_active($current, $item['target']) ?>" href="<?= $item['href'] ?>">
            <?= $item['label'] ?>
          </a>
        <?php endforeach; ?>
      </nav>

      <div class="nm-public-actions">
        <div class="nm-user-chip d-none d-md-inline-flex">
          <span class="nm-user-avatar"><?= $inisial ?></span>
          <span class="nm-user-meta">
            <strong><?= html_escape($nama_pendek) ?></strong>
            <span>Akun masyarakat</span>
          </span>
        </div>

        <div class="dropdown">
          <button class="btn nm-user-menu dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="ti ti-user-circle me-1"></i> Menu
          </button>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <div class="dropdown-header">
              <div class="fw-bold"><?= html_escape($nama_lengkap) ?></div>
              <small class="text-muted">Akun Masyarakat</small>
            </div>
            <a class="dropdown-item" href="<?= base_url('masyarakat/dashboard') ?>">
              <i class="ti ti-home me-2"></i>Beranda
            </a>
            <a class="dropdown-item" href="<?= base_url('masyarakat/daftar_antrian') ?>">
              <i class="ti ti-circle-plus me-2"></i>Daftar Antrian
            </a>
            <a class="dropdown-item" href="<?= base_url('masyarakat/antrian_saya') ?>">
              <i class="ti ti-ticket me-2"></i>Antrian Saya
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>">
              <i class="ti ti-logout me-2"></i>Logout
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="page-body">
  <div class="container-xl app-container">
    <?php if ($this->session->flashdata('success')): ?>
      <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>
