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

  public function get_today_by_instansi($instansi_id, $limit = 25, $offset = 0, $search = '')
  {
    $this->db->select('a.*, u.nama_lengkap, u.no_hp, jl.nama_layanan, a.hadir');
    $this->db->from('antrian a');
    $this->db->join('users u', 'u.id = a.user_id', 'left');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->db->where('a.tanggal', date('Y-m-d'));

    if (!empty($search)) {
      $this->db->group_start()
        ->like('a.nomor_antrian', $search)
        ->or_like('u.nama_lengkap', $search)
        ->or_like('u.no_hp', $search)
        ->or_like('jl.nama_layanan', $search)
        ->group_end();
    }

    $this->db->order_by('a.nomor_antrian', 'ASC');

    // ✅ Ini penting: limit + offset HARUS diterapkan
    if ($limit > 0) {
      $this->db->limit($limit, $offset);
    }

    return $this->db->get()->result();
  }

  public function count_today_by_instansi($instansi_id, $search = '')
  {
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->join('users u', 'u.id = a.user_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->db->where('a.tanggal', date('Y-m-d'));

    if (!empty($search)) {
      $this->db->group_start()
        ->like('a.nomor_antrian', $search)
        ->or_like('u.nama_lengkap', $search)
        ->or_like('u.no_hp', $search)
        ->or_like('jl.nama_layanan', $search)
        ->group_end();
    }

    return $this->db->count_all_results();
  }


  /**
   * Dapatkan ringkasan antrian hari ini untuk dashboard admin layanan
   */
  public function get_ringkasan_hari_ini($instansi_id)
  {
    $today = date('Y-m-d');

    // Total Antrian
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->db->where('a.tanggal', $today);
    $total = $this->db->count_all_results();

    // Menunggu (status: terdaftar / pending)
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->db->where('a.tanggal', $today);
    $this->db->where_in('a.status', ['terdaftar', 'menunggu']);
    $menunggu = $this->db->count_all_results();

    // Dipanggil
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->db->where('a.tanggal', $today);
    $this->db->where('a.status', 'dipanggil');
    $dipanggil = $this->db->count_all_results();

    // Selesai
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->db->where('a.tanggal', $today);
    $this->db->where('a.status', 'selesai');
    $selesai = $this->db->count_all_results();

    return [
      'total'     => $total,
      'menunggu'  => $menunggu,
      'dipanggil' => $dipanggil,
      'selesai'   => $selesai
    ];
  }


  public function get_riwayat_antrian($tanggal, $instansi_id, $limit, $offset, $search = '')
  {
    $this->db->select('a.*, u.nama_lengkap, l.nama_layanan');
    $this->db->from('antrian a');
    $this->db->join('users u', 'a.user_id = u.id', 'left');
    $this->db->join('jenis_layanan l', 'a.layanan_id = l.id', 'left');
    $this->db->where('a.tanggal', $tanggal);
    $this->db->where('l.instansi_id', $instansi_id);

    if ($search) {
      $this->db->group_start();
      $this->db->like('u.nama_lengkap', $search);
      $this->db->or_like('a.nomor_antrian', $search);
      $this->db->or_like('l.nama_layanan', $search);
      $this->db->group_end();
    }

    $this->db->order_by('a.nomor_antrian', 'ASC');
    $this->db->limit($limit, $offset);

    return $this->db->get()->result();
  }

  public function count_riwayat_antrian($instansi_id, $tanggal, $search = '')
  {
    $this->db->from('antrian a');
    $this->db->join('users u', 'a.user_id = u.id', 'left');
    $this->db->join('jenis_layanan l', 'a.layanan_id = l.id', 'left');
    $this->db->where('a.tanggal', $tanggal);
    $this->db->where('l.instansi_id', $instansi_id);

    if ($search) {
      $this->db->group_start();
      $this->db->like('u.nama_lengkap', $search);
      $this->db->or_like('a.nomor_antrian', $search);
      $this->db->or_like('l.nama_layanan', $search);
      $this->db->group_end();
    }

    return $this->db->count_all_results();
  }



  public function get_latest_today($instansi_id)
  {
    return $this->db->select('a.*, jl.nama_layanan')
      ->from('antrian a')
      ->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left')
      ->where('a.tanggal', date('Y-m-d'))
      ->where('jl.instansi_id', $instansi_id)
      ->order_by('a.id', 'DESC')
      ->limit(10)
      ->get()->result();
  }


  public function get_rekap($tanggal, $limit, $start)
  {
    $this->db->select('i.nama_instansi, 
                    COUNT(a.id) AS total, 
                    SUM(a.status = "terdaftar") AS terdaftar,
                    SUM(a.status = "dipanggil") AS dipanggil,
                    SUM(a.status = "selesai") AS selesai,
                    SUM(a.status = "tidak_hadir") AS tidak_hadir');
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left'); // ✅ Perbaikan di sini
    $this->db->where('a.tanggal', $tanggal);
    $this->db->group_by('jl.instansi_id');
    $this->db->order_by('i.nama_instansi', 'ASC');
    $this->db->limit($limit, $start);
    return $this->db->get()->result();
  }

  public function count_rekap($tanggal)
  {
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('a.tanggal', $tanggal);
    $this->db->group_by('jl.instansi_id');
    return $this->db->get()->num_rows();
  }

  public function get_rekap_by_instansi($instansi_id, $bulan = null, $tahun = null)
  {
    if (!$bulan) $bulan = date('m');
    if (!$tahun) $tahun = date('Y');

    $this->db->select('jl.kode, jl.nama_layanan, COUNT(a.id) as total_antrian');
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'a.layanan_id = jl.id');
    $this->db->where('MONTH(a.tanggal)', $bulan);
    $this->db->where('YEAR(a.tanggal)', $tahun);
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->db->group_by('a.layanan_id');
    $this->db->order_by('jl.nama_layanan', 'ASC');

    return $this->db->get()->result();
  }
}
