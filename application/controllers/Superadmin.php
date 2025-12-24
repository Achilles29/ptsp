<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Superadmin extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->_check_access(1); // Superadmin
  }

  private function _check_access($role_id)
  {
    if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != $role_id) {
      redirect('auth');
    }
  }


  public function dashboard()
  {
    $this->load->model('Dashboard_model');

    $data['title'] = "Dashboard Super Admin";
    $data['user'] = $this->session->userdata();

    // Ambil ringkasan data
    $data['total_instansi']       = $this->Dashboard_model->count_instansi();
    $data['total_layanan']        = $this->Dashboard_model->count_layanan();
    $data['total_admin']          = $this->Dashboard_model->count_admin();
    $data['total_cs']             = $this->Dashboard_model->count_cs();
    $data['antrian_hari_ini']     = $this->Dashboard_model->count_antrian_today();
    $data['antrian_per_instansi'] = $this->Dashboard_model->get_antrian_today_per_instansi();

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar', $data);
    $this->load->view('superadmin/dashboard', $data);
    $this->load->view('templates/_footer');
  }


  public function users()
  {
    $this->load->library('pagination');

    // Ambil parameter limit, search, dan halaman
    $limit   = (int)($this->input->get('limit') ?? 10);
    $search  = trim($this->input->get('search') ?? '');
    $segment = $this->uri->segment(3);
    $start   = (!empty($segment) && ctype_digit((string)$segment)) ? (int)$segment : 0;

    // Hitung total data
    $this->db->from('users'); // <== penting agar tidak double table
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
    $this->db->select('users.*, roles.nama_role');
    $this->db->from('users');
    $this->db->join('roles', 'roles.id = users.role_id', 'left'); // Tambahkan join ke tabel roles

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
    $this->load->view('templates/_sidebar');
    $this->load->view('superadmin/users', $data);
    $this->load->view('templates/_footer');
  }


  /**
   * AJAX: pencarian user dinamis
   */
  public function search_users_ajax()
  {
    $keyword = trim($this->input->get('keyword'));
    $this->db->group_start()
      ->like('nama_lengkap', $keyword)
      ->or_like('username', $keyword)
      ->or_like('email', $keyword)
      ->or_like('nik', $keyword)
      ->group_end()
      ->order_by('role_id', 'ASC')
      ->order_by('id', 'ASC');
    $result = $this->db->get('users')->result();
    echo json_encode($result);
  }

  public function add_user()
  {
    $data = [
      'nama_lengkap' => $this->input->post('nama_lengkap'),
      'username'     => $this->input->post('username'),
      'password'     => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
      'nik'          => $this->input->post('nik'),
      'alamat'       => $this->input->post('alamat'),
      'email'        => $this->input->post('email'),
      'no_hp'        => $this->input->post('no_hp'),
      'role_id'      => $this->input->post('role_id'),
      'instansi_id'   => $this->input->post('instansi_id') ?: null,
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
    $data = [
      'nama_lengkap' => $this->input->post('nama_lengkap'),
      'username'     => $this->input->post('username'),
      'nik'          => $this->input->post('nik'),
      'alamat'       => $this->input->post('alamat'),
      'email'        => $this->input->post('email'),
      'no_hp'        => $this->input->post('no_hp'),
      'role_id'      => $this->input->post('role_id'),
      'instansi_id'   => $this->input->post('instansi_id') ?: null,
      'is_active'    => $this->input->post('is_active'),
      'updated_at'   => date('Y-m-d H:i:s')
    ];
    if ($this->input->post('password')) {
      $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
    }
    $this->db->update('users', $data, ['id' => $id]);
    redirect('superadmin/users');
  }

  public function instansi()
  {
    $this->load->library('pagination');
    $this->load->model('Instansi_model');

    // Ambil parameter filter
    $limit   = (int)($this->input->get('limit') ?? 10);
    $search  = trim($this->input->get('search') ?? '');
    $segment = $this->uri->segment(3);
    $start   = (!empty($segment) && ctype_digit((string)$segment)) ? (int)$segment : 0;

    // Hitung total
    $this->db->from('instansi');
    if ($search) {
      $this->db->group_start()
        ->like('kode_instansi', $search)
        ->or_like('nama_instansi', $search)
        ->or_like('deskripsi', $search)
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
    $this->db->from('instansi');
    if ($search) {
      $this->db->group_start()
        ->like('kode_instansi', $search)
        ->or_like('nama_instansi', $search)
        ->or_like('deskripsi', $search)
        ->group_end();
    }
    $this->db->order_by('id', 'DESC');
    if ($limit > 0 && $limit < $total_rows) {
      $this->db->limit($limit, $start);
    }
    $instansi = $this->db->get()->result();

    $data = [
      'title'      => 'Manajemen Instansi',
      'instansi'   => $instansi,
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
    $keyword = trim($this->input->get('keyword'));
    $this->db->group_start()
      ->like('kode_instansi', $keyword)
      ->or_like('nama_instansi', $keyword)
      ->or_like('deskripsi', $keyword)
      ->group_end()
      ->order_by('id', 'DESC');
    $result = $this->db->get('instansi')->result();
    echo json_encode($result);
  }

  public function instansi_add()
  {
    $data = [
      'kode_instansi'    => $this->input->post('kode_instansi'),
      'nama_instansi'    => $this->input->post('nama_instansi'),
      'deskripsi'        => $this->input->post('deskripsi'),
      'loket'            => $this->input->post('loket'),
      'status_layanan'   => $this->input->post('status_layanan') ?? 'buka',
      'is_aktif'        => $this->input->post('is_aktif') ?? 1,
      'created_at'       => date('Y-m-d H:i:s'),
      'updated_at'       => date('Y-m-d H:i:s')
    ];
    $this->db->insert('instansi', $data);
    redirect('superadmin/instansi');
  }


  public function instansi_edit($id)
  {
    $data = [
      'kode_instansi'    => $this->input->post('kode_instansi'),
      'nama_instansi'    => $this->input->post('nama_instansi'),
      'deskripsi'        => $this->input->post('deskripsi'),
      'loket'            => $this->input->post('loket'),
      'status_layanan'   => $this->input->post('status_layanan'),
      'is_aktif'        => $this->input->post('is_aktif'),
      'updated_at'       => date('Y-m-d H:i:s')
    ];
    $this->db->update('instansi', $data, ['id' => $id]);
    redirect('superadmin/instansi');
  }


  public function instansi_delete($id)
  {
    $this->db->delete('instansi', ['id' => $id]);
    redirect('superadmin/instansi');
  }

  public function video_setting()
  {
    $data['title'] = 'Pengaturan Video Layar Antrian';

    // Cek apakah ada data video_setting
    $query = $this->db->get('video_setting');
    if ($query->num_rows() == 0) {
      // buat data default
      $this->db->insert('video_setting', [
        'source_type' => 'file',
        'file_path' => null,
        'youtube_url' => null,
        'updated_at' => date('Y-m-d H:i:s')
      ]);
      $data['video'] = (object)[
        'source_type' => 'file',
        'file_path' => null,
        'youtube_url' => null
      ];
    } else {
      $data['video'] = $query->row();
    }

    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar');
    $this->load->view('superadmin/video_setting', $data);
    $this->load->view('templates/_footer');
  }

  public function save_video_setting()
  {
    $source_type = $this->input->post('source_type');
    $is_muted    = $this->input->post('is_muted') ?? 1; // default muted
    $youtube_url = $this->input->post('youtube_url');
    $file_path   = null;

    if ($source_type == 'file' && !empty($_FILES['video_file']['name'])) {
      $config['upload_path']   = './uploads/video/';
      $config['allowed_types'] = 'mp4|mkv';
      $config['max_size']      = 512000; // 500MB
      $this->load->library('upload', $config);

      if ($this->upload->do_upload('video_file')) {
        $file_path = 'uploads/video/' . $this->upload->data('file_name');
      } else {
        $this->session->set_flashdata('error', $this->upload->display_errors());
        redirect('superadmin/video_setting');
        return;
      }
    }

    $data = [
      'source_type' => $source_type,
      'youtube_url' => $source_type == 'youtube' ? $youtube_url : null,
      'file_path'   => $source_type == 'file' ? $file_path : null,
      'is_muted'    => $is_muted,
      'updated_at'  => date('Y-m-d H:i:s')
    ];

    $this->db->update('video_setting', $data);
    $this->session->set_flashdata('success', 'Pengaturan video berhasil disimpan.');
    redirect('superadmin/video_setting');
  }
}
