<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model(['User_model', 'Email_setting_model']);
    $this->load->library(['session', 'email']);
    $this->load->helper(['url', 'form', 'cookie']);
    $this->load->database();
    $this->_ensure_remember_schema();
    $this->Email_setting_model->ensure_schema();
  }

  private function _ensure_remember_schema()
  {
    if (!$this->db->field_exists('remember_token_hash', 'users')) {
      $this->db->query("ALTER TABLE users ADD COLUMN remember_token_hash VARCHAR(64) NULL AFTER kode_layanan");
    }
    if (!$this->db->field_exists('remember_token_expires_at', 'users')) {
      $this->db->query("ALTER TABLE users ADD COLUMN remember_token_expires_at DATETIME NULL AFTER remember_token_hash");
    }
  }

  private function _set_user_session($user)
  {
    $this->session->set_userdata([
      'user_id'       => $user->id,
      'username'      => $user->username,
      'nama_lengkap'  => $user->nama_lengkap,
      'role_id'       => $user->role_id,
      'nama_instansi' => $user->nama_instansi,
      'instansi_id'   => $user->instansi_id,
      'kode_layanan'  => $user->kode_layanan ?? null,
      'logged_in'     => true
    ]);
  }

  private function _issue_remember_cookie($user_id)
  {
    $raw_token = bin2hex(random_bytes(32));
    $token_hash = hash('sha256', $raw_token);
    $expired_at = date('Y-m-d H:i:s', strtotime('+5 years'));

    $this->db->where('id', (int) $user_id)->update('users', [
      'remember_token_hash'       => $token_hash,
      'remember_token_expires_at' => $expired_at,
      'updated_at'                => date('Y-m-d H:i:s')
    ]);

    set_cookie([
      'name'     => 'mpp_remember',
      'value'    => $raw_token,
      'expire'   => 60 * 60 * 24 * 365 * 5,
      'path'     => '/',
      'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
      'httponly' => true,
      'samesite' => 'Lax'
    ]);
  }

  private function _clear_remember_cookie($user_id = null)
  {
    if ($user_id) {
      $this->db->where('id', (int) $user_id)->update('users', [
        'remember_token_hash'       => null,
        'remember_token_expires_at' => null,
        'updated_at'                => date('Y-m-d H:i:s')
      ]);
    }

    delete_cookie('mpp_remember', '/');
  }

  private function _auto_login_from_cookie()
  {
    if ($this->session->userdata('logged_in')) {
      return false;
    }

    $raw_token = get_cookie('mpp_remember', true);
    if (empty($raw_token) || !preg_match('/^[a-f0-9]{64}$/', $raw_token)) {
      return false;
    }

    $token_hash = hash('sha256', $raw_token);
    $now = date('Y-m-d H:i:s');

    $user = $this->db
      ->select('u.*, i.nama_instansi')
      ->from('users u')
      ->join('instansi i', 'i.id = u.instansi_id', 'left')
      ->where('u.remember_token_hash', $token_hash)
      ->where('u.remember_token_expires_at >=', $now)
      ->where('u.is_active', 1)
      ->get()->row();

    if (!$user) {
      $this->_clear_remember_cookie();
      return false;
    }

    $this->_set_user_session($user);
    $this->_issue_remember_cookie($user->id); // rotate token
    return true;
  }

  public function login()
  {
    if ($this->session->userdata('logged_in')) {
      $role_id = (int) $this->session->userdata('role_id');
      switch ($role_id) {
        case 1: redirect('superadmin/dashboard'); break;
        case 2: redirect('admin_layanan/dashboard'); break;
        case 3: redirect('dashboard'); break;
        case 4: redirect('masyarakat/dashboard'); break;
        default: break;
      }
    }

    if ($this->_auto_login_from_cookie()) {
      $role_id = (int) $this->session->userdata('role_id');
      switch ($role_id) {
        case 1: redirect('superadmin/dashboard'); break;
        case 2: redirect('admin_layanan/dashboard'); break;
        case 3: redirect('dashboard'); break;
        case 4: redirect('masyarakat/dashboard'); break;
        default: break;
      }
    }

    $this->load->library('form_validation');
    $this->form_validation->set_rules('username', 'Username', 'required|trim');
    $this->form_validation->set_rules('password', 'Password', 'required');

    if ($this->form_validation->run() === FALSE) {
      $this->load->view('auth/login', [
        'resend_cooldown_minutes' => $this->Email_setting_model->get_resend_cooldown_minutes()
      ]);
    } else {
      $username = $this->input->post('username');
      $password = $this->input->post('password');
      $remember = (bool) $this->input->post('remember_me');

      // $user = $this->db->get_where('users', ['username' => $username])->row();
      $user = $this->db
        ->select('u.*, i.nama_instansi')
        ->from('users u')
        ->join('instansi i', 'i.id = u.instansi_id', 'left')
        ->where('u.username', $username)
        ->get()->row();

      if (!$user) {
        $this->session->set_flashdata('error', 'Akun tidak ditemukan.');
        redirect('auth/login');
      }

      if (!password_verify($password, $user->password)) {
        $this->session->set_flashdata('error', 'Password salah.');
        redirect('auth/login');
      }

      if (isset($user->is_verified) && !$user->is_verified) {
        $this->session->set_flashdata('error', 'Akun belum diverifikasi. Cek inbox dan folder spam Anda, lalu gunakan fitur kirim ulang jika perlu.');
        $this->session->set_flashdata('unverified_email', $user->email);
        redirect('auth/login');
      }

      if (isset($user->is_active) && !$user->is_active) {
        $this->session->set_flashdata('error', 'Akun Anda nonaktif.');
        redirect('auth/login');
      }

      // Simpan session
      $this->_set_user_session($user);

      if ($remember) {
        $this->_issue_remember_cookie($user->id);
      } else {
        $this->_clear_remember_cookie($user->id);
      }

      // Redirect sesuai role
      switch ($user->role_id) {
        case 1:
          redirect('superadmin/dashboard');
          break;
        case 2:
          redirect('admin_layanan/dashboard');
          break;
        case 3:
          redirect('dashboard');
          break;
        case 4:
          redirect('masyarakat/dashboard');
          break;
        default:
          $this->session->set_flashdata('error', 'Role tidak dikenali.');
          redirect('auth/login');
      }
    }
  }

  public function register()
  {
    if ($this->input->post()) {
      $token = bin2hex(random_bytes(32));
      $input = [
        'nama_lengkap' => trim((string) $this->input->post('nama_lengkap')),
        'nik'          => trim((string) $this->input->post('nik')),
        'alamat'       => trim((string) $this->input->post('alamat')),
        'no_hp'        => trim((string) $this->input->post('no_hp')),
        'email'        => trim((string) $this->input->post('email')),
        'username'     => trim((string) $this->input->post('username')),
        'password'     => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        'role_id'      => 4,
        'instansi_id'  => null,
        'is_verified'  => 0,
        'verify_token' => $token,
        'verification_sent_at' => null,
        'created_at'   => date('Y-m-d H:i:s'),
        'updated_at'   => null
      ];

      // Cek duplikat
      $errors = [];
      if ($this->User_model->username_exists($input['username'])) $errors[] = 'Username';
      if ($this->User_model->email_exists($input['email'])) $errors[] = 'Email';
      if ($this->User_model->nik_exists($input['nik'])) $errors[] = 'NIK';
      if ($this->User_model->no_hp_exists($input['no_hp'])) $errors[] = 'No HP';

      if (!empty($errors)) {
        $message = implode(', ', $errors) . ' sudah terdaftar.';
        $this->session->set_flashdata('error', $message);
        redirect('auth/register');
      }

      // Simpan
      $insert = $this->User_model->insert($input);
      if ($insert) {
        $send = $this->Email_setting_model->send_verification_email($input['email'], $input['verify_token'], [
          'nama_lengkap' => $input['nama_lengkap']
        ]);

        if ($send['status']) {
          $this->User_model->update_verification_sent_at($insert);
        }

        $superadmin = $this->User_model->get_superadmin();
        $wa_link = null;
        if ($superadmin && !empty($superadmin->no_hp)) {
          $hp = preg_replace('/[^0-9]/', '', $superadmin->no_hp);
          if (substr($hp, 0, 1) == '0') {
            $hp = '62' . substr($hp, 1);
          }
          $wa_link = 'https://wa.me/' . $hp;
        }

        $cooldown = $this->Email_setting_model->get_resend_cooldown_minutes();
        if ($send['status']) {
          $pesan = 'Pendaftaran berhasil. Email verifikasi sudah dikirim. Periksa inbox dan folder spam Anda.';
          $pesan .= '<br>Jika email belum diterima, Anda bisa kirim ulang setelah ' . $cooldown . ' menit.';
        } else {
          $pesan = 'Akun berhasil dibuat, tetapi email verifikasi belum berhasil dikirim.';
          $pesan .= '<br>' . $send['message'];
          $pesan .= '<br>Silakan simpan email Anda dan gunakan fitur kirim ulang verifikasi di bawah.';
        }

        if ($wa_link) {
          $pesan .= '<br>Jika masih mengalami kendala, silakan hubungi <a href="' . $wa_link . '" target="_blank">admin</a>.';
        }

        $this->session->set_flashdata('success', $pesan);
        $this->session->set_flashdata('verification_email', $input['email']);
        redirect('auth/register');
      } else {
        $this->session->set_flashdata('error', 'Gagal mendaftar.');
        redirect('auth/register');
      }
    } else {
      $this->load->view('auth/register', [
        'resend_cooldown_minutes' => $this->Email_setting_model->get_resend_cooldown_minutes()
      ]);
    }
  }

  public function resend_verification()
  {
    $email = trim((string) $this->input->post('email'));
    $source = trim((string) $this->input->post('source'));
    $redirect = $source === 'login' ? 'auth/login' : 'auth/register';

    if ($email === '') {
      $this->session->set_flashdata('error', 'Masukkan email yang ingin dikirimi ulang verifikasi.');
      redirect($redirect);
    }

    $user = $this->User_model->get_by_email($email);
    if (!$user) {
      $this->session->set_flashdata('error', 'Email belum terdaftar di sistem.');
      redirect($redirect);
    }

    if ((int) $user->is_verified === 1) {
      $this->session->set_flashdata('success', 'Akun dengan email tersebut sudah terverifikasi. Silakan login.');
      redirect($redirect);
    }

    $remaining_seconds = $this->Email_setting_model->get_remaining_resend_seconds($user->verification_sent_at ?? null);
    if ($remaining_seconds > 0) {
      $minutes = floor($remaining_seconds / 60);
      $seconds = $remaining_seconds % 60;
      $wait_label = $minutes > 0
        ? $minutes . ' menit ' . str_pad((string) $seconds, 2, '0', STR_PAD_LEFT) . ' detik'
        : $seconds . ' detik';
      $this->session->set_flashdata('error', 'Email verifikasi baru saja dikirim. Silakan tunggu ' . $wait_label . ' lagi sebelum mencoba kirim ulang.');
      $this->session->set_flashdata('verification_email', $email);
      redirect($redirect);
    }

    $token = bin2hex(random_bytes(32));
    $this->User_model->update_verification_token($user->id, $token);

    $send = $this->Email_setting_model->send_verification_email($user->email, $token, [
      'nama_lengkap' => $user->nama_lengkap
    ]);

    if ($send['status']) {
      $this->User_model->update_verification_sent_at($user->id);
      $this->session->set_flashdata('success', 'Email verifikasi berhasil dikirim ulang. Periksa inbox dan folder spam Anda.');
    } else {
      $this->session->set_flashdata('error', $send['message']);
    }

    $this->session->set_flashdata('verification_email', $email);
    redirect($redirect);
  }

  public function verify_email($token)
  {
    $user = $this->User_model->get_by_token($token);

    if ($user) {
      $this->User_model->verify_user($user->id);
      $this->session->set_flashdata('success', 'Akun berhasil diverifikasi. Silakan login.');
      redirect('auth/login');
    } else {
      $this->session->set_flashdata('error', 'Token verifikasi tidak valid.');
      redirect('auth/login');
    }
  }

  public function logout()
  {
    $user_id = $this->session->userdata('user_id');
    $this->_clear_remember_cookie($user_id);
    $this->session->sess_destroy();
    redirect('auth/login');
  }
  public function forgot_password()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

    if ($this->form_validation->run() === FALSE) {
      $this->load->view('auth/forgot_password', [
        'resend_cooldown_minutes' => $this->Email_setting_model->get_resend_cooldown_minutes()
      ]);
    } else {
      $email = $this->input->post('email');
      $user = $this->User_model->get_by_email($email);

      if (!$user) {
        $this->session->set_flashdata('error', 'Email tidak ditemukan.');
        redirect('auth/forgot_password');
      }

      // Buat password baru random
      $new_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
      $hashed = password_hash($new_password, PASSWORD_DEFAULT);

      // Simpan password baru
      $this->db->where('id', $user->id)->update('users', ['password' => $hashed]);

      // Kirim email
      $send = $this->Email_setting_model->send_reset_password_email($user->email, $user->nama_lengkap, $new_password);

      if (!$send['status']) {
        $this->session->set_flashdata('error', $send['message']);
        redirect('auth/forgot_password');
      }

      $this->session->set_flashdata('success', 'Password baru telah dikirim ke email Anda.');
      redirect('auth/forgot_password');
    }
  }
}
