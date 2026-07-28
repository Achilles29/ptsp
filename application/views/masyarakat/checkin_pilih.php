<div class="nm-page-intro">
  <div class="page-pretitle">Check-In Kunjungan</div>
  <h2 class="page-title">
    <i class="ti ti-qrcode me-2"></i>Pilih Antrian
  </h2>
</div>

<div class="row g-3">
  <div class="col-12">
    <div class="alert alert-info">
      <i class="ti ti-info-circle me-2"></i>
      Akun Anda memiliki lebih dari satu antrian hari ini. Pilih nomor yang sedang Anda check-in di lokasi layanan.
    </div>
  </div>

  <?php foreach ($antrian as $a): ?>
    <div class="col-12">
      <form action="<?= site_url('masyarakat/checkin_submit') ?>" method="post" class="card nm-queue-card">
        <input type="hidden" name="antrian_id" value="<?= (int) $a->id ?>">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
              <div class="text-muted">Nomor Antrian</div>
              <div class="nm-queue-number"><?= html_escape($a->nomor_antrian) ?></div>
            </div>
            <span class="badge bg-secondary-lt fs-6 px-3 py-2">
              <i class="ti ti-clock me-1"></i>Belum Check-In
            </span>
          </div>

          <hr class="my-3">

          <div class="nm-queue-meta mb-4">
            <div class="nm-queue-meta-item">
              <i class="ti ti-building"></i>
              <div>
                <div class="fw-semibold"><?= html_escape($a->nama_instansi) ?></div>
                <div class="text-muted">Instansi tujuan</div>
              </div>
            </div>
            <div class="nm-queue-meta-item">
              <i class="ti ti-list-details"></i>
              <div>
                <div class="fw-semibold"><?= html_escape($a->nama_layanan) ?></div>
                <div class="text-muted">Jenis layanan</div>
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

          <button type="submit" class="btn btn-primary w-100 btn-lg">
            <i class="ti ti-check me-1"></i>
            Check-In Nomor Ini
          </button>
        </div>
      </form>
    </div>
  <?php endforeach; ?>
</div>
