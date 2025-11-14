<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Walk-in & QR Display</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <style>
        body {
            background: #f7fafb;
            font-family: 'Poppins', sans-serif;
        }

        .section-title {
            font-weight: 700;
            font-size: 1.3rem;
            color: #fff;
            background: linear-gradient(90deg, #a00037, #c2185b);
            padding: 10px 15px;
            border-radius: 8px 8px 0 0;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-gradient {
            background: linear-gradient(90deg, #c2185b, #a00037);
            color: white;
            font-weight: 600;
            border: none;
        }

        .btn-gradient:hover {
            filter: brightness(1.1);
            transform: scale(1.03);
        }

        .qr-box {
            width: 300px;
            height: 300px;
            margin: auto;
            border: 4px solid #198754;
            border-radius: 16px;
            background: #fff;
            padding: 15px;
        }
    </style>
</head>

<body>

    <div class="container py-4">

        <!-- 🖨️ BAGIAN WALK-IN -->
        <div class="card mb-5">
            <div class="section-title">
                <i class="bi bi-person-plus-fill me-2"></i> Pendaftaran Walk-in (Cetak Nomor Antrian)
            </div>
            <div class="card-body bg-white">
                <form id="formCetak" class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-bank me-1"></i> Instansi
                        </label>
                        <select name="instansi_id" id="instansi" class="form-select" required>
                            <option value="">Pilih Instansi</option>
                            <?php foreach ($instansi as $i): ?>
                                <option value="<?= $i->id ?>"><?= $i->nama_instansi ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-list-check me-1"></i> Jenis Layanan
                        </label>
                        <select name="layanan_id" id="layanan" class="form-select" required>
                            <option value="">Pilih Jenis Layanan</option>
                        </select>
                    </div>

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-gradient w-100 py-2">
                            <i class="bi bi-printer-fill me-2"></i> Cetak Nomor Antrian
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 📱 BAGIAN BARCODE DISPLAY -->
        <div class="card text-center">
            <div class="section-title bg-success" style="background:linear-gradient(90deg,#00796b,#009688);">
                <i class="bi bi-qr-code me-2"></i> Scan Barcode untuk Check-In
            </div>
            <div class="card-body bg-white">
                <div id="qrcode" class="qr-box mb-3"></div>
                <p class="fw-semibold text-muted">Silakan scan barcode ini untuk melakukan pendaftaran ulang.</p>
            </div>
        </div>

    </div>

    <script>
        $(function() {

            // Load layanan dinamis
            $('#instansi').change(function() {
                $.getJSON('<?= base_url("ajax/get_layanan_by_instansi/") ?>' + $(this).val(), function(data) {
                    let opt = '<option value="">Pilih Jenis Layanan</option>';
                    data.forEach(d => opt += `<option value="${d.id}">${d.nama_layanan}</option>`);
                    $('#layanan').html(opt);
                });
            });

            // Cetak antrian dan insert data
            $('#formCetak').on('submit', function(e) {
                e.preventDefault();
                $.post('<?= base_url("pendaftaran/generate_antrian") ?>', $(this).serialize(), function(res) {
                    const r = JSON.parse(res);
                    if (r.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Nomor Antrian Dibuat',
                            html: `<h1 class="text-primary fw-bold">${r.nomor}</h1><p>Silakan tunggu panggilan di layar utama.</p>`,
                            confirmButtonText: 'Cetak Sekarang',
                            confirmButtonColor: '#a00037'
                        }).then(() => {
                            window.open('<?= base_url("antrian/cetak/") ?>' + r.nomor, '_blank');
                            // Buat QR baru untuk pengunjung scan
                            $('#qrcode').empty();
                            new QRCode(document.getElementById("qrcode"), {
                                text: "<?= base_url('antrian/checkin/') ?>" + r.id,
                                width: 270,
                                height: 270,
                                colorDark: "#000000",
                                colorLight: "#ffffff",
                                correctLevel: QRCode.CorrectLevel.H
                            });
                        });
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat membuat antrian.', 'error');
                    }
                });
            });

        });
    </script>

</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
<script>
    $(function() {

        // 🔹 Load layanan dinamis
        $('#instansi').change(function() {
            const id = $(this).val();
            if (!id) return;
            $.getJSON('<?= site_url("pendaftaran/get_layanan_by_instansi/") ?>' + id, function(data) {
                let opt = '<option value="">Pilih Jenis Layanan</option>';
                data.forEach(d => opt += `<option value="${d.id}">${d.nama_layanan}</option>`);
                $('#layanan').html(opt);
            });
        });

        // 🔹 Generate dan cetak antrian
        $('#formCetak').on('submit', function(e) {
            e.preventDefault();
            $.post('<?= site_url("pendaftaran/generate_antrian") ?>', $(this).serialize(), function(res) {
                const r = JSON.parse(res);
                if (r.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Nomor Antrian Dibuat',
                        html: `<h1 class="text-primary fw-bold">${r.nomor}</h1><p>Silakan tunggu panggilan di layar utama.</p>`,
                        confirmButtonText: 'Cetak Sekarang',
                        confirmButtonColor: '#a00037'
                    }).then(() => {
                        window.open('<?= site_url("antrian/cetak/") ?>' + r.nomor, '_blank');

                        // Buat QRCode baru (reset dulu)
                        $('#qrcode').empty();
                        new QRCode(document.getElementById("qrcode"), {
                            text: "<?= site_url('antrian/checkin/') ?>" + r.id,
                            width: 260,
                            height: 260,
                            colorDark: "#000000",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.H
                        });
                    });
                } else {
                    Swal.fire('Gagal', 'Terjadi kesalahan saat membuat antrian.', 'error');
                }
            });
        });

    });
</script>