<?php
// Hitung tanggal default berdasarkan jam sekarang
date_default_timezone_set('Asia/Jakarta');
$jam = date('H:i');
$today = date('Y-m-d');
$besok = date('Y-m-d', strtotime('+1 day'));
$default_tanggal = ($jam < '15:30') ? $today : $besok;
?>
<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
<?php endif; ?>

<div class="container-fluid px-4 mt-4">
  <h4 class="text-maroon"><i class="fas fa-plus-square me-2"></i><?= $title ?></h4>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="<?= base_url('masyarakat/simpan_antrian') ?>" method="post" id="form-antrian">
        <div class="mb-3">
          <label for="instansi" class="form-label">Instansi</label>
          <select name="instansi_id" id="instansi" class="form-select" required>
            <option value="">-- Pilih Instansi --</option>
            <?php foreach ($instansi as $i): ?>
              <option value="<?= $i->id ?>"><?= $i->nama_instansi ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="layanan_id" class="form-label">Jenis Layanan</label>
          <select name="layanan_id" id="layanan_id" class="form-select" required>
            <option value="">-- Pilih Layanan --</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="tanggal" class="form-label">Tanggal Kunjungan</label>
          <input type="date" name="tanggal" class="form-control" required min="<?= $today ?>" value="<?= $default_tanggal ?>">
        </div>

        <button class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Daftar Antrian</button>
      </form>

    </div>
  </div>
</div>

<script>
  document.getElementById('instansi').addEventListener('change', function() {
    const instansi_id = this.value;
    const layananSelect = document.getElementById('layanan_id');
    layananSelect.innerHTML = '<option>Loading...</option>';

    fetch(`<?= base_url('masyarakat/get_layanan_by_instansi/') ?>${instansi_id}`)
      .then(res => res.json())
      .then(data => {
        layananSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';
        data.forEach(item => {
          layananSelect.innerHTML += `<option value="${item.id}">${item.nama_layanan}</option>`;
        });
      });
  });
</script>