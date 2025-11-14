<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendaftaran extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function manual()
    {
        $data['title'] = 'Pendaftaran Manual & Check-In';
        $data['instansi'] = $this->db->get('instansi')->result();
        $this->load->view('pendaftaran/manual', $data);
    }


    // =========================
    // 🔹 GET LAYANAN BY INSTANSI
    // =========================
    public function get_layanan_by_instansi($instansi_id = null)
    {
        if (!$instansi_id) {
            echo json_encode([]);
            return;
        }

        $this->db->select('id, nama_layanan, kode_huruf');
        $this->db->where('instansi_id', $instansi_id);
        $this->db->order_by('nama_layanan', 'ASC');
        $data = $this->db->get('jenis_layanan')->result();

        echo json_encode($data);
    }

    // =========================
    // 🔹 CETAK / GENERATE ANTRIAN
    // =========================
    public function generate_antrian()
    {
        $layanan_id = $this->input->post('layanan_id');
        $tanggal = date('Y-m-d');

        // Ambil prefix kode huruf
        $prefix = $this->db->select('kode_huruf')->where('id', $layanan_id)->get('jenis_layanan')->row('kode_huruf');
        if (!$prefix) $prefix = 'A';

        // Hitung urutan hari ini per layanan
        $todayCount = $this->db->where('layanan_id', $layanan_id)
            ->where('tanggal', $tanggal)
            ->count_all_results('antrian') + 1;

        // Format nomor antrian
        $nomor = $prefix . str_pad($todayCount, 3, '0', STR_PAD_LEFT);

        $data = [
            'user_id' => null,
            'layanan_id' => $layanan_id,
            'tanggal' => $tanggal,
            'status' => 'terdaftar',
            'nomor_antrian' => $nomor,
            'updated_by' => null,
            'updated_role' => null,
            'hadir' => 1,
        ];

        $this->db->insert('antrian', $data);
        $id = $this->db->insert_id();

        echo json_encode([
            'success' => true,
            'nomor' => $nomor,
            'id' => $id,
        ]);
    }


    public function check_in()
    {
        $kode = $this->input->post('kode_qr');
        $antrian = $this->db->get_where('antrian', ['kode_qr' => $kode])->row();
        if ($antrian) {
            $this->db->where('id', $antrian->id)->update('antrian', ['status' => 'hadir']);
            echo json_encode(['success' => true, 'nomor' => $antrian->nomor_antrian]);
        } else {
            echo json_encode(['success' => false, 'message' => 'QR tidak valid']);
        }
    }
}
