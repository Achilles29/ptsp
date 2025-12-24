<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
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

    public function count_antrian_today()
    {
        return $this->db->where('DATE(created_at)', date('Y-m-d'))
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
}
