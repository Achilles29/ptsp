<?php
$total = $ringkasan['total'] ?? 0;
$belum = $ringkasan['belum'] ?? 0;
$selesai = $ringkasan['selesai'] ?? 0;
$jumlah_layanan = $jumlah_layanan ?? 0;
?>

<!-- Tambahan informasi user -->
<div class="alert alert-secondary small">
  <i class="fas fa-user me-1"></i> Login sebagai: <strong><?= $this->session->userdata('username') ?></strong>
</div>

<div class="row mb-4">
  <div class="col-md-4">
    <div class="card border-start border-primary shadow-sm">
      <div class="card-body">
        <h6 class="text-muted"><i class="fas fa-users me-1 text-primary"></i> Total Antrian Hari Ini</h6>
        <h3 class="text-primary fw-bold"><?= $total ?></h3>
        <div class="small">Belum dipanggil: <?= $belum ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-start border-success shadow-sm">
      <div class="card-body">
        <h6 class="text-muted"><i class="fas fa-check-circle me-1 text-success"></i> Selesai Dilayani</h6>
        <h3 class="text-success fw-bold"><?= $selesai ?></h3>
        <div class="small">Layanan berhasil ditangani</div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-start border-info shadow-sm">
      <div class="card-body">
        <h6 class="text-muted"><i class="fas fa-cogs me-1 text-info"></i> Layanan Aktif</h6>
        <h3 class="text-info fw-bold"><?= $jumlah_layanan ?></h3>
        <div class="small">Jumlah jenis layanan pada instansi ini</div>
      </div>
    </div>
  </div>
</div>