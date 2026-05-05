<div class="container-fluid px-4 mt-4">
    <div class="portal-page">
        <section class="portal-page-intro">
            <div>
                <div class="portal-page-eyebrow"><i class="ri ri-toggle-line"></i> Kontrol Operasional</div>
                <h1 class="portal-page-title"><?= html_escape($title) ?></h1>
                <p class="portal-page-subtitle">
                    Gunakan halaman ini untuk mengatur mode status layanan otomatis atau memaksa buka dan tutup layanan pada instansi tertentu.
                </p>
            </div>
        </section>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>

        <section class="card portal-section-card">
            <div class="card-header">
                <h5 class="portal-section-title">Form Pengaturan Status</h5>
                <div class="portal-card-note">Mode paksa tutup akan langsung membatalkan antrian aktif sesuai logika operasional terbaru.</div>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('superadmin/update_status_layanan') ?>" id="formLayanan">
                    <div class="mb-3">
                        <label class="fw-bold">Mode Aksi</label>
                        <select name="mode" id="mode" class="form-control">
                            <option value="single">Tutup / buka 1 layanan</option>
                            <option value="all">Tutup semua layanan</option>
                        </select>
                    </div>

                    <div class="mb-3" id="instansiBox">
                        <label class="fw-bold">Instansi</label>
                        <select name="instansi_id" class="form-control">
                            <option value="">Pilih instansi</option>
                            <?php foreach ($instansi_list as $i): ?>
                                <option value="<?= $i->id ?>">
                                    <?= $i->nama_instansi ?> (<?= strtoupper($i->status_layanan) ?>)
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="mb-3" id="statusBox">
                        <label class="fw-bold">Mode Status Layanan</label>
                        <select name="status_layanan_mode" class="form-control">
                            <option value="otomatis">Otomatis sesuai jam layanan</option>
                            <option value="buka">Paksa buka</option>
                            <option value="tutup">Paksa tutup</option>
                        </select>
                    </div>

                    <button class="btn btn-primary">
                        <i class="ri ri-save-line me-1"></i> Simpan Pengaturan
                    </button>
                </form>
            </div>
        </section>
    </div>
</div>

<script>
document.getElementById('mode').addEventListener('change', function () {
    const single = this.value === 'single';
    document.getElementById('instansiBox').style.display = single ? 'block' : 'none';
    document.getElementById('statusBox').style.display   = single ? 'block' : 'none';
});
</script>
