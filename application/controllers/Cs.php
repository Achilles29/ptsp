<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cs extends CI_Controller {
  public function __construct() {
    parent::__construct();
    if (!$this->session->userdata('logged_in')) {
      redirect('auth/login');
    }
  }

  public function dashboard() {
    $this->session->set_flashdata('error', 'Modul Customer Service tidak lagi digunakan pada tampilan aplikasi ini.');
    redirect('dashboard');
  }
}
