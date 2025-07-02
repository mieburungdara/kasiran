<h3 class="mt-0 mb-4 text-primary"><i class="fas fa-edit"></i> <?php echo $title; ?></h3>

<div class="card">
    <div class="card-header">
        Form Edit Kategori Produk
    </div>
    <div class="card-body">
        <?php echo form_open('admin/kategori/edit/' . $kategori->id_kategori); ?>

            <?php if(validation_errors()): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo validation_errors(); ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" class="form-control <?php echo form_error('nama_kategori') ? 'is-invalid' : ''; ?>" id="nama_kategori" name="nama_kategori" value="<?php echo set_value('nama_kategori', $kategori->nama_kategori); ?>" required autofocus>
                <?php if(form_error('nama_kategori')): ?>
                    <div class="invalid-feedback">
                        <?php echo form_error('nama_kategori'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Update Kategori
            </button>
            <a href="<?php echo base_url('admin/kategori'); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        <?php echo form_close(); ?>
    </div>
</div>
