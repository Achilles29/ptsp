<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->session->userdata();
        $role_id = $data['user']['role_id'];

        switch ($role_id) {
            case 1:
                redirect('superadmin/dashboard');
                return;
            case 2:
                redirect('admin_layanan/dashboard');
                return;
            case 3:
                $data['title'] = 'Modul Internal';
                break;
            case 4:
                redirect('masyarakat/dashboard');
                return;
            default:
                show_error("Role tidak dikenal.");
                return;
        }

        $this->load->view('templates/_header', $data);
        $this->load->view('templates/_sidebar', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/_footer');
    }
}
