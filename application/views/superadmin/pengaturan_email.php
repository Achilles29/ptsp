<div class="container-fluid py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
      <h3 class="mb-1"><?= $title ?></h3>
      <p class="text-muted mb-0">Atur akun SMTP, identitas pengirim, cooldown kirim ulang, dan isi email verifikasi tanpa perlu mengubah script.</p>
    </div>
    <a href="<?= base_url('superadmin/verifikasi_user') ?>" class="btn btn-outline-primary">
      <i class="ri ri-mail-send-line me-1"></i> Buka Verifikasi Akun
    </a>
  </div>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pb-0">
          <h5 class="mb-1">SMTP dan Template Verifikasi</h5>
          <p class="text-muted mb-0">Gunakan App Password Gmail atau SMTP provider lain. Password SMTP boleh dikosongkan jika tidak ingin diubah.</p>
        </div>
        <div class="card-body">
          <form method="post" action="<?= base_url('superadmin/simpan_pengaturan_email') ?>">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nama Pengirim</label>
                <input type="text" name="from_name" class="form-control" value="<?= htmlspecialchars($settings['from_name'] ?? '') ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email Pengirim</label>
                <input type="email" name="from_email" class="form-control" value="<?= htmlspecialchars($settings['from_email'] ?? '') ?>" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">SMTP Host</label>
                <input type="text" name="smtp_host" class="form-control" value="<?= htmlspecialchars($settings['smtp_host'] ?? 'smtp.gmail.com') ?>" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">Port</label>
                <input type="number" name="smtp_port" class="form-control" value="<?= (int) ($settings['smtp_port'] ?? 465) ?>" min="1" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">Enkripsi</label>
                <select name="smtp_crypto" class="form-select">
                  <?php $smtp_crypto = (string) ($settings['smtp_crypto'] ?? 'ssl'); ?>
                  <option value="ssl" <?= $smtp_crypto === 'ssl' ? 'selected' : '' ?>>SSL</option>
                  <option value="tls" <?= $smtp_crypto === 'tls' ? 'selected' : '' ?>>TLS</option>
                  <option value="" <?= $smtp_crypto === '' ? 'selected' : '' ?>>Tanpa Enkripsi</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Akun SMTP</label>
                <input type="text" name="smtp_user" class="form-control" value="<?= htmlspecialchars($settings['smtp_user'] ?? '') ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Password SMTP / App Password</label>
                <input type="password" name="smtp_pass" class="form-control" placeholder="Kosongkan jika tidak ingin diubah">
              </div>

              <div class="col-md-6">
                <label class="form-label">Reply-To Email</label>
                <input type="email" name="reply_to_email" class="form-control" value="<?= htmlspecialchars($settings['reply_to_email'] ?? '') ?>" placeholder="Opsional">
              </div>
              <div class="col-md-6">
                <label class="form-label">Cooldown Kirim Ulang</label>
                <div class="input-group">
                  <input type="number" name="resend_cooldown_minutes" class="form-control" value="<?= (int) ($settings['resend_cooldown_minutes'] ?? 5) ?>" min="1" required>
                  <span class="input-group-text">menit</span>
                </div>
              </div>

              <div class="col-12">
                <label class="form-label">Subjek Verifikasi</label>
                <input type="text" name="verification_subject" class="form-control" value="<?= htmlspecialchars($settings['verification_subject'] ?? '') ?>" required>
              </div>

              <div class="col-12">
                <label class="form-label">Isi Email Verifikasi</label>
                <textarea name="verification_message" class="form-control" rows="14" required><?= htmlspecialchars($settings['verification_message'] ?? $default_message) ?></textarea>
                <div class="form-text">
                  Placeholder yang bisa dipakai: <code>{nama_lengkap}</code>, <code>{email}</code>, <code>{verification_link}</code>, <code>{app_name}</code>, <code>{from_name}</code>.
                </div>
              </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="ri ri-save-line me-1"></i> Simpan Pengaturan
              </button>
              <a href="<?= base_url('superadmin/pengaturan_email') ?>" class="btn btn-outline-secondary">
                Reset Tampilan
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-0 pb-0">
          <h5 class="mb-1">Panduan Gmail</h5>
          <p class="text-muted mb-0">Untuk akun Gmail biasa, gunakan App Password, bukan password login utama.</p>
        </div>
        <div class="card-body">
          <ol class="ps-3 mb-0">
            <li class="mb-2">Aktifkan <strong>2-Step Verification</strong> pada akun Gmail yang akan dipakai mengirim email.</li>
            <li class="mb-2">Buka menu <strong>App Passwords</strong> di akun Google, lalu buat password khusus untuk aplikasi mail.</li>
            <li class="mb-2">Isi <strong>SMTP Host</strong> dengan <code>smtp.gmail.com</code>.</li>
            <li class="mb-2">Pilih <strong>SSL + port 465</strong> atau <strong>TLS + port 587</strong>.</li>
            <li class="mb-2">Isi <strong>Akun SMTP</strong> dengan alamat Gmail lengkap.</li>
            <li class="mb-2">Masukkan <strong>App Password</strong> ke kolom password SMTP, bukan password Gmail utama.</li>
            <li>Disarankan <strong>Email Pengirim</strong> sama dengan akun SMTP agar Gmail tidak menolak atau menandai sebagai spoofing.</li>
          </ol>
        </div>
      </div>

      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pb-0">
          <h5 class="mb-1">Catatan Anti Spam</h5>
          <p class="text-muted mb-0">Tips agar email verifikasi lebih mudah lolos ke inbox.</p>
        </div>
        <div class="card-body">
          <ul class="mb-0 ps-3">
            <li class="mb-2">Jangan kirim terlalu banyak email mendadak dari akun Gmail baru.</li>
            <li class="mb-2">Pastikan isi email tidak hanya berisi link, tambahkan penjelasan dan identitas pengirim yang jelas.</li>
            <li class="mb-2">Hindari pengiriman berulang ke alamat yang salah atau tidak aktif.</li>
            <li class="mb-2">Gunakan subjek yang konsisten dan tidak berlebihan seperti promosi.</li>
            <li class="mb-2">Minta penerima cek folder spam terlebih dahulu, lalu tandai email sebagai bukan spam.</li>
            <li>Jika trafik email mulai banyak, pertimbangkan pindah ke Gmail Workspace atau layanan transactional email.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
