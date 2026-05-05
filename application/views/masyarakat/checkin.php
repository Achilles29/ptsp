<!DOCTYPE html>
<html>

<head>
    <title>Scan Check-In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: black;
        }

        #reader {
            width: 100vw;
            height: 100vh;
        }

        .back-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 9999;
            background: white;
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
        }
    </style>
</head>

<body>

    <button class="back-btn" onclick="history.back()">← Kembali</button>

    <div id="reader"></div>

    <script>
        let scanner = new Html5Qrcode("reader");

        Html5Qrcode.getCameras().then(devices => {

            let backCam = devices.find(
                d => d.label.toLowerCase().includes("back") ||
                d.label.toLowerCase().includes("rear")
            );

            if (!backCam) backCam = devices[devices.length - 1];

            scanner.start(
                backCam.id, {
                    fps: 15,
                    qrbox: {
                        width: 350,
                        height: 350
                    },
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
                },
                decoded => window.location.href = decoded,
                err => {}
            );

        });
    </script>

</body>

</html>