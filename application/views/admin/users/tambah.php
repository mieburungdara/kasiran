<h3 class="mt-0 mb-4 text-primary"><i class="fas fa-user-plus"></i> <?php echo $title; ?></h3>

<div class="card">
    <div class="card-header">
        Form Tambah Pengguna Baru
    </div>
    <div class="card-body">
        <?php echo form_open('admin/users/tambah'); ?>

            <?php if(validation_errors()): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo validation_errors(); ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" class="form-control <?php echo form_error('nama_lengkap') ? 'is-invalid' : ''; ?>" id="nama_lengkap" name="nama_lengkap" value="<?php echo set_value('nama_lengkap'); ?>" required autofocus>
                <?php if(form_error('nama_lengkap')): ?><div class="invalid-feedback"><?php echo form_error('nama_lengkap'); ?></div><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control <?php echo form_error('username') ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo set_value('username'); ?>" required>
                <small class="form-text text-muted">Minimal 4 karakter, hanya huruf, angka, underscore, dan dash.</small>
                <?php if(form_error('username')): ?><div class="invalid-feedback"><?php echo form_error('username'); ?></div><?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="password">Password</label>
                    <input type="password" class="form-control <?php echo form_error('password') ? 'is-invalid' : ''; ?>" id="password" name="password" required>
                    <small class="form-text text-muted">Minimal 6 karakter.</small>
                    <?php if(form_error('password')): ?><div class="invalid-feedback"><?php echo form_error('password'); ?></div><?php endif; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="passconf">Konfirmasi Password</label>
                    <input type="password" class="form-control <?php echo form_error('passconf') ? 'is-invalid' : ''; ?>" id="passconf" name="passconf" required>
                    <?php if(form_error('passconf')): ?><div class="invalid-feedback"><?php echo form_error('passconf'); ?></div><?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="level">Level Pengguna</label>
                <select class="form-control <?php echo form_error('level') ? 'is-invalid' : ''; ?>" id="level" name="level" required>
                    <option value="">-- Pilih Level --</option>
                    <option value="admin" <?php echo set_select('level', 'admin'); ?>>Admin</option>
                    <option value="kasir" <?php echo set_select('level', 'kasir'); ?>>Kasir</option>
                </select>
                <?php if(form_error('level')): ?><div class="invalid-feedback"><?php echo form_error('level'); ?></div><?php endif; ?>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan Pengguna
            </button>
            <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        <?php echo form_close(); ?>
    </div>
</div>
