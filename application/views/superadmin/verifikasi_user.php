<div class="container-fluid">
        <h4><?= $title ?></h4>

  <div class="card shadow-sm">
    <!-- HEADER -->
    <div class="card-header bg-warning text-dark d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <i class="bx bx-user-check fs-4"></i>
        <strong>Daftar Akun Belum Terverifikasi</strong>
      </div>
      <span class="badge bg-dark">
        <?= count($users) ?> akun
      </span>
    </div>

    <div class="card-body p-0">
      <?php if (empty($users)): ?>
        <div class="p-4">
          <div class="alert alert-success d-flex align-items-center gap-2 mb-0">
            <i class="bx bx-check-circle fs-4"></i>
            <div>
              <strong>Semua akun sudah terverifikasi</strong><br>
              Tidak ada akun yang perlu tindakan admin 🎉
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-light">
              <tr class="text-center">
                <th width="60">No</th>
                <th class="text-start">Nama</th>
                <th class="text-start">Email</th>
                <th width="140">Role</th>
                <th>Instansi</th>
                <th width="170">Tanggal Daftar</th>
                <th width="220">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $i => $u): ?>
              <tr>
                <td class="text-center fw-bold"><?= $i + 1 ?></td>

                <td>
                  <div class="fw-semibold"><?= htmlspecialchars($u->nama_lengkap) ?></div>
                </td>

                <td>
                  <span class="text-muted"><?= htmlspecialchars($u->email) ?></span>
                </td>

                <td class="text-center">
                  <span class="badge bg-info text-dark text-uppercase">
                    <?= $u->nama_role ?>
                  </span>
                </td>

                <td class="text-center">
                  <?php if ($u->nama_instansi): ?>
                    <span class="badge bg-secondary"><?= $u->nama_instansi ?></span>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>

                <td class="text-center text-muted">
                  <?= date('d-m-Y H:i', strtotime($u->created_at)) ?>
                </td>

                <td>
                  <div class="d-flex justify-content-center gap-1">
                    <a href="<?= site_url('superadmin/verify_user_manual/'.$u->id) ?>"
                       class="btn btn-success btn-sm px-3"
                       onclick="return confirm('Verifikasi akun ini?')">
                      <i class="bx bx-check"></i>
                      Verifikasi
                    </a>

                    <a href="<?= site_url('superadmin/resend_verification/'.$u->id) ?>"
                       class="btn btn-primary btn-sm px-3">
                      <i class="bx bx-mail-send"></i>
                      Kirim Email
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
