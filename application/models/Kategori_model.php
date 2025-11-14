<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {
    public function get_all() {
        return $this->db->get('gis_kategori')->result_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where('gis_kategori', ['id' => $id])->row_array();
    }

    public function insert($data) {
        return $this->db->insert('gis_kategori', $data);
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('gis_kategori', $data);
    }

    public function delete($id) {
        return $this->db->delete('gis_kategori', ['id' => $id]);
    }
}
