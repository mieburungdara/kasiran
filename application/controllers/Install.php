<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load helper yang dibutuhkan
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        // $this->load->database(); // Akan diload setelah konfigurasi disimpan
        $this->load->dbforge(); // Untuk membuat database jika belum ada
    }

    public function index() {
        // Cek apakah konfigurasi sudah ada
        if ($this->is_configured()) {
            redirect(base_url()); // Redirect ke halaman utama jika sudah terkonfigurasi
        }

        $this->load->view('install_view');
    }

    public function setup() {
        if ($this->is_configured()) {
            redirect(base_url());
        }

        // Atur rules validasi
        $this->form_validation->set_rules('db_host', 'Database Host', 'required');
        $this->form_validation->set_rules('db_name', 'Database Name', 'required');
        $this->form_validation->set_rules('db_user', 'Database User', 'required');
        // Password boleh kosong
        $this->form_validation->set_rules('db_pass', 'Database Password', 'trim');
        $this->form_validation->set_rules('base_url', 'Base URL', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan kembali form instalasi
            $this->load->view('install_view');
        } else {
            // Proses penyimpanan konfigurasi
            $db_host = $this->input->post('db_host');
            $db_name = $this->input->post('db_name');
            $db_user = $this->input->post('db_user');
            $db_pass = $this->input->post('db_pass');
            $base_url = $this->input->post('base_url');

            // Update file database.php
            if ($this->update_database_config($db_host, $db_name, $db_user, $db_pass)) {
                // Update file config.php
                if ($this->update_config_file($base_url)) {
                    // Setelah konfigurasi file berhasil, coba setup database
                    $db_setup_result = $this->setup_database_schema($db_name);
                    if ($db_setup_result === TRUE) {
                        // Buat file penanda instalasi selesai
                        $this->create_install_flag();
                        // Instalasi berhasil, redirect ke halaman utama
                        redirect(base_url() . '?install_success=true');
                    } else {
                        // Gagal setup skema database
                        // Hapus file konfigurasi yang mungkin sudah setengah jadi agar bisa coba lagi
                        $this->delete_config_files_on_error();
                        $data['error'] = "Gagal melakukan setup skema database: " . $db_setup_result;
                        $this->load->view('install_view', $data);
                    }
                } else {
                    // Gagal update config.php
                    $data['error'] = 'Gagal memperbarui file config.php. Pastikan file tersebut writable.';
                    $this->load->view('install_view', $data);
                }
            } else {
                // Gagal update database.php
                $data['error'] = 'Gagal memperbarui file database.php. Pastikan file tersebut writable.';
                $this->load->view('install_view', $data);
            }
        }
    }

    private function delete_config_files_on_error() {
        // Fungsi ini bisa digunakan untuk 'mereset' sebagian jika instalasi gagal di tengah jalan
        // Misalnya, menghapus installed.txt atau mengembalikan placeholder di config
        // Untuk saat ini, kita tidak implementasi rollback file config secara otomatis,
        // tapi ini adalah tempat yang baik jika diperlukan.
        // Cukup pastikan installed.txt tidak dibuat jika ada error.
        $flag_file = APPPATH . 'config/installed.txt';
        if (file_exists($flag_file)) {
            @unlink($flag_file);
        }
        // Idealnya, kita juga mengembalikan database.php dan config.php ke keadaan semula
        // atau menghapusnya jika itu adalah bagian dari logika instalasi yang lebih kompleks.
        // Namun, untuk saat ini, error message akan meminta user memastikan file writable dan mencoba lagi.
    }


    private function setup_database_schema($db_name) {
        // 1. Coba buat database jika belum ada
        if ($this->dbforge->create_database($db_name)) {
            // Database berhasil dibuat atau sudah ada
        } else {
            // Bisa jadi database sudah ada dan user tidak punya privilege CREATE DATABASE
            // Kita akan coba lanjut dengan asumsi database sudah ada
            // Namun, kita perlu cara yang lebih baik untuk mengecek ini
            // Untuk sekarang, kita log atau abaikan error ini jika koneksi tetap berhasil nanti
        }

        // Muat ulang konfigurasi database yang baru saja ditulis
        // Ini penting karena CI mungkin sudah memuat konfigurasi lama (kosong)
        $this->load->database(null, FALSE, TRUE); // Argumen ketiga TRUE untuk return instance DB

        // Cek koneksi database
        if (!$this->db->conn_id) {
            return "Tidak dapat terhubung ke database menggunakan konfigurasi yang diberikan.";
        }

        // Baca file schema.sql
        $schema_path = APPPATH . 'config/schema.sql';
        if (!file_exists($schema_path)) {
            return "File schema.sql tidak ditemukan di application/config/";
        }
        $sql_queries = file_get_contents($schema_path);
        if (empty($sql_queries)) {
            return "File schema.sql kosong.";
        }

        // Pisahkan multiple queries (jika ada, dipisahkan oleh ';')
        // dan filter query kosong
        $queries = array_filter(array_map('trim', explode(';', $sql_queries)));

        foreach ($queries as $query) {
            if (!empty($query)) {
                $this->db->query($query);
                // Error handling per query bisa ditambahkan di sini jika perlu
                // Namun, CI akan throw exception jika ada error SQL fatal
            }
        }

        // Cek apakah tabel utama (misal 'users') sudah ada sebagai tanda skema berhasil
        if ($this->db->table_exists('users')) {
            return TRUE;
        } else {
            // Ini bisa terjadi jika ada error SQL yang tidak fatal tapi tabel tidak dibuat
            return "Gagal membuat tabel-tabel dasar. Periksa error SQL atau isi file schema.sql.";
        }
    }

    private function is_configured() {
        // Cara sederhana: cek apakah file flag 'installed.txt' ada
        return file_exists(APPPATH . 'config/installed.txt');
    }

    private function create_install_flag() {
        $flag_file = APPPATH . 'config/installed.txt';
        if (!file_exists($flag_file)) {
            // Pastikan tidak ada error sebelum membuat flag
            @touch($flag_file);
        }
    }

    private function update_database_config($host, $dbname, $username, $password) {
        $db_path = APPPATH . 'config/database.php';
        if (is_writable($db_path)) {
            $content = file_get_contents($db_path);

            // Ganti nilai placeholder dengan nilai sebenarnya
            $content = preg_replace("/'hostname' => '.*?',/", "'hostname' => '{$host}',", $content);
            $content = preg_replace("/'username' => '.*?',/", "'username' => '{$username}',", $content);
            $content = preg_replace("/'password' => '.*?',/", "'password' => '{$password}',", $content);
            $content = preg_replace("/'database' => '.*?',/", "'database' => '{$dbname}',", $content);

            if (file_put_contents($db_path, $content) !== FALSE) {
                return TRUE;
            }
        }
        return FALSE;
    }

    private function update_config_file($base_url) {
        $config_path = APPPATH . 'config/config.php';
        if (is_writable($config_path)) {
            $content = file_get_contents($config_path);

            // Pastikan base_url memiliki trailing slash
            $base_url = rtrim($base_url, '/') . '/';

            // Ganti nilai base_url
            $content = preg_replace("/\\\$config\['base_url'\] = '.*?'/", "\$config['base_url'] = '{$base_url}'", $content);

            if (file_put_contents($config_path, $content) !== FALSE) {
                return TRUE;
            }
        }
        return FALSE;
    }
}
