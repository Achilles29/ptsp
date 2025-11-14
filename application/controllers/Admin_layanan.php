<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_layanan extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->_check_access(2); // role_id 2 = admin layanan
    $this->load->model(['Antrian_model', 'Layanan_model', 'User_model', 'Instansi_model']);
  }

  private function _check_access($role_id)
  {
    if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != $role_id) {
      redirect('auth/login');
    }
  }

  public function dashboard()
  {
    $instansi_id           = $this->session->userdata('instansi_id');
    $data['title']         = "Dashboard Admin Pelayanan";
    $data['user']          = $this->session->userdata();
    $data['ringkasan']     = $this->Antrian_model->get_ringkasan_hari_ini($instansi_id);
    $data['jumlah_layanan'] = $this->Layanan_model->count_by_instansi($instansi_id);
    $data['antrian']       = $this->Antrian_model->get_today_by_instansi($instansi_id);
    // $data['kuota']      = $this->Layanan_model->get_kuota_layanan($instansi_id);

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('admin_layanan/dashboard', $data);
    $this->load->view('templates/_footer');
  }

  public function antrian_hari_ini()
  {
    $instansi_id     = $this->session->userdata('instansi_id');
    $data['title']   = "Antrian Hari Ini";
    $data['user']    = $this->session->userdata();
    $data['antrian'] = $this->Antrian_model->get_today_by_instansi($instansi_id);

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('admin_layanan/antrian_hari_ini', $data);
    $this->load->view('templates/_footer');
  }

  public function panggil($id)
  {
    // Ambil data nomor antrian & loket (join jenis_layanan → instansi)
    $this->db->select('a.nomor_antrian, i.loket');
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan j', 'j.id = a.layanan_id', 'left');
    $this->db->join('instansi i', 'i.id = j.instansi_id', 'left');
    $this->db->where('a.id', $id);
    $row = $this->db->get()->row();

    // Update status antrian
    $this->db->set('status', 'dipanggil');
    $this->db->set('updated_at', date('Y-m-d H:i:s'));
    $this->db->where('id', $id);
    $ok = $this->db->update('antrian');

    // Output JSON ke AJAX
    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'success' => (bool) $ok,
        'message' => $ok ? 'Antrian berhasil dipanggil' : 'Gagal memanggil antrian',
        'nomor_antrian' => $row->nomor_antrian ?? '',
        'loket' => $row->loket ?? ''
      ]));
  }


  public function selesai($id)
  {
    $this->db->where('id', $id)->update('antrian', [
      'status'     => 'selesai',
      'updated_at' => date('Y-m-d H:i:s')
    ]);
    $this->session->set_flashdata('success', 'Antrian telah ditandai selesai.');
    redirect('admin_layanan/antrian_hari_ini');
  }

  public function batal($id)
  {
    $this->db->where('id', $id)->update('antrian', [
      'status'     => 'batal',
      'updated_at' => date('Y-m-d H:i:s')
    ]);
    $this->session->set_flashdata('success', 'Antrian telah dibatalkan.');
    redirect('admin_layanan/antrian_hari_ini');
  }

  public function get_detail_antrian($id)
  {
    // Catatan relasi:
    // - Jika kolom relasi ke jenis_layanan ada di a.layanan_id (umum), gunakan join ke jenis_layanan dengan a.layanan_id
    // - Jika skema kamu memakai instansi_id di antrian, sesuaikan sesuai struktur DB
    $data = $this->db
      ->select('a.*, u.nama_lengkap, u.no_hp, jl.nama_layanan')
      ->from('antrian a')
      ->join('users u', 'u.id = a.user_id', 'left')
      ->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left') // <— sesuaikan jika beda
      ->where('a.id', $id)
      ->get()
      ->row_array();

    // Estimasi sederhana: urutan * 5 menit, mulai jam 08:00
    $urutan = intval($data['nomor_antrian'] ?? 0);
    $data['estimasi_waktu'] = date('H:i', strtotime('08:00') + ($urutan * 5 * 60));

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($data));
  }

  public function refresh_antrian()
  {
    $instansi_id     = $this->session->userdata('instansi_id');
    $data['antrian'] = $this->Antrian_model->get_today_by_instansi($instansi_id);
    $this->load->view('admin_layanan/_partial_antrian_table', $data);
  }

  public function jumlah_antrian_hari_ini()
  {
    $instansi_id = $this->session->userdata('instansi_id');

    $jumlah = $this->db
      ->from('antrian a')
      ->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left') // sesuaikan jika struktur berbeda
      ->where('jl.instansi_id', $instansi_id)
      ->where('a.tanggal', date('Y-m-d'))
      ->count_all_results();

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode(['jumlah' => (int)$jumlah]));
  }

  public function refresh_ringkasan()
  {
    $instansi_id              = $this->session->userdata('instansi_id');
    $data['ringkasan']        = $this->Antrian_model->get_ringkasan_hari_ini($instansi_id);
    $data['jumlah_layanan']   = $this->Layanan_model->count_by_instansi($instansi_id);

    $this->load->view('admin_layanan/_partial_ringkasan', $data);
  }

  public function cek_total_antrian_json()
  {
    $instansi_id = $this->session->userdata('instansi_id');
    $ringkasan   = $this->Antrian_model->get_ringkasan_hari_ini($instansi_id);

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'total' => (int)($ringkasan['total'] ?? 0)
      ]));
  }


  // ✅ Fitur Kelola Status Layanan
  public function kelola_layanan()
  {
    $instansi_id = $this->session->userdata('instansi_id');
    if (!$instansi_id) {
      show_error('Instansi ID tidak ditemukan di session.');
      return;
    }

    $data['instansi'] = $this->Instansi_model->get_by_id($instansi_id);

    if (!$data['instansi']) {
      show_error('Data instansi tidak ditemukan di database.');
      return;
    }

    $data['title'] = "Kelola Status Layanan";

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('admin_layanan/kelola_layanan', $data);
    $this->load->view('templates/_footer');
  }

  public function update_status_layanan()
  {
    $instansi_id = $this->input->post('instansi_id');
    $status = $this->input->post('status_layanan');

    $this->db->where('id', $instansi_id)->update('instansi', ['status_layanan' => $status]);

    $this->session->set_flashdata('success', 'Status layanan berhasil diperbarui.');
    redirect('admin_layanan/kelola_layanan');
  }

  public function riwayat_antrian()
  {
    $instansi_id = $this->session->userdata('instansi_id');
    $tanggal = $this->input->get('tanggal') ?? date('Y-m-d');
    $search = $this->input->get('search') ?? '';
    $limit = (int) ($this->input->get('limit') ?? 25);
    $page = (int) ($this->input->get('page') ?? 1);
    $offset = ($page - 1) * $limit;

    $this->load->model('Antrian_model');

    $data['antrian'] = $this->Antrian_model->get_riwayat_antrian($tanggal, $instansi_id, $limit, $offset, $search);
    $data['tanggal'] = $tanggal;
    $data['search'] = $search;
    $data['limit'] = $limit;
    $data['start'] = $offset;
    $data['title'] = "Riwayat Antrian";

    // === Pagination ===
    $this->load->library('pagination');
    $total_rows = $this->Antrian_model->count_riwayat_antrian($instansi_id, $tanggal, $search);

    $config['base_url'] = base_url("admin_layanan/riwayat_antrian?tanggal=$tanggal&search=" . urlencode($search) . "&limit=$limit");
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $limit;
    $config['page_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';
    $config['reuse_query_string'] = TRUE;

    // Bootstrap pagination style
    $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul></nav>';
    $config['attributes'] = ['class' => 'page-link'];
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open']  = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['next_tag_open']  = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['prev_tag_open']  = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['cur_tag_open']   = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close']  = '</span></li>';
    $config['num_tag_open']   = '<li class="page-item">';
    $config['num_tag_close']  = '</li>';

    $this->pagination->initialize($config);
    $data['pagination_links'] = $this->pagination->create_links();

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('admin_layanan/riwayat_antrian', $data);
    $this->load->view('templates/_footer');
  }

  public function riwayat_antrian_ajax()
  {
    $instansi_id = $this->session->userdata('instansi_id');
    $tanggal = $this->input->get('tanggal') ?? date('Y-m-d');
    $search = $this->input->get('search') ?? '';
    $limit = 100;

    $data['antrian'] = $this->Antrian_model->get_riwayat_antrian($tanggal, $instansi_id, $limit, 0, $search);
    $data['start'] = 0;

    $this->load->view('admin_layanan/_partial_riwayat_ajax', $data);
  }




  public function update_status_antrian()
  {
    $id = $this->input->post('id');
    $status = $this->input->post('status');

    $this->db->where('id', $id)->update('antrian', ['status' => $status]);
    $this->session->set_flashdata('success', 'Status antrian berhasil diperbarui.');
    redirect('admin_layanan/riwayat_antrian');
  }
}
