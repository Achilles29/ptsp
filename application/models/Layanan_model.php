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
    ->select('i.id, i.kode_instansi, i.nama_instansi')
    ->distinct()
    ->from('jenis_layanan jl')
    ->join('instansi i', 'jl.instansi_id = i.id')
    ->order_by('i.kode_instansi', 'ASC')
    ->get()
    ->result();
}


public function get_by_instansi($instansi_id)
{
  return $this->db
    ->where('instansi_id', $instansi_id)
    ->order_by('nama_layanan', 'ASC')
    ->get('jenis_layanan')
    ->result();
}

  public function count_by_instansi($instansi_id, $allowed_kode_layanan = [])
  {
    $this->db->from('jenis_layanan');
    $this->db->where('instansi_id', $instansi_id);

    if (!empty($allowed_kode_layanan) && is_array($allowed_kode_layanan)) {
      $clean = [];
      foreach ($allowed_kode_layanan as $kode) {
        $kode = strtoupper(trim((string) $kode));
        if ($kode !== '') {
          $clean[] = $kode;
        }
      }
      if (!empty($clean)) {
        $this->db->where_in('kode', array_values(array_unique($clean)));
      }
    }

    return $this->db->count_all_results();
  }
}
