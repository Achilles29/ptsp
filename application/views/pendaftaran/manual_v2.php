<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>

    <style>
        body {
            background: #f5f5f5;
            font-family: Poppins, sans-serif;
        }

        .instansi-card {
            cursor: pointer;
            transition: .2s;
            border-radius: 16px;
            padding: 20px;
            background: #ffffff;
            border: 2px solid #ddd;
        }

        .instansi-card:hover,
        .instansi-card.active {
            transform: scale(1.03);
            border-color: #a00037;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .instansi-name {
            font-weight: 700;
            font-size: 1.1rem;
            color: #a00037;
        }

        .btn-layanan {
            background: #a00037;
            color: #fff;
            border-radius: 8px;
            padding: 12px;
            font-size: 1rem;
            transition: .2s;
        }

        .btn-layanan:hover {
            filter: brightness(1.15);
        }

        .bg-maroon {
            background: #7a003c;
        }

        .text-maroon {
            color: #7a003c;
        }

        .qr-box {
            width: 260px;
            margin: auto;
            padding: 15px;
            background: #fff;
            border-radius: 16px;
            border: 4px solid #198754;
        }

        /* ======== KHUSUS CETAK ======== */
        @media print {

            /* Hilangkan semua elemen selain kartu antrian */
            body * {
                visibility: hidden !important;
                margin: 0;
                padding: 0;
            }

            #ticketArea,
            #ticketArea * {
                visibility: visible !important;
            }

            /* Tempatkan kartu di tengah halaman */
            #ticketArea {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw !important;
                padding-top: 30px;
                text-align: center;
            }

            /* Hilangkan modal layanan */
            .modal,
            .modal-backdrop {
                display: none !important;
            }

            /* paksa background tampil */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                margin: 10mm;
            }
        }
    </style>
</head>

<body>

    <div class="container py-4">

        <!-- QR CHECK-IN -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white fw-bold">
                <i class="bi bi-qr-code me-2"></i> QR Check-In (Pengunjung Online)
            </div>

            <div class="card-body text-center">
                <div id="qrcode"
                    style="width:260px; margin:auto; padding:18px;
                border:6px solid #198754; border-radius:22px;
                background:white;">
                </div>
                <p class="mt-3 text-muted fw-semibold">Scan untuk check-in online</p>
            </div>
        </div>
        <div class="text-center mt-3">
            <button class="btn btn-lg px-4 py-2 fw-bold"
                style="background:linear-gradient(90deg,#a00037,#c2185b);color:white;border-radius:12px;"
                id="btnCheckinManual">
                <i class="bi bi-people-check me-2"></i> Check-In Manual
            </button>
        </div>
        <br>

        <!-- INSTANSI LIST -->
        <h4 class="fw-bold mb-3 text-maroon">
            <i class="bi bi-building me-2"></i> Pilih Instansi
        </h4>

        <div class="row g-3">
            <?php foreach ($instansi as $i): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div
                        class="instansi-card text-center"
                        onclick="pilihInstansi(<?= $i->id ?>,'<?= $i->nama_instansi ?>', this)">

                        <i class="bi bi-bank fs-1 text-maroon"></i>
                        <div class="instansi-name mt-2"><?= $i->nama_instansi ?></div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-maroon text-white fw-bold">
                <i class="bi bi-person-check me-2"></i> Check-In Manual
            </div>

            <div class="card-body" id="listManual">
                <p class="text-muted text-center">Memuat data...</p>
            </div>
        </div> -->


    </div>

    <!-- MODAL PILIH LAYANAN -->
    <div class="modal fade" id="modalLayanan" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">

                <div class="modal-header bg-maroon text-white">
                    <h5 class="modal-title fw-bold" id="layananTitle"></h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="layananList">
                    <p class="text-center text-muted">Memuat layanan...</p>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalManual" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header" style="background:#a00037;color:white;">
                    <h5 class="modal-title"><i class="bi bi-people-check me-2"></i> Check-In Manual</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="listManual"></div>

            </div>
        </div>
    </div>



    <script>
        // QR CODE UNTUK CHECK-IN ONLINE
        new QRCode(document.getElementById("qrcode"), {
            text: "<?= site_url('pendaftaran/checkin') ?>",
            width: 220,
            height: 220,
            colorDark: "#000",
            colorLight: "#fff",
            correctLevel: QRCode.CorrectLevel.H
        });


        // ========================
        // PILIH INSTANSI
        // ========================
        function pilihInstansi(id, nama, el) {

            $(".instansi-card").removeClass("active");
            $(el).addClass("active");

            $("#layananTitle").text("Pilih Layanan — " + nama);
            $("#modalLayanan").modal("show");

            $.getJSON("<?= site_url('pendaftaran/get_layanan_by_instansi/') ?>" + id, function(data) {

                if (!data || data.length === 0) {
                    $("#layananList").html(`
                        <p class="text-center text-muted py-3">Tidak ada layanan untuk instansi ini.</p>
                    `);
                    return;
                }

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


        // ========================
        // GENERATE & CETAK NOMOR
        // ========================
        function daftar(layanan_id) {

            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.post("<?= site_url('pendaftaran/generate_antrian_v2') ?>", {
                    layanan_id: layanan_id
                },
                function(res) {

                    let r = JSON.parse(res);

                    if (!r.success) {
                        Swal.fire("Gagal", "Tidak bisa membuat antrian", "error");
                        return;
                    }

                    Swal.close();
                    Swal.fire({
                        showConfirmButton: true,
                        confirmButtonText: "Print",
                        width: 350,
                        background: "transparent",
                        html: `
        <div id="ticketArea" style="
            width:330px;
            margin:auto;
            background:#fff;
            border-radius:16px;
            padding:25px;
            text-align:center;
            box-shadow:0 4px 14px rgba(0,0,0,0.2);
        ">
            <h5 style="color:#555;font-weight:600;margin-bottom:7px;">
                NOMOR ANTRIAN
            </h5>

            <div style="font-size:64px;font-weight:800;color:#a00037;line-height:1;">
                ${r.nomor}
            </div>

            <p style="margin-top:10px;color:#555;">
                Silakan menunggu panggilan
            </p>

            <hr>

            <p style="font-size:12px;margin:0;color:#777;">
                Dicetak: <?= date('d/m/Y H:i') ?>
            </p>
        </div>
    `
                    }).then(() => {
                        window.print();
                    });


                }
            );
        }

        function loadManual() {
            $("#listManual").load("<?= site_url('pendaftaran/list_antrian_manual_today') ?>");
        }
        loadManual();
        setInterval(loadManual, 5000);

        function openCheckinManual() {
            $("#modalManual").modal("show");
            $("#listManual").html("<p class='text-center text-muted'>Memuat...</p>");

            $.get("<?= site_url('pendaftaran/list_antrian_manual_today') ?>", function(res) {
                $("#listManual").html(res);
            });
        }

        $("#btnCheckinManual").click(function() {
            $("#modalManual").modal("show");
            $("#listManual").html("<p class='text-center text-muted'>Memuat data...</p>");

            $.get("<?= site_url('pendaftaran/list_antrian_manual_today') ?>", function(res) {
                $("#listManual").html(res);
            });
        });
    </script>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>