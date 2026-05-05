<?php
$konsultasi_count = 0;
$produk_count = 0;
foreach ($hasil as $row) {
  if (($row->jenis_hasil ?? '') === 'konsultasi') {
    $konsultasi_count++;
  }
  if (($row->jenis_hasil ?? '') === 'produk_hukum') {
    $produk_count++;
  }
}
$export_query = http_build_query([
  'start_date' => $start_date,
  'end_date' => $end_date,
  'instansi_id' => $instansi_id,
  'layanan_id' => $layanan_id
]);
?>
<?php $this->load->view('laporan/_report_theme'); ?>
<div class="container-fluid report-page">
  <div class="report-shell">
    <section class="report-hero">
      <div class="report-hero-grid">
        <div>
          <div class="report-eyebrow"><i class="ri ri-file-chart-line"></i> Laporan Hasil</div>
          <h1 class="report-title">Laporan Detail Hasil Layanan</h1>
          <p class="report-subtitle">Telusuri hasil konsultasi maupun produk hukum dari antrian yang telah diproses, lalu buka detail petugas dan catatan tanpa layout yang acak.</p>
        </div>
        <div class="report-summary">
          <div class="report-summary-title">Ringkasan Tampilan</div>
          <div class="report-summary-list">
            <div class="report-summary-item">
              <span>Periode laporan</span>
              <strong><?= date('d M Y', strtotime($start_date)) ?> - <?= date('d M Y', strtotime($end_date)) ?></strong>
            </div>
            <div class="report-summary-item">
              <span>Total hasil</span>
              <strong><?= number_format((int) $total_rows) ?> data</strong>
            </div>
            <div class="report-summary-item">
              <span>Instansi aktif</span>
              <strong><?= $this->session->userdata('role_id') == 1 ? 'Semua / terfilter' : 'Instansi akun' ?></strong>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="report-kpi-grid">
      <article class="report-kpi">
        <small>Data Halaman Ini</small>
        <strong><?= number_format(count($hasil)) ?></strong>
        <span>Jumlah data hasil layanan yang tampil di halaman aktif.</span>
      </article>
      <article class="report-kpi">
        <small>Konsultasi</small>
        <strong><?= number_format($konsultasi_count) ?></strong>
        <span>Hasil layanan berbentuk konsultasi.</span>
      </article>
      <article class="report-kpi">
        <small>Produk Hukum</small>
        <strong><?= number_format($produk_count) ?></strong>
        <span>Dokumen atau produk hukum yang tercatat.</span>
      </article>
      <article class="report-kpi">
        <small>Selesai Diproses</small>
        <strong><?= number_format(count(array_filter($hasil, static function ($item) { return !empty($item->selesai_at); }))) ?></strong>
        <span>Data yang sudah memiliki waktu penyelesaian.</span>
      </article>
    </section>

    <section class="report-card">
      <div class="report-card-header">
        <div>
          <h2 class="report-card-title">Filter dan Ekspor Hasil Layanan</h2>
          <div class="report-card-note">Saring data per instansi dan layanan untuk melihat jenis hasil yang paling sering dihasilkan.</div>
        </div>
        <div class="report-actions">
          <a href="<?= site_url('laporan/export_detail_hasil_layanan_excel?' . $export_query) ?>" class="report-btn-export">
            <i class="ri ri-file-excel-2-line me-1"></i> Download Excel
          </a>
        </div>
      </div>
      <div class="report-card-body">
        <form method="get" class="report-filter row g-3 align-items-end">
          <div class="col-lg-2 col-md-6">
            <label class="form-label">Tanggal Awal</label>
            <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control">
          </div>
          <div class="col-lg-2 col-md-6">
            <label class="form-label">Tanggal Akhir</label>
            <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control">
          </div>

          <?php if ($this->session->userdata('role_id') == 1): ?>
            <div class="col-lg-3 col-md-6">
              <label class="form-label">Instansi</label>
              <select name="instansi_id" class="form-select" onchange="this.form.submit()">
                <option value="">Semua instansi</option>
                <?php foreach ($instansi_list as $i): ?>
                  <option value="<?= $i->id ?>" <?= $i->id == $instansi_id ? 'selected' : '' ?>>
                    <?= $i->nama_instansi ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endif; ?>

          <div class="col-lg-3 col-md-6">
            <label class="form-label">Layanan</label>
            <select name="layanan_id" class="form-select">
              <option value="">Semua layanan</option>
              <?php foreach ($layanan_list as $l): ?>
                <option value="<?= $l->id ?>" <?= $l->id == $layanan_id ? 'selected' : '' ?>>
                  <?= $l->nama_layanan ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-lg-2 col-md-6">
            <label class="form-label">Baris</label>
            <select name="limit" class="form-select">
              <?php foreach ([25, 50, 100] as $l): ?>
                <option value="<?= $l ?>" <?= $limit == $l ? 'selected' : '' ?>><?= $l ?> baris</option>
              <?php endforeach; ?>
              <option value="0" <?= (int) $limit === 0 ? 'selected' : '' ?>>Semua data</option>
            </select>
          </div>

          <div class="col-lg-2 col-md-6 d-grid">
            <button class="btn btn-primary report-btn-primary">
              <i class="ri ri-search-line me-1"></i> Tampilkan
            </button>
          </div>
        </form>
      </div>
    </section>

    <section class="report-card">
      <div class="report-card-header">
        <div>
          <h2 class="report-card-title">Tabel Hasil Layanan</h2>
          <div class="report-card-note">Klik detail untuk membuka ringkasan, produk hukum, catatan petugas, dan waktu selesai.</div>
        </div>
      </div>
      <div class="report-card-body">
        <div class="table-responsive">
          <table class="table report-table align-middle">
            <thead>
              <tr>
                <th class="text-center col-nowrap">No</th>
                <th class="col-nowrap">Tanggal</th>
                <th class="col-name-min">Instansi</th>
                <th class="col-name-min">Layanan</th>
                <th class="text-center col-nowrap">No Antrian</th>
                <th class="text-center col-nowrap">Jenis Hasil</th>
                <th class="text-center col-nowrap">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($hasil)): ?>
                <?php $no = $limit > 0 ? 1 + ($limit * ($page - 1)) : 1; ?>
                <?php foreach ($hasil as $h): ?>
                  <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= date('d/m/Y', strtotime($h->tanggal)) ?></td>
                    <td><?= html_escape($h->nama_instansi) ?></td>
                    <td><?= html_escape($h->nama_layanan) ?></td>
                    <td class="text-center"><strong><?= html_escape($h->nomor_antrian) ?></strong></td>
                    <td class="text-center">
                      <span class="report-pill <?= ($h->jenis_hasil ?? '') === 'produk_hukum' ? 'info' : 'success' ?>">
                        <?= ucfirst($h->jenis_hasil ?? '-') ?>
                      </span>
                    </td>
                    <td class="text-center">
                      <button class="btn btn-outline-primary btn-sm btn-toggle-detail" data-target="detail<?= $h->id ?>">
                        <i class="ri ri-eye-line me-1"></i>Detail
                      </button>
                    </td>
                  </tr>
                  <tr class="report-subrow" id="detail<?= $h->id ?>" style="display:none;">
                    <td colspan="7">
                      <div class="report-subrow-content">
                        <div class="report-detail-grid">
                          <div class="report-detail-box">
                            <small>Petugas</small>
                            <strong><?= $h->nama_petugas ? html_escape($h->nama_petugas) : '-' ?></strong>
                          </div>
                          <div class="report-detail-box">
                            <small>Waktu Selesai</small>
                            <strong><?= $h->selesai_at ? html_escape($h->selesai_at) : '-' ?></strong>
                          </div>
                          <div class="report-detail-box">
                            <small>Status Antrian</small>
                            <strong><?= ucfirst($h->status ?? '-') ?></strong>
                          </div>
                        </div>

                        <?php if (($h->jenis_hasil ?? '') === 'konsultasi'): ?>
                          <div class="report-detail-box">
                            <small>Ringkasan Konsultasi</small>
                            <div><?= nl2br(html_escape($h->ringkasan_konsultasi ?? '-')) ?></div>
                          </div>
                        <?php else: ?>
                          <div class="report-detail-grid">
                            <div class="report-detail-box">
                              <small>Jenis Produk Hukum</small>
                              <strong><?= html_escape($h->jenis_produk_hukum ?? '-') ?></strong>
                            </div>
                            <div class="report-detail-box">
                              <small>Nomor Produk</small>
                              <strong><?= html_escape($h->nomor_produk ?? '-') ?></strong>
                            </div>
                            <div class="report-detail-box">
                              <small>Tanggal Produk</small>
                              <strong><?= html_escape($h->tanggal_produk ?? '-') ?></strong>
                            </div>
                          </div>
                        <?php endif; ?>

                        <div class="report-detail-box">
                          <small>Catatan Petugas</small>
                          <div><?= nl2br(html_escape($h->catatan_petugas ?? '-')) ?></div>
                        </div>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="report-empty">Belum ada hasil layanan pada periode ini.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <?php if ((int) $limit > 0 && !empty($pagination)): ?>
          <div class="report-pagination mt-4">
            <?= $pagination ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btn-toggle-detail').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const row = document.getElementById(this.dataset.target);
      if (!row) return;
      const isVisible = row.style.display !== 'none';
      row.style.display = isVisible ? 'none' : 'table-row';
      this.classList.toggle('active', !isVisible);
      const icon = this.querySelector('i');
      if (icon) {
        icon.className = isVisible ? 'ri ri-eye-line me-1' : 'ri ri-eye-off-line me-1';
      }
    });
  });
});
</script>
