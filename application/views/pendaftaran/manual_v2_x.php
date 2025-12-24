<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?> – Mode X</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>

    <style>
        /* ==============================
   MODE X — DARK GLASS STYLE
================================= */

        body {
            margin: 0;
            background: linear-gradient(135deg, #1e1e1e, #2b2b2b);
            color: #fff;
            font-family: "Poppins", sans-serif;
            min-height: 100vh;
        }

        /* Title */
        .page-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #ffcf5c;
            text-shadow: 0px 2px 6px rgba(255, 255, 255, 0.18);
        }

        /* QR Box */
        .qr-container {
            width: 280px;
            margin: auto;
            padding: 20px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }

        /* Instansi Card */
        .instansi-card {
            padding: 25px 15px;
            border-radius: 22px;
            text-align: center;
            cursor: pointer;

            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);

            transition: 0.25s ease-in-out;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.4);
        }

        .instansi-card:hover {
            transform: translateY(-5px) scale(1.03);
            border-color: #ffcf5c;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.6);
        }

        .instansi-card.active {
            border-color: #ffcf5c !important;
            box-shadow: 0 10px 24px rgba(255, 207, 92, 0.4);
            transform: scale(1.05);
        }

        .instansi-icon {
            font-size: 40px;
            color: #ffcf5c;
        }

        .instansi-name {
            margin-top: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
        }

        /* Modal Style */
        .modal-content {
            background: #1c1c1c;
            color: white;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-layanan {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 14px;
            color: #ffcf5c;
            font-weight: 600;
            padding: 12px;
            transition: .2s;
        }

        .btn-layanan:hover {
            background: #ffcf5c;
            color: #000;
            transform: translateY(-2px);
        }

        /* Ripple effect */
        .instansi-card {
            position: relative;
            overflow: hidden;
        }

        .instansi-card:active::after {
            content: "";
            position: absolute;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: ripple .4s linear;
        }

        @keyframes ripple {
            from {
                transform: translate(-50%, -50%) scale(0);
                opacity: 1;
            }

            to {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0;
            }
        }
    </style>
</head>

<body>

    <div class="container py-4">

        <!-- QR CHECK-IN -->
        <div class="text-center mb-4">
            <h4 class="page-title mb-3"><i class="bi bi-qr-code"></i> QR Check-In</h4>

            <div class="qr-container">
                <div id="qrcode"></div>
            </div>

            <p class="mt-3 text-light opacity-75">
                Pengunjung online cukup scan QR ini dari HP mereka.
            </p>
        </div>

        <!-- INSTANSI LIST -->
        <h4 class="page-title mb-3"><i class="bi bi-building"></i> Pilih Instansi</h4>

        <div class="row g-3">
            <?php foreach ($instansi as $i): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="instansi-card" onclick="pilihInstansi(<?= $i->id ?>, '<?= $i->nama_instansi ?>', this)">
                        <i class="bi bi-bank instansi-icon"></i>
                        <div class="instansi-name"><?= $i->nama_instansi ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- MODAL LAYANAN -->
    <div class="modal fade" id="modalLayanan" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="layananTitle"></h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="layananList">
                    <p class='text-center opacity-75'>Memuat layanan...</p>
                </div>

            </div>
        </div>
    </div>

    <script>
        // QR STATIC
        new QRCode(document.getElementById("qrcode"), {
            text: "<?= site_url('pendaftaran/checkin') ?>",
            width: 240,
            height: 240,
            colorDark: "#fff",
            colorLight: "transparent"
        });

        // PILIH INSTANSI
        function pilihInstansi(id, nama, el) {

            $(".instansi-card").removeClass("active");
            $(el).addClass("active");

            $("#layananTitle").text("Pilih Layanan – " + nama);
            $("#modalLayanan").modal("show");

            $.getJSON("<?= site_url('pendaftaran/layanan_by_instansi/') ?>" + id, function(data) {
                let html = "";

                data.forEach(l => {
                    html += `
                <button class="btn btn-layanan w-100 my-2"
                        onclick="daftar(${l.id})">
                    ${l.nama_layanan}
                </button>
            `;
                });

                $("#layananList").html(html);
            });
        }

        // GENERATE ANTRIAN WALK-IN
        function daftar(layanan_id) {
            $.post("<?= site_url('pendaftaran/generate_antrian_v2') ?>", {
                    layanan_id: layanan_id
                },
                function(res) {

                    let r = JSON.parse(res);

                    Swal.fire({
                        icon: 'success',
                        title: 'Nomor Antrian',
                        html: `<h1 class="fw-bold text-warning">${r.nomor}</h1>`,
                        confirmButtonColor: "#ffcf5c",
                        background: "#1e1e1e",
                        color: "#fff"
                    });
                }
            );
        }
    </script>

</body>

</html>