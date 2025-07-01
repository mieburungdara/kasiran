<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('level') != 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini!');
            redirect('auth');
        }
        $this->load->model('User_model', 'user_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'Manajemen Pengguna';
        $data['users_list'] = $this->user_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin', $data);
        $this->load->view('admin/users/index', $data);
        $this->load->view('templates/footer');
    }

    private function _user_rules($is_edit = false) {
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');

        $username_rules = 'required|trim|alpha_dash|min_length[4]|is_unique[users.username]';
        if ($is_edit) {
            // Untuk edit, is_unique hanya berlaku jika username diubah
            $original_username = $this->user_model->get_by_id($this->input->post('id_user'))->username;
            if ($this->input->post('username') == $original_username) {
                $username_rules = 'required|trim|alpha_dash|min_length[4]';
            }
        }
        $this->form_validation->set_rules('username', 'Username', $username_rules);

        $password_rules = 'min_length[6]'; // Password tidak wajib diisi saat edit
        if (!$is_edit || ($is_edit && !empty($this->input->post('password')))) {
            $password_rules = 'required|min_length[6]';
        }
        $this->form_validation->set_rules('password', 'Password', $password_rules);
        if (!empty($this->input->post('password'))) { // Hanya validasi konfirmasi jika password diisi
             $this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'matches[password]');
        }

        $this->form_validation->set_rules('level', 'Level Pengguna', 'required|in_list[admin,kasir]');
    }

    public function tambah() {
        $this->_user_rules();

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Tambah Pengguna';
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar_admin', $data);
            $this->load->view('admin/users/tambah', $data);
            $this->load->view('templates/footer');
        } else {
            $data_user = [
                'nama_lengkap' => $this->input->post('nama_lengkap', TRUE),
                'username' => $this->input->post('username', TRUE),
                'password' => $this->input->post('password'), // Hashing dilakukan di model
                'level' => $this->input->post('level', TRUE)
            ];

            if ($this->user_model->insert($data_user)) {
                $this->session->set_flashdata('success', 'Pengguna berhasil ditambahkan.');
                redirect('admin/users');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pengguna.');
                redirect('admin/users/tambah');
            }
        }
    }

    public function edit($id_user = null) {
        if ($id_user == null) redirect('admin/users');
        // Admin tidak boleh edit dirinya sendiri melalui form ini untuk mencegah terkunci
        if ($id_user == $this->session->userdata('id_user')) {
             $this->session->set_flashdata('error', 'Anda tidak dapat mengedit akun Anda sendiri melalui halaman ini.');
             redirect('admin/users');
        }


        $user = $this->user_model->get_by_id($id_user);
        if (!$user) {
            $this->session->set_flashdata('error', 'Pengguna tidak ditemukan.');
            redirect('admin/users');
        }

        // Simpan id_user ke post data agar bisa diakses di _user_rules
        $_POST['id_user'] = $id_user;
        $this->_user_rules(true);


        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Edit Pengguna';
            $data['user_data'] = $user; // Ganti nama variabel agar tidak konflik dengan session 'user'
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar_admin', $data);
            $this->load->view('admin/users/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $data_user = [
                'nama_lengkap' => $this->input->post('nama_lengkap', TRUE),
                'username' => $this->input->post('username', TRUE),
                'level' => $this->input->post('level', TRUE)
            ];
            // Hanya tambahkan password ke array jika diisi
            if (!empty($this->input->post('password'))) {
                $data_user['password'] = $this->input->post('password');
            }

            if ($this->user_model->update($id_user, $data_user)) {
                $this->session->set_flashdata('success', 'Data pengguna berhasil diperbarui.');
                redirect('admin/users');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data pengguna.');
                redirect('admin/users/edit/' . $id_user);
            }
        }
    }

    public function hapus($id_user = null) {
        if ($id_user == null) redirect('admin/users');

        // Mencegah admin menghapus akunnya sendiri
        if ($id_user == $this->session->userdata('id_user')) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            redirect('admin/users');
        }

        $user = $this->user_model->get_by_id($id_user);
        if (!$user) {
            $this->session->set_flashdata('error', 'Pengguna tidak ditemukan.');
            redirect('admin/users');
        }

        if ($this->user_model->delete($id_user)) {
            $this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pengguna. Pengguna mungkin memiliki transaksi terkait.');
        }
        redirect('admin/users');
    }
}
?>
