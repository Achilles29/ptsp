<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta charset="UTF-8">
    <title>Daftar Akun Masyarakat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .bg-primary-custom {
            background-color: #0d6efd !important;
            /* Bootstrap primary */
        }

        .btn-primary-custom {
            background-color: #0d6efd;
            color: #fff;
        }

        .btn-primary-custom:hover {
            background-color: #0b5ed7;
            color: #fff;
        }

        .card {
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary-custom text-white text-center">
                        <h5><i class="fas fa-user-plus me-2"></i>Form Pendaftaran Akun</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Gagal:</strong> <?= $this->session->flashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $this->session->flashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= base_url('auth/register') ?>">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control"
                                        value="<?= set_value('nama_lengkap') ?>" required>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label>NIK</label>
                                    <input type="text" name="nik" class="form-control"
                                        value="<?= set_value('nik') ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control" required><?= set_value('alamat') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label>No HP</label>
                                <input type="text" name="no_hp" class="form-control"
                                    value="<?= set_value('no_hp') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="<?= set_value('email') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control"
                                    value="<?= set_value('username') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <button class="btn btn-primary-custom w-100">
                                <i class="fas fa-paper-plane me-1"></i> Daftar & Kirim Verifikasi
                            </button>
                        </form>

                        <hr>

                        <div class="text-center mt-2">
                            <p class="mb-1">Sudah punya akun?
                                <a href="<?= base_url('auth/login') ?>" class="fw-bold text-primary">Login di sini</a>
                            </p>

                            <?php
                            $CI = &get_instance();
                            $CI->load->model('User_model');
                            $superadmin = $CI->User_model->get_superadmin();
                            if ($superadmin) {
                                // Konversi 08xx → 62xx
                                $no_hp = preg_replace('/[^0-9]/', '', $superadmin->no_hp);
                                if (substr($no_hp, 0, 1) === '0') {
                                    $no_hp = '62' . substr($no_hp, 1);
                                }
                                $wa_link = 'https://wa.me/' . $no_hp;
                            ?>
                                <p class="mt-3">
                                    Kesulitan mendaftar?
                                    <a href="<?= $wa_link ?>" target="_blank" class="btn btn-outline-success btn-sm ms-1">
                                        <i class="fab fa-whatsapp"></i> Hubungi Admin
                                    </a>
                                </p>
                            <?php } ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5 pb-4">

        <!-- FontAwesome & Bootstrap JS -->
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>