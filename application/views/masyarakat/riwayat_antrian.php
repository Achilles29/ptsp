<div class="nm-page-intro">
  <div class="page-pretitle">Riwayat Layanan</div>
  <h2 class="page-title">
    <i class="ti ti-history me-2"></i><?= $title ?>
  </h2>
</div>

<div class="row g-3">

  <!-- Flash -->
  <?php if ($this->session->flashdata('success')): ?>
    <div class="col-12">
      <div class="alert alert-success">
        <i class="ti ti-check me-2"></i>
        <?= $this->session->flashdata('success') ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if (empty($antrian)): ?>

    <div class="col-12">
      <div class="card">
        <div class="card-body nm-empty-state">
          <div class="avatar avatar-xl bg-secondary-lt mb-3">
            <i class="ti ti-history fs-1"></i>
          </div>
          <h3 class="mb-1">Belum Ada Riwayat</h3>
          <div class="text-muted">
            Riwayat antrian Anda akan muncul di sini
          </div>
        </div>
      </div>
    </div>

  <?php else: ?>
    <?php foreach ($antrian as $a): ?>

      <div class="col-12">
        <div class="card nm-queue-card">
          <div class="card-body">

            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between gap-3 mb-2 flex-column flex-md-row">
              <div>
                <div class="text-muted">Nomor Antrian</div>
                <div class="nm-queue-number" style="font-size:2.2rem;">
                  <?= $a->nomor_antrian ?>
                </div>
              </div>

              <!-- Status -->
              <?php if ($a->status == 'terdaftar'): ?>
                <span class="badge bg-blue-lt fs-6 px-3 py-2">
                  <i class="ti ti-circle-check me-1"></i>Terdaftar
                </span>
              <?php elseif ($a->status == 'dipanggil'): ?>
                <span class="badge bg-yellow-lt fs-6 px-3 py-2">
                  <i class="ti ti-volume me-1"></i>Dipanggil
                </span>
              <?php elseif ($a->status == 'selesai'): ?>
                <span class="badge bg-green-lt fs-6 px-3 py-2">
                  <i class="ti ti-check me-1"></i>Selesai
                </span>
              <?php else: ?>
                <span class="badge bg-red-lt fs-6 px-3 py-2">
                  <i class="ti ti-x me-1"></i>Batal
                </span>
              <?php endif; ?>
            </div>

            <hr class="my-2">

            <div class="nm-queue-meta">
              <div class="nm-queue-meta-item">
                <i class="ti ti-list-details"></i>
                <div>
                  <div class="fw-semibold"><?= $a->nama_layanan ?></div>
                  <div class="text-muted">Layanan yang Anda ambil</div>
                </div>
              </div>

              <div class="nm-queue-meta-item">
                <i class="ti ti-calendar-event"></i>
                <div>
                  <div class="fw-semibold"><?= date('d M Y', strtotime($a->tanggal)) ?></div>
                  <div class="text-muted">Tanggal pendaftaran</div>
                </div>
              </div>
            </div>

            <!-- Action -->
            <div class="mt-3">

              <?php if (in_array($a->status, ['terdaftar','dipanggil'])): ?>
                <a href="<?= base_url('masyarakat/batalkan_antrian/'.$a->id) ?>"
                   class="btn btn-outline-danger w-100"
                   onclick="return confirm('Yakin ingin membatalkan antrian ini?')">
                  <i class="ti ti-x me-1"></i>
                  Batalkan Antrian
                </a>
              <?php else: ?>
                <div class="text-muted text-center">
                  Tidak ada aksi tersedia
                </div>
              <?php endif; ?>

            </div>

          </div>
        </div>
      </div>

    <?php endforeach; ?>
  <?php endif; ?>

</div>
