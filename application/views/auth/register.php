<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Akun MPP</title>

  <!-- Bootstrap -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/auth-theme.css') ?>">
</head>

<body class="auth-screen">

<div class="container auth-shell">
  <div class="auth-layout">
    <div class="auth-spotlight">
      <div class="auth-brand">
        <span class="auth-brand-mark">
          <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo MPP">
        </span>
        <div>
          <h2 class="mb-0">Buat Akun MPP</h2>
          <p class="mb-0 opacity-75">Satu akun untuk seluruh layanan publik digital</p>
        </div>
      </div>

      <h1 class="mb-3">Mulai pengalaman layanan yang lebih rapi dan cepat.</h1>
      <p class="mb-0 opacity-75">
        Setelah registrasi, akun Anda bisa dipakai untuk mendaftar antrian online, memeriksa riwayat layanan, dan melakukan check-in dengan alur yang lebih sederhana.
      </p>

      <div class="auth-stat-grid">
        <div class="auth-stat">
          <strong>Verifikasi aman</strong>
          <span>Pendaftaran dikirim dengan validasi email agar akun tetap terjaga</span>
        </div>
        <div class="auth-stat">
          <strong>Satu portal</strong>
          <span>Semua informasi layanan tersusun rapi di satu dashboard</span>
        </div>
      </div>
    </div>

    <div class="auth-card">
      <div class="auth-card-head">
        <h3 class="auth-card-title"><i class="ri ri-user-add-line me-1"></i> Pendaftaran Akun Masyarakat</h3>
        <div class="auth-card-subtitle">Lengkapi data Anda agar proses verifikasi dan layanan berjalan lebih lancar.</div>
      </div>

      <div class="auth-card-body">
          <?php
          $cooldown_minutes = isset($resend_cooldown_minutes) ? (int) $resend_cooldown_minutes : 5;
          $verification_email = $this->session->flashdata('verification_email');
          ?>

          <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger py-2">
              <?= $this->session->flashdata('error') ?>
            </div>
          <?php endif; ?>

          <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success py-2">
              <?= $this->session->flashdata('success') ?>
            </div>
          <?php endif; ?>

          <div class="alert alert-info border-0 mb-4" style="background:#eef4ff;color:#183b74;border-radius:12px;">
            <a href="#resend-box" class="d-flex align-items-center gap-2 text-decoration-none" style="color:inherit;" data-bs-toggle="collapse">
              <i class="ri ri-mail-check-line"></i>
              <span class="fw-semibold">Belum menerima email verifikasi?</span>
              <i class="ri ri-arrow-down-s-line ms-auto"></i>
            </a>
            <div class="collapse mt-3" id="resend-box">
              <p class="mb-2" style="font-size:.9rem;">Masukkan email yang dipakai mendaftar. Sistem akan mengizinkan kirim ulang setelah jeda <?= $cooldown_minutes ?> menit dari pengiriman terakhir.</p>
              <form method="post" action="<?= base_url('auth/resend_verification') ?>" class="row g-2">
                <input type="hidden" name="source" value="register">
                <div class="col-8">
                  <input type="email" name="email" class="form-control auth-form-control" placeholder="nama@email.com" value="<?= htmlspecialchars($verification_email ?? '') ?>" required>
                </div>
                <div class="col-4 d-grid">
                  <button type="submit" class="btn btn-outline-primary">
                    <i class="ri ri-mail-send-line me-1"></i> Kirim Ulang
                  </button>
                </div>
              </form>
            </div>
          </div>

          <form method="post" action="<?= base_url('auth/register') ?>">
            <input type="hidden" name="nik" value="<?= set_value('nik') ?>">

            <!-- DATA PRIBADI -->
            <div class="form-section-title">
              <i class="ri ri-id-card-line"></i> Data Pribadi
            </div>

            <div class="mb-3">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" name="nama_lengkap" class="form-control auth-form-control"
                     placeholder="Masukkan nama lengkap sesuai KTP"
                     value="<?= set_value('nama_lengkap') ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Alamat</label>
              <textarea name="alamat" class="form-control auth-form-control" rows="2"
                        placeholder="Alamat tempat tinggal" required><?= set_value('alamat') ?></textarea>
            </div>

            <div class="row g-3 mb-1">
              <div class="col-md-6">
                <label class="form-label">No HP / WhatsApp</label>
                <input type="text" name="no_hp" class="form-control auth-form-control"
                       placeholder="08xxxxxxxxxx"
                       value="<?= set_value('no_hp') ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control auth-form-control"
                       placeholder="nama@email.com"
                       value="<?= set_value('email') ?>" required>
              </div>
            </div>

            <!-- AKUN -->
            <div class="form-section-title mt-4">
              <i class="ri ri-shield-user-line"></i> Informasi Akun
            </div>

            <div class="row g-3 mb-1">
              <div class="col-md-6">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control auth-form-control"
                       placeholder="Nama pengguna untuk login"
                       value="<?= set_value('username') ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Password</label>
                <div class="position-relative">
                  <input type="password" name="password" id="regPassword" class="form-control auth-form-control"
                         placeholder="Minimal 6 karakter" required>
                  <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y pe-3 text-muted"
                          onclick="togglePass('regPassword', this)" tabindex="-1" style="line-height:1;">
                    <i class="ri ri-eye-off-line"></i>
                  </button>
                </div>
              </div>
            </div>

            <button class="btn btn-primary w-100 auth-action mt-4">
              <i class="ri ri-mail-send-line me-1"></i>
              Daftar &amp; Kirim Verifikasi Email
            </button>
          </form>

          <hr>

          <div class="text-center auth-footer-note">
            Sudah punya akun?
            <a href="<?= base_url('auth/login') ?>" class="auth-link">Login di sini</a>
          </div>

          <?php
          $CI = &get_instance();
          $CI->load->model('User_model');
          $superadmin = $CI->User_model->get_superadmin();
          if ($superadmin):
              $no_hp = preg_replace('/[^0-9]/', '', $superadmin->no_hp);
              if (substr($no_hp, 0, 1) === '0') {
                  $no_hp = '62' . substr($no_hp, 1);
              }
          ?>
          <div class="text-center mt-3">
            <small>Kesulitan mendaftar?</small><br>
            <a href="https://wa.me/<?= $no_hp ?>" target="_blank"
               class="btn btn-outline-success btn-sm mt-1">
              <i class="ri ri-whatsapp-line"></i> Hubungi Admin
            </a>
          </div>
          <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePass(id, btn) {
  const inp = document.getElementById(id);
  const icon = btn.querySelector('i');
  if (inp.type === 'password') {
    inp.type = 'text';
    icon.className = 'ri ri-eye-line';
  } else {
    inp.type = 'password';
    icon.className = 'ri ri-eye-off-line';
  }
}
</script>

</body>
</html>
