<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Antrian_display extends CI_Controller
{
    private function ensure_sector_schema()
    {
        if (!$this->db->table_exists('sektor_display')) {
            $this->db->query("
                CREATE TABLE sektor_display (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    kode_sektor VARCHAR(30) NOT NULL UNIQUE,
                    nama_sektor VARCHAR(100) NOT NULL,
                    slug VARCHAR(120) NOT NULL UNIQUE,
                    lokasi_display VARCHAR(150) NULL,
                    is_aktif TINYINT(1) NOT NULL DEFAULT 1,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL
                )
            ");
        }

        if (!$this->db->field_exists('sektor_id', 'instansi')) {
            $this->db->query("ALTER TABLE instansi ADD COLUMN sektor_id INT NULL AFTER nama_instansi");
            $this->db->query("ALTER TABLE instansi ADD INDEX idx_instansi_sektor (sektor_id)");
        }
    }

    private function get_sector_by_slug($slug = null)
    {
        if (!$slug) {
            return null;
        }

        return $this->db
            ->where('slug', $slug)
            ->where('is_aktif', 1)
            ->get('sektor_display')
            ->row();
    }

    public function index($slug = null)
    {
        $this->ensure_sector_schema();
        $data['title'] = 'MPP Kabupaten Rembang';

        $this->db->limit(1);
        $video = $this->db->get('video_setting')->row();
        if (!$video) {
            $video = (object)[
                'source_type' => 'file',
                'file_path'   => null,
                'youtube_url' => null,
                'is_muted'    => 1,
                'audio_speed' => 1.50
            ];
        }
        $data['video'] = $video;
        $data['selected_sector'] = $this->get_sector_by_slug($slug);
        $data['data_endpoint'] = $data['selected_sector']
            ? site_url('antrian_display/get_data/' . $data['selected_sector']->slug)
            : site_url('antrian_display/get_data');

        $this->load->view('antrian/display_monitor', $data);
    }

    
public function get_data($slug = null)
{
  $this->ensure_sector_schema();
  $selected_sector = $this->get_sector_by_slug($slug);
  $today = date('Y-m-d');

  /* ================= CURRENT =================
     HANYA 1 ANTRIAN:
     - status = dipanggil
     - called_at NOT NULL
     - paling terakhir
  ============================================ */
  $this->db->select('
    a.id,
    a.nomor_antrian,
    i.nama_instansi,
    i.loket AS nama_loket,
    a.status,
    a.called_at
  ');
  $this->db->from('antrian a');
  $this->db->join('jenis_layanan j', 'j.id = a.layanan_id', 'left');
  $this->db->join('instansi i', 'i.id = j.instansi_id', 'left');

  $this->db->where('a.tanggal', $today);
  $this->db->where('a.status', 'dipanggil');
  $this->db->where('a.called_at IS NOT NULL', null, false);
  if ($selected_sector) {
    $this->db->where('i.sektor_id', (int) $selected_sector->id);
  }
  $this->db->order_by('a.called_at', 'DESC');
  $this->db->limit(1);

  $current = $this->db->get()->row();

  /* ================= SLIDER =================
     DAFTAR ANTRIAN AKTIF DIPANGGIL
     (status selesai/batal tidak ditampilkan)
  ============================================ */
  $this->db->select('
    a.nomor_antrian,
    i.nama_instansi,
    i.loket AS nama_loket
  ');
  $this->db->from('antrian a');
  $this->db->join('jenis_layanan j', 'j.id = a.layanan_id', 'left');
  $this->db->join('instansi i', 'i.id = j.instansi_id', 'left');

  $this->db->where('a.tanggal', $today);
  $this->db->where('a.status', 'dipanggil');
  $this->db->where('a.called_at IS NOT NULL', null, false);
  if ($selected_sector) {
    $this->db->where('i.sektor_id', (int) $selected_sector->id);
  }
  $this->db->order_by('a.called_at', 'DESC');
  $this->db->limit(10); // histori terakhir

  $slider = $this->db->get()->result();

  echo json_encode([
    'sector'  => $selected_sector,
    'current' => $current,
    'slider'  => $slider
  ]);
}

}
