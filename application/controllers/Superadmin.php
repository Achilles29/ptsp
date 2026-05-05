<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Superadmin extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->_check_access(1); // Superadmin
    $this->load->model(['Instansi_model', 'Operasional_model', 'Email_setting_model', 'User_model']);
    $this->_ensure_sector_schema();
    $this->_ensure_users_layanan_schema();
    $this->Operasional_model->ensure_instansi_operasional_schema();
    $this->Email_setting_model->ensure_schema();
  }

  private function _check_access($role_id)
  {
    if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != $role_id) {
      redirect('auth');
    }
  }

  private function _ensure_sector_schema()
  {
    if (!$this->db->table_exists('sektor_display')) {
      $this->db->query("
        CREATE TABLE sektor_display (
          id INT AUTO_INCREMENT PRIMARY KEY,
          kode_sektor VARCHAR(30) NOT NULL UNIQUE,
          nama_sektor VARCHAR(100) NOT NULL,
          slug VARCHAR(120) NOT NULL UNIQUE,
          lokasi_display VARCHAR(150) NULL,
          is_aktif TINYINT(1) NOT NULL DEFAULT 1,
          created_at DATETIME NULL,
          updated_at DATETIME NULL
        )
      ");
    }

    if (!$this->db->field_exists('sektor_id', 'instansi')) {
      $this->db->query("ALTER TABLE instansi ADD COLUMN sektor_id INT NULL AFTER nama_instansi");
      $this->db->query("ALTER TABLE instansi ADD INDEX idx_instansi_sektor (sektor_id)");
    }

    $count = (int) $this->db->count_all('sektor_display');
    if ($count === 0) {
      $now = date('Y-m-d H:i:s');
      $this->db->insert('sektor_display', [
        'kode_sektor' => 'UMUM',
        'nama_sektor' => 'Sektor Umum',
        'slug' => 'sektor-umum',
        'lokasi_display' => 'Display Utama',
        'is_aktif' => 1,
        'created_at' => $now,
        'updated_at' => $now
      ]);
    }

    $default_sector = $this->db->order_by('id', 'ASC')->get('sektor_display')->row();
    if ($default_sector) {
      $this->db->where('sektor_id IS NULL', null, false)->update('instansi', ['sektor_id' => $default_sector->id]);
    }
  }

  private function _slugify($text)
  {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text ?: 'sektor';
  }

  private function _generate_unique_sektor_slug($nama, $ignore_id = null)
  {
    $base = $this->_slugify($nama);
    $slug = $base;
    $i = 1;

    while (true) {
      $this->db->from('sektor_display')->where('slug', $slug);
      if ($ignore_id) {
        $this->db->where('id !=', (int) $ignore_id);
      }
      $exists = $this->db->count_all_results();
      if (!$exists) {
        return $slug;
      }
      $slug = $base . '-' . $i;
      $i++;
    }
  }

  private function _ensure_users_layanan_schema()
  {
    if (!$this->db->field_exists('kode_layanan', 'users')) {
      $this->db->query("ALTER TABLE users ADD COLUMN kode_layanan VARCHAR(255) NULL AFTER instansi_id");
    }
  }

  private function _normalize_kode_layanan($instansi_id, $posted_kode_layanan)
  {
    if (empty($instansi_id) || !is_array($posted_kode_layanan)) {
      return null;
    }

    $clean = [];
    foreach ($posted_kode_layanan as $kode) {
      $kode = strtoupper(trim((string) $kode));
      if ($kode !== '') {
        $clean[] = $kode;
      }
    }
    $clean = array_values(array_unique($clean));

    if (empty($clean)) {
      return null; // kosong = semua layanan instansi
    }

    $valid = $this->db
      ->select('kode')
      ->from('jenis_layanan')
      ->where('instansi_id', (int) $instansi_id)
      ->where_in('kode', $clean)
      ->get()
      ->result_array();

    $valid_kode = array_values(array_unique(array_column($valid, 'kode')));
    return empty($valid_kode) ? null : implode(',', $valid_kode);
  }


  public function dashboard()
  {
    $this->load->model('Dashboard_model');

    $data['title'] = "Dashboard Super Admin";
    $data['user'] = $this->session->userdata();

    $data['total_instansi']       = $this->Dashboard_model->count_instansi();
    $data['total_instansi_aktif'] = $this->Dashboard_model->count_instansi_aktif();
    $data['total_instansi_buka']  = $this->Dashboard_model->count_instansi_buka();
    $data['total_layanan']        = $this->Dashboard_model->count_layanan();
    $data['total_admin']          = $this->Dashboard_model->count_admin();
    $data['pending_verification'] = $this->Dashboard_model->count_pending_verification();
    $data['antrian_hari_ini']     = $this->Dashboard_model->count_antrian_today();
    $data['antrian_per_instansi'] = $this->Dashboard_model->get_antrian_today_per_instansi();
    $data['status_hari_ini']      = $this->Dashboard_model->get_today_status_breakdown();
    $data['tren_7_hari']          = $this->Dashboard_model->get_recent_queue_trend(7);
    $data['top_instansi']         = $this->Dashboard_model->get_top_instansi_today(6);

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('superadmin/dashboard', $data);
    $this->load->view('templates/_footer');
  }


  public function users()
  {
    $this->load->library('pagination');

    // Ambil parameter limit, search, dan halaman
    $limit   = (int)($this->input->get('limit') ?? 25);
    $search  = trim($this->input->get('search') ?? '');
    $segment = $this->uri->segment(3);
    $start   = (!empty($segment) && ctype_digit((string)$segment)) ? (int)$segment : 0;

    // Hitung total data
    $this->db->from('users');
    $this->db->where('role_id !=', 3);
    if ($search) {
      $this->db->group_start()
        ->like('nama_lengkap', $search)
        ->or_like('username', $search)
        ->or_like('email', $search)
        ->or_like('nik', $search)
        ->group_end();
    }
    $total_rows = $this->db->count_all_results();

    // Konfigurasi pagination
    $config['base_url'] = base_url('superadmin/users');
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $limit;
    $config['uri_segment'] = 3;

    // Styling pagination bootstrap
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

    // Ambil data user sesuai limit dan pencarian
    $this->db->select('users.*, roles.nama_role, i.nama_instansi, i.kode_instansi');
    $this->db->from('users');
    $this->db->join('roles', 'roles.id = users.role_id', 'left');
    $this->db->join('instansi i', 'i.id = users.instansi_id', 'left');
    $this->db->where('users.role_id !=', 3);

    if ($search) {
      $this->db->group_start()
        ->like('nama_lengkap', $search)
        ->or_like('username', $search)
        ->or_like('email', $search)
        ->or_like('nik', $search)
        ->group_end();
    }

    $this->db->order_by('role_id', 'ASC');
    $this->db->order_by('users.id', 'ASC');

    if ($limit > 0 && $limit < $total_rows) {
      $this->db->limit($limit, $start);
    }

    $users = $this->db->get()->result();


    $data = [
      'title'      => 'Manajemen User',
      'users'      => $users,
      'pagination' => $this->pagination->create_links(),
      'limit'      => $limit,
      'total_rows' => $total_rows,
      'start'      => $start,
      'search'     => $search
    ];

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('superadmin/users', $data);
    $this->load->view('templates/_footer');
  }


  /**
   * AJAX: pencarian user dinamis
   */
public function search_users_ajax()
{
    $keyword = trim($this->input->get('keyword'));

    $this->db->select('users.*, roles.nama_role, i.nama_instansi, i.kode_instansi');
    $this->db->from('users');
    $this->db->join('roles', 'roles.id = users.role_id', 'left');
    $this->db->join('instansi i', 'i.id = users.instansi_id', 'left');
    $this->db->where('users.role_id !=', 3);

    if ($keyword) {
        $this->db->group_start()
            ->like('users.nama_lengkap', $keyword)
            ->or_like('users.username', $keyword)
            ->or_like('users.email', $keyword)
            ->or_like('users.nik', $keyword)
            ->or_like('roles.nama_role', $keyword)
            ->or_like('i.nama_instansi', $keyword)
            ->group_end();
    }

    $this->db->order_by('users.role_id', 'ASC');
    $this->db->order_by('users.id', 'ASC');

    $result = $this->db->get()->result();
    echo json_encode($result);
}

  public function add_user()
  {
    $role_id = (int) $this->input->post('role_id');
    if ($role_id === 3) {
      $this->session->set_flashdata('error', 'Role Customer Service sudah tidak digunakan di tampilan aplikasi.');
      redirect('superadmin/users');
      return;
    }
    $instansi_id = $this->input->post('instansi_id') ?: null;
    if ($role_id !== 2) {
      $instansi_id = null;
    }
    $kode_layanan = ($role_id === 2)
      ? $this->_normalize_kode_layanan($instansi_id, $this->input->post('kode_layanan'))
      : null;

    $data = [
      'nama_lengkap' => $this->input->post('nama_lengkap'),
      'username'     => $this->input->post('username'),
      'password'     => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
      'nik'          => $this->input->post('nik'),
      'alamat'       => $this->input->post('alamat'),
      'email'        => $this->input->post('email'),
      'no_hp'        => $this->input->post('no_hp'),
      'role_id'      => $role_id,
      'instansi_id'  => $instansi_id,
      'kode_layanan' => $kode_layanan,
      'is_verified'  => 1,
      'is_active'    => 1,
      'created_at'   => date('Y-m-d H:i:s')
    ];
    $this->db->insert('users', $data);
    redirect('superadmin/users');
  }


  public function delete_user($id)
  {
    $this->db->delete('users', ['id' => $id]);
    redirect('superadmin/users');
  }

  public function edit_user($id)
  {
    $role_id = (int) $this->input->post('role_id');
    if ($role_id === 3) {
      $this->session->set_flashdata('error', 'Role Customer Service sudah tidak digunakan di tampilan aplikasi.');
      redirect('superadmin/users');
      return;
    }
    $instansi_id = $this->input->post('instansi_id') ?: null;
    if ($role_id !== 2) {
      $instansi_id = null;
    }
    $kode_layanan = ($role_id === 2)
      ? $this->_normalize_kode_layanan($instansi_id, $this->input->post('kode_layanan'))
      : null;

    $data = [
      'nama_lengkap' => $this->input->post('nama_lengkap'),
      'username'     => $this->input->post('username'),
      'nik'          => $this->input->post('nik'),
      'alamat'       => $this->input->post('alamat'),
      'email'        => $this->input->post('email'),
      'no_hp'        => $this->input->post('no_hp'),
      'role_id'      => $role_id,
      'instansi_id'  => $instansi_id,
      'kode_layanan' => $kode_layanan,
      'is_active'    => $this->input->post('is_active'),
      'updated_at'   => date('Y-m-d H:i:s')
    ];
    if ($this->input->post('password')) {
      $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
    }
    $this->db->update('users', $data, ['id' => $id]);
    redirect('superadmin/users');
  }

  public function get_layanan_by_instansi_ajax()
  {
    $instansi_id = (int) $this->input->get('instansi_id');
    if ($instansi_id <= 0) {
      return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([]));
    }

    $result = $this->db
      ->select('kode, nama_layanan')
      ->from('jenis_layanan')
      ->where('instansi_id', $instansi_id)
      ->order_by('nama_layanan', 'ASC')
      ->get()
      ->result();

    return $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($result));
  }

  public function instansi()
  {
    $this->load->library('pagination');
    $this->load->model('Instansi_model');
    $this->_ensure_sector_schema();

    // Ambil parameter filter
    $limit   = (int)($this->input->get('limit') ?? 25);
    $search  = trim($this->input->get('search') ?? '');
    $segment = $this->uri->segment(3);
    $start   = (!empty($segment) && ctype_digit((string)$segment)) ? (int)$segment : 0;

    // Hitung total
    $this->db->from('instansi i');
    $this->db->join('sektor_display s', 's.id = i.sektor_id', 'left');
    if ($search) {
      $this->db->group_start()
        ->like('i.kode_instansi', $search)
        ->or_like('i.nama_instansi', $search)
        ->or_like('i.deskripsi', $search)
        ->or_like('s.nama_sektor', $search)
        ->group_end();
    }
    $total_rows = $this->db->count_all_results();

    // Pagination config
    $config['base_url'] = base_url('superadmin/instansi');
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $limit;
    $config['uri_segment'] = 3;
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

    // Ambil data instansi
    $this->db->select('i.*, s.nama_sektor');
    $this->db->from('instansi i');
    $this->db->join('sektor_display s', 's.id = i.sektor_id', 'left');
    if ($search) {
      $this->db->group_start()
        ->like('i.kode_instansi', $search)
        ->or_like('i.nama_instansi', $search)
        ->or_like('i.deskripsi', $search)
        ->or_like('s.nama_sektor', $search)
        ->group_end();
    }
    $this->db->order_by('i.id', 'DESC');
    if ($limit > 0 && $limit < $total_rows) {
      $this->db->limit($limit, $start);
    }
    $instansi = $this->db->get()->result();

    $data = [
      'title'      => 'Manajemen Instansi',
      'instansi'   => $instansi,
      'sektor_list' => $this->db->order_by('nama_sektor', 'ASC')->get('sektor_display')->result(),
      'pagination' => $this->pagination->create_links(),
      'limit'      => $limit,
      'total_rows' => $total_rows,
      'start'      => $start,
      'search'     => $search
    ];

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar');
    $this->load->view('superadmin/instansi', $data);
    $this->load->view('templates/_footer');
  }

  public function search_instansi_ajax()
  {
    $this->_ensure_sector_schema();
    $keyword = trim($this->input->get('keyword'));
    $this->db->select('i.*, s.nama_sektor');
    $this->db->from('instansi i');
    $this->db->join('sektor_display s', 's.id = i.sektor_id', 'left');
    $this->db->group_start()
      ->like('i.kode_instansi', $keyword)
      ->or_like('i.nama_instansi', $keyword)
      ->or_like('i.deskripsi', $keyword)
      ->or_like('s.nama_sektor', $keyword)
      ->group_end();
    $this->db->order_by('i.id', 'DESC');
    $result = $this->db->get()->result();
    echo json_encode($result);
  }

  public function instansi_add()
  {
    $this->_ensure_sector_schema();
    $operasional = $this->Operasional_model->prepare_operasional_payload($this->input->post(null, true));
    $data = [
      'kode_instansi'    => $this->input->post('kode_instansi'),
      'nama_instansi'    => $this->input->post('nama_instansi'),
      'sektor_id'        => $this->input->post('sektor_id') ?: null,
      'deskripsi'        => $this->input->post('deskripsi'),
      'loket'            => $this->input->post('loket'),
      'is_aktif'        => $this->input->post('is_aktif') ?? 1,
      'created_at'       => date('Y-m-d H:i:s'),
      'updated_at'       => date('Y-m-d H:i:s')
    ];
    $data = array_merge($data, $operasional);
    $this->db->insert('instansi', $data);
    redirect('superadmin/instansi');
  }


  public function instansi_edit($id)
  {
    $this->_ensure_sector_schema();
    $existing = $this->Instansi_model->get_by_id($id);
    $operasional = $this->Operasional_model->prepare_operasional_payload($this->input->post(null, true), $existing);
    $data = [
      'kode_instansi'    => $this->input->post('kode_instansi'),
      'nama_instansi'    => $this->input->post('nama_instansi'),
      'sektor_id'        => $this->input->post('sektor_id') ?: null,
      'deskripsi'        => $this->input->post('deskripsi'),
      'loket'            => $this->input->post('loket'),
      'is_aktif'        => $this->input->post('is_aktif'),
      'updated_at'       => date('Y-m-d H:i:s')
    ];
    $data = array_merge($data, $operasional);
    $this->db->update('instansi', $data, ['id' => $id]);
    redirect('superadmin/instansi');
  }


  public function instansi_delete($id)
  {
    $this->db->delete('instansi', ['id' => $id]);
    redirect('superadmin/instansi');
  }

  public function sektor_display()
  {
    $this->_ensure_sector_schema();

    $data['title'] = 'Pengaturan Sektor Display';
    $data['sektor_list'] = $this->db->order_by('nama_sektor', 'ASC')->get('sektor_display')->result();

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar');
    $this->load->view('superadmin/sektor_display', $data);
    $this->load->view('templates/_footer');
  }

  public function sektor_add()
  {
    $this->_ensure_sector_schema();

    $nama = trim($this->input->post('nama_sektor', true));
    $kode = trim($this->input->post('kode_sektor', true));
    $lokasi = trim($this->input->post('lokasi_display', true));

    if ($nama === '' || $kode === '') {
      $this->session->set_flashdata('error', 'Nama sektor dan kode sektor wajib diisi.');
      redirect('superadmin/sektor_display');
      return;
    }

    $slug = $this->_generate_unique_sektor_slug($nama);
    $now = date('Y-m-d H:i:s');

    $this->db->insert('sektor_display', [
      'kode_sektor' => strtoupper($kode),
      'nama_sektor' => $nama,
      'slug' => $slug,
      'lokasi_display' => $lokasi ?: null,
      'is_aktif' => (int) ($this->input->post('is_aktif') ?? 1),
      'created_at' => $now,
      'updated_at' => $now
    ]);

    $this->session->set_flashdata('success', 'Sektor berhasil ditambahkan.');
    redirect('superadmin/sektor_display');
  }

  public function sektor_edit($id)
  {
    $this->_ensure_sector_schema();
    $id = (int) $id;

    $nama = trim($this->input->post('nama_sektor', true));
    $kode = trim($this->input->post('kode_sektor', true));
    $lokasi = trim($this->input->post('lokasi_display', true));

    if ($nama === '' || $kode === '') {
      $this->session->set_flashdata('error', 'Nama sektor dan kode sektor wajib diisi.');
      redirect('superadmin/sektor_display');
      return;
    }

    $slug = $this->_generate_unique_sektor_slug($nama, $id);

    $this->db->where('id', $id)->update('sektor_display', [
      'kode_sektor' => strtoupper($kode),
      'nama_sektor' => $nama,
      'slug' => $slug,
      'lokasi_display' => $lokasi ?: null,
      'is_aktif' => (int) ($this->input->post('is_aktif') ?? 1),
      'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Sektor berhasil diperbarui.');
    redirect('superadmin/sektor_display');
  }

  public function sektor_delete($id)
  {
    $this->_ensure_sector_schema();
    $id = (int) $id;

    $used = $this->db->where('sektor_id', $id)->count_all_results('instansi');
    if ($used > 0) {
      $this->session->set_flashdata('error', 'Sektor masih dipakai oleh instansi, tidak bisa dihapus.');
      redirect('superadmin/sektor_display');
      return;
    }

    $this->db->delete('sektor_display', ['id' => $id]);
    $this->session->set_flashdata('success', 'Sektor berhasil dihapus.');
    redirect('superadmin/sektor_display');
  }

  private function _ensure_video_setting_schema()
  {
    if (!$this->db->table_exists('video_setting')) {
      return;
    }

    if (!$this->db->field_exists('audio_speed', 'video_setting')) {
      $this->db->query("ALTER TABLE video_setting ADD COLUMN audio_speed DECIMAL(3,2) NOT NULL DEFAULT 1.50");
    }
  }

  private function _get_or_create_video_setting()
  {
    $this->_ensure_video_setting_schema();

    $query = $this->db->get('video_setting');
    if ($query->num_rows() == 0) {
      $this->db->insert('video_setting', [
        'source_type' => 'file',
        'file_path' => null,
        'youtube_url' => null,
        'is_muted' => 1,
        'audio_speed' => 1.50,
        'updated_at' => date('Y-m-d H:i:s')
      ]);

      return (object)[
        'source_type' => 'file',
        'file_path' => null,
        'youtube_url' => null,
        'is_muted' => 1,
        'audio_speed' => 1.50
      ];
    }

    return $query->row();
  }

  public function video_setting()
  {
    $data['title'] = 'Pengaturan Video Layar Antrian';
    $data['video'] = $this->_get_or_create_video_setting();

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar');
    $this->load->view('superadmin/video_setting', $data);
    $this->load->view('templates/_footer');
  }

public function save_video_setting()
{
    $this->_get_or_create_video_setting();
    $source_type = $this->input->post('source_type');
    $is_muted    = $this->input->post('is_muted') ?? 1;
    $youtube_url = $this->input->post('youtube_url');
    $file_path   = null;

    if ($source_type === 'file' && !empty($_FILES['video_file']['name'])) {

        $upload_dir = FCPATH . 'assets/videos/antrian/';

        // 🔍 safety check
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $config['upload_path']      = $upload_dir;
        $config['allowed_types']    = 'mp4|mkv|webm';
        $config['max_size']         = 512000; // 500MB
        $config['overwrite']        = true;
        $config['file_name']        = 'video'; // jadi video.mp4
        $config['file_ext_tolower'] = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('video_file')) {
            $this->session->set_flashdata(
                'error',
                strip_tags($this->upload->display_errors())
            );
            redirect('superadmin/video_setting');
            return;
        }

        $file = $this->upload->data();

        // path yang disimpan ke DB (RELATIF)
        $file_path = 'assets/videos/antrian/' . $file['file_name'];
    }

    $data = [
        'source_type' => $source_type,
        'youtube_url' => $source_type === 'youtube' ? $youtube_url : null,
        'file_path'   => $source_type === 'file' ? $file_path : null,
        'is_muted'    => $is_muted,
        'updated_at'  => date('Y-m-d H:i:s')
    ];

    $this->db->update('video_setting', $data);

    $this->session->set_flashdata(
        'success',
        'Pengaturan video berhasil disimpan.'
    );
    redirect('superadmin/video_setting');
}

public function audio_speed_setting()
{
    $data['title'] = 'Pengaturan Kecepatan Suara';
    $data['video'] = $this->_get_or_create_video_setting();

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar');
    $this->load->view('superadmin/audio_speed_setting', $data);
    $this->load->view('templates/_footer');
}

public function save_audio_speed_setting()
{
    $this->_get_or_create_video_setting();

    $audio_speed = (float) $this->input->post('audio_speed');
    if ($audio_speed < 0.5) {
        $audio_speed = 0.5;
    }
    if ($audio_speed > 3.0) {
        $audio_speed = 3.0;
    }

    $this->db->update('video_setting', [
        'audio_speed' => number_format($audio_speed, 2, '.', ''),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Kecepatan suara berhasil disimpan.');
    redirect('superadmin/audio_speed_setting');
}
public function kelola_layanan()
{
    $data['title'] = 'Kelola Status Layanan';
    $data['instansi_list'] = $this->Instansi_model->get_all();

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('superadmin/kelola_layanan', $data);
    $this->load->view('templates/_footer');
}

/* ===============================
 * UPDATE STATUS LAYANAN
 * =============================== */
public function update_status_layanan()
{
    $mode        = $this->input->post('mode'); // single | all
    $status_mode = $this->input->post('status_layanan_mode');
    if (!$status_mode) {
        $status_mode = $this->input->post('status_layanan');
    }
    $instansi_id = $this->input->post('instansi_id');

    $user_id = $this->session->userdata('id');
    $role_id = $this->session->userdata('role_id');

    if ($mode === 'single') {

        $hasil = $this->Operasional_model->update_instansi_status_mode($instansi_id, $status_mode);
        $status_mode = $hasil['status_layanan_mode'] ?? 'otomatis';
        $status_saat_ini = ucfirst($hasil['status_layanan'] ?? 'tutup');

        if ($status_mode === 'tutup') {
            $jumlah = $this->Instansi_model
                ->batalkan_antrian_aktif($instansi_id, $user_id, $role_id);

            $this->session->set_flashdata(
                'success',
                "Layanan instansi berhasil ditutup. {$jumlah} antrian aktif diselesaikan otomatis."
            );
        } elseif ($status_mode === 'buka') {
            $this->session->set_flashdata('success', 'Layanan dipaksa buka oleh superadmin.');
        } else {
            $this->session->set_flashdata('success', "Mode otomatis disimpan. Status saat ini: {$status_saat_ini}.");
        }

    } elseif ($mode === 'all') {

        $jumlah = $this->Instansi_model
            ->tutup_semua_layanan($user_id, $role_id);

        $this->session->set_flashdata(
            'success',
            "SEMUA layanan ditutup. Total {$jumlah} antrian aktif diselesaikan otomatis."
        );
    }

    redirect('superadmin/kelola_layanan');
}

public function verifikasi_user()
{
    $this->db->select('u.*, r.nama_role, i.nama_instansi');
    $this->db->from('users u');
    $this->db->join('roles r', 'r.id = u.role_id', 'left');
    $this->db->join('instansi i', 'i.id = u.instansi_id', 'left');
    $this->db->where('u.is_verified', 0);
    $this->db->order_by('u.created_at', 'DESC');

    $data['users'] = $this->db->get()->result();
    $data['title'] = 'Verifikasi Akun Pengguna';

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar');
    $this->load->view('superadmin/verifikasi_user', $data);
    $this->load->view('templates/_footer');
}

public function pengaturan_email()
{
    $data['title'] = 'Pengaturan Email Verifikasi';
    $data['settings'] = $this->Email_setting_model->get_settings();
    $data['default_message'] = $this->Email_setting_model->get_default_verification_message();

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('superadmin/pengaturan_email', $data);
    $this->load->view('templates/_footer');
}

public function simpan_pengaturan_email()
{
    $smtp_host = trim((string) $this->input->post('smtp_host'));
    $smtp_port = (int) $this->input->post('smtp_port');
    $smtp_user = trim((string) $this->input->post('smtp_user'));
    $from_email = trim((string) $this->input->post('from_email'));
    $verification_subject = trim((string) $this->input->post('verification_subject'));
    $verification_message = trim((string) $this->input->post('verification_message'));

    if ($smtp_host === '' || $smtp_port <= 0 || $smtp_user === '' || $from_email === '' || $verification_subject === '' || $verification_message === '') {
        $this->session->set_flashdata('error', 'SMTP host, port, akun SMTP, email pengirim, subjek, dan isi verifikasi wajib diisi.');
        redirect('superadmin/pengaturan_email');
        return;
    }

    $this->Email_setting_model->save_settings([
        'smtp_host' => $smtp_host,
        'smtp_port' => $smtp_port,
        'smtp_crypto' => trim((string) $this->input->post('smtp_crypto')),
        'smtp_user' => $smtp_user,
        'smtp_pass' => (string) $this->input->post('smtp_pass'),
        'from_email' => $from_email,
        'from_name' => trim((string) $this->input->post('from_name')),
        'reply_to_email' => trim((string) $this->input->post('reply_to_email')),
        'resend_cooldown_minutes' => (int) $this->input->post('resend_cooldown_minutes'),
        'verification_subject' => $verification_subject,
        'verification_message' => $verification_message
    ]);

    $this->session->set_flashdata('success', 'Pengaturan email verifikasi berhasil disimpan.');
    redirect('superadmin/pengaturan_email');
}

public function verify_user_manual($id)
{
    $this->db->where('id', $id)->update('users', [
        'is_verified'  => 1,
        'verify_token' => null,
        'verification_sent_at' => null,
        'updated_at'   => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Akun berhasil diverifikasi.');
    redirect('superadmin/verifikasi_user');
}
public function resend_verification($id)
{
    $user = $this->db->get_where('users', ['id' => $id])->row();
    if (!$user) show_404();

    $token = bin2hex(random_bytes(32));
    $this->User_model->update_verification_token($id, $token);
    $send = $this->Email_setting_model->send_verification_email($user->email, $token, [
        'nama_lengkap' => $user->nama_lengkap
    ]);

    if ($send['status']) {
        $this->User_model->update_verification_sent_at($id);
        $this->session->set_flashdata('success', 'Email verifikasi berhasil dikirim ulang.');
    } else {
        $this->session->set_flashdata('error', $send['message']);
    }

    redirect('superadmin/verifikasi_user');
}


}
