<?php
class Jenislayanan_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->ensure_schema();
    }

    public function ensure_schema()
    {
        if (!$this->db->field_exists('target_durasi_menit', 'jenis_layanan')) {
            $this->db->query("ALTER TABLE jenis_layanan ADD COLUMN target_durasi_menit INT NULL DEFAULT 30 AFTER deskripsi");
            $this->db->query("UPDATE jenis_layanan SET target_durasi_menit = 30 WHERE target_durasi_menit IS NULL");
        }
    }

    public function get_all($keyword = null)
    {
        $this->db->select('jl.*, i.nama_instansi, i.kode_instansi');
        $this->db->from('jenis_layanan jl');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');

        if ($keyword) {
            $this->db->group_start()
                ->like('jl.kode', $keyword)
                ->or_like('jl.kode_huruf', $keyword)
                ->or_like('jl.nama_layanan', $keyword)
                ->or_like('i.nama_instansi', $keyword)
                ->group_end();
        }
        $this->db->order_by('jl.id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('jl.*, i.nama_instansi');
        $this->db->from('jenis_layanan jl');
        $this->db->join('instansi i', 'i.id = jl.instansi_id', 'left');
        $this->db->where('jl.id', $id);
        return $this->db->get()->row();
    }

    public function simpan_data()
    {
        $id = $this->input->post('id');
        $data = [
            'instansi_id'  => $this->input->post('instansi_id'),
            'kode'         => $this->input->post('kode'),
            'kode_huruf'   => $this->input->post('kode_huruf'),
            'nama_layanan' => $this->input->post('nama_layanan'),
            'deskripsi'    => $this->input->post('deskripsi'),
            'target_durasi_menit' => max(1, (int) ($this->input->post('target_durasi_menit') ?: 30)),
            'updated_at'   => date('Y-m-d H:i:s')
        ];

        if ($id) {
            $this->db->update('jenis_layanan', $data, ['id' => $id]);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('jenis_layanan', $data);
        }
    }

    public function get_paginated($limit, $start, $keyword = null)
    {
        if ($keyword) {
            $this->db->group_start()
                ->like('kode', $keyword)
                ->or_like('kode_huruf', $keyword)
                ->or_like('nama_layanan', $keyword)
                ->or_like('instansi', $keyword)
                ->or_like('kode_instansi', $keyword)
                ->group_end();
        }
        $this->db->order_by('id', 'DESC');
        return $this->db->get('jenis_layanan', $limit, $start)->result();
    }

    public function count_all($keyword = null)
    {
        if ($keyword) {
            $this->db->group_start()
                ->like('kode', $keyword)
                ->or_like('kode_huruf', $keyword)
                ->or_like('nama_layanan', $keyword)
                ->or_like('instansi', $keyword)
                ->or_like('kode_instansi', $keyword)
                ->group_end();
        }
        return $this->db->count_all_results('jenis_layanan');
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
}
