<h3 class="mt-0 mb-4 text-primary"><i class="fas fa-cash-register"></i> <?php echo $title; ?></h3>

<div class="row">
    <!-- Kolom Kiri: Pencarian Produk dan Form Tambah ke Keranjang -->
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <i class="fas fa-search"></i> Cari & Tambah Produk
            </div>
            <div class="card-body">
                <?php echo form_open('kasir/transaksi/tambah_ke_keranjang', ['id' => 'form_tambah_keranjang_kasir']); ?>
                    <div class="form-group">
                        <label for="search_produk_kasir">Cari Produk (Nama/ID)</label>
                        <input type="text" id="search_produk_kasir" class="form-control search-product-input" placeholder="Ketik nama atau ID produk...">
                    </div>

                    <input type="hidden" name="produk_id" id="produk_id_kasir">

                    <div class="form-group">
                        <label for="nama_produk_display_kasir">Nama Produk Terpilih</label>
                        <input type="text" id="nama_produk_display_kasir" class="form-control" readonly>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="harga_produk_display_kasir">Harga (Rp)</label>
                            <input type="text" id="harga_produk_display_kasir" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="stok_produk_display_kasir">Stok Tersedia</label>
                            <input type="text" id="stok_produk_display_kasir" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jumlah_kasir">Jumlah Beli</label>
                        <input type="number" name="jumlah" id="jumlah_kasir" class="form-control" value="1" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-cart-plus"></i> Tambah ke Keranjang</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Keranjang Belanja dan Pembayaran -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="fas fa-shopping-cart"></i> Keranjang Belanja
            </div>
            <div class="card-body" id="area-keranjang">
                <?php if (empty($cart_items)): ?>
                    <div class="alert alert-info text-center">Keranjang belanja masih kosong.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-center" style="width:100px;">Jumlah</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-center" style="width:80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td class="text-right">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <?php echo form_open('kasir/transaksi/update_keranjang_item/' . $item['rowid'], ['class' => 'form-inline justify-content-center']); ?>
                                            <input type="number" name="qty" value="<?php echo $item['qty']; ?>" class="form-control form-control-sm" style="width: 60px;" min="1" onchange="this.form.submit()">
                                        <?php echo form_close(); ?>
                                    </td>
                                    <td class="text-right">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo base_url('kasir/transaksi/hapus_item_keranjang/' . $item['rowid']); ?>" class="btn btn-danger btn-sm" title="Hapus Item">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td colspan="3" class="text-right">Total Belanja:</td>
                                    <td class="text-right total-display" id="total_belanja_display">Rp <?php echo number_format($this->cart->total(), 0, ',', '.'); ?></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <a href="<?php echo base_url('kasir/transaksi/hapus_keranjang'); ?>" class="btn btn-warning btn-sm float-right" onclick="return confirm('Anda yakin ingin mengosongkan keranjang?')">
                        <i class="fas fa-times-circle"></i> Kosongkan Keranjang
                    </a>
                    <div class="clearfix mb-3"></div>
                    <hr>

                    <!-- Form Pembayaran -->
                    <h5 class="text-primary">Form Pembayaran</h5>
                     <?php if($this->session->flashdata('error_bayar')): ?>
                        <div class="alert alert-danger small">
                            <?php echo $this->session->flashdata('error_bayar'); ?>
                        </div>
                    <?php endif; ?>
                    <?php echo form_open('kasir/transaksi/proses_pembayaran', ['id' => 'form-pembayaran']); ?>
                        <div class="form-group row">
                            <label for="bayar_transaksi" class="col-sm-4 col-form-label">Jumlah Bayar (Rp)</label>
                            <div class="col-sm-8">
                                <input type="text" name="bayar" id="bayar_transaksi" class="form-control form-control-lg <?php echo form_error('bayar') ? 'is-invalid' : ''; ?>" placeholder="0" required
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                <?php if(form_error('bayar')): ?><div class="invalid-feedback"><?php echo form_error('bayar'); ?></div><?php endif; ?>
                            </div>
                        </div>
                         <div class="form-group row">
                            <label for="kembalian_transaksi_display" class="col-sm-4 col-form-label">Kembalian (Rp)</label>
                            <div class="col-sm-8">
                                <span id="kembalian_transaksi_display" class="form-control-plaintext form-control-lg font-weight-bold">Rp 0</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="catatan_transaksi">Catatan (Opsional)</label>
                            <textarea name="catatan" id="catatan_transaksi" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg btn-block mt-3"><i class="fas fa-check-circle"></i> Proses & Simpan Transaksi</button>
                    <?php echo form_close(); ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
```
