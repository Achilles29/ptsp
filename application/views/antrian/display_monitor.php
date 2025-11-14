<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #e8fff8, #f8ffff);
            margin: 0;
            overflow: hidden;
        }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(90deg, #00796b, #009688);
            color: #fff;
            padding: 8px 30px;
        }

        .header-left h6 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
        }

        .header-right {
            text-align: right;
        }

        .header-right h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .main-content {
            display: flex;
            height: calc(100vh - 180px);
            padding: 20px 30px 0 30px;
            gap: 25px;
        }

        .left-panel {
            flex: 1;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }



        .right-panel {
            flex: 1.5;
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .video-frame {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .overlay-top {
            position: absolute;
            top: 0;
            width: 100%;
            text-align: center;
            background: rgba(0, 121, 107, 0.85);
            color: #fff;
            padding: 6px 0;
        }

        .overlay-top h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.4rem;
        }


        .loket-card {
            background: #fff;
            border-radius: 10px;
            width: 180px;
            text-align: center;
            padding: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .loket-card h6 {
            margin: 0;
            color: #009688;
            font-weight: 700;
            font-size: 1.1rem;
            border-bottom: 2px solid #009688;
            padding-bottom: 3px;
        }

        .loket-card span {
            font-size: 1.8rem;
            font-weight: 800;
            color: #004d40;
        }


        /* Proporsi kiri: judul loket makin tegas, nama instansi auto-wrap 2 baris */
        .loket-now {
            font-size: 5.6vw;
            font-weight: 900;
            color: #00796b;
            letter-spacing: 2px;
            margin-bottom: 12px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, .15)
        }

        .left-panel h3 {
            color: #004d40;
            font-weight: 700;
            margin: 0 0 8px 0;
            font-size: clamp(1rem, 1.6vw, 1.5rem);
            /* 🔹 sedikit lebih kecil */
            line-height: 1.15;
            /* 🔹 rapatkan antarbaris */
            text-align: center;
            word-wrap: break-word;
            max-width: 90%;
        }

        .left-panel span {
            font-size: 1.2rem;
            color: #555;
            margin-top: 5px;
            display: inline-block;
        }


        .left-panel h1 {
            font-size: 13.5vw;
            color: #009688;
            font-weight: 800;
            line-height: 1;
            margin: 0
        }

        /* Jarak bawah konten agar tidak ketiban slider */
        .main-content {
            height: calc(100vh - 200px);
            padding: 20px 30px 0 30px;
            gap: 25px
        }

        @media(max-width:991px) {
            .main-content {
                height: auto;
                padding-bottom: 180px
            }
        }

        /* --- SLIDER FIX --- */
        .slider-container {
            position: fixed;
            left: 0;
            width: 100%;
            bottom: 18px;
            /* dinaikkan supaya aman */
            background: #004d40;
            color: #fff;
            border-top: 6px solid #00bfa5;
            overflow: hidden;
            height: 170px;
            /* lebih tinggi agar TIDAK terpotong */
            z-index: 9999;
            /* pastikan di atas elemen lain */
            box-shadow: 0 -6px 12px rgba(0, 0, 0, .25)
        }

        .slider-track {
            display: flex;
            align-items: center;
            gap: 3rem;
            padding: 18px 2rem;
            width: max-content;
            will-change: transform
        }

        .slider-card {
            background: #fff;
            color: #004d40;
            border-radius: 18px;
            width: 320px;
            height: 135px;
            /* kotak lebih besar */
            padding: 12px 14px;
            text-align: center;
            flex-shrink: 0;
            box-shadow: 0 6px 12px rgba(0, 0, 0, .25);
            display: flex;
            flex-direction: column;
            justify-content: center
        }

        .slider-card h6 {
            margin: 0;
            color: #009688;
            font-weight: 800;
            font-size: 1.05rem;
            border-bottom: 2px solid #009688;
            padding-bottom: 4px
        }

        .slider-card h5 {
            margin: 8px 0 4px;
            color: #009688;
            font-weight: 900;
            font-size: 2.6rem;
            line-height: 1
        }

        /* ✅ nama instansi FULL: wrap 2–3 baris, tanpa dipotong */
        .slider-card small {
            font-weight: 700;
            font-size: 1rem;
            line-height: 1.15;
            white-space: normal;
            word-break: break-word;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: visible;
            max-height: 3.45em;
            /* 3 baris */
        }

        /* Bar loket tidak tertutup slider: beri margin bawah */
        .loket-bar {
            margin-bottom: 190px
        }

        /* Responsive kecil */
        @media(max-width:991px) {
            .loket-now {
                font-size: 8vw
            }

            .slider-container {
                height: 190px
            }

            .slider-card {
                width: 280px;
                height: 145px
            }
        }
    </style>
</head>

<body>
    <div class="header-bar">
        <div class="header-left">
            <h6 id="currentDate">—</h6>
        </div>
        <div class="header-right">
            <h5>MAL PELAYANAN PUBLIK<br>REMBANG<br><small>REMBANG – JAWA TENGAH</small></h5>
        </div>
    </div>

    <div class="main-content">
        <div class="left-panel">
            <div id="loketNow" class="loket-now">LOKET —</div>
            <h3 id="instansiName">—</h3>
            <h1 id="currentNumber">—</h1>
            <span>Sedang Dilayani</span>
        </div>

        <?php
        $video = $video ?? (object)['source_type' => 'file', 'file_path' => null, 'youtube_url' => null, 'is_muted' => 1];
        function getYoutubeId($url)
        {
            if (preg_match('/(youtu\.be\/|v=)([^&]+)/', $url, $m)) return $m[2];
            return null;
        }
        $ytId = $video->youtube_url ? getYoutubeId($video->youtube_url) : null;
        $muted_attr = (!isset($video->is_muted) || $video->is_muted == 1) ? 'muted' : '';
        $mute_param = (!isset($video->is_muted) || $video->is_muted == 1) ? '&mute=1' : '';
        ?>

        <div class="right-panel">
            <div class="overlay-top">
                <h5>INFORMASI LAYANAN VIDEO</h5>
            </div>
            <?php if ($video->source_type === 'youtube' && $ytId): ?>
                <iframe class="video-frame"
                    src="https://www.youtube.com/embed/<?= $ytId ?>?autoplay=1&loop=1&playlist=<?= $ytId ?><?= $mute_param ?>"
                    frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            <?php elseif (!empty($video->file_path) && file_exists(FCPATH . $video->file_path)): ?>
                <video autoplay loop <?= $muted_attr ?> playsinline class="video-frame">
                    <source src="<?= base_url($video->file_path) ?>" type="video/mp4">
                </video>
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center text-white bg-dark h-100">
                    <h3>Belum ada video diatur</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="loket-bar" id="loketBar"></div>

    <div class="slider-container">
        <div class="slider-track" id="sliderAntrian"></div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const options = {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            };
            const dateStr = now.toLocaleDateString('id-ID', options);
            const timeStr = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('currentDate').textContent = dateStr + " • " + timeStr;
        }
        setInterval(updateClock, 1000);
        updateClock();

        let lastData = "";
        let animationFrameId;
        let sliderTrack = document.getElementById('sliderAntrian');
        let sliderPos = 0;

        function animateSlider() {
            sliderPos -= 0.3; // 🔹 super halus dan lambat
            if (sliderPos <= -sliderTrack.scrollWidth / 2) {
                sliderPos = 0;
            }
            sliderTrack.style.transform = `translateX(${sliderPos}px)`;
            animationFrameId = requestAnimationFrame(animateSlider);
        }

        async function updateDisplay() {
            try {
                const res = await fetch("<?= site_url('antrian_display/get_data') ?>");
                const data = await res.json();
                const current = data.current || {};
                document.getElementById('instansiName').textContent = current.nama_instansi || '—';
                document.getElementById('currentNumber').textContent = current.nomor_antrian || '—';
                document.getElementById('loketNow').textContent =
                    current.nama_loket ? ('LOKET ' + String(current.nama_loket).toUpperCase()) : 'LOKET —';

                // Bar Loket
                const loketWrap = document.getElementById('loketBar');
                loketWrap.innerHTML = '';
                if (data.slider && data.slider.length > 0) {
                    data.slider.forEach(item => {
                        loketWrap.innerHTML += `
          <div class="loket-card">
            <h6>LOKET ${item.nama_loket||'-'}</h6>
            <span>${item.nomor_antrian}</span>
            <div style="font-size:0.8rem;color:#004d40;font-weight:600;margin-top:2px;">${item.nama_instansi||''}</div>
          </div>`;
                    });
                }

                // Slider bawah
                const json = JSON.stringify(data.slider);
                if (json === lastData) return;
                lastData = json;
                sliderTrack.innerHTML = '';
                if (data.slider && data.slider.length > 0) {
                    let content = '';
                    data.slider.forEach(item => {
                        content += `
          <div class="slider-card">
            <h6>LOKET ${item.nama_loket||'-'}</h6>
            <h5>${item.nomor_antrian}</h5>
            <small>${item.nama_instansi||''}</small>
          </div>`;
                    });
                    sliderTrack.innerHTML = content + content; // loop tanpa putus
                    sliderTrack.style.width = 'max-content';
                    sliderPos = 0;
                    cancelAnimationFrame(animationFrameId);
                    animateSlider();
                } else {
                    sliderTrack.innerHTML = `<div class="fs-5 text-center text-light w-100 py-2">Tidak ada antrian aktif</div>`;
                    cancelAnimationFrame(animationFrameId);
                }
            } catch (e) {
                console.error(e);
            }
        }
        setInterval(updateDisplay, 2000);
        updateDisplay();
    </script>
</body>

</html>