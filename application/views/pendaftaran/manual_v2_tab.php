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
            border-radius: 12px;
            padding: 12px 10px;
            width: 100%;
            background: #ffffff;
            border: 1px solid #ddd;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .instansi-card:hover,
        .instansi-card.active {
            transform: scale(1.03);
            border-color: #a00037;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .instansi-name {
            font-weight: 700;
            font-size: 0.84rem;
            line-height: 1.2;
            color: #a00037;
            margin-top: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-layanan {
            background: #a00037;
            color: #fff;
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 0.9rem;
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

        .tab-hero {
            background: linear-gradient(90deg, #a00037, #c2185b);
            color: #fff;
            border-radius: 12px;
            padding: 9px 14px;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .welcome-text {
            font-size: 0.92rem;
            color: #7a003c;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .tab-hero-sub {
            margin-top: 4px;
            margin-bottom: 0;
            opacity: 0.92;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .qr-box {
            width: 260px;
            margin: auto;
            padding: 15px;
            background: #fff;
            border-radius: 16px;
            border: 4px solid #198754;
        }

        .nav-tabs .nav-link {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.9rem;
            padding: 8px 12px;
        }

        .nav-tabs .nav-link.active {
            color: #7a003c;
        }

        .instansi-title {
            font-size: 1.1rem;
        }

        .instansi-subtitle {
            font-size: 0.84rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .instansi-icon {
            font-size: 1.65rem;
        }

        @media (max-width: 992px) {
            .instansi-card {
                min-height: 104px;
                padding: 10px 8px;
            }

            .instansi-name {
                font-size: 0.78rem;
            }
        }
    </style>
</head>

<body>

    <div class="container py-3">

        <p class="welcome-text mb-2">Selamat datang di Frontdesk Mal Pelayanan Publik.</p>

        <div class="tab-hero mb-3">
            <i class="bi bi-people-fill me-2"></i> Frontdesk Antrian
            <p class="tab-hero-sub">Pilih instansi, pilih layanan, nomor antrian langsung tercetak.</p>
        </div>

        <!-- TABS -->
        <ul class="nav nav-tabs mb-2" id="frontdeskTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-walkin" data-bs-toggle="tab" data-bs-target="#pane-walkin" type="button" role="tab">
                    <i class="bi bi-building me-1"></i> Walk-In
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-checkin" data-bs-toggle="tab" data-bs-target="#pane-checkin" type="button" role="tab">
                    <i class="bi bi-qr-code me-1"></i> Check-In Online
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- TAB WALK-IN -->
            <div class="tab-pane fade show active" id="pane-walkin" role="tabpanel">
                <h4 class="fw-bold mb-2 text-maroon instansi-title">
                    <i class="bi bi-building me-2"></i> Pilih Instansi
                </h4>
                <p class="instansi-subtitle">Sentuh kartu instansi di bawah ini untuk melanjutkan pendaftaran walk-in.</p>

                <div class="row g-2 justify-content-center">
                    <?php foreach ($instansi as $i): ?>
                        <div class="col-6 col-md-3 col-lg-2 d-flex">
                            <div class="instansi-card text-center"
                                onclick='pilihInstansi(<?= (int) $i->id ?>, <?= json_encode($i->nama_instansi) ?>, this)'>

                                <i class="bi bi-bank instansi-icon text-maroon"></i>
                                <div class="instansi-name"><?= $i->nama_instansi ?></div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- TAB CHECK-IN -->
            <div class="tab-pane fade" id="pane-checkin" role="tabpanel">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white fw-bold">
                        <i class="bi bi-qr-code me-2"></i> QR Check-In (Pengunjung Online)
                    </div>

                    <div class="card-body text-center">
                        <div id="qrcode" class="qr-box"></div>
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
            </div>
        </div>

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
        const PRINT_SERVER_URL = "http://127.0.0.1:9100/print";

        // QR CODE UNTUK CHECK-IN ONLINE
        new QRCode(document.getElementById("qrcode"), {
            text: "<?= site_url('checkin') ?>",
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
                                onclick="daftar(${l.id}, '${l.nama_layanan.replace(/'/g, "\\'")}')">
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
        async function daftar(layanan_id, layanan_nama) {

            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.post("<?= site_url('pendaftaran/generate_antrian_v2') ?>", {
                    layanan_id: layanan_id
                },
                async function(res) {

                    let r = JSON.parse(res);

                    if (!r.success) {
                        Swal.fire("Gagal", r.message || "Tidak bisa membuat antrian", "error");
                        return;
                    }

                    const payload = {
                        nomor: r.nomor,
                        layanan: layanan_nama
                    };

                    try {
                        const resp = await fetch(PRINT_SERVER_URL, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await resp.json();

                        if (data && data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Nomor antrian sudah dicetak',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            $("#modalLayanan").modal("hide");
                            $(".instansi-card").removeClass("active");
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Cetak',
                                text: data && data.message ? data.message : 'Printer tidak merespons',
                                confirmButtonText: 'Tutup'
                            });
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Cetak',
                            text: 'Tidak bisa terhubung ke server printer',
                            confirmButtonText: 'Tutup'
                        });
                    }
                }
            );
        }

        function loadManual() {
            $("#listManual").load("<?= site_url('pendaftaran/list_antrian_manual_today') ?>");
        }
        loadManual();
        setInterval(loadManual, 5000);

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
