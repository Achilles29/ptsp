<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Masyarakat_model extends CI_Model
{
    // Hitung antrian aktif user
    public function count_antrian_aktif($user_id)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->where_in('status', ['terdaftar', 'menunggu', 'dipanggil'])
            ->count_all_results('antrian');
    }

    // Ambil daftar nama layanan dari antrian aktif
    public function get_layanan_aktif($user_id)
    {
        $this->db->select('jl.nama_layanan');
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->where('a.user_id', $user_id);
        $this->db->where_in('a.status', ['terdaftar', 'menunggu', 'dipanggil']);
        return $this->db->get()->result();
    }

    // Hitung jumlah riwayat antrian selesai
    public function count_riwayat($user_id)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->count_all_results('antrian');
    }

    // Hitung pesan belum dibalas CS
    public function count_chat_pending($user_id)
    {
        // Ambil pesan terakhir dari user
        $this->db->from('chat');
        $this->db->where('pengirim_id', $user_id);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get()->row();

        if (!$last) {
            return 0;
        }

        // Cek apakah setelah pesan terakhir user, CS sudah membalas
        $this->db->from('chat');
        $this->db->where('pengirim_id !=', $user_id); // CS
        $this->db->where('id >', $last->id);
        $countBalasan = $this->db->count_all_results();

        return ($countBalasan == 0) ? 1 : 0;
    }
    public function get_antrian_aktif_detail($user_id)
    {
        // Ambil antrian aktif user
        $this->db->select('a.id, a.nomor_antrian, jl.kode_huruf');
        $this->db->from('antrian a');
        $this->db->join('jenis_layanan jl', 'jl.id = a.layanan_id', 'left');
        $this->db->where('a.user_id', $user_id);
        $this->db->where_in('a.status', ['terdaftar', 'menunggu', 'dipanggil']);
        $this->db->order_by('a.id', 'desc');
        $this->db->limit(1);
        $my = $this->db->get()->row();

        if (!$my) return null;

        // Nomor antrian saya
        $my_number = $my->nomor_antrian;

        // Ambil antrian yang sedang dipanggil (paling terbaru)
        $this->db->select('nomor_antrian');
        $this->db->from('antrian');
        $this->db->where('layanan_id', $my->id);
        $this->db->where('status', 'dipanggil');
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $called = $this->db->get()->row();

        $current_called = $called ? $called->nomor_antrian : null;

        // Hitung sisa antrian
        $prefix = preg_replace('/\d+/', '', $my_number);  // huruf A/B/C
        $num_my = intval(preg_replace('/[^0-9]/', '', $my_number));

        if ($current_called) {
            $num_called = intval(preg_replace('/[^0-9]/', '', $current_called));
            $remaining = max(0, $num_my - $num_called);
        } else {
            $remaining = "Menunggu mulai dipanggil";
        }

        return [
            'my_number'      => $my_number,
            'called_number'  => $current_called,
            'remaining'      => $remaining
        ];
    }
}
