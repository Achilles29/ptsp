<?php $this->load->view('laporan/_report_theme'); ?>
<div class="container-fluid report-page">
  <div class="report-shell">
    <section class="report-hero">
      <div class="report-hero-grid">
        <div>
          <div class="report-eyebrow"><i class="ri ri-team-line"></i> Kinerja Operasional</div>
          <h1 class="report-title">Laporan Kinerja Petugas</h1>
          <p class="report-subtitle">Laporan ini menampilkan jumlah layanan selesai, komposisi hasil layanan, dan rata-rata durasi per petugas untuk membaca beban kerja yang sesungguhnya.</p>
        </div>
        <div class="report-summary">
          <div class="report-summary-title">Filter Aktif</div>
          <form method="get" class="report-summary-list">
            <div class="report-summary-item">
              <span>Tanggal awal</span>
              <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control form-control-sm">
            </div>
            <div class="report-summary-item">
              <span>Tanggal akhir</span>
              <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control form-control-sm">
            </div>
            <?php if ($this->session->userdata('role_id') == 1): ?>
              <div class="report-summary-item">
                <span>Instansi</span>
                <select name="instansi_id" class="form-select form-select-sm">
                  <option value="">Semua instansi</option>
                  <?php foreach ($instansi_list as $instansi): ?>
                    <option value="<?= (int) $instansi->id ?>" <?= (int) $instansi_id === (int) $instansi->id ? 'selected' : '' ?>>
                      <?= html_escape($instansi->nama_instansi) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php endif; ?>
            <button class="btn btn-light btn-sm">Tampilkan</button>
          </form>
        </div>
      </div>
    </section>

    <section class="report-card">
      <div class="report-card-header">
        <div>
          <h2 class="report-card-title">Tabel Kinerja Petugas</h2>
          <div class="report-card-note">Baris teratas menunjukkan volume kerja tertinggi.</div>
        </div>
      </div>
      <div class="report-card-body">
        <div class="table-responsive">
          <table class="table report-table align-middle">
            <thead>
              <tr>
                <th class="col-name-min">Petugas</th>
                <th class="col-name-min">Instansi</th>
                <th class="text-center col-nowrap">Selesai</th>
                <th class="text-center col-nowrap">Konsultasi</th>
                <th class="text-center col-nowrap">Produk Hukum</th>
                <th class="text-center col-nowrap">Rata Durasi</th>
                <th class="text-center col-nowrap">Layanan Pertama</th>
                <th class="text-center col-nowrap">Layanan Terakhir</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                  <tr>
                    <td><?= html_escape($row->nama_petugas ?? '-') ?></td>
                    <td><?= html_escape($row->nama_instansi ?? '-') ?></td>
                    <td class="text-center"><span class="report-pill info"><?= (int) $row->total_selesai ?></span></td>
                    <td class="text-center"><?= (int) $row->total_konsultasi ?></td>
                    <td class="text-center"><?= (int) $row->total_produk_hukum ?></td>
                    <td class="text-center"><?= number_format((float) $row->rata_durasi_menit, 1) ?> menit</td>
                    <td class="text-center"><?= $row->layanan_pertama ? date('d/m H:i', strtotime($row->layanan_pertama)) : '-' ?></td>
                    <td class="text-center"><?= $row->layanan_terakhir ? date('d/m H:i', strtotime($row->layanan_terakhir)) : '-' ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="8" class="report-empty">Belum ada data kinerja petugas pada periode ini.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
</div>
