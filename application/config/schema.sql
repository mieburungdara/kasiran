-- Tabel: kategori_produk
CREATE TABLE IF NOT EXISTS `kategori_produk` (
  `id_kategori` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel: produk
CREATE TABLE IF NOT EXISTS `produk` (
  `id_produk` INT(11) NOT NULL AUTO_INCREMENT,
  `kategori_produk_id` INT(11) NOT NULL,
  `nama_produk` VARCHAR(255) NOT NULL,
  `harga` DECIMAL(10,2) NOT NULL,
  `stok` INT(11) NOT NULL DEFAULT 0,
  `gambar_produk` VARCHAR(255) DEFAULT NULL,
  `deskripsi_produk` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_produk`),
  KEY `fk_produk_kategori` (`kategori_produk_id`),
  CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`kategori_produk_id`) REFERENCES `kategori_produk` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel: users
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `level` ENUM('admin','kasir') NOT NULL DEFAULT 'kasir',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel: transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id_transaksi` VARCHAR(20) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `tanggal_transaksi` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_item` INT(11) NOT NULL,
  `total_harga` DECIMAL(15,2) NOT NULL,
  `bayar` DECIMAL(15,2) NOT NULL,
  `kembali` DECIMAL(15,2) NOT NULL,
  `catatan` TEXT DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `fk_transaksi_user` (`user_id`),
  CONSTRAINT `fk_transaksi_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel: detail_transaksi
CREATE TABLE IF NOT EXISTS `detail_transaksi` (
  `id_detail` INT(11) NOT NULL AUTO_INCREMENT,
  `transaksi_id` VARCHAR(20) NOT NULL,
  `produk_id` INT(11) NOT NULL,
  `harga_produk` DECIMAL(10,2) NOT NULL,
  `jumlah` INT(11) NOT NULL,
  `diskon_item` DECIMAL(10,2) DEFAULT 0.00,
  `subtotal` DECIMAL(15,2) NOT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `fk_detail_transaksi` (`transaksi_id`),
  KEY `fk_detail_produk` (`produk_id`),
  CONSTRAINT `fk_detail_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detail_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id_produk`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Awal yang Di-generate (Contoh):

-- Kategori Produk
INSERT INTO `kategori_produk` (`nama_kategori`) VALUES
('Makanan Ringan'),
('Minuman Dingin'),
('Keperluan Mandi')
ON DUPLICATE KEY UPDATE nama_kategori=VALUES(nama_kategori);

-- Users (Password: "password123", contoh hash: $2y$10$EaPVIZfLyF74Le5jZ0493u6f2rF.M0.Fif9jQP.B3uQk8mJ9G32ye)
-- Ganti dengan hash yang benar saat implementasi. Untuk keperluan instalasi ini, kita pakai contoh hash.
-- Jika ingin password 'admin', hashnya adalah $2y$10$Nfklx7Q4S6X.vlX.HAb3A.9xG1xM05P7hSjTHX8mubF.ML0v.x/LS (password: admin)
-- Jika ingin password 'password', hashnya adalah $2y$10$EaPVIZfLyF74Le5jZ0493u6f2rF.M0.Fif9jQP.B3uQk8mJ9G32ye (password: password)
INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `level`) VALUES
('admin', '$2y$10$Nfklx7Q4S6X.vlX.HAb3A.9xG1xM05P7hSjTHX8mubF.ML0v.x/LS', 'Administrator', 'admin'),
('kasir1', '$2y$10$EaPVIZfLyF74Le5jZ0493u6f2rF.M0.Fif9jQP.B3uQk8mJ9G32ye', 'Kasir Satu', 'kasir')
ON DUPLICATE KEY UPDATE username=VALUES(username), password=VALUES(password), nama_lengkap=VALUES(nama_lengkap), level=VALUES(level);

-- Produk
INSERT INTO `produk` (`kategori_produk_id`, `nama_produk`, `harga`, `stok`) VALUES
((SELECT id_kategori FROM kategori_produk WHERE nama_kategori='Makanan Ringan' LIMIT 1), 'Keripik Kentang Gurih 50gr', 7500.00, 100),
((SELECT id_kategori FROM kategori_produk WHERE nama_kategori='Minuman Dingin' LIMIT 1), 'Teh Botol Sosro Kotak 250ml', 3500.00, 150),
((SELECT id_kategori FROM kategori_produk WHERE nama_kategori='Makanan Ringan' LIMIT 1), 'Biskuit Coklat Enak 100gr', 12000.00, 70),
((SELECT id_kategori FROM kategori_produk WHERE nama_kategori='Keperluan Mandi' LIMIT 1), 'Sabun Mandi Cair 250ml', 18000.00, 50)
ON DUPLICATE KEY UPDATE nama_produk=VALUES(nama_produk), harga=VALUES(harga), stok=VALUES(stok);
