<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->library('session');
        // Helper 'url' dan 'form' sudah di-autoload
    }

    public function index() {
        // Jika sudah login, redirect ke dashboard sesuai level
        if ($this->session->userdata('logged_in')) {
            if ($this->session->userdata('level') == 'admin') {
                redirect('admin/dashboard');
            } else {
                redirect('kasir/dashboard'); // Atau cukup 'dashboard' jika kasir punya dashboard sendiri
            }
        }
        $this->load->view('auth/login');
    }

    public function login() {
        if ($this->session->userdata('logged_in')) {
             if ($this->session->userdata('level') == 'admin') {
                redirect('admin/dashboard');
            } else {
                redirect('kasir/dashboard');
            }
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->User_model->login($username, $password);

            if ($user) {
                $userdata = array(
                    'id_user' => $user->id_user,
                    'username' => $user->username,
                    'nama_lengkap' => $user->nama_lengkap,
                    'level' => $user->level,
                    'logged_in' => TRUE
                );
                $this->session->set_userdata($userdata);

                // Redirect berdasarkan level
                if ($user->level == 'admin') {
                    redirect('admin/dashboard');
                } else {
                    // Nanti akan diarahkan ke controller Kasir
                    redirect('kasir'); // Atau ke dashboard kasir jika ada
                }
            } else {
                $this->session->set_flashdata('error', 'Username atau Password salah!');
                redirect('auth');
            }
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
?>
