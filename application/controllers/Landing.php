<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Landing extends CI_Controller
{

    public function index()
    {
        $data['title'] = 'Antrian Online MPP Kabupaten Rembang';
        $data['ig_username'] = 'mpp_rembang'; // ubah sesuai username Instagram resmi
        $this->load->view('landing/index', $data);
    }
}
