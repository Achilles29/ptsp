<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Scan QR Check-In</title>

  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#000000">

  <!-- html5-qrcode (STABIL) -->
  <script src="https://unpkg.com/html5-qrcode"></script>

  <style>
    html, body {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      background: #000;
      overflow: hidden;
      font-family: system-ui, -apple-system;
    }

    #reader {
      position: fixed;
      inset: 0;
      width: 100vw;
      height: 100vh;
      background: #000;
    }

    /* header */
    .top {
      position: fixed;
      top: env(safe-area-inset-top);
      left: 0;
      right: 0;
      z-index: 10;
      padding: 12px;
      text-align: center;
      color: #fff;
      background: linear-gradient(to bottom, rgba(0,0,0,.6), transparent);
    }

    /* frame */
    .frame {
      position: fixed;
      inset: 0;
      pointer-events: none;
      z-index: 5;
      display: flex;
      align-items: center;
      justify-content: center;
    }

.box {
  width: 300px;
  height: 300px;
  border: 3px solid #00ff99;
  border-radius: 18px;
}


    /* back */
    .back {
      position: fixed;
      bottom: env(safe-area-inset-bottom);
      left: 0;
      right: 0;
      z-index: 10;
      text-align: center;
      padding: 14px;
    }

    .back a {
      color: #fff;
      text-decoration: none;
      background: rgba(0,0,0,.6);
      padding: 10px 18px;
      border-radius: 999px;
      font-size: 14px;
    }
    #reader video {
    object-fit: cover !important;
    width: 100vw !important;
    height: 100vh !important;
    }
#reader video {
  width: 100vw !important;
  height: 100vh !important;
  object-fit: cover !important;
}

  </style>
</head>

<body>

<div class="top">
  <strong>Scan QR Check-In</strong><br>
  <small>Arahkan kamera ke QR Code</small>
</div>

<div id="reader"></div>

<div class="frame">
  <div class="box"></div>
</div>

<div class="back">
  <a href="<?= base_url('masyarakat/antrian_saya') ?>">← Kembali</a>
</div>

<script>
let scanner = new Html5Qrcode("reader");
let scanned = false;

Html5Qrcode.getCameras().then(devices => {

  if (!devices.length) {
    alert("Kamera tidak ditemukan");
    return;
  }

  // Pilih kamera belakang (biasanya index terakhir)
  let backCam = devices[devices.length - 1];

  const config = {
    fps: 10,
    experimentalFeatures: {
      useBarCodeDetectorIfSupported: true
    },
    videoConstraints: {
      facingMode: "environment",

      // KUNCI RESOLUSI TINGGI
      width: { ideal: 1920 },
      height: { ideal: 1080 },

      // PENTING: MATIKAN DIGITAL ZOOM
      advanced: [
        { zoom: 1.0 }
      ]
    }
  };

  scanner.start(
    backCam.id,
    config,
    (decodedText) => {
      if (scanned) return;
      scanned = true;

      if (navigator.vibrate) navigator.vibrate(200);

      window.location.href =
        "<?= site_url('masyarakat/checkin_user/' . $antrian_id) ?>";
    },
    () => {}
  );

}).catch(err => {
  alert("Gagal membuka kamera. Pastikan izin kamera diaktifkan.");
  console.error(err);
});
</script>


</body>
</html>
