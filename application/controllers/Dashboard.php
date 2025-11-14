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

        // Menentukan tampilan berdasarkan role
        switch ($role_id) {
            case 1:
                $view = 'dashboard/superadmin';
                break;
            case 2:
                $view = 'admin_layanan/dashboard';
                break;
            case 3:
                $view = 'dashboard/cs_layanan';
                break;
            case 4:
                $view = 'dashboard/masyarakat';
                break;
            default:
                show_error("Role tidak dikenal.");
                return;
        }

        $this->load->view('templates/_header', $data);
        $this->load->view('templates/_sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('templates/_footer');
    }
}
