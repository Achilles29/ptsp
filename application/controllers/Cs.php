<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cs extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->_check_access(3); // CS layanan
  }

  private function _check_access($role_id) {
    if (!$this->session->userdata('is_logged_in') || $this->session->userdata('role_id') != $role_id) {
      redirect('auth');
    }
  }

  public function dashboard() {
    $data['title'] = "Dashboard Customer Service";
    $this->load->view('templates/_header', $data);
    $this->load->view('templates/_sidebar');
    $this->load->view('cs/dashboard', $data);
    $this->load->view('templates/_footer');
  }
}
