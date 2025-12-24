<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Check-In</title>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #000;
        }

        #reader {
            width: 100vw;
            height: 100vh;
            background: #000;
        }

        .back-btn {
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 9999;
            padding: 10px 16px;
            background: white;
            border-radius: 8px;
            border: none;
            font-size: 15px;
        }
    </style>
</head>

<body>

    <button class="back-btn" onclick="history.back()">← Kembali</button>

    <div id="reader"></div>

    <script>
        let scanner = new Html5Qrcode("reader");

        Html5Qrcode.getCameras().then(devices => {

            // PILIH KAMERA BELAKANG
            let backCam = devices.find(d =>
                d.label.toLowerCase().includes("back") ||
                d.label.toLowerCase().includes("rear") ||
                d.label.toLowerCase().includes("belakang")
            );

            if (!backCam) backCam = devices[devices.length - 1];

            // KONFIGURASI SAFARI-COMPATIBLE
            const config = {
                fps: 15,
                qrbox: 350,
                aspectRatio: 1.777,
                videoConstraints: {
                    deviceId: backCam.id,
                    facingMode: {
                        exact: "environment"
                    },
                    width: {
                        ideal: 1920
                    },
                    height: {
                        ideal: 1080
                    },
                    focusMode: "continuous"
                }
            };

            scanner.start(
                backCam.id,
                config,
                decodedText => window.location.href = "<?= site_url('masyarakat/checkin_user/' . $antrian_id) ?>",
                error => {}
            );

        }).catch(err => {
            alert("Gagal membuka kamera. Izinkan akses kamera.");
            console.error(err);
        });
    </script>

</body>

</html>