<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load helper yang dibutuhkan
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        // $this->load->database(); // Akan diload setelah konfigurasi disimpan
        // dbforge akan diload jika diperlukan dalam setup_database_schema
    }

    private function determine_current_url_base() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';

        $script_name = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''; // contoh: /kasiran/index.php atau /index.php

        // Hapus nama file skrip (misalnya, index.php) dari path
        $path_parts = pathinfo($script_name);
        $directory = $path_parts['dirname']; // contoh: /kasiran atau /

        // Jika direktori adalah root ('/'), maka jadikan string kosong
        if ($directory === '/' || $directory === '\\') {
            $directory = '';
        }

        return rtrim($protocol . $host . $directory, '/') . '/';
    }

    public function index() {
        // Cek apakah konfigurasi sudah ada
        if ($this->is_configured()) {
            // Jika sudah terkonfigurasi, base_url dari config seharusnya sudah benar
            redirect($this->config->item('base_url'));
        }
        $data['form_action'] = $this->determine_current_url_base() . 'install/setup';
        $data['suggested_base_url'] = $this->determine_current_url_base();
        $this->load->view('install_view', $data);
    }

    public function setup() {
        if ($this->is_configured()) {
            // Jika sudah terkonfigurasi, base_url dari config seharusnya sudah benar
            redirect($this->config->item('base_url'));
        }

        // Atur rules validasi
        $this->form_validation->set_rules('db_host', 'Database Host', 'required');
        $this->form_validation->set_rules('db_name', 'Database Name', 'required');
        $this->form_validation->set_rules('db_user', 'Database User', 'required');
        // Password boleh kosong
        $this->form_validation->set_rules('db_pass', 'Database Password', 'trim');
        $this->form_validation->set_rules('base_url', 'Base URL', 'required|trim');

        $data['form_action'] = $this->determine_current_url_base() . 'install/setup'; // Untuk error case
        $data['suggested_base_url'] = $this->determine_current_url_base(); // Untuk error case

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, tampilkan kembali form instalasi
            $this->load->view('install_view', $data);
        } else {
            // Proses penyimpanan konfigurasi
            $db_host = $this->input->post('db_host');
            $db_name = $this->input->post('db_name');
            $db_user = $this->input->post('db_user');
            $db_pass = $this->input->post('db_pass');
            $base_url_input = rtrim($this->input->post('base_url'), '/') . '/';

            // Update file database.php
            if ($this->update_database_config($db_host, $db_name, $db_user, $db_pass)) {
                // Update file config.php
                if ($this->update_config_file($base_url_input)) {
                    // Setelah konfigurasi file berhasil, coba setup database
                    $db_setup_result = $this->setup_database_schema($db_name);
                    if ($db_setup_result === TRUE) {
                        // Buat file penanda instalasi selesai
                        $this->create_install_flag();
                        // Instalasi berhasil, redirect ke halaman utama menggunakan base_url dari input
                        redirect($base_url_input . '?install_success=true');
                    } else {
                        // Gagal setup skema database
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
        // Simpan state db_debug saat ini dan matikan sementara
        $original_db_debug_config = $this->config->item('db_debug');
        $this->config->set_item('db_debug', FALSE); // Matikan debug global CI

        // HAPUS instance config database yang mungkin sudah ada di CI Config object
        // Ini memaksa CI untuk membaca ulang dari file saat $this->load->database() berikutnya
        if (isset($this->config->config['database'])) {
            unset($this->config->config['database']);
            log_message('debug', 'Unset $this->config->config[\'database\']');
        }
        if (isset($this->config->config['db'])) { // Alias
            unset($this->config->config['db']);
            log_message('debug', 'Unset $this->config->config[\'db\']');
        }

        // Hapus juga instance $this->db jika sudah ada, untuk memaksa re-inisialisasi penuh
        if (isset($this->db)) {
            // Tutup koneksi lama jika $this->db ada dan memiliki koneksi aktif
            if ($this->db->conn_id) {
                $this->db->close();
                log_message('debug', 'Old $this->db connection closed.');
            }
            unset($this->db);
            log_message('debug', 'Unset $this->db');
        }

        // Sebagai tindakan lebih lanjut, pastikan CI Loader juga tidak memiliki cache internal untuk 'database'
        // Ini mungkin tidak diperlukan karena $this->load->database() seharusnya menangani ini, tapi untuk jaga-jaga
        if (isset($this->load->_ci_loaded_files) && in_array('database', $this->load->_ci_loaded_files)) {
             $this->load->_ci_loaded_files = array_diff($this->load->_ci_loaded_files, array('database'));
             log_message('debug', 'Removed "database" from $this->load->_ci_loaded_files');
        }
        // Ini lebih bersifat internal dan mungkin tidak aman untuk semua versi CI, jadi gunakan dengan hati-hati
        // atau lewati jika tidak yakin. Untuk CI3, _ci_loaded_files ada di Loader.

        // Muat konfigurasi database dari file database.php yang baru ditulis
        log_message('debug', 'Attempting to load database configuration...');
        $this->load->database(null, FALSE, TRUE); // TRUE untuk return instance, FALSE untuk tidak auto-init

        // Periksa koneksi ke server database (tanpa harus memilih $db_name dulu)
        if (!$this->db->conn_id || $this->db->conn_id === FALSE) {
            $this->config->set_item('db_debug', $original_db_debug_config); // Kembalikan db_debug
            log_message('error', 'Failed to connect to DB server after attempting to load new config.');
            return "Tidak dapat terhubung ke server database. Periksa hostname, username, dan password.";
        }
        log_message('info', 'Successfully connected to DB server after loading new config.');

        // Cek apakah database $db_name sudah ada
        $query = $this->db->query("SHOW DATABASES LIKE ".$this->db->escape($db_name));
        $database_exists = ($query && $query->num_rows() > 0);

        if (!$database_exists) {
            $this->load->dbforge(); // Load dbforge hanya jika kita benar-benar perlu membuat DB
            if (!$this->dbforge->create_database($db_name)) {
                $this->config->set_item('db_debug', $original_db_debug_config); // Kembalikan db_debug
                return "Gagal membuat database '{$db_name}'. Pastikan user memiliki hak akses CREATE DATABASE atau buat database secara manual.";
            }
            // Setelah membuat database, kita perlu memastikan koneksi berikutnya menggunakannya.
            // Menutup dan memuat ulang koneksi adalah cara yang paling pasti.
            $this->db->close();
            $this->load->database(null, FALSE, TRUE); // Re-load dengan $db_name sekarang seharusnya ada di config

            if (!$this->db->conn_id || $this->db->conn_id === FALSE || !$this->db->db_select($db_name)) {
                 $this->config->set_item('db_debug', $original_db_debug_config);
                 return "Berhasil membuat database '{$db_name}' tetapi gagal terkoneksi atau memilihnya setelah pembuatan.";
            }
        } else {
            // Database sudah ada, pastikan kita terhubung dan memilihnya
            if (!$this->db->db_select($db_name)) {
                $this->config->set_item('db_debug', $original_db_debug_config);
                return "Database '{$db_name}' sudah ada, tetapi gagal memilihnya (select). Periksa hak akses user database.";
            }
        }

        // Pada titik ini, kita seharusnya sudah terhubung ke $db_name yang benar.
        // Kembalikan db_debug ke state semula sebelum eksekusi schema.sql
        $this->config->set_item('db_debug', $original_db_debug_config);
        if(isset($this->db) && $this->db->conn_id){ // Pastikan objek db ada dan terkoneksi
            $this->db->db_debug = $original_db_debug_config;
        }

        // Cek koneksi sekali lagi untuk memastikan kita benar-benar terhubung ke database yang benar
        if (!$this->db->conn_id || $this->db->conn_id === FALSE || $this->db->database !== $db_name) {
            // Ini seharusnya tidak terjadi jika logika di atas benar, tapi sebagai fallback.
            return "Gagal memastikan koneksi ke database '{$db_name}' yang spesifik sebelum menjalankan skema. Proses instalasi tidak dapat dilanjutkan.";
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
            if ($content === FALSE) {
                log_message('error', 'Gagal membaca database.php untuk update.');
                return FALSE;
            }

            // Escape input data to prevent issues with special characters in replacements
            $host_escaped = addslashes($host);
            $username_escaped = addslashes($username);
            $password_escaped = addslashes($password);
            $dbname_escaped = addslashes($dbname);

            $patterns = array(
                "/'hostname' => '.*?',/",
                "/'username' => '.*?',/",
                "/'password' => '.*?',/",
                "/'database' => '.*?',/"
            );
            $replacements = array(
                "'hostname' => '{$host_escaped}',",
                "'username' => '{$username_escaped}',",
                "'password' => '{$password_escaped}',",
                "'database' => '{$dbname_escaped}',"
            );

            $new_content = preg_replace($patterns, $replacements, $content);

            // CRITICAL: Check if preg_replace failed (it returns NULL on error)
            if ($new_content === NULL) {
                // Log the error from preg_replace
                $preg_error = preg_last_error_msg();
                log_message('error', "preg_replace failed while updating database.php. Error: {$preg_error}. Input data (escaped): host='{$host_escaped}', user='{$username_escaped}', db='{$dbname_escaped}'.");
                return FALSE;
            }

            log_message('debug', 'Konten database.php SEBELUM ditulis ulang: ' . $content);
            log_message('debug', 'Konten database.php AKAN DITULIS: ' . $new_content);

            if (file_put_contents($db_path, $new_content) !== FALSE) {
                // Coba invalidate OPcache jika ada
                if (function_exists('opcache_invalidate')) {
                    opcache_invalidate($db_path, TRUE);
                    log_message('debug', 'opcache_invalidate dipanggil untuk database.php');
                }
                // Verifikasi dengan membaca kembali
                // clearstatcache agar file_get_contents berikutnya membaca dari disk, bukan cache stat PHP
                clearstatcache(true, $db_path);
                $verify_content = file_get_contents($db_path);
                log_message('debug', 'Konten database.php SETELAH ditulis ulang (verifikasi): ' . $verify_content);

                // Perbandingan harusnya case-sensitive dan exact.
                // Menghapus spasi/baris baru di akhir untuk perbandingan yang lebih robust jika editor menambahkan/menghilangkannya.
                if (trim($verify_content) === trim($new_content)) {
                    log_message('info', 'Berhasil update dan verifikasi database.php');
                    return TRUE;
                } else {
                    log_message('error', 'Gagal verifikasi penulisan database.php. Konten tidak cocok.');
                    log_message('error', 'VERIFY CONTENT: ' . $verify_content); // Log konten yang terbaca untuk debug
                    return FALSE;
                }
            } else {
                log_message('error', 'Gagal menulis ke database.php.');
                return FALSE;
            }
        } else {
            log_message('error', 'database.php tidak writable: ' . $db_path);
            // return FALSE; // Sudah implisit oleh akhir fungsi
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
