# Aplikasi Kasir (Point of Sale)

Aplikasi kasir sederhana yang dibangun menggunakan CodeIgniter 3.

## Table of Contents

- [Fitur](#fitur)
- [Instalasi](#instalasi)
- [Struktur Direktori](#struktur-direktori)
- [Contributing](#contributing)
- [License](#license)
- [Catatan](#catatan)

## Fitur

*   Manajemen Produk (Product Management)
*   Manajemen Kategori Produk (Product Category Management)
*   Manajemen Pengguna (Admin dan Kasir) (User Management - Admin and Cashier roles)
*   Transaksi Penjualan (Sales Transactions)
*   Laporan Penjualan (Sales Reports)

## Instalasi

1.  **Persyaratan Server**: Pastikan server Anda memenuhi [persyaratan CodeIgniter 3](https://codeigniter.com/userguide3/installation/requirements.html).
    *   PHP version 5.6 or newer is recommended.
    *   Supported Databases: MySQL (5.1+), PostgreSQL, MS SQL, SQLite, etc. (as per CI3 documentation).
2.  **Clone Repositori**:
    ```bash
    git clone <URL_REPOSITORI_ANDA> nama_direktori_proyek
    cd nama_direktori_proyek
    ```
    *(Ganti `<URL_REPOSITORI_ANDA>` dengan URL aktual repositori Anda jika sudah ada di Git)*
3.  **Konfigurasi Database**:
    *   Buka file `application/config/database.php`.
    *   Sesuaikan pengaturan koneksi database (hostname, username, password, database name) sesuai dengan konfigurasi server Anda.
    ```php
    $db['default'] = array(
        'dsn'   => '',
        'hostname' => 'localhost', // Ganti jika perlu
        'username' => 'root',      // Ganti dengan username database Anda
        'password' => '',          // Ganti dengan password database Anda
        'database' => 'nama_database_kasir', // Ganti dengan nama database Anda
        'dbdriver' => 'mysqli',
        // ... pengaturan lainnya
    );
    ```
4.  **Import Database**:
    *   Jika file SQL untuk struktur database dan data awal tersedia di direktori `database/` (misalnya `database.sql`), import file tersebut ke database yang telah Anda konfigurasi.
    *   Anda bisa menggunakan alat seperti phpMyAdmin atau perintah command-line:
        ```bash
        mysql -u username_anda -p nama_database_anda < database/nama_file_database.sql
        ```
5.  **Akses Aplikasi**:
    *   Arahkan browser Anda ke URL root proyek Anda (misalnya `http://localhost/nama_direktori_proyek`).
    *   Aplikasi siap digunakan.

## Struktur Direktori

Berikut adalah struktur direktori utama aplikasi setelah pemindahan file dari subdirektori `kasir_ci3` ke root:

*   `application/`: Berisi logika inti aplikasi (controllers, models, views).
    *   `config/`: File konfigurasi aplikasi.
    *   `controllers/`: Mengatur alur permintaan.
    *   `models/`: Berinteraksi dengan database.
    *   `views/`: Menampilkan data ke pengguna.
*   `assets/`: Berisi file statis seperti CSS, JavaScript, gambar, dll.
*   `system/`: Direktori inti CodeIgniter (umumnya tidak perlu diubah).
*   `database/`: (Jika ada) Berisi file dump SQL atau skema database.
*   `index.php`: File utama (front controller) untuk menjalankan aplikasi.
*   `.htaccess`: (Jika ada) Digunakan untuk konfigurasi server web, misalnya untuk URL yang bersih (menghilangkan `index.php` dari URL).

## Contributing

Kontribusi sangat diharapkan! Jika Anda ingin berkontribusi:

1.  **Laporkan Bug**: Gunakan bagian "Issues" di repositori ini untuk melaporkan bug. Sertakan langkah-langkah untuk mereproduksi bug tersebut.
2.  **Saran Fitur**: Gunakan bagian "Issues" untuk menyarankan fitur baru atau perbaikan.
3.  **Pull Requests**:
    *   Fork repositori ini.
    *   Buat branch baru untuk fitur atau perbaikan Anda (`git checkout -b nama-branch-anda`).
    *   Lakukan perubahan Anda dan commit (`git commit -am 'Menambahkan fitur X'`).
    *   Push ke branch Anda (`git push origin nama-branch-anda`).
    *   Buat Pull Request baru.

## License

Proyek ini dilisensikan di bawah [MIT License](LICENSE). (Anda bisa membuat file `LICENSE` dengan teks lisensi MIT jika belum ada).

## Catatan

Repositori ini telah dimodifikasi untuk memindahkan semua file dari subdirektori `kasir_ci3` ke direktori root untuk struktur proyek yang lebih standar. Hal ini dilakukan untuk menyederhanakan deployment dan mengikuti praktik umum struktur proyek PHP.

---

*README ini dibuat berdasarkan informasi yang diberikan. Anda dapat menyesuaikan dan menambahkan detail lebih lanjut sesuai kebutuhan.*
