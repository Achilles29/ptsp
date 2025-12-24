<!DOCTYPE html>
<html>

<head>
    <title>QR Code Antrian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>

    <style>
        body {
            text-align: center;
            padding-top: 40px;
            font-family: Arial
        }

        #qrcode {
            width: 260px;
            height: 260px;
            margin: auto;
        }
    </style>
</head>

<body>

    <h3>QR Check-In</h3>
    <p>Tunjukkan barcode ini ke kamera check-in</p>

    <div id="qrcode"></div>

    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "<?= $url ?>",
            width: 260,
            height: 260
        });
    </script>

</body>

</html>