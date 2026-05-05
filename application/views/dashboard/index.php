<div class="container-fluid px-4 mt-4">
    <div class="portal-page">
        <section class="portal-page-intro">
            <div>
                <div class="portal-page-eyebrow"><i class="ri ri-information-line"></i> Modul Nonaktif</div>
                <h1 class="portal-page-title">Akun ini tidak lagi memakai antarmuka Customer Service.</h1>
                <p class="portal-page-subtitle">
                    Data lama tetap dipertahankan di database, tetapi menu chat dan dashboard CS sudah disembunyikan dari UI aplikasi.
                </p>
            </div>
        </section>

        <div class="card portal-section-card">
            <div class="card-body">
                <h5 class="portal-section-title mb-2">Informasi Akun</h5>
                <p class="portal-card-note mb-3">
                    Anda login sebagai <strong><?= html_escape($user['nama_lengkap'] ?? 'Pengguna') ?></strong>.
                    Jika akun ini perlu dialihkan menjadi admin layanan atau masyarakat, perubahan role dapat dilakukan dari manajemen user superadmin.
                </p>
                <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-secondary">
                    <i class="ri ri-logout-box-line me-1"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>
