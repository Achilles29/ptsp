<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
  private $table = 'users';

  public function insert($data)
  {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  public function get_by_username($username)
  {
    return $this->db->get_where($this->table, ['username' => $username])->row();
  }

  public function username_exists($username)
  {
    return $this->db->where('username', $username)->count_all_results($this->table) > 0;
  }


  public function email_exists($email)
  {
    return $this->db->where('email', $email)->count_all_results($this->table) > 0;
  }

  public function nik_exists($nik)
  {
    return $this->db->where('nik', $nik)->count_all_results('users') > 0;
  }

  public function no_hp_exists($no_hp)
  {
    return $this->db->where('no_hp', $no_hp)->count_all_results('users') > 0;
  }

  public function get_superadmin()
  {
    return $this->db->where('role_id', 1)->order_by('id')->get('users')->row();
  }


  public function get_by_token($token)
  {
    return $this->db->get_where($this->table, ['verify_token' => $token])->row();
  }

  public function verify_user($id)
  {
    $this->db->where('id', $id)
      ->update($this->table, ['is_verified' => 1, 'verify_token' => NULL]);
  }

  public function update_last_login($id)
  {
    $this->db->where('id', $id)
      ->update($this->table, ['updated_at' => date('Y-m-d H:i:s')]);
  }


  public function count_filtered_users($search)
  {
    if ($search) {
      $this->db->group_start();
      $this->db->like('nama_lengkap', $search);
      $this->db->or_like('username', $search);
      $this->db->or_like('email', $search);
      $this->db->or_like('nik', $search);
      $this->db->or_like('alamat', $search);
      $this->db->group_end();
    }
    return $this->db->count_all_results('users');
  }
}
