<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jenislayanan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $this->load->model('Jenislayanan_model');
    }

    public function index()
    {
        $this->load->library('pagination');
        $this->load->model('Instansi_model');

        $limit   = (int)($this->input->get('limit') ?? 10);
        $search  = trim($this->input->get('search') ?? '');
        $segment = $this->uri->segment(3);
        $start   = (!empty($segment) && ctype_digit((string)$segment)) ? (int)$segment : 0;

        // Hitung total data
        $this->db->from('jenis_layanan jl');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        if ($search) {
            $this->db->group_start()
                ->like('jl.kode', $search)
                ->or_like('jl.kode_huruf', $search)
                ->or_like('jl.nama_layanan', $search)
                ->or_like('i.nama_instansi', $search)
                ->group_end();
        }
        $total_rows = $this->db->count_all_results();

        // Pagination config
        $config['base_url'] = base_url('jenislayanan/index');
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

        // Ambil data utama (dengan JOIN instansi)
        $this->db->select('jl.*, i.nama_instansi, i.kode_instansi');
        $this->db->from('jenis_layanan jl');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        if ($search) {
            $this->db->group_start()
                ->like('jl.kode', $search)
                ->or_like('jl.kode_huruf', $search)
                ->or_like('jl.nama_layanan', $search)
                ->or_like('i.nama_instansi', $search)
                ->group_end();
        }
        $this->db->order_by('jl.id', 'ASC');
        if ($limit > 0 && $limit < $total_rows) {
            $this->db->limit($limit, $start);
        }
        $jenis_layanan = $this->db->get()->result();

        $data = [
            'title' => 'Manajemen Jenis Layanan',
            'jenis_layanan' => $jenis_layanan,
            'pagination' => $this->pagination->create_links(),
            'limit' => $limit,
            'total_rows' => $total_rows,
            'start' => $start,
            'search' => $search,
            'instansi' => $this->Instansi_model->get_all()
        ];

        $this->load->view('templates/_header', $data);
        $this->load->view('templates/_sidebar');
        $this->load->view('jenis_layanan/index', $data);
        $this->load->view('templates/_footer');
    }

    public function simpan()
    {
        if ($this->session->userdata('role_id') != 1) {
            show_error('Akses ditolak: hanya Superadmin yang boleh menyimpan data', 403);
        }
        $this->Jenislayanan_model->simpan_data();
        redirect('jenislayanan');
    }

    public function jenis_layanan_delete($id)
    {
        if ($this->session->userdata('role_id') != 1) {
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            return;
        }

        if ($this->db->delete('jenis_layanan', ['id' => $id])) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data.']);
        }
    }

    public function get_by_id($id)
    {
        $data = $this->Jenislayanan_model->get_by_id($id);
        echo json_encode($data);
    }

    public function search_ajax()
    {
        $keyword = trim($this->input->get('keyword'));
        $result = $this->Jenislayanan_model->get_all($keyword);
        echo json_encode($result);
    }
    public function by_instansi($kode_instansi)
    {
        $data = $this->Jenislayanan_model->get_by_instansi($kode_instansi);
        echo json_encode($data);
    }
}
