<div class="container-fluid px-4 mt-4">
  <h4 class="text-maroon"><i class="fas fa-building me-2"></i>Dashboard Admin Pelayanan</h4>
  <hr>

  <!-- Banner Selamat Datang -->
  <div class="alert alert-info shadow-sm d-flex justify-content-between align-items-center">
    <div>
      🏢 Anda bertugas sebagai admin layanan <strong><?= $this->session->userdata('instansi_nama') ?? '...' ?></strong>
      <div class="text-muted small">Pantau antrian masuk, kelola layanan, dan bantu masyarakat.</div>
    </div>
    <a href="<?= base_url('admin_layanan/antrian_hari_ini') ?>" class="btn btn-sm btn-primary">
      <i class="fas fa-list-ol me-1"></i> Lihat Antrian Hari Ini
    </a>
  </div>

  <div id="ringkasan-box">
    <?php $this->load->view('admin_layanan/_partial_ringkasan', ['ringkasan' => $ringkasan]); ?>
  </div>


  <!-- Tombol Aksi Cepat -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="mb-3"><i class="fas fa-tools text-dark me-2"></i> Aksi Cepat</h5>
      <div class="row text-center">
        <div class="col-md-3 mb-3">
          <a href="<?= base_url('admin_layanan/antrian_hari_ini') ?>" class="btn btn-outline-primary w-100">
            <i class="fas fa-clock fa-lg mb-1"></i><br>
            Antrian Hari Ini
          </a>
        </div>
        <div class="col-md-3 mb-3">
          <a href="<?= base_url('admin_layanan/riwayat_pelayanan') ?>" class="btn btn-outline-secondary w-100">
            <i class="fas fa-folder-open fa-lg mb-1"></i><br>
            Riwayat Layanan
          </a>
        </div>
        <div class="col-md-3 mb-3">
          <a href="<?= base_url('admin_layanan/rekap') ?>" class="btn btn-outline-success w-100">
            <i class="fas fa-chart-line fa-lg mb-1"></i><br>
            Rekap Bulanan
          </a>
        </div>
        <div class="col-md-3 mb-3">
          <a href="<?= base_url('admin_layanan/panggilan') ?>" class="btn btn-outline-danger w-100">
            <i class="fas fa-bullhorn fa-lg mb-1"></i><br>
            Panggil Antrian
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function refreshAntrian() {
    $('#antrian-body').load('<?= base_url('admin_layanan/refresh_antrian') ?>');
  }

  function refreshRingkasan() {
    $('#ringkasan-box').load('<?= base_url('admin_layanan/refresh_ringkasan') ?>');
  }

  // Refresh setiap 10 detik
  setInterval(() => {
    refreshAntrian();
    refreshRingkasan();
  }, 10000);
</script>

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

        // Tampilkan notifikasi (pakai SweetAlert)
        Swal.fire({
          title: '📢 Antrian Baru!',
          text: 'Ada antrian baru masuk.',
          icon: 'info',
          toast: true,
          timer: 4000,
          position: 'top-end',
          showConfirmButton: false
        });

        // Mainkan suara
        document.getElementById('notifikasiSound').play();
      }
    });
  }

  // Jalankan tiap 10 detik
  setInterval(() => {
    refreshAntrian();
    refreshRingkasan();
    cekNotifikasiAntrianBaru();
  }, 10000);
</script>