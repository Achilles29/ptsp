<style>
  .badge.small {
    font-size: 0.75rem;
    padding: 0.35em 0.6em;
  }
  .antrian-fixed {
    table-layout: fixed;
    width: 100%;
  }
  .antrian-fixed th,
  .antrian-fixed td {
    white-space: normal !important;
    word-break: break-word;
    font-size: 0.92rem;
  }
  .antrian-fixed .col-no { width: 50px; }
  .antrian-fixed .col-nomor { width: 110px; }
  .antrian-fixed .col-nama { width: 220px; }
  .antrian-fixed .col-hp { width: 110px; }
  .antrian-fixed .col-layanan { width: 300px; }
  .antrian-fixed .col-hadir { width: 85px; }
  .antrian-fixed .col-status { width: 100px; }
  .antrian-fixed .col-aksi { width: 180px; }
  .antrian-fixed td:nth-child(6),
  .antrian-fixed td:nth-child(7),
  .antrian-fixed th:nth-child(6),
  .antrian-fixed th:nth-child(7) {
    text-align: center !important;
    padding-left: 0.4rem;
    padding-right: 0.4rem;
  }
  .antrian-fixed td:nth-child(6) .badge,
  .antrian-fixed td:nth-child(7) .badge {
    min-width: 70px;
    display: inline-block;
  }
  .antrian-fixed .aksi-wrap .btn {
    display: block;
    width: 100%;
    margin-bottom: 0.35rem;
  }
  .antrian-fixed .aksi-wrap .btn:last-child { margin-bottom: 0; }
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
      <ul class="nav nav-pills mb-3">
        <li class="nav-item">
          <a class="nav-link <?= $tab === 'aktif' ? 'active' : '' ?>" href="<?= base_url('admin_layanan/antrian_hari_ini?tab=aktif&search=' . urlencode($search) . '&limit=' . (int) $limit . '&hadir=' . urlencode($hadir) . '&status=' . urlencode($status)) ?>">Aktif</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $tab === 'selesai' ? 'active' : '' ?>" href="<?= base_url('admin_layanan/antrian_hari_ini?tab=selesai&search=' . urlencode($search) . '&limit=' . (int) $limit . '&hadir=' . urlencode($hadir) . '&status=' . urlencode($status)) ?>">Selesai</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $tab === 'semua' ? 'active' : '' ?>" href="<?= base_url('admin_layanan/antrian_hari_ini?tab=semua&search=' . urlencode($search) . '&limit=' . (int) $limit . '&hadir=' . urlencode($hadir) . '&status=' . urlencode($status)) ?>">Semua</a>
        </li>
      </ul>
      <form method="get" class="row g-2 align-items-end">
        <input type="hidden" name="tab" value="<?= html_escape($tab) ?>">
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
          <label class="form-label">Hadir</label>
          <select name="hadir" class="form-select">
            <option value="semua" <?= $hadir === 'semua' ? 'selected' : '' ?>>Semua</option>
            <option value="hadir" <?= $hadir === 'hadir' ? 'selected' : '' ?>>Hadir</option>
            <option value="belum" <?= $hadir === 'belum' ? 'selected' : '' ?>>Belum Hadir</option>
          </select>
        </div>
        <div class="col-md-3 col-xl-2">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="" <?= $status === '' ? 'selected' : '' ?>>Semua</option>
            <option value="terdaftar" <?= $status === 'terdaftar' ? 'selected' : '' ?>>Terdaftar</option>
            <option value="dipanggil" <?= $status === 'dipanggil' ? 'selected' : '' ?>>Dipanggil</option>
            <option value="selesai" <?= $status === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            <option value="batal" <?= $status === 'batal' ? 'selected' : '' ?>>Batal</option>
          </select>
        </div>
        <div class="col-md-3 col-xl-2">
          <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
        <div class="col-md-3 col-xl-2">
          <a href="<?= base_url('admin_layanan/antrian_hari_ini?tab=' . urlencode($tab) . '&limit=' . (int) $limit . '&hadir=semua&status=') ?>" class="btn btn-outline-secondary w-100">
            <i class="fas fa-eraser me-1"></i> Clear Filter
          </a>
        </div>
      </form>
    </section>

    <section class="card portal-section-card portal-table-card">
      <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
          <h5 class="portal-section-title">Daftar Antrian Aktif Hari Ini</h5>
          <div class="portal-card-note">Gunakan tab dan filter untuk memfokuskan data antrian harian.</div>
        </div>
        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle"><?= count($antrian) ?> baris</span>
      </div>
      <div class="card-body">
      <div class="table-responsive">
        <table class="table table-style align-middle antrian-fixed">
          <thead class="text-center">
            <tr>
              <th class="col-no">No</th>
              <th class="col-nomor">Nomor Antrian</th>
              <th class="col-nama">Nama</th>
              <th class="col-hp">No HP</th>
              <th class="col-layanan">Layanan</th>
              <th class="col-hadir">Hadir</th>
              <th class="col-status">Status</th>
              <th class="col-aksi">Aksi</th>
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
  <div class="aksi-wrap">

    <?php if ($a->status === 'dipanggil' && (int) $a->hadir === 1): ?>

      <!-- 🔊 PANGGIL ULANG (antrian aktif) -->
      <a href="javascript:void(0)" data-endpoint="<?= site_url('admin_layanan/panggil/') . (int) $a->id ?>"
              class="btn btn-sm btn-warning btn-panggil"
              data-action="panggil"
              onclick="event.preventDefault();event.stopPropagation();if(window.runQueueAction){window.runQueueAction(this);}return false;"
              data-id="<?= (int) $a->id ?>"
             >
        <i class="bi bi-megaphone"></i>
        Panggil Ulang
      </a>

    <?php elseif ($a->status === 'terdaftar'): ?>

      <!-- 🔊 PANGGIL (tidak ada antrian aktif) -->
      <a href="javascript:void(0)" data-endpoint="<?= site_url('admin_layanan/panggil/') . (int) $a->id ?>"
              class="btn btn-sm btn-warning btn-panggil"
              data-action="panggil"
              onclick="event.preventDefault();event.stopPropagation();if(window.runQueueAction){window.runQueueAction(this);}return false;"
              data-id="<?= (int) $a->id ?>"
             >
        <i class="bi bi-megaphone"></i>
        <?= (int) $a->hadir === 1 ? 'Panggil' : 'Panggil (Auto Check-In)' ?>
      </a>

    <?php endif; ?>

    <?php if ($a->status === 'terdaftar'): ?>

      <!-- ❌ BATAL -->
      <a href="javascript:void(0)" data-endpoint="<?= site_url('admin_layanan/batal/') . (int) $a->id ?>"
              class="btn btn-sm btn-outline-danger btn-batal"
              data-action="batal"
              onclick="event.preventDefault();event.stopPropagation();if(window.runQueueAction){window.runQueueAction(this);}return false;"
              data-id="<?= (int) $a->id ?>"
             >
        <i class="bi bi-x-circle"></i> Batal
      </a>

    <?php elseif ($a->status === 'dipanggil'): ?>

      <!-- ✅ SELESAI -->
      <a href="javascript:void(0)" data-endpoint="<?= site_url('admin_layanan/selesai/') . (int) $a->id ?>"
              class="btn btn-sm btn-success btn-selesai"
              data-action="selesai"
              onclick="event.preventDefault();event.stopPropagation();if(window.runQueueAction){window.runQueueAction(this);}return false;"
              data-id="<?= (int) $a->id ?>"
             >
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
const refreshUrl = '<?= base_url('admin_layanan/refresh_antrian?tab=' . urlencode($tab) . '&hadir=' . urlencode($hadir) . '&status=' . urlencode($status)) ?>';
function refreshAntrian() {
  fetch(refreshUrl, { credentials: 'same-origin' })
    .then(res => res.text())
    .then(html => {
      const tbody = document.getElementById('antrian-body');
      if (!tbody) return;
      tbody.innerHTML = html;
      const first = tbody.querySelector('tr');
      if (first) {
        first.classList.add('table-success');
        setTimeout(() => {
          tbody.querySelectorAll('tr').forEach(tr => tr.classList.remove('table-success'));
        }, 1500);
      }
    })
    .catch(() => {});
}
</script>

<script>
window.addEventListener('load', function () {
  setInterval(refreshAntrian, 10000);
});

function queueActionConfig(action) {
  if (action === 'panggil') {
    return { title: 'Panggil Antrian', text: 'Nomor akan dipanggil ke loket sekarang.', icon: 'question' };
  }
  if (action === 'batal') {
    return { title: 'Batalkan Antrian', text: 'Antrian ini akan dibatalkan.', icon: 'warning' };
  }
  return { title: 'Selesaikan Layanan', text: 'Status antrian akan diubah menjadi selesai.', icon: 'question' };
}

function runQueueAction(el) {
  const action = el.getAttribute('data-action') || '';
  const endpoint = el.getAttribute('data-endpoint') || '';

  if (!action || !endpoint) return;

  const cfg = queueActionConfig(action);
  const ensureSwal = function(cb) {
    if (typeof Swal !== 'undefined' && Swal.fire) return cb(true);
    const old = document.getElementById('swal-dynamic-loader');
    if (old) {
      old.addEventListener('load', () => cb(typeof Swal !== 'undefined' && Swal.fire));
      return;
    }
    const s = document.createElement('script');
    s.id = 'swal-dynamic-loader';
    s.src = '<?= base_url('assets/libs/sweetalert2/sweetalert2.all.min.js') ?>';
    s.onload = () => cb(typeof Swal !== 'undefined' && Swal.fire);
    s.onerror = () => cb(false);
    document.head.appendChild(s);
  };

  const askConfirm = function(onYes) {
    ensureSwal(function(ready){
      if (!ready) {
        const ok = confirm(cfg.title + '\n\n' + cfg.text);
        if (ok) onYes();
        return;
      }
      Swal.fire({
        title: cfg.title,
        html:
          '<div class="portal-swal-hero">' +
          '<div class="portal-swal-glow"></div>' +
          '<div class="portal-swal-sub">' + cfg.text + '</div>' +
          '</div>',
        icon: cfg.icon,
        customClass: {
          popup: 'portal-swal',
          confirmButton: 'portal-swal-confirm',
          cancelButton: 'portal-swal-cancel'
        },
        showCancelButton: true,
        confirmButtonText: 'Ya, Proses',
        cancelButtonText: 'Batal',
        reverseButtons: true
      }).then(function(result) {
        if (result.isConfirmed) onYes();
      });
    });
  };

  const showResult = function(type, title, text) {
    if (typeof Swal !== 'undefined' && Swal.fire) {
      Swal.fire({
        icon: type,
        title: title,
        text: text,
        timer: type === 'success' ? 1300 : undefined,
        showConfirmButton: type !== 'success',
        customClass: { popup: 'portal-swal' }
      });
    }
  };

  askConfirm(function() {
    fetch(endpoint, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(res => res.json())
      .then(function (res) {
        if (res && res.success) {
          showResult('success', 'Berhasil', res.message || 'Aksi berhasil diproses.');
          refreshAntrian();
          return;
        }
        showResult('error', 'Gagal', (res && res.message) ? res.message : 'Aksi gagal diproses.');
      })
      .catch(function () {
        const msg = 'Terjadi gangguan saat memproses aksi.';
        showResult('error', 'Gagal', msg);
      });
  });
}
window.runQueueAction = runQueueAction;

document.addEventListener('click', function (e) {
  const targetEl = e.target && e.target.nodeType === 1 ? e.target : (e.target ? e.target.parentElement : null);
  const trigger = targetEl ? targetEl.closest('a.btn-panggil, a.btn-batal, a.btn-selesai') : null;
  if (!trigger) return;
  e.preventDefault();
  e.stopPropagation();
  runQueueAction(trigger);
}, true);
</script>

<script>
function syncHasilLayananRequirements() {
  const jenis = $('input[name="jenis_hasil"]:checked').val();
  const isKonsultasi = jenis === 'konsultasi';

  $('#ringkasan_konsultasi').prop('required', isKonsultasi);
  $('#jenis_produk_hukum, #nomor_produk, #tanggal_produk').prop('required', !isKonsultasi);
  $('#catatan_petugas').prop('required', true);
}
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
    const tab = (new URLSearchParams(window.location.search)).get('tab') || 'aktif';
    const hadir = document.querySelector('select[name="hadir"]').value;
    const status = document.querySelector('select[name="status"]').value;

    // Minimal 2 huruf untuk trigger search
    if (keyword.length < 2 && keyword !== '') return;

    // Panggil endpoint AJAX
    fetch(`<?= base_url('admin_layanan/antrian_hari_ini_ajax') ?>?tab=${encodeURIComponent(tab)}&search=${encodeURIComponent(keyword)}&limit=${limit}&hadir=${encodeURIComponent(hadir)}&status=${encodeURIComponent(status)}`)
      .then(res => res.text())
      .then(html => {
        tbody.innerHTML = html;
        if (pagination) pagination.style.display = 'none';
      })
      .catch(err => console.error('Error:', err));

    // Jika input dikosongkan, reload halaman normal
    if (keyword === '') {
      location.href = location.pathname + '?tab=' + encodeURIComponent(tab) + '&limit=' + limit + '&hadir=' + encodeURIComponent(hadir) + '&status=' + encodeURIComponent(status);
    }
  });
  }
</script>
