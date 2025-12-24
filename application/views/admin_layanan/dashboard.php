<div class="container-fluid px-4 mt-4">

  <h4 class="text-maroon mb-3">
    <i class="ri ri-building-4-line me-2"></i> <?= $title ?>
  </h4>

  <!-- Banner Selamat Datang -->
  <div class="alert alert-primary shadow-sm d-flex justify-content-between align-items-center">
    <div>
      <strong>Halo, <?= $this->session->userdata('nama_lengkap') ?>!</strong><br>
      Anda bertugas di instansi:
      <span class="fw-bold"><?= $this->session->userdata('instansi_nama') ?></span>
    </div>

    <a href="<?= base_url('admin_layanan/antrian_hari_ini') ?>" class="btn btn-maroon btn-sm">
      <i class="ri ri-list-check"></i> Lihat Antrian
    </a>
  </div>

  <!-- RINGKASAN -->
  <div class="row g-3 mb-4">

    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-success text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="small">Total Antrian</div>
            <h3 class="fw-bold"><?= $ringkasan['total'] ?></h3>
          </div>
          <i class="ri ri-team-line fs-1 opacity-75"></i>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-warning text-dark">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="small">Menunggu</div>
            <h3 class="fw-bold"><?= $ringkasan['menunggu'] ?></h3>
          </div>
          <i class="ri ri-time-line fs-1 opacity-75"></i>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-primary text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="small">Dipanggil</div>
            <h3 class="fw-bold"><?= $ringkasan['dipanggil'] ?></h3>
          </div>
          <i class="ri ri-notification-3-line fs-1 opacity-75"></i>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-success text-white">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="small">Selesai</div>
            <h3 class="fw-bold"><?= $ringkasan['selesai'] ?></h3>
          </div>
          <i class="ri ri-checkbox-circle-line fs-1 opacity-75"></i>
        </div>
      </div>
    </div>

  </div>

  <!-- Aksi Cepat -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
      <strong><i class="ri ri-flashlight-line me-1"></i> Aksi Cepat</strong>
    </div>
    <div class="card-body">
      <div class="row text-center">

        <div class="col-md-3 mb-3">
          <a href="<?= base_url('admin_layanan/antrian_hari_ini') ?>" class="btn btn-outline-primary w-100 p-3 shadow-sm">
            <i class="ri ri-time-line fs-2 mb-2"></i><br>Antrian Hari Ini
          </a>
        </div>

        <div class="col-md-3 mb-3">
          <a href="<?= base_url('admin_layanan/riwayat_antrian') ?>" class="btn btn-outline-secondary w-100 p-3 shadow-sm">
            <i class="ri ri-folder-history-line fs-2 mb-2"></i><br>Riwayat Pelayanan
          </a>
        </div>

        <div class="col-md-3 mb-3">
          <a href="<?= base_url('admin_layanan/rekap') ?>" class="btn btn-outline-success w-100 p-3 shadow-sm">
            <i class="ri ri-bar-chart-grouped-line fs-2 mb-2"></i><br>Rekap Bulanan
          </a>
        </div>

        <div class="col-md-3 mb-3">
          <a href="<?= base_url('admin_layanan/antrian_hari_ini') ?>" class="btn btn-outline-danger w-100 p-3 shadow-sm">
            <i class="ri ri-megaphone-line fs-2 mb-2"></i><br>Panggil Antrian
          </a>
        </div>

      </div>
    </div>
  </div>

  <!-- Antrian Terbaru -->
  <div class="card shadow-sm">
    <div class="card-header bg-maroon text-white">
      <strong><i class="ri ri-list-check me-1"></i> Antrian Terbaru (Hari Ini)</strong>
    </div>
    <div class="card-body">

      <?php if (empty($antrian_terbaru)): ?>
        <p class="text-muted">Belum ada antrian masuk hari ini.</p>
      <?php else: ?>

        <div class="table-responsive">
          <table class="table table-sm table-bordered">
            <thead class="bg-light">
              <tr>
                <th>No</th>
                <th>Layanan</th>
                <th>Nomor</th>
                <th>Status</th>
                <th>Waktu</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1;
              foreach ($antrian_terbaru as $a): ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td><?= $a->nama_layanan ?></td>
                  <td><strong><?= $a->nomor_antrian ?></strong></td>
                  <td><?= ucfirst($a->status) ?></td>
                  <td><?= date('H:i', strtotime($a->created_at)) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      <?php endif; ?>

    </div>
  </div>

</div>

<!-- Notifikasi -->
<script>
  let antrianTerakhir = <?= $ringkasan['total'] ?? 0 ?>;
</script>

<audio id="notifikasiSound" src="<?= base_url('assets/sounds/antrian.mp3') ?>" preload="auto"></audio>

<script>
  function cekNotifikasiAntrianBaru() {
    $.getJSON('<?= base_url('admin_layanan/cek_total_antrian_json') ?>', function(res) {

      const totalBaru = parseInt(res.total);

      if (totalBaru > antrianTerakhir) {
        antrianTerakhir = totalBaru;

        Swal.fire({
          title: '📢 Antrian Baru!',
          text: 'Ada antrian baru masuk.',
          icon: 'info',
          toast: true,
          timer: 4000,
          position: 'top-end',
          showConfirmButton: false
        });

        document.getElementById('notifikasiSound').play();
      }
    });
  }

  setInterval(() => {
    cekNotifikasiAntrianBaru();
  }, 10000);
</script>