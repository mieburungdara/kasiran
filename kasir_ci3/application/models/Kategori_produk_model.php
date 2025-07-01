<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_produk_model extends CI_Model {

    private $table = 'kategori_produk';

    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id_kategori) {
        return $this->db->get_where($this->table, ['id_kategori' => $id_kategori])->row();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id_kategori, $data) {
        $this->db->where('id_kategori', $id_kategori);
        return $this->db->update($this->table, $data);
    }

    public function delete($id_kategori) {
        $this->db->where('id_kategori', $id_kategori);
        return $this->db->delete($this->table);
    }

    public function count_all() {
        return $this->db->count_all($this->table);
    }
}
?>
