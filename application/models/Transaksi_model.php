<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model {

    private $table_transaksi = 'transaksi';
    private $table_detail_transaksi = 'detail_transaksi';
    private $table_produk = 'produk';
    private $table_user = 'users';

    public function generate_id_transaksi() {
        // Format: YYYYMMDD-HIS-XXX (XXX adalah angka random atau sequence)
        $date = date('Ymd-His');
        // Ambil 3 digit terakhir dari microtime untuk sedikit keunikan tambahan jika dalam detik yang sama ada banyak transaksi
        $microtime_suffix = substr(explode(" ", microtime())[0], 2, 3);
        $id_transaksi = $date . '-' . $microtime_suffix;

        // Pastikan ID unik
        while($this->db->where('id_transaksi', $id_transaksi)->count_all_results($this->table_transaksi) > 0) {
            $microtime_suffix = substr(explode(" ", microtime())[0], 2, 3);
            $id_transaksi = $date . '-' . $microtime_suffix . rand(10,99); // Tambah random kecil jika masih bentrok
        }
        return $id_transaksi;
    }

    public function simpan_transaksi($data_transaksi, $data_detail_transaksi) {
        $this->db->trans_start(); // Mulai transaction

        // 1. Simpan data transaksi utama
        $this->db->insert($this->table_transaksi, $data_transaksi);
        $transaksi_id = $data_transaksi['id_transaksi']; // Ambil ID transaksi yang baru saja di-generate dan disimpan

        // 2. Simpan detail transaksi
        foreach ($data_detail_transaksi as $item) {
            $item['transaksi_id'] = $transaksi_id; // Set transaksi_id untuk setiap item detail
            $this->db->insert($this->table_detail_transaksi, $item);

            // 3. Update stok produk
            $this->db->set('stok', 'stok - ' . (int)$item['jumlah'], FALSE);
            $this->db->where('id_produk', $item['produk_id']);
            $this->db->update($this->table_produk);
        }

        $this->db->trans_complete(); // Selesaikan transaction

        if ($this->db->trans_status() === FALSE) {
            // Jika terjadi error, transaction akan di-rollback
            return false;
        } else {
            // Jika berhasil
            return $transaksi_id;
        }
    }

    public function get_transaksi_by_id($id_transaksi) {
        $this->db->select('t.*, u.nama_lengkap as nama_kasir');
        $this->db->from($this->table_transaksi . ' t');
        $this->db->join($this->table_user . ' u', 't.user_id = u.id_user', 'left');
        $this->db->where('t.id_transaksi', $id_transaksi);
        return $this->db->get()->row();
    }

    public function get_detail_transaksi_by_id($id_transaksi) {
        $this->db->select('dt.*, p.nama_produk');
        $this->db->from($this->table_detail_transaksi . ' dt');
        $this->db->join($this->table_produk . ' p', 'dt.produk_id = p.id_produk', 'left');
        $this->db->where('dt.transaksi_id', $id_transaksi);
        return $this->db->get()->result();
    }

    // Untuk Laporan
    public function get_laporan_penjualan($start_date, $end_date) {
        $this->db->select('t.id_transaksi, t.tanggal_transaksi, u.nama_lengkap as nama_kasir, t.total_item, t.total_harga, t.bayar, t.kembali');
        $this->db->from($this->table_transaksi . ' t');
        $this->db->join($this->table_user . ' u', 't.user_id = u.id_user', 'left');
        $this->db->where('DATE(t.tanggal_transaksi) >=', $start_date);
        $this->db->where('DATE(t.tanggal_transaksi) <=', $end_date);
        $this->db->order_by('t.tanggal_transaksi', 'DESC');
        return $this->db->get()->result();
    }

    public function get_total_penjualan_harian($date) {
        $this->db->select_sum('total_harga');
        $this->db->where('DATE(tanggal_transaksi)', $date);
        $result = $this->db->get($this->table_transaksi)->row();
        return ($result->total_harga) ? $result->total_harga : 0;
    }

    public function get_jumlah_transaksi_harian($date) {
        $this->db->where('DATE(tanggal_transaksi)', $date);
        return $this->db->count_all_results($this->table_transaksi);
    }

    public function get_produk_terlaris($start_date, $end_date, $limit = 5) {
        $this->db->select('p.nama_produk, SUM(dt.jumlah) as total_terjual');
        $this->db->from($this->table_detail_transaksi . ' dt');
        $this->db->join($this->table_produk . ' p', 'dt.produk_id = p.id_produk');
        $this->db->join($this->table_transaksi . ' t', 'dt.transaksi_id = t.id_transaksi');
        $this->db->where('DATE(t.tanggal_transaksi) >=', $start_date);
        $this->db->where('DATE(t.tanggal_transaksi) <=', $end_date);
        $this->db->group_by('p.nama_produk');
        $this->db->order_by('total_terjual', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function count_all_transaksi() {
        return $this->db->count_all($this->table_transaksi);
    }
}
?>
