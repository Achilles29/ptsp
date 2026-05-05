<?php
date_default_timezone_set('Asia/Jakarta');
$jam = date('H:i');
$jam_detik = date('H:i:s');
$today = date('Y-m-d');
$besok = date('Y-m-d', strtotime('+1 day'));
$default_tanggal = $today;
?>

<div class="nm-page-intro">
  <div class="page-pretitle">Layanan MPP</div>
  <h2 class="page-title">
    <i class="ti ti-circle-plus me-2"></i><?= $title ?>
  </h2>
</div>

<div class="row g-3 g-xl-4">

  <!-- Flash Message -->
  <?php if ($this->session->flashdata('error')): ?>
    <div class="col-12">
      <div class="alert alert-danger">
        <i class="ti ti-alert-circle me-2"></i>
        <?= $this->session->flashdata('error') ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="col-12">
      <div class="alert alert-success">
        <i class="ti ti-check me-2"></i>
        <?= $this->session->flashdata('success') ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="col-12">
    <div class="nm-form-layout">
      <div class="card nm-form-aside">
        <div class="page-pretitle">Panduan Singkat</div>
        <h3 class="mb-2">Daftar lebih cepat tanpa kehilangan konteks</h3>
        <p class="text-muted mb-0">
          Pilih instansi, layanan, lalu tentukan tanggal kunjungan. Sistem akan otomatis menyesuaikan batas pendaftaran online untuk hari yang sama.
        </p>

        <div class="nm-form-aside-list">
          <div class="nm-form-aside-item">
            <i class="ti ti-clock-hour-4"></i>
            <div>
              <strong>Batas waktu online</strong>
              <div class="text-muted">Setelah jam tutup pendaftaran, pilihan tanggal otomatis bergeser ke besok.</div>
            </div>
          </div>
          <div class="nm-form-aside-item">
            <i class="ti ti-qrcode"></i>
            <div>
              <strong>Check-in lebih mudah</strong>
              <div class="text-muted">Setelah berhasil mendaftar, gunakan QR check-in agar nomor Anda siap dipanggil petugas.</div>
            </div>
          </div>
          <div class="nm-form-aside-item">
            <i class="ti ti-device-mobile"></i>
            <div>
              <strong>Nyaman di semua perangkat</strong>
              <div class="text-muted">Tampilan akan menyesuaikan desktop, tablet, dan mobile tanpa mengubah alur Anda.</div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <i class="ti ti-clipboard-list me-2"></i>Form Pendaftaran Antrian
          </div>
        </div>

        <div class="card-body">
        <form action="<?= base_url('masyarakat/simpan_antrian') ?>" method="post" id="form-antrian">

          <!-- Instansi -->
          <div class="mb-3">
            <label class="form-label">
              <i class="ti ti-building me-1"></i>Instansi
            </label>
            <select name="instansi_id" id="instansi" class="form-select" required>
              <option value="">-- Pilih Instansi --</option>
              <?php foreach ($instansi as $i): ?>
                <option
                  value="<?= $i->id ?>"
                  data-jam-tutup-pendaftaran="<?= substr($i->jam_tutup_pendaftaran ?? '15:30:00', 0, 5) ?>"
                  data-status-mode="<?= $i->status_layanan_mode ?? 'otomatis' ?>"
                ><?= $i->nama_instansi ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Layanan -->
          <div class="mb-3">
            <label class="form-label">
              <i class="ti ti-list-details me-1"></i>Jenis Layanan
            </label>
            <select name="layanan_id" id="layanan_id" class="form-select" required>
              <option value="">-- Pilih Layanan --</option>
            </select>
          </div>

          <!-- Tanggal -->
          <div class="mb-4">
            <label class="form-label">
              <i class="ti ti-calendar-event me-1"></i>Tanggal Kunjungan
            </label>
            <input type="date"
                   name="tanggal"
                   id="tanggal_kunjungan"
                   class="form-control"
                   required
                   min="<?= $today ?>"
                   value="<?= $default_tanggal ?>">
            <div class="form-hint" id="hint-jadwal">
              Pilih instansi terlebih dahulu untuk melihat batas pendaftaran online.
            </div>
          </div>

          <!-- Submit -->
          <div class="d-grid">
            <button class="btn btn-primary btn-lg" id="btn-submit-antrian">
              <i class="ti ti-send me-1"></i>
              Daftar Antrian
            </button>
          </div>

        </form>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
const instansiSelect = document.getElementById('instansi');
const layananSelect = document.getElementById('layanan_id');
const tanggalInput = document.getElementById('tanggal_kunjungan');
const hintJadwal = document.getElementById('hint-jadwal');
const submitButton = document.getElementById('btn-submit-antrian');
const today = '<?= $today ?>';
const besok = '<?= $besok ?>';
const nowTime = '<?= $jam_detik ?>';

function updateJadwalHint() {
  const selected = instansiSelect.options[instansiSelect.selectedIndex];
  const jamTutup = selected && selected.dataset.jamTutupPendaftaran ? selected.dataset.jamTutupPendaftaran : '15:30';
  const statusMode = selected && selected.dataset.statusMode ? selected.dataset.statusMode : 'otomatis';

  if (!instansiSelect.value) {
    hintJadwal.textContent = 'Pilih instansi terlebih dahulu untuk melihat batas pendaftaran online.';
    submitButton.disabled = false;
    return;
  }

  if (statusMode === 'tutup') {
    hintJadwal.textContent = 'Layanan sedang ditutup oleh admin. Pendaftaran online sementara tidak tersedia.';
    submitButton.disabled = true;
    return;
  }

  submitButton.disabled = false;
  hintJadwal.innerHTML = `Pendaftaran online hari ini ditutup pukul <b>${jamTutup}</b>. Setelah itu otomatis diarahkan ke tanggal besok.`;

  if (nowTime >= `${jamTutup}:00` && (!tanggalInput.value || tanggalInput.value === today)) {
    tanggalInput.value = besok;
  } else if (!tanggalInput.value || tanggalInput.value < today) {
    tanggalInput.value = today;
  }
}

instansiSelect.addEventListener('change', function () {
  const instansi_id = this.value;

  layananSelect.innerHTML = '<option>Loading...</option>';
  updateJadwalHint();

  if (!instansi_id) {
    layananSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';
    return;
  }

  fetch(`<?= base_url('masyarakat/get_layanan_by_instansi/') ?>${instansi_id}`)
    .then(res => res.json())
    .then(data => {
      layananSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';
      data.forEach(item => {
        layananSelect.innerHTML += `<option value="${item.id}">${item.nama_layanan}</option>`;
      });
    })
    .catch(() => {
      layananSelect.innerHTML = '<option value="">Gagal memuat layanan</option>';
    });
});

updateJadwalHint();
</script>
