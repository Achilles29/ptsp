<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lupa Password - MPP</title>

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
          <h2 class="mb-0">Pemulihan Akun</h2>
          <p class="mb-0 opacity-75">Tetap mudah kembali ke akun Anda</p>
        </div>
      </div>

      <h1 class="mb-3">Reset password tanpa proses yang merepotkan.</h1>
      <p class="mb-0 opacity-75">
        Masukkan email yang terdaftar, lalu sistem akan membantu mengirimkan password baru agar Anda bisa kembali menggunakan layanan.
      </p>

      <div class="auth-stat-grid">
        <div class="auth-stat">
          <strong>Cepat dipulihkan</strong>
          <span>Satu form singkat untuk memulai proses reset</span>
        </div>
        <div class="auth-stat">
          <strong>Lebih jelas</strong>
          <span>Tampilan dirancang supaya tetap nyaman dipakai di layar kecil maupun besar</span>
        </div>
      </div>
    </div>

    <div class="auth-card">
      <div class="auth-card-head">
        <h3 class="auth-card-title"><i class="ri ri-key-2-line me-1"></i> Reset Password</h3>
        <div class="auth-card-subtitle">Masukkan email terdaftar dan sistem akan mengirimkan password baru ke alamat tersebut.</div>
      </div>

      <div class="auth-card-body">

          <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger py-2">
              <?= $this->session->flashdata('error') ?>
            </div>
          <?php elseif ($this->session->flashdata('success')): ?>
            <div class="alert alert-success py-2">
              <?= $this->session->flashdata('success') ?>
            </div>
          <?php endif; ?>

          <p class="small text-muted mb-3">
            Masukkan <strong>email yang terdaftar</strong>.  
            Sistem akan mengirimkan password baru ke email Anda.
          </p>

          <form method="post" action="<?= base_url('auth/forgot_password') ?>">

            <div class="mb-3 position-relative auth-has-icon">
              <i class="ri ri-mail-line auth-input-icon"></i>
              <input type="email" name="email"
                     class="form-control auth-form-control"
                     placeholder="Email terdaftar"
                     required autofocus>
            </div>

            <button class="btn btn-warning w-100 auth-action">
              <i class="ri ri-send-plane-line me-1"></i>
              Kirim Password Baru
            </button>
          </form>

          <hr>

          <div class="text-center auth-footer-note">
            <a href="<?= base_url('auth/login') ?>" class="auth-link">
              ← Kembali ke Login
            </a>
          </div>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
