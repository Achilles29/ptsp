<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Masyarakat extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->_check_access(4); // role_id 2 = masyarakat
    $this->load->model('Antrian_model');
    $this->load->model('Layanan_model');
    $this->load->model('Jenislayanan_model');
  }

  private function _check_access($role_id)
  {
    if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != $role_id) {
      redirect('auth/login');
    }
  }

  public function dashboard()
  {
    $this->load->model('Masyarakat_model');

    $user_id = $this->session->userdata('user_id');

    $data['title'] = "Dashboard Masyarakat";
    $data['user']  = $this->session->userdata();

    // Ringkasan dashboard
    $data['aktif']        = $this->Masyarakat_model->count_antrian_aktif($user_id);
    $data['layanan_aktif'] = $this->Masyarakat_model->get_layanan_aktif($user_id);
    $data['riwayat']      = $this->Masyarakat_model->count_riwayat($user_id);
    $data['chat_pending'] = $this->Masyarakat_model->count_chat_pending($user_id);
    $data['antrian_detail'] = $this->Masyarakat_model->get_antrian_aktif_detail($user_id);


    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('masyarakat/dashboard', $data);
    $this->load->view('templates/_footer');
  }

  public function daftar_antrian()
  {
    $data['title'] = "Daftar Antrian";
    $data['user'] = $this->session->userdata();
    $this->load->model('Instansi_model');
    $data['instansi'] = $this->Instansi_model->get_all();

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('masyarakat/daftar_antrian', $data);
    $this->load->view('templates/_footer');
  }

  public function simpan_antrian()
  {
    $user_id = $this->session->userdata('user_id');
    $layanan_id = $this->input->post('layanan_id');
    $tanggal = $this->input->post('tanggal');

    $layanan = $this->db
      ->select('l.*, i.id as instansi_id')
      ->from('jenis_layanan l')
      ->join('instansi i', 'i.id = l.instansi_id', 'left')
      ->where('l.id', $layanan_id)
      ->get()
      ->row();

    $instansi_id = $layanan->instansi_id ?? 0;

    $ada_antrian = $this->db
      ->select('a.*, l.instansi_id')
      ->from('antrian a')
      ->join('jenis_layanan l', 'l.id = a.layanan_id')
      ->where('a.user_id', $user_id)
      ->where('l.instansi_id', $instansi_id)
      ->where_in('a.status', ['terdaftar', 'dipanggil'])
      ->get()
      ->num_rows();


    if ($ada_antrian > 0) {
      $this->session->set_flashdata('error', 'Anda sudah memiliki antrian aktif di instansi ini.');
      redirect('masyarakat/daftar_antrian');
      return;
    }

    // Simpan seperti sebelumnya
    $nomor_antrian = $this->Antrian_model->generate_nomor($layanan_id, $tanggal);

    $data = [
      'user_id'        => $user_id,
      'layanan_id'     => $layanan_id,
      'tanggal'        => $tanggal,
      'nomor_antrian'  => $nomor_antrian,
      'status'         => 'terdaftar',
      'created_at'     => date('Y-m-d H:i:s'),
      'updated_at'     => date('Y-m-d H:i:s'),
      'updated_by'     => $user_id,
      'updated_role'   => 'masyarakat'
    ];

    $this->Antrian_model->insert($data);
    $this->session->set_flashdata('success', 'Antrian berhasil didaftarkan.');
    redirect('masyarakat/dashboard');
  }

  public function by_instansi($kode_instansi)
  {
    $data = $this->Jenislayanan_model->get_by_instansi($kode_instansi);
    echo json_encode($data);
  }
  public function get_layanan_by_instansi($instansi_id)
  {
    $data = $this->db
      ->select('id, nama_layanan')
      ->where('instansi_id', $instansi_id)
      ->get('jenis_layanan')
      ->result();
    echo json_encode($data);
  }

  public function antrian_saya()
  {
    $user_id = $this->session->userdata('user_id');

    $data['title'] = "Antrian Saya";
    $data['user']  = $this->session->userdata();

    // Ambil semua antrian user (status aktif)
    $data['antrian'] = $this->db
      ->select('a.*, jl.nama_layanan, i.nama_instansi')
      ->from('antrian a')
      ->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left')
      ->join('instansi i', 'i.id = jl.instansi_id', 'left')
      ->where('a.user_id', $user_id)
      ->where_in('a.status', ['terdaftar', 'dipanggil'])
      ->order_by('a.created_at', 'DESC')
      ->get()->result();

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('masyarakat/antrian_saya', $data);
    $this->load->view('templates/_footer');
  }

  public function riwayat_antrian()
  {
    $user_id = $this->session->userdata('user_id');

    $this->db->select('a.*, l.nama_layanan');
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan l', 'l.id = a.layanan_id');
    $this->db->where('a.user_id', $user_id);
    $this->db->order_by('a.tanggal', 'DESC');
    $data['antrian'] = $this->db->get()->result();

    $data['title'] = 'Riwayat Antrian';
    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('masyarakat/riwayat_antrian', $data);
    $this->load->view('templates/_footer');
  }


  public function batalkan_antrian($id)
  {
    $user_id = $this->session->userdata('user_id');

    $cek = $this->db->get_where('antrian', [
      'id' => $id,
      'user_id' => $user_id,
      'status !=' => 'selesai',
    ])->row();

    if ($cek) {
      $this->db->where('id', $id)->update('antrian', [
        'status'       => 'batal',
        'updated_at'   => date('Y-m-d H:i:s'),
        'updated_by'   => $user_id,
        'updated_role' => 'masyarakat'
      ]);
      $this->session->set_flashdata('success', 'Antrian berhasil dibatalkan.');
    }

    redirect('masyarakat/riwayat_antrian');
  }

  public function scan_qr($antrian_id)
  {
    $data['antrian_id'] = $antrian_id;
    $this->load->view('masyarakat/scan_qr', $data);
  }


  public function checkin_user($id)
  {
    $user_id = $this->session->userdata('user_id');

    // Validasi kepemilikan
    $antrian = $this->db->get_where('antrian', [
      'id'     => $id,
      'user_id' => $user_id,
      'tanggal' => date('Y-m-d'),
      'hadir'  => 0
    ])->row();

    if (!$antrian) {
      $this->session->set_flashdata('error', 'Antrian tidak valid atau sudah check-in');
      redirect('masyarakat/antrian_saya');
    }

    $this->db->where('id', $id)->update('antrian', ['hadir' => 1]);

    $this->session->set_flashdata('success', 'Check-in berhasil!');
    redirect('masyarakat/antrian_saya');
  }

  public function checkin()
  {
    // halaman kamera scan
    $this->load->view('masyarakat/checkin');
  }

  public function qrcode($id)
  {
    $user_id = $this->session->userdata('user_id');

    // Validasi bahwa QR ini milik user tersebut
    $antrian = $this->db->get_where('antrian', [
      'id'      => $id,
      'user_id' => $user_id
    ])->row();

    if (!$antrian) {
      echo "QR tidak valid.";
      return;
    }

    $data['url'] = site_url("masyarakat/checkin_url/" . $id);
    $this->load->view('masyarakat/qrcode', $data);
  }

  public function checkin_url($id)
  {
    $antrian = $this->db->get_where('antrian', [
      'id'      => $id,
      'tanggal' => date('Y-m-d')
    ])->row();

    if (!$antrian) {
      echo "QR tidak valid atau antrian tidak ditemukan.";
      return;
    }

    // Update hadir
    $this->db->where('id', $id)->update('antrian', [
      'hadir' => 1
    ]);

    echo "<h1 style='text-align:center;margin-top:50px;color:green'>
            Check-in Berhasil ✔
          </h1>";
  }
}
