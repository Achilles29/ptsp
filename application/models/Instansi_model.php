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
        return $this->db->update('instansi', ['status_layanan' => $status], ['id' => $id]);
    }
}
