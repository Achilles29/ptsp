<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_layanan extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->_check_access(2); // role_id 2 = admin layanan
    $this->load->model(['Antrian_model', 'Layanan_model', 'User_model', 'Instansi_model', 'Operasional_model']);
    $this->Operasional_model->ensure_instansi_operasional_schema();
    date_default_timezone_set('Asia/Jakarta');
  }

  private function _check_access($role_id)
  {
    if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != $role_id) {
      redirect('auth/login');
    }
  }

  private function _get_allowed_kode_layanan()
  {
    $raw = (string) $this->session->userdata('kode_layanan');
    if (trim($raw) === '') {
      return [];
    }

    $parts = explode(',', $raw);
    $clean = [];
    foreach ($parts as $kode) {
      $kode = strtoupper(trim($kode));
      if ($kode !== '') {
        $clean[] = $kode;
      }
    }

    return array_values(array_unique($clean));
  }

  public function dashboard()
  {
    $instansi_id = $this->session->userdata('instansi_id');
    $allowed_kode = $this->_get_allowed_kode_layanan();

    $data['title']          = "Dashboard Admin Pelayanan";
    $data['user']           = $this->session->userdata();

    // ringkasan
    $data['ringkasan']      = $this->Antrian_model->get_ringkasan_hari_ini($instansi_id, $allowed_kode);
    $data['jumlah_layanan'] = $this->Layanan_model->count_by_instansi($instansi_id, $allowed_kode);
    $data['status_breakdown'] = $this->Antrian_model->get_today_status_breakdown_by_instansi($instansi_id, $allowed_kode);
    $data['attendance_breakdown'] = $this->Antrian_model->get_today_attendance_breakdown_by_instansi($instansi_id, $allowed_kode);
    $data['trend_harian'] = $this->Antrian_model->get_recent_trend_by_instansi($instansi_id, 7, $allowed_kode);
    $data['layanan_breakdown'] = $this->Antrian_model->get_today_layanan_breakdown_by_instansi($instansi_id, 6, $allowed_kode);
    $data['current_call'] = $this->Antrian_model->get_current_call_by_instansi($instansi_id, $allowed_kode);

    $data['antrian']        = $this->Antrian_model->get_today_by_instansi($instansi_id, 25, 0, '', $allowed_kode);
    $data['antrian_terbaru'] = $this->Antrian_model->get_latest_today($instansi_id, $allowed_kode);

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('admin_layanan/dashboard', $data);
    $this->load->view('templates/_footer');
  }


  public function antrian_hari_ini()
  {
    $instansi_id = $this->session->userdata('instansi_id');
    $allowed_kode = $this->_get_allowed_kode_layanan();
    $search = $this->input->get('search') ?? '';
    $limit  = (int) ($this->input->get('limit') ?? 25);

    // Ambil query ?page= (bisa berisi nomor halaman atau offset)
    $page_param = (int) ($this->input->get('page') ?? 0);

    // 🧠 Cek apakah pagination kirim offset (>= limit)
    if ($limit > 0) {
      if ($page_param >= $limit) {
        $offset = $page_param;
        $page = floor($offset / $limit) + 1;
      } else {
        $page = max($page_param, 1);
        $offset = ($page - 1) * $limit;
      }
    } else {
      $page = 1;
      $offset = 0;
    }

    $total_rows = $this->Antrian_model->count_today_by_instansi($instansi_id, $search, $allowed_kode);
    $pagination_links = '';
    if ($limit > 0) {
      $this->load->library('pagination');
      $config['base_url'] = site_url('admin_layanan/antrian_hari_ini');
      $config['page_query_string'] = true;
      $config['query_string_segment'] = 'page';
      $config['total_rows'] = $total_rows;
      $config['per_page'] = $limit;
      $config['reuse_query_string'] = true;
      $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
      $config['full_tag_close'] = '</ul></nav>';
      $config['attributes'] = ['class' => 'page-link'];
      $config['first_tag_open'] = '<li class="page-item">';
      $config['first_tag_close'] = '</li>';
      $config['last_tag_open'] = '<li class="page-item">';
      $config['last_tag_close'] = '</li>';
      $config['next_tag_open'] = '<li class="page-item">';
      $config['next_tag_close'] = '</li>';
      $config['prev_tag_open'] = '<li class="page-item">';
      $config['prev_tag_close'] = '</li>';
      $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
      $config['cur_tag_close'] = '</span></li>';
      $config['num_tag_open'] = '<li class="page-item">';
      $config['num_tag_close'] = '</li>';
      $this->pagination->initialize($config);
      $pagination_links = $this->pagination->create_links();
    }

    // ✅ Ambil data
    $data['title'] = "Antrian Hari Ini";
    $data['user'] = $this->session->userdata();
    $data['antrian'] = $this->Antrian_model->get_today_by_instansi($instansi_id, $limit, $offset, $search, $allowed_kode);
    $data['pagination'] = $pagination_links;
    $data['search'] = $search;
    $data['limit'] = $limit;
    $data['offset'] = $offset;
    $data['total_rows'] = $total_rows;

    $this->load->view('templates/_header', $data);
    
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('admin_layanan/antrian_hari_ini', $data);
    $this->load->view('templates/_footer');
  }


  public function antrian_hari_ini_ajax()
  {
    $instansi_id = $this->session->userdata('instansi_id');
    $allowed_kode = $this->_get_allowed_kode_layanan();
    $search = $this->input->get('search') ?? '';
    $limit  = (int) ($this->input->get('limit') ?? 25);
    $offset = 0;

    $this->load->model('Antrian_model');

    $data['antrian'] = $this->Antrian_model->get_today_by_instansi($instansi_id, $limit, $offset, $search, $allowed_kode);
    $data['offset']  = $offset;

    // ✅ hanya load bagian tabel (tanpa layout)
    $this->load->view('admin_layanan/_partial_antrian_ajax', $data);
  }



public function panggil($id)
{
  // Ambil data nomor antrian & loket
  $row = $this->db
    ->select('a.id, a.nomor_antrian, a.status, a.hadir, i.loket')
    ->from('antrian a')
    ->join('jenis_layanan j', 'j.id = a.layanan_id', 'left')
    ->join('instansi i', 'i.id = j.instansi_id', 'left')
    ->where('a.id', $id)
    ->get()
    ->row();

  if (!$row) {
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'success' => false,
        'message' => 'Data antrian tidak ditemukan'
      ]));
  }

  if (!in_array($row->status, ['terdaftar', 'dipanggil'], true)) {
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'success' => false,
        'message' => 'Antrian tidak dalam status yang bisa dipanggil'
      ]));
  }

  if ((int) $row->hadir !== 1) {
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'success' => false,
        'message' => 'Antrian belum check-in, jadi belum bisa dipanggil'
      ]));
  }

  // Cegah memanggil antrian baru jika sudah ada antrian berstatus 'dipanggil'
  if ($row->status === 'terdaftar') {
    $instansi_id = $this->session->userdata('instansi_id');
    $ada_aktif = $this->db
      ->from('antrian a')
      ->join('jenis_layanan j', 'j.id = a.layanan_id', 'left')
      ->join('instansi i', 'i.id = j.instansi_id', 'left')
      ->where('i.id', $instansi_id)
      ->where('a.status', 'dipanggil')
      ->where('a.tanggal', date('Y-m-d'))
      ->count_all_results();

    if ($ada_aktif > 0) {
      return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
          'success' => false,
          'message' => 'Masih ada antrian yang sedang dipanggil. Selesaikan dulu sebelum memanggil berikutnya.'
        ]));
    }
  }

  // ✅ UPDATE STATUS + CALLED_AT (KHUSUS PANGGIL)
  $this->db->set([
    'status'     => 'dipanggil',
    'called_at'  => date('Y-m-d H:i:s'), // ⬅️ INI KUNCI AUDIO
    'updated_at' => date('Y-m-d H:i:s')
  ]);
  $this->db->where('id', $id);
  $ok = $this->db->update('antrian');

  // Output JSON ke AJAX
  return $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode([
      'success'        => (bool) $ok,
      'message'        => $ok ? 'Antrian berhasil dipanggil' : 'Gagal memanggil antrian',
      'nomor_antrian'  => $row->nomor_antrian,
      'loket'          => $row->loket
    ]));
}



  public function selesai($id)
  {
    $this->session->set_flashdata('error', 'Gunakan form hasil layanan untuk menyelesaikan antrian.');
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

public function simpan_hasil_layanan()
{
  log_message('error', '=== SIMPAN HASIL LAYANAN DIPANGGIL ===');

  // ===============================
  // POST DATA
  // ===============================
  $post = $this->input->post(null, true);
  log_message('error', 'POST RAW: ' . json_encode($_POST));
  log_message('error', 'POST CI : ' . json_encode($post));

  // ===============================
  // SESSION
  // ===============================
  $user_id = $this->session->userdata('user_id');
  $role_id = $this->session->userdata('role_id');

  log_message('error', 'SESSION id=' . var_export($user_id, true) . ' role_id=' . var_export($role_id, true));

  if (empty($user_id)) {
    log_message('error', '❌ GAGAL: session id kosong');
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'success' => false,
        'message' => 'Session tidak valid. Silakan login ulang.'
      ]));
  }

  // ===============================
  // VALIDASI POST
  // ===============================
  if (empty($post['antrian_id']) || empty($post['jenis_hasil'])) {
    log_message('error', '❌ GAGAL: antrian_id / jenis_hasil kosong');
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'success' => false,
        'message' => 'Data tidak lengkap'
      ]));
  }

  $jenis_hasil = trim((string) $post['jenis_hasil']);
  $ringkasan = trim((string) ($post['ringkasan_konsultasi'] ?? ''));
  $jenis_produk = trim((string) ($post['jenis_produk_hukum'] ?? ''));
  $nomor_produk = trim((string) ($post['nomor_produk'] ?? ''));
  $tanggal_produk = trim((string) ($post['tanggal_produk'] ?? ''));
  $catatan_petugas = trim((string) ($post['catatan_petugas'] ?? ''));

  if (!in_array($jenis_hasil, ['konsultasi', 'produk_hukum'], true)) {
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'success' => false,
        'message' => 'Jenis hasil layanan tidak valid'
      ]));
  }

  if ($jenis_hasil === 'konsultasi' && $ringkasan === '') {
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'success' => false,
        'message' => 'Ringkasan konsultasi wajib diisi sebelum antrian diselesaikan'
      ]));
  }

  if ($jenis_hasil === 'produk_hukum') {
    if ($jenis_produk === '' || $nomor_produk === '' || $tanggal_produk === '') {
      return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
          'success' => false,
          'message' => 'Jenis produk, nomor produk, dan tanggal produk wajib diisi'
        ]));
    }
  }

  if ($catatan_petugas === '') {
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'success' => false,
        'message' => 'Catatan petugas wajib diisi sebelum layanan ditutup'
      ]));
  }

  // ===============================
  // CEK STATUS ANTRIAN
  // ===============================
  $antrian = $this->db
    ->where('id', $post['antrian_id'])
    ->get('antrian')
    ->row();

  log_message('error', 'DATA ANTRIAN DB: ' . json_encode($antrian));

  if (!$antrian) {
    log_message('error', '❌ GAGAL: antrian tidak ditemukan');
    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'success' => false,
        'message' => 'Antrian tidak ditemukan'
      ]));
  }

  if ($antrian->status !== 'dipanggil') {
    log_message(
      'error',
      '❌ GAGAL: status antrian bukan dipanggil (status=' . $antrian->status . ')'
    );

    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'success' => false,
        'message' => 'Antrian sudah tidak dalam status dipanggil'
      ]));
  }

  // ===============================
  // TRANSAKSI DB
  // ===============================
  log_message('error', '▶️ DB TRANSACTION START');
  $this->db->trans_start();

  $insertData = [
    'antrian_id'           => $post['antrian_id'],
    'jenis_hasil'          => $jenis_hasil,
    'ringkasan_konsultasi' => $jenis_hasil === 'konsultasi'
                                ? $ringkasan
                                : null,
    'jenis_produk_hukum'   => $jenis_hasil === 'produk_hukum'
                                ? $jenis_produk
                                : null,
    'nomor_produk'         => $jenis_hasil === 'produk_hukum'
                                ? $nomor_produk
                                : null,
    'tanggal_produk'       => $jenis_hasil === 'produk_hukum'
                                ? $tanggal_produk
                                : null,
    'catatan_petugas'      => $catatan_petugas,
    'selesai_at'           => date('Y-m-d H:i:s'),
    'created_by'           => $user_id,
    'created_role'         => $role_id
  ];

  log_message('error', 'INSERT hasil_layanan: ' . json_encode($insertData));
  $this->db->insert('hasil_layanan', $insertData);

  log_message('error', 'UPDATE antrian ke selesai');
  $this->db->where('id', $post['antrian_id'])->update('antrian', [
    'status'       => 'selesai',
    'updated_by'   => $user_id,
    'updated_role' => $role_id,
    'updated_at'   => date('Y-m-d H:i:s')
  ]);

  $this->db->trans_complete();

  $status = $this->db->trans_status();
  log_message('error', 'DB TRANSACTION STATUS: ' . ($status ? 'SUCCESS' : 'FAILED'));

  return $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode([
      'success' => $status
    ]));
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
    $instansi_id = $this->session->userdata('instansi_id');
    $allowed_kode = $this->_get_allowed_kode_layanan();
    $antrian = $this->Antrian_model->get_today_by_instansi($instansi_id, 25, 0, '', $allowed_kode);
    $this->load->view('admin_layanan/_partial_antrian_table', ['antrian' => $antrian, 'offset' => 0]);
  }

  public function jumlah_antrian_hari_ini()
  {
    $instansi_id = $this->session->userdata('instansi_id');
    $allowed_kode = $this->_get_allowed_kode_layanan();

    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->db->where('a.tanggal', date('Y-m-d'));
    if (!empty($allowed_kode)) {
      $this->db->where_in('jl.kode', $allowed_kode);
    }
    $jumlah = $this->db->count_all_results();

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode(['jumlah' => (int)$jumlah]));
  }

  public function refresh_ringkasan()
  {
    $instansi_id              = $this->session->userdata('instansi_id');
    $allowed_kode             = $this->_get_allowed_kode_layanan();
    $data['ringkasan']        = $this->Antrian_model->get_ringkasan_hari_ini($instansi_id, $allowed_kode);
    $data['jumlah_layanan']   = $this->Layanan_model->count_by_instansi($instansi_id, $allowed_kode);

    $this->load->view('admin_layanan/_partial_ringkasan', $data);
  }

  public function cek_total_antrian_json()
  {
    $instansi_id = $this->session->userdata('instansi_id');
    $allowed_kode = $this->_get_allowed_kode_layanan();
    $ringkasan   = $this->Antrian_model->get_ringkasan_hari_ini($instansi_id, $allowed_kode);

    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode([
        'total' => (int)($ringkasan['total'] ?? 0)
      ]));
  }


  // ✅ Fitur Kelola Status Layanan
public function kelola_layanan()
{
    $this->session->set_flashdata('error', 'Kelola layanan dipusatkan di Super Admin.');
    redirect('admin_layanan/dashboard');
}

public function update_status_layanan()
{
    $this->session->set_flashdata('error', 'Perubahan status layanan dipusatkan di Super Admin.');
    redirect('admin_layanan/dashboard');
}



  public function riwayat_antrian()
  {
    $instansi_id = $this->session->userdata('instansi_id');
    $allowed_kode = $this->_get_allowed_kode_layanan();
    $tanggal_awal = $this->input->get('tanggal_awal') ?: date('Y-m-01');
    $tanggal_akhir = $this->input->get('tanggal_akhir') ?: date('Y-m-d');
    $search = $this->input->get('search') ?? '';
    $limit = (int) ($this->input->get('limit') ?? 25);
    $page = (int) ($this->input->get('page') ?? 1);
    $page = max(1, $page);
    $offset = $limit > 0 ? ($page - 1) * $limit : 0;

    if ($tanggal_awal > $tanggal_akhir) {
      $temp = $tanggal_awal;
      $tanggal_awal = $tanggal_akhir;
      $tanggal_akhir = $temp;
    }

    $data['antrian'] = $this->Antrian_model->get_riwayat_antrian($tanggal_awal, $tanggal_akhir, $instansi_id, $limit, $offset, $search, $allowed_kode);
    $data['tanggal_awal'] = $tanggal_awal;
    $data['tanggal_akhir'] = $tanggal_akhir;
    $data['search'] = $search;
    $data['limit'] = $limit;
    $data['start'] = $offset;
    $data['title'] = "Riwayat Antrian";
    $data['total_rows'] = $this->Antrian_model->count_riwayat_antrian($instansi_id, $tanggal_awal, $tanggal_akhir, $search, $allowed_kode);

    // === Pagination ===
    $data['pagination_links'] = '';
    if ($limit > 0 && $data['total_rows'] > 0) {
      $this->load->library('pagination');
      $config['base_url'] = base_url('admin_layanan/riwayat_antrian');
      $config['total_rows'] = $data['total_rows'];
      $config['per_page'] = $limit;
      $config['page_query_string'] = TRUE;
      $config['query_string_segment'] = 'page';
      $config['use_page_numbers'] = TRUE;
      $config['reuse_query_string'] = TRUE;
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
    }

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('admin_layanan/riwayat_antrian', $data);
    $this->load->view('templates/_footer');
  }

  public function riwayat_antrian_ajax()
  {
    $instansi_id = $this->session->userdata('instansi_id');
    $allowed_kode = $this->_get_allowed_kode_layanan();
    $tanggal_awal = $this->input->get('tanggal_awal') ?: date('Y-m-01');
    $tanggal_akhir = $this->input->get('tanggal_akhir') ?: date('Y-m-d');
    $search = $this->input->get('search') ?? '';
    $limit = 100;

    if ($tanggal_awal > $tanggal_akhir) {
      $temp = $tanggal_awal;
      $tanggal_awal = $tanggal_akhir;
      $tanggal_akhir = $temp;
    }

    $data['antrian'] = $this->Antrian_model->get_riwayat_antrian($tanggal_awal, $tanggal_akhir, $instansi_id, $limit, 0, $search, $allowed_kode);
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

  public function rekap_laporan()
  {
    redirect('laporan/dashboard');
  }
}
