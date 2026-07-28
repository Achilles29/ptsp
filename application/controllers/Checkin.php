<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checkin extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('session');
    $this->load->helper('url');
  }

  public function index()
  {
    if (!$this->session->userdata('logged_in')) {
      $this->session->set_userdata('after_login_redirect', 'masyarakat/checkin_qr');
      $this->session->set_flashdata('error', 'Silakan login terlebih dahulu untuk melakukan check-in.');
      redirect('auth/login');
      return;
    }

    if ((int) $this->session->userdata('role_id') !== 4) {
      $this->session->set_flashdata('error', 'Check-in QR hanya dapat digunakan oleh akun masyarakat.');
      redirect('auth/login');
      return;
    }

    redirect('masyarakat/checkin_qr');
  }
}
