<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Instansi_model extends CI_Model
{
    public function get_all()
    {
        return $this->db->order_by('id', 'ASC')->get('instansi')->result();
    }

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('instansi', $data);
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id)->update('instansi', $data);
    }

    public function delete($id)
    {
        $this->db->delete('instansi', ['id' => $id]);
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('instansi', ['id' => $id])->row();
    }

    public function update_status($id, $status)
    {
        $data = ['status_layanan' => $status];
        if ($this->db->field_exists('status_layanan_mode', 'instansi') && in_array($status, ['buka', 'tutup'], true)) {
            $data['status_layanan_mode'] = $status;
        }

        return $this->db->update('instansi', $data, ['id' => $id]);
    }
    
public function batalkan_antrian_aktif($instansi_id, $user_id = null, $role_id = null)
{
    // Ambil semua layanan milik instansi
    $layanan = $this->db
        ->select('id')
        ->from('jenis_layanan')
        ->where('instansi_id', $instansi_id)
        ->get()
        ->result_array();

    if (empty($layanan)) {
        return 0;
    }

    $layanan_ids = array_column($layanan, 'id');

    // Selesaikan antrian aktif hari ini saat layanan ditutup
    $this->db->where_in('layanan_id', $layanan_ids);
    $this->db->where('tanggal', date('Y-m-d'));
    $this->db->where_in('status', ['terdaftar', 'menunggu', 'dipanggil']);
    $this->db->update('antrian', [
        'status'       => 'selesai',
        'updated_at'   => date('Y-m-d H:i:s'),
        'updated_by'   => $user_id,
        'updated_role' => $role_id
    ]);

    return $this->db->affected_rows();
}

public function tutup_semua_layanan($user_id = null, $role_id = null)
{
    $data_instansi = [
        'status_layanan' => 'tutup',
        'updated_at'     => date('Y-m-d H:i:s')
    ];
    if ($this->db->field_exists('status_layanan_mode', 'instansi')) {
        $data_instansi['status_layanan_mode'] = 'tutup';
    }

    $this->db->update('instansi', $data_instansi);

    // Ambil semua layanan
    $layanan_ids = $this->db
        ->select('id')
        ->from('jenis_layanan')
        ->get()
        ->result_array();

    if (empty($layanan_ids)) {
        return 0;
    }

    $layanan_ids = array_column($layanan_ids, 'id');

    // Selesaikan semua antrian aktif hari ini
    $this->db->where_in('layanan_id', $layanan_ids);
    $this->db->where('tanggal', date('Y-m-d'));
    $this->db->where_in('status', ['terdaftar', 'menunggu', 'dipanggil']);
    $this->db->update('antrian', [
        'status'       => 'selesai',
        'updated_at'   => date('Y-m-d H:i:s'),
        'updated_by'   => $user_id,
        'updated_role' => $role_id
    ]);

    return $this->db->affected_rows();
}

}
