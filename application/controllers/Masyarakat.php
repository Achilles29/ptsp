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
    $data['title'] = "Dashboard Masyarakat";
    $data['user'] = $this->session->userdata();
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
}
