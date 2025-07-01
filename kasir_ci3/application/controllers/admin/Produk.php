<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('level') != 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini!');
            redirect('auth');
        }
        $this->load->model('Produk_model', 'produk_model');
        $this->load->model('Kategori_produk_model', 'kategori_model');
        $this->load->library('form_validation');
        $this->load->helper('text'); // Untuk word_limiter atau character_limiter jika perlu
    }

    public function index() {
        $data['title'] = 'Manajemen Produk';
        $data['produk_list'] = $this->produk_model->get_all(); // get_produk_with_kategori() sudah ada di model

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin', $data);
        $this->load->view('admin/produk/index', $data);
        $this->load->view('templates/footer');
    }

    private function _common_rules($is_edit = false) {
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required|trim');
        $this->form_validation->set_rules('kategori_produk_id', 'Kategori Produk', 'required|integer');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('deskripsi_produk', 'Deskripsi', 'trim');
    }

    public function tambah() {
        $this->_common_rules();

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Tambah Produk';
            $data['kategori_list'] = $this->kategori_model->get_all();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar_admin', $data);
            $this->load->view('admin/produk/tambah', $data);
            $this->load->view('templates/footer');
        } else {
            $data_produk = [
                'nama_produk' => $this->input->post('nama_produk', TRUE),
                'kategori_produk_id' => $this->input->post('kategori_produk_id', TRUE),
                'harga' => $this->input->post('harga', TRUE),
                'stok' => $this->input->post('stok', TRUE),
                'deskripsi_produk' => $this->input->post('deskripsi_produk', TRUE)
            ];

            // Konfigurasi upload gambar
            $config['upload_path']   = './uploads/produk/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size']      = 2048; // 2MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

            if (!empty($_FILES['gambar_produk']['name'])) {
                if ($this->upload->do_upload('gambar_produk')) {
                    $upload_data = $this->upload->data();
                    $data_produk['gambar_produk'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error_upload', $this->upload->display_errors());
                    // Redirect atau tampilkan error di view tambah
                    $data['title'] = 'Tambah Produk';
                    $data['kategori_list'] = $this->kategori_model->get_all();
                    $this->load->view('templates/header', $data);
                    $this->load->view('templates/sidebar_admin', $data);
                    $this->load->view('admin/produk/tambah', $data);
                    $this->load->view('templates/footer');
                    return;
                }
            }

            if ($this->produk_model->insert($data_produk)) {
                $this->session->set_flashdata('success', 'Produk berhasil ditambahkan.');
                redirect('admin/produk');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan produk.');
                // Hapus gambar jika sudah terupload tapi gagal simpan data
                if (isset($data_produk['gambar_produk']) && file_exists($config['upload_path'] . $data_produk['gambar_produk'])) {
                    unlink($config['upload_path'] . $data_produk['gambar_produk']);
                }
                redirect('admin/produk/tambah');
            }
        }
    }

    public function edit($id_produk = null) {
        if ($id_produk == null) redirect('admin/produk');

        $produk = $this->produk_model->get_by_id($id_produk);
        if (!$produk) {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan.');
            redirect('admin/produk');
        }

        $this->_common_rules(true);

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Edit Produk';
            $data['produk'] = $produk;
            $data['kategori_list'] = $this->kategori_model->get_all();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar_admin', $data);
            $this->load->view('admin/produk/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $data_produk = [
                'nama_produk' => $this->input->post('nama_produk', TRUE),
                'kategori_produk_id' => $this->input->post('kategori_produk_id', TRUE),
                'harga' => $this->input->post('harga', TRUE),
                'stok' => $this->input->post('stok', TRUE),
                'deskripsi_produk' => $this->input->post('deskripsi_produk', TRUE)
            ];

            $config['upload_path']   = './uploads/produk/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size']      = 2048; // 2MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

            if (!empty($_FILES['gambar_produk']['name'])) {
                if ($this->upload->do_upload('gambar_produk')) {
                    $upload_data = $this->upload->data();
                    // Hapus gambar lama jika ada dan bukan default
                    if ($produk->gambar_produk && $produk->gambar_produk != 'default.jpg' && file_exists($config['upload_path'] . $produk->gambar_produk)) {
                        unlink($config['upload_path'] . $produk->gambar_produk);
                    }
                    $data_produk['gambar_produk'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error_upload', $this->upload->display_errors());
                    $data['title'] = 'Edit Produk';
                    $data['produk'] = $produk;
                    $data['kategori_list'] = $this->kategori_model->get_all();
                    $this->load->view('templates/header', $data);
                    $this->load->view('templates/sidebar_admin', $data);
                    $this->load->view('admin/produk/edit', $data);
                    $this->load->view('templates/footer');
                    return;
                }
            }

            if ($this->produk_model->update($id_produk, $data_produk)) {
                $this->session->set_flashdata('success', 'Produk berhasil diperbarui.');
                redirect('admin/produk');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui produk.');
                 // Hapus gambar baru jika sudah terupload tapi gagal update data
                if (isset($data_produk['gambar_produk']) && $data_produk['gambar_produk'] != $produk->gambar_produk && file_exists($config['upload_path'] . $data_produk['gambar_produk'])) {
                    unlink($config['upload_path'] . $data_produk['gambar_produk']);
                }
                redirect('admin/produk/edit/' . $id_produk);
            }
        }
    }

    public function hapus($id_produk = null) {
        if ($id_produk == null) redirect('admin/produk');

        $produk = $this->produk_model->get_by_id($id_produk);
        if (!$produk) {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan.');
            redirect('admin/produk');
        }

        // Cek di model produk apakah bisa dihapus (tidak terkait transaksi)
        if ($this->produk_model->delete($id_produk)) {
            // Hapus gambar fisik jika ada dan bukan default
            if ($produk->gambar_produk && $produk->gambar_produk != 'default.jpg' && file_exists('./uploads/produk/' . $produk->gambar_produk)) {
                unlink('./uploads/produk/' . $produk->gambar_produk);
            }
            $this->session->set_flashdata('success', 'Produk berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus produk. Produk mungkin sudah ada dalam transaksi.');
        }
        redirect('admin/produk');
    }
}
?>
