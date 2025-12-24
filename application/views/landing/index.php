<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/img/favicon.ico') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/img/favicon.ico') ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/css/landing.css') ?>" rel="stylesheet">
    <script async src="https://www.instagram.com/embed.js"></script>
</head>

<body>
    <!-- HERO -->
    <section class="hero d-flex align-items-center justify-content-center text-center">
        <div class="overlay"></div>
        <div class="container position-relative z-2">
            <img src="<?= base_url('assets/img/logo-smaller.jpeg') ?>" alt="Logo MPP Rembang" class="hero-logo mb-3">
            <h1 class="fw-bold mb-2 text-dark">Selamat Datang di <span class="text-primary">Antrian Online</span></h1>
            <p class="lead mb-4 text-secondary">Mal Pelayanan Publik Kabupaten Rembang</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="<?= site_url('auth/login') ?>" class="btn btn-primary btn-lg px-4 shadow-sm"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a>
                <a href="<?= site_url('auth/register') ?>" class="btn btn-success btn-lg px-4 shadow-sm"><i class="bi bi-person-plus me-2"></i>Daftar</a>
            </div>
        </div>
    </section>

    <!-- FITUR UTAMA -->
    <section class="features py-5 text-center">
        <div class="container">
            <h2 class="fw-bold text-primary mb-3">Pelayanan Publik Lebih Mudah</h2>
            <p class="text-muted mb-5">Daftar dan ambil nomor antrian secara online tanpa harus menunggu lama di kantor.</p>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 p-3 feature-card">
                        <div class="icon-circle bg-primary mb-3">
                            <i class="bi bi-pencil-square text-white fs-3"></i>
                        </div>
                        <h5 class="fw-semibold">Daftar Online</h5>
                        <p class="text-muted">Isi data diri dan pilih jenis layanan yang ingin diakses dengan mudah.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 p-3 feature-card">
                        <div class="icon-circle bg-success mb-3">
                            <i class="bi bi-clock-history text-white fs-3"></i>
                        </div>
                        <h5 class="fw-semibold">Nomor Antrian Otomatis</h5>
                        <p class="text-muted">Nomor antrian dikirim otomatis dan bisa dipantau secara real-time.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 p-3 feature-card">
                        <div class="icon-circle bg-info mb-3">
                            <i class="bi bi-calendar-check text-white fs-3"></i>
                        </div>
                        <h5 class="fw-semibold">Datang Sesuai Jadwal</h5>
                        <p class="text-muted">Hindari antre panjang dengan jadwal pelayanan yang teratur.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- INSTAGRAM -->
    <section class="instagram-feed py-5 bg-light text-center">
        <div class="container">
            <h2 class="fw-bold text-primary mb-3">Kegiatan Terbaru Kami</h2>
            <p class="text-muted mb-4">Ikuti aktivitas dan informasi terbaru dari DPMPTSP Kabupaten Rembang melalui Instagram.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DMNgCqKTX2k/" data-instgrm-version="14"></blockquote>
                <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DLJ1DQXPj7F/" data-instgrm-version="14"></blockquote>
                <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/DKgxcZkyyO8/" data-instgrm-version="14"></blockquote>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer py-4 text-center text-white">
        <div class="container">
            <p class="mb-1">© <?= date('Y') ?> DPMPTSP Kabupaten Rembang</p>
            <small>Pelayanan Publik • Antrian Online • MPP Rembang</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>