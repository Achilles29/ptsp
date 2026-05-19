<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Antrian_model extends CI_Model
{

  private $table = 'antrian';

  private function _normalize_kode_layanan($allowed_kode_layanan)
  {
    if (empty($allowed_kode_layanan) || !is_array($allowed_kode_layanan)) {
      return [];
    }

    $clean = [];
    foreach ($allowed_kode_layanan as $kode) {
      $kode = strtoupper(trim((string) $kode));
      if ($kode !== '') {
        $clean[] = $kode;
      }
    }

    return array_values(array_unique($clean));
  }

  private function _apply_kode_layanan_filter($alias, $allowed_kode_layanan)
  {
    $clean = $this->_normalize_kode_layanan($allowed_kode_layanan);
    if (!empty($clean)) {
      $this->db->where_in($alias . '.kode', $clean);
    }
  }

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

  public function get_today_by_instansi($instansi_id, $limit = 25, $offset = 0, $search = '', $allowed_kode_layanan = [], $filters = [])
  {
    $this->db->select('a.*, u.nama_lengkap, u.no_hp, jl.nama_layanan, a.hadir');
    $this->db->from('antrian a');
    $this->db->join('users u', 'u.id = a.user_id', 'left');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('jl', $allowed_kode_layanan);
    $this->db->where('a.tanggal', date('Y-m-d'));
    $this->_apply_admin_layanan_filters($filters);

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

  public function count_today_by_instansi($instansi_id, $search = '', $allowed_kode_layanan = [], $filters = [])
  {
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->join('users u', 'u.id = a.user_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('jl', $allowed_kode_layanan);
    $this->db->where('a.tanggal', date('Y-m-d'));
    $this->_apply_admin_layanan_filters($filters);

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

  private function _apply_admin_layanan_filters($filters = [])
  {
    $tab = isset($filters['tab']) ? (string) $filters['tab'] : 'aktif';
    $hadir = isset($filters['hadir']) ? (string) $filters['hadir'] : 'semua';
    $status = isset($filters['status']) ? (string) $filters['status'] : '';

    if ($tab === 'aktif') {
      $this->db->where_not_in('a.status', ['selesai', 'batal']);
    } elseif ($tab === 'selesai') {
      $this->db->where('a.status', 'selesai');
    }

    if ($hadir === 'hadir') {
      $this->db->where('a.hadir', 1);
    } elseif ($hadir === 'belum') {
      $this->db->where('a.hadir', 0);
    }

    if ($status !== '' && $status !== 'semua') {
      $this->db->where('a.status', $status);
    }
  }


  /**
   * Dapatkan ringkasan antrian hari ini untuk dashboard admin layanan
   */
  public function get_ringkasan_hari_ini($instansi_id, $allowed_kode_layanan = [])
  {
    $today = date('Y-m-d');

    // Total Antrian
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('jl', $allowed_kode_layanan);
    $this->db->where('a.tanggal', $today);
    $total = $this->db->count_all_results();

    // Menunggu (status: terdaftar / pending)
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('jl', $allowed_kode_layanan);
    $this->db->where('a.tanggal', $today);
    $this->db->where_in('a.status', ['terdaftar', 'menunggu']);
    $menunggu = $this->db->count_all_results();

    // Dipanggil
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('jl', $allowed_kode_layanan);
    $this->db->where('a.tanggal', $today);
    $this->db->where('a.status', 'dipanggil');
    $dipanggil = $this->db->count_all_results();

    // Selesai
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('jl', $allowed_kode_layanan);
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


  public function get_riwayat_antrian($tanggal_awal, $tanggal_akhir, $instansi_id, $limit, $offset, $search = '', $allowed_kode_layanan = [])
  {
    $this->db->select('a.*, u.nama_lengkap, l.nama_layanan');
    $this->db->from('antrian a');
    $this->db->join('users u', 'a.user_id = u.id', 'left');
    $this->db->join('jenis_layanan l', 'a.layanan_id = l.id', 'left');
    $this->db->where('a.tanggal >=', $tanggal_awal);
    $this->db->where('a.tanggal <=', $tanggal_akhir);
    $this->db->where('l.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('l', $allowed_kode_layanan);

    if ($search) {
      $this->db->group_start();
      $this->db->like('u.nama_lengkap', $search);
      $this->db->or_like('a.nomor_antrian', $search);
      $this->db->or_like('l.nama_layanan', $search);
      $this->db->group_end();
    }

    $this->db->order_by('a.tanggal', 'DESC');
    $this->db->order_by('a.created_at', 'DESC');
    $this->db->order_by('a.id', 'DESC');
    if ((int) $limit > 0) {
      $this->db->limit((int) $limit, (int) $offset);
    }

    return $this->db->get()->result();
  }

  public function count_riwayat_antrian($instansi_id, $tanggal_awal, $tanggal_akhir, $search = '', $allowed_kode_layanan = [])
  {
    $this->db->from('antrian a');
    $this->db->join('users u', 'a.user_id = u.id', 'left');
    $this->db->join('jenis_layanan l', 'a.layanan_id = l.id', 'left');
    $this->db->where('a.tanggal >=', $tanggal_awal);
    $this->db->where('a.tanggal <=', $tanggal_akhir);
    $this->db->where('l.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('l', $allowed_kode_layanan);

    if ($search) {
      $this->db->group_start();
      $this->db->like('u.nama_lengkap', $search);
      $this->db->or_like('a.nomor_antrian', $search);
      $this->db->or_like('l.nama_layanan', $search);
      $this->db->group_end();
    }

    return $this->db->count_all_results();
  }



  public function get_latest_today($instansi_id, $allowed_kode_layanan = [])
  {
    $this->db->select('a.*, jl.nama_layanan');
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('a.tanggal', date('Y-m-d'));
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('jl', $allowed_kode_layanan);
    $this->db->order_by('a.id', 'DESC');
    $this->db->limit(10);

    return $this->db->get()->result();
  }

  public function get_today_status_breakdown_by_instansi($instansi_id, $allowed_kode_layanan = [])
  {
    $this->db->select('a.status, COUNT(*) AS total');
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('jl', $allowed_kode_layanan);
    $this->db->where('a.tanggal', date('Y-m-d'));
    $this->db->group_by('a.status');
    $rows = $this->db->get()->result();

    $result = [
      'terdaftar' => 0,
      'dipanggil' => 0,
      'selesai' => 0,
      'batal' => 0
    ];

    foreach ($rows as $row) {
      $status = (string) $row->status;
      if (array_key_exists($status, $result)) {
        $result[$status] = (int) $row->total;
      }
    }

    return $result;
  }

  public function get_today_attendance_breakdown_by_instansi($instansi_id, $allowed_kode_layanan = [])
  {
    $this->db->select('SUM(CASE WHEN a.hadir = 1 THEN 1 ELSE 0 END) AS hadir, COUNT(*) AS total', false);
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('jl', $allowed_kode_layanan);
    $this->db->where('a.tanggal', date('Y-m-d'));
    $row = $this->db->get()->row();

    $hadir = (int) ($row->hadir ?? 0);
    $total = (int) ($row->total ?? 0);

    return [
      'hadir' => $hadir,
      'belum_hadir' => max(0, $total - $hadir)
    ];
  }

  public function get_recent_trend_by_instansi($instansi_id, $days = 7, $allowed_kode_layanan = [])
  {
    $days = max(1, (int) $days);
    $start = date('Y-m-d', strtotime('-' . ($days - 1) . ' days'));

    $this->db->select('a.tanggal, COUNT(*) AS total');
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('jl', $allowed_kode_layanan);
    $this->db->where('a.tanggal >=', $start);
    $this->db->where('a.tanggal <=', date('Y-m-d'));
    $this->db->group_by('a.tanggal');
    $this->db->order_by('a.tanggal', 'ASC');
    $rows = $this->db->get()->result_array();

    $indexed = [];
    foreach ($rows as $row) {
      $indexed[$row['tanggal']] = (int) $row['total'];
    }

    $result = [];
    for ($i = $days - 1; $i >= 0; $i--) {
      $date = date('Y-m-d', strtotime('-' . $i . ' days'));
      $result[] = [
        'label' => date('d M', strtotime($date)),
        'total' => $indexed[$date] ?? 0
      ];
    }

    return $result;
  }

  public function get_today_layanan_breakdown_by_instansi($instansi_id, $limit = 6, $allowed_kode_layanan = [])
  {
    $this->db->select('jl.nama_layanan, COUNT(a.id) AS total');
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('jl', $allowed_kode_layanan);
    $this->db->where('a.tanggal', date('Y-m-d'));
    $this->db->group_by('jl.id');
    $this->db->order_by('total', 'DESC');
    $this->db->order_by('jl.nama_layanan', 'ASC');
    $this->db->limit((int) $limit);
    return $this->db->get()->result();
  }

  public function get_current_call_by_instansi($instansi_id, $allowed_kode_layanan = [])
  {
    $this->db->select('a.nomor_antrian, a.called_at, jl.nama_layanan, u.nama_lengkap');
    $this->db->from('antrian a');
    $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
    $this->db->join('users u', 'u.id = a.user_id', 'left');
    $this->db->where('jl.instansi_id', $instansi_id);
    $this->_apply_kode_layanan_filter('jl', $allowed_kode_layanan);
    $this->db->where('a.tanggal', date('Y-m-d'));
    $this->db->where('a.status', 'dipanggil');
    $this->db->where('a.called_at IS NOT NULL', null, false);
    $this->db->order_by('a.called_at', 'DESC');
    return $this->db->get()->row();
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
    if ((int) $limit > 0) {
      $this->db->limit((int) $limit, (int) $start);
    }
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
