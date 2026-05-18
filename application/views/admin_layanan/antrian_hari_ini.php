<style>
  .badge.small {
    font-size: 0.75rem;
    padding: 0.35em 0.6em;
  }
</style>

<div class="container-fluid px-4 mt-4">
  <div class="portal-page">
    <section class="portal-page-intro">
      <div>
        <div class="portal-page-eyebrow"><i class="ri ri-list-check-3"></i> Operasional Harian</div>
        <h1 class="portal-page-title"><?= html_escape($title) ?></h1>
        <p class="portal-page-subtitle">
          Panggil antrian yang sudah check-in, batalkan yang perlu dibatalkan, dan selesaikan layanan tanpa kehilangan konteks status kehadiran masyarakat.
        </p>
      </div>
      <div class="portal-inline-metrics">
        <div class="portal-inline-metric">
          <small>Total data</small>
          <strong><?= (int) $total_rows ?></strong>
        </div>
      </div>
    </section>

    <section class="portal-filter-card">
      <form method="get" class="row g-2 align-items-end">
        <div class="col-md-6 col-xl-5">
          <label class="form-label">Pencarian</label>
          <input type="text" name="search" class="form-control" placeholder="Cari nama, nomor, atau layanan" value="<?= $search ?>">
        </div>
        <div class="col-md-3 col-xl-2">
          <label class="form-label">Baris</label>
          <select name="limit" class="form-select" onchange="this.form.submit()">
            <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
            <option value="0" <?= $limit == 0 ? 'selected' : '' ?>>Semua</option>
          </select>
        </div>
        <div class="col-md-3 col-xl-2">
          <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
      </form>
    </section>

    <section class="card portal-section-card portal-table-card">
      <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
          <h5 class="portal-section-title">Daftar Antrian Aktif Hari Ini</h5>
          <div class="portal-card-note">Nomor dengan status belum hadir akan ditandai khusus dan tidak bisa dipanggil.</div>
        </div>
        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle"><?= count($antrian) ?> baris</span>
      </div>
      <div class="card-body">
      <div class="table-responsive">
        <table class="table table-style align-middle table-scroll-x">
          <thead class="text-center">
            <tr>
              <th class="col-nowrap">No</th>
              <th class="col-nowrap">Nomor Antrian</th>
              <th class="col-name-min">Nama</th>
              <th class="col-nowrap">No HP</th>
              <th class="col-name-min">Layanan</th>
              <th class="col-nowrap">Hadir</th>
              <th class="col-nowrap">Status</th>
              <th class="col-nowrap" width="200">Aksi</th>
            </tr>
          </thead>
          <tbody id="antrian-body"><!-- ✅ penting: tambahkan id ini -->
<?php if (empty($antrian)): ?>
<tr>
  <td colspan="8" class="text-center text-muted">Belum ada antrian hari ini</td>
</tr>
<?php else: ?>
<?php
$ada_dipanggil = false;
foreach ($antrian as $_a) {
    if ($_a->status === 'dipanggil') { $ada_dipanggil = true; break; }
}
?>
<?php $no = $offset + 1; foreach ($antrian as $a): ?>
<tr class="text-center">
  <td><?= $no++ ?></td>
  <td class="fw-bold"><?= $a->nomor_antrian ?></td>
  <td class="text-start"><?= $a->nama_lengkap ?? '-' ?></td>
  <td><?= $a->no_hp ?? '-' ?></td>
  <td><?= $a->nama_layanan ?? '-' ?></td>

  <td>
    <?= $a->hadir
      ? '<span class="badge bg-success">Hadir</span>'
      : '<span class="badge bg-secondary">Belum</span>' ?>
  </td>

  <td>
    <?php
switch ($a->status) {
    case 'selesai':
        $cls = 'success';
        break;
    case 'batal':
        $cls = 'danger';
        break;
    case 'dipanggil':
        $cls = 'warning text-dark';
        break;
    default:
        $cls = 'secondary';
}
    ?>
    <span class="badge bg-<?= $cls ?>"><?= ucfirst($a->status) ?></span>
  </td>

<td>
  <div class="d-flex flex-wrap justify-content-center gap-1">

    <?php if ($a->status === 'dipanggil' && (int) $a->hadir === 1): ?>

      <!-- 🔊 PANGGIL ULANG (antrian aktif) -->
      <a href="<?= base_url('admin_layanan/panggil_sync/'.$a->id) ?>"
              class="btn btn-sm btn-warning btn-panggil"
              onclick="return confirm('Panggil ulang antrian ini?')">
        <i class="bi bi-megaphone"></i>
        Panggil Ulang
      </a>

    <?php elseif ($a->status === 'terdaftar'): ?>

      <!-- 🔊 PANGGIL (tidak ada antrian aktif) -->
      <a href="<?= base_url('admin_layanan/panggil_sync/'.$a->id) ?>"
              class="btn btn-sm btn-warning btn-panggil"
              onclick="return confirm('Panggil antrian ini?')">
        <i class="bi bi-megaphone"></i>
        <?= (int) $a->hadir === 1 ? 'Panggil' : 'Panggil (Auto Check-In)' ?>
      </a>

    <?php endif; ?>

    <?php if ($a->status === 'terdaftar'): ?>

      <!-- ❌ BATAL -->
      <a href="<?= base_url('admin_layanan/batal/'.$a->id) ?>"
              class="btn btn-sm btn-outline-danger btn-batal"
              onclick="return confirm('Batalkan antrian ini?')">
        <i class="bi bi-x-circle"></i> Batal
      </a>

    <?php elseif ($a->status === 'dipanggil'): ?>

      <!-- ✅ SELESAI -->
      <a href="<?= base_url('admin_layanan/selesai/'.$a->id) ?>"
              class="btn btn-sm btn-success btn-selesai"
              onclick="return confirm('Selesaikan antrian ini?')">
        <i class="bi bi-check2-circle"></i> Selesai
      </a>

    <?php endif; ?>

  </div>
</td>

</tr>
<?php endforeach; ?>
<?php endif; ?>

          </tbody>
        </table>
      </div>

      <!-- 🔢 PAGINATION -->
      <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-muted small">
          Menampilkan <?= count($antrian) ?> dari <?= $total_rows ?> data
        </div>
        <div><?= $pagination ?></div>
      </div>
      </div>
    </section>
  </div>
</div>

<!-- ================= MODAL HASIL LAYANAN ================= -->
<div class="modal fade" id="modalHasilLayanan" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="bi bi-clipboard-check me-2"></i>Hasil Layanan
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- ✅ SATU FORM SAJA -->
      <form id="formHasilLayanan">

        <!-- ✅ CSRF -->
        <input type="hidden"
               name="<?= $this->security->get_csrf_token_name(); ?>"
               value="<?= $this->security->get_csrf_hash(); ?>">

        <!-- ✅ ANTRIAN ID -->
        <input type="hidden" name="antrian_id" id="hasil_antrian_id">

        <div class="modal-body">

          <!-- PILIH JENIS -->
          <div class="mb-3">
            <label class="fw-bold">Jenis Hasil</label>
            <div class="d-flex gap-4 mt-1">
              <div class="form-check">
                <input class="form-check-input" type="radio"
                       name="jenis_hasil" value="konsultasi" checked>
                <label class="form-check-label">Konsultasi</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio"
                       name="jenis_hasil" value="produk_hukum">
                <label class="form-check-label">Produk Hukum</label>
              </div>
            </div>
          </div>

          <!-- KONSULTASI -->
          <div id="formKonsultasi">
            <div class="mb-3">
              <label>Ringkasan Konsultasi</label>
              <textarea name="ringkasan_konsultasi"
                        id="ringkasan_konsultasi"
                        class="form-control"
                        rows="4"></textarea>
            </div>
          </div>

          <!-- PRODUK HUKUM -->
          <div id="formProdukHukum" class="d-none">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Jenis Produk</label>
                <input type="text" name="jenis_produk_hukum" id="jenis_produk_hukum" class="form-control">
              </div>
              <div class="col-md-6 mb-3">
                <label>Nomor Produk</label>
                <input type="text" name="nomor_produk" id="nomor_produk" class="form-control">
              </div>
              <div class="col-md-6 mb-3">
                <label>Tanggal Produk</label>
                <input type="date" name="tanggal_produk" id="tanggal_produk" class="form-control">
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label>Catatan Petugas</label>
            <textarea name="catatan_petugas" id="catatan_petugas" class="form-control" rows="2"></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>Simpan & Selesaikan
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<script>
function refreshAntrian() {
  $('#antrian-body').load('<?= base_url('admin_layanan/refresh_antrian') ?>', function() {
    $('#antrian-body tr:first').addClass('table-success');
    setTimeout(() => $('#antrian-body tr').removeClass('table-success'), 1500);
  });
}
</script>

<script>
$(function () {

  setInterval(refreshAntrian, 10000);

  $(document).on('click', '.btn-panggil, .btn-batal', function (e) {
    e.preventDefault();

    const btn  = $(this);
    const id   = btn.data('id');
    const url  = btn.data('url');
    const fallbackUrl = btn.data('fallback-url');
    const type = btn.hasClass('btn-panggil') ? 'panggil' : 'batal';

    if (typeof Swal === 'undefined' || typeof Swal.fire !== 'function') {
      if (type === 'panggil') {
        if (fallbackUrl) window.location.href = fallbackUrl;
        return;
      }
      if (url) window.location.href = url;
      return;
    }

    Swal.fire({
      title: type === 'panggil'
        ? 'Panggil Antrian Ini?'
        : 'Batalkan Antrian Ini?',
      icon: type === 'batal' ? 'warning' : 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, Lanjutkan',
      cancelButtonText: 'Batal'
    }).then(result => {
      if (!result.isConfirmed) return;

      if (type === 'panggil') {
        $.ajax({
          url: '<?= site_url('admin_layanan/panggil/') ?>' + id,
          type: 'POST',
          dataType: 'json',
          success: function (res) {
            if (res.success) {
              Swal.fire({
                icon: 'success',
                title: 'Dipanggil',
                text: res.nomor_antrian + ' menuju loket ' + res.loket,
                timer: 1500,
                showConfirmButton: false
              });
              refreshAntrian();
            } else {
              Swal.fire('Gagal', res.message || 'Terjadi kesalahan', 'error');
            }
          },
          error: function (xhr) {
            const msg = xhr.responseJSON && xhr.responseJSON.message
              ? xhr.responseJSON.message
              : 'Terjadi kesalahan saat memanggil antrian';
            Swal.fire({
              title: 'Gagal',
              text: msg + '\nDialihkan ke mode pemanggilan langsung.',
              icon: 'error'
            }).then(() => {
              if (fallbackUrl) window.location.href = fallbackUrl;
            });
          }
        });
      } else {
        window.location.href = url;
      }
    });
  });

});
</script>

<script>
function syncHasilLayananRequirements() {
  const jenis = $('input[name="jenis_hasil"]:checked').val();
  const isKonsultasi = jenis === 'konsultasi';

  $('#ringkasan_konsultasi').prop('required', isKonsultasi);
  $('#jenis_produk_hukum, #nomor_produk, #tanggal_produk').prop('required', !isKonsultasi);
  $('#catatan_petugas').prop('required', true);
}

$(document).on('click', '.btn-selesai', function () {

  const id = $(this).data('id');

  const form = $('#formHasilLayanan')[0];
  form.reset();

  $('#hasil_antrian_id').val(id);

  $('input[name="jenis_hasil"][value="konsultasi"]').prop('checked', true);
  $('#formKonsultasi').removeClass('d-none');
  $('#formProdukHukum').addClass('d-none');
  syncHasilLayananRequirements();

  $('#modalHasilLayanan').modal('show');
});
</script>




<script>
$(document).on('change', 'input[name="jenis_hasil"]', function () {
  if (this.value === 'konsultasi') {
    $('#formKonsultasi').removeClass('d-none');
    $('#formProdukHukum').addClass('d-none');
  } else {
    $('#formKonsultasi').addClass('d-none');
    $('#formProdukHukum').removeClass('d-none');
  }

  syncHasilLayananRequirements();
});
</script>


<script>
$(document).on('submit', '#formHasilLayanan', function (e) {
  e.preventDefault();

  const jenis = $('input[name="jenis_hasil"]:checked').val();
  const ringkasan = $.trim($('#ringkasan_konsultasi').val());
  const jenisProduk = $.trim($('#jenis_produk_hukum').val());
  const nomorProduk = $.trim($('#nomor_produk').val());
  const tanggalProduk = $.trim($('#tanggal_produk').val());
  const catatanPetugas = $.trim($('#catatan_petugas').val());

  if (jenis === 'konsultasi' && !ringkasan) {
    Swal.fire('Data belum lengkap', 'Ringkasan konsultasi wajib diisi sebelum layanan diselesaikan.', 'warning');
    return;
  }

  if (jenis === 'produk_hukum' && (!jenisProduk || !nomorProduk || !tanggalProduk)) {
    Swal.fire('Data belum lengkap', 'Jenis produk, nomor produk, dan tanggal produk wajib diisi.', 'warning');
    return;
  }

  if (!catatanPetugas) {
    Swal.fire('Data belum lengkap', 'Catatan petugas wajib diisi sebelum layanan diselesaikan.', 'warning');
    return;
  }

  const data = $(this).serialize();

  console.log('=== FORM SUBMIT ===');
  console.log(data);

  $.ajax({
    url: '<?= site_url('admin_layanan/simpan_hasil_layanan') ?>',
    type: 'POST',
    data: data,
    dataType: 'json',
    success: function (res) {
      console.log('RESPONSE:', res);

      if (!res.success) {
        Swal.fire('Gagal', res.message || 'Data tidak lengkap', 'error');
        return;
      }

      $('#modalHasilLayanan').modal('hide');
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        timer: 1200,
        showConfirmButton: false
      });

      $('#formHasilLayanan')[0].reset();
      refreshAntrian();
    },
    error: function (xhr) {
      console.error('AJAX ERROR:', xhr.responseText);
    }
  });
});
</script>




<script>
  const searchInput = document.querySelector('input[name="search"]');
  if (searchInput) {
  searchInput.addEventListener('keyup', function() {
    const keyword = this.value.trim();
    const limit = document.querySelector('select[name="limit"]').value;
    const tbody = document.querySelector('#antrian-body');
    const pagination = document.querySelector('.pagination');

    // Minimal 2 huruf untuk trigger search
    if (keyword.length < 2 && keyword !== '') return;

    // Panggil endpoint AJAX
    fetch(`<?= base_url('admin_layanan/antrian_hari_ini_ajax') ?>?search=${encodeURIComponent(keyword)}&limit=${limit}`)
      .then(res => res.text())
      .then(html => {
        tbody.innerHTML = html;
        if (pagination) pagination.style.display = 'none';
      })
      .catch(err => console.error('Error:', err));

    // Jika input dikosongkan, reload halaman normal
    if (keyword === '') {
      location.href = location.pathname + '?limit=' + limit;
    }
  });
  }
</script>
