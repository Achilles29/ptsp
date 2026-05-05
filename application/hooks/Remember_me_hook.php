<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Remember_me_hook
{
  public function auto_login()
  {
    $CI =& get_instance();
    $CI->load->database();
    $CI->load->library('session');
    $CI->load->helper('cookie');

    if ($CI->session->userdata('logged_in')) {
      return;
    }

    if (!$CI->db->field_exists('remember_token_hash', 'users') || !$CI->db->field_exists('remember_token_expires_at', 'users')) {
      return;
    }

    $raw_token = get_cookie('mpp_remember', true);
    if (empty($raw_token) || !preg_match('/^[a-f0-9]{64}$/', $raw_token)) {
      delete_cookie('mpp_remember', '/');
      return;
    }

    $now = date('Y-m-d H:i:s');
    $token_hash = hash('sha256', $raw_token);

    $user = $CI->db
      ->select('u.*, i.nama_instansi')
      ->from('users u')
      ->join('instansi i', 'i.id = u.instansi_id', 'left')
      ->where('u.remember_token_hash', $token_hash)
      ->where('u.remember_token_expires_at >=', $now)
      ->where('u.is_active', 1)
      ->get()
      ->row();

    if (!$user) {
      delete_cookie('mpp_remember', '/');
      return;
    }

    $CI->session->set_userdata([
      'user_id'       => $user->id,
      'username'      => $user->username,
      'nama_lengkap'  => $user->nama_lengkap,
      'role_id'       => $user->role_id,
      'nama_instansi' => $user->nama_instansi,
      'instansi_id'   => $user->instansi_id,
      'kode_layanan'  => $user->kode_layanan ?? null,
      'logged_in'     => true
    ]);

    // Rotate token saat auto-login agar lebih aman.
    $new_raw_token = bin2hex(random_bytes(32));
    $CI->db->where('id', (int) $user->id)->update('users', [
      'remember_token_hash'       => hash('sha256', $new_raw_token),
      'remember_token_expires_at' => date('Y-m-d H:i:s', strtotime('+5 years')),
      'updated_at'                => date('Y-m-d H:i:s')
    ]);

    set_cookie([
      'name'     => 'mpp_remember',
      'value'    => $new_raw_token,
      'expire'   => 60 * 60 * 24 * 365 * 5,
      'path'     => '/',
      'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
      'httponly' => true,
      'samesite' => 'Lax'
    ]);
  }
}
