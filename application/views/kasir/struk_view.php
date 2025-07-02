<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" media="screen"> <!-- Untuk tombol, bukan untuk struk -->
    <style>
        /* Style khusus untuk struk ada di style.css, ini hanya untuk tombol print jika di luar struk-container */
        .button-area {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="struk-container">
        <div class="struk-header">
            <h4>NAMA TOKO ANDA</h4>
            <p>Alamat Toko Anda, Kota, No. Telp</p>
            <p>--------------------------------</p>
        </div>

        <div class="struk-info">
            <table>
                <tr>
                    <td>No. Transaksi</td>
                    <td>: <?php echo htmlspecialchars($transaksi->id_transaksi); ?></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: <?php echo date('d/m/Y H:i:s', strtotime($transaksi->tanggal_transaksi)); ?></td>
                </tr>
                <tr>
                    <td>Kasir</td>
                    <td>: <?php echo htmlspecialchars($transaksi->nama_kasir); ?></td>
                </tr>
            </table>
        </div>
        <p>--------------------------------</p>

        <div class="struk-items">
            <table>
                <!-- Tidak perlu thead untuk struk thermal biasanya -->
                <?php foreach ($detail_transaksi as $item): ?>
                <tr>
                    <td colspan="2" class="item-name"><?php echo htmlspecialchars($item->nama_produk); ?></td>
                </tr>
                <tr>
                    <td class="item-qty">
                        <?php echo $item->jumlah; ?> x <?php echo number_format($item->harga_produk, 0, ',', '.'); ?>
                    </td>
                    <td class="item-subtotal">
                        <?php echo number_format($item->subtotal, 0, ',', '.'); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <p>--------------------------------</p>

        <div class="struk-total">
            <table>
                <tr>
                    <td>Total Item</td>
                    <td><?php echo $transaksi->total_item; ?></td>
                </tr>
                <tr>
                    <td>Subtotal</td>
                    <td><?php echo number_format($transaksi->total_harga, 0, ',', '.'); ?></td>
                </tr>
                <!-- Jika ada diskon umum atau pajak bisa ditambahkan di sini -->
                <tr class="grand-total">
                    <td><strong>TOTAL</strong></td>
                    <td><strong><?php echo number_format($transaksi->total_harga, 0, ',', '.'); ?></strong></td>
                </tr>
                <tr>
                    <td>Bayar</td>
                    <td><?php echo number_format($transaksi->bayar, 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Kembali</td>
                    <td><?php echo number_format($transaksi->kembali, 0, ',', '.'); ?></td>
                </tr>
            </table>
        </div>

        <?php if(!empty($transaksi->catatan)): ?>
        <p>--------------------------------</p>
        <div class="struk-info">
            <p style="font-size: 9pt;">Catatan: <?php echo htmlspecialchars($transaksi->catatan); ?></p>
        </div>
        <?php endif; ?>
        <p>--------------------------------</p>
        <div class="struk-footer">
            <p>Terima Kasih Telah Berbelanja!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
        </div>
    </div>

    <div class="button-area no-print">
        <button onclick="window.print();" class="btn btn-primary"><i class="fas fa-print"></i> Cetak Struk</button>
        <a href="<?php echo base_url('kasir/transaksi'); ?>" class="btn btn-success"><i class="fas fa-plus"></i> Transaksi Baru</a>
        <?php if($this->session->userdata('level') == 'admin'): ?>
            <a href="<?php echo base_url('admin/laporan/penjualan'); ?>" class="btn btn-info"><i class="fas fa-file-alt"></i> Kembali ke Laporan</a>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- Untuk tombol print jika script.js tidak diload -->
    <script src="<?php echo base_url('assets/js/script.js'); ?>"></script>
</body>
</html>
