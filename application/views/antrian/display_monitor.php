<?php
$youtube_embed_id = null;

if (
    isset($video->source_type, $video->youtube_url) &&
    $video->source_type === 'youtube' &&
    !empty($video->youtube_url)
) {
    $youtube_url = trim($video->youtube_url);
    $youtube_parts = parse_url($youtube_url);

    if (!empty($youtube_parts['query'])) {
        parse_str($youtube_parts['query'], $youtube_query);
        if (!empty($youtube_query['v'])) {
            $youtube_embed_id = $youtube_query['v'];
        }
    }

    if (!$youtube_embed_id && !empty($youtube_parts['path'])) {
        $path_segments = array_values(array_filter(explode('/', trim($youtube_parts['path'], '/'))));
        if (!empty($path_segments)) {
            $last_segment = end($path_segments);
            if (in_array($path_segments[0], ['embed', 'shorts', 'live'], true) && !empty($path_segments[1])) {
                $youtube_embed_id = $path_segments[1];
            } elseif (strpos((string) ($youtube_parts['host'] ?? ''), 'youtu.be') !== false) {
                $youtube_embed_id = $last_segment;
            }
        }
    }

    if ($youtube_embed_id && preg_match('/^[A-Za-z0-9_-]{11}$/', $youtube_embed_id) !== 1) {
        $youtube_embed_id = null;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $title ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700;800&display=swap" rel="stylesheet">
<link rel="icon" type="image/x-icon" href="../assets/img/favicon/logo.ico" />

<style>
/* ================= GLOBAL ================= */
body{
  font-family:'Poppins',sans-serif;
  background:linear-gradient(180deg,#f4f7ff,#ffffff);
  margin:0;overflow:hidden
}

/* ================= HEADER ================= */
.header-bar{
  display:flex;justify-content:space-between;align-items:center;
  background:linear-gradient(90deg,#0d47a1,#1e88e5);
  color:#fff;padding:8px 30px
}

.sector-badge{
  display:inline-block;
  margin-top:4px;
  padding:3px 10px;
  border-radius:999px;
  background:rgba(255,255,255,.18);
  font-size:.82rem;
  font-weight:700;
}

/* ================= MAIN ================= */
.main-content{
  display:flex;height:calc(100vh - 200px);
  padding:20px 30px 0;gap:25px
}

.left-panel{
  flex:1;background:#fff;border-radius:16px;
  box-shadow:0 5px 15px rgba(0,0,0,.15);
  display:flex;flex-direction:column;
  align-items:center;justify-content:center;text-align:center
}

.loket-now{
  font-size:5.6vw;font-weight:900;
  color:#0d47a1;letter-spacing:2px
}

.left-panel h3{font-weight:700;color:#333}
.left-panel h1{
  font-size:13.5vw;color:#1e88e5;font-weight:800;margin:0
}

.right-panel{
  flex:1.5;border-radius:16px;overflow:hidden;
  box-shadow:0 5px 15px rgba(0,0,0,.15)
}

.video-frame{width:100%;height:100%;object-fit:cover}

/* ================= SLIDER ================= */
.slider-container{
  position:fixed;left:0;bottom:18px;width:100%;
  height:170px;background:#0b3c8a;
  border-top:6px solid #42a5f5;
  overflow:hidden;z-index:9999
}

.slider-track{
  display:flex;gap:3rem;padding:18px 2rem;
  width:max-content;will-change:transform
}

.slider-card{
  background:#fff;border-radius:18px;
  width:320px;height:135px;padding:12px 14px;
  text-align:center;flex-shrink:0;
  box-shadow:0 6px 12px rgba(0,0,0,.25)
}

.slider-card h6{
  margin:0;color:#1976d2;font-weight:800;
  border-bottom:2px solid #42a5f5
}
.slider-card h5{
  margin:8px 0;color:#0d47a1;
  font-size:2.6rem;font-weight:900
}
.slider-card small{
  font-weight:700;line-height:1.2;display:block
}
</style>
</head>

<body>

<!-- ================= HEADER ================= -->
<div class="header-bar">
  <div id="currentDate">—</div>
  <div>
    <h5 class="m-0 fw-bold">
      MAL PELAYANAN PUBLIK<br>
      REMBANG<br>
      <small>JAWA TENGAH</small>
      <?php if (!empty($selected_sector)): ?>
        <br><span class="sector-badge">SEKTOR: <?= strtoupper($selected_sector->nama_sektor) ?></span>
      <?php endif; ?>
    </h5>
  </div>
</div>

<!-- ================= MAIN ================= -->
<div class="main-content">
  <div class="left-panel">
    <div id="loketNow" class="loket-now">LOKET —</div>
    <h3 id="instansiName">—</h3>
    <h1 id="currentNumber">—</h1>
    <span>Sedang Dilayani</span>
  </div>

<div class="right-panel">
<?php if ($video->source_type === 'youtube' && !empty($youtube_embed_id)): ?>
  <iframe class="video-frame"
    src="https://www.youtube-nocookie.com/embed/<?= $youtube_embed_id ?>?autoplay=1&mute=1&loop=1&playlist=<?= $youtube_embed_id ?>&controls=0&rel=0&playsinline=1&modestbranding=1"
    allow="autoplay; encrypted-media"
    referrerpolicy="strict-origin-when-cross-origin"
    allowfullscreen>
  </iframe>

<?php elseif (!empty($video->file_path)): ?>
  <video autoplay muted loop playsinline class="video-frame">
    <source src="<?= base_url($video->file_path) ?>" type="video/mp4">
  </video>
<?php endif; ?>
</div>

<!-- ================= SLIDER ================= -->
<div class="slider-container">
  <div class="slider-track" id="sliderAntrian"></div>
</div>

<script>
/* ================= JAM ================= */
let lastPlayedCalledAt = null;
let hasInitialDataSync = false;
const AUDIO_PLAYBACK_RATE = <?= isset($video->audio_speed) && is_numeric($video->audio_speed) ? (float) $video->audio_speed : 1.5 ?>;
const DATA_ENDPOINT = "<?= $data_endpoint ?>";

setInterval(()=>{
  const n=new Date();
  document.getElementById('currentDate').innerText =
    n.toLocaleDateString('id-ID',{weekday:'long',day:'2-digit',month:'long',year:'numeric'})+
    ' • '+n.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
},1000);

/* ================= SLIDER ENGINE ================= */
let sliderPos=0,animationFrameId=null;
const sliderTrack=document.getElementById('sliderAntrian');

function animateSlider(){
  const w = sliderTrack.scrollWidth;

  // ⛔ jangan animasi kalau belum siap
  if (!w || w < window.innerWidth) {
    animationFrameId = requestAnimationFrame(animateSlider);
    return;
  }

  sliderPos -= 0.3;

  if (sliderPos <= -w / 2) {
    sliderPos = 0;
  }

  sliderTrack.style.transform = `translate3d(${sliderPos}px,0,0)`;
  animationFrameId = requestAnimationFrame(animateSlider);
}

/* ================= AUDIO ENGINE ================= */

function playQueueAudio(files){
  if(!files.length) return;
  const a=new Audio(files.shift());
  a.playbackRate = AUDIO_PLAYBACK_RATE;
  a.play();
  a.onended=()=>playQueueAudio(files);
  a.onerror=()=>playQueueAudio(files);
}

function parseMysqlDateTime(value){
  if(!value) return null;

  const normalized = String(value).trim().replace(' ', 'T');
  const parsed = new Date(normalized);
  if(!Number.isNaN(parsed.getTime())) {
    return parsed.getTime();
  }

  const m = String(value).trim().match(
    /^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2})(?::(\d{2}))?)?$/
  );
  if(!m) return null;

  return new Date(
    Number(m[1]),
    Number(m[2]) - 1,
    Number(m[3]),
    Number(m[4] || 0),
    Number(m[5] || 0),
    Number(m[6] || 0)
  ).getTime();
}

function normalizeLoketValue(loket){
  if(loket === null || loket === undefined) return '';
  const raw = String(loket).trim();
  const digits = raw.match(/\d+/);
  return digits ? String(parseInt(digits[0], 10)) : raw;
}

function formatLoketLabel(loket){
  const normalized = normalizeLoketValue(loket);
  return normalized ? 'LOKET ' + normalized : 'LOKET -';
}

function playAntrianAudio(nomor,loket){
  const base="<?= base_url('assets/sounds/voice/') ?>";
  const f=[];
  f.push(base+"1_nomor_antrian.mp3");

  const huruf=nomor.substring(0,1).toLowerCase();
  if(huruf>='a'&&huruf<='z') f.push(base+"huruf_"+huruf+".mp3");

  nomor.substring(1).split('').forEach(n=>{
    if(!isNaN(n)) f.push(base+"angka_"+n+".mp3");
  });

  f.push(base+"4_menuju_loket.mp3");
  const loketAudio = normalizeLoketValue(loket);
  if(loketAudio) {
    f.push(base+"loket_"+loketAudio+".mp3");
  }
  playQueueAudio(f);
}

/* ================= DATA UPDATE ================= */
let lastDataHash='';

async function updateDisplay(){
  try {
    const res = await fetch(DATA_ENDPOINT, { cache: 'no-store' });
    const data = await res.json();
    const c = data.current || {};

    // ===== UPDATE TEKS =====
    document.getElementById('instansiName').innerText = c.nama_instansi || '—';
    document.getElementById('currentNumber').innerText = c.nomor_antrian || '—';
    document.getElementById('loketNow').innerText = formatLoketLabel(c.nama_loket);

    // ===== AUDIO =====
    if (c.status === 'dipanggil' && c.called_at) {
      const calledTime = parseMysqlDateTime(c.called_at);

      if (calledTime !== null) {
        if (!hasInitialDataSync) {
          lastPlayedCalledAt = calledTime;
        } else if (lastPlayedCalledAt === null || calledTime > lastPlayedCalledAt) {
          lastPlayedCalledAt = calledTime;
          if (c.nomor_antrian) {
            playAntrianAudio(c.nomor_antrian, c.nama_loket);
          }
        }
      }
    }

    // ===== SLIDER =====
    const hash = JSON.stringify(data.slider || []);
    if (hash !== lastDataHash) {
      lastDataHash = hash;
      sliderTrack.innerHTML = '';

      if (data.slider && data.slider.length) {
        let html = '';
        data.slider.forEach(i => {
          html += `
            <div class="slider-card">
              <h6>${formatLoketLabel(i.nama_loket)}</h6>
              <h5>${i.nomor_antrian}</h5>
              <small>${i.nama_instansi || ''}</small>
            </div>
          `;
        });

        sliderTrack.innerHTML = html + html;
        sliderPos = 0;

        if (animationFrameId) cancelAnimationFrame(animationFrameId);
        animationFrameId = null;
        animateSlider();
      } else if (animationFrameId) {
        cancelAnimationFrame(animationFrameId);
        animationFrameId = null;
        sliderTrack.style.transform = 'translate3d(0,0,0)';
      }
    }

    hasInitialDataSync = true;
  } catch (err) {
    console.error('Gagal memuat data monitor antrian', err);
  }
}


setInterval(updateDisplay, 2000);
updateDisplay();

</script>

</body>
</html>
