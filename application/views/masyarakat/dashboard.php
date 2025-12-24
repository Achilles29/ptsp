<div class="container-fluid px-4 mt-4">

  <!-- Judul -->
  <h4 class="text-maroon mb-3">
    <i class="fas fa-home me-2"></i>Dashboard Masyarakat
  </h4>

  <!-- Welcome Banner -->
  <div class="alert alert-primary shadow-sm rounded-3 py-3 px-4 d-flex justify-content-between align-items-center">
    <div>
      <div class="fw-semibold fs-5 mb-1">
        👋 Selamat datang, <strong><?= $this->session->userdata('nama_lengkap') ?></strong>!
      </div>
      <div class="text-muted small">
        Akses layanan publik kini lebih mudah, cepat, dan transparan.
      </div>
    </div>

    <a href="<?= base_url('masyarakat/daftar_antrian') ?>"
      class="btn btn-success btn-sm rounded-pill px-3 shadow-sm">
      <i class="fas fa-plus-circle me-1"></i> Daftar Antrian Baru
    </a>
  </div>

  <!-- Ringkasan -->
  <div class="row mb-4 g-4">

    <!-- Antrian Aktif -->
    <div class="col-md-4">
      <div class="card h-100 shadow-sm border-0 rounded-3">
        <div class="card-body">

          <h6 class="text-muted mb-2">
            <i class="fas fa-calendar-check me-2 text-success"></i>
            Antrian Aktif
          </h6>

          <?php if ($antrian_detail): ?>

            <div class="fs-3 fw-bold text-success"><?= $antrian_detail['my_number'] ?></div>

            <div class="small mt-2">
              <div class="mb-1">
                Sedang Dipanggil:
                <strong class="text-dark">
                  <?= $antrian_detail['called_number'] ?: 'Belum ada' ?>
                </strong>
              </div>

              <div>
                Sisa Antrian:
                <strong class="text-dark">
                  <?= is_numeric($antrian_detail['remaining'])
                    ? $antrian_detail['remaining'] . ' orang'
                    : $antrian_detail['remaining']; ?>
                </strong>
              </div>
            </div>

          <?php else: ?>
            <div class="fs-3 fw-bold text-muted">–</div>
            <div class="small text-muted">Tidak ada antrian aktif</div>
          <?php endif; ?>

        </div>
      </div>
    </div>

    <!-- Riwayat -->
    <div class="col-md-4">
      <div class="card h-100 shadow-sm border-0 rounded-3">
        <div class="card-body">

          <h6 class="text-muted mb-2">
            <i class="fas fa-history me-2 text-primary"></i>
            Riwayat Antrian
          </h6>

          <div class="fs-3 fw-bold text-primary"><?= $riwayat ?></div>
          <div class="small text-muted mt-1">Layanan yang pernah Anda gunakan</div>

        </div>
      </div>
    </div>

    <!-- Chat -->
    <div class="col-md-4">
      <div class="card h-100 shadow-sm border-0 rounded-3">
        <div class="card-body">

          <h6 class="text-muted mb-2">
            <i class="fas fa-comment-dots me-2 text-warning"></i>
            Pesan ke CS
          </h6>

          <div class="fs-3 fw-bold text-warning"><?= $chat_pending ?></div>
          <div class="small text-muted mt-1">Menunggu balasan dari CS</div>

        </div>
      </div>
    </div>

  </div>

  <!-- Quick Actions -->
  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body">
      <h5 class="mb-4">
        <i class="fas fa-bolt text-danger me-2"></i>Aksi Cepat
      </h5>

      <div class="row text-center g-3">

        <div class="col-6 col-md-3">
          <a href="<?= base_url('masyarakat/daftar_antrian') ?>"
            class="btn btn-outline-primary w-100 py-3 rounded-3 shadow-sm">
            <i class="fas fa-plus-square fa-lg mb-2"></i><br>
            Daftar Antrian
          </a>
        </div>

        <div class="col-6 col-md-3">
          <a href="<?= base_url('masyarakat/antrian_saya') ?>"
            class="btn btn-outline-success w-100 py-3 rounded-3 shadow-sm">
            <i class="fas fa-ticket-alt fa-lg mb-2"></i><br>
            Antrian Saya
          </a>
        </div>

        <div class="col-6 col-md-3">
          <a href="<?= base_url('masyarakat/riwayat_antrian') ?>"
            class="btn btn-outline-secondary w-100 py-3 rounded-3 shadow-sm">
            <i class="fas fa-list fa-lg mb-2"></i><br>
            Riwayat
          </a>
        </div>

        <div class="col-6 col-md-3">
          <a href="<?= base_url('masyarakat/chat') ?>"
            class="btn btn-outline-warning w-100 py-3 rounded-3 shadow-sm">
            <i class="fas fa-comment-alt fa-lg mb-2"></i><br>
            Chat CS
          </a>
        </div>

      </div>
    </div>
  </div>

</div>