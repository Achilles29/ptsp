<div class="nm-page-intro">
  <div class="page-pretitle">Status Layanan</div>
  <h2 class="page-title">
    <i class="ti ti-ticket me-2"></i> Antrian Saya
  </h2>
</div>

<div class="row g-3">

  <?php if (empty($antrian)): ?>

    <div class="col-12">
      <div class="card">
        <div class="card-body nm-empty-state">
          <div class="avatar avatar-xl bg-yellow-lt mb-3">
            <i class="ti ti-info-circle fs-1"></i>
          </div>
          <h3 class="mb-1">Belum Ada Antrian</h3>
          <div class="text-muted mb-3">
            Anda belum memiliki antrian aktif saat ini
          </div>
          <a href="<?= base_url('masyarakat/daftar_antrian') ?>"
             class="btn btn-primary">
            <i class="ti ti-circle-plus me-1"></i>
            Daftar Antrian
          </a>
        </div>
      </div>
    </div>

  <?php else: ?>
    <?php foreach ($antrian as $a): ?>

      <div class="col-12">
        <div class="card nm-queue-card">
          <div class="card-body">

            <!-- Nomor & Status -->
            <div class="d-flex align-items-center justify-content-between gap-3 flex-column flex-md-row">
              <div>
                <div class="text-muted">Nomor Antrian</div>
                <div class="nm-queue-number">
                  <?= $a->nomor_antrian ?>
                </div>
              </div>

              <?php if ($a->hadir): ?>
                <span class="badge bg-success-lt fs-6 px-3 py-2">
                  <i class="ti ti-check me-1"></i>Sudah Check-In
                </span>
              <?php else: ?>
                <span class="badge bg-secondary-lt fs-6 px-3 py-2">
                  <i class="ti ti-clock me-1"></i>Belum Check-In
                </span>
              <?php endif; ?>
            </div>

            <hr class="my-3">

            <div class="nm-queue-meta">
              <div class="nm-queue-meta-item">
                <i class="ti ti-building"></i>
                <div>
                  <div class="fw-semibold"><?= $a->nama_instansi ?></div>
                  <div class="text-muted">Instansi tujuan</div>
                </div>
              </div>

              <div class="nm-queue-meta-item">
                <i class="ti ti-list-details"></i>
                <div>
                  <div class="fw-semibold"><?= $a->nama_layanan ?></div>
                  <div class="text-muted">Jenis layanan yang dipilih</div>
                </div>
              </div>

              <div class="nm-queue-meta-item">
                <i class="ti ti-calendar-event"></i>
                <div>
                  <div class="fw-semibold"><?= date('d M Y', strtotime($a->tanggal)) ?></div>
                  <div class="text-muted">Tanggal kunjungan</div>
                </div>
              </div>
            </div>

            <!-- Action -->
            <div class="mt-4">

              <?php if (!$a->hadir): ?>
                <a href="<?= site_url("masyarakat/scan_qr/$a->id") ?>"
                   class="btn btn-outline-primary w-100 btn-lg">
                  <i class="ti ti-qrcode me-1"></i>
                  Scan QR Check-In
                </a>
              <?php else: ?>
                <div class="alert alert-success text-center mb-0 py-2">
                  <i class="ti ti-checks me-1"></i>
                  Anda sudah melakukan check-in
                </div>
              <?php endif; ?>

            </div>

          </div>
        </div>
      </div>

    <?php endforeach; ?>
  <?php endif; ?>

</div>
