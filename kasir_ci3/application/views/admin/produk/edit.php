<h3 class="mt-0 mb-4 text-primary"><i class="fas fa-edit"></i> <?php echo $title; ?></h3>

<div class="card">
    <div class="card-header">
        Form Edit Produk
    </div>
    <div class="card-body">
        <?php echo form_open_multipart('admin/produk/edit/' . $produk->id_produk); ?>

            <?php if(validation_errors()): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo validation_errors(); ?>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error_upload')): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $this->session->flashdata('error_upload'); ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" class="form-control <?php echo form_error('nama_produk') ? 'is-invalid' : ''; ?>" id="nama_produk" name="nama_produk" value="<?php echo set_value('nama_produk', $produk->nama_produk); ?>" required autofocus>
                <?php if(form_error('nama_produk')): ?><div class="invalid-feedback"><?php echo form_error('nama_produk'); ?></div><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="kategori_produk_id">Kategori Produk</label>
                <select class="form-control <?php echo form_error('kategori_produk_id') ? 'is-invalid' : ''; ?>" id="kategori_produk_id" name="kategori_produk_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori_list as $kategori): ?>
                        <option value="<?php echo $kategori->id_kategori; ?>" <?php echo set_select('kategori_produk_id', $kategori->id_kategori, ($produk->kategori_produk_id == $kategori->id_kategori)); ?>><?php echo htmlspecialchars($kategori->nama_kategori); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if(form_error('kategori_produk_id')): ?><div class="invalid-feedback"><?php echo form_error('kategori_produk_id'); ?></div><?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="harga">Harga (Rp)</label>
                    <input type="number" class="form-control <?php echo form_error('harga') ? 'is-invalid' : ''; ?>" id="harga" name="harga" value="<?php echo set_value('harga', $produk->harga); ?>" required min="0" step="1">
                    <?php if(form_error('harga')): ?><div class="invalid-feedback"><?php echo form_error('harga'); ?></div><?php endif; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="stok">Stok</label>
                    <input type="number" class="form-control <?php echo form_error('stok') ? 'is-invalid' : ''; ?>" id="stok" name="stok" value="<?php echo set_value('stok', $produk->stok); ?>" required min="0">
                    <?php if(form_error('stok')): ?><div class="invalid-feedback"><?php echo form_error('stok'); ?></div><?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="deskripsi_produk">Deskripsi Produk (Opsional)</label>
                <textarea class="form-control <?php echo form_error('deskripsi_produk') ? 'is-invalid' : ''; ?>" id="deskripsi_produk" name="deskripsi_produk" rows="3"><?php echo set_value('deskripsi_produk', $produk->deskripsi_produk); ?></textarea>
                <?php if(form_error('deskripsi_produk')): ?><div class="invalid-feedback"><?php echo form_error('deskripsi_produk'); ?></div><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="gambar_produk">Ganti Gambar Produk (Opsional)</label>
                <input type="file" class="form-control-file <?php echo form_error('gambar_produk') ? 'is-invalid' : ''; ?>" id="gambar_produk" name="gambar_produk">
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti gambar. Tipe file: gif, jpg, png, jpeg. Maks 2MB.</small>
                <?php if(form_error('gambar_produk')): ?><div class="invalid-feedback d-block"><?php echo form_error('gambar_produk'); ?></div><?php endif; ?>
                <?php if ($produk->gambar_produk && file_exists('./uploads/produk/' . $produk->gambar_produk)): ?>
                    <div class="mt-2">
                        <img src="<?php echo base_url('uploads/produk/' . $produk->gambar_produk); ?>" alt="<?php echo htmlspecialchars($produk->nama_produk); ?>" style="width: 100px; height: auto;">
                        <p><small>Gambar saat ini.</small></p>
                    </div>
                <?php elseif ($produk->gambar_produk == 'default.jpg' || !$produk->gambar_produk): ?>
                     <div class="mt-2">
                        <img src="<?php echo base_url('uploads/produk/default.jpg'); ?>" alt="Default Image" style="width: 100px; height: auto;">
                         <p><small>Gambar default.</small></p>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Update Produk
            </button>
            <a href="<?php echo base_url('admin/produk'); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        <?php echo form_close(); ?>
    </div>
</div>
```
