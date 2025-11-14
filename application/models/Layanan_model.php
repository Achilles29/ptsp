<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Layanan_model extends CI_Model
{
  public function get_all()
  {
    return $this->db->get('jenis_layanan')->result();
  }


  public function get_all_instansi()
  {
    return $this->db
      ->select('kode_instansi')
      ->distinct()
      ->order_by('kode_instansi', 'ASC')
      ->get('jenis_layanan')
      ->result();
  }

  public function get_by_instansi($kode_instansi)
  {
    return $this->db
      ->where('kode_instansi', $kode_instansi)
      ->get('jenis_layanan')
      ->result();
  }
  public function count_by_instansi($instansi_id)
  {
    return $this->db->where('instansi_id', $instansi_id)->count_all_results('jenis_layanan');
  }
}
