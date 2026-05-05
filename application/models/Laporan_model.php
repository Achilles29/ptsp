<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->ensure_reporting_schema();
    }

    public function ensure_reporting_schema()
    {
        if (!$this->db->field_exists('target_durasi_menit', 'jenis_layanan')) {
            $this->db->query("ALTER TABLE jenis_layanan ADD COLUMN target_durasi_menit INT NULL DEFAULT 30 AFTER deskripsi");
            $this->db->query("UPDATE jenis_layanan SET target_durasi_menit = 30 WHERE target_durasi_menit IS NULL");
        }
    }

    private function apply_scope($role_id, $instansi_id = null, $column = 'i.id')
    {
        if ((int) $role_id !== 1 && !empty($instansi_id)) {
            $this->db->where($column, (int) $instansi_id);
        } elseif (!empty($instansi_id)) {
            $this->db->where($column, (int) $instansi_id);
        }
    }

    public function get_rekap_antrian_fix($start_date, $end_date, $role_id, $instansi_id = null, $limit = 0, $offset = 0)
    {
        $this->db->select("
            DATE(a.tanggal) AS tanggal,
            i.id AS instansi_id,
            jl.id AS layanan_id,
            i.nama_instansi,
            jl.nama_layanan,
            COUNT(a.id) AS total_pendaftar,
            SUM(CASE WHEN a.hadir = 1 THEN 1 ELSE 0 END) AS datang,
            SUM(CASE WHEN a.hadir = 0 THEN 1 ELSE 0 END) AS tidak_datang,
            COUNT(hl.id) AS selesai
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id');
        $this->db->join('instansi i', 'i.id = jl.instansi_id');
        $this->db->join('hasil_layanan hl', 'hl.antrian_id = a.id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        $this->apply_scope($role_id, $instansi_id);
        $this->db->group_by(['DATE(a.tanggal)', 'i.id', 'jl.id']);
        $this->db->order_by('DATE(a.tanggal)', 'DESC');
        $this->db->order_by('i.nama_instansi', 'ASC');
        $this->db->order_by('jl.nama_layanan', 'ASC');

        if ((int) $limit > 0) {
            $this->db->limit((int) $limit, (int) $offset);
        }

        return $this->db->get()->result();
    }

    public function count_rekap_antrian_fix($start_date, $end_date, $role_id, $instansi_id = null)
    {
        return count($this->get_rekap_antrian_fix($start_date, $end_date, $role_id, $instansi_id, 0, 0));
    }

    public function get_rekap_chart_series($start_date, $end_date, $role_id, $instansi_id = null)
    {
        $this->db->select("
            DATE(a.tanggal) AS tanggal,
            COUNT(a.id) AS total,
            SUM(CASE WHEN a.hadir = 1 THEN 1 ELSE 0 END) AS hadir,
            SUM(CASE WHEN a.status = 'selesai' THEN 1 ELSE 0 END) AS selesai,
            SUM(CASE WHEN a.status = 'batal' THEN 1 ELSE 0 END) AS batal
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        $this->apply_scope($role_id, $instansi_id);
        $this->db->group_by('DATE(a.tanggal)');
        $this->db->order_by('DATE(a.tanggal)', 'ASC');

        return $this->db->get()->result();
    }

    public function get_dashboard_summary($start_date, $end_date, $role_id, $instansi_id = null)
    {
        $this->db->select("
            COUNT(a.id) AS total_antrian,
            SUM(CASE WHEN a.hadir = 1 THEN 1 ELSE 0 END) AS total_hadir,
            SUM(CASE WHEN a.hadir = 0 THEN 1 ELSE 0 END) AS total_tidak_hadir,
            SUM(CASE WHEN a.status = 'selesai' THEN 1 ELSE 0 END) AS total_selesai,
            SUM(CASE WHEN a.status = 'batal' THEN 1 ELSE 0 END) AS total_batal,
            SUM(CASE WHEN a.called_at IS NOT NULL THEN 1 ELSE 0 END) AS total_terpanggil,
            ROUND(AVG(CASE WHEN hl.selesai_at IS NOT NULL AND a.called_at IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, a.called_at, hl.selesai_at) END), 1) AS rata_durasi_menit
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->join('hasil_layanan hl', 'hl.antrian_id = a.id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        $this->apply_scope($role_id, $instansi_id);
        return $this->db->get()->row();
    }

    public function get_top_instansi($start_date, $end_date, $role_id, $instansi_id = null, $limit = 5)
    {
        $this->db->select("
            i.nama_instansi,
            COUNT(a.id) AS total_antrian,
            SUM(CASE WHEN a.status = 'selesai' THEN 1 ELSE 0 END) AS total_selesai
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        $this->apply_scope($role_id, $instansi_id);
        $this->db->group_by('i.id');
        $this->db->order_by('total_antrian', 'DESC');
        $this->db->order_by('i.nama_instansi', 'ASC');
        $this->db->limit((int) $limit);
        return $this->db->get()->result();
    }

    public function get_laporan_detail_antrian($start_date, $end_date, $instansi_id = null, $layanan_id = null, $limit = 25, $offset = 0)
    {
        $this->db->select("a.*, jl.nama_layanan, i.nama_instansi, u.nama_lengkap, hl.jenis_hasil, hl.selesai_at,
            CASE WHEN a.user_id IS NULL THEN 'offline' ELSE 'online' END AS sumber_daftar");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->join('users u', 'u.id = a.user_id', 'left');
        $this->db->join('hasil_layanan hl', 'hl.antrian_id = a.id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);

        if (!empty($instansi_id)) {
            $this->db->where('jl.instansi_id', (int) $instansi_id);
        }
        if (!empty($layanan_id)) {
            $this->db->where('a.layanan_id', (int) $layanan_id);
        }

        $this->db->order_by('a.tanggal', 'DESC');
        $this->db->order_by('a.created_at', 'DESC');
        $this->db->order_by('a.id', 'DESC');
        if ((int) $limit > 0) {
            $this->db->limit((int) $limit, (int) $offset);
        }
        return $this->db->get()->result();
    }

    public function count_laporan_detail_antrian($start_date, $end_date, $instansi_id = null, $layanan_id = null)
    {
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        if (!empty($instansi_id)) {
            $this->db->where('jl.instansi_id', (int) $instansi_id);
        }
        if (!empty($layanan_id)) {
            $this->db->where('a.layanan_id', (int) $layanan_id);
        }
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        return $this->db->count_all_results();
    }

    public function get_by_instansi($instansi_id = null)
    {
        if (!empty($instansi_id)) {
            $this->db->where('instansi_id', (int) $instansi_id);
        }
        return $this->db->order_by('nama_layanan', 'ASC')->get('jenis_layanan')->result();
    }

    public function get_laporan_detail_antrian_excel($start_date, $end_date, $instansi_id = null)
    {
        $this->db->select("
            a.tanggal,
            i.nama_instansi,
            jl.nama_layanan,
            u.nama_lengkap,
            a.nomor_antrian,
            a.hadir,
            a.status,
            CASE WHEN a.user_id IS NULL THEN 'offline' ELSE 'online' END AS sumber_daftar,
            hl.jenis_hasil,
            hl.selesai_at
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->join('users u', 'u.id = a.user_id', 'left');
        $this->db->join('hasil_layanan hl', 'hl.antrian_id = a.id', 'left');
        $this->db->where('a.tanggal >=', $start_date);
        $this->db->where('a.tanggal <=', $end_date);

        if (!empty($instansi_id)) {
            $this->db->where('i.id', (int) $instansi_id);
        }

        $this->db->order_by('a.tanggal', 'DESC');
        $this->db->order_by('a.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_waktu_layanan_all($start_date, $end_date, $limit)
    {
        $this->db->select('
            antrian.nomor_antrian,
            instansi.nama_instansi,
            jenis_layanan.nama_layanan,
            COALESCE(jenis_layanan.target_durasi_menit, 30) AS target_durasi_menit,
            users.nama_lengkap AS nama_petugas,
            hasil_layanan.selesai_at,
            antrian.called_at
        ')
            ->select("TIMEDIFF(hasil_layanan.selesai_at, antrian.called_at) AS durasi")
            ->from('hasil_layanan')
            ->join('antrian', 'antrian.id = hasil_layanan.antrian_id')
            ->join('users', 'users.id = hasil_layanan.created_by')
            ->join('jenis_layanan', 'jenis_layanan.id = antrian.layanan_id')
            ->join('instansi', 'instansi.id = jenis_layanan.instansi_id')
            ->where("DATE(antrian.tanggal) >= ", $start_date)
            ->where("DATE(antrian.tanggal) <= ", $end_date)
            ->order_by('hasil_layanan.selesai_at', 'DESC');

        if ((int) $limit > 0) {
            $this->db->limit((int) $limit);
        }
        return $this->db->get()->result();
    }

    public function get_waktu_layanan_by_instansi($instansi_id, $start_date, $end_date, $limit)
    {
        $this->db->select('
            antrian.nomor_antrian,
            instansi.nama_instansi,
            jenis_layanan.nama_layanan,
            COALESCE(jenis_layanan.target_durasi_menit, 30) AS target_durasi_menit,
            users.nama_lengkap AS nama_petugas,
            hasil_layanan.selesai_at,
            antrian.called_at
        ')
            ->select("TIMEDIFF(hasil_layanan.selesai_at, antrian.called_at) AS durasi")
            ->from('hasil_layanan')
            ->join('antrian', 'antrian.id = hasil_layanan.antrian_id')
            ->join('users', 'users.id = hasil_layanan.created_by')
            ->join('jenis_layanan', 'jenis_layanan.id = antrian.layanan_id')
            ->join('instansi', 'instansi.id = jenis_layanan.instansi_id')
            ->where('instansi.id', (int) $instansi_id)
            ->where("DATE(antrian.tanggal) >= ", $start_date)
            ->where("DATE(antrian.tanggal) <= ", $end_date)
            ->order_by('hasil_layanan.selesai_at', 'DESC');

        if ((int) $limit > 0) {
            $this->db->limit((int) $limit);
        }
        return $this->db->get()->result();
    }

    public function get_laporan_durasi_layanan_excel($start_date, $end_date, $instansi_id = null)
    {
        $this->db->select("
            a.nomor_antrian,
            i.nama_instansi,
            jl.nama_layanan,
            COALESCE(jl.target_durasi_menit, 30) AS target_durasi_menit,
            petugas.nama_lengkap AS nama_petugas,
            a.called_at,
            hl.selesai_at
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->join('hasil_layanan hl', 'hl.antrian_id = a.id', 'left');
        $this->db->join('users petugas', 'petugas.id = hl.created_by', 'left');
        $this->db->where('a.tanggal >=', $start_date);
        $this->db->where('a.tanggal <=', $end_date);

        if (!empty($instansi_id)) {
            $this->db->where('i.id', (int) $instansi_id);
        }

        $this->db->order_by('a.tanggal', 'DESC');
        $this->db->order_by('hl.selesai_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_detail_hasil_layanan($start_date, $end_date, $instansi_id = null, $layanan_id = null, $limit = 25, $offset = 0)
    {
        $this->db->select("
            a.id,
            a.tanggal,
            a.nomor_antrian,
            a.status,
            i.nama_instansi,
            jl.nama_layanan,
            u.nama_lengkap AS nama_petugas,
            hl.jenis_hasil,
            hl.ringkasan_konsultasi,
            hl.jenis_produk_hukum,
            hl.nomor_produk,
            hl.tanggal_produk,
            hl.catatan_petugas,
            hl.selesai_at
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->join('hasil_layanan hl', 'hl.antrian_id = a.id', 'left');
        $this->db->join('users u', 'u.id = hl.created_by', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);

        if (!empty($instansi_id)) {
            $this->db->where('jl.instansi_id', (int) $instansi_id);
        }
        if (!empty($layanan_id)) {
            $this->db->where('a.layanan_id', (int) $layanan_id);
        }

        $this->db->order_by('a.tanggal', 'DESC');
        $this->db->order_by('hl.selesai_at', 'DESC');
        $this->db->order_by('a.id', 'DESC');
        if ((int) $limit > 0) {
            $this->db->limit((int) $limit, (int) $offset);
        }

        return $this->db->get()->result();
    }

    public function count_detail_hasil_layanan($start_date, $end_date, $instansi_id = null, $layanan_id = null)
    {
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('hasil_layanan hl', 'hl.antrian_id = a.id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);

        if (!empty($instansi_id)) {
            $this->db->where('jl.instansi_id', (int) $instansi_id);
        }
        if (!empty($layanan_id)) {
            $this->db->where('a.layanan_id', (int) $layanan_id);
        }

        return $this->db->count_all_results();
    }

    public function get_busy_hours_matrix($start_date, $end_date, $role_id, $instansi_id = null)
    {
        $this->db->select("
            DAYOFWEEK(COALESCE(a.called_at, a.created_at)) AS weekday_index,
            HOUR(COALESCE(a.called_at, a.created_at)) AS hour_of_day,
            COUNT(a.id) AS total
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        $this->db->where('COALESCE(a.called_at, a.created_at) IS NOT NULL', null, false);
        $this->apply_scope($role_id, $instansi_id);
        $this->db->group_by(['DAYOFWEEK(COALESCE(a.called_at, a.created_at))', 'HOUR(COALESCE(a.called_at, a.created_at))']);
        $this->db->order_by('weekday_index', 'ASC');
        $this->db->order_by('hour_of_day', 'ASC');
        return $this->db->get()->result();
    }

    public function get_busy_hours_top($start_date, $end_date, $role_id, $instansi_id = null, $limit = 6)
    {
        $this->db->select("
            HOUR(COALESCE(a.called_at, a.created_at)) AS hour_of_day,
            COUNT(a.id) AS total
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        $this->db->where('COALESCE(a.called_at, a.created_at) IS NOT NULL', null, false);
        $this->apply_scope($role_id, $instansi_id);
        $this->db->group_by('HOUR(COALESCE(a.called_at, a.created_at))');
        $this->db->order_by('total', 'DESC');
        $this->db->order_by('hour_of_day', 'ASC');
        $this->db->limit((int) $limit);
        return $this->db->get()->result();
    }

    public function get_sla_layanan_report($start_date, $end_date, $role_id, $instansi_id = null)
    {
        $this->db->select("
            i.nama_instansi,
            jl.nama_layanan,
            COALESCE(jl.target_durasi_menit, 30) AS target_durasi_menit,
            COUNT(hl.id) AS total_selesai,
            ROUND(AVG(TIMESTAMPDIFF(MINUTE, a.called_at, hl.selesai_at)), 1) AS rata_durasi_menit,
            MAX(TIMESTAMPDIFF(MINUTE, a.called_at, hl.selesai_at)) AS durasi_terlama_menit,
            SUM(CASE WHEN TIMESTAMPDIFF(MINUTE, a.called_at, hl.selesai_at) <= COALESCE(jl.target_durasi_menit, 30) THEN 1 ELSE 0 END) AS sesuai_target,
            ROUND(
                (SUM(CASE WHEN TIMESTAMPDIFF(MINUTE, a.called_at, hl.selesai_at) <= COALESCE(jl.target_durasi_menit, 30) THEN 1 ELSE 0 END) / COUNT(hl.id)) * 100,
                1
            ) AS persentase_sla
        ");
        $this->db->from('hasil_layanan hl');
        $this->db->join('antrian a', 'a.id = hl.antrian_id');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->where('a.called_at IS NOT NULL', null, false);
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        $this->apply_scope($role_id, $instansi_id);
        $this->db->group_by(['i.id', 'jl.id']);
        $this->db->order_by('persentase_sla', 'DESC');
        $this->db->order_by('total_selesai', 'DESC');
        return $this->db->get()->result();
    }

    public function get_no_show_report($start_date, $end_date, $role_id, $instansi_id = null)
    {
        $this->db->select("
            DATE(a.tanggal) AS tanggal,
            i.nama_instansi,
            jl.nama_layanan,
            CASE WHEN a.user_id IS NULL THEN 'offline' ELSE 'online' END AS sumber_daftar,
            COUNT(a.id) AS total_pendaftar,
            SUM(CASE WHEN a.hadir = 0 THEN 1 ELSE 0 END) AS total_tidak_hadir,
            ROUND((SUM(CASE WHEN a.hadir = 0 THEN 1 ELSE 0 END) / COUNT(a.id)) * 100, 1) AS persentase_tidak_hadir
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        $this->apply_scope($role_id, $instansi_id);
        $this->db->group_by(['DATE(a.tanggal)', 'i.id', 'jl.id', "CASE WHEN a.user_id IS NULL THEN 'offline' ELSE 'online' END"]);
        $this->db->order_by('tanggal', 'DESC');
        $this->db->order_by('persentase_tidak_hadir', 'DESC');
        return $this->db->get()->result();
    }

    public function get_no_show_summary_by_source($start_date, $end_date, $role_id, $instansi_id = null)
    {
        $this->db->select("
            CASE WHEN a.user_id IS NULL THEN 'offline' ELSE 'online' END AS sumber_daftar,
            COUNT(a.id) AS total_pendaftar,
            SUM(CASE WHEN a.hadir = 0 THEN 1 ELSE 0 END) AS total_tidak_hadir
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        $this->apply_scope($role_id, $instansi_id);
        $this->db->group_by("CASE WHEN a.user_id IS NULL THEN 'offline' ELSE 'online' END");
        return $this->db->get()->result();
    }

    public function get_kinerja_petugas_report($start_date, $end_date, $role_id, $instansi_id = null)
    {
        $this->db->select("
            u.nama_lengkap AS nama_petugas,
            i.nama_instansi,
            COUNT(hl.id) AS total_selesai,
            ROUND(AVG(TIMESTAMPDIFF(MINUTE, a.called_at, hl.selesai_at)), 1) AS rata_durasi_menit,
            SUM(CASE WHEN hl.jenis_hasil = 'konsultasi' THEN 1 ELSE 0 END) AS total_konsultasi,
            SUM(CASE WHEN hl.jenis_hasil = 'produk_hukum' THEN 1 ELSE 0 END) AS total_produk_hukum,
            MIN(hl.selesai_at) AS layanan_pertama,
            MAX(hl.selesai_at) AS layanan_terakhir
        ");
        $this->db->from('hasil_layanan hl');
        $this->db->join('antrian a', 'a.id = hl.antrian_id');
        $this->db->join('users u', 'u.id = hl.created_by', 'left');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        $this->apply_scope($role_id, $instansi_id);
        $this->db->group_by(['hl.created_by', 'i.id']);
        $this->db->order_by('total_selesai', 'DESC');
        $this->db->order_by('rata_durasi_menit', 'ASC');
        return $this->db->get()->result();
    }

    public function get_tren_antrian($start_date, $end_date, $role_id, $instansi_id = null, $mode = 'harian')
    {
        $period_select = $mode === 'bulanan'
            ? "DATE_FORMAT(a.tanggal, '%Y-%m')"
            : "DATE(a.tanggal)";

        $this->db->select("
            {$period_select} AS periode,
            COUNT(a.id) AS total_antrian,
            SUM(CASE WHEN a.hadir = 1 THEN 1 ELSE 0 END) AS total_hadir,
            SUM(CASE WHEN a.status = 'selesai' THEN 1 ELSE 0 END) AS total_selesai,
            SUM(CASE WHEN a.status = 'batal' THEN 1 ELSE 0 END) AS total_batal
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        $this->apply_scope($role_id, $instansi_id);
        $this->db->group_by($period_select, false);
        $this->db->order_by('periode', 'ASC');
        return $this->db->get()->result();
    }

    public function get_konversi_antrian($start_date, $end_date, $role_id, $instansi_id = null)
    {
        $this->db->select("
            COUNT(a.id) AS total_terdaftar,
            SUM(CASE WHEN a.hadir = 1 THEN 1 ELSE 0 END) AS total_hadir,
            SUM(CASE WHEN a.called_at IS NOT NULL THEN 1 ELSE 0 END) AS total_dipanggil,
            SUM(CASE WHEN a.status = 'selesai' THEN 1 ELSE 0 END) AS total_selesai,
            SUM(CASE WHEN a.status = 'batal' THEN 1 ELSE 0 END) AS total_batal
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        $this->apply_scope($role_id, $instansi_id);
        return $this->db->get()->row();
    }

    public function get_kepadatan_sektor($start_date, $end_date, $role_id, $instansi_id = null)
    {
        $this->db->select("
            s.nama_sektor,
            COUNT(a.id) AS total_antrian,
            SUM(CASE WHEN a.status = 'selesai' THEN 1 ELSE 0 END) AS total_selesai,
            ROUND(AVG(CASE WHEN hl.selesai_at IS NOT NULL AND a.called_at IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, a.called_at, hl.selesai_at) END), 1) AS rata_durasi_menit,
            ROUND(COUNT(a.id) / GREATEST(COUNT(DISTINCT DATE(a.tanggal)), 1), 1) AS rata_antrian_per_hari
        ");
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->join('sektor_display s', 's.id = i.sektor_id', 'left');
        $this->db->join('hasil_layanan hl', 'hl.antrian_id = a.id', 'left');
        $this->db->where('DATE(a.tanggal) >=', $start_date);
        $this->db->where('DATE(a.tanggal) <=', $end_date);
        $this->apply_scope($role_id, $instansi_id);
        $this->db->group_by('s.id');
        $this->db->order_by('total_antrian', 'DESC');
        $this->db->order_by('rata_durasi_menit', 'DESC');
        return $this->db->get()->result();
    }
}
