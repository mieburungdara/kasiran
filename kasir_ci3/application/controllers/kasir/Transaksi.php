<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Mengganti nama file dan class dari Kasir.php menjadi Transaksi.php agar lebih konsisten
// dengan nama route yang mungkin kasir/transaksi
class Transaksi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect('auth');
        }
        // Bisa diakses oleh admin dan kasir
        if (!in_array($this->session->userdata('level'), ['admin', 'kasir'])) {
             $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini!');
            redirect('auth'); // atau redirect ke dashboard masing-masing
        }

        $this->load->model('Produk_model', 'produk_model');
        $this->load->model('Transaksi_model', 'transaksi_model');
        $this->load->library('cart'); // Library Cart CodeIgniter
    }

    public function index() {
        $data['title'] = 'Transaksi Penjualan';
        $data['cart_items'] = $this->cart->contents();
        // Nanti akan ada view untuk halaman kasir
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_kasir', $data); // atau sidebar umum
        $this->load->view('kasir/transaksi_form', $data);
        $this->load->view('templates/footer');
    }

    public function cari_produk() {
        // Fungsi ini untuk AJAX request dari halaman kasir
        $keyword = $this->input->get('term'); // 'term' biasanya digunakan oleh jQuery UI Autocomplete
        if ($keyword) {
            $produk_list = $this->produk_model->search_produk($keyword);
            $results = [];
            foreach ($produk_list as $produk) {
                // Pastikan produk memiliki stok
                if($produk->stok > 0){
                    $results[] = [
                        'id' => $produk->id_produk,
                        'label' => $produk->nama_produk . ' (Stok: ' . $produk->stok . ')', // Teks yang ditampilkan di autocomplete
                        'value' => $produk->nama_produk, // Teks yang masuk ke input setelah dipilih
                        'nama_produk' => $produk->nama_produk,
                        'harga' => $produk->harga,
                        'stok' => $produk->stok
                    ];
                }
            }
            echo json_encode($results);
        }
    }

    public function tambah_ke_keranjang() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('produk_id', 'Produk ID', 'required|integer');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|integer|greater_than[0]');
        // Harga dan nama bisa diambil dari database berdasarkan produk_id untuk keamanan

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error_cart', validation_errors());
            redirect('kasir/transaksi');
        } else {
            $produk_id = $this->input->post('produk_id');
            $jumlah = (int)$this->input->post('jumlah');

            $produk = $this->produk_model->get_by_id($produk_id);

            if ($produk) {
                if ($produk->stok >= $jumlah) {
                    $data_cart = array(
                        'id'      => $produk->id_produk,
                        'qty'     => $jumlah,
                        'price'   => $produk->harga,
                        'name'    => $produk->nama_produk,
                        'options' => array('stok_awal' => $produk->stok) // Simpan stok awal jika perlu
                    );
                    $this->cart->insert($data_cart);
                    $this->session->set_flashdata('success_cart', 'Produk berhasil ditambahkan ke keranjang.');
                } else {
                    $this->session->set_flashdata('error_cart', 'Stok produk tidak mencukupi. Sisa stok: ' . $produk->stok);
                }
            } else {
                $this->session->set_flashdata('error_cart', 'Produk tidak ditemukan.');
            }
            redirect('kasir/transaksi');
        }
    }

    public function update_keranjang_item($rowid) {
        $jumlah = (int)$this->input->post('qty');
        if ($rowid && $jumlah > 0) {
            // Cek stok sebelum update
            $item = $this->cart->get_item($rowid);
            $produk = $this->produk_model->get_by_id($item['id']);
            if ($produk->stok >= $jumlah) {
                $data = array(
                    'rowid' => $rowid,
                    'qty'   => $jumlah
                );
                $this->cart->update($data);
            } else {
                 $this->session->set_flashdata('error_cart', 'Stok produk ' . $produk->nama_produk . ' tidak mencukupi. Sisa stok: ' . $produk->stok);
            }
        }
        redirect('kasir/transaksi');
    }

    public function hapus_item_keranjang($rowid) {
        if ($rowid) {
            $this->cart->remove($rowid);
            $this->session->set_flashdata('success_cart', 'Item berhasil dihapus dari keranjang.');
        }
        redirect('kasir/transaksi');
    }

    public function hapus_keranjang() {
        $this->cart->destroy();
        $this->session->set_flashdata('success_cart', 'Keranjang berhasil dikosongkan.');
        redirect('kasir/transaksi');
    }

    public function proses_pembayaran() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('bayar', 'Jumlah Bayar', 'required|numeric|greater_than_equal_to[' . $this->cart->total() . ']');
        // Rule untuk pelanggan bisa ditambahkan jika ada data pelanggan

        if ($this->cart->total_items() == 0) {
            $this->session->set_flashdata('error', 'Keranjang belanja kosong.');
            redirect('kasir/transaksi');
            return;
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error_bayar', validation_errors());
            redirect('kasir/transaksi');
        } else {
            $id_transaksi = $this->transaksi_model->generate_id_transaksi();
            $total_harga = $this->cart->total();
            $bayar = $this->input->post('bayar');
            $kembali = $bayar - $total_harga;

            $data_transaksi = [
                'id_transaksi' => $id_transaksi,
                'user_id' => $this->session->userdata('id_user'),
                'tanggal_transaksi' => date('Y-m-d H:i:s'),
                'total_item' => $this->cart->total_items(),
                'total_harga' => $total_harga,
                'bayar' => $bayar,
                'kembali' => $kembali,
                'catatan' => $this->input->post('catatan') // Jika ada field catatan
            ];

            $data_detail_transaksi = [];
            foreach ($this->cart->contents() as $item) {
                $data_detail_transaksi[] = [
                    // 'transaksi_id' akan diisi di model
                    'produk_id' => $item['id'],
                    'harga_produk' => $item['price'],
                    'jumlah' => $item['qty'],
                    'subtotal' => $item['subtotal']
                ];
            }

            $simpan = $this->transaksi_model->simpan_transaksi($data_transaksi, $data_detail_transaksi);

            if ($simpan) {
                $this->cart->destroy(); // Kosongkan keranjang setelah berhasil
                $this->session->set_flashdata('success', 'Transaksi berhasil disimpan. ID Transaksi: ' . $simpan);
                // Redirect ke halaman struk atau kembali ke form kasir dengan pesan sukses
                redirect('kasir/transaksi/struk/' . $simpan);
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan transaksi.');
                redirect('kasir/transaksi');
            }
        }
    }

    public function struk($id_transaksi = null) {
        if (!$id_transaksi) {
            redirect('kasir/transaksi');
        }

        $data['transaksi'] = $this->transaksi_model->get_transaksi_by_id($id_transaksi);
        $data['detail_transaksi'] = $this->transaksi_model->get_detail_transaksi_by_id($id_transaksi);

        if (!$data['transaksi']) {
            $this->session->set_flashdata('error', 'Data transaksi tidak ditemukan.');
            redirect('kasir/transaksi');
        }

        $data['title'] = 'Struk Pembayaran - ' . $id_transaksi;
        // View struk akan dibuat nanti
        $this->load->view('kasir/struk_view', $data);
    }
}
?>
