<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Pengecekan session login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        // Load model yang mungkin dibutuhkan di dashboard umum
        $this->load->model('Transaksi_model');
        $this->load->model('Produk_model');
        $this->load->model('User_model');
    }

    public function index() {
        $data['title'] = 'Dashboard';

        // Data untuk ditampilkan di dashboard (Contoh)
        $today = date('Y-m-d');
        $data['total_penjualan_hari_ini'] = $this->Transaksi_model->get_total_penjualan_harian($today);
        $data['jumlah_transaksi_hari_ini'] = $this->Transaksi_model->get_jumlah_transaksi_harian($today);
        $data['jumlah_produk'] = $this->Produk_model->count_all();
        $data['jumlah_user'] = $this->User_model->count_all(); // Mungkin hanya relevan untuk admin

        // Load view berdasarkan level pengguna
        if ($this->session->userdata('level') == 'admin') {
            // $this->load->view('templates/header', $data);
            // $this->load->view('templates/sidebar_admin', $data); // atau sidebar umum dengan kondisi
            // $this->load->view('dashboard/admin_dashboard_view', $data);
            // $this->load->view('templates/footer');
            redirect('admin/dashboard'); // Redirect ke controller admin dashboard
        } else { // kasir
            // $this->load->view('templates/header', $data);
            // $this->load->view('templates/sidebar_kasir', $data); // atau sidebar umum dengan kondisi
            // $this->load->view('dashboard/kasir_dashboard_view', $data);
            // $this->load->view('templates/footer');
            redirect('kasir/dashboard'); // Redirect ke controller kasir dashboard
        }
    }
}
?>
