<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|   example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|   https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|   $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|   $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|   $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> My_controller/index
|       my-controller/my-method -> My_controller/my_method
*/

// Default controller akan dicek di hook atau index.php untuk instalasi
// Jika instalasi selesai, default_controller akan menjadi 'auth'
// Jika belum, akan diarahkan ke 'install'
if (file_exists(APPPATH . 'config/installed.txt')) {
    $route['default_controller'] = 'auth'; // Mengarahkan ke halaman login sebagai default
} else {
    $route['default_controller'] = 'install';
    $route['install'] = 'install/index';
    $route['install/setup'] = 'install/setup';
}

$route['404_override'] = ''; // Bisa diatur nanti jika ada halaman error custom
$route['translate_uri_dashes'] = FALSE;

// Rute untuk Admin
$route['admin'] = 'admin/dashboard';
$route['admin/dashboard'] = 'admin/dashboard/index';
$route['admin/kategori'] = 'admin/kategori/index';
$route['admin/kategori/tambah'] = 'admin/kategori/tambah';
$route['admin/kategori/edit/(:num)'] = 'admin/kategori/edit/$1';
$route['admin/kategori/hapus/(:num)'] = 'admin/kategori/hapus/$1';

$route['admin/produk'] = 'admin/produk/index';
$route['admin/produk/tambah'] = 'admin/produk/tambah';
$route['admin/produk/edit/(:num)'] = 'admin/produk/edit/$1';
$route['admin/produk/hapus/(:num)'] = 'admin/produk/hapus/$1';

$route['admin/users'] = 'admin/users/index';
$route['admin/users/tambah'] = 'admin/users/tambah';
$route['admin/users/edit/(:num)'] = 'admin/users/edit/$1';
$route['admin/users/hapus/(:num)'] = 'admin/users/hapus/$1';

$route['admin/laporan'] = 'admin/laporan/penjualan'; // Default laporan adalah penjualan
$route['admin/laporan/penjualan'] = 'admin/laporan/penjualan';
$route['admin/laporan/produk_terlaris'] = 'admin/laporan/produk_terlaris';
$route['admin/laporan/detail_transaksi/(:any)'] = 'admin/laporan/detail_transaksi/$1';


// Rute untuk Kasir
// kasir/dashboard/index akan mengarah ke kasir/transaksi (sesuai logic di Kasir/Dashboard controller)
$route['kasir'] = 'kasir/dashboard';
$route['kasir/dashboard'] = 'kasir/dashboard/index';

$route['kasir/transaksi'] = 'kasir/transaksi/index';
$route['kasir/transaksi/cari_produk'] = 'kasir/transaksi/cari_produk';
$route['kasir/transaksi/tambah_ke_keranjang'] = 'kasir/transaksi/tambah_ke_keranjang';
$route['kasir/transaksi/update_keranjang_item/(:any)'] = 'kasir/transaksi/update_keranjang_item/$1';
$route['kasir/transaksi/hapus_item_keranjang/(:any)'] = 'kasir/transaksi/hapus_item_keranjang/$1';
$route['kasir/transaksi/hapus_keranjang'] = 'kasir/transaksi/hapus_keranjang';
$route['kasir/transaksi/proses_pembayaran'] = 'kasir/transaksi/proses_pembayaran';
$route['kasir/transaksi/struk/(:any)'] = 'kasir/transaksi/struk/$1';


// Rute untuk Auth
$route['login'] = 'auth/login'; // Alias untuk auth/login
$route['logout'] = 'auth/logout';

// Controller Dashboard umum tidak lagi digunakan secara langsung karena redirect
// $route['dashboard'] = 'dashboard';

?>
