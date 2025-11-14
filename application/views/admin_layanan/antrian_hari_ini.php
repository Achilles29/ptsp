<style>
  .modal-content {
    border-radius: 10px;
  }

  .modal-body div {
    margin-bottom: 5px;
  }

  .modal-body p {
    font-size: 0.95rem;
  }

  .modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    color: #ffffff !important;
  }

  .modal-header {
    background: linear-gradient(90deg, #7b2cbf, #9d4edd);
    color: white;
    border-radius: 10px 10px 0 0;
    padding: 0.75rem 1.25rem;
  }


  .badge.small {
    font-size: 0.75rem;
    padding: 0.35em 0.6em;
  }
</style>

<div class="container-fluid px-4 mt-4">
  <h4 class="text-maroon"><i class="fas fa-list-ol me-2"></i><?= $title ?></h4>
  <hr>

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
              <th>Layanan</th> <!-- tambahkan ini -->
              <th>Status</th>
              <th width="180">Aksi</th>
            </tr>
          </thead>


          <tbody id="antrian-body">
            <?php $this->load->view('admin_layanan/_partial_antrian_table', ['antrian' => $antrian]); ?>
          </tbody>

        </table>
      </div>
    </div>
  </div>
</div>


<!-- Modal Detail -->
<div class="modal fade" id="modalDetailAntrian" tabindex="-1" aria-labelledby="detailAntrianLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header" style="background: linear-gradient(90deg, #6f42c1, #8e44ad); color: #ffffff;">
        <h5 class="modal-title fw-bold">
          <i class="fas fa-user-clock me-2"></i> Rincian Antrian Pemohon
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4 py-3">
        <div class="row g-3">
          <div class="col-md-6">
            <p class="mb-1"><span class="text-muted fw-semibold">Nomor Antrian:</span> <span class="text-dark fw-bold" id="detail_nomor"></span></p>
            <p class="mb-1"><span class="text-muted fw-semibold">Nama Pemohon:</span> <span class="text-dark" id="detail_nama"></span></p>
            <p class="mb-1"><span class="text-muted fw-semibold">No HP:</span> <span class="text-dark" id="detail_hp"></span></p>
            <p class="mb-0"><span class="text-muted fw-semibold">Layanan:</span> <span class="text-dark" id="detail_layanan"></span></p>
          </div>
          <div class="col-md-6">
            <p class="mb-1"><span class="text-muted fw-semibold">Tanggal Kunjungan:</span> <span class="text-dark" id="detail_tanggal"></span></p>
            <p class="mb-1"><span class="text-muted fw-semibold">Waktu Daftar:</span> <span class="text-dark" id="detail_waktu"></span></p>
            <p class="mb-1"><span class="text-muted fw-semibold">Status:</span>
              <span id="detail_status" class="badge rounded-pill bg-secondary small"></span>
            </p>
            <p class="mb-0"><span class="text-muted fw-semibold">Estimasi:</span> <em class="text-primary" id="detail_estimasi"></em></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  $(document).ready(function() {

    // ✅ Detail Antrian (modal)
    $(document).on('click', '.btn-detail', function(e) {
      e.preventDefault();
      const id = $(this).data('id');

      $.getJSON('<?= base_url('admin_layanan/get_detail_antrian') ?>/' + id, function(data) {
        $('#detail_nomor').text(data.nomor_antrian);
        $('#detail_nama').text(data.nama_lengkap);
        $('#detail_hp').text(data.no_hp);
        $('#detail_tanggal').text(data.tanggal);
        $('#detail_layanan').text(data.nama_layanan);
        $('#detail_waktu').text(data.created_at ?? '-');
        $('#detail_estimasi').text(data.estimasi_waktu + ' WIB');

        const badge = $('#detail_status');
        badge.text(data.status);
        badge.removeClass().addClass('badge small');

        switch (data.status) {
          case 'terdaftar':
            badge.addClass('bg-secondary');
            break;
          case 'dipanggil':
            badge.addClass('bg-warning text-dark');
            break;
          case 'selesai':
            badge.addClass('bg-success');
            break;
          case 'batal':
            badge.addClass('bg-danger');
            break;
        }

        var modal = new bootstrap.Modal(document.getElementById('modalDetailAntrian'));
        modal.show();
      });
    });


    // ✅ Auto refresh tabel antrian
    function refreshAntrian() {
      $('#antrian-body').load('<?= base_url('admin_layanan/refresh_antrian') ?>', function() {
        $('#antrian-body tr:first').addClass('table-success');
        setTimeout(() => $('#antrian-body tr').removeClass('table-success'), 2000);
      });
    }
    setInterval(refreshAntrian, 10000);


    // ✅ Notifikasi suara (diaktifkan setelah interaksi)
    let soundEnabled = false;
    const audio = document.getElementById('notifikasiSound');

    document.addEventListener('click', function() {
      soundEnabled = true;
      audio.play().then(() => audio.pause()).catch(() => {});
    }, {
      once: true
    });


    // ✅ Deteksi antrian baru (opsional)
    let lastAntrianCount = 0;

    function checkAntrianBaru() {
      $.getJSON('<?= base_url('admin_layanan/jumlah_antrian_hari_ini') ?>', function(data) {
        if (data.jumlah > lastAntrianCount) {
          if (lastAntrianCount > 0 && soundEnabled) {
            toastr.success('📢 Antrian baru masuk!');
            audio.currentTime = 0;
            audio.play().catch(err => console.log('Autoplay diblokir:', err));
          }
          lastAntrianCount = data.jumlah;
        } else {
          lastAntrianCount = data.jumlah;
        }
      });
    }
    setInterval(checkAntrianBaru, 5000);


    // ✅ SweetAlert konfirmasi untuk panggil, selesai, batal
    $(document).on('click', '.btn-panggil, .btn-selesai, .btn-batal', function(e) {
      e.preventDefault();
      const btn = $(this);
      const id = btn.data('id');
      const url = btn.data('url');

      const type = btn.hasClass('btn-panggil') ? 'panggil' :
        btn.hasClass('btn-selesai') ? 'selesai' :
        'batal';

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

          // 🔔 Jika tombol panggil → pakai AJAX (tidak reload)
          if (type === 'panggil') {
            $.ajax({
              url: '<?= site_url('admin_layanan/panggil/') ?>' + id,
              type: 'POST',
              dataType: 'json',
              success: function(res) {
                if (res.success) {
                  Swal.fire('Berhasil', res.message, 'success');
                  refreshAntrian();

                  const nomor = res.nomor_antrian || '';
                  const loket = res.loket || '';
                  playAntrianAudio(nomor, loket);
                } else {
                  Swal.fire('Gagal', 'Terjadi kesalahan saat memanggil.', 'error');
                }
              },
              error: function() {
                Swal.fire('Gagal', 'Koneksi ke server gagal.', 'error');
              }
            });
          } else {
            // selesai & batal tetap redirect biasa
            window.location.href = url;
          }

        }
      });
    });

  });
</script>

<script>
  function playQueueAudio(files) {
    if (!files.length) return;

    const audio = new Audio(files.shift());
    audio.play();
    audio.onended = () => playQueueAudio(files);
  }

  // 🔊 Fungsi untuk memanggil suara antrian berdasarkan data AJAX
  function playAntrianAudio(nomor, loket) {
    const base = "<?= base_url('assets/sounds/voice/') ?>";

    const files = [];
    files.push(base + "1_nomor_antrian.mp3");

    // Ambil huruf depan (A/B/C/dll)
    const huruf = nomor.substring(0, 1).toLowerCase();
    if (huruf >= 'a' && huruf <= 'z') {
      files.push(base + "huruf_" + huruf + ".mp3");
    }

    // Ambil angka setelah huruf
    const angkaPart = nomor.substring(1).split('');
    angkaPart.forEach(a => {
      if (!isNaN(a)) files.push(base + "angka_" + a + ".mp3");
    });

    // Tambah suara “Menuju ke loket”
    files.push(base + "4_menuju_loket.mp3");

    // Tambah angka loket (misal: “1”, “2”, “3”)
    files.push(base + "loket_" + loket + ".mp3");

    // Jalankan secara berurutan
    playQueueAudio(files);
  }
</script>


<!-- ✅ Suara notifikasi -->
<!-- <audio id="notifikasiSound" src="<?= base_url('assets/sounds/antrian.mp3') ?>" preload="auto"></audio> -->