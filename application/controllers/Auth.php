<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('User_model');
    $this->load->library(['session', 'email']);
    $this->load->helper(['url', 'form']);
    $this->load->database();
  }

  public function login()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('username', 'Username', 'required|trim');
    $this->form_validation->set_rules('password', 'Password', 'required');

    if ($this->form_validation->run() === FALSE) {
      $this->load->view('auth/login');
    } else {
      $username = $this->input->post('username');
      $password = $this->input->post('password');

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
        $this->session->set_flashdata('error', 'Akun belum diverifikasi. Cek email Anda.');
        redirect('auth/login');
      }

      if (isset($user->is_active) && !$user->is_active) {
        $this->session->set_flashdata('error', 'Akun Anda nonaktif.');
        redirect('auth/login');
      }

      // Simpan session
      $this->session->set_userdata([
        'user_id'       => $user->id,
        'username'      => $user->username,
        'nama_lengkap'  => $user->nama_lengkap,
        'role_id'       => $user->role_id,
        'nama_instansi' => $user->nama_instansi,
        'instansi_id'   => $user->instansi_id,
        'logged_in'     => true
      ]);

      // Redirect sesuai role
      switch ($user->role_id) {
        case 1:
          redirect('superadmin/dashboard');
          break;
        case 2:
          redirect('admin_layanan/dashboard');
          break;
        case 3:
          redirect('cs/dashboard');
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
      $input = [
        'nama_lengkap' => $this->input->post('nama_lengkap'),
        'nik'          => $this->input->post('nik'),
        'alamat'       => $this->input->post('alamat'),
        'no_hp'        => $this->input->post('no_hp'),
        'email'        => $this->input->post('email'),
        'username'     => $this->input->post('username'),
        'password'     => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        'role_id'      => 4,
        'instansi_id'  => null,
        'is_verified'  => 0,
        'verify_token' => md5(uniqid()),
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
        $this->_send_verification_email($input['email'], $input['verify_token']);
        $superadmin = $this->User_model->get_superadmin();
        // Ubah format no_hp ke format internasional 62xxxxxxxxx
        $hp = preg_replace('/[^0-9]/', '', $superadmin->no_hp);
        if (substr($hp, 0, 1) == '0') {
          $hp = '62' . substr($hp, 1); // ganti 08xxxx → 628xxxx
        }
        $wa_link = 'https://wa.me/' . $hp;

        $pesan = 'Pendaftaran berhasil. Periksa email (termasuk folder spam) untuk verifikasi.<br>Jika mengalami kesulitan, silakan hubungi <a href="' . $wa_link . '" target="_blank">admin</a>.';
        $this->session->set_flashdata('success', $pesan);
        redirect('auth/register');
      } else {
        $this->session->set_flashdata('error', 'Gagal mendaftar.');
        redirect('auth/register');
      }
    } else {
      $this->load->view('auth/register');
    }
  }

  private function _send_verification_email($email, $token)
  {
    $this->email->from('akunanda@gmail.com', 'Admin Antrian DPMPTSP');
    $this->email->to($email);
    $this->email->subject('Verifikasi Akun Anda');

    $link = base_url("auth/verify_email/$token");
    $message = "Terima kasih telah mendaftar. Klik link berikut untuk verifikasi akun Anda:<br><br>";
    $message .= "<a href='$link'>$link</a>";

    $this->email->message($message);
    $this->email->send();
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
    $this->session->sess_destroy();
    redirect('auth/login');
  }
  public function forgot_password()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

    if ($this->form_validation->run() === FALSE) {
      $this->load->view('auth/forgot_password');
    } else {
      $email = $this->input->post('email');
      $user = $this->db->get_where('users', ['email' => $email])->row();

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
      $this->_send_email_reset_password($user->email, $user->nama_lengkap, $new_password);

      $this->session->set_flashdata('success', 'Password baru telah dikirim ke email Anda.');
      redirect('auth/forgot_password');
    }
  }

  private function _send_email_reset_password($email, $nama, $password_baru)
  {
    $this->load->library('email');
    $this->email->from('noreply@namua.com', 'Aplikasi Antrian DPMPTSP');
    $this->email->to($email);

    $this->email->subject('Password Baru Anda');
    $this->email->message("Halo $nama,\n\nBerikut adalah password baru Anda:\n\nPassword: $password_baru\n\nSilakan login dan segera ganti password Anda setelah berhasil masuk.\n\nTerima kasih.");

    $this->email->send();
  }
}
