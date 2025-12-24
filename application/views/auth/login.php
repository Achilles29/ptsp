<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ini penting untuk responsif -->
  <title>Login MPP</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-5">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">Login Pengguna</h5>
          </div>
          <div class="card-body p-4">
            <?php if ($this->session->flashdata('error')): ?>
              <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>
            <form method="post" action="<?= base_url('auth/login') ?>">
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required autofocus>
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <button class="btn btn-primary w-100">Login</button>
            </form>
            <hr>
            <div class="text-center mt-3 small">
              <a href="<?= base_url('auth/register') ?>" class="d-block mb-1">Belum punya akun? Daftar di sini</a>
              <a href="<?= base_url('auth/forgot_password') ?>" class="text-danger">Lupa Password?</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>