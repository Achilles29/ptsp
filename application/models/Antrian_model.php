<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Antrian_model extends CI_Model
{

  private $table = 'antrian';

  /**
   * Simpan data antrian
   */
  public function insert($data)
  {
    $this->db->insert($this->table, $data);
  }

  /**
   * Generate nomor antrian baru berdasarkan layanan dan tanggal
   * Format: [kode_huruf][3 digit], contoh: A001, B023
   */
  public function generate_nomor($layanan_id, $tanggal)
  {
    // Ambil instansi dari layanan terkait
    $layanan = $this->db->select('id, kode_huruf, instansi_id')
      ->from('jenis_layanan')
      ->where('id', $layanan_id)
      ->get()
      ->row();

    if (!$layanan) {
      return 'X000'; // fallback jika data layanan tidak ditemukan
    }

    $instansi_id = $layanan->instansi_id;
    $kode_huruf  = !empty($layanan->kode_huruf) ? $layanan->kode_huruf : 'X';

    // Hitung total antrian hari ini untuk seluruh layanan dalam instansi yang sama
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->db->where('a.tanggal', $tanggal);
    $count = $this->db->count_all_results();

    // Nomor berikutnya (misal A001, A002, dst)
    $next = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

    return $kode_huruf . $next;
  }

  /**
   * Ambil data antrian hari ini per layanan
   */
  public function get_today_by_layanan($layanan_id)
  {
    return $this->db
      ->select('a.*, u.nama_lengkap, u.no_hp')
      ->from($this->table . ' a')
      ->join('users u', 'u.id = a.user_id', 'left')
      ->where('a.layanan_id', $layanan_id)
      ->where('a.tanggal', date('Y-m-d'))
      ->order_by('a.nomor_antrian', 'ASC')
      ->get()
      ->result();
  }
  public function get_today_by_instansi($instansi_id)
  {
    return $this->db
      ->select('a.*, u.nama_lengkap, u.no_hp, jl.nama_layanan') // tambahkan nama_layanan
      ->from('antrian a')
      ->join('users u', 'u.id = a.user_id', 'left')
      ->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left')
      ->where('jl.instansi_id', $instansi_id)
      ->where('a.tanggal', date('Y-m-d'))
      ->order_by('a.nomor_antrian', 'ASC')
      ->get()
      ->result();
  }


  /**
   * Dapatkan ringkasan antrian hari ini untuk dashboard admin layanan
   */
  public function get_ringkasan_hari_ini($instansi_id)
  {
    $today = date('Y-m-d');

    // Total antrian
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->db->where('a.tanggal', $today);
    $total = $this->db->count_all_results();

    // Belum dipanggil
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->db->where('a.tanggal', $today);
    $this->db->where('a.status', 'terdaftar');
    $belum = $this->db->count_all_results();

    // Selesai
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->db->where('a.tanggal', $today);
    $this->db->where('a.status', 'selesai');
    $selesai = $this->db->count_all_results();

    return [
      'total' => $total,
      'belum' => $belum,
      'selesai' => $selesai
    ];
  }

  public function get_riwayat_antrian($tanggal, $instansi_id, $limit, $offset, $search)
  {
    $this->db->select('a.*, j.nama_layanan, u.nama_lengkap')
      ->from('antrian a')
      ->join('jenis_layanan j', 'j.id = a.layanan_id', 'left')
      ->join('users u', 'u.id = a.user_id', 'left')
      ->where('DATE(a.tanggal)', $tanggal)
      ->where('j.instansi_id', $instansi_id);

    if (!empty($search)) {
      $this->db->group_start();
      $this->db->like('u.nama_lengkap', $search);
      $this->db->or_like('a.nomor_antrian', $search);
      $this->db->or_like('j.nama_layanan', $search);
      $this->db->group_end();
    }

    $this->db->order_by('a.nomor_antrian', 'ASC');
    $this->db->limit($limit, $offset);
    return $this->db->get()->result();
  }

  public function count_riwayat_antrian($instansi_id, $tanggal, $keyword = '')
  {
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan j', 'j.id = a.layanan_id', 'left');
    $this->db->join('users u', 'u.id = a.user_id', 'left');
    $this->db->where('DATE(a.tanggal)', $tanggal);
    $this->db->where('j.instansi_id', $instansi_id);

    if (!empty($keyword)) {
      $this->db->group_start();
      $this->db->like('u.nama_lengkap', $keyword);
      $this->db->or_like('a.nomor_antrian', $keyword);
      $this->db->group_end();
    }

    return $this->db->count_all_results();
  }
}
