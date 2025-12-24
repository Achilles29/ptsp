<div class="container-fluid px-4 mt-4">

  <h4 class="text-maroon mb-3">
    <i class="ri ri-user-star-line me-2"></i><?= $title ?>
  </h4>

  <p class="mb-4">
    Selamat datang, <strong><?= $this->session->userdata('nama') ?></strong> 👋 <br>
    <small class="text-muted">Anda masuk sebagai Super Admin</small>
  </p>

  <!-- Ringkasan -->
  <div class="row g-3 mb-4">

    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-primary text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-white">Instansi</h6>
            <h3 class="fw-bold"><?= $total_instansi ?></h3>
          </div>
          <i class="ri ri-government-fill fs-1 opacity-75"></i>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-success text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-white">Jenis Layanan</h6>
            <h3 class="fw-bold"><?= $total_layanan ?></h3>
          </div>
          <i class="ri ri-briefcase-2-fill fs-1 opacity-75"></i>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-info text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h6 class="text-white">Admin Layanan</h6>
            <h3 class="fw-bold"><?= $total_admin ?></h3>
          </div>
          <i class="ri ri-admin-line fs-1 opacity-75"></i>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-warning text-dark">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h6>Customer Service</h6>
            <h3 class="fw-bold"><?= $total_cs ?></h3>
          </div>
          <i class="ri ri-headphone-line fs-1 opacity-75"></i>
        </div>
      </div>
    </div>

  </div>

  <!-- Antrian Hari Ini -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-maroon text-white">
      <strong><i class="ri ri-time-line me-1"></i> Antrian Hari Ini</strong>
    </div>
    <div class="card-body">
      <h2 class="fw-bold text-maroon"><?= $antrian_hari_ini ?></h2>
      <p class="text-muted">Total semua instansi hari ini.</p>
    </div>
  </div>

  <!-- Tabel per instansi -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light">
      <strong><i class="ri ri-building-4-line me-1"></i> Antrian per Instansi (Hari Ini)</strong>
    </div>
    <div class="card-body">

      <?php if (empty($antrian_per_instansi)): ?>
        <p class="text-muted">Belum ada antrian hari ini.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="bg-maroon text-white">
              <tr>
                <th>Instansi</th>
                <th>Total Antrian</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($antrian_per_instansi as $row): ?>
                <tr>
                  <td><?= $row->nama_instansi ?></td>
                  <td><strong><?= $row->total ?></strong></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <!-- Aksi cepat -->
  <div class="row g-3">

    <div class="col-md-3">
      <a href="<?= base_url('jenislayanan'); ?>" class="btn btn-maroon w-100 py-3 shadow-sm">
        <i class="ri ri-briefcase-2-line me-2"></i> Kelola Jenis Layanan
      </a>
    </div>

    <div class="col-md-3">
      <a href="<?= base_url('superadmin/users'); ?>" class="btn btn-outline-maroon w-100 py-3 shadow-sm">
        <i class="ri ri-user-settings-line me-2"></i> Kelola Admin / CS
      </a>
    </div>

    <div class="col-md-3">
      <a href="<?= base_url('antrian_display'); ?>" class="btn btn-outline-primary w-100 py-3 shadow-sm">
        <i class="ri ri-tv-2-line me-2"></i> Monitor Display
      </a>
    </div>

    <div class="col-md-3">
      <a href="<?= base_url('pendaftaran/manual'); ?>" class="btn btn-outline-dark w-100 py-3 shadow-sm">
        <i class="ri ri-user-follow-line me-2"></i> Front Desk
      </a>
    </div>

  </div>

</div>