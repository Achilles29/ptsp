<?php
$seg1 = $this->uri->segment(1);
$seg2 = $this->uri->segment(2);
$current = $seg1.'/'.$seg2;
if (!function_exists('masyarakat_nav_active')) {
  function masyarakat_nav_active($current, $target)
  {
    return ($current === $target) ? 'is-active' : '';
  }
}
?>
</div><!-- end container -->
</div><!-- end page-body -->

<nav class="nm-mobile-nav">
  <div class="container-xl app-container">
    <div class="nm-mobile-nav-shell">
      <a class="nm-mobile-nav-link <?= masyarakat_nav_active($current,'masyarakat/dashboard') ?>" href="<?= base_url('masyarakat/dashboard') ?>">
        <i class="ti ti-home"></i>
        <span>Beranda</span>
      </a>
      <a class="nm-mobile-nav-link <?= masyarakat_nav_active($current,'masyarakat/daftar_antrian') ?>" href="<?= base_url('masyarakat/daftar_antrian') ?>">
        <i class="ti ti-circle-plus"></i>
        <span>Daftar</span>
      </a>
      <a class="nm-mobile-nav-link <?= masyarakat_nav_active($current,'masyarakat/antrian_saya') ?>" href="<?= base_url('masyarakat/antrian_saya') ?>">
        <i class="ti ti-ticket"></i>
        <span>Antrian</span>
      </a>
      <a class="nm-mobile-nav-link <?= masyarakat_nav_active($current,'masyarakat/riwayat_antrian') ?>" href="<?= base_url('masyarakat/riwayat_antrian') ?>">
        <i class="ti ti-history"></i>
        <span>Riwayat</span>
      </a>
    </div>
  </div>
</nav>
