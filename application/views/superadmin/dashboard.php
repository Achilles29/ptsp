<div class="container-fluid px-4 mt-4">
  <h4 class="text-maroon"><i class="fas fa-user-shield me-2"></i><?= $title ?></h4>
  <hr>
  <p>Selamat datang, <strong><?= $this->session->userdata('nama') ?></strong> (Super Admin)</p>

  <ul>
    <li><a href="#">Kelola Jenis Layanan</a></li>
    <li><a href="#">Kelola Admin dan CS</a></li>
    <li><a href="#">Monitoring Antrian</a></li>
  </ul>
</div>
