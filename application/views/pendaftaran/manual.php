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
body{
    background:#f7fafb;
    font-family:Poppins,sans-serif;
}

/* ===== UI ===== */
.section-title{
    font-weight:700;
    font-size:1.25rem;
    color:#fff;
    background:linear-gradient(90deg,#a00037,#c2185b);
    padding:12px 16px;
    border-radius:12px 12px 0 0;
}

.card{
    border-radius:14px;
    box-shadow:0 6px 14px rgba(0,0,0,.12);
}

.btn-gradient{
    background:linear-gradient(90deg,#c2185b,#a00037);
    color:#fff;
    font-weight:600;
}

.qr-box{
    width:280px;
    height:280px;
    margin:auto;
    padding:16px;
    background:#fff;
    border:4px solid #198754;
    border-radius:18px;
}
</style>
</head>

<body>

<div class="container py-4">

<!-- WALK-IN -->
<div class="card mb-5">
<div class="section-title">
<i class="bi bi-person-plus-fill me-2"></i> Pendaftaran Walk-in
</div>

<div class="card-body bg-white">
<form id="formCetak" class="row g-3">

<div class="col-md-6">
<label class="fw-semibold">Instansi</label>
<select id="instansi" name="instansi_id" class="form-select" required>
<option value="">Pilih Instansi</option>
<?php foreach($instansi as $i): ?>
<option value="<?= $i->id ?>"><?= $i->nama_instansi ?></option>
<?php endforeach ?>
</select>
</div>

<div class="col-md-6">
<label class="fw-semibold">Jenis Layanan</label>
<select id="layanan" name="layanan_id" class="form-select" required>
<option value="">Pilih Layanan</option>
</select>
</div>

<div class="col-12">
<button class="btn btn-gradient w-100 py-2">
<i class="bi bi-printer-fill me-2"></i> Cetak Nomor Antrian
</button>
</div>

</form>
</div>
</div>

<!-- QR -->
<div class="card text-center">
<div class="section-title" style="background:linear-gradient(90deg,#00796b,#009688)">
<i class="bi bi-qr-code me-2"></i> Check-In Online
</div>

<div class="card-body">
<div id="qrcode" class="qr-box mb-3"></div>
<p class="text-muted fw-semibold">Scan QR untuk check-in</p>

<button id="btnCheckinManual" class="btn btn-outline-success fw-bold">
<i class="bi bi-person-check-fill me-2"></i> Check-In Manual
</button>
</div>
</div>

</div>

<!-- MODAL MANUAL -->
<div class="modal fade" id="modalManual">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<div class="modal-header bg-success text-white">
<h5 class="modal-title">Check-In Manual</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body" id="listManual"></div>
</div>
</div>
</div>

<script>
$(function(){
const PRINT_SERVER_URL = "http://127.0.0.1:9100/print";

/* QR */
new QRCode(document.getElementById("qrcode"),{
    text:"<?= site_url('pendaftaran/checkin') ?>",
    width:240,height:240
});

/* layanan */
$('#instansi').change(function(){
    $.getJSON("<?= site_url('pendaftaran/get_layanan_by_instansi/') ?>"+this.value,function(d){
        let o='<option value="">Pilih Layanan</option>';
        d.forEach(x=>o+=`<option value="${x.id}">${x.nama_layanan}</option>`);
        $('#layanan').html(o);
    });
});

/* submit */
$('#formCetak').submit(function(e){
e.preventDefault();
const $btn = $(this).find('button[type="submit"]');
const layananNama = $('#layanan option:selected').text();

if(!$('#instansi').val() || !$('#layanan').val()){
Swal.fire('Validasi','Pilih instansi dan layanan terlebih dahulu','warning');
return;
}

Swal.fire({title:'Memproses...',allowOutsideClick:false,didOpen:()=>Swal.showLoading()});
$btn.prop('disabled', true);

$.ajax({
url: "<?= site_url('pendaftaran/generate_antrian') ?>",
method: "POST",
data: $(this).serialize(),
dataType: "json"
}).done(async function(r){
if(!r.success){
Swal.fire('Gagal', r.message || 'Tidak bisa membuat antrian','error');
return;
}

try{
const resp = await fetch(PRINT_SERVER_URL,{
method:'POST',
headers:{'Content-Type':'application/json'},
body: JSON.stringify({ nomor:r.nomor, layanan: layananNama })
});
const data = await resp.json();
if(!resp.ok || !data.success){
throw new Error((data && data.message) ? data.message : 'Printer tidak merespons');
}

Swal.fire({
icon:'success',
title:'Berhasil',
html:`Nomor antrian <b>${r.nomor}</b> sudah dicetak`,
timer:1600,
showConfirmButton:false
});
$('#layanan').html('<option value="">Pilih Layanan</option>');
$('#formCetak')[0].reset();
}catch(err){
Swal.fire({
icon:'error',
title:'Antrian dibuat, cetak gagal',
text: err.message || 'Tidak bisa terhubung ke server printer'
});
}
}).fail(function(xhr){
const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan server';
Swal.fire('Gagal', msg, 'error');
}).always(function(){
$btn.prop('disabled', false);
});
});

/* manual */
$('#btnCheckinManual').click(()=>{
$('#modalManual').modal('show');
$('#listManual').load("<?= site_url('pendaftaran/list_antrian_manual_today') ?>");
});

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
