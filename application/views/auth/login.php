<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login MPP</title>

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
          <h2 class="mb-0">Mal Pelayanan Publik</h2>
          <p class="mb-0 opacity-75">Kabupaten Rembang · Jawa Tengah</p>
        </div>
      </div>

      <h1 class="mb-3">Akses layanan publik tanpa hambatan.</h1>
      <p class="mb-0 opacity-75">
        Masuk untuk mendaftar antrian, memantau status kunjungan, dan mengelola kebutuhan layanan dengan tampilan yang nyaman di desktop maupun mobile.
      </p>

      <div class="auth-stat-grid">
        <div class="auth-stat">
          <strong>Responsif penuh</strong>
          <span>Nyaman dibuka di desktop, tablet, dan ponsel</span>
        </div>
        <div class="auth-stat">
          <strong>Pendaftaran lebih cepat</strong>
          <span>Pilih instansi, layanan, dan tanggal tanpa alur yang membingungkan</span>
        </div>
      </div>
    </div>

    <div class="auth-card">
      <div class="auth-card-head">
        <h3 class="auth-card-title">Login Pengguna</h3>
        <div class="auth-card-subtitle">Masuk ke akun Anda untuk mengakses seluruh layanan MPP.</div>
      </div>

      <div class="auth-card-body">
          <?php
          $cooldown_minutes = isset($resend_cooldown_minutes) ? (int) $resend_cooldown_minutes : 5;
          $verification_email = $this->session->flashdata('unverified_email') ?: $this->session->flashdata('verification_email');
          ?>

          <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger py-2">
              <?= $this->session->flashdata('error') ?>
            </div>
          <?php endif; ?>

          <form method="post" action="<?= base_url('auth/login') ?>">

            <!-- Username -->
            <div class="mb-3 position-relative auth-has-icon">
              <i class="ri ri-user-3-line auth-input-icon"></i>
              <input type="text" name="username"
                     class="form-control auth-form-control"
                     placeholder="Username"
                     required autofocus>
            </div>

            <!-- Password -->
            <div class="mb-3 position-relative auth-has-icon">
              <i class="ri ri-lock-2-line auth-input-icon"></i>
              <input type="password" name="password"
                     class="form-control auth-form-control"
                     placeholder="Password"
                     required>
            </div>

            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me" value="1" checked>
              <label class="form-check-label" for="remember_me">
                Ingat saya (tetap login)
              </label>
            </div>

            <button class="btn btn-primary w-100 auth-action">
              <i class="ri ri-login-box-line me-1"></i> Login
            </button>
          </form>

          <hr>

          <div class="p-3 rounded-4 mb-3" style="background:#eef4ff;border:1px solid #d6e4ff;">
            <div class="fw-semibold mb-2 text-primary">
              <i class="ri ri-mail-send-line me-1"></i> Kirim Ulang Verifikasi
            </div>
            <p class="small text-muted mb-3">Jika akun Anda belum aktif dan email belum masuk, kirim ulang verifikasi setelah jeda <?= $cooldown_minutes ?> menit.</p>
            <form method="post" action="<?= base_url('auth/resend_verification') ?>" class="row g-2">
              <input type="hidden" name="source" value="login">
              <div class="col-12">
                <input type="email" name="email" class="form-control auth-form-control" placeholder="Email yang dipakai saat daftar" value="<?= htmlspecialchars($verification_email ?? '') ?>" required>
              </div>
              <div class="col-12 d-grid">
                <button type="submit" class="btn btn-outline-primary">
                  Kirim Ulang Email Verifikasi
                </button>
              </div>
            </form>
          </div>

          <div class="text-center auth-footer-note">
            <a href="<?= base_url('auth/register') ?>" class="auth-link d-block mb-1">
              Belum punya akun? <strong>Daftar</strong>
            </a>
            <a href="<?= base_url('auth/forgot_password') ?>" class="auth-link text-danger">
              Lupa Password?
            </a>
          </div>

      </div>
    </div>
  </div>
</div>

</body>
</html>
