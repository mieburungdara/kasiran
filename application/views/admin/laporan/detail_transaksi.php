<h3 class="mt-0 mb-4 text-primary"><i class="fas fa-receipt"></i> <?php echo $title; ?></h3>

<div class="struk-container" style="margin: 0 auto 20px auto; border: 1px solid #dee2e6; padding: 20px; background-color: #fff; max-width: 400px;">
    <div class="struk-header">
        <h5>NAMA TOKO ANDA</h5>
        <p style="font-size: 0.8em;">Alamat Toko Anda, Kota, No. Telp</p>
        <p>-----------------------------------</p>
    </div>

    <div class="struk-info">
        <table style="font-size: 0.9em;">
            <tr>
                <td style="width: 100px;">No. Transaksi</td>
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
    <p>-----------------------------------</p>

    <div class="struk-items">
        <table style="font-size: 0.9em;">
            <?php foreach ($detail_transaksi as $item): ?>
            <tr>
                <td colspan="2" class="item-name"><?php echo htmlspecialchars($item->nama_produk); ?></td>
            </tr>
            <tr>
                <td class="item-qty" style="padding-left:10px;">
                    <?php echo $item->jumlah; ?> x <?php echo number_format($item->harga_produk, 0, ',', '.'); ?>
                </td>
                <td class="item-subtotal">
                    <?php echo number_format($item->subtotal, 0, ',', '.'); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <p>-----------------------------------</p>

    <div class="struk-total">
        <table style="font-size: 0.9em;">
            <tr>
                <td>Total Item</td>
                <td><?php echo $transaksi->total_item; ?></td>
            </tr>
            <tr>
                <td>Subtotal</td>
                <td><?php echo number_format($transaksi->total_harga, 0, ',', '.'); ?></td>
            </tr>
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
    <p>-----------------------------------</p>
    <div class="struk-info">
        <p style="font-size: 0.8em;">Catatan: <?php echo htmlspecialchars($transaksi->catatan); ?></p>
    </div>
    <?php endif; ?>
    <p>-----------------------------------</p>
    <div class="struk-footer" style="font-size: 0.8em;">
        <p>Terima Kasih!</p>
    </div>
</div>

<div class="text-center mb-4">
    <button onclick="window.print();" class="btn btn-primary no-print"><i class="fas fa-print"></i> Cetak Detail</button>
    <a href="<?php echo base_url('admin/laporan/penjualan'); ?>" class="btn btn-secondary no-print"><i class="fas fa-arrow-left"></i> Kembali ke Laporan Penjualan</a>
</div>
