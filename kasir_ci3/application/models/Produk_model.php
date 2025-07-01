<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_model extends CI_Model {

    private $table = 'produk';
    private $table_kategori = 'kategori_produk';

    public function get_all($limit = null, $offset = 0) {
        $this->db->select('p.*, kp.nama_kategori');
        $this->db->from($this->table . ' p');
        $this->db->join($this->table_kategori . ' kp', 'p.kategori_produk_id = kp.id_kategori', 'left');
        $this->db->order_by('p.nama_produk', 'ASC');
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result();
    }

    public function get_by_id($id_produk) {
        $this->db->select('p.*, kp.nama_kategori');
        $this->db->from($this->table . ' p');
        $this->db->join($this->table_kategori . ' kp', 'p.kategori_produk_id = kp.id_kategori', 'left');
        $this->db->where('p.id_produk', $id_produk);
        return $this->db->get()->row();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id_produk, $data) {
        $this->db->where('id_produk', $id_produk);
        return $this->db->update($this->table, $data);
    }

    public function delete($id_produk) {
        // Periksa apakah produk ada di detail_transaksi sebelum menghapus
        $this->db->where('produk_id', $id_produk);
        $count = $this->db->count_all_results('detail_transaksi');
        if ($count > 0) {
            return false; // Produk tidak bisa dihapus karena sudah ada dalam transaksi
        }

        $this->db->where('id_produk', $id_produk);
        return $this->db->delete($this->table);
    }

    public function count_all() {
        return $this->db->count_all($this->table);
    }

    public function search_produk($keyword) {
        $this->db->select('p.id_produk, p.nama_produk, p.harga, p.stok, kp.nama_kategori');
        $this->db->from($this->table . ' p');
        $this->db->join($this->table_kategori . ' kp', 'p.kategori_produk_id = kp.id_kategori', 'left');
        $this->db->like('p.nama_produk', $keyword);
        $this->db->or_like('p.id_produk', $keyword); // Jika ada pencarian by ID/barcode
        $this->db->where('p.stok >', 0); // Hanya tampilkan produk yang masih ada stok
        $this->db->limit(10); // Batasi hasil pencarian
        return $this->db->get()->result();
    }

    public function get_produk_with_kategori() {
        $this->db->select('produk.*, kategori_produk.nama_kategori');
        $this->db->from('produk');
        $this->db->join('kategori_produk', 'produk.kategori_produk_id = kategori_produk.id_kategori');
        $query = $this->db->get();
        return $query->result();
    }

    public function update_stok($id_produk, $jumlah_dibeli) {
        $this->db->set('stok', 'stok - ' . (int)$jumlah_dibeli, FALSE);
        $this->db->where('id_produk', $id_produk);
        return $this->db->update($this->table);
    }
}
?>
