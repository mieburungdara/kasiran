<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        // Untuk saat ini, hanya admin yang bisa akses semua laporan
        if ($this->session->userdata('level') != 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman laporan!');
            // Jika kasir boleh akses laporan tertentu, logika bisa disesuaikan di sini atau di methodnya
            if ($this->session->userdata('level') == 'kasir'){
                 redirect('kasir/dashboard'); // atau halaman lain untuk kasir
            } else {
                 redirect('auth');
            }
        }
        $this->load->model('Transaksi_model', 'transaksi_model');
        $this->load->helper('date'); // Untuk format tanggal jika diperlukan
    }

    public function penjualan() {
        $data['title'] = 'Laporan Penjualan';

        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : date('Y-m-01');
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : date('Y-m-t');

        $data['laporan_penjualan'] = $this->transaksi_model->get_laporan_penjualan($start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $total_pendapatan = 0;
        foreach ($data['laporan_penjualan'] as $lap) {
            $total_pendapatan += $lap->total_harga;
        }
        $data['total_pendapatan'] = $total_pendapatan;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin', $data);
        $this->load->view('admin/laporan/penjualan', $data);
        $this->load->view('templates/footer');
    }

    public function produk_terlaris() {
        $data['title'] = 'Laporan Produk Terlaris';

        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : date('Y-m-01');
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : date('Y-m-t');
        $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;

        $data['produk_terlaris'] = $this->transaksi_model->get_produk_terlaris($start_date, $end_date, $limit);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['limit'] = $limit;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin', $data);
        $this->load->view('admin/laporan/produk_terlaris', $data);
        $this->load->view('templates/footer');
    }

    // Jika ingin ada halaman detail per transaksi dari laporan
    public function detail_transaksi($id_transaksi = null) {
        if (!$id_transaksi) {
            $this->session->set_flashdata('error', 'ID Transaksi tidak valid.');
            redirect('admin/laporan/penjualan'); // atau halaman laporan sebelumnya
        }

        $data['transaksi'] = $this->transaksi_model->get_transaksi_by_id($id_transaksi);
        $data['detail_transaksi'] = $this->transaksi_model->get_detail_transaksi_by_id($id_transaksi);

        if (!$data['transaksi']) {
            $this->session->set_flashdata('error', 'Data transaksi tidak ditemukan.');
            redirect('admin/laporan/penjualan');
        }

        $data['title'] = 'Detail Transaksi - ' . $id_transaksi;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin', $data);
        $this->load->view('admin/laporan/detail_transaksi', $data); // View ini bisa sama dengan struk_view atau dimodifikasi
        $this->load->view('templates/footer');
    }

}
?>
