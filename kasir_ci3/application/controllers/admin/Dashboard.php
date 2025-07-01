<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Pengecekan session login dan level admin
        if (!$this->session->userdata('logged_in') || $this->session->userdata('level') != 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini!');
            redirect('auth');
        }
        $this->load->model('Transaksi_model');
        $this->load->model('Produk_model');
        $this->load->model('User_model');
        $this->load->model('Kategori_produk_model');
    }

    public function index() {
        $data['title'] = 'Admin Dashboard';

        // Data untuk ditampilkan di dashboard admin
        $today = date('Y-m-d');
        $start_date_month = date('Y-m-01');
        $end_date_month = date('Y-m-t');

        $data['total_penjualan_hari_ini'] = $this->Transaksi_model->get_total_penjualan_harian($today);
        $data['jumlah_transaksi_hari_ini'] = $this->Transaksi_model->get_jumlah_transaksi_harian($today);
        $data['jumlah_produk'] = $this->Produk_model->count_all();
        $data['jumlah_kategori'] = $this->Kategori_produk_model->count_all();
        $data['jumlah_user'] = $this->User_model->count_all();

        // Contoh data untuk chart (misal: penjualan 7 hari terakhir)
        $sales_data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $sales_data['labels'][] = date('d M', strtotime("-$i days"));
            $sales_data['values'][] = $this->Transaksi_model->get_total_penjualan_harian($date);
        }
        $data['sales_chart_data'] = $sales_data;

        $data['produk_terlaris'] = $this->Transaksi_model->get_produk_terlaris($start_date_month, $end_date_month, 5);

        // Nanti kita akan membuat view-nya
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin', $data); // Asumsi ada sidebar khusus admin
        $this->load->view('admin/dashboard_view', $data); // View untuk dashboard admin
        $this->load->view('templates/footer');
    }
}
?>
