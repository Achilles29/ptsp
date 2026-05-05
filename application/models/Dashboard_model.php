<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    public function count_instansi_aktif()
    {
        return $this->db->where('is_aktif', 1)->count_all_results('instansi');
    }

    public function count_instansi_buka()
    {
        return $this->db
            ->where('is_aktif', 1)
            ->where('status_layanan', 'buka')
            ->count_all_results('instansi');
    }

    public function count_instansi()
    {
        return $this->db->count_all('instansi');
    }

    public function count_layanan()
    {
        return $this->db->count_all('jenis_layanan');
    }

    public function count_admin()
    {
        return $this->db->where('role_id', 2)->count_all_results('users');
    }

    public function count_cs()
    {
        return $this->db->where('role_id', 3)->count_all_results('users');
    }

    public function count_pending_verification()
    {
        return $this->db->where('is_verified', 0)->count_all_results('users');
    }

    public function count_antrian_today()
    {
        return $this->db->where('tanggal', date('Y-m-d'))
            ->count_all_results('antrian');
    }

    public function get_antrian_today_per_instansi()
    {
        $this->db->select('i.nama_instansi, COUNT(a.id) as total');
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->where('a.tanggal', date('Y-m-d'));  // ← gunakan kolom tanggal
        $this->db->group_by('i.id');
        return $this->db->get()->result();
    }

    public function get_top_instansi_today($limit = 6)
    {
        return $this->db
            ->select('i.nama_instansi, COUNT(a.id) as total')
            ->from('antrian a')
            ->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left')
            ->join('instansi i', 'i.id = jl.instansi_id', 'left')
            ->where('a.tanggal', date('Y-m-d'))
            ->group_by('i.id')
            ->order_by('total', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result();
    }

    public function get_today_status_breakdown()
    {
        $rows = $this->db
            ->select('status, COUNT(*) AS total')
            ->from('antrian')
            ->where('tanggal', date('Y-m-d'))
            ->group_by('status')
            ->get()
            ->result();

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

    public function get_recent_queue_trend($days = 7)
    {
        $days = max(1, (int) $days);
        $start = date('Y-m-d', strtotime('-' . ($days - 1) . ' days'));
        $rows = $this->db
            ->select('tanggal, COUNT(*) AS total')
            ->from('antrian')
            ->where('tanggal >=', $start)
            ->where('tanggal <=', date('Y-m-d'))
            ->group_by('tanggal')
            ->order_by('tanggal', 'ASC')
            ->get()
            ->result_array();

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
}
