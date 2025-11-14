<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Antrian_display extends CI_Controller
{
    public function index()
    {
        $data['title'] = 'Tampilan Antrian Layar Besar';

        // Ambil data video_setting
        $this->db->limit(1);
        $video = $this->db->get('video_setting')->row();
        if (!$video) {
            $video = (object)[
                'source_type' => 'file',
                'file_path'   => null,
                'youtube_url' => null,
                'is_muted'    => 1
            ];
        }
        $data['video'] = $video;

        $this->load->view('antrian/display_monitor', $data);
    }

    public function get_data()
    {
        // Antrian_display::get_data()

        // CURRENT (terakhir dipanggil)
        $this->db->select('a.nomor_antrian, i.nama_instansi, i.loket AS nama_loket, a.status, a.updated_at');
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan j', 'j.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = j.instansi_id', 'left');
        $this->db->where('a.status', 'dipanggil');
        $this->db->order_by('a.updated_at', 'DESC');
        $this->db->limit(1);
        $current = $this->db->get()->row();

        // SLIDER
        $this->db->select('a.nomor_antrian, i.nama_instansi, i.loket AS nama_loket, i.status_layanan');
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan j', 'j.id = a.layanan_id', 'left');
        $this->db->join('instansi i', 'i.id = j.instansi_id', 'left');
        $this->db->where('a.status', 'dipanggil');
        $this->db->where('i.status_layanan', 'buka');
        $this->db->order_by('a.updated_at', 'DESC');
        $slider = $this->db->get()->result();

        // BACKUP
        if (!$current) {
            $this->db->select('a.nomor_antrian, i.nama_instansi, i.loket AS nama_loket, a.status, a.updated_at');
            $this->db->from('antrian a');
            $this->db->join('jenis_layanan j', 'j.id = a.layanan_id', 'left');
            $this->db->join('instansi i', 'i.id = j.instansi_id', 'left');
            $this->db->order_by('a.updated_at', 'DESC');
            $this->db->limit(1);
            $current = $this->db->get()->row();
        }


        echo json_encode([
            'current' => $current,
            'slider' => $slider
        ]);
    }
}
