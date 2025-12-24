<style>
  .badge.small {
    font-size: 0.75rem;
    padding: 0.35em 0.6em;
  }
</style>

<div class="container-fluid px-4 mt-4">
  <h4 class="text-maroon"><i class="fas fa-list-ol me-2"></i><?= $title ?></h4>
  <hr>

  <!-- 🔎 FILTER -->
  <form method="get" class="row mb-3">
    <div class="col-md-4">
      <input type="text" name="search" class="form-control" placeholder="Cari nama / nomor / layanan" value="<?= $search ?>">
    </div>
    <div class="col-md-2">
      <select name="limit" class="form-select" onchange="this.form.submit()">
        <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
        <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
        <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-maroon"><i class="fas fa-search me-1"></i> Cari</button>
    </div>
  </form>

  <!-- 🧾 TABEL ANTRIAN -->
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-style align-middle">
          <thead class="bg-maroon text-white text-center">
            <tr>
              <th>No</th>
              <th>Nomor Antrian</th>
              <th>Nama</th>
              <th>No HP</th>
              <th>Layanan</th>
              <th>Hadir</th>
              <th>Status</th>
              <th width="200">Aksi</th>
            </tr>
          </thead>
          <tbody id="antrian-body"><!-- ✅ penting: tambahkan id ini -->
            <?php if (empty($antrian)): ?>
              <tr>
                <td colspan="8" class="text-center text-muted">Belum ada antrian hari ini</td>
              </tr>
            <?php else: ?>
              <?php $no = $offset + 1;
              foreach ($antrian as $a): ?>
                <tr class="text-center">
                  <td><?= $no++ ?></td>
                  <td class="fw-bold"><?= $a->nomor_antrian ?></td>
                  <td class="text-start"><?= $a->nama_lengkap ?? '-' ?></td>
                  <td><?= $a->no_hp ?? '-' ?></td>
                  <td><?= $a->nama_layanan ?? '-' ?></td>
                  <td>
                    <?php if ($a->hadir): ?>
                      <span class="badge bg-success"><i class="bi bi-check-circle"></i> Hadir</span>
                    <?php else: ?>
                      <span class="badge bg-secondary"><i class="bi bi-clock"></i> Belum</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php
                    $cls = $a->status == 'selesai' ? 'success' : ($a->status == 'batal' ? 'danger' : ($a->status == 'dipanggil' ? 'warning text-dark' : 'secondary'));
                    ?>
                    <span class="badge bg-<?= $cls ?>"><?= ucfirst($a->status) ?></span>
                  </td>
                  <td>
                    <div class="d-flex flex-wrap justify-content-center gap-1">
                      <?php if ($a->status == 'terdaftar'): ?>
                        <button class="btn btn-sm btn-warning text-dark btn-panggil" data-id="<?= $a->id ?>">
                          <i class="bi bi-megaphone"></i> Panggil
                        </button>
                        <a href="#" class="btn btn-sm btn-outline-danger btn-batal" data-url="<?= base_url('admin_layanan/batal/' . $a->id) ?>">
                          <i class="bi bi-x-circle"></i> Batal
                        </a>
                      <?php elseif ($a->status == 'dipanggil'): ?>
                        <a href="#" class="btn btn-sm btn-success btn-selesai" data-url="<?= base_url('admin_layanan/selesai/' . $a->id) ?>">
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
      <div class="mt-3 d-flex justify-content-between align-items-center">
        <div class="text-muted small">
          Menampilkan <?= count($antrian) ?> dari <?= $total_rows ?> data
        </div>
        <div><?= $pagination ?></div>
      </div>
    </div>
  </div>
</div>

<script>
  // 🔁 Auto-refresh tabel
  $(function() {
    function refreshAntrian() {
      $('#antrian-body').load('<?= base_url('admin_layanan/refresh_antrian') ?>', function() {
        $('#antrian-body tr:first').addClass('table-success');
        setTimeout(() => $('#antrian-body tr').removeClass('table-success'), 1500);
      });
    }
    setInterval(refreshAntrian, 10000);

    // 🔔 SweetAlert konfirmasi panggil / selesai / batal
    $(document).on('click', '.btn-panggil, .btn-selesai, .btn-batal', function(e) {
      e.preventDefault();
      const btn = $(this);
      const id = btn.data('id');
      const url = btn.data('url');
      const type = btn.hasClass('btn-panggil') ? 'panggil' :
        btn.hasClass('btn-selesai') ? 'selesai' : 'batal';

      const title = type === 'panggil' ? 'Panggil Antrian Ini?' :
        type === 'selesai' ? 'Tandai Selesai?' :
        'Batalkan Antrian Ini?';
      const confirmText = type === 'panggil' ? 'Ya, Panggil' : 'Ya, Lanjutkan';

      Swal.fire({
        title: title,
        icon: type === 'batal' ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#aaa'
      }).then((result) => {
        if (result.isConfirmed) {
          if (type === 'panggil') {
            $.ajax({
              url: '<?= site_url('admin_layanan/panggil/') ?>' + id,
              type: 'POST',
              dataType: 'json',
              success: function(res) {
                if (res.success) {
                  Swal.fire('Berhasil', res.message, 'success');
                  refreshAntrian();
                  playAntrianAudio(res.nomor_antrian, res.loket);
                } else Swal.fire('Gagal', 'Terjadi kesalahan.', 'error');
              },
              error: () => Swal.fire('Gagal', 'Koneksi server gagal.', 'error')
            });
          } else {
            window.location.href = url;
          }
        }
      });
    });
  });

  // 🔊 Fungsi suara antrian
  function playQueueAudio(files) {
    if (!files.length) return;
    const a = new Audio(files.shift());
    a.play();
    a.onended = () => playQueueAudio(files);
  }

  function playAntrianAudio(nomor, loket) {
    const base = "<?= base_url('assets/sounds/voice/') ?>";
    const f = [];
    f.push(base + "1_nomor_antrian.mp3");
    const huruf = nomor.substring(0, 1).toLowerCase();
    if (huruf >= 'a' && huruf <= 'z') f.push(base + "huruf_" + huruf + ".mp3");
    nomor.substring(1).split('').forEach(a => {
      if (!isNaN(a)) f.push(base + "angka_" + a + ".mp3");
    });
    f.push(base + "4_menuju_loket.mp3");
    f.push(base + "loket_" + loket + ".mp3");
    playQueueAudio(f);
  }
</script>


<script>
  document.querySelector('input[name="search"]').addEventListener('keyup', function() {
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
</script>