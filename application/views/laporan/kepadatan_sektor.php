<?php $this->load->view('laporan/_report_theme'); ?>
<div class="container-fluid report-page">
  <div class="report-shell">
    <section class="report-hero">
      <div class="report-hero-grid">
        <div>
          <div class="report-eyebrow"><i class="ri ri-layout-grid-line"></i> Distribusi Sektor</div>
          <h1 class="report-title">Laporan Kepadatan Sektor</h1>
          <p class="report-subtitle">Gunakan laporan ini untuk melihat sektor mana yang paling padat dan sektor mana yang cenderung melambat berdasarkan volume dan durasi layanan.</p>
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
          <h2 class="report-card-title">Tabel Kepadatan Sektor</h2>
          <div class="report-card-note">Rata-rata antrian per hari membantu membaca beban, sedangkan rata durasi membantu membaca potensi perlambatan.</div>
        </div>
      </div>
      <div class="report-card-body">
        <div class="table-responsive">
          <table class="table report-table align-middle">
            <thead>
              <tr>
                <th class="col-name-min">Sektor</th>
                <th class="text-center col-nowrap">Total Antrian</th>
                <th class="text-center col-nowrap">Total Selesai</th>
                <th class="text-center col-nowrap">Rata/Hari</th>
                <th class="text-center col-nowrap">Rata Durasi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                  <tr>
                    <td><?= html_escape($row->nama_sektor ?: 'Tanpa Sektor') ?></td>
                    <td class="text-center"><span class="report-pill info"><?= (int) $row->total_antrian ?></span></td>
                    <td class="text-center"><?= (int) $row->total_selesai ?></td>
                    <td class="text-center"><?= number_format((float) $row->rata_antrian_per_hari, 1) ?></td>
                    <td class="text-center"><?= number_format((float) $row->rata_durasi_menit, 1) ?> menit</td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5" class="report-empty">Belum ada data sektor pada periode ini.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
</div>
