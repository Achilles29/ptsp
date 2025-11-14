<div class="container-fluid px-4 mt-4">
    <h4 class="text-maroon"><i class="fas fa-home me-2"></i>Dashboard Masyarakat</h4>
    <hr>

    <!-- Welcome Banner -->
    <div class="alert alert-primary shadow-sm d-flex justify-content-between align-items-center">
        <div>
            👋 Selamat datang, <strong><?= $this->session->userdata('nama') ?></strong>!
            <div class="text-muted small">Akses layanan publik lebih mudah dan cepat.</div>
        </div>
        <a href="<?= base_url('masyarakat/daftar_antrian') ?>" class="btn btn-sm btn-success">
            <i class="fas fa-plus-circle me-1"></i> Daftar Antrian Baru
        </a>
    </div>

    <!-- Ringkasan Kartu -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-start border-success shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted"><i class="fas fa-calendar-check me-1 text-success"></i> Antrian Aktif</h6>
                    <h3 class="text-success fw-bold">2</h3>
                    <div class="small">IMB, NIB</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-primary shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted"><i class="fas fa-history me-1 text-primary"></i> Riwayat Antrian</h6>
                    <h3 class="text-primary fw-bold">5</h3>
                    <div class="small">Layanan sebelumnya</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-warning shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted"><i class="fas fa-comment-dots me-1 text-warning"></i> Pesan ke CS</h6>
                    <h3 class="text-warning fw-bold">1</h3>
                    <div class="small">Menunggu balasan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi Cepat -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3"><i class="fas fa-bolt text-danger me-2"></i> Aksi Cepat</h5>
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <a href="<?= base_url('masyarakat/daftar_antrian') ?>" class="btn btn-outline-primary w-100">
                        <i class="fas fa-plus-square fa-lg mb-1"></i><br>
                        Daftar Antrian
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="<?= base_url('masyarakat/riwayat_antrian') ?>" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-list fa-lg mb-1"></i><br>
                        Lihat Riwayat
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="<?= base_url('masyarakat/chat') ?>" class="btn btn-outline-warning w-100">
                        <i class="fas fa-comment-alt fa-lg mb-1"></i><br>
                        Chat CS
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="<?= base_url('masyarakat/profil') ?>" class="btn btn-outline-dark w-100">
                        <i class="fas fa-user fa-lg mb-1"></i><br>
                        Profil Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>