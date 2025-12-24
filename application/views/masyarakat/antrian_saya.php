<h4 class="text-maroon mb-4 fw-bold">
    <i class="bi bi-ticket-detailed me-2"></i> Antrian Saya
</h4>

<?php if (empty($antrian)): ?>

    <div class="alert alert-warning text-center py-4">
        <i class="bi bi-info-circle fs-3 mb-2"></i><br>
        Anda belum memiliki antrian aktif.
    </div>

<?php else: ?>
    <?php foreach ($antrian as $a): ?>

        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="fw-bold text-primary m-0"><?= $a->nomor_antrian ?></h3>
                    <span class="badge <?= $a->hadir ? 'bg-success' : 'bg-secondary' ?> px-3 py-2">
                        <?= $a->hadir ? 'Sudah Check-In' : 'Belum Check-In' ?>
                    </span>
                </div>

                <hr>

                <p class="mb-1">
                    <i class="bi bi-building text-maroon"></i>
                    <b><?= $a->nama_instansi ?></b><br>

                    <i class="bi bi-list-ul text-maroon"></i>
                    <?= $a->nama_layanan ?><br>

                    <i class="bi bi-calendar2-week text-maroon"></i>
                    <?= $a->tanggal ?>
                </p>

                <div class="mt-3 d-flex gap-2">

                    <!-- HAPUS tombol checkin manual -->

                    <?php if (!$a->hadir): ?>
                        <button class="btn btn-outline-primary w-100"
                            onclick="window.location='<?= site_url("masyarakat/scan_qr/$a->id") ?>'">
                            <i class="bi bi-qr-code-scan"></i> Scan QR
                        </button>
                    <?php else: ?>
                        <div class="alert alert-success w-100 text-center mb-0 py-2">
                            <i class="bi bi-check2-circle"></i> Sudah Check-In
                        </div>
                    <?php endif; ?>


                </div>

            </div>
        </div>

    <?php endforeach; ?>
<?php endif; ?>