<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('level') != 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini!');
            redirect('auth');
        }
        $this->load->model('Kategori_produk_model', 'kategori_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'Manajemen Kategori Produk';
        $data['kategori_list'] = $this->kategori_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin', $data);
        $this->load->view('admin/kategori/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambah() {
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required|trim|is_unique[kategori_produk.nama_kategori]');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Tambah Kategori Produk';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar_admin', $data);
            $this->load->view('admin/kategori/tambah');
            $this->load->view('templates/footer');
        } else {
            $nama_kategori = $this->input->post('nama_kategori', TRUE);
            if ($this->kategori_model->insert(['nama_kategori' => $nama_kategori])) {
                $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan.');
                redirect('admin/kategori');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan kategori.');
                redirect('admin/kategori/tambah');
            }
        }
    }

    public function edit($id_kategori = null) {
        if ($id_kategori == null) {
            redirect('admin/kategori');
        }

        $kategori = $this->kategori_model->get_by_id($id_kategori);
        if (!$kategori) {
            $this->session->set_flashdata('error', 'Kategori tidak ditemukan.');
            redirect('admin/kategori');
        }

        // Jika nama kategori tidak diubah, is_unique tidak perlu dicek untuk dirinya sendiri
        $original_value = $kategori->nama_kategori;
        if($this->input->post('nama_kategori') != $original_value) {
           $is_unique =  '|is_unique[kategori_produk.nama_kategori]';
        } else {
           $is_unique =  '';
        }
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required|trim'.$is_unique);

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Edit Kategori Produk';
            $data['kategori'] = $kategori;
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar_admin', $data);
            $this->load->view('admin/kategori/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $nama_kategori = $this->input->post('nama_kategori', TRUE);
            if ($this->kategori_model->update($id_kategori, ['nama_kategori' => $nama_kategori])) {
                $this->session->set_flashdata('success', 'Kategori berhasil diperbarui.');
                redirect('admin/kategori');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui kategori.');
                redirect('admin/kategori/edit/' . $id_kategori);
            }
        }
    }

    public function hapus($id_kategori = null) {
        if ($id_kategori == null) {
            redirect('admin/kategori');
        }

        $kategori = $this->kategori_model->get_by_id($id_kategori);
        if (!$kategori) {
            $this->session->set_flashdata('error', 'Kategori tidak ditemukan.');
            redirect('admin/kategori');
        }

        // Periksa apakah kategori digunakan oleh produk
        $this->load->model('Produk_model');
        $produk_terkait = $this->db->get_where('produk', ['kategori_produk_id' => $id_kategori])->num_rows();

        if ($produk_terkait > 0) {
            $this->session->set_flashdata('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh produk.');
            redirect('admin/kategori');
        }

        if ($this->kategori_model->delete($id_kategori)) {
            $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kategori.');
        }
        redirect('admin/kategori');
    }
}
?>
