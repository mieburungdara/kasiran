<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Pengecekan session login dan level kasir
        if (!$this->session->userdata('logged_in') || !in_array($this->session->userdata('level'), ['kasir', 'admin'])) {
            // Admin juga boleh akses halaman kasir jika diperlukan, atau bisa di-restrict hanya 'kasir'
            // Jika hanya kasir: $this->session->userdata('level') != 'kasir'
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini!');
            redirect('auth');
        }
        $this->load->model('Transaksi_model');
        // Model lain jika dibutuhkan di dashboard kasir
    }

    public function index() {
        $data['title'] = 'Kasir Dashboard';

        // Data spesifik untuk dashboard kasir, misalnya:
        // - Tombol shortcut ke halaman transaksi utama
        // - Mungkin ringkasan transaksi yang dibuat oleh kasir ybs hari ini

        $today = date('Y-m-d');
        // Ambil data transaksi yang dibuat oleh kasir yang sedang login hari ini
        // Ini memerlukan penyesuaian di Transaksi_model jika ingin lebih spesifik per kasir
        // Untuk sementara, kita tampilkan total penjualan umum hari ini
        $data['total_penjualan_hari_ini'] = $this->Transaksi_model->get_total_penjualan_harian($today);
        $data['jumlah_transaksi_hari_ini'] = $this->Transaksi_model->get_jumlah_transaksi_harian($today);


        // Nanti kita akan membuat view-nya
        // $this->load->view('templates/header', $data);
        // $this->load->view('templates/sidebar_kasir', $data); // Asumsi ada sidebar khusus kasir
        // $this->load->view('kasir/dashboard_view', $data); // View untuk dashboard kasir
        // $this->load->view('templates/footer');

        // Untuk saat ini, dashboard kasir bisa langsung redirect ke halaman utama kasir (transaksi)
        redirect('kasir/transaksi');
    }
}
?>
